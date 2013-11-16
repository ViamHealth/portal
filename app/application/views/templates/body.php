
<?php
$family_array = array();
$sidebar_family_array = array();
if($loggedin)
{
	foreach ($family as $key => $value) {
		$active = false;
		if($value->id == $appuser->id){
			$li_class = " class='active visible-lg hidden-sm hidden-xs ' ";
		} else {
			$li_class = " class='visible-lg hidden-sm hidden-xs' ";
		}
		if($value->first_name && $value->last_name)
			$visible_identity = $value->first_name." ".$value->last_name;
		else if($value->first_name)
			$visible_identity = $value->first_name;
		else if($value->email)
			$visible_identity = $value->email;
		else
			$visible_identity = $value->username;
		$sidebar_family_array[] = 
"<li $li_class ><a href=".viam_url('/home/',$value->id)."><img  height='25' width='25' src='".$value->profile->profile_picture_url."' /> &nbsp; $visible_identity</a></li>";
	}
}
?>


<div class="container" >
<?php if($loggedin): ?>
	<div class="col-md-3">
		<div class="well well-small sidebar-nav">
			<ul class="nav nav-list">
				<li class="nav-header">Family Profiles</li>
				<?php foreach($sidebar_family_array as $li): ?>
					<?php echo $li; ?>
				<?php endforeach; ?>
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

<?php //var_dump($family); ?>