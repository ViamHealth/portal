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

<div class="row guttered">
      <div class="span4">
      	<figure style="height: 300px;" id="goalWeightChart"></figure>
      </div>
</div>

<script type="text/javascript">
jQuery.get('/index.php?r=goals/getweightgoal', function(data){
	data = $.parseJSON(data);
	if(data.count == 0 ){
		$('#goalWeightChart').html('Set a goal for weight');
		return;
	} else {
		data = data.results;
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
		var myChart = new xChart('line', data, '#goalWeightChart');	
		return;		
	}
});
</script>
