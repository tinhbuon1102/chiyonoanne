<?php if ( ! defined( 'ABSPATH' ) ) exit;


final class WPHTMLMail_Woocommerce
{

    private static $instance;

    
    
    public static function instance(){
        if (!isset(self::$instance) && !(self::$instance instanceof WPHTMLMail_Woocommerce)) {
            self::$instance = new WPHTMLMail_Woocommerce();
        }

        return self::$instance;
    }




    public function __construct(){
        add_filter( 'mce_external_plugins', array( $this,'register_editor_plugins') );
        add_filter( 'mce_buttons', array( $this,'register_editor_buttons') );        
        add_filter( 'tiny_mce_before_init', array( $this, 'customize_editor_toolbar'), 100 );

        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_scripts_and_styles') );
        
        // core plugin up to 2.7.3
        // add_action( 'add_meta_boxes', array( $this, 'setup_meta_boxes' ) );
        // core plugin after 2.7.3
        add_action( 'haet_mailbuilder_header_buttons', array( $this, 'mailbuilder_header_buttons' ) );

        add_action( 'save_post', array($this,'save_post'), 20 );

        add_filter( 'haet_mail_enqueue_js_data', array( $this, 'enqueue_js_translations' ) );
        add_filter( 'haet_mail_enqueue_js_data', array( $this, 'enqueue_js_placeholder_values' ) );
        add_filter( 'haet_mail_enqueue_js_data', array( $this, 'enqueue_js_placeholder_menu' ) );
        add_action( 'haet_mailbuilder_create_email', array( $this, 'set_email_default_content' ), 10, 2 );

        add_filter( 'haet_mail_print_content_text', array( $this, 'fill_placeholders_text' ), 10, 3 );
        add_filter( 'haet_mail_print_content_twocol', array( $this, 'fill_placeholders_text' ), 10, 3 );

        add_action( 'init', array( $this, 'replace_email_subjects' ) );

        add_filter( 'woocommerce_email_settings', array( $this, 'add_link_to_wc_email_settings' ) );

        if(is_plugin_active( 'woocommerce-german-market/WooCommerce-German-Market.php' )){
            add_filter( 'wgm_start_email_footer_html', function( $html ){
                return '<div class="wgm-wrap-email-appendixes">';
            });

            add_filter( 'woocommerce_de_add_delivery_time_to_product_title', array( $this, 'wgm_wrap_delivery_time' ), 10, 3 );
        }
        
        add_action( 'haet_mail_plugin_reset_buttons', array( $this, 'show_reset_buttons' ));
        add_action( 'haet_mail_plugin_reset_actions', array( $this, 'process_reset_actions' ));

        // WooCommerce::session and WooCommerce::customer are only loaded in frontend so they may not be defined here
        if( !WC()->customer ){
            if( !WC()->session ){
                // include_once( trailingslashit( dirname( WC_PLUGIN_FILE ) ).'includes/abstracts/abstract-wc-session.php' );
                // include_once( trailingslashit( dirname( WC_PLUGIN_FILE ) ).'includes/class-wc-session-handler.php' );
                 
                // load all frontend functions for compatibiliyt reasons e.g. with dynamic pricing plugin which requires wc_add_notice
                wc()->frontend_includes();
                $session_class  = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
                WC()->session  = new $session_class();
            }
            include_once( trailingslashit( dirname( WC_PLUGIN_FILE ) ).'includes/wc-cart-functions.php' );
            WC()->customer = new WC_Customer();

        }
        
        add_action( 'init', array( $this, 'init_cart' ) );
    }



    public function init_cart(){
        if( !WC()->cart )
            WC()->cart = new WC_Cart();
    }



    public function replace_email_subjects(){
        $emails = $this->get_supported_woocommerce_emails();
        foreach( $emails AS $email_key => $email_name ){
            add_filter( 
                'woocommerce_email_subject_' . $email_key, 
                function( $subject, $order ) use ( $email_name, $email_key ){
                    $email_id = Haet_Mail_Builder()->get_email_post_id( $email_name );
                    $instance = $this->get_email_instance_by_key( $email_key );
                    if( $instance && $email_id ){
                        $new_subject = get_post_meta( $email_id, 'subject', true );
                        if( $new_subject ){
                            $settings = array( 
                                    'wc_order' => $order, 
                                    'wc_email' => $instance, 
                                    'wc_sent_to_admin' => false,
                                    'plain_text' => true
                                );
                            $new_subject = $this->fill_placeholders_text($new_subject, '', $settings);
                            return strip_tags( $new_subject );
                        }
                    }
                    return $subject;
                }, 10, 2 );
        }
    }





    /**
     * Searches for a WC_Email instance with the given ID and returns it if found
     * @param string $id - email id
     * @return WC_Email|null - email instance or null if not found
     */
    function get_email_instance_by_key( $key ) {
        $mailer = WC()->mailer();
        $wc_email_objects = $mailer->get_emails();

        foreach ( $wc_email_objects as $email ) {
            if ( $key === $email->id ) 
                return $email;
        }
        return null;
    }



