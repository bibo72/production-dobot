<?php
/*
Plugin Name: Test List Table Example
*/

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

require_once("tax-meta-class/Tax-meta-class.php");

add_action( 'init', 'news_events_post_types' );
function news_events_post_types() {
    $labels = array(
        'name'                => __( 'News & Events', 'storefront' ),
        'singular_name'       => __( 'News & Events', 'storefront' ),
        'add_new'             => __( 'Add New', 'storefront' ),
        'add_new_item'        => __( 'Add New News & Events', 'storefront' ),
        'edit_item'           => __( 'Edit News & Events','storefront'),
        'new_item'            => __( 'New News & Events', 'storefront'),
        'all_items'           => __( 'All News & Events', 'storefront' ),
        'view_item'           => __( 'View News & Events', 'storefront'),
        'search_items'        => __( 'Search News & Events','storefront'),
        'not_found'           => __( 'No events found', 'storefront' ),
        'not_found_in_trash'  => __( 'No events found in Trash','storefront' ),
        'menu_name'           => __( 'News & Events', 'storefront'),
    );

    $supports = array( 'title','custom-fields','editor','comments','page-attributes','thumbnail');

    $slug = get_theme_mod( 'event_permalink' );
    $slug = ( empty( $slug ) ) ? 'event' : $slug;

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => $slug ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => $supports,
    );
    register_post_type( 'event', $args );
}

add_filter('manage_event_posts_columns', 'bs_event_table_head');
function bs_event_table_head( $defaults ) {
    $defaults['author'] = __('Author','default');
    return $defaults;
}


add_action( 'init', 'video_post_types' );
function video_post_types() {
    $labels = array(
        'name'                => __( 'Video Posts', 'storefront' ),
        'singular_name'       => __( 'Video Posts', 'storefront' ),
        'add_new'             => __( 'Add New', 'storefront' ),
        'add_new_item'        => __( 'Add New Video Posts', 'storefront' ),
        'edit_item'           => __( 'Edit Video Posts','storefront'),
        'new_item'            => __( 'New Video Posts', 'storefront'),
        'all_items'           => __( 'All Video Posts', 'storefront' ),
        'view_item'           => __( 'View Video Posts', 'storefront'),
        'search_items'        => __( 'Search Video Posts','storefront'),
        'not_found'           => __( 'No events found', 'storefront' ),
        'not_found_in_trash'  => __( 'No events found in Trash','storefront' ),
        'menu_name'           => __( 'Video Posts', 'storefront'),
    );

    $supports = array( 'title','editor','comments');

    $slug = get_theme_mod( 'video_permalink' );
    $slug = ( empty( $slug ) ) ? 'video' : $slug;

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => $slug ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => $supports,
    );

    register_post_type( 'video', $args );

}


add_action( 'manage_video_posts_custom_column' , 'custom_video_column', 10, 2 );
add_filter('manage_video_posts_columns', 'set_custom_edit_video_columns');

function set_custom_edit_video_columns( $defaults ) {
    $defaults['author'] = __('Author','default');
    $defaults['video_cat'] = __('Categories','default');
    $defaults['liked'] = __('Likes','default');
    return $defaults;
}

function custom_video_column( $column, $post_id ) {
    switch ( $column ) {
        case 'liked' :
            $terms = get_post_meta( $post_id , '_liked' , true);
            $liked = $terms ? $terms : 0;
            echo number_format($liked).' liked';
            break;
        case 'video_cat' :
            $terms = get_post_meta( $post_id , 'post_video_cat' , true);
            $video_id = get_post_meta($post_id,'post_video',true);
            $video = get_video_by_id($video_id);
            $video_gallery = get_video_cat_name_by_id($video->videogallery_id);
            $name = get_video_gallery_name_by_id($terms) ? get_video_gallery_name_by_id($terms) : __($video_gallery,'storefront');
            echo __($name,'storefront');
            break;
    }
}



