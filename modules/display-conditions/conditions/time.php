<?php
namespace PowerpackElementsLite\Modules\DisplayConditions\Conditions;

// Powerpack Elements Classes
use PowerpackElementsLite\Base\Condition;

// Elementor Classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * \Modules\DisplayConditions\Conditions\Time
 *
 * @since  1.2.7
 */
class Time extends Condition {

	/**
	 * Get Group
	 *
	 * Get the group of the condition
	 *
	 * @since  1.2.7
	 * @return string
	 */
	public function get_group() {
		return 'date_time';
	}

	/**
	 * Get Name
	 *
	 * Get the name of the module
	 *
	 * @since  1.2.7
	 * @return string
	 */
	public function get_name() {
		return 'time';
	}

	/**
	 * Get Title
	 *
	 * Get the title of the module
	 *
	 * @since  1.2.7
	 * @return string
	 */
	public function get_title() {
		return __( 'Time of Day', 'powerpack-lite-for-elementor' );
	}

	/**
	 * Get Value Control
	 *
	 * Get the settings for the value control
	 *
	 * @since  1.2.7
	 * @return string
	 */
	public function get_value_control() {
		return [
			'label'     => __( 'Before', 'powerpack-lite-for-elementor' ),
			'type'      => \Elementor\Controls_Manager::DATE_TIME,
			'picker_options' => [
				'dateFormat'    => 'H:i',
				'enableTime'    => true,
				'noCalendar'    => true,
			],
			'label_block'   => true,
			'default'       => '',
		];
	}

	/**
	 * Check condition
	 *
	 * @since 1.2.7
	 *
	 * @access public
	 *
	 * @param string    $name       The control name to check
	 * @param string    $operator   Comparison operator
	 * @param mixed     $value      The control value to check
	 */
	public function check( $name, $operator, $value ) {

		if ( empty( $value ) ) {
			return false;
		}

		// Clean value
		$time = preg_replace( '/\s+/', '', $value );

		// Validate format
		if ( ! \DateTime::createFromFormat( 'H:i', $time ) ) {
			return false;
		}

		// Current site timestamp
		$current_timestamp = current_time( 'timestamp' );

		// Get today's date in site timezone
		$today_date = wp_date( 'Y-m-d', $current_timestamp );

		// Build full datetime string using site timezone
		$selected_datetime = $today_date . ' ' . $time;

		// Convert to timestamp
		$selected_timestamp = strtotime( $selected_datetime );

		if ( ! $selected_timestamp ) {
			return false;
		}

		$show = $current_timestamp < $selected_timestamp;

		return $this->compare( $show, true, $operator );
	}
}
