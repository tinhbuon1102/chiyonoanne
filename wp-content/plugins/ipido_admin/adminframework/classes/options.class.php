<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Options Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
abstract class CSFramework_Options extends CSFramework_Abstract {

	/**
	 * total_cols
	 *
	 * @var int
	 */
	public static $total_cols = 0;

	/**
	 * field
	 *
	 * @var array|null
	 */
	public $field = null;

	/**
	 * value
	 *
	 * @var null|string|array
	 */
	public $value = null;

	/**
	 * org_value
	 *
	 * @var null|string
	 */
	public $org_value = null;

	/**
	 * unique
	 *
	 * @var null|string
	 */
	public $unique = null;

	/**
	 * multilang
	 *
	 * @var bool|mixed|null
	 */
	public $multilang = null;

	/**
	 * row_after
	 *
	 * @var null
	 */
	public $row_after = null;

	/**
	 * js_settings
	 *
	 * @var null
	 */
	public $js_settings = null;

	/**
	 * uid
	 *
	 * @var null
	 */
	public $uid = null;



  public function __construct( $field = array(), $value = '', $unique = '' ) {
    // $this->field      = $field;
    $this->field      = wp_parse_args( $field, $this->get_defaults() );
    $this->value      = $value;
    $this->org_value  = $value;
    $this->unique     = $unique;
    $this->multilang  = $this->element_multilang();
  }

  public function element_value( $value = '' ) {
    $value = $this->value;
    if ( is_array( $this->multilang ) && is_array( $value ) ) {
      $current  = $this->multilang['current'];
      if( isset( $value[$current] ) ) {
        $value = $value[$current];
      } else if( $this->multilang['current'] == $this->multilang['default'] ) {
        $value = $this->value;
      } else {
        $value = '';
      }
    } else if ( ! is_array( $this->multilang ) && isset( $this->value['multilang'] ) && is_array( $this->value ) ) {
      $value = array_values( $this->value );
      $value = $value[0];
    } else if ( is_array( $this->multilang ) && ! is_array( $value ) && ( $this->multilang['current'] != $this->multilang['default'] ) ) {
      $value = '';
    }
    return $value;

  }
  public function element_name( $extra_name = '', $multilang = false ) {

    $element_id      = ( isset( $this->field['id'] ) ) ? $this->field['id'] : '';
    $extra_multilang = ( ! $multilang && is_array( $this->multilang ) ) ? '['. $this->multilang['current'] .']' : '';
    return ( isset( $this->field['name'] ) ) ? $this->field['name'] . $extra_name : $this->unique .'['. $element_id .']'. $extra_multilang . $extra_name;

  }
  public function element_name______( $extra_name = '', $multilang = false ) {
		$element_id      = ( isset( $this->field ['id'] ) ) ? $this->field ['id'] : '';
		$extra_multilang = ( ! $multilang && is_array( $this->multilang ) ) ? '[' . $this->multilang ['current'] . ']' : '';
		$unique          = $this->get_unique( $element_id ) . $extra_multilang . $extra_name;
		$fname           = $unique;
		if ( isset( $this->field['name'] ) ) {
			$fname = $this->field['name'] . $extra_name;
		} elseif ( isset( $this->field['name_before'] ) || isset( $this->field['name_after'] ) ) {
			$fname = isset( $this->field['name_before'] ) ? $this->field['name_before'] . $fname : $fname;
			$fname = isset( $this->field['name_after'] ) ? $fname . $this->field['name_after'] : $fname;
		}

		#return ( isset( $this->field ['name'] ) ) ? $this->field ['name'] . $extra_name : $unique;
		return $fname;
	}

  public function element_type() {
    $type = ( isset( $this->field['attributes']['type'] ) ) ? $this->field['attributes']['type'] : $this->field['type'];
    return $type;
	}
	
	public function element_raw_type() {
    $type = $this->field['type'];
    return $type;
  }

