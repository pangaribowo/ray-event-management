<?php
require_once '../library/config.php';
require_once '../library/functions.php';

checkFDUser();

$view = (isset($_GET['v']) && $_GET['v'] != '') ? $_GET['v'] : '';

switch ($view) {
	case 'LIST' :
		$content 	= 'eventlist.php';		
		$pageTitle 	= 'View Event Details';
		break;
		
	case 'NOTES' :
		$content 	= 'eventnotes.php';		
		$pageTitle 	= 'Create Notes';
		break;

	case 'USERS' :
		$content 	= 'userlist.php';		
		$pageTitle 	= 'View User Details';
		break;

	case 'USER':
   		 $content = 'user.php';
   		 $pageTitle = 'View User Details';
   		 break;
		
	case 'CREATE' :
		$content 	= 'userform.php';		
		$pageTitle 	= 'Create New User';
		break;
	
	case 'BLOCK' :
		$content 	= 'block.php';	
		$pageTitle 	= 'View Block Details';
		break;
	
	case 'BLOCK_CREATE' :
		$content 	= 'blockform.php';		
		$pageTitle 	= 'Create New Block';
		break;
	
	default :
		$content 	= 'dashboard.php';		
		$pageTitle 	= 'Calendar Dashboard';
}

require_once '../include/template.php';
?>
