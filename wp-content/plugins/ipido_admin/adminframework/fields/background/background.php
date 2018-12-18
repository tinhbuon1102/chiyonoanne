<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Background
 *
 * @since 1.0.0
 * @version 1.0.1
 *
 */
class CSFramework_Option_background extends CSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo $this->element_before();

    $settings = array(
			'repeat'		  => ( empty($this->field['settings']['repeat']) || ($this->field['settings']['repeat'] === false) ) ? false : true,
      'position'		=> ( empty($this->field['settings']['position']) || ($this->field['settings']['position'] === false) ) ? false : true,
			'attachment'	=> ( empty($this->field['settings']['attachment']) || ($this->field['settings']['attachment'] === false) ) ? false : true,
			'size'		    => ( empty($this->field['settings']['size']) || ($this->field['settings']['size'] === false) ) ? false : true,
      'color'		    => ( empty($this->field['settings']['color']) || ($this->field['settings']['color'] === false) ) ? false : true,
      'palettes'    => ( isset($this->field['settings']['palettes']) ) ? $this->field['settings']['palettes'] : false,
		);

    $value_defaults = array(
      'image'       => '',
      'repeat'      => '',
      'position'    => '',
      'attachment'  => '',
      'size'        => '',
      'color'       => '',
    );

    $this->value  = wp_parse_args( $this->element_value(), $value_defaults );

    if( isset( $this->field['settings'] ) ) { extract( $this->field['settings'] ); }
    $upload_type  = ( isset( $upload_type  ) ) ? $upload_type  : 'image';
    $button_title = ( isset( $button_title ) ) ? $button_title : __( 'Upload', 'csf-framework' );
    $frame_title  = ( isset( $frame_title  ) ) ? $frame_title  : __( 'Upload', 'csf-framework' );
    $insert_title = ( isset( $insert_title ) ) ? $insert_title : __( 'Use Image', 'csf-framework' );

    $preview = '';
    $value   = $this->value['image'];
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

    echo '<div class="csf-image-preview'. $hidden .'" '.$preview_size_attr.'><div class="csf-preview"><img src="'. $preview .'" alt="preview" /></div></div>';
    
    echo '<div class="csf-field-upload-form">';
    echo '<input type="text" name="'. $this->element_name( '[image]' ) .'" value="'. $this->value['image'] .'"'. $this->element_class() . $this->element_attributes() .'/>';
    echo '<a href="#" class="csf-button csf-button-primary csf-add" data-frame-title="'. $frame_title .'" data-upload-type="'. $upload_type .'" data-insert-title="'. $insert_title .'">'. $button_title .'</a>';
    echo '<a href="#" class="csf-button csf-button-warning csf-remove'. $hidden .'">'. __( 'Remove', 'csf-framework' ) .'</a>';
    echo '</div>';

    // background attributes
    echo '<fieldset><div class="csf-multifield">';
    if ($settings['repeat'] === true){
      echo csf_add_element( array(
          'pseudo'          => true,
          'type'            => 'select',
          'name'            => $this->element_name( '[repeat]' ),
          'options'         => array(
            ''              => 'repeat',
            'repeat-x'      => 'repeat-x',
            'repeat-y'      => 'repeat-y',
            'no-repeat'     => 'no-repeat',
            'inherit'       => 'inherit',
          ),
          'attributes'      => array(
            'data-atts'     => 'repeat',
          ),
          'value'           => $this->value['repeat'],
          'before'		      => '<label>'.__('Repeat','csf-framework').'</label>',
      ) );
    }
    if ($settings['position'] === true){
      echo csf_add_element( array(
          'pseudo'          => true,
          'type'            => 'select',
          'name'            => $this->element_name( '[position]' ),
          'options'         => array(
            ''              => 'left top',
            'left center'   => 'left center',
            'left bottom'   => 'left bottom',
            'right top'     => 'right top',
            'right center'  => 'right center',
            'right bottom'  => 'right bottom',
            'center top'    => 'center top',
            'center center' => 'center center',
            'center bottom' => 'center bottom'
          ),
          'attributes'      => array(
            'data-atts'     => 'position',
          ),
          'value'           => $this->value['position'],
          'before'		      => '<label>'.__('Position','csf-framework').'</label>',
      ) );
    }
    if ($settings['attachment'] === true){
      echo csf_add_element( array(
          'pseudo'          => true,
          'type'            => 'select',
          'name'            => $this->element_name( '[attachment]' ),
          'options'         => array(
            ''              => 'scroll',
            'fixed'         => 'fixed',
          ),
          'attributes'      => array(
            'data-atts'     => 'attachment',
          ),
          'value'           => $this->value['attachment'],
          'before'		      => '<label>'.__('Attachment','csf-framework').'</label>',
      ) );
    }
    if ($settings['size'] === true){
      echo csf_add_element( array(
          'pseudo'          => true,
          'type'            => 'select',
          'name'            => $this->element_name( '[size]' ),
          'options'         => array(
            ''              => 'size',
            'cover'         => 'cover',
            'contain'       => 'contain',
            'inherit'       => 'inherit',
            'initial'       => 'initial',
          ),
          'attributes'      => array(
            'data-atts'     => 'size',
          ),
          'value'           => $this->value['size'],
          'before'		      => '<label>'.__('Size','csf-framework').'</label>',
      ) );
    }
    if ($settings['color'] === true){
      echo csf_add_element( array(
          'pseudo'          => true,
          'id'              => $this->field['id'].'_color',
          'type'            => 'color_picker',
          'name'            => $this->element_name('[color]'),
          'attributes'      => array(
            'data-atts'     => 'bgcolor',
          ),
          'value'           => $this->value['color'],
          'default'         => ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '',
          'rgba'            => ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
          'palettes'		    => $settings['palettes'],
          'before'		      => '<label>'.__('Color','csf-framework').'</label>',
      ) );
    }
    echo '</div></fieldset>';

    echo $this->element_after();

  }
}
