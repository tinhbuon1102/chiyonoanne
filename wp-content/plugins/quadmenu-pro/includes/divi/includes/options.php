<?php

if (!defined('ABSPATH')) {
  die('-1');
}

if (class_exists('QuadMenu_PRO_Divi')) {

  class QuadMenu_PRO_Divi_Options {

    function __construct() {

      add_filter('quadmenu_default_options', array($this, 'defaults'), 10);

      add_filter('quadmenu_developer_options', array($this, 'options'), 10);

      add_filter('quadmenu_default_themes', array($this, 'themes'), 10);

      add_filter('quadmenu_locate_template', array($this, 'template'), 10, 5);

      if (defined('QUADMENU_OPTIONS')) {
        add_filter('redux/options/' . QUADMENU_OPTIONS . '/section/quadmenu_location_primary-menu', array($this, 'primary_menu_info'));
      }
    }

    function defaults($defaults) {

      $defaults['gutter'] = '30';
      $defaults['screen_sm_width'] = '981';
      $defaults['screen_md_width'] = '1100';
      $defaults['screen_lg_width'] = '1200';

      $defaults['primary-menu_integration'] = et_get_option('primary-menu_integration', true);
      $defaults['primary-menu_theme'] = et_get_option('primary-menu_theme', 'divi_primary_menu');

      return $defaults;
    }

    function options($options) {

      // General
      // -------------------------------------------------------------------------

      $options['viewport'] = 0;
      $options['footer-menu_integration'] = false;
      $options['footer-menu_unwrap'] = false;
      $options['footer-menu_theme'] = false;
      $options['footer-menu_information'] = false;
      $options['footer-menu_manual'] = false;
      $options['footer-menu_editor'] = false;
      $options['divi_primary_menu_theme_title'] = '';
      $options['divi_secondary_menu_theme_title'] = '';


      // Locations
      // -------------------------------------------------------------------------

      $options['primary-menu_information'] = false;
      $options['primary-menu_manual'] = false;
      $options['primary-menu_unwrap'] = false;
      //$options['primary-menu_theme'] = 'divi_primary_menu';

      if (isset($_POST['customized']) && $options = json_decode(stripslashes_deep($_POST['customized']), true)) {

        foreach ($options as $key => $value) {
          if (strpos($key, 'et_divi') !== false) {
            $key = str_replace('et_divi' . '[', '', rtrim($key, "]"));

            //var_dump($key);

            $options[$key] = $value;
          }
        }
      }

      // Themes
      // -------------------------------------------------------------------------

      $font_weight = $font_style = $text_transform = '';

      //$logo = ( $user_logo = et_get_option('divi_logo') ) && '' != $user_logo ? $user_logo : get_template_directory_uri() . '/images/logo.png';

      $accent_color = et_get_option('accent_color', '#2ea3f2');

      $font_color = et_get_option('font_color', '#666666');

      $font_family = $this->validate_font(et_get_option('body_font', 'inherit'));

      $font = explode('|', et_get_option('primary_nav_font_style', 'inherit'));

      if (in_array('bold', $font)) {
        $font_weight = '700';
      } else {
        $font_weight = '600';
      }
      if (in_array('italic', $font)) {
        $font_style = 'italic';
      } else {
        $font_style = 'normal';
      }
      if (in_array('uppercase', $font)) {
        $text_transform = 'uppercase';
      }

      $primary_nav_bg = et_get_option('primary_nav_bg', '#ffffff');

      // Custom
      // ---------------------------------------------------------------------
      //var_dump($options);

      $options['menu_height'] = et_get_option('menu_height', '66');
      $options['minimized_menu_height'] = et_get_option('minimized_menu_height', '40');
      $options['fixed_menu_link'] = et_get_option('fixed_menu_link', $accent_color);

      // Layout
      // ---------------------------------------------------------------------

      $options['divi_primary_menu_menu_divider'] = '';
      $options['divi_primary_menu_dropdown_divider'] = '';
      $options['divi_primary_menu_layout_lazyload'] = et_get_option('divi_primary_menu_layout_lazyload', 0);
      $options['divi_primary_menu_layout_current'] = et_get_option('divi_primary_menu_layout_current', 0);
      $options['divi_primary_menu_layout_caret'] = et_get_option('divi_primary_menu_layout_caret', 1) ? 'show' : 'hide';
      $options['divi_primary_menu_layout_dropdown_maxheight'] = et_get_option('divi_primary_menu_layout_dropdown_maxheight', 1);
      $options['divi_primary_menu_layout_trigger'] = et_get_option('divi_primary_menu_layout_trigger', 0) ? 'click' : 'hoverintent';
      $options['divi_primary_menu_layout_classes'] = et_get_option('divi_primary_menu_layout_classes');
      $options['divi_primary_menu_layout'] = et_get_option('header_style') === 'slide' ? 'inherit' : 'embed';
      $options['divi_primary_menu_layout_offcanvas_float'] = 'right';
      $options['divi_primary_menu_layout_align'] = 'right';
      $options['divi_primary_menu_layout_breakpoint'] = 980;
      $options['divi_primary_menu_layout_width'] = 0;
      $options['divi_primary_menu_layout_width_inner'] = '';
      $options['divi_primary_menu_layout_width_inner_selector'] = '';
      $options['divi_primary_menu_layout_divider'] = 0;
      $options['divi_primary_menu_layout_sticky_divider'] = 0;
      $options['divi_primary_menu_layout_sticky'] = 0;
      $options['divi_primary_menu_layout_sticky_offset'] = 0;
      $options['divi_primary_menu_layout_hover_effect'] = null;

      // Mobile
      // ---------------------------------------------------------------------

      $options['divi_primary_menu_mobile_shadow'] = 'hide';
      $options['divi_primary_menu_mobile_link'] = 'hide';
      $options['divi_primary_menu_mobile_link_padding'] = array(
          'border-top' => '15px',
          'border-right' => '15px',
          'border-bottom' => '15px',
          'border-left' => '15px',
          'border-style' => '',
          'border-color' => '',
      );
      $options['divi_primary_menu_mobile_link_border'] = array(
          'border-all' => '0',
          'border-top' => '0',
          'border-color' => 'transparent',
          'border-style' => 'none'
      );

      // Navbar
      // ---------------------------------------------------------------------

      $options['divi_primary_menu_mobile'] = '';
      $options['divi_primary_menu_toggle'] = '';
      $options['divi_primary_menu_logo'] = '';
      $options['divi_primary_menu_navbar_logo_link'] = esc_url(home_url('/'));
      $options['divi_primary_menu_navbar_layout'] = '';

      $options['divi_primary_menu_navbar_background'] = 'color';
      $options['divi_primary_menu_navbar_background_color'] = 'transparent';
      $options['divi_primary_menu_navbar_background_to'] = 'transparent';
      $options['divi_primary_menu_navbar_background_deg'] = 0;
      $options['divi_primary_menu_navbar'] = '';
      $options['divi_primary_menu_navbar_divider'] = 'rgba(0,0,0,0.1)';
      $options['divi_primary_menu_navbar_mobile_border'] = 'transparent';
      $options['divi_primary_menu_navbar_toggle_open'] = $accent_color;
      $options['divi_primary_menu_navbar_toggle_close'] = $accent_color;
      $options['divi_primary_menu_navbar_height'] = et_get_option('primary_nav_font_size', '14');
      $options['divi_primary_menu_navbar_width'] = '260';
      $options['divi_primary_menu_navbar_logo'] = false;
      $options['divi_primary_menu_navbar_logo_height'] = 0;
      $options['divi_primary_menu_navbar_logo_bg'] = 'transparent';
      $options['divi_primary_menu_sticky_height'] = et_get_option('minimized_menu_height', '40');
      $options['divi_primary_menu_sticky_logo_height'] = 0;
      $options['divi_primary_menu_navbar_text'] = et_get_option('font_color', '#666666');
      $options['divi_primary_menu_link'] = '';
      $options['divi_primary_menu_navbar_link'] = et_get_option('menu_link', 'rgba(0,0,0,0.6)');
      $options['divi_primary_menu_navbar_link_hover'] = et_get_option('menu_link_active', $accent_color);
      $options['divi_primary_menu_navbar_link_bg'] = 'transparent';
      $options['divi_primary_menu_navbar_link_bg_hover'] = 'transparent';
      $options['divi_primary_menu_navbar_link_hover_effect'] = $accent_color;
      $options['divi_primary_menu_navbar_link_transform'] = $text_transform;
      $options['divi_primary_menu_navbar_link_margin'] = array(
          'border-top' => '0px',
          'border-right' => '0px',
          'border-bottom' => '0px',
          'border-left' => '0px',
          'border-style' => '',
          'border-color' => '',
      );
      $options['divi_primary_menu_navbar_link_radius'] = array(
          'border-top' => '0px',
          'border-right' => '0px',
          'border-bottom' => '0px',
          'border-left' => '0px',
          'border-style' => '',
          'border-color' => '',
      );

      $options['divi_primary_menu_icon'] = '';
      $options['divi_primary_menu_navbar_link_icon'] = et_get_option('divi_primary_menu_navbar_link_icon', $font_color);
      $options['divi_primary_menu_navbar_link_icon_hover'] = et_get_option('divi_primary_menu_navbar_link_icon_hover', $accent_color);
      $options['divi_primary_menu_subtitle'] = '';
      $options['divi_primary_menu_navbar_link_subtitle'] = et_get_option('divi_primary_menu_navbar_link_subtitle', $font_color);
      $options['divi_primary_menu_navbar_link_subtitle_hover'] = et_get_option('divi_primary_menu_navbar_link_subtitle_hover', $font_color);
      $options['divi_primary_menu_badge'] = '';
      $options['divi_primary_menu_navbar_badge_color'] = et_get_option('divi_primary_menu_navbar_badge_color', '#ffffff');
      $options['divi_primary_menu_navbar_badge'] = et_get_option('divi_primary_menu_navbar_badge', $accent_color);
      $options['divi_primary_menu_navbar_button_radius'] = array(
          'border-top' => '3px',
          'border-right' => '3px',
          'border-bottom' => '3px',
          'border-left' => '3px',
          'border-style' => '',
          'border-color' => '',
      );
      $options['divi_primary_menu_navbar_scrollbar'] = $accent_color;
      $options['divi_primary_menu_navbar_scrollbar_rail'] = 'rgba(255,255,255, 0.05)';

      // Button
      // ---------------------------------------------------------------------

      $options['divi_primary_menu_navbar_button'] = et_get_option('all_buttons_text_color', '#ffffff');
      $options['divi_primary_menu_navbar_button_background'] = et_get_option('all_buttons_background_color', $accent_color);
      $options['divi_primary_menu_navbar_button_hover'] = et_get_option('all_buttons_text_color_hover', '#ffffff');
      $options['divi_primary_menu_navbar_button_hover_background'] = et_get_option('all_buttons_background_color_hover', $accent_color);

      // Sticky
      // ---------------------------------------------------------------------
      $options['divi_primary_menu_sticky'] = '';
      $options['divi_primary_menu_sticky_background'] = 'transparent';
      $options['divi_primary_menu_sticky_height'] = '60';
      $options['divi_primary_menu_sticky_logo_height'] = '25';
      $options['divi_primary_menu_scrollbar'] = '';

      // Dropdown
      // ---------------------------------------------------------------------
      $options['divi_primary_menu_dropdown_scrollbar_section'] = '';
      $options['divi_primary_menu_dropdown_link_section'] = '';
      $options['divi_primary_menu_title'] = '';
      $options['divi_primary_menu_dropdown_shadow'] = et_get_option('divi_primary_menu_dropdown_shadow', 'show');
      $options['divi_primary_menu_dropdown_margin'] = et_get_option('divi_primary_menu_dropdown_margin', 0);
      $options['divi_primary_menu_dropdown_radius'] = array(
          'border-top' => '0',
          'border-right' => '0',
          'border-left' => '0',
          'border-bottom' => '0',
      );
      $options['divi_primary_menu_dropdown_background'] = et_get_option('primary_nav_dropdown_bg', $primary_nav_bg);
      $options['divi_primary_menu_dropdown_border'] = array(
          'border-all' => '0',
          'border-top' => '3',
          'border-right' => '0',
          'border-left' => '0',
          'border-bottom' => '0',
          'border-color' => et_get_option('primary_nav_dropdown_line_color', $accent_color),
      );

      // Dropdown Link
      // ---------------------------------------------------------------------
      $options['divi_primary_menu_dropdown_link'] = et_get_option('primary_nav_dropdown_link_color', et_get_option('menu_link'));
      $options['divi_primary_menu_dropdown_link_hover'] = et_get_option('divi_primary_menu_dropdown_link_hover', $accent_color);
      $options['divi_primary_menu_dropdown_link_transform'] = 'none';
      $options['divi_primary_menu_dropdown_link_bg_hover'] = et_get_option('divi_primary_menu_dropdown_link_bg_hover', 'rgba(0,0,0,0.03)');
      $options['divi_primary_menu_dropdown_link_border'] = array(
          'border-top' => '1px',
          'border-right' => '0px',
          'border-bottom' => '0px',
          'border-left' => '0px',
          'border-style' => 'solid',
          'border-color' => et_get_option('divi_primary_menu_dropdown_link_border_color', 'rgba(0,0,0,0.05)')
      );
      $options['divi_primary_menu_dropdown_scrollbar'] = $accent_color;
      $options['divi_primary_menu_dropdown_scrollbar_rail'] = 'rgba(255,255,255, 0.05)';
      $options['divi_primary_menu_dropdown_title'] = et_get_option('primary_nav_dropdown_link_color', et_get_option('header_color', $font_color));
      $options['divi_primary_menu_dropdown_title_border'] = array(
          'border-top' => et_get_option('divi_primary_menu_dropdown_title_border_width', '3'),
          'border-right' => '',
          'border-bottom' => '',
          'border-left' => '',
          'border-style' => 'solid',
          'border-color' => et_get_option('divi_primary_menu_dropdown_title_border_color', $accent_color),
      );

      $options['divi_primary_menu_dropdown_link_icon'] = et_get_option('divi_primary_menu_dropdown_link_icon', $font_color);
      $options['divi_primary_menu_dropdown_link_icon_hover'] = et_get_option('divi_primary_menu_dropdown_link_icon_hover', $accent_color);
      $options['divi_primary_menu_dropdown_link_subtitle'] = et_get_option('divi_primary_menu_dropdown_link_subtitle', $font_color);
      $options['divi_primary_menu_dropdown_link_subtitle_hover'] = et_get_option('divi_primary_menu_dropdown_link_subtitle_hover', $font_color);

      // Tab
      // ---------------------------------------------------------------------
      $options['divi_primary_menu_tab'] = '';
      $options['divi_primary_menu_dropdown_tab_bg'] = et_get_option('divi_primary_menu_dropdown_tab_bg', 'rgba(0,0,0,0.01)');
      $options['divi_primary_menu_dropdown_tab_bg_hover'] = et_get_option('divi_primary_menu_dropdown_tab_bg_hover', 'rgba(0,0,0,0.05)');

      // Button
      // ---------------------------------------------------------------------        
      $options['divi_primary_menu_button'] = '';
      $options['divi_primary_menu_dropdown_button'] = et_get_option('all_buttons_text_color', '#ffffff');
      $options['divi_primary_menu_dropdown_button_bg'] = et_get_option('all_buttons_bg_color', $accent_color);
      $options['divi_primary_menu_dropdown_button_hover'] = et_get_option('all_buttons_text_color_hover', '#ffffff');
      $options['divi_primary_menu_dropdown_button_bg_hover'] = et_get_option('all_buttons_bg_color_hover', $accent_color);

      // Fonts
      // ---------------------------------------------------------------------

      $options['divi_primary_menu_font'] = array(
          'font-family' => $font_family,
          'font-options' => '',
          'google' => false,
          'font-weight' => '400',
          'font-style' => '',
          'subsets' => '',
          'font-size' => et_get_option('body_font_size', '14'),
          'letter-spacing' => 'inherit',
      );
      $options['divi_primary_menu_navbar_font'] = array(
          'font-family' => $this->validate_font(et_get_option('primary_nav_font', $font_family)),
          'font-options' => '',
          'google' => false,
          'font-weight' => $font_weight,
          'font-style' => $font_style,
          'subsets' => '',
          'font-size' => et_get_option('primary_nav_font_size', '14'),
          'letter-spacing' => et_get_option('primary_nav_font_spacing', '0'),
      );
      $options['divi_primary_menu_dropdown_font'] = array(
          'font-family' => $font_family,
          'font-options' => '',
          'google' => false,
          'font-weight' => '400',
          'font-style' => '',
          'subsets' => '',
          'font-size' => et_get_option('body_font_size', '14'),
          'letter-spacing' => et_get_option('primary_dropdown_font_spacing', '0'),
      );

      return $options;
    }

    /* function secondary_menu_options($options) {

      $options['secondary-menu_integration'] = et_get_option('secondary-menu_integration', true);
      $options['secondary-menu_unwrap'] = et_get_option('secondary-menu_unwrap', false);
      $options['secondary-menu_theme'] = et_get_option('secondary-menu_theme', 'divi_secondary_menu');
      $options['secondary-menu_information'] = false;
      $options['secondary-menu_manual'] = false;

      return $options;
      } */

    function primary_menu_info($section) {

      $section['fields'][] = array(
          'customizer' => false,
          'id' => $section['id'] . '_information',
          'type' => 'info',
          'title' => esc_html__('Customizer', 'quadmenu'),
          'style' => 'success',
          'desc' => sprintf(__('All menu settings will be moved to the <a href="%s">customizer</a> and they will be available in Header & Navigation > Primary Menu Bar. If you need more information about the integration process you can check our documentation <a href="%s">here</a>.', 'quadmenu'), admin_url('customize.php?et_customizer_option_set=theme'), 'https://quadmenu.com/documentation/integration/divi/?utm_source=quadmenu_admin'),
          'required' => array(
              'primary-menu_theme',
              '=',
              'divi_primary_menu'
          ),
      );

      if (!class_exists('QuadMenu_Divi')) {

        $section['fields'][] = array(
            'customizer' => false,
            'id' => $section['id'] . '_alert',
            'type' => 'info',
            'title' => esc_html__('Alert', 'quadmenu'),
            'style' => 'critical',
            'desc' => sprintf(__('This theme is not officially supported. This means the automatic adjustments will not be applied and you will have to make your own integration. If you need more information about the integration process you can check our documentation <a href="%s">here</a>.', 'quadmenu'), admin_url('customize.php?et_customizer_option_set=theme'), 'https://quadmenu.com/documentation/integration/divi/?utm_source=quadmenu_admin'),
            'required' => array(
                array(
                    'primary-menu_theme',
                    '!=',
                    'divi_primary_menu'
                ),
                array(
                    'primary-menu_theme',
                    '!=',
                    'divi'
                ),
                array(
                    'primary-menu_theme',
                    '!=',
                    ''
                )
            ),
        );
      }

      return $section;
    }

    function template($template, $template_name, $template_path, $default_path, $args) {

        if (et_get_option('header_style') === 'slide') {
          return plugin_dir_path(__FILE__) . '/collapsed.php';
        }

      return $template;
    }

    function themes($themes) {

      $themes['divi_primary_menu'] = 'Customizer';
      //$themes['divi_secondary_menu'] = 'Divi Secondary';

      return $themes;
    }

    public static function validate_font($font_family) {
      if (!$font_family || in_array($font_family, array('none', ''))) {
        return 'inherit';
      }

      return $font_family;
    }

  }

  new QuadMenu_PRO_Divi_Options();
}

