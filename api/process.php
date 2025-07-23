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

  case 'user':
    getUserData();
    break;

  case 'calview':
    getCalendarEvents();
    break;

  default:
    break;
}

// =============================
// Fungsi-Fungsi
// =============================

function createUser() {
  $name     = $_POST['name'];
  $email    = $_POST['email'];
  $phone    = $_POST['phone'];
  $role     = $_POST['role'];
  $position = $_POST['position'];

  $sql = "INSERT INTO tbl_users (name, email, phone, role, position) 
          VALUES ('$name', '$email', '$phone', '$role', '$position')";
  dbQuery($sql);

  header('Location: ../views/?v=USERS&msg=' . urlencode('User successfully registered.'));
  exit();
}

function changeStatus() {
  $action = $_GET['action'];
  $userId = (int)$_GET['userId'];

  $sql = "UPDATE tbl_users SET status = '$action' WHERE id = $userId";
  dbQuery($sql);

  header('Location: ../views/?v=USERS&msg=' . urlencode('User status successfully updated.'));
  exit();
}

function createEvent() {
  $eventName = $_POST['event_name'] ?? '';
  $blockId   = $_POST['block_id'] ?? '';
  $ownerName = $_POST['owner_name'] ?? '';
  $ownerId   = $_POST['owner_id'] ?? '';

  $_SESSION['event_data'] = [
    'event_name' => $eventName,
    'block_id'   => $blockId,
    'owner_name' => $ownerName,
    'owner_id'   => $ownerId
  ];

  // Redirect ke halaman form catatan tiap divisi
  header('Location: ../views/?v=ADD_NOTES');
  exit();
}

function addNotes() {
  if (!isset($_SESSION['event_data'])) {
    header('Location: ../views/?v=EVENT_FORM&msg=' . urlencode('Event data missing.'));
    exit();
  }

  $event = $_SESSION['event_data'];
  $notes = $_POST['notes'] ?? [];

  foreach ($notes as $department => $note) {
    $department_clean = addslashes($department);
    $note_clean = addslashes($note);
    
    $sql = "INSERT INTO tbl_event_notes (event_name, block_id, owner, department, note)
            VALUES (
              '{$event['event_name']}', 
              '{$event['block_id']}', 
              '{$event['owner_name']}', 
              '$department_clean', 
              '$note_clean'
            )";
    dbQuery($sql);
  }

  unset($_SESSION['event_data']);

  header('Location: ../views/?v=CALENDAR&msg=' . urlencode('Catatan berhasil disimpan.'));
  exit();
}

function createBlock() {
  $blockName   = $_POST['block_name'];
  $accountType = $_POST['account_type'];
  $accountName = $_POST['account_name'];
  $alamat      = $_POST['alamat'];
  $telepon     = $_POST['telepon'];
  $ownerEvent  = $_POST['owner_event'];
  $dateStart   = $_POST['date_start'];
  $dateEnd     = $_POST['date_end'];
  $revenueRoom = $_POST['revenue_room'];
  $revenueCat  = $_POST['revenue_catering'];
  $status      = $_POST['status'];

  // Data PIC
  $pic = $_POST['pic'];
  $picFirstName = $pic['first_name'] ?? '';
  $picLastName  = $pic['last_name'] ?? '';
  $picPosition  = $pic['position'] ?? '';
  $picAlamat    = $pic['alamat'] ?? '';
  $picTelepon   = $pic['telepon'] ?? '';
  $picEmail     = $pic['email'] ?? '';
  $picFax       = $pic['fax'] ?? '';

  $sql = "INSERT INTO tbl_business_blocks 
          (block_name, account_type, account_name, address, phone, owner_event, start_date, end_date, revenue_room, revenue_catering, status, owner_id)
          VALUES 
          ('$blockName', '$accountType', '$accountName', '$alamat', '$telepon', '$ownerEvent', '$dateStart', '$dateEnd', 
           '$revenueRoom', '$revenueCat', '$status', 1)";
  dbQuery($sql);

  header('Location: ../views/?v=BLOCK_LIST&msg=' . urlencode('Block ID berhasil ditambahkan.'));
  exit();
}

function getUserData() {
  // Clear any previous output
  ob_clean();
  
  // Set JSON header
  header('Content-Type: application/json');
  
  $userId = isset($_GET['userId']) ? (int)$_GET['userId'] : 0;
  
  if ($userId <= 0) {
    echo json_encode(['error' => 'Invalid user ID']);
    exit();
  }
  
  try {
    $sql = "SELECT id as user_id, name, email, phone as phone_no, address 
            FROM tbl_users WHERE id = $userId";
    $result = dbQuery($sql);
    
    if ($row = dbFetchAssoc($result)) {
      echo json_encode($row);
    } else {
      echo json_encode(['error' => 'User not found']);
    }
  } catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
  }
  
  exit();
}

function getCalendarEvents() {
  // Clear any previous output
  ob_clean();
  
  // Set JSON header
  header('Content-Type: application/json');
  
  $start = $_POST['start'] ?? '';
  $end = $_POST['end'] ?? '';
  
  try {
    // Query to get events from database
    $sql = "SELECT 
              id,
              event_name as title,
              start_datetime as start,
              end_datetime as end,
              'false' as allDay,
              '#3c8dbc' as backgroundColor,
              '#367fa9' as borderColor
            FROM event_bookings 
            WHERE start_datetime >= '$start' AND end_datetime <= '$end'
            ORDER BY start_datetime";
    
    $result = dbQuery($sql);
    $events = [];
    
    while ($row = dbFetchAssoc($result)) {
      $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end'],
        'allDay' => false,
        'backgroundColor' => '#3c8dbc',
        'borderColor' => '#367fa9'
      ];
    }
    
    echo json_encode($events);
  } catch (Exception $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
  }
  
  exit();
}