////////////////  tutorial  /////////////
add_action( 'init', 'tutorial_post_types' );
function tutorial_post_types() {
    $labels = array(
        'name'                => __( 'Tutorial', 'storefront' ),
        'singular_name'       => __( 'Tutorial', 'storefront' ),
        'add_new'             => __( 'Add New', 'storefront' ),
        'add_new_item'        => __( 'Add New Tutorial', 'storefront' ),
        'edit_item'           => __( 'Edit Tutorial','storefront'),
        'new_item'            => __( 'New Tutorial', 'storefront'),
        'all_items'           => __( 'All Tutorial', 'storefront' ),
        'view_item'           => __( 'View Tutorial', 'storefront'),
        'search_items'        => __( 'Search Tutorial','storefront'),
        'not_found'           => __( 'No events found', 'storefront' ),
        'not_found_in_trash'  => __( 'No events found in Trash','storefront' ),
        'menu_name'           => __( 'Tutorial', 'storefront'),
    );

    $supports = array( 'title','editor','comments','page-attributes','thumbnail');

    $slug = get_theme_mod( 'tutorial_permalink' );
    $slug = ( empty( $slug ) ) ? 'tutorial' : $slug;

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => $slug ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => $supports,
    );

    register_post_type( 'tutorial', $args );
    $labels = array(
        'name' => __('Category','storefront'),
        'singular_name' => 'tutorial',
        'search_items' =>  __('Search...','storefront') ,
        'popular_items' => __('Popular','storefront') ,
        'all_items' => __('All','storefront') ,
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit','storefront') ,
        'update_item' => __('Update','storefront') ,
        'add_new_item' => __('Add','storefront'),
        'new_item_name' =>__('Category Name','storefront'),
        'separate_items_with_commas' => __('Separated by commas','storefront') ,
        'add_or_remove_items' => __('Add Or Remove','storefront'),
        'choose_from_most_used' => __('Choose from the type often used in','storefront'),
        'menu_name' => __('Category','storefront'),
    );

    register_taxonomy(
        'tutorial-category',//自定义分类组的别名
        array('tutorial'),
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'tutorial' ),
        )
    );
}

add_filter('manage_tutorial_posts_columns', 'tutorial_post_table_head');
function tutorial_post_table_head( $defaults ) {
    $defaults['author'] = __('Author','default');
    $defaults['views'] = __('Views','default');
    $defaults['tutorial_cat'] = __('Categories','default');
    return $defaults;
}

add_action('restrict_manage_posts','restrict_manage_tutorial_by_category');
function restrict_manage_tutorial_by_category() {
    global $post_type;
    if($post_type == 'tutorial'){
        $taxonomy = 'tutorial-category';
        $category_taxonomy = get_taxonomy($taxonomy);
        $tutorial_args = array(
            'show_option_all'   => __("All {$category_taxonomy->label}",'storefront'),
            'orderby'           => 'name',
            'order'             => 'ASC',
            'name'              => 'tutorial_cat',
            'taxonomy'          => 'tutorial-category',
            'hierarchical'    =>  true,
            'depth'           =>  5,
            'show_count'      =>  true,
            'hide_empty'      =>  true,
        );
        if(isset($_GET['tutorial_cat'])){
            $tutorial_args['selected'] = sanitize_text_field($_GET['tutorial_cat']);
        }
        wp_dropdown_categories($tutorial_args);
    }
}

add_filter('pre_get_posts','tutorial_filters');
function tutorial_filters($query) {
    global $post_type, $pagenow;
    if($pagenow == 'edit.php' && $post_type == 'tutorial'){
        if(isset($_GET['tutorial_cat'])){
            $tutorial_cat = sanitize_text_field($_GET['tutorial_cat']);
            if($tutorial_cat != 0){
                $query->query_vars['tax_query'] = array(
                    array(
                        'taxonomy'  => 'tutorial-category',
                        'field'     => 'id',
                        'terms'     => array($tutorial_cat)
                    )
                );
            }
        }
    }
}

