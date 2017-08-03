<?php
require 'setup.php';
$section = $_REQUEST['section']; 
if(!isset($_REQUEST['section']))
{
	$section='login';
?>
<script>window.location.href="<?php echo SITE_URL; ?>?section=login";</script>
<?php	
}	
else
{	
	load_content($section);
}
?>