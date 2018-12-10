<?php
/**
 * Created PDF
 * How to use: initialization object with pdf_id or pdfData. After that, set shortCodeData. Finally, exportPdf.
 */

namespace model;

set_include_path( implode( PATH_SEPARATOR, array( GIFTCARD_PATH . 'lib', get_include_path() ) ) );
require_once 'Zend/Loader.php';
require_once 'Zend/Loader/Autoloader.php';
\Zend_Loader_Autoloader::getInstance();
if ( ! class_exists( 'Zend_Pdf' ) ) {
	include_once GIFTCARD_PATH . 'lib/Zend/Pdf.php';
}

if ( ! class_exists( 'Zend_Barcode' ) ) {
	include_once GIFTCARD_PATH . 'lib/Zend/Zend_Barcode.php';
}

class PdfCreate extends \Zend_Pdf {

	private $pdfContents;
	private $pdfDatas;
	public $shortCodeData;


	// init a new page pdf with background, width, heigh, metadata...
	public function __construct(  )
	{
		parent::__construct();

	}

	public function createPdf($pdf_id = 0, $pdfDatas = array()){
        // get pdfData
        $flag = false;
        try{
            if ( $pdf_id != 0 && $pdf_id != "") {
                //			$pdf_data = get_post($pdf_id);
                $pdfDatas['background_image'] = get_attached_file( get_post_meta( $pdf_id, '_background_img', true ) );
                $pdfDatas['pdfwidth']         = get_post_meta( $pdf_id, '_pdfwidth', true );
                $pdfDatas['pdfheight']        = get_post_meta( $pdf_id, '_pdfheight', true );
                $pdfDatas['pdf_id']           = $pdf_id;
                $this->pdfContents            = get_post_meta( $pdf_id, 'pdf_data', true );
                $this->pdfDatas = $pdfDatas;
                $this->configPdf($pdfDatas);
                $flag = true;
            }
        } catch (\ErrorException $e){
            error_log(print_r($e,true));
        };
        return $flag;
    }


	// config pdf metadata
	public function configPdf( $pdfData )
	{
		$pdfContent = $this->newPage( $pdfData['pdfwidth'] . ':' . $pdfData['pdfheight'] );
		// set document info and metadata
		$this->properties['Title']  = isset($pdfData['title'])?$pdfData['title']:'Title';
		$this->properties['Author'] = isset($pdfData['author'])?$pdfData['author']:'admin';

		// paint background
		$pdfContent->drawImage( \Zend_Pdf_Image::imageWithPath( $pdfData['background_image'] ), 0, 0, $pdfData['pdfwidth'], $pdfData['pdfheight'] );
		$pdf_contents = json_decode( $this->pdfContents, true );

		foreach ( $pdf_contents as $pdf_content ) {
			switch ( $pdf_content['type_area'] ) {
				case 'textArea':
					self::drawText( $pdfContent, $pdf_content );
					break;
                case 'shortcodeArea':
                    self::drawText( $pdfContent, $pdf_content );
                    break;
				case 'imageArea':
					self::drawImage( $pdfContent, $pdf_content );
					break;
				case 'qrCode':
					self::drawQrcode( $pdfContent, $pdf_content );
					break;
			}
		}


		$this->pages[] = $pdfContent;
	}

	public function exportPdf()
	{

		$product_id = isset($this->shortCodeData['{{product_id}}'])?$this->shortCodeData['{{product_id}}']:'';
		$order_id   = isset($this->shortCodeData['{{order_id}}'])?$this->shortCodeData['{{order_id}}']:'';
		if ( empty( $product_id ) ) {
		    //pdf_id
			$fileName = "Giftcard-".$this->pdfDatas['pdf_id'];
		} else {
			$fileName = get_post_meta( $product_id, 'file_name_giftcard', true );
		}

		$wp_uploads     = wp_upload_dir();
		$wp_uploads_dir = $wp_uploads['path'];
		$filePath       = $wp_uploads_dir . '/' . $fileName . '-' . $order_id . '.pdf';
		$this->save( $filePath );

		// insert attachment

		// Check the type of file. We'll use this as the 'post_mime_type'.
		$filetype = wp_check_filetype( basename( $filePath ), null );

		// Get the path to the upload directory.
		$wp_upload_dir = wp_upload_dir();

		// Prepare an array of post data for the attachment.
		$attachment = array(
			'guid'           => $wp_upload_dir['url'] . '/' . basename( $filePath ),
			'post_mime_type' => $filetype['type'],
			'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filePath ) ),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Insert the attachment.
		$attach_id = wp_insert_attachment( $attachment, $filePath, $product_id );

		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filePath );
		wp_update_attachment_metadata( $attach_id, $attach_data );

//		set_post_thumbnail( $product_id, $attach_id );

		return $attach_id;
	}

