<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/19
 * Time: 16:02
 */
$seo_cat = get_seo_cat();
$cay_id = $GLOBALS['seo_id'] ? $GLOBALS['seo_id'] : 0;
$popular_cat = get_term_by('slug','popular','seo-category');
$seo_posts = get_seo_cat_below_posts($cay_id,12);
?>
<!--seo post navigation-->

<div class="full-screen pro-tab-cont resource-navigation">
    <div class="col-full">
        <div class="pro-title"><?php echo __('Resource','storefront')?></div>
        <div class="nav-items">
            <?php if($seo_cat):?>
            <ul class="tabs wc-tabs">
                <li class="nav-item <?php if(!$cay_id):?>active <?php else:?>inactive<?php endif;;?>">
                    <a href="<?php echo get_page_url('seo');?>#seo">All</a>
                </li>
                <?php $front_cat =  array_slice($seo_cat,0,3,true);
                foreach ($front_cat as $_cat):?>
                <li class="nav-item <?php if($cay_id == $_cat->term_id):?>active<?php else:?>inactive<?php endif;?>">
                    <a href="<?php echo esc_url(get_term_link($_cat->term_id,'seo-category'))?>#seo">
                        <?php echo $_cat->name; ?>
                    </a>
                </li>
                <?php endforeach;?>
                <?php if(count($seo_cat) > 3):?>
                    <li class="nav-item more-cat"><button type="button" class="button"><?php echo __('MORE')?></button></li>
                <?php endif;?>
            </ul>
            <?php endif;?>
        </div>
    </div>
    <?php if(count($seo_cat) > 3):?>
    <div class="more-cat-div" id="more-cat-div" style="display: none">
        <div class="col-full">
        <?php $more_cat = array_slice($seo_cat,3,null,true);?>
            <ul class="more-items clearbox">
                <?php foreach ($more_cat as $_cat):?>
                <li class="nav-item <?php if($cay_id == $_cat->term_id):?>active<?php else:?>inactive<?php endif;?>">
                    <a  href="<?php echo esc_url(get_term_link($_cat->term_id,'seo-category'))?>#seo">
                        <?php echo $_cat->name; ?>
                    </a>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
    <?php endif;?>
</div>

<!--popular seo posts-->

<?php if(!$cay_id):?>
<?php $popular_cat_below_posts = get_seo_cat_below_posts($popular_cat->term_id,12,true);?>
<?php if( count($popular_cat_below_posts['posts']) >= 4):?>
<div class="popular-seo clearbox">
    <?php $for = floor((count($popular_cat_below_posts['posts']))/4);?>
    <div class="popular-title clearbox p36"><?php _e('Most popular','storefront')?></div>
    <?php for($i=0;$i<$for;$i++):?>
        <?php $popular_seos = array_slice($popular_cat_below_posts['posts'],$i*4,4,true); ?>
        <div class="popular-seo-left">
            <?php
            $_seo = $popular_seos[0];
            $title = $_seo->post_title;
            $short_desc = mb_strimwidth(strip_tags(apply_filters('the_content', $_seo->post_content)), 0, 100,"……");
            $seo_image_url = get_post_thumbnail_url($_seo->ID,'full');
            ?>
            <a class="line0" style="background-image: url('<?php //echo esc_url($seo_image_url)?>')" href="<?php echo esc_url(get_permalink($_seo->ID))?>" title="<?php echo $title?>">
                <img src="<?php echo esc_url($seo_image_url)?>"></a>
            </a>
            <div class="seo-title">
                <a href="<?php echo esc_url(get_permalink($_seo->ID))?>" title="<?php echo $title?>"> <?php echo $title;?></a>
            </div>
        </div>
        <div class="popular-seo-right">
            <ul class="seo-right-items">
                <?php
                foreach (array_slice($popular_seos,1,3,true) as $_seo):
                    $title = $_seo->post_title;
                    $short_desc = mb_strimwidth(strip_tags(apply_filters('the_content', $_seo->post_content)), 0, 100,"……");
                    $seo_image_url = get_post_thumbnail_url($_seo->ID);
                    ?>
                    <li class="seo-right-item clearbox">
                        <a class="seo-right-item-img" style="background-image: url('<?php //echo esc_url($seo_image_url)?>')" href="<?php echo esc_url(get_permalink($_seo->ID))?>" title="<?php echo $title?>">
                            <img src="<?php echo esc_url($seo_image_url)?>">
                        </a>
                        <div class="seo-short-info">
                            <div class="seo-title">
                                <a href="<?php echo esc_url(get_permalink($_seo->ID))?>" title="<?php echo $title?>"> <?php echo $title;?></a>
                            </div>
                            <div class="des p666"><?php echo $short_desc?></div>
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
        </div>
    <?php endfor;?>
</div>
<?php endif;?>
<?php endif;?>

<!--seo posts list-->
<div class="seo-posts-list">
    <div class="container">
        <?php if(count($seo_posts['posts'])):?>
        <?php $i=0;foreach ($seo_posts['posts'] as $key=> $seo):$i++;
            $short_desc = mb_strimwidth(strip_tags(apply_filters('the_content', $seo->post_content)), 0, 120,"……");
            $create_date = date('m.d.Y',strtotime($seo->post_date));
            $author_name =  ucwords(get_user_by('id',$seo->post_author)->user_login);
            $seo_cat_url = get_term_link(get_seo_cat_by_postid($seo->ID)->term_id,'seo-category');
            $seo_cat_name = get_seo_cat_by_postid($seo->ID)->name;
            if(($i-1) == array_search(end($seo_posts['posts']),$seo_posts['posts'])){
                $last_class = 'page-last-item';
            }else{
                $last_class = '';
            }
        ?>
        <div class="list clear <?php echo $last_class?>">
            <div class="category">
                <span class="dot"></span>
                <a href="<?php echo esc_url($seo_cat_url)?>"><?php echo $seo_cat_name?></a>
            </div>
            <div class="info">
                <div class="title"><a href="<?php echo esc_url(get_permalink($seo->ID))?>"><?php echo $seo->post_title?></a></div>
                <div class="des p666"><?php echo $short_desc?></div>
                <div class="author p666">
                    <span><?php echo __('Posted by&nbsp;','storefront')?></span><span class="pblue"><?php echo $author_name?></span><span class="time"><?php echo $create_date?></span>
                </div>
            </div>
        </div>
        <?php endforeach;?>
        <div class="paged contest-entries-page">
            <?php echo $seo_posts['page'];?>
        </div>
        <?php else:?>
        <div class="no-data">
             <p><?php  _e('No find any resource posts.','storefront')?></p>
        </div>
        <?php endif;?>
    </div>
</div>
<script>
    (function ($) {
        $('.resource-navigation .nav-items .more-cat button').click(function () {
            $('.resource-navigation .more-cat-div').toggle();
        });
        $('.resource-navigation .more-cat-div li').each(function () {
            if($(this).hasClass('active')){
                $(this).parents('.more-cat-div').show().delay(2000).hide(0);
                $(this).parents('.more-cat-div').prev('ul').find('li').removeClass('active').end().addClass('inactive');
            }
        })
    })(jQuery);
</script>