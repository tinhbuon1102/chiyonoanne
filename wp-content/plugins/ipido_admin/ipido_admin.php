<?php
/**
 * Plugin Name:       IPIDO Wordpress Admin Theme
 * Plugin URI:        http://www.castorstudio.com/ipido-admin-wordpress-white-label-admin-theme
 * Description:       IPIDO Admin is the most complete and fully powered WP admin theme that we have ever made. With IPIDO you will forget that you are using WordPress as fast as a lightning. Customizing your admin area has never been so easy, with so many options, easy to use and with the posibility to change everything in seconds.
 * Version:           1.0.0
 * Author:            Castorstudio
 * Author URI:        http://www.castorstudio.com
 * Text Domain:       ipido_admin
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'IPIDO_ADMIN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ipido_admin-activator.php
 */
function activate_ipido_admin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ipido_admin-activator.php';
	Ipido_admin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ipido_admin-deactivator.php
 */
function deactivate_ipido_admin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ipido_admin-deactivator.php';
	Ipido_admin_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ipido_admin' );
register_deactivation_hook( __FILE__, 'deactivate_ipido_admin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ipido_admin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ipido_admin() {

	$plugin = new Ipido_admin();
	$plugin->run();

}
run_ipido_admin();
