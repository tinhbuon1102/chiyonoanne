<?php
/**
 * WooCommerce Gift Card Settings
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly
if ( ! class_exists( 'WC_Settings_Page' ) ) {
	include_once dirname( GIFTCARD_PATH ) . '/woocommerce/includes/admin/settings/class-wc-settings-page.php';
}
if ( ! class_exists( 'Magenest_Giftcard_Settings' ) ) :

	/**
	 * WC_Settings_Accounts
	 */
	class Magenest_Giftcard_Settings extends WC_Settings_Page {

		/**
		 * Constructor.
		 */
		public function __construct()
		{
			$this->id    = 'giftcard';
			$this->label = __( 'Gift Cards', GIFTCARD_TEXT_DOMAIN );

			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_page' ), 200 );
			add_action( 'woocommerce_settings_' . $this->id, array( $this, 'output' ) );
			add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'save' ) );
			add_action( 'woocommerce_sections_' . $this->id, array( $this, 'output_sections' ) );
			add_action( 'woocommerce_admin_field_file', array( $this, 'display_file' ), 10 );

		}

		public function output_sections()
		{
			global $current_section;

			$sections = $this->get_sections();

			if ( empty( $sections ) ) {
				return;
			}

			echo '<ul class="subsubsub">';

			$array_keys = array_keys( $sections );

			foreach ( $sections as $id => $label ) {
				echo '<li><a href="' . admin_url( 'admin.php?page=wc-settings&tab=' . $this->id . '&section=' . sanitize_title( $id ) ) . '" class="' . ( $current_section == $id ? 'current' : '' ) . '">' . $label . '</a> ' . ( end( $array_keys ) == $id ? '' : '|' ) . ' </li>';
			}

			echo '</ul><br class="clear" />';
		}

		/**
		 * Get sections
		 *
		 * @return array
		 */
		public function get_sections()
		{
			$sections['']                            = __( 'Processing Gift card', 'GIFTCARD' );
			$sections['giftcard_options']            = __( 'Gift card', 'GIFTCARD' );
//			$sections['giftcard_sendfriend_options'] = __( 'Gift card send friend option', 'GIFTCARD' );
//			$sections['pdf_setting']                 = __( 'PDF Setting ', 'GIFTCARD' );
			$sections['default_email_setting']       = __( 'Default Email Setting', 'GIFTCARD' );

			return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
		}

		public function output()
		{
			global $current_section;

			if ( $current_section == 'default_email_setting' ) {
				include_once 'view/default_email_settings.php';
			} else {
				WC_Admin_Settings::output_fields( self::get_setting( $current_section ) );
			}

		}

		public function save()
		{
			global $current_section;
			if ( $current_section == 'default_email_setting' ) {
				$data = $_POST;
				if ( empty( $data ) ) {
					return false;
				}
                if($data['magenest_giftcard_to_content'] == ""){
				    $data['magenest_giftcard_to_content'] = "<?=__('Dear',GIFTCARD_TEXT_DOMAIN)?> {{to_name}},<br/><?=__('You received a gift card from',GIFTCARD_TEXT_DOMAIN)?> {{from_name}}!<br/><?=__('Balance:',GIFTCARD_TEXT_DOMAIN)?> $ {{balance}}<br/><?=__('Message:',GIFTCARD_TEXT_DOMAIN)?> {{message}}<br/><?=__('Code:',GIFTCARD_TEXT_DOMAIN)?> {{code}}";
                }
				update_option('magenest_giftcard_to_subject', wp_kses_post($data['magenest_giftcard_to_subject']));
				update_option('magenest_giftcard_to_content', wp_kses_post($data['magenest_giftcard_to_content']));
				if(isset($data['magenest_giftcard_to_pdf'])){
                    update_option('magenest_giftcard_to_pdf', 'yes');
                }else{
                    update_option('magenest_giftcard_to_pdf', 'no');
                }
                if(isset($data['giftcard_bcc_sender'])){
                    update_option('giftcard_bcc_sender', 'yes');
                }else{
                    update_option('giftcard_bcc_sender', 'no');
                }

			} else {
				WC_Admin_Settings::save_fields( self::get_setting( $current_section ) );
			}
		}

		private function get_setting( $current_section )
		{
			if ( $current_section == '' ) {
				$options = array(
					array(
						'title' => __( 'Processing Gift card', 'GIFTCARD' ),
						'type'  => 'title',
						'id'    => 'giftcard_processing_options_title'
					),
					array(
						'title'    => __( 'Apply gift card for shipping fee', 'GIFTCARD' ),
						'desc'     => __( 'Allow customers to pay shipping fee with  gift card.', 'GIFTCARD' ),
						'id'       => 'giftcard_apply_for_shipping',
						'default'  => 'no',
						'type'     => 'checkbox',
						'autoload' => false
					),
					array(
						'title'    => __( 'Apply gift card for tax', 'GIFTCARD' ),
						'desc'     => __( 'Allow customers to pay for tax with their gift card.', 'GIFTCARD' ),
						'id'       => 'magenest_enable_giftcard_charge_tax',
						'default'  => 'no',
						'type'     => 'checkbox',
						'autoload' => true
					),
					array(
						'title'    => __( 'Apply gift card for fee', 'GIFTCARD' ),
						'desc'     => __( 'Allow customers to pay for fees with their gift card.', 'GIFTCARD' ),
						'id'       => 'magenest_enable_giftcard_charge_fee',
						'default'  => 'no',
						'type'     => 'checkbox',
						'autoload' => false
					),
					array(
						'title'    => __( 'Enable use gift card to buy other gift card', 'GIFTCARD' ),
						'id'       => 'magenest_giftcard_buy_other_giftcard',
						'default'  => 'no',
						'type'     => 'checkbox',
						'autoload' => false
					),
					array(
						'title'    => __( 'Active giftcard and send notification email when status of order', 'GIFTCARD' ),
						'id'       => 'magenest_giftcard_active_when',
						'type'     => 'select',
						'options'  => array(
							'completed'  => __( 'Completed', 'woocommerce' ),
							'processing' => __( 'Processing', 'woocommerce' ),
							'on-hold'    => __( 'On hold', 'woocommerce' ),
							'pending'    => __( 'Pending', 'woocommerce' ),
						),
						'autoload' => false
					),
					array( 'type' => 'sectionend', 'id' => 'giftcard_generate_option' )
				);

				$settings = apply_filters( 'giftcard_processing_options', $options );

			} elseif ( $current_section == 'giftcard_options' ) {
				$options = array(
					array(
						'title' => __( 'Gift card ', 'GIFTCARD' ),
						'type'  => 'title',
						'id'    => 'giftcard_options_title'
					),
					array(
						'name'     => __( 'Default expire time span', 'GIFTCARD' ),
						'desc'     => __( 'If you set this to 30 , the gift card will expire after 30 day from created time. Leave blank or 0 if you do not want to use it', 'GIFTCARD' ),
						'id'       => 'magenest_giftcard_timespan',
						'default'  => '0',
						'type'     => 'text',
						'desc_tip' => true,
					),

					array(
						'name'     => __( 'Default Gift card code pattern', 'GIFTCARD' ),
						'desc'     => __( 'You can use [A4] for 4 random characters, use [N5] for 5 random digit and so on', 'GIFTCARD' ),
						'id'       => 'magenest_giftcard_code_pattern',
						'default'  => 'Magenest[A2]xyz[N4]',
						'type'     => 'text',
						'desc_tip' => true,
					),

					array( 'type' => 'sectionend', 'id' => 'giftcard_generate_option' )
				);

				$settings = apply_filters( 'giftcard_options', $options );
			} elseif ( $current_section == 'giftcard_sendfriend_options' ) {

			} elseif ( $current_section == 'pdf_setting' ) {
			} elseif ( $current_section == 'default_email_setting' ) {
				$options = array(
					array(
						'title' => __( 'Default Email Template', 'GIFTCARD' ),
						'type'  => 'title',
						'id'    => 'giftcard_default_email_template'
					),

					array(
						'name'     => __( 'Subject', 'GIFTCARD' ),
						'desc'     => __( '', 'GIFTCARD' ),
						'id'       => 'magenest_giftcard_to_subject',
						'type'     => 'text',
						'desc_tip' => true,
					),
					array(
						'name'     => __( 'Content', 'GIFTCARD' ),
						'desc'     => __( '', 'GIFTCARD' ),
						'id'       => 'magenest_giftcard_to_content',
						'type'     => 'text',
						'desc_tip' => true,
					),
                    array(
                        'name' => __( 'Attach pdf gift card', 'GIFTCARD' ),
                        'id'   => 'magenest_giftcard_to_pdf',
                        'type' => 'checkbox',
                    ),
                    array(
                        'name' => __( 'BCC Gift card to the sender', 'GIFTCARD' ),
                        'id'   => 'giftcard_bcc_sender',
                        'type' => 'checkbox',
                    ),

					array( 'type' => 'sectionend', 'id' => 'default_email_setting' )
				);

				$settings = apply_filters( 'default_email_setting', $options );
			}

			return $settings;
		}

		public function display_file( $value )
		{
			$fileValue = $value;
			$imagePath = WC_Admin_Settings::get_option( $value['id'] );
			$nameImage = get_option( 'magenestgc_pdf_background' );
			?>
			<tr>
                <th>
                    <label> <?php echo __( 'File name of pdf background ( jpg,png file)' ,GIFTCARD_TEXT_DOMAIN) ?></label>
                </th>
                <td>
                    <input type="file" name="magenestgc_pdf_background"
                           value="<?= isset( $nameImage ) ? $nameImage : ''; ?>"/>
	                <?php
	                if ( $nameImage ) {
		                ?>
		                <?php
		                echo __( '<br/><b>File you chosen:</b>', GIFTCARD_TEXT_DOMAIN );
		                ?>
		                <span class=giftcardbackground">
								<?php
								echo $nameImage
								?>
							</span>
		                <?php
	                }
	                ?>
                </td>
            </tr>
			<?php

		}


	}

endif;

return new Magenest_Giftcard_Settings();
