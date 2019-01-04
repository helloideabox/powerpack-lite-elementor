<?php

namespace PowerpackElementsLite\Base;

use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Common Widget
 *
 * @since 0.0.1
 */
abstract class Powerpack_Widget extends Widget_Base {

	/**
	 * Get categories
	 *
	 * @since 0.0.1
	 */
	public function get_categories() {
		return [ 'powerpack-elements' ];
	}
}
