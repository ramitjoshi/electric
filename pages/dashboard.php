<?php 
if(!isset($_SESSION['user_id']))
{
?>
<script>window.location.href="<?php echo SITE_URL; ?>?section=login";</script>
<?php
die;
}
get_header(); 

$user_role=get_user_detail($_SESSION['user_id'],'role');  
?>
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
.red{
	color:red !important;
}
.green{
	color:green !important;
}
</style>
		<?php get_sidebar(); ?>
		
        <div class="rt_sidebar pull-left">
            <div class="btns_top display_block">
                <div class="btns_lft pull-left without_search">
                   
                    <a href="javascript:void(0);" onclick="jQuery('.box').slideUp();jQuery('.creat-job-cont').slideDown();" class="custom">New Job</a>
                    <a href="javascript:void(0);" class="custom" onclick="show_hide('1');">New Customer</a>
                </div>
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
					<form name="add_job" id="add_job" action="" method="post" class="new_change">
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
							<input class="frst-ip" type="text" placeholder="Name the Job" name="job_name" style=""> 
							<input class="" type="text" placeholder="Budgeted Hours" name="budget_hours" style="width:16%">  

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
								
								$sub=substr($subject,0,30).$asd;
								$sub=str_replace('=?UTF-8?Q?36291','',$sub);
								
								
							?>
							<tr class="row_<?php echo $row['id']; ?>"> 
                                <td></td>
                                <td><?php echo date('M d, Y', strtotime($row['cr_date'])); ?></td>
                                <td><?php echo stripslashes(get_vendor_by_email($row['user_from'],'name')); ?></td>
                                <td><?php echo $sub; //echo $asd; ?></td>
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
				

                <div class="close_mn display_block">
                    <h4 class="close_title">Open Jobs</h4>
                    <?php
					$job_open="select * from customer_job where status='1' ORDER BY id DESC";
					$res_job_open=mysql_query($job_open);
					$count_opn_job=mysql_num_rows($res_job_open);
					if($count_opn_job > 0)
					{	
				
					?>
					<table class="table">
                        <thead>
                         <tr>
                                <th style="width: 34px;"></th>
                                <th style="width: 144px;">Date Added</th>
                                <th style="width: 333px;">Job Name</th>
                                <th style="width: 140px;">Created By</th>
                                <th style="text-align: center; width: 221px;">Last Updated</th>
                                <th style=" width: 221px;text-align:center;">Hours Remaining </th>
                                <th style="width:107px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$today=date('Y-m-d');
							while($row_opn=mysql_fetch_array($res_job_open))
							{	
									$date1 = date("Y-m-d");
								    $date2 = $row_opn['to_date'];
									if($date1 < $date2){
										if($date1 > $date2){
											$days_left=0;
										}
										else{	
											$date1Timestamp = strtotime($date1);
											$date2Timestamp = strtotime($date2);
											$difference = $date2Timestamp - $date1Timestamp;
											$days_left= floor($difference/86400);						
										}
									}else{
										$days_left='0';
									}
							$last_up=job_timecard($row_opn['id']);		
							?>
							<tr>
                                <td></td>
                                <td><?php echo date('M d, Y', strtotime($row_opn['cr_date'])); ?></td>
                                <td><?php echo stripslashes($row_opn['name']); ?></td>
                                <td><?php echo stripslashes(get_user_detail($row_opn['job_insert_by'],'first_name')); ?> <?php echo stripslashes(get_user_detail($row_opn['job_insert_by'],'last_name')); ?></td>
                                
								<!--<td class="text-center"><?php echo $days_left; ?></td>-->
								<td class="text-center">
								<?php 
								if($last_up!="")
								{
									 echo time_elapsed_string($last_up);
								}	
								else
								{
								?>
								<span style="color:#1e1a1b;"><b>No Timecard</b></span> 
								<?php
								}	
								?></td>
								
                                 <td class="text-center"><?php echo job_hour_remaining($row_opn['id']); ?></td> 
								<td><a class="custom" href="<?php echo SITE_URL; ?>?section=customer-job&id=<?php echo $row_opn['id']; ?>">View</a></td>
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

                <div class="close_mn display_block" id="closed">
					<div class="pagination_cont display_block">
                    <h4 class="close_title pull-left">Closed Jobs</h4> 
					<div class="pagination_new pull-right">
					
                    <?php
					
					$user_id=$_SESSION['user_id'];
					$user_role=get_user_detail($user_id,'role');
					
					
					$job_close_tot="select * from customer_job where status='0'"; 
					$res_job_close_tot=mysql_query($job_close_tot);
					$count_close_job_tot=mysql_num_rows($res_job_close_tot);
					
					$count_status=ceil($count_close_job_tot/10);
					$count_statuss=$count_status*10; 
					?>
					<div class="pag_list pull-right">
					<ul class="close_page">
						
						<?php
						/*
						if(isset($_GET['page']))
						{
							
						?>
						<li>
							<a href="<?php echo '?section=dashboard'; ?>#closed">1</a>
							
						</li>
						<?php
						}
						*/
						for($i=10;$i<=$count_statuss;$i=$i+10)
						{
							if($count_close_job_tot > $i)
							{	
						?>
							<li>
								<a href="<?php echo '?section=dashboard&page=' . $i; ?>#closed" class="<?php if($i==$_GET['page']) { echo 'active';} ?>" ><?php echo $i;?></a>  
								
							</li>
						<?php
							}
						}
						?>
					</ul>
					</div>
					</div>
					</div>
					<?php
					if(isset($_GET['page']))
					{
						
						$job_close="select * from customer_job where status='0' order by DATE(closing_date) desc limit 10,".$_GET['page']."";  
					} 
					else 
					{		
						$job_close="select * from customer_job where status='0' order by DATE(closing_date) desc limit 0,10"; 
					}
					$res_job_close=mysql_query($job_close);
					$count_close_job=mysql_num_rows($res_job_close);
					if($count_close_job > 0)
					{	
					?>
					<table class="table cstm-mobile" id="example-1"> 
                        <thead>
                            <tr>
                                <th style="width: 34px;"></th>
                                <th style="width: 141px;">Date Closed</th>
                                <th style="width: 350px;">Job Name</th>
                                <th style="width: 146px;">Created By</th>
                                <?php
								if($user_role=="admin")
								{	
								?>
								<th style="text-align: center; width: 221px;">Invoiced</th> 
                                <?php
								}
								else
								{	
								?>
								<th style="text-align: center; width: 221px;">&nbsp;</th> 
								<?php
								}
								?>
								
								<th style="text-align: center; width: 221px;">File</th> 
								<th style="width:107px;"></th>
                            </tr>
							
							
							
							
                        </thead>
                        <tbody>
                            <?php
							$today=date('Y-m-d');
							while($row_close=mysql_fetch_array($res_job_close))
							{	
								
								$date1 = date_create($today);
								$date2 = date_create($row_close['to_date']);
								$interval = date_diff($date1, $date2);
								$countt=get_closed_job_dashboard($row_close['id']);
								$invoice_number=$row_close['invoice_number'];
																
							?>
							<tr class="closed_row_<?php echo $row_close['id']; ?>" <?php if($countt!=0) { echo 'style="display:none;"'; } ?>> 
                                <td></td> 
                                <td><?php echo date('M d, Y', strtotime($row_close['closing_date'])); ?></td>
                                <td><?php echo stripslashes($row_close['name']); ?></td>
                                <td><?php echo stripslashes(get_user_detail($row_close['job_insert_by'],'first_name')); ?> <?php echo stripslashes(get_user_detail($row_close['job_insert_by'],'last_name')); ?></td>
                                <?php
								if($user_role=="admin")
								{	
									$countt=get_invoiced_job($row_close['id']);
									
									?>
									<td class="text-center">
									<span class="input-table cl_job_<?php echo $row_close['id']; ?>">
									<?php 
									if($countt > 0)
									{
									?>
										<a href="javascript:void(0)" class="invoiced-no" onclick="cl_invoiced('0','<?php echo $row_close['id']; ?>');"><i class="fa blue fa-check-circle-o"></i></a>
									<?php 
									}
									else 
									{
									?>
										<a href="javascript:void(0)" class="invoiced-no black" onclick="cl_invoiced('1','<?php echo $row_close['id']; ?>');"><i class="fa blue fa-circle-o"></i></a> 
									<?php
									}
									?>
									</span>
									<?php 
									if($countt > 0)
									{
									?>
									<span class="text-input close_in_<?php echo $row_close['id']; ?>">
										<?php echo $invoice_number; ?> 
									</span> 
									<?php
									}
									else
									{	
									?>
									<span class="table-input close_in_<?php echo $row_close['id']; ?>">
										<input type="text" class="asd-d" maxlength="8" placeholder="Invoice#" value="<?php echo $invoice_number; ?>"> 
									</span> 
									<?php
									}
									?>
									</td> 
									<?php
								}
								else
								{	
								?>
								<td class="text-center">&nbsp;</td> 
								<?php
								}
								?>
								
								<td class="text-center skip_<?php echo $row_close['id']; ?>">
									<?php
									$countt=get_closed_job_dashboard($row_close['id']);
									if($countt==0)
									{	
									?>
									<a class="custom" href="javascript:void(0)" class="invoiced-no" onclick="cl_invoicedd('0','<?php echo $row_close['id']; ?>');">File</a>  	 	
									<?php 
									}  
									else
									{	
									?>
									<a href="javascript:void(0)" class="invoiced-no red" onclick="cl_invoicedd('1','<?php echo $row_close['id']; ?>');"><i class="fa blue fa-check-circle-o"></i></a>   
									<?php 
									} 
									?>
									
								</td>
								<td><a class="custom" href="<?php echo SITE_URL; ?>?section=customer-job&id=<?php echo $row_close['id']; ?>">View</a></td>
                            </tr> 
                            <?php
								//}
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
		minDate:0
		
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
			},
			budget_hours: {
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


function cl_invoiced(e,job_id)
{
	if(e==1)
	{
		var invoice=jQuery('.close_in_'+job_id).find('.asd-d').val();
		if(invoice=="")
		{
			alert('Enter Invoice Number');
			return false; 
		}	
		var loader='<img src="assets/images/loader.gif" />';
		jQuery('.cl_job_'+job_id).empty().append(loader);
		
		jQuery.ajax({type: "POST",
		url: "handler.php",
		data: "invoice="+invoice+"&e="+e+"&job_id="+job_id+"&action=AddClosedInvoiced",  
		success:function(result)   
		{
			jQuery('.cl_job_'+job_id).empty().append('<a href="javascript:void(0)" class="invoiced-no" onclick="cl_invoiced(0,'+job_id+');"><i class="fa blue fa-check-circle-o"></i></a>');    
			jQuery('.close_in_'+job_id).empty().addClass('input-span').append(invoice);    
			
		}			
		});  
		/*
		jQuery('#myModal').show();
		var job_name=jQuery('.closed_row_'+job_id).find('td:eq(2)').html(); 
		jQuery('#myModal').modal();
		jQuery('#myModal').find('.modal-title').empty().append(job_name);  
		var loader='<img src="assets/images/loader.gif" />';
		jQuery('#myModal').find('.modal-body').empty().append(loader); 
		jQuery.ajax({type: "POST",
		url: "handler_new.php",
		data: "e="+e+"&job_id="+job_id+"&action=ShowInvoiceForm", 
		success:function(result) 
		{
			jQuery('#myModal').find('.modal-body').empty().append(result); 
		},
		error:function(e){
			console.log(e);
		}	
		}); 	
		*/	
	}	
	else
	{
		var invoice=jQuery('.close_in_'+job_id).html();
		var invoice=jQuery.trim(invoice); 
		var loader='<img src="assets/images/loader.gif" />';
		jQuery('.cl_job_'+job_id).empty().append(loader); 
		
		jQuery.ajax({type: "POST", 
		url: "handler.php",
		data: "e="+e+"&job_id="+job_id+"&action=AddClosedInvoiced", 
		success:function(result)   
		{
			jQuery('.cl_job_'+job_id).empty().append('<a href="javascript:void(0)" class="invoiced-no black" onclick="cl_invoiced(1,'+job_id+');"><i class="fa blue fa-circle-o"></i></a>');    
			jQuery('.close_in_'+job_id).empty().addClass('table-input').append('<input class="asd-d" maxlength="8" type="text" placeholder="Invoice#" value="'+invoice+'">');    
			 
		}			
		});  
		
	}		
	
	
}


function cl_invoicedd(e,job_id)
{ 
		if(confirm("Would you like to hide this job from the Closed Jobs list?")){
		
		var loader='<img src="assets/images/loader.gif" />';
		jQuery('.skip_'+job_id).empty().append(loader); 
		
		jQuery.ajax({type: "POST",  
		url: "handler.php",
		data: "e="+e+"&job_id="+job_id+"&action=AddClosedInvoicedd",  
		success:function(result) 
		{ 
			if(result==1)
			{
				jQuery('.closed_row_'+job_id).fadeOut(3000);
				jQuery('.closed_row_'+job_id).remove();  
				/*
				if(e==0)
				{
					jQuery('.skip_'+job_id).empty().append('<a href="javascript:void(0)" class="invoiced-no red" onclick="cl_invoicedd(1,'+job_id+');"><i class="fa blue fa-check-circle-o"></i></a>'); 
				} 
				if(e==1)
				{
					jQuery('.skip_'+job_id).empty().append('<a class="custom" href="javascript:void(0)" class="invoiced-no" onclick="cl_invoicedd(0,'+job_id+');">File</a>');   
				}
				*/	
			} 	  
			
		},
		error:function(e){
			console.log(e);
		}	
		}); 
	}	
}

</script>		
<?php get_footer(); ?>		