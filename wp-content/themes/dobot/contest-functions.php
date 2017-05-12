<?php
/**
 * custom contest field
 * Class dot_taxonomy_field
 */
class dot_taxonomy_field{
    var $options;
    var $taxonomyinfo;
    function __construct($options,$taxonomyinfo){
        $this->options = $options;
        $this->taxonomyinfo = $taxonomyinfo;
        foreach($this->taxonomyinfo as $taxonomy){
            add_action($taxonomy.'_add_form_fields', array($this, 'taxonomy_fields_adds'), 10, 2);
            add_action($taxonomy.'_edit_form_fields', array($this, 'taxonomy_metabox_edit'), 10, 2);
            add_action('created_'.$taxonomy, array($this, 'save_taxonomy_metadata'), 10, 1);
            add_action('edited_'.$taxonomy,array($this, 'save_taxonomy_metadata'), 10, 1);
            add_action('wp_enqueue_scripts', array($this, 'init_taxonomy'));
            add_action('delete_'.$taxonomy, array($this,'delete_taxonomy_metadata'),10,1);
        }
    }
    function init_taxonomy(){
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
    }

    /*添加分类页面*/
    function taxonomy_fields_adds($tag){
        foreach($this->options as $option){
            if( (!isset($option["edit_only"]) || !$option["edit_only"] ) ){
                if (method_exists($this, $option['type'])){
                    echo '<div class="form-field">';
                    echo '<label for="'.$option['id'].'" >'.$option['name'].'</label>';
                    $this->run($option);
                    echo '</div>';
                }
            }
        }
    }
    /*编辑分类页面*/
    function taxonomy_metabox_edit($tag){
        foreach($this->options as $option){
            if (method_exists($this, $option['type'])){
                if(get_term_meta($tag->term_id , $option['id']) !== ""){
                    $option['std'] = get_term_meta($tag->term_id,$option['id'], true);
                }
                echo '<tr class="form-field">';
                echo '<th scope="row" valign="top">';
                echo '<label for="'.$option['id'].'" >'.__($option['name'],'storefront').'</label>';
                echo '</th>';
                echo '<td>';
                $this->run($option);
                echo '</td>';
                echo '</tr>';
            }
        }
    }
    function run($option){
        if($option['type'] == 'text')
            $this->text($option);
        else if($option['type'] == 'upload')
            $this->upload($option);
        else if($option['type'] == 'textarea')
            $this->textarea($option);
        else if($option['type'] == 'select')
            $this->select($option);
        else if($option['type'] == 'tinymce')
            $this->tinymce($option);
        else
            $this->text($option);
    }
    /*删除数据*/
    function delete_taxonomy_metadata($term_id){
        foreach($this->options as $option){
            delete_term_meta($term_id,$option['id']);
        }
    }
    /*保存数据*/
    function save_taxonomy_metadata($term_id){
        foreach($this->options as $option){
            if(isset($_POST[$option['id']])){
                if(!current_user_can('manage_categories')){
                    return $term_id ;
                }
                if( $option['type'] == 'tinymce' ){
                    $data =  stripslashes($_POST[$option['id']]);
                }elseif( $option['type'] == 'checkbox' ){
                    $data =  $_POST[$option['id']];
                }else{
                    $data = htmlspecialchars($_POST[$option['id']], ENT_QUOTES,"UTF-8");
                }
                if(get_term_meta($term_id , 'create_date', true) == '' ){
                    add_term_meta($term_id, 'create_date', date('Y-m-d'));
                }
                if( get_term_meta($term_id, 'views',true) == ''){
                    add_term_meta($term_id, 'views', 0);
                }
                if(get_term_meta($term_id , $option['id']) == "")
                    add_term_meta($term_id , $option['id'], $data, true);
                elseif($data != get_term_meta($term_id , $option['id'], true))
                    update_term_meta($term_id , $option['id'], $data);
                elseif($data == "")
                    delete_term_meta($term_id , $option['id'], get_term_meta($term_id , $option['id'], true));
            }
        }
    }

    /*文本输入框text*/
    function text($option){
        echo '<input type="text" size="'.$option['size'].'" value="';
        echo $option['std'];
        echo '" id="'.$option['id'].'" name="'.$option['id'].'"/>';
        echo '<p>'.__($option['desc'],'storefront').'</p>';
        if($option['id'] == 'end_time'){
            echo '<script>jQuery("#end_time,#start_time,#being_judged").datetimepicker();</script>';
        }
    }

