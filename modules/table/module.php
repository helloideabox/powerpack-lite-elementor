<?php
namespace PowerpackElements\Modules\Table;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'pp-table';
	}

	public function get_widgets() {
		return [
			'Table',
		];
	}
}
