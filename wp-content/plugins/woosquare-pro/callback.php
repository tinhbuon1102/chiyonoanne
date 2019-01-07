<?php
square_woo_debug_log('info', "Callback page called.");

require(dirname(__FILE__) . '/../../../wp-blog-header.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    square_woo_debug_log('info', "Callback page called via get request.");
    echo die('Callback request working!');
}

$post_data = json_decode(file_get_contents("php://input"));

if (!$post_data) {
    square_woo_debug_log('info', "Callback page called via POST request. but there is no post data.");
    echo die('Callback request working with no post data');
}

square_woo_debug_log('info', "Callback page called via POST with post data (json format) " . $HTTP_RAW_POST_DATA);

if (isset($post_data->event_type) && $post_data->event_type == "TEST_NOTIFICATION") {
    square_woo_debug_log('error', "This is a test notifications from Square ");
    header("HTTP/1.1 200 OK");
	die();
}
if(empty(get_option('square_payment_begin_time'))){
	// 2013-01-15T00:00:00Z
	update_option('square_payment_begin_time',date("Y-m-d")."T00:00:00Z");
}
/* get all items */
$ran = rand(1000000,20000000);
usleep($ran);
square_woo_debug_log('sleep', "sleep for ".$ran);

$squord = get_option('square_order');
if(!empty($squord)){
delete_option('square_order');
square_woo_debug_log('sleep', "order_deleted".$squord);
}


$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://connect.squareup.com/v1/".get_option('woo_square_location_id')."/payments?begin_time=".get_option('square_payment_begin_time')."&end_time=".date('Y-m-d', strtotime($Date. ' + 1 days'))."T00:00:00Z&order=DESC",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer ".get_option('woo_square_access_token'),
    "cache-control: no-cache",
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

square_woo_debug_log('info', "All transaction response" . $response);
square_woo_debug_log('info', "All transaction request : https://connect.squareup.com/v1/".get_option('woo_square_location_id')."/payments?begin_time=".get_option('square_payment_begin_time')."&end_time=".date('Y-m-d', strtotime($Date. ' + 1 days'))."T00:00:00Z&order=DESC");

$payment_obj = json_decode($response);


