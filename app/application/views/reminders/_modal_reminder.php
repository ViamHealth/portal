
<?php 
$modal_id = "reminder-modal";
$header_title = "Reminder";

$modal_body_html=$this->load->view('reminders/_reminder_form',array(),TRUE);
	
$modal_data = array();
$modal_data['modal_id'] = $modal_id;
$modal_data['hide_close_button'] = false;
$modal_data['header_title'] = $header_title;
$modal_data['btn_primary_text'] = null;
$modal_data['footer_html'] = null;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('templates/_modal_viam',$modal_data);
?>
