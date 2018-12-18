<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_get_icons' ) ) {
	function csf_get_icons() {

		do_action( 'csf_add_icons_before' );

		// $jsons = apply_filters('csf_add_icons_json', glob( CSF_DIR . '/fields/icon/*.json' ));
		$jsons = apply_filters('csf_add_icons_json', glob( CSF_DIR . '/assets/icons/*.json' ));

		if( ! empty( $jsons ) ) {

			foreach ( $jsons as $path ) {

				$object = csf_get_icon_fonts( 'assets/icons/'. basename( $path ) );

				if( is_object( $object ) ) {

					echo ( count( $jsons ) >= 2 ) ? '<h4 class="csf-icon-title">'. $object->name .'</h4>' : '';

					echo '<div class="csf-icon-accordion-content">';
					foreach ( $object->icons as $icon ) {
						$value = "";
						if (is_object($icon)) { 
							$class 	= $icon->class;
							$icon 	= $icon->icon;
							echo '<a class="csf-icon-tooltip" data-icon="'. $icon .'" data-title="'. $icon .'"><span class="csf-icon csf-selector"><i class="'. $class .'">'.$icon.'</i></span></a>';
						} else {
							echo '<a class="csf-icon-tooltip" data-icon="'. $icon .'" data-title="'. $icon .'"><span class="csf-icon csf-selector"><i class="'. $icon .'"></i></span></a>';
						}
					}
					echo '</div>';

				} else {
					echo '<h4 class="csf-icon-title">'. __( 'Error! Can not load json file.', 'csf-framework' ) .'</h4>';
				}

			}

		}

		do_action( 'csf_add_icons' );
		do_action( 'csf_add_icons_after' );

		die();
	}
	add_action( 'wp_ajax_csf-get-icons', 'csf_get_icons' );
}

/**
 *
 * Export options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_export_options' ) ) {
	function csf_export_options() {

		header('Content-Type: plain/text');
		header('Content-disposition: attachment; filename=backup-options-'. gmdate( 'd-m-Y' ) .'.txt');
		header('Content-Transfer-Encoding: binary');
		header('Pragma: no-cache');
		header('Expires: 0');

		// echo csf_encode_string( get_option( CSF_OPTION ) );
		$option_array = ! empty( $_GET['option_array'] ) ? $_GET['option_array'] : CSF_OPTION;
		echo csf_encode_string( get_option( $option_array ) );

		die();
	}
	add_action( 'wp_ajax_csf-export-options', 'csf_export_options' );
}

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_set_icons' ) ) {
	function csf_set_icons() {

		echo '<div id="csf-icon-dialog" class="csf-dialog" title="'. __( 'Add Icon', 'csf-framework' ) .'">';
		echo '<div class="csf-dialog-header csf-text-center"><input type="text" placeholder="'. __( 'Search a Icon...', 'csf-framework' ) .'" class="csf-icon-search" /></div>';
		echo '<div class="csf-dialog-load"><div class="csf-loading-indicator"><div class="csf-spinner"></div>'. __( 'Loading...', 'csf-framework' ) .'</div></div>';
		echo '</div>';

	}
	add_action( 'admin_footer', 'csf_set_icons' );
	add_action( 'customize_controls_print_footer_scripts', 'csf_set_icons' );
}

















/**
 *
 * Get icons from admin ajax
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_get_images' ) ) {
	function csf_get_images() {
		$path 			= $_POST['path'];
		$images_path 	= CSF_DIR . $path;
		$images_uri	 	= CSF_URI . $path;

		do_action( 'csf_add_images_before' );

		// $images = apply_filters('csf_add_image_gallery_custom', glob( CSF_DIR . '/assets/images/images_gallery/*.*' ));
		$images = apply_filters('csf_add_image_gallery_custom', glob( $images_path .'*.*' ));

		if( ! empty( $images ) ) {

			foreach ( $images as $image ) {

				$image_info 	= pathinfo($image);
				$_image_name 	= $image_info['filename'];
				$_image_uri 	= "{$images_uri}{$_image_name}";

				echo "<a class='csf-image-tooltip' data-image-uri='{$_image_uri}' data-image='{$_image_name}' data-title='{$_image_name}'><span class='csf-image csf-selector'><img src='{$_image_uri}'></span></a>";

			}

		}

		do_action( 'csf_add_images' );
		do_action( 'csf_add_images_after' );

		die();
	}
	add_action( 'wp_ajax_csf-get-images', 'csf_get_images' );
}

/**
 *
 * Set icons for wp dialog
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! function_exists( 'csf_set_custom_image_gallery' ) ) {
	function csf_set_custom_image_gallery() {

		echo '<div id="csf-image-dialog" class="csf-dialog" title="'. __( 'Add image', 'csf-framework' ) .'">';
		echo '<div class="csf-dialog-header csf-text-center"><input type="text" placeholder="'. __( 'Search a image...', 'csf-framework' ) .'" class="csf-image-search" /></div>';
		echo '<div class="csf-dialog-load"><div class="csf-loading-indicator"><div class="csf-spinner"></div>'. __( 'Loading...', 'csf-framework' ) .'</div></div>';
		echo '</div>';

	}
	add_action( 'admin_footer', 'csf_set_custom_image_gallery' );
	add_action( 'customize_controls_print_footer_scripts', 'csf_set_custom_image_gallery' );
}








/**
 * 
 * Field: Color Theme
 * 
 * @version 1.0
 * 
 */
