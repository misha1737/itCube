<?php

/*
Plugin Name: GetSimple Mail Newsletter
Description: Send newsletter to list of mails
Version: 1.0.1
Author: Jose Daniel Rodriguez Castro
Author URI: http://528729.0.es.strato-hosting.eu

v1.0.1
- Now plugin use global editor defined in Get Simple configuration
- Fix blankspaces in new email address
- Function mail_newsletter_form that insert subscribe form in any page
*/



# get correct id for plugin
$thisfile = basename(__FILE__, '.php');

# register plugin
register_plugin(
  $thisfile,
  'Mail Newsletter',
  '1.0.1',
  'Daniel Rodriguez',
  'http://528729.0.es.strato-hosting.eu',
  'Send newsletter to list of mails',
  'plugins',
  'mail_newsletter'
);


# hooks
add_action('plugins-sidebar', 'createSideMenu', array($thisfile, 'Mail Newsletter'));

# definitions
define (CONFIGFILE, GSDATAOTHERPATH . 'mail_newsletter.xml');


############################### ADMIN FUNCTIONS ################################


/*******************************************************
 * @function mail_newsletter
 * @action main function, creates, edits or deletes emails address*/
function mail_newsletter() 
{
if (isset($_GET['delete'])) 
    {
     $mail = $_GET['delete'];
     deleteMail($mail);
    }
 else if (isset($_GET['add'])) 
    {
     addMail();
    } 
 else if (isset($_GET['send'])) 
    sendNewsletter();
 else
    mailOverview();
}


/*******************************************************
 * @function mailOverview
 * @action show main screen of mail newsletter*/
function mailOverview() 
{   
	$data = loadConfig();

	mailList($data['mails']);

	echo '<form action="load.php?id=mail_newsletter&send=true" method="post" accept-charset="utf-8">';
	echo '<p>';
	echo '<b>Subject:</b><br />';
	echo '<input name="mail-subject" size="90" type="text" value="' . $data['subject'] . '">';
	echo '</p>';

	echo '<p>';
	echo '<b>Body:</b><br />';
	echo '<textarea name="mail-body">' . $data['body'] . '</textarea>';
	echo '</p>';
	echo '<p>'; 
	echo '<input name="submit" type="submit" class="submit" value="Send" />';
	echo '</p>';
	echo '</form>';
	
    global $HTMLEDITOR;
    if (isset($HTMLEDITOR) && $HTMLEDITOR != '') 
	   {
        $TOOLBAR = "['Bold', 'Italic', 'Underline', 'NumberedList', 'BulletedList', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', 'Link', 'Unlink', 'Image', 'RemoveFormat', 'Source']";
        //$EDLANG = defined('GSEDITORLANG') ? GSEDITORLANG : 'en';
        if (defined('GSEDITORLANG')) { $EDLANG = GSEDITORLANG; } else {  $EDLANG = i18n_r('CKEDITOR_LANG'); }    
        if (defined('GSEDITOROPTIONS') && trim(GSEDITOROPTIONS)!="") { $EDOPTIONS = ", ".GSEDITOROPTIONS; } else {  $EDOPTIONS = ''; } 
      ?>
      
	  <script type="text/javascript" src="template/js/ckeditor/ckeditor.js"></script>
      <script type="text/javascript">
        var editor = CKEDITOR.replace( 'mail-body', {
          skin : 'getsimple',
          forcePasteAsPlainText : true,
          language : '<?php echo $EDLANG; ?>',
          defaultLanguage : '<?php echo $EDLANG; ?>',
          entities : true,
          //entities : false,      
          uiColor : '#FFFFFF',
          height: '300',
          toolbar :
          [
          <?php echo $TOOLBAR; ?>
          ]
          <?php echo $EDOPTIONS; ?>,                
          tabSpaces:10,
          filebrowserBrowseUrl : 'filebrowser.php?type=all',
          filebrowserImageBrowseUrl : 'filebrowser.php?type=images',
          filebrowserWindowWidth : '730',
          filebrowserWindowHeight : '500'
        });      
      </script>
      <?php
	  }
}
 
  
/*******************************************************
 * @function mailList
 * @action show a list of emails address and a form to add a new one*/    
