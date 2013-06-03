<?php
/* @var $this UserBmiProfileController */
/* @var $model UserBmiProfile */

$this->breadcrumbs=array(
	'User Bmi Profiles'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UserBmiProfile', 'url'=>array('index')),
	array('label'=>'Create UserBmiProfile', 'url'=>array('create')),
	array('label'=>'Update UserBmiProfile', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserBmiProfile', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserBmiProfile', 'url'=>array('admin')),
);
?>

<h1>View UserBmiProfile #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'height',
		'weight',
		'created_at',
	),
)); ?>
