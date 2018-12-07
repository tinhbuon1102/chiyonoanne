<?php
/**
 * Single product Accordion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post, $product;
$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );
$deliver_date = get_post_meta( $post->ID, 'deliver_date', true );
/*$categ = $product->get_categories();
$term = get_term_by ( 'name' , strip_tags($categ), 'product_cat' );
$catdesc = $term->description;*/
$args = array( 'taxonomy' => 'series',);
$terms = wp_get_post_terms($post->ID,'series', $args);
$count = count($terms); 
/*if ( ! $short_description ) {
	return;
}*/
?>
<ul class="accordion product-details acc-parent">
	<?php if ( $short_description || $count > 0  ) { ?>
	<li class="acc-item acc_desc">
		<div class="acc-toggle"><span class="prod-info-heading"><?php _e('Item Info', 'zoa'); ?></span><span class="acc-icon"></span></div>
		<div class="acc-inner prod-detail-content">
			<?php if( get_field('fabric') ): ?>
			<p class="label_fabric"><?php _e('Fabric', 'zoa'); ?></p>
			<p><?php the_field('fabric'); ?></p>
			<?php endif; ?>
			<?php if( have_rows('model_size') || have_rows('model_wearsize') ): ?>
			<p class="label_details has_icon has_icon_left"><i class="oecicon oecicon-measurement-2"></i><?php _e('Model size info', 'zoa'); ?></p>
			<?php endif; ?>
			<?php if( have_rows('model_size') ): ?>
			<ul class="size_model">
				<?php echo('<li class="list_label">'.__( 'Measurement', 'zoa' ).'</li>'); ?>
				<?php
					while ( have_rows('model_size') ) : the_row(); 
					echo('<li>');
					echo('<span class="label">'.the_sub_field('size_title').'</span>');
					echo('<span class="value">'.the_sub_field('size_value').'cm</span>');
					echo('</li>');
					endwhile;
				?>
			</ul>
			<?php endif; ?>
			
			<?php 
			$model_wearsizes = get_field('model_wearsize', $post->ID);
			if($model_wearsizes && !empty($model_wearsizes) ): ?>
			<ul class="size_model size_model_wear">
				<?php echo('<li class="list_label">'.__( 'Wearing Size', 'zoa' ).'</li>'); ?>
				<?php
				foreach ($model_wearsizes as $model_wearsize)
				{
					
					if( isset($model_wearsize['item']) && !empty($model_wearsize['item']) ) {
						$wearCat = get_term($model_wearsize['item']['wear_item_cat']);
						$wearSize = get_term($model_wearsize['item']['wear_item_size']);
						
					echo('<li>');
					echo('<span class="label">'.$wearCat->name.' </span>');
					echo('<span class="value">'.$wearSize->name.__( ' size', 'zoa' ).'</span>');
					echo('</li>');
					}
				}
				?>
			</ul>
			<?php endif; //model_wearsize ?>
			
			<?php if( have_rows('feature_item') ): //Feature ACF ?>
			<p class="label_details has_icon has_icon_left"><i class="oecicon oecicon-star-rate"></i><?php _e('Feature', 'zoa'); ?></p>
			<ul class="detail_additional detail_feature">
			<?php while ( have_rows('feature_item') ) : the_row(); ?>
				<li><?php echo(the_sub_field('item')); ?></li>
			<?php endwhile; ?>
			</ul>
			<?php endif; //feature_item ?>
			
			<?php if( have_rows('recommend_who') ): //Recommend who ACF ?>
			<p class="label_details has_icon has_icon_left"><i class="oecicon oecicon-heart-2-3"></i><?php _e('Recommend to who', 'zoa'); ?></p>
			<ul class="detail_additional detail_feature">
			<?php while ( have_rows('recommend_who') ) : the_row(); ?>
				<li><?php echo(the_sub_field('item')); ?></li>
			<?php endwhile; ?>
			</ul>
			<?php endif; //recommend_who ?>
			
			<?php if( have_rows('no_recommend_who') ): //Not Recommend Point ACF ?>
			<p class="label_details has_icon has_icon_left"><i class="oecicon oecicon-heart-remove"></i><?php _e('難しい体型', 'zoa'); ?></p>
			<ul class="detail_additional detail_feature">
			<?php while ( have_rows('no_recommend_who') ) : the_row(); ?>
				<li><?php echo(the_sub_field('item')); ?></li>
			<?php endwhile; ?>
			</ul>
			<?php endif; //Not Recommend Point ?>
			
			<?php if($short_description || $count > 0) {
					echo '<p class="label_details has_icon has_icon_left"><i class="oecicon oecicon-alert-circle-i"></i>'.__( 'Item description', 'zoa' ).'</i></p>';
				} ?>
			<?php if($short_description) { 
					echo $short_description; 
				} ?>
			<?php if ($count > 0) {
					
	foreach ($terms as $term) {
            echo '<p>';
            echo wpautop($term->description);
            echo '</p>';
	}
} ?>
			
		</div>
	</li>
	<?php } ?>
	<?php 
	if (class_exists('productsize_chart_Public'))
	{
		$chart = new productsize_chart_Public('productsize-chart-for-woocommerce', 123);
		global $post;
		$chart_id=$chart->productsize_chart_id($post->ID);
	}
	if ($chart_id)
	{
	?>
	<li class="acc-item acc_size">
		<div class="acc-toggle"><span class="prod-info-heading"><?php _e('Size Info', 'zoa'); ?></span><span class="acc-icon"></span></div>
		<div class="acc-inner prod-detail-content" id="size_chart_content">
			<?php $chart->productsize_chart_new_product_tab_content();?>
		</div>
	</li>
	<?php }?>
	<li class="acc-item acc_size">
		<div class="acc-toggle"><span class="prod-info-heading"><?php _e('About Delivery', 'zoa'); ?></span><span class="acc-icon"></span></div>
		<div class="acc-inner prod-detail-content">
			<p><?php _e('お客様よりご注文いただきました商品は、ゆうパックにてお届けいたします。', 'zoa'); ?><br/><?php _e('詳しくは', 'zoa'); ?><a id="shipping_info_link" class="link_underline"><?php _e('配送について', 'zoa'); ?></a><?php _e('をご覧ください。', 'zoa'); ?></p>
			<?php if  (!empty( $deliver_date )) { ?>
			<p><?php _e('納期', 'zoa'); ?>：<?php echo esc_html( $deliver_date ) ?></p>
			<?php }else{ ?>
			<p><?php _e('納期', 'zoa'); ?>：<?php _e('受注から約1ヶ月', 'zoa'); ?></p>
			<?php } ?>
		</div>
	</li>
</ul>
<div class="pd__extras light-copy">
	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>
	<span class="pd__extras__productid offset">
		<?php esc_html_e( 'Product ID #', 'woocommerce' ); ?> <?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'woocommerce' ); ?>
	</span>
	<?php endif; ?>
	<p class="pd__extras__moreinfo"><a href="<?php echo home_url('/returns-exchanges'); ?>" class="link_underline"><?php _e('返品・交換', 'zoa'); ?></a>に関して</p>
</div>

<div class="remodal" data-remodal-id="shipping_info_modal" id="shipping_info_modal" role="dialog" data-remodal-options="hashTracking: false, closeOnOutsideClick: true">
  <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
  <div class="remodal_wraper">
  <?php 
  if ( $post = get_page_by_path( 'shipping-info', OBJECT, 'page' ) )
  {
  	echo $post->post_content;
  }
  ?>
  </div>
  <br>
</div>