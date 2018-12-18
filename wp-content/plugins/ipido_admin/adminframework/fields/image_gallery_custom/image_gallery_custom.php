<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Image Gallery Custom
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_image_gallery_custom extends CSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo $this->element_before();

    $value  = $this->element_value();
    $hidden = ( empty( $value ) ) ? ' hidden' : '';
    $visible = ( !empty( $value ) ) ? ' hidden' : '';

    // Settings
    if( isset( $this->field['settings'] ) ) { 
      $settings     = $this->field['settings'];
      $images_path  = $settings['images_path'];
    }


    echo '<div class="csf-image-select">';
    echo '<div class="csf-image-preview'. $hidden .'"><img src="'. CSF_URI . $images_path . $value .'"></i></div>';
    echo '<div class="csf-field-wrapper-horizontal">';
    echo '<a href="#" class="csf-button csf-button-primary csf-image-add" data-images-path="'.$images_path.'">'. __( 'Add image', 'csf-framework' ) .'</a>';
    echo '<a href="#" class="csf-button csf-button-warning csf-image-remove'. $hidden .'">'. __( 'Remove image', 'csf-framework' ) .'</a>';
    echo '<input type="text" name="'. $this->element_name() .'" value="'. $value .'"'. $this->element_class( 'csf-image-value' ) . $this->element_attributes() .' />';
    echo '</div>';
    echo '</div>';

    echo $this->element_after();

  }

}
