<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>

<?php
$this->breadcrumbs=array(
 'Healthfiles',
); ?>


<h1>Update healthfile</h1>


<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
      'action'=>$this->createUrl('/healthfiles/update/'.$model->id),
      'id'=>'verticalForm',
      'htmlOptions'=>array('class'=>'well well-large span3'),
)); ?>
<div>
      <?php echo $form->uneditableRow($model, 'name'); ?>
      <?php echo $form->textAreaRow($model, 'description', array('class'=>'span3', 'rows'=>5)); ?>
      <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Modify')); ?>
</div>
<?php $this->endWidget(); ?>