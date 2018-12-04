<?php

class zoa_Widget_Mailchimp_Subscribe extends WP_Widget {

	/**
	 * @internal
	 */
	function __construct() {
		parent::__construct( false, esc_html__( 'Mailchimp Subscribe Form', 'zoa' ), array() );
	}

	/**
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		extract( $args );

        $form_action = $instance['form_action'];
        $title       = $before_title . $instance['title'] . $after_title;

		$filepath = get_template_directory() . '/inc/widgets/mailchimp-subscribe/views/widget.php';

		if ( file_exists( $filepath ) ) {
			include( $filepath );
		}
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args(
            (array) $instance,
            array(
                'title'       => '',
                'form_action' => '#'
            )
        );
	?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title:', 'zoa' ); ?> </label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>"
                   value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat"
                   id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"/>
        </p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'form_action' )); ?>"><?php esc_html_e( 'Form action:', 'zoa' ); ?> :</label>
			<input type="text" name="<?php echo esc_attr($this->get_field_name( 'form_action' )); ?>"
			       value="<?php echo esc_attr( $instance['form_action'] ); ?>" class="widefat"
			       id="<?php echo esc_attr($this->get_field_id( 'form_action' )); ?>"/>
		</p>
	<?php
	}
}