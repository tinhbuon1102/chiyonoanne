<?php
namespace admin;
class EmailTemplateSetting{
    public function __construct(){
        add_action('add_meta_boxes', array($this, 'boxes_pdf_settings'));
    }
    public function boxes_pdf_settings(){
        add_meta_box( 'email_giftcard-shortcode', __( 'ShortCode', 'GIFTCARD' ), array($this, 'email_giftcard_shortcode'), 'email_giftcard', 'side' );
    }
    public function email_giftcard_shortcode(){
        include_once GIFTCARD_PATH . 'admin/view/html-email-shortcode.php';
    }
}