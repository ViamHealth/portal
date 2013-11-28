<style>
.btn.btn-facebook1
{
    background-color: #3b5998;
    color: #fff;
    font-weight:bold;
    background-image: none;
}

.btn.btn-facebook1:hover, .btn.btn-block.btn-facebook1:focus
{
    background-color: #3b5998;
    color: #fff;
    font-weight:bold;
}
</style>
<script src="<?php echo base_url('assets/js/user.js') ?>"></script>

<?php $this->load->view('users/_modal_change_password'); ?>

<?php $this->load->view('users/_modal_share_user',$user); ?>

<div class="row">
	<div class="col-md-6 pull-right">
	<?php if ($current_user_id == $appuser->id) : ?>
		<a href="#" onclick='$("#change-password-modal").modal()'; >Change password</a>
		<?php if(!$appuser->profile->fb_profile_id): ?>
			<button onclick="fb_attach();" class="btn btn-facebook1 btn-default">Login with Facebook</button>
		<?php endif ?>
		
	<?php else: ?>
		<button onclick='$("#share-user-modal").modal();' class="btn btn-facebook1 btn-default pull-right">Share User</button>
	<?php endif ?>
	</div>
</div>	

<div class="row" >
	<div  class="col-md-12">
		<div class="panel-group" id="accordion">
		  <div class="panel panel-primary">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#profile-details">
		          Profile Details
		        </a>
		      </h4>
		    </div>
		    <div id="profile-details" class="panel-collapse collapse in">
		     	<div class="panel-body" >
		      		<?php $this->load->view('users/_profile_details',$user); ?>
		     	</div>
		    </div>
		  </div>
		  <div class="panel panel-primary">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#health-stats">
		          Basic Information
		        </a>
		        <div class="pull-right">
		        <?php echo $user->bmi_profile->get_bmi_classification_text(); ?>
		        </div>
		      </h4>
		    </div>
		    <div id="health-stats" class="panel-collapse collapse">
		      <div class="panel-body">
		        <?php $this->load->view('users/_health_stats',$user); ?>
		      </div>
		    </div>
		  </div>
		  <div class="panel panel-primary">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#profile-bp">
		          Blood Pressure Profile
		        </a>
		        <div class="pull-right">
		        <?php echo $user->bmi_profile->get_bp_classification_text(); ?>
		        </div>
		      </h4>
		    </div>
		    <div id="profile-bp" class="panel-collapse collapse">
		     	<div class="panel-body" >
		      		<?php $this->load->view('users/_profile_bp',$user); ?>
		     	</div>
		    </div>
		  </div>

		  <div class="panel panel-primary">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#profile-cholesterol">
		          Cholesterol Profile
		        </a>
		        <div class="pull-right">
		        <?php echo $user->bmi_profile->get_cholesterol_classification_text(); ?>
		        </div>
		      </h4>
		    </div>
		    <div id="profile-cholesterol" class="panel-collapse collapse">
		     	<div class="panel-body" >
		      		<?php $this->load->view('users/_profile_cholesterol',$user); ?>
		     	</div>
		    </div>
		  </div>

		  <div class="panel panel-primary">
		    <div class="panel-heading">
		      <h4 class="panel-title">
		        <a data-toggle="collapse" data-parent="#accordion" href="#profile-glucose">
		          Sugar Profile
		        </a>
		        <div class="pull-right">
		        <?php echo $user->bmi_profile->get_sugar_classification_text(); ?>
		        </div>
		      </h4>
		    </div>
		    <div id="profile-glucose" class="panel-collapse collapse">
		     	<div class="panel-body" >
		      		<?php $this->load->view('users/_profile_glucose',$user); ?>
		     	</div>
		    </div>
		  </div>

		</div>
	</div>
</div>