    /*图片上传*/
    function upload($option){
        $prevImg = '';
        if($option['std'] != ''){$prevImg = '<img id="show-img" src='.$option['std'].' alt="" />';}
        echo '<div class="preview_pic_optionspage" id="'.$option['id'].'_div">'.$prevImg.'</div>';
        echo $option['desc'].'<br/>';
        echo '<input type="text" size="60" value="'.$option['std'].'" name="'.$option['id'].'" class="upload_pic_input" id="upload_image" />';
        echo '&nbsp;<input type="button" name="upload_button" value="'.__('Insert Image','storefront').'" id="upbottom"/>';
    }

    /*文本域*/
    function textarea($option){
        echo '<textarea cols="40" rows="5" id="'.$option['id'].'" name="'.$option['id'].'">'.$option['std'].'</textarea>';
        echo '<p>'.$option['desc'].'</p>';
    }


    /*单选框*/
    function select( $option ){
        echo "<select name='".$option['id']."' id='".$option['id']."'>";
        foreach( $option['buttons'] as $key=>$value ) {
            $selected = "";
            if( $option['std'] == $key) {
                $selected  = 'selected = "selected"';
            }
            echo '<option '.$selected.' class="kcheck" value="'.$key.'">'.__($value,'storefront').'</option>';
        }
        echo "</select>";
        echo '<p>'.$option['desc'].'</p>';
    }

    /**编辑器**/
    function tinymce($option){
        wp_editor(
            $option['std'],
            $option['id'],
            $settings=array('tinymce'=>1,'media_buttons'=>0)
        );
    }
}

$options[] = array(
    "name"          =>  __("Subtitle",'storefront'),
    "desc"          =>  "",
    "id"            =>  "subtitle",
    "std"           =>  '',
    "edit_only"     =>  true,
    "type"          =>  "text"
);
$options[] = array(
    "name"          =>  __("Start Time",'storefront'),
    "desc"          =>  "",
    "id"            =>  "start_time",
    "std"           =>  '',
    "edit_only"     =>  true,
    "type"          =>  "text"
);

$options[] = array(
    "name"          =>  __("Being Judged",'storefront'),
    "desc"          =>  "",
    "id"            =>  "being_judged",
    "std"           =>  '',
    "edit_only"     =>  true,
    "type"          =>  "text"
);

$options[] = array(
    "name"          =>  __("End Time",'storefront'),
    "desc"          =>  "",
    "id"            =>  "end_time",
    "std"           =>  '',
    "edit_only"     =>  true,
    "type"          =>  "text"
);

$options[] = array(
    "name"          =>  __("Contest Tag",'storefront'),
    "desc"          =>  __('Separated by commas','storefront'),
    "id"            =>  "contest_tag",
    "std"           =>  '',
    "edit_only"     =>  true,
    "type"          =>  "text"
);

$options[] = array(
    "name"      => __("Banner",'storefront'),
    "desc"      => __("Banner of the details page",'storefront'),
    "id"        => "banner",
    "std"       => "",
    "edit_only" =>true,
    "type"      => "upload"
);

$options[] = array(
    "name"      => __("Banner Text",'storefront'),
    "desc"      => __("Banner of the details page",'storefront'),
    "id"        => "banner_text",
    "std"       => "",
    "edit_only" => true,
    "type"      => "text"
);

$options[] = array(
    "name"          =>  __("Introduction",'storefront'),
    "desc"          =>  __("the main introduction of contest",'storefront'),
    "id"            =>  "introduction",
    "std"           =>  '',
    "edit_only"     =>  true,
    "type"          =>  "tinymce"
);


$options[] = array(
    "name"          =>  __("Prize Setting",'storefront'),
    "desc"          =>  "",
    "id"            =>  "prize",
    "std"           =>  '',
    "edit_only"     =>  true,
    "type"          =>  "tinymce"
);

$taxonomyinfo = array('contest-category');
$new_taxonomy_feild = new dot_taxonomy_field($options, $taxonomyinfo);

/**
* add field to tutorial category
*/

