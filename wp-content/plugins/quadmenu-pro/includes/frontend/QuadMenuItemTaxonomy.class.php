<?php

if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenuItemTaxonomy extends QuadMenuItemArchive {

  protected $type = 'taxonomy';
  public $post_type = false;
  public $query_args = array();

  protected function post_type() {

    $taxonomy = get_taxonomy($this->item->object);

    if ($taxonomy) {
      return $taxonomy->object_type;
    }
  }

  protected function parse_query_args() {

    $this->query_args = array(
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
        'posts_per_page' => $this->item->limit,
        'orderby' => $this->item->orderby,
        'order' => $this->item->order,
    );

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
