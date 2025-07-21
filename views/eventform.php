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
  <form role="form" action="<?php echo WEB_ROOT; ?>views/process.php?cmd=addnotes" method="post">
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
    <input type="text" name="block_id" class="form-control input-sm" placeholder="Enter Block ID" id="block_id" required>
    <span class="textfieldRequiredMsg">Block ID is required.</span>
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
        	<input type="date" name="start_date" id="edate" class="form-control input-sm" required>
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
    <input type="hidden" name="owner_id" value="" id="userId" />
    <input type="hidden" name="owner_name" id="ownerName" />
    
    <div id="sprytf_name">
        <select name="rental" id="rentalOption" class="form-control input-sm">
            <option value="">-- Select Option --</option>
            <option value="include">Include</option>
            <option value="exclude">Exclude</option>
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
var sprytf_address 	= new Spry.Widget.ValidationTextarea("sprytf_address", {minChars:6, isRequired:true, validateOn:["blur", "change"]});
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
$('select').on('change', function() {
	//alert( this.value );
	var id = this.value;
	$.get('<?php echo WEB_ROOT. 'api/process.php?cmd=user&userId=' ?>'+id, function(data, status){
		var obj = $.parseJSON(data);
		$('#userId').val(obj.user_id);
		$('#email').val(obj.email);
		$('#address').val(obj.address);
		$('#phone').val(obj.phone_no);
	});
	
})
</script>