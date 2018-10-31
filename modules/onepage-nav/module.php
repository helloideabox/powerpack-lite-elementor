<?php
namespace PowerpackElements\Modules\OnepageNav;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'pp-one-page-nav';
	}

	public function get_widgets() {
		return [
			'Onepage_Nav',
		];
	}
}
