<?php

/**
 * @package jolifaq
 */
namespace WPJoli\JoliFAQ\Engine;

// use WPJoli\JoliFAQ\Engine\ContentProcessing;
class JoliFAQGroup {
    public function ajaxGetFAQGroups() {
        check_ajax_referer( 'JoliFAQSEOEditor', 'nonce' );
        $args = jfaq_sanitize_faq_group_args( $_POST['args'] );
        $faq_groups = self::getFAQGroups( $args );
        if ( sizeof( $faq_groups ) >= 0 ) {
            wp_send_json_success( [
                'faq_groups' => $faq_groups,
            ] );
        } else {
            wp_send_json_error( [
                'message' => __( 'An error occured while inserting FAQ', 'joli_faq_seo' ),
            ] );
        }
    }

    public function ajaxCreateFAQGroup() {
        check_ajax_referer( 'JoliFAQSEOEditor', 'nonce' );
        $title = sanitize_text_field( isset_or_null( $_POST['title'] ) );
        $args = jfaq_sanitize_faq_group_args( $_POST['args'] );
        // $faq_id = 1;
        // JFAQ()->log($_POST['args']);
        // JFAQ()->log($args);
        // die;
        $faq_group_id = self::createFAQGroup( $title, $args );
        if ( $faq_group_id ) {
            wp_send_json_success( [
                'faq_group_id' => $faq_group_id,
                'title'        => $title,
            ] );
        } else {
            wp_send_json_error( [
                'message' => __( 'An error occured while inserting FAQ', 'joli_faq_seo' ),
            ] );
        }
    }

    public function ajaxUpdateFAQGroups() {
        check_ajax_referer( 'JoliFAQSEOEditor', 'nonce' );
        $groups = ( isset( $_POST['groups'] ) ? $_POST['groups'] : [] );
        $groups = json_decode( stripslashes( $groups ), true );
        $args = jfaq_sanitize_faq_group_args( isset_or_null( $_POST['args'] ) );
        $result = self::updateFAQGroups( $groups, $args );
        // $faq_id = 1;
        if ( $result ) {
            wp_send_json_success( [] );
        } else {
            wp_send_json_error( [
                'message' => __( 'An error occured while updating FAQ', 'joli_faq_seo' ),
            ] );
        }
    }

    public function ajaxUpdateCategoryFAQGroups() {
        check_ajax_referer( 'JoliFAQSEOEditor', 'nonce' );
        $groups = ( isset( $_POST['groups_ids'] ) ? $_POST['groups_ids'] : [] );
        $new_category = isset_or_null( $_POST['category'] );
        // JFAQ()->log($groups);
        // $groups = json_decode(stripslashes($groups), true);
        if ( $new_category === null ) {
            wp_send_json_error( [
                'message' => __( 'Target category must be set', 'joli_faq_seo' ),
            ] );
        }
        $errors = [];
        $has_errors = false;
        if ( is_array( $groups ) && count( $groups ) > 0 ) {
            foreach ( $groups as $group_id ) {
                $updated = self::updateFAQGroupCategory( $group_id, $new_category );
                if ( $updated['status'] === 'error' ) {
                    $errors[] = $updated;
                    $has_errors = true;
                }
            }
        }
        if ( !$has_errors ) {
            wp_send_json_success( [
                'message' => __( 'Success updating FAQ Group and FAQ category', 'joli_faq_seo' ),
            ] );
        } else {
            wp_send_json_error( [
                'message'       => __( 'An error occured', 'joli_faq_seo' ),
                'error_details' => $errors,
            ] );
        }
    }

    public function ajaxCreateCategoryFAQGroups() {
        check_ajax_referer( 'JoliFAQSEOEditor', 'nonce' );
        $new_category = isset_or_null( $_POST['category'] );
        // JFAQ()->log($groups);
        // $groups = json_decode(stripslashes($groups), true);
        $creation = self::createFAQGroupCategory( $new_category );
        if ( $creation !== false ) {
            wp_send_json_success( [
                'message'  => __( 'Success updating FAQ Group and FAQ category', 'joli_faq_seo' ),
                'category' => $creation,
            ] );
        } else {
            wp_send_json_error( [
                'message' => __( 'Could not create the new category, maybe it already exists or the name is invalid.', 'joli_faq_seo' ),
            ] );
        }
    }

