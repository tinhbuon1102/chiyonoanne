<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 02/08/2018
 * Time: 15:12
 */
if (!defined('ABSPATH')){
    exit();
}
?>
<div style="display: none;" type = "hidden" id="text_data"></div>
<p class = "form-field center-block">
    <button class = "add-shortcode button" data-shortcode="from_name" style="margin: 3px;"><?=__('Sender',GIFTCARD_TEXT_DOMAIN)?></button>
    <button class = "add-shortcode button" data-shortcode="to_name" style="margin: 3px;"><?=__('Friend Name',GIFTCARD_TEXT_DOMAIN)?></button>
    <button class = "add-shortcode button" data-shortcode="to_email" style="margin: 3px;"><?=__('Friend Mail',GIFTCARD_TEXT_DOMAIN)?></button>
    <button class = "add-shortcode button" data-shortcode="message" style="margin: 3px;"><?=__('Message send to friend',GIFTCARD_TEXT_DOMAIN)?></button>
    <button class = "add-shortcode button" data-shortcode="code" style="margin: 3px;"><?=__('Gift Card code',GIFTCARD_TEXT_DOMAIN)?></button>
    <button class = "add-shortcode button" data-shortcode="balance" style="margin: 3px;"><?=__('Gift Card balance',GIFTCARD_TEXT_DOMAIN)?></button>
    <button class = "add-shortcode button" data-shortcode="expired_at" style="margin: 3px;"><?=__('Gift Card Expiry Date',GIFTCARD_TEXT_DOMAIN)?></button>
    <button class = "add-shortcode button" data-shortcode="store_url" style="margin: 3px;"><?=__('Store URL',GIFTCARD_TEXT_DOMAIN)?></button>
</p>