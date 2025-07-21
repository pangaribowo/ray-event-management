<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
  <ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>
    <li class="treeview"> 
		<a href="<?php echo WEB_ROOT; ?>views/?v=DB"><i class="fa fa-calendar"></i><span>Events Calendar</span></a>
	</li>
    <li class="treeview"> 
		<a href="<?php echo WEB_ROOT; ?>views/?v=LIST"><i class="fa fa-newspaper-o"></i><span>Events Booking</span></a>
	</li>
	<li class="treeview">
		<a href="<?php echo WEB_ROOT; ?>views/?v=BLOCK"><i class="fa fa-building"></i><span> Business Blocks</span></a>
	</li>
	<li class="treeview"> 
		<a href="<?php echo WEB_ROOT; ?>views/?v=USERS"><i class="fa fa-users"></i><span>Users</span></a>
	</li>
	<?php 
	$type = $_SESSION['calendar_fd_user']['type'];
	if($type == 'admin') {
	?>
	<?php
	}
	?>
  </ul>
</section>
<!-- /.sidebar -->