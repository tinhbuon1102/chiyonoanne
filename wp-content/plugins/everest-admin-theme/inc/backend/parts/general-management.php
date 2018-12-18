<div class='eat-tab-content eat-tab-general-management' style='display: none;'>
	<div class="eat-tab-content-header">
		<div class='eat-tab-content-header-title'><?php _e('General Management' , 'everest-admin-theme'); ?></div>
	</div>
	<div class='eat-tab-content-body'>
		<div class="eat-general-settings-options-wrap eat-options-wrap-outer">
				<div class="eat-select-options-wrap eat-admin-menu-header-wrap">
					<div class="eat-options-wrap">
						<label for="eat-background-option"><?php _e('Admin menu header settings', 'everest-admin-theme'); ?></label>
						<div class="eat-input-field-wrap eat-background-select-wrap">
							<select id='eat-background-options' name='everest_admin_theme[general-settings][admin-menu-header][type]' class="eat-selectbox-wrap eat-select-options ">
								<option value='' ><?php _e( 'None', 'everest-admin-theme' ); ?></option>
								<option value='image' <?php selected( $plugin_settings['general-settings']['admin-menu-header']['type'], 'image' ); ?> > <?php _e( 'Image', 'everest-admin-theme'); ?></option>
								<option value='texts' <?php selected( $plugin_settings['general-settings']['admin-menu-header']['type'], 'texts' ); ?>><?php _e( 'Texts', 'everest-admin-theme'); ?></option>
							</select>
						</div>
					</div>

					<div class="eat-select-content-wrap">
						<div class="eat-background-image-content-wrap eat-image eat-common-content-wrap" style="display: <?php if(isset($plugin_settings['general-settings']['admin-menu-header']['type']) && $plugin_settings['general-settings']['admin-menu-header']['type'] =='image' ){ ?> block; <?php }else{ ?> none; <?php } ?>">
							<div class="eat-image-selection-wrap">
								<div class="eat-options-wrap">
									<label for="eat-background-image-url"><?php _e( 'Image Upload: ', 'everest-admin-theme' ); ?></label>
									<div class="eat-input-field-wrap">
										<input type="text" id='eat-background-image-url' name='everest_admin_theme[general-settings][admin-menu-header][image][url]' class='eat-image-upload-url' value='<?php if(isset($plugin_settings['general-settings']['admin-menu-header']['image']['url']) && $plugin_settings['general-settings']['admin-menu-header']['image']['url'] != '' ){ echo $plugin_settings['general-settings']['admin-menu-header']['image']['url']; } ?>' />
										<input type="button" class='eat-button eat-image-upload-button' value='<?php _e('Upload Image', 'everest-admin-theme'); ?>' />
									</div>
								</div>
								<div class='eat-image-preview eat-image-placeholder'>
									<img src='<?php if(isset($plugin_settings['general-settings']['admin-menu-header']['image']['url']) && $plugin_settings['general-settings']['admin-menu-header']['image']['url'] != '' ){ echo $plugin_settings['general-settings']['admin-menu-header']['image']['url']; } ?>' />
								</div>
							</div>
						</div>

						<div class="eat-background-color-content eat-texts eat-common-content-wrap" style="display: <?php if(isset($plugin_settings['general-settings']['admin-menu-header']['type']) && $plugin_settings['general-settings']['admin-menu-header']['type'] =='texts' ){ ?> block; <?php }else{ ?> none; <?php } ?>">
							<div class="eat-background-color-content-wrap">
								<div class="eat-options-wrap">
									<label for="eat-background-background-color"><?php _e('Title', 'everest-admin-theme' ); ?></label>
									<input id='eat-background-background-color' type="text" name='everest_admin_theme[general-settings][admin-menu-header][text][title][text]' class='' value='<?php if(isset($plugin_settings['general-settings']['admin-menu-header']['text']['title']['text']) && $plugin_settings['general-settings']['admin-menu-header']['text']['title']['text'] != '' ){ echo $plugin_settings['general-settings']['admin-menu-header']['text']['title']['text']; } ?>' />
								</div>
								<div class="eat-options-wrap">
									<label for="eat-background-background-color"><?php _e('Sub Title', 'everest-admin-theme' ); ?></label>
									<input id='eat-background-background-color' type="text" name='everest_admin_theme[general-settings][admin-menu-header][text][subtitle][text]' class='' value='<?php if(isset($plugin_settings['general-settings']['admin-menu-header']['text']['subtitle']['text']) && $plugin_settings['general-settings']['admin-menu-header']['text']['subtitle']['text'] != '' ){ echo $plugin_settings['general-settings']['admin-menu-header']['text']['subtitle']['text']; } ?>' />
								</div>

								<div class='eat-font-settings'>
									<div class="eat-style-label">Title Font Settings</div>
									<div class="eat-options-wrap">
										<label for='eat-general-settings'><?php _e('Font Family', 'everest-admin-theme'); ?></label>
										<div class="eat-item-input-field-wrap eat-input-field-wrap">
											<select id='eat-google-fonts' name='everest_admin_theme[general-settings][admin-menu-header][text][title][font-settings][google-fonts]' class="eat-selectbox-wrap">
												<option value=''><?php esc_html_e('Default', 'everest-admin-theme' ); ?></option>
												<?php
												foreach($google_fonts as $key=>$value){
													?>
													<option value='<?php echo $value; ?>' <?php if(isset($plugin_settings['general-settings']['admin-menu-header']['text']['title']['font-settings']['google-fonts'])){selected( $plugin_settings['general-settings']['admin-menu-header']['text']['title']['font-settings']['google-fonts'], $value ); } ?>><?php echo $value; ?></option>
													<?php
												}
												?>
											</select>
										</div>
									</div>
									<div class="eat-options-wrap">
										<label for='eat-general-settings'><?php _e('Font color', 'everest-admin-theme'); ?></label>
										<input id='ec-background-background-color' type="text" name='everest_admin_theme[general-settings][admin-menu-header][text][title][font-settings][font-color]' class='eat-color-picker' data-alpha="true" value="<?php if(isset($plugin_settings['general-settings']['admin-menu-header']['text']['title']['font-settings']['font-color']) && $plugin_settings['general-settings']['admin-menu-header']['text']['title']['font-settings']['font-color'] != '' ){ echo $plugin_settings['general-settings']['admin-menu-header']['text']['title']['font-settings']['font-color']; } ?>" />
									</div>
								</div>

								<div class='eat-font-settings'>
									<div class="eat-style-label"><?php _e( 'Sub Title Font Settings', 'everest-admin-theme' ); ?></div>
									<div class="eat-options-wrap">
										<label for='eat-general-settings'><?php _e( 'Font Family', 'everest-admin-theme' ); ?></label>
										<div class="eat-item-input-field-wrap eat-input-field-wrap">
											<select id='eat-google-fonts' name='everest_admin_theme[general-settings][admin-menu-header][text][subtitle][font-settings][google-fonts]' class="eat-selectbox-wrap">
												<option value=''><?php esc_html_e('Default', 'everest-admin-theme' ); ?></option>
												<?php
												foreach($google_fonts as $key=>$value){
													?>
													<option value='<?php echo $value; ?>' <?php if(isset($plugin_settings['general-settings']['admin-menu-header']['text']['subtitle']['font-settings']['google-fonts'])){selected( $plugin_settings['general-settings']['admin-menu-header']['text']['subtitle']['font-settings']['google-fonts'], $value ); } ?>><?php echo $value; ?></option>
													<?php
												}
												?>
											</select>
										</div>
									</div>
									<div class="eat-options-wrap">
										<label for='eat-general-settings'><?php _e('Font color', 'everest-admin-theme'); ?></label>
										<input id='ec-background-background-color' type="text" name='everest_admin_theme[general-settings][admin-menu-header][text][subtitle][font-settings][font-color]' class='eat-color-picker' data-alpha="true" value="<?php if(isset($plugin_settings['general-settings']['admin-menu-header']['text']['subtitle']['font-settings']['font-color']) && $plugin_settings['general-settings']['admin-menu-header']['text']['subtitle']['font-settings']['font-color'] != '' ){ echo $plugin_settings['general-settings']['admin-menu-header']['text']['subtitle']['font-settings']['font-color']; } ?>" />
									</div>
								</div>

							</div>
						</div>

						<div class="eat-admin-menu-header-background-settings eat-common-content-wrap eat-background" style="display:<?php if(isset($plugin_settings['general-settings']['admin-menu-header']['type']) && ($plugin_settings['general-settings']['admin-menu-header']['type'] =='image' || $plugin_settings['general-settings']['admin-menu-header']['type'] =='texts' )){ ?> block; <?php }else{ ?> none; <?php } ?>">
							<div class="eat-substyle-label"><?php _e("Background Settings", 'everest-admin-theme'); ?></div>
							<div class="eat-background-color-content-wrap">
								<div class="eat-options-wrap">
									<label for="eat-background-background-color"><?php _e('Background Color', 'everest-admin-theme' ); ?></label>
									<input id='eat-background-background-color' type="text" name='everest_admin_theme[general-settings][admin-menu-header][background-color][color]' class='eat-color-picker' data-alpha="true" value='<?php if(isset($plugin_settings['general-settings']['admin-menu-header']['background-color']['color']) && $plugin_settings['general-settings']['admin-menu-header']['background-color']['color'] != '' ){ echo $plugin_settings['general-settings']['admin-menu-header']['background-color']['color']; } ?>' />
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="eat-style-label"><?php _e("Dashboard Settings", 'everest-admin-theme'); ?></div>
				<div class="eat-options-wrap">
					<label for="eat-background-option"><?php _e('Background selection', 'everest-admin-theme'); ?></label>
					<div class="eat-input-field-wrap eat-background-select-wrap">
						<select id='eat-background-options' name='everest_admin_theme[general-settings][background][type]' class="eat-selectbox-wrap eat-select-options eat-background-selector">
							<option value='' ><?php _e( 'Default', 'everest-admin-theme' ); ?></option>
							<option value='image' <?php selected( $plugin_settings['general-settings']['background']['type'], 'image' ); ?> > <?php _e( 'Image', 'everest-admin-theme'); ?></option>
							<option value='background-color' <?php selected( $plugin_settings['general-settings']['background']['type'], 'background-color' ); ?>><?php _e( 'Background Color', 'everest-admin-theme'); ?></option>
							<option value='video' <?php selected( $plugin_settings['general-settings']['background']['type'], 'video' ); ?>><?php _e( 'Video', 'everest-admin-theme' ); ?></option>
						</select>
					</div>
				</div>
				<div class="eat-background-select-content">
					<div class="eat-background-image-content-wrap eat-image eat-common-content-wrap" style="display: <?php if(isset($plugin_settings['general-settings']['background']['type']) && $plugin_settings['general-settings']['background']['type'] =='image' ){ ?> block; <?php }else{ ?> none; <?php } ?>">
						<div class="eat-image-selection-wrap">
							<div class="eat-options-wrap">
								<label for="eat-background-image-url"><?php _e( 'Image Upload: ', 'everest-admin-theme' ); ?></label>
								<div class="eat-input-field-wrap">
									<input type="text" id='eat-background-image-url' name='everest_admin_theme[general-settings][background][image][url]' class='eat-image-upload-url' value='<?php if(isset($plugin_settings['general-settings']['background']['image']['url']) && $plugin_settings['general-settings']['background']['image']['url'] != '' ){ echo $plugin_settings['general-settings']['background']['image']['url']; } ?>' />
									<input type="button" class='eat-button eat-image-upload-button' value='<?php _e('Upload Image', 'everest-admin-theme'); ?>' />
								</div>
							</div>
							<div class='eat-image-preview eat-image-placeholder'>
								<img src='<?php if(isset($plugin_settings['general-settings']['background']['image']['url']) && $plugin_settings['general-settings']['background']['image']['url'] != '' ){ echo $plugin_settings['general-settings']['background']['image']['url']; } ?>' />
							</div>
						</div>
					</div>

					<div class="eat-background-color-content eat-background-color eat-common-content-wrap" style="display: <?php if(isset($plugin_settings['general-settings']['background']['type']) && $plugin_settings['general-settings']['background']['type'] =='background-color' ){ ?> block; <?php }else{ ?> none; <?php } ?>">
						<div class="eat-background-color-content-wrap">
							<div class="eat-options-wrap">
								<label for="eat-background-background-color"><?php _e('Background Color', 'everest-admin-theme' ); ?></label>
								<input id='eat-background-background-color' type="text" name='everest_admin_theme[general-settings][background][background-color][color]' class='eat-color-picker' data-alpha="true" value='<?php if(isset($plugin_settings['general-settings']['background']['background-color']['color']) && $plugin_settings['general-settings']['background']['background-color']['color'] != '' ){ echo $plugin_settings['general-settings']['background']['background-color']['color']; } ?>' />
							</div>
						</div>
					</div>

					<div class="eat-video-content eat-video eat-common-content-wrap" style="display: <?php if(isset($plugin_settings['general-settings']['background']['type']) && $plugin_settings['general-settings']['background']['type'] =='video' ){ ?> block; <?php }else{ ?> none; <?php } ?>">
						<div class="eat-background-color-content-wrap eat-video-options-wrap">
							<div class="eat-options-wrap">
								<label for="eat-background-video-type"> <?php _e('Background Video type', 'everest-admin-theme' ); ?></label>
								<div class="eat-input-field-wrap">
									<select id='eat-background-video-type' name='everest_admin_theme[general-settings][background][video][type]' class='eat-selectbox-wrap eat-video-select-option'>
										<option value='youtube' <?php if(isset($plugin_settings['general-settings']['background']['video']['type']) && $plugin_settings['general-settings']['background']['video']['type'] == 'youtube' ){ ?> selected  <?php } ?> ><?php _e('Youtube', 'everest-admin-theme'); ?></option>
										<option value='viemo' 	<?php if(isset($plugin_settings['general-settings']['background']['video']['type']) && $plugin_settings['general-settings']['background']['video']['type'] == 'viemo' ){ ?> selected  <?php } ?> ><?php _e('Viemo', 'everest-admin-theme'); ?></option>
										<option value='html5' 	<?php if(isset($plugin_settings['general-settings']['background']['video']['type']) && $plugin_settings['general-settings']['background']['video']['type'] == 'html5' ){ ?> selected  <?php } ?>><?php _e('HTML5', 'everest-admin-theme'); ?></option>
									</select>
								</div>
							</div>
							<div class="eat-input-field-wrap eat-common-content-wrap-inner eat-youtube-details-input eat-youtube" style='display: <?php if(isset($plugin_settings['general-settings']['background']['video']['type']) && $plugin_settings['general-settings']['background']['video']['type'] =='youtube' ){ ?> block; <?php }else{ ?> none; <?php } ?>'>
								<div class='eat-options-wrap'>
									<label for="eat-background-video-youtube"><?php _e('Youtube Video URL', 'everest-admin-theme'); ?></label>
									<div class="eat-input-field-wrap">
										<input id='eat-background-video-youtube' type="url" name='everest_admin_theme[general-settings][background][video][youtube][video-url]' value='<?php if(isset($plugin_settings['general-settings']['background']['video']['youtube']['video-url']) && $plugin_settings['general-settings']['background']['video']['youtube']['video-url'] != '' ){ echo $plugin_settings['general-settings']['background']['video']['youtube']['video-url']; } ?>'/>
									</div>
								</div>
							</div>

							<div class="eat-input-field-wrap eat-common-content-wrap-inner eat-viemo-details-input eat-viemo" style='display: <?php if(isset($plugin_settings['general-settings']['background']['video']['type']) && $plugin_settings['general-settings']['background']['video']['type'] =='viemo' ){ ?> block; <?php }else{ ?> none; <?php } ?>'>
								<div class="eat-options-wrap">
									<label for="eat-background-video-viemo"><?php _e( 'Viemo Video URL', 'everest-admin-theme' ); ?></label>
									<input id='eat-background-video-viemo' type="url" name='everest_admin_theme[general-settings][background][video][viemo][video-url]' value='<?php if(isset($plugin_settings['general-settings']['background']['video']['viemo']['video-url']) && $plugin_settings['general-settings']['background']['video']['viemo']['video-url'] != '' ){ echo $plugin_settings['general-settings']['background']['video']['viemo']['video-url']; } ?>' />
								</div>
							</div>

							<div class="eat-input-field-wrap eat-common-content-wrap-inner eat-html5-video-details-input eat-html5" style='display: <?php if(isset($plugin_settings['general-settings']['background']['video']['type']) && $plugin_settings['general-settings']['background']['video']['type'] =='html5' ){ ?> block; <?php }else{ ?> none; <?php } ?>'>
								<div class="eat-options-wrap">
									<label for="eat-background-video-html5-video-url"><?php _e( 'MP4 video URL: ', 'everest-admin-theme' ); ?></label>
									<div class="eat-item-input-field-wrap">
										<input type="url" id='eat-background-video-html5-video-url' name='everest_admin_theme[general-settings][background][video][html5][mp4-video-url]' class='eat-image-upload-url' value='<?php if(isset($plugin_settings['general-settings']['background']['video']['html5']['mp4-video-url']) && $plugin_settings['general-settings']['background']['video']['html5']['mp4-video-url'] != '' ){ echo $plugin_settings['general-settings']['background']['video']['html5']['mp4-video-url']; } ?>' />
										<input type="button" class='eat-button eat-image-upload-button' value='<?php _e('Upload Video', 'everest-admin-theme'); ?>' />
									</div>
								</div>
								
								<div class="eat-options-wrap">
									<label for="eat-background-video-html5-video-url"><?php _e( 'WEBM video URL: ', 'everest-admin-theme' ); ?></label>
									<div class="eat-item-input-field-wrap">
										<input type="url" id='eat-background-video-html5-video-url' name='everest_admin_theme[general-settings][background][video][html5][webm-video-url]' class='eat-image-upload-url' value='<?php if(isset($plugin_settings['general-settings']['background']['video']['html5']['webm-video-url']) && $plugin_settings['general-settings']['background']['video']['html5']['webm-video-url'] != '' ){ echo $plugin_settings['general-settings']['background']['video']['html5']['webm-video-url']; } ?>' />
										<input type="button" class='eat-button eat-image-upload-button' value='<?php _e('Upload Video', 'everest-admin-theme'); ?>' />
									</div>
								</div>
								<div class="eat-options-wrap">
									<label for="eat-background-video-html5-video-url"><?php _e( 'OGV video URL: ', 'everest-admin-theme' ); ?></label>
									<div class="eat-item-input-field-wrap">
										<input type="url" id='eat-background-video-html5-video-url' name='everest_admin_theme[general-settings][background][video][html5][ogv-video-url]' class='eat-image-upload-url' value='<?php if(isset($plugin_settings['general-settings']['background']['video']['html5']['ogv-video-url']) && $plugin_settings['general-settings']['background']['video']['html5']['ogv-video-url'] != '' ){ echo $plugin_settings['general-settings']['background']['video']['html5']['ogv-video-url']; } ?>' />
										<input type="button" class='eat-button eat-image-upload-button' value='<?php _e('Upload Video', 'everest-admin-theme'); ?>' />
									</div>
								</div>
							</div>

							<div class='eat-options-wrap'>
								<label for='eat-display-settings-background-video-start-time'><?php _e( 'Video start/end time (sec)', 'everest-admin-theme' ); ?></label>
								<div class="eat-item-input-field-wrap">
									<input type="number" placeholder="Start Time" step="0.01" id='eat-display-settings-background-video-start-time' name='everest_admin_theme[general-settings][background][video][start-time]' class="eat-image-upload-url" value='<?php if(isset($plugin_settings['general-settings']['background']['video']['start-time']) && $plugin_settings['general-settings']['background']['video']['start-time'] != '' ){ echo $plugin_settings['general-settings']['background']['video']['start-time']; } ?>'>
									<input type="number" step="0.01" placeholder="End Time" id='eat-display-settings-background-video-end-time' name='everest_admin_theme[general-settings][background][video][end-time]' class="eat-image-upload-url" value='<?php if(isset($plugin_settings['general-settings']['background']['video']['end-time']) && $plugin_settings['general-settings']['background']['video']['end-time'] != '' ){ echo $plugin_settings['general-settings']['background']['video']['end-time']; } ?>'>
									<div class="input-info"><?php _e("Please enter the start time and end time in seconds for video(these values will be applied for each loop as well.)", 'everest-admin-theme'); ?></div>
								</div>
							</div>
						</div>
					</div>
					<div class="eat-parallax-options-content-wrap eat-no-margin eat-options-wrap eat-common-content-wrap eat-common-content-wrap-all" style="<?php if( isset($plugin_settings['general-settings']['background']['type']) && ($plugin_settings['general-settings']['background']['type'] =='image' || $plugin_settings['general-settings']['background']['type'] =='video' ) ){ ?> display:block; <?php }else{ ?> display: none; <?php } ?>">
							<div class="eat-checkbox-outer-wrap">
								<div class="eat-options-wrap">
									<label for="eat-background-video-parallax"><?php _e( 'Enable Parallax Effect?', 'everest-admin-theme' ); ?></label>
									<input type="checkbox" id='eat-background-video-parallax' name='everest_admin_theme[general-settings][background][parallax][enable]' class='eat-image-overlay-enable-option eat-parallax-enable-option' <?php if(isset($plugin_settings['general-settings']['background']['parallax']['enable'])){ ?> checked <?php } ?> />
									<label for='eat-background-video-parallax'></label>
								</div>

								<div class="eat-checkbox-checked-options" style='display: <?php if(isset($plugin_settings['general-settings']['background']['parallax']['enable'])){ ?> block; <?php }else{ ?> none; <?php } ?>'>
									<div class="eat-options-wrap">
										<label for="eat-parallax-type-select-option"><?php _e( 'Parallax Type', 'everest-admin-theme' ); ?></label>
										<div class="eat-input-field-wrap">
											<select id='eat-parallax-type-select-option' name='everest_admin_theme[general-settings][background][parallax][type]' class='eat-selectbox-wrap eat-parallax-type-select-option'>
												<option value='scroll' <?php if(isset($plugin_settings['general-settings']['background']['parallax']['type']) && $plugin_settings['general-settings']['background']['parallax']['type'] == 'scroll' ){ ?> selected  <?php } ?> ><?php _e('Scroll', 'everest-admin-theme'); ?></option>
												<option value='scale' <?php if(isset($plugin_settings['general-settings']['background']['parallax']['type']) && $plugin_settings['general-settings']['background']['parallax']['type'] == 'scale' ){ ?> selected  <?php } ?> ><?php _e('Scale', 'everest-admin-theme'); ?></option>
												<option value='opacity' <?php if(isset($plugin_settings['general-settings']['background']['parallax']['type']) && $plugin_settings['general-settings']['background']['parallax']['type'] == 'opacity' ){ ?> selected  <?php } ?>><?php _e('Opacity', 'everest-admin-theme'); ?></option>
												<option value='scroll-opacity' <?php if(isset($plugin_settings['general-settings']['background']['parallax']['type']) && $plugin_settings['general-settings']['background']['parallax']['type'] == 'scroll-opacity' ){ ?> selected  <?php } ?> ><?php _e('Scroll Opacity', 'everest-admin-theme'); ?></option>
												<option value='scale-opacity' <?php if(isset($plugin_settings['general-settings']['background']['parallax']['type']) && $plugin_settings['general-settings']['background']['parallax']['type'] == 'scale-opacity' ){ ?> selected  <?php } ?> ><?php _e('Scale Opacity', 'everest-admin-theme'); ?></option>
											</select>
										</div>
									</div>
									<div class="eat-options-wrap eat-image-overlay-color">
										<label for="eat-background-video-overlay-color"><?php _e( 'Speed', 'everest-admin-theme' ); ?></label>
										<div class="eat-item-input-field-wrap">
											<input type="number" step='0.01' min='0' max='2' id='eat-background-video-overlay-color' class='min-max-value' name='everest_admin_theme[general-settings][background][parallax][speed]' data-alpha="true" value='<?php if(isset($plugin_settings['general-settings']['background']['parallax']['speed']) && $plugin_settings['general-settings']['background']['parallax']['speed'] != '' ){ echo $plugin_settings['general-settings']['background']['parallax']['speed']; }else { echo "0.5"; } ?>' />
											<div class="input-info"><?php _e('Please enter the number between 0 and 2', 'everest-admin-theme'); ?></div>
										</div>
									</div>

									<div class="eat-options-wrap eat-image-overlay-color">
										<label for="eat-background-video-enable-parallax-mobile"><?php _e( 'Enable on Mobile devices?', 'everest-admin-theme' ); ?></label>
										<input type="checkbox" id='eat-background-video-enable-parallax-mobile' name='everest_admin_theme[general-settings][background][parallax][enable-on-mobile-devices]' class='eat-enable-parallax-on-mobile-option' <?php if(isset($plugin_settings['general-settings']['background']['parallax']['enable-on-mobile-devices'])){ ?> checked <?php } ?> />
										<label for='eat-background-video-enable-parallax-mobile'></label>
									</div>

								</div>
							</div>

							<div class="eat-checkbox-outer-wrap">
								<div class="eat-options-wrap">
									<label for="eat-background-video-overlay-enable"><?php _e( 'Enable Overlay?', 'everest-admin-theme' ); ?></label>
									<input type="checkbox" id='eat-background-video-overlay-enable' name='everest_admin_theme[general-settings][background][overlay][enable]' class='eat-image-overlay-enable-option' <?php if(isset($plugin_settings['general-settings']['background']['overlay']['enable'])){ ?> checked <?php } ?> />
									<label for='eat-background-video-overlay-enable'></label>
								</div>

								<div class="eat-options-wrap eat-checkbox-checked-options eat-image-overlay-color" style='display: <?php if(isset($plugin_settings['general-settings']['background']['overlay']['enable'])){ ?> block; <?php }else{ ?> none; <?php } ?>'>
									<label for="eat-background-video-overlay-color"><?php _e( 'Overlay Color', 'everest-admin-theme' ); ?></label>
									<input type="text" id='eat-background-video-overlay-color' class='eat-color-picker' name='everest_admin_theme[general-settings][background][overlay][color]' data-alpha="true" value='<?php if(isset($plugin_settings['general-settings']['background']['overlay']['color']) && $plugin_settings['general-settings']['background']['overlay']['color'] != '' ){ echo $plugin_settings['general-settings']['background']['overlay']['color']; } ?>' />
								</div>
							</div>
					</div>
				</div>
		</div>

		<div class="eat-style-label" ><?php _e('Favicon Settings', 'everest-admin-theme'); ?></div>
		<div class="eat-image-selection-wrap">
			<div class="eat-general-settings-options-wrap eat-options-wrap">
				<label for='eat-general-settings'><?php _e('Custom Favicon', 'everest-admin-theme'); ?></label>
				<div class="eat-input-field-wrap">
					<input type="url" id='favicon-upload' name='everest_admin_theme[general-settings][favicon][url]' class='eat-image-upload-url' value="<?php if(isset($plugin_settings['general-settings']['favicon']['url']) && $plugin_settings['general-settings']['favicon']['url'] != '' ){ echo esc_url($plugin_settings['general-settings']['favicon']['url']); } ?>" />
					<input type="button" class='eat-button eat-image-upload-button' value="<?php _e('Upload Image', 'everest-admin-theme'); ?>" />
				</div>
			</div>
			<div class="eat-image-preview eat-image-placeholder">
				<img src="<?php if(isset($plugin_settings['general-settings']['favicon']['url']) && $plugin_settings['general-settings']['favicon']['url'] != '' ){ echo esc_url($plugin_settings['general-settings']['favicon']['url']); } ?>" alt='site favicon'/>
			</div>
		</div>
	</div>
</div>