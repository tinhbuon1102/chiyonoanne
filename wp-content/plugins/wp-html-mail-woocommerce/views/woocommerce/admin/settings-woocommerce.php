<?php if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
} ?>
<div class="postbox">
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e('How do you want to edit your WooCommerce emails?','haet_mail'); ?></label></th>
                    <td class="">
                        <input type="radio" name="haet_mail_plugins[woocommerce][edit_mode]" id="haet_mail_plugins_woocommerce_edit_mode_global" <?php echo (!isset($plugin_options['woocommerce']['edit_mode']) || $plugin_options['woocommerce']['edit_mode']=='global' ?'checked':''); ?> value="global">
                        <label for="haet_mail_plugins_woocommerce_edit_mode_global">
                            <?php _e('Set one global template and use WooCommerce default content.','haet_mail'); ?>
                        </label>
                        <br>
                        <input type="radio" name="haet_mail_plugins[woocommerce][edit_mode]" id="haet_mail_plugins_woocommerce_edit_mode_mailbuilder" <?php echo (isset($plugin_options['woocommerce']['edit_mode']) && $plugin_options['woocommerce']['edit_mode']=='mailbuilder' ?'checked':''); ?> value="mailbuilder">
                        <label for="haet_mail_plugins_woocommerce_edit_mode_mailbuilder">
                            <?php _e('Customize each email individually.','haet_mail'); ?>
                        </label>
                    </td>
                </tr>
                <?php if( file_exists( get_stylesheet_directory().'/woocommerce/emails/' ) ): ?>
                    <tr valign="top">
                        <th scope="row"><label><?php _e('Use theme email template?','haet_mail'); ?></label></th>
                        <td class="">
                            <p class="description"><?php _e('We found custom email templates in your theme ( YOUR_THEME/woocommerce/emails/ ). You can either keep them to replace individual emails with your custom code or ignore them and only use the settings configured here in WP HTML Mail.','haet_mail'); ?></p><br>
                            <input type="radio" name="haet_mail_plugins[woocommerce][custom_template]" id="haet_mail_plugins_woocommerce_custom_template_keep" <?php echo (!isset($plugin_options['woocommerce']['custom_template']) || $plugin_options['woocommerce']['custom_template']=='keep' ?'checked':''); ?> value="keep">
                            <label for="haet_mail_plugins_woocommerce_custom_template_keep">
                                <?php _e('Use theme template if available.','haet_mail'); ?>
                            </label>
                            <br>
                            <input type="radio" name="haet_mail_plugins[woocommerce][custom_template]" id="haet_mail_plugins_woocommerce_custom_template_ignore" <?php echo (isset($plugin_options['woocommerce']['custom_template']) && $plugin_options['woocommerce']['custom_template']=='ignore' ?'checked':''); ?> value="ignore">
                            <label for="haet_mail_plugins_woocommerce_custom_template_ignore">
                                <?php _e('Ignore email templates from theme.','haet_mail'); ?>
                            </label>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="postbox haet-mail-woocommerce-mailbuilder">
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Build your email','haet_mail'); ?></label></th>
                    <td class="customize-email-content-selection">
                        <?php 
                        $has_addon_emails = false; 
                        if( !is_array( $order_ids ) || count( $order_ids ) == 0 ): ?>
                            <?php _e('Please create at least one order. We need this as demo data.','haet_mail'); ?>
                        <?php else: ?>
                            <?php 
                                // show customization for every mail that has a template file within this plugin
                                // show global template for additonal emails (from WooCommerce AddOns)
                                $addon_emails = array();
                                /*?>
                                <pre><?php print_r($available_woocommerce_mails);?></pre>
                                <?php*/
                                foreach ($available_woocommerce_mails as $woocommerce_mail_name => $woocommerce_mail) :
                                    if( isset($woocommerce_mail->template_html) ):
                                        $template = str_replace( 'emails/', '', $woocommerce_mail->template_html );
                                        $haet_mail_template = apply_filters( 'haet_mail_email_template', HAET_MAIL_WOOCOMMERCE_PATH . 'views/woocommerce/template/' . $template, $woocommerce_mail );
                                        if ( file_exists( $haet_mail_template ) ):
                                            ?>
                                            <h4 style="margin-bottom: 0;">
                                                <?php 
                                                edit_post_link($woocommerce_mail->title . ' <span class="dashicons dashicons-edit"></span>','','', Haet_Mail_Builder()->get_email_post_id( $woocommerce_mail_name  )); 
                                                ?>
                                            </h4>       
                                            <p class="description">
                                                <?php 
                                                echo $woocommerce_mail->description;
                                                ?>
                                            </p>
                                            <?php 
                                        else:
                                            $has_addon_emails = true;
                                            $addon_emails[] = str_replace('_',' ', str_replace('WC_Email_', '', $woocommerce_mail_name ) );
                                        endif;
                                    endif;
                                endforeach; ?>
                                <?php if ( class_exists( 'WGM_Email_Confirm_Order' ) ) : 
                                    ?>
                                    <br><br>
                                    <h3>WooCommerce German Market</h3>

                                    <?php
                                    $wgm_emails = array( 
                                            'customer_order_confirmation' => 'WGM_Email_Confirm_Order',
                                            'double_opt_in_customer_registration' => 'WGM_Email_Double_Opt_In_Customer_Registration',
                                        );


                                    foreach( $wgm_emails AS $email_key => $email_name ):
                                        $email_id = Haet_Mail_Builder()->get_email_post_id( $email_name );
                                        $instance = new $email_name();
                                        if( $instance && $email_id ):
                                            ?>
                                            <h4 style="margin-bottom: 0;">
                                                <?php 
                                                edit_post_link( $instance->title . ' <span class="dashicons dashicons-edit"></span>','','', $email_id ); 
                                                ?>
                                            </h4>       
                                            <p class="description">
                                                <?php 
                                                echo $instance->description;
                                                ?>
                                            </p>
                                        <?php endif; 
                                    endforeach;
                                endif; ?>
                        <?php endif; ?>
                        
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="postbox haet-mail-woocommerce-global-template <?php echo ( $has_addon_emails ? 'has-addon-emails' : '' ); ?>">
    <h3 class="hndle"><span><?php _e('WooCommerce global template','haet_mail'); ?></span></h3>
    <div style="" class="inside">
        <?php if( $has_addon_emails ): ?>
            <div class="addon-emails">
                <h3><?php _e('AddOn emails','haet_mail'); ?></h3>
                <p><?php _e("Your installed WooCommerce addons registered the emails listed below. These emails can't be customized with our mailbuilder but you can add some global settings.",'haet_mail'); ?></p>
                <ul>
                    <?php echo '<li>' . implode('</li><li>', $addon_emails) . '</li>'; ?>
                </ul>
            </div>
        <?php endif; ?>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Show product thumbnails','haet_mail'); ?></label></th>
                    <td>
                        <input type="hidden" name="haet_mail_plugins[woocommerce][thumbs_customer]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_thumbs_customer" name="haet_mail_plugins[woocommerce][thumbs_customer]" value="1" <?php echo (isset($plugin_options['woocommerce']['thumbs_customer']) && $plugin_options['woocommerce']['thumbs_customer']==1 || !isset($plugin_options['woocommerce'])?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_thumbs_customer"><?php _e('for customers','haet_mail'); ?></label><br>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][thumbs_admin]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_thumbs_admin" name="haet_mail_plugins[woocommerce][thumbs_admin]" value="1" <?php echo (isset($plugin_options['woocommerce']['thumbs_admin']) && $plugin_options['woocommerce']['thumbs_admin']==1 || !isset($plugin_options['woocommerce'])?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_thumbs_admin"><?php _e('for admins','haet_mail'); ?></label><br>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Thumbnail size','haet_mail'); ?></label></th>
                    <td>
                        <input type="number" id="haet_mail_plugins_woocommerce_thumb_size" name="haet_mail_plugins[woocommerce][thumb_size]" value="<?php echo (isset($plugin_options['woocommerce']['thumb_size'])?$plugin_options['woocommerce']['thumb_size']:'32'); ?>" style="width:60px;">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="haet_mail_plugins_woocommerce_headline_font"><?php _e('Table Headline Font','haet_mail'); ?></label>
                    </th>
                    <td>
                        <?php 
                            Haet_Mail()->font_toolbar( array(
                                'font'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][headlinefont]',
                                    'value' =>  $plugin_options['woocommerce']['headlinefont']
                                    ),
                                'fontsize'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][headlinefontsize]',
                                    'value' =>  $plugin_options['woocommerce']['headlinefontsize']
                                    ),
                                'color' =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][headlinecolor]',
                                    'value' =>  $plugin_options['woocommerce']['headlinecolor']
                                    ),
                                'bold'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][headlinebold]',
                                    'value' =>  $plugin_options['woocommerce']['headlinebold']
                                    ),
                                'italic'    =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][headlineitalic]',
                                    'value' =>  $plugin_options['woocommerce']['headlineitalic']
                                    ),
                                ) );
                        ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="haet_mail_plugins_woocommerce_content_font"><?php _e('Table Content Font','haet_mail'); ?></label>
                    </th>
                    <td>
                        <?php 
                            Haet_Mail()->font_toolbar( array(
                                'font'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][contentfont]',
                                    'value' =>  $plugin_options['woocommerce']['contentfont']
                                    ),
                                'fontsize'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][contentfontsize]',
                                    'value' =>  $plugin_options['woocommerce']['contentfontsize']
                                    ),
                                'color' =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][contentcolor]',
                                    'value' =>  $plugin_options['woocommerce']['contentcolor']
                                    ),
                                'bold'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][contentbold]',
                                    'value' =>  $plugin_options['woocommerce']['contentbold']
                                    ),
                                'italic'    =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][contentitalic]',
                                    'value' =>  $plugin_options['woocommerce']['contentitalic']
                                    ),
                                ) );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="haet_mail_plugins_woocommerce_variation_font"><?php _e('Variation Font','haet_mail'); ?></label>
                    </th>
                    <td>
                        <?php 
                            Haet_Mail()->font_toolbar( array(
                                'font'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][variationfont]',
                                    'value' =>  $plugin_options['woocommerce']['variationfont']
                                    ),
                                'fontsize'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][variationfontsize]',
                                    'value' =>  $plugin_options['woocommerce']['variationfontsize']
                                    ),
                                'color' =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][variationcolor]',
                                    'value' =>  $plugin_options['woocommerce']['variationcolor']
                                    ),
                                'bold'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][variationbold]',
                                    'value' =>  $plugin_options['woocommerce']['variationbold']
                                    ),
                                'italic'    =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][variationitalic]',
                                    'value' =>  $plugin_options['woocommerce']['variationitalic']
                                    ),
                                ) );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="haet_mail_plugins_woocommerce_total_font"><?php _e('Total Font','haet_mail'); ?></label>
                    </th>
                    <td>
                        <?php 
                            Haet_Mail()->font_toolbar( array(
                                'font'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][totalfont]',
                                    'value' =>  $plugin_options['woocommerce']['totalfont']
                                    ),
                                'fontsize'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][totalfontsize]',
                                    'value' =>  $plugin_options['woocommerce']['totalfontsize']
                                    ),
                                'align'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][totalalign]',
                                    'value' =>  $plugin_options['woocommerce']['totalalign']
                                    ),
                                'color' =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][totalcolor]',
                                    'value' =>  $plugin_options['woocommerce']['totalcolor']
                                    ),
                                'bold'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][totalbold]',
                                    'value' =>  $plugin_options['woocommerce']['totalbold']
                                    ),
                                'italic'    =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][totalitalic]',
                                    'value' =>  $plugin_options['woocommerce']['totalitalic']
                                    ),
                                ) );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php _e('Alignment','haet_mail'); ?></label>
                    </th>
                    <td>
                        <label><?php _e('Quantity','haet_mail'); ?></label>
                        <select name="haet_mail_plugins[woocommerce][quantity_align]">
                            <option value="left" <?php echo ($plugin_options['woocommerce']['quantity_align']=="left"?"selected":""); ?>><?php _e('Left','haet_mail'); ?></option>
                            <option value="center" <?php echo ($plugin_options['woocommerce']['quantity_align']=="center"?"selected":""); ?>><?php _e('Center','haet_mail'); ?></option>
                            <option value="right" <?php echo ($plugin_options['woocommerce']['quantity_align']=="right"?"selected":""); ?>><?php _e('Right','haet_mail'); ?></option>
                        </select>
                        &nbsp;&nbsp; 
                        <label><?php _e('Price','haet_mail'); ?></label>
                        <select name="haet_mail_plugins[woocommerce][price_align]">
                            <option value="left" <?php echo ($plugin_options['woocommerce']['price_align']=="left"?"selected":""); ?>><?php _e('Left','haet_mail'); ?></option>
                            <option value="center" <?php echo ($plugin_options['woocommerce']['price_align']=="center"?"selected":""); ?>><?php _e('Center','haet_mail'); ?></option>
                            <option value="right" <?php echo ($plugin_options['woocommerce']['price_align']=="right"?"selected":""); ?>><?php _e('Right','haet_mail'); ?></option>
                        </select>
                        &nbsp;&nbsp; 
                        <label><?php _e('Address','haet_mail'); ?></label>
                        <select name="haet_mail_plugins[woocommerce][address_align]">
                            <option value="left" <?php echo ($plugin_options['woocommerce']['address_align']=="left"?"selected":""); ?>><?php _e('Left','haet_mail'); ?></option>
                            <option value="center" <?php echo ($plugin_options['woocommerce']['address_align']=="center"?"selected":""); ?>><?php _e('Center','haet_mail'); ?></option>
                            <option value="right" <?php echo ($plugin_options['woocommerce']['address_align']=="right"?"selected":""); ?>><?php _e('Right','haet_mail'); ?></option>
                        </select>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label><?php _e('Header border','haet_mail'); ?></label></th>
                    <td>
                        <input type="text" class="color" id="haet_mail_plugins_woocommerce_header_bordercolor" name="haet_mail_plugins[woocommerce][header_bordercolor]" value="<?php echo ( isset($plugin_options['woocommerce']['header_bordercolor']) ? $plugin_options['woocommerce']['header_bordercolor'] : '#000'); ?>">
                        
                        <input type="hidden" name="haet_mail_plugins[woocommerce][header_border_outer_v]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_header_border_outer_v" class="haet-toggle" name="haet_mail_plugins[woocommerce][header_border_outer_v]" value="1" <?php echo (isset($plugin_options['woocommerce']['header_border_outer_v']) && $plugin_options['woocommerce']['header_border_outer_v']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_header_border_outer_v" class="border-choice-label">
                            <table class="border-choice border-choice-outer-v"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][header_border_inner_v]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_header_border_inner_v" class="haet-toggle" name="haet_mail_plugins[woocommerce][header_border_inner_v]" value="1" <?php echo (isset($plugin_options['woocommerce']['header_border_inner_v']) && $plugin_options['woocommerce']['header_border_inner_v']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_header_border_inner_v" class="border-choice-label">
                            <table class="border-choice border-choice-inner-v"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][header_border_top]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_header_border_top" class="haet-toggle" name="haet_mail_plugins[woocommerce][header_border_top]" value="1" <?php echo (isset($plugin_options['woocommerce']['header_border_top']) && $plugin_options['woocommerce']['header_border_top']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_header_border_top" class="border-choice-label">
                            <table class="border-choice border-choice-top"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][header_border_bottom]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_header_border_bottom" class="haet-toggle" name="haet_mail_plugins[woocommerce][header_border_bottom]" value="1" <?php echo (isset($plugin_options['woocommerce']['header_border_bottom']) && $plugin_options['woocommerce']['header_border_bottom']==1 || !isset($plugin_options['woocommerce'])?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_header_border_bottom" class="border-choice-label">
                            <table class="border-choice border-choice-bottom"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label><?php _e('Products border','haet_mail'); ?></label></th>
                    <td>
                        <input type="text" class="color" id="haet_mail_plugins_woocommerce_products_bordercolor" name="haet_mail_plugins[woocommerce][products_bordercolor]" value="<?php echo ( isset($plugin_options['woocommerce']['products_bordercolor']) ? $plugin_options['woocommerce']['products_bordercolor'] : '#000'); ?>">

                        <input type="hidden" name="haet_mail_plugins[woocommerce][products_border_outer_v]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_products_border_outer_v" class="haet-toggle" name="haet_mail_plugins[woocommerce][products_border_outer_v]" value="1" <?php echo (isset($plugin_options['woocommerce']['products_border_outer_v']) && $plugin_options['woocommerce']['products_border_outer_v']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_products_border_outer_v" class="border-choice-label">
                            <table class="border-choice border-choice-outer-v"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][products_border_inner_v]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_products_border_inner_v" class="haet-toggle" name="haet_mail_plugins[woocommerce][products_border_inner_v]" value="1" <?php echo (isset($plugin_options['woocommerce']['products_border_inner_v']) && $plugin_options['woocommerce']['products_border_inner_v']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_products_border_inner_v" class="border-choice-label">
                            <table class="border-choice border-choice-inner-v"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>
    
                        <input type="hidden" name="haet_mail_plugins[woocommerce][products_border_top]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_products_border_top" class="haet-toggle" name="haet_mail_plugins[woocommerce][products_border_top]" value="1" <?php echo (isset($plugin_options['woocommerce']['products_border_top']) && $plugin_options['woocommerce']['products_border_top']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_products_border_top" class="border-choice-label">
                            <table class="border-choice border-choice-top"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>
                        
                        <input type="hidden" name="haet_mail_plugins[woocommerce][products_border_inner_h]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_products_border_inner_h" class="haet-toggle" name="haet_mail_plugins[woocommerce][products_border_inner_h]" value="1" <?php echo (isset($plugin_options['woocommerce']['products_border_inner_h']) && $plugin_options['woocommerce']['products_border_inner_h']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_products_border_inner_h" class="border-choice-label">
                            <table class="border-choice border-choice-inner-h"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][products_border_bottom]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_products_border_bottom" class="haet-toggle" name="haet_mail_plugins[woocommerce][products_border_bottom]" value="1" <?php echo (isset($plugin_options['woocommerce']['products_border_bottom']) && $plugin_options['woocommerce']['products_border_bottom']==1 || !isset($plugin_options['woocommerce'])?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_products_border_bottom" class="border-choice-label">
                            <table class="border-choice border-choice-bottom"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label><?php _e('Total border','haet_mail'); ?></label></th>
                    <td>
                        <input type="text" class="color" id="haet_mail_plugins_woocommerce_total_bordercolor" name="haet_mail_plugins[woocommerce][total_bordercolor]" value="<?php echo ( isset($plugin_options['woocommerce']['total_bordercolor']) ? $plugin_options['woocommerce']['total_bordercolor'] : '#000'); ?>">

                        <input type="hidden" name="haet_mail_plugins[woocommerce][total_border_outer_v]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_total_border_outer_v" class="haet-toggle" name="haet_mail_plugins[woocommerce][total_border_outer_v]" value="1" <?php echo (isset($plugin_options['woocommerce']['total_border_outer_v']) && $plugin_options['woocommerce']['total_border_outer_v']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_total_border_outer_v" class="border-choice-label">
                            <table class="border-choice border-choice-outer-v"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][total_border_inner_v]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_total_border_inner_v" class="haet-toggle" name="haet_mail_plugins[woocommerce][total_border_inner_v]" value="1" <?php echo (isset($plugin_options['woocommerce']['total_border_inner_v']) && $plugin_options['woocommerce']['total_border_inner_v']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_total_border_inner_v" class="border-choice-label">
                            <table class="border-choice border-choice-inner-v"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][total_border_top]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_total_border_top" class="haet-toggle" name="haet_mail_plugins[woocommerce][total_border_top]" value="1" <?php echo (isset($plugin_options['woocommerce']['total_border_top']) && $plugin_options['woocommerce']['total_border_top']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_total_border_top" class="border-choice-label">
                            <table class="border-choice border-choice-top"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][total_border_inner_h]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_total_border_inner_h" class="haet-toggle" name="haet_mail_plugins[woocommerce][total_border_inner_h]" value="1" <?php echo (isset($plugin_options['woocommerce']['total_border_inner_h']) && $plugin_options['woocommerce']['total_border_inner_h']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_total_border_inner_h" class="border-choice-label">
                            <table class="border-choice border-choice-inner-h"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][total_border_bottom]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_total_border_bottom" class="haet-toggle" name="haet_mail_plugins[woocommerce][total_border_bottom]" value="1" <?php echo (isset($plugin_options['woocommerce']['total_border_bottom']) && $plugin_options['woocommerce']['total_border_bottom']==1 || !isset($plugin_options['woocommerce'])?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_total_border_bottom" class="border-choice-label">
                            <table class="border-choice border-choice-bottom"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>


