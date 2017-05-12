<?php
/**
 * Storefront template functions.
 *
 * @package storefront
 */

if ( ! function_exists( 'storefront_display_comments' ) ) {
	/**
	 * Storefront display comments
	 *
	 * @since  1.0.0
	 */

	function storefront_display_comments() {
		// If comments are open or we have at least one comment, load up the comment template.
        global $post;
        if(
            in_array($post->post_type,array('video','tutorial','event','contest','seo'))
        ){

        }else{
            if (comments_open() || '0' != get_comments_number()) :
                comments_template();
            endif;
        }
	}
}

if(!function_exists( 'dobot_post_list')){
    function dobot_post_list(){
        $cat_ID = get_query_var('cat');
        get_template_part('customer-tutorial');
    }
}


if ( ! function_exists( 'storefront_comment' ) ) {
	/**
	 * Storefront comment template
	 *
	 * @param array $comment the comment array.
	 * @param array $args the comment args.
	 * @param int   $depth the comment depth.
	 * @since 1.0.0
	 */
	function storefront_comment( $comment, $args, $depth ) {
		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo esc_attr( $tag ); ?> <?php if( $depth > 1){ echo ' style=""';} ?><?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
		<div class="comment-body">
		<div class="comment-head clearbox">
			<div class="comment-meta commentmetadata fl">
				<div class="comment-author vcard">
				<?php echo get_avatar( $comment, 30 ); ?>
				<?php printf( wp_kses_post( '<div class="pblue comment-anthor"><strong>%s</strong></div>', 'storefront' ), get_comment_author_link() ); ?>
				<!--<a href="<?php /*echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); */?>" class="comment-date">-->
				<?php echo '<time datetime="' . get_comment_date( 'c' ) . '">' . date('M, d, Y \a\t  h:i A',strtotime(get_comment_date('c'))). '</time>'; ?>
				<!--</a>-->
				</div>
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<em class="comment-awaiting-moderation"><?php esc_attr_e( 'Your comment is awaiting moderation.', 'storefront' ); ?></em>
					<br />
				<?php endif; ?>
			</div>
			<div class="reply fr">
			<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' =>$args['max_depth'] ) ) ); ?>
			<?php //edit_comment_link( __( 'Edit', 'storefront' ), '  ', '' ); ?>
			</div>
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-content">
		<?php endif; ?>
		<div class="comment-text">
		<?php comment_text(); ?>
		</div>
		
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif; ?>
	<?php
	}
}

if ( ! function_exists( 'storefront_footer_widgets' ) ) {
	/**
	 * Display the footer widget regions
	 *
	 * @since  1.0.0
	 * @return  void
	 */
	function storefront_footer_widgets() {
		if ( is_active_sidebar( 'footer-4' ) ) {
			$widget_columns = apply_filters( 'storefront_footer_widget_regions', 4 );
		} elseif ( is_active_sidebar( 'footer-3' ) ) {
			$widget_columns = apply_filters( 'storefront_footer_widget_regions', 3 );
		} elseif ( is_active_sidebar( 'footer-2' ) ) {
			$widget_columns = apply_filters( 'storefront_footer_widget_regions', 2 );
		} elseif ( is_active_sidebar( 'footer-1' ) ) {
			$widget_columns = apply_filters( 'storefront_footer_widget_regions', 1 );
		} else {
			$widget_columns = apply_filters( 'storefront_footer_widget_regions', 0 );
		}

		if ( $widget_columns > 0 ) : ?>

			<div class="footer-widgets col-<?php echo intval( $widget_columns ); ?> fix">

				<?php
				$i = 0;
				while ( $i < $widget_columns ) : $i++;
					if ( is_active_sidebar( 'footer-' . $i ) ) : ?>

						<div class="block footer-widget-<?php echo intval( $i ); ?>">
							<?php dynamic_sidebar( 'footer-' . intval( $i ) ); ?>
						</div>

					<?php endif;
				endwhile; ?>

			</div><!-- /.footer-widgets  -->

		<?php endif;
	}
}

