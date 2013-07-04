<?php
$this->breadcrumbs=array(
	'Reminders',
);

$this->menu=array(
	array('label'=>'Create Reminder','url'=>array('create')),
	array('label'=>'Manage Reminder','url'=>array('admin')),
);
?>

<h1>Reminders</h1>

<div class="row">
<?php $this->widget('bootstrap.widgets.TbListView',array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
</div>
