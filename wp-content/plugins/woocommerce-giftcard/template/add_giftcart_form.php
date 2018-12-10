<?php
	global $woocommerce;
	$productId = '';
	if ($woocommerce->cart->cart_contents ) {
		foreach ($woocommerce->cart->cart_contents  as $key=>$cart_item) {
			$productId .= $cart_item['product_id'] .' ';
		}
	}
?>
<form method="POST" id="giftcard-apply-form">
	<div class="giftcard coupon">
			<label for="giftcard_code"><?php _e( 'Giftcard', 'woocommerce' ); ?>:</label>
			<input type="hidden" name="product_id" class="input-text" id="product_id" value="<?= isset($productId)?trim($productId):'' ?>"/>
			<input type="text" name="giftcard_code" class="input-text" id="giftcard_code" value="<?php  if ( isset($woocommerce->session->giftcard_code )) :?><?php echo $woocommerce->session->giftcard_code ?> <?php endif;?>" placeholder="<?php _e( 'Gift Card', 'woocommerce' ); ?>" />
			<input type="submit" class="button" onclick="saveFormGiftCard()" value="<?php _e( 'Apply Gift card', 'woocommerce' ); ?>" />
	</div>
</form>
