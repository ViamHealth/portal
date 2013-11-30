
<style>
.rr_row {
	margin: 10px 0px;
}
.medicine_check{
	margin:5px;
}
.is_complete button{
	margin:5px;
}

</style>
<div>
<div class="page-header">
  <h1>Reminders <div class="pull-right viam_date_parent"  id="sandbox-container">
		<input class="viam_date_selector" name="reminder_date" type="text" id="reminder_date" >
	</div></h1>
</div>

<div class="row" >
	<button class="btn btn-success pull-right" data-toggle="modal" data-target="#reminder-modal">Add Reminder</button>
</div>
<div class="row">
	<div class="alert" id="rr_messages">Loading..</div>
	<div class="col-md-12" id="rr_container">
		
	</div>
</div>
</div>

<?php $this->load->view('reminders/_modal_reminder'); ?>

<script>
function get_rr_row_template(type){
	
	if(type == 4) 
		return '<?php $this->load->view("reminders/_row_drappointment",array()); ?>';
	else if(type == 1)
		return '<?php $this->load->view("reminders/_row_others",array()); ?>';
	else if(type == 2)
		return '<?php $this->load->view("reminders/_row_medication",array()); ?>';
	else if(type == 3)
		return '<?php $this->load->view("reminders/_row_medicaltest",array()); ?>';
	else
		return false;
}
</script>
<script src="<?php echo base_url('assets/js/reminders.js') ?>"></script>