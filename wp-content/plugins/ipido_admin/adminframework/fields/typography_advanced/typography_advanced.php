<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Typography Advanced
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_typography_advanced extends CSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		echo '<div class="csf-typography_advanced csf-multifield">';

		$defaults_value = array(
			'family'	=> 'Arial',
			'variant'	=> 'regular',
			'font'		=> 'websafe',
			'size'		=> 12,
			'height'	=> 20,
			'spacing'	=> '0',
			'align'		=> 'left',
			'transform'	=> 'none',
			'color'		=> '#000',
			'preview'	=> 'Lorem ipsum dolor sit amet',
		);

		$default_variants = apply_filters( 'csf_websafe_fonts_variants', array(
			'regular',
			'italic',
			'700',
			'700italic',
			'inherit'
		));

		$websafe_fonts = apply_filters( 'csf_websafe_fonts', array(
			'Arial',
			'Arial Black',
			'Comic Sans MS',
			'Impact',
			'Lucida Sans Unicode',
			'Tahoma',
			'Trebuchet MS',
			'Verdana',
			'Courier New',
			'Lucida Console',
			'Georgia, serif',
			'Palatino Linotype',
			'Times New Roman'
		));

		$value 				= wp_parse_args( $this->element_value(), $defaults_value );

		$family_value 		= $value['family'];
		$variant_value 		= $value['variant'];
		$value_size			= $value['size'];
		$value_height 		= $value['height'];
		$value_spacing 		= $value['spacing'];
		$value_align 		= $value['align'];
		$value_transform 	= $value['transform'];
		$value_color		= $value['color'];

		// Default Preview
		$value_preview		= (isset($this->field['default']['preview'])) ? $this->field['default']['preview'] : 'Lorem ipsum dolor sit amet, consectetur adipisicing elit.';

		$is_variant 		= ( isset( $this->field['variant'] ) && $this->field['variant'] === false ) ? false : true;
		$is_chosen 			= ( isset( $this->field['chosen'] ) && $this->field['chosen'] === false ) ? '' : 'chosen ';
		$google_json 		= csf_get_google_fonts();
		$chosen_rtl 		= ( is_rtl() && ! empty( $is_chosen ) ) ? 'chosen-rtl ' : '';

		// Field Settings
		$settings = $this->field['settings'];


		if (is_object($google_json)){
			$googlefonts 			= array();

			foreach ( $google_json->items as $key => $font ) {
				$googlefonts[$font->family] = $font->variants;
			}

			$is_google 	= ( array_key_exists( $family_value, $googlefonts ) ) ? true : false;
			
			// Websafe Fonts
			$websafe_typography = array();
			$websafe_variants 	= array();
			foreach ( $websafe_fonts as $websafe_key => $websafe_value ) {
				$websafe_typography[$websafe_key] 	= $websafe_value ."|data-type:websafe";
				$websafe_variants[$websafe_key]		= $default_variants;
			}

			// Google Fonts
			$googlefonts_typography = array();
			$googlefonts_variants	= array();
			foreach ( $googlefonts as $google_key => $google_value) {
				$googlefonts_typography[$google_key] = $google_key ."|data-type:google";
				$googlefonts_variants[$google_key] = $google_value;
			}

			// Full List
			$typography_family_list = array(
				__( 'Web Safe Fonts', 'csf-framework' ) => $websafe_typography,
				__( 'Google Fonts', 'csf-framework' ) 	=> $googlefonts_typography,
			);
			$typography_family_variants = array(
				'websafe'	=> $websafe_variants,
				'google'	=> $googlefonts_variants,
			);
			$typography_family_variants = json_encode($typography_family_variants);

			if( ! empty( $is_variant ) ) {
				$variants_options = array();

				$variants = ( $is_google ) ? $googlefonts[$family_value] : $default_variants;
				$variants = ( $value['font'] === 'google' || $value['font'] === 'websafe' ) ? $variants : array( 'regular' );

				foreach ( $variants as $variant ) {
					$variants_options[$variant] = $variant;
				}
			}


			// Show Elements
			if ($settings['family'] !== false){
				echo csf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'select',
					'name'		=> $this->element_name( '[family]' ),
					'options'	=> $typography_family_list,
					'value'		=> $family_value,
					'class'		=> 'csf-typo-family',
					'before'	=> '<label>'.__('Font Family','csf-framework').'</label>',
					'chosen'	=> false,
					'attributes'	=> array(
						'data-variants' => $typography_family_variants
					),
				));
			}
			if ($settings['variant'] !== false){
				echo csf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'select',
					'name'		=> $this->element_name( '[variant]' ),
					'options'	=> $variants_options,
					'value'		=> $variant_value,
					'class'		=> 'csf-typo-variant',
					'before'	=> '<label>'.__('Font Weight & Style','csf-framework').'</label>',
				));
			}
			if ($settings['size'] !== false){
				echo csf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[size]'),
					'settings'	=> array(
						'type'			=> 'append',
						'addon_value'	=> 'px',
					),
					'value'		=> $value_size,
					'attributes' => [
						'placeholder' => 'size'
					],
					'class'		=> 'csf-typo-size',
					'before'	=> '<label>'.__('Font Size','csf-framework').'</label>',
				) );
			}

			if ($settings['height'] !== false){
				echo csf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[height]'),
					'settings'	=> array(
						'type'			=> 'append',
						'addon_value'	=> 'px',
					),
					'value'		=> $value_height,
					'attributes' => [
						'placeholder' => 'height'
					],
					'class'		=> 'csf-typo-height',
					'before'	=> '<label>'.__('Line Height','csf-framework').'</label>',
				) );
			}

			if ($settings['spacing'] !== false){
				echo csf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[spacing]'),
					'settings'	=> array(
						'type'			=> 'append',
						'addon_value'	=> 'px',
					),
					'value'		=> $value_spacing,
					'attributes' => [
						'placeholder' => 'spacing'
					],
					'class'		=> 'csf-typo-spacing',
					'before'	=> '<label>'.__('Letter Spacing','csf-framework').'</label>',
				) );
			}

			if ($settings['align'] !== false){
				echo csf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'select',
					'name'		=> $this->element_name( '[align]' ),
					'options'	=> [
						'left'		=> __('Align Left','csf-framework'),
						'center'	=> __('Align Center','csf-framework'),
						'right'		=> __('Align Right','csf-framework'),
						'justify'	=> __('Justify','csf-framework'),
					],
					'value'		=> $value_align,
					'class'		=> 'csf-typo-align',
					'before'	=> '<label>'.__('Text Align','csf-framework').'</label>',
				));
			}

			if ($settings['transform'] !== false){
				echo csf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'select',
					'name'		=> $this->element_name( '[transform]' ),
					'options'	=> [
						'none'			=> __('None','csf-framework'),
						'capitalize'	=> __('Capitalize','csf-framework'),
						'uppercase'		=> __('Uppercase','csf-framework'),
						'lowercase'		=> __('Lowercase','csf-framework'),
						'initial'		=> __('Initial','csf-framework'),
						'inherit'		=> __('Inherit','csf-framework'),
					],
					'value'		=> $value_transform,
					'class'		=> 'csf-typo-transform',
					'before'	=> '<label>'.__('Text Transform','csf-framework').'</label>',
				));
			}

			if ($settings['color'] !== false){
				echo csf_add_element( array(
					'pseudo'		=> true,
					'id'			=> $this->field['id'].'_color',
					'type'			=> 'color_picker',
					'name'			=> $this->element_name('[color]'),
					'attributes'	=> array(
						'data-atts'		=> 'bgcolor',
					),
					'value'			=> $value_color,
					'default'		=> ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '',
					'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
					'class'			=> 'csf-typo-color',
					'before'		=> '<label>'.__('Font Color','csf-framework').'</label>',
				));
			}


			$preview_styles = "--csf-typo-preview-weight: $variant_value; --csf-typo-preview-size: $value_size; --csf-typo-preview-size: $value_height; --csf-typo-preview-align: $value_align; --csf-typo-preview-color: $value_color";

			echo 	'<div class="csf-typo-preview" data-preview-id="csf-typo-preview_'.$this->field['id'].'_preview" id="csf-typo-preview_'.$this->field['id'].'_preview" style="'.$preview_styles.'">
						<div class="csf-typo-preview-toggle"></div>
						<p>'.$value_preview.'</p>
					</div>';

			echo '<input type="text" name="'. $this->element_name( '[font]' ) .'" class="csf-typo-font hidden" data-atts="font" value="'. $value['font'] .'" />';

		} else {

			echo __( 'Error! Can not load json file.', 'csf-framework' );

		}

		echo '</div>';

		echo $this->element_after();

	}

}