if ( ! function_exists( 'storefront_credit' ) ) {
	/**
	 * Display the theme credit
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_credit() {
		?>
		<div class="site-info">
			<?php /*echo esc_html( apply_filters( 'storefront_copyright_text', $content = '&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ) ) ); */?>
			<?php /*if ( apply_filters( 'storefront_credit_link', true ) ) { */?>
			<!--<br />--> <?php /*printf( esc_attr__( '%1$s designed by %2$s.', 'storefront' ), 'Storefront', '<a href="http://www.woocommerce.com" title="WooCommerce - The Best eCommerce Platform for WordPress" rel="author">WooCommerce</a>' ); */?>
			<?php /*} */?>
            <div class="site-info-detail">
                <p>Dobot.cc &copy Copyright 2016. All Rights Reserved. 粤ICP备16048417号-1</p>
            </div>
            
            <div class="site-languge">
                <ul class="language-chooser">
                    <?php
                        $en_url = get_option('en_site_address');
                        $zh_url = get_option('zh_site_address');
                    ?>
                    <li class="active">
                        <a href="<?php echo $en_url.$_SERVER['REQUEST_URI']?>"><?php echo 'English';?></a>
                    </li>
                    <li class="inactive">
                        <a href="<?php echo $zh_url.$_SERVER['REQUEST_URI']?>"><?php echo  '中文';?></a>
                    </li>
                </ul>
            </div>
            <div class="site-server">
                <a href="<?php echo home_url('pricacy-pollcy').'.html'?>"><?php echo __('Pricacy Pollcy')?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                <a href="<?php echo home_url('terms-conditions').'.html'?>"><?php echo __('Terms & Conditions')?></a>
            </div>
		</div><!-- .site-info -->
		<?php
	}
}

if ( ! function_exists( 'storefront_header_widget_region' ) ) {
	/**
	 * Display header widget region
	 *
	 * @since  1.0.0
	 */
	function storefront_header_widget_region() {
		if ( is_active_sidebar( 'header-1' ) ) {
		?>
		<div class="header-widget-region" role="complementary">
			<div class="col-full">
				<?php dynamic_sidebar( 'header-1' ); ?>
			</div>
		</div>
		<?php
		}
	}
}

if ( ! function_exists( 'storefront_site_branding' ) ) {
	/**
	 * Site branding wrapper and display
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_site_branding() {
		?>
		<div class="site-branding">
			<?php storefront_site_title_or_logo(); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'storefront_site_title_or_logo' ) ) {
	/**
	 * Display the site title or logo
	 *
	 * @since 2.1.0
	 * @param bool $echo Echo the string or return it.
	 * @return string
	 */
	function storefront_site_title_or_logo( $echo = true ) {
		if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
			$logo = get_custom_logo();
			$html = is_home() ? '<h1 class="logo">' . $logo . '</h1>' : $logo;
		} elseif ( function_exists( 'jetpack_has_site_logo' ) && jetpack_has_site_logo() ) {
			// Copied from jetpack_the_site_logo() function.
			$logo    = site_logo()->logo;
			$logo_id = get_theme_mod( 'custom_logo' ); // Check for WP 4.5 Site Logo
			$logo_id = $logo_id ? $logo_id : $logo['id']; // Use WP Core logo if present, otherwise use Jetpack's.
			$size    = site_logo()->theme_size();
			$html    = sprintf( '<a href="%1$s" class="site-logo-link" rel="home" itemprop="url">%2$s</a>',
				esc_url( home_url( '/' ) ),
				wp_get_attachment_image(
					$logo_id,
					$size,
					false,
					array(
						'class'     => 'site-logo attachment-' . $size,
						'data-size' => $size,
						'itemprop'  => 'logo'
					)
				)
			);

			$html = apply_filters( 'jetpack_the_site_logo', $html, $logo, $size );
		} else {
			$tag = is_home() ? 'h1' : 'div';

			$html = '<' . esc_attr( $tag ) . ' class="beta site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></' . esc_attr( $tag ) .'>';

			if ( '' !== get_bloginfo( 'description' ) ) {
				$html .= '<p class="site-description">' . esc_html( get_bloginfo( 'description', 'display' ) ) . '</p>';
			}
		}

		if ( ! $echo ) {
			return $html;
		}

		echo $html;
	}
}

