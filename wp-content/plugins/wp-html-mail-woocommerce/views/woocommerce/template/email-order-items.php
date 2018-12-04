<?php
/**
 * Email Order Items
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $haet_mail_options;
global $haet_mail_plugin_options;
global $current_mail_template;


if( $sent_to_admin ){
	if( $haet_mail_plugin_options['woocommerce']['thumbs_admin'] )
		$show_image = true;
	else
		$show_image = false;
}else{
	if( $haet_mail_plugin_options['woocommerce']['thumbs_customer'] )
		$show_image = true;
	else
		$show_image = false;
}

if( isset($haet_mail_plugin_options['woocommerce']['thumb_size']) )
	$image_size = array($haet_mail_plugin_options['woocommerce']['thumb_size'],$haet_mail_plugin_options['woocommerce']['thumb_size']);


foreach ( $items as $item_id => $item ) :
	$_product     = $item->get_product();

	if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
		?>
		<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
			<td class="col-product">
				<?php if ( $show_image ) { ?>

				<table class="product-left-col" >
					<tr>

						<td class="image-wrap" style="width:<?php echo $image_size[0];?>px">
							<?php
								if( $_product->get_image_id() ){
									$image = wp_get_attachment_image_src( $_product->get_image_id(), $image_size );
									$image_src = $image[0];
									$current_image_size = array($image[1],$image[2]);
								}else{
									$image_src = wc_placeholder_img_src();
									$current_image_size = $image_size;
								}
								// Show title/image etc
								echo apply_filters( 'woocommerce_order_item_thumbnail', '<img src="' . $image_src .'" alt="' . __( 'Product Image', 'woocommerce' ) . '" height="' . esc_attr( $current_image_size[1] ) . '" width="' . esc_attr( $current_image_size[0] ) . '" style="vertical-align:middle; margin-right: 10px;" />', $item );
							?>							
						</td>
						<td class="product-name" style="vertical-align:middle;">
				<?php } 
					// Product name
					echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );

					// SKU
					if ( $show_sku && is_object( $_product ) && $_product->get_sku() ) {
						echo ' (#' . $_product->get_sku() . ')';
					}

					// allow other plugins to add additional product information here
					do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

					$item_meta = wc_display_item_meta( $item, array( 
					        'before'    => '',
					        'after'     => '',
					        'separator' => '<br/>',
					        'echo'      => false,
					        'autop'     => false
					    ) );
					echo '<div class="variation">' . strip_tags( $item_meta, '<br><br/>' ) . '</div>';

					if ( $show_download_links ) {
						wc_display_item_downloads( $item );
					}

					// allow other plugins to add additional product information here
					do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );

				if ( $show_image ) { ?>
							</td>
						</tr>
					</table>
				<?php } ?>
			</td>
			<td class="col-quantity">
				<?php echo wp_kses_post( apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ) ); ?>
			</td>
			<td class="col-price">
				<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); ?>
			</td>
		</tr>
		<?php
	}

	if ( $show_purchase_note && is_object( $_product ) ):
		$purchase_note = $_product->get_purchase_note();
		if ( $purchase_note ) : ?>
			<tr>
				<td colspan="3" style="text-align:left; vertical-align:middle;">
					<?php
					echo wp_kses_post( wpautop( do_shortcode( $purchase_note ) ) );
					?>
				</td>
			</tr>
		<?php endif; ?>
	<?php endif; ?>

<?php endforeach; ?>
<?php if( isset( $current_mail_template ) ) $current_mail_template = ''; ?> 


