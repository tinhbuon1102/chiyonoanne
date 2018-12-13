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

if(!class_exists('THWEC_Admin_Settings_Builder')):

class THWEC_Admin_Settings_Builder extends THWEC_Admin_Settings {
	protected static $_instance = null;
	
	private $cell_props_L = array();
	private $cell_props_R = array();
	private $cell_props_CB = array();
	private $cell_props_CBS = array();
	private $cell_props_CBL = array();
	private $cell_props_CP = array();
	private $cell_props_S  = array();
	private $cell_props_RB = array();
	private $section_props = array();
	private $field_props = array();
	private $field_props_display = array();
	
	public function __construct() {
		$this->init_constants();
	}

	public static function instance() {
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function init_constants(){
		$this->cell_props_L = array( 
			'label_cell_props' => 'style="width:13%"', 
			'input_cell_props' => 'style="width:34%"', 
			'input_width' => '200px',  
		);
		
		$this->cell_props_R = array( 
			'label_cell_props' => 'style="width:13%;"', 
			'input_cell_props' => 'style="width:34%;"', 
			'input_width' => '200px', 
		);
		
		$this->cell_props_S = array( 
			'label_cell_props' => 'style="width:20%;"', 
			'input_cell_props' => 'style="width:85%;"', 
			// 'input_width' => '90px', 
		);
		
		$this->cell_props_RB = array( 
			'label_cell_props' => 'style="width:5%;"', 
			'input_cell_props' => 'style="width:5%;"', 
			// 'input_width' => '90px', 
		);
		
		$this->cell_props_CB = array( 
			'label_props' => 'style="margin-right: 40px;"', 
		);
		$this->cell_props_CBS = array( 
			'label_props' => 'style="margin-right: 15px;"', 
		);
		$this->cell_props_CBL = array( 
			'label_props' => 'style="margin-right: 52px;"', 
		);
		
		$this->cell_props_CP = array(
			'label_cell_props' => 'style="width:13%;"', 
			'input_cell_props' => 'style="width:34%;"', 
			'input_width' => '225px',
		);
		$this->img_props_display = array(
			'image_props'	=> 'style="width:50px;height:50px;display:block;"',
		);
		$this->field_props = $this->get_field_form_props();
	}

	public function get_field_form_props(){
		$text_align = array('left' => 'Left','center' => 'Center','right' => 'Right');
		$float_align = array('left' => 'Left', 'right' => 'Right', 'none' => 'None');
		$divider_options = array('dotted' => 'Dotted','solid' => 'Line','dashed' => 'Dashed');
		$font_list = array(
			'helvetica' => 'Helvetica',
			'georgia' => 'Georgia',
			'times' => 'Times New Roman',
			'arial' => 'Arial',
			'arial-black' => 'Arial Black',
			'comic-sans'=>'Comic Sans MS',
			'impact'=>'Impact',
			'tahoma'=>'Tahoma',
			'trebuchet'=>'Trebuchet MS',
			'verdana'=>'Verdana'
		);

		// $font_list = array(
		// 	'"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif' => 'Helvetica',
		// 	'Georgia, serif' => 'Georgia',
		// 	'"Times New Roman", Times, serif' => 'Times New Roman',
		// 	'Arial, Helvetica, sans-serif' => 'Arial',
		// 	'"Arial Black", Gadget, sans-serif' => 'Arial Black',
		// 	'"Comic Sans MS", cursive, sans-serif'=>'Comic Sans MS',
		// 	'Impact, Charcoal, sans-serif'=>'Impact',
		// 	'Tahoma, Geneva, sans-serif'=>'Tahoma',
		// 	'"Trebuchet MS", Helvetica, sans-serif'=>'Trebuchet MS',
		// 	'Verdana, Geneva, sans-serif'=>'Verdana'
		// );

		$border_style = array(
			'solid'=>'solid', 
			'dotted'=>'dotted', 
			'dashed'=>'dashed', 
			'none'=>'none',
			// 'hidden'=>'hidden',
			'initial'=>'initial', 
			'inherit'=>'inherit',
		);

		$bg_repeat = array(
			'repeat'=>'repeat', 
			'repeat-x'=>'repeat-x',
			'repeat-y'=>'repeat-y',
			'no-repeat'=>'no-repeat',
			'space'=>'space', 
			'round'=>'round', 
			'initial'=>'initial', 
			'inherit'=>'inherit',
		);
		
		return array(
			'width' => array('type'=>'text', 'name'=>'width', 'label'=>'Width', 'value'=>''),
			'height' => array('type'=>'text', 'name'=>'height', 'label'=>'Height', 'value'=>''),
			'padding' => array('type'=>'fourside', 'name'=>'padding', 'label'=>'Padding', 'value'=>''),
			'margin' => array('type'=>'fourside', 'name'=>'margin', 'label'=>'Margin', 'value'=>''),
			
			'img_width' => array('type'=>'text', 'name'=>'img_width', 'label'=>'Image Width', 'value'=>''),
			'img_height' => array('type'=>'text', 'name'=>'img_height', 'label'=>'Image Height', 'value'=>''),
			'img_padding' => array('type'=>'fourside', 'name'=>'img_padding', 'label'=>'Image Padding', 'value'=>''),
			'img_margin' => array('type'=>'fourside', 'name'=>'img_margin', 'label'=>'Image Margin', 'value'=>''),

			'content_width' => array('type'=>'text', 'name'=>'content_width', 'label'=>'Width', 'value'=>''),
			'content_height' => array('type'=>'text', 'name'=>'content_height', 'label'=>'Height', 'value'=>''),
			'content_padding' => array('type'=>'fourside', 'name'=>'content_padding', 'label'=>'Content Padding', 'value'=>''),
			'content_margin' => array('type'=>'fourside', 'name'=>'content_margin', 'label'=>'Content Margin', 'value'=>''),
			

			'img_border_width' => array('type'=>'fourside', 'name'=>'img_border_width', 'label'=>'Border Width', 'value'=>''),
			'img_border_style' => array('type'=>'select', 'name'=>'img_border_style', 'label'=>'Border Style', 'options'=>$border_style),
			'img_border_color' => array('type'=>'colorpicker', 'name'=>'img_border_color', 'label'=>'Border Color', 'value'=>''),
			'img_border_radius' => array('type'=>'text', 'name'=>'img_border_radius', 'label'=>'Border Radius', 'value'=>''),

			'border_width' => array('type'=>'fourside', 'name'=>'border_width', 'label'=>'Border Width', 'value'=>''),
			'border_style' => array('type'=>'select', 'name'=>'border_style', 'label'=>'Border Style', 'options'=>$border_style),
			'border_color' => array('type'=>'colorpicker', 'name'=>'border_color', 'label'=>'Border Color', 'value'=>''),
			'border_radius' => array('type'=>'text', 'name'=>'border_radius', 'label'=>'Border Radius', 'value'=>''),

			'content_border_width' => array('type'=>'fourside', 'name'=>'content_border_width', 'label'=>'Border Width', 'value'=>''),
			'content_border_style' => array('type'=>'select', 'name'=>'content_border_style', 'label'=>'Border Style', 'options'=>$border_style),
			'content_border_color' => array('type'=>'colorpicker', 'name'=>'content_border_color', 'label'=>'Border Color', 'value'=>''),
			'content_border_radius' => array('type'=>'text', 'name'=>'content_border_radius', 'label'=>'Border Radius', 'value'=>''),

			'divider_width' => array('type'=>'text', 'name'=>'divider_width', 'label'=>'Divider Height', 'value'=>''),
			'divider_color' => array('type'=>'colorpicker', 'name'=>'divider_color', 'label'=>'Divider Color', 'value'=>''),
			'divider_style' => array('type'=>'select', 'name'=>'divider_style', 'label'=>'Divider Style', 'options'=>$border_style),

			'img_bg_color' => array('type'=>'colorpicker', 'name'=>'img_bg_color', 'label'=>'BG Color', 'value'=>''),
			'content_bg_color' => array('type'=>'colorpicker', 'name'=>'content_bg_color', 'label'=>'BG Color', 'value'=>''),
			'bg_color' => array('type'=>'colorpicker', 'name'=>'bg_color', 'label'=>'BG Color', 'placeholder'=>'Color', 'value'=>''),
			'bg_image' => array('type'=>'text', 'name'=>'bg_image', 'label'=>'BG Image', 'value'=>''),
			'bg_position' => array('type'=>'text', 'name'=>'bg_position', 'label'=>'BG Position', 'placeholder'=>'Position', 'value'=>'','hint_text'=>'left top | x% y% | xpos ypos etc.'),
			'bg_size' => array('type'=>'text', 'name'=>'bg_size', 'label'=>'BG Size', 'placeholder'=>'Size', 'value'=>''),
			'bg_repeat' => array('type'=>'select', 'name'=>'bg_repeat', 'label'=>'BG Repeat', 'placeholder'=>'Repeat', 'options'=>$bg_repeat,'hint_text'=>'image should be repeated or not'),

			'url' => array('type'=>'text', 'name'=>'url', 'label'=>'URL', 'value'=>''),
			'title' => array('type'=>'text', 'name'=>'title', 'label'=>'Title', 'value'=>''),
			'content' => array('type'=>'text', 'name'=>'content', 'label'=>'Content', 'value'=>''),
			'textarea_content' => array('type'=>'textarea', 'name'=>'textarea_content', 'label'=>'Content', 'value'=>''),
			'color' => array('type'=>'colorpicker', 'name'=>'color', 'label'=>'Text Color', 'value'=>'', 'placeholder'=>'Color',),
			'font_size' => array('type'=>'text', 'name'=>'font_size', 'label'=>'Font Size', 'value'=>'', 'placeholder'=>'Size',),
			'font_weight' => array('type'=>'text', 'name'=>'font_weight', 'label'=>'Font Weight', 'value'=>''),
			'font_family' => array('type'=>'select', 'name'=>'font_family', 'label'=>'Font Family', 'options'=>$font_list),
			'line_height' => array('type'=>'text', 'name'=>'line_height', 'label'=>'Line Height', 'value'=>'','placeholder'=>'Line height'),
			'details_color' => array('type'=>'colorpicker', 'name'=>'details_color', 'label'=>'Color', 'value'=>''),
			'details_font_size' => array('type'=>'text', 'name'=>'details_font_size', 'label'=>'Font Size', 'value'=>''),
			'details_font_weight' => array('type'=>'text', 'name'=>'details_font_weight', 'label'=>'Font Weight', 'value'=>''),
			'details_line_height' => array('type'=>'text', 'name'=>'details_line_height', 'label'=>'Line Height', 'value'=>''),
			'details_font_family' => array('type'=>'select', 'name'=>'details_font_family', 'label'=>'Font Family', 'options'=>$font_list),


			'align' => array('type'=>'select', 'name'=>'align', 'label'=>'Alignment', 'options'=>$float_align),
			'text_align' => array('type'=>'select', 'name'=>'text_align', 'label'=>'Alignment', 'options'=>$text_align),
			'details_text_align' => array('type'=>'select', 'name'=>'details_text_align', 'label'=>'Align', 'options'=>$text_align),
			'cell_spacing' => array('type'=>'text', 'name'=>'cellspacing', 'label'=>'Column Spacing'),
			
			'url1'	=> array('type'=>'text', 'name'=>'url1', 'label'=>'Facebook', 'value'=>''),
			'url2'	=> array('type'=>'text', 'name'=>'url2', 'label'=>'Gmail', 'value'=>''),
			'url3'	=> array('type'=>'text', 'name'=>'url3', 'label'=>'Twitter ', 'value'=>''),
			'url4'	=> array('type'=>'text', 'name'=>'url4', 'label'=>'Youtube', 'value'=>''),
			'url5'	=> array('type'=>'text', 'name'=>'url5', 'label'=>'Linkedin', 'value'=>''),
			'url6'	=> array('type'=>'text', 'name'=>'url6', 'label'=>'Pinterest', 'value'=>''),
			'url7'	=> array('type'=>'text', 'name'=>'url7', 'label'=>'Instagram', 'value'=>''),
			'checkbox_option_image'		=> array('type'=>'checkbox','name'=>'checkbox_option_image','label'=>'Product Image','checked'=>0),
			// 'checkbox-option-sku'	=> array('type'=>'checkbox','name'=>'checkbox_option_sku','checked'=>0),





			'textareacontent' => array('type'=>'textarea', 'name'=>'textareacontent', 'label'=>'Content', 'value'=>''),

			// 'block-text1'			=> array('type'=>'text', 'name'=>'block_text1', 'label'=>'Text', 'value'=>'','required'=>0),
			// 'block-text1-color'		=> array('type'=>'colorpicker', 'name'=>'block_text1_color', 'label'=>'Color', 'value'=>'','required'=>0),
			// 'block-text1-size'		=> array('type'=>'text', 'name'=>'block_text1_size', 'label'=>'Size', 'value'=>'','required'=>0),
			// 'block-text1-align'		=> array('type'=>'select', 'name'=>'block_text1_align', 'label'=>'Alignment', 'value'=>'','options'=>$text_align),


			// 'block-text2'			=> array('type'=>'text', 'name'=>'block_text2', 'label'=>'Text', 'value'=>'','required'=>0),
			// 'block-text2-color'		=> array('type'=>'colorpicker', 'name'=>'block_text2_color', 'label'=>'Color', 'value'=>'','required'=>0),
			// 'block-text2-size'		=> array('type'=>'text', 'name'=>'block_text2_size', 'label'=>'Size', 'value'=>'','required'=>0),
			// 'block-text2-align'		=> array('type'=>'select', 'name'=>'block_text2_align', 'label'=>'Alignment', 'value'=>'','options'=>$text_align),
			
			// 'block-text3'			=> array('type'=>'text', 'name'=>'block_text3', 'label'=>'Text', 'value'=>'','required'=>0),
			// 'block-text3-color'		=> array('type'=>'colorpicker', 'name'=>'block_text3_color', 'label'=>'Color', 'value'=>'','required'=>0),
			// 'block-text3-size'		=> array('type'=>'text', 'name'=>'block_text3_size', 'label'=>'Size', 'value'=>'','required'=>0),
			// 'block-text3-align'		=> array('type'=>'select', 'name'=>'block_text3_align', 'label'=>'Alignment', 'value'=>'','options'=>$text_align),

			// 'block-text4'			=> array('type'=>'text', 'name'=>'block_text4', 'label'=>'Text', 'value'=>'','required'=>0),
			// 'block-text4-color'		=> array('type'=>'colorpicker', 'name'=>'block_text4_color', 'label'=>'Color', 'value'=>''),
			// 'block-text4-size'		=> array('type'=>'text', 'name'=>'block_text4_size', 'label'=>'Size', 'value'=>''),

			
			// 'block-text5'			=> array('type'=>'text', 'name'=>'block_text5', 'label'=>'Text ', 'value'=>''),
			// 'block-text6'			=> array('type'=>'text', 'name'=>'block_text6', 'label'=>'Text', 'value'=>''),
			// 'block-text7'			=> array('type'=>'text', 'name'=>'block_text7', 'label'=>'Text', 'value'=>''),
			
			// 'block-textarea'			=> array('type'=>'textarea', 'name'=>'block_textarea', 'label'=>'Text', 'value'=>''),
		
			// 'block-image-width'			=> array('type'=>'text', 'name'=>'block_image_width', 'label'=>'Width', 'value'=>''),
			// 'block-content-width'		=> array('type'=>'text', 'name'=>'block_content1_width', 'label'=>'Width', 'value'=>''),
			// 'block-content-bg-color'	=> array('type'=>'colorpicker', 'name'=>'block_content1_bg_color', 'label'=>'Background Color', 'value'=>''),
			// 'block-content-border-color'	=> array('type'=>'colorpicker', 'name'=>'block_content1_border_color', 'label'=>'Border Color', 'value'=>''),
			// 'block-content-border-radius'	=> array('type'=>'text', 'name'=>'block_content1_border_radius', 'label'=>'Border Radius', 'value'=>''),
			// 'block-content-border-width'	=> array('type'=>'text', 'name'=>'block_content1_border_width', 'label'=>'Border Size', 'value'=>''),
			// 'block-content2-border-width'	=> array('type'=>'text', 'name'=>'block_content2_border_width', 'label'=>'Border Size', 'value'=>''),
			
			// 'block-content2-border-radius'			=> array('type'=>'text', 'name'=>'block_content2_border_radius', 'label'=>'Border radius', 'value'=>'','required'=>0),
			// 'block-content2-border-color'			=> array('type'=>'colorpicker', 'name'=>'block_content2_border_color', 'label'=>'Border Color', 'value'=>'','required'=>0),
			// 'block-content2-bg-color'			=> array('type'=>'colorpicker', 'name'=>'block_content2_bg_color', 'label'=>'Background Color', 'value'=>'','required'=>0),
			// 'block-content2-width'			=> array('type'=>'text', 'name'=>'block_content2_width', 'label'=>'Width', 'value'=>''),
			// 'block-border-top-width'		=> array('type'=>'text', 'name'=>'block_border_top_width', 'label'=>'height', 'value'=>''),
			// 'social-icon-option1'		=> array('type'=>'checkbox','name'=>'social_icon_option1','checked'=>1),
			// 'social-icon-option2'		=> array('type'=>'checkbox','name'=>'social_icon_option2','checked'=>1),
			// 'social-icon-option3'		=> array('type'=>'checkbox','name'=>'social_icon_option3','checked'=>1),
			// 'social-icon-option4'		=> array('type'=>'checkbox','name'=>'social_icon_option4','checked'=>1),
			// 'social-icon-option5'		=> array('type'=>'checkbox','name'=>'social_icon_option5','checked'=>1),
			// 'social-icon-option6'		=> array('type'=>'checkbox','name'=>'social_icon_option6','checked'=>1),
			// 'social-icon-option7'		=> array('type'=>'checkbox','name'=>'social_icon_option7','checked'=>1),
			// 'block-text-url'			=> array('type'=>'text', 'name'=>'block_text_url', 'label'=>'Url', 'value'=>''),
			// 'block-text-url-color'		=> array('type'=>'colorpicker', 'name'=>'block_text_ur_color', 'label'=>'Link Color', 'value'=>''),
			
			// 'block-text-line-height'	=> array('type'=>'text','name'=>'block_text_line_height','label'=>'Line Height','value'=>''),
			// 'block-base-color'		=> array('type'=>'colorpicker','name'=>'block_base_color','label'=>'Base color','value'=>''),
			// 'block-float-align'			=> array('type'=>'select', 'name'=>'block_float_align', 'label'=>'Alignment', 'value'=>'','options'=>$float_align),
			// 'block-divider-options'	=> array('type'=>'select','name'=>'block_divider_options','label'=>'Type','options'=>$divider_options),
			// 'block-link-title'			=> array('type'=>'text', 'name'=>'block_link_title', 'label'=>'Link Title ', 'value'=>''),
			// 'row-bg-img-option'		=> array('type'=>'checkbox','name'=>'row_bg_img_option','checked'=>0),
			// 'row-bg-color-option'		=> array('type'=>'checkbox','name'=>'row_bg_color_option','checked'=>0),
			
			// 'block-bg-image'	=> array('type'=>'text','name'=>'block_bg_image','label'=>'Background Image','value'=>''),
			// 'block-bg-image-size'	=> array('type'=>'text','name'=>'block_bg_image_size','label'=>'Image size','value'=>''),
			// 'block-bg-image-repeat'	=> array('type'=>'text','name'=>'block_bg_image_repeat','label'=>'Image repeat','value'=>'no-repeat'),

		);
	}

	public function render_template_builder($posted){
		?>
		<div class="thwec-tbuilder-messages">
			<p></p>
		</div>
		<?php 
		$template_content = $this->get_template_file_data($posted);
		if($template_content){
			echo $template_content;
		}else{
			?>
			<table class="thwec-tbuilder-wrapper">
				<tr>
					<td class="thwec-tbuilder-elm-wrapper">
						<?php $this->render_template_builder_elements_panel(); ?>
					</td>
					<td class="thwec-tbuilder-editor-wrapper">
						<?php $this->render_template_builder_editor_panel(); ?>
					</td>
				</tr>	
			</table>
		<?php 
		}
	    $this->render_template_element_pp();
		$this->render_template_elements();
        // $this->render_template_preview();
	}
		
	private function render_template_builder_elements_panel(){
		?>
		<table class="thwec-tbuilder-elm-grid">
			<tbody>
				<!-- Layouts  -->
				<tr>
					<td class="grid-title" colspan="4">Builder Elements</td>
				</tr>
				<tr>
					<td class="grid-elms">
						<p class="new-template-notice">Click on <strong>Add Row</strong> button to start building your email template.</p>
						<ul class="tracking-list">
							<?php //$this->test_tracking_elements(); ?>
						</ul>
					</td>
				</tr>	
				<tr>
					<td class="grid-actions">
						<button type="button" onclick="thwecTActionAddRow(this)" class="thwec-panel-add-btn">Add Row</button>						
					</td>
				</tr>												
			</tbody>
		</table>
		<?php
	}


	private function render_template_builder_editor_panel() {
		// $template_name = is_array($posted) && isset($posted['template_to_edit']) ? $posted['template_to_edit'] : '';

		$this->render_template_builder_css_section('thwec_template_css');
		?>
		<form name="thwec_tbuilder_form" method ="post" action="">
		<table class="thwec-tbuilder-editor-grid">
			<tr class="thwec-tbuilder-header">
				<td>
					<input type="text" name="template_name" id="template_save_name" placeholder="Enter Template Name" value="">
				</td>
				<td class="actions">
					<button type="button" name="create_new_template" value="Save" onclick="thwecNewTemplate(this)">New Template</button>
					<button type="button" name="save_template" value="Save" onclick="thwecSaveTemplate(this)">Save</button>
					<button type="button" class="icon-fun1 icon-fun2" onclick="thwecPreviewTemplate(this)">Preview</button>
					<button type="button" onclick="thwecClearTemplateBuilder(this)">Clear</button>
				</td>
			</tr>
			<tr>
				<td class="thwec-tbuilder-editor" colspan="2">
					<div class="container-icon-panel"  onclick="thwecBuilderBlockEdit(this, 'temp_builder', 'temp_builder')"><span class="dashicons dashicons-edit"></span></div>
					<div id="template_drag_and_drop" class="thwec-dropable-wrapper">
						<div id="tb_temp_builder" class="thwec-dropable sortable main-builder" data-global-id="1000" data-track-save="1000" data-css-change="true">	
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="thwec-tbuilder-footer" colspan="2">
					<button type="button" name="save_template" value="Save" onclick="thwecSaveTemplate(this)">Save</button>
					<button type="button" onclick="thwecPreviewTemplate(this)">Preview</button>
					<button type="button" onclick="thwecClearTemplateBuilder(this)">Clear</button>
				</td>
			</tr>
		</table>

		<div id="thwec_tbuilder_editor_preview" class="thwec-tbuilder-editor-preview" style="display: none;"></div>
		</form>
		<?php
	}


	private function render_template_builder_css_section($wrapper_id) {
		// $template_ecss = $this->get_template_file_data($posted,'.ecss');
		// $template_pcss = $this->get_template_file_data($posted,'.pcss');
		?>

		<style id="<?php echo $wrapper_id; ?>" type="text/css">
			.main-builder{
				max-width:600px;
				width:600px;
				margin: auto; 
				background-color:#f6f7fa; 
				border:1px solid #f6f7fa; 
				border-radius: 2px;
				box-sizing: border-box;
			}
			.thwec_wrapper{
				background-color: #f7f7f7;
				margin: 0; 
				/*padding: 70px 0 70px 0;*/
				width: 100%;
			}
			.thwec_wrapper table td,.thwec_wrapper table th{
				padding: 0px;
			}
			
			.thwec_wrapper table td td {
				padding: 0px;
			}
			.thwec-block table{
				width: 100%;
				border-collapse: collapse;
			}
			.thwec-row{
				border-spacing: 0px;
			}

			.thwec-row,
			.thwec-block{
				width:100%;
				table-layout: fixed;
			}
			.thwec-block td{
				padding: 0;
			}
			.thwec-layout-block{
				overflow: hidden;
			}
			.thwec-row td{
				vertical-align: top;
				box-sizing: border-box;
			}
			.thwec-block-one-column,
			.thwec-block-two-column,
			.thwec-block-three-column,
			.thwec-block-four-column,
			.thwec-block-large-left-column,
			.thwec-block-large-right-column,
			.thwec-block-gallery-column{
				max-width: 100%;
				margin: 0 auto;
				border:0px solid transparent;
			}
			.thwec-row .thwec-columns{
				border: 1px dotted #dddddd;
				word-break: break-word;
			}
			.thwec-block-one-column td{
				width: 100%;				
			}
			.thwec-block-two-column td{
				width: 50%;				
			}
			.thwec-block-three-column td{
				width: 33%;				
			}
			.thwec-block-four-column td{
				width: 25%;				
			}
			.thwec-block-left-large-column td:nth-child(1){
				width: 70%;				
			}
			.thwec-block-left-large-column td:nth-child(2){
				width: 30%;				
			}
			.thwec-block-right-large-column td:nth-child(1){
				width: 30%;				
			}
			.thwec-block-right-large-column td:nth-child(2){
				width: 70%;				
			}
			.thwec-block-gallery-column{
				border-spacing: 10px;
				border-collapse: initial;
			}
			.thwec-block-gallery-column td{
				width: 30%;
			}
			.thwec-block-header{
				background-color:#0099ff;
				overflow: hidden;
				text-align: center;
				box-sizing: border-box;
				position: relative;
				width:100%;
				margin:0 auto;
				max-width: 100%;
			}
			.thwec-block-header .header-logo-ph{
				 width:180px;
				 margin:0 auto;
				 border:1px solid transparent;
				 display: none;
				 padding: 15px 0px 15px 0px;
			}
			.thwec-block-header .header-logo-ph img{
				width:100%;
				height:100%;
				display: block;
			}
			.thwec-block-header .header-text h1{
				margin:0 auto;
				width: 100%;
				max-width: 100%;
				color:#ffffff;
				font-size:40px;
				font-weight:300;
				line-height:150%;
				text-align:center;
			    font-family: halant;
			    border:1px solid transparent;
			    box-sizing: border-box;	
			    padding: 15px 0px 15px 0px;	
			}

			.thwec-block-header .header-text h3{
				padding:0px;
				margin:0;
				color:#ffffff;
				font-size:22px;
				font-weight:300;
				text-align:center;
			    font-family: times;
			    line-height:150%;		
			}

			.thwec-block-header .header-text p{
				padding:5px 0px;
				margin:0;
				color:#ffffff;
				text-align:justify;
			    font-family: times;
			    line-height: 16pt;
			    font-size: 16px;		
			}
			.thwec-block-footer{
				border-spacing: 0px;
			}

			.thwec-block-footer .footer-padding{
				padding:10px 10px;
				height:92px;
				border:0;
				color:#636363;
				font-family:Arial;
				font-size:12px;
				line-height:125%;
				text-align:center;
				/*background-color: #322e2e;*/
			}

			.thwec-block-footer .footer-padding >  * {
				width: 90%;
    			text-align: justify;
    			color: #636363;
				font-size: inherit;
				text-align:inherit;
				color: inherit;
			}

			.thwec-block-footer .footer-padding a{
				color: inherit;
			}


			hr.thwec-block-divider{
				border:none;
				border-top: 2px solid transparent;
				border-color: gray;
				width:70%;
				/*border-bottom:none !important;*/
				margin: 40px auto;
				height: 2px;
			}

			.thwec-block-text{
				width: 100%;
				color:#636363;
				font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
				font-size:14px;
				line-height:17pt;
				text-align:center;
				margin: 0 auto;
				padding: 12px 10px;
				box-sizing: border-box;
			}

			.thwec-block-text *{
				color:#636363;
				font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
				font-size:14px;
				line-height:17pt;
				text-align:center;
				padding: 3px 3px;
			}

			.thwec-block-text p{
				margin: 0;
			}
	
			.thwec-block-image{
				margin:12px auto;
				width: 272px;
				height: 164px;
				max-width: 100%;
				box-sizing: border-box;
			}
			.thwec-block-image img {
				width:100%;
				height:100%;
				display:block;
			}

			.thwec-block-shipping .shipping-padding{
				height: 170px;
			}
			.thwec-block-billing,
			.thwec-block-shipping,
			.thwec-block-customer{
				margin: 0 auto;
			}

			.thwec-block-billing .billing-padding{
				height: 208px;
			}
			.thwec-block-customer .customer-padding{
				height: 113px;
			}

			.thwec-block-customer .thwec-customer-header,
			.thwec-block-billing .thwec-billing-header,
			.thwec-block-shipping .thwec-shipping-header {
				color:#0099ff;
				display:block;
				font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
				font-size:18px;
				font-weight:bold;
				line-height:170%;
				text-align:center;
				margin: 0px;
			}

			.thwec-block-customer .thwec-customer-body,
			.thwec-block-billing .thwec-billing-body,
			.thwec-block-shipping .thwec-shipping-body {
				text-align:center;
				line-height:150%;
				border:0px !important;
				font-family: 'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;
				font-size: 13px;
				padding: 0px 0px 0px 0px;
				
			}

			.thwec-block-social{
				text-align: center;
				width:100%;
				margin: 0;
				margin: 12px auto;
				box-sizing: border-box;
			}

			.thwec-block-social .thwec-social-icon{
			    width: 40px;
    			height: 40px;
    			margin: 0px;
    			display: inline-block;
    			padding: 0px 3px 0px 3px;
    			text-decoration:none;
				box-shadow:none;
			}
	
			.thwec-block-social .thwec-social-icon img {
				width: 100%;
				height: 100%;
				display:block;
			}

			a.thwec-button-link{
				/*-webkit-appearance: button;*/
    			/*-moz-appearance: button;*/
    			/*appearance: button;*/
    			display: block;
    			margin: 15px auto;
    			width: 50%;
    			border: 1px solid royalblue;
				padding: 10px 0px;
				width: 80px;
				max-width: 100%;
				background-color: royalblue;
				border-radius: 2px;
				color: #fff;
				line-height: 150%;
				text-align: center;
				font-size: 13px;
				text-decoration: none;
				box-shadow:none;
    			box-sizing: border-box;
    			font-family: 'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;
			}


			.thwec-block-gif{
				/*text-align:center;*/
				width: 272px;
				height: 164px;
				padding: 12px 12px;
				margin:0 auto;
				/*border:1px solid transparent;*/
			}
			.thwec-block-gif img {
				width:100%;
				height:100%;
				display:block;
			}

		/*	.thwec-block-menu ul {
				list-style-type: none;
				margin: 0;
				padding: 0;
				overflow: hidden;
				background-color: #333;
				border:1px solid transparent;
			}
			.thwec-block-menu ul li {
				float: left;
				margin-bottom: 0px;
			}
			.thwec-block-menu ul li a{
				display: inline-block;
				color:white;
				text-align: center;
				padding: 14px 16px;
				text-decoration: none;
				line-height:10px;
			}*/
			.thwec-block-order{
				background-color: white;
				margin: 0 auto;
			}
			.thwec-block-order td{
				word-break: unset;
			}
			.thwec-block-order .order-padding {
				padding:20px 48px;
			}
			.thwec-block-order .thwec-order-heading {
				font-size:18px;
				text-align:left;
				line-height:130%;
				color: #4286f4;
				font-family: 'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;
			}

			.thwec-block-order .thwec-order-table tr{
				/*border-bottom: 1px solid #F5F5F5;*/
			}

			.thwec-block-order .thwec-order-table {
				table-layout: fixed;
				background-color: #ffffff;
				margin:auto;
				width:100%;
   	 			font-family: "Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;
    			color: #636363;
    			border: 1px solid #e5e5e5;
				border-collapse:collapse;
			}
			.thwec-block-order .thwec-td {
				color: #636363;
				border: 1px solid #e5e5e5;
				padding:12px;
				text-align: left;
				font-size: 14px;
    			line-height: 150%;
			}
			.thwec-block-order .thwec-order-item-img{
				margin-bottom: 5px;
				display: none;
			}
			.thwec-block-order .thwec-order-item-img img{
				width: 32px;
				height: 32px;
				display: inline;
				height: auto;
				outline: none;
				line-height: 100%;
				vertical-align: middle;
    			margin-right: 10px;
    			text-decoration: none;
    			text-transform: capitalize;
			}

			.thwec-block-gap{
				height:48px;
				margin: 0;
			}

		</style>
		<style id="<?php echo $wrapper_id; ?>_override" type="text/css"></style>
		<style id="<?php echo $wrapper_id; ?>_preview_override" type="text/css"></style>
		<?php
	}

	private function get_template_file_data($posted){
		$data = false;

		if(is_array($posted) && isset($posted['i_edit_template'])){
			$file_name = $posted['i_edit_template'];
			THWEC_Utils_Core::write_log($file_name);
			$path = THWEC_CUSTOM_TEMPLATE_PATH.$file_name.'.thwec';
			if(file_exists($path)){
				 $data = file_get_contents($path);
			}	
		}
		return $data;
	}

	// private function render_template_preview(){
	// 	<div id="template_preview" style="display: none;margin:auto; padding:0px;margin:0px;"></div>
	// 	<div id="test_123" style="border:1px solid gray;margin:auto; padding:0px;margin:0px;">
	// 		<div class="tester"><p>Hello World.</p></div>
	// 	</div>
	// 	<?php
	// }	

	private function render_padding_options($input_name){
		 echo '<td>Padding Options</td><td><span style="display:inline-block;">
    		<input type="text" class="block-padding" name="i_'.$input_name.'_padding_top">
    		<span style="text-align:center; display:block;">Top</span>
    	</span>
    	<span style="display:inline-block;">
    		<input type="text" class="block-padding" name="i_'.$input_name.'_padding_right">
    		<span style="text-align:center; display:block;">Right</span>
    	</span>
    	<span style="display:inline-block;">
    		<input type="text" class="block-padding" name="i_'.$input_name.'_padding_bottom">
    		<span style="text-align:center; display:block;">Bottom</span>
    	</span>
    	<span style="display:inline-block;">
    		<input type="text" class="block-padding" name="i_'.$input_name.'_padding_left">
    		<span style="text-align:center; display:block;">Left</span>
    	</span></td>';
	}

	private function render_template_element_pp(){
		?>
		<div id="thwec_builder_block_pp" class="thwec-tbuilder-elm-pp" style="display: none;">
			<form id="thwec_builder_block_form" class="popup_form_class">
				<input type="hidden" name="i_block_id" value="">
				<input type="hidden" name="i_block_name" value="">
				<input type="hidden" name="i_block_props" value="">
				<input type="hidden" name="i_popup_flag" value="">
				<table class="thwec_field_form_general" cellspacing="10px"></table>
			</form>
		</div>
		<div id="thwec_builder_edit_block_pp" style="display:none;">
			<form id="thwec_builder_block_edit_form" class="popup_form_class">
				<input type="hidden" name="i_block_id" value="">
				<input type="hidden" name="i_block_name" value="">
				<input type="hidden" name="i_block_props" value="">
				<table class="thwec_field_form_edit" cellspacing="10px"></table>
			</form>
		</div>
		<?php 
		$this->render_builder_elm_pp_rows();
		$this->render_builder_elm_pp_col();
		$this->render_builder_elm_pp_header(); 
		$this->render_builder_elm_pp_footer();
		$this->render_builder_elm_pp_image();
		$this->render_builder_elm_pp_social_icons();
		$this->render_builder_elm_pp_customer_address();
		$this->render_builder_elm_pp_billing_address();
		$this->render_builder_elm_pp_shipping_address();
		$this->render_builder_elm_pp_divider();
		$this->render_builder_elm_pp_text();
		$this->render_builder_elm_pp_button();
		$this->render_builder_elm_pp_order();
		$this->render_builder_elm_pp_gif();
		$this->render_builder_elm_pp_gap();
			// $this->render_builder_elm_pp_video();
		$this->render_builder_elm_pp_temp_builder();
		$this->render_add_row_block();
		$this->render_add_element_block();

	}

	private function render_add_row_block(){
		?>
		<div id="thwec_field_form_id_add-row" style="display: none;">
			<table class="thwec-tbuilder-elm-grid">
			<tbody>
				<!-- Layouts  -->
				<tr>
					<td class="section-title" colspan="4">Layouts</td>
				</tr>
				<tr>
					<td class="elm-col">
						<div id="one-column" class="tbuilder-elm column_layout" data-block-name="one_column">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/one-column.svg';?>" alt="">
							<p>1 Column</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="two-column" class="tbuilder-elm column_layout" data-block-name="two_column">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/two-column.svg';?>" alt="">
							<p>2 Column</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="three-column" class="tbuilder-elm column_layout" data-block-name="three_column">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/three-column.svg';?>" alt="">
							<p>3 Column</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="four-column" class="tbuilder-elm column_layout" data-block-name="four_column">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/four-column.svg';?>" alt="">
							<p>4 Column</p>
						</div>
					</td>
				</tr>
														
			</tbody>
		</table>
		</div>
		<?php
	}

	private function render_add_element_block(){
		?>
		<div id="thwec_field_form_id_add-element" style="display: none;">
			<table class="thwec-tbuilder-elm-grid">
			<tbody>
				<!-- Layouts  -->
				<tr>
					<td class="section-title" colspan="5">Structures</td>
				</tr>
				<tr>
					<td class="elm-col">
						<div id="one-column" class="tbuilder-elm column_layout" data-block-name="one_column">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/one-column.svg';?>" alt="">
							<p>1 Column</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="two-column" class="tbuilder-elm column_layout" data-block-name="two_column">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/two-column.svg';?>" alt="">
							<p>2 Column</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="three-column" class="tbuilder-elm column_layout" data-block-name="three_column">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/three-column.svg';?>" alt="">
							<p>3 Column</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="four-column" class="tbuilder-elm column_layout" data-block-name="four_column">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/four-column.svg';?>" alt="">
							<p>4 Column</p>
						</div>
					</td>
					<td></td>
				</tr>
<!-- 				<tr>
					<td class="elm-col">
						<div id="left-large-column" class="column_layout" data-block-name="left-large-column">
							<img src="<?php //echo THWEC_ASSETS_URL_ADMIN.'images/left-large.svg';?>" alt="">
							<p>Left Large</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="right-large-column" class="column_layout" data-block-name="right-large-column">
							<img src="<?php //echo THWEC_ASSETS_URL_ADMIN.'images/right-large.svg';?>" alt="">
							<p>Right Large</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="gallery-column" class="column_layout" data-block-name="gallery-column">
							<img src="<?php //echo THWEC_ASSETS_URL_ADMIN.'images/layout.png';?>" alt="">
							<p>Gallery</p>
						</div>
					</td>
					<td></td>
				</tr> -->
				<tr class="section-gap"><td colspan="5"></td></tr>
				<!-- Elements -->
				<tr><td  class="section-title" colspan="5">Basic Elements</td></tr>
				<tr>
					<td class="elm-col">
						<div id="text" class="tbuilder-elm block_element" data-block-name="text">
							<span class="dashicons dashicons-editor-textcolor"></span>
							<p>Text</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="image" class="tbuilder-elm block_element" data-block-name="image">
							<span class="dashicons dashicons-format-image"></span>
							<p>Image</p>
						</div>
					</td>					
					<td class="elm-col">
						<div id="social" class="tbuilder-elm block_element" data-block-name="social">
							<span class="dashicons dashicons-share"></span>
							<p>Social</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="button" class="tbuilder-elm block_element" data-block-name="button">
							<img src=" <?php echo THWEC_ASSETS_URL_ADMIN.'images/button.svg';?>" alt="">
							<p>Buttons</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="divider" class="tbuilder-elm block_element" data-block-name="divider">
							<img src=" <?php echo THWEC_ASSETS_URL_ADMIN.'images/divider.svg';?>" alt="">
							<p>Divider</p>
						</div>
					</td>
				</tr>
				<tr>
					<td class="elm-col">
						<div id="gif" class="tbuilder-elm block_element" data-block-name="gif">
							<img src=" <?php echo THWEC_ASSETS_URL_ADMIN.'images/gif.svg';?>" alt="">
							<p>GIF</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="gap" class="tbuilder-elm block_element" data-block-name="gap">
							<img src=" <?php echo THWEC_ASSETS_URL_ADMIN.'images/gap.svg';?>" alt="">
							<p>Gap</p>
						</div>
					</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr class="section-gap"><td colspan="5"></td></tr>
				<tr><td  class="section-title" colspan="5">Woocommerce Elements</td></tr>
				<tr>
					<td class="elm-col">
						<div id="header_details" class="tbuilder-elm block_element" data-block-name="header_details">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/header.svg';?>" alt="">
							<p>Header</p>
						</div>
					</td>
					<!-- <td class="elm-col">
						<div id="footer_details" class="tbuilder-elm block_element" data-block-name="footer_details">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/footer.svg';?>" alt="">
							<p>Footer</p>
						</div>
					</td> -->
					<td class="elm-col">
						<div id="customer_address" class="tbuilder-elm block_element" data-block-name="customer_address">
							<img src=" <?php echo THWEC_ASSETS_URL_ADMIN.'images/customer-details.svg';?>" alt="">
							<p>Customer</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="order_details" class="tbuilder-elm block_element" data-block-name="order_details">
							<img src=" <?php echo THWEC_ASSETS_URL_ADMIN.'images/order-details.svg';?>" alt="">
							<p>Order</p>
						</div>
					</td>					
					<td class="elm-col">
						<div id="billing_address" class="tbuilder-elm block_element" data-block-name="billing_address">
							<img src=" <?php echo THWEC_ASSETS_URL_ADMIN.'images/billing-details.svg';?>" alt="">
							<p>Billing</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="shipping_address" class="tbuilder-elm block_element" data-block-name="shipping_address">
							<img src=" <?php echo THWEC_ASSETS_URL_ADMIN.'images/shipping-details.svg';?>" alt="">
							<p>Shipping</p>
						</div>
					</td>
				</tr>
				<tr>	
					<td class="elm-col">
						<div id="woocommerce_email_footer" class="tbuilder-elm block_element hook_element" data-block-name="downloadable_product">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/hooks.svg';?>" alt="" >
							<p>Downloadable Product</p>
						</div>
					</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>	
				</tr>
				<tr class="section-gap"><td colspan="5"></td></tr>
				<tr><td  class="section-title" colspan="5">WooCommerce Hooks</td></tr>
				<tr>
					<td class="elm-col">
						<div id="woocommerce_email_header" class="tbuilder-elm block_element hook_element" data-block-name="email_header">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/hooks.svg';?>" alt="" >
							<p>Email Header</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="woocommerce_email_order_details" class="tbuilder-elm block_element hook_element" data-block-name="email_order_details">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/hooks.svg';?>" alt="" >
							<p>Email Order Details</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="woocommerce_email_before_order_table" class="tbuilder-elm block_element hook_element" data-block-name="before_order_table">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/hooks.svg';?>" alt="" >
							<p>Before Order Table</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="woocommerce_email_after_order_table" class="tbuilder-elm block_element hook_element" data-block-name="after_order_table">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/hooks.svg';?>" alt="" >
							<p>After Order Table</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="woocommerce_email_order_meta" class="tbuilder-elm block_element hook_element" data-block-name="order_meta">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/hooks.svg';?>" alt="" >
							<p>Order Meta</p>
						</div>
					</td>
				</tr>
				<tr>
					<td class="elm-col">
						<div id="woocommerce_email_customer_details" class="tbuilder-elm block_element hook_element" data-block-name="customer_details">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/hooks.svg';?>" alt="" >
							<p>Customer Details</p>
						</div>
					</td>
					<td class="elm-col">
						<div id="woocommerce_email_footer" class=tbuilder-elm "block_element hook_element" data-block-name="email_footer">
							<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/hooks.svg';?>" alt="" >
							<p>Email Footer</p>
						</div>
					</td>
					<td></td>
					<td></td>
					<td></td>	
				</tr>			
				<tr class="section-gap"><td colspan="5"></td></tr>						
			</tbody>
		</table>
		</div>
		<?php
	}

	private function render_builder_elm_pp_fragment_border($content=false,$prefix=''){
		$atts = array('content' => 'Border Properties', 'padding-top' => '10px');
		if($content){
			$atts['content'] = $content;
		}
		$this->render_form_fragment_h_separator($atts);
		$cell_props = array('input_width' => '100px');
		?>
		<tr>
			<td colspan="2">Border</td>  
			<td colspan="4">    
			<?php       
			$this->render_form_field_element($this->field_props[$prefix.'border_width'], $cell_props, false);
			$this->render_form_field_element($this->field_props[$prefix.'border_style'], $cell_props, false);
			$this->render_form_field_element($this->field_props[$prefix.'border_color'], $cell_props, false);
			$this->render_form_element_empty_cell();
			?>
			</td>
		</tr>
		<?php /*<tr>      
			<?php       
			$this->render_form_field_element($this->field_props[$prefix.'border_width'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props[$prefix.'border_style'], $this->cell_props_R);
			?>
		</tr>
		<tr>      
			<?php       
			$this->render_form_field_element($this->field_props[$prefix.'border_color'], $this->cell_props_L);
			$this->render_form_element_empty_cell();
			// $this->render_form_field_element($this->field_props[$prefix.'border_radius'], $this->cell_props_R);
			?>
		</tr> */?>
		<?php
	}

	private function render_builder_elm_pp_fragment_bg($content=false,$prefix=''){
		$atts = array('content' => 'Background Properties', 'padding-top' => '10px');
		if($content){
			$atts['content'] = $content;
		}
		$this->render_form_fragment_h_separator($atts);

		$cell_props = array('input_width' => '100px');

		?>
		<tr>      
			<?php       
			$this->render_form_field_element($this->field_props[$prefix.'bg_image'], $this->cell_props_R);
			?>
			<td colspan="2"><input type="button" name="image_upload" value="Upload" onclick="thwecImageUploader(this,'bg_image')" class="thwec-upload-button"></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td><div class="img_preview_bg_image"></div></td>
			<td colspan="3"></td>
		</tr>
		<tr>
			<td colspan="2">Background</td>  
			<td colspan="4">    
			<?php       
			$this->render_form_field_element($this->field_props[$prefix.'bg_color'], $cell_props, false);
			$this->render_form_field_element($this->field_props[$prefix.'bg_size'], $cell_props, false);
			$this->render_form_field_element($this->field_props[$prefix.'bg_position'], $cell_props, false);
			$this->render_form_field_element($this->field_props[$prefix.'bg_repeat'], $cell_props, false);
			?>
			</td>
		</tr>
		<?php /*<tr>      
			<?php       
			$this->render_form_field_element($this->field_props[$prefix.'bg_position'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props[$prefix.'bg_repeat'], $this->cell_props_L);
			?>
		</tr> */ ?>
		<?php
	}

	private function render_builder_elm_pp_fragment_text($content=false,$text_flag=true,$prefix=false){
		// $atts = array('content' => 'Header Text Properties', 'padding-top' => '10px');
		// if($content){
			// $atts['content']=$content;
		// }
		// $this->render_form_fragment_h_separator($atts);
		$cell_props = array('input_width' => '100px');
		?>
		<tr>      
			<?php 
			if($text_flag){      
				$this->render_form_field_element($this->field_props[$prefix.'content'], $this->cell_props_L);
			}
			?>
		</tr>
		<tr>
			<td colspan="2">Font</td>  
			<td colspan="4"> 
			<?php       
			$this->render_form_field_element($this->field_props[$prefix.'color'], $cell_props, false);
			$this->render_form_field_element($this->field_props[$prefix.'font_size'], $cell_props, false);
			$this->render_form_field_element($this->field_props[$prefix.'line_height'], $cell_props, false);
			$this->render_form_field_element($this->field_props[$prefix.'text_align'], $cell_props, false);
			?>
		</tr>
		<tr>
			<?php       
			$this->render_form_field_element($this->field_props[$prefix.'font_weight'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props[$prefix.'font_family'], $this->cell_props_R);
			?>
		</tr>
		<?php /*<tr>      
			<?php       
			$this->render_form_field_element($this->field_props[$prefix.'font_size'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props[$prefix.'font_weight'], $this->cell_props_R);
			?>
		</tr>
		<tr>      
			<?php       
			$this->render_form_field_element($this->field_props[$prefix.'font_family'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props[$prefix.'text_align'], $this->cell_props_L);
			?>
		</tr>
		<tr>
			<?php
			$this->render_form_field_element($this->field_props[$prefix.'line_height'], $this->cell_props_R);
			$this->render_form_element_empty_cell();
			?>
		</tr>
		*/?>
		<?php
	}

	private function render_builder_elm_pp_fragment_img($content=false,$show_bg_props=true, $prefix='img_'){
		$atts = array('content' => 'Header Image Properties', 'padding-top' => '10px');
		if($content){
			$atts['content']=$content;
		}
		$this->render_form_fragment_h_separator($atts);
		?>
		<tr>
			<?php       
			$this->render_form_field_element($this->field_props['url'], $this->cell_props_L);
			?>      
			<td colspan="2"><input type="button" name="image_upload" value="Upload" onclick="thwecImageUploader(this,'image')" class="thwec-upload-button"></td>
			<td></td>
		</tr>
		<tr>
			<td colspan="2"></td>
			<td><div class="img_preview_image"></div></td>
			<td colspan="3"></td>
		</tr>
		<tr>      
			<?php       
			$this->render_form_field_element($this->field_props['img_width'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props['img_height'], $this->cell_props_R);
			?>
		</tr>
		<tr>
			<?php
			$this->render_form_field_element($this->field_props['align'], $this->cell_props_R);
			$this->render_form_field_element($this->field_props['img_bg_color'], $this->cell_props_L);
			?>
			<td></td>
		</tr>
		<tr>      
			<?php       
			$this->render_form_field_element($this->field_props['img_padding'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props['img_margin'], $this->cell_props_R);
			?>
		</tr>
		<?php 
		if($show_bg_props){
			$this->render_builder_elm_pp_fragment_border('Image Border Properties','img_'); 
		}
	}

	private function render_builder_elm_pp_rows(){
		?>
		<table id="thwec_field_form_id_row" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
				?>
			</tr>
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['align'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['cell_spacing'],$this->cell_props_R);
				?>
			</tr>
			<tr>
				<?php 
				$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['margin'], $this->cell_props_L);
				?>
				<td></td>
			</tr>
			<?php
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg(); 
			?>
        </table>
        <?php   
	}

	private function render_builder_elm_pp_col(){
		?>
		<table id="thwec_field_form_id_col" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
				?>
			</tr>
			<tr>      
				<?php       
				// $this->render_form_field_element($this->field_props['color'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['text_align'],$this->cell_props_R);				
				?>
			</tr>
			<?php
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg(); 
			?>
        </table>
        <?php   
	}

	private function render_builder_elm_pp_header(){
		?>
        <table id="thwec_field_form_id_header_details" class="thpl-admin-form-table thec-admin-form-table" style="display: none;">
        	<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
				?>
			</tr>
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['margin'], $this->cell_props_R);
				?>
			</tr>
			<?php 
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg(); 
			$this->render_builder_elm_pp_fragment_text(false,true); 
			$this->render_builder_elm_pp_fragment_img(false,true); 
			?>
        </table>
        <?php   
	}

	private function render_builder_elm_pp_footer(){
		?>
        <table id="thwec_field_form_id_footer_details" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
        	<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
				?>
			</tr>
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['margin'], $this->cell_props_R);
				?>
			</tr>
			<?php 
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg();  
			?>
			<tr>
				<td>Footer Text</td>
				<td></td>
				<td colspan="4">
				<?php 
				echo '<textarea name="i_textarea_content" rows="7" cols="72"></textarea>';
				?>
		    	</td>
			</tr>
			<tr>
				<?php
				$this->render_form_field_element($this->field_props['color'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['font_size'], $this->cell_props_R);
				?>
			</tr>
			<tr>
				<?php
				$this->render_form_field_element($this->field_props['font_weight'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['text_align'], $this->cell_props_R);
				?>
			</tr>
        </table>
        <?php   
	}

	private function render_builder_elm_pp_image(){
		?>
        <table id="thwec_field_form_id_image" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
        	<tr>      
				<?php       
				$this->render_builder_elm_pp_fragment_bg(); 
				$this->render_builder_elm_pp_fragment_img('Image Properties',true); 
				?>
        </table>
        <?php   
	}

	private function render_builder_elm_pp_customer_address(){
		?>
        <table id="thwec_field_form_id_customer_address" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
        	<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
				?>
			</tr>
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['margin'], $this->cell_props_R);
				?>
			</tr>
			<?php 
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg(); 
			$atts = array('content' => 'Heading Properties', 'padding-top' => '10px');
			$this->render_form_fragment_h_separator($atts);
			$this->render_builder_elm_pp_fragment_text('Text Properties',true); 
			$atts = array('content' => 'Details Properties', 'padding-top' => '10px');
			$this->render_form_fragment_h_separator($atts);
			$this->render_builder_elm_pp_fragment_text('Details Properties',false,'details_'); 
			?>   
        </table>
        <?php   
	}

	private function render_builder_elm_pp_billing_address(){
		?>
        <table id="thwec_field_form_id_billing_address" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
				?>
			</tr>
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['margin'], $this->cell_props_R);
				?>
			</tr>
			<tr>
				<?php
				$this->render_form_field_element($this->field_props['align'], $this->cell_props_L);
				$this->render_form_element_empty_cell();
				?>
			</tr>
			<?php 
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg(); 
			$atts = array('content' => 'Heading Properties', 'padding-top' => '10px');
			$this->render_form_fragment_h_separator($atts);
			$this->render_builder_elm_pp_fragment_text('Text Properties',true); 
			$atts = array('content' => 'Details Properties', 'padding-top' => '10px');
			$this->render_form_fragment_h_separator($atts);
			$this->render_builder_elm_pp_fragment_text('Details Properties',false,'details_'); 
			?>   
        </table>
        <?php   
	}

	private function render_builder_elm_pp_shipping_address(){
		?>
        <table id="thwec_field_form_id_shipping_address" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
        	<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
				?>
			</tr>
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['margin'], $this->cell_props_R);
				?>
			</tr>
			<?php 
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg(); 
			$atts = array('content' => 'Heading Properties', 'padding-top' => '10px');
			$this->render_form_fragment_h_separator($atts);
			$this->render_builder_elm_pp_fragment_text('Text Properties',true); 
			$atts = array('content' => 'Details Properties', 'padding-top' => '10px');
			$this->render_form_fragment_h_separator($atts);
			$this->render_builder_elm_pp_fragment_text('Details Properties',false,'details_'); 
			?>   
        </table>
        <?php   
	}

