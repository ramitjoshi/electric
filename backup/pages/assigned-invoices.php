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
        <div class="rt_sidebar pull-left cstmr_rt">
            <div class="btns_top display_block">
                <div class="btns_lft pull-left">
                    <a href="<?php echo SITE_URL; ?>?section=dashboard" class="custom">Dashboard</a>
                    <!--
					<a href="javascript:void(0);" class="custom" onclick="show_hide('1');">New Customer</a>
                    <a href="<?php echo SITE_URL; ?>?section=job" class="custom">New Job</a>
					-->
                </div>
                <div class="btns_rt pull-right">
                    
                </div>
            </div>
            <div class="close_mn display_block">
                <div class="box add_cust_box" style="display:none;">
				<div class="new_custmer">
					<a class="close_new" onclick="jQuery('.add_cust_box').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
					<h4 class="close_title">ASSIGNED INVOICES</h4>
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
				<div class="box edit_cust_box" style="display:none;"></div>
				<div class="box show_cust_box" style="display:none;"></div>
                <div class="edit_table">
                    <table class="table" id="example">
                        <thead>
                            <tr>
                                <th style="width: 35px;"></th>
                                <th style="width: 196px;">Date Added</th>
                                <th style="width: 376px;">Vendor</th>
                                <th style="width: 245px;">Type</th>
                                <th style="text-align: center; width: 101px;"></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$sql="SELECT a . * , b.id,b.vendor_id 'vendor',b.attachment,b.in_type
							FROM assign_invoices AS a
							INNER JOIN invoices AS b ON a.invoice_id = b.id order by a.cr_date desc";
							$res=mysql_query($sql);
							while($row=mysql_fetch_array($res))
							{
								$invoice_name=get_vendor_info($row['vendor'],'name');	
								if($row['in_type']==0)
								{
									$asd="Automatic";
								}	
								else
								{
									$asd="Manual";
								}	
							?>
							<tr>
                                <td>&nbsp;</td>
                                <td><?php echo date('M d,Y', strtotime($row['cr_date'])); ?></td>
                                <td><?php echo $invoice_name; ?></td>
                                <td><?php echo $asd; ?></td>
                                <td style="text-align: right;padding-right: 12px;"><a href="javascript:void(0);" class="custom edit_cstm" onclick="get_cust_info('<?php echo $row['id']; ?>')">View</a></td>
								
                            </tr>  
							<?php
							}
							?>
						</tbody>
                    </table>

                </div>


            </div>


        </div>
		

		
		
<script>
function get_cust_info(id)
{
	jQuery('.box').hide();	
	var loader='<center><img src="assets/images/loader.gif" /></center>';
	
	
	jQuery.ajax({type: "POST",
	url: "handler.php",
	data: "id="+id+"&action=AssignedInvoicesShow", 
	success:function(result){
		jQuery("html, body").animate({
				scrollTop: jQuery('.rt_sidebar').offset().top 
			}, 1000); 
		jQuery('.show_cust_box').slideDown();
		jQuery('.show_cust_box').empty().append(result);
	},
	error:function(e){
		console.log(e);
	}	
	}); 
}	
function explode(){
		jQuery("#example_filter").detach().appendTo('.btns_rt');
	}

	
	jQuery(document).ready(function() {
		setTimeout(explode, 500);
		
		
		table=jQuery('#example').DataTable({
		responsive: true,
		paging: false,
		"oLanguage": { "sSearch": "" } ,
		});		
				
		jQuery('div.dataTables_filter input').attr('placeholder', 'Search...');	
		jQuery("#example").wrap("<div class='responsive-table'></div>");
		
		
		
		
	});

function show_hide(e)
{
	jQuery('.box').hide();
	if(e==1)
	{
		jQuery('.add_cust_box').slideDown();
	}	
}

function edit(id)
	{
		jQuery('.box').hide();
		jQuery('.add_cust_box').slideUp();
		jQuery('.edit_cust_box').slideDown(); 
		var loader='<center><img src="assets/images/loader.gif" /></center>';
		var id=id;		
		jQuery('.edit_cust_box').empty().append(loader);
		
		jQuery.ajax({type: "POST",
		url: "handler.php",
		data: "id="+id+"&action=customerEdit", 
		success:function(result){
			jQuery("html, body").animate({
				scrollTop: jQuery('.rt_sidebar').offset().top 
			}, 1000); 
			jQuery('.edit_cust_box').empty().append(result);
		},
		error:function(e){
			console.log(e);
		}	
		}); 
		
		
	}

</script>		
<?php get_footer(); ?>		