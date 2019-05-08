<?php
                        //Name   
                        $cbcontactform .= '<input class="cbrightV" type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Nb_nb').'&quot;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Nb_nb').'&quot;) {this.value = &quot;&quot;}" value="';
                        if ($fields[i18n_r('cbcontact/Nb')] != "") {
                            $cbcontactform .= $fields[i18n_r('cbcontact/Nb')];
                        } else {
                            $cbcontactform .= i18n_r('cbcontact/Nb_nb');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Nb').']">';

                        //Email
                        $cbcontactform .= '<input class="cbrightV" type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;(*)'.i18n_r('cbcontact/Em_em').'&quot;}" onfocus="if(this.value == &quot;(*)'.i18n_r('cbcontact/Em_em').'&quot;) {this.value = &quot;&quot;}" value="';
                        if ($fields[i18n_r('cbcontact/Em')] != "") {
                            $cbcontactform .= $fields[i18n_r('cbcontact/Em')];
                        } else {
                            $cbcontactform .= '(*)'.i18n_r('cbcontact/Em_em');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Em').']">';

                        //Subject
                        $cbcontactform .= '<input class="cbrightV" type="text" onblur="if (this.value == &quot;&quot;) {this.value = &quot;'.i18n_r('cbcontact/Sb_sb').'&quot;}" onfocus="if(this.value == &quot;'.i18n_r('cbcontact/Sb_sb').'&quot;) {this.value = &quot;&quot;}" value="';
                        if ($fields[i18n_r('cbcontact/Sub')] != "") {
                            $cbcontactform .= $fields[i18n_r('cbcontact/Sub')];
                        } else {
                            $cbcontactform .= i18n_r('cbcontact/Sb_sb');
                        }
                        $cbcontactform .= '" name="contact['.i18n_r('cbcontact/Sub').']">';

                        //Message   
                        $cbcontactform .= '<textarea class="cbrightV" onblur="if (this.value == &quot;&quot;) {this.value = &quot;(*)'.i18n_r('cbcontact/Ms_ms').'&quot;}" onfocus="if(this.value == &quot;(*)'.i18n_r('cbcontact/Ms_ms').'&quot;) {this.value = &quot;&quot;}" name="contact['.i18n_r('cbcontact/Ms').']">';
                        if ($fields[i18n_r('cbcontact/Ms')] != "") {
                            $cbcontactform .= $fields[i18n_r('cbcontact/Ms')];
                        } else {
                            $cbcontactform .= '(*)'.i18n_r('cbcontact/Ms_ms');
                        }
                        $cbcontactform .= '</textarea>';

						//system captcha   1 = true
						if ($cbfcaptcha == 1){
							$cbcontactform .= '<div class="cbcaptcha">';
								$cbcontactform .= '<div class="cbcaptcha_label">'.i18n_r('cbcontact/veri').' <span class="cbreload_label"> '.i18n_r('cbcontact/rl').'</span></div>';
								$cbcontactform .= '<div>';
									$cbcontactform .= '<img id="captcha" class="cbcaptchaimg" src="'.$SITEURL.'plugins/cbcontact/img_cpt.php?url='.GSPLUGINPATH.'cbcontact/" alt=" " />';
									$cbcontactform .= '<input class="cbreload" type="button" value="'.i18n_r('cbcontact/rl_but').'" onClick="javascript:rec_cpt(&quot;captcha&quot;,&quot;'.$SITEURL.'plugins/cbcontact/img_cpt.php?url='.$mGSPLUGINPATH.'&quot;)" />';
								$cbcontactform .= '</div>';
							$cbcontactform .= '</div>';
						} else {
							$cbcontactform .= '<input type="hidden" name="contact[pot]" value="">';
						}

						//submit button  
						$cbcontactform .= '<div class="cbpadleft">'; 
							if ($cbfcaptcha == 1){
								$cbcontactform .= '<div class="cbcaptcha_write"> (*)'.i18n_r('cbcontact/Cpt').'</div>'; 
								$cbcontactform .= '<input class="cbcaptcha_input" type="text" value="" name="contact[pot]" />';                           
							}
							$cbcontactform .= '<input class="cbsend" type="submit" value="'.i18n_r('cbcontact/Ev').'" id="contact-submit" name="contact-submit" />';
							$cbcontactform .= '<div class="cbreload_label">';
								$cbcontactform .= '(*) '.i18n_r('cbcontact/Rf');
								//Check notification
								if ($cbfnotification == 1){
									$cbcontactform .= '<input class="cbcheck" type="checkbox" name="contact[q_notifi]"> '.i18n_r('cbcontact/cbcheckNotifi');
								}
							$cbcontactform .= '</div>';
						$cbcontactform .= '</div>';

?>
