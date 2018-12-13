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

if(!class_exists('THWEC_Admin_Settings_Templates')):

class THWEC_Admin_Settings_Templates extends THWEC_Admin_Settings {
	protected static $_instance = null;
	//const OPTIONS_KEY_SET_TEMPLATE_SETTINGS ='thwec_set_template';

	private $cell_props_L = array();
	private $cell_props_R = array();
	private $cell_props_CB = array();
	private $cell_props_CBS = array();
	private $cell_props_CBL = array();
	private $cell_props_CP = array();
	private $cell_props_S  = array();
	private $cell_props_RB = array();
	private $section_props = array();

	private $edit_template_form_props = array();
	private $map_template_form_props = array();
	private $field_props_display = array();
	private $image_props;
	private $settings = '';
	private $default_settings = array();
	private $edit_url;
	private $template_status = array();
	private $template_list = array();

	public function __construct() {
		parent::__construct('template_settings', '');
		$this->init_constants();

		add_filter('thpladmin_load_products', array('THWEC_Admin_Utils', 'load_products'));
		add_filter('thpladmin_load_products_cat', array('THWEC_Admin_Utils', 'load_products_cat'));
	}
	
	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function init_constants(){
		$this->cell_props = array( 
			'label_cell_props' => 'width="18%"', 
			'input_width' => '350px',  
		);
		$this->cell_props_L = array( 
			'label_cell_props' => 'width="18%"', 
			'input_cell_props' => 'width="34%"', 
			'input_width' => '350px',  
		);
		
		$this->cell_props_R = array( 
			'label_cell_props' => 'width="13%"', 
			'input_cell_props' => 'width="34%"', 
			'input_width' => '250px', 
		);

		$this->cell_props_CB = array( 
			'label_cell_props' => 'width="3%"', 
			'input_cell_props' => 'width="3%"', 
		);

		$this->image_props = 'style="width:100%;height:100%;"'; 
		$this->init_field_form_props();

		$this->default_settings = array('default'=>'Default');
		$this->edit_url = $this->get_admin_url();
		$this->template_status =array(
			'0'=>'admin-new-order',
			'1'=>'admin-cancelled-order',
			'2'=>'admin-failed-order',
			'3'=>'customer-completed-order',
			'4'=>'customer-on-hold-order',
			'5'=>'customer-processing-order',
			'6'=>'customer-refunded-order',
			'7'=>'customer-invoice',
			'8'=>'customer-note',
			'9'=>'customer-reset-password',
			'10'=>'customer-new-account',
		);
	}
		
