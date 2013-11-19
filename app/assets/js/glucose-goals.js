function populate_glucose_graph(){
	populate_graph('glucose');
}

function attach_glucose_events(){
	var goal_type = 'glucose';
	var _stack = stacks['glucose'];

	event_delete_goal_button('glucose',populate_glucose_graph);
	event_click_to_add_reading('glucose');

	$(_stack['new_goal_form_save_button']).click(function(event){
		event.preventDefault();
		var $form = $(_stack['new_goal_form']);

		//$form.validate();
		//if($form.valid()){
		if(1){
			var goal = {};
			goal.random = $("#glucose_goal_random").val();
			goal.fasting = $("#glucose_goal_fasting").val();
			
			var goal_id = $("#glucose_goal_id").val();	
			
			var reading = {};
			reading.random = $("#glucose_goal_current_random").val();
			reading.fasting = $("#glucose_goal_current_fasting").val();
			
			if(!reading.random || !reading.fasting){
				reading = null;
			}
			save_goal(this,goal_type,goal_id,goal,reading, populate_glucose_graph)
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
			goal.random = $("#glucose_goal_reading_random").val();
			goal.fasting = $("#glucose_goal_reading_fasting").val();
			goal.reading_date = $("#glucose_goal_reading_reading_date").val();

			_DB.GlucoseReading.retrieve(goal.reading_date,function(response,status){
				if (response.status && response.status == 404) {
					_DB.GlucoseReading.create(goal,function(response,status){
						$('#glucose-goal-reading-model').modal('hide');
						
						if(!status) console.log(response.responseText);
						populate_glucose_graph();
					});
				} else {
					_DB.GlucoseReading.update(goal.reading_date,goal,function(response,status){
						$('#glucose-goal-reading-model').modal('hide');
						if(!status) console.log(response.responseText);
						populate_glucose_graph();
					});
				}
			});
		}
	});

}