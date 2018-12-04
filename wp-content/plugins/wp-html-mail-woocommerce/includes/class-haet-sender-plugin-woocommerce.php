<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
*   detect the origin of an email
*
**/
class Haet_Sender_Plugin_WooCommerce extends Haet_Sender_Plugin {
    public function __construct($mail) {
        if( !strpos($mail['message'], '<!--woocommerce-content-start-->') )
            throw new Haet_Different_Plugin_Exception();
    }

    /**
    *   request_preview_instance()
    *   force creating an instance to apply all modifications to live preview
    **/
    public static function request_preview_instance(){
        $fake_mail = array('message'=>' <!--woocommerce-content-start-->');
        return new self($fake_mail);
    }

    /**
    *   modify_template()
    *   mofify the email template before the content is added
    **/
    public function modify_template($template){
        $plugin_options = self::get_plugin_options();
        // keep media queries at the bottom of CSS block
        $css = file_get_contents( HAET_MAIL_WOOCOMMERCE_PATH.'views/woocommerce/template/general-desktop.css' );
        $css .= file_get_contents( HAET_MAIL_WOOCOMMERCE_PATH.'views/woocommerce/template/global-template-desktop.css' );
        $template = str_replace('/**** ADD CSS HERE ****/', $css . '/**** ADD CSS HERE ****/', $template);
        
        // keep media queries at the bottom of CSS block
        $css = file_get_contents( HAET_MAIL_WOOCOMMERCE_PATH.'views/woocommerce/template/general-mobile.css' );
        $css .= file_get_contents( HAET_MAIL_WOOCOMMERCE_PATH.'views/woocommerce/template/global-template-mobile.css' );
        $template = str_replace('/**** ADD CSS HERE ****/', $css . '/**** ADD MOBILE CSS HERE ****/', $template);
        
        $options = $plugin_options['woocommerce'];
        foreach ($options as $option => $value) {
            if(strpos($option, 'bold'))
                $value=($value==1?'bold':'normal');
            if(strpos($option, 'italic'))
                $value=($value==1?'italic':'normal');
            if(strpos($option, 'border_'))
                $value=($value==1?'solid 1px':'none 0');
            $template = str_replace('###woocommerce_'.$option.'###', $value, $template);
        }
        $template = preg_replace("/###woocommerce\_.*\_border\_.*###/", "none 0", $template);

        
        return $template;
    }    

    /**
    *   settings_tab()
    *   output specific settings for this plugin
    **/
    public static function settings_tab(){
        $plugin_options = self::get_plugin_options();
        // get latest order ids for preview
        $order_ids = array();
        $args = array (
            'post_type'              => 'shop_order',
            'posts_per_page'         => 30,
            'post_status'            => 'any'
        );

        // The Query
        $query = new WP_Query( $args );
        // The Loop
        while ( $query->have_posts() ) {
            $query->the_post();
            $order_ids[] = get_the_id();
        }
        wp_reset_postdata();

        $wc_mail = WC_Emails::instance();
        $available_woocommerce_mails = $wc_mail->emails;

        
        $fonts = Haet_Mail()->get_fonts();
        include( HAET_MAIL_WOOCOMMERCE_PATH.'views/woocommerce/admin/settings-woocommerce.php' );
    }

    /**
    *   modify_content()
    *   mofify the email content before applying the template
    **/
    public function modify_content($content){
        $startpos = strpos($content, '<!--woocommerce-content-start-->');
        $endpos = strpos($content, '<!--woocommerce-content-end-->');
        
        if(!$startpos || !$endpos)
            return $content;
        
        $content = substr($content, $startpos, $endpos-$startpos);


        //make styles overwriteable
        $content = str_replace('!important', '', $content);

        return $content;
        
    }

    /**
    *   modify_styled_mail()
    *   mofify the email body after the content has been added to the template
    **/
    public function modify_styled_mail($message){
        if( !strpos( $message, '<!--mailbuilder-content-start-->' ) ){
            $message = preg_replace("/(<body[^<]*>)/i", "$1 <div class=\"woocommerce-global-template\">", $message);
            $message = preg_replace("/(<\/body[^<]*>)/i", "</div>$1", $message);
        }
        return $message;
    } 

    /**
    *   email_header()
    *   add a marker to the end of the woocommerce header to find the content in between
    **/
    public static function email_header(){
        echo '<!--woocommerce-content-start-->';

    }

    /**
    *   email_footer()
    *   add a marker to the top of the woocommerce footer to find the content in between
    **/
    public static function email_footer(){
        echo '<!--woocommerce-content-end-->';
    }


