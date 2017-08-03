<?php
function get_header()
{
require_once   'config/header.php';	
}

function get_footer()
{
require_once   'config/footer.php';	
}

function get_sidebar()
{
require_once   'config/leftbar.php';	
}


function get_navbar()
{
require_once   'config/navbar.php';	
}

function user_exist($username)
{
	$sql="select username from users where username='".$username."'";
	$res=mysql_query($sql);
	return $count=mysql_num_rows($res);
}

function user_email_exist($email)
{
	$sql="select username from users where email='".$email."'";
	$res=mysql_query($sql);
	return $count=mysql_num_rows($res);
}

function get_user_detail($user_id,$field)
{
	$sql="select ".$field." from users where id='".$user_id."'";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		return $row[$field];
	}	
}

function get_cust_detail($user_id,$field)
{
	$sql="select ".$field." from customer where id='".$user_id."'";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		return $row[$field];
	}	
}

function get_vendor_by_email($email,$field)
{
	$sql="select ".$field." from vendor where email='".$email."'";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		return $row[$field];
	}	
}

function get_vendor_info($vendor_id,$field)
{
	$sql="select ".$field." from vendor where id='".$vendor_id."'";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		return $row[$field];
	}	
}

function get_invoice_info($invoice_id,$field)
{
	$sql="select ".$field." from invoices where id='".$invoice_id."'";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		return $row[$field];
	}	
}

function get_customer_job_info($job_id,$field)
{
	$sql="select ".$field." from customer_job where id='".$job_id."'";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		return $row[$field];
	}	
}

function get_invoiced_job($job_id)
{
	$sql="select * from invoiced_jobs where job_id='".$job_id."'";
	$res=mysql_query($sql);
	return $count=mysql_num_rows($res);
}

function get_closed_job_dashboard($job_id)
{
	$sql="select * from closed_job_dashboard where job_id='".$job_id."'";
	$res=mysql_query($sql); 
	return $count=mysql_num_rows($res);
}



function get_sum_hours($date,$user_id)
{
	$sql="SELECT SUM(hours) 'total' FROM timecard_staff WHERE DATE(cr_date) =  '".$date."' AND staff_id ='".$user_id."' GROUP BY staff_id";
	$res=mysql_query($sql);
	$count=mysql_num_rows($res);
	if($count==0)
	{
		return "0"; 
	}
	else
	{		
		while($row=mysql_fetch_array($res))
		{
			return $row['total'];  
		}
	}	
}

function invoice_assigned_exist($invoice_id)
{
	$sql="select * from assign_invoices where invoice_id='".$invoice_id."'";
	$res=mysql_query($sql);
	return $count=mysql_num_rows($res);	
}

function get_assign_invoices_info($invoice_id,$field)
{
	$sql="select ".$field." from assign_invoices where invoice_id='".$invoice_id."'";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		return $row[$field];
	}	
}


function returnDates($fromdate, $todate) {
	$fromdate = \DateTime::createFromFormat('Y-m-d', $fromdate);
	$todate = \DateTime::createFromFormat('Y-m-d', $todate);
	return new \DatePeriod(
		$fromdate,
		new \DateInterval('P1D'),
		$todate->modify('+1 day')
	);
}

function check_attempt($session_id,$cr_date)
{
	$check_at="select * from login_attempts where session_id='".$session_id."' and cr_date='".$cr_date."'";  
	$res_check=mysql_query($check_at);
	while($row_check=mysql_fetch_array($res_check))
	{
		return $last_attmpt=$row_check['attempt']; 
	}
	
	
	
}
function calculateWeekDate($month,$year)
{
$noOfDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$returnData = array();
	for($day = 1; $day <= $noOfDaysInMonth; $day=$day+7) {
		$weekStartDate = date("Y-m-d", mktime(date("H"), date("i"), date("s"), $month, $day, $year));
		$returnData[] = weekRange($weekStartDate);
	}
return $returnData;
}

function weekRange($date) {
$ts = strtotime($date);
$start = (date('w', $ts) == 0) ? $ts : strtotime('last monday', $ts);
$startWeekDate = date('Y-m-d', $start);
$endWeekDate = date('Y-m-d', strtotime('next sunday', $start));
return array(date('Y-m-d', $start), date('Y-m-d', strtotime('next sunday', $start)));
}

