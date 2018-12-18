<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Accordion
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_accordion extends CSFramework_Options {

	public function __construct( $field, $value = '', $unique = '' ) {
		parent::__construct( $field, $value, $unique );
	}

	public function output() {

		echo $this->element_before();

		$title 		= $this->field['accordion_title'];

		echo '<div class="csf-accordion-inner-section">';

		foreach($this->field['accordion_content'] as $accordion){

			$accordion_id = $accordion['id'];
			echo '
					<div class="csf-accordion-title">
						<h4>'.$accordion['title'].'</h4>
					</div>
				';

			echo '<div class="csf-accordion-content">';

			foreach ( $accordion['fields'] as $field ) {

				$accordion_value = isset($this->value[$accordion_id]) ? $this->value[$accordion_id] : array();

				$default    = ( isset( $field['default'] ) ) ? $field['default'] : '';
				$field_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';
				$field_value = ( isset( $accordion_value[$field_id] ) ) ? $accordion_value[$field_id] : $default;
				$unique_id   = $this->unique .'['. $this->field['id'] .']['. $accordion_id .']';

				if ( ! empty( $this->field['un_array'] ) ) {
					// echo csf_add_element( $field, csf_get_option( $field_id ), $this->unique );
				} else {
					// echo csf_add_element( $field, $field_value, $unique_id );
					echo csf_add_element( $field, $field_value, $unique_id );
				}
			}
			echo '</div>';
		}

		echo '</div>';

		echo $this->element_after();

	}

}
