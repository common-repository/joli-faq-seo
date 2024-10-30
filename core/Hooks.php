<?php

/**
 * @package jolifaq
 */
namespace WPJoli\JoliFAQ;

use WPJoli\JoliFAQ\Application;
use WPJoli\JoliFAQ\Controllers\AdminController;
use WPJoli\JoliFAQ\Controllers\BlocksController;
use WPJoli\JoliFAQ\Controllers\FAQEditorController;
use WPJoli\JoliFAQ\Controllers\MenuController;
use WPJoli\JoliFAQ\Controllers\PublicAppController;
use WPJoli\JoliFAQ\Controllers\SettingsController;
use WPJoli\JoliFAQ\Controllers\ShortcodesController;
use WPJoli\JoliFAQ\Controllers\NoticesFreeController;
use WPJoli\JoliFAQ\Engine\JoliFAQ;
use WPJoli\JoliFAQ\Engine\JoliFAQProcessor;
use WPJoli\JoliFAQ\Engine\JoliFAQGroup;
use WPJoli\JoliFAQ\Engine\JoliFAQStats;
use WPJoli\JoliFAQ\Engine\WooCommerce;
class Hooks {
    protected $app;

    protected $admin;

    protected $menu;

    protected $public_app;

    protected $settings;

    protected $shortcodes;

    protected $notices;

    protected $notices_free;

    protected $faq;

    protected $faq_editor;

    protected $faq_group;

    protected $faq_processor;

    protected $blocks;

    protected $woocom;

    protected $stats;

    public function __construct( Application &$app ) {
        $this->app = $app;
        $this->admin = $app->requestService( AdminController::class );
        $this->faq_editor = $app->requestService( FAQEditorController::class );
        $this->menu = $app->requestService( MenuController::class );
        $this->public_app = $app->requestService( PublicAppController::class );
        $this->settings = $app->requestService( SettingsController::class );
        $this->shortcodes = $app->requestService( ShortcodesController::class );
        $this->faq = $app->requestService( JoliFAQ::class );
        $this->faq_group = $app->requestService( JoliFAQGroup::class );
        $this->faq_processor = $app->requestService( JoliFAQProcessor::class );
        if ( version_compare( $GLOBALS['wp_version'], '5.0', '>=' ) ) {
            $this->blocks = $app->requestService( BlocksController::class );
        }
        if ( jfaq_xy()->is_free_plan() ) {
            $this->notices_free = $app->requestService( NoticesFreeController::class );
        }
    }

    /**
     * Registers all the plugin hooks and filters
     */
    public function run() {
        $this->registerAdminHooks();
        $this->registerPublicHooks();
    }

    public function disable_wp_emojicons() {
        // all actions related to emojis
        // remove_action( 'admin_print_styles', 'print_emoji_styles' );
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        // remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        // remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        // remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        // remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
        // filter to remove TinyMCE emojis
        // add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
        // add_filter( 'emoji_svg_url', '__return_false' );
    }

    private function registerAdminHooks() {
        add_action( 'init', [$this, 'disable_wp_emojicons'] );
        //actions
        if ( jfaq_xy()->is_free_plan() ) {
            add_action( 'init', [$this->notices_free, 'initNotices'] );
            add_action( 'wp_ajax_joli_faq_seo_handle_notice', [$this->notices_free, 'jfaqHandleNotice'] );
        }
        //Pst Type
        add_action( 'init', [$this->admin, 'registerPostTypes'] );
        add_action( 'init', [$this->admin, 'registerTaxonomies'] );
        add_action( 'init', [$this->settings, 'handleResetSettings'] );
        //Registers the block for WP version above 5.0
        if ( version_compare( $GLOBALS['wp_version'], '5.0', '>=' ) ) {
            add_action( 'init', [$this->blocks, 'registerBlocks'] );
        }
        // add_action( 'plugins_loaded',                       [ $this->app,           'registerLanguages' ] );
        add_action( 'admin_enqueue_scripts', [$this->admin, 'enqueueAssets'] );
        add_action( 'admin_menu', [$this->menu, 'addAdminMenu'] );
        add_action( 'admin_init', [$this->settings, 'registerSettings'] );
        add_action( 'admin_enqueue_scripts', [$this->faq_editor, 'enqueueAssets'] );
        add_action(
            'save_post_' . JFAQ()::POST_TYPE,
            [$this->faq_editor, 'saveGutenbergPost'],
            10,
            3
        );
        //filters
        add_filter( 'plugin_action_links_' . plugin_basename( JFAQ()->path( 'joli-faq-seo.php' ) ), [$this->admin, 'addSettingsLink'] );
        if ( !jfaq_is_front() ) {
            /**
             * Settings
             */
            add_action( 'wp_ajax_jfaq_admin_get_faq_preview', [$this->admin, 'ajaxGetFAQPreview'] );
            /**
             * EDITOR
             */
            //FAQs
            add_action( 'wp_ajax_jfaq_get_faqs', [$this->faq, 'ajaxGetFAQs'] );
            add_action( 'wp_ajax_jfaq_insert_faq', [$this->faq, 'ajaxInsertFAQ'] );
            add_action( 'wp_ajax_jfaq_update_faq', [$this->faq, 'ajaxUpdateFAQ'] );
            add_action( 'wp_ajax_jfaq_delete_faq', [$this->faq, 'ajaxDeleteFAQ'] );
            add_action( 'wp_ajax_jfaq_check_gutenberg_faq', [$this->faq, 'ajaxCheckGutenbergEditingComplete'] );
            //FAQ Groups
            add_action( 'wp_ajax_jfaq_get_faq_groups', [$this->faq_group, 'ajaxGetFAQGroups'] );
            add_action( 'wp_ajax_jfaq_create_faq_group', [$this->faq_group, 'ajaxCreateFAQGroup'] );
            add_action( 'wp_ajax_jfaq_update_faq_groups', [$this->faq_group, 'ajaxUpdateFAQGroups'] );
            add_action( 'wp_ajax_jfaq_delete_faq_group', [$this->faq_group, 'ajaxDeleteFAQGroup'] );
            add_action( 'wp_ajax_jfaq_update_category_faq_group', [$this->faq_group, 'ajaxUpdateCategoryFAQGroups'] );
            add_action( 'wp_ajax_jfaq_create_category_faq_group', [$this->faq_group, 'ajaxCreateCategoryFAQGroups'] );
            add_action( 'wp_ajax_jfaq_delete_category_faq_group', [$this->faq_group, 'ajaxDeleteCategoryFAQGroups'] );
        }
    }

    private function registerPublicHooks() {
        //only for front end, avoid interferences with the editor
        if ( jfaq_is_front() ) {
            //actions
            add_action( 'init', [$this->shortcodes, 'registerShortcodes'] );
            //Process answer content using wp do_shortcode filter
            add_filter( 'joli_faq_seo_faq_answer', [$this->faq_processor, 'processAnswer'] );
        }
    }

}