//load content
function load_content($section)
{
	if($section == 'login'){
		require_once   'pages/login.php';	
	}
	else if($section == 'dashboard'){
		require_once   'pages/dashboard.php';	
	}
	else if($section == 'staff'){
		require_once   'pages/staff.php';	
	}
	else if($section == 'customer'){
		require_once   'pages/customer.php'; 	
	}
	else if($section == 'job'){
		require_once   'pages/job.php'; 	
	}
	else if($section == 'customer-history'){
		require_once   'pages/customerHistory.php'; 	
	}
	else if($section == 'customer-job'){
		require_once   'pages/customerJob.php'; 	
	}
	else if($section == 'vendors'){
		require_once   'pages/vendor.php'; 	
	}
    else if($section == 'invoice'){
		require_once   'pages/manual-invoice.php'; 	  
    }
    else if($section == 'closed-jobs'){
		require_once   'pages/closed-jobs.php'; 	  
    }
	else if($section == 'assigned-invoices'){
		require_once   'pages/assigned-invoices.php'; 	  
    }
	else if($section == 'timesheet'){
		require_once   'pages/timesheet.php'; 	  
    }
	else if($section == 'vendor-history'){
		require_once   'pages/vendorHistory.php'; 	  
    }
	else if($section == 'setting'){
		require_once   'pages/setting.php'; 	  
    }
	else if($section == 'forgot-password'){
		require_once   'pages/forget-pass.php';  	  
    }
	else if($section == 'all-closed-jobs'){ 
		require_once   'pages/allclosedjobs.php';   	  
    }
	else if($section == 'invoices'){ 
		require_once   'pages/invoices.php';   	  
    }
	else 
	{
	?>
	<script>window.location.href="<?php echo SITE_URL; ?>?section=login";</script>
	<?php	
	}	
   
}

function job_timecard($job_id)
{
	$sql="SELECT * FROM  `timecard` WHERE  `job_id` ='".$job_id."' ORDER BY id DESC LIMIT 0 , 1";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		return $row['cr_date'];
	}	
}


function time_elapsed_string($ptime)
{
    // Past time as MySQL DATETIME value
    $ptime = strtotime($ptime);

    // Current time as MySQL DATETIME value
    $csqltime = date('Y-m-d H:i:s');

    // Current time as Unix timestamp
    $ctime = strtotime($csqltime); 

    // Elapsed time
    $etime = $ctime - $ptime;

    // If no elapsed time, return 0
    if ($etime < 1){
        return '0 seconds';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                          
    );

    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days'
                       
    );

    foreach ($a as $secs => $str){
        // Divide elapsed time by seconds
        $d = $etime / $secs;
        if ($d >= 1){
            // Round to the next lowest integer 
            $r = floor($d);
            // Calculate time to remove from elapsed time
            $rtime = $r * $secs;
            // Recalculate and store elapsed time for next loop
            if(($etime - $rtime)  < 0){
                $etime -= ($r - 1) * $secs;
            }
            else{
                $etime -= $rtime;
            }
            // Create string to return
            $estring = $estring . $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ';
        }
    }
    return $estring . ' ago';
}

/*New functions*/
function total_work_by_staff($job_id)
{
	$sql="SELECT *
	FROM  `timecard` 
	WHERE job_id =  '".$job_id."'"; 
	$res=mysql_query($sql);
	$count=mysql_num_rows($res);
	if($count > 0)
	{	
		while($row=mysql_fetch_array($res))
		{
			$arr[]=$row['id'];
		}
		$job="";
		foreach($arr as $val)
		{
			$job .=$val.",";
		}
		$job_idd=substr($job,0,-1);
		
		$sql_1="SELECT SUM( hours )  'total'
		FROM  `timecard_staff` 
		WHERE card_id
		IN (".$job_idd.") 
		GROUP BY card_id";
		$res1=mysql_query($sql_1);
		while($row1=mysql_fetch_array($res1))
		{
			return $row1['total'];
		}
		
	}	
	else
	{
		
	}	
}  

function job_hour_remaining($job_id)
{
	$bud=get_customer_job_info($job_id,'budget_hours');
	$total_work=total_work_by_staff($job_id);
	if($bud=="")
	{
		return "(Not Assigned)"; 
	}	
	else
	{
		return $bud-$total_work ." Hours";
	}	
}

?>