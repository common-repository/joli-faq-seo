<?php

use WPJoli\JoliFAQ\Application;
/**
 * @package jolifaq
 */
/*
 * Plugin Name: Joli FAQ SEO - WordPress FAQ Plugin
 * Plugin URI: https://wpjoli.com/joli-faq-seo
 * Description: A FAQ plugin for WordPress that use schema.org json markup & will enhance your SEO. Easy to use with our one-page FAQ Editor.
 * Version: 1.3.5
 * Author: WPJoli
 * Author URI: https://wpjoli.com
 * License: GPLv2 or later
 * Text Domain: joli-faq-seo
 * Domain Path: /languages
 * 
 */
defined( 'ABSPATH' ) or die( 'Wrong path bro!' );
if ( function_exists( 'jfaq_xy' ) ) {
    jfaq_xy()->set_basename( false, __FILE__ );
} else {
    if ( !function_exists( 'jfaq_xy' ) ) {
        // Create a helper function for easy SDK access.
        function jfaq_xy() {
            global $jfaq_xy;
            if ( !isset( $jfaq_xy ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/includes/fs/start.php';
                $jfaq_xy = fs_dynamic_init( array(
                    'id'             => '5451',
                    'slug'           => 'joli-faq-seo',
                    'premium_slug'   => 'joli-faq-seo-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_5ba26385532df83b00c33f5b74a5a',
                    'is_premium'     => false,
                    'premium_suffix' => 'Pro',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                        'slug'       => 'joli_faq_seo_faq_editor',
                        'first-path' => 'admin.php?page=joli_faq_seo_settings',
                        'account'    => false,
                        'support'    => false,
                    ),
                    'is_live'        => true,
                ) );
            }
            return $jfaq_xy;
        }

        // Init Freemius.
        jfaq_xy();
        // Signal that SDK was initiated.
        do_action( 'jfaq_xy_loaded' );
    }
    require_once dirname( __FILE__ ) . '/helpers.php';
    require_once dirname( __FILE__ ) . '/fs-helpers.php';
    require_once dirname( __FILE__ ) . '/autoload.php';
    $app = new Application();
    $app->run();
    register_activation_hook( __FILE__, [$app, 'activate'] );
    register_deactivation_hook( __FILE__, [$app, 'deactivate'] );
}