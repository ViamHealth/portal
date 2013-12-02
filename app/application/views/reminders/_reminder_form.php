<div class="row">
	<div class="col-md-12">
	<form class="form-horizontal" id="reminder-form" role="form">
		<div class="form-group">
			<label for="type" class="col-sm-2 col-md-3  control-label">Reminder Type</label>
			<div class="col-sm-10 col-md-9">
				<select class="form-control" name="reminder_type" required >
					<option value="" >Choose reminder type</option>
			  		<option value="4" >Dr. Appointment</option>
			  		<option value="3" >Medical Test</option>
			  		<option value="2" >Medication</option>
			  		<option value="1" >Other</option>
				</select>
			</div>
		</div>
		<div class="form-group ">
			<label for="name" class="col-sm-2 col-md-3 control-label">Title</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="name" placeholder="Title" value="" required>
			</div>
		</div>
		<div class="form-group ">
			<label for="details" class="col-sm-2 col-md-3 control-label">Details</label>
			<div class="col-sm-10 col-md-9">
			  <input type="text" class="form-control" name="details" placeholder="Details" value="" >
			</div>
		</div>
		<div class="form-group">
			<label for="type" class="col-sm-2 col-md-3  control-label">Repeat Mode</label>
			<div class="col-sm-10 col-md-9">
				<select class="form-control" name="repeat_mode" required >
					<option value="" >Choose Repeat Mode</option>
			  		<option value="0" >None</option>
			  		<option value="3" >Daily</option>
			  		<option value="2" >Weekly</option>
			  		<option value="1" >Monthly</option>
			  		<option value="4" >Yearly</option>
				</select>
			</div>
		</div>
		<div class="form-group ">
			<label for="start_date" class="col-sm-2 col-md-3 control-label">Start Date</label>
			<div class="col-sm-10 col-md-6 viam_date_parent" id="reminder_start_date_parent">
				<div class="input-append date">
				<input class="viam_date_selector" name="start_date" type="text" id="reminder_start_date" required >
				<span class="add-on glyphicon glyphicon-calendar"></span>
		  		</div>
			</div>
		</div>

		<div class="form-group hidden" >
			<label for="end_date" class="col-sm-2 col-md-3 control-label">End Date</label>
			<div class="col-sm-10 col-md-6 viam_date_parent" id="reminder_end_date_parent" >
				<div class="input-append date">
				<input class="viam_date_selector" name="end_date" type="text" id="reminder_end_date" >
				<span class="add-on glyphicon glyphicon-calendar"></span>
		  		</div>
			</div>
		</div>
		<hr/>
		<div class="form-group hidden">
			<label for="morning_count" class="col-sm-2 col-md-3 control-label">Morning Count</label>
			<div class="col-sm-10 col-md-9">
			  <input type="text" class="form-control" name="morning_count" placeholder="Morning Count" value="" >
			</div>
		</div>
		<div class="form-group hidden">
			<label for="afternoon_count" class="col-sm-2 col-md-3 control-label">afternoon Count</label>
			<div class="col-sm-10 col-md-9">
			  <input type="text" class="form-control" name="afternoon_count" placeholder="afternoon Count" value="" >
			</div>
		</div>
		<div class="form-group hidden">
			<label for="night_count" class="col-sm-2 col-md-3 control-label">night Count</label>
			<div class="col-sm-10 col-md-9">
			  <input type="text" class="form-control" name="night_count" placeholder="night Count" value="" >
			</div>
		</div>
		<div class="form-group ">
			<label for="submit" class="col-sm-2 col-md-3 control-label"></label>
			<div class="col-sm-10 col-md-5">
			  <button type="submit" class="form-control btn btn-save btn-primary" name="submit" >Save</button>
			</div>
		</div>
	</form>
</div>
</div>