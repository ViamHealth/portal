<script src="<?php echo base_url('assets/js/user.js') ?>"></script>

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
		</div>
	</div>
</div>
