<link href="<?php echo WEB_ROOT; ?>library/spry/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_ROOT; ?>library/spry/textfieldvalidation/SpryValidationTextField.js" type="text/javascript"></script>

<link href="<?php echo WEB_ROOT; ?>library/spry/textareavalidation/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_ROOT; ?>library/spry/textareavalidation/SpryValidationTextarea.js" type="text/javascript"></script>

<link href="<?php echo WEB_ROOT; ?>library/spry/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_ROOT; ?>library/spry/selectvalidation/SpryValidationSelect.js" type="text/javascript"></script>

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><b>Book Event</b></h3>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
<form role="form" action="<?php echo WEB_ROOT; ?>views/process.php?cmd=create_event" method="post">
    <div class="box-body">
      <div class="form-group">
        <label for="exampleInputEmail1">Owner</label>
		<input type="hidden" name="userId" value=""  id="userId"/>
        <span id="sprytf_name">
		<select name="name" class="form-control input-sm">
			<option>--Select Sales--</option>
			<?php
			$sql = "SELECT id, name FROM tbl_users";
			$result = dbQuery($sql);
			while($row = dbFetchAssoc($result)) {
				extract($row);
			?>
			<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
			<?php 
			}
			?>
		</select>
		<span class="selectRequiredMsg">Name is required.</span>
		
		</span>
      </div>
	  
	  <div class="form-group">
    <label for="account_name">Account Name</label>
    <input type="text" name="account_name" class="form-control input-sm" placeholder="Company / Travel Agent" id="account_name" required>
    <span class="textfieldRequiredMsg">Account Name is required.</span>
</div>

	  <div class="form-group">
    <label for="block_id">Block ID</label>
    <select name="block_id" id="block_id" class="form-control input-sm" required>
        <option value="">-- Select Business Block --</option>
        <?php
        $sql = "SELECT id, block_name, account_name FROM tbl_business_blocks WHERE status = 'ACT' ORDER BY block_name";
        $result = dbQuery($sql);
        while ($row = dbFetchAssoc($result)) {
            echo '<option value="' . $row['id'] . '">' . $row['block_name'] . ' - ' . $row['account_name'] . '</option>';
        }
        ?>
    </select>
    <span class="selectRequiredMsg">Block ID is required.</span>
</div>

<div class="form-group">
    <label for="event_name">Event Name</label>
    <input type="text" name="event_name" class="form-control input-sm" placeholder="Enter Event Name" id="event_name" required>
    <span class="textfieldRequiredMsg">Event Name is required.</span>
</div>

	  <div class="form-group">
    <label for="functionSpace">Function Space</label>
    <select name="function_space" id="functionSpace" class="form-control input-sm">
        <option value="">-- Select Function Space --</option>
        <?php
        $sql = "SELECT id, description FROM function_space_styles";
        $result = dbQuery($sql);
        while ($row = dbFetchAssoc($result)) {
            echo '<option value="' . $row['id'] . '">' . $row['description'] . '</option>';
        }
        ?>
    </select>
    <span class="selectRequiredMsg">Function Space is required.</span>
</div>
      <div class="form-group">
      <div class="row">
      	<div class="col-xs-6">
			<label>Date Start</label>
			<span id="sprytf_sdate">
        	<input type="date" name="start_date" id="sdate" class="form-control input-sm" required>
			<span class="textfieldRequiredMsg">Date is required.</span>
			<span class="textfieldInvalidFormatMsg">Invalid date Format.</span>
			</span>
        </div>
        <div class="col-xs-6">
			<label>Time Start</label>
			<span id="sprytf_stime">
            <input type="text" name="stime" class="form-control" placeholder="HH:mm">
			<span class="textfieldRequiredMsg">Time is required.</span>
			<span class="textfieldInvalidFormatMsg">Invalid time format.</span>
			</span>
       </div>
      </div>
	  </div>
	  <div class="form-group">
      <div class="row">
      	<div class="col-xs-6">
			<label>Date End</label>
			<span id="sprytf_edate">
        	<input type="date" name="end_date" id="edate" class="form-control input-sm" required>
			<span class="textfieldRequiredMsg">Date is required.</span>
			<span class="textfieldInvalidFormatMsg">Invalid date Format.</span>
			</span>
        </div>
        <div class="col-xs-6">
			<label>Time End</label>
			<span id="sprytf_etime">
            <input type="text" name="etime" class="form-control" placeholder="HH:mm">
			<span class="textfieldRequiredMsg">Time is required.</span>
			<span class="textfieldInvalidFormatMsg">Invalid time Format.</span>
			</span>
       </div>
      </div>
	  </div>
				  
	  <div class="form-group">
        <label for="exampleInputPassword1">Pax</label>
		<span id="sprytf_pax">
        <input type="text" name="pax" class="form-control input-sm" placeholder="No of peoples" >
		<span class="textfieldRequiredMsg">No of peoples is required.</span>
		<span class="textfieldInvalidFormatMsg">Invalid Format.</span>
      </div>
	  <div class="form-group">
    <label for="rentalOption">Rental</label>
    <input type="hidden" name="owner_id" value="" id="ownerId" />
    <input type="hidden" name="owner_name" id="ownerName" />
    
    <div id="sprytf_name">
        <select name="rental" id="rentalOption" class="form-control input-sm">
            <option value="">-- Select Option --</option>
            <option value="Include">Include</option>
            <option value="Exclude">Exclude</option>
        </select>
        <span class="selectRequiredMsg">Rental selection is required.</span>
    </div>
