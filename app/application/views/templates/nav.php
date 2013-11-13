<div class="navbar navbar-default navbar-static-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"> &nbsp; </a>
    </div>
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">

      
      <?php if($loggedin): ?>
      	<li class="<?php echo active_link('healthfiles','index'); ?>">
        	<a href="<?php echo site_url('files'); ?>">Files</a>
        </li>
        <li class="<?php echo active_link('fooddiary','index'); ?>">
          <a href="<?php echo site_url('diary'); ?>">Food Diary</a>
        </li>


  	  <?php else: ?>
        <li class="<?php echo active_link('site','login'); ?>">
        	<a href="<?php echo site_url('login'); ?>">Login</a>
        </li>
        <li class="<?php echo active_link('site','signup'); ?>">
        	<a href="<?php echo site_url('signup'); ?>">Signup</a>
        </li>
      <?php endif ?>
      </ul>
    </div><!--/.nav-collapse -->
  </div>
</div>