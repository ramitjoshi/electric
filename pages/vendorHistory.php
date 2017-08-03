		<?php get_header(); ?>
		<?php get_sidebar(); ?>
		<?php $vendor_id=$_GET['id']; ?>
		<?php 
		$user_role=get_user_detail($_SESSION['user_id'],'role');  
		?>
        <div class="rt_sidebar pull-left">
            <div class="btns_top display_block">
                <div class="btns_lft pull-left">
                    <a href="<?php echo SITE_URL; ?>?section=dashboard" class="custom">Dashboard</a>
                    <a onclick="show_hide('1');" class="custom" href="javascript:void(0);">New Vendor</a>
                </div> 
                <!--
				<div class="btns_rt pull-right">
                    <input type="text" placeholder="Search">
                    <a href=""><i class="fa fa-search" aria-hidden="true"></i></a>
                </div>
				-->
            </div>
			<div class="box assign_invoice_box box " style="display:none;"></div>
            
			<div class="close_mn historical-invoides display_block">
				<div class="box add_cust_box" style="display:none;">
				<div class="new_custmer">
					<a class="close_new" onclick="jQuery('.add_cust_box').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
					<h4 class="close_title">NEW VENDOR</h4>
					</div>
					<div class="close_container custmr_edt_cnt">
					<form name="add_vendor" id="add_vendor" action="" method="post"> 
						<input type="hidden" name="action" value="AddVendor">	
					
						<div class="cstm-wid top_inpt"> 
							
							<ul>
								<li>
									<input type="text" name="name" placeholder="Name">
								</li>
								
								<li>
									<input type="text" name="email" placeholder="Email address">
								</li>
								<li>
									<input type="text" name="phone" placeholder="Phone No">
								</li>
							</ul>
						</div>

						<div class="cstm-wid top_inpt"> 
							
							<ul>
								<li>
									<input type="text" name="address" placeholder="Address">
								</li>
								
								<li>
									<input type="text" name="city" placeholder="City">
								</li>
								
							</ul>
						</div>
						<div class="cstm-wid top_inpt"> 	
							<ul>
								<li>
									<input type="text" name="prov" placeholder="Province">
								</li>
								<li>
									<input type="text" name="post_code" placeholder="Postal Code">
								</li>
							</ul>
						</div>
						<div class="btn_deit">
							
							<button type="submit" class="custom edit_cstm" name="submit">Save</button>
							<img id="cust_loader" src="assets/images/loader.gif" style="display:none;" />
						</div>
					</form>
					<div class="result"></div>
					</div>
                </div>
				<div class="box add_cust_box" style="display:none;">
				<div class="new_custmer">
					<a class="close_new" onclick="jQuery('.add_cust_box').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
					<h4 class="close_title">NEW VENDOR</h4>
					</div>
					<div class="close_container custmr_edt_cnt">
					<form name="add_vendor" id="add_vendor" action="" method="post"> 
						<input type="hidden" name="action" value="AddVendor">	
					
						<div class="cstm-wid top_inpt"> 
							
							<ul>
								<li>
									<input type="text" name="name" placeholder="Name">
								</li>
								
								<li>
									<input type="text" name="email" placeholder="Email address">
								</li>
								<li>
									<input type="text" name="phone" placeholder="Phone No">
								</li>
							</ul>
						</div>

						<div class="cstm-wid top_inpt"> 
							
							<ul>
								<li>
									<input type="text" name="address" placeholder="Address">
								</li>
								
								<li>
									<input type="text" name="city" placeholder="City">
								</li>
								
							</ul>
						</div>
						<div class="cstm-wid top_inpt"> 	
							<ul>
								<li>
									<input type="text" name="prov" placeholder="Province">
								</li>
								<li>
									<input type="text" name="post_code" placeholder="Postal Code">
								</li>
							</ul>
						</div>
						<div class="btn_deit">
							
							<button type="submit" class="custom edit_cstm" name="submit">Save</button>
							<img id="cust_loader" src="assets/images/loader.gif" style="display:none;" />
						</div>
					</form>
					<div class="result"></div>
					</div>
                </div>
                <div class="edit_table vndr_cnt">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td colspan="5">
                                    <table class="table brdr">
                                        <tbody>
											
                                            <tr>
                                                <td style="width: 3%;"></td>
                                                <td style="width: 29%;"><?php echo get_vendor_info($vendor_id,'name'); ?></td>
                                                <td style="width: 29%;"><?php echo get_vendor_info($vendor_id,'email'); ?></td>
                                                <td style="width: 20%;"><?php echo get_vendor_info($vendor_id,'phone'); ?></td>
                                                <td style="width: 19%; text-align: right;" class="alignmnt">&nbsp;</td>

                                            </tr>

                                            <tr class="vertcal_top">
                                                <td></td>
                                                <td>
                                                    <strong>Telephone</strong>
                                                    <span><?php echo get_vendor_info($vendor_id,'phone'); ?></span>
                                                </td>
                                                <td>
                                                    <strong>Adress</strong>
                                                    <span><?php echo get_vendor_info($vendor_id,'address'); ?> <br> <?php echo get_vendor_info($vendor_id,'city'); ?> <?php echo get_vendor_info($vendor_id,'prov'); ?> <br> <?php echo get_vendor_info($vendor_id,'post_code'); ?></span></td>
                                              
                                                <td></td>

                                            </tr>
											
                                        </tbody>

                                    </table>

                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
                <div class="historical-invoides display_block">
                    <h4 class="close_title">INVOICES</h4>
                    <?php
					$sql="select * from invoices where vendor_id='".$vendor_id."' ORDER BY id DESC";
					$res=mysql_query($sql);
					$count=mysql_num_rows($res);
					if($count > 0)
					{	
					?>
					<table class="table">
                        <thead>

                          <tr>
                                <th style="width: 3%;"></th>
                                <th style="width: 13%;">Date</th>
                                <th style="width: 29%;">Invoice</th>
                                <th style="width: 18%;">Job Name</th>
                                <th style="width: 1%;"></th>
                                <th style="text-align: center; width: 9%;"></th>
                                <th style="text-align: center; width: 9%;"></th>
                                <th style="text-align: center; width: 9%;"></th>
                            </tr>

                        </thead>
                        <tbody>
							<?php 
							while($row=mysql_fetch_array($res))
							{	
								$invoice_id=$row['id'];
								$status=$row['status']; 
								$count=invoice_assigned_exist($invoice_id);
								if($count!=0)
								{	
									
							?>
                            <tr class="asd_<?php echo $invoice_id; ?>">
                                <td></td>
                                <td><?php echo date('M d, Y', strtotime($row['cr_date'])); ?></td>
                                <td><?php echo $row['subject']; ?></td>
                                <td>
								<?php 
								if($count==0) 
								{ 
									echo "xxx"; 
								} 
								else 
								{ 
									$job_id=get_assign_invoices_info($invoice_id,'job_id');
									echo get_customer_job_info($job_id,'name');
								} 
								?></td>
                                <td></td>
                                <td class="text-center">
									<a href="javascript:void(0);" onclick="show_modal_1('<?php echo $invoice_id; ?>')" style="text-align:center;" class="custom">Re-Assign</a>	
								</td>
                                
								<td class="text-center">
								<?php
								if($user_role=="admin")
								{	
									if($count==0)
									{	
									?>
										<a href="javascript:void(0);" onclick="del_invoice('1','<?php echo $invoice_id; ?>')" style="text-align:center;" class="custom">Delete</a>
									<?php
									}
									else
									{
									?>
										<a href="javascript:void(0);" onclick="del_invoice('2','<?php echo $invoice_id; ?>')" style="text-align:center;" class="custom">Delete</a>
									<?php	
									}
								}	 
								?>	
								</td>
                                <td class="text-center"><a href="<?php echo SITE_URL; ?>attachment/<?php echo $row['attachment']; ?>" target="_blank" class="custom">View</a></td>
                            </tr>
                            <?php
							}
							}
							?>
                        </tbody>
                    </table>
					<?php
						
					}
					else
					{
					?>
					<div class="alert alert-danger">No invoices found</div>
					<?php	
					}	
					?>
                </div>
            </div>
		</div>
