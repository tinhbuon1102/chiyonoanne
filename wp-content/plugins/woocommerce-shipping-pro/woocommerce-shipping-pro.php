<?php
/*
	Plugin Name: WooCommerce Shipping Pro with Table Rate
	Plugin URI: https://www.pluginhive.com/product/woocommerce-table-rate-shipping-pro-plugin/
	Description: Intuitive Rule Based Shipping Plug-in for WooCommerce. Set shipping rates based on rules based by Country, State, Post Code, Product Category,Shipping Class and Weight.
	Version: 3.1.3
	Author: PluginHive
	Author URI: https://www.pluginhive.com/about/
	Copyright: 2014-2015 PluginHive.
	WC requires at least: 2.6.0
	WC tested up to: 3.5.7

*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wf_shipping_pro_activatoin_check(){
    //check if basic version is there
    if ( is_plugin_active('weight-country-woocommerce-shipping/weight-country-woocommerce-shipping.php') ){
        deactivate_plugins( basename( __FILE__ ) );
        wp_die( __("Oops! You tried installing the premium version without deactivating and deleting the basic version. Kindly deactivate and delete Australia Post(Basic) Woocommerce Extension and then try again", "wf_australia_post" ), "", array('back_link' => 1 ));
    }
}
register_activation_hook( __FILE__, 'wf_shipping_pro_activatoin_check' );

load_plugin_textdomain( 'wf_woocommerce_shipping_pro', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

require_once 'includes/class-ph-wc-shipping-pro-common.php';
$wc_active = Ph_WC_Shipping_Pro_Common::is_plugin_active('woocommerce/woocommerce.php');
if ( $wc_active ) {

    include( 'wf-shipping-pro-common.php' );
	if ( is_admin() ) {
		//include api manager
		include_once ( 'includes/wf_api_manager/wf-api-manager-config.php' );
	}
   
    if (!function_exists('wf_plugin_configuration')){
       function wf_plugin_configuration(){
            return array(
                'id' => 'wf_woocommerce_shipping_pro',
                'method_title' => __('Shipping Pro', 'wf_woocommerce_shipping_pro' ),
                'method_description' => __('Intuitive Rule Based Shipping Plug-in for WooCommerce. Set shipping rates based on rules based by Country, State, Post Code, Product Category,Shipping Class and Weight.', 'wf_woocommerce_shipping_pro' ));		
        }
    }

}

register_activation_hook( __FILE__, 'wf_plugin_activate' );