</div>

    <!-- /.box-body -->
    <div class="box-footer">
    <button type="submit" class="btn btn-primary">Next</button>
    </div>
  </form>
</div>
<!-- /.box -->
<script type="text/javascript">
<!--
var sprytf_name 	= new Spry.Widget.ValidationSelect("sprytf_name");
// var sprytf_address 	= new Spry.Widget.ValidationTextarea("sprytf_address", {minChars:6, isRequired:true, validateOn:["blur", "change"]});
var sprytf_phone 	= new Spry.Widget.ValidationTextField("sprytf_phone", 'none', {validateOn:["blur", "change"]});
var sprytf_mail 	= new Spry.Widget.ValidationTextField("sprytf_email", 'email', {validateOn:["blur", "change"]});
var sprytf_sdate 	= new Spry.Widget.ValidationTextField("sprytf_sdate", "date", {format:"yyyy-mm-dd", useCharacterMasking: true, validateOn:["blur", "change"]});
var sprytf_edate 	= new Spry.Widget.ValidationTextField("sprytf_edate", "date", {format:"yyyy-mm-dd", useCharacterMasking: true, validateOn:["blur", "change"]});
var sprytf_stime 	= new Spry.Widget.ValidationTextField("sprytf_stime", "time", {hint:"i.e 20:10", useCharacterMasking: true, validateOn:["blur", "change"]});var sprytf_rtime 	= new Spry.Widget.ValidationTextField("sprytf_rtime", "time", {hint:"i.e 20:10", useCharacterMasking: true, validateOn:["blur", "change"]});
var sprytf_etime 	= new Spry.Widget.ValidationTextField("sprytf_etime", "time", {hint:"i.e 20:10", useCharacterMasking: true, validateOn:["blur", "change"]});
var sprytf_ucount 	= new Spry.Widget.ValidationTextField("sprytf_ucount", "integer", {validateOn:["blur", "change"]});
//-->
</script>

<script type="text/javascript">
// Only target the Owner select dropdown, not all select elements
$('select[name="name"]').on('change', function() {
	var id = this.value;
	
	// Skip if no ID selected or it's the default option
	if (!id || id === '' || id === '--Select Sales--') {
		$('#userId').val('');
		$('#ownerId').val('');
		$('#ownerName').val('');
		return;
	}
	
	// Check if the value is a valid number (user ID)
	if (isNaN(id)) {
		console.log('Selected value is not a user ID:', id);
		return;
	}
	
	$.get('<?php echo WEB_ROOT. 'api/process.php?cmd=user&userId=' ?>'+id, function(data, status){
		try {
			// Check if data starts with HTML error tags
			if (typeof data === 'string' && data.indexOf('<') === 0) {
				console.error('Server returned HTML instead of JSON:', data);
				alert('Server error occurred. Please check the console for details.');
				return;
			}
			
			var obj;
			if (typeof data === 'string') {
				obj = $.parseJSON(data);
			} else {
				obj = data; // Already parsed
			}
			
			if (obj.error) {
				console.error('Server error:', obj.error);
				alert('Error: ' + obj.error);
				return;
			}
			
			$('#userId').val(obj.user_id || '');
			$('#ownerId').val(obj.user_id || '');
			$('#ownerName').val(obj.name || '');
			
		} catch (e) {
			console.error('JSON Parse Error:', e);
			console.error('Response data:', data);
			alert('Error parsing server response. Please check the console for details.');
		}
	}).fail(function(xhr, status, error) {
		console.error('AJAX Error:', status, error);
		alert('Failed to load user data: ' + error);
	});
	
});
</script>
