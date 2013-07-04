<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php echo $form->textFieldRow($model,'id',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'user_id',array('class'=>'span5')); ?>

	<?php echo $form->textAreaRow($model,'details',array('rows'=>6, 'cols'=>50, 'class'=>'span8')); ?>

	<?php echo $form->textFieldRow($model,'start_datetime',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'repeat_mode',array('class'=>'span5','maxlength'=>32)); ?>

	<?php echo $form->textFieldRow($model,'repeat_day',array('class'=>'span5','maxlength'=>2)); ?>

	<?php echo $form->textFieldRow($model,'repeat_hour',array('class'=>'span5','maxlength'=>2)); ?>

	<?php echo $form->textFieldRow($model,'repeat_min',array('class'=>'span5','maxlength'=>2)); ?>

	<?php echo $form->textFieldRow($model,'repeat_weekday',array('class'=>'span5','maxlength'=>9)); ?>

	<?php echo $form->textFieldRow($model,'repeat_day_interval',array('class'=>'span5','maxlength'=>3)); ?>

	<?php echo $form->textFieldRow($model,'status',array('class'=>'span5','maxlength'=>18)); ?>

	<?php echo $form->textFieldRow($model,'created_at',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'updated_at',array('class'=>'span5')); ?>

	<?php echo $form->textFieldRow($model,'updated_by',array('class'=>'span5')); ?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array(
			'buttonType'=>'submit',
			'type'=>'primary',
			'label'=>'Search',
		)); ?>
	</div>

<?php $this->endWidget(); ?>