    public function ajaxDeleteCategoryFAQGroups() {
        check_ajax_referer( 'JoliFAQSEOEditor', 'nonce' );
        $category = isset_or_null( $_POST['category'] );
        // $groups = json_decode(stripslashes($groups), true);
        $deletion = self::deleteFAQGroupCategory( $category );
        if ( $deletion ) {
            wp_send_json_success( [
                'message' => __( 'Success deleting FAQ Group and FAQ category', 'joli_faq_seo' ),
            ] );
        } else {
            wp_send_json_error( [
                'message' => __( 'Could not delete the category', 'joli_faq_seo' ),
            ] );
        }
    }

    public static function createFAQGroupCategory( $new_category ) {
        if ( $new_category == '0' ) {
            return false;
        }
        $insertion = wp_create_term( $new_category, JFAQ()::TAXONOMY_GROUP );
        //success
        if ( is_array( $insertion ) ) {
            $term_id = $insertion['term_id'];
            $term = get_term_by( 'id', $term_id, JFAQ()::TAXONOMY_GROUP );
            return $term->slug;
        }
        return false;
    }

    public static function deleteFAQGroupCategory( $category ) {
        if ( !$category ) {
            return false;
        }
        $term = get_term_by( 'slug', $category, JFAQ()::TAXONOMY_GROUP );
        if ( $term !== false ) {
            $term_id = $term->term_id;
        }
        $deletion = wp_delete_term( $term_id, JFAQ()::TAXONOMY_GROUP );
        //success
        if ( $deletion === true ) {
            return true;
        }
        return false;
    }

    public static function updateFAQGroupCategory( $faq_group_id, $new_category ) {
        if ( !$faq_group_id ) {
            return false;
        }
        $faq_group = self::getFAQGroup( $faq_group_id );
        //Get the faqs from the group
        $faqs = $faq_group['faqs'];
        //update the faqs category first
        $updated_faqs = [];
        $errors = [];
        if ( $faqs ) {
            foreach ( $faqs as $faq_id ) {
                // $updated = self::updateFAQCategory($faq_id, $new_category);
                if ( $new_category === '0' ) {
                    //remove category
                    $update = wp_set_object_terms( $faq_id, '', JFAQ()::TAXONOMY_GROUP );
                } else {
                    //set new category
                    $update = wp_set_object_terms( $faq_id, $new_category, JFAQ()::TAXONOMY_GROUP );
                }
                if ( is_array( $update ) ) {
                    $updated_faqs[] = $faq_id;
                } else {
                    $errors[] = $faq_id;
                }
            }
        }
        //update the faq_group category first
        if ( $new_category === '0' ) {
            $update_group = wp_set_object_terms( $faq_group_id, '', JFAQ()::TAXONOMY_GROUP );
        } else {
            $update_group = wp_set_object_terms( $faq_group_id, $new_category, JFAQ()::TAXONOMY_GROUP );
        }
        if ( is_array( $update_group ) && count( isset_or_null( $errors ) ) === 0 ) {
            return [
                'status'       => 'success',
                'faq_group_id' => $faq_group_id,
                'faq_ids'      => $updated_faqs,
            ];
        } else {
            return [
                'status'          => 'error',
                'faq_group_id'    => $faq_group_id,
                'faq_group_error' => $update_group,
                'faq_ids'         => $updated_faqs,
                'faq_ids_error'   => $errors,
            ];
        }
    }

    public static function getFAQGroup( $faq_group_id ) {
        $faq_group = get_post( $faq_group_id );
        if ( !$faq_group ) {
            return false;
        }
        // * - position (in the editor)
        // * - active (true or false)
        // * - color (accent color)
        // * - faqs (serialized array of joli_faq IDs)
        // * - custom_settings (serialized array of custom settings that override defaults)
        $faqs = get_post_meta( $faq_group->ID, 'faqs', true );
        $row = [
            'ID'       => $faq_group->ID,
            'title'    => $faq_group->post_title,
            'position' => get_post_meta( $faq_group->ID, 'position', true ),
            'faqs'     => ( $faqs ? $faqs : [] ),
            'views'    => null,
        ];
        // error_log(json_encode($output));
        return $row;
    }

