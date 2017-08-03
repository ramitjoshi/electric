<?php 
if(isset($_SESSION['user_id']))
{
?>
<script>window.location.href="<?php echo SITE_URL; ?>?section=dashboard";</script>
<?php
die;
}
get_header(); ?>	 	
<?php
if($_SESSION['user_id']=='')
{	
?>



<div class="login-main">
	<div class="login-lft">
		<div class="login-logo">
			<img src="assets/images/logo-login.jpg" alt="">
		</div>
	</div>
	<div class="login-right">
		<h4 class="close_title">Log in</h4>
		<?php
		if(isset($_GET['c']))
		{
		?>
		
		<div class="inner_content">
			<div class="inner_pdng">
				<form name="login" id="login" action="" method="post">
					<input type="hidden" name="session_id" value="<?php echo session_id(); ?>" />
					<input type="text"  id="username" name="username" placeholder="Username">
					<input type="password" id="Password" name="user_pass" placeholder="Password">
					<div class="captcha">
						<div class="g-recaptcha" data-sitekey="6LdG1AYUAAAAAJJVL5IdIEqm2KRhsSPI6W5Sdt91" data-callback="recaptchaCallback"></div> 
					</div>
					<div class="display_block"><a href="<?php echo SITE_URL; ?>?section=forgot-password" class="forgot">Forgot Password?</a></div>
					<input type="hidden" name="action" value="ValidLogin">	
					<input type="submit" class="custom edit_cstm login-login" value="LOGIN" id="submitBtn" disabled>
					<img src="assets/images/loader.gif" id="login_loader" style="display:none;"/>	
					<div class="result"></div>
				</form>
			</div>
		</div>
		<?php	
		}
		else
		{		
		?>
		<div class="inner_content">
			<div class="inner_pdng">
				<form name="login" id="login" action="" method="post">
					<input type="hidden" name="session_id" value="<?php echo session_id(); ?>" />
					<input type="text"  id="username" name="username" placeholder="Username">
					<input type="password" id="Password" name="user_pass" placeholder="Password">

					<div class="display_block"><a href="<?php echo SITE_URL; ?>?section=forgot-password" class="forgot">Forgot Password?</a></div>
					<input type="hidden" name="action" value="ValidLogin">	
					<input type="submit" class="custom edit_cstm login-login" value="LOGIN">
					<img src="assets/images/loader.gif" id="login_loader" style="display:none;"/>	
					<div class="result"></div>
				</form>
			</div>
		</div>
		<?php
		}
		?>
	</div>


</div>
<script>
function recaptchaCallback() { jQuery('#submitBtn').removeAttr('disabled')}; 
jQuery(document).ready(function()
{
	jQuery('.login-main').unwrap(); 
	
});
</script>
<?php
}
else
{
?>
<script>window.location.href="<?php echo SITE_URL; ?>?section=dashboard";</script>
<?php	
}	
?>

<?php get_footer(); ?>
    