<?php
include_once GIFTCARD_PATH . 'model/giftcard.php';

class Magenest_Giftcard_Buygiftcard
{

    public function __construct()
    {
        /**
         * Cart manipulation
         */
        add_filter('woocommerce_add_cart_item_data', array($this, 'add_cart_item_data'), 50, 2);
        add_filter('woocommerce_add_cart_item', array(&$this, 'add_cart_item'), 50, 1);
        add_filter('woocommerce_get_cart_item_from_session', array(&$this, 'get_cart_item_from_session'), 50, 2);
        add_filter('woocommerce_get_item_data', array(&$this, 'get_item_data'), 50, 2);
        add_action('woocommerce_add_order_item_meta', array($this, 'order_item_meta'), 50, 2);

        add_action('woocommerce_resume_order', array($this, 'removeGiftcardPaymentFailed'));
        add_action("woocommerce_checkout_update_order_meta", array($this, 'generateGiftcard'), 10);
        add_action('woocommerce_order_status_pending', array($this, 'active_giftcardpro'), 10);
        add_action('woocommerce_order_status_on-hold', array($this, 'active_giftcardpro'), 10);
        add_action('woocommerce_order_status_processing', array($this, 'active_giftcardpro'), 10);
        add_action('woocommerce_order_status_completed', array($this, 'active_giftcardpro'), 10);

        add_action('woocommerce_before_cart_totals', array($this, 'before_add_cart_totals'), 10);
    }

    //woocommerce_before_cart_totals
    public function before_add_cart_totals()
    {

    }

    /**
     * @param $cart_item_meta
     * @param $product_id
     * add custom field to cart data
     *
     * @return mixed
     */
    public function add_cart_item_data($cart_item_meta, $product_id)
    {
        $post = $_REQUEST;
        if (isset($post['giftcard'])) {
            $option = $post['giftcard'];
            foreach ($option as $key => $value) {
                //key can be amount
                switch ($key) {
                    case 'amount' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('Value', GIFTCARD_TEXT_DOMAIN)),
                            'value' => esc_html($value),
                            'price' => $value
                        );
                        break;

                    case 'send_to_name' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('To', GIFTCARD_TEXT_DOMAIN)),
                            'value' => esc_html($value),
                            'price' => 0
                        );
                        break;

