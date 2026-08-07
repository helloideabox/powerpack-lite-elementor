<?php
/**
 * What the settings screen needs that this plugin did not already have.
 *
 * The settings app, its REST controller and the settings registry are the same
 * code as the paid edition's. What is not shared is the name of anything: every
 * function here is prefixed for this plugin alone, and this plugin's copy of the
 * shared code calls these names rather than the paid edition's.
 *
 * That is deliberate, and it is the whole reason both plugins can be active at
 * once. Two declarations of one function name is a fatal, and which of the two
 * declares first depends on the order WordPress happens to load them in. With no
 * name in common there is nothing to collide, and the paid edition needs to know
 * nothing about this one.
 *
 * @package PowerPackElementsLite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The enabled widgets as a lookup table.
 *
 * The stored option is a list of names, but an unsaved one reads as false and
 * an all-off selection made on the old screen reads as the string 'disabled'.
 * powerpack_elements_lite_get_enabled_modules() resolves all three; this
 * flattens the result to name => true, once per request.
 *
 * @since x.x.x
 * @param bool $reset Internal. Flush the cached lookup.
 * @return array
 */
function powerpack_elements_lite_get_enabled_modules_lookup( $reset = false ) {
	static $lookup = null;

	if ( $reset ) {
		$lookup = null;

		return [];
	}

	if ( null !== $lookup ) {
		return $lookup;
	}

	$enabled = powerpack_elements_lite_get_enabled_modules();
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
function powerpack_elements_lite_flush_enabled_modules_cache() {
	powerpack_elements_lite_get_enabled_modules_lookup( true );
}
add_action( 'add_option_pp_elementor_modules', 'powerpack_elements_lite_flush_enabled_modules_cache' );
add_action( 'update_option_pp_elementor_modules', 'powerpack_elements_lite_flush_enabled_modules_cache' );
add_action( 'delete_option_pp_elementor_modules', 'powerpack_elements_lite_flush_enabled_modules_cache' );

/**
 * Enabled widget names, limited to those this edition still ships.
 *
 * @since x.x.x
 * @return array
 */
function powerpack_elements_lite_get_enabled_module_names() {
	$all     = array_keys( powerpack_elements_lite_get_modules() );
	$enabled = array_keys( powerpack_elements_lite_get_enabled_modules_lookup() );

	return array_values( array_intersect( $all, $enabled ) );
}

/**
 * Counts for the widget library.
 *
 * @since x.x.x
 * @return array
 */
function powerpack_elements_lite_get_modules_stats() {
	$total   = count( powerpack_elements_lite_get_modules() );
	$enabled = count( powerpack_elements_lite_get_enabled_module_names() );

	return [
		'total'    => $total,
		'enabled'  => $enabled,
		'disabled' => $total - $enabled,
		'percent'  => $total ? (int) round( ( $enabled / $total ) * 100 ) : 0,
	];
}

/**
 * Send "upgrade" to the page that sells the paid edition.
 *
 * @since x.x.x
 * @return string
 */
function powerpack_elements_lite_settings_upgrade_url() {
	return 'https://powerpackelements.com/upgrade/';
}
add_filter( 'pp_settings_upgrade_url', 'powerpack_elements_lite_settings_upgrade_url' );
