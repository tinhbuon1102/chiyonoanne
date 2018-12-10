<?php
namespace admin;
class MagenestGiftcardAdmin {
	public function __construct() {
//		add_action('admin_notices',         array($this, 'admin_notices'));
//		add_action ( 'woocommerce_admin_order_totals_after_discount',  array($this,'display_giftcard_on_order' ));//woocommerce_admin_order_item_headers
		
	}
	public static function display_giftcard_on_order ( $order_id ) {
		global $wpdb;
		$tbl = $wpdb->prefix.'postmeta';
		$order = new \WC_Order($order_id);
		$giftcard_code = get_post_meta($order_id , 'giftcard_code' , true);
		$giftcard_discount = get_post_meta($order_id , 'giftcard_discount' , true);
		if( $giftcard_discount > 0 ) {
			?>
			<tr>
				<td class="label"><?php _e( 'Gift Card Payment(' .$giftcard_code.')' , 'woocommerce' ); ?>:</td>
				<td class="giftcardTotal">
					<div class="view"><?php echo '-'. wc_price( $giftcard_discount ); ?></div>
				</td>
			</tr>
			<?php
		}

	}
	public static function printpdf_action() {
		global $typenow;
		$post_type = $typenow;
        if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'printpdf' && isset( $_REQUEST['id'] ) ) {
			// get the action
			$pdf_file_path = 	self::adminExportPdf($_REQUEST['id']) ;
		    if(!empty($pdf_file_path)){
                wp_redirect($pdf_file_path);
            }else{

            }
			exit();
		}
	}
	public static function admin_notices() {
		global $post_type, $pagenow;
		if(  $post_type == 'shop_giftcard' && isset($_REQUEST['printpdf'])) {
			$message = sprintf( _n( 'Pdf exported.', '%s posts exported.', $_REQUEST['printpdf'] ), number_format_i18n( $_REQUEST['printpdf'] ) );
			$message = sprintf( 'PDF file is available for <a href="%s "> Download</a>', $_REQUEST['file']  );
			echo "<div class=\"updated\"><p>{$message}</p></div>";
		}
	}
	public static function adminExportPdf($post_id){
        $pdf_url = [];
        $pdf_template_id = get_post_meta( $post_id, 'gc_pdf_template_id', true );
        if($pdf_template_id || $pdf_template_id != ""){

        }else{
            $args = array( 'post_type' => 'pdf_settings');
            $loops =  get_posts( $args );
            foreach ($loops as $loop){
                $pdf_template_id = $loop->ID;
            }
        }
        $from_name    = "";
        $to_name = get_post_meta( $post_id, 'gc_send_to_name', true );
        $to_email = get_post_meta( $post_id, 'gc_send_to_email', true );
        $to_message = get_post_meta( $post_id, 'gc_message', true );
        $code = get_post_meta( $post_id, 'gc_code', true );
        $balance = get_post_meta( $post_id, 'gc_balance', true );
        $format            = 'Y-m-d';
        $expire_at = get_post_meta( $post_id, 'gc_expired_at', true );
        $expire_at = new \DateTime($expire_at);
        $expire_at = $expire_at->format($format);
        $product_id    = get_post_meta( $post_id, 'product_id', true );
        $product_name  = get_the_title( $product_id );
        $product_image = get_the_post_thumbnail( $product_id, 'medium' );

        $datas       = array(
            'from_name'     => $from_name,
            'to_name'       => $to_name,
            'to_email'      => $to_email,
            'message'       => $to_message,
            'code'          => $code,
            'balance'       => $balance,
            'expired_at'    => $expire_at,
            'product_image' => $product_image,
            'product_name'  => $product_name,
            'store_url'     => get_permalink( wc_get_page_id( 'shop' ) ),
            'store_name'    => get_bloginfo( 'name' ),
        );
        $replaces = [];
        $replaces = \model\EmailTemplate::getShortcode($replaces, $datas);
        // get pdf
        $pdf = new \model\PdfCreate();
        $pdf->setShortCodeData($replaces);
        $pdf->createPdf($pdf_template_id);
        $pdfAttachmentId = $pdf->exportPdf();
        $pdf_url = wp_get_attachment_url($pdfAttachmentId);
        return $pdf_url;
    }
	
}
//return new Magenest_Giftcard_Admin();