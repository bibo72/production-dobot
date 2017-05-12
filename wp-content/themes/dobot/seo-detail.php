<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/7
 * Time: 14:40
 */
$post_id = get_the_ID();
$post = get_post($post_id);
$liked = get_post_meta($post_id,'_liked',true) ? get_post_meta($post_id,'_liked',true) : 0;
$views = get_post_meta($post_id,'views',true) ? get_post_meta($post_id,'views',true) : 0;
$user = get_user_by('ID',$post->post_author);
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
$link = $link = esc_url(get_permalink($post->ID));
$refer = reset_page_url_param('refer',urlencode($link),wc_get_page_permalink('myaccount'));
$categories = get_seo_cat_by_postid($post_id)->name;
global $current_seo_cat;
$crumb_gallery_slug = null;
if($current_seo_cat){
    $current_tutorial_cat_object = get_term_by('id',$current_seo_cat,'seo-category');
    $crumb_gallery_name = $current_tutorial_cat_object->name;
}else{
    $crumb_gallery_name = __('Resources','storefront');
}
?>


<div class="full-screen category-customer-video-head">
    <div class="col-full textcenter">
        <h1><?php echo __($post->post_title)?></h1>
        <div class="video-post-note">
            <?php if($categories!=''):?>
                <span class="video-post-cat"><strong class="pblue"><?php echo __($categories)?></strong></span>
            <?php endif;?>
            <?php if($user->user_login!=''):?>
                <span class="video-post-anthor">BY <span class="pblue"><?php echo __($user->user_login)?></span></span>
            <?php endif;?>
            <span class="video-post-date"><?php echo date("M, d, Y",strtotime($post->post_date));?></span>
            <span class="video-parise"> <?php echo number_format($liked)?></span>
            <span class="views"> <?php echo number_format($views)?></span>
        </div>
        <div class="video-post-share pfff">
            <a href="<?php echo share_to_social($post,'facebook')?>" target="_blank"><span class="share-facebook"></span></a>
            <a href="<?php echo share_to_social($post,'twitter')?>" target="_blank"><span class="share-twitter"></span></a>
            <a href="<?php echo share_to_social($post,'google-plus')?>" target="_blank"><span class="share-google"></span></a>
            <a href="<?php echo share_to_social($post,'pinterest')?>" target="_blank"><span class="share-pinterest"></span></a>
        </div>
    </div>
</div>
<div class="full-screen bgfff">
    <div class="col-full">
        <div class="crumb" id="crumbs">
            <ul>
                <li><a href="<?php echo home_url()?>"><?php _e('Home','storefront')?></a></li>
                <?php if($current_seo_cat):?>
                    <li><a href="<?php echo reset_page_url_param('seoid',$current_seo_cat,get_page_url('resource-seo'));?>#seo"><?php _e($crumb_gallery_name,'storefront')?></a></li>
                <?php else:?>
                    <li><a href="<?php echo get_page_url('resource-seo');?>"><?php _e($crumb_gallery_name,'storefront')?></a></li>
                <?php endif;?>
                <li><?php echo  __($post->post_title)?></li>
            </ul>

        </div>
    </div>
</div>

<div class="video-info-box">
    <?php
    if ( has_post_thumbnail() ) {
        ?>
        <div class="video-info-box-img">
            <?php
            the_post_thumbnail( 'full');
            ?>
        </div>
        <?php
    }
    ?>
    <div class="tutorial-content">
        <h2><?php echo $post->post_title?></h2>
        <?php if($post->post_content):?>
            <?php echo __($post->post_content)?>
        <?php else:?>
            <?php echo __('The resource  has no content','storefront')?>
        <?php endif;?>
    </div>
    <div class="video-info">
        <div class="description">
            <!-- tag -->
            <div class="post-margin-35 tagsection">
                <div class="tag-cont">
                    <span class="post-tag"><strong>TAGS: </strong><?php echo $categories?></span>
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
        </div>
    </div>
    <!-- display comments-->
    <?php
    if (comments_open() || '0' != get_comments_number()) :
        comments_template();
    endif;
    ?>
</div>
<div class="customer-tutorial-page">
    <!-- you may also like -->
    <?php
    $popular_cat = get_term_by('slug','popular','seo-category');
    $popular_cat_below_posts = get_seo_cat_below_posts($popular_cat->term_id,20,true);
    shuffle($popular_cat_below_posts['posts']);
    foreach ($popular_cat_below_posts['posts'] as $key=> $_seo){
        if($_seo->ID == $post_id) {
            unset($popular_cat_below_posts['posts'][$key]);
        }
    }
    if(count($popular_cat_below_posts['posts'])):
    ?>
    <div class="post-margin-35 also-like-cont tutorial-also-like">
        <div class="p36 also-like-cont-tit"><strong><?php _e('Related Resource','storefront');?></strong></div>
        <ul class="cols3-ul-items clearbox">
            <?php
            foreach ($popular_cat_below_posts['posts'] as $_seo):
                $title = $_seo->post_title;
                $short_desc = mb_strimwidth(strip_tags(apply_filters('the_content', $_seo->post_content)), 0, 100,"");
                $seo_image_url = get_post_thumbnail_url($_seo->ID);
                ?>
                <li class="seo-right-item">
                    <a style="background-image: url('<?php echo esc_url($seo_image_url)?>')" href="<?php echo esc_url(get_permalink($_seo->ID))?>" title="<?php echo $title?>">
                    </a>
                    <div class="seo-title">
                        <a href="<?php echo esc_url(get_permalink($_seo->ID))?>" title="<?php echo $title?>"> <?php echo $title;?></a>
                    </div>
                </li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php endif;?>
</div>