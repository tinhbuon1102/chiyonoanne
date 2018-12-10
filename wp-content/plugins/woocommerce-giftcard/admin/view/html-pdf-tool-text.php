<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 05/06/2018
 * Time: 16:38
 */
if (!defined('ABSPATH')){
	exit();
}

?>
<div style="display: none;" type = "hidden" id="text_data"></div>
<p class = "form-field center-block">
	<button class = "add-text button"><?=__('Add new',GIFTCARD_TEXT_DOMAIN)?></button>
</p>
<p class = "form-field">
	<label for = "_text"><?php echo __( 'Text', 'GIFTCARD' ) ?></label>
	<span class = "woocommerce-help-tip" title="<?= __('Add text for pdf template','GIFTCARD');?>"></span>
	<input type = "text" class = "short" style = "" name = "_text" id = "_text_content" value = "" placeholder = "Text content">
</p>
<p class = "form-field">
	<label for = "_text"><?php echo __( 'Color', 'GIFTCARD' ) ?></label>
	<span class = "woocommerce-help-tip" title="<?= __('Add text color for pdf template','GIFTCARD');?>"></span>
	<input type = "color" class = "short" style = "" name = "_text" id = "_text_color" value = "" placeholder = "Text content">
</p>
<p class = "form-field">
    <label for = "_text"><?php echo __( 'Font', 'GIFTCARD' ) ?></label>
    <span class = "woocommerce-help-tip" title="<?= __('Add font for text in pdf template','GIFTCARD');?>"></span>
    <select name="_text" id="_text_font">
        <option value="" disabled selected><?=__('Select value',GIFTCARD_TEXT_DOMAIN)?></option>
        <option value="Acme-Regular" style="font-family: 'Acme';"><?=__('Acme',GIFTCARD_TEXT_DOMAIN)?></option>
        <option value="AlexBrush-Regular" style="font-family: 'Alex Brush';"><?=__('Alex Brush',GIFTCARD_TEXT_DOMAIN)?></option>
        <option value="AnonymousPro-Regular" style="font-family: 'Anonymous Pro';"><?=__('Anonymous Pro',GIFTCARD_TEXT_DOMAIN)?></option>
        <option value="Baumans-Regular" style="font-family: 'Baumans';"><?=__('Baumans',GIFTCARD_TEXT_DOMAIN)?></option>
        <option value="Galada-Regular" style="font-family: 'Galada';"><?=__('Galada',GIFTCARD_TEXT_DOMAIN)?></option>
    </select>
</p>
<p class = "form-field">
	<label for = "_text_size"><?php echo __( 'Size', 'GIFTCARD' ) ?></label>
	<span class = "woocommerce-help-tip" title="<?= __('Add size for text in pdf template','GIFTCARD');?>"></span>
	<input type = "number" class = "short" name = "_text" id = "_text_size" readonly style="border:0; color:#f6931f; font-weight:bold;">
	<div id="slider-range-min"></div>
</p>