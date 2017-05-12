<?php
/**
 * Created by PhpStorm.
 * User: yang
 * Date: 2017/3/23
 * Time: 12:54
 */
$tutorial_category = get_tutorial_category();
?>
<div class="publish-tutorial dobot-account-right-habg publish-tutorial-right">
    <div class="inventions-header"><h3 class="account-set-title"><?php _e('Publish Tutorial','woocommerce')?></h3></div>
    <form method="post" id="pub-tuto-form" enctype="multipart/form-data">
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
            <label for="title"><?php _e('Title','woocommerce')?> </label>
            <input type="text" name="post_title" id="title" class="woocommerce-Input woocommerce-Input--text input-text"/>
        </p>
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
            <label for="description"><?php _e('Description','woocommerce')?> </label>
            <input type="text" name="post_description" id="description" class="woocommerce-Input woocommerce-Input--text input-text"/>
        </p>
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide selectImage">
            <label for="description"><?php _e('Attachment Image','woocommerce')?> </label>
            <input style="margin-left: 0px;" name="attachment" type="file" class="woocommerce-Input submitbutton" id="attachment" value="<?php _e('Browse','woocommerce')?>"/></p>
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
            <label for="description"><?php _e('Enclosure','woocommerce')?> </label>
            <input style="margin-left: 0px;" name="enclosure" type="file" class="woocommerce-Input submitbutton" id="selectImage" value="<?php _e('Browse','woocommerce')?>"/>
        </p>
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
            <label for="description"><?php _e('Enclosure Description','woocommerce')?> </label>
            <input type="text" name="enclosure_des" class="woocommerce-Input woocommerce-Input--text input-text" >
        </p>
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
            <label for="category"><?php _e('Category','woocommerce')?> </label>
            <select name="post_category" id="category_id">
            <?php foreach ($tutorial_category as $_category):?>
                <option value="<?php echo $_category->term_id?>"><?php echo  __($_category->name,'storefront');?></option>
            <?php endforeach;?>
            </select>
        </p>
        <p class="woocommerce-FormRow woocommerce-FormRow--last form-row form-row-wide">
            <label for="category"><?php _e('Content','woocommerce')?> </label>
            <textarea name="post_content" id="post_content" cols="30" rows="10"></textarea>
        </p>
        <input type="hidden" name="tutorial" value="tutorial">
        <input type="hidden" name="action" value="submit">
        <p class="textcenter">
            <input class="button submitbutton" name="save_video" id="tutorial-subbtn" value="Submit" type="button">
            <input class="button submitbutton" name="cancel_video" id="tutorial-calbtn"value="Cancel" type="button">
        </p>
    </form>
    <script type="text/javascript">
        (function ($) {
            KindEditor.options.filterMode = false;
            KindEditor.ready(function(K) {
                window.editor = K.create('#post_content', {
                    width: '260px', height: '320px',
                    themeType: 'default',
                    langType: 'en',
                    allowImageRemote: false,
                    filterMode: false,
                    afterBlur: function () {this.sync();},
                    afterUpload: function () {this.sync();}
                });
            });
            $('#tutorial-subbtn').bind('click',function (e) {
                if(check_form()){
                    $('#pub-tuto-form').submit();
                }
                e.stopPropagation();
            });

            $('#tutorial-calbtn').bind('click',function (e) {
                var field = ['title','description'];
                for(var i = 0; i<field.length ; i++) {
                    var id = field[i];
                    var element = $('#pub-tuto-form #' + id);
                    element.val('');
                }
                KindEditor.instances[0].html('');
                window.location.href = "<?php echo esc_url(wc_get_account_endpoint_url('contest'))?>";
                e.stopPropagation();
            });
            function check_form() {
                var field = ['title','description','post_content','attachment'];
                var errors = 0;
                for(var i = 0; i<field.length ; i++){
                    var id = field[i];
                    var $element = $('#pub-tuto-form #'+id);
                    if( $element.val() == '' || $element.val() == null){
                        errors++;
                        $element.addClass('valid-error')
                    }else{
                        $element.removeClass('valid-error')
                    }
                }
                if(errors){
                    return false;
                }else{
                    return true;
                }
            }
        })(jQuery)
    </script>
</div>
