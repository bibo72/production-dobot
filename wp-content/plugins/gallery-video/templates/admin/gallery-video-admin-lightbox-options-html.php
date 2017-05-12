<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="wrap">
	<?php require(GALLERY_VIDEO_TEMPLATES_PATH.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'gallery-video-admin-free-banner.php');?>
	<p class="pro_info">
		<?php echo __('These features are available in the Professional version of the plugin only.', 'gallery-video'); ?>
		<a href="http://huge-it.com/wordpress-video-gallery/" target="_blank" class="button button-primary"><?php echo __('Enable','gallery-images'); ?></a>
	</p>
	<div style="clear:both;"></div>
	<div id="poststuff">
		<div id="post-body-content" class="videogallery-options">
			<div id="post-body-heading">
				<h3>Lightbox Options</h3>
				<a href="#" class="save-videogallery-options button-primary">Save</a>
			</div>
			<div class="gallery_options_grey_overlay"></div>
			<img style="width: 100%;" src="<?php echo GALLERY_VIDEO_IMAGES_URL.'/admin_images/'; ?>/lightbox_opt.png">
			<input type="hidden" name="option" value=""/>
			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="controller" value="options"/>
			<input type="hidden" name="op_type" value="styles"/>
			<input type="hidden" name="boxchecked" value="0"/>
		</div>
	</div>
</div>