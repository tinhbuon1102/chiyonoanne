<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */
if ( ! defined('ABSPATH') )
{
	exit();
}
global $woocommerce;
$cart_url = $woocommerce->cart->get_cart_url();
wc_print_notices();

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() )
{
	echo apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce'));
	return;
}

?>
<form id="checkout" name="checkout" method="post" class="checkout woocommerce-checkout form--stepped" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
	<div class="row flex-justify-between">
	<?php if ( $checkout->get_checkout_fields() ) : ?>

		

		<div class="order--checkout__col--form col-md-7 col-xs-12">
			<fieldset class="step" id="step-1">
				<?php if (isHideShippingByMailGiftCard()) {?>
				<label class="no-shipping-text" data-card="<?php echo json_encode(getCartGiftCardData())?>">
					<?php _e('Just go to next step because your item doesn\'t need shipping info.', 'zoa');?>
				</label>
				<?php }?>
				<div class="step-1-content" style="<?php echo isHideShippingByMailGiftCard() ? 'display: none' : ''?>">
			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
				<div class="order--checkout__form__section legend_wrap">
						<legend class="legend-order--checkout__form__section">
							<h2 class="heading heading--xlarge checkout_heading">
								<span class="order--checkout__title-break"><?php _e( 'Delivery Details', 'zoa' ); ?></span>
							</h2>
						</legend>
						<p class="form__description p6"><?php _e( 'Please fill in the information below:', 'zoa' ); ?></p>
					</div>
					<div class="order--checkout--row">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
					<div class="order--checkout--row">
						<div class="woocommerce-additional-fields">
	<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>

	<?php if ( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) ) : ?>

		<?php if ( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ) : ?>

			<h3><?php _e( 'Additional information', 'woocommerce' ); ?></h3>

		<?php endif; ?>

		<div class="woocommerce-additional-fields__field-wrapper">
			<?php foreach ( $checkout->get_checkout_fields( 'order' ) as $key => $field ) : ?>
				<?php woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
			<?php endforeach; ?>
		</div>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
</div>
					</div>
				</div>
				<div class="order--checkout__footer input-list">
					<input type="button" class="button js-prev" value="<?php _e( 'Previous', 'zoa' ); ?>" />
					<input type="button" class="button button--primary js-next" value="<?php _e( 'Next', 'zoa' ); ?>" />
				</div>
				<!--/order--checkout__footer-->
			</fieldset>
			<fieldset class="step" id="step-2">
				<div class="order--checkout__form__section legend_wrap">
					<legend class="legend-order--checkout__form__section">
						<h2 class="heading heading--xlarge checkout_heading">
							<span class="order--checkout__title-break"><?php _e( 'Billing Details', 'zoa' ); ?></span>
						</h2>
					</legend>
					<p class="form__description p6"><?php _e( 'Please fill in the information below:', 'zoa' ); ?></p>
				</div>
				<div class="order--checkout--row">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>
				<div class="order--checkout__footer input-list">
					<input type="button" class="button js-prev" value="<?php _e( 'Previous', 'zoa' ); ?>" />
					<input type="button" class="button button--primary js-next" value="<?php _e( 'Next', 'zoa' ); ?>" />
				</div>
				<!--/order--checkout__footer-->
			</fieldset>
			<fieldset class="step" id="step-3">
				<div class="order--checkout__form__section legend_wrap">
					<legend class="legend-order--checkout__form__section">
						<h2 class="heading heading--xlarge checkout_heading">
							<span class="order--checkout__title-break"><?php _e( 'Payment', 'zoa' ); ?></span>
						</h2>
					</legend>
				</div>
				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
				
			</fieldset>
		</div>

		

	<?php endif; ?>
	<div id="secondary" class="order--checkout__col--summary summary col-lg-4 col-md-5 col-xs-12 toggle--active">
			<h2 class="order--checkout__summary__heading heading heading--xlarge serif flex-justify-between icon--plus toggle--active"><?php _e( 'Order Summary', 'zoa' ); ?></h2>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div class="order__summary order--checkout__summary toggle--active">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>
			<div class="order--checkout__actions--top flex-justify-between">
				<span class="heading heading--small"><?php _e( 'Products', 'woocommerce' ); ?> (<?php echo WC()->cart->get_cart_contents_count();?>)</span>
				<a class="cta cta--underlined txt--upper section-header-note" href="<?php echo $cart_url;?>"><?php _e( 'Edit Cart', 'zoa' ); ?></a>
			</div>
	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
	</div>
		<!--/#secondary-->
	</div>
	<!--/.row-->
</form>
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
