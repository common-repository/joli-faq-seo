<?php

do_action( 'joli_faq_seo_before_faq', ( isset( $faq_group['ID'] ) ? $faq_group['ID'] : null ) );
echo $custom_styles;
?><div class="jfaq-wrap">
    <div class="jfaq--group <?php 
echo $data['group_classes'];
?>" data-id="<?php 
echo jfaq_salt( ( isset( $faq_group['ID'] ) ? $faq_group['ID'] : -1 ) );
?>">
        <div class="jfaq--head">
            <?php 
if ( isset( $options['show_faq_group_name'] ) && $options['show_faq_group_name'] && isset( $faq_group['title'] ) ) {
    ?>
                <?php 
    echo apply_filters( 'joli_faq_seo_faq_group_name', $faq_group['title'] );
    ?>
            <?php 
}
?>
        </div>
        <?php 
?>
        <div class="jfaq--content">
            <?php 
?>
            <ul class="jfaq--faqs" style="margin:0;">
                <?php 
foreach ( $faqs as $faq ) {
    ?>
                    <?php 
    if ( jfaq_xy()->is_free_plan() && isset_or_null( $faq['gut'] ) === 1 ) {
        continue;
    }
    ?>
                    <?php 
    $faq_id = apply_filters( 'joli_faq_seo_faq_id', 'faq-' . jfaq_salt( $faq['ID'] ), $faq );
    ?>
                    <li>
                        <div class="jfaq--item" id="<?php 
    echo $faq_id;
    ?>" data-id="<?php 
    echo jfaq_salt( $faq['ID'] );
    ?>">
                            <?php 
    do_action( 'joli_faq_seo_before_question', $faq );
    ?>
                            <div class="jfaq--question">
                                <?php 
    do_action( 'joli_faq_seo_before_question_inner', $faq );
    ?>
                                <?php 
    echo ( isset( $options['show_toggle'] ) && $options['show_toggle'] && $options['toggle_position'] == 'left' ? $data['toggle_html'] : '' );
    ?>
                                <<?php 
    echo $options['questions_tag'];
    ?> class="jfaq--title"><span class="jfaq--title-text"><?php 
    echo apply_filters( 'joli_faq_seo_faq_question', $faq['title'] );
    ?></span></<?php 
    echo $options['questions_tag'];
    ?>>
                                <?php 
    ?>
                                <?php 
    echo ( isset( $options['show_toggle'] ) && $options['show_toggle'] && $options['toggle_position'] != 'left' ? $data['toggle_html'] : '' );
    ?>
                                <?php 
    do_action( 'joli_faq_seo_after_question_inner', $faq );
    ?>
                            </div>
                            <?php 
    do_action( 'joli_faq_seo_after_question', $faq );
    ?>
                            <div class="jfaq--answer">
                                <?php 
    do_action( 'joli_faq_seo_before_answer_inner', $faq );
    ?>
                                <div class="jfaq--answer-content"><?php 
    echo apply_filters( 'joli_faq_seo_faq_answer', $faq['content'] );
    ?></div>
                                <?php 
    do_action( 'joli_faq_seo_after_answer_inner', $faq );
    ?>
                            </div>
                        </div>
                    </li>
                <?php 
}
?>
            </ul>
        </div>
    </div>
</div>
<?php 
if ( isset( $data['schema'] ) && $data['schema'] ) {
    print $data['schema'];
}
do_action( 'joli_faq_seo_after_faq', ( isset( $faq_group['ID'] ) ? $faq_group['ID'] : null ) );
?>
<!-- FAQ generated with Joli FAQ SEO wpjoli.com/joli-faq-seo -->