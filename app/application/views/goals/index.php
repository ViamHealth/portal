<script src="<?php echo base_url('assets/js/highcharts/highcharts.js') ?>"></script>
<script src="<?php echo base_url('assets/js/highcharts/modules/exporting.js') ?>"></script>

<script src="<?php echo base_url('assets/js/weight-goals.js') ?>"></script>
<script src="<?php echo base_url('assets/js/cholesterol-goals.js') ?>"></script>
<script src="<?php echo base_url('assets/js/glucose-goals.js') ?>"></script>
<script src="<?php echo base_url('assets/js/blood-pressure-goals.js') ?>"></script>

<div class="row" >
	<!-- Weight -->
	<div  class="col-md-6 chart-container" chart-type="weight" goal_id="">
		<div class="chart-title">Weight
			<?php $this->load->view('goals/_cls_settings'); ?>
		</div>
		<div class="chart-content">
			<div id="weight-chart"  style="height: 400px; margin: 0 auto">
			</div>
		</div>
	</div>
	<!-- blood pressure -->
	<div  class="col-md-6 chart-container" chart-type="blood_pressure" goal_id="">
		<div class="chart-title">Blood Pressure
			<?php $this->load->view('goals/_cls_settings'); ?>
		</div>
		<div class="chart-content">
			<div id="blood-pressure-chart" style="height: 400px; margin: 0 auto">
			</div>
		</div>
	</div>
</div>
<div class="row" >
	<!-- cholesterol -->
	<div  class="col-md-6 chart-container" chart-type="cholesterol" goal_id="">
		<div class="chart-title">Cholesterol
			<?php $this->load->view('goals/_cls_settings'); ?>
		</div>
		<div class="chart-content">
			<div id="cholesterol-chart" style="height: 400px; margin: 0 auto">
			</div>
		</div>
	</div>
	<!-- glucose -->
	<div  class="col-md-6 chart-container" chart-type="glucose" goal_id="">
		<div class="chart-title">Glucose
			<?php $this->load->view('goals/_cls_settings'); ?>
		</div>
		<div class="chart-content">
			<div id="glucose-chart" style="height: 400px; margin: 0 auto">
			</div>
		</div>
	</div>
</div>	


<?php $this->load->view('goals/_modal_add_weight_reading'); ?>
<?php $this->load->view('goals/_modal_add_glucose_reading'); ?>
<?php $this->load->view('goals/_modal_add_cholesterol_reading'); ?>
<?php $this->load->view('goals/_modal_add_blood_pressure_reading'); ?>

<?php $this->load->view('goals/_modal_manage_goals'); ?>


<script type="text/javascript">

//console.log(VH.vars.profile_id);
var stacks = {};

