<?php
/*
  Plugin Name: WooSquare Pro 
  Plugin URI: http://codecanyon.net/item/woosquare/14663170
  Description: WooSquare purpose is to migrate & synchronize data (sales – products - categories) between Square system point of sale & Woo commerce plug-in. 
  Version: 6.2
  Author: wpexperts.io
  Author URI: https://wpexperts.io/
  License: GPL
 */
 
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
			

function report_error_pro() {
	$class = 'notice notice-error';
	if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
		$message = __( 'To use "WooSquare Pro - WooCommerce Square Integration" WooCommerce must be activated or installed!', 'woosquare' );
		printf( '<br><div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	}
	if (version_compare( PHP_VERSION, '5.5.0', '<' )) {
		$message = __( 'To use "WooSquare Pro - WooCommerce Square Integration" PHP version must be 5.5.0+, Current version is: ' . PHP_VERSION . ". Contact your hosting provider to upgrade your server PHP version.\n", 'woosquare' );
		printf( '<br><div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	}
	deactivate_plugins('woosquare-pro/woocommerce-square-integration.php');
	wp_die('','Plugin Activation Error',  array( 'response'=>200, 'back_link'=>TRUE ) );

}
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))
	or 
	version_compare( PHP_VERSION, '5.5.0', '<' )
	) { 
	add_action( 'admin_notices', 'report_error_pro' );
	} else {
		
if (!in_array('woosquare/woocommerce-square-integration.php', apply_filters('active_plugins', get_option('active_plugins')))) {

define('WOO_SQUARE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WOO_SQUARE_PLUGIN_PATH', plugin_dir_path(__FILE__));

define('WOO_SQUARE_TABLE_DELETED_DATA','woo_square_integration_deleted_data');
define('WOO_SQUARE_TABLE_SYNC_LOGS','woo_square_integration_logs');

//max sync running time
define('WOO_SQUARE_MAX_SYNC_TIME',600*200);
define( 'WooSquare_VERSION', '1.0.11' );
define( 'WooSquare_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WooSquare_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );

// if using staging, define this in wp-config.php
if ( ! defined( 'WC_SQUARE_ENABLE_STAGING' ) ) {
	define( 'WC_SQUARE_ENABLE_STAGING', false );
}

add_action('admin_menu', 'woo_square_settings_page');
add_action('admin_enqueue_scripts', 'woo_square_script');
add_action('wp_ajax_manual_sync', "woo_square_manual_sync");
add_action('save_post', 'woo_square_add_edit_product', 10, 3);
add_action('before_delete_post', 'woo_square_delete_product');
add_action('create_product_cat', 'woo_square_add_category');
add_action('edited_product_cat', 'woo_square_edit_category');
add_action('delete_product_cat', 'woo_square_delete_category',10,3);
add_action('woocommerce_order_refunded', 'woo_square_create_refund', 10, 2);
add_action('woocommerce_order_status_completed', 'woo_square_complete_order');
add_action('auto_sync_cron_job_hook', 'auto_sync_cron_job');
add_filter('cron_schedules', 'cron_add_3min');
add_action( 'wp_loaded','post_savepage_load_admin_notice' );
add_action( 'admin_post_add_foobar', 'prefix_admin_Square_payment_settings_save' );
add_action( 'admin_post_nopriv_add_foobar', 'prefix_admin_Square_payment_settings_save' );
// ADDED ACTION TO CATCH DUPLICATE PRODUCT AND REMOVE META DATA
add_action("woocommerce_product_duplicate_before_save",'CatchDuplicateProduct',1, 2);
function CatchDuplicateProduct($duplicate, $product){
    $duplicate->delete_meta_data( "square_id" );
    $duplicate->delete_meta_data( "_square_item_id" );
    $duplicate->delete_meta_data( "_square_item_variation_id" );
    Helpers::debug_log('Info',"Duplicate product - Remove Square ID's");
}

// Change new order email recipient for registered customers
function wc_change_admin_new_order_email_recipient( $recipient, $order ) {
	$customer_id  = get_post_meta($order->get_id(),'_customer_user',true);
	$user_info = get_userdata($customer_id);	
	update_option('square_new_email',$user_info->user_nicename);
    // check if product in order
    if ( $user_info->user_nicename == "square_user" )  {
        $recipient = "";
    } else {
        $recipient = $recipient;
    }
	
    return $recipient; 
	
}
if(get_option('sync_square_order_notify') == 1){
	//add_filter('woocommerce_email_recipient_new_order', 'wc_change_admin_new_order_email_recipient', 1, 2);
}

register_activation_hook(__FILE__, 'square_plugin_activation');

//import classes
require_once WOO_SQUARE_PLUGIN_PATH . '_inc/square.class.php';
require_once WOO_SQUARE_PLUGIN_PATH . '_inc/Helpers.class.php';
require_once WOO_SQUARE_PLUGIN_PATH . '_inc/WooToSquareSynchronizer.class.php';
require_once WOO_SQUARE_PLUGIN_PATH . '_inc/SquareToWooSynchronizer.class.php';
require_once WOO_SQUARE_PLUGIN_PATH . '/_inc/admin/ajax.php';
require_once WOO_SQUARE_PLUGIN_PATH . '/_inc/admin/pages.php';
require_once WOO_SQUARE_PLUGIN_PATH . '/_inc/SquareClient.class.php' ;
require_once WOO_SQUARE_PLUGIN_PATH . '/_inc/SquareSyncLogger.class.php' ;
require_once WOO_SQUARE_PLUGIN_PATH . '/_inc/payment/SquarePaymentLogger.class.php' ;
require_once WOO_SQUARE_PLUGIN_PATH . '/_inc/payment/SquarePayments.class.php' ;


function checkOrAddPluginTables(){
    //create tables
    require_once  ABSPATH . '/wp-admin/includes/upgrade.php' ;
    global $wpdb;
   
    //deleted products table
    $del_prod_table = $wpdb->prefix.WOO_SQUARE_TABLE_DELETED_DATA;
    if ($wpdb->get_var("SHOW TABLES LIKE '$del_prod_table'") != $del_prod_table) {
        
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";

        
        $sql = "CREATE TABLE " . $del_prod_table . " (
			`square_id` varchar(50) NOT NULL,
                        `target_id` bigint(20) NOT NULL,
                        `target_type` tinyint(2) NULL,
                        `name` varchar(255) NULL,
			PRIMARY KEY (`square_id`)
		) $charset_collate;";
        dbDelta($sql);
    }
    
    //logs table
    $sync_logs_table = $wpdb->prefix.WOO_SQUARE_TABLE_SYNC_LOGS;
    if ($wpdb->get_var("SHOW TABLES LIKE '$sync_logs_table'") != $sync_logs_table) {
        
        if (!empty($wpdb->charset))
            $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
        if (!empty($wpdb->collate))
            $charset_collate .= " COLLATE $wpdb->collate";


        $sql = "CREATE TABLE " . $sync_logs_table . " (
                    `id` bigint(20) auto_increment NOT NULL,
                    `target_id` bigint(20) NULL,
                    `target_type` tinyint(2) NULL,
                    `target_status` tinyint(1) NULL,
                    `parent_id` bigint(20) NOT NULL default '0',
                    `square_id` varchar(50) NULL,
                    `action`  tinyint(3) NOT NULL,
                    `date` TIMESTAMP NOT NULL,
                    `sync_type` tinyint(1) NULL,
                    `sync_direction` tinyint(1) NULL,
                    `name` varchar(255) NULL,
                    `message` text NULL,
                    PRIMARY KEY (`id`)
            ) $charset_collate;";
        dbDelta($sql);
    }
}

/*
 * square activation
 */

function square_plugin_activation() {
	
    $user_id = username_exists('square_user');
    if (!$user_id) {
        $random_password = wp_generate_password(12);
        $user_id = wp_create_user('square_user', $random_password);
        wp_update_user(array('ID' => $user_id, 'first_name' => 'Square', 'last_name' => 'User'));
    }
	//check begin time exist for payment.
	if(!get_option('square_payment_begin_time')){
		// 2013-01-15T00:00:00Z
		update_option('square_payment_begin_time',date("Y-m-d")."T00:00:00Z");
	}
	
	
    update_option('woo_square_merging_option', 1);
    update_option('sync_on_add_edit', 1);
	update_option('sync_square_order_notify','');
	update_option('html_sync_des','');	
    // file_put_contents(__DIR__.'/my_loggg.txt', ob_get_contents());
}


/**
 * include script
 */
function woo_square_script() {
    
    wp_enqueue_script('woo_square_script', WOO_SQUARE_PLUGIN_URL . '_inc/js/script.js', array('jquery')); 
    wp_localize_script('woo_square_script', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));

    wp_enqueue_style('woo_square_pop-up', WOO_SQUARE_PLUGIN_URL . '_inc/css/pop-up.css');
    wp_enqueue_style('woo_square_synchronization', WOO_SQUARE_PLUGIN_URL . '_inc/css/synchronization.css');
   
}

