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
 * \Extensions\Conditions\Date
 *
 * @since  1.2.7
 */
class Date extends Condition {

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
		return 'date';
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
		return __( 'Current Date', 'powerpack-lite-for-elementor' );
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
		$current_timestamp  = current_time( 'timestamp' );

		$default_date_start = wp_date(
			'Y-m-d',
			$current_timestamp - ( 3 * DAY_IN_SECONDS )
		);

		$default_date_end = wp_date(
			'Y-m-d',
			$current_timestamp + ( 3 * DAY_IN_SECONDS )
		);

		$default_interval = $default_date_start . ' to ' . $default_date_end;

		return [
			'label'             => __( 'In interval', 'powerpack-lite-for-elementor' ),
			'type'              => \Elementor\Controls_Manager::DATE_TIME,
			'picker_options'    => [
				'enableTime'    => false,
				'mode'          => 'range',
			],
			'label_block'       => true,
			'default'           => $default_interval,
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

		// Normalize and split interval
		$intervals = explode( 'to', preg_replace( '/\s+/', '', $value ) );

		if ( 2 !== count( $intervals ) ) {
			return false;
		}

		list( $start, $end ) = $intervals;

		// Validate dates
		if (
			! \DateTime::createFromFormat( 'Y-m-d', $start ) ||
			! \DateTime::createFromFormat( 'Y-m-d', $end )
		) {
			return false;
		}

		// Get today's date in site timezone
		$today_date = wp_date( 'Y-m-d', current_time( 'timestamp' ) );

		$show = (
			$today_date >= $start &&
			$today_date <= $end
		);

		return $this->compare( $show, true, $operator );
	}
}
