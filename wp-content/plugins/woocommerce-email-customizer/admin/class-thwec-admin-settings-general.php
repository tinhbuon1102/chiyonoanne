<?php
/**
 * The admin general settings page functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package    woocommerce-email-customizer-pro
 * @subpackage woocommerce-email-customizer-pro/admin
 */
if(!defined('WPINC')){	die; }

if(!class_exists('THWEC_Admin_Settings_General')):

class THWEC_Admin_Settings_General extends THWEC_Admin_Settings {
	protected static $_instance = null;
	private $tbuilder = null;
	private $woo_method_variables = array();
	private $wcfe_pattern = '';

	public function __construct() {
		parent::__construct('general_settings', '');
		$this->tbuilder = THWEC_Admin_Settings_Builder::instance();

		add_action('wp_ajax_thwec_save_email_template', array($this,'save_email_template'));
		add_action('wp_ajax_nopriv_thwec_save_email_template', array($this,'save_email_template'));

		$this->init_constants();
	}

	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function init_constants(){
		$this->woo_method_variables = array(
			'billing_first_name',
			'billing_last_name',
			'billing_company',
			'billing_country',
			'billing_address_1',
			'billing_address_2',
			'billing_city',
			'billing_state',
			'billing_postcode',
			'billing_phone',
			'billing_email',
			'shipping_first_name',
			'shipping_last_name',
			'shipping_company',
			'shipping_country',
			'shipping_address_1',
			'shipping_address_2',
			'shipping_city',
			'shipping_state',
			'shipping_postcode'
		);
		$this->wcfe_pattern = '\[(\[?)(WCFE)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)'; 
	}

	public function render_page(){
		$this->render_tabs();
		$this->render_sections();
		$this->render_content();
	}
	
	private function render_content(){
		$this->tbuilder->render_template_builder($_POST);
    }
	
	public function save_email_template(){
		if(isset($_POST['template_edit_data'])){			
			$template_display_name = $_POST['template_name'];			
			$template_name = THWEC_Admin_Utils::prepare_template_name($template_display_name);

			//Save template files
			$save_files = $this->save_template_files($template_name, $_POST);

			if($save_files){
				//Save template meta data in DB
				$save_meta = $this->save_settings($template_name, $_POST);
			}
		}
	}	

	public function save_settings($template_name, $posted){
		$settings = $this->prepare_settings($template_name, $posted);
		$result = THWEC_Utils::save_template_settings($settings);
		return $result;
	}

	public function save_template_files($template_name, $posted){
		$template_html_edit = isset($posted['template_edit_data']) ? stripslashes($posted['template_edit_data']) : '';
		$template_html_final = isset($posted['template_render_data']) ? stripslashes($posted['template_render_data']) : '';
		$template_css_final = isset($posted['template_render_css']) ? stripslashes($posted['template_render_css']) : '';

		$path_template = THWEC_CUSTOM_TEMPLATE_PATH.$template_name.'.php';
		$path_edit = THWEC_CUSTOM_TEMPLATE_PATH.$template_name.'.thwec';
		$path_css = THWEC_CUSTOM_TEMPLATE_PATH.$template_name.'.css';
		$content = $this->style_inline( $template_html_final, $template_css_final );

		$template_html_final = $this->insert_dynamic_data($content);
		
		$save_edit_file = $this->save_template_file($template_html_edit, $path_edit); 
		// $save_render_file = $this->save_template_file($template_html_final, $path_template, $template_css_final);
		$save_render_file = $this->save_template_file($template_html_final, $path_template);

		return ($save_edit_file && $save_render_file);
	}

	public function save_template_file($content, $path, $css=false){
		$saved = false;
		// $content = $this->style_inline( $content, $css );
		$myfile_template = fopen($path, "w") or die("Unable to open file!");
		if(false !== $myfile_template){
			fwrite($myfile_template, $content);
			fclose($myfile_template);
			$saved = true; 
		}
		return $saved;
	}

	public function style_inline( $content, $css ) {
		if ( $content && $css) {
			try {
				require_once(WP_PLUGIN_DIR.'/woocommerce/includes/libraries/class-emogrifier.php');
				$emogrifier = new Emogrifier( $content, $css );
				$content    = $emogrifier->emogrify();
				$content    = htmlspecialchars_decode($content);
			} catch ( Exception $e ) {
				//$logger = wc_get_logger();
				//$logger->error( $e->getMessage(), array( 'source' => 'emogrifier' ) );
			}
		}
		return $content;
	}

	public function prepare_settings($template_name, $posted){
		$settings = THWEC_Utils::get_template_settings();
		$data = $this->prepare_template_meta_data($template_name, $posted);
		$settings['templates'][$template_name] = $data;
		return $settings;
	}

	public function prepare_template_meta_data($template_name, $posted){
		$display_name = isset($posted['template_name']) ? $posted['template_name'] : '';
		$file_name = $template_name ? $template_name.'.php' : false;

		$data = array();
		$data['file_name'] = $file_name;
		$data['display_name'] = $display_name;
		return $data;
	}