/*
 * Ajax action to execute manual sync
 */

function woo_square_manual_sync() {
    
    ini_set('max_execution_time', 0);
    
    if(!get_option('woo_square_access_token')){
        return;
    }
    
    if(get_option('woo_square_running_sync') && (time()-(int)get_option('woo_square_running_sync_time')) < (WOO_SQUARE_MAX_SYNC_TIME) ){
        Helpers::debug_log('error',"Manual Sync Request: There is already sync running");
        echo 'There is another Synchronization process running. Please try again later. Or <a href="'. admin_url('admin.php?page=square-settings&terminate_sync=true').'" > terminate now </a>';
        die();
    }
    
    update_option('woo_square_running_sync', true);
    update_option('woo_square_running_sync_time', time());
    
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

        $sync_direction = $_GET['way'];
        $square = new Square(get_option('woo_square_access_token'),get_option('woo_square_location_id'),get_option('woo_square_app_id'));
        if ($sync_direction == 'wootosqu') {
            $squareSynchronizer = new WooToSquareSynchronizer($square);
            $squareSynchronizer->syncFromWooToSquare();
        } else if ($sync_direction == 'squtowoo') {          
            $squareSynchronizer = new SquareToWooSynchronizer($square);
            $squareSynchronizer->syncFromSquareToWoo();
        }
    }
    update_option('woo_square_running_sync', false);
    update_option('woo_square_running_sync_time', 0);
    die();
}


