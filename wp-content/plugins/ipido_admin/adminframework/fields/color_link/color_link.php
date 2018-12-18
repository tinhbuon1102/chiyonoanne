<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Color Link
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_color_link extends CSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		$settings = array(
			'regular'	=> ( empty($this->field['settings']['regular']) ) ? false : true,
			'hover'		=> ( empty($this->field['settings']['hover']) ) ? false : true,
			'visited'	=> ( empty($this->field['settings']['visited']) ) ? false : true,
            'active'	=> ( empty($this->field['settings']['active']) ) ? false : true,
			'focus'   	=> ( empty($this->field['settings']['focus']) ) ? false : true,
			'palettes'  => ( isset($this->field['settings']['palettes']) ) ? $this->field['settings']['palettes'] : false,
		);

		$defaults_value = array(
			'regular'	=> '',
			'hover'		=> '',
			'visited'	=> '',
            'active'	=> '',
            'focus'     => '',
		);

		$value			= wp_parse_args( $this->element_value(), $defaults_value );

		echo '<div class="csf-link_color csf-multifield">';

		if ($settings['regular'] === true) {
			echo csf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color_regular',
				'type'			=> 'color_picker',
				'name'			=> $this->element_name('[regular]'),
				'attributes'	=> array(
					'data-atts'		=> 'bgcolor',
				),
				'value'			=> $value['regular'],
				'default'		=> ( isset( $this->field['default']['regular'] ) ) ? $this->field['default']['regular'] : '',
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'palettes'		=> $settings['palettes'],
				'before'		=> '<label>'.__('Regular','csf-framework').'</label>',
			) );
		}
        if ($settings['hover'] === true) {
			echo csf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color_hover',
				'type'			=> 'color_picker',
				'name'			=> $this->element_name('[hover]'),
				'attributes'	=> array(
					'data-atts'		=> 'bgcolor',
				),
				'value'			=> $value['hover'],
				'default'		=> ( isset( $this->field['default']['hover'] ) ) ? $this->field['default']['hover'] : '',
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'palettes'		=> $settings['palettes'],
				'before'		=> '<label>'.__('Hover','csf-framework').'</label>',
			) );
        }
        if ($settings['visited'] === true) {
			echo csf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color_visited',
				'type'			=> 'color_picker',
				'name'			=> $this->element_name('[visited]'),
				'attributes'	=> array(
					'data-atts'		=> 'bgcolor',
				),
				'value'			=> $value['visited'],
				'default'		=> ( isset( $this->field['default']['visited'] ) ) ? $this->field['default']['visited'] : '',
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'palettes'		=> $settings['palettes'],
				'before'		=> '<label>'.__('Visited','csf-framework').'</label>',
			) );
		}
        if ($settings['active'] === true) {
			echo csf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color_active',
				'type'			=> 'color_picker',
				'name'			=> $this->element_name('[active]'),
				'attributes'	=> array(
					'data-atts'		=> 'bgcolor',
				),
				'value'			=> $value['active'],
				'default'		=> ( isset( $this->field['default']['active'] ) ) ? $this->field['default']['active'] : '',
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'palettes'		=> $settings['palettes'],
				'before'		=> '<label>'.__('Active','csf-framework').'</label>',
			) );
        }
        if ($settings['focus'] === true) {
			echo csf_add_element( array(
				'pseudo'		=> true,
				'id'			=> $this->field['id'].'_color_focus',
				'type'			=> 'color_picker',
				'name'			=> $this->element_name('[focus]'),
				'attributes'	=> array(
					'data-atts'		=> 'bgcolor',
				),
				'value'			=> $value['focus'],
				'default'		=> ( isset( $this->field['default']['focus'] ) ) ? $this->field['default']['focus'] : '',
				'rgba'			=> ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
				'palettes'		=> $settings['palettes'],
				'before'		=> '<label>'.__('Focus','csf-framework').'</label>',
			) );
		}

		echo '</div>';
		echo $this->element_after();

	}

}