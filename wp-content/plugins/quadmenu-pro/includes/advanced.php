<?php

if (!defined('ABSPATH')) {
  die('-1');
}

if (!class_exists('QuadMenu_Advanced')) :

  class QuadMenu_Advanced {

    function __construct() {

      add_action('admin_init', array($this, 'navmenu'), 40);
      add_action('wp_ajax_quadmenu_register_user', array($this, 'register_user'));
      add_action('wp_ajax_nopriv_quadmenu_register_user', array($this, 'register_user'));
      add_action('wp_ajax_quadmenu_login_user', array($this, 'login_user'));
      add_action('wp_ajax_nopriv_quadmenu_login_user', array($this, 'login_user'));
      add_filter('quadmenu_item_object_class', array($this, 'item_object_class'), 10, 4);
      add_filter('quadmenu_custom_nav_menu_items', array($this, 'nav_menu_items'));
      add_filter('quadmenu_nav_menu_item_fields', array($this, 'nav_menu_item_fields'), 10, 2);
      add_filter('quadmenu_remove_nav_menu_item', array($this, 'remove_nav_menu_item'));
      add_filter('quadmenu_setup_nav_menu_item', array($this, 'remove_nav_menu_login'), 10);
      add_filter('redux/options/' . QUADMENU_OPTIONS . '/sections', array($this, 'social'), 90);
      add_action('init', array($this, 'frontend'));
    }

    function frontend() {
      require_once plugin_dir_path(__FILE__) . 'frontend/QuadMenuItemArchive.class.php';
      require_once plugin_dir_path(__FILE__) . 'frontend/QuadMenuItemTaxonomy.class.php';
      require_once plugin_dir_path(__FILE__) . 'frontend/QuadMenuItemCarousel.class.php';
      require_once plugin_dir_path(__FILE__) . 'frontend/QuadMenuItemCarouselPanel.class.php';
      require_once plugin_dir_path(__FILE__) . 'frontend/QuadMenuItemTabs.class.php';
      require_once plugin_dir_path(__FILE__) . 'frontend/QuadMenuItemTab.class.php';
      require_once plugin_dir_path(__FILE__) . 'frontend/QuadMenuItemLogin.class.php';
      require_once plugin_dir_path(__FILE__) . 'frontend/QuadMenuItemSocial.class.php';
      require_once plugin_dir_path(__FILE__) . 'frontend/QuadMenuItemButton.class.php';
    }

    function remove_nav_menu_login($item) {

      if (!is_admin() && !is_user_logged_in() && isset($item->quadmenu_menu_item_parent) && $item->quadmenu_menu_item_parent == 'login') {
        $item->_invalid = true;
      }

      return $item;
    }

    function navmenu() {

      if (function_exists('is_quadmenu') && is_quadmenu()) {
        require_once plugin_dir_path(__FILE__) . 'backend/panel.php';
        require_once plugin_dir_path(__FILE__) . 'backend/carousel.php';
        require_once plugin_dir_path(__FILE__) . 'backend/tab.php';
        require_once plugin_dir_path(__FILE__) . 'backend/tabs.php';
        require_once plugin_dir_path(__FILE__) . 'backend/account.php';
      }
    }

    function nav_menu_items($items) {

      $items['social'] = array(
          'label' => esc_html__('QuadMenu Social', 'quadmenu'),
          'title' => esc_html__('Social', 'quadmenu'),
          'panels' => array(
              'general' => array(
                  'title' => esc_html__('General', 'quadmenu'),
                  'icon' => 'dashicons dashicons-admin-settings',
                  'settings' => array('float', 'hidden', 'social'),
              ),
              'icon' => array(
                  'title' => esc_html__('Icon', 'quadmenu'),
                  'icon' => 'dashicons dashicons-art',
                  'settings' => array('icon'),
              ),
          ),
          'desc' => esc_html__('Icon list of your social networks.', 'quadmenu'),
          'parent' => 'main',
          'depth' => 0,
      );
      $items['login'] = array(
          'label' => esc_html__('QuadMenu Login', 'quadmenu'),
          'title' => esc_html__('Login', 'quadmenu'),
          'panels' => array(
              'general' => array(
                  'title' => esc_html__('General', 'quadmenu'),
                  'icon' => 'dashicons dashicons-admin-settings',
                  'settings' => array('float', 'dropdown', 'hidden'),
              ),
              'login' => array(
                  'title' => esc_html__('Login', 'quadmenu'),
                  'icon' => 'dashicons dashicons-unlock',
                  'settings' => array('register', 'password', 'login'),
              ),
              'logout' => array(
                  'title' => esc_html__('Logout', 'quadmenu'),
                  'icon' => 'dashicons dashicons-lock',
                  'settings' => array('avatar', 'name', 'logout'),
              ),
          /* 'register' => array(
            'title' => esc_html__('Register', 'quadmenu'),
            'icon' => 'dashicons dashicons-admin-users',
            'settings' => array('register'),
            ), */
          ),
          'desc' => esc_html__('A login and register widget for your users.', 'quadmenu'),
          'parent' => 'main',
          'depth' => 0,
      );
      $items['tabs'] = array(
          'label' => esc_html__('QuadMenu Tabs', 'quadmenu'),
          'title' => esc_html__('Tabs', 'quadmenu'),
          'panels' => array(
              'general' => array(
                  'title' => esc_html__('General', 'quadmenu'),
                  'icon' => 'dashicons dashicons-admin-settings',
                  'settings' => array('subtitle', 'badge', 'float', 'dropdown', 'hidden'),
              ),
              'icon' => array(
                  'title' => esc_html__('Icon', 'quadmenu'),
                  'icon' => 'dashicons dashicons-art',
                  'settings' => array('icon'),
              ),
              'background' => array(
                  'title' => esc_html__('Background', 'quadmenu'),
                  'icon' => 'dashicons dashicons-format-image',
                  'settings' => array('background'),
              ),
              'width' => array(
                  'title' => esc_html__('Width', 'quadmenu'),
                  'icon' => 'dashicons dashicons-align-left',
                  'settings' => array('dropdown', 'stretch', 'width'),
              ),
          ),
          'desc' => esc_html__('A tab menu which can wrap any type of widget.', 'quadmenu'),
          'parent' => 'main',
          'depth' => 0,
      );
      $items['carousel'] = array(
          'label' => esc_html__('QuadMenu Carousel', 'quadmenu'),
          'title' => esc_html__('Carousel', 'quadmenu'),
          'panels' => array(
              'general' => array(
                  'title' => esc_html__('General', 'quadmenu'),
                  'icon' => 'dashicons dashicons-admin-settings',
                  'settings' => array('subtitle', 'badge', 'float', 'hidden'),
              ),
              'icon' => array(
                  'title' => esc_html__('Icon', 'quadmenu'),
                  'icon' => 'dashicons dashicons-art',
                  'settings' => array('icon'),
              ),
              'background' => array(
                  'title' => esc_html__('Background', 'quadmenu'),
                  'icon' => 'dashicons dashicons-format-image',
                  'settings' => array('background'),
              ),
              'width' => array(
                  'title' => esc_html__('Width', 'quadmenu'),
                  'icon' => 'dashicons dashicons-align-left',
                  'settings' => array('dropdown', 'stretch', 'width'),
              ),
              'carousel' => array(
                  'title' => esc_html__('Carousel', 'quadmenu'),
                  'icon' => 'dashicons dashicons-image-flip-horizontal',
                  'settings' => array('speed', 'autoplay', 'autoplay_speed', 'dots', 'pagination'),
              //'settings' => array('speed', 'autoplay', 'autoplay_speed', 'dots', 'pagination', 'controls'),
              ),
          ),
          'desc' => esc_html__('A carousel menu which can wrap any type of widget.', 'quadmenu'),
          'parent' => 'main',
          'depth' => 0,
      );
      // Subitems
      // -------------------------------------------------------------
      $items['tab'] = array(
          'label' => esc_html__('Tab', 'quadmenu'),
          'title' => esc_html__('Tab', 'quadmenu'),
          'panels' => array(
              'general' => array(
                  'title' => esc_html__('General', 'quadmenu'),
                  'icon' => 'dashicons dashicons-admin-settings',
                  'settings' => array('subtitle', 'badge', 'hidden'),
              ),
              'icon' => array(
                  'title' => esc_html__('Icon', 'quadmenu'),
                  'icon' => 'dashicons dashicons-art',
                  'settings' => array('icon'),
              ),
          ),
          'desc' => esc_html__('Tab for QuadMenu Tabs.', 'quadmenu'),
          'parent' => 'tabs',
      );
      $items['panel'] = array(
          'label' => esc_html__('Panel', 'quadmenu'),
          'title' => esc_html__('Panel', 'quadmenu'),
          'panels' => array(
          ),
          'desc' => esc_html__('Panel for QuadMenu Carousel.', 'quadmenu'),
          'parent' => 'carousel',
      );
      $items['button'] = array(
          'label' => esc_html__('QuadMenu Button', 'quadmenu'),
          'title' => esc_html__('Button', 'quadmenu'),
          'panels' => array(
              'general' => array(
                  'title' => esc_html__('General', 'quadmenu'),
                  'icon' => 'dashicons dashicons-admin-settings',
                  'settings' => array('float', 'hidden', 'dropdown'),
              ),
              'icon' => array(
                  'title' => esc_html__('Icon', 'quadmenu'),
                  'icon' => 'dashicons dashicons-art',
                  'settings' => array('icon'),
              ),
          ),
          'desc' => esc_html__('Add button element.', 'quadmenu'),
          'depth' => 0,
              //'parent' => array('main', 'column', 'custom', 'login', 'post_type', 'post_type_archive', 'taxonomy'),
      );

      // Archives
      // ---------------------------------------------------------------------

      $items['post_type_archive'] = array(
          'label' => esc_html__('Posts', 'quadmenu'),
          'title' => esc_html__('Posts', 'quadmenu'),
          'panels' => array(
              'general' => array(
                  'title' => esc_html__('General', 'quadmenu'),
                  'icon' => 'dashicons dashicons-admin-settings',
                  'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
              ),
              'icon' => array(
                  'title' => esc_html__('Icon', 'quadmenu'),
                  'icon' => 'dashicons dashicons-art',
                  'settings' => array('icon'),
              ),
              'query' => array(
                  'title' => esc_html__('Query', 'quadmenu'),
                  'icon' => 'dashicons dashicons-update',
                  'settings' => array('limit', 'orderby', 'order'),
              ),
              'archive_carousel' => array(
                  'title' => esc_html__('Carousel', 'quadmenu'),
                  'icon' => 'dashicons dashicons-image-flip-horizontal',
                  'settings' => array('items', 'speed', 'autoplay', 'autoplay_speed', 'dots', 'pagination', 'navigation'),
              ),
              'content' => array(
                  'title' => esc_html__('Posts', 'quadmenu'),
                  'icon' => 'dashicons dashicons-format-aside',
                  'settings' => array('thumb', 'excerpt'),
              ),
          ),
          'parent' => array('main', 'column', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
      );

      $items['taxonomy'] = array(
          'panels' => array(
              'general' => array(
                  'title' => esc_html__('General', 'quadmenu'),
                  'icon' => 'dashicons dashicons-admin-settings',
                  'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
              ),
              'icon' => array(
                  'title' => esc_html__('Icon', 'quadmenu'),
                  'icon' => 'dashicons dashicons-art',
                  'settings' => array('icon'),
              ),
              'query' => array(
                  'title' => esc_html__('Query', 'quadmenu'),
                  'icon' => 'dashicons dashicons-update',
                  'settings' => array('limit', 'orderby', 'order'),
              ),
              'archive_carousel' => array(
                  'title' => esc_html__('Carousel', 'quadmenu'),
                  'icon' => 'dashicons dashicons-image-flip-horizontal',
                  'settings' => array('items', 'speed', 'autoplay', 'autoplay_speed', 'dots', 'pagination', 'navigation'),
              ),
              'content' => array(
                  'title' => esc_html__('Posts', 'quadmenu'),
                  'icon' => 'dashicons dashicons-format-aside',
                  'settings' => array('thumb', 'excerpt'),
              ),
          ),
          'parent' => array('main', 'column', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
      );

      return $items;
    }

    function nav_menu_item_fields($settings, $menu_obj) {

      $settings['password'] = array(
          'id' => 'quadmenu-settings[password]',
          'db' => 'password',
          'title' => esc_html__('Reset Password', 'quadmenu'),
          'placeholder' => wp_lostpassword_url(get_permalink()),
          'type' => 'text',
          'default' => '',
      );

      $settings['register'] = array(
          'id' => 'quadmenu-settings[register]',
          'db' => 'register',
          'title' => esc_html__('Register'),
          'placeholder' => esc_html__('Custom register account link'),
          'type' => 'text',
          'default' => '',
      );

      $settings['login'] = array(
          'id' => 'quadmenu-settings[login]',
          'db' => 'login',
          'type' => 'icon',
          'placeholder' => esc_html__('Search', 'quadmenu'),
          'default' => 'dashicons dashicons-unlock',
      );

      $settings['logout'] = array(
          'id' => 'quadmenu-settings[logout]',
          'db' => 'logout',
          'type' => 'icon',
          'placeholder' => esc_html__('Search', 'quadmenu'),
          'default' => 'dashicons dashicons-lock',
      );

      $settings['name'] = array(
          'id' => 'quadmenu-settings[name]',
          'db' => 'name',
          'type' => 'select',
          'title' => esc_html__('Name', 'quadmenu'),
          'ops' => array(
              'user_login' => esc_html__('Username'),
              'nickname' => esc_html__('Nickname'),
              'display_name' => esc_html__('Display name'),
          ),
          'default' => 'user_login',
      );

      $settings['avatar'] = array(
          'id' => 'quadmenu-settings[avatar]',
          'db' => 'avatar',
          'type' => 'checkbox',
          'title' => esc_html__('Avatar', 'quadmenu'),
          'placeholder' => esc_html__('Display user avatar', 'quadmenu'),
          'default' => 'off',
      );

      $settings['account'] = array(
          'id' => 'quadmenu-settings[account]',
          'db' => 'account',
          'title' => esc_html__('Account'),
          'placeholder' => esc_html__('Custom my account link', 'quadmenu'),
          'type' => 'text',
          'default' => '',
      );

      $settings['login_text'] = array(
          'id' => 'quadmenu-settings[login_text]',
          'db' => 'login_text',
          'title' => esc_html__('Footer', 'quadmenu'),
          'type' => 'textarea',
          'default' => '',
      );

      // Carousel
      // ---------------------------------------------------------------------

      $settings['speed'] = array(
          'id' => 'quadmenu-settings[speed]',
          'db' => 'speed',
          'type' => 'number',
          'title' => esc_html__('Speed', 'quadmenu'),
          'ops' => array(
              'step' => 100,
              'min' => 100,
              'max' => 10000
          ),
          'default' => 1500,
      );

      $settings['autoplay'] = array(
          'id' => 'quadmenu-settings[autoplay]',
          'db' => 'autoplay',
          'type' => 'checkbox',
          'title' => esc_html__('Autoplay', 'quadmenu'),
          'placeholder' => esc_html__('Run carousel automatically', 'quadmenu'),
          'default' => 'off',
      );

      $settings['autoplay_speed'] = array(
          'id' => 'quadmenu-settings[autoplay_speed]',
          'db' => 'autoplay_speed',
          'type' => 'number',
          'title' => esc_html__('Autoplay Speed', 'quadmenu'),
          'placeholder' => esc_html__('Time between 2 consecutive slides (in ms)', 'quadmenu'),
          'ops' => array(
              'step' => 100,
              'min' => 100,
              'max' => 10000
          ),
          'default' => 500,
      );

      $settings['dots'] = array(
          'id' => 'quadmenu-settings[dots]',
          'db' => 'dots',
          'type' => 'checkbox',
          'placeholder' => esc_html__('Show dots control', 'quadmenu'),
          'title' => esc_html__('Dots', 'quadmenu'),
          'default' => 'off',
      );

      $settings['pagination'] = array(
          'id' => 'quadmenu-settings[pagination]',
          'db' => 'pagination',
          'type' => 'checkbox',
          'placeholder' => esc_html__('Show pagination control', 'quadmenu'),
          'title' => esc_html__('Pagination', 'quadmenu'),
          'default' => 'on',
      );

      $settings['navigation'] = array(
          'id' => 'quadmenu-settings[navigation]',
          'db' => 'navigation',
          'type' => 'checkbox',
          'placeholder' => esc_html__('Show navigation control', 'quadmenu'),
          'title' => esc_html__('Navigation', 'quadmenu'),
          'default' => 'off',
      );

      /* $settings['controls'] = array(
        'id' => 'quadmenu-settings[controls]',
        'db' => 'controls',
        'type' => 'select',
        'title' => esc_html__('Controls', 'quadmenu'),
        'ops' => array(
        'middle' => esc_html__('Middle', 'quadmenu'),
        'center' => esc_html__('Center', 'quadmenu'),
        'left' => esc_html__('Bottom Left', 'quadmenu'),
        'right' => esc_html__('Bottom Right', 'quadmenu'),
        ),
        'default' => 'left',
        ); */

      // Query
      // ---------------------------------------------------------------------

      $settings['items'] = array(
          'id' => 'quadmenu-settings[items]',
          'db' => 'items',
          'type' => 'number',
          'title' => esc_html__('Items', 'quadmenu'),
          'depth' => array(1, 2, 3, 4),
          'ops' => array(
              'step' => 1,
              'min' => 0,
              'max' => 6
          ),
          'default' => 0,
      );

      $settings['limit'] = array(
          'id' => 'quadmenu-settings[limit]',
          'db' => 'limit',
          'type' => 'number',
          'depth' => array(1, 2, 3, 4),
          'title' => esc_html__('Limit', 'quadmenu'),
          'ops' => array(
              'step' => 1,
              'min' => 1,
              'max' => 12
          ),
          'default' => 3,
      );

      $settings['orderby'] = array(
          'id' => 'quadmenu-settings[orderby]',
          'db' => 'orderby',
          'type' => 'select',
          'depth' => array(1, 2, 3, 4),
          'title' => esc_html__('Orderby', 'quadmenu'),
          'ops' => array(
              'date' => esc_html__('Date', 'quadmenu'),
              'title' => esc_html__('Title', 'quadmenu'),
          ),
          'default' => 'date',
      );

      $settings['order'] = array(
          'id' => 'quadmenu-settings[order]',
          'db' => 'order',
          'type' => 'select',
          'depth' => array(1, 2, 3, 4),
          'title' => esc_html__('Order', 'quadmenu'),
          'ops' => array(
              'ASC' => esc_html__('Ascending', 'quadmenu'),
              'DESC' => esc_html__('Descending', 'quadmenu'),
          ),
          'default' => 'DESC',
      );

      return $settings;
    }

    function item_object_class($class, $item, $id, $auto_child = '') {

      switch ($item->quadmenu) {

        case 'post_type_archive';
          $class = 'QuadMenuItemArchive';
          break;

        case 'taxonomy';
          $class = 'QuadMenuItemTaxonomy';
          break;

        case 'carousel';
          $class = 'QuadMenuItemCarousel';
          break;

        case 'panel';
          $class = 'QuadMenuItemCarouselPanel';
          break;

        case 'tabs';
          $class = 'QuadMenuItemTabs';
          break;

        case 'tab';
          $class = 'QuadMenuItemTab';
          break;

        case 'login';
          $class = 'QuadMenuItemLogin';
          break;

        case 'social':
          $class = 'QuadMenuItemSocial';
          break;

        case 'button';
          $class = 'QuadMenuItemButton';
          break;
      }

      return $class;
    }

    function social($sections) {

      $sections[] = array(
          'id' => 'quadmenu_social',
          'title' => esc_html__('Social', 'quadmenu'),
          'heading' => false,
          'icon' => 'dashicons dashicons-share',
          'class' => 'quadmenu_social',
          'permissions' => 'edit_theme_options',
          'fields' => array(
              array(
                  'id' => 'social',
                  'type' => 'icons',
                  'title' => esc_html__('Networks', 'quadmenu'),
                  'subtitle' => esc_html__('Add your social networks', 'quadmenu'),
                  'placeholder' => array(
                      'title' => esc_html__('Title', 'quadmenu'),
                      'icon' => esc_html__('Icon', 'quadmenu'),
                      'url' => esc_html__('Link', 'quadmenu'),
                  ),
                  'show' => array(
                      'upload' => false,
                      'description' => false,
                      'key' => false,
                      'title' => true,
                      'icon' => true,
                      'url' => true,
                  ),
                  'default' => apply_filters('quadmenu_default_options_social', array(
                      array(
                          'title' => 'Facebook',
                          'icon' => 'dashicons dashicons-facebook-alt',
                          'url' => QUADMENU_DEMO,
                      ),
                      array(
                          'title' => 'Twitter',
                          'icon' => 'dashicons dashicons-twitter',
                          'url' => QUADMENU_DEMO,
                      ),
                      array(
                          'title' => 'Google',
                          'icon' => 'dashicons dashicons-googleplus',
                          'url' => QUADMENU_DEMO,
                      ),
                      array(
                          'title' => 'RSS',
                          'icon' => 'dashicons dashicons-rss',
                          'url' => QUADMENU_DEMO,
                      ),
                  )),
              ),
          ),
      );

      return $sections;
    }

    function remove_nav_menu_item($remove) {

      $remove[] = 'tab';
      $remove[] = 'tabs';
      $remove[] = 'panel';
      $remove[] = 'carousel';

      return $remove;
    }

    function login_user() {

      if (!check_ajax_referer('quadmenu', 'nonce', false)) {
        QuadMenu::send_json_error(esc_html__('Please reload page.', 'quadmenu'));
      }

      $username = sanitize_user($_POST['user']);
      $password = $_POST['pass'];
      $remember = sanitize_email($_POST['remember']);

      if (empty($username)) {
        QuadMenu::send_json_error(sprintf('<div class="quadmenu-alert alert-danger">%s</div>', esc_html__('Please provide an username.', 'quadmenu')));
      }

      if (empty($password)) {
        QuadMenu::send_json_error(sprintf('<div class="quadmenu-alert alert-danger">%s</div>', esc_html__('Please provide a password.', 'quadmenu')));
      }

      $userdata = array(
          'user_login' => $username,
          'user_password' => $password,
          'remember' => $remember,
      );

      $user_id = wp_signon($userdata);

      if (!is_wp_error($user_id)) {
        QuadMenu::send_json_success(sprintf('<div class="quadmenu-alert alert-success">%s</div>', esc_html__('Login successful, redirecting...', 'quadmenu')));
      } else {
        QuadMenu::send_json_error(sprintf('<div class="quadmenu-alert alert-danger">%s</div>', $user_id->get_error_message()));
      }

      wp_die();
    }

    function register_user($output) {

      if (!check_ajax_referer('quadmenu', 'nonce', false)) {
        QuadMenu::send_json_error(esc_html__('Please reload page.', 'quadmenu'));
      }

      $username = sanitize_user($_POST['user']);
      $email = sanitize_email($_POST['mail']);
      $password = $_POST['pass'];

      if (empty($username)) {
        QuadMenu::send_json_error(sprintf('<div class="quadmenu-alert alert-danger">%s</div>', esc_html__('Please provide an username.', 'quadmenu')));
      }

      if (empty($email) || !is_email($email)) {
        QuadMenu::send_json_error(sprintf('<div class="quadmenu-alert alert-danger">%s</div>', esc_html__('Please provide a valid email address.', 'quadmenu')));
      }

      if (empty($password)) {
        QuadMenu::send_json_error(sprintf('<div class="quadmenu-alert alert-danger">%s</div>', esc_html__('Please provide a password.', 'quadmenu')));
      }

      $userdata = array(
          'user_login' => $username,
          'user_pass' => $password,
          'user_email' => $email,
      );

      $user_id = wp_insert_user($userdata);

      if (!is_wp_error($user_id)) {

        $user = get_user_by('id', $user_id);

        if ($user) {
          wp_set_current_user($user_id, $user->user_login);
          wp_set_auth_cookie($user_id);
          do_action('wp_login', $user->user_login);
        }

        QuadMenu::send_json_success(sprintf('<div class="quadmenu-alert alert-success">%s</div>', esc_html__('Welcome! Your user have been created.', 'quadmenu')));
      } else {
        QuadMenu::send_json_error(sprintf('<div class="quadmenu-alert alert-danger">%s</div>', $user_id->get_error_message()));
      }

      wp_die();
    }

  }

  new QuadMenu_Advanced();

endif;