<?php
namespace PowerpackElementsLite\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class PP_Event_Calendar_Helper.
 */
class PP_Event_Calendar_Helper {

	/**
	 * Get Calender Timezones.
	 *
	 * @since 3.0.0
	 * @access public
	 */
	public static function get_timezones() {
		$timezone_list = [];
		foreach ( timezone_identifiers_list() as $timezone ) {
			$timezone_list[ $timezone ] = $timezone;
		}
		return $timezone_list;
	}

	/**
	 * Convert a date string from the site timezone to another timezone.
	 *
	 * @since 3.0.0
	 * @access public
	 * @param string $date         The source date string.
	 * @param string $formate      The output date format.
	 * @param string $new_timezone Target timezone identifier.
	 * @return string The formatted date, or the original value on failure.
	 */
	public static function get_timezones_converted_date( $date, $formate, $new_timezone ) {
		if ( empty( $date ) ) {
			return '';
		}

		$timezone_string = ( '' !== get_option( 'timezone_string' ) ) ? get_option( 'timezone_string' ) : 'UTC';

		try {
			$datetime = new \DateTime( $date, new \DateTimeZone( $timezone_string ) );
			$datetime->setTimezone( new \DateTimeZone( $new_timezone ) );
			return $datetime->format( $formate );
		} catch ( \Exception $e ) {
			return $date;
		}
	}

}