    public function get_supported_woocommerce_emails(){
        // we could do this automatically with
        // $wc_mail = WC()->mailer();
        // $emails = $wc_mail->emails
        // but this needs up do 50 database queries
        return apply_filters( 'haet_mail_supported_woocommerce_emails', 
                    array(
                        'new_order' => 'WC_Email_New_Order', 
                        'cancelled_order' => 'WC_Email_Cancelled_Order', 
                        'failed_order' => 'WC_Email_Failed_Order', 
                        'customer_on_hold_order' => 'WC_Email_Customer_On_Hold_Order', 
                        'customer_processing_order' => 'WC_Email_Customer_Processing_Order', 
                        'customer_completed_order' => 'WC_Email_Customer_Completed_Order', 
                        'customer_refunded_order' => 'WC_Email_Customer_Refunded_Order', 
                        'customer_invoice' => 'WC_Email_Customer_Invoice', 
                        'customer_note' => 'WC_Email_Customer_Note', 
                        'customer_reset_password' => 'WC_Email_Customer_Reset_Password', 
                        'customer_new_account' => 'WC_Email_Customer_New_Account',
                    ) 
                );
    }




    public function enqueue_scripts_and_styles($page){
        if( false !== strpos($page, 'post.php')){
            wp_enqueue_style('haet_mailbuilder_woocommerce_css',  HAET_MAIL_WOOCOMMERCE_URL.'/css/mailbuilder.css');
            wp_enqueue_script( 'haet_mailbuilder_woocommerce_js',  HAET_MAIL_WOOCOMMERCE_URL.'/js/mailbuilder.js', array( 'jquery' ) );
        }
    }




    public function customize_editor_toolbar( $initArray ) {  
        global $post;
        if ( $post ){
            $post_type = get_post_type( $post->ID );
            if( 'wphtmlmail_mail' == $post_type && $initArray['selector'] == '#mb_wysiwyg_editor' ){
                $initArray['toolbar1'] .= ',mb_woocommercetext_placeholder';
            }
        }

        return $initArray;  
      
    } 





    public function set_email_default_content( $post_id, $email_name ){
        include('email-default-content.php');
        if( array_key_exists( $email_name, $mailbuilder_content ) )
            update_post_meta( $post_id, 'mailbuilder_json', addslashes( json_encode( $mailbuilder_content[$email_name] ) ) );
        if( array_key_exists( $email_name, $mailbuilder_subject ) )
            update_post_meta( $post_id, 'subject', $mailbuilder_subject[$email_name]);
    }




    function register_editor_buttons($buttons){
        array_push( $buttons, 'mb_woocommercetext_placeholder' );
        return $buttons;
    }





    function register_editor_plugins($plugin_array) {
        global $post;
        if( $post ){
            $post_type = get_post_type( $post->ID );
            if( 'wphtmlmail_mail' == $post_type ){
                $plugin_array['mb_woocommercetext_placeholder'] = HAET_MAIL_WOOCOMMERCE_URL.'/js/contenttype-text-editor-placeholder.js';
            }
        }
        return $plugin_array;
    }




    /**
     * Add PHP data to the main MailBuilder Javascript file using wp_localize_script
     * @param  array $enqueue_data
     * @return array $enqueue_data
     */
    public function enqueue_js_translations( $enqueue_data ){
        $enqueue_data['translations']['placeholder_button']     = __('Placeholder','woocommerce');
        $enqueue_data['translations']['confirm_restore_default_content'] = __('Are you sure you want to restore default content? All your changes will be deleted.','haet_mail');

        return $enqueue_data;
    }




