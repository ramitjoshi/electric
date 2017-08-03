<?php
require '../setup.php';

$today=date("Y-m-d H:i:s");
$sql="select * from job_last_updated where status='1' and last_email_sent <'".date("Y-m-d H:i:s", strtotime ("-24 hours"))."' and last_updated <'".date("Y-m-d H:i:s", strtotime ("-48 hours"))."'";
$res=mysql_query($sql);
$count=mysql_num_rows($res);    
if($count > 0)
{	
	while($row=mysql_fetch_array($res))
	{
		$job_id=$row['job_id'];
		$job_name .=get_customer_job_info($job_id,'name');	
		$job_name .=', ';  	

	}	
	$message=' 

	<p>Dear Terry,</p>
	<p>No updates have been saved for '.$job_name.' in the past 48 hours. </p> 
	<p>Please login: <a href="https://algonquinelectrical.pro" target="_blank">www.algonquinelectrical.pro</a></p> 
	';	


	$subject='Job Reminder | '.$job_name;
	$headers  = 'MIME-Version: 1.0' . "\r\n"; 
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	$headers .= 'From: Algonquin Electrical <noreply@algonquinelectrical.pro>' . "\r\n";

	mail('algonquinelectrical@gmail.com', $subject, $message, $headers); 
	//mail('daniel@creativeone.ca', $subject, $message, $headers);
	//mail('ramit@imarkinfotech.com', $subject, $message, $headers); 
		 
	$upp="update job_last_updated set last_updated='".$today."',last_email_sent='".$today."' where job_id='".$job_id."'";
	$res_upp=mysql_query($upp);          
 
}	

	
?> 