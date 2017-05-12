<?php
/**
* Plugin Name: [Enqtran] Subscriber website
* Plugin URI: http://enqtran.com/
* Description: Subscriber website auto send email to admin and subscriber.
* Author: enqtran
* Version: 1.0
* Author URI: http://enqtran.com/
* Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=EU3YV2GB9434U
* License: GPLv3 or later
* License URI: http://www.gnu.org/licenses/gpl-3.0.html
* Tags: enqtran, enq, enqpro, send mail, subscriber, follow ...
*/

/*
* Plugin status
* Last update: 09/12/2015
*/
add_action( 'widgets_init', 'subscriber_enqtran_widget' );
if ( !function_exists('subscriber_enqtran_widget') ) {
    function subscriber_enqtran_widget() {
        register_widget('Enqtran_Subscriber_Widget');
    }
}
class Enqtran_Subscriber_Widget extends WP_Widget {

/**
 * config widget
 */
function __construct() {
    $widget_ops = array(
            'sub_email_widget', // id
            'description'=>'[Enqtran] Subscriber website'
        );
     parent::__construct( '', '[Enqtran] Subscriber website', $widget_ops );
}

/**
 * [form admin]
 */
function form( $instance ){
    $defaults = array(
        'title' => ''
        );
    $instance = wp_parse_args( $instance, $defaults );
    $title = esc_attr($instance['title']);
?>

<!-- show form admin -->
<div class="box-w">
    <p>
        <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title' ); ?></label>
    </p>
    <p>
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" placeholder="Title for Widget" />
    </p>
</div>
<div class="box-w">
    <p>Default don't show</p>
</div>
<?php
}

/*
* [update]
*/
function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance['title'] = esc_attr($new_instance['title']);
    return $instance;
}

/**
* [widget content]
*/
function widget( $args, $instance ) {
    extract($args);
    $title = apply_filters( 'widget_title', $instance['title'] );
    echo $before_widget;
    if ( !empty( $title ) ) {
        echo $before_title;
        echo $title;
        echo $after_title;
    } ?>
    <div class="content-sidebar-widget">
        <div class="subscriberb_widget">
            <form action="" method="post" class="enqtran-send-mail form-horizontal ">
            <div class="emial-cont">
                <?php $placeHolder = 'Enter your E-mail'?>
                <?php $submitBtn = 'Subscribe'?>
                <input type="email" name="email_sub_by_enqtran" class="enqtran_sub_emails" value="" placeholder="<?php echo $placeHolder?>" required>
                <input type="button" name="submit_email_sub_by_enqtran" class="submit_email_enqtran" value="<?php echo $submitBtn?>">
                <div class="megs_enqtran_sub"></div>
            </div>
            </form>
            <style>

                .enqtran_sub_emails{
                    color: #000;
                }
                .submit_email_enqtran{
                    color: #fff;
                }
                form .emial-cont{position:relative;}
                form.enqtran-send-mail input {
                    display: inline;
                    text-align:center;
                    line-height:22px;
                }
                form.enqtran-send-mail input:focus{
                    text-align: left;
                }
                input.enqtran_sub_emails {
                    color: #000 !important;
                    background: #fff !important;
                    padding: 5px 5px;
                    border:none;
                    width: 100%;
                    padding-right: 130px;
                    border:1px solid #000;
                }
                input.submit_email_enqtran {
                    background: #333;
                    border: none;
                    padding: 5px;
                    text-transform: uppercase;
                    position: absolute;
                    right: 0px;
                    top: 0px;
                    width: 130px;
                    text-align: center;
                    border:1px solid #000;
                }

            </style>
            <script>
                jQuery(document).ready(function() {
                    jQuery('.submit_email_enqtran').click(function(event){
                        var img = '<img src="<?php echo get_bloginfo('template_directory').'/assets/images/dobot/loading.gif' ?>"/>';
                        var eamil = jQuery('.enqtran_sub_emails').val();
                        var myreg = /^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/;
                        if(myreg.test(eamil) ){
                            jQuery.ajax({
                                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                                type: 'POST',
                                data: jQuery('.enqtran-send-mail').serialize()+'&action=email_sub_action_enqtran_plugin',
                                dataType:"json",
                                // dataType: 'text',

                                beforeSend: function(){
                                    jQuery('.megs_enqtran_sub').html(img+'<span>Subscribing ...</span>');
                                    jQuery('.megs_enqtran_sub img').css({'display':'table-ceil','margin-right':'8px'});
                                    jQuery('.megs_enqtran_sub').css({'display':'table','margin':'8px 0'});
                                    jQuery('.megs_enqtran_sub span').css({'color': 'green','border':'0','vertical-align':'middle','display':'table-ceil','margin-right':'5px'});
                                }, //end before
                                success: function(result){
                                    if ( result == 'error') {
                                        jQuery('.megs_enqtran_sub').html('<span>An error occurred in the process of subscription!<span>');
                                        jQuery('.megs_enqtran_sub').css({'display':'table','margin':'8px 0'});
                                        jQuery('.megs_enqtran_sub span').css({'color': 'red','border':'0','vertical-align':'middle','display':'table-ceil','margin-right':'5px'});
                                        jQuery('.enqtran_sub_emails').val('');
                                        setTimeout(function(){
                                            jQuery('.megs_enqtran_sub').html('');
                                            jQuery('.megs_enqtran_sub').removeAttr('style');
                                        }, 2000);
                                    }
                                    if ( result == 'success' ) {
                                        jQuery('.megs_enqtran_sub').html('<span>You have successfully subscribed.</span>');
                                        jQuery('.megs_enqtran_sub').css({'display':'table','margin':'8px 0'});
                                        jQuery('.megs_enqtran_sub span').css({'color': 'green','border':'0','vertical-align':'middle','display':'table-ceil','margin-right':'5px'});
                                        jQuery('.enqtran_sub_emails').val('');
                                        setTimeout(function(){
                                            jQuery('.megs_enqtran_sub').html('');
                                            jQuery('.megs_enqtran_sub').removeAttr('style');
                                        }, 2000);
                                    }

                                    if ( result == 'subscribed' ) {
                                        jQuery('.megs_enqtran_sub').html('<span>You have subscribed!<span>');
                                        jQuery('.megs_enqtran_sub').css({'display':'table','margin':'8px 0'});
                                        jQuery('.megs_enqtran_sub span').css({'color': 'red','border':'0','vertical-align':'middle','display':'table-ceil','margin-right':'5px'});
                                        jQuery('.enqtran_sub_emails').val('');
                                        setTimeout(function(){
                                            jQuery('.megs_enqtran_sub').html('');
                                            jQuery('.megs_enqtran_sub').removeAttr('style');
                                        }, 2000);
                                    }
                                } //end success
                            });
                        }else{
                            jQuery('.megs_enqtran_sub').html('<span>Please enter a valid email address<span>');
                            jQuery('.megs_enqtran_sub').css('color', 'red');
                            jQuery('.enqtran_sub_emails').val('');
                            jQuery('.enqtran_sub_emails').focus();
                            setTimeout(function(){
                                jQuery('.megs_enqtran_sub').html('');
                                jQuery('.megs_enqtran_sub').removeAttr('style');
                            }, 2000);
                        }
                        event.preventDefault();
                    });
                });
            </script>
        </div>
    </div>
<?php
    echo $after_widget;
    }
}
// end class widget

