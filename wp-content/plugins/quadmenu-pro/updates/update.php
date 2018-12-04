<?php

if (!defined('ABSPATH')) {
  die('-1');
}

if (class_exists('QuadMenu_License')) {

  class QuadMenu_Update extends QuadMenu_License {

    private $plugin_file;

    public function __construct($plugin_file = '') {

      $this->plugin_file = $plugin_file;

      add_filter('plugins_api', array($this, 'plugin_info'), 10, 3);
      add_filter('pre_set_site_transient_update_plugins', array($this, 'plugin_update'));
      add_action('in_plugin_update_message-quadmenu-pro/quadmenu-pro.php', array($this, 'plugin_notification'), 10, 2);
    }

    function plugin_notification($plugin_data, $response) {
      if (empty($response->package)) {
        echo self::get_update_error_message($plugin_data);
      }
    }

    private function is_api_error($response) {
      if ($response === false) {
        return true;
      }

      if (!is_object($response)) {
        return true;
      }

      if (isset($response->error)) {
        return true;
      }

      return false;
    }

    private function get_local_version() {

      $plugin_data = get_plugin_data($this->plugin_file, false);

      return $plugin_data['Version'];
    }

    public function plugin_info($false, $action, $args) {

      if ('plugin_information' != $action) {
        return $false;
      }
      if (!isset($args->slug) || $args->slug != plugin_basename($this->plugin_file)) {
        return $false;
      }

      $response = $this->license_info();

      if (!$this->is_api_error($response)) {

        $info = new stdClass();
        $info->name = QUADMENU_NAME;
        $info->version = $response->new_version;
        $info->slug = $args->slug;
        $info->plugin_name = $response->name;
        $info->author = $response->author;
        $info->homepage = $response->description_url;
        $info->requires = $response->requires;
        $info->tested = $response->tested;
        $info->last_updated = $response->last_updated;
        $info->download_link = $response->package;
        $info->sections = (array) $response->sections;
        $info->banners = (array) $response->banners;

        return $info;
      }

      return $false;
    }

    function plugin_update($transient) {
      global $pagenow;

      if ('plugins.php' == $pagenow && is_multisite()) {
        return $transient;
      }
      if (!is_object($transient)) {
        $transient = new stdClass();
      }
      if (!isset($transient->checked)) {
        $transient->checked = array();
      }

      $response = $this->license_info();

      if (!$this->is_api_error($response)) {

        $transient->last_checked = time();
        $transient->checked[plugin_basename($this->plugin_file)] = $this->get_local_version();

        $plugin = 'quadmenu-pro/quadmenu-pro.php'; //self::get_plugin_file($this->settings['slug']);

        if (version_compare($response->new_version, $this->get_local_version(), '>')) {

          $transient->response[$plugin] = new stdClass();
          $transient->response[$plugin]->slug = plugin_basename($this->plugin_file);
          $transient->response[$plugin]->new_version = $response->new_version;
          $transient->response[$plugin]->url = $response->homepage;
          $transient->response[$plugin]->package = $response->package;
          $transient->response[$plugin]->tested = $response->tested;
          $transient->response[$plugin]->icons = array(
              '1x' => 'https://ps.w.org/quadmenu/assets/icon-128x128.jpg',
              '2x' => 'https://ps.w.org/quadmenu/assets/icon-256x256.jpg',
              'default' => 'https://ps.w.org/quadmenu/assets/icon-128x128.jpg',
          );
          if (empty($response->package)) {
            $transient->response[$plugin]->upgrade_notice = self::get_update_error_message();
          }
        }
      }

      return $transient;
    }

    static private function get_update_error_message($plugin_data = null) {

      $license = get_option('quadmenu_license', false);

      $message = '';

      if (!$plugin_data) {
        // update-core.php
        if (!isset($license->instance)) {
          $message = sprintf(__('Please visit %s to activate the license or %s on our website.', 'quadmenu'), sprintf('<a href="%s" target="_blank">%s</a>', admin_url('admin.php?page=quadmenu_license'), __('settings', 'quadmenu')), sprintf('<a href="%s" target="_blank">%s</a>', QUADMENU_DEMO, __('purchase', 'quadmenu')));
        }
      } else {
        // plugins.php
        if (!isset($license->instance)) {
          $message = sprintf('</p></div><span class="notice notice-error notice-alt" style="display:block; padding: 10px;"><b>%s</b> %s</span>', __('Activate your license.', 'quadmenu'), sprintf(__('Please visit %s to activate the license or %s on our website.', 'quadmenu'), sprintf('<a href="%s" target="_blank">%s</a>', admin_url('admin.php?page=quadmenu_license'), __('settings', 'quadmenu')), sprintf('<a href="%s" target="_blank">%s</a>', QUADMENU_DEMO, __('purchase', 'quadmenu'))));
        }
      }

      return $message;
    }

  }

}