    public static function getAllFAQGroups() {
        //Get a list of all the categories
        $categories = self::getFAQGroupCategories__premium_only();
        $all = [[
            'category' => null,
            'name'     => null,
            'faqs'     => self::getFAQGroups(),
        ]];
        if ( $categories ) {
            //Get all FAQ Gropups for each category, grouping by category
            foreach ( $categories as $cat ) {
                $faqs = self::getFAQGroups( [
                    'category' => $cat->slug,
                ] );
                $all[] = [
                    'category' => $cat->slug,
                    'name'     => $cat->name,
                    'faqs'     => $faqs,
                ];
            }
        }
        return $all;
    }

    /**
     * Get all FAQ Groups
     *
     * @param [string|array] $category Category slug
     * @return void
     */
    public static function getFAQGroups( $args = null ) {
        $query_args = [
            'numberposts' => -1,
            'post_type'   => JFAQ()::POST_TYPE_GROUP,
            'orderby'     => 'meta_value_num',
            'meta_key'    => 'position',
            'order'       => 'ASC',
        ];
        // pre($query_args);
        $faq_groups = get_posts( $query_args );
        $output = [];
        foreach ( $faq_groups as $faq_group ) {
            // * - position (in the editor)
            // * - active (true or false)
            // * - color (accent color)
            // * - faqs (serialized array of joli_faq IDs)
            // * - custom_settings (serialized array of custom settings that override defaults)
            $faqs = get_post_meta( $faq_group->ID, 'faqs', true );
            // $_collapsed = get_post_meta($faq_group->ID, 'position', true);
            $row = [
                'ID'        => $faq_group->ID,
                'title'     => $faq_group->post_title,
                'position'  => (int) get_post_meta( $faq_group->ID, 'position', true ),
                'faqs'      => ( $faqs ? $faqs : [] ),
                'views'     => null,
                'collapsed' => (bool) get_post_meta( $faq_group->ID, 'collapsed', true ),
            ];
            $output[] = $row;
        }
        // error_log(json_encode($output));
        return $output;
    }

    public static function updateFAQGroups( $groups, $args = null ) {
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
        // error_log('INPUT');
        // error_log(serialize($groups));
        if ( !is_array( $groups ) ) {
            return false;
        }
        $faq_group_args = null;
        //Check current faq group to decide or not to delete groups
        $local_groups = self::getFAQGroups( $faq_group_args );
        $local_ids = array_column( $local_groups, 'ID' );
        $front_ids = array_column( $groups, 'ID' );
        $to_delete = array_diff( $local_ids, $front_ids );
        // JFAQ()->log($local_groups);
        // return true;
        // exit;
        // error_log(json_encode($local_ids));
        // error_log(json_encode($front_ids));
        // error_log(json_encode($to_delete));
        foreach ( $to_delete as $group_id ) {
            // error_log($group_id);
            wp_delete_post( $group_id, true );
            // jlog('DELETING FAQ GROUP ' . $group_id);
        }
        foreach ( $groups as $group ) {
            $postarr = [
                'ID'         => $group['ID'],
                'post_title' => $group['title'],
            ];
            $update_id = wp_update_post( $postarr );
            // error_log($update_id);
            $metas = [
                'position'  => (int) $group['position'],
                'collapsed' => (bool) $group['collapsed'],
                'faqs'      => $group['faqs'],
            ];
            //Custom fields
            foreach ( $metas as $key => $value ) {
                $res = update_post_meta( $update_id, $key, $value );
            }
        }
        return true;
    }

    public static function createFAQGroup( $title, $args ) {
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
        if ( !$title ) {
            return false;
        }
        $postarr = [
            'post_title'  => $title,
            'post_status' => 'publish',
            'post_type'   => JFAQ()::POST_TYPE_GROUP,
        ];
        $new_id = wp_insert_post( $postarr );
        // $new_id = 1050;
        if ( $new_id > 0 ) {
            $metas = [
                'position' => $args['position'],
            ];
            //Custom fields
            foreach ( $metas as $key => $value ) {
                // EPF()->log($key);
                // EPF()->log($value);
                $res = update_post_meta( $new_id, $key, $value );
                //probleme de validation lors de l'insertion des custon fields
                // if ($res === false) {
                //     error_log('FALSE : ' . $key . ' : ' . $value);
                //     return false;
                // }
            }
            return $new_id;
        }
        return false;
    }

}
