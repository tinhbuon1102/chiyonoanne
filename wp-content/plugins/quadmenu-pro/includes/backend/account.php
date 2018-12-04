<?php
if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenu_Nav_Menu_Account extends QuadMenu_Nav_Menu_Column {

  public function __construct() {

    add_action('quadmenu_modal_panels_tab', array($this, 'modal_panels_account'), 10, 4);

    add_action('quadmenu_modal_panels_pane', array($this, 'modal_panels_account_content'), 10, 4);
  }

  function modal_panels_account($menu_item_depth, $account_obj, $menu_id) {

    if (!empty($account_obj->quadmenu) && $account_obj->quadmenu == 'login') {
      ?>
      <li><a class="account" href="#setting_account_<?php echo esc_attr($account_obj->ID); ?>" data-quadmenu="tab" aria-expanded="true"><i class="dashicons dashicons-money"></i><span class="title"><?php esc_html_e('Account', 'quadmenu'); ?></span></a></li>
      <?php
    }
  }

  function modal_panels_account_content($menu_item_depth, $account_obj, $menu_id) {
    if (!empty($account_obj->quadmenu) && $account_obj->quadmenu == 'login') {
      ?>
      <div role="tabpanel" class="quadmenu-tab-pane quadmenu-tab-pane-account fade" id="setting_account_<?php echo esc_attr($account_obj->ID); ?>">
        <?php echo $this->form($account_obj, 0, array('account', 'login_text')); ?>   
        <?php echo $this->account($account_obj, $menu_id); ?>
      </div>         
      <?php
    }
  }

  public function account($menu_obj, $menu_id = 0) {

    $menu_obj->columns = array('col-12');
    ?>
    <div id="columns_<?php echo esc_attr($menu_obj->ID); ?>" class="quadmenu-columns sortable-area row" data-drop-area="drop-column" data-sortable-items=".quadmenu-column" data-sortable-handle=".action-top" data-menu_item_parent_id="<?php echo esc_attr($menu_obj->ID); ?>">
      <?php echo $this->column($menu_obj, $menu_id); ?>
    </div>
    <?php
  }

}

new QuadMenu_Nav_Menu_Account();
