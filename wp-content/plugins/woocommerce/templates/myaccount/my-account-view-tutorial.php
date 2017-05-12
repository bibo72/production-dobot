<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/3/23
 * Time: 15:26
 */
$post_id = $_GET['post_id'];
$post = get_post($post_id);
$post_content = $post->post_content;
?>
<div class="tutorial-back">
    <a href="/my-account/tutorial"><span class="go-back"><?php _e('< Back','woocommerce')?></span></a>
</div>
<div class="tutorial-detail dobot-account-right-habg">
	<h3 class="textcenter"><?php _e(apply_filters(' ',$post->post_title))?></h3>
    <?php _e(apply_filters(' ',$post_content))?>
</div>