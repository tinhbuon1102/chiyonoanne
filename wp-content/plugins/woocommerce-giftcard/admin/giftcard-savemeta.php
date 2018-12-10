<?php

class Magenest_Giftcard_Savemeta {
	public function __construct() {

		
	}
	
	public function updateGiftcard($post_id, $post) {
		global $wpdb, $woocommerce_errors;
		$load_data = array(
            'gc_expired_at'        => get_post_meta($post_id,'gc_expired_at',true),
            'gc_extra_info' => get_post_meta($post_id,'gc_extra_info',true)
		);
		$orderId = get_post_meta($post_id,'magenest_giftcard_order_id',0);
		if($orderId == 0){
			$load_data['model'] = 'admin';
		}else{
			$load_data['model'] = 'buyer';
		}
		foreach ( $load_data as $key => $default ){
			if($key == 'scheduled_send_time'){
				$sheduled = isset ( $_POST [$key] ) && $_POST [$key] != '' ? $_POST [$key]: $default;
				$shedule = new DateTime($sheduled);
				$format = 'Y-m-d';
				$time = $shedule->format ( $format );
				update_post_meta( $post_id, $key, $time );
				continue;
			}
			$value = isset ( $_POST [$key] ) && $_POST [$key] != '' ? $_POST [$key]: $default;
			update_post_meta( $post_id, $key, $value );
		}
		$log = get_post_meta($post_id,'gc_extra_info',true);
        $balance = get_post_meta($post_id,'gc_balance',true);

        $giftcardcode = $post->post_title;
        $giftcard_history = $wpdb->prefix.'magenest_giftcard_history';
        $sql = "SELECT * FROM ".$giftcard_history." WHERE `giftcard_code` = '%s'";
        $giftcard_history_record = $wpdb->get_row($wpdb->prepare($sql, $wpdb->esc_like($giftcardcode)), ARRAY_A);
        if(!empty($giftcard_history_record)){
            $giftcard = new model\Magenest_Giftcard();
            $data = array(
                'giftcard_id' => $post->ID,
                'giftcard_code' => $giftcardcode,
                'balance' => $balance[0],
                'change_balanced' => 0,
                'order_id' => 0,
                'log' => $log[0] .' update by Admin'

            );
            $giftcard->InsertRedeemLog($data);
        }
        update_post_meta( $post_id, 'gc_extra_info', '' );
	}
}

return new Magenest_Giftcard_Savemeta();
