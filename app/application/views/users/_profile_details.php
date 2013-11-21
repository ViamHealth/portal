<div class="col-md-3">
</div>
<div class="col-md-9">
	<form class="form-horizontal" id="profile-details-form" role="form">
  <input type="hidden" value="<?php echo $id ?>" name="user_id" >
  <div class="form-group">
    <label for="first_name" class="col-sm-2 col-md-3 control-label">First Name</label>
    <div class="col-sm-10 col-md-5">
      <input type="text" class="form-control" id="first_name" placeholder="First Name" value="<?php echo $first_name; ?>" required>
    </div>
  </div>
  <div class="form-group">
    <label for="last_name" class="col-sm-2 col-md-3  control-label">Last Name</label>
    <div class="col-sm-10 col-md-5">
      <input type="text" class="form-control" id="last_name" placeholder="Last Name" value="<?php echo $last_name; ?>">
    </div>
  </div>
  <div class="form-group">
  	<label for="gender" class="col-sm-2 col-md-3  control-label">Gender</label>
    <div class="col-sm-10 col-md-5">
      <label class="radio-inline">
      	<input type="radio" name="gender" id="gender_m" value="MALE"
      	<?php if($profile->gender == 'MALE'){ ?> checked="checked" <?php } ?> >Male
      </label>
      <label class="radio-inline">
      	<input type="radio" name="gender" id="gender_f" value="FEMALE" 
      		<?php if($profile->gender == 'FEMALE'){ ?> checked="checked" <?php } ?> >Female
      </label>
    </div>
  </div>
  <div class="form-group">
    <label for="date_of_birth" class="col-sm-2 col-md-3  control-label">Born on</label>
    <div class="col-sm-10 col-md-5" id="sandbox-container">
		<input class="form-control" style="" name="date_of_birth" type="text" id="date_of_birth" value="<?php echo $profile->date_of_birth; ?>" >
    </div>
  </div>

  <div class="form-group">
    <label for="city" class="col-sm-2 col-md-3  control-label">City</label>
    <div class="col-sm-10 col-md-5">
      <input type="text" class="form-control" id="city" placeholder="City" value="<?php echo $profile->city; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="col-sm-2 col-md-3  control-label">Email</label>
    <div class="col-sm-10 col-md-5">
      <input type="email" class="form-control" id="email" placeholder="Email" value="<?php echo $email; ?>" >
    </div>
  </div>
  <div class="form-group">
    <label for="mobile" class="col-sm-2 col-md-3  control-label">Phone Number</label>
    <div class="col-sm-10 col-md-5">
      <input type="text" class="form-control" id="mobile" placeholder="mobile" value="<?php echo $profile->mobile; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="organization" class="col-sm-2 col-md-3  control-label">Organization</label>
    <div class="col-sm-10 col-md-5">
      <input type="text" class="form-control" id="organization" placeholder="Organization" value="<?php echo $profile->organization; ?>">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" id="profile-detail-save" class="btn btn-default">Save</button>
      <button type="button" id="profile-detail-next" class="btn btn-default">Next</button>
    </div>
  </div>
</form>
</div>

