<?php
/**
 * Uninstall routine.
 *
 * Runs only when the plugin is deleted from the Plugins screen, and only when
 * the site has opted in via the "Delete data on uninstall" setting. Deactivating
 * the plugin never removes anything.
 *
 * WordPress loads this file in isolation — the plugin itself is not bootstrapped
 * — so the key list below is deliberately self-contained rather than derived
 * from PP_Settings_Registry. Keep the two in step when adding a setting.
 *
 * Every function here is prefixed for this plugin alone. The paid edition ships
 * its own uninstall routine, and deleting one plugin must never reach into the
 * other's data.
 *
 * @package PowerPackElementsLite
 * @since 3.0.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Option keys written by the plugin.
 *
 * @since 3.0.0
 * @return array
 */
function powerpack_elements_lite_uninstall_option_keys() {
	return [
		// Core settings.
		'pp_elementor_settings',
		'pp_elementor_modules',
		'pp_elementor_extensions',
		'pp_elementor_used_modules',
		'pp_elementor_notused_modules',

		// Integration credentials.
		'pp_instagram_access_token',

		// Internal state.
		'pp_override_ms',
		'pp_delete_data_on_uninstall',
		'pp_plugin_activated',
		'pp_install_date',

		// Prompts this plugin has shown, and the answers given.
		'pp_tracking_notice',
		'pp_tracking_last_send',
		'pp_review_already_did',
		'pp_review_later_date',
		'pp_do_not_upgrade_to_pro',
	];
}

/**
 * User meta keys written by the plugin.
 *
 * @since 3.0.0
 * @return array
 */
function powerpack_elements_lite_uninstall_user_meta_keys() {
	return [
		'pp_lite_welcome_setup_dismissed',
	];
}

/**
 * Delete every trace of the plugin from the current site.
 *
 * @since 3.0.0
 * @return void
 */
function powerpack_elements_lite_uninstall_site() {
	global $wpdb;

	foreach ( powerpack_elements_lite_uninstall_option_keys() as $key ) {
		delete_option( $key );
	}

	foreach ( powerpack_elements_lite_uninstall_user_meta_keys() as $key ) {
		delete_metadata( 'user', 0, $key, '', true );
	}

	/*
	 * Transients are matched by prefix, and only by the prefixes this plugin
	 * owns outright. The bare 'pp_' prefix is deliberately not swept: the paid
	 * edition writes it too, and a site may have both installed, so removing
	 * this plugin must not clear the other's caches.
	 */
	foreach ( [ 'pp_lite_', 'ppe_lite_' ] as $prefix ) {
		$like = $wpdb->esc_like( '_transient_' . $prefix ) . '%';
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

		$like = $wpdb->esc_like( '_transient_timeout_' . $prefix ) . '%';
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}
}

/**
 * Whether the current site opted in to data deletion.
 *
 * Defaults to false: keeping data is the safe default, so an accidental delete
 * of the plugin never destroys a site's configuration.
 *
 * @since 3.0.0
 * @return bool
 */
function powerpack_elements_lite_uninstall_opted_in() {
	return (bool) get_option( 'pp_delete_data_on_uninstall' );
}

if ( is_multisite() ) {
	$pp_lite_site_ids = get_sites( [
		'fields' => 'ids',
		'number' => 0,
	] );

	foreach ( $pp_lite_site_ids as $pp_lite_site_id ) {
		switch_to_blog( $pp_lite_site_id );

		// Each site keeps its own preference, so one site opting out is
		// respected even when the plugin is deleted network wide.
		if ( powerpack_elements_lite_uninstall_opted_in() ) {
			powerpack_elements_lite_uninstall_site();
		}

		restore_current_blog();
	}

	if ( (bool) get_site_option( 'pp_delete_data_on_uninstall' ) ) {
		foreach ( powerpack_elements_lite_uninstall_option_keys() as $pp_lite_key ) {
			delete_site_option( $pp_lite_key );
		}
	}
} elseif ( powerpack_elements_lite_uninstall_opted_in() ) {
	powerpack_elements_lite_uninstall_site();
}
