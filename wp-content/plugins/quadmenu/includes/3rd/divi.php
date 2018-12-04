<?php

if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenu_Divi_Module {

  public function __construct() {
    add_action('et_builder_ready', array($this, 'module'));

    add_action('et_builder_ready', array($this, 'cache'));

    add_action('quadmenu_create_theme', array($this, 'clear_builder_cache'));

    add_action('quadmenu_delete_theme', array($this, 'clear_builder_cache'));

    add_filter('wp_nav_menu_args', array($this, 'fullwidth_menu'), 100000, 1);
  }

  function fullwidth_menu($args) {

    if (class_exists('ET_Builder_Module')) {
      if (isset($args['menu_class']) && strpos($args['menu_class'], 'fullwidth-menu') !== false) {
        $args['theme_location'] = false;
      }
    }

    return $args;
  }

  function cache() {

    if (get_transient('quadmenu_create_theme')) {

      et_update_option('et_pb_clear_templates_cache', true);

      delete_transient('quadmenu_create_theme');
    }
  }

  function module() {
    require_once 'divi/module.php';
  }

  function clear_builder_cache() {
    set_transient('quadmenu_create_theme', true, 30);
  }

}

new QuadMenu_Divi_Module();
