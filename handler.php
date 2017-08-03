<?php
require_once  'setup.php';
$action=$_REQUEST['action'];

//get requested action
if(isset($_REQUEST['action']) && !empty($_REQUEST['action']))
{
   $action = $_REQUEST['action'];
   call_user_func($action);
}

function ValidLogin()
{
	$cr_date=date('Y-m-d');
	unset($_REQUEST['action']);
	$session_id = $_REQUEST['session_id'];
	$username = $_REQUEST['username'];
	$pwd = $_REQUEST['user_pass'];
	$user_pass=md5($pwd);
	$count=user_exist($username);
	
	$check_at="select * from login_attempts where session_id='".$session_id."' and cr_date='".$cr_date."'";  
	$res_check=mysql_query($check_at);
	$count_check=mysql_num_rows($res_check);
	
	if($count_check==0)
	{
		$ins="insert into login_attempts (session_id,attempt,cr_date) values ('".$session_id."','1','".$cr_date."')";
		$res_ins=mysql_query($ins);
	}
	else
	{		
		while($row_check=mysql_fetch_array($res_check))
		{
			$last_attmpt=$row_check['attempt'];
		}
		$asd=$last_attmpt + 1; 
		$up="update login_attempts set attempt='".$asd."' where session_id='".$session_id."' and cr_date='".$cr_date."'";	
		$res_up=mysql_query($up);  
	}
	
	$attemp=check_attempt($session_id,$cr_date); 
	
	
	if($count!=0)
	{
		$sql="select * from users where username='".$username."' and password='".$user_pass."'";
		
		$res=mysql_query($sql);
		$count=mysql_num_rows($res);
		if($count==0)
		{
			if($attemp > 3)
			{
				echo "5";
			}	
			else
			{	
				echo "1";
			}
		}	
		else
		{
			while($row=mysql_fetch_array($res))
			{	
				$status=$row['status'];
				if($status==0)
				{
					echo "0"; 
					die; 
				}	
				else
				{	
					$_SESSION['CREATED'] = date("Y-m-d H:i:s");
					$_SESSION['user'] = $username;
					$_SESSION['user_id'] = $row['id']; 
					
					$update="update users set last_logged_in='".$_SESSION['CREATED']."' where id='".$_SESSION['user_id']."'";
					$res_up=mysql_query($update); 
					
					$del="delete from login_attempts where session_id='".$session_id."'";
					$res_del=mysql_query($del);
					
				}
				
			}
			echo "3"; 
		}	
	}
	else
	{
		if($attemp > 3)
		{
			echo "5";
		}	
		else
		{	
			echo "2";
		}
	}		
	
}
function AddStaff() 
{
	unset($_REQUEST['action']);
	$fname=mysql_real_escape_string($_REQUEST['fname']);
	$lname=mysql_real_escape_string($_REQUEST['lname']);
	$email=$_REQUEST['email'];
	$phone=mysql_real_escape_string($_REQUEST['phone']);
	$role=$_REQUEST['role'];
	$username=mysql_real_escape_string($_REQUEST['username']);
	$user_pass=mysql_real_escape_string($_REQUEST['user_pass']);
	$user_pass=md5($_REQUEST['user_pass']);
	
	$count=user_exist($username);
	if($count==0)
	{
		
			$date=date("Y-m-d H:i:s");	
			$insert="insert into users (username,password,role,first_name,last_name,email,phone,status,last_logged_in,created) values ('".$username."','".$user_pass."','".$role."','".$fname."','".$lname."','".$email."','".$phone."','1','0000-00-00','".$date."')";
			$res=mysql_query($insert);
			echo "1"; 
			
	}	
	else
	{
		echo "0";
	}	
	
}	


function staffDel()
{
$id=$_REQUEST['id'];
$sql = "DELETE FROM users WHERE id ='".$id."'";
$res=mysql_query($sql);	
}

function staffEdit()
{
$id=$_REQUEST['id'];
$sql= "SELECT * FROM users WHERE id='".$id."'";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$role=$row['role'];
	$status=$row['status'];
?>
<script>
jQuery('#edit_staff').validate({
		
		rules: {
			fname: {
				required: true
			},
			lname: {
				required: true
			},
			email: {
				required: true,
				email: true
			},
			phone: {
				required: true
			},
			role: {
				required: true
			},
			
		},
			
		submitHandler: function(form) {
			jQuery('.result_edit').empty();
			jQuery('.result_edit').show();
			jQuery('#editSaff_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{
					jQuery('#editSaff_loader').hide();
					jQuery('.result_edit').empty().append('<div class="alert alert-success">Record updated</div>');
					jQuery('.result_edit').fadeOut(3000);	
					location.reload();
					
				}
			});
		}
		
	});
	
		jQuery('.staff-del').click(function()
		{
			
			var username= jQuery(this).attr("u");
			 
			if(confirm ("Are you sure you want to Delete: " +username+"?"))
			{	
							
				id=jQuery(this).attr("id");		
				jQuery.ajax({type: "POST",
				url: "handler.php",
				data: "id="+id+"&action=staffDel",
				success:function(result){
					location.reload();
				},
				error:function(e){
					console.log(e);
				}	
				}); 
			  
			}
			else 
			{
				  jQuery(this).parent().parent().removeClass("deletion ");
			return;  
			}
	 
	
		});
	
	
</script>	
<div class="new_custmer">
	<a href="javascript:void(0);" onclick="jQuery('#editFromToggle').slideUp();" class="close_new" style="margin-left: 10px;"><i class="fa fa-times" aria-hidden="true"></i></a>
	<a href="javascript:void(0)" u="<?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?>" id="<?php echo $row['id']; ?>" class="staff-del pull-right"> <i aria-hidden="true" class="fa fa-trash-o"></i> </a>
	<h4 class="close_title">EDIT <?php echo $row['first_name']. " ".$row['last_name']; ?></h4>
</div>
<div class="close_container custmr_edt_cnt">
	<form name="edit_staff" id="edit_staff" action="" method="post">
		<input type="hidden" name="staff_id" value="<?php echo $row['id']; ?>">
		<div class="cstm-wid top_inpt">
			<ul> 
				<li>
					<input type="text" name="fname" placeholder="First Name" value="<?php echo stripslashes($row['first_name']); ?>">
				</li>
				<li>
					<input type="text" name="lname" placeholder="Last Name" value="<?php echo stripslashes($row['last_name']); ?>">
				</li>
				<li>
					<input type="text" name="email" placeholder="Email address" value="<?php echo stripslashes($row['email']); ?>">
				</li>
				<li>
					<input type="text" name="phone" placeholder="Phone" value="<?php echo stripslashes($row['phone']); ?>">
				</li>
				<li>
					<select name="role">
						<option value="">Select role</option>
						<option value="admin" <?php if($role=="admin") { echo 'selected'; } ?>>Admin</option>
						<option value="staff" <?php if($role=="staff") { echo 'selected'; } ?>>Staff</option>
					</select> 
				</li>
				
			</ul>
		</div>
		
		<div class="btn_deit">
			<?php
			if($role=="staff")
			{
				if($status==1)
				{		
			?>
				<span class="freeze_sec_<?php echo $row['id']; ?>">
				<a href="javascript:void(0);" class="custom edit_cstm" onclick="freeze_user('0','<?php echo $row['id']; ?>');">Freeze</a>
				</span>
			<?php
				}
				else
				{
				?>
				<span class="freeze_sec_<?php echo $row['id']; ?>">
				<a href="javascript:void(0);" class="custom edit_cstm" onclick="freeze_user('1','<?php echo $row['id']; ?>');">Un Freeze</a> 
				</span>
				<?php	
				}	
			}
			?>
			<button type="submit" class="custom edit_cstm">Save</button>
			<img src="assets/images/loader.gif" id="editSaff_loader" style="display:none;" />
		</div> 
		<input type="hidden" name="action" value="EditStaff">
	</form> 
	<div class="result_edit"></div>
</div>
<?php		
}	
} 	


function EditStaff()
{
	unset($_REQUEST['action']);
	$staff_id=$_REQUEST['staff_id'];
	$fname=mysql_real_escape_string($_REQUEST['fname']);
	$lname=mysql_real_escape_string($_REQUEST['lname']);
	$email=$_REQUEST['email'];
	$phone=mysql_real_escape_string($_REQUEST['phone']);
	$role=$_REQUEST['role'];
	
	$update="update users set role='".$role."',first_name='".$fname."',last_name='".$lname."',email='".$email."',phone='".$phone."' where id='".$staff_id."'";
	$res=mysql_query($update);	 
}


function AddCustomer() 
{
	unset($_REQUEST['action']);
	$company=mysql_real_escape_string($_REQUEST['company']);
	$fname=mysql_real_escape_string($_REQUEST['fname']);
	$lname=mysql_real_escape_string($_REQUEST['lname']);
	$email=mysql_real_escape_string($_REQUEST['email']);
	$work_phone=mysql_real_escape_string($_REQUEST['work_phone']);
	$home_phone=mysql_real_escape_string($_REQUEST['home_phone']);
	$mobile_phone=mysql_real_escape_string($_REQUEST['mobile_phone']);
	
	$work_name=mysql_real_escape_string($_REQUEST['work_name']);
	$work_add1=mysql_real_escape_string($_REQUEST['work_add1']);
	$work_add2=mysql_real_escape_string($_REQUEST['work_add2']);
	$work_city=mysql_real_escape_string($_REQUEST['work_city']);
	$work_prov=mysql_real_escape_string($_REQUEST['work_prov']);
	$work_post_code=mysql_real_escape_string($_REQUEST['work_post_code']);
	
	$home_name=mysql_real_escape_string($_REQUEST['home_name']);
	$home_add1=mysql_real_escape_string($_REQUEST['home_add1']);
	$home_add2=mysql_real_escape_string($_REQUEST['home_add2']);
	$home_city=mysql_real_escape_string($_REQUEST['home_city']);
	$home_prov=mysql_real_escape_string($_REQUEST['home_prov']);
	$home_post_code=mysql_real_escape_string($_REQUEST['home_post_code']);  
	
	$note=mysql_real_escape_string($_REQUEST['note']);   
	 
	$sql="INSERT INTO customer (company,first_name ,last_name ,email ,work_phone ,home_phone ,mobile_phone ,work_name ,work_add1 ,work_add2 ,work_city ,work_prov ,work_post_code ,home_name ,home_add1 ,home_add2 ,home_city ,home_prov ,home_post_code ,note) VALUES ('".$company."','".$fname."','".$lname."','".$email."','".$work_phone."','".$home_phone."','".$mobile_phone."','".$work_name."','".$work_add1."','".$work_add2."','".$work_city."','".$work_prov."','".$work_post_code."','".$home_name."','".$home_add1."','".$home_add2."','".$home_city."','".$home_prov."','".$home_post_code."',  '".$note."')"; 
	$res=mysql_query($sql); 
	echo "1";
	
	
}

function EditCustomer()
{
	unset($_REQUEST['action']);
	$id=$_REQUEST['id'];
	
	$company=mysql_real_escape_string($_REQUEST['company']);
	$fname=mysql_real_escape_string($_REQUEST['fname']);
	$lname=mysql_real_escape_string($_REQUEST['lname']);
	$email=mysql_real_escape_string($_REQUEST['email']);
	$work_phone=mysql_real_escape_string($_REQUEST['work_phone']);
	$home_phone=mysql_real_escape_string($_REQUEST['home_phone']);
	$mobile_phone=mysql_real_escape_string($_REQUEST['mobile_phone']);
	
	$work_name=mysql_real_escape_string($_REQUEST['work_name']);
	$work_add1=mysql_real_escape_string($_REQUEST['work_add1']);
	$work_add2=mysql_real_escape_string($_REQUEST['work_add2']);
	$work_city=mysql_real_escape_string($_REQUEST['work_city']);
	$work_prov=mysql_real_escape_string($_REQUEST['work_prov']);
	$work_post_code=mysql_real_escape_string($_REQUEST['work_post_code']);
	
	$home_name=mysql_real_escape_string($_REQUEST['home_name']);
	$home_add1=mysql_real_escape_string($_REQUEST['home_add1']);
	$home_add2=mysql_real_escape_string($_REQUEST['home_add2']);
	$home_city=mysql_real_escape_string($_REQUEST['home_city']);
	$home_prov=mysql_real_escape_string($_REQUEST['home_prov']);
	$home_post_code=mysql_real_escape_string($_REQUEST['home_post_code']);  
	
	$note=$_REQUEST['note'];   
	 
	$sql="UPDATE customer SET  company='".$company."',first_name =  '".$fname."',last_name = '".$lname."',email =  '".$email."',work_phone =  '".$work_phone."',home_phone =  '".$home_phone."',mobile_phone ='".$mobile_phone."',work_name =  '".$work_name."',work_add1 ='".$work_add1."',work_add2 = '".$work_add2."',work_city ='".$work_city."',work_prov = '".$work_prov."',work_post_code = '".$work_post_code."',home_name =  '".$home_name."',home_add1 ='".$home_add1."',home_add2 = '".$home_add2."',home_city =  '".$home_city."',home_prov = '".$home_prov."',home_post_code = '".$home_post_code."',note =  '".$note."' WHERE id ='".$id."'";
	$res=mysql_query($sql);
	echo "1";  
}

function customerDel()
{
$id=$_REQUEST['id'];
$sql = "DELETE FROM customer WHERE id ='".$id."'";
$res=mysql_query($sql);	
}

function customerEdit()
{
$id=$_REQUEST['id'];   
$sql="select * from customer where id='".$id."'";
$res=mysql_query($sql);	
while($row=mysql_fetch_array($res))
{	
?>
<script>
jQuery('#edit_customer').validate({
		
		rules: {
			fname: {
				required: true
			},
			lname: {
				required: true
			},
			
			
		},
			
		submitHandler: function(form) {
			jQuery('.edit_result').empty();
			jQuery('.edit_result').show();
			jQuery('#edit_cust_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					jQuery('#edit_cust_loader').hide();
					if(data==1)
					{
						jQuery('.edit_result').empty().append('<div class="alert alert-success">Customer Saved</div>');
						jQuery('.edit_result').fadeOut(3000); 
						location.reload(); 
					}	
					
				}
			});
		}
		
	});
	
		jQuery('.cust-del').click(function()
		{
			var username= jQuery(this).attr("u");
			if(confirm ("Are you sure you want to Delete: " +username+"?"))
			{	
							
				id=jQuery(this).attr("id");		
				jQuery.ajax({type: "POST",
				url: "handler.php",
				data: "id="+id+"&action=customerDel",
				success:function(result){
					location.reload(); 
				},
				error:function(e){
					console.log(e);
				}	
				}); 
			  
			}
			else 
			{
				  jQuery(this).parent().parent().removeClass("deletion ");
			return;  
			}
	 
	
		});
	
