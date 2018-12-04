<?php if ( ! defined( 'ABSPATH' ) ) exit;
require_once HAET_MAIL_PATH . 'includes/class-contenttype.php';

final class Haet_MB_ContentType_RelatedProducts extends Haet_MB_ContentType_ProductsTable
{

	private static $instance;

	/**
	 * @var string
	 */
	protected $_name  = 'relatedproducts';

	/**
	 * @var string
	 */
	protected $_nicename = '';

	/**
	 * @var int
	 */
	protected $_priority = 50;

	/**
	 * @var string
	 */
	protected $_icon = 'dashicons-admin-links';

	/**
	 * @var bool
	 * contenttype can be used once per email
	 */
	protected $_once = true;

	/**
	 * @var array 
	 */
	protected $_element_content;

	/**
	 * @var array 
	 */
	protected $_settings;
	
	public static function instance(){
		if (!isset(self::$instance) && !(self::$instance instanceof Haet_MB_ContentType_RelatedProducts)) {
			self::$instance = new Haet_MB_ContentType_RelatedProducts();
		}

		return self::$instance;
	}




	public function __construct(){
		parent::__construct();
		$this->_nicename = __('Related Products','haet_mail');
	}




	public function enqueue_scripts_and_styles($page){
		if( false !== strpos($page, 'post.php')){
			global $post;
			$post_type = get_post_type( $post->ID );
			if ( $post_type == 'wphtmlmail_mail' ){
				wp_enqueue_script( 'haet_mb_contenttype_'.$this->_name.'_js',  HAET_MAIL_WOOCOMMERCE_URL.'/js/contenttype-'.$this->_name.'.js', array( 'jquery' ) );
			}
		}
	}





	public function admin_render_contentelement_template( $current_email ){
		$this->admin_print_element_start(); 
		$demo_order = WPHTMLMail_Woocommerce()->get_demo_order(); 
		$num_items = 3;
		?>
		<input type="hidden" name="mb-relatedproducts-num-items" id="mb-relatedproducts-num-items" value="<?php echo $num_items; ?>">
		<label for="mb-relatedproducts-source"><?php _e('Data source for related products','haet_mail') ; ?></label>
		<select name="mb-relatedproducts-source" id="mb-relatedproducts-source">
			<option value="linked"><?php _e('Linked to order items','haet_mail') ; ?></option>
			<option value="latest"><?php _e('Latest products','haet_mail') ; ?></option>
			<option value="random"><?php _e('Random products','haet_mail') ; ?></option>
		</select>
		<p class="description">
			<?php _e( 'Add up to four related products to your customer notifications.', 'haet_mail' ); ?>
		</p>
		<table class="mb-edit-table mb-edit-related-products-table">
			<tbody>
				<tr>
					<td class="mb-edit-cell">
						<textarea class="mb-cell-content" name="mb-relatedproducts-content"><p style="text-align: left;">[PHOTO_SHOPTHUMBNAIL]<br /><span style="font-size: 16px; color:black; "><strong>[PRODUCTNAME]</strong></span></p>
							<p style="text-align: right;"><span style="font-size: 16px; color: #808080;"><strong><em>[PRICE]</em></strong></span></p>
						</textarea>
						<input type="hidden" class="mb-cell-styles" name="mb-relatedproducts-styles" value=''>
						<a href="#" class="mb-content-preview"></a>
						<div class="mb-cell-edit-panel">
							<span class="mb-edit-cell-content dashicons dashicons-edit"  title="<?php _e('edit cell content','haet_mail'); ?>"></span>
						</div>
					</td>
					<td class="mb-edit-cell">
						<a href="#" class="mb-content-preview"></a>
						<div class="mb-cell-edit-panel">
							<span class="mb-add-column dashicons dashicons-plus"  title="<?php _e('add column','haet_mail'); ?>"></span>
							<span class="mb-remove-column dashicons dashicons-trash"  title="<?php _e('remove column','haet_mail'); ?>"></span>
						</div>
					</td>
					<td class="mb-edit-cell">
						<a href="#" class="mb-content-preview"></a>
						<div class="mb-cell-edit-panel">
							<span class="mb-add-column dashicons dashicons-plus"  title="<?php _e('add column','haet_mail'); ?>"></span>
							<span class="mb-remove-column dashicons dashicons-trash"  title="<?php _e('remove column','haet_mail'); ?>"></span>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		
		<?php
		$this->admin_print_element_end();
	}




	public function get_placeholders_singleproduct( $product ){	
		$image_placeholder_sizes = array();
		$image_sizes = WPHTMLMail_Woocommerce()->get_image_sizes();
		$item = array();

		$wc_product = new WC_Product( $product );
		foreach ($image_sizes as $name => $dimensions) {
		    if( $dimensions['width']>0 
		        && $dimensions['width']<500
		        && $dimensions['height']>0
		        && $dimensions['height']<500
		        ){
		        $image_size_placeholder_name = 'photo_'.strtolower( preg_replace('/[^\da-z]/i', '', $name) );
		        $image_placeholder_sizes[$image_size_placeholder_name] = $name;
		    }
		}

		foreach ( $image_placeholder_sizes as $image_size_placeholder_name => $image_size_name) {
		    $item[$image_size_placeholder_name] = '<img src="' . ( $wc_product->get_image_id() ? current( wp_get_attachment_image_src( $wc_product->get_image_id(), $image_size_name) ) : wc_placeholder_img_src() ) . '">';
		}

		$item['productname'] = $wc_product->get_title();

		$item['product_link'] = get_permalink( $wc_product->get_id() );

		$item['sku'] = $wc_product->get_sku();

		$item['itemmeta'] = '';
		        
		$item['quantity'] = '';
		$item['price'] = $wc_product->get_price_html();

		$item['purchase_note'] = '';

		return $item;
	}




