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
  else
    var date = new Date(ev);
  
	var yyyy = date.getFullYear();
	var mm = date.getMonth()+1;
	var dd = date.getDate();
	var formattedTime = yyyy + '-' + mm + '-' + dd;
	return formattedTime;
}
function reset_session_user_data()
{
  $.get('resetsessionuserdata/');
}
