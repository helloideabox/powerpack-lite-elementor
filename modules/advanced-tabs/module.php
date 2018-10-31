<?php
namespace PowerpackElements\Modules\AdvancedTabs;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'pp-advanced-tabs';
	}

	public function get_widgets() {
		return [
			'Advanced_Tabs',
		];
	}
}
