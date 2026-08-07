<?php
/**
 * Nothing this plugin declares may collide with the paid edition.
 *
 * Both plugins may be active at once. Two declarations of one function name is
 * a fatal, and which plugin declares first depends on the order of names inside
 * a stored option — so a collision is not something that fails consistently. It
 * fails on some installs and not others, at load, with a white screen.
 *
 * The rule this enforces: no file in this plugin declares a function at global
 * scope whose name the paid edition also declares. Every helper is prefixed for
 * this plugin alone, and this plugin's copy of the shared settings code calls
 * those names rather than the paid edition's.
 *
 * Set PP_PRO_PATH to check against a real checkout of the paid edition. Without
 * it the paid edition's names are taken from the list below, which is what it
 * declared when this was written.
 *
 * @package PowerPackElementsLite
 */

require __DIR__ . '/helpers.php';

$lite = dirname( __DIR__ );

/**
 * Every function declared at global scope in a tree, with the file it is in.
 *
 * Indentation is the test: a function at column zero is global, one indented
 * inside a class or a conditional is not. Good enough for a naming rule, and it
 * needs neither a parser nor the plugin loaded.
 *
 * @param string $root Directory to walk.
 * @param array  $skip Path fragments to ignore.
 * @return array name => relative file path.
 */
function pp_global_functions( $root, $skip = [] ) {
	$found = [];
	$files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $root ) );

	foreach ( $files as $file ) {
		$path = $file->getPathname();

		if ( 'php' !== strtolower( $file->getExtension() ) ) {
			continue;
		}

		foreach ( array_merge( [ '/node_modules/', '/vendor/' ], $skip ) as $fragment ) {
			if ( false !== strpos( $path, $fragment ) ) {
				continue 2;
			}
		}

		if ( preg_match_all( '/^function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/m', (string) file_get_contents( $path ), $matches ) ) {
			foreach ( $matches[1] as $name ) {
				$found[ $name ] = str_replace( $root . '/', '', $path );
			}
		}
	}

	return $found;
}

/*
 * The test harness is not shipped and never loads inside WordPress, so its
 * helpers are exempt.
 */
$ours = pp_global_functions( $lite, [ '/tests/' ] );

$pro_path = getenv( 'PP_PRO_PATH' );

if ( $pro_path && is_dir( $pro_path ) ) {
	$theirs = array_keys( pp_global_functions( $pro_path, [ '/tests/' ] ) );
	pp_note( 'paid edition read from', $pro_path );
} else {
	$theirs = [
		'pp_get_modules',
		'pp_get_extensions',
		'pp_get_enabled_modules',
		'pp_get_enabled_modules_lookup',
		'pp_get_enabled_module_names',
		'pp_flush_enabled_modules_cache',
		'pp_get_modules_stats',
		'pp_get_filter_modules',
		'pp_get_enabled_extensions',
		'pp_init',
		'pp_get_license_key',
	];
	pp_note( 'paid edition read from', 'the built in list (set PP_PRO_PATH to check a checkout)' );
}

pp_note( 'global functions here', count( $ours ) );

$clashes = [];

foreach ( array_intersect( array_keys( $ours ), $theirs ) as $name ) {
	$clashes[] = $name . ' in ' . $ours[ $name ];
}

pp_check( 'no global function name is shared with the paid edition', $clashes, [] );

/*
 * A weaker rule that catches the same mistake earlier: this plugin has no
 * business declaring a bare pp_* function at all. Both editions have used that
 * prefix for years, so any new one is a collision waiting for the paid edition
 * to add the same name.
 */
$bare_prefixed = [];

foreach ( $ours as $name => $file ) {
	if ( 0 === strpos( $name, 'pp_' ) ) {
		$bare_prefixed[] = $name . ' in ' . $file;
	}
}

pp_check( 'no global function uses the bare pp_ prefix', $bare_prefixed, [] );

/* ---------------------------------------------------------------------------
 * The plugin also has to stand down when the paid edition is active, which it
 * decides before declaring anything. Check the guard is still the first thing
 * the file does, and that it does not depend on the paid edition having loaded.
 * ------------------------------------------------------------------------ */

$bootstrap = (string) file_get_contents( $lite . '/powerpack-lite-elementor.php' );
$guard_at  = strpos( $bootstrap, "'powerpack-elements.php' === basename(" );
$define_at = strpos( $bootstrap, "define( 'POWERPACK_ELEMENTS_LITE_VER'" );

pp_check( 'the stand down reads the active plugin list', false !== $guard_at, true );
pp_check( 'it runs before anything is defined', $guard_at < $define_at, true );

pp_summary();
