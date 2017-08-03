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
		
        <div class="rt_sidebar pull-left">
            <div class="btns_top display_block">
                <div class="btns_lft pull-left">
                    <a href="<?php echo SITE_URL; ?>?section=dashboard" class="custom">Dashboard</a>
                    <a href="javascript:void(0);" class="custom" onclick="show_hide('1');">New Customer</a>
                </div>
                
				<div class="btns_rt pull-right">
                   
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
                    
                     <?php
					$job_close="select * from customer_job where status='0'";
					$res_job_close=mysql_query($job_close);
					$count_close_job=mysql_num_rows($res_job_close);
					if($count_close_job > 0)
					{	
					?>
					<table class="table" id="example">
                        <thead>
                            <tr>
                                <th style="width: 34px;"></th>
                                <th style="width: 206px;">Date Added</th>
                                <th style="width: 523px;">Job Name</th>
                                <th style="width: 121px;">Created By</th>
                                <th style="text-align: center; width: 221px;">Days Left</th>
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
								
							?>
							<tr>
                                <td></td>
                                <td><?php echo date('M d, Y', strtotime($row_close['cr_date'])); ?></td>
                                <td><?php echo stripslashes($row_close['name']); ?></td>
                                <td><?php echo stripslashes(get_user_detail($row_close['job_insert_by'],'first_name')); ?> <?php echo stripslashes(get_user_detail($row_close['job_insert_by'],'last_name')); ?></td>
                                <td class="text-center"><?php echo $interval->d; ?></td>
                                <td><a class="custom" href="<?php echo SITE_URL; ?>?section=customer-job&id=<?php echo $row_close['id']; ?>">View</a></td>
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
	
	function explode(){
		jQuery("#example_filter").detach().appendTo('.btns_rt');
	}

	
	jQuery(document).ready(function() {
		setTimeout(explode, 500);
		
		
		table=jQuery('#example').DataTable({
		responsive: true,
		"oLanguage": { "sSearch": "" } ,
		});		
				
		jQuery('div.dataTables_filter input').attr('placeholder', 'Search...');	
		jQuery("#example").wrap("<div class='responsive-table'></div>");
		
		
		
		
	});
	
	function show_modal(invoice_id)
	{
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
	
	</script>		
<?php get_footer(); ?>		