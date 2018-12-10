<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 20/07/2018
 * Time: 20:01
 */
namespace model;
class SendGiftCard
{
    private $gc_id;
    private $data_shortcode;

    /**
     * @param mixed $pdf_path
     */
    public function setDataShortcode($data_shortcode)
    {
        $this->$data_shortcode = $data_shortcode;
    }

    /**
     * @param mixed $gc_id
     */
    public function setGcId($gc_id)
    {
        $this->gc_id = $gc_id;
    }

    public function __construct()
    {

    }

    public function SendGiftCard(){
        // get info gc
        $gc = get_post($this->gc_id, ARRAY_A);

        // prepare email
        $headers    = array();
        $headers [] = "Content-Type: text/html";
        $headers [] = 'From: ' . get_option( 'woocommerce_email_from_name' ) . ' <' . get_option( 'woocommerce_email_from_address' ) . '>';
        $to         = $gc['send_to_name'] . '<' . $gc['send_to_email'] . '>';



		$datas = $this->data_shortcode;
		array_merge($datas, array(
			'to_name'       => $gc['send_to_name'],
			'to_email'      => $gc['send_to_email'],
			'message'       => $gc['message'],
			'code'          => $gc['code'],
			'balance'       => $gc['balance'],
			'expired_at'    => $gc['expired_at'],
			'store_url'     => get_permalink( wc_get_page_id( 'shop' ) ),
			'store_name'    => get_bloginfo( 'name' ),
		));

        $shortCodes = [];
	    $shortCodes = \model\EmailTemplate::getShortcode($shortCodes, $datas);

	    // get pdf
	    $pdf = new \model\PdfCreate();

        // get email template
	    $email_template = \model\EmailTemplate::getMailTemplate($gc['email_template_id']);
	    $subject = $email_template['subject'];
	    $content = $email_template['content'];
        $body        = strtr( $content, $shortCodes );

        // send email
        $attach_pdf_option = get_option( 'magenest_giftcard_to_pdf', 'yes' );
        if ( $attach_pdf_option == 'yes' ) {
            $attachments    = array();
            $attachments [] = $this->pdf_path;
            add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
            if (wp_mail( $to, $subject, $body, $headers, $attachments )){
                update_post_meta( $gc['product_id'], 'status', 1 );
            }
        } else {
            add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
            if(wp_mail( $to, $subject, $body, $headers )){
                update_post_meta( $gc['product_id'], 'status', 1 );
            }
        }
    }
}