<?php


$modal_id = "family-invite-user-modal";

$footer_html = null;
$header_title = 'Invite a family member';

$modal_body_html = '
	<form class="form-horizontal" id="family-invite-user-form" role="form">
	  	<div class="form-group">
			<label for="email" class="col-sm-2 col-md-3 control-label">Email</label>
			<div class="col-sm-10 col-md-5">
			  <input type="text" class="form-control" name="email" placeholder="Email" 
			  value="" required>
			</div>
		</div>
		<div class="saving_message" style="display:none;">Sending invite</div>
	</form>
';
$modal_data = array();
$modal_data['modal_id'] = $modal_id;
$modal_data['hide_close_button'] = False;
$modal_data['header_title'] = $header_title;
$modal_data['btn_primary_text'] = 'Send Invite';
$modal_data['footer_html'] = $footer_html;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('templates/_modal_viam',$modal_data);
?>
