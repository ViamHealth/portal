<div style="display:none;">
	<div id="goal_menu_dropdown_old">
		<a href="#" id="weight_goal_reading_open" goal_id="">Add Reading</a>
		<br/>
		<a href="#" id="weight_goal_delete" goal_id="" >Delete Goal !</a>
		<br/>
		<a href="#" >Manage Goals</a>
	</div>
	

	<form id="weight-goal-add" class="form-inline" style="display:none;">
		Create a weight goal
		<br/>
  		<input id="weight_goal_weight" type="text" name="weight_goal_weight" class="input input-small" required placeholder="Target weight"/>
  		<input id="weight_goal_target_date" type="date" name="weight_goal_target_date" class="input input-medium" required/>
  		<button class="btn btn-primary" id="save-weight-goal">Save</button>
	</form>

	<a href="#" id="blood_pressure_goal_reading_open" goal_id="" style="display:none;" class="pull-right">Add Reading</a>
	<a href="#" id="blood_pressure_goal_delete" goal_id="" style="display:none;" class="pull-right">Delete Goal !</a>
	<form id="blood-pressure-goal-add" class="form-inline" style="display:none;">
		Create a blood-pressure goal
		<br/>
  		<input id="blood_pressure_goal_systolic_pressure" type="text" name="blood_pressure_goal_systolic_pressure" class="input input-small" required placeholder="Target systolic pressure"/>
  		<input id="blood_pressure_goal_diastolic_pressure" type="text" name="blood_pressure_goal_diastolic_pressure" class="input input-small" required placeholder="diastolic pressure"/>
  		<br/>
  		<input id="blood_pressure_goal_pulse_rate" type="text" name="blood_pressure_goal_pulse_rate" class="input input-small" required placeholder="pulse rate"/>
  		<input id="blood_pressure_goal_target_date" type="date" name="blood_pressure_goal_target_date" class="input input-medium" required/>
  		
  		<button class="btn btn-primary" id="save-blood-pressure-goal">Save</button>
	</form>
</div>


<div class="row-fuild">
	<!-- Weight -->
	<div  class="span6 chart-container" chart-type="weight" goal_id="">
		<div class="chart-title">Weight
		<div class="cls_settings pull-right"> &nbsp; &nbsp; &nbsp; </div>
		</div>
		<div class="chart-content">
			<div id="weight-chart"  style="height: 200px; margin: 0 auto">
			</div>
		</div>
	</div>
	<!-- blood pressure -->
	<div  class="span6 chart-container" chart-type="blood-pressure" >
		<div class="chart-title">Blood Pressure
		<div class="cls_settings pull-right"> &nbsp; &nbsp; &nbsp; </div>
		</div>
		<div class="chart-content">
			<div id="blood-pressure-chart" style="height: 400px; margin: 0 auto">
			</div>
		</div>
	</div>
</div>	