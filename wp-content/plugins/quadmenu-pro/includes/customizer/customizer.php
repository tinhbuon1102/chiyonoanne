<?php

if (!defined('ABSPATH')) {
  die('-1');
}

if (class_exists('QuadMenu_Compiler')) {

  class QuadMenu_Customizer extends QuadMenu_Compiler {

    private $parent;
    private $options = array();
    private $options_refresh = array();

    public function __construct($parent) {

      global $pagenow, $wp_customize;

      define('QUADMENU_CUSTOMIZER_URL', plugin_dir_url(__FILE__));

      $this->parent = $parent;

      if (empty($this->parent->args['customizer'])) {
        return;
      }

      add_action('admin_menu', array($this, 'panel'), 20);

      if (!isset($wp_customize) && $pagenow !== 'customize.php' && $pagenow !== 'admin-ajax.php') {
        return;
      }

      add_action('wp_ajax_quadmenu_customize', array($this, '_override_customize'));

      add_action('wp_ajax_nopriv_quadmenu_customize', array($this, '_override_customize'));

      add_action('customize_register', array($this, 'register_customizer_controls'));

      add_action('customize_register', array($this, 'register_customizer_controls_themes'));

      add_action('customize_register', array($this, 'remove_customize_sections'), 9999);

      add_action('customize_save_after', array(&$this, 'customizer_save_after'));

      add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_controls'));

      add_action('customize_preview_init', array($this, 'delete_transient'));

      add_action('customize_preview_init', array($this, 'enqueue_preview'));

      add_filter('wp_nav_menu_args', array($this, 'filter_wp_nav_menu_args'), 1001);

      $this->upload_dir = ReduxFramework::$_upload_dir . 'advanced-customizer/';

      if (isset($_POST['wp_customize']) && $_POST['wp_customize'] == "on") {
        $this->parent->args['customizer_only'] = true;
      }

      if (!is_customize_preview())
        return;

      add_filter('quadmenu_global_js_data', array($this, 'js_data'));

      add_action('wp_enqueue_scripts', array($this, 'enqueue'), 999);

      add_action('redux/options/' . QUADMENU_OPTIONS . '/options', array($this, '_override_options'), 100);
    }

    public function filter_wp_nav_menu_args($args) {

      if (empty($args['can_partial_refresh']) && !empty($args['theme_location']) && is_quadmenu_location($args['theme_location'])) {

        $args['can_partial_refresh'] = true;

        $exported_args = $args;

        if (!empty($exported_args['menu']) && is_object($exported_args['menu'])) {
          $exported_args['menu'] = $exported_args['menu']->term_id;
        }

        ksort($exported_args);

        $exported_args['args_hmac'] = $this->hash_nav_menu_args($exported_args);

        $args['customize_preview_nav_menus_args'] = $exported_args;
      }

      return $args;
    }

    public function hash_nav_menu_args($args) {
      return wp_hash(serialize($args));
    }

    public function wp_nav_menu($partial, $nav_menu_args) {

      unset($partial);

      $opts = array(
          'echo' => false,
      );

      $args = wp_parse_args($opts, $nav_menu_args);

      if (isset($nav_menu_args['args_hmac'])) {

        $nav_menu_args_hmac = $nav_menu_args['args_hmac'];

        unset($nav_menu_args['args_hmac']);

        ksort($nav_menu_args);

        if (hash_equals($this->hash_nav_menu_args($nav_menu_args), $nav_menu_args_hmac)) {
          return wp_nav_menu($nav_menu_args);
        }
      }

      return wp_nav_menu($nav_menu_args);
    }

    function js_data($data) {

      $data['global'] = QUADMENU_OPTIONS;

      $data['options_refresh'] = array_values($this->options_refresh);

      return $data;
    }

    function panel() {
      add_submenu_page('quadmenu_welcome', esc_html__('Customize', 'quadmenu'), esc_html__('Customize', 'quadmenu'), 'manage_options', 'customize.php?quadmenu_customize');
    }

    public function customizer_save_after($wp_customize) {

      $changed = false;

      global $quadmenu;

      if (!empty($_POST['customized']) && $options = json_decode(stripslashes_deep($_POST['customized']), true)) {

        foreach ($options as $key => $value) {
          if (strpos($key, QUADMENU_OPTIONS) !== false) {
            $key = str_replace(QUADMENU_OPTIONS . '[', '', rtrim($key, "]"));
            $quadmenu[$key] = $value;
            $changed = true;
          }
        }

        if ($changed) {
          $this->parent->set_options($quadmenu);
        }
      }
    }

    function enqueue_controls() {

      if (!self::is_quadmenu_customize())
        return;

      require_once ReduxFramework::$_dir . 'core/enqueue.php';

      $enqueue = new reduxCoreEnqueue($this->parent);

      $enqueue->get_warnings_and_errors_array();

      $enqueue->init();

      wp_enqueue_style('quadmenu-admin');

      wp_register_script('serializejson', QUADMENU_CUSTOMIZER_URL . 'assets/jquery.serializejson.min.js', array('jquery'), QUADMENU_VERSION, true);

      wp_enqueue_script('quadmenu-customizer-controls', QUADMENU_CUSTOMIZER_URL . 'assets/quadmenu-customizer-controls' . Redux_Functions::isMin() . '.js', array('jquery', 'redux-js', 'serializejson'), QUADMENU_VERSION, true);
    }

    function delete_transient() {
      delete_transient('quadmenu_customize_option_set');
    }

    function enqueue_preview() {
      wp_enqueue_script('quadmenu-customizer', QUADMENU_CUSTOMIZER_URL . 'assets/quadmenu-customizer-preview' . QuadMenu::isMin() . '.js', array('jquery'), QUADMENU_VERSION, 'all');
    }

    function in_theme($option) {

      global $quadmenu_themes;

      foreach ($quadmenu_themes as $theme => $name) {

        if (strrpos($option, $theme) !== false) {
          return $theme;
        }
      }
    }

    public function _override_customize($data) {

      check_ajax_referer('quadmenu', 'nonce');

      global $quadmenu, $quadmenu_themes;

      $in_themes = array();

      if (!empty($_POST['customized']) && $options = json_decode(stripslashes_deep($_POST['customized']), true)) {

        foreach ($options as $key => $value) {

          if (strpos($key, QUADMENU_OPTIONS) === false)
            continue;

          $key = str_replace(QUADMENU_OPTIONS . '[', '', rtrim($key, "]"));

          $quadmenu[$key] = $value;

          $in_themes[$this->in_theme($key)] = null;
        }
      }

      // Limit css to themes changed
      // -----------------------------------------------------------------
      if (isset($_POST['change']) && $_POST['change'] === 'true' && count($in_themes) > 0) {
        $quadmenu['themes'] = QuadMenu_Themes::less_themes($in_themes);
      }

      if (is_array($quadmenu)) {
        QuadMenu::send_json_success(parent::less_variables($quadmenu));
      } else {
        QuadMenu::send_json_error(esc_html__('Failed create less variables', 'quadmenu'));
      }
    }

    public function _override_options($data) {

      if (!empty($_POST['customized']) && $options = json_decode(stripslashes_deep($_POST['customized']), true)) {

        foreach ($options as $key => $value) {
          if (strpos($key, QUADMENU_OPTIONS) !== false) {
            $key = str_replace(QUADMENU_OPTIONS . '[', '', rtrim($key, "]"));
            $data[$key] = $value;
          }
        }
      }

      return $data;
    }

    public function render($control) {
      $fieldID = str_replace(QUADMENU_OPTIONS . '-', '', $control->redux_id);
      $field = $this->options[$fieldID];

      if (isset($field['compiler']) && !empty($field['compiler'])) {
        echo '<tr class="compiler">';
      } else {
        echo '<tr>';
      }
      echo '<th scope="row">' . $this->parent->field_head[$field['id']] . '</th>';
      echo '<td>';
      $field['name'] = $field['id'];
      $this->parent->_field_input($field);
      echo '</td>';
      echo '</tr>';
    }

    public function register_customizer_controls($wp_customize) {

      if (!class_exists('QuadMenu_Customizer_Section')) {
        require_once dirname(__FILE__) . '/inc/customizer_section.php';
        if (method_exists($wp_customize, 'register_section_type')) {
          $wp_customize->register_section_type('QuadMenu_Customizer_Section');
        }
      }
      if (!class_exists('QuadMenu_Customizer_Panel')) {
        require_once dirname(__FILE__) . '/inc/customizer_panel.php';
        if (method_exists($wp_customize, 'register_panel_type')) {
          $wp_customize->register_panel_type('QuadMenu_Customizer_Panel');
        }
      }
      if (!class_exists('QuadMenu_Customizer_Control')) {
        require_once dirname(__FILE__) . '/inc/customizer_control.php';
      }

      require_once dirname(__FILE__) . '/inc/customizer_fields.php';
      require_once dirname(__FILE__) . '/inc/customizer_devs.php';

      do_action("redux/extension/customizer/control/includes");

      $order = array(
          'heading' => - 500,
          'option' => - 500,
      );
      $defaults = array(
          'default-color' => '',
          'default-image' => '',
          'wp-head-callback' => '',
          'admin-head-callback' => '',
          'admin-preview-callback' => ''
      );
      $panel = "";

      $this->parent->args['options_api'] = false;

      $this->parent->_register_settings();

      foreach ($this->parent->sections as $key => $section) {

        // Not a type that should go on the customizer
        if (isset($section['type']) && ( $section['type'] == "divide" )) {
          continue;
        }

        if (isset($section['id']) && $section['id'] == "import/export") {
          continue;
        }

        // If section customizer is set to false
        if (isset($section['customizer']) && $section['customizer'] === false) {
          continue;
        }

        $section['permissions'] = isset($section['permissions']) ? $section['permissions'] : 'edit_theme_options';

        // No errors please
        if (!isset($section['desc'])) {
          $section['desc'] = "";
        }

// Fill the description if there is a subtitle
        if (empty($section['desc']) && !empty($section['subtitle'])) {
          $section['desc'] = $section['subtitle'];
        }

// Let's make a section ID from the title
        if (empty($section['id'])) {
          $section['id'] = strtolower(str_replace(" ", "", $section['title']));
        }

// No title is present, let's show what section is missing a title
        if (!isset($section['title'])) {
          $section['title'] = "";
        }

        if (!isset($section['customizer_title'])) {
          $section['customizer_title'] = $section['title'];
        }

// Let's set a default priority
        if (empty($section['priority'])) {
          $section['priority'] = $order['heading'];
          $order['heading'] ++;
        }

        if (!empty($section['icon'])) {
//$section['title'] = '<i class="' . $section['icon'] . '"></i>' . $section['title'];
        }

        if (method_exists($wp_customize, 'add_panel') && (!isset($section['subsection']) || ( isset($section['subsection']) && $section['subsection'] != true ) ) && isset($this->parent->sections[( $key + 1 )]['subsection']) && $this->parent->sections[( $key + 1 )]['subsection']) {

          $this->add_panel($section['id'], array(
              'icon' => $section['icon'],
              'priority' => $section['priority'],
              'capability' => $section['permissions'],
              'title' => $section['title'],
              'section' => $section,
              'opt_name' => QUADMENU_OPTIONS,
              'description' => '',), $wp_customize);

          $panel = $section['id'];

          $this->add_section($section['id'], array(
              'icon' => $section['icon'],
              'title' => $section['customizer_title'],
              'priority' => $section['priority'],
              'description' => $section['desc'],
              'section' => $section,
              'opt_name' => QUADMENU_OPTIONS,
              'capability' => $section['permissions'],
              'panel' => $panel), $wp_customize);
        } else {
          if (!isset($section['subsection']) || ( isset($section['subsection']) && $section['subsection'] != true )) {
            $panel = "";
          }
          $this->add_section($section['id'], array(
              'icon' => $section['icon'],
              'title' => $section['title'],
              'priority' => $section['priority'],
              'description' => $section['desc'],
              'opt_name' => QUADMENU_OPTIONS,
              'section' => $section,
              'capability' => $section['permissions'],
              'panel' => $panel), $wp_customize);
        }

        if (!isset($section['fields']) || ( isset($section['fields']) && empty($section['fields']) )) {
          continue;
        }

        foreach ($section['fields'] as $skey => $option) {

          if (empty($option['customizer'])) {
            continue;
          }

          if ($this->parent->args['customizer'] === false && (!isset($option['customizer']) || $option['customizer'] !== true )) {
            continue;
          }

          $this->options[$option['id']] = $option;

          add_action('redux/advanced_customizer/control/render/' . QUADMENU_OPTIONS . '-' . $option['id'], array($this, 'render'));

          $option['permissions'] = isset($option['permissions']) ? $option['permissions'] : 'edit_theme_options';

          if ($option['type'] != 'heading' && !isset($option['priority'])) {
            $option['priority'] = $order['option'];
            $order['option'] ++;
          }

          if (!empty($this->options_defaults[$option['id']])) {
            $option['default'] = $this->options_defaults['option']['id'];
          }

          if (!isset($option['default'])) {
            $option['default'] = "";
          }
          if (!isset($option['title'])) {
            $option['title'] = "";
          }
          if (!isset($option['transport'])) {
            $option['transport'] = 'refresh';
          }

          if ($option['transport'] == 'selective') {
            $this->options_refresh[$option['id']] = QUADMENU_OPTIONS . "[{$option['id']}]";
            $option['transport'] = 'postMessage';
          }

          $option['id'] = QUADMENU_OPTIONS . '[' . $option['id'] . ']';

          if ($option['type'] != 'heading' && $option['type'] != 'import_export' && !empty($option['type'])) {

            $wp_customize->add_setting($option['id'], array(
                'default' => $option['default'],
                'transport' => $option['transport'],
                'opt_name' => QUADMENU_OPTIONS,
                'sanitize_callback' => array($this, 'field_validation'),
            ));
          }

          if (!empty($option['data']) && empty($option['options'])) {
            if (empty($option['args'])) {
              $option['args'] = array();
            }

            if ($option['data'] == "elusive-icons" || $option['data'] == "elusive-icon" || $option['data'] == "elusive") {
              $icons_file = ReduxFramework::$_dir . 'inc/fields/select/elusive-icons.php';
              $icons_file = apply_filters('redux-font-icons-file', $icons_file);

              if (file_exists($icons_file)) {
                require_once $icons_file;
              }
            }
            $option['options'] = $this->parent->get_wordpress_data($option['data'], $option['args']);
          }

          $class_name = 'QuadMenu_Customizer_Control_' . $option['type'];

          do_action('redux/extension/customizer/control_init', $option);

          if (!class_exists($class_name)) {
            continue;
          }

          $wp_customize->add_control(new $class_name($wp_customize, $option['id'], array(
              'label' => $option['title'],
              'section' => $section['id'],
              'settings' => $option['id'],
              'type' => 'redux-' . $option['type'],
              'field' => $option,
              'ReduxFramework' => $this->parent,
              'active_callback' => ( isset($option['required']) && class_exists('QuadMenu_Customizer_Active_Callback') ) ? array(
                  'QuadMenu_Customizer_Active_Callback',
                  'evaluate'
                      ) : '__return_true',
              'priority' => $option['priority'],
          )));

          $section['fields'][$skey]['name'] = $option['id'];

          if (!isset($section['fields'][$skey]['class'])) { // No errors please
            $section['fields'][$skey]['class'] = "";
          }

          $this->controls[$section['fields'][$skey]['id']] = $section['fields'][$skey];

          add_action('redux/advanced_customizer/render/' . $option['id'], array($this, 'field_render'), $option['priority']);
        }
      }
    }

    public function selective_refresh_settings($theme) {

      $selective_refresh_settings = array();

      foreach ($this->options_refresh as $id => $setting) {

        if (strpos($setting, $theme) === false) {
          continue;
        }

        $selective_refresh_settings[] = $setting;
      }

      return $selective_refresh_settings;
    }

    public function register_customizer_controls_themes($wp_customize) {

      global $quadmenu_themes;

      foreach ($quadmenu_themes as $theme => $name) {

        $wp_customize->selective_refresh->add_partial('quadmenu_partial_' . $theme, array(
            'selector' => "nav#quadmenu.quadmenu-{$theme}",
            'settings' => $this->selective_refresh_settings($theme),
            'render_callback' => array($this, 'wp_nav_menu'),
            'container_inclusive' => true,
            'fallback_refresh' => false
        ));
      }
    }

    public function add_section($id, $args = array(), $wp_customize) {

      if (is_a($id, 'WP_Customize_Section')) {
        $section = $id;
      } else {
        $section = new QuadMenu_Customizer_Section($wp_customize, $id, $args);
      }

      $wp_customize->add_section($section, $args);
    }

    public function add_panel($id, $args = array(), $wp_customize) {

      if (is_a($id, 'WP_Customize_Panel')) {
        $panel = $id;
      } else {
        $panel = new QuadMenu_Customizer_Panel($wp_customize, $id, $args);
      }

      $wp_customize->add_panel($panel, $args);
    }

    public function field_render($option) {
      echo '1';
      preg_match_all("/\[([^\]]*)\]/", $option->id, $matches);
      $id = $matches[1][0];
      echo $option->link();
      $this->parent->_field_input($this->controls[$id]);
      echo '2';
    }

    public function field_validation($value) {
      return $value;
    }

    function remove_customize_sections($wp_customize) {

      if (!isset($_REQUEST['customized'])) {

        $customizer_option_set = false;

        foreach ($wp_customize->sections() as $section_key => $section_object) {

          if (self::is_quadmenu_customize() && strpos($section_key, 'quadmenu') === false && strpos($section_key, 'nav_menu') === false) {
            $wp_customize->remove_section($section_key);
          }

          if (!self::is_quadmenu_customize() && strpos($section_key, 'quadmenu') !== false) {
            $wp_customize->remove_section($section_key);
          }
        }
      }
    }

    static public function is_quadmenu_customize() {

      $customizer_option_set = false;

      if (isset($_REQUEST['quadmenu_customize'])) {
        $customizer_option_set = true;

        set_transient('quadmenu_customize_option_set', $customizer_option_set, 30);
      }

      if (false === $customizer_option_set && ( $q_customizer_option_set_value = get_transient('quadmenu_customize_option_set') )) {
        $customizer_option_set = $q_customizer_option_set_value;
      }

      return $customizer_option_set;
    }

  }

} // if