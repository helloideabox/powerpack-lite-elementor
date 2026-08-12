<?php
namespace PowerpackElementsLite\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Nudges admins to turn off widgets they do not use.
 *
 * Every enabled widget is registered with Elementor on every editor load, and
 * each one adds its controls to the editor config payload. Trimming the widget
 * library is the single biggest thing a site owner can do to speed the editor
 * up, so we surface a notice once a large share of the library is enabled.
 *
 * @since 3.0.0
 */
final class PP_Widgets_Notice {

	/**
	 * Option that stores the state of the dismissed notice.
	 *
	 * @since 3.0.0
	 * @var string
	 */
	const DISMISS_OPTION = 'pp_lite_widgets_notice_dismissed';

	/**
	 * AJAX action used to dismiss the notice.
	 *
	 * @since 3.0.0
	 * @var string
	 */
	const AJAX_ACTION = 'pp_lite_dismiss_widgets_notice';

	/**
	 * Default percentage of the widget library that must be enabled before the
	 * notice is shown.
	 *
	 * @since 3.0.0
	 * @var int
	 */
	const DEFAULT_THRESHOLD = 70;

	/**
	 * Minimum number of widgets that must still be enabled before the notice is
	 * shown. Keeps the notice quiet on sites where there is nothing meaningful
	 * left to trim.
	 *
	 * @since 3.0.0
	 * @var int
	 */
	const MIN_ENABLED = 25;

	/**
	 * How long the notice stays hidden after it has been dismissed.
	 *
	 * @since 3.0.0
	 * @var int
	 */
	const SNOOZE_PERIOD = 90 * DAY_IN_SECONDS;

	/**
	 * Initialize.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public static function init() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_notices', [ __CLASS__, 'render_admin_notice' ] );
		add_action( 'wp_ajax_' . self::AJAX_ACTION, [ __CLASS__, 'dismiss' ] );
	}

	/**
	 * Whether the widget library is loaded heavily enough to warrant a nudge.
	 *
	 * @since 3.0.0
	 * @return bool
	 */
	public static function is_threshold_crossed() {
		$stats = powerpack_elements_lite_get_modules_stats();

		/**
		 * Filters the share of the widget library that must be enabled before
		 * the performance notice is shown.
		 *
		 * @since 3.0.0
		 * @param int $threshold Percentage, 0-100.
		 */
		$threshold = (int) apply_filters( 'pp_widgets_notice_threshold', self::DEFAULT_THRESHOLD );

		if ( $stats['enabled'] < self::MIN_ENABLED ) {
			return false;
		}

		return $stats['percent'] >= $threshold;
	}

	/**
	 * Whether the dismissible notice should be printed on the current screen.
	 *
	 * @since 3.0.0
	 * @return bool
	 */
	private static function should_show_admin_notice() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( ! self::is_screen_allowed() ) {
			return false;
		}

		if ( self::is_dismissed() ) {
			return false;
		}

