<?php

/**
 * @package jolifaq
 */
namespace WPJoli\JoliFAQ\Engine;

use DateTime;
use Exception;
use WPJoli\JoliFAQ\Controllers\SettingsController;
// use WPJoli\JoliFAQ\Engine\ContentProcessing;
class JoliFAQBuilder {
    protected $faq_group;

    protected $faqs;

    protected $options;

    protected $schema;

    public function __construct( $faq_group_id = null, $options = null ) {
        if ( isset( $faq_group_id ) && $faq_group_id !== null ) {
            $is_faq_group = get_post_type( $faq_group_id ) == JFAQ()::POST_TYPE_GROUP;
            if ( $is_faq_group ) {
                $this->initData( $faq_group_id, $options );
            } else {
                throw new Exception('Invalid FAQ Group');
            }
        }
        // if ( $options ){
        $this->setOptions( $options );
        // }
    }

    /**
     * Initializes data
     *
     * @param [type] $faq_group_id
     * @param [type] $options
     * @return void
     */
    private function initData( $faq_group_id, $options = null ) {
        $this->faq_group = JoliFAQGroup::getFAQGroup( $faq_group_id );
        if ( !$this->faq_group ) {
            return false;
        }
        if ( isset( $this->faq_group['isDisabled'] ) && $this->faq_group['isDisabled'] ) {
            return false;
        }
        $args = [
            'post__in' => $this->faq_group['faqs'],
        ];
        $this->faqs = JoliFAQ::getFAQs( $args, false );
    }

    /**
     * Prints out JSON-LD FAQPage Schema
     *
     * @return void
     */
    public function outputSchema() {
        echo $this->schema;
    }

    /**
     * FAQ rendering from user settings
     *
     * @param [type] $faqs_override
     * @return void
     */
    public function buildJoliFAQSEO( $faqs_override = null ) {
        static $count = 0;
        $count++;
        $faqs = $this->getFAQs();
        // JFAQ()->log($faqs);
        if ( !$faqs_override && (!$faqs || count( $faqs ) == 0) ) {
            return false;
        }
        // $options = count($options) > 0 ? $options : $this->options;
        $options = $this->options;
        $faqs = $this->sortFAQs( $this->getFAQs() );
        $faqs = apply_filters( 'joli_faq_seo_faqs', $faqs );
        //enqueue style & script only if FAQ is running
        wp_enqueue_style(
            'wpjoli-joli-faq-seo-styles',
            JFAQ()->url( 'assets/public/css/wpjoli-joli-faq-seo.css', JFAQ()::USE_MINIFIED_ASSETS ),
            [],
            JFAQ()::VERSION
        );
        wp_enqueue_script(
            'wpjoli-joli-faq-seo-scripts',
            JFAQ()->url( 'assets/public/js/wpjoli-joli-faq-seo.js', JFAQ()::USE_MINIFIED_ASSETS ),
            [],
            JFAQ()::VERSION,
            true
        );
        //Custom styles if not disabled
        $custom_styles = ( $this->options['disable_custom_styles'] == 1 ? '' : $this->buildCustomStyles() );
        $schema_str = '';
        //add script to head or bode for schema.org
        if ( $options['schema_faq'] ) {
            $se = false;
            $schema = new SchemaFAQ($faqs, $se);
            $schema_str = $schema->makeSchema();
            // $this->schema();
            // add_action('joli_faq_seo_after_faq',  [$this, 'outputSchema']);
            // echo($this->schema());
            // wp_add_inline_script( 'wpjoli-joli-faq-seo-scripts', $this->schema() );
        }
        //make the questions uppercase
        if ( $options['questions_uppercase'] ) {
            add_filter( 'joli_faq_seo_faq_question', function ( $title ) {
                return strtoupper( $title );
            } );
        }
        //Default options
        $settings = JFAQ()->requestService( SettingsController::class );
        $current_options = $settings->getOptions();
        //Only gets the options different from defaults
        $current_options_no_default = array_filter( $current_options, function ( $value, $key ) use($options) {
            $addr = explode( '.', $key );
            //options from the current object, they can be set by shortcode or the block
            $index = str_replace( '-', '_', $addr[1] );
            $override_option = $options[$index];
            //if the override option is different from the global settings, we take it.
            $option_val = ( $override_option != $value ? $override_option : $value );
            $default_val = jfaq_get_option_default( $addr[1], $addr[0] );
            return $option_val !== null && $option_val != $default_val;
        }, ARRAY_FILTER_USE_BOTH );
        $js_options = $this->mapSettingsToJs( $current_options_no_default );
        $script_data = [
            'options' => apply_filters( 'joli_faq_seo_front_options', $js_options ),
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'strings' => [
                'link_copied' => __( 'Link copied !', 'joli_faq_seo' ),
            ],
        ];
        //Data to pass on to the view
        $data = [
            'faq_group'     => apply_filters( 'joli_faq_seo_faq_group', $this->getFAQGroup() ),
            'faqs'          => ( $faqs_override ? $faqs_override : $faqs ),
            'options'       => $options,
            'custom_styles' => $custom_styles,
            'data'          => [
                'group_classes' => implode( ' ', apply_filters( 'joli_faq_seo_group_classes', $this->getGroupClasses() ) ),
                'toggle_html'   => apply_filters( 'joli_faq_seo_toggle_html', '<div class="jfaq--toggle-wrap"><div class="jfaq--toggle"></div></div>' ),
            ],
            'data_pro'      => [],
        ];
        // if (jfaq_xy()->can_use_premium_code__premium_only()) {
        //     //PREMIUM
        //     $data['data']['faq_link'] = apply_filters('joli_faq_seo_faq_link_html', '<div class="jfaq--faq-link-wrap"><i class="gg-link"></i></div>');
        // }
        static $times = 1;
        //only sets once if multiple shortcodes
        if ( $times == 1 ) {
            wp_localize_script( 'wpjoli-joli-faq-seo-scripts', 'JFAQ', $script_data );
            $data['data']['schema'] = $schema_str;
        }
        $times++;
        $faq = JFAQ()->render( [
            'public' => 'jolifaqseo',
        ], $data, true );
        // return jfaq_minify_html($faq);
        return $faq;
    }

