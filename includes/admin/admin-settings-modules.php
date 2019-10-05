<?php
$modules = pp_elements_lite_get_modules();
$enabled_modules = pp_elements_lite_get_enabled_modules();
$settings= self::get_settings();
?>

<div class="pp-modules-wrap">
		<div class="form-table">
			<div class="pp-module-grid">				
					<div class="pp-modules-title" valign="top">
						<?php esc_html_e('Enable/Disable Widgets', 'power-pack'); ?>
					</div>
				<div class="pp-modules-list">
					<?php
					foreach ( $modules as $module_name => $module ) :
						$module_enabled = in_array( $module_name, $enabled_modules ) || isset( $enabled_modules[$module_name] );
					?>
					<div class="pp-module <?php echo $module['title']; ?>">
						<div class="pp-module-name-icon-wrapper">
							<div class="pp-module-icon <?php 
									if( isset($module['icon']) ) {
										echo $module['icon'] ;
									}
									else{
										echo preg_replace('/(pp)/','ppicon', $module_name);
									}							 
								?>">
							</div>
							<p class="pp-module-name"><?php echo $module['title']; ?></p>
						</div>
						<label class="pp-admin-field-toggle"for="<?php echo $module['title']; ?>">
							<input
								id="<?php echo $module['title']; ?>"
								name="pp_enabled_modules[]"
								type="checkbox"
								value="<?php echo $module['title']; ?>"
								<?php echo $module_enabled ? ' checked="checked"' : '' ?>
							/>
							<span class="pp-admin-field-toggle-slider"></span>							
						</label>
					</div>
					<?php endforeach; ?>
				</div>
				<?php wp_nonce_field('pp-modules-settings', 'pp-modules-settings-nonce'); ?>
				<?php submit_button( __('Save Settings'), 'pp-submit-button'); ?>
			</div>
			<!-- End pp-module-list -->
			<!-- Start pro-upgrade-banner -->
	<div class="side-banner">
		<div class="banner-inner pro-upgrade-banner">
			<div class="banner-image"><img src="<?php echo POWERPACK_ELEMENTS_LITE_URL . 'assets/images/pp-elements-logo.svg'; ?>" /></div>
				<h3 class="banner-title-1"><?php _e('Get access to more premium widgets and features.', 'power-pack'); ?></h3>
				<h3 class="banner-title-2"><?php _e('Upgrade to <strong>PowerPack Pro</strong> and get <strong>15%</strong> OFF', 'power-pack'); ?></h3>
				<ul>
					<li><span class="dashicons dashicons-yes"></span><?php esc_html_e('More Widgets', 'power-pack'); ?></li>
					<li><span class="dashicons dashicons-yes"></span><?php esc_html_e('White Label Branding', 'power-pack'); ?></li>
					<li><span class="dashicons dashicons-yes"></span><?php esc_html_e('Expert Support', 'power-pack'); ?></li>
					<li><span class="dashicons dashicons-yes"></span><?php esc_html_e('Lifetime package available', 'power-pack'); ?></li>
				</ul>
				<div class="banner-action"><a href="https://powerpackelements.com/upgrade/?utm_medium=pp-elements-lite&utm_source=pp-settings&utm_campaign=pp-pro-upgrade" class="pp-button" target="_blank" title="<?php _e('Upgrade to PowerPack Pro'); ?>"><?php _e('Upgrade Now', 'power-pack'); ?></a></div>
			</div>
			<div class="banner-inner pp-support pro-upgrade-banner">
				<div class="banner-image">
					<img src="<?php echo POWERPACK_ELEMENTS_LITE_URL . 'assets/images/pp-elements-logo.svg'; ?>" />
					<h4 class="pp-support-title">Documentation</h4>
				</div>
				<p>Checkout the extensive documentation on basic troubleshooting, how-tos, widget overviews.</p>
				<div class="banner-action"><a href="https://powerpackelements.com/docs/" class="pp-button" target="_blank" title="<?php _e('Read Documentation'); ?>"><?php _e('Read Documentation', 'power-pack'); ?></a></div>
			</div>
			<div class="banner-inner pp-support pro-upgrade-banner">
				<div class="banner-image">
					<img src="<?php echo POWERPACK_ELEMENTS_LITE_URL . 'assets/images/pp-elements-logo.svg'; ?>" />
					<h4 class="pp-support-title">Feedback</h4>
				</div>
				<?php
				$support_link = $settings['support_link'];
				$support_link = !empty( $support_link ) ? $support_link : 'https://powerpackelements.com/contact/'; ?>
				<p><b style="font-size: 14px;"><?php esc_html_e('Have something so say about PowerPack?')?></b><br/><br/><?php esc_html_e('Click on the button below to submit queries, feedback, bug reports or feature requests now.', 'power-pack'); ?> </p>
				<div class="banner-action">
					<a href="<?php echo $support_link; ?>" class="pp-button" target="_blank" title="<?php _e('Submit Feedback', 'power-pack');?>">
						<?php _e('Submit Feedback', 'power-pack'); ?>
					</a>
				</div>
			</div>
		</div>
	</div>	
</div>

