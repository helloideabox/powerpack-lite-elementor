<?php
namespace PowerpackElements\Modules\OffcanvasContent;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'pp-offcanvas-content';
	}

	public function get_widgets() {
		return [
			'Offcanvas_Content',
		];
	}
}
