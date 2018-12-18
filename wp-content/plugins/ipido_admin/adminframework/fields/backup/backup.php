<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Backup
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Option_backup extends CSFramework_Options {

  public function __construct( $field, $value = '', $unique = '' ) {
    parent::__construct( $field, $value, $unique );
  }

  public function output() {

    echo $this->element_before();

    echo '<textarea name="'. $this->unique .'[import]"'. $this->element_class() . $this->element_attributes() .'></textarea>';
    submit_button( __( 'Import a Backup', 'csf-framework' ), 'primary csf-import-backup', 'backup', false );
    echo '<small>( '. __( 'copy-paste your backup string here', 'csf-framework' ).' )</small>';

    echo '<hr />';

    echo '<textarea name="_nonce"'. $this->element_class() . $this->element_attributes() .' disabled="disabled">'. csf_encode_string( get_option( $this->unique ) ) .'</textarea>';
    // echo '<a href="'. admin_url( 'admin-ajax.php?action=csf-export-options' ) .'" class="button button-primary" target="_blank">'. __( 'Export and Download Backup', 'csf-framework' ) .'</a>';
    echo '<a href="'. admin_url( 'admin-ajax.php?action=csf-export-options&option_array=' . $this->unique ) .'" class="button button-primary" target="_blank">'. __( 'Export and Download Backup', 'csf-framework' ) .'</a>'; 
    echo '<small>-( '. __( 'or', 'csf-framework' ) .' )-</small>';
    submit_button( __( 'Reset All Options', 'csf-framework' ), 'csf-warning-primary csf-reset-confirm', $this->unique . '[resetall]', false );
    echo '<small class="csf-text-warning">'. __( 'Please be sure for reset all of framework options.', 'csf-framework' ) .'</small>';

    echo $this->element_after();

  }

}
