<?php



$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Algonquin Electrical Services</title>
    <link rel="icon" href="favicon.ico" sizes="16x16">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/bootstrap-select.css">
    <link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
	<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
</head>
<body>
<div class="main display_block">		<div class="lft_sidebar pull-left">
	<div class="logo text-center">
		<a href="http://algonquinelectrical.pro/"><img src="assets/images/logo.jpg" alt="logo"></a>
	</div>
		<nav>
		<ul>
			
			<li class=""><a href="http://algonquinelectrical.pro/?section=dashboard">Dashboard</a></li>
			<li class=""><a href="http://algonquinelectrical.pro/?section=customer">Customers</a></li>
			<li class=""><a href="http://algonquinelectrical.pro/?section=vendors">Vendors</a></li>
			<li class=""><a href="http://algonquinelectrical.pro/?section=staff">Staff</a></li> 
			
			
			<li class="active"><a href="http://algonquinelectrical.pro/?section=timesheet">Timesheets</a></li>
			<!-- 
			<li><a href="">Settings</a></li>
			-->
			<li class="" ><a href="http://algonquinelectrical.pro/?section=invoice">Manual Invoices</a></li> 
			<li class="" ><a href="http://algonquinelectrical.pro/?section=closed-jobs">Closed Jobs</a></li> 
			<li class="" ><a href="http://algonquinelectrical.pro/?section=assigned-invoices">Assigned Invoices</a></li> 
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</nav>
		
</div>		        <div class="rt_sidebar pull-left">
            <div class="btns_top display_block">
                <div class="btns_lft pull-left">
                    <a href="http://algonquinelectrical.pro/?section=dashboard" class="custom">Dashboard</a>
                    <a href="#" class="custom">New Report</a>
                </div>
                <div class="btns_rt pull-right">
                    <input type="text" placeholder="Search">
                    <a href=""><i class="fa fa-search" aria-hidden="true"></i></a>
                </div>
            </div>
            <div class="timeshts_cont display_block">
                <div class="recen_cont display_block">
                    <div class="inner_pdng">
                        <span class="date pull-right">September 12, 2016 – September 18, 2016</span></div>
                </div>
                <div class="calndr_cont display_block">
					                    <div class="display_block mn_clndr">
                        <div class="clndr_lft">
                            <span></span>
                            <strong>Julius Pukler</strong>
                        </div>
                        <div class="clndr_rt">
                            <div class="recen_cont display_block">
                                <div class="inner_pdng">
                                    <span class="date pull-left">Sep 12-18, 2016</span></div> 
                            </div>
                            <div class="table_calndr">
                                <table>
                                    <thead>
                                        										<th>Mon</th>
																				<th>Tue</th>
																				<th>Wed</th>
																				<th>Thu</th>
																				<th>Fri</th>
																				<th>Sat</th>
																				<th>Sun</th>
										
                                    </thead>
                                    <tbody>
                                        <tr>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                                                                    </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>TOTAL</strong></td>
                                            <td><strong>0 Hours</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                                        <div class="display_block mn_clndr">
                        <div class="clndr_lft">
                            <span></span>
                            <strong>Michael Nicholls</strong>
                        </div>
                        <div class="clndr_rt">
                            <div class="recen_cont display_block">
                                <div class="inner_pdng">
                                    <span class="date pull-left">Sep 12-18, 2016</span></div> 
                            </div>
                            <div class="table_calndr">
                                <table>
                                    <thead>
                                        										<th>Mon</th>
																				<th>Tue</th>
																				<th>Wed</th>
																				<th>Thu</th>
																				<th>Fri</th>
																				<th>Sat</th>
																				<th>Sun</th>
										
                                    </thead>
                                    <tbody>
                                        <tr>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                                                                    </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>TOTAL</strong></td>
                                            <td><strong>0 Hours</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                                        <div class="display_block mn_clndr">
                        <div class="clndr_lft">
                            <span></span>
                            <strong>Jack Gaughan</strong>
                        </div>
                        <div class="clndr_rt">
                            <div class="recen_cont display_block">
                                <div class="inner_pdng">
                                    <span class="date pull-left">Sep 12-18, 2016</span></div> 
                            </div>
                            <div class="table_calndr">
                                <table>
                                    <thead>
                                        										<th>Mon</th>
																				<th>Tue</th>
																				<th>Wed</th>
																				<th>Thu</th>
																				<th>Fri</th>
																				<th>Sat</th>
																				<th>Sun</th>
										
                                    </thead>
                                    <tbody>
                                        <tr>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                                                                    </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>TOTAL</strong></td>
                                            <td><strong>0 Hours</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                                        <div class="display_block mn_clndr">
                        <div class="clndr_lft">
                            <span></span>
                            <strong>Dave Furgeson</strong>
                        </div>
                        <div class="clndr_rt">
                            <div class="recen_cont display_block">
                                <div class="inner_pdng">
                                    <span class="date pull-left">Sep 12-18, 2016</span></div> 
                            </div>
                            <div class="table_calndr">
                                <table>
                                    <thead>
                                        										<th>Mon</th>
																				<th>Tue</th>
																				<th>Wed</th>
																				<th>Thu</th>
																				<th>Fri</th>
																				<th>Sat</th>
																				<th>Sun</th>
										
                                    </thead>
                                    <tbody>
                                        <tr>
                                            											<td>3 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                                                                    </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>TOTAL</strong></td>
                                            <td><strong>3 Hours</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                                        <div class="display_block mn_clndr">
                        <div class="clndr_lft">
                            <span></span>
                            <strong>Marc Picard</strong>
                        </div>
                        <div class="clndr_rt">
                            <div class="recen_cont display_block">
                                <div class="inner_pdng">
                                    <span class="date pull-left">Sep 12-18, 2016</span></div> 
                            </div>
                            <div class="table_calndr">
                                <table>
                                    <thead>
                                        										<th>Mon</th>
																				<th>Tue</th>
																				<th>Wed</th>
																				<th>Thu</th>
																				<th>Fri</th>
																				<th>Sat</th>
																				<th>Sun</th>
										
                                    </thead>
                                    <tbody>
                                        <tr>
                                            											<td>20 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                                                                    </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>TOTAL</strong></td>
                                            <td><strong>20 Hours</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                                        <div class="display_block mn_clndr">
                        <div class="clndr_lft">
                            <span></span>
                            <strong>Terry Gaughan</strong>
                        </div>
                        <div class="clndr_rt">
                            <div class="recen_cont display_block">
                                <div class="inner_pdng">
                                    <span class="date pull-left">Sep 12-18, 2016</span></div> 
                            </div>
                            <div class="table_calndr">
                                <table>
                                    <thead>
                                        										<th>Mon</th>
																				<th>Tue</th>
																				<th>Wed</th>
																				<th>Thu</th>
																				<th>Fri</th>
																				<th>Sat</th>
																				<th>Sun</th>
										
                                    </thead>
                                    <tbody>
                                        <tr>
                                            											<td>6 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                            											<td>0 Hours</td>
                                                                                    </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td><strong>TOTAL</strong></td>
                                            <td><strong>6 Hours</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                                    </div>



            </div>
        </div>
 </div>
   
</body>

</html>		';


//==============================================================
//==============================================================
//==============================================================

include("../mpdf.php");

$mpdf=new mPDF('c'); 

$mpdf->SetDisplayMode('fullpage');

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyleA4.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->WriteHTML($html);

$mpdf->Output();

exit;
//==============================================================
//==============================================================
//==============================================================

?>