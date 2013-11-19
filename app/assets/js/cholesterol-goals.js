function populate_cholesterol_graph(){
	populate_graph('cholesterol');
}

function attach_cholesterol_events(){
	var goal_type = 'cholesterol';
	var _stack = stacks['cholesterol'];

	event_delete_goal_button('cholesterol',populate_cholesterol_graph);
	event_click_to_add_reading('cholesterol');

	$(_stack['new_goal_form_save_button']).click(function(event){
		event.preventDefault();
		var $form = $(_stack['new_goal_form']);

		//$form.validate();
		//if($form.valid()){
		if(1){
			var goal = {};
			goal.hdl = $("#cholesterol_goal_hdl").val();
			goal.ldl = $("#cholesterol_goal_ldl").val();
			goal.triglycerides = $("#cholesterol_goal_triglycerides").val();
			
			var goal_id = $("#cholesterol_goal_id").val();	
			
			var reading = {};
			reading.hdl = $("#cholesterol_goal_current_hdl").val();
			reading.ldl = $("#cholesterol_goal_current_ldl").val();
			reading.triglycerides = $("#cholesterol_goal_current_triglycerides").val();
			
			if(!reading.hdl || !reading.ldl || !reading.triglycerides){
				reading = null;
			}
			save_goal(this,goal_type,goal_id,goal,reading, populate_cholesterol_graph)
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
			goal.hdl = $("#cholesterol_goal_reading_hdl").val();
			goal.ldl = $("#cholesterol_goal_reading_ldl").val();
			goal.triglycerides = $("#cholesterol_goal_reading_triglycerides").val();
			goal.total_cholesterol = $("#cholesterol_goal_reading_total_cholesterol").val();
			goal.reading_date = $("#cholesterol_goal_reading_reading_date").val();

			_DB.CholesterolReading.retrieve(goal.reading_date,function(response,status){
				if (response.status && response.status == 404) {
					_DB.CholesterolReading.create(goal,function(response,status){
						$('#cholesterol-goal-reading-model').modal('hide');
						
						if(!status) console.log(response.responseText);
						populate_cholesterol_graph();
					});
				} else {
					_DB.CholesterolReading.update(goal.reading_date,goal,function(response,status){
						$('#cholesterol-goal-reading-model').modal('hide');
						if(!status) console.log(response.responseText);
						populate_cholesterol_graph();
					});
				}
			});


			/*_stack['model'].set_reading(id, goal,function(){
				$(_stack['add_reading_model']).modal('hide');
				populate_cholesterol_graph();
			});*/
		}
	});

}