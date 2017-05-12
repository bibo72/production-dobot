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
global  $wpdb;
$query = $wpdb->prepare("SELECT post_author FROM wp_posts WHERE ID = %d",$post_id);
$user_id= $wpdb->get_results($query);
$user = get_user_by('ID',$user_id[0]->post_author);
$cats = get_the_terms($post_id,'tutorial-category');
$cat = array();
if($cats){
    foreach($cats as $key => $_cat) {
        $cat[] = $_cat->name;
    }
    $categories = implode(',',$cat);
}


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

?>

<?php
global $current_tutorial_cat;
$crumb_gallery_slug = null;
if($current_tutorial_cat){
    $current_tutorial_cat_object = get_term_by('id',$current_tutorial_cat,'tutorial-category');
    $crumb_gallery_name = $current_tutorial_cat_object->name;
    $crumb_gallery_slug = $current_tutorial_cat_object->slug;
}else{
    $crumb_gallery_name = __('Tutorials','storefront');
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
            <span class="video-post-date"><?php echo date("M, d, Y",strtotime($post->post_date_gmt));?></span>
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
                <?php if($current_tutorial_cat):?>
                    <li><a href="<?php echo home_url('tutorials-center/'.$crumb_gallery_slug).'.html';?>"><?php _e($crumb_gallery_name,'storefront')?></a></li>
                <?php else:?>
                    <li><a href="<?php echo home_url('tutorials-center').'.html';?>"><?php _e($crumb_gallery_name,'storefront')?></a></li>
                <?php endif;?>
                <li><?php echo  __($post->post_title)?></li>
            </ul>

        </div>
    </div>
</div>

    <div class="video-info-box">
            <!--download attached file-->
            <?php $_enclosure_id = get_post_meta($post_id,'_enclosure_id',true)?>
            <?php $_attached_file = get_post_meta($_enclosure_id,'_wp_attached_file',true)?>
            <?php if($_enclosure_id):?>
            <div class="enclosure-download">
                <?php
                    $file_url = parse_url($_attached_file);
                    $path = ABSPATH.$file_url['path'];
                    $fileInfo = PATHINFO($path);
                    $file_name = $fileInfo['basename'];
                    $file_size = sizecount(filesize($path));
                    $file_date = date('Y. m. d',strtotime($post->post_date_gmt));
                    $file_description = 'ALARM definition for Dobot Magician. The controller will monitor the system status in all Dobot products. The external software can read or clear the related alarm status according to this file.';
                    //download
                    if($_POST['down']){
                        dobot_download($path,$file_name);
                    }
                ?>
                <form method="post" class="clearbox">
                    <img class="fl" src="<?php echo get_bloginfo('template_directory').'/assets/images/dobot/file.png' ?>">
                    <div class="down-file-info">
                        <div class="file-info clearbox">
                            <div class="fl">
                                <div class="file-name p18 p000"><?php echo $file_name?></div>
                                <div class="file-date"><?php echo $file_date?></div>
                            </div>
                            <div class="fr file-size-download below-pad-hide">
                                <span class="file-size"><?php echo $file_size;?></span>
                                <input type="hidden" name="down" value="1">
                                <button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                            </div>
                        </div>
                        <div class="file-descirpiton"><?php echo $file_description?></div>
                        <div class="below-pad-show file-size-download">
                            <span class="file-size"><?php echo $file_size;?></span>
                            <input type="hidden" name="down" value="1">
                            <button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                        </div>
                    </div>
                </form>
            </div>
            <?php endif;?>
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
            <?php if($post->post_content):?>
                <?php echo __($post->post_content)?>
            <?php else:?>
                <?php echo __('The tutorial has no content','storefront')?>
            <?php endif;?>
        </div>
        <div class="video-info">
            <div class="description">
                <div class="post-margin-35">
                    <?php echo get_post_meta($post_id,'post_description',true)?>
                </div>
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
    <div class="post-margin-35 also-like-cont tutorial-also-like">
        <div class="p36 also-like-cont-tit"><strong><?php _e('You May Also Like','storefront');?></strong></div>
        <ul class="cols3-ul-items clearbox">
            <?php $also_likes = get_the_most_views_and_like_tutorials(3)?>
            <?php foreach ($also_likes  as $_post):
                get_alsolike_tutorial_list($_post);
             endforeach;?>
        </ul>
    </div>
</div>