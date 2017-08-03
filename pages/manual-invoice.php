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
<style>
.bootstrap-filestyle .form-control {
	display:none;
}
</style>
        <div class="rt_sidebar pull-left">
            <div class="btns_top display_block">
                <div class="btns_lft pull-left">
                    <a href="<?php echo SITE_URL; ?>?section=dashboard" class="custom">Dashboard</a>
                    <a href="javascript:void(0);" class="custom" onclick="jQuery('.collapsible-area').hide();jQuery('#fromToggle').slideDown();">New Invoice</a>
                </div>
                <div class="btns_rt pull-right">
                    <!--
					<input type="text" placeholder="Search">
                    <a href=""><i class="fa fa-search" aria-hidden="true"></i></a>
					-->
                </div>
            </div>
			 <div class="box assign_invoice_box " style="display:none;"></div> 
			 <div id="fromToggle" class="collapsible-area" style="display:none">	
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
			<div id="editFromToggle" class="collapsible-area new_cstmr" style="display:none"></div>
			
            <div class="close_mn display_block">


                <div class="edit_table staff">
                    <?php
					$sql="select * from invoices where cust_job='0' and status='1' and in_type='1' and subject not like '%statement%' order by cr_date desc"; 
					$res=mysql_query($sql);
					$count=mysql_num_rows($res);
					if($count > 0)
					{	
					?>
					<table class="table" id="example">
                        <thead>
                            <tr>
                                <th style="width: 34px;"></th>
                                <th style="width: 206px;">Date Added</th>
                                <th style="width: 523px;">Vendor</th>
                                <th style="width: 121px;">Type</th>
                                <th style="text-align: center; width: 221px;"></th>
                                <th style="width:107px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							while($row=mysql_fetch_array($res))
							{	
								$attachment=$row['attachment'];
								$in_type=$row['in_type'];
								if($in_type==0)
								{
									$asd="Automatic";
								}	
								else
								{
									$asd="Manual";
								}	
							?>
							<tr class="row_<?php echo $row['id']; ?>"> 
                                <td></td>
                                <td><?php echo date('M d, Y', strtotime($row['cr_date'])); ?></td>
                                <td><?php echo stripslashes(get_vendor_by_email($row['user_from'],'name')); ?></td>
                                <td><?php echo $asd; ?></td>
                                <td class="text-center"><a class="custom" style="text-align:center;" href="<?php echo SITE_URL; ?>attachment/<?php echo $attachment; ?>" target="_blank">View</a></td>
                                <td><a class="custom" href="javascript:void(0);" onclick="show_modal(<?php echo $row['id']; ?>);">Assign</a></td> 
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
