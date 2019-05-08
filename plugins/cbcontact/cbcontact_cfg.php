<?php

/* ***************************************************************************​************************ */ 
/*  cbcontact_cfg.php  : Mains variables that uses the Contact form.                                   */  
/*		$cbfcaptcha, Boolean,                                                                          */
/*			* true = Use Captcha,                                                                      */
/*			* false = Don’t use Captcha,                                                               */ 
/*		$cbfsendphpmail, Boolean,                                                                      */
/*			* true = send mail with PHPMailer class,                                                   */
/*			* false = with the php fn mail()                                                           */ 
/*		$cbfdisplay_jsalert, Boolean; after a send                                                     */
/*			* true = display an alert box with a message that will be a success or error message.      */
/*			* false = display a message (success or error) before of contact form.                     */
/*		$cbfnotification, Boolean;                                                                     */
/*			* true = display in contact form a check box if you want to receive a notification email   */
/*			* false = Don't display check box in contact form                                          */  
/*      $LANG, Just a basic default if don't know anything else .                                      */
/* ***************************************************************************​************************ */  	

$cbfcaptcha = true;
$cbfsendphpmail = false;
$cbfdisplay_jsalert = false;
$cbfnotification = false;
$LANG = 'en_US'; 
	
?>
