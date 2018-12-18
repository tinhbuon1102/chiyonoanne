<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Add framework element
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_add_element' ) ) {
	/**
	 * Adds A CSF Field & Renders it.
	 *
	 * @param array  $field
	 * @param string $value
	 * @param string $unique
	 * @param bool   $force
	 *
	 * @return string
	 */
	function csf_add_element( $field = array(), $value = '', $unique = '', $force = false ) {
		$output = '';

		$value   = ( !isset( $value ) && isset( $field['default'] ) ) ? $field['default'] : $value;
		$value   = ( isset( $field['value'] ) ) ? $field['value'] : $value;
		
		if ( isset( $field['instance_id'] ) && false === $force ) {
			$_instance = csf_field_registry()->get( $field['instance_id'] );
			if ( $_instance instanceof CSFramework_Options ) {
				ob_start();
				$_instance->final_output();
				return ob_get_clean();
			}
			return csf_add_element( $field, $value, $unique, true );
		} else {
			$class = 'CSFramework_Option_' . $field ['type'];
			if ( isset( $field['clone'] ) && true === $field['clone'] ) {
				$class = 'CSFramework_Field_Cloner';
			}
			csf_autoloader( $class );
			if ( class_exists( $class ) ) {
				ob_start();
				$element = new $class( $field, $value, $unique );
				$element->final_output();
				$output .= ob_get_clean();
			} else {
				$output .= '<p>' . sprintf( esc_html__( 'This field class is not available! %s', 'csf-framework' ), '<strong>' . $class . '</strong>' ) . ' </p > ';
			}
		}
		return $output;
	}
}
if ( ! function_exists( 'csf_unarray_fields' ) ) {
	/**
	 * Returns all field types that can be unarrayed.
	 *
	 * @return array
	 */
	function csf_unarray_fields() {
		return apply_filters( 'csf_unarray_fields_types', array( 'tab', 'group', 'fieldset', 'accordion' ) );
	}
}
if ( ! function_exists( 'csf_is_unarray_field' ) ) {
	/**
	 * Checks if field type is unarray.
	 *
	 * @param mixed $type .
	 *
	 * @return bool
	 */
	function csf_is_unarray_field( $type ) {
		if ( is_array( $type ) && isset( $type['clone'] ) && true === $type['clone'] ) {
			return true;
		} elseif ( is_array( $type ) && isset( $type['type'] ) ) {
			return in_array( $type['type'], csf_unarray_fields() );
		}
		return in_array( $type, csf_unarray_fields() );
	}
}
if ( ! function_exists( 'csf_is_unarrayed' ) ) {
	/**
	 * Checks if field is unarray.
	 *
	 * @param mixed $field .
	 *
	 * @return bool
	 */
	function csf_is_unarrayed( $field = array() ) {
		if ( csf_is_unarray_field( $field ) ) {
			if ( isset( $field['un_array'] ) && true === $field['un_array'] ) {
				return true;
			}
		}
		return false;
	}
}







/**
 *
 * Encode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_encode_string' ) ) {
  function csf_encode_string( $string ) {
    return rtrim( strtr( call_user_func( 'base'. '64' .'_encode', addslashes( gzcompress( serialize( $string ), 9 ) ) ), '+/', '-_' ), '=' );
  }
}

/**
 *
 * Decode string for backup options
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_decode_string' ) ) {
  function csf_decode_string( $string ) {
    return unserialize( gzuncompress( stripslashes( call_user_func( 'base'. '64' .'_decode', rtrim( strtr( $string, '-_', '+/' ), '=' ) ) ) ) );
  }
}

/**
 *
 * Get google font from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_get_google_fonts' ) ) {
  function csf_get_google_fonts() {

    global $csf_google_fonts;

    if( ! empty( $csf_google_fonts ) ) {

      return $csf_google_fonts;

    } else {

      ob_start();
      csf_locate_template( 'fields/typography/google-fonts.json' );
      $json = ob_get_clean();

      $csf_google_fonts = json_decode( $json );

      return $csf_google_fonts;
    }

  }
}

/**
 *
 * Get icon fonts from json file
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_get_icon_fonts' ) ) {
  function csf_get_icon_fonts( $file ) {

    ob_start();
    csf_locate_template( $file );
    $json = ob_get_clean();

    return json_decode( $json );

  }
}

/**
 *
 * Array search key & value
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_array_search' ) ) {
  function csf_array_search( $array, $key, $value ) {

    $results = array();

    if ( is_array( $array ) ) {
      if ( isset( $array[$key] ) && $array[$key] == $value ) {
        $results[] = $array;
      }

      foreach ( $array as $sub_array ) {
        $results = array_merge( $results, csf_array_search( $sub_array, $key, $value ) );
      }

    }

    return $results;

  }
}

/**
 *
 * Getting POST Var
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_get_var' ) ) {
  function csf_get_var( $var, $default = '' ) {

    if( isset( $_POST[$var] ) ) {
      return $_POST[$var];
    }

    if( isset( $_GET[$var] ) ) {
      return $_GET[$var];
    }

    return $default;

  }
}

/**
 *
 * Getting POST Vars
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'csf_get_vars' ) ) {
  function csf_get_vars( $var, $depth, $default = '' ) {

    if( isset( $_POST[$var][$depth] ) ) {
      return $_POST[$var][$depth];
    }

    if( isset( $_GET[$var][$depth] ) ) {
      return $_GET[$var][$depth];
    }

    return $default;

  }
}

if ( ! function_exists( 'csf_js_vars' ) ) {
	/**
	 * Converts PHP Array into JS JSON String with script tag and returns it.
	 *
	 * @param      $object_name
	 * @param      $l10n
	 * @param bool $with_script_tag
	 *
	 * @return string
	 */
	function csf_js_vars( $object_name = '', $l10n, $with_script_tag = true ) {
		foreach ( (array) $l10n as $key => $value ) {
			if ( ! is_scalar( $value ) ) {
				continue;
			}
			$l10n[ $key ] = html_entity_decode( (string) $value, ENT_QUOTES, 'UTF-8' );
		}
		$script = null;
		if ( ! empty( $object_name ) ) {
			$script = "var $object_name = " . wp_json_encode( $l10n ) . ';';
		} else {
			$script = wp_json_encode( $l10n );
		}
		if ( ! empty( $after ) ) {
			$script .= "\n$after;";
		}
		if ( $with_script_tag ) {
			return '<script type="text/javascript" >' . $script . '</script>';
		}
		return $script;
	}
}

