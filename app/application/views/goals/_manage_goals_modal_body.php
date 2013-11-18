<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#weight-goal-add">
          Weight
        </a>
      </h4>
    </div>
    <div id="weight-goal-add" class="panel-collapse collapse in">
      <div class="panel-body" id="">
        <?php $this->load->view('goals/_manage_weight',NULL); ?>
        
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#blood-pressure-goal-add">
          Blood Pressure
        </a>
      </h4>
    </div>
    <div id="blood-pressure-goal-add" class="panel-collapse collapse">
      <div class="panel-body" id="manage-blood-pressure-goal-body">
        <?php $this->load->view('goals/_manage_blood_pressure',NULL); ?>
      </div>
    </div>
  </div>
</div>