function post_savepage_load_admin_notice() {
	// Use html_compress($html) function to minify html codes.
	
			
	
			if(!empty($_GET['post'])){
				$admin_notice_square = get_post_meta($_GET['post'], 'admin_notice_square', true);
		
				if(!empty($admin_notice_square)){
					ob_start();
					echo __('<div  id="message" class="notice notice-error  is-dismissible"><p>'.$admin_notice_square.'</p></div>','error-sql-syn-on-update');
					delete_post_meta($_GET['post'], 'admin_notice_square', 'Product unable to sync to Square due to Sku missing ');
					
				}
			}
			
}



/*
 * Adding and editing new product
 */

function woo_square_add_edit_product($post_id, $post, $update) {
	// checking Would you like to synchronize your product on every product edit or update ?   
	$sync_on_add_edit = get_option( 'sync_on_add_edit', $default = false ) ;
	if($sync_on_add_edit == '1'){
			
		
		//Avoid auto save from calling Square APIs.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}
		   

		if ($update && $post->post_type == "product" && $post->post_status == "publish") {
			
			update_post_meta($post_id, 'is_square_sync', 0);
			Helpers::debug_log('info',"[add_update_product_hook] Start updating product on Square");

		
			if(!get_option('woo_square_access_token')){
				return;
			}
			

			$product_square_id = get_post_meta($post_id, 'square_id', true);
			$square = new Square(get_option('woo_square_access_token'),get_option('woo_square_location_id'),get_option('woo_square_app_id'));
			
			$squareSynchronizer = new WooToSquareSynchronizer($square);       
			$result = $squareSynchronizer->addProduct($post, $product_square_id);

			$termid = get_post_meta($post_id, '_termid', true);
			if ($termid == '') {//new product
				$termid = 'update';
			}
			update_post_meta($post_id, '_termid', $termid);
			
			if( $result===TRUE ){
				update_post_meta($post_id, 'is_square_sync', 1);  
			}

			Helpers::debug_log('info',"[add_update_product_hook] End updating product on Square");

			
		}
	} else {
		update_post_meta($post_id, 'is_square_sync', 0);  
	}
}

/*
 * Deleting product 
 */

