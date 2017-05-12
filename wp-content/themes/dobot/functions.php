<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */
@ini_set( 'upload_max_size' , '64M' );
@ini_set( 'post_max_size', '64M');
@ini_set( 'max_execution_time', '300' );
$theme   = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];
include_once 'dobot-functions.php';
include_once 'menu-functions.php';
include_once 'dobot-email-template.php';
include_once 'subscribe-success-email.php';
include_once 'url-rewrite-functions.php';
include_once 'faq-functions.php';
include_once 'contest-functions.php';
require_once  WP_PLUGIN_DIR. '/download-monitor/includes/class-wp-dlm.php';
require_once  WP_PLUGIN_DIR. '/gallery-video/includes/gallery-video-video-functions.php';
require_once  WP_PLUGIN_DIR. '/download-monitor/includes/class-dlm-download-version.php';
include_once  'short-code.php';
include_once  'custom-list-table-contest-kind.php';
include_once  'subcriber_list_table.php';
include_once  'dolnload-functions.php';
include_once  'seo-functions.php';

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}
$storefront = (object) array(
	'version' => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);


require 'inc/storefront-functions.php';

require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce = require 'inc/woocommerce/class-storefront-woocommerce.php';
	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';
}


function get_term_taxonomy_cat($term_id){
    global  $wpdb;
    $query = $wpdb->prepare("SELECT taxonomy FROM wp_term_taxonomy WHERE term_id=%d",$term_id);
    $result = $wpdb->get_row($query);
    return $result->taxonomy;
}


function generateThumbnail($attachmentId, $width ,$height)
{
    $imageArr = wp_get_attachment_image_src($attachmentId, 'single-post-thumbnail');
    $imageSrc = $imageArr[0];
    $imagePath = realpath(str_replace(get_bloginfo('url'), '.', $imageSrc));
    $name = basename($imagePath);
    $newImagePath = str_replace($name, 'thumb_' . $width, $imagePath);
    $newImageSrc = str_replace($name, 'thumb_' . $width, $imageSrc);
    if (!file_exists($newImagePath)) {
        $image = wp_get_image_editor($imagePath);
        if (!is_wp_error($image)) {
            $image->resize($width, $height, false);
            $image->save($newImagePath);
        }
    }
    return $newImageSrc;
}
function inserT_post_category($post_id,$cats){
    global  $wpdb;
    $table = 'wp_term_relationships';
    if(is_array($cats)){
        foreach ($cats as $_cat){
            $data = array('object_id'=>$post_id,'term_taxonomy_id'=>$_cat);
            $wpdb->insert($table,$data);
        }
    }elseif(is_numeric($cats)){
        $data = array('object_id'=>$post_id,'term_taxonomy_id'=>$cats);
        $wpdb->insert($table,$data);
    }
}
function get_post_id_by_slug($post_slug){
    global  $wpdb;
    $query = $wpdb->prepare("SELECT * FROM wp_posts WHERE post_name='%s' ",$post_slug);
    $post = $wpdb->get_row($query);
    if($post){
        return $post->ID;
    }else{
        return null;
    }
}
function  get_post_by_id ($post_id){
    global  $wpdb;
    $query = $wpdb->prepare("SELECT * FROM wp_posts WHERE id=%d",$post_id);
    $post = $wpdb->get_row($query);
    if($post){
        return $post;
    }else{
        return null;
    }
}
function get_post_thumbnail_url($post_id ,$thumbnail = 'thumbnail'){

    $post_id = ( null === $post_id ) ? get_the_ID() : $post_id;
    $thumbnail_id = get_post_thumbnail_id($post_id);
    if($thumbnail_id ){
        $img_url =  wp_get_attachment_image_src($thumbnail_id,$thumbnail);
        return $img_url[0];
    }else{
        return '';
    }
}

