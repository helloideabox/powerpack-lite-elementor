<?php
namespace PowerpackElements\Modules\ModalPopup;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function get_name() {
		return 'pp-modal-popup';
	}

	public function get_widgets() {
		return [
			'Modal_Popup',
		];
	}
}