	private function render_builder_elm_pp_social_icons(){
		$this->cell_props_S['input_cell_props'] = 'width="5%"';
		?>
        <table id="thwec_field_form_id_social" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">

			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['margin'], $this->cell_props_R);
				?>
			</tr>
			<tr>
				<?php
				$atts = array('content' => 'Icon Properties', 'padding-top' => '10px');
				$this->render_form_fragment_h_separator($atts);
				?>
			</tr>
			<tr>
		        <?php
				$this->render_form_field_element($this->field_props['url1'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['url2'], $this->cell_props_R);
				?>
			</tr>     
			<tr>      
				<?php
 				$this->render_form_field_element($this->field_props['url3'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['url4'], $this->cell_props_R); 
				?>
			</tr> 
			<tr>      
				<?php         
				$this->render_form_field_element($this->field_props['url5'], $this->cell_props_L); 
				$this->render_form_field_element($this->field_props['url6'], $this->cell_props_R); 
				?>
			</tr> 	
			<tr>      
				<?php        
				$this->render_form_field_element($this->field_props['url7'], $this->cell_props_L); 
				$this->render_form_element_empty_cell();
				?>
			</tr> 					  
			<tr>
            	<?php
            	$this->render_form_field_element($this->field_props['img_width'], $this->cell_props_L);
            	$this->render_form_field_element($this->field_props['img_height'], $this->cell_props_R);
            	?>
            </tr>
      		<tr>     
				<?php         
				$this->render_form_field_element($this->field_props['text_align'], $this->cell_props_L);
				?>
			</tr>       
			<tr>     
				<?php         
				$this->render_form_field_element($this->field_props['img_padding'], $this->cell_props_L);
				$this->render_form_element_empty_cell();
				?>
			</tr> 
			<?php
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg(); 
			?>
        </table>
        <?php   
	}

	private function render_builder_elm_pp_divider(){
		?>
        <table id="thwec_field_form_id_divider" class=" thpl-admin-form-table thec-admin-form-table" style="display:none;">
        	<tr>
				<?php
				$atts = array('content' => 'Divider Properties', 'padding-top' => '10px');
				$this->render_form_fragment_h_separator($atts);
				?>
			</tr>
			<tr>
        		<?php       
				$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['divider_width'], $this->cell_props_R);
				?>
        	</tr>
     	  	<tr>
        		<?php       
				$this->render_form_field_element($this->field_props['divider_color'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['divider_style'], $this->cell_props_R);
				?>
        	</tr>
        	<tr>
        		<?php       
				$this->render_form_field_element($this->field_props['margin'], $this->cell_props_L);
				$this->render_form_element_empty_cell();
				?>
        	</tr>
        </table>
        <?php   
	}

	private function render_builder_elm_pp_text(){
		?>
        <table id="thwec_field_form_id_text" class="thpl-admin-form-table thec-admin-form-table" style="display: none;">
      	<tr>      
			<?php       
			$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
			?>
		</tr>
		<tr>      
			<?php       
			$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props['margin'], $this->cell_props_R);
			?>
		</tr>
		<tr>
			<?php
			$this->render_form_field_element($this->field_props['align'],$this->cell_props_L);
			$this->render_form_element_empty_cell();
			?>
		</tr>
			<?php
			$this->render_builder_elm_pp_fragment_bg();
			$this->render_builder_elm_pp_fragment_border();
			$atts = array('content' => 'Text Properties', 'padding-top' => '10px');
			$this->render_form_fragment_h_separator($atts);
			?>
		<tr>
			<td colspan="6">
			<?php 
			// $content = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry";
			// wp_editor( 'content is this', $content, array ( 'tinymce' => false )); 
 			
			// echo '<textarea name="i_textarea_content" rows="7" cols="80">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500.</textarea>';

			echo '<textarea name="i_textarea_content" rows="7" cols="80"><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500.</p></textarea>';
			?>
		    </td>
		</tr>
		<tr>
			<?php 
			$this->render_builder_elm_pp_fragment_text(false,false,false);
			?>
		</tr>
		<?php /*<tr>
			<?php       
			$this->render_form_field_element($this->field_props['color'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props['font_size'], $this->cell_props_R);
			?>
		</tr>
		<tr>
			<?php       
			$this->render_form_field_element($this->field_props['font_weight'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props['font_family'], $this->cell_props_L);
			?>
		</tr>	
		<tr>
			<?php
			$this->render_form_field_element($this->field_props['text_align'],$this->cell_props_L);
			$this->render_form_field_element($this->field_props['line_height'], $this->cell_props_R);
			?>
		</tr>	*/ ?>
        </table>
        <?php   
	}