function get_video_url_by_image_url($image_url){
    $videourl = gallery_video_get_video_id_from_url($image_url);

    $video_url = '';
    if ( $videourl[1] == 'youtube') {
        $video_url  =  '//www.youtube.com/embed/'.$videourl[0];
    }else if( $videourl[1] == 'youku'){
        $video_url  =  '//player.youku.com/embed/'.$videourl[0];
    }else if($videourl == 'vimeo'){
        $video_url  =  '//player.vimeo.com/video/'.$videourl[0];
    }
    return $video_url;
}
function get_video_cat_name_by_id($video_cat_id){
    global  $wpdb;
    $query = $wpdb->prepare('SELECT name FROM wp_huge_it_videogallery_galleries WHERE id=%d',$video_cat_id);
    $result = $wpdb->get_results($query);
    $video_catName = $result[0]->name;
    return $video_catName;
}
function get_thumb_url_by_image_url($row){
    $image_url = $row->image_url;
    $videourl = gallery_video_get_video_id_from_url($image_url);
    if ($videourl[1] == 'youtube') {
        if (empty($row->thumb_url)) {
            $thumb_pic = '//img.youtube.com/vi/' . $videourl[0] . '/mqdefault.jpg';
        } else {
            $thumb_pic = $row->thumb_url;
        }
    }elseif ($videourl[1] == 'youku') {
        if (empty($row->thumb_url)) {
            $thumb_pic = get_youku_image_from_url($row->image_url);
        } else {
            $thumb_pic = $row->thumb_url;
        }
    }else {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
        $hash = @unserialize(wp_remote_fopen($protocol . "vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
        if (empty($row->thumb_url)) {
            $thumb_pic  = $hash[0]['thumbnail_large'];
        } else {
            $thumb_pic = $row->thumb_url;
        }
    }

    return $thumb_pic;
}


function get_post_category_name($post_id){
    global $wpdb;
    $qeury = $wpdb->prepare("SELECT * FROM wp_term_relationships WHERE  object_id =%d",$post_id);
    $string = array();
    $result = $wpdb->get_results($qeury);
    foreach ($result as $_cat){
        $cats = get_category($_cat->term_taxonomy_id);
        $string[] = __($cats->name,'storefront');
    }
    return implode(',',$string);
}

global  $current_tutorial_cat;
$current_tutorial_cat =  $_COOKIE['tutorial_id'];

function get_tutorial_posts($cat_id,$args = array())
{
    $postsAll = array();
    $result = array();
    $per_page_show = 12;
    if(isset($_GET['q'])){
        $args['s'] = $_GET['q'];
    }
    if(isset($_GET['orderby'])){
        if($_GET['orderby'] == 'views'){
            $args['meta_key']= 'views';
            $args['orderby'] = 'meta_value_num';
        }else{
            $args['orderby'] = $_GET['orderby'];
        }
    }
    if(isset($_GET['pag'])){
        $args['paged'] = $_GET['pag'] > 0  ? $_GET['pag'] : 1;
    }
    //$cat_id = $_GET['cat'] ?  $_GET['cat'] : 0;
    if($cat_id){
        $args['tax_query' ] = array(
            array(
                'taxonomy' =>'tutorial-category',
                'field'    => 'id',
                'terms'    => $cat_id,
            )
        );
    }
    setcookie('tutorial_id',$cat_id,0,'/');
    $defaults = array(
        'orderby'           => 'date',
        'order'             => 'desc',
        'post_type'         => 'tutorial',
        'post_status'       => 'publish',
        'posts_per_page'    =>  $per_page_show,
        'paged'             =>  1,
    );

    $total = array(
        'orderby'           => 'date',
        'order'             => 'desc',
        'post_type'         => 'tutorial',
        'post_status'       => 'publish',
        'posts_per_page'    =>  -1,
    );
    //unset($_GET['q']);

    if($args){
        $argsNew = array_merge($defaults,$args);
    }else{
        $argsNew = $defaults;
    }

    $post_query= new WP_Query($argsNew);

    foreach ($post_query->get_posts() as $post){
        $postsAll[] = $post;
    }
    $result['post'] = $postsAll;

    //page
    $all = array_merge($total,$args);
    unset($all['paged']);
    $all_post_query = new WP_Query($all);
    $totals = $all_post_query->post_count;
    $result['page'] = custom_page($totals,$_GET['pag'],$per_page_show);
    $result['count'] = $totals;
    return $result;
}

/**
 * @param $totals
 * @param $paged
 * @param $per_page_show
 * @param bool $ajax
 * @param string $hash
 * @return string
 */
function custom_page($totals,$paged,$per_page_show,$ajax=true,$hash = null){
    if ($totals) {
        //当前页
        $paged = $paged ? $paged : 1;
        //总页数
        $pages = ceil($totals / $per_page_show);
        $prev = $paged - 1;
        $next = $paged + 1;

        //最多显示的页数
        $_pageNum = 5;

        //当前页大于总页数 则为总页数
        $paged = $paged > $pages ? $pages : $paged;

        //页数小当前页 则为当前页
        $pages = $pages < $paged ? $paged : $pages;

        //计算开始页
        $_start = $paged - floor($_pageNum/2);
        $_start = $_start<1 ? 1 : $_start;

        //计算结束页
        $_end = $paged + floor($_pageNum/2);
        $_end = $_end > $pages? $pages : $_end;

        //当前显示的页码个数不够最大页码数，在进行左右调整
        $_curPageNum = $_end-$_start+1;

        //左调整
        if($_curPageNum<$_pageNum && $_start>1){
            $_start = $_start - ($_pageNum-$_curPageNum);
            $_start = $_start <1 ? 1 : $_start;
            $_curPageNum = $_end-$_start+1;
        }
        //右边调整
        if($_curPageNum<$_pageNum && $_end<$pages){
            $_end = $_end + ($_pageNum-$_curPageNum);
            $_end = $_end>$pages? $pages : $_end;
        }

        if($pages > 1) {
            $page = "<div class='pagination'><ul>";

            if ($paged > $_start) {
                $page .= "<li class='page-item pre-page'><a href='" . reset_page_url_param('pag',$prev) . $hash ."'> « </a></li>";
            }
            for ($i = $_start; $i <= $_end; $i++) {
                $page .= ($paged == $i) ? "<li class='page-item'><span class='current'>" . $i . "</span></li>" :
                    "<li class='page-item inactive'><a href='" . reset_page_url_param('pag',$i) . $hash . "'>" . $i . "</a></li>";

            }
            $page .= ($paged < $_end) ?
                "<li class='page-item next-page'><a href='" . reset_page_url_param('pag',$next) .$hash."'> » </a></li>" : "";
            $page .= "</ul></div>\n";

            if($ajax){
                $next_html = "<a href='" . reset_page_url_param('pag',$next) . "'> ".__('More')." </a>";
                return $paged < $_end ? $next_html : '';
            }else{
                return $page;
            }

        }
    }
}

/**
 * @param $key
 * @param $value
 * @param null $url
 * @return string
 */
function reset_page_url_param($key,$value,$url=null){
    $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
    $parse_url = $url ? parse_url($url) : parse_url(curPageURL());
    $query = isset($parse_url['query']) ? $parse_url['query'] : '';
    if($query){
        $params = parseUrlParam($query);
    }

    $params[$key] = $value;
    $url = $protocol.$_SERVER['HTTP_HOST'].$parse_url['path'].'?'.http_build_query($params);
    return $url;
}

function parseUrlParam($query){
    $queryArr = explode('&', $query);
    $params = array();
    if($queryArr){
        foreach( $queryArr as $param ){
            list($name, $value) = explode('=', $param);
            $params[urldecode($name)] = urldecode($value);
        }
    }
    return $params;
}

function delete_video(){
    $id = $_POST['id'];
    global  $wpdb;
    try{
        $post_id = get_video_relate_post_id($id);
        $post_query = $wpdb->prepare("DELETE FROM ".$wpdb->prefix."posts WHERE ID=%d",$post_id);
        $wpdb->query($post_query);
        $post_meta_query = $wpdb->prepare("DELETE FROM ".$wpdb->prefix."postmeta WHERE post_id=%d",$post_id);
        $wpdb->query($post_meta_query);
        $video_query = $wpdb->prepare("DELETE FROM ".$wpdb->prefix."huge_it_videogallery_videos WHERE id=%d",$id);
        $wpdb->query($video_query);
        echo 'ok';
        exit;
    }catch (Exception $e ){
        echo 'no';
        exit;
    }
}

add_action('wp_ajax_delete_video', 'delete_video');
add_action('wp_ajax_nopriv_delete_video', 'delete_video');

function get_video_info(){
    global $current_user;
    $video_Id = $_POST['id'];
    $video = get_video_by_id($video_Id);
    $result['url'] = $video->image_url;
    $result['video_id'] = $video_Id;
    $result['title'] = $video->name;
    $result['description'] = $video->description;
    $result['videogallery_id'] = $video->videogallery_id;
    wp_die(json_encode($result));
}
add_action('wp_ajax_get_video_info', 'get_video_info');
add_action('wp_ajax_nopriv_get_video_info', 'get_video_info');

function diffDate($date1,$date2)
{
    $datetime1 = new \DateTime($date1);
    $datetime2 = new \DateTime($date2);
    $interval = $datetime1->diff($datetime2);
    $time['Years']         = $interval->format('%Y');
    $time['Months']         = $interval->format('%m');
    $time['Days']         = $interval->format('%d');
    $time['Hours']         = $interval->format('%H');
    $time['Minutes']         = $interval->format('%i');
    $time['Seconds']         = $interval->format('%s');
    foreach ($time as $key =>$_time){
        if($_time != '00'){
            $diff_date = $_time. ' ' .$key .' Ago';
            break;
        }
    }
    return $diff_date;
}

function user_is_liked_the_post($post_id){
    global  $current_user,$wpdb;
    $wp_user_IP = wp_user_get_real_ip();
    $user_id = $current_user->ID;
    $query = $wpdb->prepare("SELECT status FROM wp_ulike WHERE user_id =%d AND post_id = %d AND ip = '%s' AND status='%s'",$user_id,$post_id,$wp_user_IP,'like');
    $status = $wpdb->get_results($query);
    if(count($status)){
        return true;
    }else{
        return false;
    }
}

function ajax_get_video_pop_info(){
    $user_login = is_user_logged_in();
    $video_id = $_POST['id'];
    $video_url = $_POST['url'];
    $video = get_video_by_id($video_id);
    $user = get_the_video_user($video_id);
    $post_id = get_video_relate_post_id($video_id);
    $status = user_is_liked_the_post($post_id);
    $like_class = $status ? 'liked' : 'unlike';
    $is_liked = $status ? 2 : 1;
    if($user_login ){
        $like_id = 'pop-video-liked';
    }else{
        $like_id = 'no-login-like';
    }

    $post = get_post($post_id);
    $pubDate = date('Y-m-d',strtotime($video->create_date));
    $now_date = new DateTime();
    $now_date = $now_date->format('Y-m-d');
    $diffDate = diffDate($pubDate,$now_date);
    $title = $video->name;
    $comments = get_comments(array('post_id'=>$post_id));
    $videogallery = get_category_by_videoid($video_id)->name;
    $views = get_post_meta($post_id,'views',true) ? get_post_meta($post_id,'views',true) : 0;
    $liked = get_post_meta($post_id,'_liked',true) ? get_post_meta($post_id,'_liked',true) : 0;
    update_post_meta($post_id,'views',$views+1);
    $no_login_notice = __('Login to praise','storefront');
    $login_btn = __('Login','storefront');
    $login_cancel_btn = __('Cancel','storefront');
    $link = esc_url(get_permalink($post->ID));
    $refer = reset_page_url_param('refer',urlencode($link),wc_get_page_permalink('myaccount'));
    if($user_login){
        $commnet_link = $link.'#respond';
    }else{
        $commnet_link = reset_page_url_param('refer',urlencode($link).'#respond',wc_get_page_permalink('myaccount'));
    }
        $html = '<div class="video-pop-player">
        <!--player content-->
        <div class="player-content-box">
            <div class="player-content">
                <iframe src="'.esc_url($video_url).'" frameborder="0" width="100%" height="100%"
                scrolling=no marginwidth=0 allowtransparency marginheight=0
                ></iframe>
            </div>
            <div class="video-addtionnal">
                <div class="video-title"><a href="'.esc_url($link).'"><h3>'. __($title).'</h3></a></div>
                <div class="ext-data">
                    <span class="cat pblue"><strong>'.__($videogallery).'</strong></span>&nbsp;&nbsp;|
                    &nbsp;&nbsp;' . __('By'). '&nbsp;
                    <span class="user pblue">'. __($user->user_login) . '</span>
                    <span class="diff_date fr">'. $diffDate. '</span>
                </div>';
            $html .= '<div class="views-liked-share clearbox">
                    <div class="views-liked fl">
                        <input type="hidden" id="no_login_like" value="'.$no_login_notice.'">
                        <input type="hidden" id="login_btn" value="'.$login_btn.'">
                        <input type="hidden" id="cancel_btn" value="'.$login_cancel_btn.'">
                        <input type="hidden" id="refer" value="'.$refer.'">
                        
                        <span data-status="'.$is_liked.'"style="cursor:pointer" data-id="'.$post_id.'" class="video-parise '.$like_class.'" id="'.$like_id.'">' . number_format($liked) . '</span><img id="loading-img" style="display:none" src="'.get_stylesheet_directory_uri().'/assets/images/dobot/loading.gif'.'"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="views">' . number_format($views) . '</span>
                    </div>
                    <div class="share fr">
                        <a href="'.share_to_social($post,'facebook').'" target="_blank"><span class="share-facebook"></span></a>&nbsp;&nbsp;
                        <a href="'.share_to_social($post,'twitter').'" target="_blank"><span class="share-twitter"></span></a>&nbsp;&nbsp;
                        <a href="'.share_to_social($post,'google-plus') .' " target="_blank"><span class="share-google"></span></a>&nbsp;&nbsp;
                        <a href="'.share_to_social($post,'pinterest') .'" target="_blank"><span class="share-pinterest"></span></a>
                    </div>
                </div>
                <div class="video-comment">';
                    if(count($comments)){
                    $html .=  '<ul class="comment-items">';
                    foreach ($comments as $_comment):
                        $_comment_user = get_user_by('ID',$_comment->user_id);
                        $_comment_date = $_comment->comment_date;
                        $_diff_date = diffDate(date('Y-m-d',strtotime($_comment_date)),date('Y-m-d'));
                    $html .= '<li class="comment-item">
                            <div class="comment-data">
                                 <span class="user-img">'. get_avatar($_comment_user->ID,50) . '</span>
                                 <div class="comment-tiem-right">   
                                 <span class="user-name pblue"><strong>'. $_comment_user->user_login . '</strong></span>&nbsp;&nbsp;
                                 <span class="ago-date p12">'. $_diff_date .'</span>
                                 <div class="comment-content">'. mb_strimwidth(strip_tags(apply_filters('the_content', $_comment->comment_content)), 0, 70,"……").'</div>
                                 </div>
                            </div>
                         </li>';
                    endforeach;
                    $html .= '</ul>';
                    }else{
                        $html .= '<p class="video-no-comments">'.__('There are not comments.').'</p>';
                    }
        $html  .=  '<div class="clearbox"><div class="post-comments fl"><a class="pblue text-underline" href="'.esc_url($commnet_link).'">'.__('Post a comment').'</a></div>
                    <div class="video-comments fr">'.__('Comments').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.number_format($post->comment_count).'</div></div>
                </div>
            </div>
        </div>
    </div>';

    $result['html'] = $html;
    $result['title'] = $title;
    $result['code'] = 200;
    wp_die(json_encode($result));
}

/**
 * get user real ip address
 * @return array|false|string
 */
function wp_user_get_real_ip() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    } elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');
    } elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    return $ip;
}
add_action('wp_ajax_ajax_get_video_pop_info', 'ajax_get_video_pop_info');
add_action('wp_ajax_nopriv_ajax_get_video_pop_info', 'ajax_get_video_pop_info');

