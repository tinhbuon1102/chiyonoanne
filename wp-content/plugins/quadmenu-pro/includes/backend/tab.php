<?php
if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenu_Nav_Menu_Tab extends QuadMenu_Nav_Menu_Column {

  var $i = 0;

  public function __construct() {

    add_filter('quadmenu_edit_nav_menu_walker', array($this, 'add_nav_menu_item_tab'), 10, 3);
  }

  function add_nav_menu_item_tab($walker_class_name, $menu_id = null, $menu_obj = null, $menu_items = null) {

    if (!empty($menu_obj->menu_item_parent) && !empty($menu_obj->quadmenu) && $menu_obj->quadmenu === 'tab') {
      return __CLASS__;
    }
    return $walker_class_name;
  }

  public function walk($elements, $max_depth) {

    $output = '';

    foreach ($elements as $e) {

      $output .= $this->tab($e);
    }

    return $output;

    wp_die();
  }

  public function tab($tab_obj, $menu_id = 0) {
    ob_start();
    $open = ($this->i == 0) ? ' open' : '';
    ?>
    <li class="dropdown <?php echo esc_attr($open); ?>" data-menu_item_id="<?php echo esc_attr($tab_obj->ID); ?>">
      <a id="quadmenu-title-<?php echo esc_attr($tab_obj->ID); ?>" data-quadmenu="dropdown" class="quadmenu-title dropdown-toggle" href="javascript:void(0)" aria-haspopup="true" aria-expanded="false"><i class="<?php echo esc_attr($tab_obj->icon); ?>"></i><span><?php echo esc_html($tab_obj->title); ?></span></a>
      <ul class="dropdown-menu quadmenu-tab">
        <div class="inner">
          <div class="action-top clearfix">
            <div class="actions">
              <a class="option edit" title="<?php esc_html_e('Edit', 'quadmenu'); ?>"></a>
              <a class="option remove" title="<?php esc_html_e('Remove', 'quadmenu'); ?>"></a>
              <span class="spinner"></span>
            </div>
          </div>
          <div class="settings">
            <?php //$this->form($tab_obj, 1, array('title', 'subtitle', 'badge', 'icon'));  ?>
            <?php do_action('quadmenu_modal_panels', 1, $tab_obj, $menu_id); ?>            
          </div>
          <ul id="quadmenu-tab-items-<?php echo esc_attr($tab_obj->ID); ?>" class="items add-tab-item" data-menu_item_parent_id="<?php echo esc_attr($tab_obj->ID); ?>">     
            <?php echo $this->columns($tab_obj, $menu_id); ?>
          </ul>
        </div> 
      </ul>
    </li>
    <?php
    $this->i++;
    return ob_get_clean();
  }

}

new QuadMenu_Nav_Menu_Tab();
