<?php
// Set explicit content type header
header('Content-Type: text/html; charset=UTF-8');

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
        $content = 'views/eventlist.php';
        $pageTitle = 'View Event Details';
        break;

    case 'NOTES':
        $content = 'views/eventnotes.php';
        $pageTitle = 'Create Notes';
        break;

    case 'USERS':
        $content = 'views/userlist.php';
        $pageTitle = 'View User Details';
        break;

    case 'USER':
        $content = 'views/user.php';
        $pageTitle = 'View User Details';
        break;

    case 'CREATE':
        $content = 'views/userform.php';
        $pageTitle = 'Create New User';
        break;

    case 'BLOCK':
        $content = 'views/block.php';
        $pageTitle = 'View Block Details';
        break;

    case 'BLOCK_CREATE':
        $content = 'views/blockform.php';
        $pageTitle = 'Create New Block';
        break;

    default:
        $content = 'views/dashboard.php';
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
    $blockId   = $_POST['business_block_id'];
    $functionSpace = $_POST['function_space'] ?? '';
    $startDatetime = $_POST['start_datetime'];
    $endDatetime = $_POST['end_datetime'];
    $pax = $_POST['pax'] ?? 0;
    $rental = $_POST['rental'] ?? 'Exclude';

    $sql = "INSERT INTO event_bookings (business_block_id, event_name, function_space, start_datetime, end_datetime, pax, rental)
            VALUES ('$blockId', '$eventName', '$functionSpace', '$startDatetime', '$endDatetime', '$pax', '$rental')";
    dbQuery($sql);

    header('Location: ../views/?v=LIST&msg=' . urlencode('Event successfully created.'));
    exit();
}

function createBlock() {
    $blockName = $_POST['block_name'];
    $accountType = $_POST['account_type'] ?? 'Company';
    $accountName = $_POST['account_name'] ?? '';
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $ownerEvent = $_POST['owner_event'] ?? '';
    $startDate = $_POST['start_date'];
    $endDate   = $_POST['end_date'];
    $ownerId = $_SESSION['calendar_fd_user']['id'] ?? 1;

    $sql = "INSERT INTO tbl_business_blocks (block_name, account_type, account_name, address, phone, owner_event, start_date, end_date, owner_id)
            VALUES ('$blockName', '$accountType', '$accountName', '$address', '$phone', '$ownerEvent', '$startDate', '$endDate', '$ownerId')";
    dbQuery($sql);

    header('Location: ../views/?v=BLOCK&msg=' . urlencode('Block successfully created.'));
    exit();
}

function addNotes() {
    $eventBookingId = $_POST['event_booking_id'];
    $department = $_POST['department'];
    $note = $_POST['note'];

    $sql = "INSERT INTO event_notes (event_booking_id, department, note)
            VALUES ('$eventBookingId', '$department', '$note')";
    dbQuery($sql);

    header('Location: ../views/?v=NOTES&msg=' . urlencode('Note added.'));
    exit();
}
?>
