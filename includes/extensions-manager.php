<?php
namespace PowerpackElementsLite;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Extensions_Manager {

	const DISPLAY_CONDITIONS 	= 'display-conditions';

	private $_extensions = null;

	public $available_extensions = [
		self::DISPLAY_CONDITIONS,
	];

	/**
	 * Loops though available extensions and registers them
	 *
	 * @since 0.1.0
	 *
	 * @access public
	 * @return void
	 */
	public function register_extensions() {

		$this->_extensions = [];

		$available_extensions = $this->available_extensions;

		foreach ( $available_extensions as $index => $extension_id ) {
			$extension_filename = str_replace( '_', '-', $extension_id );
			$extension_name = str_replace( '-', '_', $extension_id );

			$extension_filename = POWERPACK_ELEMENTS_LITE_PATH . "extensions/{$extension_filename}.php";

			require( $extension_filename );

			$class_name = str_replace( '-', '_', $extension_id );

			$class_name = 'PowerpackElementsLite\Extensions\Extension_' . ucwords( $class_name );

			if ( ! $this->is_available( $extension_name ) )
				unset( $this->available_extensions[ $index ] );

			// Skip extension if it's disabled in admin settings or is dependant on non-exisiting Elementor Pro plugin
			/*if ( $this->is_disabled( $extension_name ) ) {
				continue;
			}*/

			$this->register_extension( $extension_id, new $class_name() );
		}

		do_action( 'powerpack_elements/extensions/extensions_registered', $this );
	}

	/**
	 * Check if extension is disabled through admin settings
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 * @return bool
	 */
	public function is_disabled( $extension_name ) {
		if ( ! $extension_name )
			return false;

		$option_name 	= 'enable_' . $extension_name;
		$section 		= 'powerpack_elements_extensions';
		$option 		= \PowerpackElementsLite\Powerpackplugin::instance()->settings->get_option( $option_name, $section, false );

		return ( 'off' === $option ) || ( ! $option && $this->is_default_disabled( $extension_name ) );
	}

	/**
	 * Check if extension is disabled by default
	 *
	 * @since 2.0.0
	 *
	 * @access public
	 * @return bool
	 */
	public function is_default_disabled( $extension_name ) {
		if ( ! $extension_name )
			return false;

		$class_name = str_replace( '-', '_', $extension_name );
		$class_name = 'PowerpackElementsLite\Extensions\Extension_' . ucwords( $class_name );

		if ( $class_name::is_default_disabled() )
			return true;

		return false;
	}

	/**
	 * Check if extension is available at all
	 *
	 * @since 1.8.0
	 *
	 * @access public
	 * @return bool
	 */
	public function is_available( $extension_name ) {
		if ( ! $extension_name )
			return false;

		$class_name = str_replace( '-', '_', $extension_name );
		$class_name = 'PowerpackElementsLite\Extensions\Extension_' . ucwords( $class_name );

		if ( $class_name::requires_elementor_pro() && ! is_elementor_pro_active() )
			return false;

		return true;
	}

	/**
	 * @since 0.1.0
	 *
	 * @param $extension_id
	 * @param Extension_Base $extension_instance
	 */
	public function register_extension( $extension_id, Base\Extension_Base $extension_instance ) {
		$this->_extensions[ $extension_id ] = $extension_instance;
	}

	/**
	 * @since 0.1.0
	 *
	 * @param $extension_id
	 * @return bool
	 */
	public function unregister_extension( $extension_id ) {
		if ( ! isset( $this->_extensions[ $extension_id ] ) ) {
			return false;
		}

		unset( $this->_extensions[ $extension_id ] );

		return true;
	}

	/**
	 * @since 0.1.0
	 *
	 * @return Extension_Base[]
	 */
	public function get_extensions() {
		if ( null === $this->_extensions ) {
			$this->register_extensions();
		}

		return $this->_extensions;
	}

	/**
	 * @since 0.1.0
	 *
	 * @param $extension_id
	 * @return bool|\PowerpackElementsLite\Extension_Base
	 */
	public function get_extension( $extension_id ) {
		$extensions = $this->get_extensions();

		return isset( $extensions[ $extension_id ] ) ? $extensions[ $extension_id ] : false;
	}

	private function require_files() {
		require( POWERPACK_ELEMENTS_LITE_PATH . 'base/extension-base.php' );
	}

	public function __construct() {
		$this->require_files();
		$this->register_extensions();
	}
}
