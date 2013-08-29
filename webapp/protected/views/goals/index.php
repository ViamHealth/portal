<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.validate.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.ui.widget.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/highcharts/highcharts.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/highcharts/modules/exporting.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/weight-goals.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/blood-pressure-goals.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/cholesterol-goals.js');
?>

<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Goals',
);
?>


<div class="row-fuild">
	<!-- Weight -->
	<div  class="span6" >
		<a href="#" id="weight_goal_reading_open" goal_id="" style="display:none;" class="pull-right">Add Reading</a>
		<a href="#" id="weight_goal_delete" goal_id="" style="display:none;" class="pull-right">Delete Goal !</a>
		<div id="weight-chart" style="height: 400px; margin: 0 auto">
		</div>
		<form id="weight-goal-add" class="form-inline" style="display:none;">
			Create a weight goal
			<br/>
      		<input id="weight_goal_weight" type="text" name="weight_goal_weight" class="input input-small" required placeholder="Target weight"/>
      		<input id="weight_goal_target_date" type="date" name="weight_goal_target_date" class="input input-medium" required/>
      		<button class="btn btn-primary" id="save-weight-goal">Save</button>
		</form>
	</div>
	<!-- blood pressure -->
	<div  class="span6" >
		<a href="#" id="blood_pressure_goal_reading_open" goal_id="" style="display:none;" class="pull-right">Add Reading</a>
		<a href="#" id="blood_pressure_goal_delete" goal_id="" style="display:none;" class="pull-right">Delete Goal !</a>
		<div id="blood-pressure-chart" style="height: 400px; margin: 0 auto">
		</div>
		<form id="blood-pressure-goal-add" class="form-inline" style="display:none;">
			Create a blood-pressure goal
			<br/>
      		<input id="blood_pressure_goal_systolic_pressure" type="text" name="blood_pressure_goal_systolic_pressure" class="input input-small" required placeholder="Target systolic pressure"/>
      		<input id="blood_pressure_goal_diastolic_pressure" type="text" name="blood_pressure_goal_diastolic_pressure" class="input input-small" required placeholder="diastolic pressure"/>
      		<br/>
      		<input id="blood_pressure_goal_pulse_rate" type="text" name="blood_pressure_goal_pulse_rate" class="input input-small" required placeholder="pulse rate"/>
      		<input id="blood_pressure_goal_target_date" type="date" name="blood_pressure_goal_target_date" class="input input-medium" required/>
      		
      		<button class="btn btn-primary" id="save-blood-pressure-goal">Save</button>
		</form>
	</div>
</div>

<div class="row-fuild" >
	<!-- cholesterol -->
	<div  class="span12" style="margin-top:40px;">
		<a href="#" id="cholesterol_goal_reading_open" goal_id="" style="display:none;" class="pull-right">Add Reading</a>
		<a href="#" id="cholesterol_goal_delete" goal_id="" style="display:none;" class="pull-right">Delete Goal !</a>
		<div id="cholesterol-chart" style="height: 400px; margin: 0 auto">
		</div>
		<form id="cholesterol-goal-add" class="form-inline" style="display:none;">
			Create a cholesterol goal
			<br/>
      		<input id="cholesterol_goal_hdl" type="text" name="cholesterol_goal_hdl" class="input input-small" required placeholder="Target HDL"/>
      		<input id="cholesterol_goal_ldl" type="text" name="cholesterol_goal_ldl" class="input input-small" required placeholder="Target LDL"/>
      		<br/>
      		<input id="cholesterol_goal_triglycerides" type="text" name="cholesterol_goal_triglycerides" class="input input-small" required placeholder="Target triglycerides"/>
      		<input id="cholesterol_goal_total_cholesterol" type="text" name="cholesterol_goal_total_cholesterol" class="input input-small" required placeholder="Target total_cholesterol"/>
      		<br/>      		
      		<input id="cholesterol_goal_target_date" type="date" name="cholesterol_goal_target_date" class="input input-medium" required/>
      		
      		<button class="btn btn-primary" id="save-cholesterol-goal">Save</button>
		</form>
	</div>

</div>




