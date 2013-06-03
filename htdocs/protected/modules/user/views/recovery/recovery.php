<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
?>

<div class="login-reg-left">
	<img src="../images/healthy2.jpg"/>
</div>
<div class="login-reg-right">
<div class="login-form">
	<?php if(Yii::app()->user->hasFlash('recoveryMessage')) { ?>
		<div class="success">
		<?php //echo Yii::app()->user->getFlash('recoveryMessage'); ?>
		<?php $this->beginwidget('bootstrap.widgets.TbLabel', array( 
				'type'=>'success',
				'label'=>Yii::app()->user->getFlash('recoveryMessage'),
				'encodeLabel'=>true,
			));

			$this->widget('bootstrap.widgets.TbButton', array(
	            'buttonType'=>'link',
	            'type'=>'primary',
	            'label'=>'Login',
	            'size'=>'large',
	            'url'=>array('/user/login'),
	        ));

			$this->endWidget();
		?>
		</div>
	<?php } else { ?>

		<?php $formUI=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
			'id'=>'recovery-form',
		    'type'=>'inline',
			'enableClientValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); ?>

			<?php echo $formUI->textFieldRow($form,'login_or_email'); ?>
		</div>
		<div class="form-actions">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
	            'buttonType'=>'submit',
	            'type'=>'primary',
	            'label'=>'Recover',
	            'size'=>'large',
	        )); ?>
		</div>
		<?php $this->endWidget(); ?>
	<?php } ?>
	
</div><!-- form -->
</div>