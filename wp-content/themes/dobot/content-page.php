<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package storefront
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?> >
	


	<?php
	/**
	 * Functions hooked in to storefront_page add_action
	 *
	 * @hooked storefront_page_header          - 10
	 * @hooked storefront_page_content         - 20
	 * @hooked storefront_init_structured_data - 30
	 */
	do_action( 'storefront_page' );
	?>
</div><!-- #post-## -->
<script type="text/javascript">
jQuery(function(){
	if(jQuery("#carousel1").length){
		var xpos=jQuery('#carousel1').width()/2;
		jQuery("#carousel1").CloudCarousel({			
			xPos:xpos,
			yPos:0,
			buttonLeft: jQuery('#but1'),
			buttonRight: jQuery('#but2'),				
			FPS:30,
			reflHeight:40,
			reflGap:0,
			xRadius:xpos,
			yRadius:10,
			autoRotateDelay: 1200,
			speed:0.2,
			bringToFront:true
		});
	}
	
});

</script>