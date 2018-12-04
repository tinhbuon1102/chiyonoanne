<?php
/**
  * Customer new account email
  * @version     1.6.4
  */

if ( ! defined( 'ABSPATH' ) ) { exit; }

do_action( 'woocommerce_email_header', $email_heading, $email );

$plugin_options = get_option('haet_mail_plugin_options');
if( isset($plugin_options['woocommerce']['edit_mode']) && $plugin_options['woocommerce']['edit_mode'] == 'mailbuilder'): 

    $settings = array( 
            'wc_email' => $email, 
            'wc_sent_to_admin' => false,
            'user_login' => $user_login,
            'user_pass' => $user_pass
        );
    Haet_Mail_Builder()->print_email('WC_Email_Customer_New_Account', $settings);

else: //default content ?>
    <h1><?php _e($email_heading,'woocommerce'); ?></h1>
    <p><?php printf( __( 'Thanks for creating an account on %1$s. Your username is %2$s', 'woocommerce' ), esc_html( $blogname ), '<strong>' . esc_html( $user_login ) . '</strong>' ); ?></p>

    <?php if ( 'yes' === get_option( 'woocommerce_registration_generate_password' ) && $password_generated ) : ?>

        <p><?php printf( __( 'Your password has been automatically generated: %s', 'woocommerce' ), '<strong>' . esc_html( $user_pass ) . '</strong>' ); ?></p>

    <?php endif; ?>
    <p><?php printf( __( 'You can access your account area to view your orders and change your password here: %s.', 'woocommerce' ), make_clickable( esc_url( wc_get_page_permalink( 'myaccount' ) ) ) ); ?></p>
    <?php
endif;

do_action( 'woocommerce_email_footer' ); ?>