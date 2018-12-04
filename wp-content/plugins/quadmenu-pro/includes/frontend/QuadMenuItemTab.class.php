<?php

if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenuItemTab extends QuadMenuItem {

  protected $type = 'tab';

  function init() {

    $this->args->has_background = false;

    //$this->args->has_caret = false;

    $this->item->dropdown = $this->item->float = false;
  }

  function get_end_el() {

    if ($this->depth > 2)
      return;
  }

  function get_start_el() {

    if ($this->depth > 2)
      return;

    $item_output = '';

    $this->add_item_classes();

    $this->add_item_classes_prefix();

    $this->add_item_classes_quadmenu();

    $this->add_item_classes_tab();

    $id = $this->get_item_id();

    $class = $this->get_item_classes();

    $item_output .= '<li' . $id . $class . '>';

    $this->add_link_atts();

    $this->add_link_atts_toggle();

    $item_output .= $this->get_link();

    return $item_output;
  }

  function add_item_classes_tab() {
    $this->item_classes[] = 'dropdown-maxheight';
  }

  function add_item_dropdown_ul_classes() {
    $this->dropdown_ul_classes[] = 'quadmenu-row';
  }

}
