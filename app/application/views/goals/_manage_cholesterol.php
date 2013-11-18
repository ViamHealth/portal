<div class="row">
	<div class="pull-right"><a id="cholesterol_goal_delete" class="delete-goal" href="#">Delete</a></div>
</div>
<div class="row">
  <form id="cholesterol-goal-add">
	<input type="hidden" id="cholesterol_goal_id" value="" />

    <div class="cholesterol-inputs col-md-4" >
      <label>Current BP</label>
      <div class="">
        HDL
      <br/>
      <input id="cholesterol_goal_current_hdl" type="text" name="cholesterol_goal_current_hdl" class="input" required placeholder=""/>
      </br>
      LDL
      <br/>
      <input id="cholesterol_goal_current_ldl" type="text" name="cholesterol_goal_current_ldl" class="input" required />
      Triglycerides
      <br/>
      <input id="cholesterol_goal_current_triglycerides" type="text" name="cholesterol_goal_current_triglycerides" class="input" required />
      
      </div>
    </div>
    <div class="cholesterol-inputs col-md-4" >
      <label>Target BP</label>
      <div class="">
        HDL
      <br/>
      <input id="cholesterol_goal_ldl" type="text" name="cholesterol_goal_ldl" class="input" required placeholder=""/>
      </br>
      LDL
      <br/>
      <input id="cholesterol_goal_hdl" type="text" name="cholesterol_goal_hdl" class="input" required />
       Triglycerides
      <br/>
      <input id="cholesterol_goal_triglycerides" type="text" name="cholesterol_goal_triglycerides" class="input" required />
      
      </div>
    </div>
    <div class="cholesterol-inputs col-md-4" >
      <label>Goal Time</label>
      <div class="">
        <div class="goal_time_watch pull-left">&nbsp;</div>
        <div  class="goal_range_dd pull-right">
          <div id="cholesterol_time_range"></div>
        </div>
      </div>
    </div>
    </form>
</div>
<div class="row">
	<div class="pull-right">
		<button type="button" id="save-cholesterol-goal" class="btn btn-primary">Save</button>
	</div>
</div>