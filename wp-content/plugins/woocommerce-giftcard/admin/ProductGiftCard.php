<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 03/08/2018
 * Time: 23:40
 */

namespace admin;


class ProductGiftCard {
	public function __construct() {
		add_filter( 'product_type_options', array( $this, 'add_giftcard_product_type' ) );
		add_action( 'woocommerce_product_options_pricing', array( $this, 'add_giftcard_price' ) );
		add_action( 'woocommerce_process_product_meta_simple', array( $this, 'save_giftcard_product_info' ) );
	}
	public function save_giftcard_product_info( $post_id )
	{
		update_post_meta($post_id, '_giftcard_mode', $_POST['_gc_mode']);
		update_post_meta( $post_id, '_giftcard-expiry-model', $_POST['_giftcard-expiry-model'] );
		update_post_meta( $post_id, '_giftcard-price-model', $_POST['_giftcard-price-model'] );
		if(isset($_POST['_email_templates'])){
            update_post_meta( $post_id, '_giftcard-email_templates', json_encode($_POST['_email_templates']));
        }else{
            update_post_meta( $post_id, '_giftcard-email_templates', json_encode([]) );
        }
        if(isset($_POST['_pdf_templates'])){
            update_post_meta( $post_id, '_giftcard-pdf_templates', json_encode( $_POST['_pdf_templates'] ) );
        }else{
            update_post_meta( $post_id, '_giftcard-pdf_templates', json_encode([]));
        }
        if(isset($_POST['file_name_giftcard'])){
            update_post_meta( $post_id, 'file_name_giftcard', $_POST['file_name_giftcard']);
        }else{
            update_post_meta( $post_id, 'file_name_giftcard', []);
        }

		if ( isset( $_POST['_giftcard-preset-price'] ) ) {
			update_post_meta( $post_id, '_giftcard-preset-price', $_POST['_giftcard-preset-price'] );
		}

		if ( isset( $_POST['_giftcard-price-range'] ) ) {
            $min = $_POST['_giftcard-price-range']['min'];
            $max = $_POST['_giftcard-price-range']['max'];
            if($min>$max){
                $tmp = $max;
                $max = $min;
                $min = $tmp;
            }
			update_post_meta( $post_id, '_giftcard-price-range', $min .'-'. $max);
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

        if ( isset( $_POST['schedule_send_gc_mode'] ) ) {
            update_post_meta( $post_id, 'schedule_send_gc_mode', 'yes' );
        } else {
            update_post_meta( $post_id, 'schedule_send_gc_mode', 'no' );
        }

		if(isset( $_POST['_exclude_products'])){
            update_post_meta( $post_id, '_exclude_products', json_encode($_POST['_exclude_products']));
        }else{
            update_post_meta( $post_id, '_exclude_products', json_encode([]));
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
		wc_get_template( 'view-giftcard-price.php', array('id' => $id), $template_path, $default_path);
		echo ob_get_clean();
		//echo 	include_once GIFTCARD_PATH.'/admin/view/view-giftcard-price.php';
	}
}