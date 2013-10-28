<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.validate.min.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/bootbox.min.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/bootstrap-datepicker.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/highcharts/highcharts.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/highcharts/modules/exporting.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/weight-goals.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/blood-pressure-goals.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/cholesterol-goals.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/glucose-goals.js');
?>

<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Goals',
);
?>
<style>
.cls_settings {
	background: url(/images/sprite-icn.png) no-repeat left -1152px;
}
.chart-container {
	background: #eef8fd;
	border: 1px solid #0099cc;
	border-radius:5px;
	margin-top:10px;
}
.chart-title {
	border-bottom: 1px solid #cccccc;
	padding:5px 10px;
	font: bold 12px Segoe UI,Arial,Helvetica,sans-serif;
	color: #333;
}
.chart-content {
	margin: 20px;
}
.modal-header {

border-bottom: 1px solid #aaa;
	font: bold 18px Segoe UI, Arial, Helvetica, sans-serif;
color: #666666;
line-height: 30px;

}
.modal-body {
	background: #efefef;
border-bottom: 1px solid #aaa;
}
.modal-body label {
	font: 600 14px Segoe UI, Arial, Helvetica, sans-serif;	
	cursor: auto;
}

.weight-inputs .wval {
	float: left;
width: 72px;
height: 32px;
font: 24px Segoe UI,Helvetica,Arial,sans-serif;
color: #666;
background: url(/images/ic_weight.png) no-repeat left center;
line-height: 32px;
padding: 0 0 0 40px;
}
.weight-inputs .spnr {
	float: left;
	width: 16px;
padding: 4px 0 3px 0;
margin: 0 0 0 5px;
}
.weight-inputs .spnr .inc {
	height: 8px;
background-image: url(/images/sprite-icn.png);
background-position: right -153px;
background-repeat: no-repeat;
display: block;
margin-bottom: 5px;
}
.weight-inputs .spnr .dec {
	height: 8px;
background-image: url(/images/sprite-icn.png);
background-position: right -161px;
background-repeat: no-repeat;
display: block;
margin-top: 5px;
}
.datepicker{
	z-index:1151;
	cursor: pointer;
	border-radius: 0px;
	text-align: center;
}
.datepicker td{
	font-size: x-small;
	padding: 9px 10px;
}
.datepicker .prev {
	/*width: 12px;
height: 11px;
background-image: url(/images/sprite-img.png);
background-repeat: no-repeat;
margin: 0;
background-position: 0 -538px;*/
}
.datepicker th{
	font-size: small;
	padding: 9px 10px;
	font-weight: normal;
}
</style>
<?php
$this->renderPartial('_weight',array());
$this->renderPartial('_models_weight',array());
$this->renderPartial('_models_blood-pressure',array());
$this->renderPartial('_models_cholesterol',array());
$this->renderPartial('_ahtml',array());
?>
<div style="display:none;">
	<div id="goal_menu_dropdown">
		<a href="#" class="goal_reading_open" >Add Reading</a>
		<br/>
		<!--<a href="#" class="goal_delete"  >Delete Goal !</a>-->
		<br/>
		<a href="#" class="manage-goals" >Manage Goals</a>
	</div>
</div>
<!-- Modals end-->
<script type="text/javascript">
VH.vars.profile_id = find_family_user_id()?find_family_user_id():'<?php echo $profile_id; ?>';
//console.log(VH.vars.profile_id);
var stacks = {};

stacks['weight'] ={};
stacks['weight']['model'] = _DB.WeightGoal;
stacks['weight']['model_reading'] = _DB.WeightReading;
stacks['weight']['graph_title'] = 'Weight Goal';
stacks['weight']['add_reading_model'] = $("#weight-goal-reading-model");
stacks['weight']['add_reading_form'] = $("#weight-goal-reading-add");
stacks['weight']['add_reading_form_save'] = $("#save-weight-reading");
//notuser below line
stacks['weight']['click_to_add_reading'] = $(".weight_goal_reading_open");
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
stacks['blood_pressure']['graph_title'] = 'Blood pressure Goal';
stacks['blood_pressure']['add_reading_model'] = $("#blood-pressure-goal-reading-model");
stacks['blood_pressure']['add_reading_form'] = $("#blood-pressure-goal-reading-add");
stacks['blood_pressure']['add_reading_form_save'] = $("#save-blood-pressure-reading");
stacks['blood_pressure']['click_to_add_reading'] = $(".blood_pressure_goal_reading_open");
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
		'color':'rgba(243, 14, 14, 0.1)',
	},
];


stacks['cholesterol'] ={};
stacks['cholesterol']['model'] = _DB.CholesterolGoal;
stacks['cholesterol']['graph_title'] = 'Cholesterol Goal';
stacks['cholesterol']['add_reading_model'] = $("#cholesterol-goal-reading-model");
stacks['cholesterol']['add_reading_form'] = $("#cholesterol-goal-reading-add");
stacks['cholesterol']['add_reading_form_save'] = $("#save-cholesterol-reading");
stacks['cholesterol']['click_to_add_reading'] = $(".cholesterol_goal_reading_open");
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
		'color':'rgba(243, 14, 14, 0.1)',
	},
	{	'field': 'total_cholesterol',
		'label':'Healthy total cholesterol',
	},
];

