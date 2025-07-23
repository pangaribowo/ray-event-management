<?php 
// Start output buffering to prevent any accidental output
ob_start();

require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/mail.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cmd = isset($_GET['cmd']) ? $_GET['cmd'] : '';

switch($cmd) {
	
	case 'create':
		createUser();
	break;
	
	case 'change':
		changeStatus();
	break;

	case 'create_event':
	createEvent();
	break;

	case 'create_block':
	createBlock();
	break;

	case 'addnotes':
	addNotes();
	break;

	
	default :
	break;
}

function createUser() {
	$name 		= $_POST['name'] ?? '';
	$email 		= $_POST['email'] ?? '';
	$phone 		= $_POST['phone'] ?? '';
	$password	= $_POST['password'] ?? '';
	$role		= $_POST['role'] ?? 'Sales';
	$position	= $_POST['position'] ?? '';
	
	// Validasi input
	if (empty($name) || empty($email) || empty($phone) || empty($password)) {
		header('Location: ../views/?v=CREATE&error=' . urlencode('Semua field wajib diisi'));
		exit();
	}
	
	// Hash password untuk keamanan
	$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
	
	// Simpan ke database dengan struktur tabel users yang baru
	try {
		$sql = "INSERT INTO users (name, pwd, email, phone, role, position) 
				VALUES ('$name', '$hashedPassword', '$email', '$phone', '$role', '$position')";
		$result = dbQuery($sql);
		
		if ($result) {
			header('Location: ../views/?v=USERS&msg=' . urlencode('User berhasil didaftarkan.'));
		} else {
			global $dbConn;
			$error = mysqli_error($dbConn);
			header('Location: ../views/?v=CREATE&error=' . urlencode('Gagal menyimpan user: ' . $error));
		}
	} catch (Exception $e) {
		header('Location: ../views/?v=CREATE&error=' . urlencode('Error: ' . $e->getMessage()));
	}
	exit();
}

//http://localhost/houda/views/process.php?cmd=change&action=inactive&userId=1
function changeStatus() {
	$action 	= $_GET['action'];
	$userId 	= (int)$_GET['userId'];
	
	
	$sql = "UPDATE tbl_users SET status = '$action' WHERE id = $userId";	
	dbQuery($sql);
	
	//send email on registration confirmation
	$bodymsg = "User $name booked the date slot on $bkdate. Requesting you to please take further action on user booking.<br/>Mbr/>Tousif Khan";
	$data = array('to' => '$email', 'sub' => 'Booking on $rdate.', 'msg' => $bodymsg);
	//send_email($data);
	header('Location: ../views/?v=USERS&msg=' . urlencode('User status successfully updated.'));
	exit();
}

function createEvent() {
	$eventName = $_POST['event_name'] ?? '';
	$blockId = $_POST['block_id'] ?? '';
	$ownerId = $_POST['userId'] ?? '';
	$ownerName = $_POST['owner_name'] ?? '';
	$functionSpace = $_POST['function_space'] ?? '';
	$startDate = $_POST['start_date'] ?? '';
	$startTime = $_POST['stime'] ?? '';
	$endDate = $_POST['end_date'] ?? $_POST['start_date']; // Fallback if end_date not set
	$endTime = $_POST['etime'] ?? '';
	$pax = $_POST['pax'] ?? 0;
	$rental = $_POST['rental'] ?? 'Exclude';
	
	// Validasi input
	if (empty($blockId)) {
		header('Location: ../views/?v=EVENTFORM&error=' . urlencode('Block ID harus dipilih'));
		exit();
	}
	
	// Cek apakah block_id ada di database
	$checkSql = "SELECT id FROM tbl_business_blocks WHERE id = '$blockId'";
	$checkResult = dbQuery($checkSql);
	if (dbNumRows($checkResult) == 0) {
		header('Location: ../views/?v=EVENTFORM&error=' . urlencode('Block ID tidak ditemukan dalam database'));
		exit();
	}
	
	// Combine date and time for datetime fields
	$startDateTime = $startDate . ' ' . $startTime . ':00';
	$endDateTime = $endDate . ' ' . $endTime . ':00';

	// Save to database
	$sql = "INSERT INTO event_bookings (business_block_id, event_name, function_space, start_datetime, end_datetime, pax, rental) 
			VALUES ('$blockId', '$eventName', '$functionSpace', '$startDateTime', '$endDateTime', '$pax', '$rental')";
	
	$result = dbQuery($sql);
	if ($result) {
		header('Location: ../views/?v=LIST&msg=' . urlencode('Event successfully created.'));
	} else {
		// Get the actual MySQL error
		global $dbConn;
		$error = mysqli_error($dbConn);
		header('Location: ../views/?v=DB&error=' . urlencode('Failed to create event: ' . $error));
	}
	exit();
}

