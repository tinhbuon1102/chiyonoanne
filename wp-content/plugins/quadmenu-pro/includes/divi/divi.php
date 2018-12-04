<?php
if (!defined('ABSPATH')) {
  die('-1');
}

if (!class_exists('QuadMenu_PRO_Divi')) {

  define('QUADMENU_DIVI_URL', plugin_dir_url(__FILE__));

  class QuadMenu_PRO_Divi {

    function __construct() {

      add_action('init', array($this, 'hooks'), -30);

      add_action('init', array($this, 'options'), -25);

      add_action('init', array($this, 'customizer'), -5);

      add_action('init', array($this, 'primary_menu'));
    }

    function is_divi() {

      global $quadmenu;

      if (!function_exists('et_divi_fonts_url'))
        return false;

      if (!function_exists('et_get_option'))
        return false;

      if (isset($quadmenu['primary-menu_theme']) && $quadmenu['primary-menu_theme'] != 'divi_primary_menu')
        return false;

      return true;
    }

    function hooks() {

      if (!$this->is_divi())
        return;

      add_action('wp_enqueue_scripts', array($this, 'enqueue'));

      add_filter('quadmenu_compiler_files', array($this, 'files'));

      add_filter('quadmenu_redux_args', array($this, 'args'));

      add_action('admin_menu', array($this, 'remove'), 999);
    }

    function enqueue() {

      if (is_file(QUADMENU_PATH_CSS . 'quadmenu-divi.css')) {
        wp_enqueue_style('quadmenu-divi', QUADMENU_URL_CSS . 'quadmenu-divi.css', array(), filemtime(QUADMENU_PATH_CSS . 'quadmenu-divi.css'), 'all');
      }
    }

    function files($files) {

      $files[] = QUADMENU_DIVI_URL . 'assets/quadmenu-divi.less';

      return $files;
    }

    function args($args) {

      $args['customizer'] = false;
      $args['disable_google_fonts_link'] = true;

      return $args;
    }

    function remove() {
      remove_submenu_page('quadmenu_welcome', 'customize.php?quadmenu_customize');
    }

    function options() {

      if (!$this->is_divi())
        return;

      require_once plugin_dir_path(__FILE__) . 'includes/options.php';
    }

    function customizer() {

      if (!$this->is_divi())
        return;

      if (!is_customize_preview())
        return;

      require_once plugin_dir_path(__FILE__) . 'includes/customizer.php';
    }

    function primary_menu() {

      if (!self::is_divi())
        return;

      if (!function_exists('is_quadmenu_location'))
        return;

      if (!is_quadmenu_location('primary-menu'))
        return;

      remove_action('et_header_top', 'et_add_mobile_navigation');

      add_action('et_header_top', array($this, 'primary_menu_integration'));
    }

    function primary_menu_integration() {

      if (is_customize_preview() || ( 'slide' !== et_get_option('header_style', 'left') && 'fullscreen' !== et_get_option('header_style', 'left') )) {
        ?>
        <div id="et_mobile_nav_menu">
          <div class="mobile_nav closed">
            <span class="select_page"><?php esc_html_e('Select Page', 'Divi'); ?></span>
            <span class="mobile_menu_bar mobile_menu_bar_toggle"></span>
            <div class="et_mobile_menu">
              <?php wp_nav_menu(array('theme_location' => 'primary-menu', 'layout' => 'inherit')); ?>
            </div>
          </div>
        </div>

        <?php
      }
    }

  }

  new QuadMenu_PRO_Divi();
}
