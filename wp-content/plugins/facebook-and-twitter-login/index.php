<?php
/*
Plugin Name: Facebook and Twitter login
Plugin URI: http://www.coralwebdesigns.com/
Version: 1.3
Author: Coral Web Design
Description: A plugin for creating user with their twitter and facebook credentials
*/
session_start();
include_once 'facebook-login.php';
include_once 'twitter-login.php';
new Facebook();
new Twitter();

add_action('admin_menu', 'fb_tw_init_settings');

function fb_tw_init_settings()
{
    add_menu_page('Facebook and Twitter Login', 'Facebook and Twitter Login', 'manage_options', 'loginsettings', 'fb_tw_settings', plugins_url( 'facebook-and-twitter-login/images/icon.png' ),82);
}

function fb_tw_settings()
{
    if (isset($_POST['twapi'])) {
        if ($_POST['twapi']) {
            $key = $_POST['twitterapiname'];
            $secret = $_POST['twitterapisecret'];
            $oauth = $_POST['twoauth'];
            if (get_option('twitter_apikey') !== false) {
                update_option('twitter_apikey', $key);
                update_option('twitter_secret', $secret);
                update_option('twitter_callback', $oauth);
            } else {
                add_option('twitter_apikey', $key);
                add_option('twitter_secret', $secret);
                add_option('twitter_callback', $oauth);
            }
        }
    }
    if (isset($_POST['fbapi'])) {
        if ($_POST['fbapi']) {
            $api = $_POST['fbapiname'];
            $secret = $_POST['fbapisecret'];
            if (get_option('facebook_api') != false) {
                update_option('facebook_api', $api);
                update_option('facebook_secret', $secret);
            } else {
                add_option('facebook_api', $api);
                add_option('facebook_secret', $secret);
            }
        }
    }
    if (isset($_POST['clear'])) {
        if ($_POST['clear']) {
            if (get_option('twitter_apikey')) {
                delete_option('twitter_apikey');
            }
            if (get_option('twitter_secret')) {
                delete_option('twitter_secret');
            }
            if (get_option('twitter_callback')) {
                delete_option('twitter_callback');
            }
            if (get_option('facebook_api')) {
                delete_option('facebook_api');
            }
            if (get_option('facebook_secret')) {
                delete_option('facebook_secret');
            }
        }
    }
    $twitter_callback = get_bloginfo("url") . "/wp-admin/admin-ajax.php?action=tw_user_login_cwd" ;
    echo '<form action="" method="post">';
    echo '<h2>Enter your Facebook App Secret and API ID</h2><br>';
    echo '<label for="name" id="for_align">API ID </label><input type="text" name="fbapiname" id="fbkey" value="' . get_option('facebook_api') . '" size="55"><br><br><br>';
    echo '<label for="name" id="for_align">API Secret </label><input type="password" name="fbapisecret" id="fbsecret" value="' . get_option('facebook_secret') . '" size="55"><br><br><br>';
    echo '<input type="submit" name="fbapi" value="Set" id="fbapikey" class="button-primary">';
    echo '</form>';
    echo '<form action="" method="post">';
    echo '<br><h2>Enter your Twitter API key and API secret</h2><br><br>';
    echo '<label for="name" id="for_align">API Key </label><input type="text" name="twitterapiname" value="' . get_option('twitter_apikey') . '" size="55"><br><br>';
    echo '<label for="name" id="for_align">API Secret</label><input type="password" name="twitterapisecret" value="' . get_option('twitter_secret') . '" size="55"><br><br>';
    echo '<input type="hidden" name="twoauth"  value="' . $twitter_callback . '" size="55">';
    echo '<label for="name" id="for_align">OAuth Callback </label><input type="text" name="twoauth_callback" disabled="disabled" value="' . $twitter_callback . '" size="55"><br><br><br><br>';
    echo '<input type="submit" name="twapi" value="Set" class="button-primary"><br><br><br><br>';
    echo '</form>';
    echo '<form action="" method="post">';
    echo '<input type="submit" name="clear" value="Clear API Details" class="button-primary">';
    echo '</form>';
}
