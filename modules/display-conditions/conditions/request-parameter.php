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
		return esc_html__( ' Request Parameter', 'powerpack' );
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
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => '',
			'placeholder' => '',
			'description' => esc_html__( 'Enter each request parameter on a new line as pairs of param=value or param1=value1&amp;param2=value2.', 'powerpack' ),
			'ai'          => [
				'active' => false,
			],
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

		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return $this->compare( $show, true, $operator );
		}

		$request_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		$url = wp_parse_url( $request_uri );

		if ( isset( $url['query'] ) && ! empty( $url['query'] ) ) {
			$query_params = explode( '&', $url['query'] );

			$value = str_replace( '&', "\n", sanitize_textarea_field( $value ) );
			$value = explode( "\n", $value );

			if ( ! empty( $value ) ) {
				foreach ( $value as $index => $param ) {
					if ( ! empty( $param ) ) {
						$is_strict = strpos( $param, '=' );
						if ( ! $is_strict ) {
							$value[ $index ] = $value[ $index ] . '=' . rawurlencode( $_GET[ $param ] );
						}
					}
				}

				$show = ! empty( array_intersect( $value, $query_params ) );
			}
		}

		return $this->compare( $show, true, $operator );
	}
}
