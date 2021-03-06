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
$user_id=$_SESSION['user_id'];
$user_role=get_user_detail($_SESSION['user_id'],'role');  
?>

		<?php get_sidebar(); ?>
		<?php
		
		if(isset($_GET['d']))
		{
			$arr=explode('/',$_GET['d']);
			
			$mon=$arr[0];
			$sun=$arr[1];
			
			$curr_monday = $mon;
			$curr_saturday = $sun; 
			
			$givenDate=$curr_monday;  
			
			$prev_monday=date('Y-m-d', strtotime('previous monday', strtotime($givenDate)));
			$next_monday= date('Y-m-d', strtotime('next monday', strtotime($givenDate)));
			
			
		}	
		else
		{	
		$curr_monday = date( 'Y-m-d', strtotime('monday this week'));
		$curr_saturday = date( 'Y-m-d', strtotime('sunday this week') );	
		
		$givenDate=$curr_monday;  
		
		$prev_monday=date('Y-m-d', strtotime('previous monday', strtotime($givenDate)));
		$next_monday= date('Y-m-d', strtotime('next monday', strtotime($givenDate)));
		}
		
		
		$month=date('m', strtotime($givenDate));
		$year=date('Y', strtotime($givenDate));
		
		$dateArr = calculateWeekDate($month,$year);
		$count=count($dateArr);
		for($i=0;$i<$count;$i++)
		{   
		$start= $dateArr[$i][0];
		$monArr[]= $dateArr[$i][0];
		
		} 
		
		
		?>
        <div class="rt_sidebar pull-left">
            
			<?php
			if($user_role=="admin")
			{	
			?>
			<div class="btns_top display_block">
                <div class="btns_lft pull-left">
                    <a href="<?php echo SITE_URL; ?>?section=dashboard" class="custom">Dashboard</a>
                    <a class="custom" onclick="show_report('<?php echo $curr_monday; ?>','<?php echo $curr_saturday; ?>');" href="javascript:void(0);">New Report</a> 
                </div>
                <div class="btns_rt pull-right">
                   <!--<input type="text" placeholder="Search">
                    <a href=""><i class="fa fa-search" aria-hidden="true"></i></a>-->
                </div>
            </div>
            <div class="timeshts_cont display_block">
				<div style="display:none;" class="box add_cust_box"> </div>
			
                <div class="recen_cont display_block">
                <div class="inner_pdng">
                <?php
				$sunday=date("Y-m-d",strtotime($prev_monday."+6 day"));
				?>
				<a class="navbtn left" href="?section=timesheet&d=<?php echo $prev_monday ?>/<?php echo $sunday; ?>"><i class="fa fa-angle-left" aria-hidden="true"></i> Prev</a>
				<?php	
				$sunday=date("Y-m-d",strtotime($next_monday."+6 day"));
				?>
				<a class="navbtn right" href="?section=timesheet&d=<?php echo $next_monday ?>/<?php echo $sunday; ?>">Next <i class="fa fa-angle-right" aria-hidden="true"></i></a>
				
                        <span class="date centered pull-right"><?php echo date('F d, Y', strtotime($curr_monday)); ?> – <?php echo date('F d, Y', strtotime($curr_saturday)); ?></span></div>
                </div>
                <div class="calndr_cont display_block">
					<?php
					$sql="select distinct id from users where id!=1 order by last_name ASC";
					$res=mysql_query($sql);
					while($row=mysql_fetch_array($res))
					{	
						$staff_id=$row['id'];
						$count=0;
					?>
                    <div class="display_block mn_clndr">
                        <div class="clndr_lft">
                            <span></span>
                            <strong><?php echo get_user_detail($staff_id,'first_name'); ?> <?php echo get_user_detail($staff_id,'last_name'); ?></strong>
                        </div>
                        <div class="clndr_rt">
                            <div class="recen_cont display_block">
                                <div class="inner_pdng">
                                    <span class="date pull-left"><?php echo date('M', strtotime($curr_monday)); ?> <?php echo date('d', strtotime($curr_monday)); ?>-<?php echo date('M', strtotime($curr_saturday)); ?> <?php echo date('d', strtotime($curr_saturday)); ?>, <?php echo date('Y', strtotime($curr_saturday)); ?></span></div> 
                            </div>
                            <div class="table_calndr">
                                <table>
                                    <thead>
                                        <?php
										$datePeriod = returnDates($curr_monday, $curr_saturday);
										foreach($datePeriod as $date) {
										$date_d=$date->format('Y-m-d');	
										?>
										<th><?php echo date('D', strtotime($date_d));  ?></th>
										<?php } ?>

                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
											$datePeriod = returnDates($curr_monday, $curr_saturday);
											foreach($datePeriod as $date)  
											{
											$datee=$date->format('Y-m-d');	
											$total=get_sum_hours($datee,$staff_id);
											$count=$count+$total;
											?>
											<td><?php echo get_sum_hours($datee,$staff_id); ?> Hours</td>
                                            <?php  
											}
											?>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>TOTAL</strong></td>
                                            <td><strong><?php echo $count; ?> Hours</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                          
                            </div>
                        </div>
                    </div>
                    <?php
					}
					?>
                </div>
			</div>
			<?php
			}
			else
			{
			?>
			<div class="btns_top display_block">
                <div class="btns_lft pull-left">
                    <a href="<?php echo SITE_URL; ?>?section=dashboard" class="custom">Dashboard</a>
                     
                </div>
                <div class="btns_rt pull-right">
                   <!--<input type="text" placeholder="Search">
                    <a href=""><i class="fa fa-search" aria-hidden="true"></i></a>-->
                </div>
            </div>
			<div class="timeshts_cont display_block"> 
				<div style="display:none;" class="box add_cust_box"> 
				<div class="new_custmer">
					<a href="javascript:void(0);" onclick="jQuery('.add_cust_box').slideUp();" class="close_new"><i class="fa fa-times" aria-hidden="true"></i></a>
					<h4 class="close_title">NEW REPORT</h4> 
					</div>
					<div class="close_container custmr_edt_cnt mb-20 pb-20">
					<form method="post" action="<?php echo SITE_URL; ?>pdf/timesheet.php" id="add_customer" name="add_customer" target="_blank">  
						<input type="hidden" value="AddCustomer" name="action">	
						
						<input type="hidden" value="<?php echo $curr_monday ?>" name="curr_monday">	
						<input type="hidden" value="<?php echo $curr_saturday; ?>" name="curr_sunday">	
						
						<div class="cstm-wid btm_inpt new-report">
							<div class="col-1">								 
								<ul>
									<li>
										<h4><?php echo date('F d, Y', strtotime($curr_monday)); ?> – <?php echo date('F d, Y', strtotime($curr_saturday)); ?></h4>
									</li>
								</ul>
							</div>
							<div class="col-1">
								<h4> Staff Name</h4>
								
								<ul>
									
									<li>
										<div class="selectn_box pb-20"> 
											<div class="asd">
												<select class="show-menu-arrow form-control valid" name="staff_id[]" id="staff_id">
													<option value=""></option>
													<?php
													$sql="select * from users where id!='1'";
													$res=mysql_query($sql);
													while($row=mysql_fetch_array($res))
													{	
														$staff_id=$row['id'];
														$first_name=$row['first_name'];
														$last_name=$row['last_name'];
														$name=$first_name.' '.$last_name;
													?>
														<option value="<?php echo $staff_id; ?>" ><?php echo $name; ?></option>
													<?php
													}
													?>
												</select>
											</div>
										
										</div>
										<div class="text-center"><a onclick="add_timecard_staff_11();" class="custom edit_cstm" href="javascript:void(0);">Add Staff +</a></div>
									</li>
									
									
								</ul>
								
							</div>
							<div class="col-1">
								
								<ul>
									
									<li>
										<button name="submit" type="submit" class="custom edit_cstm">Create</button>
									</li>
								</ul>
							</div>
							
						</div>
						
					</form>
					
					</div>
                </div>
			
                <div class="recen_cont display_block">
                <div class="inner_pdng">
                <?php
				$sunday=date("Y-m-d",strtotime($prev_monday."+6 day"));
				?>
				<a class="navbtn left" href="?section=timesheet&d=<?php echo $prev_monday ?>/<?php echo $sunday; ?>"><i class="fa fa-angle-left" aria-hidden="true"></i> Prev</a>
				<?php	
				$sunday=date("Y-m-d",strtotime($next_monday."+6 day"));
				?>
				<a class="navbtn right" href="?section=timesheet&d=<?php echo $next_monday ?>/<?php echo $sunday; ?>">Next <i class="fa fa-angle-right" aria-hidden="true"></i></a>
				                   
                <span class="date centered pull-right"><?php echo date('F d, Y', strtotime($curr_monday)); ?> – <?php echo date('F d, Y', strtotime($curr_saturday)); ?></span></div>
                </div>
                <div class="calndr_cont display_block">
					<?php
					$sql="select distinct staff_id from timecard_staff where staff_id='".$user_id."'"; 
					$res=mysql_query($sql);
					while($row=mysql_fetch_array($res))
					{	
						$staff_id=$row['staff_id'];
						$count=0;
					?>
                    <div class="display_block mn_clndr">
                        <div class="clndr_lft">
                            <span></span>
                            <strong><?php echo get_user_detail($staff_id,'first_name'); ?> <?php echo get_user_detail($staff_id,'last_name'); ?></strong>
                        </div>
                        <div class="clndr_rt">
                            <div class="recen_cont display_block">
                                <div class="inner_pdng">
                                    <span class="date pull-left"><?php echo date('M', strtotime($curr_monday)); ?> <?php echo date('d', strtotime($curr_monday)); ?>-<?php echo date('d', strtotime($curr_saturday)); ?>, <?php echo date('Y', strtotime($curr_saturday)); ?></span></div> 
                            </div>
                            <div class="table_calndr">
                                <table>
                                    <thead>
                                        <?php
										$datePeriod = returnDates($curr_monday, $curr_saturday);
										foreach($datePeriod as $date) {
										$date_d=$date->format('Y-m-d');	
										?>
										<th><?php echo date('D', strtotime($date_d));  ?></th>
										<?php } ?>

                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
											$datePeriod = returnDates($curr_monday, $curr_saturday);
											foreach($datePeriod as $date)  
											{
											$datee=$date->format('Y-m-d');	
											$total=get_sum_hours($datee,$staff_id);
											$count=$count+$total;
											?>
											<td><?php echo get_sum_hours($datee,$staff_id); ?> Hours</td>
                                            <?php  
											}
											?>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>TOTAL</strong></td>
                                            <td><strong><?php echo $count; ?> Hours</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
					}
					?>
                </div>
			</div>
			<?php	
			}	
			?>
        </div>
<script>



function add_timecard_staff_11()
{
	jQuery('.gen_report').empty().hide();
	var staff_id='';
	jQuery.each(jQuery("select[name='staff_id[]']"), function() {
	staff_id += (staff_id?',':'') + jQuery(this).val();
	});
	 
	
	
	var id=jQuery('.selectn_box>.asd').length; 
	jQuery.ajax({type: "POST",
	url: "handler.php",
	data: "id="+id+"&staff_id="+staff_id+"&action=addTimecardStaff_11",  
	success:function(result)
	{
		
		jQuery(result).insertAfter('.selectn_box>.asd:last');
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

function show_report(monday,saturday)
{
	var loader='<center><img src="assets/images/loader.gif" /></center>';	
	jQuery('.add_cust_box').show();
	jQuery('.add_cust_box').empty().append(loader); 
	jQuery.ajax({type: "POST",
	url: "handler.php", 
	data: "mon="+monday+"&sat="+saturday+"&action=ShowReportTime",     
	success:function(result) 
	{
		jQuery('.add_cust_box').empty().append(result);  
		
		
	},
	error:function(e){ 
		console.log(e); 
	}	
	}); 
}

</script>		
<?php get_footer(); ?>		