</script>
<div class="new_custmer">
<a class="close_new" onclick="jQuery('.edit_cust_box').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
<a class="cust-del pull-right" id="<?php echo $id; ?>" u="<?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?>" href="javascript:void(0)"> <i class="fa fa-trash-o"></i></a>
<h4 class="close_title">EDIT CUSTOMER</h4>
</div>
<div class="close_container custmr_edt_cnt">
<form name="edit_customer" id="edit_customer" action="" method="post"> 
	<input type="hidden" name="action" value="EditCustomer">	
	<input type="hidden" name="id" value="<?php echo $id; ?>">	
	<div class="cstm-wid top_inpt">
		<div class="edit_table vndr_cnt customrs_cont">
            <ul class="cmpny_nm">
			<li>
				<input type="text" name="company" placeholder="Company" value="<?php echo stripslashes($row['company']); ?>">
			</li>
			</ul>
			<ul>     
			<li>
				<input type="text" name="fname" placeholder="First Name" value="<?php echo stripslashes($row['first_name']); ?>">
			</li>
			<li>
		
				<input type="text" name="lname" placeholder="Last Name" value="<?php echo stripslashes($row['last_name']); ?>">
			</li>
			<li>
		
				<input type="text" name="email" placeholder="Email address" value="<?php echo stripslashes($row['email']); ?>">
			</li>
			</ul>
		</div>

	<div class="cstm-wid btm_inpt">
		<div class="col-1">
			<h4></h4>
			<ul>

				<li>
					<input type="text" name="work_phone" placeholder="Work Phone No" value="<?php echo stripslashes($row['work_phone']); ?>">
				</li>
				<li>
					<input type="text" name="home_phone" placeholder="Home Phone No" value="<?php echo stripslashes($row['home_phone']); ?>">
				</li>
				<li>
					<input type="text" name="mobile_phone" placeholder="Mobile Phone No" value="<?php echo stripslashes($row['mobile_phone']); ?>">
				</li>
				<li>
				<textarea name="note" placeholder="Notes"><?php echo stripslashes($row['note']); ?></textarea> 
				</li>
			</ul>
		</div>
		<div class="col-1">
			<h4> Work</h4>
			<ul>
				<!--
				<li>
					<input type="text" placeholder="First Name" name="work_name" value="<?php echo stripslashes($row['work_name']); ?>">
				</li>
				-->
				<li>
					<input type="text" placeholder="Address" name="work_add1" value="<?php echo stripslashes($row['work_add1']); ?>">
				</li>
				
				<li>
					<input type="text" placeholder="Address 2" name="work_add2" value="<?php echo stripslashes($row['work_add2']); ?>"> 
				</li>
				<li>
					<input type="text" placeholder="City" name="work_city" value="<?php echo stripslashes($row['work_city']); ?>">
				</li>
				<li>
					<input type="text" placeholder="Province" name="work_prov" value="<?php echo stripslashes($row['work_prov']); ?>">
				</li>
				<li>
					<input type="text" placeholder="Postal Code" name="work_post_code" value="<?php echo stripslashes($row['work_post_code']); ?>">
				</li>
			</ul>
		</div>
		<div class="col-1">
			<h4> Home</h4>
			<ul>
				<!--
				<li>
					<input type="text" placeholder="First Name" name="home_name" value="<?php echo stripslashes($row['home_name']); ?>">
				</li>
				-->
				<li>
					<input type="text" placeholder="Address" name="home_add1" value="<?php echo stripslashes($row['home_add1']); ?>">
				</li>
				
				<li>
					<input type="text" placeholder="Address 2" name="home_add2" value="<?php echo stripslashes($row['home_add2']); ?>"> 
				</li>
				<li>
					<input type="text" placeholder="City" name="home_city" value="<?php echo stripslashes($row['home_city']); ?>">
				</li>
				<li>
					<input type="text" placeholder="Province" name="home_prov" value="<?php echo stripslashes($row['home_prov']); ?>">
				</li>
				<li>
					<input type="text" placeholder="Postal Code" name="home_post_code" value="<?php echo stripslashes($row['home_post_code']); ?>"> 
				</li>
			</ul>
		</div>
		
	</div>
	<div class="btn_deit">
		
		<button type="submit" class="custom edit_cstm" name="submit">Save</button>
		<img id="edit_cust_loader" src="assets/images/loader.gif" style="display:none;" />
	</div>
</form>
<div class="edit_result result_edit"></div>
<?php	
}
}

function customerShow()
{
$id=$_REQUEST['id'];   
$sql="select * from customer where id='".$id."'";
$res=mysql_query($sql);	
while($row=mysql_fetch_array($res))
{	
?>
<div class="new_custmer">
<a class="close_new" onclick="jQuery('.show_cust_box').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
<h4 class="close_title">CUSTOMER</h4>
</div>
<div class="close_container custmr_edt_cnt">
<div class="customers_container view_cstmr">
	<div class="close_mn display_block">
	<div class="cstm-wid top_inpt edit_table vndr_cnt customrs_cont">
	<div class="responsive-table">
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
			<table class="table brdr brdr_none margin_non not-responsive">
				<tbody>
					<tr>
						<td style="width: 3%;"></td>
						<td style="width: 18%;"><?php echo $row['first_name']; ?></td>
						<td style="width: 19%;"><?php echo $row['last_name']; ?></td>
						<td style="width: 35%;"><?php echo $row['email']; ?></td>
						<td style="width: 15%;"><?php echo $row['mobile_phone']; ?></td>
						<td style=" width: 10%;"></td>

					</tr>

					<tr>
						<td colspan="6" class="internal-table ">
							<table class="table not-responsive">
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
										
										<td style="width: 20%;"><strong>Cottage  </strong>
										
										</td>
										
										<td style="width: 30%;"><strong>Notes </strong>
											<span><?php echo $row['note']; ?></span></td>
										<td style=" width: 10%;"><a class="custom" href="<?php echo SITE_URL; ?>?section=customer-history&id=<?php echo $id; ?>">More</a></td> 
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
	</div>
	</div>
	</div>
	</div>

<?php	
}	
}

function getCustomer()
{
	$cust_name=$_REQUEST['cust'];
	$sql="SELECT * FROM  `customer` WHERE (company like '%".$cust_name."%') or (CONCAT(first_name,' ',last_name ) LIKE  '%".$cust_name."%')";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		$company=$row['company'];
		$first_name=$row['first_name'];
		$last_name=$row['last_name'];
		if($company!='')
		{
			$asd=$company;
		}
		else
		{
			$asd=$first_name.' '.$last_name;
		}		
		
		
	?> 
		<li><a href="javascript:void(0);" onclick="set_name('<?php echo $row['id']; ?>','<?php echo mysql_real_escape_string($asd); ?>');"><?php echo stripslashes($asd); ?></a></li>   
	<?php
	}
	
}

function addcustomerJob() 
{
	unset($_REQUEST['action']);
	$temp_po=mysql_real_escape_string($_REQUEST['temp_po']);
	$cust_id=mysql_real_escape_string($_REQUEST['cust_id']);
	$job_name=mysql_real_escape_string($_REQUEST['job_name']);
	$po=mysql_real_escape_string($_REQUEST['po_num']);
	$from_date=date('Y-m-d', strtotime($_REQUEST['from_date']));
	$to_date=date('Y-m-d', strtotime($_REQUEST['to_date']));
	$job_insert_by=$_REQUEST['job_insert_by'];
	$budget_hours=$_REQUEST['budget_hours'];
	$job_name=mysql_real_escape_string($_REQUEST['job_name']);
	$cr_date=date("Y-m-d H:i:s");
	
	if($to_date < $from_date)
	{
		echo "0";
		die;
	}
	else 
	{
		 $sql="INSERT INTO  customer_job (name,cust_id,po,from_date,to_date,status,vendor_id ,cr_date,job_insert_by,temp_po,closing_date,budget_hours) VALUES ('".$job_name."','".$cust_id."','".$po."','".$from_date."','".$to_date."','1','0','".$cr_date."','".$job_insert_by."','".$temp_po."','0000-00-00','".$budget_hours."')";  
		 
		$res=mysql_query($sql);  
		
		$sql_get="select id from customer_job where temp_po='".$temp_po."'";
		$res_get=mysql_query($sql_get);
		while($row=mysql_fetch_array($res_get))
		{
			$id=$row['id'];  
		}	
		if(!empty($id))
		{	
			$ins="insert into job_last_updated (job_id,status,last_updated,last_email_sent) values ('".$id."','1','".$cr_date."','".$cr_date."')"; 
			$res_ins=mysql_query($ins); 
			echo $id; 
		} 
	}		
	
}

function FreezeUser()
{
	$user_id=$_REQUEST['user_id'];
	$status=$_REQUEST['status'];
	$sql="update users set status='".$status."' where id='".$user_id."'";
	$res=mysql_query($sql);
	echo $status; 
}


function editcustomerJob()
{
	unset($_REQUEST['action']); 
	$job_id=$_REQUEST['job_id']; 
	$temp_po=$_REQUEST['temp_po']; 
	$cust_id=$_REQUEST['cust_id'];
	$job_name=$_REQUEST['job_name'];
	$budget_hours=$_REQUEST['budget_hours'];
	$po=$_REQUEST['po_num'];
	$from_date=date('Y-m-d', strtotime($_REQUEST['from_date'])); 
	$to_date=date('Y-m-d', strtotime($_REQUEST['to_date'])); 
	$job_insert_by=$_REQUEST['job_insert_by'];
	$job_name=stripslashes($_REQUEST['job_name']);
	$cr_date=date("Y-m-d H:i:s");
	
	if($to_date < $from_date)
	{
		echo "0";
		die;
	}
	else
	{
		$sql="update customer_job set name='".$job_name."',po='".$po."',from_date='".$from_date."',to_date='".$to_date."',job_insert_by='".$job_insert_by."',budget_hours='".$budget_hours."' where id='".$job_id."'";
		$res=mysql_query($sql); 
		echo "1"; 
				
	}	
}

function addTimecardStaff()
{
	unset($_REQUEST['action']); 
	$id=$_REQUEST['id']; 
	$staff_id=$_REQUEST['staff_id']; 
	$count=$id+1;
?>
<!--<script>jQuery('select').selectpicker();</script>-->

<ul class="box_<?php echo $count; ?>">
	<li class="pull-left">
		<select id="staff_id" name="staff_id[]" class="show-menu-arrow form-control">
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
					<option value="<?php echo $staff_id; ?>" <?php if($staff_id==$user_id) { echo 'selected'; } ?>><?php echo $name; ?></option>
				<?php
				} 
			
			?>
		</select>
	</li>
	<li class="pull-right">
		<input type="text" placeholder="Hours" name="hours[]">
		<a href="javascript:void(0);" onclick="remove_ul('1','<?php echo $count; ?>')">Remove</a> 
	</li>
</ul>
<?php	
}


function addTimecardStaff_11()
{
	unset($_REQUEST['action']); 
	$id=$_REQUEST['id']; 
	$staff_id=$_REQUEST['staff_id']; 
	$count=$id+1;
?>

<div class="asd box_<?php echo $count; ?>">
	<select id="staff_id" name="staff_id[]" class="show-menu-arrow form-control" onchange="get_value();">
		<option value=""></option>
		<?php
		if($staff_id!="")
		{	
			$sql="select * from users where id!=1 and id not in ($staff_id)";   
		}
		else
		{
			$sql="select * from users where id!=1";   
		}	
		$res=mysql_query($sql);
		
		while($row=mysql_fetch_array($res))
		{	
			$staff_id=$row['id'];
			$first_name=$row['first_name'];
			$last_name=$row['last_name'];
			$name=$first_name.' '.$last_name;
		?>
			<option value="<?php echo $staff_id; ?>" <?php if($staff_id==$user_id) { echo 'selected'; } ?>><?php echo $name; ?></option>
		<?php
		}
		?>
	</select>
<a href="javascript:void(0);" onclick="remove_ul('1','<?php echo $count; ?>')" >Remove</a>   
</div>
	
<?php	
}

