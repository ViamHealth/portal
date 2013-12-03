
<?php 
$modal_id = "add-exercise-modal";
$header_title = "Add exercise";


$modal_body_html = '<div class="row">
	<div class="col-md-12">
	<form class="form-horizontal" id="add-exercise-form" role="form">
		<div class="form-group">
			<label for="physical_activity" class="col-sm-2 col-md-3  control-label">Acitity</label>
			<div class="col-sm-10 col-md-9">
				<select class="form-control" name="physical_activity" required >
					<option value="" >Choose Activity</option>
			  		
				</select>
			</div>
		</div>
		<div class="form-group ">
			<label for="activity_date" class="col-sm-2 col-md-3 control-label">Activity Date</label>
			<div class="col-sm-10 col-md-6 viam_date_parent" id="activity_date_parent" >
				<div class="input-append date">
				<input class="viam_date_selector" name="activity_date" type="text" id="activity_date" >
				<span class="add-on glyphicon glyphicon-calendar"></span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="weight" class="col-sm-2 col-md-3 control-label">Weight</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="weight" placeholder="Weight" 
			  	value="" required>
			</div>
		</div>
		<div class="form-group">
			<label for="time_spent" class="col-sm-2 col-md-3 control-label">Time Spent (minutes)</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="time_spent" placeholder="Time Spent" 
			  	value="" required>
			</div>
		</div>
	</form>
</div>


</div>';
$modal_data = array();
$modal_data['modal_id'] = $modal_id;
$modal_data['hide_close_button'] = false;
$modal_data['header_title'] = $header_title;
$modal_data['btn_primary_text'] = 'Save';
$modal_data['footer_html'] = null;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('templates/_modal_viam',$modal_data);
?>
