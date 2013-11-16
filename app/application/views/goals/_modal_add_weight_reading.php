<style>

</style>

<?php 
$modal_id = "weight-goal-reading-model";
$header_title = "Add  weight reading";


$modal_body_html = '
<form id="weight-goal-reading-add" class="form-inline" goal_id="">
        <div class="weight-inputs col-md-6">
          <label>Current Weight</label>
          <div class="frow clearfix">
            <p class="wval">77Kg</p>
              <input id="weight_goal_reading_weight" name="weight_goal_reading_weight" type="hidden" value="77">
              <div class="spnr" id="adj_weight">
                  <a class="inc" href="#"></a>
                  <a class="dec" href="#"></a>
              </div>
          </div>
        </div>
        <div class="viam-inputs col-md-6">
          <label>Select a Date :</label>
          <br/>

         <div class="col-md-3 col-xs-2 col-sm-3 sandbox-container">
			<input style="font-size:12px;padding: 0 20px;
		  border: 0 none;background: url(/assets/images/sprite-img.png) no-repeat 0 -581px;
		  text-align: center;cursor: pointer;box-shadow: none;
		  "
		          name="weight_goal_reading_reading_date" 
		          type="text" id="weight_goal_reading_reading_date" >
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
