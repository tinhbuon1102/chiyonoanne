<?php

if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenuItemCarousel extends QuadMenuItem {

  protected $type = 'carousel';

  function init() {
    $this->args->has_background = (0 === $this->depth && isset($this->item->background['thumbnail-id']) && is_array(wp_get_attachment_image_src($this->item->background['thumbnail-id'], 'full')));
  }

  function get_start_el() {

    global $quadmenu;

    $item_output = '';

    $this->add_item_classes();

    $this->add_item_classes_prefix();

    $this->add_item_classes_quadmenu();

    $this->add_item_classes_maxheight();

    $this->add_item_dropdown_width();

    $id = $this->get_item_id();

    $class = $this->get_item_classes();

    $item_output .= '<li' . $id . $class . '>';

    $this->add_link_atts();

    $this->add_link_atts_toggle();

    if (empty($quadmenu['styles_owlcarousel'])) {
      $item_output .= sprintf('<a href="%2$s" title="%1$s"><span class="quadmenu-item-content"><span class="quadmenu-text">%1$s</span></span></a>', esc_html__('Activate OWL Carousel', 'quadmenu'), QuadMenu::taburl('0'));
      return $item_output;
    }

    $item_output .= $this->get_link();

    //$this->add_dropdown_background();

    $this->add_carousel_ul_classes();

    return $item_output;
  }

  function get_dropdown_ul_data() {

    return ' data-pagination="' . esc_attr($this->item->pagination) . '" data-dots="' . esc_attr($this->item->dots) . '" data-speed="' . esc_attr($this->item->speed) . '" data-autoplay="' . esc_attr($this->item->autoplay) . '"  data-autoplay_speed="' . esc_attr($this->item->autoplay_speed) . '"';
  }

  function add_item_dropdown_width() {

    if (!empty($this->item->stretch)) {
      $this->dropdown_classes[] = 'quadmenu-dropdown-stretch-' . $this->item->stretch;
    }

    if (empty($this->item->stretch) && !empty($this->item->columns)) {
      $this->dropdown_classes = array_merge($this->dropdown_classes, $this->item->columns);
    }

    if (empty($this->item->stretch) && empty($this->item->columns)) {
      $this->dropdown_classes[] = 'quadmenu-dropdown-stretch-boxed';
    }
  }

  function add_carousel_ul_classes() {
    //$this->dropdown_ul_classes[] = 'quadmenu-carousel-controls-' . esc_attr($this->item->controls);
    $this->dropdown_ul_classes[] = 'owl-carousel';
  }

}