function newTimeCard()
{
	$cr_date=date('Y-m-d H:i:s');
	unset($_REQUEST['action']); 
	$job_id=$_REQUEST['job_id'];
	$insert_by=$_REQUEST['insert_by'];
	$staff_id=$_REQUEST['staff_id'];
	$hours=$_REQUEST['hours'];
	$cr_date=$_REQUEST['cr_date'];   
	$cr_date=date('Y-m-d', strtotime($cr_date));   
	$notes=mysql_real_escape_string($_REQUEST['notes']);
	
	$photo_1=$_FILES['photo_1']['name'];
	$photo_2=$_FILES['photo_2']['name'];
	$photo_3=$_FILES['photo_3']['name'];
	
	$caption_1=mysql_real_escape_string($_REQUEST['caption_1']);
	$caption_2=mysql_real_escape_string($_REQUEST['caption_2']);
	$caption_3=mysql_real_escape_string($_REQUEST['caption_3']); 
	
		
	$check="select id from timecard where job_id='".$job_id."'";
	$res_check=mysql_query($check);
	$count=mysql_num_rows($res_check);
	
		$insert="insert into timecard (job_id,staff_id,hours,cr_date,insert_by,status,notes) values ('".$job_id."','0','0','".$cr_date."','".$insert_by."','1','".$notes."')"; 
		$res_insert=mysql_query($insert);
		$asd=0;
		/*
		$i=0;
		foreach($staff_id as $val)
		{
			
			$hr=$hours[$i];
			
			if($val=='')
			{
				$staff .=$asd.",";
			}
			else
			{
				$staff.=$val.",";
			}		
			
			if($hr=='')
			{
				$hourss .=$asd.",";
			}
			else
			{
				$hourss.=$hr.",";
			}
			
		$i++;	
		}
		
		$staff_n=substr($staff,0,-1);
		$hourss_n=substr($hourss,0,-1);
		*/
		
		
		$sql_get="select id from timecard where job_id='".$job_id."' order by id desc limit 0,1";
		$res_get=mysql_query($sql_get);
		while($row_get=mysql_fetch_array($res_get))
		{
			 $row_id=$row_get['id'];
		}	
		
		$i=0;
		foreach($staff_id as $val)
		{
			$fname=get_user_detail($val,'first_name');
			$lname=get_user_detail($val,'last_name'); 
			
			
		$hr=$hours[$i];
			if($hr=='')
			{
				$hourss=0;
			}
			else
			{
				$hourss=$hr;  
			}
		
		
			$ins="insert into timecard_staff (card_id,staff_id,first_name,last_name,hours,cr_date) Values ('".$row_id."','".$val."','".$fname."','".$lname."','".$hourss."','".$cr_date."')"; 
			$res_ins=mysql_query($ins);
		$i++; 
		}
			
		
		/*
		$update="update timecard set staff_id='".$staff_n."',hours='".$hourss_n."' where id='".$row_id."'";
		$res=mysql_query($update); 
		*/
		if($photo_1!='')
		{	
			$t=time();
			$image1=str_replace(' ','',$photo_1);
			$final_photo_1=$t.$image1;
			if(move_uploaded_file($_FILES["photo_1"]["tmp_name"], $path='timecard/'.$final_photo_1))
			{
				$update="update timecard set photos_1='".$final_photo_1."' where id='".$row_id."'";
				$res=mysql_query($update);
			}
			
			
		}
		
		if($photo_2!='')
		{
		$image2=str_replace(' ','',$photo_2);
		$final_photo_2=$t.$image2;
			if(move_uploaded_file($_FILES["photo_2"]["tmp_name"], $path='timecard/'.$final_photo_2))
			{
				$update="update timecard set photos_2='".$final_photo_2."' where id='".$row_id."'";
				$res=mysql_query($update);
			}
		}
		
		if($photo_3!='')
		{
		$image3=str_replace(' ','',$photo_3);
		$final_photo_3=$t.$image3;
			if(move_uploaded_file($_FILES["photo_3"]["tmp_name"], $path='timecard/'.$final_photo_3)) 
			{
				$update="update timecard set photos_3='".$final_photo_3."' where id='".$row_id."'";
				$res=mysql_query($update);
			}
		} 
		
		$update="update timecard set photos_1_caption='".$caption_1."',photos_2_caption='".$caption_2."',photos_3_caption='".$caption_3."' where id='".$row_id."'";   
		$res=mysql_query($update);
		
		$upp="update job_last_updated set last_updated='".$cr_date."' where job_id='".$job_id."'";
		$res_upp=mysql_query($upp); 
		
		
		$vehicle=mysql_real_escape_string($_REQUEST['vehicle_id']); 
		$veh_descp=mysql_real_escape_string($_REQUEST['veh_descp']); 
		$km=mysql_real_escape_string($_REQUEST['km']); 
		
		$sql="insert into timecard_vehicle (card_id,vehicle_id,descp,km,cr_date) values ('".$row_id."','".$vehicle."','".$veh_descp."','".$km."','".$cr_date."')";
		$res=mysql_query($sql);
		 
		
		//$update="update timecard set photos_1='".$final_photo_1."',photos_2='".$final_photo_2."',photos_3='".$final_photo_3."' where id='".$row_id."'";
		//$res=mysql_query($update);
		
		
	//echo "1"; 
	
}

function editTimecard_show()
{
unset($_REQUEST['action']); 
$id=$_REQUEST['id']; 

$sql="select * from timecard where id='".$id."'";
$res=mysql_query($sql);

while($row=mysql_fetch_array($res))
{
	$id=$row['id'];
	$job_id=$row['job_id'];
	$insert_by=$row['insert_by'];
	$staff_id=$row['staff_id'];
	$hours=$row['hours'];
	$photos_1=$row['photos_1'];
	$photos_2=$row['photos_2'];
	$photos_3=$row['photos_3'];
	$notes=stripslashes($row['notes']); 
	$cr_date=$row['cr_date'];
	$insert_by=$row['insert_by'];
	
	$caption_1=stripslashes($row['photos_1_caption']);
	$caption_2=stripslashes($row['photos_2_caption']);
	$caption_3=stripslashes($row['photos_3_caption']);
	
}	
					
$staff_n=explode(',',$staff_id);	
$hours_n=explode(',',$hours);	
	
?>
<style>
.bootstrap-filestyle .form-control {
	display:none;
}
</style>
<script>

   jQuery( ".datepicker" ).datepicker({
        changeMonth: false,
        changeYear: false,
		onSelect: function(year, month, inst) {
				var datValue=jQuery(this).val();
				
				var year= jQuery.datepicker.formatDate('M dd, yy', new Date(datValue));
				jQuery('.date').html(year);
				return false;
		}, 
		 
    });

jQuery('#editTimeCardd').validate({
rules: {
	'staff_id[]': {
		required: true
	}
},
submitHandler: function(form) { 
	jQuery('.edit_result_timecard').empty();
	jQuery('.edit_result_timecard').show();
	jQuery('#edit_timecard_loader').show();
	jQuery(form).ajaxSubmit({
		type: "POST",
		data: jQuery(form).serialize(),
		url: 'handler.php', 
		success: function(data) 
		{					
			
			if(data==1)
			{	
				jQuery('#edit_timecard_loader').hide();
				jQuery('.edit_result_timecard').empty().append('<div class="alert alert-success">Your timecard has been saved</div>');
				jQuery('.edit_result_timecard').fadeOut(3000); 
				location.reload();  
			}
		}
	});
}

});
jQuery('.filestyle').filestyle({
buttonText: ' Upload'
});
</script>
<div class="new_custmer">
<a class="close_new" href="javascript:void(0);" onclick="jQuery('.box').slideUp();jQuery('.edit-timcrd').slideUp();" class="close_new"><i class="fa fa-times" aria-hidden="true"></i></a> 
</div>
<div class="inner_content new-timecard">
<div class="inner_pdng">
	<form name="editTimeCardd" id="editTimeCardd" action="" method="post" class="veh_form">
	<input type="hidden" name="row_id" value="<?php echo $id; ?>" /> 
	<input type="hidden" name="job_id" value="<?php echo $job_id; ?>" />
	<input type="hidden" name="insert_by" value="<?php echo $_SESSION['user_id']; ?>" />
	<input type="hidden" name="action" value="editTimeCard" /> 
	<h4 class="close_title">EDIT TIMECARD</h4>
	<div class="timcrd_lft pull-left">
	<div class="edit-clndr">
	<div class="inr_date">
		<?php $today=date('Y-m-d'); ?>
		<h3 class="date"><?php echo date('M d, Y', strtotime($cr_date)); ?></h3>
		<span><a href="javascript:void(0);" onclick="jQuery('#ed_cr_date').show();"><i aria-hidden="true" class="fa fa-calendar"></i></a></span></div>
		<div class="cstm_clndr">
		<input type="text" id="ed_cr_date" name="cr_date" class="datepicker edt-calndr" placeholder="<?php echo date('m/d/Y', strtotime($cr_date)); ?>" value="<?php echo date('m/d/Y', strtotime($cr_date)); ?>" style="display:none;" readonly> </div></div>
		<strong>UPLOAD PHOTOS</strong>
		<ul>
			<li>
				<div class="form-group">
					<div class="left_inpt pull-left">
						<input type="text" name="caption_1" placeholder="Caption" class="caption form-control" value="<?php echo $caption_1; ?>">
					</div>
					<div class="pull-right file_upld">
						<input type="file" name="photoo_1" class="filestyle" data-icon="false"  >
					</div>
				</div>
				<?php if($photos_1!='') { ?>
						<div class="ed_photo_1 edit_photo">
							<img src="thumb.php?src=timecard/<?php echo $photos_1; ?>&w=50&h=50">
							<a onclick="del_photo('1','<?php echo $id; ?>')" href="javascript:void(0);" class="remove-1"><i aria-hidden="true" class="fa fa-trash-o"></i></a>
						</div> 
				<?php }?>
			</li>
			<li>
				<div class="form-group">
					<div class="left_inpt pull-left">
						<input type="text" name="caption_2" placeholder="Caption" class="caption form-control" value="<?php echo $caption_2; ?>">
					</div>
					<div class="pull-right file_upld">
						<input type="file" name="photoo_2" class="filestyle" data-icon="false"> 
					</div>
				</div>
				<?php if($photos_2!='') { ?>
						<div class="ed_photo_2 edit_photo">
							<img src="thumb.php?src=timecard/<?php echo $photos_2; ?>&w=50&h=50">
							<a onclick="del_photo('2','<?php echo $id; ?>')" href="javascript:void(0);" class="remove-1"><i aria-hidden="true" class="fa fa-trash-o"></i></a> 
						</div>
					
				<?php }?>
			</li>
			<li>
				<div class="form-group">
					<div class="left_inpt pull-left">
						<input type="text" name="caption_3" placeholder="Caption" class="caption form-control" value="<?php echo $caption_3; ?>"> 
					</div>		
					<div class="pull-right file_upld">
						<input type="file" name="photoo_3" class="filestyle" data-icon="false">
					</div>
				</div>
				<?php if($photos_3!='') { ?>
						<div class="ed_photo_3 edit_photo">
							<img src="thumb.php?src=timecard/<?php echo $photos_3; ?>&w=50&h=50">
							<a onclick="del_photo('3','<?php echo $id; ?>')" href="javascript:void(0);" class="remove-1"><i aria-hidden="true" class="fa fa-trash-o"></i></a>  
						</div>
				<?php }?> 
			</li>

		</ul> 
	</div>
	<div class="timcrd_rt pull-right">
		<div class="selectn_box_1">  
			<?php
			$i=0;
			$a=1;
			$get_staff="select * from timecard_staff where card_id='".$id."'";
			$res_get_staff=mysql_query($get_staff);
			while($row_get_staff=mysql_fetch_array($res_get_staff))
			{		
		
				$hours=$row_get_staff['hours'];
				if($hours==0)
				{
					$asd='';
				}
				else
				{
					$asd=$hours;
				}		
			?>
			<ul class="box_<?php echo $a; ?>">
				<li class="pull-left">
						
						<select  name="staff_id[]" class="show-menu-arrow form-control">
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
								<option value="<?php echo $staff_id; ?>" <?php if($staff_id==$row_get_staff['staff_id']) { echo 'selected'; } ?> ><?php echo $name; ?></option> 
							<?php 
							} 
							?>
						</select> 
						
				</li>
				<li class="pull-right"> 
					<input type="text" placeholder="Hours" name="hours[]" value="<?php echo $asd; ?>">
					<?php if($a!=1) { ?><a onclick="remove_ul('2','<?php echo $a; ?>')" href="javascript:void(0);">Remove</a><?php } ?>
				</li>
			</ul>
			<?php
			$i++;
			$a++;
			} 
			?>
		</div>
		<a href="javascript:void(0);" class="custom" onclick="add_timecard_staff_1();">Add Staff &#43;</a>
		<?php $vehicle_id=get_timecard_vehicle_info($id,'vehicle_id'); ?>
		<div class="selectn_boxx"> 
			<ul>
				<li class="pull-left">
					<select id="vehicle_id" name="vehicle_id" class="show-menu-arrow form-control">
						<option value="">Select Vehicle</option>
						<?php
						$sql="select * from vehicle where status='1'";
						$res=mysql_query($sql);
						while($row=mysql_fetch_array($res))
						{	
							$veh_id=$row['id'];
							$vehicle_num=$row['vehicle_num']; 
						?>
						<option value="<?php echo $veh_id; ?>" <?php if($vehicle_id==$veh_id) { echo 'selected'; } ?>><?php echo $vehicle_num; ?></option>
						<?php
						}
						?>
						
					</select>
					
					
				</li>
				<li><input placeholder="Description" name="veh_descp" value="Doug Werner" type="text" value="<?php echo get_timecard_vehicle_info($id,'descp'); ?>">
				</li>
				<li class="pull-right">
					<input placeholder="KM" name="km" type="text" value="<?php echo get_timecard_vehicle_info($id,'km'); ?>">
				</li>  
			</ul> 
		</div>
		
		<textarea placeholder="Type your notes here…" name="notes"><?php echo $notes; ?></textarea>
	</div>

	<div class="closed_by_usr bg-transparent">
		<div class="inner_pdng">
			<div class="pull-right">
			   <span>Created by <b> <?php echo get_user_detail($_SESSION['user_id'],'first_name'); ?> <?php echo get_user_detail($_SESSION['user_id'],'last_name'); ?></b></span>
			   <button class="custom" type="submit" name="submit">Save</button>
			   <img src="assets/images/loader.gif" id="edit_timecard_loader" style="display:none;">	
			</div>
		</div>
	</div>
</form>	
<div class="edit_result_timecard"></div>
</div>
</div>
<?php	
}

function del_photo()
{
	$path=ROOTPATH;
	$path=str_replace('config','',$path);
	
	unset($_REQUEST['action']); 
	$id=$_REQUEST['id']; 
	$e=$_REQUEST['e']; 
	
	$sel="select * from timecard where id='".$id."'";
	$res=mysql_query($sel);
	while($row=mysql_fetch_array($res))
	{
		$photo=$row['photos_'.$e];
	}
	
	$path=$path.'timecard/'.$photo;
	
	if(file_exists($path)) 
	{
		unset($path); 
	}
	
	$sql="update timecard set photos_".$e."='' where id='".$id."'";
	$res=mysql_query($sql);   
	
}

