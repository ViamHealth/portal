<?php

$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Profile',
);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.validate.min.js');
?>

<div class="row-fluid">
	<div class="span6 offset2">
		<span class="user-form-loader">Loading..</span>
		<form id="user-form" style="display:none;" class="form-horizontal">
			<fieldset>
			<img src="" class="img-polaroid" id="profile_picture">
			<!--<div class="control-group" >
		      <label class="control-label" for="email">E-Mail (required)</label>
		      <input id="email" type="email" name="email" class="input uneditable-input" required/>
		    </div>-->
			<div class="control-group">
		      <label class="control-label" for="first_name">First Name (required, at least 2 characters)</label>
		      <div class="controls">
		      	<input id="first_name" name="first_name" class="input" minlength="2" type="text" required />
		      </div>
		    </div>
		    <div class="control-group">
		      <label class="control-label" for="last_name">Last Name (required, at least 2 characters)</label>
		      <div class="controls">
		      	<input id="last_name" name="last_name" class="input" minlength="2" type="text" />
		      </div>
		    </div>
		    <div class="control-group">
		      <label class="control-label" for="location">Location</label>
		      <div class="controls">
		      	<input id="location" name="location" class="input" minlength="2" type="text" />
		      </div>
		    </div>
		    <div class="control-group">
		      <label class="control-label" for="date_of_birth">Date of Birth</label>
		      <div class="controls">
		      	<input id="date_of_birth" name="date_of_birth" class="input" minlength="2" type="date" required/>
		      </div>
		    </div>
		    <div class="control-group">
		    	<label class="control-label" for="gender">Gender:</label>
		    	<div class="controls">
		    	<label class="radio">
					<input type="radio" name="gender" id="gender_male" value="MALE">Male
				</label>
				<label class="radio">
			  		<input type="radio" name="gender" id="gender_female" value="FEMALE">Female
				</label>
				<button class="btn btn-primary" id="save-profile">Save</button>
				</div>
			</div>
		    
			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	_DB.User.retrieve(<?php echo $profile_id; ?>,function(json){

		$("#profile_picture").attr('src',json.profile.profile_picture_url);
		$("#first_name").val(json.first_name);
	  	$("#last_name").val(json.last_name);
	  	$("#location").val(json.profile.location);
	  	$("#date_of_birth").val(json.profile.date_of_birth);
	  	if(json.profile.gender)
	  	{
	  		var $radios = $("input:radio[name=gender]");
		  	if($radios.is(':checked') === false) 
		  	{
		        $radios.filter('[value='+json.profile.gender+']').prop('checked', true);
		    }
		}
	  	//$("#email").val(json.email);
	  	$(".user-form-loader").hide();
	  	$("#user-form").show();
	});


	$("#save-profile").click(function(event){
		event.preventDefault();
		var user = {};
		user.profile = {};
		user.first_name = $("#first_name").val();
	  	user.last_name = $("#last_name").val();
	  	user.profile.location = $("#location").val();
	  	user.profile.date_of_birth = $("#date_of_birth").val();
	  	//user.email = $("#email").val();
	  	user.profile.gender = $("input:radio[name=gender]:checked").val().toUpperCase();
	  	
	  	_DB.User.update(<?php echo $profile_id ?>,user,function(json){
	  		_DB.User.update_profile(<?php echo $profile_id ?>,user.profile,function(json){
	  		alert('saved');
	  		});
	  	});
	});
});

</script>


