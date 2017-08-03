<div class="lft_sidebar pull-left">
	<div class="logo text-center">
		<a href="<?php echo SITE_URL; ?>"><img src="assets/images/logo.jpg" alt="logo"></a>
	</div>
	<div class="responisve-menu">
		<div class="click-menu">
	<span class="menu-icon">
			<i class="fa fa-bars" aria-hidden="true"></i>
	</span>	
	<span class="menu-cross">
			<i class="fa fa-times" aria-hidden="true"></i>	
	</span>	
</div>
</div>
			

	<?php
	if($_SESSION['user_id']!='')
	{	
		$role=get_user_detail($_SESSION['user_id'],'role');		
	?>
	<div class="user-info">
	<h4>Welcome <span><?php echo get_user_detail($_SESSION['user_id'],'first_name'); ?></span> <span><?php echo get_user_detail($_SESSION['user_id'],'last_name'); ?></span></h4>
	<h4>You are login as <span><?php echo get_user_detail($_SESSION['user_id'],'role'); ?></span></h4>
	</div>
	
	<nav>
		<ul>
			
			<li class="<?php if($_GET['section']=="dashboard") { echo 'active'; } ?>"><a href="<?php echo SITE_URL; ?>?section=dashboard">Dashboard</a></li>
			<li class="<?php if($_GET['section']=="customer") { echo 'active'; } ?>"><a href="<?php echo SITE_URL; ?>?section=customer">Customers</a></li>
			<li class="<?php if($_GET['section']=="vendors") { echo 'active'; } ?>"><a href="<?php echo SITE_URL; ?>?section=vendors">Vendors</a></li>
			<li class="<?php if($_GET['section']=="vehicle") { echo 'active'; } ?>"><a href="<?php echo SITE_URL; ?>?section=vehicle">Vehicles</a></li>
			<?php
			if($role=="admin")
			{	
			?>
				<li class="<?php if($_GET['section']=="staff") { echo 'active'; } ?>"><a href="<?php echo SITE_URL; ?>?section=staff">Staff</a></li> 
			<?php
			}
			?>
			
			
			<li class="<?php if($_GET['section']=="timesheet") { echo 'active'; } ?>"><a href="<?php echo SITE_URL; ?>?section=timesheet">Timesheets</a></li>
			
			<li class="<?php if($_GET['section']=="setting") { echo 'active'; } ?>"><a href="<?php echo SITE_URL; ?>?section=setting">Settings</a></li>
			
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</nav>
	<?php
	}
	?>
	
</div>