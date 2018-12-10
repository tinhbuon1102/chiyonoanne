<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 02/06/2018
 * Time: 13:40
 */
namespace admin;

class PdfSettings {
	public function __construct()
	{
		add_action('init', array($this, 'create_pdf_settings'));
		add_action('add_meta_boxes', array($this, 'boxes_pdf_settings'));
		add_action('save_post_pdf_settings', array($this, 'save_pdf') );
		add_action('admin_post_preview_pdf', array(__CLASS__, 'preview_pdf'));
		add_action('post_submitbox_minor_actions', array($this, 'button_download_pdf'));
	}

	public function create_pdf_settings() {
		// register pdf template
		register_post_type( 'pdf_settings', array(
			'labels'              => array(
				'name'               => __( 'PDF SETTINGS', 'GIFTCARD' ),
				'singular_name'      => __( 'pdf_settings', 'GIFTCARD' ),
				'menu_name'          => _x( 'Pdf Templates', 'Admin menu name', 'GIFTCARD' ),
				'add_new'            => __( 'Add PDF template', 'GIFTCARD' ),
				'add_new_item'       => __( 'Add PDF template', 'GIFTCARD' ),
				'edit'               => __( 'Edit', 'GIFTCARD' ),
				'edit_item'          => __( 'Edit PDF template', 'GIFTCARD' ),
				'new_item'           => false,
				'view'               => __( 'View PDF template', 'GIFTCARD' ),
				'view_item'          => __( 'View PDF template', 'GIFTCARD' ),
				'search_items'       => __( 'Search PDF template', 'GIFTCARD' ),
				'not_found'          => __( 'No PDF template found', 'GIFTCARD' ),
				'not_found_in_trash' => __( 'No PDF template found in trash', 'GIFTCARD' ),
				'parent'             => __( 'Parent PDF template', 'GIFTCARD' )
			),
			'public'              => true,
			'publicly_queryable'  => false,
			'exclude_from_search' => false,
			'has_archive'         => true,
			'show_in_menu'        => 'edit.php?post_type=shop_giftcard',
			'hierarchical'        => true,
			'supports'            => array( 'title', 'thumbnail' ),

		) );
	}

	public function boxes_pdf_settings(){
		add_meta_box( 'pdf-area', __( 'PDF View', 'GIFTCARD' ), array($this, 'pdf_area'), 'pdf_settings' );
		add_meta_box( 'pdf-config', __( 'PDF Config', 'GIFTCARD' ), array($this, 'pdf_config'), 'pdf_settings', 'side' );
		add_meta_box( 'pdf-tool-text', __( 'Text settings', 'GIFTCARD' ), array($this, 'pdf_tool_text'), 'pdf_settings', 'side' );
		add_meta_box( 'pdf-tool-shortcode', __( 'ShortCode', 'GIFTCARD' ), array($this, 'pdf_tool_shortcode'), 'pdf_settings', 'side' );
		add_meta_box( 'pdf-tool-img', __( 'Image settings', 'GIFTCARD' ), array($this, 'pdf_tool_img'), 'pdf_settings', 'side' );
		add_meta_box( 'pdf-tool-qrcode', __( 'Qrcode', 'GIFTCARD' ), array($this, 'pdf_tool_qrcode'), 'pdf_settings', 'side' );
	}

