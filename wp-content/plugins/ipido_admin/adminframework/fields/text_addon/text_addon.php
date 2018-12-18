<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Text Addon
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_text_addon extends CSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output(){

		$settings = array(
			'type'			=> ( empty( $this->field['settings']['type'] ) ) ? 'prepend' : $this->field['settings']['type'],
			'icon'			=> ( empty( $this->field['settings']['icon'] ) ) ? false : $this->field['settings']['icon'],
			'addon_value'	=> ( empty( $this->field['settings']['addon_value'] ) ) ? '' : $this->field['settings']['addon_value'],
		);

		$addon_icon = ($settings['icon']) ? 'csf-input-addon-icon' : '';

		echo $this->element_before();

		if ($settings['type'] === 'prepend') {
			echo '<div class="csf-input-addon-field csf-input-prepend">';
			echo '<span class="csf-input-addon '.$addon_icon.'">'.$settings['addon_value'].'</span>';
			echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
			echo '</div>';
		} else if ($settings['type'] === 'append') {
			echo '<div class="csf-input-addon-field csf-input-append">';
			echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
			echo '<span class="csf-input-addon '.$addon_icon.'">'.$settings['addon_value'].'</span>';
			echo '</div>';
		}
		echo $this->element_after();

	}

}