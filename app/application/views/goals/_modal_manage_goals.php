<style>

</style>

<?php 
$modal_id = "manage-goals-modal";
$header_title = "Manage Goals";


$modal_body_html = $this->load->view('goals/_manage_goals_modal_body',NULL, true);
$modal_data = array();
$modal_data['modal_id'] = $modal_id;
$modal_data['hide_close_button'] = false;
$modal_data['header_title'] = $header_title;
$modal_data['btn_primary_text'] = null;
$modal_data['footer_html'] = null;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('templates/_modal_viam',$modal_data);
?>