function editTimeCard()
{
	$cr_date=date('Y-m-d H:i:s');
	unset($_REQUEST['action']); 
	$row_id=$_REQUEST['row_id'];
	$job_id=$_REQUEST['job_id'];
	$insert_by=$_REQUEST['insert_by'];
	$staff_id=$_REQUEST['staff_id'];
	$hours=$_REQUEST['hours'];
	$cr_date=$_REQUEST['cr_date'];
	$cr_date=date('Y-m-d H:i:s', strtotime($cr_date)); 
	
	$notes=mysql_real_escape_string($_REQUEST['notes']); 
	
	$photo_1=$_FILES['photoo_1']['name'];
	$photo_2=$_FILES['photoo_2']['name'];
	$photo_3=$_FILES['photoo_3']['name']; 
	
	$caption_1=mysql_real_escape_string($_REQUEST['caption_1']);
	$caption_2=mysql_real_escape_string($_REQUEST['caption_2']);
	$caption_3=mysql_real_escape_string($_REQUEST['caption_3']);
	
	 
	
		$insert="update timecard set insert_by='".$insert_by."',notes='".$notes."',cr_date='".$cr_date."' where id='".$row_id."'";
		$res_insert=mysql_query($insert); 
		$asd=0;
		/*
		$i=0;
		foreach($staff_id as $val) 
		{
			
			$hr=$hours[$i];
			
			if($val=='')
			{
				$staff .=$asd.",";
			}
			else
			{
				$staff.=$val.",";
			}		
			
			if($hr=='')
			{
				$hourss .=$asd.",";
			}
			else
			{
				$hourss.=$hr.",";
			}
			
		$i++;	
		}
		
		$staff_n=substr($staff,0,-1);
		$hourss_n=substr($hourss,0,-1);
		
		
		$update="update timecard set staff_id='".$staff_n."',hours='".$hourss_n."' where id='".$row_id."'";
		$res=mysql_query($update);
		*/
		
		$del="delete from timecard_staff where card_id='".$row_id."'";    
		$res_del=mysql_query($del);
		 
		
		$i=0;
		foreach($staff_id as $val)
		{
			
			$fname=get_user_detail($val,'first_name');
			$lname=get_user_detail($val,'last_name'); 
			
		$hr=$hours[$i];
			if($hr=='')
			{
				$hourss=0;
			}
			else
			{
				$hourss=$hr;  
			}
		
		
			$ins="insert into timecard_staff (card_id,staff_id,first_name,last_name,hours,cr_date) Values ('".$row_id."','".$val."','".$fname."','".$lname."','".$hourss."','".$cr_date."')"; 
			$res_ins=mysql_query($ins); 
		$i++;
		}
		
		$del_vehicle="delete from timecard_vehicle where card_id='".$row_id."'";    
		$res_del_vehicle=mysql_query($del_vehicle);
		 
		$vehicle=mysql_real_escape_string($_REQUEST['vehicle_id']); 
		$veh_descp=mysql_real_escape_string($_REQUEST['veh_descp']); 
		$km=mysql_real_escape_string($_REQUEST['km']); 
		
		$sql="insert into timecard_vehicle (card_id,vehicle_id,descp,km,cr_date) values ('".$row_id."','".$vehicle."','".$veh_descp."','".$km."','".$cr_date."')"; 
		$res=mysql_query($sql);
		

		$path='timecard/';
		if($photo_1!='')
		{	
			$t=time();
			$image1=str_replace(' ','',$photo_1);
			$final_photo_1=$t.$image1;
			move_uploaded_file($_FILES["photoo_1"]["tmp_name"], $path.$final_photo_1);
			$update="update timecard set photos_1='".$final_photo_1."' where id='".$row_id."'";
			$res=mysql_query($update);
		}
		
		if($photo_2!='') 
		{
		$image2=str_replace(' ','',$photo_2);
		$final_photo_2=$t.$image2;
		move_uploaded_file($_FILES["photoo_2"]["tmp_name"], $path.$final_photo_2);
		$update="update timecard set photos_2='".$final_photo_2."' where id='".$row_id."'";
		$res=mysql_query($update);
		}
		
		if($photo_3!='')
		{
		$image3=str_replace(' ','',$photo_3); 
		$final_photo_3=$t.$image3;
		move_uploaded_file($_FILES["photoo_3"]["tmp_name"], $path.$final_photo_3); 
		$update="update timecard set photos_3='".$final_photo_3."' where id='".$row_id."'";
		$res=mysql_query($update);    
		}
		
		$update="update timecard set photos_1_caption='".$caption_1."',photos_2_caption='".$caption_2."',photos_3_caption='".$caption_3."' where id='".$row_id."'";   
		$res=mysql_query($update);
		
		$selQry="select hours from timecard_staff where id='".$row_id."'";
		$getData=mysql_query($selQry);
		while($fetchData=mysql_fetch_array($getData))
		{
			 $getHours=$fetchData['hours'];
		}
		$today = date("Y-m-d H:i:s");
		$upp="update job_last_updated set last_updated='".$today."' where job_id='".$job_id."'";
		$res_upp=mysql_query($upp);   
		$getDateFormat =  date("jS F, Y", strtotime($today)); 
		$job_name=get_customer_job_info($job_id,'name'); 
		$message='
		<p>Dear Terry,</p>
		 <p>The following change was made to '.$job_name.'</p></br>
		 <p>'.$getDateFormat.' timecard changed to total hours: '.$hourss.' hours</p></br>
		<p>Please login: <a href="https://algonquinelectrical.pro" target="_blank">www.algonquinelectrical.pro</a></p> 
		';	


		$subject='Timecard Edited  | '.$job_name;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: Algonquin Electrical <noreply@algonquinelectrical.com>' . "\r\n";

		mail(ADMIN_EMAIL, $subject, $message, $headers);  

		
		echo "1";
}


function AddClosejobPhoto()
{
unset($_REQUEST['action']); 
$id=$_REQUEST['job_id'];
$row_id=$_REQUEST['row_id'];
$caption=$_REQUEST['caption_1'];	 
	$path='close/';
	$valid_formats = array("jpg", "png", "gif", "bmp","jpeg","PNG","JPG","JPEG","GIF","BMP");
	$image=$_FILES['upload']['name'];
	$image=date("YmdHis").$image; 
	$image=str_replace(' ','',$image);
	$ext = pathinfo($image, PATHINFO_EXTENSION);
	if(in_array($ext,$valid_formats))
	{
		if (move_uploaded_file($_FILES["upload"]["tmp_name"], $path.$image)) 
		{
			
			$update="update customer_job set photo_".$row_id."='".$image."',photo_".$row_id."_caption='".$caption."' where id='".$id."'";
			$res=mysql_query($update);
			
			echo $image; 
			
		}	
	}	
	
}

function CloseJob()
{
unset($_REQUEST['action']); 
$id=$_REQUEST['id'];
$sql="select * from customer_job where id='".$id."'";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res))
{
$cust_id=$row['cust_id'];	
?>
<script>
jQuery('#photo_close_1').validate({
submitHandler: function(form) {
	
	var loader='<center><img src="assets/image/loader.gif"></center>';
	jQuery('.cl_res_1').empty().append(loader);
	jQuery(form).ajaxSubmit({ 
		type: "POST",
		data: jQuery(form).serialize(),
		url: 'handler.php', 
		success: function(data) 
		{					
			jQuery('.cl_res_1').empty().append('<img src="thumb.php?src=close/'+data+'&amp;w=320&amp;h=250">'); 
			
		}
	});
}
});
	
jQuery('#photo_close_1').find('input[name=upload]').change(function()
{	
	jQuery('#photo_close_1').submit(); 
});

jQuery('#photo_close_2').validate({
submitHandler: function(form) {
	
	var loader='<center><img src="assets/image/loader.gif"></center>';
	jQuery('.cl_res_2').empty().append(loader);
	jQuery(form).ajaxSubmit({ 
		type: "POST",
		data: jQuery(form).serialize(),
		url: 'handler.php', 
		success: function(data) 
		{					
			jQuery('.cl_res_2').empty().append('<img src="thumb.php?src=close/'+data+'&amp;w=320&amp;h=250">'); 
			
		}
	});
}
});
	
jQuery('#photo_close_2').find('input[name=upload]').change(function()
{	
	jQuery('#photo_close_2').submit(); 
});

jQuery('#photo_close_3').validate({
submitHandler: function(form) {
	
	var loader='<center><img src="assets/image/loader.gif"></center>';
	jQuery('.cl_res_3').empty().append(loader);
	jQuery(form).ajaxSubmit({ 
		type: "POST",
		data: jQuery(form).serialize(),
		url: 'handler.php', 
		success: function(data) 
		{					
			jQuery('.cl_res_3').empty().append('<img src="thumb.php?src=close/'+data+'&amp;w=320&amp;h=250">'); 
			
		} 
	});
}
});
	
jQuery('#photo_close_3').find('input[name=upload]').change(function()
{	
	jQuery('#photo_close_3').submit();  
});

function insert_close_job()
{
	jQuery('#close_job_loader').show();
	var job_id=jQuery('#job_id').val();
	var po_num=jQuery('#po_num').val();
	var close_note=jQuery('#close_note').val(); 
	var ins_by=jQuery('#ins_by').val();  
	var cst_id=jQuery('#cst_id').val();  
	var caption_1=jQuery('#caption_1').val();  
	var caption_2=jQuery('#caption_2').val();  
	var caption_3=jQuery('#caption_3').val();  
	
	jQuery.ajax({type: "POST", 
	url: "handler.php",
	data: "id="+job_id+"&po_num="+po_num+"&close_note="+close_note+"&ins_by="+ins_by+"&caption_1="+caption_1+"&caption_2="+caption_2+"&caption_3="+caption_3+"&action=CloseJobSave",    
	success:function(result) 
	{
		jQuery('#close_job_loader').hide(); 
		jQuery('.close_job_res').empty().append('<div class="alert alert-success">Job has been closed</div>');
		window.location.href="<?php echo SITE_URL; ?>?section=customer-history&id="+cst_id+""; 
	}, 
	error:function(e){ 
		console.log(e); 
	}	
	}); 
	
	
}

</script>
	
	<div class="new_custmer">
	<h4 class="close_title">CLOSE JOB</h4>
	<a class="close_new" onclick="jQuery('.close-job-notee').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
	</div>
	<input type="hidden" id="ins_by" value="<?php echo $_SESSION['user_id']; ?>"/>
	<input type="hidden" id="job_id" value="<?php echo $id; ?>"/>
	<input type="hidden" id="cst_id" value="<?php echo $cust_id; ?>"/>
	
	<div class="close_container">
		<div class="recen_cont display_block">
			<div class="inner_pdng">
				<h5 class="pull-left"><?php echo $row['name']; ?></h5>
				<span class="date pull-right"><?php echo date('F d,Y', strtotime($row['from_date'])); ?> - <?php echo date('F d,Y', strtotime($row['to_date'])); ?></span></div>
		</div>
		<div class="inner_content">
			<div class="inner_pdng">
				<div class="notifictn_no display_block">
					<div class="notfctn_info pull-left">
						<strong><?php echo $row['po']; ?></strong>
						<p><?php echo get_cust_detail($cust_id,'first_name') ?> <?php echo get_cust_detail($cust_id,'last_name') ?> - <?php echo get_cust_detail($cust_id,'work_add1') ?>, <?php echo get_cust_detail($cust_id,'work_add2') ?> <?php echo get_cust_detail($cust_id,'work_city') ?> <?php echo get_cust_detail($cust_id,'work_prov') ?> <?php echo get_cust_detail($cust_id,'work_post_code') ?></p>
					</div> 
					<div class="ntfctn_btn pull-right">
						<input type="text" placeholder="Permit Notification Number" id="po_num" >
					</div>
				</div>
				<div class="closing_nts_cont">
					<h5>Closing Notes</h5>
					<textarea name="close_notes" id="close_note"></textarea>
				</div>
				<div class="close_lst">
					<ul>
						<li>
							<form name="photo_close_1" id="photo_close_1" action="" method="post">
								<input type="hidden" name="row_id" id="row_1" value="1">
								<input type="hidden" name="job_id" value="<?php echo $id; ?>">
								<input type="hidden" name="action" value="AddClosejobPhoto">
								<div class="image_clos">
									<div class="cl_res_1">
										<img src="assets/images/upload_bg.jpg" alt="">
									</div>
									<div class="fileUpload btn btn-primary">
										<span class="custom">Upload Photo</span>
										<input type="file" name="upload"  class="upload" />
									</div>
								</div>	
								<div class="imag_info">
									<input type="text" name="caption_1" id="caption_1">
								</div>
								<input type="submit" name="submit" value="submit" style="display:none;">
							</form>
						</li>
						<li>
							<form name="photo_close_2" id="photo_close_2" action="" method="post">
								<input type="hidden" name="row_id" id="row_2" value="2">
								<input type="hidden" name="job_id" value="<?php echo $id; ?>">
								<input type="hidden" name="action" value="AddClosejobPhoto">
								<div class="image_clos">
									<div class="cl_res_2">
										<img src="assets/images/upload_bg.jpg" alt="">
									</div>
									<div class="fileUpload btn btn-primary">
										<span class="custom">Upload Photo</span>
										<input type="file" name="upload" class="upload" />
									</div>
								</div>	 
								<div class="imag_info">
									<input type="text" name="caption_1" id="caption_2">
								</div>
								<input type="submit" name="submit" value="submit" style="display:none;">
							</form>
						</li>
						<li>
							<form name="photo_close_3" id="photo_close_3" action="" method="post">
								<input type="hidden" name="row_id" id="row_3" value="3">
								<input type="hidden" name="job_id" value="<?php echo $id; ?>"> 
								<input type="hidden" name="action" value="AddClosejobPhoto">
								<div class="image_clos">
									<div class="cl_res_3">
										<img src="assets/images/upload_bg.jpg" alt="">
									</div>
									<div class="fileUpload btn btn-primary">
										<span class="custom">Upload Photo</span>
										<input type="file" name="upload" class="upload" />
									</div>
								</div>	
								<div class="imag_info">
									<input type="text" name="caption_1" id="caption_3">
								</div>
								<input type="submit" name="submit" value="submit" style="display:none;">
							</form>
						</li>
					</ul>

				</div>
			</div>
			<div class="closed_by_usr">
				<div class="inner_pdng">
					<div class="pull-right">
						<span>Closed by <b><?php echo get_user_detail($_SESSION['user_id'],'first_name'); ?> <?php echo get_user_detail($_SESSION['user_id'],'last_name'); ?></b></span>
						<a href="javascript:void(0);" class="custom" onclick="insert_close_job();">Close Job</a>
						<img src="assets/images/loader.gif" id="close_job_loader" style="display:none;" />
					</div>
					<div id="close_job_res"></div>
				</div>
			</div>
		</div>
	</div>
<?php	
}
}


function OpenJob()
{
	unset($_REQUEST['action']); 
	$id=$_REQUEST['id'];
	$sql="update customer_job set status='1' where id='".$id."'"; 
	$res=mysql_query($sql);   
	//echo "1"; 
}


function AddVendor()
{
	unset($_REQUEST['action']);
	$name=mysql_real_escape_string($_REQUEST['name']);
	$email=$_REQUEST['email'];
	$phone=mysql_real_escape_string($_REQUEST['phone']);
	$address=mysql_real_escape_string($_REQUEST['address']);
	$city=mysql_real_escape_string($_REQUEST['city']);
	$prov=mysql_real_escape_string($_REQUEST['prov']);
	$post_code=mysql_real_escape_string($_REQUEST['post_code']);
	
	$check_email="select id from vendor where email='".$email."'";
	$res_email=mysql_query($check_email);
	$count=mysql_num_rows($res_email);
	if($count > 0)
	{
		echo "0";
		die;
	}
	else
	{		
		$sql="INSERT INTO vendor (name,email,phone,address,city,prov,post_code) VALUES ('".$name."','".$email."','".$phone."','".$address."','".$city."','".$prov."','".$post_code."')"; 
		$res=mysql_query($sql);  
		echo "1";
	}
}

