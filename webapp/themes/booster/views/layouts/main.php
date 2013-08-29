<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="language" content="en"/>

	<link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon"/>
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
	      media="screen, projection"/>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
	      media="print"/>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css"
	      media="screen, projection"/>
	<![endif]-->
	

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<style>
	.navbar .navbar-inner  {
		background: url(/images/bg-main_menu.jpg) repeat-x 0 0;
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
		if(isset($value->username)){
			if(isset($value->id) && $value->id != $this->getCurrentUserId()){
				$family_array[] = array('label' => $value->username, 'url' => array('/u/'.$value->id.'/site/index'), 'visible' => !Yii::app()->user->isGuest,);
			}
			else {
				$current_profile_name = $value->username;
			}
			if($value->first_name && $value->last_name)
				$visible_identity = $value->first_name." ".$value->last_name;
			else
				$visible_identity = $value->first_name?$value->first_name:$value->username;	
			$sidebar_family_array[] = "<li><a href='".$this->createUrl('site/index',array(),'&',$value->id)."'><img  height='25' width='25' src='".$value->profile->profile_picture_url."' /> &nbsp; $visible_identity</a></li>";
		}
		
	}
	$family_array[] = array('label' => 'Add User', 'url' => array('/user/add'), 'visible' => !Yii::app()->user->isGuest,);
	$family_array[] = array('label' => 'Logout', 'url' => array('/site/logout'), 'visible' => !Yii::app()->user->isGuest,);

}
?>
<div class="container" id="page">
	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
	'type' => 'inverse', // null or 'inverse'
	'brand' => ' &nbsp; ',
	'brandOptions' => array('style'=>'background:url(/images/logo.png) no-repeat 0 8px;width:148px;'),
	'brandUrl' => $this->createUrl('site/index'),
	'collapse' => true, // requires bootstrap-responsive.css
	'items' => array(
		array(
			'class' => 'bootstrap.widgets.TbMenu',
			'items' => array(
				array('label' => 'Goals', 'url' => '#', 'visible' => !Yii::app()->user->isGuest, 
					'items' => array(
						array('label' => 'All', 'url' => array('/goals/index'), 'visible' => !Yii::app()->user->isGuest),
						array('label' => 'Weight', 'url' => array('/goalsweight/index'), 'visible' => !Yii::app()->user->isGuest),
						array('label' => 'Blood Pressure', 'url' => array('/goalsbloodpressure/index'), 'visible' => !Yii::app()->user->isGuest),
						array('label' => 'Cholesterol', 'url' => array('/goalscholesterol/index'), 'visible' => !Yii::app()->user->isGuest),
					)
				),
				array('label' => 'Reminders', 'url' => array('/reminders/index'), 'visible' => !Yii::app()->user->isGuest),
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
	<div class="container-fluid" <?php if(Yii::app()->user->isGuest) { ?> style="margin-top:50px" <?php } ?>>
		<?php if (isset($this->breadcrumbs)): ?>
			<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
				'links' => $this->breadcrumbs,
			)); ?><!-- breadcrumbs -->
		<?php endif?>

		
		<div class="row-fluid">
		<!-- TODO Move content block -->
		<?php if(count($sidebar_family_array)): ?>
			<div class="span3">
				<div class="well well-small sidebar-nav">
					<ul class="nav nav-list">
						<li class="nav-header">Family Profiles</li>
						<?php foreach ($sidebar_family_array as $key => $value) echo $value; ?>
					</ul>
				</div>
			</div>
			<div class="span9">
				<?php echo $content; ?>
			</div>
		<?php else: ?>
			<div class="span12">
			<?php echo $content; ?>
			</div>
		<?php endif ?>
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