	public function pdf_area($post, $metabox){
		?>
		<div id="pdf_area" style="height: 400px;background-color: #dededa">
			<img src = "" alt = "" style="display: block;width: 100%; height: 100%;overflow:hidden;"/>
		</div>
		<?php

		$pdf_data = get_post_meta( $post->ID, 'pdf_data', true );
		$img_id = get_post_meta( $post->ID, '_background_img', true );

		// load font
        wp_enqueue_style('font-acme', '//fonts.googleapis.com/css?family=Acme');
        wp_enqueue_style('font-baumans', '//fonts.googleapis.com/css?family=Baumans');
        wp_enqueue_style('font-anonymous', '//fonts.googleapis.com/css?family=Anonymous Pro');
        wp_enqueue_style('font-galada', '//fonts.googleapis.com/css?family=Galada');
        wp_enqueue_style('font-alex-brush', '//fonts.googleapis.com/css?family=Alex Brush');

		// convert pdf data to js
		wp_localize_script( 'gc_image_background_pdf', 'pdfData', $pdf_data );
		wp_localize_script( 'gc_image_background_pdf', 'imgSrc', wp_get_attachment_image_src($img_id, 'full')[0] );

        wp_enqueue_style('gc_style_ui');
        wp_enqueue_script('jquery-select-area');
		wp_enqueue_script('gc_image_background_pdf');
		wp_enqueue_script('gc_pdf_config');

	}

	public function pdf_config($post, $metabox){
		include_once GIFTCARD_PATH . 'admin/view/html-pdf-config.php';
	}

	public function pdf_tool_text($post, $metabox){
		include_once GIFTCARD_PATH . 'admin/view/html-pdf-tool-text.php';
	}

    public function pdf_tool_shortcode($post, $metabox){
        include_once GIFTCARD_PATH . 'admin/view/html-pdf-tool-shortcode.php';
    }

	public function pdf_tool_img($post, $metabox){
		include_once GIFTCARD_PATH . 'admin/view/html-pdf-tool-image.php';
	}

	public function pdf_tool_qrcode($post, $metabox){
		include_once GIFTCARD_PATH . 'admin/view/html-pdf-tool-qrcode.php';
	}

	public function save_pdf($post_id){
		if ( isset( $_POST['_pdfwidth'] ) ) {
		    $pdfWidth = $_POST['_pdfwidth'];
		    if($pdfWidth > 0)
			    update_post_meta( $post_id, '_pdfwidth', $_POST['_pdfwidth'] );
		}
		if ( isset( $_POST['_pdfheight'] ) ) {
		    $pdfHeight = $_POST['_pdfheight'];
		    if($pdfHeight > 0)
			    update_post_meta( $post_id, '_pdfheight', $_POST['_pdfheight'] );
		}
		if ( isset( $_POST['_background_img'] ) ) {
		    $backgroundImage = $_POST['_background_img'];
		    if($backgroundImage != "")
			    update_post_meta( $post_id, '_background_img', $_POST['_background_img'] );
		}
		if ( isset( $_POST['pdf_data'] ) && isset( $_POST['_pdfheight'] ) && isset( $_POST['_pdfwidth'] )  ) {
		    if($pdfWidth > 0 && $pdfHeight > 0)
			    update_post_meta( $post_id, 'pdf_data', $_POST['pdf_data'] );
		}
		$post = get_post($post_id);
		if($post->post_status == 'publish'){
            $pdf = new \model\PdfCreate();
            $flag = $pdf->createPdf($post_id);
            if($flag){
                $pdfAttachmentId = $pdf->exportPdf();
                $pdf_url = wp_get_attachment_url($pdfAttachmentId);
                update_post_meta( $post_id, 'pdf_url', $pdf_url);
            }
        }

	}

	public function button_download_pdf($post){
		if ($post->post_type == 'pdf_settings') :
		?>

		<div style="clear: both;padding-top: 5px;">
			<a href="<?php echo admin_url('admin-post.php?action=preview_pdf&pdf_setting&pdf_id='.$post->ID); ?>" class="button" style="" target="_blank">Download PDF</a>
		</div>
		<?php
		endif;
	}

	public static function preview_pdf(){
		if ($_GET['action'] == 'preview_pdf'){
		    if($_GET['pdf_id'] != ""){
		        $pdf_url = get_post_meta($_GET['pdf_id'],'pdf_url','');
                if (isset($_GET['pdf_setting'])){
                    wp_redirect($pdf_url[0]);
                }else{
                    wp_die($pdf_url[0]);
                }
            }else{
                $out['message'] = "You must choose pdf before click button";
                echo json_encode($out);
                wp_die();
            }
		}
	}
}