//sort filed
$tutorialOptions[] = array(
    "name"      => __("Sort Order",'storefront'),
    "desc"      => __('Tutorial category of the sort, the more value in front.','storefront'),
    "id"        => "sort_order",
    "std"       => 0,
    "edit_only" => false,
    "type"      => "text"
);
// Whether in front display
$tutorialOptions[] = array(
    "name"      => __("Display in front",'storefront'),
    "desc"      => __('Whether in front display.','storefront'),
    "id"        => "display_front",
    "std"       => 0,
    "edit_only" => false,
    "buttons" => array(
        '0'=> __('No','storefront'),
        '1'=> __('Yes','storefront'),
    ),
    "type"      => "select"
);
$tutorialTax = array('tutorial-category');
$tutorialField = new dot_taxonomy_field($tutorialOptions,$tutorialTax);

$seoOptions [] = array(
    "name"      => __("Display in front",'storefront'),
    "desc"      => __('Whether in front display.','storefront'),
    "id"        => "display_front",
    "std"       => 1,
    "edit_only" => false,
    "buttons" => array(
        '0'=> __('No','storefront'),
        '1'=> __('Yes','storefront'),
    ),
    "type"      => "select"
);
$seoOptions [] = array(
    "name"      => __("Order By",'storefront'),
    "desc"      => __('Seo posts order field.','storefront'),
    "id"        => "order_by",
    "std"       => 'date',
    "edit_only" => false,
    "buttons" => array(
        'views' => __('Views','storefront'),
        'date'  => __('Date','storefront'),
    ),
    "type"      => "select"
);
$seoTax = array('seo-category');
$seoField = new dot_taxonomy_field($seoOptions,$seoTax);


///////////////////////////
///add datetimepicker js //
////////////////////////
global  $pagenow;
if($pagenow == 'term.php' && $_GET['taxonomy'] =='contest-category'){
    wp_enqueue_script('my-upload', get_bloginfo( 'stylesheet_directory' ) . '/js/upload.js');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_script( 'custom_js', get_template_directory_uri().'/assets/datetimepicker/jquery.datetimepicker.full.min.js', array(), $my_js_ver ,false);
    wp_enqueue_style( 'custom_js_style', get_template_directory_uri().'/assets/datetimepicker/jquery.datetimepicker.min.css', array(), $my_js_ver );

}

///////////////////////////
///date function //
////////////////////////
/**
 * get contest countdown
 * @param $inputSeconds
 * @return string
 */
function contest_seconds_to_time( $inputSeconds) {
    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);

    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // return the final string
    $string = '';
    if ( $days > 0 ) {
        $string .= '<span class="daytime">'.$days.'</span>'.' '.__("Days",'storefront');
    }
    if ( $hours > 0 OR $days > 0 ) {
        $string .= ' '.'<span class="hourstime">'.$hours.'</span>'.' '.__("Hours",'storefront');
    }
    return $string;
}

///////////////////////////
///get contest//
////////////////////////
/**
 * get all published contests
 * @return array
 */
function get_contests(){
    global $wpdb;
    $contests = array();
    $orderby = ' ORDER BY term_id DESC';

    $paged = isset($_GET['pag']) ? $_GET['pag'] : 1;
    $paged = $paged < 1 ? 1 : $paged;

    $left = '';

    //contest status
    if(isset($_GET['status'])){
        if($_GET['status'] != 'all' && is_numeric($_GET['status'])){
            $left .= " JOIN wp_termmeta AS b ON b.meta_key = 'status' AND  b.meta_value = {$_GET['status']} AND b.term_id = wp_term_taxonomy.term_id ";
        }
    }

    //orderby
    if(isset($_GET['orderby'])){
        if($_GET['orderby'] == 'create_date'){
            $orderby = ' ORDER BY term_id DESC';
        }else if($_GET['orderby'] == 'views'){
            $join = ',(c.meta_value+0) as views';
            $left .= " JOIN wp_termmeta AS c ON c.meta_key = 'views' AND c.term_id = wp_term_taxonomy.term_id ";
            $orderby = ' ORDER BY views DESC';
        }else if($_GET['orderby'] == 'entries'){
            $orderby = ' ORDER BY count DESC';
        }else if($_GET['orderby'] == 'end_time'){
            $join = ',(c.meta_value) as end_time';
            $left .= " JOIN wp_termmeta AS c ON c.meta_key = 'end_time' AND c.term_id = wp_term_taxonomy.term_id ";
            $orderby = ' ORDER BY end_time ASC';
        }
    }
    if(isset($_GET['status']) && is_numeric($_GET['status'])){
        $all_left = " JOIN wp_termmeta AS b ON b.meta_key = 'status' AND  b.meta_value = {$_GET['status']} AND b.term_id = wp_term_taxonomy.term_id ";
    }

    //get totals using paged
    $query_all = "SELECT wp_term_taxonomy.term_id FROM wp_term_taxonomy {$all_left} WHERE taxonomy='contest-category'";
    $totals  = count($wpdb->get_results($query_all));

    $persize = 12;
    $paged = $paged > ceil($totals/$persize) ? ceil($totals/$persize) : $paged;
    $start = ($paged-1) * $persize;
    $limit = " LIMIT {$start},{$persize} ";

    $query = "SELECT wp_term_taxonomy.term_id{$join} FROM wp_term_taxonomy {$left} WHERE taxonomy='contest-category' {$orderby} {$limit}";
    foreach ($wpdb->get_results($query) as $_term){
        $contests[$_term->term_id] = get_term_by('id',$_term->term_id,'contest-category');
    }

    $contests['contest'] = $contests;
    $contests['page'] = custom_page($totals,$paged,$persize,false,'#contests');

    return $contests;
}

