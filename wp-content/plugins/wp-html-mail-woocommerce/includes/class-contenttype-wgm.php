<?php if ( ! defined( 'ABSPATH' ) ) exit;

// Content Element for WooCommerce German Market

final class Haet_MB_ContentType_WGM extends Haet_MB_ContentType
{

	private static $instance;

	/**
	 * @var string
	 */
	protected $_name  = 'wgm';

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
	protected $_once = false;

	/**
	 * @var string
	 */
	protected $_icon = 'dashicons-shield';

	
	public static function instance(){
		if (!isset(self::$instance) && !(self::$instance instanceof Haet_MB_ContentType_WGM)) {
			self::$instance = new Haet_MB_ContentType_WGM();
		}

		return self::$instance;
	}




	public function __construct(){
		$this->_nicename = __('WC German Market','haet_mail');
		parent::__construct();
		add_action( 'add_meta_boxes', array( $this, 'setup_meta_boxes' ) );
		add_filter( 'haet_mail_placeholder_menu', array( $this, 'add_mailbuilder_placeholder' ) );
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
		$this->admin_print_element_start(); ?>
		<div class="mb-contentelement-content mb-contentelement-content-wgm">
			<div class="mb-edit-wgm">
				<input type="hidden" name="wgmcontent" class="mb-wgm-content">
				<input type="hidden" name="wgmstyle" class="mb-wgm-style">
				<div class="mb-content-preview">
					<h1>Headline 1</h1>
					<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
					<h2>Headline 2</h2>
					<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.</p>
					<h3>Headline 3</h3>
					<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. </p>
				</div>
			</div>
		</div>
		<?php
		$this->admin_print_element_end();
	}






	function inline_css($html, $css){
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head><body>' . $html .'</body></html>';
		require_once(HAET_MAIL_PATH.'/vendor/autoload.php');
		if( class_exists('voku\CssToInlineStyles\CssToInlineStyles') ){
			$cssToInlineStyles = new voku\CssToInlineStyles\CssToInlineStyles();
			$cssToInlineStyles->setHTML($html);
			$cssToInlineStyles->setCSS($css);
			$html = $cssToInlineStyles->convert();
		}elseif( class_exists('TijsVerkoyen\CssToInlineStyles\CssToInlineStyles') ){
			$cssToInlineStyles = new TijsVerkoyen\CssToInlineStyles\CssToInlineStyles();
			$html = $cssToInlineStyles->convert($html, $css);
		}
		return substr( $html, stripos( $html, '<body>' ) + 6, stripos( $html, '</body>' ) - stripos( $html, '<body>' ) - 6 );
	}






	public function print_content( $element_content, $settings ){
		$css = '';
		if( isset( $element_content->content ) && isset( $element_content->content->wgmstyle ) ){
			$styles = json_decode( $element_content->content->wgmstyle );
			foreach ($styles as $key => $value) {
				$attribute = substr( $key , strpos( $key, '-' ) + 1 );
				if( strpos( $attribute, 'size' ) ){
					$value .= 'px';
				}
				if( false !== strpos( $key, 'h1' ) )
					$css .= '.wgm-wrap-email-appendixes h1 { ' . $attribute . ': ' . $value . '; } ';
				elseif( false !== strpos( $key, 'h2' ) )
					$css .= '.wgm-wrap-email-appendixes h2 { ' . $attribute . ': ' . $value . '; } ';
				elseif( false !== strpos( $key, 'h3' ) )
					$css .= '.wgm-wrap-email-appendixes h3 { ' . $attribute . ': ' . $value . '; } ';
				else
					$css .= '.wgm-wrap-email-appendixes { ' . $attribute . ': ' . $value . '; } ';
			}
		}

		$html = '';
		if( isset( $element_content->content ) && isset( $element_content->content->wgmcontent ) ){
			$content = json_decode( $element_content->content->wgmcontent );
			if( is_array($content) ){
				foreach ( $content as $key => $value)
					add_filter( 'wgm_email_display_' . $key, ( $value ? '__return_true' : '__return_false' ), 1, 100 );
			}
		}
		$html .= WGM_Email::get_email_de_footer();

		$html = $this->inline_css( $html, $css );

		$html = Haet_Mail()->wrap_in_padding_container( $html, $element_content->id );
		$html = apply_filters( 'haet_mail_print_content_'.$this->_name, $html, $element_content, $settings );

		echo $html;
	}






	public function setup_meta_boxes(){
		add_meta_box ( 'mb_wgm', __( 'WooCommerce German Market Options', 'haet_mail' ), array($this,'add_wgm_popup'), 'wphtmlmail_mail', 'normal', 'default' );
	}





