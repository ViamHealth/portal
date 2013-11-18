<div class="row">
	<div class="pull-right"><a id="blood_pressure_goal_delete" class="delete-goal" href="#">Delete</a></div>
</div>
<div class="row">
	<input type="hidden" id="blood_pressure_goal_id" value="" />

    <div class="blood-pressure-inputs col-md-4" >
      <label>Current BP</label>
      <div class="">
        Diastolic
      <br/>
      <input id="blood_pressure_goal_current_systolic_pressure" type="text" name="blood_pressure_goal_current_systolic_pressure" class="input" required placeholder=""/>
      </br>
      Systolic
      <br/>
      <input id="blood_pressure_goal_current_diastolic_pressure" type="text" name="blood_pressure_goal_current_diastolic_pressure" class="input" required />
      <br/>
      Pulse
      <br/>
      <input id="blood_pressure_goal_current_pulse_rate" type="text" name="blood_pressure_goal_current_pulse_rate" class="input" required />
      </div>
    </div>
    <div class="blood-pressure-inputs col-md-4" >
      <label>Target BP</label>
      <div class="">
        Diastolic
      <br/>
      <input id="blood_pressure_goal_systolic_pressure" type="text" name="blood_pressure_goal_systolic_pressure" class="input" required placeholder=""/>
      </br>
      Systolic
      <br/>
      <input id="blood_pressure_goal_diastolic_pressure" type="text" name="blood_pressure_goal_diastolic_pressure" class="input" required />
      <br/>
      Pulse
      <br/>
      <input id="blood_pressure_goal_pulse_rate" type="text" name="blood_pressure_goal_pulse_rate" class="input" required />
      </div>
    </div>
    <div class="blood-pressure-inputs col-md-4" >
      <label>Goal Time</label>
      <div class="">
        <div class="goal_time_watch pull-left">&nbsp;</div>
        <div  class="goal_range_dd pull-right">
          <div id="blood_pressure_time_range"></div>
        </div>
      </div>
    </div>
</div>
<div class="row">
	<div class="pull-right">
		<button type="button" id="save-blood-pressure-goal" class="btn btn-primary">Save</button>
	</div>
</div>