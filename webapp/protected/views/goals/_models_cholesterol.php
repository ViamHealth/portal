<div id="cholesterol-goal-reading-model" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Add reading for blood pressure goal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        Enter Blood Pressure
      </div>
      <div class="modal-body" itemid="">
      <form id="cholesterol-goal-reading-add" class="form-inline" >
        <div class="cholesterol-inputs span6">
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
          name="cholesterol_goal_reading_reading_date" class="disp_dp fld_box hasDatepicker" 
          type="text" value="" id="cholesterol_goal_reading_reading_date" data-date-format="yyyy-mm-dd">
        </div>
      </form>
      </div><!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button"  id="save-cholesterol-reading" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->