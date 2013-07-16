<?php
session_start();
if(isset($_SESSION['token'])){
  $token = $_SESSION['token'];
}
else {
  $token = get_api_token();
  $_SESSION['token'] = $token;
}
$api_url = 'http://127.0.0.1:8080/';

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Backboned UI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="/tmp_media/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <script>
      var _auth_token = '<?php echo $token  ?>';
      var _api_url = '<?php echo $api_url ?>';
    </script>
  </head>
  <body>
  	<div class="header"></div>

	<div class="container">
	    <div class="row">
	        <div id="content" class="span12>"></div>
	    </div>
	</div>
    
    
    <script src="/tmp_media/js/jquery-1.10.2.min.js"></script>
    <script src="/tmp_media/js/underscore-min.js"></script>
    <script src="/tmp_media/js/json2.js"></script>
    <script src="/tmp_media/js/backbone-min.js"></script>
    <script src="/tmp_media/js/bootstrap.min.js"></script>

    <script src="/tmp_media/js/models/usermodel.js"></script>
	<script src="/tmp_media/js/views/header.js"></script>
	<script src="/tmp_media/js/views/home.js"></script>
	<script src="/tmp_media/js/views/userlist.js"></script>
	<script src="/tmp_media/js/views/userdetails.js"></script>

    <script src="/tmp_media/js/main.js"></script>
  </body>
</html>



<?php

function get_api_token()
{
  $api_url = 'http://127.0.0.1:8080/';
  $fields = array(
    'username' => 'kunal',
    'password' => 'kunal',
    );
  $fields_string = '';
  foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
  rtrim($fields_string, '&');
  //open connection
  $ch = curl_init();

  //set the url, number of POST vars, POST data
  curl_setopt($ch,CURLOPT_URL, $api_url."api-token-auth/");
  curl_setopt($ch,CURLOPT_POST, count($fields));
  curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  //execute post
  $result = json_decode(curl_exec($ch));
  $token = $result->token;

  //close connection
  curl_close($ch);  
  return $token;
}
?>