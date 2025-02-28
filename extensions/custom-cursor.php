<?php
namespace PowerpackElementsLite\Extensions;

// Powerpack Elements classes
use PowerpackElementsLite\Base\Extension_Base;

// Elementor classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Custom Cursor Extension
 *
 * Adds link around sections, columns and widgets
 *
 * @since 2.7.0
 */
class Extension_Custom_Cursor extends Extension_Base {

	/**
	 * Is Common Extension
	 *
	 * Defines if the current extension is common for all element types or not
	 *
	 * @since 2.7.0
	 * @access protected
	 *
	 * @var bool
	 */
	protected $is_common = true;

	/**
	 * A list of scripts that the widgets is depended in
	 *
	 * @since 2.7.0
	 **/
	public function get_script_depends() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return array(
				'pp-custom-cursor',
			);
		}

		return [];
	}

	/**
	 * A list of styles that the extension is depended in
	 *
	 * @since 2.8.0
	 **/
	public function get_style_depends() {
		return array(
			'pp-extensions',
		);
	}

	/**
	 * The description of the current extension
	 *
	 * @since 2.7.0
	 **/
	public static function get_description() {
		return esc_html__( 'Adds custom mouse cursors on columns.', 'powerpack' );
	}

	/**
	 * Is disabled by default
	 *
	 * Return wether or not the extension should be disabled by default,
	 * prior to user actually saving a value in the admin page
	 *
	 * @access public
	 * @since 2.7.0
	 * @return bool
	 */
	public static function is_default_disabled() {
		return true;
	}

	/**
	 * Add common sections
	 *
	 * @since 2.7.0
	 *
	 * @access protected
	 */
	protected function add_common_sections_actions() {

		// Activate sections for sections
		add_action( 'elementor/element/section/section_advanced/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );

		// Activate sections for columns
		add_action( 'elementor/element/column/section_advanced/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );

		// Activate sections for widgets
		add_action( 'elementor/element/common/_section_style/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );

		// Activate sections for containers
		add_action( 'elementor/element/container/section_layout/after_section_end', function( $element, $args ) {

			$this->add_common_sections( $element, $args );

		}, 10, 2 );
	}

	/**
	 * Add Controls
	 *
	 * @since 2.7.0
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {

		$element_type = $element->get_type();

		$element->add_control(
			'pp_custom_cursor_enable',
			array(
				'label'              => esc_html__( 'Custom Cursor', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'label_on'           => esc_html__( 'Yes', 'powerpack' ),
				'label_off'          => esc_html__( 'No', 'powerpack' ),
				'return_value'       => 'yes',
				'separator'          => 'before',
				'frontend_available' => true,
			)
		);

		$element->add_control(
			'pp_custom_cursor_target',
			array(
				'label'              => esc_html__( 'Apply On', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'container',
				'options'            => array(
					'container'    => ucfirst( $element_type ),
					'css-selector' => esc_html__( 'Element Class/ID', 'powerpack' ),
				),
				'frontend_available' => true,
				'condition'          => array(
					'pp_custom_cursor_enable' => 'yes',
				),
			)
		);

		$element->add_control(
			'pp_custom_cursor_css_selector',
			array(
				'label'              => esc_html__( 'CSS Selector', 'powerpack' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'ai'                 => [
					'active' => false,
				],
				'condition'          => array(
					'pp_custom_cursor_enable' => 'yes',
					'pp_custom_cursor_target' => 'css-selector',
				),
			)
		);

		$element->add_control(
			'pp_custom_cursor_type',
			array(
				'label'              => esc_html__( 'Cursor Type', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'image',
				'options'            => array(
					'image'        => esc_html__( 'Image', 'powerpack' ),
					'follow-image' => esc_html__( 'Follow Image', 'powerpack' ),
					'follow-text'  => esc_html__( 'Follow Text', 'powerpack' ),
				),
				'frontend_available' => true,
				'condition'          => array(
					'pp_custom_cursor_enable' => 'yes',
				),
			)
		);

		$element->add_control(
			'pp_custom_cursor_icon',
			array(
				'label'              => esc_html__( 'Choose Cursor Icon', 'powerpack' ),
				'type'               => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'condition'          => array(
					'pp_custom_cursor_enable' => 'yes',
					'pp_custom_cursor_type'   => [ 'image', 'follow-image' ],
				),
			)
		);

		$element->add_control(
			'pp_custom_cursor_text',
			array(
				'label'              => esc_html__( 'Cursor Text', 'powerpack' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'condition'          => array(
					'pp_custom_cursor_enable' => 'yes',
					'pp_custom_cursor_type'   => 'follow-text',
				),
			)
		);

		$element->add_control(
			'pp_custom_cursor_left_offset',
			[
				'label'              => esc_html__( 'Left Offset', 'powerpack' ),
				'type'               => Controls_Manager::SLIDER,
				'frontend_available' => true,
				'range'      => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'default'            => [
					'size'  => 0,
					'unit'  => 'px',
				],
				'condition'          => [
					'pp_custom_cursor_enable' => 'yes',
				],
			]
		);

		$element->add_control(
			'pp_custom_cursor_top_offset',
			[
				'label'              => esc_html__( 'Top Offset', 'powerpack' ),
				'type'               => Controls_Manager::SLIDER,
				'frontend_available' => true,
				'range'              => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'default'            => [
					'size'  => 0,
					'unit'  => 'px',
				],
				'condition'          => [
					'pp_custom_cursor_enable' => 'yes',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'pp_custom_cursor_text_typography',
				'label'     => esc_html__( 'Typography', 'powerpack' ),
				'selector'  => '{{WRAPPER}} .pp-cursor-pointer-text',
				'condition' => array(
					'pp_custom_cursor_enable' => 'yes',
					'pp_custom_cursor_type'   => 'follow-text',
				),
			)
		);

		$element->add_control(
			'pp_custom_cursor_text_color',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-cursor-pointer-text' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'pp_custom_cursor_enable' => 'yes',
					'pp_custom_cursor_type'   => 'follow-text',
				),
			)
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'pp_custom_cursor_text_bg',
				'label'     => esc_html__( 'Background', 'powerpack' ),
				'types'     => [ 'classic', 'gradient' ],
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .pp-cursor-pointer-text',
				'condition' => array(
					'pp_custom_cursor_enable' => 'yes',
					'pp_custom_cursor_type'   => 'follow-text',
				),
			]
		);

		$element->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'pp_custom_cursor_text_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-cursor-pointer-text',
				'condition'   => array(
					'pp_custom_cursor_enable' => 'yes',
					'pp_custom_cursor_type'   => 'follow-text',
				),
			)
		);

		$element->add_control(
			'pp_custom_cursor_text_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-cursor-pointer-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'pp_custom_cursor_enable' => 'yes',
					'pp_custom_cursor_type'   => 'follow-text',
				),
			)
		);

		$element->add_responsive_control(
			'pp_custom_cursor_text_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-cursor-pointer-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'pp_custom_cursor_enable' => 'yes',
					'pp_custom_cursor_type'   => 'follow-text',
				),
			)
		);
	}

	protected function render() {
		$settings = $element->get_settings();
	}

	/**
	 * Add Actions
	 *
	 * @since 2.7.0
	 *
	 * @access protected
	 */
	protected function add_actions() {

		// Activate controls for section
		add_action( 'elementor/element/section/section_powerpack_elements_advanced/before_section_end', function( $element, $args ) {
			$this->add_controls( $element, $args );
		}, 10, 2 );

		// Activate controls for columns
		add_action( 'elementor/element/column/section_powerpack_elements_advanced/before_section_end', function( $element, $args ) {
			$this->add_controls( $element, $args );
		}, 10, 2 );

		// Activate controls for widgets
		add_action( 'elementor/element/common/section_powerpack_elements_advanced/before_section_end', function( $element, $args ) {
			$this->add_controls( $element, $args );
		}, 10, 2 );

		// Activate controls for containers
		add_action( 'elementor/element/container/section_powerpack_elements_advanced/before_section_end', function( $element, $args ) {
			$this->add_controls( $element, $args );
		}, 10, 2 );

		// Conditions for sections
		add_action( 'elementor/frontend/before_render', function( $element ) {
			$settings      = $element->get_settings_for_display();
			$cursor_enable = isset( $settings['pp_custom_cursor_enable'] ) ? $settings['pp_custom_cursor_enable'] : '';
			$cursor_url    = isset( $settings['pp_custom_cursor_icon'] ) ? $settings['pp_custom_cursor_icon'] : [];
			$cursor_text   = isset( $settings['pp_custom_cursor_text'] ) ? $settings['pp_custom_cursor_text'] : '';
			$cursor_target = isset( $settings['pp_custom_cursor_target'] ) ? $settings['pp_custom_cursor_target'] : '';
			$css_selector  = isset( $settings['pp_custom_cursor_css_selector'] ) ? $settings['pp_custom_cursor_css_selector'] : '';

			if ( 'yes' === $cursor_enable ) {
				if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() || ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
					wp_enqueue_script( 'pp-custom-cursor' );
				}

				$custom_cursor_options = [
					'type' => esc_attr( $settings['pp_custom_cursor_type'] ),
				];

				if ( ! empty( $cursor_url ) ) {
					$custom_cursor_options['url'] = esc_url( $cursor_url['url'] );
				}

				if ( $cursor_text ) {
					$custom_cursor_options['text'] = esc_html( $cursor_text );
				}

				if ( 'css-selector' === $cursor_target && $css_selector ) {
					$custom_cursor_options['target'] = 'selector';
					$custom_cursor_options['css_selector'] = esc_attr( $css_selector );
				}

				$element->add_render_attribute(
					'_wrapper', [
						'class'               => [ 'pp-custom-cursor', 'pp-custom-cursor-' . $element->get_id() ],
						'data-cursor-options' => wp_json_encode( $custom_cursor_options ),
					]
				);
			}
		}, 10, 1 );

		/* add_action( 'elementor/widget/print_template', function( $template, $widget ) {

			if ( ! $template ) {
				return;
			}

			ob_start();

			?><#

			if ( 'yes' === settings.pp_custom_cursor_enable ) {

				view.addRenderAttribute( '_wrapper', 'class', 'pp-custom-cursor' );
				view.addRenderAttribute( '_wrapper', 'id', 'hotip-content-' + view.$el.data('id') );

				#>

				<span {{{ view.getRenderAttributeString( 'tooltip' ) }}}>
					{{{ settings.tooltip_content }}}
				</span>

			<# } #><?php

			$template .= ob_get_clean();

			return $template;

		}, 10, 2 ); */
	}
}