	private function render_builder_elm_pp_button(){
		?>
        <table id="thwec_field_form_id_button" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
		<tr>
			<?php
			$atts = array('content' => 'Button', 'padding-top' => '10px');
			$this->render_form_fragment_h_separator($atts);
			?>
		</tr>
		<tr>
			<?php
			$this->render_form_field_element($this->field_props['url'],$this->cell_props_L);
			$this->render_form_field_element($this->field_props['content'],$this->cell_props_R);
			?>
		</tr>
		<tr>
			<?php
			$this->render_form_field_element($this->field_props['title'],$this->cell_props_L);
			$this->render_form_element_empty_cell();
			?>
		</tr>
		<tr>
			<?php
			$this->render_builder_elm_pp_fragment_text(false,false,false);
			?>
		</tr>
		<?php /*<tr>
			<?php
			$this->render_form_field_element($this->field_props['color'],$this->cell_props_L);
			$this->render_form_field_element($this->field_props['font_size'],$this->cell_props_R);
			?>
		</tr>
		<tr>
			<?php
			$this->render_form_field_element($this->field_props['font_family'],$this->cell_props_L);
			$this->render_form_element_empty_cell();
			?>
		</tr>
		<tr>
			<?php
			$this->render_form_field_element($this->field_props['font_weight'],$this->cell_props_L);
			$this->render_form_field_element($this->field_props['line_height'],$this->cell_props_R);
			?>
		</tr> */?>
		<tr>      
			<?php       
			$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
			?>
		</tr>
		<tr>      
			<?php       
			$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
			$this->render_form_field_element($this->field_props['margin'], $this->cell_props_R);
			?>
		</tr>
			<?php
			$this->render_builder_elm_pp_fragment_bg();
			$this->render_builder_elm_pp_fragment_border();
			?>
		  				
        </table>
        <?php   
	}

