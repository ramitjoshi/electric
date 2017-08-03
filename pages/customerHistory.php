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
<?php $cust_id=$_GET['id']; ?>
<?php 
$user_role=get_user_detail($_SESSION['user_id'],'role');  
?>
<div class="rt_sidebar pull-left historical-job">
	<div class="btns_top display_block">
		<div class="btns_lft pull-left">
			<a href="<?php echo SITE_URL; ?>?section=dashboard" class="custom">Dashboard</a>
			<?php
			if($user_role=="admin")
			{	
			?>
				<a href="javascript:void(0);" class="custom" onclick="edit('<?php echo $_GET['id']; ?>')">Edit Customer</a>
			<?php
			}
			?>
			<a href="<?php echo SITE_URL; ?>?section=job" class="custom">New Job</a> 
			<a href="<?php echo SITE_URL; ?>?section=customer" class="custom">Back</a> 
		</div>
		<div class="btns_rt pull-right">
			<input type="text" placeholder="Search">
			<a href=""><i class="fa fa-search" aria-hidden="true"></i></a>
		</div>
	</div>
	<div class="close_mn display_block">
		

		<div class="edit_table">
			<div class="box edit_cust_box" style="display:none;"></div>
			<div class="close_container custmr_edt_cnt">
<div class="customers_container view_cstmr">
	<div class="close_mn display_block">
			<div class="box top_inpt edit_table open-jobs-top vndr_cnt display_block">
				<?php
				$sql="select * from customer where id='".$cust_id."'";
				$res=mysql_query($sql);	
				while($row=mysql_fetch_array($res))
				{	
				?>
				<table class="table">
					<thead> 
						<tr>
							<th style="width: 3%;"></th>
							<th style="width: 18%;">First Name</th>
							<th style="width: 19%;">Last Name</th>
							<th style="width: 35%;">Email Address</th>
							<th style="width: 15%;">Telephone</th>
							<th style="text-align: center; width: 10%;"></th>
						</tr>
					</thead>
					<tbody>
					<td colspan="6" class="internal-table">
						<table class="table brdr brdr_none margin_non">
							<tbody>
								<tr>
									<td style="width: 3%;"></td>
									<td style="width: 18%;"><?php echo $row['first_name']; ?></td>
									<td style="width: 19%;"><?php echo $row['last_name']; ?></td>
									<td style="width: 35%;"><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
									<td style="width: 15%;"><?php echo $row['mobile_phone']; ?></td>
									<td style=" width: 10%;"></td>

								</tr>

								<tr>
									<td colspan="6" class="internal-table">
										<table class="table">
											<tbody>
												<tr class="vrtcl_top">
													<td style="width: 3%;"></td>
													<td style="width: 18%;">
														<strong>Telephone</strong>
														<span><?php echo $row['mobile_phone']; ?> </span>
														<span><?php echo $row['home_phone']; ?>  </span>
														<span><?php echo $row['work_phone']; ?> </span>
													</td>
													<td style="width: 19%;">
														<strong>Work or Home </strong>
														<!--<span><?php echo $row['work_name']; ?></span>-->
														<span><?php echo $row['work_add1']; ?></span>
														<span><?php echo $row['work_add2']; ?></span>
														<span><?php echo $row['work_city']; ?></span>
														<span><?php echo $row['work_prov']; ?></span>
														<span><?php echo $row['work_post_code']; ?></span>
													 
														
														<span><?php echo $row['home_add1']; ?></span>
														<span><?php echo $row['home_add2']; ?></span>
														<span><?php echo $row['home_city']; ?></span>
														<span><?php echo $row['home_prov']; ?></span>
														<span><?php echo $row['home_post_code']; ?></span>
														
														</td>
													
													<td style="width: 20%;"><strong>Cottage</strong>
													
												
													</td>
													
													<td style="width: 30%;"><strong>Notes </strong>
														<span><?php echo $row['note']; ?></span></td>
														<td style=" width: 10%;"></td>
													
												</tr>
											</tbody>
										</table>
									</td>
								</tr>

							</tbody>
						</table> 

					</td>
					
					</tbody>
				</table>
				<?php
				}
				?>
			</div>
			</div>
			</div>
			</div>


			<div class="open-jobs-hist display_block">
				<h4 class="close_title">OPEN JOBS</h4>
				<?php
				$sql="select * from customer_job where cust_id='".$cust_id."' and status='1'";
				$res=mysql_query($sql);
				$count=mysql_num_rows($res);
				if($count > 0)
				{	
				?>
				<table class="table">
					<thead>
						<tr>
							<th style="width: 3%;"></th>
							<th style="width: 18%;">Date </th>
							<th style="width: 38%;">Job Name</th>
							<th style="width: 16%;">Created By</th>
							<th style="width: 15%; text-align: center;">Days Open</th>
							<th style="text-align: center; width: 10%;"></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$today=date('Y-m-d'); 
						while($row=mysql_fetch_array($res))
						{	
						$from_date=$row['from_date'];
						$name=$row['name'];
						
						$date1 = $today;
						$date2 = $from_date; 

						$diff = abs(strtotime($date2) - strtotime($date1));

						$years = floor($diff / (365*60*60*24));
						$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
						$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
						
						$asd=$days; 
						
						?>
						<tr>
							<td></td>
							<td><?php echo date('d/m/Y', strtotime($from_date)); ?></td>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo get_user_detail($row['job_insert_by'],'first_name').' '.get_user_detail($row['job_insert_by'],'last_name'); ?></td>
							<td style="text-align: center;"><?php echo $asd; ?></td>
							<td><a class="custom edit_cstm" href="<?php echo SITE_URL; ?>?section=customer-job&id=<?php echo $row['id']; ?>">View</a></td>
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
			<div class="open-jobs-hist display_block">
				<h4 class="close_title">CLOSED JOBS</h4>
				<?php
				$sql="select * from customer_job where cust_id='".$cust_id."' and status='0'";
				$res=mysql_query($sql);
				$count=mysql_num_rows($res);
				if($count > 0)
				{	
				?>
				<table class="table">
					<thead>
						<tr>
							<th style="width: 3%;"></th>
							<th style="width: 18%;">Date </th>
							<th style="width: 38%;">Job Name</th>
							<th style="width: 16%;">Created By</th>
							<th style="width: 15%; text-align: center;">Invoiced</th>
							<th style="text-align: center; width: 10%;"></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$today=date('Y-m-d'); 
						while($row=mysql_fetch_array($res))
						{	
						$from_date=$row['from_date'];
						
						$countt=get_invoiced_job($row['id']);
						$invoiced_no=$row['invoice_number']; 
							
							?>
							<tr>
								<td></td>
								<td><?php echo date('d/m/Y', strtotime($from_date)); ?></td>
								<td><?php echo $row['name']; ?></td>
								<td><?php echo get_user_detail($row['job_insert_by'],'first_name').' '.get_user_detail($row['job_insert_by'],'last_name'); ?></td>
								<td style="text-align: center;">
								<?php
								if($countt > 0)
								{
								?>
								Yes - <span><?php echo $invoiced_no; ?></span>
								<?php
								}
								else
								{
								?>
								No
								<?php
								}
								?>
								</td>
								<td><a class="custom edit_cstm" href="<?php echo SITE_URL; ?>?section=customer-job&id=<?php echo $row['id']; ?>">View</a></td>
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
</div>
<script>
function edit(id)
	{
		//jQuery('.box').hide();
		//jQuery('.vndr_cnt').slideUp();
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
			jQuery('.cust-del').hide();
		},
		error:function(e){
			console.log(e);
		}	
		}); 
		
		
	}

</script>
<?php get_footer(); ?>