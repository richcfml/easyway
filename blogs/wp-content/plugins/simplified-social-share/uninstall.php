<?php

//if uninstall not called from WordPress, abort
defined( 'WP_UNINSTALL_PLUGIN' ) or die();

//get LoginRadius options
$loginradius_share_settings = get_option( 'LoginRadius_share_settings' );

if ( is_multisite() ) {
    //If it is Multisite, remove LoginRadius Sharing plugin options from all blogs
    global $wpdb;
    $blogs = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
    if ( $blogs ) {
        foreach ( $blogs as $blog ) {
            switch_to_blog( $blog['blog_id'] );
            delete_option( 'LoginRadius_share_settings' );
        }
        restore_current_blog();
    }
} else {
    // Delete options from Single Site
    delete_option( 'LoginRadius_share_settings' );
}
