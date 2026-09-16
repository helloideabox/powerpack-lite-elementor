<?php
namespace PowerpackElementsLite\Modules\ImageComparison\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Image Comparison Widget
 */
class Image_Comparison extends Powerpack_Widget {

	/**
	 * Retrieve image comparison widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Image_Comparison' );
	}

	/**
	 * Retrieve image comparison widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Image_Comparison' );
	}

	/**
	 * Retrieve image comparison widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Image_Comparison' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.3.4
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Image_Comparison' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the image comparison widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [
			'jquery-event-move',
			'pp-image-comparison',
		];
	}

	/**
	 * Retrieve the list of styles the image comparison widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		return [ 'widget-pp-image-comparison' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! PP_Helper::is_feature_active( 'e_optimized_markup' );
	}

	/**
	 * Register image comparison widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.3.2
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_before_image_controls();
		$this->register_content_after_image_controls();
		$this->register_content_settings_controls();

		/* Style Tab */
		$this->register_style_overlay_controls();
		$this->register_style_handle_controls();
		$this->register_style_divider_controls();
		$this->register_style_label_controls();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	CONTENT TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_content_before_image_controls() {
		/**
		 * Content Tab: Before Image
		 */
		$this->start_controls_section(
			'section_before_image',
			[
				'label'             => esc_html__( 'Before Image', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'before_label',
			[
				'label'             => esc_html__( 'Label', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::TEXT,
				'default'           => esc_html__( 'Before', 'powerpack-lite-for-elementor' ),
				'dynamic'           => array(
					'active' => true,
				),
			]
		);

		$this->add_control(
			'before_image',
			[
				'label'             => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::MEDIA,
				'dynamic'           => [
					'active'   => true,
				],
				'default'           => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'              => 'before_image',
				'default'           => 'full',
				'separator'         => 'none',
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_after_image_controls() {
		/**
		 * Content Tab: After Image
		 */
		$this->start_controls_section(
			'section_after_image',
			[
				'label'             => esc_html__( 'After Image', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'after_label',
			[
				'label'             => esc_html__( 'Label', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::TEXT,
				'default'           => esc_html__( 'After', 'powerpack-lite-for-elementor' ),
				'dynamic'           => array(
					'active' => true,
				),
			]
		);

		$this->add_control(
			'after_image',
			[
				'label'             => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::MEDIA,
				'dynamic'           => [
					'active'   => true,
				],
				'default'           => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'              => 'after_image',
				'default'           => 'full',
				'separator'         => 'none',
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_settings_controls() {
		/**
		 * Content Tab: Settings
		 */
		$this->start_controls_section(
			'section_settings',
			[
				'label'             => esc_html__( 'Settings', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'visible_ratio',
			[
				'label'                 => esc_html__( 'Visible Ratio', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => '',
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 1,
						'step'  => 0.1,
					],
				],
			]
		);

		$this->add_control(
			'orientation',
			[
				'label'                 => esc_html__( 'Orientation', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'horizontal',
				'options'               => [
					'vertical'      => esc_html__( 'Vertical', 'powerpack-lite-for-elementor' ),
					'horizontal'    => esc_html__( 'Horizontal', 'powerpack-lite-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'move_slider',
			[
				'label'                 => esc_html__( 'Move Slider', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'drag',
				'options'               => [
					'drag'          => esc_html__( 'Drag', 'powerpack-lite-for-elementor' ),
					'mouse_move'    => esc_html__( 'Mouse Move', 'powerpack-lite-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'overlay',
			[
				'label'             => esc_html__( 'Overlay', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::SWITCHER,
				'default'           => 'yes',
				'label_on'          => esc_html__( 'Show', 'powerpack-lite-for-elementor' ),
				'label_off'         => esc_html__( 'Hide', 'powerpack-lite-for-elementor' ),
				'return_value'      => 'yes',
			]
		);

		$this->end_controls_section();
	}


	/*-----------------------------------------------------------------------------------*/
	/*	STYLE TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_style_overlay_controls() {
		/**
		 * Style Tab: Overlay
		 */
		$this->start_controls_section(
			'section_overlay_style',
			[
				'label'             => esc_html__( 'Overlay', 'powerpack-lite-for-elementor' ),
				'tab'               => Controls_Manager::TAB_STYLE,
				'condition'         => [
					'overlay'  => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_overlay_style' );

		$this->start_controls_tab(
			'tab_overlay_normal',
			[
				'label'             => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'overlay_background',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .pp-image-comparison-overlay',
				'condition'         => [
					'overlay'  => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_overlay_hover',
			[
				'label'             => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'overlay_background_hover',
				'types'             => [ 'classic', 'gradient' ],
				// Keyboard focus gets the same treatment as hover. The handle sits above
				// the overlay rather than inside it, so :focus-within is asked of the widget.
				'selector'          => '{{WRAPPER}} .pp-image-comparison-overlay:hover, {{WRAPPER}} .pp-image-comparison:focus-within .pp-image-comparison-overlay',
				'condition'         => [
					'overlay'  => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_handle_controls() {
		/**
		 * Style Tab: Handle
		 */
		$this->start_controls_section(
			'section_handle_style',
			[
				'label'             => esc_html__( 'Handle', 'powerpack-lite-for-elementor' ),
				'tab'               => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'handle_icon',
			array(
				'label'                  => esc_html__( 'Choose Icon', 'powerpack-lite-for-elementor' ),
				'type'                   => Controls_Manager::ICONS,
				'label_block'            => false,
				'default'                => array(
					'value'   => 'fas fa-caret-right',
					'library' => 'fa-solid',
				),
				'skin'                   => 'inline',
				'exclude_inline_options' => 'svg',
				'recommended'            => array(
					'fa-solid'   => array(
						'angle-right',
						'angle-double-right',
						'chevron-right',
						'arrow-right',
						'long-arrow-alt-right',
						'caret-right',
					),
				),
			)
		);

		$this->add_responsive_control(
			'handle_icon_size',
			[
				'label'             => esc_html__( 'Icon Size', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::SLIDER,
				'size_units'        => [ 'px', 'em', 'rem', 'custom' ],
				'default'           => [
					'size' => 16,
					'unit' => 'px',
				],
				'range'             => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-handle' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-comparison-handle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'handle_width',
			[
				'label'             => esc_html__( 'Width', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::SLIDER,
				'size_units'        => [ 'px', 'em', 'rem', 'custom' ],
				'default'           => [
					'size' => 42,
					'unit' => 'px',
				],
				'range'             => [
					'px' => [
						// The handle is the only control; 24 is the WCAG 2.5.8 floor.
						'min' => 24,
						'max' => 100,
					],
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-image-comparison .pp-comparison-handle' => 'width: {{SIZE}}{{UNIT}}; margin-left: calc(-{{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .pp-image-comparison-vertical .pp-comparison-handle:before' => 'margin-right: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .pp-image-comparison-vertical .pp-comparison-handle:after' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->add_responsive_control(
			'handle_height',
			[
				'label'             => esc_html__( 'Height', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::SLIDER,
				'size_units'        => [ 'px', 'em', 'rem', 'custom' ],
				'default'           => [
					'size' => 42,
					'unit' => 'px',
				],
				'range'             => [
					'px' => [
						// The handle is the only control; 24 is the WCAG 2.5.8 floor.
						'min' => 24,
						'max' => 100,
					],
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-image-comparison .pp-comparison-handle' => 'height: {{SIZE}}{{UNIT}}; margin-top: calc(-{{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .pp-image-comparison-horizontal .pp-comparison-handle:before' => 'margin-bottom: calc({{SIZE}}{{UNIT}} / 2);',
					'{{WRAPPER}} .pp-image-comparison-horizontal .pp-comparison-handle:after' => 'margin-top: calc({{SIZE}}{{UNIT}} / 2);',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_handle_style' );

		$this->start_controls_tab(
			'tab_handle_normal',
			[
				'label'             => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'handle_icon_color',
			[
				'label'             => esc_html__( 'Icon Color', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::COLOR,
				'default'           => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-handle' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-comparison-handle svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'handle_background',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .pp-comparison-handle',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'handle_border',
				'label'             => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'selector'          => '{{WRAPPER}} .pp-comparison-handle',
				'separator'         => 'before',
			]
		);

		$this->add_control(
			'handle_border_radius',
			[
				'label'             => esc_html__( 'Border Radius', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-handle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'handle_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-comparison-handle',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_handle_hover',
			[
				'label'             => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'handle_icon_color_hover',
			[
				'label'             => esc_html__( 'Icon Color', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::COLOR,
				'default'           => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-handle:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-comparison-handle:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'handle_background_hover',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .pp-comparison-handle:hover',
			]
		);

		$this->add_control(
			'handle_border_color_hover',
			[
				'label'             => esc_html__( 'Border Color', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::COLOR,
				'default'           => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-handle:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_divider_controls() {
		/**
		 * Style Tab: Divider
		 */
		$this->start_controls_section(
			'section_divider_style',
			[
				'label'             => esc_html__( 'Divider', 'powerpack-lite-for-elementor' ),
				'tab'               => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'             => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::COLOR,
				'default'           => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-handle:before, {{WRAPPER}} .pp-comparison-handle:after' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_width',
			[
				'label'             => esc_html__( 'Width', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::SLIDER,
				'size_units'        => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'           => [
					'size' => 3,
					'unit' => 'px',
				],
				'range'             => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-image-comparison-horizontal .pp-comparison-handle:before, {{WRAPPER}} .pp-image-comparison-horizontal .pp-comparison-handle:after' => 'margin-left: calc(-{{SIZE}}{{UNIT}} / 2); width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-image-comparison-vertical .pp-comparison-handle:before, {{WRAPPER}} .pp-image-comparison-vertical .pp-comparison-handle:after' => 'margin-top: calc(-{{SIZE}}{{UNIT}} / 2); height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_label_controls() {
		/**
		 * Style Tab: Label
		 */
		$this->start_controls_section(
			'section_label_style',
			[
				'label'             => esc_html__( 'Label', 'powerpack-lite-for-elementor' ),
				'tab'               => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'label_horizontal_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'default'               => 'top',
				'options'               => [
					'top'          => [
						'title'    => esc_html__( 'Top', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => esc_html__( 'Middle', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => esc_html__( 'Bottom', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary'  => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'prefix_class'          => 'pp-ic-label-horizontal-',
				'selectors'             => [
					'{{WRAPPER}} .pp-image-comparison-horizontal .pp-comparison-label' => 'justify-content: {{VALUE}};',
				],
				'condition'             => [
					'orientation'  => 'horizontal',
				],
			]
		);

		$this->add_control(
			'label_vertical_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'           => [
						'title' => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'            => [
						'title' => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'               => 'center',
				'prefix_class'          => 'pp-ic-label-vertical-',
				'selectors_dictionary'  => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-comparison-vertical .pp-comparison-label' => 'justify-content: {{VALUE}};',
				],
				'condition'             => [
					'orientation'  => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'label_align',
			[
				'label'             => esc_html__( 'Align', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::SLIDER,
				'size_units'        => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'             => [
					'px' => [
						'max' => 200,
					],
				],
				'selectors'         => [
					'{{WRAPPER}}.pp-ic-label-horizontal-top .pp-image-comparison-horizontal .pp-comparison-label span' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-image-comparison-horizontal .pp-comparison-label-before' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-image-comparison-horizontal .pp-comparison-label-after' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-ic-label-horizontal-bottom .pp-image-comparison-horizontal .pp-comparison-label span' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-image-comparison-vertical .pp-comparison-label-before' => 'top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-image-comparison-vertical .pp-comparison-label-after' => 'bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-ic-label-vertical-left .pp-image-comparison-vertical .pp-comparison-label span' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-ic-label-vertical-right .pp-image-comparison-vertical .pp-comparison-label span' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'              => 'label_typography',
				'label'             => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'global'            => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'          => '{{WRAPPER}} .pp-comparison-label-before > span, {{WRAPPER}} .pp-comparison-label-after > span',
				'separator'         => 'before',
			]
		);

		$this->start_controls_tabs( 'tabs_label_style' );

		$this->start_controls_tab(
			'tab_label_before',
			[
				'label'             => esc_html__( 'Before', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'label_text_color_before',
			[
				'label'             => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::COLOR,
				'default'           => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-label-before > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'label_bg_color_before',
			[
				'label'             => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::COLOR,
				'default'           => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-label-before > span' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'label_border',
				'label'             => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'selector'          => '{{WRAPPER}} .pp-comparison-label-before > span',
			]
		);

		$this->add_control(
			'label_border_radius',
			[
				'label'             => esc_html__( 'Border Radius', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-label-before > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_label_after',
			[
				'label'             => esc_html__( 'After', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'label_text_color_after',
			[
				'label'             => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::COLOR,
				'default'           => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-label-after > span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'label_bg_color_after',
			[
				'label'             => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::COLOR,
				'default'           => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-label-after > span' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'label_border_after',
				'label'             => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'selector'          => '{{WRAPPER}} .pp-comparison-label-after > span',
			]
		);

		$this->add_control(
			'label_border_radius_after',
			[
				'label'             => esc_html__( 'Border Radius', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-label-after > span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'label_padding',
			[
				'label'             => esc_html__( 'Padding', 'powerpack-lite-for-elementor' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-comparison-label-before > span, {{WRAPPER}} .pp-comparison-label-after > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'         => 'before',
			]
		);

		$this->end_controls_section();

	}

	/**
	 *  Get Image Source.
	 *
	 * @access protected
	 */
	protected function get_image_src( $settings, $position ) {

		$image_id  = apply_filters( 'wpml_object_id', $settings[ $position . '_image' ]['id'], 'attachment', true );
		$image_url = '';

		if ( ! empty( $image_id ) ) {
			$image_url = Group_Control_Image_Size::get_attachment_image_src( $image_id, $position . '_image', $settings );
		} else {
			$image_url = $settings[ $position . '_image' ]['url'];
		}

		return $image_url;
	}

	/**
	 * Build an image's accessible name.
	 *
	 * Only part of each image is on screen at a time, so the name has to say which side it
	 * is. Control_Media::get_image_alt() falls back to the attachment caption and then its
	 * post title, which is normally the file name, so only an alt the author actually
	 * wrote is used here.
	 *
	 * @since x.x.x
	 * @access protected
	 *
	 * @param array  $settings Widget settings.
	 * @param string $position 'before' or 'after'.
	 * @param string $label    The side's label, already defaulted.
	 * @return string
	 */
	protected function get_image_a11y_alt( $settings, $position, $label ) {
		$image = $settings[ $position . '_image' ];
		$alt   = '';

		if ( ! empty( $image['id'] ) ) {
			$alt = trim( (string) get_post_meta( $image['id'], '_wp_attachment_image_alt', true ) );
		} elseif ( ! empty( $image['alt'] ) ) {
			$alt = trim( $image['alt'] );
		}

		if ( '' === $alt ) {
			return $label;
		}

		return sprintf(
			/* translators: 1: the image's label, for example Before. 2: the image's alt text. */
			esc_attr__( '%1$s: %2$s', 'powerpack-lite-for-elementor' ),
			$label,
			$alt
		);
	}

	/**
	 * Render before/after image.
	 *
	 * @param array  $settings Widget settings.
	 * @param string $type     Image type (before|after).
	 * @param string $label    The side's label, already defaulted.
	 */
	private function render_image( $settings, $type, $label ) {

		if ( empty( $settings[ $type . '_image' ]['url'] ) ) {
			return;
		}

		$image_url = $this->get_image_src( $settings, $type );

		$attribute_key = $type . '-image';

		$this->add_render_attribute(
			$attribute_key,
			[
				'src'   => esc_url( $image_url ),
				'alt'   => $this->get_image_a11y_alt( $settings, $type, $label ),
				'class' => 'pp-' . esc_attr( $type ) . '-img',
			]
		);
		?>

		<div class="pp-<?php echo esc_attr( $type ); ?>-image">
			<img <?php $this->print_render_attribute_string( $attribute_key ); ?> />
		</div>

		<?php
	}

	/**
	 * Render comparison handle.
	 *
	 * @param array  $settings    Widget settings.
	 * @param string $orientation Layout orientation.
	 */
	private function render_handle( $settings, $orientation ) {
		// The handle is the keyboard slider, so it is rendered even without an icon.
		$icon = ! empty( $settings['handle_icon']['value'] ) ? $settings['handle_icon'] : '';

		if ( $icon ) {
			if ( 'horizontal' === $orientation ) {
				$before_icon = str_replace( 'right', 'left', $icon );
				$after_icon  = $icon;
			} else {
				$before_icon = str_replace( 'right', 'up', $icon );
				$after_icon  = str_replace( 'right', 'down', $icon );
			}
		}
		?>

		<div <?php $this->print_render_attribute_string( 'comparison-handle' ); ?>>
			<?php
			if ( $icon ) {
				Icons_Manager::render_icon( $before_icon, [ 'aria-hidden' => 'true' ] );
				Icons_Manager::render_icon( $after_icon, [ 'aria-hidden' => 'true' ] );
			}
			?>
		</div>

		<?php
	}

	/**
	 * Render overlay and labels.
	 *
	 * @param array $settings Widget settings.
	 */
	private function render_overlay( $settings ) {

		$overlay_enabled = ( isset( $settings['overlay'] ) && 'yes' === $settings['overlay'] );

		if ( ! $overlay_enabled && empty( $settings['before_label'] ) && empty( $settings['after_label'] ) ) {
			return;
		}

		if ( $overlay_enabled ) :
			?>
			<div class="pp-image-comparison-overlay">
		<?php endif; ?>

		<?php
		// The labels already name the images; announcing them again here would repeat
		// "Before" with nothing to attach it to. They are also faded out with opacity,
		// which AT ignores, so they would be read out while invisible on screen.
		if ( ! empty( $settings['before_label'] ) ) :
			?>
			<div class="pp-comparison-label pp-comparison-label-before" aria-hidden="true">
				<span><?php echo esc_html( $settings['before_label'] ); ?></span>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $settings['after_label'] ) ) : ?>
			<div class="pp-comparison-label pp-comparison-label-after" aria-hidden="true">
				<span><?php echo esc_html( $settings['after_label'] ); ?></span>
			</div>
		<?php endif; ?>

		<?php if ( $overlay_enabled ) : ?>
			</div>
		<?php endif;
	}

	/**
	 * Render image comparison widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$orientation   = ! empty( $settings['orientation'] ) ? $settings['orientation'] : 'vertical';
		$move_slider   = ! empty( $settings['move_slider'] ) ? $settings['move_slider'] : '';
		$visible_ratio = ! empty( $settings['visible_ratio']['size'] ) ? $settings['visible_ratio']['size'] : '0.5';

		$is_vertical   = 'vertical' === $orientation;
		$ratio_percent = max( 0, min( 100, round( (float) $visible_ratio * 100 ) ) );

		$widget_options = [
			'visible_ratio'      => $visible_ratio,
			'orientation'        => $orientation,
			'slider_on_hover'    => ( 'mouse_move' === $move_slider ),
			'slider_with_handle' => ( 'drag' === $move_slider ),
			'slider_with_click'  => ( 'mouse_click' === $move_slider ),
		];

		// The labels are the only thing that says which image is which, so they name the
		// images too. A cleared label still leaves a side to name, hence the fallbacks.
		$before_name = ! empty( $settings['before_label'] ) ? $settings['before_label'] : esc_html__( 'Before', 'powerpack-lite-for-elementor' );
		$after_name  = ! empty( $settings['after_label'] ) ? $settings['after_label'] : esc_html__( 'After', 'powerpack-lite-for-elementor' );

		// {percent} is swapped for the live value by the script on every move.
		$value_text = sprintf(
			/* translators: 1: the before image's label. 2: a token the script replaces with the current percentage. */
			esc_attr__( '%1$s %2$s%% visible', 'powerpack-lite-for-elementor' ),
			$before_name,
			'{percent}'
		);

		$this->add_render_attribute(
			'wrapper',
			[
				'class'         => [
					'pp-image-comparison',
					'pp-image-comparison-' . esc_attr( $orientation ),
				],
				'id'            => 'pp-image-comparison-' . esc_attr( $this->get_id() ),
				'data-settings' => wp_json_encode( $widget_options ),
				'role'          => 'group',
				'aria-label'    => $this->get_title(),
			]
		);

		$this->add_render_attribute(
			'comparison-handle',
			[
				'class'            => 'pp-comparison-handle',
				'role'             => 'slider',
				'tabindex'         => '0',
				'aria-label'       => esc_attr__( 'Image comparison slider', 'powerpack-lite-for-elementor' ),
				'aria-orientation' => $is_vertical ? 'vertical' : 'horizontal',
				'aria-valuemin'    => '0',
				'aria-valuemax'    => '100',
				'aria-valuenow'    => (string) $ratio_percent,
				'aria-valuetext'   => str_replace( '{percent}', $ratio_percent, $value_text ),
				'data-value-text'  => $value_text,
			]
		);
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>

			<?php
			$this->render_image( $settings, 'before', $before_name );
			$this->render_image( $settings, 'after', $after_name );
			$this->render_handle( $settings, $orientation );
			$this->render_overlay( $settings );
			?>

		</div>
		<?php
	}

	/**
	 * Render image comparison widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.0.3
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			var visible_ratio       = ( settings.visible_ratio.size != '' ) ? settings.visible_ratio.size : '0.5';
			var slider_on_hover     = ( settings.move_slider == 'mouse_move' ) ? true : false;
			var slider_with_handle  = ( settings.move_slider == 'drag' ) ? true : false;
			var slider_with_click   = ( settings.move_slider == 'mouse_click' ) ? true : false;

			var is_vertical  = ( 'vertical' === settings.orientation );
			var before_name  = settings.before_label ? settings.before_label : '<?php echo esc_js( __( 'Before', 'powerpack-lite-for-elementor' ) ); ?>';
			var after_name   = settings.after_label ? settings.after_label : '<?php echo esc_js( __( 'After', 'powerpack-lite-for-elementor' ) ); ?>';
			var ratio_pct    = Math.round( Math.max( 0, Math.min( 1, parseFloat( visible_ratio ) || 0 ) ) * 100 );
			var value_text   = '<?php echo esc_js( __( '{name} {percent}% visible', 'powerpack-lite-for-elementor' ) ); ?>'.replace( '{name}', before_name );
		#>
		<div class="pp-image-comparison pp-image-comparison-{{ settings.orientation }}" role="group" aria-label="<?php echo esc_attr( $this->get_title() ); ?>" data-settings='{ "visible_ratio":{{ visible_ratio }},"orientation":"{{ settings.orientation }}","before_label":"{{ settings.before_label }}","after_label":"{{ settings.after_label }}","slider_on_hover":{{ slider_on_hover }},"slider_with_handle":{{ slider_with_handle }},"slider_with_click":{{ slider_with_click }} }'>
			<# if ( settings.before_image.url != '' ) { #>
				<div class="pp-before-image">
					<#
					var before_image = {
						id: settings.before_image.id,
						url: settings.before_image.url,
						size: settings.before_image_size,
						dimension: settings.before_image_custom_dimension,
						model: view.getEditModel()
					};
					var before_image_url = elementor.imagesManager.getImageUrl( before_image );
					#>
					<img src="{{ _.escape( before_image_url ) }}" alt="{{ before_name }}" class="pp-before-img">
				</div>
			<# } #>

			<# if ( settings.after_image.url != '' ) { #>
				<div class="pp-after-image">
					<#
					var after_image = {
						id: settings.after_image.id,
						url: settings.after_image.url,
						size: settings.after_image_size,
						dimension: settings.after_image_custom_dimension,
						model: view.getEditModel()
					};
					var after_image_url = elementor.imagesManager.getImageUrl( after_image );
					#>
					<img src="{{ _.escape( after_image_url ) }}" alt="{{ after_name }}" class="pp-after-img">
				</div>
			<# } #>

			<div class="pp-comparison-handle" role="slider" tabindex="0" aria-label="<?php echo esc_attr__( 'Image comparison slider', 'powerpack-lite-for-elementor' ); ?>" aria-orientation="{{ is_vertical ? 'vertical' : 'horizontal' }}" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ ratio_pct }}" aria-valuetext="{{ value_text.replace( '{percent}', ratio_pct ) }}" data-value-text="{{ value_text }}">
				<#
				if ( settings.handle_icon.value ) {
					if ( 'horizontal' === settings.orientation ) {
						var after_icon = settings.handle_icon.value;
						var before_icon = after_icon.replace('right', 'left');
					} else {
						var afterIcon = settings.handle_icon.value;
						var after_icon = afterIcon.replace('right', 'down');
						var before_icon = afterIcon.replace('right', 'up');
					}
					#>
					<i class="{{ before_icon }}"></i>
					<i class="{{ after_icon }}"></i>
					<#
				}
				#>
			</div>

			<# if ( 'yes' === settings.overlay ) { #>
				<div class="pp-image-comparison-overlay">
			<# } #>
				<# if ( settings.before_label != '' ) { #>
					<div class="pp-comparison-label pp-comparison-label-before" aria-hidden="true">
						<span>{{ settings.before_label }}</span>
					</div>
				<# } #>

				<# if ( settings.after_label != '' ) { #>
					<div class="pp-comparison-label pp-comparison-label-after" aria-hidden="true">
						<span>{{ settings.after_label }}</span>
					</div>
				<# } #>
			<# if ( 'yes' === settings.overlay ) { #>
				</div>
			<# } #>
		</div>
		<?php
	}
}
