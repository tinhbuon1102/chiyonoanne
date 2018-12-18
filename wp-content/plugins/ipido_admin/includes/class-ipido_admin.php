<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.castorstudio.com
 * @since      1.0.0
 *
 * @package    Ipido_admin
 * @subpackage Ipido_admin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ipido_admin
 * @subpackage Ipido_admin/includes
 * @author     Castorstudio <support@castorstudio.com>
 */
class Ipido_admin {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Ipido_admin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The loader that's responsible for maintaining and registering all the modules that power
	 * the plugin.
	 *
	 * @since    1.2.0
	 * @access   protected
	 * @var      Ipido_admin_Module    $modules    Maintains and registers all modules for the plugin.
	 */
	private static $modules;


	/**
	 * Themes used on the plugin
	 */
	private static $themes;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'IPIDO_ADMIN_VERSION' ) ) {
			$this->version = IPIDO_ADMIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ipido_admin';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ipido_admin_Loader. Orchestrates the hooks of the plugin.
	 * - Ipido_admin_i18n. Defines internationalization functionality.
	 * - Ipido_admin_Admin. Defines all hooks for the admin area.
	 * - Ipido_admin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ipido_admin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ipido_admin-i18n.php';



		/**
		 * El archivo responsable de cargar el framework de Admin y los archivos externos necesarios
		 * para hacer funcionar el plugin.
		 * 
		 * Se agrega aquí para poder tener disponibles las funciones antes de llamar a las acciones
		 * del área de administración y del área pública
		 * 
		 * @date 22/6/2018
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/includes/dependencies.php';



		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ipido_admin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ipido_admin-public.php';

		/**
		 * Ipido Admin Modules
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ipido_admin-modules.php';


		/**
		 * Ipido Admin THEMES
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ipido_admin-themes.php';


		$this->loader = new Ipido_admin_Loader();

		$this::$modules = new Ipido_admin_Modules();

		$this::$themes = new Ipido_admin_Themes();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ipido_admin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Ipido_admin_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Ipido_admin_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 999 ); // Priority '999' to load after all stylesheets
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Admin Framework Hooks
		$this->loader->add_action( 'csf_validate_save_after', $plugin_admin, 'save_plugin_settings',999,2);

		// Admin Menu Pages
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'register_admin_pages',990);

		// Register Core Theme Settings
		$this->loader->add_filter('cs_ipido_admin_parse_theme_settings_after', $plugin_admin, 'register_core_theme_settings');

		/**
		 * Admin Init Actions
		 * 
		 * @since 1.0.0
		 */
		// $this->loader->add_action('adminmenu', $plugin_admin, 'init_inject'); // Hook inside adminmenu
		$this->loader->add_action('in_admin_header', $plugin_admin, 'admin_init_inject');
		// $this->loader->add_action('all_admin_notices', $plugin_admin, 'init_inject');
		$this->loader->add_action('admin_init', $plugin_admin, 'admin_init');
		$this->loader->add_action('admin_head', $plugin_admin, 'admin_head');
		$this->loader->add_filter('admin_body_class', $plugin_admin, 'body_class');
		$this->loader->add_filter('update_right_now_text', $plugin_admin, 'dashboard_widget_right_now');


		/**
		 * Login Head
		 * 
		 * @since 1.0.0
		 */
		$this->loader->add_action('login_head', $plugin_admin, 'admin_head');
		

		/**
		 * Admin Footer Customizer
		 * 
		 * @since 1.0.0
		 */
		$this->loader->add_action('admin_print_footer_scripts', $plugin_admin, 'getset_settings'); // Function to transfer the admin settings to the js admin framework
		$this->loader->add_filter('admin_footer_text', $plugin_admin, 'remove_footer_text', 999); // Priority '999'
		$this->loader->add_filter('update_footer', $plugin_admin, 'remove_footer_version', 999); // Priority '999'


		/**
		 * Site "Generator" Replacement
		 * Clean all responses from VERSION GENERATOR
		 * 
		 * @since 1.2.0
		 */
		if (cs_get_settings('site_generator_status')){
			$this->loader->add_action('after_setup_theme', $plugin_admin, 'version_remover');
			$this->loader->add_filter('the_generator', $plugin_admin, 'generator_filter',10,2);
			$this->loader->add_filter('get_the_generator_html', $plugin_admin, 'generator_filter',10,2);
			$this->loader->add_filter('get_the_generator_xhtml', $plugin_admin, 'generator_filter',10,2);
			$this->loader->add_filter('get_the_generator_atom', $plugin_admin, 'generator_filter',10,2);
			$this->loader->add_filter('get_the_generator_rss2', $plugin_admin, 'generator_filter',10,2);
			$this->loader->add_filter('get_the_generator_feed', $plugin_admin, 'generator_filter',10,2);
			$this->loader->add_filter('get_the_generator_rdf', $plugin_admin, 'generator_filter',10,2);
			$this->loader->add_filter('get_the_generator_comment', $plugin_admin, 'generator_filter',10,2);
			$this->loader->add_filter('get_the_generator_export', $plugin_admin, 'generator_filter',10,2);
		}


		/**
		 * Network Sites
		 * 
		 * @since 2.0.0
		 */
		$this->loader->add_action('in_admin_header', $plugin_admin, 'network_sites_sidebar');


		/**
		 * AJAX CALLS
		 * 
		 * 1. Dynamic Themes Stylesheets
		 * 2. Dynamic Public Themes Stylesheets
		 * 
		 * @since 1.0.0
		 */
		$this->loader->add_action('wp_ajax_ipido_dynamic_themes',$plugin_admin,'dynamic_themes_callback');
		$this->loader->add_action('wp_ajax_nopriv_ipido_dynamic_themes',$plugin_admin,'dynamic_themes_callback');


		/**
		 * Plugin Info 
		 * Filters the plugin action links on "Plugins" page
		 * 
		 * 1. Filter for plugin action links 	- Hook: plugin_action_links
		 * 2. Filter for plugin meta links 		- Hook: plugin_row_meta
		 * 
		 * @since 1.0.0
		 */
		$this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'plugin_row_action_links', 10, 2 );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'plugin_row_meta_links' , 10, 2 );
	}

	
	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Ipido_admin_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		if (cs_get_settings('navbar_frontend_status')){
			$this->loader->add_action( 'wp_head', $plugin_public, 'show_admin_bar',999 );
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Ipido_admin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


	/**
	 * Retrieve the specific module instance of the plugin.
	 *
	 * @since     2.0.0
	 * @return    Ipido_admin_Module
	 */
	static function get_modules(){
		return self::$modules;
	}
	static function get_module($module){
		return self::$modules->$module;
	}


	/**
	 * Retrieve the specific themes instance of the plugin.
	 *
	 * @since     2.0.0
	 * @return    Ipido_admin_Module
	 */
	static function get_themes(){
		return self::$themes;
	}
	static function get_theme($theme){
		return self::$themes->$theme;
	}

}
