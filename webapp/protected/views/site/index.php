<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Goals',
);
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/xcharts.min.css" />
<?php
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/d3.js');
		Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/xcharts.min.js');
?>

<div class="row guttered">
      <div class="span5">
      	<figure style="height: 300px;" id="myChart"></figure>
      </div>      	
      <div class="span7">
      	<figure style="height: 300px;" id="myChart2"></figure>
      </div>
</div>

<script type="text/javascript">
jQuery.get('/index.php?r=site/getweightgoal', function(data){
	data = $.parseJSON(data);
	console.log(data);
	var o = data[0]['readings'];
	var count = data[0]['readings'].length;
	var target_weight = data[0]['weight'];
	target_weight = 100;
	var set = [];
	for(i=0;i<count;i++)
	{
	  set.push({
	  x : o[i].reading_date,
	  y : o[i].weight
	});
	}
	var data = {
		xScale : "ordinal",
		yScale : "linear",
		yMin: 30,
		yMax: target_weight,
		type : "line",
		main :[{
			className: ".goal-weight",
			data: set
		}],
	};
	var myChart = new xChart('line', data, '#myChart');
})

</script>


<script type="text/javascript">
jQuery.get('/index.php?r=site/getweightgoal', function(data){
	data = $.parseJSON(data);
	console.log(data);
	var o = data[0]['readings'];
	var count = data[0]['readings'].length;
	var target_weight = data[0]['weight'];
	target_weight = 100;
	var set = [];
	for(i=0;i<count;i++)
	{
	  set.push({
	  x : o[i].reading_date,
	  y : o[i].weight
	});
	}
	var data = {
		xScale : "ordinal",
		yScale : "linear",
		yMin: 30,
		yMax: target_weight,
		type : "line",
		main :[{
			className: ".goal-weight",
			data: set
		}],
	};
	var myChart = new xChart('line', data, '#myChart2');
})

</script>