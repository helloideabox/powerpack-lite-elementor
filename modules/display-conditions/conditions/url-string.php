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
 * \Extensions\Conditions\Url_String
 *
 * @since 3.0.0
 */
class Url_String extends Condition {

	/**
	 * Get Group
	 *
	 * Get the group of the condition
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public function get_group() {
		return 'misc';
	}

	/**
	 * Get Name
	 *
	 * Get the name of the condition
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public function get_name() {
		return 'url_string';
	}

	/**
	 * Get Title
	 *
	 * Get the title of the condition
	 *
	 * @since 3.0.0
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'URL String', 'powerpack-lite-for-elementor' );
	}

	/**
	 * Get Value Control
	 *
	 * Get the settings for the value control
	 *
	 * @since 3.0.0
	 * @return array
	 */
	public function get_value_control() {
		return [
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'placeholder' => esc_html__( 'e.g. sale', 'powerpack-lite-for-elementor' ),
			'description' => esc_html__( 'Enter a string to search for in the current page URL.', 'powerpack-lite-for-elementor' ),
			'label_block' => true,
			'ai'          => [
				'active' => false,
			],
		];
	}

	/**
	 * Check condition
	 *
	 * @since 3.0.0
	 *
	 * @access public
	 *
	 * @param string $name      The control name to check
	 * @param string $operator  Comparison operator
	 * @param mixed  $value     The control value to check
	 */
	public function check( $name, $operator, $value ) {
		$show = false;

		if ( empty( $value ) || empty( $_SERVER['REQUEST_URI'] ) ) {
			return $this->compare( $show, true, $operator );
		}

		$request_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
		$full_url    = home_url( $request_uri );
		$search      = sanitize_text_field( $value );

		$show = ( false !== stripos( $full_url, $search ) );

		return $this->compare( $show, true, $operator );
	}
}
