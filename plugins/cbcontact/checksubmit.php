<?php
//-----------------------------------------------------------------//
//--- checksubmit.php: action if form is submit                    //
//-----------------------------------------------------------------//
	if (!isset($_SESSION)) { session_start(); }
	$pot = "";
	if ($cbfcaptcha == 1){
		$imagenCadena = $_SESSION["imagencadena"]; 
		$pot = trim(strtolower($imagenCadena));
	}
	$server_name = getenv ("SERVER_NAME");       // Server Name
	$request_uri = getenv ("REQUEST_URI");       // Requested UR

// check antispam 
if (isset($_POST['contact-submit'])) {
    if ($_POST['contact']['leaveso'] != 'leaveso' or $_POST['contact']['leaveblank']!=''){
       echo '<html>';
       echo '<head>';
       echo '</head>';
       echo '<body>';
       echo '<div style="padding: 20px; border: 4px double; margin: 20%;">';
       echo i18n_r('cbcontact/spam').'<br />';
       echo '<a href="'.$idpret.'">'.i18n_r('cbcontact/back').'</a>';
       echo '</div>';
       echo '</body>';
       echo '</html>';   
       exit; 
    }  
}

	if (isset($_POST['contact-submit'])) {

			//check captcha
			//$cbfcaptcha = 1 (true); $pot = value of $imagenCadena
			if ($cbfcaptcha == 1) {
				if ( $pot == trim(strtolower($_POST['contact']['pot']))) {					$err = '';
				} else {
					$err = i18n_r('cbcontact/MSG_CAPTCHA_FAILED');
				}
			}

			//check name
			$fname = '';
			if ( $_POST['contact'][i18n_r('cbcontact/Nb')] != '' ) {
				if ($_POST['contact'][i18n_r('cbcontact/Nb')] == i18n_r('cbcontact/Nb')){
					$_POST['contact'][i18n_r('cbcontact/Nb')] = '';
				} else {
					$fname = $_POST['contact'][i18n_r('cbcontact/Nb')];
				}
			}

			//check email
			if ( $_POST['contact'][i18n_r('cbcontact/Em')] != '' ) {
				if ($_POST['contact'][i18n_r('cbcontact/Em')] == '(*)'.i18n_r('cbcontact/Em')) {
					$_POST['contact'][i18n_r('cbcontact/Em')] = ''; //i18n_r('cbcontact/Em');
				}
				if ($_POST['contact'][i18n_r('cbcontact/Em')] != i18n_r('cbcontact/Em')){
					$from =  $_POST['contact'][i18n_r('cbcontact/Em')];
				} else {
					$_POST['contact'][i18n_r('cbcontact/Em')]='';
				}
			}
       
			//check subject
			$subject2 = ''; 
			$subject = i18n_r('cbcontact/CONTACT_FORM_SUB').' '.i18n_r('cbcontact/WHO').' '.$SITENAME;
			if ( $_POST['contact'][i18n_r('cbcontact/Sub')] != '' ) {
				if ($_POST['contact'][i18n_r('cbcontact/Sub')] != i18n_r('cbcontact/Sub')){
					$subject = stripslashes(html_entity_decode($_POST['contact'][i18n_r('cbcontact/Sub')]));
				} else { 
					$_POST['contact'][i18n_r('cbcontact/Sub')] = i18n_r('cbcontact/CONTACT_FORM_SUB').' '.i18n_r('cbcontact/WHO').' '.$SITENAME;
					$subject2 =  i18n_r('cbcontact/Sub');
				}
			}

			//check message
			if ( $_POST['contact'][i18n_r('cbcontact/Ms')] != '' ) {
				if ($_POST['contact'][i18n_r('cbcontact/Ms')] == '(*)'.i18n_r('cbcontact/Ms_ms')) {
					$_POST['contact'][i18n_r('cbcontact/Ms')] = '';
				}
				if ($_POST['contact'][i18n_r('cbcontact/Ms')] == i18n_r('cbcontact/Ms_ms')){
					$_POST['contact'][i18n_r('cbcontact/Ms')] = '';
				}
			} 

			$temp = $_POST['contact'];

			//check notification
			if (array_key_exists('q_notifi',$temp) == false) $temp['q_notifi'] = 'off';

			//release variables
			unset($temp['pot']);
			unset($temp['contact-submit']);
			unset($temp['submit']);
			unset($temp['leaveso']);
			unset($temp['leaveblank']);

			if ($err == '' && trim($_POST['contact'][i18n_r('cbcontact/Em')]) !='' && trim($_POST['contact'][i18n_r('cbcontact/Ms')]) !='') {

				// Build the Body of  the mail from the 4 contact form fields, ´plus ip address etc
				// ---------------------------------------------------------------------------------
				$body = '"'.$subject.'": <a href="'.substr($idpret,0,-1).'">'.substr($idpret,0,-1).'</a>';
				$body .= "<hr><br />";

				// Save log file with data of form.
				// ---------------------------------
				if ( !file_exists($log_file) ) {
					$xml = new SimpleXMLExtended('<channel></channel>');
				} else {
					$xmldata = file_get_contents($log_file);
					$xml = new SimpleXMLExtended($xmldata);
				}

				$thislog = $xml->addChild('entry');
				$thislog->addChild('date', date('r'));
				$cdata = $thislog->addChild('captcha');
				$cdata->addCData($pot);
				$cdata = $thislog->addChild('ip_address');
				$ip = getenv("REMOTE_ADDR"); 
				$cdata->addCData(htmlentities($ip, ENT_QUOTES, 'UTF-8'));
				$body .= "Ip: ". $ip ."<br />"; 
				foreach ( $temp as $key => $value ) {
					if (substr($key, 0, 2) != 'q_') {
						$body .= ucfirst($key) .": ". stripslashes(html_entity_decode($value, ENT_QUOTES, 'UTF-8')) ."<br />";
						$cdata = $thislog->addChild(($key));
						$cdata->addCData(stripslashes(html_entity_decode($value, ENT_QUOTES, 'UTF-8')));
					}
				}
				XMLsave($xml, $log_file);


				$result = 0;
				$err_phpmailer = '';
				$err_notifi = '';
				// *************************************************************** //
				//  Send the mail .. with the PHP mail() fn or the PHPMailer class //
				// *************************************************************** //

				if (!$cbfsendphpmail) 
				{
					// ****************************************** //
					//  Send the mail .. with the PHP mail() fn   //
					// ****************************************** //

					$headers = "From: ".$from."\r\n";
					$headers .= "Return-Path: ".$from."\r\n";
					$headers .= "Content-type: text/html; charset=UTF-8 \r\n";
					$result = mail($EMAIL,$subject,$body,$headers);
					if ($usus != ''){
						foreach ($qusus as $key=>$value){
							$result =  mail($value,$subject,$body,$headers);
							$result1[$key] =  $result;
						}
					}

					//if check of form is 'on' then send a notification
					if ($temp["q_notifi"] == 'on'){
						$body = i18n_r('cbcontact/cbnotifi');
						$body .= '": <a href="'.substr($idpret,0,-1).'">'.substr($idpret,0,-1).'</a>';
						$result_notifi = mail($from,$subject,$body,$headers);
						if ($result_notifi != 1) {
							$err_notifi = i18n_r('cbcontact/cberr_notifi');
						}
					}
				} else
				if ($cbfsendphpmail)
				{
					// ****************************************** //
					//  Send the mail .. with PHPMailer           //
					// ****************************************** //	
					if (is_dir(GSPLUGINPATH.'PHPMailer_v5.1') and file_exists(GSPLUGINPATH.'PHPMailer_v5.1/class.phpmailer.php')){

						require(GSPLUGINPATH.'PHPMailer_v5.1/class.phpmailer.php');   
						$message = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
						$message->CharSet = "utf-8"; 
						$message->SMTPDebug = false;    // enables SMTP debug information (for testing)
                                                        // false = disabled debug
                                                        // 1 = errors and messages
                                                        // 2 = messages only

						$message->IsSMTP();            // telling the class to use SMTP
						$message->SMTPAuth = true;     // enable SMTP authentication

						//Localhost Configuration
						/*
						$message->SMTPSecure = "";                 // sets the prefix to the servier
						$message->Host       = "localhost";      // specify SMTP Server name
						$message->Port       = 25;                   // set the SMTP port
						$message->Username   = "me@email.com";   // user account
						$message->Password   = "mypaswd";         // password 
						$message->From   = "localhost";       */  
						
						//end localhost Configuration

						//GMAIL Configuration
						$message->SMTPSecure = "ssl";                 // sets the prefix to the servier
						$message->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP se
						$message->Port       = 465;                   // set the SMTP port for the GMAIL server
						$message->Username   = "you@gmail.com";   // GMAIL user account: youuser@gmail.com
						$message->Password   = "youpass";         // GMAIL password 
						$message->From   = "you@gmail.com";       // you GMAIL email 
						//end GMAIL Configuration

						//ONO configuration     
						/*       $message->SMTPSecure = "";                  // sets the prefix to the servier
						$message->Host       = "smtp.ono.com";       // sets ONO as the SMTP server
						$message->Port       = 25;                   // set the SMTP port for the ONO server
						$message->Username   = "username";               // ONO username 
						$message->Password   = "pass";            // ONO password
						$message->From   = "user@ono.com";           // you ono email 
						*/       //end ONO Configuration

						//HOTMAIL configuration     
						/*		$message->SMTPSecure = "tls";                   // sets the prefix to the servier
						$message->Host       = "smtp.live.com";         // sets hotmail as the SMTP server
						$message->Port       = 587;                     // set the SMTP port for hotmail server
						$message->Username   = "youruser@hotmail.com";  // hotmail user account
						$message->Password   = "yourpass";               // hotmail password
						$message->From   = "youruser@hotmail.com";  */    // you hotmail email 
						//end HOTMAIL Configuration


						$message->Subject = $subject;
						$message->FromName   = $from;
						$message->MsgHTML("$body");

						// Body for non-HTML mail clients .. so should be in plain text, here not 
						//$message->AltBody = $body_NOhtml;  

						$ex = null;
                  		try {
                     		// Add the TO email address of the GS admin user, and the
                     		// TO email addresses other user/emails passed by $usus      
							$message->AddAddress($EMAIL,"");
							if ($usus != '')  {
								foreach ($qusus as $key=>$value){
									$message->AddAddress($value,""); 
								}
							}
							// Add a ReplyTo email address  
							//$message->addReplyTo($from, $fname); 
                    		
							// Actually send the email  
		                    $result = $message->Send();

						}
						catch (phpmailerException   $ex){ 
							$err_phpmailer = $message->ErrorInfo;
						}
						catch (Exception   $ex){ 
							$err_phpmailer = $message->ErrorInfo;
						}

						$CF = array(" \t","\n","\r","\0", "\x0B");
						$err_phpmailer = str_replace ($CF, '. ', $err_phpmailer);
						$err_phpmailer = str_replace ("<br />", '', $err_phpmailer);
						$err_phpmailer = substr($err_phpmailer, 0, -2);

						//if check of form is 'on' then send a notificaction
						if ($temp["q_notifi"] == 'on'){
							$body = i18n_r('cbcontact/cbnotifi');
							$body .= '": <a href="'.substr($idpret,0,-1).'">'.substr($idpret,0,-1).'</a>';
							$ex = null;
							try {
                   				// Add a notification  email address  
								$message->Subject = $subject;
								$message->FromName   = $fname;
								$message->MsgHTML("$body");
								$message->AddAddress($from,"");
			                    $message->Send();
							}
							catch (phpmailerException   $ex){ 
								$err_notifi = "".i18n_r('cbcontact/cberr_notifi').": ";
								$err_notifi .= $message->ErrorInfo;
							}
							catch (Exception   $ex){ 
								$err_notifi = "".i18n_r('cbcontact/cberr_notifi').": ";
								$err_notifi .= $message->ErrorInfo;
							}
							$err_notifi = str_replace ($CF, '. ', $err_notifi);
							$err_notifi = str_replace ("<br />", '', $err_notifi);
							$err_notifi = substr($err_notifi, 0, -2);
                  		}
					} else {
						echo strtoupper(i18n_r('cbcontact/errphphmail'))."\n";
					}
				}

				//results	
				if ($result == '1') {
					$msgshw = i18n_r('cbcontact/MSG_CONTACTSUC');
					$fields[i18n_r('cbcontact/Nb')] =  '';  //name
					$fields[i18n_r('cbcontact/Em')] =  '';  //email
					$fields[i18n_r('cbcontact/Sub')] = '';  //subject
 					$fields[i18n_r('cbcontact/Ms')] =  '';  //message
				} else {
					$msgshw = i18n_r('cbcontact/MSG_guestERR')."\n";
					$msgshw .= $err_phpmailer."<br />\n";
					$err = $msgshw;
					if ($subject2 != '') {
						$temp[i18n_r('cbcontact/Sub')] = $subject2;
					}
					foreach($temp as $key=>$value){
						$fields[$key] = stripslashes(html_entity_decode($value));
					}
				}

				//if there are some error from notification then warns us with error
				if ($err_notifi != ''){
					$msgshw .= '\n'.$err_notifi.'\n';
				}

			} //end if $err=''
			else 
			{ //if $err != ''

				if ($subject2 != '') {
					$temp[i18n_r('cbcontact/Sub')] = $subject2;
				}
				foreach($temp as $key=>$value){
					$fields[$key] = stripslashes(html_entity_decode($value));
				}

				if (trim($err) !=''){
					$msgshw = $err.'\nCaptcha code: '.$pot.'\nCode wrote: '.$_POST['contact']['pot'];
				} else {
					$msgshw = '*** '.strtoupper(i18n_r('cbcontact/Co')).' ***';
					$err = $msgshw;
				}
			}

	}


////////////////////////////////////////////////////////////////
//
//     html page or alert box of javascript
//
////////////////////////////////////////////////////////////////
if ($cbfdisplay_jsalert == 1) {
?>
	<script type="text/javascript">
<!--
		alert ('<?php echo $msgshw; ?>');
-->
	</script>
<?php
} else {
	$msgshw = str_replace ('\n', '<br />', $msgshw);
	$cbcontactform = '<div class="msgshw">'.$msgshw.'</div>';
}

?>
