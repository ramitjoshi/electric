<?php
/*
$month  = date('m');
$year  = date('Y');
function getDays($year,$month){ 
 $days = cal_days_in_month(CAL_GREGORIAN, $month,$year);
 $wed = array();
 for($i = 1; $i<= $days; $i++){
 $day  = date('Y-m-'.$i);
 $result = date("D", strtotime($day));
 if($result == "Tue"){  
 $wed[] = date("Y-m-d", strtotime($day)). " ".$result."<br>";
 }  
}
 return  $wed;
}
$wed = getDays($year,$month);
echo "<pre>";
	print_r($wed);
echo "</pre>";

*/


// Set timezone
	
  $first_date = date('Y-m-d', strtotime('first day of this month'));
  $last_date = date('Y-m-d', strtotime('last day of this month'));
	// Start date 
	$date = $first_date;
	// End date
	$end_date = $last_date;

	while (strtotime($date) <= strtotime($end_date)) {
                $datearr[]=$date;
                $date = date ("Y-m-d", strtotime("+10 day", strtotime($date)));
	}
	
	
echo "<pre>";
	print_r($datearr);
echo "</pre>";	