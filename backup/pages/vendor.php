<?php 
if(!isset($_SESSION['user_id']))
{
?>
<script>window.location.href="<?php echo SITE_URL; ?>?section=login";</script>
<?php
die;
}
get_header(); ?>	
<?php 
$user_role=get_user_detail($_SESSION['user_id'],'role');  
?>
<style>
.bootstrap-filestyle .form-control {
	display:none;
}
</style>		
		<?php get_sidebar(); ?>
        <div class="rt_sidebar pull-left cstmr_rt">
            <div class="btns_top display_block">
                <div class="btns_lft pull-left">
                    <a href="<?php echo SITE_URL; ?>?section=dashboard" class="custom">Dashboard</a>
                    <?php
					if($user_role=="admin")
					{
					?>		
						<a href="javascript:void(0);" class="custom" onclick="jQuery('.box').slideUp();show_hide('1');">New Vendor</a> 
                    <?php
					}
					?>
					<a onclick="jQuery('.box').slideUp();jQuery('#fromToggle').slideDown();" class="custom" href="javascript:void(0);">New Invoice</a>
                </div>
                <div class="btns_rt pull-right">
                    
                </div>
            </div>
            <div class="close_mn display_block">
				<div id="fromToggle" class="collapsible-area box" style="display:none">	
					 <div class="new_custmer">
						<a href="javascript:void(0);" onclick="jQuery('#fromToggle').slideUp();" class="close_new"><i class="fa fa-times" aria-hidden="true"></i></a>
						<h4 class="close_title">ADD INVOICE</h4></div>
						<div class="close_container custmr_edt_cnt">
							<form name="add_man_invoice" id="add_man_invoice" action="" method="post" class="new_cstmr add-invoice">
								<div class="cstm-wid top_inpt asign-left"> 
									<ul> 
										<li>
											<input type="text" name="subject" placeholder="Title">
										</li>
										<li>
											<select name="vendor_id">
											<option value="">Select Vendor</option>
											<?php
											$sql="select * from vendor order by name asc";
											$res=mysql_query($sql);
											while($row=mysql_fetch_array($res))
											{	
											?> 
												<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
											<?php
											}
											?>
											</select>
										</li>
										
										
										
									</ul>
								</div>
								<div class="asign-rt">
								<div class="btn_deit ">
									<input type="file" name="attach" class="filestyle" data-icon="false" >
								</div> 
								<div class="btn_deit">
									<button type="submit" class="custom edit_cstm">Save</button>
									<img src="assets/images/loader.gif" id="man_invoice_loader" style="display:none;" />
								</div> 
								</div> 
								
								<input type="hidden" name="action" value="AddManualInvoice">
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
				<div class="box edit_cust_box" style="display:none;"></div>
				<div class="box show_cust_box" style="display:none;"></div>
                <div class="edit_table">
                    <table class="table <?php if($user_role=="staff") { echo 'cstm-mobile'; } ?>" id="example">
                        <thead>
                            <tr> 
                                <th style="width: 35px;"></th>
                                <th style="width: 196px;">Name</th>
                                <th style="width: 376px;">Email Address</th>
                                <th style="width: 245px;" class="<?php if($user_role=="staff") { echo 'telephone'; } ?>">Telephone</th>
                                <th style="text-align: center; width: 101px;"></th>
                                <th style="text-align: center; width: 101px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$sql="select * from vendor";
							$res=mysql_query($sql);
							while($row=mysql_fetch_array($res))
							{
										
							?>
							<tr>
                                <td>&nbsp;</td>
                                <td><?php echo $row['name']; ?></td>
                                <td><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
                                <td><?php echo $row['phone']; ?></td>
                                <td style="text-align: right;padding-right: 7px;">
								<?php
								if($user_role=="admin")
								{
								?>
								<a href="javascript:void(0);" class="custom edit_cstm" onclick="edit('<?php echo $row['id']; ?>')">Edit</a>
								<?php
								}
								?>
								</td>
								<td style="text-align: right;padding-right: 12px;">
									<!--<a href="javascript:void(0);" class="custom edit_cstm" onclick="get_vendor_info('<?php echo $row['id']; ?>')">View</a>-->
									<a href="<?php echo SITE_URL; ?>?section=vendor-history&id=<?php echo $row['id']; ?>" class="custom edit_cstm" >View</a> 
								</td>
								
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
function get_vendor_info(id)
{
	jQuery('.box').hide();	
	var loader='<center><img src="assets/images/loader.gif" /></center>';
	
	
	jQuery.ajax({type: "POST",
	url: "handler.php",
	data: "id="+id+"&action=vendorShow", 
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
		data: "id="+id+"&action=vendorEdit", 
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