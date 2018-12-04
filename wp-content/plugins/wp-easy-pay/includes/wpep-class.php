<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;
if (!class_exists('WPEP_Settings')) {
    class WPEP_Settings {
        /**
         * Class Constructor
         */
        public function __construct() {
			//add admin_menu
            add_action('admin_menu', array($this, 'wpep_settings'));
            //add admin script
            add_action('admin_enqueue_scripts', array($this, 'wpep_admin_scripts'));
            //add admin script
            add_action('admin_notices', array($this, 'wpep_update_notice'));
            //add admin script
            add_action('admin_footer', array($this, 'wpep_notice_script'));
			add_action('wp_ajax_dismiss_wpep_notice', array($this, 'dismiss_wpep_notice'));
			add_action('wp_ajax_nopriv_dismiss_wpep_notice', array($this, 'dismiss_wpep_notice'));
        }
        public function wpep_settings() {
            add_menu_page(__('WP Easy Pay Settings','wp-easy-pay'), __('WP Easy Pay','wp-easy-pay'), 'manage_options', 'wpep-settings', array($this, 'wpep_settings_html'),WPEP_PLUGIN_URL.'assets/img/square.png');
            add_submenu_page('wpep-settings', __('WP Easy Pay','wp-easy-pay'), __('Settings','wp-easy-pay'), 'manage_options', 'wpep-settings', array($this, 'wpep_settings_html'));
            add_submenu_page('wpep-settings', __('WP Easy Pay','wp-easy-pay'), __('Button','wp-easy-pay'), 'manage_options', 'wpep-button', array($this, 'wpep_button_html'));
			add_submenu_page('wpep-settings', __('WP Easy Pay Pro Features','wp-easy-pay'), __('Pro Features','wp-easy-pay'), 'manage_options', 'wpep-pro-features', array($this, 'wpep_pro_features_html'));
			//call register settings function
            add_action('admin_init', array($this, 'wpep_register_settings'));
        }
        public function wpep_register_settings() {
            register_setting('wpep-settings-group', 'wpep_square_mode');
            register_setting('wpep-settings-group', 'wpep_square_currency');
            register_setting('wpep-settings-group', 'wpep_test_appid');
            register_setting('wpep-settings-group', 'wpep_test_token');
            register_setting('wpep-settings-group', 'wpep_test_locationid');
            register_setting('wpep-settings-group', 'wpep_live_appid');
            register_setting('wpep-settings-group', 'wpep_live_token');
            register_setting('wpep-settings-group', 'wpep_live_locationid');

            register_setting('wpep-button-settings-group', 'wpep_button_type');
            register_setting('wpep-button-settings-group', 'wpep_button_text');
            register_setting('wpep-button-settings-group', 'wpep_amount');
            register_setting('wpep-button-settings-group', 'wpep_donation_organization_name');
            register_setting('wpep-button-settings-group', 'wpep_donation_user_amount');
            register_setting('wpep-button-settings-group', 'wpep_notification_email');
        }
        public function wpep_settings_html() {
            ?>            
            <div class="wrap">
                <h1><?php _e('WP Easy Pay Square Settings', 'wp-easy-pay') ?></h1><br>
                <a href="http://apiexperts.io/link/square-partners/" target="_blank"><img src="<?php echo WPEP_PLUGIN_URL.'assets/img/signup.png'; ?>"/></a>
                <p><?php echo sprintf(__('Get Square API keys from <a href="%s" target="_blank">here</a>.', 'wp-easy-pay'), 'https://connect.squareup.com/apps'); ?></p>
                <form method="post" action="options.php">
                    <?php settings_fields('wpep-settings-group'); ?>
                    <?php do_settings_sections('wpep-settings-group'); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th><?php _e('Mode', 'wp-easy-pay') ?></th>
                            <td>
                                <input type="radio" <?php if (get_option('wpep_square_mode') == 'live'): ?>checked="checked"<?php endif; ?> value="live" id="wpep_square_mode_live" name="wpep_square_mode">
                                <label for="wpep_square_mode_live" class="inline"><?php _e('Live', 'wp-easy-pay') ?></label>
                                &nbsp;&nbsp;&nbsp; <input type="radio" <?php if (get_option('wpep_square_mode') == 'test' || get_option('wpep_square_mode') == ''): ?>checked="checked"<?php endif; ?> value="test" id="wpep_square_mode_test" name="wpep_square_mode">
                                <label for="wpep_square_mode_test" class="inline"><?php _e('Test', 'wp-easy-pay') ?></label>                                
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Country Currency', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <select name="wpep_square_currency" style="width: 100px;">
                                        <?php $selected=get_option('wpep_square_currency'); ?>
                                    <option value="USD" <?php echo ($selected=='USD') ? 'selected':''; ?>><?php _e('USD', 'wp-easy-pay'); ?></option>
                                    <option value="CAD" <?php echo ($selected=='CAD') ? 'selected':''; ?>><?php _e('CAD', 'wp-easy-pay'); ?></option>
                                    <option value="AUD" <?php echo ($selected=='AUD') ? 'selected':''; ?>><?php _e('AUD', 'wp-easy-pay'); ?></option>
                                    <option value="JPY" <?php echo ($selected=='JPY') ? 'selected':''; ?>><?php _e('JPY', 'wp-easy-pay'); ?></option>
                                    <option value="GBP" <?php echo ($selected=='GBP') ? 'selected':''; ?>><?php _e('GBP', 'wp-easy-pay'); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2"><?php _e('Test Account', 'wp-easy-pay') ?> <hr></th>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Test Application ID', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input style="width: 60%;" type="text" value="<?php echo get_option('wpep_test_appid'); ?>" name="wpep_test_appid">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Test Token', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input style="width: 60%;" type="text" value="<?php echo get_option('wpep_test_token'); ?>" name="wpep_test_token">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Test Location ID', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input style="width: 60%;" type="text" value="<?php echo get_option('wpep_test_locationid'); ?>" name="wpep_test_locationid">
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2"><?php _e('Live Account', 'wp-easy-pay') ?> <hr></th>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Live Application ID', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input style="width: 60%;" type="text" value="<?php echo get_option('wpep_live_appid'); ?>" name="wpep_live_appid">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Live Token', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input style="width: 60%;" type="text" value="<?php echo get_option('wpep_live_token'); ?>" name="wpep_live_token">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <?php _e('Live Location ID', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input style="width: 60%;" type="text" value="<?php echo get_option('wpep_live_locationid'); ?>" name="wpep_live_locationid">
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }
        public function wpep_button_html() {
            ?>            
            <div class="wrap">
                <h1><?php _e('WP Easy Pay Button', 'wp-easy-pay') ?></h1><br>
                <a href="http://apiexperts.io/link/square-partners/" target="_blank"><img src="<?php echo WPEP_PLUGIN_URL.'assets/img/signup.png'; ?>"/></a>
                <form method="post" action="options.php">     
                    <?php settings_fields('wpep-button-settings-group'); ?>
                    <?php do_settings_sections('wpep-button--settings-group'); ?>
                    <table class="form-table">
                        <tr valign="top">
                            <th>
                                <?php _e('Short Code', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input type="text" value="[wpep_form]" readonly=""/>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th>
                                <?php _e('Notification Email', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input type="email" value="<?php echo get_option('wpep_notification_email', get_option('admin_email')); ?>" name="wpep_notification_email">
                            </td>
                        </tr>
                        
                        <tr valign="top">
                            <th><?php _e('Type', 'wp-easy-pay') ?></th>
                            <td>
                                <input type="radio" <?php if (get_option('wpep_button_type') == 'simple' || get_option('wpep_button_type') == ''): ?>checked="checked"<?php endif; ?> value="simple" id="wpep_button_type" class="wpep_button_type" name="wpep_button_type">
                                <label for="wpep_button_type" class="inline"><?php _e('Simple Payment', 'wp-easy-pay') ?></label>
                                &nbsp;&nbsp;&nbsp; <input type="radio" <?php if (get_option('wpep_button_type') == 'donation'): ?>checked="checked"<?php endif; ?> value="donation" id="wpep_button_type_donation" class="wpep_button_type" name="wpep_button_type">
                                <label for="wpep_button_type_donation" class="inline"><?php _e('Donation', 'wp-easy-pay') ?></label>                                
                            </td>
                        </tr>
                        <tr valign="top">
                            <th>
                                <?php _e('Button Text', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input type="text" value="<?php echo get_option('wpep_button_text'); ?>" name="wpep_button_text">
                            </td>
                        </tr>
                        <tr valign="top" class="wpep-amount">
                            <th>
                                <?php _e('Amount', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input type="number" value="<?php echo get_option('wpep_amount'); ?>" name="wpep_amount">
                            </td>
                        </tr>
                        <tr valign="top" class="wpep-donation">
                            <th>
                                <?php _e('Organization Name', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input type="text" value="<?php echo get_option('wpep_donation_organization_name'); ?>" name="wpep_donation_organization_name">
                            </td>
                        </tr>
                        <tr valign="top" class="wpep-donation">
                            <th>
                                <?php _e('User set donation amount', 'wp-easy-pay') ?>
                            </th>
                            <td>
                                <input class="donation_user_amount" type="checkbox" value="yes" <?php if (get_option('wpep_donation_user_amount') == 'yes'): ?>checked=""<?php endif; ?> name="wpep_donation_user_amount"/>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>
            </div>
            <?php
        }
		
		
        // Pro Features 
        public function wpep_pro_features_html() {
            ?>            
                <div class="wrap">
					
					<div id="pro_features_section" class="addons-featured">
					<div class="addons-banner-block">
					<h1><?php _e('Secure Checkout. Instant Download.', 'wp-easy-pay') ?></h1>
					<p><?php _e('WP Easy Pay Pro is perfect for business owners, startups, consultants, non-profits and developers. It’s basically for anyone wanting to collect payments quickly and easily with minimal setup using Square.', 'wp-easy-pay') ?></p>
					<div class="addons-banner-block-items">
					<div class="addons-banner-block-item">
					<div class="addons-banner-block-item-icon">
					<img class="addons-img starter_png" src="<?php echo WPEP_PLUGIN_URL; ?>assets/img/starter.png">
					</div>
					<div class="addons-banner-block-item-content">
					<h3><?php _e('STARTER', 'wp-easy-pay') ?></h3>
					<div class="pricing-price">
					<span>
					<sup>$</sup><?php _e('49.00', 'wp-easy-pay') ?>		<sub><?php _e('yearly', 'wp-easy-pay') ?></sub>
					</span>
					</div>						
					<div class="pricing-features"><ul>
					<li><?php _e('Single Site', 'wp-easy-pay') ?></li>
					<li><?php _e('Simple Payment', 'wp-easy-pay') ?></li>
					<li><?php _e('Donation', 'wp-easy-pay') ?></li>
					<li><strike><?php _e('Subscription (Recurring Payments)', 'wp-easy-pay') ?></strike></li>
					<li><?php _e('Multiple Buttons', 'wp-easy-pay') ?></li>
					<li><?php _e('Shortcode Support', 'wp-easy-pay') ?></li>
					<li><?php _e('Payment Popup', 'wp-easy-pay') ?></li>
					<li><?php _e('Form Builder', 'wp-easy-pay') ?></li>
					<li><?php _e('Button Customization', 'wp-easy-pay') ?></li>
					<li><?php _e('Send selected fields to <br/>Square Transaction Note (60 characters only)', 'wp-easy-pay') ?></li>
					<li><?php _e('Email send to user on successful payment', 'wp-easy-pay') ?></li>
					<li><?php _e('User enter custom amount', 'wp-easy-pay') ?></li>
					<li><a class="addons-button addons-button-solid" href="#starter">
					<?php _e('Buy Now', 'wp-easy-pay') ?></a></li>
					</ul></div>
					
					
					
					</div>
					</div>
					<div class="addons-banner-block-item">
					<div class="addons-banner-block-item-icon">
					<img class="addons-img business_png" src="<?php echo WPEP_PLUGIN_URL; ?>assets/img/buss.png">
					</div>
					<div class="addons-banner-block-item-content">
					<h3><?php _e('BUSINESS', 'wp-easy-pay') ?></h3>
					<div class="pricing-price">
					<span>
					<sup>$</sup><?php _e('249.00', 'wp-easy-pay') ?>			<sub><?php _e('yearly', 'wp-easy-pay') ?></sub>
					</span>
					</div>
					<div class="pricing-features"><ul>
					<li><?php _e('15 Sites', 'wp-easy-pay') ?></li>
					<li><?php _e('Simple Payments', 'wp-easy-pay') ?></li>
					<li><?php _e('Donations', 'wp-easy-pay') ?></li>
					<li><b><?php _e('Subscription (Recurring Payments)', 'wp-easy-pay') ?></b></li>
					<li><?php _e('Multiple Buttons', 'wp-easy-pay') ?></li>
					<li><?php _e('Shortcode Support', 'wp-easy-pay') ?></li>
					<li><?php _e('Payment Popup', 'wp-easy-pay') ?></li>
					<li><?php _e('Form Builder', 'wp-easy-pay') ?></li>
					<li><?php _e('Button Customization', 'wp-easy-pay') ?></li>
					<li><?php _e('Send selected fields to <br/>Square Transaction Note (60 characters only)', 'wp-easy-pay') ?></li>
					<li><?php _e('Email send to user on successful payment', 'wp-easy-pay') ?></li>
					<li><?php _e('User enter custom amount', 'wp-easy-pay') ?></li>
					<li><a class="addons-button addons-button-solid" href="#business">
					<?php _e('Buy Now', 'wp-easy-pay') ?></a></li>
					</ul></div>
					</div>
					</div>
					<div class="addons-banner-block-item">
					<div class="addons-banner-block-item-icon">
					<img class="addons-img professional_png" src="<?php echo WPEP_PLUGIN_URL; ?>assets/img/pro.png">
					</div>
					<div class="addons-banner-block-item-content">
					<h3><?php _e('PROFESSIONAL', 'wp-easy-pay') ?></h3>
					<div class="pricing-price">
					<span>
					<sup>$</sup><?php _e('99.00', 'wp-easy-pay') ?>		<sub><?php _e('yearly', 'wp-easy-pay') ?></sub>
					</span>
					</div>
					<div class="pricing-features"><ul>
					<li><?php _e('3 Sites', 'wp-easy-pay') ?></li>
					<li><?php _e('Simple Payments', 'wp-easy-pay') ?></li>
					<li><?php _e('Donations', 'wp-easy-pay') ?></li>
					<li><b><?php _e('Subscription (Recurring Payments)', 'wp-easy-pay') ?></b></li>
					<li><?php _e('Multiple Buttons', 'wp-easy-pay') ?></li>
					<li><?php _e('Shortcode Support', 'wp-easy-pay') ?></li>
					<li><?php _e('Payment Popup', 'wp-easy-pay') ?></li>
					<li><?php _e('Form Builder', 'wp-easy-pay') ?></li>
					<li><?php _e('Button Customization', 'wp-easy-pay') ?></li>
					<li><?php _e('Send selected fields to <br/>Square Transaction Note (60 characters only)', 'wp-easy-pay') ?></li>
					<li><?php _e('Email send to user on successful payment', 'wp-easy-pay') ?></li>
					<li><?php _e('User enter custom amount', 'wp-easy-pay') ?></li>
					<li><a class="addons-button addons-button-solid" href="#professional">
					<?php _e('Buy Now', 'wp-easy-pay') ?></a></li>
					</ul></div>
					<p></p>
					</div>
					</div>
					</div>
					</div>
					</div>
                </div>
                <?php
                }
		

        public function wpep_admin_scripts($hook) {
            if ($hook == 'wp-easy-pay_page_wpep-button') {
                wp_enqueue_script('wpep-script', WPEP_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), '', true);
            }
			
			if ($hook == 'wp-easy-pay_page_wpep-pro-features') {
                wp_enqueue_script('checkout_freemius_script', 'https://checkout.freemius.com/checkout.min.js', array('jquery')); 
				wp_enqueue_script('custom_freemius_script',WPEP_PLUGIN_URL.'assets/js/custom-freemius-script-wpep.js', array('jquery')); 

						$plugin_id         = '1920';
						$plugin_public_key = 'pk_4c854593bf607fd795264061bbf57';
						$plugin_secret_key = 'sk_g74m>Y=$?6NXq)i%}[(+5l}NTy^zT';
						$timestamp         = time();
						
						$sandbox_token = md5(
							$timestamp .
							$plugin_id .
							$plugin_secret_key .
							$plugin_public_key .
							'checkout'
						);
					$custom_freemius_script_obj = array(
						'timestamp' => $timestamp,
						'sandbox_token' => $sandbox_token
					);
					wp_localize_script( 'custom_freemius_script', 'custom_freemius_script_obj', $custom_freemius_script_obj );
            }
			
			
			
        }
		
		function wpep_notice_script(){
			if (  get_option('wpep_display_notice_2_1') == 'no' ){
				return '';
			}
			?>
			<script>
				jQuery(document).on( 'click', '.wpep-first-notice .notice-dismiss', function() {
					var data = {
						'action': 'dismiss_wpep_notice'    // We pass php values differently!
					};
					// We can also pass the url value separately from ajaxurl for front end AJAX implementations
					jQuery.post('<?=admin_url( 'admin-ajax.php' )?>', data, function(response) {
						// console.log('Got this from the server: ' + response);
					});
					
					
				})
			</script>
			<style>
				.wpep-first-notice {
					padding: 0;
				}
				.wp-6-c {
					width: 49%;
					display: inline-block;
				}
				.wp-6-c img {
					    width: auto;
						height: 128px;
						float: left;
						margin-left: 30px;
						margin-top: 10px;
						margin-bottom: 10px;
				}
				.content-contact {
					float: left;
					width: 74%;
				}
				.logo-contact {
					float: left;
				}
				.content-contact h2 {
					padding: 0!important;
					font-size: 16px;
					margin: 7px 0 0 0!important;
					line-height: 24px;
					padding-left: 14px!important;
				}
				.content-contact p {
					line-height: 16px;
					margin: 0;
					padding-left: 16px;
				}
				.content-contact .button.button-primary.button-hero {
					box-shadow: 0 2px 0 #006799;
					margin: 8px 0 0px 16px;
					height: auto;
					line-height: 34px;
				}
				.wpep-first-notice > h2 {
					position: absolute;
					top: -61px;
					display: none;
				}
				@media only screen and (max-width: 1252px) {

					.content-contact .button.button-primary.button-hero {
						margin: 3px 0 0px 16px;
					}
					.wp-6-c img {
						height: 100px;
					}
					.content-contact p {
						margin: 5px 0 0 0;
					}
					.wpep_notice {
						margin-top: 65px;
					}
					.wpep-first-notice > h2 {
						display: block;
					}
					.content-contact h2{
						display: none;
					}
				}
				@media only screen and (max-width: 1024px) {
					.wp-6-c {
						width: 100%;
					}
				}
			</style>
    <?php
}
		public function wpep_update_notice(){
			if (  get_option('wpep_display_notice_2_1') == 'no' )
				return;

			if( isset($_GET['page']) && 'wpep-pro-features' == $_GET['page']){
				update_option('wpep_display_notice_2_1','no');
				return;
			}
			$addons_url = admin_url( 'admin.php?page=wpep-pro-features', 'https' );
			$class = 'notice notice-info is-dismissible wpep-first-notice';
			$heading = __( 'Introducing NEW Plans For WP EASY PAY FOR WORDPRESS' , 'wp-easy-pay' );
			$message = '<div class="wpep_notice">
							<div class="wp-6-c">
							<div class="logo-contact">
								  <a href="'.$addons_url.'"> <img src="'. plugins_url( 'assets/img/notice-images/wpep-slider.png', dirname(__FILE__) ). '" alt=""/></a>
							</div>
							<div class="content-contact">
							<h2>'.__("Introducing NEW Plans For WP EASY PAY FOR WORDPRESS","wp-easy-pay").'</h2>
							<p>'.__("After many feature requests and to support the free version,
							Releasing Ultimate plans collection for you.","wp-easy-pay").'</p>
							<a class="button button-primary button-hero" href="'.$addons_url.'">'.__("Checkout NEW Plans","wp-easy-pay").'</a>
							</div>
							</div>
							<div class="wp-6-c"> 
							   <a href="'.$addons_url.'"> <img src="'. WPEP_PLUGIN_URL. 'assets/img/starter.png" alt=""/></a>
								  <a href="'.$addons_url.'"> <img src="'. WPEP_PLUGIN_URL. 'assets/img/buss.png" alt=""/></a>
								  <a href="'.$addons_url.'"> <img src="'. WPEP_PLUGIN_URL. 'assets/img/pro.png" alt=""/></a>
								  
							</div>
							</div>';

			printf( '<div data-dismissible="notice-one-forever-wpep" class="%1$s"><h2 style="font-size: 20px;font-weight: 800;" >%2$s</h2>%3$s</div>', esc_attr( $class ), esc_html( $heading ) ,  $message  );

		}
		function dismiss_wpep_notice(){
			update_option('wpep_display_notice_2_1','no');
			die();

		} 
    }
}