<?php
namespace PowerpackElements\Modules\Recipe;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'pp-recipe';
	}

	public function get_widgets() {
		return [
			'Recipe',
		];
	}
}
