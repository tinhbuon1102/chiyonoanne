<?php
/*
Plugin Name: WP HTML Mail - WooCommerce
Plugin URI: http://wp-html-mail.com/
Description: Beautiful responsive mails for your WooCommerce store
Version: 2.8.2
Author: Hannes Etzelstorfer
Author URI: http://etzelstorfer.com
License: GPLv2 or later
WC requires at least: 3.0.0
WC tested up to: 3.4.1
*/

/*  Copyright 2018 Hannes Etzelstorfer (email : hannes@etzelstorfer.com) */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
} 

include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); 

define( 'HAET_MAIL_WOOCOMMERCE_PATH', plugin_dir_path(__FILE__) );
define( 'HAET_MAIL_WOOCOMMERCE_URL', plugin_dir_url(__FILE__) );


function wphtmlmail_woocommerce_core_notice() {
    ?>
    <div class="notice notice-warning">
        <p><?php printf( 
                    __( '<strong>Notice:</strong> To use the WP HTML Mail - WooCommerce integration please install the free WP HTML Mail plugin first. <a href="%s">Install Plugin</a>', 'haet_mail' ), 
                    wp_nonce_url( network_admin_url( 'update.php?action=install-plugin&plugin=wp-html-mail' ), 'install-plugin_wp-html-mail' )
            ); ?></p>
    </div>
    <?php
}



function wphtmlmail_woocommerce_woocommerce_notice() {
    ?>
    <div class="notice notice-warning">
        <p><?php printf( 
                    __( '<strong>Notice:</strong> To use the WP HTML Mail - WooCommerce integration please install WooCommerce first. <a href="%s">Install Plugin</a>', 'haet_mail' ), 
                    wp_nonce_url( network_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' )
            ); ?></p>
    </div>
    <?php
}



function wphtmlmail_woocommerce_version_notice() {
    $min_core_version = '2.7.8';
    ?>
    <div class="notice notice-warning">
        <p><?php printf( 
                    __( '<strong>Notice:</strong> Please update WP HTML Mail to version %s before using the WooCommerce extension.', 'haet_mail' ), $min_core_version 
            ); ?></p>
    </div>
    <?php
}



function wphtmlmail_woocommerce_init(){
    if(!is_plugin_active( 'wp-html-mail/wp-html-mail.php' )){
        add_action( 'admin_notices', 'wphtmlmail_woocommerce_core_notice' );
    }else{
        if(!is_plugin_active( 'woocommerce/woocommerce.php' )){
            add_action( 'admin_notices', 'wphtmlmail_woocommerce_woocommerce_notice' );
        }else{
            $min_core_version = '2.7.8';
            $core_plugin_data = get_plugin_data( HAET_MAIL_PATH.'/wp-html-mail.php' );
            if( version_compare( $core_plugin_data['Version'] , $min_core_version, '<') ){
                add_action( 'admin_notices', 'wphtmlmail_woocommerce_version_notice' );
            }else{
                load_plugin_textdomain('haet_mail', false, dirname( plugin_basename( __FILE__ ) ) . '/translations' );
                require HAET_MAIL_WOOCOMMERCE_PATH . 'includes/class-haet-sender-plugin-woocommerce.php';
                require HAET_MAIL_WOOCOMMERCE_PATH . 'includes/class-wphtmlmail-woocommerce.php';
                require HAET_MAIL_WOOCOMMERCE_PATH . 'includes/class-contenttype-productstable.php';
                require HAET_MAIL_WOOCOMMERCE_PATH . 'includes/class-contenttype-relatedproducts.php';

                if(is_plugin_active( 'woocommerce-german-market/WooCommerce-German-Market.php' ))
                    require HAET_MAIL_WOOCOMMERCE_PATH . 'includes/class-contenttype-wgm.php';
                if(is_plugin_active( 'yith-woocommerce-barcodes-premium/init.php' ))
                    require HAET_MAIL_WOOCOMMERCE_PATH . 'includes/class-addon-yith-barcodes.php';
                require HAET_MAIL_PATH . 'includes/class-mailbuilder.php';

                add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( WPHTMLMail_Woocommerce(), 'plugin_action_links' ) );
            }
        }
    }

    // marketpress_wp_html_mail_autoupdater();
}
add_action( 'plugins_loaded', 'wphtmlmail_woocommerce_init', 20 );



function wphtmlmail_woocommerce_activate_after_wphtmlmail() {
    // ensure path to this file is via main wp plugin path
    $wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
    $this_plugin = plugin_basename(trim($wp_path_to_this_file));
    $active_plugins = get_option('active_plugins');
    $this_plugin_key = array_search($this_plugin, $active_plugins);
    if (false !== $this_plugin_key) { 
        array_splice($active_plugins, $this_plugin_key, 1);
        array_push($active_plugins, $this_plugin);
        update_option('active_plugins', $active_plugins);
    }
}
add_action('activated_plugin', 'wphtmlmail_woocommerce_activate_after_wphtmlmail');



function haet_mail_register_plugin_woocommerce($plugins){

    $plugins['woocommerce']   =  array(
        'name'      =>  'woocommerce',
        'file'      =>  'woocommerce/woocommerce.php',
        'class'     =>  'Haet_Sender_Plugin_WooCommerce',
        'display_name' => 'WooCommerce'
    );
    return $plugins;
}
add_filter( 'haet_mail_available_plugins', 'haet_mail_register_plugin_woocommerce');



function marketpress_wp_html_mail_autoupdater() {

        // Auto-Updater
        if ( is_admin() ) {

            if ( ! class_exists( 'MarketPress_Auto_Update' ) ) {
                require_once untrailingslashit( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'marketpress-autoupdater' . DIRECTORY_SEPARATOR . 'class-MarketPress_Auto_Update.php';
            }
            
            $plugindata_import = get_file_data(
                __FILE__,
                array(
                    'plugin_uri' => 'Plugin URI',
                    'plugin_name' => 'Plugin Name',
                    'version' => 'Version'
                )
            );

            $plugin_data = new stdClass();
            $plugin_data->plugin_slug       = 'email-designer-woocommerce';
            $plugin_data->shortcode         = 'email-designer-woocommerce';
            $plugin_data->plugin_name       = $plugindata_import[ 'plugin_name' ];
            $plugin_data->plugin_base_name  = plugin_basename( __FILE__ );
            $plugin_data->plugin_url        = $plugindata_import[ 'plugin_uri' ];
            $plugin_data->version           = $plugindata_import[ 'version' ];
            
            $autoupdate = new MarketPress_Auto_Update();
            $autoupdate->setup( $plugin_data );
        }
}

