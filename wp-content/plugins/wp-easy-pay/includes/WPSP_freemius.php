<?php

// Create a helper function for easy SDK access.
if ( !function_exists( 'wepp_fs' ) ) {
    function wepp_fs()
    {
        global  $wepp_fs ;
        
        if ( !isset( $wepp_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $wepp_fs = fs_dynamic_init( array(
                'id'             => '1920',
                'slug'           => 'wp-easy-pay',
                'type'           => 'plugin',
                'public_key'     => 'pk_4c854593bf607fd795264061bbf57',
                'is_premium'     => false,
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'       => 'edit.php?post_type=wp-easy-pay-pro',
                'first-path' => 'edit.php?post_type=wpep-button&page=wpep-settings',
                'support'    => false,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $wepp_fs;
    }

}
// Init Freemius.
wepp_fs();
// Signal that SDK was initiated.
do_action( 'wepp_fs_loaded' );
if ( !function_exists( 'wepp_fs_custom_connect_message_on_update' ) ) {
    function wepp_fs_custom_connect_message_on_update(
        $message,
        $user_first_name,
        $plugin_title,
        $user_login,
        $site_link,
        $freemius_link
    )
    {
        return sprintf(
            __( 'Hey %1$s' ) . ',<br>' . __( 'Please help us improve %2$s! If you opt-in, some data about your usage of %2$s will be sent to %5$s. If you skip this, that\'s okay! %2$s will still work just fine.', 'wp-easy-pay' ),
            $user_first_name,
            '<b>' . $plugin_title . '</b>',
            '<b>' . $user_login . '</b>',
            $site_link,
            $freemius_link
        );
    }

}
wepp_fs()->add_filter(
    'connect_message_on_update',
    'wepp_fs_custom_connect_message_on_update',
    10,
    6
);