/**
 * Ajax action
 */
add_action('wp_ajax_email_sub_action_enqtran_plugin', 'email_sub_action_enqtran_plugin');
add_action('wp_ajax_nopriv_email_sub_action_enqtran_plugin', 'email_sub_action_enqtran_plugin');
function generateRandomStringEnqtranPlugins($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
// Email Notification to Admin
function email_sub_action_enqtran_plugin()
{
    global $wpdb;
    $email = esc_attr($_POST['email_sub_by_enqtran']);
    // Remove all illegal characters from email
    $email = filter_var( $email, FILTER_SANITIZE_EMAIL );
    // Validate e-mail
    if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
        $userdata =  array(
            'email'    => $email,
        );
        $table = 'wp_subscriber';
        if(check_have_subscribe($email)){
            $idUser = 'subscribed';
        }else{
            $idUser = $wpdb->insert( $table ,$userdata );
        }
    } else {
        echo json_encode('error');
        exit();
    }
    if ( isset($idUser) && $idUser != 'subscribed') {
        if ( get_option( 'on_send_admin' ) == 'on') {
            $to = get_option('mail_from');
            $subject = 'New subscriber - '.$email;
            $body = EMAIL_HEADER_TOP.__('New subscriber','storefront').EMAIL_HEADER_BOT."News email subscriber: <a href=\"mailto:".$email."\">".$email."</a>".EMAIL_FOOTER;
            $headers = "MIME-Version: 1.0\n" . "Content-Type: text/html;";
            wp_mail( $to, $subject, $body ,$headers);
        }
        if ( get_option( 'on_send_subscriber' ) == 'on'){
            $subject = __('Newsletter subscription success');
            $body =  get_subscriber_email_html();
            $headers = "MIME-Version: 1.0\n" . "Content-Type: text/html;";
            wp_mail( $email, $subject, $body,$headers );
        }
        echo json_encode('success');
    } elseif ($idUser == 'subscribed'){
        echo json_encode('subscribed');
    }else {
        echo json_encode('error');
    }
    exit();
}

