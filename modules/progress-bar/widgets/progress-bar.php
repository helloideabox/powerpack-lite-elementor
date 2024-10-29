<?php
/**
 * Progress Bar Widget
 *
 * @package PPE
 */

namespace PowerpackElementsLite\Modules\ProgressBar\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Modules\DynamicTags\Module as TagsModule;

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

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the timeline widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'pp-progress-bar',
		);
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
		return [
			'widget-pp-progress-bar'
		];
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
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_progress_controls();
		$this->register_style_labels_controls();
		$this->register_style_indicator_controls();
		$this->register_style_prefix_suffix_controls();
	}

	/**
	 * Register progress bar controls in Content tab
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
				'label' => esc_html__( 'Progress Bar', 'powerpack' ),
			]
		);

		$this->add_control(
			'type',
			[
				'label'              => esc_html__( 'Type', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => [
					'line'        => esc_html__( 'Horizontal Line', 'powerpack' ),
					'vertical'    => esc_html__( 'Vertical Line', 'powerpack' ),
					'circle'      => esc_html__( 'Circle', 'powerpack' ),
					'circle_half' => esc_html__( 'Half Circle', 'powerpack' ),
					//'dots'        => esc_html__( 'Dots', 'powerpack' ),
				],
				'default'            => 'line',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'dot_size',
			array(
				'label'              => __( 'Dots Size', 'powerpack' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min' => 1,
						'max' => 60,
					),
				),
				'default'            => array(
					'size' => 25,
					'unit' => 'px',
				),
				'render_type'        => 'template',
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}} .pp-progress-segment' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
				),
				'condition'          => array(
					'type' => 'dots',
				),
			)
		);

		$this->add_responsive_control(
			'dot_spacing',
			array(
				'label'              => __( 'Spacing', 'powerpack' ),
				'type'               => Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
					),
				),
				'default'            => array(
					'size' => 8,
					'unit' => 'px',
				),
				'render_type'        => 'template',
				'frontend_available' => true,
				'selectors'          => array(
					'{{WRAPPER}} .pp-progress-segment:not(:first-child):not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 ); margin-left: calc( {{SIZE}}{{UNIT}}/2 )',
					'{{WRAPPER}} .pp-progress-segment:first-child' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 )',
					'{{WRAPPER}} .pp-progress-segment:last-child' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 )',
				),
				'condition'          => array(
					'type' => 'dots',
				),
			)
		);

		$this->add_control(
			'hr',
			[
				'type'      => Controls_Manager::DIVIDER,
				'condition' => array(
					'type' => 'dots',
				),
			]
		);

		$this->add_control(
			'labels_type',
			[
				'label'     => esc_html__( 'Labels Type', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'single'   => __( 'Single', 'powerpack' ),
					'multiple' => __( 'Multiple', 'powerpack' ),
				],
				'default'   => 'single',
				'condition' => array(
					'type!' => [ 'circle', 'circle_half' ],
				),
			]
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'                => __( 'Alignment', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => '',
				'options'              => array(
					'left'   => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors_dictionary' => array(
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-progress-bar-container' => 'justify-content: {{VALUE}};',
				),
				'condition'            => array(
					'labels_type!' => 'multiple',
					'type'         => [ 'vertical', 'circle', 'circle_half' ],
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'       => __( 'Label', 'powerpack' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'label_block' => true,
				'placeholder' => __( 'label', 'powerpack' ),
				'default'     => __( 'label', 'powerpack' ),
			)
		);

		$repeater->add_control(
			'number',
			array(
				'label'   => __( 'Percentage', 'powerpack' ),
				'dynamic' => [
					'active' => true,
				],
				'type'    => Controls_Manager::NUMBER,
				'default' => 50,
			)
		);

		$this->add_control(
			'labels',
			array(
				'label'     => __( 'Label', 'powerpack' ),
				'type'      => Controls_Manager::REPEATER,
				'default'   => array(
					array(
						'text'   => __( 'Label', 'powerpack' ),
						'number' => 50,
					),
				),
				'fields'    => $repeater->get_controls(),
				'condition' => array(
					'labels_type' => 'multiple',
					'type!'       => [ 'circle', 'circle_half' ],
				),
			)
		);

		$this->add_control(
			'display_percentage_labels',
			array(
				'label'       => esc_html__( 'Display Labels Percentage', 'powerpack' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => 'yes',
				'condition'   => array(
					'labels_type' => 'multiple',
					'type!'       => [ 'circle', 'circle_half' ],
				),
			)
		);

		$this->add_control(
			'labels_indicator',
			array(
				'label'     => __( 'Labels Indicator', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'line_pin',
				'options'   => array(
					''         => __( 'None', 'powerpack' ),
					'line_pin' => __( 'Pin', 'powerpack' ),
					'arrow'    => __( 'Arrow', 'powerpack' ),
				),
				'condition' => array(
					'labels_type' => 'multiple',
					'type!'       => [ 'circle', 'circle_half' ],
				),
			)
		);

		$this->add_control(
			'labels_align',
			array(
				'label'     => __( 'Labels Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'condition' => array(
					'labels_type' => 'multiple',
					'type!'       => [ 'circle', 'circle_half', 'vertical' ],
				),
			)
		);

		$this->add_control(
			'bar_label',
			[
				'label'       => __( 'Label', 'powerpack' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => __( 'Web design', 'powerpack' ),
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
				'label'      => esc_html__( 'Label HTML Tag', 'powerpack' ),
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
				'label'       => esc_html__( 'Percentage', 'powerpack' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => 80,
			]
		);

		$this->add_control(
			'display_percentage',
			[
				'label'        => esc_html__( 'Display Percentage', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'powerpack' ),
				'label_off'    => esc_html__( 'Hide', 'powerpack' ),
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
			array(
				'label'     => __( 'Percentage Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => array(
					'before' => __( 'Before', 'powerpack' ),
					'after'  => __( 'After', 'powerpack' ),
				),
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
			)
		);

		$this->add_control(
			'bar_style',
			array(
				'label'     => __( 'Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'    => __( 'Solid', 'powerpack' ),
					'striped'  => __( 'Striped', 'powerpack' ),
					'gradient' => __( 'Animated Gradient', 'powerpack' ),
					'rainbow'  => __( 'Rainbow', 'powerpack' ),
				),
				'condition' => array(
					'type' => [ 'line', 'vertical' ],
				),
			)
		);

		$this->add_control(
			'striped_animation',
			array(
				'label'     => __( 'Animated', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					'bar_style' => 'striped',
					'type'      => [ 'line', 'vertical' ],
				),
			)
		);

		$repeater_gradient = new Repeater();

		$repeater_gradient->add_control(
			'gradient_color',
			array(
				'label'   => esc_html__( 'Color', 'powerpack' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
			)
		);

		$this->add_control(
			'gradient_colors',
			array(
				'label'              => __( 'Gradient Colors', 'powerpack' ),
				'type'               => Controls_Manager::REPEATER,
				'default'            => array(
					array( 'gradient_color' => '#6EC1E4' ),
					array( 'gradient_color' => '#54595F' ),
				),
				'fields'             => $repeater_gradient->get_controls(),
				'title_field'        => '{{{ gradient_color }}}',
				'frontend_available' => true,
				'condition'          => array(
					'bar_style' => 'gradient',
					'type'      => [ 'line', 'vertical' ],
				),
			)
		);

		$this->add_control(
			'show_suffix',
			[
				'label'        => esc_html__( 'Show Prefix/Suffix', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'powerpack' ),
				'label_off'    => esc_html__( 'Hide', 'powerpack' ),
				'return_value' => 'show',
				'default'      => 'show',
				'condition'    => array(
					'type'        => 'circle_half',
					'show_suffix' => 'show',
				),
			]
		);

		$this->add_control(
			'half_circle_prefix',
			array(
				'label'     => __( 'Prefix Label', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => __( '0%', 'powerpack' ),
				'condition' => array(
					'type'        => 'circle_half',
					'show_suffix' => 'show',
				),
			)
		);

		$this->add_control(
			'half_circle_suffix',
			array(
				'label'     => __( 'Suffix Label', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => __( '100%', 'powerpack' ),
				'condition' => array(
					'type'        => 'circle_half',
					'show_suffix' => 'show',
				),
			)
		);

		$this->add_control(
			'bar_speed',
			array(
				'label'              => __( 'Speed (ms)', 'powerpack' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 1500,
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Help Docs Controls in Content tab
	 *
	 * @return void
	 */
	protected function register_content_help_docs_controls() {
		$help_docs = PP_Config::get_widget_help_links( 'Progress_Bar' );

		if ( ! empty( $help_docs ) ) {

			/**
			 * Content Tab: Help Docs
			 *
			 * @since 2.8.0
			 * @access protected
			 */
			$this->start_controls_section(
				'section_help_docs',
				array(
					'label' => __( 'Help Docs', 'powerpack' ),
				)
			);

			$hd_counter = 1;
			foreach ( $help_docs as $hd_title => $hd_link ) {
				$this->add_control(
					'help_doc_' . $hd_counter,
					array(
						'type'            => Controls_Manager::RAW_HTML,
						'raw'             => sprintf( '%1$s ' . $hd_title . ' %2$s', '<a href="' . $hd_link . '" target="_blank" rel="noopener">', '</a>' ),
						'content_classes' => 'pp-editor-doc-links',
					)
				);

				$hd_counter++;
			}

			$this->end_controls_section();
		}
	}

	/**
	 * Register progress bar controls in Style tab
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
				'label' => esc_html__( 'Progress Bar', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'bar_size',
			[
				'label'      => esc_html__( 'Thickness', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'    => [
					'size' => 30,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-progress-line' => 'height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-vertical-bar' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => array(
					'type' => [ 'line', 'vertical' ],
				),
			]
		);

		$this->add_control(
			'vertical_bar_height',
			[
				'label'      => esc_html__( 'Height', 'powerpack' ),
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
				'condition'  => array(
					'type' => 'vertical',
				),
			]
		);

		$this->add_control(
			'bar_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'    => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-progress-line, {{WRAPPER}} .pp-progress-fill, {{WRAPPER}} .pp-vertical-bar' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition'  => array(
					'type' => [ 'line', 'vertical' ],
				),
			]
		);

		$this->add_control(
			'circle_size',
			[
				'label'      => esc_html__( 'Size', 'powerpack' ),
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
					'{{WRAPPER}} .pp-bar-circle-wrapper' => 'width: {{SIZE}}px; height: {{SIZE}}px;',
					'{{WRAPPER}} .pp-progress-circle-half' => 'width: {{SIZE}}px; height: calc({{SIZE}} / 2 * 1{{UNIT}});',
					'{{WRAPPER}} .pp-progress-bar-circle_half .pp-bar-circle-content, {{WRAPPER}} .pp-progress-bar-circle_half .pp-progress-bar-hf-labels' => 'width: {{SIZE}}px;',
				],
				'condition'  => array(
					'type' => [ 'circle', 'circle_half' ],
				),
			]
		);

		$this->add_control(
			'circle_border_width',
			array(
				'label'     => __( 'Border Width', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => array(
					'{{WRAPPER}} .pp-progress-fill, {{WRAPPER}} .pp-bar-circle-inner' => 'border-width: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					'type' => [ 'circle', 'circle_half' ],
				),
			)
		);

		$this->add_control(
			'background_color_title',
			array(
				'label'     => __( 'Background', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'bar_bg_color',
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .pp-progress-line, {{WRAPPER}} .pp-vertical-bar',
				'condition' => array(
					'type' => [ 'line', 'vertical' ],
				),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'dots_bg_color',
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '.pp-progress-dots .pp-progress-segment',
				'condition' => array(
					'type' => [ 'dots' ],
				),
			]
		);

		$this->add_control(
			'circle_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-bar-circle-inner' => 'border-color: {{VALUE}};',
				],
				'condition' => array(
					'type' => [ 'circle', 'circle_half' ],
				),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'bar_border',
				'label'     => esc_html__( 'Border', 'powerpack' ),
				'selector'  => '{{WRAPPER}} .pp-progress-line, {{WRAPPER}} .pp-vertical-bar',
				'condition' => array(
					'type' => [ 'line', 'vertical' ],
				),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'bar_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-progress-line, {{WRAPPER}} .pp-vertical-bar',
				'condition' => array(
					'type' => [ 'line', 'vertical' ],
				),
			]
		);

		$this->add_responsive_control(
			'bar_padding',
			[
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', '%', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-progress-line, {{WRAPPER}} .pp-vertical-bar' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} ;',
				],
				'condition'  => array(
					'type' => [ 'line', 'vertical' ],
				),
			]
		);

		$this->add_control(
			'fill_title',
			array(
				'label'     => __( 'Fill', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'bar_style!' => [ 'gradient', 'rainbow' ],
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'bar_color',
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .pp-progress-fill',
				'condition' => array(
					'type' => [ 'line', 'vertical' ],
					'bar_style!' => [ 'gradient', 'rainbow' ],
				),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'dots_color',
				'types'     => array( 'classic', 'gradient' ),
				'exclude'   => array( 'image' ),
				'selector'  => '{{WRAPPER}} .pp-progress-dots .segment-inner',
				'condition' => array(
					'type' => [ 'dots' ],
				),
			]
		);

		$this->add_control(
			'circle_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .pp-bar-circle .pp-progress-fill' => 'border-color: {{VALUE}};',
				],
				'condition' => array(
					'type' => [ 'circle', 'circle_half' ],
				),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register title controls in Style tab
	 *
	 * @return void
	 */
	protected function register_style_labels_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Labels', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'label_spacing',
			array(
				'label'       => __( 'Spacing', 'powerpack' ),
				'description' => __( 'Spacing between label, percentage and bar', 'powerpack' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'     => [
					'size' => 10,
					'unit' => 'px',
				],
				'selectors'   => array(
					'{{WRAPPER}} .pp-progress-bar-line .pp-progress-line' => 'margin-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-progress-bar-vertical .pp-progress-bar-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				),
				'separator'  => 'after',
				'condition'  => array(
					'labels_type!' => 'multiple',
					'type'         => [ 'line', 'vertical' ],
				),
			)
		);

		$this->add_control(
			'label_heading_style',
			array(
				'label' => __( 'Label', 'powerpack' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .pp-progress-label, {{WRAPPER}} .pp-bar-center-label' => 'color: {{VALUE}};',
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
			array(
				'label'      => __( 'Percentage', 'powerpack' ),
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
			)
		);

		$this->add_control(
			'bar_counter_color',
			[
				'label'      => esc_html__( 'Color', 'powerpack' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'{{WRAPPER}} .pp-progress-count, {{WRAPPER}} .pppb-percentage' => 'color: {{VALUE}};',
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
				'selector'   => '{{WRAPPER}} .pp-progress-count, {{WRAPPER}} .pppb-percentage',
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
				'selector'   => '{{WRAPPER}} .pp-progress-count, {{WRAPPER}} .pppb-percentage',
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
	 * Register prefix/suffix controls in Style tab
	 *
	 * @return void
	 */
	protected function register_style_prefix_suffix_controls() {
		$this->start_controls_section(
			'section_suffix',
			[
				'label'     => esc_html__( 'Prefix/Suffix', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'type'        => 'circle_half',
					'show_suffix' => 'show',
				),
			]
		);

		$this->add_control(
			'bar_suffix_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-progress-bar-hf-label-left, {{WRAPPER}} .pp-progress-bar-hf-label-right' => 'color: {{VALUE}};',
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
	 * Register indicator controls in Style tab
	 *
	 * @return void
	 */
	protected function register_style_indicator_controls() {
		$this->start_controls_section(
			'labels_indicator_section',
			array(
				'label'     => __( 'Indicator', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'labels_type'       => 'multiple',
					'labels_indicator!' => '',
				),
			)
		);

		$this->add_control(
			'indicator_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => array(
					'default' => Global_Colors::COLOR_SECONDARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-bar-label-pin' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .pp-bar-label-arrow' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'labels_indicator!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'indicator_pin_size',
			array(
				'label'      => __( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .pp-bar-label-pin' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-bar-label-arrow' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-bar-indicator-pin.pp-bar-indicator-align-left .pp-bar-center-label' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .pp-bar-indicator-pin.pp-bar-indicator-align-right .pp-bar-center-label' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 );',
				),
				'condition'  => array(
					'labels_indicator' => 'line_pin',
				),
			)
		);

		$this->add_responsive_control(
			'indicator_arrow_size',
			array(
				'label'      => __( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .pp-bar-label-arrow' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-bar-indicator-arrow.pp-bar-indicator-align-left .pp-bar-center-label' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-bar-indicator-arrow.pp-bar-indicator-align-right .pp-bar-center-label' => 'margin-left: -{{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'labels_indicator' => 'arrow',
				),
			)
		);

		$this->add_responsive_control(
			'indicator_pin_height',
			array(
				'label'      => __( 'Height', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => array(
					'{{WRAPPER}} .pp-bar-label-pin' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'labels_indicator' => 'line_pin',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render_progress_bar() {
		$settings   = $this->get_settings_for_display();
		$type       = $settings['type'] ?? 'line';
		$title      = ( isset( $settings['bar_label'] ) && ! empty( $settings['bar_label'] ) ) ? esc_html( $settings['bar_label'] ) : '';
		$percentage = ( isset( $settings['percentage'] ) && ! empty( $settings['percentage'] ) ) ? esc_html( $settings['percentage'] ) : '';
		$percentage = is_numeric( $percentage ) ? min( $percentage, 100 ) : 0;
		$style      = ( isset( $settings['bar_style'] ) && ! empty( $settings['bar_style'] ) ) ? esc_html( $settings['bar_style'] ) : 'solid';

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
		} elseif ( 'gradient' === $style ) {
			$this->add_render_attribute( $bar_setting_key, 'class', 'pp-progress-bar-gradient' );
		} elseif ( 'rainbow' === $style ) {
			$this->add_render_attribute( $bar_setting_key, 'class', 'pp-progress-bar-rainbow' );
		}

		if ( 'yes' === $settings['striped_animation'] ) {
			$this->add_render_attribute( $bar_setting_key, 'class', 'pp-progress-bar-active' );
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
				'single' === $settings['labels_type'] && 
				'yes' === $settings['display_percentage'] && 
				(
					('line' === $type) || 
					('vertical' === $type && 'before' === $settings['percentage_position'])
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
							<?php if ( 'yes' === $settings['display_percentage'] ) { ?>
								<div class="pp-progress-count">0%</div>
							<?php } ?>
							<?php if ( ! Utils::is_empty( $title ) ) : ?>
								<<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> class="pp-progress-label">
									<?php echo esc_html( $title ); ?>
								</<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
							<?php endif; ?>
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
							<?php if ( 'yes' === $settings['display_percentage'] ) { ?>
								<div class="pp-progress-count">0%</div>
							<?php } ?>
							<?php if ( ! Utils::is_empty( $title ) ) : ?>
								<<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> class="pp-progress-label">
									<?php echo esc_html( $title ); ?>
								</<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
							<?php endif; ?>
						</div>
					</div>
					<?php if ( 'show' === $settings['show_suffix'] ) { ?>
						<div class="pp-progress-bar-hf-labels">
							<span class="pp-progress-bar-hf-label-left">
								<?php echo wp_kses_post( $settings['half_circle_prefix'] ); ?>
							</span>
							<span class="pp-progress-bar-hf-label-right">
								<?php echo wp_kses_post( $settings['half_circle_suffix'] ); ?>
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
				'vertical' === $type && 
				'single' === $settings['labels_type'] && 
				'yes' === $settings['display_percentage'] && 
				'after' === $settings['percentage_position'] 
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

	protected function render_labels() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['labels'] ) ) {
			return;
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
				$number            = ( ! empty( $item['number'] ) && is_numeric( $item['number'] ) ) ? (int) $item['number'] : 0;
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
					$label_content .= ' <span class="pppb-percentage">' . $number_percentage . '</span>';
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
	 * Make sure value does no exceed 100%.
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
