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
 * \Extensions\Conditions\Request_Parameter
 *
 * @since  2.6.7
 */
class Request_Parameter extends Condition {

	/**
	 * Get Group
	 *
	 * Get the group of the condition
	 *
	 * @since  2.6.7
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
	 * @since  2.6.7
	 * @return string
	 */
	public function get_name() {
		return 'request_parameter';
	}

	/**
	 * Get Title
	 *
	 * Get the title of the module
	 *
	 * @since  2.6.7
	 * @return string
	 */
	public function get_title() {
		return __( ' Request Parameter', 'powerpack' );
	}

	/**
	 * Get Value Control
	 *
	 * Get the settings for the value control
	 *
	 * @since  2.6.7
	 * @return string
	 */
	public function get_value_control() {
		return [
			'type'          => Controls_Manager::TEXTAREA,
			'default'       => '',
			'placeholder'   => __( 'utm_source=facebook&utm_medium=paid_social&utm_campaign=christmas_sale&utm_term=social_media&utm_content=video_ad', 'powerpack' ),
			'description'   => __( 'Enter the query string that needs to the condition to apply.', 'powerpack' ),
		];
	}

	/**
	 * Check condition
	 *
	 * @since 2.6.7
	 *
	 * @access public
	 *
	 * @param string    $name       The control name to check
	 * @param string    $operator   Comparison operator
	 * @param mixed     $value      The control value to check
	 */
	public function check( $name, $operator, $value ) {
		$show = false;

		if ( ! empty( $_SERVER['QUERY_STRING'] ) && ! empty( $value ) ) {
			parse_str( $_SERVER['QUERY_STRING'], $server_qs_array );
			parse_str( $value, $value_qs_array );

			$result = array_diff_assoc( $server_qs_array, $value_qs_array );
			if ( empty( $result ) ) {
				$show = true;
			}
		}

		return $this->compare( $show, true, $operator );
	}
}
