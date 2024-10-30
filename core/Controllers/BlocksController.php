<?php

/**
 * @package jolifaq
 */
namespace WPJoli\JoliFAQ\Controllers;

use WPJoli\JoliFAQ\Controllers\ShortcodesController;
class BlocksController {
    public function registerBlocks() {
        if ( !function_exists( 'register_block_type' ) ) {
            return;
        }
        $app = JFAQ();
        $asset_file = (include JFAQ()->path( 'blocks/joli-faq-group/index.asset.php' ));
        wp_enqueue_style(
            'wpjoli-joli-faq-seo-styles',
            JFAQ()->url( 'assets/public/css/wpjoli-joli-faq-seo.css', $app::USE_MINIFIED_ASSETS ),
            [],
            JFAQ()::VERSION
        );
        wp_register_script(
            'joli-faq-group-block',
            JFAQ()->url( 'blocks/joli-faq-group/index.js' ),
            $asset_file['dependencies'],
            $asset_file['version']
        );
        $ret = register_block_type( JFAQ()->path( 'blocks/joli-faq-group' ), [
            'editor_script'   => 'joli-faq-group-block',
            'render_callback' => [$this, 'joliFaqGroupRenderCallback'],
        ] );
    }

    public function joliFaqGroupRenderCallback( $atts ) {
        $scc = JFAQ()->requestService( ShortcodesController::class );
        return $scc->joliFAQShortcode( $atts );
    }

}
