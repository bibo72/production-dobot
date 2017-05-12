
<section id="huge_it_videogallery_content_<?php echo esc_attr($gallery_videoID); ?>" class="gallery-video-content"
         data-gallery-video-id="<?php echo esc_attr($gallery_videoID); ?>"
         data-gallery-video-perpage="<?php echo $num; ?>">
    <div id="huge_it_videogallery_container_<?php echo esc_attr($gallery_videoID);?>"
         class="huge_it_videogallery_container super-list variable-sizes clearfix view-<?php echo esc_attr($view_slug); ?>"
         data-show-center="<?php echo esc_attr($gallery_video_get_option['gallery_video_ht_view2_content_in_center_lightbox']); ?>">
        <div id="huge_it_videogallery_container_moving_<?php echo esc_attr($gallery_videoID); ?>"
             class="super-list variable-sizes clearfix">
            <input type="hidden" class="pagenum" value="1"/>
            <input type="hidden" id="total" value="<?= $total; ?>"/>
            <?php
            $colnum=0;
            foreach ($page_videos as $key => $row) {
                $gallery_videoID = get_category_by_videoid($row->id)->id;
                $gallery_videoName = get_category_by_videoid($row->id)->name;
                $link = str_replace('__5_5_5__', '%', $row->sl_url);
                $descnohtml = strip_tags(str_replace('__5_5_5__', '%', $row->description));
                $result = substr($descnohtml, 0, 50);
                $post_id = get_video_relate_post_id($row->id);
                $liked = $val = get_post_meta($post_id, '_liked', true) ? get_post_meta($post_id,'_liked',true) : 0;
                $views = get_post_meta($post_id,'views',true) ? get_post_meta($post_id,'views',true) : 0;
                $colnum++;
                ?>
                <?php if($colnum%3==1):?>
                <ul class='cols3-ul-items clearbox'>
                <?php endif;?>
                <li class='cols3-ul-item'>
                <div data-id="<?php echo esc_attr($row->id); ?>"
                     class="video-item-cont video-element_<?php //echo $gallery_videoID; ?> video-element" tabindex="0"
                     data-symbol="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                     data-category="alkaline-earth">
                    <div class="user-and-category-block video-head">
                        <?php  $user = get_the_video_user($row->id)?>
                        <div class="user-image"><?php echo get_avatar($user->user_email,30)?></div>
                        <div class="user-name"><?php echo $user->user_login ?></div>
                        <div class="video-cat"><?php _e(apply_filters(' ',$gallery_videoName))?></div>
                    </div>
                    <div class="video-img image-block_<?php //echo esc_attr($gallery_videoID); ?>">
                        <?php
                        $videourl = gallery_video_get_video_id_from_url($row->image_url);
                        if ($videourl[1] == 'youtube') {
                            if (empty($row->thumb_url)) {
                                $thumb_pic = '//img.youtube.com/vi/' . $videourl[0] . '/mqdefault.jpg';
                            } else {
                                $thumb_pic = $row->thumb_url;
                            }
                            ?>
                            <a class="vyoutube huge_it_videogallery_item group<?php echo esc_attr($gallery_videoID); ?>"
                               href="//www.youtube.com/embed/<?php echo $videourl[0]; ?>"
                               title="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                               data-id="<?php echo esc_attr($row->id); ?>">
                                <img src="<?php echo esc_attr($thumb_pic); ?>"
                                     alt="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"/>
                                <div class="play-icon <?php echo $videourl[1]; ?>-icon"></div>
                            </a>
                            <?php
                        } elseif ($videourl[1] == 'youku'){
                            if (empty($row->thumb_url)) {
                                $thumb_pic = get_youku_image_from_url($row->image_url);
                            } else {
                                $thumb_pic = $row->thumb_url;
                            }
                            ?>
                            <a class="vyoutube huge_it_videogallery_item group<?php echo esc_attr($gallery_videoID); ?>"
                               href="//player.youku.com/embed/<?php echo $videourl[0]; ?>"
                               title="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                               data-id="<?php echo esc_attr($row->id); ?>">
                                <img src="<?php echo esc_attr($thumb_pic); ?>"
                                     alt="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"/>
                                <div class="play-icon <?php echo $videourl[1]; ?> youtube-icon"></div>
                            </a>
                            <?php
                        } else {
                            $hash = @unserialize(wp_remote_fopen($protocol . "vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                            if (empty($row->thumb_url)) {
                                $imgsrc = $hash[0]['thumbnail_large'];
                            } else {
                                $imgsrc = $row->thumb_url;
                            }
                            ?>
                            <a class="vvimeo huge_it_videogallery_item group<?php echo esc_attr($gallery_videoID); ?>"
                               href="//player.vimeo.com/video/<?php echo $videourl[0]; ?>"
                               title="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                               data-id="<?php echo esc_attr($row->id); ?>">
                                <img src="<?php echo esc_attr($imgsrc); ?>" alt=""/>
                                <div class="play-icon <?php echo $videourl[1]; ?>-icon"></div>
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                    <?php if (str_replace('__5_5_5__', '%', $row->name) != "") { ?>
                        <div class="video-footer title-block-dobot-_<?php echo esc_attr($gallery_videoID); ?>">
                        <?php if($link != '') :?>
                            <!--<a href="<?php echo $link; ?>" <?php if ($row->link_target == "on") {
                                echo 'target="_blank"';
                            } ?>>
                        <?php endif;?>
                            <?php //echo str_replace('__5_5_5__', '%', $row->name); ?>
                        <?php if($link != '') :?>
                            </a>-->
                        <?php endif;?>
                            <?php $post_link = get_permalink($post_id)?>
                            <div class="video-title"><a href="<?php echo esc_url($post_link )?>"><?php _e(apply_filters(' ',$row->name))?></a></div>
                            <div class="video-ext-data clearbox">
                                <div class="video-kaopin fr">
                                    <span class="video-parise"><?php echo number_format($liked)?></span>
                                    <span class="views"><?php echo number_format($views)?></span>
                                </div>
                                <div class="video-share fl">
                                    <a href="<?php echo share_video_to_facebook($row->id)?>">Facebook</a>
                                    <a href="<?php echo share_video_to_twitter($row->id)?>">Twitter</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </li>
            <?php if($colnum%3==0 || $colnum==count($page_videos)):?>
                </ul>
            <?php endif;?>
                <?php
            } ?>
 </div>
</div>

    <?php
    $a = $disp_type;
    if ($a == 1 && $num < $total_videos) {
        $gallery_video_lightbox_nonce = wp_create_nonce('gallery_video_lightbox_nonce');
        ?>
<!--   <div class="load_more4">
        <div class="load_more_button4"
               data-lightbox-nonce-value="<?php echo esc_attr($gallery_video_lightbox_nonce); ?>"><?= esc_attr($gallery_video_get_option['gallery_video_video_ht_view4_loadmore_text']); ?></div>
            <div class="loading4"><img
                    src="<?php if ($gallery_video_get_option['gallery_video_video_ht_view4_loading_type'] == '1') {
                        echo $path_site . '/arrows/loading1.gif';
                    } elseif ($gallery_video_get_option['gallery_video_video_ht_view4_loading_type'] == '2') {
                       echo $path_site . '/arrows/loading4.gif';
                   } elseif ($gallery_video_get_option['gallery_video_video_ht_view4_loading_type'] == '3') {
                        echo $path_site . '/arrows/loading36.gif';
                   } elseif ($gallery_video_get_option['gallery_video_video_ht_view4_loading_type'] == '4') {
                       echo $path_site . '/arrows/loading51.gif';
                    } ?>"></div>
            </div>-->
        <?php
    } elseif ($a == 0) {
        ?>
       <div class="paginate4">
            <?php
            $actual_link = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "";

            echo custom_page($total_videos,$page,$num);
//            $checkREQ = '';
//            $pattern = "/\?p=/";
//            $pattern2 = "/&page-video[0-9]+=[0-9]+/";
//            if (preg_match($pattern, $actual_link)) {
//                if (preg_match($pattern2, $actual_link)) {
//                    $actual_link = preg_replace($pattern2, '', $actual_link);
//                }
//                $checkREQ = $actual_link . '&page-video' . $gallery_videoID . $pID;
//            } else {
//                $checkREQ = '?page-video' . $gallery_videoID . $pID;
//            }
//            $pervpage = '';
//
//            if ($page != 1)
//                $pervpage = '<a href= ' . $checkREQ . '=1><i class="icon-style4 hugeiticons-fast-backward" ></i></a>
//                                   <a href= ' . $checkREQ . '=' . ($page - 1) . '><i class="icon-style4 hugeiticons-chevron-left"></i></a> ';
//            $nextpage = '';
//            if ($page != $total)
//                $nextpage = ' <a href= ' . $checkREQ . '=' . ($page + 1) . '><i class="icon-style4 hugeiticons-chevron-right"></i></a>
//                                   <a href= ' . $checkREQ . '=' . $total . '><i class="icon-style4 hugeiticons-fast-forward" ></i></a>';
//            echo $pervpage . $page . '/' . $total . $nextpage;
            ?>
        </div>
        <?php
    }
    ?>
<!--</section>-->