if ( ! function_exists( 'storefront_primary_navigation' ) ) {
	/**
	 * Display Primary Navigation
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_primary_navigation() {
		?>
		<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'storefront' ); ?>">
		<button class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"></button>
			<?php
			login_user_info_pad();
			language_switcher();
			wp_nav_menu(
				array(
					'theme_location'	=> 'primary',
					'container_class'	=> 'primary-navigation',
					)
			);

			wp_nav_menu(
				array(
					'theme_location'	=> 'handheld',
					'container_class'	=> 'handheld-navigation',
					)
			);
			?>
		</nav><!-- #site-navigation -->
		<?php
	}
}

if ( ! function_exists( 'storefront_secondary_navigation' ) ) {
	/**
	 * Display Secondary Navigation
	 *
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_secondary_navigation() {
	    if ( has_nav_menu( 'secondary' ) ) {
		    ?>
		    <nav class="secondary-navigation" role="navigation" aria-label="<?php esc_html_e( 'Secondary Navigation', 'storefront' ); ?>">
			    <?php
				    wp_nav_menu(
					    array(
						    'theme_location'	=> 'secondary',
						    'fallback_cb'		=> '',
					    )
				    );
			    ?>
		    </nav><!-- #site-navigation -->
		    <?php
		}
	}
}

if ( ! function_exists( 'storefront_skip_links' ) ) {
	/**
	 * Skip links
	 *
	 * @since  1.4.1
	 * @return void
	 */
	function storefront_skip_links() {
		?>
		<a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_attr_e( 'Skip to navigation', 'storefront' ); ?></a>
		<a class="skip-link screen-reader-text" href="#content"><?php esc_attr_e( 'Skip to content', 'storefront' ); ?></a>
		<?php
	}
}

