<?php

// don't load directly
if ( !defined('ABSPATH') )
	die('-1');

/**
 * settings page
 */
function woo_square_settings_page() {
    add_menu_page('Woo Square Settings', 'Woo-Square', 'manage_options', 'square-settings', 'square_settings_page', "dashicons-store");
    add_submenu_page('square-settings', "Square-Payment-Settings", "Square Payment", 'manage_options', 'Square-Payment-Settings', 'square_payment_plugin_page');
	add_submenu_page('square-settings', "Logs", "Logs", 'manage_options', 'square-logs', 'logs_plugin_page');
}

/**
 * Settings page action
 */
function square_settings_page() {
    
    checkOrAddPluginTables();
    $square = new Square(get_option('woo_square_access_token'), get_option('woo_square_location_id'),get_option('woo_square_app_id'));

    $errorMessage = '';
    $successMessage = '';
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['terminate_sync'])) {
        
        //clear session variables if exists
        if (isset($_SESSION["square_to_woo"])){ unset($_SESSION["square_to_woo"]); };
        if (isset($_SESSION["woo_to_square"])){ unset($_SESSION["woo_to_square"]); };
        
        update_option('woo_square_running_sync', false);
        update_option('woo_square_running_sync_time', 0);
        Helpers::debug_log('info', "Synchronization terminated due to admin request");

        $successMessage = 'Sync terminated successfully!';
    }
    
    // check if the location is not setuped
    if (get_option('woo_square_access_token') && !get_option('woo_square_location_id')) {
        $square->authorize();
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // setup account
        if (isset($_POST['woo_square_access_token'])) {
            $square->setAccessToken(sanitize_text_field($_POST['woo_square_access_token']));
			$square->setapp_id(sanitize_text_field($_POST['woo_square_app_id']));
            if ($square->authorize()) {
                $successMessage = 'Settings updated successfully!';
            } else {
                $errorMessage = 'Square Account Not Authorized';
            }
        }
        // save settings
        if (isset($_POST['woo_square_settings'])) {
            update_option('woo_square_auto_sync', sanitize_text_field($_POST['woo_square_auto_sync']));
            if ($_POST['woo_square_auto_sync']) {
                update_option('woo_square_auto_sync_duration', sanitize_text_field($_POST['woo_square_auto_sync_duration']));
                wp_clear_scheduled_hook('auto_sync_cron_job_hook');
                switch ($_POST['woo_square_auto_sync_duration']) {
                    case 3:
                        wp_schedule_event(time(), '3min', 'auto_sync_cron_job_hook');
                        break;
                    case 60: 
                        wp_schedule_event(time(), 'hourly', 'auto_sync_cron_job_hook');
                        break;
                    case 720:
                        wp_schedule_event(time(), 'twicedaily', 'auto_sync_cron_job_hook');
                        break;
                    case 1440:
                        wp_schedule_event(time(), 'daily', 'auto_sync_cron_job_hook');
                        break;
                }
            } else {
                wp_clear_scheduled_hook('auto_sync_cron_job_hook');
            }
            update_option('woo_square_merging_option', sanitize_text_field($_POST['woo_square_merging_option']));
            update_option('sync_on_add_edit', sanitize_text_field($_POST['sync_on_add_edit']));
            //update location id
            if( !empty($_POST['woo_square_location_id'])){
                $location_id = sanitize_text_field($_POST['woo_square_location_id']);
                @$woo_square_app_id = sanitize_text_field($_POST['woo_square_app_id']);
                update_option('woo_square_location_id', $location_id);               
                $square->setLocationId($location_id);
                $square->getCurrencyCode();
               
            }
			$square->setupWebhook("PAYMENT_UPDATED",get_option('woo_square_access_token'));
			update_option('sync_square_order_notify', sanitize_text_field(@$_POST['sync_square_order_notify']));
			update_option('html_sync_des', sanitize_text_field(@$_POST['html_sync_des']));
			
            $successMessage = 'Settings updated successfully!';
        }
    }
    $wooCurrencyCode    = get_option('woocommerce_currency');
    $squareCurrencyCode = get_option('woo_square_account_currency_code');
    
    if(!$squareCurrencyCode){
        $square->getCurrencyCode();
        $square->getapp_id();
        $squareCurrencyCode = get_option('woo_square_account_currency_code');
    }
    if ( $currencyMismatchFlag = ($wooCurrencyCode != $squareCurrencyCode) ){
        Helpers::debug_log('info', "Currency code mismatch between Square [$squareCurrencyCode] and WooCommerce [$wooCurrencyCode]");

    }
    include WOO_SQUARE_PLUGIN_PATH . 'views/settings.php';
}

