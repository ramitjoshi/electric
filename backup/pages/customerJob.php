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
<?php $job_id=$_GET['id']; ?>
<?php 
$user_role=get_user_detail($user_id,'role');  
?>
<?php
$sql="select * from customer_job where id='$job_id'";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$id=$row['id'];
	$job_name=stripslashes($row['name']);
	$cust_id=$row['cust_id'];
	$po=$row['po'];
	$from_date=date('m/d/Y', strtotime($row['from_date']));
	$to_date=date('m/d/Y', strtotime($row['to_date'])); 
	$status=$row['status'];
	$job_insert_by=$row['job_insert_by']; 
	$budget_hours=$row['budget_hours']; 
	$invoice_number=$row['invoice_number']; 
	
}


$sql_1="select * from customer where id='".$cust_id."'";
$res_1=mysql_query($sql_1);
while($row_1=mysql_fetch_array($res_1))
{
	$first_name=$row_1['first_name'];
	$last_name=$row_1['last_name'];
	$company=$row_1['company'];
}		


$namee=$first_name.' '.$last_name;

$company=get_cust_detail($cust_id,'company');

if($company=="")
{
	$asdd=$first_name.' '.$last_name;
}
else
{
	$asdd=$company;
}	

?>
        <div class="rt_sidebar open-job pull-left">
            <div class="btns_top display_block"> 
                <div class="btns_lft pull-left without_search">
                    <a href="<?php echo SITE_URL; ?>?section=dashboard" class="custom">Dashboard</a>
                    <?php
					if($status==1)
					{	
					?>
					<a href="javascript:void(0);" class="custom" onclick="show_close_job('<?php echo $_GET['id']; ?>');">Close Job</a>  
                    <?php
					}
					else
					{	
					?>
					<a href="javascript:void(0);" class="custom" onclick="open_job('<?php echo $id; ?>','<?php echo $cust_id; ?>')">Open Job</a> 
					<!--<a href="javascript:void(0);" class="custom" onclick="close_job_info('<?php echo $id; ?>')">Close Job Info</a> -->
					<?php
					}
					?>
					<a href="javascript:void(0);" class="custom" onclick="jQuery('.box').slideUp();jQuery('.creat-job-cont').slideDown();">Edit Job</a>
					<?php
					if($user_role=="admin")
					{	
					?>	
						<a href="javascript:void(0);" onclick="show_report('<?php echo $job_id; ?>','<?php echo $user_id; ?>');" class="custom">Report</a>
                    <?php
					}
					?>
					
					<a href="javascript:void(0);" class="custom" onclick="jQuery('.box').slideUp();jQuery('.add_material').slideDown();">Add Material</a>
					
					<a href="javascript:void(0);" class="custom" onclick="show_new_timecard('<?php echo $job_id; ?>');">New Timecard</a> 
					
					<form name="ramit_1" id="ramit_1" action="<?php echo SITE_URL; ?>pdf/job-progress.php" target="_blank" style="display:none;" method="get">   
					
					<input type="hidden" id="job_id" name="job_id" value="<?php echo $job_id; ?>">
					<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
					<button type="submit">aaaa</button>
					</form>
					
                </div>
                <!--
				<div class="btns_rt pull-right">
                    <input type="text" placeholder="Search">
                    <a href=""><i class="fa fa-search" aria-hidden="true"></i></a>
                </div>
				-->
            </div>
			<div class="close-job-info display_block box" style="display:none;"></div>
			<div class="close-job-notee display_block box" style="display:none;"></div>
			<div style="display:none;" class="create-report display_block box"></div>
			<div class="creat-job-cont display_block box" style="display:none;">
				<div class="new_custmer">
				<a href="javascript:void(0);" onclick="jQuery('.creat-job-cont').slideUp();" class="close_new"><i class="fa fa-times" aria-hidden="true"></i></a>
				</div>
				<div class="inner_content">
					<div class="inner_pdng">
					<form method="post" action="" id="edit_job" name="edit_job">
						<input type="hidden" id="job_id" name="job_id" value="<?php echo $id; ?>">
						<input type="hidden" id="cust_id" name="cust_id" value="<?php echo $cust_id; ?>">
						<input type="hidden" name="job_insert_by" value="<?php echo $job_insert_by; ?>">
						<input type="hidden" value="editcustomerJob" name="action">
						<div class="top_cont display_block">  
							<div class="po_cont pull-left">
								<ul>
									<li class="pull-left search_filter">
										<input type="text"  placeholder="Customer Name" name="cust_name" value="<?php echo $asdd; ?>" onkeyup="get_customer();" >  
										 
										<ul class="filter" style="display:none;"></ul>
									</li>
									<li class="pull-right">
										<input type="text" value="<?php echo $po; ?>" placeholder="PO Number" name="po_num">
									</li>
								</ul>

							</div>

							<div class="dte_cont pull-right">
									<table>
									<tbody>
										<tr>
											<td style="width: 49px; padding: 0px 5% 0px 0px;">From</td>
											<td class="padding-input" style="width: 126px; position: relative;">
												<input type="text" readonly value="<?php echo $from_date; ?>" class="datepicker" name="from_date" placeholder="<?php echo $from_date; ?>">
												<div class="calndr_icns"> 
													<i aria-hidden="true" class="fa fa-calendar"></i>
												</div>
											</td>
											<td style="width: 49px; padding: 0px 5% 0px 0px;">To</td>
											<td class="padding-input" style="width: 126px; position: relative;">
												<input type="text" value="<?php echo $to_date; ?>" class="datepicker" name="to_date" placeholder="<?php echo $to_date; ?>"> 
												<div class="calndr_icns"> 
													<i aria-hidden="true" class="fa fa-calendar"></i>
												</div>
											</td>

										</tr>

									</tbody>
								</table>

							</div>
						</div>
					
						<div class="btm_cont display_block">
						
							<input class="frst-ip" type="text" name="job_name" placeholder="Name the Job" value="<?php echo $job_name; ?>" style=""> 
							<input class="scnd-ip" type="text" placeholder="Budgeted Hours" name="budget_hours" style="" value="<?php echo $budget_hours; ?>">  
						</div>
						<div class="closed_by_usr">
							<div class="pull-right">
								<span>Created by <b> <?php echo get_user_detail($job_insert_by,'first_name'); ?> <?php echo get_user_detail($job_insert_by,'last_name'); ?></b></span>
								<button type="submit" class="custom" name="submit">Save Job</button>
								<img style="display:none;" id="cust_job_loader" src="assets/images/loader.gif">
							</div> 
						</div>
					</form>
					<div class="result_job"></div>
					</div>
				</div>
			</div>
		
			<div class="close_mn new-timcrd display_block box" style="display:none;"></div>
			<div class="close_mn edit-timcrd display_block box" style="display:none;"></div> 
			<div class="edit_material display_block box" style="display:none;"></div> 
			<div class="add_material display_block box" style="display:none;">
				
				<?php
				$today=date('Y-m-d');
				$today_date=date('m/d/Y', strtotime($today));
				?>
				<div class="new_custmer">
				<a href="javascript:void(0);" onclick="jQuery('.add_material').slideUp();" class="close_new"><i class="fa fa-times" aria-hidden="true"></i></a>
				</div>
				<div class="inner_content">
					<div class="inner_pdng">
					<form method="post" action="" id="add_material" name="add_material" class="materl_cntnt"> 
						<input id="job_id" name="job_id" value="<?php echo $job_id; ?>" type="hidden">
						<input name="job_insert_by" value="<?php echo $user_id; ?>" type="hidden">
						<input value="Addmaterial" name="action" type="hidden">
						<div class="top_cont display_block">  
							<div class="pull-left material_sec">  
								<ul class="edit-mtrl">
									<li>
										<input readonly value="<?php echo $today_date; ?>" class="datepicker" name="cr_date[]" placeholder="<?php echo $today_date; ?>"  type="text">
										<div class="calndr_icns"> 
											<i aria-hidden="true" class="fa fa-calendar"></i>
										</div>
									</li>
									<li>
										<input  placeholder="Quantity" name="qty[]" type="text">
									</li> 
									<li> 
										<input  placeholder="Part" name="part[]" type="text">
									</li> 
									<li class="last-margin">
										<input  placeholder="Description" name="descp[]" type="text"> 
											 <div class="add-icn">
									<a href="javascript:void(0);" onclick="add_new_material();"><i class="fa fa-plus" aria-hidden="true"></i></a>								
								</div>
									</li>  
										
								</ul>
								
							</div>

							
						</div>
					
						
						<div class="closed_by_usr">
							<div class="pull-right">
								<span>Created by <b> <?php echo get_user_detail($user_id,'first_name'); ?> <?php echo get_user_detail($user_id,'last_name'); ?></b></span> 
								<button type="submit" class="custom" name="submit">Save</button>
								<img style="display:none;" id="new_material_loader" src="assets/images/loader.gif">
							</div>  
						</div>
					</form>
					<div class="result_material"></div>
					</div>
				</div>
			
			
			</div> 
			
			
			
            <div class="contrl-pnl display_block">
                <div class="inner_pdng">
                    <div class="pnl_lft pull-left custt-left">
                        <h5><?php echo $job_name; ?></h5>
                        <span><?php echo get_cust_detail($cust_id,'company') ?> - <?php echo get_cust_detail($cust_id,'work_add1') ?>, <?php echo get_cust_detail($cust_id,'work_add2') ?></span>
                    </div>
                    <div class="pnl_rt pull-right custt-right">
                        <ul>
                            <?php
							if($status==0) 
							{
							?>
							<li><strong>Invoice #</strong>
                                <span><?php echo $invoice_number; ?></span>
                            </li>
							<?php
							}
							?>
							<li><strong>PO #</strong>
                                <span><?php echo $po; ?></span>
                            </li>
                            <li><strong>Date(s)</strong>
                                <span><?php echo date('M d, Y', strtotime($from_date)); ?> - <?php echo date('M d, Y', strtotime($to_date)); ?></span>
                            </li>
							
						</ul>
                    </div>
					
                </div>
            </div>
			<div class=" digit_cont">
			
			<div class="figures-count heading row">		 
		
			<div class="col-md-3 hours-digit"> 
			<?php 
			if($budget_hours=="")
			{
				echo "<span class='digit'>0</span>";	
			}
			else
			{
				
				echo "<span class='digit'>".$budget_hours."</span>";
			}		
			?>
				<small>Budgeted Hours </small> 
			</div>
			<div class="col-md-3 hours-digit">
			<span class="digit"><?php echo $total_work=total_work_by_staff($job_id); ?>	</span>	
				<small>Hours Logged by Staff</small> 
			</div>  
			<div class="col-md-3 hours-digit">
				<span class="digit"><?php echo job_hour_remaining($job_id); ?></span>
				<small>Hours Remaining </small>
			</div>		
			<div class="col-md-3 hours-digit"> 
				<span class="digit"><?php echo total_work_by_vehicle($job_id); ?></span>
				<small>Km's Logged</small>  
			</div>
			</div>
			</div>
			
			
			
			
            <div class="close_mn closed_cont display_block">
				
				<?php
				if($status==0) 
				{
				$sql="select * from customer_job where status='0' and id='".$job_id."'";
				$res=mysql_query($sql);
				while($row=mysql_fetch_array($res))
				{
					$cust_id=$row['cust_id']; 
					$closed_by=$row['closed_by']; 
				?>
				<div class="close_job_sec">
				<div class="new_custmer">
					<h4 class="close_title">CLOSING NOTES</h4>
					</div>
					<div class="close_container">

						<div class="inner_content"> 
							<div class="inner_pdng">
								<div class="permit_no">
									<ul>
										<li><strong>Date Closed</strong>
											<p><?php echo date('F d,Y', strtotime($row['closing_date'])); ?></p>
										</li>
										<li><strong>Permit Notification Number</strong>
											<p><?php echo $row['pno']; ?></p>
										</li>
									</ul>
								</div>
								<div class="closing_nts_cont">
									<h5>Closing Notes</h5>
									<p><?php echo stripslashes($row['closing_notes']); ?></p>
								</div>
								<div class="close_lst">
									<ul>
										<li>
											<div class="image_clos">
											   <?php
												if($row['photo_1']=='')
												{	
												?>
												<img src="assets/images/upload_bg.jpg" alt="">
												<?php
												}
												else
												{
												?>											
												<img src="thumb.php?src=close/<?php echo $row['photo_1']; ?>&amp;w=320&amp;h=250">
												<?php
												}
												?>
											</div>
											<div class="imag_info" <?php if($row['photo_1_caption']==""){ echo 'style=display:none'; } ?>>
												<p><?php echo $row['photo_1_caption']; ?></p>
											</div>

										   
										</li>
										<li>
											<div class="image_clos">
											   <?php
												if($row['photo_2']=='')
												{	
												?>
												<img src="assets/images/upload_bg.jpg" alt="">
												<?php
												}
												else
												{
												?>											
												<img src="thumb.php?src=close/<?php echo $row['photo_2']; ?>&amp;w=320&amp;h=250">
												<?php
												}
												?>
											</div>
											<div class="imag_info" <?php if($row['photo_2_caption']==""){ echo 'style=display:none'; } ?>>
												<p><?php echo $row['photo_2_caption']; ?></p>
											</div>
										</li>
										<li>
										   
												<div class="image_clos">
											   <?php
												if($row['photo_3']=='')
												{	
												?>
												<img src="assets/images/upload_bg.jpg" alt="">
												<?php
												}
												else
												{
												?>											
												<img src="thumb.php?src=close/<?php echo $row['photo_3']; ?>&amp;w=320&amp;h=250">
												<?php
												}
												?>
											</div>
											<div class="imag_info" <?php if($row['photo_3_caption']==""){ echo 'style=display:none'; } ?>>
												<p><?php echo $row['photo_3_caption']; ?></p>
											</div>

										   
										</li>
									</ul>

								</div>
							</div>
							<?php
							if($user_role=="admin")
							{	
							?>
							<div class="closed_by_usr">
								<div class="inner_pdng">
									<div class="pull-right">
										<span>Closed by <b>by <?php echo get_user_detail($row['closed_by'],'first_name'); ?> <?php echo get_user_detail($row['closed_by'],'last_name'); ?></b></span>
										<a href="javascript:void(0);" class="custom" onclick="show_edit_close_job(<?php echo $job_id; ?>)">Edit</a> 
									</div> 
								</div>
							</div>
							<?php
							}
							?>
						</div>
					</div>
                </div>
				<?php
				}
				}
				?>
                <div class="edit_table customrs_cont">
					
					<?php
					$sql_1="select * from assign_invoices where job_id='".$_GET['id']."' order by DATE(cr_date) desc"; 
					$res_1=mysql_query($sql_1);
					$count_1=mysql_num_rows($res_1);
					if($count_1 > 0)
					{	
					?>
					<div class="view-pag display_block">
					<h4 class="close_title pull-left">INVOICES </h4>
					<div class="pagination_new pull-right">
					<span><a class="custom edit_cstm" target="_blank" href="<?php echo SITE_URL; ?>pdf_merger/sample.php?id=<?php echo $_GET['id']; ?>">View All</a></span>
					</div>
					</div>
					<table class="table">
                        <thead>
                            <tr>
                                <th style="width: 35px;"></th>
                                <th style="width: 196px;">Date</th>
                                <th style="width: 253px;">Invoice No.</th>
                                <th style="width: 376px;">Vendor</th>
                                <th style="width: 245px;">Added By</th>
                                <th style="text-align: center; width: 101px;"></th>
								
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							while($row_1=mysql_fetch_array($res_1))
							{	
								$vendor_id=get_invoice_info($row_1['invoice_id'],'vendor_id');
								$invoice_id=$row_1['invoice_id'];
								$inserted_by=$row_1['insert_by'];
								$attachment=get_invoice_info($invoice_id,'attachment');
							?>
							<tr>
                                <td></td> 
                                <td><?php echo date('M d, Y', strtotime($row_1['cr_date'])); ?></td>
                                <td><?php echo $row_1['invoice_num']; ?></td>
                                <td><?php echo get_vendor_info($vendor_id,'name'); ?></td>
                                <td><?php echo get_user_detail($inserted_by,'first_name'); ?> <?php echo get_user_detail($inserted_by,'last_name'); ?></td>
                                <td><a href="<?php echo SITE_URL; ?>attachment/<?php echo $attachment; ?>" target="_blank" class="custom edit_cstm">View</a></td>
                            </tr> 
                            <?php
							}
							?>
                        </tbody>
                    </table>
					<?php
					}
					?>

                </div>
				
				<div class="edit_table customrs_cont">
					
                    <?php
					$sql_1="select * from job_material where job_id='".$_GET['id']."' order by DATE(cr_date) desc"; 
					$res_1=mysql_query($sql_1);
					$count_1=mysql_num_rows($res_1);
					if($count_1 > 0)
					{	
					?>
					<h4 class="close_title">STOCK MATERIAL</h4> 
					<div style="display:none" class="delete_material"></div>
					<div class="del-mgs-cls"></div>
					<table class="table">
                        <thead>
                            <tr>
                                <th style="width: 35px;"></th>
                                <th style="width: 258px;">Date</th>
                                <th style="width: 253px;">Qty</th>
                                <th style="width: 345px;">Part</th>
                                <th style="width: 376px;">Description</th>
                                <th style="width: 245px;">Added By</th>
                                <th style="text-align: center; width: 101px;"></th>
								 <th style="text-align: center; width: 101px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							while($row_1=mysql_fetch_array($res_1))
							{	
								
							?>
							<tr>
                                 <td></td> 
                                <td><?php echo date('M d, Y', strtotime($row_1['cr_date'])); ?></td>
                                <td><?php echo $row_1['qty']; ?></td>
                                <td><?php echo $row_1['part']; ?></td>
                                <td><?php echo $row_1['descp']; ?></td>
                                <td><?php echo get_user_detail($row_1['insert_by'],'first_name'); ?> <?php echo get_user_detail($row_1['insert_by'],'last_name'); ?></td>
                                <td><a href="javascript:void(0);" class="custom edit_cstm" onclick="edit_material_1('<?php echo $row_1['id']; ?>');">Edit</a></td> 
								<?php if($user_role=="admin"){ ?>
								  <td><a href="javascript:void(0);" class="custom edit_cstm" onclick="delete_material('<?php echo $row_1['id']; ?>');">Delete</a> 
									
								  </td>
								<?php } ?>
                            </tr>  
                            <?php
							}
							?>
                        </tbody>
                    </table>
					<?php
					}
					?>

                </div>
				
				
				
				<div class="cust-timecardd">
				<?php
				$sql="select * from timecard where job_id='".$job_id."' order by cr_date desc";
				$res=mysql_query($sql);
				$count=mysql_num_rows($res);
				if($count > 0)
				{
				?>
				<h4 class="close_title">TIMECARDS</h4>
				<?php	
					while($row=mysql_fetch_array($res))
					{
						$id=$row['id'];
						$photos_1=$row['photos_1'];
						$photos_2=$row['photos_2'];
						$photos_3=$row['photos_3'];
						$notes=$row['notes'];
						$cr_date=$row['cr_date'];
						$insert_by=$row['insert_by'];
					
				?>
				<div class="timecard_box">
					<div class="time_cont display_block">
						
					   
						<div class="tim_left pull-left">
							<span></span>
							<p><?php echo date('M d, Y', strtotime($cr_date)); ?></p>
						</div>
						<div class="tim_rt pull-left" style="width:45%">
							<table>
								<tbody>
									<?php
									$staff_sql="select * from timecard_staff where card_id='".$id."'";
									$staff_res=mysql_query($staff_sql);
									while($row_staff=mysql_fetch_array($staff_res))
									{
									?>
									<tr>
										<td style="width: 22%; padding: 19px 3%;"><?php echo get_user_detail($row_staff['staff_id'],'first_name'); ?> <?php echo get_user_detail($row_staff['staff_id'],'last_name'); ?></td>
										<td><?php echo $row_staff['hours']; ?> Hours <span class="asddd" style="display:none;"><?php echo $row_staff['hours']; ?></span></td>
									</tr> 
									<?php	
									}	
									?>
									<tr>

										<td style="padding: 17px 3%; border-bottom: 1px solid #ccc;" colspan="2">
											<b>NOTES</b>
											<p><?php echo $notes; ?></p>
										</td>



									</tr>
								</tbody>
							</table>

						</div>
						<div class="tim_rt pull-left" style="width:50%">
						<table>
								<tbody>
									<?php
									$vehicle_sql="select * from timecard_vehicle where card_id='".$id."'";
									$vehicle_res=mysql_query($vehicle_sql);
									while($row_vehicle=mysql_fetch_array($vehicle_res))
									{
									?>
									<tr>
										<td style="width: 54%; padding: 19px 3%;">VEHICLE # <?php echo get_vehicle_info($row_vehicle['vehicle_id'],'vehicle_num'); ?></td>
										<td><?php echo $row_vehicle['descp']; ?></td>
										<td><?php echo $row_vehicle['km']; ?> KM</td>
									</tr> 
									<?php	
									}	
									?>
									
								</tbody>
							</table>
						</div>	
					</div>
					
						<div class="close_lst btm_cls">
							<ul>
								<?php
								if($photos_1!='')
								{	
								?>
								<li class="photo_1">
								
										<div class="image_clos">
											<a class="example-image-link" href="thumb.php?src=timecard/<?php echo $photos_1; ?>&w=800&h=600" data-lightbox="example-set">
											<img src="thumb.php?src=timecard/<?php echo $photos_1; ?>&w=320&h=250">
											</a>
										
										<div class="imag_info"><?php echo $row['photos_1_caption']; ?></div>
										</div>
								</li>
								<?php
								}
								?>
								<?php
								if($photos_2!='')
								{	
								?>
								<li class="photo_2">
									
								<div class="image_clos">
									<a class="example-image-link" href="thumb.php?src=timecard/<?php echo $photos_2; ?>&w=800&h=600" data-lightbox="example-set">
									<img src="thumb.php?src=timecard/<?php echo $photos_2; ?>&w=320&h=250">
									</a>
								</div>
								<div class="imag_info"><?php echo $row['photos_2_caption']; ?></div>
								</li>
								<?php
								}
								?>
								<?php
								if($photos_3!='')
								{	
								?>
								<li class="photo_3">
									<div class="image_clos">
									<a class="example-image-link" href="thumb.php?src=timecard/<?php echo $photos_3; ?>&w=800&h=600" data-lightbox="example-set">
									<img src="thumb.php?src=timecard/<?php echo $photos_3; ?>&w=320&h=250">
									</a>
									<div class="imag_info"><?php echo $row['photos_3_caption']; ?></div>
									</div> 
								</li>
								<?php
								} 
								?>
							</ul>
							
							<div class="closed_by_usr">
								<div class="inner_pdng">
									<div class="pull-right">
										<span>Inserted by <b><?php echo get_user_detail($insert_by,'first_name'); ?> <?php echo get_user_detail($insert_by,'last_name'); ?></b></span>
										<a class="custom edit_cstm" href="javascript:void(0);" onclick="edit('<?php echo $id; ?>');">Edit</a>
									</div>
								</div>
							</div>
							
					
						</div>
					
				</div>
				<?php
					}
				}
				else
				{	
				?>
				<div class="alert alert-danger">No timechart found</div>
				<?php
				}
				?>
                </div> 
            </div>


        </div>
