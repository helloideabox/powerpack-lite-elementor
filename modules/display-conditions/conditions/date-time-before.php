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
 * \Modules\DisplayConditions\Conditions\Date_Time_Before
 *
 * @since  1.2.7
 */
class Date_Time_Before extends Condition {

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
		return 'date_time_before';
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
		return __( 'Current Date & Time', 'powerpack-lite-for-elementor' );
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
		// $default_date = date( 'Y-m-d H:i', strtotime( '+3 day' ) + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS ) );
		$default_date = wp_date(
			'Y-m-d H:i',
			current_time( 'timestamp' ) + ( 3 * DAY_IN_SECONDS )
		);

		return [
			'label'     => __( 'Before', 'powerpack-lite-for-elementor' ),
			'type'      => \Elementor\Controls_Manager::DATE_TIME,
			'picker_options' => [
				'enableTime'    => true,
			],
			'label_block'   => true,
			'default'       => $default_date,
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

		// Convert selected date to timestamp
		$selected_timestamp = strtotime( $value, current_time( 'timestamp' ) );

		if ( ! $selected_timestamp ) {
			return false;
		}

		// Get current site timestamp
		$current_timestamp = current_time( 'timestamp' );

		// Check if current time is before selected date
		$show = $current_timestamp < $selected_timestamp;

		return $this->compare( $show, true, $operator );
	}
}
