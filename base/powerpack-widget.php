<?php

namespace PowerpackElementsLite\Base;

use Elementor\Widget_Base;
use PowerpackElementsLite\Classes\PP_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Common Widget
 *
 * @since 0.0.1
 */
abstract class Powerpack_Widget extends Widget_Base {

	/**
	 * Get categories
	 *
	 * @since 0.0.1
	 */
	public function get_categories() {
		return [ 'powerpack-elements' ];
	}

	/**
	 * Get widget name
	 *
	 * @param string $slug Module class.
	 * @since x.x.x
	 */
	public function get_widget_name( $slug = '' ) {
		return PP_Helper::get_widget_name( $slug );
	}

	/**
	 * Get widget title
	 *
	 * @param string $slug Module class.
	 * @since x.x.x
	 */
	public function get_widget_title( $slug = '' ) {
		return PP_Helper::get_widget_title( $slug );
	}

	/**
	 * Get widget title
	 *
	 * @param string $slug Module class.
	 * @since x.x.x
	 */
	public function get_widget_categories( $slug = '' ) {
		return PP_Helper::get_widget_categories( $slug );
	}

	/**
	 * Get widget title
	 *
	 * @param string $slug Module class.
	 * @since x.x.x
	 */
	public function get_widget_icon( $slug = '' ) {
		return PP_Helper::get_widget_icon( $slug );
	}

	/**
	 * Get widget title
	 *
	 * @param string $slug Module class.
	 * @since x.x.x
	 */
	public function get_widget_keywords( $slug = '' ) {
		return PP_Helper::get_widget_keywords( $slug );
	}

	/**
	 * Widget base constructor.
	 *
	 * Initializing the widget base class.
	 *
	 * @since 1.2.9.4
	 * @access public
	 *
	 * @param array       $data Widget data. Default is an empty array.
	 * @param array|null  $args Optional. Widget default arguments. Default is null.
	 */
	public function __construct( $data = [], $args = null ) {

		parent::__construct( $data, $args );

		add_filter( 'upgrade_powerpack_title', [$this, 'upgrade_powerpack_title'], 10, 3 );
		add_filter( 'upgrade_powerpack_message', [$this, 'upgrade_powerpack_message'], 10, 3 );
	}
	
	public function upgrade_powerpack_title() {
		$upgrade_title = __( 'Get PowerPack Pro', 'powerpack' );

		return $upgrade_title;
	}
	
	public function upgrade_powerpack_message() {
		$upgrade_message = sprintf( __( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="https://powerpackelements.com/upgrade/?utm_medium=pp-elements-lite&utm_source=pp-widget-upgrade-section&utm_campaign=pp-pro-upgrade" target="_blank" rel="noopener">', '</a>' );

		return $upgrade_message;
	}
}
