<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
// ===============================================================================================
// -----------------------------------------------------------------------------------------------
// FRAMEWORK SETTINGS
// -----------------------------------------------------------------------------------------------
// ===============================================================================================
class CS_Admin_Module_LPM_settings{

    public function set_options(){
        // ===============================================================================================
        // -----------------------------------------------------------------------------------------------
        // FRAMEWORK OPTIONS
        // -----------------------------------------------------------------------------------------------
        // ===============================================================================================
        $options        = array();


        /* ===============================================================================================
            LOGIN PAGE GENERAL SETTINGS
        =============================================================================================== */
        $options['general'] = array(
            'name'        => 'general',
            'title'       => __('General','ipido_admin'),
            'icon'        => 'cli cli-log-in',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('General Settings','ipido_admin'),
                ),

                array(
                    'id'        => 'login_page_status',
                    'type'      => 'switcher',
                    'title'     => __('Login Page','ipido_admin'),
                    'label'     => __('Use custom login page theme','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'default'   => true
                ),
                
                // Login Screen Logo
                // -----------------------------------------------------------------
                array(
                    'id'            => 'logo_image_login',
                    'type'          => 'image',
                    'title'         => __('Login Page Logo','ipido_admin'),
                    'desc'          => __('Upload your own logo of 350px * 100px (width * height).','ipido_admin'),
                    'settings'      => array(
                        'button_title'  => __('Choose Logo','ipido_admin'),
                        'frame_title'   => __('Choose an image','ipido_admin'),
                        'insert_title'  => __('Use this logo','ipido_admin'),
                        'preview_size'  => 'medium',
                    ),
                ),
                array(
                    'id'            => 'login_page_title_status',
                    'type'          => 'switcher',
                    'title'         => __('Page Title','ipido_admin'),
                    'label'         => __('Use custom login page title','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'default'       => true
                ),
                array(
                    'dependency'    => array('login_page_title_status','==','true'),
                    'id'            => 'login_page_title',
                    'type'          => 'text',
                    'title'         => __('Page Title Text','ipido_admin'),
                    'desc'          => __('This is the "title" meta tag.','ipido_admin'),
                    'default'       => __('IPIDO Admin - Whitelabel WordPress Admin Theme','ipido_admin'),
                    'wrap_class'	=> 'csf-field-subfield',
                ),

                array(
                    'id'            => 'login_logo_url_status',
                    'type'          => 'switcher',
                    'title'         => __('Logo URL','ipido_admin'),
                    'label'         => __('Use custom login logo url','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_logo_url_status','==','true'),
                    'id'            => 'login_logo_url',
                    'type'          => 'text',
                    'title'         => __('Logo URL','ipido_admin'),
                    'desc'          => __('This is the URL to which the logo points','ipido_admin'),
                    'after'         => __('<p class="csf-text-muted">By default this url is your bloginfo url.</p>','ipido_admin'),
                    'wrap_class'	=> 'csf-field-subfield',
                ),

                array(
                    'id'            => 'login_logo_url_title_status',
                    'type'          => 'switcher',
                    'title'         => __('Logo Title','ipido_admin'),
                    'label'         => __('Use custom logo title','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_logo_url_title_status','==','true'),
                    'id'            => 'login_logo_url_title',
                    'type'          => 'text',
                    'title'         => __('Logo Title Text','ipido_admin'),
                    'desc'          => __('This is simply ALT text for the logo.','ipido_admin'),
                    'wrap_class'	=> 'csf-field-subfield',
                ),

                array(
                    'id'        => 'login_page_error_shake',
                    'type'      => 'switcher',
                    'title'     => __('Login Error Shake','ipido_admin'),
                    'desc'      => __('When you enter an incorrect username or password, the login form shakes to alert the user they need to try again.','ipido_admin'),
                    'label'     => __('Remove the error shake effect','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),



                array(
                    'type'      => 'subheading',
                    'content'   => __('Login Page Links','ipido_admin'),
                ),

                array(
                    'id'            => 'login_page_rememberme_status',
                    'type'          => 'switcher',
                    'title'         => __('Remember Me','ipido_admin'),
                    'label'         => __('Use custom "Remember Me" text','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUGIN_URI .'/modules/login_page_manager/images/login-rememberme.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_rememberme_status','==','true'),
                    'id'            => 'login_page_rememberme_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_rememberme_visibility',
                            'type'          => 'switcher',
                            'title'         => __('Link Visibility','ipido_admin'),
                            'label'         => __('Hide "Remember me" link','ipido_admin'),
                            'labels'        => array(
                                'on'    => __('Yes','ipido_admin'),
                                'off'   => __('No','ipido_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'login_page_rememberme',
                            'type'          => 'text',
                            'title'         => __('Remember Me Text','ipido_admin'),
                            'default'       => __('Keep session active','ipido_admin'),
                        ),
                    ),
                ),
                array(
                    'id'            => 'login_page_link_back_status',
                    'type'          => 'switcher',
                    'title'         => __('Back to main site','ipido_admin'),
                    'label'         => __('Use custom link options','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUGIN_URI .'/modules/login_page_manager/images/login-backtosite.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_link_back_status','==','true'),
                    'id'            => 'login_page_link_back_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_link_back_visibility',
                            'type'          => 'switcher',
                            'title'         => __('Link Visibility','ipido_admin'),
                            'label'         => __('Hide "Back to main site" link','ipido_admin'),
                            'labels'        => array(
                                'on'    => __('Yes','ipido_admin'),
                                'off'   => __('No','ipido_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'login_page_link_back',
                            'type'          => 'text',
                            'title'         => __('Link Text','ipido_admin'),
                            'after'         => __('<p class="csf-text-muted">Use %s as a site name wildcard</p>','ipido_admin'),
                            'default'       => __('Go to Homepage','ipido_admin'),
                        ),
                    )
                ),
                
                array(
                    'id'            => 'login_page_link_lostpassword_status',
                    'type'          => 'switcher',
                    'title'         => __('Lost your password?','ipido_admin'),
                    'label'         => __('Use custom link options','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUGIN_URI .'/modules/login_page_manager/images/login-lostpassword.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_link_lostpassword_status','==','true'),
                    'id'            => 'login_page_link_lostpassword_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_link_lostpassword_visibility',
                            'type'          => 'switcher',
                            'title'         => __('Link Visibility','ipido_admin'),
                            'label'         => __('Hide "Lost your password?" link','ipido_admin'),
                            'labels'        => array(
                                'on'    => __('Yes','ipido_admin'),
                                'off'   => __('No','ipido_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'login_page_link_lostpassword',
                            'type'          => 'text',
                            'title'         => __('Link Text','ipido_admin'),
                            'default'       => __('Lost your password? Click here','ipido_admin'),
                        ),
                    )
                ),

                array(
                    'id'            => 'login_page_link_register_status',
                    'type'          => 'switcher',
                    'title'         => __('Register','ipido_admin'),
                    'label'         => __('Use custom link text','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUGIN_URI .'/modules/login_page_manager/images/login-register.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_link_register_status','==','true'),
                    'id'            => 'login_page_link_register_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'type'          => 'info',
                            'content'       => __('To change the visibility of this link, you must enable/disable "Anyone can register" Membership option under the <a href="wp-admin/options-general.php">General Settings</a> page'),
                        ),
                        array(
                            'id'            => 'login_page_link_register',
                            'type'          => 'text',
                            'title'         => __('Link Text','ipido_admin'),
                            'default'       => __('Sign Up Now','ipido_admin'),
                        ),
                    ),
                ),

                array(
                    'id'            => 'login_page_link_login_status',
                    'type'          => 'switcher',
                    'title'         => __('Login','ipido_admin'),
                    'label'         => __('Use custom link text','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUGIN_URI .'/modules/login_page_manager/images/login-log-in.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_link_login_status','==','true'),
                    'id'            => 'login_page_link_login_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_link_login_visibility',
                            'type'          => 'switcher',
                            'title'         => __('Link Visibility','ipido_admin'),
                            'label'         => __('Hide "Login" link','ipido_admin'),
                            'labels'        => array(
                                'on'    => __('Yes','ipido_admin'),
                                'off'   => __('No','ipido_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'login_page_link_login',
                            'type'          => 'text',
                            'title'         => __('Link Text','ipido_admin'),
                            'default'       => __('Back to Sign in','ipido_admin'),
                        ),
                    ),
                ),

                array(
                    'id'            => 'login_page_button_login_status',
                    'type'          => 'switcher',
                    'title'         => __('Login Button','ipido_admin'),
                    'label'         => __('Use custom login button style','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUGIN_URI .'/modules/login_page_manager/images/login-loginbtn.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_button_login_status','==','true'),
                    'id'            => 'login_page_button_login',
                    'type'          => 'text',
                    'title'         => __('Button Text','ipido_admin'),
                    'default'       => __('Test IPIDO Admin now!','ipido_admin'),
                    'wrap_class'	=> 'csf-field-subfield',
                ),


                array(
                    'id'            => 'login_page_button_getnewpassword_status',
                    'type'          => 'switcher',
                    'title'         => __('Get New Password Button','ipido_admin'),
                    'label'         => __('Use custom button text','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'help'          => array(
                        'type'      => 'image',
			            'position'  => 'top',
                        'content'   => CS_PLUGIN_URI .'/modules/login_page_manager/images/login-getnewpasswordbtn.png',
                    ),
                ),
                array(
                    'dependency'    => array('login_page_button_getnewpassword_status','==','true'),
                    'id'            => 'login_page_button_getnewpassword',
                    'type'          => 'text',
                    'title'         => __('Button Text','ipido_admin'),
                    'default'       => __('Get New Password','ipido_admin'),
                    'wrap_class'	=> 'csf-field-subfield',
                ),







                array(
                    'type'      => 'subheading',
                    'content'   => __('Messages','ipido_admin'),
                ),

                array(
                    'id'            => 'login_page_login_message_status',
                    'type'          => 'switcher',
                    'title'         => __('Login Message','ipido_admin'),
                    'label'         => __('Use custom login message','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_page_login_message_status','==','true'),
                    'id'            => 'login_page_login_message_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_login_message_style',
                            'type'          => 'switcher',
                            'title'         => __('Message Box Style','ipido_admin'),
                            'label'         => __('Apply frozen glass style to the message box','ipido_admin'),
                            'labels'        => array(
                                'on'    => __('Yes','ipido_admin'),
                                'off'   => __('No','ipido_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'login_page_login_message_color',
                            'type'          => 'color_picker',
                            'title'         => __('Login Message Text Color','ipido_admin'),
                            'default'       => 'rgba(255,255,255,0.8)',
                        ),
                        array(
                            'id'            => 'login_page_login_message',
                            'type'          => 'wysiwyg',
                            'title'         => __('Login Message Text','ipido_admin'),
                            'desc'          => __('Enter a custom text to show on the login screen.','ipido_admin'),
                            'default'       => __('Welcome back to IPIDO Admin. Please login using the user credentials below:<br><strong>Username:</strong> demo <strong>Password:</strong> demo','ipido_admin'),
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

                array(
                    'id'            => 'login_page_logout_message_status',
                    'type'          => 'switcher',
                    'title'         => __('Logout Message','ipido_admin'),
                    'label'         => __('Use custom logout message','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_page_logout_message_status','==','true'),
                    'id'            => 'login_page_button_login_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_page_logout_message_visibility',
                            'type'          => 'switcher',
                            'title'         => __('Message Visibility','ipido_admin'),
                            'label'         => __('Hide loggedout message','ipido_admin'),
                            'labels'        => array(
                                'on'    => __('Yes','ipido_admin'),
                                'off'   => __('No','ipido_admin'),
                            ),
                        ),
                        array(
                            'id'            => 'login_page_logout_message',
                            'type'          => 'wysiwyg',
                            'title'         => __('Logout Message Text','ipido_admin'),
                            'desc'          => __('Enter a text to show on the logout screen.','ipido_admin'),
                            'default'       => __('Now you\'re out','ipido_admin'),
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
                array(
                    'id'            => 'login_page_invalid_username_status',
                    'type'          => 'switcher',
                    'title'         => __('Invalid Username Message','ipido_admin'),
                    'label'         => __('Use custom invalid username message','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_page_invalid_username_status','==','true'),
                    'id'            => 'login_page_invalid_username',
                    'type'          => 'wysiwyg',
                    'title'         => __('Invalid Username Message Text','ipido_admin'),
                    'desc'          => __('Enter a text to show when entering an incorrect username.','ipido_admin'),
                    'default'       => __('<strong>ERROR</strong>: Invalid username.','ipido_admin'),
                    'settings'      => array(
                        'textarea_rows' => 5,
                        'tinymce'       => true,
                        'media_buttons' => false,
                        'quicktags'     => false,
                        'teeny'         => true,
                    ),
                    'wrap_class'	=> 'csf-field-subfield',
                ),

                array(
                    'id'            => 'login_page_invalid_password_status',
                    'type'          => 'switcher',
                    'title'         => __('Invalid Password Message','ipido_admin'),
                    'label'         => __('Use custom invalid password message','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_page_invalid_password_status','==','true'),
                    'id'            => 'login_page_invalid_password',
                    'type'          => 'wysiwyg',
                    'title'         => __('Invalid Password Message Text','ipido_admin'),
                    'desc'          => __('Enter a text to show when entering an incorrect password.','ipido_admin'),
                    'default'       => __('<strong>ERROR</strong>: The password you entered is incorrect.','ipido_admin'),
                    'settings'      => array(
                        'textarea_rows' => 5,
                        'tinymce'       => true,
                        'media_buttons' => false,
                        'quicktags'     => false,
                        'teeny'         => true,
                    ),
                    'wrap_class'	=> 'csf-field-subfield',
                ),

                array(
                    'id'            => 'login_page_invalid_captcha_status',
                    'type'          => 'switcher',
                    'title'         => __('Invalid Captcha Message','ipido_admin'),
                    'label'         => __('Use custom invalid captcha message','ipido_admin'),
                    'labels'        => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_page_invalid_captcha_status','==','true'),
                    'id'            => 'login_page_invalid_captcha',
                    'type'          => 'wysiwyg',
                    'title'         => __('Invalid Captcha Message Text','ipido_admin'),
                    'desc'          => __('Enter a text to show when entering an incorrect captcha.','ipido_admin'),
                    'default'       => __('<strong>ERROR</strong>: The captcha you entered is incorrect.','ipido_admin'),
                    'settings'      => array(
                        'textarea_rows' => 5,
                        'tinymce'       => true,
                        'media_buttons' => false,
                        'quicktags'     => false,
                        'teeny'         => true,
                    ),
                    'wrap_class'	=> 'csf-field-subfield',
                ),
                
            ), // end: fields
        );


        /* ===============================================================================================
            LOGIN PAGE THEMES
        =============================================================================================== */
        $options['themes'] = array(
            'name'        => 'themes',
            'title'       => __('Themes','ipido_admin'),
            'icon'        => 'cli cli-droplet',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Login Page Themes','ipido_admin'),
                ),
                array(
                    'type'    => 'content',
                    'content' => __('Choose a theme and customize your login screen as you want!','ipido_admin'),
                ),

                array(
                    'id'			=> 'theme',
                    'type'			=> 'image_select',
                    // 'title'			=> __('Theme','ipido_admin'),
                    'radio'			=> true,
                    'options'		=> Ipido_admin_Module_Login_Page_Manager::get_login_themes(),
                    'default'   	=> 'corporate',
                ),
                array(
                    'id'			=> 'theme_settings',
                    'type'			=> 'fieldset',
                    'fields'		=> Ipido_admin_Module_Login_Page_Manager::get_login_themes_settings(),
                ),
            ),
        );


        /* ===============================================================================================
            LOGIN PAGE SECURITY
        =============================================================================================== */
        $options['login_page_security'] = array(
            'name'        => 'login_page_security',
            'title'       => __('Login & Logout Redirect','ipido_admin'),
            'icon'        => 'cli cli-shield',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Login & Logout Redirect','ipido_admin'),
                ),

                array(
                    'id'        => 'login_security_custom_login_url_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Login URL','ipido_admin'),
                    'label'     => __('Use custom login URL','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'default'   => true
                ),
                array(
                    'dependency'    => array('login_security_custom_login_url_status','==','true'),
                    'id'            => 'login_security_custom_login_url_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_security_custom_login_slug',
                            'type'          => 'text',
                            'title'         => __('Login URL slug','ipido_admin'),
                            'default'       => __('ipido-admin-login','ipido_admin'),
                            'after'         => __('<p class="csf-text-muted">Important: Your new login url will be in this format: http://www.yoursite.com/your-new-login-url-slug/</p>','ipido_admin'),
                        ),
                    ),
                ),

                array(
                    'id'        => 'login_security_custom_login_redirect_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Login Redirect','ipido_admin'),
                    'label'     => __('Redirect users to a custom page after login','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_security_custom_login_redirect_status','==','true'),
                    'id'            => 'login_security_custom_login_redirect_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_security_custom_login_redirect_roles',
                            'type'          => 'custom_userrole',
                            'title'         => __('Redirect by User Role','ipido_admin'),
                        ),
                    ),
                ),

                array(
                    'id'        => 'login_security_custom_logout_url_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Logout URL','ipido_admin'),
                    'label'     => __('Use custom logout URL','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                    'default'   => true
                ),
                array(
                    'dependency'    => array('login_security_custom_logout_url_status','==','true'),
                    'id'            => 'login_security_custom_logout_url_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_security_custom_logout_slug',
                            'type'          => 'text',
                            'title'         => __('Logout URL slug','ipido_admin'),
                            'default'       => __('ipido-admin-logout','ipido_admin'),
                            'after'         => __('<p class="csf-text-muted">Important: Your new logout url will be in this format: http://www.yoursite.com/your-new-logout-url-slug/</p>','ipido_admin'),
                        ),
                    ),
                ),

                array(
                    'id'        => 'login_security_custom_logout_redirect_status',
                    'type'      => 'switcher',
                    'title'     => __('Custom Logout Redirect','ipido_admin'),
                    'label'     => __('Redirect users to a custom page after logout','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'dependency'    => array('login_security_custom_logout_redirect_status','==','true'),
                    'id'            => 'login_security_custom_logout_redirect_fs',
                    'type'          => 'fieldset',
                    'fields'        => array(
                        array(
                            'id'            => 'login_security_custom_logout_redirect_roles',
                            'type'          => 'custom_userrole',
                            'title'         => __('Redirect by User Role','ipido_admin'),
                        ),
                    ),
                ),
            ),
        );


        /* ===============================================================================================
            LOGIN PAGE RECAPTCHA
        =============================================================================================== */
        $options['recaptcha'] = array(
            'name'        => 'recaptcha',
            'title'       => __('reCAPTCHA nocaptcha','ipido_admin'),
            'icon'        => 'cli cli-lock',
            
            // begin: fields
            'fields'      => array(
                array(
                    'type'    => 'heading',
                    'content' => __('Login Page reCAPTCHA','ipido_admin'),
                ),

                array(
                    'id'        => 'login_page_recaptcha_status',
                    'type'      => 'switcher',
                    'title'     => __('reCAPTCHA','ipido_admin'),
                    'label'     => __('Use reCAPTCHA ','ipido_admin'),
                    'labels'    => array(
                        'on'    => __('Yes','ipido_admin'),
                        'off'   => __('No','ipido_admin'),
                    ),
                ),
                array(
                    'type'      => 'info',
                    'content'   => __('With the default test keys, you will always get No CAPTCHA and all verification requests will pass. Please <a href="https://www.google.com/recaptcha/admin" target="_blank">register a new key</a>','ipido_admin'),
                ),
                array(
                    'id'        => 'login_page_recaptcha_sitekey',
                    'type'      => 'text',
                    'title'     => __('Site Key','ipido_admin'),
                    'desc'      => __('Used in the HTML code that shows your site to users.','ipido_admin'),
                    'default'   => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
                    // 'validate'  => 'required',
                ),
                array(
                    'id'        => 'login_page_recaptcha_secretkey',
                    'type'      => 'text',
                    'title'     => __('Secret Key','ipido_admin'),
                    'desc'      => __('Used for communications between your site and Google. Be careful not to reveal it to anyone.','ipido_admin'),
                    'default'   => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
                    // 'validate'  => 'required',
                ),
                array(
                    'id'        => 'login_page_recaptcha_forms',
                    'type'      => 'checkbox',
                    'title'     => __('Forms to protect','ipido_admin'),
                    'options'   => array(
                        'login'     => __('Login Form','ipido_admin'),
                        'lostpw'    => __('Lost Password Form','ipido_admin'),
                        'register'  => __('Register Form','ipido_admin'),
                    ),
                    'settings'  => array(
                        'style' => 'labeled'
                    ),
                ),
                array(
                    'id'        => 'login_page_recaptcha_theme',
                    'type'      => 'image_select',
                    'title'     => __('Theme','ipido_admin'),
                    'options'   => array(
                        'light' => CS_PLUGIN_URI .'/modules/login_page_manager/images/theme-light.png',
                        'dark'  => CS_PLUGIN_URI .'/modules/login_page_manager/images/theme-dark.png',
                    ),
                    'radio'     => true,
                    'default'   => 'light',
                ),
                array(
                    'id'        => 'login_page_recaptcha_size',
                    'type'      => 'image_select',
                    'title'     => __('Size','ipido_admin'),
                    'options'   => array(
                        'normal'    => CS_PLUGIN_URI .'/modules/login_page_manager/images/theme-light.png',
                        'compact'   => CS_PLUGIN_URI .'/modules/login_page_manager/images/size-compact.png',
                    ),
                    'radio'     => true,
                    'default'   => 'normal',
                ),
                // To-do: Live test API Key
                // array(
                //     'type'      => 'content',
                //     'title'     => 'Test API Key',
                //     'content'   => 'here',
                // ),
            ),
        );


        return $options;

    }
}