	private function drawText( &$pdfContent, $textContent )
	{
		$color = new \Zend_Pdf_Color_Html( $textContent['textArea']['color'] );
		$text  = $textContent['textArea']['content'];
		if (!empty($textContent['textArea']['fontPath'])){
            $font_path = GIFTCARD_PATH . 'lib/font/' .$textContent['textArea']['fontPath']. '.ttf';
        } else {
            $font_path  = GIFTCARD_PATH . 'lib/font/AnonymousPro-Regular.ttf';
        }
        $font  = \Zend_Pdf_Font::fontWithPath($font_path);
        $size  = $textContent['textArea']['size'];

		//replace shortcode
		if ( ! empty( $this->shortCodeData ) ) {
			$text = strtr( $text, $this->shortCodeData );
		}

		$text_of_line = '';
		$x_cor  = $textContent['x'];
		$y_cor = $this->pdfDatas['pdfheight'] - $textContent['y'] - $size * 0.75; // 1px = 0.75 pt
        $linespacing = 10;
        $words = new \CachingIterator(new \ArrayIterator(explode(' ', $text)));
		foreach ( $words as $word){
		    $text_of_line .= $word . " ";
		    // check lenght > width or last word of the text will print to pdf
            $width_of_text = $this->get_width_text_line($text_of_line, $font_path, $size * 0.75, $linespacing);
            if ($width_of_text > $textContent['width'] || !$words->hasNext()){
                $pdfContent->setFont( $font, $size )
                    ->setFillColor( $color )
                    ->drawText( $text_of_line, $x_cor , $y_cor , 'UTF-8' );
                $text_of_line = '';
                $y_cor -= ($linespacing + $size * 0.75);
            }
        }

	}

    /**
     * @param $text
     * @param $font_path
     * @param $size
     * @return the width of text line
     */
	private function get_width_text_line($text, $font_path, $size = 14, $linespacing = 1){
        $corners = imageftbbox($size, 0, $font_path, $text, array("linespacing" => $linespacing));
        $width = $corners[0] - $corners[2];
        return abs($width) ;
    }

	private function drawImage( &$pdfContent, $imageContent )
	{
		$image = \Zend_Pdf_Image::imageWithPath( get_attached_file( $imageContent['imgArea']['attachment_id'] ) );
		$pdfContent->drawImage( $image, $imageContent['x'], $this->pdfDatas['pdfheight'] - $imageContent['y'] - $imageContent['height'], $imageContent['x'] + $imageContent['width'], $this->pdfDatas['pdfheight'] - $imageContent['y'] );
	}

	private function drawQrcode( &$pdfContent, $qrcodeContent )
	{
		$qrSize  = round( $qrcodeContent['width'] ) . 'x' . round( $qrcodeContent['width'] );
		$qr_code = urlencode( $this->shortCodeData['{{code}}'] );
		$qr_url  = "https://chart.googleapis.com/chart?chs={$qrSize}&cht=qr&chl=$qr_code";

		try {
			$qr_path = GIFTCARD_PATH . 'upload/qrtemp.png';
			copy( $qr_url, $qr_path );

			$image = \Zend_Pdf_Image::imageWithPath( $qr_path );

			$pdfContent->drawImage( $image, $qrcodeContent['x'], $this->pdfDatas['pdfheight'] - $qrcodeContent['y'] - $qrcodeContent['height'], $qrcodeContent['x'] + $qrcodeContent['width'], $this->pdfDatas['pdfheight'] - $qrcodeContent['y'] );
			unlink( $qr_path );
		} catch ( Exception $e ) {
			error_log( $e->getMessage() );
		}
	}


	/**
	 * @param mixed $shortCodeData
	 */
	public function setShortCodeData( $shortCodeData )
	{
		$this->shortCodeData = $shortCodeData;
	}

}