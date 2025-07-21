<?php
require_once '../library/config.php';
require_once '../library/database.php';

$type = $_SESSION['calendar_fd_user']['type'] ?? '';
$utype = ($type == 'admin') ? 'on' : 'off';

include '../include/head.php';


if (!isset($_SESSION['calendar_fd_user'])) {
  header('Location: ' . WEB_ROOT . 'login.php');
  exit;
}


?>

<div class="col-md-8">
  
<link href="<?php echo WEB_ROOT; ?>library/spry/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_ROOT; ?>library/spry/textfieldvalidation/SpryValidationTextField.js" type="text/javascript"></script>

<link href="<?php echo WEB_ROOT; ?>library/spry/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_ROOT; ?>library/spry/selectvalidation/SpryValidationSelect.js" type="text/javascript"></script>

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><b>Tambah Block ID</b></h3>
  </div>

   <form role="form" action="<?php echo WEB_ROOT; ?>views/process.php?cmd=createBlock" method="post">
    <div class="box-body">

      <div class="form-group">
        <label for="block_name">Nama Block</label>
        <span id="sprytf_block_name">
          <input type="text" name="block_name" id="block_name" class="form-control input-sm" placeholder="Nama Block" required>
          <span class="textfieldRequiredMsg">Nama Block wajib diisi.</span>
        </span>
      </div>

      <div class="form-group">
        <label for="account_type">Akun</label>
        <select name="account_type" id="account_type" class="form-control input-sm" required>
          <option value="">-- Pilih Jenis Account --</option>
          <option value="company">Company</option>
          <option value="travel_agent">Travel Agent</option>
        </select>
      </div>

      <div class="form-group">
        <label for="account_name">Nama Akun</label>
        <input type="text" name="account_name" id="account_name" class="form-control input-sm" placeholder="Nama Perusahaan / Travel" required>
      </div>

      <div class="form-group">
        <label for="alamat">Alamat</label>
        <textarea name="alamat" id="alamat" class="form-control input-sm" rows="2" placeholder="Alamat lengkap..." required></textarea>
      </div>

      <div class="form-group">
        <label for="telepon">Telepon</label>
        <input type="text" name="telepon" id="telepon" class="form-control input-sm" placeholder="Nomor Telepon" required>
      </div>

      <div class="form-group">
        <label for="owner_event">Owner Event</label>
        <input type="text" name="owner_event" id="owner_event" class="form-control input-sm" placeholder="Nama Penanggung Jawab / Sales">
      </div>

      <!-- Box Terpisah untuk Contact PIC -->
<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title"><b>Contact PIC</b></h3>
  </div>

  <div class="box-body">
    <div class="form-group">
      <label for="pic_first_name">First Name</label>
      <input type="text" name="pic_first_name" id="pic_first_name" class="form-control input-sm" placeholder="Nama Depan PIC" required>
    </div>

    <div class="form-group">
      <label for="pic_last_name">Last Name</label>
      <input type="text" name="pic_last_name" id="pic_last_name" class="form-control input-sm" placeholder="Nama Belakang PIC">
    </div>

    <div class="form-group">
      <label for="pic_position">Jabatan</label>
      <input type="text" name="pic_position" id="pic_position" class="form-control input-sm" placeholder="Jabatan PIC">
    </div>

    <div class="form-group">
      <label for="pic_alamat">Alamat</label>
      <textarea name="pic_alamat" id="pic_alamat" class="form-control input-sm" rows="2" placeholder="Alamat PIC"></textarea>
    </div>

    <div class="form-group">
      <label for="pic_telepon">Telepon</label>
      <input type="text" name="pic_telepon" id="pic_telepon" class="form-control input-sm" placeholder="Nomor Telepon PIC">
    </div>

    <div class="form-group">
      <label for="pic_email">Email</label>
      <input type="email" name="pic_email" id="pic_email" class="form-control input-sm" placeholder="Email PIC">
    </div>

    <div class="form-group">
      <label for="pic_fax">Fax</label>
      <input type="text" name="pic_fax" id="pic_fax" class="form-control input-sm" placeholder="Nomor Fax PIC (jika ada)">
    </div>
  </div>
</div>


      <div class="row">
        <div class="col-xs-6">
          <label for="start_date">Date Start</label>
          <input type="date" name="start_date" id="start_date" class="form-control input-sm" required>
        </div>
        <div class="col-xs-6">
          <label for="end_date">Date End</label>
          <input type="date" name="end_date" id="end_date" class="form-control input-sm" required>
        </div>
      </div>

      <br>

      <div class="form-group">
        <label for="revenue_room">Revenue Room</label>
        <input type="number" name="revenue_room" id="revenue_room" class="form-control input-sm" placeholder="Contoh: 5000000" min="0">
      </div>

      <div class="form-group">
        <label for="revenue_catering">Revenue Catering</label>
        <input type="number" name="revenue_catering" id="revenue_catering" class="form-control input-sm" placeholder="Contoh: 3000000" min="0">
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="form-control input-sm" required>
          <option value="">-- Pilih Status --</option>
          <option value="ACT">ACT (Active)</option>
          <option value="TEN">TEN (Tentative)</option>
          <option value="DEF">DEF (Definite)</option>
          <option value="CXL">CXL (Cancelled)</option>
        </select>
      </div>

    </div><!-- /.box-body -->

    <div class="box-footer">
      <button type="submit" class="btn btn-primary">Simpan Block ID</button>
    </div>
  </form>
</div>

<script type="text/javascript">
  var sprytf_block_name = new Spry.Widget.ValidationTextField("sprytf_block_name", 'none', {validateOn:["blur", "change"]});
  var sprytf_account_name = new Spry.Widget.ValidationTextField("sprytf_account_name", 'none', {validateOn:["blur", "change"]});
  var sprytf_alamat = new Spry.Widget.ValidationTextField("sprytf_alamat", 'none', {validateOn:["blur", "change"]});
  var sprytf_telepon = new Spry.Widget.ValidationTextField("sprytf_telepon", 'none', {validateOn:["blur", "change"]});
  var sprytf_owner_event = new Spry.Widget.ValidationTextField("sprytf_owner_event", 'none', {validateOn:["blur", "change"]});
  var sprytf_start_date = new Spry.Widget.ValidationTextField("sprytf_start_date", "date", {format:"yyyy-mm-dd", useCharacterMasking: true, validateOn:["blur", "change"]});
  var sprytf_end_date = new Spry.Widget.ValidationTextField("sprytf_end_date", "date", {format:"yyyy-mm-dd", useCharacterMasking: true, validateOn:["blur", "change"]});
  var sprytf_revenue_room = new Spry.Widget.ValidationTextField("sprytf_revenue_room", "integer", {validateOn:["blur", "change"]});
  var sprytf_revenue_catering = new Spry.Widget.ValidationTextField("sprytf_revenue_catering", "integer", {validateOn:["blur", "change"]}); 
</script>
</div>