<?php
/*
Name: Simple Cumbe contact form
Description: It is a simple contact form
Version: 6.1
Author: Cumbe (Miguel Embuena Lance)
Author URI: http://www.cumbe.es/contact/
*/

// Relative
$relative = '../';
$path = $relative. 'data/other/';

# get correct id for plugin
$thisfile=basename(__FILE__, ".php");

# register plugin
register_plugin(
	$thisfile,
	'Cumbe_contact',
	'6.1',
	'Cumbe',
	'http://www.cumbe.es/contact/',
	'Description:  Getsimple contactform.',
	'pages', //page type
	'list_email_log'
);

//add css to head
add_action('theme-header','cbcontact_css');

//check $_SESSION
add_action('index-pretemplate', 'cbcontact_session');

//set internationalization
global $LANG;
if (basename($_SERVER['PHP_SELF']) != 'index.php') { // backend only
	i18n_merge('cbcontact', $LANG);
	i18n_merge('cbcontact', 'en_US');
	//add to sidebar of admin>pages
	add_action('pages-sidebar','createSideMenu',array('cbcontact_form', $i18n['cbcontact/CONTACT']));
}
//filter $content
add_filter('content','cbcontact_content');

function cbcontact_css(){
   global $SITEURL;
   echo '<link href="'.$SITEURL.'plugins/cbcontact/form/cbcontact.css" rel="stylesheet" type="text/css" />';
}

function cbcontact_session(){
   if( !isset($_SESSION)){
         session_start();
   }
}

/* ****************************************************** */
/* Function  list_email_log()                             */ 
/* This is called from the backend/admin to display the   */
/* contact form page showing the list of emails received. */
/* ****************************************************** */ 
function list_email_log(){
	global $i18n; 

	$log_name = 'cbcontactform.log';
	$log_path = GSDATAOTHERPATH.'logs/';
	$log_file = $log_path . $log_name;

?>
	<script type="text/javascript">
        <!--
	function confirmar(formObj,count,msge,bck) {
	    if(!confirm(msge)) { 
                   return false; 
            } else {
                   if (bck == 'log'){
                       if (count =='n'){
                           window.location="load.php?id=cbcontact_form&action=delete";
                       } else {   
                           window.location="load.php?id=cbcontact_form&n_del=" + count + "";
                       }
                   } 
                   return false;
            }    
        }

        -->
	</script> 
<?php

	if(file_exists($log_file)) {
		$log_data = getXML($log_file);
		if (@$_GET['action'] == 'delete' && strlen($log_name)>0) {
			unlink($log_file);
			exec_action('logfile_delete');
?>
					<label>Log <?php echo $log_name;?> <?php echo $i18n['MSG_HAS_BEEN_CLR']; ?>			
				</div>
			</div>
			<div id="sidebar" >
				<?php include('template/sidebar-pages.php'); ?>
			</div>	
			<div class="clear"></div>
			</div>
			<?php get_template('footer'); ?>
<?php
			exit;
		}

		//delete one register:entry
		if (@$_GET['n_del'] != ''){
			$domDocument = new DomDocument();
			$domDocument->preserveWhiteSpace = FALSE; 
			$domDocument->load($log_file);
			$domNodeList = $domDocument->documentElement;
			$domNodeList = $domDocument->getElementsByTagname('entry');
			$ndel = @$_GET['n_del'];
			$ndL = $domNodeList ->item($ndel)->parentNode;
			$ndL -> removeChild($domNodeList ->item($ndel));

			//save again modified document
			$domDocument->save($log_file);
		}     

		//load data of xml
		$log_data = getXML($log_file);
		//END delete one register

?>

		<label><?php echo $i18n['VIEWING'];?>&nbsp;<?php echo $i18n['LOG_FILE'];?>: &lsquo;<em><?php echo @$log_name; ?></em>&rsquo;</label>
		<div class="edit-nav" >
<?php
			echo '<a href="load.php?id=cbcontact_form&action=delete" accesskey="c" title="'.$i18n['CLEAR_ALL_DATA'].' '.$log_name.'" onClick="return confirmar(this,&quot;n&quot;,&quot;'.$i18n['CLEAR_ALL_DATA'].' '.$log_file.'. '.$i18n['cbcontact/delsure'].'&quot;,&quot;log&quot;)" />'.$i18n['CLEAR_THIS_LOG'].'</a>';
			echo '<div class="clear"></div>';
		echo '</div>';
		echo '<ol class="more" >';
			$count = 0;

			foreach ($log_data as $log) {
				echo '<li><p style="font-size:11px;line-height:15px;" ><b style="line-height:20px;" >'.$i18n['LOG_FILE_ENTRY'].':'.$count.'</b><a style="padding-left: 50px;" title="'.$i18n['cbcontact/ndel'].'" href="load.php?id=cbcontact_form" onClick="return confirmar(this,&quot;'.$count.'&quot;,&quot;'.$i18n['cbcontact/ndelc'].$count.'. '.$i18n['cbcontact/delsure'].'&quot;,&quot;log&quot;)"><b>X</b></a><br />';
;
				foreach($log->children() as $child) {
					$name = $child->getName();
					echo '<b>'. stripslashes(ucwords($name)) .'</b>: ';	  
					$d = $log->$name;
					$n = strtolower($child->getName());
					$ip_regex = '/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/';
					$url_regex = @"((https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*)";
					  
					  
					//check if its an url address
					if (do_reg($d, $url_regex)) {
						$d = '<a href="'. $d .'" target="_blank" >'.$d.'</a>';
					}
					  
					//check if its an ip address
					if (do_reg($d, $ip_regex)) {
						if ($d == $_SERVER['REMOTE_ADDR']) {
							$d = $i18n['THIS_COMPUTER'].' (<a href="http://www.geobytes.com/IpLocator.htm?GetLocation&IpAddress='. $d.'" target="_blank" >'.$d.'</a>)';
						} else {
							$d = '<a href="http://www.geobytes.com/IpLocator.htm?GetLocation&IpAddress='. $d.'" target="_blank" >'.$d.'</a>';
						}
					}
					  
					//check if there is an email address
					if (check_email_address($d)) {
						$d = '<a href="mailto:'.$d.'">'.$d.'</a>';
					}
					  
					//check if its a date
					if ($n === 'date') {
						$d = lngDate($d);
					}
					  	
					echo stripslashes(html_entity_decode($d));

					echo ' <br />';
				}
				echo "</p></li>";
				$count++;
			}				
				
?>
		</ol>
		
<?php
	} //END if file_exists
	else
	{   //If file does not exist
?>
		<label><?php echo $i18n['MISSING_FILE']; ?>: &lsquo;<em><?php echo @$log_name; ?></em>&rsquo;</label>
<?php
	}

}  // END list_email_log

