<?php
/**
 * Plugin Name: PowerPack Lite for Elementor
 * Plugin URI: https://powerpackelements.com
 * Description: Extend Elementor Page Builder with 40+ Creative Widgets and exciting extensions.
 * Version: 2.9.11
 * Author: PowerPack Addons Team - IdeaBox Creations
 * Author URI: http://ideabox.io/
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: powerpack-lite-for-elementor
 * Domain Path: /languages
 * Elementor tested up to: 4.0.0
 * Elementor Pro tested up to: 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( defined( 'POWERPACK_ELEMENTS_VER' ) ) {
	return;
}

define( 'POWERPACK_ELEMENTS_LITE_VER', '2.9.11' );
define( 'POWERPACK_ELEMENTS_LITE_PATH', plugin_dir_path( __FILE__ ) );
define( 'POWERPACK_ELEMENTS_LITE_BASE', plugin_basename( __FILE__ ) );
define( 'POWERPACK_ELEMENTS_LITE_URL', plugins_url( '/', __FILE__ ) );
define( 'POWERPACK_ELEMENTS_LITE_ELEMENTOR_VERSION_REQUIRED', '3.5.0' );
define( 'POWERPACK_ELEMENTS_LITE_PHP_VERSION_REQUIRED', '7.4' );

require_once POWERPACK_ELEMENTS_LITE_PATH . 'includes/helper-functions.php';
require_once POWERPACK_ELEMENTS_LITE_PATH . 'includes/admin/feedback/class-pp-tracking.php';
require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/class-pp-admin-settings.php';
require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/class-pp-config.php';
require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/class-pp-helper.php';
require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/class-pp-posts-helper.php';
require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/class-pp-wpml.php';
require_once POWERPACK_ELEMENTS_LITE_PATH . 'plugin.php';
if ( did_action( 'elementor/loaded' ) ) {
	require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/class-pp-templates-lib.php';
}

/**
 * Check if Elementor is installed
 *
 * @since 1.0
 */
function powerpack_elements_lite_is_elementor_installed() {
	$file_path = 'elementor/elementor.php';
	$installed_plugins = get_plugins();
	return isset( $installed_plugins[ $file_path ] );
}

/**
 * Shows notice to user if Elementor plugin
 * is not installed or activated or both
 *
 * @since 1.0
 */
function powerpack_elements_lite_fail_load() {
    $plugin = 'elementor/elementor.php';

	if ( powerpack_elements_lite_is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
        $message = __( 'PowerPack requires Elementor plugin to be active. Please activate Elementor to continue.', 'powerpack-lite-for-elementor' );
		$button_text = __( 'Activate Elementor', 'powerpack-lite-for-elementor' );

	} else {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		$activation_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
		$message = sprintf(
			/* translators: 1: Opening strong tag, 2: Closing strong tag. */
			__(
				'PowerPack requires the %1$sElementor%2$s plugin to be installed and activated. Please install Elementor to continue.',
				'powerpack-lite-for-elementor'
			),
			'<strong>',
			'</strong>'
		);

		$message = wp_kses_post( $message );
		$button_text = __( 'Install Elementor', 'powerpack-lite-for-elementor' );
	}

	$button = sprintf(
		'<p><a href="%1$s" class="button-primary">%2$s</a></p>',
		esc_url( $activation_url ),
		esc_html( $button_text )
	);
	?>
	<div class="notice notice-error">
		<p><?php echo esc_html( $message ); ?></p>
		<?php echo wp_kses_post( $button ); ?>
	</div>
	<?php
}

/**
 * Shows notice to user if
 * Elementor version if outdated
 *
 * @since 1.0
 *
 */
function powerpack_elements_lite_fail_load_out_of_date() {
    if ( ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$message = sprintf(
		/* translators: %s: Minimum required Elementor version number. */
		esc_html__( 'PowerPack requires Elementor version at least %s. Please update Elementor to continue.', 'powerpack-lite-for-elementor' ),
		POWERPACK_ELEMENTS_LITE_ELEMENTOR_VERSION_REQUIRED
	);

	printf( '<div class="error"><p>%1$s</p></div>', esc_html( $message ) );
}

/**
 * Shows notice to user if minimum PHP
 * version requirement is not met
 *
 * @since 1.0
 *
 */
function powerpack_elements_lite_fail_php() {
	$message = sprintf(
		/* translators: %s: Minimum required PHP version number. */
		esc_html__( 'PowerPack requires PHP version %s+ to work properly. The plugin is deactivated for now.', 'powerpack-lite-for-elementor' ),
		POWERPACK_ELEMENTS_LITE_PHP_VERSION_REQUIRED
	);

	printf( '<div class="error"><p>%1$s</p></div>', esc_html( $message ) );

	// Remove the activate parameter from the URL to prevent "Plugin activated" message.
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['activate'] ) ) {
		unset( $_GET['activate'] );
	}
}

