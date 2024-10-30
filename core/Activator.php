<?php

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    jolifaq
 */
namespace WPJoli\JoliFAQ;

class Activator {
    public function activate() {
        //app settings
        $settings = JFAQ()->requestService( \WPJoli\JoliFAQ\Controllers\SettingsController::class );
        $settings->setupSettings();
    }

    public function deactivate() {
    }

}
