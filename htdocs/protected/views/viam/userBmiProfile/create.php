<?php
/* @var $this UserBmiProfileController */
/* @var $model UserBmiProfile */

$this->breadcrumbs=array(
	'User Bmi Profiles'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserBmiProfile', 'url'=>array('index')),
	array('label'=>'Manage UserBmiProfile', 'url'=>array('admin')),
);
?>

<h1>Create UserBmiProfile</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>