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
	'id'=>'login-form',
    'type'=>'inline',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<div class="login-components row row1">
		<div class="col col1">
			<?php echo $form->textFieldRow($model,'username', array('prepend'=>'@', 'placeholder'=>'username')); ?>
		</div>
		<div class="col col2">
			<?php echo $form->passwordFieldRow($model,'password',array(
		        //'hint'=>'Hint: You may login with <kbd>demo</kbd>/<kbd>demo</kbd> or <kbd>admin</kbd>/<kbd>admin</kbd>',
		        'prepend'=>'#', 'placeholder'=>'password'
		    )); ?>
		</div>
	</div>
	<div class="login-components row row2">
		<div class="col col1">
			<?php echo $form->checkBoxRow($model,'rememberMe'); ?>
		</div>
		<div class="col col2">
			<?php $this->widget('bootstrap.widgets.TbButton', array(
		        'buttonType'=>'link',
		        'type'=>'link',
		        'label'=>'Forgot Password',
		        'size'=>'small',
		        'url'=>array('/user/recovery'),
		    )); ?>        
		</div>
	</div>
	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'submit',
            'type'=>'primary',
            'label'=>'Login',
            'size'=>'large',
        )); ?>
		<?php $this->widget('bootstrap.widgets.TbButton', array(
            'buttonType'=>'link',
            'type'=>'link',
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