////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////

function cbcontact_content($content){
  /////////////////////////////////////////////////////////////////////////////
  //         filter content of page searching $cbcontact 
  // $array_coinc[0] = user / email
  // $array_coinc[1] = echo html
  // $array_coinc[2] = more users / emails
  /////////////////////////////////////////////////////////////////////////////

	if ( preg_match("/\(%\s*(cbcontact)(\s+(?:%[^%\)]|[^%])+)?\s*%\)/", $content, $coinc)){
		$array_coinc = explode(',', $coinc[2]);
		$cuantos = count($array_coinc);

		array_filter($array_coinc, 'trim_value');
		$array_coinc = str_replace("'","",$array_coinc); 
		$array_coinc = str_replace(" ","",$array_coinc); 

		$usus = '';   
		for ($q = 2; $q < $cuantos; $q++ ){
			$usus[$q] = $array_coinc[$q];
		}  
		$usus = str_replace("'","",$usus); 
		$usus = str_replace(" ","",$usus); 
		$content_cbcontact = cbcontact_page (trim($array_coinc[0]), false, $usus);
		$content = str_replace($coinc[0], $content_cbcontact, $content);
	} 

	return $content;
}   


/* ***************************************************************************​************************ */ 
/*  cbcontact_page()  : Main function to output the Contact form, and  send the form data as an email  */  
/*      ***  Thanks aldebaran for the explanation   ****                                               */
/*      1st para : $usu, String, user name of GetSimple admin, or the user's email addr.               */ 
/*      2th para : $echocontact, Boolean, true = echo the HTML code for the contact form,              */
/*                                       false = return the HTML code                                  */ 
/*      3th para : $usus .. A String array of users / users' emails                                    */ 
/* ***************************************************************************​************************ */  
function cbcontact_page($usu, $echocontact=true, $usus='') {

	// Read setting file with variables of contact form
	include  (GSPLUGINPATH.'cbcontact/cbcontact_cfg.php');

	global $EMAIL;
	global $SITEURL;
	global $SITENAME;
	global $LANG;
	global $PRETTYURLS;
	global $i18n;
	global $language;
	$log_name = 'cbcontactform.log';
	$log_path = GSDATAOTHERPATH.'logs/';
	$log_file = $log_path . $log_name;
	$fich = return_page_slug();
	$idpret = find_url($fich,'');
	if ($PRETTYURLS !='') {
		$idpret = $idpret.'?';
	}

	if (file_exists('gsconfig.php')) {
		include_once('gsconfig.php');
	}

	// Debugging
	if (defined('GSDEBUG')){
		error_reporting(E_ALL | E_STRICT);
		ini_set('display_errors', 1);
	} else {
		error_reporting(0);
		@ini_set('display_errors', 0);
	}

	$err = '';
	// Get the Email address and language defined in the GS admin for the admin user 
	if (!check_email_address($usu)){ 
		if (file_exists(GSDATAPATH.'users/'.$usu.'.xml')) {
			$data = getXML(GSDATAPATH.'users/'.$usu.'.xml');  
			$EMAIL= $data->EMAIL;
			$LANG = $data->LANG;
		}    
	} 
	else {
		//$usu is already/directly a valid email => so assign directly 
		$EMAIL = $usu;
	}
	//i18n compatible
	if (isset($_GET['setlang'])){
		$LANG = $_GET['setlang']. '_'.strtoupper($_GET['setlang']);
	}
	if (isset($language)){
		switch ($language) {
			case 'en':
        		$LANG = $language. '_US';
        		break;
			default:
		        $LANG = $language. '_'.strtoupper($language);
        		break;
		}
	}
	//i18n lang  
	$LANG = (!file_exists(GSPLUGINPATH.'cbcontact/lang/'.$LANG.'.php')) ? 'en_US' : $LANG;
	i18n_merge('cbcontact', $LANG); 	

	//check other users or emails
	if ($usus != ''){
		if (is_array($usus)){
			foreach ($usus as $key=> $value){   
				if (!check_email_address(trim($value))){ 
					if (file_exists(GSDATAPATH.'users/'.trim($value).'.xml')) {
						$data = getXML(GSDATAPATH.'users/'.trim($value).'.xml');  
						$qusus[$key] = $data->EMAIL;
					}  else {
						unset ($usus[$key]);
					}   
				} else {
					$qusus[$key] = trim($value);
				}
			}
		} else {
			$qusus[4] = $usus;
		} 
	} 

////////////////////////////////////////////////////////////////////////////////////////// 
// Define an assoc array fields[] to temporarily hold the user entered textfield values.
// Also used to to re-output textfield for the next displayed HTML contact form.
//////////////////////////////////////////////////////////////////////////////////////////
	global $fields;
	$fields = array(
		i18n_r('cbcontact/Nb') =>  '',
		i18n_r('cbcontact/Em') =>  i18n_r('cbcontact/em_text'),
		i18n_r('cbcontact/Sub') =>  '',
		i18n_r('cbcontact/Ms') =>  '',
	);

	// If we come here becuse the contact form was already displayed and the user hit the 
	// submit button, then insert the validate fields/send mail script 
	// Else This is the first time display of the contact form, so just continue ...  
	$cbcontactform = '';
	if (isset($_POST['contact-submit'])) {
		include (GSPLUGINPATH."cbcontact/checksubmit.php");
	}

?>
	<script type="text/javascript">
	<!--
		function rec_cpt(id,ourl){
			var aleat = Math.random();
			var mf = document.getElementById(id);
			mf.src = ourl + "/cbcontact/&" + aleat;
		}
	-->
	</script>

<?php
	//control uri
	$request_uri = getenv ("REQUEST_URI");       // Requested URI

	//Show in page   
	$mGSPLUGINPATH = str_replace("\\", "/", GSPLUGINPATH);
	$mGSPLUGINPATH = substr($mGSPLUGINPATH, 0, -1);

   // -------------------------------
   // Set up the actual Form HTML 
   // -------------------------------
	$cbcontactform .= '<form id="cbform" class="" action="'.$idpret.'" method="post">';
        // Call script to include the main body of he form  
		include  (GSPLUGINPATH.'cbcontact/form/cbcontact.php');

        // add in hidden fields
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[q_uri]" value="'.$request_uri.'">';
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[leaveblank]" value="">';
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[leaveso]" value="leaveso">';
	$cbcontactform .= "\r\n".'</form>'."\r\n\t";


   // ---------------------------------------------------------------------------------------------
   // Echo or return the contact form html 
   //    
   // $echocontact is passed in as FALSE when cbcontact_page() is called from cbcontact_content() 
   // so that we return HTML content of $cbcontactform to cbcontact_content() where it is then
   // added to $content .. so we replace the page editer entered (% %) syntax with the form code.   
   // 
   // $echocontact is passed in as TRUE when we just want to echo the HTML content of $cbcontactform,
   // eg when we directly call cbcontact_page() from a template file.   
   //--------------------------------------------------------------------------------------------------

	if ($echocontact == 1){
		echo $cbcontactform;
	} else {
		return $cbcontactform;
	} 

}

function trim_value(&$value){
	$value = trim($value); 
}
 
?>