  public function element_class( $el_class = '' ) {
    $field_class = ( isset( $this->field['class'] ) ) ? ' ' . $this->field['class'] : '';
    return ( $field_class || $el_class ) ? ' class="'. $el_class . $field_class .'"' : '';
  }

  public function element_attributes( $el_attributes = array(), $extra_more = array() ) {
		$attributes = ( isset( $this->field ['attributes'] ) ) ? $this->field ['attributes'] : array();

		if ( isset( $this->field['style'] ) ) {
			$attributes['style'] = $this->field['style'];
		}

		$element_id  = ( isset( $this->field ['id'] ) ) ? $this->field ['id'] : '';
		$is_in_array = in_array( $this->field['type'], array( 'text', 'textarea' ) );

		if ( false !== $el_attributes ) {
			$sub_elemenet  = ( isset( $this->field ['sub'] ) ) ? 'sub-' : '';
			$el_attributes = ( is_string( $el_attributes ) || is_numeric( $el_attributes ) ) ? array(
				'data-' . $sub_elemenet . 'depend-id' => $element_id . '_' . $el_attributes,
			) : $el_attributes;
			$el_attributes = ( empty( $el_attributes ) && isset( $element_id ) ) ? array(
				'data-' . $sub_elemenet . 'depend-id' => $element_id,
			) : $el_attributes;
		}

		if ( true === $is_in_array && ( isset( $this->field['limit'] ) && $this->field['limit'] > 0 ) ) {
			$el_attributes['data-limit-element'] = true;
		}

		if ( ! empty( $extra_more ) ) {
			$el_attributes = wp_parse_args( $el_attributes, $extra_more );
		}

		$attributes = wp_parse_args( $attributes, $el_attributes );

		return $this->array_to_html_attrs( $attributes );
	}

  public function element_before() {
    return ( isset( $this->field['before'] ) ) ? $this->field['before'] : '';
  }

  public function element_after() {
    $out = $this->element_text_limit();

		$out .= $this->element_desc_after();
		$out .= $this->element_info();
    $out .= ( isset( $this->field['after'] ) ) ? $this->field['after'] : '';
    $out .= $this->element_after_multilang();
    $out .= $this->element_get_error();
    // $out .= $this->element_help();
    $out .= $this->element_debug();
    $out .= $this->element_js_settings();
    return $out;

  }

  public function element_debug() {

    $out = '';

    if( ( isset( $this->field['debug'] ) && $this->field['debug'] === true ) || ( defined( 'CSF_OPTIONS_DEBUG' ) && CSF_OPTIONS_DEBUG ) ) {

      $value = $this->element_value();

      $out .= "<pre>";
      $out .= "<strong>". __( 'CONFIG', 'csf-framework' ) .":</strong>";
      $out .= "\n";
      ob_start();
      var_export( $this->field );
      $out .= htmlspecialchars( ob_get_clean() );
      $out .= "\n\n";
      $out .= "<strong>". __( 'USAGE', 'csf-framework' ) .":</strong>";
      $out .= "\n";
      $out .= ( isset( $this->field['id'] ) ) ? "csf_get_option( '". $this->field['id'] ."' );" : '';

      if( ! empty( $value ) ) {
        $out .= "\n\n";
        $out .= "<strong>". __( 'VALUE', 'csf-framework' ) .":</strong>";
        $out .= "\n";
        ob_start();
        var_export( $value );
        $out .= htmlspecialchars( ob_get_clean() );
      }

      $out .= "</pre>";

    }

    if( ( isset( $this->field['debug_light'] ) && $this->field['debug_light'] === true ) || ( defined( 'CSF_OPTIONS_DEBUG_LIGHT' ) && CSF_OPTIONS_DEBUG_LIGHT ) ) {

      $out .= "<pre>";
      $out .= "<strong>". __( 'USAGE', 'csf-framework' ) .":</strong>";
      $out .= "\n";
      $out .= ( isset( $this->field['id'] ) ) ? "csf_get_option( '". $this->field['id'] ."' );" : '';
      $out .= "\n";
      $out .= "<strong>". __( 'ID', 'csf-framework' ) .":</strong>";
      $out .= "\n";
      $out .= ( isset( $this->field['id'] ) ) ? $this->field['id'] : '';
      $out .= "</pre>";

    }

    return $out;

  }

