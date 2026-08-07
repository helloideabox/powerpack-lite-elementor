<?php
namespace PowerpackElementsLite\Classes;

use PowerpackElementsLite\Classes\PP_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles logic for the admin settings page.
 *
 * @since 1.0.0
 */
final class PP_Admin_Settings {
	/**
	 * Holds any errors that may arise from
	 * saving admin settings.
	 *
	 * @since 1.0.0
	 * @var array $errors
	 */
	public static $errors = array();

	public static $settings = array();

	/**
	 * Initializes the admin settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init() {
		add_action( 'plugins_loaded', __CLASS__ . '::init_hooks' );
	}

	/**
	 * Adds the admin menu and enqueues CSS/JS if we are on
	 * the plugin's admin settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function init_hooks() {

		if ( ! is_admin() ) {
			return;
		}

		// Remove all third party plugins notices, keeping this plugin's own.
		add_action( 'in_admin_header', __CLASS__ . '::remove_all_notices', PHP_INT_MAX );

		add_action( 'admin_menu', __CLASS__ . '::menu', 601 );

		if ( current_user_can( 'manage_options' ) ) {

			$page = '';

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( isset( $_GET['page'] ) ) {
				$page = sanitize_text_field( wp_unslash( $_GET['page'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}

			if ( 'powerpack-settings' === $page ) {
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );
			}
		}

		add_action( 'admin_init', __CLASS__ . '::refresh_instagram_access_token' );

		// Late, so it runs after the filter it is undoing.
		add_filter( 'admin_footer_text', __CLASS__ . '::admin_footer_text', 100 );
	}

	/**
	 * This plugin's own admin notices, captured for the settings screen.
	 *
	 * @since x.x.x
	 * @var string
	 */
	private static $notices = '';

