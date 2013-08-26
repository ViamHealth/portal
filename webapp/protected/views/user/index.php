<?php

$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Profile',
);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.validate.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.ui.widget.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.iframe-transport.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.fileupload.js');
?>

<div class="row-fluid">
	<div class="span12 well well-small">
		<span class="user-form-loader">Loading..</span>
		<strong >Profile Details</strong>
		<br/><br/>
		<div class="pull-left">
			<img src="" class="img-polaroid" id="profile_picture_img" height="100px" width="100px">
			<br/>
			<div style="display: block; width: 100px; height: 20px; overflow: hidden;">
				
				<button style="width: 110px; height: 30px; position: relative; top: -5px; left: -5px;"><a href="javascript: void(0)">Change image</a></button>
				<input style="opacity: 0; filter:alpha(opacity: 0);position: relative; top: -40px;; left: -20px;" 
					id="fileupload" 
					type="file" name="profile_picture" 
					data-url="<?php echo Yii::app()->params['apiBaseUrl']."users/".$profile_id."/profile-picture/" ?>" >
			</div>
		</div>
		<div class="pull-left">
			<form id="user-form" style="display:none;" class="form-horizontal">
				<fieldset>
				
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
			<hr/>
			<form id="user-bmi-form" class="form-horizontal">
				<fieldset>
				
				<!--<div class="control-group" >
			      <label class="control-label" for="email">E-Mail (required)</label>
			      <input id="email" type="email" name="email" class="input uneditable-input" required/>
			    </div>-->
				<div class="control-group">
			      <label class="control-label" for="height">Height (in cms)</label>
			      <div class="controls">
			      	<input id="height" name="height" class="input"  type="text" required />
			      </div>
			    </div>
			    <div class="control-group">
			      <label class="control-label" for="height">Weight (in kgs)</label>
			      <div class="controls">
			      	<input id="weight" name="weight" class="input"  type="text" required />
			      </div>
			      <div class="control-group">
			      	<div class="controls">
			      		<br/>
			      		<button class="btn btn-primary" id="save-bmi-profile">Save</button>
			      </div>
			  	  </div>
			    </div>

			</fieldset>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$(function () {
		_DB.User.update_profile_picture(function(result, textStatus){
			$("#profile_picture_img").attr('src',result.profile_picture_url);
		});
		var profile_image_progress =  setInterval(function(){
			var overallProgress = $('#fileupload').fileupload('progress');
			if(overallProgress.loaded == overallProgress.total && overallProgress.loaded != 0){
				window.clearInterval(profile_image_progress)
			}
			//console.log(overallProgress);
		},800000);
		

	});

	_DB.User.retrieve(<?php echo $profile_id; ?>,function(json){

		$("#profile_picture_img").attr('src',json.profile.profile_picture_url);
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

	_DB.User.retrieve_bmi_profile(<?php echo $profile_id; ?>,function(json){
		$("#height").val(json.height);
		$("#weight").val(json.weight);
	});

	$("#save-bmi-profile").click(function(event){
		event.preventDefault();
		var $bmiform = $("#user-bmi-form");
		$bmiform.validate();
		if($bmiform.valid()){
			var bmi_profile = {};
			bmi_profile.height = $("#height").val();
			bmi_profile.weight = $("#weight").val();
			bmi_profile.height_measure = 'METRIC';
			bmi_profile.weight_measure = 'METRIC';

			_DB.User.update_bmi_profile(<?php echo $profile_id ?>,bmi_profile,function(json){
				alert('saved');
			});	
		}
		
	});
	$("#save-profile").click(function(event){
		event.preventDefault();
		var $form = $("#user-bmi-form");
		$form.validate();
		if($form.valid()){
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
		}
	});
});

</script>


