<?php

$font_weights = [
    'none' => __('[Inherit from theme]', 'joli_faq_seo'),
    '100' => '100 (lightest)',
    '200' => '200',
    '300' => '300',
    '400' => '400 (normal)',
    '500' => '500',
    '600' => '600',
    '700' => '700 (bold)',
    '800' => '800',
    '900' => '900 (boldest)',
    'lighter' => __('Lighter (relative to parent)', 'joli_faq_seo'),
    'bolder' => __('Bolder (relative to parent)', 'joli_faq_seo'),
];

return [
    [
        'group' => 'general',
        'label' => __('General', 'joli_faq_seo'),
        'sections' => [
            [
                'name' => 'general',
                'title' => __('General', 'joli_faq_seo'),
                'fields' => [
                    // § Opening mode [Toggle/accordion]
                    [
                        'id' => 'accordion',
                        'title' => __('Accordion', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Accordion mode will automatically close previously opened question', 'joli_faq_seo'),
                            'key' => 'accordion', //js option
                            'var_type' => 'bool',
                            'deactivates' => ['general-open-all-questions']
                        ],
                        'default' => 1,
                        'sanitize' => 'checkbox',
                    ],

                    // § Open first item ?
                    [
                        'id' => 'open-first-question',
                        'title' => __('Open first question', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('First question from FAQ list will be opened by default', 'joli_faq_seo'),
                            'key' => 'openFirstQuestion',
                            'var_type' => 'bool',
                            'deactivates' => ['general-open-all-questions']
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    // § Open all item ?
                    [
                        'id' => 'open-all-questions',
                        'title' => __('Open all questions at once', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('This setting will deactivate the accordion mode but is not relevant for sorting by user engagement', 'joli_faq_seo'),
                            'key' => 'openAllQuestions',
                            'var_type' => 'bool',
                            'deactivates' => ['general-accordion', 'general-open-first-question']
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    // § Show FAQ title ?
                    [
                        'id' => 'show-faq-group-name',
                        'title' => __('Show FAQ group name', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Will display the FAQ group name above the FAQ list', 'joli_faq_seo'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                    // § Show FAQ title ?
                    [
                        'id' => 'show-faq-link',
                        'title' => __('Show copy FAQ link to clipboard icon', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Will display a copy to clipboard icon link on each FAQ on mouse hover', 'joli_faq_seo'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],

            [
                'name' => 'search-bar',
                'title' => __('Search bar', 'joli_faq_seo'),
                'fields' => [
                    // § Show search bar ?
                    [
                        'id' => 'search-bar',
                        'title' => __('Search bar', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Allows the user to perform a real-time search through displayed FAQs', 'joli_faq_seo'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    // § search bar placeholder
                    [
                        'id' => 'search-placeholder',
                        'title' => __('Placeholder', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Text shown to invite the user to searching through FAQs', 'joli_faq_seo'),
                        ],
                        'default' => __('Search FAQs...', 'joli_faq_seo'),
                        'sanitize' => 'text',
                    ],

                    // § Highlight color
                    [
                        'id' => 'search-highlight-color',
                        'title' => __('Highlight color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                            'desc' => __('Matching text will be highlighted as the user types with this color', 'joli_faq_seo'),
                        ],
                        'default' => '#ffff00',
                        'sanitize' => 'Color',
                    ],
                ],
            ],
            [
                'name' => 'sorting',
                'title' => __('Sorting', 'joli_faq_seo'),
                'fields' => [
                    // § Sort by [ position from the editor / user engagement / creation date / alphabetical ]
                    [
                        'id' => 'sort-by',
                        'title' => __('Sort by', 'joli_faq_seo'),
                        'type' => 'select',
                        'args' => [
                            'values' => [
                                'editor-position' => __('Position from the editor (default)', 'joli_faq_seo'),
                                'user-engagement' => __('User engagement (most opened FAQs)', 'joli_faq_seo'),
                                'creation-date' => __('Creation date', 'joli_faq_seo'),
                                'alphabetical' => __('Alphabetical', 'joli_faq_seo'),
                            ],
                            'values_pro' => [
                                'user-engagement',
                            ],
                            'desc' =>  __('FAQ sorting type', 'joli_faq_seo'),
                        ],
                        'default' => 'editor-position',
                    ],

                    // § Sort order [ Asc / Desc ]
                    [
                        'id' => 'sort-order',
                        'title' => __('Sort order', 'joli_faq_seo'),
                        'type' => 'select',
                        'args' => [
                            'values' => [
                                'asc' => __('Ascending (lowest to highest)', 'joli_faq_seo'),
                                'desc' => __('Descending (highest to lowest)', 'joli_faq_seo'),
                            ],
                            'desc' =>  __('FAQ sorting order', 'joli_faq_seo'),
                        ],
                        'default' => 'desc',
                    ],
                ],
            ],
            // [
            //     'name' => 'other',
            //     'title' => __('Other', 'joli_faq_seo'),
            //     'fields' => [
            //         // Wpautop ?
            //     ],
            // ],

        ],
    ],
    [
        'group' => 'seo',
        'label' => __('SEO', 'joli_faq_seo'),
        'sections' => [
            [
                'name' => 'structured-data',
                'title' => __('Structured data markup', 'joli_faq_seo'),
                'fields' => [
                    // Enable JSON-LD output
                    [
                        'id' => 'schema-faq',
                        'title' => __('Schema.org JSON-LD', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'desc' => [
                                __('Will output FAQPage schema.org-compliant JSON-LD structured data in the page. It is recommanded to keep it activated.', 'joli_faq_seo'),
                                __('More information about FAQPage schema <a href="https://developers.google.com/search/docs/data-types/faqpage" target="_blank">here</a>', 'joli_faq_seo'),
                                __('Check the validity of your page\'s structured data <a href="https://search.google.com/test/rich-results" target="_blank">here</a>', 'joli_faq_seo'),
                            ],
                        ],
                        'default' => 1,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],
            [
                'name' => 'emojis',
                'title' => __('Emojis', 'joli_faq_seo'),
                'fields' => [
                    // § Enable emojis
                    [
                        'id' => 'enable-emojis',
                        'title' => __('Enable emojis', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'desc' => [
                                __('Will display an emoji before the question (if any selected from the editor)', 'joli_faq_seo'),
                                __('This may enhance your visibility in the SERPs. However, <span style="color:orange;">it is entirely up to Google to actually show emojis or not</span>.', 'joli_faq_seo')
                            ],
                        ],
                        'default' => 1,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],
        ],
    ],
    [
        'group' => 'style',
        'label' => __('Style', 'joli_faq_seo'),
        'sections' => [
            [
                'name' => 'layout',
                'title' => __('Layout', 'joli_faq_seo'),
                'fields' => [
                    // § Theme
                    [
                        'id' => 'flat-layout',
                        'title' => __('Flat layout', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'data' => [
                                'joli-refresh' => 'true',
                                // 'disable-related' => 'true',
                                // 'disable-related-id' => 'theme',
                            ],
                            'pro' => true,
                            'desc' => [
                                __('The flat layout outputs the FAQs as regular content.', 'joli_faq_seo'),
                                __('There is no accordion or toggle in this mode.', 'joli_faq_seo'),
                                __('Insights are not working in this mode.', 'joli_faq_seo'),
                            ],
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],
            [
                'name' => 'theme',
                'title' => __('Theme', 'joli_faq_seo'),
                'fields' => [
                    // § Theme
                    [
                        'id' => 'theme',
                        'title' => __('Theme', 'joli_faq_seo'),
                        'type' => 'select',
                        // 'default' => 'gg-math-plus',
                        'args' => [
                            'classes' => 'joli-update-preview',
                            'data' => [
                                'joli-refresh' => 'true',
                                'object' => 'theme',
                                'el' => 'select',
                                'prefix' => '--jfaq-',
                            ],
                            // 'desc' => sprintf( '<span style="color:red;">%s</span>', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli_faq_seo') ),
                            'values' => [
                                'theme-1' => __('Theme 1', 'joli_faq_seo'),
                                'theme-2' => __('Theme 2', 'joli_faq_seo'),
                                'theme-3' => __('Theme 3', 'joli_faq_seo'),
                                'theme-4' => __('Theme 4', 'joli_faq_seo'),
                            ],
                            'values_pro' => [
                                'theme-2',
                                'theme-3',
                                'theme-4',
                            ],
                        ],
                        'default' => 'theme-1',
                    ],
                    [
                        'id' => 'disable-custom-styles',
                        'title' => __('Disable custom styles', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            // 'pro' => true,
                            'desc' => [
                                __('This will deactivate any custom styles from the following and keep the select Theme\'s defaults styles:', 'joli_faq_seo'),
                                __('TOGGLE STYLE', 'joli_faq_seo'),
                                __('TOGGLE OPENED STATE STYLE', 'joli_faq_seo'),
                                __('QUESTION STYLES', 'joli_faq_seo'),
                                __('QUESTION STYLES (OPENED STATE)', 'joli_faq_seo'),
                                __('ANSWER CONTAINER STYLES', 'joli_faq_seo'),
                                __('ANSWER STYLES', 'joli_faq_seo'),
                            ],
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                    // § Theme style override
                ],
                [
                    'name' => 'faq-container',
                    'title' => __('FAQ container', 'joli_faq_seo'),
                    'fields' => [
                        // § Rounded corner
                        // § Shadow
                    ],
                ],
            ],
        ],
    ],
    [
        'group' => 'toggle',
        'label' => __('Toggle', 'joli_faq_seo'),
        'sections' => [
            [
                'name' => 'toggle-button-styles',
                'title' => __('Toggle button styles', 'joli_faq_seo'),
                'fields' => [
                    // § Show toggle button
                    [
                        'id' => 'show-toggle',
                        'title' => __('Show toggle', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Will show a toggle button with each FAQ', 'joli_faq_seo'),
                        ],
                        'default' => 1,
                        'sanitize' => 'checkbox',
                    ],

                    // § Toggle icon [ style1, 2, 3… ]
                    [
                        'id' => 'toggle-button-icon-style',
                        'title' => __('Toggle button icon style', 'joli_faq_seo'),
                        'type' => 'radioicon',
                        'default' => 'toggle-1',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            // 'classes' => '--joli-fn',
                            // 'data' => [
                            //     'object' => 'toggle',
                            //     'el' => 'input',
                            //     'prefix' => '--jfaq-',
                            // ],
                            // 'desc' => sprintf( '<span style="color:red;">%s</span>', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli_faq_seo') ),
                            'values' => [
                                'toggle-1' => '<div class="jfaq-wrap"><div class="jfaq--group --jfaq-theme-x --jfaq-toggle-1"><div class="jfaq--toggle-wrap"><div class="jfaq--toggle"></div></div></div></div>',
                                'toggle-2' => '<div style="background: url(' . JFAQ()->url('assets/admin/img/toggle-2.png') . ') no-repeat;height: 100%;width: 100%;background-position: center;"></div>',
                                'toggle-3' => '<div style="background: url(' . JFAQ()->url('assets/admin/img/toggle-2.png') . ') no-repeat;height: 100%;width: 100%;background-position: center;"></div>',
                                'toggle-4' => '<div style="background: url(' . JFAQ()->url('assets/admin/img/toggle-4.png') . ') no-repeat;height: 100%;width: 100%;background-position: center;"></div>',
                            ],
                            'values_pro' => [
                                'toggle-2',
                                'toggle-3',
                                'toggle-4',
                            ],

                        ],
                    ],

                    // § Show toggle button
                    [
                        'id' => 'toggle-rotate-180',
                        'title' => __('180° rotation effect', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Use an alternative effect for the toggle (if applicable)', 'joli_faq_seo'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    // § Toggle position [left / right ]
                    [
                        'id' => 'toggle-position',
                        'title' => __('Toggle position', 'joli_faq_seo'),
                        'type' => 'select',
                        'default' => 'right',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            // 'desc' => sprintf( '<span style="color:red;">%s</span>', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli_faq_seo') ),
                            'values' => [
                                'right' => __('Right (default)', 'joli_faq_seo'),
                                'left' => __('Left', 'joli_faq_seo'),
                            ],

                        ],
                    ],

                    // § Toggle size [xs /s / m / l]
                    [
                        'id' => 'toggle-size',
                        'title' => __('Toggle size', 'joli_faq_seo'),
                        'type' => 'select',
                        'default' => 'm',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            // 'desc' => sprintf( '<span style="color:red;">%s</span>', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli_faq_seo') ),
                            'values' => [
                                'xs' => __('Extra Small', 'joli_faq_seo'),
                                's' => __('Small', 'joli_faq_seo'),
                                'm' => __('Medium (default)', 'joli_faq_seo'),
                                'l' => __('Large', 'joli_faq_seo'),
                            ],

                        ],
                    ],

                    // § Toggle border
                    [
                        'id' => 'toggle-border',
                        'title' => __('Toggle border', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Will surround the toggle button with a border', 'joli_faq_seo'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    // § Toggle border/background style [square / rounded / circle ]
                    [
                        'id' => 'toggle-background-shape',
                        'title' => __('Toggle background shape', 'joli_faq_seo'),
                        'type' => 'select',
                        'default' => 'circle',
                        'args' => [
                            'pro' => true,
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => sprintf('Background shape will affect background color and toggle border (if any).', __('Any changes in any styling below (title, headings, colors etc) will override theme defaults', 'joli_faq_seo')),
                            'values' => [
                                'circle' => __('Circle (default)', 'joli_faq_seo'),
                                'rounded' => __('Rounded', 'joli_faq_seo'),
                                'square' => __('Square', 'joli_faq_seo'),
                            ],

                        ],
                    ],
                ],
            ],
            [
                'name' => 'toggle-style',
                'title' => __('Toggle style', 'joli_faq_seo'),
                'fields' => [
                    // § Toggle color
                    [
                        'id' => 'toggle-color',
                        'title' => __('Color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color',
                    ],

                    // § Toggle border color
                    [
                        'id' => 'toggle-border-color',
                        'title' => __('Border color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],

                    // § Toggle background color
                    [
                        'id' => 'toggle-background-color',
                        'title' => __('Background color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],
                ],
            ],
            [
                'name' => 'opened-state-style',
                'title' => __('Toggle opened state style', 'joli_faq_seo'),
                'fields' => [
                    // § Toggle color
                    [
                        'id' => 'toggle-opened-color',
                        'title' => __('Color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],

                    // § Toggle border color
                    [
                        'id' => 'toggle-opened-border-color',
                        'title' => __('Border color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],

                    // § Toggle background color
                    [
                        'id' => 'toggle-opened-background-color',
                        'title' => __('Background color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],
                ],
            ],
        ],
    ],
    [
        'group' => 'questions',
        'label' => __('Questions', 'joli_faq_seo'),
        'sections' => [
            [
                'name' => 'question-options',
                'title' => __('Question options', 'joli_faq_seo'),
                'fields' => [
                    // § Uppercase ?
                    [
                        'id' => 'questions-uppercase',
                        'title' => __('Uppercase', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Transforms the questions to uppercase (ex: "What is it ?" becomes "WHAT IS IT ?").', 'joli_faq_seo'),
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],

                    // § Question tag [div / p / span / h2 /h3 /h4 / h5 /h6 ]
                    [
                        'id' => 'questions-tag',
                        'title' => __('Questions tag', 'joli_faq_seo'),
                        'type' => 'select',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            'values' => [
                                'div' => 'div',
                                'p' => 'p',
                                'span' => 'span',
                                'h2' => 'h2',
                                'h3' => 'h3',
                                'h4' => 'h4',
                                'h5' => 'h5',
                                'h6' => 'h6',
                            ],
                            'desc' =>  __('HTML tag used for each FAQ question', 'joli_faq_seo'),
                        ],
                        'default' => 'h3',
                    ],
                ],
            ],
            [
                'name' => 'question-styles',
                'title' => __('Question styles ', 'joli_faq_seo'),
                'fields' => [

                    // § Color
                    [
                        'id' => 'questions-color',
                        'title' => __('Color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],
                    // § Background Color
                    [
                        'id' => 'questions-background-color',
                        'title' => __('Background color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],

                    // § Font-size
                    [
                        'id' => 'questions-font-size',
                        'title' => __('Font size ', 'joli_faq_seo'),
                        'type' => 'unitinput',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Font size of the questions.', 'joli_faq_seo'),
                            'placeholder' => '1.1',
                            'values' => [
                                'em' => 'em',
                                'px' => 'px',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Unit'
                    ],

                    // § Font-weight
                    [
                        'id' => 'questions-font-weight',
                        'title' => __('Font weight', 'joli_faq_seo'),
                        'type' => 'select',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            // 'class' => 'tab-general',
                            'values' => $font_weights
                        ],
                        'default' => 'none',
                    ],

                    // § Vertical padding
                    //selectable unit field

                    // § Horizontal padding
                    // § Border-bottom ?
                    // § Margin-bottom
                ],
            ],
            [
                'name' => 'question-styles-opened',
                'title' => __('Question styles (opened state)', 'joli_faq_seo'),
                'fields' => [

                    // § Color
                    [
                        'id' => 'questions-opened-color',
                        'title' => __('Color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],
                    // § Background Color
                    [
                        'id' => 'questions-opened-background-color',
                        'title' => __('Background color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color',
                    ],

                    // § Font-size
                    [
                        'id' => 'questions-opened-font-size',
                        'title' => __('Font size ', 'joli_faq_seo'),
                        'type' => 'unitinput',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Font size of the questions.', 'joli_faq_seo'),
                            'placeholder' => '1.1',
                            'values' => [
                                'em' => 'em',
                                'px' => 'px',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Unit'
                    ],

                    // § Font-weight
                    [
                        'id' => 'questions-opened-font-weight',
                        'title' => __('Font weight', 'joli_faq_seo'),
                        'type' => 'select',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            // 'class' => 'tab-general',
                            'values' => $font_weights,
                        ],
                        'default' => 'none',
                    ],

                    // § Vertical padding
                    //selectable unit field

                    // § Horizontal padding
                    // § Border-bottom ?
                    // § Margin-bottom
                ],
            ],
        ],
    ],
    [
        'group' => 'answers',
        'label' => __('Answers', 'joli_faq_seo'),
        'sections' => [
            [
                'name' => 'answer-container',
                'title' => __('Answer container', 'joli_faq_seo'),
                'fields' => [
                    // § Background Color
                    [
                        'id' => 'answer-container-background-color',
                        'title' => __('Background color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            'desc' => __('Answer container background color is under the answer. You can create effects by setting a different background color to the Answer and add a <strong>margin</strong> to the Answer.', 'joli_faq_seo'),
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],
                ],
            ],
            [
                'name' => 'answer',
                'title' => __('Answer', 'joli_faq_seo'),
                'fields' => [
                    // § Border radius
                    // § Background color
                    [
                        'id' => 'answer-background-color',
                        'title' => __('Background color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],

                    // § Text color
                    [
                        'id' => 'answer-color',
                        'title' => __('Color', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            // 'class' => 'tab-appearance',
                            'placeholder' => 'rgba(255, 255, 255, 0.8)',
                            'data' => ['alpha-enabled' => 'true', 'joli-refresh' => 'true'],
                            'classes' => 'jfaq-color-picker', //adds color picker
                        ],
                        // 'default' => '#ffffff',
                        'sanitize' => 'Color'
                    ],

                    // § Vertical margin
                    [
                        'id' => 'answer-font-size',
                        'title' => __('Font size', 'joli_faq_seo'),
                        'type' => 'unitinput',
                        'args' => [
                            'data' => ['joli-refresh' => 'true'],
                            'desc' => __('Font size of the questions.', 'joli_faq_seo'),
                            'placeholder' => '1.1',
                            'values' => [
                                'em' => 'em',
                                'px' => 'px',
                                'percent' => '%',
                            ],
                        ],
                        // 'default' => '#39383a',
                        'sanitize' => 'Unit'
                    ],

                    // § Horizontal margin
                    // § Vertical padding
                    // § Horizontal padding
                ],
            ],
        ],
    ],
    [
        'group' => 'woocommerce',
        'label' => __('WooCommerce', 'joli_faq_seo'),
        'sections' => [
            [
                'name' => 'woo-integration',
                'title' => __('WooCommerce integration', 'joli_faq_seo'),
                'fields' => [
                    // Enable JSON-LD output
                    [
                        'id' => 'woo-enable-faqs',
                        'title' => __('Enable FAQs', 'joli_faq_seo'),
                        'type' => 'switch',
                        'args' => [
                            'pro' => true,
                            'desc' => [
                                __('Enables site-wide WooCommerce Product FAQs integration. If disabled, all product FAQs will not show.', 'joli_faq_seo'),
                                __('The FAQs will show through a new tab above the product description.', 'joli_faq_seo'),
                            ],
                        ],
                        'default' => 0,
                        'sanitize' => 'checkbox',
                    ],
                ],
            ],
            [
                'name' => 'woo-tab-options',
                'title' => __('Tab options', 'joli_faq_seo'),
                'fields' => [
                    // § Enable emojis
                    [
                        'id' => 'woo-tab-name',
                        'title' => __('FAQ tab name', 'joli_faq_seo'),
                        'type' => 'text',
                        'args' => [
                            'pro' => true,
                            // 'data' => ['joli-refresh' => 'true'],
                            'placeholder' => __('FAQ', 'joli_faq_seo'),
                            'desc' => __('Name of the FAQ tab on the product page.', 'joli_faq_seo'),
                        ],
                        'default' => __('FAQ', 'joli_faq_seo'),
                        'sanitize' => 'text',
                    ],
                ],
            ],
        ],
    ],
];
