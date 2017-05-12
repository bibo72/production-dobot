
/*(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

window.fbAsyncInit = function() {
    FB.init({
        appId      : url.user_api,
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        xfbml      : true,  // parse XFBML
        version    : 'v2.4',
        oauth      : true
    });
};

FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
});

function checkLoginState() {
    FB.login(function(response) {
        statusChangeCallback(response);
    },{scope: 'publish_actions,email'});
}

function statusChangeCallback(response) {
    if (response.status === 'connected') {
        FB.api('/me', { locale: 'en_Us', fields: 'name, email, birthday, id' },function (response) {
            window.location.href = url.callback+'&name='+response.name+'&email='+response.email;
        });
    } else{
        checkLoginState();
    }
}*/
function  fb_login(element) {
    var href = jQuery(element).attr('data-href');
    window.open(href,'Facebook Login','status=no,width=600,height=300,left=430,top=0');
    return false;
}

function  twitter_Login(element) {
    var ajax_url = tw_ajax.tw_ajax;
    window.open(ajax_url,'Twitter Login','status=no,width=600,height=300,left=430,top=0');
}