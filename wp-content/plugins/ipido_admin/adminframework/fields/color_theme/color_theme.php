<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Field: Color Variant
*
* @since 1.0.0
* @version 1.0.0
*
*/
class CSFramework_Option_color_theme extends CSFramework_Options {
	
	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}
	
	public function output() {
		
		echo $this->element_before();
		
		$field_unique 	= $this->unique ."[".$this->field['id']."]";
		$options 		= $this->field['options'];
		$sections 		= $options['sections'];
		$colors 		= $options['colors'];		
		$schemes 		= $this->field['schemes'];
		$settings 		= $this->field['settings'];
		$preview_colors = $settings['preview_colors'];

		// Default Colors
		$defaults = null;
		foreach($colors as $section){
			foreach($section as $color){
				if (isset($color['type']) && $color['type'] == 'group'){
					foreach($color['colors'] as $color){
						$color_id 	= (isset($color['id'])) ? $color['id'] : null;
						$color_code = (isset($color['color'])) ? $color['color'] : null;
						
						$defaults[$color_id] = $color_code;		
					}
				} else {
					$color_id 	= (isset($color['id'])) ? $color['id'] : null;
					$color_code = (isset($color['color'])) ? $color['color'] : null;
					
					$defaults[$color_id] = $color_code;
				}
			}
		}
		$this->value  = wp_parse_args( $this->element_value(), $defaults);


		// Get Current Selected Scheme
		$current_scheme_id 		= (isset($this->value['current_scheme_id'])) ? $this->value['current_scheme_id'] : null;
		$current_scheme_type 	= (isset($this->value['current_scheme_type'])) ? $this->value['current_scheme_type'] : null;


		// Predefined & Custom Schemes
		$predefined_schemes_json 	= ($schemes) ? (($this->isJSON($schemes)) ? $schemes : json_encode($schemes)) : null;
		$custom_schemes 			= (isset($this->value['custom_schemes'])) ? $this->value['custom_schemes'] : null;
		// $custom_schemes				= ($this->isJSON($custom_schemes)) ? json_decode($custom_schemes) : $custom_schemes;
		$custom_schemes_json 		= ($custom_schemes) ? (($this->isJSON($custom_schemes)) ? $custom_schemes : json_encode($custom_schemes)) : null;


		// Get Predefined & Custom Color Schemes for preview and select them
		$schemes_list_output = null;
		foreach($schemes as $key => $scheme){
			$scheme_name 			= (isset($scheme['name'])) ? $this->make_title($scheme['name']) : null;
			$scheme_scheme 			= (isset($scheme['scheme'])) ? $scheme['scheme'] : null;
			$scheme_preview_colors 	= array();
			
			// Check Current Scheme
			$is_current = null;
			if ($current_scheme_id == $key && $current_scheme_type == 'predefined'){
				$is_current = true;
			}

			// Set Preview Colors
			foreach($preview_colors as $color){
				$scheme_preview_colors[] = (isset($scheme_scheme[$color])) ? $scheme_scheme[$color] : null;
			}
			
			// Add Preview
			$schemes_list_output .= $this->get_preview_template($key,$scheme_name,$scheme_preview_colors,false,$is_current);
		}
		if ($custom_schemes){
			if ($this->isJSON($custom_schemes)) {
				$custom_schemes = json_decode($custom_schemes,true);
			}
			foreach($custom_schemes as $key => $scheme){
				$scheme_name 			= (isset($scheme['name'])) ? $this->make_title($scheme['name']) : null;
				$scheme_scheme 			= (isset($scheme['scheme'])) ? $scheme['scheme'] : null;
				$scheme_preview_colors 	= array();

				// Check Current Scheme
				$is_current = null;
				if ($current_scheme_id == $key && $current_scheme_type == 'custom'){
					$is_current = true;
				}

				// Set Preview Colors
				foreach($preview_colors as $color){
					$scheme_preview_colors[] = (isset($scheme_scheme[$color])) ? $scheme_scheme[$color] : null;
				}
				
				// Add Preview
				$schemes_list_output .= $this->get_preview_template($key,$scheme_name,$scheme_preview_colors,true,$is_current);
			}
		}
		
		
		// Get Color Scheme Builder Controls
		$output_schemes_sections = null;
		foreach($sections as $slug => $section){
			if (is_array($section)){
				$section_name = $section['title'];
				$section_desc = $section['desc'];
			} else {
				$section_name = $section;
				$section_desc = null;
			}
			
			$output_colors = null;
			
			$section_colors = (isset($colors[$slug])) ? $colors[$slug] : false;
			if ($section_colors){
				foreach($section_colors as $color){
					if (isset($color['type']) && $color['type'] == 'group'){
						$group_title 	= (isset($color['title'])) ? $color['title'] : null;
						$group_colors 	= null;
						foreach($color['colors'] as $color){
							$group_colors .= $this->color_picker($color);
						}
						$output_colors .= "<div class='csf-scheme-section-group'><h5>{$group_title}</h5><div class='csf-multifield'>{$group_colors}</div></div>";
					} else {
						$output_colors .= $this->color_picker($color);
					}
				}
			}
			
			$output_schemes_sections .= "
				<div class='csf-scheme-section'>
					<div class='csf-accordion-title'>
						<h4>{$section_name}</h4>
						<p>{$section_desc}</p>
					</div>
					<div class='csf-accordion-content'>
						<div class='csf-multifield'>
							{$output_colors}
						</div>
					</div>
				</div>
			";
		}


		// Get Preview Template
		$preview_template 				= $this->get_preview_template('0','Demo',array('rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)'),true);
		$preview_template_scheme_colors = json_encode($preview_colors);


		// Export URL
		$_field_unique 		= csf_encode_string($field_unique);
		$_export_nonce 		= wp_create_nonce('csf-framework-nonce');
		$export_admin_url 	= admin_url("admin-ajax.php?action=csf-color-scheme_export&field_unique={$_field_unique}&nonce={$_export_nonce}");
		

		// Output HTML
		$text_save_color_scheme 	= __('Save Color Scheme','ipido_admin');
		$text_export_color_scheme	= __('Export','ipido_admin');
		$text_import_color_scheme	= __('Import','ipido_admin');
		$name_current_scheme_id 	= $this->element_name("[current_scheme_id]");
		$name_current_scheme_type 	= $this->element_name("[current_scheme_type]");
		$name_scheme_unique 		= $this->element_name("[scheme_unique]");
		$name_predefined_schemes 	= $this->element_name("[predefined_schemes]");
		$name_custom_schemes 		= $this->element_name("[custom_schemes]");

		echo "
			<div class='csf-schemes'>
				<ul class='csf-schemes-list'>
					{$schemes_list_output}
				</ul>
				<input type='hidden' name='{$name_current_scheme_id}' class='csf-color-scheme-current_id' value='{$current_scheme_id}'>
				<input type='hidden' name='{$name_current_scheme_type}' class='csf-color-scheme-current_type' value='{$current_scheme_type}'>
				<input type='hidden' name='{$name_predefined_schemes}' class='csf-color-scheme-predefined_schemes' value='{$predefined_schemes_json}'>
				<input type='hidden' name='{$name_custom_schemes}' class='csf-color-scheme-custom_schemes' value='{$custom_schemes_json}'>
				<input type='hidden' name='{$name_scheme_unique}' class='csf-color-scheme-unique' value='{$field_unique}'>
				<div class='csf-schemes-controls'>
					<div class='csf-schemes-controls-buttons-row'>
						<div class='csf-element csf-field-text'>
							<input type='text' class='csf-color-scheme-scheme_name' name='' value='' placeholder='Color Scheme Name'>
						</div>
						<button class='csf-color-scheme-save_scheme csf-button csf-button-primary'>{$text_save_color_scheme}</button>
						<a href='{$export_admin_url}' class='csf-color-scheme-export_scheme csf-button' target='_blank'>{$text_export_color_scheme}</a>
						<button class='csf-color-scheme-import_scheme csf-button'>{$text_import_color_scheme}</button>
					</div>
					<div class='csf-schemes-import'>
						<div class='csf-element csf-field-textarea'>
							<textarea class='csf-schemes-import_data' placeholder='Paste your color schemes backup file content here'></textarea>
							<label>
								<div class='csf-field-checkbox'>
									<input type='checkbox' class='csf-schemes-import_overwrite csf-checkbox-icheck' value=''> Overwrite all my custom color schemes with the imported schemes
								</div>
							</label>
						</div>
						<button class='csf-schemes-import_submit csf-button csf-button-primary'>{$text_import_color_scheme}</button>
					</div>
				</div>
			</div>
			<div class='csf-scheme-builder'>
				$output_schemes_sections
			</div>
			<div class='csf-scheme-preview-template' data-scheme-colors='{$preview_template_scheme_colors}'>
				{$preview_template}
			</div>
		";
		
		echo $this->element_after();
		
	}

	function get_preview_template($key,$scheme_name,$colors = array('rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)','rgb(0,0,0)'),$is_custom = false,$is_current = false){
		$is_current 	= ($is_current) ? 'csf-schemes-item-current' : null;
		$data_type 		= ($is_custom) ? 'custom' : 'predefined';
		$delete_button 	= ($is_custom) ? "<div class='csf-schemes-item_delete' data-scheme-id='{$key}'><i class='cli cli-trash'></i></div>" : null;
		$spinner 		= "<div class='csf-schemes-loader'><div class='csf-spinner'></div></div>";
		$color_vars 	= '';
		foreach($colors as $ckey => $cvalue){
			$ckey++;
			$color_vars .= "--color{$ckey}: $cvalue;";
		}
		return "
			<li class='csf-schemes-item {$is_current}' data-scheme-id='{$key}' data-scheme-type='{$data_type}' style='{$color_vars}'>
				<div class='csf-schemes-item-preview' style='background-color:var(--color5);'>
					<span class='preview_header_brand' style='background-color:var(--color1);'></span>
					<span class='preview_header' style='background-color:var(--color2);'></span>
					<span class='preview_primary' style='background-color:var(--color3);'></span>
					<span class='preview_secondary' style='background-color:var(--color4);'></span>
					<span class='preview_text' style='color:var(--color6);'>{$scheme_name}</span>
				</div>
				{$spinner}
				{$delete_button}
			</li>
		";
	}
	
	
	private function color_picker($color){
		$color_id 		= (isset($color['id'])) ? $color['id'] : null;
		$color_code 	= (isset($color['color'])) ? $color['color'] : null;
		$color_title 	= (isset($color['title'])) ? $color['title'] : null;
		$color_palette 	= (isset($color['palette'])) ? $color['palette'] : null;
		
		return csf_add_element( array(
			'pseudo'		=> true,
			'id'			=> $this->field['id'].'_color_'.$color_id,
			'type'			=> 'color_picker',
			'name'			=> $this->element_name("[{$color_id}]"),
			'attributes'	=> array(
				'data-field-name'	=> $color_id,
			),
			'value'			=> $this->value[$color_id],
			'default'		=> ( isset( $color_code ) ) ? $color_code : '',
			'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
			'palettes'		=> ( isset( $color_palette ) ) ? $color_palette : false,
			'before'		=> "<label>{$color_title}</label>",
		), $this->value[$color_id] );
	}



	/**
	 * Helper Function to Check if is valid JSON object
	 */
	private function isJSON($string){
		return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
	private function make_title($string){
		$string = str_replace("-"," ",$string);
		$string = str_replace("_"," ",$string);
		return ucwords($string);
	}
	
}