<script>	
 
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
	
jQuery(function() {
	
	
    
	jQuery( ".datepicker" ).datepicker({
        changeMonth: false,
        changeYear: false,
		 
    });
	
	// Close Job
	jQuery('#close_job').validate({
		submitHandler: function(form) { 
			jQuery('.close_job_result').empty();
			jQuery('.close_job_result').show();
			jQuery('#close_job_loader').show();
			
			var cust_id=jQuery('#close_job').find('#cust_id').val();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					if(data==1)
					{
					jQuery('#close_job_loader').hide();
					jQuery('.close_job_result').empty().append('<div class="alert alert-success">This job has been closed</div>');
					jQuery('.close_job_result').fadeOut(3000); 
					window.location.href='<?php echo SITE_URL; ?>?section=customer-history&id='+cust_id; 
					}	
					
					
				}
			});
		}
		
	});
	
	
	jQuery('#add_material').validate({
		rules: {
			'qty[]': {
				required: true 
			},
			'part[]': {
				required: true 
			}
		},
		
		submitHandler: function(form) { 
			jQuery('.result_material').empty();
			jQuery('.result_material').show();
			jQuery('#new_material_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					
					jQuery('#new_material_loader').hide();
					jQuery('.result_material').empty().append('<div class="alert alert-success">Your material has been added</div>'); 
					jQuery('.result_material').fadeOut(3000); 
					location.reload();  
					
				}
			});
		}
		
	});
	
	
	
	
});

