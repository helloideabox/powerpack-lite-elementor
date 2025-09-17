<?php
namespace PowerpackElementsLite\Modules\Table;

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
	 * @since x.x.x
	 *
	 * @access public
	 *
	 * @return bool true|false.
	 */
	public static function is_active() {
		return true;
	}

	public function get_name() {
		return 'pp-table';
	}

	public function get_widgets() {
		return [
			'Table',
		];
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'widget-pp-table',
			$this->get_css_assets_url( 'widget-table', null, true, true ),
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
	}
}
