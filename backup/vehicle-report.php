<?php
require 'setup.php';
$today=date('Y-m-d');

$file_name='asd';
$file_name=str_replace(' ','-',$file_name).'.pdf'; 

$vehicle_id=$_GET['id']; 

$vehicle_num=get_vehicle_info($vehicle_id,'vehicle_num');
$cr_date=get_vehicle_info($vehicle_id,'cr_date');


$first_date = date('Y-m-d', strtotime('first day of this month'));
$last_date = date('Y-m-d', strtotime('last day of this month'));

$first_date=date('F j, Y', strtotime($first_date));
$last_date=date('F j, Y', strtotime($last_date));
$html = '
<head>
<style>
@import "https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i";


.logo img{
	margin:auto;
	width:517px;
	text-align:center;
}

h2{
font-size:18px;
color:#000;
margin:0 0 0px;
text-align:center;
padding:0;
}
h5{
font-weight:500;
font-size:16px;
color:#000;
margin:15px 0 0;
text-align:center;
padding:0;
}

h4{
font-weight:500;
font-size:16px;
color:#000;
margin:0px 0 0;
text-align:right;
padding:0;
}
table th{

}

.table{
margin:45px 0 0;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size:16px;
    margin:10px 0 0;
}

table tr th, table tr td {
    border: 1px solid #828282;
    padding: 8px 15px;
}

table tr th {
    background-color: #eaeaea;
}
</style>
</head>
<body style="font-family: Open Sans, sans-serif;font-size:30px;">
<div class="logo" style="text-align: center;display:inline-block;width:100%;margin:0 0 30px;">
             <img alt="" src="http://i.imgur.com/36muAXV.jpg">
</div>

<h2>VEHICLE MILEAGE REPORT - '.get_vehicle_info($vehicle_id,'model').' '.get_vehicle_info($vehicle_id,'make').'</h2>
<h5>'.$first_date.' - '.$last_date.'</h5>
<div class="table">
    <h4>Starting Odometer - 100,000    Ending Odometer - 125,000 </h4>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>KM</th>
                </tr>
            </thead>
            <tbody>';
                $sql="select * from timecard_vehicle where vehicle_id='".$vehicle_id."'";
				$res=mysql_query($sql);
				while($row=mysql_fetch_array($res))
				{	
					$cr_date=$row['cr_date'];
					$cr_date=date('d/m/y', strtotime($cr_date));
					$descp=$row['descp'];
					$km=$row['km'];
					$card_id=$row['card_id'];
					$job_id=get_timecard_info($card_id,'job_id');
					$cust_id=get_customer_job_info($job_id,'cust_id');
					$fname=get_cust_detail($cust_id,'first_name');
					$lname=get_cust_detail($cust_id,'last_name');
					$cmp=get_cust_detail($cust_id,'company');
					if($cmp=="")
					{
						$user_det=$fname.' '.$lname;
					}	
					else
					{
						$user_det=$cmp;
					}	
				$html .='<tr>
                    <td>'.$cr_date.'</td>
                    <td>'.$user_det.' - '.$descp.'</td>
                    <td>'.$km.'</td>
                </tr>';
				}
			$html .='</tbody>
        
        </table>
    

</div>
</body>';

echo $html;
die;

//==============================================================
//==============================================================
//==============================================================
include("pdf/mpdf.php");

$mpdf=new mPDF('c','A4','','',32,25,27,25,16,13);  

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
//$stylesheet = file_get_contents('mpdfstyletables.css');
//$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html);

$mpdf->Output($file_name,'D');
exit;


?>
