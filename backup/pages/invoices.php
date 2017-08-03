<?php 
if(!isset($_SESSION['user_id']))
{
?>
<script>window.location.href="<?php echo SITE_URL; ?>?section=login";</script>
<?php
die;
}
get_header(); ?>
<style>
#example_filter{
	display:none;
}
#example_info{
	display:block;
}
#example_paginate{
	display:block;
}
</style>
		<?php get_sidebar(); ?>
		
        <div class="rt_sidebar pull-left">
            <div class="btns_top display_block">
                <!--
				<div class="btns_lft pull-left without_search">
                   
                    <a href="javascript:void(0);" onclick="jQuery('.box').slideUp();jQuery('.creat-job-cont').slideDown();" class="custom">New Job</a>
                    <a href="javascript:void(0);" class="custom" onclick="show_hide('1');">New Customer</a>
                </div>
				-->
                <!--
				<div class="btns_rt pull-right">
                    <input type="text" placeholder="Search">
                    <a href=""><i class="fa fa-search" aria-hidden="true"></i></a>
                </div>
				-->
            </div>
			
			<div class="box creat-job-cont display_block" style="display:none;">
				<div class="new_custmer">
					<a class="close_new" onclick="jQuery('.creat-job-cont').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
					<h4 class="close_title">NEW JOB</h4>
				</div>
				<div class="inner_content">
					<div class="inner_pdng">
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
								<span>Created by <b> <?php echo get_user_detail($_SESSION['user_id'],'first_name'); ?> <?php echo get_user_detail($_SESSION['user_id'],'last_name'); ?></b></span>
								<button name="submit" class="custom" type="submit">Create Job</button>
								<img src="assets/images/loader.gif" id="cust_job_loader"style="display:none;" />
							</div> 
						</div>
					</form>
					<div class="result_job"></div>
					</div>
				</div>
			</div>
			
			<div class="box assign_invoice_box " style="display:none;"></div>
			
			<div class="close_mn display_block">
                <div class="box add_cust_box" style="display:none;">
					<div class="new_custmer">
					<a class="close_new" onclick="jQuery('.add_cust_box').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
					<h4 class="close_title">NEW CUSTOMER</h4>
					</div>
					<div class="close_container custmr_edt_cnt">
					<form name="add_customer" id="add_customer" action="" method="post"> 
						<input type="hidden" name="action" value="AddCustomer">	
					
						<div class="cstm-wid top_inpt">
						<ul class="cmpny_nm">
								<li>
									<input type="text" name="company" placeholder="Company">
								</li> 
								
							</ul>
							<ul>
								<li>
									<input type="text" name="fname" placeholder="First Name">
								</li>
								<li>
									<input type="text" name="lname" placeholder="Last Name">
								</li>
								<li>
									<input type="text" name="email" placeholder="Email address">
								</li>
							</ul>
						</div>

						<div class="cstm-wid btm_inpt">
							<div class="col-1">
								<h4></h4>
								<ul>

									<li>
										<input type="text" name="work_phone" placeholder="Work Phone No">
									</li>
									<li>
										<input type="text" name="home_phone" placeholder="Home Phone No">
									</li>
									<li>
										<input type="text" name="mobile_phone" placeholder="Mobile Phone No">
									</li>
									<li>
										<textarea name="note" placeholder="Notes"></textarea>
									</li>

								</ul>
							</div>
							<div class="col-1">
								<h4> Work</h4>
								<ul>
									<!--
									<li>
										<input type="text" placeholder="First Name" name="work_name">
									</li>
									-->
									<li>
										<input type="text" placeholder="Address" name="work_add1">
									</li>
									
									<li>
										<input type="text" placeholder="Address 2" name="work_add2"> 
									</li>
									<li>
										<input type="text" placeholder="City" name="work_city">
									</li>
									<li>
										<input type="text" placeholder="Province" name="work_prov">
									</li>
									<li>
										<input type="text" placeholder="Postal Code" name="work_post_code">
									</li>
								</ul>
							</div>
							<div class="col-1">
								<h4> Home</h4>
								<ul>
									<!--
									<li>
										<input type="text" placeholder="First Name" name="home_name">
									</li>
									-->
									<li>
										<input type="text" placeholder="Address" name="home_add1">
									</li>
									
									<li>
										<input type="text" placeholder="Address 2" name="home_add2"> 
									</li>
									<li>
										<input type="text" placeholder="City" name="home_city">
									</li>
									<li>
										<input type="text" placeholder="Province" name="home_prov">
									</li>
									<li>
										<input type="text" placeholder="Postal Code" name="home_post_code">
									</li>
								</ul>
							</div>
							
						</div>
						<div class="btn_deit">
							
							<button type="submit" class="custom edit_cstm" name="submit">Save</button>
							<img id="cust_loader" src="assets/images/loader.gif" style="display:none;" />
						</div>
					</form>
					<div class="result"></div>
					</div>
                </div>
				
            </div>

            <div class="dshbrd display_block">
                <div class="close_mn display_block">
                    <h4 class="close_title">Invoices to Assign</h4>
					
					<?php
					//$sql="select * from invoices where cust_job='0' and status='1' and subject not like '%statement%' order by cr_date desc"; 
					$sql="select * from invoices where cust_job='0' and status='1' order by cr_date desc"; 
					$res=mysql_query($sql);
					$count=mysql_num_rows($res);
					if($count > 0)
					{	
					?>
					<table class="table">
                        <thead>
                            <tr>
                                <th style="width: 34px;"></th>
                                <th style="width: 144px;">Date Added</th>
                                <th style="width: 156px;">Vendor</th>
                                <th style="width: 186px;">Invoice</th>
                                <th style="text-align: center; width: 173px;"></th>
                                <th style="width:107px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							while($row=mysql_fetch_array($res))
							{	
								$attachment=$row['attachment'];
								$in_type=$row['in_type'];
								$subject=$row['subject'];
								if($in_type==0)
								{
									$asd="Automatic";
								}	
								else
								{
									$asd="Manual";
								}	
								
								$length=strlen($subject);
								
								if($length > 30 ) {
								$asd="...";	
								}
								else
								{
									$asd='';	
								}	
								
								
							?>
							<tr class="row_<?php echo $row['id']; ?>"> 
                                <td></td>
                                <td><?php echo date('M d, Y', strtotime($row['cr_date'])); ?></td>
                                <td><?php echo stripslashes(get_vendor_by_email($row['user_from'],'name')); ?></td>
                                <td><?php echo substr($subject,0,30).$asd; //echo $asd; ?></td>
                                <td class="text-center"><a class="custom" style="text-align:center;" href="<?php echo SITE_URL; ?>attachment/<?php echo $attachment; ?>" target="_blank">View</a></td>
                                <td><a class="custom assign_<?php echo $row['id']; ?>" href="javascript:void(0);" onclick="show_modal(<?php echo $row['id']; ?>);">Assign</a></td> 
                            </tr>
                            <?php
							}
							?>


                        </tbody>
                    </table>
					<?php
					}
					else
					{	
					?>
					<div class="alert alert-danger">No record found</div>
					<?php
					}
					?>
                </div>


                
            </div>


        </div>
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Customer Jobs</h4>
		  </div>
		  <div class="modal-body">
			
		  </div>
		  
		</div>
	  </div>
	</div>		
		
