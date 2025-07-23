<div class="col-md-8">
  
<link href="<?php echo WEB_ROOT; ?>library/spry/textfieldvalidation/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_ROOT; ?>library/spry/textfieldvalidation/SpryValidationTextField.js" type="text/javascript"></script>

<link href="<?php echo WEB_ROOT; ?>library/spry/selectvalidation/SpryValidationSelect.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_ROOT; ?>library/spry/selectvalidation/SpryValidationSelect.js" type="text/javascript"></script>

<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title"><b>User Registration</b></h3>
  </div>

  <form role="form" action="<?php echo WEB_ROOT; ?>views/process.php?cmd=create" method="post">
    <div class="box-body">

      <!-- Input Name -->
      <div class="form-group">
        <label for="name">Name</label>
        <span id="sprytf_name">
          <input type="text" name="name" class="form-control input-sm" placeholder="Full Name">
          <span class="textfieldRequiredMsg">Name is required.</span>
          <span class="textfieldMinCharsMsg">Name must be at least 6 characters.</span>
        </span>
      </div>

       <!-- Input Password -->
      <div class="form-group">
        <label for="password">Password</label>
        <span id="sprytf_password">
          <input type="password" name="password" class="form-control input-sm" placeholder="Password" required>
          <span class="textfieldRequiredMsg">Password is required.</span>
          <span class="textfieldMinCharsMsg">Password must contain letters and numbers.</span>
        </span>
      </div>

      <!-- Input Email -->
      <div class="form-group">
        <label for="email">Email Address</label>
        <span id="sprytf_email">
          <input type="text" name="email" class="form-control input-sm" placeholder="Enter email">
          <span class="textfieldRequiredMsg">Email is required.</span>
          <span class="textfieldInvalidFormatMsg">Enter a valid email (user@domain.com).</span>
        </span>
      </div>

      <!-- Input Phone -->
      <div class="form-group">
        <label for="phone">Phone</label>
        <span id="sprytf_phone">
          <input type="text" name="phone" class="form-control input-sm" placeholder="Phone number">
          <span class="textfieldRequiredMsg">Phone number is required.</span>
        </span>
      </div>

      <!-- Select Role -->
      <div class="form-group">
        <label for="role">Role</label>
        <span id="sprytf_role">
          <select name="role" class="form-control input-sm">
            <option value="">-- Select Role --</option>
            <option value="admin">Admin</option>
            <option value="manager">Manager</option>
            <option value="sales">Sales</option>
          </select>
          <span class="selectRequiredMsg">Please select a role.</span>
        </span>
      </div>

      <!-- Input Position -->
      <div class="form-group">
        <label for="position">Position</label>
        <span id="sprytf_position">
          <input type="text" name="position" class="form-control input-sm" placeholder="Enter Position">
          <span class="textfieldRequiredMsg">Position is required.</span>
        </span>
      </div>

    </div>

    <!-- Submit Button -->
    <div class="box-footer">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
</div>

<!-- JavaScript Validation -->
<script type="text/javascript">
var sprytf_name     = new Spry.Widget.ValidationTextField("sprytf_name", 'none', {minChars:6, validateOn:["blur", "change"]});
var sprytf_password = new Spry.Widget.ValidationTextField("sprytf_password", 'none', {validateOn:["blur", "change"]});
var sprytf_email    = new Spry.Widget.ValidationTextField("sprytf_email", 'email', {validateOn:["blur", "change"]});
var sprytf_phone    = new Spry.Widget.ValidationTextField("sprytf_phone", 'none', {validateOn:["blur", "change"]});
var sprytf_role     = new Spry.Widget.ValidationSelect("sprytf_role");
var sprytf_position = new Spry.Widget.ValidationTextField("sprytf_position", 'none', {validateOn:["blur", "change"]});
</script>
</div>
