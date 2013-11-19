
function populate_blood_pressure_graph(){
	populate_graph('blood_pressure');
}

function attach_blood_pressure_events(){
	var goal_type = 'blood_pressure';
	var _stack = stacks[goal_type];

	event_delete_goal_button(goal_type,populate_blood_pressure_graph);
	event_click_to_add_reading(goal_type);

	$(_stack['new_goal_form_save_button']).click(function(event){
		var goal = {};
		goal.systolic_pressure = $("#blood_pressure_goal_systolic_pressure").val();
		goal.diastolic_pressure = $("#blood_pressure_goal_diastolic_pressure").val();
		goal.pulse_rate = $("#blood_pressure_goal_pulse_rate").val();
		
		var goal_id = $("#blood_pressure_goal_id").val();	

		var reading = {};
		reading.systolic_pressure = $("#blood_pressure_goal_current_systolic_pressure").val();
		reading.diastolic_pressure = $("#blood_pressure_goal_current_diastolic_pressure").val();
		reading.pulse_rate = $("#blood_pressure_goal_current_pulse_rate").val();

		if(!reading.systolic_pressure || !reading.diastolic_pressure|| !reading.pulse_rate){
			reading = null;
		}

		save_goal(this,goal_type,goal_id,goal,reading, populate_blood_pressure_graph)
		
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