/**
 * ajax user like post
 */
function  like_process(){
    global  $wpdb,$current_user;
    $ip = wp_user_get_real_ip();
    $post_id = $_POST['id'];
    $user_id = $current_user->ID;
    $query = "SELECT * FROM wp_ulike WHERE user_id = '".$user_id."' AND  post_id = '".$post_id."' AND ip = '".$ip."'";
    $condition 	= $wpdb->get_row($query);
    $linke = get_post_meta($post_id,'_liked',true) ? get_post_meta($post_id,'_liked',true) : 0;
    if($condition->status == 'unlike'){
        $wpdb->update(
            'wp_ulike',
            array('status'=>'like'),
            array('user_id'=>$user_id,'post_id'=>$post_id,'ip'=>$ip)
        );
        update_post_meta($post_id,'_liked',$linke+1);
        $data['data'] = get_post_meta($post_id,'_liked',true);
        wp_die(json_encode($data));
    }else if(!$condition){
        $wpdb->insert(
            'wp_ulike',
            array('date_time'=>date('Y-m-d H:i:s'),'status'=>'like', 'user_id'=>$user_id, 'post_id'=>$post_id, 'ip'=>$ip)
        );
        update_post_meta($post_id,'_liked',$linke+1);
        $data['data'] = get_post_meta($post_id,'_liked',true);
        wp_die(json_encode($data));
    }
}
add_action('wp_ajax_like_process', 'like_process');
add_action('wp_ajax_nopriv_like_process', 'like_process');

