<?php
namespace PowerpackElementsLite\Classes;

use PowerpackElementsLite\Classes\PP_Admin_Settings;

/**
 * Tracking functions for reporting plugin usage to the PowerPack Elements site for users that have opted in
 *
 * @copyright Copyright (c) 2015, Pippin Williamson
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.8.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Usage tracking
 *
 * @access public
 * @since  1.8.2
 * @return void
 */
class UsageTracking {
	/**
	 * The data to send to the PPE site
	 *
	 * @access private
	 */
	private $data;

	/**
	 * Get things going
	 *
	 * @access public
	 */
	public function __construct() {
		add_action( 'init', [ $this, 'schedule_send' ] );
		add_action( 'pp_admin_after_settings_saved', [ $this, 'check_for_settings_optin' ], 10, 2 );
		add_action( 'admin_init', [ $this, 'act_on_tracking_decision' ] );
		add_action( 'admin_notices', [ $this, 'admin_notice' ] );
		add_action( 'init', [ $this, 'create_recurring_schedule' ] );
		add_filter( 'cron_schedules', [ $this, 'cron_add_weekly' ] );
	}

	public function cron_add_weekly( $schedules ) {
		$schedules['ppeweekly'] = array(
			'interval' => 604800,
			'display' => 'Weekly',
		);
		return $schedules;
	}

	public function create_recurring_schedule() {
		//check if event scheduled before
		if ( ! wp_next_scheduled( 'pp_recurring_cron_job' ) ) {
			//schedule event to run after every day
			wp_schedule_event( time(), 'ppeweekly', 'pp_recurring_cron_job' );
		}
	}

	/**
	 * Check if the user has opted into tracking
	 *
	 * @access private
	 * @return bool
	 */
	private function tracking_allowed() {
		$setting = PP_Admin_Settings::get_option( 'pp_allowed_tracking', true, false );

		return 'on' === $setting;
	}

