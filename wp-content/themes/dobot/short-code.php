<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/11
 * Time: 11:04
 */
function get_tutorial_center($attrs){
    extract(shortcode_atts( array(
        'slug' => '',
    ), $attrs ));
    $GLOBALS['tutorial_slug']= $slug;
    get_template_part('tutorials');
}
add_shortcode('tutorial_center','get_tutorial_center');

function get_customer_videos_content(){
    get_template_part( 'videos');
}
add_shortcode('customer_videos','get_customer_videos_content');


function get_news_events_content(){
    get_template_part( 'news-events');
}
add_shortcode('news_events','get_news_events_content');

function get_cms_page_head(){
    get_template_part( 'cms-page-head');
}
add_shortcode('cms_page_head','get_cms_page_head');


function get_contest_content(){
    get_template_part( 'contest-list');
}
add_shortcode('contest','get_contest_content');

function get_contest_detail(){
    get_template_part('contest-detail');
}
add_shortcode('contest_detail','get_contest_detail');

function get_download_center($attrs){

    extract(shortcode_atts( array(
        'slug' => '',
    ), $attrs ));
    $GLOBALS['download_slug']= $slug;
    get_template_part('download-center');

}
add_shortcode('download_center','get_download_center');

function get_dobot_faq_template($attrs){
    extract(shortcode_atts( array(
        'slug' => '',
    ), $attrs ));
    $GLOBALS['faq_slug']= $slug;
    get_template_part('dobot-faq');
}
add_shortcode('dobot_faq','get_dobot_faq_template');

function get_resource_seo_template($attrs){
    extract(shortcode_atts( array(
        'id' => '',
    ), $attrs ));
    $GLOBALS['seo_id'] = $id;
    get_template_part('seo-list');
}
add_shortcode('resource_seo','get_resource_seo_template');