<div id="glucose-goal-reading-model" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Add reading for blood pressure goal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        Enter Blood Pressure
      </div>
      <div class="modal-body" itemid="">
      <form id="glucose-goal-reading-add" class="form-inline" >
        <div class="glucose-inputs span6">
          Random
          <br/>
          <input id="glucose_goal_reading_random" type="text" name="glucose_goal_reading_random" class="input "  />
          </br>
          Fasting
          <br/>
          <input id="glucose_goal_reading_fasting" type="text" name="glucose_goal_reading_fasting" class="input "  />

        </div>
        <div class="viam-inputs span6">
          <label>Select a Date :</label>
          <br/>
          <input style="width: 138px;
  height: 18px;
  font: 12px Segoe UI, Arial, Helvetica, sans-serif;
  color: #333;
  line-height: 18px;
  padding: 0 11px;
  border: 0 none;
  background: url(/images/sprite-img.png) no-repeat 0 -581px;
  margin: 0 0 5px 0;
  cursor: pointer;
  box-shadow: none;
  "
          name="glucose_goal_reading_reading_date" class="disp_dp fld_box hasDatepicker" 
          type="text" value="" id="glucose_goal_reading_reading_date" data-date-format="yyyy-mm-dd">
        </div>
      </form>
      </div><!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button"  id="save-glucose-reading" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->