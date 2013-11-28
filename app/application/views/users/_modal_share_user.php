
<?php 
$modal_id = "share-user-modal";
$header_title = "Share with....";


$modal_body_html = '<div class="row">
	<div class="col-md-12">
	<form class="form-horizontal" id="share-user-form" role="form">
		<input type="hidden" name="user_id" value="'.$user->id.'" />
	  	<div class="form-group">
			<label for="email" class="col-sm-2 col-md-3 control-label">Share With</label>
			<div class="col-sm-10 col-md-5">
			  <input type="email" class="form-control" name="email" placeholder="Email" 
			  	value="" required>
			</div>
		</div>';
if(!$user->email){
	$modal_body_html.= '

		<div class="form-group">
			<label class="radio-inline">
		      	<input type="radio" name="is_self" id="is_self_1" value="True" checked="checked" > Sharing with the same person as profile
		      </label>
		    <br/>
		    <label class="radio-inline">
		      	<input type="radio" name="is_self" id="is_self_2" value="False"  > Share with a new family member
		    </label>
		</div>';
}
	$modal_body_html.= '

	</form>
</div>


</div>';
$modal_data = array();
$modal_data['modal_id'] = $modal_id;
$modal_data['hide_close_button'] = false;
$modal_data['header_title'] = $header_title;
$modal_data['btn_primary_text'] = 'Share';
$modal_data['footer_html'] = null;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('templates/_modal_viam',$modal_data);
?>
