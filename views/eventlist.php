<?php 
$records = getBookingRecords();
$utype = '';
$type = $_SESSION['calendar_fd_user']['type'];
if ($type == 'admin' || $type == 'manager' || $type == 'sales') {
    $utype = 'on';
}
?>

<div class="col-md-12">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Event Booking Details</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <table class="table table-bordered">
        <tr>
          <th>#</th>
          <th>Owner</th>
          <th>Block ID</th>
          <th>Event Name</th>
          <th>Function Space</th>
          <th>Booking Date</th>
          <th>Time</th>
          <th>Pax</th>
          <th>Rental</th>
          <th>Notes</th>
          <th>Status</th>
          <?php if ($utype == 'on') { ?>
            <th>Action</th>
          <?php } ?>
        </tr>
        <?php
        $idx = 1;
        foreach ($records as $rec) {
            extract($rec);
            $stat = '';
            if ($status == "PENDING") {
                $stat = 'warning';
            } else if ($status == "APPROVED") {
                $stat = 'success';
            } else if ($status == "DENIED") {
                $stat = 'danger';
            }
            ?>
            <tr>
              <td><?php echo $idx++; ?></td>
              <td><?php echo strtoupper($owner_name); ?></td>
              <td><?php echo $account_name; ?></td>
              <td><?php echo $block_id; ?></td>
              <td><?php echo $event_name; ?></td>
              <td><?php echo $function_space; ?></td>
              <td><?php echo ucfirst($rental); ?></td>
              <td><?php echo $res_date; ?></td>
              <td><?php echo $time_start; ?></td>
              <td><?php echo $time_end; ?></td>
              <td><?php echo $count; ?></td>
              <td><span class="label label-<?php echo $stat; ?>"><?php echo $status; ?></span></td>
              <?php if ($utype == 'on') { ?>
              <td>
                <?php if ($status == "PENDING") { ?>
                  <a href="javascript:approve('<?php echo $user_id ?>');">Approve</a> /
                  <a href="javascript:decline('<?php echo $user_id ?>');">Denied</a> /
                  <a href="javascript:deleteUser('<?php echo $user_id ?>');">Delete</a>
                <?php } else { ?>
                  <a href="javascript:deleteUser('<?php echo $user_id ?>');">Delete</a>
                <?php } ?>
              </td>
              <?php } ?>
            </tr>
        <?php } ?>
      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
      <?php 
	    $type = $_SESSION['calendar_fd_user']['type'];
	    if($type == 'admin') {
	    ?>
	    <button type="button" class="btn btn-info" onclick="window.location.href='index.php';">
      <i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;Create a new Event</button>
	    <?php 
      }
      ?>
      <?php echo generatePagination(); ?>
    </div>
  </div>
  <!-- /.box -->
</div>


<script language="javascript">
function approve(userId) {
    if (confirm('Are you sure you want to approve it?')) {
        window.location.href = '<?php echo WEB_ROOT; ?>api/process.php?cmd=regConfirm&action=approve&userId=' + userId;
    }
}
function decline(userId) {
    if (confirm('Are you sure you want to decline the booking?')) {
        window.location.href = '<?php echo WEB_ROOT; ?>api/process.php?cmd=regConfirm&action=denied&userId=' + userId;
    }
}
function deleteUser(userId) {
    if (confirm('Deleting user will also delete its booking from the calendar.\n\nAre you sure you want to proceed?')) {
        window.location.href = '<?php echo WEB_ROOT; ?>api/process.php?cmd=delete&userId=' + userId;
    }
}
</script>
