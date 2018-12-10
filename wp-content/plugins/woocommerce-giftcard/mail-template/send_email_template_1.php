<?php
/**
 * Created by PhpStorm.
 * User: nguyenhang
 * Date: 12/06/2017
 * Time: 13:07
 */
class SEND_EMAIL_TEMPLATE_1
{
    public function output($message){
        add_action( 'woocommerce_email_header', array( $this, 'email_header' ) );
        add_action( 'woocommerce_email_footer', array( $this, 'email_footer' ) );
        $content = $message;
        $logo = get_option('logo_company');
        $image = get_option('image_template');
        $footer = get_option('email_footer');
        $links = get_permalink(wc_get_page_id ( 'shop' ));

        $body = '<table width="100%" bgcolor="#f7f7f7" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
   <tbody>
      <tr>
         <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" bgcolor="#2270AE">
               <tbody>
                  <tr>
                     <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>   

<!-- Start of logo -->
<table width="100%" bgcolor="#f7f7f7" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="header">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table bgcolor="#fff" width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                           <tbody>
                             
                              <tr>
                                 <td>
                                    <!-- logo -->
                                    <table width="140" align="center" border="0" cellpadding="0" cellspacing="0" class="devicewidth">
                                       <tbody>
                                          <tr>
                                             <td height="78" align="center">
                                                <div class="imgpop">
                                                   <a target="_blank" href="#">
                                                   <img src="'.$logo.'" alt="" border="0" width="150" height="35" style="display:block; border:none; outline:none; text-decoration:none;">
                                                   </a>
                                                </div>
                                             </td>
                                          </tr>
                                       </tbody>
                                    </table>
                                    
                                 </td>
                              </tr>
                              
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of menu -->
<!-- Start of main-banner -->
<table width="100%" bgcolor="#f7f7f7" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="banner">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" bgcolor="#fff">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                           <tbody>
                              <tr>
                                 <!-- start of image -->
                                 <td align="center" st-image="banner-image">
                                    <div class="imgpop">
                                       <a target="_blank" href="#"><img width="600" border="0" height="350" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;" src="'.$image.'" class="banner"></a>
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        <!-- end of image -->
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- end of 3 columns -->
<!-- Start of seperator -->
<table width="100%" bgcolor="#f7f7f7" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
   <tbody>
      <tr>
         <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" bgcolor="#fff" >
               <tbody>
                  <tr>
                     <td align="center" height="30" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of seperator -->
<!-- End of main-banner -->  
<!-- 3columns -->
<!-- Start of heading content-->
<table width="100%" bgcolor="#f7f7f7" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" bgcolor="#fff">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="550" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                           <tbody>
                              <tr>
                                 
                              </tr>
                              <tr>
                                 <td align="center" height="12" style="font-size:1px; line-height:1px;">&nbsp;</td>
                              </tr>
                           </tbody>
                        </table>

                     </td>
                  </tr>
                  <tr>
                     <td width="100%">
                        <table width="500" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                           <tbody>
                              <tr>
                                 <td align="center" style="font-family: Helvetica, arial, sans-serif; font-size: 14px; color: #2270AE;line-height:1.1;text-align:justify;" bgcolor="#fff" align="center">
                                    '.$content.'
                                 </td>
                              </tr>
                              
                           </tbody>
                        </table>
                        
                     </td>
                  </tr>
                  
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of heading --> 
<!-- 3columns -->
<table width="100%" bgcolor="#f7f7f7" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
   <tbody>
      <tr>
         <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" bgcolor="#fff" >
               <tbody>
                  <tr>
                     <td align="center" height="20" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
                  <tr>
                     <td align="center" height="12" style=";">
                        <a href="'.$links.'" style="font-size:12px; line-height:35px; border:none; outline:none; text-decoration:none;color:#fff;background-color: #C7272E;height:35px;display: block;width:200px;text-transform: uppercase;font-family: Helvetica, arial, sans-serif;">
                            <?=__("GO TO THE WEBSITE",GIFTCARD_TEXT_DOMAIN)?>
                        </a>
                     </td>
                  </tr>
                  <tr>
                     <td align="center" height="22" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- end of 3 columns -->
<table width="100%" bgcolor="#f7f7f7" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
   <tbody>
      <tr>
         <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth" bgcolor="#fff" >
               <tbody>
                  <tr>
                     <td align="center" height="50" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- article -->
<table width="100%" bgcolor="#f7f7f7" cellpadding="0" cellspacing="0" border="0" id="backgroundTable">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth" bgcolor="#2270AE">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table bgcolor="#2270AE" width="530" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidthinner">
                           <tbody>
                              <!-- Spacing -->
                             
                              <!-- Spacing -->
                              <tr>
                                 <td style="font-family: Helvetica, arial, sans-serif; font-size: 12px; color: #fff;line-height:1.3;" align="center">
                                    '.$footer.'
                                 </td>
                              </tr>
                              <tr>
                                 <td width="100%" height="20" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
                              </tr>
                              <!-- /bottom-border -->
                           </tbody>
                        </table>
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- end of article -->
<!-- End of postfooter --> ';
        return $body;
    }
    public function email_header(){
        $template_path = GIFTCARD_PATH.'mail-template/';
        $default_path = GIFTCARD_PATH.'mail-template/';
        wc_get_template( 'email_header_1.php', array(),$template_path,$default_path );
    }
}