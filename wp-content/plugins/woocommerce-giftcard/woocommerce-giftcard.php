<?php
/**
 * Plugin Name: WooCommerce Gift Card
 * Plugin URI: http://store.magenest.com/woocommerce-plugins/woocommerce-giftcard.html
 * Description:Add ability to create/sell/redeem giftcard.
 * Author: Magenest
 * Author URI: http://magenest.com
 * Version: 4.3
 * Text Domain: GIFTCARD
 * Domain Path: /languages/
 */
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly


if (!defined('GIFTCARD_TEXT_DOMAIN')) {
    define('GIFTCARD_TEXT_DOMAIN', 'GIFTCARD');
}

// Plugin Folder Path
if (!defined('GIFTCARD_PATH')) {
    define('GIFTCARD_PATH', plugin_dir_path(__FILE__));
}

// Plugin Folder URL
if (!defined('GIFTCARD_URL')) {
    define('GIFTCARD_URL', plugins_url('woocommerce-giftcard', 'woocommerce-giftcard.php'));
}

// Plugin Root File
if (!defined('GIFTCARD_FILE')) {
    define('GIFTCARD_FILE', plugin_basename(__FILE__));
}

spl_autoload_register(function ($class_name) {

    $location = __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
    if (file_exists($location)) {
        try {
            require_once $location;
            return;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
});

function gc_schedule_5minutes($schedules){
    $schedules['five_minutes'] = array(
        'interval' => 300,
        'display'  => esc_html__( 'Every Five Seconds' ),
    );

    return $schedules;
}
add_filter( 'cron_schedules',  'gc_schedule_5minutes' );

register_activation_hook(__FILE__, array('site\Main', 'install'));
register_deactivation_hook(__FILE__, array('model\ScheduleSendMail', 'remove_schedule') );

add_action('plugins_loaded', function () {

    load_plugin_textdomain( 'GIFTCARD', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

    \site\Main::getInstance();
});