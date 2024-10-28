<?php
namespace PowerpackElementsLite\Modules\Posts\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Event Skin for Posts widget
 */
class Skin_Event extends Skin_Base {

	/**
	 * Retrieve Skin ID.
	 *
	 * @access public
	 *
	 * @return string Skin ID.
	 */
	public function get_id() {
		return 'event';
	}

	/**
	 * Retrieve Skin title.
	 *
	 * @access public
	 *
	 * @return string Skin title.
	 */
	public function get_title() {
		return esc_html__( 'Event', 'powerpack' );
	}
}
