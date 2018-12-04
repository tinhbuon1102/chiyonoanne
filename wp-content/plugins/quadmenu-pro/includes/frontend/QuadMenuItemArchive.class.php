<?php
if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenuItemArchive extends QuadMenuItemPostType {

  protected $type = 'post_type_archive';
  public $post_type = false;
  public $query_args = array();

  function init() {

    $this->post = false;

    $this->args->has_description = false;

    $this->args->has_items = false;

    $this->args->has_navigation = false;

    $this->args->has_pagination = false;

    $this->args->has_dots = false;

    if (0 < $this->depth) {

      $this->post_type = $this->post_type();

      if ($this->post_type) {

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
  }

  function add_item_classes() {

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

  protected function post_type() {
    return get_post_type_object($this->item->object);
  }

  protected function parse_query_args() {

    $this->query_args = array(
        'post_type' => $this->post_type->name,
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
        'posts_per_page' => $this->item->limit,
        'orderby' => $this->item->orderby,
        'order' => $this->item->order,
    );

    return $this->query_args;
  }

  protected function query_posts() {
    query_posts($this->parse_query_args());
  }

  function get_item_data() {

    return ' data-items="' . esc_attr($this->item->items) . '" data-pagination="' . esc_attr($this->item->pagination) . '" data-dots="' . esc_attr($this->item->dots) . '" data-speed="' . esc_attr($this->item->speed) . '" data-autoplay="' . esc_attr($this->item->autoplay) . '"  data-autoplay_speed="' . esc_attr($this->item->autoplay_speed) . '"';
  }

  function get_posts() {

    $this->query_posts();
    ?>
    <ul class="owl-carousel" <?php echo $this->get_item_data(); ?>>
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
          <?php $this->get_post(); ?>                        
          <?php
        endwhile;
      endif;
      wp_reset_query();
      ?>
    </ul>
    <?php
  }

  function get_post() {

    $this->item->ID = $this->item->object_id = get_the_ID();

    $this->post = get_post($this->item->object_id);

    if (!$this->post)
      return;

    $this->item_atts['href'] = get_permalink();
    $this->item_atts['title'] = $this->item->title = get_the_title(get_the_ID());
    $this->item_atts['target'] = '';
    $this->item_atts['rel'] = '';

    $this->item_classes = array_diff($this->item_classes, array('quadmenu-item-type-post_type_archive', 'quadmenu-has-dots', 'quadmenu-has-pagination', 'quadmenu-has-navigation', 'quadmenu-has-icon', 'quadmenu-has-badge', 'quadmenu-has-subtitle', 'quadmenu-has-background', 'quadmenu-dropdown-left', 'quadmenu-dropdown-right'));
    $this->item_classes[] = 'quadmenu-item-type-post_type';
    //$this->item_classes[] = 'quadmenu-item-type-panel';
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

      $description = $post->post_excerpt ? $post->post_excerpt : $post->post_content;

      if (!empty($description)) {
        ob_start();
        ?>
        <span class="quadmenu-description">
          <?php echo wp_trim_words(wpautop($this->clean_item_content($description)), 10); ?>
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
      <?php echo $this->get_posts(); ?>
    <?php endif; ?>
    <?php echo $this->args->after; ?>
    <?php
    return ob_get_clean();
  }

}
