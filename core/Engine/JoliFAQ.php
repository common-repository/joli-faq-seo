<?php

/**
 * @package jolifaq
 */
namespace WPJoli\JoliFAQ\Engine;

// use WPJoli\JoliFAQ\Engine\ContentProcessing;
class JoliFAQ {
    /**
     * Updates FAQ
     * If $which is specified, onnly these fields will update
     *
     * @param [array] $which array of pairs custon_field => value 
     * @return void
     */
    public function ajaxUpdateFAQ() {
        check_ajax_referer( 'JoliFAQSEOEditor', 'nonce' );
        $faq_id = $_POST['faq_id'];
        if ( is_array( $faq_id ) || intval( $faq_id ) <= 0 ) {
            wp_send_json_error( [
                'message' => __( 'An error occured while updating FAQ', 'joli_faq_seo' ),
            ] );
        }
        $title = sanitize_text_field( stripslashes( isset_or_null( $_POST['title'] ) ) );
        $content = htmlspecialchars( stripslashes( isset_or_null( $_POST['content'] ) ) );
        $content = wp_kses( $content, wp_kses_allowed_html( 'post' ) );
        // $content = sanitize_text_field(htmlentities($content));
        $args = jfaq_sanitize_faq_args( isset_or_null( $_POST['args'] ) );
        // if ( $title && $content){
        $faq_id = self::updateFAQ(
            $faq_id,
            $args,
            $title,
            $content
        );
        // }
        // $faq_id = 1;
        if ( $faq_id ) {
            wp_send_json_success( [
                'faq_id'  => $faq_id,
                'title'   => jfaq_decode_faq_input( $title ),
                'content' => htmlspecialchars_decode( $content ),
            ] );
        } else {
            wp_send_json_error( [
                'message' => __( 'An error occured while updating FAQ', 'joli_faq_seo' ),
            ] );
        }
    }

    public function ajaxDeleteFAQ() {
        check_ajax_referer( 'JoliFAQSEOEditor', 'nonce' );
        $faq_id = $_POST['faq_id'];
        if ( is_array( $faq_id ) || intval( $faq_id ) <= 0 ) {
            wp_send_json_error( [
                'message' => __( 'An error occured while updating FAQ', 'joli_faq_seo' ),
            ] );
        }
        // error_log($title, $content);
        $faq = self::deleteFAQ( $faq_id );
        if ( $faq ) {
            wp_send_json_success( [
                'faq' => $faq,
            ] );
        } else {
            wp_send_json_error( [
                'message' => __( 'An error occured while deleting FAQ', 'joli_faq_seo' ),
            ] );
        }
    }

    public function ajaxInsertFAQ( $req ) {
        check_ajax_referer( 'JoliFAQSEOEditor', 'nonce' );
        // $title = sanitize_text_field(isset_or_null($_POST['title']));
        $title = sanitize_text_field( stripslashes( isset_or_null( $_POST['title'] ) ) );
        $content = htmlspecialchars( stripslashes( isset_or_null( $_POST['content'] ) ) );
        $content = wp_kses( $content, wp_kses_allowed_html( 'post' ) );
        $args = jfaq_sanitize_faq_args( isset_or_null( $_POST['args'] ) );
        $category = null;
        // error_log($title, $content);
        $blank_faq = isset_or_null( $_POST['blankfaq'] );
        $faq_id_src = isset_or_null( $_POST['idSrc'] );
        $faq_id = self::createFAQ(
            $title,
            $content,
            $args,
            $category,
            $blank_faq,
            $faq_id_src
        );
        if ( $faq_id ) {
            wp_send_json_success( [
                'faq_id'  => $faq_id,
                'title'   => jfaq_decode_faq_input( $title ),
                'content' => htmlspecialchars_decode( $content ),
            ] );
        } else {
            wp_send_json_error( [
                'message' => __( 'An error occured while inserting FAQ', 'joli_faq_seo' ),
            ] );
        }
    }

    public function ajaxGetFAQs() {
        check_ajax_referer( 'JoliFAQSEOEditor', 'nonce' );
        $faqs = self::getFAQs();
        if ( sizeof( $faqs ) >= 0 ) {
            wp_send_json_success( [
                'faqs' => $faqs,
            ] );
        } else {
            wp_send_json_error( [
                'message' => __( 'An error occured while retrieving FAQs', 'joli_faq_seo' ),
            ] );
        }
    }

    public function ajaxCheckGutenbergEditingComplete() {
        check_ajax_referer( 'JoliFAQSEOEditor', 'nonce' );
        $faq_id = $_POST['faq_id'];
        $has_gutenberg_flag = false;
        die;
    }

