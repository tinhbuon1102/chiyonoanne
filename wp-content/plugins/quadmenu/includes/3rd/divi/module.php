<?php

if (class_exists('ET_Builder_Module')) {

  class ET_Builder_Module_QuadMenu extends ET_Builder_Module {

    function init() {
      $this->name = esc_html__('QuadMenu', 'quadmenu');
      $this->slug = 'et_pb_quadmenu';
      $this->vb_support = 'on';
      $this->fullwidth = true;

      $this->main_css_element = '%%order_class%%.et_pb_quadmenu';

      $this->settings_modal_toggles = array(
          'general' => array(
              'toggles' => array(
                  'main_content' => esc_html__('Text', 'quadmenu'),
              ),
          ),
          'advanced' => array(
              'toggles' => array(
                  'width' => array(
                      'title' => esc_html__('Width', 'quadmenu'),
                      'priority' => 65,
                  ),
              ),
          ),
      );

      $this->advanced_fields = array(
          //'background' => array(
          //    'background_image' => false,
          //),
          'spacing' => false,
          'filters' => false,
          'animation' => false,
          'text' => false,
          'borders' => array(
              'default' => false,
          ),
          'margin_padding' => array(
              'css' => array(
                  'important' => array('custom_margin'), // needed to overwrite last module margin-bottom styling
              ),
          ),
          'text_shadow' => array(
              // Don't add text-shadow fields since they already are via font-options
              'default' => false,
          ),
          'box_shadow' => array(
              'default' => false,
          ),
          'fonts' => false,
          'button' => false,
      );
    }

    function get_fields() {
      $fields = array(
          'menu_id' => array(
              'label' => esc_html__('Menu', 'quadmenu'),
              'type' => 'select',
              'option_category' => 'basic_option',
              'options' => et_builder_get_nav_menus_options(),
              'description' => sprintf(
                      '<p class="description">%2$s. <a href="%1$s" target="_blank">%3$s</a>.</p>', esc_url(admin_url('nav-menus.php')), esc_html__('Select a menu that should be used in the module', 'quadmenu'), esc_html__('Click here to create new menu', 'quadmenu')
              ),
              'toggle_slug' => 'main_content',
              'computed_affects' => array(
                  '__menu',
              ),
          ),
          'menu_theme' => array(
              'label' => esc_html__('Theme', 'quadmenu'),
              'type' => 'select',
              'option_category' => 'basic_option',
              'options' => array_flip(quadmenu_vc_themes()),
              'description' => sprintf(
                      '<p class="description">%2$s. <a href="%1$s" target="_blank">%3$s</a>.</p>', esc_url(admin_url('admin.php?page=' . QUADMENU_PANEL)), esc_html__('Select a the theme that should be used in the menu', 'quadmenu'), esc_html__('Click here to create new theme', 'quadmenu')
              ),
              'toggle_slug' => 'main_content',
          ),
      );

      return $fields;
    }

    function render($attrs, $content = null, $render_slug) {

      if (!is_admin()) {

        $menu_id = $this->props['menu_id'];

        $menu_theme = $this->props['menu_theme'];

        $menu = quadmenu(array(
            'echo' => false,
            'menu' => $menu_id,
            'theme' => $menu_theme,
        ));

        $output = sprintf('<div class="et_pb_row et_pb_fullwidth_menu clearfix">
					%1$s
				</div>
			', $menu);

        return $output;
      }
    }

  }

  new ET_Builder_Module_QuadMenu;
}
