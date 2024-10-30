<?php

namespace WPJoli\JoliFAQ;

use WPJoli\JoliFAQ\JoliApplication;
use WPJoli\JoliFAQ\Activator;
use WPJoli\JoliFAQ\Hooks;

class Application extends JoliApplication
{

    const NAME = 'Joli FAQ SEO';
    const POST_TYPE = 'joli_faq';
    const POST_TYPE_GROUP = 'joli_faq_group';
    const TAXONOMY_GROUP = 'faq_group_category';
    const SLUG = 'joli_faq_seo';
    const SETTINGS_SLUG = 'joli_faq_seo_settings';
    const DOMAIN = 'joli-faq-seo';
    const ID = 'jolifaqseo';
    const USE_MINIFIED_ASSETS = true;
    const VERSION = '1.3.5';

    protected $hooks;
    protected $log;

    public $options;

    public function __construct()
    {
        // static::$instance = $this;
        parent::__construct();
        
        load_plugin_textdomain('joli_faq_seo',false,
                    trailingslashit(plugin_basename($this->path()) . '/languages')
                );
        // add_action(
        //     'plugins_loaded',function () {
        //         $this->log('load languages');
        //         load_plugin_textdomain('joli-faq-seo',false,
        //             trailingslashit(plugin_basename($this->path()) . '/languages')
        //         );
        //     }
        // );
        
        $this->log = new Log($this);
    }

    public function run()
    {
        $this->hooks = new Hooks( $this );
        $this->hooks->run();
    }

    public function activate()
    {
        $activator = new Activator();
        $activator->activate();
    }

    public function deactivate()
    { 
        
    }

}
