<?php
/**
 * Reinstalling an earlier release.
 *
 * There is no rollback machinery here in any real sense. WordPress already
 * knows how to replace a plugin with a zip — that is what an update is — so
 * this points the updater at an older package and lets it run. The work is in
 * deciding which packages are offerable and refusing everything else.
 *
 * Two things follow from using the updater, and both shape the design:
 *
 * - It renders progress as HTML and ends the request itself. This cannot be a
 *   REST call like the rest of the settings screen; the form leaves the app and
 *   posts to admin-post.php, and the user finishes on WordPress's own page.
 * - On a host whose filesystem is not directly writable it needs to render an
 *   FTP or SSH credentials form. A JSON endpoint has nowhere to put one, which
 *   is the kind of thing that works on the machine it was written on and fails
 *   everywhere else.
 *
 * @package PowerPackElementsLite
 */

namespace PowerpackElementsLite\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Rollback handler.
 *
 * @since 3.0.0
 */
final class PP_Rollback {

	/** The action name the form posts to, via admin-post.php. */
	const ACTION = 'pp_lite_rollback';

	/** Nonce action. */
	const NONCE = 'pp_lite_rollback';

	/**
	 * How many releases to offer.
	 *
	 * The directory lists over a hundred. A select holding all of them is a
	 * worse answer than a short list of the ones anyone would actually pick:
	 * a rollback is nearly always to the release you were on this morning.
	 */
	const LIMIT = 15;

