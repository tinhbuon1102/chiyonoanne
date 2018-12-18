<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Spacing
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_spacing extends CSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		$settings = array(
			'all'		=> ( isset($this->field['settings']['all'])) ? true : '',
			'top'		=> ( empty($this->field['settings']['top'])) ? true : '',
			'right'		=> ( empty($this->field['settings']['right'])) ? true : '',
			'bottom'	=> ( empty($this->field['settings']['bottom'])) ? true : '',
			'left'		=> ( empty($this->field['settings']['left'])) ? true : '',
			'unit'		=> ( empty($this->field['settings']['unit'])) ? true : '',
		);

		$defaults_value = array(
			'all'		=> '',
			'top'		=> '',
			'right'		=> '',
			'bottom'	=> '',
			'left'		=> '',
			'unit'		=> '',
		);

		$value 			= wp_parse_args( $this->element_value(), $defaults_value );
		$value_all		= $value['all'];
		$value_top		= $value['top'];
		$value_right	= $value['right'];
		$value_bottom	= $value['bottom'];
		$value_left		= $value['left'];
		$value_unit		= $value['unit'];
		$is_chosen		= ( isset( $this->field['chosen'] ) && $this->field['chosen'] === false ) ? '' : 'chosen ';
		$chosen_rtl		= ( is_rtl() && ! empty( $is_chosen ) ) ? 'chosen-rtl ' : '';

		echo $this->element_before();
		echo '<div class="csf-spacing csf-multifield">';

		if ($settings['all'] === true) {
			echo csf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'text_addon',
				'name'		=> $this->element_name('[all]'),
				'settings'	=> array(
					'addon_value'	=> '<i class="fa fa-arrows"></i>',
				),
				'value'		=> $value_all,
				'attributes' => [
					'placeholder' => 'all'
				]
			) );
		} else {
			if ($settings['top'] === true) {
				echo csf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[top]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="fa fa-long-arrow-up"></i>',
					),
					'value'		=> $value_top,
					'attributes' => [
						'placeholder' => 'top'
					]
				) );
			}
			if ($settings['right'] === true) {
				echo csf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[right]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="fa fa-long-arrow-right"></i>',
					),
					'value'		=> $value_right,
					'attributes' => [
						'placeholder' => 'right'
					]
				) );
			}
			if ($settings['bottom'] === true) {
				echo csf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[bottom]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="fa fa-long-arrow-down"></i>',
					),
					'value'		=> $value_bottom,
					'attributes' => [
						'placeholder' => 'bottom'
					]
				) );
			}
			if ($settings['left'] === true) {
				echo csf_add_element( array(
					'pseudo'	=> true,
					'type'		=> 'text_addon',
					'name'		=> $this->element_name('[left]'),
					'settings'	=> array(
						'addon_value'	=> '<i class="fa fa-long-arrow-left"></i>',
					),
					'value'		=> $value_left,
					'attributes' => [
						'placeholder' => 'left'
					]
				) );
			}
		}
		
		if ($settings['unit'] === true) {
			echo csf_add_element( array(
				'pseudo'	=> true,
				'type'		=> 'select',
				'name'		=> $this->element_name('[unit]'),
				'options'	=> array(
					'em'	=> 'em',
					'px'	=> 'px',
					'%'		=> '%',
				),
				'value'		=> $value_unit,
			) );
		}

		echo '</div>';
		echo $this->element_after();

	}

}