function woo_square_delete_product($post_id) {
	$sync_on_add_edit = get_option( 'sync_on_add_edit', $default = false ) ;
    if($sync_on_add_edit == '1'){
    //Avoid auto save from calling Square APIs.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
		$product_square_id = get_post_meta($post_id, 'square_id', true);
		$product= get_post($post_id);
		if ($product->post_type == "product" && !empty($product_square_id)) {
			
			Helpers::debug_log('info',"[delete_product_hook] Start deleting product {$post_id} [square:{$product_square_id}] from Square");

			global $wpdb;

			$wpdb->insert($wpdb->prefix.WOO_SQUARE_TABLE_DELETED_DATA,
					[
						'square_id'  => $product_square_id,
						'target_id'  => $post_id,
						'target_type'=> Helpers::TARGET_TYPE_PRODUCT,
						'name'       => $product->post_title
					]
			);
					
			if(!get_option('woo_square_access_token')){
				return;
			}

			$square = new Square(get_option('woo_square_access_token'),get_option('woo_square_location_id'),get_option('woo_square_app_id'));
			$squareSynchronizer = new WooToSquareSynchronizer($square);       
			$result = $squareSynchronizer->deleteProductOrGet($product_square_id,"DELETE");
			
			
			//delete product from plugin delete table
			if($result===TRUE){
				$wpdb->delete($wpdb->prefix.WOO_SQUARE_TABLE_DELETED_DATA,
					['square_id'=> $product_square_id ]
				);
				Helpers::debug_log('info',"[delete_product_hook] Product {$post_id} deleted successfully from Square");

				
			}
			Helpers::debug_log('info',"[delete_product_hook] End deleting product {$post_id} [square:{$product_square_id}] from Square");


		}
	}
}

/*
 * Adding new Category
 */

function woo_square_add_category($category_id) {
    
    //Avoid auto save from calling Square APIs.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $category = get_term_by('id', $category_id, 'product_cat');
    update_option("is_square_sync_{$category_id}", 0);
    Helpers::debug_log('info',"[add_category_hook] Start adding category to Square: {$category_id}");
   
    if(!get_option('woo_square_access_token')){
        return;
    }
    

    $square = new Square(get_option('woo_square_access_token'),get_option('woo_square_location_id'),get_option('woo_square_app_id'));
    
    $squareSynchronizer = new WooToSquareSynchronizer($square);
    $result = $squareSynchronizer->addCategory($category);
    
    if( $result===TRUE ){
        update_option("is_square_sync_{$category_id}", 1);
    }
    Helpers::debug_log('info',"[add_category_hook] End adding category {$category_id} to Square");
}

/*
 * Edit Category
 */

function woo_square_edit_category($category_id) {
    
    //Avoid auto save from calling Square APIs.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
            
    update_option("is_square_sync_{$category_id}", 0);
   
    if(!get_option('woo_square_access_token')){
        return;
    }
    $category = get_term_by('id', $category_id, 'product_cat');
    $categorySquareId = get_option('category_square_id_' . $category->term_id);
    Helpers::debug_log('info',"[edit_category_hook] Start updating category on Square: {$category_id} [square:{$categorySquareId}]");


    $square = new Square(get_option('woo_square_access_token'),get_option('woo_square_location_id'),get_option('woo_square_app_id'));
    $squareSynchronizer = new WooToSquareSynchronizer($square);
    
    //add category if not already linked to square, else update
    if( empty($categorySquareId )){
        $result = $squareSynchronizer->addCategory($category);
    }else{
        $result = $squareSynchronizer->editCategory($category,$categorySquareId);
    }
    
    
    if( $result===TRUE ){
        update_option("is_square_sync_{$category_id}", 1);
        Helpers::debug_log('info',"[edit_category_hook] category {$category_id} updated successfully");
    }
    Helpers::debug_log('info',"[edit_category_hook] End updating category on square: {$category_id} [square:{$categorySquareId}]");
}

/*
 * Delete Category ( called after the category is deleted )
 */

