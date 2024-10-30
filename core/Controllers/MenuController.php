<?php

/**
 * @package jolifaq
 */
namespace WPJoli\JoliFAQ\Controllers;

use WPJoli\JoliFAQ\Application;
use WPJoli\JoliFAQ\Controllers\SettingsController;
use WPJoli\JoliFAQ\Engine\JoliFAQBuilder;
use WPJoli\JoliFAQ\Engine\JoliFAQGroup;
class MenuController {
    public $admin_pages = [];

    public $admin_subpages = [];

    public $pages = [];

    public $subpages = [];

    protected $option_group;

    protected $logo_url;

    public function __construct() {
        $this->option_group = Application::SLUG . '_settings';
        $this->setPages();
        $this->setSubpages();
        $this->addPages( $this->pages )->withSubPage( 'FAQ Editor' )->addSubPages( $this->subpages );
        // $this->logo_url = JFAQ()->url('assets/admin/img/wpjoli-logo-new-small.png');
        $this->logo_url = JFAQ()->url( 'assets/admin/img/wpjoli-logo-2023.svg' );
    }

    /**
     * Array of menu pages
     * To be defined manually
     */
    public function setPages() {
        $this->pages = [[
            'page_title' => Application::NAME,
            'menu_title' => Application::NAME,
            'capability' => 'edit_pages',
            'menu_slug'  => Application::SLUG . '_faq_editor',
            'callback'   => [$this, 'displayFAQEditorPage'],
            'icon_url'   => 'data:image/svg+xml;base64,' . 'PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48ZGVmcz48c3R5bGU+LmNscy0xLC5jbHMtMntmaWxsOiNmZmY7fS5jbHMtMXtvcGFjaXR5OjAuNTt9PC9zdHlsZT48L2RlZnM+PGcgaWQ9IkZsYW1lIj48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0xNjEuMiwxOTYuNzFDMjAxLjE5LDEwOCwxNjYsMzkuMzIsMTczLDMyLjU4YzMwLjQ5LTI5LjQ1LDE1Ny44Niw3OS42LDE5Ny4wOSwxNTkuNzYsNTEuMSwxMDQuMiw1Ni44LDE3NS4xNSwzNC42NywyMTctMTkuMDksMzYuMjgtNzYsODYuMjQtMTc4LjU3LDc3QzE1NS4xMiw0ODAsNDcuNjksNDQ4LjcxLDE2MS4yLDE5Ni43MVoiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0xNzkuMzQsMTgwLjI4YzUyLTY4LDUwLjM0LTE0OS4xMyw1Ny43NS0xNTMuNSwzMi0xOC43MSwxMDUsMTE4LjI2LDEyMi44MSwxOTYuNTMsMjMuMTgsMTAxLjgyLDEzLjg3LDE2NC43LTEzLjQ4LDE5Ni4wNS0yMy42NSwyNy4xNi04Mi41NCw1Ny43NS0xNjksMjYuNzhDMTE3LjUsNDI0LjY4LDMxLjM1LDM3My40OCwxNzkuMzQsMTgwLjI4WiIvPjxwYXRoIGNsYXNzPSJjbHMtMiIgZD0iTTE4OC4wOCwyMjZjMzMuMjQtNzAuODYsMTYuMjQtMTQxLjcyLDIyLTE0Ny4xMywyNC44OS0yMy4zNywxMTUuNDEsODEsMTQ2LDE0NiwzOS43OSw4NC4zNCw0My41LDE0MS41MiwyNS4yNiwxNzQuODYtMTUuNzcsMjktNjIuMTIsNjguNDktMTQ0LjQ3LDU5Ljk0QzE3OS44MSw0NTMuNzQsOTMuNzYsNDI3LjMzLDE4OC4wOCwyMjZaIi8+PC9nPjwvc3ZnPg==',
            'position'   => 110,
        ]];
    }

    /**
     * Array of submenu pages
     * To be defined manually
     */
    public function setSubpages() {
        $this->subpages = [[
            'parent_slug' => Application::SLUG . '_faq_editor',
            'page_title'  => 'Settings',
            'menu_title'  => 'Settings',
            'capability'  => 'manage_options',
            'menu_slug'   => $this->option_group,
            'callback'    => [$this, 'displaySettingsPage'],
        ]];
    }

    public function addPages( array $pages ) {
        $this->admin_pages = $pages;
        return $this;
    }

