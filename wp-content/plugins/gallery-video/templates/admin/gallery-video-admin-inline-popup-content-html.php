<div id="huge_it_videogallery" style="display:none;">
	<h3><?php _e( "Select Huge IT Video Gallery to insert into post" ); ?></h3>
	<?php
	$change_view_nonce = wp_create_nonce('gallery_video_shortecode_change_view_nonce');
	$insert_shortecode_nonce = wp_create_nonce('gallery_video_insert_shortecode');
	if ( count( $shortcodevideogallerys ) ) {
		echo "<select id='huge_it_videogallery-select' data-change-view-nonce='".$change_view_nonce."'>";
		foreach ( $shortcodevideogallerys as $shortcodevideogallery ) {
			echo "<option value='" . $shortcodevideogallery->id . "'>" . $shortcodevideogallery->name . "</option>";
		}
		echo "</select>";
		echo "<button class='button button-primary' id='hugeitvideogalleryinsert' data-insert-shortecode-nonce='".$insert_shortecode_nonce."'>Insert Video Gallery</button>";
	} else {
		echo "No slideshows found", "huge_it_videogallery";
	}
	?>
	<div id="videogallery-unique-options" class="postbox">
		<h3>Current Video Gallery Options</h3>
		<ul id="videogallery-unique-options-list">
			<li>
				<label for="huge_it_sl_effects">Views</label>
				<select name="huge_it_sl_effects" id="huge_it_sl_effects">
					<option <?php if ( $row->huge_it_sl_effects == '0' ) {
						echo 'selected';
					} ?> value="0">Video Gallery/Content-Popup
					</option>
					<option <?php if ( $row->huge_it_sl_effects == '1' ) {
						echo 'selected';
					} ?> value="1">Content Video Slider
					</option>
					<option <?php if ( $row->huge_it_sl_effects == '5' ) {
						echo 'selected';
					} ?> value="5">Lightbox-Video Gallery
					</option>
					<option <?php if ( $row->huge_it_sl_effects == '3' ) {
						echo 'selected';
					} ?> value="3">Video Slider
					</option>
					<option <?php if ( $row->huge_it_sl_effects == '4' ) {
						echo 'selected';
					} ?> value="4">Thumbnails View
					</option>
					<option <?php if ( $row->huge_it_sl_effects == '6' ) {
						echo 'selected';
					} ?> value="6">Justified
					</option>
					<option <?php if ( $row->huge_it_sl_effects == '7' ) {
						echo 'selected';
					} ?> value="7">Blog Style Gallery
					</option>
				</select>
			</li>
			<div id="videogallery-current-options-0"
			     class="videogallery-current-options <?php if ( $row->huge_it_sl_effects == 0 ) {
				     echo ' active';
			     } ?>">
				<ul id="view4">
					<li>
						<label for="display_type">Displaying Content</label>
						<select id="display_type" name="display_type">
							<option <?php if ( $row->display_type == 0 ) {
								echo 'selected';
							} ?> value="0">Pagination
							</option>
							<option <?php if ( $row->display_type == 1 ) {
								echo 'selected';
							} ?> value="1">Load More
							</option>
							<option <?php if ( $row->display_type == 2 ) {
								echo 'selected';
							} ?> value="2">Show All
							</option>
						</select>
					</li>
					<li id="content_per_page">
						<label for="content_per_page">Videos Per Page</label>
						<input type="number" name="content_per_page" id="content_per_page"
						       value="<?php echo $row->content_per_page; ?>" class="text_area"/>
					</li>
				</ul>
			</div>
			<div id="videogallery-current-options-3"
			     class="videogallery-current-options <?php if ( $row->huge_it_sl_effects == 3 ) {
				     echo ' active';
			     } ?>">
				<ul id="slider-unique-options-list">
					<li>
						<label for="sl_width">Width</label>
						<input type="number" name="sl_width" id="sl_width" value="<?php echo $row->sl_width; ?>"
						       class="text_area"/>
					</li>
					<li>
						<label for="sl_height">Height</label>
						<input type="number" name="sl_height" id="sl_height" value="<?php echo $row->sl_height; ?>"
						       class="text_area"/>
					</li>
					<li>
						<label for="pause_on_hover">Pause on hover</label>
						<input type="checkbox" name="pause_on_hover" value="<?php echo $row->pause_on_hover; ?>"
						       id="pause_on_hover" <?php if ( $row->pause_on_hover == 'on' ) {
							echo 'checked="checked"';
						} ?> />
					</li>
					<li>
						<label for="videogallery_list_effects_s">Effects</label>
						<select name="videogallery_list_effects_s" id="videogallery_list_effects_s">
							<option <?php if ( $row->videogallery_list_effects_s == 'none' ) {
								echo 'selected';
							} ?> value="none">None
							</option>
							<option <?php if ( $row->videogallery_list_effects_s == 'cubeH' ) {
								echo 'selected';
							} ?> value="cubeH">Cube Horizontal
							</option>
							<option <?php if ( $row->videogallery_list_effects_s == 'cubeV' ) {
								echo 'selected';
							} ?> value="cubeV">Cube Vertical
							</option>
							<option <?php if ( $row->videogallery_list_effects_s == 'fade' ) {
								echo 'selected';
							} ?> value="fade">Fade
							</option>
						</select>
					</li>
					<li>
						<label for="sl_pausetime">Pause time</label>
						<input type="number" name="sl_pausetime" id="sl_pausetime"
						       value="<?php echo $row->description; ?>" class="text_area"/>
					</li>
					<li>
						<label for="sl_changespeed">Change speed</label>
						<input type="number" name="sl_changespeed" id="sl_changespeed"
						       value="<?php echo $row->param; ?>" class="text_area"/>
					</li>
					<li>
						<label for="slider_position">Slider Position</label>
						<select name="sl_position" id="slider_position">
							<option <?php if ( $row->sl_position == 'left' ) {
								echo 'selected';
							} ?> value="left">Left
							</option>
							<option <?php if ( $row->sl_position == 'right' ) {
								echo 'selected';
							} ?> value="right">Right
							</option>
							<option <?php if ( $row->sl_position == 'center' ) {
								echo 'selected';
							} ?> value="center">Center
							</option>
						</select>
					</li>
				</ul>
			</div>
			<div id="videogallery-current-options-4"
			     class="videogallery-current-options <?php if ( $row->huge_it_sl_effects == 4 ) {
				     echo ' active';
			     } ?>">
				<ul id="view4">
					<li>
						<label for="display_type">Displaying Content</label>
						<select id="display_type" name="display_type">
							<option <?php if ( $row->display_type == 0 ) {
								echo 'selected';
							} ?> value="0">Pagination
							</option>
							<option <?php if ( $row->display_type == 1 ) {
								echo 'selected';
							} ?> value="1">Load More
							</option>
							<option <?php if ( $row->display_type == 2 ) {
								echo 'selected';
							} ?> value="2">Show All
							</option>
						</select>
					</li>
					<li id="content_per_page">
						<label for="content_per_page">Videos Per Page</label>
						<input type="number" name="content_per_page" id="content_per_page"
						       value="<?php echo $row->content_per_page; ?>" class="text_area"/>
					</li>
				</ul>
			</div>
			<div id="videogallery-current-options-5"
			     class="videogallery-current-options <?php if ( $row->huge_it_sl_effects == 5 ) {
				     echo ' active';
			     } ?>">
				<ul id="view4">
					<li>
						<label for="display_type">Displaying Content</label>
						<select id="display_type" name="display_type">
							<option <?php if ( $row->display_type == 0 ) {
								echo 'selected';
							} ?> value="0">Pagination
							</option>
							<option <?php if ( $row->display_type == 1 ) {
								echo 'selected';
							} ?> value="1">Load More
							</option>
							<option <?php if ( $row->display_type == 2 ) {
								echo 'selected';
							} ?> value="2">Show All
							</option>
						</select>
					</li>
					<li id="content_per_page">
						<label for="content_per_page">Videos Per Page</label>
						<input type="number" name="content_per_page" id="content_per_page"
						       value="<?php echo $row->content_per_page; ?>" class="text_area"/>
					</li>
				</ul>
			</div>
			<div id="videogallery-current-options-6"
			     class="videogallery-current-options <?php if ( $row->huge_it_sl_effects == 6 ) {
				     echo ' active';
			     } ?>">
				<ul id="view4">
					<li>
						<label for="display_type">Displaying Content</label>
						<select id="display_type" name="display_type">
							<option <?php if ( $row->display_type == 0 ) {
								echo 'selected';
							} ?> value="0">Pagination
							</option>
							<option <?php if ( $row->display_type == 1 ) {
								echo 'selected';
							} ?> value="1">Load More
							</option>
							<option <?php if ( $row->display_type == 2 ) {
								echo 'selected';
							} ?> value="2">Show All
							</option>
						</select>
					</li>
					<li id="content_per_page">
						<label for="content_per_page">Videos Per Page</label>
						<input type="number" name="content_per_page" id="content_per_page"
						       value="<?php echo $row->content_per_page; ?>" class="text_area"/>
					</li>
				</ul>
			</div>
			<div id="videogallery-current-options-7"
			     class="videogallery-current-options <?php if ( $row->huge_it_sl_effects == 7 ) {
				     echo ' active';
			     } ?>">
				<ul id="view7">
					<li>
						<label for="display_type">Displaying Content</label>
						<select id="display_type" name="display_type">
							<option <?php if ( $row->display_type == 0 ) {
								echo 'selected';
							} ?> value="0">Pagination
							</option>
							<option <?php if ( $row->display_type == 1 ) {
								echo 'selected';
							} ?> value="1">Load More
							</option>
							<option <?php if ( $row->display_type == 2 ) {
								echo 'selected';
							} ?> value="2">Show All
							</option>
						</select>
					</li>
					<li id="content_per_page">
						<label for="content_per_page">Videos Per Page</label>
						<input type="number" name="content_per_page" id="content_per_page"
						       value="<?php echo $row->content_per_page; ?>" class="text_area"/>
					</li>
				</ul>
			</div>
		</ul>
	</div>
</div>