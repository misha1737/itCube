<?php
                        //Name   
                        $cbcontactform .= '<input class="cbrightV" type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Nb').'&quot;;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Nb').'&quot;) {this.value = &quot;&quot;;}" value="';
                        if ($fields[i18n_r('cbcontact/Nb')] != "") {
                            $cbcontactform .= $fields[i18n_r('cbcontact/Nb')];
                        } else {
                            $cbcontactform .= i18n_r('cbcontact/Nb');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Nb').']">';
                        $cbcontactform .= '<div class="cbclearleft"></div>';

                        //Email
                        $cbcontactform .= '<div class="cbleft">(*)';
                        $cbcontactform .= '</div>';
                        $cbcontactform .= '<input class="cbright" type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Em').'&quot;;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Em').'&quot;) {this.value = &quot;&quot;;}" value="';
                        if ($fields[i18n_r('cbcontact/Em')] != "") {
                            $cbcontactform .= $fields[i18n_r('cbcontact/Em')];
                        } else {
                            $cbcontactform .= i18n_r('cbcontact/Em');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Em').']">';
                        $cbcontactform .= '<div class="cbclearleft"></div>';

                        //Subject
                        $cbcontactform .= '<input class="cbrightV" type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Sub').'&quot;;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Sub').'&quot;) {this.value = &quot;&quot;;}" value="';
                        if ($fields[i18n_r('cbcontact/Sub')] != "") {
                            $cbcontactform .= $fields[i18n_r('cbcontact/Sub')];
                        } else {
                            $cbcontactform .= i18n_r('cbcontact/Sub');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Sub').']">';
                        $cbcontactform .= '<div class="cbclearleft"></div>';

                        //Message   
                        $cbcontactform .= '<div class="cbleft">(*)';
                        $cbcontactform .= '</div>';
                        $cbcontactform .= '<textarea class="cbright" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Ms_ms').'&quot;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Ms_ms').'&quot;) {this.value = &quot;&quot;}" name="contact['.i18n_r('cbcontact/Ms').']">';
                        if ($fields[i18n_r('cbcontact/Ms')] != "") {
                            $cbcontactform .= $fields[i18n_r('cbcontact/Ms')];
                        } else {
                            $cbcontactform .= i18n_r('cbcontact/Ms_ms');
                        }
                        $cbcontactform .= '</textarea>';

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
