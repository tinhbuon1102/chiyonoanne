<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
* Copyright 2018 Castorstudio <support@castostudio.com>
*
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*
* ------------------------------------------------------------------------------------------------
*
*/

// ------------------------------------------------------------------------------------------------
require_once plugin_dir_path( __FILE__ ) .'/csf-framework-path.php';
// ------------------------------------------------------------------------------------------------



if ( ! function_exists( 'csf_registry' ) ) {
	/**
	* @return null|\CSFramework_Registry
	*/
	function csf_registry() {
		return CSFramework_Registry::instance();
	}
}

if ( ! function_exists( 'csf_field_registry' ) ) {
	/**
	* @return null|\CSFramework_Field_Registry
	*/
	function csf_field_registry() {
		return CSFramework_Field_Registry::instance();
	}
}

if ( ! function_exists( 'csf_template' ) ) {
	/**
	* @param       $override_location
	* @param       $template_name
	* @param array $args
	*
	* @return bool
	*/
	function csf_template( $override_location, $template_name, $args = array() ) {
		if ( file_exists( $override_location . '/' . $template_name ) ) {
			$path = $override_location . '/' . $template_name;
		} elseif ( file_exists( CSF_DIR . '/templates/' . $template_name ) ) {
			$path = CSF_DIR . '/templates/' . $template_name;
		} else {
			return false;
		}
		extract( $args );
		include( $path );
		return true;
	}
}

if ( ! function_exists( 'csf_autoloader' ) ) {
	/**
	* CSF Autoloader Function to auto load required class files on the go.
	*
	* @param      $class
	*
	* @return bool
	*/
	function csf_autoloader( $class ) {
		if ( true === $class && true === class_exists( $class, false ) ) {
			return true;
		}
		
		if ( 0 === strpos( $class, 'CSFramework_Option_' ) ) {
			$path = strtolower( substr( $class, 19 ) );
			csf_locate_template( 'fields/' . $path . '/' . $path . '.php' );
		} elseif ( 0 === strpos( $class, 'CSFramework_' ) ) {
			$path  = strtolower( substr( str_replace( '_', '-', $class ), 12 ) );
			$path1 = CSF_DIR . '/classes/' . $path . '.class.php';
			$path2 = CSF_DIR . '/classes/core/' . $path . '.class.php';
			
			if ( file_exists( $path1 ) ) {
				include( $path1 );
			} elseif ( file_exists( $path2 ) ) {
				include( $path2 );
			}
		}
		return true;
	}
}






if( ! function_exists( 'csf_framework_init' ) && ! class_exists( 'CSFramework' ) ) {
	function csf_framework_init() {
		
		// active modules
		defined( 'CSF_ACTIVE_FRAMEWORK' )  or  define( 'CSF_ACTIVE_FRAMEWORK',  true );
		defined( 'CSF_ACTIVE_METABOX'   )  or  define( 'CSF_ACTIVE_METABOX',    true );
		defined( 'CSF_ACTIVE_TAXONOMY'   ) or  define( 'CSF_ACTIVE_TAXONOMY',   true );
		defined( 'CSF_ACTIVE_SHORTCODE' )  or  define( 'CSF_ACTIVE_SHORTCODE',  true );
		defined( 'CSF_ACTIVE_CUSTOMIZE' )  or  define( 'CSF_ACTIVE_CUSTOMIZE',  true );
		
		/**
		* Required CSFramework Default Helper Functions
		*/
		csf_locate_template( 'functions/deprecated.php'     );
		csf_locate_template( 'functions/fallback.php'       );
		csf_locate_template( 'functions/helpers.php'        );
		csf_locate_template( 'functions/actions.php'        );
		csf_locate_template( 'functions/enqueue.php'        );
		csf_locate_template( 'functions/sanitize.php'       );
		csf_locate_template( 'functions/validate.php'       );
		
		
		/**
		* Required CSFramework Default Classes
		*/
		csf_locate_template( 'classes/core/registry.class.php'   );
		csf_locate_template( 'classes/core/field_registry.class.php'   );
		csf_locate_template( 'classes/abstract.class.php'   );
		// fields class
		// wpsf ajax
		// wpsf query
		csf_locate_template( 'classes/options.class.php'    );
		csf_locate_template( 'classes/framework.class.php'  );
		
		// Custom Post Type
		csf_locate_template( 'classes/posttype/posttype.php' );
		csf_locate_template( 'classes/posttype/taxonomy.php' );
		csf_locate_template( 'classes/posttype/columns.php'  );
		
		spl_autoload_register('csf_autoloader');
		csf_registry();
		csf_field_registry();
		
		// Hook added to load Extra Configs settings
		do_action('csf_framework_load_config');
		
		do_action('csf_framework_loaded');
	}
	
	
	
	/**
	* Sets up framework config settings and registers support for various WordPress features.
	*
	* Note that this function is hooked into the 'after_setup_theme' hook, which
	* runs before the init hook. The init hook is too late for some features, such
	* as Custom Post Types that need to be hooked only on 'init'
	*/
	add_action('after_setup_theme','csf_framework_init',10);
	// add_action( 'init', 'csf_framework_init', 10 ); // Este hook tenia originalmente
}





add_action('csf_framework_load_config','csf_load_configs',1);
function csf_load_configs(){
	csf_locate_template( 'config/framework.config.php'  );
}