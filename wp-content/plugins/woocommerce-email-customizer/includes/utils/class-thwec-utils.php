<?php
/**
 * The common utility functionalities for the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package    woocommerce-email-customizer-pro
 * @subpackage woocommerce-email-customizer-pro/includes/utils
 */
if(!defined('WPINC')){	die; }

if(!class_exists('THWEC_Utils')):

class THWEC_Utils {
	const OPTION_KEY_TEMPLATE_SETTINGS = 'thwec_template_settings';
	const SETTINGS_KEY_TEMPLATE_LIST = 'templates';
	const SETTINGS_KEY_TEMPLATE_MAP = 'template_map';
	const OPTION_KEY_ADVANCED_SETTINGS = 'thwec_advanced_settings';
		
	public static function get_template_settings(){
		$settings = get_option(self::OPTION_KEY_TEMPLATE_SETTINGS);
		if(empty($settings)){
			$settings = array(
				self::SETTINGS_KEY_TEMPLATE_LIST => array(), 
				self::SETTINGS_KEY_TEMPLATE_MAP => array()
			);
		}
		return $settings;
	}

	public static function get_template_list($settings=false){
		if(!is_array($settings)){
			$settings = self::get_template_settings();
		}
		return is_array($settings) && isset($settings[self::SETTINGS_KEY_TEMPLATE_LIST]) ? $settings[self::SETTINGS_KEY_TEMPLATE_LIST] : array();
	}

	public static function get_template_map($settings=false){
		if(!is_array($settings)){
			$settings = self::get_template_settings();
		}
		return is_array($settings) && isset($settings[self::SETTINGS_KEY_TEMPLATE_MAP]) ? $settings[self::SETTINGS_KEY_TEMPLATE_MAP] : array();
	}

	public static function save_template_settings($settings, $new=false){
		$result = false;
		if($new){
			$result = add_option(self::OPTION_KEY_TEMPLATE_SETTINGS, $settings);
		}else{
			$result = update_option(self::OPTION_KEY_TEMPLATE_SETTINGS, $settings);
		}
		return $result;
	}

	public static function get_advanced_settings(){
		$settings = get_option(self::OPTION_KEY_ADVANCED_SETTINGS);
		return empty($settings) ? false : $settings;
	}
	
	public static function get_setting_value($settings, $key){
		if(is_array($settings) && isset($settings[$key])){
			return $settings[$key];
		}
		return '';
	}
	
	public static function get_settings($key){
		$settings = self::get_advanced_settings();
		if(is_array($settings) && isset($settings[$key])){
			return $settings[$key];
		}
		return '';
	}
	
	public static function get_template_directory(){
	    $upload_dir = wp_upload_dir();
	    $dir = $upload_dir['basedir'].'/thwec_templates';
      	//wp_mkdir_p($templates_folder);
      	$dir = trailingslashit($dir);
      	return $dir;
      	//!defined('THWEC_CUSTOM_TEMPLATE_PATH') && define('THWEC_CUSTOM_TEMPLATE_PATH', $templates_folder);
	}
}

endif;