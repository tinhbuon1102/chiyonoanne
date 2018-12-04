<?php
/**
  * Customer new account email
  * @version     2.0.0
  */

if ( ! defined( 'ABSPATH' ) ) { exit; }

do_action( 'woocommerce_email_header', $email_heading, $email );

$plugin_options = get_option('haet_mail_plugin_options');
if( isset($plugin_options['woocommerce']['edit_mode']) && $plugin_options['woocommerce']['edit_mode'] == 'mailbuilder'): 

    $settings = array( 
            'wc_email' => $email, 
            'wc_sent_to_admin' => false,
            'user_login' => $user_login,
            'reset_key' => $reset_key
        );
    Haet_Mail_Builder()->print_email('WC_Email_Customer_Reset_Password', $settings);

else: //default content ?>
    <h1><?php _e($email_heading,'woocommerce'); ?></h1>
    <p><?php _e( 'Someone requested that the password be reset for the following account:', 'woocommerce' ); ?></p>
    <p><?php printf( __( 'Username: %s', 'woocommerce' ), $user_login ); ?></p>
    <p><?php _e( 'If this was a mistake, just ignore this email and nothing will happen.', 'woocommerce' ); ?></p>
    <p><?php _e( 'To reset your password, visit the following address:', 'woocommerce' ); ?></p>
    <p>
        <a class="link" href="<?php echo esc_url( add_query_arg( array( 'key' => $reset_key, 'login' => rawurlencode( $user_login ) ), wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) ) ) ); ?>">
                <?php _e( 'Click here to reset your password', 'woocommerce' ); ?></a>
    </p>
    <p></p>
    <?php
endif;

do_action( 'woocommerce_email_footer' ); ?>