	/**
	 * Setup the data that is going to be tracked
	 *
	 * @access private
	 * @return void
	 */
	private function setup_data() {
		$data = array();

		// Retrieve current theme info
		$theme_data = wp_get_theme();
		$theme = $theme_data->Name . ' ' . $theme_data->Version;

		$data['php_version'] = phpversion();
		$data['edd_version'] = POWERPACK_ELEMENTS_LITE_VER;
		$data['wp_version'] = get_bloginfo( 'version' );
		$data['server'] = isset( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : '';

		$data['install_date'] = PP_Admin_Settings::get_option( 'pp_install_date', true, 'not set' );

		$data['multisite'] = is_multisite();
		$data['url'] = home_url();
		$data['theme'] = $theme;
		$data['email'] = get_bloginfo( 'admin_email' );

		// Retrieve current plugin information
		if ( ! function_exists( 'get_plugins' ) ) {
			include ABSPATH . '/wp-admin/includes/plugin.php';
		}

		$plugins = array_keys( get_plugins() );
		$active_plugins = get_option( 'active_plugins', array() );

		foreach ( $plugins as $key => $plugin ) {
			if ( in_array( $plugin, $active_plugins ) ) {
				// Remove active plugins from list so we can show active and inactive separately
				unset( $plugins[ $key ] );
			}
		}

		$data['active_plugins'] = $active_plugins;
		$data['inactive_plugins'] = $plugins;
		$data['locale'] = ($data['wp_version'] >= 4.7) ? get_user_locale() : get_locale();

		$this->data = $data;
	}

	/**
	 * Send the data to the PPE server
	 *
	 * @access private
	 * @return mixed
	 */
	public function send_checkin( $override = false, $ignore_last_checkin = false ) {
		$home_url = trailingslashit( home_url() );
		// Allows us to stop our own site from checking in, and a filter for our additional sites
		if ( 'https://powerpackelements.com/' === $home_url || apply_filters( 'pp_disable_tracking_checkin', false ) ) {
			return false;
		}

		if ( ! $this->tracking_allowed() && ! $override ) {
			return false;
		}

		// Send a maximum of once per week
		$last_send = $this->get_last_send();
		if ( is_numeric( $last_send ) && $last_send > strtotime( '-1 week' ) && ! $ignore_last_checkin ) {
			return false;
		}

		$this->setup_data();

		$request = wp_remote_post(
			'https://powerpackelements.com/?edd_action=checkin', array(
			'method' => 'POST',
			'timeout' => 20,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking' => true,
			'body' => $this->data,
			'user-agent' => 'EDD/' . POWERPACK_ELEMENTS_LITE_VER . '; ' . get_bloginfo( 'url' ),
			)
		);

		if ( is_wp_error( $request ) ) {
			return $request;
		}

		PP_Admin_Settings::update_option( 'pp_tracking_last_send', time(), true );

		return true;
	}

	/**
	 * Check for a new opt-in on settings save
	 *
	 * This runs during the sanitation of General settings, thus the return
	 *
	 * @access public
	 * @return array
	 */
	public function check_for_settings_optin() {
		// Send an initial check in on settings save

		if ( isset( $_POST['pp_allowed_tracking'] ) && 'on' == $_POST['pp_allowed_tracking'] ) {
			$this->send_checkin( true );
		}
	}

	public function act_on_tracking_decision() {
		if ( isset( $_GET['ppe_action'] ) ) {
			if ( 'pp_opt_into_tracking' == $_GET['ppe_action'] ) {
				$this->check_for_optin();
			}

			if ( 'pp_opt_out_of_tracking' == $_GET['ppe_action'] ) {
				$this->check_for_optout();
			}
		}
	}

	/**
	 * Check for a new opt-in via the admin notice
	 *
	 * @access public
	 * @return void
	 */
	public function check_for_optin() {
		PP_Admin_Settings::update_option( 'pp_allowed_tracking', 'on', true );

		$this->send_checkin( true );

		PP_Admin_Settings::update_option( 'pp_tracking_notice', '1', true );
	}

	/**
	 * Check for a new opt-in via the admin notice
	 *
	 * @access public
	 * @return void
	 */
	public function check_for_optout() {
		PP_Admin_Settings::delete_option( 'pp_allowed_tracking' );
		PP_Admin_Settings::update_option( 'pp_tracking_notice', '1', true );
		wp_redirect( remove_query_arg( 'ppe_action' ) );
		exit;
	}

	/**
	 * Get the last time a checkin was sent
	 *
	 * @access private
	 * @return false|string
	 */
	private function get_last_send() {
		return PP_Admin_Settings::get_option( 'pp_tracking_last_send' );
	}

	/**
	 * Schedule a weekly checkin
	 *
	 * @access public
	 * @return void
	 */
	public function schedule_send() {
		// We send once a week (while tracking is allowed) to check in, which can be used to determine active sites
		add_action( 'pp_recurring_cron_job', array( $this, 'send_checkin' ) );
	}

	/**
	 * Display the admin notice to users that have not opted-in or out
	 *
	 * @access public
	 * @return void
	 */
	public function admin_notice() {
		$hide_notice = PP_Admin_Settings::get_option( 'pp_tracking_notice', true );

		if ( $hide_notice ) {
			return;
		}

		if ( $this->tracking_allowed() ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( stristr( network_site_url( '/' ), 'dev' ) !== false
			|| stristr( network_site_url( '/' ), 'localhost' ) !== false
			|| stristr( network_site_url( '/' ), ':8888' ) !== false// This is common with MAMP on OS X
		) {
			PP_Admin_Settings::update_option( 'pp_tracking_notice', '1', true );
		} else {

			$optin_url = add_query_arg( 'ppe_action', 'pp_opt_into_tracking' );
			$optout_url = add_query_arg( 'ppe_action', 'pp_opt_out_of_tracking' );

			$source = substr( md5( get_bloginfo( 'name' ) ), 0, 10 );
			$store_url = 'https://powerpackelements.com/pricing/?utm_source=' . $source . '&utm_medium=admin&utm_term=notice&utm_campaign=PPEUsageTracking';
			$what_we_collect = esc_html__( 'Click here to check what we collect.', 'powerpack' );

			echo '<div class="notice notice-info updated"><p>';
			printf(
				__( 'Want to help make <strong>%1$s</strong> even more awesome? Allow us to <a href="#pp-what-we-collect" title="%2$s">collect non-sensitive</a> diagnostic data and plugin usage information. Opt-in to tracking and we will send you a special 15%3$s discount code for <a href="%4$s">Premium Upgrade</a>.', 'powerpack' ),
				'PowerPack Elements',
				$what_we_collect,
				'%',
				$store_url
			);
			echo '</p>';
			echo '<p id="pp-what-we-collect" style="display: none;">';
			echo esc_html__( 'We collect WordPress and PHP version, plugin and theme version, server environment, website, and email address to send you the discount code. No sensitive data is tracked.', 'powerpack' );
			echo '</p>';
			echo '<p>';
			echo '<a href="' . esc_url( $optin_url ) . '" class="button-primary">' . __( 'Sure! I\'d love to help', 'powerpack' ) . '</a>';
			echo '&nbsp;<a href="' . esc_url( $optout_url ) . '" class="button-secondary">' . __( 'No thanks', 'powerpack' ) . '</a>';
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
	}

	public static function get_instance() {
		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}
}
