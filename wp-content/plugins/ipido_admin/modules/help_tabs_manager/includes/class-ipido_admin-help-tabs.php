<?php
/**
 * Custom Help Tabs Manager
 *
 * @link       http://www.castorstudio.com
 * @since      1.2.0
 *
 * @package    Ipido_admin
 * @subpackage Ipido_admin/admin/includes
 */

class Ipido_admin_help_tabs{
	private $tabs;
	
	static public function init($tabs){
		$class = __CLASS__ ;
		new $class;
	}
	
	public function __construct($helptabs){
		// add_action( "load-toplevel_page_cs-ipido-admin-settings", array( $this, 'add_tabs' ), 20 );
		$this->helptabs = $helptabs;
		$this->add_tabs();
	}

	public function add_tabs(){
		$tabs = $this->helptabs;
		if (is_array($tabs)){
			$screen = get_current_screen();
			
			// Remove Previous Help Tabs
			if ($tabs['tabs_remove_all']){
				$screen->remove_help_tabs();
			}
			
			// Create Help Tabs
			if (is_array($tabs['tabs'])){
				foreach ($tabs['tabs'] as $id => $data){
					$screen->add_help_tab(
						array(
							'id'       => cs_sanitize($data['tab_title']),
							'title'    => $data['tab_title'],
							'content'  => $data['tab_content'],
							// 'callback' => array($this,'prepare'),
							)
						);
					}
				}
				
				// Create Help Sidebar
				if ($tabs['sidebar_state']){
					$screen->set_help_sidebar($tabs['sidebar']);
				}
			}
		}
		
		public function prepare($screen,$tab){
			printf('<p>%s</p>',__($tab['callback'][0]->tabs[ $tab['id'] ]['tab_content'],'ipido_admin'));
		}
	}