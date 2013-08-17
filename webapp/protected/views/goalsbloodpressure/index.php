<?php
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/xcharts.min.css');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/d3.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/xcharts.min.js');
?>

<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Goals - Blood Pressure',
);
?>

<div class="row guttered">
  	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	      'action'=>Yii::app()->createUrl('goalsbloodpressure/setreading'),
	      'id'=>'inlineForm',
	      'type'=>'inline',
	      'htmlOptions'=>array('class'=>'span4'),
	)); ?>
	<div>
		  <?php echo $form->hiddenField($model, 'user_blood_pressure_goal_id', array("value"=>$user_blood_pressure_goal_id)); ?>
	      <?php echo $form->textFieldRow($model, 'systolic_pressure', array('class'=>'span2')); ?>
	      <?php echo $form->textFieldRow($model, 'diastolic_pressure', array('class'=>'span2')); ?>
	      <?php echo $form->textFieldRow($model, 'pulse_rate', array('class'=>'span2')); ?>
	      <?php echo $form->datepickerRow($model, 'reading_date',
	      array(
	      'prepend'=>'<i class="icon-calendar"></i>',
	      'options'=>array('format'=>'yyyy-mm-dd'),
	      'class'=>'span2'
	      )); 
	      ?>
	      <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Add')); ?>
	</div>
	<?php $this->endWidget(); ?>
	
</div>
<div class="row guttered">
	<figure style="height: 300px;" id="goalBloodPressureChart"></figure>
</div>

<script type="text/javascript">
jQuery.get('<?php echo $this->createUrl("/goalsbloodpressure/getgoal"); ?>', function(data){
	data = $.parseJSON(data);
	if(data.count == 0 ){
		$('#goalBloodPressureChart').html('Set a goal for blood pressure');
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
		var target_systolic_pressure = data[0]['systolic_pressure'];
		target_systolic_pressure = 100;
		var set = [],
			dpset = [],
			prset = [];

		for(i=0;i<count;i++)
		{
		  	set.push({
			  	x : o[i].reading_date,
			  	y : o[i].systolic_pressure
			});
			dpset.push({
			  	x : o[i].reading_date,
			  	y : o[i].diastolic_pressure
			});
			prset.push({
			  	x : o[i].reading_date,
			  	y : o[i].pulse_rate
			});
		}
		var data = {
			xScale : "ordinal",
			yScale : "linear",
			//yMin: 30,
			//yMax: target_weight,
			type : "line",
			main :
			[
				{ className: ".systolic_pressure", data: set },
				{ className: ".diastolic_pressure", data: dpset },
				//{ className: ".pulse_rate", data: prset },
			],
			comp : [{
				className: ".pulse_rate", 
				data: prset,
				"type": "line-dotted",
			}]
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
		var myChart = new xChart('line-dotted', data, '#goalBloodPressureChart', opts);
		return;		
	}
});
</script>
