<?php
/**
 * The admin settings page specific functionality of the plugin.
 *
 * @link       https://themehigh.com
 * @since      1.0.0
 *
 * @package    woocommerce-email-customizer-pro
 * @subpackage woocommerce-email-customizer-pro/admin
 */
if(!defined('WPINC')){ die; }

if(!class_exists('THWEC_Admin_Settings')):

abstract class THWEC_Admin_Settings {
	protected $page_id = '';	
	public static $section_id = '';
	
	protected $tabs = '';
	protected $sections = '';
	
	public function __construct($page, $section = '') {
		$this->page_id = $page;
		if($section){
			self::$section_id = $section;
		}else{
			self::set_first_section_as_current();
		}
		// $this->tabs = array( 'general_settings' => 'General Settings','template_settings'=>'Add/Edit Templates', 'advanced_settings' => 'Advanced Settings', 'license_settings' => 'Plugin License');
		$this->tabs = array( 'general_settings' => 'General Settings','template_settings'=>'Add/Edit Templates','license_settings' => 'Plugin License');
	}
	
	public function get_tabs(){
		return $this->tabs;
	}

	public function get_current_tab(){
		return $this->page_id;
	}
	
	public function get_sections(){
		return $this->sections;
	}
	
	public function get_current_section(){
		return isset( $_GET['section'] ) ? esc_attr( $_GET['section'] ) : self::$section_id;
	}
	
	public static function set_current_section($section_id){
		if($section_id){
			self::$section_id = $section_id;
		}
	}
	
	public static function set_first_section_as_current(){
		$sections = false; //THWEC_Admin_Utils::get_sections();
		if($sections && is_array($sections)){
			$array_keys = array_keys( $sections );
			if($array_keys && is_array($array_keys) && isset($array_keys[0])){
				self::set_current_section($array_keys[0]);
			}
		}
	}

	public function render_tabs(){
		$current_tab = $this->get_current_tab();
		$tabs = $this->get_tabs();

		if(empty($tabs)){
			return;
		}
		
		echo '<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">';
		foreach( $tabs as $id => $label ){
			$active = ( $current_tab == $id ) ? 'nav-tab-active' : '';
			$label = THWEC_i18n::t($label);
			echo '<a class="nav-tab '.$active.'" href="'. $this->get_admin_url($id) .'">'.$label.'</a>';
		}
		echo '</h2>';		
	}
	
	public function render_sections() {
		$current_section = $this->get_current_section();
		$sections = $this->get_sections();

		if(empty($sections)){
			return;
		}
		
		$array_keys = array_keys( $sections );
		$section_html = '';
		
		foreach( $sections as $id => $label ){
			$label = THWEC_i18n::t($label);
			$url   = $this->get_admin_url($this->page_id, sanitize_title($id));	
			$section_html .= '<li><a href="'. $url .'" class="'.($current_section == $id ? 'current' : '').'">'.$label.'</a> '.(end($array_keys) == $id ? '' : '|').' </li>';
		}	
		
		if($section_html){
			echo '<ul class="thpladmin-sections">';
			echo $section_html;	
			echo '</ul>';
		}
	} 
	
	public function get_admin_url($tab = false, $section = false){
		$url = 'admin.php?page=th_email_customizer_pro';
		if($tab && !empty($tab)){
			$url .= '&tab='. $tab;
		}
		if($section && !empty($section)){
			$url .= '&section='. $section;
		}
		return admin_url($url);
	}
	
