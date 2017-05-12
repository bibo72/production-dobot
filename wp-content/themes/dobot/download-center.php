<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/14
 * Time: 15:11
 */
$download_cat_slug = $GLOBALS['download_slug'];
$download_cat = get_term_by('slug',$download_cat_slug,'dlm_download_category');
$download_cat_id = $download_cat->term_id;
$_download_parent_cats = get_download_cat();

$current_download_cat = $download_cat_id ? $download_cat_id : $_download_parent_cats[0]->term_id;
$cat_name = get_downlaod_cat_name_by_id($current_download_cat);
$protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
$current_url =$protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
?>
<!--header-->
<div class="download-center-header" style="background-color: black">
    <div class="col-full">
        <div class="page-banner-header textcenter">
            <h1 class="pfff"><?php _e('DOWNLOAD CENTER','storefront')?></h1>
            <div class='page-banner-header-des pfff'><?php _e('In order to run Dobot, please download the softwares and files according to your Dobot Arm’s Model..','storefront')?></div>
        </div>
    </div>
</div>

<!--nav -->
<div class="contest-detail-nav download-center-nav">
    <div class="contest-detail-toolbar line0">
        <div class="col-full max-col-full">
            <div class="mobile-list-nav">
                <span>
                    <?php foreach ($_download_parent_cats as $_cat):?>
                    <?php if($current_download_cat == $_cat->term_id):?>
                        <?php echo  __($_cat->name,'storefront');?>
                    <?php endif;?>
                    <?php endforeach;?>
                </span>
            </div>
            <ul class="status-item three-status-item tabs">
                <?php foreach ($_download_parent_cats as $_cat):?>
                <li <?php if($current_download_cat == $_cat->term_id):?>class="active" <?php endif;?>>
                    <a href="<?php echo home_url('download-center/'.$_cat->slug).'.html'?>#most-download"><?php echo  __($_cat->name,'storefront')?></a>
                </li>
                <?php endforeach;?>
            </ul>
        </div>
    </div>
</div>

<div class="crumb bgfff" id="crumbs">
    <div class="col-full">
        <ul>
            <li><a href="<?php echo home_url()?>"><?php _e('Home','storefront')?></a></li>
            <li><a href="<?php echo home_url('support-center').'.html'?>"><?php _e('Support','storefront')?></a></li>
            <li><?php echo __($cat_name).' '.__('Download Centers','storefront')?></li>
        </ul>
    </div>
</div>

<!--Most Frequent Download-->
<?php if($most_downloads = get_most_download_file($current_download_cat,1)):?>
<div class="bgfff" id="most-download">
    <div class="col-full">
        <div class="download-center frequent-download bgfff">
            <div class="p40 pbold below-pad-hide"><?php _e('Most Frequent Download','storefront')?></div>
            <div class="below-pad-hide">Access multiple resources about DOBOT Magician. If you have other questions, please <a class="pblue textunderline weight100" href="<?php echo esc_url( home_url( 'contact-us' ) ).'.html';?>">contact us.</a></div>
            <div class="most-frequent-download">
                <?php foreach ($most_downloads as $_file):
                    $download = new DLM_Download( $_file->ID );
                    $file     = $download->get_file_version();
                    //$image     = $download->get_the_image('full');
                    $link  = $download->get_the_download_link() ;
                    $third = get_post_meta($_file->ID,'dlm_third',true);
                    if($third != '' && $third != null ){
                        $link = $third;
                    }
                    if($file->filename != '' && $file->filename != null){
                        $file_name = $file->filename;
                    }else{
                        $file_name = $_file->post_title;
                    }
                    $file_size = $download->get_the_filesize();
                    $file_date = date('Y. m. d',strtotime($_file->post_date_gmt));
                    $file_description = $_file->post_excerpt;
                    $_members_only = get_post_meta($_file->ID,'_members_only',true);
                    $refer = reset_page_url_param('refer',urlencode($current_url),wc_get_page_permalink('myaccount'));
                    ?>
                    <div class="file-item clearbox">
                        <img class="fl" src="<?php echo get_bloginfo('template_directory').'/assets/images/dobot/bigfile.png' ?>">
                        <div class="down-file-info">
                            <div class="p24 down-file-info-title">Most Frequent Download</div>
                            <div class="file-info clearbox">
                                <div class="fl">
                                    <div class="file-name p18 p000"><?php echo $file_name?></div>
                                    <div class="file-date"><?php echo $_file->post_title .'&nbsp;&nbsp;&nbsp;&nbsp;'.$file_date?></div>
                                </div>
                                <div class="fr file-size-download below-pad-hide">
                                    <span class="file-size"><?php echo $file_size;?></span>
                                    <?php if($_members_only == 'yes' && is_user_logged_in()):?>
                                        <a target="_blank" href="<?php echo esc_url($link)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                        </a>
                                    <?php elseif($_members_only == 'no'):?>
                                        <a target="_blank" href="<?php echo esc_url($link)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                        </a>
                                    <?php elseif($_members_only == 'yes' && !is_user_logged_in()):?>
                                        <a target="_blank" href="<?php echo esc_url($refer)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                        </a>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="file-descirpiton"><?php echo $file_description?></div>
                            <div class="below-pad-show file-size-download">
                                <span class="file-size"><?php echo $file_size;?></span>
                                <?php if($_members_only == 'yes' && is_user_logged_in()):?>
                                    <a target="_blank"  href="<?php echo esc_url($link)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                    </a>
                                <?php elseif($_members_only == 'no'):?>
                                    <a  target="_blank"  href="<?php echo esc_url($link)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                    </a>
                                <?php elseif($_members_only == 'yes' && !is_user_logged_in()):?>
                                    <a target="_blank"  href="<?php echo esc_url($refer)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                    </a>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>