	public function init_field_form_props(){
		$this->template_list = THWEC_Utils::get_template_list();
		$template_files = array('' => 'Default Template');
		foreach($this->template_list as $key => $value){
			$display_name = isset($value['display_name']) ? $value['display_name'] : $key;
			$template_files[$key] = $display_name;
		}		

		$this->map_template_form_props = array(
			'section_map_templates' => array('title'=>'Choose Template', 'type'=>'separator', 'colspan'=>'2'),
			'admin-new-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Admin New Order Email', 'value'=>'','options'=>$template_files),
			'admin-cancelled-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Admin Cancelled Order Email', 'value'=>'','options'=>$template_files),
			'admin-failed-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Admin Failed Order Email', 'value'=>'','options'=>$template_files),
			'customer-completed-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer Completed Order', 'value'=>'','options'=>$template_files),
			'customer-on-hold-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer On Hold Order Email', 'value'=>'','options'=>$template_files),
			'customer-processing-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer Processing Order', 'value'=>'','options'=>$template_files),
			'customer-refunded-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer Refund Order', 'value'=>'','options'=>$template_files),
			'customer-invoice'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer invoice / Order details ', 'value'=>'','options'=>$template_files),
			'customer-note'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer Note', 'value'=>'','options'=>$template_files),
			'customer-reset-password'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Reset Password', 'value'=>'','options'=>$template_files),
			'customer-new-account'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'New Account', 'value'=>'','options'=>$template_files),
		);

		/*$temp = $array(
			'product_based_check'		=> array('type'=>'checkbox','name'=>'social-url-option1','class'=>'','value'=>'block','checked'=>0),
			'category_based_check'		=> array('type'=>'checkbox','name'=>'social-url-option1','class'=>'','value'=>'block','checked'=>0),
			'template-for-products'  	=> array('type'=>'text', 'name'=>'template-product-select', 'label'=>'Set this template for products'),
			'template-for-category'  	=> array('type'=>'text', 'name'=>'template-category-select', 'label'=>'Set this template for Category'),
			);*/
	}
	
	public function set_edit_form_fields(){
		$this->template_list = THWEC_Utils::get_template_list();
		$template_files_edit = array('' => 'Select Template');
		foreach($this->template_list as $key => $value){
			$display_name = isset($value['display_name']) ? $value['display_name'] : $key;
			$template_files_edit[$key] = $display_name;
		}		
		$this->edit_template_form_props = array(
			'section_edit_templates' => array('title'=>'Edit Template', 'type'=>'separator', 'colspan'=>'2'),
			'edit_template'		=> array('type'=>'select', 'name'=>'edit_template', 'label'=>'Choose Template', 'value'=>'','options'=>$template_files_edit),
			);
	}

	public function render_page(){
		$this->render_tabs();
		$this->render_sections();
		$this->render_content();
	}
	
	public function reset_to_default() {
		delete_option(THWEC_Utils::OPTION_KEY_TEMPLATE_SETTINGS);
		$files = scandir(THWEC_CUSTOM_TEMPLATE_PATH); // get all file names
		foreach($files as $file){ // iterate files
			if($file != '.' && $file != '..'){ //scandir() contains two values '.' & '..' 
				$file = THWEC_CUSTOM_TEMPLATE_PATH.$file;
				if(is_file($file)){
					unlink($file); // delete file		  	
				}
			}
		}
		return '<div class="updated"><p>'. THWEC_i18n::t('Template settings successfully reset') .'</p></div>';
	}
	
	private function save_settings(){
		$settings = $this->prepare_settings($_POST);
		$result = THWEC_Utils::save_template_settings($settings);
		return $result;
	}

	private function delete_template(){
		$template_name = $_POST['i_edit_template'];
		$file_extension = array('.thwec','.php');
		foreach ($file_extension as $key => $value) {
			$file = THWEC_CUSTOM_TEMPLATE_PATH.$template_name.$value;
			if(is_file($file)){
				unlink($file); // delete file		  	
			}
		}
		$settings = THWEC_Utils::get_template_settings();
		$templates = $settings[THWEC_Utils::SETTINGS_KEY_TEMPLATE_LIST];

		unset($templates[$template_name]);
		$settings[THWEC_Utils::SETTINGS_KEY_TEMPLATE_LIST] = $templates;
		THWEC_Utils::save_template_settings($settings);	
	}

	private function prepare_settings($posted){
		$settings = THWEC_Utils::get_template_settings();
		$template_map = $settings[THWEC_Utils::SETTINGS_KEY_TEMPLATE_MAP];

		foreach ($posted['i_template-list'] as $key => $value) {
			$template_map[$this->template_status[$key]] = $value;	
		}

		$settings[THWEC_Utils::SETTINGS_KEY_TEMPLATE_MAP] = $template_map;
		return $settings;
	}

	private function render_content(){
		if(isset($_POST['save_settings'])){
			$this->save_settings();
		}
		else if(isset($_POST['reset_settings'])){
			$this->reset_to_default();
		}
		else if(isset($_POST['delete_template'])){
			$this->delete_template();
		}

		$this->set_edit_form_fields();
		
		$template_map = THWEC_Utils::get_template_map();
		$choose_template_fields = $this->map_template_form_props;

		$this->render_edit_template_form();
		$this->render_map_template_form($choose_template_fields, 'template_map_form', '', $template_map);
    	//$this->render_template_settings_popup();
    }
	
	private function render_edit_template_form(){
		$fields = $this->edit_template_form_props;
		?>            
        <div style="padding-left: 30px;">               
		    <form name="thwec_edit_template_form" id="thwec_edit_template_form" action="" method="POST" >
                <table class="form-table thpladmin-form-table thwec-template-action-tb">
                    <tbody>
                    	<?php 
                    	$this->render_form_section_separator($fields['section_edit_templates']);
                    	$this->render_form_field_element($fields['edit_template'], $this->cell_props_L);
						?>
						<td>
							<input type="submit" name="edit_template" formaction="<?php echo $this->edit_url ?>" onclick="editTemplateChangeListner(this)" class="button-primary" value="Edit">
							<input type="submit" name="delete_template" id="delete_template" formaction="#" class="button-primary" value="Delete" >
						</td>
                    </tbody>
                </table> 
            </form>
    	</div>       
    	<?php
	}

	private function render_map_template_form($fields, $form_name, $form_action='', $settings=false){
		?>            
        <div style="padding-left: 30px;">               
		    <form name="<?php echo $form_name ?>" action="<?php echo $form_action ?>" method="POST">
                <table class="form-table thpladmin-form-table">
                    <tbody>
                    <?php 
					foreach( $fields as $name => $field ) { 
						if($field['type'] === 'separator'){
							$this->render_form_section_separator($field);
						}else {
					?>
                        <tr valign="top">
                        <?php 
							if(is_array($settings) && isset($settings[$name])){
								if($field['type'] === 'checkbox'){
									if($field['value'] === $settings[$name]){
										$field['checked'] = 1;
									}
								}else{
									$field['value'] = $settings[$name];
								}
							}
							
							if($field['type'] === 'checkbox'){
								$this->render_form_field_element($field, $this->cell_props_CB, false);
							}else if($field['type'] === 'multiselect' || $field['type'] === 'textarea'){
								$this->render_form_field_element($field, $this->cell_props);
							}else{
								$this->render_form_field_element($field, $this->cell_props);
							} 
						?>
                        </tr>
                    <?php 
						}
					} 
					?>
                    </tbody>
                </table> 
                <p class="submit">
					<input type="submit" name="save_settings" class="button-primary" value="Save changes">
                    <input type="submit" name="reset_settings" class="button" value="Reset to default" 
					onclick="return confirm('Are you sure you want to reset to default settings? all your changes will be deleted.');">
            	</p>
            </form>
    	</div>       
    	<?php
    }

	/*private function render_template_settings_popup(){
		?>
		<div id="template_dialog_form" class="new_template_dialog_form" style="display: none;">
			<form id="template_popup_form" name="template_additional_function" action="" method="post">
				<table id="thwec_template_form_general" width="100%">
					<tr>
						<?php
						$this->render_form_field_element($this->field_props['product_based_check'],$this->cell_props_CB);
						$this->render_form_field_element($this->field_props['template-for-products'],$this->cell_props_L);
						?>
					</tr>
					<tr>
						<?php
						$this->render_form_field_element($this->field_props['category_based_check']);
						$this->render_form_field_element($this->field_props['template-for-category'],$this->cell_props_L);
						?>
					</tr>
				</table>
			</form>
		</div>
		<?php 
	}*/

	/*private function render_field_form_fragment_product_list(){
		$products = apply_filters( "thpladmin_load_products", array() );
		array_unshift( $products , array( "id" => "-1", "title" => "All Products" ));
		?>
        <div id="thwec_product_select" style="display:none;">
        <select multiple="multiple" name="i_rule_operand" data-placeholder="Click to select products" class="thwec-enhanced-multi-select" style="width:200px;" value="">
			<?php 	
                foreach($products as $product){
                    echo '<option value="'. $product["id"] .'" >'. $product["title"] .'</option>';
                }
            ?>
        </select>
        </div>
        <?php
	}

	private function render_field_form_fragment_category_list(){		
		$categories = apply_filters( "thpladmin_load_products_cat", array() );
		array_unshift( $categories , array( "id" => "-1", "title" => "All Categories" ));
		?>
        <div id="thwec_product_cat_select" style="display:none;">
        <select multiple="multiple" name="i_rule_operand" data-placeholder="Click to select categories" class="thwec-enhanced-multi-select" style="width:200px;" value="">
			<?php 	
                foreach($categories as $category){
                    echo '<option value="'. $category["id"] .'" >'. $category["title"] .'</option>';
                }
            ?>
        </select>
        </div>
        <?php
	}*/

	/*private function template_section_view($fields, $form_name, $form_action='', $settings=false){
		?>
		<form action="<?php echo $this->edit_url ?>" method="POST" name="thwec_edit_template_form">
			<table id="thwec_edit_template_table" class=" thpl-admin-form-table thec-admin-form-table" width="100%" cellpadding="10px" >
				<tr>
					<th align="left">Edit Template</th>
				</tr>
				<tr>
					<?php
					$this->render_form_field_element($this->field_props['edit_template'], $this->cell_props_L);
					?>
					<td><button type="submit" class="thwec-edit-template-button">Edit</button></td>
				</tr>
				<tr>
					<input type="hidden" name="template_to_edit" value="">
				</tr>
       	 	</table>
       	</form>
       	<form name="template_map_form" id="template_map_form" action="" method="post">
       		<table id="thwec_template_select" class=" thpl-admin-form-table thec-admin-form-table" width="100%" cellpadding="10px" >
				<tr>
					<th align="left">Choose Templates</th>
				</tr>
				<tr>      
					<?php          
					$this->render_form_field_element($this->field_props['admin-new-order'], $this->cell_props_L);
					?>
					<td><button type="button" class="customize-template-button">customize</button></td>
				</tr>
				<tr>
					<?php
					$this->render_form_field_element($this->field_props['admin-cancelled-order'], $this->cell_props_L);
					?>
					<td><button type="button" class="customize-template-button">customize</button></td>
				</tr>
				<tr>
					<?php
					$this->render_form_field_element($this->field_props['admin-failed-order'], $this->cell_props_L);
					?>
					<td><button type="button" class="customize-template-button">customize</button></td>
				</tr>
				<tr>
					<?php
					$this->render_form_field_element($this->field_props['customer-completed-order'], $this->cell_props_L);
					?>
					<td><button type="button" class="customize-template-button">customize</button></td>
				</tr>
				<tr>
					<?php
					$this->render_form_field_element($this->field_props['customer-on-hold-order'], $this->cell_props_L);
					?>
					<td><button type="button" class="customize-template-button">customize</button></td>
				</tr>
				<tr>
					<?php
					$this->render_form_field_element($this->field_props['customer-processing-order'], $this->cell_props_L);
					?>
					<td><button type="button" class="customize-template-button">customize</button></td>
				</tr>
				<tr>
					<?php
					$this->render_form_field_element($this->field_props['customer-refunded-order'], $this->cell_props_L);
					?>
					<td><button type="button" class="customize-template-button">customize</button></td>
				</tr>
        	</table>
        	<p class="submit">
				<input type="submit" name="save_settings" class="button-primary" value="Save changes">
            	<input type="submit" name="reset_settings" class="button" value="Reset to default"onclick="return confirm('Are you sure you want to reset to default settings? all your changes will be deleted.');">
        	</p>
        </form>

        <?php
	}*/

	/*private function save_or_update_field($section, $action) {
		try {
			$field = THWEC_Utils_Field::prepare_field_from_posted_data($_POST, $this->field_props);
			
			if($action === 'edit'){
				$section = THWEC_Utils_Section::update_field($section, $field);
			}else{
				$section = THWEC_Utils_Section::add_field($section, $field);
			}
			
			$result1 = $this->update_section($section);
			$result2 = $this->update_options_name_title_map();
			
			if($result1 == true) {
				echo '<div class="updated"><p>'. THWEC_i18n::t('Your changes were saved.') .'</p></div>';
			}else {
				echo '<div class="error"><p>'. THWEC_i18n::t('Your changes were not saved due to an error (or you made none!).') .'</p></div>';
			}
		} catch (Exception $e) {
			echo '<div class="error"><p>'. THWEC_i18n::t('Your changes were not saved due to an error.') .'</p></div>';
		}
	}*/

	/*public function prepare_template_map($template_map=false){
		if($template_map){

		}else{
			return array(
				'admin-new-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Admin New Order Email', 'value'=>'','options'=>$template_files),
				'admin-cancelled-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Admin Cancelled Order Email', 'value'=>'','options'=>$template_files),
				'admin-failed-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Admin Failed Order Email', 'value'=>'','options'=>$template_files),
				'customer-completed-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'customer-completed-order', 'value'=>'','options'=>$template_files),
				'customer-on-hold-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer On Hold Order Email', 'value'=>'','options'=>$template_files),
				'customer-processing-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer Processing Order', 'value'=>'','options'=>$template_files),
				'customer-refunded-order'		=> array('type'=>'select', 'name'=>'template-list[]', 'label'=>'Customer Refund Order', 'value'=>'','options'=>$template_files),
				
				'product_based_check'		=> array('type'=>'checkbox','name'=>'social-url-option1','class'=>'','value'=>'block','checked'=>0),
				'category_based_check'		=> array('type'=>'checkbox','name'=>'social-url-option1','class'=>'','value'=>'block','checked'=>0),


				'edit_template_button' => array('type'=>'button','name'=>'edit_template_button','class'=>''),
				'edit_template'		=> array('type'=>'select', 'name'=>'edit-template', 'label'=>'Choose Template', 'value'=>'','options'=>$edit_files),
				'template-for-products'  	=> array('type'=>'text', 'name'=>'template-product-select', 'label'=>'Set this template for products'),
				'template-for-category'  	=> array('type'=>'text', 'name'=>'template-category-select', 'label'=>'Set this template for Category'),
			);
		}		
	}*/
}

endif;