    public function setOptions( $options = null, $faq_group_id = null ) {
        $all_options = JFAQ()->requestService( \WPJoli\JoliFAQ\Controllers\SettingsController::class )->getOptions();
        foreach ( $all_options as $key => $value ) {
            $option = explode( '.', $key );
            $option_cat = $option[0];
            //ex: search-highlight-color
            $option_id = str_replace( '-', '_', $option[1] );
            //ex: => search_highlight_color
            $this->options[$option_id] = jfaq_get_option( $option[1], $option_cat, $options );
        }
    }

    private function buildCustomStyles() {
        //optios related to a css var
        $key_to_cssvar_matches = [
            'toggle_color'                      => '--jfaq-toggle-color',
            'toggle_border_color'               => '--jfaq-toggle-border-color',
            'toggle_background_color'           => '--jfaq-toggle-bg-color',
            'toggle_opened_color'               => '--jfaq-toggle-color-alt',
            'toggle_opened_border_color'        => '--jfaq-toggle-border-color-alt',
            'toggle_opened_background_color'    => '--jfaq-toggle-bg-color-alt',
            'questions_color'                   => '--jfaq-question-color',
            'questions_background_color'        => '--jfaq-question-bg-color',
            'questions_font_size'               => '--jfaq-question-font-size',
            'questions_font_weight'             => '--jfaq-question-font-weight',
            'questions_opened_color'            => '--jfaq-question-color-alt',
            'questions_opened_background_color' => '--jfaq-question-bg-color-alt',
            'questions_opened_font_size'        => '--jfaq-question-font-size-alt',
            'questions_opened_font_weight'      => '--jfaq-question-font-weight-alt',
            'answer_container_background_color' => '--jfaq-answer-container-bg-color',
            'answer_background_color'           => '--jfaq-answer-bg-color',
            'answer_color'                      => '--jfaq-answer-color',
            'answer_font_size'                  => '--jfaq-answer-font-size',
        ];
        $styles = [];
        foreach ( $key_to_cssvar_matches as $key => $cssvar ) {
            $option = isset_or_null( $this->options[$key] );
            if ( $option && $option !== 'none' ) {
                //unit options:ex 12|px => 12px
                if ( strpos( $option, '|' ) >= 0 ) {
                    $option = str_replace( '|', '', $option );
                    //replaces the % litteral to actual symbol
                    $option = str_replace( 'percent', '%', $option );
                }
                //push a single style
                $styles[] = sprintf( '%s: %s !important;', $cssvar, $option );
            }
        }
        if ( count( $styles ) ) {
            $css_styles = implode( '', $styles );
            return sprintf( '<style>.jfaq-wrap .%s{%s}</style>', $this->getGroupIDClass(), $css_styles );
        }
        return '';
    }

    public function getGroupIDClass() {
        if ( is_array( $this->faq_group ) ) {
            return '--jfaq-group-' . jfaq_salt( $this->faq_group['ID'] );
        }
        return '--jfaq-group-xxx';
    }