/**
 * 由参赛作品id得到竞赛id
 * @param $id
 * @return mixed
 */
function get_contest_id_by_entries($id){
    global  $wpdb;
    $query = $wpdb->prepare("SELECT term_taxonomy_id FROM wp_term_relationships WHERE  object_id=%d",$id);
    $result = $wpdb->get_row($query);
    return $result->term_taxonomy_id;
}

/**
 * set contest detail page title
 * @param $title
 * @return mixed
 */
function Bing_remove_tagline( $title ){
    global  $post;
    if($contest_slug = get_query_var('contest_id')){
        if(substr_count($contest_slug,'.html')){
            $contest_slug = str_replace('.html','',$contest_slug);
        }
        $contest = get_term_by('slug',$contest_slug,'contest-category');
        $title['title'] = $contest->name;
    }
    if(isset($_GET['seoid']) && ($_GET['seoid']) > 0 && is_numeric($_GET['seoid'])){
        $seo = get_term_by('id',$_GET['seoid'],'seo-category');
        $title['title'] = 'Resource '.$seo->name;
    }
    return $title;
}
add_filter( 'document_title_parts', 'Bing_remove_tagline' );

/**
 * 保存用虎竞赛作品
 * @param $data
 * @return int|WP_Error
 */
function  insert_customer_contest($data){
    global  $current_user;
    $content = str_replace('\\"',' ',$data['post_content']);
    $data['post_content'] = $content;
    $post_data = array(
        'post_author'    => $current_user->ID,
        'post_content'   => $data['post_content'],
        'post_title'     => $data['post_title'],
        'post_status'    => $data['action'],
        'post_type'      => 'contest',
    );


    if($data['post_id']){
        $post_id = $data['post_id'];
        $post_data['ID'] = $post_id;
        wp_update_post($post_data);
    }else{
        $post_id = wp_insert_post($post_data);
        if ( ! empty( $_POST['post_category'] )) {
            $cat_id = $_POST['post_category'];
            inserT_post_category($post_id,$cat_id);
        }
    }

    if($data['enclosure_des'] && $data['post_id'] &&  ! empty( $_FILES )){
        update_post_meta($data['post_id'],'_enclosure_description',$data['enclosure_des']);
    }

    if( ! empty( $_FILES ) ) {
        foreach( $_FILES as $index=> $file ) {
            if( is_array( $file ) && $file['name'] != '' && $file['tmp_name'] != ''
                && $file['name'] != null && $file['tmp_name'] != null
                ) {
                if($index == 'attachment'):
                    $attachment_id = upload_user_file( $file );
                    update_post_meta($post_id,'_thumbnail_id',$attachment_id);
                elseif($index == 'enclosure'):
                    $attachment_id = upload_user_file( $file );
                    update_post_meta($post_id,'_enclosure_id',$attachment_id);
                    update_post_meta($post_id,'_enclosure_description',$data['enclosure_des']);
                else:
                    $attachment_id = upload_user_file( $file );
                    update_post_meta($post_id,'_thumbnail_id',$attachment_id);
                endif;
            }
        }
    }
    update_post_meta($post_id,'description',$data['description']);
    if(!$data['post_id']){
        add_post_meta($post_id,'_vot',0);
    }
    update_user_meta($current_user->ID,'_contest',$_POST['post_category']);
    return $post_id;
}

