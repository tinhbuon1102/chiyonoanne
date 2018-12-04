<?php if ( ! defined( 'ABSPATH' ) ) exit;
require_once HAET_MAIL_PATH . 'includes/class-contenttype.php';

class Haet_MB_ContentType_ProductsTable extends Haet_MB_ContentType
{

	private static $instance;

	/**
	 * @var string
	 */
	protected $_name  = 'productstable';

	/**
	 * @var string
	 */
	protected $_nicename = '';

	/**
	 * @var int
	 */
	protected $_priority = 30;

	/**
	 * @var bool
	 * contenttype can be used once per email
	 */
	protected $_once = true;

	/**
	 * @var string
	 */
	protected $_icon = 'dashicons-grid-view';

	/**
	 * @var array 
	 */
	protected $_element_content;

	/**
	 * @var array 
	 */
	protected $_settings;
	
	public static function instance(){
		if (!isset(self::$instance) && !(self::$instance instanceof Haet_MB_ContentType_ProductsTable)) {
			self::$instance = new Haet_MB_ContentType_ProductsTable();
		}

		return self::$instance;
	}




	public function __construct(){
		$this->_nicename = __('Products Table','haet_mail');
		parent::__construct();
		add_action ( 'haet_mail_sidebar', array( $this, 'add_tiny_editor_settings' ) );

		add_filter( 'mce_external_plugins', array( $this,'register_editor_plugins') );
		add_filter( 'mce_buttons', array( $this,'register_editor_buttons') );
		add_filter( 'tiny_mce_before_init', array( $this, 'customize_editor_toolbar'), 100 );
		add_filter( 'haet_mail_enqueue_js_data', array( $this, 'enqueue_js_translations' ) );
		add_filter( 'haet_mail_enqueue_js_data', array( $this, 'enqueue_js_placeholder_menu' ) );
		add_filter( 'haet_mail_enqueue_js_data', array( $this, 'enqueue_js_placeholder_values' ) );
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




	public function enqueue_js_placeholder_menu( $enqueue_data ){
	    if( !array_key_exists( 'text', $enqueue_data['placeholder_menu'] ) )
	        $enqueue_data['placeholder_menu']['productstable'] = array();

	    $imagesizemenu = array();
	    $image_sizes = WPHTMLMail_Woocommerce()->get_image_sizes();
	    foreach ($image_sizes as $name => $dimensions) {
	    	if( $dimensions['width']>0 
	    		&& $dimensions['width']<500
	    		&& $dimensions['height']>0
	    		&& $dimensions['height']<500
	    		){
	    		$image_size_placeholder_name = strtoupper( preg_replace('/[^\da-z]/i', '', $name) );
		    	$imagesizemenu[] = array(
		    	    'text'      => __('Image','haet_mail').' – '.ucfirst($name).' ('.$dimensions['width'].'x'.$dimensions['height'].' '. ( $dimensions['crop'] ? __('exact','haet_mail') : __('max','haet_mail') ).')',
		    	    'tooltip'   => '[PHOTO_'.$image_size_placeholder_name.']',
		    	);
			}
	    }
	    

	    $enqueue_data['placeholder_menu']['productstable'] = apply_filters( 'haet_mail_placeholder_menu_products_table', array(
	            array(
	                'text'      => __('Product Image','woocommerce'),
	                'menu'      => $imagesizemenu
	            ),
	            array(
	                'text'      => __('Product Name','woocommerce'),
	                'tooltip'   => '[PRODUCTNAME]',
	            ),
	            array(
	                'text'      => __('SKU','woocommerce'),
	                'tooltip'   => '[SKU]',
	            ),
	            array(
	                'text'      => __('Item Meta','haet_mail'),
	                'tooltip'   => '[ITEMMETA]',
	            ),
	            array(
	                'text'      => __('Purchase Note','woocommerce'),
	                'tooltip'   => '[PURCHASE_NOTE]',
	            ),
	            array(
	                'text'      => __('Quantity','haet_mail'),
	                'tooltip'   => '[QUANTITY]',
	            ),
	            array(
	                'text'      => __('Price','woocommerce'),
	                'tooltip'   => '[PRICE]',
	            ),
	            array(
	                'text'      => __('Single Price','haet_mail'),
	                'tooltip'   => '[PRICE_SINGLE]',
	            ),
	            array(
	                'text'      => __('Tax amount','haet_mail'),
	                'tooltip'   => '[TAX]',
	            ),
	            array(
	                'text'      => __('Tax rate','haet_mail'),
	                'tooltip'   => '[TAX_RATE]',
	            ),
	            array(
	                'text'      => __('Product Link','haet_mail'),
	                'tooltip'   => '[PRODUCT_LINK]',
	            )
	        ) );

	    return $enqueue_data;
	}




	/**
	 * Add PHP data to the main MailBuilder Javascript file using wp_localize_script
	 * @param  array $enqueue_data
	 * @return array $enqueue_data
	 */
	public function enqueue_js_translations( $enqueue_data ){
		$enqueue_data['translations']['confirm_delete_column'] 	= __('Do you really want to delete this column?','haet_mail');

		return $enqueue_data;
	}




	public function enqueue_js_placeholder_values( $enqueue_data ){
		if( !array_key_exists( 'productstable', $enqueue_data['placeholders'] ) )
			$enqueue_data['placeholders']['productstable'] = array();
		$demo_order = WPHTMLMail_Woocommerce()->get_demo_order();
		$enqueue_data['placeholders']['productstable']['items'] = $demo_order['items'];
		$enqueue_data['placeholders']['productstable']['totals'] = $demo_order['totals'];
		return $enqueue_data;
	}






	public function admin_render_contentelement_template( $current_email ){
		$this->admin_print_element_start(); 
		$demo_order = WPHTMLMail_Woocommerce()->get_demo_order(); ?>
		<div class="mb-element-content-wrapper">
			<div class="mb-import-export">
				<a class="import-export-link" href="#">import / export table settings</a>
				<a class="import-export-close" href="#">&times;</a>
				<div class="import-export-toggle">
					<p class="description">
						<?php _e('Just copy the code below to another products table or paste code here and click IMPORT','haet_mail'); ?>
					</p>
					<input type="text" class="import-export-settings"/>
					<a class="import-export-apply" href="#">
						<?php _e('import','haet_mail'); ?>
					</a>
				</div>
			</div>
			<table class="mb-edit-table mb-edit-productstable">
				<thead>
					<tr>
						<td class="mb-edit-cell">
							<textarea class="mb-cell-content"></textarea>
							<input type="hidden" class="mb-cell-styles" value=''>
							<div class="mb-content-preview"></div>
							<div class="mb-cell-edit-panel">
								<span class="mb-edit-cell-content dashicons dashicons-edit"  title="<?php _e('edit cell content','haet_mail'); ?>"></span><span class="mb-add-column dashicons dashicons-plus"  title="<?php _e('add column','haet_mail'); ?>"></span><span class="mb-remove-column dashicons dashicons-trash"  title="<?php _e('remove column','haet_mail'); ?>"></span>
							</div>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr class="mb-item-content-row">
						<td class="mb-edit-cell">
							<textarea class="mb-cell-content"></textarea>
							<input type="hidden" class="mb-cell-styles" value=''>
							<div class="mb-content-preview"></div>
							<div class="mb-cell-edit-panel">
								<span class="mb-edit-cell-content dashicons dashicons-edit"  title="<?php _e('edit cell content','haet_mail'); ?>"></span><span class="mb-add-column dashicons dashicons-plus"  title="<?php _e('add column','haet_mail'); ?>"></span><span class="mb-remove-column dashicons dashicons-trash"  title="<?php _e('remove column','haet_mail'); ?>"></span>
							</div>
						</td>
					</tr>
					<?php if( stripos( $current_email, 'customer' ) === false ): ?>
						<tr class="mb-purchase-note-row">
							<td class="mb-edit-cell mb-purchase-note-cell" colspan="1">
								<textarea class="mb-cell-content"></textarea>
								<input type="hidden" class="mb-cell-styles" value=''>
								<div class="mb-content-preview"></div>
							</td>
						</tr>
					<?php endif; ?>
					<?php for($i = 1; $i < count( $demo_order['items'] ); $i++ ): ?>
						<tr class="mb-item-content-row">
							<td>
								<div class="mb-content-preview"></div>
							</td>
						</tr>
						<?php if( stripos( $current_email, 'customer' ) === false ): ?>
							<tr class="mb-purchase-note-row">
								<td class="mb-purchase-note-cell" colspan="1">
									<div class="mb-content-preview"></div>
								</td>
							</tr>
						<?php endif; ?>
					<?php endfor; ?>
				</tbody>
			</table>

			<table class="mb-edit-table mb-edit-totalstable">
				<tbody>
					<tr class="mb-item-content-row">
						<td class="mb-edit-cell">
							<textarea class="mb-cell-content"></textarea>
							<input type="hidden" class="mb-cell-styles" value=''>
							<div class="mb-content-preview"></div>
							<div class="mb-cell-edit-panel">
								<span class="mb-edit-cell-content dashicons dashicons-edit"  title="<?php _e('edit cell content','haet_mail'); ?>"></span>
							</div>
						</td>
						<td class="mb-edit-cell">
							<textarea class="mb-cell-content"></textarea>
							<input type="hidden" class="mb-cell-styles" value=''>
							<div class="mb-content-preview"></div>
							<div class="mb-cell-edit-panel">
								<span class="mb-edit-cell-content dashicons dashicons-edit"  title="<?php _e('edit cell content','haet_mail'); ?>"></span>
							</div>
						</td>
						<td class="mb-edit-cell">
							<textarea class="mb-cell-content"></textarea>
							<input type="hidden" class="mb-cell-styles" value=''>
							<div class="mb-content-preview"></div>
							<div class="mb-cell-edit-panel">
								<span class="mb-edit-cell-content dashicons dashicons-edit"  title="<?php _e('edit cell content','haet_mail'); ?>"></span>
							</div>
						</td>
					</tr>
					<?php for($i = 1; $i < count( $demo_order['totals'] ); $i++ ): ?>
					<tr class="mb-item-content-row">
						<td>
							<div class="mb-content-preview"></div>
						</td>
						<td>
							<div class="mb-content-preview"></div>
						</td>
						<td>
							<div class="mb-content-preview"></div>
						</td>
					</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</div>
		<?php
		$this->admin_print_element_end();
	}




	public function add_tiny_editor_settings(){
		?>
		<div id="mb-edit-cell" class="mailbuilder-sidebar-element">
			<h3><?php _e('Edit cell content', 'haet_mail' ); ?></h3>
			<?php 
			wp_editor( '', 'mb_tiny_wysiwyg_editor', array(
					'wpautop'		=>	false,
					'media_buttons'	=>	false,
					'textarea_rows' =>	2,
					'quicktags'		=>	false
				) ); 
			?>
			<div class="mb-cell-settings">		
				<h3><?php _e('Cell borders','haet_mail'); ?></h3>
				<div class="clearfix">
					<input type="checkbox" id="mb-cell-border-left-enabled" value="1">
					<label for="mb-cell-border-left-enabled" class="border-choice-label">
						<div class="border-choice border-choice-left"></div>
					</label>
					<select  id="mb-cell-border-left-width">
						<?php for ($width=1; $width<=10; $width++) :?>
							<option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>		
						<?php endfor; ?>
					</select>
					<input type="text" class="color" id="mb-cell-border-left-color" value="#000">
				</div>

				<div class="clearfix">
					<input type="checkbox" id="mb-cell-border-top-enabled" value="1">
					<label for="mb-cell-border-top-enabled" class="border-choice-label">
						<div class="border-choice border-choice-top"></div>
					</label>
					<select  id="mb-cell-border-top-width">
						<?php for ($width=1; $width<=10; $width++) :?>
							<option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>		
						<?php endfor; ?>
					</select>
					<input type="text" class="color" id="mb-cell-border-top-color" value="#000">
				</div>

				<div class="clearfix">
					<input type="checkbox" id="mb-cell-border-right-enabled" value="1">
					<label for="mb-cell-border-right-enabled" class="border-choice-label">
						<div class="border-choice border-choice-right"></div>
					</label>
					<select  id="mb-cell-border-right-width">
						<?php for ($width=1; $width<=10; $width++) :?>
							<option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>		
						<?php endfor; ?>
					</select>
					<input type="text" class="color" id="mb-cell-border-right-color" value="#000">
				</div>

				<div class="clearfix">
					<input type="checkbox" id="mb-cell-border-bottom-enabled" value="1">
					<label for="mb-cell-border-bottom-enabled" class="border-choice-label">
						<div class="border-choice border-choice-bottom"></div>
					</label>
					<select  id="mb-cell-border-bottom-width">
						<?php for ($width=1; $width<=10; $width++) :?>
							<option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>		
						<?php endfor; ?>
					</select>
					<input type="text" class="color" id="mb-cell-border-bottom-color" value="#000">
				</div>


				<h3><?php _e('Cell width','haet_mail'); ?></h3>
				<div class="mb-cell-width clearfix">
					<select  id="mb-cell-width">
						<option value="auto"><?php _e('auto','haet_mail'); ?></option>		
						<?php for ($width=30; $width<=400; $width+=5) :?>
							<option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>		
						<?php endfor; ?>
					</select>
				</div>

				<h3><?php _e('Cell padding','haet_mail'); ?></h3>
				<div class="mb-cell-padding clearfix">
					<label for="mb-cell-padding-left" class="border-choice-label">
						<div class="border-choice border-choice-left"></div>
					</label>
					<select  id="mb-cell-padding-left">
						<?php for ($width=0; $width<=20; $width++) :?>
							<option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>		
						<?php endfor; ?>
					</select>
					&nbsp;&nbsp;
					<label for="mb-cell-padding-top" class="border-choice-label">
						<div class="border-choice border-choice-top"></div>
					</label>
					<select  id="mb-cell-padding-top">
						<?php for ($width=0; $width<=20; $width++) :?>
							<option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>		
						<?php endfor; ?>
					</select>
					&nbsp;&nbsp;
					<label for="mb-cell-padding-right" class="border-choice-label">
						<div class="border-choice border-choice-right"></div>
					</label>
					<select  id="mb-cell-padding-right">
						<?php for ($width=0; $width<=20; $width++) :?>
							<option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>		
						<?php endfor; ?>
					</select>
					&nbsp;&nbsp;
					<label for="mb-cell-padding-bottom" class="border-choice-label">
						<div class="border-choice border-choice-bottom"></div>
					</label>
					<select  id="mb-cell-padding-bottom">
						<?php for ($width=0; $width<=20; $width++) :?>
							<option value="<?php echo $width.'px'; ?>"><?php echo $width.'px'; ?></option>		
						<?php endfor; ?>
					</select>
				</div>
			</div>

			<div class="mb-popup-buttons">
				<button class="mb-apply button button-primary" type="button">
					<span class="dashicons dashicons-yes"></span>
					<?php _e('Apply', 'haet_mail'); ?>
				</button>
				<button class="mb-cancel button button-secondary" type="button">
					<span class="dashicons dashicons-no-alt"></span>
					<?php _e('Cancel', 'haet_mail'); ?>
				</button>
			</div>
		</div>
		<?php
	}




	public function customize_editor_toolbar( $initArray ) {  
		global $post;
		if( $post ){
			$post_type = get_post_type( $post->ID );
			if( 'wphtmlmail_mail' == $post_type && $initArray['selector'] == '#mb_tiny_wysiwyg_editor' ){
				$fonts = Haet_Mail()->get_fonts();
				$initArray['font_formats'] = "";
				foreach ($fonts as $font => $display_name) {
					$initArray['font_formats'] .= "$display_name=$font;";
				}
				$initArray['font_formats'] = trim($initArray['font_formats'],';');

				$initArray['toolbar1'] = 'fontselect,fontsizeselect,bold,italic,|,alignleft,aligncenter,alignright,forecolor,mb_productstable_placeholder,code';
				// Font size
				$initArray['fontsize_formats'] = "10px 11px 12px 14px 16px 18px 20px 22px 24px 28px 32px";
				
				$initArray['toolbar2'] = '';    
			}
		}


		return $initArray;  
	  
	} 





	function register_editor_buttons($buttons){
		array_push( $buttons, 'mb_productstable_placeholder' );
		return $buttons;
	}





	function register_editor_plugins($plugin_array) {
		global $post;
		if( $post ){
			$post_type = get_post_type( $post->ID );
			if( 'wphtmlmail_mail' == $post_type ){
				$plugin_array['mb_productstable_placeholder'] = HAET_MAIL_WOOCOMMERCE_URL.'/js/contenttype-productstable-editor-placeholder.js';
			}
		}
		return $plugin_array;
	}




	public function print_products_table($order, $sent_to_admin, $plain_text, $email){
		if( !$order )
			return;
		
		$items = WPHTMLMail_Woocommerce()->get_placeholders_productstable( $this->_settings );
		$totals = $order->get_order_item_totals();

		//	PRODUCTS TABLE
		//   TABLE HEADER
		$html = '
			<table class="products-table" width="100%" style="margin-bottom:20px">
				<thead>
					<tr>
			';
		if( isset($this->_element_content->content) ):
			foreach ($this->_element_content->content as $key => $cell_content) {
				if ( strpos( $key, 'productstable-header[') ):
					$styles_key = str_replace('productstable-header', 'productstable-header-styles', $key);
					$cell_styles = $this->_element_content->content->$styles_key;
					$cell_styles_string = '';
					if( $cell_styles)

						foreach ( json_decode( $cell_styles, true ) as $property => $value )
							$cell_styles_string .= $property . ': ' . $value . '; ';

					$html .= '<td style="' . $cell_styles_string . '">'.$cell_content.'</td>';
				endif;
			}
		endif;
		$html .= ' 
					</tr>
				</thead>
			';

		//   TABLE BODY
		$html .= '<tbody>';
		if( isset($this->_element_content->content) ):
			foreach ($items as $item):
				$html .= '<tr>';
				$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
				$num_columns = 0;
				foreach ($this->_element_content->content as $key => $cell_content):
					if ( strpos( $key, 'productstable-body[') ):
						$styles_key = str_replace('productstable-body', 'productstable-body-styles', $key);
						$cell_styles = $this->_element_content->content->$styles_key;
						$cell_styles_string = '';
						$is_thumbnail_col = false;
						if( false !== stripos( $cell_content, '[PHOTO_' ) )
							$is_thumbnail_col = true;

						$thumbnail_width = 50;
						$col_width = 0;

						if( $cell_styles)
							foreach ( json_decode( $cell_styles, true ) as $property => $value ){
								$cell_styles_string .= $property . ': ' . $value . '; ';
								if( $is_thumbnail_col && $property == 'width' ){
									$col_width = intval( str_replace( 'px', '', $value ) );
									$thumbnail_width = $col_width;
								}
							}
			
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

						if( $is_thumbnail_col )
							$cell_content = str_replace( '<img ', '<img width="' . $thumbnail_width . '" ', $cell_content );
								
						$num_columns++;
						$html .= '<td ' . ( $col_width>0 ? 'width="' . $col_width . '"' : '') . ' style="' . $cell_styles_string . '">'.$cell_content.'</td>';
					endif;
				endforeach;
				$html .= '</tr>';
				if ( $order->is_paid() && ! $this->_settings['wc_sent_to_admin'] && is_object( $_product ) && ( $purchase_note = get_post_meta( $_product->get_id(), '_purchase_note', true ) ) ) : 
					$html .= '
						<tr>
							<td colspan="'.$num_columns.'" style="'.'">' . wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ) .'</td>
						</tr>';
				endif; 
					
			endforeach;
		endif;
		$html .= '
				</tbody>
			</table>
			';


		// TOTALS TABLE 
		$html .= '
			<table class="totals-table" width="100%" style="margin-bottom:30px">
				<tbody>
			';
		foreach ($totals as $row_name => $total):
			$html .= '<tr class="'.$row_name.'">';
			foreach ($this->_element_content->content as $key => $cell_content):
				if ( strpos( $key, 'totalstable-body[') ):
					$styles_key = str_replace('totalstable-body', 'totalstable-body-styles', $key);
					$cell_styles = $this->_element_content->content->$styles_key;
					$cell_styles_string = '';
					if( $cell_styles)
						foreach ( json_decode( $cell_styles, true ) as $property => $value )
							$cell_styles_string .= $property . ': ' . $value . '; ';
		
					$cell_content = preg_replace_callback(
					   "/\[([a-z0-9\_]*)\]/i",
					    function ( $placeholder ) use ( $total ) {
					        if( array_key_exists( strtolower($placeholder[1]), $total) )
					            $placeholder_value = $total[strtolower($placeholder[1])];
					        else
					            $placeholder_value = $placeholder[0];
					        return $placeholder_value;
					    },
					    $cell_content
					);
							

					$html .= '<td style="' . $cell_styles_string . '">'.( $cell_content ? $cell_content : '&nbsp;' ).'</td>';
				endif;
			endforeach;
			$html .= '</tr>';
		endforeach;

		$html .= '
				</tbody>
			</table>
			';

		$html = apply_filters( 'haet_mail_print_content_'.$this->_name, $html, $this->_element_content, $this->_settings );

		echo $html;
		do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email );
	}





	public function print_content( $element_content, $settings ){
		$this->_element_content = $element_content;
		$this->_settings = $settings;
		
		remove_action( 'woocommerce_email_order_details', array( WC_Emails::instance(), 'order_details' ), 10, 4 );
		add_action( 'woocommerce_email_order_details', array( $this, 'print_products_table' ), 10, 4 );

		ob_start();
		do_action( 'woocommerce_email_order_details', $settings['wc_order'], $settings['wc_sent_to_admin'], false, $settings['wc_email'] );
		$html = ob_get_clean();
		echo Haet_Mail()->wrap_in_padding_container( $html, 'woocommerce_email_order_details' );
	}
}



function Haet_MB_ContentType_ProductsTable()
{
	return Haet_MB_ContentType_ProductsTable::instance();
}

Haet_MB_ContentType_ProductsTable();