	public function get_placeholders_relatedproducts( $source, $num_items, $wc_order ){
		global $post;
	    $wc_items = $wc_order->get_items();

	    $order_item_ids = array();
	    $crossell_ids = array();
	    foreach ( $wc_items as $item_id => $order_item ) {
	        $product     = apply_filters( 'woocommerce_order_item_product', $wc_order->get_product_from_item( $order_item ), $order_item );
	        $order_item_ids[] = $product->get_id();
	        if( $source == 'linked' ){
	        	$item_crossell_ids = $product->get_cross_sell_ids();
	        	$crossell_ids = array_merge($crossell_ids,$item_crossell_ids);
	        }
	    }
	    
	    $items = array();

	    if ( $source == 'latest' ){
            $args = array(
				'post_type'	=> 'product',
				'post_status' => 'publish',
				'orderby'	=>	'ID',
				'order'		=>	'DESC',
				'posts_per_page' => $num_items-count( $items ),
				'post__not_in'	=> $order_item_ids,
				'offset' => 0
			);
			$query = new WP_Query( $args );
			while ( $query->have_posts() ):
				$query->the_post();
				setup_postdata($post);
                $items[] = $this->get_placeholders_singleproduct( $post );
			endwhile;
			wp_reset_postdata();
	    }elseif ( $source == 'random' ){
            $args = array(
				'post_type'	=> 'product',
				'post_status' => 'publish',
				'orderby'	=>	'rand',
				'posts_per_page' => $num_items,
				'post__not_in'	=> $order_item_ids,
				'offset' => 0
			);
			$query = new WP_Query( $args );
			while ( $query->have_posts() ):
				$query->the_post();
				setup_postdata($post);
                $items[] = $this->get_placeholders_singleproduct( $post );
			endwhile;
			wp_reset_postdata();
	    }elseif ( $source == 'linked' ){
            if( count( $crossell_ids )>0 ):
                $args = array(
    				'post_type'	=> 'product',
    				'post_status' => 'publish',
    				'posts_per_page' => $num_items,
    				'post__in'	=>	$crossell_ids,
    				'post__not_in'	=> $order_item_ids,
    				'offset' => 0,
    				'orderby'	=> 'post__in'
    			);
    			$query = new WP_Query( $args );
    			while ( $query->have_posts() ):
    				$query->the_post();
    				setup_postdata($post);
    				$items[] = $this->get_placeholders_singleproduct( $post );
    			endwhile;
    			wp_reset_postdata();
            endif; 
            if( count( $items ) < $num_items ):
	            $args = array(
					'post_type'	=> 'product',
					'post_status' => 'publish',
					'orderby'	=>	'rand',
					'posts_per_page' => $num_items-count( $items ),
					'post__not_in'	=> $order_item_ids,
					'offset' => 0
				);
				$query = new WP_Query( $args );
				while ( $query->have_posts() ):
					$query->the_post();
					setup_postdata($post);
	                $items[] = $this->get_placeholders_singleproduct( $post );
				endwhile;
				wp_reset_postdata();
			endif;
		}

	    return apply_filters( 'haet_mail_relatedproducts_items', $items, $source, $num_items, $wc_order );
	}





	public function print_content( $element_content, $settings ){
		if( isset($element_content->content) ):
			$content = (array) $element_content->content;
			$num_items = $content['mb-relatedproducts-num-items'];
			$source = $content['mb-relatedproducts-source'];
			$html = '';
			// $html .='######'.$num_items;
			// $html .= '<pre>'.print_r($element_content,true).'</pre>';
			$items = $this->get_placeholders_relatedproducts( $source, $num_items, $settings['wc_order'] );
			// $html .= '<pre>'.print_r($items,true).'</pre>';

			$num_items = count( $items );
			$gap_percent = 5;
			$column_percent = ( 100 - ($num_items-1)*$gap_percent ) / $num_items;

			$html .= '<table class="relatedproducts-table" width="100%" style="margin-bottom:20px; ">';
			$html .= '<tr>';
			$item_index = 0;
			foreach ($items as $item):
				$cell_content = '<a href="[PRODUCT_LINK]">'.$content['mb-relatedproducts-content'].'</a>';
				$cell_styles = $content['mb-relatedproducts-styles'];
				$cell_styles_string = '';
				if( $cell_styles)
					foreach ( json_decode( $cell_styles, true ) as $property => $value )
						if( $property != 'width' )
							$cell_styles_string .= $property . ': ' . $value . '; ';
	
				$cell_content = preg_replace_callback(
				   "/\[([a-z0-9\_]*)\]/i",
				    function ( $placeholder ) use ( $item ) {
				        if( array_key_exists( strtolower($placeholder[1]), $item) )
				            $placeholder_value = $item[strtolower($placeholder[1])];
				        else
				            $placeholder_value = $placeholder[0];
				        return $placeholder_value;
				    },
				    $cell_content
				);
						
				$html .= 
					'<td style="width:' . $column_percent . '%; ' . $cell_styles_string . '" class="column-content">
						'.$cell_content.'
					</td>';
				if( $item_index < $num_items-1 )
					$html .= '<td style="width:' . $gap_percent . '%; class="column-gap">&nbsp;</td>';
				$item_index ++ ;
				
			endforeach;
			$html .= '</tr>';
			$html .= '</table>';


			$html = Haet_Mail()->wrap_in_padding_container( $html, $element_content->id );
			$html = apply_filters( 'haet_mail_print_content_'.$this->_name, $html, $element_content, $settings );

			echo $html;
		endif;
	}
}



function Haet_MB_ContentType_RelatedProducts()
{
	return Haet_MB_ContentType_RelatedProducts::instance();
}

Haet_MB_ContentType_RelatedProducts();