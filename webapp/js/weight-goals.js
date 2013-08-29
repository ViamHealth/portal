$(document).ready(function(){

$("#weight_goal_delete").click(function(event){
		var id = $("#weight_goal_delete").attr("goal_id");
		event.preventDefault();
		_DB.WeightGoal.destroy(id,function(){
			populate_weight_graph();
		});
	});

$("#save-weight-goal").click(function(event){
	event.preventDefault();
	var $form = $("#weight-goal-add");
	$form.validate();
	if($form.valid()){
		var goal = {};
		goal.weight = $("#weight_goal_weight").val();
		goal.weight_measure = 'METRIC';
		goal.target_date = $("#weight_goal_target_date").val();
		_DB.WeightGoal.create(goal,function(){
			populate_weight_graph();
		});
	}
});
$("#weight_goal_reading_open").click(function(){
	$("#weight-goal-reading-add").attr("goal_id",$(this).attr("goal_id"));
	$('#weight-goal-reading-model').modal();
});

$("#save-weight-reading").click(function(){
	event.preventDefault();
	var $form = $("#weight-goal-reading-add");
	$form.validate();
	if($form.valid()){
		var id = $("#weight-goal-reading-add").attr("goal_id");
		var goal = {};
		goal.weight = $("#weight_goal_reading_weight").val();
		goal.weight_measure = 'METRIC';
		goal.reading_date = $("#weight_goal_reading_reading_date").val();
		_DB.WeightGoal.set_reading(id, goal,function(){
			$('#weight-goal-reading-model').modal('hide');
			$("#weight_goal_reading_weight").val('');
			populate_weight_graph();
		});
	}
});

});

function populate_weight_graph(){
	var _stack = stacks['weight'];

	$(_stack['chart_container']).hide();
	$(_stack['new_goal_form']).hide();

	_DB.WeightGoal.list(function(json,success){
		if(success){
			if(json.count){
				var goal = json.results[0];

				$(_stack['chart_container']).show();
				$(_stack['click_to_add_reading']).show();
				$(_stack['click_to_add_reading']).attr("goal_id",goal.id);
				$(_stack['delete_goal_button']).attr("goal_id",goal.id);
				$(_stack['delete_goal_button']).show();

				var weight_readings_end = [[ apiDateToGraphDate(goal.target_date), goal.weight ],];
				var weight_range_max = goal.healthy_range.weight.max;
				var weight_range_min = goal.healthy_range.weight.min;
				var weight_readings = [];
				if( goal.readings.length)
					var time_start = apiDateToGraphDate(goal.readings[0].reading_date);
				else
					var time_start = apiDateToGraphDate();

				for(i=0;i<goal.readings.length;i++)
				  	weight_readings.push([ apiDateToGraphDate(goal.readings[i].reading_date), goal.readings[i].weight ]);

				$('#weight-chart').highcharts({
					exporting: {enabled: false},
		            chart: {type: 'spline'},
		            title: {text: 'Weight Goal'},
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
		                    text: 'Weight (kg) '
		                },
		                min: 0,
		                minorGridLineWidth: 0,
		                gridLineWidth: 0,
		                alternateGridColor: null,
		                plotBands: [{
		                    from: weight_range_min,
		                    to: weight_range_max,
		                    color: 'rgba(68, 170, 213, 0.1)',
		                    label: {
		                        text: 'Healthy Weight Range',
		                        style: {
		                            color: '#606060'
		                        }
		                    }
		                }, ]
		            },
		            tooltip: {valueSuffix: ' Kg'},
		            plotOptions: {
		                spline: {
		                    lineWidth: 1,
		                    states: {
		                        hover: {lineWidth: 5}
		                    },
		                    marker: {enabled: true},
		                    point: {
		                        events: {
		                            'click': function() {
		                            	delete_reading('WEIGHT',goal.id, (graphDateToApiDate(this.x)), this);
		                            	//this.remove();
		                                //if (this.series.data.length > 1) this.remove();
		                            }
		                        }
		                    },
		                    //pointInterval: 3600000, // one hour
		                    pointStart: time_start
		                }
		            },
		            series: [
		            {name: 'Weight',data: weight_readings},
		            {name: 'Target',data: weight_readings_end},
		            ]
		            ,
		            navigation: {menuItemStyle: {fontSize: '10px'}}
		        });


			} else {
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
	});
}