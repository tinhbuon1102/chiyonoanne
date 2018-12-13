<?php
/*
 * Plugin Name: QuadMenu PRO
 * Plugin URI:  https://www.quadmenu.com
 * Description: The best drag & drop WordPress Mega Menu plugin which allow you to create Tabs Menus & Carousel Menus.
 * Version:     1.7.0
 * Author:      Mega Menu
 * Author URI:  https://www.quadmenu.com
 * Copyright:   2018 QuadMenu (https://www.quadmenu.com)
 * Text Domain: quadmenu
 */

if (!defined('ABSPATH')) {
  die('-1');
}

if (!class_exists('QuadMenu_PRO')) {

  define('QUADMENU_PRO_FILE', __FILE__);

  class QuadMenu_PRO {

    protected static $instance;

    public static function init() {
      if (empty(self::$instance)) {
        self::$instance = new self();
      }
      return self::$instance;
    }

    function __construct() {

      add_action('admin_notices', array($this, 'notices'));

      if (class_exists('QuadMenu')) {

        add_action('admin_init', array($this, 'panel'));

        add_filter('admin_body_class', array($this, 'body'), 99);

        if (defined('QUADMENU_OPTIONS')) {
          add_action('redux/extensions/' . QUADMENU_OPTIONS . '/before', array($this, 'customizer'), 0);
        }

        require_once plugin_dir_path(QUADMENU_PRO_FILE) . 'updates/license.php';
        require_once plugin_dir_path(QUADMENU_PRO_FILE) . 'includes/advanced.php';
        require_once plugin_dir_path(QUADMENU_PRO_FILE) . 'includes/woocommerce.php';
        require_once plugin_dir_path(QUADMENU_PRO_FILE) . 'includes/divi/divi.php';
      }
    }

    function body($classes) {

      $screen = get_current_screen();

      $classes .= ' quadmenu-pro';

      return $classes;
    }

    function notices() {

      $screen = get_current_screen();

      if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
        return;
      }

      $plugin = 'quadmenu/quadmenu.php';

      if (is_plugin_active($plugin)) {
        return;
      }

      if (is_quadmenu_installed()) {

        if (!current_user_can('activate_plugins')) {
          return;
        }
        ?>
        <div class="error">
          <p>
            <a href="<?php echo wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1', 'activate-plugin_' . $plugin); ?>" class='button button-secondary'><?php _e('Activate QuadMenu', 'quadmenu'); ?></a>
            <?php esc_html_e('QuadMenu Pro not working because you need to activate the QuadMenu plugin.', 'quadmenu'); ?>   
          </p>
        </div>
        <?php
      } else {

        if (!current_user_can('install_plugins')) {
          return;
        }
        ?>
        <div class="error">
          <p>
            <a href="<?php echo wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=quadmenu'), 'install-plugin_quadmenu'); ?>" class='button button-secondary'><?php _e('Install QuadMenu', 'quadmenu'); ?></a>
            <?php esc_html_e('QuadMenu Pro not working because you need to install the QuadMenu plugin.', 'quadmenu'); ?>
          </p>
        </div>
        <?php
      }
    }

    function panel() {
      remove_submenu_page('quadmenu_welcome', 'quadmenu_pro');
    }

    function customizer($ReduxFramework) {

      if (!is_admin() && !is_customize_preview())
        return;

      require_once plugin_dir_path(__FILE__) . 'includes/customizer/customizer.php';

      new QuadMenu_Customizer($ReduxFramework);
    }

  }

  if (!function_exists('is_quadmenu_installed')) {

    function is_quadmenu_installed() {

      $file_path = 'quadmenu/quadmenu.php';

      $installed_plugins = get_plugins();

      return isset($installed_plugins[$file_path]);
    }

  }

  add_action('plugins_loaded', array('QuadMenu_PRO', 'init'));
}