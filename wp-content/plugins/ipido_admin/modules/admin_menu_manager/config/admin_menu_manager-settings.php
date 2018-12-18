<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK SETTINGS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$settings = array(
    'menu_type'             => 'submenu', // menu, submenu, options, theme, etc.
    'menu_parent'           => 'cs-ipido-admin-settings',
    'menu_title'            => __('Admin Menu Manager','ipido_admin'),
    'menu_slug'             => 'cs-ipido-admin-admin-menu-manager-settings',
    'menu_capability'       => 'manage_options',
    'menu_icon'             => 'dashicon-shield',
    'menu_position'         => null,
    'show_submenus'         => true,
    'framework_title'       => __('Admin Menu Manager','ipido_admin'),
    'framework_subtitle'    => __('v1.0.0','ipido_admin'),
    'ajax_save'             => true,
    'buttons'               => array('reset' => false),
    'option_name'           => 'cs_ipido_admin_amm_settings',
    'override_location'     => '',
    'extra_css'             => array(),
    'extra_js'              => array(),
    'is_single_page'        => true,
    'is_sticky_header'      => false,
    'style'                 => 'modern',
    'help_tabs'             => array(),
);


class CS_Admin_Module_AMM_settings{

    public function set_options(){
        // ===============================================================================================
        // -----------------------------------------------------------------------------------------------
        // FRAMEWORK OPTIONS
        // -----------------------------------------------------------------------------------------------
        // ===============================================================================================
        $options        = array();


        /* ===============================================================================================
            GENERAL SETTINGS
           =============================================================================================== */
        $options[]      = array(
            'name'        => 'sidebar_general',
            'title'       => __('Admin Menu','ipido_admin'),
            'icon'        => 'cli cli-sidebar',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Admin Menu','ipido_admin'),
                ),
                array(
                    'id'        => 'sidebar_accordion',
                    'type'      => 'switcher',
                    'title'     => __('Submenu Accordion','ipido_admin'),
                    'label'     => __('Collapse submenu as an accordion menu','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'sidebar_scrollbar',
                    'type'      => 'switcher',
                    'title'     => __('Custom Scrollbar','ipido_admin'),
                    'label'     => __('Use custom scrollbar when fixed-sidebar gets overflowed','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'sidebar_brand_position',
                    'type'      => 'select',
                    'title'     => __('Sidebar Brand Position','ipido_admin'),
                    'options'   => array(
                        'scroll'    => __('Follow Scroll','ipido_admin'),
                        'fixed'     => __('Fixed','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'sidebar_position',
                    'type'      => 'select',
                    'title'     => __('Sidebar Position','ipido_admin'),
                    'options'   => array(
                        'scroll'    => __('Follow Scroll','ipido_admin'),
                        'fixed'     => __('Fixed','ipido_admin'),
                    ),
                ),
            ), // end: fields
        );



        /* ===============================================================================================
            CUSTOMIZATION
           =============================================================================================== */
        $options[]      = array(
            'name'        => 'sidebar_customization',
            'title'       => __('Admin Menu Customization','ipido_admin'),
            'icon'        => 'cli cli-sidebar',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Admin Menu Customization','ipido_admin'),
                ),
                array(
                    'id'        => 'sidebar_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Admin Menu','ipido_admin'),
                    'label'     => __('Use custom admin menu','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'type'    		=> 'content',
                    'content'		=> Ipido_admin_Module_Admin_Menu_Manager::cs_menumng_settings_page(),
                    // 'wrap_class'	=> 'cs-uls-custom-content'
                ),
                
            ),
        );

        return $options;

    }

}

$module_settings    = new CSFramework( $settings );
$module_options     = new CS_Admin_Module_AMM_settings();

$fn = function() use($module_settings,$module_options){
    $options = $module_options->set_options();
    $module_settings->set_options($options);
};
add_action( 'admin_menu', $fn ,1000);





// add_filter('cs_framework_options','cs_filter_options',10,2);
// function cs_filter_options($options,$unique){
//     if ($unique == 'cs_ipidoadmin_settings'){
        
//     }

//     return $options;
// }