<!-- Modals -->
<div id="weight-goal-reading-model" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Add reading for weight goal" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    Add a new reading
  </div>
  <div class="modal-body" itemid="">
    <form id="weight-goal-reading-add" class="form-inline" goal_id="">
      		<input id="weight_goal_reading_weight" type="text" name="weight_goal_reading_weight" class="input input-small" required placeholder="weight"/>
      		<input id="weight_goal_reading_reading_date" type="date" name="weight_goal_reading_reading_date" class="input input-medium" required/>
      		<button class="btn btn-primary" id="save-weight-reading">Save</button>
		</form>
  </div>
</div>

<div id="blood-pressure-goal-reading-model" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Add reading for goal" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    Add a new bp reading
  </div>
  <div class="modal-body" itemid="">
    <form id="blood-pressure-goal-reading-add" class="form-inline" goal_id="">
      		<input id="blood_pressure_goal_reading_systolic_pressure" type="text" name="blood_pressure_goal_reading_systolic_pressure" class="input input-small" required placeholder="systolic pressure"/>
      		<input id="blood_pressure_goal_reading_diastolic_pressure" type="text" name="blood_pressure_goal_reading_diastolic_pressure" class="input input-small" required placeholder="diastolic pressure"/>
      		<br/>
      		<input id="blood_pressure_goal_reading_pulse_rate" type="text" name="blood_pressure_goal_reading_pulse_rate" class="input input-small" required placeholder="pulse rate"/>
      		<input id="blood_pressure_goal_reading_reading_date" type="date" name="blood_pressure_goal_reading_reading_date" class="input input-medium" required/>
      		<button class="btn btn-primary" id="save-blood-pressure-reading">Save</button>
		</form>
  </div>
</div>

<div id="cholesterol-goal-reading-model" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Add reading for goal" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    Add a new cholesterol reading
  </div>
  <div class="modal-body" itemid="">
    <form id="cholesterol-goal-reading-add" class="form-inline" goal_id="">
      		<input id="cholesterol_goal_reading_hdl" type="text" name="cholesterol_goal_reading_hdl" class="input input-small" required placeholder="hdl"/>
      		<input id="cholesterol_goal_reading_ldl" type="text" name="cholesterol_goal_reading_ldl" class="input input-small" required placeholder="ldl"/>
      		<br/>
      		<input id="cholesterol_goal_reading_triglycerides" type="text" name="cholesterol_goal_reading_triglycerides" class="input input-small" required placeholder="triglycerides"/>
      		<input id="cholesterol_goal_reading_total_cholesterol" type="text" name="cholesterol_goal_reading_total_cholesterol" class="input input-small" required placeholder="total_cholesterol"/>
      		<br/>
      		<input id="cholesterol_goal_reading_reading_date" type="date" name="cholesterol_goal_reading_reading_date" class="input input-medium" required/>
      		<button class="btn btn-primary" id="save-cholesterol-reading">Save</button>
		</form>
  </div>
</div>

<!-- Modals end-->
<script type="text/javascript">
VH.vars.profile_id = '<?php echo $profile_id; ?>';

var stacks = {};

stacks['weight'] ={};
stacks['weight']['model'] = _DB.WeightGoal;
stacks['weight']['add_reading_model'] = $("#weight-goal-reading-model");
stacks['weight']['add_reading_form'] = $("#weight-goal-reading-add");
stacks['weight']['add_reading_form_save'] = $("#save-weight-reading");
stacks['weight']['click_to_add_reading'] = $("#weight_goal_reading_open");
stacks['weight']['chart_container'] = $("#weight-chart");
stacks['weight']['new_goal_form'] = $("#weight-goal-add");
stacks['weight']['new_goal_form_save_button'] = $("#save-weight-goal");
stacks['weight']['delete_goal_button'] = $("#weight_goal_delete");
stacks['weight']['yaxis'] = [
	{   'field':'weight',
		'label':'Weight', 
		'readings': true,
	},
	{   'field':'weight',
		'label':'Target Weight', 
		'readings': false,
	},
	];
stacks['weight']['plots'] = [
	{	'field': 'weight',
		'label':'Healthy weight range',
	},
];