	/**
	 * Clear the screen of notices, keeping this plugin's own.
	 *
	 * Every plugin on the site writes to admin_notices, and a settings screen
	 * that opens under three unrelated banners is not a settings screen anyone
	 * can read. They are all dropped here — but dropping ours with them meant a
	 * licence warning or an update prompt never reached the one screen where it
	 * is relevant, so ours are rendered first and kept.
	 *
	 * They are held rather than printed because WordPress fires admin_notices
	 * above the whole page. render() puts them in the markup and the settings
	 * app moves them below its header, which is where they belong.
	 *
	 * @since x.x.x
	 * @return void
	 */
	public static function remove_all_notices() {
		if ( ! isset( $_REQUEST['page'] ) || 'powerpack-settings' !== $_REQUEST['page'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		self::$notices = self::capture_own_notices();

		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
	}

	/**
	 * Render this plugin's notices and return the markup.
	 *
	 * Priority order is preserved, because a notice registered late is meant to
	 * appear after one registered early.
	 *
	 * @since x.x.x
	 * @return string
	 */
	private static function capture_own_notices() {
		global $wp_filter;

		$html = '';

		foreach ( [ 'admin_notices', 'all_admin_notices' ] as $hook ) {
			if ( empty( $wp_filter[ $hook ] ) ) {
				continue;
			}

			$callbacks = $wp_filter[ $hook ]->callbacks;
			ksort( $callbacks );

			foreach ( $callbacks as $group ) {
				foreach ( $group as $registered ) {
					if ( ! self::is_own_notice( $registered['function'] ) ) {
						continue;
					}

					ob_start();
					call_user_func( $registered['function'] );
					$html .= ob_get_clean();
				}
			}
		}

		return trim( $html );
	}

	/**
	 * Whether a notice callback belongs to this plugin.
	 *
	 * Attribution is by name, which is as much as the hook records. A closure
	 * carries no name and is treated as someone else's: showing a third party
	 * notice inside this plugin's chrome is the worse of the two mistakes.
	 *
	 * @since x.x.x
	 * @param callable $callback Registered callback.
	 * @return bool
	 */
	private static function is_own_notice( $callback ) {
		if ( is_string( $callback ) ) {
			return 0 === strpos( $callback, 'pp_' ) || 0 === strpos( $callback, 'powerpack' );
		}

		if ( is_array( $callback ) && isset( $callback[0] ) ) {
			$class = is_object( $callback[0] ) ? get_class( $callback[0] ) : (string) $callback[0];

			return false !== stripos( $class, 'PowerpackElements' );
		}

		return false;
	}

	/**
	 * Enqueues the needed CSS/JS for the builder's admin settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function styles_scripts() {
		$settings   = self::get_settings();
		$asset_path = POWERPACK_ELEMENTS_LITE_PATH . 'assets/build/settings/index.asset.php';

		if ( ! file_exists( $asset_path ) ) {
			return;
		}

		$asset = include $asset_path;

		wp_enqueue_script(
			'pp-settings-app',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/build/settings/index.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_enqueue_style(
			'pp-settings-app',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/build/settings/style-index.css',
			array( 'wp-components' ),
			$asset['version']
		);

		wp_style_add_data( 'pp-settings-app', 'rtl', 'replace' );

		/*
		 * Follow the colour scheme the user picked on their profile. The
		 * override is scoped to the screen and also covers
		 * '--wp-admin-theme-color', so the block components rendered inside it
		 * — switches, buttons — pick up the same accent as our own chrome.
		 */
		$scheme_colors = self::get_admin_scheme_colors();

		if ( ! empty( $scheme_colors ) ) {
			$soft = ! empty( $scheme_colors['soft'] ) ? sprintf( '--ppe-primary-color-soft:%s;', esc_attr( $scheme_colors['soft'] ) ) : '';

			wp_add_inline_style(
				'pp-settings-app',
				sprintf(
					'.pp-settings{--ppe-primary-color:%1$s;--ppe-primary-color-light:%2$s;--wp-admin-theme-color:%1$s;%3$s}',
					esc_attr( $scheme_colors['primary'] ),
					esc_attr( $scheme_colors['light'] ),
					$soft
				)
			);
		}

		wp_set_script_translations( 'pp-settings-app', 'powerpack-lite-for-elementor', POWERPACK_ELEMENTS_LITE_PATH . 'languages' );

		wp_enqueue_style(
			'powerpack-icons',
			POWERPACK_ELEMENTS_LITE_URL . 'assets/lib/ppicons/css/powerpack-icons.css',
			array(),
			POWERPACK_ELEMENTS_LITE_VER
		);

		$docs_link    = ! empty( $settings['docs_link'] ) ? $settings['docs_link'] : 'https://powerpackelements.com/docs/';
		$support_link = ! empty( $settings['support_link'] ) ? $settings['support_link'] : 'https://powerpackelements.com/contact/';

		/*
		 * The upgrade prompt names PowerPack Pro, so it is withheld once the
		 * license is active, and whenever the plugin has been rebranded — a
		 * white labelled install should not be advertising someone else.
		 */
		$show_upgrade = 'valid' !== self::get_option( 'pp_license_status' ) && empty( $settings['plugin_name'] );

		wp_add_inline_script(
			'pp-settings-app',
			'window.ppSettingsBootstrap = ' . wp_json_encode( array(
				'adminLabel'   => self::get_admin_label(),
				'version'      => POWERPACK_ELEMENTS_LITE_VER,

				/*
				 * Which edition is running. The paid edition is a separate
				 * plugin, so this file only ever executes in the free one — but
				 * sending it rather than hardcoding it in the bundle keeps the
				 * two builds able to share one settings app.
				 */
				'isPro'        => false,
				'hideLogo'     => false,
				'docsLink'     => $docs_link,
				'supportLink'  => $support_link,
				'showSupport'  => 'on' !== $settings['hide_support'],

				// Where every "upgrade" link on the screen points. There is no
				// longer a flag beside it: the panels that sell the paid edition
				// are the ones that describe it, and each decides for itself.
				'upgradeUrl'   => self::get_upgrade_url(),

				// Sent with the page so a dismissed checklist never flashes up
				// before a request comes back to say it was dismissed.
				'setupDone'    => (bool) get_user_meta( get_current_user_id(), PP_Settings_REST_Controller::SETUP_DISMISSED_META, true ),
			) ) . ';',
			'before'
		);

		/*
		 * Hand the first responses to the client with the page, so the screen
		 * paints without a request waterfall.
		 */
		$preload = rest_preload_api_request( array(), '/powerpack/v1/settings' );
		$preload = rest_preload_api_request( $preload, '/powerpack/v1/modules' );

		wp_add_inline_script(
			'wp-api-fetch',
			sprintf(
				'wp.apiFetch.use( wp.apiFetch.createPreloadingMiddleware( %s ) );',
				wp_json_encode( $preload )
			),
			'after'
		);

		if ( isset( $settings['plugin_short_name'] ) && '' !== $settings['plugin_short_name'] ) {
			$short_name  = $settings['plugin_short_name'];
			$custom_css  = '.elementor-element [class*="power-pack-"]:after {';
			$custom_css .= 'content: "' . $short_name . '"; }';
			wp_add_inline_style( 'powerpack-editor', $custom_css );
		}
	}

	/**
	 * Accent colours from the current user's admin colour scheme.
	 *
	 * Core does not expose the scheme through a CSS custom property — the
	 * scheme stylesheets only consume '--wp-admin-theme-color', which is set
	 * once to the default. The registered palettes do carry it though, and the
	 * highlight is consistently the second to last swatch across every scheme
	 * core ships, whether the palette has three entries or four.
	 *
	 * @since x.x.x
	 * @return array Empty when the scheme is unknown, otherwise 'primary' and 'light'.
	 */
	private static function get_admin_scheme_colors() {
		global $_wp_admin_css_colors;

		$scheme = get_user_option( 'admin_color' );

		if ( empty( $scheme ) || empty( $_wp_admin_css_colors[ $scheme ]->colors ) ) {
			return array();
		}

		$colors = array_values( (array) $_wp_admin_css_colors[ $scheme ]->colors );
		$count  = count( $colors );

		if ( $count < 2 ) {
			return array();
		}

		return array(
			'primary' => $colors[ $count - 2 ],
			'light'   => $colors[ $count - 1 ],
			'soft'    => self::hex_to_rgba( $colors[ $count - 2 ], 0.1 ),
		);
	}

	/**
	 * Convert a hex colour to an rgba() string.
	 *
	 * Used for the tinted backgrounds behind active and hovered navigation, so
	 * they follow the accent instead of staying a fixed blue.
	 *
	 * @since x.x.x
	 * @param string $hex   Hex colour, with or without the leading hash.
	 * @param float  $alpha Alpha channel.
	 * @return string Empty when the colour cannot be parsed.
	 */
	private static function hex_to_rgba( $hex, $alpha ) {
		$hex = ltrim( (string) $hex, '#' );

		if ( 3 === strlen( $hex ) ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		if ( ! preg_match( '/^[0-9a-f]{6}$/i', $hex ) ) {
			return '';
		}

		return sprintf(
			'rgba(%d, %d, %d, %s)',
			hexdec( substr( $hex, 0, 2 ) ),
			hexdec( substr( $hex, 2, 2 ) ),
			hexdec( substr( $hex, 4, 2 ) ),
			$alpha
		);
	}

	/**
	 * Render the settings screen mount point.
	 *
	 * Everything below this node is rendered by the React app in
	 * assets/build/settings. The noscript message matters: without it a failed
	 * bundle leaves an administrator staring at an empty page with no clue why.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function render() {
		if ( ! file_exists( POWERPACK_ELEMENTS_LITE_PATH . 'assets/build/settings/index.asset.php' ) ) {
			?>
			<div class="wrap">
				<div class="notice notice-error">
					<p><?php esc_html_e( 'The PowerPack settings screen has not been built. Run "npm install" followed by "npm run build:settings" in the plugin directory.', 'powerpack-lite-for-elementor' ); ?></p>
				</div>
			</div>
			<?php
			return;
		}
		?>
		<div class="wrap pp-settings-wrap">
			<?php
			/*
			 * Hidden until the app has moved it below the header. Without the
			 * attribute a notice would flash at the top of the page on every
			 * load, in the position this is trying to move it out of.
			 *
			 * Not escaped: this is notice markup the plugin itself just
			 * rendered, captured verbatim from its own callbacks.
			 */
			if ( '' !== self::$notices ) {
				echo '<div class="pp-settings-notices" hidden>' . self::$notices . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
			<div id="pp-settings-root"></div>
			<noscript>
				<div class="notice notice-error">
					<p><?php esc_html_e( 'The PowerPack settings screen needs JavaScript. Please enable it and reload this page.', 'powerpack-lite-for-elementor' ); ?></p>
				</div>
			</noscript>
		</div>
		<?php
	}

	/**
	 * Where every "upgrade" link on the screen points.
	 *
	 * One place, and filterable: the free edition and any affiliate build need
	 * their own, and a URL repeated at each call site drifts.
	 *
	 * @since x.x.x
	 * @param array $args Optional query arguments, for campaign tagging.
	 * @return string
	 */
	public static function get_upgrade_url( $args = [] ) {
		$url = apply_filters( 'pp_settings_upgrade_url', 'https://powerpackelements.com/upgrade/' );

		return empty( $args ) ? $url : add_query_arg( $args, $url );
	}

	/**
	 * Restore the default admin footer on this plugin's screens.
	 *
	 * Elementor replaces the footer on any screen whose id contains its own
	 * name, and this screen is a submenu of Elementor's, so it inherited a
	 * request to review a different plugin. WordPress's own text is put back
	 * rather than the line being emptied, so the footer keeps the shape every
	 * other admin page has.
	 *
	 * @since x.x.x
	 * @param string $text Footer text.
	 * @return string
	 */
	public static function admin_footer_text( $text ) {
		if ( ! self::is_settings_screen() ) {
			return $text;
		}

		return '<span id="footer-thankyou">' . sprintf(
			/* translators: %s: WordPress.org link. */
			esc_html__( 'Thank you for creating with %s.', 'powerpack-lite-for-elementor' ),
			'<a href="https://wordpress.org/">WordPress</a>'
		) . '</span>';
	}

	/**
	 * Whether the screen being rendered belongs to this plugin.
	 *
	 * @since x.x.x
	 * @return bool
	 */
	private static function is_settings_screen() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}

		$screen = get_current_screen();

		return $screen && false !== strpos( $screen->id, 'powerpack-settings' );
	}

