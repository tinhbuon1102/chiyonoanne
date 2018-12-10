<?php

class Magenest_Giftcard_Form_Handler
{

    public function __construct()
    {
        add_action('wp_loaded', array($this, 'apply_giftcard'));
        add_action('woocommerce_calculate_totals', array($this, 'gc'), 10, 1);
        add_action('woocommerce_cart_totals_before_order_total', array($this, 'show_giftcard_discount_in_cart'));
        add_action('woocommerce_review_order_before_order_total', array($this, 'show_giftcard_discount_in_cart'));
        add_action('woocommerce_checkout_order_processed', array($this, 'after_checkout'), 99);
        add_action('woocommerce_order_status_refunded', array($this,'refund_order'));
        add_filter('woocommerce_calculated_total', array($this, 'applydiscount'), 10, 1);
        add_filter('woocommerce_get_order_item_totals', array($this, 'show_giftcard_discount_on_order'), 10, 2);
        add_filter('woocommerce_update_cart_validation',array($this,'checkGiftCardInCart'),10,4);
        //woocommerce_review_order_before_order_total
        //add_action('woocommerce_get_discounted_price',array($this, 'calculate_giftcard_discount'),10,3);
    }

    public function checkGiftCardInCart($passed_validation,$cart_item_key, $values, $quantity){
        global $woocommerce, $wpdb;
        $product_id = $values['product_id'];
        $is_giftcard = get_post_meta($product_id, '_giftcard', true);
        $_product = $values['data'];
        if($is_giftcard == "yes"){
            $giftcard_option = $values['giftcard_option'];
            $giftcard_amount = $giftcard_option['amount']['value'];
            $giftcardCodeMode = get_post_meta($product_id,'_giftcard_mode',true);
            if($giftcardCodeMode == "manual"){
                $sql = "SELECT `post_id` as `giftcard_code_id` FROM `".$wpdb->prefix."postmeta` WHERE `meta_key` = 'gc_product_id' AND `meta_value` = ".$product_id;
                $results = $wpdb->get_results($sql, ARRAY_A);
                if(!empty($results)){
                    $giftcard_value = [];
                    foreach ($results as $result){
                        $giftcard_code_id[] = $result['giftcard_code_id'];
                        $query = "SELECT * FROM `".$wpdb->prefix."postmeta` WHERE `post_id` = ".$result['giftcard_code_id'];
                        $giftcardcode = $wpdb->get_results($query,ARRAY_A);
                        $value = get_post_meta($result['giftcard_code_id'],'gc_balance', true);
                        foreach ($giftcardcode as $giftcard){
                            if($giftcard['meta_key'] == 'gc_status' && ($giftcard['meta_value'] == '-1' || $giftcard['meta_value'] == -1) && $value !="" && $giftcard_amount == $value){
                                $giftcard_value[] = $value;
                            }
                        }
                    }
                    if(count($giftcard_value) < $quantity){
                        wc_add_notice( sprintf( __( 'You can only add %s %s in your cart.', 'GIFTCARD' ),count($giftcard_value), $_product->get_name() ), 'error' );
                        $passed_validation = false;
                    }
                }
            }
        }
        return $passed_validation;
    }
    public function refund_order($order_id)
    {
        global $woocommerce, $wpdb;

        $sql = "SELECT `post_id` FROM `".$wpdb->prefix."postmeta` WHERE `meta_key` = 'magenest_giftcard_order_id' AND `meta_value` = '".$order_id."' ";
        $results = $wpdb->get_results($sql,ARRAY_A);
        foreach ($results as $key => $value){
            $giftcard_code = get_post_meta($value['post_id'],'gc_code', true);
            if($giftcard_code && $giftcard_code != ""){
                update_post_meta($value['post_id'],'gc_status', '2');
                $giftcard_balance = get_post_meta($value['post_id'],'gc_balance', true);
                $giftcard = new model\Magenest_Giftcard();
                $data = array(
                    'giftcard_id' => $value['post_id'],
                    'giftcard_code' => $giftcard_code,
                    'balance' => $giftcard_balance,
                    'change_balanced' => '',
                    'order_id' => $order_id,
                    'log' => "Refunded order"
                );
                $giftcard->InsertRedeemLog($data);
            }
        }
    }

