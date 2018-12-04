<?php

if (!defined('ABSPATH')) {
  die('-1');
}

if (class_exists('QuadMenu_Customizer')) {

  class QuadMenu_Divi_Customizer extends QuadMenu_Customizer {

    public $ET_Color_Alpha_Control;
    public $divi_settings = array(
        'et_divi[accent_color]',
        'et_divi[font_color]',
        'et_divi[body_font]',
        'et_divi[body_font_size]',
        //'et_divi[menu_height]',
        //'et_divi[minimized_menu_height]',
        'et_divi[primary_nav_font_style]',
        'et_divi[primary_dropdown_font_spacing]',
        'et_divi[primary_nav_font_size]',
        'et_divi[primary_nav_font_spacing]',
        'et_divi[primary_nav_font]',
        'et_divi[menu_link]',
        'et_divi[menu_link_active]',
        'et_divi[primary_nav_dropdown_bg]',
        'et_divi[primary_nav_dropdown_line_color]',
        'et_divi[primary_nav_dropdown_link_color]',
    );
    public $custom_settings = array(
        'et_divi[divi_primary_menu_layout_lazyload]',
        'et_divi[divi_primary_menu_layout_current]',
        //'et_divi[divi_primary_menu_layout_divider]',
        'et_divi[divi_primary_menu_layout_caret]',
        'et_divi[divi_primary_menu_layout_classes]',
        'et_divi[divi_primary_menu_layout_trigger]',
        'et_divi[divi_primary_menu_layout_dropdown_maxheight]',
        'et_divi[divi_primary_menu_navbar_text]',
        'et_divi[divi_primary_menu_navbar_link_icon]',
        'et_divi[divi_primary_menu_navbar_link_icon_hover]',
        'et_divi[divi_primary_menu_navbar_link_subtitle]',
        'et_divi[divi_primary_menu_navbar_link_subtitle_hover]',
        'et_divi[divi_primary_menu_navbar_badge_color]',
        'et_divi[divi_primary_menu_navbar_badge]',
        'et_divi[divi_primary_menu_navbar_button]',
        'et_divi[divi_primary_menu_navbar_button_background]',
        'et_divi[divi_primary_menu_navbar_button_hover]',
        'et_divi[divi_primary_menu_navbar_button_hover_background]',
        'et_divi[divi_primary_menu_dropdown_margin]',
        'et_divi[divi_primary_menu_dropdown_shadow]',
        'et_divi[divi_primary_menu_dropdown_link_hover]',
        'et_divi[divi_primary_menu_dropdown_title]',
        'et_divi[divi_primary_menu_dropdown_title_border_width]',
        'et_divi[divi_primary_menu_dropdown_title_border_color]',
        'et_divi[divi_primary_menu_dropdown_link_bg_hover]',
        'et_divi[divi_primary_menu_dropdown_link_border_color]',
        'et_divi[divi_primary_menu_dropdown_link_icon]',
        'et_divi[divi_primary_menu_dropdown_link_icon_hover]',
        'et_divi[divi_primary_menu_dropdown_link_subtitle]',
        'et_divi[divi_primary_menu_dropdown_link_subtitle_hover]',
        'et_divi[divi_primary_menu_dropdown_tab_bg]',
        'et_divi[divi_primary_menu_dropdown_tab_bg_hover]',
        'et_divi[divi_primary_menu_dropdown_button]',
        'et_divi[divi_primary_menu_dropdown_button_hover]',
        'et_divi[divi_primary_menu_dropdown_button_bg]',
        'et_divi[divi_primary_menu_dropdown_button_bg_hover]'
    );

    function __construct() {

      add_action('wp_ajax_quadmenu_divi_customized', array($this, '_override_values'));

      add_action('wp_ajax_nopriv_quadmenu_divi_customized', array($this, '_override_values'));

      add_action('customize_controls_enqueue_scripts', array($this, 'enqueue_controls'));

      add_action('customize_preview_init', array($this, 'enqueue_preview'));

      add_action('customize_register', array($this, 'custom_settings'), 100);

      add_filter('customize_dynamic_setting_args', array($this, '_override_transport'), 10, 2);

      if (!is_customize_preview())
        return;

      add_filter('quadmenu_global_js_data', array($this, 'js_data'));

      add_action('wp_enqueue_scripts', array($this, 'enqueue'), 999);
    }

    function js_data($data) {

      $data['customizer_settings'] = array_merge($this->divi_settings, $this->custom_settings);
      $data['custom_settings'] = $this->custom_settings;

      return $data;
    }

    function enqueue_controls() {
      wp_dequeue_style('quadmenu-admin');
      wp_enqueue_style('quadmenu-divi-constrols', QUADMENU_DIVI_URL . 'assets/quadmenu-divi-controls' . QuadMenu::isMin() . '.css', array(), QUADMENU_VERSION, 'all');
      wp_enqueue_script('quadmenu-divi-controls', QUADMENU_DIVI_URL . 'assets/quadmenu-divi-controls' . QuadMenu::isMin() . '.js', array(), QUADMENU_VERSION, 'all');
      wp_localize_script('quadmenu-divi-controls', 'quadmenu', apply_filters('quadmenu_global_js_data', array()));
    }

    function enqueue_preview() {
      wp_enqueue_script('quadmenu-divi-preview', QUADMENU_DIVI_URL . 'assets/quadmenu-divi-preview' . QuadMenu::isMin() . '.js', array('jquery'), QUADMENU_VERSION, 'all');
    }

    function custom_settings($wp_customize) {

      if (class_exists('ET_Divi_Customize_Color_Alpha_Control')) {
        $this->ET_Color_Alpha_Control = 'ET_Divi_Customize_Color_Alpha_Control';
      } elseif (class_exists('ET_Divi_Customize_Color_Alpha_Control')) {
        $this->ET_Color_Alpha_Control = 'ET_Color_Alpha_Control';
      } else {
        return;
      }

      // Dependency
      // ---------------------------------------------------------------------

      $controls = array();

      foreach ($this->custom_settings as $control) {
        $controls[] = $wp_customize->get_control($control);
      }

      $header_text_controls = array_filter($controls);

      foreach ($header_text_controls as $control) {

        $control->active_callback = function( $control ) {

          $setting = $control->manager->get_setting('et_divi[primary-menu_integration]');

          if (!$setting) {
            return true;
          }
          return (bool) $setting->value();
        };
      }

      // QuadMenu
      // ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[primary-menu_integration]', array(
          'type' => 'option',
          'default' => 1,
          'capability' => 'edit_theme_options',
          //'transport' => 'postMessage',
          'transport' => 'refresh',
          'sanitize_callback' => 'wp_validate_boolean',
      ));

      $wp_customize->add_control('et_divi[primary-menu_integration]', array(
          'label' => esc_html__('Activate QuadMenu', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'type' => 'checkbox',
          'priority' => -1,
      ));

      // Lazyload
      // ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_layout_lazyload]', array(
          'type' => 'option',
          'default' => 0,
          'capability' => 'edit_theme_options',
          //'transport' => 'postMessage',
          'transport' => 'refresh',
          'sanitize_callback' => 'wp_validate_boolean',
      ));

      $wp_customize->add_control('et_divi[divi_primary_menu_layout_lazyload]', array(
          'label' => esc_html__('Activate Lazyload', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'type' => 'checkbox',
          'priority' => -1,
      ));

      // current

      $wp_customize->add_setting('et_divi[divi_primary_menu_layout_current]', array(
          'type' => 'option',
          'default' => 0,
          'capability' => 'edit_theme_options',
          //'transport' => 'postMessage',
          'transport' => 'refresh',
          'sanitize_callback' => 'wp_validate_boolean',
      ));

      $wp_customize->add_control('et_divi[divi_primary_menu_layout_current]', array(
          'label' => esc_html__('Open Current Item', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'type' => 'checkbox',
          'priority' => -1,
      ));

      // caret

      $wp_customize->add_setting('et_divi[divi_primary_menu_layout_caret]', array(
          'type' => 'option',
          'default' => 1,
          'capability' => 'edit_theme_options',
          //'transport' => 'postMessage',
          'transport' => 'refresh',
          'sanitize_callback' => 'wp_validate_boolean',
      ));

      $wp_customize->add_control('et_divi[divi_primary_menu_layout_caret]', array(
          'label' => esc_html__('Show Caret', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'type' => 'checkbox',
          'priority' => -1,
      ));

      // dropdown_maxheight

      $wp_customize->add_setting('et_divi[divi_primary_menu_layout_dropdown_maxheight]', array(
          'type' => 'option',
          'default' => 1,
          'capability' => 'edit_theme_options',
          //'transport' => 'postMessage',
          'transport' => 'refresh',
          'sanitize_callback' => 'wp_validate_boolean',
      ));

      $wp_customize->add_control('et_divi[divi_primary_menu_layout_dropdown_maxheight]', array(
          'label' => esc_html__('Dropdown Max Height', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'type' => 'checkbox',
          'priority' => -1,
      ));

      // trigger

      $wp_customize->add_setting('et_divi[divi_primary_menu_layout_trigger]', array(
          'type' => 'option',
          'default' => 0,
          'capability' => 'edit_theme_options',
          //'transport' => 'postMessage',
          'transport' => 'refresh',
          'sanitize_callback' => 'wp_validate_boolean',
              //'sanitize_callback' => 'sanitize_html_class',
      ));

      $wp_customize->add_control('et_divi[divi_primary_menu_layout_trigger]', array(
          'label' => esc_html__('Open Dropdown on Click', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'type' => 'checkbox',
          'priority' => -1,
      ));

      // classes

      $wp_customize->add_setting('et_divi[divi_primary_menu_layout_classes]', array(
          'type' => 'option',
          'default' => '',
          'capability' => 'edit_theme_options',
          //'transport' => 'postMessage',
          'transport' => 'refresh',
          'sanitize_callback' => 'sanitize_html_class',
      ));

      $wp_customize->add_control('et_divi[divi_primary_menu_layout_classes]', array(
          'label' => esc_html__('Classes', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'type' => 'text',
          'priority' => 99,
      ));

      $accent_color = et_get_option('accent_color', '#2ea3f2');

      $font_color = et_get_option('font_color', '#666666');

      // Text
      // ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_navbar_text]', array(
          'default' => et_get_option('font_color', '#666666'),
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_navbar_text]', array(
          'label' => esc_html__('Menu Text', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_navbar_text]',
      )));

// Icon
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_navbar_link_icon]', array(
          'default' => $font_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_navbar_link_icon]', array(
          'label' => esc_html__('Menu Icon', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_navbar_link_icon]',
      )));

