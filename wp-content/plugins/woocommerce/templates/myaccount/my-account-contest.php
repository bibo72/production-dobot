<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/3/17
 * Time: 17:11
 */
$user_contest = get_current_user_contest();
$contest_entries = $user_contest;
$edit_url = wc_get_account_endpoint_url('add-contest');
$view_url = wc_get_account_endpoint_url('contest-view');
$form_error = new WP_Error();
if ( is_wp_error( $form_error ) ) {
    foreach ( $form_error->get_error_messages() as $error ) {
        echo '<div>';
        echo '<strong>ERROR</strong>:';
        echo $error . '<br/>';
        echo '</div>';
    }
}
?>
<div class="my-account-tutorial dobot-account-right-habg account-tutorial-right">
    <div class="account-set-title"><?php echo __('My Contest','storefront');?></div>
    <?php if(count($contest_entries['post'])):?>
        <div class="tutorial-list">
            <table class="tutorial_table" border="0">
                <thead>
                <tr>
                    <th class="title"><?php _e('Title','woocommerce')?></th>
                    <th class="views textcenter"></th>
                    <th class="video-parise borderleft textcenter"></th>
                    <th class="comments borderleft textcenter"></th>
                    <th class="status borderleft textcenter"></th>
                    <th class="edit borderleft textcenter"></th>
                </tr>
                </thead>
                <tboby>
                    <?php foreach ($contest_entries['post'] as $_entries):
                        $views = get_post_meta($_entries->ID,'views',true) ? get_post_meta($_entries->ID,'views',true) : 0;
                        $liked = get_post_meta($_entries->ID,'_liked',true) ? get_post_meta($_entries->ID,'_liked',true) : 0;
                        $comments = $_entries->comment_count ;
                        $contest_id = get_contest_id_by_entries($_entries->ID);
                        ?>
                        <tr>
                            <td data-th='title' style="width:70%;"><a href="<?php echo esc_url(reset_page_url_param('post_id',$_entries->ID,wc_get_account_endpoint_url( 'contest-view')))?>"><?php _e($_entries->post_title,'woocommerce')?></a></td>
                            <td data-th='View' class="textcenter"><?php echo number_format($views)?></td>
                            <td data-th='Like' class="borderleft textcenter"><?php echo number_format($liked)?></td>
                            <td data-th='Comments' class="borderleft textcenter"><?php echo number_format($comments)?></td>
                            <td data-th='Status' class="borderleft textcenter"><?php echo __(get_contest_status_options($_entries->post_status))?></td>
                            <td data-th='Status' class="borderleft textcenter">
                                <?php if($_entries->post_status == 'draft' || $_entries->post_status == 'pending'):?>
                                    <a href="<?php echo $edit_url.'?cid='.$contest_id.'&post_id='.$_entries->ID?>"><?php _e('Edit','storefront')?></a>
                                    <a href="<?php echo reset_page_url_param('post_id',$_entries->ID ,$view_url);?>"><?php _e('View','storefront')?></a>
                                <?php else:?>
                                    <a href="<?php echo reset_page_url_param('post_id',$_entries->ID ,$view_url); ?>"><?php _e('View','storefront')?></a>
                                <?php endif;?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tboby>
            </table>
        </div>
        <?php if($contest_entries['page']):?>
        <div class="paged bckfff contest-entries-page">
            <?php echo $contest_entries['page'];?>
        </div>
        <?php endif;?>
    <?php else:?>
        <div class="no-data-box-notice">
            <p class="no-result-notice"><?php _e('You have not any contest entries,click <a style="color:red" href="'.home_url('contest-list').'">here</a> join a contest','woocommerce')?></p>
        </div>
    <?php endif;?>
</div>
