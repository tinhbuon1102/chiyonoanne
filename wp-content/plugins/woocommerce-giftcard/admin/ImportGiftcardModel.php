<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 26/07/2018
 * Time: 08:21
 */

namespace admin;


class ImportGiftcardModel
{
    public static function create_giftcard( $data )
    {
        if (empty($data['code']) || empty($data['balance']) || !is_numeric($data['balance'])){
            return false;
        }
        $post_id = wp_insert_post(
            array(
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
                'post_author'    => get_current_user_id(),
                'post_title'     => $data['code'],
                'post_status'    => 'publish',
                'post_type'      => 'shop_giftcard'
            )
        );
        if ($post_id == 0){
            return false;
        }

        update_post_meta($post_id, 'gc_product_id', $data['product_id']);
        update_post_meta($post_id, 'gc_product_name', get_the_title($data['product_id']));
        if(isset($data['expired_at'])&&$data['expired_at']!=''){
            update_post_meta($post_id, 'gc_expired_at', $data['expired_at']);
        }
        update_post_meta($post_id, 'gc_status', $data['status']);
        update_post_meta($post_id, 'gc_balance', $data['balance']);
        update_post_meta($post_id, 'gc_code', $data['code']);
        update_post_meta($post_id, 'model', 'admin' );
        return $post_id;
    }

    /**
     * @param $data
     */
    public static function update_product_config($data){
        global $wpdb;
        $product_id = $data['product_id'];

        $is_gc = get_post_meta($product_id, '_giftcard', true);
        if ($is_gc == 'yes'){
            $price_mode = get_post_meta($product_id, '_giftcard-price-model', true);

            if ($price_mode == 'fixed-price'){
				$list_price = get_post_meta($product_id, '_regular_price', true);
				$list_price_array[] = $list_price;
				$list_price_array = array_merge($list_price_array, $data['price']);
            } elseif ($price_mode == 'selected-price') {
	            $list_price = get_post_meta($product_id, '_giftcard-preset-price', true);
	            $list_price_array = explode(';', $list_price);
	            $list_price_array = array_merge($list_price_array, $data['price']);
            } elseif (empty($price_mode) || $price_mode == 'custom-price') {
            	$list_price_array = $data['price'];
            }
            $list_price_unique = array_unique($list_price_array);

        }  else {
	        update_post_meta( $product_id, '_giftcard', 'yes' );
	        update_post_meta( $product_id, '_virtual', 'yes' );

	        $list_price_array = $data['price'];
	        $list_price_unique = array_unique($list_price_array);
        }

	    if (count($list_price_unique) == 1){
		    // fix price
		    update_post_meta($product_id, '_giftcard-price-model', 'fixed-price');
		    update_post_meta($product_id, '_regular_price', $list_price_array[0]);
		    $product_config['price'] = $list_price_unique[0];
	    } else {
		    // select price
		    update_post_meta($product_id, '_giftcard-price-model', 'selected-price');
		    update_post_meta($product_id, '_giftcard-preset-price', implode(';', $list_price_unique));
	    }
	    update_post_meta($product_id, '_regular_price', 0);
        update_post_meta($product_id, '_giftcard_mode', 'manual');
        update_post_meta($product_id, '_giftcard-email_templates', $data['_giftcard-email_templates']);
        update_post_meta($product_id, '_giftcard-pdf_templates', $data['_giftcard-pdf_templates']);
        update_post_meta($product_id, 'pdf_name', $data['pdf_name']);
        $check_stock = get_post_meta($product_id,'_stock_status',true);
        if($check_stock != "instock"){
            $update = "UPDATE `".$wpdb->prefix."postmeta` SET `meta_value`='instock' WHERE `meta_key`='_stock_status' AND `post_id`='".uyền."'";
            $wpdb->query($update);
        }
    }

    public static function check_duplicate_code($code = null){
        global $wpdb;
        $query = 'SELECT * FROM ' .$wpdb->prefix. 'posts WHERE `post_title` = "' .$code. '" AND `post_type` = "shop_giftcard" AND `post_status` = "publish"';
        $result = $wpdb->get_results($query, ARRAY_A);
        return count($result);
    }

    public static function check_balance($balance){
        if($balance == 0 || $balance == "0"){
            return false;
        }
        if($balance*1 <= 0){
            return false;
        }
        return true;
    }
}