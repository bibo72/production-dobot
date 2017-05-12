<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/3/23
 * Time: 12:54
 */
$contest_id = isset($_GET['cid']) ? $_GET['cid'] : 0;
if(!$contest_id){
    $url = get_site_url().'/my-account';
    header("Location:$url");
}else {
    $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : 0;
    $post = get_post_by_id($post_id);
    $post_content = $post ? $post->post_content : null;
    $_enclosure_id = get_post_meta($post_id, '_enclosure_id', true);
    $_attached_file = get_post_meta($_enclosure_id, '_wp_attached_file', true);
    if ($_enclosure_id){
        $file_url = parse_url($_attached_file);
        $path = ABSPATH . $file_url['path'];
        $fileInfo = PATHINFO($path);
        $file_name = $fileInfo['basename'];
        //download
        if ($_POST['down']) {
            dobot_download($path, $file_name);
        }
    }
?>
<div class="publish-tutorial dobot-account-right-habg publish-tutorial-right">
    <div class="inventions-header">
        <h3 class="account-set-title">
            <?php if($post_id):?>
                <?php _e('Edit Contest','woocommerce')?>
            <?php else:?>
                <?php _e('Publish Contest','woocommerce')?>
            <?php endif;?>
        </h3>
    </div>
    <form method="post" id="pub-tuto-form" enctype="multipart/form-data">
        <input type="hidden" name="post_id" value="<?php echo $post_id?>">
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
            <label for="title"><?php _e('Title','woocommerce')?> </label>
            <input type="text" value="<?php echo __($post->post_title,'storefront')?>" name="post_title" id="title" class="woocommerce-Input woocommerce-Input--text input-text"/>
        </p>
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
            <label for="description"><?php _e('Description','woocommerce')?> </label>
            <input type="text" value="<?php echo __(get_post_meta($post_id,'description',true),'storefront')?>"  name="description" id="description" class="woocommerce-Input woocommerce-Input--text input-text"/>
        </p>
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide selectImage">
            <label for="description"><?php _e('Attachment Image','woocommerce')?> </label>
            <input style="margin-left: 0px;display: none"   name="attachment" type="file" class="woocommerce-Input submitbutton" id="attachment" value="<?php _e('Browse','woocommerce')?>"/>
            <img style="display: none" class="input-text" height="150" width="150" src="" id="xmTanImg">
            <?php if(get_post_thumbnail_url($post_id)):?>
                <img id="attachment-img" class="input-text" src="<?php echo get_post_thumbnail_url($post_id)?>" alt="" width="150" height="150">
                <button id="upload" type="button" style="padding: 3px 5px;background-color: whitesmoke;color: #0a0a0a;"><?php echo __('Change')?></button>
            <?php else:?>
                <button id="upload" type="button" style="padding: 3px 5px;background-color: whitesmoke;color: #0a0a0a"><?php echo __('Insert Image')?></button>
            <?php endif;?>
        </p>

        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
            <label for="description"><?php _e('Enclosure','woocommerce')?> </label>
            <input style="margin-left: 0px;display: none"  name="enclosure" type="file" class="woocommerce-Input submitbutton" id="enclosure" value="<?php _e('Browse','woocommerce')?>"/>
            <?php if($_attached_file):?>
                <button type="button" id="enclosure-btn" style="padding: 3px 5px;background-color: whitesmoke;color: #0a0a0a" class="download-btn"><?php echo __('Change','storefront')?></button>
                <input style=' width:200px;height: 28px;border:1px solid #f1f1f1' type='text' value='<?php echo $file_name?>'/>
            <?php else:?>
                <button  id="enclosure-btn" type="button" style="padding: 3px 5px;background-color: whitesmoke;color: #0a0a0a" class="download-btn"><?php echo __('Choose File','storefront')?></button>
            <?php endif;?>
        </p>
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
            <label for="description"><?php _e('Enclosure Description','woocommerce')?> </label>
            <input type="text" value="<?php echo __(get_post_meta($post_id,'_enclosure_description',true))?>" name="enclosure_des" class="woocommerce-Input woocommerce-Input--text input-text" >
        </p>
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
            <label for="category"><?php _e('Content','woocommerce')?> </label>
            <textarea name="post_content" id="post_content" cols="30" rows="10"></textarea>
        </p>
        <input type="hidden" name="post_category" value="<?php echo $contest_id?>">
        <input type="hidden" name="contest" value="contest">
        <p class="textcenter">
            <input type="hidden" name="action" id="pub_status" value="pending">
            <input class="button submitbutton save" name="save_video" id="subbtn" value="<?php echo __('Save','storefront') ?>" type="button">
            <?php if($post_id):?>
                <span class="loading-img" id="loading-img" style="display: none"><img src="<?php echo get_stylesheet_directory_uri().'/assets/images/dobot/loading.gif'?>" alt=""></span>
                <input class="button submitbutton save-and-pre" name="cancel_video" id="subbtn-pre" value="<?php echo __('Preview','storefront') ?>" type="button">
            <?php endif;?>
            <input class="button submitbutton draft" name="cancel_video" id="drabtn" value="<?php echo __('Draft','storefront') ?>" type="button">
        </p>
    </form>

    <script type="text/javascript">
        (function ($) {
            var post_conetnt = <?php echo json_encode($post_content) ?>;
            var $post_id = parseInt(<?php echo $post_id?>);

            /*实例化文本编辑器*/
            KindEditor.options.filterMode = false;
            var lang = 'en';
            KindEditor.ready(function(K) {
                window.editor = K.create('#post_content', {
                    width: '260px', height: '320px',
                    themeType: 'default',
                    langType: lang,
                    allowImageRemote: false,
                    filterMode: false,
                    afterBlur: function () {this.sync();},
                    afterUpload: function () {this.sync();}
                });

                if($post_id){
                    window.editor.html(post_conetnt);
                }

            });

            /*验证并提交表单*/
            $('#subbtn').bind('click',function (e) {
                if(check_form()){
                    $('#pub-tuto-form').submit();
                }
                e.stopPropagation();
            });

            /*作为草稿保存*/
            $('#drabtn').bind('click',function (e) {
                if(check_form()){
                    $('#pub_status').val('draft');
                    $('#pub-tuto-form').submit();
                }
                e.stopPropagation();
            });

            //验证表单
            function check_form() {
                var field = ['title','description','post_content','attachment'];
                var errors = 0;
                for(var i = 0; i<field.length ; i++){
                    var id = field[i];
                    if($post_id && id == 'attachment'){
                    }else{
                        var $element = $('#pub-tuto-form #'+id);
                        if( $element.val() == '' || $element.val() == null){
                            errors++;
                            $element.addClass('valid-error')
                        }else{
                            $element.removeClass('valid-error')
                        }
                    }
                }
                if(errors){
                    return false;
                }else{
                    return true;
                }
            };

            //附件添加后，显示附件名字
            $('#enclosure').on('change',function () {
                if($('#enclosure-btn').next('input')){
                    $('#enclosure-btn').next('input').remove();
                }
                if(!$post_id){
                    $('#enclosure-btn').html('<?php echo __('Change','storefront')?>');
                }
                $('#enclosure-btn').after("<input style='width:200px;height:28px;border:1px solid #f1f1f1' type='text' value='"+$(this).val()+"'/>");
            });

            //实时预览特色图片
            $('#attachment').live('change',function () {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = document.getElementById("xmTanImg");
                    $(img).show();
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
                if(!$post_id){
                    $('#upload').html('<?php echo __('Change','storefront')?>');
                }
                $('#attachment-img').hide();
            });

            //上传特色图片
            $('#upload').click(function () {
                $('#attachment').click();
            });

            //上传附件
            $('#enclosure-btn').click(function () {
                $('#enclosure').click();
            })


            //预览
            $('#subbtn-pre').click(function () {
                var $content = window.editor.html();
                var $title = $('#title').val();
                var $div ='<div  class="tutorial-detail dobot-account-right-habg">'+
                                '<h3 class="textcenter">'+$title+'</h3>'+
                                 $content+
                            '</div>';
                $('#loading-img').show();
                var preview =  function() {
                    $('#loading-img').hide();
                    var dialog = KindEditor.dialog({
                        width : 700,
                        height: 500,
                        overflow :'auto',
                        title : $title,
                        body : $div,
                        closeBtn : {
                            name : '<?php _e('Close','storefront')?>',
                            click : function(e) {
                                dialog.remove();
                            }
                        },
                        noBtn : {
                            name : '<?php _e('Close','storefront')?>',
                            click : function(e) {
                                dialog.remove();
                            }
                        }
                    });
                };
                setTimeout(preview,300);
            });
        })(jQuery)
    </script>
</div>
<?php } ?>