/**
 * Enable send mail to subscriber, have new articles
 */
if ( get_option( 'on_send_subscriber' ) == 'on') {
    add_action( 'save_post', 'enqtran_sub_plugin_updated_send_email' );
    function enqtran_sub_plugin_updated_send_email( $post_id ) {
         // If this is just a revision, don't send the email.
         if ( wp_is_post_revision( $post_id ) ) :
             return;
         endif;

         $post_title = get_the_title( $post_id );
         $post_url = get_permalink( $post_id );
         $subscribers = get_users( array ( 'role' => 'subscriber' ) );
            // $emails      = array ();

         $subject = "[News Post] ".$post_title;
         $messenger = "[News Post] ".$post_title;
            if ( get_option( 'messenger_email_to' ) ) {
                 $messenger .= "\n\n".get_option( 'messenger_email_to' );
            }
         $messenger .= "\n\n Link post: " . $post_url;
         $messenger .= "\n\n Thank Subscribe";
         // $messenger .= "\n\n Subscriber Website Plugin By Enqtran";

         // Send email
         if ( get_post_status( $post_id ) == 'publish') {
             foreach ( $subscribers as $subscriber ) :
                $emails = $subscriber->user_email;
                wp_mail( $emails, $subject, $messenger );
            endforeach;
         }
    }
}

add_action( 'admin_menu', 'register_enqtran_plugins_subscriber_page' );
function register_enqtran_plugins_subscriber_page() {
     add_theme_page( 'Subscriber', 'Subscriber', 'manage_options', 'subscriber', 'enqtran_plugin_subscriber_setting_page' );
}

add_action( 'admin_init' , 'enqtran_plugin_subscriber_page' );
function enqtran_plugin_subscriber_page() {
    //Turn Off Website Maintenance
    register_setting( 'enq-subscriber-group' , 'on_send_admin' );
    register_setting( 'enq-subscriber-group' , 'on_send_subscriber' );
    register_setting( 'enq-subscriber-group' , 'messenger_email_to' );
}

function enqtran_plugin_subscriber_setting_page() { ?>
    <div class="wrap">
        <?php echo get_screen_icon(); ?>
        <div>
            <h1 align="center"> Email Subscriber</h1>
        </div>
        <form action="options.php" method="post" id="theme_setting">
            <?php settings_fields( 'enq-subscriber-group' ); ?>
            <style>
                .fix-width {
                    width:150px;
                }
                .pad {
                    padding:10px 20px;
                }
            </style>

            <!-- Maintenance -->
            <h2> Option email </h2>
            <table class="theme_page widefat" >
                <tr>
                    <th class="fix-width">Email Admin</th>
                    <td>
                        <input type="checkbox" name="on_send_admin" <?php checked( get_option( 'on_send_admin' ), 'on');?> /> Send email to admin when news subscriber.
                    </td>
                </tr>
                <tr>
                    <th class="fix-width">Email Subscriber</th>
                    <td>
                        <input type="checkbox" name="on_send_subscriber" <?php checked( get_option( 'on_send_subscriber' ), 'on');?> /> Send email to subscriber when new post publish
                    </td>
                </tr>
                <tr>
                    <th>Messager</th>
                    <td>
                        <textarea name="messenger_email_to"  class="widefat" rows="4"><?php echo get_option( 'messenger_email_to' ); ?></textarea>
                    </td>
                </tr>
            </table>
            <?php submit_button( 'Save Changes','primary' ); ?>
        </form>
        <br>

        <?php
        $list_table = new Subscriber_List_Table();
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
    </div>
<?php }
function create_table(){
    global $wpdb;
    $sql = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "subscriber` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `email` varchar(200) DEFAULT NULL,
          `subscrib_status` varchar(20) default 'subscribe',
          `create_date` TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `id` (`id`)
        )  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5";
    $wpdb->query($sql);
}
register_activation_hook(__FILE__, 'create_table');

function get_subscribers($paged = null,$offset=null){
    global $wpdb;
    $limit = '';
    if($paged && $offset){
        $limit .= " LIMIT $paged,$offset";
    }
    $query = $wpdb->prepare("SELECT * FROM wp_subscriber ORDER BY create_date".$limit);
    $result = $wpdb->get_results($query);
    return $result;
}
function check_have_subscribe($email){
    global $wpdb;
    $query = $wpdb->prepare("SELECT * FROM wp_subscriber WHERE email='%s'",$email);
    $result = $wpdb->get_row($query);
    if($result){
        return true;
    }else{
        return false;
    }
}