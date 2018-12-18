<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Select
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_select extends CSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo $this->element_before();

    if( isset( $this->field['options'] ) ) {

      $options    = $this->field['options'];
      $class      = $this->element_class();
      $options    = ( is_array( $options ) ) ? $options : array_filter( $this->element_data( $options ) );
      $extra_name = ( isset( $this->field['attributes']['multiple'] ) ) ? '[]' : '';
      $chosen_rtl = ( is_rtl() && strpos( $class, 'chosen' ) ) ? 'chosen-rtl' : '';

      echo '<select name="'. $this->element_name( $extra_name ) .'"'. $this->element_class( $chosen_rtl ) . $this->element_attributes() .'>';

      echo ( isset( $this->field['default_option'] ) ) ? '<option value="">'.$this->field['default_option'].'</option>' : '';

      if( !empty( $options ) ){
        foreach ( $options as $key => $value ) {
          if ( is_array($value) ) {
            echo '<optgroup label="'.$key.'">';

            foreach ($value as $key => $value) {
              $value_has_attrs  = explode("|",$value,2);
              $value            = $value_has_attrs[0];
              $option_attrs     = '';
              
              if (isset($value_has_attrs[1])){
                $attrs = explode("|",$value_has_attrs[1]);

                foreach($attrs as $attr){
                  $_attr = explode(":",$attr);
                  $attr_name  = $_attr[0];
                  $attr_value = $_attr[1];
                  $option_attrs .= " {$attr_name}='{$attr_value}'";
                }
              }
              echo '<option value="'. $key .'" '. $this->checked( $this->element_value(), $key, 'selected' ) . $option_attrs .'>'. $value .'</option>';
            }
            echo '</optgroup>';
          } else {
            $value_has_attrs  = explode("|",$value,2);
            $value            = $value_has_attrs[0];
            $option_attrs     = '';
            
            if (isset($value_has_attrs[1])){
              $attrs = explode("|",$value_has_attrs[1]);

              foreach($attrs as $attr){
                $_attr = explode(":",$attr);
                $attr_name  = $_attr[0];
                $attr_value = $_attr[1];
                $option_attrs .= " {$attr_name}='{$attr_value}'";
              }
            }
            echo '<option value="'. $key .'" '. $this->checked( $this->element_value(), $key, 'selected' ) . $option_attrs .'>'. $value .'</option>';
          }
        }
      }

      echo '</select>';

    }

    echo $this->element_after();

  }

}
