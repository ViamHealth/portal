<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>

<?php
$this->breadcrumbs=array(
 'Reminders',
); ?>


<h1>Reminders</h1>


<?php $this->widget('bootstrap.widgets.TbListView',array(
 'dataProvider'=>$ReminderModel->search(array('user_id'=>$profile_id, 'status'=>'ACTIVE')),
 'itemView'=>'_view',
)); ?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
      'action'=>$this->createUrl('/reminders/add'),
      'id'=>'verticalForm',
      'htmlOptions'=>array('class'=>'well well-large span3'),
)); ?>
<div>
      <?php echo $form->textAreaRow($ReminderModel, 'details', array('class'=>'span3', 'rows'=>5)); ?>
      <?php echo $form->datepickerRow($ReminderModel, 'start_timestamp',
      array(
      'prepend'=>'<i class="icon-calendar"></i>',
      'options'=>array('format'=>'yyyy-mm-dd'),
      )); 
      ?>
      <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Add')); ?>
</div>
<?php $this->endWidget(); ?>