/**
 * Deactivates the plugin
 *
 * @since 1.0
 */
function powerpack_elements_lite_deactivate() {
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

add_action( 'plugins_loaded', 'powerpack_elements_lite_init' );

function powerpack_elements_lite_init() {
    // Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'powerpack_elements_lite_fail_load' );
		return;
	}

	// Check for required Elementor version
	if ( ! version_compare( ELEMENTOR_VERSION, POWERPACK_ELEMENTS_LITE_ELEMENTOR_VERSION_REQUIRED, '>=' ) ) {
		add_action( 'admin_notices', 'powerpack_elements_lite_fail_load_out_of_date' );
		add_action( 'admin_init', 'powerpack_elements_lite_deactivate' );
		return;
	}
    
    // Check for required PHP version
	if ( ! version_compare( PHP_VERSION, POWERPACK_ELEMENTS_LITE_PHP_VERSION_REQUIRED, '>=' ) ) {
		add_action( 'admin_notices', 'powerpack_elements_lite_fail_php' );
		add_action( 'admin_init', 'powerpack_elements_lite_deactivate' );
		return;
	}

	$is_plugin_activated = get_option( 'pp_plugin_activated' );
	if ( current_user_can('activate_plugins') && 'yes' !== $is_plugin_activated ) {
		update_option( 'pp_install_date', current_time( 'mysql' ) );
		update_option( 'pp_plugin_activated', 'yes' );
	}
}

/**
 * Check if PowerPack Elements is active
 *
 * @since 1.2.9.4
 *
 */
if ( ! function_exists( 'is_pp_elements_active' ) ) {
	function is_pp_elements_active() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$plugin = 'powerpack-elements/powerpack-elements.php';

		return is_plugin_active( $plugin ) || function_exists( 'pp_init' );
	}
}

/**
 * Add settings page link to plugin page
 *
 * @since 1.4.4
 */
function powerpack_elements_lite_add_plugin_page_settings_link( $links ) {

	$settings_url = admin_url( 'admin.php?page=powerpack-settings' );

	$links[] = sprintf(
		'<a href="%1$s">%2$s</a>',
		esc_url( $settings_url ),
		esc_html__( 'Settings', 'powerpack-lite-for-elementor' )
	);

	return $links;
}

add_filter( 'plugin_action_links_' . POWERPACK_ELEMENTS_LITE_BASE, 'powerpack_elements_lite_add_plugin_page_settings_link' );

function powerpack_elements_add_description_links( $plugin_meta, $plugin_file ) {

	if ( POWERPACK_ELEMENTS_LITE_BASE === $plugin_file ) {

		$row_meta = [
			'docs' => sprintf(
				'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer">%3$s</a>',
				esc_url( 'https://powerpackelements.com/docs/?utm_source=doclink&utm_medium=widget&utm_campaign=lite' ),
				esc_attr__( 'View PowerPack Documentation', 'powerpack-lite-for-elementor' ),
				esc_html__( 'Docs & FAQs', 'powerpack-lite-for-elementor' )
			),
			'pro'  => sprintf(
				'<a href="%1$s" aria-label="%2$s" target="_blank" rel="noopener noreferrer" style="font-weight:bold;">%3$s</a>',
				esc_url( 'https://powerpackelements.com/?utm_source=plugin&utm_medium=list&utm_campaign=lite' ),
				esc_attr__( 'Upgrade to PowerPack Pro', 'powerpack-lite-for-elementor' ),
				esc_html__( 'Go Pro', 'powerpack-lite-for-elementor' )
			),
		];

		$plugin_meta = array_merge( $plugin_meta, $row_meta );
	}

	return $plugin_meta;
}

add_filter( 'plugin_row_meta', 'powerpack_elements_add_description_links', 10, 4 );