    /**
     * If $editor is not true, we fetch the faqs regardless of their category
     *
     * @param [type] $args
     * @param boolean $editor
     * @return void
     */
    public static function getFAQs( $args = null, $editor = true ) {
        $query_args = [
            'numberposts' => -1,
            'post_type'   => JFAQ()::POST_TYPE,
            'orderby'     => 'date',
            'order'       => 'ASC',
        ];
        //Order by array order (user selected order)
        if ( $args && isset_or_null( $args['post__in'] ) ) {
            $query_args['post__in'] = $args['post__in'];
            $query_args['orderby'] = 'post__in';
        }
        if ( $editor && jfaq_xy()->can_use_premium_code__premium_only() ) {
            if ( $args && isset_or_null( $args['category'] ) ) {
                $query_args['tax_query'] = [[
                    'taxonomy' => JFAQ()::TAXONOMY_GROUP,
                    'field'    => 'slug',
                    'terms'    => $args['category'],
                ]];
            } else {
                $query_args['tax_query'] = [[
                    'taxonomy' => JFAQ()::TAXONOMY_GROUP,
                    'operator' => 'NOT EXISTS',
                ]];
            }
        }
        $faqs = get_posts( $query_args );
        $output = [];
        foreach ( $faqs as $faq ) {
            $row = [
                'ID'      => $faq->ID,
                'title'   => jfaq_decode_faq_input( $faq->post_title ),
                'content' => htmlspecialchars_decode( $faq->post_content, ENT_QUOTES ),
                'date'    => $faq->post_date,
            ];
            $row['gut'] = (int) get_post_meta( $faq->ID, '_jfaq_gut', true );
            $output[] = $row;
        }
        return $output;
    }

    /**
     * Updates single FAQ
     * If $title and $content are empty,  will only update custon fields
     *
     * @param [type] $faq_id
     * @param [type] $title
     * @param [type] $content
     * @param [type] $args
     * @return void
     */
    public static function updateFAQ(
        $faq_id,
        $args,
        $title = null,
        $content = null
    ) {
        // $args =
        //     'title'     => $title,
        //     'content'   => $content,
        //     'clicked'   => 0, //number of time clicked
        //     'icon'      => $icon,
        //     'tags'      => [
        //                     $tags
        //                 ],
        // ];
        // $title = sprintf('Plainte de %s (%s)', $name, date("d-m-Y"));
        if ( !$faq_id ) {
            return false;
        }
        if ( $title && $content ) {
            $postarr = [
                'ID'           => $faq_id,
                'post_title'   => wp_slash( $title ),
                'post_content' => wp_slash( $content ),
            ];
            $update_id = wp_update_post( $postarr );
            // $new_id = 1050;
        }
        if ( $update_id > 0 || !($title && $content) ) {
            $to_update = ( isset( $update_id ) ? $update_id : $faq_id );
            //set gutenberg to 0 as we save from the tinyMCE editor
            update_post_meta( $to_update, '_jfaq_gut', 0 );
            //Custom fields
            foreach ( $args as $key => $value ) {
                if ( $value === '' ) {
                    $res = delete_post_meta( $to_update, $key );
                } else {
                    $res = update_post_meta( $to_update, $key, $value );
                }
            }
        }
        return ( isset( $to_update ) ? $to_update : $faq_id );
    }

    public static function deleteFAQ( $faq_id ) {
        // $args =
        //     'title'     => $title,
        //     'content'   => $content,
        //     'clicked'   => 0, //number of time clicked
        //     'icon'      => $icon,
        //     'tags'      => [
        //                     $tags
        //                 ],
        // ];
        if ( !$faq_id ) {
            return false;
        }
        $deleted_faq = wp_delete_post( $faq_id );
        return $deleted_faq;
    }

    /**
     * Undocumented function
     *
     * @param [type] $title
     * @param [type] $content
     * @param [type] $args
     * @param boolean $blank_faq allow the creation of a blank FAQ in order to prepare a new Gutenberg FAQ
     * @return void
     */
    public static function createFAQ(
        $title,
        $content,
        $args,
        $category = null,
        $blank_faq = false,
        $faq_id_src = null
    ) {
        // $args =
        //     'title'     => $title,
        //     'content'   => $content,
        //     'clicked'   => 0, //number of time clicked
        //     'icon'      => $icon,
        //     'tags'      => [
        //                     $tags
        //                 ],
        // ];
        // $title = sprintf('Plainte de %s (%s)', $name, date("d-m-Y"));
        if ( !$blank_faq && (!$title || !$content) ) {
            return false;
        }
        // jlog($args);
        $postarr = [
            'post_title'   => wp_slash( $title ),
            'post_content' => wp_slash( $content ),
            'post_status'  => 'publish',
            'post_type'    => JFAQ()::POST_TYPE,
        ];
        if ( jfaq_xy()->can_use_premium_code__premium_only() && $faq_id_src > 0 ) {
            $post_source = get_post( $faq_id_src );
            if ( !$post_source ) {
                return false;
            }
            $postarr = [
                'post_title'   => wp_slash( $title ),
                'post_content' => $post_source->post_content,
                'post_status'  => 'publish',
                'post_type'    => JFAQ()::POST_TYPE,
            ];
        }
        $new_id = wp_insert_post( $postarr );
        if ( $new_id > 0 ) {
            //Custom fields
            if ( $args ) {
                foreach ( $args as $key => $value ) {
                    $res = update_post_meta( $new_id, $key, $value );
                    //probleme de validation lors de l'insertion des custon fields
                    // if ($res === false) {
                    //     return false;
                    // }
                }
            }
            return $new_id;
        }
        return false;
    }

}
