<?php

$modules = pp_elements_lite_get_modules();
$enabled_modules = pp_elements_lite_get_enabled_modules();
?>

<div class="pp-modules-wrap">
		<div class="form-table">
			<div class="pp-module-grid">
				<div class="pp-modules-title" valign="top">
					<?php esc_html_e('Enable/Disable Widgets', 'power-pack'); ?>
				</div>
				
				<div class="pp-modules-list">
					<?php
					foreach ( $modules as $module_name => $module_title ) :
						$module_enabled = in_array( $module_name, $enabled_modules ) || isset( $enabled_modules[$module_name] );
					?>
					<div class="pp-module">
					<p class="pp-module-name"><?php echo $module_title; ?></p>
						<label class="pp-admin-field-toggle"for="<?php echo $module_name; ?>">
							<input
								id="<?php echo $module_name; ?>"
								name="pp_enabled_modules[]"
								type="checkbox"
								value="<?php echo $module_name; ?>"
								<?php echo $module_enabled ? ' checked="checked"' : '' ?>
							/>
							<span class="pp-admin-field-toggle-slider"></span>							
						</label>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
			<!-- End pp-module-list -->
			<!-- Start pro-upgrade-banner -->
	<div class="pro-upgrade-banner">
		<div class="banner-inner">
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
		</div>
	</div>
</div>
<?php wp_nonce_field('pp-modules-settings', 'pp-modules-settings-nonce'); ?>
<?php submit_button(); ?>
