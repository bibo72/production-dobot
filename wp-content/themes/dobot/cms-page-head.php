<?php 
	$curenturl=get_permalink(get_the_ID());
	$newsurl=get_page_url( 'news-events' );
	$abouturl=get_page_url( 'about-us' );
	$contacturl=get_page_url( 'contact-us' );
	$joinusurl=get_page_url( 'join-us' );
	$partnershipurl=get_page_url( 'partnership' );
?>
<div class="pro-tab-cont cms-pro-tab-cont">
    <div class="col-full">
        <div class="col-full-cont clearbox">
            <div class="pro-title">Company info</div>
            <div class="pro-tab-cont-items">
                <ul class="tabs wc-tabs">
                	<?php if($curenturl==$newsurl):?>
                		<li class="active"><?php _e("News &amp; Event")?></li>
                	<?php else:?>
                		<li class=""><a href="<?php echo $newsurl;?>"><?php _e("News &amp; Event")?></a></li>
                	<?php endif;?>
        			<?php if($curenturl==$abouturl):?>
                		<li class="active"><?php _e("About Us")?></li>
                	<?php else:?>
                		<li class=""><a href="<?php echo $abouturl;?>"><?php _e("About Us")?></a></li>
                	<?php endif;?>
                	<?php if($curenturl==$contacturl):?>
                		<li class="active"><?php _e("Contact Us")?></li>
                	<?php else:?>
                		<li class=""><a href="<?php echo $contacturl;?>"><?php _e("Contact Us")?></a></li>
                	<?php endif;?>
                	<?php if($curenturl==$joinusurl):?>
                		<li class="active"><?php _e("Join Us")?></li>
                	<?php else:?>
                		<li class=""><a href="<?php echo $joinusurl;?>"><?php _e("Join Us")?></a></li>
                	<?php endif;?>
                	<?php if($curenturl==$partnershipurl):?>
                		<li class="active"><?php _e("Partnership")?></li>
                	<?php else:?>
                		<li class=""><a href="<?php echo $partnershipurl;?>"><?php _e("Partnership")?></a></li>
                	<?php endif;?>
                </ul>
            </div>
        </div>
    </div>
</div>