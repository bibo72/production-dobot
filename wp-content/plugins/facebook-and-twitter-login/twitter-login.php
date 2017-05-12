<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/28
 * Time: 16:14
 */
session_start();
include_once 'includes/twitteroauth/twitteroauth.php';
class Twitter{

    public  function __construct(){
        add_action('wp_logout', array($this,'destroy_session'));
        add_action('init',array($this,'tw_login_short_code'));
        add_action('wp_ajax_nopriv_tw_user_login_cwd', array($this,'tw_user_login_cwd') );
        add_action('wp_ajax_tw_user_login_cwd', array($this,'tw_user_login_cwd') );
        $this->api_key = get_option('twitter_apikey');
        $this->api_secret = get_option('twitter_secret');
        $this->callback = get_option('twitter_callback');
    }

    protected function save_twitter_user_into_wp($data){
        $email = $data['id'].'twitter@qq.com';
        $wp_user = get_user_by('email', $email);
        if ($wp_user->ID) {
            wp_set_auth_cookie($wp_user->ID);
            $_SESSION['tw_login'] = true;
        } else {
            $username = $data['name'];
            $password = wp_generate_password();
            wp_create_user($username, $password, $email);
            $_SESSION['tw_login'] = true;
        }
        echo "<script>window.close();window.opener.location.reload();</script>";
        exit;
    }

    public function tw_user_login_cwd(){
        $error = null;
        if(	! isset ( $_SESSION [ 'oauth_token' ] ) ) {
            $connection = new TwitterOAuth($this->api_key, $this->api_secret);
            $request_token = $connection->getRequestToken($this->callback);
            $token = $request_token['oauth_token'];
            $_SESSION['oauth_token'] = $token;
            $_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
            if ($connection->http_code == 200) {
                $url = $connection->getAuthorizeURL($token);
                header("Location:$url",true,301);
                exit;
            }
            else{
                $error = 'Could not connect to Twitter. Refresh the page or try again later';
            }
        }else {
            $connection = new TwitterOAuth ($this->api_key, $this->api_secret, $_SESSION ['oauth_token'], $_SESSION ['oauth_token_secret']);
            $access_token = $connection->getAccessToken($_REQUEST ['oauth_verifier']);
            $_SESSION ['access_token'] = $access_token;
            $content = $connection->get('account/verify_credentials');
            $data = array();
            if ($connection->http_code == 200) {
                if (!empty ($content->id)) {
                    $data ['id'] = $content->id;
                    $data ['name'] = $content->name;
                    $data ['screen_name'] = $content->screen_name;
                    $data ['picture'] = $content->profile_image_url;
                    $this->save_twitter_user_into_wp($data);
                }
            }else{
                $error = 'Could not connect to Twitter. Refresh the page or try again later';
            }
        }
        $response['error'] = $error;
        wp_die(json_encode($response));
        exit;
    }

    public function destroy_session(){
        session_destroy();
    }

    public function tw_login_short_code(){
        add_shortcode('tw_user_login',array($this,'tw_user_create'));
    }

    public function tw_user_create(){
        if ($this->api_key !== false) {
            wp_register_script('tw_ajax', plugins_url('/assets/js/login.js', __FILE__));
            $translation_array = array('tw_ajax' => get_bloginfo("url") . "/wp-admin/admin-ajax.php?action=tw_user_login_cwd", 'api_key' => $this->api_key);
            wp_localize_script('tw_ajax', 'tw_ajax', $translation_array);
            wp_enqueue_script('tw_ajax', plugins_url('/assets/js/login.js', __FILE__));
            echo "<p><a href='javascript:void(0)' onclick='twitter_Login(this)'><button type=\"button\"  class=\"button twitter-account\">". __('Login with Twitter','woocommerce')."</button></a></p>";
        }
    }
}