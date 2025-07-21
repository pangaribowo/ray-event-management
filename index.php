<?php
require_once 'library/config.php';
require_once 'library/functions.php';
require_once 'library/mail.php'; // jika digunakan


if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Pastikan session aktif
}
checkFDUser();
// Tangkap parameter dari URL untuk tampilan dan tindakan
$view = $_GET['v'] ?? '';
$cmd  = $_GET['cmd'] ?? '';

switch ($cmd) {
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

switch ($view) {
    case 'LIST':
        $content = 'eventlist.php';
        $pageTitle = 'View Event Details';
        break;

    case 'NOTES':
        $content = 'eventnotes.php';
        $pageTitle = 'Create Notes';
        break;

    case 'USERS':
        $content = 'userlist.php';
        $pageTitle = 'View User Details';
        break;

    case 'USER':
        $content = 'user.php';
        $pageTitle = 'View User Details';
        break;

    case 'CREATE':
        $content = 'userform.php';
        $pageTitle = 'Create New User';
        break;

    case 'BLOCK':
        $content = 'block.php';
        $pageTitle = 'View Block Details';
        break;

    case 'BLOCK_CREATE':
        $content = 'blockform.php';
        $pageTitle = 'Create New Block';
        break;

    default:
        $content = 'dashboard.php';
        $pageTitle = 'Calendar Dashboard';
}

require_once 'include/template.php';

// -------------------------
// FUNGSI-FUNGSI
// -------------------------

function createUser() {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $role     = $_POST['role'];
    $position = $_POST['position'];

    $sql = "INSERT INTO tbl_users (name, email, phone, role, position, status)
            VALUES ('$name', '$email', '$phone', '$role', '$position', 'active')";
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
    $eventName = $_POST['event_name'];
    $blockId   = $_POST['block_id'];
    $owner     = $_POST['owner'];

    $sql = "INSERT INTO tbl_events (event_name, block_id, owner)
            VALUES ('$eventName', '$blockId', '$owner')";
    dbQuery($sql);

    header('Location: ../views/?v=LIST&msg=' . urlencode('Event successfully created.'));
    exit();
}

function createBlock() {
    $blockName = $_POST['block_name'];
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];

    $sql = "INSERT INTO business_blocks_id (block_name, start_date, end_date)
            VALUES ('$blockName', '$startDate', '$endDate')";
    dbQuery($sql);

    header('Location: ../views/?v=BLOCK&msg=' . urlencode('Block successfully created.'));
    exit();
}

function addNotes() {
    $notes = $_POST['notes'];

    $sql = "INSERT INTO event_notes (notes, created_at)
            VALUES ('$notes', NOW())";
    dbQuery($sql);

    header('Location: ../views/?v=NOTES&msg=' . urlencode('Note added.'));
    exit();
}
?>