add_action('manage_posts_custom_column', 'manage_tutorial_custom_column',10,2);
function manage_tutorial_custom_column( $column_id,$post_id ) {
    global $typenow;
    if ($typenow=='tutorial') {
        $taxonomy = 'tutorial-category';
        switch ($column_id) {
            case 'tutorial_cat':
                $cats= get_the_terms($post_id,$taxonomy);
                if (is_array($cats)) {
                    $cat = array();
                    foreach($cats as $key => $_cat) {
                        $cat[$key] = '<a href="edit.php?post_status=all&post_type=tutorial&tutorial_cat='.$_cat->term_id.'">' . $_cat->name . '</a>';
                    }
                    echo implode(' | ',$cat);
                }
                break;
            case 'thumb':
                echo "<img width='75' height='75' src='".get_post_thumbnail_url($post_id)."'/>";
                break;
            }
    }
}

///// contest /////
add_action( 'init', 'add_contest_post_types' );
function add_contest_post_types() {
    $labels = array(
        'name'                => __( 'Entries', 'storefront' ),
        'singular_name'       => __( 'Entries', 'storefront' ),
        'add_new'             => __( 'Add New', 'storefront' ),
        'add_new_item'        => __( 'Add New Entries', 'storefront' ),
        'edit_item'           => __( 'Edit Entries','storefront'),
        'new_item'            => __( 'Entries', 'storefront'),
        'all_items'           => __( 'All Entries', 'storefront' ),
        'view_item'           => __( 'View Entries', 'storefront'),
        'search_items'        => __( 'Search Entries','storefront'),
        'not_found'           => __( 'No Entries found', 'storefront' ),
        'not_found_in_trash'  => __( 'No Entries found in Trash','storefront' ),
        'menu_name'           => __( 'Contest', 'storefront'),
    );

    $supports = array( 'title','editor','comments','page-attributes','thumbnail');

    $slug = get_theme_mod( 'contest_permalink' );
    $slug = ( empty( $slug ) ) ? 'contest' : $slug;

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => $slug ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => $supports,
    );
    register_post_type( 'contest', $args );

    $labels = array(
        'name' => __('Contest','storefront'),
        'singular_name' => 'contest',
        'search_items' =>  __('Search...','storefront') ,
        'popular_items' => __('Popular','storefront') ,
        'all_items' => __('All','storefront') ,
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit','storefront') ,
        'update_item' => __('Update','storefront') ,
        'add_new_item' => __('Add','storefront'),
        'new_item_name' =>__('Contest Name','storefront'),
        'separate_items_with_commas' => __('Separated by commas','storefront') ,
        'add_or_remove_items' => __('Add Or Remove','storefront'),
        'choose_from_most_used' => __('Choose from the type often used in','storefront'),
        'menu_name' => __('Contest','storefront'),
    );

    register_taxonomy(
        'contest-category',//自定义分类组的别名
        array('contest'),
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'contest' ),
        )
    );

}

add_filter('manage_contest_posts_columns', 'contest_post_table_head');
function contest_post_table_head( $defaults ) {
    $defaults['author'] = __('Author','default');
    $defaults['contest_cat'] = __('Categories','default');
    $defaults['views'] = __('Views','storefront');
    $defaults['vote'] = __('Votes','storefront');
    return $defaults;
}


add_action('manage_posts_custom_column', 'manage_contest_custom_column',10,2);
function manage_contest_custom_column( $column_id,$post_id ) {
    global $typenow;
    if ($typenow=='contest') {
        $taxonomy = 'contest-category';
        switch ($column_id) {
            case 'contest_cat':
                $cats= get_the_terms($post_id,$taxonomy);
                if (is_array($cats)) {
                    $cat = array();
                    foreach($cats as $key => $_cat) {
                        $cat[$key] = '<a href="edit.php?post_status=all&post_type=contest&contest_cat='.$_cat->term_id.'">' . $_cat->name . '</a>';
                    }
                    echo implode(' | ',$cat);
                }
                break;
            case 'thumb':
                echo "<img width='75' height='75' src='".get_post_thumbnail_url($post_id)."'/>";
                break;
            case 'vote':
                $vote = get_post_meta($post_id,'_vot',true) ? get_post_meta($post_id,'_vot',true) : 0;
                echo number_format($vote).' '.__('Votes','storefront');
        }
    }
}
add_action('restrict_manage_posts','restrict_manage_contest_by_category');
function restrict_manage_contest_by_category() {
    global $post_type;
    if($post_type == 'contest'){
        $taxonomy = 'contest-category';
        $category_taxonomy = get_taxonomy($taxonomy);
        $contest_args = array(
            'show_option_all'   => __("All {$category_taxonomy->label}",'storefront'),
            'orderby'           => 'name',
            'order'             => 'ASC',
            'name'              => 'contest_cat',
            'taxonomy'          => 'contest-category',
            'hierarchical'    =>  true,
            'depth'           =>  5,
            'show_count'      =>  true,
            'hide_empty'      =>  true,
        );
        if(isset($_GET['contest_cat'])){
            $contest_args['selected'] = sanitize_text_field($_GET['contest_cat']);
        }
        wp_dropdown_categories($contest_args);
    }
}

