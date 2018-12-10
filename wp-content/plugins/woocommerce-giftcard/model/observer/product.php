<?php

class Magenest_Giftcard_Product {
	public function __construct()
	{
//		add_filter( 'product_type_options', array( $this, 'add_giftcard_product_type' ) );
//		add_action( 'woocommerce_product_options_pricing', array( $this, 'add_giftcard_price' ) );
//		add_action( 'woocommerce_process_product_meta_simple', array( $this, 'save_giftcard_product_info' ) );
//		add_filter( 'woocommerce_add_cart_item_data', array( $this, 'process_add_giftcard' ), 10, 2 );
//		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'add_giftcart_fields' ) );
//		add_action( 'woocommerce_cart_actions', array( $this, 'show_apply_giftcart_form' ), 1 );
		//adjust price for gift card
		add_filter( 'woocommerce_get_price_html', array( $this, 'show_giftcard_price' ), 10, 2 );
		//adjust price for gift card
		add_filter( 'woocommerce_product_get_price', array($this, 'get_giftcard_price'), 10, 2 );//woocommerce_product_get_price//woocommerce_product_get_price
		//add_action('woocommerce_before_shop_loop_item', array($this,'hide_input_price_on_category_page'));
		add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_to_cart_link' ), 10, 2 );

		add_action( 'admin_post_send_giftcard', array( $this, 'send_giftcard_action' ) );

	}

