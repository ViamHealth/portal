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

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'registration-form',
    'type'=>'inline',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="login-components row row1">
		<div class="col col1">
			<?php echo $form->textFieldRow($model,'email', array('prepend'=>'@', 'placeholder'=>'email')); ?>
		</div>
		<div class="col col2">
			<?php echo $form->textFieldRow($model,'username', array('prepend'=>' ', 'placeholder'=>'username')); ?>
		</div>
	</div>
	<div class="login-components row row2">
		<div class="col col1">
			<?php echo $form->passwordFieldRow($model,'password', array('prepend'=>'#', 'placeholder'=>'password'))?>
		</div>
		<div class="col col2">
			<?php echo $form->passwordFieldRow($model,'verifyPassword', array('prepend'=>'#', 'placeholder'=>'password'))?>
		</div>
	</div>	
	<?php if (UserModule::doCaptcha('registration')): ?>
		<?php echo $form->captchaRow($model, 'verifyCode')?>
	<?php endif; ?>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'type'=>'primary',
            'label'=>'Register',
            'size'=>'large',
            'url'=>array('/user/registration'),
        )); ?>             
	</div>
	
	<div class="social-login-elements">
		<?php $this->widget('ext.hoauth.widgets.HOAuth'); ?>
	</div>


<?php $this->endWidget(); ?>
</div><!-- form -->
</div>