/**
 * Logs page action
 * @global type $wpdb
 */
function logs_plugin_page(){
        
        checkOrAddPluginTables();       
        global $wpdb;
        
        $query = "
        SELECT log.id as log_id,log.action as log_action, log.date as log_date,log.sync_type as log_type,log.sync_direction as log_direction, children.*
        FROM ".$wpdb->prefix.WOO_SQUARE_TABLE_SYNC_LOGS." AS log
        LEFT JOIN ".$wpdb->prefix.WOO_SQUARE_TABLE_SYNC_LOGS." AS children
            ON ( log.id = children.parent_id )
        WHERE log.action = %d ";
              
        $parameters = [Helpers::ACTION_SYNC_START];
        
        //get the post params if sent or 'any' option was not chosen
        $sync_type = (isset($_POST['log_sync_type']) && strcmp($_POST['log_sync_type'],'any')) ?intval(sanitize_text_field($_POST['log_sync_type'])):null;
        $sync_direction = (isset($_POST['log_sync_direction']) && strcmp($_POST['log_sync_direction'],'any'))?intval(sanitize_text_field($_POST['log_sync_direction'])):null;
        $sync_date = isset($_POST['log_sync_date'])?
            (strcmp($_POST['log_sync_date'],'any')?intval(sanitize_text_field($_POST['log_sync_date'])):null):1;

        
        if (!is_null($sync_type)){
            $query.=" AND log.sync_type = %d ";
            $parameters[] = $sync_type; 
        }
        if (!is_null($sync_direction)){
           $query.=" AND log.sync_direction = %d ";
           $parameters[] = $sync_direction;  
        }
        if (!is_null($sync_date)){
           $query.=" AND log.date > %s ";
           $parameters[] = date("Y-m-d H:i:s", strtotime("-{$sync_date} days"));
        }
        
        
        $query.="
            ORDER BY log.id DESC,
                     id ASC";

        $sql =$wpdb->prepare($query, $parameters);
        $results = $wpdb->get_results($sql);
        $helper = new Helpers();
        
        include WOO_SQUARE_PLUGIN_PATH . 'views/logs.php';
       
}
/**
 * square payment plugin page action
 * @global type $wpdb
 */
function square_payment_plugin_page(){
     $square_payment_settin = get_option('woocommerce_square_settings');
 
      include WOO_SQUARE_PLUGIN_PATH . 'views/payment-settings.php';
}
/**
* Initialize Gateway Settings Form Fields
*/
	 function init_form_fields() {
	$form = array(
			'enabled' => array(
				'title'       => __( 'Enable/Disable', 'woosquare' ),
				'label'       => __( 'Enable Square', 'woosquare' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title' => array(
				'title'       => __( 'Title', 'woosquare' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woosquare' ),
				'default'     => __( 'Credit card (Square)', 'woosquare' )
			),
			'description' => array(
				'title'       => __( 'Description', 'woosquare' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woosquare' ),
				'default'     => __( 'Pay with your credit card via Square.', 'woosquare')
			),
			'capture' => array(
				'title'       => __( 'Delay Capture', 'woosquare' ),
				'label'       => __( 'Enable Delay Capture', 'woosquare' ),
				'type'        => 'checkbox',
				'description' => __( 'When enabled, the request will only perform an Auth on the provided card. You can then later perform either a Capture or Void.', 'woosquare' ),
				'default'     => 'no'
			),
			'create_customer' => array(
				'title'       => __( 'Create Customer', 'woosquare' ),
				'label'       => __( 'Enable Create Customer', 'woosquare' ),
				'type'        => 'checkbox',
				'description' => __( 'When enabled, processing a payment will create a customer profile on Square.', 'woosquare' ),
				'default'     => 'no'
			),
			'logging' => array(
				'title'       => __( 'Logging', 'woosquare' ),
				'label'       => __( 'Log debug messages', 'woosquare' ),
				'type'        => 'checkbox',
				'description' => __( 'Save debug messages to the WooCommerce System Status log.', 'woosquare' ),
				'default'     => 'no'
			),
			'Send_customer_info' => array(
				'title'       => __( 'Send Customer Info', 'wpexpert-square' ),
				'label'       => __( 'Send First Name Last Name', 'wpexpert-square' ),
				'type'        => 'checkbox',
				'description' => __( 'Send First Name Last Name with order to square.', 'wpexpert-square' ),
				'default'     => 'no'
			),
		);
		
		
	}
