<?php
namespace PowerpackElementsLite\Modules\DisplayConditions\Conditions;

// Powerpack Elements Classes
use PowerpackElementsLite\Base\Condition;

// Elementor Classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Device_Type extends Condition {

	/**
	 * Get Group
	 *
	 * Get the group of the condition
	 *
	 * @since  2.8.0
	 * @return string
	 */
	public function get_group() {
		return 'misc';
	}

	/**
	 * Get Name
	 *
	 * Get the name of the module
	 *
	 * @since  2.8.0
	 * @return string
	 */
	public function get_name() {
		return 'device_type';
	}

	/**
	 * Get Title
	 *
	 * Get the title of the module
	 *
	 * @since  2.8.0
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Device Type', 'powerpack' );
	}

	/**
	 * Get Value Control
	 *
	 * Get the settings for the value control
	 *
	 * @since  2.8.0
	 * @return string
	 */
	public function get_value_control() {
		return [
			'type'          => Controls_Manager::SELECT,
			'default'       => 'mobile',
			'label_block'   => true,
			'options'       => array(
				'mobile'  => esc_html__( 'Mobile', 'powerpack' ),
				'desktop' => esc_html__( 'Desktop', 'powerpack' ),
			),
		];
	}

	/**
	 * Check condition
	 *
	 * @since 2.8.0
	 *
	 * @access public
	 *
	 * @param string    $name       The control name to check
	 * @param string    $operator   Comparison operator
	 * @param mixed     $value      The control value to check
	 */
	public function check( $name, $operator, $value ) {
		$show = 'desktop';

		if ( wp_is_mobile() ) {
			$show = 'mobile';
		}

		return $this->compare( $show, $value, $operator );
	}
}
