<?php
namespace PowerpackElementsLite\Extensions;

// Powerpack Elements classes
use PowerpackElementsLite\Base\Extension_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Conditions Extension
 *
 * Adds display conditions to elements
 *
 * @since 1.2.7
 */
class Extension_Display_Conditions extends Extension_Base {

	/**
	 * Name of the extension's controls section
	 *
	 * @since 3.0.0
	 */
	const SECTION_NAME = 'section_pp_display_conditions';

	/**
	 * Is Common Extension
	 *
	 * Defines if the current extension is common for all element types or not
	 *
	 * @since 1.2.7
	 * @access protected
	 *
	 * @var bool
	 */
	protected $is_common = true;

	/**
	 * A list of scripts that the widgets is depended in
	 *
	 * @since 1.2.7
	 **/
	public function get_script_depends() {
		return [];
	}

	/**
	 * A list of styles that the extension is depended in
	 *
	 * @since 2.11.0
	 **/
	public function get_style_depends() {
		return array(
			'pp-extensions',
		);
	}

	/**
	 * The description of the current extension
	 *
	 * @since 1.2.7
	 **/
	public static function get_description() {
		return esc_html__( 'Adds display conditions to widgets and sections allowing you to show them depending on authentication, roles, date and time of day.', 'powerpack-lite-for-elementor' );
	}

	/**
	 * Is disabled by default
	 *
	 * Return wether or not the extension should be disabled by default,
	 * prior to user actually saving a value in the admin page
	 *
	 * @access public
	 * @since 1.2.7
	 * @return bool
	 */
	public static function is_default_disabled() {
		return true;
	}

	/**
	 * Add common sections
	 *
	 * @since 1.2.7
	 *
	 * @access protected
	 */
	protected function add_common_sections_actions() {

		// Activate sections for widgets
		add_action( 'elementor/element/common/_section_style/after_section_end', function( $element, $args ) {

			$this->add_section( $element );

		}, 1, 2 );

		// Activate sections for columns
		add_action( 'elementor/element/column/section_advanced/after_section_end', function( $element, $args ) {

			$this->add_section( $element );

		}, 1, 2 );

		// Activate sections for sections
		add_action( 'elementor/element/section/section_advanced/after_section_end', function( $element, $args ) {

			$this->add_section( $element );

		}, 1, 2 );

		// Activate sections for containers
		add_action( 'elementor/element/container/section_layout/after_section_end', function( $element, $args ) {

			$this->add_section( $element );

		}, 1, 2 );

	}

	/**
	 * Add the extension's controls section
	 *
	 * @since 3.0.0
	 *
	 * @access private
	 *
	 * @param \Elementor\Controls_Stack $element The element the section is added to.
	 */
	private function add_section( $element ) {

		$this->add_extension_section( $element, self::SECTION_NAME, esc_html__( 'Display Conditions', 'powerpack-lite-for-elementor' ) );
	}

	/**
	 * Add Actions
	 *
	 * @since 1.2.7
	 *
	 * @access protected
	 */
	protected function add_actions() {
		$module = \PowerpackElementsLite\PowerpackLitePlugin::instance()->modules_manager->get_modules( 'display-conditions' );
		$module->add_actions();
	}

	protected function render_editor_notice( $settings ) {
		?><span><?php esc_html_e( 'This widget is displayed conditionally.', 'powerpack-lite-for-elementor' ); ?></span>
		<?php
	}
}