	public function insert_dynamic_data($template_html_final){
		// THWEC_Utils_Core::write_log($template_html_final);
		$modified_data = $template_html_final;

		/*-----------------------Placeholder Replacements ------------------------------*/
		$modified_data = str_replace('{th_customer_name}', $this->get_customer_name(), $modified_data);
		$modified_data = str_replace('{th_billing_phone}', $this->get_billing_phone(), $modified_data);
		$modified_data = str_replace('{th_order_id}', $this->get_order_id(), $modified_data);
		$modified_data = str_replace('{th_order_url}', $this->get_order_url(), $modified_data);
		$modified_data = str_replace('{th_billing_email}', $this->get_billing_email(), $modified_data);
		$modified_data = str_replace('{th_site_url}', $this->get_site_url(), $modified_data);
		$modified_data = str_replace('{th_site_name}', $this->get_site_name(), $modified_data);
		$modified_data = str_replace('{th_order_completed_date}', $this->get_order_completed_date(), $modified_data);
		$modified_data = str_replace('{th_order_created_date}', $this->get_order_created_date(), $modified_data);
		$modified_data = str_replace('{th_checkout_payment_url}', $this->get_order_checkout_payment_url(), $modified_data);
		$modified_data = str_replace('{th_payment_method}', $this->get_order_payment_method(), $modified_data);
		$modified_data = str_replace('{th_customer_note}', $this->get_customer_note(), $modified_data);
		$modified_data = str_replace('{th_user_login}', $this->get_user_login(), $modified_data);
		$modified_data = str_replace('{th_user_pass}', $this->get_user_pass(), $modified_data);
		$modified_data = str_replace('{th_account_area_url}', $this->get_account_area_url(), $modified_data);
		$modified_data = str_replace('{th_reset_password_url}', $this->get_reset_password_url(), $modified_data);

		/*-----------------------Address Replacements ------------------------------*/

		$modified_data = str_replace('<span>{billing_address}</span>', $this->billing_data(), $modified_data);
		$modified_data = str_replace('<span>{shipping_address}</span>', $this->shipping_data(), $modified_data);
		$modified_data = str_replace('<span>{customer_address}</span>', $this->customer_data(), $modified_data);
		
		$modified_data = str_replace('<span class="before_customer_table"></span>', $this->add_order_head(), $modified_data);
		$modified_data = str_replace('<span class="after_customer_table"></span>', $this->add_order_foot(), $modified_data);
		$modified_data = str_replace('<span class="before_shipping_table"></span>', $this->add_order_head(), $modified_data);
		$modified_data = str_replace('<span class="after_shipping_table"></span>', $this->add_order_foot(), $modified_data);
		$modified_data = str_replace('<span class="before_billing_table"></span>', $this->add_order_head(), $modified_data);
		$modified_data = str_replace('<span class="after_billing_table"></span>', $this->add_order_foot(), $modified_data);
		

		/*-----------------------Order Table Replacements ------------------------------*/

		$modified_data = str_replace('<span class="loop_start_before_order_table"></span>', $this->order_table_before_loop(), $modified_data); //woocommerce_email_before_order_table 
		$modified_data = str_replace('<span class="loop_end_after_order_table"></span>', $this->order_table_after_loop(), $modified_data); //woocommerce_email_before_order_table 



		$modified_data = str_replace('<span class="woocommerce_email_before_order_table"></span>', $this->order_table_before_hook(), $modified_data); //woocommerce_email_before_order_table 
		$modified_data = str_replace('{order_heading}', $this->order_table_head(), $modified_data); //h2 content
		$modified_data = str_replace('{Order_Product}', $this->order_table_header_product(), $modified_data); //first row content
		$modified_data = str_replace('{Order_Quantity}', $this->order_table_header_qty(), $modified_data); //first row content
		$modified_data = str_replace('{Order_Price}', $this->order_table_header_price(), $modified_data);//first row content
		$modified_data = str_replace('<tr class="item-loop-start"></tr>', $this->order_table_item_loop_start(), $modified_data); // product display loop start
		$modified_data = str_replace('woocommerce_order_item_class-filter1', $this->order_table_class_filter(), $modified_data); // woocommerce filter as class for a <td>
		$modified_data = str_replace('{order_items}', $this->order_table_items(), $modified_data); // Code to display  items without image
		$modified_data = str_replace('{order_items_img}', $this->order_table_items(true), $modified_data); // Code to display  items along with image
		$modified_data = str_replace('{order_items_qty}', $this->order_table_items_qty(), $modified_data);// Code to display  item quantity
		$modified_data = str_replace('{order_items_price}', $this->order_table_items_price(), $modified_data); // Code to display  item price
		$modified_data = str_replace('<tr class="item-loop-end"></tr>',$this->order_table_item_loop_end(), $modified_data);  // product display loop end
		$modified_data = str_replace('<tr class="order-total-loop-start"></tr>', $this->order_table_total_loop_start(), $modified_data); //totals display loop start
		$modified_data = str_replace('{total_label}', $this->order_table_total_labels(), $modified_data); // Code to display <tfoot> total labels
		$modified_data = str_replace('{total_value}', $this->order_table_total_values(), $modified_data); // Code to display <tfoot> total values
		$modified_data = str_replace('<tr class="order-total-loop-end"></tr>', $this->order_table_total_loop_end(), $modified_data); // totals display loop start
		
		/*----------------------- Woocommerce Email Hooks ------------------------------*/
		
		$modified_data = str_replace('<p class="hook-code">{email_header_hook}</p>', $this->thwec_email_hooks('{email_header_hook}'), $modified_data);
		$modified_data = str_replace('<p class="hook-code">{email_order_details_hook}</p>', $this->thwec_email_hooks('{email_order_details_hook}'), $modified_data);
		$modified_data = str_replace('<p class="hook-code">{before_order_table_hook}</p>', $this->thwec_email_hooks('{before_order_table_hook}'), $modified_data);
		$modified_data = str_replace('<p class="hook-code">{after_order_table_hook}</p>', $this->thwec_email_hooks('{after_order_table_hook}'), $modified_data);
		$modified_data = str_replace('<p class="hook-code">{order_meta_hook}</p>', $this->thwec_email_hooks('{order_meta_hook}'), $modified_data);
		$modified_data = str_replace('<p class="hook-code">{customer_details_hook}</p>', $this->thwec_email_hooks('{customer_details_hook}'), $modified_data);
		$modified_data = str_replace('<p class="hook-code">{email_footer_hook}</p>', $this->thwec_email_hooks('{email_footer_hook}'), $modified_data);
		// $modified_data = str_replace('{thwec_billing_body}', $this->billing_details_table(), $modified_data);
		$modified_data = str_replace('<p class="hook-code">{downloadable_product_table}</p>',$this->downloadable_product_table(), $modified_data);

		/*---------------- Checkout fields in email at any position -----------------*/ 
		// $modified_data = preg_replace_callback('/\[WCFE([^][]*)]/', array($this, 'special_wcfe_meta_functions'),$modified_data);
		$modified_data = preg_replace_callback("/$this->wcfe_pattern/", array($this, "special_wcfe_meta_functions"),$modified_data);
		return $modified_data;
	}


