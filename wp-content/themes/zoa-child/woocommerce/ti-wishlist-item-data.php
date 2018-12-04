<?php
/**
 * The Template for displaying variation product data.
 *
 * @version             1.0.0
 * @package           TInvWishlist\Template
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<div class="mini-product__item variation product__attribute_row">
	<?php foreach ( $item_data as $data ) : ?>
	<div class="mini-product__item mini-product__attribute">
		<span class="label variation-<?php echo sanitize_html_class( $data['key'] ); ?>"><?php echo wp_kses_post( $data['key'] ); ?>: </span>
		<span class="value variation-<?php echo sanitize_html_class( $data['key'] ); ?>"><?php echo wp_kses_post( $data['display'] ); ?></span>
	</div>
	<?php endforeach; ?>
</div>
