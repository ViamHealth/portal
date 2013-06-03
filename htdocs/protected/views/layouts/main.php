<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/dd.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<?php Yii::app()->user->logout()?>
<div class="container" id="page">

<!--	<div id="header">
		<div id="logo"><?php echo CHtml::encode(Yii::app()->name); ?></div>
	</div><!-- header -->
	<div id="header">
		<div class="nrow"><div class="container">&nbsp;</div></div>
	    <div class="mrow clearfix">
	    	<div class="container clearfix">
	        
	        	<div id="logo"><h1><a href="#">Viam Health</a></h1></div>
	            
	            <?php if(Yii::app()->user->getIsGuest()!=1) {?>
	            <ul id="main-menu">
	                <li><a href="#">Goals</a></li>
	                <li><a href="#">Journals</a></li>
	                <li><a href="#">Files<em>5</em></a></li>
	                <li class="active"><a href="#">Remainders</a></li>
	            </ul>
	            <?php } ?>
	            <div class="u_col">
					<div class="u_box clearfix">
	                	<div class="ub_inr">
	                		<?php if(Yii::app()->user->getIsGuest()==1) {?>
	                		<?php } else { ?>
	                        <div class="u_img"><a href="#"><img width="25" height="25" alt="User Image" src="<?php echo Yii::app()->request->baseUrl; ?>images/thumb/s_img1.jpg"></a></div>
	                        <div class="u_nam"><a href="#">Sharat Khurana</a></div>
	                        <div class="dd_lnks pp_box">
	                            <div class="twrap">&nbsp;</div>
	                            <div class="mwrap">
	                                <ul>
	                                    <li><a href="#">Profile Details</a></li>
	                                    <li><a href="#">Account Settings</a></li>
	                                    <li><a href="#">Privacy Settings</a></li>
	                                    <li><a href="#">Logout</a></li>
	                                </ul>
	                            </div>
	                            <div class="bwrap">&nbsp;</div>
	                        </div>
	                        <?php } ?>
						</div>
	                </div>
	            </div>

	        </div>
	    </div>
	    <?php if(Yii::app()->user->getIsGuest()!=1) {?>
	    <div class="hrow">
	        <div class="container">
	        	<h1>Reminders</h1>
	        </div>
	    </div>
	    <?php } ?>
	</div>

	<?php if(Yii::app()->user->getIsGuest()==1) {
		
	} ?>

	<div id="footer">
		<!--Copyright &copy; <?php echo date('Y'); ?> by Viam Health Pvt Ltd.<br/>
		All Rights Reserved.<br/> -->
		<div class="container"><p>© 2013. VIAM HEALTH</p></div>		
	</div><!-- footer -->

</div><!-- page -->

</body>
</html>