/**
 * get user all post type posts
 * @param $user_id
 * @return array|null|object
 */
function get_user_post($user_id){
    global $wpdb;
    $query = "SELECT * FROM wp_posts WHERE post_author = $user_id AND (post_type='video' OR post_type='contest'  OR post_type='tutorial' ) AND (post_status = 'publish' OR post_status = 'pending' OR post_status = 'draft')";
    $result = $wpdb->get_results($query);
    return $result;
}

/**
 * @param $user_id
 * @return int
 */
function get_user_post_count($user_id){
    $user_posts = get_user_post($user_id);
    return count($user_posts);
}

/**
 * @param $user_id
 * @return int|mixed
 */
function get_user_post_like_count($user_id){
    $user_posts = get_user_post($user_id);
    $liked = 0;
    foreach ($user_posts as $post){
        if(get_post_meta($post->ID,'_liked',true)){
            $liked += get_post_meta($post->ID,'_liked',true);
        }
    }
    return $liked;
}

/**
 * user  upload  file
 * @param array $file
 * @return bool|int|WP_Error
 */
function upload_user_file( $file = array() ) {
    require_once( ABSPATH . 'wp-admin/includes/admin.php' );
    $file_return = wp_handle_upload( $file, array('test_form' => false ) );
    $form_error = new WP_Error;
    if( isset( $file_return['error'] ) || isset( $file_return['upload_error_handler'] ) ) {
        $form_error->add_data('file_type',$file_return['error']);
        return false;
    } else {
        $filename = $file_return['file'];
        $attachment = array(
            'post_mime_type' => $file_return['type'],
            'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
            'post_content' => '',
            'post_status' => 'inherit',
            'guid' => $file_return['url']
        );
        $attachment_id = wp_insert_attachment( $attachment, $file_return['url'] );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
        wp_update_attachment_metadata( $attachment_id, $attachment_data );
        if( 0 < intval( $attachment_id ) ) {
            return $attachment_id;
        }
    }
    return false;
}

