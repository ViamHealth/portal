<?php
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/xcharts.min.css');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/d3.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/xcharts.min.js');
?>

<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Goals',
);
?>

<div id="goal-weight-view" ></div>
<script type="text/javascript">
	$.get('<?php echo $this->createUrl($weight_url) ?>', function(data){
		$('#goal-weight-view').html(data);
	});
</script>
<?php $this->renderPartial($weight_view, array('model'=>$wModel)); ?>
