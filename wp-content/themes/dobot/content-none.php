<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package storefront
 */

?>

<div class="no-results not-found">

	<header class="page-header">
        <?php if(!is_category()):?>
		    <h1 class="page-title"><?php esc_html_e( 'Nothing Found', 'storefront' ); ?></h1>
        <?php else:?>
            <h1 class="page-title"><?php echo single_cat_title('',false); ?></h1>
        <?php endif;?>
	</header><!-- .page-header -->


	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( wp_kses( __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'storefront' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'storefront' ); ?></p>
			<?php get_search_form(); ?>
        <?php elseif(is_category()) :?>
            <?php if(qtrans_getLanguage() == 'zh'):?>
                <p><?php echo '这是'.single_cat_title('',false). '页面.'?></p>
            <?php elseif(qtrans_getLanguage() == 'en'):?>
                <p><?php echo 'This is '.strtolower(single_cat_title('',false)). ' page.'?></p>
            <?php endif; ?>
		<?php else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'storefront' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .page-content -->
</div><!-- .no-results -->