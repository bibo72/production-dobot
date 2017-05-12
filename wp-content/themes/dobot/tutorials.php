<?php
/**
 * The loop template file.
 *
 * Included on pages like index.php, archive.php and search.php to display a loop of posts
 * Learn more: http://codex.wordpress.org/The_Loop
 *
 * @package storefront
 */
$categories = get_tutorial_category();
$tutorial_slug = $GLOBALS['tutorial_slug'];
$tutorial_cat_id = 0;
if($tutorial_slug){
    $tutorial_cat = get_term_by('slug',$tutorial_slug,'tutorial-category');
    $tutorial_cat_id = $tutorial_cat->term_id;
}
?>
<div class="customer-video-header responsive-img" style="background-image:url(<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/dobot/tutorial_head_bg.jpg);">
    <div class="col-full">
        <div class="page-banner-header textcenter">
            <h1 class="pfff"><?php _e('DOBOT TUTORIAL CENTER','storefront')?></h1>
            <div class='page-banner-header-des pfff'><?php _e('Offering helpful and professional tutorials for technical problems and updated topic.
','storefront')?></div>
            <div class="video-search">
                <form role="search" method="get" class="search-form" >
                    <input class="search-field" placeholder="<?php _e('Search Tutorial','storefront')?>" value="" name="q" type="search" />
                    <input class="button searchbutton btn333" value="<?php _e('Search','storefront')?>" type="submit" />
                </form>
            </div>
        </div>
    </div>
</div>

<div class="customer-video-page customer-tutorial-page">
    <div class="customer-video-content">
        <div class="contest-detail-nav video-list-nav tutorial-list-nav">
            <div class="line0">
                <div class="col-full">
                    <div class="mobile-list-nav">
                        <span>
                        <?php if(!$tutorial_slug){
                            _e('All','storefront');
                        }else{
                            foreach ($categories as $index=>$_cat){
                                if($tutorial_cat_id==$_cat->term_id){
                                    _e(apply_filters(" ",$_cat->name));
                                }
                            }
                        }
                        ?>
                        </span>
                    </div>
                    <ul class="status-item tabs">
                        <li class="<?php echo  ( !$tutorial_slug )? 'active' : 'no-active' ?>">
                            <a href="<?php echo get_page_url('tutorials-center')?>#tutorials">
                                <?php  _e('All','storefront')?>
                            </a>
                        </li>
                        <?php foreach ($categories as $index=>$_cat):?>
                            <li class="<?php echo $tutorial_cat_id == $_cat->term_id ? 'active' : 'no-active' ?>">
                                <a href="<?php echo home_url('tutorials-center/'.$_cat->slug).'.html';?>#tutorials">
                                    <?php _e(apply_filters(" ",$_cat->name));?>
                                </a>
                            </li>
                        <?php endforeach;?>
                    </ul>
                </div>
            </div>
        </div>

        <!--Most popular tutorials-->
        <?php if(!$tutorial_slug):?>
        <?php $popular = get_the_most_views_and_like_tutorials(12)?>
        <?php if(count($popular) && !isset($_GET['q'])):
            $sliders = intval(count($popular)/4);
        ?>
        <div class="recommended-video-content bgfff">
            <div class="col-full">
                <div class="tutorial-nav-img clearbox">
                    <div class="popular-title clearbox p36"><?php _e('Most popular tutorials','storefront')?></div>
                    <div class="tutorial-swiper-wrapper">
                    <ul class="swiper-wrapper">
                    <?php for($i=0; $i< $sliders ;$i++):?>
                        <?php $popular_tutorial = array_slice($popular,$i*4,4);?>
                        <li class="popular-tutorial swiper-slide" id="slider-<?php echo $i;?>">
                            <ul class="slider-main">
                                <?php get_tutorial_list($popular_tutorial[0],'full');?>
                            </ul>
                            <ul class="slider-sub">
                                <?php $j=0;foreach ($popular_tutorial as $index=>$_post): $j++;?>
                                    <?php if($j>1):?>
                                    <?php get_tutorial_list_slider_sub($_post);?>
                                <?php endif; endforeach;?>
                            </ul>
                        </li>
                    <?php endfor;?>
                    </ul>
                    <div class="tutorial-pagination textcenter"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif;?>
        <?php endif;?>
        <!--list -->
        <?php
        $posts = get_tutorial_posts($tutorial_cat_id);
        if($posts['post']){
            $count = $posts['count'];
        ?>
        <div class="tutorial-list-box col-full">
            <div class="tutorial-content-list video-list-cont" id="tutorials">
                <div class="list-toolbar clearbox">
                    <?php if(isset($_GET['q']) && $_GET['q']):?>
                        <div class="search-for">
                            <h3><?php echo __(sprintf("Search results for '%s'",$_GET['q']),'storefront')?></h3>
                        </div>
                    <?php endif;?>
                    <div class="all-total fl">
                        <span><span class="below-pad-hide"><?php echo __('All Tutorials: ','storefront').'</span><span class="pblue pbold">'.$count .'</span>'.' '.__('tutorials posted','storefront')?></span>
                    </div>
                    <div class="fr filter-tool">
                        <select class="" onchange="window.location.href=this.value">
                            <option <?php if($_GET['orderby'] == 'date'): ?> selected="selected" <?php endif;?> value="<?php echo reset_page_url_param('orderby','date').'#tutorials'?>"><?php _e('Date','storefront')?></option>
                            <option <?php if($_GET['orderby'] == 'views'): ?> selected="selected" <?php endif;?>value="<?php echo reset_page_url_param('orderby','views').'#tutorials'?>"><?php _e('Views','storefront')?></option>
                            <option <?php if($_GET['orderby'] == 'title'): ?> selected="selected" <?php endif;?>value="<?php echo reset_page_url_param('orderby','title').'#tutorials'?>"><?php _e('Name','storefront')?></option>
                        </select>
                    </div>
                    <div class="fr filter-tool-tit"><?php _e('Sort By: ','storefront')?></div>
                </div>
                <div class="video-list clearbox">
                <ul class="cols3-ul-items">
                <?php $c=0; foreach ($posts['post'] as $_post):$c++;
                    //$liked = get_post_meta($_post->ID,'_liked',true) ? get_post_meta($_post->ID,'_liked',true) : 0;
                    //$views = get_post_meta($_post->ID,'views',true) ? get_post_meta($_post->ID,'views',true) : 0;
                    ?>
                    <?php  get_tutorial_list($_post);?>
                <?php endforeach;?>
                </ul>
                </div>
                <nav class="tutorial-navi ajax-more" id="tutorial-navi">
                    <?php echo $posts['page']?>
                </nav>
                <div class="dobot-loading-more textcenter" style="display: none">
                    <img src="<?php echo get_bloginfo('template_directory').'/assets/images/dobot/loading.gif' ?>"/>
                </div>
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