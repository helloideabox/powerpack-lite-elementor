<?php
namespace PowerpackElementsLite\Modules\Charts;

use PowerpackElementsLite\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function get_name() {
		return 'pp-charts';
	}

	public function get_widgets() {
		return [
			'Charts',
		];
	}
}
