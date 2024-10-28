<?php
namespace PowerpackElementsLite\Modules\Pricing;

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
		return 'pp-pricing';
	}

	public function get_widgets() {
		return [
			'Price_Menu',
			'Pricing_Table',
		];
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'widget-pp-price-menu',
			$this->get_css_assets_url( 'widget-price-menu', null, true, true ),
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);

		wp_register_style(
			'widget-pp-pricing-table',
			$this->get_css_assets_url( 'widget-pricing-table', null, true, true ),
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
	}
}
