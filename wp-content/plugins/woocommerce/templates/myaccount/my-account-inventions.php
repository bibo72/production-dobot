<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/3/17
 * Time: 16:56
 */
$video_categories = get_video_categories();
$most_views_videos = get_popular_videos(10);
?>

<div class="my-account-inventions dobot-account-right-habg upload-video-right" id="upload-box">
    <!-- <span class="closelink fr"></span> -->
    <div class="inventions-upload-video" id="upload-video">
        <form method="post" class="upload-video-form" id="video-form">
            <input type="hidden" name="id" id="video_id" value="0">
            <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
                <label for="video-link"><?php _e('Flash Address','woocommerce')?> </label>
                <input type="text" name="image_url" id="url" class="woocommerce-Input woocommerce-Input--text input-text"/>
            </p>
            <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
                <label for="video-title"><?php _e('Title','woocommerce')?> </label>
                <input type="text" name="name" id="title" class="woocommerce-Input woocommerce-Input--text input-text"/>
            </p>
            <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
                <label for="video-description"><?php _e('Description','woocommerce')?> </label>
                <textarea name="description" id="description" cols="30" rows="10"></textarea>
            </p>
            <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
                <label for="video-videogallery"><?php _e('Category','woocommerce')?> </label>
                <select name="videogallery_id" id="videogallery_id">
                <?php foreach ($video_categories as $_category){?>
                    <option value="<?php echo $_category->id?>"><?php _e(apply_filters(' ',$_category->name));?></option>
                <?php }?>
                </select>
            </p>
            <input type="hidden" name="inventions" value="inventions">
            <p class="textcenter">
                <input class="button p18 submitbutton" name="save_video" value="Submit" type="submit">
            </p>
        </form>

    </div>
</div>
<?php
global $current_user;
if(count($most_views_videos)){
$current_user_videos = is_user_logged_in() ? get_user_videos($current_user->ID,10) : array();
?>
<div class="inventions-video-history">
    <h3 class="account-set-title"><?php count($current_user_videos) ? _e('MY VIDEO','woocommerce') : _e('RECOMMENDATION VIDEO','woocommerce'); ?></h3>
    <ul class="video-grid-max-4 video-items clearbox">
        <?php $videos = count($current_user_videos) ? $current_user_videos : $most_views_videos?>
        <?php $i=0 ;foreach ($videos as $_video){ $i++;
            $post_id = get_video_relate_post_id($_video->id);
            $liked = get_post_meta($post_id, '_liked', true) ? get_post_meta($post_id, '_liked', true) : 0;
            $post_link = $post_link = get_permalink($post_id);
            $thumb_url = get_thumb_url_by_image_url($_video);
            $user = get_the_video_user($_video->id);
            $user_email = $user->user_email;
            $user_name = $user->user_login;
            ?>
            <li class="video-item <?php if(($i-1) == 0):?>first<?php elseif((($i-1) == 4) || ($i) == count($videos)): ?>last<?php endif;?>">
                <div class='video-item-cont'>
                    <div class="video-head">
                        <div class="user-image"><?php echo get_avatar($user_email, 30);?></div>
                        <div class="user-name"><?php echo $user_name?></div>
                        <div class="video-cat"><?php _e('TAG')?>:<?php _e(apply_filters(' ',get_category_by_videoid($_video->id)->name))?></div>
                    </div>
                    <div class="video-img">
                        <a href="<?php echo esc_url($post_link)?>" style="background-image:url(<?php echo $thumb_url?>);"></a>
                    </div>
                    <div class="video-footer">
                        <div class="video-title"><a href="<?php echo esc_url($post_link)?>"><?php _e(apply_filters(' ',$_video->name))?></a></div>
                        <div class="video-published-date"><?php _e('PUBDATE','woocommerce')?>:&nbsp;<?php echo date('Y/m/d',strtotime($_video->create_date))?></div>
                        <div class="video-ext">
                            <span class="video-parise"><?php echo number_format($liked)?></span>
                            <?php if(count($current_user_videos)):?>
                                <span class="loading-img" id="loading-img<?php echo $_video->id?>" style="display: none"><img src="<?php echo get_stylesheet_directory_uri().'/assets/images/dobot/loading.gif'?>" alt=""></span>
                                <a href="javascript:void (0)" ajax-url="<?php echo admin_url('admin-ajax.php')?>" class="del-video-a" data-id="<?php echo $_video->id?>"><span class="video-action del-video"></span></a>
                                <a href="javascript:void (0)" ajax-url="<?php echo admin_url('admin-ajax.php')?>" video_id="<?php echo $_video->id?>" class="edt-video-a"><span class="video-action edit-video"></span></a>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </li>
        <?php }?>
    </ul>
</div>
<?php }else { ?>
    <div class="no-data-box-notice">
        <p class="no-result-notice"><?php _e('There are no data!','woocommerce')?></p>
    </div>
<?php } ?>
