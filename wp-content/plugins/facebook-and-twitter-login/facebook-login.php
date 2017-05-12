<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/4/28
 * Time: 11:29
 */
session_start();
include_once  'includes/Facebook/autoload.php';
class Facebook{

    function __construct(){
        add_action('wp_logout', array($this,'destroy_session'));
        add_action('init',array($this,'fb_login_short_code'));
        add_action('wp_ajax_nopriv_fb_user_login_cwd', array($this,'fb_user_login_cwd') );
        add_action('wp_ajax_fb_user_login_cwd', array($this,'fb_user_login_cwd') );
        $this->app_id = get_option('facebook_api');
        $this->app_secret = get_option('facebook_secret');
        $this->callback = get_bloginfo("url") . "/wp-admin/admin-ajax.php?action=fb_user_login_cwd";
        if($this->app_id){
            $this->fb = new Facebook\Facebook([
                'app_id' => $this->app_id,
                'app_secret' => $this->app_secret,
                'default_graph_version' => 'v2.5',
            ]);
        }
    }

    function getLoginUrl(){
        $helper = $this->fb->getRedirectLoginHelper();
        $permissions = ['email','public_profile','user_likes']; // optional
        $loginUrl = $helper->getLoginUrl($this->callback, $permissions);
        return $loginUrl;
    }

    function fb_user_login_cwd(){
        $helper = $this->fb->getRedirectLoginHelper();
        try {
            if (isset($_SESSION['facebook_access_token'])) {
                $accessToken = $_SESSION['facebook_access_token'];
            }else{
                $accessToken = $helper->getAccessToken($this->callback);
            }
            if (isset($accessToken)) {
                if (isset($_SESSION['facebook_access_token'])) {
                    $this->fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                } else {
                    $_SESSION['facebook_access_token'] = (string)$accessToken;
                    $oAuth2Client = $this->fb->getOAuth2Client();
                    // 延长访问口令期限，将短期访问口令更换为长期访问口令
                    $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
                    $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
                    // setting default access token
                    $this->fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                }
            }
            $response = $this->fb->get('/me?fields=id,name,first_name,last_name,email,picture');
            $fb_user = $response->getGraphNode()->asArray();
            $wp_user = get_user_by('email', $fb_user['email']);
            if ($wp_user->ID) {
                wp_set_auth_cookie($wp_user->ID);
                $_SESSION['fb_login'] = true;
            } else {
                $username = $fb_user['name'];
                $password = wp_generate_password();
                wp_create_user($username, $password, $fb_user['email']);
                $_SESSION['fb_login'] = true;
            }
            echo "<script>window.close();window.opener.location.reload();</script>";
            exit;
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo json_encode(array('error'=>"Graph returned an error ".$e->getCode().":". $e->getMessage()));
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo json_encode(array('error'=>"Facebook SDK returned an error ".$e->getCode().":".$e->getMessage()));
            exit;
        }
    }

    /**
     * create facebook login button
     */
    function fb_user_create(){
        if ($this->app_id !== false) {
            wp_register_script('for_ajax', plugins_url('/assets/js/login.js', __FILE__));
            $translation_array = array('url_ajax' => get_bloginfo("url") . "/wp-admin/admin-ajax.php", 'user_api' => $this->app_id);
            wp_localize_script('for_ajax', 'url', $translation_array);
            wp_enqueue_script('for_ajax', plugins_url('/assets/js/login.js', __FILE__));
            echo "<p><a data-href='".$this->getLoginUrl()."' onclick='fb_login(this)'><button type=\"button\"  class=\"button facebook-account\">". __('Login with FaceBook','woocommerce')."</button></a></p>";
        }
    }

    function destroy_session(){
        session_destroy();
    }

    function  fb_login_short_code(){
        add_shortcode('fb_user_login',array($this,'fb_user_create'));
    }
}