if ( ! function_exists( 'storefront_page_header' ) ) {
	/**
	 * Display the post header with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function storefront_page_header() {
		?>
		<header class="entry-header">
			<?php
            //the_title( '<h1 class="entry-title">', '</h1>' );
           //storefront_post_thumbnail( 'full' );
			?>
		</header><!-- .entry-header -->
		<?php
	}
}

if ( ! function_exists( 'storefront_page_content' ) ) {
	/**
	 * Display the post content with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function storefront_page_content() {
		?>
		<div class="entry-content">
			<?php the_content(); ?>
			<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'storefront' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->
		<?php
	}
}

if ( ! function_exists( 'storefront_post_header' ) ) {
	/**
	 * Display the post header with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function storefront_post_header() {
		?>
		<?php
        $post = get_post(get_the_ID());
        $type = $post->post_type;
        $typeArr = array('event','video','tutorial','contest','seo');
        if(in_array($type,$typeArr)){

        }else{
        ?>
    		<header class="entry-header">
    	<?php
            if (is_single()) {
                storefront_posted_on();
                the_title('<h1 class="entry-title">', '</h1>');
            } else {
                if ('post' == get_post_type()) {
                    storefront_posted_on();
                }

                the_title(sprintf('<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');
            }
        ?>
        	</header><!-- .entry-header -->
        <?php }
		?>
		<?php
	}
}

if ( ! function_exists( 'storefront_post_content' ) ) {
	/**
	 * Display the post content with a link to the single post
	 *
	 * @since 1.0.0
	 */
	function storefront_post_content() {
		?>
		<div class="entry-content">
		<?php
		/**
		 * Functions hooked in to storefront_post_content_before action.
		 *
		 * @hooked storefront_post_thumbnail - 10
		 */
		///do_action( 'storefront_post_content_before' );

        $post = get_post(get_the_ID());
        if($post->post_type == 'video'){
             get_template_part('video-detail');
        }elseif($post->post_type == 'tutorial'){
            get_template_part('tutorial-detail');
        }elseif($post->post_type == 'contest'){
            get_template_part('entries-detail');
        }elseif($post->post_type == 'seo'){
            get_template_part('seo-detail');
        } else if($post->post_type == 'event'){
            $post = get_post(get_the_ID());
            ?>
            <div class="pro-tab-cont news-nav cms-pro-tab-cont full-screen">
			    <div class="col-full">
			    	<div class="col-full-cont clearbox">
				        <div class="pro-title">Company info</div>
				        <div class="pro-tab-cont-items">
					        <ul class="tabs wc-tabs">
						        <li class="active"><span><?php _e("News &amp; Event",'storefront')?></span></li>
						        <li class=""><a href="<?php echo esc_url( get_page_url( 'about-us' ) );?>"><?php _e("About Us",'storefront')?></a></li>
						        <li class=""><a href="<?php echo esc_url( get_page_url( 'contact-us' ) );?>"><?php _e("Contact Us",'storefront')?></a></li>
						        <li class=""><a href="<?php echo esc_url( get_page_url( 'join-us' ) );?>"><?php _e("Join Us",'storefront')?></a></li>
						        <li class=""><a href="<?php echo esc_url( get_page_url( 'partnership' ) );?>"><?php _e("Partnership",'storefront')?></a></li>
					        </ul>
				        </div>
			        </div>
			    </div>
			</div>
            <div class="news-info-content">
                <div class="news-title"><h1 class="p30"><?php _e(apply_filters(' ',$post->post_title))?></h1></div>
                <div class="news-share">
                    <span class="news-category"><?php _e('NEWS','storefront')?></span>
                    <span class="news-post-date"><?php echo date('Y-m-d',strtotime($post->post_date))?></span>
                	<a href="<?php echo share_to_social($post,'facebook')?>" target="_blank"><span class="customer-share-facebook"></span></a>
					<a href="<?php echo share_to_social($post,'twitter')?>" target="_blank"><span class="customer-share-twitter"></span></a>
					<a href="<?php echo share_to_social($post,'google-plus')?>" target="_blank"><span class="customer-share-google"></span></a>
                </div>
                <div class="news-content">
                	<?php _e(apply_filters(' ',$post->post_content))?>
                </div>
            </div>
        <?php
        }else{
            the_content(
                sprintf(
                    __('Continue reading %s', 'storefront'),
                    '<span class="screen-reader-text">' . get_the_title() . '</span>'
                )
            );

            do_action('storefront_post_content_after');

            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pages:', 'storefront'),
                'after' => '</div>',
            ));
        }
		?>
		</div><!-- .entry-content -->
		<?php
	}
}

if ( ! function_exists( 'storefront_post_meta' ) ) {
	/**
	 * Display the post meta
	 *
	 * @since 1.0.0
	 */
	function storefront_post_meta() {
        global  $post;
        if(
            $post->post_type == 'video' ||
            $post->post_type == 'tutorial'||
            $post->post_type == 'contest'||
            $post->post_type == 'event'||
            $post->post_type == 'seo'
        ){

        }else {
            ?>
            <aside class="entry-meta">
                <?php if ('post' == get_post_type()) : // Hide category and tag text for pages on Search.

                    ?>
                    <div class="author">
                        <?php
                        echo get_avatar(get_the_author_meta('ID'), 128);
                        echo '<div class="label">' . esc_attr(__('Written by', 'storefront')) . '</div>';
                        the_author_posts_link();
                        ?>
                    </div>
                    <?php
                    /* translators: used between list items, there is a space after the comma */
                    $categories_list = get_the_category_list(__(', ', 'storefront'));

                    if ($categories_list) : ?>
                        <div class="cat-links">
                            <?php
                            echo '<div class="label">' . esc_attr(__('Posted in', 'storefront')) . '</div>';
                            echo wp_kses_post($categories_list);
                            ?>
                        </div>
                    <?php endif; // End if categories.
                    ?>

                    <?php
                    /* translators: used between list items, there is a space after the comma */
                    $tags_list = get_the_tag_list('', __(', ', 'storefront'));

                    if ($tags_list) : ?>
                        <div class="tags-links">
                            <?php
                            echo '<div class="label">' . esc_attr(__('Tagged', 'storefront')) . '</div>';
                            echo wp_kses_post($tags_list);
                            ?>
                        </div>
                    <?php endif; // End if $tags_list.
                    ?>

                <?php endif; // End if 'post' == get_post_type().
                ?>

                <?php if (!post_password_required() && (comments_open() || '0' != get_comments_number())) : ?>
                    <div class="comments-link">
                        <?php echo '<div class="label">' . esc_attr(__('Comments', 'storefront')) . '</div>'; ?>
                        <span class="comments-link"><?php comments_popup_link(__('Leave a comment', 'storefront'), __('1 Comment', 'storefront'), __('% Comments', 'storefront')); ?></span>
                    </div>
                <?php endif; ?>
            </aside>
            <?php
        }
	}
}