  public function element_get_error_________() {

    global $csf_errors;

    $out = '';

    if( ! empty( $csf_errors ) ) {
      foreach ( $csf_errors as $key => $value ) {
        if( isset( $this->field['id'] ) && $value['code'] == $this->field['id'] ) {
          $out .= '<p class="csf-text-warning">'. $value['message'] .'</p>';
        }
      }
    }

    return $out;

  }

  public function element_get_error() {
		$csf_errors = csf_get_errors();
		$out         = '';
		if ( ! empty( $csf_errors ) ) {
			foreach ( $csf_errors as $key => $value ) {
				$fid = isset( $this->field['error_id'] ) ? $this->field['error_id'] : $this->field['id'];
				if ( isset( $this->field ['id'] ) && $fid === $value ['code'] ) {
					$out .= '<p class="csf-text-warning">' . $value ['message'] . '</p>';
				}
			}
		}
		return $out;
	}

  public function element_help() {
		$defaults = array(
			'icon'     	=> 'cli cli-help-circle',
			'type'			=> 'text',
			'content'  	=> '',
			'position'	=> 'bottom',
		);
		$help     = array();
		if ( isset( $this->field['help'] ) ) {
			if ( ! is_array( $this->field['help'] ) ) {
				$this->field['help'] = array( 'content' => $this->field['help'] );
			}
			$help = wp_parse_args( $this->field['help'], $defaults );
		}

		$html = false;

		// Image Tooltip
		if ($help['type'] == 'image'){
			$html 		= true;
			$help['content'] 	= "<img src='".$help['content']."' />";
		}

		return ( ! empty( $help['content'] ) ) ? '<span class="csf-help" data-html="'.$html.'" data-placement="' . $help['position'] . '" data-title="' . $help['content'] . '"><span class="' . $help['icon'] . '"></span></span>' : '';
	}

  public function element_after_multilang() {

    $out = '';

    if ( is_array( $this->multilang ) ) {

      $out .= '<fieldset class="hidden">';

      foreach ( $this->multilang['languages'] as $key => $val ) {

        // ignore current language for hidden element
        if( $key != $this->multilang['current'] ) {

          // set default value
          if( isset( $this->org_value[$key] ) ) {
            $value = $this->org_value[$key];
          } else if ( ! isset( $this->org_value[$key] ) && ( $key == $this->multilang['default'] ) ) {
            $value = $this->org_value;
          } else {
            $value = '';
          }

          $cache_field = $this->field;

          unset( $cache_field['multilang'] );
          $cache_field['name'] = $this->element_name( '['. $key .']', true );

          $class = 'CSFramework_Option_' . $this->field['type'];
          $element = new $class( $cache_field, $value, $this->unique );

          ob_start();
          $element->output();
          $out .= ob_get_clean();

        }
      }

      $out .= '<input type="hidden" name="'. $this->element_name( '[multilang]', true ) .'" value="true" />';
      $out .= '</fieldset>';
      $out .= '<p class="csf-text-desc">'. sprintf( __( 'You are editing language: ( <strong>%s</strong> )', 'csf-framework' ), $this->multilang['current'] ) .'</p>';

    }

    return $out;
  }

