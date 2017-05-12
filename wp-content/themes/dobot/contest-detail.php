<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/11
 * Time: 11:03
 */
$contest_slug = get_query_var('contest_id');

if(substr_count($contest_slug,'.html')){
   $contest_slug = str_replace('.html','',$contest_slug);
}
$contest = get_term_by('slug',$contest_slug,'contest-category');
$contest_id = $contest->term_id ? $contest->term_id : $_GET['contest_id'] ;

$contest = get_term_by('id',$contest_id,'contest-category');

$name = $contest->name;
$term_id = $contest->term_id;
$link = get_term_link($contest,'contest');
$image_url =  z_taxonomy_image_url( $contest->term_id, 'thumbnail', TRUE );
$contest_tag =  get_term_meta($term_id,'contest_tag',true);
$contest_views = get_term_meta($term_id,'views',true);
update_term_meta($contest_id,'views',$contest_views+1);
$contest_date = get_term_meta($term_id,'create_date',true);
$contest_entries = $contest->count;
$cont_status = get_term_meta($term_id,'status',true);
$contest_date = get_term_meta($term_id,'create_date',true);
$contest_subtitle = get_term_meta($term_id,'subtitle',true);
$end_time = get_term_meta($term_id,'end_time',true);
$end_time = get_term_meta($term_id,'end_time',true);
$start_time = get_term_meta($term_id,'start_time',true);
$being_judged_time = get_term_meta($term_id,'being_judged',true);
$seconds_remaining = empty( $end_time ) ? 0 : strtotime( $end_time ) - current_time( 'timestamp' );
$seconds_remaining = $seconds_remaining < 0 ? 0 : $seconds_remaining;
$left_date = contest_seconds_to_time( $seconds_remaining );
$banner_imag  =  get_term_meta($term_id,'banner',true);
$banner_text  =  get_term_meta($term_id,'banner_text',true);
global $current_user;
$customer_has_join = get_user_meta($current_user->ID,'_contest',true) == $contest_id ? true : false;
?>
<!--header-->
<div class="customer-video-header responsive-img" style="background-image:url(<?php echo esc_url($banner_imag ); ?>);">
    <div class="col-full">
        <div class="page-banner-header contest-detail-banner-header textcenter">
            <div class="pfff text-uppercase p40 contest-detail-banner-header-subtitle"><?php echo __($contest_subtitle,'storefront')?></div>
            <h1 class="pfff text-uppercase contest-detail-banner-header-title"><?php echo __($contest->name,'storefront')?></h1>
            <div class='page-banner-header-des pfff'><?php _e($banner_text,'storefront')?></div>
            <?php if($cont_status == 1):?>
            <div class="contest-join-btn">
                <a href="<?php echo reset_page_url_param('cid',$contest_id,wc_get_account_endpoint_url('add-contest'))?>"><button class="button pblue emptybutton" type="button"><?php _e('Join Now','storefront')?></button></a>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>
<!--nav -->
<div class="contest-detail-nav">
    <div class="contest-detail-toolbar line0">
        <div class="col-full">
            <div class="mobile-list-nav">
                <span>
                <?php
                    _e('Detail','storefront');
                ?>
                </span>
            </div>
            <ul class="status-item tabs">
                <li class="active" ><a href="#details" ><?php echo __('Detail','storefront')?></a></li>
                <?php if($cont_status !=3 && $cont_status != ''):?>
                <li class="active-next"><a href="#entries" ><?php echo __('Entries','storefront')?></a></li>
                <?php endif;?>
                <?php if($cont_status == 0):?>
                    <li><a href="#winners"><?php echo __('Winners','storefront')?></a></li>
                <?php endif;?>
                <?php if($customer_has_join):?>
                    <li><a href="<?php echo wc_get_account_endpoint_url('contest')?>"><?php echo __('My Work','storefront')?></a></li>
                <?php endif;?>
            </ul>
        </div>
    </div>
</div>

