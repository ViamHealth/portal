<style>

</style>

<?php 
$modal_id = "blood-pressure-goal-reading-model";
$header_title = "Enter Blood Pressure";


$modal_body_html = '
<form id="blood-pressure-goal-reading-add" class="form-inline" goal_id="">
    <div class="col-md-6">
      Diastolic
      <br/>
      <input id="blood_pressure_goal_reading_systolic_pressure" type="text" name="blood_pressure_goal_reading_systolic_pressure" class="input" required placeholder=""/>
      </br>
      Systolic
      <br/>
      <input id="blood_pressure_goal_reading_diastolic_pressure" type="text" name="blood_pressure_goal_reading_diastolic_pressure" class="input" required />
      <br/>
      Pulse
      <br/>
      <input id="blood_pressure_goal_reading_pulse_rate" type="text" name="blood_pressure_goal_reading_pulse_rate" class="input" required />
         
    </div>
    <div class="viam-inputs col-md-6">
      <label>Select a Date :</label>
      <br/>

     <div class="col-md-3 col-xs-2 col-sm-3 sandbox-container">
			<input style="font-size:12px;padding: 0 20px;
		  border: 0 none;background: url(/assets/images/sprite-img.png) no-repeat 0 -581px;
		  text-align: center;cursor: pointer;box-shadow: none;
		  "
		          name="blood_pressure_goal_reading_reading_date" 
		          type="text" id="blood_pressure_goal_reading_reading_date" >
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
