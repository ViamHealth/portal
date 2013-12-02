
<?php 
$modal_id = "change-password-modal";
$header_title = "Change Password";


$modal_body_html = '<div class="row">
	<div class="col-md-12">
	<form class="form-horizontal" id="change-password-form" role="form">
	  	<div class="form-group">
			<label for="old_password" class="col-sm-2 col-md-3 control-label">Old Password</label>
			<div class="col-sm-10 col-md-5">
			  <input type="password" class="form-control" name="old_password" placeholder="Old Password" 
			  	value="" required>
			</div>
		</div>
		<div class="form-group">
			<label for="password" class="col-sm-2 col-md-3 control-label">New Password</label>
			<div class="col-sm-10 col-md-5">
			  <input type="password" class="form-control" name="password" placeholder="New Password" value="" required>
			</div>
		</div>
		<div class="form-group">
			<label for="confirm_password" class="col-sm-2 col-md-3 control-label">Confirm Password</label>
			<div class="col-sm-10 col-md-5">
			  <input type="password" class="form-control" name="confirm_password" placeholder="Same as above" value="" required>
			</div>
		</div>
		<div class="wrong_password" style="display:none;">Incorrect Old Password</div>
	</form>
</div>


</div>';
$modal_data = array();
$modal_data['modal_id'] = $modal_id;
$modal_data['hide_close_button'] = false;
$modal_data['header_title'] = $header_title;
$modal_data['btn_primary_text'] = 'Change';
$modal_data['footer_html'] = null;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('templates/_modal_viam',$modal_data);
?>