function add_timecard_staff()
{
	
	var staff_id='';
	jQuery.each(jQuery("select[name='staff_id[]']"), function() {
	staff_id += (staff_id?',':'') + jQuery(this).val();
	});
	
	var id=jQuery('.selectn_box>ul').length;
	jQuery.ajax({type: "POST",
	url: "handler.php",
	data: "id="+id+"&staff_id="+staff_id+"&action=addTimecardStaff",  
	success:function(result)
	{
		
		jQuery(result).insertAfter('.selectn_box>ul:last');
	},
	error:function(e){
		console.log(e);
	}	
	}); 
}

function add_timecard_staff_1()
{
	
	var id=jQuery('.selectn_box_1>ul').length;
	jQuery.ajax({type: "POST",
	url: "handler.php",
	data: "id="+id+"&action=addTimecardStaff",  
	success:function(result)
	{
		
		jQuery(result).insertAfter('.selectn_box_1>ul:last');
	},
	error:function(e){
		console.log(e);
	}	
	}); 
}

function remove_ul(e,id)
{
	if(e==1)
	{
		jQuery('.box_'+id).fadeOut(2000);
		jQuery('.box_'+id).remove();
	}
	else
	{
		
		jQuery('.box_'+id).fadeOut(3000);
		jQuery('.box_'+id).remove();
		
	}		
}