/**
 * get current page url
 * @return string
 */
function curPageURL(){
    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on")
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    }
    else
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/**
 * get file size
 * @param $filesize
 * @return string
 */
function sizecount($filesize) {
    if($filesize >= 1073741824) {
        $filesize = round($filesize / 1073741824 * 100) / 100 . ' gb';
    } elseif($filesize >= 1048576) {
        $filesize = round($filesize / 1048576 * 100) / 100 . ' mb';
    } elseif($filesize >= 1024) {
        $filesize = round($filesize / 1024 * 100) / 100 . ' kb';
    } else {
        $filesize = $filesize . ' bytes';
    }
    return $filesize;
}

/**
 * download file
 * @param $path_name
 * @param $save_name
 */
function dobot_download($path_name, $save_name){
    ob_end_clean();
    $hfile = fopen($path_name, "rb") or die(__("Can not find file: $path_name\n"));
    Header("Content-type: application/octet-stream");
    Header("Content-Transfer-Encoding: binary");
    Header("Accept-Ranges: bytes");
    Header("Content-Length: ".filesize($path_name));
    Header("Content-Disposition: attachment; filename=\"$save_name\"");
    while (!feof($hfile)) {
        echo fread($hfile, 32768);
    }
    fclose($hfile);
}

/**
 * remove comment reply title
 */
