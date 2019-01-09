<?php
/*
 Plugin Name: N-Media WooCommerce PPOM PRO
Plugin URI: http://najeebmedia.com/wordpress-plugin/woocommerce-personalized-product-option/
Description: This plugin allow WooCommerce Store Admin to create unlimited input fields and files to attach with Product Page
Version: 16.0
Author: Najeeb Ahmad
Text Domain: ppom
Domain Path: /languages
Author URI: http://www.najeebmedia.com/
WC requires at least: 3.0.0
WC tested up to: 3.2.6
*/

// Authencation checking
if( ! class_exists('NM_Auth') ) {
	$_auth_class = dirname(__FILE__).'/Auth/auth.php';
	if( file_exists($_auth_class))
		include_once($_auth_class);
	else
		die('Reen, Reen, BUMP! not found '.$_auth_class);
}

/**
 * Plugin API Validation
 * *** DO NOT REMOVE These Lines
 * */
define('PPOM_PLUGIN_PATH', "ppom-pro/ppom.php");
define('PPOM_REDIRECT_URL', admin_url( 'admin.php?page=ppom' ));
define('PPOM_PLUGIN_ID', 2235);
NM_AUTH(PPOM_PLUGIN_PATH, PPOM_REDIRECT_URL, PPOM_PLUGIN_ID);

define('PPOM_PRO_URL', untrailingslashit(plugin_dir_url( __FILE__ )) );
class PPOM_PRO {
    
    private static $ins = null;
    
    function __construct() {
        
        if( ! $this->is_ppom_installed() ) {
            add_action( 'admin_notices', array($this, 'ppom_notice_not_installed') );
            return '';
        }
        
        if( ! $this->is_ppom_validated() ) {
            add_action( 'admin_notices', array($this, 'ppom_notice_not_validated') );
            return '';
        }
        
        // Remove Get pro notice from admin
        remove_action('ppom_after_ppom_field_admin', 'ppom_admin_pro_version_notice', 10);
        
        // Loading all input in PRO
        add_filter('ppom_all_inputs', array($this, 'load_all_inputs'), 10, 2);
        
        // Adding PRO Scripts
        add_action('ppom_after_scripts_loaded', array($this, 'load_ppom_scripts'), 15, 2);
        
        // Show description tooltip
        add_filter('ppom_field_description', array($this, 'show_tooltip'), 15, 2);
        
        // Multiple meta selection
        // @since 15.0
        add_filter('ppom_select_meta_in_product', array($this, 'multiple_meta_in_product'), 99, 3);
        
        // Order Again
        add_filter('woocommerce_order_again_cart_item_data', array($this, 'order_again'), 99, 3);
        
        global $pagenow;
		if (! empty($pagenow) && ('post-new.php' === $pagenow || 'post.php' === $pagenow ))
		    add_action('admin_enqueue_scripts', array($this, 'product_page_script'));
    }
    
    
	public static function get_instance()
	{
		// create a new object if it doesn't exist.
		is_null(self::$ins) && self::$ins = new self;
		return self::$ins;
	}
	
	// Admin notices if PPOM is not installed
	function ppom_notice_not_installed() {
	    
	    $ppom_install_url = admin_url( 'plugin-install.php?s=ppom&tab=search&type=term' );
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e( 'PPOM Basic Version is NOT installed, please download it first.', 'ppom' ); ?>
            <a class="button" href="<?php echo esc_url($ppom_install_url)?>"><?php _e('Install Plugin','ppom')?></a></p>
        </div>
        <?php
    }
    
    // Admin notices if PPOM is not validated
	function ppom_notice_not_validated() {
	    
	    $ppom_install_url = admin_url( 'admin.php?page=ppom_auth' );
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e( 'PPOM PRO version is validated, please provide valid api key to unlock all fields.', 'ppom' ); ?>
            <a class="button" href="<?php echo esc_url($ppom_install_url)?>"><?php _e('Add API Key','ppom')?></a></p>
        </div>
        <?php
    }
	