function edit(id)
{
	jQuery('.box').hide();
	jQuery.ajax({type: "POST",
	url: "handler.php",
	data: "id="+id+"&action=editTimecard_show",  
	success:function(result)
	{
		jQuery('html, body').animate({
        scrollTop: jQuery('.rt_sidebar').offset().top
		}, 1000);
		jQuery('.box').slideUp();
		jQuery('.edit-timcrd').slideDown();
		jQuery('.edit-timcrd').empty().append(result);
	},
	error:function(e){ 
		console.log(e);
	}	
	}); 
}

function del_photo(e,id)
{
	if (confirm('Are you Sure!! You want to delete this image?')) 
	{
		jQuery.ajax({type: "POST",
		url: "handler.php",
		data: "id="+id+"&e="+e+"&action=del_photo",  
		success:function(result)
		{
			jQuery('.ed_photo_'+e).fadeOut(3000);
			jQuery('.ed_photo_'+e).remove();  
		},
		error:function(e){ 
			console.log(e);
		}	
		});
	}	
}

function open_job(job_id)
{
	var user=jQuery('#edit_job').find('#cust_id').val();	
	jQuery.ajax({type: "POST",
	url: "handler.php",
	data: "id="+job_id+"&action=OpenJob",   
	success:function(result) 
	{
		window.location.href='<?php echo SITE_URL; ?>/?section=customer-history&id='+user; 
	},
	error:function(e){ 
		console.log(e); 
	}	
	}); 
}

