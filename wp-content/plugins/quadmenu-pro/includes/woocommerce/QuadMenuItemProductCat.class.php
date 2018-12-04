<?php

if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenuItemProductCat extends QuadMenuItemProductArchive {

  protected $type = 'product_cat';
  public $query_args = array();

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

    if ('best_selling_products' === $this->item->orderby) {
      $this->query_args['meta_key'] = '';
      $this->query_args['order'] = 'DESC';
      $this->query_args['orderby'] = 'meta_value_num';
    }

    $term = get_term($this->item->object_id);

    if ($term) {
      $this->query_args['tax_query'][] = array(
          'taxonomy' => $term->taxonomy,
          'terms' => $term->term_id,
          'field' => 'ID',
          'operator' => 'IN',
      );
    }

    return $this->query_args;
  }

}