if ( ! function_exists( 'storefront_paging_nav' ) ) {
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 */
	function storefront_paging_nav() {
		global $wp_query;

		$args = array(
			'type' 	    => 'list',
			'next_text' => _x( 'Next', 'Next post', 'storefront' ),
			'prev_text' => _x( 'Previous', 'Previous post', 'storefront' ),
			);

		the_posts_pagination( $args );
	}
}

if ( ! function_exists( 'storefront_post_nav' ) ) {
	/**
	 * Display navigation to next/previous post when applicable.
	 */
	function storefront_post_nav() {
		$args = array(
			'next_text' => '%title',
			'prev_text' => '%title',
			);
		the_post_navigation( $args );
	}
}

if ( ! function_exists( 'storefront_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function storefront_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time> <time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			_x( 'Posted on %s', 'post date', 'storefront' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo wp_kses( apply_filters( 'storefront_single_post_posted_on_html', '<span class="posted-on">' . $posted_on . '</span>', $posted_on ), array(
			'span' => array(
				'class'  => array(),
			),
			'a'    => array(
				'href'  => array(),
				'title' => array(),
				'rel'   => array(),
			),
			'time' => array(
				'datetime' => array(),
				'class'    => array(),
			),
		) );
	}
}

if ( ! function_exists( 'storefront_product_categories' ) ) {
	/**
	 * Display Product Categories
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function storefront_product_categories( $args ) {

		if ( storefront_is_woocommerce_activated() ) {

			$args = apply_filters( 'storefront_product_categories_args', array(
				'limit' 			=> 3,
				'columns' 			=> 3,
				'child_categories' 	=> 0,
				'orderby' 			=> 'name',
				'title'				=> __( 'Shop by Category', 'storefront' ),
			) );

			echo '<section class="storefront-product-section storefront-product-categories" aria-label="Product Categories">';

			do_action( 'storefront_homepage_before_product_categories' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			do_action( 'storefront_homepage_after_product_categories_title' );

			echo storefront_do_shortcode( 'product_categories', array(
				'number'  => intval( $args['limit'] ),
				'columns' => intval( $args['columns'] ),
				'orderby' => esc_attr( $args['orderby'] ),
				'parent'  => esc_attr( $args['child_categories'] ),
			) );

			do_action( 'storefront_homepage_after_product_categories' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'storefront_recent_products' ) ) {
	/**
	 * Display Recent Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function storefront_recent_products( $args ) {

		if ( storefront_is_woocommerce_activated() ) {

			$args = apply_filters( 'storefront_recent_products_args', array(
				'limit' 			=> 4,
				'columns' 			=> 4,
				'title'				=> __( 'New In', 'storefront' ),
			) );

			echo '<section class="storefront-product-section storefront-recent-products" aria-label="Recent Products">';

			do_action( 'storefront_homepage_before_recent_products' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			do_action( 'storefront_homepage_after_recent_products_title' );

			echo storefront_do_shortcode( 'recent_products', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			) );

			do_action( 'storefront_homepage_after_recent_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'storefront_featured_products' ) ) {
	/**
	 * Display Featured Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function storefront_featured_products( $args ) {

		if ( storefront_is_woocommerce_activated() ) {

			$args = apply_filters( 'storefront_featured_products_args', array(
				'limit'   => 4,
				'columns' => 4,
				'orderby' => 'date',
				'order'   => 'desc',
				'title'   => __( 'We Recommend', 'storefront' ),
			) );

			echo '<section class="storefront-product-section storefront-featured-products" aria-label="Featured Products">';

			do_action( 'storefront_homepage_before_featured_products' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			do_action( 'storefront_homepage_after_featured_products_title' );

			echo storefront_do_shortcode( 'featured_products', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
				'orderby'  => esc_attr( $args['orderby'] ),
				'order'    => esc_attr( $args['order'] ),
			) );

			do_action( 'storefront_homepage_after_featured_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'storefront_popular_products' ) ) {
	/**
	 * Display Popular Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function storefront_popular_products( $args ) {

		if ( storefront_is_woocommerce_activated() ) {

			$args = apply_filters( 'storefront_popular_products_args', array(
				'limit'   => 4,
				'columns' => 4,
				'title'   => __( 'Fan Favorites', 'storefront' ),
			) );

			echo '<section class="storefront-product-section storefront-popular-products" aria-label="Popular Products">';

			do_action( 'storefront_homepage_before_popular_products' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			do_action( 'storefront_homepage_after_popular_products_title' );

			echo storefront_do_shortcode( 'top_rated_products', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			) );

			do_action( 'storefront_homepage_after_popular_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'storefront_on_sale_products' ) ) {
	/**
	 * Display On Sale Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @param array $args the product section args.
	 * @since  1.0.0
	 * @return void
	 */
	function storefront_on_sale_products( $args ) {

		if ( storefront_is_woocommerce_activated() ) {

			$args = apply_filters( 'storefront_on_sale_products_args', array(
				'limit'   => 4,
				'columns' => 4,
				'title'   => __( 'On Sale', 'storefront' ),
			) );

			echo '<section class="storefront-product-section storefront-on-sale-products" aria-label="On Sale Products">';

			do_action( 'storefront_homepage_before_on_sale_products' );

			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

			do_action( 'storefront_homepage_after_on_sale_products_title' );

			echo storefront_do_shortcode( 'sale_products', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			) );

			do_action( 'storefront_homepage_after_on_sale_products' );

			echo '</section>';
		}
	}
}

