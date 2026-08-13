<?php
namespace PowerpackElementsLite\Modules\EventCalendar;

use PowerpackElementsLite\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Module extends Module_Base {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
	}

	public function get_name() {
		return 'pp-event-calendar';
	}

	public function get_widgets() {
		return [
			'Event_Calendar',
		];
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'widget-pp-event-calendar',
			$this->get_css_assets_url( 'widget-event-calendar', null, true, true ),
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
	}
}
