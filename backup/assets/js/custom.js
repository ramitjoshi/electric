jQuery(document).ready(function()
{
	//User Validate
	jQuery('#login').validate({
		
		rules: {
			username: {
				required: true
			},
			
			user_pass: {
				required: true
			} 
		},
			
		submitHandler: function(form) {
			jQuery('.result').empty();			
			jQuery('.result').show();	
			jQuery('#login_loader').show();	
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{
					if(data=="0")
					{
						jQuery('#login_loader').hide();	
						jQuery('.result').empty().append('<div class="alert alert-danger">Your account is currently locked. Please contact your system administrator.</div>');
						jQuery('.result').fadeOut(3000);  
					}	
					else if(data=="1")
					{
						jQuery('#login_loader').hide();	  
						jQuery('.result').empty().append('<div class="alert alert-danger">Your username or password was not accepted. Please login again.</div>');
						jQuery('.result').fadeOut(3000);
					}	
					else if(data=="2") 
					{
						jQuery('#login_loader').hide();	
						jQuery('.result').empty().append('<div class="alert alert-danger">Your username or password was not accepted. Please login again.</div>');
						jQuery('.result').fadeOut(3000);
					}	
					else if(data=="5") 
					{
						window.location.href='1.php';    
					}	
					else
					{
						window.location.href='?section=dashboard';  
					}	
				}
			});
		}
		
	});
		
	
	jQuery('#for_pass').validate({
		
		rules: {
			email: {
				required: true,
				email: true
			}
		},
			
		submitHandler: function(form) {
			jQuery('.result').empty();			
			jQuery('.result').show();	
			jQuery('#login_loader').show();	
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{
					if(data=="0")
					{
						jQuery('#login_loader').hide();	
						jQuery('.result').empty().append('<div class="alert alert-danger">This email is not exists</div>');
						jQuery('.result').fadeOut(3000);  
					}	
					else 
					{
						jQuery('#login_loader').hide();	
						jQuery('.result').empty().append('<div class="alert alert-danger">Your password has been reset. Please check your email</div>');
						jQuery('.result').fadeOut(3000); 
					}	
					
				}
			});
		}
		
	});
	
		
	// Add Staff
	jQuery('#add_staff').validate({
		
		rules: {
			fname: {
				required: true
			},
			lname: {
				required: true
			},
			email: {
				
				email: true
			}
			
			
		},
			
		submitHandler: function(form) {
			jQuery('.result').empty();
			jQuery('.result').show();
			jQuery('#addSaff_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					jQuery('#addSaff_loader').hide();
					if(data==0)
					{
						jQuery('.result').empty().append('<div class="alert alert-danger">Username already exists</div>');jQuery('.result').fadeOut(3000);
					}	
					else if(data==2)
					{
						jQuery('.result').empty().append('<div class="alert alert-danger">Email already exists</div>');
						jQuery('.result').fadeOut(3000);
					}	
					else
					{
						jQuery('.result').empty().append('<div class="alert alert-success">Staff has been added</div>');
						jQuery('#add_staff').trigger('reset');						
						jQuery('.result').fadeOut(3000);						
						location.reload();
					}
						
					
					
				}
			});
		}
		
	});
	
	
	// Add Customer
	jQuery('#add_customer').validate({
		
		rules: {
			fname: {
				required: true
			},
			lname: {
				required: true
			},
			email: {
				email: true
			},
			
			
		},
			
		submitHandler: function(form) {
			jQuery('.result').empty();
			jQuery('.result').show();
			jQuery('#cust_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					jQuery('#cust_loader').hide();
					if(data==1)
					{
						jQuery('.result').empty().append('<div class="alert alert-success">Customer Added</div>');
						jQuery('.result').fadeOut(3000); 
						location.reload();
					}	
					
				}
			});
		}
		
	});
	
	
	// Add Customer Job
	
	
	// Edit Customer Job
	jQuery('#edit_job').validate({
		
		rules: {
			cust_name: {
				required: true
			},
			po_num: {
				required: true
			},
			from_date: {
				required: true
			},
			to_date: {
				required: true
			},
			job_name: {
				required: true
			}
			
			
			
		},
			
		submitHandler: function(form) {
			jQuery('.result_job').empty();
			jQuery('.result_job').show();
			jQuery('#cust_job_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					
					jQuery('#cust_job_loader').hide();
					jQuery('.result_job').empty().append('<div class="alert alert-success">Job updated successfully</div>');
					jQuery('.result_job').fadeOut(3000); 
					location.reload();  
					
				}
			});
		}
		
	});
	
	// Add Time Card
	
	
	// Add Vendor
	jQuery('#add_vendor').validate({
		
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
			
			
		},
			
		submitHandler: function(form) {
			jQuery('.result').empty();
			jQuery('.result').show();
			jQuery('#cust_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					jQuery('#cust_loader').hide();
					
					if(data==1)
					{
						jQuery('.result').empty().append('<div class="alert alert-success">Vendor Added</div>');
						jQuery('.result').fadeOut(3000); 
						location.reload();
					}
					else
					{
						jQuery('.result').empty().append('<div class="alert alert-danger">Email already exists</div>');
						jQuery('.result').fadeOut(3000);  
					}	
					
				}
			});
		}
		
	});
	
	
	// Add Manual Invoices
	jQuery('#add_man_invoice').validate({
		
		rules: {
			subject: {
				required: true
			},
			vendor_id: {
				required: true
			},
			attach: { 
				required: true
			}
			
			
		},
			
		submitHandler: function(form) {
			jQuery('.result').empty();
			jQuery('.result').show();
			jQuery('#man_invoice_loader').show();
			jQuery(form).ajaxSubmit({
				type: "POST",
				data: jQuery(form).serialize(),
				url: 'handler.php', 
				success: function(data) 
				{					
					jQuery('#man_invoice_loader').hide();
					
					if(data==1)
					{
						jQuery('.result').empty().append('<div class="alert alert-success">Invoice has been Genrated</div>');
						jQuery('.result').fadeOut(3000);  
						location.reload();
					}
					else
					{
						jQuery('.result').empty().append('<div class="alert alert-danger">Check your attachment.</div>');
						jQuery('.result').fadeOut(3000);   
					}	
					
				}
			});
		}
		
	});
	
	
	
	
	
});

function freeze_user(status,user_id)
{
	var status=status;
	var user_id=user_id;
	jQuery('#editSaff_loader').show();
	jQuery.ajax({type: "POST", 
	url: "handler.php",
	data: "user_id="+user_id+"&status="+status+"&action=FreezeUser", 
	success:function(result) 
	{
		jQuery('#editSaff_loader').hide();
		if(result==0)
		{
			jQuery('.freeze_sec_'+user_id).empty().append('<a onclick="freeze_user(1,10);" class="custom edit_cstm" href="javascript:void(0);">Un Freeze</a>')
		}
		else
		{
			jQuery('.freeze_sec_'+user_id).empty().append('<a onclick="freeze_user(0,10);" class="custom edit_cstm" href="javascript:void(0);">Freeze</a>') 
		}	 	 
	},
	error:function(e)
	{
		console.log(e);
	}	
	}); 
}