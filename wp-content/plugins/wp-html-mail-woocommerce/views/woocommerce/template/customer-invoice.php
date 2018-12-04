<?php
/**
  * Customer invoice email
  * @version     3.3.0
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
 	Haet_Mail_Builder()->print_email('WC_Email_Customer_Invoice', $settings);

else: //default content ?>
	<h1><?php echo str_replace('{order_number}', $order->get_order_number(), __($email_heading,'woocommerce')); ?></h1>
 	<?php if ( $order->has_status( 'pending' ) ) : ?>
        <p>
        <?php
        printf(
            wp_kses(
                /* translators: %1s item is the name of the site, %2s is a html link */
                __( 'An order has been created for you on %1$s. %2$s', 'woocommerce' ),
                array(
                    'a' => array(
                        'href' => array(),
                    ),
                )
            ),
            esc_html( get_bloginfo( 'name', 'display' ) ),
            '<a href="' . esc_url( $order->get_checkout_payment_url() ) . '">' . esc_html__( 'Pay for this order', 'woocommerce' ) . '</a>'
        );
        ?>
        </p>
    <?php endif; ?>

 	<?php
 	do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

 	do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

 	do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );
 	
endif;

do_action( 'woocommerce_email_footer' ); ?>