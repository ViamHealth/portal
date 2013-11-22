<div class="col-md-12">
	<form class="form-horizontal" id="profile-glucose-form" role="form">
	  	<input type="hidden" value="<?php echo $user->id ?>" name="user_id" >
	  	<div class="form-group">
			<label for="random" class="col-sm-2 col-md-3 control-label">Random</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="random" placeholder="Random" value="<?php echo $user->bmi_profile->random; ?>" required>
			</div>
		</div>
		<div class="form-group">
			<label for="fasting" class="col-sm-2 col-md-3 control-label">Fasting</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="fasting" placeholder="Fasting" value="<?php echo $user->bmi_profile->fasting; ?>" required>
			</div>
		</div>
		
		<?php if($user->bmi_profile->sugar_classification): ?>
	   	<div class="form-group">
	   		<label for="bmr" class="col-sm-2 col-md-3  control-label"></label>
		    <div class="col-sm-10 col-md-5">
		    	
		      	<?php echo $user->bmi_profile->get_sugar_classification_text(); ?>
		    </div>
	  	</div>
	  	<?php endif ?>
	  	<div class="form-group">
	    	<div class="col-sm-offset-2 col-sm-10">
	    	  	<button type="submit" id="profile-glucose-save" class="btn btn-default">Save</button>
	    	</div>
	  	</div>
	</form>
</div>




