<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.castorstudio.com
 * @since      1.0.0
 *
 * @package    Ipido_admin
 * @subpackage Ipido_admin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ipido_admin
 * @subpackage Ipido_admin/public
 * @author     Castorstudio <support@castorstudio.com>
 */
class Ipido_admin_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * The Ipido_admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'cs-castor-line-icons', CS_PLUGIN_URI .'/icons/castor-line-icons/castor-line-icons.css',array(), $this->version, 'all');
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ipido_admin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * The Ipido_admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ipido_admin-public.js', array( 'jquery' ), $this->version, false );

	}


	public function show_admin_bar(){
		if (!is_user_logged_in()) { return false; }
		// echo '
		// 	<style type="text/css">
		// 		html{
		// 			margin-top: 70px !important;
		// 		}
		// 		#wpadminbar {
		// 			// top: auto !important;
		// 		}
		// 	</style>
		// ';
	}

}
