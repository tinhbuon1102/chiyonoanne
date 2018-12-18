<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
*
* Theme: 	custom
*
* @since 1.0.0
* @version 1.0.0
*
*/

require_once CS_PLUGIN_PATH .'/admin/includes/class-colors.php';
use CastorStudio\Colors;

class Ipido_admin_Theme_custom{
	public function __construct(){
		$this->theme_name   = 'custom';
		$this->theme_prefix = 'ipido_theme-'.$this->theme_name;
	}
	
	public function get_settings(){
		$theme_id 		= $this->theme_prefix;
		$theme_prefix 	= $theme_id .'__';
		$styles_path 	= CS_PLUGIN_URI .'/themes/'.$this->theme_name;
		
		$settings 		= array(
			'dependency'	=> array('theme_'.$this->theme_name,'==','true'),
			'id'			=> $theme_id,
			'type'			=> 'fieldset',
			'fields'		=> array(
				array(
					'type'			=> 'subheading',
					'content'		=> __('Choose a Theme or create your own!','ipido_admin'),
				),
				array(
					'id'			=> $theme_prefix.'scheme',
					'type'			=> 'color_theme',
					// 'title'			=> __('Choose Admin Color Scheme'),
					'options'		=> array(
						'sections'	=> array(
							'header-brand'	=> array(
								'title'	=> __('Header Brand','ipido_admin'),
								'desc'	=> __('Set header brand colors','ipido_admin'),
							),
							'header' 	=> array(
								'title'	=> __('Header Navbar','ipido_admin'),
								'desc'	=> __('Set header titlebar and toolbar colors here','ipido_admin'),
							),
							'sidebar'	=> array(
								'title' => __('Sidebar','ipido_admin'),
								'desc'	=> __('Set sidebar colors here','ipido_admin'),
							),
							'general'		=> array(
								'title'	=> __('General Colors','ipido_admin'),
								'desc'	=> __('Set primary, accent, variants and button colors here','ipido_admin'),
							),
							'ui'		=> array(
								'title'	=> __('UI Elements','ipido_admin'),
								'desc'	=> __('Set colors used by UI Elements','ipido_admin'),
							),
						),
						'colors'	=> array(
							'header-brand' => array(
								array(
									'id'	=> 'header-brand-bg',
									'title'	=> __('Box Background','ipido_admin'),
									'color'	=> 'rgb(255,12,231)',
								),
								array(
									'id'	=> 'header-brand-icon-bg',
									'title'	=> __('Icon Background','ipido_admin'),
								),
								array(
									'id'	=> 'header-brand-icon-color',
									'title'	=> __('Icon Color','ipido_admin'),
								),
								array(
									'id'	=> 'header-brand-text',
									'title'	=> __('Text Color','ipido_admin'),
									'color'	=> 'rgb(255,12,231)',
								),
								array(
									'id'	=> 'header-brand-subtitle-text',
									'title'	=> __('Subtitle Text Color','ipido_admin'),
									'color'	=> 'rgb(255,12,231)',
								),
								array(
									'id'	=> 'header-brand-border',
									'title'	=> __('Border Color','ipido_admin'),
									'color'	=> 'rgb(255,12,231)',
								),
							),
							'header'	=> array(
								array(
									'type'		=> 'group',
									'title'		=> __('Navbar Header','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'header-bg',
											'title'	=> __('Background Color','ipido_admin'),
										),
										array(
											'id'	=> 'header-border',
											'title'	=> __('Border Color','ipido_admin'),
										),
										array(
											'id'	=> 'header-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
									),
								),
								array(
									'type'		=> 'group',
									'title'		=> __('Navbar Toolbar','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'header-toolbar-text',
											'title'	=> __('Toolbar Color','ipido_admin'),
										),
										array(
											'id'	=> 'header-toolbar-text-hover',
											'title'	=> __('Toolbar Color Hover','ipido_admin'),
										),
									),
								),
							),
							'sidebar'	=> array(
								array(
									'type'	=> 'group',
									'title'	=> __('First Level Item: Normal','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'sidebar-bg',
											'title'	=> __('Background Color','ipido_admin'),
										),
										array(
											'id'	=> 'sidebar-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
									),
								),
								array(
									'type'	=> 'group',
									'title'	=> __('First Level Item: Hover','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'sidebar-hover-bg',
											'title'	=> __('Background Color','ipido_admin'),
										),
										array(
											'id'	=> 'sidebar-hover-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
									),
								),
								array(
									'type'	=> 'group',
									'title'	=> __('First Level Item: Active/Expanded','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'sidebar-active-bg',
											'title'	=> __('Background Color','ipido_admin'),
										),
										array(
											'id'	=> 'sidebar-active-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
										array(
											'id'	=> 'sidebar-active-hover-text',
											'title'	=> __('Text Hover Color','ipido_admin'),
										),
										array(
											'id'	=> 'sidebar-active-highlight',
											'title'	=> __('Highlight Border Color','ipido_admin'),
										),
									),
								),
								array(
									'type'	=> 'group',
									'title'	=> __('Current Menu Item: Normal','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'sidebar-current-bg',
											'title'	=> __('Background Color','ipido_admin'),
										),
										array(
											'id'	=> 'sidebar-current-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
										array(
											'id'	=> 'sidebar-current-highlight',
											'title'	=> __('Highlight Border Color','ipido_admin'),
										),
									),
								),
								array(
									'type'	=> 'group',
									'title'	=> __('Current Menu Item: Hover','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'sidebar-current-hover-bg',
											'title'	=> __('Background Color','ipido_admin'),
										),
										array(
											'id'	=> 'sidebar-current-hover-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
									),
								),
								array(
									'type'	=> 'group',
									'title'	=> __('Current Menu Item: Submenu','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'sidebar-current-subitem-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
										array(
											'id'	=> 'sidebar-current-subitem-hover-text',
											'title'	=> __('Text Color Hover','ipido_admin'),
										),
										array(
											'id'	=> 'sidebar-current-subitem-current-text',
											'title'	=> __('Current Subitem Text Color','ipido_admin'),
										),
									),
								),
							),
							'general'	=> array(
								array(
									'type'	=> 'group',
									'title'	=> __('Primary Colors','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'primary-normal',
											'title'	=> __('Primary Normal','ipido_admin'),
										),
										array(
											'id'	=> 'primary-light',
											'title'	=> __('Primary Light','ipido_admin'),
										),
										array(
											'id'	=> 'accent',
											'title'	=> __('Accent Normal','ipido_admin'),
										),
									),
								),
								array(
									'type'	=> 'group',
									'title'	=> __('Button Primary','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'button-primary-ini',
											'title'	=> __('Gradient Initial Color','ipido_admin'),
										),
										array(
											'id'	=> 'button-primary-end',
											'title'	=> __('Gradient End Color','ipido_admin'),
										),
										array(
											'id'	=> 'button-primary-border',
											'title'	=> __('Border Color','ipido_admin'),
										),
										array(
											'id'	=> 'button-primary-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
									),
								),
								array(
									'type'	=> 'group',
									'title'	=> __('Button Base','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'button-base-ini',
											'title'	=> __('Gradient Initial Color','ipido_admin'),
										),
										array(
											'id'	=> 'button-base-end',
											'title'	=> __('Gradient End Color','ipido_admin'),
										),
										array(
											'id'	=> 'button-base-border',
											'title'	=> __('Border Color','ipido_admin'),
										),
										array(
											'id'	=> 'button-base-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
									),
								),
							),
							'ui'		=> array(
								array(
									'type'	=> 'group',
									'title'	=> __('Body','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'body-bg',
											'title'	=> __('Background Color','ipido_admin'),
											'color'	=> '#ecf0f1',
										),
										array(
											'id'	=> 'body-text',
											'title'	=> __('Text Color','ipido_admin'),
											'color'	=> 'rgb(87,87,87)',
										),
									),
								),
								array(
									'type'	=> 'group',
									'title'	=> __('Input Element','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'input-bg',
											'title'	=> __('Background Color','ipido_admin'),
										),
										array(
											'id'	=> 'input-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
										array(
											'id'	=> 'input-border',
											'title'	=> __('Border Color','ipido_admin'),
										),
										array(
											'id'	=> 'input-border-focus',
											'title'	=> __('Border Color on Focus','ipido_admin'),
										),
									),
								),
								array(
									'type'	=> 'group',
									'title'	=> __('Card Element','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'card-bg',
											'title'	=> __('Background Color','ipido_admin'),
										),
										array(
											'id'	=> 'card-border',
											'title'	=> __('Border Color','ipido_admin'),
										),
										array(
											'id'	=> 'card-border-bottom',
											'title'	=> __('Title Border Bottom Color','ipido_admin'),
										),
										array(
											'id'	=> 'card-title-bg',
											'title'	=> __('Title Background Color','ipido_admin'),
										),
										array(
											'id'	=> 'card-title-text',
											'title'	=> __('Title Text Color','ipido_admin'),
										),
									),
								),
								array(
									'type'	=> 'group',
									'title'	=> __('Dropdown Element: Normal','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'dropdown-bg',
											'title'	=> __('Background Color','ipido_admin'),
										),
										array(
											'id'	=> 'dropdown-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
										array(
											'id'	=> 'dropdown-border',
											'title'	=> __('Border Color','ipido_admin'),
										),
									),
								),
								array(
									'type'	=> 'group',
									'title'	=> __('Dropdown Element: Hover','ipido_admin'),
									'colors'	=> array(
										array(
											'id'	=> 'dropdown-hover-bg',
											'title'	=> __('Background Color','ipido_admin'),
										),
										array(
											'id'	=> 'dropdown-hover-text',
											'title'	=> __('Text Color','ipido_admin'),
										),
									),
								),
							),
						),
					),
					'schemes'		=> array(
						'white-blue' => array (
							'name' => 'white-blue',
							'scheme' => array (
								'header-brand-bg' => '#1487ca',
								'header-brand-icon-bg' => '#2c3e4f',
								'header-brand-icon-color' => '#3a99d9',
								'header-brand-text' => '#ecf0f1',
								'header-brand-subtitle-text' => '#ecf0f1',
								'header-brand-border' => '#4e9cc9',
								'header-bg' => '#ffffff',
								'header-border' => '#e5e5e5',
								'header-text' => '#d4dadb',
								'header-toolbar-text' => '#d4dadb',
								'header-toolbar-text-hover' => '#3a99d9',
								'sidebar-bg' => '#444444',
								'sidebar-text' => '#888888',
								'sidebar-hover-bg' => '#2e81b7',
								'sidebar-hover-text' => '#333333',
								'sidebar-active-bg' => '#333333',
								'sidebar-active-text' => '#666666',
								'sidebar-active-hover-text' => '#999999',
								'sidebar-active-highlight' => '#3a99d9',
								'sidebar-current-bg' => '#2d2d2d',
								'sidebar-current-text' => '#2e81b7',
								'sidebar-current-highlight' => '#2e81b7',
								'sidebar-current-hover-bg' => '#282828',
								'sidebar-current-hover-text' => '#2e81b7',
								'sidebar-current-subitem-text' => '#707070',
								'sidebar-current-subitem-hover-text' => '#a3a3a3',
								'sidebar-current-subitem-current-text' => '#ecf0f1',
								'primary-normal' => '#1b98e0',
								'primary-light' => '#67b3e0',
								'accent' => '#447490',
								'button-primary-ini' => '#3a99d9',
								'button-primary-end' => '#2e81b7',
								'button-primary-border' => '#357aa8',
								'button-primary-text' => '#ecf0f1',
								'button-base-ini' => '#36404a',
								'button-base-end' => '#36404a',
								'button-base-border' => '#293542',
								'button-base-text' => '#ffffff',
								'body-bg' => '#f5f5f5',
								'body-text' => '#757575',
								'input-bg' => '#ffffff',
								'input-text' => '#3d3d3d',
								'input-border' => '#a7bbc9',
								'input-border-focus' => '#4e9cc9',
								'card-bg' => '#ffffff',
								'card-border' => '#cecece',
								'card-border-bottom' => '#4e9cc9',
								'card-title-bg' => '#1b98e0',
								'card-title-text' => '#ecf0f1',
								'dropdown-bg' => '#474747',
								'dropdown-text' => '#878787',
								'dropdown-border' => '#3d3d3d',
								'dropdown-hover-bg' => '#1b98e0',
								'dropdown-hover-text' => '#333333',
							),
						),
						'grey-n-yellow' => array (
							'name' => 'grey-n-yellow',
							'scheme' => 
							array (
								'header-brand-bg' => '#fe4641',
								'header-brand-icon-bg' => '#333333',
								'header-brand-icon-color' => '#fda527',
								'header-brand-text' => '#fde3a7',
								'header-brand-subtitle-text' => '#fde3a7',
								'header-brand-border' => '#ff7772',
								'header-bg' => '#ffffff',
								'header-border' => '#cecece',
								'header-text' => '#d0d0d0',
								'header-toolbar-text' => '#d0d0d0',
								'header-toolbar-text-hover' => '#fda527',
								'sidebar-bg' => '#333333',
								'sidebar-text' => '#888888',
								'sidebar-hover-bg' => '#fda527',
								'sidebar-hover-text' => '#333333',
								'sidebar-active-bg' => '#252525',
								'sidebar-active-text' => '#666666',
								'sidebar-active-hover-text' => '#999999',
								'sidebar-active-highlight' => '#fda527',
								'sidebar-current-bg' => '#2d2d2d',
								'sidebar-current-text' => '#fde3a7',
								'sidebar-current-highlight' => '#fda527',
								'sidebar-current-hover-bg' => '#282828',
								'sidebar-current-hover-text' => '#fde3a7',
								'sidebar-current-subitem-text' => '#707070',
								'sidebar-current-subitem-hover-text' => '#a3a3a3',
								'sidebar-current-subitem-current-text' => '#fe4641',
								'primary-normal' => '#fda527',
								'primary-light' => '#fcc06c',
								'accent' => '#fe4641',
								'button-primary-ini' => '#f9b32f',
								'button-primary-end' => '#f39c12',
								'button-primary-border' => '#f1892d',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '',
								'button-base-end' => '',
								'button-base-border' => '',
								'button-base-text' => '',
								'body-bg' => '#e0e0e0',
								'body-text' => '#757575',
								'input-bg' => '#ffffff',
								'input-text' => '#3d3d3d',
								'input-border' => '#d3abab',
								'input-border-focus' => '#fe4641',
								'card-bg' => '#ffffff',
								'card-border' => '#cecece',
								'card-border-bottom' => '#ff7772',
								'card-title-bg' => '#fe4641',
								'card-title-text' => '#fde3a7',
								'dropdown-bg' => '#474747',
								'dropdown-text' => '#878787',
								'dropdown-border' => '#3d3d3d',
								'dropdown-hover-bg' => '#fda527',
								'dropdown-hover-text' => '#333333',
							),
						),
						'grey-n-pink' => array (
							'name' => 'grey-n-pink',
							'scheme' => 
							array (
								'header-brand-bg' => '#e95095',
								'header-brand-icon-bg' => '#333333',
								'header-brand-icon-color' => '#ff7cb8',
								'header-brand-text' => '#ffbcd8',
								'header-brand-subtitle-text' => '#ffbcd8',
								'header-brand-border' => '#ff7cb8',
								'header-bg' => '#ffffff',
								'header-border' => '#cecece',
								'header-text' => '#d0d0d0',
								'header-toolbar-text' => '#d0d0d0',
								'header-toolbar-text-hover' => '#ffbcd8',
								'sidebar-bg' => '#333333',
								'sidebar-text' => '#888888',
								'sidebar-hover-bg' => '#cea0e4',
								'sidebar-hover-text' => '#333333',
								'sidebar-active-bg' => '#252525',
								'sidebar-active-text' => '#666666',
								'sidebar-active-hover-text' => '#999999',
								'sidebar-active-highlight' => '#ff7cb8',
								'sidebar-current-bg' => '#2d2d2d',
								'sidebar-current-text' => '#ffbcd8',
								'sidebar-current-highlight' => '#ff7cb8',
								'sidebar-current-hover-bg' => '#282828',
								'sidebar-current-hover-text' => '#ffbcd8',
								'sidebar-current-subitem-text' => '#707070',
								'sidebar-current-subitem-hover-text' => '#a3a3a3',
								'sidebar-current-subitem-current-text' => '#e95095',
								'primary-normal' => '#e95095',
								'primary-light' => '#ff7cb8',
								'accent' => '#9b59b6',
								'button-primary-ini' => '#e95095',
								'button-primary-end' => '#ea4c88',
								'button-primary-border' => '#ca2c68',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '',
								'button-base-end' => '',
								'button-base-border' => '',
								'button-base-text' => '',
								'body-bg' => '#e0e0e0',
								'body-text' => '#757575',
								'input-bg' => '#ffffff',
								'input-text' => '#3d3d3d',
								'input-border' => '#c3a2d6',
								'input-border-focus' => '#9b59b6',
								'card-bg' => '#ffffff',
								'card-border' => '#cecece',
								'card-border-bottom' => '#ff7cb8',
								'card-title-bg' => '#e95095',
								'card-title-text' => '#ffbcd8',
								'dropdown-bg' => '#474747',
								'dropdown-text' => '#878787',
								'dropdown-border' => '#3d3d3d',
								'dropdown-hover-bg' => '#cea0e4',
								'dropdown-hover-text' => '#333333',
							),
						),
						'grey-n-sand' => array (
							'name' => 'grey-n-sand',
							'scheme' => 
							array (
								'header-brand-bg' => '#daa48a',
								'header-brand-icon-bg' => '#333333',
								'header-brand-icon-color' => '#ffc29b',
								'header-brand-text' => '#8e5c3b',
								'header-brand-subtitle-text' => '#8e5c3b',
								'header-brand-border' => '#ffc29b',
								'header-bg' => '#ffffff',
								'header-border' => '#cecece',
								'header-text' => '#d0d0d0',
								'header-toolbar-text' => '#d0d0d0',
								'header-toolbar-text-hover' => '#f6c4a3',
								'sidebar-bg' => '#333333',
								'sidebar-text' => '#888888',
								'sidebar-hover-bg' => '#be8c6b',
								'sidebar-hover-text' => '#333333',
								'sidebar-active-bg' => '#252525',
								'sidebar-active-text' => '#666666',
								'sidebar-active-hover-text' => '#999999',
								'sidebar-active-highlight' => '#d4a281',
								'sidebar-current-bg' => '#2d2d2d',
								'sidebar-current-text' => '#d4a281',
								'sidebar-current-highlight' => '#d4a281',
								'sidebar-current-hover-bg' => '#282828',
								'sidebar-current-hover-text' => '#be8c6b',
								'sidebar-current-subitem-text' => '#707070',
								'sidebar-current-subitem-hover-text' => '#a3a3a3',
								'sidebar-current-subitem-current-text' => '#ffdcb5',
								'primary-normal' => '#f6c4a3',
								'primary-light' => '#ffdcb5',
								'accent' => '#c19079',
								'button-primary-ini' => '#f6c4a3',
								'button-primary-end' => '#d4a281',
								'button-primary-border' => '#be8c6b',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '',
								'button-base-end' => '',
								'button-base-border' => '',
								'button-base-text' => '',
								'body-bg' => '#e0e0e0',
								'body-text' => '#757575',
								'input-bg' => '#ffffff',
								'input-text' => '#3d3d3d',
								'input-border' => '#d6c9c5',
								'input-border-focus' => '#daa48a',
								'card-bg' => '#ffffff',
								'card-border' => '#cecece',
								'card-border-bottom' => '#ffc29b',
								'card-title-bg' => '#daa48a',
								'card-title-text' => '#8e5c3b',
								'dropdown-bg' => '#474747',
								'dropdown-text' => '#878787',
								'dropdown-border' => '#3d3d3d',
								'dropdown-hover-bg' => '#be8c6b',
								'dropdown-hover-text' => '#333333',
							),
						),
						'nautical-white' => array (
							'name' => 'nautical-white',
							'scheme' => 
							array (
								'header-brand-bg' => '#2c3e50',
								'header-brand-icon-bg' => '#fc4349',
								'header-brand-icon-color' => '#ffffff',
								'header-brand-text' => '#c5d3e2',
								'header-brand-subtitle-text' => '#c5d3e2',
								'header-brand-border' => '#44586e',
								'header-bg' => '#ffffff',
								'header-border' => '#d3d8db',
								'header-text' => '#d0d0d0',
								'header-toolbar-text' => '#d0d0d0',
								'header-toolbar-text-hover' => '#2c3e50',
								'sidebar-bg' => '#384b5f',
								'sidebar-text' => '#8c9aa9',
								'sidebar-hover-bg' => '#44617f',
								'sidebar-hover-text' => '#bfc6cc',
								'sidebar-active-bg' => '#2c3e50',
								'sidebar-active-text' => '#6b7c89',
								'sidebar-active-hover-text' => '#bfc6cc',
								'sidebar-active-highlight' => '#fc4349',
								'sidebar-current-bg' => '#2c3e50',
								'sidebar-current-text' => '#bfc6cc',
								'sidebar-current-highlight' => '#fc4349',
								'sidebar-current-hover-bg' => '#253747',
								'sidebar-current-hover-text' => '#bfc6cc',
								'sidebar-current-subitem-text' => '#8c9aa9',
								'sidebar-current-subitem-hover-text' => '#bfc6cc',
								'sidebar-current-subitem-current-text' => '#fe4641',
								'primary-normal' => '#fc4349',
								'primary-light' => '#fc767b',
								'accent' => '#6dbcdb',
								'button-primary-ini' => '#fc4349',
								'button-primary-end' => '#d1383d',
								'button-primary-border' => '#bf3338',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '',
								'button-base-end' => '',
								'button-base-border' => '',
								'button-base-text' => '',
								'body-bg' => 'rgba(124,137,155,0.2)',
								'body-text' => '#757575',
								'input-bg' => '#ffffff',
								'input-text' => '#3d3d3d',
								'input-border' => '#bfc6cc',
								'input-border-focus' => '#44617f',
								'card-bg' => '#ffffff',
								'card-border' => '#cecece',
								'card-border-bottom' => '#44586e',
								'card-title-bg' => '#2c3e50',
								'card-title-text' => '#c5d3e2',
								'dropdown-bg' => '#384b5f',
								'dropdown-text' => '#8c9aa9',
								'dropdown-border' => '#2c3e50',
								'dropdown-hover-bg' => '#44617f',
								'dropdown-hover-text' => '#bfc6cc',
							),
						),
						'nautical-forte' => array (
							'name' => 'nautical-forte',
							'scheme' => 
							array (
								'header-brand-bg' => '#fc4349',
								'header-brand-icon-bg' => '#384b5f',
								'header-brand-icon-color' => '#ffffff',
								'header-brand-text' => '#ffffff',
								'header-brand-subtitle-text' => '#ffffff',
								'header-brand-border' => '#fc8084',
								'header-bg' => '#ffffff',
								'header-border' => '#d3d8db',
								'header-text' => '#d0d0d0',
								'header-toolbar-text' => '#d0d0d0',
								'header-toolbar-text-hover' => '#2c3e50',
								'sidebar-bg' => '#384b5f',
								'sidebar-text' => '#8c9aa9',
								'sidebar-hover-bg' => '#44617f',
								'sidebar-hover-text' => '#bfc6cc',
								'sidebar-active-bg' => '#2c3e50',
								'sidebar-active-text' => '#6b7c89',
								'sidebar-active-hover-text' => '#bfc6cc',
								'sidebar-active-highlight' => '#fc4349',
								'sidebar-current-bg' => '#2c3e50',
								'sidebar-current-text' => '#bfc6cc',
								'sidebar-current-highlight' => '#fc4349',
								'sidebar-current-hover-bg' => '#253747',
								'sidebar-current-hover-text' => '#bfc6cc',
								'sidebar-current-subitem-text' => '#8c9aa9',
								'sidebar-current-subitem-hover-text' => '#bfc6cc',
								'sidebar-current-subitem-current-text' => '#fe4641',
								'primary-normal' => '#fc4349',
								'primary-light' => '#fc6267',
								'accent' => '#6dbcdb',
								'button-primary-ini' => '#fc4349',
								'button-primary-end' => '#d1383d',
								'button-primary-border' => '#bf3338',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '',
								'button-base-end' => '',
								'button-base-border' => '',
								'button-base-text' => '',
								'body-bg' => '#f2f4f5',
								'body-text' => '#757575',
								'input-bg' => '#ffffff',
								'input-text' => '#3d3d3d',
								'input-border' => '#bfc6cc',
								'input-border-focus' => '#44617f',
								'card-bg' => '#ffffff',
								'card-border' => '#cecece',
								'card-border-bottom' => '#fc8084',
								'card-title-bg' => '#fc4349',
								'card-title-text' => '#ffffff',
								'dropdown-bg' => '#384b5f',
								'dropdown-text' => '#8c9aa9',
								'dropdown-border' => '#2c3e50',
								'dropdown-hover-bg' => '#44617f',
								'dropdown-hover-text' => '#bfc6cc',
							),
						),
						'dark-grey' => array (
							'name' => 'dark-grey',
							'scheme' => 
							array (
								'header-brand-bg' => '#36404a',
								'header-brand-icon-bg' => '#3bafda',
								'header-brand-icon-color' => '#ffffff',
								'header-brand-text' => '#98a6a9',
								'header-brand-subtitle-text' => '#98a6a9',
								'header-brand-border' => '#36404a',
								'header-bg' => '#323b44',
								'header-border' => 'rgba(211,216,219,0.01)',
								'header-text' => 'rgba(255,255,255,0.6)',
								'header-toolbar-text' => 'rgba(255,255,255,0.6)',
								'header-toolbar-text-hover' => '#ffffff',
								'sidebar-bg' => '#36404a',
								'sidebar-text' => '#9a9fa4',
								'sidebar-hover-bg' => '#2f363f',
								'sidebar-hover-text' => '#ffffff',
								'sidebar-active-bg' => '#2f363f',
								'sidebar-active-text' => '#9a9fa4',
								'sidebar-active-hover-text' => '#ffffff',
								'sidebar-active-highlight' => '#3bafda',
								'sidebar-current-bg' => '#2f363f',
								'sidebar-current-text' => '#ffffff',
								'sidebar-current-highlight' => '#3bafda',
								'sidebar-current-hover-bg' => '#2f363f',
								'sidebar-current-hover-text' => '#ffffff',
								'sidebar-current-subitem-text' => '#9a9fa4',
								'sidebar-current-subitem-hover-text' => '#ffffff',
								'sidebar-current-subitem-current-text' => '#ffffff',
								'primary-normal' => '#3bafda',
								'primary-light' => '#84c2d8',
								'accent' => '#f76397',
								'button-primary-ini' => '#3bafda',
								'button-primary-end' => '#3497c1',
								'button-primary-border' => '#3288ba',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '#36404a',
								'button-base-end' => '#36404a',
								'button-base-border' => '#293542',
								'button-base-text' => '#ffffff',
								'body-bg' => '#323b44',
								'body-text' => '#98a6ad',
								'input-bg' => '#434f5c',
								'input-text' => '#ffffff',
								'input-border' => '#4c5a67',
								'input-border-focus' => '#526170',
								'card-bg' => '#36404a',
								'card-border' => 'rgba(54,64,74,0.08)',
								'card-border-bottom' => '#36404a',
								'card-title-bg' => '#36404a',
								'card-title-text' => '#98a6ad',
								'dropdown-bg' => '#3f4a56',
								'dropdown-text' => '#e6e6e6',
								'dropdown-border' => 'rgba(52,65,79,0.01)',
								'dropdown-hover-bg' => '#434f5c',
								'dropdown-hover-text' => '#ffffff',
							),
						),
						'dark-grey-white-n-blue' => array (
							'name' => 'dark-grey-white-n-blue',
							'scheme' => 
							array (
								'header-brand-bg' => '#ffffff',
								'header-brand-icon-bg' => '#323b44',
								'header-brand-icon-color' => '#ffffff',
								'header-brand-text' => '#323b44',
								'header-brand-subtitle-text' => '#323b44',
								'header-brand-border' => '#ffffff',
								'header-bg' => '#323b44',
								'header-border' => 'rgba(211,216,219,0.01)',
								'header-text' => 'rgba(255,255,255,0.6)',
								'header-toolbar-text' => 'rgba(255,255,255,0.6)',
								'header-toolbar-text-hover' => '#ffffff',
								'sidebar-bg' => '#ffffff',
								'sidebar-text' => '#717987',
								'sidebar-hover-bg' => 'rgba(59,175,218,0.45)',
								'sidebar-hover-text' => '#3bafda',
								'sidebar-active-bg' => '#f5f5f5',
								'sidebar-active-text' => '#75798b',
								'sidebar-active-hover-text' => '#3bafda',
								'sidebar-active-highlight' => '#3bafda',
								'sidebar-current-bg' => '#f5f5f5',
								'sidebar-current-text' => '#3bafda',
								'sidebar-current-highlight' => '#3bafda',
								'sidebar-current-hover-bg' => '#f5f5f5',
								'sidebar-current-hover-text' => '#3bafda',
								'sidebar-current-subitem-text' => '#75798b',
								'sidebar-current-subitem-hover-text' => '#3bafda',
								'sidebar-current-subitem-current-text' => '#3bafda',
								'primary-normal' => '#3bafda',
								'primary-light' => '#84c2d8',
								'accent' => '#f76397',
								'button-primary-ini' => '#3bafda',
								'button-primary-end' => '#3497c1',
								'button-primary-border' => '#3288ba',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '#36404a',
								'button-base-end' => '#36404a',
								'button-base-border' => '#293542',
								'button-base-text' => '#ffffff',
								'body-bg' => '#323b44',
								'body-text' => '#98a6ad',
								'input-bg' => '#434f5c',
								'input-text' => '#ffffff',
								'input-border' => '#4c5a67',
								'input-border-focus' => '#526170',
								'card-bg' => '#36404a',
								'card-border' => 'rgba(54,64,74,0.08)',
								'card-border-bottom' => '#36404a',
								'card-title-bg' => '#36404a',
								'card-title-text' => '#98a6ad',
								'dropdown-bg' => '#3f4a56',
								'dropdown-text' => '#e6e6e6',
								'dropdown-border' => 'rgba(52,65,79,0.01)',
								'dropdown-hover-bg' => '#434f5c',
								'dropdown-hover-text' => '#ffffff',
							),
						),
						'dark-blue-amethyst' => array (
							'name' => 'dark-blue-amethyst',
							'scheme' => 
							array (
								'header-brand-bg' => '#222c3c',
								'header-brand-icon-bg' => '#9b59b6',
								'header-brand-icon-color' => '#ffffff',
								'header-brand-text' => '#e6eaee',
								'header-brand-subtitle-text' => '#e6eaee',
								'header-brand-border' => '#2a3547',
								'header-bg' => '#273142',
								'header-border' => '#313d4f',
								'header-text' => '#ffffff',
								'header-toolbar-text' => '#7f8fa4',
								'header-toolbar-text-hover' => '#ffffff',
								'sidebar-bg' => '#222c3c',
								'sidebar-text' => '#8c9aa9',
								'sidebar-hover-bg' => '#1d2531',
								'sidebar-hover-text' => '#b7c0cd',
								'sidebar-active-bg' => '#1d2531',
								'sidebar-active-text' => '#b7c0cd',
								'sidebar-active-hover-text' => '#ffffff',
								'sidebar-active-highlight' => '#9b59b6',
								'sidebar-current-bg' => '#1d2531',
								'sidebar-current-text' => '#b7c0cd',
								'sidebar-current-highlight' => '#9b59b6',
								'sidebar-current-hover-bg' => '#1d2531',
								'sidebar-current-hover-text' => '#b7c0cd',
								'sidebar-current-subitem-text' => '#8c9aa9',
								'sidebar-current-subitem-hover-text' => '#ffffff',
								'sidebar-current-subitem-current-text' => '#9b59b6',
								'primary-normal' => '#9b59b6',
								'primary-light' => '#a380b5',
								'accent' => '#f39c12',
								'button-primary-ini' => '#9b59b6',
								'button-primary-end' => '#8e44ad',
								'button-primary-border' => '#763891',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '#506072',
								'button-base-end' => '#506072',
								'button-base-border' => '#506072',
								'button-base-text' => '#ffffff',
								'body-bg' => '#1b2431',
								'body-text' => '#7f8fa4',
								'input-bg' => '#273142',
								'input-text' => '#ffffff',
								'input-border' => '#313d4f',
								'input-border-focus' => '#9b59b6',
								'card-bg' => '#273142',
								'card-border' => '#313d4f',
								'card-border-bottom' => '#273142',
								'card-title-bg' => '#273142',
								'card-title-text' => '#ffffff',
								'dropdown-bg' => '#273142',
								'dropdown-text' => '#ffffff',
								'dropdown-border' => '#313d4f',
								'dropdown-hover-bg' => '#313d4f',
								'dropdown-hover-text' => '#b676f2',
							),
						),
						'dark-blue-carrot' => array (
							'name' => 'dark-blue-carrot',
							'scheme' => 
							array (
								'header-brand-bg' => '#222c3c',
								'header-brand-icon-bg' => '#e67e22',
								'header-brand-icon-color' => '#ffffff',
								'header-brand-text' => '#e6eaee',
								'header-brand-subtitle-text' => '#e6eaee',
								'header-brand-border' => '#2a3547',
								'header-bg' => '#273142',
								'header-border' => '#313d4f',
								'header-text' => '#ffffff',
								'header-toolbar-text' => '#7f8fa4',
								'header-toolbar-text-hover' => '#ffffff',
								'sidebar-bg' => '#222c3c',
								'sidebar-text' => '#8c9aa9',
								'sidebar-hover-bg' => '#1d2531',
								'sidebar-hover-text' => '#b7c0cd',
								'sidebar-active-bg' => '#1d2531',
								'sidebar-active-text' => '#b7c0cd',
								'sidebar-active-hover-text' => '#ffffff',
								'sidebar-active-highlight' => '#e67e22',
								'sidebar-current-bg' => '#1d2531',
								'sidebar-current-text' => '#b7c0cd',
								'sidebar-current-highlight' => '#e67e22',
								'sidebar-current-hover-bg' => '#1d2531',
								'sidebar-current-hover-text' => '#b7c0cd',
								'sidebar-current-subitem-text' => '#8c9aa9',
								'sidebar-current-subitem-hover-text' => '#ffffff',
								'sidebar-current-subitem-current-text' => '#e67e22',
								'primary-normal' => '#e67e22',
								'primary-light' => '#e5934b',
								'accent' => '#8e44ad',
								'button-primary-ini' => '#e67e22',
								'button-primary-end' => '#d35400',
								'button-primary-border' => '#bc4500',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '#506072',
								'button-base-end' => '#506072',
								'button-base-border' => '#506072',
								'button-base-text' => '',
								'body-bg' => '#1b2431',
								'body-text' => '#7f8fa4',
								'input-bg' => '#273142',
								'input-text' => '#ffffff',
								'input-border' => '#313d4f',
								'input-border-focus' => '#e67e22',
								'card-bg' => '#273142',
								'card-border' => '#313d4f',
								'card-border-bottom' => '#273142',
								'card-title-bg' => '#273142',
								'card-title-text' => '#ffffff',
								'dropdown-bg' => '#273142',
								'dropdown-text' => '#ffffff',
								'dropdown-border' => '#313d4f',
								'dropdown-hover-bg' => '#313d4f',
								'dropdown-hover-text' => '#e67e22',
							),
						),
						'dark-blue-emerald' => array (
							'name' => 'dark-blue-emerald',
							'scheme' => 
							array (
								'header-brand-bg' => '#222c3c',
								'header-brand-icon-bg' => '#1abc9c',
								'header-brand-icon-color' => '#ffffff',
								'header-brand-text' => '#e6eaee',
								'header-brand-subtitle-text' => '#e6eaee',
								'header-brand-border' => '#2a3547',
								'header-bg' => '#273142',
								'header-border' => '#313d4f',
								'header-text' => '#ffffff',
								'header-toolbar-text' => '#7f8fa4',
								'header-toolbar-text-hover' => '#ffffff',
								'sidebar-bg' => '#222c3c',
								'sidebar-text' => '#8c9aa9',
								'sidebar-hover-bg' => '#1d2531',
								'sidebar-hover-text' => '#b7c0cd',
								'sidebar-active-bg' => '#1d2531',
								'sidebar-active-text' => '#b7c0cd',
								'sidebar-active-hover-text' => '#ffffff',
								'sidebar-active-highlight' => '#1abc9c',
								'sidebar-current-bg' => '#1d2531',
								'sidebar-current-text' => '#b7c0cd',
								'sidebar-current-highlight' => '#1abc9c',
								'sidebar-current-hover-bg' => '#1d2531',
								'sidebar-current-hover-text' => '#b7c0cd',
								'sidebar-current-subitem-text' => '#8c9aa9',
								'sidebar-current-subitem-hover-text' => '#ffffff',
								'sidebar-current-subitem-current-text' => '#1abc9c',
								'primary-normal' => '#1abc9c',
								'primary-light' => '#63d3b1',
								'accent' => '#e67e22',
								'button-primary-ini' => '#1abc9c',
								'button-primary-end' => '#27ae60',
								'button-primary-border' => '#2e915e',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '#506072',
								'button-base-end' => '#506072',
								'button-base-border' => '#506072',
								'button-base-text' => '',
								'body-bg' => '#1b2431',
								'body-text' => '#7f8fa4',
								'input-bg' => '#273142',
								'input-text' => '#ffffff',
								'input-border' => '#313d4f',
								'input-border-focus' => '#1abc9c',
								'card-bg' => '#273142',
								'card-border' => '#313d4f',
								'card-border-bottom' => '#273142',
								'card-title-bg' => '#273142',
								'card-title-text' => '#ffffff',
								'dropdown-bg' => '#273142',
								'dropdown-text' => '#ffffff',
								'dropdown-border' => '#313d4f',
								'dropdown-hover-bg' => '#313d4f',
								'dropdown-hover-text' => '#1abc9c',
							),
						),
						'light-blue-belize' => array (
							'name' => 'light-blue-belize',
							'scheme' => 
							array (
								'header-brand-bg' => '#ffffff',
								'header-brand-icon-bg' => '#2980b9',
								'header-brand-icon-color' => '#ffffff',
								'header-brand-text' => '#354052',
								'header-brand-subtitle-text' => '#354052',
								'header-brand-border' => '#e6eaee',
								'header-bg' => '#273142',
								'header-border' => '#313d4f',
								'header-text' => '#ffffff',
								'header-toolbar-text' => '#7f8fa4',
								'header-toolbar-text-hover' => '#ffffff',
								'sidebar-bg' => '#ffffff',
								'sidebar-text' => '#8c9aa9',
								'sidebar-hover-bg' => '#f5f8fa',
								'sidebar-hover-text' => '#7f8fa4',
								'sidebar-active-bg' => '#f5f8fa',
								'sidebar-active-text' => '#7f8fa4',
								'sidebar-active-hover-text' => '#728089',
								'sidebar-active-highlight' => '#2980b9',
								'sidebar-current-bg' => '#f5f8fa',
								'sidebar-current-text' => '#7f8fa4',
								'sidebar-current-highlight' => '#2980b9',
								'sidebar-current-hover-bg' => '#f5f8fa',
								'sidebar-current-hover-text' => '#7f8fa4',
								'sidebar-current-subitem-text' => '#8c9aa9',
								'sidebar-current-subitem-hover-text' => '#728089',
								'sidebar-current-subitem-current-text' => '#2980b9',
								'primary-normal' => '#2980b9',
								'primary-light' => '#4ea5d8',
								'accent' => '#e67e22',
								'button-primary-ini' => '#2980b9',
								'button-primary-end' => '#2c66a0',
								'button-primary-border' => '#2a5c8e',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '#506072',
								'button-base-end' => '#506072',
								'button-base-border' => '#506072',
								'button-base-text' => '',
								'body-bg' => '#1b2431',
								'body-text' => '#7f8fa4',
								'input-bg' => '#273142',
								'input-text' => '#ffffff',
								'input-border' => '#313d4f',
								'input-border-focus' => '#2980b9',
								'card-bg' => '#273142',
								'card-border' => '#313d4f',
								'card-border-bottom' => '#273142',
								'card-title-bg' => '#273142',
								'card-title-text' => '#ffffff',
								'dropdown-bg' => '#273142',
								'dropdown-text' => '#ffffff',
								'dropdown-border' => '#313d4f',
								'dropdown-hover-bg' => '#313d4f',
								'dropdown-hover-text' => '#2980b9',
							),
						),
						'light-alizarin' => array (
							'name' => 'light-alizarin',
							'scheme' => 
							array (
								'header-brand-bg' => '#ffffff',
								'header-brand-icon-bg' => '#e74c3c',
								'header-brand-icon-color' => '#ffffff',
								'header-brand-text' => '#354052',
								'header-brand-subtitle-text' => '#354052',
								'header-brand-border' => '#e6eaee',
								'header-bg' => '#ffffff',
								'header-border' => '#e6eaee',
								'header-text' => '#354052',
								'header-toolbar-text' => '#7f8fa4',
								'header-toolbar-text-hover' => '#afbecc',
								'sidebar-bg' => '#ffffff',
								'sidebar-text' => '#8c9aa9',
								'sidebar-hover-bg' => '#f5f8fa',
								'sidebar-hover-text' => '#7f8fa4',
								'sidebar-active-bg' => '#f5f8fa',
								'sidebar-active-text' => '#7f8fa4',
								'sidebar-active-hover-text' => '#728089',
								'sidebar-active-highlight' => '#e74c3c',
								'sidebar-current-bg' => '#f5f8fa',
								'sidebar-current-text' => '#7f8fa4',
								'sidebar-current-highlight' => '#e74c3c',
								'sidebar-current-hover-bg' => '#f5f8fa',
								'sidebar-current-hover-text' => '#7f8fa4',
								'sidebar-current-subitem-text' => '#8c9aa9',
								'sidebar-current-subitem-hover-text' => '#728089',
								'sidebar-current-subitem-current-text' => '#e74c3c',
								'primary-normal' => '#e74c3c',
								'primary-light' => '#e87568',
								'accent' => '#e67e22',
								'button-primary-ini' => '#e74c3c',
								'button-primary-end' => '#c0392b',
								'button-primary-border' => '#aa2525',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '',
								'button-base-end' => '',
								'button-base-border' => '',
								'button-base-text' => '',
								'body-bg' => '#eff3f6',
								'body-text' => '#7f8fa4',
								'input-bg' => '#ffffff',
								'input-text' => '#354052',
								'input-border' => '#dfe3e9',
								'input-border-focus' => '#e74c3c',
								'card-bg' => '#ffffff',
								'card-border' => '#e6eaee',
								'card-border-bottom' => '#e6eaee',
								'card-title-bg' => '#ffffff',
								'card-title-text' => '#354052',
								'dropdown-bg' => '#ffffff',
								'dropdown-text' => '#354052',
								'dropdown-border' => '#dfe3e9',
								'dropdown-hover-bg' => '#f2f4f7',
								'dropdown-hover-text' => '#e74c3c',
							),
						),
						'light-bubblegum' => array (
							'name' => 'light-bubblegum',
							'scheme' => 
							array (
								'header-brand-bg' => '#ffffff',
								'header-brand-icon-bg' => '#fa5c98',
								'header-brand-icon-color' => '#ffffff',
								'header-brand-text' => '#354052',
								'header-brand-subtitle-text' => '#354052',
								'header-brand-border' => '#e6eaee',
								'header-bg' => '#ffffff',
								'header-border' => '#e6eaee',
								'header-text' => '#354052',
								'header-toolbar-text' => '#7f8fa4',
								'header-toolbar-text-hover' => '#afbecc',
								'sidebar-bg' => '#ffffff',
								'sidebar-text' => '#8c9aa9',
								'sidebar-hover-bg' => '#f5f8fa',
								'sidebar-hover-text' => '#7f8fa4',
								'sidebar-active-bg' => '#f5f8fa',
								'sidebar-active-text' => '#7f8fa4',
								'sidebar-active-hover-text' => '#728089',
								'sidebar-active-highlight' => '#fa5c98',
								'sidebar-current-bg' => '#f5f8fa',
								'sidebar-current-text' => '#7f8fa4',
								'sidebar-current-highlight' => '#fa5c98',
								'sidebar-current-hover-bg' => '#f5f8fa',
								'sidebar-current-hover-text' => '#7f8fa4',
								'sidebar-current-subitem-text' => '#8c9aa9',
								'sidebar-current-subitem-hover-text' => '#728089',
								'sidebar-current-subitem-current-text' => '#fa5c98',
								'primary-normal' => '#fa5c98',
								'primary-light' => '#ff7cb8',
								'accent' => '#9b59b6',
								'button-primary-ini' => '#fa5c98',
								'button-primary-end' => '#da3c78',
								'button-primary-border' => '#ca2c68',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '',
								'button-base-end' => '',
								'button-base-border' => '',
								'button-base-text' => '',
								'body-bg' => '#eff3f6',
								'body-text' => '#7f8fa4',
								'input-bg' => '#ffffff',
								'input-text' => '#354052',
								'input-border' => '#dfe3e9',
								'input-border-focus' => '#fa5c98',
								'card-bg' => '#ffffff',
								'card-border' => '#e6eaee',
								'card-border-bottom' => '#e6eaee',
								'card-title-bg' => '#ffffff',
								'card-title-text' => '#354052',
								'dropdown-bg' => '#ffffff',
								'dropdown-text' => '#354052',
								'dropdown-border' => '#dfe3e9',
								'dropdown-hover-bg' => '#f2f4f7',
								'dropdown-hover-text' => '#fa5c98',
							),
						),
						'flat' => array (
							'name' => 'flat',
							'scheme' => 
							array (
								'header-brand-bg' => '#16a085',
								'header-brand-icon-bg' => '#16a085',
								'header-brand-icon-color' => '#ecf0f1',
								'header-brand-text' => '#ecf0f1',
								'header-brand-subtitle-text' => '#ecf0f1',
								'header-brand-border' => '#16a085',
								'header-bg' => '#1abc9c',
								'header-border' => '#1abc9c',
								'header-text' => '#ecf0f1',
								'header-toolbar-text' => '#ecf0f1',
								'header-toolbar-text-hover' => '#ffffff',
								'sidebar-bg' => '#2c3e50',
								'sidebar-text' => '#7f8c8d',
								'sidebar-hover-bg' => '#34495e',
								'sidebar-hover-text' => '#95a5a6',
								'sidebar-active-bg' => '#34495e',
								'sidebar-active-text' => '#95a5a6',
								'sidebar-active-hover-text' => '#b5c5c6',
								'sidebar-active-highlight' => '#1abc9c',
								'sidebar-current-bg' => '#34495e',
								'sidebar-current-text' => '#95a5a6',
								'sidebar-current-highlight' => '#1abc9c',
								'sidebar-current-hover-bg' => '#34495e',
								'sidebar-current-hover-text' => '#95a5a6',
								'sidebar-current-subitem-text' => '#95a5a6',
								'sidebar-current-subitem-hover-text' => '#b5c5c6',
								'sidebar-current-subitem-current-text' => '#1abc9c',
								'primary-normal' => '#1abc9c',
								'primary-light' => '#59d6b9',
								'accent' => '#2980b9',
								'button-primary-ini' => '#1abc9c',
								'button-primary-end' => '#1abc9c',
								'button-primary-border' => '#1abc9c',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '#bdc3c7',
								'button-base-end' => '#bdc3c7',
								'button-base-border' => '#bdc3c7',
								'button-base-text' => '#ffffff',
								'body-bg' => '#ecf0f1',
								'body-text' => '#34495e',
								'input-bg' => '#ffffff',
								'input-text' => '#95a5a6',
								'input-border' => '#95a5a6',
								'input-border-focus' => '#1abc9c',
								'card-bg' => '#d6dbdf',
								'card-border' => '#d6dbdf',
								'card-border-bottom' => '#d6dbdf',
								'card-title-bg' => '#d6dbdf',
								'card-title-text' => '#34495e',
								'dropdown-bg' => '#34495e',
								'dropdown-text' => '#ffffff',
								'dropdown-border' => '#293a4a',
								'dropdown-hover-bg' => '#293a4a',
								'dropdown-hover-text' => '#1abc9c',
							),
						),
						'flat-sun-flower' => array (
							'name' => 'flat-sun-flower',
							'scheme' => 
							array (
								'header-brand-bg' => '#f39c12',
								'header-brand-icon-bg' => '#f39c12',
								'header-brand-icon-color' => '#fde3a7',
								'header-brand-text' => '#fde3a7',
								'header-brand-subtitle-text' => '#fde3a7',
								'header-brand-border' => '#f39c12',
								'header-bg' => '#f5ab35',
								'header-border' => '#f5ab35',
								'header-text' => '#fde3a7',
								'header-toolbar-text' => '#fde3a7',
								'header-toolbar-text-hover' => '#ffffff',
								'sidebar-bg' => '#2c3e50',
								'sidebar-text' => '#7f8c8d',
								'sidebar-hover-bg' => '#34495e',
								'sidebar-hover-text' => '#95a5a6',
								'sidebar-active-bg' => '#34495e',
								'sidebar-active-text' => '#95a5a6',
								'sidebar-active-hover-text' => '#b5c5c6',
								'sidebar-active-highlight' => '#f39c12',
								'sidebar-current-bg' => '#34495e',
								'sidebar-current-text' => '#95a5a6',
								'sidebar-current-highlight' => '#f39c12',
								'sidebar-current-hover-bg' => '#34495e',
								'sidebar-current-hover-text' => '#95a5a6',
								'sidebar-current-subitem-text' => '#95a5a6',
								'sidebar-current-subitem-hover-text' => '#b5c5c6',
								'sidebar-current-subitem-current-text' => '#f39c12',
								'primary-normal' => '#f39c12',
								'primary-light' => '#f9b32f',
								'accent' => '#9b59b6',
								'button-primary-ini' => '#f39c12',
								'button-primary-end' => '#f39c12',
								'button-primary-border' => '#f39c12',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '#bdc3c7',
								'button-base-end' => '#bdc3c7',
								'button-base-border' => '#bdc3c7',
								'button-base-text' => '#ffffff',
								'body-bg' => '#ecf0f1',
								'body-text' => '#34495e',
								'input-bg' => '#ffffff',
								'input-text' => '#95a5a6',
								'input-border' => '#95a5a6',
								'input-border-focus' => '#f39c12',
								'card-bg' => '#d6dbdf',
								'card-border' => '#d6dbdf',
								'card-border-bottom' => '#d6dbdf',
								'card-title-bg' => '#d6dbdf',
								'card-title-text' => '#34495e',
								'dropdown-bg' => '#34495e',
								'dropdown-text' => '#ffffff',
								'dropdown-border' => '#293a4a',
								'dropdown-hover-bg' => '#293a4a',
								'dropdown-hover-text' => '#f39c12',
							),
						),
						'flat-wisteria' => array (
							'name' => 'flat-wisteria',
							'scheme' => 
							array (
								'header-brand-bg' => '#8e44ad',
								'header-brand-icon-bg' => '#8e44ad',
								'header-brand-icon-color' => '#dcc6e0',
								'header-brand-text' => '#dcc6e0',
								'header-brand-subtitle-text' => '#dcc6e0',
								'header-brand-border' => '#8e44ad',
								'header-bg' => '#9b59b6',
								'header-border' => '#9b59b6',
								'header-text' => '#dcc6e0',
								'header-toolbar-text' => '#dcc6e0',
								'header-toolbar-text-hover' => '#ffffff',
								'sidebar-bg' => '#2c3e50',
								'sidebar-text' => '#7f8c8d',
								'sidebar-hover-bg' => '#34495e',
								'sidebar-hover-text' => '#95a5a6',
								'sidebar-active-bg' => '#34495e',
								'sidebar-active-text' => '#95a5a6',
								'sidebar-active-hover-text' => '#b5c5c6',
								'sidebar-active-highlight' => '#cea0e4',
								'sidebar-current-bg' => '#34495e',
								'sidebar-current-text' => '#95a5a6',
								'sidebar-current-highlight' => '#cea0e4',
								'sidebar-current-hover-bg' => '#34495e',
								'sidebar-current-hover-text' => '#95a5a6',
								'sidebar-current-subitem-text' => '#95a5a6',
								'sidebar-current-subitem-hover-text' => '#b5c5c6',
								'sidebar-current-subitem-current-text' => '#cea0e4',
								'primary-normal' => '#8e44ad',
								'primary-light' => '#9b59b6',
								'accent' => '#f1c40f',
								'button-primary-ini' => '#8e44ad',
								'button-primary-end' => '#8e44ad',
								'button-primary-border' => '#8e44ad',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '#bdc3c7',
								'button-base-end' => '#bdc3c7',
								'button-base-border' => '#bdc3c7',
								'button-base-text' => '#ffffff',
								'body-bg' => '#ecf0f1',
								'body-text' => '#34495e',
								'input-bg' => '#ffffff',
								'input-text' => '#95a5a6',
								'input-border' => '#95a5a6',
								'input-border-focus' => '#8e44ad',
								'card-bg' => '#d6dbdf',
								'card-border' => '#d6dbdf',
								'card-border-bottom' => '#d6dbdf',
								'card-title-bg' => '#d6dbdf',
								'card-title-text' => '#34495e',
								'dropdown-bg' => '#34495e',
								'dropdown-text' => '#ffffff',
								'dropdown-border' => '#293a4a',
								'dropdown-hover-bg' => '#293a4a',
								'dropdown-hover-text' => '#cea0e4',
							),
						),
						'candy-pink' => array (
							'name' => 'candy-pink',
							'scheme' => 
							array (
								'header-brand-bg' => '#ff2b58',
								'header-brand-icon-bg' => '#64406d',
								'header-brand-icon-color' => '#cea0e4',
								'header-brand-text' => '#ffeff4',
								'header-brand-subtitle-text' => '#ffe2ee',
								'header-brand-border' => '#ff597d',
								'header-bg' => '#2b2c36',
								'header-border' => '#27272b',
								'header-text' => '#d3d8db',
								'header-toolbar-text' => '#d3d8db',
								'header-toolbar-text-hover' => '#ffffff',
								'sidebar-bg' => '#363845',
								'sidebar-text' => '#6c6d7a',
								'sidebar-hover-bg' => '#2b2c36',
								'sidebar-hover-text' => '#a4abb3',
								'sidebar-active-bg' => '#2b2c36',
								'sidebar-active-text' => '#6c6d7a',
								'sidebar-active-hover-text' => '#a4abb3',
								'sidebar-active-highlight' => '#ff2b58',
								'sidebar-current-bg' => '#2b2c36',
								'sidebar-current-text' => '#ff2b58',
								'sidebar-current-highlight' => '#ff2b58',
								'sidebar-current-hover-bg' => '#2b2c36',
								'sidebar-current-hover-text' => '#ff2b58',
								'sidebar-current-subitem-text' => '#6c6d7a',
								'sidebar-current-subitem-hover-text' => '#a4abb3',
								'sidebar-current-subitem-current-text' => '#ffeff4',
								'primary-normal' => '#ff2b58',
								'primary-light' => '#ff6889',
								'accent' => '#1abc9c',
								'button-primary-ini' => '#ff2b58',
								'button-primary-end' => '#dd2553',
								'button-primary-border' => '#d3235b',
								'button-primary-text' => '#ecf0f1',
								'button-base-ini' => '#36404a',
								'button-base-end' => '#36404a',
								'button-base-border' => '#293542',
								'button-base-text' => '#ffffff',
								'body-bg' => '#f2f4f5',
								'body-text' => '#6c6d7a',
								'input-bg' => '#ffffff',
								'input-text' => '#6c6d7a',
								'input-border' => '#dce0e3',
								'input-border-focus' => '#ff2b58',
								'card-bg' => '#ffffff',
								'card-border' => '#dce0e3',
								'card-border-bottom' => '#363845',
								'card-title-bg' => '#4e5663',
								'card-title-text' => '#f2f4f5',
								'dropdown-bg' => '#2b2c36',
								'dropdown-text' => '#f2f4f5',
								'dropdown-border' => '#363845',
								'dropdown-hover-bg' => '#363845',
								'dropdown-hover-text' => '#ff2b58',
							),
						),
						'onyx' => array(
							'name' => 'onyx',
							'scheme' => array(
								'header-brand-bg' => '#cca1c7',
								'header-brand-icon-bg' => '#fff6c6',
								'header-brand-icon-color' => '#b48eae',
								'header-brand-text' => '#fbeaff',
								'header-brand-subtitle-text' => '#fbeaff',
								'header-brand-border' => '#cca1c7',
								'header-bg' => '#ba93b8',
								'header-border' => '#ba93b8',
								'header-text' => '#f5d1f9',
								'header-toolbar-text' => '#f5d1f9',
								'header-toolbar-text-hover' => '#fcfcfc',
								'sidebar-bg' => '#3a2e3e',
								'sidebar-text' => '#b9b4bb',
								'sidebar-hover-bg' => '#342938',
								'sidebar-hover-text' => '#c0b0c1',
								'sidebar-active-bg' => '#342938',
								'sidebar-active-text' => '#9f8ba0',
								'sidebar-active-hover-text' => '#bc93bc',
								'sidebar-active-highlight' => '#b48eae',
								'sidebar-current-bg' => '#342938',
								'sidebar-current-text' => '#bc93bc',
								'sidebar-current-highlight' => '#bc93bc',
								'sidebar-current-hover-bg' => '#342938',
								'sidebar-current-hover-text' => '#ffffff',
								'sidebar-current-subitem-text' => '#9f8ba0',
								'sidebar-current-subitem-hover-text' => '#ffffff',
								'sidebar-current-subitem-current-text' => '#fde3a7',
								'primary-normal' => '#b48eae',
								'primary-light' => '#d6c1d2',
								'accent' => '#e8c547',
								'button-primary-ini' => '#cca1c7',
								'button-primary-end' => '#b48eae',
								'button-primary-border' => '#a3809c',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '',
								'button-base-end' => '',
								'button-base-border' => '',
								'button-base-text' => '',
								'body-bg' => '#f0eef0',
								'body-text' => '#444444',
								'input-bg' => '#ffffff',
								'input-text' => '#98939b',
								'input-border' => '#e2d7dd',
								'input-border-focus' => '#b48eae',
								'card-bg' => '#ffffff',
								'card-border' => '#e2d7dd',
								'card-border-bottom' => '#d1c6cb',
								'card-title-bg' => '#d6c1d2',
								'card-title-text' => '#3a2e3e',
								'dropdown-bg' => '#3a2e3e',
								'dropdown-text' => '#a0959e',
								'dropdown-border' => '#342938',
								'dropdown-hover-bg' => '#342938',
								'dropdown-hover-text' => '#b48eae',
							),
						),
						'forgedsteel' => array (
							'name' => 'forgedsteel',
							'scheme' => array(
								'header-brand-bg' => '#2b303a',
								'header-brand-icon-bg' => '#d64933',
								'header-brand-icon-color' => '#fde3a7',
								'header-brand-text' => '#b9b4bb',
								'header-brand-subtitle-text' => '#b9b4bb',
								'header-brand-border' => '#242830',
								'header-bg' => '#ffffff',
								'header-border' => '#cecece',
								'header-text' => '#d0d0d0',
								'header-toolbar-text' => '#d0d0d0',
								'header-toolbar-text-hover' => '#fda527',
								'sidebar-bg' => '#2b303a',
								'sidebar-text' => '#73767b',
								'sidebar-hover-bg' => '#242830',
								'sidebar-hover-text' => '#9b9da0',
								'sidebar-active-bg' => '#242830',
								'sidebar-active-text' => '#73767b',
								'sidebar-active-hover-text' => '#9b9da0',
								'sidebar-active-highlight' => '#d64933',
								'sidebar-current-bg' => '#d64933',
								'sidebar-current-text' => '#fde3a7',
								'sidebar-current-highlight' => '#fde3a7',
								'sidebar-current-hover-bg' => '#d64933',
								'sidebar-current-hover-text' => '#ffffff',
								'sidebar-current-subitem-text' => '#ddaf90',
								'sidebar-current-subitem-hover-text' => '#ffffff',
								'sidebar-current-subitem-current-text' => '#fde3a7',
								'primary-normal' => '#d64933',
								'primary-light' => '#d95945',
								'accent' => '#e8c547',
								'button-primary-ini' => '#d64933',
								'button-primary-end' => '#c43e2f',
								'button-primary-border' => '#b23c2a',
								'button-primary-text' => '#ffffff',
								'button-base-ini' => '',
								'button-base-end' => '',
								'button-base-border' => '',
								'button-base-text' => '',
								'body-bg' => '#d7d7d9',
								'body-text' => '#444444',
								'input-bg' => '#ffffff',
								'input-text' => '#3d3d3d',
								'input-border' => '#bdbdbf',
								'input-border-focus' => '#d64933',
								'card-bg' => '#ffffff',
								'card-border' => '#bdbdbf',
								'card-border-bottom' => '#bf462d',
								'card-title-bg' => '#d64933',
								'card-title-text' => '#fde3a7',
								'dropdown-bg' => '#2b303a',
								'dropdown-text' => '#73767b',
								'dropdown-border' => '#242830',
								'dropdown-hover-bg' => '#242830',
								'dropdown-hover-text' => '#d64933',
							),
						),
					),
					'settings'		=> array(
						'preview_template'	=> '',
						'preview_colors'	=> array(
							'header-brand-bg',
							'header-bg',
							'sidebar-bg',
							'primary-normal',
							'body-bg',
							'body-text',
						),
					),
				),
			),
		);
		return $settings;
	}
	
	public function parse_settings($settings){
		$option = function($option) use ($settings){
			$theme_prefix = $this->theme_prefix.'__';
			return $settings[$theme_prefix.$option];
		};
		
		
		// Parse Settings
		// ==========================================================================
		$_c = new Colors();
		
		
		// Theme
		$theme = $option('scheme');
		
		// General
		$color_primary_normal		= $_c->toHEX($theme['primary-normal'],true);
		$color_primary_light		= $theme['primary-light'];
		$color_accent 				= $theme['accent'];
		
		$body_background 			= $theme['body-bg'];
		$body_text 					= $theme['body-text'];
		
		// Dynamic Primary color variants
		$color_primary_dark 		= $_c->darken($color_primary_normal,5);
		$color_primary_darker 		= $_c->darken($color_primary_normal,10);
		$body_background_light		= $_c->lighten($body_background,10);
		
		// Header Navbar
		$navbar_background 						= $theme['header-bg'];
		$navbar_border 							= $theme['header-border'];
		$navbar_text 							= $theme['header-text'];
		$navbar_toolbar_text					= $theme['header-toolbar-text'];
		$navbar_toolbar_text_hover 				= $theme['header-toolbar-text-hover'];
		
		$brand_background 						= $theme['header-brand-bg'];
		$brand_text								= $theme['header-brand-text'];
		$brand_subtitle_text					= $theme['header-brand-subtitle-text'];
		$brand_border							= $theme['header-brand-border'];
		
		$brand_icon_background 					= $theme['header-brand-icon-bg'];
		$brand_icon_color 						= $theme['header-brand-icon-color'];
		
		$sidebar_background 					= $theme['sidebar-bg'];
		$sidebar_text							= $theme['sidebar-text'];
		$sidebar_hover_background 				= $theme['sidebar-hover-bg'];
		$sidebar_hover_text						= $theme['sidebar-hover-text'];
		$sidebar_active_background				= $theme['sidebar-active-bg'];
		$sidebar_active_text					= $theme['sidebar-active-text'];
		$sidebar_active_hover_text				= $theme['sidebar-active-hover-text'];
		$sidebar_active_highlight				= $theme['sidebar-active-highlight'];
		$sidebar_current_text					= $theme['sidebar-current-text'];
		$sidebar_current_background 			= $theme['sidebar-current-bg'];
		$sidebar_current_hover_text				= $theme['sidebar-current-hover-text'];
		$sidebar_current_hover_background 		= $theme['sidebar-current-hover-bg'];
		$sidebar_current_highlight				= $theme['sidebar-current-highlight'];
		$sidebar_current_subitem_text			= $theme['sidebar-current-subitem-text'];
		$sidebar_current_subitem_hover_text		= $theme['sidebar-current-subitem-hover-text'];
		$sidebar_current_subitem_current_text	= $theme['sidebar-current-subitem-current-text'];
		
		$card_border							= $theme['card-border'];
		$card_border_bottom						= $theme['card-border-bottom'];
		$card_background						= $theme['card-bg'];
		$card_title_background					= $theme['card-title-bg'];
		$card_title_text						= $theme['card-title-text'];
		$card_footer_background					= $_c->darken($card_background,2);
		$card_footer_border						= $_c->darken($card_background,5);
		
		$dropdown_border_color					= $theme['dropdown-border'];
		$dropdown_background					= $theme['dropdown-bg'];
		$dropdown_text							= $theme['dropdown-text'];
		$dropdown_hover_background				= $theme['dropdown-hover-bg'];
		$dropdown_hover_text					= $theme['dropdown-hover-text'];
		
		$input_border_color						= $theme['input-border'];
		$input_border_color_focus				= $theme['input-border-focus'];
		$input_background						= $theme['input-bg'];
		$input_text								= $theme['input-text'];
		
		// Primary Button
		$btn_primary_normal_ini					= $theme['button-primary-ini'];
		$btn_primary_normal_end					= $theme['button-primary-end'];
		$btn_primary_normal_border				= $theme['button-primary-border'];
		$btn_primary_normal_text				= $theme['button-primary-text'];
		
		$btn_primary_hover_ini					= $_c->lighten($btn_primary_normal_ini,5);
		$btn_primary_hover_end					= $_c->lighten($btn_primary_normal_end,5);
		$btn_primary_hover_border				= $_c->lighten($btn_primary_normal_border,10);
		$btn_primary_hover_text					= $btn_primary_normal_text;
		
		$btn_primary_active_ini					= $_c->darken($btn_primary_normal_ini,5);
		$btn_primary_active_end					= $_c->darken($btn_primary_normal_end,5);
		$btn_primary_active_border				= $_c->darken($btn_primary_normal_border,10);
		$btn_primary_active_text				= $btn_primary_normal_text;
		
		// Base Button
		$btn_base_normal_ini					= $theme['button-base-ini'];
		$btn_base_normal_end					= $theme['button-base-end'];
		$btn_base_normal_border					= $theme['button-base-border'];
		$btn_base_normal_text					= $theme['button-base-text'];
		
		$btn_base_hover_ini						= $_c->lighten($btn_base_normal_ini,5);
		$btn_base_hover_end						= $_c->lighten($btn_base_normal_end,5);
		$btn_base_hover_border					= $_c->lighten($btn_base_normal_border,10);
		$btn_base_hover_text					= $btn_base_normal_text;
		
		$btn_base_active_ini					= $_c->darken($btn_base_normal_ini,5);
		$btn_base_active_end					= $_c->darken($btn_base_normal_end,5);
		$btn_base_active_border					= $_c->darken($btn_base_normal_border,10);
		$btn_base_active_text					= $btn_base_normal_text;
		
		
		// Output Theme CSS Vars
		// ==========================================================================
		$output = "
		:root{
			%s_color-primary:					$color_primary_normal;
			%s_color-primary-light:				$color_primary_light;
			%s_color-primary-dark:				$color_primary_dark;
			%s_color-primary-darker:			$color_primary_darker;
			%s_color-accent:					$color_accent;
			
			%s_body-background:					$body_background;
			%s_body-text:						$body_text;
			
			%s_navbar-background: 				$navbar_background;
			%s_navbar-border-color: 			$navbar_border;
			%s_navbar-text: 					$navbar_text;
			%s_navbar-toolbar-text: 			$navbar_toolbar_text;
			%s_navbar-toolbar-text-hover:		$navbar_toolbar_text_hover;
			
			%s_brand-background:				$brand_background;
			%s_brand-border:					$brand_border;
			%s_brand-text:						$brand_text;
			%s_brand-subtitle-text:				$brand_subtitle_text;
			%s_brand-icon-background:			$brand_icon_background;
			%s_brand-icon-color:				$brand_icon_color;
			
			%s_sidebar-background:					$sidebar_background;
			%s_sidebar-text:						$sidebar_text;
			%s_sidebar-hover-background:			$sidebar_hover_background;
			%s_sidebar-hover-text:					$sidebar_hover_text;
			%s_sidebar-active-background:			$sidebar_active_background;
			%s_sidebar-active-text:					$sidebar_active_text;
			%s_sidebar-active-hover-text:			$sidebar_active_hover_text;
			%s_sidebar-active-highlight:			$sidebar_active_highlight;
			%s_sidebar-current-text:				$sidebar_current_text;
			%s_sidebar-current-background:			$sidebar_current_background;
			%s_sidebar-current-highlight:			$sidebar_current_highlight;	
			%s_sidebar-current-hover-text:			$sidebar_current_hover_text;
			%s_sidebar-current-hover-background:	$sidebar_current_hover_background;
			%s_sidebar-current-subitem-text:		$sidebar_current_subitem_text;
			%s_sidebar-current-subitem-hover-text:	$sidebar_current_subitem_hover_text;
			%s_sidebar-current-subitem-current-text:$sidebar_current_subitem_current_text;
			
			%s_card-background:						$card_background;
			%s_card-border-color:					$card_border;
			%s_card-border-bottom-color:			$card_border_bottom;
			%s_card-title-background:				$card_title_background;
			%s_card-title-text:						$card_title_text;
			%s_card-footer-background:				$card_footer_background;
			%s_card-footer-border-color:			$card_footer_border;
			
			%s_dropdown-border-color:				$dropdown_border_color;
			%s_dropdown-background:					$dropdown_background;
			%s_dropdown-text:						$dropdown_text;
			%s_dropdown-hover-background:			$dropdown_hover_background;
			%s_dropdown-hover-text:					$dropdown_hover_text;
			
			%s_input-border-color:					$input_border_color;
			%s_input-border-color-focus:			$input_border_color_focus;
			%s_input-background:					$input_background;
			%s_input-text:							$input_text;
			
			%s_button-primary-normal-ini:			$btn_primary_normal_ini;
			%s_button-primary-normal-end:			$btn_primary_normal_end;
			%s_button-primary-normal-border:		$btn_primary_normal_border;
			%s_button-primary-normal-text:			$btn_primary_normal_text;
			%s_button-primary-hover-ini:			$btn_primary_hover_ini;
			%s_button-primary-hover-end:			$btn_primary_hover_end;
			%s_button-primary-hover-border:			$btn_primary_hover_border;
			%s_button-primary-hover-text:			$btn_primary_hover_text;
			%s_button-primary-active-ini:			$btn_primary_active_ini;
			%s_button-primary-active-end:			$btn_primary_active_end;
			%s_button-primary-active-border:		$btn_primary_active_border;
			%s_button-primary-active-text:			$btn_primary_active_text;
			
			%s_button-base-normal-ini:				$btn_base_normal_ini;
			%s_button-base-normal-end:				$btn_base_normal_end;
			%s_button-base-normal-border:			$btn_base_normal_border;
			%s_button-base-normal-text:				$btn_base_normal_text;
			%s_button-base-hover-ini:				$btn_base_hover_ini;
			%s_button-base-hover-end:				$btn_base_hover_end;
			%s_button-base-hover-border:			$btn_base_hover_border;
			%s_button-base-hover-text:				$btn_base_hover_text;
			%s_button-base-active-ini:				$btn_base_active_ini;
			%s_button-base-active-end:				$btn_base_active_end;
			%s_button-base-active-border:			$btn_base_active_border;
			%s_button-base-active-text:				$btn_base_active_text;
		}
		";
		$prefix = CS_CSS_THEME_SLUG;
		$output = str_replace('%s',$prefix,$output);
		return $output;
	}
}