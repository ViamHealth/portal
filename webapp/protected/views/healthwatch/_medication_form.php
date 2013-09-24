<div id="medicaltest-form-modal" class="modal hide fade" tabindex="-1" role="dialog" 
	aria-labelledby="Medical test reminder" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    Medicaltest reminder form
  </div>
  <div class="modal-body" itemid="">
    <form id="medicaltest-add" class="form-horizontal" >
    	<div class="control-group">
    	<label class="control-label" for="medicaltest[name]">Name</label>
    	<div class="controls">
      		<input id="medicaltest[name]" type="text" name="medicaltest[name]" class="input" required placeholder="Test Name"/>
      	</div>
		</div>
		<div class="control-group">
		<label class="control-label" for="medicaltest[details]">Details</label>
		<div class="controls">
      		<textarea id="medicaltest[details]" name="medicaltest[details]"  required rows="3"></textarea>
      	</div>
		</div>
		<div class="control-group">
		<label class="control-label" for="medicaltest[start_date]">Start Date time</label>
		<div class="controls">
      		<input id="medicaltest[start_date]" type="date" name="medicaltest[start_date]" class="input input-medium" required/>
      		<input id="medicaltest[start_time]" type="time" name="medicaltest[start_time]" class="input input-medium" />
      	</div>
		</div>
		<div class="control-group">
		<label class="control-label" for="medicaltest[repeat_mode]">Repeat</label>
		<div class="controls">
    		<select name="medicaltest[repeat_mode]" id="medicaltest[repeat_mode]" >
    			<option value="NONE">No Repeat</option>
    			<option value="DAILY">Daily</option>
    			<option value="WEEKLY">Weekly</option>
    			<option value="MONTHLY">Monthly</option>
    			<option value="N_WEEKDAY_MONTHLY">After Every X Weeks</option>
    			<option value="N_DAYS_INTERVAL">After Every X Days</option>
  			</select>
  		</div>
		</div>
		<div class="control-group hide">
		<label class="control-label" for="medicaltest[repeat_day]">Repeat Day</label>
		<div class="controls">
  			<input id="medicaltest[repeat_day]" type="number" name="medicaltest[repeat_day]" class="input" placeholder="1 - 7" disabled />
		</div>
		</div>
		<div class="control-group hide">
		<label class="control-label" for="medicaltest[repeat_hour]">Repeat Hour</label>
		<div class="controls">
  			<input id="medicaltest[repeat_hour]" type="number" name="medicaltest[repeat_hour]" class="input input-medium" placeholder="0 - 23" disabled/>
  			</div>
		</div>
		<div class="control-group hide">
		<label class="control-label" for="medicaltest[repeat_minute]">Repeat Minute</label>
		<div class="controls">
  			<input id="medicaltest[repeat_minute]" type="number" name="medicaltest[repeat_minute]" class="input input-medium" placeholder="0 - 59" disabled/>
  			</div>
		</div>
		<div class="control-group hide">
		<label class="control-label" for="medicaltest[repeat_weekday]">Repeat Weekday</label>
		<div class="controls">
  			<input id="medicaltest[repeat_weekday]" type="number" name="medicaltest[repeat_weekday]" class="input input-medium" placeholder="1 - 54" disabled/>
  			</div>
		</div>
		<div class="control-group hide">
		<label class="control-label" for="medicaltest[repeat_day_interval]">Repeat Day interval</label>
		<div class="controls">
  			<input id="medicaltest[repeat_day_interval]" type="number" name="medicaltest[repeat_day_interval]" class="input input-medium" placeholder="1 - 31" disabled/>
      	</div>
      	</div>
      	<div class="control-group">
      	<button class="btn btn-primary" id="save-weight-reading">Save</button>
      	</div>
		</form>
  </div>
</div>
