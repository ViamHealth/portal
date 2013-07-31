<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>

<?php
$this->breadcrumbs=array(
 'Reminders',
); ?>


<h1>Update Reminder</h1>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
      'action'=>Yii::app()->createUrl('reminders/update/'.$model->id),
      'id'=>'verticalForm',
      'htmlOptions'=>array('class'=>'well well-large span3'),
)); ?>
<div>
      <?php echo $form->textAreaRow($model, 'details', array('class'=>'span3', 'rows'=>5)); ?>
      <?php echo $form->datepickerRow($model, 'start_datetime',
      array(
      'prepend'=>'<i class="icon-calendar"></i>',
      'options'=>array('format'=>'yyyy-mm-dd'),
      )); 
      ?>
      <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Add')); ?>
</div>
<?php $this->endWidget(); ?>