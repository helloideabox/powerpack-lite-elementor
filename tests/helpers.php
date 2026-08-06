<?php
/**
 * Shared harness for the settings screen tests.
 *
 * These are deliberately dependency free: plain PHP, run from the CLI, no
 * PHPUnit. Two of them stub the WordPress option API in memory and need no
 * install at all; the rest boot a real WordPress.
 *
 * @package PowerPackElementsLite
 */

$GLOBALS['pp_pass'] = 0;
$GLOBALS['pp_fail'] = 0;

/**
 * Assert strict equality.
 *
 * @param string $label    What is being checked.
 * @param mixed  $actual   Value produced.
 * @param mixed  $expected Value wanted.
 * @return void
 */
function pp_check( $label, $actual, $expected ) {
	if ( $actual === $expected ) {
		$GLOBALS['pp_pass']++;
		echo "  ok   $label\n";
		return;
	}

	$GLOBALS['pp_fail']++;
	echo "  FAIL $label\n";
	echo '       expected: ' . var_export( $expected, true ) . "\n";
	echo '       actual:   ' . var_export( $actual, true ) . "\n";
}

/**
 * Print an informational line that is not an assertion.
 *
 * @param string $label Description.
 * @param mixed  $value Value to show.
 * @return void
 */
function pp_note( $label, $value ) {
	if ( ! is_scalar( $value ) ) {
		$value = json_encode( $value );
	}

	echo "  --   $label: $value\n";
}

/**
 * Print the tally and exit with a status the shell can use.
 *
 * @return void
 */
function pp_summary() {
	echo "\n";

	if ( $GLOBALS['pp_fail'] ) {
		echo "FAILED: {$GLOBALS['pp_fail']}\n";
	}

	echo "passed: {$GLOBALS['pp_pass']}\n";

	exit( $GLOBALS['pp_fail'] ? 1 : 0 );
}

/**
 * Locate the WordPress root by walking up from the plugin directory.
 *
 * Set PP_WP_ROOT to override, for installs laid out unusually.
 *
 * @return string Path to the directory holding wp-load.php.
 */
function pp_wp_root() {
	$override = getenv( 'PP_WP_ROOT' );

	if ( $override && file_exists( rtrim( $override, '/' ) . '/wp-load.php' ) ) {
		return rtrim( $override, '/' );
	}

	$dir = dirname( __DIR__ );

	for ( $i = 0; $i < 6; $i++ ) {
		$dir = dirname( $dir );

		if ( file_exists( $dir . '/wp-load.php' ) ) {
			return $dir;
		}
	}

	fwrite( STDERR, "Could not find wp-load.php. Set PP_WP_ROOT.\n" );
	exit( 1 );
}

/**
 * Boot WordPress for the tests that need a real install.
 *
 * @param bool $admin Whether to load the admin context too.
 * @return void
 */
function pp_boot_wp( $admin = false ) {
	if ( ! defined( 'WP_USE_THEMES' ) ) {
		define( 'WP_USE_THEMES', false );
	}

	if ( $admin && ! defined( 'WP_ADMIN' ) ) {
		define( 'WP_ADMIN', true );
	}

	$_SERVER['HTTP_HOST']      = 'localhost';
	$_SERVER['REQUEST_URI']    = '/';
	$_SERVER['REQUEST_METHOD'] = 'GET';

	require_once pp_wp_root() . '/wp-load.php';

	if ( $admin ) {
		require_once ABSPATH . 'wp-admin/includes/admin.php';
	}
}

/**
 * Switch to an administrator, since every route under test requires one.
 *
 * @return WP_User
 */
function pp_become_admin() {
	$admins = get_users( [ 'role' => 'administrator', 'number' => 1 ] );

	if ( empty( $admins ) ) {
		fwrite( STDERR, "No administrator on this install.\n" );
		exit( 1 );
	}

	wp_set_current_user( $admins[0]->ID );

	return $admins[0];
}
