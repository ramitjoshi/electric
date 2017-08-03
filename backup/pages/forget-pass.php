<?php 
get_header(); ?>

<div class="login-main">
	<div class="login-lft">
		<div class="login-logo">
			<a href="<?php echo SITE_URL; ?>"><img src="assets/images/logo-login.jpg" alt=""></a>
		</div>
	</div>
	<div class="login-right">
		<h4 class="close_title">FORGET PASSWORD</h4> 
		
		<div class="inner_content">
			<div class="inner_pdng">
				<form name="for_pass" id="for_pass" action="" method="post">
					<input type="hidden" name="session_id" value="<?php echo session_id(); ?>" />
					<input type="text" id="email" name="email" placeholder="Email Address">
					<input type="hidden" name="action" value="ForgetPassword">	 
					<input type="submit" class="custom edit_cstm login-login" value="Submit" id="submitBtn">
					<img src="assets/images/loader.gif" id="login_loader" style="display:none;"/>	
					<div class="result"></div>
				</form>
			</div>
		</div>
		
		
	</div>


</div>

<script>
jQuery(document).ready(function()
{
	jQuery('.login-main').unwrap(); 
	
});
</script>
<?php get_footer(); ?>
			