if (!function_exists('csf_color_theme_save_scheme_callback')){
	function csf_color_theme_save_scheme_callback(){
		// check the nonce
		if (check_ajax_referer( 'csf-framework-nonce', 'nonce', false ) == false ) {
			wp_send_json_error();
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
		}
	
		// Request Vars
		$options_unique 	= $_POST['options_unique'];
		$path 				= $_POST['field_unique'];
		$new_scheme 		= $_POST['scheme'];
		$scheme_name 		= str_replace(' ','_',sanitize_title($new_scheme['name']));
		$scheme_scheme 		= $new_scheme['scheme'];

		$settings 			= get_option($options_unique);
		$path_to_schemes 	= $path . "[custom_schemes]";
		$_path 				= preg_replace('/^[^\[]+/','', $path_to_schemes); // remove csframework unique id
		$custom_schemes = csf_arrayValueFromKeys($settings, $_path);

		// Check if JSON or already saved by the framework "save" button
		if (csf_isJSON($custom_schemes)){
			$custom_schemes = json_decode($custom_schemes,true);
		}

		// Add New Scheme
		if (!isset($custom_schemes[$scheme_name])){
			$custom_schemes[$scheme_name] = array(
				'name' 		=> $scheme_name,
				'scheme' 	=> $scheme_scheme
			);
		} else {
			wp_send_json_error('Already added');
		}

		// Save New Scheme
		csf_arrayValueFromKeys($settings,$_path,$custom_schemes);
		update_option($options_unique,$settings);

		// AJAX Response
		$response = array(
			'message'	=> 'Added',
			'schemes'	=> json_encode($custom_schemes)
		);
		wp_send_json_success($response);

		die();
	}
	add_action('wp_ajax_csf-color-scheme_save', 'csf_color_theme_save_scheme_callback');
}

if (!function_exists('csf_color_theme_delete_scheme_callback')){
	function csf_color_theme_delete_scheme_callback(){
		// check the nonce
		if (check_ajax_referer( 'csf-framework-nonce', 'nonce', false ) == false ) {
			wp_send_json_error();
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
		}

		// Request Vars
		$options_unique 	= $_POST['options_unique'];
		$path 				= $_POST['field_unique'];
		$scheme 			= $_POST['scheme'];

		$settings 			= get_option($options_unique);
		$path_to_schemes 	= $path . "[custom_schemes]";
		$_path 				= preg_replace('/^[^\[]+/','', $path_to_schemes); // remove csframework unique id
		$custom_schemes 	= csf_arrayValueFromKeys($settings, $_path);

		// Check if JSON or already saved by the framework "save" button
		if (csf_isJSON($custom_schemes)){
			$custom_schemes = json_decode($custom_schemes,true);
		}

		// Delete Scheme by ID
		if (isset($custom_schemes[$scheme])){
			unset($custom_schemes[$scheme]);
		} else {
			wp_send_json_error('Does not exist');
		}

		// Update Schemes Collection
		csf_arrayValueFromKeys($settings,$_path,$custom_schemes);
		update_option($options_unique,$settings);

		// AJAX Response
		$response = array(
			'message'	=> 'Deleted',
			'schemes'	=> json_encode($custom_schemes)
		);
		wp_send_json_success($response);

		die();
	}
	add_action('wp_ajax_csf-color-scheme_delete', 'csf_color_theme_delete_scheme_callback');
}

