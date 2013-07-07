<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>

<?php
$this->breadcrumbs=array(
 'Reminders',
); ?>


<?php
$this->menu=array(
 array('label'=>'Create Reminder','url'=>array('create')),
 array('label'=>'Manage Reminder','url'=>array('admin')),
);
?>

<h1>Reminders</h1>

<div class="row-fluid">
<?php $this->widget('bootstrap.widgets.TbListView',array(
 'dataProvider'=>$ReminderModel->search(array('user_id'=>$profile_id)),
 'itemView'=>'_view',
)); ?>
</div>