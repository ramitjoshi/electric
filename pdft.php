<?php
require 'setup.php';
if(!isset($_SESSION['user_id']))
{
?>
<script>window.location.href="<?php echo SITE_URL; ?>?section=login";</script>
<?php
die;
}


$staff_id=$_REQUEST['staff_id'];

$curr_monday=$_REQUEST['mon'];
$curr_sunday=$_REQUEST['sun'];

$monday = $curr_monday;
$saturday = $curr_sunday;	

$today=date('Y-m-d');

$file_name=date('is').'timesheet.pdf';  


$html .= '

<head>
<style>
@import "https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i";
.clndr_lft {
    float: left;
    position: relative;
    width: 19.1%;
}
.clndr_rt {
    background: #fff none repeat scroll 0 0;
    float: right;
    width: 79.2%;
}


.calndr_cont {
    margin-top: 25px;
}
.table_calndr th {
    font-size: 22px;
    padding: 13px 0;
    text-align: center;
    width: 171px;
	 background-color: #f2f2f2;
	 color: #fff;
}
.table_calndr td {
    color: #5b5b5b;
    font-size: 20px;
    padding: 9px 0;
    text-align: center;
}

.total_hrs {
    display: inline-block;
    vertical-align: top;
    width: 100%;
}
.total_hrs table {
    float: right;
}
.clndr_rt .date {
    color: #1f1f1f;
}
.clndr_rt .recen_cont {
    background-color: #ebebeb;
}
.mn_clndr {
    margin: 0;
}
.table_calndr tr {
    border-bottom: 1px solid #ccc;
}
.table_calndr tr:last-child {
    border: 0 none;
}
.historical-invoides table {
    margin: 0;
}
.historical-invoides h4 {
    margin-top: 0;
}.clndr_lft {
  background-color: #ebebeb;
  float: left;
  height: 80px;
  padding: 11px 0px 0 11px;
  position: relative;
  width: 18%;
}
.clndr_lft strong{
	font-size:20px;
	float:left;
	padding:0 20px 0 30px;
	
}
.inner_pdng {
  padding: 7px 13px;
}
.loop_start{
	margin-bottom:10px;
}

.btm-div {
    float: right;
    margin: 23px 0 0;
    width: 500px;

}
.signature {
  border-top: 1px solid;
  float: left;
  margin: 0;
  text-align: right;
  width: 60%;
}
.last {
  float: right;
  margin: 0;  width: 35%;  margin: 0;
  text-align: right;  border-top: 1px solid;
}
.logo{
	text-align: center;
	display:block;
	width:100%;
	margin:30px 0;
}
.logo img{
	width:450px;
}

</style>
</head>
<body style="font-family: Open Sans, sans-serif;">
	<div style="padding-top:20px;"><div class="logo">
		   <img alt="" src="http://i.imgur.com/36muAXV.jpg" style="display:block; margin:0 auto; width:450px;">
	</div>
	<table width="100%">
	<tbody>
		<tr>
			<td align="center"><h3>Timesheet Report</h3></td>
			</tr>
			<tr>
			<td align="center" style="padding-bottom:20px;">'.date('M', strtotime($monday)).' '.date('d', strtotime($monday)).'-'.date('d', strtotime($saturday)).', '.date('Y', strtotime($saturday)).'</td>
		</tr>
	</tbody>


    </table>
		<div class="display_block mn_clndr">';
		//$sql="select distinct staff_id from timecard_staff where staff_id in (".$staff_id.") order by last_name ASC";
		//$sql="select distinct staff_id from timecard_staff where staff_id in (".$staff_id.") order by last_name ASC";
		$sql="select * from users where id!=1 and id in (".$staff_id.") order by last_name ASC";
		$res=mysql_query($sql); 
		while($row=mysql_fetch_array($res))
		{	
			$staff_id=$row['id'];
			$count=0;
$html .='<div class="loop_start" style="margin:0 30px 25px; padding:0 0 10px; border-bottom:1px solid #e5e5e5;"> 
			<div class="clndr_lft">
				<strong>'.get_user_detail($staff_id,'first_name').' '.get_user_detail($staff_id,'last_name').'</strong>
			</div> 
			<div class="clndr_rt">
				<div class="recen_cont display_block" style="margin:0 2px;">
					<div class="inner_pdng">
					<span class="date pull-left">'.date('M', strtotime($monday)).' '.date('d', strtotime($monday)).'-'.date('M', strtotime($saturday)).' '.date('d', strtotime($saturday)).', '.date('Y', strtotime($saturday)).'</span></div>  
				</div>
				<div class="table_calndr">
					<table cellspacing="0" cellpadding="0" border="0"> 
						<thead>
						<tr>';
						
						$datePeriod = returnDates($monday, $saturday);
						foreach($datePeriod as $date) {
						$date_d=$date->format('Y-m-d');	
						$html .='<th style="color:#5b5b5b;">'.date('D', strtotime($date_d)).'</th>';
						}
						$html .='</tr>
						</thead>
						<tbody>
							<tr>';
							$datePeriod = returnDates($monday, $saturday);
							foreach($datePeriod as $date) 
							{
							$datee=$date->format('Y-m-d');	
							$total=get_sum_hours($datee,$staff_id);
							$count=$count+$total;
							
							$html .='<td>'.get_sum_hours($datee,$staff_id).' Hours</td>';
							}	
							$html .='</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td><strong>TOTAL</strong></td>
								<td><strong>'.$count.' Hours</strong></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		</div>';
		}	
		
		
	$html .='<div class="btm-div" style="padding:0 30px;">
		<div class="signature">
		  <span>Signature</span>
		</div> 
		<div class=" last">
		  <span>Date</span>
		</div>'; 
	
	$html .='</div></div>
</body>
';

//==============================================================
//==============================================================
//==============================================================
include("pdf/mpdf.php");

$mpdf=new mPDF('utf-8', 'A4-L'); 


$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 1;	// 1 or 0 - whether to indent the first level of a list

// LOAD a stylesheet
//$stylesheet = file_get_contents('mpdfstyletables.css');
//$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html);

$mpdf->Output($file_name,'D');
exit;


?>