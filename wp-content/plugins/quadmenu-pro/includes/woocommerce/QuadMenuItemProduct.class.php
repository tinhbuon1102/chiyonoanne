<?php
if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenuItemProduct extends QuadMenuItemPostType {

  protected $type = 'product';
  protected $product = false;

  function product() {

    $this->args->has_category = false;

    $this->args->has_price = false;

    $this->args->has_rating = false;

    $this->args->has_add_to_cart = false;

    if (function_exists('wc_get_product')) {
      $this->product = wc_get_product($this->item->object_id);
    }

    if (0 < $this->depth && $this->product) {

      $this->args->has_price = (bool) ($this->item->price === 'on');

      $this->args->has_rating = (bool) ($this->item->rating === 'on');

      $this->args->has_add_to_cart = (bool) ($this->item->add_to_cart === 'on');
    }
  }

  function add_item_classes() {

    $this->item_classes[] = 'woocommerce';

    $this->item_classes[] = 'menu-item-' . $this->item->ID;

    if (is_array($this->item->classes)) {
      $this->item_classes = array_merge($this->item_classes, $this->item->classes);
    }
  }

  function get_link() {

    $this->product();

    ob_start();
    ?>
    <?php echo $this->args->before; ?>
    <a <?php echo $this->get_link_attr(); ?>>
      <span class="quadmenu-item-content">
        <?php echo $this->args->link_before; ?>
        <?php echo $this->get_thumbnail(); ?>
        <?php echo $this->get_caret(); ?>
        <?php echo $this->get_icon(); ?>
        <?php echo $this->get_title(); ?>
        <?php echo $this->get_badge(); ?>
        <span class="quadmenu-product-float">
          <?php echo $this->get_rating(); ?>
          <?php echo $this->get_price(); ?>
        </span>
        <?php echo $this->get_subtitle(); ?>
        <?php echo $this->get_description(); ?>
        <?php echo $this->get_add_to_cart(); ?>

        <?php echo $this->args->link_after; ?>
      </span>
    </a>
    <?php echo $this->args->after; ?>
    <?php
    return ob_get_clean();
  }

  /*
   * 
   * function get_category() {
    if ($this->product && $this->args->has_category) {
    ob_start();
    ?>
    <span class="quadmenu-product-category">
    <?php echo wc_get_product_category_list($this->product->get_id(), ', ', '<span class="posted_in">', '</span>'); ?>
    </span>
    <?php
    return ob_get_clean();
    }
    } */

  function get_price() {

    if ($this->product && $this->args->has_price) {
      ob_start();
      ?>
      <span class="quadmenu-product-price">
        <?php echo $this->product->get_price_html(); ?>
      </span>
      <?php
      return ob_get_clean();
    }
  }

  function get_rating() {
    if ($this->product && $this->args->has_rating && $this->product->get_average_rating()) {
      ob_start();
      ?>
      <span class="quadmenu-product-rating">
        <?php echo wc_get_rating_html($this->product->get_average_rating(), $this->product->get_review_count()); ?>
      </span>
      <?php
      return ob_get_clean();
    }
  }

  function get_add_to_cart() {
    if ($this->product && $this->args->has_add_to_cart) {
      ob_start();
      ?>
      <span class="quadmenu-product-cart"><?php echo $this->get_add_to_cart_button(); ?></span>
      <?php
      return ob_get_clean();
    }
  }

  function add_item_description() {

    if (0 < $this->depth && !$this->args->has_description) {

      $post = get_post($this->item->object_id);

      if (isset($post->post_excerpt)) {
        $this->item->description = wp_trim_words(wpautop($this->clean_item_content($post->post_excerpt ? $post->post_excerpt : $post->post_content)), 10);
        $this->args->has_description = true;
      }
    }
  }

  function get_add_to_cart_button($args = array()) {

    if ($this->product) {
      $defaults = array(
          'quantity' => 1,
          'class' => implode(' ', array_filter(array(
              'button',
              'product_type_' . $this->product->get_type(),
              $this->product->is_purchasable() && $this->product->is_in_stock() ? 'add_to_cart_button' : '',
              $this->product->supports('ajax_add_to_cart') ? 'ajax_add_to_cart' : '',
          ))),
          'attributes' => array(
              'data-product_id' => $this->product->get_id(),
              'data-product_sku' => $this->product->get_sku(),
              'aria-label' => $this->product->add_to_cart_description(),
              'rel' => 'nofollow',
          ),
      );

      $args = apply_filters('woocommerce_loop_add_to_cart_args', wp_parse_args($args, $defaults), $this->product);
      echo apply_filters('woocommerce_loop_add_to_cart_link', sprintf('<button class="%s" %s>%s</button>', esc_attr(isset($args['class']) ? $args['class'] : 'button' ), isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '', esc_html($this->product->add_to_cart_text())), $this->product, $args);

      //echo apply_filters('woocommerce_loop_add_to_cart_link', sprintf('<a href="%s" data-quantity="%s" class="%s" %s>%s</a>', esc_url($this->product->add_to_cart_url()), esc_attr(isset($args['quantity']) ? $args['quantity'] : 1 ), esc_attr(isset($args['class']) ? $args['class'] : 'button' ), isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '', esc_html($this->product->add_to_cart_text())), $this->product, $args);
    }
  }

}