add_filter('pre_get_posts','contest_filters');
function contest_filters($query) {
    global $post_type, $pagenow;
    if($pagenow == 'edit.php' && $post_type == 'contest'){
        if(isset($_GET['contest_cat'])){
            $tutorial_cat = sanitize_text_field($_GET['contest_cat']);
            if($tutorial_cat != 0){
                $query->query_vars['tax_query'] = array(
                    array(
                        'taxonomy'  => 'contest-category',
                        'field'     => 'id',
                        'terms'     => array($tutorial_cat)
                    )
                );
            }
        }
    }
}

//add sort field
add_filter( 'manage_edit-contest_sortable_columns', 'contest_sortable_columns' );
function contest_sortable_columns( $columns ) {
    $columns['vote'] = '_vot';
    $columns['views'] = 'views';
    return $columns;
}

//set sort params
add_action( 'load-edit.php', 'my_edit_contest_load' );
function my_edit_contest_load() {
    add_filter( 'request', 'my_sort_contest' );
}

//sort by callback
function my_sort_contest( $vars ) {
    if ( isset( $vars['post_type'] ) && 'contest' == $vars['post_type'] ) {
        if ( isset( $vars['orderby'] ) && '_vot' == $vars['orderby'] ) {
            $vars = array_merge(
                $vars,
                array(
                    'meta_key' => '_vot',
                    'orderby' => 'meta_value_num'
                )
            );
        }
        if ( isset( $vars['orderby'] ) && 'views' == $vars['orderby'] ) {
            $vars = array_merge(
                $vars,
                array(
                    'meta_key' => 'views',
                    'orderby' => 'meta_value_num'
                )
            );
        }
    }
    return $vars;
}

// Add menu item for draft posts
function add_kinds_admin_menu_item() {
    add_submenu_page( 'edit.php?post_type=contest', __("Collect Contest",'storefront'), __("Collect Contest",'storefront'), 'read', 'collect_user_contest' ,'collect_user_contest');
}
add_action( 'admin_menu', 'add_kinds_admin_menu_item' );

function collect_user_contest(){
    $list_table = new Custom_Table_List_Contest_Kind_Table();
    $list_table->prepare_items();
?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
        <form id="contest-collect-filter" method="get">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <!-- Now we can render the completed list table -->
            <?php $list_table->display() ?>
        </form>
    </div>
<?php
}


/**
 * add filed to download category
 */
add_filter("manage_edit-dlm_download_category_columns", 'dlm_download_category_columns');
function dlm_download_category_columns($columns) {
    $columns['id'] = __('ID');
    return $columns;
}

add_filter("manage_dlm_download_category_custom_column", 'manage_dlm_download_category_columns', 10, 3);
function manage_dlm_download_category_columns($out, $column_name, $theme_id) {
    switch ($column_name) {
        case 'id':
            $out .= "<code>".$theme_id."</code>";
            echo $out;
            break;
    }
}

add_filter("manage_edit-tutorial-category_columns", 'tutorial_category_columns');
function tutorial_category_columns($columns) {
    $columns['order'] = __('Order');
    $columns['show_in_front'] = __('Show');
    return $columns;
}

