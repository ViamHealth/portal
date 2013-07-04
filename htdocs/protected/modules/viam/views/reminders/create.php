<?php
$this->breadcrumbs=array(
	'Reminders'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Reminder','url'=>array('index')),
	array('label'=>'Manage Reminder','url'=>array('admin')),
);
?>

<h1>Create Reminder</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>