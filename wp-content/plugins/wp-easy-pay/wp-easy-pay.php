<?php
/**
 * Plugin Name: WP Easy Pay (Premium)
 * Plugin URI: https://wpexperts.io/products/
 * Description: Easily collect payments for Simple Payment or donations online without coding it yourself or hiring a developer. Skip setting up a complex shopping cart system.
 * Author: Wpexperts
 * Author URI: https://wpexperts.io/
 * Version: 1.8
 * Text Domain: wp-easy-pay
 * License: GPLv2 or later
 * @fs_premium_only /premium-files/
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;


require_once( 'includes/WPSP_freemius.php' );
if( wepp_fs()->is__premium_only() and wepp_fs()->is_plan('wpep_premium', true) OR wepp_fs()->is_plan('wpep_business', true) OR wepp_fs()->is_plan('wpep_basic', true)){
	define("WPSP_PLUGIN_PATH", plugin_dir_path(__FILE__).'premium-files/');
	define("WPSP_PLUGIN_URL", plugin_dir_url(__FILE__).'premium-files/');
	define("WPEP_PLUGIN_PATH", plugin_dir_path(__FILE__));
	define("WPEP_PLUGIN_URL", plugin_dir_url(__FILE__));
	// form 
	require_once( WPSP_PLUGIN_PATH . 'form/wpep_form.php' );
	/**
	 * Events Log 
	 */
	require_once( WPSP_PLUGIN_PATH . 'includes/wpep-event-log.php' );
	/**
	 * include square lib
	 */
	require_once( WPSP_PLUGIN_PATH . 'lib/square-sdk/autoload.php' );
	/**
	 * WPSP Custom CSS
	 */
	require_once( WPSP_PLUGIN_PATH . 'includes/wpep-custom-css.php' );
	/**
	 * wp square settings class
	 */
	require_once( WPSP_PLUGIN_PATH . 'includes/wpep-settings-class.php' );
	new WPSP_Settings();

	/**
	 * wp square button class
	 */
	require_once( WPSP_PLUGIN_PATH . 'includes/wpep-button-cpt-class.php' );
	new WPSP_Buttons();

	/**
	 * wp square button shortcode class
	 */
	require_once( WPSP_PLUGIN_PATH . 'includes/wpep-button-shortcode-class.php' );
	new WPSP_Button_Shortcode();

	/**
	 * wp square payment class
	 */
	require_once( WPSP_PLUGIN_PATH . 'includes/wpep-payment-class.php' );
	new WPSP_Payment();
	if ( wepp_fs()->is_plan('wpep_premium', true) OR wepp_fs()->is_plan('wpep_business', true) ) {
	/**
	 * renew subscription
	 */
	require_once( WPSP_PLUGIN_PATH . 'includes/wpep-subscription-renew-class.php' );
	new WPSP_Subscription_Renew();

	/**
	 * wp square subscription shortcode class
	 */
	require_once( WPSP_PLUGIN_PATH . 'includes/wpep-subscription-shortcode-class.php' );
	new WPSP_Subscription_Shortcode();

	/**
	 * wp square renew page shortcode class
	 */
	require_once( WPSP_PLUGIN_PATH . 'includes/wpep-renew-page-shortcode-class.php' );
	new WPSP_Renew_Page_Shortcode();

	/**
	 * wp square payment class
	 */
	require_once( WPSP_PLUGIN_PATH . 'includes/wpep-renew-payment-class.php' );
	new WPSP_Renew_Payment();

	require_once( WPSP_PLUGIN_PATH . 'form/wpep-fields-class.php' );

	}
} else {

	define("WPEP_PLUGIN_PATH", plugin_dir_path(__FILE__));
	define("WPEP_PLUGIN_URL", plugin_dir_url(__FILE__));
	/**
	 * include square lib
	 */
	require_once( WPEP_PLUGIN_PATH . 'lib/square-sdk/autoload.php' );
	/**
	 * wp square class
	 */
	require_once( WPEP_PLUGIN_PATH . 'includes/wpep-class.php' );
	new WPEP_Settings();
	/**
	 * ap square form
	 */
	require_once( WPEP_PLUGIN_PATH . 'includes/wpep-form-class.php' );
	new WPEP_Form();
	//require_once( WPEP_PLUGIN_PATH . 'form/wpep-fields-class.php' );
	//if it is redirected to pro page redirect to free page.
	if(@$_GET['post_type'] == 'wpep-button' and @$_GET['page'] == 'wpep-settings'){
			echo '<script> window.location.href = "admin.php?page=wpep-settings"</script>';
	}
	if(in_array('wp-easy-pay-premium/wp-easy-pay.php', apply_filters('active_plugins', get_option('active_plugins')))){
		delete_option('fs_active_plugins');
		delete_option('fs_accounts');
		if(@$_GET['page'] == 'wpep-settings'){
			echo '<script> window.location.href = "admin.php?page=wp-easy-pay"</script>';	
		} 
		
	}

}