                    case 'send_to_email' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('Send To Email', GIFTCARD_TEXT_DOMAIN)),
                            'value' => esc_html($value),
                            'price' => 0
                        );
                        break;

                    case 'message' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('Message', GIFTCARD_TEXT_DOMAIN)),
                            'value' => stripslashes($value),
                            'price' => 0
                        );
                        break;

                    case 'scheduled-send-date' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('Scheduled send', GIFTCARD_TEXT_DOMAIN)),
                            'value' => esc_html($value),
                        );
                        break;

                    case 'email_template' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('Email template ID',GIFTCARD_TEXT_DOMAIN)),
                            'value' => esc_html($value),
                        );
                        break;
                    case 'pdf_template' :
                        $cart_item_meta['giftcard_option'][$key] = array(
                            'name' => esc_html(__('PDF template ID',GIFTCARD_TEXT_DOMAIN)),
                            'value' => esc_html($value),
                        );
                        break;
                }
            }
        }

        return $cart_item_meta;
    }

    /**
     * @param $cart_item
     * @return mixed
     */
    public function add_cart_item($cart_item)
    {
        if (!empty($cart_item['giftcard_option'])) {
            $price = 0;
            foreach ($cart_item['giftcard_option'] as $option) {
                $option['price'] = (float)wc_format_decimal($option['price'], "", true);
                $price += $option['price'];
            }
            if ($price > 0) {
                $cart_item['data']->set_price($price);
            }
        }

        return $cart_item;
    }

    /**
     * @param $cart_item
     * @param $values
     *
     * @return mixed
     */
    public function get_cart_item_from_session($cart_item, $values)
    {
        if (!empty($values['giftcard_option'])) {
            $cart_item['giftcard_option'] = $values['giftcard_option'];
            $cart_item = $this->add_cart_item($cart_item);
        }

        return $cart_item;
    }



    /**
     * Adds meta data to the order.
     */
    public function order_item_meta($item_id, $values)
    {
        if (!empty($values['giftcard_option'])) {
            wc_add_order_item_meta($item_id, 'giftcard_option', $values['giftcard_option']);
        }
    }

    /**
     * change gift card status to active and send mail to the recipient and the giver
     *
     * @param int $order_id
     */
    public function active_giftcardpro($order_id)
    {
        $order = new WC_Order($order_id);
        $items = $order->get_items();
        foreach ($items as $item) {
            $post_id = $item->get_product_id();
            $is_giftcard = get_post_meta($post_id, '_giftcard', true);
            if (isset($is_giftcard) && $is_giftcard == 'yes') {
                $status = $order->get_status();
                $active_status = get_option('magenest_giftcard_active_when');
                if ($status == $active_status) {
                    $giftcard = new \model\Magenest_Giftcard();
                    $giftcard->active_giftcard($order_id);
                }
            }
        }
    }

    public function generateGiftcard($order_id)
    {
        $product_id = 0;
        $qty = 0;
        $giftcard_balance = 0;
        $to_name = '';
        $to_email = '';
        $message = '';
        $scheduled_send_date = '';
        $order = new WC_Order($order_id);
        $items = $order->get_items();
        foreach ($items as $item) {
            $data = $item->get_data();
            $product_id = $data['product_id'];
            $qty = $item->get_quantity();
            $meta_data = $item->get_meta_data();
            $order_itemId = $item->get_id();
            $quantity = wc_get_order_item_meta($order_itemId, '_qty', true);

            $is_giftcard = get_post_meta($product_id, '_giftcard', true);
            if ($is_giftcard == 'no') {
                continue;
            }
//            if ($quantity == 1) {
            foreach ($meta_data as $meta) {
                $gc_data = $meta->get_data();
                $to_email = $gc_data['value']['send_to_email']['value'];
                $to_name = $gc_data['value']['send_to_name']['value'];
                $message = $gc_data['value']['message']['value'];
                $scheduled_send_date = $gc_data['value']['scheduled-send-date']['value'];
                $email_template_id = $gc_data['value']['email_template']['value'];
                $pdf_template_id = $gc_data['value']['pdf_template']['value'];
                $giftcard_balance = $gc_data['value']['amount']['value'];
            }
//            } else {
//                foreach ($meta_data as $meta) {
//                    if ($meta->key == 'Send_To_Email') {
//                        $to_email = $meta->value;
//                    }
//                    if ($meta->key == 'To') {
//                        $to_name = $meta->value;
//                    }
//                    if ($meta->key == 'Message') {
//                        $message = $meta->value;
//                    }
//                    if ($meta->key == 'Scheduled_send_date') {
//                        $scheduled_send_date = $meta->value;
//                    }
//                    if ($meta->key == 'Value') {
//                        $giftcard_balance = $meta->value;
//                    }
//                }
//            }


//            if ($scheduled_send_date) {
//                setlocale(LC_ALL, "en_US");
//                $scheduled_send_date = strftime("%Y-%m-%d");
//                //                 $scheduleDate = new DateTime($scheduled_send_date);
//                //                 $scheduled_send_date = $scheduleDate->format('Y-m-d');
//            }
            //calculate gift card expired date

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
                    $gc = new model\Magenest_Giftcard();
                    $expiry_time = $gc->calculateExpiryDate($expired_at_product_scope);
                    if ($expiry_time) {
                        $expired_at = $expiry_time;
                    }
                }
            } elseif (get_option('magenest_giftcard_timespan')) {
                $giftcard = new model\Magenest_Giftcard();
                $timespan = get_option('magenest_giftcard_timespan');
                $expired_at_website_scope = $giftcard->calculateExpiryDate($timespan);
                if ($expired_at_website_scope) {
                    $expired_at = $expired_at_website_scope;
                }
            }

            $gift_card_data = array(
                'gc_product_id' => $product_id,
                'magenest_giftcard_order_id' => $order_id,
                'gc_product_name' => get_the_title($product_id),
                'gc_user_id' => $order->get_user_id(),
                'gc_balance' => $giftcard_balance,
                'gc_init_balance' => $giftcard_balance,
                'gc_send_from_firstname' => '',
                'gc_send_from_last_name' => '',
                'gc_send_to_name' => $to_name,
                'gc_send_to_email' => $to_email,
                'gc_scheduled_send_time' => $scheduled_send_date,
                'gc_email_template_id' => $email_template_id,
                'gc_pdf_template_id' => $pdf_template_id,
                'gc_is_sent' => 0,
                'gc_send_via' => '',
                'gc_extra_info' => '',
                'gc_code' => '',
                'gc_message' => $message,
                'gc_status' => 0,
                'gc_expired_at' => $expired_at
            );
            $gifcard = new model\Magenest_Giftcard();
            for ($i = 0; $i < $qty; $i++) {
                $gifcard->generateGiftcard($code = '', $gift_card_data, $order_id);
            }
        } // end foreach $items
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
}

return new Magenest_Giftcard_Buygiftcard();
 