// Icon Hover
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_navbar_link_icon_hover]', array(
          'default' => $accent_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_navbar_link_icon_hover]', array(
          'label' => esc_html__('Menu Icon Hover', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_navbar_link_icon_hover]',
      )));

// Subtitle
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_navbar_link_subtitle]', array(
          'default' => $font_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_navbar_link_subtitle]', array(
          'label' => esc_html__('Menu Subtitle', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_navbar_link_subtitle]',
      )));

// Subtitle Hover
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_navbar_link_subtitle_hover]', array(
          'default' => $font_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_navbar_link_subtitle_hover]', array(
          'label' => esc_html__('Menu Subtitle Hover', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_navbar_link_subtitle_hover]',
      )));

      // Badge
      // ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_navbar_badge_color]', array(
          'default' => et_get_option('all_buttons_text_color', '#ffffff'),
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_navbar_badge_color]', array(
          'label' => esc_html__('Menu Badge Color', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_navbar_badge_color]',
      )));

      $wp_customize->add_setting('et_divi[divi_primary_menu_navbar_badge]', array(
          'default' => $accent_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_navbar_badge]', array(
          'label' => esc_html__('Menu Badge Background', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_navbar_badge]',
      )));

//  Margin
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_margin]', array(
          'default' => 0,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'absint',
      ));

      $wp_customize->add_control(new ET_Divi_Range_Option($wp_customize, 'et_divi[divi_primary_menu_dropdown_margin]', array(
          'label' => esc_html__('Dropdown Margin', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'type' => 'range',
          'input_attrs' => array(
              'min' => 0,
              'step' => 1,
              'max' => 45,
          ),
      )));

      // Button
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_navbar_button]', array(
          'default' => et_get_option('all_buttons_text_color', '#ffffff'),
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_navbar_button]', array(
          'label' => esc_html__('Menu Button Color', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_navbar_button]',
      )));

      $wp_customize->add_setting('et_divi[divi_primary_menu_navbar_button_background]', array(
          'default' => $font_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_navbar_button_background]', array(
          'label' => esc_html__('Menu Button Background', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_navbar_button_background]',
      )));

