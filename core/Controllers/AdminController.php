<?php

/**
 * @package jolifaq
 */

namespace WPJoli\JoliFAQ\Controllers;

use WPJoli\JoliFAQ\Application;
use WPJoli\JoliFAQ\Engine\JoliFAQBuilder;

class AdminController
{

    public function enqueueAssets($hook_suffix)
    {
        // $faq_editor_suffix = JFAQ()::DOMAIN . '_page_' . JFAQ()::SLUG . '_faq_editor'; // joli-faq-seo_page_joli_faq_seo_faq_editor
        $faq_settings_suffix = JFAQ()::DOMAIN . '_page_' . JFAQ()::SETTINGS_SLUG; // joli-faq-seo_page_joli_faq_seo_faq_editor


        //enqueues required scripts/styles only for admin page FAQ Editor
        if ($hook_suffix === $faq_settings_suffix) {
            wp_enqueue_style('wpjoli-joli-faq-seo-admin-styles', JFAQ()->url('assets/admin/css/joli-faq-seo-admin.css', JFAQ()::USE_MINIFIED_ASSETS), [], JFAQ()::VERSION);
            wp_enqueue_script('wpjoli-joli-faq-seo-admin-scripts', JFAQ()->url('assets/admin/js/joli-faq-seo-admin.js', JFAQ()::USE_MINIFIED_ASSETS), ['jquery', 'wp-color-picker'], JFAQ()::VERSION, true);
            wp_localize_script('wpjoli-joli-faq-seo-admin-scripts', 'jfaqAdmin', ['ajaxUrl' => admin_url('admin-ajax.php')]);

            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wpjoli-wp-color-picker-alpha', JFAQ()->url('vendor/wp-color-picker-alpha/wp-color-picker-alpha.min.js'), ['wp-color-picker'], '3.0.3', true);
        }
        wp_enqueue_script('wpjoli-joli-faq-seo-admin-notice-scripts', JFAQ()->url('assets/admin/js/joli-faq-seo-admin-notices.js', JFAQ()::USE_MINIFIED_ASSETS), ['jquery'], JFAQ()::VERSION, true);
        wp_localize_script('wpjoli-joli-faq-seo-admin-notice-scripts', 'jfaqAdminNotice', ['ajaxUrl' => admin_url('admin-ajax.php')]);
    }

    public function registerPostTypes()
    {
        $this->registerFAQCustomPostType();
        $this->registerFAQGroupCustomPostType();
    }

    public function registerTaxonomies()
    {
        $this->registerFAQGroupCustomTaxonomies();
    }

    public function addSettingsLink($links)
    {
        $joli_link = '<a href="' .
            admin_url('admin.php?page=' . JFAQ()::SETTINGS_SLUG) .
            '">' . __('Settings') . '</a>';

        array_unshift($links, $joli_link);

        return $links;
    }


    // Register Custom Post Type
    public function registerFAQCustomPostType()
    {

        $labels = array(
            'name'                  => _x('Joli FAQs', 'Post Type General Name', 'joli_faq_seo'),
            'singular_name'         => _x('Joli FAQ', 'Post Type Singular Name', 'joli_faq_seo'),
            'menu_name'             => __('Joli FAQs', 'joli_faq_seo'),
            'name_admin_bar'        => __('Joli FAQ', 'joli_faq_seo'),
            'archives'              => __('Item Archives', 'joli_faq_seo'),
            'attributes'            => __('Item Attributes', 'joli_faq_seo'),
            'parent_item_colon'     => __('Parent Item:', 'joli_faq_seo'),
            'all_items'             => __('All Items', 'joli_faq_seo'),
            'add_new_item'          => __('Add New Item', 'joli_faq_seo'),
            'add_new'               => __('Add New', 'joli_faq_seo'),
            'new_item'              => __('New Item', 'joli_faq_seo'),
            'edit_item'             => __('Edit Item', 'joli_faq_seo'),
            'update_item'           => __('Update Item', 'joli_faq_seo'),
            'view_item'             => __('View Item', 'joli_faq_seo'),
            'view_items'            => __('View Items', 'joli_faq_seo'),
            'search_items'          => __('Search Item', 'joli_faq_seo'),
            'not_found'             => __('Not found', 'joli_faq_seo'),
            'not_found_in_trash'    => __('Not found in Trash', 'joli_faq_seo'),
            'featured_image'        => __('Featured Image', 'joli_faq_seo'),
            'set_featured_image'    => __('Set featured image', 'joli_faq_seo'),
            'remove_featured_image' => __('Remove featured image', 'joli_faq_seo'),
            'use_featured_image'    => __('Use as featured image', 'joli_faq_seo'),
            'insert_into_item'      => __('Insert into item', 'joli_faq_seo'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'joli_faq_seo'),
            'items_list'            => __('Items list', 'joli_faq_seo'),
            'items_list_navigation' => __('Items list navigation', 'joli_faq_seo'),
            'filter_items_list'     => __('Filter items list', 'joli_faq_seo'),
        );
        $args = array(
            'label'                 => __('Joli FAQ', 'joli_faq_seo'),
            'description'           => __('Frequently Asked Questions', 'joli_faq_seo'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'custom-fields'),
            'taxonomies'            => array('category', 'post_tag'),
            'hierarchical'          => false,
            'public'                => true,
            'show_in_rest'          => true, // Show guttenberg
            'show_ui'               => true,
            'show_in_menu'          => false,
            'menu_position'         => 5,
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'page',
        );

        register_post_type(JFAQ()::POST_TYPE, $args);
    }