	public function special_wcfe_meta_functions($occurances){
		$atts = array();
		if ( $occurances[1] == '[' && $occurances[6] == ']' ) {
			return substr($occurances[0], 1, -1);
		}
		$sec_pattern = $this->get_th_shortcode_atts_regex();
		$content = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $occurances[3]);
		if ( preg_match_all($sec_pattern, $content, $match, PREG_SET_ORDER) ) {
			foreach ($match as $m) {
				if (!empty($m[1]))
					$atts[strtolower($m[1])] = stripcslashes($m[2]);
				elseif (!empty($m[3]))
					$atts[strtolower($m[3])] = stripcslashes($m[4]);
				elseif (!empty($m[5]))
					$atts[strtolower($m[5])] = stripcslashes($m[6]);
				elseif (isset($m[7]) && strlen($m[7]))
					$atts[] = stripcslashes($m[7]);
				elseif (isset($m[8]) && strlen($m[8]))
					$atts[] = stripcslashes($m[8]);
				elseif (isset($m[9]))
					$atts[] = stripcslashes($m[9]);
			}
		}
		$version = THWEC_Admin_Utils::woo_version_check() ? true : false;
		$replace_html = '';
		if($atts){
			$replace_html .= $this->set_order_checkout_fields($atts,$version);

			// switch ($data[0]) {
			// 	case 'thwcfe-input-field':
			// 		$replace_html = $this->set_order_checkout_fields($data,$version);
			// 		break;
			// 	case 'thwepo-input-field':
			// 		$replace_html = $this->set_thwepo_fields($data,$version);
			// 		break;
			// }
			if($replace_html !==''){

				$content_bf = '<?php if(isset($order) && !empty($order)){';
				$content_bf.= '$order_id = $order->get_id();'; 
				$content_bf.= 'if(!empty($order_id)){'; 
				$content_af.= '} } ?>';
				$replace_html = $content_bf.$replace_html.$content_af;
			}
		}
		return $replace_html;
	}

	public function get_th_shortcode_atts_regex() {
		return '/([\w-]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*\'([^\']*)\'(?:\s|$)|([\w-]+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|\'([^\']*)\'(?:\s|$)|(\S+)(?:\s|$)/';
	}

	public function set_order_checkout_fields($wcfe_data,$version){
		$html='';
		$flabel = '';
		$email_visible = '';
		$fvisibility = '';
		// if(count($wcfe_data) > 0){
		
		// }

		$fname = isset($wcfe_data['name']) && !empty($wcfe_data['name']) ? $wcfe_data['name'] : false;
		$flabel = isset($wcfe_data['label']) && !empty($wcfe_data['label']) ? '<b>'.trim($wcfe_data['label'],'"').'</b> : ' : '' ;
		$fvisibility = isset($wcfe_data['visibility']) && !empty($wcfe_data['visibility'])? trim($wcfe_data['visibility'],'"') : '' ;

		if($fname){
			if(in_array($fname, $this->woo_method_variables)){
				$html .= '$field_name = '.$this->get_default_woocommerce_method($fname);
			}else{
				if($version){
					$html .= '$field_name = get_post_meta($order->get_id(),\''.$fname.'\',true);';
				}else{
					$html .= '$field_name = get_post_meta($order->id,\''.$fname.'\',true);';
				}
				$html.= '$json_value = json_decode($field_name,true);';
				$html .= 'if($json_value){';
				$html.= 'if(isset($json_value["name"]) && !empty($json_value["name"]) && isset($json_value["url"])){';
				$html.= '$field_name = "<a href=\'".$json_value[\'url\']."\'>".$json_value[\'name\']."</a>";';
				$html.= '} }';
			}
			if($fvisibility == 'admin'){
				$email_visible = ' && $sent_to_admin';
			}else if($fvisibility == 'customer'){
				$email_visible = ' && !$sent_to_admin';
			}
			$html .= 'if(!empty($field_name)'.$email_visible.'){';
			$html .= '$field_html = "'.$flabel.'".$field_name;';
			$html .= 'echo $field_html;';
			$html .= '}';
		}
		return $html;
	}

	public function set_thwepo_fields($atts,$version){
		$html='';
		$label = '';
		$sep='';
		if(isset($atts[1]) && !empty($atts[1])){
			$name_data = explode('=',$atts[1]);
			if(strtolower(trim($name_data[0])) == 'name'){
				$name = str_replace(array("'", "\""), "", trim($name_data[1]));
				$html .= '$custom_field=array();';
				$html .= 'foreach ( $order->get_items() as $item_id => $item ) {';
				$html .= '$field_data = wc_get_order_item_meta( $item_id,"'.$name.'",true);';
				$html.= '$json_value = json_decode($field_data,true);';
				$html .= 'if($json_value){';
				$html.= 'if(isset($json_value["name"]) && !empty($json_value["name"]) && isset($json_value["url"])){';
				$html.= '$field_name = "<a href=\'".$json_value[\'url\']."\'>".$json_value[\'name\']."</a>";';
				if($version){
					$html.= '$custom_field[$item->get_name()]=$field_name;';
				}else{
					$html.= '$custom_field[$item[\'name\']]=$field_name;';
				}
				$html.= '} }';
				$html.= 'else{';
				if($version){
					$html.= '$custom_field[$item->get_name()] = $field_data;';
				}else{
					$html.= '$custom_field[$item[\'name\']] = $field_data;';
				}
				$html.= '} }';

				if(isset($atts[2]) && !empty($atts[2])){
					$label_data = explode('=',$atts[2]);
					if(strtolower(trim($label_data[0])) == 'label' && !empty($label_data[1])){
						$label = str_replace(array("'", "\""), "", trim($label_data[1]));
						$label = !empty($label) ? '<b>'.$label.'</b>'.' : ' : '';
					}
				}
				$html .= 'if(!empty(array_filter($custom_field)) && is_array($custom_field)){';
				$html .= 'end($custom_field);';
				$html .= '$last_elem = key($custom_field);';
				$html .= 'reset($custom_field);';
				$html .= '$values="";';
				$html .= 'foreach ($custom_field as $key => $value) {';
				$html .= '$sep= $key == $last_elem ? "" : ", ";';
				$html .= '$values .= $custom_field[$key].\' (\'.$key.\')\'.$sep;';
				$html .= '}';
				$html .= '$field_html = "'.$label.'".$values;';
				$html .= 'echo $field_html;';
				$html .= '}';
			}
		}
		return $html;
		// $custom_field = wc_get_order_item_meta( $item_id,'test_pro_field',true);
	} 

	public function get_default_woocommerce_method($f_name){
		$method = '';
		switch ($f_name) {
			case 'billing_first_name':
				$method = '$order->get_billing_first_name();';
				break;
			case 'billing_last_name':
				$method = '$order->get_billing_last_name();';
				break;
			case 'billing_company':
				$method = '$order->get_billing_company();';
				break;
			case 'billing_country':
				$method = '$order->get_billing_country();';
				break;
			case 'billing_address_1':
				$method = '$order->get_billing_address_1();';
				break;
			case 'billing_address_2':
				$method = '$order->get_billing_address_2();';
				break;
			case 'billing_city':
				$method = '$order->get_billing_city();';
				break;
			case 'billing_state':
				$method = '$order->get_billing_state();';
				break;
			case 'billing_postcode':
				$method = '$order->get_billing_postcode();';
				break;
			case 'billing_phone':
				$method = '$order->get_billing_phone();';
				break;
			case 'billing_email':
				$method = '$order->get_billing_email();';
				break;

			case 'shipping_first_name':
				$method = '$order->get_shipping_first_name();';
				break;
			case 'shipping_last_name':
				$method = '$order->get_shipping_last_name();';
				break;
			case 'shipping_company':
				$method = '$order->get_shipping_company();';
				break;
			case 'shipping_country':
				$method = '$order->get_shipping_country();';
				break;
			case 'shipping_address_1':
				$method = '$order->get_shipping_address_1();';
				break;
			case 'shipping_address_2':
				$method = '$order->get_shipping_address_2();';
				break;
			case 'shipping_city':
				$method = '$order->get_shipping_city();';
				break;
			case 'shipping_state':
				$method = '$order->get_shipping_state();';
				break;
			case 'shipping_postcode':
				$method = '$order->get_shipping_postcode();';
				break;
			default:
				$method='';
				break;
		}
		return $method;
	}

	public function get_order_id(){
		$order_id = '<?php if(isset($order)) : ?>';
		$order_id.= '<?php echo $order->get_id();?>';
		$order_id.= '<?php endif; ?>';
		return $order_id;
	}

	public function get_order_url(){
		$order_url = '<?php if(isset($order) && $order->get_user()) : ?>';
		$order_url.= '<?php echo $order->get_view_order_url(); ?>';
		$order_url.= '<?php endif; ?>';
		return $order_url;
	}

	public function get_customer_name(){
		$customer_name = '<?php if(isset($order)) : ?>';
		$customer_name.= '<?php echo $order->get_billing_first_name().\' \'.$order->get_billing_last_name(); ?>';
		$customer_name.= '<?php endif; ?>';
		return $customer_name;
	}

	public function get_billing_phone(){
		$billing_phone = '<?php if ( isset($order) && $order->get_billing_phone() ) : ?>';
		$billing_phone.= '<?php echo esc_html( $order->get_billing_phone() ); ?>';
		$billing_phone.= '<?php endif; ?>';
		return $billing_phone;
	}

	public function get_billing_email(){
		$billing_email = '<?php if ( isset($order) && $order->get_billing_email() ) : ?>';
		$billing_email.= '<?php echo esc_html( $order->get_billing_email() ); ?>';
		$billing_email.= '<?php endif; ?>';
		return $billing_email;
	}

	public function get_site_url(){
		$site_url = '<?php echo get_site_url();?>';
		return $site_url;
	}

	public function get_site_name(){
		$site_name = '<?php echo get_bloginfo();?>';
		return $site_name;
	}

	public function get_order_completed_date(){
		$order_date = '<?php if(isset($order) && $order->has_status( \'completed\' )):?>';
		$order_date.= '<?php echo wc_format_datetime($order->get_date_completed()); ?>';
		$order_date.= '<?php endif; ?>';
		return $order_date;
	}

	public function get_order_created_date(){
		$order_date = '<?php if(isset($order)) : ?>';
		$order_date.= '<?php echo wc_format_datetime($order->get_date_created()); ?>';
		$order_date.= '<?php endif; ?>';
		return $order_date;
	}

	public function get_order_checkout_payment_url(){
		$checkout_payment_url = '<?php if ( isset($order) && $order->has_status( \'pending\' ) ) : ?>
	<?php
	printf(
		wp_kses(
			/* translators: %1s item is the name of the site, %2s is a html link */
			__( \'An order has been created for you on %s\', \'woocommerce\' ),
			array(
				\'a\' => array(
					\'href\' => array(),
				),
			)
		),
		\'<a href="\' . esc_url( $order->get_checkout_payment_url() ) . \'">\' . esc_html__( \'Pay for this order\', \'woocommerce\' ) . \'</a>\'); ?>	
	<?php endif; ?>';
		return $checkout_payment_url;
	}

	public function get_order_payment_method(){
		$payment_method = '<?php if(isset($order)) : ?>';
		$payment_method.= '<?php echo $order->get_payment_method(); ?>';
		$payment_method.= '<?php endif; ?>';
		return $payment_method;
	}

	public function get_customer_note(){
		/*$customer_note = '<blockquote><?php echo wpautop( wptexturize( $customer_note ) ) ?></blockquote>';*/
		$customer_note = '<?php if(isset($customer_note)) : ?>';
		$customer_note.= '<blockquote><?php echo wptexturize( $customer_note ); ?></blockquote>';
		$customer_note.= '<?php endif; ?>';
		return $customer_note;
	}

	public function get_user_login(){
		$user_login = '<?php if(isset($user_login)){ ?>';
		$user_login .= '<?php echo \'<strong>\' . esc_html( $user_login ) . \'</strong>\' ?>';
		$user_login .= '<?php } ?>';
		return $user_login;
	}

	public function get_user_pass(){
		$user_pass = '<?php if ( \'yes\' === get_option( \'woocommerce_registration_generate_password\' ) && isset($password_generated) ) : ?>';
		$user_pass.= '<?php echo \'<strong>\' . esc_html( $user_pass ) . \'</strong>\' ?>';
		$user_pass.= '<?php endif; ?>';
		return $user_pass;
	}

	public function get_account_area_url(){
		$account_area_url = '<?php echo make_clickable( esc_url( wc_get_page_permalink( \'myaccount\' ) ) ); ?>';
		return $account_area_url;
	}

	public function get_reset_password_url(){
		$reset_pass = '<?php if(isset($reset_key) && isset($user_id)): ?>';
		$reset_pass .= '<a class="link" href="<?php echo esc_url( add_query_arg( array( \'key\' => $reset_key, \'id\' => $user_id ), wc_get_endpoint_url( \'lost-password\', \'\', wc_get_page_permalink( \'myaccount\' ) ) ) ); ?>">
			<?php _e( \'Click here to reset your password\', \'woocommerce\' ); ?></a>';
		$reset_pass.= '<?php endif; ?>';
		return $reset_pass;
	}

	public function order_table_total_loop_start(){
		$order_data = '<?php
		if(isset($order)){
			$totals = $order->get_order_item_totals();
			if ( $totals ) {
				$i = 0;
				foreach ( $totals as $total ) {
					$i++;
					?>';
		return $order_data;
	}

	public function order_table_total_labels(){
		$order_data = '<?php echo wp_kses_post( $total[\'label\'] ); ?>';
		return $order_data;
	}

	public function order_table_total_values(){
		$order_data = '<?php echo wp_kses_post( $total[\'value\'] ); ?>';
		return $order_data;
	}


	public function order_table_total_loop_end(){
		$order_data = '<?php
				}
			}
			if ( isset($order) && $order->get_customer_note() ) {
				?>
				<tr>
					<th class="td" scope="row" colspan="2" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php esc_html_e( \'Note:\', \'woocommerce\' ); ?></th>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo wp_kses_post( wptexturize( $order->get_customer_note() ) ); ?></td>
				</tr>
				<?php
			}
		}
			?>';
		return $order_data;
	}

	public function order_table_header_product(){
		$order_data = '<?php esc_html_e( \'Product\', \'woocommerce\' ); ?>';
		return $order_data;
	}
	public function order_table_header_qty(){
		$order_data = '<?php esc_html_e( \'Quantity\', \'woocommerce\' ); ?>';
		return $order_data;
	}
	public function order_table_header_price(){
		$order_data = '<?php esc_html_e( \'Price\', \'woocommerce\' ); ?>';
		return $order_data;
	}
	public function order_table_item_loop_start(){
		$order_data = '<?php 
		$items = $order->get_items();
		foreach ( $items as $item_id => $item ) :
	$product = $item->get_product();
	if ( apply_filters( "woocommerce_order_item_visible", true, $item ) ) {
		?>';
		return $order_data;
	}


	public function order_table_item_loop_end(){
		$order_data = '<?php
		}
		$show_purchase_note=true;
		if ( $show_purchase_note && is_object( $product ) && ( $purchase_note = $product->get_purchase_note() ) ) : ?>
			<tr>
				<td colspan="3" style="text-align:<?php echo $text_align; ?>;vertical-align:middle; border: 1px solid #eee; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) );?></td>
			</tr>
		<?php endif; ?>
		<?php endforeach; ?>';
		return $order_data;
	}

	public function order_table_class_filter(){
		$order_data = '<?php echo esc_attr( apply_filters( \'woocommerce_order_item_class\', \'order_item\', $item, $order ) ); ?>';
		return $order_data;
	}

	public function order_table_items_qty(){
		$order_data = '<?php echo apply_filters( \'woocommerce_email_order_item_quantity\', $item->get_quantity(), $item ); ?>';
		return $order_data;
	}

	public function order_table_items_price(){
		$order_data = '<?php echo $order->get_formatted_line_subtotal( $item ); ?>';
		return $order_data;
	}	

	public function order_table_items($img=false,$sku=false){
		$order_data = '<?php '; 
		if($img){
			$order_data .= '$show_image = true;';
			$order_data .= '$image_size=array( 32, 32);';
		}else{
			$order_data .= '$show_image = false;';
		}
		if($sku){
			$order_data .= '$show_sku = true;';
		}else{
			$order_data .= '$show_sku = false;';
		}
		$order_data .= '

				// Show title/image etc
				if ( $show_image ) {
					echo apply_filters( \'woocommerce_order_item_thumbnail\', \'<div style="margin-bottom: 5px"><img src="\' . ( $product->get_image_id() ? current( wp_get_attachment_image_src( $product->get_image_id(), \'thumbnail\' ) ) : wc_placeholder_img_src() ) . \'" alt="\' . esc_attr__( \'Product image\', \'woocommerce\' ) . \'" height="\' . esc_attr( $image_size[1] ) .\'" width="\' . esc_attr( $image_size[0] ) . \'" style="vertical-align:middle; margin-\' . ( is_rtl() ? \'left\' : \'right\' ) . \': 10px;" /></div>\', $item );
				}

				// Product name
				echo apply_filters( \'woocommerce_order_item_name\', $item->get_name(), $item, false );

				// SKU
				if ( $show_sku && is_object( $product ) && $product->get_sku() ) {
					echo \' (#\' . $product->get_sku() . \')\';
				}

				// allow other plugins to add additional product information here
				do_action( \'woocommerce_order_item_meta_start\', $item_id, $item, $order, $plain_text );

				wc_display_item_meta( $item );

				// allow other plugins to add additional product information here
				do_action( \'woocommerce_order_item_meta_end\', $item_id, $item, $order, $plain_text );

			?>';
		return $order_data;
	}

	public function order_table_before_loop(){
		$loop = '<?php if(isset($order)){ ?>';
		return $loop;
	}
	public function order_table_after_loop(){
		$loop = '<?php } ?>';
		return $loop;
	}


	public function billing_data(){
		$address = '<?php echo ( $address = $order->get_formatted_billing_address() ) ? $address : __( "N/A", "woocommerce"); ?>
				<?php if ( $order->get_billing_phone() ) : ?>
					<br><?php echo esc_html( $order->get_billing_phone() ); ?>
				<?php endif; ?>
				<?php if ( $order->get_billing_email() ) : ?>
					<br><span style="color:inherit;"><?php echo esc_html( $order->get_billing_email() ); ?></span>
				<?php endif; ?>';
		return $address;
	}

	public function shipping_data(){
		$address = '<?php echo $order->get_formatted_shipping_address(); ?>';
		return $address;
	}

	public function customer_data(){
		$address = '<?php echo $order->get_formatted_billing_full_name(); ?><br/><strong><?php echo $order->get_billing_email(); ?></strong>';
		return $address;
	}	
	
	public function order_table_before_hook(){
		$order_data = '<?php $text_align = is_rtl() ? "right" : "left"; ?>';
		/*$order_data .= '<?php do_action( "woocommerce_email_before_order_table", $order, $sent_to_admin, $plain_text, $email ); ?>';*/
		return $order_data;
	}

	public function order_table_head(){
		$order_table = '<?php
		if ( $sent_to_admin ) {
			$before = \'<a class="link" style="color:inherit;" href="\' . esc_url( $order->get_edit_order_url() ) . \'">\';
			$after  = \'</a>\';
		} else {
			$before = "";
			$after  = "";
		}
		echo wp_kses_post( $before . sprintf( __( \'Order #%s\', \'woocommerce\' ) . $after . \' (<time datetime="%s">%s</time>)\', $order->get_order_number(), $order->get_date_created()->format( \'c\' ), wc_format_datetime( $order->get_date_created() ) ) );
	?>';

		return $order_table;
	}

	public function downloadable_product_table(){
		$downloadable_product = '<?php $text_align = is_rtl() ? \'right\' : \'left\';?>';

		$downloadable_product .= '<?php if(isset($order)){';
		$downloadable_product .= '$downloads = $order->get_downloadable_items();';
		$downloadable_product .= '$columns   = apply_filters(
					\'woocommerce_email_downloads_columns\', array(
					\'download-product\' => __( \'Product\', \'woocommerce\' ),
					\'download-expires\' => __( \'Expires\', \'woocommerce\' ),
					\'download-file\'    => __( \'Download\', \'woocommerce\' ),
					)
				); ?>';
		$downloadable_product .= '<?php if($downloads) {?>';
		$downloadable_product .=  '<h2 class="woocommerce-order-downloads__title"><?php esc_html_e( \'Downloads\', \'woocommerce\' ); ?></h2>';
		$downloadable_product .= '<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; margin-bottom: 40px;" border="1">
		<thead>
			<tr>
				<?php foreach ( $columns as $column_id => $column_name ) : ?>
					<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo esc_html( $column_name ); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<?php foreach ( $downloads as $download ) : ?>
			<tr>
				<?php foreach ( $columns as $column_id => $column_name ) : ?>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
						<?php
						if ( has_action( \'woocommerce_email_downloads_column_\' . $column_id ) ) {
							do_action( \'woocommerce_email_downloads_column_\' . $column_id, $download, $plain_text );
						} else {
							switch ( $column_id ) {
								case \'download-product\':
									?>
									<a href="<?php echo esc_url( get_permalink( $download[\'product_id\'] ) ); ?>"><?php echo wp_kses_post( $download[\'product_name\'] ); ?></a>
									<?php
									break;
								case \'download-file\':
									?>
									<a href="<?php echo esc_url( $download[\'download_url\'] ); ?>" class="woocommerce-MyAccount-downloads-file button alt"><?php echo esc_html( $download[\'download_name\'] ); ?></a>
									<?php
									break;
								case \'download-expires\':
									if ( ! empty( $download[\'access_expires\'] ) ) {
										?>
										<time datetime="<?php echo esc_attr( date( \'Y-m-d\', strtotime( $download[\'access_expires\'] ) ) ); ?>" title="<?php echo esc_attr( strtotime( $download[\'access_expires\'] ) ); ?>"><?php echo esc_html( date_i18n( get_option( \'date_format\' ), strtotime( $download[\'access_expires\'] ) ) ); ?></time>
										<?php
									} else {
										esc_html_e( \'Never\', \'woocommerce\' );
									}
									break;
							}
						}
						?>
					</td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?></table>';
			$downloadable_product .= '<?php } } ?>';
			return $downloadable_product;

	}

	public function downloadable_product_tablesss(){
		$downloadable_product = '<?php $text_align = is_rtl() ? \'right\' : \'left\';?>';
		$downloadable_product .= '<?php if($downloads && $columns) {?>';
		$downloadable_product .=  '<h2 class="woocommerce-order-downloads__title"><?php esc_html_e( \'Downloads\', \'woocommerce\' ); ?></h2>';
		$downloadable_product .= '<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; margin-bottom: 40px;" border="1">
		<thead>
			<tr>
				<?php foreach ( $columns as $column_id => $column_name ) : ?>
					<th class="td" scope="col" style="text-align:<?php echo esc_attr( $text_align ); ?>;"><?php echo esc_html( $column_name ); ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<?php foreach ( $downloads as $download ) : ?>
			<tr>
				<?php foreach ( $columns as $column_id => $column_name ) : ?>
					<td class="td" style="text-align:<?php echo esc_attr( $text_align ); ?>;">
						<?php
						if ( has_action( \'woocommerce_email_downloads_column_\' . $column_id ) ) {
							do_action( \'woocommerce_email_downloads_column_\' . $column_id, $download, $plain_text );
						} else {
							switch ( $column_id ) {
								case \'download-product\':
									?>
									<a href="<?php echo esc_url( get_permalink( $download[\'product_id\'] ) ); ?>"><?php echo wp_kses_post( $download[\'product_name\'] ); ?></a>
									<?php
									break;
								case \'download-file\':
									?>
									<a href="<?php echo esc_url( $download[\'download_url\'] ); ?>" class="woocommerce-MyAccount-downloads-file button alt"><?php echo esc_html( $download[\'download_name\'] ); ?></a>
									<?php
									break;
								case \'download-expires\':
									if ( ! empty( $download[\'access_expires\'] ) ) {
										?>
										<time datetime="<?php echo esc_attr( date( \'Y-m-d\', strtotime( $download[\'access_expires\'] ) ) ); ?>" title="<?php echo esc_attr( strtotime( $download[\'access_expires\'] ) ); ?>"><?php echo esc_html( date_i18n( get_option( \'date_format\' ), strtotime( $download[\'access_expires\'] ) ) ); ?></time>
										<?php
									} else {
										esc_html_e( \'Never\', \'woocommerce\' );
									}
									break;
							}
						}
						?>
					</td>
				<?php endforeach; ?>
			</tr>
			<?php endforeach; ?></table>';
			$downloadable_product .= '<?php } ?>';
			return $downloadable_product;
	}

	public function add_order_head(){
		$order_head = '<?php if(isset($order)){?>';
		return $order_head;
	}
	
	public function add_order_foot(){
		$order_foot = '<?php } ?>';
		return $order_foot;
	}


	public function thwec_email_hooks($hook){
		switch($hook){
			 case '{email_header_hook}':
                $hook ='<?php do_action( \'woocommerce_email_header\', $email_heading, $email ); ?>'; 
                break;
 			case '{email_order_details_hook}': 
 				$hook = '<?php if(isset($order)){ 
 					do_action( \'woocommerce_email_order_details\', $order, $sent_to_admin, $plain_text, $email ); 
 				}?>';
 				break;
  			case '{before_order_table_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action(\'woocommerce_email_before_order_table\', $order, $sent_to_admin, $plain_text, $email); 
  				}?>';
 				break;
  			case '{after_order_table_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action(\'woocommerce_email_after_order_table\', $order, $sent_to_admin, $plain_text, $email); 
  				}?>';
 				break;
  			case '{order_meta_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action( \'woocommerce_email_order_meta\', $order, $sent_to_admin, $plain_text, $email ); 
  				}?>';
 				break;
  			case '{customer_details_hook}': 
  				$hook = '<?php if(isset($order)){ 
  					do_action( \'woocommerce_email_customer_details\', $order, $sent_to_admin, $plain_text, $email ); 
  				}?>';
 				break;
 			case '{email_footer_hook}':
                $hook = '<?php do_action( \'woocommerce_email_footer\', $email ); ?>';
                break;
            case '{email_footer_blogname}':
            $hook = '<?php echo wpautop( wp_kses_post( wptexturize( apply_filters( \'woocommerce_email_footer_text\', \'\' ) ) ) ); ?>';
            default:
                $hook = '';
		}
		return $hook;
	}
}

endif;