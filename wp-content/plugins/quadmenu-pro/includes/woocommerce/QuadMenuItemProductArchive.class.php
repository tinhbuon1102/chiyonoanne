<?php
if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenuItemProductArchive extends QuadMenuItemProduct {

  protected $type = 'product_archive';
  protected $product = false;
  public $query_args = array();

  function init() {

    $this->args->has_description = false;

    $this->args->has_items = false;

    $this->args->has_navigation = false;

    $this->args->has_dots = false;

    $this->args->has_pagination = false;

    if (0 < $this->depth) {

      $this->args->has_description = (bool) ($this->item->excerpt == 'on');

      $this->args->has_navigation = (bool) ($this->item->navigation == 'on');

      $this->args->has_pagination = (bool) ($this->item->pagination == 'on');

      $this->args->has_dots = (bool) ($this->item->dots == 'on');

      $this->args->has_subtitle = (bool) $this->args->has_subtitle;

      $this->args->has_thumbnail = (bool) $this->item->thumb;

      $this->args->has_items = (bool) ($this->item->items > 0);

      if ($this->args->has_thumbnail) {
        $this->args->has_subtitle = false;
      }

      if ($this->args->has_description) {
        $this->args->has_subtitle = false;
      }

      if ($this->args->has_items) {
        $this->args->has_link = false;
      } else {
        $this->args->has_navigation = true;
      }
    }
  }

  function add_item_classes() {

    $this->item_classes[] = 'woocommerce';

    $this->item_classes[] = 'menu-item-' . $this->item->ID;

    if ($this->args->has_navigation) {
      $this->item_classes[] = 'quadmenu-has-navigation';
    }

    if ($this->args->has_pagination) {
      $this->item_classes[] = 'quadmenu-has-pagination';
    }

    if ($this->args->has_dots) {
      $this->item_classes[] = 'quadmenu-has-dots';
    }

    if (is_array($this->item->classes)) {
      $this->item_classes = array_merge($this->item_classes, $this->item->classes);
    }
  }

  protected function parse_query_args() {

    $this->query_args = array(
        'post_type' => 'product',
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
        'posts_per_page' => $this->item->limit,
        'orderby' => $this->item->orderby,
        'order' => $this->item->order,
    );

    if ('sale_products' === $this->item->orderby && function_exists('wc_get_product_ids_on_sale')) {
      $this->query_args['post__in'] = array_merge(array(0), wc_get_product_ids_on_sale());
    }

    if ('featured_products' === $this->item->orderby) {

      $this->query_args['tax_query'][] = array(
          'taxonomy' => 'product_visibility',
          'terms' => 'featured',
          'field' => 'name',
          'operator' => 'IN',
          'include_children' => false,
      );
    }

    if ('best_selling_products' === $this->item->orderby) {
      $this->query_args['meta_key'] = '';
      $this->query_args['order'] = 'DESC';
      $this->query_args['orderby'] = 'meta_value_num';
    }

    return $this->query_args;
  }

  protected function query_products() {
    if ('top_rated_products' === $this->item->orderby && class_exists('WC_Shortcode_Products')) {
      add_filter('posts_clauses', array('WC_Shortcode_Products', 'order_by_rating_post_clauses'));
      query_posts($this->parse_query_args());
      remove_filter('posts_clauses', array('WC_Shortcode_Products', 'order_by_rating_post_clauses'));
    } else {
      query_posts($this->parse_query_args());
    }
  }

  function get_item_data() {

    return ' data-items="' . esc_attr($this->item->items) . '" data-pagination="' . esc_attr($this->item->pagination) . '" data-dots="' . esc_attr($this->item->dots) . '" data-speed="' . esc_attr($this->item->speed) . '" data-autoplay="' . esc_attr($this->item->autoplay) . '"  data-autoplay_speed="' . esc_attr($this->item->autoplay_speed) . '"';
  }

  function get_products() {

    $this->query_products();
    ?>
    <ul class="owl-carousel" <?php echo $this->get_item_data(); ?>>
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <?php $this->get_product(); ?>                        
          <?php
        endwhile;
      endif;
      wp_reset_query();
      ?>
    </ul>
    <?php
  }

  function get_product() {

    $this->item->ID = $this->item->object_id = get_the_ID();

    if (function_exists('wc_get_product')) {
      $this->product = wc_get_product($this->item->object_id);
    }

    if (!$this->product)
      return;

    $this->item_atts['href'] = get_permalink();
    $this->item_atts['title'] = $this->item->title = get_the_title(get_the_ID());
    $this->item_atts['target'] = '';
    $this->item_atts['rel'] = '';

    $this->item_classes = array_diff($this->item_classes, array('woocommerce', 'quadmenu-has-dots', 'quadmenu-has-pagination', 'quadmenu-has-navigation', 'quadmenu-item-object-product_cat', 'quadmenu-has-navigation', 'quadmenu-has-icon', 'quadmenu-has-badge', 'quadmenu-has-subtitle', 'quadmenu-has-background', 'quadmenu-dropdown-left', 'quadmenu-dropdown-right'));
    $this->item_classes[] = 'quadmenu-item-type-post_type';
    $this->item_classes[] = 'quadmenu-item-object-product';
    $this->item_classes[] = 'quadmenu-item-type-panel';
    $this->item_classes[] = 'quadmenu-has-link';

    $this->args->has_icon = false;
    $this->args->has_subtitle = false;
    $this->args->has_badge = false;
    ?>
    <li <?php echo $this->get_item_id(); ?> <?php echo $this->get_item_classes(); ?>>
      <?php echo parent::get_link(); ?>
    </li>
    <?php
  }

  function get_description() {
    if ($this->args->has_description) {

      $post = get_post($this->item->object_id);

      if (!empty($post->post_excerpt)) {
        ob_start();
        ?>
        <span class="quadmenu-description">
          <?php echo wp_trim_words(wpautop($this->clean_item_content($post->post_excerpt ? $post->post_excerpt : $post->post_content)), 10); ?>
        </span>
        <?php
        return ob_get_clean();
      }
    }
  }

  function get_link() {
    ob_start();
    ?>
    <?php echo $this->args->before; ?>
    <?php if ($this->args->has_navigation || $this->depth == 0) : ?>
      <a <?php echo $this->get_link_attr(); ?>>
        <span class="quadmenu-item-content">
          <?php echo $this->args->link_before; ?>
          <?php echo $this->get_icon(); ?>
          <?php echo $this->get_title(); ?>
          <?php echo $this->get_badge(); ?>
          <?php echo $this->get_subtitle(); ?>
          <?php echo $this->args->link_after; ?>
        </span>
      </a>
    <?php endif; ?>
    <?php if ($this->args->has_items) : ?>
      <?php echo $this->get_products(); ?>
    <?php endif; ?>
    <?php echo $this->args->after; ?>
    <?php
    return ob_get_clean();
  }

}