function vendorShow()
{
	
 
	
$id=$_REQUEST['id'];   
$sql="select * from vendor where id='".$id."'";
$res=mysql_query($sql);	
while($row=mysql_fetch_array($res))
{	
?>
<div class="new_custmer">
<a class="close_new" onclick="jQuery('.show_cust_box').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
<h4 class="close_title">VENDOR</h4>
</div>
<div class="close_container custmr_edt_cnt">
<div class="customers_container view_cstmr">
	<div class="close_mn display_block">
	<div class="cstm-wid top_inpt edit_table vndr_cnt customrs_cont"><div class="responsive-table">
	<table class="table border-sep">
		<thead>
			<tr>
				<th style="width: 3%;"></th>
				<th style="width: 18%;">Name</th>
				<th style="width: 35%;">Email Address</th>
				<th style="width: 15%;">Telephone</th>
				<th style="text-align: center; width: 10%;"></th>
			</tr>
		</thead>
		<tbody>
		<td colspan="6" class="internal-table">
			<table class="table brdr brdr_none margin_non not-responsive">
				<tbody>
					<tr>
						<td style="width: 3%;"></td>
						<td style="width: 18%;"><?php echo $row['name']; ?></td>
						<td style="width: 35%;"><?php echo $row['email']; ?></td>
						<td style="width: 15%;"><?php echo $row['phone']; ?></td>
						<td style=" width: 10%;"></td>

					</tr>

					<tr>
						<td colspan="6" class="internal-table">
							<table class="table not-responsive">
								<tbody>
									<tr class="vrtcl_top">
										<td style="width: 3%;"></td>
										
										<td style="width: 19%;">
											
											<span><?php echo $row['address']; ?></span>
										    <span><?php echo $row['city']; ?></span>
										    <span><?php echo $row['prov']; ?></span>
										    <span><?php echo $row['post_code']; ?></span>
										 
											</td>
										
										<td style="width: 20%;">&nbsp;</td>
										
										<td style="width: 30%;"><strong>&nbsp;</strong>
											<span>&nbsp;</span></td>
										<td style=" width: 10%;">
										
										<a class="custom" href="<?php echo SITE_URL; ?>?section=vendor-history&id=<?php echo $id; ?>">More</a>
										
										</td> 
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
	
	</div>
	</div>
	</div>
	</div>

<?php	
}	
}

function vendorEdit()
{
$id=$_REQUEST['id'];   
$sql="select * from vendor where id='".$id."'";
$res=mysql_query($sql);	
while($row=mysql_fetch_array($res))
{	
?>
<script>
jQuery('#edit_vendor').validate({
		
		rules: {
			name: {
				required: true
			},
			email: {
				required: true,
				email:true
			}
			
			
		},
			
		submitHandler: function(form) {
			jQuery('.edit_result').empty();
			jQuery('.edit_result').show();
			jQuery('#edit_cust_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					jQuery('#edit_cust_loader').hide();
					if(data==1)
					{
						jQuery('.edit_result').empty().append('<div class="alert alert-success">Customer Saved</div>');
						jQuery('.edit_result').fadeOut(3000); 
						location.reload(); 
					} 
					
				}
			});
		}
		
	});
	
		jQuery('.cust-del').click(function()
		{
			var username= jQuery(this).attr("u");
			if(confirm ("Are you sure you want to Delete: " +username+"?"))
			{	
							
				id=jQuery(this).attr("id");		
				jQuery.ajax({type: "POST",
				url: "handler.php",
				data: "id="+id+"&action=vendorDel",
				success:function(result){
					location.reload();  
				},
				error:function(e){
					console.log(e);
				}	
				}); 
			  
			}
			else 
			{
				  jQuery(this).parent().parent().removeClass("deletion ");
			return;  
			}
	 
	
		});
	
</script>
<div class="new_custmer">
<a class="close_new" onclick="jQuery('.edit_cust_box').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
<a class="cust-del pull-right" id="<?php echo $id; ?>" u="<?php echo $row['first_name']; ?> <?php echo $row['last_name']; ?>" href="javascript:void(0)"> <i class="fa fa-trash-o"></i></a>
<h4 class="close_title">EDIT CUSTOMER</h4>
</div>
<div class="close_container custmr_edt_cnt">
<form name="edit_vendor" id="edit_vendor" action="" method="post"> 
	<input type="hidden" name="action" value="EditVendor">	 
	<input type="hidden" name="id" value="<?php echo $id; ?>">	
	<div class="cstm-wid top_inpt"> 
							
			<ul>
				<li>
					<input type="text" name="name" placeholder="Name" value="<?php echo stripslashes($row['name']); ?>">
				</li>
				
				<li>
					<input type="text" name="email" placeholder="Email addres" value="<?php echo stripslashes($row['email']); ?>">
				</li>
				<li>
					<input type="text" name="phone" placeholder="Phone No" value="<?php echo stripslashes($row['phone']); ?>">
				</li>
			</ul>
		</div>

		<div class="cstm-wid top_inpt"> 
			
			<ul>
				<li>
					<input type="text" name="address" placeholder="Address" value="<?php echo stripslashes($row['address']); ?>">
				</li>
				
				<li>
					<input type="text" name="city" placeholder="City" value="<?php echo stripslashes($row['city']); ?>">
				</li>
			</ul>
		</div>
		<div class="cstm-wid top_inpt"> 
			<ul>
				<li>
					<input type="text" name="prov" placeholder="Province" value="<?php echo stripslashes($row['prov']); ?>">
				</li>
				<li>
					<input type="text" name="post_code" placeholder="Postal Code" value="<?php echo stripslashes($row['post_code']); ?>">
				</li>
			</ul>
		</div>
	<div class="btn_deit">
		
		<button type="submit" class="custom edit_cstm" name="submit">Save</button>
		<img id="edit_cust_loader" src="assets/images/loader.gif" style="display:none;" />
	</div>
</form>
<div class="edit_result result_edit"></div> 
<?php	
}
}

function EditVendor()
{
	unset($_REQUEST['action']);
	$id=$_REQUEST['id'];
	$name=$_REQUEST['name'];
	$email=$_REQUEST['email'];
	$phone=$_REQUEST['phone'];
	$address=$_REQUEST['address'];
	$city=$_REQUEST['city'];
	$prov=$_REQUEST['prov'];
	$post_code=$_REQUEST['post_code'];
	
	 
	$sql="UPDATE vendor SET  name='".$name."',email =  '".$email."',phone = '".$phone."',address =  '".$address."',city =  '".$city."',prov =  '".$prov."',post_code ='".$post_code."' WHERE id ='".$id."'";
	$res=mysql_query($sql); 
	echo "1";  
}

function vendorDel()
{
$id=$_REQUEST['id'];
$sql = "DELETE FROM vendor WHERE id ='".$id."'";
$res=mysql_query($sql);	
}

function ShowAssignInvoice()
{
	unset($_REQUEST['action']);
	$id=$_REQUEST['id'];
?>
<script>
jQuery('#invoice_asgn').validate({
		
		rules: {
			cust_job_id: {
				required: true
			},
			
		},
			
		submitHandler: function(form) {
			jQuery('#modal_loader').show();
			var in_id=jQuery('#in_id').val();
			jQuery('.result_asgn_invoice').empty();
			jQuery('.result_asgn_invoice').show();
			jQuery('#cust_loader_asgn_invoice').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					if(data==1)
					{
						jQuery('.result_asgn_invoice').empty().append('<div class="alert alert-success">Invoice has been assigned</div>');
						jQuery('.result_asgn_invoice').fadeOut(3000); 
						location.reload();
					}	
					
					
				}
			});
		}
		
	});
</script>
<?php
$check="select temp_invoice from assign_invoices order by id desc limit 0,1";
$res_check=mysql_query($check);
$count=mysql_num_rows($res_check);
if($count==0)
{
	$invoice_num=1000;
}
else
{
	while($row_check=mysql_fetch_array($res_check))
	{
		$invoice_num=$row_check['temp_invoice'] + 1;
	}	
}

$sql="select * from invoices where id='".$id."'";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$cr_date=$row['cr_date'];
	$vendor_id=$row['vendor_id'];
	$vendor_name=get_vendor_info($vendor_id,'name');
	$attachment=$row['attachment'];
	
}	
	
?>
<a href="javascript:void(0);" onclick="jQuery('.assign_invoice_box').slideUp();" class="close_new"><i class="fa fa-times" aria-hidden="true"></i></a>
<div class="close_container custmr_edt_cnt">

<form name="invoice_asgn" id="invoice_asgn" method="post" action="" class="invoice_cont"> 
<input type="hidden" name="invoice_id" value="<?php echo $id; ?>" id="in_id">
<input type="hidden" name="temp_invoice" value="<?php echo $invoice_num; ?>" id="temp_invoice">
<input type="hidden" name="insert_by" value="<?php echo $_SESSION['user_id']; ?>" id="insert_by">

<input type="hidden" name="action" value="AssignInvoice">
<div class="cstm-wid top_inpt asign-left">
	
	<ul>
		
			
		
			
	
		<li>
		<p><?php echo date('M d, Y', strtotime($cr_date)); ?></p>
			<input type="text" name="invoice_num" class="form-control" placeholder="Invoice #">
		</li>
		<li>
		<p><?php echo stripslashes($vendor_name); ?></p>
			<select name="cust_job_id" class="form-control">
			<option value=""></option>
			<?php
			$sql="select * from customer_job where status='1'";
			$res=mysql_query($sql);
			while($row=mysql_fetch_array($res)) 
			{	
			?>
			<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
			<?php
			}
			?>		
			</select>
		</li>
	
	</ul>
</div>
<div class="asign-rt">
<div class="btn_deit ">
<a target="_blank" href="<?php echo SITE_URL; ?>attachment/<?php echo $attachment; ?>" style="text-align:center;" class="custom edit_cstm">View</a>
</div>
<div class="btn_deit pull-right">
	<button name="submit" class="custom edit_cstm" type="submit">Save</button>
	<img src="assets/images/loader.gif" id="modal_loader" style="display:none;"/>
</div>
</div>
</form>
<div class="result_asgn_invoice edit_result result_edit"></div>
</div>
<?php	
}

function ShowAssignInvoice_1()
{
	unset($_REQUEST['action']);
	$id=$_REQUEST['id'];
?>
<script>
jQuery('#invoice_asgn').validate({
		
		rules: {
			cust_job_id: {
				required: true
			},
			
		},
			
		submitHandler: function(form) {
			jQuery('#modal_loader').show();
			var in_id=jQuery('#in_id').val();
			jQuery('.result_asgn_invoice').empty();
			jQuery('.result_asgn_invoice').show();
			jQuery('#cust_loader_asgn_invoice').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					if(data==1)
					{
						jQuery('.result_asgn_invoice').empty().append('<div class="alert alert-success">Invoice has been assigned</div>');
						jQuery('.result_asgn_invoice').fadeOut(3000); 
						location.reload();
					}	
					
					
				}
			});
		}
		
	});
</script>
<?php
$check="select temp_invoice from assign_invoices order by id desc limit 0,1";
$res_check=mysql_query($check);
$count=mysql_num_rows($res_check);
if($count==0)
{
	$invoice_num=1000;
}
else
{
	while($row_check=mysql_fetch_array($res_check))
	{
		$invoice_num=$row_check['temp_invoice'] + 1;
	}	
}

$sql="select * from invoices where id='".$id."'";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$cr_date=$row['cr_date'];
	$vendor_id=$row['vendor_id'];
	$vendor_name=get_vendor_info($vendor_id,'name');
	$attachment=$row['attachment'];
	
}	

$sql_1="select * from assign_invoices where invoice_id='".$id."'";
$res_1=mysql_query($sql_1);
while($row=mysql_fetch_array($res_1))
{
	$job_id=$row['job_id'];
} 

	
?>
<a href="javascript:void(0);" onclick="jQuery('.assign_invoice_box').slideUp();" class="close_new"><i class="fa fa-times" aria-hidden="true"></i></a>
<div class="close_container custmr_edt_cnt">

<form name="invoice_asgn" id="invoice_asgn" method="post" action="" class="invoice_cont"> 
<input type="hidden" name="invoice_id" value="<?php echo $id; ?>" id="in_id">
<input type="hidden" name="temp_invoice" value="<?php echo $invoice_num; ?>" id="temp_invoice">
<input type="hidden" name="insert_by" value="<?php echo $_SESSION['user_id']; ?>" id="insert_by">

<input type="hidden" name="action" value="AssignInvoice_1">
<div class="cstm-wid top_inpt asign-left">
	
	<ul>
		
			
		
			
	
		<li>
		<p><?php echo date('M d, Y', strtotime($cr_date)); ?></p>
			<input type="text" name="invoice_num" class="form-control" placeholder="Invoice #">
		</li>
		<li>
		<p><?php echo stripslashes($vendor_name); ?></p>
			<select name="cust_job_id" class="form-control">
			<option value=""></option>
			<?php
			$sql="select * from customer_job where status='1'";
			$res=mysql_query($sql);
			while($row=mysql_fetch_array($res)) 
			{	
			?>
			<option value="<?php echo $row['id']; ?>" <?php if($job_id==$row['id']) { echo 'selected'; } ?>><?php echo $row['name']; ?></option> 
			<?php
			}
			?>		
			</select>
		</li>
	
	</ul>
</div>
<div class="asign-rt">
<div class="btn_deit ">
<a target="_blank" href="<?php echo SITE_URL; ?>attachment/<?php echo $attachment; ?>" style="text-align:center;" class="custom edit_cstm">View</a>
</div>
<div class="btn_deit pull-right">
	<button name="submit" class="custom edit_cstm" type="submit">Save</button>
	<img src="assets/images/loader.gif" id="modal_loader" style="display:none;"/>
</div>
</div>
</form>
<div class="result_asgn_invoice edit_result result_edit"></div>
</div>
<?php	
}

function AssignInvoice()
{
	$today=date("Y-m-d H:i:s");
	unset($_REQUEST['action']);
	$invoice_id=$_REQUEST['invoice_id'];
	$job_id=$_REQUEST['cust_job_id'];
	$temp_invoice=$_REQUEST['temp_invoice'];
	$invoice_num=mysql_real_escape_string($_REQUEST['invoice_num']);
	$insert_by=$_REQUEST['insert_by'];
	
	$up1="update invoices set cust_job='".$invoice_id."' where id='".$invoice_id."'";
	$res_up1=mysql_query($up1); 
	
	$insert="insert into assign_invoices(invoice_id,invoice_num,job_id,cr_date,temp_invoice,insert_by) values ('".$invoice_id."','".$invoice_num."','".$job_id."','".$today."','".$temp_invoice."','".$insert_by."')";
	$res_insert=mysql_query($insert);
	echo "1";   
	
}

function AssignInvoice_1() 
{
	$today=date("Y-m-d H:i:s");
	unset($_REQUEST['action']);
	$invoice_id=$_REQUEST['invoice_id'];
	$job_id=$_REQUEST['cust_job_id'];
	$temp_invoice=$_REQUEST['temp_invoice'];
	$invoice_num=mysql_real_escape_string($_REQUEST['invoice_num']);
	$insert_by=$_REQUEST['insert_by'];
	
	$up1="update invoices set cust_job='1',status='1' where id='".$invoice_id."'";
	$res_up1=mysql_query($up1);   
	
	$update="update assign_invoices set job_id='".$job_id."',cr_date='".$today."' where invoice_id='".$invoice_id."'";
	$res_up=mysql_query($update); 
	echo "1";   
	
}


