<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/3/22
 * Time: 11:08
 */
global  $wpdb;
$video_id = get_post_meta(get_the_ID(),'post_video',true);

$post_id = get_the_ID();
$post = get_post($post_id);
$liked = get_post_meta($post_id,'_liked',true) ? get_post_meta($post_id,'_liked',true) : 0;
$views = get_post_meta($post_id,'views',true) ? get_post_meta($post_id,'views',true) : 0;

$query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videogallery_videos WHERE id=%d",$video_id);
$video = $wpdb->get_results($query);
$user = get_the_video_user($video_id);
$video_url = get_video_url_by_image_url($video[0]->image_url);
$video_cat = $video[0]->videogallery_id;
$gallery_name = get_video_gallery_name_by_id($video_cat);

$status = user_is_liked_the_post($post_id);
$like_class = $status ? 'liked' : 'unlike';
$is_liked = $status ? 2 : 1;
$user_login = is_user_logged_in();
if( $user_login ){
    $like_id = 'pop-video-liked';
}else{
    $like_id = 'no-login-like';
}
$no_login_notice = __('Login to praise','storefront');
$login_btn = __('Login','storefront');
$login_cancel_btn = __('Cancel','storefront');
$link = esc_url(get_permalink($post->ID));
$refer = reset_page_url_param('refer',urlencode($link),wc_get_page_permalink('myaccount'));
?>

<?php
global $current_video_cat;
if(is_numeric($current_video_cat)){
    $crumb_gallery_name = get_video_gallery_name_by_id($current_video_cat);
}else{
    $crumb_gallery_name = __('Videos','storefront');
}
?>


<div class="full-screen category-customer-video-head">
    <div class="col-full textcenter">
        <h1><?php echo $video[0]->name?></h1>
        <div class="video-post-note">
        <?php if( $gallery_name != ''):?>
          <span class="video-post-cat"><strong class="pblue"><?php echo __($gallery_name)?></strong></span>
        <?php endif;?>
        <?php if( $user->user_login !=''):?>
          <span class="video-post-anthor">BY <span class="pblue"><?php echo __($user->user_login)?></span></span>
        <?php endif;?>
          <span class="video-post-date"><?php echo date("M, d, Y",strtotime($video[0]->create_date));?></span>
        </div>
        <div class="video-post-share pfff">
            <span class="video-parise" id="video-detail-header-video-parise"> <?php echo number_format($liked)?></span>
            <span class="views"> <?php echo number_format($views)?></span>
            <a href="<?php echo share_to_social($post,'facebook')?>" target="_blank"><span class="share-facebook"></span></a>
            <a href="<?php echo share_to_social($post,'twitter')?>" target="_blank"><span class="share-twitter"></span></a>
            <a href="<?php echo share_to_social($post,'google-plus')?>" target="_blank"><span class="share-google"></span></a>
            <a href="<?php echo share_to_social($post,'pinterest')?>" target="_blank"><span class="share-pinterest"></span></a>
        </div>
    </div>
</div>
<div class="full-screen bgfff">
    <div class="col-full">
        <div id="crumbs">
            <ul>
                <li><a href="<?php echo home_url()?>"><?php _e('Home','storefront')?></a></li>
                <li><a href="<?php echo get_page_url('videos-center')?>?videogallery_id=<?php echo $current_video_cat?>"><?php _e($crumb_gallery_name,'storefront')?></a></li>
                <li><?php echo  __($post->post_title)?></li>
            </ul>
        </div>
    </div>
</div>
<div class="video-info-box">
    <div class="video-player" >
        <iframe src="<?php echo esc_url($video_url) ?>" frameborder="0" width="100%" height="100%"
          scrolling=no marginwidth=0 allowtransparency marginheight=0>
        </iframe>
    </div>
    <div class="video-info">
        <div class="description">
            <div class="post-margin-35">
            <?php echo $video[0]->description?>
            </div>
            <!-- tag -->
            <div class="post-margin-35 tagsection">
                <div class="tag-cont">
                    <strong>TAGS: </strong><?php echo __($gallery_name)?>
                    <span class="comments fr"><?php _e('COMMENTS:');?> <?php echo number_format($post->comment_count)?></span>
                    <span class="views fr"> <?php echo number_format($views)?></span>
                    <input type="hidden" id="no_login_like" value="<?php echo $no_login_notice?>">
                    <input type="hidden" id="login_btn" value="<?php echo $login_btn?>">
                    <input type="hidden" id="cancel_btn" value="<?php echo $login_cancel_btn?>">
                    <input type="hidden" id="refer" value="<?php echo $refer?>">
                    <img id="loading-img" style="display:none" src="<?php echo get_stylesheet_directory_uri().'/assets/images/dobot/loading.gif'?>"/>
                    <span data-status="<?php echo $is_liked ;?>" style="cursor:pointer" data-id="<?php echo $post_id?>" class="video-parise fr <?php echo $like_class ?>" id="<?php echo $like_id?>"><?php echo number_format($liked)?></span>
                </div>
                <div class="video-post-share textcenter">
                    <a href="<?php echo share_to_social($post,'facebook')?>" target="_blank"><span class="share-facebook"></span></a>
                    <a href="<?php echo share_to_social($post,'twitter')?>" target="_blank"><span class="share-twitter"></span></a>
                    <a href="<?php echo share_to_social($post,'google-plus')?>" target="_blank"><span class="share-google"></span></a>
                    <a href="<?php echo share_to_social($post,'pinterest')?>" target="_blank"><span class="share-pinterest"></span></a>
                </div>
            </div>
            <!-- display comments-->
            <?php
            if (comments_open() || '0' != get_comments_number()) :
                comments_template();
            endif;
            ?>

            
        </div>
    </div>
</div>
<div class="customer-tutorial-page">
    <!-- you may also like -->
    <div class="post-margin-35 also-like-cont video-also-like">
        <div class="p36 also-like-cont-tit"><strong><?php _e('You May Also Like');?></strong></div>
        <ul class="cols3-ul-items clearbox outmargin13">
            <?php $videos = get_popular_videos(3)?>
            <?php foreach ($videos as $_video):?>
            <li class="cols3-ul-item">
                <?php get_video_list_html($_video)?>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>