<div class="postbox haet-mail-woocommerce-additional-table-settings">
    <h3 class="hndle"><span><?php _e('Additional tables','haet_mail'); ?></span></h3>
    <div style="" class="inside">
        <div class="addon-emails">
            <p><?php _e('Use these settings for additional tables. For example a table of downloads if you sell digital products or a table of subscription infos if you have some kind of membership site.','haet_mail'); ?></p>
        </div>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label><?php _e('Table Headline Font','haet_mail'); ?></label>
                    </th>
                    <td>
                        <?php 
                            Haet_Mail()->font_toolbar( array(
                                'font'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][additional_headlinefont]',
                                    'value' =>  $plugin_options['woocommerce']['additional_headlinefont']
                                    ),
                                'fontsize'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][additional_headlinefontsize]',
                                    'value' =>  $plugin_options['woocommerce']['additional_headlinefontsize']
                                    ),
                                'color' =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][additional_headlinecolor]',
                                    'value' =>  $plugin_options['woocommerce']['additional_headlinecolor']
                                    ),
                                'bold'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][additional_headlinebold]',
                                    'value' =>  $plugin_options['woocommerce']['additional_headlinebold']
                                    ),
                                'italic'    =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][additional_headlineitalic]',
                                    'value' =>  $plugin_options['woocommerce']['additional_headlineitalic']
                                    ),
                                ) );
                        ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label><?php _e('Table Content Font','haet_mail'); ?></label>
                    </th>
                    <td>
                        <?php 
                            Haet_Mail()->font_toolbar( array(
                                'font'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][additional_contentfont]',
                                    'value' =>  $plugin_options['woocommerce']['additional_contentfont']
                                    ),
                                'fontsize'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][additional_contentfontsize]',
                                    'value' =>  $plugin_options['woocommerce']['additional_contentfontsize']
                                    ),
                                'color' =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][additional_contentcolor]',
                                    'value' =>  $plugin_options['woocommerce']['additional_contentcolor']
                                    ),
                                'bold'  =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][additional_contentbold]',
                                    'value' =>  $plugin_options['woocommerce']['additional_contentbold']
                                    ),
                                'italic'    =>  array(
                                    'name'  =>  'haet_mail_plugins[woocommerce][additional_contentitalic]',
                                    'value' =>  $plugin_options['woocommerce']['additional_contentitalic']
                                    ),
                                ) );
                        ?>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label><?php _e('Header border','haet_mail'); ?></label></th>
                    <td>
                        <input type="text" class="color" id="haet_mail_plugins_woocommerce_additional_header_bordercolor" name="haet_mail_plugins[woocommerce][additional_header_bordercolor]" value="<?php echo ( isset($plugin_options['woocommerce']['additional_header_bordercolor']) ? $plugin_options['woocommerce']['additional_header_bordercolor'] : '#000'); ?>">
                        
                        <input type="hidden" name="haet_mail_plugins[woocommerce][additional_header_border_outer_v]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_additional_header_border_outer_v" class="haet-toggle" name="haet_mail_plugins[woocommerce][additional_header_border_outer_v]" value="1" <?php echo (isset($plugin_options['woocommerce']['additional_header_border_outer_v']) && $plugin_options['woocommerce']['additional_header_border_outer_v']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_additional_header_border_outer_v" class="border-choice-label">
                            <table class="border-choice border-choice-outer-v"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][additional_header_border_inner_v]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_additional_header_border_inner_v" class="haet-toggle" name="haet_mail_plugins[woocommerce][additional_header_border_inner_v]" value="1" <?php echo (isset($plugin_options['woocommerce']['additional_header_border_inner_v']) && $plugin_options['woocommerce']['additional_header_border_inner_v']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_additional_header_border_inner_v" class="border-choice-label">
                            <table class="border-choice border-choice-inner-v"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][additional_header_border_top]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_additional_header_border_top" class="haet-toggle" name="haet_mail_plugins[woocommerce][additional_header_border_top]" value="1" <?php echo (isset($plugin_options['woocommerce']['additional_header_border_top']) && $plugin_options['woocommerce']['additional_header_border_top']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_additional_header_border_top" class="border-choice-label">
                            <table class="border-choice border-choice-top"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][additional_header_border_bottom]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_additional_header_border_bottom" class="haet-toggle" name="haet_mail_plugins[woocommerce][additional_header_border_bottom]" value="1" <?php echo (isset($plugin_options['woocommerce']['additional_header_border_bottom']) && $plugin_options['woocommerce']['additional_header_border_bottom']==1 || !isset($plugin_options['woocommerce'])?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_additional_header_border_bottom" class="border-choice-label">
                            <table class="border-choice border-choice-bottom"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label><?php _e('Products border','haet_mail'); ?></label></th>
                    <td>
                        <input type="text" class="color" id="haet_mail_plugins_woocommerce_additional_products_bordercolor" name="haet_mail_plugins[woocommerce][additional_products_bordercolor]" value="<?php echo ( isset($plugin_options['woocommerce']['additional_products_bordercolor']) ? $plugin_options['woocommerce']['additional_products_bordercolor'] : '#000'); ?>">

                        <input type="hidden" name="haet_mail_plugins[woocommerce][additional_products_border_outer_v]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_additional_products_border_outer_v" class="haet-toggle" name="haet_mail_plugins[woocommerce][additional_products_border_outer_v]" value="1" <?php echo (isset($plugin_options['woocommerce']['additional_products_border_outer_v']) && $plugin_options['woocommerce']['additional_products_border_outer_v']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_additional_products_border_outer_v" class="border-choice-label">
                            <table class="border-choice border-choice-outer-v"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][additional_products_border_inner_v]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_additional_products_border_inner_v" class="haet-toggle" name="haet_mail_plugins[woocommerce][additional_products_border_inner_v]" value="1" <?php echo (isset($plugin_options['woocommerce']['additional_products_border_inner_v']) && $plugin_options['woocommerce']['additional_products_border_inner_v']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_additional_products_border_inner_v" class="border-choice-label">
                            <table class="border-choice border-choice-inner-v"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>
    
                        <input type="hidden" name="haet_mail_plugins[woocommerce][additional_products_border_top]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_additional_products_border_top" class="haet-toggle" name="haet_mail_plugins[woocommerce][additional_products_border_top]" value="1" <?php echo (isset($plugin_options['woocommerce']['additional_products_border_top']) && $plugin_options['woocommerce']['additional_products_border_top']==1 ?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_additional_products_border_top" class="border-choice-label">
                            <table class="border-choice border-choice-top"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>

                        <input type="hidden" name="haet_mail_plugins[woocommerce][additional_products_border_bottom]" value="0">
                        <input type="checkbox" id="haet_mail_plugins_woocommerce_additional_products_border_bottom" class="haet-toggle" name="haet_mail_plugins[woocommerce][additional_products_border_bottom]" value="1" <?php echo (isset($plugin_options['woocommerce']['additional_products_border_bottom']) && $plugin_options['woocommerce']['additional_products_border_bottom']==1 || !isset($plugin_options['woocommerce'])?'checked':''); ?>>
                        <label for="haet_mail_plugins_woocommerce_additional_products_border_bottom" class="border-choice-label">
                            <table class="border-choice border-choice-bottom"><tr><td></td><td></td></tr><tr><td></td><td></td></tr></table>
                        </label>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>


<div class="postbox">
    <div style="" class="inside">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Preview woocommerce mail','haet_mail'); ?></label></th>
                    <td>
                        <select id="haet_mail_plugins_woocommerce_preview_order" name="haet_mail_plugins[woocommerce][preview_order]" >
                            <?php foreach ($order_ids as $order_id) :?>
                                <option value="<?php echo $order_id; ?>" <?php echo ($plugin_options['woocommerce']['preview_order']==$order_id?'selected':''); ?>><?php echo __('Order #','haet_mail').$order_id; ?></option>       
                            <?php endforeach; ?>
                        </select>
                        <select id="haet_mail_plugins_woocommerce_preview_mail" name="haet_mail_plugins[woocommerce][preview_mail]" >
                            <?php foreach ($available_woocommerce_mails as $woocommerce_mail_name => $woocommerce_mail) :
                                // exclude emails from WooCommerce Booking because they expect another object, not an order
                                if( false === strpos( $woocommerce_mail_name, '_Booking' ) ): ?>
                                    <option value="<?php echo $woocommerce_mail_name; ?>" <?php echo ($plugin_options['woocommerce']['preview_mail']==$woocommerce_mail_name?'selected':''); ?>><?php echo str_replace('_',' ', str_replace('WC_Email_', '', $woocommerce_mail_name) ); ?></option>       
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label><?php _e('Send test mails','haet_mail'); ?></label></th>
                    <td>
                        <?php _e('Go to the order edit screen to resend some order mails and see the results in your mail client.','haet_mail'); ?>
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
</div>
<?php 
// output default settings
// echo '<pre>';
// foreach ($plugin_options['woocommerce'] as $key => $value)
//     echo "'$key' => '$value',\n";
// echo '</pre>';
?>