	public function add_wgm_popup(){
		global $post;
		?>
			<div class="clearfix">
				<div class="mb-wgm-settings">		
					<h3><?php _e('Select WGM content to append to your emails','haet_mail'); ?></h3>
					<div class="clearfix">
						<input type="checkbox" id="mb-wgm-imprint" value="1">
						<label for="mb-wgm-imprint">
							<?php _e( 'Legal Information', 'woocommerce-german-market' ); ?>
						</label>
					</div>
					<div class="clearfix">
						<input type="checkbox" id="mb-wgm-terms" value="1">
						<label for="mb-wgm-terms">
							<?php _e( 'Terms and Conditions', 'woocommerce-german-market' ); ?>
						</label>
					</div>
					<div class="clearfix">
						<input type="checkbox" id="mb-wgm-cancellation_policy" value="1">
						<label for="mb-wgm-cancellation_policy">
							<?php _e( 'Revocation', 'woocommerce-german-market' ); ?>
						</label>
					</div>
					<div class="clearfix">
						<input type="checkbox" id="mb-wgm-cancellation_policy_for_digital_goods" value="1">
						<label for="mb-wgm-cancellation_policy_for_digital_goods">
							<?php _e( 'Revocation Policy for Digital Content', 'woocommerce-german-market' ); ?>
						</label>
					</div>
					<div class="clearfix">
						<input type="checkbox" id="mb-wgm-cancellation_policy_for_digital_goods_acknowlagement" value="1">
						<label for="mb-wgm-cancellation_policy_for_digital_goods_acknowlagement">
							<?php _e( 'Waiver of Rights of Revocation for Digital Content', 'woocommerce-german-market' ); ?>
						</label>
					</div>
					<div class="clearfix">
						<input type="checkbox" id="mb-wgm-delivery" value="1">
						<label for="mb-wgm-delivery">
							<?php printf( __( 'Shipping %s Delivery', 'woocommerce-german-market' ), '&amp;'); ?>
						</label>
					</div>
					<div class="clearfix">
						<input type="checkbox" id="mb-wgm-payment_methods" value="1">
						<label for="mb-wgm-payment_methods">
							<?php _e( 'Payment Method', 'woocommerce-german-market' ); ?>
						</label>
					</div>
					<table class="form-table">
						<tbody>
							
							<?php foreach ( array( 
									'h1' 	=> __('Headline','haet_mail') . ' 1', 
									'h2' 	=> __('Headline','haet_mail') . ' 2', 
									'h3'	=> __('Headline','haet_mail') . ' 3', 
									'text'	=> __('Text','haet_mail') 
								) as $tag => $label ): ?>
								<tr>
									<th scope="row">
										<label for="haet_mailheadlinefont"><?php echo $label; ?></label>
									</th>
									<td class="haet-font-styling">
										<?php
										Haet_Mail()->font_toolbar( array(
											'font'	=>	array(
												'name'	=>	'mb-wgm-' . $tag . '-font-family',
												'value'	=>	''
												),
											'fontsize'	=>	array(
												'name'	=>	'mb-wgm-' . $tag . '-font-size',
												'value'	=>	'',
												),
											'bold'	=>	array(
												'name'	=>	'mb-wgm-' . $tag . '-font-weight',
												'value'	=>	false,
												),
											'italic'	=>	array(
												'name'	=>	'mb-wgm-' . $tag . '-font-style',
												'value'	=>	false
												),
											'align'	=>	array(
												'name'	=>	'mb-wgm-' . $tag . '-text-align',
												'value'	=>	''
												),
											'color'	=>	array(
												'name'	=>	'mb-wgm-' . $tag . '-color',
												'value'	=>	'#000000'
												),
											) );?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
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
		<?php
	}


	public function add_mailbuilder_placeholder( $placeholder_menu ){
	    if( is_array( $placeholder_menu ) ){
	    	for ($i=0; $i < count( $placeholder_menu ); $i++) { 
	    		if( array_key_exists( 'menu', $placeholder_menu[$i] ) && $placeholder_menu[$i]['text'] == __('Profile','woocommerce') ){
	    			$placeholder_menu[$i]['menu'][] = array(
	    			        'text'      => __( 'Activation URL', 'haet_mail' ),
	    			        'tooltip'   => '[ACTIVATION_URL]',
	    			    );
	    		}
	    	}
	    }
	    return $placeholder_menu;
	}

}



function Haet_MB_ContentType_WGM()
{
	return Haet_MB_ContentType_WGM::instance();
}

Haet_MB_ContentType_WGM();