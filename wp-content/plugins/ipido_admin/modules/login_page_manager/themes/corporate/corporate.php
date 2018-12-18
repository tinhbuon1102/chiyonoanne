<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

class Ipido_admin_login_theme_Corporate extends Ipido_admin_Login_Theme{

	public function __construct(){
		parent::__construct();

        $this->theme_name   = 'corporate';
		$this->theme_prefix = 'ipido_theme-'.$this->theme_name;

		$this->settings = (object) array(
			'show_outside_logo'		=> false,
			'show_side_logo'		=> false,
			'show_links_outside'	=> false,
		);


		// $this->define_hooks();
		// $this->run();
	}

	public function init(){
		// $this->load_dependencies();
		$this->define_hooks();
		$this->run();
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
					'content'		=> __('Corporate Theme Settings'),
				),
				array(
                    'id'        => $theme_prefix.'background_type',
                    'type'      => 'image_select',
                    'title'     => __('Background Type','ipido_admin'),
                    'options'   => array(
						'gallery' 	=> CS_PLUGIN_URI .'/adminframework/assets/images/background-gallery.png',
						'custom' 	=> CS_PLUGIN_URI .'/adminframework/assets/images/background-custom.png',
						'youtube' 	=> CS_PLUGIN_URI .'/adminframework/assets/images/background-video-youtube.png',
						'vimeo' 	=> CS_PLUGIN_URI .'/adminframework/assets/images/background-video-vimeo.png',
                    ),
                    'radio'     => true,
                    'default'   => 'gallery',
				),
				array(
					'dependency'    => array($theme_prefix.'background_type_gallery','==','true'),
                    'id'        	=> $theme_prefix.'background_gallery',
                    'type'      	=> 'image_gallery_custom',
					'title'     	=> __('Background Gallery','ipido_admin'),
					'default'		=> 'bg-polygon-3',
					'settings'		=> array(
						'images_path'	=> '/assets/images/images_gallery/',
					),
				),
				array(
                    'dependency'    => array($theme_prefix.'background_type_custom','==','true'),
                    'id'            => $theme_prefix.'background',
                    'type'          => 'background',
                    'title'         => __('Custom Background Image','ipido_admin'),
                    'desc'          => __('Background image, color and settings etc. for login page (Eg: http://www.yourdomain.com/wp-login.php)','ipido_admin'),
                    'settings'       => array(
                        'button_title' => __('Choose Background','ipido_admin'),
                        'frame_title'  => __('Choose an image to use as a login background','ipido_admin'),
						'insert_title' => __('Use this background image','ipido_admin'),
						'preview_size'  => 'medium',
                        'repeat'        => true,
                        'position'      => true,
                        'attachment'    => false,
						'size'          => true,
						'color'			=> true,
                    ),
                    'default'       => array(
                        'repeat'     	=> 'no-repeat',
                        'position'   	=> 'center center',
						'attachment' 	=> 'fixed',
						'size'			=> 'cover',
                        'color'      	=> '#ffbc00',
					),
                    // 'wrap_class'	=> 'csf-field-subfield',
				),
				array(
					'dependency'    => array($theme_prefix.'background_type_youtube','==','true'),
					'id'			=> $theme_prefix . 'background_youtube',
					'type'			=> 'text',
					'title'			=> __('YouTube Video URL','ipido_admin'),
					'attributes'	=> array(
						'placeholder'	=> __('Insert any YouTube video url here'),
					),
					'default'		=> 'https://www.youtube.com/watch?v=vGrf-e8Rhok',
				),
				array(
					'dependency'    => array($theme_prefix.'background_type_vimeo','==','true'),
					'id'			=> $theme_prefix . 'background_vimeo',
					'type'			=> 'text',
					'title'			=> __('Vimeo Video URL','ipido_admin'),
					'attributes'	=> array(
						'placeholder'	=> __('Insert any Vimeo video url here'),
					),
					'default'		=> 'https://vimeo.com/152051757',
				),
				array(
					'id'		=> $theme_prefix.'footer_form',
					'type'      => 'wysiwyg',
					'title'     => __('Form Footer Text','ipido_admin'),
					'desc'      => __('Enter the text that displays in the footer of the login form area','ipido_admin'),
					'default'	=> __('Need more help? <a href="http://www.castorstudio.com" target="_blank">Contact us!</a>','ipido_admin'),
					'settings'  => array(
						'textarea_rows' => 5,
						'tinymce'       => true,
						'media_buttons' => false,
						'quicktags'     => false,
						'teeny'         => true,
					),
				),
				array(
					'id'		=> $theme_prefix.'footer_page',
					'type'      => 'wysiwyg',
					'title'     => __('Page Footer Text','ipido_admin'),
					'desc'      => __('Enter the text that displays in the footer of the login page','ipido_admin'),
					'default'	=> __('Copyright 2018 <a href="http://www.castorstudio.com" target="_blank">CastorStudio</a>','ipido_admin'),
					'settings'  => array(
						'textarea_rows' => 5,
						'tinymce'       => true,
						'media_buttons' => false,
						'quicktags'     => false,
						'teeny'         => true,
					),
				),

