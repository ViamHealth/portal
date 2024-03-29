<script>


function fb_login(){
    FB.login(function(response) {
        if (response.authResponse) {
            access_token = response.authResponse.accessToken; //get access token
            user_id = response.authResponse.userID; //get FB UID
            _DB.Login.by_facebook(access_token,function(json,success){
              if(success){
                set_login_session(json.token,'fb');
              } else {
                show_body_alerts('Can not login right now. Please try again later.','danger');

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

function set_login_session(token,type)
{
  $.get("<?php echo site_url('site/loginapi/'); ?>/"+token, function(data){
    if(data == '1'){
      window.location.href = "<?php echo site_url('files'); ?>";
    } else {
        if(type == 'email')
                      $("#login-unsuccess-message").show();
        elseif(type=='fb')
            show_body_alerts('Your facebook account is not attached to any registered user','danger');
    }
  });
}

$(document).ready(function(){
    $("#login-button-home").on("click",function(event){
        
        $("#login-unsuccess-message").hide();
        event.preventDefault();
        var form = $('#form-signin');
        form.validate();
        if(form.valid()){

        	var email = $("input[name=email]").val();
        	var password = $("input[name=password]").val();
            $("#login-button-home").addClass("disabled").val('Loading....');
        	_DB.Login.by_email(email,password,function(json,success){
        		if(success){
        			set_login_session(json.token,'email');
        		} else {
                    if(json.responseText){
                        if(json.responseText){
                            var r = $.parseJSON(json.responseText);
                            if(r['non_field_errors'][0] == 'Unable to login with provided credentials.'){
                                $("#login-unsuccess-message").show();    
                            }
                            console.log(r['non_field_errors'][0]);
                            }        
                    }
                }
                $("#login-button-home").removeClass("disabled").val('Sign in');
        	});
        }
    });
});
</script>
<style>
.account-box
{
    border: 2px solid rgba(153, 153, 153, 0.75);
    border-radius: 2px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    -khtml-border-radius: 2px;
    -o-border-radius: 2px;
    z-index: 3;
    font-size: 13px !important;
    font-family: "Helvetica Neue" ,Helvetica,Arial,sans-serif;
    background-color: #37AA4F;
    padding: 20px;
}

.logo
{
    width: 138px;
    height: 30px;
    text-align: center;
    margin: 0px 0px 20px;
    background-position: 0px -4px;
    position: relative;
}

.forgotLnk
{
    margin-top: 10px;
    display: block;
    color: #000;
}

.purple-bg
{
    background-color: #6E329D;
    color: #fff;
}
.or-box
{
    position: relative;
    border-top: 1px solid #dfdfdf;
    padding-top: 20px;
    margin-top:20px;
}
.or
{
    color: #666666;
    background-color: #ffffff;
    position: absolute;
    text-align: center;
    top: -8px;
    width: 40px;
    left: 115px;
}
.account-box .btn:hover
{
    /*color: #fff;*/
}
.btn.btn-block.btn-facebook1
{
    background-color: #3b5998;
    color: #fff;
    font-weight:bold;
    background-image: none;
}

.btn.btn-block.btn-facebook1:hover, .btn.btn-block.btn-facebook1:focus
{
    background-color: #3b5998;
    color: #fff;
    font-weight:bold;
}
.btn-google
{
    background-color: #454545;
    color: #fff;
    font-weight:bold;
}

</style>
<div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="account-box">
                <div class="logo ">
                    <img src="<?php echo base_url('assets/images/logo.png') ?>" alt=""/>
                </div>
                <p class="text-error" id="login-unsuccess-message" style="display:none;"> Wrong email or password. Please try again.</p>
                <form class="form-signin" id="form-signin" action="#">
                <div class="form-group">
                    <input name="email" type="email"  class="form-control input-block-level" placeholder="Email" required autofocus />
                </div>
                <div class="form-group">
                    <input name="password" type="password" class="form-control input-block-level" placeholder="Password" required />
                </div>
                <button class="btn btn-primary btn-block " id="login-button-home" type="submit">
                    Sign in</button>
                </form>
                <a class="forgotLnk" href="<?php echo viam_url('/forgot_password/') ?>">I can't access my account</a>
                <div class="or-box">
                    
                    <div class="row">
                        <div class="col-md-12">
                            <button onclick="fb_login();" class="btn btn-facebook1 btn-block btn-default">Login with Facebook</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
