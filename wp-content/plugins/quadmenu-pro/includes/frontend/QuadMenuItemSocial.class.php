<?php
if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenuItemSocial extends QuadMenuItem {

  protected $type = 'social';

  function init() {
    $this->item->url = '';
    $this->item->title = '';
    $this->args->has_caret = false;
    $this->args->has_submenu = false;
    $this->has_children = false;

    if ($this->item->social == 'toggle') {
      $this->args->has_dropdown = $this->has_children = true;
    }
  }

  function get_start_el() {

    $item_output = '';

    if (method_exists($this, $this->item->social)) {

      $this->add_item_classes();

      $this->add_item_classes_prefix();

      $this->add_item_classes_quadmenu();

      $this->remove_item_classes();

      $this->add_item_classes_social();

      $id = $this->get_item_id();

      $class = $this->get_item_classes();

      $item_output .= '<li' . $id . $class . '>';

      $this->add_link_atts();

      $this->add_link_atts_toggle();

      $item_output .= call_user_func(array($this, $this->item->social));
    }

    return $item_output;
  }

  function add_item_classes_social() {
    $this->item_classes[] = 'quadmenu-social-' . $this->item->social;
  }

  function toggle() {

    $item_output = $this->get_link();
    $item_output .= $this->embed();

    return $item_output;
  }

  function embed() {

    ob_start();
    ?>

    <span class="quadmenu-toggle-container">
      <?php $this->networks(); ?>
    </span>

    <?php
    return ob_get_clean();
  }

  function networks($networks = '') {

    global $quadmenu;

    if (empty($quadmenu['social'])) {
      printf('<a href="%2$s" title="%1$s">%1$s</a>', esc_html__('Add your social networks', 'quadmenu'), QuadMenu::taburl('quadmenu_social'));
      return;
    }

    foreach ($quadmenu['social'] as $social) {
      ?>
      <a href="<?php echo esc_url($social['url']); ?>" target="_blank" title="<?php echo esc_attr($social['title']); ?>">
        <span class="quadmenu-icon <?php echo esc_attr($social['icon']); ?>"></span>
      </a>
      <?php
    }
  }

  function remove_item_classes() {

    if (($key = array_search('quadmenu-has-link', $this->item_classes)) !== false) {
      unset($this->item_classes[$key]);
    }
  }

}