/**
 * 竞赛状态
 * @param $key
 * @return mixed
 */
function get_contest_status_options($key){
  $status  =  array(
          'pending'=>__('Pending','storefront'),
          'publish'=>__('Publish','storefront'),
          'draft'=>__('Draft','storefront'),
  );
  return $status[$key];
}

/**
 * 得到当前用户的竞赛作品
 * @return array
 */
function get_current_user_contest(){
    global $current_user,$wpdb;
    $paged = $_GET['pag'] ? $_GET['pag'] : 1;
    $per_size = 10;
    $total = new WP_Query('post_status=any&showposts=-1&orderby=date&post_type=contest&author='.$current_user->ID);
    $total_page = ceil($total->post_count/$per_size);
    $paged = $paged > $total_page ? $total_page : $paged;
    $result = new WP_Query('post_status=any&paged='.$paged.'&showposts='.$per_size.'&orderby=date&post_type=contest&author='.$current_user->ID);
    $page = custom_page($total->post_count,$paged,$per_size,false);
    $posts = $result->posts;
    $data['post'] = $posts;
    $data['page'] = $page;
    return $data;
}

/**
 * ajax对参赛作品的投票
 */
function ajax_vot_contest(){
    global $current_user;
    $post_id = $_POST['post_id'];
    $user_has_vot = check_user_has_vot($current_user->ID,$post_id);
    if($user_has_vot){
        $html = '<button class="button" disabled="disabled">'.__('Voted!','storefront').'</button>';
        $result['votes'] = get_post_meta($post_id,'_vot',true);
        $result['html'] = $html;
        $result['status'] = 200;
    }else{
        $vot = get_post_meta($post_id,'_vot',true) ? get_post_meta($post_id,'_vot',true) : 0 ;
        update_post_meta($post_id,'_vot',$vot+1);
        update_user_meta($current_user->ID,"_vot_post_$post_id",$post_id);

        if(!isset($_POST['page'])){
            $html = '<span class="hasvoted" id="'.$post_id.'">'.__('Voted!','storefront').'</span>';
            $result['status'] = 200;
            $result['html'] = $html;
        }else{
            $html = '<button class="button" disabled="disabled">'.__('Voted!','storefront').'</button>';
            $result['votes'] = get_post_meta($post_id,'_vot',true);
            $result['html'] = $html;
            $result['status'] = 200;
        }

    }
    wp_die(json_encode($result));
}

add_action('wp_ajax_vot', 'ajax_vot_contest');
add_action('wp_ajax_nopriv_vot', 'ajax_vot_contest');

/**
 * 检查用户是否对某个参赛作品已投票
 * @param  int $user
 * @param  int $post
 * @return bool
 */
function check_user_has_vot($user,$post){
    global  $wpdb;
    $query = $wpdb->prepare("SELECT umeta_id FROM wp_usermeta WHERE meta_key = '_vot_post_".$post."' AND user_id = %d  AND  meta_value =%d ",$user,$post);
    $row = $wpdb->get_row($query);
    if($row){
        return true;
    }else{
        return false;
    }
}

/**
 * 得到参赛作品列表
 * @param object $entry
 */
