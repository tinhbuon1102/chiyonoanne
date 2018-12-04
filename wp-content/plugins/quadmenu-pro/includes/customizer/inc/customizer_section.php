<?php

class QuadMenu_Customizer_Section extends WP_Customize_Section {

  public $type = 'redux';

  public function __construct($manager, $id, $args = array()) {

    $keys = array_keys(get_object_vars($this));

    foreach ($keys as $key) {
      if (isset($args[$key])) {
        $this->$key = $args[$key];
      }
    }

    $this->manager = $manager;

    $this->id = $id;

    if (isset($args['icon'])) {
      $this->icon = $args['icon'];
    }

    if (empty($this->active_callback)) {
      $this->active_callback = array($this, 'active_callback');
    }

    self::$instance_count += 1;

    $this->instance_number = self::$instance_count;

    $this->controls = array();

    if (isset($args['section'])) {
      $this->section = $args['section'];
      $this->description = isset($this->section['desc']) ? $this->section['desc'] : '';
      $this->opt_name = isset($args['opt_name']) ? $args['opt_name'] : '';
    }
  }

  protected function render() {
    global $wp_version;
    $version = explode('-', $wp_version);
    if (version_compare($version[0], '4.3', '<')) {
      $this->render_fallback();
    }
  }

  public function json() {
    $array = wp_array_slice_assoc((array) $this, array('icon', 'id', 'description', 'priority', 'panel', 'type', 'description_hidden'));
    $array['title'] = html_entity_decode($this->title, ENT_QUOTES, get_bloginfo('charset'));
    $array['content'] = $this->get_content();
    $array['active'] = $this->active();
    $array['instanceNumber'] = $this->instance_number;
    $array['icon'] = $this->icon;

    if ($this->panel) {
      $array['customizeAction'] = sprintf('%s &#9656; %s', __('Customizing'), esc_html($this->manager->get_panel($this->panel)->title));
    } else {
      $array['customizeAction'] = __('Customizing');
    }

    return $array;
  }

  protected function render_template() {
    ?>
    <li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }}">
      <h3 class="accordion-section-title" tabindex="0">
        <i class="{{ data.icon }}"></i>
        {{ data.title }}
        <span class="screen-reader-text"><?php _e('Press return or enter to open', 'quadmenu'); ?></span>
      </h3>
      <ul class="accordion-section-content">
        <li class="customize-section-description-container">
          <div class="customize-section-title">
            <button class="customize-section-back" tabindex="-1">
              <span class="screen-reader-text"><?php _e('Back', 'quadmenu'); ?></span>
            </button>
            <h3>
              <span class="customize-action">
                {{{ data.customizeAction }}}
              </span> {{ data.title }}
            </h3>
          </div>
          <# if ( data.description ) { #>
          <p class="description customize-section-description">{{{ data.description }}}</p>
          <# } #>
          <?php
          if (isset($this->opt_name) && isset($this->section)) {
            do_action("redux/page/{$this->opt_name}/section/before", $this->section);
          }
          ?>
        </li>
      </ul>
    </li>
    <?php
  }

  protected function render_fallback() {
    $classes = 'accordion-section control-section control-section-' . $this->type;
    ?>
    <li id="accordion-section-<?php echo esc_attr($this->id); ?>" class="<?php echo esc_attr($classes); ?>">
      <h3 class="accordion-section-title" tabindex="0">
        <?php
        echo wp_kses($this->title, array(
            'em' => array(),
            'i' => array(),
            'strong' => array(),
            'span' => array(
                'class' => array(),
                'style' => array(),
            ),
        ));
        ?>
        <span class="screen-reader-text"><?php esc_attr_e('Press return or enter to expand', 'quadmenu'); ?></span>
      </h3>
      <ul class="accordion-section-content">
        <?php
        if (isset($this->opt_name) && isset($this->section)) {
          do_action("redux/page/{$this->opt_name}/section/before", $this->section);
        }
        ?>
        <?php if (!empty($this->description)) : ?>
          <li class="customize-section-description-container">
            <p class="description customize-section-description legacy"><?php echo $this->description; ?></p>
          </li>
        <?php endif; ?>
      </ul>
    </li>
    <?php
  }

}
