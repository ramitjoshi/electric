<?php
require 'setup.php';
$job_id=$_GET['job_id'];
$sql="select * from customer_job where id='".$_GET['job_id']."'";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$name=$row['name'];
	$cust_id=$row['cust_id'];
}	


$sql_g="select * from report_gen where job_id='".$_GET['job_id']."' and  user_id='".$_GET['user_id']."'";
$res_g=mysql_query($sql_g);
while($row=mysql_fetch_array($res_g))
{
	$summary=$row['summary'];
} 
 



$today=date('Y-m-d');

$file_name=$name.$_GET['job_id'].$_GET['user_id'];
$file_name=str_replace(' ','-',$file_name).'.pdf'; 


$html = '
<head>
<style>
@import "https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i";


.logo img{
	margin:auto;
	width:517px;
	text-align:center;
}
h2 {
  margin: 0;
}
.configure{
		display:inline-block;
	width:100%;
	vertical-align:top;
	margin:18px 0 0;
}
.configure-details{
	display:inline-block;
	width:100%;
	vertical-align:top;
}
.configure-details div{
	color:#5c5c5c;
}
.report, .close_lst{
	margin:25px 0 0 0;
}
.tim_left {
    width: 22.4%;
    position: relative;
	float:left;
}
.tim_rt {
    color: #5b5b5b;
    font-size: 14px;
    width: 77.6%;float:left;
}
.clndr_lft {
  background-color: #ebebeb;
  float: left;
  height: 80px;
  padding: 11px 0px 0 11px;
  position: relative;
  width: 18%;
}
.clndr_rt {
    background: #fff none repeat scroll 0 0;
    float: left;
    width: 82%;
}
.clndr_lft strong{
	font-size:13px;
	float:left;
	padding:0 20px 0 30px;
	
}
.clndr_rt {
    background: #fff none repeat scroll 0 0;
    float: right;
    width: 79.2%;
}
.clndr_rt tr{
border:1px solid #cccccc;
border-top:0;
border-left:0;
border-right:0;	
}


.clndr_rt td {
    color: #5b5b5b;
    font-size: 14px;
    padding: 5px;
    text-align: center;
	font-weight:normal;
}
.clndr_rt td:last-child {border-bottom:1px solid #cccccc;}

.timecard_box{
	margin:30px 0 0 0;
}
.imag_inr {
float:left;
width:32.5%;
margin:0 0 0 3px;
}
.close_lst img{
	width:100%;
}
.image_clos, .imag_info, .close_lst{
	display:inline-block;
	width:100%;
	vertical-align:top;
}
.imag_info p{
	font-size:14px;
  color: #5b5b5b;
  text-align:center;
  width:100%;
  margin: 10px 0 0 0;
  padding:0 4px;
}
</style>
</head>
<body style="font-family: Open Sans, sans-serif;font-size:30px;">
<div class="logo" style="text-align: center;display:inline-block;width:100%;margin:0 0 30px;">
             <img alt="" src="http://i.imgur.com/36muAXV.jpg">
</div>
<div class="configure">
	<h2 style="display:inline-block;vertical-align:top;font-size:16px;width:100%;border:0;color:#000000;margin:0 0 6px 0;">'.$name.'</h2>

<div class="configure-details">
	<p style="width:100%;font-size:13px;">'.get_cust_detail($cust_id,'company').'</p><p style="width:100%;font-size:13px;">'.get_cust_detail($cust_id,'work_add1').' '.get_cust_detail($cust_id,'work_add2').'</p><p style="width:100%;font-size:13px;">'.get_cust_detail($cust_id,'work_city').' '.get_cust_detail($cust_id,'work_prov').' '.get_cust_detail($cust_id,'work_post_code').'</p> 
	<div style="float:right;font-size:13px;width:28%;text-align:right;">'.date('M d, Y', strtotime($today)).'</div>
</div>	 

</div>
<div class="configure report">
	<h2 style="display:inline-block;vertical-align:top;font-size:16px;width:100%;border:0;color:#000000;margin:0 0 6px 0;">REPORT SUMMARY</h2>

<div class="configure-details">
	<div style="float:left;font-size:13px;border:0;width:100%">'.$summary.'</div><hr style="margin:10px 0 16px 0;">
</div>	

</div>
<h2 style="display:inline-block;vertical-align:top;font-size:16px;width:100%;border:0;color:#000000;margin:0 0 6px 0;">DAY-BY-DAY PROGRESS</h2>';
		$sql="select * from timecard where job_id='".$job_id."' order by cr_date desc";
		$res=mysql_query($sql);
		$count=mysql_num_rows($res);
		if($count > 0)
		{
			while($row=mysql_fetch_array($res))
			{
				$id=$row['id'];
				$photos_1=$row['photos_1'];
				$photos_2=$row['photos_2'];
				$photos_3=$row['photos_3'];
				$notes=$row['notes']; 
				$cr_date=$row['cr_date'];
				$insert_by=$row['insert_by'];
					
		$html .='<div class="configure timecard_box">
		
			
			<div class="clndr_lft">
				<strong>'.date('M d, Y', strtotime($cr_date)).'</strong>
			</div>
			<div class="clndr_rt">
				<table style="width:100%">
					<tbody style="width:100%">';
						$staff_sql="select * from timecard_staff where card_id='".$id."'";
						$staff_res=mysql_query($staff_sql);
						while($row_staff=mysql_fetch_array($staff_res))
						{
						$html .='<tr>
							<td style="width:25%;text-align:left; border-top:1px solid #cccccc;">'.get_user_detail($row_staff['staff_id'],'first_name').' '.get_user_detail($row_staff['staff_id'],'last_name').'</td>
							<td style="width:75%;text-align:left; border-top:1px solid #cccccc;">'.$row_staff['hours'].' Hours</td>
						</tr>';  
						}
						/*
						$html .='<tr>
						<td style="padding: 17px 3%; border-bottom: 1px solid #ccc;" colspan="2">
						<b>NOTES</b>
						<p>'.$notes.'</p>  
						</td>
						';
						*/
					$html .='</tbody>
				</table>

			</div>
		</div>
		<div class="close_lst btm_cls">';
			if($photos_1!='')
			{	
			$html .='<div class="imag_inr">
			   <div class="image_clos">
					<img src="timecard/'.$photos_1.'" width="320"  />
				</div>
				<div class="imag_info"> 
					<p>'.$row['photos_1_caption'].'</p>
				</div>
			</div>';
			}
			if($photos_2!='')
			{	
			$html .='<div class="imag_inr">
			   <div class="image_clos">
					<img src="timecard/'.$photos_2.'" width="320" />
				</div>
				<div class="imag_info">
					<p>'.$row['photos_2_caption'].'</p>
				</div>
			</div>';
			}
			if($photos_3!='') 
			{	
			$html .='<div class="imag_inr">
			   <div class="image_clos">
					<img src="timecard/'.$photos_3.'" width="320" />  
				</div>
				<div class="imag_info">
					<p>'.$row['photos_3_caption'].'</p>
				</div>
			</div>';
			}
			
        $html .='</div>';
		}
	}		
$html .='</body>
';

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