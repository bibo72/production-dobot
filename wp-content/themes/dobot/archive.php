<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package storefront
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
            <?php
                $term = get_queried_object();
                $taxonomy = get_term_taxonomy_cat($term->term_id);
                $term_id = $term->term_id;
                if ( $taxonomy == 'seo-category' ) {
                    echo do_shortcode("[resource_seo id='$term_id']");
                }else {?>
                    <?php if (have_posts()) : ?>
                        <header class="page-header">
                            <?php
                            //the_archive_title( '<h1 class="page-title">', '</h1>' );
                            //the_archive_description( '<div class="taxonomy-description">', '</div>' );
                            ?>
                        </header><!-- .page-header -->
                        <?php
                        get_template_part('loop');
                    else :
                        get_template_part('content', 'none');
                    endif;
                }
            ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'storefront_sidebar' );
get_footer();