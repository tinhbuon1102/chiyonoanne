<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Color Scheme
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_color_scheme extends CSFramework_Options {
    /**
     * CSFramework_Option_color_scheme constructor.
     * @param        $field
     * @param string $value
     * @param string $unique
     */
    public function __construct($field, $value = '', $unique = '') {
        parent::__construct($field, $value, $unique);
    }

    public function output() {
        if( empty($this->field['options']) ) {
            return;
        }
        echo $this->element_before();
        $field_name = $this->unique . '[' . $this->field['id'] . ']';
        echo '<fieldset id="csf-color-scheme">';
        foreach( $this->field['options'] as $label => $colors ) {
            $is_text = is_string($label);

            echo '<label><div class="color_palette_option">';
            echo '<input type="radio" name="' . $field_name . '" value="' . $label . '" ' . $this->checked($this->value, $label) . '>';
            echo '<div class="color-option">';
            echo '<label>' . $label . '</label>';
            echo '<table class="color-palette"> <tr>';
            foreach( $colors as $color ) {
                $color = '#' . ltrim($color);
                echo '<td style="background-color: ' . $color . ';"></td>';
            }
            echo '</tr></table>';
            echo '</div>';
            echo '</div></label>';

        }

        echo '</fieldset>';

        echo $this->element_after();
    }

}