add_filter("manage_tutorial-category_custom_column", 'manage_tutorial_category_columns', 10, 3);
function manage_tutorial_category_columns($out, $column_name, $theme_id) {
    switch ($column_name) {
        case 'order':
            $order = get_term_meta($theme_id,'sort_order',true) ? get_term_meta($theme_id,'sort_order',true) : 0;
            $out .= "<code>".$order."</code>";
            echo $out;
            break;
        case 'show_in_front':
            $show_in_front = get_term_meta($theme_id,'display_front',true) ? 'Yes' : 'No';
            $out .= "<code>".$show_in_front."</code>";
            echo $out;
            break;
    }
}


/**
 * add seo category  and seo post type
 */
add_action( 'init', 'add_seo_post_types' );
function add_seo_post_types() {
    $labels = array(
        'name'                => __( 'SEO', 'storefront' ),
        'singular_name'       => __( 'SEO', 'storefront' ),
        'add_new'             => __( 'Add New', 'storefront' ),
        'add_new_item'        => __( 'Add New SEO', 'storefront' ),
        'edit_item'           => __( 'Edit SEO','storefront'),
        'new_item'            => __( 'SEO', 'storefront'),
        'all_items'           => __( 'All SEO', 'storefront' ),
        'view_item'           => __( 'View SEO', 'storefront'),
        'search_items'        => __( 'Search SEO','storefront'),
        'not_found'           => __( 'No SEO found', 'storefront' ),
        'not_found_in_trash'  => __( 'No SEO found in Trash','storefront' ),
        'menu_name'           => __( 'SEO', 'storefront'),
    );

    $supports = array( 'title','editor','comments','thumbnail','categories');

    $slug = get_theme_mod( 'seo_permalink' );
    $slug = ( empty( $slug ) ) ? 'seo' : $slug;

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => $slug ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => null,
        'supports'            => $supports,
    );
    register_post_type( 'seo', $args );

    $labels = array(
        'name' => __('SEO Category','storefront'),
        'singular_name' => 'seo-category',
        'search_items' =>  __('Search...','storefront') ,
        'popular_items' => __('Popular','storefront') ,
        'all_items' => __('All','storefront') ,
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit','storefront') ,
        'update_item' => __('Update','storefront') ,
        'add_new_item' => __('Add New Seo Category','storefront'),
        'new_item_name' =>__('Category Name','storefront'),
        'separate_items_with_commas' => __('Separated by commas','storefront') ,
        'add_or_remove_items' => __('Add Or Remove','storefront'),
        'choose_from_most_used' => __('Choose from the type often used in','storefront'),
        'menu_name' => __('SEO Category','storefront'),
    );

    register_taxonomy(
        'seo-category',//自定义分类组的别名
        array('seo'),
        array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'seocat' ),
        )
    );
}

add_action('restrict_manage_posts','restrict_manage_seo_by_category');
function restrict_manage_seo_by_category() {
    global $post_type;
    if($post_type == 'seo'){
        $taxonomy = 'seo-category';
        $category_taxonomy = get_taxonomy($taxonomy);
        $seo_args = array(
            'show_option_all'   => __("All {$category_taxonomy->label}",'storefront'),
            'orderby'           => 'name',
            'order'             => 'ASC',
            'name'              => 'seo_cat',
            'taxonomy'          => 'seo-category',
            'hierarchical'      =>  true,
            'depth'             =>  5,
            'show_count'        =>  true,
            'hide_empty'        =>  true,
        );
        if(isset($_GET['seo_cat'])){
            $seo_args['selected'] = sanitize_text_field($_GET['seo_cat']);
        }
        wp_dropdown_categories($seo_args);
    }
}

add_filter('manage_seo_posts_columns', 'seo_post_table_head');
function seo_post_table_head( $defaults ) {
    $defaults['author'] = __('Author','default');
    $defaults['seo_cat'] = __('Categories','default');
    $defaults['thumb'] = __('Thumbnail','default');
    return $defaults;
}