	/**
	 * Get settings.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public static function get_settings() {
		$default_settings = array(
			'plugin_name'       => '',
			'plugin_desc'       => '',
			'plugin_author'     => '',
			'plugin_uri'        => '',
			'admin_label'       => '',
			'support_link'      => '',
			'hide_support'      => 'off',
			'hide_wl_settings'  => 'off',
			'hide_plugin'       => 'off',
			'google_map_api'    => '',
		);

		$settings = self::get_option( 'pp_elementor_settings', true );

		if ( ! is_array( $settings ) || empty( $settings ) ) {
			return $default_settings;
		}

		if ( is_array( $settings ) && ! empty( $settings ) ) {
			return array_merge( $default_settings, $settings );
		}
	}

	/**
	 * Get admin label from settings.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public static function get_admin_label() {
		return 'PowerPack';
	}



	/**
	 * Renders the admin settings menu.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function menu() {
		$admin_label = self::get_admin_label();
		$title       = $admin_label;
		$cap         = 'manage_options';
		$slug        = 'powerpack-settings';
		$func        = __CLASS__ . '::render';

		if ( current_user_can( 'manage_options' ) ) {
			if ( defined( '\ELEMENTOR_VERSION' ) && version_compare( \ELEMENTOR_VERSION, '3.35.0', '>=' ) ) {
				add_submenu_page( 'elementor-home', $title, $title, $cap, $slug, $func );
			} else {
				add_submenu_page( 'elementor', $title, $title, $cap, $slug, $func );
			}
		}
	}





	/**
	 * Renders the action for a form.
	 *
	 * @since 1.0.0
	 * @param string $type The type of form being rendered.
	 * @return void
	 */
	public static function get_form_action( $type = '' ) {
		$panel = '';

		if ( preg_match( '/&tab=([a-z_-]+)/', $type, $matches ) ) {
			$map   = [
				'modules'     => 'elements',
				'extensions'  => 'extensions',
				'integration' => 'integration',
			];
			$slug  = $matches[1];
			$panel = isset( $map[ $slug ] ) ? $map[ $slug ] : $slug;
			$type  = str_replace( $matches[0], '', $type );
		}

		$url = is_network_admin()
			? network_admin_url( '/admin.php?page=powerpack-settings' . $type )
			: admin_url( '/admin.php?page=powerpack-settings' . $type );

		return esc_url( $url ) . ( $panel ? '#' . $panel : '' );
	}

