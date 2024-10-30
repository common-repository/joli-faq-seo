<?php

use WPJoli\JoliFAQ\Controllers\SettingsController;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Returns an instance of the applciation
 * @return WPJoli\JoliFAQ\Application
 */
function JFAQ() {
    return WPJoli\JoliFAQ\Application::instance();
}

if ( !function_exists( 'jlog' ) ) {
    function jlog(  $message, $level = 'info', $logfile = null  ) {
        JFAQ()->log( $message, $level, $logfile );
    }

}
if ( !function_exists( 'pre' ) ) {
    function pre(  $data  ) {
        echo '<pre>';
        print_r( $data );
        echo '</pre>';
    }

}
/**
 * pre only if is super admin
 * @param type $data
 */
if ( !function_exists( 'apre' ) ) {
    function apre(  $data  ) {
        if ( is_super_admin() ) {
            echo '<pre>';
            print_r( $data );
            echo '</pre>';
        }
    }

}
if ( !function_exists( 'jfaq_pro_only' ) ) {
    function jfaq_pro_only() {
        return '<span class="joli-pro-only">' . __( ' (Pro only)', 'joli_faq_seo' ) . '</span>';
    }

}
/**
 * Converts a name into a slug friendly 
 * @param type $name
 * @return type
 */
if ( !function_exists( 'jfaq_slugify' ) ) {
    function jfaq_slugify(  $string, $delimiter = '-'  ) {
        $oldLocale = setlocale( LC_ALL, '0' );
        setlocale( LC_ALL, 'en_US.UTF-8' );
        $clean = iconv( 'UTF-8', 'ASCII//TRANSLIT', $string );
        $clean = preg_replace( "/[^a-zA-Z0-9\\/_|+ -]/", '', $clean );
        $clean = strtolower( $clean );
        $clean = preg_replace( "/[\\/_|+ -]+/", $delimiter, $clean );
        $clean = trim( $clean, $delimiter );
        setlocale( LC_ALL, $oldLocale );
        return $clean;
    }

}
if ( !function_exists( 'arrayFind' ) ) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     * @param string $key Comparison key
     * @param mixed $value Value to search
     * @param array $array The array to search from
     * @return array
     */
    function arrayFind(  $value, $key, $array  ) {
        $item = null;
        foreach ( $array as $row ) {
            if ( $row[$key] == $value ) {
                $item = $row;
                break;
            }
        }
        return $item;
    }

}
if ( !function_exists( 'jfaq_get_option' ) ) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     *
     * @param [type] $name
     * @param [type] $section
     * @param [type] $options override global options with an array of options
     * @return void
     */
    function jfaq_get_option(  $name, $section, $options = null  ) {
        $settings = JFAQ()->requestService( SettingsController::class );
        //Modif jfaqs seo, allows to override options with custom array of options
        //will be used in the ajax call to provide options from settings without posting the form
        if ( $options ) {
            return $settings->getOption(
                $name,
                $section,
                false,
                $options
            );
        }
        return $settings->getOption( $name, $section );
    }

}
if ( !function_exists( 'isset_or_null' ) ) {
    /**
     * Returns $var or null if $var is not set
     * $empty_string = true returns an empty string instead of null
     */
    function isset_or_null(  &$var, $empty_string = null  ) {
        return ( isset( $var ) ? $var : (( $empty_string ? '' : null )) );
    }

}
if ( !function_exists( 'jfaq_minify_html' ) ) {
    /**
     * Removes line breaks and excessive empty spaces from a string
     */
    function jfaq_minify_html(  $html  ) {
        // return preg_replace('/\v(?:[\v\h]+)/', '', $string);
        return preg_replace( '#(?ix)(?>[^\\S ]\\s*|\\s{2,})(?=(?:(?:[^<]++|<(?!/?(?:textarea|pre)\\b))*+)(?:<(?>textarea|pre)\\b|\\z))#', '', $html );
    }

}
if ( !function_exists( 'jfaq_is_front' ) ) {
    function jfaq_is_front() {
        if ( function_exists( 'wp_doing_ajax' ) ) {
            return !is_admin() && !wp_doing_ajax();
        } else {
            return !is_admin();
        }
    }

}
// if (!function_exists('saveHTMLNoWrapping')) {
//     function saveHTMLNoWrapping( $html ){
//         return substr(trim($html->saveHTML()), 12, -14);
//     }
// }
if ( !function_exists( 'getHostURL' ) ) {
    function getHostURL() {
        $_url = parse_url( site_url() );
        return ( $_url ? urlencode( $_url['host'] ) : false );
    }

}
if ( !function_exists( 'jfaq_css_prop' ) ) {
    /**
     * Returns a css string if the value is set or not null
     *
     * @param [type] $prop
     * @param [type] $value
     * @return void 
     */
    function jfaq_css_prop(  $prop, &$value, $suffix = ''  ) {
        if ( isset( $value ) && $value ) {
            return sprintf(
                '%s: %s%s;',
                $prop,
                $value,
                $suffix
            );
        }
        return '';
    }

}
if ( !function_exists( 'jfaq_get_option_default' ) ) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     */
    function jfaq_get_option_default(  $name, $section  ) {
        $settings = JFAQ()->requestService( SettingsController::class );
        return $settings->getOption( $name, $section, true );
    }

}
if ( !function_exists( 'jfaq_mustache_key' ) ) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     */
    function jfaq_mustache_key(  $string  ) {
        return '{{' . $string . '}}';
    }

}
if ( !function_exists( 'jfaq_get_option_args' ) ) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     */
    function jfaq_get_option_args(  $name, $section, $key = null  ) {
        $settings = JFAQ()->requestService( SettingsController::class );
        return $settings->getOptionArgs( $name, $section, $key );
    }

}
if ( !function_exists( 'jfaq_wc_product' ) ) {
    /**
     * Returns the first sub_array from an array matching $key and $value
     */
    function jfaq_wc_product() {
        global $product;
        if ( is_object( $product ) ) {
            return $product;
        } else {
            return wc_get_product( get_the_ID() );
        }
    }

}
if ( !function_exists( 'jfaq_salt' ) ) {
    /**
     * A very basic way to prevent IDs from showing
     */
    function jfaq_salt(  $number  ) {
        $_number = ($number + $number / 2) * 16;
        return dechex( $_number );
    }

}
if ( !function_exists( 'jfaq_unsalt' ) ) {
    /**
     * Get the ID back from salting
     */
    function jfaq_unsalt(  $string  ) {
        if ( !$string ) {
            return null;
        }
        $_number = hexdec( $string );
        $number = $_number / 16 / 3 * 2;
        return $number;
    }

}
if ( !function_exists( 'jfaq_decode_faq_input' ) ) {
    /**
     * Get the ID back from salting
     */
    function jfaq_decode_faq_input(  $input  ) {
        // return $input;
        return htmlspecialchars_decode( $input, ENT_QUOTES );
    }

}
if ( !function_exists( 'jfaq_sanitize_faq_args' ) ) {
    /**
     * Get the ID back from salting
     */
    function jfaq_sanitize_faq_args(  $args  ) {
        if ( !$args ) {
            return null;
        }
        $allowed_values = [
            'emoji'     => 'sanitize_text_field',
            '_jfaq_gut' => 'intval',
        ];
        return jfaq_sanitize_args( $args, $allowed_values );
    }

}
if ( !function_exists( 'jfaq_sanitize_faq_group_args' ) ) {
    /**
     * Get the ID back from salting
     */
    function jfaq_sanitize_faq_group_args(  $args  ) {
        $allowed_values = [
            'position' => 'intval',
            'faqs'     => 'is_array',
        ];
        return jfaq_sanitize_args( $args, $allowed_values );
    }

}
if ( !function_exists( 'jfaq_sanitize_args' ) ) {
    /**
     * Sanitizes $args using $allowed_values as a model of sanitation $key => sanitation_function
     *
     * @param [array] $args input 
     * @param [array] $allowed_values (key => function to call back for sanitation)
     * @return void
     */
    function jfaq_sanitize_args(  $args, $allowed_values  ) {
        if ( !is_array( $args ) || !is_array( $allowed_values ) ) {
            return null;
        }
        $output = [];
        foreach ( $args as $key => $value ) {
            if ( !array_key_exists( $key, $allowed_values ) ) {
                continue;
            }
            //sanitizes valid key with its callback function
            $output[$key] = call_user_func( $allowed_values[$key], $value );
        }
        return $output;
    }

}
if ( !function_exists( 'jfaq_sca' ) ) {
    function jfaq_sca(  $a  ) {
        return array_filter( $a, function ( $c ) {
            return $c == 'id';
        }, ARRAY_FILTER_USE_KEY );
    }

}
if ( !function_exists( 'jfaq_meta_key' ) ) {
    /**
     * Prefixes meta key
     *
     * @param [string] $key
     * @param boolean $private prefixes the key with an underscore to mark it as private within wordpress
     * @return string
     * @since 1.3.0
     */
    function jfaq_meta_key(  $key, $private = true  ) {
        if ( !$key ) {
            return;
        }
        $prefix = ( $private ? '_' : '' );
        return $prefix . 'jfaq_' . $key;
    }

}
if ( !function_exists( 'jfaq_is_gutenberg_editor' ) ) {
    function jfaq_is_gutenberg_editor() {
        if ( !is_admin() ) {
            return;
        }
        if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
            return true;
        }
        if ( function_exists( 'get_current_screen' ) ) {
            $current_screen = get_current_screen();
            if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
                return true;
            }
        }
        return false;
    }

}
/**
 * Check if WooCommerce is activated
 */
// if (!function_exists('jfaq_is_woocommerce_activated')) {
//     function jfaq_is_woocommerce_activated()
//     {
//         // if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
//         if (class_exists(WooCommerce::class)) {
//             JFAQ()->log('WOO');
//             return true;
//         } else {
//             JFAQ()->log('NO WOO');
//             return false;
//         }
//     }
// }