function mailList($mails) 
{ 
 echo '<h3>Mail List Newsletter</h3>';

 if (!empty($mails)) 
     {
      echo '<table class="highlight">';
      foreach ($mails as $mail) 
              {
      echo '<tr>';
      echo '<td>';
      echo '<a href="" title="Edit mail:' . $mail . '">';
      echo $mail;
      echo '</a>';
      echo '</td>';
      echo '<td class="delete">';
      echo '<a href="load.php?id=mail_newsletter&delete=' . $mail . '" class="delconfirm" title="Delete mail: ' . $mail . '?">';
      echo 'X';
      echo '</a>';
      echo '</td>';
      echo '</tr>';
              }
      echo '</table>';
     }
     
  echo '<p><b>' . count($mails) . '</b> mails';
  mail_newsletter_form();  
  echo '</p>';
} 

/*******************************************************
 * @function deleteMail
 * @action delete a email address from list*/     
function deleteMail($mail)
{
 $new = array();   

 $data = loadConfig();
  
 foreach ($data['mails'] as $current)
         {
          if ($current != $mail)
             $new[] = $current;
         }
   
 saveConfig($new, $data['subject'], $data['body']); 
   
 echo '<div class="updated">The mail ' . $mail . ' has been deleted</div>';

 mailOverview();
}
     
    
/*******************************************************
 * @function addMail
 * @action add a email address to list*/      
function addMail()
{
 $new = $_POST['new-mail'];  
 $mails = array();
 $subject = "";
 $body = ""; 

 $data = loadConfig();
 
 $data['mails'][] = trim($new);
   
 saveConfig($data['mails'], $data['subject'], $data['body']); 
   
 echo '<div class="updated">The mail ' . $new . ' has been added</div>';

 mailOverview();
}
 
  
/*******************************************************
 * @function sendNewsletter
 * @action sends a email to each email address*/       
function sendNewsletter() 
{
 $subject = $_POST['mail-subject']; 
 $random_hash = md5(date('r', time())); 
 $body  = "--PHP-alt-" . $random_hash;
 //$body .= "\nContent-Type: text/html; charset=\"iso-8859-1\"";
 $body .= "\nContent-Type: text/html; charset=utf-8";
 $body .= "\nContent-Transfer-Encoding: 8bit\n\n";
 $body .= $_POST['mail-body'];  
 $body .= "\n--PHP-alt-" . $random_hash . "--\n";
 $header  = "From: 1+1 Diseño <info@xn--1mas1diseo-19a.es>\r\nReply-To: 1+1 Diseño <info@xn--1mas1diseo-19a.es>";
 $header .= "\r\nContent-Type: multipart/alternative; boundary=\"PHP-alt-".$random_hash."\"";  
 
 $data = loadConfig();

  foreach ($data['mails'] as $mail)
         {
          if (mail($mail, '=?UTF-8?B?'.base64_encode($subject).'?=', $body, $header))
             echo("<p>Mensaje enviado!</p>");
          else 
             echo("<div class='updated'>No se ha podido mandar el mensaje</div>");
         }

 saveConfig($data['mails'], $subject, $_POST['mail-body']);  
   
 mailOverview();    
} 

/*******************************************************
 * @function loadConfig
 * @action load data from config file */  
function loadConfig()
{
 $data = array();

 if (file_exists(CONFIGFILE)) 
    {
     $xml = @getXML(CONFIGFILE);

     if (!empty($xml))
        {
         if ($xml->mails != "")
		    $data['mails'] = split("[,]+", $xml->mails);
         $data['subject'] =  $xml->subject;
         $data['body'] =  @stripslashes($xml->body);
        }
     else
        $data['error'] = "Error reading " .CONFIGFILE;
    }
 
 return $data; 
}


/*******************************************************
 * @function saveConfig
 * @action save data to config file */      
function saveConfig($mails, $subject, $body)
{
  $xml = @new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><item></item>');
  $xml->addChild('mails', join("," ,$mails));
  $xml->addChild('subject', htmlspecialchars($subject));
  $xml->addChild('body', safe_slash_html($body));    
  XMLsave($xml, CONFIGFILE);

  if (!is_writable(CONFIGFILE))
     return '<div class="error">Unable to write config to file</div>';
  else
     return '<div class="updated">Config has been succesfully saved</div>';
} 

/*******************************************************
 * @function mail_newsletter_form
 * @action insert form html code to subscribe a new email */  
function mail_newsletter_form($text='Add')
{
  echo '<form action="load.php?id=mail_newsletter&add" method="post" accept-charset="utf-8">';
  echo '<input name="new-mail" type="text" value="">';
  echo '<input name="submit" type="submit" class="submit" value="'.$text.'" />';
  echo '</form>';
}



?>