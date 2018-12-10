<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 03/08/2018
 * Time: 15:22
 */

namespace site;


class RedeemGC
{
    public function __construct()
    {
        add_action( 'woocommerce_cart_actions', array( $this, 'show_apply_giftcart_form' ) ); // show form to redeem gift card

        add_filter( 'woocommerce_get_price_html', array( $this, 'show_giftcard_price' ), 10, 2 ); // show gift card price instead of product price

    }

    public function show_apply_giftcart_form()
    {
        global $post;
        ob_start();
        $id            = 0;
        $template_path = GIFTCARD_PATH . 'template/';
        $default_path  = GIFTCARD_PATH . 'template/';
        wc_get_template( 'add_giftcart_form.php', array( 'id' => $id, ), $template_path, $default_path );
        echo ob_get_clean();
    }

    public function show_giftcard_price( $price, $product )
    {
        global $wpdb;
        //if (!is_single())  return $price;
        $post_id     = $product->get_id();
        $is_giftcard = get_post_meta( $post_id, '_giftcard', true );
        if ( $is_giftcard ) {
            $price_model = get_post_meta( $post_id, '_giftcard-price-model', true );
            switch ( $price_model ) {
                case 'fixed-price':
                {
                    return $price;
                    break;
                }
                case 'selected-price':
                {
                    $presets         = get_post_meta( $post_id, '_giftcard-preset-price', true );
                    $preset          = explode( ';', $presets );
                    $currency_symbol = get_woocommerce_currency_symbol();
                    $giftcard_code_mode = get_post_meta($post_id,'_giftcard_mode', true);
                    if($giftcard_code_mode == 'manual'){
                        $sql = "SELECT `post_id` as `giftcard_code_id` FROM `".$wpdb->prefix."postmeta` WHERE `meta_key` = 'gc_product_id' AND `meta_value` = ".$post_id;
                        $results = $wpdb->get_results($sql, ARRAY_A);
                        if(!empty($results)){
                            $giftcard_value = [];
                            foreach ($results as $result){
                                $giftcard_code_id[] = $result['giftcard_code_id'];
                                $query = "SELECT * FROM `".$wpdb->prefix."postmeta` WHERE `post_id` = ".$result['giftcard_code_id'];
                                $giftcardcode = $wpdb->get_results($query,ARRAY_A);
                                $value = get_post_meta($result['giftcard_code_id'],'gc_balance', true);
                                foreach ($giftcardcode as $giftcard){
                                    if($giftcard['meta_key'] == 'gc_status' && ($giftcard['meta_value'] == '-1' || $giftcard['meta_value'] == -1) && !in_array($value,$giftcard_value) && $value != ""){
                                        $giftcard_value[] = $value;
                                    }
                                }
                            }
                            sort($giftcard_value);
                            $preset = $giftcard_value;
                        }
                    }
                    if(!empty($preset)&&$presets != ""){
                        $count           = count( $preset );
                        sort($preset);
                        $from = $preset[0] != "" ? $preset[0] : $preset[1];
                        $to = $preset[ $count - 1 ];
                        $html            = __( 'From', 'GIFTCARD' ) . ' ' . $currency_symbol . $from . ' to ' . $currency_symbol . $to;
                        if($giftcard_code_mode == 'manual'){
                            $update = "UPDATE `".$wpdb->prefix."postmeta` SET `meta_value`='instock' WHERE `meta_key`='_stock_status' AND `post_id`='".$post_id."'";
                            $wpdb->query($update);
                        }
                        return $html;
                    }
                    return '';
                    break;
                }
                case 'custom-price' :
                {
                    $currency_symbol = get_woocommerce_currency_symbol();
                    $price_range     = get_post_meta( $post_id, '_giftcard-price-range', true );
                    $prices          = explode( '-', $price_range );
                    $html            = __( 'Enter an amount between ', 'GIFTCARD') . $currency_symbol . ' ' . $prices[0] . __( ' and ', 'GIFTCARD' ) . $currency_symbol . ' ' . $prices[1];
                    $html            = __( 'From', 'GIFTCARD' ) . ' ' . $currency_symbol . $prices[0] . __( ' to ', 'GIFTCARD' ) . $currency_symbol . ' ' . $prices[1];
                    $placeholder     = $prices[0] . '-' . $prices[1];

                    return $html;
                    break;
                }
                default:
                    return $price;
                    break;
            }
        } else {
            return $price;
        }
    }
}