<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/25
 * Time: 19:45
 */
function get_subscriber_email_html(){
    $message =  EMAIL_HEADER_TOP.__('Newsletter subscription success','storefront').EMAIL_HEADER_BOT.
        __('You have been successfully subscribed to our newsletter.','storefront').EMAIL_FOOTER;
    return $message;
}
