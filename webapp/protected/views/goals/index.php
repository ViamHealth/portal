<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.validate.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.ui.widget.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/highcharts/highcharts.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/highcharts/modules/exporting.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/weight-goals.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/blood-pressure-goals.js');
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

<!-- Modals end-->
<script type="text/javascript">
VH.vars.profile_id = '<?php echo $profile_id; ?>';

var stacks = {};

stacks['weight'] ={};
stacks['weight']['add_reading_model'] = $("#weight-goal-reading-model");
stacks['weight']['add_reading_form'] = $("#weight-goal-reading-add");
stacks['weight']['add_reading_form_save'] = $("#save-weight-reading");
stacks['weight']['click_to_add_reading'] = $("#weight_goal_reading_open");
stacks['weight']['chart_container'] = $("#weight-chart");
stacks['weight']['new_goal_form'] = $("#weight-goal-add");
stacks['weight']['new_goal_form_save_button'] = $("#save-weight-goal");
stacks['weight']['delete_goal_button'] = $("#weight_goal_delete");

stacks['blood_pressure'] ={};
stacks['blood_pressure']['add_reading_model'] = $("#blood-pressure-goal-reading-model");
stacks['blood_pressure']['add_reading_form'] = $("#blood-pressure-goal-reading-add");
stacks['blood_pressure']['add_reading_form_save'] = $("#save-blood-pressure-reading");
stacks['blood_pressure']['click_to_add_reading'] = $("#blood_pressure_goal_reading_open");
stacks['blood_pressure']['chart_container'] = $("#blood-pressure-chart");
stacks['blood_pressure']['new_goal_form'] = $("#blood-pressure-goal-add");
stacks['blood_pressure']['new_goal_form_save_button'] = $("#save-blood-pressure-goal");
stacks['blood_pressure']['delete_goal_button'] = $("#blood_pressure_goal_delete");


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
	if(goal_type == 'WEIGHT')
		_DB.WeightGoal.destroy_reading(goal_id, reading_id, function(json,success){
			point.remove();
		});
	else (goal_type == 'BLOOD_PRESSURE')
		_DB.BloodPressureGoal.destroy_reading(goal_id, reading_id, function(json,success){
			point.remove();
		});
}


$(document).ready(function(){
	attach_blood_pressure_events();
	populate_weight_graph();
	populate_blood_pressure_graph();
});




</script>