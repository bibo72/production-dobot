<?php
/*
Plugin Name: .html on PAGES
Plugin URI: http://www.introsites.co.uk/33~html-wordpress-permalink-on-pages-plugin.html
Description: Adds .html to pages.
Author: IntroSites
Version: 1.1
Author URI: http://www.introsites.co.uk/
*/

    add_action('init', 'html_page_permalink', -1);
    register_activation_hook(__FILE__, 'active');
    register_deactivation_hook(__FILE__, 'deactive');


    function html_page_permalink() {
        $string = $_SERVER['REQUEST_URI'];
        $pos = strpos($string, "/my-account.html");
        if ($pos !== false) {
            wp_redirect(get_option('home') . str_replace('/my-account.html', '/my-account', $string), 301);
            exit();
        } else {
            $pos = strpos($string, "/my-account");
            if ($pos !== false) {
                $_SERVER['REQUEST_URI'] = str_replace("/my-account", "/my-account.html", $string);
                global $wp;
                $wp->parse_request();
            }
        }
        global $wp_rewrite;
        $permastruct = $wp_rewrite->get_page_permastruct();
        if ( !strpos($permastruct, '.html')){
            $wp_rewrite->page_structure = $permastruct. '.html';
        }
    }
    add_filter('page_link', 'blog_permalinks_page_link', 10, 2);
    function blog_permalinks_page_link($permalink, $page) {
        $pos = strpos($permalink, "/my-account.html");
        if ($pos !== false) {
            $permalink = str_replace("/my-account.html", "/my-account", $permalink);
        }
        return $permalink;
    }

    add_filter('user_trailingslashit', 'no_page_slash', 66, 2);
    function no_page_slash($string, $type){
        global $wp_rewrite;
        if ($wp_rewrite->using_permalinks() && $wp_rewrite->use_trailing_slashes==true && $type == 'page'){
            return untrailingslashit($string);
        }else{
            return $string;
      }
    }


    function active() {
        global $wp_rewrite;
        if ( !strpos($wp_rewrite->get_page_permastruct(), '.html') ){
            $wp_rewrite->page_structure = $wp_rewrite->page_structure . '.html';
        }
        $wp_rewrite->flush_rules();
    }
	function deactive() {
		global $wp_rewrite;
		$wp_rewrite->page_structure = str_replace(".html","",$wp_rewrite->page_structure);
		$wp_rewrite->flush_rules();
	}