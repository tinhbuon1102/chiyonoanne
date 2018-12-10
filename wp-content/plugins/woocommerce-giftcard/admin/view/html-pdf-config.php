<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 02/06/2018
 * Time: 14:46
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
?>

<p class = "form-field _pdfwidth_field ">
	<label for = "_pdfwidth"><?php echo __( 'Page width (px)', 'GIFTCARD' ) ?></label>
	<span class = "woocommerce-help-tip" title="<?= __('Set width for pdf templae (px)','GIFTCARD');?>"></span>
	<input type = "text" class = "short" style = "" name = "_pdfwidth" id = "_pdfwidth"
	       value = "<?php echo get_post_meta( $post->ID, '_pdfwidth', true ) ?>" placeholder = "Page width" readonly>
</p>

<p class = "form-field _pdfheight_field ">
	<label for = "_pdfheight"><?php echo __( 'Page height (px)', 'GIFTCARD' ) ?></label>
	<span class = "woocommerce-help-tip" title="<?= __('Set height for pdf templae (px)','GIFTCARD');?>"></span>
	<input type = "text" class = "short" style = "" name = "_pdfheight" id = "_pdfheight"
	       value = "<?php echo get_post_meta( $post->ID, '_pdfheight', true ) ?>" placeholder = "Page height" readonly>
</p>

<p class = "form-field _background_img_field ">
		<label for = "_background_img"><?php echo __( 'Background image for PDF', 'GIFTCARD' ) ?></label>
		<span class = "woocommerce-help-tip" title="<?= __('Set background image for pdf templae (px)','GIFTCARD');?>"></span>
		<input type = "hidden" class = "short" style = "" name = "_background_img" id = "_background_img"
		       value = "<?php echo get_post_meta( $post->ID, '_background_img', true ) ?>" readonly>
		<button class = "set_pdf_images button"><?=__('Choose Image',GIFTCARD_TEXT_DOMAIN)?></button>
		<a href = "#" class = "button" id = "delete_brimage"><?=__('Delete',GIFTCARD_TEXT_DOMAIN)?></a>
</p>

<input type = "hidden" id="pdf_data" name="pdf_data" <?php echo get_post_meta( $post->ID, 'pdf_data', true ) ?>>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('[name="post_title"]').attr('required','true');
    });
</script>