<script>
function del_invoice(e,id)
{
	if(confirm("Are you sure you want to delete this invoice?"))
	{
		jQuery.ajax({type: "POST", 
		url: "handler.php",
		data: "id="+id+"&e="+e+"&action=DelInvoice", 
		success:function(result){
			if(result==1)
			{
				jQuery('.asd_'+id).fadeOut(1000); 
				location.reload();
			}	
		},
		error:function(e){
			console.log(e); 
		}	
		}); 
	}	
}

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
		jQuery("html, body").animate({
				scrollTop: jQuery('.rt_sidebar').offset().top 
		}, 1000); 
	},
	error:function(e){ 
		console.log(e); 
	}	
	}); 
}

function show_modal_1(invoice_id)
{
	var loader='<center><img src="assets/images/loader.gif"/></center>';
	jQuery('.box').slideUp();
	jQuery('.assign_invoice_box').slideDown(); 
	jQuery('.assign_invoice_box').empty().append(loader);
	jQuery.ajax({type: "POST",
	url: "handler.php",
	data: "id="+invoice_id+"&action=ShowAssignInvoice_1",    
	success:function(result) 
	{
		jQuery('.assign_invoice_box').empty().append(result); 
		jQuery("html, body").animate({
				scrollTop: jQuery('.rt_sidebar').offset().top 
		}, 1000); 
	},
	error:function(e){ 
		console.log(e); 
	}	
	}); 
}

function show_hide(e)
{
	jQuery('.box').hide();
	if(e==1)
	{
		jQuery('.add_cust_box').slideDown();
	}	
}



</script>		
<?php get_footer(); ?>		