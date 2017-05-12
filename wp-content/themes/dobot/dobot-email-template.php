<?php
$email_logo = get_bloginfo('url') .'/wp-content/themes/dobot/assets/images/dobot/email-logo.png';
define ('emailbg', $email_bg );
$email_headertop = '
    <div class="emailpaged" style="background-image: url();-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover;background-position: center center;background-repeat: no-repeat;">
        <div class="emailcontent" style="width:100%;max-width:720px;text-align: left;margin: 0 auto;padding-top: 80px;padding-bottom: 20px">
            <div class="email-logo" style="text-align: center; border: none; padding: 8px; margin-bottom: 15px;">
                <a class="logo" href="'.get_bloginfo('url').'" style="color: #1979c3; text-decoration: none;" target="_blank">
                   <img src="'.$email_logo.'" style="border: 0px none; height: auto; line-height: 100%; outline: 0px none; text-decoration: none; width: 250px;"/>
                </a>
            </div>
            <div class="emailtitle">
                <h1 style="color:#fff;background: #51a0e3;line-height:70px;font-size:24px;font-weight:normal;padding-left:40px;margin:0">
';
define ('EMAIL_HEADER_TOP', $email_headertop );

$email_headerbot = '
                </h1>
                <div class="emailtext" style="background: rgb(255, 255, 255) none repeat scroll 0% 0%; padding: 20px 32px 40px; min-height: 100px; height: 100px;">
';
define ('EMAIL_HEADER_BOT', $email_headerbot );

$email_footer = '
                    <p style="color: #6e6e6e;font-size:13px;line-height:24px;">('.__('This message is automatically issued by the system, please do not reply.','storefront').')</p>
                </div>
                <p style="color: #6e6e6e;font-size:13px;line-height:24px;text-align:right;padding:0 32px">'.__('Mail from:','storefront').'<a href="' . get_bloginfo('url') . '" style="color:#51a0e3;text-decoration:none">' . get_option("blogname") . '</a></p>
            </div>
        </div>
    </div>
';
define ('EMAIL_FOOTER', $email_footer );