stacks['blood_pressure'] ={};
stacks['blood_pressure']['model'] = _DB.BloodPressureGoal;
stacks['blood_pressure']['add_reading_model'] = $("#blood-pressure-goal-reading-model");
stacks['blood_pressure']['add_reading_form'] = $("#blood-pressure-goal-reading-add");
stacks['blood_pressure']['add_reading_form_save'] = $("#save-blood-pressure-reading");
stacks['blood_pressure']['click_to_add_reading'] = $("#blood_pressure_goal_reading_open");
stacks['blood_pressure']['chart_container'] = $("#blood-pressure-chart");
stacks['blood_pressure']['new_goal_form'] = $("#blood-pressure-goal-add");
stacks['blood_pressure']['new_goal_form_save_button'] = $("#save-blood-pressure-goal");
stacks['blood_pressure']['delete_goal_button'] = $("#blood_pressure_goal_delete");
stacks['blood_pressure']['yaxis'] = [
	{   'field':'systolic_pressure',
		'label':'systolic pressure', 
		'readings': true,
	},
	{   'field':'diastolic_pressure',
		'label':'diastolic pressure', 
		'readings': true,
	},
	{   'field':'systolic_pressure',
		'label':'target systolic pressure', 
		'readings': false,
	},
	{   'field':'diastolic_pressure',
		'label':'target diastolic pressure', 
		'readings': false,
	},
	];
stacks['blood_pressure']['plots'] = [
	{	'field': 'systolic_pressure',
		'label':'Healthy systolic pressure',
	},
	{	'field': 'diastolic_pressure',
		'label':'Healthy diastolic pressure',
	},
];


stacks['cholesterol'] ={};
stacks['cholesterol']['model'] = _DB.CholesterolGoal;
stacks['cholesterol']['add_reading_model'] = $("#cholesterol-goal-reading-model");
stacks['cholesterol']['add_reading_form'] = $("#cholesterol-goal-reading-add");
stacks['cholesterol']['add_reading_form_save'] = $("#save-cholesterol-reading");
stacks['cholesterol']['click_to_add_reading'] = $("#cholesterol_goal_reading_open");
stacks['cholesterol']['chart_container'] = $("#cholesterol-chart");
stacks['cholesterol']['new_goal_form'] = $("#cholesterol-goal-add");
stacks['cholesterol']['new_goal_form_save_button'] = $("#save-cholesterol-goal");
stacks['cholesterol']['delete_goal_button'] = $("#cholesterol_goal_delete");
stacks['cholesterol']['yaxis'] = [
	{   'field':'hdl',
		'label':'HDL', 
		'readings': true,
	},
	{   'field':'ldl',
		'label':'LDL', 
		'readings': true,
	},
	{   'field':'triglycerides',
		'label':'triglycerides', 
		'readings': true,
	},
	{   'field':'total_cholesterol',
		'label':'total cholesterol', 
		'readings': true,
	},
	
	{   'field':'hdl',
		'label':'target HDL', 
		'readings': false,
	},
	{   'field':'ldl',
		'label':'target LDL', 
		'readings': false,
	},
	{   'field':'triglycerides',
		'label':'target triglycerides', 
		'readings': false,
	},
	{   'field':'total_cholesterol',
		'label':'target total cholesterol', 
		'readings': false,
	},
	];

stacks['cholesterol']['plots'] = [
	{	'field': 'hdl',
		'label':'Healthy HDL',
	},
	{	'field': 'ldl',
		'label':'Healthy LDL',
	},
	{	'field': 'total_cholesterol',
		'label':'Healthy total cholesterol',
	},
];

function isFunction(functionToCheck) {
 var getType = {};
 return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
}

function apiDateToGraphDate(apidate){
	if(!apidate){
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!

		var yyyy = today.getFullYear();
		if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+'-'+mm+'-'+dd;
		var _tmp_parts = today.split('-');
		return Date.UTC(_tmp_parts[0],  _tmp_parts[1]-1, _tmp_parts[2]);
	} else {
		var _tmp_parts = apidate.split('-');
		return Date.UTC(_tmp_parts[0],  _tmp_parts[1]-1, _tmp_parts[2]);
	}
}
function graphDateToApiDate(timestamp){
	var date = new Date(timestamp);
	var mm = parseInt(date.getMonth())+1;
	if(mm < 10) mm = '0'+mm.toString();
	var dd = date.getDate();
	if(dd < 10) dd = '0'+dd.toString();
	var d = date.getFullYear()+"-"+mm+"-"+dd;

	return d;

}


function delete_reading(goal_type, goal_id, reading_id, point){
	var _stack = stacks[goal_type];
	_stack['model'].destroy_reading(goal_id, reading_id, function(json,success){
		point.remove();
	});
}

