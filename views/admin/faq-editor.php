<?php

$free = isset_or_null( $pro ) === false;
$pro_class = ( $free ? ' joli-pro' : '' );
$pro_disabled = ( $free ? 'disabled ' : '' );
// var_dump($group_category);
?>
<div class="wrap">
    <h1 class="h1-title"></h1>
    <?php 
settings_errors();
?>

    <div class="jfaq-editor-wrap">
        <div id="jfaq-overlay">
            <p style="font-size: 1.5em;"><?php 
echo __( "Updating data, please wait...", "joli_faq_seo" );
?><span class="spinner is-active"></span></p>
        </div>
        <div class="jfaq-header">
            <div id="jfaq-group-category">
                <a id="gc-0" class="jfaq-gc default<?php 
echo ( !isset_or_null( $group_category ) ? ' active' : '' );
?>" href="<?php 
echo $jfaq_editor_url;
?>"><?php 
echo __( "Default", "joli_faq_seo" );
?></a>
                <?php 
?>
                <span id="new-category-panel" class="--hidden">
                    <input type="text" id="new-category-name">
                    <button id="add-new-faq-group-cat-btn" class="button button-primary button-large">
                        <span><?php 
echo __( "Add", "joli_faq_seo" );
?></span>
                    </button>
                    <button id="cancel-add-new-faq-group-cat-btn" class="button button-secondary button-large">
                        <span><?php 
echo __( "Cancel", "joli_faq_seo" );
?></span>
                    </button>
                </span>
                <span id="category-controls">
                    <button id="add-faq-group-cat-btn" class="button button-primary button-large">
                        <span><?php 
echo __( "Add category", "joli_faq_seo" );
?></span>
                    </button>
                    <button id="delete-faq-group-cat-btn" class="button button-danger button-large" <?php 
echo ( $group_category == '' ? ' disabled' : '' );
?>>
                        <span><?php 
echo __( "Delete category", "joli_faq_seo" );
?></span>
                    </button>
                    <span class="joli-field-info dashicons dashicons-info-outline" style="margin-top:5px;"></span>
                    <div class="joli-info-bubble" style="z-index:2;text-align:left; min-width: 460px;left: calc(100% + 10px);transform: translateY(-10px);top: 0;">
                        <p><?php 
echo __( 'Add custom categories to organize your FAQs.', 'joli-table-of-contents' );
?></p>
                        <p><?php 
echo __( 'Each category contains its own set of FAQs & FAQ Groups.', 'joli-table-of-contents' );
?></p>
                        <p><?php 
echo __( 'Use the Bulk editing mode to transfer FAQ Groups from one category to another.', 'joli-table-of-contents' );
?></p>
                        <p><?php 
echo __( 'Deleting a category will automatically revert all the FAQs/FAQ Groups inside to the Default category.', 'joli-table-of-contents' );
?></p>
                    </div>
                </span>
            </div>
        </div>
        <div class="jfaq-body">
            <div class="jfaq-groups">
                <h2><?php 
echo __( "FAQ Groups", "joli_faq_seo" );
?></h2>
                <div class="jfaq-group-controls">
                    <div>
                        <button <?php 
echo $pro_disabled;
?>class="button jfaq-dash-btn<?php 
echo $pro_class;
?>" id="jfaq-bulk-edit"><?php 
echo __( "Bulk editing", "joli_faq_seo" );
?></button>
                        <button <?php 
echo $pro_disabled;
?>class="button jfaq-dash-btn<?php 
echo $pro_class;
?>" id="jfaq-collapse-all"><?php 
echo __( "Collapse all", "joli_faq_seo" );
?></button>
                        <button <?php 
echo $pro_disabled;
?>class="button jfaq-dash-btn<?php 
echo $pro_class;
?>" id="jfaq-expand-all"><?php 
echo __( "Expand all", "joli_faq_seo" );
?></button>
                        <button <?php 
echo $pro_disabled;
?>class="button jfaq-dash-btn<?php 
echo $pro_class;
?>" id="jfaq-toggle-compact"><?php 
echo __( "Compact view", "joli_faq_seo" );
?></button>
                    </div>
                    <div id="bulk-edit-controls">
                        <select name="target-category" id="target-category">
                            <option value=""><?php 
echo __( "Move to category", "joli_faq_seo" );
?></option>
                            <option value="0"><?php 
echo __( "Default", "joli_faq_seo" );
?></option>
                            <?php 
?>
                        </select>
                        <button class="button" id="move-to-category-apply">Apply</button>
                    </div>
                </div>
                <div class="jfaq-info">
                    <p><?php 
echo __( "A FAQ Group contains multiple FAQs and can be displayed via shortcode.", "joli_faq_seo" );
?></p>
                    <p><?php 