  public function element_data( $type = '' ) {

    $options = array();
    $query_args = ( isset( $this->field['query_args'] ) ) ? $this->field['query_args'] : array();
    $settings   = ( isset( $this->field['settings'] ) ) ? $this->field['settings'] : null;

    switch( $type ) {

      case 'pages':
      case 'page':

        $pages = get_pages( $query_args );

        if ( ! is_wp_error( $pages ) && ! empty( $pages ) ) {
          foreach ( $pages as $page ) {
            $options[$page->ID] = $page->post_title;
          }
        }

      break;

      case 'posts':
      case 'post':

        $posts = get_posts( $query_args );

        if ( ! is_wp_error( $posts ) && ! empty( $posts ) ) {
          foreach ( $posts as $post ) {
            $options[$post->ID] = $post->post_title;
          }
        }

      break;

      case 'categories':
      case 'category':

        $categories = get_categories( $query_args );

        if ( ! is_wp_error( $categories ) && ! empty( $categories ) && ! isset( $categories['errors'] ) ) {
          foreach ( $categories as $category ) {
            $options[$category->term_id] = $category->name;
          }
        }

      break;

      case 'tags':
      case 'tag':

        $taxonomies = ( isset( $query_args['taxonomies'] ) ) ? $query_args['taxonomies'] : 'post_tag';
        $tags = get_terms( $taxonomies, $query_args );

        if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
          foreach ( $tags as $tag ) {
            $options[$tag->term_id] = $tag->name;
          }
        }

      break;

      case 'menus':
      case 'menu':

        $menus = wp_get_nav_menus( $query_args );

        if ( ! is_wp_error( $menus ) && ! empty( $menus ) ) {
          foreach ( $menus as $menu ) {
            $options[$menu->term_id] = $menu->name;
          }
        }

      break;

      case 'post_types':
      case 'post_type':
        $settings = ($settings) ? $settings : array('show_in_nav_menus' => true);

        $post_types = get_post_types($settings,'objects');

        if ( ! is_wp_error( $post_types ) && ! empty( $post_types ) ) {
          foreach ( $post_types as $post_type ) {
            $options[$post_type->name] = ucfirst($post_type->label);
          }
        }

      break;

      case 'custom':
      case 'callback':

        if( is_callable( $query_args['function'] ) ) {
          $options = call_user_func( $query_args['function'], $query_args['args'] );
        }

      break;

    }

