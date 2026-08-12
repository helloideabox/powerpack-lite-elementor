<?php
/**
 * Progress Bar Widget
 *
 * @package PPE
 */

namespace PowerpackElementsLite\Modules\ProgressBar\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Progress Bar Widget
 */
class Progress_Bar extends Powerpack_Widget {

	/**
	 * Retrieve progress bar widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Progress_Bar' );
	}

	/**
	 * Retrieve progress bar widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Progress_Bar' );
	}

	/**
	 * Retrieve progress bar widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Progress_Bar' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Progress_Bar' );
	}

	/**
	 * Whether the widget renders dynamic content.
	 *
	 * Returning true prevents Elementor from caching the rendered markup so
	 * that dynamic-tag values (e.g. label, percentage) re-evaluate per request.
	 *
	 * @since 2.8.0
	 * @access protected
	 *
	 * @return bool
	 */
	protected function is_dynamic_content(): bool {
		return true;
	}

	/**
	 * Retrieve the list of scripts the progress bar widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [
			'pp-progress-bar',
		];
	}

	/**
	 * Retrieve the list of styles the progress bar widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		return [
			'widget-pp-progress-bar'
		];
	}

	/**
	 * Whether the widget renders an inner wrapper around its content.
	 *
	 * Disabled when Elementor's "Optimized Markup" experiment is active so
	 * the widget output stays compatible with that experiment.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @return bool
	 */
	public function has_widget_inner_wrapper(): bool {
		return ! PP_Helper::is_feature_active( 'e_optimized_markup' );
	}

