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
						<a href="javascript:void(0);" class="custom" onclick="jQuery('.box').slideUp();show_hide('1');">New Vehicle</a> 
                    <?php 
					}
					?>
					<a onclick="jQuery('.box').slideUp();jQuery('#fromToggle').slideDown();" class="custom" href="javascript:void(0);">New Record</a>
                </div>
                <div class="btns_rt pull-right">
                    
                </div>
            </div>
            <div class="close_mn display_block">
				<div id="fromToggle" class="collapsible-area box" style="display:none">	
					 <div class="new_custmer">
						<a href="javascript:void(0);" onclick="jQuery('#fromToggle').slideUp();" class="close_new"><i class="fa fa-times" aria-hidden="true"></i></a>
						<h4 class="close_title">ADD MILEAGE</h4></div>
						<div class="close_container custmr_edt_cnt">
							<form name="add_mileage" id="add_mileage" action="" method="post" class="new_cstmr add-invoice">
								<div class="cstm-wid top_inpt asign-left"> 
									<ul>  
										
										<li>
											<select name="mil_veh_id">
											<option value="">Select Vehicle</option>
											<?php
											$sql="select * from vehicle where status='1' order by id desc";
											$res=mysql_query($sql);
											while($row=mysql_fetch_array($res))
											{	
											?> 
												<option value="<?php echo $row['id']; ?>"><?php echo $row['vehicle_num']; ?></option>
											<?php
											}
											?>
											</select>
										</li>
										<li>
											<input type="text" name="mil_descp" placeholder="Description">
										</li>
										<li>
											<input type="text" name="mil_km" placeholder="KM's">
										</li>
										
									</ul>
								</div>
								<div class="asign-rt">
								
								<div class="btn_deit">
									<button type="submit" class="custom edit_cstm">Save</button>
									<img src="assets/images/loader.gif" id="man_invoice_loader" style="display:none;" />
								</div> 
								</div> 
								
								<input type="hidden" name="action" value="AddMilege">
							</form>
							<div class="result"></div> 
						</div>
					</div>
                <div class="box add_cust_box" style="display:none;">
					<div class="new_custmer">
					<a class="close_new" onclick="jQuery('.add_cust_box').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
					<h4 class="close_title">NEW VEHICLE</h4>
					</div>
					<div class="close_container custmr_edt_cnt">
					<form name="add_vehicle" id="add_vehicle" action="" method="post"> 
						<input type="hidden" name="action" value="AddVehicle">	
						<div class="cstm-wid top_inpt">  
							<ul>
								<li>
									<input class="numbersOnly" type="text" name="vehicle_num" placeholder="Vehicle #" maxlength="8">
								</li>
								<li>
									<input class="numbersOnly" type="text" name="year" placeholder="Year" maxlength="4">
								</li>
								<li>
									<input type="text" name="make" placeholder="Make" maxlength="15">
								</li>
								<li>
									<input type="text" name="model" placeholder="Model"> 
								</li>
								<li>
									<input type="text" name="km" placeholder="KMs (start of year)">
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
                                <th style="width: 196px;">Vehicle #</th>
                                <th style="width: 376px;">Year</th>
                                <th style="width: 376px;">Make</th>
                                <th style="width: 376px;">Model</th>
                                <th style="width: 245px;" class="<?php if($user_role=="staff") { echo 'telephone'; } ?>">KMs (start of year)</th>
                                <th style="text-align: center; width: 101px;"></th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$sql="select * from vehicle";
							$res=mysql_query($sql);
							while($row=mysql_fetch_array($res))
							{
							$status=$row['status'];			
							$cls_fr="";
							if($status==0)
							{
								$cls_fr="cst_red_fr";
							}	
							?>
							<tr class="<?php echo $cls_fr; ?>">
                                <td>&nbsp;</td>
                                <td><?php echo $row['vehicle_num']; ?></td>
                                <td><?php echo $row['year']; ?></td>
                                <td><?php echo $row['make']; ?></td>
                                <td><?php echo $row['model']; ?></td>
                                <td><?php echo $row['km']; ?></td>
                                <td style="text-align: right;padding-right: 7px;">
								<?php
								if($user_role=="admin") 
								{
								?>
								<a href="javascript:void(0);" class="custom edit_cstm" onclick="editvehicleForm('<?php echo $row['id']; ?>')">Edit</a>
								<?php
								} 
								?>
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



</script>			
<script src="<?php echo SITE_URL; ?>assets/js/vehicle.js"></script>	
<?php get_footer(); ?>		