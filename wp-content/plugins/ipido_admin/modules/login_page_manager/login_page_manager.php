<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

class Ipido_admin_Module_Login_Page_Manager extends Ipido_admin_Module{
	private $_captcha_instance = null;



    public function __construct() {
		parent::__construct();
		
        $this->name     	= 'login_page_manager';
		$this->version  	= '1.0.0';
		$this->plugin_name 	= 'ipido_admin_login_page_manager';
		$this->unique 		= 'cs_ipido_admin_lpm_settings';
    }
	
    public function init(){
		$this->load_dependencies();
        $this->define_admin_hooks();
		$this->define_public_hooks();
    }
	
    private function load_dependencies(){
		require_once( 'includes/class-ipido_admin-login-theme.php' );
		require_once( 'includes/class-ipido_admin-recaptcha.php'  );
	}

    private function define_admin_hooks(){
        /**
         * Load Module Config Settings Options
         * 
         * @since 2.0.0
         */
        $this->add_action('csf_framework_load_config', $this, 'load_module_config');


        /**
         * Enqueue Scripts
         * 
         * @since 2.0.0
         */
		$this->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );


		/**
		 * Set Active Theme
		 */
		$this->add_action('cs_ipido_admin_load_themes_after', $this, 'set_active_theme');
		

		/**
		 * Login Page Themes
		 * 
		 * @since 2.0.0
		 */
		$this->add_filter('cs_ipido_admin_load_themes',$this,'register_themes');
		$this->add_filter('cs_ipido_admin_parse_theme_settings',$this,'register_theme_settings');


		/**
		 * Login Page Customizer
		 * 
		 * @since 1.0.0
		 */
		if ($this->_gs('login_page_status')){
			$this->add_action('login_enqueue_scripts', $this, 'enqueue_login_style', 999 );
			$this->add_action('login_head', $this, 'login_head');
			$this->add_filter('login_body_class', $this, 'login_class');
			$this->add_filter('gettext', $this, 'login_label_change', 20, 3);
			$this->add_filter('gettext_with_context', $this, 'login_label_change', 20, 3);
			$this->add_filter('login_title', $this, 'login_title');
			$this->add_filter('login_headerurl', $this, 'login_logo_url' );
			$this->add_filter('login_headertitle', $this, 'login_logo_url_title' );
			$this->add_filter('login_message', $this, 'login_message_override' );
			$this->add_filter('login_messages', $this, 'login_messages_override');
			$this->add_filter('login_errors', $this, 'login_errors_override');
			$this->add_filter('cs_ipido_admin_getset_settings', $this, 'getset_settings');
			$this->add_filter('cs_ipido_admin_login_logo', $this, 'set_login_logo');
		
			// AJAX CALL: Dynamic Themes Stylesheet
			// $this->add_action('wp_ajax_nopriv_ipido_dynamic_themes',$this,'cs_ipido_dynamic_themes_callback');
		}


		/**
		 * Login Security & Logout Security Manager
		 * 
		 * 1. Logout Redirect by user role
		 * 2. Logout Custom URL
		 * 3. Login Redirect by user role
		 * 4. Login Custom URL
		 * 
		 * @since 1.1.0
		 */
		// Logout Redirect and URL Slug Changer
		if ($this->_gs('login_security_custom_logout_redirect_status')){
			$this->add_filter('logout_redirect', $this, 'logout_redirect', 999, 30);
		}
		if ($this->_gs('login_security_custom_logout_url_status')){
			$this->add_filter('logout_url', $this, 'logout_url', 10, 2 );
			$this->add_action('wp_loaded', $this, 'logout_action' );
		}