if ( ! function_exists( 'storefront_best_selling_products' ) ) {
	/**
	 * Display Best Selling Products
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since 2.0.0
	 * @param array $args the product section args.
	 * @return void
	 */
	function storefront_best_selling_products( $args ) {
		if ( storefront_is_woocommerce_activated() ) {
			$args = apply_filters( 'storefront_best_selling_products_args', array(
				'limit'   => 4,
				'columns' => 4,
				'title'	  => esc_attr__( 'Best Sellers', 'storefront' ),
			) );
			echo '<section class="storefront-product-section storefront-best-selling-products" aria-label="Best Selling Products">';
			do_action( 'storefront_homepage_before_best_selling_products' );
			echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';
			do_action( 'storefront_homepage_after_best_selling_products_title' );
			echo storefront_do_shortcode( 'best_selling_products', array(
				'per_page' => intval( $args['limit'] ),
				'columns'  => intval( $args['columns'] ),
			) );
			do_action( 'storefront_homepage_after_best_selling_products' );
			echo '</section>';
		}
	}
}

if ( ! function_exists( 'storefront_homepage_content' ) ) {
	/**
	 * Display homepage content
	 * Hooked into the `homepage` action in the homepage template
	 *
	 * @since  1.0.0
	 * @return  void
	 */
	function storefront_homepage_content() {
		while ( have_posts() ) {
			the_post();

			get_template_part( 'content', 'page' );

		} // end of the loop.
	}
}

