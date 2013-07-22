<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<figure style="width: 300px; height: 300px;" id="myChart"></figure>
<script type="text/javascript">
jQuery.get('/index.php?r=site/getweightgoal', function(data){
	data = $.parseJSON(data);
	var o = data[0]['readings'];
	var count = data[0]['readings'].length;
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
		//yMin: 0,
		//yMax: 150,
		type : "bar",
		main :[{
			className: ".pizza",
			data: set
		}],
	};
	var myChart = new xChart('bar', data, '#myChart');
})

</script>