	/**
	 * @param unknown    $add_to_cart_html
	 * @param WC_Product $product
	 *
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
				esc_html( __( 'Select Options', GIFTCARD_TEXT_DOMAIN ) )
			);

			return $select_options;
		}
	}

	public function hide_input_price_on_category_page()
	{
		$js = '<script type="text/javascript">
				jQuery(document).ready(function() {
				jQuery(".giftcardinputprice").hide();
				jQuery(".giftcardhelpicon").hide();
	});
				 </script>';
		echo $js;
	}

	public function get_giftcard_price( $price, $product )
	{
		$post_id     = $product->get_id();
		$is_giftcard = get_post_meta( $post_id, '_giftcard', true );
		if ( $is_giftcard ) {
			$price_model = get_post_meta( $post_id, '_giftcard-price-model', true );
			$gc          = 'gc' . $post_id;
			//$woocommerce->session->$gc= $_POST['giftcard-amount'];
			if ( isset( $_SESSION[ $gc ] ) && is_numeric( $_SESSION[ $gc ] ) && $_SESSION[ $gc ] > 0 ) {

				return $_SESSION[ $gc ];
			} else {
				return $price;
			}
		}

		return $price;
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
                                        if($giftcard['meta_key'] == 'gc_status' && ($giftcard['meta_value'] == '-1' || $giftcard['meta_value'] == -1) && !in_array($value,$giftcard_value)){
                                            $giftcard_value[] = $value;
                                        }
                                    }
                                }
                                sort($giftcard_value);
                                $preset = $giftcard_value;
                            }
                        }
                        sort($preset);
						$count           = count( $preset );
						$currency_symbol = get_woocommerce_currency_symbol();
						$html            = __( 'From', GIFTCARD_TEXT_DOMAIN ) . ' ' . $currency_symbol . $preset[0] . ' tossss ' . $currency_symbol . $preset[ $count - 1 ];

						return $html;
						break;
					}
				case 'custom-price' :
					{
						$currency_symbol = get_woocommerce_currency_symbol();
						$price_range     = get_post_meta( $post_id, '_giftcard-price-range', true );
						$prices          = explode( '-', $price_range );
						$html            = __( 'Enter an amount between ', GIFTCARD_TEXT_DOMAIN ) . $currency_symbol . ' ' . $prices[0] . __( ' and ', GIFTCARD_TEXT_DOMAIN ) . $currency_symbol . ' ' . $prices[1];
						$html            = __( 'From', GIFTCARD_TEXT_DOMAIN ) . ' ' . $currency_symbol . $prices[0] . __( ' to ', GIFTCARD_TEXT_DOMAIN ) . $currency_symbol . ' ' . $prices[1];
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

	public function save_giftcard_product_info( $post_id )
	{
		update_post_meta($post_id, '_giftcard_mode', $_POST['_gc_mode']);
        update_post_meta( $post_id, '_giftcard-expiry-model', $_POST['_giftcard-expiry-model'] );
        update_post_meta( $post_id, '_giftcard-price-model', $_POST['_giftcard-price-model'] );
		update_post_meta( $post_id, '_giftcard-email_templates', json_encode( $_POST['_email_templates'] ) );
		update_post_meta( $post_id, '_giftcard-pdf_templates', json_encode( $_POST['_pdf_templates'] ) );
		update_post_meta( $post_id, 'file_name_giftcard', $_POST['file_name_giftcard']);


        if ( isset( $_POST['_giftcard-preset-price'] ) ) {
            update_post_meta( $post_id, '_giftcard-preset-price', $_POST['_giftcard-preset-price'] );
        }

        if ( isset( $_POST['_giftcard-price-range'] ) ) {
            update_post_meta( $post_id, '_giftcard-price-range', $_POST['_giftcard-price-range']['min'] .'-'. $_POST['_giftcard-price-range']['max']);
        }

        if ( isset( $_POST['_giftcard-expiry-date'] ) ) {
            update_post_meta( $post_id, '_giftcard-expiry-date', $_POST['_giftcard-expiry-date'] );
        }

        if ( isset( $_POST['_giftcard-expiry-time'] ) ) {
            update_post_meta( $post_id, '_giftcard-expiry-time', $_POST['_giftcard-expiry-time'] );
        }

        if ( isset( $_POST['_giftcard'] ) ) {
			update_post_meta( $post_id, '_giftcard', 'yes' );
			update_post_meta( $post_id, '_virtual', 'yes' );
		} else {
			update_post_meta( $post_id, '_giftcard', 'no' );
		}


	}

	public function add_giftcard_product_type( $product_type_options )
	{

		$giftcard = array(
			'giftcard' => array(
				'id'            => '_giftcard',
				'wrapper_class' => 'show_if_simple show_if_variable',
				'label'         => __( 'Gift Card', 'GIFTCARD' ),
				'description'   => __( 'Gift card is virtual product', 'GIFTCARD' )
			),
		);

		$product_type_options = array_merge( $giftcard, $product_type_options );

		return $product_type_options;
	}

	public function add_giftcard_price()
	{
        wp_enqueue_style('gc_product_config');

        wp_enqueue_script('gc_product_conf');
		ob_start();
		$id            = 0;
		$template_path = GIFTCARD_PATH . 'admin/view/';
		$default_path  = GIFTCARD_PATH . 'admin/view/';


		wc_get_template( 'view-giftcard-price.php', array(
			'id' => $id,

		), $template_path, $default_path
		);
		echo ob_get_clean();
		//echo 	include_once GIFTCARD_PATH.'/admin/view/view-giftcard-price.php';
	}

	public function process_add_giftcard( $cart_item_data, $product_id )
	{
		$is_giftcard = get_post_meta( $product_id, '_giftcard', true );

		if ( $is_giftcard == "yes" ) {

			$unique_cart_item_key         = md5( "gc" . microtime() . rand() );
			$cart_item_data['unique_key'] = $unique_cart_item_key;

		}

		return $cart_item_data;
	}

//	public function add_giftcart_fields()
//	{
//        wp_enqueue_style('datetimepickerstyle');
//        wp_enqueue_style('datetimepickerstandlonestyle');
//        wp_enqueue_style('boostrap');
//        wp_enqueue_style('gc_preview_jquery');
//        wp_enqueue_style('gc_front_end');
//
//        wp_enqueue_script("momentjs");
//        wp_enqueue_script("datetimepicker");
//        wp_enqueue_script('gc-preview-email');
//        wp_enqueue_script('gc-preview-pdf');
//        wp_localize_script('gc-preview-email', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
//        wp_localize_script('gc-preview-pdf', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
//
//		if ( ! is_single() ) {
//			return;
//		}
//		global $post;
//		$is_giftcard = get_post_meta( $post->ID, '_giftcard', true );
//		if ( $is_giftcard == 'yes' ) {
//			ob_start();
//			$template_path = GIFTCARD_PATH . 'template/';
//			$default_path  = GIFTCARD_PATH . 'template/';
//			wc_get_template( 'add_giftcart_fields.php', array(), $template_path, $default_path );
//			echo ob_get_clean();
//		}
//	}

//	public function show_apply_giftcart_form()
//	{
//		global $post;
//		ob_start();
//		$id            = 0;
//		$template_path = GIFTCARD_PATH . 'template/';
//		$default_path  = GIFTCARD_PATH . 'template/';
//		wc_get_template( 'add_giftcart_form.php', array( 'id' => $id, ), $template_path, $default_path );
//		echo ob_get_clean();
//	}

	/**
	 * process send/resend button in giftcard post type
	 */
	public function send_giftcard_action()
	{
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'send_giftcard' && isset( $_REQUEST['id'] ) ) {
			$giftcardId       = $_REQUEST['id'];
			$post             = get_post( $giftcardId );
			$code             = $post->post_title;
			$giftcardInstance = new \model\Magenest_Giftcard( $code );
			//$order_id = get_post_meta($post, 'magenest_giftcard_order_id', 0);
			$giftcardInstance->send( $giftcardId );
			wp_redirect( admin_url( 'edit.php?post_type=shop_giftcard' ) );
		} else {
			wp_redirect( admin_url( 'edit.php?post_type=shop_giftcard' ) );
		}
	}
}

return new Magenest_Giftcard_Product();