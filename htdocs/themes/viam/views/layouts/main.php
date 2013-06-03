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
	<div id="nrow"><div class="container">&nbsp;</div></div>		
	<div id="header" class="outer">
		<div class="inner">
			<div class="logo"><h1><a href="#">Viam Health</a></h1></div>
            <div class="u_col">
				<div class="u_box clearfix">
                		<?php if(Yii::app()->user->getIsGuest()==1) {?>
                		<?php } else { ?>
						<?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
					        'type'=>'link', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
					        'buttons'=>array(
					            array('label'=>Yii::app()->user->name, 'block'=>true, 'htmlOptions'=>array('class'=>'ub_inr')),
					            array('items'=>array(
					                array('label'=>'Profile Details', 'url'=>'#'),
					                array('label'=>'Account Settings', 'url'=>'#'),
					                array('label'=>'Privacy Settings', 'url'=>'#'),
					                '---',
					                array('label'=>'Logout', 'url'=>array("/user/logout")),
					            )),
					        ),
					    )); ?>                        


<!--                        <div class="u_img"><a href="#"><img width="25" height="25" alt="User Image" src="<?php echo Yii::app()->request->baseUrl; ?>images/thumb/s_img1.jpg"></a></div>
                        <div class="u_nam"><a href="#"><?php echo (Yii::app()->user->name); ?></a></div>
                        <div class="dd_lnks pp_box">
                            <div class="twrap">&nbsp;</div>
                            <div class="mwrap">
                                <ul>
                                </ul>
                            </div>
                            <div class="bwrap">&nbsp;</div>-->
                        <?php } ?>
                </div>
            </div>
			<div class="navigation">
				<?php if(Yii::app()->user->getIsGuest()!=1) {?>
				<?php $this->widget('bootstrap.widgets.TbNavbar',array(
				    'items'=>array(
				  		
				        array(
				            'class'=>'bootstrap.widgets.TbMenu',
				            'items'=>array(
				                array('label'=>'Home', 'url'=>array('/site/index')),
				                array('label'=>'Journals', 'url'=>array('/site/page', 'view'=>'about')),
				                array('label'=>'Files', 'url'=>array('/site/contact')),
				                array('label'=>'Reminders', 'url'=>array('/site/contact')),
				                //array('label'=>'Login', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
				                //array('label'=>'Logout ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest)
				            ),
				        ),
				    ),
				)); ?>
				<?php } ?>
			</div>
		</div>	
	</div>
	<div id="body">
		<?php echo $content; ?>
	</div>
</body>
</html>