function get_entries_list_item($entry){
    global $current_user;
    $user = get_user_by('ID',$entry->post_author);
    $user_name = $user ? ucwords($user->user_login) : ucwords('Dobot');
    $views = get_post_meta($entry->ID,'views',true) ? get_post_meta($entry->ID,'views',true) : 0;
    $praize = get_post_meta($entry->ID,'_liked',true) ? get_post_meta($entry->ID,'_liked',true) : 0;
    $user_has_vot = check_user_has_vot($current_user->ID,$entry->ID);
    $contest_id = get_contest_id_by_entries($entry->ID);
    $contest_status = get_term_meta($contest_id,'status',true);
?>
    <li class="entries-item">
        <div class="entries-img">
            <a class="responsive-img" href="<?php echo esc_url(get_permalink($entry->ID))?>" style="background-image: url('<?php echo get_post_thumbnail_url($entry->ID,'media')?>')" alt="<?php echo __($entry->post_title,'storefront')?>"></a>
            <div class="video-img-hover-bg">
                <a href="<?php echo share_to_social($entry,'facebook')?>" target="_blank"><span class="share-facebook"></span></a>
                <a href="<?php echo share_to_social($entry,'twitter')?>" target="_blank"><span class="share-twitter"></span></a>
            </div>
        </div>
        <div class="entries-footer">
            <div class="entires-title"><a title='<?php echo __($entry->post_title,'storefront')?>' href="<?php echo esc_url(get_permalink($entry->ID))?>"><?php echo __($entry->post_title,'storefront')?></a>
            </div>
            <div class="ext-data clearbox">
                <span class="publish-by fl"><?php echo __('By').' '.'<strong>'.$user_name.'</strong>';?></span>
                <span class="views fr"><?php echo number_format($views)?></span>
                <span class="video-parise fr"><?php echo number_format($praize)?></span>
            </div>
            <?php if($contest_status == 1):?>
            <div class="vote-this textcenter" id="vot-<?php echo $entry->ID?>">
                <span class="loading-img" id="loading-img-<?php echo $entry->ID?>" style="display: none"><img src="<?php echo get_stylesheet_directory_uri().'/assets/images/dobot/loading.gif'?>" alt=""></span>
                <?php if(!$user_has_vot):?>
                    <span id="span-<?php echo $entry->ID?>"><input class="vot-box no-vot"  type="checkbox"  name="vot-<?php echo $entry->ID?>" id="<?php echo $entry->ID?>"><?php _e('Vote Now','storefront')?></span>
                <?php  else:?>
                    <span class="hasvoted" id="span-<?php echo $entry->ID?>"><?php _e('Voted!','storefront')?></span>
                <?php endif; ?>
            </div>
            <?php endif;?>
        </div>
    </li>
<?php
}

/**
 * 得到某个竞赛的获奖作品
 * @param $contest_id
 * @param int $num
 * @return array|null|object
 */
function get_contest_winners($contest_id,$num = 3){
    global  $wpdb;
    $query = "SELECT a.ID,a.post_author,a.post_title,(b.meta_value + 0) AS vots 
              FROM wp_posts AS a 
              JOIN wp_postmeta AS b ON b.meta_key = '_vot' AND b.post_id = a.ID AND (b.meta_value+0) >0 
              JOIN wp_term_relationships AS c ON a.ID=c.object_id AND term_taxonomy_id=".$contest_id." 
              WHERE post_type='contest' ORDER BY vots DESC LIMIT 0,".$num;

    return $wpdb->get_results($query);
}

/**
 * get contest winners
 * @param $winners
 */
function get_winners_items($winners){?>
    <ul class="winner-items">
    <?php $i=0;foreach ($winners as $winner) {$i++;
        $user = get_user_by('ID', $winner->post_author);
        $user_name = $user->user_login;
        $user_img= get_avatar($user->ID,'70');
        $views = get_post_meta($winner->ID, 'views', true) ? get_post_meta($winner->ID, 'views', true) : 0;
        $comments = $winner->comment_count;
        $praize = get_post_meta($winner->ID, '_liked', true) ? get_post_meta($winner->ID, '_liked', true) : 0;
        $location = '';
        if($i == 1)
            $location = 'center';
        elseif($i == 2)
            $location = 'left';
        elseif($i == 3)
            $location = 'right';
        ?>
        <li class="winner-item <?php echo  $location ?> <?php echo "prise-level".$i;?>">
            <div class="winner-header">
                <div class="user-img textcenter"><?php echo $user_img;?></div>
                <div class="by-user textcenter"><?php echo __('By').' '.'<span>'.$user_name.'</span>';?></div>
            </div>
            <div class="winner-img responsive-img" style="background-image:url(<?php echo esc_url(get_post_thumbnail_url($winner->ID, 'thubnail')) ?>);">
            </div>
            <div class="textcenter">
                <div class="winner-footer textcenter">
                    <span class=""><?php echo number_format($views)?><span class="winner-footer-btn"><?php echo __('Views','storefront')?></span></span>
                    <span class=""><?php echo number_format($comments)?><span class="winner-footer-btn"><?php echo __('Comments','storefront')?></span></span>
                    <span class=""><?php echo number_format($praize)?><span class="winner-footer-btn"><?php echo __('Likes','storefront')?></span></span>
                </div>
            </div>
            <div class="prise-level">
                <?php if($i == 1 ):?>
                <div class="levle-bottom-<?php echo $i;?>"><?php echo __('Grand Prize','storefront')?></div>
                <?php elseif($i == 2):?>
                <div class="levle-bottom-<?php echo $i;?>"><?php echo __('First Prize','storefront')?></div>
                <?php elseif ($i == 3):?>
                <div class="levle-bottom-<?php echo $i;?>"><?php echo __('Second Prize','storefront')?></div>
                <?php endif;?>
            </div>
        </li>
    <?php } ?>
    </ul>
<?php
}

