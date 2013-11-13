<div class="container" >
<?php if($loggedin): ?>
	<div class="col-md-3">
		<div class="well well-small sidebar-nav">
			<ul class="nav nav-list">
				<li class="nav-header">Family Profiles</li>
			</ul>
			<div class="row" style="margin-top:5px;">
				<a href="<?php //echo $this->createUrl('/user/add',array()); ?>"><button id="family-users-add" class="btn btn-success  col-md-4 col-md-offset-1" type="button">Add</button></a>
				<a href="<?php //echo $this->createUrl('/user/invite',array()); ?>"><button id="family-users-invite" class="btn btn-info  col-md-5 col-md-offset-1" type="button">Invite</button></a>

			</div>
		</div>
	</div>
	<div class="col-md-9 well">
<?php else: ?>

		<div class="row">
			<div class="col-md-12" style="">
<?php endif ?>