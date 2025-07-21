<?php
require_once '../library/database.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$type = $_SESSION['calendar_fd_user']['type'] ?? '';
$utype = ($type == 'admin') ? 'on' : 'off';

if ($search !== '') {
    $stmt = $dbConn->prepare("SELECT * FROM business_blocks_id WHERE block_name LIKE ? OR account_name LIKE ? ORDER BY start_date DESC");
    $likeSearch = "%" . $search . "%";
    $stmt->bind_param("ss", $likeSearch, $likeSearch);
} else {
    $stmt = $dbConn->prepare("SELECT * FROM business_blocks_id ORDER BY start_date DESC");
}
$stmt->execute();
$result = $stmt->get_result();

?>

<div class="col-md-12">
  <div class="box">
    <div class="box-header with-border">
      <h3 class="box-title">Daftar Business Block</h3>
    </div>

    <form role="form" action="<?php echo WEB_ROOT; ?>views/blockform.php" method="post">
    <div class="box-body">
      <form class="form-inline" onsubmit="return false;" style="margin-bottom: 20px;">
        <div class="form-group">
             <input type="text" id="searchInput" class="form-control" placeholder="Cari nama blok / akun">
        </div>
        <button type="button" class="btn btn-primary" onclick="filterTable()">Cari</button>
    </form>


      <table class="table table-bordered table-striped" id="blockTable">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Blok</th>
            <th>Akun</th>
            <th>Owner Event</th>
            <th>PIC</th>
            <th>Date Start</th>
            <th>Date End</th>
            <th>Revenue Room</th>
            <th>Revenue Catering</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <!-- data dari PHP -->
    </tbody>
          <?php if ($result && $result->num_rows > 0): ?>
            <?php 
            $idx = 1;
            while ($row = $result->fetch_assoc()):
              $statusLabel = strtolower($row['status']);
              switch ($statusLabel) {
                  case 'act': $labelClass = 'success'; break;
                  case 'ten': $labelClass = 'warning'; break;
                  case 'def': $labelClass = 'info'; break;
                  case 'cxl': $labelClass = 'danger'; break;
                  default: $labelClass = 'default';
              }
            ?>
              <tr>
                <td><?= $idx++ ?></td>
                <td><?= htmlspecialchars($row['block_name']) ?></td>
                <td><?= htmlspecialchars($row['account_name']) ?></td>
                <td><?= htmlspecialchars($row['owner_event']) ?></td>
                <td><?= date('d M Y', strtotime($row['date_start'])) ?> - <?= date('d M Y', strtotime($row['date_end'])) ?></td>
                <td><span class="label label-<?= $labelClass ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                <td><a href="block_detail.php?id=<?= urlencode($row['id']) ?>" class="btn btn-sm btn-info">Detail</a></td>
                <?php if ($utype == 'on'): ?>
                  <td>
                    <a href="javascript:editBlock('<?= $row['id'] ?>');">Edit</a> /
                    <a href="javascript:deleteBlock('<?= $row['id'] ?>');">Delete</a>
                  </td>
                <?php endif; ?>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
              <tr>
                <td colspan="<?= ($utype == 'on') ? '8' : '7' ?>" class="text-center">Tidak ada data ditemukan.</td>
              </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <div class="box-footer clearfix">
      <!-- Jika ada pagination bisa ditambahkan di sini -->

      <?php 
	    $type = $_SESSION['calendar_fd_user']['type'];
	    if($type == 'admin') {
	    ?>
	   <button type="button" class="btn btn-info" onclick="window.location.href='index.php?v=BLOCK_CREATE';">
     <i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;Create a new Block</button>

	    <?php 
      }
  ?>
      <?php /* echo generatePagination(); */ ?>
    </div>
  </div>
</div>


<script>
function editBlock(blockId) {
    if (confirm('Edit block ini?')) {
        window.location.href = 'block_edit.php?id=' + blockId;
    }
}
function deleteBlock(blockId) {
    if (confirm('Apakah Anda yakin ingin menghapus block ini?')) {
        window.location.href = '<?php echo WEB_ROOT; ?>api/process.php?cmd=deleteBlock&id=' + blockId;
    }
}
</script>

<?php
$stmt->close();
$dbConn->close();
?>
