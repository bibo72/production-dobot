<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/3/28
 * Time: 17:25
 */
$total = new WP_Query( 'showposts=-1&orderby=date&post_type=event');
$totals = $total->post_count;
$third_total_posts['all'] = $total->posts;
foreach ($total->posts as $_r){
    if(get_post_meta($_r->ID,'news_link',true)){
        $third_total_posts['media'][] = $_r;
    }else{
        $third_total_posts['news'][] = $_r;
    }
}
if(isset($_GET['type'])){
    $last_total_posts = $third_total_posts[$_GET['type']];
}else{
    $last_total_posts = $total->posts;
}

$persize = 12;
$paged = $_GET['pag'] >= 1 ? $_GET['pag'] : 1;
$total_pages = ceil(count($last_total_posts)/$persize);
$paged = $paged > $total_pages ? $total_pages : $paged;
$page = custom_page($totals,$paged,$persize,false);

$resutl = new WP_Query( 'showposts='.$persize.'&paged='.$paged.'&orderby=date&post_type=event');
$type_posts = array();
$posts = $resutl->posts;
$third_posts['all'] = $resutl->posts;
foreach ($posts as $_r){
    if(get_post_meta($_r->ID,'news_link',true)){
        $third_posts['media'][] = $_r;
    }else{
        $third_posts['news'][] = $_r;
    }
}
if(isset($_GET['type'])){
    $last_posts = $third_posts[$_GET['type']];
}else{
    $last_posts = $posts;
}

?>

<div class="pro-tab-cont news-nav cms-pro-tab-cont full-screen">
    <div class="col-full">
        <div class="col-full-cont clearbox">
            <div class="pro-title"><?php _e("Company info",'storefront')?></div>
            <div class="pro-tab-cont-items">
                <ul class="tabs wc-tabs">
                    <li class="active"><span><?php _e("News &amp; Event",'storefront')?></span></li>
                    <li class=""><a href="<?php echo esc_url( get_page_url( 'about-us"' ) );?>"><?php _e("About Us",'storefront')?></a></li>
                    <li class=""><a href="<?php echo esc_url( get_page_url( 'contact-us' ) );?>"><?php _e("Contact Us",'storefront')?></a></li>
                    <li class=""><a href="<?php echo esc_url( get_page_url( 'join-us' ) );?>"><?php _e("Join Us",'storefront')?></a></li>
                    <li class=""><a href="<?php echo esc_url( get_page_url( 'partnership' ) );?>"><?php _e("Partnership",'storefront')?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>



<div class="news-events-box">
    <div class="news-events-box-header textcenter">
        <?php $type = isset($_GET['type']) ? $_GET['type'] : '';?>
        <ul class="news-events-nav clearbox">
            <li class="<?php if($type == '' || $type == 'all'):?>active<?php endif;?>"><a href="?type=all"><?php _e('ALL','storefront')?></a></li>
            <li class="<?php if($type == 'news'):?>active<?php endif;?>"><a href="?type=news"><?php _e('News','storefront')?></a></li>
            <li class="<?php if($type == 'media'):?>active<?php endif;?>"><a href="?type=media"><?php _e('Media Coverage','storefront')?></a></li>
        </ul>
    </div>
    <?php if($last_posts):?>
    <ul class="news-events-list clearbox">
        <?php $i=0; foreach ($last_posts as $_post):$i++;?>
            <li class="new-item">
                <div class="new-item-cont">
                    <div class="news-image">
                        <img src="<?php echo (get_the_post_thumbnail_url($_post->ID,'medium')); ?>" alt="<?php _e($_post->post_title,'storefront')?>">
                    </div>
                    <div class="news-list-intro">
                        <div class="news-title">
                            <?php
                                $link = esc_url(get_permalink($_post->ID));
                                $thirdlink='';
                                $target='';
                                if(get_post_meta($_post->ID,'news_link',true)){
                                    $thirdlink='<span class="third-link"></span>';
                                    $target='_blank';
                                    $link = get_post_meta($_post->ID,'news_link',true);
                                }
                                echo $thirdlink;
                            ?>
                            <a class="p18 p000" target="<?php echo $target;?>" href="<?php echo $link ?>"><?php echo __($_post->post_title); ?></a>
                        </div>
                        <div class="news-description">
                            <?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $_post->post_content)), 0, 150,"……");?>
                        </div>
                        <div class="news-pub-date">
                            <span><?php echo date('M, d, Y',strtotime($_post->post_date_gmt))?></span>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; wp_reset_query(); ?>
    </ul>
        <div class="paged bckfff contest-entries-page">
            <?php echo $page;?>
        </div>
    <?php else:?>
        <p><?php _e('There are not any news.','storefront')?></p>
    <?php endif;?>
</div>