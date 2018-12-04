<?php
if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenu_Nav_Menu_Tabs extends QuadMenu_Nav_Menu_Tab {

  public function __construct() {

    add_action('quadmenu_modal_panels_tab', array($this, 'modal_panels_tabs'), 10, 4);

    add_action('quadmenu_modal_panels_pane', array($this, 'modal_panels_tabs_content'), 10, 4);
  }

  function modal_panels_tabs($menu_item_depth, $tabs_obj, $menu_id) {

    if (!empty($tabs_obj->quadmenu) && $tabs_obj->quadmenu == 'tabs') {
      ?>
      <li><a class="tabs" href="#setting_tabs_<?php echo esc_attr($tabs_obj->ID); ?>" data-quadmenu="tab" aria-expanded="true"><i class="dashicons dashicons-feedback"></i><span class="title"><?php esc_html_e('Tabs', 'quadmenu'); ?></span></a></li>
      <?php
    }
  }

  function modal_panels_tabs_content($menu_item_depth, $tabs_obj, $menu_id) {
    if (!empty($tabs_obj->quadmenu) && $tabs_obj->quadmenu == 'tabs') {
      ?>
      <div role="tabpanel" class="quadmenu-tab-pane quadmenu-tab-pane-tabs fade" id="setting_tabs_<?php echo esc_attr($tabs_obj->ID); ?>">
        <?php echo $this->tabs($tabs_obj, $menu_id); ?>
      </div>
      <?php
    }
  }

  public function tabs($tabs_obj, $menu_id = 0) {

    $tabs = $this->get_children_nav_menu_items($tabs_obj->ID, $menu_id);

    ob_start();
    ?>
    <ul role="tablist" id="tabs_<?php echo esc_attr($tabs_obj->ID); ?>" class="quadmenu-tabs sortable-area clearfix" data-drop-area="drop-tab" data-sortable-items=".dropdown" data-sortable-handle=".dropdown-toggle" data-menu_item_parent_id="<?php echo esc_attr($tabs_obj->ID); ?>">
      <span class="spinner"></span>
      <li>
        <a class="submit-add-to-quadmenu-tab" data-menu_item_type="custom" data-menu_item_quadmenu="tab" data-menu_item_url="#tab" data-menu_item_title="<?php esc_html_e('Tab %', 'quadmenu'); ?>" data-menu_item_parent=".quadmenu-tabs" data-menu_item_parent_id="<?php echo esc_attr($tabs_obj->ID); ?>">
        </a>
      </li>
      <?php
      if (is_array($tabs) && count($tabs)) :
        foreach ($tabs as $tab):

          $tab_obj = QuadMenu::wp_setup_nav_menu_item($tab['id']);

          if (!isset($tab_obj->quadmenu) || $tab_obj->quadmenu != 'tab') {
            continue;
          }

          echo $this->tab($tab_obj, $menu_id);

        endforeach;
      endif;
      ?>
    </ul>
    <?php
    return ob_get_clean();
  }

}

new QuadMenu_Nav_Menu_Tabs();
