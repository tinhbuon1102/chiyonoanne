<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 31/07/2018
 * Time: 16:36
 */
namespace site\order;

use site\cart\AddGiftCardToCartDAL;

if (!defined('ABSPATH')){
    exit();
}

class GCOrderBLL
{
    public function __construct()
    {
        add_action('woocommerce_checkout_create_order', array($this, 'check_gc_avail'), 10, 2); // check gc available if import giftcard

        add_action("woocommerce_checkout_update_order_meta", array($this, 'gc_process_order'), 10);

        add_action('woocommerce_order_status_changed', array($this, 'active_giftcardpro'), 10, 3);
        add_action('woocommerce_checkout_order_processed', array($this, 'active_giftcardpro_pendingstatus'), 10,3);

        add_action('woocommerce_resume_order', array($this, 'removeGiftcardPaymentFailed'));

    }

	/**
	 * @param $order
	 * @param $data
	 * check gc available if import giftcard
     * if qty in cart > gc avail -> can't add to cart
	 * @throws \Exception
	 */
    public function check_gc_avail($order, $data){
        $items = $order->get_items();
        foreach ($items as $item){
            $product_id = $item->get_product_id();
            $is_giftcard = get_post_meta($product_id, '_giftcard', true);

            if (empty($is_giftcard) || $is_giftcard == 'no') {
                continue;
            }

            $create_gc = get_post_meta($product_id, '_giftcard_mode', true);
            if (empty($create_gc) || $create_gc != 'manual'){
                continue;
            }

            $gc_balance = $item->get_subtotal();
            $qty = $item->get_quantity();
            $price = $gc_balance/$qty;
            $gc_avail = AddGiftCardToCartDAL::get_gc_by_price($price, $product_id);

            if (count($gc_avail) < $qty){
                throw new \Exception(
                    sprintf('Gift Card availble in stock not enough!')
                );
            }

        }

    }

    public function gc_process_order($order_id) {
        global $wpdb;
        $order = new \WC_Order($order_id);
        $items = $order->get_items();
        foreach ($items as $item) {
            $data = $item->get_data();
            $product_id = $data['product_id'];
            $is_giftcard = get_post_meta($product_id, '_giftcard', true);

            if ($is_giftcard == 'no' || $is_giftcard == "") {
                continue;
            }

            // get data for GC
            $qty = $item->get_quantity();
	        $gc_data = wc_get_order_item_meta($item->get_id(), 'giftcard_option', true);

            $gift_card_data = array(
                'gc_product_id' => $product_id,
                'magenest_giftcard_order_id' => $order_id,
                'gc_order_item_id' => $item->get_id(),
                'gc_product_name' => get_the_title($product_id),
                'gc_user_id' => $order->get_user_id(),
                'gc_balance' => $gc_data['amount']['value'],
                'gc_init_balance' => $gc_data['amount']['value'],
                'gc_send_from_firstname' => $order->get_billing_first_name(),
                'gc_send_from_last_name' => $order->get_billing_last_name(),
                'gc_send_to_name' => $gc_data['send_to_name']['value'],
                'gc_send_to_email' => $gc_data['send_to_email']['value'],
                'gc_scheduled_send_time' => $gc_data['scheduled-send-date']['value'],
                'gc_email_template_id' => $gc_data['email_template']['value'],
                'gc_pdf_template_id' => $gc_data['pdf_template']['value'],
                'gc_is_sent' => 0,
                'gc_send_via' => '',
                'gc_extra_info' => '',
                'gc_code' => '',
                'gc_message' => $gc_data['message']['value'],
                'gc_status' => 0
            );

	        $gifcard = new \model\Magenest_Giftcard();
            $create_gc = get_post_meta($product_id, '_giftcard_mode', true);
            if ($create_gc == 'manual'){
	            $gc_avails = AddGiftCardToCartDAL::get_gc_by_price($gift_card_data['gc_balance'], $product_id);

	            for ($num_gc = 0; $num_gc < $qty; $num_gc++){
		            $gc_avail = $gc_avails[$num_gc];
                    $gift_card_data['model'] = 'admin';
		            $gift_card_data['gc_status'] = 0;
		            unset($gift_card_data['gc_code']);
		            $gifcard->updateGiftcard($gc_avail['post_id'], $gift_card_data,$product_id);
	            }

                $sql = "SELECT `postmeta`.`post_id` as `giftcard_code_id` 
                        FROM `".$wpdb->prefix."postmeta` as `postmeta`
                        JOIN `".$wpdb->prefix."posts` as `posts` ON `postmeta`.`post_id` = `posts`.`ID`
                        WHERE `postmeta`.`meta_key` = 'gc_product_id' 
                        AND `postmeta`.`meta_value` = ".$product_id." 
                        AND `posts`.`post_status` = 'publish'";
                $results = $wpdb->get_results($sql, ARRAY_A);
                $giftcard_stock = false;
                if(!empty($results)){
                    foreach ($results as $result){
                        $query = "SELECT * FROM `".$wpdb->prefix."postmeta` WHERE `post_id` = ".$result['giftcard_code_id'];
                        $giftcardcode = $wpdb->get_results($query,ARRAY_A);
                        foreach ($giftcardcode as $giftcard){
                            if($giftcard['meta_key'] == 'gc_status' && ($giftcard['meta_value'] == '-1' || $giftcard['meta_value'] == -1) ){
                                $giftcard_stock = true;
                            }
                        }
                    }
                }
                if(!$giftcard_stock){
                    $update = "UPDATE `".$wpdb->prefix."postmeta` SET `meta_value`='outofstock' WHERE `meta_key`='_stock_status' AND `post_id`='".$product_id."'";
                    $wpdb->query($update);
                }
            } else {

                // if auto create GC -> caculate expiry date
                $expired_at = '';
                $expiry_mode = get_post_meta($product_id, '_giftcard-expiry-model', true);
                if (!empty($expiry_mode)) {
                    if ($expiry_mode == 'expiry-date') {
                        $expired_at_product_scope = get_post_meta($product_id, '_giftcard-expiry-date', true);
                        if ($expired_at_product_scope) {
                            $expired_at = $expired_at_product_scope;

                        }
                    } elseif ($expiry_mode == 'expiry-time') {
                        $expired_at_product_scope = get_post_meta($product_id, '_giftcard-expiry-time', true);
                        $gc = new \model\Magenest_Giftcard();
                        $expiry_time = $gc->calculateExpiryDate($expired_at_product_scope);
                        if ($expiry_time) {
                            $expired_at = $expiry_time;
                        }
                    }
                } elseif (get_option('magenest_giftcard_timespan')) {
                    $giftcard = new \model\Magenest_Giftcard();
                    $timespan = get_option('magenest_giftcard_timespan');
                    $expired_at_website_scope = $giftcard->calculateExpiryDate($timespan);
                    if ($expired_at_website_scope) {
                        $expired_at = $expired_at_website_scope;
                    }
                }
                $gift_card_data['model'] = 'buyer';
                $gift_card_data['gc_expired_at'] = $expired_at;

                for ($i = 0; $i < $qty; $i++) {
                    $gifcard->generateGiftcard($code = '', $gift_card_data, $order_id);
                }
            }
        }
    }