    return $options;
  }

  public function checked( $helper = '', $current = '', $type = 'checked', $echo = false ) {

    if ( is_array( $helper ) && in_array( $current, $helper ) ) {
      $result = ' '. $type .'="'. $type .'"';
    } else if ( $helper == $current ) {
      $result = ' '. $type .'="'. $type .'"';
    } else {
      $result = '';
    }

    if ( $echo ) {
      echo $result;
    }

    return $result;

  }

  public function element_multilang() {
    return ( isset( $this->field['multilang'] ) ) ? csf_language_defaults() : false;
  }







  protected function get_defaults() {
		return wp_parse_args( $this->field_defaults(), array(
			'id'          => '',
			'title'       => null,
			'type'        => null,
			'desc'        => null,
			'default'     => false,
			'help'        => false,
			'class'       => '',
			'wrap_class'  => '',
			'dependency'  => false,
			'before'      => null,
			'after'       => null,
			'attributes'  => array(),
      'only_field'  => false,
      'settings'    => array(),
      'label_type'  => 'left',
		) );
  }
  
  protected function field_defaults() {
		return array();
  }


  /**
	 * Converts Array into HTML Attribute String
	 *
	 * @param $attributes
	 *
	 * @return string
	 */
	public function array_to_html_attrs( $attributes ) {
		$atts = '';
		if ( ! empty( $attributes ) ) {
			foreach ( $attributes as $key => $value ) {
				if ( 'only-key' === $value ) {
					$atts .= ' ' . esc_attr( $key );
				} else {
					$atts .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
				}
			}
		}
		return $atts;
	}

  /**
	 * outputs JS settings HTML
	 *
	 * @return null|string
	 */
	public function element_js_settings() {
		return $this->js_settings;
  }
  
  	/**
	 * @param       $field_id
	 * @param array $default
	 *
	 * @return array|string
	 */
	public function _unarray_values( $field_id, $default = array() ) {
		if ( csf_is_unarray_field( $this->field['type'] ) ) {
			if ( true === $this->field['un_array'] ) {
				if ( isset( $this->value[ $field_id ] ) ) {
					return $this->value[ $field_id ];
				} else {
					return $default;
				}
			} else {
				return ( isset( $this->value[ $field_id ] ) ) ? $this->value[ $field_id ] : ( isset( $default[ $field_id ] ) ? $default[ $field_id ] : false );
			}
		}
		return ( empty( $this->value ) ) ? $default : $this->value;
  }
  

  /**
	 * @param $option
	 * @param $key
   * 
   * Usado en Checkbox y Radio
	 *
	 * @return array
	 */
	public function element_handle_option( $option, $key ) {
		if ( ! is_array( $option ) ) {
			$option = array(
				'label' => $option,
				'key'   => $key,
			);
		}

		$defaults = array(
			'label'      => '',
			'key'        => '',
			'attributes' => array(),
			'disabled'   => '',
			'icon'       => '',
		);

		$option = wp_parse_args( $option, $defaults );

		if ( true === $option['disabled'] ) {
			$option['attributes']['disabled'] = 'disabled';
		}

		if ( '' === $option['key'] ) {
			$option['key'] = $key;
		}

		return array(
			'id'         => $option['key'],
			'value'      => $option['label'],
			'attributes' => $option['attributes'],
			'icon'       => $option['icon'],
		);
  }
  


  /**
	 * Checks For Select Class And Returns IT.
	 *
	 * @return string
	 */
	public function select_style() {
		if ( ( isset( $this->field['select2'] ) && true === $this->field['select2'] ) || false !== strpos( $this->field['class'], 'select2' ) ) {
			return ( is_rtl() ) ? ' select2 select2-rtl' : 'select2';
		} elseif ( ( isset( $this->field['chosen'] ) && true === $this->field['chosen'] ) || false !== strpos( $this->field['class'], 'chosen' ) ) {
			return ( is_rtl() ) ? ' chosen chosen-rtl' : 'chosen';
		} elseif ( ( isset( $this->field['selectize'] ) && true === $this->field['selectize'] ) || false !== strpos( $this->field['class'], 'selectize' ) ) {
			return 'selectize';
		}
	}





  /**
	 * @param array  $field
	 * @param string $value
	 * @param string $unique
	 *
	 * @return string
	 */
	public function add_field( $field = array(), $value = '', $unique = '' ) {
		$field['uid'] = $this->uid;
		return csf_add_element( $field, $value, $unique );
	}

	public function final_output() {
		if ( 'hidden' === $this->element_type() ) {
			echo $this->output();
		} else {
			if ( isset( $this->field['only_field'] ) && true === $this->field['only_field'] ) {
				echo $this->output();
			} else {
				echo $this->element_wrapper();
				echo $this->output();
				echo $this->element_wrapper( false );
			}
		}
  }
  
  	/**
	 * @return mixed
	 */
	abstract public function output();

	/**
	 * @param bool $is_start
	 */
	public function element_wrapper( $is_start = true ) {
		if ( true === $is_start ) {
			$this->row_after = '';
			$sub             = ( isset( $this->field['sub'] ) ) ? 'sub-' : '';
			$languages       = csf_language_defaults();
			$raw_type 				= ($this->element_type() !== $this->element_raw_type()) ? ' csf-field-' . $this->element_raw_type() : null;
			$wrap_class      = 'csf-element csf-element-' . $this->element_type() . ' csf-field-' . $this->element_type() . $raw_type . ' ';

			$wrap_class .= ( ! empty( $this->field['wrap_class'] ) ) ? ' ' . $this->field['wrap_class'] : '';
			$wrap_class .= ( ! empty( $this->field['title'] ) ) ? ' csf-element-' . sanitize_title( $this->field ['title'] ) : ' csf-field-no-title ';
			$wrap_class .= ( isset( $this->field ['pseudo'] ) ) ? ' csf-pseudo-field' : '';

			$is_hidden = ( isset( $this->field ['show_only_language'] ) && ( $this->field ['show_only_language'] != $languages ['current'] ) ) ? ' hidden ' : '';

			$wrap_attr = ( isset( $this->field['wrap_attributes'] ) && is_array( $this->field['wrap_attributes'] ) ) ? $this->field['wrap_attributes'] : array();
			if ( is_array( $this->field['dependency'] ) && false !== $this->field['dependency'] ) {
				$is_hidden                                  = ' hidden';
				$wrap_attr[ 'data-' . $sub . 'controller' ] = $this->field ['dependency'] [0];
				$wrap_attr[ 'data-' . $sub . 'condition' ]  = $this->field ['dependency'] [1];
				$wrap_attr[ 'data-' . $sub . 'value' ]      = $this->field ['dependency'] [2];
			}
			$wrap_attr = $this->array_to_html_attrs( $wrap_attr );

			if ( isset( $this->field['columns'] ) ) {
				$wrap_class .= ' csf-column csf-column-' . $this->field['columns'] . ' ';

				if ( 0 == self::$total_cols ) {
					$wrap_class .= ' csf-column-first ';
					echo '<div class="csf-element csf-row">';
				}

				self::$total_cols += $this->field['columns'];

				if ( 12 == self::$total_cols ) {
					$wrap_class .= ' csf-column-last ';

					$this->row_after  = '</div>';
					self::$total_cols = 0;
				}
      }
      if (isset($this->field['label_type']) && ($this->field['label_type'] == 'top')){
        $label_type = 'top';
        $wrap_class .= " csf-element-label--{$label_type}";
      }
			$wrap_class .= ' ' . $is_hidden;
			echo '<div class="' . $wrap_class . '" ' . $wrap_attr . ' >';
			$this->element_title();
			echo $this->element_title_before();
		} else {
			echo $this->element_title_after();
			echo '<div class="clear"></div>';
			echo '</div>';
			echo $this->row_after;
		}
  }
  
  public function element_title() {
		if ( true === isset( $this->field ['title'] ) ) {
			if ( ! empty( $this->field ['title'] ) ) {
				echo '<div class="csf-title"><h4>' . $this->field ['title'] . '</h4>' . $this->element_subtitle() . ' ' . $this->element_help() . '</div>';
			}
		}
  }

  /**
	 * @return string
	 */
	public function element_title_before() {
		return ( isset( $this->field ['title'] ) && ! empty( $this->field ['title'] ) ) ? '<div class="csf-fieldset">' : '';
	}

	/**
	 * @return string
	 */
	public function element_title_after() {
		return ( isset( $this->field ['title'] ) && ! empty( $this->field ['title'] ) ) ? '</div>' : '';
  }
  
  /**
	 * @return string
	 */
	public function element_subtitle() {
		return ( isset( $this->field['subtitle'] ) ) ? '<div class="csf-field-subtitle">' . $this->field['subtitle'] . '</div>' : '';
	}

	public function element_text_limit() {
		$return      = '';
		$is_in_array = in_array( $this->field['type'], array( 'text', 'textarea' ) );
		if ( true === $is_in_array && ( isset( $this->field['limit'] ) && $this->field['limit'] > 0 ) ) {
			if ( $this->field['limit'] > 0 ) {
				$type = isset( $this->field['limit_type'] ) ? $this->field['limit_type'] : 'character';
				$text = 'word' === $type ? __( 'Word Count', 'text-limiter' ) : __( 'Character Count', 'text-limiter' );
				return '<div class="text-limiter" data-limit-type="' . esc_attr( $type ) . '"> <span>' . esc_html( $text ) . ': <span class="counter">0</span>/<span class="maximum">' . esc_html( $this->field['limit'] ) . '</span></span></div>';
			}
		}
		return $return;
	}

	public function element_desc_after() {
		return ( isset( $this->field['desc_field'] ) ) ? '<p class="csf-text-desc">' . $this->field ['desc_field'] . '</p>' : '';
	}
	
	public function element_info(){
		// '<p class="csf-text-muted">'. $this->field['info'] .'</p>'
		return ( isset( $this->field['info'] ) ) ? '<div class="csf-field-info">'. $this->field['info'] .'</div>' : '';
	}




}

// load all of fields
// csf_load_option_fields();
