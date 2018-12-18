<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.castorstudio.com
 * @since      1.0.0
 *
 * @package    Ipido_admin
 * @subpackage Ipido_admin/admin
 */

class Ipido_admin_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}


	/**
	 * Get All Available Loaded Modules
	 * 
	 * Used on Options CSFramework
	 * 
	 * Used on the "modules" checkbox fields under the "Modules" tab 
	 *
	 * @since 2.0.0
	 */
	public static function get_modules(){
		$modules = Ipido_admin::get_modules()->get_modules();
		$output_field_options = array();

		foreach($modules as $module){
			$output_field_options[$module->raw_name] = array(
				'image'	=> $module->uri .'/preview.png',
				'name'	=> $module->human_name,
			);
		}
		return $output_field_options;
    }


	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ipido_admin-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'cs-castor-line-icons', CS_PLUGIN_URI .'/icons/castor-line-icons/castor-line-icons.css',array(), $this->version, 'all');
		wp_enqueue_style('thickbox'); // Used for Custom Help Tabs

		// AJAX CALL: All Available Themes (for all customizable sections)
		wp_enqueue_style( $this->plugin_name . '_dynamic-themes',admin_url('admin-ajax.php').'?action=ipido_dynamic_themes', array($this->plugin_name), $this->version, 'all');
	}


	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * Page Loader 
		 * @since 1.0.0
		 */
		if(cs_get_settings('page_loader_status')){
			wp_enqueue_script( $this->plugin_name . '_pace', plugin_dir_url( __FILE__ ) . 'js/pace.js', array( ), $this->version, false );
		}


		/**
		 * Notification Center Toast
		 * @since 1.2.0
		 */ 
		if (cs_get_settings('notification_center_status')){
			wp_enqueue_script( $this->plugin_name . '_toast', plugin_dir_url( __FILE__ ) . 'js/jquery.toast.min.js', array( 'jquery' ), $this->version, false );
		}


		/**
		 * Navbar Tooltips
		 * @since 1.2.0
		 */ 
		if (cs_get_settings('navbar_tooltips_status')){
			wp_enqueue_script( $this->plugin_name . '_tippy', plugin_dir_url( __FILE__ ) . 'js/jquery.tippy.min.js', array( 'jquery' ), $this->version, false );
		}

		
		/** 
		 * IPIDO Admin - Core Third Part Plugins
		 * 
		 * 1. Custom Scrollbars - jquery.overlayScrollbars.js
		 * 2. Select Field		- select2.js
		 * 3. Thickbox 			- wordpress core included
		 * @since 1.0.0
		 */
		wp_enqueue_script( $this->plugin_name . '_scrollbars', plugin_dir_url( __FILE__ ) . 'js/jquery.overlayScrollbars.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '_select2', plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js', array( 'jquery' ), $this->version, false);
		wp_enqueue_script('thickbox'); // Used for Custom Help Tabs


		/** 
		 * IPIDO Admin Main Javascript File
		 * @since 1.0.0
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ipido_admin-admin.js', array( 'jquery' ), $this->version, false );


		/**
		 * Localize IPIDO Admin Main Javascript File
		 */
		wp_localize_script( $this->plugin_name, 'ipido_admin', 
			array( 
				'ajax_url' 	=> admin_url('admin-ajax.php'),
				'nonce' 	=> wp_create_nonce('cs-ipido-admin-nonce'),
			)
		);
	}


	/**
	 * Get Available Admin Themes for Settings Framework
	 *
	 * @since    1.0.0
	 */
	public static function get_admin_themes($preview = true){
		$active_themes = Ipido_admin::get_themes()->get_themes();
		$output_field_options = array();

		foreach ($active_themes['themes'] as $theme){
			if ($theme->type == 'dynamic'){
				$output_field_options[$theme->raw_name] = array(
					'image'	=> $theme->uri .'/preview.png',
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
	public static function get_admin_themes_settings(){
		$active_themes = Ipido_admin::get_themes()->get_themes();
		$settings = array();

		foreach ($active_themes['themes'] as $theme){
			if ($theme->type == 'dynamic'){
				$settings[] = Ipido_admin::get_theme($theme->object_name)->get_settings();
			}
		}
		return $settings;
	}


	/**
	 * Generate dynamic css stylesheet
	 *
	 * @since    	1.0.0
	 * @param 		string 		String of css vars to apply to the parsed theme stylesheet
	 */
	public function dynamic_themes_callback() {
		$active_theme 			= cs_get_settings('theme');
		$active_theme_settings 	= cs_get_settings('ipido_theme-'.$active_theme);

		$theme_settings_to_parse = array(
			'themes'	=> (object) array(
				'name'		=> $active_theme,
				'settings'	=> $active_theme_settings,
			),
		);

		$theme_settings_to_parse = apply_filters('cs_ipido_admin_parse_theme_settings', $theme_settings_to_parse);

		$themes = Ipido_admin::get_themes();
		$output_settings = null;
		foreach ($theme_settings_to_parse as $theme){
			$output_settings .= $themes->parse_theme_settings($theme->name,$theme->settings);
		}

		$output_settings = apply_filters('cs_ipido_admin_parse_theme_settings_after', $output_settings);
		
		$output_settings = $this->sanitize($output_settings);
		// $showcase_style_vars			= $showcase->get_style_vars(true);
		
		$themes->parse_theme_stylesheet($output_settings,$theme_settings_to_parse);

		die();
	}


	/**
	 * Get general theme style vars
	 *
	 * @description Returns the full list of settings styles variables, to apply and use into the admin themes.
	 *
	 * @since 	1.0.0
	 * @param 	boolean 	$asString 	Return the list as a string instead of array
	 * @return 	string|array
	 */
	public function get_style_vars($asString){
		$vars = $this->style_vars;

		if ($asString){
			$vars = implode('', $vars);
		}
		return $vars;
	}
	private function sanitize($string){
		return filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
	}


	/**
	 * Admin Head
	 *
	 * @since    1.0.0
	 */
	function admin_init_inject(){

		/**
		 * Top Navbar Unificada
		 */
		if (!cs_get_settings('navbar_titlebar_unify')){
			$navbar = "
				<div class='cs-ipido-header'>
					<div class='cs-ipido-header-toolbar'>
					</div>
				</div>
			";
			echo $navbar;
		}
	}
	function admin_init(){
		// Ipido_admin::get_module('dashboard_widgets_manager')->say_hello();
		// Ipido_admin::get_module('dashboard_widgets_manager')->hola();
		// $modules = Ipido_admin::get_modules();
		// echo "Loaded Modules: ";
		// echo '<pre>';
		// print_r($modules);
		// echo '</pre>';
	}
	function admin_head(){
		// Favicons & Device Icons
		$devices_icons = $this->cs_ipido_favicon_get_html();
		if ($devices_icons) {
			echo $devices_icons;
		}
	}


	/**
	 * Parse Core theme settings or Custom Styles
	 * Settings like: 
	 * - Page Loader custom styles
	 * - Custom CSS code
	 * 
	 * @since 1.0.0
	 */
	function register_core_theme_settings($settings){
		require_once CS_PLUGIN_PATH . '/admin/includes/class-ipido_admin-theme-core.php';

		$core_theme = new Ipido_admin_Theme_core();
		$core_settings = $core_theme->parse_settings();

		return $settings . $core_settings;
	}


	/**
	 * Body Class
	 */
	function body_class($classes){
		$cs_classes = array();

		// Page Loader
		// --------------------------------------------------------
		if (cs_get_settings('page_loader_status')){
			$theme = cs_get_settings('page_loader_theme');
			$cs_classes[] = 'cs-ipido-page-loader__'.$theme;
		}


		// User Profile Settings
		// --------------------------------------------------------
		if (cs_get_settings('user_profile_status')){
			$cs_classes[] = 'cs-ipido-userprofile_hidden';
		}
		$sections = cs_get_settings('user_profile_options');
		if ($sections){
			$editor = in_array('editor', $sections);
			if ($editor){
				$cs_classes[] = 'cs-ipido-userprofile_hidden-editor';
			}
			$syntaxis = in_array('syntaxis', $sections);
			if ($syntaxis){
				$cs_classes[] = 'cs-ipido-userprofile_hidden-syntaxis';
			}
			$colors = in_array('colors', $sections);
			if ($colors){
				$cs_classes[] = 'cs-ipido-userprofile_hidden-colors';
			}
			$shortcuts = in_array('shortcuts', $sections);
			if ($shortcuts){
				$cs_classes[] = 'cs-ipido-userprofile_hidden-shortcuts';
			}
			$adminbar = in_array('adminbar', $sections);
			if ($adminbar){
				$cs_classes[] = 'cs-ipido-userprofile_hidden-adminbar';
			}
			$language = in_array('language', $sections);
			if ($language){
				$cs_classes[] = 'cs-ipido-userprofile_hidden-language';
			}
		}


		// Top Navbar: Fixed Style, Unified Style, Hide Page Title
		// --------------------------------------------------------
		$navbar_title = cs_get_settings('navbar_position');
		if ($navbar_title == 'fixed'){
			$cs_classes[] = 'cs-ipido-fixed-title';
		}
		
		$navbar_unify = cs_get_settings('navbar_titlebar_unify');
		if (!$navbar_unify){
			$cs_classes[] = 'cs-ipido-not-unified-navbar';
		} else if ($navbar_unify){
			$cs_classes[] = 'cs-ipido-unified-navbar';
		}

		$navbar_pagetitle = cs_get_settings('navbar_titlebar_hidepagetitle');
		if ($navbar_pagetitle){
			$cs_classes[] = 'cs-ipido-hidden-pagetitle';
		}


		// Filter and Return Admin Classes
		// --------------------------------------------------------
		$cs_classes = apply_filters('body_class', $cs_classes);
		return $classes . implode(' ',$cs_classes);
	}

	function dashboard_widget_right_now($content){
		if (!cs_get_settings('rightnowwidget_status')){
			$version = $this->version;
			$content .= printf( __('<br>Your WordPress is using <a href="%s" title="IPIDO Admin Website" target="_blank">IPIDO Admin %s</a>','ipido_admin'), CS_PLUGIN_URL, $version);
		}
		return $content;
	}


	/**
	 * Network Sites Sidebar
	 * @since 1.0.0
	 */
	function network_sites_sidebar(){
		if (is_multisite()){
			global $wp_admin_bar;
			
			$sidebar_position 	= cs_get_settings('network_sidebar_position');
			$sidebar_position	= ($sidebar_position) ? $sidebar_position : 'left';
			$blog_names 		= array();
			$output_sidebar		= false;
			$sites 				= $wp_admin_bar->user->blogs;
			
			// Demo Sites
			// $demo_sites = array(
			// 	'3' => array(
			// 		'userblog_id'	=> '3',
			// 		'blogname'		=> 'Demo Site 3',
			// 		'domain'		=> 'demo3.multisite-wordpress.local',
			// 		'siteurl'		=> 'http://demo3.multisite-wordpress.local',
			// 	),
			// 	'4' => array(
			// 		'userblog_id'	=> '4',
			// 		'blogname'		=> 'SchoolNet Site',
			// 		'domain'		=> 'demo4.multisite-wordpress.local',
			// 		'siteurl'		=> 'http://demo4.multisite-wordpress.local',
			// 	),
			// 	'5' => array(
			// 		'userblog_id'	=> '5',
			// 		'blogname'		=> 'Castores School',
			// 		'domain'		=> 'demo5.multisite-wordpress.local',
			// 		'siteurl'		=> 'http://demo5.multisite-wordpress.local',
			// 	),
			// 	'6' => array(
			// 		'userblog_id'	=> '6',
			// 		'blogname'		=> 'SchoolMan Manager Demo Site',
			// 		'domain'		=> 'demo6.multisite-wordpress.local',
			// 		'siteurl'		=> 'http://demo6.multisite-wordpress.local',
			// 	),
			// 	'7' => array(
			// 		'userblog_id'	=> '7',
			// 		'blogname'		=> 'Biosites World Wide',
			// 		'domain'		=> 'demo7.multisite-wordpress.local',
			// 		'siteurl'		=> 'http://demo7.multisite-wordpress.local',
			// 	),
			// 	'8' => array(
			// 		'userblog_id'	=> '8',
			// 		'blogname'		=> 'Rusty Rivets',
			// 		'domain'		=> 'demo8.multisite-wordpress.local',
			// 		'siteurl'		=> 'http://demo8.multisite-wordpress.local',
			// 	),
			// 	'9' => array(
			// 		'userblog_id'	=> '9',
			// 		'blogname'		=> 'Ipido Admin Demo Site',
			// 		'domain'		=> 'demo9.multisite-wordpress.local',
			// 		'siteurl'		=> 'http://demo9.multisite-wordpress.local',
			// 	),
			// 	'10' => array(
			// 		'userblog_id'	=> '10',
			// 		'blogname'		=> 'Ultimate Logo Showcase Demo Site',
			// 		'domain'		=> 'demo10.multisite-wordpress.local',
			// 		'siteurl'		=> 'http://demo10.multisite-wordpress.local',
			// 	),
			// 	'11' => array(
			// 		'userblog_id'	=> '11',
			// 		'blogname'		=> 'Material Color Picker Plus',
			// 		'domain'		=> 'demo11.multisite-wordpress.local',
			// 		'siteurl'		=> 'http://demo11.multisite-wordpress.local',
			// 	),
			// 	'12' => array(
			// 		'userblog_id'	=> '12',
			// 		'blogname'		=> 'Bemat Admin',
			// 		'domain'		=> 'demo12.multisite-wordpress.local',
			// 		'siteurl'		=> 'http://demo12.multisite-wordpress.local',
			// 	),
			// 	'13' => array(
			// 		'userblog_id'	=> '13',
			// 		'blogname'		=> 'Clients Portfolio',
			// 		'domain'		=> 'demo13.multisite-wordpress.local',
			// 		'siteurl'		=> 'http://demo13.multisite-wordpress.local',
			// 	),
			// );
			// $sites = array_replace($sites,$demo_sites);
			
			foreach ($sites as $site_id => $site){
				// $site = (object) $site; // For DEMO ONLY
				$blog_names[$site_id] = strtoupper( $site->blogname );
			}
	
			$sites_order = cs_get_settings('network_sidebar_sorting');
			if ($sites_order != 'none'){
				$is_excluded = (cs_get_settings('network_sidebar_sorting_mainsite') === true) ? true : false;
	
				if ($is_excluded){
					// Remove main blog from list
					unset($blog_names[1]);
				}
		
				// Sort menu by site name
				if ($sites_order == 'asc'){
					asort($blog_names);
				} else if ($sites_order == 'desc'){
					arsort($blog_names);
				}
				
				if ($is_excluded){
					// Add main blog back in to list
					if ($sites[1]){
						$_sites[1] = strtoupper( $sites[1]->blogname );
					}
				}
	
				foreach ($blog_names as $site_id => $site_name){
					$_sites[$site_id] = $site_name;
				}
			} else {
				$_sites = $blog_names;
			}
			
			foreach ($_sites as $site_id => $site_name){
				$current_site = (object) $sites[$site_id];
	
				$site_id		= $current_site->userblog_id;
				$site_name 		= $current_site->blogname;
				$site_domain 	= $current_site->domain;
				$site_url		= $current_site->siteurl;
				$site_admin_url = $site_url ."/wp-admin/";
	
				$output_sidebar .= "
					<li id='wp-admin-bar-blog-{$site_id}' class='menupop wp-has-submenu cs-network-site' data-name='{$site_name}'>
						<a class='ab-item wp-has-submenu' aria-haspopup='true' href='{$site_admin_url}'>
							<div class='blavatar'></div> {$site_name}
						</a>
						<ul id='wp-admin-bar-blog-{$site_id}-default' class='wp-submenu'>
							<li id='wp-admin-bar-blog-{$site_id}-d'><a class='ab-item' href='{$site_admin_url}'>Dashboard</a></li>
							<li id='wp-admin-bar-blog-{$site_id}-n'><a class='ab-item' href='{$site_admin_url}post-new.php'>New Post</a></li>
							<li id='wp-admin-bar-blog-{$site_id}-c'><a class='ab-item' href='{$site_admin_url}edit-comments.php'>Manage Comments</a></li>
							<li id='wp-admin-bar-blog-{$site_id}-v'><a class='ab-item' href='{$site_url}'>Visit Site</a></li>
						</ul>
					</li>
				";
			}
	
			$sidebar_brand = "
				<div class='sidebar-brand-wrapper'>
					<a href='#'>
						<div class='sidebar-brand_brand sidebar-brand_brand--visible'>
							<div class='sidebar-brand_icon'>
								<i class='cli cli-network-alt1'></i>
							</div>
							<div class='sidebar-brand_text'>Network Sites</div>
						</div>
					</a>
				</div>
			";
	
			$sidebar_search_field = false;
			if (cs_get_settings('network_sidebar_searchfield_status')){
				$sidebar_search_field = "
					<div class='cs-ipido-network-sites-search-wrapper'>
						<input placeholder='".__('Search a site','ipido_admin')."' class='cs-network-site-search' type='text'>
					</div>
				";
			}
	
			$output_sidebar = "
				<div id='cs-ipido-network-sites-sidebar' class='cs-ipido-network-sidebar_{$sidebar_position}'>
					<div class='cs-ipido-network-sites-sidebar-wrapper'>
						{$sidebar_brand}
						{$sidebar_search_field}
						<div id='cs_wp-admin-bar-my-sites-list-wrapper'>
							<ul id='cs_wp-admin-bar-my-sites-list' class='ab-sub-secondary ab-submenu'>{$output_sidebar}</ul>
						</div>
					</div>
				</div>
			";
			echo $output_sidebar;
		}
	}


	/**
	 * Plugin row action links
	 */
	function plugin_row_action_links($actions,$file){
		$ipido_basename = 'ipido_admin/ipido_admin.php';
		if ($ipido_basename != $file){ return $actions; }

		$settings = array('settings' => '<a href="admin.php?page=cs-ipido-admin-settings">' . __('Settings','ipido_admin') . '</a>');
		$site_link = array('support' => '<a href="' . CS_PLUGIN_URL . '/support/" target="_blank">'. __('Support','ipido_admin') .'</a>');

		$actions = array_merge($settings, $actions);
		$actions = array_merge($site_link, $actions);

		return $actions;
	}

	/**
	 * Plugin row meta links
	 */
	function plugin_row_meta_links( $input, $file ) {
		$ipido_basename = 'ipido_admin/ipido_admin.php';
		if ($ipido_basename != $file){ return $input; }

		$links = array(
			'<a href="' . admin_url( 'admin.php?page=cs-ipido-admin-home' ) . '">' . __( 'Getting Started','ipido_admin' ) . '</a>',
			'<a href="' . CS_PLUGIN_URL . '/docs/" target="_blank">' . __( 'Documentation','ipido_admin' ) . '</a>',
		);

		$output = array_merge( $input, $links );

		return $output;
	}


	/**
	 * On Plugin Settings Save Hook
	 *
	 * @since    1.0.0
	 */
	function save_plugin_settings($options,$framework_unique){
		if ($framework_unique == 'cs_ipidoadmin_settings'){
			$this->favicon_generate($options);
		}
	}


	/**
	 * Create Favicons - Apple Devices Icon - Android Devices Icon
	 * 
	 * The icons are generated by resizing the specified uploaded image
	 *
	 * @since    1.0.0
	 */
	private function favicon_generate($options){
		$favicon_status 	= $options['logo_favicon_status'];
		$apple_status 		= $options['logo_apple_status'];
		$android_status 	= $options['logo_android_status'];
		$devices			= $options['logo_devices_fs'];
		
		if ($favicon_status || $apple_status || $android_status){
			require_once CS_PLUGIN_PATH . '/admin/includes/ImageResize.php';
	
			$site_id = false;
			if (is_multisite()) {
				$site_id 		= "/".get_current_blog_id();
			}

			$favicon_path 	= CS_PLUGIN_PATH ."/favicons{$site_id}";

			if (!file_exists($favicon_path)) {
				mkdir($favicon_path, 0777, true);
			}
	
			// FAVICON
			if ($favicon_status){
				$favicon_id = $devices['logo_favicon'];

				if (cs_get_settings('logo_favicon') != $favicon_id) {
					$favicon 	= get_attached_file($favicon_id);
					$sizes		= array('16', '32', '96');
					
					if ($favicon){
						foreach ($sizes as $size){
							$image = new \Gumlet\ImageResize($favicon);
							$image
								->resizeToBestFit($size, $size)
								->save($favicon_path."/favicon-{$size}x{$size}.png");
						}
					} else {
						foreach ($sizes as $size){
							$file = $favicon_path."/favicon-{$size}x{$size}.png";
							unlink($file);
						}
					}
				}
			}
	
			// APPLE
			if ($apple_status){
				$apple_id = $devices['logo_apple'];

				if (cs_get_settings('logo_apple') != $apple_id) {
					$apple 	= get_attached_file($apple_id);
					$sizes 	= array('57', '60', '72', '76', '114', '120', '144', '152', '180');
		
					if ($apple){
						foreach ($sizes as $size){
							$image = new \Gumlet\ImageResize($apple);
							$image
								->resizeToBestFit($size, $size)
								->save($favicon_path."/apple-touch-icon-{$size}x{$size}.png");
						}
					} else {
						foreach ($sizes as $size){
							$file = $favicon_path."/apple-touch-icon-{$size}x{$size}.png";
							unlink($file);
						}
					}
				}
			}
	
			// ANDROID
			if ($android_status){
				$android_id = $devices['logo_android'];

				if (cs_get_settings('logo_android') != $android_id) {
					$android 	= get_attached_file($android_id);
					$sizes 		= array('36', '48', '72', '96', '144', '192');
		
					if ($android){
						foreach ($sizes as $size){
							$image = new \Gumlet\ImageResize($android);
							$image
								->resizeToBestFit($size, $size)
								->save($favicon_path."/android-chrome-{$size}x{$size}.png");
						}
					} else {
						foreach ($sizes as $size){
							$file = $favicon_path."/android-chrome-{$size}x{$size}.png";
							unlink($file);
						}
					}
				}
			}
		}
	}


	/**
	 * Generate Favicon/Apple/Android icons HTML code to be displayed on the admin area
	 *
	 * @since    1.0.0
	 */
	private function cs_ipido_favicon_get_html(){
		$site_id = false;
		if (is_multisite()) {
			$site_id 		= "/".get_current_blog_id();
		}
		$favicon_path 	= CS_PLUGIN_PATH ."/favicons{$site_id}";
		$favicon_uri	= CS_PLUGIN_URI ."/favicons{$site_id}";
		$html = '';

		// FAVICON
		if (cs_get_settings('logo_favicon_status')){
			foreach (array('16', '32', '96') as $size) {
				$size = "{$size}x{$size}";
				if (file_exists("{$favicon_path}/favicon-{$size}.png")) {
					$html .= '<link rel="icon" type="image/png" href="'.$favicon_uri.'/favicon-'.$size.'.png" sizes="'.$size.'">';
					$html .= "\n";
				}
			}
		}

		// APPLE
		if (cs_get_settings('logo_favicon_status')){
			foreach (array('57', '60', '72', '76', '114', '120', '144', '152', '180') as $size){
				$size = "{$size}x{$size}";
				if (file_exists("{$favicon_path}/apple-touch-icon-{$size}.png")) {
					$html .= '<link rel="apple-touch-icon" sizes="'.$size.'" href="'.$favicon_uri.'/apple-touch-icon-'.$size.'.png">';
					$html .= "\n";
				}
			}
		}

		// ANDROID
		if (cs_get_settings('logo_android_status')){
			foreach (array('36', '48', '72', '96', '144', '192') as $size){
				$size = "{$size}x{$size}";
				if (file_exists("{$favicon_path}/android-chrome-{$size}.png")) {
					$html .= '<link rel="icon" type="image/png" href="'.$favicon_uri.'/android-chrome-'.$size.'.png" sizes="'.$size.'">';
					$html .= "\n";
				}
			}
		}

		return strlen($html) > 0 ? $html : false;
	}


	/**
	 * SET Admin Settings for the Admin Area [Hook: admin_footer]
	 *
	 * @since    1.0.0
	 */
	function getset_settings(){
		// General Settings
		// --------------------------------------------------------
		$logo_url = cs_get_settings('logo_url');
		if ($logo_url == 'admin_url') { $logo_url = admin_url(); }
		$navbar_elements = cs_get_settings('navbar_elements')['main'];
		$navbar_elements = (json_decode($navbar_elements)) ? json_decode($navbar_elements) : array();

		$sidebar_status = (get_user_setting('mfold') == 'o') ? 'expanded' : 'collapsed';


		$output = array(
			'general'	=> array(
				'plugin_name'		=> $this->plugin_name,
				'plugin_version'	=> $this->version,
				'body_scrollbar'	=> cs_get_settings('bodyscrollbar_status'),
				'wp_is_mobile'		=> wp_is_mobile(),
				'is_multisite'		=> is_multisite(),
				'is_network_admin'	=> is_network_admin(),
				'is_super_admin'	=> is_super_admin(),
				'sidebar_status'	=> $sidebar_status,
			),
			'logo'		=> array(
				'status'	=> cs_get_settings('logo_status'),
				'url'		=> $logo_url,
				'type'		=> cs_get_settings('logo_type'),
				'image'		=> wp_get_attachment_url(cs_get_settings('logo_image')),
				'collapsed'	=> wp_get_attachment_url(cs_get_settings('logo_image_collapsed')),
				'icon'		=> cs_get_settings('logo_icon'),
				'text'		=> cs_get_settings('logo_text'),
				// 'login'		=> wp_get_attachment_url(cs_get_settings('logo_image_login')),
			),
			'navbar' 	=> array(
				'help' 					=> (in_array('help', $navbar_elements)) ? true : false,
				'help_title'			=> __('Help','ipido_admin'),
				'screen'				=> (in_array('screen', $navbar_elements)) ? true : false,
				'screen_title'			=> __('Screen Options','ipido_admin'),
				'notifications'			=> (in_array('notifications', $navbar_elements)) ? true : false,
				'notifications_title'	=> __('Notifications','ipido_admin'),
				'site' 					=> (in_array('site', $navbar_elements)) ? true : false,
				'site_title'			=> __('View Live Site','ipido_admin'),
				'updates'				=> (in_array('updates', $navbar_elements)) ? true : false,
				'comments'				=> (in_array('comments', $navbar_elements)) ? true : false,
				'newcontent'			=> (in_array('newcontent', $navbar_elements)) ? true : false,
				'account'				=> (in_array('account', $navbar_elements)) ? true : false,
				'networksites'			=> (in_array('networksites', $navbar_elements)) ? true : false,
				'networksites_title'	=> __('View Network Sites','ipido_admin'),
				'flexiblespace'			=> (in_array('flexiblespace', $navbar_elements)) ? true : false,
				'pagetitle'				=> (in_array('pagetitle', $navbar_elements)) ? true : false,
				'sidebartoggle'			=> (in_array('sidebartoggle', $navbar_elements)) ? true : false,
				'tooltips'				=> cs_get_settings('navbar_tooltips_status'),
				'order'					=> $navbar_elements,
				'position'				=> cs_get_settings('navbar_position'),
				'unify'					=> cs_get_settings('navbar_titlebar_unify'),
				'sidebartoggle_button'	=> cs_get_settings('navbar_sidebar_toggle_button_action'),
			),
			'notifications'	=> array(
				'status'		=> cs_get_settings('notification_center_status'),
				'duration'		=> cs_get_settings('notification_center_notification_duration')['slider1'],
			),
		);

		$output = apply_filters('cs_ipido_admin_getset_settings', $output);

		$output = json_encode($output);

		echo '<script type="text/javascript">$csj = jQuery.noConflict();_IPIDO_ADMIN.settings = $csj.extend(true,_IPIDO_ADMIN.settings, '.$output.');</script>';

	}


	/**
	 * Replace Footer Text & Footer Version
	 * 
	 * @since 1.0.0
	 */
	function remove_footer_text($default){
		$status = cs_get_settings('footer_text_status');
		if ($status){
			$hidden = cs_get_settings('footer_text_visibility');
			$text 	= cs_get_settings('footer_text');

			echo ($hidden) ? '' : $text;
		} else {
			echo $default;
		}
	}
	function remove_footer_version($default){
		$status = cs_get_settings('footer_version_status');
		if ($status){
			$hidden = cs_get_settings('footer_version_visibility');
			$text 	= cs_get_settings('footer_version');
			
			echo ($hidden) ? '' : $text;
		} else {
			echo $default;
		}
	}


	/**
	 * Register the 'Admin Menu Manager Page' as a submenu page
	 * 
	 * @since 1.0.0
	 */
	function register_admin_pages($framework_unique){
		// if ($framework_unique == 'cs_ipidoadmin_settings'){
			$page_hook = add_submenu_page('cs-ipido-admin-settings', 'IPIDO Admin About', __( 'About the Plugin','ipido_admin'), 'manage_options', 'cs-ipido-admin-about', 'cs_ipido_admin_welcome_page' );
			add_action("load-{$page_hook}",array(&$this,'cs_ipido_register_about_plugin_page'));
		// }
	}

	function cs_ipido_register_about_plugin_page(){
		wp_register_style( $this->plugin_name .'_about', plugin_dir_url( __FILE__ ) . 'css/ipido_admin-page-dashboard.css' );
		wp_enqueue_style( $this->plugin_name .'_about' );
	}


	/**
	 * Site "Generator" Replacement
	 * Remove "generator" tag from all the pages that use this information
	 * 
	 * 1. Completely hide site generator text
	 * 2. Replace site generator text
	 * 
	 * @since 1.2.0
	 */
	function version_remover(){
		if (cs_get_settings('site_generator_visibility')){
			remove_action('wp_head', 'wp_generator');   //remove inbuilt version
			remove_action('wp_head', 'woo_version');    //remove Woo-version (in case someone uses that)
		}
	}
	function generator_filter($html,$type){
		if (cs_get_settings('site_generator_status')){
			$generator_text		= cs_get_settings('site_generator_text');
			$generator_version 	= cs_get_settings('site_generator_version');
			$generator_url 		= cs_get_settings('site_generator_link');
			$generator_text 	= $generator_text ." ". $generator_version;
	
			switch($type){
				case 'html':
					$gen = '<meta name="generator" content="'.$generator_text.'">';
					break;
				case 'xhtml':
					$gen = '<meta name="generator" content="'.$generator_text.'" />';
					break;
				case 'atom':
					$gen = '<generator uri="'.$generator_url.'" version="'.$generator_version.'">'.$generator_text.'</generator>';
					break;
				case 'rss2':
					$gen = '<generator>'.$generator_text.'</generator>';
					break;
				case 'rdf':
					$gen = '<admin:generatorAgent rdf:resource="'.$generator_text.'" />';
					break;
				case 'comment':
					$gen = '<!-- generator="'.$generator_text.'" -->';
					break;
				case 'export':
					$gen = '<!-- generator="'.$generator_text.'" created="'. date('Y-m-d H:i') . '" -->';
					break;
				default:
					$gen = '';
			}
			return $gen;
		}
	}
}