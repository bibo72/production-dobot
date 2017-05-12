<?php
/**
 * Storefront  functions.
 *
 * @package storefront
 */

if ( ! function_exists( 'storefront_is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function storefront_is_woocommerce_activated() {
		return class_exists( 'woocommerce' ) ? true : false;
	}
}

/**
 * Checks if the current page is a product archive
 * @return boolean
 */
function storefront_is_product_archive() {
	if ( storefront_is_woocommerce_activated() ) {
		if ( is_shop() || is_product_taxonomy() || is_product_category() || is_product_tag() ) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}

/**
 * Call a shortcode function by tag name.
 *
 * @since  1.4.6
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function storefront_do_shortcode( $tag, array $atts = array(), $content = null ) {
	global $shortcode_tags;

	if ( ! isset( $shortcode_tags[ $tag ] ) ) {
		return false;
	}

	return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}

/**
 * Get the content background color
 * Accounts for the Storefront Designer and Storefront Powerpack content background option.
 *
 * @since  1.6.0
 * @return string the background color
 */
function storefront_get_content_background_color() {
	if ( class_exists( 'Storefront_Designer' ) ) {
		$content_bg_color = get_theme_mod( 'sd_content_background_color' );
		$content_frame    = get_theme_mod( 'sd_fixed_width' );
	}

	if ( class_exists( 'Storefront_Powerpack' ) ) {
		$content_bg_color = get_theme_mod( 'sp_content_frame_background' );
		$content_frame    = get_theme_mod( 'sp_content_frame' );
	}

	$bg_color = str_replace( '#', '', get_theme_mod( 'background_color' ) );

	if ( class_exists( 'Storefront_Powerpack' ) || class_exists( 'Storefront_Designer' ) ) {
		if ( $content_bg_color && ( 'true' == $content_frame || 'frame' == $content_frame ) ) {
			$bg_color = str_replace( '#', '', $content_bg_color );
		}
	}

	return '#' . $bg_color;
}

/**
 * Apply inline style to the Storefront header.
 *
 * @uses  get_header_image()
 * @since  2.0.0
 */
function storefront_header_styles() {
	$is_header_image = get_header_image();

	if ( $is_header_image ) {
		$header_bg_image = 'url(' . esc_url( $is_header_image ) . ')';
	} else {
		$header_bg_image = 'none';
	}

	$styles = apply_filters( 'storefront_header_styles', array(
		'background-image' => $header_bg_image,
	) );

	foreach ( $styles as $style => $value ) {
		echo esc_attr( $style . ': ' . $value . '; ' );
	}
}

/**
 * Adjust a hex color brightness
 * Allows us to create hover styles for custom link colors
 *
 * @param  strong  $hex   hex color e.g. #111111.
 * @param  integer $steps factor by which to brighten/darken ranging from -255 (darken) to 255 (brighten).
 * @return string        brightened/darkened hex color
 * @since  1.0.0
 */
function storefront_adjust_color_brightness( $hex, $steps ) {
	// Steps should be between -255 and 255. Negative = darker, positive = lighter.
	$steps  = max( -255, min( 255, $steps ) );

	// Format the hex color string.
	$hex    = str_replace( '#', '', $hex );

	if ( 3 == strlen( $hex ) ) {
		$hex    = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
	}

	// Get decimal values.
	$r  = hexdec( substr( $hex, 0, 2 ) );
	$g  = hexdec( substr( $hex, 2, 2 ) );
	$b  = hexdec( substr( $hex, 4, 2 ) );

	// Adjust number of steps and keep it inside 0 to 255.
	$r  = max( 0, min( 255, $r + $steps ) );
	$g  = max( 0, min( 255, $g + $steps ) );
	$b  = max( 0, min( 255, $b + $steps ) );

	$r_hex  = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
	$g_hex  = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
	$b_hex  = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

	return '#' . $r_hex . $g_hex . $b_hex;
}

/**
 * Sanitizes choices (selects / radios)
 * Checks that the input matches one of the available choices
 *
 * @param array $input the available choices.
 * @param array $setting the setting object.
 * @since  1.3.0
 */
function storefront_sanitize_choices( $input, $setting ) {
	// Ensure input is a slug.
	$input = sanitize_key( $input );

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Checkbox sanitization callback.
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 * @since  1.5.0
 */
function storefront_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	/**
	 * Query WooCommerce activation
	 */
	function is_woocommerce_activated() {
		_deprecated_function( 'is_woocommerce_activated', '2.1.6', 'storefront_is_woocommerce_activated' );

		return class_exists( 'woocommerce' ) ? true : false;
	}
}

/**
 * Schema type
 *
 * @return void
 */
function storefront_html_tag_schema() {
	_deprecated_function( 'storefront_html_tag_schema', '2.0.2' );

	$schema = 'http://schema.org/';
	$type   = 'WebPage';

	if ( is_singular( 'post' ) ) {
		$type = 'Article';
	} elseif ( is_author() ) {
		$type = 'ProfilePage';
	} elseif ( is_search() ) {
		$type 	= 'SearchResultsPage';
	}

	echo 'itemscope="itemscope" itemtype="' . esc_attr( $schema ) . esc_attr( $type ) . '"';
}

/**
 * Sanitizes the layout setting
 *
 * Ensures only array keys matching the original settings specified in add_control() are valid
 *
 * @param array $input the layout options.
 * @since 1.0.3
 */
function storefront_sanitize_layout( $input ) {
	_deprecated_function( 'storefront_sanitize_layout', '2.0', 'storefront_sanitize_choices' );

	$valid = array(
		'right' => 'Right',
		'left'  => 'Left',
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	} else {
		return '';
	}
}

/**
 * Storefront Sanitize Hex Color
 *
 * @param string $color The color as a hex.
 * @todo remove in 2.1.
 */
function storefront_sanitize_hex_color( $color ) {
	_deprecated_function( 'storefront_sanitize_hex_color', '2.0', 'sanitize_hex_color' );

	if ( '' === $color ) {
		return '';
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return $color;
	}

	return null;
}

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 * @todo remove in 2.1.
 */
function storefront_categorized_blog() {
	_deprecated_function( 'storefront_categorized_blog', '2.0' );

	if ( false === ( $all_the_cool_cats = get_transient( 'storefront_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,
			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'storefront_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so storefront_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so storefront_categorized_blog should return false.
		return false;
	}
}





////////////////////////
//custom functions///
//////////////////

include_once  WP_PLUGIN_DIR.'/gallery-video/includes/gallery-video-video-functions.php';
/**
 * Class Add_Endpoint
 * add a endpoint to my account page
 */
class  Add_Endpoint{

    protected $_endpoint;
    protected $_title;
    protected $_file;

    public function __construct($point,$title,$file){
        $this->_endpoint = $point;
        $this->_title = $title;
        $this->_file = $file;
        add_action( 'init',array($this,'add_endpoints' ));
        add_filter( 'query_vars',array($this, 'add_query_vars') , 0 );
        add_filter( 'the_title',  array($this,'endpoint_title' ) );
        add_filter( 'woocommerce_account_menu_items',array($this, 'new_menu_items' ));
        add_action( 'woocommerce_account_'.$this->_endpoint.'_endpoint',  array($this, 'endpoint_content' ));
    }

    public function add_endpoints() {
        add_rewrite_endpoint( $this->_endpoint, EP_ROOT | EP_PAGES );
    }

    public function add_query_vars( $vars ) {
        $vars[] = $this->_endpoint;
        return $vars;
    }

    public function endpoint_title( $title ) {
        global $wp_query;
        $is_endpoint = isset( $wp_query->query_vars[$this->_endpoint] );
        if ( $is_endpoint && ! is_admin() && is_main_query() && in_the_loop() && is_account_page() ) {
            // New page title.
            $title = __( $this->_title, 'woocommerce' );
            remove_filter( 'the_title', 'endpoint_title'  );
        }
        return $title;
    }
    function new_menu_items( $items ) {
        $logout = $items['customer-logout'];
        unset( $items['customer-logout'] );
        $items[ $this->_endpoint ] = __( $this->_title, 'woocommerce' );
        $items['customer-logout'] = $logout;
        return $items;
    }
    public function endpoint_content() {
        wc_get_template( 'myaccount/'.$this->_file );
    }

    public function install() {
        flush_rewrite_rules();
    }
}
new Add_Endpoint('profile','My Profile','my-account-profile.php');
new Add_Endpoint('inventions','Video','my-account-inventions.php');
new Add_Endpoint('tutorial','Tutorial','my-account-tutorial.php');
new Add_Endpoint('contest','Contest','my-account-contest.php');
new Add_Endpoint('customer-login','Login','form-login.php');
new Add_Endpoint('customer-register','Register','form-register.php');
new Add_Endpoint('contest-view','View Contest','my-account-view-contest.php');
new Add_Endpoint('add-contest','Add Contest','my-account-add-contest.php');
new Add_Endpoint('add-tutorial','Add Tutorial','my-account-add-tutorial.php');
new Add_Endpoint('tutorial-view','View Tutorial','my-account-view-tutorial.php');
register_activation_hook( __FILE__,array('Add_Endpoint','install' ));


function get_video_categories(){
    global $wpdb;
    $table_name = $wpdb->prefix . 'huge_it_videogallery_galleries';
    $sql = "SELECT id,name FROM {$table_name} WHERE video_type ='official' order by id ASC ";
    $result = $wpdb->get_results($sql);
    return $result;
}

function insert_customer_video($data){
    global $wpdb,$current_user;
    if($data['image_url'] && $data['name'] && $data['image_url']) {
        $table_name = $wpdb->prefix . 'huge_it_videogallery_videos';
        $urlimage = gallery_video_get_video_id_from_url($data['image_url']);
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
        $thumb_url = '';
        $link_url = '';
        if($urlimage[1] == 'youku'){
            $thumb_url = get_youku_image_from_url($data['image_url']);
            $link_url  =  '//player.youku.com/embed/'.$urlimage[0];
        }else if($urlimage[1] == 'youtube'){
            $thumb_url = '//img.youtube.com/vi/' . $urlimage[0] . '/mqdefault.jpg';
            $link_url = '//www.youtube.com/embed/'.$urlimage[0];
        }else if($urlimage[1] == 'vimeo'){
            $hash = unserialize(wp_remote_fopen($protocol . "vimeo.com/api/v2/video/" . $urlimage[0] . ".php"));
            $thumb_url = $hash[0]['thumbnail_large'];
            $link_url  =  '//player.vimeo.com/video/'.$urlimage[0];
        }
        $thumb_url = isset($data['thumb_url']) ? $data['thumb_url'] : $thumb_url;
        $user_id = $current_user->ID;
        if($data['id'] == 0){
            $wpdb->insert(
                $table_name,
                array(
                    'name' => $data['name'],
                    'videogallery_id' => $data['videogallery_id'],
                    'description' => $data['description'],
                    'image_url' => $data['image_url'],
                    'sl_url' => $link_url,
                    'sl_type' => 'video',
                    'link_target' => 'on',
                    'ordering' => 0,
                    'published' => 2,
                    'show_info' => 'on',
                    'thumb_url' => $thumb_url,
                    'published_in_sl_width' => 1
                )
            );
            $video_id = $wpdb->insert_id;
            $description = "<div class='video-player'>
                                <iframe src='".$link_url."' width='100%' height='100%' frameborder='0' allowfullscreen='allowfullscreen'></iframe>
                             </div>
                             <div class='video-description'>".$data['description']."</div>";
            $postData = array(
                'post_author'    => $user_id,
                'post_content'   => $description,
                'post_title'     => $data['name'],
                'post_status'    => 'publish',
                'post_type'      => 'video',
            );
            $post_id = wp_insert_post($postData);
            update_post_meta($post_id, 'post_video', $video_id);
            update_post_meta($post_id, 'views', 0);
            update_post_meta($post_id, 'post_video_cat', $data['videogallery_id']);
            update_user_meta($user_id, 'user_video_'.$video_id, $video_id);
            update_post_meta($post_id, 'video_secrecy', $data['type']);
            return $wpdb->insert_id;
        }else{
            $wpdb->update(
                $table_name,
                array(
                    'name' => $data['name'],
                    'videogallery_id' => $data['videogallery_id'],
                    'description' => $data['description'],
                    'image_url' => $data['image_url'],
                    'sl_url' => $link_url,
                    'sl_type' => 'video',
                    'link_target' => 'on',
                    'ordering' => 0,
                    'published' => 2,
                    'show_info' => 'on',
                    'thumb_url' => $thumb_url,
                    'published_in_sl_width' => 1
                ),
                array('id'=>$data['id'])
            );
            $description = "<div class='video-player'>
                                <iframe src='".$link_url."' width='100%' height='100%' frameborder='0' allowfullscreen='allowfullscreen'></iframe>
                             </div>
                             <div class='video-description'>".$data['description']."</div>";

            $video_id = $data['id'];
            $post_id = get_video_relate_post_id($data['id']);
            $my_post['ID'] = $post_id;
            $my_post['post_content'] = $description;
            wp_update_post($my_post);
            update_post_meta($post_id, 'post_video_cat',$data['videogallery_id']);
            update_user_meta($user_id, 'video_secrecy_' . $video_id, $data['type']);
            return $post_id;
        }
    }
}

function get_video_gallery_name_by_id($video_gallery_id){
    global $wpdb;
    $query = $wpdb->prepare("SELECT name FROM wp_huge_it_videogallery_galleries WHERE id=%d",$video_gallery_id);
    $result = $wpdb->get_results($query);
    return $result[0]->name;
}

function get_video_relate_post_id($video_id){
    global $wpdb;
    $meta_key = 'post_video';
    $query = $wpdb->prepare("SELECT post_id FROM wp_postmeta WHERE meta_key='{$meta_key}' AND meta_value=%d",$video_id);
    $result = $wpdb->get_results($query);
    $post_id = $result[0]->post_id;
    return $post_id;
}

function add_video_views($video_id){
    $post_id = get_video_relate_post_id($video_id);
    $views = get_post_meta($post_id,'views');
    update_post_meta($post_id,'views',($views+1));
    $result = get_post_meta($post_id,'views');
    return $result;
}

function get_video_views($video_id){
    $post_id = get_video_relate_post_id($video_id);
    $result = get_post_meta($post_id,'views');
    return $result;
}

function get_user_videos($user_id,$per_page = 0){
    global  $wpdb;
    $page = $_GET['pag'] ? $_GET['pag'] : 1;
    $pagesize = $per_page ? $per_page : 9;
    $start =  $start = ($page - 1)* $pagesize;
    $table = $wpdb->prefix. 'usermeta';
    $video_table = $wpdb->prefix . 'huge_it_videogallery_videos';
    $video_query = "SELECT * FROM {$video_table} WHERE id IN (SELECT meta_value FROM {$table} WHERE user_id={$user_id} AND meta_key LIKE 'user_video_%') ORDER BY id DESC LIMIT ". $start.','.$pagesize ;
    $videos = $wpdb->get_results($video_query);
    return $videos;
}


function get_users_public_video_ids(){
    global  $wpdb;
    $public_videos  =null;
    $videos = get_all_videos();
    foreach ($videos as $row ){
        $post_id = get_video_relate_post_id($row->id);
        $query = "SELECT meta_value FROM wp_postmeta WHERE meta_value = 'public' AND meta_key ='video_secrecy' AND post_id=".$post_id;
        $col = $wpdb->get_results($query);
        if(count($col)){
            $public_videos[] = $row;
        }
    }
    return $public_videos;
}

function get_the_video_user($video_id){
    global  $wpdb;
    $user_meta = $wpdb->prefix. 'usermeta';
    $query = $wpdb->prepare("SELECT user_id FROM {$user_meta} WHERE meta_key = 'user_video_{$video_id}' AND meta_value=%d",$video_id);
    $resulte= $wpdb->get_results($query);
    $user_id = $resulte[0]->user_id;
    return get_user_by('ID',$user_id);
}

function get_category_by_videoid($video_id){
    global  $wpdb;
    $video_table = $wpdb->prefix. 'huge_it_videogallery_videos';
    $gallery_table = $wpdb->prefix . 'huge_it_videogallery_galleries';
    $query = $wpdb->prepare("SELECT * FROM {$gallery_table} WHERE id IN (SELECT videogallery_id FROM {$video_table} WHERE id=%d) ORDER BY id DESC",$video_id);
    $category = $wpdb->get_results($query);
    return $category[0];
}

function get_all_videos(){
    global $wpdb;
    $query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videogallery_videos WHERE published = %d ",1);
    $all_videos = $wpdb->get_results($query);
    return $all_videos;
}

function check_video_is_published($video_id){
    $video = get_video_by_id($video_id);
    if($video->published != 1){
        return false;
    }else{
        return true;
    }
}

/**
 * @param $video_id
 * @param bool $public | public video
 * @return mixed
 */
function get_video_by_id($video_id){
    global $wpdb;
    $query = $wpdb->prepare("SELECT * FROM ".$wpdb->prefix."huge_it_videogallery_videos WHERE  id=%d",$video_id);
    $video = $wpdb->get_results($query);
    return $video[0];
}


function get_popular_videos($number = 20){
    global $wpdb;
    $limit = null;
    if($number){
        $limit = ' LIMIT 0,'.$number;
    }
    $as = ',(wp_postmeta.meta_value+0) as views';
    $joinSelect = "SELECT wp_postmeta.post_id FROM wp_postmeta WHERE wp_postmeta.meta_key = 'post_video' AND wp_postmeta.meta_value = wp_huge_it_videogallery_videos.id ";
    $join  = " JOIN wp_postmeta ON wp_postmeta.meta_key = 'views' AND wp_postmeta.post_id IN ($joinSelect) ";
    $order = ' ORDER BY views DESC';
    $table  = $wpdb->prefix . 'huge_it_videogallery_galleries';
    $query  = $wpdb->prepare("SELECT *{$as} FROM wp_huge_it_videogallery_videos {$join} WHERE videogallery_id IN (SELECT id FROM {$table} WHERE video_type = '%s') {$order} {$limit}",'customer');
    $popular = $wpdb->get_results($query);
    shuffle($popular);
    return $popular;
}
function get_the_most_views_and_like_tutorials($showNum){
    global  $wpdp,$cat,$cats;
    foreach (get_tutorial_category() as $_cat){
        $cats[] = $_cat->term_id;
    }
    $cat = implode(',',$cats);
    $views  = array(
        array(
            'taxonomy'  =>'tutorial-category',
            'field'     => 'id',
            'terms'     => array($cat),
        ),
        'orderby'           => 'meta_value_num',
        'meta_key'          => 'views',
        'order'             => 'desc',
        'post_type'         => 'tutorial',
        'post_status'       => 'publish',
        'showposts'         =>  20,
    );
    $views_tutorial = new WP_Query($views);
    $views_t = $views_tutorial->get_posts();
    $likes  = array(
        array(
            'taxonomy'  =>'tutorial',
            'field'     => 'id',
            'terms'     => array($cat),
        ),
        'orderby'           => 'meta_value_num',
        'meta_key'          => '_liked',
        'order'             => 'desc',
        'post_type'         => 'tutorial',
        'post_status'       => 'publish',
        'showposts'         =>  20,
    );
    $liked_tutorial = new WP_Query($likes);
    $likes_t = $liked_tutorial->get_posts();
    $array = array_merge($views_t,$likes_t);
    $result = null;
    foreach ($array as $_post){
        $result[$_post->ID] = $_post;
    }
    shuffle($result);
    return array_slice($result,0,$showNum,true);
}


/**
 * Whether the user video is open | public or private
 * @param $_videoId
 * @return string
 */
function get_user_video_secrecy($_videoId){
    global  $current_user;
    $secrecy = get_user_meta($current_user->ID,'video_secrecy_'.$_videoId,true);
    return $secrecy;
}

function get_user_tutorials(){
    global $wpdb,$current_user;
    $user_id = $current_user->ID;
    $paged = $_GET['pag'] ? $_GET['pag'] : 1;
    $paged = $paged < 1 ? 1 : $paged;
    $per_siza = 10;
    $total  = array(
        'orderby'           => 'date',
        'author'            => $user_id,
        'order'             => 'desc',
        'post_type'         => 'tutorial',
        'post_status'       => array('publish','pending'),
        'showposts'         =>  -1,
    );
    $total_Post  = new WP_Query($total);
    $totals = $total_Post->post_count;
    $paged  = $paged > ceil($totals/$per_siza) ? ceil($totals/$per_siza) : $paged;
    $defaultsAll  = array(
        'orderby'           => 'date',
        'author'            => $user_id,
        'order'             => 'desc',
        'post_type'         => 'tutorial',
        'post_status'       => array('publish','pending'),
        'showposts'         =>  $per_siza,
        'paged'             =>  $paged,
    );
    $user_tutorial = new WP_Query($defaultsAll);
    $data['post'] = $user_tutorial->posts;
    $data['page'] = custom_page($totals,$paged,$per_siza,false);
    return $data;
}

function get_tutorial_category($id = null){
    global  $wpdb;
    $category = array();
    if($id){
        $category = get_term_by('id',$id,'tutorial-category');
        return $category->name;
    }else{
        $query = $wpdb->prepare("SELECT a.term_id,(b.meta_value+0) as sort FROM wp_term_taxonomy AS a,wp_termmeta AS b WHERE b.term_id=a.term_id AND b.meta_key='%s' AND a.taxonomy='%s' ORDER BY sort DESC",'sort_order','tutorial-category');
        foreach ($wpdb->get_results($query) as $_term){
            if(get_term_meta($_term->term_id,'display_front',true)){
                $category[$_term->term_id] = get_term_by('id',$_term->term_id,'tutorial-category');
            }
        }
        return $category;
    }
}

function insert_customer_tutorial($data){
    global  $current_user;
    $post_data = array(
        'post_author'    => $current_user->ID,
        'post_content'   => $data['post_content'],
        'post_title'     => $data['post_title'],
        'post_status'    => 'pending',
        'post_type'      => 'tutorial',
    );

    $post_id = wp_insert_post($post_data);

    if ( ! empty( $_POST['post_category'] )) {
        $cat_id = $_POST['post_category'];
        inserT_post_category($post_id,$cat_id);
    }
    if( ! empty( $_FILES ) ) {
        foreach( $_FILES as $index=> $file ) {
            if( is_array( $file ) ) {
                if($index == 'attachment'):
                    $attachment_id = upload_user_file( $file );
                    update_post_meta($post_id,'_thumbnail_id',$attachment_id);
                else:
                    $attachment_id = upload_user_file( $file );
                    update_post_meta($post_id,'_enclosure_id',$attachment_id);
                    update_post_meta($post_id,'_enclosure_description',$data['enclosure_des']);
                endif;
            }
        }
    }
    update_post_meta($post_id,'tutorial_description',$data['post_description']);
    return $post_id;
}

global  $current_video_cat;
$current_video_cat = isset($_COOKIE['videogallery_id']) && $_COOKIE['videogallery_id'] ? $_COOKIE['videogallery_id'] : 'all';

function get_videogallery_bellow_videos($videogallery_id){
    global  $wpdb;
    $like = null;
    setcookie('videogallery_id',$videogallery_id,0,'/');
    if(isset($_GET['q'])) {
        $keyword = $_GET['q'];
        $like = ' AND name LIKE "%'.$keyword.'%" ';
    }

    $order = " ORDER BY create_date DESC";
    $views = null;
    $join = null;
    if(isset($_GET['order'])){
        if($_GET['order'] != 'views'){

            $order = ' ORDER BY '.$_GET['order'].' DESC';
        }else{
            $join = ',(wp_postmeta.meta_value+0) as views';
            $joinSelect = "SELECT wp_postmeta.post_id FROM wp_postmeta WHERE wp_postmeta.meta_key = 'post_video' AND wp_postmeta.meta_value = wp_huge_it_videogallery_videos.id ";
            $views  = " JOIN wp_postmeta ON wp_postmeta.meta_key = 'views' AND wp_postmeta.post_id IN ($joinSelect) ";
            $order = ' ORDER BY views DESC';
        }
    }

    $paged = 1;
    if(isset($_GET['pag']) && $_GET['pag']){
        $paged = $_GET['pag'] > 1 ? $_GET['pag'] : 1;
    }

    $persize = 12;
    $start = ($paged-1) * $persize;

    $table  = $wpdb->prefix . 'huge_it_videogallery_videos';

    if(is_numeric($videogallery_id) && $videogallery_id){
        $query = "SELECT *{$join} FROM ".$table. $views . " WHERE videogallery_id = {$videogallery_id} AND published =1 {$like} {$order} LIMIT {$start},{$persize}";
        $videos = $wpdb->get_results($query);
        $all_query = $query = "SELECT * FROM ".$table." WHERE videogallery_id = {$videogallery_id} AND published =1 {$like} ";
        $all_videos = $wpdb->get_results($all_query);
    }else{
        $all_query = "SELECT * FROM ".$table." WHERE  published =1 ".$like." ORDER BY create_date DESC";
        $all_re_videos = $wpdb->get_results($all_query);
        $all_unique = array();
        foreach ($all_re_videos as $key => $row){
            $videourl = gallery_video_get_video_id_from_url($row->image_url);
            $all_unique[$videourl[0]] = $row;
        }
        $all_videos = $all_unique;

        $query = "SELECT *{$join}  FROM ".$table . $views . " WHERE published =1 ". $like . $order . " LIMIT $start,$persize";
        $re_videos = $wpdb->get_results($query);
        $unique = array();
        foreach ($re_videos as $key => $row){
            $videourl = gallery_video_get_video_id_from_url($row->image_url);
            $unique[$videourl[0]] = $row;
        }
        if(count($unique) % 3 == 0){
            $videos = $unique;
        }else{
            $offset = count($unique) - (count($unique)%3);
            $videos = array_slice($unique,0,$offset,true);
        }
    }
    $totals = count($all_videos);
    $result['page'] = custom_page($totals,$paged,$persize);
    $result['count'] = $totals;
    $result['videos'] = $videos;
    return $result;

}

/**
 * get video gallery object by id
 * @param $id
 * @return array|null|object
 */
function get_video_gallery_by_id($id){
    global  $wpdb;
    $query = $wpdb->prepare("SELECT * FROM wp_huge_it_videogallery_galleries WHERE id=%d",$id);
    $gallery = $wpdb->get_row($query);
    return $gallery;
}

/**
 * @return array
 */
function get_have_views_videos(){
    global  $wpdb;
    $table = 'wp_postmeta';
    $query = $wpdb->prepare("SELECT post_id FROM ".$table." WHERE meta_key='%s' ORDER BY meta_value DESC",'views');
    $result = $wpdb->get_results($query);
    foreach ($result as $row){
        $video_ids[] = get_post_meta($row->post_id,'post_video',true);
    }
    return $video_ids;
}

function get_video_list_html($row,$show_additional=true){

    $post_id = get_video_relate_post_id($row->id);
    $post_link = get_permalink($post_id);
    $liked = $val = get_post_meta($post_id, '_liked', true) ? get_post_meta($post_id,'_liked',true) : 0;
    $views = get_post_meta($post_id,'views',true) ? get_post_meta($post_id,'views',true) : 0;
    $user = get_the_video_user($row->id);
    $gallery_videoName = get_category_by_videoid($row->id)->name;
    $create_date = $row->create_date;
    $second1 = strtotime($create_date);
    $second2 = strtotime(date('Y-m-d h:i:s'));
    if ($second1 < $second2) {
        $tmp = $second2;
        $second2 = $second1;
        $second1 = $tmp;
    }
    $diff_date = ($second1 - $second2) / 86400;
    $duration = '';
    ?>
        <div class="video-item-cont video-element_ video-element"  data-id="<?php echo $row->id?>" tabindex="0" data-symbol="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
             data-category="alkaline-earth">
            <div class="video-img image-block_">
                <?php
                $videourl = gallery_video_get_video_id_from_url($row->image_url);
                if ($videourl[1] == 'youtube') {
                    if (empty($row->thumb_url)) {
                        $thumb_pic = '//img.youtube.com/vi/' . $videourl[0] . '/hqdefault.jpg';
                    } else {
                        $thumb_pic = $row->thumb_url;
                    }
                    //$duration = get_youtube_vimeo_duration_from_url($row->image_url);
                    ?>
                    <a class="responsive-img vyoutube huge_it_videogallery_item group"
                       href="javascript:void(0)" data-link="//www.youtube.com/embed/<?php echo $videourl[0]; ?>"
                       title="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                       data-id="<?php echo esc_attr($row->id); ?>"
                       ajax-url = '<?php echo esc_url(admin_url('admin-ajax.php'))?>'
                       style="background-image: url('<?php echo esc_url($thumb_pic)?>')"
                       >
                        <div class="play-icon video-play-icon"></div>
                        <div class="most-popular-bg"></div>
                    </a>
                    <?php
                } elseif ($videourl[1] == 'youku'){
                    if (empty($row->thumb_url)) {
                        $thumb_pic = get_youku_image_from_url($row->image_url);
                    } else {
                        $thumb_pic = $row->thumb_url;
                    }
                    //$duration = get_youku_duration_from_url($row->image_url)
                    ?>
                    <a class="responsive-img vyoutube huge_it_videogallery_item group"
                       href="javascript:void(0)" data-link="//player.youku.com/embed/<?php echo $videourl[0]; ?>"
                       title="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                       data-id="<?php echo esc_attr($row->id); ?>"
                       ajax-url = '<?php echo esc_url(admin_url('admin-ajax.php'))?>'
                       style="background-image: url('<?php echo esc_url($thumb_pic)?>')"
                        >
                        <div class="play-icon video-play-icon"></div>
                        <div class="most-popular-bg"></div>
                    </a>
                    <?php
                } else {
                    $protocol = stripos( $_SERVER['SERVER_PROTOCOL'], 'https' ) === true ? 'https://' : 'http://';
                    $hash = @unserialize(wp_remote_fopen($protocol . "vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                    if (empty($row->thumb_url)) {
                        $imgsrc = $hash[0]['thumbnail_large'];
                    } else {
                        $imgsrc = $row->thumb_url;
                    }
                    //$duration = get_youtube_vimeo_duration_from_url($row->image_url);
                    ?>
                    <a class="responsive-img vvimeo huge_it_videogallery_item group"
                       href="javascript:void(0);" data-link="//player.vimeo.com/video/<?php echo $videourl[0]?>"
                       title="<?php echo str_replace('__5_5_5__', '%', $row->name); ?>"
                       data-id="<?php echo esc_attr($row->id); ?>"
                       ajax-url = '<?php echo esc_url(admin_url('admin-ajax.php'))?>' style="background-image: url('<?php echo esc_attr($imgsrc); ?>')">
                       <div class="play-icon video-play-icon"></div>
                        <div class="most-popular-bg"></div>
                    </a>
                    <?php
                }
                ?>
                <?php if($diff_date < 30):?>
                    <div class="video-is-new">
                        <label for="new"><?php _e('New','storefront')?></label>
                    </div>
                <?php endif;?>
                <div class="video-times">
                    <span><?php echo $duration?></span>
                </div>
            </div>
            <div class="video-footer">
                <div class="video-title"><a href="<?php echo esc_url($post_link )?>"><?php _e(apply_filters(' ',$row->name))?></a></div>
                <?php if($show_additional):?>
                <div class="clearbox">
                    <div class="item-cat-author text-uppercase">
                        <strong class="pblue"><?php echo $gallery_videoName?></strong>&nbsp;&nbsp;|&nbsp;&nbsp;BY <span class="pblue"><?php echo $user->user_login?></span>
                    </div>
                    <div class="video-kaopin">
                        <span class="video-parise"><?php echo number_format($liked);?></span>
                        <span class="views"><?php echo number_format($views);?></span>
                    </div>
                </div>
                <?php endif;?>
            </div>

        </div>
      <?php
}
function share_to_social($object,$social ,$type='post'){
    if($type == 'post'){
        $url = get_permalink($object->ID);
        $image = get_post_thumbnail_url($object->ID);
        $description = mb_strimwidth(strip_tags(apply_filters('the_content', $object->post_content)), 0, 100,"……");
        $title = $object->post_title;
        if($object->post_type == 'video'){
            $video_id = get_post_meta($object->ID,'post_video',true);
            $video = get_video_by_id($video_id);
            $videourl = gallery_video_get_video_id_from_url($video->image_url);
            if($videourl[1] == 'youtube'){
                if (empty($video->thumb_url)) {
                    $image = 'https://img.youtube.com/vi/' . $videourl[0] . '/hqdefault.jpg';
                } else {
                    $image = $video->thumb_url;
                }
            }elseif ($videourl[1] == 'youku') {
                if (empty($video->thumb_url)) {
                    $image = get_youku_image_from_url($video->image_url);
                } else {
                    $image = $video->thumb_url;
                }
            }elseif ($videourl[1] == 'vimeo') {
                $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
                $hash = @unserialize(wp_remote_fopen($protocol . "vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                if (empty($video->thumb_url)) {
                    $image = $hash[0]['thumbnail_large'];
                } else {
                    $image = $video->thumb_url;
                }
            }
            $description = $video->description;
            $title = $video->name;
        }
    }else if($type == 'contest'){
        $url = home_url('contest-list/'.$object->slug).'.html';
        $image = z_taxonomy_image_url( $object->term_id, 'thumbnail', TRUE );
        $description = mb_strimwidth(strip_tags(apply_filters('the_content', $object->description)), 0, 100,"……");
        $title = $object->name;
    }
    switch ($social){
        case 'facebook':
            $share_link = "http://www.facebook.com/sharer.php?title=".urlencode($title)."&description=".urlencode($description)."&u=".urlencode($url)."&picture=".urlencode($image);
            return $share_link;
            break;
        case 'twitter':
            $share_link = "https://twitter.com/share?text=".urlencode($title)."&url=".urlencode($url).'&image_src='.urlencode($image);
            return $share_link;
            break;
        case 'google-plus':
            $share_link = "https://plus.google.com/share?url=".urlencode($url);
            return $share_link;
            break;
        case 'pinterest':
            $share_link = "https://pinterest.com/pin/create/button/?url=".urlencode($url)."&media=". urlencode($image)."&description=".urlencode($description);
            return $share_link;
            break;
    }
}
function get_tutorial_list($_post,$thumbnail = 'thumbnail'){
    $liked = get_post_meta($_post->ID,'_liked',true) ? get_post_meta($_post->ID,'_liked',true) : 0;
    $views = get_post_meta($_post->ID,'views',true) ? get_post_meta($_post->ID,'views',true) : 0;
?>
      <li class="cols3-ul-item">
          <div class="item-box video-item-cont">
              <div class="item-img video-img">
                  <a style="background-image:url(<?php echo get_post_thumbnail_url($_post->ID,$thumbnail)?>);"  href="<?php echo esc_url(get_permalink($_post->ID))?>" class="item-url">
                  </a>
                  <div class="video-img-hover-bg">
                    <a href="<?php echo share_to_social($_post,'facebook')?>" target="_blank"><span class="share-facebook"></span></a>
                    <a href="<?php echo share_to_social($_post,'twitter')?>" target="_blank"><span class="share-twitter"></span></a>
                  </div>
              </div>
              <div class="video-footer">
                  <div class="item-title video-title">
                      <a href="<?php echo esc_url(get_permalink($_post->ID))?>"><?php echo __($_post->post_title);?></a>
                  </div>
                  <div class="item-cat-author pbold">
                      <?php
                        $user = get_user_by('ID',$_post->post_author);
                        $cateString = get_post_category_name($_post->ID);
                  ?>
                  <?php echo '<strong class="pblue">'.rtrim($cateString,',').'</strong>' . '<span class="p999">&nbsp/&nbsp;By</span> '.$user->user_login;?>
                    <div class="fr below-pad-show">
                          <span class="video-parise"><?php echo number_format($liked);?></span>
                          <span class="views"><?php echo number_format($views);?></span>
                      </div>
                  </div>
                  <?php if(get_post_meta($_post->ID,'tutorial_description',true)):?>
                      <div class="item-description"><?php echo esc_html(get_post_meta($_post->ID,'tutorial_description',true))?></div>
                  <?php else:?>
                   <div class="item-description"><?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $_post->post_content)), 0, 100,"……");?></div>
                    <?php endif;?>
                    <div class="item-addtional">
                      <a class="read-more button emptybutton p12" href="<?php echo esc_url(get_permalink($_post->ID))?>"><?php echo __('READ MORE')?></a>
                      <div class="fr">
                          <span class="video-parise"><?php echo number_format($liked);?></span>
                          <span class="views"><?php echo number_format($views);?></span>
                      </div>
                  </div>
              </div>
          </div>
      </li>
<?php
}
function get_tutorial_list_slider_sub($_post){
    $liked = get_post_meta($_post->ID,'_liked',true) ? get_post_meta($_post->ID,'_liked',true) : 0;
    $views = get_post_meta($_post->ID,'views',true) ? get_post_meta($_post->ID,'views',true) : 0;
?>
      <li class="cols3-ul-item">
          <div class="item-box video-item-cont">
                <div class="clearbox sub-slider-left">
                  <div class="item-img video-img fl">
                    <a style="background-image:url(<?php echo get_post_thumbnail_url($_post->ID)?>);"  href="<?php echo esc_url(get_permalink($_post->ID))?>">
                    </a>
                  </div>
                  <div class="fr">
                    <div class="item-title video-title">
                          <a href="<?php echo esc_url(get_permalink($_post->ID))?>"><?php echo __($_post->post_title);?></a>
                      </div>
                      <div class="item-cat-author pbold">
                          <?php
                            $user = get_user_by('ID',$_post->post_author);
                            $cateString = get_post_category_name($_post->ID);
                      ?>
                      <?php echo '<strong class="pblue">'.rtrim($cateString,',').'</strong>' . '<span class="p999">&nbsp;/&nbsp;By </span>'.$user->user_login;?>
                      </div>
                      <div class="video-footer">
                            <div class="item-addtional">
                              <div class="">
                                  <span class="video-parise"><?php echo number_format($liked);?></span>
                                  <span class="views"><?php echo number_format($views);?></span>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              
          </div>
      </li>
<?php
}
function get_alsolike_tutorial_list($_post){
    $liked = get_post_meta($_post->ID,'_liked',true) ? get_post_meta($_post->ID,'_liked',true) : 0;
    $views = get_post_meta($_post->ID,'views',true) ? get_post_meta($_post->ID,'views',true) : 0;
?>
      <li class="cols3-ul-item">
          <div class="item-box video-item-cont">
              <div class="item-img video-img">
                  <a class="item-url responsive-img" style="background-image:url(<?php echo get_post_thumbnail_url($_post->ID)?>);"  href="<?php echo esc_url(get_permalink($_post->ID))?>">
                  </a>
                  <div class="video-img-hover-bg">
                    <a href="<?php echo share_to_social($_post,'facebook')?>" target="_blank"><span class="share-facebook"></span></a>
                    <a href="<?php echo share_to_social($_post,'twitter')?>" target="_blank"><span class="share-twitter"></span></a>
                  </div>
              </div>
              <div class="video-footer">
                  <div class="item-title video-title">
                      <a class="p18 pbold" href="<?php echo esc_url(get_permalink($_post->ID))?>"><?php echo __($_post->post_title);?></a>
                  </div>
                  <div class="item-cat-author pbold">
                      <?php
                        $user = get_user_by('ID',$_post->post_author);
                        $cateString = get_post_category_name($_post->ID);
                        ?>
                    <?php echo '<strong class="pblue">'.rtrim($cateString,',').'</strong>' . '&nbsp;/&nbsp;By <span class="pblue">'.$user->user_login.'</span>';?>
                  </div>
                  <?php if(get_post_meta($_post->ID,'tutorial_description',true)):?>
                      <div class="item-description"><?php echo esc_html(get_post_meta($_post->ID,'tutorial_description',true))?></div>
                  <?php else:?>
                   <div class="item-description"><?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $_post->post_content)), 0, 100,"……");?></div>
                  <?php endif;?>
                  <div class="item-addtional clearbox">
                    <a class="read-more button emptybutton p12" href="<?php echo esc_url(get_permalink($_post->ID))?>"><?php echo __('READ MORE')?></a>
                      <div class="fr">
                          <span class="video-parise"><?php echo number_format($liked);?></span>
                          <span class="views"><?php echo number_format($views);?></span>
                      </div>
                  </div>
              </div>
          </div>
      </li>
<?php
}