<!--contest detail-->
<div class="contest-detail-box" id="detail">
    <div class="instruction bgfff">
        <div class="info col-full">
            <div class="info-1 fl">
                <div class="info-1-up">
                    <div class="clearbox">
                        <div class="contest-title-left fl">
                            <h2 class="title p25"><?php echo __("Contest: ",'storefront')?><?php echo __($name,'storefront')?></h2>
                            <span class="tag pblue text-uppercase"><?php echo __($contest_tag,'storefront')?></span>
                            <span class="date"><?php echo date('M, d, Y',strtotime($contest_date))?></span>
                            <span class="below-pad-show entries file"><?php echo $contest_entries.' '.__('ENTRIES','storefront')?></span>
                            <span class="below-pad-show views"><?php echo $contest_views.' '.__('VIEWS','storefront')?></span>
                            <span class="share">
                                <a href="<?php echo share_to_social($contest,'facebook','contest')?>" target="_blank"><span class="share-facebook"></span></a>
                                <a href="<?php echo share_to_social($contest,'twitter','contest')?>" target="_blank"><span class="share-twitter"></span></a>
                                <a href="<?php echo share_to_social($contest,'google-plus','contest')?>" target="_blank"><span class="share-google"></span></a>
                                <a href="<?php echo share_to_social($contest,'pinterest','contest')?>" target="_blank"><span class="share-pinterest"></span></a>
                            </span>
                        </div>
                        <div class="contest-title-right fr">
                            <div class="entries file"><?php echo $contest_entries.' '.__('ENTRIES','storefront')?></div>
                            <div class="views"><?php echo $contest_views.' '.__('VIEWS','storefront')?></div>
                        </div>
                    </div>
                </div>
                <div class="info-1-down">
                    <?php echo get_term_meta($contest_id,'introduction',true)?>
                </div>
                
            </div>
            <div class="info-2 fr">
                <div class="status-box below-pad-hide">
                    <?php if($cont_status == 1):?>
                        <div class="status-box-ongoing"><?php echo __('Ongoing','storefront')?></div>
                    <?php elseif($cont_status == 2):?>
                        <div class="status-box-judged"><?php echo __('Being Judged','storefront')?></div>
                    <?php elseif($cont_status == 0):?>
                        <div class="status-box-closed"><?php echo __('Closed','storefront')?></div>
                    <?php else:?>
                        <div class="status-box-ongoing"><?php echo __('Open Soon','storefront')?></div>
                    <?php endif;?>
                </div>
                <div class="status-box below-pad-show">
                    <?php if($cont_status == 1):?>
                        <span class="status open"><?php _e('Ongoing','storefront')?></span>
                        <?php if($left_date):?>
                            <span class="left-date"><?php _e('Left:','storefront')?><?php echo $left_date?></span>
                        <?php endif;?>
                    <?php elseif ($cont_status == 2):?>
                        <span class="status being-judged"><?php _e('Being Judged','storefront')?></span>
                    <?php elseif ($cont_status == 0 ):?>
                        <span class="status closed"><?php _e('Contest Closed','storefront')?></span>
                    <?php elseif ($cont_status == 3):?>
                        <span class="status prev"><?php _e('Open Soon','storefront')?></span>
                        <?php if($pre_date):?>
                            <span class="left-date"><?php echo $pre_date?></span>
                        <?php endif;?>
                    <?php endif;?>
                </div>
            </div>
        </div>
        
    </div>
    <div class="time-cont-screen <?php if($cont_status == 0){echo 'bgfff';}?> below-pad-hide">
        <div class="time-cont col-full">
            <?php
                $timestatus='';
                if($cont_status==1){
                    $timestatus='time-ongoing';
                }
                if($cont_status==2){
                    $timestatus='time-judged';
                }
                if($cont_status==0){
                    $timestatus='time-closed';
                }

            ?>
            <ul class="time-cont-items">
                <li class="time-cont-item begintime <?php echo $timestatus;?>">
                    <span class="begintime-line"></span>
                    <span class="up open-up"><?php _e('Open','storefront')?></span>
                    <span class="down open-down"><?php echo date('M, d, Y',strtotime($contest_date))?></span>
                    <span class="up ongoing-up"><?php _e('Ongoing','storefront')?></span>
                    <span class="down ongoing-down"><?php echo date('M, d, Y',strtotime($start_time))?></span>
                </li>
                <li class="time-cont-item middletime <?php echo $timestatus;?>">
                    <span class="up judged-up"><?php _e('Being Judged','storefront')?></span>
                    <span class="down judged-down"><?php echo date('M, d, Y',strtotime($being_judged_time))?></span>
                </li>
                <li class="time-cont-item endtime <?php echo $timestatus;?>">
                    <span class="up closed-up"><?php _e('Closed','storefront')?></span>
                    <span class="down closed-down"><?php echo date('M, d, Y',strtotime($end_time))?></span>
                </li>
            </ul>
        </div>
    </div>
    <?php if($cont_status == 1):?>
    <div class="col-full">
        <div class="how-to-join bgfff">
            <div class="join fr">
                <a href="<?php echo reset_page_url_param('cid',$contest_id,wc_get_account_endpoint_url('add-contest'))?>"><button type="button"><?php echo __('JOIN NOW!','storefront')?></button></a>
            </div>
            <div class="how-to">
                <a class="pblue" target="_blank" href="<?php echo esc_url( home_url( 'create-contest-article-guide' ).'.html' );?>"><?php _e('How to Write a Great Contest Article »','storefront')?></a>
            </div>
        </div>
    </div>
    <?php endif;?>