		return self::is_threshold_crossed();
	}

	/**
	 * Limits the notice to screens where acting on it makes sense, so it does
	 * not follow the user around the whole admin.
	 *
	 * @since 3.0.0
	 * @return bool
	 */
	private static function is_screen_allowed() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}

		$screen = get_current_screen();

		if ( ! $screen ) {
			return false;
		}

		$allowed_screens = [
			'dashboard',
			'plugins',
			'edit-elementor_library',
			'elementor_page_elementor-tools',
			'toplevel_page_elementor',
		];

		/**
		 * Filters the admin screens the widgets performance notice appears on.
		 *
		 * @since 3.0.0
		 * @param array $allowed_screens Screen IDs.
		 */
		$allowed_screens = apply_filters( 'pp_widgets_notice_screens', $allowed_screens );

		return in_array( $screen->id, $allowed_screens, true );
	}

	/**
	 * Whether the notice has been dismissed and is still snoozed.
	 *
	 * The notice comes back once the snooze expires, or as soon as the site
	 * enables noticeably more widgets than it had when the notice was hidden.
	 *
	 * @since 3.0.0
	 * @return bool
	 */
	private static function is_dismissed() {
		$dismissed = get_option( self::DISMISS_OPTION );

		if ( empty( $dismissed ) || ! is_array( $dismissed ) ) {
			return false;
		}

		$time    = isset( $dismissed['time'] ) ? (int) $dismissed['time'] : 0;
		$enabled = isset( $dismissed['enabled'] ) ? (int) $dismissed['enabled'] : 0;

		if ( ( time() - $time ) > self::SNOOZE_PERIOD ) {
			return false;
		}

		$stats = powerpack_elements_lite_get_modules_stats();

		if ( $stats['enabled'] > ( $enabled + 5 ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Prints the dismissible admin notice.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public static function render_admin_notice() {
		if ( ! self::should_show_admin_notice() ) {
			return;
		}

		$stats = powerpack_elements_lite_get_modules_stats();
		?>
		<div class="notice notice-warning is-dismissible pp-widgets-notice">
			<p>
				<strong><?php echo esc_html( PP_Admin_Settings::get_admin_label() ); ?></strong>
				&mdash;
				<?php
				printf(
					/* translators: 1: Number of enabled widgets, 2: Total number of widgets, 3: Percentage of enabled widgets. */
					esc_html__( '%1$s of %2$s widgets are enabled (%3$s%%). Every enabled widget is loaded into the Elementor editor, so turning off the ones this site does not use makes the editor open and save noticeably faster.', 'powerpack-lite-for-elementor' ),
					esc_html( number_format_i18n( $stats['enabled'] ) ),
					esc_html( number_format_i18n( $stats['total'] ) ),
					esc_html( number_format_i18n( $stats['percent'] ) )
				);
				?>
			</p>
			<p>
				<a href="<?php echo esc_url( self::get_modules_url( 'notused' ) ); ?>" class="button button-primary">
					<?php esc_html_e( 'Review unused widgets', 'powerpack-lite-for-elementor' ); ?>
				</a>
				<a href="<?php echo esc_url( self::get_modules_url() ); ?>" class="button">
					<?php esc_html_e( 'Manage widgets', 'powerpack-lite-for-elementor' ); ?>
				</a>
			</p>
		</div>
		<script>
		(function($) {
			$( document ).on( 'click', '.pp-widgets-notice .notice-dismiss', function() {
				$.post( ajaxurl, {
					action: '<?php echo esc_js( self::AJAX_ACTION ); ?>',
					nonce: '<?php echo esc_js( wp_create_nonce( self::AJAX_ACTION ) ); ?>'
				} );
			} );
		})(jQuery);
		</script>
		<?php
	}

	/**
	 * Stores the dismissal.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public static function dismiss() {
		check_ajax_referer( self::AJAX_ACTION, 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}

		$stats = powerpack_elements_lite_get_modules_stats();

		update_option(
			self::DISMISS_OPTION,
			[
				'time'    => time(),
				'enabled' => $stats['enabled'],
			],
			false
		);

		wp_send_json_success();
	}

	/**
	 * URL of the Elements panel, optionally pre-filtered.
	 *
	 * The 'show' parameter is read back by the Elements panel on mount, which
	 * is what lets the notice hand someone straight to the unused widgets.
	 *
	 * @since 3.0.0
	 * @param string $filter Optional. 'used' or 'notused'.
	 * @return string
	 */
	private static function get_modules_url( $filter = '' ) {
		$query = '&tab=modules';

		if ( $filter ) {
			$query .= '&show=' . rawurlencode( $filter );
		}

		// The query is built in one go rather than with add_query_arg(), since
		// get_form_action() returns an escaped URL where '&' is already encoded
		// as '&#038;' and add_query_arg() would treat that '#' as a fragment.
		return PP_Admin_Settings::get_form_action( $query );
	}
}

PP_Widgets_Notice::init();