function woo_square_delete_category($category_id,$term_taxonomy_id, $deleted_category) {
   
    //Avoid auto save from calling Square APIs.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    $category_square_id = get_option('category_square_id_' . $category_id);
    
    //delete category options
    delete_option( "is_square_sync_{$category_id}" );
    delete_option( "category_square_id_{$category_id}" );
    delete_option( "category_square_id_{$category_id}" );
    
    //no need to call square
    if(empty($category_square_id)){
        return;
    }

    Helpers::debug_log('info',"[delete_category_hook] Start deleting category {$category_id} [square:{$category_square_id}] from Square");
    global $wpdb;

    $wpdb->insert($wpdb->prefix.WOO_SQUARE_TABLE_DELETED_DATA,
            [
                'square_id'  => $category_square_id,
                'target_id'  => $category_id,
                'target_type'=> Helpers::TARGET_TYPE_CATEGORY,
                'name'       => $deleted_category->name
            ]
    );

    if(!get_option('woo_square_access_token')){
        return;
    }

    $square = new Square(get_option('woo_square_access_token'),get_option('woo_square_location_id'),get_option('woo_square_app_id'));
    $squareSynchronizer = new WooToSquareSynchronizer($square); 
    $result = $squareSynchronizer->deleteCategory($category_square_id);
    
    //delete product from plugin delete table
    if($result===TRUE){
        $wpdb->delete($wpdb->prefix.WOO_SQUARE_TABLE_DELETED_DATA,
            ['square_id'=> $category_square_id ]
        );
        Helpers::debug_log('info',"[delete_category_hook] Category {$category_id} deleted successfully from Square");

    }
    Helpers::debug_log('info',"[delete_category_hook] End deleting category {$category_id} [square:{$category_square_id}] from Square");
}

/*
 * Create Refund
 */

function woo_square_create_refund($order_id, $refund_id) {
    if(!get_option('woo_square_access_token')){
        return;
    }
    //Avoid auto save from calling Square APIs.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (get_post_meta($order_id, 'square_payment_id', true)) {

        $square = new Square(get_option('woo_square_access_token'),get_option('woo_square_location_id'),get_option('woo_square_app_id'));
        $square->refund($order_id, $refund_id);
    }
}

/*
 * update square inventory on complete order 
 */

function woo_square_complete_order($order_id) {
    if(!get_option('woo_square_access_token')){
        return;
    }
    //Avoid auto save from calling Square APIs.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    $square = new Square(get_option('woo_square_access_token'),get_option('woo_square_location_id'),get_option('woo_square_app_id'));
    $square->completeOrder($order_id);
}

/*
 *  Cron Jobs setup
 */

function auto_sync_cron_job() {
    ini_set('max_execution_time', 0);
    
    if(!get_option('woo_square_access_token')){
        return;
    }
    if(get_option('woo_square_running_sync') && (time()-(int)get_option('woo_square_running_sync_time')) < (WOO_SQUARE_MAX_SYNC_TIME) ){
        Helpers::debug_log('error',"Automatic Sync Request: There is already sync running");
        return;
    }
    update_option('woo_square_running_sync', true);
    update_option('woo_square_running_sync_time', time());
    
    Helpers::debug_log('info',"[cron] Automatic sync run via cron job");
    
    $square = new Square(get_option('woo_square_access_token'),get_option('woo_square_location_id'),get_option('woo_square_app_id'));
    if (get_option('woo_square_merging_option') == '1') { //From Woo To Square
        $squareSynchronizer = new WooToSquareSynchronizer($square);
        $squareSynchronizer->syncFromWooToSquare();       
    } else if (get_option('woo_square_merging_option') == '2') { //From Square To Woo
        $squareSynchronizer = new SquareToWooSynchronizer($square);
        $squareSynchronizer->syncFromSquareToWoo();
    }
    update_option('woo_square_running_sync', false);
    update_option('woo_square_running_sync_time', 0);
}

function cron_add_3min($schedules) {
    $schedules['3min'] = array(
        'interval' => 3 * 60,
        'display' => __('Once every three minutes')
    );
    return $schedules;
}





/*
* form submit to save data of payment settings 
*/



