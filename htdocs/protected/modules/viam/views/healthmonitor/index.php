<?php
$assetUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('viam.assets'));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($assetUrl.'/js/d3.js');
$cs->registerScriptFile($assetUrl.'/js/xcharts.js');
$cs->registerCssFile($assetUrl.'/css/xcharts.css');
?>

<h1>Goals</h1>

<?php /*
<figure style=" height: 300px;" id="myChart"></figure>
<script>
var data_gr = {
	xScale : "ordinal",
	yScale : "linear",
	type : "bar",
	main :[{
		className: ".pizza",
		data: [
		{
			x: "Pepperoni",
			y: 12
		},
		{
			x: "Cheese",
			y: 8
		}
		]
	}],
	comp :[{
		className: ".pizza",
		type: "line-dotted",
		data: [
		{
			x: "Pepperoni",
			y: 10
		},
		{
			x: "Cheese",
			y: 7
		}
		]
	}],
};

var myChart = new xChart('bar', data_gr, '#myChart');
</script>
*/ ?>
	 <div class="toolbar pull-right">
      <i class="icon-calendar"></i>
      year
      <div class="btn-group">
        <button class="btn">Today</button>
      </div>
      <div class="btn-group">
       <button class="btn">Day</button>
       <button class="btn">Week</button>
       <button class="btn">Month</button>
      </div>
      </div>

<div class="row-fluid">
<?php $this->widget('bootstrap.widgets.TbListView',array(
 'dataProvider'=>UserWeightGoal::model()->search(array('user_id'=>$profile_id)),
 //'dataProvider'=>UserWeightReading::model()->search(3),
 'itemView'=>'_view',
)); ?>

</div>