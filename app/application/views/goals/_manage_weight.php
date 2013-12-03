<div class="row">
	<div class="pull-right"><a id="weight_goal_delete" goal_id="" class="delete-goal" href="#">Delete</a></div>
</div>
<form class="form-inline" id="weight-goal-add-form" role="form">
<div class="row">
  
	<input type="hidden" id="weight_goal_id" value="" />
	<div class="weight-inputs col-md-4" id="weight_goal_current_weight_div">
      <label>Current Weight</label>
      <div class="">
        <!--<p class="wval">77Kg</p>-->
          <input id="weight_goal_current_weight" class="form-control" name="weight_goal_current_weight" type="text" value="" required >
          <!--<div class="spnr" id="adj_current_weight">
              <a class="inc" href="#"></a>
              <a class="dec" href="#"></a>
          </div>-->
      </div>
    </div>
    <div class="weight-inputs col-md-4" id="weight_goal_target_weight_div">
      <label>Goal Weight</label>
      <div class="">
        <!--<p class="wval">77Kg</p>-->
          <input id="weight_goal_target_weight" class="form-control" name="weight_goal_target_weight" type="text" value="" required>
          <!--<div class="spnr" id="adj_target_weight">
              <a class="inc u_arw_grn" href="#"></a>
              <a class="dec d_arw_red" href="#"></a>
          </div>-->
      </div>
    </div>
    <div class="weight-inputs col-md-4" >
      <label>Goal Time</label>
      <div class="">
        <div class="goal_time_watch pull-left">&nbsp;</div>
        <div  class="goal_range_dd pull-right">
        	<div id="weight_time_range"></div>
        </div>
      </div>
    </div>
</div>
<div class="row">
	<div class="pull-right">
		<button type="submit" id="save-weight-goal" class="btn btn-primary">Save</button>
	</div>
</div>
</form>