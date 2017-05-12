<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/14
 * Time: 15:37
 */

/**
 * 得到产品下载分类
 * @param int $parent_id
 * @return array|null|
 */
function get_download_cat($parent_id = 0){
    global  $wpdb;
    $query = $wpdb->prepare("SELECT term_id FROM wp_term_taxonomy WHERE taxonomy='%s' AND parent =%d",'dlm_download_category',$parent_id);
    $result = $wpdb->get_results($query);
    foreach ($result as $item) {
        $cats[] = get_term_by('id',$item->term_id,'dlm_download_category');
    }
    return $cats;
}

/**
 * @param $cat_id
 * @return string|void
 */
function get_downlaod_cat_name_by_id($cat_id){
    $cat = get_term_by('id',$cat_id,'dlm_download_category');
    return $cat->name;
}

/**
 * 得到下载次数最多的
 * @param  int $type
 * @param int $num
 * @return array|null|object
 */
function get_most_download_file($type,$num=5){
    global  $wpdb;
    $query = "SELECT *,(b.meta_value+0) AS counts FROM wp_posts AS a JOIN wp_postmeta AS b ON b.post_id = a.ID AND b.meta_key='_download_count' 
              AND (b.meta_value+0)>= 0 
              JOIN wp_term_relationships AS c ON c.object_id = a.ID AND term_taxonomy_id=".$type." WHERE  post_type = 'dlm_download' ORDER BY counts DESC LIMIT 0,".$num;
    $result = $wpdb->get_results($query);
    return $result;
}

function get_download_files($id){
    $cat = isset($_GET['sub_cat']) ? $_GET['sub_cat'] : $id;
    if($cat){
        $per_posts = 12;
        $paged = isset($_GET['pag']) ? $_GET['pag'] : 1;
        $paged = $paged < 1 ? 1 : $paged;
        $args = array(
            'tax_query' => array(
                array(
                    'taxonomy'  => 'dlm_download_category',
                    'field'     => 'id',
                    'terms'     => $cat,
                ),
            ),
            'paged'             => $paged,
            'orderby'           => 'meta_value_num',
            'meta_key'          => '_download_count',
            'order'             => 'desc',
            'post_type'         => 'dlm_download',
            'post_status'       => 'publish',
            'showposts'         =>  $per_posts,

        );
        $all_args = array(
            'tax_query' => array(
                array(
                    'taxonomy'  =>'dlm_download_category',
                    'field'     => 'id',
                    'terms'     => $cat,
                ),
            ),
            'orderby'           => 'meta_value_num',
            'meta_key'          => '_download_count',
            'order'             => 'desc',
            'post_type'         => 'dlm_download',
            'post_status'       => 'publish',
            'showposts'         => -1
        );
        $query = new WP_Query($args);
        $total = new WP_Query($all_args);
        $result['files'] = $query->posts;
        $result['count'] = $total->post_count;
        $result['page']  = custom_page($total->post_count,$paged,$per_posts,false,'#sub-download');
        return $result;
    }else{
        return array();
    }
}
//add third link
add_action( 'admin_menu', 'add_download_third_link' );
add_action('save_post', 'save_download_meta');
function add_download_third_link(){
    add_meta_box( 'third-download-link', __( 'Third Download Link', 'download-monitor' ), 'download_third_link', 'dlm_download', 'normal', 'high' );
}
function download_third_link(){
    global $post;
    $box_value = get_post_meta($post->ID,'dlm_third',true);
    $input_name = 'dlm_third';
    // 自定义字段标题
    echo '
    <div id="postcustomstuff">
        <table id="list-table">
            <thead>
                <tr>
                    <th class="left">Link</th>
                </tr>
            </thead>
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="dlm_third">
                    <td class="left">
                        <label class="screen-reader-text" for="meta-5963-key">Key</label>
                        <input style="background-color: rgb(255, 255, 255); font-size: 1.7em; height: 1.7em; line-height: 100%; margin: 0px 0px 3px; outline: 0px none; padding: 0px; width: 100%; border-width: 1px;" name="'.$input_name.'" value="'.$box_value.'" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>';
}
function save_download_meta( $post_id ){
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } else {
        if (!current_user_can('edit_post', $post_id))
            return $post_id;
    }
    $data = $_POST['dlm_third'];
    if (get_post_meta($post_id, 'dlm_third') == "")
        add_post_meta($post_id, 'dlm_third', $data, true);
    elseif($data != get_post_meta($post_id, 'dlm_third', true))
        update_post_meta($post_id, 'dlm_third', $data);
    elseif($data == "")
        delete_post_meta($post_id, 'dlm_third', get_post_meta($post_id, 'dlm_third', true));
}