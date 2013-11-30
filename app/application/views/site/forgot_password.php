<div class="well">
<div class="page-header">
  <h1>Forgot Password</h1>
</div>

<div class="row">
	
	<form class="form-horizontal" id="forgot-password-form" role="form">
		<div class="form-group ">
			<label for="email" class="col-sm-2 col-md-3 control-label">Email</label>
			<div class="col-sm-10 col-md-3">
			  <input type="email" class="form-control" name="email" placeholder="Email" value="" required>
			</div>
		</div>
		<div class="form-group ">
			<label for="save" class="col-sm-2 col-md-3 control-label"></label>
			<div class="col-sm-10 col-md-2">
			  <button type="submit" class="form-control btn btn-save btn-primary" 
			  	name="save"  value="Send new password" >Send New Password</button>
			</div>
		</div>
	</form>
	
</div>

</div>

<script>

$(document).ready(function(){
	$("#forgot-password-form .btn-save").on('click',function(event){
		$("#forgot-password-form .btn-save").addClass("disabled");
		event.preventDefault();
		var form = $('#forgot-password-form');
		var email = $(form).find("input[name=email]").val();
		form.validate();
		if(form.valid()){
			_DB.Login.forgot_password_email(email,function(json,success){
				if(success)
					show_body_alerts('Your new password has been sent to your email.','success');
				else
					show_body_alerts('Make sure to input correct email id.','danger');
				$("#forgot-password-form .btn-save").removeClass("disabled");
				
			});
		}
	});
});
</script>