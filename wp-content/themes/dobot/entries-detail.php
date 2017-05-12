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
$link = esc_url(get_permalink($post->ID));
$refer = reset_page_url_param('refer',urlencode($link),wc_get_page_permalink('myaccount'));
$votes = get_post_meta($post->ID,'_vot',true) ? get_post_meta($post->ID,'_vot',true) :0;
?>

<?php
global $current_contest_cat,$current_user;
$current_contest_cat = $current_contest_cat ? $current_contest_cat : get_contest_id_by_entries($post_id);
$current_contest = get_term_by('id',$current_contest_cat,'contest-category');
$crumb_gallery_name = get_contest_cat_name_by_id($current_contest_cat);
$banner_imag  =  get_term_meta($current_contest_cat,'banner',true);
$contest_status = get_term_meta($current_contest_cat,'status',true);
$term= get_term_by('id',$current_contest_cat,'contest-category');
$name = $term->name;
$contest_subtitle = get_term_meta($current_contest_cat,'subtitle',true);
$banner_text  =  get_term_meta($current_contest_cat,'banner_text',true);
$user_has_vot = check_user_has_vot($current_user->ID,$post->ID);
?>


<div class="entries-detail-head responsive-img" style="background-image:url(<?php echo esc_url($banner_imag ); ?>);">
    <div class="col-full textcenter page-banner-header">
        <div class="pfff text-uppercase p40 contest-detail-banner-header-subtitle">OCEANS OF</div>
        <h1 class="pfff text-uppercase"><?php echo __($name,'storefront')?></h1>
        <div class='page-banner-header-des pfff'><?php echo  __($banner_text,'storefront')?></div>
        <?php if($contest_status == 1):?>
        <div class="contest-join-btn">
            <a href="<?php echo esc_url(reset_page_url_param('cid',$current_contest_cat),wc_get_account_endpoint_url('add-contest'))?>"><button class="button pblue emptybutton" type="button"><?php _e('Join Now','storefront')?></button></a>
        </div>
        <?php endif;?>
    </div>
</div>
<div class="crumb" id="crumbs">
    <div class="col-full">
        <ul>
            <li><a href="<?php echo home_url('');?>"><?php _e('Home','storefront')?></a></li>
            <li><a href="<?php echo home_url('contest-list').'.html';?>"><?php _e('Contest List','storefront')?></a></li>
            <li><a href="<?php echo home_url('contest-list/'.$current_contest->slug).'.html'?>"><?php _e($crumb_gallery_name,'storefront')?></a></li>
            <li><?php echo  __($post->post_title)?></li>
        </ul>
        <div class="back fr"><a href="<?php echo home_url('contest-list/'.$current_contest->slug)?>"><button class="emptybutton backbtn pblue button smallbutton"><?php echo __('Back','storefront')?></button></a></div>
    </div>
</div>
<div class="bgfff">
    <div class="col-full contest-detail-box entries-detail-box">
        <div class="contest-instruct instruction clearbox">
            <div class="instrcu-left info-1 fl <?php if(!$contest_status == 1):?>all-row<?php endif;?>">
                <div class="info-1-up">
                    <div class="clearbox">
                        <div class="contest-title-left fl">
                            <h2 class="title p30"><?php echo __($post->post_title,'storefront')?></h2>
                            <span class="tag pblue text-uppercase"><?php echo  __(get_term_meta($current_contest_cat,'contest_tag',true),'storefront');?></span>
                            <span class="user"><?php echo __('By','storefront').' '.'<label class="pblue">'.$user->user_login.'</label>'; ?></span>
                            <span class="date"><?php echo date('M, d, Y',strtotime($post->post_date))?></span>
                            <span class="share">
                                <a href="<?php echo share_to_social($post,'facebook')?>" target="_blank"><span class="share-facebook"></span></a>
                                <a href="<?php echo share_to_social($post,'twitter')?>" target="_blank"><span class="share-twitter"></span></a>
                                <a href="<?php echo share_to_social($post,'google-plus')?>" target="_blank"><span class="share-google"></span></a>
                                <a href="<?php echo share_to_social($post,'pinterest')?>" target="_blank"><span class="share-pinterest"></span></a>
                            </span>
                            <div class="below-pad-show">
                                <span class="vote-icon" id="vote-icon"><?php echo number_format($votes).' '.__('VOTES','storefront')?></span>
                                <span class="views"><?php echo number_format($views).' '.__('VIEWS','storefront')?></span>
                            </div>
                        </div>
                        <div class="contest-title-right fr">
                            <div class="vote-icon" id="vote-icon"><?php echo number_format($votes).' '.__('VOTES','storefront')?></div>
                            <div class="views"><?php echo number_format($views).' '.__('VIEWS','storefront')?></div>
                        </div>
                    </div>
                </div>
                
                
            </div>
            <?php if($contest_status == 1):?>
            <div class="instruct-right info-2 fr">
                <?php if($user_has_vot):?>
                    <button class="button" disabled='disabled'><?php echo __('Voted!','storefront');?></button>
                <?php else:?>
                    <?php if(!is_user_logged_in()):?>
                        <?php $current_url = curPageURL().'#crumbs';?>
                        <a href="<?php echo esc_url(reset_page_url_param('refer',urlencode($current_url),wc_customer_login_url()))?>"><button class="button" data-post="<?php echo $post_id?>"><?php echo __('Vote Now!','storefront');?></button></a>
                    <?php else:?>
                        <button class="button" id="vote-btn" data-post="<?php echo $post_id?>"><?php echo __('Vote Now!','storefront');?></button>
                    <?php endif;?>
                <?php endif;?>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<div class="col-full">
    <div class="video-info-box entries-info-box">
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
        <?php } ?>
        <div class="tutorial-content">
            <?php if($post->post_content):?>
                <?php echo __($post->post_content)?>
            <?php else:?>
                <?php echo __('The tutorial has no content','storefront')?>
            <?php endif;?>
        </div>
        <div class="video-info">
            <div class="description">
                <?php if(esc_html(get_post_meta($post_id,'post_description',true))):?>
                <div class="post-margin-35">
                    <?php echo get_post_meta($post_id,'post_description',true)?>
                </div>
                <?php endif;?>
                <!-- tag -->
                <div class="post-margin-35 tagsection">
                    <div class="tag-cont">
                        <span class="post-tag"><strong>TAGS: </strong><?php echo  __(get_term_meta($current_contest_cat,'contest_tag',true),'storefront');?></span>
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
</div>
    <script type="text/javascript">
    (function ($) {
        var dobot_obj = {"ajax_url":"<?php echo admin_url('admin-ajax.php')?>"};
        $('#vote-btn').on('click',function () {
            $(this).html('<?php _e('Voting...','storefront')?>');
            var $post_id = parseInt($(this).data('post'));
            $.post(dobot_obj.ajax_url,{post_id:$post_id,action:'vot',page:'detail'},function (response) {
                var json = JSON.parse(response);
                if(json.status == 200){
                    $('#vote-icon').html(json.votes+"<?php echo ' '.__('VOTES','storefront')?>");
                    $('#vote-btn').remove();
                    $('.instruct-right').append(json.html);
                }
            });
        });
    })(jQuery);
</script>
