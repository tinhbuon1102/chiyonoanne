<?php

if (!defined('ABSPATH')) {
  die('-1');
}

if (!class_exists('QuadMenu_WooCommerce')) {

  class QuadMenu_WooCommerce {

    function __construct() {

      //add_action('admin_init', array($this, 'navmenu'), 40);
      add_filter('quadmenu_item_object_class', array($this, 'item_object_class'), 10, 4);
      add_filter('quadmenu_custom_nav_menu_items', array($this, 'nav_menu_items'));
      add_filter('quadmenu_nav_menu_item_fields', array($this, 'nav_menu_item_fields'), 20, 2);

      if (is_admin())
        return;

      add_action('init', array($this, 'includes'));
    }

    function includes() {
      require_once plugin_dir_path(__FILE__) . 'woocommerce/QuadMenuItemProduct.class.php';
      require_once plugin_dir_path(__FILE__) . 'woocommerce/QuadMenuItemProductArchive.class.php';
      require_once plugin_dir_path(__FILE__) . 'woocommerce/QuadMenuItemProductCat.class.php';
    }

    function navmenu() {

      if (is_quadmenu()) {
        require_once plugin_dir_path(__FILE__) . 'backend/product.php';
        require_once plugin_dir_path(__FILE__) . 'backend/product_cat.php';
        require_once plugin_dir_path(__FILE__) . 'backend/product_archive.php';
      }
    }

    function nav_menu_items($items) {

      $items['product'] = array(
          'label' => esc_html__('Product', 'quadmenu'),
          'title' => esc_html__('Product', 'quadmenu'),
          'panels' => array(
              'general' => array(
                  'title' => esc_html__('General', 'quadmenu'),
                  'icon' => 'dashicons dashicons-admin-settings',
                  'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
              ),
              'icon' => array(
                  'title' => esc_html__('Icon', 'quadmenu'),
                  'icon' => 'dashicons dashicons-art',
                  'settings' => array('icon'),
              ),
              'product' => array(
                  'title' => esc_html__('Product', 'quadmenu'),
                  'icon' => 'dashicons dashicons-cart',
                  'settings' => array('thumb', 'price', 'rating', 'excerpt', 'add_to_cart'),
              ),
          ),
          'parent' => array('main', 'column', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
      );

      $items['product_cat'] = array(
          'label' => esc_html__('Product Category', 'quadmenu'),
          'title' => esc_html__('Product Category', 'quadmenu'),
          'panels' => array(
              'general' => array(
                  'title' => esc_html__('General', 'quadmenu'),
                  'icon' => 'dashicons dashicons-admin-settings',
                  'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
              ),
              'icon' => array(
                  'title' => esc_html__('Icon', 'quadmenu'),
                  'icon' => 'dashicons dashicons-art',
                  'settings' => array('icon'),
              ),
              'query' => array(
                  'title' => esc_html__('Query', 'quadmenu'),
                  'icon' => 'dashicons dashicons-update',
                  'settings' => array('limit', 'orderby', 'order'),
              ),
              'archive_carousel' => array(
                  'title' => esc_html__('Carousel', 'quadmenu'),
                  'icon' => 'dashicons dashicons-image-flip-horizontal',
                  'settings' => array('items', 'speed', 'autoplay', 'autoplay_speed', 'dots', 'pagination', 'navigation'),
              ),
              'content' => array(
                  'title' => esc_html__('Products', 'quadmenu'),
                  'icon' => 'dashicons dashicons-cart',
                  'settings' => array('thumb', 'price', 'rating', 'excerpt', 'add_to_cart'),
              ),
          ),
          'parent' => array('main', 'column', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
      );

      $items['product_archive'] = array(
          'label' => esc_html__('Products', 'quadmenu'),
          'title' => esc_html__('Products', 'quadmenu'),
          'panels' => array(
              'general' => array(
                  'title' => esc_html__('General', 'quadmenu'),
                  'icon' => 'dashicons dashicons-admin-settings',
                  'settings' => array('subtitle', 'badge', 'float', 'hidden', 'dropdown'),
              ),
              'icon' => array(
                  'title' => esc_html__('Icon', 'quadmenu'),
                  'icon' => 'dashicons dashicons-art',
                  'settings' => array('icon'),
              ),
              'query' => array(
                  'title' => esc_html__('Query', 'quadmenu'),
                  'icon' => 'dashicons dashicons-update',
                  'settings' => array('limit', 'orderby', 'order'),
              ),
              'archive_carousel' => array(
                  'title' => esc_html__('Carousel', 'quadmenu'),
                  'icon' => 'dashicons dashicons-image-flip-horizontal',
                  'settings' => array('items', 'speed', 'autoplay', 'autoplay_speed', 'dots', 'pagination', 'navigation'),
              ),
              'content' => array(
                  'title' => esc_html__('Products', 'quadmenu'),
                  'icon' => 'dashicons dashicons-cart',
                  'settings' => array('thumb', 'price', 'rating', 'excerpt', 'add_to_cart'),
              ),
          ),
          'parent' => array('main', 'column', 'custom', 'post_type', 'post_type_archive', 'taxonomy'),
      );

      return $items;
    }

    function nav_menu_item_fields($settings, $menu_obj) {

      $settings['category'] = array(
          'id' => 'quadmenu-settings[category]',
          'db' => 'category',
          'type' => 'checkbox',
          'title' => esc_html__('Category', 'quadmenu'),
          'placeholder' => esc_html__('Show category', 'quadmenu'),
          'default' => 'off',
      );

      $settings['rating'] = array(
          'id' => 'quadmenu-settings[rating]',
          'db' => 'rating',
          'type' => 'checkbox',
          'title' => esc_html__('Rating', 'quadmenu'),
          'placeholder' => esc_html__('Show product rating', 'quadmenu'),
          'default' => 'on',
      );

      $settings['price'] = array(
          'id' => 'quadmenu-settings[price]',
          'db' => 'price',
          'type' => 'checkbox',
          'title' => esc_html__('Price', 'quadmenu'),
          'placeholder' => esc_html__('Show product price', 'quadmenu'),
          'default' => 'on',
      );

      $settings['add_to_cart'] = array(
          'id' => 'quadmenu-settings[add_to_cart]',
          'db' => 'add_to_cart',
          'type' => 'checkbox',
          'title' => esc_html__('Add To Cart', 'quadmenu'),
          'placeholder' => esc_html__('Show add to cart button', 'quadmenu'),
          'default' => 'on',
      );

      if (isset($menu_obj->quadmenu) && in_array($menu_obj->quadmenu, array('product_archive', 'product_cat'))) {
        $settings['orderby']['ops'] = array(
            'date' => esc_html__('Date', 'quadmenu'),
            'featured_products' => esc_html__('Featured products', 'quadmenu'),
            'top_rated_products' => esc_html__('Top rated products', 'quadmenu'),
            'best_selling_products' => esc_html__('Best selling products', 'quadmenu'),
            'sale_products' => esc_html__('Sale products', 'quadmenu'),
            'popularity' => esc_html__('Popularity', 'quadmenu'),
        );
      }

      return $settings;
    }

    function item_object_class($class, $item, $id, $auto_child = '') {

      switch ($item->quadmenu) {

        case 'product';
          $class = 'QuadMenuItemProduct';
          break;

        case 'product_cat':
          $class = 'QuadMenuItemProductCat';
          break;

        case 'product_archive':
          $class = 'QuadMenuItemProductArchive';
          break;
      }

      return $class;
    }

  }

  new QuadMenu_WooCommerce();
}