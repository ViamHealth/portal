<div class="col-md-12">
	<form class="form-horizontal" id="profile-bp-form" role="form">
	  	<input type="hidden" value="<?php echo $id ?>" name="user_id" >
	  	<div class="form-group">
			<label for="systolic" class="col-sm-2 col-md-3 control-label">Systolic Pressure</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="systolic_pressure" placeholder="Systolic" value="<?php echo $bmi_profile->systolic_pressure; ?>" required>
			</div>
		</div>
		<div class="form-group">
			<label for="diastolic_pressure" class="col-sm-2 col-md-3 control-label">Diastolic Pressure</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="diastolic_pressure" placeholder="diastolic" value="<?php echo $bmi_profile->diastolic_pressure; ?>" required>
			</div>
		</div>
		<div class="form-group">
			<label for="pulse_rate" class="col-sm-2 col-md-3 control-label">Pulse rate</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="pulse_rate" placeholder="Pulse Rate" value="<?php echo $bmi_profile->pulse_rate; ?>" required>
			</div>
		</div>
		
		<?php if($bmi_profile->bp_classification): ?>
	   	<div class="form-group">
	   		<label for="bmr" class="col-sm-2 col-md-3  control-label"></label>
		    <div class="col-sm-10 col-md-5">
		      <?php echo $bmi_profile->bp_classification_text; ?>
		    </div>
	  	</div>
	  	<?php endif ?>
	  	<div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	    	  	<button type="submit" id="profile-bp-save" class="btn btn-default">Save</button>
	    	</div>
	  	</div>
	</form>
</div>