function prefix_admin_Square_payment_settings_save() {
    // Handle request then generate response using echo or leaving PHP and using HTML
		$arraytosave = array(
			'enabled' => ($_POST['woocommerce_square_enabled'] == 1 ? 'yes' : 'no'),
			'title' => (!empty($_POST['woocommerce_square_title']) ? $_POST['woocommerce_square_title'] : ''),
			'description' => (!empty($_POST['woocommerce_square_description']) ? $_POST['woocommerce_square_description'] : ''),
			'capture' => ($_POST['woocommerce_square_capture'] == 1 ? 'yes' : 'no'),
			'create_customer' => ($_POST['woocommerce_square_create_customer'] == 1 ? 'yes' : 'no'),
			'logging' => ($_POST['woocommerce_square_logging'] == 1 ? 'yes' : 'no'),
			'Send_customer_info' => ($_POST['Send_customer_info'] == 1 ? 'yes' : 'no')
		);
		$arraytosave_serialize =  ($arraytosave);
		update_option( 'woocommerce_square_settings', $arraytosave_serialize );
		wp_redirect(get_admin_url( ).'admin.php?page=Square-Payment-Settings');
}

/**
		 * Check required environment
		 *
		 * @access public
		 * @since 1.0.10
		 * @version 1.0.10
		 * @return null
		 */
		add_action( 'admin_notices', 'check_environment' );

		 function check_environment() {
			if ( ! is_allowed_countries() ) {
				$admin_page = 'wc-settings';

				echo '<div class="error">
					<p>' . sprintf( __( 'To enable payment gateway Square requires that the <a href="%s">base country/region</a> is the United States,United Kingdom,Japan, Canada or Australia.', 'woosquare' ), admin_url( 'admin.php?page=' . $admin_page . '&tab=general' ) ) . '</p>
				</div>';
			}

			if ( ! is_allowed_currencies() ) {
				$admin_page = 'wc-settings';

				echo '<div class="error">
					<p>' . sprintf( __( 'To enable payment gateway Square requires that the <a href="%s">currency</a> is set to USD,GBP,JPY, CAD or AUD.', 'woosquare' ), admin_url( 'admin.php?page=' . $admin_page . '&tab=general' ) ) . '</p>
				</div>';
			}
		}


		
		 function is_allowed_countries() {
			 
	
			 if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				if ( 
					'US' !== WC()->countries->get_base_country() && 
					'CA' !== WC()->countries->get_base_country() && 
					'JP' !== WC()->countries->get_base_country() &&
					'AU' !== WC()->countries->get_base_country() &&
					'GB' !== WC()->countries->get_base_country() 
					) {
					return false;
				}
			} else {
				$class = 'notice notice-error';
				$message = __( 'To use Woosquare WooCommerce must be installed and activated!',  'woosquare');

				printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
				deactivate_plugins( plugin_basename( __FILE__ ) );
			}
			 
			 
			

			return true;
		}
		
		 function is_allowed_currencies() {
			 
			 
			  if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				if ( 
				'US' !== WC()->countries->get_base_country() && 
				'CA' !== WC()->countries->get_base_country() && 
				'JP' !== WC()->countries->get_base_country() &&
				'AU' !== WC()->countries->get_base_country() &&
				'GB' !== WC()->countries->get_base_country() 
				) {
					return false;
				}
			} else {
				$class = 'notice notice-error';
				$message = __( 'To use Woosquare. WooCommerce Currency must be USD,CAD,AUD',  'woosquare');

				printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
				deactivate_plugins( plugin_basename( __FILE__ ) );
			}
			 
			 
			 
			

			return true;
		}
		
function payment_gateway_disable_country( $available_gateways ) {
	global $woocommerce;


	if ( isset( $available_gateways['square'] ) && !is_ssl()) {
		unset( $available_gateways['square'] );
	}
	$sandorlive =  explode('-',get_option('woo_square_access_token'))[0];
	$woocommerce_square_settings = get_option('woocommerce_square_settings');
	if($woocommerce_square_settings['enabled'] == 'no'){
		unset( $available_gateways['square'] );
	} else if($sandorlive == 'sandbox'){
		$current_user = wp_get_current_user();
		if(user_can( $current_user, 'administrator' ) != 1){
				// user is an admin
				unset( $available_gateways['square'] );
		} 
	}


	return $available_gateways;
}
 
add_filter( 'woocommerce_available_payment_gateways', 'payment_gateway_disable_country' );	
	

} else {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	deactivate_plugins('woosquare/woocommerce-square-integration.php');
	activate_plugin('woosquare-pro/woocommerce-square-integration.php');
		
}
} 