stacks['glucose'] ={};
stacks['glucose']['model'] = _DB.GlucoseGoal;
stacks['glucose']['graph_title'] = 'glucose Goal';
stacks['glucose']['add_reading_model'] = $("#glucose-goal-reading-model");
stacks['glucose']['add_reading_form'] = $("#glucose-goal-reading-add");
stacks['glucose']['add_reading_form_save'] = $("#save-glucose-reading");
stacks['glucose']['click_to_add_reading'] = $("#glucose_goal_reading_open");
stacks['glucose']['chart_container'] = $("#glucose-chart");
stacks['glucose']['new_goal_form'] = $("#glucose-goal-add");
stacks['glucose']['new_goal_form_save_button'] = $("#save-glucose-goal");
stacks['glucose']['delete_goal_button'] = $("#glucose_goal_delete");
stacks['glucose']['yaxis'] = [
	{   'field':'random',
		'label':'Random Glucose', 
		'readings': true,
	},
	{   'field':'fasting',
		'label':'Fasting Glucose', 
		'readings': true,
	},
	{   'field':'random',
		'label':'target Random Glucose', 
		'readings': false,
	},
	{   'field':'fasting',
		'label':'target fasting Glucose', 
		'readings': false,
	},
	];

stacks['glucose']['plots'] = [
	{	'field': 'random',
		'label':'Healthy random glucose',
		'color':'rgba(68, 170, 213, 0.1)',
	},
	{	'field': 'fasting',
		'label':'Healthy fasting',
		'color':'rgba(243, 14, 14, 0.1)',
	},
];



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


function delete_reading(goal_type, reading_date, point){
	bootbox.confirm("Delete Reading?", function(result) {
	  //Example.show("Confirm result: "+result);
	  if(result){
		var _stack = stacks[goal_type];
		_stack['model_reading'].destroy(reading_date, function(json,success){
			point.remove();
		});  	
	  }
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
//console.log(goal_type);
	var _stack = stacks[goal_type];
	//console.log($(_stack['click_to_add_reading']).html());

	$(_stack['click_to_add_reading']).click(function(){

		//console.log('asdf');
		$(_stack['add_reading_form']).attr("goal_id",$(this).attr("goal_id"));
		$(_stack['add_reading_model']).modal();
	});
}

function click_to_add_reading(goal_type){
	console.log(goal_type);
	var _stack = stacks[goal_type];
	
	//$(_stack['add_reading_form']).attr("goal_id",$(this).attr("goal_id"));
	$(_stack['add_reading_model']).modal();
}


$(document).ready(function(){
	
	attach_blood_pressure_events();
	attach_weight_events();
	attach_cholesterol_events();
	//attach_glucose_events();
	$(".cls_settings").popover({
		html: true,
		content: function(){
			return $("#goal_menu_dropdown").html();
		},
		placement: 'bottom',
	}).parent().delegate('.goal_reading_open', 'click', function() {
		var chart_type  = $(this).parents('.chart-container').attr("chart-type");
		click_to_add_reading(chart_type);
    	
	});
	populate_weight_graph();
	populate_blood_pressure_graph();
	populate_cholesterol_graph();
	//populate_glucose_graph();

	/*Custom Spinner*/
	$(function() {
		/*Date Picker Script*/
		$(function() {
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
			$(".fld_box").datepicker({
				showOtherMonths: true,
				selectOtherMonths: true,
				"dateFormat": "YYYY-MM-DD",
				/* onRender: function(date) {
					return date.valueOf() < now.valueOf() ? 'disabled' : '';
				}*/
			});
			$(".fld_box").datepicker("setValue", new Date());
		});
		/*Spinner in the weight popup*/
		$("#adj_weight a.inc").click(function(event){
			event.preventDefault(); 
			var wight_v = $("#weight_goal_reading_weight").val();
			wight_v++; 
			$("#weight_goal_reading_weight").val(wight_v); 
			$("p.wval").html(wight_v+"kg");
		});
		$("#adj_weight a.dec").click(function(event){
			event.preventDefault(); 
			var wight_v = $("#weight_goal_reading_weight").val();
			wight_v--; 
			$("#weight_goal_reading_weight").val(wight_v); 
			$("p.wval").html(wight_v+"kg");		
		});
		$("#cwight_arr a.u_arw").click(function(event){
			event.preventDefault();
			var wight_v = $("#cwight").val();
			wight_v++;
			$("#cwight").val(wight_v);
			$("div#CW.w_val").html(wight_v+" kg");
		});
	});
});


function populate_graph(goal_type,options){
	var options = options || {};
	var _stack = stacks[goal_type];
	//$(_stack['chart_container']).hide();
	$(_stack['new_goal_form']).hide();
	_stack['model'].list(function(json,success){
		if(success){
			if(json.count){
				
				var goal = json.results[0];
				//console.log($(_stack['chart_container']).parents('.chart-container').html());
				$(_stack['chart_container']).show();
				$(_stack['chart_container']).parents('.chart-container').attr("goal_id",goal.id);
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
							if(goal.readings[j][arf])
						  		ag['data'].push([ apiDateToGraphDate(goal.readings[j].reading_date), goal.readings[j][arf] ]);
						}
					}
					else {
						ag['data'].push([ apiDateToGraphDate(goal.target_date), goal[arf], ]);
					}

					_series.push(ag);
				}
				//console.log(_series);
				//plot bands
				var _bands = [];
				for(i=0;i< _stack['plots'].length; i++){
					var arf = _stack['plots'][i]['field'];

					var ap = {};
					ap['from'] = goal.healthy_range[arf].max;
					ap['to'] = goal.healthy_range[arf].min;
					if(_stack['plots'][i]['color'])
						ap['color'] = 	_stack['plots'][i]['color'];
					else
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
		            title: {text: ''},
		            //title: {text: _stack['graph_title']},
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
		            tooltip: {valueSuffix: 'Kg'},
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
		                            	delete_reading(goal_type,(graphDateToApiDate(this.x)), this);
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