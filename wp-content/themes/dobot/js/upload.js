/**
 * Created by yang on 2017/4/11.
 */
jQuery(document).ready(function() {
    //upbottom为上传按钮的id
    jQuery('#upbottom').click(function () {
        targetfield = jQuery('#upload_image');
        tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
        return false;
    });

    window.send_to_editor = function (html) {
        var imgurl = jQuery(html).find('img').attr('src');
        jQuery('#upload_image').val(imgurl);
        tb_remove();
    };


    var maxwidth = 520;
    var image = jQuery("#show-img");
    if ( image.width() > maxwidth) {
        var oldwidth  =  image.width();
        var oldheight =  image.height();
        var newheight = maxwidth/oldwidth*oldheight;
        image.css({width:maxwidth+"px",height:newheight+"px",cursor:"pointer"});
        image.attr("title","点击查看原图");
        image.click(function(){window.open( image.attr("src"))});
    }


});
