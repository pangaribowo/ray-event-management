<?php
$eventName = $_POST['event_name'] ?? '';
$blockId   = $_POST['block_id'] ?? '';
$ownerName = $_POST['owner_name'] ?? '';
?>

<input type="hidden" name="event_name" value="<?php echo htmlspecialchars($eventName); ?>">
<input type="hidden" name="block_id" value="<?php echo htmlspecialchars($blockId); ?>">
<input type="hidden" name="owner_name" value="<?php echo htmlspecialchars($name); ?>">


<div class="card shadow-sm mb-4 border-primary">
  <div class="card-header bg-primary text-white">
    <strong>Informasi Event</strong>
  </div>
  <div class="card-body">
    <p class="mb-2"><strong>Event Name:</strong> <?php echo htmlspecialchars($eventName); ?></p>
    <p class="mb-2"><strong>Block ID:</strong> <?php echo htmlspecialchars($blockId); ?></p>
    <p class="mb-0"><strong>Owner:</strong> <?php echo htmlspecialchars($name); ?></p>
  </div>
</div>



<div class="col-md-8">

<!-- Spry CSS & JS -->
<link href="<?php echo WEB_ROOT; ?>library/spry/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_ROOT; ?>library/spry/textfieldvalidation/SpryValidationTextField.js" type="text/javascript"></script>

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><b>Form Catatan Tiap Bagian Hotel</b></h3>
  </div>

  <form role="form" action="<?php echo WEB_ROOT; ?>views/process.php?cmd=eventlist" method="post">
    <div class="box-body">

      <!-- Textarea untuk tiap divisi -->
      <?php 
        $departments = [
          "Signage", 
          "Front Office", 
          "Engineering", 
          "FB Banquet & Service", 
          "FB Product (Menu Makanan)", 
          "House Keeping", 
          "HR & Security", 
          "Accounting"
        ];
        foreach ($departments as $dept):
          $id = strtolower(str_replace([' ', '(', ')', '&'], ['_', '', '', 'and'], $dept));
      ?>
      <div class="form-group">
        <label for="<?php echo $id; ?>">Catatan untuk <?php echo $dept; ?></label>
        <textarea name="notes[<?php echo $id; ?>]" class="form-control" rows="3" placeholder="Tulis catatan untuk <?php echo $dept; ?>"></textarea>
      </div>
      <?php endforeach; ?>

    </div>

    <!-- Tombol Submit -->
    <div class="box-footer">
      <button type="submit" class="btn btn-primary">Simpan Catatan</button>
    </div>
  </form>
</div>

<!-- JavaScript Validation -->
<script type="text/javascript">
  var sprytf_event = new Spry.Widget.ValidationTextField("sprytf_event", 'none', {validateOn:["blur", "change"]});
</script>
</div>
