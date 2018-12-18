<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Metabox Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
class CSFramework_Metabox extends CSFramework_Abstract{

  /**
   *
   * metabox options
   * @access public
   * @var array
   *
   */
  public $options = array();

  /**
   *
   * instance
   * @access private
   * @var class
   *
   */
  private static $instance = null;

  // run metabox construct
  public function __construct( $options ){

    $this->options = apply_filters( 'cs_metabox_options', $options );

    if( ! empty( $this->options ) ) {
      $this->addAction( 'add_meta_boxes', 'add_meta_box' );
      $this->addAction( 'save_post', 'save_post', 10, 2 );
    }

  }

  // instance
  public static function instance( $options = array() ){
    if ( is_null( self::$instance ) && CSF_ACTIVE_METABOX ) {
      self::$instance = new self( $options );
    }
    return self::$instance;
  }

  // add metabox
  public function add_meta_box( $post_type ) {

    foreach ( $this->options as $value ) {
      add_meta_box( $value['id'], $value['title'], array( &$this, 'render_meta_box_content' ), $value['post_type'], $value['context'], $value['priority'], $value );
    }

  }

  // metabox render content
  public function render_meta_box_content( $post, $callback ) {

    global $post, $cs_errors;

    wp_nonce_field( 'csf-framework-metabox', 'csf-framework-metabox-nonce' );
    $nav_style    = 'csf-framework-tabstyle_'.$callback['args']['style'];
    $unique       = $callback['args']['id'];
    $sections     = $callback['args']['sections'];
    $meta_value   = get_post_meta( $post->ID, $unique, true );
    $transient    = get_transient( 'csf-metabox-transient' );
    $cs_errors    = $transient['errors'];
    $has_nav      = ( count( $sections ) >= 2 && $callback['args']['context'] != 'side' ) ? true : false;
    $show_all     = ( ! $has_nav ) ? ' csf-show-all' : '';
    $section_name = ( ! empty( $sections[0]['fields'] ) ) ? $sections[0]['name'] : $sections[1]['name'];
    $section_id   = ( ! empty( $transient['ids'][$unique] ) ) ? $transient['ids'][$unique] : $section_name;
    $section_id   = cs_get_var( 'csf-section', $section_id );

    echo '<div class="csf-framework csf-metabox-framework '.$nav_style.'">';

      echo '<input type="hidden" name="cs_section_id['. $unique .']" class="csf-reset" value="'. $section_id .'">';

      echo '<div class="csf-body'. $show_all .'">';

        if( $has_nav ) {

          echo '<div class="csf-nav">';

            echo '<ul>';
            foreach( $sections as $value ) {

              $tab_icon = ( ! empty( $value['icon'] ) ) ? '<i class="csf-icon '. $value['icon'] .'"></i>' : '';

              if( isset( $value['fields'] ) ) {
                $active_section = ( $section_id == $value['name'] ) ? ' class="csf-section-active"' : '';
                echo '<li><a href="#"'. $active_section .' data-section="'. $value['name'] .'">'. $tab_icon . $value['title'] .'</a></li>';
              } else {
                echo '<li><div class="csf-seperator">'. $tab_icon . $value['title'] .'</div></li>';
              }

            }
            echo '</ul>';

          echo '</div>';

        }

        echo '<div class="csf-content">';

          echo '<div class="csf-sections">';
          foreach( $sections as $val ) {

            if( isset( $val['fields'] ) ) {

              $active_content = ( $section_id == $val['name'] ) ? ' style="display: block;"' : '';

              echo '<div id="csf-tab-'. $val['name'] .'" class="csf-section"'. $active_content .'>';
              echo ( isset( $val['title'] ) ) ? '<div class="csf-section-title"><h3>'. $val['title'] .'</h3></div>' : '';

              foreach ( $val['fields'] as $field_key => $field ) {

                $default    = ( isset( $field['default'] ) ) ? $field['default'] : '';
                $elem_id    = ( isset( $field['id'] ) ) ? $field['id'] : '';
                $elem_value = ( is_array( $meta_value ) && isset( $meta_value[$elem_id] ) ) ? $meta_value[$elem_id] : $default;

                echo cs_add_element( $field, $elem_value, $unique );

              }
              echo '</div>';

            }
          }
          echo '</div>';

          echo '<div class="clear"></div>';

        echo '</div>';

        echo ( $has_nav ) ? '<div class="csf-nav-background"></div>' : '';

        echo '<div class="clear"></div>';

      echo '</div>';

    echo '</div>';

  }

