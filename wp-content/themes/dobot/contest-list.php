<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/3/29
 * Time: 14:09
 */
?>
<!--header-->
<div class="customer-video-header responsive-img" style="background-image:url(<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/dobot/contest_head_bg.jpg);">
    <div class="col-full">
        <div class="page-banner-header textcenter">
            <h1 class="pfff"><?php _e('DOBOT CONTEST','storefront')?></h1>
            <div class='page-banner-header-des contest-list-head-des pfff'><?php _e('Show off your wonderful skills and wow all of us with DOBOT.','storefront')?></div>
            <div class="contest-join-btn">
                <a href="javascript:void (0)"><button class="button emptybutton pblue" type="button"><?php _e('Join Now','storefront')?></button></a>
            </div>
        </div>
    </div>
</div>
<!--cat toolbar-->
<div class="contest-status-box bgfff" id="join-contest">
    <?php $status = isset($_GET['status']) ? $_GET['status'] : '' ?>
    <div class="list-toolbar pro-tab-cont">
        <div class="col-full">
            <div class="tutorial-tabs-head">
                <div class="mobile-list-nav">
                    <span>
                    <?php if($status == '' || $status == 'all'){
                        _e('All','storefront');
                    }else{
                        if($status == 1){_e('Open','storefront');}
                        if($status == 2){_e('Being Judged','storefront');}
                        if($status == 0){_e('Closed','storefront');}
                    }
                    ?>
                    </span>
                </div>
                <ul class="status-item tabs wc-tabs">
                    <li class="<?php echo ($status == '' || $status == 'all') ? 'is-active' : 'no-active'?>">
                        <a href="?status=all#contests"><?php _e('All','storefront')?></a>
                    </li>
                    <li class="<?php echo ($status == 1) ? 'is-active' : 'no-active'?>">
                        <a href="?status=1#contests"><?php _e("Open",'storefront')?></a>
                    </li>
                    <li class="<?php echo ($status == 2) ? 'is-active' : 'no-active'?>">
                        <a href="?status=2#contests"><?php _e('Being Judged','storefront')?></a>
                    </li>
                    <li class="<?php echo ($status == 0 && $status != '' && $status != 'all' ) ? 'is-active' : 'no-active'?>">
                        <a href="?status=0#contests"><?php _e('Closed','storefront')?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- my contest -->
<div class="contest-list-box col-full">
<div class="contest-content-list video-list-cont clearbox">




<div class="contest-list-toolbar list-toolbar clearbox" id="contests">
    <?php if(is_user_logged_in()):?>
    <div class="user-contest-btn fl all-total">
        <a href="<?php echo wc_get_account_endpoint_url('contest')?>"><button type="button" class="button emptybutton"><?php _e('My Contest','storefront')?></button></a>
    </div>
    <?php endif;?>
    <!--sort by-->
    <div class="fr filter-tool">
        <select class="" onchange="window.location.href=this.value">
            <option <?php if($_GET['orderby'] == 'views'): ?> selected="selected" <?php endif;?>value="<?php echo reset_page_url_param('orderby','views').'#contests'?>"><?php _e('Views','storefront')?></option>
            <option <?php if($_GET['orderby'] == 'end_time'): ?> selected="selected" <?php endif;?>value="<?php echo reset_page_url_param('orderby','end_time').'#contests'?>"><?php _e('Soon End','storefront')?></option>
            <option <?php if($_GET['orderby'] == 'entries'): ?> selected="selected" <?php endif;?>value="<?php echo reset_page_url_param('orderby','entries').'#contests'?>"><?php _e('Entries','storefront')?></option>
        </select>
    </div>
    <div class="fr filter-tool-tit">Sort By: </div>
