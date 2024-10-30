<?php

/**
 * @package jolifaq
 */
namespace WPJoli\JoliFAQ\Controllers;

use WPJoli\JoliFAQ\Engine\JoliFAQ;
use WPJoli\JoliFAQ\Engine\JoliFAQGroup;
class FAQEditorController {
    public function enqueueAssets( $hook_suffix ) {
        // $faq_editor_suffix = JFAQ()::DOMAIN . '_page_' . JFAQ()::SLUG . '_faq_editor'; // joli-faq-seo_page_joli_faq_seo_faq_editor
        $faq_editor_suffix = 'toplevel_page_' . JFAQ()::SLUG . '_faq_editor';
        // joli-faq-seo_page_joli_faq_seo_faq_editor
        //enqueues required scripts/styles only for admin page FAQ Editor
        if ( $hook_suffix === $faq_editor_suffix ) {
            wp_enqueue_media();
            wp_enqueue_editor();
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'jquery-ui-droppable' );
            wp_enqueue_style(
                'wpjoli-joli-faq-seo-faq-editor-styles',
                JFAQ()->url( 'assets/admin/css/joli-faq-seo-faq-editor.css', JFAQ()::USE_MINIFIED_ASSETS ),
                [],
                JFAQ()::VERSION
            );
            wp_enqueue_script(
                'handlebarsjs',
                JFAQ()->url( 'vendor/handlebars/handlebars.min-v4.7.8.js' ),
                [],
                '4.7.8',
                true
            );
            wp_enqueue_script(
                'wpjoli-joli-faq-seo-faq-editor-scripts',
                JFAQ()->url( 'assets/admin/js/joli-faq-seo-faq-editor.js', JFAQ()::USE_MINIFIED_ASSETS ),
                ['jquery', 'editor'],
                '',
                true
            );
            $args = null;
            $data = [
                'editor_nonce'  => wp_create_nonce( 'JoliFAQSEOEditor' ),
                'ajaxurl'       => admin_url( 'admin-ajax.php' ),
                'faqs'          => JFAQ()->requestService( JoliFAQ::class )->getFAQs( $args ),
                'faq_groups'    => JFAQ()->requestService( JoliFAQGroup::class )->getFAQGroups( $args ),
                'edit_post_url' => get_admin_url(),
            ];
            $data['pro'] = false;
            wp_localize_script( 'wpjoli-joli-faq-seo-faq-editor-scripts', 'JFAQ', $data );
            //        wp_enqueue_script( 'accordion', $this->plugin_url . '../../wp-content/wp-admin/js/accordion.min.js' );
            // wp_enqueue_style('wp-color-picker');
            // add_filter('script_loader_tag', [$this, 'add_type_attribute'] , 10, 3);
        }
    }

    // public function add_type_attribute($tag, $handle, $src) {
    //     // if not your script, do nothing and return original $tag
    //     if ( 'emoji-button' !== $handle ) {
    //         return $tag;
    //     }
    //     // change the script tag by adding type="module" and return it.
    //     $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
    //     return $tag;
    // }
    public function saveGutenbergPost( $post_id, $post, $update ) {
        $is_gut = get_post_meta( $post_id, '_jfaq_gut', true );
        //on first save, it is not saved by the gutemberg editor
        if ( $update == true && $is_gut !== 0 ) {
            // if $is_gut is -1 or 1
            //updates the meta to assign Guttenberg edit to that post
            update_post_meta( $post_id, '_jfaq_gut', 1 );
        }
    }

}