	// Checking if PPOM Basic version is installed
	function is_ppom_installed() {
	    
	    $return = false;
	    
	    if( class_exists('NM_PersonalizedProduct') ) 
	        $return = true;
	   
	   return $return;
	}
	
	// Checking if PPOM not validated
	function is_ppom_validated() {
	    
	    $return = false;
	   
	   if( NM_AUTH(PPOM_PLUGIN_PATH, PPOM_REDIRECT_URL, PPOM_PLUGIN_ID) -> api_key_found() ) 
	        $return = true;
	    
	    return $return;
	}
	
	// Loading all PRO inputs
	function load_all_inputs( $inputs_array, $inputObj) {
	    
	   // ppom_pa($inputs_array);
	    $inputs_array['number'] 	= $inputObj->get_input ( 'number' );
		$inputs_array['email'] 	    = $inputObj->get_input ( 'email' );
		$inputs_array['date'] 		= $inputObj->get_input ( 'date' );
		$inputs_array['daterange']  = $inputObj->get_input ( 'daterange' );
	    $inputs_array['color']      = $inputObj->get_input ( 'color' );				
		$inputs_array['file']   	= $inputObj->get_input ( 'file' );
		$inputs_array['cropper']    = $inputObj->get_input ( 'cropper' );
		$inputs_array['timezone']   = $inputObj->get_input ( 'timezone' );
		$inputs_array['quantities'] = $inputObj->get_input ( 'quantities' );
		$inputs_array['image']  	= $inputObj->get_input ( 'image' );
		$inputs_array['facebook']   = $inputObj->get_addon ( 'facebook' );	//Addon
		$inputs_array['pricematrix']= $inputObj->get_input ( 'pricematrix' );
		$inputs_array['section']    = $inputObj->get_input ( 'section' );
		$inputs_array['palettes']   = $inputObj->get_input ( 'palettes' );
		$inputs_array['audio']  	= $inputObj->get_input ( 'audio' );
		$inputs_array['measure']  	= $inputObj->get_input ( 'measure' );
		
		// checking event calendar addon is enable
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'ppom-addon-eventcalendar/ppom-addon-eventcalendar.php' ) ) {
			$inputs_array['eventcalendar'] = $inputObj->get_input ( 'eventcalendar' );
		}
		
		
		return $inputs_array;
	}
	
	function load_ppom_scripts( $ppom_meta_id, $product ) {
		
		// accordion files
		
		if( apply_filters('ppom_collapse_fields', false) ) {
		    wp_enqueue_style('ppom-accordion', PPOM_URL."/css/ziehharmonika.css");
		    wp_enqueue_script('ppom-accordion', PPOM_URL."/js/ziehharmonika.js", array('jquery'), 2.0, true);
		    wp_enqueue_script('ppom-accordion-script', PPOM_URL."/js/ppom-collapse.js", array('jquery'), 2.0, true);
		}
	}
	
	function show_tooltip( $description, $meta ) {
		
		$show_tooltip	= (isset($meta['desc_tooltip']) && $meta['desc_tooltip'] == 'on') ? true : false;
	
		if( $show_tooltip ) {
			
			$description = ( !empty($description) ) ? ' <span class="ppom-tooltip fa fa-question-circle" title="'.esc_attr($description).'" data-html="true" data-toggle="tooltip"></span>' : '';
		}
		
		return $description;
	}
	
	/**
     * @param $html - not used
     * @param $ppom - our meta object for the product
     * @param array $all_meta - array of meta items
     * @return string - html for ul list of meta groups, each with checkbox to select
     */
    function multiple_meta_in_product($html, $ppom, $all_meta)
    {
        /** CHANGES: add drag/drop capability to enable re-ordering of the meta groups by the user.
         * The selected meta WILL then be saved in the list display order (top to bottom) to wp_postmeta
         * When loaded, we have added code to show the selected meta first, in same order as saved, with unselected below.
         */
		$output = []; //build the meta id's and names in the correct order, selected first, then the rest

        $selected = ($ppom->has_multiple_meta ? $ppom->meta_id : $ppom->single_meta_id);
        // NOTE: $selected can be an array e.g. [ 0=>'9',1='15',2='6'], OR numeric id (single-meta),
        // NOTE: OR null (if no meta-group selected at all)

        // build all selected groups into $output array
        // ppom_pa($all_meta);

        if ( is_array($selected) ) { // multiple_meta
            foreach ($selected as $id) {  // step through the array of selected ID's
                foreach ($all_meta as $meta) {
                    if ($id==$meta->productmeta_id) { // push onto end of array
                        array_push($output, array('productmeta_id' => $meta->productmeta_id,
                                                  'productmeta_name' => $meta->productmeta_name));
                    }
                }
            }
        }else{
            if (is_numeric($selected)) // single_meta
                array_push($output, array('productmeta_id' => $selected,
                'productmeta_name' => $ppom->productmeta_name));
        }

        // Add all groups NOT selected onto the _end_ of the $output array.
        foreach ($all_meta as $meta) {

            if ( is_null($selected) || !in_array($meta->productmeta_id, $selected))
            array_push($output, array('productmeta_id' => $meta->productmeta_id, 'productmeta_name' => $meta->productmeta_name));
        }

        // create the html for list of all meta groups in the sidebar meta box, including attributes for 'sortable' javascript to work
        // and Edit link for each group as well!
        $html = '<ul id="ppom_meta_sortable" class="ui-sortable">';

        foreach ($output as $meta) {
            $html .= '<li class="ui-state-default ui-sortable"><span class="hndle ui-sortable-handle">';
            $html .= '<label for="ppom-' . esc_attr($meta['productmeta_id']) . '">';
            $html .= '<input name="ppom_product_meta[]" type="checkbox" style="cursor:auto;-webkit-appearance:checkbox" value="' . esc_attr($meta['productmeta_id']) . '" ';

            if ( !is_null($selected) AND in_array($meta['productmeta_id'], $selected)) {
                $html .= ' checked ';
            }
            $html .= 'id="ppom-' . esc_attr($meta['productmeta_id']) . '">';

            $html .= $meta['productmeta_id'] . ' -' . stripslashes($meta['productmeta_name']);

            // add Edit link as original
            //@TODO Note it requires extra style to color the link, as jquery-ui overrides the link color

            $ppom_setting = admin_url('admin.php?page=ppom');
            $url_edit = add_query_arg(array('productmeta_id'=> $meta['productmeta_id'], 'do_meta'=>'edit'), $ppom_setting);

            $html .= ' - <a style="font-weight:600; color:#0073aa" href="'.esc_url($url_edit).'">';
            $html .= __('Edit', 'ppom');
            $html .= '</a>';

            $html .= '</label></span>';


            $html .= '</li>';
        }

        $html .= '</ul>';
        
        return $html;
    }
	
	function order_again( $cart_item_meta, $item, $order ) {
		
		$ppom_fields = wc_get_order_item_meta( $item->get_id(), '_ppom_fields');
		
		if( ! $ppom_fields ) return $cart_item_meta;
		
		$cart_item_meta['ppom'] = $ppom_fields;
		return $cart_item_meta;
	}
	
	
	function product_page_script() {
		global $post;
		
		if( !empty($post) && $post->post_type == 'product' ) {
			$ppom_sortable = PPOM_PRO_URL.'/js/ppom-sortable.js';
			wp_enqueue_script('ppom-sortable', $ppom_sortable, array('jquery'));
		}
	}
}

add_action('plugins_loaded', 'PPOM_PRO');
function PPOM_PRO(){
	return PPOM_PRO::get_instance();
}