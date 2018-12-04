<?php
if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenu_Nav_Menu_Panel extends QuadMenu_Nav_Menu_Column {

  var $i = 0;

  public function __construct() {

    add_filter('quadmenu_edit_nav_menu_walker', array($this, 'add_nav_menu_item_panel'), 10, 3);
  }

  function add_nav_menu_item_panel($walker_class_name, $menu_id = null, $menu_obj = null, $menu_items = null) {

    if (!empty($menu_obj->menu_item_parent) && !empty($menu_obj->quadmenu) && $menu_obj->quadmenu === 'panel') {
      return __CLASS__;
    }
    return $walker_class_name;
  }

  public function walk($elements, $max_depth) {

    $output = '';

    foreach ($elements as $e) {

      $output .= $this->panel($e);
    }

    return $output;

    wp_die();
  }

  public function panel($panel_obj, $menu_id = 0) {
    ob_start();
    $open = ($this->i == 0) ? ' open' : '';
    ?>
    <li class="dropdown <?php echo esc_attr($open); ?>" data-menu_item_id="<?php echo esc_attr($panel_obj->ID); ?>">
      <a id="quadmenu-title-<?php echo esc_attr($panel_obj->ID); ?>" data-quadmenu="dropdown" class="quadmenu-title dropdown-toggle" href="javascript:void(0)" aria-haspopup="true" aria-expanded="false"><i class="<?php echo esc_attr($panel_obj->icon); ?>"></i><span><?php echo esc_html($panel_obj->title); ?></span></a>
      <ul class="dropdown-menu quadmenu-panel">
        <div class="inner">
          <div class="action-top clearfix">
            <div class="actions">
              <a class="option edit" title="<?php esc_html_e('Edit', 'quadmenu'); ?>"></a>
              <a class="option remove" title="<?php esc_html_e('Remove', 'quadmenu'); ?>"></a>
              <span class="spinner"></span>
            </div>
          </div>
          <div class="settings">
            <?php do_action('quadmenu_modal_panels', 1, $panel_obj, $menu_id); ?>            
          </div>
          <ul id="quadmenu-panel-items-<?php echo esc_attr($panel_obj->ID); ?>" class="items add-panel-item" data-menu_item_parent_id="<?php echo esc_attr($panel_obj->ID); ?>">     
            <?php echo $this->columns($panel_obj, $menu_id); ?>
          </ul>
        </div> 
      </ul>
    </li>
    <?php
    $this->i++;
    return ob_get_clean();
  }

}

new QuadMenu_Nav_Menu_Panel();
