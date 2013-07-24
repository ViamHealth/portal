<div class="row guttered">
      <div class="span4">
      	<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		      'action'=>Yii::app()->createUrl('goals/addreading'),
		      'id'=>'inlineForm',
		      'type'=>'inline',
		      'htmlOptions'=>array('class'=>'span4'),
		)); ?>
		<div>
		      <?php echo $form->textFieldRow($model, 'weight', array('class'=>'span1')); ?>
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
		var tt = document.createElement('div'),
		leftOffset = -(~~$('html').css('padding-left').replace('px', '') + ~~$('body').css('margin-left').replace('px', '')),
		topOffset = -32;
		tt.className = 'ex-tooltip';
		document.body.appendChild(tt);
		data = data.results;
		var o = data[0]['readings'];
		console.log(o);
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
			//yMin: 30,
			//yMax: target_weight,
			//type : "line",
			main :[{
				className: ".goal-weight",
				data: set
			}],
		};
		var opts = {
		  //"dataFormatX": function (x) { return d3.time.format('%Y-%m-%d').parse(x); },
		  "dataFormatX": function (x) { return x; },
		  //"tickFormatX": function (x) { return d3.time.format('%A')(x); },
		  "mouseover": function (d, i) {
		    var pos = $(this).offset();
		    console.log(pos);
//		    $(tt).text(d3.time.format('%A')(d.x) + ': ' + d.y)
			$(tt).text('Edit')
		      .css({top: topOffset + pos.top, left: pos.left + leftOffset})
		      .show();
		  },
		  "mouseout": function (x) {
		    $(tt).hide();
		  }
		};
		var myChart = new xChart('line-dotted', data, '#goalWeightChart', opts);
		return;		
	}
});
</script>
