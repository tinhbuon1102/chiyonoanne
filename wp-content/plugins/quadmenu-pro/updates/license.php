<?php
if (!defined('ABSPATH')) {
  die('-1');
}

if (class_exists('QuadMenu_Panel')) {

  class QuadMenu_License extends QuadMenu_Panel {

    static $license;
    static $quadmenu = array(
        'url' => 'https://quadmenu.com/',
        'wc-api' => 'software-api',
        'name' => 'quadmenu-pro.zip',
        'product_id' => 14326,
    );

    function __construct() {
      add_action('admin_menu', array($this, 'panel'), 6);
      add_action('admin_init', array($this, 'license'));
      add_action('admin_init', array($this, 'updater'));
    }

    function panel() {
      add_submenu_page('quadmenu_welcome', esc_html__('License', 'quadmenu'), esc_html__('License', 'quadmenu'), 'manage_options', 'quadmenu_license', array($this, 'license_page'));
    }

    function header() {
      require_once QUADMENU_PATH . 'includes/panel/header.php';
    }

    function license() {

      $run = false;

      self::$license = (object) array(
                  'license_key' => null,
                  'email' => null,
                  'activated' => null,
                  'instance' => null,
                  'market' => null,
                  'site_count' => null,
                  'expires' => null,
                  'supported_until' => null,
                  'license_limit' => null,
                  'message' => null,
      );

      if (isset($_POST['quadmenu_update_licensing'])) {

        if (isset($_POST['quadmenu_license_key']) && $run = true) {
          self::$license->license_key = trim($_POST['quadmenu_license_key']);
        }

        if (isset($_POST['quadmenu_license_email']) && $run = true) {
          self::$license->email = trim($_POST['quadmenu_license_email']);
        }

        if ($old = get_option('quadmenu_license', false)) {
          self::$license->instance = $old->instance;
        }

        if ($run)
          $this->activate_license();
      }
    }

    function activate_license() {

      if (isset(self::$license->license_key)) {

        $data = $this->verify_license(self::$license->license_key, self::$license->email, self::$license->instance);

        self::$license = (object) array_merge((array) self::$license, (array) $data);

        update_option('quadmenu_license', self::$license);
      }

      wp_clean_plugins_cache();
    }

    function license_page() {

      $this->header();

      if (self::$license = get_option('quadmenu_license', false)) {
        // Key
        /* -----------------------------------------------------------------
        $this->add('License', array(
            'check_name' => esc_html__('Key', 'quadmenu'),
            'tooltip' => '',
            'value' => self::$license->license_key,
            'status' => 'info'
        ));

        $this->add('License', array(
            'check_name' => esc_html__('Email', 'quadmenu'),
            'tooltip' => '',
            'value' => self::$license->email,
            'status' => 'info'
        ));*/
        // Type
        // -----------------------------------------------------------------

        if (!empty(self::$license->activated) && self::$license->activated) {
          // Market
          // -------------------------------------------------------------
          if (!empty(self::$license->market)) {
            $this->add('License', array(
                'check_name' => esc_html__('Market', 'quadmenu'),
                'tooltip' => '',
                'value' => self::$license->market,
                'status' => 'info'
            ));
          }
          // Sites
          // -------------------------------------------------------------
          if (!empty(self::$license->license_limit)) {
            if (self::$license->license_limit > 0) {
              $this->add('License', array(
                  'check_name' => esc_html__('Sites', 'quadmenu'),
                  'tooltip' => '',
                  'value' => sprintf('<span class="quadmenu-status-small-text">You can install this license in %1$s sites</span>', esc_attr(self::$license->license_limit)),
                  'status' => 'yellow'
              ));
            } else {
              $this->add('License', array(
                  'check_name' => esc_html__('Sites', 'quadmenu'),
                  'tooltip' => '',
                  'value' => '<span class="quadmenu-status-small-text">' . esc_html__('Unlimited sites', 'quadmenu') . '</span>',
                  'status' => 'green'
              ));
            }
          }
          // Usage
          // -------------------------------------------------------------
          if (!empty(self::$license->site_count)) {
            $this->add('License', array(
                'check_name' => esc_html__('Usage', 'quadmenu'),
                'tooltip' => '',
                'value' => sprintf('<span class="quadmenu-status-small-text">This license have been installed in %1$s sites</span>', esc_attr(self::$license->site_count)),
                'status' => (self::$license->license_limit > 0 && self::$license->site_count > self::$license->license_limit) ? 'red' : 'green',
            ));
          }
          // Expires
          // -------------------------------------------------------------
          if (!empty(self::$license->expires)) {
            if (self::$license->expires === 'lifetime') {
              $this->add('License', array(
                  'check_name' => esc_html__('Expires', 'quadmenu'),
                  'tooltip' => '',
                  'value' => esc_html(ucwords(self::$license->expires)),
                  'status' => 'green',
              ));
            } elseif (self::$license->expires > 0) {

              $days = ceil(abs(strtotime(self::$license->expires) - time()) / 86400);

              $this->add('License', array(
                  'check_name' => esc_html__('Status', 'quadmenu'),
                  'tooltip' => '',
                  'value' => sprintf(esc_html__('%s days left', 'quadmenu'), $days),
                  'status' => $days > 0 ? 'greed' : 'red',
              ));
            }
          }
          // Support
          // -------------------------------------------------------------
          if (!empty(self::$license->supported_until)) {

            $days = ceil(abs(strtotime(self::$license->supported_until) - time()) / 86400);

            $this->add('License', array(
                'check_name' => 'Support',
                'tooltip' => '',
                'value' => sprintf(esc_html__('%s days left', 'quadmenu'), $days),
                'status' => $days > 0 ? 'green' : 'red',
            ));
          }
          // License
          // -------------------------------------------------------------
          $this->add('License', array(
              'check_name' => 'Status',
              'tooltip' => '',
              'value' => sprintf('%s - (%s)', esc_html__('Active', 'quadmenu'), esc_html(self::$license->message)),
              'status' => 'green'
          ));
        } else {
          $this->add('License', array(
              'check_name' => esc_html__('Status', 'quadmenu'),
              'tooltip' => '',
              'value' => sprintf('%s - (%s)', esc_html__('Error', 'quadmenu'), esc_html(self::$license->message)),
              'status' => 'red'
          ));
        }
      } else {
        $this->add('License', array(
            'check_name' => esc_html__('Key', 'quadmenu'),
            'tooltip' => '',
            'value' => esc_html__('Please, include your license code', 'quadmenu'),
            'status' => 'red'
        ));
      }
      ?>
      <div class="about-wrap quadmenu-admin-wrap">
        <h1><?php esc_html_e('License', 'quadmenu'); ?></h1>
        <div class="about-text">
          <?php if (empty(self::$license->instance)): ?>
            <?php printf(__('Before you can receive plugin updates, you must first authenticate your license. To do this, you need to enter your email and License Key into the <a href="%s" target="_blank">License</a> tab.', 'quadmenu'), admin_url('admin.php?page=quadmenu_license'), QUADMENU_DEMO); ?>
            <?php //printf(__('Before you can receive plugin updates, you must first authenticate your license. To do this, you need to enter your email and License Key into the <a href="%s" target="_blank">License</a> tab. To locate your License Key, <a href="%s" target="_blank">log in</a> to your QuadMenu account and navigate to the <strong>Account > Downloads</strong> page.', 'quadmenu'), admin_url('admin.php?page=quadmenu_license'), QUADMENU_DEMO); ?>
          <?php else: ?>
            <?php printf(__('Thanks for register your license from %s. If you have doubts you can request <a href="%s" target="_blank">support</a> through the ticket system.', 'quadmenu'), self::$license->market, QUADMENU_SUPPORT); ?>
          <?php endif; ?>
        </div>
        <?php $this->tables(); ?>

        <form method="POST">
          <table class="widefat quadmenu-system-table" cellspacing="0">
            <thead>
              <tr>
                <th colspan="4"><?php esc_html_e('Activate', 'quadmenu'); ?></th>
              </tr>
            </thead>
            <tbody>                        
              <tr>
                <td class="quadmenu-system-name">
                  <?php esc_html_e('Email', 'quadmenu'); ?>
                </td>
                <td>
                  <input placeholder="<?php echo esc_html_e('Enter your account email', 'quadmenu'); ?>" id="quadmenu_license_email" name="quadmenu_license_email" type="password" class="regular-text" value="<?php echo esc_html(isset(self::$license->email)) ? self::$license->email : ''; ?>" />
                </td>
              </tr>    
              <tr>
                <td class="quadmenu-system-name">
                  <?php esc_html_e('License', 'quadmenu'); ?>
                </td>
                <td>
                  <input placeholder="<?php echo esc_html_e('Enter your license key', 'quadmenu'); ?>" id="quadmenu_license_key" name="quadmenu_license_key" type="password" class="regular-text" value="<?php echo esc_html(isset(self::$license->license_key)) ? self::$license->license_key : ''; ?>" />
                </td>
              </tr>    
              <tr>
                <td colspan="2">
                  <input type="submit" name="quadmenu_update_licensing" class="button button-primary" value="<?php esc_html_e('Save License', 'quadmenu'); ?>" />
                </td>
              </tr>                       
            </tbody>
          </table>
        </form>
      </div>
      <?php
    }

    function validate_response($response) {

      if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

        if (!is_wp_error($response)) {
          $response = new WP_Error('broke', __('An error occurred, please try again', 'quadmenu'));
        }

        $response->message = $response->get_error_message();

        return $response;
      }

      $response = json_decode(wp_remote_retrieve_body($response));

      return $response;
    }

    function verify_license($key, $email, $instance = null) {

      $params = array(
          'license_key' => $key,
          'email' => $email,
          'instance' => $instance,
          'request' => 'activation',
          'platform' => home_url(),
      );

      $url = self::$quadmenu['url'] . '?' . http_build_query(wp_parse_args($params, self::$quadmenu));

      $response = wp_remote_get($url);

      $license_data = $this->validate_response($response);

      if (!is_wp_error($license_data)) {

        if (!$license_data->activated) {
          $license_data->message = $license_data->error;
        }
      }

      return $license_data;
    }

    function license_info() {

      $params = array(
          'request' => 'version',
          'platform' => home_url(),
      );

      if ($data = get_option('quadmenu_license')) {
        $params['license_key'] = $data->license_key;
        $params['email'] = $data->email;
        $params['instance'] = $data->instance;
      }

      $url = self::$quadmenu['url'] . '?' . http_build_query(wp_parse_args($params, self::$quadmenu));

      $response = wp_remote_get($url);

      $license_data = $this->validate_response($response);

      return $license_data;
    }

    function updater() {

      require_once dirname(__FILE__) . '/update.php';

      $update = new QuadMenu_Update(QUADMENU_PRO_FILE, self::$license);
    }

  }

  new QuadMenu_License();
}