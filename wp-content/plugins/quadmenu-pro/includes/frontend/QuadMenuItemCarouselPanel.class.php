<?php

if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenuItemCarouselPanel extends QuadMenuItem {

  protected $type = 'panel';

  function get_end_el() {

    if ($this->depth > 2)
      return;
  }

  function get_start_el() {

    global $quadmenu;

    if (empty($quadmenu['styles_owlcarousel'])) {
      return;
    }

    if ($this->depth > 2)
      return;

    $item_output = '';

    $this->add_item_classes();

    $this->add_item_classes_prefix();

    $this->add_item_classes_quadmenu();

    $id = $this->get_item_id();

    $class = $this->get_item_classes();

    $item_output .= '<li' . $id . $class . '>';

    return $item_output;
  }

  function get_item_classes() {
    return ' class="quadmenu-item-type-panel"';
  }

  function add_item_dropdown_classes() {
    
  }

  function add_item_dropdown_ul_classes() {
    $this->dropdown_ul_classes[] = 'quadmenu-row';
  }

}
