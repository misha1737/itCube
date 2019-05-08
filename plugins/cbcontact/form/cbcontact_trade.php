<?php
						//Name
                        $cbcontactform .= '<div class="cbleft">';
                             $cbcontactform .= i18n_r('cbcontact/Nb').':';
                        $cbcontactform .= '</div>';
                        $cbcontactform .= '<input class="cbright" type="text" name="contact['.i18n_r('cbcontact/Nb').']" value="'.$fields[i18n_r('cbcontact/Nb')].'" />';
                        $cbcontactform .= '<div class="cbclearleft"></div>';

						//Email
                        $cbcontactform .= '<div class="cbleft">(*)';
                             $cbcontactform .= i18n_r('cbcontact/Em').':';
                        $cbcontactform .= '</div>';
                        $cbcontactform .= '<input class="cbright" type="text" name="contact['.i18n_r('cbcontact/Em').']"  value="'.$fields[i18n_r('cbcontact/Em')].'" />';
                        $cbcontactform .= '<div class="cbclearleft"></div>';

						//Subject
                        $cbcontactform .= '<div class="cbleft">';
                             $cbcontactform .= i18n_r('cbcontact/Sub').':';
                        $cbcontactform .= '</div>';
                        $cbcontactform .= '<input class="cbright" type="text" name="contact['.i18n_r('cbcontact/Sub').']" value="'.$fields[i18n_r('cbcontact/Sub')].'" />';
                        $cbcontactform .= '<div class="cbclearleft"></div>';

                        //Options radio
                        $cbcontactform .= '<div class="cbpadleft">';
                                 $cbcontactform .= i18n_r('cbcontact/radio_title').'<br />';
                                 $cbcontactform .= '<input class="cbradio" type="radio" name="contact['.i18n_r('cbcontact/radio').']" VALUE="1"> '.i18n_r('cbcontact/radio1').'<br />';
                                 $cbcontactform .= '<input class="cbradio" type="radio" name="contact['.i18n_r('cbcontact/radio').']" VALUE="2"> '.i18n_r('cbcontact/radio2');
                        $cbcontactform .= '</div>'; 

                        //Options Checkbox
                        $cbcontactform .= '<div class="cbpadleft">';
                                 $cbcontactform .= i18n_r('cbcontact/check_title').'<br />';
                                 $cbcontactform .= '<input class="cbcheck" type="checkbox" name="contact['.i18n_r('cbcontact/check1').']" VALUE="Value1"> '.i18n_r('cbcontact/check1').'<br />';
                                 $cbcontactform .= '<input class="cbcheck" type="checkbox" name="contact['.i18n_r('cbcontact/check2').']" VALUE="Value2" checked> '.i18n_r('cbcontact/check2');
                        $cbcontactform .= '</div>'; 

                        $cbcontactform .= '<div class="cbleft">(*)';
                             $cbcontactform .= i18n_r('cbcontact/Ms').':';
                        $cbcontactform .= '</div>';
                        $cbcontactform .= '<textarea  name="contact['.i18n_r('cbcontact/Ms').']" class="cbright">'.$fields[i18n_r('cbcontact/Ms')].'</textarea>';

                        //system captcha
                        if ($cbfcaptcha == 1){
							$cbcontactform .= '<div class="cbcaptcha">';
                            	$cbcontactform .= '<div class="cbcaptcha_label">'.i18n_r('cbcontact/veri').' <span class="cbreload_label"> '.i18n_r('cbcontact/rl').'</span></div>';
                            	$cbcontactform .= '<img id="captcha" class="cbcaptchaimg" src="'.$SITEURL.'plugins/cbcontact/img_cpt.php?url='.GSPLUGINPATH.'cbcontact/" alt=" " />';
                            	$cbcontactform .= '<input class="cbcaptcha_input" type="text" value="" name="contact[pot]" />';
								$cbcontactform .= '<input class="cbreload" type="button" value="Reload" onClick="javascript:rec_cpt(&quot;captcha&quot;,&quot;'.$SITEURL.'plugins/cbcontact/img_cpt.php?url='.$mGSPLUGINPATH.'&quot;)" />';
								$cbcontactform .= '<div class="cbclear"></div>';
								$cbcontactform .= '<div class="cbcaptcha_write"> (*)'.i18n_r('cbcontact/Cpt').'</div>';
                            $cbcontactform .= '</div>';
                            $cbcontactform .= '<div class="cbclear"></div>';
                        } else {
                            $cbcontactform .= '<input type="hidden" name="contact[pot]" value="">';
                        }

                        $cbcontactform .= '<div class="cbpadleft">';                              
                                 $cbcontactform .= '<input class="cbsend" type="submit" value="'.i18n_r('cbcontact/Ev').'" id="contact-submit" name="contact-submit" />';
    			         $cbcontactform .= '<span class="cbreload_label">(*) '.i18n_r('cbcontact/Rf').'</span>';
								//Check notification
								if ($cbfnotification == 1){
									$cbcontactform .= '<input class="cbcheck" type="checkbox" name="contact[q_notifi]">  <span class="cbreload_label">'.i18n_r('cbcontact/cbcheckNotifi').'</span>';
								}
                        $cbcontactform .= '</div>';

?>
