function populate_weight_graph(){
	var _stack = stacks['weight'];
	options = {
		'no_goal_action' : function(){
			_DB.User.retrieve_bmi_profile(VH.vars.profile_id,function(json,success){
					if(!json.height){
						alert('create bmi profile first');
					} else {
						// create goal		
						$(_stack['new_goal_form']).show();
					}
				});
		}
	}
	populate_graph('weight',options);
}

function attach_weight_events(){
	var _stack = stacks['weight'];

	event_delete_goal_button('weight',populate_weight_graph);
	event_click_to_add_reading('weight');

	$("#save-weight-goal").click(function(event){
		event.preventDefault();
		var $form = $("#weight-goal-add");
		$form.validate();
		if($form.valid()){
			var goal = {};
			goal.weight = $("#weight_goal_weight").val();
			//goal.weight_measure = 'METRIC';
			goal.target_date = $("#weight_goal_target_date").val();
			_DB.WeightGoal.create(goal,function(){
				populate_weight_graph();
			});
		}
	});

	$("#save-weight-reading").click(function(){
		event.preventDefault();
		var $form = $("#weight-goal-reading-add");
		$form.validate();
		if($form.valid()){
			var id = $("#weight-chart").parents('.chart-container').attr("goal_id");
			var goal = {};
			goal.weight = $("#weight_goal_reading_weight").val();
			//goal.weight_measure = 'METRIC';
			goal.reading_date = $("#weight_goal_reading_reading_date").val();

			_DB.WeightReading.retrieve(goal.reading_date,function(response,status){
				if (response.status && response.status == 404) {
					_DB.WeightReading.create(goal,function(response,status){
						//$('#weight-goal-reading-model').modal('hide');
						//$("#weight_goal_reading_weight").val('');
						if(!status) console.log(response.responseText);
						populate_weight_graph();
					});
				} else {
					_DB.WeightReading.update(goal.reading_date,goal,function(response,status){
						//$('#weight-goal-reading-model').modal('hide');
						//$("#weight_goal_reading_weight").val('');
						if(!status) console.log(response.responseText);
						populate_weight_graph();
					});
				}
			});
			/*_DB.WeightReading.create(goal,function(response,status){
				$('#weight-goal-reading-model').modal('hide');
				//$("#weight_goal_reading_weight").val('');
				if(!status) console.log(response.responseText);
				populate_weight_graph();
			});*/
		}
	});
}


