<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package    woocommerce-email-customizer-pro
 * @subpackage woocommerce-email-customizer-pro/admin
 */
if(!defined('WPINC')){	die; }

if(!class_exists('THWEC_Admin')):
 
class THWEC_Admin {
	private $plugin_name;
	private $version;
	public $admin_instance = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->init();
	}

	public function init() {		
		if(is_admin() || (defined( 'DOING_AJAX' ) && DOING_AJAX)){
			$this->admin_instance = THWEC_Admin_Settings_General::instance();
		}
	}
	
	public function enqueue_styles_and_scripts($hook) {
		if(strpos($hook, 'woocommerce_page_th_email_customizer_pro') === false) {
			return;
		}
		$debug_mode = apply_filters('thwec_debug_mode', false);
		$suffix = $debug_mode ? '' : '.min';
		
		$this->enqueue_styles($suffix);
		$this->enqueue_scripts($suffix);
		wp_enqueue_media();
	}

	private function enqueue_styles($suffix) {
		wp_enqueue_style('jquery-ui-style', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css?ver=1.11.4');
		wp_enqueue_style('woocommerce_admin_styles', THWEC_WOO_ASSETS_URL.'css/admin.css');
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('thwec-admin-style', THWEC_ASSETS_URL_ADMIN . 'css/thwec-admin'. $suffix .'.css', $this->version);
	}

	private function enqueue_scripts($suffix) {
		// $deps = array('jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'jquery-ui-dialog','jquery-ui-resizable', 'jquery-ui-widget', 'jquery-ui-tabs','jquery-tiptip', 'woocommerce_admin', 'wc-enhanced-select', 'select2', 'wp-color-picker');
		
		$deps = array('jquery', 'jquery-ui-core', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'jquery-ui-dialog','jquery-ui-resizable', 'jquery-ui-widget', 'jquery-ui-tabs','jquery-tiptip', 'woocommerce_admin', 'wc-enhanced-select', 'select2', 'wp-color-picker');
		
		wp_enqueue_script( 'thwec-admin-script', THWEC_ASSETS_URL_ADMIN . 'js/thwec-admin'. $suffix .'.js', $deps, $this->version, false );
		$script_var = array(
            'admin_url' => admin_url(),
            'ajaxurl'   => admin_url( 'admin-ajax.php' ),
            //'userdata'	=> $this->user_details(),
            'image_folder_path' => THWEC_ASSETS_URL_ADMIN,
        );
		//wp_localize_script('thwec-admin-script', 'thwec_var', $script_var);
	}
	
	public function admin_menu() {
		$this->screen_id = add_submenu_page('woocommerce', THWEC_i18n::t('WooCommerce Email Customizer'), 
		THWEC_i18n::t('Email Customizer'), 'manage_woocommerce', 'th_email_customizer_pro', array($this, 'output_settings'));
	}
	
	public function add_screen_id($ids){
		$ids[] = 'woocommerce_page_th_email_customizer_pro';
		$ids[] = strtolower( THWEC_i18n::t('WooCommerce') ) .'_page_th_email_customizer_pro';

		return $ids;
	}
	
	public function plugin_action_links($links) {
		$settings_link = '<a href="'.admin_url('admin.php?page=th_email_customizer_pro').'">'. __('Settings') .'</a>';
		array_unshift($links, $settings_link);
		return $links;
	}
	
	public function plugin_row_meta( $links, $file ) {
		if(THWEC_BASE_NAME == $file) {
			$doc_link = esc_url('https://www.themehigh.com/help-guides/woocommerce-email-customizer/');
			$support_link = esc_url('https://www.themehigh.com/help-guides/');
				
			$row_meta = array(
				'docs' => '<a href="'.$doc_link.'" target="_blank" aria-label="'.THWEC_i18n::esc_attr__t('View plugin documentation').'">'.THWEC_i18n::esc_html__t('Docs').'</a>',
				'support' => '<a href="'.$support_link.'" target="_blank" aria-label="'. THWEC_i18n::esc_attr__t('Visit premium customer support' ) .'">'. THWEC_i18n::esc_html__t('Premium support') .'</a>',
			);

			return array_merge( $links, $row_meta );
		}
		return (array) $links;
	}
	
	public function output_settings(){
		$tab  = isset( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : 'general_settings';
		if($tab === 'template_settings'){			
			$template_settings = THWEC_Admin_Settings_Templates::instance();	
			$template_settings->render_page();	
		}else if($tab === 'advanced_settings'){			
			$advanced_settings = THWEC_Admin_Settings_Advanced::instance();	
			$advanced_settings->render_page();			
		}else if($tab === 'license_settings'){			
			$license_settings = THWEC_Admin_Settings_License::instance();	
			$license_settings->render_page();	
		}else{
			$general_settings = THWEC_Admin_Settings_General::instance();	
			$general_settings->render_page();
		}
	}
}

endif;