// Button Hover
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_navbar_button_hover]', array(
          'default' => et_get_option('all_buttons_text_color', '#ffffff'),
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_navbar_button_hover]', array(
          'label' => esc_html__('Menu Button Hover Color', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_navbar_button_hover]',
      )));

      $wp_customize->add_setting('et_divi[divi_primary_menu_navbar_button_hover_background]', array(
          'default' => $accent_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_navbar_button_hover_background]', array(
          'label' => esc_html__('Menu Button Hover Background', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_navbar_button_hover_background]',
      )));

// Dropdown Shadow
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_shadow]', array(
          'default' => 'show',
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'refresh',
      ));

      $wp_customize->add_control('et_divi[divi_primary_menu_dropdown_shadow]', array(
          'label' => esc_html__('Dropdown Shadow', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'type' => 'select',
          'choices' => array(
              'show' => esc_html__('Show', 'quadmenu'),
              'hide' => esc_html__('Hide', 'quadmenu'),
          )
      ));

// Dropdown Link Hover
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_link_hover]', array(
          'default' => et_get_option('divi_primary_menu_dropdown_link_hover', $accent_color),
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_link_hover]', array(
          'label' => esc_html__('Dropdown Link Hover', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_link_hover]',
      )));

// Dropdown Title
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_title]', array(
          'default' => et_get_option('header_color', $font_color),
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_title]', array(
          'label' => esc_html__('Dropdown Title', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_title]',
      )));

