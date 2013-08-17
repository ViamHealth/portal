<?php
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/xcharts.min.css');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/d3.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/xcharts.min.js');
?>

<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Goals - cholesterol',
);
?>
Add Readings :- 
<div class="row guttered">

  	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	      'action'=>Yii::app()->createUrl('goalscholesterol/setreading'),
	      'id'=>'inlineForm',
	      'type'=>'inline',
	      'htmlOptions'=>array('class'=>'span4'),
	)); ?>
	<div>
		  <?php echo $form->hiddenField($model, 'user_cholesterol_goal_id', array("value"=>$user_cholesterol_goal_id)); ?>
	      <?php echo $form->textFieldRow($model, 'ldl', array('class'=>'span2')); ?>
	      <?php echo $form->textFieldRow($model, 'hdl', array('class'=>'span2')); ?>
	      <?php echo $form->textFieldRow($model, 'triglycerides', array('class'=>'span2')); ?>
	      <?php echo $form->textFieldRow($model, 'total_cholesterol', array('class'=>'span2')); ?>
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
	<figure style="height: 300px;" id="goalCholesterolChart"></figure>
</div>

<script type="text/javascript">
jQuery.get('<?php echo $this->createUrl("/goalscholesterol/getgoal"); ?>', function(data){
	data = $.parseJSON(data);
	if(data.count == 0 ){
		$('#goalCholesterolChart').html('Set a goal for cholesterol');
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
		var total_cholesterol = data[0]['total_cholesterol'];
		total_cholesterol = 100;
		var set = [],
			dpset = [],
			prset = [],
			tcset = [];

		for(i=0;i<count;i++)
		{
		  	set.push({
			  	x : o[i].reading_date,
			  	y : o[i].ldl
			});
			dpset.push({
			  	x : o[i].reading_date,
			  	y : o[i].hdl
			});
			prset.push({
			  	x : o[i].reading_date,
			  	y : o[i].triglycerides
			});
			tcset.push({
			  	x : o[i].reading_date,
			  	y : o[i].total_cholesterol
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
				{ className: ".ldl", data: set },
				{ className: ".hdl", data: dpset },
				{ className: ".triglycerides", data: prset },
				//{ className: ".pulse_rate", data: prset },
			],
			comp : [{
				className: ".total_cholesterol", 
				data: tcset,
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
		var myChart = new xChart('line-dotted', data, '#goalCholesterolChart', opts);
		return;		
	}
});
</script>
