<?php
if(is_user_logged_in()) {
    $current_user = wp_get_current_user();
    $user_email =  $current_user->user_email;
    $user_name  =  $current_user->user_login;
    $user_image =  get_avatar($user_email, 30);
    $items = wc_get_account_menu_items();
    foreach ($items as $endpoint=>$_item){
        if($endpoint == 'inventions' || $endpoint == 'tutorial' || $endpoint == 'contest'){
            $url = esc_url( wc_get_account_endpoint_url( $endpoint ) );
            $label = $_item;
            $active = substr_count($_SERVER['REQUEST_URI'],$endpoint) ? "is-active" : "";
            $menu[] = array('url'=>$url,'label'=>$label,'active'=>$active);
        }
    }
}
?>
<div class="account-head">
	<div class="account-head-slider line0 textcenter"><img src="<?php echo WC()->plugin_url() . '/assets/images/account_setting.jpg'; ?>" /></div>
	<div class="account-head-menu-cont">
		<div class="col-full">
			<ul class="account-head-menu">
				<li class="account-head-menu-items itme-name"><?php echo $user_name ?></li>
				<li class="account-head-menu-items itme-photo">
					<?php echo $user_image?>
					<img src="<?php echo WC()->plugin_url() . '/assets/images/love.png'; ?>" class="love"/>
				</li>
                <?php foreach ($menu as $key=> $_menu){?>
                    <li class="account-head-menu-items <?php echo $key==0 ? 'begin' :''?>
                        <?php echo $_menu['active']?>">
                        <a href="<?php echo $_menu['url']?>">
                            <?php  echo  $_menu['label'];?>
                        </a>
                    </li>
                <?php }?>
			</ul>
		</div>
	</div>
</div>