	/**
	 * Register the hook.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public static function init() {
		add_action( 'admin_post_' . self::ACTION, [ __CLASS__, 'handle' ] );
	}

	/**
	 * Whether the current user may replace plugin files.
	 *
	 * 'update_plugins' rather than 'manage_options', because that is the
	 * capability the act itself needs — the one WordPress checks before letting
	 * anyone update a plugin. On multisite it is not granted to site
	 * administrators at all, so the panel simply does not offer this there
	 * rather than offering something that would fail at the last step.
	 *
	 * @since 3.0.0
	 * @return bool
	 */
	public static function is_available() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return false;
		}

		if ( is_multisite() && ! is_super_admin() ) {
			return false;
		}

		// A site can switch this off entirely — some hosts manage plugin
		// versions themselves and do not want it done from inside WordPress.
		return (bool) apply_filters( 'pp_lite_rollback_enabled', ! defined( 'DISALLOW_FILE_MODS' ) || ! DISALLOW_FILE_MODS );
	}

	/**
	 * Releases this install could be moved to, newest first.
	 *
	 * Read from the plugin directory rather than kept in a list here, because a
	 * list here would be wrong the day after it was written. The current
	 * version is dropped — it is not somewhere to go — and so is 'trunk', which
	 * the directory reports as a version and is not a release.
	 *
	 * Cached against the running version, so the list refreshes on update
	 * without anything having to remember to clear it.
	 *
	 * @since 3.0.0
	 * @return array List of version strings.
	 */
	public static function get_versions() {
		$key    = 'pp_lite_rollback_versions_' . POWERPACK_ELEMENTS_LITE_VER;
		$cached = get_transient( $key );

		if ( is_array( $cached ) ) {
			return $cached;
		}

		/*
		 * The seam the paid edition needs. It is not on WordPress.org, so it
		 * cannot use the code below; it has to ask its own store, which has to
		 * be able to answer "which versions may this licence have" and "give me
		 * that one". Filtering here and at get_package_url() is the whole
		 * contract between the two.
		 */
		$versions = apply_filters( 'pp_lite_rollback_versions', null );

		if ( null === $versions ) {
			$versions = self::versions_from_directory();
		}

		$versions = array_values( array_slice( (array) $versions, 0, self::LIMIT ) );

		set_transient( $key, $versions, DAY_IN_SECONDS );

		return $versions;
	}

	/**
	 * Ask WordPress.org which versions of this plugin exist.
	 *
	 * @since 3.0.0
	 * @return array
	 */
	private static function versions_from_directory() {
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$information = plugins_api( 'plugin_information', [ 'slug' => self::get_slug() ] );

		if ( is_wp_error( $information ) || empty( $information->versions ) || ! is_array( $information->versions ) ) {
			return [];
		}

		$versions = $information->versions;

		unset( $versions['trunk'] );

		uksort( $versions, 'version_compare' );

		$versions = array_keys( array_reverse( $versions ) );

		return array_values(
			array_filter(
				$versions,
				function ( $version ) {
					return version_compare( $version, POWERPACK_ELEMENTS_LITE_VER, '<' );
				}
			)
		);
	}

	/**
	 * Where to download a given version from.
	 *
	 * @since 3.0.0
	 * @param string $version Version to fetch.
	 * @return string Package URL, or '' when there is none.
	 */
	public static function get_package_url( $version ) {
		$url = sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', self::get_slug(), $version );

		return (string) apply_filters( 'pp_lite_rollback_package_url', $url, $version );
	}

	/**
	 * This plugin's directory slug.
	 *
	 * Taken from the folder rather than written down, so an install in a
	 * renamed directory still asks the directory about the right plugin.
	 *
	 * @since 3.0.0
	 * @return string
	 */
	private static function get_slug() {
		return dirname( POWERPACK_ELEMENTS_LITE_BASE );
	}

	/**
	 * The URL the form posts to.
	 *
	 * Deliberately not wp_nonce_url(). That helper runs its result through
	 * esc_html(), which is right when the URL is printed into markup and wrong
	 * when it is handed over as data: the '&' comes back as '&amp;', survives
	 * JSON and the React attribute intact, and the nonce arrives under the name
	 * 'amp;_wpnonce'. The nonce goes in the form as a field instead, which is
	 * the ordinary shape for a POST anyway.
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public static function get_action_url() {
		return admin_url( 'admin-post.php' );
	}

	/**
	 * A nonce for the form.
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public static function get_nonce() {
		return wp_create_nonce( self::NONCE );
	}

	/**
	 * Run a rollback, then end the request on the updater's own page.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public static function handle() {
		check_admin_referer( self::NONCE );

		if ( ! self::is_available() ) {
			wp_die(
				esc_html__( 'You are not allowed to change this plugin’s version.', 'powerpack-lite-for-elementor' ),
				'',
				[ 'response' => 403 ]
			);
		}

		$version = isset( $_REQUEST['version'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['version'] ) ) : '';

		/*
		 * Checked against the list rather than merely sanitised. The package URL
		 * is built from this value, so anything not offered a moment ago is
		 * refused outright — the alternative is letting a submitted string
		 * decide what gets downloaded and unzipped over the plugin.
		 */
		if ( '' === $version || ! in_array( $version, self::get_versions(), true ) ) {
			wp_die(
				esc_html__( 'That version is not one this plugin can be rolled back to. Reload the settings screen and try again.', 'powerpack-lite-for-elementor' ),
				'',
				[ 'response' => 400 ]
			);
		}

		self::run( $version );

		// The updater has printed its own page by now, including a link back.
		wp_die( '', esc_html__( 'Roll Back to Previous Version', 'powerpack-lite-for-elementor' ), [ 'response' => 200 ] );
	}

	/**
	 * Point the updater at an older package and let it run.
	 *
	 * @since 3.0.0
	 * @param string $version Version to install.
	 * @return void
	 */
	private static function run( $version ) {
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

		self::stage_package( $version );

		$upgrader = new \Plugin_Upgrader(
			new \Plugin_Upgrader_Skin(
				[
					'title'  => esc_html__( 'Roll Back to Previous Version', 'powerpack-lite-for-elementor' ),
					'url'    => 'update.php?action=upgrade-plugin&plugin=' . rawurlencode( POWERPACK_ELEMENTS_LITE_BASE ),
					'plugin' => POWERPACK_ELEMENTS_LITE_BASE,
					'nonce'  => 'upgrade-plugin_' . POWERPACK_ELEMENTS_LITE_BASE,
				]
			)
		);

		/*
		 * "Installing the latest version…" is what the updater says, and here it
		 * is the opposite of what is happening. Setting $upgrader->strings does
		 * not work: upgrade() calls upgrade_strings() and overwrites the array.
		 * The string has to be caught as it is translated.
		 */
		self::$installing = $version;

		add_filter( 'gettext', [ __CLASS__, 'rename_installing_string' ], 10, 3 );

		$upgrader->upgrade( POWERPACK_ELEMENTS_LITE_BASE );

		remove_filter( 'gettext', [ __CLASS__, 'rename_installing_string' ], 10 );
	}

	/**
	 * The version being installed, while a rollback is running.
	 *
	 * @since 3.0.0
	 * @var string
	 */
	private static $installing = '';

	/**
	 * Say which version is going in, not that it is the newest.
	 *
	 * @since 3.0.0
	 * @param string $translation Translated text.
	 * @param string $text        Original text.
	 * @param string $domain      Text domain.
	 * @return string
	 */
	public static function rename_installing_string( $translation, $text, $domain ) {
		if ( 'Installing the latest version&#8230;' === $text && 'default' === $domain ) {
			return sprintf(
				/* translators: %s: version number being installed. */
				esc_html__( 'Installing version %s…', 'powerpack-lite-for-elementor' ),
				self::$installing
			);
		}

		return $translation;
	}

	/**
	 * Tell the update transient that the chosen version is the one on offer.
	 *
	 * The updater takes what it installs from this transient, so overwriting the
	 * entry is how a downgrade is expressed — there is no separate downgrade
	 * path in WordPress. It is left in place afterwards: WordPress refreshes it
	 * on its own schedule, and the next check will correctly report the real
	 * latest release as an available update.
	 *
	 * @since 3.0.0
	 * @param string $version Version to install.
	 * @return void
	 */
	private static function stage_package( $version ) {
		$updates = get_site_transient( 'update_plugins' );

		if ( ! is_object( $updates ) ) {
			$updates = new \stdClass();
		}

		if ( ! isset( $updates->response ) || ! is_array( $updates->response ) ) {
			$updates->response = [];
		}

		$entry              = new \stdClass();
		$entry->slug        = self::get_slug();
		$entry->plugin      = POWERPACK_ELEMENTS_LITE_BASE;
		$entry->new_version = $version;
		$entry->package     = self::get_package_url( $version );
		$entry->url         = 'https://powerpackelements.com/';

		$updates->response[ POWERPACK_ELEMENTS_LITE_BASE ] = $entry;

		set_site_transient( 'update_plugins', $updates );
	}
}

PP_Rollback::init();