echo __( "Start by adding a FAQ Group and add FAQs within the group.", "joli_faq_seo" );
?></p>
                    <p><?php 
echo __( "Create different categories to organize your FAQs.", "joli_faq_seo" );
?></p>
                </div>
                <section class="jfaq-group-list">

                    <!-- <div class="jfaq-box jfaq-group --opened">
                    <div class="jb-header">
                        Fiches produits
                    </div>
                    <div class="jb-content">
                        <div class="jb-body">
                            <div class="jfaq-item">
                                <div class="jfaq-controls jfaq-controls-left">
                                    <div class="jfaq-control">
                                        <i class="gg-more-vertical-alt"></i>
                                    </div>
                                </div>
                                <div class="jfaq-item-title">How much for delivery ?</div>
                                <div class="jfaq-controls jfaq-controls-right">
                                    <div class="jfaq-control">
                                        <i class="gg-pen"></i>
                                    </div>
                                    <div class="jfaq-control">
                                        <i class="gg-trash"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="jfaq-item">
                                <div class="jfaq-controls jfaq-controls-left">
                                    <div class="jfaq-control">
                                        <i class="gg-more-vertical-alt"></i>
                                    </div>
                                </div>
                                <div class="jfaq-item-title">How much for shiupping ?</div>
                                <div class="jfaq-controls jfaq-controls-right">
                                    <div class="jfaq-control">
                                        <i class="gg-pen"></i>
                                    </div>
                                    <div class="jfaq-control">
                                        <i class="gg-trash"></i>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="jb-footer">
                            <div class="jfaq--add-faq">
                                <i class="gg-add"></i>
                                <span><?php 
echo __( "Add FAQ", "joli_faq_seo" );
?></span>
                            </div>
                        </div>
                    </div>
                </div> -->

                </section>
                <div class="jfaq-group-footer">
                    <button id="add-faq-group-btn" class="button button-primary button-large">
                        <!-- <i class="gg-add"></i> -->
                        <span><?php 
echo __( "Add FAQ Group", "joli_faq_seo" );
?></span>
                    </button>
                </div>
            </div>
            <aside class="jfaq-faqs">
                <h2><?php 
echo __( "FAQs", "joli_faq_seo" );
?></h2>
                <div class="jfaq-box jfaq-list">
                    <!-- <div class="jb-header">
                    <?php 
echo __( "All FAQs", "joli_faq_seo" );
?>
                </div> -->
                    <div class="jb-content">
                        <div class="jb-body">
                        </div>

                        <div class="jb-footer">
                            <button id="add-faq-btn" class="jfaq--add-faq">
                                <i class="gg-add"></i>
                                <span><?php 
echo __( "Add FAQ", "joli_faq_seo" );
?></span>
                            </button>
                            <button <?php 
echo $pro_disabled;
?>id="add-faq-gut-btn" class="jfaq--add-faq-gut<?php 
echo $pro_class;
?>">
                                <i class="gg-add"></i>
                                <span><?php 
echo __( "Add Gutenberg FAQ", "joli_faq_seo" );
?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
    <?php 
?>
</div>

<!-- ADD/EDIT FAQ ITEM TEMPLATE -->
<script id="faq-item-edit-template" type="text/x-handlebars-template">
    <div id="jfaq-item-edit-{{ID}}" class="jfaq-item-edit --{{mode}}" data-faq-id="{{ID}}">
        <div class="jie-header">
            <div class="jfaq-item-title">
                <?php 
?>
                <input type="text" class="jfaq-title-input" name="{{ID}}-title" id="{{ID}}-title" value="{{title}}" placeholder="<?php 
echo __( "FAQ title", "joli_faq_seo" );
?>" >
            </div>
            <div class="jfaq-controls-right">
                <div id="spinner-{{ID}}" class="spinner"></div>
                <button id="jfaq-item-edit-cancel-{{ID}}" class="jfaq-item-edit-cancel button button-small button-link"><i class="gg-close"></i><?php 
echo __( "Cancel", "joli_faq_seo" );
?></button>
                <button id="jfaq-item-edit-save-{{ID}}" class="jfaq-item-edit-save button button-small" data-id="{{ID}}"><i class="gg-check"></i><?php 
echo __( "Save", "joli_faq_seo" );
?></button>
            </div>
        </div>
        <div class="jie-body">
            <textarea name="jfaq-tmce-{{ID}}" id="jfaq-tmce-{{ID}}" placeholder="<?php 
echo __( "FAQ Answer", "joli_faq_seo" );
?>">{{content}}</textarea>
        </div>
    </div>
</script>


