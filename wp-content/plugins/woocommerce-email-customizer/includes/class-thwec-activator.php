<?php
/**
 * Fired during plugin activation.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package    woocommerce-email-customizer-pro
 * @subpackage woocommerce-email-customizer-pro/includes
 */
if(!defined('WPINC')){	die; }

if(!class_exists('THWEC_Activator')):

class THWEC_Activator {

	/**
	 * Copy older version settings if any.
	 *
	 * Use pro version settings if available, if no pro version settings found 
	 * check for free version settings and use it.
	 *
	 * - Check for premium version settings, if found do nothing. 
	 * - If no premium version settings found, then check for free version settings and copy it.
	 */
	public static function activate() {
		self::create_upload_directory();
		self::check_for_premium_settings();
	}

	public static function create_upload_directory(){
	    //$wp_upload_dir = wp_upload_dir();
	    //$templates_folder = $wp_upload_dir['basedir'].'/thwec_templates';
	    //require_once plugin_dir_path(dirname(__FILE__)).'includes/utils/class-thwec-utils.php';
      	wp_mkdir_p(THWEC_Utils::get_template_directory());
      	//$templates_folder = trailingslashit($templates_folder);

      	//!defined('THWEC_CUSTOM_TEMPLATE_PATH') && define('THWEC_CUSTOM_TEMPLATE_PATH', $templates_folder);
	}
	
	public static function check_for_premium_settings(){
		/*$premium_settings = get_option(THDEMO_Utils::SETTINGS_KEY);
		
		if($premium_settings && is_array($premium_settings)){			
			return;
		}else{		
			self::may_copy_free_version_settings();
		}*/
	}
	
	public static function may_copy_free_version_settings(){
		
	}
}

endif;