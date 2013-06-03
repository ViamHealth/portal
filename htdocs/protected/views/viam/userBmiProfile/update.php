<?php
/* @var $this UserBmiProfileController */
/* @var $model UserBmiProfile */

$this->breadcrumbs=array(
	'User Bmi Profiles'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserBmiProfile', 'url'=>array('index')),
	array('label'=>'Create UserBmiProfile', 'url'=>array('create')),
	array('label'=>'View UserBmiProfile', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UserBmiProfile', 'url'=>array('admin')),
);
?>

<h1>Update UserBmiProfile <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>