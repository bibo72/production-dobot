<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/18
 * Time: 14:13
 */

add_action('init', 'add_rewrite_rules' );
/*重写规则*/
function add_rewrite_rules(){
    global $wp_rewrite;
    add_rewrite_rule(
        'contest-list/([^/]+)?$',
        'index.php?pagename=contest-detail&contest_id=$matches[1]',
        'top'
    );

}
/*添加query_var变量*/
add_filter('query_vars', 'add_query_vars');
function add_query_vars($public_query_vars){
    $public_query_vars[] = 'contest_id';
    return $public_query_vars;
}

$categories =  array('seo-category'=>'seocat');
function custom_category_rules() {
    global $wp_rewrite,$categories;
    foreach ($categories as $_cat=>$front){
        $wp_rewrite->extra_permastructs[$_cat]['with_front'] = $front;
        $wp_rewrite->extra_permastructs[$_cat]['struct'] = $wp_rewrite->extra_permastructs[$_cat]['with_front'].'/%'.$_cat.'%.html';
    }
    return $wp_rewrite;
}
add_action( 'init', 'custom_category_rules' );