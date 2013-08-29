<?php
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/xcharts.min.css');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/d3.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/xcharts.min.js');
?>

<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Goals - Weight',
);
?>

<div class="guttered">
  <div class="row-fluid">
  	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	      'action'=>Yii::app()->createUrl('goalsweight/setreading'),
	      'id'=>'inlineForm',
	      'type'=>'inline',
	      'htmlOptions'=>array('class'=>'span12'),
	)); ?>
	<div>
		  <?php echo $form->hiddenField($model, 'user_weight_goal_id', array("value"=>$user_weight_goal_id)); ?>
	      <?php echo $form->textFieldRow($model, 'weight', array('class'=>'span2')); ?>
	      <?php echo $form->datepickerRow($model, 'reading_date',
	      array(
	      'prepend'=>'<i class="icon-calendar"></i>',
	      'options'=>array('format'=>'yyyy-mm-dd'),
	      'class'=>'span8'
	      )); 
	      ?>
	      <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Add')); ?>
	</div>
	<?php $this->endWidget(); ?>
	<figure style="height: 300px;" id="goalWeightChart"></figure>
  </div>
</div>

<script type="text/javascript">
jQuery.get('<?php echo $this->createUrl("/goalsweight/getweightgoal"); ?>', function(data){
	data = $.parseJSON(data);
	if(data.count == 0 ){
		$('#goalWeightChart').html('Set a goal for weight');
		return;
	} else {
		var tt = document.createElement('div'),
		leftOffset = -(~~$('html').css('padding-left').replace('px', '') + ~~$('body').css('margin-left').replace('px', '')),
		topOffset = -32;
		tt.className = 'ex-tooltip';
		document.body.appendChild(tt);
		data = data.results;
		var o = data[0]['readings'];
		var count = data[0]['readings'].length;
		var target_weight = data[0]['weight'];
		target_weight = 100;
		var set = [];
		var xset = [];
		var iset = [];
		var gset = [];
		

		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!

		var yyyy = today.getFullYear();
		if(dd<10){dd='0'+dd};
		if(mm<10){mm='0'+mm};

		min_reading_date = yyyy+'-'+mm+'-'+dd;
		max_reading_date = data[0].target_date;

		if(o[1] != undefined ){
			min_reading_date = 	o[1].reading_date;
			max_reading_date = data[0].target_date;
		}

		for(i=0;i<count;i++)
		{
		  	set.push({
			  	x : o[i].reading_date,
			  	y : o[i].weight
			});
		}

		var date_ranges = [];
		var _old_date = min_reading_date;
		date_ranges[0] = _old_date;
		var _tmp_date = new Date();
		var _tmp_parts = _old_date.split('-');
		_tmp_date.setFullYear(_tmp_parts[0], _tmp_parts[1]-1,_tmp_parts[2]);
		i = 1;
		while(1){			
			_tmp_date.setTime(_tmp_date.getTime() + 86400000);
			
			var dd = _tmp_date.getDate();
			var mm = _tmp_date.getMonth()+1; //January is 0!
			if(dd<10){dd='0'+dd};
			if(mm<10){mm='0'+mm};
			_old_date = yyyy+'-'+mm+'-'+dd;
			date_ranges[i] = _old_date;
			i++;
			
			if(_old_date == max_reading_date){
				date_ranges[i] = max_reading_date;
				break;
			}
			if(i>300)
				break;
		}
		
		gset.push({
			x : max_reading_date,
			y : data[0].weight
		})
		

		for(i=0;i<date_ranges.length;i++)
		{
			xset.push({
			  	y : data[0].healthy_range.weight.min,
			  	x : date_ranges[i],
			});
			iset.push({
			  	y : data[0].healthy_range.weight.max,
			  	x : date_ranges[i],
			});
		}
		var data = {
			xScale : "ordinal",
			yScale : "linear",
			yMin: 30,
			//yMax: target_weight,
			type : "line",
			main :[
			{
				className: ".goal-weight",
				data: set
			},
			{ className: ".ldl", data: xset },
			{ className: ".ldld", data: iset },
			{ className: ".abc", data: gset },
			],

		};
		var opts = {
		  //"dataFormatX": function (x) { return d3.time.format('%Y-%m-%d').parse(x); },
		  "dataFormatX": function (x) { return x; },
		  //"tickFormatX": function (x) { return d3.time.format('%A')(x); },
		  "mouseover": function (d, i) {
		    var pos = $(this).offset();
//		    $(tt).text(d3.time.format('%A')(d.x) + ': ' + d.y)
			$(tt).text('...')
		      .css({top: topOffset + pos.top, left: pos.left + leftOffset})
		      .show();
		  },
		  "mouseout": function (x) {
		    $(tt).hide();
		  }
		};
		var myChart = new xChart('bar', data, '#goalWeightChart', opts);
		return;		
	}
});
</script>