<?php if($cont_status == 0):?>
<div class="bgfff">
    <div class="col-full" id="winners">
        <div class="contest-winner-box">
        <div class="p30 textcenter"><strong><?php echo __('Winners','storefront')?></strong></div>
        <?php $prize_setting = get_term_meta($contest_id,'prize',true);?>
        <?php if($prize_setting):?>
            <?php echo $prize_setting;?>
        <?php else:?>
            <?php $winners = get_contest_winners($contest_id,3)?>
            <?php if(count($winners)):?>
                <?php get_winners_items($winners)?>
            <?Php else:?>
                <div class="no-data">
                    <p><?php echo __('Winners are selecting, please keep an eye on.','storefront')?></p>
                </div>
            <?php endif;?>
        <?php endif;?>
        </div>
    </div>
</div>
<?php endif;?>

<!--contest entries-->
<?php if($cont_status !=3 && $cont_status != ''):?>
<div class="col-full" id="entries">
<div class="contest-entries-box" >
    <?php if($contest_entries):?>
    <div class="title">
        <h3 class="p30 textcenter"><?php echo $contest_entries.' '.__('Entries','storefront')?></h3>
    </div>
    <div class="contest-entries-list">
        <?php $entries = get_contests_entries($contest_id);?>
        <ul class="entries-items">
            <?php foreach ($entries['post'] as $_entries):?>
                <?php get_entries_list_item($_entries);?>
            <?php endforeach;?>
        </ul>
        <div class="paged bckfff contest-entries-page">
            <?php echo $entries['page']?>
        </div>
        <script type="text/javascript">
            jQuery('.entries-item .vote-this .no-vot').each(function () {
                var $this = jQuery(this);
                $this.change(function () {
                    var id = $this.attr('id');
                    jQuery('#loading-img-'+id).show();
                    var url = '<?php echo admin_url('admin-ajax.php')?>';
                    jQuery.post(url,{post_id:id,action:'vot'},function (response) {
                        var json = JSON.parse(response);
                        if(json.status == 200){
                            jQuery('#loading-img-'+id).hide();
                            jQuery('#span-'+id).remove();
                            jQuery('#vot-'+id).append(json.html);
                            jQuery('#vot-'+id).addClass('vote-just');
                        }
                    });
                });
            });
        </script>
    </div>
    <?php else:?>
    <div class="no-data">
        <p><?php echo __('The contest has no entries.','storefront')?></p>
    </div>
    <?php endif;?>
</div>
</div>
<?php endif;?>