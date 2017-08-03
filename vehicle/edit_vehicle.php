<?php $vehicle_id; ?>
<script src="<?php echo SITE_URL; ?>assets/js/vehicle.js"></script>	
<div class="new_custmer">
<a class="close_new" onclick="jQuery('#fromToggle').slideUp();" href="javascript:void(0);"><i aria-hidden="true" class="fa fa-times"></i></a>
<h4 class="close_title">EDIT VEHICLE</h4>
</div>
<div class="close_container custmr_edt_cnt">
<form name="edit_vehicle" id="edit_vehicle" action="" method="post"> 
	<input type="hidden" name="action" value="EditVehicle">	
	<input type="hidden" name="veh_id" value="<?php echo $vehicle_id; ?>">	
	<div class="cstm-wid top_inpt">    
		<ul> 
			<li> 
				<input class="" type="text" name="vehicle_num" placeholder="Vehicle #" value="<?php echo get_vehicle_info($vehicle_id,'vehicle_num'); ?>" maxlength="8">
			</li> 
			<li>
				<input class="numbersOnly" type="text" name="year" placeholder="Year" value="<?php echo get_vehicle_info($vehicle_id,'year'); ?>" maxlength="4">
			</li>
			<li>
				<input type="text" name="make" placeholder="Make" value="<?php echo get_vehicle_info($vehicle_id,'make'); ?>" maxlength="15">
			</li>
			<li>
				<input type="text" name="model" placeholder="Model" value="<?php echo get_vehicle_info($vehicle_id,'model'); ?>"> 
			</li>
			<li>
				<input type="text" class="format" name="km" placeholder="KMs (start of year)" value="<?php echo get_vehicle_info($vehicle_id,'km'); ?>"> 
			</li> 
		</ul> 
	</div>

	
	<div class="btn_deit">
		<?php
		$status=get_vehicle_info($vehicle_id,'status'); 
		if($status==1)
		{	
		?>
			<div class="freeze_ac"><a class="custom edit_cstm" href="javascript:void(0);" onclick="freeze_vehicle('<?php echo $vehicle_id; ?>','0');">Inactive</a></div>
		<?php
		}
		else
		{	
		?>
			<div class="freeze_ac"><a class="custom edit_cstm" href="javascript:void(0);" onclick="freeze_vehicle('<?php echo $vehicle_id; ?>','1');">Active</a></div>
		<?php
		}
		?>
		<button type="submit" class="custom edit_cstm" name="submit">Save</button>
		<img id="cust_loader1" src="assets/images/loader.gif" style="display:none;" />
	</div>
</form>
<div class="result"></div>
</div>