				array(
                    'type'      => 'subheading',
                    'content'   => __('Side Content','ipido_admin'),
                ),
				array(
                    'id'            => $theme_prefix.'background_side',
                    'type'          => 'background',
                    'title'         => __('Side Background Image','ipido_admin'),
                    'desc'          => __('Background image, color and settings etc.','ipido_admin'),
                    'settings'       => array(
                        'button_title' => __('Choose Background','ipido_admin'),
                        'frame_title'  => __('Choose an image to use as a login background','ipido_admin'),
						'insert_title' => __('Use this background image','ipido_admin'),
						'preview_size'  => 'medium',
                        'repeat'        => true,
                        'position'      => true,
                        'attachment'    => false,
						'size'          => true,
						'color'			=> true,
                    ),
                    'default'       => array(
                        'repeat'     	=> 'no-repeat',
                        'position'   	=> 'center center',
						'attachment' 	=> 'fixed',
						'size'			=> 'cover',
                        'color'      	=> '#cb3002',
					),
				),
				array(
					'id'		=> $theme_prefix.'side_title',
					'type'		=> 'text',
					'title'		=> __('Side Title','ipido_admin'),
					'default'	=> __('We reimagine the WordPress admin','ipido_admin'),
				),
				array(
					'id'		=> $theme_prefix.'side_description',
					'type'      => 'wysiwyg',
					'title'     => __('Side Description','ipido_admin'),
					// 'desc'      => __('Enter the text that displays in the footer bar.','ipido_admin'),
					'default'	=> __('The Ipido Admin is your solution for customizing the WordPress admin area','ipido_admin'),
					'settings'  => array(
						'textarea_rows' => 5,
						'tinymce'       => true,
						'media_buttons' => false,
						'quicktags'     => false,
						'teeny'         => true,
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

		// Login Logo
		$login_logo 	= $this->_gs('logo_image_login');
		$login_logo 	= wp_get_attachment_url($login_logo);
		$login_logo 	= "url({$login_logo})";

		// Login Background Custom
		$bg_type = $option('background_type');
		if ($bg_type){
			if ($bg_type == 'gallery'){
				$bg = $option('background_gallery');

				// Find BG File
				$path 	= CS_PLUGIN_URI . "/images/ipido-$bg";
				$ext 	= '.png'; // Our default file extension
				$files 	= glob("$path.*");
				foreach ($files as $file){
					$info = pathinfo($file);
					$ext = $info["extension"];
				}

				$image_url 					= $path.$ext;
				$background_image_url 		= "url($image_url)";
				$background_image_repeat	= 'no-repeat';
				$background_image_position	= 'center center';
				$background_image_size		= 'cover';
				$background_image_color		= 'initial';
			} else if ($bg_type == 'custom'){
				$bg = $option('background');
				
				$image_url 					= wp_get_attachment_url($bg['image']);
				$background_image_url		= "url({$image_url})";
				$background_image_repeat	= $bg['repeat'];
				$background_image_position	= $bg['position'];
				$background_image_size		= $bg['size'];
				$background_image_color		= $bg['color'];
			}
		}

		// Side Background
		$side_background	= $option('background_side');
		if ($side_background){
			$bg 							= $side_background;
			$image_url 						= wp_get_attachment_url($bg['image']);
			$side_background_image_url		= "url({$image_url})";
			$side_background_image_repeat	= $bg['repeat'];
			$side_background_image_position	= $bg['position'];
			$side_background_image_size		= $bg['size'];
			$side_background_image_color	= $bg['color'];
		}

		$side_color = 'rgb(255,255,255)';

		// Theme
		$theme_primary 	= '#cc2f04'; // #cb3002
		$theme_accent 	= '#edb700';

		// Grays
		$gray 			= '#585858';
		$gray_light		= '#b8b8b8';
		$gray_lighter	= '#e5e5e5';

		// Link
		$link_general	= $theme_primary;
		$link_accent	= '#ffffff';


		// Output Theme CSS Vars
		// ==========================================================================
		$output = "
		:root{
			%s_login-logo:					$login_logo;
			%s_login-background:			$background_image_url $background_image_repeat $background_image_position $background_image_color;
			%s_login-background-size: 		$background_image_size;
			%s_login-side-background: 		$side_background_image_url $side_background_image_repeat $side_background_image_position $side_background_image_color;
			%s_login-side-background-size: 	$side_background_image_size;
			%s_login-side-color: 			$side_color;
			%s_login-theme-primary:			$theme_primary;
			%s_login-theme-accent:			$theme_accent;
			%s_login-theme-gray:			$gray;
			%s_login-theme-gray-light:		$gray_light;
			%s_login-theme-gray-lighter:	$gray_lighter;
		}
		";
		$prefix = CS_CSS_THEME_SLUG;
		$output = str_replace('%s',$prefix,$output);
		return $output;
	}

	private function define_hooks(){
		/**
		 * Enqueue Scripts
		 */
		$this->add_action( 'login_enqueue_scripts', $this,'enqueue_scripts') ;

		/**
		 * Render HTML Fields
		 */
		// $this->add_filter('login_message',$this,'render_header_title_html');
		$this->add_action('login_header',$this,'render_header_html');
		$this->add_action('login_footer',$this,'render_footer_html');
		$this->add_action('login_form',$this,'render_form_html');
		$this->add_action('lostpassword_form',$this,'render_lostpwform_html');
		$this->add_action('register_form',$this,'render_registerform_html');
	}

	function enqueue_scripts(){
		// wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/style-login.css' );
		// wp_enqueue_script( 'custom-login', get_stylesheet_directory_uri() . '/style-login.js' );
		$fonts = "Nunito:200,400,700|Slabo+27px";
		wp_enqueue_style('cs-ipido-admin-google-fonts',"https://fonts.googleapis.com/css?family={$fonts}", false ); 
	}
	function render_header_title_html($message){
		$pattern = "/<p ?.*>(.*)<\/p>/";
    	preg_match($pattern, $message, $matches);
		$_message = $matches[1];
		
		$output_message = '';
		if ($_message == 'Register For This Site'){
			$output_message .= "
				<div class='cs-ipido-admin-login-title'>
					<h2>Register on our site</h2>
				</div>
			";
		} else if ($_message == 'Please enter your username or email address. You will receive a link to create a new password via email.'){
			$output_message .= "
				<div class='cs-ipido-admin-login-title'>
					<h2>Recover your password</h2>
				</div>
			";
		} else {
			$output_message .= "
				<div class='cs-ipido-admin-login-title'>
					<h2>Welcome back!</h2>
				</div>
			";
		}

		$output_message .= $message;

		return $output_message;

		// return "
		// 	<div class='cs-ipido-admin-login-title'>
		// 		<h2>Welcome back to Ipido Admin!</h2>
		// 	</div>
		// 	{$message}
		// ";
	}
	function render_header_html(){
		$this->the_header('layout1');
	}
	function render_footer_html(){
		$this->the_footer('layout1');
	}
	function render_form_html(){
		$forgetmenot 	= $this->get_forgetmenot();
		$lostpw 		= $this->get_link_lostpassword();
		$register 		= $this->get_link_register('Don\'t have an account?');
		echo "
			<div class='cs-ipido-admin-login-links'>
				{$forgetmenot}
				{$lostpw}
			</div>
			{$register}
		";
	}
	function render_lostpwform_html(){
		$login 			= $this->get_link_login();
		echo "
			<div class='cs-ipido-admin-login-links'>
				{$login}
			</div>
		";
	}
	function render_registerform_html(){
		$login 			= $this->get_link_login();
		echo "
			<div class='cs-ipido-admin-login-links'>
				{$login}
			</div>
		";
	}
	function background_content(){
		$video_type = $this->_gst('background_type');

		if ($video_type == 'youtube'){
			$video_url 	= $this->_gst('background_youtube');
			$video_id 	= $this->get_youtube_id($video_url);
			$video_obj 	= "<iframe id='player' type='text/html' width='100%' height='100%' src='https://www.youtube.com/embed/{$video_id}?autoplay=1&controls=0&enablejsapi=1&fs=0&color=white&loop=1&showinfo=0&iv_load_policy=3&playlist={$video_id}' frameborder='0' allowfullscreen></iframe>";
		} else if ($video_type == 'vimeo'){
			$video_url 	= $this->_gst('background_vimeo');
			$video_id 	= $this->get_vimeo_id($video_url);
			$video_obj 	= "<iframe src='https://player.vimeo.com/video/{$video_id}?background=1' width='100%' height='100%' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
		}

		if (isset($video_id)){
			// return "<div id='player'></div>";
			return "<div id='cs-ipido-admin-video-background-player'>{$video_obj}</div>";
		}
	}
	function footer_form(){
		$text = wpautop($this->_gst('footer_form'));
		$backto = $this->get_back_to_site();
		return $backto . $text;
	}
	function footer_page(){
		$text = wpautop($this->_gst('footer_page'));
		return $text;
	}

	function additional_content(){
		$title 	= $this->_gst('side_title');
		$desc 	= wpautop($this->_gst('side_description'));
		$html 	= "
			<div class='cs-ipido-admin-side-intro'>
				<h2>{$title}</h2>
				<div class='cs-ipido-admin-side-intro-body'>
					{$desc}
				</div>
			</div>
		";
					// <ul>
					// 	<li>It's completely free with no commitment.</li>
					// 	<li>Amazing deals not available anywhere else!</li>
					// 	<li>After you have booked, we continue scanning to get you an even better deal.</li>
					// </ul>
		return $html;
	}

}