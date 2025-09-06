<?php
/**
 * Tracking functions for reporting plugin usage to the PowerPack Elements site for users that have opted in
 *
 * @copyright Copyright (c) 2015, Pippin Williamson
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.3
 *
 * @package PowerPackElements
 */

namespace PowerpackElementsLite\Classes;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Usage tracking.
 *
 * @access public
 * @since  1.3
 * @return void
 */
class UsageTracking {
	private $config;

	/**
	 * The data to send to the remote site.
	 *
	 * @var array $data
	 * @access private
	 */
	private $data;

	/**
	 * Remote site.
	 *
	 * @var string $site_url
	 * @access private
	 */
	private $site_url = 'https://powerpackelements.com/';

	/**
	 * Plugin slug.
	 *
	 * @var string $plugin_slug
	 * @access private
	 */
	private $plugin_slug = 'powerpack-lite-for-elementor';

	/**
	 * Plugin name.
	 *
	 * @var string $plugin_name
	 * @access private
	 */
	private $plugin_name = 'PowerPack Lite for Elementor';

	/**
	 * Get things going.
	 *
	 * @access public
	 */
	public function __construct( $config = [] ) {
		//add_action( 'init', array( $this, 'schedule_send' ) );
		//add_action( 'init', array( $this, 'create_recurring_schedule' ) );
		//add_filter( 'cron_schedules', array( $this, 'cron_add_weekly' ) );
		//add_action( 'pp_admin_after_settings_saved', array( $this, 'check_for_settings_optin' ), 10, 2 );
		//add_action( 'admin_init', array( $this, 'act_on_tracking_decision' ) );
		$this->config = wp_parse_args( $config, $this->get_default_config() );

		// Normalize slug to a key-safe value.
		$this->plugin_slug = sanitize_key( str_replace( '-', '_', $this->plugin_slug ) );

		add_action( 'admin_init', array( $this, 'hook_notices' ) );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_action( 'admin_footer-plugins.php', [ $this, 'show_deactivation_feedback_popup' ] );
		add_action( 'wp_ajax_' . $this->plugin_slug . '_submit_deactivation_response', [ $this, 'submit_deactivation_response' ] );
	}

	/**
	 * Get default configuration
	 *
	 * @return array Default configuration
	 */
	private function get_default_config() {
		return [
			'plugin_slug'                 => '',
			'plugin_name'                 => '',
			'plugin_url'                  => '',
			'plugin_version'              => POWERPACK_ELEMENTS_LITE_VER,
			'assets_url'                  => '',
			'feedback_api_url'            => 'https://feedback.bloompixel.com/wp-json/feedback/v1/feedback',
			'show_deactivation_feedback'  => true,
			'collect_system_info'         => true,
			'custom_deactivation_reasons' => [],
		];
	}

	/**
	 * Enqueue scripts and styles.
	 */
	public function enqueue_scripts() {
		$screen = get_current_screen();

		if ( ! $screen || 'plugins' !== $screen->id ) {
			return;
		}

		// Enqueue CSS.
		wp_enqueue_style(
			'pp-feedback-css',
			POWERPACK_ELEMENTS_LITE_URL . 'includes/admin/feedback/css/feedback.css',
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);

		// Enqueue JavaScript.
		wp_enqueue_script(
			'pp-feedback-js',
			POWERPACK_ELEMENTS_LITE_URL . 'includes/admin/feedback/js/feedback.js',
			[ 'jquery' ],
			POWERPACK_ELEMENTS_LITE_VER,
			true
		);

		// Localize script.
		wp_localize_script(
			'pp-feedback-js',
			$this->plugin_slug . '_feedback_vars',
			[
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'plugin_slug' => $this->plugin_slug,
				'nonce'       => wp_create_nonce( $this->plugin_slug . '_feedback_nonce' ),
				'strings'     => [
					'submitting' => esc_html__( 'Submitting...', 'powerpack' ),
					'error'      => esc_html__( 'An error occurred. Please try again.', 'powerpack' ),
					'success'    => esc_html__( 'Thank you for your feedback!', 'powerpack' )
				]
			]
		);
	}