stacks['weight'] ={};
stacks['weight']['model'] = _DB.WeightGoal;
stacks['weight']['model_reading'] = _DB.WeightReading;
stacks['weight']['graph_title'] = 'Weight Goal';
stacks['weight']['add_reading_model'] = $("#weight-goal-reading-model");
stacks['weight']['add_reading_form'] = $("#weight-goal-reading-add");
stacks['weight']['add_reading_form_save'] = $("#weight-goal-reading-model .btn-primary");
//notuser below line
stacks['weight']['click_to_add_reading'] = $(".weight_goal_reading_open");
stacks['weight']['chart_container'] = $("#weight-chart");
stacks['weight']['new_goal_form'] = $("#weight-goal-add");
stacks['weight']['new_goal_form_save_button'] = $("#save-weight-goal");
stacks['weight']['goal_time_range'] = "#weight_time_range";
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
stacks['blood_pressure']['model_reading'] = _DB.BloodPressureReading;
stacks['blood_pressure']['graph_title'] = 'Blood pressure Goal';
stacks['blood_pressure']['add_reading_model'] = $("#blood-pressure-goal-reading-model");
stacks['blood_pressure']['add_reading_form'] = $("#blood-pressure-goal-reading-add");
stacks['blood_pressure']['add_reading_form_save'] = $("#blood-pressure-goal-reading-model .btn-primary");
stacks['blood_pressure']['click_to_add_reading'] = $(".blood_pressure_goal_reading_open");
stacks['blood_pressure']['chart_container'] = $("#blood-pressure-chart");
stacks['blood_pressure']['new_goal_form'] = $("#blood-pressure-goal-add");
stacks['blood_pressure']['new_goal_form_save_button'] = $("#save-blood-pressure-goal");
stacks['blood_pressure']['goal_time_range'] = "#blood_pressure_time_range";
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
stacks['cholesterol']['model_reading'] = _DB.CholesterolReading;
stacks['cholesterol']['graph_title'] = 'Cholesterol Goal';
stacks['cholesterol']['add_reading_model'] = $("#cholesterol-goal-reading-model");
stacks['cholesterol']['add_reading_form'] = $("#cholesterol-goal-reading-add");
stacks['cholesterol']['add_reading_form_save'] = $("#cholesterol-goal-reading-model .btn-primary");
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
stacks['glucose']['model_reading'] = _DB.GlucoseReading;
stacks['glucose']['graph_title'] = 'glucose Goal';
stacks['glucose']['add_reading_model'] = $("#glucose-goal-reading-model");
stacks['glucose']['add_reading_form'] = $("#glucose-goal-reading-add");
stacks['glucose']['add_reading_form_save'] = $("#glucose-goal-reading-model .btn-primary");
stacks['glucose']['click_to_add_reading'] = $(".glucose_goal_reading_open");
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
		var that = this;
		event.preventDefault();
		bootbox.confirm("Delete Goal?", function(result) {
		  if(result){
			var id = $(_stack['delete_goal_button']).attr("goal_id");
			
			_stack['model'].destroy(id,function(){
				populate_graph_function();
				$(this).hide();
			});
		  }
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
	$(".cls_settings").mousemove(function(){

   		$(this).css({"position":"relative"});
    	$(this).find(".sb_list1").css("display","block");
    });
    $(".cls_settings").mouseleave(function(){
    	$(this).css({"position":""});
    	$(this).find(".sb_list1").css("display","none");
    });
	
	attach_blood_pressure_events();
	attach_weight_events();
	attach_cholesterol_events();
	attach_glucose_events();
	
	$(".cls_settings").delegate('.goal_reading_open', 'click', function() {
		var chart_type  = $(this).parents('.chart-container').attr("chart-type");
		click_to_add_reading(chart_type);
    	
	});

	$(".cls_settings").delegate('.manage_goals', 'click', function() {
		//var chart_type  = $(this).parents('.chart-container').attr("chart-type");
		//click_to_add_reading(chart_type);
		
    	$("#manage-goals-modal").modal();
	});

	
	populate_weight_graph();
	populate_blood_pressure_graph();
	populate_cholesterol_graph();
	populate_glucose_graph();

	/*Custom Spinner*/
	$(function() {
		/*Date Picker Script*/
		$(function() {
			var nowTemp = new Date();
			var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
			var date_options = {
			    format: "yyyy-mm-dd",
			    endDate: now,
			    todayBtn: "linked",
			    keyboardNavigation: false,
			    forceParse: false,
			    autoclose: true,
			    todayHighlight: true
			};

			$('#weight-goal-reading-model .sandbox-container input').datepicker(date_options).on('changeDate', function(ev){
						var date = new Date(ev.date);
						var yyyy = date.getFullYear();
						var mm = date.getMonth()+1;
						var dd = date.getDate();
						var formattedTime = yyyy + '-' + mm + '-' + dd;
					});;
			$('#weight-goal-reading-model .sandbox-container input').datepicker("setValue", new Date());

			$('#glucose-goal-reading-model .sandbox-container input').datepicker(date_options).on('changeDate', function(ev){
						var date = new Date(ev.date);
						var yyyy = date.getFullYear();
						var mm = date.getMonth()+1;
						var dd = date.getDate();
						var formattedTime = yyyy + '-' + mm + '-' + dd;
					});;
			$('#glucose-goal-reading-model .sandbox-container input').datepicker("setValue", new Date());

			$('#cholesterol-goal-reading-model .sandbox-container input').datepicker(date_options).on('changeDate', function(ev){
						var date = new Date(ev.date);
						var yyyy = date.getFullYear();
						var mm = date.getMonth()+1;
						var dd = date.getDate();
						var formattedTime = yyyy + '-' + mm + '-' + dd;
					});;
			$('#cholesterol-goal-reading-model .sandbox-container input').datepicker("setValue", new Date());

			$('#blood-pressure-goal-reading-model .sandbox-container input').datepicker(date_options).on('changeDate', function(ev){
						var date = new Date(ev.date);
						var yyyy = date.getFullYear();
						var mm = date.getMonth()+1;
						var dd = date.getDate();
						var formattedTime = yyyy + '-' + mm + '-' + dd;
					});;
			$('#blood-pressure-goal-reading-model .sandbox-container input').datepicker("setValue", new Date());

			/***
			$(".fld_box").datepicker({
				showOtherMonths: true,
				selectOtherMonths: true,
				"dateFormat": "YYYY-MM-DD",
			});
			$(".fld_box").datepicker("setValue", new Date());
			***/
		});
		/*Spinner in the weight popup*/
		$("#adj_weight a.inc").click(function(event){
			event.preventDefault(); 
			var wight_v = $("#weight_goal_reading_weight").val();
			wight_v++; 
			$("#weight_goal_reading_weight").val(wight_v); 
			$("p.wval").html(wight_v+"Kg");
		});
		$("#adj_weight a.dec").click(function(event){
			event.preventDefault(); 
			var wight_v = $("#weight_goal_reading_weight").val();
			wight_v--; 
			$("#weight_goal_reading_weight").val(wight_v); 
			$("p.wval").html(wight_v+"Kg");		
		});

		$("#adj_current_weight a.inc").click(function(event){
			event.preventDefault(); 
			var wight_v = $("#weight_goal_current_weight").val();
			wight_v++; 
			$("#weight_goal_current_weight").val(wight_v); 
			$("#weight_goal_current_weight_div p.wval").html(wight_v+"Kg");
		});
		$("#adj_current_weight a.dec").click(function(event){
			event.preventDefault(); 
			var wight_v = $("#weight_goal_current_weight").val();
			wight_v--; 
			$("#weight_goal_current_weight").val(wight_v); 
			$("#weight_goal_current_weight_div p.wval").html(wight_v+"Kg");		
		});

		$("#adj_target_weight a.inc").click(function(event){
			event.preventDefault(); 
			var wight_v = $("#weight_goal_target_weight").val();
			wight_v++; 
			$("#weight_goal_target_weight").val(wight_v); 
			$("#weight_goal_target_weight_div p.wval").html(wight_v+"Kg");
		});
		$("#adj_target_weight a.dec").click(function(event){
			event.preventDefault(); 
			var wight_v = $("#weight_goal_target_weight").val();
			wight_v--; 
			$("#weight_goal_target_weight").val(wight_v); 
			$("#weight_goal_target_weight_div p.wval").html(wight_v+"Kg");		
		});


		set_goal_time_range_ui('weight');
		set_goal_time_range_ui('blood_pressure');

		
		
	});
});