/**
 * Unique Options Page
 */
$settings = array(
    'menu_type'             => 'submenu', // menu, submenu, options, theme, etc.
    'menu_parent'           => 'cs-ipido-admin-settings',
    'menu_title'            => __('Login Page Manager','ipido_admin'),
    'menu_slug'             => 'cs-ipido-admin-login-page-manager-settings',
    'menu_capability'       => 'manage_options',
    'menu_icon'             => 'dashicon-shield',
    'menu_position'         => null,
    'show_submenus'         => true,
    'framework_title'       => __('Login Page Manager','ipido_admin'),
    'framework_subtitle'    => __('v1.0.0','ipido_admin'),
    'ajax_save'             => true,
    'buttons'               => array('reset' => false),
    'option_name'           => 'cs_ipido_admin_lpm_settings',
    'override_location'     => '',
    'extra_css'             => array(),
    'extra_js'              => array(),
    'is_single_page'        => true,
    'is_sticky_header'      => false,
    'style'                 => 'modern',
    'help_tabs'             => array(),
    'show_all_options_link'	=> false,
);
$module_settings    = new CSFramework( $settings );
$module_options     = new CS_Admin_Module_LPM_settings();
$fn = function() use($module_settings,$module_options){
    $options = $module_options->set_options();
    $module_settings->set_options($options);
};
add_action( 'admin_menu', $fn );


/**
 * Extend Framework Options
 */
// $module_options     = new CS_Admin_Module_LPM_settings();
// $fn = function($options) use($module_options){
//     // if ($unique == 'cs_ipidoadmin_settings'){
//         $new_options = $module_options->set_options();
//         array_insert($options,2, $new_options);
//         // $options = array_merge($options,$new_options);
//     // }
    
//     return $options;
// };
// add_filter('csf_settings_cs_ipidoadmin_settings_options',$fn,10);