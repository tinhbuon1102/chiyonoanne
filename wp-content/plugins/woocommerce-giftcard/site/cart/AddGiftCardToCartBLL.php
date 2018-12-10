<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 31/07/2018
 * Time: 16:06
 */

namespace site\cart;

if (!defined('ABSPATH')) {
    exit();
}

class AddGiftCardToCartBLL
{
    public function __construct()
    {
        add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'add_giftcart_fields' ) ); // show gift card info form in product detail page
	    add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_to_cart_link' ), 10, 2 ); // replace link add to cart if is gift card product

        add_action('woocommerce_add_to_cart', array($this, 'check_giftcard_before_add_to_cart'), 10, 6); // if create manual gc (via import file) -> check gc in stock

        add_filter('woocommerce_add_cart_item_data', array($this, 'add_cart_item_data'), 50, 2); // add gc info to cart
        add_filter('woocommerce_get_cart_item_from_session', array($this, 'add_cart_item'), 50, 1); // set price item depend price gift card when add to cart
        add_filter('woocommerce_get_item_data',  array($this, 'get_item_data'), 50, 2); // Gets and formats a list of gift card item data + variations for display on the frontend
        add_action('woocommerce_add_order_item_meta', array($this, 'order_item_meta'), 50, 2); // save to order detail
        add_filter('woocommerce_add_to_cart_validation',array($this,'woocommerce_add_to_cart_validation'),10,3);
    }

    public function add_giftcart_fields()
    {
        wp_enqueue_style('datetimepickerstyle');
        wp_enqueue_style('datetimepickerstandlonestyle');
        wp_enqueue_style('boostrap');
        wp_enqueue_style('gc_preview_jquery');
        wp_enqueue_style('gc_front_end');

        wp_enqueue_script("momentjs");
        wp_enqueue_script("datetimepicker");
        wp_enqueue_script('gc-preview-email');
        wp_enqueue_script('gc-preview-pdf');
        wp_localize_script('gc-preview-email', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
        wp_localize_script('gc-preview-pdf', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));

        if ( ! is_single() ) {
            return;
        }
        global $post;
        $is_giftcard = get_post_meta( $post->ID, '_giftcard', true );
        if ( $is_giftcard == 'yes' ) {
            ob_start();
            $template_path = GIFTCARD_PATH . 'template/';
            $default_path  = GIFTCARD_PATH . 'template/';
            wc_get_template( 'add_giftcart_fields.php', array(), $template_path, $default_path );
            echo ob_get_clean();
        }
    }

    public function check_giftcard_before_add_to_cart($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
    {
        global $woocommerce;
        $is_giftcard = get_post_meta($product_id,'_giftcard',true);
        if($is_giftcard == "yes"){
            $create_gc_auto = get_post_meta($product_id, '_giftcard_mode', true); // return manual or auto
            if (empty($create_gc_auto) || $create_gc_auto == 'auto'){
                return;
            }
            $cart = $woocommerce->cart;
            $cart_contents = $cart->get_cart_contents();
            $count = 0;
            $values = $cart_item_data['giftcard_option']['amount']['value'];
            foreach ($cart_contents as $key => $content){
                $value = $content['giftcard_option']['amount']['value'];
                if(($content['product_id'] == $product_id)&&($values == $value)){
                    $count += $content['quantity'];
                }
            }
            $gc_avail = \site\cart\AddGiftCardToCartDAL::get_gc_by_price($cart_item_data['giftcard_option']['amount']['value'], $product_id);
            if (count($gc_avail) < $count){
                throw new \Exception(
                    sprintf('Cannot add Gift Card to cart. The selected value is out of stock.')
                );
            }
        }
    }

    public function woocommerce_add_to_cart_validation($passed_validation, $product_id, $quantity ){
        $is_giftcard = get_post_meta($product_id,'_giftcard',true);
        if ($is_giftcard == "yes") {
            $expiry_model = get_post_meta($product_id,'_giftcard-expiry-model',true);
            $expiry = false;
            if($expiry_model == "expiry-date"){
                $expiry_date = new \DateTime(get_post_meta($product_id,'_giftcard-expiry-date',true));
                $now = new \DateTime();
                $format = 'Y-m-d';
                $expiry_date = $expiry_date->format($format);
                $expirydate = strtotime($expiry_date);
                $now = $now->format($format);
                $now = strtotime($now);
                if($expirydate <= $now){
                    $expiry = true;
                }
            }
            if($expiry){
                wc_add_notice(__('Sorry, this product cannot be purchased.','GIFTCARD'), 'error' );
                $passed_validation = false;
            }
        }
        return $passed_validation;
    }
    /**
     * @param $cart_item_meta
     * @param $product_id
     * add gift card field to cart data
     *
     * @return mixed
     */
    public function add_cart_item_data($cart_item_meta, $product_id)
    {
        $post = $_REQUEST;
        $is_giftcard = get_post_meta($product_id,'_giftcard',true);
        if (isset($post['giftcard']) && $is_giftcard == "yes") {
            $option = $post['giftcard'];
            foreach ($option as $key => $value) {
                //key can be amount
                switch ($key) {
                    case 'amount' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('Value', 'GIFTCARD')),
                            'value' => esc_html($value),
                            'price' => $value
                        );
                        break;

                    case 'send_to_name' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('To', 'GIFTCARD')),
                            'value' => esc_html($value),
                            'price' => 0
                        );
                        break;

                    case 'send_to_email' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('Send To Email', 'GIFTCARD')),
                            'value' => esc_html($value),
                            'price' => 0
                        );
                        break;

                    case 'message' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('Message', 'GIFTCARD')),
                            'value' => stripslashes($value),
                            'price' => 0
                        );
                        break;

                    case 'scheduled-send-date' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('Scheduled send', 'GIFTCARD')),
                            'value' => esc_html($value),
                        );
                        break;

                    case 'email_template' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('Email template ID','GIFTCARD')),
                            'value' => esc_html($value),
                        );
                        break;
                    case 'pdf_template' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('PDF template ID','GIFTCARD')),
                            'value' => esc_html($value),
                        );
                        break;
                }
            }
        }

        return $cart_item_meta;
    }

    /**
     * Set price item depend price gift card when add to cart
     * @param $cart_item
     * @return mixed
     */
    public function add_cart_item($cart_item)
    {
        if (!empty($cart_item['giftcard_option'])) {
            $price = 0;
            foreach ($cart_item['giftcard_option'] as $key => $option) {
                if($key == 'amount'){
                    $option['price'] = (float)wc_format_decimal($option['price'], "", true);
                    $price += $option['price'];
                }
            }
            if ($price > 0) {
                $cart_item['data']->set_price($price);
            }
        }

        return $cart_item;
    }

    /**
     * @param $other_data
     * @param $cart_item
     * Gets and formats a list of gift card item data + variations for display on the frontend
     * @return array
     */
    public function get_item_data($other_data, $cart_item)
    {
        if (!empty($cart_item['giftcard_option'])) {

            if (isset($cart_item['giftcard_option']['amount'])) {
                $other_data[] = array(
                    'name' => __('Value', 'GIFTCARD'),
                    'value' => $cart_item['giftcard_option']['amount']['value']
                );
            }

            if (isset($cart_item['giftcard_option']['send_to_name'])) {
                $other_data[] = array(
                    'name' => __('To Name', 'GIFTCARD'),
                    'value' => $cart_item['giftcard_option']['send_to_name']['value']
                );
            }

            if (isset($cart_item['giftcard_option']['send_to_email'])) {
                $other_data[] = array(
                    'name' => __('To Email', 'GIFTCARD'),
                    'value' => $cart_item['giftcard_option']['send_to_email']['value']
                );
            }

            if (isset($cart_item['giftcard_option']['message'])) {
                $other_data[] = array(
                    'name' => __('Message', 'GIFTCARD'),
                    'value' => $cart_item['giftcard_option']['message']['value']
                );
            }

            if (isset($cart_item['giftcard_option']['scheduled-send-date']) && $cart_item['giftcard_option']['scheduled-send-date']['value'] != '') {
                $other_data[] = array(
                    'name' => __('Scheduled send date', 'GIFTCARD'),
                    'value' => date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($cart_item['giftcard_option']['scheduled-send-date']['value']))
                );
            }


        }

        return $other_data;
    }


    /**
     * Adds giftcard meta data to the order.
     */
    public function order_item_meta($item_id, $values)
    {
        if (!empty($values['giftcard_option'])) {
            wc_add_order_item_meta($item_id, 'giftcard_option', $values['giftcard_option']);
        }
    }

	/**
	 * @param unknown    $add_to_cart_html
	 * @param WC_Product $product
	 * Change Link to Select options if product is gift card
	 * @return unknown|string
	 */
	public function add_to_cart_link( $add_to_cart_html, $product )
	{
		$post_id     = $product->get_id();
		$is_giftcard = get_post_meta( $post_id, '_giftcard', true );
		if ( $is_giftcard != "yes" ) {
			return $add_to_cart_html;
		} else {
			$select_options = sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s">%s</a>',
				esc_url( $product->get_permalink() ),
				esc_attr( $product->get_id() ),//$product->id
				esc_attr( $product->get_sku() ),
				esc_attr( isset( $quantity ) ? $quantity : 1 ),
				$product->is_purchasable() && $product->is_in_stock() ? 'available' : '',
				esc_attr( $product->get_type() ),
				esc_html( __( 'Select Options', 'GIFTCARD' ) )
			);

			return $select_options;
		}
	}
}