function set_goal_time_range_ui(goal_type){
	var ddData = [
			{
				text: '3 Months',
				value: '3'
			},
			{
				text: '6 Months',
				value: '6'
			},
			{
				text: '9 Months',
				value: '9'
			},
			{
				text: '1 Year',
				value: '12'
			}
		];
	var _stack = stacks[goal_type];
	console.log('sadsdfsfsdfds');
	$(_stack['goal_time_range']).ddslick({
		    data:ddData,
		    width:100,
		    selectText: "Months",
		    defaultSelectedIndex:0,
		    background: '#fff',
		    //imagePosition:"left",
		    onSelected: function(selectedData){
		        //callback function: do something with selectedData;
		    }   
		});	
}
function from_ui_to_api_goal_interval(goal_type,goal){
	var _stack = stacks[goal_type];
	var dr = $(_stack['goal_time_range']).data('ddslick');
	dr = dr.selectedIndex;
	if(dr >= '0' && dr <= '2'){
		goal.interval_num = (parseInt(dr)+1)*3;
		goal.interval_unit = 'MONTH';
	} else if (dr == '3'){
		goal.interval_num = '1';
		goal.interval_unit = 'YEAR';
	} else {
		console.log(dr);
		throw ('interval values not proper') ;
	}
	return goal;
}

function populate_manage_goals(goal_type,goal){
	var _stack = stacks[goal_type];

	if(goal_type == 'weight'){
		$("#weight_goal_target_weight").val(goal.weight);
		$("#weight_goal_target_weight_div p.wval").html(goal.weight+"Kg");
		$("#weight_goal_id").val(goal.id);	
	}

	
	var i_int = 0;

	if(goal.interval_unit=='MONTH' ){
		if(goal.interval_num =='3'){
			i_int = 0;
		} else if (goal.interval_num == '6'){
			i_int = 1;
		} else if (goal.interval_num == '9'){
			i_int = 2;
		}
	} else if (goal.interval_unit == 'YEAR'){
		 if (goal.interval_num == '1'){
			i_int = 3;
		}
	}
	$(_stack['goal_time_range']).ddslick('select', {index: i_int });

	//$(_stack['delete_goal_button']).attr("goal_id",goal.id).show();
}

function populate_graph(goal_type,options){
	var options = options || {};
	var _stack = stacks[goal_type];
	//$(_stack['chart_container']).hide();
	//$(_stack['new_goal_form']).hide();
	_stack['model'].list(function(json,success){
		if(success){
			if(json.count){
				var goal = json.results[0];

				//Populate Manage goals
				populate_manage_goals(goal_type,goal);
				
				
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
		            tooltip: {valueSuffix: ''},
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