	/**
	 * Returns an option from the database for
	 * the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @return mixed
	 */
	public static function get_option( $key, $network_override = true, $default = null ) {
		if ( is_network_admin() ) {
			$value = get_site_option( $key );
		} elseif ( ! $network_override && is_multisite() ) {
			$value = get_site_option( $key );
		} elseif ( $network_override && is_multisite() ) {
			$value = get_option( $key );
			$value = ( false === $value || ( is_array( $value ) && in_array( 'disabled', $value ) && get_option( 'pp_override_ms' ) != 1 ) ) ? get_site_option( $key ) : $value;
		} else {
			$value = get_option( $key );
		}

		if ( empty( $value ) && ! is_null( $default ) ) {
			$value = $default;
		}

		return $value;
	}

	/**
	 * Updates an option from the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @param mixed $value The value to update.
	 * @return mixed
	 */
	public static function update_option( $key, $value, $network_override = true, $override_checked = false ) {

		if ( is_network_admin() ) {

			update_site_option( $key, $value );

		} elseif ( $network_override && is_multisite() && ! $override_checked ) {

			// Delete the option if network overrides are allowed
			// and the override checkbox isn't checked.
			delete_option( $key );

		} else {

			update_option( $key, $value );
		}
	}

	/**
	 * Delete an option from the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @param mixed $value The value to delete.
	 * @return mixed
	 */
	public static function delete_option( $key ) {
		if ( is_network_admin() ) {
			delete_site_option( $key );
		} else {
			delete_option( $key );
		}
	}