function CloseJobSave()
{
	$today=date('Y-m-d');
	unset($_REQUEST['action']);
	$job_id=$_REQUEST['id']; 
	$po_num=mysql_real_escape_string($_REQUEST['po_num']);
	$close_note=mysql_real_escape_string($_REQUEST['close_note']);
	$ins_by=$_REQUEST['ins_by'];
	$caption_1=mysql_real_escape_string($_REQUEST['caption_1']);
	$caption_2=mysql_real_escape_string($_REQUEST['caption_2']);
	$caption_3=mysql_real_escape_string($_REQUEST['caption_3']);  
	
	
	$sql="update  customer_job set status='0',pno='".$po_num."',closing_notes='".$close_note."',closing_date='".$today."',closed_by='".$ins_by."',photo_1_caption='".$caption_1."',photo_2_caption='".$caption_2."',photo_3_caption='".$caption_3."' where id='".$job_id."'";
	$res=mysql_query($sql);
	
	$upp="update job_last_updated set status='0',last_updated='".$today."',last_email_sent='".$today."' where job_id='".$job_id."'";  
	$res_upp=mysql_query($upp);

	$job_name=get_customer_job_info($job_id,'name'); 
	$message='
	<p>Dear Terry,</p>
	<p>The following job has been closed '.$job_name.'</p>
	<p>Please login: <a href="https://algonquinelectrical.pro" target="_blank">www.algonquinelectrical.pro</a></p> 
	';	
	$subject='Job Closed | '.$job_name;
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	$headers .= 'From: Algonquin Electrical <noreply@algonquinelectrical.com>' . "\r\n";

	mail(ADMIN_EMAIL, $subject, $message, $headers);     
	
	
}

function CloseJobInfo()
{
	unset($_REQUEST['action']);
	$job_id=$_REQUEST['id']; 
	
	$sql="select * from customer_job where status='0' and id='".$job_id."'";
	$res=mysql_query($sql);
while($row=mysql_fetch_array($res))
{
	$cust_id=$row['cust_id'];
?> 
<a class="close_new" onclick="jQuery('.close-job-info').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
<h4 class="close_title">CLOSE JOB</h4>
	<input type="hidden" id="ins_by" value="<?php echo $_SESSION['user_id']; ?>"/>
	<input type="hidden" id="job_id" value="<?php echo $job_id; ?>"/>
	<input type="hidden" id="cst_id" value="<?php echo $cust_id; ?>"/>
	
	<div class="close_container">
		<div class="recen_cont display_block">
			<div class="inner_pdng">
				<h5 class="pull-left"><?php echo $row['name']; ?></h5>
				<span class="date pull-right"><?php echo date('F d,Y', strtotime($row['from_date'])); ?> - <?php echo date('F d,Y', strtotime($row['to_date'])); ?></span></div>
		</div>
		<div class="inner_content">
			<div class="inner_pdng">
				<div class="notifictn_no display_block">
					<div class="notfctn_info pull-left">
						<strong><?php echo $row['po']; ?></strong>
						<p><?php echo get_cust_detail($cust_id,'company') ?> - <?php echo get_cust_detail($cust_id,'work_add1') ?>, <?php echo get_cust_detail($cust_id,'work_add2') ?> <?php echo get_cust_detail($cust_id,'work_city') ?> <?php echo get_cust_detail($cust_id,'work_prov') ?> <?php echo get_cust_detail($cust_id,'work_post_code') ?></p>
					</div>
					<div class="ntfctn_btn pull-right">
						<strong>Permit Notification Number : </strong><?php echo $row['pno']; ?>
					</div>
				</div>
				<div class="closing_nts_cont">
					<h5>Closing Notes</h5>
					<p><?php echo stripslashes($row['closing_notes']); ?></p>
				</div>
				<div class="close_lst">
					<ul>
						
						<li>
							<form name="photo_close_1" id="photo_close_1" action="" method="post">
								<input type="hidden" name="row_id" id="row_1" value="1">
								<input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
								<input type="hidden" name="action" value="AddClosejobPhoto">
								<div class="image_clos">
									<div class="cl_res_1">
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
									
								</div>	
								<div class="imag_info" <?php if($row['photo_1_caption']==""){ echo 'style=display:none'; } ?>>
									
									<strong><?php echo $row['photo_1_caption']; ?></strong>
									
								</div>
								<input type="submit" name="submit" value="submit" style="display:none;">
							</form>
						</li>
						<li>
							<form name="photo_close_2" id="photo_close_2" action="" method="post">
								<input type="hidden" name="row_id" id="row_2" value="2">
								<input type="hidden" name="job_id" value="<?php echo $id; ?>">
								<input type="hidden" name="action" value="AddClosejobPhoto">
								<div class="image_clos">
									<div class="cl_res_1">
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
									
								</div>	
								<div class="imag_info" <?php if($row['photo_2_caption']==""){ echo 'style=display:none'; } ?>>
									
									<strong><?php echo $row['photo_2_caption']; ?></strong>
									
								</div>
								<input type="submit" name="submit" value="submit" style="display:none;">
							</form>
						</li>
						<li>
							<form name="photo_close_3" id="photo_close_3" action="" method="post">
								<input type="hidden" name="row_id" id="row_3" value="3">
								<input type="hidden" name="job_id" value="<?php echo $id; ?>"> 
								<input type="hidden" name="action" value="AddClosejobPhoto">
								<div class="image_clos">
									<div class="cl_res_1">
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
									
								</div>	
								<div class="imag_info" <?php if($row['photo_3_caption']==""){ echo 'style=display:none'; } ?>>
									
									<strong><?php echo $row['photo_3_caption']; ?></strong>
									
								</div>
								<input type="submit" name="submit" value="submit" style="display:none;">
							</form>
						</li>
					</ul>

				</div>
			</div>
			<div class="closed_by_usr">
				<div class="inner_pdng">
					<div class="pull-right">
						<span>Closed by <b>by <?php echo get_user_detail($row['closed_by'],'first_name'); ?> <?php echo get_user_detail($row['closed_by'],'first_name'); ?></b></span>
						<!--<a href="javascript:void(0);" class="custom" onclick="insert_close_job();">Close Job</a>-->
						<img src="assets/images/loader.gif" id="close_job_loader" style="display:none;" />
					</div> 
					<div id="close_job_res"></div>
				</div>
			</div>
		</div>
	</div>
<?php	
}	
}

function AddManualInvoice()
{
	$cr_date=date('Y-m-d H:i:s');
	unset($_REQUEST['action']);
	$subject=$_REQUEST['subject']; 
	$vendor_id=$_REQUEST['vendor_id']; 
	$vendor_email=get_vendor_info($vendor_id,'email');
	$attach=$_FILES['attach']['name'];  
	
	$path='attachment/';
	$msg_id=0;
	$valid_formats = array("pdf","PDF");
	$image=date("YmdHis").$attach; 
	$image=str_replace(' ','',$image);
	$ext = pathinfo($image, PATHINFO_EXTENSION);
	if(in_array($ext,$valid_formats))
	{
		if(move_uploaded_file($_FILES["attach"]["tmp_name"], $path.$image))
		{
			$insert="insert into invoices(msg_id,subject,user_from,vendor_id,cr_date,attachment,cust_job,status,in_type) values ('".$msg_id."','".$subject."','".$vendor_email."','".$vendor_id."','".$cr_date."','".$image."','0','1','1')";
			$res=mysql_query($insert);
			echo "1";
		}	
	}
	else
	{
		echo "0";
	}	
}

function ShowEditCloseJob()
{
unset($_REQUEST['action']); 
$id=$_REQUEST['id'];
$sql="select * from customer_job where id='".$id."'";
$res=mysql_query($sql);
while($row=mysql_fetch_array($res))
{
$cust_id=$row['cust_id'];	
?> 
<script>
jQuery('#photo_close_1').validate({
submitHandler: function(form) {
	
	var loader='<center><img src="assets/image/loader.gif"></center>';
	jQuery('.cl_res_1').empty().append(loader);
	jQuery(form).ajaxSubmit({ 
		type: "POST",
		data: jQuery(form).serialize(),
		url: 'handler.php', 
		success: function(data) 
		{					
			jQuery('.cl_res_1').empty().append('<img src="thumb.php?src=close/'+data+'&amp;w=320&amp;h=250">'); 
			
		}
	});
}
});
	
jQuery('#photo_close_1').find('input[name=upload]').change(function()
{	
	jQuery('#photo_close_1').submit(); 
});

jQuery('#photo_close_2').validate({
submitHandler: function(form) {
	
	var loader='<center><img src="assets/image/loader.gif"></center>';
	jQuery('.cl_res_2').empty().append(loader);
	jQuery(form).ajaxSubmit({ 
		type: "POST",
		data: jQuery(form).serialize(),
		url: 'handler.php', 
		success: function(data) 
		{					
			jQuery('.cl_res_2').empty().append('<img src="thumb.php?src=close/'+data+'&amp;w=320&amp;h=250">'); 
			
		}
	});
}
});
	
jQuery('#photo_close_2').find('input[name=upload]').change(function()
{	
	jQuery('#photo_close_2').submit(); 
});

jQuery('#photo_close_3').validate({
submitHandler: function(form) {
	
	var loader='<center><img src="assets/image/loader.gif"></center>';
	jQuery('.cl_res_3').empty().append(loader);
	jQuery(form).ajaxSubmit({ 
		type: "POST",
		data: jQuery(form).serialize(),
		url: 'handler.php', 
		success: function(data) 
		{					
			jQuery('.cl_res_3').empty().append('<img src="thumb.php?src=close/'+data+'&amp;w=320&amp;h=250">'); 
			
		} 
	});
}
});
	
jQuery('#photo_close_3').find('input[name=upload]').change(function()
{	
	jQuery('#photo_close_3').submit();  
});

function insert_close_job()
{
	jQuery('#close_job_loader').show();
	var job_id=jQuery('#job_id').val();
	var po_num=jQuery('#po_num').val();
	var close_note=jQuery('#close_note').val(); 
	var ins_by=jQuery('#ins_by').val();  
	var cst_id=jQuery('#cst_id').val();  
	var caption_1=jQuery('#caption_1').val();  
	var caption_2=jQuery('#caption_2').val();  
	var caption_3=jQuery('#caption_3').val();  
	
	jQuery.ajax({type: "POST", 
	url: "handler.php",
	data: "id="+job_id+"&po_num="+po_num+"&close_note="+close_note+"&ins_by="+ins_by+"&caption_1="+caption_1+"&caption_2="+caption_2+"&caption_3="+caption_3+"&action=CloseJobSave",    
	success:function(result) 
	{
		jQuery('#close_job_loader').hide(); 
		jQuery('.close_job_res').empty().append('<div class="alert alert-success">Changes has been saved.</div>');
		location.reload();
	}, 
	error:function(e){ 
		console.log(e); 
	}	
	}); 
	
	
}

</script>
<a class="close_new" onclick="jQuery('.close_job_sec').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
<h4 class="close_title">CLOSE JOB</h4>
	<input type="hidden" id="ins_by" value="<?php echo $_SESSION['user_id']; ?>"/>
	<input type="hidden" id="job_id" value="<?php echo $id; ?>"/>
	<input type="hidden" id="cst_id" value="<?php echo $cust_id; ?>"/> 
	
	<div class="close_container">
		<div class="recen_cont display_block">
			<div class="inner_pdng">
				<h5 class="pull-left"><?php echo $row['name']; ?></h5>
				<span class="date pull-right"><?php echo date('F d,Y', strtotime($row['from_date'])); ?> - <?php echo date('F d,Y', strtotime($row['to_date'])); ?></span></div>
		</div>
		<div class="inner_content">
			<div class="inner_pdng">
				<div class="notifictn_no display_block">
					<div class="notfctn_info pull-left">
						<strong><?php echo $row['po']; ?></strong>
						<p><?php echo get_cust_detail($cust_id,'company') ?> - <?php echo get_cust_detail($cust_id,'work_add1') ?>, <?php echo get_cust_detail($cust_id,'work_add2') ?> <?php echo get_cust_detail($cust_id,'work_city') ?> <?php echo get_cust_detail($cust_id,'work_prov') ?> <?php echo get_cust_detail($cust_id,'work_post_code') ?></p>
					</div>
					<div class="ntfctn_btn pull-right">
						<input type="text" placeholder="Permit Notification Number" id="po_num" value="<?php echo $row['pno']; ?>" >
					</div>
				</div>
				<div class="closing_nts_cont">
					<h5>Closing Notes</h5>
					<textarea name="close_notes" id="close_note"><?php echo stripslashes($row['closing_notes']); ?></textarea>
				</div>
				<div class="close_lst">
					<ul>
						<li>
							<form name="photo_close_1" id="photo_close_1" action="" method="post">
								<input type="hidden" name="row_id" id="row_1" value="1">
								<input type="hidden" name="job_id" value="<?php echo $id; ?>">
								<input type="hidden" name="action" value="AddClosejobPhoto">
								<div class="image_clos">
									<div class="cl_res_1">
									<?php
									if($row['photo_1']!='')
									{	
									?>
									<img src="thumb.php?src=close/<?php echo $row['photo_1']; ?>&amp;w=320&amp;h=250">
									<?php
									}
									else
									{	
									?>
									<img src="assets/images/upload_bg.jpg" alt="">
									<?php
									}
									?>
									
										
									</div>
									<div class="fileUpload btn btn-primary">
										<span class="custom">Upload Photo</span>
										<input type="file" name="upload"  class="upload" />
									</div>
								</div>	
								<div class="imag_info">
									<input type="text" name="caption_1" id="caption_1" value="<?php echo $row['photo_1_caption']; ?>">
								</div>
								<input type="submit" name="submit" value="submit" style="display:none;">
							</form>
						</li>
						<li>
							<form name="photo_close_2" id="photo_close_2" action="" method="post">
								<input type="hidden" name="row_id" id="row_2" value="2">
								<input type="hidden" name="job_id" value="<?php echo $id; ?>">
								<input type="hidden" name="action" value="AddClosejobPhoto">
								<div class="image_clos">
									<div class="cl_res_2">
										<?php
										if($row['photo_2']!='')
										{	
										?>
										<img src="thumb.php?src=close/<?php echo $row['photo_2']; ?>&amp;w=320&amp;h=250">
										<?php
										}
										else
										{	
										?>
										<img src="assets/images/upload_bg.jpg" alt="">
										<?php
										}
										?>
									</div>
									<div class="fileUpload btn btn-primary">
										<span class="custom">Upload Photo</span>
										<input type="file" name="upload" class="upload" />
									</div>
								</div>	 
								<div class="imag_info">
									<input type="text" name="caption_1" id="caption_2" value="<?php echo $row['photo_2_caption']; ?>">
								</div>
								<input type="submit" name="submit" value="submit" style="display:none;">
							</form>
						</li>
						<li>
							<form name="photo_close_3" id="photo_close_3" action="" method="post">
								<input type="hidden" name="row_id" id="row_3" value="3">
								<input type="hidden" name="job_id" value="<?php echo $id; ?>"> 
								<input type="hidden" name="action" value="AddClosejobPhoto">
								<div class="image_clos">
									<div class="cl_res_3">
										<?php
										if($row['photo_3']!='')
										{	
										?>
											<img src="thumb.php?src=close/<?php echo $row['photo_3']; ?>&amp;w=320&amp;h=250">
										<?php 
										}
										else
										{	
										?>
										<img src="assets/images/upload_bg.jpg" alt="">
										<?php
										}
										?>
									</div> 
									<div class="fileUpload btn btn-primary">
										<span class="custom">Upload Photo</span>
										<input type="file" name="upload" class="upload"  />
									</div>
								</div>	
								<div class="imag_info">
									<input type="text" name="caption_1" id="caption_3" value="<?php echo $row['photo_3_caption']; ?>">
								</div>
								<input type="submit" name="submit" value="submit" style="display:none;">
							</form>
						</li>
					</ul>

				</div>
			</div>
			<div class="closed_by_usr">
				<div class="inner_pdng">
					<div class="pull-right">
						<span>Closed by <b>by <?php echo get_user_detail($_SESSION['user_id'],'first_name'); ?> <?php echo get_user_detail($_SESSION['user_id'],'first_name'); ?></b></span>
						<a href="javascript:void(0);" class="custom" onclick="insert_close_job();">Close Job</a>
						<img src="assets/images/loader.gif" id="close_job_loader" style="display:none;" />
					</div>
					<div id="close_job_res"></div>
				</div>
			</div>
		</div>
	</div>
<?php	
}
}

