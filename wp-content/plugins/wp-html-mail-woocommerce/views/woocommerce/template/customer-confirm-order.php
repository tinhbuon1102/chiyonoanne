<?php
/**
 * Customer confirmation order email
 *
 * @author      MarketPress
 * @package     WooCommerce_German_Market
 * @version     2.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( 'woocommerce_email_header', $email_heading, $email );

$plugin_options = get_option('haet_mail_plugin_options');
if( isset($plugin_options['woocommerce']['edit_mode']) && $plugin_options['woocommerce']['edit_mode'] == 'mailbuilder'): 

    $settings = array( 
            'wc_order' => $order, 
            'wc_email' => $this, 
            'wc_sent_to_admin' => false 
        );
    Haet_Mail_Builder()->print_email('WGM_Email_Confirm_Order', $settings);

    //Subject
    add_filter( 
        'woocommerce_email_subject_customer_order_confirmation', 
        function( $subject, $order ) use ( $settings ){
            $email_id = Haet_Mail_Builder()->get_email_post_id( 'WGM_Email_Confirm_Order' );
            if( $email_id ){
                $new_subject = get_post_meta( $email_id, 'subject', true );
                if( $new_subject ){
                    $new_subject = WPHTMLMail_Woocommerce()->fill_placeholders_text($new_subject, '', $settings);
                    return strip_tags( $new_subject );
                }
            }
            return $subject;
        }, 10, 2 );

else: //default content ?>
    <h1><?php _e($email_heading,'woocommerce'); ?></h1>
    <p><?php echo apply_filters(
		'wgm_customer_received_order_email_text',
		__( 'With this e-mail we confirm that we have received your order. However, this is not a legally binding offer until payment is received.', 'woocommerce-german-market' )
	); ?></p>

    <?php
    do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

    do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

    do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
    
    if(is_plugin_active( 'woocommerce-german-market/WooCommerce-German-Market.php' ))
        WGM_Email::email_de_footer();
endif;

do_action( 'woocommerce_email_footer' ); 
