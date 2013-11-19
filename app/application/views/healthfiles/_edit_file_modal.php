<div id="" data-fileid="" data-backdrop="static" data-keyboard="false" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Upload a file" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    
  </div>
  <div class="modal-body" >
  	
    <br/>
    <button id="family-users-add" class="btn btn-success" type="button">Save</button>
  </div>
</div>


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
$modal_data['hide_close_button'] = null;
$modal_data['header_title'] = $header_title;
$modal_data['btn_primary_text'] = 'Save';
$modal_data['footer_html'] = null;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('templates/_modal_viam',$modal_data);
?>
