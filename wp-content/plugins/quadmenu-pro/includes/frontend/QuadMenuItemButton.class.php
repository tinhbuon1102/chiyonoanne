<?php

if (!defined('ABSPATH')) {
  die('-1');
}

class QuadMenuItemButton extends QuadMenuItemDefault {

  protected $type = 'button';

  function init() {
    $this->args->has_caret = false;
    //$this->args->has_dropdown = $this->has_children = true;
    $this->args->link_before = $this->args->link_after = false;
  }

}