    /**
     * If there is an order pending payment, we can resume it here so
     * long as it has not changed. If the order has changed, i.e.
     * different items or cost, create a new order. We use a hash to
     * detect changes which is based on cart items + order total.
     */
    public function removeGiftcardPaymentFailed($order_id)
    {
        global $wpdb;
        $sql = 'SELECT * FROM ' . $wpdb->prefix . 'postmeta' . ' WHERE meta_key ="magenest_giftcard_order_id" AND meta_value=' . $order_id;
        $results = $wpdb->get_results($sql, ARRAY_A);
        if (!empty($results)) {
            foreach ($results as $row) {
                wp_delete_post($row['post_id'], true);
            }
        }
    }


    /**
     * change gift card status to active and send mail to the recipient and the giver
     *
     * @param int $order_id
     */
    public function active_giftcardpro($order_id, $status_transition_from, $status_transition_to)
    {
        global $wpdb;
        $order = new \WC_Order($order_id);
        $active_status = get_option('magenest_giftcard_active_when');
        if ($status_transition_to == $active_status) {

            $items = $order->get_items();
            foreach ($items as $item) {
                $post_id = $item->get_product_id();
                $is_giftcard = get_post_meta($post_id, '_giftcard', true);
                if (isset($is_giftcard) && $is_giftcard == 'yes') {
                    $giftcard = new \model\Magenest_Giftcard();
                    $giftcard->active_giftcard($order_id, $item->get_id());
                }
            }
        }
    }
    public function active_giftcardpro_pendingstatus($order_id, $posted_data, $order){
        global $wpdb;
        $order = new \WC_Order($order_id);
        $active_status = get_option('magenest_giftcard_active_when');
        $status_order_current = $order->get_status();
        if($status_order_current == $active_status){
            $items = $order->get_items();
            foreach ($items as $item) {
                $post_id = $item->get_product_id();
                $is_giftcard = get_post_meta($post_id, '_giftcard', true);
                if (isset($is_giftcard) && $is_giftcard == 'yes') {
                    $giftcard = new \model\Magenest_Giftcard();
                    $giftcard->active_giftcard($order_id, $item->get_id());
                }
            }
        }
    }

}