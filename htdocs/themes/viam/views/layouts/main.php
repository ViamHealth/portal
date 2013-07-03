<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	<?php Yii::app()->bootstrap->register(); ?>
</head>

<body>
	<?php $this->widget('bootstrap.widgets.TbNavbar', array(
		'type'=>'inverse', // null or 'inverse'
    'brand'=>'Viam Health',
    'brandUrl'=>'#',
    'collapse'=>true, // requires bootstrap-responsive.css
    'items'=>array(
    	array(
        'class'=>'bootstrap.widgets.TbMenu',
        'items'=>array(
            array('label'=>'Viam Health', 'url'=>array('/site/index')),
            array('label'=>'Journals', 'url'=>array('/site/page', 'view'=>'about')),
            array('label'=>'Files', 'url'=>array('/viam/healthfiles')),
            array('label'=>'Reminders', 'url'=>array('/site/contact')),
            //array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
            //array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
        ),
    	),
    	array(
        'class'=>'bootstrap.widgets.TbMenu',
        'htmlOptions'=>array('class'=>'pull-right'),
        'items'=>array(
	    		array('label'=>Yii::app()->user->name, 'url'=>'#', 'items'=>array(
		       array('label'=>'Profile Details', 'url'=>'#'),
		       array('label'=>'Account Settings', 'url'=>'#'),
		       array('label'=>'Privacy Settings', 'url'=>'#'),
		       array('label'=>'Logout', 'url'=>array("/user/logout")),
		      )
		    ),
      )),
				
				//	            array('label'=>Yii::app()->user->name, 'block'=>true, 'htmlOptions'=>array('class'=>'ub_inr')),
			
    ),
	)); ?>
				<?php //if(Yii::app()->user->getIsGuest()!=1) {?>
				
		</div>	
	</div>
	<div id="body">
		<br/>
		<?php echo $content; ?>
	</div>
	<!--<div class="footer">
		<div class="footerContent">
			<div class="copy">© 2013 by Viam Health</div>

		</div>
	</div>-->	
</body>
</html>
