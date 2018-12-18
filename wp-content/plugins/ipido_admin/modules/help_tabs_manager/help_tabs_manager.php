<?php

class Ipido_admin_Module_Help_Tabs_Manager extends Ipido_admin_Module{

    public function __construct() {
        parent::__construct();

        $this->name     	= 'help_tabs_manager';
		$this->version  	= '1.0.0';
		$this->plugin_name 	= false;
		$this->unique 		= false; // use the main/core plugin unique id
    }

    public function init(){
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies(){

    }

    private function define_admin_hooks(){
        /**
         * Load Module Config Settings Options
         * 
         * @since 2.0.0
         */
        $this->add_action('csf_framework_load_config', $this, 'load_module_config');


        /**
         * Enqueue Scripts
         * 
         * @since 2.0.0
         */
		$this->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );
		

		/**
		 * Custom Contextual Help Tabs Manager
		 * 
		 * @since 2.0.0
		 */
		$this->add_action('admin_head', $this, 'custom_help_tabs');
    }

    private function define_public_hooks(){

    }



    /**
     * ------------------------------------------------------------------------------------------------
     * 
     * Module Specific Functionality
     * 
     * ------------------------------------------------------------------------------------------------
     */


    
    /**
	 * Load Config Settings - CSFRAMEWORK
     * 
     * @since 2.0.0
	 */
	public function load_module_config(){
		require_once( 'config/'.$this->name.'-settings.php'  );
    }
    


    /**
     * Enqueue Scripts
     * 
     * @since 2.0.0
     */
    public function enqueue_scripts(){
        wp_enqueue_script('ipido_admin_'.$this->name, plugin_dir_url( __FILE__ ) . 'js/'.$this->name.'.js', array('ipido_admin'), $this->version, false );
	}



	/**
	 * Custom Help Tabs
	 *
	 * @since 1.2.0
	 */

	/**
	 * Get Active Menu Pages for Admin Settings Dropdown field
	 *
	 * @return array
	 * @since 1.2.0
	 */
	public static function cs_ipido_get_menu_pages(){
		global $menu, $submenu;
		$output = array();
		if (current_user_can('manage_options')){
			$core_items = array('menu-dashboard','menu-posts','menu-media','menu-pages','menu-comments','menu-appearance','menu-plugins','menu-users','menu-tools','menu-settings');
			if (is_array($menu)){
				foreach($menu as $key => $item){
					/**
					 * The elements in each item array are :
					 * 0: Menu title
					 * 1: Minimum level or capability required.
					 * 2: The URL of the item's file
					 * 3: Page Title
					 * 4: Classes
					 * 5: ID
					 * 6: Icon for top level menu
					 **/

					$maybe_separator 	= ($item[4] == 'wp-menu-separator') ? true : false;
					if (!$maybe_separator){
						$item_name 		= $item[0];
						$item_url 		= $item[2];
						$item_id 		= $item[5];
						$item_key 		= (in_array($item_id,$core_items)) ? $item_url : $item_id;
		
	
						if (isset($submenu[$item_url])){
							$item_submenu 	= $submenu[$item_url];
							$_item_submenu 	= array();
	
							foreach($item_submenu as $subkey => $subitem){
								$subitem_name 	= $subitem[0];
								$subitem_url 	= $subitem[2];
								$_item_submenu[$subitem_url] = wp_strip_all_tags($subitem_name);
							}
	
							$output[wp_strip_all_tags($item_name)] = $_item_submenu;
						} else {
							$output[$item_key] = wp_strip_all_tags($item_name);
						}
						
					}
				}
			}
		}
		return $output;
	}
	function custom_help_tabs(){
		if (cs_get_settings('helptabs_status')){
			global $pagenow;
			global $hook_suffix;
			require_once 'includes/class-ipido_admin-help-tabs.php';
			
			$current_screen 	= get_current_screen();
			// $current_screen_id 	= $current_screen->id;
			$current_screen_id 	= $hook_suffix;
			// $current_screen_id 	= str_replace(array('toplevel_page_', 'settings_page_'),array('',''), $current_screen_id);
			$current_screen_id = preg_replace('/^(.*?)_page_/','', $current_screen_id);

			$all_tabs 				= cs_get_settings('helptabs_container');
			$page_index 			= cs_search_array_for_id($all_tabs,'helptab_page',$current_screen_id);
			$helptab_data 			= (isset($all_tabs[$page_index])) ? $all_tabs[$page_index] : null;
			$helptab_page 			= (isset($helptab_data['helptab_page'])) ? $helptab_data['helptab_page'] : null;
			$helptab_remove			= (isset($helptab_data['helptab_remove_original'])) ? $helptab_data['helptab_remove_original'] : false;
			$helptab_sidebar_status	= (isset($helptab_data['helptab_custom_sidebar'])) ? $helptab_data['helptab_custom_sidebar'] : false;
			$helptab_sidebar 		= (isset($helptab_data['helptab_sidebar_content'])) ? $helptab_data['helptab_sidebar_content'] : false;
			$helptab_tabs			= (isset($helptab_data['helptab_items'])) ? $helptab_data['helptab_items'] : false;
			
			$helptabs = array(
				'tabs_remove_all'	=> $helptab_remove,
				'tabs'				=> $helptab_tabs,
				'sidebar_state'		=> $helptab_sidebar_status,
				'sidebar'			=> $helptab_sidebar,
			);
	
			if ($helptab_tabs){
				if ($current_screen_id == $helptab_page){
					new Ipido_admin_help_tabs($helptabs);
				}
			} else {

			}
		} else {

		}
	}
	



}