/**
 * ERROR Handler
 */
global $csf_errors;
$csf_errors = array();
if ( ! function_exists( 'csf_add_errors' ) ) {
	/**
	 * Adds Error to global $csf_error array.
	 *
	 * @param $errs
	 *
	 */
	function csf_add_errors( $errs ) {
		global $csf_errors;
		if ( is_array( $csf_errors ) && is_array( $errs ) ) {
			$csf_errors = array_merge( $csf_errors, $errs );
		} else {
			$csf_errors = $errs;
		}
	}
}

if ( ! function_exists( 'csf_get_errors' ) ) {
	/**
	 * Returns gloabl $csf_errors.
	 *
	 * @return array
	 */
	function csf_get_errors() {
		global $csf_errors;
		return $csf_errors;
	}
}








if ( ! function_exists( 'csf_modern_navs' ) ) {
	/**
	 * Renders Modern Theme Menu
	 *
	 * @param      $navs
	 * @param      $class
	 * @param null $parent
	 */
	function csf_modern_navs( $navs, $class, $parent = null ) {
		$parent = ( null === $parent ) ? '' : 'data-parent-section="' . $parent . '"';
		foreach ( $navs as $i => $nav ) :
			$title = ( isset( $nav['title'] ) ) ? $nav['title'] : '';
			$href  = ( isset( $nav['href'] ) && false !== $nav['href'] ) ? $nav['href'] : '#';
			if ( ! empty( $nav['submenus'] ) ) {
				$is_active    = ( isset( $nav['is_active'] ) && true === $nav['is_active'] ) ? ' style="display: block;"' : '';
				$is_active_li = ( isset( $nav['is_active'] ) && true === $nav['is_active'] ) ? ' csf-tab-active ' : '';
				echo '<li class="csf-sub ' . $is_active_li . '">';
				echo '<a href="#" class="csf-arrow">' . $class->icon( $nav ) . ' ' . $title . '</a>';
				echo '<ul ' . $is_active . '>';
				csf_modern_navs( $nav['submenus'], $class, $nav['name'] );
				echo '</ul>';
				echo '</li>';
			} else {
				if ( isset( $nav['is_separator'] ) && true === $nav['is_separator'] ) {
					echo '<li><div class="csf-seperator">' . $class->icon( $nav ) . ' ' . $title . '</div></li>';
				} else {
					$is_active = ( isset( $nav['is_active'] ) && true === $nav['is_active'] ) ? "class='csf-section-active'" : '';
					echo '<li>';
					echo '<a ' . $is_active . ' href="' . $href . '" ' . $parent . ' data-section="' . $nav['name'] . '">' . $class->icon( $nav ) . ' ' . $title . '</a>';
					echo '</li>';
				}
			}
		endforeach;
	}
}
if ( ! function_exists( 'csf_simple_render_submenus' ) ) {
	/**
	 * @param array $menus
	 * @param null  $parent_name
	 * @param array $class
	 */
	function csf_simple_render_submenus( $menus = array(), $parent_name = null, $class = array() ) {
		global $csf_submenus;
		$return = array();
		$first  = current( $menus );
		$first  = isset( $first['name'] ) ? $first['name'] : false;
		foreach ( $menus as $nav ) {
			if ( isset( $nav['is_separator'] ) && true === $nav['is_separator'] ) {
				continue;
			}
			$title     = ( isset( $nav['title'] ) ) ? $nav['title'] : '';
			$is_active = ( isset( $nav['is_active'] ) && true === $nav['is_active'] ) ? ' current ' : '';
			if ( empty( $is_active ) ) {
				$is_active = ( $parent_name !== $class->active() && $first === $nav['name'] ) ? 'current' : $is_active;
			}
			$href = '#';
			if ( isset( $nav['href'] ) && ( false !== $nav['href'] && '#' !== $nav['href'] && true !== $nav['is_internal_url'] ) ) {
				$href = $nav['href'];
				$is_active .= ' has-link ';
			}
			if ( isset( $nav['query_args'] ) && is_array( $nav['query_args'] ) ) {
				$url  = remove_query_arg( array_keys( $nav['query_args'] ) );
				$href = add_query_arg( array_filter( $nav['query_args'] ), $url );
				$is_active .= ' has-link ';
			}
			$icon     = $class->icon( $nav );
			$return[] = '<li> <a href="' . $href . '" class="' . $is_active . '" data-parent-section="' . $parent_name . '" data-section="' . $nav['name'] . '">' . $icon . ' ' . $title . '</a>';
		}
		$csf_submenus[ $parent_name ] = implode( '|</li>', $return );
	}
}