	/**
	* Refresh instagram token after 30 days.
	*
	* @since 2.5.4
	*/
	public static function refresh_instagram_access_token( $access_token = '', $widget_id = '' ) {
		if ( empty( $access_token ) ) {
			$access_token = trim( \PowerpackElementsLite\Classes\PP_Admin_Settings::get_option( 'pp_instagram_access_token' ) );
		}

		$updated_access_token = "ppe_updated_instagram_access_token";
		
		if ( ! empty( $widget_id ) ) {
			$updated_access_token = "ppe_updated_instagram_access_token_widget_$widget_id";
		}

		if ( empty( $access_token ) ) {
			return;
		}
	
		$updated = get_transient( $updated_access_token );

		if ( ! empty( $updated ) ) {
			return;
		}
	
		$endpoint_url = add_query_arg(
			[
				'access_token' => $access_token,
				'grant_type'   => 'ig_refresh_token',
			],
			'https://graph.instagram.com/refresh_access_token'
		);
	
		$response = wp_remote_get( $endpoint_url );
	
		if ( ! $response || 200 !== wp_remote_retrieve_response_code( $response ) || is_wp_error( $response ) ) {
			set_transient( $updated_access_token, 'error', DAY_IN_SECONDS );
			return;
		}
	
		$body = wp_remote_retrieve_body( $response );
	
		if ( ! $body ) {
			set_transient( $updated_access_token, 'error', DAY_IN_SECONDS );
			return;
		}
	
		$body = json_decode( $body, true );
	
		if ( empty( $body['access_token'] ) || empty( $body['expires_in'] ) ) {
			set_transient( $updated_access_token, 'error', DAY_IN_SECONDS );
			return;
		}
	
		set_transient( $updated_access_token, 'updated', 30 * DAY_IN_SECONDS );
	}
}

PP_Admin_Settings::init();
