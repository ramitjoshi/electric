<?php 
if(!isset($_SESSION['user_id']))
{
?>
<script>window.location.href="<?php echo SITE_URL; ?>?section=login";</script>
<?php
die;
}
get_header(); ?>

		<?php get_sidebar(); ?>
		<?php
		$user_id=$_SESSION['user_id'];
		$role=get_user_detail($user_id,'role');
		?>
        <div class="rt_sidebar pull-left">
            <div class="btns_top display_block">
                <div class="btns_lft pull-left">
                    <a href="<?php echo SITE_URL; ?>?section=dashboard" class="custom">Dashboard</a>
                   
                </div>
                <div class="btns_rt pull-right">
                    <!--
					<input type="text" placeholder="Search">
                    <a href=""><i class="fa fa-search" aria-hidden="true"></i></a>
					-->
                </div>
            </div>
			 <div id="fromToggle" class="collapsible-area">	<div class="new_custmer">
				
			    <h4 class="close_title">SETTINGS</h4></div>
                <div class="close_container custmr_edt_cnt">
                    <form name="edit_per_info" id="edit_per_info" action="" method="post" class="new_cstmr">
						<input type="hidden" name="action" value="EditPerInfo">
						<input type="hidden" name="user_id" value="<?php echo $user_id; ?>"> 
						<div class="cstm-wid top_inpt">
							<ul> 
								
								<li>
									<input type="password" name="user_pass" placeholder="Current Password"> 
								</li>
								<li>
									<input type="password" name="new_pass" id="new_pass" placeholder="New Password">
								</li>
								<li>
									<input type="password" name="cnf_pass" placeholder="Confirm Password">
								</li>
								
							</ul>
						</div>
						<div class="btn_deit">
							<button type="submit" class="custom edit_cstm">Save</button>
							<img src="assets/images/loader.gif" id="addSaff_loader" style="display:none;" />
						</div> 
						
					</form>
					<div class="result"></div>
                </div>
			</div>
			<div id="editFromToggle" class="collapsible-area new_cstmr" style="display:none"></div>
			
            
        </div>
<script>
jQuery(document).ready(function()
{
	jQuery('#edit_per_info').validate({
		
		rules: {
			curr_pass: {
				required: true
			},
			new_pass: {
				required: true
			},
			cnf_pass: {
				required: true,
				equalTo: "#new_pass"
			}  
		},
			
		submitHandler: function(form) {
			jQuery('.result').empty();			
			jQuery('.result').show();	
			jQuery('#addSaff_loader').show();	
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{
					jQuery('#addSaff_loader').hide(); 	
					if(data==0)
					{
						jQuery('.result').empty().append('<div class="alert alert-danger">Your password not matched</div>');
						jQuery('.result').fadeOut(3000);
					}	
					else
					{
						jQuery('.result').empty().append('<div class="alert alert-success">Your password has been changed.</div>');
						jQuery('.result').fadeOut(3000);
					}	
				}
			});
		}
		
	});
});
</script>	
<?php get_footer(); ?>		
