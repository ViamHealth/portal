<div class="row" >
	<div  class="col-md-12">
		<div class="panel-group" id="accordion">
		  <div class="panel panel-primary">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#profile-details">
		          Profile Details
		        </a>
		      </h4>
		    </div>
		    <div id="profile-details" class="panel-collapse collapse in">
		     	<div class="panel-body" >
		      		<?php $this->load->view('users/_profile_details',$user); ?>
		     	</div>
		    </div>
		  </div>
		  <div class="panel panel-primary">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#health-stats">
		          Basic Information
		        </a>
		        <div class="pull-right">
		        <?php echo $user->bmi_profile->bmi_classification_text; ?>
		        </div>
		      </h4>
		    </div>
		    <div id="health-stats" class="panel-collapse collapse">
		      <div class="panel-body">
		        <?php $this->load->view('users/_health_stats',$user); ?>
		      </div>
		    </div>
		  </div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	var nowTemp = new Date();
	var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
	
	$('#sandbox-container input').datepicker({
	    format: "yyyy-mm-dd",
	    endDate: now,
	    keyboardNavigation: false,
	    forceParse: false,
	    autoclose: true,
	    todayHighlight: true
	});
	$('#sandbox-container input').datepicker("setValue", new Date());


	$('#profile-detail-next').on('click',function(){
		event.preventDefault();
		$("#profile-details").removeClass("in");
		$("#health-stats").addClass("in");
	});

	_DB.User.update_profile_picture(function(result, textStatus){
		$("#profile_picture_img").attr('src',result.profile_picture_url);
	});

	$('#profile-detail-save').on('click',function(){
		event.preventDefault();
		var form = $('#profile-details-form');
		form.validate();
		if(form.valid()){
			//$("#profile-details").removeClass("in");
			$("#profile-details").parents('.panel').removeClass('panel-primary').addClass('panel-success');
			//$("#health-stats").addClass("in");
			//$("#health-stats").parents('.panel').addClass('panel-primary');

			var user = {};
			var profile = {};
			user.first_name = $("#first_name").val();
			user.last_name = $("#last_name").val();
			user.email = $("#email").val();
			var user_id = $("input:hidden[name=user_id]").val();

			_DB.User.update(user_id,user,function(json, success){
				if(!success)
					throw 'Something went wrong with user updation';
			});
			profile.gender = $("input:radio[name=gender]:checked").val();
			profile.date_of_birth = format_date_for_api($("#date_of_birth").val());
			profile.city = $("#city").val();
			profile.mobile = $("#mobile").val();
			profile.organization = $("#organization").val();
			
			_DB.User.update_profile(user_id,profile,function(json, success){
				if(!success)
					throw 'Something went wrong with user  profile updation';
			});
		}
	});

	$("#profile_adj_weight a.inc").click(function(event){
		event.preventDefault(); 
		var wight_v = $("#profile_weight").val();
		wight_v++; 
		$("#profile_weight").val(wight_v); 
		$("#profile_weight").parent().find("p.wval").html(wight_v+"Kg");
	});
	$("#profile_adj_weight a.dec").click(function(event){
		event.preventDefault(); 
		var wight_v = $("#weight").val();
		wight_v--; 
		$("#profile_weight").val(wight_v); 
		$("#profile_weight").parent().find("p.wval").html(wight_v+"Kg");	
	});
	$("#profile_adj_height a.inc").click(function(event){
		event.preventDefault(); 
		var wight_v = $("#profile_height").val();
		wight_v++; 
		$("#profile_height").val(wight_v); 
		$("#profile_height").parent().find("p.hval").html(wight_v+"cms");
	});
	$("#profile_adj_height a.dec").click(function(event){
		event.preventDefault(); 
		var wight_v = $("#height").val();
		wight_v--; 
		$("#profile_height").val(wight_v); 
		$("#profile_height").parent().find("p.hval").html(wight_v+"cms");	
	});

	$('#health-stats-save').on('click',function(){
		event.preventDefault();
		var form = $('#health-stats-form');
		form.validate();
		if(form.valid()){
			//$("#health-stats").removeClass("in");
			$("#health-stats").parents('.panel').removeClass('panel-primary').addClass('panel-success');
			//$("#health-stats").addClass("in");
			//$("#health-stats").parents('.panel').addClass('panel-primary');
			var user_id = $("input:hidden[name=user_id]").val();
			
			var profile = {};
			
			profile.blood_group = $("select[name=blood_group] option:selected").val();
			_DB.User.update_profile(user_id,profile,function(json, success){
				if(!success)
					throw 'Something went wrong with user profile updation';
			});

			var bmi_profile = {};

			bmi_profile.lifestyle = $("select[name=lifestyle] option:selected").val();
			bmi_profile.weight = $("#profile_weight").val();
			bmi_profile.height = $("#profile_height").val();

			
			_DB.User.update_bmi_profile(user_id,bmi_profile,function(json, success){
				if(!success)
					throw 'Something went wrong with user bmi updation';
			});
		}
	});
});
</script>
