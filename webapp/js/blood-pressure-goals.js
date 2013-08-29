

function attach_blood_pressure_events(){
	var _stack = stacks['blood_pressure'];

	$(_stack['delete_goal_button']).click(function(event){
		var id = $(_stack['delete_goal_button']).attr("goal_id");
		event.preventDefault();
		_DB.BloodPressureGoal.destroy(id,function(){
			populate_blood_pressure_graph();
		});
	});

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
			console.log('jehe');
			console.log(goal);
			_DB.BloodPressureGoal.create(goal,function(){
				populate_blood_pressure_graph();
			});
		}
	});	

	$(_stack['click_to_add_reading']).click(function(){
		$(_stack['add_reading_form']).attr("goal_id",$(this).attr("goal_id"));
		$(_stack['add_reading_model']).modal();
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

			_DB.BloodPressureGoal.set_reading(id, goal,function(){
				$(_stack['add_reading_model']).modal('hide');
				//$("#weight_goal_reading_weight").val('');
				populate_blood_pressure_graph();
			});
		}
	});

}








function populate_blood_pressure_graph(){
	var _stack = stacks['blood_pressure'];
	$(_stack['chart_container']).hide();
	$(_stack['new_goal_form']).hide();
	_DB.BloodPressureGoal.list(function(json,success){
		if(success){
			if(json.count){
				
				var goal = json.results[0];
				$(_stack['chart_container']).show();
				$(_stack['click_to_add_reading']).show();
				$(_stack['click_to_add_reading']).attr("goal_id",goal.id);
				$(_stack['delete_goal_button']).attr("goal_id",goal.id);
				$(_stack['delete_goal_button']).show();

				var systolic_pressure_readings_end = [[ apiDateToGraphDate(goal.target_date), goal.systolic_pressure, ],];
				var diastolic_pressure_readings_end = [[ apiDateToGraphDate(goal.target_date),  goal.diastolic_pressure, ],];

				var systolic_pressure_max = goal.healthy_range.systolic_pressure.max;
				var systolic_pressure_min = goal.healthy_range.systolic_pressure.min;

				var diastolic_pressure_max = goal.healthy_range.diastolic_pressure.max;
				var diastolic_pressure_min = goal.healthy_range.diastolic_pressure.min;

				var systolic_pressure_readings = [];
				var diastolic_pressure_readings = [];

				if( goal.readings.length)
					var time_start = apiDateToGraphDate(goal.readings[0].reading_date);
				else
					var time_start = apiDateToGraphDate();

				for(i=0;i<goal.readings.length;i++) {
				  	systolic_pressure_readings.push([ apiDateToGraphDate(goal.readings[i].reading_date), goal.readings[i].systolic_pressure ]);
				  	diastolic_pressure_readings.push([ apiDateToGraphDate(goal.readings[i].reading_date), goal.readings[i].diastolic_pressure ]);
				}

				$(_stack['chart_container']).highcharts({
					exporting: {enabled: false},
		            chart: {type: 'spline'},
		            title: {text: 'Blood Pressure Goal'},
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
		                plotBands: [{
		                    from: systolic_pressure_min,
		                    to: systolic_pressure_max,
		                    color: 'rgba(68, 170, 213, 0.1)',
		                    label: {
		                        text: 'Healthy systolic Pressure Range',
		                        style: {
		                            color: '#606060'
		                        }
		                    }
		                }, {
		                    from: diastolic_pressure_min,
		                    to: diastolic_pressure_max,
		                    color: 'rgba(68, 170, 213, 0.1)',
		                    label: {
		                        text: 'Healthy diastolic Pressure Range',
		                        style: {
		                            color: '#706060'
		                        }
		                    }
		                },
		                ]
		            },
		            tooltip: {valueSuffix: ' mmHg'},
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
		                            	delete_reading('BLOOD_PRESSURE',goal.id, (graphDateToApiDate(this.x)), this);
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
		            {name: 'systolic',data: systolic_pressure_readings},
		            {name: 'diastolic',data: diastolic_pressure_readings},
		            {name: 'Target systolic',data: systolic_pressure_readings_end},
		            {name: 'Target diastolic',data: diastolic_pressure_readings_end},
		            ]
		            ,
		            navigation: {menuItemStyle: {fontSize: '10px'}}
		        });


			} else {
				
				$(_stack['new_goal_form']).show();				
			}
		}
	});
}