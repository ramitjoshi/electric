jQuery(function($) {
	
	jQuery('.numbersOnly').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	
	
	jQuery('#add_vehicle').validate({
		
		rules: {
			vehicle_num: {
				required: true
			},
			year: {
				required: true
			},
			make: { 
				required: true
			},
			model: {
				required: true
			}
			,
			km: {
				required: true
			}
		},
			
		submitHandler: function(form) {
			jQuery('#cust_loader').show();			
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'vehicle-handler.php', 
				success: function(data) 
				{
					jQuery('#cust_loader').hide();	
					if(data==1) 
					{ 
						toastr.success("Vehicle has been added"); 
						location.reload();
					}
					else
					{
						toastr.error("Vehicle number already exists"); 
					}
				} 
			});
		}
		
	});
	
	jQuery('#edit_vehicle').validate({
		
		rules: {
			vehicle_num: {
				required: true
			},
			year: {
				required: true
			},
			make: { 
				required: true
			},
			model: {
				required: true
			}
			,
			km: {
				required: true
			}
		},
			
		submitHandler: function(form) {
			jQuery('#cust_loader1').show();		 	
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'vehicle-handler.php', 
				success: function(data) 
				{
					jQuery('#cust_loader1').hide();	 
					if(data==1)  
					{ 
						toastr.success("Vehicle has been updated"); 
						location.reload();
					}
					else
					{
						toastr.error("Vehicle number already exists"); 
					}
				} 
			});
		}
		
	});
	
	jQuery('#add_mileage').validate({
		
		rules: {
			mil_veh_id: {
				required: true
			},
			mil_descp: {
				required: true
			},
			mil_km: { 
				required: true
			}
		},
			
		submitHandler: function(form) {
			jQuery('#man_invoice_loader').show();		 	
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'vehicle-handler.php', 
				success: function(data) 
				{
					jQuery('#man_invoice_loader').hide();	 
					if(data==1)  
					{ 
						toastr.success("Mileage has been updated"); 
						location.reload();
					}
					
				} 
			});
		}
		
	});
	
});

function editvehicleForm(id)
{
	jQuery('.box').hide();
	jQuery('#fromToggle').slideUp();
	var loader='<img src="assets/images/loader.gif" class="loader-ani" />';
	jQuery('#fromToggle').slideDown();
	jQuery('#fromToggle').empty().append(loader); 
	jQuery.ajax({type: "POST",
	url: "vehicle-handler.php", 
	data: "id="+id+"&action=ShowVehicleForm", 
	success:function(result) 
	{
		jQuery('#fromToggle').empty().append(result);  
	},
	error:function(e){ 
		console.log(e); 
	}	
	});  
}

function freeze_vehicle(veh_id,e)
{
	jQuery('#cust_loader1').show();	 
	jQuery.ajax({type: "POST",
	url: "vehicle-handler.php", 
	data: "veh_id="+veh_id+"&status="+e+"&action=FreezeVehicle", 
	success:function(result)  
	{
		jQuery('#cust_loader1').hide(); 	 
		if(e==0)
		{
			var loader='<a class="custom edit_cstm" href="javascript:void(0);" onclick="freeze_vehicle('+veh_id+',1);">UnFreeze</a>';
		}
		else
		{
			var loader='<a class="custom edit_cstm" href="javascript:void(0);" onclick="freeze_vehicle('+veh_id+',0);">Freeze</a>';
		}
		jQuery('.freeze_ac').empty().append(loader);

		
	},
	error:function(e){ 
		console.log(e);
	}	
	});  
}

function gen_vehicle_rep()
{ 
	
	var val=jQuery('select[name=mil_veh_rep]').val();
	
	if(val!="")
	{
		jQuery('.veh_rep_btn').attr('href','vehicle-report.php?id='+val);
	}	
}