if ( ! function_exists( 'storefront_social_icons' ) ) {
	/**
	 * Display social icons
	 * If the subscribe and connect plugin is active, display the icons.
	 *
	 * @link http://wordpress.org/plugins/subscribe-and-connect/
	 * @since 1.0.0
	 */
	function storefront_social_icons() {
		if ( class_exists( 'Subscribe_And_Connect' ) ) {
			echo '<div class="subscribe-and-connect-connect">';
			subscribe_and_connect_connect();
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'storefront_get_sidebar' ) ) {
	/**
	 * Display storefront sidebar
	 *
	 * @uses get_sidebar()
	 * @since 1.0.0
	 */
	function storefront_get_sidebar() {
		get_sidebar();
	}
}

if ( ! function_exists( 'storefront_post_thumbnail' ) ) {
	/**
	 * Display post thumbnail
	 *
	 * @var $size thumbnail size. thumbnail|medium|large|full|$custom
	 * @uses has_post_thumbnail()
	 * @uses the_post_thumbnail
	 * @param string $size the post thumbnail size.
	 * @since 1.5.0
	 */
	function storefront_post_thumbnail( $size = 'full' ) {
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( $size );
		}
	}
}

if ( ! function_exists( 'storefront_primary_navigation_wrapper' ) ) {
	/**
	 * The primary navigation wrapper
	 */
	function storefront_primary_navigation_wrapper() {
		echo '<div class="storefront-primary-navigation">';
	}
}

if ( ! function_exists( 'storefront_primary_navigation_wrapper_close' ) ) {
	/**
	 * The primary navigation wrapper close
	 */
	function storefront_primary_navigation_wrapper_close() {
		echo '</div>';
	}
}

if ( ! function_exists( 'storefront_init_structured_data' ) ) {
	/**
	 * Generates structured data.
	 *
	 * Hooked into the following action hooks:
	 *
	 * - `storefront_loop_post`
	 * - `storefront_single_post`
	 * - `storefront_page`
	 *
	 * Applies `storefront_structured_data` filter hook for structured data customization :)
	 */
	function storefront_init_structured_data() {

		// Post's structured data.
		if ( is_home() || is_category() || is_date() || is_search() || is_single() && ( storefront_is_woocommerce_activated() && ! is_woocommerce() ) ) {
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'normal' );
			$logo  = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );

			$json['@type']            = 'BlogPosting';

			$json['mainEntityOfPage'] = array(
				'@type'                 => 'webpage',
				'@id'                   => get_the_permalink(),
			);

			$json['publisher']        = array(
				'@type'                 => 'organization',
				'name'                  => get_bloginfo( 'name' ),
				'logo'                  => array(
					'@type'               => 'ImageObject',
					'url'                 => $logo[0],
					'width'               => $logo[1],
					'height'              => $logo[2],
				),
			);

			$json['author']           = array(
				'@type'                 => 'person',
				'name'                  => get_the_author(),
			);

			if ( $image ) {
				$json['image']            = array(
					'@type'                 => 'ImageObject',
					'url'                   => $image[0],
					'width'                 => $image[1],
					'height'                => $image[2],
				);
			}

			$json['datePublished']    = get_post_time( 'c' );
			$json['dateModified']     = get_the_modified_date( 'c' );
			$json['name']             = get_the_title();
			$json['headline']         = $json['name'];
			$json['description']      = get_the_excerpt();

		// Page's structured data.
		} elseif ( is_page() ) {
			$json['@type']            = 'WebPage';
			$json['url']              = get_the_permalink();
			$json['name']             = get_the_title();
			$json['description']      = get_the_excerpt();
		}

		if ( isset( $json ) ) {
			Storefront::set_structured_data( apply_filters( 'storefront_structured_data', $json ) );
		}
	}
}

