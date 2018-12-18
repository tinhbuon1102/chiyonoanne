<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK SETTINGS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================

class CS_Admin_Module_HTM_settings{

    public function set_options(){
        // ===============================================================================================
        // -----------------------------------------------------------------------------------------------
        // FRAMEWORK OPTIONS
        // -----------------------------------------------------------------------------------------------
        // ===============================================================================================
        // $options        = array();


        /* ===============================================================================================
            CUSTOM HELP TABS MANAGER
           =============================================================================================== */
        $options    = array(
            'name'        => 'customhelptabs',
            'title'       => __('Custom Help Tabs Manager','ipido_admin'),
            'icon'        => 'cli cli-help-circle',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Custom Help Tabs Manager','ipido_admin'),
                ),
                array(
                    'type'    => 'content',
                    'content' => __('"Help Tabs" helps users on how to exactly use any settings page by giving them more information in the help tab.','ipido_admin'),
                ),
                array(
                    'id'        => 'helptabs_status',
                    'type'      => 'switcher',
                    'title'     => __('Help Tabs','ipido_admin'),
                    'label'     => __('Use custom Help Tabs','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'id'              => 'helptabs_container',
                    'type'            => 'group',
                    // 'title'           => 'Group Field',
                    // 'desc'            => 'Accordion title using the ID of the field.',
                    'button_title'    => __('Add Page','ipido_admin'),
                    'accordion_title' => __('New Help Tabs','ipido_admin'),
                    'fields'          => array(
                        array(
                            'id'        => 'helptab_page',
                            'type'      => 'select',
                            'title'     => __('Page','ipido_admin'),
                            'options'   => Ipido_admin_Module_Help_Tabs_Manager::cs_ipido_get_menu_pages(),
                            'default_option'   => __('Choose a page','ipido_admin'),
                        ),
                        array(
                            'id'        => 'helptab_remove_original',
                            'type'      => 'switcher',
                            'title'     => __('Remove Original Help','ipido_admin'),
                            'label'     => __('Remove all original previous help tabs from this page','ipido_admin'),
                            'labels'    => array(
                                'on'    => __('Yes','ipido_admin'),
                                'off'   => __('No','ipido_admin'),
                            ),
                        ),
                        array(
                            'id'        => 'helptab_custom_sidebar',
                            'type'      => 'switcher',
                            'title'     => __('Custom Sidebar','ipido_admin'),
                            'label'     => __('Use custom sidebar info','ipido_admin'),
                            'labels'    => array(
                                'on'    => __('Yes','ipido_admin'),
                                'off'   => __('No','ipido_admin'),
                            ),
                        ),
                        array(
                            'dependency'    => array('helptab_custom_sidebar','==','true'),
                            'id'        => 'helptab_sidebar_content',
                            'type'      => 'wysiwyg',
                            'title'     => 'Custom Sidebar Help Tab Content',
                            'settings'  => array(
                                'textarea_rows' => 5,
                                'tinymce'       => true,
                                'media_buttons' => false,
                                'quicktags'     => false,
                                'teeny'         => true,
                            ),
                        ),
                        array(
                            'id'              => 'helptab_items',
                            'type'            => 'group',
                            // 'title'           => 'Group Field',
                            // 'desc'            => 'Accordion title using the ID of the field.',
                            'button_title'    => __('Add New Help Tab','ipido_admin'),
                            'accordion_title' => __('New Help Tab','ipido_admin'),
                            'fields'          => array(
                                array(
                                    'id'            => 'tab_title',
                                    'type'          => 'text',
                                    'title'         => __('Help Tab Title','ipido_admin'),
                                ),
                                array(
                                    'id'            => 'tab_content',
                                    'type'          => 'wysiwyg',
                                    'title'         => __('Help Tab Content','ipido_admin'),
                                    'settings'      => array(
                                        'textarea_rows' => 5,
                                        'tinymce'       => true,
                                        'media_buttons' => false,
                                        'quicktags'     => false,
                                        'teeny'         => true,
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
                
            ),
        );


        return $options;

    }
}



/**
 * Unique Options Page
 */
// $module_settings    = new CSFramework( $settings );
// $module_options     = new CS_Admin_Module_HTM_settings();
// $fn = function() use($module_settings,$module_options){
//     $options = $module_options->set_options();
//     $module_settings->set_options($options);
// };
// add_action( 'admin_menu', $fn );


/**
 * Extend Framework Options
 */
$module_options     = new CS_Admin_Module_HTM_settings();
$fn = function($options) use($module_options){
    // if ($unique == 'cs_ipidoadmin_settings'){
        $new_options = $module_options->set_options();
        // cs_array_insert($options,'logo', array('customhelptabs' => $new_options));
        $options['customhelptabs'] = $new_options;
    // }
    
    return $options;
};
add_filter('csf_settings_cs_ipidoadmin_settings_options',$fn,10);