function event_delete_goal_button(goal_type,populate_graph_function){
	var _stack = stacks[goal_type];
	$(_stack['delete_goal_button']).click(function(event){
		var id = $(_stack['delete_goal_button']).attr("goal_id");
		event.preventDefault();
		_stack['model'].destroy(id,function(){
			populate_graph_function();
		});
	});
}

function event_click_to_add_reading(goal_type){
	var _stack = stacks[goal_type];
	$(_stack['click_to_add_reading']).click(function(){
		$(_stack['add_reading_form']).attr("goal_id",$(this).attr("goal_id"));
		$(_stack['add_reading_model']).modal();
	});
}

$(document).ready(function(){
	attach_blood_pressure_events();
	attach_weight_events();
	attach_cholesterol_events();
	populate_weight_graph();
	populate_blood_pressure_graph();
	populate_cholesterol_graph();
});


function populate_graph(goal_type,options){
	var options = options || {};
	var _stack = stacks[goal_type];
	$(_stack['chart_container']).hide();
	$(_stack['new_goal_form']).hide();
	_stack['model'].list(function(json,success){
		if(success){
			if(json.count){
				
				var goal = json.results[0];
				$(_stack['chart_container']).show();
				$(_stack['click_to_add_reading']).show();
				$(_stack['click_to_add_reading']).attr("goal_id",goal.id);
				$(_stack['delete_goal_button']).attr("goal_id",goal.id);
				$(_stack['delete_goal_button']).show();

				if( goal.readings.length)
					var time_start = apiDateToGraphDate(goal.readings[0].reading_date);
				else
					var time_start = apiDateToGraphDate();

				//series
				var _series = [];
				for(var i=0 ;i < _stack['yaxis'].length; i++){
					var arf = _stack['yaxis'][i]['field'];
					var arl = _stack['yaxis'][i]['label'];
					var ag = {};
					ag['name'] = arl;
					ag['data'] = [];
					if(_stack['yaxis'][i]['readings']){
						for(var j =0;j<goal.readings.length;j++) {
						  	ag['data'].push([ apiDateToGraphDate(goal.readings[j].reading_date), goal.readings[j][arf] ]);
						}
					}
					else {
						ag['data'].push([ apiDateToGraphDate(goal.target_date), goal[arf], ]);
					}

					_series.push(ag);
				}
				console.log(_series);
				//plot bands
				var _bands = [];
				for(i=0;i< _stack['plots'].length; i++){
					var arf = _stack['plots'][i]['field'];

					var ap = {};
					ap['from'] = goal.healthy_range[arf].max;
					ap['to'] = goal.healthy_range[arf].min;
					ap['color'] = 'rgba(68, 170, 213, 0.1)';
					ap['label'] = {
						'text' : _stack['plots'][i]['label'],
						'style': {'color': '#606060'}
					}
					_bands.push(ap);
				}

				$(_stack['chart_container']).highcharts({
					exporting: {enabled: false},
		            chart: {type: 'spline'},
		            title: {text: 'Blood Pressure Goal'},
		            subtitle: {text: ''},
		            xAxis: {
		                type: 'datetime',
		                dateTimeLabelFormats: { // don't display the dummy year
		                    month: '%e. %b',
		                    year: '%b'
		                },
		            },
		            yAxis: {
		                title: {
		                    text: ''
		                },
		                min: 0,
		                minorGridLineWidth: 0,
		                gridLineWidth: 0,
		                alternateGridColor: null,
		                plotBands: _bands,
		            },
		            tooltip: {valueSuffix: ' mmHg'},
		            plotOptions: {
		                spline: {
		                    lineWidth: 1,
		                    states: {
		                        hover: {lineWidth: 2}
		                    },
		                    marker: {enabled: true},
		                    point: {
		                        events: {
		                            'click': function() {
		                            	delete_reading(goal_type,goal.id, (graphDateToApiDate(this.x)), this);
		                            	//this.remove();
		                                //if (this.series.data.length > 1) this.remove();
		                            }
		                        }
		                    },
		                    //pointInterval: 3600000, // one hour
		                    pointStart: time_start
		                }
		            },
		            series: _series,
		            navigation: {menuItemStyle: {fontSize: '10px'}}
		        });
			} else {
				if(isFunction(options['no_goal_action'])){
					options['no_goal_action']();
				} else {
					$(_stack['new_goal_form']).show();	
				}
			}
		}
	});
}

</script>