<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK SETTINGS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
$settings = array(
    'menu_type'             => 'menu', // menu, submenu, options, theme, etc.
    'menu_parent'           => '',
    'menu_title'            => __('IPIDO Admin Settings','ipido_admin'),
    'menu_slug'             => 'cs-ipido-admin-settings',
    'menu_capability'       => 'manage_options',
    'menu_icon'             => 'dashicons-shield',
    'menu_position'         => 1113.12,
    'show_submenus'         => true,
    'framework_title'       => __('IPIDO Admin Settings','ipido_admin'),
    'framework_subtitle'    => __('by CastorStudio','ipido_admin'),
    'ajax_save'             => true,
    'buttons'               => array('reset' => false),
    'option_name'           => 'cs_ipidoadmin_settings',
    'override_location'     => '',
    'extra_css'             => array(),
    'extra_js'              => array(),
    'is_single_page'        => true,
    'is_sticky_header'      => false,
    'style'                 => 'modern',
    'help_tabs'             => array(),
);


class cs_ipido_admin_settings{

    public function set_options(){
        // ===============================================================================================
        // -----------------------------------------------------------------------------------------------
        // FRAMEWORK OPTIONS
        // -----------------------------------------------------------------------------------------------
        // ===============================================================================================
        $options        = array();


        /* ===============================================================================================
            PICK THEME
        =============================================================================================== */
        $options['theme'] = array(
            'name'        => 'theme',
            'title'       => __('Themes','ipido_admin'),
            'icon'        => 'cli cli-layout',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Choose a Theme','ipido_admin'),
                ),
                array(
                    'type'    => 'content',
                    'content' => __('Here you can choose the theme you want, customize it or create a totally new one!','ipido_admin'),
                ),
                array(
                    'id'			=> 'theme',
                    'type'			=> 'image_select',
                    // 'title'			=> __('Theme','ipido_admin'),
                    'radio'			=> true,
                    'options'		=> Ipido_admin_Admin::get_admin_themes(),
                    'default'   	=> 'custom',
                ),
                array(
                    'id'			=> 'theme_settings',
                    'type'			=> 'fieldset',
                    'fields'		=> Ipido_admin_Admin::get_admin_themes_settings(),
                ),
                
            ), // end: fields
        );


        /* ===============================================================================================
            LOGO SETTINGS
        =============================================================================================== */
        $options['logo'] = array(
            'name'        => 'logo',
            'title'       => __('Logo Settings','ipido_admin'),
            'icon'        => 'cli cli-image',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Logo Settings','ipido_admin'),
                ),
                array(
                    'id'            => 'logo_url',
                    'type'          => 'text',
                    'title'         => __('Logo URL','ipido_admin'),
                    'subtitle'      => __('User will be redirected to this mentioned url when clicking the logo.','ipido_admin'),
                    'info'          => __('If you need to redirect to the wordpress admin area, use: "admin_url" without quotes','ipido_admin'),
                    'default'       => 'admin_url',
                ),
                array(
                    'id'            => 'logo_type',
                    'type'          => 'image_select',
                    'title'         => __('Admin Logo Type','ipido_admin'),
                    'subtitle'      => __('Choose a logo style to use in the admin area','ipido_admin'),
                    'options'       => array(
                        'image'     => CS_PLUGIN_URI .'/adminframework/assets/images/logo-type-image.png',
                        'text'      => CS_PLUGIN_URI .'/adminframework/assets/images/logo-type-text.png',
                    ),
                    'radio'         => true,
                    'default'       => 'text'
                ),

                // Logo Image
                // -----------------------------------------------------------------
                array(
                    'dependency'    => array('logo_type_image','==','true'),
                    'id'            => 'logo_type_image_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'logo_image',
                            'type'          => 'image',
                            'title'         => __('Logo Image','ipido_admin'),
                            'subtitle'      => __('Upload your own logo of 200px * 44px (width*height)','ipido_admin'),
                            'settings'      => array(
                                'button_title' => __('Choose Logo','ipido_admin'),
                                'frame_title'  => __('Choose an image','ipido_admin'),
                                'insert_title' => __('Use this logo','ipido_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'logo_image_collapsed',
                            'type'          => 'image',
                            'title'         => __('Logo Image Collapsed Menu','ipido_admin'),
                            'subtitle'      => __('Upload your own logo of 44px * 44px (width*height)','ipido_admin'),
                            'settings'      => array(
                                'button_title' => __('Choose Logo','ipido_admin'),
                                'frame_title'  => __('Choose an image','ipido_admin'),
                                'insert_title' => __('Use this logo','ipido_admin'),
                            ),
                        ),
                    ),
                ),

                // Logo Text
                // -----------------------------------------------------------------
                array(
                    'dependency'    => array('logo_type_text','==','true'),
                    'id'            => 'logo_type_text_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'logo_icon',
                            'type'          => 'icon',
                            'title'         => __('Logo Icon','ipido_admin'),
                            'subtitle'      => __('Choose an icon for the logo','ipido_admin'),
                            'default'       => 'cli cli-diamond',
                        ),
                        array(
                            'id'            => 'logo_text',
                            'type'          => 'text',
                            'title'         => __('Logo Text','ipido_admin'),
                            'subtitle'      => __('Enter the text to use in the logo','ipido_admin'),
                            'default'       => __('IPIDO ADMIN <small>White label WordPress Admin Theme</small>','ipido_admin'),
                            'sanitize'      => false,
                        ),
                    ),
                ),

                array(
                    'id'            => 'logo_favicon_status',
                    'type'          => 'switcher',
                    'title'         => __('Favicon Logo','ipido_admin'),
                    'label'         => __('Use custom favicon for admin area','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('logo_favicon_status','==','true'),
                    'id'            => 'logo_devices_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'type'    		=> 'info',
                            'title'         => __('Notice','ipido_admin'),
                            'content'		=> __('We\'ll automatically generate 3 different favicon sizes: 16x16px, 32x32px, 96x96px. <br> To get the best results, upload an image of at least 96x96 pixels.','ipido_admin'),
                        ),
                        array(
                            'id'            => 'logo_favicon',
                            'type'          => 'image',
                            'title'         => __('Favicon Logo Image','ipido_admin'),
                            'subtitle'      => __('Upload an image to use as a favicon','ipido_admin'),
                            'settings'       => array(
                                'button_title' => __('Choose Logo','ipido_admin'),
                                'frame_title'  => __('Choose an image to use as a Favicon','ipido_admin'),
                                'insert_title' => __('Use this logo','ipido_admin'),
                            ),
                        ),
                    ),
                ),

                array(
                    'id'            => 'logo_apple_status',
                    'type'          => 'switcher',
                    'title'         => __('Apple Devices Logo','ipido_admin'),
                    'label'         => __('Use custom logo for Apple devices','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('logo_apple_status','==','true'),
                    'id'            => 'logo_devices_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'type'    		=> 'info',
                            'title'         => __('Notice','ipido_admin'),
                            'content'		=> __('We\'ll automatically generate 9 different device icon sizes: 57x57px, 60x60px, 72x72px, 76x76px, 114x114px, 120x120px, 144x144px, 152x152px, 180x180px. <br> To get the best results, upload an image of at least 180x180 pixels.','ipido_admin'),
                        ),
                        array(
                            'id'            => 'logo_apple',
                            'type'          => 'image',
                            'title'         => __('Apple Devices Logo Image','ipido_admin'),
                            'subtitle'      => __('Upload an image to use as a logo for Apple devices','ipido_admin'),
                            'settings'       => array(
                                'button_title' => __('Choose Logo','ipido_admin'),
                                'frame_title'  => __('Choose an image to use as a Apple devices logo','ipido_admin'),
                                'insert_title' => __('Use this logo','ipido_admin'),
                            ),
                        ),
                    ),
                ),

                array(
                    'id'            => 'logo_android_status',
                    'type'          => 'switcher',
                    'title'         => __('Android Devices Logo','ipido_admin'),
                    'label'         => __('Use custom logo for Android devices','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('logo_android_status','==','true'),
                    'id'            => 'logo_devices_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'type'    		=> 'info',
                            'title'         => __('Notice','ipido_admin'),
                            'content'		=> __('We\'ll automatically generate 6 different device icon sizes: 36x36px, 48x48px, 72x72px, 96x96px, 144x144px, 192x192px. <br> To get the best results, upload an image of at least 192x192 pixels.','ipido_admin'),
                        ),
                        array(
                            'id'            => 'logo_android',
                            'type'          => 'image',
                            'title'         => __('Android Devices Logo Image','ipido_admin'),
                            'subtitle'      => __('Upload an image to use as a logo for Android devices','ipido_admin'),
                            'settings'       => array(
                                'button_title' => __('Choose Logo','ipido_admin'),
                                'frame_title'  => __('Choose an image to use as a Android devices logo','ipido_admin'),
                                'insert_title' => __('Use this logo','ipido_admin'),
                            ),
                        ),
                    ),
                ),
            ), // end: fields
        );


        /* ===============================================================================================
            SITE GENERATOR REPLACEMENT SECURITY
        =============================================================================================== */
        $options['site_generator_security'] = array(
            'name'        => 'site_generator_security',
            'title'       => __('Site Generator Replacement','ipido_admin'),
            'icon'        => 'cli cli-shield',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Site "Generator" Replacement','ipido_admin'),
                ),
                array(
                    'id'        => 'site_generator_status',
                    'type'      => 'switcher',
                    'title'     => __('Site Generator Replacement','ipido_admin'),
                    'label'     => __('Use custom site generator replacement','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'default'   => true,
                ),
                array(
                    'id'        => 'site_generator_visibility',
                    'type'      => 'switcher',
                    'title'     => __('Site Generator Visibility','ipido_admin'),
                    'label'     => __('Completely hide site generator text','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'site_generator_text',
                    'type'      => 'text',
                    'title'     => __('Custom Site Generator Text','ipido_admin'),
                    'subtitle'  => __('Enter the "generator" information text from WordPress to something you prefer.','ipido_admin'),
                    'default'   => __('CastorStudio.com','ipido_admin'),
                ),
                array(
                    'id'        => 'site_generator_version',
                    'type'      => 'text',
                    'title'     => __('Custom Site Generator Version Text','ipido_admin'),
                    'subtitle'  => __('Enter the "generator version" text.','ipido_admin'),
                    'default'   => __('1.2.0','ipido_admin'),
                ),
                array(
                    'id'        => 'site_generator_link',
                    'type'      => 'text',
                    'title'     => __('Custom Site Generator URL','ipido_admin'),
                    'subtitle'  => __('Enter the "generator url" from WordPress to something you prefer.','ipido_admin'),
                    'default'   => __('http://www.castorstudio.com','ipido_admin'),
                ),
                // array(
                //     'dependency'    => array('site_generator_status','==','true'),
                //     'id'            => 'site_generator_fs',
                //     'type'          => 'fieldset',
                //     'fields'        => array(
                //         ,
                //     ),
                // ),
            ),
        );


        /* ===============================================================================================
            PAGE LOADER
        =============================================================================================== */
        $options['page_loader'] = array(
            'name'        => 'page_loader',
            'title'       => __('Page Loader','ipido_admin'),
            'icon'        => 'cli cli-loader',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Page Loader','ipido_admin'),
                ),
                array(
                    'id'        => 'page_loader_status',
                    'type'      => 'switcher',
                    'title'     => __('Page Loader','ipido_admin'),
                    'label'     => __('Use custom page load progress indicator','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),

                array(
                    'id'        => 'page_loader_custom_colors_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Colors','ipido_admin'),
                    'label'     => __('Use custom progress loader colors','ipido_admin'),
                    'info'      => __('Important: By default, the progress bar uses the theme primary and primary light colors.','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('page_loader_custom_colors_status','==','true'),
                    'id'            => 'page_loader_custom_colors_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'        => 'page_loader_color_primary',
                            'type'      => 'color_picker',
                            'title'     => __('Bar Primary Color','ipido_admin'),
                            'default'   => '#9C27B0',
                        ),
                        array(
                            'id'        => 'page_loader_color_secondary',
                            'type'      => 'color_picker',
                            'title'     => __('Bar Secondary Color','ipido_admin'),
                            'default'   => '#E1BEE7',
                        ),
                    ),
                ),
                array(
                    'id'        => 'page_loader_theme',
                    'type'      => 'image_select',
                    'title'     => __('Choose a Theme','ipido_admin'),
                    'options'   => array(
                        'theme-1'       => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-1.png',
                        'theme-2'       => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-2.png',
                        'theme-3'       => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-3.png',
                        'theme-4'       => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-4.png',
                        'theme-5'       => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-5.png',
                        'theme-6'       => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-6.png',
                        'theme-7'       => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-7.png',
                        'theme-8'       => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-8.png',
                        'theme-9'       => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-9.png',
                        'theme-10'      => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-10.png',
                        'theme-11'      => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-11.png',
                        'theme-12'      => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-12.png',
                        'theme-13'      => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-13.png',
                        'theme-14'      => CS_PLUGIN_URI .'/adminframework/assets/images/theme-pace-14.png',
                    ),
                    'radio'     => true,
                    'default'   => 'theme-2',
                ),
                
            ), // end: fields
        );


        /* ===============================================================================================
            USER PROFILE
        =============================================================================================== */
        $options['user_profile'] = array(
            'name'        => 'user_profile',
            'title'       => __('User Profile','ipido_admin'),
            'icon'        => 'cli cli-users',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('User Profile','ipido_admin'),
                ),
                array(
                    'id'        => 'user_profile_status',
                    'type'      => 'switcher',
                    'title'     => __('Personal Settings','ipido_admin'),
                    'label'     => __('Hide all user profile Personal Settings section','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'user_profile_options',
                    'type'      => 'checkbox',
                    'title'     => __('User Profile Personal Settings','ipido_admin'),
                    'subtitle'  => __('Check to hide the section','ipido_admin'),
                    'options'   => array(
                        'editor'    => __('Hide Visual editor','ipido_admin'),
                        'syntaxis'  => __('Hide Syntaxis Highlighting','ipido_admin'),
                        'colors'    => __('Hide Admin colors schemes','ipido_admin'),
                        'shortcuts' => __('Hide Keyboard shortcuts','ipido_admin'),
                        'adminbar'  => __('Hide Admin Bar','ipido_admin'),
                        'language'  => __('Hide Language selector','ipido_admin'),
                    ),
                    'settings'  => array(
                        'style'  => 'icheck',
                    ),
                ),
                
            ), // end: fields
        );


        /* ===============================================================================================
            ADMIN TOP NAVBAR
        =============================================================================================== */
        $options['top_navbar'] = array(
            'name'        => 'top_navbar',
            'title'       => __('Top Navbar','ipido_admin'),
            'icon'        => 'cli cli-header',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Top Navbar','ipido_admin'),
                ),

                array(
                    'type'    	=> 'info',
                    'content'	=> __('To create your own navbar layout, just drag any of the available elements from the bottom panel and drop it into the top panel. Then use drag and drop to rearrange the elements between panels.','ipido_admin'),
                    // 'settings'  => array(
                    //     'type'  => 'info'
                    // ),
                ),
                array(
                    'id'        => 'navbar_elements',
                    'type'      => 'builder_navbar',
                    'elements'  => array(
                        'sidebartoggle'     => array(
                            'name'  => __('Sidebar Toggle','ipido_admin'),
                            'slug'  => 'sidebartoggle',
                        ),
                        'pagetitle'         => array(
                            'name'  => __('Page Title','ipido_admin'),
                            'slug'  => 'pagetitle',
                        ),
                        'flexiblespace'     => array(
                            'name'  => __('<i class="cli cli-arrow-left"></i> Flexible Space <i class="cli cli-arrow-right"></i>','ipido_admin'),
                            'slug'  => 'flexiblespace',
                        ),
                        'networksites' => array(
                            'name'  => __('<i class="cli cli-network-alt1"></i> Network Sites','ipido_admin'),
                            'slug'  => 'networksites',
                        ),
                        'help' => [
                            'name'  => __('Help','ipido_admin'),
                            'slug'  => 'help',
                        ],
                        'screen' => [
                            'name'  => __('Screen Options','ipido_admin'),
                            'slug'  => 'screen',
                        ],
                        'notifications' => [
                            'name'  => __('Notifications','ipido_admin'),
                            'slug'  => 'notifications',
                        ],
                        'site' => [
                            'name'  => __('View Site','ipido_admin'),
                            'slug'  => 'site',
                        ],
                        'updates' => [
                            'name'  => __('Updates','ipido_admin'),
                            'slug'  => 'updates',
                        ],
                        'comments' => [
                            'name'  => __('Comments','ipido_admin'),
                            'slug'  => 'comments',
                        ],
                        'newcontent' => [
                            'name'  => __('New Content','ipido_admin'),
                            'slug'  => 'newcontent',
                        ],
                        'account' => [
                            'name'  => __('User Profile','ipido_admin'),
                            'slug'  => 'account',
                        ],
                    ),
                ),
                array(
                    'id'        => 'navbar_tooltips_status',
                    'type'      => 'switcher',
                    'title'     => __('Navbar Tooltips','ipido_admin'),
                    'label'     => __('Use custom tooltips on navbar items','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'navbar_position',
                    'type'      => 'select',
                    'title'     => __('Navbar Position','ipido_admin'),
                    'options'   => array(
                        'scroll'    => __('Follow Scroll','ipido_admin'),
                        'fixed'     => __('Fixed to Top','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'navbar_titlebar_hidepagetitle',
                    'type'      => 'switcher',
                    'title'     => __('Hide Page Title','ipido_admin'),
                    'label'     => __('Hide the original page title','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'navbar_titlebar_unify',
                    'type'      => 'switcher',
                    'title'     => __('Unify Title Bar','ipido_admin'),
                    'label'     => __('Unify the page title with the toolbar','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'navbar_sidebar_toggle_button_action',
                    'type'      => 'switcher',
                    'title'     => __('Sidebar Toggle Button Action','ipido_admin'),
                    'label'     => __('Always toggle sidebar','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'info'      => __('By default, the button will toggle the sidebar only when is auto-folded or folded using the "collapse menu" sidebar button','ipido_admin'),
                ),
                // array(
                //     'id'        => 'navbar_frontend_status',
                //     'type'      => 'switcher',
                //     'title'     => __('Navbar on Frontend','ipido_admin'),
                //     'label'     => __('Use custom navbar on the frontend','ipido_admin'),
                //     'labels'    => array(
                //         'on'    => __('Yes','ipido_admin'),
                //         'off'   => __('No','ipido_admin'),
                //     ),
                // ),
                
            ), // end: fields
        );


        /* ===============================================================================================
            FOOTER
        =============================================================================================== */
        $options['footer'] = array(
            'name'        => 'footer',
            'title'       => __('Footer','ipido_admin'),
            'icon'        => 'cli cli-footer',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Footer','ipido_admin'),
                ),
                array(
                    'id'        => 'footer_text_status',
                    'type'      => 'switcher',
                    'title'     => __('Footer','ipido_admin'),
                    'label'     => __('Use custom footer text','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'default'   => true,
                ),
                array(
                    'dependency'    => array('footer_text_status','==','true'),
                    'id'            => 'footer_text_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'        => 'footer_text_visibility',
                            'type'      => 'switcher',
                            'title'     => __('Footer Text Visibility','ipido_admin'),
                            'label'     => __('Hide footer text','ipido_admin'),
                            'labels'    => array(
                                'on'    => __('Yes','ipido_admin'),
                                'off'   => __('No','ipido_admin'),
                            ),
                        ),
                        array(
                            'id'        => 'footer_text',
                            'type'      => 'wysiwyg',
                            'title'     => __('Custom Footer Text','ipido_admin'),
                            'subtitle'  => __('Enter the text that displays in the footer bar. HTML markup can be used.','ipido_admin'),
                            'default'   => __('IPIDO Admin Powered by <a href="http://www.castorstudio.com" target="_blank">CastorStudio</a>','ipido_admin'),
                            'settings'  => array(
                                'textarea_rows' => 5,
                                'tinymce'       => true,
                                'media_buttons' => false,
                                'quicktags'     => false,
                                'teeny'         => true,
                            ),
                        ),
                    ),
                ),
                array(
                    'id'            => 'footer_version_status',
                    'type'          => 'switcher',
                    'title'         => __('Footer Version','ipido_admin'),
                    'label'         => __('Use custom footer version text','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'default'       => true,
                ),
                array(
                    'dependency'    => array('footer_version_status','==','true'),
                    'id'            => 'footer_version_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'        => 'footer_version_visibility',
                            'type'      => 'switcher',
                            'title'     => __('Footer Version Text Visibility','ipido_admin'),
                            'label'     => __('Hide footer version text','ipido_admin'),
                            'labels'    => array(
                                'on'    => __('Yes','ipido_admin'),
                                'off'   => __('No','ipido_admin'),
                            ),
                        ),
                        array(
                            'id'        => 'footer_version',
                            'type'      => 'wysiwyg',
                            'title'     => __('Custom Version Text','ipido_admin'),
                            'subtitle'  => __('Enter the text that displays in the footer version bar. HTML markup can be used.','ipido_admin'),
                            'default'   => sprintf(__('<a href="http://www.castorstudio.com/ipido-admin-wordpress-white-label-admin-theme" target="_blank">%s</a>','ipido_admin'),IPIDO_ADMIN_VERSION),
                            'settings'  => array(
                                'textarea_rows' => 5,
                                'tinymce'       => true,
                                'media_buttons' => false,
                                'quicktags'     => false,
                                'teeny'         => true,
                            ),
                        ),
                    ),
                ),
            ), // end: fields
        );


        /* ===============================================================================================
            CUSTOM CSS
        =============================================================================================== */
        $options['customcss'] = array(
            'name'        => 'customcss',
            'title'       => __('Custom CSS','ipido_admin'),
            'icon'        => 'cli cli-code',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Custom CSS','ipido_admin'),
                ),
                array(
                    'id'        => 'customcss_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom CSS','ipido_admin'),
                    'label'     => __('Use custom CSS code','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'customcss',
                    'type'      => 'code_editor',
                    'title'     => __('Custom CSS','ipido_admin'),
                    'subtitle'  => __('The code you paste here will be applied in all your admin and login area.','ipido_admin'),
                    'info'      => __('Information: If you need to overwrite any CSS setting, you can add !important at the end of CSS property. eg: margin: 10px !important;','ipido_admin'),
                    'attributes'  => array(
                        'data-theme'    => 'monokai',  // the theme for ACE Editor
                        'data-mode'     => 'css',     // the language for ACE Editor
                    ),
                )
            ),
        );


        /* ===============================================================================================
            GENERAL SETTINGS
        =============================================================================================== */
        $options['generalsettings'] = array(
            'name'        => 'generalsettings',
            'title'       => __('General Settings','ipido_admin'),
            'icon'        => 'cli cli-settings',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('General Settings','ipido_admin'),
                ),
                array(
                    'id'        => 'bodyscrollbar_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Body Scrollbars','ipido_admin'),
                    'subtitle'  => __('Same as sidebar scrollbar','ipido_admin'),
                    'label'     => __('Use custom body scrollbar','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'resetsettings_status',
                    'type'      => 'switcher',
                    'title'     => __('Reset Admin Settings','ipido_admin'),
                    'subtitle'  => __('When you deactivate the plugin all your preferences will be deleted or reset to their default value.','ipido_admin'),
                    'label'     => __('Reset admin settings on plugin deactivation','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'id'        => 'rightnowwidget_status',
                    'type'      => 'switcher',
                    'title'     => __('Admin Name on Right Now Dashboard Widget','ipido_admin'),
                    'label'     => __('Hide the IPIDO Admin version on Dashboard','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'default'   => false,
                ),
            ),
        );


        /* ===============================================================================================
            NETWORK ADMIN
        =============================================================================================== */
        if (is_super_admin() && is_multisite()){
            $options['network_settings'] = array(
                'name'        => 'network_settings',
                'title'       => __('Network Settings','ipido_admin'),
                'icon'        => 'cli cli-network-alt2',
                
                // begin: fields
                'fields'      => array(
                    array(
                        'type'    => 'heading',
                        'content' => __('Network Settings','ipido_admin'),
                    ),

                    array(
                        'type'      => 'subheading',
                        'content'   => __('Network Sites Sidebar','ipido_admin'),
                    ),
                    array(
                        'id'        => 'network_sidebar_position',
                        'type'      => 'image_select',
                        'title'     => __('Sidebar Position','ipido_admin'),
                        'options'   => array(
                            'left'      => CS_PLUGIN_URI .'/adminframework/assets/images/network-sidebar-left.png',
                            'right'     => CS_PLUGIN_URI .'/adminframework/assets/images/network-sidebar-right.png',
                        ),
                        'default'   => 'left',
                    ),
                    array(
                        'id'        => 'network_sidebar_sorting',
                        'type'      => 'select',
                        'title'     => __('Sites Sorting','ipido_admin'),
                        'options'   => array(
                            'none'  => __('None','ipido_admin'),
                            'asc'   => __('Ascending Sort','ipido_admin'),
                            'desc'  => __('Descending Sort','ipido_admin'),
                        ),
                    ),
                    array(
                        'id'        => 'network_sidebar_sorting_mainsite',
                        'type'      => 'switcher',
                        'title'     => __('Main Site Sort','ipido_admin'),
                        'label'     => __('Exclude main site from sites sorting','ipido_admin'),
                        'labels'    => array(
                            'on'    => __('Yes','ipido_admin'),
                            'off'   => __('No','ipido_admin'),
                        ),
                    ),
                    array(
                        'id'        => 'network_sidebar_searchfield_status',
                        'type'      => 'switcher',
                        'title'     => __('Search Field','ipido_admin'),
                        'label'     => __('Use search field on the sidebar','ipido_admin'),
                        'labels'    => array(
                            'on'    => __('Yes','ipido_admin'),
                            'off'   => __('No','ipido_admin'),
                        ),
                    ),
                ),
            );
        }


        /* ===============================================================================================
            MODULES
        =============================================================================================== */
        $options['modules'] = array(
            'name'        => 'modules',
            'title'       => __('Modules','ipido_admin'),
            'icon'        => 'cli cli-package',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Modules','ipido_admin'),
                ),
                array(
                    'type'    => 'content',
                    'content' => __('With modules you can extend the functionality of IPIDO Admin','ipido_admin'),
                ),
                array(
                    'id'            => 'modules',
                    'type'          => 'image_select',
                    'options'       => Ipido_admin_Admin::get_modules(),
                    'multi_select'  => true,
                    'wrap_class'    => 'csf-flex-row',
                ),
                  
            ),
        );


        /* ===============================================================================================
            BACKUP
        =============================================================================================== */
        // $options[]   = array(
        //     'name'     => 'backup_section',
        //     'title'    => 'Backup',
        //     'icon'     => 'cli cli-shield',
        //     'fields'   => array(
        //         array(
        //             'type'    => 'heading',
        //             'content' => __('Backup','ipido_admin'),
        //         ),
        //         array(
        //             'type'    => 'notice',
        //             'class'   => 'warning',
        //             'content' => __('You can save your current options. Download a Backup and Import.','ipido_admin'),
        //         ),
        //         array(
        //             'type'    => 'backup',
        //         ),
        //     ),
        // );


        return $options;



    }
}

$myframework = new CSFramework( $settings );
$myOptions = new cs_ipido_admin_settings();

$fn = function() use($myframework,$myOptions){
    $options = $myOptions->set_options();
    $myframework->set_options($options);
};
add_action( 'admin_menu', $fn, 100 );