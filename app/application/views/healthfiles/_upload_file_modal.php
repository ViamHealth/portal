<?php


$modal_id = "upload-file-modal";

$footer_html = null;
$header_title = 'Uploading new file';

$modal_body_html = '<div id="progress">
	    <div class="bar" style="width: 0%;height: 18px;background: #38B452;"></div>
	</div>';
$modal_data = array();
$modal_data['modal_id'] = $modal_id;
$modal_data['hide_close_button'] = TRUE;
$modal_data['header_title'] = $header_title;
$modal_data['btn_primary_text'] = null;
$modal_data['footer_html'] = $footer_html;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('templates/_modal_viam',$modal_data);
?>
