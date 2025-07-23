<?php
require_once('mail.php');


function random_string($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return strtoupper($randomString);
}

/*
	Check if a session user id exist or not. If not set redirect
	to login page. If the user session id exist and there's found
	$_GET['logout'] in the query string logout the user
*/
function checkFDUser()
{
	// if the session id is not set, redirect to login page
	if (!isset($_SESSION['calendar_fd_user'])) {
		header('Location: ' . WEB_ROOT . 'login.php');
		exit;
	}
	// the user want to logout
	if (isset($_GET['logout'])) {
		doLogout();
	}
}

function doLogin()
{
	$name 	= $_POST['name'];
	$pwd 	= $_POST['pwd'];
	
	$errorMessage = '';
	
	// Coba tabel users yang baru terlebih dahulu
	$sql = "SELECT * FROM users WHERE name = '$name'";
	$result = dbQuery($sql);
	
	if (dbNumRows($result) == 1) {
		$row = dbFetchAssoc($result);
		// Cek password dengan hashing
		if (password_verify($pwd, $row['pwd'])) {
			$_SESSION['calendar_fd_user'] = $row;
			$_SESSION['calendar_fd_user']['type'] = strtolower($row['role']); // Untuk kompatibilitas
			$_SESSION['calendar_fd_user_name'] = $row['name'];
			header('Location: index.php');
			exit();
		} else {
			$errorMessage = 'Invalid username / password. Please try again or contact support.';
		}
	} else {
		// Fallback ke tabel lama jika tidak ditemukan di tabel baru
		$sql = "SELECT * FROM tbl_users WHERE name = '$name' AND pwd = '$pwd'";
		$result = dbQuery($sql);
		
		if (dbNumRows($result) == 1) {
			$row = dbFetchAssoc($result);
			$_SESSION['calendar_fd_user'] = $row;
			$_SESSION['calendar_fd_user_name'] = $row['name'];
			header('Location: index.php');
			exit();
		} else {
			$errorMessage = 'Invalid username / password. Please try again or contact support.';
		}
	}
	return $errorMessage;
}


/*
	Logout a user
*/
function doLogout()
{
	if (isset($_SESSION['calendar_fd_user'])) {
		unset($_SESSION['calendar_fd_user']);
		//session_unregister('hlbank_user');
	}
	header('Location: index.php');
	exit();
}

function getBookingRecords(){
	$per_page = 10;
	$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 1;
	$start 	= ($page-1)*$per_page;
	
	$sql = "SELECT 
				eb.id,
				eb.event_name,
				eb.business_block_id as block_id,
				eb.function_space,
				DATE(eb.start_datetime) as res_date,
				TIME(eb.start_datetime) as time_start,
				TIME(eb.end_datetime) as time_end,
				eb.pax as count,
				eb.rental,
				eb.status,
				bb.account_name,
				bb.owner_event as owner_name,
				bb.owner_id as user_id
			FROM event_bookings eb
			LEFT JOIN tbl_business_blocks bb ON eb.business_block_id = bb.id
			ORDER BY eb.id DESC 
			LIMIT $start, $per_page";
	
	$result = dbQuery($sql);
	$records = array();
	while($row = dbFetchAssoc($result)) {
		extract($row);
		$records[] = array(
			"user_id" => $user_id,
			"owner_name" => $owner_name,
			"block_id" => $block_id,
			"account_name" => $account_name,
			"event_name" => $event_name,
			"function_space" => $function_space,
			"res_date" => $res_date,
			"time_start" => $time_start,
			"time_end" => $time_end,
			"count" => $count,
			"rental" => $rental,
			"status" => $status
		);	
	}//while
	return $records;
}


function getUserRecords(){
	$per_page = 20;
	$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 1;
	$start 	= ($page-1)*$per_page;
	
	$type = $_SESSION['calendar_fd_user']['type'] ?? 'sales';
	if($type == 'sales') {
		$id = $_SESSION['calendar_fd_user']['id'] ?? 1;
		$sql = "SELECT  * FROM users u WHERE role != 'Admin' AND id = $id ORDER BY u.id DESC";
	}
	else {
		$sql = "SELECT  * FROM users u WHERE role != 'Admin' ORDER BY u.id DESC LIMIT $start, $per_page";
	}
	
	$result = dbQuery($sql);
	$records = array();
	while($row = dbFetchAssoc($result)) {
		extract($row);
		$records[] = array(
			"user_id" => $id,
			"user_name" => $name,
			"user_phone" => $phone,
			"user_email" => $email,
			"type" => $role,
			"status" => 'active', // Default status karena tidak ada field status di tabel users baru
			"bdate" => $created_at ?? date('Y-m-d H:i:s')
		);	
	}
	return $records;
}

function generatePagination(){
	$per_page = 10;
	$sql 	= "SELECT * FROM tbl_users";
	$result = dbQuery($sql);
	$count 	= dbNumRows($result);
	$pages 	= ceil($count/$per_page);
	$pageno = '<ul class="pagination pagination-sm no-margin pull-right">';
	for($i=1; $i<=$pages; $i++)	{
	//<li><a href="#">1</a></li>
		//$pageno .= "<a href=\"?v=USER&page=$i\"><li id=\".$i.\">".$i."</li></a> ";
		$pageno .= "<li><a href=\"?v=USER&page=$i\">".$i."</a></li>";
	}
	$pageno .= 	"</ul>";
	return $pageno;
}

?>