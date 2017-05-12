<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package storefront
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
global $post;
?>

<div id="comments" class="comments-area" aria-label="Post Comments">

	<div class="comments-title p25" id="comments-list-title"><?php _e('Comments . ');?><?php echo $post->comment_count;?></div>
	<?php
	if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'storefront' ); ?></p>
	<?php endif;
    global  $current_user;
    $user_email = $current_user->user_email;
    $user_name = $current_user->user_login;
    $dt = new DateTime();
	$args = apply_filters( 'storefront_comment_form_args', array(
		'title_reply_before' => '<span id="reply-title" class="gamma comment-reply-title">',
		'title_reply_after'  => '</span>',
        'title_reply'          => '',
        'cancel_reply_link'    => __('Cancel','storefront' ),
        'must_log_in'          => '<p class="must-log-in">' . sprintf(
                __( 'You must be <a href="%s" class="pblue pbold">logged in</a> to post a comment.' ),
                esc_url( reset_page_url_param('refer',urlencode(curPageURL()),wc_customer_login_url()))
            ) . '</p>',
        'logged_in_as'      => "<div class='current_user'>".
                                     "<div class=\"user-image\">". get_avatar($user_email, 65)."</div>".
                                "</div>",
        'comment_field'        => '<div class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="2" maxlength="65525" aria-required="true" placeholder="Add a public comment..." required="required"></textarea></div>',
    ) );

	comment_form( $args );
	?>

	<?php
	if ( have_comments() ) : ?>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
		<!--<nav id="comment-nav-above" class="comment-navigation" role="navigation" aria-label="Comment Navigation Above">
			<span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'storefront' ); ?></span>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'storefront' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'storefront' ) ); ?></div>
		</nav>--><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>

         <ol class="comment-list">
          	<?php
				wp_list_comments( array(
					'style'      	=> 'ol',
					'short_ping' 	=> false,
                    'max_depth'     => 999999,
                    'type'          => 'comment',
					'callback'		=> 'storefront_comment',
				) );
			?>
		</ol><!-- .comment-list -->
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
		<nav id="comments-navi"  data-ajax="<?php echo admin_url('admin-ajax.php')?>" data-fuck="<?php the_ID()?>" class="comment-navigation" role="navigation" aria-label="Comment Navigation Below">
           <?php if( get_option('default_comments_page') == 'newest'):?>
                <?php previous_comments_link(__('Load More')); ?>
           <?php elseif('oldest' == get_option('default_comments_page')):?>
               <?php next_comments_link(__('Load More')); ?>
           <?php endif;?>
        </nav><!-- #comment-nav-below -->
        <div id="loading-comments" class="textcenter" style="display: none"><img src="<?php echo get_bloginfo('template_directory').'/assets/images/dobot/loading.gif' ?>">
                <?php //_e('<!--:zh-->加载中,请稍候...<!--:---><!--:en-->loading...<!--:-->')?>
         </div>
		<?php endif; // Check for comment navigation.

	endif;
	?>


</div><!-- #comments -->
