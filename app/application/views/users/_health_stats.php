<div class="col-md-12">
	<form class="form-horizontal" id="health-stats-form" role="form">
	  	<input type="hidden" value="<?php echo $user->id ?>" name="user_id" >
	  	<div class="form-group weight-inputs">
			<label for="city" class="col-sm-2 col-md-3  control-label">Weight</label>
		    <div class="col-sm-10 col-md-5">
		    	<p class="wval" style="width:auto;"><?php echo $user->bmi_profile->weight?$user->bmi_profile->weight:77;?> Kg</p>
				<input id="profile_weight" name="profile_weight" type="hidden" value="<?php echo $user->bmi_profile->weight?$user->bmi_profile->weight:77;?>">
				<div class="spnr" id="profile_adj_weight">
				  <a class="inc u_arw_grn" href="#"></a>
				  <a class="dec d_arw_red" href="#"></a>
				</div>
		      
		    </div>
		</div>
		<div class="form-group weight-inputs">
			<label for="city" class="col-sm-2 col-md-3  control-label">Height</label>
		    <div class="col-sm-10 col-md-6">
		    	<p class="hval" style="width:auto;"><?php echo $user->bmi_profile->height?$user->bmi_profile->height:120?> cms</p>
				<input id="profile_height" name="profile_height" type="hidden" value="<?php echo $user->bmi_profile->height?$user->bmi_profile->height:120;?>" >
				<div class="spnr" id="profile_adj_height">
				  <a class="inc u_arw_grn" href="#"></a>
				  <a class="dec d_arw_red" href="#"></a>
				</div>
		      
		    </div>
		</div>
	  	<div class="form-group">
			<label for="blood_group" class="col-sm-2 col-md-3  control-label">Blood Group</label>
			<div class="col-sm-10 col-md-3">
		  
		  	<select class="form-control" name="blood_group" id="blood_group">
			  <option value="3" <?php if ($user->profile->blood_group == 3) echo "selected" ?>>B+</option>
			  <option value="1" <?php if ($user->profile->blood_group == 1) echo "selected" ?>>A+</option>
			  <option value="5" <?php if ($user->profile->blood_group == 5) echo "selected" ?>>AB+</option>
			  <option value="7" <?php if ($user->profile->blood_group == 7) echo "selected" ?>>O+</option>
			  <option value="4" <?php if ($user->profile->blood_group == 4) echo "selected" ?>>B-</option>
			  <option value="2" <?php if ($user->profile->blood_group == 2) echo "selected" ?>>A-</option>
			  <option value="6" <?php if ($user->profile->blood_group == 6) echo "selected" ?>>AB-</option>
			  <option value="8" <?php if ($user->profile->blood_group == 8) echo "selected" ?>>O-</option>
			</select>
			</div>
		</div>
		<div class="form-group">
			<label for="lifestyle" class="col-sm-2 col-md-3  control-label">Lifestyle</label>
			<div class="col-sm-10 col-md-5">
		  
		  	<select class="form-control" name="lifestyle" id="lifestyle">
		  		<option value="1" <?php if ($user->bmi_profile->lifestyle == 1) echo "selected" ?>>Sedentary</option>
		  		<option value="2" <?php if ($user->bmi_profile->lifestyle == 2) echo "selected" ?>>Lightly Active</option>
		  		<option value="3" <?php if ($user->bmi_profile->lifestyle == 3) echo "selected" ?>>Moderately Active</option>
		  		<option value="4" <?php if ($user->bmi_profile->lifestyle == 4) echo "selected" ?>>Very Active</option>
		  		<option value="5" <?php if ($user->bmi_profile->lifestyle == 5) echo "selected" ?>>Extremely Active</option>
			</select>
			</div>
		</div>
		<?php if($user->bmi_profile->bmr): ?>
	   	<div class="form-group">
	   		<label for="bmr" class="col-sm-2 col-md-3  control-label">Bmr</label>
		    <div class="col-sm-10 col-md-5">
		      <?php echo $user->bmi_profile->bmr; ?> Kg/m<span style="font-size: 12px;vertical-align: top;">2</span>
		      <br/>
		      <?php echo $user->bmi_profile->get_bmi_classification_text(); ?>
		    </div>
	  	</div>
	  	<?php endif ?>
	  	<div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	    	  	<button type="submit" id="health-stats-save" class="btn btn-default">Save</button>
	    	</div>
	  	</div>
	</form>
</div>