global  $current_contest_cat;
$current_contest_cat = isset($_COOKIE['contest_id']) ? $_COOKIE['contest_id'] : 0;

/**
 * get contest name
 * @param $contest_id
 * @return string
 */
function get_contest_cat_name_by_id($contest_id){
    $term = get_term_by('id',$contest_id,'contest-category');
    return $term->name;
}

/**
 * get contest entries
 * @param $contest_id
 * @param array $args
 * @return array
 */
function get_contests_entries($contest_id, $args = array()){
    $postsAll = array();
    $result = array();
    $per_page_show = 12;
    $paged = 1;
    if(isset($_GET['pag'])){
        $paged = $_GET['pag'] ;
    }
    $paged = $paged < 1 ? 1 : $paged;
    $cat_id = $contest_id;
    if($cat_id){
        $args['tax_query' ] = array(
            array(
                'taxonomy' =>'contest-category',
                'field'    => 'id',
                'terms'    =>$cat_id,
            )
        );
    }
    setcookie('contest_id',$cat_id,0,'/');
    $total = array(
        'orderby'           => 'date',
        'order'             => 'desc',
        'post_type'         => 'contest',
        'post_status'       => 'publish',
        'posts_per_page'    =>  -1,
    );
    $all_post_query = new WP_Query($total);
    $totals = $all_post_query->post_count;
    $paged = $paged > ceil($totals/$per_page_show) ? ceil($totals/$per_page_show) : $paged;
    $defaults = array(
        'orderby'           => 'date',
        'order'             => 'DESC',
        'post_type'         => 'contest',
        'post_status'       => 'publish',
        'posts_per_page'    =>  $per_page_show,
        'paged'             =>  $paged,
    );

    if($args){
        $argsNew = array_merge($defaults,$args);
    }else{
        $argsNew = $defaults;
    }

    $post_query= new WP_Query($argsNew);

    foreach ($post_query->get_posts() as $post){
        $postsAll[] = $post;
    }
    $result['post'] = $postsAll;

    //page
    $result['page'] = custom_page($totals,$paged,$per_page_show,false,'#entries');
    $result['count'] = $totals;

    return $result;
}

add_action('wp_ajax_collect_user_like_contest_kind', 'collect_user_like_contest_kind');
add_action('wp_ajax_nopriv_collect_user_like_contest_kind', 'collect_user_like_contest_kind');

/**
 * 收集用户比较感兴趣的竞赛
 */
function collect_user_like_contest_kind(){
   if($_POST['join_kind']) {
       global $wpdb;
       $table = $wpdb->prefix . 'contest_collect';
       $wpdb->insert($table,
           array(
               'name' => $_POST['name'],
               'email' => $_POST['email']  ,
               'content' => $_POST['text'] ,
           )
       );
       if($wpdb->insert_id){
           $mesg = __('Your suggestion has been submitted, thanks for your support.','storefront');
           $data['msg'] = $mesg;
           wp_die(json_encode($data));
       }
   }
}

function get_also_like_contest_entries($num){
    $per_size = $num;
    $defaults = array(
        'orderby'           => 'meta_value_num',
        'meta_key'          => 'views',
        'order'             => 'DESC',
        'post_type'         => 'contest',
        'post_status'       => 'publish',
        'posts_per_page'    =>  $per_size,
    );
    $post_query= new WP_Query($defaults);
    shuffle($post_query->posts);
    $data['post'] = $post_query->posts;
    return $data;
}