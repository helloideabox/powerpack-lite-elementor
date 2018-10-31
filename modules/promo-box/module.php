<?php
namespace PowerpackElements\Modules\PromoBox;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'pp-promo-box';
	}

	public function get_widgets() {
		return [
			'Promo_Box',
		];
	}
}
