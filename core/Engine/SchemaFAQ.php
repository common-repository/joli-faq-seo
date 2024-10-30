<?php

/**
 * @package jolifaq
 */

namespace WPJoli\JoliFAQ\Engine;

// use WPJoli\JoliFAQ\Engine\ContentProcessing;

class SchemaFAQ
{
    private $has_emoji;
    private $faqs;
    private $schema;
    // {
    //     "@context":"https://schema.org",
    //     "@type":"FAQPage",
    //     "mainEntity":[
    //         {
    //             "@type":"Question",
    //             "name":"What is the return policy?",
    //             "acceptedAnswer":{
    //                 "@type":"Answer",
    //                 "text":"Most unopened items in new condition and returned within <strong>90 days</strong> will receive a refund or exchange. Some items have a modified return policy noted on the receipt or packing slip. Items that are opened or damaged or do not have a receipt may be denied a refund or exchange. Items purchased online or in-store may be returned to any store.<br /><p>Online purchases may be returned via a major parcel carrier. <a href=http://example.com/returns> Click here </a> to initiate a return.</p>"
    //             }
    //         },
    //         {}
    //     ]
    // }

    public function __construct($faqs = null, $emoji = true)
    {
        $this->faqs = $faqs;
        $this->has_emoji = $emoji;
        $this->schema = '';
        // if ($this->faqs && count($this->faqs) > 0){
            // $this->schema = $this->makeSchema();
        // }

        // add_filter( 'joli_faq_seo_schema_faq_answer', [$this, 'filterAnswer'] );
    }

    public function getSchema(){
        return $this->schema;
    }

    public function makeSchema()
    {
        $faqs = $this->faqs;

        if (!$this->faqs || count($this->faqs) == 0){
            return null;
        }

        $schema = [
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => [],
        ];

        
        foreach ($faqs as $faq){
            $element = $this->makeSchemaQA($faq);

            if ( $element ){
                $schema["mainEntity"][] = $element;
            }
        }

        return sprintf('<script type="application/ld+json">%s</script>', 
            json_encode(
                apply_filters('joli_faq_seo_schema', $schema),
                JSON_UNESCAPED_UNICODE
            )
        );

    }

    
    public function makeSchemaQA($faq)
    {
        $question = $faq['title'];

        if (jfaq_xy()->can_use_premium_code__premium_only() && $this->has_emoji){
            $question = $faq['emoji'] . ' ' . $question;
        }

        $answer = $this->filterAnswer( $this->getAnswer($faq));
        
        $element = [
            "@type" => "Question",
            "name" => $question,
            "acceptedAnswer" => [
                "@type" => "Answer",
                "text" => $answer,
            ],
        ];

        return $element;
    }


    private function getAnswer($answer){
        $answer_str = apply_filters( 'joli_faq_seo_faq_answer', $answer['content'] );

        return $answer_str;
    }


    public function filterAnswer($answer)
    {
        //The full answer to the question. 
        //The answer may contain HTML content such as links and lists. 
        //Google Search displays the following HTML tags; all other tags are ignored: 
        //<h1> through <h6>, <br>, <ol>, <ul>, <li>, <a>, <p>, <div>, <b>, <strong>, <i>, and <em>.

        // $allowed_tags = '<h1><h2><h3><h4><h5><h6><br><ol><ul><li><a><b><strong><i><em>'; //remove <p><div>
        $allowed_tags_arr = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'br', 'ol', 'ul', 'li', 'a', 'b', 'strong', 'i', 'em'  ]; //remove <p><div>
        $allowed_tags = apply_filters( 'joli_faq_seo_schema_faq_answer_allowed_tags', $allowed_tags_arr );

        $allowed_tags_str = implode('', array_map(function($tag){
            return '<' . $tag . '>';
        }, $allowed_tags));

        return strip_tags($answer, $allowed_tags_str);
    }

}
