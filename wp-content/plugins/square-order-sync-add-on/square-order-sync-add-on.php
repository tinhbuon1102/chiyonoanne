<?php
/*
 * Plugin Name:       WooCommerce to Square order sync Add-on
 * Plugin URI:        https://wpexperts.io/wordpress-plugins/
 * Description:       Order itemization will help you to send item information like taxes, discount and breakdown of items from woocommerce to square dashboard.
 * Version:           1.0
 * Author:            Wpexperts
 * Author URI:        https://wpexperts.io/wordpress-plugins/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       square-order-sync-add-on
 * Domain Path:       /languages
 */

			

function admin_notice_squ_ordr_sync() {
	$class = 'notice notice-error';
	if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
		$message = __( 'To use "WooCommerce to Square order sync Add-on" WooCommerce must be activated or installed!', 'woosquare' );
		printf( '<br><div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	}
	if (!in_array('woosquare-pro/woocommerce-square-integration.php', apply_filters('active_plugins', get_option('active_plugins')))) {
		$message = __( 'To use "WooCommerce to Square order sync Add-on" WooSquare Pro must be activated or installed!', 'woosquare' );
		printf( '<br><div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	}
	if (version_compare( PHP_VERSION, '5.5.0', '<' )) {
		$message = __( 'To use "WooCommerce to Square order sync Add-on" PHP version must be 5.5.0+, Current version is: ' . PHP_VERSION . ". Contact your hosting provider to upgrade your server PHP version.\n", 'woosquare' );
		printf( '<br><div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
	}
	deactivate_plugins('square-order-sync-add-on/square-order-sync-add-on.php');
	wp_die('','Plugin Activation Error',  array( 'response'=>200, 'back_link'=>TRUE ) );
}
if (
	!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))
	or 
	!in_array('woosquare-pro/woocommerce-square-integration.php', apply_filters('active_plugins', get_option('active_plugins')))
	or 
	version_compare( PHP_VERSION, '5.5.0', '<' )
	) { 
	add_action( 'admin_notices', 'admin_notice_squ_ordr_sync' );
	} else {
	
	function square_order_sync_add_on($order,$woo_square_locations,$currency,$uid){
				$WooSquare_Gateway = new WooSquare_Gateway();
				$line_items_array	 = array();
				// Get an instance of WC_Order object
				// $order = wc_get_order( $order_id );
				$discounted_amount = null; 
				
				$totalcartitems = count($order->get_items());
				$total_order_item_qty = null;
				foreach ($order->get_items() as $item_id => $item_data) {
					$total_order_item_qty +=  $item_data->get_quantity();
				}
				foreach ($order->get_items() as $item_id => $item_data) {
					$discounted_amount = null;
					// Get an instance of corresponding the WC_Product object
					
					$product = $item_data->get_product();
					$get_id = $product->get_id();
					$product_name = $product->get_name(); // Get the product name

					$item_quantity = $item_data->get_quantity(); // Get the item quantity

					$item_total = $product->get_price(); // Get the item line total

					$tax_data = $item_data->get_data();
					// Coupons used in the order LOOP (as they can be multiple)
					if(!empty($order->get_used_coupons())){
						foreach( $order->get_used_coupons() as $coupon_name ){

						// Retrieving the coupon ID
						$coupon_post_obj = get_page_by_title($coupon_name, OBJECT, 'shop_coupon');
						$coupon_id = $coupon_post_obj->ID;

						// Get an instance of WC_Coupon object in an array(necesary to use WC_Coupon methods)
						$coupons_obj = new WC_Coupon($coupon_id);
							
							if(!empty($coupons_obj)){
								if($coupons_obj->get_discount_type() == "fixed_product" ){
									  $discounted_amount_fixed_product = round($coupons_obj->get_amount()*$item_quantity,2);
								}
								if($coupons_obj->get_discount_type() == "percent" ){
									  $discounted_amount_for_fixed_cart = round((($item_total*$item_quantity)*$coupons_obj->get_amount())/100,2);
								} 
								if( $coupons_obj->get_discount_type() == "fixed_cart"){
									$discounted_amount_for_fixed_cart = ($coupons_obj->get_amount()/$total_order_item_qty)*$item_quantity;
								}
							}
					}
					}
					
					// price without tax - price with tax = xxxx /  price without tax *100
					if(!empty($tax_data['taxes']['total'])){
						$pricewithouttax = $tax_data['total']; 
						$pricewithtax = $tax_data['total'] + round($tax_data['taxes']['total'][1],2); 
						$res = $pricewithtax - $pricewithouttax;  
						$perc = ($res/$pricewithouttax )*100;
						$item_tax = ',"taxes": [ 
									{
									   "name": "Sales Tax",
									   "type": "ADDITIVE",
									   "percentage": "'.round($perc,2).'"
									}
								 ]';
					  
					} else {
						$item_tax = '';
					}
					if(!empty($discounted_amount_fixed_product)){
						$discounts_for_fixed_product = ',"discounts": [ 
									{
									   "name": "'.$currency.' '.$discounted_amount_fixed_product.' '.$coupons_obj->get_discount_type().'",
									   "amount_money": {
										  "amount": '.(int) $WooSquare_Gateway->format_amount( $discounted_amount_fixed_product, $currency ).',
										  "currency": "'.$currency.'"
									   },
									   "scope": "LINE_ITEM"
									}
								 ]';
								 $discounts = '';
								 
					} else {
						$discounts_for_fixed_product = ''; 
					}
					if(!empty($discounted_amount_for_fixed_cart)){
						
						$discounts_for_fixed_cart = ',"discounts": [ 
									{
									   "name": "'.$currency.' '.$discounted_amount_for_fixed_cart.' '.$coupons_obj->get_discount_type().'",
									   "amount_money": {
										  "amount": '.(int) $WooSquare_Gateway->format_amount( $discounted_amount_for_fixed_cart, $currency ).',
										  "currency": "'.$currency.'"
									   },
									   "scope": "LINE_ITEM"
									}
								 ]';
								 $discounts = '';
					} else {
						$discounts_for_fixed_cart = ''; 
					}
					$line_items_array[] = 	'{
						"name": "'.$product_name.'",
						"note": "custom-note",
						"quantity": "'.$item_quantity.'",
						"base_price_money": {
							"amount": '.(int) $WooSquare_Gateway->format_amount( $item_total, $currency ).',
							"currency": "'.$currency.'"
						}'.$item_tax.'
						'.$discounts_for_fixed_cart.'						
						'.$discounts_for_fixed_product.'						
					}';
					$line_items = implode( ', ', $line_items_array );
				}
						$order_create = '{
						   "idempotency_key": "'.$uid.'",
						   "reference_id": "'.(string) $order->get_order_number().'",
						   "line_items": [
								'.$line_items.'
								
						   ]
						}';
						
						$curl = curl_init();
						curl_setopt_array($curl, array(
						  CURLOPT_URL => "https://connect.squareup.com/v2/locations/".$woo_square_locations."/orders",
						  CURLOPT_RETURNTRANSFER => true,
						  CURLOPT_ENCODING => "",
						  CURLOPT_MAXREDIRS => 10,
						  CURLOPT_TIMEOUT => 30,
						  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						  CURLOPT_CUSTOMREQUEST => "POST",
						  CURLOPT_POSTFIELDS => $order_create,
						  CURLOPT_HTTPHEADER => array(
							"authorization: Bearer ".get_option('woo_square_access_token'),
							"cache-control: no-cache",
							"content-type: application/json"
						  ),
						));
						$response = curl_exec($curl);
						
						$err = curl_error($curl);
						curl_close($curl);
						if ($err) {
							$order_created = sprintf( __( 'Square order created error ( response : %s )', 'wpexpert-square' ), $err );
							$order->add_order_note( $order_created );
						} else {
							$orderresponse = json_decode( $response ); 
							$order_created = sprintf( __( 'Square order created ( Order ID : %s )', 'wpexpert-square' ), $orderresponse->order->id );
							$order->add_order_note( $order_created );
						}	
						update_post_meta((string) $order->get_order_number(),'WooSquare_Order_create_response',$response);
						update_post_meta((string) $order->get_order_number(),'WooSquare_Order_create_response_error',$err);
						
				
						return $orderresponse->order->id;
						
}
	

} 