    private function getGroupClasses() {
        // $faqs = $this->faqs;
        $options = $this->options;
        $classes = [];
        //accordion
        if ( isset( $options['accordion'] ) && $options['accordion'] && !isset_or_null( $options['open_all_questions'] ) ) {
            $classes[] = '--jfaq-accordion';
        }
        //WARNING FIX: if isset $options && $options....
        if ( isset( $options['flat_layout'] ) && $options['flat_layout'] ) {
            $classes[] = '--jfaq-flat';
        } else {
            //theme
            $classes[] = '--jfaq-' . $options['theme'];
            if ( isset( $options['show_toggle'] ) && $options['show_toggle'] ) {
                //toggle icon style
                $classes[] = '--jfaq-' . $options['toggle_button_icon_style'];
                //toggle size
                if ( isset( $options['toggle_size'] ) && $options['toggle_size'] ) {
                    $classes[] = '--toggle-size-' . $options['toggle_size'];
                }
                //toggle rotate 180
                if ( isset( $options['toggle_rotate_180'] ) && $options['toggle_rotate_180'] ) {
                    $classes[] = '--toggle-180';
                }
            }
        }
        //Group ID (leave a the end to allow custom styles to override theme)
        $classes[] = $this->getGroupIDClass();
        return $classes;
    }

    /**
     * Sort FAQs according to the user settings
     *
     * @param [type] $faqs
     * @return void
     */
    private function sortFAQs( $faqs ) {
        $options = $this->options;
        $sort_order = ( isset( $options['sort_order'] ) ? $options['sort_order'] : null );
        if ( !isset( $options['sort_by'] ) ) {
            return $faqs;
        }
        if ( jfaq_xy()->can_use_premium_code__premium_only() && $options['sort_by'] == 'user-engagement' ) {
            $order = $this->orderFAQIDsByViews__premium_only( $sort_order );
            $ordered_faqs = $this->sortFAQsByOrder__premium_only( $faqs, $order );
            return $ordered_faqs;
        } else {
            if ( isset( $options['sort_by'] ) && $options['sort_by'] == 'alphabetical' ) {
                $ordered_faqs = $this->orderFAQsByAlphabet( $faqs, $sort_order );
                return $ordered_faqs;
            } else {
                if ( isset( $options['sort_by'] ) && $options['sort_by'] == 'creation-date' ) {
                    $ordered_faqs = $this->orderFAQsByDate( $faqs, $sort_order );
                    return $ordered_faqs;
                }
            }
        }
        //position from the editor by default
        return $faqs;
    }

    private function orderFAQsByDate( $faqs, $sort_order ) {
        //order by view count
        if ( !$faqs ) {
            return false;
        }
        usort( $faqs, function ( $a, $b ) {
            $ad = new DateTime($a['date']);
            $bd = new DateTime($b['date']);
            if ( $ad == $bd ) {
                return 0;
            }
            return ( $ad < $bd ? -1 : 1 );
        } );
        if ( $sort_order == 'desc' ) {
            $faqs = array_reverse( $faqs, true );
        }
        return $faqs;
    }

    private function orderFAQsByAlphabet( $faqs, $sort_order ) {
        //order by view count
        if ( !$faqs ) {
            return false;
        }
        uasort( $faqs, function ( $a, $b ) {
            return strcmp( strtolower( $a['title'] ), strtolower( $b['title'] ) );
        } );
        if ( $sort_order == 'desc' ) {
            $faqs = array_reverse( $faqs, true );
        }
        return $faqs;
    }

    private function getFAQs() {
        $faqs = $this->faqs;
        $faq_group = $this->faq_group;
        $options = $this->options;
        if ( isset( $options['sort_by'] ) && $options['sort_by'] == 'alphabetical' ) {
            // ksort($faqs)
        }
        return $faqs;
    }

    private function getFAQGroup() {
        if ( !$this->faq_group ) {
            return [];
        }
        // if (isset($faq_group['isDisabled']) && $faq_group['isDisabled'] == true ) {
        //     return [];
        // }
        return $this->faq_group;
    }

    private function mapSettingsToJs( $settings ) {
        $js_options = [];
        //maps the local settings with the js settings option names
        //the js keys must correspond to the args[key] value
        foreach ( $settings as $key => $value ) {
            $addr = explode( '.', $key );
            $option_args = jfaq_get_option_args( $addr[1], $addr[0] );
            if ( !isset( $option_args['key'] ) ) {
                continue;
            }
            $js_key = $option_args['key'];
            if ( isset( $option_args['var_type'] ) ) {
                if ( $option_args['var_type'] == 'bool' ) {
                    $value = boolval( $value );
                } else {
                    if ( $option_args['var_type'] == 'int' ) {
                        $value = intval( $value );
                    } else {
                        if ( $option_args['var_type'] == 'float' ) {
                            $value = floatval( $value );
                        }
                    }
                }
            }
            $js_options[$js_key] = $value;
        }
        return $js_options;
    }

}
