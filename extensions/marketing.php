<?php
namespace PowerpackElementsLite\Extensions;

// Powerpack Elements classes
use PowerpackElementsLite\Base\Extension_Base;
use PowerpackElementsLite\Classes\Hints;

// Elementor classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Marketing Notice Extension
 *
 * Adds install TableMaster notice to PowerPack Pricing Table widget
 *
 * @since x.x.x
 */
class Extension_Marketing extends Extension_Base {

	/**
	 * Is Common Extension
	 *
	 * Defines if the current extension is common for all element types or not
	 *
	 * @since x.x.x
	 * @access protected
	 *
	 * @var bool
	 */
	protected $is_common = true;

	/**
	 * A list of scripts that the widgets is depended in
	 *
	 * @since x.x.x
	 **/
	public function get_script_depends() {
		return [];
	}

	/**
	 * The description of the current extension
	 *
	 * @since x.x.x
	 **/
	public static function get_description() {
		return esc_html__( 'Adds upgrade PowerPack notice to all widgets of PowerPack.', 'powerpack' );
	}

	/**
	 * Is disabled by default
	 *
	 * Return wether or not the extension should be disabled by default,
	 * prior to user actually saving a value in the admin page
	 *
	 * @access public
	 * @since x.x.x
	 * @return bool
	 */
	public static function is_default_disabled() {
		return false;
	}

	/**
	 * Add Controls
	 *
	 * @since x.x.x
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {
		// error_log( print_r( 'hi', true ) );
		$notice_id = 'tablemaster_pricing_table_notice';
		if ( ! Hints::should_show_hint( $notice_id ) ) {
			return;
		}
		$notice_content = esc_html__( 'Create advanced, responsive tables with TableMaster for Elementor.', 'elementor-pro' );

		$element->add_control(
			'upgrade_powerpack_lite_notices',
			array(
				'label'           => '',
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => Hints::get_notice_template( [
					'display' => ! Hints::is_dismissed( $notice_id ),
					'type' => 'info',
					'content' => $notice_content,
					'icon' => true,
					'dismissible' => $notice_id,
					'button_text' => Hints::is_plugin_installed( 'tablemaster-for-elementor' ) ? __( 'Activate Plugin', 'elementor-pro' ) : __( 'Install Plugin', 'elementor-pro' ),
					'button_event' => $notice_id,
					'button_data' => [
						'action_url' => Hints::get_plugin_action_url( 'tablemaster-for-elementor' ),
					],
				], true ),
			)
		);

	}

	/**
	 * Add Actions
	 *
	 * @since x.x.x
	 *
	 * @access protected
	 */
	protected function add_actions() {

		add_action( 'elementor/element/pp-pricing-table/section_header/before_section_end', function( $element, $args ) {
				$this->add_controls( $element, $args );
		}, 10, 2 );
	}
}