if (empty($response)) {
    // some kind of an error happened
    square_woo_debug_log('error', "The response of payment details curl request " . $err);
    curl_close($ch);
    return false;
} else {
    square_woo_debug_log('info', "The response of payment details curl request " . $response);
    curl_close($ch);
	
	foreach($payment_obj as $payment){
    if (!empty($payment->itemizations) or !empty($payment->refunds)) {
        
        
		global $wpdb;
		$checkif_order_not_exist = $wpdb->get_results("SELECT * FROM `".$wpdb->postmeta."` WHERE meta_key='".$wpdb->escape('square_payment_id')."' AND meta_value='".$wpdb->escape($payment->id)."'");
		if(empty($checkif_order_not_exist[0])){
			 foreach ($payment->itemizations as $item) {
				 if($item->name == "Custom Amount"){
					square_woo_debug_log('info', "Square Custom ammount not supported");
					continue 2;    
				 }
				  if(empty($item->item_detail->sku)){
					square_woo_debug_log('info', "Square item not found order break");
    				continue 2;    
				 }
			 }

            
            $user = get_user_by('login', 'square_user');
            $args = array(
                'customer_id' => $user->ID,
                'created_via' => 'Square',
            );
            $order = wc_create_order($args);
            square_woo_debug_log('info', "Creating new order for : ".$order->get_order_number()." and payment id is ".$payment->id);
			// if (!is_wp_error( $order )) {
				// square_woo_debug_log('info', json_encode($order->get_error_message()));
			// }
			
            foreach ($payment->itemizations as $item) {
                square_woo_debug_log('info', "Square item details: " . json_encode($item));

                $sku = $item->item_detail->sku;
				
				global $wpdb;

				$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

				
				
				
               /*  $args = array(
                    'post_type' => array('product', 'product_variation'),
                    'meta_query' => array(array('key' => 'variation_square_id', 'value' => $variation_id)),
                    'fields' => 'ids'
                );
                $vid_query = new WP_Query($args);
                $vid_ids = $vid_query->posts; */
                square_woo_debug_log('info', "The result of searching for item on woocommerce: " . $product_id);
                // do something if the meta-key-value-pair exists in another post


                if (!empty($product_id)) {
                    $order->add_product(wc_get_product($product_id), $item->quantity); //(get_product with id and next is for quantity)
                } else {
                    square_woo_debug_log('info', "product not found on woocommerce.");

					$square = new Square(get_option('woo_square_access_token'), get_option('woo_square_location_id'),get_option('woo_square_app_id'));
                    /* get all items */
                    $url = $square->getSquareURL() .  '/items/' . $item->item_detail->item_id;
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . get_option('woo_square_access_token'), 'Accept: application/json'));
                    $response = curl_exec($ch);
                    square_woo_debug_log('info', "The response of getting product details of curl request" . $response);
                    curl_close($ch);
                    $squareProduct = json_decode($response);

                    /* get Inventory of all items */
                    $url1 = $square->getSquareURL() .  '/inventory';
                    $ch1 = curl_init();
                    curl_setopt($ch1, CURLOPT_URL, $url1);
                    curl_setopt($ch1, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, FALSE);
                    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch1, CURLOPT_CUSTOMREQUEST, 'GET');
                    curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . get_option('woo_square_access_token'), 'Accept: application/json'));
                    $response = curl_exec($ch1);
                    square_woo_debug_log('info', "The response of get inventory curl request" . $response);
                    curl_close($ch1);
                    $squareInventory = json_decode($response);

                    
                    // include WOO_SQUARE_PLUGIN_PATH . '_inc/SquareToWooSynchronizer.class.php';
                    $squareSynchronizer = new SquareToWooSynchronizer($square);
					
					square_woo_debug_log('info', "new category add if category not exist in woocommerce.");
					$result = $squareSynchronizer->addCategoryToWoo($squareProduct->category);
					if ($result!==FALSE){
						update_option("is_square_sync_{$result}", 1);           
					}
					
					square_woo_debug_log('info', "new product going to be add in woocommerce.");
                    $squareSynchronizer->addProductToWoo($squareProduct, $squareInventory);
					

                    $sku = $item->item_detail->sku;
                   /*  $args = array(
                        'post_type' => array('product', 'product_variation'),
                        'meta_query' => array(array('key' => 'variation_square_id', 'value' => $variation_id)),
                        'fields' => 'ids'
                    );
                    $vid_query = new WP_Query($args);
                    $vid_ids = $vid_query->posts; */
					
					global $wpdb;

					$product_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

					
					
                    square_woo_debug_log('info', "The result of searching AGAIN for item on woocommerce " . $product_id);

                    
                    $order->add_product(wc_get_product($product_id), $item->quantity); //(get_product with id and next is for quantity)
                }
            }
            $order_id = $order->get_order_number();
			add_post_meta($order_id, 'square_payment_id', $payment->id);
			//update order date according to square created date.
			// created_at
			
			
			
			
			$payment_date = $payment->created_at;
			$payment_date  = str_replace("Z","",$payment_date);
			$payment_date = explode("T",$payment_date);
			//2017-11-08 06:44:00
			$date = $payment_date[0]." ".$payment_date[1];
			
			$date = new DateTime($date);
			$timezone_string = get_option('timezone_string');
			if(!empty($timezone_string)){
				$date->setTimezone(new DateTimeZone($timezone_string)); // +06
				$converted_date =  $date->format('Y-m-d H:i:s'); // 2012-07-15 05:00:00 
				$my_post = array(
					  'ID'           => $order_id,
					  'post_date'   => $converted_date,
					  'post_date_gmt' => $converted_date
				  );
				// Update the post into the database
				wp_update_post( $my_post ); 
			}
           
				
            //check if there is discount in the order
            if ($payment->discount_money->amount) {
				$total = $order->calculate_totals();
                $order->set_total(-1 * $payment->discount_money->amount / 100, 'cart_discount');
                $order->set_total($total + ($payment->discount_money->amount / 100), 'total');
				
            } else {
				$total = $order->calculate_totals();
			}
			
			$order->update_status('completed');	
			wc_reduce_stock_levels($order_id);
			global $wpdb;
			$sql ="UPDATE `wp_posts` SET `ID` = '".$order_id."', `post_date_gmt` = '".$date."', `post_date` = '".$date."' WHERE `ID` = '".$order_id."'";
			$rez = $wpdb->query($sql);
		}
		//Check if this request is "Refund Request"
		if (count($payment->refunds)){ 
            square_woo_debug_log('info', "Creating new refund and array of refund from square .".json_encode($payment->refunds));
            global $wpdb;
            $results = $wpdb->get_results("select post_id, meta_key from $wpdb->postmeta where meta_value = '$payment->id'", ARRAY_A);
            square_woo_debug_log('info', "refund query result :" . json_encode($results));
            if (count($results)) {
                $order_id = $results[0]['post_id'];
                $created_at = get_post_meta($order_id, "refund_created_at", true);
                if ($created_at != $payment->refunds[0]->created_at and !empty($payment->refunds[0]->created_at)) {// Avoid duplicate insert in case we refund from woo commerce which will fire payment update webhooks, so we need do nothing in this case to avoid dubplicate insertion
				$refund_obj = wc_create_refund(array('order_id' => $order_id, 'amount' => -1 * $payment->refunds[0]->refunded_money->amount / 100, 'reason' => $payment->refunds[0]->reason));
                $user = get_user_by('login', 'square_user');
                wp_update_post(array('ID' => $refund_obj->id, 'post_author' => $user->ID));
				update_post_meta($order_id, "refund_created_at", $payment->refunds[0]->created_at);
				//increase stock after refund.
				foreach ($payment->itemizations as $item) {
				$variation_id = $item->item_detail->item_variation_id;
				$args = array(
					'post_type' => array('product', 'product_variation'),
					'meta_query' => array(array('key' => 'variation_square_id', 'value' => $variation_id)),
					'fields' => 'ids'
				);
				$vid_query = new WP_Query($args);
				$vid_ids = $vid_query->posts;
				 if ($vid_ids) {
					$product_id = $vid_ids[0];
					$product  = get_product($product_id);
					if ( $product && $product->managing_stock()){ 
							$old_stock = $product->get_stock_quantity(); 
							$new_stock = wc_update_product_stock( $product, $item->quantity, 'increase' ); 
							$order = new WC_Order( $order_id );
							do_action( 'woocommerce_restock_refunded_item', $product->get_id(), $old_stock, $new_stock, $order, $product ); 
					  } 
					
				}
				}
                }
			}  //else Create new order
			
        }
        
    } 
}
update_option('square_payment_begin_time',date("Y-m-d")."T00:00:00Z");
}

/**
 * @param type $data
 */
function square_woo_debug_log($type, $data) {
    error_log("[$type] [" . date("Y-m-d H:i:s") . "] " . print_r($data, true) . "\n", 3, dirname(__FILE__) . '/logs.log');
}
