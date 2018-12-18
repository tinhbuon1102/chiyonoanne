<?php

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