    public function show_giftcard_discount_on_order($total_rows, $order)
    {
        global $woocommerce;

        $return = array();

        $order_id = $order->get_id();

        $gift_discount = get_post_meta($order_id, 'giftcard_discount', true);
        $gift_code = get_post_meta($order_id, 'giftcard_code', true);

        if ($gift_discount && $gift_discount != '') {
            $newRow['giftcard'] = array(
                'label' => __('Gift Card Payment', 'GIFTCARD') . '(' . $gift_code . ')',
                'value' => wc_price(-1 * $gift_discount)
            );

            array_splice($total_rows, 1, 0, $newRow);
        }

        return $total_rows;
    }

    public function after_checkout($order_id)
    {
        global $woocommerce, $wpdb;
        if (isset($woocommerce->session->giftcard_discount)) {
            update_post_meta($order_id, 'giftcard_discount', $woocommerce->session->giftcard_discount);
            update_post_meta($order_id, 'giftcard_code', $woocommerce->session->giftcard_code);
	        // reduce gift card balance
            $giftcard = new model\Magenest_Giftcard();
            $giftcard->add_balance(-$woocommerce->session->giftcard_discount, $woocommerce->session->giftcard_code);

	        //log
            $order =  new WC_Order($order_id);
            $log = '';
            foreach ( $order->get_items () as $item ) {
                $product = $item->get_data();
                $product_name = $product['name'];
                $product_price = $product['total'];
                $log .= $product_name.': ' .$product_price;
            }
            $balance = (float)($woocommerce->session->giftcard_balance - $woocommerce->session->giftcard_discount);
			$data = array(
			    'giftcard_id' => $woocommerce->session->giftcard_id,
			    'giftcard_code' => $woocommerce->session->giftcard_code,
			    'balance' => $balance,
			    'change_balanced' => $woocommerce->session->giftcard_discount,
			    'order_id' => $order_id,
                'log' => $log
			);

			$giftcard->InsertRedeemLog($data);

            $woocommerce->session->__unset('giftcard_discount');
            $woocommerce->session->__unset('giftcard_code');
        }
    }

