<?php
namespace PowerpackElementsLite\Modules\GravityForms;

use PowerpackElementsLite\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
	}

	/**
	 * Module is active or not.
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 *
	 * @return bool true|false.
	 */
	public static function is_active() {
		if ( class_exists( 'GFCommon' ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get Module Name.
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'pp-gravity-forms';
	}

	/**
	 * Get Widgets.
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 *
	 * @return array Widgets.
	 */
	public function get_widgets() {
		return [
			'Gravity_Forms',
		];
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'widget-pp-gravity-forms',
			$this->get_css_assets_url( 'widget-gravity-forms', null, true, true ),
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
	}
}
