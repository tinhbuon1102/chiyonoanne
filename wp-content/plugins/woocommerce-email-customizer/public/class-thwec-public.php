<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package    woocommerce-email-customizer-pro
 * @subpackage woocommerce-email-customizer-pro/public
 */
if(!defined('WPINC')){	die; }

if(!class_exists('THWEC_Public')):
 
class THWEC_Public {
	private $plugin_name;
	private $version;
	public $templates;
    public $current_email;
	
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_action('after_setup_theme', array($this, 'define_public_hooks'));
	}

	public function enqueue_styles_and_scripts() {
		global $wp_scripts;
		
		if(is_product()){
			$debug_mode = apply_filters('thwec_debug_mode', false);
			$suffix = $debug_mode ? '' : '.min';
			$jquery_version = isset($wp_scripts->registered['jquery-ui-core']->ver) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';
			
			//$this->enqueue_styles($suffix, $jquery_version);
			//$this->enqueue_scripts($suffix, $jquery_version);
		}
	}
	
	/*private function enqueue_styles($suffix, $jquery_version) {
		wp_enqueue_style('thwec-public-style', THWEC_ASSETS_URL_PUBLIC . 'css/thwec-public'. $suffix .'.css', $this->version);
	}

	private function enqueue_scripts($suffix, $jquery_version) {
		wp_register_script('thwec-public-script', THWEC_ASSETS_URL_PUBLIC . 'js/thwec-public'. $suffix .'.js', array('jquery-ui-i18n', 'select2'), $this->version, true );
		
		wp_enqueue_script('thwec-public-script');
		
		$script_var = array(
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
		);
		wp_localize_script('thwec-public-script', 'thwec_public_var', $script_var);
	}*/
	
	public function define_public_hooks(){
		add_filter('woocommerce_locate_template', array($this, 'woo_locate_template'), 999, 3);
	}

	public function custom_function(){
		return null;
	}

	public function woo_locate_template($template, $template_name, $template_path){
		$template_map = THWEC_Utils::get_template_map();
		if($template_map){ 
		    $search = array('emails/', '.php');
            $replace = array('', '');
		    $template_name = str_replace($search, $replace, $template_name);
			
			if(array_key_exists($template_name, $template_map)) {
    			$template_name = $template_map[$template_name];
            
    			if($template_name != ''){
        			return $this->get_email_template_path($template_name);  

    			}		
    		}
    	}
       	return $template;
	}

	public function get_email_template_path($template_name){
    	$tpath = false;
    	$email_template_path = THWEC_CUSTOM_TEMPLATE_PATH.$template_name.'.php';
    	if(file_exists($email_template_path)){
    	   	$tpath = $email_template_path;
    	}
    	return $tpath;
    }
	
}
endif;