add_filter( 'comment_form_default_fields','unset_replay_field');
function unset_replay_field($fields){
    if(isset($fields['title_reply']))
        unset($fields['title_reply']);
    return $fields;
}

/**
 * register setting fields
 */
add_filter( 'admin_init' , 'register_fields' );
function register_fields() {
    register_setting( 'general', 'en_site_address', 'esc_attr' );
    register_setting( 'general', 'zh_site_address', 'esc_attr' );
    register_setting( 'general', 'web_keywords', 'esc_attr' );
    register_setting( 'general', 'web_description', 'esc_attr' );
    add_settings_field('zh_site_address', '<label for="favorite_color">'.__('Site Address(Chinese)','storefront' ).'</label>' ,'zh_fields_html' , 'general' );
    add_settings_field('en_site_address', '<label for="favorite_color">'.__('Site Address(English)','storefront' ).'</label>' ,'en_fields_html' , 'general' );
    add_settings_field('web_keywords', '<label for="web_keywords">'.__('Site Keywords' ).'</label>' ,'keywords_fields_html' , 'general' );
    add_settings_field('web_description', '<label for="web_description">'.__('Site Description' ).'</label>' , 'description_fields_html', 'general' );

}
function zh_fields_html() {
    $value = get_option( 'zh_site_address', '' );
    echo '<input type="text" class="regular-text" id="zh_site_address" name="zh_site_address" value="' . $value . '" />';
    echo '<p class="description">'.__('Chinese site base address, eg: <a href="javascript:void(0)">cn.dobot.cc</a>','storefront').'</p>';
}
function en_fields_html() {
    $value = get_option( 'en_site_address', '' );
    echo '<input type="text" class="regular-text" id="en_site_address" name="en_site_address" value="' . $value . '" />';
    echo '<p class="description">'.__('English site base address, eg: <a href="javascript:void(0)">www.dobot.cc</a>','storefront').'</p>';
}

