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

?>

<?php 
do_action( 'woocommerce_email_header', $email_heading, $email );

$plugin_options = get_option('haet_mail_plugin_options');
if( isset($plugin_options['woocommerce']['edit_mode']) && $plugin_options['woocommerce']['edit_mode'] == 'mailbuilder'): 

    $settings = array( 
    		'user_login' => $user_login,
            'activation_url' => $activation_link, 
            'wc_email' => $email, 
            'wc_sent_to_admin' => false 
        );
    Haet_Mail_Builder()->print_email('WGM_Email_Double_Opt_In_Customer_Registration', $settings);

    //Subject
    add_filter( 
        'woocommerce_email_subject_double_opt_in_customer_registration', 
        function( $subject, $order ) use ( $settings ){
            $email_id = Haet_Mail_Builder()->get_email_post_id( 'WGM_Email_Double_Opt_In_Customer_Registration' );
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
	
	<p>
		<?php echo sprintf( __( 'Thanks for creating a customer account on %s. Your username is %s. Please follow the activation link to activate your account:', 'woocommerce-german-market' ), esc_html ( get_bloginfo( 'name' ) ), '<strong>' . esc_html( $user_login ) . '</strong>' ); ?>
	</p>

	<p>
		<a href="<?php echo $activation_link; ?>"><?php echo $activation_link; ?></a>
	</p>

	<p>
		<?php echo sprintf( __( 'If you haven\'t created an account on %s please ignore this email.', 'woocommerce-german-market' ), esc_html( get_bloginfo( 'name' ) ) );?>
	</p>

	<?php 
endif;

do_action( 'woocommerce_email_footer', $email ); ?>
