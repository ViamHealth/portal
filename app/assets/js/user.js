function fb_attach() {
	FB.login(function(response) {
        if (response.authResponse) {
            access_token = response.authResponse.accessToken; //get access token
            user_id = response.authResponse.userID; //get FB UID
            _DB.User.attach_facebook(access_token,function(json,success){
              if(success){
                //set_login_session(json.token,'fb');
              } else {
                //alert('Can not login right now. Please try again later.');
              }
            });
        } else {
            //user hit cancel button
            console.log('User cancelled login or did not fully authorize.');
        }
    }, {
        scope: 'email'
    });
}

function load_dob_widget(){
		var messages = "Try a different format of date.";
		var input = $("input:text[name=date_of_birth]"),  date = null;
		var input_date = $("input[name=date_of_birth_val]");
		
		if (input_date.val().length > 0) {
			date = Date.parse(input_date.val());
			if (date !== null){
				input.val(date.toString("MMMM dd, yyyy"));
			}
		}
		input.focus(
			function (e) {
				input.val("");
			}
		);
		input.blur( 
			function (e) {
				if (input.val().length > 0) {
					date = Date.parse(input.val());
					if (date !== null){
						input_date.val(date.toString("yyyy-mm-dd"));
						input.removeClass("error").addClass("accept").val(date.toString("MMMM dd, yyyy"));
					}

					else{
						//show_body_alerts('Could not understand date format for your Date of Birth. Please try again','warning');
						input_date.val('');
						input.removeClass("accept").addClass("error").val('');
						input.focus();
					}
				} else if ( input_date.val().length >  0){
					date = Date.parse(input_date.val());
					if (date !== null){
						input.addClass("accept").val(date.toString("MMMM dd, yyyy"));
					}
				}
			}
		);
}
$(document).ready(function(){
	load_dob_widget();
	
	_DB.User.update_profile_picture(function(result, textStatus){
		$("#profile_picture_img").attr('src',result.profile_picture_url);
		reset_session_user_data();
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
		var wight_v = $("#profile_weight").val();
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
		var wight_v = $("#profile_height").val();
		wight_v--; 
		$("#profile_height").val(wight_v); 
		$("#profile_height").parent().find("p.hval").html(wight_v+"cms");	
	});

	$('#profile-detail-save').on('click',function(event){
		event.preventDefault();
		var date_of_birth = $("#date_of_birth_val").val();
		var date = Date.parse(date_of_birth);
		if(date === null){
			$("input:text[name=date_of_birth]").addClass("error").removeClass("accept").focus();
			return false;
		}
		var form = $('#profile-details-form');
		/*form.rules( "add", {
			date_of_birth_val : "required",
		});*/
		form.validate();
		if(form.valid()){
			var user_id = $("input:hidden[name=user_id]").val();

			
			var user = {};
			var profile = {};
			user.first_name = $("#first_name").val();
			user.last_name = $("#last_name").val();
			var email = $("#email").val();
			if(email) user.email = email;

			profile.gender = $("input:radio[name=gender]:checked").val();
			profile.date_of_birth = format_date_for_api($("#date_of_birth_val").val());
			profile.city = $("#city").val();
			var mobile = $("#mobile").val();
			if(mobile) profile.mobile = mobile;

			if(user_id){
				$("#profile-details").collapse("hide");
				$("#profile-details").parents('.panel').removeClass('panel-primary').addClass('panel-success');

				_DB.User.update(user_id,user,function(json, success){
					if(!success)
						throw 'Something went wrong with user updation';
					reset_session_user_data();
				});
				
				_DB.User.update_profile(user_id,profile,function(json, success){
					if(!success)
						throw 'Something went wrong with user  profile updation';
					reset_session_user_data();
				});
			} else {
				_DB.User.create(user,function(json,success){
					if(success){
						user_id = json.id;
						_DB.User.update_profile(user_id,profile,function(pp, success){
							if(!success)
								throw 'Something went wrong with user  profile updation';

							reset_session_user_data(function(){
								window.location.href = "/u/"+user_id+"/user/";
							});
						});	
					}
				});
			}
			
			
		}
	});

	

	$('#health-stats-save').on('click',function(event){
		event.preventDefault();
		var form = $('#health-stats-form');
		form.validate();
		if(form.valid()){
			$("#health-stats").collapse("hide");
			$("#health-stats").parents('.panel').removeClass('panel-primary').addClass('panel-success');

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

	$('#profile-bp-save').on('click',function(event){
		event.preventDefault();
		var form = $('#profile-bp-form');
		form.validate();
		if(form.valid()){
			$("#profile-bp").collapse("hide");
			$("#profile-bp").parents('.panel').removeClass('panel-primary').addClass('panel-success');

			var user_id = $("input:hidden[name=user_id]").val();
			
			var bmi_profile = {};
			bmi_profile.systolic_pressure = $(form).find("input:text[name=systolic_pressure]").val();
			bmi_profile.diastolic_pressure = $(form).find("input:text[name=diastolic_pressure]").val();
			bmi_profile.pulse_rate = $(form).find("input:text[name=pulse_rate]").val();
			
			_DB.User.update_bmi_profile(user_id,bmi_profile,function(json, success){
				if(!success)
					throw 'Something went wrong with user bmi updation';
			});
		}
	});

	$('#profile-cholesterol-save').on('click',function(event){
		event.preventDefault();
		var form = $('#profile-cholesterol-form');
		form.validate();
		if(form.valid()){
			$("#profile-cholesterol").collapse("hide");
			$("#profile-cholesterol").parents('.panel').removeClass('panel-primary').addClass('panel-success');

			var user_id = $("input:hidden[name=user_id]").val();
			
			var bmi_profile = {};
			bmi_profile.ldl = $(form).find("input:text[name=ldl]").val();
			bmi_profile.hdl = $(form).find("input:text[name=hdl]").val();
			bmi_profile.triglycerides = $(form).find("input:text[name=triglycerides]").val();
			
			_DB.User.update_bmi_profile(user_id,bmi_profile,function(json, success){
				if(!success)
					throw 'Something went wrong with user bmi updation';
			});
		}
	});


	$('#profile-glucose-save').on('click',function(event){
		event.preventDefault();
		var form = $('#profile-glucose-form');
		form.validate();
		if(form.valid()){
			$("#profile-glucose").collapse("hide");
			$("#profile-glucose").parents('.panel').removeClass('panel-primary').addClass('panel-success');

			var user_id = $("input:hidden[name=user_id]").val();
			
			var bmi_profile = {};
			bmi_profile.random = $(form).find("input:text[name=random]").val();
			bmi_profile.fasting = $(form).find("input:text[name=fasting]").val();
			
			_DB.User.update_bmi_profile(user_id,bmi_profile,function(json, success){
				if(!success)
					throw 'Something went wrong with user bmi updation';
			});
		}
	});

	$("#change-password-modal .btn-save").on('click',function(event){
		event.preventDefault();
		var form = $('#change-password-form');
		form.validate({
			rules: {
				old_password : {
					required: true,
				},
				password : {
					required: true,
				},
				confirm_password: {
					required: true,
					equalTo: "input:password[name=password]"
				},
			}
		});
		if(form.valid()){
			var data = {};
			data.old_password = $(form).find("input:password[name=old_password]").val();
			data.password = $(form).find("input:password[name=password]").val();
			
			_DB.User.change_password(data,function(json,success){
				if(success){
					$("#change-password-modal").modal('hide');
				} else {
					if(json.responseText == '{"old_password": ["Incorrect password"]}'){
						$(".wrong_password").show();
					} else {
						$("#change-password-modal").modal('hide');
						alert('Could not complete the request. Please try again later.');
					}
				}
			})
		}
	});

	$("#share-user-modal .btn-save").on('click',function(event){
		event.preventDefault();
		var form = $('#share-user-form');
		form.validate();
		if(form.valid()){
			var data = {};
			data.share_user_id = $(form).find("input:hidden[name=user_id]").val();
			data.email = $(form).find("input[name=email]").val();
			data.is_self = $(form).find("input:radio[name=is_self]").val();
			if (!data.is_self) { data.is_self = 'False'}
			_DB.User.share(data,function(json,success){
				if(success){
					$("#share-user-modal").modal('hide');
					if(data.is_self == 'True')
						location.reload();
				} else {
					$("#share-user-modal").modal('hide');
					alert('Could not complete the request. Please try again later.');
				}
			})
		}
	});
	
});