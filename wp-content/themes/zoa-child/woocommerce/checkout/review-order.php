<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="woocommerce-checkout-review-order-table">
<div class="checkout-mini-cart">
	
	
		<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<div class="minicart__product <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<div class="mini-product--group">
							<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo wp_kses_post( $thumbnail );
						} else {
							printf( '<a class="mini-product__link" href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
						}
						?>
							<div class="mini-product__info">
								<?php 
					echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<p class="mini-product__item mini-product__name p5"><a href="%s" class="link">%s</a></p>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) ); ?>
								<div class="mini-product__item mini-product__attribute">
									<span class="label"><?php _e( 'Quantity:', 'woocommerce' ); ?></span>
								<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', '<span class="value">' . sprintf( '%s', $cart_item['quantity'] ) . '</span>', $cart_item, $cart_item_key ); ?>
								</div>
								<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
								<div class="mini-product__item mini-product__price price">
									<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
								</div>
								<?php
					// Add SKU below product name
					if ( !empty($_product->get_sku()) ) { ?>
								<div class="mini-product__item mini-product__id light-copy">
									<span class="label">Product ID #</span>
									<span class="value"><?php echo $_product->get_sku(); ?></span>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php
				}
			}

			do_action( 'woocommerce_review_order_after_cart_contents' );
		?>
	</div><!--/checkout-mini-cart-->
	<div class="checkout-order-totals">
		<div class="order__summary__contents order-totals-table">
		<p class="order__summary__row heading m-no_topmargin heading--small"><?php _e( 'Cart totals', 'woocommerce' ); ?></p>
		<div class="order__summary__row order-subtotal">
			<span class="label"><?php _e( 'Subtotal', 'woocommerce' ); ?></span>
			<span class="value price-amount"><?php wc_cart_totals_subtotal_html(); ?></span>
		</div>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="order__summary__row cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
				<span class="label"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
				<span class="value price-amount"><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
			</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

			<?php wc_cart_totals_shipping_html(); ?>

			<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="order__summary__row fee">
				<span class="label"><?php echo esc_html( $fee->name ); ?></span>
				<span class="value price-amount"><?php wc_cart_totals_fee_html( $fee ); ?></span>
			</div>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<div class="order__summary__row tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<span class="label"><?php echo esc_html( $tax->label ); ?></span>
						<span class="value price-amount"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="order__summary__row tax-total">
					<span class="label"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></span>
					<span class="value price-amount"><?php wc_cart_totals_taxes_total_html(); ?></span>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<div class="order__summary__totals">
			<div class="order__summary__row order-total">
				<span class="label"><?php _e( 'Total', 'woocommerce' ); ?></span>
				<span class="value price-amount bigger order-value"><?php wc_cart_totals_order_total_html(); ?></span>
			</div>
		</div>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>
			</div><!--/order-totals-table-->
	</div><!--/checkout-order-totals-->

</div>