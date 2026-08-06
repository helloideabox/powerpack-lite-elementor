<?php
/**
 * Names the shared settings screen expects.
 *
 * The settings app, its REST controller and the settings registry are shared
 * with the paid edition, and they read the library through a handful of plain
 * functions. This edition names its own helpers differently, so the difference
 * is reconciled here — once, in one file — rather than by forking three larger
 * ones.
 *
 * Everything below either delegates to this plugin's own helper or fills a gap
 * the paid edition happens to have and this one does not.
 *
 * @package PowerPackElementsLite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Every widget this edition ships, as name => title.
 *
 * @since x.x.x
 * @return array
 */
function pp_get_modules() {
	return powerpack_elements_lite_get_modules();
}

/**
 * Every extension this edition ships, as name => title.
 *
 * @since x.x.x
 * @return array
 */
function pp_get_extensions() {
	return powerpack_elements_lite_get_extensions();
}

/**
 * The stored enabled-widget value, whatever shape it is in.
 *
 * @since x.x.x
 * @return array|string
 */
function pp_get_enabled_modules() {
	$enabled = get_option( 'pp_elementor_modules' );

	if ( ! is_array( $enabled ) && 'disabled' !== $enabled ) {
		$enabled = pp_get_modules();
	}

	return apply_filters( 'pp_elementor_enabled_modules', $enabled );
}

/**
 * The enabled widgets as a lookup table.
 *
 * The option is a plain list of names, but an unsaved option reads as a
 * name => title map and an empty selection reads as the string 'disabled'.
 * All three are flattened to name => true, once per request.
 *
 * @since x.x.x
 * @param bool $reset Internal. Flush the cached lookup.
 * @return array
 */
function pp_get_enabled_modules_lookup( $reset = false ) {
	static $lookup = null;

	if ( $reset ) {
		$lookup = null;

		return [];
	}

	if ( null !== $lookup ) {
		return $lookup;
	}

	$enabled = pp_get_enabled_modules();
	$lookup  = [];

	if ( is_array( $enabled ) ) {
		foreach ( $enabled as $key => $value ) {
			$name = is_int( $key ) ? $value : $key;

			if ( is_string( $name ) ) {
				$lookup[ $name ] = true;
			}
		}
	}

	return $lookup;
}

/**
 * Flush the cached lookup when the option is written.
 *
 * @since x.x.x
 * @return void
 */
function pp_flush_enabled_modules_cache() {
	pp_get_enabled_modules_lookup( true );
}
add_action( 'add_option_pp_elementor_modules', 'pp_flush_enabled_modules_cache' );
add_action( 'update_option_pp_elementor_modules', 'pp_flush_enabled_modules_cache' );
add_action( 'delete_option_pp_elementor_modules', 'pp_flush_enabled_modules_cache' );

/**
 * Enabled widget names, limited to those this edition still ships.
 *
 * @since x.x.x
 * @return array
 */
function pp_get_enabled_module_names() {
	$all     = array_keys( pp_get_modules() );
	$enabled = array_keys( pp_get_enabled_modules_lookup() );

	return array_values( array_intersect( $all, $enabled ) );
}

/**
 * Counts for the widget library.
 *
 * @since x.x.x
 * @return array
 */
function pp_get_modules_stats() {
	$total   = count( pp_get_modules() );
	$enabled = count( pp_get_enabled_module_names() );

	return [
		'total'    => $total,
		'enabled'  => $enabled,
		'disabled' => $total - $enabled,
		'percent'  => $total ? (int) round( ( $enabled / $total ) * 100 ) : 0,
	];
}

/**
 * Widgets used somewhere on this site, and widgets used nowhere.
 *
 * @since x.x.x
 * @param string $status 'used' or 'notused'.
 * @return array
 */
function pp_get_filter_modules( $status = '' ) {
	return powerpack_elements_lite_get_filter_modules( $status );
}

/**
 * Send "upgrade" to the page that sells the paid edition.
 *
 * @since x.x.x
 * @return string
 */
function pp_lite_settings_upgrade_url() {
	return 'https://powerpackelements.com/upgrade/';
}
add_filter( 'pp_settings_upgrade_url', 'pp_lite_settings_upgrade_url' );
