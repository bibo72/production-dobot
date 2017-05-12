/**
 * dobot.js
 */
( function() {
jQuery( window ).load( function() {
    // home slider full screen
    if(jQuery(window).width()>1024){
        var windowh = jQuery(window).height();
        jQuery('.home-banner.slides').height(jQuery('.home-banner.slides li img').attr('height'));
        jQuery('.homepage-content-area .home-banner.slides').height(windowh);
        jQuery('.homepage-content-area .home-banner.slides .caption').show();
        jQuery(window).resize(function () {
            var windowh = jQuery(window).height();
            jQuery('.homepage-content-area .home-banner.slides').height(windowh);
        });
    }
    // tab
    jQuery('.dobot-tabs .dobot-tab-item').click(function () {
        var objectid = jQuery(this).attr('id');
        jQuery(this).addClass('active');
        jQuery(this).siblings().removeClass('active');
        jQuery(this).parents('.dobot-tabs-cont').find('.tab-content-item #tab-' + objectid).show();
        jQuery(this).parents('.dobot-tabs-cont').find('.tab-content-item #tab-' + objectid).css('height','auto');
        jQuery(this).parents('.dobot-tabs-cont').find('.tab-content-item #tab-' + objectid).siblings().hide();
        jQuery(this).parents('.dobot-tabs-cont').find('.tab-content-item #tab-' + objectid).siblings().css('height','0px')
        //suport loading page nav
        if(jQuery(this).parents('.dobot-tabs').hasClass('support-dobot-tabs')){
            if(jQuery(window).width()<768){
                var activehtml=jQuery(this).parent().find('.active span').html();
                jQuery('.support-moible-nav span').html(activehtml);
                jQuery(this).parents('.dobot-tabs').removeClass('toggled');
            }
        }
    });
    jQuery('#tab-next').click(function(){
        var al=jQuery(this).parents('.dobot-tabs-cont').find('.dobot-tabs .dobot-tab-item').last();
        var f=jQuery(this).parents('.dobot-tabs-cont').find('.dobot-tabs .dobot-tab-item').first();
        var n=jQuery(this).parents('.dobot-tabs-cont').find('.dobot-tabs .dobot-tab-item.active').next();
        if(al.hasClass('active')){
            f.click();
        }else{
            n.click();
        }
    });
    jQuery('#tab-prev').click(function(){
        var al=jQuery(this).parents('.dobot-tabs-cont').find('.dobot-tabs .dobot-tab-item').last();
        var f=jQuery(this).parents('.dobot-tabs-cont').find('.dobot-tabs .dobot-tab-item').first();
        var p=jQuery(this).parents('.dobot-tabs-cont').find('.dobot-tabs .dobot-tab-item.active').prev();
        if(f.hasClass('active')){
            al.click();
        }else{
            p.click();
        }
    });


    // hide & show & hascss
    jQuery('.hide-section-title').click(function (event) {
        event.stopPropagation();
        jQuery(this).parents('.hide-section').find('.hide-section-content').toggle();
        jQuery(this).parents('.hide-section').siblings().find('.hide-section-content').hide();
        jQuery(this).parents('.hide-section').toggleClass('hideactive');
        jQuery(this).parents('.hide-section').siblings().removeClass('hideactive');
    });
    // account-upload video
    jQuery('#upvideobtn').click(function () {
        jQuery('.inventions-upload-menu-item.menu-item-video').addClass('active');
        jQuery('.upload-video-right').addClass('active');
        go_action_section();
    });


    // jQuery('.closelink').click(function () {
    //     jQuery('.inventions-upload-menu-item.menu-item-video').removeClass('active');
    //     jQuery('.upload-video-right').removeClass('active');
    //     reset_form();
    //     go_to_invention_top();
    // });

    // fixedtop
    if (jQuery('.pro-tab-cont').length) {
        var tabtotop = jQuery('.pro-tab-cont').offset().top;
        jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() > tabtotop) {
                jQuery('.pro-tab-cont').addClass('fixed');
            } else {
                jQuery('.pro-tab-cont').removeClass('fixed');
            }
        });
    }
    // scroll body to 0px on click
    function gotop(){
        if(jQuery(window).scrollTop()>100){
            jQuery('.page-top').fadeIn(100);
            
                jQuery('.page-top').click(function(){
                    jQuery('body,html').stop(false, false).animate({
                        scrollTop: 0
                    }, 800);
                    return false;
                });
           
        }else{
            jQuery('.page-top').fadeOut(100);
        }
    }
    gotop();
    jQuery(window).scroll(function(){
        gotop();
     });
    
    //my-account-inventions delete video
    jQuery('.del-video-a').bind('click', function (e) {
        var id = jQuery(this).attr('data-id');
        var url = jQuery(this).attr('ajax-url');
        var pophtml='<div class="pophtml"><div class="textcenter pophtml-title">Are you sure?</div><div class="textcenter"><button class="btn838383 button" id="yes-del">YES</button><button class="btn838383 button" id="no-del">No</button></div></div>';
        jQuery('body').append(pophtml);
        jQuery("#yes-del").click(function(){
            var data = {action: 'delete_video', id: id};
            var loading_mask = jQuery('#loading-img' + id);
            loading_mask.show();
            jQuery.post(url, data, function (response) {
                var sear = new RegExp('ok');
                if (sear.test(response)) {
                    loading_mask.hide();
                    window.location.reload();
                }
            });
            jQuery(".pophtml").remove();
        }); 
        jQuery("#no-del").click(function(){
            jQuery(".pophtml").remove();
        });
        e.stopPropagation();
    });
    //my-account-inventions edit video
    jQuery('.edt-video-a').each(function () {
        jQuery(this).bind('click', function (e) {
            var video_id = jQuery(this).attr('video_id');
            var url = jQuery(this).attr('ajax-url');
            var data = {action: 'get_video_info', id: video_id};
            var loading_mask = jQuery('#loading-img' + video_id);
            loading_mask.show();
            reset_form();
            jQuery.ajax({
                type: 'POST', url: url, data: data, dataType: 'json',
                success: function (response) {
                    jQuery.each(response, function (index, value) {
                        jQuery("#video-form #" + index).val(value);
                    });
                    loading_mask.hide();
                    jQuery('.inventions-upload-menu-item.menu-item-video').addClass('active');
                    jQuery('.upload-video-right').addClass('active');
                    jQuery('.upload-video-right #upload-video #video-form').show();
                    go_action_section();
                }
            });
            e.stopPropagation();
        });
    });

    // my profile upload photo
    function ishasfile() {
        if(jQuery('#wpua-file-existing').val()!=''){
            jQuery("#wpua-file-existing").parent().addClass('upload');
        }else{
            jQuery("#wpua-file-existing").parent().removeClass('upload');
        }
    }
    ishasfile();
    jQuery('#wpua-file-existing').change(function(){
        ishasfile();
    });
     // time axis
     if(jQuery('.time-cont').length){
        var item_height = 30, item_length, delay_time = 0;
        jQuery('.time-item').each(function(){
            jQuery(this).css('top',item_height);
            if(jQuery(this).height()<80){
                jQuery(this).height('60');
            }
            item_height = item_height*1 + 20*1 + jQuery(this).height();
            // animation
            jQuery(this).css({
                '-webkit-animation-delay': delay_time + 's',
            });
            delay_time = delay_time+0.5;
        });
        item_length  = jQuery('.time-item').length;
        jQuery('.time-axis-line').height( item_height);
        jQuery(window).scroll(function(){
            if(jQuery(window).scrollTop()+(jQuery(this).height()/2) >= jQuery('.time-cont').position().top){
                jQuery('.time-cont').find('.time-item').addClass('animation-active');
            }else{
                jQuery('.time-cont').find('.time-item').removeClass('animation-active');
            }
        });
     }
    
    // cms page load more
    if(jQuery('.load-more-blue')){
        jQuery('.load-more-blue').click(function(){
            jQuery(this).parents('.cms-page-content').find('.cms-page-content-hide').slideToggle("slow");
            jQuery(this).remove();
        });
    }
    // cms page mobile nav
      if(jQuery('.pro-tab-cont-items').length){
        var account_menu=jQuery('.pro-tab-cont-items');
        var ulwidth=1;
        account_menu.find('li').each(function(){
            ulwidth=ulwidth*1+Math.ceil(jQuery(this).width())*1;
        });
        ulwidth=ulwidth*1+20*1;
        if(jQuery(window).width()<480){
            jQuery('.pro-tab-cont-items .tabs').width(ulwidth);
        }
        if(account_menu.find('li.active').length)
        {
          account_menu.scrollLeft(account_menu.find('li.active').offset().left);  
        } 
      }
    
      // download category page  nav
      
      if(jQuery('.download-center-category .download-center-items-nav').length){
        if(jQuery(window).width()<1025){
            var download_menu=jQuery('.download-center-category .download-center-items-nav');
            var ulwidth=1;
            var padwidth=96;
            download_menu.find('li').each(function(){
                ulwidth=ulwidth*1+Math.ceil(jQuery(this).width())*1+padwidth*1;
            });
            // if(jQuery(window).width()<768){
                jQuery('.download-center-category .download-center-items').width(ulwidth);
            // }
            if(download_menu.find('li.active').length)
            {
              download_menu.scrollLeft(download_menu.find('li.active').offset().left);  
            }
        }else{
            var download_menu=jQuery('.download-center-items-nav');
            var itemnum=6;
            if(jQuery('.download-center-category').width()<1170){
                itemnum=Math.floor(jQuery('.download-center-items-nav').width()/195);
            }
            var lhtml='<div class="little-download-category"></div>';
            var rhtml='<div class="more-download-category"></div>';
            // add left icon and right icon
            if(download_menu.find('li').length>itemnum){
                jQuery('.download-center-items-nav-cont').before(lhtml);
                jQuery('.download-center-items-nav-cont').after(rhtml);    
                jQuery('.download-center-items-nav-cont').addClass('more');
            }
            var contwidth=jQuery('.download-center-items-nav').width()*1;
            console.log(contwidth);
            var itmewidth=contwidth/itemnum;
            download_menu.find('li').width(itmewidth);
            var ulwidth=itmewidth*1*(download_menu.find('li').length)*1;
            jQuery('.download-center-items').width(ulwidth);

            // init active position
            var activeleft=1;
            download_menu.find('li').each(function(){
                activeleft=activeleft*1+Math.ceil(jQuery(this).width())*1;
                if(jQuery(this).hasClass('active')){return false;}
            });
            activeleft=Math.floor(activeleft/contwidth)*contwidth;
            console.log(activeleft);
            jQuery('.download-center-items-nav').scrollLeft(activeleft);

            // go next
            jQuery('.more-download-category').click(function(){
                var currentleft=jQuery('.download-center-items-nav').scrollLeft();
                if(ulwidth>(currentleft*1+contwidth*1)){

                    jQuery('.download-center-items-nav').scrollLeft(currentleft*1+contwidth*1);
                }
            });
            // go prev
            jQuery('.little-download-category').click(function(){
                var currentleft=jQuery('.download-center-items-nav').scrollLeft();
                jQuery('.download-center-items-nav').scrollLeft(currentleft*1-contwidth*1);
            });
        }  
      }


    //reset form
    function reset_form() {
        jQuery("#video-form #video_id").val(0);
        jQuery("#video-form #url").val('');
        jQuery("#video-form #title").val('');
        jQuery("#video-form #description").val('');
        jQuery("#video-form #type option:first").prop("selected", 'selected');
        jQuery("#video-form #videogallery_id option:first").prop("selected", 'selected');
    }

    function go_action_section() {
        jQuery("html, body").animate({
            scrollTop: jQuery("#upload-box").offset().top + "px"
        }, {
            duration: 800,
            easing: "swing"
        });
    }

    function go_to_invention_top() {
        jQuery("html, body").animate({
            scrollTop: jQuery(".account-head-menu-items.itme-photo").offset().top + "px"
        }, {
            duration: 800,
            easing: "swing"
        });
    }
    pop_video();
    function pop_video() {
        jQuery('.video-element .huge_it_videogallery_item').each(function () {
            jQuery(this).unbind('click').bind('click', function (e) {
                var index = layer.load(0, {shade: false});
                var video_url = jQuery(this).attr('data-link');
                var video_id  = jQuery(this).attr('data-id');
                var ajax_url = jQuery(this). attr('ajax-url');
                var data = {action:'ajax_get_video_pop_info',id:video_id,url:video_url};
                jQuery.ajax({
                    type:'post', url:ajax_url, data:data, dataType:'json',
                    success:function (response) {
                        layer.close(index);
                        //show content
                        if(response.code == 200) {
                            layer.open({
                                type: 1,
                                title: response.title,
                                skin: 'layui-layer-rim',
                                area: ['100%', '100%'],
                                content: response.html
                            });
                        }
                        like_or_unlike_post();
                    }
                });
                e.stopPropagation();
            });
        });
    }

    like_or_unlike_post();

    function like_or_unlike_post() {
        var no_login_text = jQuery('#no_login_like').val();
        var login_btn_text = jQuery('#login_btn').val();
        var cancel_btn_text = jQuery('#cancel_btn').val();
        var refer = jQuery('#refer').val();
        jQuery("#no-login-like").click(function (e) {
            layer.msg(no_login_text, {
                time: 3000,
                btn: [login_btn_text, cancel_btn_text],
                yes: function(index) {
                    layer.close(index);
                    window.location.href=refer;
                }
            });
            e.stopPropagation();
        });
        jQuery('#pop-video-liked').click(function (e) {
            jQuery('#loading-img').show();
            var id  = jQuery(this).attr('data-id');
            var status  = jQuery(this).attr('data-status');
            jQuery.ajax({type:'POST', cache: false, url: dobot_obj.ajax_url,
                data:{action:'like_process', id: id, type: 'likeThis'},
                success: function(data) {
                    jQuery('#loading-img').hide();
                    var json = JSON.parse(data);
                    var vardata = json.data;
                    jQuery('#pop-video-liked').html(vardata);
                    if(status == 1){
                        jQuery('#pop-video-liked').attr('data-status',2);
                        jQuery('#pop-video-liked').removeClass('unlike');
                        jQuery('#pop-video-liked').addClass('liked');
                    }
                    if(status == 2){
                        jQuery('#pop-video-liked').attr('data-status',1);
                        jQuery('#pop-video-liked').removeClass('liked');
                        jQuery('#pop-video-liked').addClass('unlike');
                    }
                }
            });
            e.stopPropagation();
        });
    }

    // video category swiper
    var videoCategorySwiperWrapperWidth=jQuery('.video-category-swiper-wrapper').width();
    var videoCategorySwiperWrapperLiWidth=(jQuery('.video-category-swiper-wrapper .swiper-slide').width()*1+30*1)*1*jQuery('.video-category-swiper-wrapper .swiper-slide').length;
    if(videoCategorySwiperWrapperLiWidth>videoCategorySwiperWrapperWidth){
          var mySwiper = new Swiper ('.swiper-container', {
            slidesPerView:5,
            loop: true,
            autoplayDisableOnInteraction: false,
            spaceBetween:30,
            breakpoints:{
              1025:{
                slidesPerView:5,
                speed:1000,
              },
              768:{
                slidesPerView:4,
                speed:1000, 
              },
              479:{
                slidesPerView:2,
                speed:1000,
              }
            },
            paginationClickable:true,
            observer:true,
            observeParents:true,
            nextButton: '.swiper-button-next',
            prevButton: '.swiper-button-prev',
            
          });
    }else{
        jQuery('.video-category-swiper-wrapper').css({'display':'inline-block','margin-left':'30px'});
        jQuery('.video-category-nav .swiper-button-prev').hide();
        jQuery('.video-category-nav .swiper-button-next').hide();
    }

    $body=(window.opera)?(document.compatMode=="CSS1Compat"?jQuery('html'):jQuery('body')):jQuery('html,body');

    // join us slider
    if(jQuery('.joinus-swiper-container').length){
        var joinSwiper = new Swiper ('.joinus-swiper-container', {
            slidesPerView:2,
            loop: true,
            autoplay:1000,
            speed:3000,
            autoplayDisableOnInteraction: false,
            spaceBetween:0,
            breakpoints:{
              479:{
                slidesPerView:1
              }
            },
            paginationClickable:true,
            observer:true,
            observeParents:true
            
        });
        var bgleft='<div class="joinus-photos-bg-left"></div>';
        var bgright='<div class="joinus-photos-bg-right"></div>';
        jQuery('.joinus-photos-section').append(bgleft);
        jQuery('.joinus-photos-section').append(bgright);
        var shadebgwidth=(jQuery('.joinus-photos-section').width()-jQuery('.joinus-swiper-container').width())/2;
        jQuery('.joinus-photos-bg-left').width(shadebgwidth);
        jQuery('.joinus-photos-bg-right').width(shadebgwidth);
    }
    // m1 over slider
    if(jQuery('.m1overview-swiper-container').length){
        var m1Swiper = new Swiper ('.m1overview-swiper-container', {
            slidesPerView:3,
            loop: true,
            autoplay:1000,
            speed:3000,
            breakpoints:{
              479:{
                slidesPerView:2
              }
            },
            autoplayDisableOnInteraction: false,
            spaceBetween:5,
            paginationClickable:true,
            observer:true,
            observeParents:true,
            prevButton:'.m1overview-prev',
            nextButton:'.m1overview-next',
            pagination:'.m1overview-swiper-pagination'
            
        });
    }
    // tutorial list slider
    if(jQuery('.tutorial-swiper-wrapper').length){
        var fademode='fade';
        if(jQuery(window).width()<480){
            fademode='slide';
        }
        var tutorialSwiper = new Swiper ('.tutorial-swiper-wrapper', {
            slidesPerView:1,
            loop: true,
            autoplay:1000,
            speed:5000,
            spaceBetween:24,
            effect: 'fade',
            autoplayDisableOnInteraction: false,
            paginationClickable:true,
            observer:true,
            pagination:'.tutorial-pagination',
            observeParents:true 
        });
    }

    // 评论分页
    jQuery('#comments-navi a').live('click', function(e){
        e.preventDefault();
        jQuery.ajax({
            type: "GET",
            url: jQuery(this).attr('href'),
            beforeSend: function(){
                jQuery('#comments-navi').remove();
                jQuery('#loading-comments').slideDown();
            },
            dataType: "html",
            success: function(out){
                result = jQuery(out).find('.comment-list .depth-1');
                nextlink = jQuery(out).find('#comments-navi');
                jQuery('#loading-comments').slideUp('fast');
                jQuery('.comment-list').append(result.fadeIn(500));
                jQuery('.comment-list').after(nextlink);
            }
        });
    });

    //教程分页 & 视频分页
    var scrollPromise;
    var footheight=jQuery('.site-footer').height()*1+120*1;
    jQuery(window).bind("scroll",function(e){
        e.preventDefault();
        if(jQuery('.video-list-cont #tutorial-navi a').length){
        if( jQuery(document).scrollTop() + jQuery(window).height() > jQuery(document).height() - footheight ) {
            if(!scrollPromise||scrollPromise.state()!='pending'){
                scrollPromise=jQuery.ajax({
                    type: "GET",
                    url: jQuery('.video-list-cont #tutorial-navi a').attr('href'),
                    beforeSend: function(){
                        jQuery('#tutorial-navi').remove();
                        jQuery('.dobot-loading-more').slideDown();
                    },
                    dataType: "html",
                    success: function(out){
                        result = jQuery(out).find('.video-list .cols3-ul-items');
                        nextlink = jQuery(out).find('#tutorial-navi');
                        jQuery('.dobot-loading-more').slideUp('fast');
                        jQuery('.video-list').append(result.fadeIn(500));
                        jQuery('.video-list').after(nextlink);
                        pop_video();
                    }
                });
            }
        }
        }
    });
    // video list other-category click
    if(jQuery('.video-list-nav li.other')){
        jQuery('.video-list-nav li.other').click(function(){
            jQuery('.video-list-nav ul.status-item').removeClass('toggled');
            jQuery('.mobile-list-nav span').html(jQuery(this).find('a').html());
            jQuery(this).toggleClass('active');
            jQuery(this).siblings().removeClass('active');
            jQuery('.other-cat').toggleClass('show');
        });
    }

    //视频列表others
    jQuery('.other-cat').find('li').each(function () {
        if(jQuery(this).hasClass('is-active')) {
            jQuery('.other-cat').addClass(' show');
            jQuery(this).addClass('is-active');
            jQuery(this).parents('.other').addClass('is-active');
            jQuery(this).parents('.other').siblings().removeClass('is-active');
        }
    });


    jQuery('.other-cat li').each(function (e1) {
        jQuery(this).click(function (e) {
            var href = jQuery(this).find('a').attr('href');
            window.location.href = href;
            jQuery(this).siblings('.other').find('.other-cat').hide();
            jQuery(this).siblings('.other').removeClass('is-active');
            e.stopPropagation();
        });
        // e1.stopPropagation();
    });

    jQuery('.contest-join-btn a button').click(function () {
        jQuery("html, body").animate({
            scrollTop: jQuery("#join-contest").offset().top + "px"
        }, {
            duration: 800,
            easing: "swing"
        });
    });
    if(jQuery('.contest-detail-toolbar li').length){
        jQuery('.contest-detail-toolbar li').each(function () {
            jQuery(this).unbind('click').bind('click',function () {
                jQuery(this).siblings().removeClass('active');
                jQuery(this).addClass('active');
                jQuery("html, body").animate({
                    scrollTop: jQuery(jQuery(this).find('a').attr('href')).offset().top + "px"
                }, {
                    duration: 800,
                    easing: "swing"
                });
            });
        });
    }

    // mobile-footer
    if(jQuery(window).width()<768){
        jQuery('.footer-widgets .widget_nav_menu ul li.menu-item-has-children a[title=title]').click(function (event) {
            event.stopPropagation();
            jQuery(this).parent().find('.sub-menu').toggle();
            jQuery(this).parent().siblings().find('.sub-menu').hide();
            jQuery(this).parent().toggleClass('active');
            jQuery(this).parent().siblings().removeClass('active');
        });
    }
    function supportslider(obj){
        if(jQuery('.'+obj).length){
            var newobj=obj+'Swiper';
            var newobj = new Swiper ('.'+obj, {
                slidesPerView:2,
                loop: false,
                spaceBetween:0,
                paginationClickable:true,
                pagination: '.'+obj+'-scrollbar'
            });
        }
    }
    if(jQuery(window).width()<768){
        // turotial-list-nav
        jQuery('.mobile-list-nav').click(function(){
            if(jQuery('.contest-detail-nav .status-item').length){
                jQuery('.contest-detail-nav .status-item').toggleClass('toggled');
            }
            if(jQuery('.tutorial-tabs-head .status-item.tabs.wc-tabs').length){
                jQuery('.tutorial-tabs-head .status-item.tabs.wc-tabs').toggleClass('toggled');
            }
        });
        // comment show
        jQuery('#comments-list-title').click(function(){
            jQuery(this).parent().toggleClass('mshow');
        });
        // support loading page swiper
        supportslider('down-swiper-container');
        supportslider('tutorial-swiper-container');
        supportslider('faq-swiper-container');
        // my account menu
        if(jQuery('.woocommerce-MyAccount-navigation-items-cont').length){
            // console.log('1');
            var account_center_menu=jQuery('.woocommerce-MyAccount-navigation-items-cont');
            var ulwidth=1;
            account_center_menu.find('li.woocommerce-MyAccount-navigation-link').each(function(){
                ulwidth=ulwidth*1+Math.ceil(jQuery(this).width())*1;
            });
            jQuery('.woocommerce-MyAccount-navigation-items-cont ul').width(ulwidth);
            if(account_center_menu.find('li.is-active').length)
            {
              account_center_menu.scrollLeft(account_center_menu.find('li.is-active').offset().left);  
            } 
        }
    }
    
});
} )();