function show_close_job(job_id)
{
	
	jQuery.ajax({type: "POST",
	url: "handler.php", 
	data: "id="+job_id+"&action=CloseJob",    
	success:function(result) 
	{
		jQuery('.box').slideUp();
		jQuery('.close-job-notee').slideDown();
		jQuery('.close-job-notee').empty().append(result);
		
	},
	error:function(e){ 
		console.log(e); 
	}	
	}); 
}

function close_job_info(id)
{
	jQuery.ajax({type: "POST",
	url: "handler.php", 
	data: "id="+id+"&action=CloseJobInfo",    
	success:function(result) 
	{
		jQuery('.box').slideUp();
		jQuery('.close-job-info').slideDown();
		jQuery('.close-job-info').empty().append(result); 
		//window.location.href='<?php echo SITE_URL; ?>/?section=customer-history&id='+user;
	},
	error:function(e){ 
		console.log(e); 
	}	
	}); 
}

function show_edit_close_job(job_id)
{
	
	jQuery('#close_job_loader').show();
	jQuery.ajax({type: "POST",
	url: "handler.php", 
	data: "id="+job_id+"&action=ShowEditCloseJob",     
	success:function(result) 
	{
		jQuery('#close_job_loader').hide(); 
		jQuery('html, body').animate({
        scrollTop: jQuery(".rt_sidebar").offset().top
		}, 1000);
		jQuery('.close_job_sec').empty().append(result); 
		
	},
	error:function(e){ 
		console.log(e); 
	}	
	}); 
}

