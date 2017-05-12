<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/17
 * Time: 11:36
 */

/**
 * get categories of faq
* @param int $parent_id
* @return array|null|
 */
function get_faq_cat($parent_id = 0){
    global  $wpdb;
    $terms  = array();
    $query = $wpdb->prepare("SELECT term_id FROM wp_term_taxonomy WHERE taxonomy='%s' AND parent =%d",'ufaq-category',$parent_id);
    $result = $wpdb->get_results($query);
    foreach ($result as $item) {
        $terms[] = get_term_by('id',$item->term_id,'ufaq-category');
    }
    return $terms;
}

/**
 * get $faq_id of posts
 * @param $faq_id
 * @return array
 */
function get_faq_posts($faq_id){
    $faq_id = isset($_GET['faq_cat']) ? $_GET['faq_cat'] : $faq_id;
    if($faq_id){
        $per_posts = 12;
        $paged = isset($_GET['pag']) ? $_GET['pag'] : 1;
        $paged = $paged < 1 ? 1 : $paged;
        $args = array(
            'tax_query' => array(
                array(
                    'taxonomy'  =>'ufaq-category',
                    'field'     => 'id',
                    'terms'     => $faq_id,
                ),
            ),
            'orderby'           => 'date',
            'order'             => 'asc',
            'post_type'         => 'ufaq',
            'post_status'       => 'publish',
            'paged'             =>  $paged,
            'showposts'         =>  $per_posts,

        );
        $all_args = array(
            'tax_query' => array(
                array(
                    'taxonomy'  =>'ufaq-category',
                    'field'     => 'id',
                    'terms'     => $faq_id,
                ),
            ),
            'post_type'         => 'ufaq',
            'post_status'       => 'publish',
            'showposts'         => -1
        );
        $query = new WP_Query($args);
        $total = new WP_Query($all_args);
        $result['posts'] = $query->posts;
        $result['count'] = count($total->posts);
        $result['page']  = custom_page(count($total->posts),$paged,$per_posts,false,'#faq');
        return $result;
    }else{
        return array();
    }
}

/**
 * check $faq_cat is $term_id of sub-category
 * @param $term_id
 * @param $faq_cat
 * @return bool
 */
function include_faq_cat($term_id,$faq_cat){
    global  $wpdb;
    $query = $wpdb->prepare("SELECT term_id FROM wp_term_taxonomy WHERE taxonomy='%s' AND parent =%d AND term_id=%d",'ufaq-category',$term_id,$faq_cat);
    $row = $wpdb->get_row($query);
    if($row){
        return true;
    }else{
        return false;
    }
}