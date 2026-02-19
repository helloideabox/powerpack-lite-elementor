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
		return esc_html__( ' Request Parameter', 'powerpack-lite-for-elementor' );
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
			'description' => esc_html__( 'Enter each request parameter on a new line as pairs of param=value or param1=value1&amp;param2=value2.', 'powerpack-lite-for-elementor' ),
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

		$request_uri = esc_url_raw(
			wp_unslash( $_SERVER['REQUEST_URI'] )
		);

		$url = wp_parse_url( $request_uri );

		if ( empty( $url['query'] ) ) {
			return $this->compare( $show, true, $operator );
		}

		$current_query = [];
		wp_parse_str( $url['query'], $current_query );

		$value = sanitize_textarea_field( $value );
		$value = str_replace( '&', "\n", $value );
		$value = explode( "\n", $value );

		$matched = false;

		foreach ( $value as $param ) {

			$param = trim( $param );

			if ( empty( $param ) ) {
				continue;
			}

			if ( false !== strpos( $param, '=' ) ) {

				list( $key, $expected ) = array_map( 'trim', explode( '=', $param, 2 ) );

				$key      = sanitize_key( $key );
				$expected = sanitize_text_field( $expected );

				if ( isset( $current_query[ $key ] ) ) {

					$current_value = sanitize_text_field( $current_query[ $key ] );

					if ( (string) $current_value === (string) $expected ) {
						$matched = true;
						break;
					}
				}

			} else {

				$key = sanitize_key( $param );

				if ( isset( $current_query[ $key ] ) ) {
					$matched = true;
					break;
				}
			}
		}

		$show = $matched;

		return $this->compare( $show, true, $operator );
	}
}