  // save metabox options
  public function save_post( $post_id, $post ) {

    if ( wp_verify_nonce( cs_get_var( 'csf-framework-metabox-nonce' ), 'csf-framework-metabox' ) ) {

      $errors = array();
      $post_type = cs_get_var( 'post_type' );

      foreach ( $this->options as $request_value ) {

        if( in_array( $post_type, (array) $request_value['post_type'] ) ) {

          $request_key = $request_value['id'];
          $request = cs_get_var( $request_key, array() );
          // $request = $this->array_filter_recursive($request);
          // echo phpinfo();
          // echo sizeof($_POST['cs_uls_settings'],1);
          // echo "<h1>CRUSH!</h1>";
          // echo "Request Keyx: $request_key";
          // echo '<pre>';
          // print_r($request);
          // print_r($_POST);
          // echo '</pre>';
          // return false;

          // ignore _nonce
          if( isset( $request['_nonce'] ) ) {
            unset( $request['_nonce'] );
          }

          foreach( $request_value['sections'] as $key => $section ) {

            if( isset( $section['fields'] ) ) {

              foreach( $section['fields'] as $field ) {

                if( isset( $field['type'] ) && isset( $field['id'] ) ) {

                  $field_value = cs_get_vars( $request_key, $field['id'] );

                  // sanitize options
                  if( isset( $field['sanitize'] ) && $field['sanitize'] !== false ) {
                    $sanitize_type = $field['sanitize'];
                  } else if ( ! isset( $field['sanitize'] ) ) {
                    $sanitize_type = $field['type'];
                  }

                  if( has_filter( 'cs_sanitize_'. $sanitize_type ) ) {
                    $request[$field['id']] = apply_filters( 'cs_sanitize_' . $sanitize_type, $field_value, $field, $section['fields'] );
                  }

                  // validate options
                  if ( isset( $field['validate'] ) && has_filter( 'cs_validate_'. $field['validate'] ) ) {

                    $validate = apply_filters( 'cs_validate_' . $field['validate'], $field_value, $field, $section['fields'] );

                    if( ! empty( $validate ) ) {

                      $meta_value = get_post_meta( $post_id, $request_key, true );

                      $errors[$field['id']] = array( 'code' => $field['id'], 'message' => $validate, 'type' => 'error' );
                      $default_value = isset( $field['default'] ) ? $field['default'] : '';
                      $request[$field['id']] = ( isset( $meta_value[$field['id']] ) ) ? $meta_value[$field['id']] : $default_value;

                    }

                  }

                }

              }

            }

          }

          $request = apply_filters( 'cs_save_post', $request, $request_key, $post );
          $request = $this->array_filter_recursive($request);
          if( empty( $request ) ) {

            delete_post_meta( $post_id, $request_key );

          } else {

            if( get_post_meta( $post_id, $request_key ) ) {

              update_post_meta( $post_id, $request_key, $request );

            } else {

              add_post_meta( $post_id, $request_key, $request );

            }

          }

          $transient['ids'][$request_key] = cs_get_vars( 'cs_section_id', $request_key );
          $transient['errors'] = $errors;

        }

      }

      set_transient( 'csf-metabox-transient', $transient, 10 );

    }

  }

  // Filter $request on save_post
  // @description To remove empty array fields
  public function array_filter_recursive($array) {
    foreach ($array as $key => &$value) {
      if (empty($value)) {
        unset($array[$key]);
      }
      else {
        if (is_array($value)) {
          $value = $this->array_filter_recursive($value);
          if (empty($value)) {
            unset($array[$key]);
          }
        }
      }
    }
    return $array;
  }
  

}
