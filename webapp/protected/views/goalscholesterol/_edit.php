<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Goals - Cholesterol- Create',
);
?>

<div class="row guttered">
      <div class="span4">
      	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		      'action'=>$this->createUrl('/goalscholesterol/add'),
		      'id'=>'inlineForm',
		      'type'=>'inline',
		      'htmlOptions'=>array('class'=>'span4'),
		)); ?>
		<div>
		      <?php echo $form->textFieldRow($model, 'ldl', array('class'=>'span2')); ?>
		      <br/>
		      <?php echo $form->textFieldRow($model, 'hdl', array('class'=>'span2')); ?>
		      <br/>
		      <?php echo $form->textFieldRow($model, 'triglycerides', array('class'=>'span2')); ?>
		      <br/>
		      <?php echo $form->textFieldRow($model, 'total_cholesterol', array('class'=>'span2')); ?>
		      <br/>
		      <?php echo $form->datepickerRow($model, 'target_date',
		      array(
		      'prepend'=>'<i class="icon-calendar"></i>',
		      'options'=>array('format'=>'yyyy-mm-dd'),
		      'class'=>'span2'
		      )); 
		      ?>
		      <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Add')); ?>
		</div>
		<?php $this->endWidget(); ?>
		
      </div>
</div>
