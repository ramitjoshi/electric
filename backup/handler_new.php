<?php
require_once  'setup.php';
$action=$_REQUEST['action'];

//get requested action
if(isset($_REQUEST['action']) && !empty($_REQUEST['action']))
{
   $action = $_REQUEST['action'];
   call_user_func($action);
}

function ShowInvoiceForm()
{
unset($_REQUEST['action']);
$job_id = $_REQUEST['job_id']; 
?>
<script>
jQuery('#closed_job_invoice').validate({
		
	rules: { 
		invoice: {  
			required: true
		}
	},
		
	submitHandler: function(form) {
		jQuery('.result').empty();
		jQuery('.result').show();
		jQuery('#addinvoice_loader').show();
		jQuery(form).ajaxSubmit({
			type: "POST",
			data: jQuery(form).serialize(),
			url: 'handler.php', 
			success: function(data) 
			{					
				var job_id=jQuery('input[name=job_id]').val();
				
				jQuery('#addinvoice_loader').modal('hide');
				jQuery('.close_in_'+job_id).html(data); 
				jQuery('#myModal').hide();  
				jQuery('.cl_job_'+job_id).empty().append('<a href="javascript:void(0)" class="invoiced-no " onclick="cl_invoiced(0,'+job_id+');"><i class="fa blue fa-check-circle-o"></i></a>');   	
				
			}
		});
	}
	
});	
</script>	

<form name="closed_job_invoice" id="closed_job_invoice" action="" method="post" class="new_cstmr">
<input type="hidden" name="job_id" value="<?php echo $job_id ?>">
<input name="action" value="AddCloseJobInvoice" type="hidden">

  <div class="form-group">
    <label for="exampleInputEmail1">Invoice Number</label>
    <input name="invoice" placeholder="#Invoice Number" type="text">
  </div>
  <button type="submit" class="custom edit_cstm">Save</button>
  <img src="assets/images/loader.gif" id="addinvoice_loader" style="display:none;">	
</form> 
<div class="result"></div>
	
	<?php
}



?>