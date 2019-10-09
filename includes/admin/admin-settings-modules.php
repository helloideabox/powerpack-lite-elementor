<?php

/*$modules = pp_elements_lite_get_modules();
$enabled_modules = pp_elements_lite_get_enabled_modules();*/

$modules            = pp_elements_lite_get_modules();
$extensions         = pp_elements_lite_get_extensions();
$enabled_modules    = pp_elements_lite_get_enabled_modules();
$enabled_extensions = pp_elements_lite_get_enabled_extensions();
?>
<style>
.pp-modules-wrap:before,
.pp-modules-wrap:after {
	content: "";
	display: table;
}
.pp-modules-wrap:after {
	clear: both;
}
.form-table {
	max-width: 450px;
	float: left;
}
.pro-upgrade-banner {
	width: 320px;
	height: 320px;
	float: right;
	margin-top: 40px;
	background: #fff;
    border: 1px solid #eee;
	box-shadow: 1px 1px 8px 1px rgba(0,0,0,0.1);
	padding: 20px;
	border-top: 2px solid #5353dc;
}
.pro-upgrade-banner,
.pro-upgrade-banner * {
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
.pro-upgrade-banner .banner-image {
	text-align: center;
}
.pro-upgrade-banner img {
	max-width: 100%;
	width: 120px;
}
.pro-upgrade-banner h3 {
	font-weight: 400;
    line-height: 1.4;
	margin-bottom: 0;
}
.pro-upgrade-banner .banner-title-1 {
	font-size: 22px;
    font-weight: 300;
	display: none;
}
.pro-upgrade-banner li {
	font-size: 15px;
}
.pro-upgrade-banner li span {
	margin-right: 5px;
}
.pro-upgrade-banner .banner-action {
	text-align: center;
}
.pro-upgrade-banner a.pp-button {
	display: inline-block;
	text-align: center;
	margin-top: 10px;
	text-decoration: none;
    background: #5353dc;
    color: #fff;
    padding: 10px 20px;
    border-radius: 50px;
}
.pro-upgrade-banner a.pp-button:hover {
    background: #4242ce;
}
</style>
<div class="pp-modules-wrap">
	<table class="form-table">
	<tr valign="top">
		<th scope="row" valign="top">
			<?php esc_html_e('Enable/Disable Extensions', 'powerpack'); ?>
		</th>
		<td>
			<?php
			foreach ( $extensions as $extension_name => $extension_title ) :
				if ( ! is_array( $enabled_extensions ) && 'disabled' !== $enabled_extensions ) {
					$extension_enabled = true;
				} elseif ( ! is_array( $enabled_extensions ) && 'disabled' === $enabled_extensions ) {
					$extension_enabled = false;
				} else {
					$extension_enabled = in_array( $extension_name, $enabled_extensions ) || isset( $enabled_extensions[ $extension_name ] );
				}
				?>
			<p>
				<label for="<?php echo $extension_name; ?>">
					<input
						id="<?php echo $extension_name; ?>"
						name="pp_enabled_extensions[]"
						type="checkbox"
						value="<?php echo $extension_name; ?>"
						<?php echo $extension_enabled ? ' checked="checked"' : '' ?>
					/>
						<?php echo $extension_title; ?>
				</label>
			</p>
			<?php endforeach; ?>
		</td>
	</tr>
		<tr valign="top">
			<th scope="row" valign="top">
				<?php esc_html_e('Enable/Disable Widgets', 'power-pack'); ?>
			</th>
			<td>
				<?php
				foreach ( $modules as $module_name => $module_title ) :
					if ( ! is_array( $enabled_modules ) && 'disabled' !== $enabled_modules ) {
						$module_enabled = true;
					} elseif ( ! is_array( $enabled_modules ) && 'disabled' === $enabled_modules ) {
						$module_enabled = false;
					} else {
						$module_enabled = in_array( $module_name, $enabled_modules ) || isset( $enabled_modules[ $module_name ] );
					}
				?>
				<p>
					<label for="<?php echo $module_name; ?>">
						<input
							id="<?php echo $module_name; ?>"
							name="pp_enabled_modules[]"
							type="checkbox"
							value="<?php echo $module_name; ?>"
							<?php echo $module_enabled ? ' checked="checked"' : '' ?>
						/>
							<?php echo $module_title; ?>
					</label>
				</p>
				<?php endforeach; ?>
			</td>
		</tr>
	</table>

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
<?php wp_nonce_field('pp-modules-settings', 'pp-modules-settings-nonce'); ?>
<?php submit_button(); ?>
