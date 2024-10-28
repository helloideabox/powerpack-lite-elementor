<?php
namespace PowerpackElementsLite\Modules\Logos;

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
		return 'pp-logo-carousel';
	}

	public function get_widgets() {
		return [
			'Logo_Carousel',
			'Logo_Grid',
		];
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'widget-pp-logo-grid',
			$this->get_css_assets_url( 'widget-logo-grid', null, true, true ),
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);

		wp_register_style(
			'widget-pp-logo-carousel',
			$this->get_css_assets_url( 'widget-logo-carousel', null, true, true ),
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
	}
}
