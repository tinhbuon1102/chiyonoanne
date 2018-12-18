<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Theme: 	core
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class Ipido_admin_Theme_core{
	public function __construct(){
        $this->theme_name   = 'core';
		$this->theme_prefix = 'ipido_theme-'.$this->theme_name;
	}

	public function get_settings(){
		$theme_id 		= $this->theme_prefix;
		$theme_prefix 	= $theme_id .'__';

		$settings 		= array();
		return $settings;
	}

	public function parse_settings($settings = array()){
		$option = function($option) use ($settings){
			$theme_prefix = $this->theme_prefix.'__';
			return $settings[$theme_prefix.$option];
		};

		// Parse Settings
		// ==========================================================================
		// Page Loader
		$page_loader_custom_colors_status 	= cs_get_settings('page_loader_custom_colors_status');
		$page_loader_primary 	= null;
		$page_loader_secondary 	= null;
		if ($page_loader_custom_colors_status){
			$page_loader_primary 	= cs_get_settings('page_loader_color_primary');
			$page_loader_secondary 	= cs_get_settings('page_loader_color_secondary');
		}

		// Sidebar Brand Logo
		$brand_logo_normal 		= wp_get_attachment_url(cs_get_settings('logo_image'));
		$brand_logo_collapsed 	= wp_get_attachment_url(cs_get_settings('logo_image_collapsed'));

		// Custom CSS
		$custom_css_status = cs_get_settings('customcss_status');
		$custom_css = '';
		if ($custom_css_status){
			$custom_css = cs_get_settings('customcss');
		}

		// Output Theme CSS Vars
		// ==========================================================================
		$output = "
			:root{
				%s_page-loader-primary:			$page_loader_primary;
				%s_page-loader-secondary:		$page_loader_secondary;

				%s_brand-logo-normal:			url($brand_logo_normal);
				%s_brand-logo-collapsed:		url($brand_logo_collapsed);
			}
			$custom_css
		";
		$prefix = CS_CSS_THEME_SLUG;
		$output = str_replace('%s',$prefix,$output);
		return $output;
	}
}