</div>
<div class="contest-list-container">
<?php $contests = get_contests();?>
<?php if(count($contests['contest'])):
    include_once WP_PLUGIN_DIR.'/categories-images/categories-images.php';
 ?>

    <ul class="contest-items">
        <?php $i=0;foreach ($contests['contest'] as $_contest):$i++;?>
            <?php
                $name = $_contest->name;
                $term_id = $_contest->term_id;
                $image_url =  z_taxonomy_image_url( $_contest->term_id, 'full', TRUE );
                $contest_tag =  get_term_meta($term_id,'contest_tag',true);
                $contest_views = get_term_meta($term_id,'views',true);
                $contest_date = get_term_meta($term_id,'create_date',true);
                $contest_entries = $_contest->count;

                $contest_date = get_term_meta($term_id,'create_date',true);
                $end_time = get_term_meta($term_id,'end_time',true);
                $start_time = get_term_meta($term_id,'start_time',true);
                $being_judged_time = get_term_meta($term_id,'being_judged',true);
                $now_time = current_time( 'timestamp' );

                if($now_time < strtotime($start_time)){
                    $cont_status = 3;
                    update_term_meta($term_id,'status',3);
                }else if($now_time > strtotime($end_time)){
                    $cont_status = 0;
                    update_term_meta($term_id,'status',0);
                } else if($now_time > strtotime($being_judged_time) && $now_time < strtotime($end_time)){
                    $cont_status = 2;
                    update_term_meta($term_id,'status',2);
                }else if($now_time > strtotime($start_time) && $now_time < strtotime($being_judged_time)){
                    $cont_status = 1;
                    update_term_meta($term_id,'status',1);
                }
                $seconds_remaining = empty( $start_time ) ? 0 : strtotime( $start_time ) - current_time( 'timestamp' );
                $seconds_remaining = $seconds_remaining < 0 ? 0 : $seconds_remaining;
                $pre_date = contest_seconds_to_time( $seconds_remaining );

                $contest_subtitle = get_term_meta($term_id,'subtitle',true);
                $seconds_remaining = empty( $being_judged_time ) ? 0 : strtotime( $being_judged_time ) - current_time( 'timestamp' );
                $seconds_remaining = $seconds_remaining < 0 ? 0 : $seconds_remaining;
                $left_date = contest_seconds_to_time( $seconds_remaining );

            ?>
            <li class="contest-item">
                <div class="contest-image">
                    <a href="<?php echo home_url('contest-list/'.$_contest->slug).'.html'?>" title="<?php  echo __($name,'storefront')?>" >
                        <img src="<?php echo esc_url($image_url)?>" title="<?php echo __($name,'storefront')?>" alt=""/>
                    </a>
                </div>
                <div class="contest-info">
                    <div class="name">
                        <h3>
                            <a href="<?php echo home_url('contest-list/'.$_contest->slug).'.html'?>" title="<?php echo __($name,'storefront')?>" >
                                <?php echo __($name,'storefront')?>
                            </a>
                        </h3>
                    </div>
                    <div class="description"><?php echo __($_contest->description,'storefront')?></div>
                    <div class="fuzhu-xinxi">
                        <div class="xiangguan fl">
                            <?php if($contest_tag):?><span class="tag pblue"><?php echo __($contest_tag,'storefront')?></span><?php endif;?>
                            <span class="date"><?php echo __(date("M, d, Y",strtotime($contest_date)),'storefront');?></span>
                            <span class="views"><label class="pblue"><?php echo __($contest_views,'storefront').'</label><label class="below-pad-hide">'.' '.__('Views','storefront')?></label></span>
                            <span class="entries file"><label class="pblue"><?php echo __($contest_entries,'storefront').'</label><label class="below-pad-hide">'.' '.__('Entries','storefront')?></label></span>
                        </div>
                        <div class="below-mobile-show description"><?php echo __($_contest->description,'storefront')?></div>
                        <div class="status-box fr">
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
            </li>
        <?php endforeach;?>
    </ul>
    <div class="paged bckfff contest-entries-page">
        <?php echo $contests['page']?>
    </div>

<?php else:?>
    <div class="no-data">
        <p><?php  _e('No find contest','storefront')?></p>
    </div>
<?php endif;?>
</div>
</div>
</div>


<!--you want to join-->
<div class="want-to-join">
    <div class="col-full">
        <div class="want-desc textcenter">
            <div class="p36 pfff marginbottom15"><?php echo  __('What kind of competition you want to join?','storefront')?></div>
            <div class="info textcenter pfff"><?php _e('Tell us more about the contest you want to join in.',
                        'storefront'
                    )?>
            </div>
        </div>
    </div>
    <div class="form-box">
        <form method="post" id="like_kind_form">
            <p class="success-msg" style="display: none"></p>
            <input type="hidden" name="join_kind" value="1"/>
            <input type="hidden" name="action" value="collect_user_like_contest_kind"/>
            <fieldset>
                <input class="contest-form-input" type="text" name="name" placeholder="<?php _e('name','storefront')?>" id="name"/>
                <input class="contest-form-input" type="text" name="email" placeholder="<?php _e('email','storefront')?>" id="email"/>
            </fieldset>
            <fieldest>
                <textarea class="contest-form-textarea" name="text" id="text" cols="30" rows="10" placeholder="<?php _e('text','storefront')?>"></textarea>
            </fieldest>
            <span class="loading-img" id="loading-img" style="display: none"><img src="<?php echo get_stylesheet_directory_uri().'/assets/images/dobot/loading.gif'?>" alt=""></span>
            <div class="form-btn"><button type="button"><?php _e('Submit','storefront')?></button></div>
        </form>
        <script type="text/javascript">
            jQuery('.form-btn button').click(function () {
                var email = jQuery('#email').val();
                var $error = 0;
                var url = '<?php echo admin_url('admin-ajax.php')?>';
                var pat = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/;
                if(!pat.test(email)){
                    $error++;
                    jQuery('#email').addClass('valid-error');
                }else{
                    jQuery('#email').removeClass('valid-error');
                }
                if(jQuery('#name').val() == '' || jQuery('#name').val() == null){
                    jQuery('#name').addClass('valid-error');
                    $error++;
                }else{
                    jQuery('#name').removeClass('valid-error');
                }
                if(jQuery('#text').val() == '' || jQuery('#text').val() == null){
                    jQuery('#text').addClass('valid-error');
                    $error++;
                }else {
                    jQuery('#text').removeClass('valid-error');
                }
                if(!$error){
                    jQuery('#loading-img').show();
                    jQuery.ajax({cache: true, type: "POST", url: url, data: jQuery('#like_kind_form').serialize(),
                        async: false,
                        success: function(data) {
                            var json = JSON.parse(data);
                            if(json.msg){
                                jQuery('#loading-img').hide();
                                jQuery('.success-msg').html(json.msg);
                                jQuery('.success-msg').show().delay(3000).hide(0);
                                jQuery('#like_kind_form')[0].reset();
                            }
                        }
                    });
                }
            });
            </script>

    </div>
</div>