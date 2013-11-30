<?php


$modal_id = "share-file-modal";

$footer_html = '';
$header_title = 'Share file';

$modal_body_html = '
  <div class="row">
	<form class="form-horizontal" id="share-file-form" role="form">
		<div class="form-group ">
			<label for="email" class="col-sm-2 col-md-3 control-label">Share with :</label>
			<div class="col-sm-10 col-md-5">
			  <input type="email" class="form-control" name="email" placeholder="Email" value="" required>
			</div>
		</div>
	</form>
	
</div>
';
$modal_data = array();
$modal_data['modal_id'] = $modal_id;
$modal_data['hide_close_button'] = False;
$modal_data['disallow_close'] = False;
$modal_data['hide_footer'] = False;
$modal_data['header_title'] = $header_title;
$modal_data['btn_primary_text'] = 'Share';
$modal_data['footer_html'] = null;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('templates/_modal_viam',$modal_data);
?>
