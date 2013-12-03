function populate_weight_graph(){
	var _stack = stacks['weight'];
	options = {
		'no_goal_action' : function(){
			_DB.User.retrieve_bmi_profile(VH.vars.profile_id,function(json,success){
					if(!json.height){
						bootbox.confirm("You need to complete your profile first. Go to profile page ?", function(result) {
							if(result){
								var uu = find_family_user_id();
								if(uu)
									window.location = "/u/"+uu+"/user";
								else
									window.location = "/user";
							}
						});
					} else {
						// create goal		
						//$(_stack['new_goal_form']).show();
					}
				});
		}
	}
	populate_graph('weight',options);
}


function attach_weight_events(){
	var goal_type = "weight";
	var _stack = stacks[goal_type];

	event_delete_goal_button(goal_type,populate_weight_graph);
	event_click_to_add_reading(goal_type);

	$(_stack['new_goal_form_save_button']).click(function(event){
		event.preventDefault();
		var $form = $("#weight-goal-add-form");
		$form.validate();
		if($form.valid()){
			var goal = {}
			goal.weight = $("#weight_goal_target_weight").val();
			var goal_id = $("#weight_goal_id").val();	
			var reading = {};
			reading.weight = $("#weight_goal_current_weight").val();
			save_goal(this,goal_type,goal_id,goal,reading, populate_weight_graph)
		}
	});

	$(_stack['add_reading_form_save']).click(function(event){
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
						$('#weight-goal-reading-model').modal('hide');
						//$("#weight_goal_reading_weight").val('');
						if(!status) console.log(response.responseText);
						populate_weight_graph();
					});
				} else {
					_DB.WeightReading.update(goal.reading_date,goal,function(response,status){
						$('#weight-goal-reading-model').modal('hide');
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


