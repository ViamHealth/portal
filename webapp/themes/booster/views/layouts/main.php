<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="language" content="en"/>

	<link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>
	<!-- blueprint CSS framework -->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
	      media="screen, projection"/>-->
	<!--<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
	      media="print"/>-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css"
	      media="print"/>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css"
	      media="screen, projection"/>
	<![endif]-->
	

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<style>
	.datepicker{
	z-index:1151;
	cursor: pointer;
	border-radius: 0px;
	text-align: center;
}
.datepicker td{
	font-size: x-small;
	padding: 9px 10px;
}
.datepicker .prev {
	/*width: 12px;
height: 11px;
background-image: url(/images/sprite-img.png);
background-repeat: no-repeat;
margin: 0;
background-position: 0 -538px;*/
}
.datepicker th{
	font-size: small;
	padding: 9px 10px;
	font-weight: normal;
}

.navbar.navbar-fixed-top {
		border-bottom-color: rgb(52, 171, 76);
		border-bottom-style: solid;
border-bottom-width: 1px;
	}
	
	.navbar .navbar-inner  {
		/*background: url(/images/bg-main_menu.jpg) repeat-x 0 0;*/
		background-color: #37AA4F;
		background-image: none;
		padding: 10px 0px;
		font-size: 20px;
		font-family: Roboto;
		line-height: 20px;
		border-bottom-color: rgb(52, 171, 76);
border-bottom-style: solid;
border-bottom-width: 1px;
	}
	.navbar .nav>li>a {
float: none;
padding: 10px 15px 10px;
color: white;
text-decoration: none;
}

	.navbar .nav .active>a, .navbar .nav .active>a:hover, .navbar .nav .active>a:focus {
color: black;
background-color: #37AA4F;
}
	.navbar .brand, .navbar .nav>li>a {
color: white;
text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
}
a {
color: black;
text-decoration: none;
}
.navbar-inverse .nav-collapse .nav > li > a, .navbar-inverse .nav-collapse .dropdown-menu a {
    color: white;
}
	.navbar .nav>.active>a, .navbar .nav>.active>a:hover, .navbar .nav>.active>a:focus {
		box-shadow: none;
	}
	.well {
		background-color: white;
	}
	body {
		background-color: #38B452;
	}
	.nav-list>li>a {
		font-size:12px;
	}
	</style>
	<script type="text/javascript">
	var VH = {};
	VH.vars = {};
	VH.params = {};
	VH.params.auth_token = "<?php if(isset(Yii::app()->user->token)) echo Yii::app()->user->token; ?>"
	VH.params.apiUrl = "<?php echo Yii::app()->params['apiBaseUrl'] ?>";
	VH.vars.profile_id = '<?php if(isset($profile_id)) echo $profile_id; ?>';

	</script>
</head>

