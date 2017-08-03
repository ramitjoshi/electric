<?php
require 'setup.php';

set_time_limit(3000); 
 
 
/* connect to gmail with your credentials */

/* connect to gmail */
$hostname = '{mail.algonquinelectrical.pro:143/imap/novalidate-cert}INBOX';
$username = 'invoices@algonquinelectrical.pro'; 
$password = 'admin123#';
 
 
/* try to connect */
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
 
 
/* get all new emails. If set to 'ALL' instead 
 * of 'NEW' retrieves all the emails, but can be 
 * resource intensive, so the following variable, 
 * $max_emails, puts the limit on the number of emails downloaded.
 * 
 */
$emails = imap_search($inbox,'ALL');
 
/* useful only if the above search is set to 'ALL' */
$max_emails = 200;

// echo "<pre>";
// print_r($emails);
// echo "</pre>";
 
 
/* if any emails found, iterate through each email */
if($emails) {
 
    $count = 1;
 
    /* put the newest emails on top */
    rsort($emails);
 
    /* for every email... */
	
	$sql="select email from vendor";
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res))
	{
		$arr_ven_email[]=$row['email'];
	}	
	
	
	/*   echo "<pre>";
	print_r($arr_ven_email);
	echo "</pre>";   */
	
	
	
    foreach($emails as $email_number) 
    {
		
		
		
		
		$email_number;
		$username;
        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox,$email_number,0);
		
		
		
		
        /* get mail message, not actually used here. 
           Refer to http://php.net/manual/en/function.imap-fetchbody.php
           for details on the third parameter.
         */
		/*   echo "<pre>";
		 print_r($overview);
		 echo "</pre>";  */ 
		
		$string=$overview[0]->from;
		$string=strstr($overview[0]->from,'@');
		$string=str_replace('@','',$string); 
		
		if($string=="algonquinelectrical.pro")
		{
			$msg_idd=$overview[0]->message_id;
			$asd=str_replace('<','',$msg_idd);
			$msg_id=strstr(substr($asd,0,-1),'@',true);
		}
		else if($string=="IDEALSUPPLY.COM")
		{
			$msg_idd=$overview[0]->message_id;
			$asd=str_replace('<','',$msg_idd);
			 $msg_id=strstr(substr($asd,0,-1),'@',true);
			
		}
		else if($string=="rexel.ca") 
		{
			$msg_idd=$overview[0]->message_id;
			$asd=str_replace('<','',$msg_idd);
			$msg_id=strstr(substr($asd,0,-1),'@',true);
			
		}
		else
		{
			$msg_idd=$overview[0]->message_id;
			$asd=str_replace('<','',$msg_idd);
			$msg_id=strstr(substr($asd,0,-1),'@',true);
		}	
		
		
		
		if($string=="algonquinelectrical.pro")
		{
			 $from=$overview[0]->from;
		}
		else if($string=="IDEALSUPPLY.COM")
		{
			 $from=$overview[0]->from;
		}
		else
		{
			$ovber_array = explode("<",$overview[0]->from);
			$from=substr($ovber_array[1], 0, -1);
		}	
		
		if($from=="Rexel Invoicing")
		{
			$from="Invoicing@rexel.ca";
		}	 
			
		
		
		$cr_date=date('Y-m-d H:i:s', strtotime($overview[0]->date));
		$subject=mysql_real_escape_string($overview[0]->subject);  
		 
		
		 
        $message = imap_fetchbody($inbox,$email_number,2);
		
		
		
		
        /* get mail structure */
        $structure = imap_fetchstructure($inbox, $email_number);
		
		
		
        $attachments = array();
 
        /* if any attachments found... */
        if(isset($structure->parts) && count($structure->parts)) 
        {
            for($i = 0; $i < count($structure->parts); $i++) 
            {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );
 
                if($structure->parts[$i]->ifdparameters) 
                {
                    foreach($structure->parts[$i]->dparameters as $object) 
                    {
                        if(strtolower($object->attribute) == 'filename') 
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }
 
                if($structure->parts[$i]->ifparameters) 
                {
                    foreach($structure->parts[$i]->parameters as $object) 
                    {
                        if(strtolower($object->attribute) == 'name') 
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }
 
                if($attachments[$i]['is_attachment']) 
                {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
 
                    /* 3 = BASE64 encoding */
                    if($structure->parts[$i]->encoding == 3) 
                    { 
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 4 = QUOTED-PRINTABLE encoding */
                    elseif($structure->parts[$i]->encoding == 4) 
                    { 
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }
		
		
		
        /* iterate through each attachment and save it */
        foreach($attachments as $attachment)
        {
			
			//print_r($attachments);
			
            if($attachment['is_attachment'] == 1)
            {	
				
				
				if($from=="Invoicing@rexel.ca")
				{
					$msg_id=strstr($attachment['filename'], '.pdf', true);
					
				}
				 
                $filename = $msg_id.'-'.$attachment['filename']; 
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				if($ext=='pdf')
				{		
					if (in_array($from, $arr_ven_email))  
					{
						$vendor_id=get_vendor_by_email($from,'id'); 
						$sql_check="select msg_id from invoices where msg_id='".$msg_id."'";
						
						$res_check=mysql_query($sql_check); 
						$count=mysql_num_rows($res_check);  
						if($count==0)
						{
							 $insert="insert into invoices (msg_id,subject,user_from,vendor_id,cr_date,attachment,cust_job,status,in_type) values ('".$msg_id."','".$subject."','".$from."','".$vendor_id."','".$cr_date."','".$filename."','0','1','0')";
							 $res=mysql_query($insert);     
							
						}  
						else 
						{
							$update="update invoices subject='".$subject."',user_from='".$from."',cr_date='".$cr_date."',attachment='".$filename."' where msg_id='".$msg_id."'"; 
							//$res=mysql_query($update);    
						}
					}
					
					$fp = fopen("attachment/" . $msg_id.'-'.$attachment['filename'], "w+");
					fwrite($fp, $attachment['attachment']); 
					fclose($fp);    
					
				} 
				
				  
               
            }
			else
			{
				$filename='';
			}	
        }
 
        
		
    }
	
 
} 
 
/* close the connection */
imap_close($inbox);
 
 
?><?php
 error_reporting(0);
ini_set('display_errors', 0);
 
/**
 *	Gmail attachment extractor.
 *
 *	Downloads attachments from Gmail and saves it to a file.
 *	Uses PHP IMAP extension, so make sure it is enabled in your php.ini,
 *	extension=php_imap.dll
 *
 */
 
 
set_time_limit(3000); 
 
 
/* connect to gmail with your credentials */

/* connect to gmail */
$hostname = '{mail.algonquinelectrical.pro:143/imap/novalidate-cert}INBOX';
$username = 'admin@algonquinelectrical.pro';
$password = 'Incorrect0$';
 
 
/* try to connect */
$inbox = imap_open($hostname,$username,$password) or die('Cannot connect to Gmail: ' . imap_last_error());
 
 
/* get all new emails. If set to 'ALL' instead 
 * of 'NEW' retrieves all the emails, but can be 
 * resource intensive, so the following variable, 
 * $max_emails, puts the limit on the number of emails downloaded.
 * 
 */
$emails = imap_search($inbox,'ALL');
 
/* useful only if the above search is set to 'ALL' */
$max_emails = 16;

// echo "<pre>";
// print_r($emails);
// echo "</pre>";
 
 
/* if any emails found, iterate through each email */
if($emails) {
 
    $count = 1;
 
    /* put the newest emails on top */
    rsort($emails);
 
    /* for every email... */
	?>
	<table border="1">
	<tr>
	<th>Mail Number</th>
	<th>To</th>
	<th> From</th>
	<th>Date</th>
	<th>Subject</th>
	<th>Attachment Name</th>
	</tr>
	<?php
    foreach($emails as $email_number) 
    {
		echo "<tr>";
		echo "<td>".$email_number."</td>";
		echo "<td>".$username."</td>";
        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox,$email_number,0);
 
        /* get mail message, not actually used here. 
           Refer to http://php.net/manual/en/function.imap-fetchbody.php
           for details on the third parameter.
         */
		 // echo "<pre>";
		 // print_r($overview);
		 // echo "</pre>";
		 
		  echo $overview[0]->from; 
		  echo "<br>";
		 $ovber_array = explode("<",$overview[0]->from);
		echo "<td>".substr($ovber_array[1], 0, -1)."</td>";
		echo "<td>".$overview[0]->date."</td>";
		echo "<td>".$overview[0]->subject."</td>";
		 
		// $output.= '<div class="toggler '.($overview[0]->seen ? 'read' : 'unread').'">';
		// $output.= '<span class="subject">'.$overview[0]->subject.'</span> ';
		// $outpu1 = '<span class="from">'.$overview[0]->from.'</span>';
		// $output.= '<span class="date">on '.$overview[0]->date.'</span>';
		// $output.= '</div>';
		// echo $output;
		// $html = $outpu1;
// $doc = new DOMDocument();
// $doc->loadHTML($html);
// $span = $doc->getElementsByTagName('span')->item(3);
// echo $doc->saveHTML($span);
		 
        $message = imap_fetchbody($inbox,$email_number,2);
		
		
		
		
        /* get mail structure */
        $structure = imap_fetchstructure($inbox, $email_number);
		
		
		
        $attachments = array();
 
        /* if any attachments found... */
        if(isset($structure->parts) && count($structure->parts)) 
        {
            for($i = 0; $i < count($structure->parts); $i++) 
            {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );
 
                if($structure->parts[$i]->ifdparameters) 
                {
                    foreach($structure->parts[$i]->dparameters as $object) 
                    {
                        if(strtolower($object->attribute) == 'filename') 
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }
 
                if($structure->parts[$i]->ifparameters) 
                {
                    foreach($structure->parts[$i]->parameters as $object) 
                    {
                        if(strtolower($object->attribute) == 'name') 
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }
 
                if($attachments[$i]['is_attachment']) 
                {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
 
                    /* 3 = BASE64 encoding */
                    if($structure->parts[$i]->encoding == 3) 
                    { 
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 4 = QUOTED-PRINTABLE encoding */
                    elseif($structure->parts[$i]->encoding == 4) 
                    { 
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }
 
        /* iterate through each attachment and save it */
        foreach($attachments as $attachment)
        {
            if($attachment['is_attachment'] == 1)
            {
                $filename = $attachment['filename'];
				
		echo "<td>". $filename."</td>";
				// echo $email_number . "-" . $filename;
				// echo "<br>";
                if(empty($filename)) $filename = $attachment['filename'];
 
                if(empty($filename)) $filename = time() . ".dat";
 
                /* prefix the email number to the filename in case two emails
                 * have the attachment with the same file name.
                 */
				 
				 
				 
                $fp = fopen("./" . $email_number . "-" . $filename, "w+");
                fwrite($fp, $attachment['attachment']);
                fclose($fp);
            }
 
        }
 
        if($count++ >= $max_emails) break;
		echo "</tr>";
		
    }
	echo "</table>";
 
} 
 
/* close the connection */





imap_close($inbox);
 
?>