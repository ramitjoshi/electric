<?php
require_once  'setup.php';
$action=$_REQUEST['action'];

//get requested action
if(isset($_REQUEST['action']) && !empty($_REQUEST['action']))
{
   $action = $_REQUEST['action'];
   call_user_func($action);
}

function AddVehicle()
{
	$today = date("Y-m-d H:i:s");
	$action = $_REQUEST['action'];
	$vehicle_num = $_REQUEST['vehicle_num'];
	$year = $_REQUEST['year'];
	$make = $_REQUEST['make'];
	$model = $_REQUEST['model'];
	$km = $_REQUEST['km'];
	
	$count=get_vehicle_num_info($vehicle_num); 
	if($count > 0)
	{
		echo "0";
	}
	else 
	{		
		$sql="insert into vehicle (vehicle_num,year,make,model,km,cr_date,status) values ('".$vehicle_num."','".$year."','".$make."','".$model."','".$km."','".$today."','1')";
		$res=mysql_query($sql);
		echo "1";
	}
}	

function ShowVehicleForm() 
{
	$today = date("Y-m-d H:i:s");
	$action = $_REQUEST['action'];
	$vehicle_id = $_REQUEST['id']; 
	include 'vehicle/edit_vehicle.php';
}

function EditVehicle()  
{
	$today = date("Y-m-d H:i:s");
	
	$veh_id = $_REQUEST['veh_id'];
	$vehicle_num = $_REQUEST['vehicle_num'];
	$year = $_REQUEST['year'];
	$make = $_REQUEST['make'];
	$model = $_REQUEST['model'];
	$km = $_REQUEST['km'];
	
	$sql="update vehicle set vehicle_num='".$vehicle_num."',year='".$year."',make='".$make."',model='".$model."',km='".$km."' where id='".$veh_id."'";
	$res=mysql_query($sql);
	echo "1"; 
}

function FreezeVehicle()
{
	$veh_id = $_REQUEST['veh_id'];
	$vehicle_num = $_REQUEST['vehicle_num'];
	$status = $_REQUEST['status'];
		
	$sql="update vehicle set status='".$status."' where id='".$veh_id."'";
	$res=mysql_query($sql);
	echo "1";  
}

function AddMilege()
{
	$today = date("Y-m-d H:i:s");
	$veh_id = $_REQUEST['mil_veh_id'];
	$descp = $_REQUEST['mil_descp'];
	$km = $_REQUEST['mil_km']; 
	
	$sql="insert into mileage (vehicle_id,descp,km,cr_date,status) values ('".$veh_id."','".$descp."','".$km."','".$today."','1')";
	$res=mysql_query($sql);
	echo "1"; 
	
}

?>