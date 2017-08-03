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
<?php $user_id=$_SESSION['user_id']; ?>
<?php
$sql_count="select temp_po from customer_job order by id desc limit 0,1";
$res_co=mysql_query($sql_count);
$count=mysql_num_rows($res_co);
$po=0;
if($count==0)
{
	$po=1000;
}	
else
{
	while($row=mysql_fetch_array($res_co))
	{
		$po=$row['temp_po'];
	}	
	$po=$po+1;
}
	
?>
<div class="rt_sidebar open-job pull-left">
	<div class="btns_top display_block">
		<div class="btns_lft pull-left">
			<a href="<?php echo SITE_URL; ?>?section=dashboard" class="custom">Dashboard</a>
		</div>
		<div class="btns_rt pull-right">
			<input type="text" placeholder="Search">
			<a href=""><i class="fa fa-search" aria-hidden="true"></i></a>
		</div>
	</div>
	
	<div class="creat-job-cont display_block">
		<div class="inner_content">
			<div class="inner_pdng">
			<form name="add_job" id="add_job" action="" method="post">
				<input type="hidden" name="cust_id" id="cust_id">
				<input type="hidden" name="temp_po" value="<?php echo $po; ?>">
				<input type="hidden" name="job_insert_by" value="<?php echo $_SESSION['user_id']; ?>">
				<input type="hidden" name="action" value="addcustomerJob">
				<div class="top_cont display_block">
					<div class="po_cont pull-left">
						<ul>
							<li class="pull-left search_filter">
								<input type="text" name="cust_name" placeholder="Customer Name" onkeyup="get_customer();">
								
								<ul class="filter" style="display:none;"></ul>
							</li>
							<li class="pull-right">
								<input type="text" name="po_num" placeholder="PO Number" value="<?php echo $po; ?>">
							</li>
						</ul>

					</div>

					<div class="dte_cont pull-right">
						<?php 
						$today=date('Y-m-d');
						?>
						<table>
							<tbody>
								<tr>
									<td style="width: 49px; padding: 0px 5% 0px 0px;">From</td>
									<td style="width: 126px; position: relative;" class="padding-input">
										<input type="text" placeholder="<?php echo date('m/d/Y', strtotime($today)); ?>" name="from_date" class="datepicker" value="<?php echo date('m/d/Y', strtotime($today)); ?>" readonly>
										<div class="calndr_icns">
											<i class="fa fa-calendar" aria-hidden="true"></i>
										</div>
									</td>
									<td style="width: 49px; padding: 0px 5% 0px 0px;">To</td>
									<td style="width: 126px; position: relative;" class="padding-input">
										<input type="text" placeholder="<?php echo date('m/d/Y', strtotime($today)); ?>" name="to_date" class="datepicker" value="<?php echo date('m/d/Y', strtotime($today)); ?>">
										<div class="calndr_icns">
											<i class="fa fa-calendar" aria-hidden="true"></i>
										</div>
									</td>

								</tr>

							</tbody>
						</table>

					</div>
				</div>
			
				<div class="btm_cont display_block">
					<input type="text" placeholder="Name the Job" name="job_name"> 

				</div>
				<div class="closed_by_usr">
					<div class="pull-right">
						<span>Created by <b> <?php echo get_user_detail($user_id,'first_name'); ?> <?php echo get_user_detail($user_id,'last_name'); ?></b></span>
						<button name="submit" class="custom" type="submit">Create Job</button>
						<img src="assets/images/loader.gif" id="cust_job_loader"style="display:none;" />
					</div> 
				</div>
			</form>
			<div class="result_job"></div>
			</div>
		</div>
	</div>
</div>
<script>
jQuery(function() {
   jQuery( ".datepicker" ).datepicker({
        changeMonth: false,
        changeYear: false,
		 minDate: 0
    });
	
	jQuery('#add_job').validate({
		
		rules: {
			cust_name: {
				required: true
			},
			po_num: {
				required: true
			},
			from_date: {
				required: true
			},
			to_date: {
				required: true
			},
			job_name: {
				required: true
			}
			
			
			
		},
			
		submitHandler: function(form) {
			jQuery('.result_job').empty();
			jQuery('.result_job').show();
			jQuery('#cust_job_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					
					jQuery('#cust_job_loader').hide();
					
					if(data=="0")  
					{
						jQuery('.result_job').empty().append('<div class="alert alert-danger">Please check Dates</div>');
						jQuery('.result_job').fadeOut(3000); 
						
					}
					else 
					{
						jQuery('.result_job').empty().append('<div class="alert alert-success">Job added successfully</div>');
						jQuery('.result_job').fadeOut(3000); 
						window.location.href="<?php echo SITE_URL; ?>?section=customer-job&id="+data;  
					}	 
					
				}
			});
		}
		
	});
	
	
});


function get_customer()
{
	jQuery('.filter').show();
	var cust=jQuery('input[name=cust_name]').val();
	if(cust!='')
	{	
		jQuery.ajax({type: "POST",
		url: "handler.php",
		data: "cust="+cust+"&action=getCustomer", 
		success:function(result)
		{
			jQuery('.filter').empty().append(result);
		},
		error:function(e){
			console.log(e);
		}	
		}); 
	}
	else
	{
		jQuery('.filter').hide(); 
	}	
}

function set_name(id,name)
{
	jQuery('#cust_id').empty().val(id);
	jQuery('input[name=cust_name]').val(name);
	jQuery('.filter').empty();
}

</script>
<?php get_footer(); ?>