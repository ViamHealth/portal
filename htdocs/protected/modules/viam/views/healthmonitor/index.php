<?php
$assetUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('viam.assets'));
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($assetUrl.'/js/d3.js');
$cs->registerScriptFile($assetUrl.'/js/xcharts.js');
$cs->registerCssFile($assetUrl.'/css/xcharts.css');
?>

<br/><br/><br/>Health Monitor Controller

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
