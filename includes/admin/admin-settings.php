<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use PowerpackElementsLite\Classes\PP_Admin_Settings;
?>
<style>
#wpcontent {
	padding: 0;
}
#footer-left {
	display: none;
}
.pp-settings-wrap {
	margin: 0;
}
.pp-settings-wrap * {
	box-sizing: border-box;
}
.pp-notices-target {
	margin: 0;
}
.pp-settings-header {
	display: flex;
	align-items: center;
	padding: 0 20px;
	background: #fff;
	box-shadow: 0 1px 8px 0 rgba(0,0,0,0.05);
	position: relative;
}
.pp-settings-header h3 {
	margin: 0;
	font-weight: 500;
}
.pp-settings-header h3 .dashicons {
	color: #a2a2a2;
	vertical-align: text-bottom;
}
.pp-settings-version {
	position: absolute;
	right: 20px;
}
.pp-settings-tabs {
	margin-left: 30px;
}
.pp-settings-tabs a,
.pp-settings-tabs a:hover,
.pp-settings-tabs a.nav-tab-active {
	background: none;
	border: none;
	box-shadow: none;
}
.pp-settings-tabs a {
	font-weight: 500;
	padding: 0 10px;
	color: #5f5f5f;
}
.pp-settings-tabs a.nav-tab-active {
	color: #333;
}
.pp-settings-tabs a > span {
	display: block;
	padding: 10px 0;
	border-bottom: 3px solid transparent;
}
.pp-settings-tabs a.nav-tab-active > span {
	border-bottom: 3px solid #0073aa;
}
.pp-settings-content {
	padding: 20px;
}
.pp-settings-content #pp-settings-form {
	background: #fff;
	padding: 10px 30px;
	box-shadow: 1px 1px 10px 0 rgba(0,0,0,0.05);
}
.pp-settings-content #pp-settings-form .form-table th {
	font-weight: 500;
}
.pp-settings-section {
	margin-bottom: 20px;
}
.pp-settings-section:after {
	content: "";
	display: table;
	clear: both;
}
.pp-settings-section .pp-settings-section-title {
	font-weight: 300;
	font-size: 22px;
	border-bottom: 1px solid #eee;
	padding-bottom: 15px;
}
.pp-settings-section .pp-modules-manager-filters {
	float: right;
}
.pp-settings-section .pp-settings-elements-grid > tbody {
	display: flex;
	align-items: center;
	flex-direction: row;
	flex-wrap: wrap;
}
.pp-settings-section .pp-settings-elements-grid > tbody tr {
	background: #f3f5f6;
	margin-right: 10px;
	margin-bottom: 10px;
	padding: 12px;
	border-radius: 5px;
}
.pp-settings-section .pp-settings-elements-grid > tbody tr th,
.pp-settings-section .pp-settings-elements-grid > tbody tr td {
	padding: 0;
}
.pp-settings-section .pp-settings-elements-grid th > label {
	user-select: none;
}
.pp-settings-section .toggle-all-widgets {
	margin-bottom: 10px;
}
.pp-settings-section .pp-admin-field-toggle {
	position: relative;
	display: inline-block;
	width: 35px;
	height: 16px;
}
.pp-settings-section .pp-admin-field-toggle input {
	opacity: 0;
	width: 0;
	height: 0;
}
.pp-settings-section .pp-admin-field-toggle .pp-admin-field-toggle-slider {
	position: absolute;
	cursor: pointer;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-color: #fff;
	border: 1px solid #7e8993;
	border-radius: 34px;
	-webkit-transition: .4s;
	transition: .4s;
}
.pp-settings-section .pp-admin-field-toggle .pp-admin-field-toggle-slider:before {
	border-radius: 50%;
	position: absolute;
	content: "";
	height: 10px;
	width: 10px;
	left: 2px;
	bottom: 2px;
	background-color: #7e8993;
	-webkit-transition: .4s;
	transition: .4s;
}
.pp-settings-section .pp-admin-field-toggle input:checked + .pp-admin-field-toggle-slider:before {
	background-color: #0071a1;
	-webkit-transform: translateX(19px);
	-ms-transform: translateX(19px);
	transform: translateX(19px);
}
.pp-settings-section .pp-admin-field-toggle input:focus + .pp-admin-field-toggle-slider {
	border-color: #0071a1;
	box-shadow: 0 0 2px 1px #0071a1;
	transition: 0s;
}
.pp-settings-form-wrap {
	display: flex;
	flex-direction: row;
	align-items: flex-start;
	flex: 1 1 auto;
}
.pp-settings-form-wrap #pp-settings-form {
	flex: 2 0 0;
}
.pro-upgrade-banner {
	flex: 1 0 0;
	width: 320px;
	max-width: 320px;
	float: right;
	margin-left: 20px;
	background: #fff;
	border: 1px solid #eee;
	box-shadow: 1px 1px 10px 0 rgba(0,0,0,0.05);
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
<?php
function powerpack_elements_lite_render_admin_settings() {
	$current_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$settings     = PP_Admin_Settings::get_settings();
	?>
	<div class="wrap pp-settings-wrap">
		<div class="pp-settings-header">
			<h3>
				<span class="dashicons dashicons-admin-settings"></span>
				<span><?php esc_html_e( 'PowerPack Settings', 'powerpack-lite-for-elementor' ); ?></span>
			</h3>
			<div class="pp-settings-tabs wp-clearfix">
				<?php PP_Admin_Settings::render_tabs( $current_tab ); ?>
			</div>
			<div class="pp-settings-version wp-clearfix">
				<span>
					<?php
					printf(
						/* translators: %s: Plugin version number. */
						esc_html__( 'Version %s', 'powerpack-lite-for-elementor' ),
						esc_html( POWERPACK_ELEMENTS_LITE_VER )
					);
					?>
				</span>
			</div>
		</div>

		<div class="pp-settings-content">
			<h2 class="pp-notices-target"></h2>
			<?php \PowerpackElementsLite\Classes\PP_Admin_Settings::render_update_message(); ?>
			<div class="pp-settings-form-wrap">
				<?php
				$tab = sanitize_key( $current_tab );

				$form_action = add_query_arg(
					array(
						'tab' => $tab,
					),
					PP_Admin_Settings::get_form_action()
				);
				?>
				<form method="post" id="pp-settings-form" action="<?php echo esc_url( $form_action ); ?>">
					<?php PP_Admin_Settings::render_setting_page(); ?>
					<?php submit_button(); ?>
				</form>
				<div class="pro-upgrade-banner">
					<div class="banner-inner">
						<?php $logo_url = POWERPACK_ELEMENTS_LITE_URL . 'assets/images/pp-elements-logo.svg'; ?>

						<div class="banner-image">
							<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php esc_attr_e( 'PowerPack Elements Logo', 'powerpack-lite-for-elementor' ); ?>" />
						</div>
						<h3 class="banner-title-1"><?php esc_html_e( 'Get access to more premium widgets and features.', 'powerpack-lite-for-elementor' ); ?></h3>
						<h3 class="banner-title-2">
							<?php
							printf(
								/* translators: %s: Pro version name wrapped in strong tag. */
								esc_html__( 'Upgrade to %s and get', 'powerpack-lite-for-elementor' ),
								'<strong>' . esc_html__( 'PowerPack Pro', 'powerpack-lite-for-elementor' ) . '</strong>'
							);
							?>
						</h3>
						<ul>
							<li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'More Widgets', 'powerpack-lite-for-elementor' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'WooCommerce Widgets', 'powerpack-lite-for-elementor' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'White Label Branding', 'powerpack-lite-for-elementor' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Expert Support', 'powerpack-lite-for-elementor' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span><?php esc_html_e( 'Lifetime package available', 'powerpack-lite-for-elementor' ); ?></li>
						</ul>
						<?php $upgrade_url = 'https://powerpackelements.com/upgrade/?utm_medium=pp-elements-lite&utm_source=pp-settings&utm_campaign=pp-pro-upgrade'; ?>

						<div class="banner-action">
							<a href="<?php echo esc_url( $upgrade_url ); ?>" class="pp-button" target="_blank" rel="noopener noreferrer" title="<?php esc_attr_e( 'Upgrade to PowerPack Pro', 'powerpack-lite-for-elementor' ); ?>">
								<?php esc_html_e( 'Upgrade Now', 'powerpack-lite-for-elementor' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>

			<br />
			<h2><?php esc_html_e( 'Support', 'powerpack-lite-for-elementor' ); ?></h2>
			<p>
				<?php
				$support_link = 'https://powerpackelements.com/contact/';

				printf(
					wp_kses_post(
						/* translators: %s: Support page link. */
						__( 'For submitting any support queries, feedback, bug reports or feature requests, please visit %s.', 'powerpack-lite-for-elementor' )
					),
					sprintf(
						'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s</a>',
						esc_url( $support_link ),
						esc_html__( 'this link', 'powerpack-lite-for-elementor' )
					)
				);
				?>
			</p>
		</div>
	</div>
	<?php
}

powerpack_elements_lite_render_admin_settings();
