<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/17
 * Time: 11:02
 */

$faq_slug = $GLOBALS['faq_slug'];
$faq_parent_terms = get_faq_cat();
$default_slug = 'top-questions';
if($faq_slug == '' && $faq_slug == null){
    $is_default = true;
    $faq_term = get_term_by('slug',$default_slug,'ufaq-category');
    $faq_term_id = $faq_term->term_id;
}else{
    $is_default = false;
    $faq_term = get_term_by('slug',$faq_slug,'ufaq-category');
    $faq_term_id = $faq_term->term_id;
}
$current_faq = $faq_term_id;
?>
<!--header-->
<div class="faq-center-header" style="">
    <div class="col-full">
        <div class="page-banner-header textcenter">
            <h1 class="pfff"><?php _e('FAQ','storefront')?></h1>
            <div class='page-banner-header-des pfff'><?php _e('Find answers to frequently asked questions, including product updates and how-to guides.','storefront')?></div>
        </div>
    </div>
</div>

<!--crumb -->
<div class="crumb bgfff faqcurmb" id="crumbs">
    <div class="col-full">
        <ul>
            <li><a href="<?php echo home_url()?>"><?php _e('Home','storefront')?></a></li>
            <li><a href="<?php echo home_url('support-center').'.html'?>"><?php _e('Support','storefront')?></a></li>
            <?php //if($is_default):?>
                <li><?php echo __('FAQ','storefront')?></li>
            <?php //else:?>
                <li><?php //echo __($faq_term->name).' '.__('FAQS','storefront')?></li>
            <?php //endif;?>
        </ul>
        <div class="back fr">
            <a href="<?php echo home_url('support-center').'.html'?>"><button type="button" class="emptybutton backbtn pblue button smallbutton"><?php _e('Back','storefront')?></button></a>
        </div>
    </div>
</div>

<!--content area-->
<div class="col-full" id="faq">
<div class="faq-main-content clearbox">
    <div class="faq-navigation fl <?php if(isset($_GET['faq_cat'])){echo 'hascat';}?>">
        <div class="nav-title"><h3 class="p40 pfff"><?php echo __('FAQ','storefront')?></h3></div>
        <div class="nav-container">
            <ul class="nav-items parent">
                <?php $i=0;foreach ($faq_parent_terms as $term):$i++;?>
                    <?php
                        /*$has_include_faq_cat Is a subclass of the parent in the loop */
                        $has_include_faq_cat = isset($_GET['faq_cat']) ? include_faq_cat($term->term_id,$_GET['faq_cat']) : false;
                        $show = '';
                        if(($term->term_id == $faq_term_id ) && !(isset($_GET['faq_cat']))):
                            $show = 'hideactive';
                        elseif (isset($_GET['faq_cat']) && $_GET['faq_cat'] && $has_include_faq_cat):
                            $show = 'hideactive';
                        elseif(isset($_GET['faq_cat']) && $_GET['faq_cat'] == $term->term_id):
                            $show = 'hideactive';
                        endif;
                        $children = get_faq_cat($term->term_id);
                    ?>
                     <li data-id="<?php echo $term->term_id;?>" class="nav-item <?php echo $show;?> <?php if($children): ?>hide-section<?php endif;?>">
                         <?php if($i > 1):?>
                             <?php $href = home_url('faq/'.$term->slug.'.html')?>
                         <?php else:?>
                             <?php $href = home_url('faq').'.html'?>
                         <?php endif;?>
                         <a class="<?php if($children): ?>hide-section-title<?php endif;?>" href="<?php echo esc_url($href)?>"><?php echo __($term->name,'storefront')?></a>
                         <?php if(count($children)): ?>
                            <div class="level-box hide-section-content" <?php if(($show == '' || $show == null) && !$has_include_faq_cat):?>style="display: none"<?php endif;?> id="box-<?php echo $term->term_id ?>">
                            <ul>
                                <?php $j=0;foreach ($children as $_child):$j++;?>
                                    <?php
                                    $active = '';
                                    if($j==1 && !isset($_GET['faq_cat']) && $current_faq == $term->term_id ):
                                        $faq_cat = $_child->term_id;
                                        $active = 'active';
                                    elseif(isset($_GET['faq_cat']) && ($_child->term_id == $_GET['faq_cat'])):
                                        $active = 'active';
                                        $faq_cat = $_GET['faq_cat'];
                                    endif;
                                    ?>
                                    <li class="term-children <?php echo $active;?>">
                                        <?php if($i > 1):?>
                                            <a href="<?php echo home_url('faq/'.$term->slug).'.html?faq_cat='. $_child->term_id?>"><?php echo __($_child->name,'storefront')?></a>
                                        <?php else:?>
                                            <a href="<?php echo home_url('faq').'.html?faq_cat='. $_child->term_id?>"><?php echo __($_child->name,'storefront')?></a>
                                        <?php endif;?>
                                    </li>
                                <?php endforeach;?>
                            </ul>
                         </div>
                         <?php endif;?>
                     </li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
    <div class="faq-content fr <?php if(isset($_GET['faq_cat'])){echo 'hascat';}?>">
        <a class="backfaqnav pblue" href="/faq"><?php echo __('Returns the directory','storefront');?></a>
        <?php $faq_cat = $faq_cat ? $faq_cat : $current_faq;?>
        <?php $faq_posts = get_faq_posts($faq_cat);?>
        <?php if($faq_posts['posts']):?>
            <?php $faq = get_term_by('id',$faq_cat,'ufaq-category')?>
            <div class="faq-list">
                <ul class="list-items">
                    <?php foreach ($faq_posts['posts'] as $_post):?>
                    <li class="list-item hide-section">
                        <div class="faq-item-cat"><?php echo __($faq->name,'storefront')?></div>
                        <div class="faq-item-title hide-section-title clearbox" id="<?php echo $_post->ID?>">
                            <div class="title fl" style="cursor: pointer"> <?php echo __($_post->post_title,'storefront');?></div>
                            <span class="target-icon fr" style="cursor: pointer" ></span>
                        </div>
                        <div class="faq-item-content hide-section-content" id="faq-<?php echo $_post->ID?>" style="display: none">
                            <?php echo __($_post->post_content,'storefront');?>
                        </div>
                    </li>
                    <?php endforeach;?>
                </ul>
            </div>
            <div class="paged bckfff contest-entries-page">
                <?php echo $faq_posts['page']?>
            </div>
        <?php else:?>
            <div class="no-data">
                <p><?php echo __('Could not find any FAQS.','storefront');?></p>
            </div>
        <?php endif;?>
    </div>
</div>
</div>
<script>
    (function ($) {
        var hash = location.hash.substring(1);
        if(hash){
            $('#'+hash).parent().addClass('hideactive');
            $('#faq-'+hash).show();
        }
    })(jQuery)
</script>