<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 31/07/2018
 * Time: 16:56
 */

namespace site\cart;


class AddGiftCardToCartDAL
{
    public static function get_gc_by_price($price = 0, $product_id)
    {
        global $wpdb;
        $query = 'SELECT * FROM `' .$wpdb->prefix. 'postmeta` 
        WHERE `meta_key` = "gc_balance" 
        AND `meta_value` = ' .$price. ' 
        AND `post_id` IN (
            SELECT `post_id` 
            FROM `' .$wpdb->prefix. 'postmeta` 
            WHERE `meta_key` = "gc_status" 
                AND `meta_value` = -1 
                AND `post_id` IN (
                    SELECT `post_id` 
                    FROM `wp_postmeta` 
                    WHERE `meta_key` = "gc_product_id" 
                        AND `meta_value` = ' .$product_id. '
                    )
            )';
        $result = $wpdb->get_results($query, ARRAY_A);
        return $result;
    }
}