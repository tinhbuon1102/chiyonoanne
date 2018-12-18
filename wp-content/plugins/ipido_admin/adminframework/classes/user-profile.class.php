<?php
class CSFramework_User_Profile extends CSFramework_Abstract{

	/**
	 * options
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * type
	 *
	 * @var string
	 */
	protected $type = 'user_profile';

	/**
	 * CSFramework_User_Profile constructor.
	 *
	 * @param array $options
	 */
	public function __construct( $options = array() ) {
		$this->init( $options );
	}

	/**
	 * @param $options
	 */
	public function init( $options ) {
		$this->options = $options;
		add_action( 'load-profile.php', array( &$this, 'map_user_info' ) );
		add_action( 'load-user-edit.php', array( &$this, 'map_user_info' ) );
		add_action( 'show_user_profile', array( &$this, 'custom_user_profile_fields' ), 10, 1 );
		add_action( 'edit_user_profile', array( &$this, 'custom_user_profile_fields' ), 10, 1 );
		add_action( 'user_new_form', array( &$this, 'custom_user_profile_fields' ), 10, 1 );
		add_action( 'personal_options_update', array( $this, 'save_customer_meta_fields' ), 10, 2 );
		add_action( 'edit_user_profile_update', array( $this, 'save_customer_meta_fields' ), 10, 2 );
		$this->addAction( 'admin_enqueue_scripts', 'load_style_script' );
	}

	public function map_user_info() {
		foreach ( $this->options as $optionid => $option ) {
			$this->options[ $optionid ] = $this->map_error_id( $option, $option['id'] );
		}
	}

	public function load_style_script() {
		global $pagenow;
		if ( 'profile.php' === $pagenow || 'user-edit.php' === $pagenow || 'user-new.php' === $pagenow) {
			csf_assets()->render_framework_style_scripts();
		}
	}

	/**
	 * @param null $user_id
	 */
	public function custom_user_profile_fields( $user_id = null ) {
		$user_id = ( is_object( $user_id ) ) ? $user_id->ID : $user_id;
		foreach ( $this->options as $option_id => $option ) {
			$csf_errors           = get_transient( '_csf_umeta_' . $option['id'] );
			$csf_errors['errors'] = isset( $csf_errors['errors'] ) ? $csf_errors['errors'] : array();
			csf_add_errors( $csf_errors['errors'] );
			$values = get_user_meta( $user_id, $option['id'], true );
			$values = ( ! is_array( $values ) ) ? array() : $values;
			$title  = ( isset( $option['title'] ) && ! empty( $option['title'] ) ) ? '<h2>' . $option['title'] . '</h2>' : '';
			echo $title;
			echo '<div class="csf-framework csf-user-profile">';
			if ( isset( $option['style'] ) && 'modern' === $option['style'] ) {
				echo '<div class="csf-body">';
			}

			foreach ( $option['fields'] as $field ) {
				$value = $this->get_field_values( $field, $values );
				echo csf_add_element( $field, $value, $option['id'] );
			}

			if ( isset( $option['style'] ) && 'modern' === $option['style'] ) {
				echo '</div>';
			}
			echo '</div>';
		}
	}

	/**
	 * @param $user_id
	 */
	public function save_customer_meta_fields( $user_id ) {
		$save_handler = new CSFramework_DB_Save_Handler;
		foreach ( $this->options as $options ) {
			$posted_data = csf_get_var( $options['id'] );
			if ( isset( $options['fields'] ) ) {
				$posted_data = $save_handler->general_save_handler( $posted_data, $options );
			}

			update_user_meta( $user_id, $options['id'], $posted_data );
			set_transient( '_csf_umeta_' . $options['id'], array( 'errors' => $save_handler->get_errors() ), 10 );
		}
	}
}
