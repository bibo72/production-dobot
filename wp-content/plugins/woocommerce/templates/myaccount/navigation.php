<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation">
	<div class="account-info">
		<?php global $current_user;if($current_user->description!=''):?>
		<p class="textcenter"><?php  echo ('"'.$current_user->description.'"');?></p>
		<?php endif;?>
		<div class="textcenter">
			<div class="clearbox totaltem">
				<div class="postnum">
					<?php _e('Post','woocommerce');?>
                    <?php $user_post_count = get_user_post_count($current_user->ID)?>
					<label><?php echo number_format($user_post_count)?></label>
				</div>
				<div>
					<?php _e('Like','woocommerce');?>
                    <?php $user_post_like_count = get_user_post_like_count($current_user->ID)?>
					<label><?php echo number_format($user_post_like_count)?></label>
				</div>
			</div>
		</div>
		
	</div>
	<div class="clearbox woocommerce-MyAccount-navigation-overhide">
		<div class="woocommerce-MyAccount-navigation-items-cont">
			<ul class="woocommerce-MyAccount-navigation-items">
				<li><?php _e('Setting');?></li>
				<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
		            <?php if($endpoint != 'tutorial-view' && $endpoint != 'inventions'
		                && $endpoint!= 'add-tutorial' && $endpoint != 'tutorial'
		                && $endpoint!='contest-view' && $endpoint!= 'contest' && $endpoint!= 'add-contest'
		                && $endpoint!= 'customer-login' && $endpoint != 'customer-register'
		            ){?>
					<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
						<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
					</li>
		            <?php }?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>
