<?php

function jfaq_xy_custom_connect_message(
    $message,
    $user_first_name,
    $plugin_title,
    $user_login,
    $site_link,
    $freemius_link
) {
    return sprintf(
        __( 'Hey %1$s', 'joli_faq_seo' ) . ',<br>' . __( 'Welcome aboard %2$s! We are very pleased to count you as a new user! 
        In order to provide the best experience for our plugins, we like to understand how our users interact.
        If you opt-in, some data about your usage of %2$s will be sent to %5$s. 
        If you skip this, that\'s okay too! %2$s will still work just fine.', 'joli_faq_seo' ),
        $user_first_name,
        '<b>' . $plugin_title . '</b>',
        '<b>' . $user_login . '</b>',
        $site_link,
        $freemius_link
    );
}

jfaq_xy()->add_filter(
    'connect_message',
    'jfaq_xy_custom_connect_message',
    10,
    6
);
function jfaq_fs_uninstall_cleanup() {
    // delete_option( 'joli_faq_settings' );
    delete_option( JFAQ()::SLUG . '_rating_time' );
    delete_option( JFAQ()::SLUG . '_gopro_time' );
}

jfaq_xy()->add_action( 'after_uninstall', 'jfaq_fs_uninstall_cleanup' );
if ( !function_exists( 'fs_file' ) ) {
    function fs_file(  $file  ) {
        return $file;
    }

}
if ( !function_exists( 'jfaq_fs_custom_icon' ) ) {
    function jfaq_fs_custom_icon() {
        return dirname( __FILE__ ) . '/assets/icon-256x256.png';
    }

    jfaq_xy()->add_filter( 'plugin_icon', 'jfaq_fs_custom_icon' );
}