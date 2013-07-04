<?php
$this->breadcrumbs=array(
	'Reminders'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Reminder','url'=>array('index')),
	array('label'=>'Create Reminder','url'=>array('create')),
	array('label'=>'Update Reminder','url'=>array('update','id'=>$model->id)),
	array('label'=>'Delete Reminder','url'=>'#','linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Reminder','url'=>array('admin')),
);
?>

<h1>View Reminder #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView',array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'details',
		'start_datetime',
		'repeat_mode',
		'repeat_day',
		'repeat_hour',
		'repeat_min',
		'repeat_weekday',
		'repeat_day_interval',
		'status',
		'created_at',
		'updated_at',
		'updated_by',
	),
)); ?>