	/*************************************************
	******* Form field render functions - START ******
	*************************************************/
	public function render_form_field_element($field, $atts = array(), $render_cell = true){
		// var_dump($field);
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'label_cell_props' => '',
				'input_cell_props' => '',
				'label_cell_colspan' => '',
				'input_cell_colspan' => '',
			), $atts );
			$ftype     = isset($field['type']) ? $field['type'] : 'text';
			$flabel    = isset($field['label']) && !empty($field['label']) ? THWEC_i18n::t($field['label']) : '';
			$sub_label = isset($field['sub_label']) && !empty($field['sub_label']) ? THWEC_i18n::t($field['sub_label']) : '';
			$tooltip   = isset($field['hint_text']) && !empty($field['hint_text']) ? THWEC_i18n::t($field['hint_text']) : '';
			
			$field_html = '';
			// var_dump($ftype);

			if($ftype == 'text'){
				$field_html = $this->render_form_field_element_inputtext($field, $atts);
				
			}
			else if($ftype == 'hidden'){
				$field_html = $this->render_form_field_element_hidden($field,$atts);
			}else if($ftype == 'textarea'){
				$field_html = $this->render_form_field_element_textarea($field, $atts);
				   
			}else if($ftype == 'select'){
				$field_html = $this->render_form_field_element_select($field, $atts);     
				
			}else if($ftype == 'multiselect'){
				$field_html = $this->render_form_field_element_multiselect($field, $atts);     
				
			}else if($ftype == 'colorpicker'){
				$field_html = $this->render_form_field_element_colorpicker($field, $atts);              
            
			}else if($ftype == 'fourside'){
				$field_html = $this->render_form_field_element_fourside($field, $atts);              
            
			}else if($ftype == 'checkbox'){
				$field_html = $this->render_form_field_element_checkbox($field, $atts, $render_cell);   
				//$flabel 	= '&nbsp;';
			}else if($ftype == 'radio'){
				$field_html = $this->render_form_field_element_radio($field, $atts, $render_cell);
				$flabel     = '&nbsp;';
			}else if($ftype == 'number'){
				$field_html = $this->render_form_field_element_number($field, $atts);   
			}

			// var_dump($field_html);
			if($render_cell){
				$required_html = isset($field['required']) && $field['required'] ? '<abbr class="required" title="required">*</abbr>' : '';
				$label_cell_props = !empty($args['label_cell_props']) ? $args['label_cell_props'] : '';
				$input_cell_props = !empty($args['input_cell_props']) ? $args['input_cell_props'] : '';
				if($flabel){
				?>
				<td <?php echo $label_cell_props ?> >
					<?php echo $flabel; echo $required_html; 
					if($sub_label){
						?>
						<br/><span class="thpladmin-subtitle"><?php echo $sub_label; ?></span>
						<?php 
					}
					?>
				</td>
				<?php 
				}
				$this->render_form_element_tooltip($tooltip); ?>
				<td <?php echo $input_cell_props ?> ><?php echo $field_html;?></td>
				<?php
			}else{
				echo $field_html;

			}
		}
	}
	
	private function prepare_form_field_props($field, $atts = array()){
		$field_props = '';
		$args = shortcode_atts( array(
			'input_width' => '',
			'input_name_prefix' => 'i_',
			'input_name_suffix' => '',
			'input_margin' => '',
		), $atts );
		
		$ftype = isset($field['type']) ? $field['type'] : 'text';
		
		if($ftype == 'multiselect'){
			$args['input_name_suffix'] = $args['input_name_suffix'].'[]';
		}
		$fname  = $args['input_name_prefix'].$field['name'].$args['input_name_suffix'];
		$fvalue = isset($field['value']) ? $field['value'] : '';
		$input_width  = $args['input_width'] ? 'width:'.$args['input_width'].';' : '';
		$input_margin  = $args['input_margin'] ? 'margin:'.$args['input_margin'].';' : '';
		$field_props  = 'name="'. $fname .'" value="'. $fvalue .'" style="'. $input_width .'"';
		$field_props .= ( isset($field['placeholder']) && !empty($field['placeholder']) ) ? ' placeholder="'.$field['placeholder'].'"' : '';
		$field_props .= ( isset($field['onchange']) && !empty($field['onchange']) ) ? ' onchange="'.$field['onchange'].'"' : '';
		// var_dump($field_props);
		return $field_props;
	}
	
	private function render_form_field_element_inputtext($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			$fclass = isset($field['class']) ? $field['class'] : '';
			$field_html = '<input type="text" class="'.$fclass.'" '. $field_props .' />';
		}
		return $field_html;
	}
	
	private function render_form_field_element_hidden($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			$field_html = '<input type="hidden" '. $field_props .' />';
		}
		return $field_html;
	}

	private function render_form_field_element_textarea($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'rows' => '4',
				'cols' => '100',
			), $atts );
		
			$fvalue = isset($field['value']) ? $field['value'] : '';
			$field_props = $this->prepare_form_field_props($field, $atts);
			$field_html = '<textarea '. $field_props .' rows="'.$args['rows'].'" cols="'.$args['cols'].'" >'.$fvalue.'</textarea>';
		}
		return $field_html;
	}
	
	private function render_form_field_element_select($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$fvalue = isset($field['value']) ? $field['value'] : '';
			$field_props = $this->prepare_form_field_props($field, $atts);
			
			$field_html = '<select '. $field_props .' >';
			foreach($field['options'] as $value => $label){
				$selected = $value === $fvalue ? 'selected' : '';
				$field_html .= '<option value="'. trim($value) .'" '.$selected.'>'. THWEC_i18n::t($label) .'</option>';
			}
			$field_html .= '</select>';
		}
		return $field_html;
	}
	
	private function render_form_field_element_multiselect($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$field_props = $this->prepare_form_field_props($field, $atts);
			
			$field_html = '<select multiple="multiple" '. $field_props .' class="thpladmin-enhanced-multi-select" >';
			foreach($field['options'] as $value => $label){
				//$selected = $value === $fvalue ? 'selected' : '';
				$field_html .= '<option value="'. trim($value) .'" >'. THWEC_i18n::t($label) .'</option>';
			}
			$field_html .= '</select>';
		}
		return $field_html;
	}
	
	private function render_form_field_element_radio($field, $atts = array(), $render_cell = true){
		$field_html = '';
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'label_props' => '',
				'cell_props'  => 3,
				'render_input_cell' => false,
			), $atts );
		
			$fid 	= 'a_f'. $field['name'];
			$fclass = isset($field['class']) && !empty($field['class']) ? 'c_f'. $field['class'] : '';
			$flabel = isset($field['label']) && !empty($field['label']) ? THWEC_i18n::t($field['label']) : '';
			
			$field_props  = $this->prepare_form_field_props($field, $atts);
			$field_props .= $field['checked'] ? ' checked' : '';
			$field_html  = '<input type="radio" id="'. $fid .'" class="'.$fclass.'" '.$field_props .' />';
			$field_html .= '<label for="'. $fid .'" '. $args['label_props'] .' > '. $flabel .'</label>';
		}
		if(!$render_cell && $args['render_input_cell']){
			return '<td '. $args['cell_props'] .' >'. $field_html .'</td>';
		}else{
			return $field_html;
		}
		return $field_html;
	}
	
	private function render_form_field_element_checkbox($field, $atts = array(), $render_cell = true){
		$field_html = '';
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'label_props' => '',
				'cell_props'  => 3,
				'render_input_cell' => false,
			), $atts );
		
			$fid 	= 'a_f'. $field['name'];
			$fclass = isset($field['class']) && !empty($field['class']) ? 'c_f'. $field['class'] : '';
			$flabel = isset($field['label']) && !empty($field['label']) ? THWEC_i18n::t($field['label']) : '';
			
			$field_props  = $this->prepare_form_field_props($field, $atts);
			$field_props .= $field['checked'] ? ' checked' : '';
			$field_html  = '<input type="checkbox" id="'. $fid .'" class="'.$fclass.'" '.$field_props .' />';
			$field_html .= '<label for="'. $fid .'" '. $args['label_props'] .' > '. $flabel .'</label>';
		}
		if(!$render_cell && $args['render_input_cell']){
			return '<td '. $args['cell_props'] .' >'. $field_html .'</td>';
		}else{
			return $field_html;
		}
	}
	
	private function render_form_field_element_number($field, $atts = array(), $render_cell = true){
		$field_html = '';
		if($field && is_array($field)){

			$flabel = isset($field['label']) && !empty($field['label']) ? THWEC_i18n::t($field['label']) : '';
			$fmin = isset($field['min']) ? THWEC_i18n::t($field['min']) : '';			
			$fmax = isset($field['max']) && !empty($field['max']) ? THWEC_i18n::t($field['max']) : '';			
			$fstep = isset($field['step']) && !empty($field['step']) ? THWEC_i18n::t($field['step']) : '';		
			// $field_props .= 'min="'.$fmin.'" max="'.$fmax.'"';
			$field_props  = 'min="'.$fmin.'" max="'.$fmax.'" step="'.$fstep.'"';
			$field_props .= $this->prepare_form_field_props($field, $atts);
			$field_html = '<input type="number" '. $field_props .' />';
		}
		return $field_html;
	}
	

	private function render_form_field_element_colorpicker($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$atts = array('input_width' => '100px');

			$field_props = $this->prepare_form_field_props($field, $atts);
			
			$field_html  = '<span class="thpladmin-colorpickpreview '.$field['name'].'_preview" style=""></span>';
            $field_html .= '<input type="text" '. $field_props .' class="thpladmin-colorpick" size="8"/>';
		}
		return $field_html;
	}

	private function render_form_field_element_fourside($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$fclass  = isset($field['class']) ? $field['class'] : '';
			$fclass .= ' input-group';

			$atts_top = array('input_name_suffix' => '_top', 'input_margin' => '0 15px 0 0');
			$atts_right = array('input_name_suffix' => '_right', 'input_margin' => '0 15px 0 0');
			$atts_bottom = array('input_name_suffix' => '_bottom', 'input_margin' => '0 15px 0 0');
			$atts_left = array('input_name_suffix' => '_left');

			$field_props_top = $this->prepare_form_field_props($field, $atts_top);
			$field_props_right = $this->prepare_form_field_props($field, $atts_right);
			$field_props_bottom = $this->prepare_form_field_props($field, $atts_bottom);
			$field_props_left = $this->prepare_form_field_props($field, $atts_left);

			$field_html  = '<input type="text" placeholder="Top" class="'.$fclass.'" '. $field_props_top .' />';
			$field_html .= '<input type="text" placeholder="Right" class="'.$fclass.'" '. $field_props_right .' />';
			$field_html .= '<input type="text" placeholder="Bottom" class="'.$fclass.'" '. $field_props_bottom .' />';
			$field_html .= '<input type="text" placeholder="Left" class="'.$fclass.'" '. $field_props_left .' />';
		}
		return $field_html;
	}
	
	public function render_form_element_tooltip($tooltip){
        $tooltip_html = '';
        
        if($tooltip){
            $tooltip_html = '<a href="javascript:void(0)" title="'. $tooltip .'" class="thpladmin_tooltip"><span class="dashicons 
dashicons-editor-help"></span></a>';
        }
        ?>
        <td style="width: 26px; padding:0px;"><?php echo $tooltip_html; ?></td>
        <?php
    }
	
	public function render_form_fragment_h_separator($atts = array()){
		$args = shortcode_atts( array(
			'colspan' 	   => 6,
			'padding-top'  => '5px',
			'border-style' => 'dashed',
    		'border-width' => '1px',
			'border-color' => '#e6e6e6',
			'content'	   => '',
		), $atts );
		
		$style  = $args['padding-top'] ? 'padding-top:'.$args['padding-top'].';' : '';
		$style .= $args['border-style'] ? ' border-bottom:'.$args['border-width'].' '.$args['border-style'].' '.$args['border-color'].';' : '';
		$style .= 'font-weight: bold;';
		
		?>
        <tr><td colspan="<?php echo $args['colspan']; ?>" style="<?php echo $style; ?>"><?php echo $args['content']; ?></td></tr>
        <?php
	}
	
	/*private function output_h_separator($show_line = true){
		$style = $show_line ? 'margin: 5px 0; border-bottom: 1px dashed #ccc' : '';
		echo '<tr><td colspan="6" style="'.$style.'">&nbsp;</td></tr>';
	}*/
	
	public function render_field_form_fragment_h_spacing($padding = 5){
		$style = $padding ? 'padding-top:'.$padding.'px;' : '';
		?>
        <tr><td colspan="6" style="<?php echo $style ?>"></td></tr>
        <?php
	}

	public function render_form_element_empty_cell(){
        ?>
        <td width="13%">&nbsp;</td>
        <?php $this->render_form_element_tooltip(false); ?>
        <td width="34%">&nbsp;</td>
        <?php
    }
	
	public function render_form_field_blank($colspan = 3){
		?>
        <td colspan="<?php echo $colspan; ?>">&nbsp;</td>  
        <?php
	}
	
	public function render_form_section_separator($props, $atts=array()){
		?>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" style="height:10px;"></td></tr>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" class="thpladmin-form-section-title" ><?php echo $props['title']; ?></td></tr>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" style="height:0px;"></td></tr>
		<?php
	}
	/***********************************************
	******* Form field render functions - END ******
	***********************************************/
}

endif;