		// Login Redirect and URL Slug Changer
		if ($this->_gs('login_security_custom_login_redirect_status')){
			$this->add_filter('login_redirect', $this, 'login_redirect', 999, 30);
		}
		if ($this->_gs('login_security_custom_login_url_status')){
			$this->add_filter('login_url', $this, 'login_url', 10, 3 );
			$this->add_action('wp_loaded', $this, 'login_action' );
			$this->add_action('plugins_loaded', $this, 'plugins_loaded', 1 );
			$this->add_filter('wp_redirect', $this, 'wp_redirect' , 10, 2 );
			// $this->add_action('setup_theme', $this, 'setup_theme', 1 ); NO USADO
			
			$this->add_filter('site_url', $this, 'site_url', 10, 4 );
			// $this->add_filter('network_site_url', $this, 'cs_ipido_network_site_url', 10, 3 );
			$this->add_filter('site_option_welcome_email', $this, 'login_welcome_email' );
			remove_action('template_redirect', 'wp_redirect_admin_locations', 1000 );

			$this->add_action('template_redirect', $this, 'hide_login_redirect_page_email_notif_woocommerce' );
		}



		/**
		 * reCAPTCHA
		 *
		 * @since 2.0.0
		 */
		if ($this->_gs('login_page_recaptcha_status')){
			$this->add_action( 'login_footer' , $this,'recaptcha_footer');
	
			$protected_forms = $this->_gs('login_page_recaptcha_forms');

			// Login Form
			if (!empty($protected_forms) && in_array('login',$protected_forms)){
				$this->add_action('login_form', $this, 'parse_recaptcha_form');
				// $this->add_action('authenticate',$this,'authentucate_user', 30, 3);
				$this->add_filter('wp_authenticate_user', $this,'authenticate_user',99 );
			}
	
			// Lost Password Form
			if (!empty($protected_forms) && in_array('lostpw',$protected_forms)){
				$this->add_action('lostpassword_form', $this,'parse_recaptcha_form');
				// $this->add_action('lostpassword_post', $this,'recaptcha_check_or_die', 99 );
				$this->add_filter('allow_password_reset', $this,'recaptcha_check_with_message', 10, 2 );
			}

			// Register Form
			if (!empty($protected_forms) && in_array('register',$protected_forms)){
				$this->add_action('register_form', $this,'parse_recaptcha_form');
				$this->add_filter('registration_errors', $this,'recaptcha_check_with_message', 10, 2 );
			}

		}
    }

    private function define_public_hooks(){

	}

	public function set_active_theme($themes){
		$active_theme = $this->_gs('theme');

		if (($active_theme) && isset($themes['login'][$active_theme])){
			$theme = $themes['login'][$active_theme]->instance;
			$theme->init();
		}
	}
	
	/**
	 * Get Module Settings
	 */
	public static function _gs($field_id){
		return cs_get_settings($field_id,(new self)->unique);
	}



    /**
     * ------------------------------------------------------------------------------------------------
	 * ------------------------------------------------------------------------------------------------
     * 
     * Module Specific Functionality
     * 
	 * ------------------------------------------------------------------------------------------------
     * ------------------------------------------------------------------------------------------------
     */


    
    /**
	 * Load Config Settings - CSFRAMEWORK
     * 
     * @since 2.0.0
	 */
	public function load_module_config(){
		require_once( 'config/'.$this->name.'-settings.php'  );
    }
    


    /**
     * Enqueue Scripts
     * 
     * @since 2.0.0
     */
    public function enqueue_scripts(){
        wp_enqueue_script('ipido_admin_'.$this->name, plugin_dir_url( __FILE__ ) . 'js/'.$this->name.'.js', array('ipido_admin'), $this->version, false );
	}


	/**
	 * Register Login Themes Directory to Admin Themes Manager
	 */
	function register_themes($themes){
		$themes['login'] = (object) array(
			'base'  => 'Ipido_admin_login_theme',
			'path'  => plugin_dir_path( __FILE__ ) . 'themes/',
			'uri'   => plugin_dir_url( __FILE__ ) . 'themes/',
		);
		return $themes;
	}


	/**
	 * Parse Active theme settings
	 */
	function register_theme_settings($settings){
		$active_theme 			= $this->_gs('theme');
		$active_theme_settings 	= $this->_gs('ipido_theme-'.$active_theme);

		$settings['login']	= (object) array(
			'name'		=> $active_theme,
			'settings'	=> $active_theme_settings,
		);
		return $settings;
	}


	/**
	 * Get Available Admin Themes for Settings Framework
	 *
	 * @since    1.0.0
	 */
	public static function get_login_themes($preview = true){
		$active_themes = Ipido_admin::get_themes()->get_themes();
		$output_field_options = array();

		foreach ($active_themes['login'] as $theme){
			if ($theme->type == 'dynamic'){
				$output_field_options[$theme->raw_name] = array(
					'image'	=> $theme->uri . '/preview.png',
					'name'	=> $theme->human_name,
				);
			}
		}
		return $output_field_options;
	}


	/**
	 * Get Available Admin Themes Settings for Settings Framework
	 *
	 * @since    1.0.0
	 */
	public static function get_login_themes_settings(){
		$active_themes = Ipido_admin::get_themes()->get_themes();
		$settings = array();

		foreach ($active_themes['login'] as $theme){
			if ($theme->type == 'dynamic'){
				$settings[] = Ipido_admin::get_theme($theme->object_name)->get_settings();
			}
		}
		return $settings;
	}


	/**
	 * Login Area: Enqueue & Register stylesheets and scripts for login area
	 *
	 * @since 1.0.0
	 */
	function enqueue_login_style(){
		wp_enqueue_style( 'cs-castor-line-icons', CS_PLUGIN_URI .'/icons/castor-line-icons/castor-line-icons.css',array(), $this->version, 'all');

		// Revisar los efectos de no agregar estos estilos en el login...
		// wp_enqueue_style( $this->plugin_name, CS_PLUGIN_URI . '/admin/css/ipido_admin-admin.css', array(), $this->version, 'all' );
		
		// AJAX CALL: All Available Themes (for all customizable sections)
		wp_enqueue_style( $this->plugin_name . '_dynamic-themes',admin_url('admin-ajax.php').'?action=ipido_dynamic_themes', array(), $this->version, 'all');

		// Scripts
		wp_enqueue_script( $this->plugin_name, CS_PLUGIN_URI . '/admin/js/ipido_admin-login.js', array( 'jquery' ), $this->version, false );
	}


	/**
	 * Login Head
	 * 
	 * Para agregar o quitar lo que sea necesario solo en la pantalla de login:
	 * - Remover el efecto shake
	 *
	 * @since    1.0.0
	 */
	function login_head(){
		if ($this->_gs('login_page_error_shake')){
			remove_action('login_head', 'wp_shake_js', 12);
		}
	}
	function login_class($classes){
		$bg 		= $this->_gs('login_page_background_image');
		$style 		= $this->_gs('login_page_loginbox_style');
		$loginbox 	= $this->_gs('login_page_loginbox_background_style');

		if ($bg){
			$classes[] = 'cs-ipido-login-theme__'.$bg;
		}
		if ($style){
			$classes[] = 'cs-ipido-login-theme__'.$style;
		}
		if ($loginbox){
			$classes[] = 'cs-ipido-login-theme__'.$loginbox;
		}
		return $classes;
	}
	function login_label_change( $translated_text, $original_text, $domain ) {
		global $pagenow;
		
		// Reemplazar solo en la pagina de login
		if (in_array( $pagenow, array( 'wp-login.php', 'wp-register.php' ) )) {
			switch ( $original_text ) {
				// To remove labels on login inputs
				case 'Username or Email Address':
					$translated_text = '';
					break;
				case 'Password':
					$translated_text = '';
					break;
				case 'Username':
					$translated_text = '';
					break;
				case 'Email':
					$translated_text = '';
					break;

				// Replace with custom user texts
				case 'Remember Me':
					if ($this->_gs('login_page_rememberme_status')) {
						$translated_text = $this->_gs('login_page_rememberme');
					}
					break;
				case 'Log in':
					// $translated_text = 'Back to Sign In';
					if ($this->_gs('login_page_link_login_status')) {
						if (!$this->_gs('login_page_link_login_visibility')){
							$translated_text = $this->_gs('login_page_link_login');
						} else {
							$translated_text = '';
						}
					}
					break;
				case 'Register':
					// $translated_text = 'Sign up now';
					if ($this->_gs('login_page_link_register_status')) {
						$translated_text = $this->_gs('login_page_link_register');
					}
					break;
				case 'Get New Password':
					// $translated_text = 'Generate Password';
					if ($this->_gs('login_page_button_getnewpassword_status')) {
						$translated_text = $this->_gs('login_page_button_getnewpassword');
					}
					break;
				case 'Log In':
					if ($this->_gs('login_page_button_login_status')) {
						$translated_text = $this->_gs('login_page_button_login');
					}
					break;
				case 'Lost your password?':
					if ($this->_gs('login_page_link_lostpassword_status')) {
						if (!$this->_gs('login_page_link_lostpassword_visibility')){
							$translated_text = $this->_gs('login_page_link_lostpassword');
						} else {
							$translated_text = '';
						}
					}
					break;
				case '&larr; Back to %s':
					if ($this->_gs('login_page_link_back_status')) {
						if (!$this->_gs('login_page_link_back_visibility')){
							$translated_text = $this->_gs('login_page_link_back');
						} else {
							$translated_text = '';
						}
					}
					break;
			}
		}
		return $translated_text;
	}


	/**
	 * Replace Login Area Settings
	 * 
	 * @since 1.0.0
	 */
	function login_title($default){
		if ($this->_gs('login_page_title_status')){
			return $this->_gs('login_page_title');
		} else { return $default; }
	}
	function login_logo_url($default) {
		if ($this->_gs('login_logo_url_status')){
			return $this->_gs('login_logo_url');
		} else {
			return get_bloginfo( 'url' );
			// return $default;
		}
	}
	function login_logo_url_title($default) {
		if ($this->_gs('login_logo_url_title_status')){
			return $this->_gs('login_logo_url_title');
		} else { return $default; }
	}

	// Login Messages
	function login_message_override($message) {
		if (empty($message)){
			if ($this->_gs('login_page_login_message_status')){
				if ($this->_gs('login_page_login_message_style')){
					return '<div class="cs-glass-message"><div class="cs-glass-message-inner">'.$this->_gs('login_page_login_message').'</div></div>';
				} else {
					return '<div class="cs-normal-message">'.$this->_gs('login_page_login_message').'</div>';
				}
			}
		} else {
			return $message;
		}
	}
	function login_messages_override($message){
		global $errors;
		$err_codes = $errors->get_error_codes();
		
		if ( in_array( 'loggedout', $err_codes ) ) {
			if ($this->_gs('login_page_logout_message_status')){
				$message = $this->_gs('login_page_logout_message');
			}
		}
		return $message;
	}
	function login_errors_override($error) {
		global $errors;
		$err_codes = $errors->get_error_codes();

		// Invalid username.
		// Default: '<strong>ERROR</strong>: Invalid username. <a href="%s">Lost your password</a>?'
		if ( in_array( 'invalid_username', $err_codes ) ) {
			if ($this->_gs('login_page_invalid_username_status')){
				$error = $this->_gs('login_page_invalid_username');
			}
		}
	
		// Incorrect password.
		// Default: '<strong>ERROR</strong>: The password you entered for the username <strong>%1$s</strong> is incorrect. <a href="%2$s">Lost your password</a>?'
		if ( in_array( 'incorrect_password', $err_codes ) ) {
			if ($this->_gs('login_page_invalid_password_status')){
				$error = $this->_gs('login_page_invalid_password');
			}
		}

		// Recaptcha Error
		if ( in_array( 'captcha_error', $err_codes ) ) {
			if ($this->_gs('login_page_invalid_captcha_status')){
				$error = $this->_gs('login_page_invalid_captcha');
			}
		}
	
		return $error;
	}


	// Get & Set Module Settings
	public function getset_settings($settings){
		$logo = $this->_gs('logo_image_login');
		$settings['logo']['login'] = wp_get_attachment_url($logo);

		return $settings;
	}
	public function set_login_logo($logo){
		$logo = $this->_gs('logo_image_login');
		return $logo;
	}











	/**
	 * Custom Login and Logout URLs
	 * 
	 * @since 1.1.0
	 */
	private $wp_login_php;
	private $wp_logout_php;


	/**
	 * Custom Login Page, Login URL, Custom Login Redirect
	 *
	 * @wp-hook login_redirect
	 * 
	 * @since 1.1.0
	 */
	function login_redirect( $redirect_to, $request, $user){
		$login_redirect 		= $this->_gs('login_security_custom_login_redirect_status');
		$login_redirect_roles 	= $this->_gs('login_security_custom_login_redirect_roles');

		if ($login_redirect){
			$user_role = $user->roles[0];

			$url 	= $login_redirect_roles[$user_role];
			$status = $login_redirect_roles[$user_role .'_status'];

			if (isset($url) && isset($status)){
				$redirect_to = home_url($url);
			} else {
				$redirect_to = $redirect_to;
			}
		}
		return $redirect_to;
	}

	/**
	 * Custom Logout Page, Logout URL, Custom Logout Redirect
	 *
	 * @wp-hook logout_redirect
	 * @wp-hook logout_url
	 * @wp-hook wp_loaded
	 * 
	 * @since 1.1.0
	 */

	private function logout_slug(){
		if ($this->_gs('login_security_custom_logout_url_status')){
			$slug = $this->_gs('login_security_custom_logout_slug');
	
			if (!$slug){
				$slug = 'ipido-admin-logout';
			}
			return $slug;
		} else {
			return '';
		}
	}
	private function logout_slug_url(){
		$slug 	= $this->logout_slug();
		$url 	= home_url($slug);

		return $url;
	}
	private function logout_redirect_url(){
		$user = wp_get_current_user();
		$logout_redirect 		= $this->_gs('login_security_custom_logout_redirect_status');
		$logout_redirect_roles 	= $this->_gs('login_security_custom_logout_redirect_roles');

		if ($logout_redirect){
			$user_role = $user->roles[0];

			$url 	= $logout_redirect_roles[$user_role];
			$status = $logout_redirect_roles[$user_role .'_status'];

			if (isset($url) && isset($status)){
				$redirect_to = home_url($url);
			} else {
				$url = $this->logout_slug_url();
				// $redirect_to = ($url) ? $url : 'wp-login.php';
				$redirect_to = 'wp-login.php';
				
				echo $url;
			}
			// die();
		}
		return $redirect_to;
	}
	
	// Logout Redirect URL
	function logout_redirect( $redirect_to, $request, $user){
		$logout_redirect 		= $this->_gs('login_security_custom_logout_redirect_status');
		$logout_redirect_roles 	= $this->_gs('login_security_custom_logout_redirect_roles');

		if ($logout_redirect){
			$user_role = $user->roles[0];

			$url 	= $logout_redirect_roles[$user_role];
			$status = $logout_redirect_roles[$user_role .'_status'];

			if (isset($url) && isset($status)){
				$redirect_to = home_url($url);
			} else {
				$redirect_to = $redirect_to;
			}
		}
		return $redirect_to;
	}

	// Custom Logout URL
	function logout_url( $logout_url, $redirect ) {
		$logout_url = $this->logout_slug_url();
		$url 		= add_query_arg( 'action', 'logout', $logout_url );
		return $url;
	}
	function logout_action(){
		if (!isset($_GET['action'])){
			return;
		}
		$request = parse_url( $_SERVER['REQUEST_URI'] );

		if (
			untrailingslashit( $request['path'] ) === home_url( $this->logout_slug(), 'relative' ) || (
				! get_option( 'permalink_structure' ) &&
				isset( $_GET[$this->logout_slug()] ) &&
				empty( $_GET[$this->logout_slug()] )
				)
			) 
		{
			if ($this->wp_logout_php){
				wp_logout();
				wp_safe_redirect($this->logout_redirect_url());
				die();
			} else {
				wp_logout();
				$logout_url = $this->logout_slug_url();
				$url 		= add_query_arg( 'loggedout', 'true', $logout_url );
				wp_safe_redirect( $url );
				exit;

			}
		}
	}


	/**
	 * Filter WP Login
	 *
	 * @since 1.1.0
	 */

	// Helper Functions
	// ---------------------------------------------------------
	function site_url( $url, $path, $scheme, $blog_id ) {
		return $this->filter_wp_login_php( $url, $scheme );
	}

	function cs_ipido_network_site_url( $url, $path, $scheme ) {
		return $this->filter_wp_login_php( $url, $scheme );
	}

	function wp_redirect( $location, $status ) {
		return $this->filter_wp_login_php( $location );
	}

	private function use_trailing_slashes() {
		// return '/' === substr( get_option( 'permalink_structure' ), -1, 1 );
	}

	private function user_trailingslashit( $string ) {
		return $this->use_trailing_slashes() ? trailingslashit( $string ) : untrailingslashit( $string );
	}



	// General
	// ---------------------------------------------------------
	function filter_wp_login_php( $url, $scheme = null ) {
		if ( strpos( $url, 'wp-login.php' ) !== false ) {
			if ( is_ssl() ) {
				$scheme = 'https';
			}

			$args = explode( '?', $url );

			if ( isset( $args[1] ) ) {
				parse_str( $args[1], $args );
				if ( isset( $args['login'] ) ) {
					$args['login'] = rawurlencode( $args['login'] );
				}
				$url = add_query_arg( $args, $this->new_login_url( $scheme ) );
			} else {
				$url = $this->new_login_url( $scheme );
			}
		}
		return $url;
	}

	private function new_login_slug() {
		$slug = $this->_gs('login_security_custom_login_slug');
		if (!$slug) {
			$slug = 'ipido-admin-login';
		}
		return $slug;
	}

	private function new_login_url( $scheme = null ) {
		if ( get_option( 'permalink_structure' ) ) {
			return $this->user_trailingslashit( home_url( '/', $scheme ) . $this->new_login_slug() );
		} else {
			return home_url( '/', $scheme ) . '?' . $this->new_login_slug();
		}
	}







	public function plugins_loaded() {
		global $pagenow;

		if (
			! is_multisite() && (
				strpos( $_SERVER['REQUEST_URI'], 'wp-signup' ) !== false ||
				strpos( $_SERVER['REQUEST_URI'], 'wp-activate' ) !== false
			)
		) {
			wp_die( __( 'This feature is not enabled.', 'rename-wp-login' ) );
		}

		$request = parse_url( $_SERVER['REQUEST_URI'] );

		if ( (
				strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false ||
				untrailingslashit( $request['path'] ) === site_url( 'wp-login', 'relative' )
			) &&
			! is_admin()
		) {
			$this->wp_login_php = true;
			$_SERVER['REQUEST_URI'] = $this->user_trailingslashit( '/' . str_repeat( '-/', 10 ) );
			$pagenow = 'index.php';
		} elseif (
			untrailingslashit( $request['path'] ) === home_url( $this->new_login_slug(), 'relative' ) || (
				! get_option( 'permalink_structure' ) &&
				isset( $_GET[$this->new_login_slug()] ) &&
				empty( $_GET[$this->new_login_slug()] )
		) ) {
			$pagenow = 'wp-login.php';
		}  elseif ( ( strpos( rawurldecode( $_SERVER['REQUEST_URI'] ), 'wp-register.php' ) !== false
			|| untrailingslashit( $request['path'] ) === site_url( 'wp-register', 'relative' ) )
			&& ! is_admin() ) {

				$this->wp_login_php = true;

				$_SERVER['REQUEST_URI'] = $this->user_trailingslashit( '/' . str_repeat( '-/', 10 ) );

				$pagenow = 'index.php';
			}

		
		// LOGOUT CUSTOM URL PAGE 
		if (
			untrailingslashit( $request['path'] ) === home_url( $this->logout_slug(), 'relative' ) || (
				! get_option( 'permalink_structure' ) &&
				isset( $_GET[$this->logout_slug()] ) &&
				empty( $_GET[$this->logout_slug()] )
		)) {
			$logout_redirect = $this->_gs('login_security_custom_logout_redirect_status');
			if (!$logout_redirect){
				$pagenow = 'wp-login.php';
				$this->wp_logout_php = false;
			} else {
				$this->wp_logout_php = true;
			}
		}
	}

	public function login_action() {
		global $pagenow;

		if ( is_admin() && ! is_user_logged_in() && ! defined( 'DOING_AJAX' ) && $pagenow !== 'admin-post.php' && ( isset( $_GET ) && empty( $_GET['adminhash'] ) && $request['path'] !== '/wp-admin/options.php' ) ) {
			wp_safe_redirect( 
				home_url( $this->user_trailingslashit( $this->new_login_slug() ) . '?redirect_to='.admin_url().'&reauth=1')
			);
			die();
		}

		$request = parse_url( $_SERVER['REQUEST_URI'] );

		if (
			$pagenow === 'wp-login.php' &&
			$request['path'] !== $this->user_trailingslashit( $request['path'] ) &&
			get_option( 'permalink_structure' )
		) {
			wp_safe_redirect( $this->user_trailingslashit( $this->new_login_url() ) . ( ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . $_SERVER['QUERY_STRING'] : '' ) );
			die;
		} elseif ( $this->wp_login_php ) {
			if (
				( $referer = wp_get_referer() ) &&
				strpos( $referer, 'wp-activate.php' ) !== false &&
				( $referer = parse_url( $referer ) ) &&
				! empty( $referer['query'] )
			) {
				parse_str( $referer['query'], $referer );

				if (
					! empty( $referer['key'] ) &&
					( $result = wpmu_activate_signup( $referer['key'] ) ) &&
					is_wp_error( $result ) && (
						$result->get_error_code() === 'already_active' ||
						$result->get_error_code() === 'blog_taken'
				) ) {
					wp_safe_redirect( $this->new_login_url() . ( ! empty( $_SERVER['QUERY_STRING'] ) ? '?' . $_SERVER['QUERY_STRING'] : '' ) );
					die;
				}
			}

			$this->wp_template_loader();
		} elseif ( $pagenow === 'wp-login.php' ) {
			global $errors, $error, $interim_login, $action, $user_login;

			if ( is_user_logged_in() && ! isset( $_REQUEST['action'] ) ) {
				wp_safe_redirect( admin_url() );
				die();
			}

			@require_once ABSPATH . 'wp-login.php';

			die;

		}
	}
	private function wp_template_loader() {
		global $pagenow;

		$pagenow = 'index.php';

		if ( ! defined( 'WP_USE_THEMES' ) ) {
			define( 'WP_USE_THEMES', true );
		}

		wp();

		if ( $_SERVER['REQUEST_URI'] === $this->user_trailingslashit( str_repeat( '-/', 10 ) ) ) {
			$_SERVER['REQUEST_URI'] = $this->user_trailingslashit( '/wp-login-php/' );
		}

		require_once( ABSPATH . WPINC . '/template-loader.php' );

		die;
	}



	/**
	 * Update Welcome Email with the new login url
	 *
	 * @since 1.1.0
	 */
	public function login_welcome_email($value){
		return $value = str_replace( 'wp-login.php', trailingslashit( get_site_option( 'whl_page', 'login' ) ), $value );
	}


	/**
	 * Update redirect for Woocommerce email notification
	 * @since 1.1.0
	 */
	public function hide_login_redirect_page_email_notif_woocommerce() {
		if (!class_exists('WC_Form_Handler')){
			return false;
		}
		if (!empty( $_GET ) && isset( $_GET['action'] ) && 'rp' === $_GET['action'] && isset( $_GET['key'] ) && isset( $_GET['login'] ) ) {
			wp_redirect( $this->new_login_url() );
			exit();
		}
	}


	/**
	 *
	 * Update url redirect : wp-admin/options.php
	 *
	 * @param $login_url
	 * @param $redirect
	 * @param $force_reauth
	 *
	 * @return string
	 * @since 1.1.0
	 */
	public function login_url( $login_url, $redirect, $force_reauth ) {
		if ( $force_reauth === false ) {
			return $login_url;
		}
		if ( empty( $redirect ) ) {
			return $login_url;
		}
		$redirect = explode( '?', $redirect );

		if ( $redirect[0] === admin_url( 'options.php' ) ) {
			$login_url = admin_url();
		}
		return $login_url;
	}



	/** 
	 * *********************************************************************************
	 * reCAPTCHA
	 * 
	 * @since 2.0.0
	 * *********************************************************************************
	 */


	/**
	 * Set current captcha instance and return it.
	 */
	public function captcha_instance() {
		if (is_null($this->_captcha_instance)){
			$theme = ($this->_gs('login_page_recaptcha_theme') == 'light') ? 'light' : 'dark';
			$size = ($this->_gs('login_page_recaptcha_size') == 'normal') ? 'normal' : 'compact';

			$settings = array(
				'key_public'	=> $this->_gs('login_page_recaptcha_sitekey'),
				'key_private'	=> $this->_gs('login_page_recaptcha_secretkey'),
				'theme'			=> $theme,
				'size'			=> $size,
				'language'		=> 'es-ES',
			);
			$this->_captcha_instance = Ipido_admin_recaptcha::instance($settings);
		}
		return $this->_captcha_instance;
	}


	/**
	 * Check recaptcha
	 */
	function recaptcha_check(){
		$response = $this->captcha_instance()->check();
		if (isset($response->success) && $response->success === true){
			return 'success';
		} else {
			$response = $this->captcha_instance()->get_google_errors_as_string($response->wp_error);

			return $response;
		}
	}


	/**
	 * Print recaptcha scripts
	 * 
	 * @wp-hook login_footer
	 */
	function recaptcha_footer(){
		$this->captcha_instance()->print_foot();
	}


	/**
	 * Print recaptcha HTML. Use inside a form.
	 */
	function parse_recaptcha_form( $attr = array() ){
		echo $this->captcha_instance()->get_html( $attr );
	}


	/**
	 *	check recaptcha on login
	 *	filter function for 'wp_authenticate_user'
	 *
	 *	@param $user WP_User
	 *	@return object user or wp_error
	 */
	function authenticate_user($user){
		$recaptcha_check = $this->recaptcha_check();
		if (isset($_POST["log"]) && ($recaptcha_check == 'success')){
			if (!$this->test_keys()){
				return $user;
			} else {
				return $this->wp_error( $user );
			}
			$msg = __("<strong>Error:</strong> the captcha didn't verify.",'ipido_admin');
			return new WP_Error('captcha_error',$msg);
		} else {
			return new WP_Error('captcha_error',$recaptcha_check);
		}
		return $user;
	}


	function recaptcha_check_with_message( $result, $user_id ){
		if ($this->recaptcha_check() != 'success'){
			$msg = $this->recaptcha_check();
			$result = new WP_Error('captcha_error',$msg);
		}
		return $result;
	}

	/**
	 *	Check recaptcha and wp_die() on fail
	 *	hooks into `pre_comment_on_post`, `lostpassword_post`
	 */
	// function recaptcha_check_or_die( ) {
	// 	if (!$this->recaptcha_check()){
	// 		$err = new WP_Error('comment_err',  __("<strong>Error:</strong> the captcha didn't verify.",'ipido_admin') );
	// 		wp_die( $err );
	// 	}
   	// }


	/**
	 *	check recaptcha and return WP_Error on failure.
	 *	filter function for `allow_password_reset`
	 *
	 *	@param $param mixed return value of funtion when captcha validates
	 *	@return mixed will return argument $param an success, else WP_Error
	 */
	function wp_error( $param , $error_code = 'captcha_error' ) {
		if (!$this->recaptcha_check()){
			$msg = __("<strong>Error:</strong> the captcha didn't verify.",'ipido_admin');
			return new WP_Error($error_code,$msg);
		} else {
			return $param;
		}
	}
	 

	/**
	 *	Test public and private key
	 *
	 *	@return bool
	 */
	public function test_keys(){
		return $this->captcha_instance()->test_keys();
	}
	



}