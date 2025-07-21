<div class="col-md-8">
  <?php include('calendar.php'); ?>
</div>
<!-- /.col -->
<div class="col-md-4">
<?php 
$type = $_SESSION['calendar_fd_user']['type'];
if($type == 'admin' || $type == 'manager' || $type == 'sales') {
	include('eventform.php');
}
else {
	echo "&nbsp;";
}
?>	
</div>
<!-- /.col -->
