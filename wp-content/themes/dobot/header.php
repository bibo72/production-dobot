<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php
    if( is_single() || is_page() ) {
        if( function_exists('get_query_var') ) {
            $cpage = intval(get_query_var('cpage'));
            $commentPage = intval(get_query_var('comment-page'));
        }
        if( !empty($cpage) || !empty($commentPage) ) {
            echo '<meta name="robots" content="noindex, nofollow" />';
            echo "\n";
        }
    }
$keywords =  $value = get_option( 'web_keywords', '' );
$description =  $value = get_option( 'web_description', '' );
global $post;
$seo_keywords = get_post_meta($post->ID,'seo_keywords',TRUE);
$seo_description = get_post_meta($post->ID,'seo_description',TRUE);
?>
<?php if($keywords != '' && $keywords != null && $seo_keywords == ''):?>
<meta name="keywords" content="<?php echo $keywords?>">
<?php else:?>
    <meta name="keywords" content="<?php echo $seo_keywords?>">
<?php endif;?>
<?php if($description != '' && $description != null && $seo_description == ''):?>
<meta name="description" content="<?php echo $description?>">
<?php else:?>
    <meta name="description" content="<?php echo $seo_description?>">
<?php endif;?>

<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
<meta name = "format-detection" content = "telephone=no" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link href='http://cdn.webfont.youziku.com/webfonts/nomal/101989/35099/590196f3f629d81470a2fc9f.css' rel='stylesheet' type='text/css' />
<?php wp_head(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/sass/swiper/swiper-3.4.2.min.css"/>
<link href='http://cdn.webfont.youziku.com/webfonts/nomal/101989/46768/5901950bf629d81470a2fc96.css' rel='stylesheet' type='text/css' />
<link rel="stylesheet" type="text/css" href="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/layer/skin/layer.css"/>
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/dobot.js"></script>
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/layer/layer.js"></script>
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/kindeditor/kindeditor.js"></script>
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/CloudCarousel.1.0.5.js"></script>
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/jquery.mousewheel.js"></script>
<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/js/swiper-3.4.2.jquery.min.js"></script>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
    <script>
        var dobot_obj = {"ajax_url":"<?php echo admin_url('admin-ajax.php');?>"};
    </script>
	<?php
	do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner" style="<?php storefront_header_styles(); ?>">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked into storefront_header action
			 *
			 * @hooked storefront_skip_links                       - 0
			 * @hooked storefront_social_icons                     - 10
			 * @hooked storefront_site_branding                    - 20
			 * @hooked storefront_secondary_navigation             - 30
			 * @hooked storefront_product_search                   - 40
			 * @hooked storefront_primary_navigation_wrapper       - 42
			 * @hooked storefront_primary_navigation               - 50
			 * @hooked storefront_header_cart                      - 60
			 * @hooked storefront_primary_navigation_wrapper_close - 68
			 */
			do_action( 'storefront_header' ); ?>

		</div>
	</header><!-- #masthead -->

	<?php
	/**
	 * Functions hooked in to storefront_before_content
	 * @hooked storefront_header_widget_region - 10
	 */
	do_action( 'storefront_before_content' ); ?>

	<div id="content" class="site-content" tabindex="-1">
        <?php if(is_account_page()):?>
            <?php echo do_shortcode('[my_account_head]')?>
        <?php endif;?>
		<div class="col-full site-content-col-full">

		<?php
		/**
		 * Functions hooked in to storefront_content_top
		 *
		 * @hooked woocommerce_breadcrumb - 10
		 */
		do_action( 'storefront_content_top' );