<script>	
jQuery(function() {
	
	jQuery('#example').DataTable();
	
   jQuery( ".datepicker" ).datepicker({
        changeMonth: false,
        changeYear: false,
		
    });
	
	jQuery('#example_info').parent().addClass('main-page-1');
	jQuery('#example_paginate').parent().addClass('main-page-2');
	
	
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

function show_hide(e)
{
	jQuery('.box').hide();
	if(e==1)
	{
		jQuery('.add_cust_box').slideDown();
	}	
}

function show_modal(invoice_id)
{
	jQuery('.custom').removeClass('active');
	jQuery('.assign_'+invoice_id).addClass('active'); 
	jQuery("html, body").delay(2000).animate({
        scrollTop: jQuery('.rt_sidebar').offset().top 
    }, 2000); 
	var loader='<center><img src="assets/images/loader.gif"/></center>';
	jQuery('.box').slideUp();
	jQuery('.assign_invoice_box').slideDown(); 
	jQuery('.assign_invoice_box').empty().append(loader);
	jQuery.ajax({type: "POST",
	url: "handler.php",
	data: "id="+invoice_id+"&action=ShowAssignInvoice",    
	success:function(result) 
	{
		jQuery('.assign_invoice_box').empty().append(result); 
	},
	error:function(e){ 
		console.log(e); 
	}	
	}); 
}

function get_customer()
{
	var loader='<li><div class="list-load"><img src="assets/images/loader.gif"/></div><li>';
	jQuery('.filter').show();
	jQuery('.filter').empty().append(loader); 
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
	jQuery('.filter').empty().hide();
}
</script>		
<?php get_footer(); ?>		