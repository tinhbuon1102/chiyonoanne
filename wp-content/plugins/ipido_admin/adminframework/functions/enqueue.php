<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Framework admin enqueue style and scripts
*
* @since 1.0.0
* @version 1.0.0
*
*/

if ( ! class_exists( 'CSFramework_Assets' ) ) {
  /**
  * Class CSFramework_Assets
  */
  final class CSFramework_Assets {
    /**
    * _instance
    *
    * @var null
    */
    private static $_instance = null;
    
    /**
    * scripts
    *
    * @var array
    */
    public $scripts = array();
    
    /**
    * styles
    *
    * @var array
    */
    public $styles = array();
    
    /**
    * CSFramework_Assets constructor.
    */
    public function __construct() {
      $this->init_array();
      add_action( 'admin_enqueue_scripts', array( &$this, 'register_assets' ) );
    }
    
    /**
    * Stores All default CSF Assets Into A Array
    *
    * @uses $this->styles
    * @uses $this->scripts
    */
    public function init_array() {
      // Define Styles
      $this->styles = array(
        'csf-framework' => array(
          self::is_debug( CSF_URI . '/assets/css/csf-framework.css', 'css' ),
          array(),
          CSF_VERSION,
        ),
        'csf-font-awesome'  => array(
          self::is_debug( CSF_URI . '/assets/css/font-awesome.css', 'css' ),
          array(),
          '4.2.0',
        ),
      );

      // Add Icons
      $jsons = apply_filters('csf_add_icons', glob( CSF_DIR . '/assets/icons/*.json' ));
      if( ! empty( $jsons ) ) {
        foreach ( $jsons as $path ) {
          // $object = csf_get_icon_fonts( 'assets/icons/'. basename( $path ) );
          $_path      = pathinfo($path);
          $_name      = $_path['filename'];
          $filepath   = "/assets/icons/{$_name}/{$_name}.css";
          $_filepath  = CSF_DIR . $filepath;
          $_fileuri   = CSF_URI . $filepath;
                    
          if (file_exists($_filepath)){
            $this->styles['csf-'.$_name] = array(
              self::is_debug($_fileuri, 'css' ),
              array(),
              CSF_VERSION,
            );
          }
        }
      }
      
      // Define Scripts
      $this->scripts = array(
        'csf-plugins'   => array(
          self::is_debug( CSF_URI . '/assets/js/csf-plugins.js', 'js' ),
          null,
          CSF_VERSION,
          true,
        ),
        'csf-framework'   => array(
          self::is_debug( CSF_URI . '/assets/js/csf-framework.js', 'js' ),
          null,
          CSF_VERSION,
          true,
        ),
        'csf-vendor-ace'  => array(
          self::is_debug( CSF_URI . '/assets/js/vendor/ace/ace.js', 'js' ),
          null,
          '1.0.0',
          true,
        ),
        'csf-vendor-ace-mode' => array(
          self::is_debug( CSF_URI . '/assets/js/vendor/ace/mode-css.js', 'js' ),
          array('csf-vendor-ace'),
          '1.0.0',
          true,
        ),
        'csf-vendor-ace-language_tools'   => array(
          self::is_debug( CSF_URI . '/assets/js/vendor/ace/ext-language_tools.js', 'js' ),
          array('csf-vendor-ace'),
          '1.0.0',
          true,
        ),
        'jquery-deserialize'  => array(
          self::is_debug( CSF_URI . '/assets/js/vendor/jquery.deserialize.js', 'js' ),
          array( 'csf-plugins' ),
          '1.0.0',
          true,
        ),
      );
    }
    
    /**
    * Creates A Instance for CSFramework_Assets.
    *
    * @return null|\CSFramework_Assets
    * @static
    */
    public static function instance() {
      if ( null === self::$_instance ) {
        self::$_instance = new self;
      }
      return self::$_instance;
    }
    
    /**
    * Loads All Default Styles & Assets.
    */
    public function render_framework_style_scripts() {
      wp_enqueue_media();

      /**
       * Enqueue Styles
       */
      wp_enqueue_style( 'editor-buttons' );
      wp_enqueue_style( 'wp-color-picker' );
      wp_enqueue_style( 'wp-jquery-ui-dialog' );
      
      // Enqueue Dynamic Styles
      foreach ($this->styles as $style => $value){
        wp_enqueue_style($style);
      }
      

      /**
       * Enqueue Scripts
       */
      wp_enqueue_script( 'wp-color-picker' );
      wp_enqueue_script( 'jquery-ui-dialog' );
      wp_enqueue_script( 'jquery-ui-sortable' );
      wp_enqueue_script( 'jquery-ui-accordion' );
      wp_enqueue_script( 'wplink' );

      wp_enqueue_script( 'csf-vendor-ace' );
      wp_enqueue_script( 'csf-vendor-ace-mode' );
      wp_enqueue_script( 'csf-vendor-ace-language_tools' );
      wp_enqueue_script( 'jquery-deserialize' );
      wp_enqueue_script( 'csf-plugins' );
      wp_enqueue_script( 'csf-framework' );

      /**
       * Localize Framework
       */
      wp_localize_script( 'csf-framework', 'csf_framework', 
        array( 
          'ajax_url' 	=> admin_url('admin-ajax.php'),
          'nonce' 	  => wp_create_nonce('csf-framework-nonce'),
        )
      );
    }
    
    /**
    * Registers Assets With WordPress
    */
    public function register_assets() {
      foreach ( $this->styles as $id => $file ) {
        wp_register_style( $id, $file[0], $file[1], $file[2], 'all' );
      }
      
      foreach ( $this->scripts as $iid => $ffile ) {
        wp_register_script( $iid, $ffile[0], $ffile[1], $ffile[2], true );
      }
    }
    
    /**
    * Check if WP_DEBUG & SCRIPT_DEBUG Is enabled.
    *
    * @param string $file_name
    * @param string $ext
    *
    * @return mixed|string
    * @static
    */
    private static function is_debug( $file_name = '', $ext = 'css' ) {
      $search  = '.' . $ext;
      $replace = '.' . $ext;
      if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) {
        return $file_name;
      }
      return str_replace( $search, $replace, $file_name );
    }
  }
}





if ( ! function_exists( 'csf_assets' ) ) {
  /**
  * @return null|\CSFramework_Assets
  */
  function csf_assets() {
    return CSFramework_Assets::instance();
  }
}

if ( ! function_exists( 'csf_load_customizer_assets' ) ) {
  /**
  * Loads CSF Assets on customizer page.
  */
  function csf_load_customizer_assets() {
    csf_assets()->render_framework_style_scripts();
  }
  
  
  if ( has_action( 'csf_widgets' ) ) {
    add_action( 'admin_print_styles-widgets.php', 'csf_load_customizer_assets' );
  }
}

return csf_assets();