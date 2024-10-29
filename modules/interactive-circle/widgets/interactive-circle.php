<?php
namespace PowerpackElementsLite\Modules\InteractiveCircle\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Posts_Helper;
use PowerpackElementsLite\Classes\PP_Config;
use PowerpackElementsLite\Classes\PP_Helper;

// Elementor Classes
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Typography;
use \Elementor\Icons_Manager;
use \Elementor\Repeater;
use \Elementor\Widget_Base;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Interactive Circle Widget
 */
class Interactive_Circle extends Powerpack_Widget {

	/**
	 * Retrieve Interactive Circle widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Interactive_Circle' );
	}

	/**
	 * Retrieve Interactive Circle widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Interactive_Circle' );
	}

	/**
	 * Retrieve Interactive Circle widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Interactive_Circle' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Interactive_Circle' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the Interactive Circle widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [
			'pp-interactive-circle',
		];
	}

	/**
	 * Retrieve the list of styles the offcanvas content widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return [ 'widget-pp-interactive-circle', 'pp-interactive-circle-animations' ];
		}

		$settings = $this->get_settings_for_display();
		$styles = [ 'widget-pp-interactive-circle' ];

		if ( 'none' !== $settings['circle_animation'] ) {
			array_push( $styles, 'pp-interactive-circle-animations' );
		}

		return $styles;
	}

	/**
	 * Register Interactive Circle widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.8.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_content_general_controls();
		$this->register_content_items_controls();
		$this->register_content_additional_options_controls();

		$this->register_style_circle_controls();
		$this->register_style_tabs_controls();
		$this->register_style_content_controls();
	}

	protected function register_content_general_controls() {
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'General', 'powerpack' ),
			]
		);

		$this->add_control(
			'source',
			[
				'label'       => esc_html__( 'Source', 'powerpack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'custom',
				'label_block' => false,
				'options'     => [
					'custom' => esc_html__( 'Custom', 'powerpack' ),
					'posts'  => esc_html__( 'Posts', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'show_source_notice',
			array(
				'label'           => '',
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This feature is available in PowerPack Pro.', 'powerpack' ) . ' ' . apply_filters( 'upgrade_powerpack_message', sprintf( esc_html__( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
				'content_classes' => 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					'source' => 'posts',
				),
			)
		);

		$this->add_control(
			'skin',
			[
				'label'       => esc_html__( 'Skin', 'powerpack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'skin-1',
				'label_block' => false,
				'options'     => [
					'skin-1' => esc_html__( 'Skin 1', 'powerpack' ),
					'skin-2' => esc_html__( 'Skin 2', 'powerpack' ),
					'skin-3' => esc_html__( 'Skin 3', 'powerpack' ),
					'skin-4' => esc_html__( 'Skin 4', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'tabs_heading',
			[
				'label'     => esc_html__( 'Tabs', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tabs_icon',
			[
				'label'        => esc_html__( 'Show Icon', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'tabs_title',
			[
				'label'        => esc_html__( 'Show Title', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'content_heading',
			[
				'label'     => esc_html__( 'Content', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_icon_type',
			[
				'label'       => esc_html__( 'Icon Type', 'powerpack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'label_block' => false,
				'options'     => [
					''      => esc_html__( 'None', 'powerpack' ),
					'icon'  => esc_html__( 'Icon', 'powerpack' ),
					'image' => esc_html__( 'Image', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'content_icon_location',
			[
				'label'       => esc_html__( 'Icon Location', 'powerpack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'start',
				'label_block' => false,
				'options'     => [
					'start' => esc_html__( 'Above Title', 'powerpack' ),
					'end'   => esc_html__( 'Below Content', 'powerpack' ),
				],
				'condition' => [
					'content_icon_type!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'label'     => esc_html__( 'Image Size', 'powerpack' ),
				'default'   => 'full',
				'condition' => [
					'content_icon_type' => 'image',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_items_controls() {
		$this->start_controls_section(
			'section_items',
			[
				'label'     => esc_html__( 'Items', 'powerpack' ),
				'condition' => [
					'source' => 'custom',
				],
			]
		);

		$this->add_control(
			'max_items_notice',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Interactive Circle supports maximum 8 items. Adding more than 8 items will break the layout.', 'powerpack' ),
				'content_classes' => 'pp-editor-info',
				'condition'       => [
					'source' => 'custom',
				],
			)
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'interactive_circle_tabs' );

		$repeater->start_controls_tab( 'interactive_circle_content_tab', [ 'label' => __( 'Content', 'powerpack' ) ] );

		$repeater->add_control(
			'item_title',
			[
				'label'       => esc_html__( 'Title', 'powerpack' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Item Title', 'powerpack' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'item_content',
			[
				'label'   => esc_html__( 'Content', 'powerpack' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Optio, neque qui velit. Magni dolorum quidem ipsam eligendi, totam, facilis laudantium cum accusamus ullam voluptatibus commodi numquam, error, est. Ea, consequatur.', 'powerpack' ),
				'dynamic' => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'tab_label',
			[
				'label'   => esc_html__( 'Tab Label', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'dynamic' => [ 'active' => true ],
				'ai' => [
					'active' => false,
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'interactive_circle_icon_tab', [ 'label' => __( 'Icon/Image', 'powerpack' ) ] );

		$repeater->add_control(
			'tab_icon',
			[
				'label'   => esc_html__( 'Icon', 'powerpack' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-home',
					'library' => 'fa-solid',
				],
			]
		);

		$repeater->add_control(
			'tab_image',
			[
				'label'     => __( 'Image', 'powerpack' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'interactive_circle_style_tab', [ 'label' => __( 'Style', 'powerpack' ) ] );

		$repeater->add_control(
			'interactive_circle_tab_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-wrapper:not(.pp-circle-skin-4) {{CURRENT_ITEM}} .pp-circle-tab-icon,
					{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-4 {{CURRENT_ITEM}} .pp-circle-tab-icon' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-4 {{CURRENT_ITEM}} .pp-circle-icon-shapes' => 'background-color: {{VALUE}};',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'tabs',
			[
				'type'        => Controls_Manager::REPEATER,
				'separator'   => 'before',
				'default'     => [
					[
						'tab_icon'     => [
							'value'   => 'fas fa-leaf',
							'library' => 'fa-solid',
						],
						'tab_label'    => esc_html__( 'Item 1', 'powerpack' ),
						'item_content' => esc_html__( 'Present your content in an attractive Circle layout item 1. You can highlight key information with click or hover effects and style it as per your preference.', 'powerpack' ),
					],
					[
						'tab_icon'     => [
							'value'   => 'fas fa-comment',
							'library' => 'fa-solid',
						],
						'tab_label'    => esc_html__( 'Item 2', 'powerpack' ),
						'item_content' => esc_html__( 'Present your content in an attractive Circle layout item 2. You can highlight key information with click or hover effects and style it as per your preference.', 'powerpack' ),
					],
					[
						'tab_icon'     => [
							'value'   => 'fas fa-map-marker-alt',
							'library' => 'fa-solid',
						],
						'tab_label'    => esc_html__( 'Item 3', 'powerpack' ),
						'item_content' => esc_html__( 'Present your content in an attractive Circle layout item 3. You can highlight key information with click or hover effects and style it as per your preference.', 'powerpack' ),
					],
					[
						'tab_icon'     => [
							'value'   => 'fas fa-rocket',
							'library' => 'fa-solid',
						],
						'tab_label'    => esc_html__( 'Item 4', 'powerpack' ),
						'item_content' => esc_html__( 'Present your content in an attractive Circle layout item 4. You can highlight key information with click or hover effects and style it as per your preference.', 'powerpack' ),
					],
					[
						'tab_icon'     => [
							'value'   => 'fas fa-hourglass-half',
							'library' => 'fa-solid',
						],
						'tab_label'    => esc_html__( 'Item 5', 'powerpack' ),
						'item_content' => esc_html__( 'Present your content in an attractive Circle layout item 5. You can highlight key information with click or hover effects and style it as per your preference.', 'powerpack' ),
					],
					[
						'tab_icon'     => [
							'value'   => 'fas fa-tag',
							'library' => 'fa-solid',
						],
						'tab_label'    => esc_html__( 'Item 6', 'powerpack' ),
						'item_content' => esc_html__( 'Present your content in an attractive Circle layout item 6. You can highlight key information with click or hover effects and style it as per your preference.', 'powerpack' ),
					],
				],
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{tab_label}}',
				'condition' => [
					'source' => 'custom',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function register_content_additional_options_controls() {
		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => esc_html__( 'Additional Options', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'active_tab',
			[
				'label'       => esc_html__( 'Default Active Item', 'powerpack' ),
				'description' => esc_html__( 'Add item number to make that item active by default. For example: Add 1 to make first item active by default.', 'powerpack' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'max'         => 100,
				'step'        => 1,
				'default'     => '',
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'open_on',
			[
				'label'              => esc_html__( 'Show Tab Content On', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'click',
				'label_block'        => false,
				'frontend_available' => true,
				'options'            => [
					'click' => esc_html__( 'Click', 'powerpack' ),
					'hover' => esc_html__( 'Hover', 'powerpack' ),
				],
				'conditions'         => [
					'relation' => 'or',
					'terms' => [
					[
						'name' => 'autoplay_tabs',
						'operator' => '!=',
						'value' => 'yes',
					],
					[
						'name' => 'skin',
						'operator' => '==',
						'value' => 'skin-3',
					],
					[
						'name' => 'skin',
						'operator' => '==',
						'value' => 'skin-4',
					],
					],
				],
			]
		);

		$this->add_control(
			'circle_animation',
			[
				'label'              => esc_html__( 'Entrance Animation', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'none',
				'label_block'        => false,
				'frontend_available' => true,
				'options'            => [
					'none'      => esc_html__( 'None', 'powerpack' ),
					'bounce-in' => esc_html__( 'Bounce In', 'powerpack' ),
					'rotate'    => esc_html__( 'Rotate', 'powerpack' ),
					'spin'      => esc_html__( 'Spin', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'circle_rotation',
			[
				'label'        => esc_html__( 'Rotate Animation', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition' => [
					'skin!' => 'skin-2'
				],
			]
		);

		$this->add_control(
			'circle_rotation_speed',
			[
				'label'      => esc_html__( 'Rotation Speed', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 's', 'ms', 'custom' ],
				'default'    => [
					'unit' => 's',
					'size' => 50,
				],
				'range'      => [
					's' => [
						'min' => 5,
						'max' => 200,
					],
					'ms' => [
						'min' => 5000,
						'max' => 50000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-interactive-circle-rotate' => 'animation-duration: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-interactive-circle-rotate .pp-circle-tab-icon' => 'animation-duration: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-interactive-circle-rotate .pp-circle-content' => 'animation-duration: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'skin!' => 'skin-2',
					'circle_rotation' => 'yes'
				],
			]
		);

		$this->add_control(
			'circle_rotation_pause_hover',
			[
				'label'        => esc_html__( 'Pause on Hover', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'condition'    => [
					'skin!' => 'skin-2',
					'circle_rotation' => 'yes'
				],
			]
		);

		$this->add_control(
			'autoplay_tabs',
			[
				'label'              => esc_html__( 'Autoplay Tabs', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'condition'          => [
					'skin' => [ 'skin-1', 'skin-2' ]
				],
			]
		);

		$this->add_control(
			'autoplay_tabs_interval',
			[
				'label'              => esc_html__( 'Interval', 'powerpack' ) . ' (ms)',
				'type'               => Controls_Manager::NUMBER,
				'default'            => 2000,
				'frontend_available' => true,
				'condition'	         => [
					'autoplay_tabs' => 'yes',
				]
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_circle_controls() {
		$this->start_controls_section(
			'section_circle_style',
			[
				'label' => esc_html__( 'Circle', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'circle_size',
			[
				'label'      => __( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 300,
						'max'  => 1000,
						'step' => 1,
					],
					'em' => [
						'min'  => 10,
						'max'  => 60,
					],
					'rem' => [
						'min'  => 10,
						'max'  => 60,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-circle-inner'                                     => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-circle-skin-2 .pp-circle-inner'       => 'width: {{SIZE}}{{UNIT}}; height: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .pp-circle-skin-2 .pp-circle-content'     => 'height: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .pp-circle-skin-2 .pp-circle-tab-content' => 'height: calc({{SIZE}}{{UNIT}} / 2);',
				],
				'devices'    => [ 'widescreen', 'desktop', 'tablet_extra', 'tablet' ],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'circle_border',
				'label'    => esc_html__( 'Border', 'powerpack' ),
				'selector' => '{{WRAPPER}} .pp-circle-inner, {{WRAPPER}}.pp-circle-stacked .pp-circle-inner .pp-circle-item',
			]
		);

		$this->add_responsive_control(
			'circle_margin',
			[
				'label'      => esc_html__( 'Margin', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-circle-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$breakpoints = PP_Helper::elementor()->breakpoints->get_active_breakpoints();

		$stack_on_options = ['none' => esc_html__( 'None', 'powerpack' )];

		foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
			// This feature is meant for mobile screens.
			if ( 'widescreen' === $breakpoint_key ) {
				continue;
			}

			$stack_on_options[ $breakpoint_key ] = sprintf(
				/* translators: 1: `<` character, 2: Breakpoint value. */
				esc_html__( '%1$s (%2$s %3$dpx)', 'powerpack' ),
				$breakpoint->get_label(),
				'<',
				$breakpoint->get_value()
			);
		}

		$this->add_control(
			'stack_on',
			array(
				'label'              => esc_html__( 'Stack On', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'mobile',
				'options'            => $stack_on_options,
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'circle_connectors',
			[
				'label'     => esc_html__( 'Connectors', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'skin' => 'skin-3'
				],
			]
		);
		$this->add_control(
			'circle_connector_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-shape-1, {{WRAPPER}} .pp-shape-2' => 'background: {{VALUE}};',
				],
				'condition' => [
					'skin' => 'skin-3'
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_tabs_controls() {
		$this->start_controls_section(
			'section_tabs_style',
			[
				'label' => esc_html__( 'Tabs', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'tab_size',
			[
				'label'      => __( 'Tab Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-circle-tab' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],

			]
		);
		$this->add_responsive_control(
			'tab_icon_size',
			[
				'label'      => __( 'Icon Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 16,
					'unit' => 'px',
				],
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-circle-tab-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-circle-tab-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .pp-circle-tab-text',
			]
		);

		$this->start_controls_tabs( 'circle_tabs_style' );

		$this->start_controls_tab( 'circle_tabs_style_normal', [ 'label' => esc_html__( 'Normal', 'powerpack' ) ] );

		$this->add_control(
			'tab_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-1 .pp-circle-item .pp-circle-tab .pp-circle-tab-icon, {{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-2 .pp-circle-item .pp-circle-tab .pp-circle-tab-icon'     => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-3 .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab .pp-circle-tab-icon .pp-circle-icon-inner, {{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-4 .pp-circle-tab .pp-circle-icon-inner' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'tab_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab .pp-circle-tab-icon .pp-circle-icon-inner span.pp-circle-tab-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab .pp-circle-tab-icon .pp-circle-tab-icon-inner span.pp-circle-tab-text' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'tab_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab .pp-circle-tab-icon i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab .pp-circle-tab-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'tabs_icon' => 'yes'
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'tab_border',
				'label'    => esc_html__( 'Border', 'powerpack' ),
				'selector' => '{{WRAPPER}} .pp-circle-wrapper .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab-icon',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tab_box_shadow',
				'selector' => '{{WRAPPER}} .pp-circle-wrapper .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'circle_tabs_style_hover', [ 'label' => esc_html__( 'Hover', 'powerpack' ) ] );

		$this->add_control(
			'tab_background_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-1 .pp-circle-tab:hover .pp-circle-tab-icon, {{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-2 .pp-circle-tab:hover .pp-circle-tab-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-3 .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab:hover .pp-circle-tab-icon .pp-circle-icon-inner, {{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-4 .pp-circle-tab:hover .pp-circle-icon-inner' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-1 .pp-circle-tab.active:hover .pp-circle-tab-icon, {{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-2 .pp-circle-tab.active:hover .pp-circle-tab-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-3 .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab.active:hover .pp-circle-tab-icon .pp-circle-icon-inner, {{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-4 .pp-circle-tab.active:hover .pp-circle-icon-inner' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_text_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab:hover .pp-circle-tab-icon .pp-circle-icon-inner span.pp-circle-tab-text'            => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab:hover .pp-circle-tab-icon .pp-circle-tab-icon-inner span.pp-circle-tab-text'        => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab.active:hover .pp-circle-tab-icon .pp-circle-icon-inner span.pp-circle-tab-text'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab.active:hover .pp-circle-tab-icon .pp-circle-tab-icon-inner span.pp-circle-tab-text' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'tab_icon_color_hover',
			[
				'label'     => esc_html__( 'Icon Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab:hover .pp-circle-tab-icon i, {{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab.active:hover .pp-circle-tab-icon i'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab:hover .pp-circle-tab-icon svg, {{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab.active:hover .pp-circle-tab-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'tabs_icon' => 'yes'
				],
			]
		);

		$this->add_control(
			'tab_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-item .pp-circle-tab:hover .pp-circle-tab-icon, {{WRAPPER}} .pp-circle-item .pp-circle-tab.active:hover .pp-circle-tab-icon' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-skin-4 .pp-circle-tab:hover .pp-circle-icon-shapes, {{WRAPPER}} .pp-circle-skin-4  .pp-circle-item .pp-circle-tab.active:hover .pp-circle-icon-shapes' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'tab_border_border!' => ''
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tab_box_shadow_hover',
				'selector' => '{{WRAPPER}} .pp-circle-wrapper .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'circle_tabs_style_active', [ 'label' => esc_html__( 'Active', 'powerpack' ) ] );

		$this->add_control(
			'tab_background_color_active',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-1 .pp-circle-tab.active .pp-circle-tab-icon,
					{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-2 .pp-circle-tab.active .pp-circle-tab-icon' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-3 .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab.active .pp-circle-tab-icon .pp-circle-icon-inner,
					{{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-4 .pp-circle-tab.active .pp-circle-icon-inner' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-tab.active .pp-circle-tab-icon' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_text_color_active',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab.active .pp-circle-tab-icon .pp-circle-icon-inner span.pp-circle-tab-text' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab.active .pp-circle-tab-icon .pp-circle-tab-icon-inner span.pp-circle-tab-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_icon_color_active',
			[
				'label'     => esc_html__( 'Icon Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-tab.active i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-tab.active svg' => 'fill: {{VALUE}};',
				],
				'selectors' => [
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab.active .pp-circle-tab-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab.active .pp-circle-tab-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'tabs_icon' => 'yes'
				],
			]
		);

		$this->add_control(
			'tab_border_color_active',
			[
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-item .pp-circle-tab.active .pp-circle-tab-icon' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-skin-4 .pp-circle-tab.active .pp-circle-icon-shapes' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'tab_border_border!' => ''
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'tab_box_shadow_active',
				'selector' => '{{WRAPPER}} .pp-circle-wrapper .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-tab.active',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_content_controls() {
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-circle-content' => 'background: {{VALUE}};',
				],
				'condition' => [
					'skin!' => 'skin-2'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'content_border',
				'label'     => esc_html__( 'Border', 'powerpack' ),
				'selector'  => '{{WRAPPER}} .pp-circle-content',
				'condition' => [
					'skin!' => 'skin-2'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'content_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-circle-wrapper:not(.pp-circle-skin-1) .pp-circle-content, {{WRAPPER}} .pp-circle-wrapper.pp-circle-skin-1 .pp-circle-inner',
				'condition' => [
					'skin!' => 'skin-2'
				],
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label'      => esc_html__( 'Margin', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-circle-tab-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'skin!' => 'skin-2'
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-circle-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} ;',
				],
			]
		);

		$this->add_control(
			'content_icon_heading_style',
			[
				'label'     => esc_html__( 'Icon/Image', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'content_icon_size',
			[
				'label'      => __( 'Icon Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 50,
					'unit' => 'px',
				],
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-circle-content-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-circle-content-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'content_icon_type!' => ''
				],
			]
		);

		$this->add_control(
			'content_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-content .pp-circle-content-icon i'   => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-circle-info .pp-circle-inner .pp-circle-item .pp-circle-content .pp-circle-content-icon svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'content_icon_type!' => ''
				],
			]
		);

		$this->add_responsive_control(
			'circle_content_image_width',
			[
				'label'      => __( 'Image Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'   => 25,
						'max'   => 200,
						'step'  => 1,
					],
				],
				'size_units' => [ 'px', '%', 'custom' ],
				'default'    => [
					'size' => 100,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-circle-content-image img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'content_icon_type!' => '',
				],
			]
		);

		$this->add_control(
			'title_heading_style',
			[
				'label'     => esc_html__( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'content_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors' => [
					'{{WRAPPER}} .pp-circle-content-title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'content_title_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .pp-circle-content-title',
			]
		);

		$this->add_control(
			'content_heading_style',
			[
				'label'     => esc_html__( 'Content', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'circle_content_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'selectors' => [
					'{{WRAPPER}} .pp-circle-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'circle_content_typography',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .pp-circle-content',
			]
		);

		$this->end_controls_section();
	}

	protected function render_tab_icon( $item ) {
		$settings       = $this->get_settings_for_display();
		$show_btn_icon  = isset( $settings['tabs_icon'] ) && 'yes' === $settings['tabs_icon'];
		$show_btn_title = isset( $settings['tabs_title'] ) && 'yes' === $settings['tabs_title'];

		if ( $show_btn_icon ) {
			Icons_Manager::render_icon( $item['tab_icon'] );
		}
		if ( $show_btn_title ) {
			echo '<span class="pp-circle-tab-text">' . esc_html( $item['tab_label'] ) . '</span>';
		}
	}

	protected function render_content_icon( $item, $icon_type ) {
		$settings = $this->get_settings_for_display();

		if ( 'icon' === $icon_type ) {
			?>
			<div class="pp-circle-content-icon">
				<?php Icons_Manager::render_icon( $item['tab_icon'], [ 'aria-hidden' => 'true' ] ); ?>
			</div>
			<?php
		} elseif ( 'image' === $icon_type ) {
			if ( ! empty( $item['tab_image']['url'] ) ) {
				?>
				<div class="pp-circle-content-image">
					<?php
					$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['tab_image']['id'], 'image', $settings );

					if ( $image_url ) {
						?>
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( Control_Media::get_image_alt( $item['tab_image'] ) ); ?>">
						<?php
					} else {
						?>
						<img src="<?php echo esc_url( $item['tab_image']['url'] ); ?>">
						<?php
					}
					?>
				</div>
				<?php
			}
		}
	}

	protected function render_circle_content( $item ) {
		$settings  = $this->get_settings_for_display();
		$icon_type = $settings['content_icon_type'];

		if ( $icon_type && 'start' === $settings['content_icon_location'] ) {
			$this->render_content_icon( $item, $icon_type );
		}
		?>
		<div class="pp-circle-content-title"><?php echo wp_kses_post( $item['item_title'] ); ?></div>
		<?php
		echo wpautop( wp_kses_post( $item['item_content'] ) );

		if ( $icon_type && 'end' === $settings['content_icon_location'] ) {
			$this->render_content_icon( $item, $icon_type );
		}
	}

	protected function render_skin_circle( $items, $item_count ) {
		$settings   = $this->get_settings_for_display();
		$active_tab = ( '' !== $settings['active_tab'] ) ? $settings['active_tab'] : 1;
		$active_tab = $active_tab - 1;
		?>
		<div <?php echo $this->get_render_attribute_string( 'circle_info' ); ?>>
			<div class="pp-circle-inner">
				<?php
				foreach ( $items as $index => $item ) :
					$item_count = $index + 1;
					$is_active  = $index === $active_tab ? 'active' : '';
					?>
					<div class="pp-circle-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
						<div aria-controls="pp-interactive-<?php echo esc_attr( $item_count ); ?>" tabindex="0" class="pp-circle-tab <?php echo $is_active; ?>" id="pp-circle-item-<?php echo $item_count; ?>">
							<?php if ( in_array( $settings['skin'], [ 'skin-3', 'skin-4'] ) ) { ?>
								<div class="pp-circle-icon-shapes">
									<div class="pp-shape-1"></div>
									<div class="pp-shape-2"></div>
								</div>
							<?php } ?>
							<div class="pp-circle-tab-icon">
								<div class="pp-circle-icon-inner">
									<?php $this->render_tab_icon( $item ); ?>
								</div>
							</div>
						</div>
						<div id="pp-interactive-<?php echo esc_attr( $item_count ); ?>" aria-labelledby="pp-circle-item-<?php echo esc_attr( $item_count ); ?>" class="pp-circle-tab-content pp-circle-item-<?php echo $item_count . ' ' . $is_active; ?>">
							<div class="pp-circle-content">
								<?php $this->render_circle_content( $item ); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	protected function render_skin_circle_half( $items, $item_count ) {
		$settings   = $this->get_settings_for_display();
		$active_tab = ( '' !== $settings['active_tab'] ) ? $settings['active_tab'] : 1;
		$active_tab = $active_tab - 1;
		?>
		<div class="pp-circle-info">
			<div class="pp-circle-inner" data-items="<?php echo $item_count; ?>">
				<?php
				foreach ( $items as $index => $item ) :
					$item_count = $index + 1;
					$is_active  = $index === $active_tab ? 'active' : '';
					?>
					<div class="pp-circle-item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
						<div aria-controls="pp-interactive-<?php echo esc_attr( $item_count ); ?>" tabindex="0" class="pp-circle-tab <?php echo $is_active; ?>" id="pp-circle-item-<?php echo $item_count; ?>">
							<div class="pp-circle-icon-shapes">
								<div class="pp-shape-1"></div>
								<div class="pp-shape-2"></div>
							</div>
							<div class="pp-circle-tab-icon">
								<div class="pp-circle-tab-icon-inner">
									<?php $this->render_tab_icon( $item ); ?>
								</div>
							</div>
						</div>
						<div class="pp-circle-tab-content pp-circle-item-<?php echo $item_count . ' ' . $is_active; ?>">
							<div id="pp-interactive<?php echo esc_attr( $item_count ); ?>" aria-labelledby="pp-circle-item-<?php echo esc_attr( $item_count ); ?>" class="pp-circle-content">
								<?php $this->render_circle_content( $item ); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render promo box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'container', 'class', 'pp-interactive-circle' );

		$this->add_render_attribute(
			'circle_wrapper',
			[
				'class' => [
					'pp-circle-wrapper',
					'pp-circle-' . $settings['skin'],
				],
			]
		);

		$items          = $this->get_circle_items();
		$item_count     = count( $items );
		$stack_on       = ( $settings['stack_on'] ) ?? 'tablet';
		$mobile_view    = ( 'none' === $stack_on ) ? 'pp-circle-desktop-view' : '';
		$stack_on_width = 'none';

		$breakpoints = PP_Helper::elementor()->breakpoints->get_active_breakpoints();

		foreach ( $breakpoints as $breakpoint_key => $breakpoint ) {
			if ( $stack_on === $breakpoint_key ) {
				$stack_on_width = $breakpoint->get_value();
				break;
			}
		}

		$this->add_render_attribute( 'circle_wrapper', 'class', $mobile_view );
		$this->add_render_attribute( 'circle_info', 'class', 'pp-circle-info' );
		$this->add_render_attribute( 'circle_info', 'data-items', $item_count );

		if ( 'yes' === $settings['circle_rotation'] ) {
			$this->add_render_attribute( 'circle_wrapper', 'class', 'pp-interactive-circle-rotate' );
		}

		if ( 'yes' === $settings['circle_rotation_pause_hover'] ) {
			$this->add_render_attribute( 'circle_wrapper', 'class', 'pp-pause-rotate');
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'container' ); ?>>
			<?php if ( ( $settings['skin'] != 'skin-2' ) ) { ?>
				<div <?php echo $this->get_render_attribute_string( 'circle_wrapper' ); ?>>
					<?php $this->render_skin_circle( $items, $item_count ); ?>
				</div>
			<?php } else { ?>
				<div <?php echo $this->get_render_attribute_string( 'circle_wrapper' ); ?>>
					<?php $this->render_skin_circle_half( $items, $item_count ); ?>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Get post query arguments.
	 *
	 * @access protected
	 */
	protected function get_posts_query_arguments() {
		$settings = $this->get_settings();
		$posts_count = absint( $settings['posts_per_page'] );

		// Query Arguments
		$query_args = array(
			'post_status'           => array( 'publish' ),
			'post_type'             => $settings['post_type'],
			'orderby'               => $settings['orderby'],
			'order'                 => $settings['order'],
			'offset'                => $settings['offset'],
			'ignore_sticky_posts'   => ( 'yes' === $settings['sticky_posts'] ) ? 0 : 1,
			'showposts'             => $posts_count,
		);

		// Author Filter
		if ( ! empty( $settings['authors'] ) ) {
			$query_args[ $settings['author_filter_type'] ] = $settings['authors'];
		}

		// Posts Filter
		$post_type = $settings['post_type'];

		if ( 'post' === $post_type ) {
			$posts_control_key = 'exclude_posts';
		} else {
			$posts_control_key = $post_type . '_filter';
		}

		if ( ! empty( $settings[ $posts_control_key ] ) ) {
			$query_args[ $settings[ $post_type . '_filter_type' ] ] = $settings[ $posts_control_key ];
		}

		// Taxonomy Filter
		$taxonomy = PP_Posts_Helper::get_post_taxonomies( $post_type );

		if ( ! empty( $taxonomy ) && ! is_wp_error( $taxonomy ) ) {

			foreach ( $taxonomy as $index => $tax ) {

				if ( 'post' === $post_type ) {
					if ( 'post_tag' === $index ) {
						$tax_control_key = 'tags';
					} elseif ( 'category' === $index ) {
						$tax_control_key = 'categories';
					} else {
						$tax_control_key = $index . '_' . $post_type;
					}
				} else {
					$tax_control_key = $index . '_' . $post_type;
				}

				if ( ! empty( $settings[ $tax_control_key ] ) ) {

					$operator = $settings[ $index . '_' . $post_type . '_filter_type' ];

					$query_args['tax_query'][] = [
						'taxonomy' => $index,
						'field'    => 'term_id',
						'terms'    => $settings[ $tax_control_key ],
						'operator' => $operator,
					];
				}
			}
		}

		if ( 'anytime' !== $settings['select_date'] ) {
			$select_date = $settings['select_date'];
			if ( ! empty( $select_date ) ) {
				$date_query = [];
				if ( 'today' === $select_date ) {
					$date_query['after'] = '-1 day';
				} elseif ( 'week' === $select_date ) {
					$date_query['after'] = '-1 week';
				} elseif ( 'month' === $select_date ) {
					$date_query['after'] = '-1 month';
				} elseif ( 'quarter' === $select_date ) {
					$date_query['after'] = '-3 month';
				} elseif ( 'year' === $select_date ) {
					$date_query['after'] = '-1 year';
				} elseif ( 'exact' === $select_date ) {
					$after_date = $settings['date_after'];
					if ( ! empty( $after_date ) ) {
						$date_query['after'] = $after_date;
					}
					$before_date = $settings['date_before'];
					if ( ! empty( $before_date ) ) {
						$date_query['before'] = $before_date;
					}
					$date_query['inclusive'] = true;
				}

				$query_args['date_query'] = $date_query;
			}
		}

		// Sticky Posts Filter
		if ( 'yes' === $settings['sticky_posts'] && 'yes' === $settings['all_sticky_posts'] ) {
			$post__in = get_option( 'sticky_posts' );

			$query_args['post__in'] = $post__in;
		}

		return apply_filters( 'ppe_interactive_circle_query_args', $query_args, $settings );
	}

	/**
	 * Render posts output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function get_interactive_circle_posts() {
		$settings = $this->get_settings();

		$i = 0;
		$ic_item = array();
		$active_tabs = array();

		// Query Arguments
		$args = $this->get_posts_query_arguments();
		$posts_query = new \WP_Query( $args );

		if ( $posts_query->have_posts() ) :
			while ( $posts_query->have_posts() ) :
				$posts_query->the_post();

				$limit = $settings['preview_excerpt_length'];

				$ic_item[ $i ]['_id'] = 'ppic' . $i;
				$ic_item[ $i ]['tab_icon'] = [];

				$ic_item[ $i ]['tab_label'] = $this->get_circle_post_content( get_the_title(), 3 );

				$ic_item[ $i ]['item_title'] = $this->get_circle_post_content( get_the_title(), 5 );

				if ( 'excerpt' === $settings['posts_content_type'] ) {
					$content = get_the_excerpt();
				} else {
					$content = get_the_content();
				}

				$ic_item[ $i ]['item_content'] = $this->get_circle_post_content( $content, $limit );
				$ic_item[ $i ]['icon_type'] = 'image';

				$attachment_id = get_post_thumbnail_id( $posts_query->post->ID );
				$url = wp_get_attachment_image_src($attachment_id, 'desired-size');

				$ic_item[ $i ]['tab_image']['id'] = $attachment_id;
				$ic_item[ $i ]['tab_image']['url'] = ! empty( $url ) ? $url[0] : '';

				$i++;
			endwhile;
		endif;

		wp_reset_postdata();

		return $ic_item;
	}

	public function get_circle_items() {
		$settings = $this->get_settings();

		return $settings['tabs'];
	}

	/**
	 * Get custom post excerpt.
	 *
	 * @access protected
	 */
	protected function get_circle_post_content( $content, $limit = '' ) {
		$settings = $this->get_settings();

		$content = explode( ' ', $content, $limit );

		if ( count( $content ) >= $limit ) {
			array_pop( $content );
			$content = implode( ' ', $content ) . '...';
		} else {
			$content = implode( ' ', $content );
		}

		$content = preg_replace( '`[[^]]*]`', '', $content );

		return $content;
	}
}
