<?php 
function report_gc() {
		$order_id = 713;
		$order = wc_get_order($order_id);
		
		/* @var $order WC_Order */
		if (sizeof ( $order->get_items () ) > 0) {
				
			foreach ( $order->get_items () as $item ) {
				$_product     = apply_filters( 'magenest_giftcard_order_item_product', $order->get_product_from_item( $item ), $item );
		
				/* @var $_product WC_Product */
		
				$giftcard_balance = $_product->get_price();
				$is_giftcard = get_post_meta ( $_product->get_id(), '_giftcard', true );
				if($is_giftcard=='yes') {
					$to_name ='';
					$to_email ='';
					$message ='';
					//$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );
					var_dump($item['item_meta']['_qty'][0]);
					$qty = $item['item_meta']['_qty'][0];
					for ($i  = 0; $i < $qty ; $i++) {
						echo "generate bi <br>";
					}
				}
			}
	}
}
	
//	function report() {
//		$gc = new model\Magenest_Giftcard();
//		$gc->printPdf('Luu Thanh Thuy', 'Nam Hung', '290', 'fafdaf', '12-12-2016', 'Merry Xmas');
//
//	}
	function barcode() {
		set_include_path ( implode ( PATH_SEPARATOR, array ( GIFTCARD_PATH . 'lib', get_include_path () ) ) );
		require_once 'Zend/Loader.php';
		
		require_once 'Zend/Loader/Autoloader.php';
		Zend_Loader_Autoloader::getInstance ();
		if (!class_exists('Zend_Barcode'))
		
			include_once GIFTCARD_PATH . 'lib/Zend/Zend_Barcode.php';
		$barcodeOptions = array('text' => 'abc','factor'=>3);
		// 		// No required options
		
		$rendererOptions = array();
		
		// Draw the barcode in a new image,
		$imageResource = Zend_Barcode::factory(
				'code128', 'image', $barcodeOptions, $rendererOptions)->draw();
		
		// 		$output =  Mage::getBaseDir('var').DS.'
		$output =  GIFTCARD_PATH."barcode.jpg";
			
		imagejpeg($imageResource, $output);
			
		list($width, $height) = getimagesize($output);
		error_log('barcode width '. $width );
		error_log('barcode $height '. $height );
	}
	function report_gcx() {
		add_filter ( 'wp_headers', array (
				$this,
				'add_pdf_header' 
		) );
		set_include_path ( implode ( PATH_SEPARATOR, array (
				GIFTCARD_PATH . 'lib',
				get_include_path () 
		) ) );
		require_once 'Zend/Loader.php';
		
		require_once 'Zend/Loader/Autoloader.php';
		Zend_Loader_Autoloader::getInstance ();
		if (!class_exists('Zend_Pdf'))
		
		include_once GIFTCARD_PATH . 'lib/Zend/Pdf.php';
		$pdf = new Zend_Pdf ();
		$bold_font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA_BOLD );
		$font_regular = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA );
		$font = Zend_Pdf_Font::fontWithName ( Zend_Pdf_Font::FONT_HELVETICA );
		
		$page_size = '306:214';
		$page = $pdf->newPage ( $page_size );
		$image = GIFTCARD_PATH .'assets/giftcard.png';
		
		if (is_file ( $image )) {
			$image = Zend_Pdf_Image::imageWithPath ( $image );
			// $page->drawImage($image, 0,$pageheight , $pagewidth, 0);
			$page->drawImage ( $image, 0, 0, 306, 214 );
		}
		
		$page->setFont ( $font, 8 );
		$page->setFillColor ( Zend_Pdf_Color_Html::color ( 'black' ) )->drawText ( 'hung', '10', '70', 'UTF-8' );
		$pdf->pages [] = $page;
		
		// $file = $pdf->render ();
		$pdfData = $pdf->render ();
		$pdf->save(GIFTCARD_PATH .'giftcard.pdf');
		//@wp_mail($to, $subject, $message);
		// header("Content-Disposition: inline; filename=result.pdf");
		// header("Content-type: application/x-pdf");
		//echo $pdfData;
		//get_attached_file();
	}
	
	function add_pdf_header($headers) {
		error_log('======>modify header');
		$headers['Content-type'] = 'application/x-pdf';
		$headers['CoContent-Disposition'] = 'attachment; filename="downloaded.pdf';
		//Content-Disposition: attachment; filename="downloaded.pdf"
		//$headers['Content-Disposition'] = 'inline; filename=result.pdf';
		//$headers["Cache-Control"]="no-cache, must-revalidate";
		return $headers;
	}