    public function show_giftcard_discount_in_cart()
    {
        global $woocommerce, $wpdb;
        if (isset($woocommerce->session->giftcard_discount) && $woocommerce->session->giftcard_discount > 0) {
            $http_schema = 'http://';
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
                $http_schema = 'https://';
            }
            $request_link = $http_schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] . $_SERVER["QUERY_STRING"];
            if (strpos($request_link, '?') > 0) {
                $request_link .= '&removegiftcardcode=true';
            } else {
                $request_link .= '?removegiftcardcode=true';
            }
            $request_link = $request_link . "&giftcardcode=" . $woocommerce->session->giftcard_code;
            ?>
            <tr class="order-discount giftcard-discount">
                <th><?php echo __('Gift card',GIFTCARD_TEXT_DOMAIN) ?> : <?php echo $woocommerce->session->giftcard_code ?></th>
                <td>
                    <form method="POST" id="giftcard-remove-apply-form">
                        <input type="hidden" name="action" value="removegiftcardcode"/>
                        <input type="hidden" id="magenest_giftcardcode" value="<?= $woocommerce->session->giftcard_code; ?>"/>
                        -<?php echo get_woocommerce_currency_symbol() . '' . $woocommerce->session->giftcard_discount ?>
                        <a href="#" onclick="removeapplygiftcard()"><?php echo __('Remove', 'GIFTCARD'); ?></a>
                    </form>
                </td>
            </tr>
            <?php
        }
    }

    public function getGiftcardProductInCart()
    {
        global $woocommerce;
        if ($woocommerce->cart->cart_contents) {
            foreach ($woocommerce->cart->cart_contents as $key => $cart_item) {
                $product_id = $cart_item['product_id'];
                $is_giftcard = get_post_meta($product_id, '_giftcard', true);
                if ($is_giftcard == 'yes')
                    return true;
            }
        }
        return false;
    }

    function applydiscount($total)
    {
        $giftcard_code = WC()->session->giftcard_code;
        if (isset($giftcard_code)) {
            $total -= WC()->session->discount_cart;
        }
        return $total;
    }

    public function gc($cart)
    {
        global $woocommerce, $wpdb;
        $is_giftcardproduct_incart = $this->getGiftcardProductInCart();
        if (!$is_giftcardproduct_incart || get_option('magenest_giftcard_buy_other_giftcard') == 'yes') {
            $giftCardCode = $woocommerce->session->giftcard_code;
            if (empty($giftCardCode)) {
                return $cart;
            }
            $giftcard = new model\Magenest_Giftcard($giftCardCode);
            $balance = $giftcard->balance;
            $charge_shipping = get_option('giftcard_apply_for_shipping');
            $charge_tax = get_option('magenest_enable_giftcard_charge_tax');
            $charge_fee = get_option('magenest_enable_giftcard_charge_fee');
            ////////////////////
            $giftcardPayment = 0;
            foreach ($cart->cart_contents as $key => $product) {
                if (isset($product['line_total'])) $giftcardPayment += $product['line_total'];
            }
//            $giftcardPayment += $cart->get_total();

            if ($charge_shipping == 'yes') {
                $giftcardPayment += $cart->get_shipping_total();
            }
            if ($charge_tax == "yes")
                $giftcardPayment += $cart->get_total_tax();
            if ($charge_fee == "yes")
                $giftcardPayment += $cart->get_fee_total();
            if ($giftcardPayment <= $balance) {
                $woocommerce->session->giftcard_discount = $giftcardPayment;
                $woocommerce->session->discount_cart = $giftcardPayment;
            } else {
                $woocommerce->session->giftcard_discount = $balance;
                $woocommerce->session->discount_cart = $balance;
            }
            $woocommerce->session->giftcard_id = $giftcard->id;
            $woocommerce->session->giftcard_balance = $giftcard->balance;
            ///////////////////
        } else {
            if (get_option('magenest_giftcard_buy_other_giftcard') != 'yes' && isset($woocommerce->session->giftcard_discount)) {
                if (isset($woocommerce->session->giftcard_discount)) unset($woocommerce->session->giftcard_discount);
                if (isset($woocommerce->session->giftcard_code)) unset($woocommerce->session->giftcard_code);
                wc_add_notice(__('A gift card can not be used to buy other gift card', 'GIFTCARD'), 'error');

            }
        }
        return $cart;

    }

    public function apply_giftcard()
    {
        global $woocommerce, $wpdb;

        if(!isset($_POST['product_id'])) return;
        $product = $_POST['product_id'];
        $productId = explode(' ', $product);
        if (!empty($_POST['giftcard_code'])) {

            $flag = true;
            $giftCardCode = sanitize_text_field($_POST['giftcard_code']);

            //get gift card if it is available check balance , status , expiry date
            $giftcard = new model\Magenest_Giftcard($giftCardCode);
            $woocommerce->session->giftcard_code = $giftCardCode;
            if ($giftcard->is_valid($giftCardCode,$productId)) {
                // check apply for other gift card
                foreach ($productId as $product_id) {
                    $is_giftcard = get_post_meta($product_id, '_giftcard', true);
                    if ($is_giftcard == 'yes') {
                        $check = get_option('magenest_giftcard_buy_other_giftcard');
                        if ($check == 'no') {
                            $flag = false;
                            break;
                        } else {
                            $flag = true;
                        }
                    } else {
                        $flag = true;
                    }
                }
                if ($flag) {
                    wc_add_notice(__('Gift card applied successfully.', 'GIFTCARD'), 'success');
                } else {
                    if (isset($woocommerce->session->giftcard_discount)) unset($woocommerce->session->giftcard_discount);
                    if (isset($woocommerce->session->giftcard_code)) unset($woocommerce->session->giftcard_code);
                    wc_add_notice(__('A gift card can not be used to buy other gift card', 'GIFTCARD'), 'error');
                }
            } else {
                if (isset($woocommerce->session->giftcard_discount)) unset($woocommerce->session->giftcard_discount);
                if (isset($woocommerce->session->giftcard_code)) unset($woocommerce->session->giftcard_code);
                if($giftcard->error_message){
                    wc_add_notice(__($giftcard->error_message), 'error');
                }else{
                    wc_add_notice(__('Gift card is not valid.', GIFTCARD_TEXT_DOMAIN), 'error');
                }
            }
        }
    }
}

return new Magenest_Giftcard_Form_Handler();