<?php

class Square {

    //Class properties.
    protected $accessToken;
    protected $app_id;
    protected $squareURL;
    protected $locationId;
    protected $mainSquareURL;

    /**
     * Constructor
     *
     * @param object $accessToken
     *
     */
    public function __construct($accessToken, $locationId="me",$app_id) {
        $this->accessToken = $accessToken;
        $this->app_id = $app_id;
        if(empty($locationId)){ $locationId = 'me'; }
        $this->locationId = $locationId;
        $this->squareURL = "https://connect.squareup.com/v1/" . $this->locationId;
        $this->mainSquareURL = "https://connect.squareup.com/v1/me";
    }

    
    public function getAccessToken(){
        return $this->accessToken;
    }
    
    public function setAccessToken($access_token){
        $this->accessToken = $access_token;
    }
    
    public function getapp_id(){
        return $this->app_id;
    }
    
    public function setapp_id($app_id){
        $this->app_id = $app_id;
    }
    
    public function getSquareURL(){
        return $this->squareURL;
    }
    

    public function setLocationId($location_id){        
        $this->locationId = $location_id;
        $this->squareURL = "https://connect.squareup.com/v2/".$location_id;
    }
    
    public function getLocationId(){
        return $this->locationId;
    }
    
    /*
     * authoirize the connect to Square with the given token
     */

