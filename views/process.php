<?php 

require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/mail.php';

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
	$name 		= $_POST['name'];
	$email 		= $_POST['email'];
	$phone 		= $_POST['phone'];
	$role		= $_POST['role'];
	$position	= $_POST['position'];
	
	//send email on registration confirmation
	$bodymsg = "User $name booked the date slot on $bkdate. Requesting you to please take further action on user booking.<br/>Mbr/>Tousif Khan";
	$data = array('to' => '$email', 'sub' => 'Booking on $rdate.', 'msg' => $bodymsg);
	//send_email($data);
	header('Location: ../views/?v=USERS&msg=' . urlencode('User successfully registered.'));
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
	$eventName = $_POST['event_name'];
	$blockId = $_POST['block_id'];
	$ownerName = $_POST['owner'];

	// Simpan ke session atau database, sesuai kebutuhan
	$_SESSION['event_data'] = [
		'event_name' => $eventName,
		'block_id' => $blockId,
		'owner' => $ownerName
	];

	// Redirect ke halaman notes
	header('Location: ../views/?v=LIST&msg=' . urlencode('Event successfully created.'));
	exit();
}

function createBlock() {
	$blockName = $_POST['block_name'];
	$startDate = $_POST['start_date'];
	$endDate = $_POST['end_date'];

	// Simpan ke session atau database, sesuai kebutuhan
	$_SESSION['block_data'] = [
		'block_name' => $blockName,
		'start_date' => $startDate,
		'end_date' => $endDate
	];

	// Redirect ke halaman notes
	header('Location: ../index.php?v=BLOCKS');
	exit();
}


function addNotes() {
	if ($_GET['cmd'] == 'addnotes') {
		$notes = $_POST['notes'];
		// Simpan ke session atau database, sesuai kebutuhan
		$_SESSION['notes_data'] = $notes;
		// Redirect ke halaman notes
		header('Location: ../index.php?v=NOTES');
		exit();
	}
}

require_once '../library/database.php'; // Pastikan path ini benar

if (isset($_GET['cmd']) && $_GET['cmd'] === 'eventlist') {
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
        echo "✅ Data event berhasil disimpan.";
    } catch (Exception $e) {
        $dbConn->rollBack();
        echo "❌ Terjadi kesalahan: " . $e->getMessage();
    }
}

?>