//  Dropdown Title Border
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_title_border_width]', array(
          'default' => 3,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'absint',
      ));

      $wp_customize->add_control(new ET_Divi_Range_Option($wp_customize, 'et_divi[divi_primary_menu_dropdown_title_border_width]', array(
          'label' => esc_html__('Dropdown Title Border', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'type' => 'range',
          'input_attrs' => array(
              'min' => 0,
              'step' => 1,
              'max' => 10,
          ),
      )));

// Dropdown Border Color
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_title_border_color]', array(
          'default' => 'rgba(0,0,0,0.05)',
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_title_border_color]', array(
          'label' => esc_html__('Dropdown Title Border Color', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_title_border_color]',
      )));

// Dropdown Link Hover Background
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_link_bg_hover]', array(
          'default' => 'rgba(0,0,0,0.03)',
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_link_bg_hover]', array(
          'label' => esc_html__('Dropdown Link Hover Background', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_link_bg_hover]',
      )));

// Dropdown Link Border Color
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_link_border_color]', array(
          'default' => 'rgba(0,0,0,0.05)',
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_link_border_color]', array(
          'label' => esc_html__('Dropdown Link Border Color', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_link_border_color]',
      )));

// Icon
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_link_icon]', array(
          'default' => $font_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_link_icon]', array(
          'label' => esc_html__('Dropdown Icon', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_link_icon]',
      )));

// Icon Hover
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_link_icon_hover]', array(
          'default' => $accent_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_link_icon_hover]', array(
          'label' => esc_html__('Dropdown Icon Hover', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_link_icon_hover]',
      )));

// Subtitle
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_link_subtitle]', array(
          'default' => $font_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_link_subtitle]', array(
          'label' => esc_html__('Dropdown Subtitle', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_link_subtitle]',
      )));

// Subtitle Hover
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_link_subtitle_hover]', array(
          'default' => $font_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_link_subtitle_hover]', array(
          'label' => esc_html__('Dropdown Subtitle Hover', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_link_subtitle_hover]',
      )));

// Tab
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_tab_bg]', array(
          'default' => 'rgba(0,0,0,0.01)',
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_tab_bg]', array(
          'label' => esc_html__('Dropdown Tab', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_tab_bg]',
      )));

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_tab_bg_hover]', array(
          'default' => 'rgba(0,0,0,0.05)',
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_tab_bg_hover]', array(
          'label' => esc_html__('Dropdown Tab Hover', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_tab_bg_hover]',
      )));

// Button
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_button]', array(
          'default' => et_get_option('all_buttons_text_color', '#ffffff'),
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_button]', array(
          'label' => esc_html__('Dropdown Button Color', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_button]',
      )));

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_button_bg]', array(
          'default' => $font_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_button_bg]', array(
          'label' => esc_html__('Dropdown Button Background', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_button_bg]',
      )));

