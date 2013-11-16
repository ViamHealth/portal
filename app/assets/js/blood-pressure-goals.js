
function populate_blood_pressure_graph(){
	populate_graph('blood_pressure');
}

function attach_blood_pressure_events(){
	var _stack = stacks['blood_pressure'];

	event_delete_goal_button('blood_pressure',populate_blood_pressure_graph);
	event_click_to_add_reading('blood_pressure');

	$(_stack['new_goal_form_save_button']).click(function(event){
		event.preventDefault();
		var $form = $(_stack['new_goal_form']);
		$form.validate();
		if($form.valid()){
			var goal = {};
			goal.systolic_pressure = $("#blood_pressure_goal_systolic_pressure").val();
			goal.diastolic_pressure = $("#blood_pressure_goal_diastolic_pressure").val();
			goal.pulse_rate = $("#blood_pressure_goal_pulse_rate").val();
			goal.target_date = $("#blood_pressure_goal_target_date").val();

			_stack['model'].create(goal,function(){
				populate_blood_pressure_graph();
			});
		}
	});	
//add_reading_form_save
	$(_stack['add_reading_form_save']).click(function(){
		event.preventDefault();
		var $form = $(_stack['add_reading_form']);
		$form.validate();
		if($form.valid()){
			var id = $(_stack['add_reading_form']).attr("goal_id");
			var goal = {};
			goal.systolic_pressure = $("#blood_pressure_goal_reading_systolic_pressure").val();
			goal.diastolic_pressure = $("#blood_pressure_goal_reading_diastolic_pressure").val();
			goal.pulse_rate = $("#blood_pressure_goal_reading_pulse_rate").val();
			goal.reading_date = $("#blood_pressure_goal_reading_reading_date").val();

			_DB.BloodPressureReading.retrieve(goal.reading_date,function(response,status){
				if (response.status && response.status == 404) {
					_DB.BloodPressureReading.create(goal,function(response,status){
						if(!status) console.log(response.responseText);
						$(_stack['add_reading_model']).modal('hide');
						populate_blood_pressure_graph();
					});
				} else {
					_DB.BloodPressureReading.update(goal.reading_date,goal,function(response,status){
						if(!status) console.log(response.responseText);
						$(_stack['add_reading_model']).modal('hide');
						populate_blood_pressure_graph();
					});
				}
			});
		}
	});

}