    public static function plugin_actions_and_filters(){

        // remove woocommerce email styling
        add_action( 'woocommerce_email_header', 'Haet_Sender_Plugin_WooCommerce::email_header', 100 );
        add_action( 'woocommerce_email_footer', 'Haet_Sender_Plugin_WooCommerce::email_footer', 1 );
        add_action( 'woocommerce_email_styles','__return_empty_string');

        // override some of the woocommerce templates
        add_filter( 'wc_get_template', 'Haet_Sender_Plugin_WooCommerce::replace_template',10,5 );

        //override WC German Market templates
        add_filter( 'wgm_locate_template', 'Haet_Sender_Plugin_WooCommerce::replace_template_wgm',10,3 );


        // preview woocommerce mails
        add_filter( 'haet_mail_demo_content','Haet_Sender_Plugin_WooCommerce::demo_content',10,4);

        // remove woocommerce standard email formatting settings
        add_filter( 'woocommerce_email_settings', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_configs',10,1);
        // add_filter( 'woocommerce_settings_api_form_fields_cancelled_order', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_emailtype_configs',10,1);
        // add_filter( 'woocommerce_settings_api_form_fields_failed_order', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_emailtype_configs',10,1);
        // add_filter( 'woocommerce_settings_api_form_fields_new_order', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_emailtype_configs',10,1);
        // add_filter( 'woocommerce_settings_api_form_fields_completed_order', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_emailtype_configs',10,1);
        // add_filter( 'woocommerce_settings_api_form_fields_invoice', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_emailtype_configs',10,1);
        // add_filter( 'woocommerce_settings_api_form_fields_new_account', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_emailtype_configs',10,1);
        // add_filter( 'woocommerce_settings_api_form_fields_customer_note', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_emailtype_configs',10,1);
        // add_filter( 'woocommerce_settings_api_form_fields_processing_order', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_emailtype_configs',10,1);
        // add_filter( 'woocommerce_settings_api_form_fields_customer_refunden_order', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_emailtype_configs',10,1);
        // add_filter( 'woocommerce_settings_api_form_fields_customer_partially_refunded_order', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_emailtype_configs',10,1);
        // add_filter( 'woocommerce_settings_api_form_fields_customer_reset_password', 'Haet_Sender_Plugin_WooCommerce::remove_woocommerce_emailtype_configs',10,1);
        
        // make sure the default "powered by woocommerce" message doesn't appear
        add_filter( 'woocommerce_email_footer_text', '__return_empty_string', 11);
    }




    /**
    *   replace_template()
    *   override some of the WooCommerce mail templates
    **/
    public static function replace_template( $located, $template_name, $args, $template_path, $default_path ){
        global $haet_mail_options;
        global $haet_mail_plugin_options;
        global $current_mail_template;

        $haet_mail_plugin_options = self::get_plugin_options();
        $haet_mail_options = Haet_Mail()->get_options();

        if( strpos( $template_name, 'email' ) !== false ){
            $current_mail_template = preg_replace("/emails\/(.*).php/", "$1", $template_name);
            if( $haet_mail_plugin_options['woocommerce']['custom_template'] != 'ignore' && file_exists( get_stylesheet_directory().'/woocommerce/' . $template_name ) ) // has custom template
                return $located;
            elseif( file_exists( HAET_MAIL_WOOCOMMERCE_PATH.'views/woocommerce/template/'.$current_mail_template.'.php' ) ) // plugin contains a template
                return HAET_MAIL_WOOCOMMERCE_PATH.'views/woocommerce/template/'.$current_mail_template.'.php';
            
        }

        return $located;
    }





    public static function replace_template_wgm( $template, $template_name, $template_path ){
        global $haet_mail_options;
        global $haet_mail_plugin_options;
        global $current_mail_template;
        $haet_mail_plugin_options = self::get_plugin_options();
        $haet_mail_options = Haet_Mail()->get_options();

        if( strpos( $template_name, 'email' ) !== false ){
            $current_mail_template = preg_replace("/emails\/(.*).php/", "$1", $template_name);
            if( file_exists( get_stylesheet_directory().'/woocommerce/' . $template_name ) ) // has custom template
                return $template;
            elseif( file_exists( HAET_MAIL_WOOCOMMERCE_PATH.'views/woocommerce/template/'.$current_mail_template.'.php' ) ) // plugin contains a template
                return HAET_MAIL_WOOCOMMERCE_PATH.'views/woocommerce/template/'.$current_mail_template.'.php';
            
        }

        return $template;
    }




    public static function get_preview_object( $plugin_options ){
        $preview_object = array(
                'success'   => false,
                'object'    =>  null,
                'message'   =>  ''
            );

        if( isset( $plugin_options['woocommerce']['preview_order'] ) && is_numeric( $plugin_options['woocommerce']['preview_order'] ) ){
            $order_id = $plugin_options['woocommerce']['preview_order'];
            $order_status = get_post_status( $order_id );
            if( FALSE !== $order_status && 'trash' != $order_status )
                $order = wc_get_order( $order_id );
        }

        if( !isset($order) ){
            // get latest order id
            $args = array (
                'post_type'              => 'shop_order',
                'posts_per_page'         => '1',
                'order'                  => 'DESC',
                'orderby'                => 'ID',
                'post_status'            => 'any',
                'fields'                 => 'ids'
            );

            // The Query
            $query = new WP_Query( $args );
            // The Loop
            if ( $query->have_posts() ) {
                $order = wc_get_order( $query->posts[0] );
            }
        }


        if( $order ){
            $preview_object['object'] = $order;
            $preview_object['success'] = true;

            // WooCommerce Germanized Pro - Invoice Cancellation Email
            if( 'WC_GZDP_Email_Customer_Invoice_Cancellation' == $plugin_options['woocommerce']['preview_mail'] ){
                if ( $order->invoices ) {
                    $preview_object['object'] = $order->invoices[0];
                    $preview_object['success'] = true;
                }else{
                    $preview_object = array(
                            'success'   => false,
                            'object'    => null,
                            'message'   =>  __('This order has no invoice yet, please select another order for preview.','haet_mail')
                        );
                }
            }

            // Default Invoice Mail with Germanized Pro enabled
            if( 'WC_Email_Customer_Invoice' == $plugin_options['woocommerce']['preview_mail'] 
                && is_plugin_active( 'woocommerce-germanized-pro/woocommerce-germanized-pro.php' )){
                    $preview_object = array(
                            'success'   => false,
                            'object'    => null,
                            'message'   =>  __('The invoice email is modified by Germanized Pro and can not be shown in preview.','haet_mail')
                        );
            }



            if( 'WC_Email_Customer_Refunded_Order' == $plugin_options['woocommerce']['preview_mail'] && !$order->get_refunds() ){
                $preview_object = array(
                        'success'   => false,
                        'object'    => null,
                        'message'   =>  __('This order has no refund yet, please select another order for preview.','haet_mail')
                    );
            }


            // WooCommerce Subscriptions
            if( in_array( $plugin_options['woocommerce']['preview_mail'], array( 
                        'WCS_Email_Cancelled_Subscription',
                        'WCS_Email_Expired_Subscription',
                        'WCS_Email_On_Hold_Subscription',
                        'WCS_Email_Customer_Payment_Retry',
                        'WCS_Email_Payment_Retry'
                    )
                ) && (
                    !property_exists( $order, 'order_type' ) 
                    || $order->order_type != 'shop_subscription'
                )){
                $preview_object = array(
                        'success'   => false,
                        'object'    => null,
                        'message'   =>  __('This order does not contain a subscription, please select another order for preview.','haet_mail')
                    );
            }
        }


        // this email requires a product instead of an order as object
        if( 'Pie_WCWL_Waitlist_Mailout' == $plugin_options['woocommerce']['preview_mail'] ){
            $args = array (
                'post_type'              => 'product',
                'posts_per_page'         => '1',
                'orderby'                => 'rand',
                'post_status'            => 'publish',
                'fields'                 => 'ids'
            );

            $query = new WP_Query( $args );

            if ( $query->have_posts() ) {
                $preview_object['object'] = wc_get_product( $query->posts[0] );
                $preview_object['success'] = true;
            }
        }elseif( !$order ){
            $preview_object = array(
                    'success'   => false,
                    'object'    => null,
                    'message'   =>  __('You need to have at least one active order to preview and customize WooCommerce emails.','haet_mail')
                );
        }

        $preview_object = apply_filters( 'haet_mail_woocommerce_demo_preview_object' , $preview_object, $plugin_options );

        return $preview_object;
    }


    /**
    *   demo_content( )
    *   Preview real emails from order history
    **/
    public static function demo_content( $demo_content, $options, $plugin_options, $tab ){
        if( $tab == 'woocommerce' && isset( $plugin_options['woocommerce'] ) ){
            // show a standard preview in case of error
            // $plugin_options['woocommerce']['preview_mail'] = 'WC_Email_New_Order';
            $preview_object = self::get_preview_object( $plugin_options );

            if( isset( $plugin_options['woocommerce']['preview_mail'] ) )
                $preview_mail = $plugin_options['woocommerce']['preview_mail'];
            else
                $preview_mail = 'WC_Email_New_Order';
            
            if( $preview_object['success'] ){
                $wc_mail = WC()->mailer();
                if( array_key_exists($preview_mail, $wc_mail->emails) )
                    $demo_mail = $wc_mail->emails[$preview_mail];
                else 
                    $demo_mail = reset($wc_mail->emails);

                $demo_mail->object = $preview_object['object'];

                
                return $demo_mail->get_content();
            }else
                return $preview_object['message'];
        }
        return $demo_content;
    }




    /**
    *   remove_woocommerce_configs($settings)
    *   remove all styling and sender settings from first tab of WooCommerce email options 
    **/
    public static function remove_woocommerce_configs($settings){
        $remove_settings = array(
                'email_template_options',
                'woocommerce_email_header_image',
                'woocommerce_email_footer_text',
                'woocommerce_email_base_color',
                'woocommerce_email_background_color',
                'woocommerce_email_body_background_color',
                'woocommerce_email_text_color',
            );
        foreach ($settings as $index => $setting) {
            if( isset($setting['id']) && in_array( $setting['id'] , $remove_settings ) )
                unset( $settings[$index] );
        }


        return $settings;
    }


    public static function remove_woocommerce_emailtype_configs( $form_fields ){
        // echo '<pre>'.print_r($form_fields,true).'</pre>';
        unset( $form_fields['email_type'] );
                
        return $form_fields;
    }





    /**
    *   get_plugin_default_options()
    *   define plugin specific default options
    **/
    public static function get_plugin_default_options(){
        return array(
            'template' => true,
            'sender' => true,
            'custom_template' => 'ignore',
            'thumbs_customer' => '1',
            'thumbs_admin' => '1',
            'thumb_size' => '32',
            'headlinefont' => 'Arial, Helvetica, sans-serif',
            'headlinefontsize' => '14',
            'headlinecolor' => '#000000',
            'headlinebold' => '1',
            'headlineitalic' => '0',
            'contentfont' => 'Arial, Helvetica, sans-serif',
            'contentfontsize' => '13',
            'contentcolor' => '#000000',
            'contentbold' => '0',
            'contentitalic' => '0',
            'variationfont' => 'Arial, Helvetica, sans-serif',
            'variationfontsize' => '12',
            'variationcolor' => '#828282',
            'variationbold' => '0',
            'variationitalic' => '0',
            'totalfont' => 'Arial, Helvetica, sans-serif',
            'totalfontsize' => '13',
            'totalalign' => 'right',
            'totalcolor' => '#000000',
            'totalbold' => '1',
            'totalitalic' => '0',
            'quantity_align' => 'left',
            'price_align' => 'left',
            'address_align' => 'left',
            'header_bordercolor' => '#000',
            'header_border_outer_v' => '0',
            'header_border_inner_v' => '0',
            'header_border_top' => '0',
            'header_border_bottom' => '1',
            'products_bordercolor' => '#c9c9c9',
            'products_border_outer_v' => '0',
            'products_border_inner_v' => '0',
            'products_border_top' => '0',
            'products_border_inner_h' => '1',
            'products_border_bottom' => '1',
            'total_bordercolor' => '#000',
            'total_border_outer_v' => '0',
            'total_border_inner_v' => '0',
            'total_border_top' => '0',
            'total_border_inner_h' => '0',
            'total_border_bottom' => '0',
            // additional tables
            'additional_headlinefont' => 'Arial, Helvetica, sans-serif',
            'additional_headlinefontsize' => '14',
            'additional_headlinecolor' => '#000000',
            'additional_headlinebold' => '1',
            'additional_headlineitalic' => '0',
            'additional_contentfont' => 'Arial, Helvetica, sans-serif',
            'additional_contentfontsize' => '13',
            'additional_contentcolor' => '#000000',
            'additional_contentbold' => '0',
            'additional_contentitalic' => '0',
            'additional_header_bordercolor' => '#000',
            'additional_header_border_outer_v' => '0',
            'additional_header_border_inner_v' => '0',
            'additional_header_border_top' => '0',
            'additional_header_border_bottom' => '1',
            'additional_products_bordercolor' => '#c9c9c9',
            'additional_products_border_outer_v' => '0',
            'additional_products_border_inner_v' => '0',
            'additional_products_border_top' => '0',
            'additional_products_border_inner_h' => '1',
            'additional_products_border_bottom' => '1',
            // preview
            'preview_order' => '',
            'preview_mail' => 'WC_Email_New_Order',
        );
    }
}