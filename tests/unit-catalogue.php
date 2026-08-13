<?php
/**
 * The widget catalogue, and the line between what this edition ships and what
 * it only advertises.
 *
 * PP_Config::get_widget_info() carries both: the widgets this plugin contains,
 * and the paid edition's, marked 'is_pro', so the settings screen and the
 * editor can promote them. Everything that registers a widget or decides what
 * a save may enable has to see only the first set, and there is exactly one
 * place that separates them — PP_Helper::get_widgets_list().
 *
 * If that filter is ever dropped, this plugin tries to instantiate widget
 * classes it does not contain, and paid widget names become writable into
 * pp_elementor_modules. Both fail loudly here first.
 *
 * WordPress is stubbed in memory, so this needs no install and no database.
 *
 * @package PowerPackElementsLite
 */

require __DIR__ . '/helpers.php';

define( 'ABSPATH', true );

// The catalogue and its two accessors touch nothing else.
function esc_html__( $t, $d = '' ) { return $t; }
function apply_filters( $h, $v ) { return $v; }
function apply_filters_ref_array( $h, $a ) { return $a[0]; }
function wp_json_encode( $v ) { return json_encode( $v ); }
function _deprecated_hook( ...$a ) {}
function has_filter( ...$a ) { return false; }
// helper-functions.php registers the enabled-modules cache flush, and the
// settings upgrade URL, on load.
function add_action( ...$a ) {}
function add_filter( ...$a ) {}

require_once dirname( __DIR__ ) . '/classes/class-pp-helper.php';
require_once dirname( __DIR__ ) . '/classes/class-pp-config.php';
require_once dirname( __DIR__ ) . '/includes/helper-functions.php';

use PowerpackElementsLite\Classes\PP_Config;
use PowerpackElementsLite\Classes\PP_Helper;

echo "Catalogue\n";

$catalogue = PP_Config::get_widget_info();
$shipped   = PP_Helper::get_widgets_list();
$paid      = PP_Config::get_pro_widgets();

$flat = [];

foreach ( $catalogue as $group => $widgets ) {
	foreach ( $widgets as $key => $widget ) {
		$flat[ $key ] = $widget + [ 'group' => $group ];
	}
}

$paid_keys = array_keys( array_filter( $flat, function ( $w ) { return ! empty( $w['is_pro'] ); } ) );

pp_note( 'groups', count( $catalogue ) );
pp_note( 'catalogue', count( $flat ) );
pp_note( 'shipped', count( $shipped ) );
pp_note( 'paid', count( $paid_keys ) );

pp_check( 'catalogue is the shipped set plus the paid set', count( $flat ), count( $shipped ) + count( $paid_keys ) );
pp_check( 'get_pro_widgets() returns every paid widget and nothing else', array_keys( $paid ), $paid_keys );

/* ---------------------------------------------------------------------------
 * No paid widget may reach registration or the save allowlist.
 * ------------------------------------------------------------------------ */

$leaked = array_intersect( $paid_keys, array_keys( $shipped ) );
pp_check( 'no paid widget in get_widgets_list()', array_values( $leaked ), [] );

$paid_names    = array_column( $paid, 'name' );
$shipped_names = array_keys( powerpack_elements_lite_get_modules() );

pp_check(
	'no paid widget name in the module list',
	array_values( array_intersect( $paid_names, $shipped_names ) ),
	[]
);

/* ---------------------------------------------------------------------------
 * Every entry is complete, and no name is claimed twice.
 * ------------------------------------------------------------------------ */

$incomplete = [];
$names      = [];
$duplicates = [];

foreach ( $flat as $key => $widget ) {
	foreach ( [ 'name', 'title', 'icon', 'categories' ] as $required ) {
		if ( empty( $widget[ $required ] ) ) {
			$incomplete[] = "$key.$required";
		}
	}

	if ( isset( $names[ $widget['name'] ] ) ) {
		$duplicates[] = $widget['name'];
	}

	$names[ $widget['name'] ] = $key;
}

pp_check( 'every entry has a name, title, icon and categories', $incomplete, [] );
pp_check( 'every widget name is unique across the catalogue', $duplicates, [] );

/* ---------------------------------------------------------------------------
 * The editor's promotion panel runs JSON.parse() over 'categories'.
 * ------------------------------------------------------------------------ */

$unparsable = [];

foreach ( $paid as $key => $widget ) {
	if ( ! is_string( $widget['categories'] ) || ! is_array( json_decode( $widget['categories'], true ) ) ) {
		$unparsable[] = $key;
	}
}

pp_check( 'every paid widget carries JSON parsable categories', $unparsable, [] );

/* ---------------------------------------------------------------------------
 * The module list is what a save is validated against, so a name leaving it
 * silently disables a widget for everyone who has saved. Two names did leave
 * it, deliberately: no widget ever answered to either.
 * ------------------------------------------------------------------------ */

$dead = [ 'pp-link-effects', 'pp-hotspots' ];

pp_check(
	'the two retired names match no widget',
	array_values( array_intersect( $dead, array_column( $shipped, 'name' ) ) ),
	[]
);

pp_check( 'their replacements are shipped', array_values( array_diff( [ 'pa-link-effects', 'pp-image-hotspots' ], $shipped_names ) ), [] );

pp_summary();
