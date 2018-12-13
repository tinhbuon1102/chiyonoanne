<?php
if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenu_Nav_Menu_Carousel extends QuadMenu_Nav_Menu_Panel {

  public function __construct() {

    add_action('quadmenu_modal_panels_tab', array($this, 'modal_panels_panels'), 10, 4);

    add_action('quadmenu_modal_panels_pane', array($this, 'modal_panels_panels_content'), 10, 4);

    add_action('wp_ajax_quadmenu_setting_panels', array($this, 'ajax_panels'));
  }

  function modal_panels_panels($menu_item_depth, $carousel_obj, $menu_id) {

    if (!empty($carousel_obj->quadmenu) && $carousel_obj->quadmenu == 'carousel') {
      ?> 
      <li><a class="tabs carousel" href="#setting_panels_<?php echo esc_attr($carousel_obj->ID); ?>" data-quadmenu="tab" aria-expanded="true"><i class="dashicons dashicons-screenoptions"></i><span class="title"><?php esc_html_e('Panels', 'quadmenu'); ?></span></a></li>
      <?php
    }
  }

  function modal_panels_panels_content($menu_item_depth, $carousel_obj, $menu_id) {
    if (!empty($carousel_obj->quadmenu) && $carousel_obj->quadmenu == 'carousel') {
      ?>
      <div role="tabpanel" class="quadmenu-tab-pane quadmenu-tab-pane-carousel fade" id="setting_panels_<?php echo esc_attr($carousel_obj->ID); ?>">
        <?php echo $this->carousel($carousel_obj, $menu_id); ?>
      </div>
      <?php
    }
  }

  public function carousel($panels_obj, $menu_id = 0) {

    $panels = $this->get_children_nav_menu_items($menu_id, $panels_obj->ID);

    ob_start();
    ?>
    <div class="clearfix"></div>
    <ul role="panellist" id="carousel_<?php echo esc_attr($panels_obj->ID); ?>" class="quadmenu-tabs sortable-area clearfix" data-drop-area="drop-tab" data-sortable-items=".dropdown" data-sortable-handle=".dropdown-toggle" data-menu_item_parent_id="<?php echo esc_attr($panels_obj->ID); ?>">
      <span class="spinner"></span>
      <li>
        <a class="submit-add-to-quadmenu-panel" data-menu_item_type="custom" data-menu_item_quadmenu="panel" data-menu_item_url="#panel" data-menu_item_title="<?php esc_html_e('Panel %', 'quadmenu'); ?>" data-menu_item_parent=".quadmenu-tabs" data-menu_item_parent_id="<?php echo esc_attr($panels_obj->ID); ?>">
        </a>
      </li>
      <?php
      if (is_array($panels) && count($panels)) :
        foreach ($panels as $panel):

          $panel_obj = QuadMenu::wp_setup_nav_menu_item($panel['id']);

          if (!isset($panel_obj->quadmenu) || $panel_obj->quadmenu != 'panel') {
            continue;
          }

          echo $this->panel($panel_obj, $menu_id);

        endforeach;
      endif;
      ?>
    </ul>
    <?php
    return ob_get_clean();
  }

}

new QuadMenu_Nav_Menu_Carousel();
