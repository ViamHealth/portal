<div id="weight-goal-reading-model" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="Add reading for weight goal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        Enter Weight
      </div>
      <div class="modal-body" itemid="">
      <form id="weight-goal-reading-add" class="form-inline" goal_id="">
        <div class="weight-inputs span6">
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
        <div class="viam-inputs span6">
          <label>Select a Date :</label>
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
          name="weight_goal_reading_reading_date" class="disp_dp fld_box hasDatepicker" 
          type="text" value="" id="weight_goal_reading_reading_date" data-date-format="yyyy-mm-dd">
        </div>
        <!--<form id="weight-goal-reading-add" class="form-inline" goal_id="">
          		<input id="weight_goal_reading_weight" type="text" name="weight_goal_reading_weight" class="input input-small" required placeholder="weight"/>
          		<input id="weight_goal_reading_reading_date" type="date" name="weight_goal_reading_reading_date" class="input input-medium" required/>
    		</form>-->
      </form>
      </div><!-- /.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button"  id="save-weight-reading" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->