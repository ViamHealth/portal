<div class="col-md-3">
  <img src="<?php echo $profile->profile_picture_url ?>" class="img-polaroid" id="profile_picture_img" height="100px" width="100px">
      <br/>
      <div style="display: block; width: 100px; height: 20px; overflow: hidden;">
        
        <button style="width: 110px; height: 30px; position: relative; top: -5px; left: -5px;"><a href="javascript: void(0)">Change image</a></button>
        <input style="opacity: 0; filter:alpha(opacity: 0);position: relative; top: -40px;; left: -20px;" 
          id="fileupload" 
          type="file" name="profile_picture" 
          data-url="<?php echo $api_url."users/".$id."/profile-picture/" ?>" >
      </div>
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
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" id="profile-detail-save" class="btn btn-default">Save</button>
    </div>
  </div>
</form>
</div>

