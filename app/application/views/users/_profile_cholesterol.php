<div class="col-md-12">
	<form class="form-horizontal" id="profile-cholesterol-form" role="form">
	  	<input type="hidden" value="<?php echo $user->id ?>" name="user_id" >
	  	<div class="form-group">
			<label for="ldl" class="col-sm-2 col-md-3 control-label">LDL</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="ldl" placeholder="LDL" value="<?php echo $user->bmi_profile->ldl; ?>" required>
			</div>
		</div>
		<div class="form-group">
			<label for="hdl" class="col-sm-2 col-md-3 control-label">HDL</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="hdl" placeholder="HDL" value="<?php echo $user->bmi_profile->hdl; ?>" required>
			</div>
		</div>
		<div class="form-group">
			<label for="triglycerides" class="col-sm-2 col-md-3 control-label">Triglycerides</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="triglycerides" placeholder="Triglycerides" value="<?php echo $user->bmi_profile->triglycerides; ?>" required>
			</div>
		</div>
		
		<?php if($user->bmi_profile->cholesterol_classification): ?>
	   	<div class="form-group">
	   		<label for="bmr" class="col-sm-2 col-md-3  control-label"></label>
		    <div class="col-sm-10 col-md-5">
		    	Total Cholesterol <?php echo $user->bmi_profile->total_cholesterol; ?>
		    	<br/>
		      	<?php echo $user->bmi_profile->get_cholesterol_classification_text(); ?>
		    </div>
	  	</div>
	  	<?php endif ?>
	  	<div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	    	  	<button type="submit" id="profile-cholesterol-save" class="btn btn-default">Save</button>
	    	</div>
	  	</div>
	</form>
</div>




