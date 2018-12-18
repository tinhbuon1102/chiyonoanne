<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Image
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_Image extends CSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output(){

    echo $this->element_before();

    if( isset( $this->field['settings'] ) ) { extract( $this->field['settings'] ); }
    $upload_type  = ( isset( $upload_type  ) ) ? $upload_type  : 'image';
    $button_title = ( isset( $button_title ) ) ? $button_title : __( 'Add Image', 'csf-framework' );
    $frame_title  = ( isset( $frame_title  ) ) ? $frame_title  : __( 'Upload Image', 'csf-framework' );
    $insert_title = ( isset( $insert_title ) ) ? $insert_title : __( 'Use Image', 'csf-framework' );

    $preview = '';
    $value   = $this->element_value();
    $add     = ( ! empty( $this->field['add_title'] ) ) ? $this->field['add_title'] : __( 'Add Image', 'csf-framework' );
    $hidden  = ( empty( $value ) ) ? ' hidden' : '';

    // Preview Size
    $preview_size = ( isset( $preview_size ) ) ? $preview_size : null;
    $preview_size_attr = null;

    if ($preview_size){
      if (!is_array($preview_size)){
        $preview_size_attr = "data-preview-size='{$preview_size}'";
      } else {
        $width  = $preview_size['width'];
        $height = $preview_size['height'];
        $fit    = $preview_size['fit'];
        $preview_size_attr = "data-preview-size='custom' style='--csf-image-preview-size-width:{$width};--csf-image-preview-size-height:{$height};--csf-image-preview-size-fit:{$fit};'";
      }
    }

    if (!empty( $value )){
      if (isset($preview_size)){
        if (!is_array($preview_size)){
          $attachment_size = $preview_size;
        } else {
          $attachment_size = true;
        }
      } else {
        $attachment_size = 'thumbnail';
      }
      $attachment       = wp_get_attachment_image_src( $value, $attachment_size );
      $preview          = $attachment[0];
    }

    echo '<div class="csf-image-select">';
    echo '<div class="csf-image-preview'. $hidden .'" '.$preview_size_attr.'><div class="csf-preview"><img src="'. $preview .'" alt="preview" /></div></div>';
    echo '<a href="#" class="csf-button csf-button-primary csf-add" data-frame-title="'. $frame_title .'" data-upload-type="'. $upload_type .'" data-insert-title="'. $insert_title .'">'. $button_title .'</a>';
    echo '<a href="#" class="csf-button csf-button-warning csf-remove'. $hidden .'">'. __( 'Remove', 'csf-framework' ) .'</a>';
    echo '<input type="text" name="'. $this->element_name() .'" value="'. $this->element_value() .'"'. $this->element_class() . $this->element_attributes() .'/>';
    echo '</div>';

    echo $this->element_after();
  }

}
