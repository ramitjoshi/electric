jQuery(function($) {
	
	jQuery('.numbersOnly').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	
	
	jQuery( ".datepicker" ).datepicker({
	changeMonth: false,
	changeYear: false,
	});	
	
	
	jQuery('.format').keyup(function()
	{
		var asd=jQuery(this).val()
		asd=number_format(asd);
		jQuery(this).val(asd); 
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
	
	jQuery('#gen_veh_report').validate({
		
		rules: {
			rep_start_date: {
				required: true
			},
			rep_end_date: {
				required: true
			},
			rep_veh_id: { 
				required: true
			}
		},
			
		submitHandler: function(form) {
			jQuery('#gen_veh_rep_ld').show();	
			var veh_id=jQuery('select[name=rep_veh_id]').val();	
			var start=jQuery('input[name=rep_start_date]').val();	
			var end=jQuery('input[name=rep_end_date]').val();	
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(), 
				url: 'vehicle-handler.php', 
				success: function(data) 
				{
					jQuery('#gen_veh_rep_ld').hide();	
					if(data==0)
					{
						toastr.error("No timecard with selected Vehicle"); 
					}
					else if(data==2)
					{
						toastr.error("Check Dates"); 
					} 
					else
					{
						jQuery('#gen_veh_report').removeClass('gen_btn');
						var asd='<a class="custom edit_cstm" target="_blank" href="vehicle-report.php?id='+veh_id+'&start='+start+'&end='+end+'">Generate Report</a>'
						jQuery('.gen_rep_box').empty().append(asd);
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
		location.reload();  
		if(e==0)
		{
			var loader='<a class="custom edit_cstm" href="javascript:void(0);" onclick="freeze_vehicle('+veh_id+',1);">Active</a>';
		}
		else
		{
			var loader='<a class="custom edit_cstm" href="javascript:void(0);" onclick="freeze_vehicle('+veh_id+',0);">Inactive</a>';
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
		jQuery('.veh_rep_btn').attr('href','https://algonquinelectrical.pro/dev/vehicle-report.php?id='+val);
	}	
}

function number_format(user_input){
    var filtered_number = user_input.replace(/[^0-9]/gi, '');
    var length = filtered_number.length;
    var breakpoint = 1;
    var formated_number = '';

    for(i = 1; i <= length; i++){
        if(breakpoint > 3){
            breakpoint = 1;
            formated_number = ',' + formated_number;
        }
        var next_letter = i + 1;
        formated_number = filtered_number.substring(length - i, length - (i - 1)) + formated_number; 

        breakpoint++;
    }

    return formated_number;
}