<!-- ALL FAQ LIST TEMPLATE -->
<script id="all-faqs-template" type="text/x-handlebars-template">
    {{#each this}}
        <div id="jfaq-item-{{ID}}" class="jfaq-item{{#if gut}} --gut{{/if}}" data-faq-id="{{ID}}">
            <div class="jfaq-controls-left">
                <div class="jfaq-control jfaq-item-handle">
                    <i class="gg-more-vertical-alt"></i>
                </div>
            </div>
            <div class="jfaq-item-title-wrap">
                <?php 
?>
                <div class="jfaq-item-title">{{title}}</div>
            </div>
            <input type="hidden" class="faq-content" id="faq-content-{{ID}}" name="faq-content-{{ID}}" value="{{content}}">
            <div class="jfaq-controls-right">
                <button class="jfaq-control jfaq--duplicate-faq" title="<?php 
echo __( "Duplicate FAQ", "joli_faq_seo" );
?>">
                    <i class="gg-duplicate"></i>
                </button>
                <button class="jfaq-control jfaq--edit-faq" title="<?php 
echo __( "Edit FAQ with TinyMCE editor", "joli_faq_seo" );
?>">
                    <i class="gg-pen"></i>
                </button>
                <?php 
?>
                <button class="jfaq-control jfaq--delete-faq" title="<?php 
echo __( "Delete FAQ (confirmation required)", "joli_faq_seo" );
?>">
                    <i class="gg-trash"></i>
                </button>
            </div>
            <div class="jfaq-spinner">
                <button class="jfaq-control">
                    <i class="spinner is-active"></i>
                </button>
            </div>
        </div>
    {{/each}}
</script>

<!-- SINGLE FAQ ITEM TEMPLATE -->
<!-- <script id="faq-item-template" type="text/x-handlebars-template">
    <div id="jfaq-item-{{ID}}" class="jfaq-item" data-faq-id="{{ID}}">
            <div class="jfaq-controls-left">
                <div class="jfaq-control jfaq-item-handle">
                    <i class="gg-more-vertical-alt"></i>
                </div>
            </div>            
            <div class="jfaq-item-title-wrap">
                <?php 
?>
                <div class="jfaq-item-title">{{title}}</div>
            </div>
            <input type="hidden" class="faq-content" id="faq-content-{{ID}}" name="faq-content-{{ID}}" value="{{content}}">
            <?php 
?>
            <div class="jfaq-controls-right">
                <button class="jfaq-control jfaq--edit-faq">
                    <i class="gg-pen"></i>
                </button>
                <button class="jfaq-control jfaq--delete-faq" title="<?php 
echo __( "Delete FAQ", "joli_faq_seo" );
?>">
                    <i class="gg-trash"></i>
                </button>
            </div>
            <div class="jfaq-spinner">
                <button class="jfaq-control">
                    <i class="spinner is-active"></i>
                </button>
            </div>
        </div>  
</script> -->

<!-- FAQ GROUP TEMPLATE -->
<script id="faq-group-template" type="text/x-handlebars-template">
    {{#each this}}
        <div id="jfaq-group-{{ID}}" class="jfaq-box jfaq-group{{#unless collapsed}} --opened{{/unless}}{{#if mode}} --{{mode}}{{/if}}{{#if isDisabled}} --faq-group-disabled{{/if}}" data-faq-group-id="{{ID}}" data-collapsed="{{collapsed}}">
            <div class="jb-header">
                <div class="jfaq-group-controls-left">
                    <div class="jfaq-control bulk-edit-checkbox">
                        <input type="checkbox" name="bulk-edit-checkboxes" data-faq-group-id="{{ID}}">
                    </div>
                    <div class="jfaq-control jfaq-group-handle">
                        <i class="gg-more-vertical-alt"></i>
                    </div>
                </div>
                <div class="jfaq-disable-faq-group" title="<?php 
echo __( "Disable FAQ Group (FAQ Group will not show on the front-end).", "joli_faq_seo" );
?>">
                    <label class="jfaq-switch" for="jfaq-disable-group-{{ID}}">
                        <input type="checkbox" id="jfaq-disable-group-{{ID}}" class="jfaq-checkbox" data-linkedfield="jfaq-disable-group-{{ID}}" {{#unless isDisabled}} checked{{/unless}} />
                        <span class="jfaq-slider round"></span>
                    </label>
                    <input type="hidden" class="jfaq-group--is-disabled" id="check_jfaq-disable-group-{{ID}}" name="check_jfaq-disable-group-{{ID}}" value="{{#if isDisabled}}1{{else}}0{{/if}}" />
                </div>
                <div class="jfaq-group-header-content">
                    <div class="jfaq-group-info">
                        <span class="jfaq-group-title">{{title}}</span>
                        <span class="jfaq-group-shortcode" title="Double click to copy shortcode to clipboard" data-shortcode="[<?php 
echo JFAQ()::DOMAIN;
?> id='{{ID}}']">[<?php 
echo JFAQ()::DOMAIN;
?> id='{{ID}}']</span>
                    </div>
                    <input type="text" class="jfaq-group-title-input" value="{{title}}" placeholder="<?php 
echo __( "FAQ Group name", "joli_faq_seo" );
?>">
                </div>
                <div class="jfaq-group-controls-right">
                    <button class="jfaq-control jfaq--edit-faq-group" title="<?php 
echo __( "Edit FAQ Group", "joli_faq_seo" );
?>">
                        <i class="gg-pen"></i>
                    </button>
                    <button class="jfaq-control jfaq--delete-faq-group" title="<?php 
echo __( "Delete FAQ Group", "joli_faq_seo" );
?>">
                        <i class="gg-trash"></i>
                    </button>
                </div>
                <div class="jfaq-group-edit-controls-right">
                    <div id="spinner-{{ID}}" class="spinner"></div>
                    <button id="jfaq-group-edit-cancel-{{ID}}" class="jfaq-group-edit-cancel button button-small button-link"><i class="gg-close"></i><?php 
echo __( "Cancel", "joli_faq_seo" );
?></button>
                    <button id="jfaq-group-edit-save-{{ID}}" class="jfaq-group-edit-save button button-small" data-id="{{ID}}"><i class="gg-check"></i><?php 
echo __( "Save", "joli_faq_seo" );
?></button>
                </div>
            </div>
            <div class="jb-content">
                <div class="jb-body">
                    <div class="jfaq-info jfaq-drop">
                        <p><?php 
echo __( "Drop FAQs here or create a new one.", "joli_faq_seo" );
?></p>
                    </div>
                    {{#each faqs}}
                        <div id="jfaq-item-{{../ID}}-{{ID}}" class="jfaq-item{{#if (faqDisabled ../disabled ID)}} --faq-disabled{{/if}}{{#if gut}} --gut{{/if}}" data-faq-id="{{ID}}">
                            <?php 
?>
                            <div class="jfaq-controls-left">
                                <div class="jfaq-control jfaq-item-handle">
                                    <i class="gg-more-vertical-alt"></i>
                                </div>
                            </div>
                            <div class="jfaq-disable-faq" title="<?php 
echo __( "Disable FAQ from this group (FAQ will not show on the front-end).", "joli_faq_seo" );
?>">
                                <label class="jfaq-switch" for="jfaq-disable-{{../ID}}-{{ID}}">
                                    <input type="checkbox" id="jfaq-disable-{{../ID}}-{{ID}}" class="jfaq-checkbox" data-linkedfield="jfaq-disable-{{../ID}}-{{ID}}" {{#unless (faqDisabled ../disabled ID)}} checked{{/unless}} />
                                    <span class="jfaq-slider round"></span>
                                </label>
                                <input type="hidden" class="jfaq-is-disabled" id="check_jfaq-disable-{{../ID}}-{{ID}}" name="check_jfaq-disable-{{../ID}}-{{ID}}" value="{{faqDisabled ../disabled ID}}" />
                            </div>
                            <div class="jfaq-item-title-wrap">
                                <?php 
?>
                                <div class="jfaq-item-title">{{title}}</div>
                            </div>
                            <input type="hidden" class="faq-content" id="faq-content-{{ID}}" name="faq-content-{{ID}}" value="{{content}}">

                            <div class="jfaq-controls-right">
                                <button class="jfaq-control jfaq--duplicate-faq" title="<?php 
echo __( "Duplicate FAQ", "joli_faq_seo" );
?>">
                                    <i class="gg-duplicate"></i>
                                </button>
                                <button class="jfaq-control jfaq--edit-faq" title="<?php 
echo __( "Edit FAQ with TinyMCE editor", "joli_faq_seo" );
?>">
                                    <i class="gg-pen"></i>
                                </button>
                                <?php 
?>
                                <button class="jfaq-control jfaq--delete-faq" title="<?php 
echo __( "Remove FAQ from this group", "joli_faq_seo" );
?>">
                                    <i class="gg-close"></i>
                                </button>
                            </div>
                            <div class="jfaq-spinner">
                                <button class="jfaq-control">
                                    <i class="spinner is-active"></i>
                                </button>
                            </div>
                        </div>
                    {{/each}}
                </div>
                <div class="jb-footer">
                    <button class="jfaq--add-faq">
                        <i class="gg-add"></i>
                        <span><?php 
echo __( "Add FAQ", "joli_faq_seo" );
?></span>
                    </button>
                    <button <?php 
echo $pro_disabled;
?>class="jfaq--add-faq-gut<?php 
echo $pro_class;
?>">
                        <i class="gg-add"></i>
                        <span><?php 
echo __( "Add Gutenberg FAQ", "joli_faq_seo" );
?></span>
                    </button>
                </div>
            </div>
        </div>
    {{/each}}
</script>