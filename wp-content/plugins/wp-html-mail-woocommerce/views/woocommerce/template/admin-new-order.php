<?php
/**
 * Admin new order email
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

do_action( 'woocommerce_email_header', $email_heading, $email );

$plugin_options = get_option('haet_mail_plugin_options');
if( isset($plugin_options['woocommerce']['edit_mode']) && $plugin_options['woocommerce']['edit_mode'] == 'mailbuilder'): 

	$settings = array( 
			'wc_order' => $order, 
			'wc_email' => $email, 
			'wc_sent_to_admin' => true 
		);
	Haet_Mail_Builder()->print_email('WC_Email_New_Order', $settings);

else: //default content ?>
	<h1><?php _e($email_heading,'woocommerce'); ?></h1>
	<p><?php printf( __( 'You have received an order from %s. The order is as follows:', 'woocommerce' ), $order->get_formatted_billing_full_name() ); ?></p>

	<?php
	do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

	do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

	do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

endif;

do_action( 'woocommerce_email_footer' ); ?>