	/**
	 * Register progress bar widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.8.0
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_progress_controls();

		/* Style Tab */
		$this->register_style_progress_controls();
		$this->register_style_labels_controls();
		$this->register_style_indicator_controls();
		$this->register_style_prefix_suffix_controls();
	}

	/**
	 * Register progress bar controls in the Content tab.
	 *
	 * @since 2.8.0
	 * @access protected
	 *
	 * @return void
	 */
	protected function register_content_progress_controls() {
		/**
		 * Content Tab: Progress Bar
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_progress',
			[
				'label' => esc_html__( 'Progress Bar', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'type',
			[
				'label'              => esc_html__( 'Type', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'line'        => esc_html__( 'Horizontal Line', 'powerpack-lite-for-elementor' ),
					'vertical'    => esc_html__( 'Vertical Line', 'powerpack-lite-for-elementor' ),
					'circle'      => esc_html__( 'Circle', 'powerpack-lite-for-elementor' ),
					'circle_half' => esc_html__( 'Half Circle', 'powerpack-lite-for-elementor' ),
					//'dots'        => esc_html__( 'Dots', 'powerpack-lite-for-elementor' ),
				],
				'default'            => 'line',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'dot_size',
			[
				'label'              => esc_html__( 'Dots Size', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min' => 1,
						'max' => 60,
					],
				],
				'default'            => [
					'size' => 25,
					'unit' => 'px',
				],
				'render_type'        => 'template',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .pp-progress-segment' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				],
				'condition'          => [
					'type' => 'dots',
				],
			]
		);

		$this->add_responsive_control(
			'dot_spacing',
			[
				'label'              => esc_html__( 'Spacing', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'default'            => [
					'size' => 8,
					'unit' => 'px',
				],
				'render_type'        => 'template',
				'frontend_available' => true,
				'selectors'          => [
					'{{WRAPPER}} .pp-progress-segment:not(:first-child):not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 ); margin-left: calc( {{SIZE}}{{UNIT}}/2 )',
					'{{WRAPPER}} .pp-progress-segment:first-child' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 )',
					'{{WRAPPER}} .pp-progress-segment:last-child' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 )',
				],
				'condition'          => [
					'type' => 'dots',
				],
			]
		);

		$this->add_control(
			'hr',
			[
				'type'      => Controls_Manager::DIVIDER,
				'condition' => [
					'type' => 'dots',
				],
			]
		);

		$this->add_control(
			'labels_type',
			[
				'label'     => esc_html__( 'Labels Type', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'single'   => esc_html__( 'Single', 'powerpack-lite-for-elementor' ),
					'multiple' => esc_html__( 'Multiple', 'powerpack-lite-for-elementor' ),
				],
				'default'   => 'single',
				'condition' => [
					'type!' => [ 'circle', 'circle_half' ],
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'                => esc_html__( 'Alignment', 'powerpack-lite-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => '',
				'options'              => [
					'left'   => [
						'title' => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors_dictionary' => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
				'selectors'            => [
					'{{WRAPPER}} .pp-progress-bar-container' => 'justify-content: {{VALUE}};',
				],
				'condition'            => [
					'labels_type!' => 'multiple',
					'type'         => [ 'vertical', 'circle', 'circle_half' ],
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			[
				'label'       => esc_html__( 'Label', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'label_block' => true,
				'placeholder' => __( 'Label', 'powerpack-lite-for-elementor' ),
				'default'     => __( 'Label', 'powerpack-lite-for-elementor' ),
			]
		);

		$repeater->add_control(
			'number',
			[
				'label'   => esc_html__( 'Percentage', 'powerpack-lite-for-elementor' ),
				'dynamic' => [
					'active' => true,
				],
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 100,
				'default' => 50,
			]
		);

		$this->add_control(
			'labels',
			[
				'label'       => esc_html__( 'Label', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => [
					[
						'text'   => __( 'Label', 'powerpack-lite-for-elementor' ),
						'number' => 50,
					],
				],
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ text + " - " + number + "%" }}}',
				'condition'   => [
					'labels_type' => 'multiple',
					'type!'       => [ 'circle', 'circle_half' ],
				],
			]
		);

		$this->add_control(
			'display_percentage_labels',
			[
				'label'       => esc_html__( 'Display Labels Percentage', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'condition'   => [
					'labels_type' => 'multiple',
					'type!'       => [ 'circle', 'circle_half' ],
				],
			]
		);

		$this->add_control(
			'labels_indicator',
			[
				'label'     => esc_html__( 'Labels Indicator', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'line_pin',
				'options'   => [
					''         => esc_html__( 'None', 'powerpack-lite-for-elementor' ),
					'line_pin' => esc_html__( 'Pin', 'powerpack-lite-for-elementor' ),
					'arrow'    => esc_html__( 'Arrow', 'powerpack-lite-for-elementor' ),
				],
				'condition' => [
					'labels_type' => 'multiple',
					'type!'       => [ 'circle', 'circle_half' ],
				],
			]
		);

		$this->add_control(
			'labels_align',
			[
				'label'     => esc_html__( 'Labels Alignment', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'condition' => [
					'labels_type' => 'multiple',
					'type!'       => [ 'circle', 'circle_half', 'vertical' ],
				],
			]
		);

		$this->add_control(
			'bar_label',
			[
				'label'       => esc_html__( 'Label', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Web design', 'powerpack-lite-for-elementor' ),
				'label_block' => true,
				'conditions'  => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'labels_type',
							'operator' => '==',
							'value'    => 'single',
						],
						[
							'name'     => 'type',
							'operator' => 'in',
							'value'    => [ 'circle', 'circle_half' ],
						],
					],
				],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'      => esc_html__( 'Label HTML Tag', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SELECT,
				'options'    => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default'    => 'span',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'labels_type',
							'operator' => '==',
							'value'    => 'single',
						],
						[
							'name'     => 'type',
							'operator' => 'in',
							'value'    => [ 'circle', 'circle_half' ],
						],
					],
				],
			]
		);

		$this->add_control(
			'percentage',
			[
				'label'       => esc_html__( 'Percentage', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'dynamic'     => [
					'active' => true,
				],
				'min'         => 0,
				'max'         => 100,
				'default'     => 80,
			]
		);

		$this->add_control(
			'display_percentage',
			[
				'label'        => esc_html__( 'Display Percentage', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'powerpack-lite-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'powerpack-lite-for-elementor' ),
				'default'      => 'yes',
				'conditions'   => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'labels_type',
							'operator' => '==',
							'value'    => 'single',
						],
						[
							'name'     => 'type',
							'operator' => 'in',
							'value'    => [ 'circle', 'circle_half' ],
						],
					],
				],
			]
		);

		$this->add_control(
			'percentage_position',
			[
				'label'     => esc_html__( 'Percentage Position', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => [
					'before' => esc_html__( 'Before', 'powerpack-lite-for-elementor' ),
					'after'  => esc_html__( 'After', 'powerpack-lite-for-elementor' ),
				],
				'conditions'   => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'display_percentage',
							'operator' => '==',
							'value'    => 'yes',
						],
						[
							'relation' => 'or',
							'terms'    => [
								[
									'relation' => 'and',
									'terms'    => [
										[
											'name'     => 'labels_type',
											'operator' => '==',
											'value'    => 'single',
										],
										[
											'name'     => 'type',
											'operator' => '==',
											'value'    => 'vertical',
										],
									],
								],
								[
									'name'     => 'type',
									'operator' => 'in',
									'value'    => [ 'circle', 'circle_half' ],
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'bar_style',
			[
				'label'     => esc_html__( 'Style', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => [
					'solid'    => esc_html__( 'Solid', 'powerpack-lite-for-elementor' ),
					'striped'  => esc_html__( 'Striped', 'powerpack-lite-for-elementor' ),
					'gradient' => esc_html__( 'Animated Gradient', 'powerpack-lite-for-elementor' ),
					'rainbow'  => esc_html__( 'Rainbow', 'powerpack-lite-for-elementor' ),
				],
				'condition' => [
					'type' => [ 'line', 'vertical' ],
				],
			]
		);

		$this->add_control(
			'striped_animation',
			[
				'label'        => esc_html__( 'Animated', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => [
					'bar_style' => 'striped',
					'type'      => [ 'line', 'vertical' ],
				],
			]
		);

		$repeater_gradient = new Repeater();

		$repeater_gradient->add_control(
			'gradient_color',
			[
				'label'   => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
			]
		);

		$this->add_control(
			'gradient_colors',
			[
				'label'              => esc_html__( 'Gradient Colors', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::REPEATER,
				'default'            => [
					[ 'gradient_color' => '#6EC1E4' ],
					[ 'gradient_color' => '#54595F' ],
				],
				'fields'             => $repeater_gradient->get_controls(),
				'title_field'        => '{{{ gradient_color }}}',
				'frontend_available' => true,
				'condition'          => [
					'bar_style' => 'gradient',
					'type'      => [ 'line', 'vertical' ],
				],
			]
		);

		$this->add_control(
			'show_suffix',
			[
				'label'        => esc_html__( 'Show Prefix/Suffix', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'powerpack-lite-for-elementor' ),
				'label_off'    => esc_html__( 'Hide', 'powerpack-lite-for-elementor' ),
				'return_value' => 'show',
				'default'      => 'show',
				'condition'    => [
					'type' => 'circle_half',
				],
			]
		);

		$this->add_control(
			'half_circle_prefix',
			[
				'label'     => esc_html__( 'Prefix Label', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => __( '0%', 'powerpack-lite-for-elementor' ),
				'condition' => [
					'type'        => 'circle_half',
					'show_suffix' => 'show',
				],
			]
		);

		$this->add_control(
			'half_circle_suffix',
			[
				'label'     => esc_html__( 'Suffix Label', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => __( '100%', 'powerpack-lite-for-elementor' ),
				'condition' => [
					'type'        => 'circle_half',
					'show_suffix' => 'show',
				],
			]
		);

		$this->add_control(
			'bar_speed',
			[
				'label'              => esc_html__( 'Speed (ms)', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 1500,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register progress bar controls in the Style tab.
	 *
	 * @since 2.8.0
	 * @access protected
	 *
	 * @return void
	 */
	protected function register_style_progress_controls() {
		/**
		 * Style Tab: Progress Bar
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_progress_style',
			[
				'label' => esc_html__( 'Progress Bar', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'bar_size',
			[
				'label'      => esc_html__( 'Thickness', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'    => [
					'size' => 30,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--pp-bar-thickness: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'type' => [ 'line', 'vertical' ],
				],
			]
		);

		$this->add_control(
			'vertical_bar_height',
			[
				'label'      => esc_html__( 'Height', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 500,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 200,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-vertical-bar' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'type' => 'vertical',
				],
			]
		);

		$this->add_control(
			'bar_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'    => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--pp-bar-radius: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'type' => [ 'line', 'vertical' ],
				],
			]
		);

		$this->add_control(
			'circle_size',
			[
				'label'      => esc_html__( 'Size', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 50,
						'max'  => 500,
						'step' => 1,
					],
				],
				'default'    => [
					'size' => 200,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}}' => '--pp-circle-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'type' => [ 'circle', 'circle_half' ],
				],
			]
		);

		$this->add_control(
			'circle_border_width',
			[
				'label'     => esc_html__( 'Border Width', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--pp-circle-border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'type' => [ 'circle', 'circle_half' ],
				],
			]
		);

		$this->add_control(
			'background_color_title',
			[
				'label'     => esc_html__( 'Background', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'bar_bg_color',
				'types'     => [ 'classic', 'gradient' ],
				'exclude'   => [ 'image' ],
				'selector'  => '{{WRAPPER}} .pp-progress-line, {{WRAPPER}} .pp-vertical-bar',
				'condition' => [
					'type' => [ 'line', 'vertical' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'dots_bg_color',
				'types'     => [ 'classic', 'gradient' ],
				'exclude'   => [ 'image' ],
				'selector'  => '.pp-progress-dots .pp-progress-segment',
				'condition' => [
					'type' => [ 'dots' ],
				],
			]
		);

		$this->add_control(
			'circle_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--pp-circle-track-color: {{VALUE}};',
				],
				'condition' => [
					'type' => [ 'circle', 'circle_half' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'bar_border',
				'label'     => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
				'selector'  => '{{WRAPPER}} .pp-progress-line, {{WRAPPER}} .pp-vertical-bar',
				'condition' => [
					'type' => [ 'line', 'vertical' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'bar_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-progress-line, {{WRAPPER}} .pp-vertical-bar',
				'condition' => [
					'type' => [ 'line', 'vertical' ],
				],
			]
		);

		$this->add_responsive_control(
			'bar_padding',
			[
				'label'      => esc_html__( 'Padding', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-progress-line, {{WRAPPER}} .pp-vertical-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'type' => [ 'line', 'vertical' ],
				],
			]
		);

		$this->add_control(
			'fill_title',
			[
				'label'     => esc_html__( 'Fill', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'bar_style!' => [ 'gradient', 'rainbow' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'bar_color',
				'types'     => [ 'classic', 'gradient' ],
				'exclude'   => [ 'image' ],
				'selector'  => '{{WRAPPER}} .pp-progress-fill',
				'condition' => [
					'type' => [ 'line', 'vertical' ],
					'bar_style!' => [ 'gradient', 'rainbow' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'dots_color',
				'types'     => [ 'classic', 'gradient' ],
				'exclude'   => [ 'image' ],
				'selector'  => '{{WRAPPER}} .pp-progress-dots .segment-inner',
				'condition' => [
					'type' => [ 'dots' ],
				],
			]
		);

		$this->add_control(
			'circle_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--pp-circle-fill-color: {{VALUE}};',
				],
				'condition' => [
					'type' => [ 'circle', 'circle_half' ],
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register label controls in the Style tab.
	 *
	 * @since 2.8.0
	 * @access protected
	 *
	 * @return void
	 */
	protected function register_style_labels_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Labels', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'label_spacing',
			[
				'label'       => esc_html__( 'Spacing', 'powerpack-lite-for-elementor' ),
				'description' => esc_html__( 'Spacing between label, percentage and bar', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'     => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors'   => [
					'{{WRAPPER}}' => '--pp-label-spacing: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'after',
				'condition'  => [
					'labels_type!' => 'multiple',
					'type'         => [ 'line', 'vertical' ],
				],
			]
		);

		$this->add_control(
			'label_heading_style',
			[
				'label' => esc_html__( 'Label', 'powerpack-lite-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--pp-label-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .pp-progress-label, {{WRAPPER}} .pp-bar-center-label',
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'title_shadow',
				'selector' => '{{WRAPPER}} .pp-progress-label, {{WRAPPER}} .pp-bar-center-label',
			]
		);

		$this->add_control(
			'percentage_heading_style',
			[
				'label'      => esc_html__( 'Percentage', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::HEADING,
				'separator'  => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'display_percentage',
							'operator' => '==',
							'value'    => 'yes',
						],
						[
							'name'     => 'display_percentage_labels',
							'operator' => '==',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'bar_counter_color',
			[
				'label'      => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}}' => '--pp-count-color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'display_percentage',
							'operator' => '==',
							'value'    => 'yes',
						],
						[
							'name'     => 'display_percentage_labels',
							'operator' => '==',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'       => 'bar_counter_typography',
				'selector'   => '{{WRAPPER}} .pp-progress-count, {{WRAPPER}} .pp-bar-label-percentage',
				'exclude'    => [
					'line_height',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'display_percentage',
							'operator' => '==',
							'value'    => 'yes',
						],
						[
							'name'     => 'display_percentage_labels',
							'operator' => '==',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'       => 'bar_counter_shadow',
				'selector'   => '{{WRAPPER}} .pp-progress-count, {{WRAPPER}} .pp-bar-label-percentage',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'display_percentage',
							'operator' => '==',
							'value'    => 'yes',
						],
						[
							'name'     => 'display_percentage_labels',
							'operator' => '==',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register prefix/suffix controls in the Style tab.
	 *
	 * @since 2.8.0
	 * @access protected
	 *
	 * @return void
	 */
	protected function register_style_prefix_suffix_controls() {
		$this->start_controls_section(
			'section_suffix',
			[
				'label'     => esc_html__( 'Prefix/Suffix', 'powerpack-lite-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'type'        => 'circle_half',
					'show_suffix' => 'show',
				],
			]
		);

		$this->add_control(
			'bar_suffix_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => '--pp-suffix-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'bar_suffix_typography',
				'selector' => '{{WRAPPER}} .pp-progress-bar-hf-label-left, {{WRAPPER}} .pp-progress-bar-hf-label-right',
				'exclude'  => [
					'line_height',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'bar_suffix_shadow',
				'selector' => '{{WRAPPER}} .pp-progress-bar-hf-label-left, {{WRAPPER}} .pp-progress-bar-hf-label-right',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register indicator controls in the Style tab.
	 *
	 * @since 2.8.0
	 * @access protected
	 *
	 * @return void
	 */
	protected function register_style_indicator_controls() {
		$this->start_controls_section(
			'labels_indicator_section',
			[
				'label'     => esc_html__( 'Indicator', 'powerpack-lite-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'labels_type'       => 'multiple',
					'labels_indicator!' => '',
				],
			]
		);

		$this->add_control(
			'indicator_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => [
					'{{WRAPPER}}' => '--pp-indicator-color: {{VALUE}};',
				],
				'condition' => [
					'labels_indicator!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'indicator_pin_size',
			[
				'label'      => esc_html__( 'Size', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--pp-indicator-pin-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'labels_indicator' => 'line_pin',
				],
			]
		);

		$this->add_responsive_control(
			'indicator_arrow_size',
			[
				'label'      => esc_html__( 'Size', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}}' => '--pp-indicator-arrow-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'labels_indicator' => 'arrow',
				],
			]
		);

		$this->add_responsive_control(
			'indicator_pin_height',
			[
				'label'      => esc_html__( 'Height', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-bar-label-pin' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'labels_indicator' => 'line_pin',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the progress bar wrapper, label, percentage and inner markup.
	 *
	 * @since 2.8.0
	 * @access protected
	 *
	 * @return void
	 */
	protected function render_progress_bar() {
		$settings   = $this->get_settings_for_display();
		$type       = ! empty( $settings['type'] ) ? $settings['type'] : 'line';
		$title      = ! empty( $settings['bar_label'] ) ? $settings['bar_label'] : '';
		$percentage = is_numeric( $settings['percentage'] ) ? max( 0, min( 100, (int) $settings['percentage'] ) ) : 0;
		$style      = ! empty( $settings['bar_style'] ) ? $settings['bar_style'] : 'solid';
		$show_count = 'yes' === $settings['display_percentage'];
		$count_pos  = 'after' === $settings['percentage_position'] ? 'after' : 'before';

		$bar_setting_key = 'bar_wrapper';

		$this->add_render_attribute(
			$bar_setting_key,
			[
				'class'         => 'pp-progress-bar-wrapper',
				'role'          => 'progressbar',
				'aria-valuemin' => '0',
				'aria-valuemax' => '100',
				'aria-valuenow' => $percentage,
				'data-value'    => $percentage,
			]
		);

		if ( 'striped' === $style ) {
			$this->add_render_attribute( $bar_setting_key, 'class', 'pp-progress-bar-striped' );

			if ( 'yes' === $settings['striped_animation'] ) {
				$this->add_render_attribute( $bar_setting_key, 'class', 'pp-progress-bar-active' );
			}
		} elseif ( 'gradient' === $style ) {
			$this->add_render_attribute( $bar_setting_key, 'class', 'pp-progress-bar-gradient' );
		} elseif ( 'rainbow' === $style ) {
			$this->add_render_attribute( $bar_setting_key, 'class', 'pp-progress-bar-rainbow' );
		}

		?>
		<div <?php $this->print_render_attribute_string( $bar_setting_key ); ?>>
			<?php if ( 'single' === $settings['labels_type'] && ( ( 'line' === $type || 'dots' === $type ) && ! Utils::is_empty( $title ) ) ) : ?>
				<<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> class="pp-progress-label">
					<?php echo esc_html( $title ); ?>
				</<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
			<?php endif; ?>
			<?php
			if (
				$show_count &&
				'single' === $settings['labels_type'] &&
				(
					'line' === $type ||
					( 'vertical' === $type && 'before' === $count_pos )
				)
			) {
				?>
				<div class="pp-progress-count">0%</div>
				<?php
			}

			if ( 'multiple' === $settings['labels_type'] && ( 'line' === $type || 'vertical' === $type || 'dots' === $type ) ) {
				echo $this->render_labels();
			}

			switch ( $type ) :
				case 'line': ?>
					<div class="pp-progress-line">
						<div class="pp-progress-fill"></div>
					</div>
				<?php break;

				case 'circle': ?>
					<div class="pp-bar-circle-wrapper">
						<div class="pp-bar-circle">
							<div class="pp-progress-fill pp-progress-fill-left"></div>
							<div class="pp-progress-fill pp-progress-fill-right"></div>
						</div>
						<div class="pp-bar-circle-inner"></div>
						<div class="pp-bar-circle-content">
							<?php $this->render_circle_label_and_count( $settings, $title, $show_count, $count_pos ); ?>
						</div>
					</div>
				<?php break;

				case 'circle_half': ?>
					<div class="pp-progress-circle-half">
						<div class="pp-bar-circle-wrapper">
							<div class="pp-bar-circle">
								<div class="pp-progress-fill pp-progress-fill-left"></div>
							</div>
							<div class="pp-bar-circle-inner"></div>
						</div>
						<div class="pp-bar-circle-content">
							<?php $this->render_circle_label_and_count( $settings, $title, $show_count, $count_pos ); ?>
						</div>
					</div>
					<?php if ( 'show' === $settings['show_suffix'] ) { ?>
						<div class="pp-progress-bar-hf-labels">
							<span class="pp-progress-bar-hf-label-left">
								<?php echo esc_html( $settings['half_circle_prefix'] ); ?>
							</span>
							<span class="pp-progress-bar-hf-label-right">
								<?php echo esc_html( $settings['half_circle_suffix'] ); ?>
							</span>
						</div>
					<?php } ?>
				<?php break;

				case 'vertical': ?>
					<div class="pp-vertical-bar">
						<div class="pp-progress-fill"></div>
					</div>
				<?php break;

				case 'dots': ?>
					<div class="pp-progress-dots">
						<div class="pp-progress-fill"></div>
					</div>
				<?php break;
			endswitch;

			if (
				$show_count &&
				'vertical' === $type &&
				'single' === $settings['labels_type'] &&
				'after' === $count_pos
			) {
				?>
				<div class="pp-progress-count">0%</div>
				<?php
			} ?>

			<?php if ( 'single' === $settings['labels_type'] && 'vertical' === $type && ! Utils::is_empty( $title ) ) : ?>
				<<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> class="pp-progress-label">
					<?php echo esc_html( $title ); ?>
				</<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Render the percentage count and label inside a circle / half-circle.
	 *
	 * Output order honours the "Percentage Position" control: when set to
	 * "after", the label is rendered before the percentage; otherwise the
	 * percentage is rendered first.
	 *
	 * @since 3.0.0
	 * @access protected
	 *
	 * @param array  $settings   Widget settings.
	 * @param string $title      Bar label text.
	 * @param bool   $show_count Whether to render the percentage count.
	 * @param string $count_pos  Position of the percentage relative to the label.
	 */
	protected function render_circle_label_and_count( $settings, $title, $show_count, $count_pos ) {
		$has_label = ! Utils::is_empty( $title );

		if ( 'after' === $count_pos && $has_label ) {
			?>
			<<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> class="pp-progress-label">
				<?php echo esc_html( $title ); ?>
			</<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
			<?php
		}

		if ( $show_count ) {
			?>
			<div class="pp-progress-count">0%</div>
			<?php
		}

		if ( 'after' !== $count_pos && $has_label ) {
			?>
			<<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> class="pp-progress-label">
				<?php echo esc_html( $title ); ?>
			</<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
			<?php
		}
	}

	/**
	 * Build the markup for the multi-label list rendered alongside the bar.
	 *
	 * @since 2.8.0
	 * @access protected
	 *
	 * @return string Rendered labels markup, or an empty string when no labels are configured.
	 */
	protected function render_labels() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['labels'] ) ) {
			return '';
		}

		$indicator = 'none';

		if ( 'arrow' === $settings['labels_indicator'] ) {
			$indicator = 'arrow';
		} elseif ( 'line_pin' === $settings['labels_indicator'] ) {
			$indicator = 'pin';
		}

		ob_start();
		?>
		<div class="pp-bar-container-label pp-bar-indicator-<?php echo esc_attr( $indicator ); ?> pp-bar-indicator-align-<?php echo esc_attr( $settings['labels_align'] ); ?>">
			<?php
			$direction = is_rtl() ? 'right' : 'left';

			foreach ( $settings['labels'] as $item ) {
				$number            = is_numeric( $item['number'] ) ? max( 0, min( 100, (int) $item['number'] ) ) : 0;
				$text              = esc_html( $item['text'] );
				$number_percentage = esc_attr( $number . '%' );

				if ( 'vertical' === $settings['type'] ) {
					$direction_style = 'top:' . (100 - $number) . '%;';
				} else {
					$direction_style = esc_attr( $direction . ':' . $number . '%;' );
				}

				$indicator_markup = $this->get_indicator_markup( $settings['labels_indicator'] );

				$label_content = '<p class="pp-bar-center-label">' . $text;

				if ( 'yes' === $settings['display_percentage_labels'] ) {
					$label_content .= ' <span class="pp-bar-label-percentage">' . $number_percentage . '</span>';
				}

				$label_content .= '</p>';

				echo '<div class="pp-bar-label" style="' . esc_attr( $direction_style ) . '">' . $label_content . $indicator_markup . '</div>';
			}
			?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get the markup for the label indicator.
	 *
	 * @since 2.8.0
	 * @access private
	 *
	 * @param string $indicator The type of label indicator.
	 * @return string The markup for the label indicator.
	 */
	private function get_indicator_markup( $indicator ) {
		switch ( $indicator ) {
			case 'arrow':
				return '<p class="pp-bar-label-arrow"></p>';
			case 'line_pin':
				return '<p class="pp-bar-label-pin"></p>';
			default:
				return '';
		}
	}

	/**
	 * Render progress widget output on the frontend.
	 * Make sure value does not exceed 100%.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 2.8.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$type     = ( $settings['type'] ) ?? 'line';

		$this->add_render_attribute( 'container', 'class', [ 'pp-progress-bar-container', 'pp-progress-bar-' . esc_attr( $type ) ] );

		?>
		<div <?php $this->print_render_attribute_string( 'container' ); ?>>
			<?php $this->render_progress_bar(); ?>
		</div>
		<?php
	}
}
