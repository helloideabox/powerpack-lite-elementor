<?php
namespace PowerpackElementsLite\Modules\Marquee;

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
		return 'pp-marquee';
	}

	public function get_widgets() {
		return [
			'Marquee',
		];
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'widget-pp-marquee',
			$this->get_css_assets_url( 'widget-marquee', null, true, true ),
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
	}
}
