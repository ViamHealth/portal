<?php
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.validate.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.ui.widget.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/highcharts/highcharts.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/highcharts/modules/exporting.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/weight-goals.js');
?>

<?php
$this->pageTitle=Yii::app()->name;
$this->breadcrumbs=array(
	'Goals',
);
?>


<div class="row-fuild">
	<div  class="span6" >
		<a href="#" id="weight_goal_reading_open" goal_id="" style="display:none;" class="pull-right">Add Reading</a>
		<div id="weight-chart" style="height: 400px; margin: 0 auto">
		</div>
		<form id="weight-goal-add" class="form-inline" style="display:none;">
			Create a weight goal
			<br/>
      		<input id="weight_goal_weight" type="text" name="weight_goal_weight" class="input input-small" required placeholder="Target weight"/>
      		<input id="weight_goal_target_date" type="date" name="weight_goal_target_date" class="input input-medium" required/>
      		<button class="btn btn-primary" id="save-weight-goal">Save</button>
		</form>

	</div>
	<div  class="span6" id="blood-pressure-chart" style="height: 400px; margin: 0 auto"></div>
</div>



<div id="weight-goal-reading-model" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Add reading for weight goal" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    Add a new reading
  </div>
  <div class="modal-body" itemid="">
    <form id="weight-goal-reading-add" class="form-inline" goal_id="">
      		<input id="weight_goal_reading_weight" type="text" name="weight_goal_reading_weight" class="input input-small" required placeholder="weight"/>
      		<input id="weight_goal_reading_reading_date" type="date" name="weight_goal_reading_reading_date" class="input input-medium" required/>
      		<button class="btn btn-primary" id="save-weight-reading">Save</button>
		</form>
  </div>
</div>

<script type="text/javascript">
VH.vars.profile_id = '<?php echo $profile_id; ?>';

function apiDateToGraphDate(apidate){
	if(!apidate){
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!

		var yyyy = today.getFullYear();
		if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = yyyy+'-'+mm+'-'+dd;
		var _tmp_parts = today.split('-');
		return Date.UTC(_tmp_parts[0],  _tmp_parts[1]-1, _tmp_parts[2]);
	} else {
		var _tmp_parts = apidate.split('-');
		return Date.UTC(_tmp_parts[0],  _tmp_parts[1]-1, _tmp_parts[2]);
	}
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


$(document).ready(function(){

	populate_weight_graph();

});




</script>