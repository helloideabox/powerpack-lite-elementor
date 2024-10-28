<?php
namespace PowerpackElementsLite\Modules\Hotspots;

use PowerpackElementsLite\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
	}

	public function get_name() {
		return 'pp-image-hotspots';
	}

	public function get_widgets() {
		return [
			'Hotspots',
		];
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'widget-pp-hotspots',
			$this->get_css_assets_url( 'widget-hotspots', null, true, true ),
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
	}
}