add_action('manage_posts_custom_column', 'manage_seo_custom_column',10,2);
function manage_seo_custom_column( $column_id,$post_id ) {
    global $typenow;
    if ($typenow=='seo') {
        $taxonomy = 'seo-category';
        switch ($column_id) {
            case 'seo_cat':
                $cats= get_the_terms($post_id,$taxonomy);
                if (is_array($cats)) {
                    $cat = array();
                    foreach($cats as $key => $_cat) {
                        $cat[$key] = '<a href="edit.php?post_status=all&post_type=contest&seo_cat='.$_cat->term_id.'">' . $_cat->name . '</a>';
                    }
                    echo implode(' | ',$cat);
                }
                break;
            case 'thumb':
                echo "<img width='75' height='75' src='".get_post_thumbnail_url($post_id)."'/>";
                break;
        }
    }
}

add_filter('pre_get_posts','seo_filters');
function seo_filters($query) {
    global $post_type, $pagenow;
    if($pagenow == 'edit.php' && $post_type == 'seo'){
        if(isset($_GET['seo_cat'])){
            $seo_cat = sanitize_text_field($_GET['seo_cat']);
            if($seo_cat != 0){
                $query->query_vars['tax_query'] = array(
                    array(
                        'taxonomy'  => 'seo-category',
                        'field'     => 'id',
                        'terms'     => array($seo_cat)
                    )
                );
            }
        }
    }
}
//add custom filed to seo edit page

$new_meta_boxes =
    array(
        "keywords" => array(
            "name" => "keywords",
            "std" =>  '',
            "title" => __("Keywords",'storefront'),
            'desc' => __('Keywords of seo post detail page','storefront'),
        ),
        "description" => array(
            "name"  => "description",
            "std"   => '',
            "title" => __("Description",'storefront'),
            'desc'  => __('Description of seo post detail page','storefront'),
        ),
    );


function new_meta_boxes() {
    global $post, $new_meta_boxes;
    foreach($new_meta_boxes as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, 'seo_'.$meta_box['name'], true);
        echo'<input type="hidden" name="'.$meta_box['name'].'_noncename" id="'.$meta_box['name'].'_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
        // 自定义字段标题
        echo '<div id="postcustomstuff">
                  <table id="newmeta">
                        <thead>
                            <tr>
                                <th class="left"><label for="metavalue">'.$meta_box['title'].'</label></th>
                            </tr>
                        </thead>
                        <tbody id="the-list" data-wp-lists="list:meta">
                           <tr id="dlm_third">
                                <td class="left">
                                  <textarea id="metavalue" name="'.$meta_box['name'].'" rows="4" cols="25">'.$meta_box_value.'</textarea>
                               </td>
                           </tr>
                       </tbody>
                  </table>
              </div>
              <p>'.$meta_box['desc'].'</p>';
    }
}

function create_meta_box() {
    global $theme_name;
    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'new-meta-boxes', __('Seo Post Meta Information','storefront'), 'new_meta_boxes', 'seo', 'normal', 'high' );
    }
}

add_action('save_post', 'save_postdata');
add_action('admin_menu', 'create_meta_box');
function save_postdata( $post_id ) {
    global $post, $new_meta_boxes,$typenow;
    foreach($new_meta_boxes as $meta_box) {
        if ( !wp_verify_nonce( $_POST[$meta_box['name'].'_noncename'], plugin_basename(__FILE__) ))  {
            return $post_id;
        }
        if ( 'seo' == $_POST['post_type'] ) {
            if ( !current_user_can( 'edit_page', $post_id ))
                return $post_id;
        }
        else {
            if ( !current_user_can( 'edit_post', $post_id ))
                return $post_id;
        }
        if($typenow =='seo'){
            if(get_post_meta($post_id,'views',true) == ''){
                add_post_meta($post_id,'views',0);
            }
            $data = $_POST[$meta_box['name']];
            if(get_post_meta($post_id, 'seo_'.$meta_box['name']) == "")
                add_post_meta($post_id, 'seo_'.$meta_box['name'], $data, true);
            elseif($data != get_post_meta($post_id, 'seo_'.$meta_box['name'], true))
                update_post_meta($post_id, 'seo_'.$meta_box['name'], $data);
            elseif($data == "")
                delete_post_meta($post_id, 'seo_'.$meta_box['name'], get_post_meta($post_id, 'seo_'.$meta_box['name'], true));
        }
    }
}