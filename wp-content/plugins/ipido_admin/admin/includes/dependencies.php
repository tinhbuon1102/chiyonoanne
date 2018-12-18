<?php
/* ===============================================================================================
    ADMIN FRAMEWORK
   =============================================================================================== */
function csf_framework_init_check() {
    if( ! function_exists( 'csf_framework_init' ) && ! class_exists( 'CSFramework' ) ) {
        // Plugin location of cs-framework.php
        require_once plugin_dir_path( dirname( __FILE__ ) ) . '../adminframework/csf-framework.php';
    }
}
add_action( 'plugins_loaded', 'csf_framework_init_check' );


/* ===============================================================================================
    LOAD PLUGIN EXTERNAL DEPENDENCY FILES
   =============================================================================================== */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/define.php'; // Set plugin constants

// Init other actions before everything
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ipido_admin-before-activator.php';
Ipido_admin_Before_Activator::activate();

// Admin Pages
// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/page-menu_manager.php'; // Admin Page
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/page-dashboard.php';   // Admin Page