function asd()  
{
	alert('aaaaaaaaaaaaaa');
	//window.open('http://google.com', '_blank');
}

function show_report(job_id,user_id)
{
	var loader='<center><img src="assets/images/loader.gif" /></center>';	
	jQuery('.create-report').show();
	jQuery('.create-report').empty().append(loader); 
	jQuery.ajax({type: "POST",
	url: "handler.php", 
	data: "job_id="+job_id+"&user_id="+user_id+"&action=ShowReportSection",     
	success:function(result) 
	{
		jQuery('.create-report').empty().append(result); 
		
		
	},
	error:function(e){ 
		console.log(e); 
	}	
	}); 
}

function show_new_timecard(job_id)
{
	var loader='<center><img src="assets/images/loader.gif" style="margin-top:10px;" /></center>';	
	jQuery('.new-timcrd').show();
	jQuery('.new-timcrd').empty().append(loader); 
	
	jQuery.ajax({type: "POST",
	url: "handler.php", 
	data: "job_id="+job_id+"&action=ShowNewTimecard",     
	success:function(result) 
	{
		jQuery('.new-timcrd').empty().append(result); 
		
		
	},
	error:function(e){ 
		console.log(e); 
	}	
	}); 
}

function show_material(job_id)
{
	var loader='<center><img src="assets/images/loader.gif" style="margin-top:10px;" /></center>';	
	jQuery('.add_material').show();
	jQuery('.add_material').empty().append(loader); 
	
	jQuery.ajax({type: "POST",
	url: "handler.php", 
	data: "job_id="+job_id+"&action=ShowMaterial",      
	success:function(result) 
	{
		jQuery('.add_material').empty().append(result);  
		
		
	},
	error:function(e){ 
		console.log(e); 
	}	
	}); 
}

