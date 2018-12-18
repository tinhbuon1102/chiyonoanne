<?php
class Ipido_admin_Before_Activator {
    
    /**
    * Autoload plugin settings
    *
    * @since    1.0.0
    */
    public static function activate() {
        $default_settings = array(
            'theme' => 'custom',
            'theme_settings' => array(
                'ipido_theme-custom' => array(
                    'ipido_theme-custom__scheme' => array(
                        'current_scheme_id' => 'grey-n-yellow',
                        'current_scheme_type' => 'predefined',
                        'predefined_schemes' => '',
                        'custom_schemes' => '',
                        'scheme_unique' => 'cs_ipidoadmin_settings[theme_settings][ipido_theme-custom][ipido_theme-custom__scheme]',
                        'header-brand-bg' => '#fe4641',
                        'header-brand-icon-bg' => '#333333',
                        'header-brand-icon-color' => '#fda527',
                        'header-brand-text' => '#fde3a7',
                        'header-brand-subtitle-text' => '#fde3a7',
                        'header-brand-border' => '#ff7772',
                        'header-bg' => '#ffffff',
                        'header-border' => '#cecece',
                        'header-text' => '#d0d0d0',
                        'header-toolbar-text' => '#d0d0d0',
                        'header-toolbar-text-hover' => '#fda527',
                        'sidebar-bg' => '#333333',
                        'sidebar-text' => '#888888',
                        'sidebar-hover-bg' => '#fda527',
                        'sidebar-hover-text' => '#333333',
                        'sidebar-active-bg' => '#252525',
                        'sidebar-active-text' => '#666666',
                        'sidebar-active-hover-text' => '#999999',
                        'sidebar-active-highlight' => '#fda527',
                        'sidebar-current-bg' => '#2d2d2d',
                        'sidebar-current-text' => '#fde3a7',
                        'sidebar-current-highlight' => '#fda527',
                        'sidebar-current-hover-bg' => '#282828',
                        'sidebar-current-hover-text' => '#fde3a7',
                        'sidebar-current-subitem-text' => '#707070',
                        'sidebar-current-subitem-hover-text' => '#a3a3a3',
                        'sidebar-current-subitem-current-text' => '#fe4641',
                        'primary-normal' => '#fda527',
                        'primary-light' => '#fcc06c',
                        'accent' => '#fe4641',
                        'button-primary-ini' => '#f9b32f',
                        'button-primary-end' => '#f39c12',
                        'button-primary-border' => '#f1892d',
                        'button-primary-text' => '#ffffff',
                        'button-base-ini' => '',
                        'button-base-end' => '',
                        'button-base-border' => '',
                        'button-base-text' => '',
                        'body-bg' => '#e0e0e0',
                        'body-text' => '#757575',
                        'input-bg' => '#ffffff',
                        'input-text' => '#3d3d3d',
                        'input-border' => '#d3abab',
                        'input-border-focus' => '#fe4641',
                        'card-bg' => '#ffffff',
                        'card-border' => '#cecece',
                        'card-border-bottom' => '#ff7772',
                        'card-title-bg' => '#fe4641',
                        'card-title-text' => '#fde3a7',
                        'dropdown-bg' => '#474747',
                        'dropdown-text' => '#878787',
                        'dropdown-border' => '#3d3d3d',
                        'dropdown-hover-bg' => '#fda527',
                        'dropdown-hover-text' => '#333333',
                    ),
                ),
            ),
            'logo_url' => 'admin_url',
            'logo_type' => 'image',
            'logo_type_image_fs' => array(
                'logo_image' => '3188',
                'logo_image_collapsed' => '3187',
            ),
            'logo_type_text_fs' => array(
                'logo_icon' => 'cli cli-zap',
                'logo_text' => 'IPIDO',
            ),
            'logo_favicon_status' => true,
            'logo_devices_fs' => array(
                'logo_favicon' => '3187',
                'logo_apple' => '',
                'logo_android' => '',
            ),
            'site_generator_status' => true,
            'site_generator_text' => 'CastorStudio.com',
            'site_generator_version' => '1.0.0',
            'site_generator_link' => 'http://www.castorstudio.com',
            'page_loader_status' => true,
            'page_loader_custom_colors_fs' => array(
                'page_loader_color_primary' => '#9C27B0',
                'page_loader_color_secondary' => '#E1BEE7',
            ),
            'page_loader_theme' => 'theme-2',
            'navbar_elements' => array(
                'main' => '["sidebartoggle","updates","comments","help","flexiblespace","account"]',
            ),
            'navbar_tooltips_status' => true,
            'navbar_position' => 'fixed',
            'footer_text_status' => true,
            'footer_text_fs' => array(
                'footer_text' => 'IPIDO Admin Powered by <a href="http://www.castorstudio.com" target="_blank" rel="noopener">CastorStudio</a>',
                'footer_text_visibility' => false,
            ),
            'footer_version_status' => true,
            'footer_version_fs' => array(
                'footer_version' => '<a href="http://www.castorstudio.com/ipido-admin-wordpress-white-label-admin-theme" target="_blank" rel="noopener">v1.0.0</a>',
                'footer_version_visibility' => false,
            ),
            'customcss' => '',
            'modules' => array(
                'admin_menu_manager',
                'help_tabs_manager',
                'login_page_manager',
            ),
            'helptabs_status' => true,
            'helptabs_container' => array(
                array(
                    'helptab_page' => 'index.php',
                    'helptab_sidebar_content' => '',
                    'helptab_items' => array(
                        array(
                            'tab_title' => 'How to use IPIDO Admin?',
                            'tab_content' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit, montes at venenatis nulla mollis proin varius praesent, nostra nascetur sociosqu etiam sociis euismod. Varius praesent aliquam sociosqu nunc sagittis porttitor integer suscipit mi, consequat pretium nisi inceptos senectus justo dignissim nisl mattis, molestie donec curae tempor iaculis velit a felis. Donec odio tincidunt laoreet mi vehicula elementum venenatis consequat et sollicitudin, fames ac pharetra tempus sed dictumst porta imperdiet vel ut, dui nec libero est natoque ad aliquet mauris ornare.',
                        ),
                    ),
                ),
            ),
            'logo_apple_status' => false,
            'logo_android_status' => false,
            'site_generator_visibility' => false,
            'page_loader_custom_colors_status' => false,
            'user_profile_status' => false,
            'user_profile_options' => false,
            'navbar_titlebar_hidepagetitle' => false,
            'navbar_titlebar_unify' => false,
            'navbar_sidebar_toggle_button_action' => false,
            'customcss_status' => false,
            'bodyscrollbar_status' => false,
            'resetsettings_status' => false,
            'rightnowwidget_status' => false,
        );
        
        if (!get_option('cs_ipidoadmin_settings')){
            add_option('cs_ipidoadmin_settings',$default_settings);
        }
    }
    
}