<?php


$modal_id = "edit-file-modal";

$footer_html = '';
$header_title = 'Edit file description';

$modal_body_html = '
  <img id="file_edit_img" width="100px" src="" style="display:none;"></img>
    <div style="height:40px;"><span class="filetype_icon img_ft"></span>
      <span type="text" id="file_edit_name" name="name"  ></span>
    </div>  
    <div>
    Description :-
    <input type="text" id="file_edit_description" name="description" value="" />
    </div>';
$modal_data = array();
$modal_data['modal_id'] = $modal_id;
$modal_data['hide_close_button'] = True;
$modal_data['disallow_close'] = True;
$modal_data['hide_footer'] = True;
$modal_data['header_title'] = $header_title;
$modal_data['btn_primary_text'] = 'Save';
$modal_data['footer_html'] = null;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('templates/_modal_viam',$modal_data);
?>
