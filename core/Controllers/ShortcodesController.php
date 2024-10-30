<?php

/**
 * @package jolifaq
 */
namespace WPJoli\JoliFAQ\Controllers;

use Exception;
use WPJoli\JoliFAQ\Application;
use WPJoli\JoliFAQ\Engine\JoliFAQBuilder;
class ShortcodesController {
    protected $user_settings;

    public function registerShortcodes() {
        add_shortcode( apply_filters( 'joli_faq_seo_shortcode_tag', Application::DOMAIN ), [$this, 'joliFAQShortcode'] );
    }

    /**
     * Processes 'joli_faq' shortcode
     * @param type $atts
     * @return type
     */
    public function joliFAQShortcode( $atts = [] ) {
        //All plugin's options
        $settings = JFAQ()->requestService( SettingsController::class );
        //current user settings
        $this->user_settings = $settings->getOptions();
        $defaults = [
            'id' => null,
        ];
        $current_user_settings = $this->getFAQGroupShortcodeDefaults();
        $shortcode_defaults = array_merge( $defaults, $current_user_settings );
        //WP shortcode function
        $atts = shortcode_atts( 
            $shortcode_defaults,
            //default values
            jfaq_sca( $atts ),
            Application::DOMAIN
         );
        $group_id = $atts['id'];
        // JFAQ()->log($atts);
        array_shift( $atts );
        //removes the id
        $args = $this->normalizeFAQGroupShortcodeOptions( $atts );
        // JFAQ()->log($args);
        $can_proceed = false;
        try {
            //Instantiate a new FAQ Builder with shortcode options
            $builder = new JoliFAQBuilder($group_id, $args);
            // JFAQ()->log($builder);
            $can_proceed = true;
        } catch ( Exception $e ) {
            $shortcode = null;
        }
        if ( $can_proceed === true ) {
            $shortcode = $builder->buildJoliFAQSEO();
            // JFAQ()->log($shortcode);
        }
        return $shortcode;
    }

    /**
     * Normalize shortcode attr for our array of settings
     * EX: Changes sort_order => sorting.sort-order
     *
     * @return void
     */
    public function normalizeFAQGroupShortcodeOptions( $atts ) {
        $normalized = [];
        $current_user_settings = $this->user_settings;
        foreach ( $current_user_settings as $key => $value ) {
            $option = explode( '.', $key );
            $option_id = str_replace( '-', '_', $option[1] );
            //ex: =>
            $normalized[$key] = $atts[$option_id];
        }
        return $normalized;
    }

    /**
     * Generates defaults shortcode atts from options
     *
     * @return void
     */
    public function getFAQGroupShortcodeDefaults() {
        $shortcode_defaults = [];
        $current_user_settings = $this->user_settings;
        foreach ( $current_user_settings as $key => $value ) {
            $option = explode( '.', $key );
            $option_id = str_replace( '-', '_', $option[1] );
            //ex: =>
            $shortcode_defaults[$option_id] = $value;
        }
        return $shortcode_defaults;
    }

}
