<?php
namespace PowerpackElementsLite\Modules\Posts\Skins;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * News Skin for Posts widget
 */
class Skin_News extends Skin_Base {

	/**
	 * Retrieve Skin ID.
	 *
	 * @access public
	 *
	 * @return string Skin ID.
	 */
	public function get_id() {
		return 'news';
	}

	/**
	 * Retrieve Skin title.
	 *
	 * @access public
	 *
	 * @return string Skin title.
	 */
	public function get_title() {
		return esc_html__( 'News', 'powerpack' );
	}
}
