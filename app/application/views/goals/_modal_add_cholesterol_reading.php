<style>

</style>

<?php 
$modal_id = "cholesterol-goal-reading-model";
$header_title = "Enter Cholesterol";


$modal_body_html = '
<form id="cholesterol-goal-reading-add" class="form-inline" goal_id="">
  <div class="col-md-6">
    HDL
    <br/>
    <input id="cholesterol_goal_reading_hdl" type="text" name="cholesterol_goal_reading_hdl" class="input" required placeholder=""/>
    <br/>
    LDL
    <br/>
    <input id="cholesterol_goal_reading_ldl" type="text" name="cholesterol_goal_reading_ldl" class="input" required placeholder=""/>
    <br/>
    Triglycerides
    <br/>

    <input id="cholesterol_goal_reading_triglycerides" type="text" name="cholesterol_goal_reading_triglycerides" class="input" required placeholder=""/>
    <br/>
    <br/>
  </div>

  <div class="viam-inputs col-md-6">
    <label>Select a Date :</label>
    <br/>

   <div class="col-md-3 col-xs-2 col-sm-3 sandbox-container">
		<input style="font-size:12px;padding: 0 20px;
	  border: 0 none;background: url(/assets/images/sprite-img.png) no-repeat 0 -581px;
	  text-align: center;cursor: pointer;box-shadow: none;
	  "
	          name="cholesterol_goal_reading_reading_date" 
	          type="text" id="cholesterol_goal_reading_reading_date" >
		</div>
  </div>
</form>
';
$modal_data = array();
$modal_data['modal_id'] = $modal_id;
$modal_data['hide_close_button'] = false;
$modal_data['header_title'] = $header_title;
$modal_data['btn_primary_text'] = 'Save';
$modal_data['footer_html'] = null;
$modal_data['modal_body_html'] = $modal_body_html;


$this->load->view('templates/_modal_viam',$modal_data);
?>