    public function enqueue_js_placeholder_menu( $enqueue_data ){
        if( !array_key_exists( 'text', $enqueue_data['placeholder_menu'] ) )
            $enqueue_data['placeholder_menu']['text'] = array();

        global $post;
        $email_name = get_the_title( $post );

        if( $this->is_order_mail( $email_name ) ){
            $checkout_fields = $this->get_wc_checkout_fields();
            $billing_fields = array();
            foreach ($checkout_fields['billing'] as $key => $field) {
                if( isset( $field['custom'] ) && $field['custom'] == 1 ) // custom fields are automatically prefixed with field group
                    $key = 'billing_'.$key;
                $billing_fields[] = array(
                    'text'      => ( isset( $field['label'] ) && $field['label']!= "" ? $field['label'] : '['. strtoupper($key) .']' ),
                    'tooltip'   => '['. strtoupper($key) .']',
                );
            }

            $shipping_fields = array();
            foreach ($checkout_fields['shipping'] as $key => $field) {
                if( isset( $field['custom'] ) && $field['custom'] == 1 ) // custom fields are automatically prefixed with field group
                    $key = 'shipping_'.$key;
                $shipping_fields[] = array(
                    'text'      => ( isset( $field['label'] ) && $field['label']!= "" ? $field['label'] : '['. strtoupper($key) .']' ),
                    'tooltip'   => '['. strtoupper($key) .']',
                );
            }

            $order_fields = array(
                        array(
                            'text'      => __('Order Number','haet_mail'),
                            'tooltip'   => '[ORDER_NUMBER]',
                        ),
                        array(
                            'text'      => __('Order Date','haet_mail'),
                            'tooltip'   => '[ORDER_DATE]',
                        ),
                        array(
                            'text'      => __('Edit Order URL','haet_mail'),
                            'tooltip'   => '[EDIT_ORDER_URL]',
                        ),
                        array(
                            'text'      => __('Payment URL','haet_mail'),
                            'tooltip'   => '[PAYMENT_URL]',
                        ),
                        array(
                            'text'      => __('Order Meta','haet_mail'),
                            'tooltip'   => '[ORDER_META]',
                        ),
                        array(
                            'text'      => __('Customer note','woocommerce'),
                            'tooltip'   => '[CUSTOMER_NOTE]',
                        ),
                        array(
                            'text'      => __('Payment Instructions','haet_mail'),
                            'tooltip'   => '[PAYMENT_INSTRUCTIONS]',
                        ),
                        array(
                            'text'      => str_replace( ':', '', __('Payment method:','woocommerce') ), //we use this string beacuse it is already translated by WooCommerce
                            'tooltip'   => '[PAYMENT_METHOD]',
                        ),
                        array(
                            'text'      => __('Order total','woocommerce'),
                            'tooltip'   => '[ORDER_TOTAL]',
                        ),
                        array(
                            'text'      => __('Order total','woocommerce') . ' ' . __('numeric','haet_mail'),
                            'tooltip'   => '[ORDER_TOTAL_NUMERIC]',
                        ),
                        array(
                            'text'      => __('Subtotal','woocommerce'),
                            'tooltip'   => '[ORDER_SUBTOTAL]',
                        ),
                        array(
                            'text'      => __('Subtotal','woocommerce') . ' ' . __('numeric','haet_mail'),
                            'tooltip'   => '[ORDER_SUBTOTAL_NUMERIC]',
                        ),
                        array(
                            'text'      => __('Shipping','woocommerce'),
                            'tooltip'   => '[ORDER_SHIPPING]',
                        ),
                        array(
                            'text'      => __('Shipping','woocommerce') . ' ' . __('numeric','haet_mail'),
                            'tooltip'   => '[ORDER_SHIPPING_NUMERIC]',
                        ),
                        array(
                            'text'      => __('Discount','woocommerce'),
                            'tooltip'   => '[ORDER_DISCOUNT]',
                        ),
                        array(
                            'text'      => __('Discount','woocommerce') . ' ' . __('numeric','haet_mail'),
                            'tooltip'   => '[ORDER_DISCOUNT_NUMERIC]',
                        ),
                        array(
                            'text'      => __('Order notes','woocommerce'),
                            'tooltip'   => '[ORDER_NOTES]',
                        )
        
                    );
            foreach ($checkout_fields['order'] as $key => $field) {
                if( $key != 'order_comments' ){
                    if( isset( $field['custom'] ) && $field['custom'] == 1 )
                        $key = 'order_custom_'.$key;
                    $order_fields[] = array(
                        'text'      => ( isset( $field['label'] ) && $field['label']!= "" ? $field['label'] : '['. strtoupper($key) .']' ),
                        'tooltip'   => '['. strtoupper($key) .']',
                    );
                }
            }

            if( class_exists('WP_WC_Running_Invoice_Number_Functions') ){
                $order_fields[] = array(
                        'text'      => __('Invoice Number','haet_mail'),
                        'tooltip'   => '[INVOICE_NUMBER]',
                    );
            }


            $enqueue_data['placeholder_menu']['text'] = apply_filters( 'haet_mail_placeholder_menu', array(
                array(
                    'text'      => __('Order','woocommerce'),
                    'menu'      => $order_fields
                ),
                array(
                    'text'      => __('Customer','woocommerce'),
                    'menu'      => array(
                        array(
                            'text'      => __('Billing full name','haet_mail'),
                            'tooltip'   => '[BILLING_FULL_NAME]',
                        ),
                        array(
                            'text'      => __('Customer details','woocommerce'),
                            'tooltip'   => '[CUSTOMER_DETAILS]',
                        ),
                        array(
                            'text'      => __('Billing address','woocommerce'),
                            'tooltip'   => '[BILLING_ADDRESS]',
                        ),
                        array(
                            'text'      => __('Billing fields','haet_mail'),
                            'menu'      => $billing_fields
                        ),
                        array(
                            'text'      => __('Shipping address','woocommerce'),
                            'tooltip'   => '[SHIPPING_ADDRESS]',
                        ),
                        array(
                            'text'      => __('Shipping fields','haet_mail'),
                            'menu'      => $shipping_fields
                        ),
                    )
                ),
                array(
                    'text'      => __('General','woocommerce'),
                    'menu'      => array(
                        array(
                            'text'      => __('Website name','haet_mail'),
                            'tooltip'   => '[WEBSITE_NAME]',
                        ),
                    )
                ),
                array(
                    'text'      => __('Profile','woocommerce'),
                    'menu'      => array(
                        array(
                            'text'      => __('Username','woocommerce'),
                            'tooltip'   => '[USERNAME]',
                        ),
                        array(
                            'text'      => __('New Password','haet_mail'),
                            'tooltip'   => '[NEW_PASSWORD]',
                        ),
                        array(
                            'text'      => __('My account URL','haet_mail'),
                            'tooltip'   => '[MY_ACCOUNT_URL]',
                        ),
                        array(
                            'text'      => __('Reset password URL','haet_mail'),
                            'tooltip'   => '[RESET_PASSWORD_URL]',
                        ),
                    )
                )
            ) );
        }
        
        if( $this->is_account_mail( $email_name ) ){
            $enqueue_data['placeholder_menu']['text'] = apply_filters( 'haet_mail_placeholder_menu', array(
                array(
                    'text'      => __('General','woocommerce'),
                    'menu'      => array(
                        array(
                            'text'      => __('Website name','haet_mail'),
                            'tooltip'   => '[WEBSITE_NAME]',
                        ),
                    )
                ),
                array(
                    'text'      => __('Profile','woocommerce'),
                    'menu'      => array(
                        array(
                            'text'      => __('Username','woocommerce'),
                            'tooltip'   => '[USERNAME]',
                        ),
                        array(
                            'text'      => __('New Password','haet_mail'),
                            'tooltip'   => '[NEW_PASSWORD]',
                        ),
                        array(
                            'text'      => __('My account URL','haet_mail'),
                            'tooltip'   => '[MY_ACCOUNT_URL]',
                        ),
                        array(
                            'text'      => __('Reset password URL','haet_mail'),
                            'tooltip'   => '[RESET_PASSWORD_URL]',
                        ),
                    )
                )
            ) );
        }


        return $enqueue_data;
    }





