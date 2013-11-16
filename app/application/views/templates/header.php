<!DOCTYPE html>
<html lang="en">
<head>
   <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="">
   <meta name="keywords" content="">
   <meta name="author" content="">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">

   <title>Viamhealth</title>

   <link href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
   <link href="<?php echo base_url('assets/css/bootstrap-theme.min.css') ?>" rel="stylesheet">
   <link href="<?php echo base_url('assets/css/datepicker.css') ?>" rel="stylesheet">
   <link href="<?php echo base_url('assets/css/jquery.fileupload.css') ?>" rel="stylesheet">

   <link href="<?php echo base_url('assets/css/viam.css') ?>" rel="stylesheet">


   <script>
   	var VH = {};
	VH.vars = {};
	VH.params = {};
	VH.params.auth_token = "<?php echo $token; ?>";
	VH.params.apiUrl = '<?php echo $api_url; ?>';
	VH.vars.profile_id = '<?php if($loggedin) echo $current_user_id;?>';
   </script>
   <script src="<?php echo base_url('assets/js/jquery.min.js') ?>"></script>
   <script src="<?php echo base_url('assets/js/jquery-ui.min.js') ?>"></script>
   <script src="<?php echo base_url('assets/js/api.js') ?>"></script>
   
   <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
