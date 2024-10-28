<?php
namespace PowerpackElementsLite\Modules\Posts\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Classic Skin for Posts widget
 */
class Skin_Classic extends Skin_Base {

	/**
	 * Retrieve Skin ID.
	 *
	 * @access public
	 *
	 * @return string Skin ID.
	 */
	public function get_id() {
		return 'classic';
	}

	/**
	 * Retrieve Skin title.
	 *
	 * @access public
	 *
	 * @return string Skin title.
	 */
	public function get_title() {
		return esc_html__( 'Classic', 'powerpack' );
	}
}