<body>
<?php  Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/api.js'); ?>
<?php
$family_array = array();
$sidebar_family_array = array();
$current_profile_name = '';
if(!Yii::app()->user->isGuest)
{
	$family = $this->getFamilyUsers();
	if(!$family) $family = array();
	$current_profile_name = Yii::app()->user->username;
	foreach ($family as $key => $value) {
		$active = false;
		if(isset($value->username)){
			if(isset($value->id) && $value->id != $this->getCurrentUserId()){
				$family_array[] = array('label' => $value->username, 'url' => array('/u/'.$value->id.'/site/index'), 'visible' => !Yii::app()->user->isGuest,);
			}
			else {
				$current_profile_name = $value->username;
				$active = true;
			}
			if($value->first_name && $value->last_name)
				$visible_identity = $value->first_name." ".$value->last_name;
			else
				$visible_identity = $value->first_name?$value->first_name:$value->username;	
			if(strlen($visible_identity)>15) $visible_identity = substr($visible_identity, 0,15);
			$li_class =''; 
			if($active){
				$li_class = " class='active' ";
			}
			$sidebar_family_array[] = "<li $li_class ><a href='".$this->createUrl('site/index',array(),'&',$value->id)."'><img  height='25' width='25' src='".$value->profile->profile_picture_url."' /> &nbsp; $visible_identity</a></li>";
		}
		
	}
	$family_array[] = array('label' => 'Add User', 'url' => array('/user/add'), 'visible' => !Yii::app()->user->isGuest,);
	$family_array[] = array('label' => 'Logout', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest,);

}
?>
<div class="container" id="page">
	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
	'type' => '', // null or 'inverse'
	'brand' => ' &nbsp; ',
	'brandOptions' => array('style'=>'background:url(/images/logo.png) no-repeat;background-size:180px 33px;width: 155px;'),
	'brandUrl' => $this->createUrl('site/index'),
	'collapse' => true, // requires bootstrap-responsive.css
	'items' => array(
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'items' => array(
				array('label' => 'Goals', 'url' => array('/goals/index'), 'visible' => !Yii::app()->user->isGuest, ),
				array('label' => 'Health Watch', 'url' => array('/healthwatch/index'), 'visible' => !Yii::app()->user->isGuest),
				array('label' => 'Files', 'url' => array('/healthfiles/index'), 'visible' => !Yii::app()->user->isGuest),
                array('label' => 'Food Diary', 'url' => array('/fooddiary/index'), 'visible' => !Yii::app()->user->isGuest),
                array('label' => 'Profile', 'url' => array('/user/index'), 'visible' => !Yii::app()->user->isGuest),

				//array('label' => 'Contact', 'url' => array('/site/contact')),
				array('label' => 'Login', 'url' => array('/site/login'), 'visible' => Yii::app()->user->isGuest),
//				array('label' => 'Logout (' . Yii::app()->user->name . ')', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest)
			),
		),
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'htmlOptions' => array('class' => 'pull-right'),

			'items' => array(
				array('label' => $current_profile_name , 'url' => '#', 'visible' => !Yii::app()->user->isGuest,
					'items' =>
						$family_array
				),
			),
		),

		//'<form class="navbar-search pull-left" action=""><input type="text" class="search-query span2" placeholder="Search"></form>',
		//(!Yii::app()->user->isGuest) ? '<p class="navbar-text pull-right"><a href="#">'.Yii::app()->user->name.'</a></p>' : '',
		/*array(
			'class' => 'bootstrap.widgets.TbMenu',
			'htmlOptions' => array('class' => 'pull-right'),
			'items' => array(
				array('label' => 'Link', 'url' => '#'),
				'---',
				array('label' => 'Dropdown', 'url' => '#', 'items' => array(
					array('label' => 'Action', 'url' => '#'),
					'---',
					array('label' => 'Separated link', 'url' => '#'),
				)),
			),
		),*/
	),
)); ?>
	<!-- mainmenu -->
	<div class="container-fluid" style="margin: 70px 0;" <?php if(Yii::app()->user->isGuest) { ?> style="margin-top:50px" <?php } ?>>
		<?php if (0)://isset($this->breadcrumbs)): ?>
			<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
				'links' => $this->breadcrumbs,
			)); ?><!-- breadcrumbs -->
		<?php endif?>

		
		<div class="row-fluid">
		<!-- TODO Move content block -->
		
			<div class="span3">
				<div class="well well-small sidebar-nav">
					<ul class="nav nav-list">
						<li class="nav-header">Family Profiles</li>
						<?php if(count($sidebar_family_array)): ?>
						<?php foreach ($sidebar_family_array as $key => $value) echo $value; ?>
						<?php endif ?>
					</ul>
				</div>
			</div>
			<div class="span9 well">
				<?php echo $content; ?>
			</div>
		</div>
		<!-- -->

		<hr/>
		<div id="footer">
			Copyright &copy; <?php echo date('Y'); ?> by Viamhealth.<br/>
			All Rights Reserved.<br/>
		</div>
		<!-- footer -->
	</div>
</div>
<!-- page -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/viam.css"
	      media="print"/>
</body>
</html>