function AssignedInvoicesShow() 
{
$id=$_REQUEST['id'];   
$sql="SELECT a . * , b.id,b.vendor_id 'vendor',b.attachment,b.in_type
FROM assign_invoices AS a
INNER JOIN invoices AS b ON a.invoice_id = b.id where a.invoice_id='".$id."'";
$res=mysql_query($sql);	 
while($row=mysql_fetch_array($res))
{	

$vendor_name=get_vendor_info($row['vendor'],'name');
$custJob=get_customer_job_info($row['job_id'],'name'); 
$cust_id=get_customer_job_info($row['job_id'],'cust_id');
$custfName=get_cust_detail($cust_id,'first_name'); 
$custlName=get_cust_detail($cust_id,'last_name'); 
$custcmpny=get_cust_detail($cust_id,'company');

$custName=$custfName. ' '.$custlName;

if($custName=="")
{
	$custName=$custcmpny;
}	
 
?>
<div class="new_custmer">
<a class="close_new" onclick="jQuery('.show_cust_box').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
<h4 class="close_title">ASSIGNED INVOICES</h4>
</div>
<div class="close_container custmr_edt_cnt">
<div class="customers_container view_cstmr">
	<div class="close_mn display_block">
	<div class="cstm-wid top_inpt edit_table vndr_cnt customrs_cont">
	<div class="responsive-table">
	<table class="table">
		<thead>
			<tr>
				<th style="width: 3%;"></th>
				<th style="width: 18%;">Invoice #</th>
				<th style="width: 19%;">Date Added</th>
				<th style="width: 35%;">Vendor</th>
				<th style="text-align: center; width: 10%;"></th>
			</tr>
		</thead>
		<tbody>
		<td colspan="6" class="internal-table">
			<table class="table brdr brdr_none margin_non not-responsive">
				<tbody>
					<tr>
						<td style="width: 3%;"></td>
						<td style="width: 18%;"><?php echo $row['invoice_num']; ?></td>
						<td style="width: 19%;"><?php echo date('M d, Y', strtotime($row['cr_date'])); ?></td>
						<td style="width: 35%;"><?php echo $vendor_name; ?></td>
						<td style=" width: 10%;"><a target="_blank" href="<?php echo SITE_URL; ?>/attachment/<?php echo $row['attachment']; ?>" class="custom">View</a></td> 

					</tr>

					<tr>
						<td colspan="6" class="internal-table">
							<table class="table not-responsive">
								<tbody>
									<tr class="vrtcl_top">
										<td style="width: 3%;"></td>
										<td style="width: 18%;">
											<strong>Job Name</strong>
											<span><?php echo $custJob; ?> </span>
											
										</td>
										<td style="width: 19%;">
											<strong>Customer Name</strong>
											<span><?php echo $custName; ?></span>
											
										   
											
										</td>
										
										
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
	
	</div>
	</div>
	</div>
	</div>

<?php	
}	
}

function DelInvoice()
{
	unset($_REQUEST['action']); 
	$id=$_REQUEST['id'];
	$status=$_REQUEST['e'];
	if($status==1)
	{
		$up="update invoices set status='0',cust_job='0' where id='".$id."'";
		$res_up=mysql_query($up);
	}
	else
	{
		$up="update invoices set status='0',cust_job='0' where id='".$id."'";
		$res_up=mysql_query($up);
		
		$del="delete from assign_invoices where invoice_id='".$id."'";
		$res_del=mysql_query($del); 
	}
	
	echo "1";
	
}

function JobReportGenrate()
{
	unset($_REQUEST['action']); 
	$job_id=$_REQUEST['job_id'];
	$user_id=$_REQUEST['user_id'];
	$summary=mysql_real_escape_string($_REQUEST['summary']); 
	
	$sql="select * from report_gen where user_id='".$user_id."' and job_id='".$job_id."'";
	$res=mysql_query($sql);
	$count=mysql_num_rows($res);
	if($count==0)
	{
		$ins="insert into report_gen (job_id,user_id,summary) values ('".$job_id."','".$user_id."','".$summary."')";
		$res=mysql_query($ins);
	}
	else
	{
		$up="update report_gen set summary='".$summary."' where user_id='".$user_id."' and job_id='".$job_id."'";
		$res=mysql_query($up); 
	}		
	
	echo "1";
	
}

function EditPerInfo()
{
	unset($_REQUEST['action']); 
	$user_id=$_REQUEST['user_id']; 
	 $pwd = $_REQUEST['user_pass'];
	$user_pass=md5($pwd);
	
	
	$new_pass=md5($_REQUEST['new_pass']);  
	$username=get_user_detail($user_id,'username'); 
	
	 $sql="select * from users where username='".$username."' and password='".$user_pass."'";
	$res=mysql_query($sql);
	$count=mysql_num_rows($res);
	if($count==0)
	{
		echo "0";
	}	
	else
	{
		 $up="update users set password='".$new_pass."' where id='".$user_id."'"; 
		$res1=mysql_query($up);
		echo "1";
	}	
}

function ShowReportSection()
{
	unset($_REQUEST['action']); 
	 $user_id=$_REQUEST['user_id'];  
	 $job_id=$_REQUEST['job_id'];  
?>
<script>
jQuery('#gen_report').validate({
		
		
		submitHandler: function(form) {
			
			
			//jQuery('.report_result').empty().append('<div class="alert alert-warning">Your report is generated in short while...</div>');
			jQuery('.report_result').show(); 
			
			jQuery('#report_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(), 
				url: 'handler.php', 
				success: function(data) 
				{					
					if(data==1) 
					{	
						jQuery('#report_loader').hide();
						//jQuery('.report_result').empty().append('<div class="alert alert-success">Your report is generated...</div>'); 
						//jQuery('.report_result').fadeOut(3000);
						jQuery('#link_gen_report').show();
						//jQuery('.box').slideUp();
						//jQuery('#ramit_1').submit(); 
					} 
					
				}
			});
		}
		
	});
</script>
<div class="new_custmer">
<a class="close_new" onclick="jQuery('.create-report').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
</div>
<div class="inner_content">
	<div class="inner_pdng">
	<form name="gen_report" id="gen_report" action="" method="post" novalidate>
		<input type="hidden" value="<?php echo $job_id; ?>" name="job_id" id="job_id">
		<input type="hidden" value="<?php echo $user_id; ?>" name="user_id">
		<input type="hidden" name="action" value="JobReportGenrate">  
		<div class="top_cont display_block">  
			<div class="po_cont report-summary pull-left">
				
				<textarea placeholder="Report Summary" name="summary"></textarea> 
				
			</div>  
		</div>  
		<div class="closed_by_usr">
			<div class="pull-right">
				<button name="submit" class="custom" type="submit">Save</button>
				<a id="link_gen_report" style="display:none;" target="_blank" href="<?php echo SITE_URL; ?>pdf.php?job_id=<?php echo $job_id; ?>&user_id=<?php echo $user_id; ?>" class="custom">Create Report</a>
				<img src="assets/images/loader.gif" id="report_loader" style="display:none;">  
			</div>    
		</div>
	</form>
	<div class="report_result"></div>
	</div>
</div>
<?php	
}

function ShowReportTime()
{
	unset($_REQUEST['action']); 
	 $curr_monday=$_REQUEST['mon'];  
	 $curr_saturday=$_REQUEST['sat'];   
?>
<script>
jQuery('#add_customer').validate({
		
		rules: {
			'staff_id[]': {
				required: true 
			}
		},
		submitHandler: function(form) {
			var staff_id='';
			var curr_mon=jQuery('#add_customer').find('input[name=curr_monday]').val();
			var curr_sun=jQuery('#add_customer').find('input[name=curr_sunday]').val();
			
			
			
			jQuery.each(jQuery(".asd>select"), function()  
			{
				staff_id += (staff_id?',':'') + jQuery(this).val();
			});  
			
			
			
			var link='<a  class="custom edit_cstm" target="_blank" href=<?php echo SITE_URL; ?>pdft.php?mon='+curr_mon+'&sun='+curr_sun+'&staff_id='+staff_id+' onclick="hide_all();"> Create Report</a>';
			jQuery('.gen_report').show();
			jQuery('.gen_report').empty().append(link);  
			
			
		}   
		
	});
	

function hide_all()
{
	jQuery('.add_cust_box').hide(); 
}	

function get_value()
{
	
}

</script>
<div class="new_custmer">
<a href="javascript:void(0);" onclick="jQuery('.add_cust_box').slideUp();" class="close_new"><i class="fa fa-times" aria-hidden="true"></i></a>
<h4 class="close_title">NEW REPORT</h4>
</div>
<div class="close_container custmr_edt_cnt mb-20 pb-20">
<form method="post" action="" id="add_customer" name="add_customer" target="_blank">  
	<input type="hidden" value="AddCustomer" name="action">	
	
	<input type="hidden" value="<?php echo session_id(); ?>" id="session_id" name="session_id">	
	<input type="hidden" value="<?php echo $curr_monday ?>" name="curr_monday">	 
	<input type="hidden" value="<?php echo $curr_saturday; ?>" name="curr_sunday">	
	
	<div class="cstm-wid btm_inpt new_report">
		<div class="col-1">								 
			<ul>
				<li>
					<h4><?php echo date('F d, Y', strtotime($curr_monday)); ?> – <?php echo date('F d, Y', strtotime($curr_saturday)); ?></h4>
				</li>
			</ul>
		</div>
		<div class="col-1 second-col-1">
			<h4> Staff Name</h4>
			
			<ul>
				
				<li>
					<div class="selectn_box pb-20"> 
						<div class="asd">
							<select class="show-menu-arrow form-control valid" name="staff_id[]" id="staff_id" onchange="get_value();">
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
					<div class="text-center add-staff"><a onclick="add_timecard_staff_11();" class="custom edit_cstm" href="javascript:void(0);">Add Staff +</a></div>
				</li>
				
				
			</ul>
			
		</div>
		<div class="col-1 last-col-1 ">
			
			<ul>
				
				<li>
					<button name="submit" type="submit" class="custom edit_cstm">Save</button>
					<div class="gen_report" style="display:none;"></div>
				</li>
			</ul>
		</div>
		
	</div>
	
</form>

</div>
<?php	 
}

function ForgetPassword()
{
	unset($_REQUEST['action']); 
	$email=$_REQUEST['email']; 
	$count=user_email_exist($email);
	if($count==0)
	{
		echo "0";
	}
	else
	{
		
		$sql="select * from users where email='".$email."'";
		$res=mysql_query($sql);
		while($row=mysql_fetch_array($res))
		{
			$username=$row['username'];
			$user_id=$row['id'];
		}
		
		$fname=get_user_detail($user_id,'first_name');	
		$pass=uniqid();
		$pass_code=md5($pass);
		
		$update="update users set password='".$pass_code."' where id='".$user_id."'";
		$res_up=mysql_query($update); 
		
		$message='
		<p>Dear '.$fname.'</p> 
		<p>Your password has been reset</p>
		<p>You can login using below details<p>
		<p>Username : '.$username.'</p>
		<p>Password : '.$pass.'</p>
		<p>Please login: <a href="https://algonquinelectrical.pro" target="_blank">www.algonquinelectrical.pro</a></p> 
		';	

		$subject='Password Reset  | '.$fname;
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

		$headers .= 'From: Algonquin Electrical <noreply@algonquinelectrical.com>' . "\r\n";

		mail($email, $subject, $message, $headers);    
	}		
}

function Checklogintime()
{
	$asd=$_SESSION['user_id'];
	if(!empty($asd))
	{
		$today = date("Y-m-d H:i:s");
		$sql="select * from users where id='".$_SESSION['user_id']."'";
		$res=mysql_query($sql);
		while($row=mysql_fetch_array($res))
		{
			$login_time=$row['last_logged_in'];
		}	
		
		$start_date = new DateTime($login_time);
		$since_start = $start_date->diff(new DateTime($today));
		$min=$since_start->i;
		if($min > 480)  
		{	
			unset($_SESSION['user_id']);
		}
	}	 
}

