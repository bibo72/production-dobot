<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="dobot-account-right-habg dashboard-right">
<p>
	<?php
		echo sprintf( esc_attr__( 'Hello %s%s%s, Welcome!', 'woocommerce' ), '<strong>', esc_html( $current_user->display_name ), '</strong>', '<a href="' . esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) ) . '">', '</a>' );
	?>
</p>

<p>
	<?php
		// echo sprintf( esc_attr__( 'From your account dashboard you can view your %1$srecent orders%2$s, manage your %3$sshipping and billing addresses%2$s and %4$sedit your password and account details%2$s.', 'woocommerce' ), '<a href="' . esc_url( wc_get_endpoint_url( 'orders' ) ) . '">', '</a>', '<a href="' . esc_url( wc_get_endpoint_url( 'edit-address' ) ) . '">', '<a href="' . esc_url( wc_get_endpoint_url( 'edit-account' ) ) . '">' );
		echo sprintf( esc_attr__( 'From your account dashboard you can upload video, public tutorial, manage your %3$sshipping and billing addresses%2$s and %4$sedit your password and account details%2$s.', 'woocommerce' ), '<a href="' . esc_url( wc_get_endpoint_url( 'orders' ) ) . '">', '</a>', '<a href="' . esc_url( wc_get_endpoint_url( 'edit-address' ) ) . '">', '<a href="' . esc_url( wc_get_endpoint_url( 'edit-account' ) ) . '">' );
	?>
</p>

<div class="inventions-header"><h3 class="account-set-title"><?php _e('I CAN','woocommerce')?></h3></div>
<div class="my-account-inventions dobot-account-right-habg inventions-right">
    
    <div class="inventions-upload-menu clearbox">
        <div class="inventions-upload-menu-item menu-item-video">
            <div class="inventions-upload-menu-item-cont">
                <div class="textcenter"><span class="inventions-mennu-head account-video-title"><?php _e('Upload Video','storefront')?><span></div>
                <div class="textcenter inventions-menu-des"><?php _e('You don\'t have any public recent uploads, so this will not appear on your chanel.','storefront')?></div>
                <!-- <div class="textcenter"><span id='upvideobtn' class="button"><?php //_e('Upload','woocommerce')?></span></div> --><div class="textcenter"><a href="<?php echo esc_url(wc_get_account_endpoint_url('inventions'))?>" class="button"><?php _e('Upload','woocommerce')?></a></div>
            </div>
        </div>
        <div class="inventions-upload-menu-item menu-item-tutorial">
            <div class="inventions-upload-menu-item-cont">
                <div class="textcenter"><span class="inventions-mennu-head account-tutorial-title"><?php _e('Upload Tutorial','storefront')?><span></div>
                <div class="textcenter inventions-menu-des"><?php _e('You don\'t have any public recent uploads, so this will not appear on your chanel.','storefront')?></div>
                <div class="textcenter"><a href="<?php echo esc_url(wc_get_account_endpoint_url('add-tutorial'))?>" class="button"><?php _e('Publish','storefront')?></a></div>
            </div>
        </div>
        <div class="inventions-upload-menu-item menu-item-setting">
            <div class="inventions-upload-menu-item-cont">
                <div class="textcenter"><span class="inventions-mennu-head account-set-title"><?php _e('Account Settings','storefront')?><span></div>
                        <div class="textcenter inventions-menu-des"><?php _e('You don\'t have any public recent uploads, so this will not appear on your chanel.','storefront')?></div>
                <div class="textcenter"><span class="button"><?php _e('Settings','woocommerce')?></span></div>
            </div>
        </div>
    </div>
</div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );
?>