function keywords_fields_html() {
    $value = get_option( 'web_keywords', '' );
    echo '<textarea  rows="10" class="regular-text" type="text" id="web_keywords" name="web_keywords">'.  $value .'</textarea>';
    echo '<p class="description">'.__('The keyword of the website','storefront').'</p>';
}

function  description_fields_html(){
    $value = get_option( 'web_description', '' );
    echo '<textarea rows="10" class="regular-text" type="text" id="web_description" name="web_description">'.  $value .'</textarea>';
    echo '<p class="description">'.__('The description of the website','storefront').'</p>';
}

/*add upload file type*/
add_filter('upload_mimes', 'add_upload_mimes', 1, 1);
function add_upload_mimes ( $mines) {

    $mines['xml'] = 'application/xml';
    $mines['exe'] = 'application/x-dosexec';
    $mines['dll'] = 'application/octet-stream';
    $mines['docx'] = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    $mines['rar'] = 'application/x-rar';
    $mines['h'] =  'text/plain';
    $mines['apk'] = 'application/java-archive';
    $mines['py'] = 'text/x-c++';
    $mines['dmg'] = 'application/octet-stream';
    $mines['pro'] = 'text/plain';
    $mines['plt'] = 'text/x-c';
    $mines['ui'] =  'text/x-c';
    $mines['user'] = 'text/plain';

    return $mines;
}

/**
 * get page url by page_name
 * @param $slug
 * @return false|string
 */
function  get_page_url($slug){
    global  $wpdb;
    $url_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_name = '".$slug."'");
    return get_permalink($url_id);
}

/*modify post thumbnails default size*/
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 500, 250 ,true);


//禁止显示更新提示
add_filter('pre_site_transient_update_core',    create_function('$a', "return null;"));// 关闭核心提示
add_filter('pre_site_transient_update_plugins', create_function('$a', "return null;"));// 关闭插件提示
add_filter('pre_site_transient_update_themes',  create_function('$a', "return null;"));// 关闭主题提示
remove_action('admin_init', '_maybe_update_plugins');// 禁止 WordPress 更新插件
remove_action('admin_init', '_maybe_update_core');// 禁止 WordPress 检查更新
remove_action('admin_init', '_maybe_update_themes');// 禁止 WordPress 更新主
