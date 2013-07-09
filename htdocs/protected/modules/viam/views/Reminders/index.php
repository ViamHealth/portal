<?php
/* @var $this SiteController */
$this->pageTitle=Yii::app()->name;
?>

<?php
$this->breadcrumbs=array(
 'Reminders',
); ?>

<body style="background: #eee">
<h1>Reminders</h1>

<div class="row-fluid">
<?php $this->widget('bootstrap.widgets.TbListView',array(
 'dataProvider'=>$ReminderModel->search(array('user_id'=>$profile_id)),
 'itemView'=>'_view',
)); ?>

	<div class="span4" style="border-radius: 5px #ccc; border: 1px solid #ccc; postion: relative; margin-left: 20px; margin-top: 10px; margin-bottom: 20px; height: 230px">
	  <div class="box-container" style="height: 200px; background: #eee">
	   
	   <div class="t_row" style="padding: 20px; background: #ffffff; height: 150px">
       </div>

       <div class="b_row" style="overflow: hidden; position: relative; background: #eee; height: 50px"> 
       <div class="btn-toolbar" style="margin-left: 10px; margin-top: 5px">
       <div class="btn btn-success">Add</div>
       </div>
       </div>

      </div>
	</div>

</div>
</body>