function add_new_material()
{
	var count=jQuery('.material_sec>ul').length;
	jQuery.ajax({type: "POST",
	url: "handler.php", 
	data: "count="+count+"&action=AddNewmaterial",      
	success:function(result) 
	{
		jQuery(result).insertAfter('.material_sec>ul:last');  
		jQuery( ".datepicker" ).datepicker();
	},
	error:function(e){ 
		console.log(e); 
	}	
	});  
}

function remove_material(e,id)
{
	jQuery('.mat_'+id).fadeOut(3000);
	jQuery('.mat_'+id).remove(); 
}

function edit_material_1(id)
{
	jQuery('.box').hide(); 
	jQuery("html, body").animate({
        scrollTop: jQuery('.rt_sidebar').offset().top 
    }, 1000); 
	
	
	var loader='<center><img src="assets/images/loader.gif" style="margin-top:10px;" /></center>';	
	jQuery('.edit_material').show();
	jQuery('.edit_material').empty().append(loader);  
	
	jQuery.ajax({type: "POST",
	url: "handler.php", 
	data: "id="+id+"&action=ShowMaterial",          
	success:function(result) 
	{
		jQuery('.edit_material').empty().append(result);   
		//alert(result);
		
	},
	error:function(e){ 
		console.log(e);  
	}	
	});  
}
function delete_material(id)
{
if (confirm('Are you sure you want to delete this item?')) 
	{
		var loader='<img src="assets/images/loader.gif" style="margin-top:10px;" />';	
		jQuery('.delete_material').show();
		jQuery('.delete_material').empty().append(loader);  
		
		jQuery.ajax({type: "POST",
		url: "handler.php", 
		data: "id="+id+"&action=DeleteMaterial",          
		success:function(result) 
		{
			jQuery('.delete_material').hide();
			if(result=='1'){
				jQuery('.del-mgs-cls').empty().append('<div class="alert alert-success">Material has been deleted sucessfully.</div>');
					jQuery('.del-mgs-cls').fadeOut(30000); 
					location.reload(); 
			}else{
				jQuery('.del-mgs-cls').empty().append('<div class="alert alert-danger">Please try again.</div>');
					jQuery('.del-mgs-cls').fadeOut(3000); 
					location.reload(); 
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