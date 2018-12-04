<?php

class zoa_Widget_recent_posts_with_thumbnail extends WP_Widget {
    function __construct() {
        $widget_ops = array(
            'description' => esc_html__('List recent posts with thubmnail', 'zoa'),
            'classname' => 'widget_recent_posts_thumbnail'
        );
        parent::__construct( false, esc_html__( 'Recent posts with thumbnail', 'zoa'), $widget_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title  = $before_title . $instance['title'] . $after_title;
        $number = $instance['number'];

        $filepath = get_template_directory().'/inc/widgets/recent-posts-with-thumbnail/views/widget.php';
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
                'number' => 3,
                'title'  => esc_html__('Recent Posts', 'zoa')
            )
        );
    ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title', 'zoa'); ?> </label>
            <input type="text" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>"
                   value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat"
                   id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"/>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"><?php esc_html_e( 'Number of posts', 'zoa'); ?></label>
            <input type="number" name="<?php echo esc_attr($this->get_field_name( 'number' )); ?>"
                   value="<?php echo esc_attr( $instance['number'] ); ?>" class="widefat"
                   id="<?php echo esc_attr($this->get_field_id( 'number' )); ?>"/>
        </p>
    <?php
    }
}
