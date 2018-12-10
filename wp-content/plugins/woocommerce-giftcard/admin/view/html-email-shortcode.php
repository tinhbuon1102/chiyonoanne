<?php
if (!defined('ABSPATH')){
    exit();
}
?>
<div style="display: none;" type = "hidden" id="text_data"></div>
<p class = "form-field center-block">
    <p><b>{{from_name}}</b><br/><?=__('replace with name of user who send the giftcard',GIFTCARD_TEXT_DOMAIN)?></p>
    <p><b>{{to_name}}</b><br/> <?=__('replaced by username received gift card',GIFTCARD_TEXT_DOMAIN)?></p>
    <p><b>{{to_email}}</b><br/> <?=__('replace with email of user who send the giftcard',GIFTCARD_TEXT_DOMAIN)?></p>
    <p><b>{{message}}</b><br/> <?=__('replaced by message of user who send the giftcard',GIFTCARD_TEXT_DOMAIN)?></p>
    <p><b>{{code}}</b><br/> <?=__('replace with code of giftcard',GIFTCARD_TEXT_DOMAIN)?></p>
    <p><b>{{balance}}</b><br/> <?=__('replace with balance of giftcard',GIFTCARD_TEXT_DOMAIN)?></p>
    <p><b>{{expired_at}}</b><br/> <?=__('replaced with giftcard expired date',GIFTCARD_TEXT_DOMAIN)?></p>
    <p><b>{{product_image}}</b><br/> <?=__('replace with image of the product',GIFTCARD_TEXT_DOMAIN)?></p>
    <p><b>{{product_name}}</b><br/> <?=__('replace with name of the product',GIFTCARD_TEXT_DOMAIN)?></p>
    <p><b>{{store_url}}</b><br/> <?=__('replace with url of the store',GIFTCARD_TEXT_DOMAIN)?></p>
    <p><b>{{store_name}}</b><br/> <?=__('replace with name of the store',GIFTCARD_TEXT_DOMAIN)?></p>
</p>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('[name="post_title"]').attr('required','true');
    });
</script>