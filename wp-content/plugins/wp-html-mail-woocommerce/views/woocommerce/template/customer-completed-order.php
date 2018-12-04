<?php
/**
 * Customer Completed order email
 * @version     2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

do_action( 'woocommerce_email_header', $email_heading, $email );

$plugin_options = get_option('haet_mail_plugin_options');
if( isset($plugin_options['woocommerce']['edit_mode']) && $plugin_options['woocommerce']['edit_mode'] == 'mailbuilder'): 

	$settings = array( 
			'wc_order' => $order, 
			'wc_email' => $email, 
			'wc_sent_to_admin' => false 
		);
	Haet_Mail_Builder()->print_email('WC_Email_Customer_Completed_Order', $settings);

else: //default content ?>
	<h1><?php _e($email_heading,'woocommerce'); ?></h1>
	<p><?php printf( __( "Hi there. Your recent order on %s has been completed. Your order details are shown below for your reference:", 'woocommerce' ), get_option( 'blogname' ) ); ?></p>

	<?php
	do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

	do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

	do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
	
endif;

do_action( 'woocommerce_email_footer' ); ?>
