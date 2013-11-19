<div class="row">
	<div class="pull-right"><a id="glucose_goal_delete" class="delete-goal" href="#">Delete</a></div>
</div>
<div class="row">
  <form id="glucose-goal-add">
	<input type="hidden" id="glucose_goal_id" value="" />

    <div class="glucose-inputs col-md-4" >
      <label>Current BP</label>
      <div class="">
        Random
      <br/>
      <input id="glucose_goal_current_random" type="text" name="glucose_goal_current_random" class="input" required placeholder=""/>
      </br>
      Fasting
      <br/>
      <input id="glucose_goal_current_fasting" type="text" name="glucose_goal_current_fasting" class="input" required />
      </div>
    </div>
    <div class="glucose-inputs col-md-4" >
      <label>Target BP</label>
      <div class="">
        Random
      <br/>
      <input id="glucose_goal_random" type="text" name="glucose_goal_random" class="input" required placeholder=""/>
      </br>
      Fasting
      <br/>
      <input id="glucose_goal_fasting" type="text" name="glucose_goal_fasting" class="input" required />
      
      </div>
    </div>
    <div class="glucose-inputs col-md-4" >
      <label>Goal Time</label>
      <div class="">
        <div class="goal_time_watch pull-left">&nbsp;</div>
        <div  class="goal_range_dd pull-right">
          <div id="glucose_time_range"></div>
        </div>
      </div>
    </div>
    </form>
</div>
<div class="row">
	<div class="pull-right">
		<button type="button" id="save-glucose-goal" class="btn btn-primary">Save</button>
	</div>
</div>