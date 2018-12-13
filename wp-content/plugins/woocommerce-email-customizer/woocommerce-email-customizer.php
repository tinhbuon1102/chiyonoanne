<?php
/**
 * Plugin Name:       WooCommerce Email Customizer
 * Plugin URI:        https://themehigh.com/product/woocommerce-email-customizer
 * Description:       WooCommerce Email Customizer plugin allows store owners to customize transactional emails using a visual template editor.
 * Version:           1.1.0
 * Author:            ThemeHigh
 * Author URI:        https://themehigh.com/
 *
 * Text Domain:       woocommerce-email-customizer-pro
 * Domain Path:       /languages
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.4.4
 */

if(!defined('WPINC')){	die; }

if (!function_exists('is_woocommerce_active')){
	function is_woocommerce_active(){
	    $active_plugins = (array) get_option('active_plugins', array());
	    if(is_multisite()){
		   $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
	    }
	    return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
	}
}

if(is_woocommerce_active()) {
	define('THWEC_VERSION', '1.1.0');
	!defined('THWEC_SOFTWARE_TITLE') && define('THWEC_SOFTWARE_TITLE', 'WooCommerce Email Customizer');
	!defined('THWEC_FILE') && define('THWEC_FILE', __FILE__);
	!defined('THWEC_PATH') && define('THWEC_PATH', plugin_dir_path( __FILE__ ));
	!defined('THWEC_URL') && define('THWEC_URL', plugins_url( '/', __FILE__ ));
	!defined('THWEC_BASE_NAME') && define('THWEC_BASE_NAME', plugin_basename( __FILE__ ));
	
	/**
	 * The code that runs during plugin activation.
	 */
	function activate_thwec() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-thwec-activator.php';
		THWEC_Activator::activate();
	}
	
	/**
	 * The code that runs during plugin deactivation.
	 */
	function deactivate_thwec() {
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-thwec-deactivator.php';
		THWEC_Deactivator::deactivate();
	}
	
	register_activation_hook( __FILE__, 'activate_thwec' );
	register_deactivation_hook( __FILE__, 'deactivate_thwec' );
	
	function init_auto_updater_thwec(){
		if(!class_exists('THWEC_Auto_Update_License') ) {
			$api_url = 'https://themehigh.com/';
			require_once plugin_dir_path( __FILE__  ) . 'class-thwec-auto-update-license.php';
			THWEC_Auto_Update_License::instance(__FILE__, THWEC_SOFTWARE_TITLE, THWEC_VERSION, 'plugin', $api_url, 'woocommerce-email-customizer-pro');
		}
	}
	init_auto_updater_thwec();
	
	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-thwec.php';
	
	/**
	 * Begins execution of the plugin.
	 */
	function run_thwec() {
		$plugin = new THWEC();
		$plugin->run();
	}
	run_thwec();
}