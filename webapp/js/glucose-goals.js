function populate_glucose_graph(){
	populate_graph('glucose');
}

function attach_glucose_events(){
	var _stack = stacks['glucose'];

	event_delete_goal_button('glucose',populate_glucose_graph);
	event_click_to_add_reading('glucose');

	$(_stack['new_goal_form_save_button']).click(function(event){
		event.preventDefault();
		var $form = $(_stack['new_goal_form']);
		$form.validate();
		if($form.valid()){
			var goal = {};
			goal.random = $("#glucose_goal_random").val();
			goal.fasting = $("#glucose_goal_fasting").val();
			goal.target_date = $("#glucose_goal_target_date").val();

			_stack['model'].create(goal,function(){
				populate_glucose_graph();
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
			goal.random = $("#glucose_goal_reading_random").val();
			goal.fasting = $("#glucose_goal_reading_fasting").val();
			goal.reading_date = $("#glucose_goal_reading_reading_date").val();

			_stack['model'].set_reading(id, goal,function(){
				$(_stack['add_reading_model']).modal('hide');
				populate_glucose_graph();
			});
		}
	});

}