if(!function_exists('dobot_home_slider_after')){
    function dobot_home_slider_after(){
        $args=array(
            'get'   => 'all',
            'orderby' => 'id',
            'order' => 'ASC'
        );
        $categories=get_categories($args);
        echo '<div class="home-new-activity-video">';
        echo '<ul>';
        foreach ($categories as $category){
            include_once WP_PLUGIN_DIR.'/categories-images/categories-images.php';
            if($category->slug == 'video' || $category->slug == 'activity' || $category->slug == 'news'){
                if($category->slug == 'news'){
                    $link = get_page_url('news-events');
                }else if($category->slug == 'video'){
                    $link = get_page_url('videos-center');
                }else if($category->slug == 'activity'){
                	$link = get_page_url('contest-list');
                }
                $name = $category->name;
                $description = $category->description;
                $image = '<img src="'.z_taxonomy_image_url( $category->term_id, TRUE, TRUE ).'" title= "'.  __( $name ) .'"/>';
                echo '<li>';
                echo '<div class="category-image"><a href="' . $link . '" title="' .  __( $name ) . '" ' . '>' . $image.'</a></div>';
                echo '<div class="new-activity-foot"><div class="category-name"><a href="'.$link.'"><h3 style="margin-bottom: 0px;">'.__($name).'</h3></a></div>';
                echo '<div class="category-description">'.__($description).'</div></div>';
                echo '</li>';
            };
        }
        echo '</ul>';
        echo '</div>';
        echo '<div class="home-adv">'.do_shortcode('[metaslider id=207]').'</div>';
    }
}
if(!function_exists('language_switcher')){
     function language_switcher(){
         $en_url = get_option('en_site_address');
         $zh_url = get_option('zh_site_address');
         $headerLanguageEN  = '<a href="'.$en_url.$_SERVER['REQUEST_URI'].'">EN&nbsp;/&nbsp;</a>';
         $headerLanguageZH  = '<a href="'.$zh_url.$_SERVER['REQUEST_URI'].'">中文</a>';
         echo '<div class="header-language"><ul class="menu-header-language"><li class="menu-item">'. $headerLanguageEN.$headerLanguageZH .'</li></ul></div>';
     }
}
if(!function_exists('login_user_info')){
    function login_user_info(){
        $current_user = wp_get_current_user();
        $userName = $current_user->user_login;
        $user_email = $current_user->user_email;
        $userPhoto =  get_avatar($user_email, 32);
        $loginhtml='';
        if($userName!=''){
        	$loginhtml='<ul class="header-user-dropdown">';
        	$loginhtml.='<li class="header-user-dropdown-item"><a href="/my-account">'.$userPhoto.'<span name="user-name">'.$userName.'</span></a><a href="'.wc_logout_url().'" class="fr">'.__('Log Out').'</a></li>';
        	foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
                if( substr_count($endpoint,'contest')== 0 && $endpoint != 'tutorial-view' && $endpoint != 'inventions' && $endpoint!= 'add-tutorial' && $endpoint != 'tutorial' && $endpoint != 'customer-login' && $endpoint != 'customer-register'):
                    $loginhtml.='<li class="'.wc_get_account_menu_item_classes( $endpoint ).'">'.'<a href="'.esc_url( wc_get_account_endpoint_url( $endpoint ) ).'">'.esc_html( $label ).'</a>
                    </li>';
        	    endif;;
			endforeach;
			$loginhtml.='</ul>';
        }
        echo '<div class="heder-user-info"><ul class="header-user-photo">
                    <li><a class="clearbox account-link" href="/my-account">
                     <span class="photo">'.$userPhoto.'</span>
                     <span class="name">'.$userName.'</span>
                    </a>'.$loginhtml.'</li>
                </ul></div>';
    }
}

if(!function_exists('login_user_info_pad')){
    function login_user_info_pad(){
        $current_user = wp_get_current_user();
        $userName = $current_user->user_login;
        $user_email = $current_user->user_email;
        $userPhoto =  get_avatar($user_email, 32);
        $loginhtml='';
        if($userName!=''){
        	$loginhtml.='<a href="'.wc_logout_url().'" class="">'.__('Log Out').'</a>';
        }
        echo '<div class="heder-user-info"><ul class="header-user-photo">
                    <li><a class="clearbox account-link" href="'.home_url('my-account').'">
                     <span class="photo">'.$userPhoto.'</span>
                     <span class="name">'.$userName.'</span>
                    </a>'.$loginhtml.'</li>
                </ul></div>';
    }
}

if(!function_exists( 'dobot_tutorial_before' )){
    function dobot_tutorial_before(){}
}