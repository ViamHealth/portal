<?php
    
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/highcharts/highcharts.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/highcharts/modules/exporting.js');
?>

<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Goals',
);
?>


<div class="row-fuild">
	<div  class="span6" id="weight-chart" style="height: 400px; margin: 0 auto"></div>
	<div  class="span6" id="blood-pressure-chart" style="height: 400px; margin: 0 auto"></div>
</div>

<script type="text/javascript">
function apiDateToGraphDate(){

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
	_DB.WeightGoal.destroy_reading(goal_id, reading_id, function(json,success){
		if(success){
			console.log('deleted');
			//point.remove();
		} else {
			console.log('could not delete')
		}
	});
}

$(document).ready(function(){
	_DB.WeightGoal.list(function(json,success){
		if(success){
			if(json.count){
				var goal = json.results[0];

				var _tmp_parts = goal.target_date.split('-');
				
				var weight_readings_end = [[Date.UTC(_tmp_parts[0],  _tmp_parts[1]-1, _tmp_parts[2]-1),goal.weight],];
				var weight_range_max = goal.healthy_range.weight.max;
				var weight_range_min = goal.healthy_range.weight.min;
				var weight_readings = [];

				var _tmp_parts = goal.readings[0].reading_date.split('-');
				var time_start = Date.UTC(_tmp_parts[0],  _tmp_parts[1]-1, _tmp_parts[2]);

				for(i=0;i<goal.readings.length;i++)
				{
					var _tmp_parts = goal.readings[i].reading_date.split('-');
					var t = Date.UTC(_tmp_parts[0],  _tmp_parts[1]-1, _tmp_parts[2]);

				  	weight_readings.push([
					  	t,
					  	goal.readings[i].weight
					]);
				}

				$('#weight-chart').highcharts({
					exporting: {
					         enabled: false
					},
		            chart: {
		                type: 'spline'
		            },
		            title: {
		                text: 'Weight Goal'
		            },
		            subtitle: {
		                text: ''
		            },
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
		            tooltip: {
		                valueSuffix: ' Kg'
		            },
		            plotOptions: {
		                spline: {
		                    lineWidth: 1,
		                    states: {
		                        hover: {
		                            lineWidth: 5
		                        }
		                    },
		                    marker: {
		                        enabled: true
		                    },
		                    point: {
		                        events: {
		                            'click': function() {
		                            	delete_reading('WEIGHT',goal.id, (graphDateToApiDate(this.x)));
		                            	this.remove();
		                                //if (this.series.data.length > 1) this.remove();
		                            }
		                        }
		                    },
		                    //pointInterval: 3600000, // one hour
		                    pointStart: time_start
		                }
		            },
		            series: [{
		                name: 'Weight',
		                data: weight_readings
		            },
		            {
		                name: 'Target',
		                data: weight_readings_end
		            },
		            
		            ]
		            ,
		            navigation: {
		                menuItemStyle: {
		                    fontSize: '10px'
		                }
		            }
		        });


			} else {
				_DB.User.retrieve_bmi_profile(<?php echo $profile_id; ?>,function(json,success){
					if(!json.height){
						alert('create bmi profile first');
					} else {
						// create goal			
					}
				});
				
			}
		}
	});
});
</script>