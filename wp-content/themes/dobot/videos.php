<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/3/21
 * Time: 10:55
 */
?>
<div class="customer-video-header responsive-img" style="background-image:url(<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/dobot/video_head_bg.jpg);">
    
    <div class="col-full">
        <div class="page-banner-header textcenter">
            <h1 class="pfff"><?php _e('DOBOT VIDEO GALLERY','storefront')?></h1>
            <div class='page-banner-header-des pfff'><?php _e('Offering helpful and professional videos for technical problems and updated topic.
','storefront')?></div>
            <div class="video-search">
                <form role="search" method="get" class="search-form" >
                    <input class="search-field" placeholder="<?php _e('Search Video','storefront')?>" value="" name="q" type="search" />
                    <input class="button searchbutton btn333" value="<?php _e('Search','storefront')?>" type="submit" />
                </form>
            </div>
        </div>
    </div>
</div>
<div class="customer-video-page customer-tutorial-page">
    <div class="customer-video-content">
        <?php  $video_cat= get_video_categories()?>
        <div class="contest-detail-nav video-list-nav">
            <div class="video-list-toolbar line0">
                <div class="col-full">
                    <div class="mobile-list-nav">
                        <span>
                        <?php if(!($_GET['videogallery_id']) || $_GET['videogallery_id']=='all'){
                            _e('All','storefront');
                        }else{
                            foreach ($video_cat as $_cat){
                                if($_cat->id==$_GET['videogallery_id']){
                                    echo __($_cat->name,'storefront');
                                }
                            }
                        }
                        ?>
                        </span>
                    </div>
                    <ul class="status-item tabs">
                        <li class="<?php echo !($_GET['videogallery_id']) || $_GET['videogallery_id']=='all' ? 'active' :''?>">
                            <a href="<?php echo get_page_url('videos-center')?>#videos">
                                <?php echo __('All','storefront')?>
                            </a>
                        </li>
                        <?php $i=0;foreach ($video_cat as $_cat):$i++?>
                            <?php if($i <= 4):?>
                            <li class="<?php echo $_cat->id==$_GET['videogallery_id'] ? 'active' :''?>">
                                <a href="<?php echo reset_page_url_param('videogallery_id',$_cat->id)?>#videos">
                                    <?php echo __($_cat->name,'storefront')?>
                                </a>
                            </li>
                            <?php endif;?>
                        <?php endforeach;?>
                        <?php if(count($video_cat)>4):?>
                        <li class="other">
                            <a href="javascript:void(0)"><?php echo __('Others','storefront')?></a>
                        </li>
                        <?php endif;?>
                    </ul>
                </div>
            </div>
        </div>
        <?php if(count($video_cat)>4):?>
            <div class="other-cat bgfff">
                <div class="col-full">
                    <ul>
                        <?php $i=0;foreach ($video_cat as $_cat):$i++?>
                            <?php if($i > 4):?>
                                <li class="<?php echo $_cat->id== $_GET['videogallery_id'] ? 'is-active' :''?>">
                                    <a href="<?php echo reset_page_url_param('videogallery_id',$_cat->id);?>#videos">
                                        <?php echo __($_cat->name,'storefront')?>
                                    </a>
                                </li>
                            <?php endif;?>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        <?php endif;?>
        <?php if($_GET['videogallery_id'] == 'all' || !$_GET['videogallery_id']):?>
        <?php $popular_videos = get_popular_videos();?>
        <?php if($popular_videos && !isset($_GET['q'])):?>
        <div class="recommended-video-content bgfff">
            <div class="most_like_nav col-full">
                <div class="popular-title clearbox p36"><?php _e('Most popular videos','storefront')?></div>
                <div class="clearbox">
                    <div class="popular-main fl">
                        <div class="popular-first" >
                            <?php $first = array_slice($popular_videos,0,1)?>
                            <?php get_video_list_html($first[0],false)?>
                        </div>
                    </div>
                        
                    <div class="popular-right fr">
                        <div class="popular-second">
                            <ul class="">
                            <?php $second = array_slice($popular_videos,1,2)?>
                            <?php foreach ($second  as $index=> $_sed):?>
                                <li class="">
                                    <?php get_video_list_html($_sed,false)?>
                                </li>
                            <?php endforeach;?>
                            </ul>
                        </div>
                        
                    </div>
                </div>
                <?php $third =  array_slice($popular_videos,3,2); ?>
                <div class="pointer-third">
                    <ul class="cols2-ul-items clearbox">
                    <?php foreach ( $third as $video){?>
                        <li class="cols2-ul-item">
                            <?php get_video_list_html($video,false)?>
                        </li>
                    <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif;?>
        <?php endif;?>
        <div class="col-full video-list-box">
            <?php
            global  $wp_query;
            $videogallery_id = 'all';

            if(isset($_GET['videogallery_id'])){
                $videogallery_id = $_GET['videogallery_id'];
            }
            $result = get_videogallery_bellow_videos($videogallery_id);
            $videos = $result['videos'];
            $count = $result['count'];
            ?>
            <?php if(count($videos)){
            ?>
            <div class="tutorial-content-list video-list-cont tutorial-list-cont" id="videos">
                <div class="list-toolbar clearbox">
                    <?php if(isset($_GET['q']) && $_GET['q']):?>
                    <div class="search-for">
                        <h3><?php echo __(sprintf("Search results for '%s'",$_GET['q']),'storefront')?></h3>
                    </div>
                    <?php endif;?>
                    <div class="all-total fl">
                        <?php _e('All Videos: ')?><strong class="pblue"><?php echo number_format($count).'</strong> '. __('videos posted','storefront')?>
                    </div>
                    <div class="fr filter-tool">
                        <select class="" onchange="window.location.href=this.value">
                            <option value="<?php echo reset_page_url_param('order','create_date').'#videos'?>" <?php echo $_GET['order'] == 'create_date' ? 'selected="selected"' : "" ;?>><?php _e('Date','storefront')?></option>
                            <option value="<?php echo reset_page_url_param('order','views').'#videos'?>" <?php echo $_GET['order'] == 'views' ? 'selected="selected"' : "" ;?>><?php _e('Views','storefront')?></option>
                            <option value="<?php echo reset_page_url_param('order','name').'#videos'?>" <?php echo $_GET['order'] == 'name' ? 'selected="selected"' : "" ;?>><?php _e('Name','storefront')?></option>
                        </select>
                    </div>
                    <div class="fr filter-tool-tit"><?php _e('Sort By: ','storefront')?></div>
                </div>
                <div class="video-list ready-video-list clearbox">
                    <ul class="cols3-ul-items">
                        <?php foreach ($videos as  $_video):?>
                            <li class="cols3-ul-item">
                                <?php get_video_list_html($_video);?>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
                <nav class="video-navi ajax-more" id="tutorial-navi">
                    <?php echo $result['page']?>
                </nav>
                <div class="dobot-loading-more textcenter" style="display: none">
                    <img src="<?php echo get_bloginfo('template_directory').'/assets/images/dobot/loading.gif' ?>"/>
                </div>
            </div>
            <?php }else{?>
                <div class="tutorial-list-box col-full">
                    <div class="tutorial-content-list video-list-cont clearbox">
                        <?php if(isset($_GET['q']) && $_GET['q']):?>
                            <div class="search-for">
                                <h3><?php echo __(sprintf("Search results for '%s'",$_GET['q']),'storefront')?></h3>
                            </div>
                        <?php endif;?>
                        <div class="no-data-box-notice">
                            <p class="no-result-notice"><?php _e('There are no data!','woocommerce')?></p>
                        </div>
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
</div>