    public function withSubPage( string $title = null ) {
        if ( empty( $this->admin_pages ) ) {
            return $this;
        }
        $admin_page = $this->admin_pages[0];
        $subpage = [[
            'parent_slug' => $admin_page['menu_slug'],
            'page_title'  => $admin_page['page_title'],
            'menu_title'  => ( $title ? $title : $admin_page['menu_title'] ),
            'capability'  => $admin_page['capability'],
            'menu_slug'   => $admin_page['menu_slug'],
            'callback'    => $admin_page['callback'],
        ]];
        $this->admin_subpages = $subpage;
        return $this;
    }

    public function addSubPages( array $pages ) {
        $this->admin_subpages = array_merge( $this->admin_subpages, $pages );
        return $this;
    }

    public function addAdminMenu() {
        foreach ( $this->admin_pages as $page ) {
            add_menu_page(
                $page['page_title'],
                $page['menu_title'],
                $page['capability'],
                $page['menu_slug'],
                $page['callback'],
                $page['icon_url'],
                $page['position']
            );
        }
        foreach ( $this->admin_subpages as $page ) {
            add_submenu_page(
                $page['parent_slug'],
                $page['page_title'],
                $page['menu_title'],
                $page['capability'],
                $page['menu_slug'],
                $page['callback']
            );
        }
    }

    public function displayMainPage() {
        JFAQ()->render( [
            'admin' => 'main',
        ] );
    }

    public function displayFAQEditorPage() {
        // $tabs = [
        //     'quick-start' => __( 'Quick start', 'joli_faq_seo'),
        //     'quick-setup' => __( 'Quick setup', 'joli_faq_seo'),
        // 	'shortcode' => __( 'Shortcode', 'joli_faq_seo'),
        // 	'documentation' => __( 'Documentation', 'joli_faq_seo'),
        // 	'hooks' => __( 'Hooks (for developers)', 'joli_faq_seo'),
        // ];
        $admin_url = get_admin_url();
        $data = [
            'logo_url'        => $this->logo_url,
            'jfaq_editor_url' => sprintf( '%sadmin.php?page=' . Application::SLUG . '_faq_editor', $admin_url ),
        ];
        $data['pro'] = false;
        JFAQ()->render( [
            'admin' => 'faq-editor',
        ], $data );
    }

    public function displayUserGuidePage() {
        $tabs = [
            'quick-start'   => __( 'Quick start', 'joli_faq_seo' ),
            'quick-setup'   => __( 'Quick setup', 'joli_faq_seo' ),
            'shortcode'     => __( 'Shortcode', 'joli_faq_seo' ),
            'documentation' => __( 'Documentation', 'joli_faq_seo' ),
            'hooks'         => __( 'Hooks (for developers)', 'joli_faq_seo' ),
        ];
        $data = [
            'tabs'     => $tabs,
            'logo_url' => $this->logo_url,
        ];
        JFAQ()->render( [
            'admin/user-guide' => 'user-guide',
        ], $data );
    }

    public function displaySettingsPage() {
        $tabs = [
            'general'    => __( 'General', 'joli_faq_seo' ),
            'appearance' => __( 'Appearance', 'joli_faq_seo' ),
            'dimensions' => __( 'Dimensions', 'joli_faq_seo' ),
        ];
        $settings = JFAQ()->requestService( SettingsController::class );
        $groups = $settings->getGroups();
        $tabs = [];
        foreach ( $groups as $group ) {
            $tabs[$group['id']] = $group['label'];
        }
        $plugin_info = get_plugin_data( JFAQ()->path( 'joli-faq-seo.php' ) );
        $base_url = 'https://wpjoli.com/joli-faq-seo/';
        $params = '?utm_source=' . getHostURL() . '&utm_medium=admin-settings';
        $demo_json = (include JFAQ()->path( 'views/admin/demo-faq.json.php' ));
        $faq_builder = new JoliFAQBuilder();
        $data = [
            'option_group' => $this->option_group,
            'tabs'         => $tabs,
            'logo_url'     => $this->logo_url,
            'version'      => ( isset( $plugin_info['Version'] ) ? $plugin_info['Version'] : '' ),
            'wpjoli_url'   => $base_url . $params,
            'pro_url'      => $base_url . $params,
            'pro_url_v'    => $base_url . '#visibilities' . $params,
            'demo_faq'     => $faq_builder->buildJoliFAQSEO( $demo_json ),
        ];
        JFAQ()->render( [
            'admin' => 'settings',
        ], $data );
    }

}
