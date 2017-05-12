<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/19
 * Time: 16:03
 */

/**
 * @param int $cat
 * @param int $parent
 * @return array
 */
function get_seo_cat($cat=null,$parent=0){
    global  $wpdb;
    $seo_cat = null;
    if($cat){
        $query = $wpdb->prepare("SELECT * FROM wp_term_taxonomy WHERE taxonomy='%s' AND parent=%d AND term_id=%d ", 'seo-category', $parent,$cat);
        $resutl = $wpdb->get_results($query);
        $seo_cat = get_term_by('id', $resutl[0]->term_id, 'seo-category');
    }else {
        $query = $wpdb->prepare("SELECT * FROM wp_term_taxonomy WHERE taxonomy='%s' AND parent=%d ", 'seo-category', $parent);
        $resutl = $wpdb->get_results($query);
        foreach ($resutl as $item) {
            if(get_term_meta($item->term_id,'display_front',true)){
                $cat =  get_term_by('id', $item->term_id, 'seo-category');
                $seo_cat[] = $cat;
            }
        }
    }
    return $seo_cat;
}
global $current_seo_cat;
$current_seo_cat = isset($_COOKIE['seo_id']) && $_COOKIE['seo_id'] >0 ? $_COOKIE['seo_id'] : 0;
function get_seo_cat_below_posts($cat_id = 0,$per_page_show=null,$is_popular=false){
    if(!$is_popular){
        setcookie('seo_id',$cat_id,0,'/');
    }
    $pag= $_GET['pag'] ? $_GET['pag'] : 1;
    $paged = intval($pag) < 1 ? 1: intval($pag) ;
    $per_page_show = $per_page_show ? $per_page_show : 12;
    $cats = get_seo_cat();
    $ids = array();
    foreach ($cats as $_cat){
        $ids[] = $_cat->term_id;
    }
    $defaults = array(
        'orderby'           => 'date',
        'order'             => 'desc',
        'post_type'         => 'seo',
        'post_status'       => 'publish',
        'posts_per_page'    =>  $per_page_show,
        'tax_query'          =>  array(
            array(
                'taxonomy' =>'seo-category',
                'field'    => 'id',
                'terms'    => $ids,
            )
        ),
    );
    if(!$is_popular){
        $defaults['paged']  = $paged;
    }
    $total  = array(
        'orderby'           => 'date',
        'order'             => 'desc',
        'post_type'         => 'seo',
        'post_status'       => 'publish',
        'posts_per_page'    =>  -1,
        'tax_query'          =>  array(
            array(
                'taxonomy' =>'seo-category',
                'field'    => 'id',
                'terms'    => $ids,
            )
        ),
    );

    if($cat_id){
        $orderby = get_term_meta($cat_id,'order_by',true);
        if($orderby == 'views'){
            $defaults['orderby']  = 'meta_value_num';
            $defaults['meta_key'] = 'views';
            $defaults['meta_query'] = array(
                array(
                    'key'     => 'views',
                    'value'   => 0,
                    'compare' => '>=',
                )
            );
        }else{
            $defaults['orderby'] = $orderby;
        }
        $defaults ['tax_query' ] = array(
            array(
                'taxonomy' =>'seo-category',
                'field'    => 'id',
                'terms'    => $cat_id,
            )
        );
        $total['tax_query' ] = array(
            array(
                'taxonomy' =>'seo-category',
                'field'    => 'id',
                'terms'    => $cat_id,
            )
        );
    }

    $query = new WP_Query($defaults);
    $result['posts'] = $query->posts;
    if(!$is_popular){
        $total = new  WP_Query($total);
        $totlas = $total->post_count;
        $result['count'] = $totlas;
        $result['page'] = custom_page($totlas,$paged,$per_page_show,false,'#seo');
    }
    return  $result;
}
function get_seo_cat_by_postid($post_id){
    global  $wpdb;
    $query = $wpdb->prepare("SELECT a.term_taxonomy_id,b.term_id as bt_id FROM wp_term_relationships AS a 
          JOIN  wp_termmeta AS b 
            ON b.term_id = a.term_taxonomy_id  AND b.meta_key = 'display_front' AND b.meta_value = '1'
          WHERE a.object_id=%d",$post_id);
    $result = $wpdb->get_results($query);
    $seo_cat = get_term_by('id',$result[0]->bt_id,'seo-category');
    return $seo_cat;
}