	/**
	 * Hook some notices and perform actions.
	 *
	 * @access public
	 */
	public function hook_notices() {
		$action = isset( $_GET['pp_admin_action'] ) ? sanitize_key( wp_unslash( $_GET['pp_admin_action'] ) ) : '';
		$nonce  = isset( $_GET['_nonce'] ) ? wp_unslash( $_GET['_nonce'] ) : '';

		if ( $action && $nonce && wp_verify_nonce( $nonce, 'pp_admin_notice' ) ) {
			switch ( $action ) {
				case 'review_maybe_later':
					update_option( 'pp_review_later_date', current_time( 'mysql' ) );
					break;
				case 'review_already_did':
					update_option( 'pp_review_already_did', 'yes' );
					break;
				case 'do_not_upgrade':
					update_option( 'pp_do_not_upgrade_to_pro', 'yes' );
					break;
			}
			wp_safe_redirect( esc_url_raw( remove_query_arg( [ 'pp_admin_action', '_nonce' ] ) ) );
			exit;
		}

		if ( isset( $_GET['page'] ) && 'powerpack-settings' === sanitize_key( wp_unslash( $_GET['page'] ) ) ) {
			remove_all_actions( 'admin_notices' );
		}

		// add_action( 'admin_notices', [ $this, 'tracking_admin_notice' ] );
		add_action( 'admin_notices', [ $this, 'review_plugin_notice' ] );
		add_action( 'admin_notices', [ $this, 'pro_upgrade_notice' ] );
	}

	/**
	 * Add weekly schedule for cron.
	 *
	 * @param 	array $schedules Array of cron schedules.
	 * @access 	public
	 * @return 	array
	 */
	public function cron_add_weekly( $schedules ) {
		$schedules['ppeweekly'] = array(
			'interval' => 604800,
			'display' => 'Weekly',
		);
		return $schedules;
	}

	/**
	 * Create recurring schedule.
	 *
	 * @access public
	 */
	public function create_recurring_schedule() {
		// check if event scheduled before.
		if ( ! wp_next_scheduled( 'pp_recurring_cron_job' ) ) {
			// schedule event to run after every day.
			wp_schedule_event( time(), 'ppeweekly', 'pp_recurring_cron_job' );
		}
	}

	/**
	 * Check if the user has opted into tracking.
	 *
	 * @access private
	 * @return bool
	 */
	private function tracking_allowed() {
		$setting = get_option( 'pp_allowed_tracking', false );

		return 'on' === $setting;
	}

	/**
	 * Setup the data that is going to be tracked.
	 *
	 * @access private
	 * @return void
	 */
	private function setup_data() {
		if ( ! $this->tracking_allowed() ) {
			$this->data = [];
			return;
		}

		$theme       = wp_get_theme();
		$current_user = wp_get_current_user();

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins    = get_plugins(); // [ 'dir/file.php' => [ 'Name' => ..., 'Version' => ... ] ]
		$active_plugins = (array) get_option( 'active_plugins', [] );
		$inactive       = array_diff( array_keys( $all_plugins ), $active_plugins );

		$this->data = [
			'php_version'      => PHP_VERSION,
			'edd_version'      => POWERPACK_ELEMENTS_LITE_VER,
			'wp_version'       => get_bloginfo( 'version' ),
			'server'           => isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '',
			'install_date'     => get_option( 'pp_install_date', 'not set' ),
			'multisite'        => is_multisite(),
			'url'              => home_url(),
			'theme'            => $theme->get( 'Name' ) . ' ' . $theme->get( 'Version' ),
			'email'            => get_bloginfo( 'admin_email' ),
			'active_plugins'   => $active_plugins,
			'inactive_plugins' => array_values( $inactive ),
			'locale'           => get_user_locale(),
			'user_firstname'   => sanitize_text_field( $current_user->user_firstname ),
			'user_lastname'    => sanitize_text_field( $current_user->user_lastname ),
			'user_email'       => sanitize_email( $current_user->user_email ),
		];
	}

