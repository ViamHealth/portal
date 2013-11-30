if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
  var msViewportStyle = document.createElement("style")
  msViewportStyle.appendChild(
    document.createTextNode(
      "@-ms-viewport{width:auto!important}"
    )
  )
  document.getElementsByTagName("head")[0].appendChild(msViewportStyle)
}

function format_date_for_api(ev)
{

  if(!ev)
	 var date = new Date();
  else {
    var date = new Date(Date.parse(ev));
  }

	var yyyy = date.getFullYear();
	var mm = date.getMonth()+1;
	var dd = date.getDate();
	var formattedTime = yyyy + '-' + mm + '-' + dd;
	return formattedTime;
}

function show_body_alerts(message,status){
  var alert = $.parseHTML('<div class="alert alert-warning alert-dismissable">'+
    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button></div>');
  $(alert).append(message);
  $("#alert_box").html(alert);
  
    $(alert).addClass('alert-'+status);
}

function reset_session_user_data(callback)
{
  if(!callback) callback = function(){}
  $.get('/resetsessionuserdata/',callback);
}

$("#family-users-invite").on('click',function(event){
  event.preventDefault();
  $("#family-invite-user-modal").modal();
});

$('#family-invite-user-modal .btn-save').on('click',function(event){
    event.preventDefault();

    var form = $('#family-invite-user-form');
    form.validate();
    if(form.valid()){  
      var data = {};
      data.email = $(form).find("input:text[name=email]").val();

      $("#family-invite-user-form .saving_message").show();
      
      _DB.User.invite(data,function(json, success){
        if(!success)
          throw 'Something went wrong with user bmi updation';
        //$("#family-invite-user-modal").modal('hide');  
        reset_session_user_data(function(){
          window.location.href = "/u/"+json.id+"/user/";
        });
        
      });
    }
  });
