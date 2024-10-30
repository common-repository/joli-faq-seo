<?php

/**
 * @package jolifaq
 */

namespace WPJoli\JoliFAQ\Engine;

// use WPJoli\JoliFAQ\Engine\ContentProcessing;

class JoliFAQProcessor
{

    public function processAnswer($answer)
    {
        // remove_filter( 'the_content', 'wpautop' );
        // $content = apply_filters('the_content', $answer);
        $content = do_shortcode( $answer );
        // add_filter( 'the_content', 'wpautop' );
        return $content;
    }
}