function createBlock() {
	$blockName = $_POST['block_name'] ?? '';
	$accountType = $_POST['account_type'] ?? '';
	$accountName = $_POST['account_name'] ?? '';
	$alamat = $_POST['alamat'] ?? '';
	$telepon = $_POST['telepon'] ?? '';
	$ownerEvent = $_POST['owner_event'] ?? '';
	$startDate = $_POST['start_date'] ?? '';
	$endDate = $_POST['end_date'] ?? '';
	$revenueRoom = $_POST['revenue_room'] ?? 0;
	$revenueCatering = $_POST['revenue_catering'] ?? 0;
	$status = $_POST['status'] ?? 'ACT';
	$ownerId = $_SESSION['calendar_fd_user']['id'] ?? 1; // Default owner_id

	// Simpan ke database tbl_business_blocks
	try {
		$sql = "INSERT INTO tbl_business_blocks (block_name, account_type, account_name, address, phone, owner_event, start_date, end_date, revenue_room, revenue_catering, status, owner_id) 
				VALUES ('$blockName', '$accountType', '$accountName', '$alamat', '$telepon', '$ownerEvent', '$startDate', '$endDate', '$revenueRoom', '$revenueCatering', '$status', '$ownerId')";
		$result = dbQuery($sql);
		
		// Ambil ID yang baru saja dibuat
		$blockId = mysqli_insert_id($GLOBALS['dbConn']);
		
		// Simpan contact PIC jika ada
		$picFirstName = $_POST['pic_first_name'] ?? '';
		$picLastName = $_POST['pic_last_name'] ?? '';
		$picPosition = $_POST['pic_position'] ?? '';
		$picAlamat = $_POST['pic_alamat'] ?? '';
		$picTelepon = $_POST['pic_telepon'] ?? '';
		$picEmail = $_POST['pic_email'] ?? '';
		$picFax = $_POST['pic_fax'] ?? '';
		
		if ($picFirstName && $picLastName) {
			$sqlContact = "INSERT INTO contacts (business_block_id, first_name, last_name, position, address, phone, email, fax) 
						VALUES ('$blockId', '$picFirstName', '$picLastName', '$picPosition', '$picAlamat', '$picTelepon', '$picEmail', '$picFax')";
			dbQuery($sqlContact);
		}
		
		header('Location: ../views/?v=BLOCKS&msg=' . urlencode('Block berhasil dibuat dengan ID: ' . $blockId));
	} catch (Exception $e) {
		header('Location: ../views/?v=BLOCKS&error=' . urlencode('Gagal membuat block: ' . $e->getMessage()));
	}
	exit();
}


function addNotes() {
	if ($_GET['cmd'] == 'addnotes') {
		$notes = $_POST['notes'] ?? [];
		// Simpan ke session atau database, sesuai kebutuhan
		$_SESSION['notes_data'] = $notes;
		// Redirect ke halaman notes
		header('Location: ../index.php?v=NOTES');
		exit();
	}
}

require_once '../library/database.php'; // Pastikan path ini benar

if (isset($_GET['cmd']) && $_GET['cmd'] === 'eventlist') {
    // Clear any previous output and set JSON header
    ob_clean();
    header('Content-Type: application/json');
    
    // Ambil data dari form
    $eventName      = $_POST['event_name'] ?? '';
    $blockId        = $_POST['block_id'] ?? ''; // Sesuaikan name input: "block_id" bukan "business_block_id"
    $ownerId        = $_POST['owner_id'] ?? '';
    $ownerName      = $_POST['owner_name'] ?? '';
    $functionSpace  = $_POST['function_space'] ?? '';
    $bkDate         = $_POST['bkDate'] ?? '';
    $bkTime         = $_POST['bkTime'] ?? '';
    $pax            = $_POST['pax'] ?? '';
    $rental         = $_POST['rental'] ?? '';
    $status         = $_POST['status'] ?? '';
    $notes          = $_POST['notes'] ?? [];

    try {
        $dbConn->beginTransaction();

        // Simpan ke tabel event_bookings
        $stmt = $dbConn->prepare("
            INSERT INTO event_bookings (
                event_name, block_id, owner_id, owner_name,
                function_space, booking_date, booking_time,
                pax, rental, status
            ) VALUES (
                :event_name, :block_id, :owner_id, :owner_name,
                :function_space, :booking_date, :booking_time,
                :pax, :rental, :status
            )
        ");

        $stmt->execute([
            ':event_name'     => $eventName,
            ':block_id'       => $blockId,
            ':owner_id'       => $ownerId,
            ':owner_name'     => $ownerName,
            ':function_space' => $functionSpace,
            ':booking_date'   => $bkDate,
            ':booking_time'   => $bkTime,
            ':pax'            => $pax,
            ':rental'         => $rental,
            ':status'         => $status
        ]);

        $eventId = $dbConn->lastInsertId(); // Ambil ID event terakhir

        // Simpan catatan per divisi ke tabel event_notes
        $stmtNote = $dbConn->prepare("
            INSERT INTO event_notes (event_booking_id, department, note)
            VALUES (:event_booking_id, :department, :note)
        ");
        foreach ($notes as $dept => $note) {
            $stmtNote->execute([
                ':event_booking_id' => $eventId,
                ':department'       => $dept,
                ':note'             => $note
            ]);
        }

        $dbConn->commit();
        echo json_encode(['success' => true, 'message' => 'Data event berhasil disimpan.', 'event_id' => $eventId]);
    } catch (Exception $e) {
        $dbConn->rollBack();
        echo json_encode(['success' => false, 'error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
    exit();
}

?>