// Button Hover
// ---------------------------------------------------------------------

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_button_hover]', array(
          'default' => et_get_option('all_buttons_text_color', '#ffffff'),
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_button_hover]', array(
          'label' => esc_html__('Dropdown Button Hover Color', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_button_hover]',
      )));

      $wp_customize->add_setting('et_divi[divi_primary_menu_dropdown_button_bg_hover]', array(
          'default' => $accent_color,
          'type' => 'option',
          'capability' => 'edit_theme_options',
          'transport' => 'postMessage',
          'sanitize_callback' => 'et_sanitize_alpha_color',
      ));

      $wp_customize->add_control(new $this->ET_Color_Alpha_Control($wp_customize, 'et_divi[divi_primary_menu_dropdown_button_bg_hover]', array(
          'label' => esc_html__('Dropdown Button Hover Background', 'quadmenu'),
          'section' => 'et_divi_header_primary',
          'settings' => 'et_divi[divi_primary_menu_dropdown_button_bg_hover]',
      )));
    }

    function _override_transport($args, $id) {

      if (in_array($id, array('et_divi[header_style]'))) {

        $args['transport'] = 'refresh';
      }

      return $args;
    }

    function _override_values() {

      global $quadmenu;

      check_ajax_referer('quadmenu', 'nonce');

      $data = array();

      if (isset($_POST['customized']) && $options = json_decode(stripslashes_deep($_POST['customized']), true)) {

        foreach ($options as $key => $value) {
          if (strpos($key, 'et_divi') !== false) {
            $key = str_replace('et_divi' . '[', '', rtrim($key, "]"));
            $data[$key] = $value;
            $quadmenu[$key] = $value;
          }
        }
      }

      if (isset($data['accent_color']) && !isset($data['divi_primary_menu_navbar_link_icon_hover'])) {
        $quadmenu['divi_primary_menu_navbar_link_icon_hover'] = $data['accent_color'];
      }

      if (isset($data['accent_color']) && !isset($data['divi_primary_menu_navbar_badge'])) {
        $quadmenu['divi_primary_menu_navbar_badge'] = $data['accent_color'];
      }

      if (isset($data['accent_color']) && !isset($data['divi_primary_menu_dropdown_link_hover'])) {
        $quadmenu['divi_primary_menu_dropdown_link_hover'] = $data['accent_color'];
      }

      if (isset($data['accent_color']) && !isset($data['divi_primary_menu_dropdown_link_hover'])) {
        $quadmenu['divi_primary_menu_dropdown_link_icon_hover'] = $data['divi_primary_menu_dropdown_link_icon_hover'];
      }

      if (isset($data['accent_color']) && !isset($data['divi_primary_menu_dropdown_title_border_color'])) {
        $quadmenu['divi_primary_menu_dropdown_title_border_color'] = $data['divi_primary_menu_dropdown_title_border_color'];
      }

      if (isset($data['font_color']) && !isset($data['divi_primary_menu_navbar_text'])) {
        $quadmenu['divi_primary_menu_navbar_text'] = $data['font_color'];
      }

      if (isset($data['font_color']) && !isset($data['divi_primary_menu_navbar_link_icon'])) {
        $quadmenu['divi_primary_menu_navbar_link_icon'] = $data['font_color'];
      }

      if (isset($data['font_color']) && !isset($data['divi_primary_menu_navbar_link_subtitle'])) {
        $quadmenu['divi_primary_menu_navbar_link_subtitle'] = $data['font_color'];
      }

      if (isset($data['font_color']) && !isset($data['divi_primary_menu_navbar_link_subtitle_hover'])) {
        $quadmenu['divi_primary_menu_navbar_link_subtitle_hover'] = $data['font_color'];
      }

      if (isset($data['font_color']) && !isset($data['divi_primary_menu_dropdown_title'])) {
        $quadmenu['divi_primary_menu_dropdown_title'] = $data['font_color'];
      }

      if (isset($data['font_color']) && !isset($data['divi_primary_menu_dropdown_link_icon'])) {
        $quadmenu['divi_primary_menu_dropdown_link_icon'] = $data['font_color'];
      }

      if (isset($data['font_color']) && !isset($data['divi_primary_menu_dropdown_link_subtitle'])) {
        $quadmenu['divi_primary_menu_dropdown_link_subtitle'] = $data['font_color'];
      }

      if (isset($data['font_color']) && !isset($data['divi_primary_menu_dropdown_link_subtitle_hover'])) {
        $quadmenu['divi_primary_menu_dropdown_link_subtitle_hover'] = $data['font_color'];
      }

      if (isset($data['body_font'])) {
        $quadmenu['body_font'] = $this->validate_font($quadmenu['body_font']);
      }

      if (isset($data['body_font_size'])) {
        $quadmenu['divi_primary_menu_font_size'] = $data['body_font_size'];
        $quadmenu['divi_primary_menu_dropdown_font_size'] = $data['body_font_size'];
      }

      //$quadmenu['divi_primary_menu_dropdown_font']['font-family'] = $data['divi_primary_menu_font']['font-family'];

      if (isset($data['primary_nav_font_style']) && $font_style = explode('|', $quadmenu['primary_nav_font_style'])) {

        if (in_array('bold', $font_style)) {
          $quadmenu['divi_primary_menu_navbar_font']['font-weight'] = '700';
        } else {
          $quadmenu['divi_primary_menu_navbar_font']['font-weight'] = '600';
        }
        if (in_array('italic', $font_style)) {
          $quadmenu['divi_primary_menu_navbar_font']['font-style'] = 'italic';
        } else {
          $quadmenu['divi_primary_menu_navbar_font']['font-style'] = 'normal';
        }
        if (in_array('uppercase', $font_style)) {
          $quadmenu['divi_primary_menu_navbar_link_transform'] = 'uppercase';
        } else {
          $quadmenu['divi_primary_menu_navbar_link_transform'] = 'none';
        }
        //if (in_array('underline', $font_style)) {
        //    $font_styles .= "text-decoration: underline{$important}; ";
        //} 
      }

      if (isset($data['divi_primary_menu_dropdown_title_border_width'])) {
        $quadmenu['divi_primary_menu_dropdown_title_border']['border-top'] = $data['divi_primary_menu_dropdown_title_border_width'];
      }
      if (isset($data['divi_primary_menu_dropdown_title_border_color'])) {
        $quadmenu['divi_primary_menu_dropdown_title_border']['border-color'] = $data['divi_primary_menu_dropdown_title_border_color'];
      }
      if (isset($data['divi_primary_menu_dropdown_link_border_color'])) {
        $quadmenu['divi_primary_menu_dropdown_link_border']['border-color'] = $data['divi_primary_menu_dropdown_link_border_color'];
      }
      if (isset($data['primary_nav_font'])) {
        $quadmenu['divi_primary_menu_navbar_font']['font-family'] = $this->validate_font($quadmenu['primary_nav_font']);
      }
      if (isset($data['primary_nav_font_size'])) {
        $quadmenu['divi_primary_menu_navbar_font']['font-size'] = $data['primary_nav_font_size'];
      }
      if (isset($data['primary_nav_font_spacing'])) {
        $quadmenu['divi_primary_menu_navbar_font']['letter-spacing'] = $data['primary_nav_font_spacing'];
      }
      if (isset($data['font_color'])) {
        $quadmenu['divi_primary_menu_navbar_text'] = $data['font_color'];
      }
      if (isset($data['menu_link'])) {
        $quadmenu['divi_primary_menu_navbar_link'] = $data['menu_link'];
      }
      if (isset($data['menu_link_active'])) {
        $quadmenu['divi_primary_menu_navbar_link_hover'] = $data['menu_link_active'];
      }
      if (isset($data['primary_nav_dropdown_bg'])) {
        $quadmenu['divi_primary_menu_dropdown_background'] = $data['primary_nav_dropdown_bg'];
      }
      if (isset($data['primary_nav_dropdown_line_color'])) {
        $quadmenu['divi_primary_menu_dropdown_border']['border-color'] = $data['primary_nav_dropdown_line_color'];
      }
      if (isset($data['primary_nav_dropdown_link_color'])) {
        $quadmenu['divi_primary_menu_dropdown_link'] = $data['primary_nav_dropdown_link_color'];
      }

      if (is_array($quadmenu)) {
        QuadMenu::send_json_success(parent::less_variables($quadmenu));
      } else {
        QuadMenu::send_json_error(esc_html__('Failed create less variables', 'quadmenu'));
      }
    }

    public static function validate_font($font_family) {
      if (!$font_family || in_array($font_family, array('none', ''))) {
        return 'inherit';
      }

      return $font_family;
    }

  }

  new QuadMenu_Divi_Customizer();
}