<?php 

require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/mail.php';

session_start();

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

  $sql = "INSERT INTO business_blocks_id 
          (block_name, account_type, account_name, alamat, telepon, owner_event, date_start, date_end, revenue_room, revenue_catering, status, 
           pic_first_name, pic_last_name, pic_position, pic_alamat, pic_telepon, pic_email, pic_fax)
          VALUES 
          ('$blockName', '$accountType', '$accountName', '$alamat', '$telepon', '$ownerEvent', '$dateStart', '$dateEnd', 
           '$revenueRoom', '$revenueCat', '$status',
           '$picFirstName', '$picLastName', '$picPosition', '$picAlamat', '$picTelepon', '$picEmail', '$picFax')";
  dbQuery($sql);

  header('Location: ../views/?v=BLOCK_LIST&msg=' . urlencode('Block ID berhasil ditambahkan.'));
  exit();
}
