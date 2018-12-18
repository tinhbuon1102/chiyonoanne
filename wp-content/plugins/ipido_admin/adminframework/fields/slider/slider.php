<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Slider
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_slider extends CSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		$defaults_value = array(
			'slider1'	=> '0',
			'slider2'	=> '0',
		);
		$this->value 	= wp_parse_args( $this->element_value(), $defaults_value );
		$value_slider1 	= (isset($value['slider1'])) ? $value['slider1'] : '';
		$value_slider2 	= (isset($value['slider2'])) ? $value['slider2'] : '';

		$settings = array(
			'step'  	=> ( empty( $this->field['settings']['step'] ) ) ? 1 : $this->field['settings']['step'],
			'unit'  	=> ( empty( $this->field['settings']['unit'] ) ) ? '' : $this->field['settings']['unit'],
			'min'   	=> ( empty( $this->field['settings']['min'] ) ) ? 0 : $this->field['settings']['min'],
			'max'   	=> ( empty( $this->field['settings']['max'] ) ) ? 100 : $this->field['settings']['max'],
			'round' 	=> ( empty( $this->field['settings']['round'] ) ) ? false : true,
			'tooltip'	=> ( empty( $this->field['settings']['tooltip'] ) ) ? false : true,
			'input'		=> ( empty( $this->field['settings']['input'] ) ) ? false : true,
			'handles'	=> ( empty( $this->field['settings']['handles'] ) ) ? false : true,
			'slider1'	=> $this->value['slider1'],
			'slider2'	=> $this->value['slider2'],
		);

		$input_type 	= ($settings['input']) ? 'text' : 'hidden';

		echo $this->element_before();
		echo '<div class="csf-slider" data-slider-options=\'' . json_encode( $settings ) . '\'>';

		echo csf_add_element( array(
			'pseudo'		=> true,
			'type'			=> 'text_addon',
			'name'			=> $this->element_name('[slider1]'),
			'value'			=> $this->value['slider1'],
			'default'		=> $this->value['slider1'],
			'class'			=> 'csf-slider_handler1',
			'attributes' 	=> [
				'placeholder' 	=> $settings['min'],
				'type'			=> $input_type
			],
			'settings'		=> [
				'type'			=> 'append',
				'addon_value'	=> $settings['unit']
			]
		) );
		
		echo '<div class="csf-slider-wrapper"></div>';
		
		if ($settings['handles']) { 

			echo csf_add_element( array(
				'pseudo'		=> true,
				'type'			=> 'text',
				'name'			=> $this->element_name('[slider2]'),
				'value'			=> $this->value['slider2'],
				'default'		=> $this->value['slider2'],
				'class'			=> 'csf-slider_handler2',
				'attributes' 	=> [
					'placeholder' 	=> $settings['max'],
					'type'			=> $input_type
				]
			) );
		}
		
		echo '</div>';

		echo $this->element_after();

	}

}