<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
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
	exit; // Exit if accessed directly
}
if(strpos($_SERVER['REQUEST_URI'],'customer-register') && !(is_user_logged_in())){
    wc_get_template( 'myaccount/form-register.php');

}else {
    ?>

    <?php wc_print_notices(); ?>

    <?php do_action('woocommerce_before_customer_login_form'); ?>

    <?php
    if (!(is_user_logged_in())) {
        ?>

        <div class="u-columns col-set" id="customer_login">
            <div class="u-column1 col-1">
                <h2 class="textcenter"><?php _e('LOG IN', 'woocommerce'); ?></h2>

                <form method="post" class="login">

                    <?php do_action('woocommerce_login_form_start'); ?>
                    <div class="woocommerce-other-acctount">
                        <?php echo do_shortcode('[fb_user_login]') ?>
                        <?php echo do_shortcode('[tw_user_login]') ?>
                        <!--	            <button type="button" class="button twitter-account"><?php /*_e('Login with Twitter','woocommerce')*/
                        ?></button>
-->            </div>
                    <p class="woocommerce-other-or textcenter">
                        <?php _e('OR', 'woocommerce') ?>
                    </p>

                    <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                        <!-- <label for="username"><?php //_e( 'Username or email address', 'woocommerce' );
                        ?> <span class="required">*</span></label> -->
                        <span class="required">*</span>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username"
                               id="username" placeholder="<?php _e('Full Name/Email', 'woocommerce'); ?>"
                               value="<?php if (!empty($_POST['username'])) echo esc_attr($_POST['username']); ?>"/>
                    </p>
                    <p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
                        <!-- <label for="password"><?php //_e( 'Password', 'woocommerce' );
                        ?> <span class="required">*</span></label> -->
                        <span class="required">*</span>
                        <input class="woocommerce-Input woocommerce-Input--text input-text" type="password"
                               name="password" id="password" placeholder="<?php _e('Password', 'woocommerce'); ?>"/>
                    </p>

                    <?php do_action('woocommerce_login_form'); ?>
                    <?php if ($_GET['refer']): ?>
                        <input type="hidden" name="redirect" value="<?php echo urldecode($_GET['refer']) ?>">
                    <?php endif; ?>
                    <p class="form-row">
                        <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                        <input type="submit" class="woocommerce-Button button loginsubmit" name="login"
                               value="<?php esc_attr_e('Login', 'woocommerce'); ?>"/>
                        <label for="rememberme" class="inline">
                            <input class="woocommerce-Input woocommerce-Input--checkbox" name="rememberme"
                                   type="checkbox" id="rememberme"
                                   value="forever"/> <?php _e('Remember me', 'woocommerce'); ?>
                        </label>
                    </p>
                    <p class="woocommerce-LostPassword lost_password">
                        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php _e('Lost your password?', 'woocommerce'); ?></a>
                    </p>
                    <div class="woocommerce-noAccount no-account textcenter">
                        <?php _e('Need an account? <a href="' . wc_customer_register_url() . '' . '">Sign Up</a>') ?>
                    </div>
                    <?php do_action('woocommerce_login_form_end'); ?>
                </form>
            </div>
        </div>
    <?php } else {
        $url = get_site_url() . '/my-account';
        header("Location:$url");
    }
}?>
<script type="text/javascript">

//绑定回车动作 
jQuery(".login #username").keydown(function(event){
	if(event.which==13){jQuery(".loginsubmit").click();return false;} 
});

jQuery(".login #password").keydown(function(event){ 
	if(event.which==13){jQuery(".loginsubmit").click();return false;} 
});

</script>