    public function authorize() {
        Helpers::debug_log('info', "-------------------------------------------------------------------------------");
        Helpers::debug_log('info', "Authorize square account with Token: " . $this->accessToken);
		$accessToken = explode('-',$this->accessToken);
		
		delete_option('woo_square_account_type' ); 
		delete_option('woo_square_account_currency_code' ); 
		delete_option('wc_square_version', '1.0.11', 'yes');
		delete_option('woocommerce_square_merchant_access_token');
		delete_option('woo_square_access_token');
		delete_option('woo_square_app_id');
		delete_option('woo_square_locations');
		delete_option('woo_square_business_name');
		delete_option('woo_square_location_id');
		
		if($accessToken[0] != 'sandbox'){
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $this->mainSquareURL );
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Authorization: Bearer ' . $this->accessToken)
			);
			$response = curl_exec($curl);
			Helpers::debug_log('info', "The response of authorize curl request" . $response);
			curl_close($curl);
			$response_v_1 = json_decode($response, true); 
			update_option('woo_square_account_type', @$response_v_1['account_type']);
			update_option('woo_square_account_currency_code', @$response_v_1['currency_code']);
				// live/production app id from Square account
				if (!defined('SQUARE_APPLICATION_ID')) define('SQUARE_APPLICATION_ID',$this->app_id );
				if (!defined('WC_SQUARE_ENABLE_STAGING')) define('WC_SQUARE_ENABLE_STAGING',false );
			} else {
				// live/production app id from Square account
				if (!defined('SQUARE_APPLICATION_ID')) define('SQUARE_APPLICATION_ID',$this->app_id );
				if (!defined('WC_SQUARE_ENABLE_STAGING')) define('WC_SQUARE_ENABLE_STAGING',true );
				update_option('woo_square_account_type', 'BUSINESS');
				update_option('woo_square_account_currency_code',get_option('woocommerce_currency'));
			}
			$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => "https://connect.squareup.com/v2/locations",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
					"authorization: Bearer ".$this->accessToken,
					"cache-control: no-cache",
					"postman-token: f39c2840-20f3-c3ba-554c-a1474cc80f12"
				),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
				Helpers::debug_log('info', "cURL Error #:" . $err);
			} else {
				$response = json_decode($response, true);
				$response = @$response['locations'][0];
				Helpers::debug_log('info', "The response of authorize curl request" . json_encode( $response ));
			}
			if (isset($response['id'])) {
				update_option('wc_square_version', '1.0.11', 'yes');
				update_option('woocommerce_square_merchant_access_token', $this->accessToken, 'yes');
				update_option('woo_square_access_token', $this->accessToken);
				update_option('woo_square_app_id', $this->app_id);
				$result = $this->getAllLocations();
				if(!empty($result['locations']) and is_array($result['locations'])){
					
					foreach($result['locations'] as $key => $value){
						if(!empty($value['capabilities']) 
							and 
							$value['status'] == 'ACTIVE'
							and 
							$accessToken[0] == 'sandbox'
							){
							$accurate_result['locations'][] =  $result['locations'][$key];
						} elseif($accessToken[0] != 'sandbox'){
							$accurate_result['locations'][] =  $result['locations'][$key];
						}
					}
				}
				$results =  $accurate_result['locations'];
				$caps = null;
				if(!empty($results)){
					foreach($results as $result){
						$locations = $result;
						if(!empty($locations['capabilities'])){
							$caps = ' | '.implode(",",$locations['capabilities']).' ENABLED';
						}
						$location_id = ($locations['id']);
						$str[] = array(
						$location_id => $locations['name'].' '.str_replace("_"," ",$caps)
						);
					}
					update_option('woo_square_locations', $str);
					update_option('woo_square_business_name', $locations['name']);
					update_option('woo_square_location_id', $location_id);
				}
				$this->setupWebhook("PAYMENT_UPDATED",$this->accessToken);
				return true;
			} else {
				return false;
			}
    }
    /*
     * get currency code by location id
     */
    public function getCurrencyCode(){
        
        Helpers::debug_log('info', "Getting currency code for square token: {$this->getAccessToken()}, url: {$this->squareURL} "
        . "and location: {$this->locationId}");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->squareURL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->accessToken)
        );

        $response = curl_exec($curl);
        Helpers::debug_log('info', "The response of current location curl request" . $response);
        curl_close($curl);
        $response = json_decode($response, true);
        if (isset($response['id'])) {
            update_option('woo_square_account_currency_code', $response['currency_code']);
        }
    }
    
    
    
    
    /*
     * get all locations if account type is business
     */

    public function getAllLocations() {
      		
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://connect.squareup.com/v2/locations",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer ".$this->accessToken,
    "cache-control: no-cache",
    "postman-token: f39c2840-20f3-c3ba-554c-a1474cc80f12"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  // echo "cURL Error #:" . $err;
   Helpers::debug_log('info', "cURL Error #:" . $err);
} else {
	// $response = json_decode($response, true);
	// $response = $response['locations'][0];
	Helpers::debug_log('info', "The response of authorize curl request" . $response);

	// echo $response;
}
		
	return json_decode($response, true);	
		
		
		
		
		
    }

    /*
     * setup webhook with Square
     */

    public function setupWebhook($type,$accessToken) {
        // setup notifications
        $data = array($type);
        $data_json = json_encode($data);
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->squareURL . "/webhooks");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_json),
            'Authorization: Bearer '.$accessToken)
        );

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_json);

        $response = curl_exec($curl);
        Helpers::debug_log('info', "The response of setup webhook curl request " . $response);
        Helpers::debug_log('info', "-------------------------------------------------------------------------------");
		$err = curl_error($curl);
        curl_close($curl);
		if ($err) {
            update_option('Woosquare_webhook_response_error',json_encode($err).' : '.get_option('woo_square_location_id'));
        } else {
            update_option('Woosquare_webhook_response',json_encode($response).' : '.get_option('woo_square_location_id'));
        }

		
		
        return true;
    }

 
    /*
     * Update Square inventory based on this order 
     */

    public function completeOrder($order_id) {
       
        
        Helpers::debug_log('info', "Complete Order: " . $order_id);
        $order = new WC_Order($order_id);
        $items = $order->get_items();
        Helpers::debug_log('info', "Order's items" . json_encode($items));
        Helpers::debug_log('info', "Order created by " . $order->get_created_via());
 
        if ($order->get_created_via() == "Square")
            return;
 
        foreach ($items as $item) {
            if ($item['variation_id']) {
                Helpers::debug_log('info', "Variable item");
                if (get_post_meta($item['variation_id'], '_manage_stock', true) == 'yes' or get_post_meta($item['variation_id'], '_manage_stock', true) == '1') {
                    Helpers::debug_log('info', "Item allow manage stock");
                    $product_variation_id = get_post_meta($item['variation_id'], 'variation_square_id', true);
                    Helpers::debug_log('info', "Item variation square id: " . $product_variation_id);
                    $this->updateInventory($product_variation_id, -1 * $item['qty'], 'SALE');
                }
            } else {
                Helpers::debug_log('info', "Simple item");
                if (get_post_meta($item['product_id'], '_manage_stock', true) == 'yes' or get_post_meta($item['product_id'], '_manage_stock', true) == '1') {
                    Helpers::debug_log('info', "Item allow manage stock");
                    $product_variation_id = get_post_meta($item['product_id'], 'variation_square_id', true);
                    Helpers::debug_log('info', "Item variation square id: " . $product_variation_id);
                    $this->updateInventory($product_variation_id, -1 * $item['qty'], 'SALE');
                }
            }
        }
    }

    

    /*
     * create a refund to Square
     */

     /*
     * create a refund to Square
     */
 
    public function refund($order_id, $refund_id) {
       
        Helpers::debug_log('info', "Refund Order: " . $order_id);
        $order = new WC_Order($order_id);
        $items = $order->get_items();
        Helpers::debug_log('info', "Order's items" . json_encode($items));
        foreach ($items as $item) {
            if ($item['variation_id']) {
                Helpers::debug_log('info', "Variable item");
                if (get_post_meta($item['variation_id'], '_manage_stock', true) == 'yes' or get_post_meta($item['variation_id'], '_manage_stock', true) == '1') {
                    Helpers::debug_log('info', "Item allow manage stock");
                    $product_variation_id = get_post_meta($item['variation_id'], 'variation_square_id', true);
                    Helpers::debug_log('info', "Item variation square id: " . $product_variation_id);
                    $this->updateInventory($product_variation_id, 1 * $item['qty'], 'RECEIVE_STOCK');
                }
            } else {
                Helpers::debug_log('info', "Simple item");
                if (get_post_meta($item['product_id'], '_manage_stock', true) == 'yes' or get_post_meta($item['product_id'], '_manage_stock', true) == '1') {
                    Helpers::debug_log('info', "Item allow manage stock");
                    $product_variation_id = get_post_meta($item['product_id'], 'variation_square_id', true);
                    Helpers::debug_log('info', "Item variation square id: " . $product_variation_id);
                    $this->updateInventory($product_variation_id, 1 * $item['qty'], 'RECEIVE_STOCK');
                }
            }
        }
       
        
        $request = array(
            "payment_id" => get_post_meta($order_id, 'square_payment_id', true),
            "type" => "PARTIAL",
            "reason" => "Returned Goods",
            "refunded_money" => array(
                "currency_code" => get_post_meta($order_id, '_order_currency', true),
                "amount" => (get_post_meta($refund_id, '_refund_amount', true) * -100 )
            )
        );
        $json = json_encode($request);
 
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->squareURL . "/refunds");
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json),
            'Authorization: Bearer ' . $this->accessToken)
        );
 
        $response = curl_exec($curl);
        Helpers::debug_log('info', "The response of refund curl request" . $response);
        curl_close($curl);
        $refund_obj = json_decode($response);
        update_post_meta($order_id, "refund_created_at", $refund_obj->created_at);
    }

   
	/*
	* Update Inventory with stock amount
	*/

	public function updateInventory($variation_id, $stock, $adjustment_type = "RECEIVE_STOCK") {
		$data_string = '{
		"quantity_delta": ' . $stock . ',
		"adjustment_type": "' . $adjustment_type . '"
		}';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->getSquareURL() . '/inventory/' . $variation_id);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string),
		'Authorization: Bearer ' . $this->getAccessToken())
		);

		$response = curl_exec($curl);
		Helpers::debug_log('info', "The response of updating inventory curl request" . $response);
		curl_close($curl);

		return $response;
	}
    
}