<?php endif;?>

<div class="col-full" id="sub-download">
    <div class="download-center-category">
        <?php $sub_cats = get_download_cat($current_download_cat);?>
        <?php  $sub_cat = isset($_GET['sub_cat']) ? $_GET['sub_cat'] : 0;?>
        <?php if(is_array($sub_cats)):?>
        <div class="clearbox">
            <div class="download-center-items-nav-cont">
                <div class="download-center-items-nav" >
                    <ul class="download-center-items clearbox" style="position:relative;">
                        
                        <?php $i=0;foreach ($sub_cats as $_cat): $i++;?>
                            <?php
                                if($i==1 && !$sub_cat){$sub_cat = $_cat->term_id;}
                            ?>
                            <li class="download-center-item p16
                                <?php if(isset($_GET['sub_cat']) && $_GET['sub_cat'] == $_cat->term_id):echo ' active';?>
                                <?php elseif(!isset($_GET['sub_cat']) && $i==1):echo ' active';?>
                                <?php endif;?>"
                            >
                                <a href="<?php echo esc_url(reset_page_url_param('sub_cat',$_cat->term_id))?>#sub-download">
                                    <?php echo __($_cat->name,'storefront')?>
                                </a>
                            </li>
                        <?php endforeach;?>
                        
                    </ul>
                </div>
            </div>
        </div>
        <?php $files = get_download_files($sub_cat);?>
            <?php if($files['files']):?>
            <div class="download-file-box">
                <ul class="download-file-items bgfff">
                    <?php foreach ($files['files'] as $_file):
                        $download = new DLM_Download( $_file->ID );
                        $file     = $download->get_file_version();
                        $link  = $download->get_the_download_link() ;
                        $third = get_post_meta($_file->ID,'dlm_third',true);
                        if($third != '' && $third != null ){
                            $link = $third;
                        }
                        if($file->filename != '' && $file->filename != null){
                            $file_name = $file->filename;
                        }else{
                            $file_name = $_file->post_title;
                        }
                        $file_size = $download->get_the_filesize();
                        $file_date = date('Y. m. d',strtotime($_file->post_date_gmt));
                        $file_description = $_file->post_excerpt;
                        $_members_only = get_post_meta($_file->ID,'_members_only',true);
                        $refer = reset_page_url_param('refer',urlencode($current_url),wc_get_page_permalink('myaccount'));
                        ?>
                        <li class="file-item clearbox">
                            <img class="fl" src="<?php echo get_bloginfo('template_directory').'/assets/images/dobot/file.png' ?>">
                            <div class="down-file-info">
                                <div class="file-info clearbox">
                                    <div class="fl">
                                        <div class="file-name p18 p000"><?php echo $file_name?></div>
                                        <div class="file-date"><?php echo $_file->post_title .'&nbsp;&nbsp;&nbsp;&nbsp;'.$file_date?></div>
                                    </div>
                                    <div class="fr file-size-download below-pad-hide">
                                        <span class="file-size"><?php echo $file_size;?></span>
                                        <?php if($_members_only == 'yes' && is_user_logged_in()):?>
                                            <a target="_blank"  href="<?php echo esc_url($link)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                            </a>
                                        <?php elseif($_members_only == 'no'):?>
                                            <a  target="_blank"  href="<?php echo esc_url($link)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                            </a>
                                        <?php elseif($_members_only == 'yes' && !is_user_logged_in()):?>
                                            <a target="_blank"   href="<?php echo esc_url($refer)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                            </a>
                                        <?php endif;?>
                                    </div>
                                </div>
                                <div class="file-descirpiton"><?php echo $file_description?></div>
                                <div class="file-size-download below-pad-show">
                                    <span class="file-size"><?php echo $file_size;?></span>
                                    <?php if($_members_only == 'yes' && is_user_logged_in()):?>
                                        <a target="_blank"  href="<?php echo esc_url($link)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                        </a>
                                    <?php elseif($_members_only == 'no'):?>
                                        <a target="_blank"  href="<?php echo esc_url($link)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                        </a>
                                    <?php elseif($_members_only == 'yes' && !is_user_logged_in()):?>
                                        <a target="_blank"  href="<?php echo esc_url($refer)?>"><button type="submit" class="button" value="<?php _e('Download','storefront')?>"><?php _e('Download','storefront')?></button>
                                        </a>
                                    <?php endif;?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach;?>
                </ul>
            <div class="paged bckfff contest-entries-page">
            <?php echo $files['page']?>
            </div>
            </div>
            <?php else:?>
                <div class="no-data download-file-items bgfff">
                    <div><?php _e('There are no data.','storefront')?></div>
                </div>
            <?php endif;?>
        <?php else:?>
            <div class="no-data download-file-items">
                <div><?php _e('There are no category.','storefront')?></div>
            </div>
        <?php endif;?>
    </div>
</div>

<div class="responsive-img map-section">
    <div class="col-full">
        <div class="p40 textcenter text-uppercase"><?php _e('service in-store','storefront');?></div>
        <div class="page-banner-header-des textcenter"><?php _e('Find a location near you and register for a workshop.','storefront')?></div>
        <div class="textcenter"><a class="button text-uppercase" href="<?php echo esc_url(get_page_url('workshop'))?>"><?php _e('Find a Workshop','storefront')?></a></div>
    </div>
</div>