	private function render_builder_elm_pp_order(){
		?>
        <table id="thwec_field_form_id_order_details" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
				?>
			</tr>
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['margin'], $this->cell_props_R);
				?>
			</tr>
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['align'], $this->cell_props_L);
				$this->render_form_element_empty_cell();
				?>
			</tr>
			<?php 
			$this->render_builder_elm_pp_fragment_border(); 
			$this->render_builder_elm_pp_fragment_bg(); 
			$this->render_builder_elm_pp_fragment_text('Text Properties',true); 
			$atts = array('content' => 'Table Properties', 'padding-top' => '10px');
			$this->render_form_fragment_h_separator($atts);
			?>
			<tr>
				<?php
				$this->render_form_field_element($this->field_props['content_width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['content_height'], $this->cell_props_R);
				?>
			</tr>
			<!-- <tr> -->
				<?php
				$this->render_form_field_element($this->field_props['content_bg_color'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['content_border_color'], $this->cell_props_R);
				?>
			</tr>
			<tr>
				<?php
				$this->render_form_field_element($this->field_props['checkbox_option_image'], $this->cell_props_CBL);
				?>
			</tr>	
			<tr>
				<?php
				$this->render_form_field_element($this->field_props['content_padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['content_margin'], $this->cell_props_R);
				?>
			</tr>
			<?php
			$this->render_builder_elm_pp_fragment_text('Table Content Properties',false,'details_'); 
			?>                        
        </table>
        <?php   
	}	

	private function render_builder_elm_pp_gif(){
		?>
        <table id="thwec_field_form_id_gif" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
       		<?php
       		$atts = array('content' => 'Gif Properties', 'padding-top' => '10px');
			$this->render_form_fragment_h_separator($atts);
        	?>
        	<tr>
				<?php       
				$this->render_form_field_element($this->field_props['url'], $this->cell_props_L);
				?>      
				<td colspan="2"><input type="button" name="image_upload" value="Upload" onclick="thwecImageUploader(this,'image')" class="thwec-upload-button"></td>
				<td></td>
			</tr>
			<tr>
				<?php
				$this->render_form_field_element($this->field_props['align'], $this->cell_props_L);
				$this->render_form_element_empty_cell();
				?>
				<!-- <td colspan="3"></td> -->
			</tr>
        	<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
				?>
			</tr>
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['margin'], $this->cell_props_R);
				?>
			</tr>
			<?php
        		$this->render_builder_elm_pp_fragment_border();
        		$this->render_builder_elm_pp_fragment_bg();	
        	?>
        </table>
        <?php   
	}

	private function render_builder_elm_pp_gap(){
		?>
        <table id="thwec_field_form_id_gap" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
        	<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['width'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['height'], $this->cell_props_R);
				?>
			</tr>
			<tr>      
				<?php       
				$this->render_form_field_element($this->field_props['padding'], $this->cell_props_L);
				$this->render_form_field_element($this->field_props['margin'], $this->cell_props_R);
				?>
			</tr>
        	<tr>
        		<?php
        		$this->render_builder_elm_pp_fragment_border();
        		$this->render_builder_elm_pp_fragment_bg();
        		?>
        	</tr>
        </table>
        <?php   
	}
	
	private function render_builder_elm_pp_temp_builder(){
		?>
		<table id="thwec_field_form_id_temp_builder" class=" thpl-admin-form-table thec-admin-form-table" style="display:none">
		<tr>
			<td></td>
			<td></td>
			<td style="width:65%;" colspan="4"></td>
		</tr>
		<?php
		$this->render_builder_elm_pp_fragment_border();
        $this->render_builder_elm_pp_fragment_bg();
		?>
	    </table>
		<?php
	}

	private function render_template_elements(){
		$this->render_template_layout_1_col_row();
		$this->render_template_layout_2_col_row();
		$this->render_template_layout_3_col_row();
		$this->render_template_layout_4_col_row();
		$this->render_template_layout_left_large_col_row();
		$this->render_template_layout_right_large_col_row();
		$this->render_template_layout_gallery_col_row();

		
		$this->render_template_element_header();
		$this->render_template_element_footer();
		$this->render_template_element_customer_address();
		$this->render_template_element_order_details();
		$this->render_template_element_billing_address();
		$this->render_template_element_shipping_address();
		$this->render_template_element_text();
		$this->render_template_element_image();
		$this->render_template_element_social();
		$this->render_template_element_button();
		$this->render_template_element_divider();
		$this->render_template_element_gap();
		$this->render_template_element_gif();
		// $this->render_template_element_coupon();
		// $this->render_template_element_video();

		$this->render_template_hook_email_header();
		$this->render_template_hook_email_order_details();
		$this->render_template_hook_before_order_table();
		$this->render_template_hook_after_order_table();
		$this->render_template_hook_order_meta();
		$this->render_template_hook_customer_details();
		$this->render_template_hook_email_footer();
		$this->render_template_hook_downloadable_product();

		$this->render_template_tracking_add_row_html();
		$this->render_template_tracking_add_col_html();
		$this->render_template_tracking_add_elm_html();
		$this->render_template_tracking_add_hook_html();
		$this->add_column_confirm_dialog();
		$this->save_changes_confirm_dialog();

	}
	
	private function render_template_layout_1_col_row(){
		?>
		<div id="thwec_template_layout_1_col" style="display:none;">
			<table class="thwec-row thwec-block-one-column builder-block" id="one_column" data-elm="row-1-col">
				<tr>
					<td class="column-padding thwec-col thwec-columns" id="one_column_1" data-props='{"width":"100%","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd"}'>
						<span class="builder-add-btn btn-add-element"><p>+ Add Element</p></span>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
	
	private function render_template_layout_2_col_row(){
		?>
		<div id="thwec_template_layout_2_col" style="display:none;">
			<table class="thwec-row thwec-block-two-column builder-block" id="two_column" data-elm="row-2-col">
				<tr>
					<td class="column-padding thwec-col thwec-columns" id="two_column_1"  data-props='{"width":"50%","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd"}'>
						<span class="builder-add-btn btn-add-element"><p>+ Add Element</p></span>
					</td>
					<td class="column-padding thwec-col thwec-columns" id="two_column_2" data-props='{"width":"50%","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd"}'>
						<span class="builder-add-btn btn-add-element"><p><strong>+</strong> Add Element</p></span>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
	
	private function render_template_layout_3_col_row(){
		?>
		<div id="thwec_template_layout_3_col" style="display:none;">
			<table class="thwec-row thwec-block-three-column builder-block" id="three_column">
				<tr>
					<td class="column-padding thwec-columns" id="three_column_1" data-props='{"width":"33.333333333333336%","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd"}'>
						<span class="builder-add-btn btn-add-element"><p>+ Add Element</p></span>
					</td>
					<td class="column-padding thwec-columns" id="three_column_2" data-props='{"width":"33.333333333333336%","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd"}'>
						<span class="builder-add-btn btn-add-element"><p>+ Add Element</p></span>
					</td>
					<td class="column-padding thwec-columns" id="three_column_3" data-props='{"width":"33.333333333333336%","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd"}'>
						<span class="builder-add-btn btn-add-element"><p>+ Add Element</p></span>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
	
	private function render_template_layout_4_col_row(){
		?>
		<div id="thwec_template_layout_4_col" style="display:none;">
			<table class="thwec-row thwec-block-four-column builder-block" id="four_column">
				<tr>           
					<td class="column-padding thwec-columns" id="four_column_1" data-props='{"width":"25%","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd"}'>
						<span class="builder-add-btn btn-add-element"><p>+ Add Element</p></span>
					</td>
					<td class="column-padding thwec-columns" id="four_column_2" data-props='{"width":"25%","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd"}'>
						<span class="builder-add-btn btn-add-element"><p>+ Add Element</p></span>
					</td>
					<td class="column-padding thwec-columns" id="four_column_3" data-props='{"width":"25%","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd"}'>
						<span class="builder-add-btn btn-add-element"><p>+ Add Element</p></span>
					</td>
					<td class="column-padding thwec-columns" id="four_column_4" data-props='{"width":"25%","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd"}'>
						<span class="builder-add-btn btn-add-element"><p>+ Add Element</p></span>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	private function render_template_layout_left_large_col_row(){
		?>
		<div id="thwec_template_layout_left_large_col" style="display:none;">
			<table class="thwec-row thwec-block-left-large-column builder-block">
				<tr>           
					<td class="column-padding thwec-columns">
						<span class="dashicons dashicons-edit" onclick="thwecBuilderBlockEdit(this, 'column-one', 'column-one')"></span>
					</td>
					<td class="column-padding thwec-columns">
						<span class="dashicons dashicons-edit" onclick="thwecBuilderBlockEdit(this, 'column-two', 'column-two')"></span>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
	
	private function render_template_layout_right_large_col_row(){
		?>
		<div id="thwec_template_layout_right_large_col" style="display:none;">
			<table class="thwec-row thwec-block-right-large-column builder-block">
				<tr>           
					<td style="width:30%;" class="column-padding thwec-columns">
						<span class="dashicons dashicons-edit" onclick="thwecBuilderBlockEdit(this, 'column-one', 'column-one')"></span>
					</td>
					<td style="width:70%;" class="column-padding thwec-columns">
						<span class="dashicons dashicons-edit" onclick="thwecBuilderBlockEdit(this, 'column-two', 'column-two')"></span>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	private function render_template_layout_gallery_col_row(){
		?>
		<div id="thwec_template_layout_gallery_col" style="display:none;">
			<table class="thwec-row thwec-block-gallery-column builder-block">
				<tr>           
					<td class="column-padding thwec-columns">
						<span class="dashicons dashicons-edit" onclick="thwecBuilderBlockEdit(this, 'column-four', 'column-one-one')"></span>
					</td>
					<td class="column-padding thwec-columns">
						<span class="dashicons dashicons-edit" onclick="thwecBuilderBlockEdit(this, 'column-four', 'column-one-two')"></span>
					</td>
					<td class="column-padding thwec-columns">
						<span class="dashicons dashicons-edit" onclick="thwecBuilderBlockEdit(this, 'column-four', 'column-one-three')"></span>
					</td>
				</tr>
				<tr>           
					<td class="column-padding thwec-columns">
						<span class="dashicons dashicons-edit" onclick="thwecBuilderBlockEdit(this, 'column-four', 'column-two-one')"></span>
					</td>
					<td class="column-padding thwec-columns">
						<span class="dashicons dashicons-edit" onclick="thwecBuilderBlockEdit(this, 'column-four', 'column-two-two')"></span>
					</td>
					<td class="column-padding thwec-columns">
						<span class="dashicons dashicons-edit" onclick="thwecBuilderBlockEdit(this, 'column-four', 'column-two-three')"></span>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}

	private function render_template_element_header(){
		?>
		<div id="thwec_template_elm_header" style="display: none;">
			<table class="thwec-block thwec-block-header builder-block" id="{header_details}" data-block-name="header_details">
				<tr>
					<td class="header-logo">
						<p class="header-logo-ph">
							<img src="https://www.themehigh.com/wp-content/uploads/2018/03/themehigh-logo.png" alt="">
						</p>
					</td>
				</tr>
				<tr>
					<td class="header-text">
						<h1>Email Template Header</h1>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
	
	private function render_template_element_footer(){
		?>
		<div id="thwec_template_elm_footer" style="display:none;">
			<table class="thwec-block thwec-block-footer builder-block" id="{footer_details}" data-block-name="footer_details">
				<tr>
					<td class="footer-padding">
						<p>Company Name&nbsp;&nbsp;|&nbsp;&nbsp;Address 1&nbsp;&nbsp;|&nbsp;&nbsp;Address 2</p>
						<p>If you no more wish to receive our emails, please click <a href="#">unsubscribe</a></p>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}


	
	private function render_template_element_customer_address(){
		?>
		<div id="thwec_template_elm_customer_address" style="display:none;">
			<span class="before_customer_table"></span>
			<table class="thwec-block thwec-block-customer builder-block" id="{customer_address}" data-block-name="customer_address">
      			<tr>
      				<td class="customer-padding">
      					<h2 class="thwec-customer-header">Customer Details</h2>
      					<p class="address thwec-customer-body">
      						John Smith<br>johnsmith@gmail.com<br>333-6457
      					</p>	
      				</td>
      			</tr>
      		</table>
      		<span class="after_customer_table"></span>
		</div>
		<?php
	}

	private function render_template_element_order_details(){
		?>
		<div id="thwec_template_elm_order_details" style="display:none;">
			<?php
			$thwec_total = array('label1'=>'Subtotal:','label2'=>'Shipping:','label3'=>'Payment method:','label4'=>'Total:','value1'=>'$20','value2'=>'Free shipping','value3'=>'Cash on delivery','value4'=>'$20');
			$thwec_item = array('item1'=>'T-shirt','item2'=>'Jeans','qty1'=>'1','qty2'=>'1','price1'=>'$5','price2'=>'$15');
			?>
			<span class="loop_start_before_order_table"></span>
			<table class="thwec-block thwec-block-order builder-block" id="{order_details}" data-block-name="order_details">
				<tr class="before_order_table"></tr>
				<tr>
					<td class="order-padding">
						<span class="woocommerce_email_before_order_table"></span>
      					<h2 class="thwec-order-heading"><u><span class="order-title">Order</span>#248</u> (November 22, 017)</h2>
						<table class="thwec-order-table thwec-td" style="font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
							<thead>
								<tr>
									<th class="thwec-td order-head" scope="col" style="">Product</th>
									<th class="thwec-td order-head" scope="col" style="">Quantity</th>
									<th class="thwec-td order-head" scope="col" style="">Price</th>
								</tr>
							</thead>
							<tbody>
								<tr class="item-loop-start"></tr>
								<?php for($j=1;$j<=2;$j++) { ?>
								<tr class="woocommerce_order_item_class-filter<?php echo $j; ?>">
									<td class="order-item thwec-td" style="vertical-align:middle;word-wrap:break-word;">
											<div class="thwec-order-item-img">
												<img src="<?php echo THWEC_ASSETS_URL_ADMIN.'images/product.png'; ?>" alt="">
											</div>
											<?php echo $thwec_item['item'.$j]; ?>
									</td>
									<td class="order-item-qty thwec-td" style="vertical-align:middle;">
										<?php echo $thwec_item['qty'.$j]; ?>
									</td>
									<td class="order-item-price thwec-td" style="vertical-align:middle;">
										<?php echo $thwec_item['price'.$j];?>
									</td>
								</tr>
								<?php } ?>
								<tr class="item-loop-end"></tr>
							</tbody>
							<tfoot class="order-footer">
								<tr class="order-total-loop-start"></tr>
							<?php 
							for($i=1;$i<=4;$i++){ ?>
								<tr class="order-footer-row">
									<th class="order-total-label thwec-td" scope="row" colspan="2"><?php echo $thwec_total['label'.$i]; ?></th>
									<td class="order-total-value thwec-td"><?php echo $thwec_total['value'.$i]; ?></td>
								</tr>
							<?php } ?>
							<tr class="order-total-loop-end"></tr>
							</tfoot>
						</table>
      					</td>
      				</tr>
      			</table>
      			<span class="loop_end_after_order_table"></span>
			</div>

		<?php
	}

	private function render_template_element_billing_address(){
		?>
		<div id="thwec_template_elm_billing_address" style="display:none;">
			<span class="before_billing_table"></span>
			<table class="thwec-block thwec-block-billing builder-block" id="{billing_address}" data-block-name="billing_address">
      			<tr>
      				<td class="billing-padding">  	
      					<h2 class="thwec-billing-header">Billing Details</h2>
      					<p class="address thwec-billing-body">
      						John Smith<br>
     						252  Bryan Avenue<br>
     						Minneapolis, MN 55412<br>
     						United States (US)
     						<br>333-6457<br><a href="#">johnsmith@gmail.com</a>;
      					</p>
      				</td>
      			</tr>
      		</table>
      		<span class="after_billing_table"></span>
		</div>
		<?php
	}

	private function render_template_element_shipping_address(){
		?>
		<div id="thwec_template_elm_shipping_address" style="display:none;">
			<span class="before_shipping_table"></span>
			<table class="thwec-block thwec-block-shipping builder-block" id="{shipping_address}" data-block-name="shipping_address">
      			<tr>
      				<td class="shipping-padding">      
     	 				<h2 class="thwec-shipping-header">Shipping Details</h2>
      					<p class="address thwec-shipping-body">
     						John Smith<br>
     						252  Bryan Avenue<br>
     						Minneapolis, MN 55412<br>
     						United States (US)
      					</p>
      				</td>
      			</tr>
      		</table>
      		<span class="after_shipping_table"></span>
		</div>
		<?php
	}

	private function render_template_element_text(){
		?>
		<div id="thwec_template_elm_text" style="display:none;">
			<div class="thwec-block thwec-block-text builder-block" id="{text}" data-block-name="text">
				<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500.</p>
			</div>
		</div>
		<?php
	}


	private function render_template_element_image(){
		?>
		<div id="thwec_template_elm_image" style="display:none;"> 
		    <p class=" thwec-block thwec-block-image builder-block" id="{image}" data-block-name="image">
      			<img src="<?php echo THWEC_ASSETS_URL_ADMIN ?>images/image.jpg" alt="" />
      		</p>
		</div>
		<?php
	}

	private function render_template_element_social(){
		?>
		<div id="thwec_template_elm_social" style="display:none;">
  			<p class="thwec-block thwec-block-social builder-block" id="{social}" data-block-name="social">
  				<a href="http://www.facebook.com" class="thwec-social-icon facebook">
      				<img src="<?php echo THWEC_ASSETS_URL_ADMIN ?>images/fb_icon_square.png" alt="">
      			</a>
				<a href="https://mail.google.com/mail/?view=cm&to=yourmail@example.com&bcc=somemail@example.com" class="thwec-social-icon gmail" >
					<img src="<?php echo THWEC_ASSETS_URL_ADMIN ?>images/google_icon_square.png" alt="">
				</a>	
				<a href="http://www.twitter.com" class="thwec-social-icon twitter">
					<img src="<?php echo THWEC_ASSETS_URL_ADMIN ?>images/twitter_icon_square.png" alt="">
				</a>
				<a href="http://www.youtube.com" class="thwec-social-icon youtube">
					<img src="<?php echo THWEC_ASSETS_URL_ADMIN ?>images/youtube_icon_square.png" alt="">
				</a>
				<a href="https://www.linkedin.com/" class="thwec-social-icon linkedin">
					<img src="<?php echo THWEC_ASSETS_URL_ADMIN ?>images/linkedin_icon_square.png" alt="">
				</a>
			
				<a href="http://www.pinterest.com" class="thwec-social-icon pinterest">
					<img src="<?php echo THWEC_ASSETS_URL_ADMIN ?>images/pinterest_icon_square.png" alt="">
				</a>
  				<a href="http://www.instagram.com" class="thwec-social-icon instagram">
    				<img src="<?php echo THWEC_ASSETS_URL_ADMIN ?>/images/instagram_icon_square.png" alt="">
  				</a>
  			</p>
		</div>
		<?php
	}

	private function render_template_element_button(){
		?>
		<div id="thwec_template_elm_button" style="display:none;">
     	 	<a href="#" title="alt text" class="thwec-block builder-block thwec-button-link" id="{button}" data-block-name="button">Click Here</a>
		</div>
		<?php
	}

	private function render_template_element_divider(){
		?>
		<div id="thwec_template_elm_divider" style="display:none;">
      		<div><hr class="thwec-block builder-block thwec-block-divider" id="{divider}" data-block-name="divider"></div>
		</div>
		<?php
	}

	private function render_template_element_gap(){
		?>
		<div id="thwec_template_elm_gap" style="display:none;">
      		<p class="thwec-block thwec-block-gap builder-block" id="{gap}" data-block-name="gap"></p>
		</div>
		<?php
	}

	private function render_template_element_gif(){
		?>
		<div id="thwec_template_elm_gif" style="display:none;">
        	<p class="thwec-block thwec-block-gif builder-block" id="{gif}" data-block-name="gif">
        		<img src="<?php echo THWEC_ASSETS_URL_ADMIN ?>images/gif.gif" alt="" />
        	</p>
		</div>
		<?php
	}


	private function render_template_hook_email_header(){
		?>
		<div id="thwec_template_hook_email_header" style="display:none;">
			<p class="hook-code" data-hook="{email_header}">{email_header_hook}</p>
		</div>
		<?php
	}

	private function render_template_hook_email_order_details(){
		?>
		<div id="thwec_template_hook_order_details" style="display:none;">
			<p class="hook-code" data-hook="{email_order_details}">{email_order_details_hook}</p>
		</div>
		<?php		
	}

	private function render_template_hook_before_order_table(){
		?>
		<div id="thwec_template_hook_before_order_table" style="display:none;">
			<p class="hook-code" data-hook="{before_order_table}">{before_order_table_hook}</p>
		</div>
		<?php		
	}

	private function render_template_hook_after_order_table(){
		?>
		<div id="thwec_template_hook_after_order_table" style="display:none;">
			<p class="hook-code" data-hook="{after_order_table}">{after_order_table_hook}</p>
		</div>
		<?php		
	}

	private function render_template_hook_order_meta(){
		?>
		<div id="thwec_template_hook_order_meta" style="display:none;">
			<p class="hook-code" data-hook="{order_meta}">{order_meta_hook}</p>
		</div>
		<?php		
	}

	private function render_template_hook_customer_details(){
		?>
		<div id="thwec_template_hook_customer_details" style="display:none;">
			<p class="hook-code" data-hook="{customer_details}">{customer_details_hook}</p>
		</div>
		<?php		
	}

	private function render_template_hook_email_footer(){
		?>
		<div id="thwec_template_hook_email_footer" style="display:none;">
			<p class="hook-code" data-hook="{email_footer}">{email_footer_hook}</p>
		</div>
		<?php		
	}

	private function render_template_hook_downloadable_product(){
		?>
		<div id="thwec_template_downloadable_product" style="display: none;">
			<p class="hook-code" data-hook="{downloadable_product}">{downloadable_product_table}</p>
		</div>
		<?php
	}

	private function render_template_tracking_add_row_html(){
		?>
		<div id="thwec_tracking_panel_row_html" style="display:none;">	
			<div class="layout-lis-item">
				<span class="dashicons dashicons-arrow-down list-collapse"></span>
				<span class="sortable-row-handle">Row</span>
				<span class="dashicons dashicons-admin-generic thwec-settings">
					<div class="settings-expand">
		      			<a class="edit item-settings" onclick="thwecBuilderBlockEdit(this, {bl_id}, '{bl_name}')">Edit</a>
		      			<a class="clone item-settings" onclick="thwecBuilderBlockClone(this)">Clone</a>
		      			<a class="delete item-settings" onclick="thwecBuilderBlockDelete(this)">Delete</a>
					</div>
				</span>
			</div>
		</div>
		<?php
	}

	private function render_template_tracking_add_col_html(){
		?>
		<div id="thwec_tracking_panel_col_html" style="display:none;">	
			<div class="layout-lis-item">
				<span class="dashicons dashicons-arrow-right list-collapse"></span>
				<span class="sortable-col-handle">Column</span>
				<span class="dashicons dashicons-admin-generic thwec-settings">
					<div class="settings-expand">
						<a class="edit item-settings" onclick="thwecBuilderBlockEdit(this, {bl_id}, '{bl_name}')">Edit</a>
		      			<a class="clone item-settings" onclick="thwecBuilderBlockClone(this)">Clone</a>
		      			<a class="delete item-settings" onclick="thwecBuilderBlockDelete(this)">Delete</a>
					</div>
				</span>
			</div>
		</div>
		<?php
	}

	private function render_template_tracking_add_elm_html(){
		?>
		<div id="thwec_tracking_panel_elm_html" style="display:none;">	
			<div class="layout-lis-item">
				<span class="sortable-elm-handle">{name}</span>
				<span class="dashicons dashicons-admin-generic thwec-settings">
					<div class="settings-expand">
						<a class="edit item-settings" onclick="thwecBuilderBlockEdit(this, {bl_id}, '{bl_name}')">Edit</a>
		      			<a class="clone item-settings" onclick="thwecBuilderBlockClone(this)">Clone</a>
		      			<a class="delete item-settings" onclick="thwecBuilderBlockDelete(this)">Delete</a>
					</div>
				</span>
			</div>
		</div>
		<?php
	}

	private function render_template_tracking_add_hook_html(){
		?>
		<div id="thwec_tracking_panel_hook_html" style="display:none;">	
			<div class="layout-lis-item">
				<span class="sortable-elm-handle">{name}</span>
				<span class="dashicons dashicons-trash thwec-settings" onclick="thwecBuilderBlockDelete(this)"></span>
			</div>
		</div>
		<?php
	}

	private function add_column_confirm_dialog(){
		?>
		<div id="add_col_confirm" style="display: none;">Adding new column resize the existing columns in the row</div>
		<?php
	}

	private function save_changes_confirm_dialog(){
		?>
		<div id="save_changes_confirm" style="display: none;">All unsaved changes will be lost. Do you want to continue?</div>
		<?php
	}

	/*private function render_template_element_footer(){
		?>
		<div id="thwec_template_elm_footer" style="display:none;">
			<table class="thwec-block thwec-block-footer" cellpadding="10" cellspacing="0">
				<tr><td class="footer-padding">
					<div class="footer-logo">
						<img src="<?php //echo THWEC_ASSETS_URL_ADMIN ?>images/themehigh.png" alt="">
					</div>
					<div class="vertical-line"></div>
					<div class="footer-text">
						<p class="footer-text1">Text1</p>
						<p class="footer-text2">Contact Number +91 0000 000 000</p>
						<p class="footer-text3">www.example@gmail.com</p>
						<hr>
						<p class="footer-link"><span class="footer-link-html">If you no more wish to receive our emails, please click</span><a href="#" class="footer-unsubscribe">Unsubscribe</a></p>
					</div>

				</td></tr>
			</table>
		</div>
		<?php
	} */


	/*private function shortcode_popup(){
		?>
		<div id="shortcode_display" style="display: none;">
			<table cellpadding="10">
				<tr>
					<td width="15%"><input type="button" value="[CopyRight]" id="copyright_shortcode" class="thec-shortcode-class"/></td>
					<td>Use Copyright Symbol</td>
				</tr>
				<tr>
					<td><input type="button" value="[CopyRight]" placeholder="" id="" class="thec-shortcode-class"/></td>
					<td>Description</td>
				</tr>
				<tr>
					<td><input type="button" value="[TH_CUSTOMER]" placeholder="" id="" class="thec-shortcode-class"/></td>
					<td>Customer Name to be displayed in Email Salutation</td>
				</tr>
				<tr>
					<td><input type="button" value="[TH_ORDER_NO]" placeholder="" id="" class="thec-shortcode-class"/></td>
					<td>Order Number in Order Table</td>
				</tr>								
			</table>
		</div>
		<?php
	}*/
	
}

endif;