    /**
     * Get size information for all currently-registered image sizes.
     *
     * @global $_wp_additional_image_sizes
     * @uses   get_intermediate_image_sizes()
     * @return array $sizes Data for all currently-registered image sizes.
     */
    public function get_image_sizes() {
        global $_wp_additional_image_sizes;

        $sizes = array();

        foreach ( get_intermediate_image_sizes() as $_size ) {
            if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
                $sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
                $sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
                $sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
            } elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
                $sizes[ $_size ] = array(
                    'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
                    'height' => $_wp_additional_image_sizes[ $_size ]['height'],
                    'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
                );
            }
        }

        return $sizes;
    }





    public function enqueue_js_placeholder_values( $enqueue_data ){
        if( !array_key_exists( 'productstable', $enqueue_data['placeholders'] ) )
            $enqueue_data['placeholders']['productstable'] = array();
        $demo_order = WPHTMLMail_Woocommerce()->get_demo_order();
        $enqueue_data['placeholders']['text'] = $demo_order;
        return $enqueue_data;
    }





    public function setup_meta_boxes(){
        add_meta_box ( 'mb_restore_defaults_metabox', __( 'Restore default email content', 'haet_mail' ), array($this,'print_restore_defaults_button'), 'wphtmlmail_mail', 'side', 'default' );
        add_meta_box ( 'mb_back_to_overview_metabox', __( 'Back to Email settings', 'haet_mail' ), array($this,'print_back_to_overview_button'), 'wphtmlmail_mail', 'side', 'default' );
    }


    public function print_restore_defaults_button(){
        ?>
        <input type="hidden" name="mb_restore_defaults" id="mb_restore_defaults" value="0">
        <a href="#" class="mb-restore-defaults button">
            <span class="dashicons dashicons-image-rotate"></span>
            <?php _e('Restore default template','haet_mail'); ?>
        </a>

        <?php
    }




    public function print_back_to_overview_button(){
        $overview_url = get_admin_url(null,'options-general.php?page=wp-html-mail&tab=woocommerce'); 
        if( !current_user_can( 'manage_options' ) ) // Email settings could be shown outside "Settings" menu item
            $overview_url = get_admin_url(null,'admin.php?page=wp-html-mail?tab=woocommerce');  ?>
        <a href="<?php echo $overview_url; ?>" class="mb-back-to-overview button">
            <span class="dashicons dashicons-arrow-left-alt2"></span>
            <?php _e('Back to overview','haet_mail'); ?>
        </a>

        <?php
    }




    public function mailbuilder_header_buttons( $post ){
        $this->print_back_to_overview_button();
        $this->print_restore_defaults_button();
    }




    public static function get_demo_order(  ){
        $plugin_options = get_option('haet_mail_plugin_options');

        $wc_order = FALSE;

        if( isset( $plugin_options['woocommerce'] ) && isset( $plugin_options['woocommerce']['preview_order'] ) ){
            $order_id = $plugin_options['woocommerce']['preview_order'];
            $order_status = get_post_status( $order_id );
            if( FALSE !== $order_status && 'trash' != $order_status )
                $wc_order = wc_get_order( $order_id );
        }

        // if order desn't exist anymore or doesn't contain products
        if( FALSE === $wc_order || !count( $wc_order->get_items() ) ){
            // get latest order id
            $args = array (
                'post_type'              => 'shop_order',
                'posts_per_page'         => '10',
                'order'                  => 'DESC',
                'orderby'                => 'ID',
                'post_status'            => 'any',
                'fields'                 => 'ids'
            );

            // look for a non-empty order
            $query = new WP_Query( $args );
            while ( $query->have_posts() ) {
                $wc_order = wc_get_order( $query->posts[0] );
                if( count( $wc_order->get_items() ) > 0 )
                    break;
            }
        }




        // try to find an order with refunds
        if( $plugin_options['woocommerce']['preview_mail'] == 'WC_Email_Customer_Refunded_Order' && !$wc_order->get_refunds() ){
            $query_args = array(
                'fields'         => 'id=>parent',
                'post_type'      => 'shop_order_refund',
                'post_status'    => 'any',
                'posts_per_page' => 1,
            );

            $refunded_orders = array_unique( get_posts( $query_args ) );
            $refunded_orders = array_values( $refunded_orders );
            if( count( $refunded_orders ) > 0 )
                $wc_order = wc_get_order( $refunded_orders[0] ); 
        }

        if( !$wc_order )
            return array();
        
        $totals = $wc_order->get_order_item_totals();
        $totals_indexed_array = array();
        foreach ( $totals as $total ) {
            $totals_indexed_array[] = $total;
        }
        $wc_emails = WC_Emails::instance()->get_emails();
        global $post;
        $email_name = get_the_title( $post );

        $current_user = wp_get_current_user();

        $sent_to_admin = ( strpos($email_name, 'Customer') === false );

        if( $email_name == 'WGM_Email_Confirm_Order' ){
            $wc_email = new WGM_Email_Confirm_Order();
            $sent_to_admin = false;
        }else
            $wc_email = $wc_emails[$email_name];

        $order = WPHTMLMail_Woocommerce()->get_placeholders_text( 
                array(
                    'wc_order' => $wc_order, 
                    'wc_email' => $wc_email, 
                    'wc_sent_to_admin' => $sent_to_admin,
                    'user_login' => $current_user->user_login,
                    'user_pass' => 'XXXXXXXXXXXX'
                )
            );

        if( $email_name == 'WGM_Email_Double_Opt_In_Customer_Registration' ){
            $order['activation_url'] = get_permalink( get_option('woocommerce_myaccount_page_id') ) . '?account-activation=XXXXXXXXXXXXXXXX';            
        }

        $order['items'] = WPHTMLMail_Woocommerce()->get_placeholders_productstable( array( 'wc_order' => $wc_order ) );
        $order['totals'] = $totals_indexed_array;
        $order['customer_note'] = '<p>This is a demo note</p><p>Only use this placeholder in "Customer Note" email template</p>';
        $order['order_notes'] = 'This is a optional note a customer could have entered during checkout';

        return $order;
    }





    private function get_wc_checkout_fields(){
        return WC_Checkout::instance()->checkout_fields;
    }



    public function is_account_mail( $email_name ){
        return in_array( $email_name, array(
                'WC_Email_Customer_New_Account',
                'WC_Email_Customer_Reset_Password',
                'WGM_Email_Double_Opt_In_Customer_Registration'
            ) );
    }


    public function is_order_mail( $email_name ){
        return !$this->is_account_mail( $email_name );
    }


    private function get_placeholders_text( $settings ){
        $wc_email = $settings['wc_email']; 

        if( isset( $settings['wc_email'] ) )
            $email_name = get_class( $settings['wc_email'] );
        else 
            $email_name = '';

        $plain_text = false;
        if( isset( $settings['plain_text'] ) && $settings['plain_text'] == true )
            $plain_text = true;

        

        $reset_password_url = '';
        if( isset( $settings['reset_key'] ) &&  isset( $settings['user_login'] ) )
            $reset_password_url = esc_url( add_query_arg( array( 'key' => $settings['reset_key'], 'login' => rawurlencode( $settings['user_login'] ) ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) );

        $order = array(
                'website_name'      =>  get_option( 'blogname' ),
                'username'          =>  ( isset( $settings['user_login'] ) ? $settings['user_login'] : '' ),
                'new_password'      =>  ( isset( $settings['user_pass'] ) ? $settings['user_pass'] : '' ),
                'my_account_url'    =>  wc_get_page_permalink( 'myaccount' ),
                'reset_password_url'=>  $reset_password_url,
                'activation_url'   => ( isset( $settings['activation_url'] ) ? $settings['activation_url'] : '' ), // WGM Account DOI
            );

        if( isset( $settings['wc_order'] ) && is_a( $settings['wc_order'], 'WC_Abstract_Order' ) ){
            $wc_order = $settings['wc_order'];
            
            ob_start();
            do_action( 'woocommerce_email_order_meta', $wc_order, $settings['wc_sent_to_admin'], false, $wc_email );
            $order_meta = ob_get_clean();

            ob_start();
            // remove customer details and add our own 
            remove_action( 'woocommerce_email_customer_details', array( WC_Emails::instance(), 'email_addresses' ), 20, 3 );
            remove_action( 'woocommerce_email_customer_details', array( WC_Emails::instance(), 'customer_details' ), 10, 3 );
            add_action( 'woocommerce_email_customer_details', array( $this, 'print_customer_details' ), 10, 3 );
            do_action( 'woocommerce_email_customer_details', $wc_order, $settings['wc_sent_to_admin'], false, $wc_email );
            $customer_details = ob_get_clean();

            ob_start();
            do_action( 'woocommerce_email_before_order_table', $wc_order, $settings['wc_sent_to_admin'], false, $wc_email );
            $payment_instructions = ob_get_clean();




            $order['id']                =   $wc_order->get_id();
            $order['order_number']      =   $wc_order->get_order_number();
            $order['order_date']        =   sprintf( '<time datetime="%s">%s</time>', date_i18n( 'c', strtotime( $wc_order->get_date_created() ) ), date_i18n( wc_date_format(), strtotime( $wc_order->get_date_created() ) ) );
            $order['edit_order_url']    =   esc_url( admin_url( 'post.php?post=' . $wc_order->get_id() . '&action=edit' ) );
            $order['order_meta']        =   $order_meta;
            $order['customer_note']     =   ( isset( $settings['customer_note'] ) ? wpautop( wptexturize( $settings['customer_note'] ) ) : '' );
            $order['payment_url']       =   esc_url( $wc_order->get_checkout_payment_url() );

            $order['billing_full_name'] =   $wc_order->get_formatted_billing_full_name();
            $order['customer_details']  =   $customer_details;
            $order['billing_address']   =   $wc_order->get_formatted_billing_address();

            $order['shipping_address']  =   $wc_order->get_formatted_shipping_address();

            $order['payment_method']    =   $wc_order->get_payment_method_title();
            $order['payment_instructions'] =   $payment_instructions; // the action woocommerce_email_before_order_table is primary used for payment instructions

            $order['order_total']           =  $wc_order->get_formatted_order_total();
            $order['order_total_numeric']   =  round( $wc_order->get_total(), 2 );

            $order['order_subtotal']        =  $wc_order->get_subtotal_to_display();
            $order['order_subtotal_numeric']=  round( $wc_order->get_subtotal(), 2 );

            $order['order_shipping']        =  $wc_order->get_shipping_to_display();
            $order['order_shipping_numeric']=  round( $wc_order->get_shipping_total(), 2 );

            $order['order_discount']        =  $wc_order->get_discount_to_display();
            $order['order_discount_numeric']=  round( $wc_order->get_discount_total(), 2 );

            $order['order_notes']           =  nl2br( $wc_order->get_customer_note() );

            if( class_exists('WP_WC_Running_Invoice_Number_Functions') && get_option( 'wp_wc_running_invoice_email_activation', 'on' ) == 'on' ){
                $running_invoice_number = new WP_WC_Running_Invoice_Number_Functions( $wc_order );
                $order['invoice_number']      =   $running_invoice_number->get_invoice_number();
            }

            
            $checkout_fields = $this->get_wc_checkout_fields();

            foreach ($checkout_fields['billing'] as $key => $field) {
                if( isset( $field['custom'] ) && $field['custom'] == 1 ) // custom fields are automatically prefixed with field group
                    $order['billing_'.$key] = get_post_meta($wc_order->get_id(), $key, true);
                else
                    $order[$key] = get_post_meta($wc_order->get_id(), '_'.$key, true);
            }
            foreach ($checkout_fields['shipping'] as $key => $field) {
                if( isset( $field['custom'] ) && $field['custom'] == 1 ) // custom fields are automatically prefixed with field group
                    $order['shipping_'.$key] = get_post_meta($wc_order->get_id(), $key, true);
                else
                    $order[$key] = get_post_meta($wc_order->get_id(), '_'.$key, true);
            }
            foreach ($checkout_fields['order'] as $key => $field) {
                if( isset( $field['custom'] ) && $field['custom'] == 1 ) // custom fields are automatically prefixed with field group
                    $order['order_custom_'.$key] = get_post_meta($wc_order->get_id(), $key, true);
                else
                    $order[$key] = get_post_meta($wc_order->get_id(), '_'.$key, true);
            }

            if( $plain_text )
                $order['order_date'] = date_i18n( wc_date_format(), strtotime( $wc_order->get_date_created() ) );
        }elseif( isset( $settings['wc_email'] ) && $this->is_account_mail( $email_name ) ){
            
            if( is_a( $settings['wc_email']->object, 'WP_User' ) ){ // password reset & new account mails
                $user = $settings['wc_email']->object;
            }elseif( isset( $settings['user_login'] ) ){ // WGM Double Opt In
                $user = get_user_by( 'login', $settings['user_login'] );
            }

            if( $user ){
                $first_name = get_usermeta( $user->ID, 'billing_first_name' );
                $last_name = get_usermeta( $user->ID, 'billing_last_name' );
                if( !$first_name && !$last_name ){
                    $first_name = get_usermeta( $user->ID, 'first_name' );
                    $last_name = get_usermeta( $user->ID, 'last_name' );
                }
                // we use this from WooCommerce get_formatted_billing_full_name() to allow localization of display order
                $order['billing_full_name'] = sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $first_name, $last_name );
                $order['billing_first_name'] = $first_name;
                $order['billing_last_name'] = $last_name;
                $order['billing_company'] = get_usermeta( $user->ID, 'billing_company' );
                $order['billing_email'] = get_usermeta( $user->ID, 'billing_email' );
            }
        }

        $order['__debug__'] = 'E-Mail Name: ' . $email_name . '<br>'
                                . 'is_account_mail? ' . ( $this->is_account_mail( $email_name ) ? 'yes' : 'no' ) . '<br>'
                                . 'userid: ' . $user->ID;
                                
        $order = apply_filters( 'haet_mail_order_placeholders', $order, ( isset( $wc_order ) ? $wc_order : false ), $settings );
        

        return $order;
    }





    /**
     * print customer details
     * the original template prints too much formatting so we create our own here
     */
    public function print_customer_details( $order, $sent_to_admin = false, $plain_text = false ) {
        $fields = array();

        if ( $order->get_customer_note() ) {
            $fields['customer_note'] = array(
                'label' => __( 'Note', 'woocommerce' ),
                'value' => ( $order->get_customer_note()  ? wpautop( wptexturize( $order->get_customer_note() ) ) : '' )
            );
        }
        
        if ( $order->get_billing_email() ) {
            $fields['billing_email'] = array(
                'label' => __( 'Email', 'woocommerce' ),
                'value' => wptexturize( $order->get_billing_email() )
            );
        }

        if ( $order->get_billing_phone() ) {
            $fields['billing_phone'] = array(
                'label' => __( 'Tel', 'woocommerce' ),
                'value' => wptexturize( $order->get_billing_phone() )
            );
        }

        $fields = array_filter( apply_filters( 'woocommerce_email_customer_details_fields', $fields, $sent_to_admin, $order ), array( WC_Emails::instance(), 'customer_detail_field_is_valid' ) );

        ?>
        <?php foreach ( $fields as $field ) : ?>
            <strong><?php echo wp_kses_post( $field['label'] ); ?>:</strong> <span class="text"><?php echo wp_kses_post( $field['value'] ); ?></span><br>
        <?php endforeach; ?>
        <?php
    }

    



    public function get_placeholders_productstable( $settings ){
        $wc_order = $settings['wc_order']; 

        $wc_items = $wc_order->get_items();

        $image_placeholder_sizes = array();
        $image_sizes = WPHTMLMail_Woocommerce()->get_image_sizes();
        foreach ($image_sizes as $name => $dimensions) {
            if( $dimensions['width']>0 
                && $dimensions['width']<500
                && $dimensions['height']>0
                && $dimensions['height']<500
                ){
                $image_size_placeholder_name = 'photo_'.strtolower( preg_replace('/[^\da-z]/i', '', $name) );
                $image_placeholder_sizes[$image_size_placeholder_name] = $name;
            }
        }
        
        $items = array();

        foreach ( $wc_items as $item_id => $item ) {
            $_product     = apply_filters( 'woocommerce_order_item_product', $wc_order->get_product_from_item( $item ), $item );

            $i = 0;
            if ( $_product && apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
                $i = count( $items );
                foreach ( $image_placeholder_sizes as $image_size_placeholder_name => $image_size_name) {
                    $items[ $i ][$image_size_placeholder_name] = '<img src="' . ( $_product->get_image_id() ? current( wp_get_attachment_image_src( $_product->get_image_id(), $image_size_name) ) : wc_placeholder_img_src() ) . '">';
                }

                $items[ $i ]['productname'] = apply_filters( 'woocommerce_order_item_name', $item['name'], $item, false );

                $items[ $i ]['product_link'] = get_permalink( $_product->get_id() );

                $items[ $i ]['sku'] = $_product->get_sku();

                ob_start();
                // allow other plugins to add additional product information here
                do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $wc_order );

                // Variation
                wc_display_item_meta( $item, array( 
                        'before'    => '',
                        'after'     => '',
                        'separator' => '<br/>',
                        'echo'      => true,
                        'autop'     => false
                    ) );

                // File URLs
                wc_display_item_downloads( $item );
                // allow other plugins to add additional product information here
                do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $wc_order );
                $items[ $i ]['itemmeta'] = '<div class="variation">' . strip_tags( ob_get_clean(), '<br><br/>' ) . '</div>';
                        
                $items[ $i ]['quantity'] = $item->get_quantity();
                
                /********** Customers are not redirected to paypal any more with these lines active  removed in 2.3 *****/
                // if(is_plugin_active( 'woocommerce-german-market/WooCommerce-German-Market.php' ))
                //     WC()->cart = new WC_Cart();
                
                $items[ $i ]['tax']                     = wc_price( $item->get_total_tax(), array( 'currency' => $wc_order->get_currency() ) );

                $line_subtotal = $wc_order->get_line_subtotal( $item, false );
                if( $line_subtotal > 0 )
                    $tax_rate = round( ( $item->get_total_tax() / $line_subtotal ), 2 ) * 100;
                else
                    $tax_rate = 0;
                $items[ $i ]['tax_rate']                = ( $tax_rate ? $tax_rate : '0' ) . '%';

                $items[ $i ]['price']                   = $wc_order->get_formatted_line_subtotal( $item ); 
                $items[ $i ]['price_single']            = wc_price( $wc_order->get_line_subtotal( $item, ( get_option( 'woocommerce_tax_display_cart' ) == 'incl') ) / $items[ $i ]['quantity'] , array( 'currency' => $wc_order->get_currency() ) );

                $wc_product     = apply_filters( 'woocommerce_order_item_product', $wc_order->get_product_from_item( $item ), $item );
                $items[ $i ]['purchase_note'] = wpautop( do_shortcode( wp_kses_post( get_post_meta( $wc_product->get_id(), '_purchase_note', true ) ) ) );

                $items[ $i ] = apply_filters( 'haet_mail_order_placeholders_products_table', $items[$i], $item, $_product, $wc_order );
            }
        }
        return $items;
    }
    




    public function save_post( $post_id ){
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;
            
        if ( ! isset( $_POST[ 'mailbuilder_nonce' ] ) ||
            ! wp_verify_nonce( $_POST[ 'mailbuilder_nonce' ], 'save_mailbuilder' ) )
            return;
        
        if ( ! current_user_can( 'edit_posts' ) )
            return;

        if( isset( $_POST['mb_restore_defaults'] ) && $_POST['mb_restore_defaults'] )
            $this->set_email_default_content( $post_id, get_the_title( $post_id ) );
    }





    public function fill_placeholders_text($html, $element_content, $settings){
        if( array_key_exists('wc_email', $settings) ){
            $order = $this->get_placeholders_text( $settings );
            //Payment instructions contain block elements (h2, h3 & p) so we convert the wrapping <p> tag to a <div>
            $html = preg_replace_callback(
               "/<p([^>]*)>([^<]*)(\[PAYMENT\_INSTRUCTIONS\])([^<]*)<\/p>/i",
                function ( $placeholder ) use ( $order ) {
                    if( array_key_exists( 'payment_instructions', $order) )
                        $placeholder_value = '<div '.$placeholder[1].'>'.$placeholder[2].$order['payment_instructions'].$placeholder[4].'</div>';
                    return $placeholder_value;
                },
                $html
              );

            //wrap customer note in a "<blockquote>"
            $html = preg_replace_callback(
               "/<p([^>]*)>([^<]*)(\[CUSTOMER\_NOTE\])([^<]*)<\/p>/i",
                function ( $placeholder ) use ( $order ) {
                    if( array_key_exists( 'customer_note', $order) )
                        $placeholder_value = '<blockquote '.$placeholder[1].'>'.$placeholder[2].$order['customer_note'].$placeholder[4].'</blockquote>';
                    return $placeholder_value;
                },
                $html
              );

            $html = preg_replace_callback(
               "/\[([a-z0-9\_]*)\]/i",
                function ( $placeholder ) use ( $order ) {
                    if( array_key_exists( strtolower($placeholder[1]), $order) )
                        $placeholder_value = $order[strtolower($placeholder[1])];
                    else
                        $placeholder_value = $placeholder[0];
                    return $placeholder_value;
                },
                $html
              );
        }
        return $html;
    }


    public function wgm_wrap_delivery_time( $return, $item_name, $item ){
        // replace last occurrence of "(" and ")" by "<span class="delivery-time">(" ... ")</span>"
        
        $start_pos = strrpos( $return, '(' );
        if($start_pos !== false){
            $return = substr_replace($return, '<span class="delivery-time">(', $start_pos, 1);
        }

        $end_pos = strrpos( $return, ')' );
        if($end_pos !== false){
            $return = substr_replace($return, ')</span>', $end_pos, 1);
        }
        return $return;
    }


    /**
     * Show action links on the plugin screen
     */
    public function plugin_action_links( $links ) {
        return array_merge( array(
            '<a href="' . get_admin_url(null,'options-general.php?page=wp-html-mail&tab=woocommerce') . '">' . __( 'Settings' ) . '</a>'
        ), $links );
    }




    public function add_link_to_wc_email_settings( $settings ){
        $settings[] = array( 'title' => __( 'WP HTML Mail for WooCommerce', 'haet_mail' ), 'type' => 'title', 'desc' => '<a href="' . get_admin_url(null,'options-general.php?page=wp-html-mail&tab=woocommerce') . '">' . __( 'Customize your email design and content. ', 'haet_mail' ) . '</a>', 'id' => 'email_wp_html_mail' );

        return $settings;
    }



    public function show_reset_buttons(){
        ?>
        <a href="<?php echo add_query_arg( 'advanced-action', 'delete-woo-preview' ); ?>" class="button-secondary">
            <?php _e('Reset WooCommerce preview', 'haet_mail'); ?>
        </a>
        <?php
    }


    public function process_reset_actions(){
        if( array_key_exists( 'advanced-action', $_GET ) ){
            switch ($_GET['advanced-action']) {
                case 'delete-woo-preview':
                    $plugin_options = get_option('haet_mail_plugin_options');
                    unset( $plugin_options['woocommerce']['preview_mail'] );     
                    unset( $plugin_options['woocommerce']['preview_order'] );       
                    update_option('haet_mail_plugin_options', $plugin_options);
                    echo '<div class="updated"><p><strong>';
                            _e('Settings updated.', 'wp-html-mail');
                    echo '</strong></p></div>'; 
                    break;

                case 'delete-all':
                    $emails = $this->get_supported_woocommerce_emails();
                    foreach( $emails AS $email_key => $email_name ){
                        $email_id = Haet_Mail_Builder()->get_email_post_id( $email_name );
                        if( $email_id ){
                            wp_delete_post( $email_id, true );
                        }
                    }
                    break;
            }
        }
    }
}



function WPHTMLMail_Woocommerce()
{
    return WPHTMLMail_Woocommerce::instance();
}

WPHTMLMail_Woocommerce();