    /**
     * Register Joli FAQ Group post type
     * Custom fields in use:
     * - position (in the editor)
     * - active (true or false)
     * - color (accent color)
     * - faqs (serialized array of joli_faq IDs)
     * - custom_settings (serialized array of custom settings that override defaults)
     *
     * @return void
     */
    public function registerFAQGroupCustomPostType()
    {

        $labels = array(
            'name'                  => _x('Joli FAQ Groups', 'Post Type General Name', 'joli_faq_seo'),
            'singular_name'         => _x('Joli FAQ Group', 'Post Type Singular Name', 'joli_faq_seo'),
            'menu_name'             => __('Joli FAQ Groups', 'joli_faq_seo'),
            'name_admin_bar'        => __('Joli FAQ Group', 'joli_faq_seo'),
            'archives'              => __('Item Archives', 'joli_faq_seo'),
            'attributes'            => __('Item Attributes', 'joli_faq_seo'),
            'parent_item_colon'     => __('Parent Item:', 'joli_faq_seo'),
            'all_items'             => __('All Items', 'joli_faq_seo'),
            'add_new_item'          => __('Add New Item', 'joli_faq_seo'),
            'add_new'               => __('Add New', 'joli_faq_seo'),
            'new_item'              => __('New Item', 'joli_faq_seo'),
            'edit_item'             => __('Edit Item', 'joli_faq_seo'),
            'update_item'           => __('Update Item', 'joli_faq_seo'),
            'view_item'             => __('View Item', 'joli_faq_seo'),
            'view_items'            => __('View Items', 'joli_faq_seo'),
            'search_items'          => __('Search Item', 'joli_faq_seo'),
            'not_found'             => __('Not found', 'joli_faq_seo'),
            'not_found_in_trash'    => __('Not found in Trash', 'joli_faq_seo'),
            'featured_image'        => __('Featured Image', 'joli_faq_seo'),
            'set_featured_image'    => __('Set featured image', 'joli_faq_seo'),
            'remove_featured_image' => __('Remove featured image', 'joli_faq_seo'),
            'use_featured_image'    => __('Use as featured image', 'joli_faq_seo'),
            'insert_into_item'      => __('Insert into item', 'joli_faq_seo'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'joli_faq_seo'),
            'items_list'            => __('Items list', 'joli_faq_seo'),
            'items_list_navigation' => __('Items list navigation', 'joli_faq_seo'),
            'filter_items_list'     => __('Filter items list', 'joli_faq_seo'),
        );
        $args = array(
            'label'                 => __('Joli FAQ Group', 'joli_faq_seo'),
            'description'           => __('Groups of Joli FAQs', 'joli_faq_seo'),
            'labels'                => $labels,
            'supports'              => array('title', /*'editor',*/ 'custom-fields'),
            'taxonomies'            => array('category', 'post_tag'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_rest'               => true,
            'show_in_menu'          => false,
            // 'menu_position'         => 5,
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'page',
        );

        register_post_type(JFAQ()::POST_TYPE_GROUP, $args);
    }

    function registerFAQGroupCustomTaxonomies()
    {

        // Déclaration de la Taxonomie
        $labels = array(
            'name' => __('FAQ Group category', 'joli_faq_seo'),
        );

        $args = [
            'labels' => $labels,
            'public'       => true,
            'hierarchical' => true,
            'show_in_rest' => true,
            'show_ui' => false,
            'show_in_menu' => false,
            'query_var' => true,
            "show_in_nav_menus" => true,
            "show_admin_column" => true,
        ];

        register_taxonomy(JFAQ()::TAXONOMY_GROUP, [
            JFAQ()::POST_TYPE,
            JFAQ()::POST_TYPE_GROUP,
        ], $args);
    }

    public function ajaxGetFAQPreview()
    {
        parse_str($_POST['form_data'], $options);

        $jfaq_options = $options['joli_faq_seo_settings'];
        $builder = new JoliFAQBuilder(null, $jfaq_options);
        $demo_json = include JFAQ()->path('views/admin/demo-faq.json.php');

        $faq_html = $builder->buildJoliFAQSEO($demo_json);

        $data = [
            'faq_preview' => $faq_html,
        ];

        wp_send_json_success($data);
    }
}