if (!function_exists('csf_color_theme_export_scheme_callback')){
	function csf_color_theme_export_scheme_callback(){
		// check the nonce
		if (check_ajax_referer( 'csf-framework-nonce', 'nonce', false ) == false ) {
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
			wp_send_json_error();
		}

		// Request Vars
		$path 				= csf_decode_string($_REQUEST['field_unique']);
		$path_to_schemes 	= $path . "[custom_schemes]";
		$_path 				= preg_replace('/^[^\[]+/','', $path_to_schemes); // remove csframework unique id
		preg_match('/^[^\[]+/',$path_to_schemes,$_p);
		$options_unique 	= $_p[0];

		$settings 			= get_option($options_unique);
		$custom_schemes 	= csf_arrayValueFromKeys($settings, $_path);

		if ($custom_schemes){
			header('Content-Type: plain/text');
			header('Content-disposition: attachment; filename=custom-color-schemes-'. gmdate( 'd-m-Y' ) .'.txt');
			header('Content-Transfer-Encoding: binary');
			header('Pragma: no-cache');
			header('Expires: 0');
			echo csf_encode_string( $custom_schemes );
		} else {
			wp_die(__('No color schemes to export','csf-framework'));
		}


		die();
	}
	add_action('wp_ajax_csf-color-scheme_export', 'csf_color_theme_export_scheme_callback');
}

if (!function_exists('csf_color_theme_import_scheme_callback')){
	function csf_color_theme_import_scheme_callback(){
		// check the nonce
		if (check_ajax_referer( 'csf-framework-nonce', 'nonce', false ) == false ) {
			wp_send_json_error();
			die('Permissions check failed. Please login or refresh (if already logged in) the page, then try Again.');
		}

		// Request Vars
		$options_unique 	= $_POST['options_unique'];
		$path 				= $_POST['field_unique'];
		$schemes_to_import	= csf_decode_string($_POST['schemes']);
		$overwrite			= $_POST['overwrite'];

		if ($schemes_to_import){
			$settings 			= get_option($options_unique);
			$path_to_schemes 	= $path . "[custom_schemes]";
			$_path 				= preg_replace('/^[^\[]+/','', $path_to_schemes); // remove csframework unique id
			$custom_schemes 	= csf_arrayValueFromKeys($settings, $_path);

			// Check if JSON or already saved by the framework "save" button
			if (csf_isJSON($schemes_to_import)){
				$schemes_to_import = json_decode($schemes_to_import,true);
			}
			if ($custom_schemes){
				if (csf_isJSON($custom_schemes)){
					$custom_schemes = json_decode($custom_schemes,true);
				}
			} else {
				$custom_schemes = array();
			}


			// Overwrite or Rename Imported Schemes
			if ($overwrite === 'true'){
				$custom_schemes = $schemes_to_import;
			} else {
				// echo "Agregando al final y renombrando las que existen...";
				$new_schemes_to_import = array();

				foreach($schemes_to_import as $key => $scheme){
					$index = 1;
	
					rename:
					$index++;
					$_key = $key .'-'. $index;
					if (isset($custom_schemes[$_key])){
						goto rename;
					} else {
						$new_schemes_to_import[$_key] = $scheme;
					}
				}

				// Merge
				$custom_schemes = array_merge($custom_schemes,$new_schemes_to_import);
			}


			// Update Schemes Collection
			csf_arrayValueFromKeys($settings,$_path,$custom_schemes);
			update_option($options_unique,$settings);
	
			// AJAX Response
			$response = array(
				'message'	=> 'Imported',
				'schemes'	=> json_encode($custom_schemes)
			);
			wp_send_json_success($response);
		} else {
			wp_send_json_error('Invalid');
		}


		die();
	}
	add_action('wp_ajax_csf-color-scheme_import', 'csf_color_theme_import_scheme_callback');
}


if (!function_exists('csf_isJSON')){
	/**
	 * Check if object or string is a valid JSON object
	 *
	 * @param [type] $string
	 * @return void
	 */
	function csf_isJSON($string){
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
}

if (!function_exists('csf_arrayValueFromKeys')){
	/**
	 * Function to get the value of an array based on a string path
	 * Ex: [settings][parent_field][field][subfield][target]
	 *
	 * @param array $array
	 * @param [type] $keys
	 * @param boolean $value
	 * @return void
	 */
	function csf_arrayValueFromKeys(&$array = array(), $keys, $value = false){
		$keys = explode('][', trim($keys, '[]'));
		$reference = &$array;
		foreach ($keys as $key) {
			if (!array_key_exists($key, $reference)) {
				$reference[$key] = [];
			}
			$reference = &$reference[$key];
		}
		if ($value === false){
			return $reference;
		} else {
			$reference = $value;
		}
	}
}