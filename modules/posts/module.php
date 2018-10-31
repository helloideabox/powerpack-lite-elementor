<?php
namespace PowerpackElements\Modules\Posts;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'pp-posts';
	}

	public function get_widgets() {
		return [
			'Card_Slider',
			'Tiled_Posts',
			'Timeline',
		];
	}
}
