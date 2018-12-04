<?php
if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenuItemLogin extends QuadMenuItem {

  protected $type = 'login';
  var $instance;

  function init() {

    global $wp;

    $this->current_user = wp_get_current_user();

    $this->is_user_logged_in = (bool) $this->current_user->ID;

    $this->has_children = (bool) (!$this->is_user_logged_in || $this->has_children);

    $this->args->has_caret = $this->args->has_dropdown = $this->has_children;

    $this->args->has_avatar = (bool) ($this->item->avatar == 'on');

    $this->args->has_link = true;

    $this->item->url = $this->item->target = '';

    $this->item->xfn = 'nofollow';

    if (!$this->is_user_logged_in) {
      $this->item->icon = $this->item->login;
    } else {
      $this->item->title = $this->current_user->{$this->item->name}; //esc_html__('Log Out', 'quadmenu');
      $this->item->icon = $this->item->logout;
    }

    if (!$this->args->has_avatar) {
      $this->args->has_icon = (bool) $this->item->icon;
    }

    if (!$this->has_children && $this->is_user_logged_in) {
      $this->item->url = wp_logout_url(esc_url(home_url(add_query_arg(array(), $wp->request))));
    }

    $this->instance++;
  }

  function get_start_el() {

    $item_output = '';

    $this->add_item_classes();

    $this->add_item_classes_prefix();

    $this->add_item_classes_quadmenu();

    $this->add_item_classes_maxheight();

    $id = $this->get_item_id();

    $class = $this->get_item_classes();

    $item_output .= '<li' . $id . $class . '>';

    $this->add_link_atts();

    $this->add_link_atts_toggle();

    $item_output .= $this->get_link();

    if (!$this->is_user_logged_in) {
      $item_output .= $this->get_form();
    }

    return $item_output;
  }

  function get_form() {

    ob_start();
    ?>    
    <div id="dropdown-<?php echo esc_attr($this->item->ID); ?>" class="<?php echo join(' ', array_map('sanitize_html_class', $this->dropdown_classes)); ?>">
      <?php echo $this->add_dropdown_background(); ?>
      <?php $this->login_form(); ?>
      <?php $this->registration_form(); ?>
    </div>
    <?php
    return ob_get_clean();
  }

  function start_lvl() {

    if ($this->is_user_logged_in) {
      $this->output.= $this->get_dropdown_logged_in_start();
    }
  }

  function end_lvl() {
    if ($this->is_user_logged_in) {
      $this->output.= $this->get_dropdown_logged_in_end();
    }
  }

  function get_dropdown_logged_in_start() {
    ob_start();
    ?>
    <div id="dropdown-<?php echo esc_attr($this->item->ID); ?>" class="<?php echo join(' ', array_map('sanitize_html_class', $this->dropdown_classes)); ?>">
      <?php echo $this->add_dropdown_background(); ?>
      <ul<?php echo $this->get_dropdown_ul_style(); ?><?php echo $this->get_dropdown_ul_classes(); ?><?php echo $this->get_dropdown_ul_data(); ?>>
        <li class="quadmenu-item quadmenu-login-avatar quadmenu-item-type-post_type quadmenu-has-image-thumbnail quadmenu-has-title">                
          <a href="javascript:void(0)">
            <span class="quadmenu-item-content">
              <?php echo get_avatar($this->current_user->user_email, 60); ?>                                                   
              <span class="quadmenu-text"><?php echo ucfirst($this->item->title); ?></span>                                                      
              <span class="quadmenu-subtitle"><?php echo sprintf(esc_html__('Welcome %s', 'quadmenu'), $this->current_user->display_name); ?></span>
            </span>
          </a>
        </li>
        <?php
        return ob_get_clean();
      }

      function get_dropdown_logged_in_end() {

        global $wp;

        ob_start();
        ?>
      </ul>
      <div class="quadmenu-login-buttons">
        <?php if (!empty($this->item->account)) : ?>
          <a href="<?php echo esc_url($this->item->account); ?>" class="button"><?php esc_html_e('My Account', 'quadmenu'); ?></a>
        <?php endif; ?>
        <a href="<?php echo wp_logout_url(esc_url(home_url(add_query_arg(array(), $wp->request)))); ?>" class="button"><?php esc_html_e('Log Out', 'quadmenu'); ?></a>
      </div>
      <?php if (!empty($this->item->login_text)) : ?>
        <div class="quadmenu-bottom-text"><?php echo $this->item->login_text; ?></div>
      <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
  }

  function login_form() {
    ?>
    <form class="quadmenu-login-form" role="form">
      <div class="quadmenu-login-inputs">
        <input type="text" name="quadmenu_username" value="" placeholder="<?php esc_html_e('Username', 'quadmenu'); ?>"/>
        <input type="password" name="quadmenu_pass" value="" placeholder="<?php esc_html_e('Password', 'quadmenu'); ?>"/>                
        <input type="hidden" name="action" value="quadmenu_login_user" />
      </div>
      <div class="quadmenu-login-buttons">
        <input type="submit" class="button button-primary" value="<?php esc_html_e('Login', 'quadmenu') ?>" />
        <?php if ($this->item->register) : ?> 
          <a class="button button-primary" href="<?php echo esc_url($this->item->register); ?>" title="<?php esc_html_e('Register', 'quadmenu') ?>"><?php esc_html_e('Register', 'quadmenu') ?></a>
        <?php else: ?> 
          <a class="button button-primary" href="javascript:void(0)" data-toggle="form" data-target=".quadmenu-registration-form" data-current=".quadmenu-login-form" title="<?php esc_html_e('Register', 'quadmenu') ?>"><?php esc_html_e('Register', 'quadmenu') ?></a>
        <?php endif; ?>
      </div>
      <div class="quadmenu-result-message"></div>
      <?php if ($this->item->password) : ?> 
        <a class="quadmenu-bottom-text" href="<?php echo esc_url($this->item->password); ?>" title="<?php esc_html_e('Lost Password', 'quadmenu') ?>"><?php esc_html_e('Lost Password', 'quadmenu') ?></a>
      <?php else: ?> 
        <a class="quadmenu-bottom-text" href="<?php echo wp_lostpassword_url(get_permalink()); ?>" title="<?php esc_html_e('Lost Password', 'quadmenu') ?>"><?php esc_html_e('Lost Password', 'quadmenu') ?></a>
      <?php endif; ?>
    </form>
    <?php
  }

  function registration_form() {

    if ($this->item->register)
      return;
    ?>
    <form class="hidden quadmenu-registration-form" role="form">
      <div class="quadmenu-login-inputs">
        <input type="text" name="quadmenu_username" value="" placeholder="<?php esc_html_e('Username', 'quadmenu'); ?>"/>
        <small><?php esc_html_e('Use only a-z,A-Z,0-9, dash and underscores.', 'quadmenu'); ?></small>
        <input type="email" name="quadmenu_email" value="" placeholder="<?php esc_html_e('Email', 'quadmenu'); ?>"/>
        <input type="password" name="quadmenu_pass" value="" placeholder="<?php esc_html_e('Password', 'quadmenu'); ?>"/>
        <input type="hidden" name="action" value="quadmenu_register_user" />
      </div>
      <div class="quadmenu-login-buttons">
        <input type="submit" class="button button-primary" value="<?php esc_html_e('Register', 'quadmenu'); ?>" />
        <a class="button button-primary" href="javascript:void(0)" data-toggle="form" data-target=".quadmenu-login-form" data-current=".quadmenu-registration-form"><?php esc_html_e('Login', 'quadmenu') ?></a>
      </div>
      <div class="quadmenu-result-message"></div>
      <?php if ($this->item->password) : ?> 
        <a class="quadmenu-bottom-text" href="<?php echo esc_url($this->item->password); ?>" title="<?php esc_html_e('Lost Password', 'quadmenu') ?>"><?php esc_html_e('Lost Password', 'quadmenu') ?></a>
      <?php else: ?> 
        <a class="quadmenu-bottom-text" href="<?php echo wp_lostpassword_url(get_permalink()); ?>" title="<?php esc_html_e('Lost Password', 'quadmenu') ?>"><?php esc_html_e('Lost Password', 'quadmenu') ?></a>
      <?php endif; ?>
    </form>
    <?php
  }

  function get_thumbnail() {
    ob_start();
    ?>
    <?php if (!empty($this->args->has_avatar)) { ?>

      <span class="quadmenu-avatar">  
        <?php echo get_avatar($this->current_user->user_email, 32); ?>
      </span>
      <?php
    }

    return ob_get_clean();
  }

}
