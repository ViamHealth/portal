<?php
/* @var $this UserBmiProfileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Bmi Profiles',
);

$this->menu=array(
	array('label'=>'Create UserBmiProfile', 'url'=>array('create')),
	array('label'=>'Manage UserBmiProfile', 'url'=>array('admin')),
);
?>

<h1>User Bmi Profiles</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