	/**
	 * Send the data to the remote server.
	 *
	 * @param boolean $override Force checkin.
	 * @param boolean $ignore_last_checkin Ignore last checkin.
	 * @access private
	 * @return mixed
	 */
	public function send_checkin( $override = false, $ignore_last_checkin = false ) {
		$home_url = trailingslashit( home_url() );

		// Allows us to stop our own site from checking in, and a filter for our additional sites.
		if ( $this->site_url === $home_url || apply_filters( 'pp_disable_tracking_checkin', false ) ) {
			return false;
		}

		if ( ! $this->tracking_allowed() && ! $override ) {
			return false;
		}

		// Send a maximum of once per week.
		$last_send = $this->get_last_send();
		if ( is_numeric( $last_send ) && $last_send > strtotime( '-1 week' ) && ! $ignore_last_checkin ) {
			return false;
		}

		$this->setup_data();

		$response = wp_remote_post(
			add_query_arg( 'edd_action', 'checkin', $this->site_url ),
			[
				'timeout'    => 20,
				'redirection'=> 5,
				'httpversion'=> '1.1',
				'blocking'   => true,
				'body'       => $this->data,
				'user-agent' => 'EDD/' . POWERPACK_ELEMENTS_LITE_VER . '; ' . home_url( '/' ),
			]
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		update_option( 'pp_tracking_last_send', time() );

		return true;
	}

	/**
	 * Check for a new opt-in on settings save.
	 *
	 * This runs during the sanitation of General settings, thus the return.
	 *
	 * @access public
	 * @return mixed
	 */
	public function check_for_settings_optin() {
		// Send an initial check in on settings save.
		if ( isset( $_POST['pp_allowed_tracking'] ) && 'on' === wp_unslash( $_POST['pp_allowed_tracking'] ) ) { // @codingStandardsIgnoreLine.
			$this->send_checkin( true );
		}
	}

	/**
	 * Act on tracking descision.
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function act_on_tracking_decision() {
		if ( isset( $_GET['pp_admin_action'] ) ) {
			if ( 'pp_opt_into_tracking' === $_GET['pp_admin_action'] ) {
				$this->check_for_optin();
			}

			if ( 'pp_opt_out_of_tracking' === $_GET['pp_admin_action'] ) {
				$this->check_for_optout();
			}
		}
	}

	/**
	 * Check for a new opt-in via the admin notice.
	 *
	 * @access public
	 * @return void
	 */
	public function check_for_optin() {
		update_option( 'pp_allowed_tracking', 'on' );

		$this->send_checkin( true );

		update_option( 'pp_tracking_notice', '1' );

		wp_safe_redirect( remove_query_arg( 'pp_admin_action' ) );
	}

	/**
	 * Check for a new opt-in via the admin notice.
	 *
	 * @access public
	 * @return void
	 */
	public function check_for_optout() {
		delete_option( 'pp_allowed_tracking' );
		update_option( 'pp_tracking_notice', '1' );
		wp_safe_redirect( remove_query_arg( 'pp_admin_action' ) );
		exit;
	}

	/**
	 * Get the last time a checkin was sent.
	 *
	 * @access private
	 * @return false|string
	 */
	private function get_last_send() {
		return get_option( 'pp_tracking_last_send' );
	}

	/**
	 * Schedule a weekly checkin.
	 *
	 * @access public
	 * @return void
	 */
	public function schedule_send() {
		// We send once a week (while tracking is allowed) to check in, which can be used to determine active sites.
		add_action( 'pp_recurring_cron_job', array( $this, 'send_checkin' ) );
	}

	/**
	 * Display the admin notice to users that have not opted-in or out.
	 *
	 * @access public
	 * @return void
	 */
	public function tracking_admin_notice() {
		$hide_notice = get_option( 'pp_tracking_notice' );

		if ( $hide_notice || $this->tracking_allowed() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$host = network_site_url( '/' );
		$dev_env = ( false !== stripos( $host, 'dev' ) )
			|| ( false !== stripos( $host, 'localhost' ) )
			|| ( false !== stripos( $host, ':8888' ) ) // This is common with MAMP on OS X.
			|| in_array( ( $_SERVER['REMOTE_ADDR'] ?? '' ), [ '127.0.0.1', '::1' ], true );

		if ( $dev_env ) {
			update_option( 'pp_tracking_notice', '1' );
			return;
		}

		$optin_url  = add_query_arg( 'pp_admin_action', 'pp_opt_into_tracking' );
		$optout_url = add_query_arg( 'pp_admin_action', 'pp_opt_out_of_tracking' );

		$source = substr( md5( get_bloginfo( 'name' ) ), 0, 10 );
		$store_url = $this->site_url . 'pricing/?utm_source=' . $source . '&utm_medium=admin&utm_term=notice&utm_campaign=PPEUsageTracking';

		echo '<div class="notice notice-info updated"><p>';
		printf(
			// translators: %1$s denotes plugin name, %2$s denotes title text, %3$s denotes percentile, %4$s denotes store URL.
			__( 'Want to help make %1$s even more awesome? Allow us to <a href="#pp-what-we-collect" title="%2$s">collect non-sensitive</a> diagnostic data and plugin usage information. Opt-in to tracking and we will send you a special 15%3$s discount code for <a href="%4$s">Premium Upgrade</a>.', 'powerpack' ),
			'<strong>PowerPack Elements</strong>',
			esc_html__( 'Click here to check what we collect.', 'powerpack' ),
			'%',
			esc_url( $store_url )
		);
		echo '</p>';
		echo '<p id="pp-what-we-collect" style="display: none;">';
		echo esc_html__( 'We collect WordPress and PHP version, plugin and theme version, server environment, website, user first name, user last name, and email address to send you the discount code. No sensitive data is tracked.', 'powerpack' );
		echo '</p>';
		echo '<p>';
		echo '<a href="' . esc_url( $optin_url ) . '" class="button-primary">' . esc_html__( 'Sure! I\'d love to help', 'powerpack' ) . '</a>';
		echo '&nbsp;<a href="' . esc_url( $optout_url ) . '" class="button-secondary">' . esc_html__( 'No thanks', 'powerpack' ) . '</a>';
		echo '</p></div>';
		?>
		<script type="text/javascript">
		;(function($) {
			$('a[href="#pp-what-we-collect"]').on('click', function(e) {
				e.preventDefault();
				$( $(this).attr('href') ).slideToggle('fast');
			});
		})(jQuery);
		</script>
		<?php
	}

	/**
	 * Render notice for plugin review.
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function review_plugin_notice() {
		if ( 'yes' === get_option( 'pp_review_already_did' ) ) {
			return;
		}

		$maybe_later_date = get_option( 'pp_review_later_date' );

		if ( ! empty( $maybe_later_date ) ) {
			$diff = round( ( time() - strtotime( $maybe_later_date ) ) / 24 / 60 / 60 );

			if ( $diff < 7 ) {
				return;
			}
		} else {
			$install_date = get_option( 'pp_install_date' );

			if ( ! $install_date || empty( $install_date ) ) {
				return;
			}

			$diff = round( ( time() - strtotime( $install_date ) ) / 24 / 60 / 60 );

			if ( $diff < 7 ) {
				return;
			}
		}

		$nonce = wp_create_nonce( 'pp_admin_notice_nonce' );

		$review_url = 'https://wordpress.org/support/plugin/powerpack-lite-for-elementor/reviews/?filter=5#new-post';
		$maybe_later_url = add_query_arg(
			array(
				'pp_admin_action' 	=> 'review_maybe_later',
				'_nonce'			=> $nonce,
			)
		);
		$already_did_url = add_query_arg(
			array(
				'pp_admin_action' 	=> 'review_already_did',
				'_nonce'			=> $nonce,
			)
		);

		$notice = sprintf(
			// translators: %1$s denotes plugin name, %2$s denotes opening anchor tag, %3$s denots closing anchor tag.
			__( 'Hey, It seems you have been using %1$s for at least 7 days now - that\'s awesome!<br>Could you please do us a BIG favor and give it a %2$s5-star rating on WordPress?%3$s This will help us spread the word and boost our motivation - thanks!', 'powerpack' ),
			'<strong>PowerPack Elements Lite</strong>',
			'<a href="' . esc_url( $review_url ) . '" target="_blank">',
			'</a>'
		);
		?>
		<?php $this->print_notices_common_style(); ?>
		<style>
		.pp-review-notice {
			display: block;
		}
		.pp-review-notice p {
			line-height: 22px;
		}
		.pp-review-notice .pp-notice-buttons {
			margin: 10px 0;
			display: flex;
			align-items: center;
		}
		.pp-review-notice .pp-notice-buttons a {
			margin-right: 13px;
			text-decoration: none;
		}
		.pp-review-notice .pp-notice-buttons .dashicons {
			margin-right: 5px;
		}
		</style>
		<div class="pp-review-notice pp--notice notice notice-info is-dismissible">
			<p><?php echo $notice; // @codingStandardsIgnoreLine. ?></p>
			<div class="pp-notice-buttons">
				<a href="<?php echo esc_url( $review_url ); ?>" target="_blank" class="pp-button-primary"><?php esc_html_e( 'Ok, you deserve it', 'powerpack' ); ?></a>
				<span class="dashicons dashicons-calendar"></span>
				<a href="<?php echo esc_url_raw( $maybe_later_url ); ?>"><?php esc_html_e( 'Nope, maybe later', 'powerpack' ); ?></a>
				<span class="dashicons dashicons-smiley"></span>
				<a href="<?php echo esc_url_raw( $already_did_url ); ?>"><?php esc_html_e( 'I already did', 'powerpack' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Render notice for Pro upgrade.
	 *
	 * @access 	public
	 * @return 	void
	 */
	public function pro_upgrade_notice() {
		if ( 'yes' === get_option( 'pp_do_not_upgrade_to_pro' ) ) {
			return;
		}

		$install_date = get_option( 'pp_install_date' );

		if ( ! $install_date || empty( $install_date ) ) {
			return;
		}

		$diff = round( ( time() - strtotime( $install_date ) ) / 24 / 60 / 60 );

		if ( $diff < 23 ) {
			return;
		}

		$nonce = wp_create_nonce( 'pp_admin_notice_nonce' );

		$upgrade_url = 'https://powerpackelements.com/upgrade/?utm_source=wporg&utm_medium=notice&utm_campaign=lite_offer';
		$no_upgrade_url = add_query_arg(
			array(
				'pp_admin_action' 	=> 'do_not_upgrade',
				'_nonce'			=> $nonce,
			)
		);

		$notice = __( '<strong>Exclusive Offer!</strong> We don\'t run promotions very often. But for a limited time we are offering an exclusive <strong>20% discount</strong> to all users of Free PowerPack Elementor addon.', 'powerpack' );
		$button_text = __( 'Get this offer', 'powerpack' );

		if ( class_exists( 'WooCommerce' ) ) {
			$notice = __( 'Upgrade to <strong>PowerPack Pro for Elementor</strong> and Get WooCommerce Elementor Widgets like Product Grid, Checkout Styler, Off-Canvas Cart, etc.', 'powerpack' );
			$upgrade_url = 'http://powerpackelements.com/woocommerce-elementor-widgets/?utm_source=wporg&utm_medium=notice&utm_campaign=woo_upgrade';
			$button_text = __( 'Explore now', 'powerpack' );
		}
		?>
		<?php $this->print_notices_common_style(); ?>
		<style>
		.pp-upgrade-notice {
			padding-left: 5px;
			padding-top: 5px;
			padding-bottom: 5px;
			border: 0;
		}
		.pp-upgrade-notice .pp-notice-wrap {
			display: flex;
			align-items: center;
		}
		.pp-upgrade-notice .pp-notice-col-left {
			width: <?php echo class_exists( 'WooCommerce' ) ? 8 : 10; ?>%;
    		max-width: 75px;
		}
		.pp-upgrade-notice .pp-notice-col-right {
			padding-left: 15px;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
		}
		.pp-upgrade-notice img {
			display: block;
			max-width: 100%;
		}
		.pp-upgrade-notice p {
			line-height: 22px;
			font-size: 14px;
			margin: 0;
		}
		.pp-upgrade-notice .pp-notice-buttons {
			margin: 10px 0;
			margin-bottom: 0;
			display: flex;
    		align-items: center;
		}
		.pp-upgrade-notice .pp-notice-buttons .dashicons {
			margin-right: 5px;
		}
		</style>
		<div class="pp-upgrade-notice pp--notice notice notice-success is-dismissible">
			<div class="pp-notice-wrap">
				<div class="pp-notice-col-left">
					<img src="<?php echo POWERPACK_ELEMENTS_LITE_URL; ?>assets/images/icon-256x256.png" />
				</div>
				<div class="pp-notice-col-right">
					<p><?php echo $notice; // @codingStandardsIgnoreLine. ?></p>
					<div class="pp-notice-buttons">
						<a href="<?php echo esc_url( $upgrade_url ); ?>" target="_blank" class="pp-button-primary"><?php echo $button_text; ?></a>
						<a href="<?php echo esc_url_raw( $no_upgrade_url ); ?>"><?php esc_html_e( 'I\'m not interested', 'powerpack' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	private function print_notices_common_style() {
		?>
		<style>
		.pp--notice {
			--brand-color: #4849d7;
			display: block;
			border-top: 0;
			border-bottom: 0;
			border-right: 0;
			border-left-color: var(--brand-color);
			box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);
		}
		.pp--notice a {
			color: var(--brand-color);
		}
		.pp--notice .pp-notice-buttons a {
			margin-right: 13px;
		}
		.pp--notice .pp-notice-buttons a.pp-button-primary {
			background: var(--brand-color);
			color: #fff;
			text-decoration: none;
			padding: 6px 12px;
			border-radius: 4px;
		}
		</style>
		<?php
	}

	/**
	 * Show deactivation feedback popup
	 */
	public function show_deactivation_feedback_popup() {
		$screen = get_current_screen();
		if ( ! isset( $screen ) || $screen->id !== 'plugins' ) {
			return;
		}

		$reasons = $this->get_deactivation_reasons();
		?>
		<div id="<?php echo esc_attr( $this->plugin_slug ); ?>-feedback-popup" class="feedback-popup-overlay">
			<div class="feedback-popup-container">
				<div class="feedback-popup-header">
					<h3><?php esc_html_e( 'Quick Feedback', 'powerpack' ); ?></h3>
				</div>

				<div class="feedback-popup-content">
					<div class="feedback-loader">
						<div class="loader-spinner"></div>
					</div>

					<div class="feedback-form-container">
						<p class="feedback-form-title">
							<?php printf( esc_html__( 'If you have a moment, please let us know why you\'re deactivating %s:', 'powerpack' ), $this->plugin_name ); ?>
						</p>

						<form class="feedback-form" data-plugin-slug="<?php echo esc_attr( $this->plugin_slug ); ?>">
							<?php foreach ( $reasons as $key => $reason ): ?>
								<div class="feedback-reason-item">
									<label class="feedback-reason-label">
										<input type="radio" name="reason" value="<?php echo esc_attr( $key ); ?>" class="feedback-reason-input">
										<span class="feedback-reason-text"><?php echo esc_html( $reason['title'] ); ?></span>
									</label>
									<?php if ( ! empty( $reason['placeholder'] ) ): ?>
										<div class="feedback-reason-details">
											<textarea
												class="feedback-details-input"
												placeholder="<?php echo esc_attr( $reason['placeholder'] ); ?>"
												rows="2"
											></textarea>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>

							<?php if ( $this->config['collect_system_info'] ): ?>
								<div class="feedback-consent">
									<label class="feedback-consent-label">
										<input type="checkbox" class="feedback-consent-checkbox" required>
										<span class="feedback-consent-text">
											<?php printf( esc_html__( 'I agree to share anonymous usage data and basic site details to help improve %s.', 'powerpack' ), $this->plugin_name ); ?>
										</span>
									</label>
								</div>
							<?php endif; ?>

							<div class="feedback-actions">
								<button type="submit" class="button button-primary feedback-submit-btn">
									<?php esc_html_e( 'Submit & Deactivate', 'powerpack' ); ?>
								</button>
								<button type="button" class="button button-secondary feedback-skip-btn">
									<?php esc_html_e( 'Skip & Deactivate', 'powerpack' ); ?>
								</button>
								<button type="button" class="button button-secondary feedback-cancel-btn">
									<?php esc_html_e( 'Cancel', 'powerpack' ); ?>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Get deactivation reasons
	 *
	 * @return array Deactivation reasons
	 */
	private function get_deactivation_reasons() {
		$default_reasons = [
			'not_working' => [
				'title' => esc_html__( 'The plugin is not working', 'powerpack' ),
				'placeholder' => esc_html__( 'Please describe the issue you encountered', 'powerpack' ),
			],
			'found_better' => [
				'title' => esc_html__( 'I found a better alternative', 'powerpack' ),
				'placeholder' => esc_html__( 'Please share which plugin you\'re switching to', 'powerpack' ),
			],
			'no_longer_required' => [
				'title' => esc_html__( 'I no longer need the plugin', 'powerpack' ),
				'placeholder' => ''
			],
			'temporary' => [
				'title' => esc_html__( 'It\'s a temporary deactivation', 'powerpack' ),
				'placeholder' => ''
			],
			'missing_feature' => [
				'title' => esc_html__( 'Missing a feature I need', 'powerpack' ),
				'placeholder' => esc_html__( 'What feature were you looking for?', 'powerpack' ),
			],
			'other' => [
				'title' => esc_html__( 'Other', 'powerpack' ),
				'placeholder' => esc_html__( 'Please share your reason', 'powerpack' ),
			]
		];

		// Merge with custom reasons if provided.
		if ( ! empty( $this->config['custom_deactivation_reasons'] ) ) {
			return array_merge( $default_reasons, $this->config['custom_deactivation_reasons'] );
		}

		return $default_reasons;
	}

	/**
	 * Submit deactivation response.
	 */
	public function submit_deactivation_response() {
		check_ajax_referer( $this->plugin_slug . '_feedback_nonce', 'nonce' );

		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Unauthorized.', 'powerpack' ) ], 403 );
		}

		$reason  = isset( $_POST['reason'] )  ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : '';
		$details = isset( $_POST['details'] ) ? sanitize_textarea_field( wp_unslash( $_POST['details'] ) ) : '';
		$consent = ! empty( $_POST['consent'] );

		$data = [
			'plugin_name'    => $this->plugin_name,
			'plugin_version' => POWERPACK_ELEMENTS_LITE_VER,
			'plugin_slug'    => $this->plugin_slug,
			'reason'         => $reason,
			'details'        => $details,
			'site_url'       => site_url(),
			'timestamp'      => current_time( 'mysql' ),
		];

		// Add system info if consent is given.
		if ( $consent && $this->config['collect_system_info'] ) {
			$data['system_info'] = $this->get_system_info();
		}

		// Send to feedback API.
		$response = $this->send_feedback( $data );

		if ( is_wp_error( $response ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Failed to send feedback', 'powerpack' ) ] );
		}

		wp_send_json_success( [ 'message' => esc_html__( 'Feedback submitted successfully', 'powerpack' ) ] );
	}

	/**
	 * Get system information
	 *
	 * @return array System information
	 */
	private function get_system_info() {
		global $wpdb;

		$server = isset( $_SERVER['SERVER_SOFTWARE'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : 'Unknown';

		return [
			'wp_version'      => get_bloginfo( 'version' ),
			'php_version'     => PHP_VERSION,
			'mysql_version'   => $wpdb->get_var( 'SELECT VERSION()' ),
			'server_software' => $server,
			'wp_memory_limit' => ini_get( 'memory_limit' ),
			'wp_debug'        => ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Enabled' : 'Disabled',
			'wp_multisite'    => is_multisite() ? 'Yes' : 'No',
			'wp_language'     => get_locale(),
			'active_theme'    => wp_get_theme()->get( 'Name' ),
			'active_plugins'  => $this->get_active_plugins_info(),
		];
	}

	/**
	 * Get active plugins information
	 *
	 * @return array Active plugins info
	 */
	private function get_active_plugins_info() {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins    = get_plugins();
		$active_plugins = (array) get_option( 'active_plugins', [] );
		$plugins_info   = [];

		foreach ( $active_plugins as $plugin_path ) {
			if ( isset( $all_plugins[ $plugin_path ] ) ) {
				$meta = $all_plugins[ $plugin_path ];
				$plugins_info[] = [
					'name'    => isset( $meta['Name'] ) ? $meta['Name'] : $plugin_path,
					'version' => isset( $meta['Version'] ) ? $meta['Version'] : '',
				];
			}
		}

		return $plugins_info;
	}

	/**
	 * Send feedback to API
	 *
	 * @param array $data Feedback data
	 * @return array|WP_Error Response
	 */
	private function send_feedback( $data ) {
		$api_url = isset( $this->config['feedback_api_url'] ) ? esc_url_raw( $this->config['feedback_api_url'] ) : '';

		if ( empty( $api_url ) ) {
			return new \WP_Error( 'no_api_url', esc_html__( 'No feedback API URL configured', 'powerpack' ) );
		}

		$json_data = json_encode( $data );

		if ( false === $json_data ) {
			return new \WP_Error( 'json_encode_error', json_last_error_msg() );
		}

		return wp_remote_post(
			$api_url,
			[
				'timeout' => 30,
				'body'    => $json_data,
				'headers' => [
					'Content-Type' => 'application/json'
				]
			]
		);
	}

	/**
	 * Get singleton class instance.
	 *
	 * @access 	public
	 * @return 	object
	 */
	public static function get_instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}
}
