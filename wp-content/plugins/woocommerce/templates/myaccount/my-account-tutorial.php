<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/3/17
 * Time: 17:11
 */
$also_like = get_the_most_views_and_like_tutorials(10);
$user_tutorial = get_user_tutorials();
$form_error = new WP_Error();
if ( is_wp_error( $form_error ) ) {
    foreach ( $form_error->get_error_messages() as $error ) {
        echo '<div>';
        echo '<strong>ERROR</strong>:';
        echo $error . '<br/>';
        echo '</div>';
    }
}

$title = null;
if(count($user_tutorial['post'])){
    $tutorials = $user_tutorial;
}else{
    $tutorials['post'] = $also_like;
    $tutorials['page'] = null;
    $title = __('You Also Like','storefront');
}

?>
<div class="my-account-tutorial dobot-account-right-habg <?php if($title==''):?>account-tutorial-right<?php endif;?>">
    <div class="account-set-title"><?php echo __('My Tutorial','woocommerce');?></div>
    <div class="publish-tutorial fr">
        <a href="<?php echo wc_get_account_endpoint_url('add-tutorial')?>" class="button"><?php _e('Publish Now!','woocommerce')?></a>
    </div>
    <?php if(!$user_tutorial['post']):?>
        <p class="no-data"><?php echo __('You have not any tutorials,please publish.','storefront')?></p>
    <?php endif;?>
    <?php
    if($title){
    ?></div><div class="dobot-account-right-habg">
    <div class="account-set-title also-like"><?php echo $title;?></div>
    <?php }?>
    <div class="tutorial-list">
        <table class="tutorial_table" border="0">
            <thead>
               <tr>
                   <th class="title"><?php _e('Title','woocommerce')?></th>
                   <th class="views textcenter"></th>
                   <th class="video-parise borderleft textcenter"></th>
                   <th class="comments borderleft textcenter"></th>
               </tr>
            </thead>

            <tboby>
                <?php foreach ($tutorials['post'] as $_tutorial):
                $views = get_post_meta($_tutorial->ID,'views',true) ? get_post_meta($_tutorial->ID,'views',true) : 0;
                $liked = get_post_meta($_tutorial->ID,'_liked',true) ? get_post_meta($_tutorial->ID,'_liked',true) : 0;
                $comments = $_tutorial->comment_count ;
                ?>
                <tr>
                    <?php if($title):?>
                        <td data-th='title' style="width:70%;"><a href="<?php echo esc_url(get_permalink($_tutorial->ID))?>"><?php _e($_tutorial->post_title,'woocommerce')?></a></td>
                    <?php else:?>
                        <td data-th='title' style="width:70%;"><a href="<?php echo esc_url(reset_page_url_param('post_id',$_tutorial->ID,wc_get_account_endpoint_url( 'tutorial-view')))?>"><?php _e($_tutorial->post_title,'woocommerce')?></a></td>
                    <?php endif;?>
                    <td data-th='View' class="textcenter"><?php echo number_format($views)?></td>
                    <td data-th='Like' class="borderleft textcenter"><?php echo number_format($liked)?></td>
                    <td data-th='Comments' class="borderleft textcenter"><?php echo number_format($comments)?></td>
                </tr>
                <?php endforeach;?>
            </tboby>
        </table>
    </div>
    <?php if($tutorials['page']):?>
        <div class="paged bckfff contest-entries-page">
            <?php echo $tutorials['page'];?>
        </div>
    <?php endif;?>
</div>