function ShowNewTimecard()
{
unset($_REQUEST['action']); 
$job_id=$_REQUEST['job_id']; 
$insert_by=$_SESSION['user_id']; 

$cust_id=get_customer_job_info($job_id,'cust_id'); 
$cust_cmp=get_cust_detail($cust_id,'company');
if($cust_cmp=="")
{
	$cust_cmp=get_cust_detail($cust_id,'first_name').' '.get_cust_detail($cust_id,'last_name');
}	

$cr_date=date('Y-m-d');
$sql_count="select * from timecard where job_id='".$job_id."' and DATE(cr_date)='".$cr_date."'";
$sql_res=mysql_query($sql_count);
$count_11=mysql_num_rows($sql_res); 
?>
<script>
 jQuery('.filestyle').filestyle({
	buttonText: ' Upload'
});


jQuery('#new_timecardd').validate({
		rules: {
			'staff_id[]': {
				required: true 
			},
			vehicle_id: { 
				required: true 
			},
			veh_descp: {
				required: true 
			},
			km: {
				required: true 
			}
		},
		
		submitHandler: function(form) { 
			jQuery('.result_timecard').empty();
			jQuery('.result_timecard').show();
			jQuery('#new_timecard_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					
				
					jQuery('#new_timecard_loader').hide();
					jQuery('.result_timecard').empty().append('<div class="alert alert-success">Your timecard has been added</div>');
					jQuery('.result_timecard').fadeOut(3000); 
					location.reload(); 
					
				}
			});
		}
		
	});
	
jQuery('.form-control').each(function()
{
	if(jQuery(this).attr('disabled'))
	{
		jQuery(this).hide();
	}		
});

 jQuery( ".datepicker1" ).datepicker({
	changeMonth: false,
	changeYear: false,
	onSelect: function(year, month, inst) {
		var datValue=jQuery(this).val();
		var datValue= jQuery.datepicker.formatDate('yy-mm-dd', new Date(datValue));
		var curr_date=jQuery('#curr_date').val();
		var job_id=jQuery('#job_idd').val(); 
		
		jQuery.ajax({type: "POST",
		url: "handler.php",
		data: "sel_date="+datValue+"&job_id="+job_id+"&action=checkTimeCardde", 
		success:function(result)
		{
			if(result=='1') 
			{
				jQuery('.timecard-war').show();
			}
			else
			{
				jQuery('.timecard-war').hide(); 
			}		
		},
		error:function(e){
			console.log(e);
		}	
		});
	}
	 
});	
	
</script>
<div class="new_custmer">
<a class="close_new" href="javascript:void(0);" onclick="jQuery('.new-timcrd').slideUp();" class="close_new"><i class="fa fa-times" aria-hidden="true"></i></a>
</div>
<div class="inner_content">
	
	<div class="timecard-war" style="<?php if($count_11 > 0) { echo 'display:block;'; } else {  echo 'display:none;'; } ?>">
		<div class="alert alert-warning">You are attempting to add a second timecard with the same date. Would you like to continue?</div>	
	</div>
	
	<div class="inner_pdng">
	<form name="new_timecardd" id="new_timecardd" action="" method="post" target="_blank" class="veh_form">
		<input type="hidden" name="job_id" id="job_idd" value="<?php echo $job_id; ?>" />
		<input type="hidden" name="insert_by" value="<?php echo $insert_by; ?>" />
		<input type="hidden" name="action" value="newTimeCard" />  
		<input type="hidden" id="curr_date" value="<?php echo date('Y-m-d'); ?>" />  
		
		<h4 class="close_title">NEW TIMECARD</h4>
		<div class="timcrd_lft pull-left">
		<div class="edit-clndr">
			<div class="inr_date">
		
			<h3 class="date"><?php echo date('M d, Y'); ?></h3>
			<span><a href="javascript:void(0);" onclick="jQuery('#ed_cr_date1').show();"><i aria-hidden="true" class="fa fa-calendar"></i></a></span>
			</div>
			<div class="cstm_clndr">
			<input type="text" id="ed_cr_date1" name="cr_date" class="datepicker1 edt-calndr" placeholder="<?php echo date('m/d/Y', strtotime($cr_date)); ?>" value="<?php echo date('m/d/Y', strtotime($cr_date)); ?>" style="display:none;" readonly>   
			</div> 
			</div> 
			<hr>
			<strong>UPLOAD PHOTOS</strong>
			<ul>
				<li>
					<div class="form-group">
						<div class="left_inpt pull-left">
							<input type="text" name="caption_1" placeholder="Caption" class="caption form-control ">
						</div>
						<div class="pull-right file_upld">
							<input type="file" name="photo_1" class="filestyle" data-icon="false" >
						</div>
					</div>
				</li>
				<li>
					<div class="form-group">
						<div class="left_inpt pull-left">
							<input type="text" name="caption_2" placeholder="Caption" class="caption form-control">
						</div>
						<div class="pull-right file_upld">
							<input type="file" name="photo_2" class="filestyle" data-icon="false">
						</div>
					</div>
				</li>
				<li>
					<div class="form-group">
						<div class="left_inpt pull-left">
							<input type="text" name="caption_3" placeholder="Caption" class="caption form-control "> 
						</div>		
						<div class="pull-right file_upld">
							<input type="file" name="photo_3" class="filestyle" data-icon="false">
						</div>
					</div>
				</li>

			</ul>
		</div>
		<div class="timcrd_rt pull-right">
			<div class="selectn_box"> 
				<ul>
					<li class="pull-left">
						<select id="staff_id" name="staff_id[]" class="show-menu-arrow form-control">
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
								<option value="<?php echo $staff_id; ?>" <?php if($staff_id==$user_id) { echo 'selected'; } ?>><?php echo $name; ?></option>
							<?php
							}
							?>
						</select>
					</li>
					<li class="pull-right">
						<input type="text" placeholder="Hours" name="hours[]">
					</li>
				</ul>
			</div>
			<a href="javascript:void(0);" class="custom" onclick="add_timecard_staff();">Add Staff &#43;</a>
			
			<div class="selectn_boxx"> 
				<ul>
					<li class="pull-left">
						<select id="vehicle_id" name="vehicle_id" class="show-menu-arrow form-control">
							<option value="">Select Vehicle</option>
							<?php
							$sql="select * from vehicle where status='1'";
							$res=mysql_query($sql);
							while($row=mysql_fetch_array($res))
							{	
								$veh_id=$row['id'];
								$vehicle_num=$row['vehicle_num'];
							?>
								<option value="<?php echo $veh_id; ?>"><?php echo $vehicle_num; ?></option>
							<?php
							}
							?>
						</select>
						
						
					</li>
					<li><input placeholder="Description" name="veh_descp" type="text" value="<?php echo $cust_cmp;  ?>"></li>
					
					<li class="pull-right">
						<input placeholder="KM" name="km" type="text" maxlength="4">
					</li>
				</ul>
			</div>	
			<textarea placeholder="Type your notes here…" name="notes"></textarea>
		</div>

		<div class="closed_by_usr bg-transparent">
			<div class="inner_pdng">
				<div class="pull-right">
				   <span>Created by <b> <?php echo get_user_detail($_SESSION['user_id'],'first_name'); ?> <?php echo get_user_detail($_SESSION['user_id'],'last_name'); ?></b></span> 
				   <button class="custom" type="submit" name="submit">Create</button>
				   <img src="assets/images/loader.gif" id="new_timecard_loader" style="display:none;">	
				</div>
			</div>
		</div>
	</form>	
	<div class="result_timecard"></div>
	</div>
</div>
<?php	
}

function checkTimeCardde()
{
	unset($_REQUEST['action']); 
	$job_id=$_REQUEST['job_id']; 
	$sel_date=$_REQUEST['sel_date']; 
	$sql="select * from timecard where job_id='".$job_id."' and DATE(cr_date)='".$sel_date."'";
	$res=mysql_query($sql);
	$count=mysql_num_rows($res); 
	if($count > 0) 
	{
		echo "1";
	}	
	else
	{
		echo "0";
	}	
	
}
function ShowMaterial()
{
	unset($_REQUEST['action']); 
	$id=$_REQUEST['id']; 	
		
	$sql="select * from job_material where id='".$id."'";
	$res=mysql_query($sql);	
	while($row=mysql_fetch_array($res))
	{
		$cr_date=$row['cr_date'];
		$cr_date=date('m/d/Y', strtotime($cr_date));
		$qty=$row['qty'];
		$part=$row['part'];
		$descp=$row['descp'];
		$insert_by=$row['insert_by'];
	}	
?>
<script>

jQuery( ".datepicker" ).datepicker({
        changeMonth: false,
        changeYear: false,
		 
    });
	

jQuery('#edit_material').validate({
		rules: {
			qty: {
				required: true 
			},
			part: {
				required: true  
			}
		},
		submitHandler: function(form) { 
			jQuery('.edit_result_mat').empty();
			jQuery('.edit_result_mat').show();
			jQuery('#edit_mat_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					
					jQuery('#edit_mat_loader').hide();
					jQuery('.edit_result_mat').empty().append('<div class="alert alert-success">Your material has been updated</div>');  
					jQuery('.edit_result_mat').fadeOut(3000); 
					location.reload();   
					
				}
			});
		}
		
	});
	
	
	
</script>
<div class="new_custmer">
<a href="javascript:void(0);" onclick="jQuery('.edit_material').slideUp();" class="close_new"><i class="fa fa-times" aria-hidden="true"></i></a>
</div>
<div class="inner_content">
	<div class="inner_pdng">
	<form method="post" action="" id="edit_material" name="edit_material" class="materl_cntnt">
		<input id="id" name="id" value="<?php echo $id; ?>" type="hidden">
		<input value="editmaterial" name="action" type="hidden"> 
		<div class="top_cont display_block ">  
			<div class="pull-left material_sec">
				<ul class=" edit-mtrl">
					<li>
						<input readonly value="<?php echo $cr_date; ?>" class="datepicker" name="cr_date" placeholder="<?php echo $cr_date; ?>"  type="text">
						<div class="calndr_icns"> 
							<i aria-hidden="true" class="fa fa-calendar"></i>
						</div>
					</li>
					<li>
						<input  placeholder="Quatity" name="qty" type="text" value="<?php echo $qty; ?>">
					</li> 
					<li> 
						<input  placeholder="Part" name="part" type="text" value="<?php echo $part; ?>">
					</li> 
					<li class="last-margin">
						<input  placeholder="Description" name="descp" type="text" value="<?php echo $descp; ?>"> 
					</li>  
					
				</ul> 

			</div>

			
		</div>
	
		
		<div class="closed_by_usr">
			<div class="pull-right">
				<span>Created by <b> <?php echo get_user_detail($insert_by,'first_name'); ?> <?php echo get_user_detail($insert_by,'last_name'); ?></b></span>  
				<button type="submit" class="custom" name="submit">Save</button>
				<img style="display:none;" id="edit_mat_loader" src="assets/images/loader.gif">
			</div> 
		</div>
	</form>
	<div class="edit_result_mat"></div> 
	</div>
</div>
<?php	
}

function AddNewmaterial()
{
	unset($_REQUEST['action']); 
	$count=$_REQUEST['count']; 
	$count1=$count+1;
	$today=date('Y-m-d');
	$today_date=date('m/d/Y', strtotime($today));	
	
?>

<ul class="mat_<?php echo $count1; ?> edit-mtrl">
	
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
		<a href="javascript:void(0);" onclick="remove_material('1','<?php echo $count1; ?>');"><i class="fa fa-times" aria-hidden="true"></i></a>
	</div>
	</li> 
	
	</ul>

<?php
}

function Addmaterial()
{
	unset($_REQUEST['action']); 
	$job_id=$_REQUEST['job_id']; 
	$insert_by=$_REQUEST['job_insert_by']; 
	$cr_date=$_REQUEST['cr_date']; 
	$qty=$_REQUEST['qty']; 
	$part=$_REQUEST['part']; 
	$descp=$_REQUEST['descp'];  
	$i=0;
	foreach($cr_date as $val)
	{
		$cr_datee=date('Y-m-d', strtotime($val));
		$qtyy=$qty[$i];
		$partt=$part[$i];
		$descpp=$descp[$i];
		
		$sql="insert into job_material (job_id,cr_date,qty,part,descp,status,insert_by) values ('".$job_id."','".$cr_datee."','".$qtyy."','".$partt."','".$descpp."','1','".$insert_by."')";
		$res=mysql_query($sql);
		 
	$i++;
	}
	 
	
}


function editmaterial()
{
	unset($_REQUEST['action']); 
	$id=$_REQUEST['id']; 
	$cr_date=$_REQUEST['cr_date']; 
	$cr_date=date('Y-m-d', strtotime($cr_date));
	$qty=$_REQUEST['qty']; 
	$part=$_REQUEST['part']; 
	$descp=$_REQUEST['descp']; 
	$sql="update job_material set cr_date='".$cr_date."',qty='".$qty."',part='".$part."',descp='".$descp."' where id='".$id."'";
	$res=mysql_query($sql);
	
}
function DeleteMaterial(){
	$id=$_REQUEST['id'];
	$sql = "DELETE FROM job_material WHERE id ='".$id."'";
	$res=mysql_query($sql);	
		if($res){
			echo '1';
		}else{
			echo '0';
		}
}


function AddClosedInvoiced()
{
	$today = date("Y-m-d H:i:s");
	unset($_REQUEST['action']); 
	$req=$_REQUEST['e']; 
	$job_id=$_REQUEST['job_id']; 
	
	
	if($req==1)
	{
		$invoice=$_REQUEST['invoice']; 
		$del="delete from invoiced_jobs where job_id='".$job_id."'";
		$res_del=mysql_query($del);
		
		$sql_ins="insert into invoiced_jobs (job_id,cr_date) values ('".$job_id."','".$today."')";
		$res_ins=mysql_query($sql_ins);  
		
		$sql="update customer_job set invoice_number='".$invoice."' where id='".$job_id."'";
		$res=mysql_query($sql);    
	}
	else
	{
		$del="delete from invoiced_jobs where job_id='".$job_id."'";
		$res_del=mysql_query($del);
	}		
	echo "1";  
	
}


function AddClosedInvoicedd()  
{
	$today = date("Y-m-d H:i:s");
	unset($_REQUEST['action']); 
	$req=$_REQUEST['e']; 
	$job_id=$_REQUEST['job_id']; 
	if($req==0)
	{	
		$ins="insert into closed_job_dashboard(job_id) values ('".$job_id."')"; 
	}
	else
	{
		$ins="delete from closed_job_dashboard where job_id='".$job_id."'";   
	}		
	$res=mysql_query($ins);   
	echo "1";  
	
}

function AddCloseJobInvoice()
{
	unset($_REQUEST['action']); 
	$job_id=$_REQUEST['job_id']; 
	$invoice=$_REQUEST['invoice']; 
	$sql="update customer_job set invoice_number='".$invoice."' where id='".$job_id."'";
	$res=mysql_query($sql);  
	
	$ins="insert into invoiced_jobs(job_id,cr_date) values ('".$job_id."','".$today."')";
	$res=mysql_query($ins);  
	
	echo $invoice;
}

?>