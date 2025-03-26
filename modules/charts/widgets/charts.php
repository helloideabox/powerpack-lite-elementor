<?php
namespace PowerpackElementsLite\Modules\Charts\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Charts Widget
 */
class Charts extends Powerpack_Widget {

	/**
	 * Retrieve Charts widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Charts' );
	}

	/**
	 * Retrieve Charts widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Charts' );
	}

	/**
	 * Retrieve Charts widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Charts' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the Charts widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Charts' );
	}

	/**
	 * Retrieve the list of scripts the Charts widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [
			'pp-chartjs',
			'pp-chart',
		];
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	/**
	 * Register Charts widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function register_controls() {
		// Content tab
		$this->register_content_charts_controls();
		$this->register_content_dataset_controls();
		$this->register_content_legend_controls();
		$this->register_content_tooltip_controls();
		$this->register_content_chart_title_controls();
		$this->register_content_options_controls();

		// Style tab
		$this->register_style_chart_controls();
		$this->register_style_chart_title_controls();
		$this->register_style_grid_controls();
		$this->register_style_labels_controls();
		$this->register_style_legend_controls();
		$this->register_style_points_controls();
		$this->register_style_tooltip_controls();
	}

	protected function register_content_charts_controls() {
		$this->start_controls_section(
			'section_charts',
			[
				'label' => esc_html__( 'Charts', 'powerpack' ),
			]
		);

		$this->add_control(
			'chart_type',
			[
				'label'   => esc_html__( 'Chart Type', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'line',
				'options' => [
					'line'      => esc_html__( 'Line', 'powerpack' ),
					'bar'       => esc_html__( 'Bar', 'powerpack' ),
					'radar'     => esc_html__( 'Radar', 'powerpack' ) . ' (' . esc_html__( 'Pro', 'powerpack' ) . ')',
					'pie'       => esc_html__( 'Pie', 'powerpack' ) . ' (' . esc_html__( 'Pro', 'powerpack' ) . ')',
					'doughnut'  => esc_html__( 'Doughnut', 'powerpack' ) . ' (' . esc_html__( 'Pro', 'powerpack' ) . ')',
					'polarArea' => esc_html__( 'Polar Area', 'powerpack' ) . ' (' . esc_html__( 'Pro', 'powerpack' ) . ')',
					'bubble'    => esc_html__( 'Bubble', 'powerpack' ) . ' (' . esc_html__( 'Pro', 'powerpack' ) . ')',
				],
			]
		);

		$this->add_control(
			'chart_type_notice',
			[
				'label'           => '',
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This chart type is available in PowerPack Pro.', 'powerpack' ) . ' ' . apply_filters( 'upgrade_powerpack_message', sprintf( esc_html__( 'Upgrade to %1$s Pro Version %2$s for 90+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
				'content_classes' => 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				'condition'       => [
					'chart_type!' => [ 'line', 'bar' ],
				],
			]
		);

		$this->add_control(
			'stepped_line',
			[
				'label'     => esc_html__( 'Stepped Line Chart', 'powerpack' ) . ' (' . esc_html__( 'Pro', 'powerpack' ) . ')',
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => '',
				'condition' => [
					'chart_type' => 'line',
				],
			]
		);

		$this->add_control(
			'bar_chart_type',
			[
				'label'   => esc_html__( 'Orientation', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'vertical_bar',
				'options' => [
					'vertical_bar'   => esc_html__( 'Vertical Bar', 'powerpack' ),
					'horizontal_bar' => esc_html__( 'Horizontal Bar', 'powerpack' ),
				],
				'condition' => [
					'chart_type' => 'bar',
				],
			]
		);

		$this->add_control(
			'labels',
			[
				'label'       => esc_html__( 'Labels', 'powerpack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'January, February, March, April', 'powerpack' ),
				'description' => esc_html__( 'Add labels separated by comma', 'powerpack' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_dataset_controls() {
		$this->start_controls_section(
			'section_chart_dataset',
			[
				'label' => esc_html__( 'Dataset', 'powerpack' ),
			],
		);

		// Line, Bar and Radar Area Chart Data
		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_chart' );

		$repeater->start_controls_tab(
			'tabs_chart_content',
			[
				'label' => esc_html__( 'Content', 'powerpack' ),
			]
		);

		$repeater->add_control(
			'dataset_label',
			[
				'label'   => esc_html__( 'Label', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Label', 'powerpack' ),
				'dynamic' => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'dataset_data',
			[
				'label'       => esc_html__( 'Data', 'powerpack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '40, 60, 10, 80',
				'description' => esc_html__( 'Add data values separated by comma', 'powerpack' ),
				'dynamic'     => [ 'active' => true ],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tabs_chart_style',
			[
				'label' => esc_html__( 'Style', 'powerpack' ),
			]
		);

		$repeater->add_control(
			'bg_color',
			[
				'label'   => esc_html__( 'Background Color', 'powerpack' ),
				'type'    => Controls_Manager::COLOR,
				'default' => 'rgb(0 0 0 / 50%)',
			]
		);

		$repeater->add_control(
			'border_color',
			[
				'label'   => esc_html__( 'Border Color', 'powerpack' ),
				'type'    => Controls_Manager::COLOR,
				'default' => 'rgb(0 0 0 / 50%)',
			]
		);

		$repeater->add_control(
			'fill',
			[
				'label'       => esc_html__( 'Fill', 'powerpack' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => '',
				'description' => esc_html__( 'Fill option is supported by Line and Radar charts only', 'powerpack' ),
				'separator'   => 'before',
			]
		);

		$repeater->add_control(
			'border_dash',
			[
				'label'       => esc_html__( 'Border Dash', 'powerpack' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Border Dash option is supported by Line and Radar charts only', 'powerpack' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'chart_dataset',
			[
				'label'       => esc_html__( 'Chart Data', 'powerpack' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'dataset_label'   => esc_html__( 'Data 1', 'powerpack' ),
						'dataset_data'    => '40, 60, 10, 80',
						'bg_color'     => '#EC6E8599',
						'border_color' => '#EC6E85',
					],
					[
						'dataset_label'   => esc_html__( 'Data 2', 'powerpack' ),
						'dataset_data'    => '5, 35, 55, 90',
						'bg_color'     => '#569FE599',
						'border_color' => '#569FE5',
					],
					[
						'dataset_label'   => esc_html__( 'Data 3', 'powerpack' ),
						'dataset_data'    => '30, 25, 40, 5',
						'bg_color'     => '#F7CF6B99',
						'border_color' => '#F7CF6B',
					],
				],
				'title_field' => '{{{ dataset_label }}}',
				'condition'   => [
					'chart_type' => [ 'line', 'bar' ],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_legend_controls() {
		$this->start_controls_section(
			'section_legend',
			[
				'label' => esc_html__( 'Legend', 'powerpack' ),
			]
		);

		$this->add_control(
			'show_legend',
			[
				'label'     => esc_html__( 'Show Legend', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'legend_reverse',
			[
				'label'     => esc_html__( 'Reverse', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
			]
		);

		$this->add_control(
			'legend_position',
			[
				'label'   => esc_html__( 'Position', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'left'   => esc_html__( 'Left', 'powerpack' ),
					'top'    => esc_html__( 'Top', 'powerpack' ),
					'bottom' => esc_html__( 'Bottom', 'powerpack' ),
					'right'  => esc_html__( 'Right', 'powerpack' ),
				],
				'condition' => [
					'show_legend' => 'yes',
				],
			]
		);

		$this->add_control(
			'legend_align',
			[
				'label'   => esc_html__( 'Alignment', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'start'  => esc_html__( 'Start', 'powerpack' ),
					'center' => esc_html__( 'Center', 'powerpack' ),
					'end'    => esc_html__( 'End', 'powerpack' ),
				],
				'condition' => [
					'show_legend' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_tooltip_controls() {
		$this->start_controls_section(
			'section_chart_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'powerpack' ),
			]
		);

		$this->add_control(
			'show_tooltip',
			[
				'label'     => esc_html__( 'Show Tooltip', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'tooltip_event',
			[
				'label'     => esc_html__( 'Tooltip Event', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'hover',
				'options'   => [
					'hover' => esc_html__( 'Hover', 'powerpack' ),
					'click' => esc_html__( 'Click', 'powerpack' ),
				],
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'interaction_mode',
			[
				'label'     => esc_html__( 'Tooltip Mode', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'point',
				'options'   => [
					'index'   => esc_html__( 'Index', 'powerpack' ),
					'point'   => esc_html__( 'Point', 'powerpack' ),
					'dataset' => esc_html__( 'Dataset', 'powerpack' ),
				],
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_chart_title_controls() {
		$this->start_controls_section(
			'section_chart_title',
			[
				'label' => esc_html__( 'Chart Title', 'powerpack' ),
			]
		);

		$this->add_control(
			'show_chart_title',
			[
				'label'     => esc_html__( 'Show Chart Title', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => '',
			]
		);

		$this->add_control(
			'chart_title',
			[
				'label'     => esc_html__( 'Chart Title', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'chart_title_position',
			[
				'label'   => esc_html__( 'Position', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'left'   => esc_html__( 'Left', 'powerpack' ),
					'top'    => esc_html__( 'Top', 'powerpack' ),
					'bottom' => esc_html__( 'Bottom', 'powerpack' ),
					'right'  => esc_html__( 'Right', 'powerpack' ),
				],
				'condition' => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'chart_title_align',
			[
				'label'   => esc_html__( 'Alignment', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => [
					'start'  => esc_html__( 'Start', 'powerpack' ),
					'center' => esc_html__( 'Center', 'powerpack' ),
					'end'    => esc_html__( 'End', 'powerpack' ),
				],
				'condition' => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_options_controls() {
		$this->start_controls_section(
			'section_chart_additional_options',
			[
				'label' => esc_html__( 'Additional Options', 'powerpack' ),
			]
		);

		$this->add_control(
			'begin_at_zero',
			[
				'label'      => esc_html__( 'Begin at Zero', 'powerpack' ),
				'type'       => Controls_Manager::SWITCHER,
				'default'    => 'yes',
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'x_axis_show_grid_lines',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'y_axis_show_grid_lines',
							'operator' => '===',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'step_size',
			[
				'type'       => Controls_Manager::NUMBER,
				'label'      => esc_html__( 'Step Size', 'powerpack' ),
				'min'        => 0,
				'step'       => 0.5,
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'x_axis_show_grid_lines',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'y_axis_show_grid_lines',
							'operator' => '===',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'maintain_aspect_ratio',
			[
				'label'     => esc_html__( 'Maintain Aspect Ratio', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => 'yes',
			]
		);

		$this->add_responsive_control(
			'chart_height',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Chart Height', 'powerpack' ),
				'range'      => [
					'px' => [
						'min' => 200,
						'max' => 1200,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'default'    => [
					'size' => 350,
				],
				'size_units' => [ 'px', '%', 'vh' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-chart-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'maintain_aspect_ratio!' => 'yes',
				],
			]
		);

		$this->add_control(
			'x_axis_heading',
			[
				'label'     => esc_html__( 'X Axis', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'x_axis_show_grid_lines',
			[
				'label'     => esc_html__( 'Show Grid Lines', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'x_axis_show_labels',
			[
				'label'     => esc_html__( 'Show Labels', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'x_axis_show_title',
			[
				'label'     => esc_html__( 'Show Title', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => '',
			]
		);

		$this->add_control(
			'x_axis_title',
			[
				'label'     => esc_html__( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'x_axis_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'y_axis_heading',
			[
				'label'     => esc_html__( 'Y Axis', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'y_axis_show_grid_lines',
			[
				'label'     => esc_html__( 'Show Grid Lines', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'y_axis_show_labels',
			[
				'label'     => esc_html__( 'Show Labels', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'y_axis_labels_prefix',
			[
				'label'              => esc_html__( 'Labels Prefix', 'powerpack' ) . ' (' . esc_html__( 'Pro', 'powerpack' ) . ')',
				'type'               => Controls_Manager::TEXT,
				'default'            => '',
				'dynamic'            => [
					'active' => true,
				],
				'condition'          => [
					'y_axis_show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'y_axis_labels_suffix',
			[
				'label'              => esc_html__( 'Labels Suffix', 'powerpack' ) . ' (' . esc_html__( 'Pro', 'powerpack' ) . ')',
				'type'               => Controls_Manager::TEXT,
				'default'            => '',
				'dynamic'            => [
					'active' => true,
				],
				'condition'          => [
					'y_axis_show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			'y_axis_show_title',
			[
				'label'     => esc_html__( 'Show Title', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => '',
			]
		);

		$this->add_control(
			'y_axis_title',
			[
				'label'     => esc_html__( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'dynamic'   => [
					'active' => true,
				],
				'condition' => [
					'y_axis_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'y_axis_min',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => esc_html__( 'Minimum Value', 'powerpack' ) . ' (' . esc_html__( 'Pro', 'powerpack' ) . ')',
				'description' => esc_html__( 'Set minimum value for Y-axis. This value is ignored when data has a smaller value.', 'powerpack' ),
			]
		);

		$this->add_control(
			'y_axis_max',
			[
				'type'        => Controls_Manager::NUMBER,
				'label'       => esc_html__( 'Maximum Value', 'powerpack' ) . ' (' . esc_html__( 'Pro', 'powerpack' ) . ')',
				'description' => esc_html__( 'Set maximum value for Y-axis. This value is ignored when data has a larger value.', 'powerpack' ),
			]
		);

		$this->add_control(
			'line_chart_heading',
			[
				'label'     => esc_html__( 'Line Chart', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'chart_type' => 'line',
				],
			]
		);

		$this->add_control(
			'tension',
			[
				'type'      => Controls_Manager::NUMBER,
				'label'     => esc_html__( 'Line Tension', 'powerpack' ),
				'min'       => 0,
				'max'       => 10,
				'step'      => 0.1,
				'condition' => [
					'chart_type' => 'line',
				],
			]
		);

		$this->add_control(
			'stacked_heading',
			[
				'label'     => esc_html__( 'Bar Chart', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'chart_type' => 'bar',
				],
			]
		);

		$this->add_control(
			'stacked',
			[
				'label'     => esc_html__( 'Stacked', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => '',
				'condition' => [
					'chart_type' => 'bar',
				],
			]
		);

		$this->add_control(
			'animation_heading',
			[
				'label'     => esc_html__( 'Animation', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chart_animation',
			[
				'label'   => esc_html__( 'Animation', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'easeOutQuart',
				'options' => [
					'linear'           => esc_html__( 'linear', 'powerpack' ),
					'easeInQuad'       => esc_html__( 'easeInQuad', 'powerpack' ),
					'easeOutQuad'      => esc_html__( 'easeOutQuad', 'powerpack' ),
					'easeInOutQuad'    => esc_html__( 'easeInOutQuad', 'powerpack' ),
					'easeInCubic'      => esc_html__( 'easeInCubic', 'powerpack' ),
					'easeOutCubic'     => esc_html__( 'easeOutCubic', 'powerpack' ),
					'easeInOutCubic'   => esc_html__( 'easeInOutCubic', 'powerpack' ),
					'easeInQuart'      => esc_html__( 'easeInQuart', 'powerpack' ),
					'easeOutQuart'     => esc_html__( 'easeOutQuart', 'powerpack' ),
					'easeInOutQuart'   => esc_html__( 'easeInOutQuart', 'powerpack' ),
					'easeInQuint'      => esc_html__( 'easeInQuint', 'powerpack' ),
					'easeOutQuint'     => esc_html__( 'easeOutQuint', 'powerpack' ),
					'easeInOutQuint'   => esc_html__( 'easeInOutQuint', 'powerpack' ),
					'easeInSine'       => esc_html__( 'easeInSine', 'powerpack' ),
					'easeOutSine'      => esc_html__( 'easeOutSine', 'powerpack' ),
					'easeInOutSine'    => esc_html__( 'easeInOutSine', 'powerpack' ),
					'easeInExpo'       => esc_html__( 'easeInExpo', 'powerpack' ),
					'easeOutExpo'      => esc_html__( 'easeOutExpo', 'powerpack' ),
					'easeInOutExpo'    => esc_html__( 'easeInOutExpo', 'powerpack' ),
					'easeInCirc'       => esc_html__( 'easeInCirc', 'powerpack' ),
					'easeOutCirc'      => esc_html__( 'easeOutCirc', 'powerpack' ),
					'easeInOutCirc'    => esc_html__( 'easeInOutCirc', 'powerpack' ),
					'easeInElastic'    => esc_html__( 'easeInElastic', 'powerpack' ),
					'easeOutElastic'   => esc_html__( 'easeOutElastic', 'powerpack' ),
					'easeInOutElastic' => esc_html__( 'easeInOutElastic', 'powerpack' ),
					'easeInBack'       => esc_html__( 'easeInBack', 'powerpack' ),
					'easeOutBack'      => esc_html__( 'easeOutBack', 'powerpack' ),
					'easeInOutBack'    => esc_html__( 'easeInOutBack', 'powerpack' ),
					'easeInBounce'     => esc_html__( 'easeInBounce', 'powerpack' ),
					'easeOutBounce'    => esc_html__( 'easeOutBounce', 'powerpack' ),
					'easeInOutBounce'  => esc_html__( 'easeInOutBounce', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'chart_animation_duration',
			[
				'type'    => Controls_Manager::SLIDER,
				'label'   => esc_html__( 'Duration', 'powerpack' ),
				'range'   => [
					'px' => [
						'min' => 1,
						'max' => 10000,
						'step' => 100,
					],
				],
				'default' => [
					'size' => 1000,
				],
			]
		);

		$this->add_control(
			'chart_animation_loop',
			[
				'label'     => esc_html__( 'Loop Animation', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => '',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_chart_controls() {
		$this->start_controls_section(
			'section_chart_style',
			[
				'label' => esc_html__( 'Chart', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'line_border_width',
			[
				'label'     => esc_html__( 'Line Border Width', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 3,
				],
				'condition' => [
					'chart_type' => 'line',
				],
			]
		);

		$this->add_control(
			'bar_border_width',
			[
				'label'     => esc_html__( 'Bar Border Width', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 1,
				],
				'condition' => [
					'chart_type' => 'bar',
				],
			]
		);

		$this->add_control(
			'bar_border_radius',
			[
				'label'     => esc_html__( 'Bar Border Radius', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'condition' => [
					'chart_type' => 'bar',
				],
			]
		);

		$this->add_control(
			'maxbarthickness',
			[
				'label'     => esc_html__( 'Bar Size', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'condition' => [
					'chart_type' => 'bar',
				],
			]
		);

		$this->add_control(
			'barthickness',
			[
				'label'     => esc_html__( 'Bar Space', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'condition' => [
					'chart_type' => 'bar',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_chart_title_controls() {
		$this->start_controls_section(
			'section_chart_title_style',
			[
				'label'     => __( 'Chart Title', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'chart_title_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'chart_title_typography',
			[
				'label'        => esc_html__( 'Typography', 'powerpack' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition'    => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->start_popover();

		$this->add_control(
			'chart_title_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Size', 'powerpack' ),
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 12,
				],
				'condition' => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'chart_title_font_weight',
			[
				'label'     => esc_html__( 'Weight', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'100'    => esc_html__( '100', 'powerpack' ),
					'200'    => esc_html__( '200', 'powerpack' ),
					'300'    => esc_html__( '300', 'powerpack' ),
					'400'    => esc_html__( '400', 'powerpack' ),
					'500'    => esc_html__( '500', 'powerpack' ),
					'600'    => esc_html__( '600', 'powerpack' ),
					'700'    => esc_html__( '700', 'powerpack' ),
					'800'    => esc_html__( '800', 'powerpack' ),
					'900'    => esc_html__( '900', 'powerpack' ),
					''       => esc_html__( 'Default', 'powerpack' ),
					'normal' => esc_html__( 'Normal', 'powerpack' ),
					'bold'   => esc_html__( 'Bold', 'powerpack' ),
				],
				'condition' => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'chart_title_font_style',
			[
				'label'     => esc_html__( 'Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''        => esc_html__( 'Default', 'powerpack' ),
					'normal'  => esc_html__( 'Normal', 'powerpack' ),
					'italic'  => esc_html__( 'Italic', 'powerpack' ),
					'oblique' => esc_html__( 'Oblique', 'powerpack' ),
				],
				'condition' => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'chart_title_line_height',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Line Height', 'powerpack' ),
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'condition'  => [
					'show_chart_title' => 'yes',
				],
			]
		);

		$this->end_popover();

		$this->end_controls_section();
	}

	protected function register_style_grid_controls() {
		$this->start_controls_section(
			'section_chart_grid_style',
			[
				'label'      => __( 'Grid', 'powerpack' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'     => 'chart_type',
									'operator' => '!==',
									'value'    => 'pie',
								],
								[
									'name'     => 'chart_type',
									'operator' => '!==',
									'value'    => 'doughnut',
								],
							],
						],
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'x_axis_show_grid_lines',
									'operator' => '===',
									'value'    => 'yes',
								],
								[
									'name'     => 'y_axis_show_grid_lines',
									'operator' => '===',
									'value'    => 'yes',
								],
								[
									'name'     => 'x_axis_show_title',
									'operator' => '===',
									'value'    => 'yes',
								],
								[
									'name'     => 'y_axis_show_title',
									'operator' => '===',
									'value'    => 'yes',
								],
							],
						],
					],
				],
			]
		);

		$this->start_controls_tabs( 'tabs_grid_style' );

		$this->start_controls_tab(
			'tab_grid_x',
			[
				'label'     => esc_html__( 'X Axis', 'powerpack' ),
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
				],
			]
		);

		$this->grid_style_controls( 'x' );

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_grid_y',
			[
				'label'     => esc_html__( 'Y Axis', 'powerpack' ),
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
				],
			]
		);

		$this->grid_style_controls( 'y' );

		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function grid_style_controls( $type ) {
		$this->add_control(
			'grid_color_' . $type,
			[
				'label'     => esc_html__( 'Grid Line Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgb(0 0 0 / 50%)',
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_grid_lines' => 'yes',
				],
			]
		);

		$this->add_control(
			'grid_width_' . $type,
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Grid Line Width', 'powerpack' ),
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 10,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 1,
				],
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_grid_lines' => 'yes',
				],
			]
		);

		$this->title_style_controls( $type );
	}

	protected function title_style_controls( $type ) {

		$this->add_control(
			$type . '_heading',
			[
				'label'     => esc_html__( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			$type . '_axis_title_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			$type . '_axis_title_typography',
			[
				'label'        => esc_html__( 'Typography', 'powerpack' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition'    => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_title' => 'yes',
				],
			]
		);

		$this->start_popover();

		$this->add_control(
			$type . '_axis_title_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Size', 'powerpack' ),
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 12,
				],
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			$type . '_axis_title_font_weight',
			[
				'label'     => esc_html__( 'Weight', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'100'    => esc_html__( '100', 'powerpack' ),
					'200'    => esc_html__( '200', 'powerpack' ),
					'300'    => esc_html__( '300', 'powerpack' ),
					'400'    => esc_html__( '400', 'powerpack' ),
					'500'    => esc_html__( '500', 'powerpack' ),
					'600'    => esc_html__( '600', 'powerpack' ),
					'700'    => esc_html__( '700', 'powerpack' ),
					'800'    => esc_html__( '800', 'powerpack' ),
					'900'    => esc_html__( '900', 'powerpack' ),
					''       => esc_html__( 'Default', 'powerpack' ),
					'normal' => esc_html__( 'Normal', 'powerpack' ),
					'bold'   => esc_html__( 'Bold', 'powerpack' ),
				],
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			$type . '_axis_title_font_style',
			[
				'label'     => esc_html__( 'Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''        => esc_html__( 'Default', 'powerpack' ),
					'normal'  => esc_html__( 'Normal', 'powerpack' ),
					'italic'  => esc_html__( 'Italic', 'powerpack' ),
					'oblique' => esc_html__( 'Oblique', 'powerpack' ),
				],
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			$type . '_axis_title_line_height',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Line Height', 'powerpack' ),
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'condition'  => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_title' => 'yes',
				],
			]
		);

		$this->end_popover();
	}

	protected function register_style_labels_controls() {
		$this->start_controls_section(
			'section_chart_labels_style',
			[
				'label'      => __( 'Labels', 'powerpack' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'relation' => 'and',
							'terms'    => [
								[
									'name'     => 'chart_type',
									'operator' => '!==',
									'value'    => 'pie',
								],
								[
									'name'     => 'chart_type',
									'operator' => '!==',
									'value'    => 'doughnut',
								],
							],
						],
						[
							'relation' => 'or',
							'terms'    => [
								[
									'name'     => 'x_axis_show_labels',
									'operator' => '===',
									'value'    => 'yes',
								],
								[
									'name'     => 'y_axis_show_labels',
									'operator' => '===',
									'value'    => 'yes',
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'x_axis_labels_heading',
			[
				'label'     => esc_html__( 'X Axis', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					'x_axis_show_labels' => 'yes',
				],
			]
		);

		$this->labels_style_controls( 'x' );

		$this->add_control(
			'y_axis_labels_heading',
			[
				'label'     => esc_html__( 'Y Axis', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					'y_axis_show_labels' => 'yes',
				],
			]
		);

		$this->labels_style_controls( 'y' );

		$this->end_controls_section();
	}

	protected function labels_style_controls( $type ) {
		$this->add_control(
			$type . '_axis_labels_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#666',
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			$type . '_axis_labels_typography',
			[
				'label'        => esc_html__( 'Typography', 'powerpack' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition'    => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_labels' => 'yes',
				],
			]
		);

		$this->start_popover();

		$this->add_control(
			$type . '_axis_labels_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Size', 'powerpack' ),
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 12,
				],
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			$type . '_axis_labels_font_weight',
			[
				'label'     => esc_html__( 'Weight', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'100'    => esc_html__( '100', 'powerpack' ),
					'200'    => esc_html__( '200', 'powerpack' ),
					'300'    => esc_html__( '300', 'powerpack' ),
					'400'    => esc_html__( '400', 'powerpack' ),
					'500'    => esc_html__( '500', 'powerpack' ),
					'600'    => esc_html__( '600', 'powerpack' ),
					'700'    => esc_html__( '700', 'powerpack' ),
					'800'    => esc_html__( '800', 'powerpack' ),
					'900'    => esc_html__( '900', 'powerpack' ),
					''       => esc_html__( 'Default', 'powerpack' ),
					'normal' => esc_html__( 'Normal', 'powerpack' ),
					'bold'   => esc_html__( 'Bold', 'powerpack' ),
				],
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			$type . '_axis_labels_font_style',
			[
				'label'     => esc_html__( 'Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''        => esc_html__( 'Default', 'powerpack' ),
					'normal'  => esc_html__( 'Normal', 'powerpack' ),
					'italic'  => esc_html__( 'Italic', 'powerpack' ),
					'oblique' => esc_html__( 'Oblique', 'powerpack' ),
				],
				'condition' => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_labels' => 'yes',
				],
			]
		);

		$this->add_control(
			$type . '_axis_labels_line_height',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Line Height', 'powerpack' ),
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'condition'  => [
					'chart_type!' => [ 'pie', 'doughnut' ],
					$type . '_axis_show_labels' => 'yes',
				],
			]
		);

		$this->end_popover();
	}

	protected function register_style_legend_controls() {
		$this->start_controls_section(
			'section_legend_style',
			[
				'label'     => __( 'Legend', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_legend' => 'yes',
				],
			]
		);

		$this->add_control(
			'legend_color',
			[
				'label'     => esc_html__( 'Label Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#666',
				'condition' => [
					'show_legend' => 'yes',
				],
			]
		);

		$this->add_control(
			'legend_typography',
			[
				'label'        => esc_html__( 'Typography', 'powerpack' ),
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',
				'condition'    => [
					'show_legend' => 'yes',
				],
			]
		);

		$this->start_popover();

		$this->add_control(
			'legend_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Size', 'powerpack' ),
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 12,
				],
				'condition' => [
					'show_legend' => 'yes',
				],
			]
		);

		$this->add_control(
			'legend_font_weight',
			[
				'label'     => esc_html__( 'Weight', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'100'    => esc_html__( '100', 'powerpack' ),
					'200'    => esc_html__( '200', 'powerpack' ),
					'300'    => esc_html__( '300', 'powerpack' ),
					'400'    => esc_html__( '400', 'powerpack' ),
					'500'    => esc_html__( '500', 'powerpack' ),
					'600'    => esc_html__( '600', 'powerpack' ),
					'700'    => esc_html__( '700', 'powerpack' ),
					'800'    => esc_html__( '800', 'powerpack' ),
					'900'    => esc_html__( '900', 'powerpack' ),
					''       => esc_html__( 'Default', 'powerpack' ),
					'normal' => esc_html__( 'Normal', 'powerpack' ),
					'bold'   => esc_html__( 'Bold', 'powerpack' ),
				],
				'condition' => [
					'show_legend' => 'yes',
				],
			]
		);

		$this->add_control(
			'legend_font_style',
			[
				'label'     => esc_html__( 'Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''        => esc_html__( 'Default', 'powerpack' ),
					'normal'  => esc_html__( 'Normal', 'powerpack' ),
					'italic'  => esc_html__( 'Italic', 'powerpack' ),
					'oblique' => esc_html__( 'Oblique', 'powerpack' ),
				],
				'condition' => [
					'show_legend' => 'yes',
				],
			]
		);

		$this->add_control(
			'legend_line_height',
			[
				'type'       => Controls_Manager::SLIDER,
				'label'      => esc_html__( 'Line Height', 'powerpack' ),
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0.1,
						'max' => 10,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'condition'  => [
					'show_legend' => 'yes',
				],
			]
		);

		$this->end_popover();

		$this->end_controls_section();
	}

	protected function register_style_points_controls() {
		$this->start_controls_section(
			'section_chart_points_style',
			[
				'label'     => __( 'Points', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'chart_type' => 'line',
				],
			]
		);

		$this->add_control(
			'custom_point_styles',
			[
				'label'     => esc_html__( 'Custom Styles', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Yes', 'powerpack' ),
				'label_off' => esc_html__( 'No', 'powerpack' ),
				'default'   => '',
				'condition' => [
					'chart_type' => 'line',
				],
			]
		);

		$this->add_control(
			'point_style',
			[
				'label'     => esc_html__( 'Point Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'circle',
				'options'   => [
					'circle'      => esc_html__( 'Circle', 'powerpack' ),
					'cross'       => esc_html__( 'Cross', 'powerpack' ),
					'crossRot'    => esc_html__( 'CrossRot', 'powerpack' ),
					'dash'        => esc_html__( 'Dash', 'powerpack' ),
					'line'        => esc_html__( 'Line', 'powerpack' ),
					'rect'        => esc_html__( 'Rect', 'powerpack' ),
					'rectRounded' => esc_html__( 'RectRounded', 'powerpack' ),
					'rectRot'     => esc_html__( 'RectRot', 'powerpack' ),
					'star'        => esc_html__( 'Star', 'powerpack' ),
					'triangle'    => esc_html__( 'Triangle', 'powerpack' ),
				],
				'condition' => [
					'chart_type' => 'line',
					'custom_point_styles' => 'yes',
				],
			]
		);

		$this->add_control(
			'point_bg',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ff5a6e99',
				'condition' => [
					'chart_type' => 'line',
					'custom_point_styles' => 'yes',
					'point_style' => [ 'circle', 'rect', 'rectRounded', 'rectRot', 'triangle' ],
				],
			]
		);

		$this->add_control(
			'point_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#00000099',
				'condition' => [
					'chart_type' => 'line',
					'custom_point_styles' => 'yes',
				],
			]
		);

		$this->add_control(
			'point_border_width',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Border Width', 'powerpack' ),
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 1,
				],
				'condition' => [
					'chart_type' => 'line',
					'custom_point_styles' => 'yes',
				],
			]
		);

		$this->add_control(
			'point_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Point Size', 'powerpack' ),
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 5,
				],
				'condition' => [
					'chart_type' => 'line',
					'custom_point_styles' => 'yes',
				],
			]
		);

		$this->add_control(
			'point_hover_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Point Hover Size', 'powerpack' ),
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 10,
				],
				'condition' => [
					'chart_type' => 'line',
					'custom_point_styles' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_tooltip_controls() {
		$this->start_controls_section(
			'section_chart_tooltip_style',
			[
				'label'     => __( 'Tooltip', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_border_width',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Border Width', 'powerpack' ),
				'range'     => [
					'px' => [
						'min'  => 1,
						'max'  => 20,
						'step' => 1,
					],
				],
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_border_radius',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Border Radius', 'powerpack' ),
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 25,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 6,
				],
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_padding',
			[
				'label'     => esc_html__( 'Padding', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_title_heading',
			[
				'label'     => esc_html__( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_title_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_title_font_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Font Size', 'powerpack' ),
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 12,
				],
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_body_heading',
			[
				'label'     => esc_html__( 'Body', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'tooltip_body_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_body_font_size',
			[
				'type'      => Controls_Manager::SLIDER,
				'label'     => esc_html__( 'Font Size', 'powerpack' ),
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 50,
						'step' => 1,
					],
				],
				'default'   => [
					'size' => 12,
				],
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Charts widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings   = $this->get_settings_for_display();
		$chart_type = $this->get_chart_type();

		if ( 'radar' === $chart_type || 'pie' === $chart_type || 'doughnut' === $chart_type || 'polarArea' === $chart_type || 'bubble' === $chart_type ) {
			$placeholder = sprintf( esc_html__( 'Click here to edit the "%1$s" settings and choose one of the available chart types fron the Chart Type option.', 'powerpack' ), esc_attr( $this->get_title() ) );

			echo esc_attr( $this->render_editor_placeholder(
				[
					'title' => esc_html__( 'Chart type not available!', 'powerpack' ),
					'body' => $placeholder,
				]
			) );

			return;
		}

		$datasets = $this->get_datasets( $settings, $chart_type );
		$options  = $this->get_chart_options( $settings, $chart_type );

		$datasets = apply_filters( 'pp_chart_datasets', $datasets );
		$options  = apply_filters( 'pp_chart_options', $options );

		$this->add_render_attribute( 'wrapper', 'class', 'pp-chart-wrapper' );
		$this->add_render_attribute( 'wrapper', 'data-id', esc_attr( uniqid('chart') ) );
		$this->add_render_attribute( 'wrapper', 'style', 'position: relative;' );
		$this->add_render_attribute( 'wrapper', 'data-settings',
			wp_json_encode( array_filter([
				'type'    => $chart_type,
				'data'    => [
					'labels'   => explode( ',', $settings['labels'] ),
					'datasets' => $datasets,
				],
				'options' => $options,
			]) )
		);
		?>
		<div <?php echo $this->get_render_attribute_string('wrapper'); ?>></div>
		<?php
	}

	protected function get_chart_type() {
		$settings = $this->get_settings_for_display();

		if ( ! empty( $settings['chart_type'] ) && 'bar' === $settings['chart_type'] && 
			! empty( $settings['bar_chart_type'] ) && 'horizontal_bar' === $settings['bar_chart_type'] ) {
			return 'bar';
		}

		return $settings['chart_type'] ?? '';
	}
	
	protected function get_datasets( $settings, $chart_type ) {
		$datasets = [];

		$chart_datasets = $settings['chart_dataset'];
		foreach ( $chart_datasets as $item ) {
			$datasets[] = $this->get_chart_dataset( $settings, $chart_type, $item );
		}

		return $datasets;
	}

	protected function get_chart_dataset( $settings, $chart_type, $item ) {
		$dataset = [
			'label'           => $item['dataset_label'],
			'data'            => array_map( 'floatval', explode( ',', $item['dataset_data'] ) ),
			'backgroundColor' => $this->get_background_color( $item ),
			'borderColor'     => $this->get_border_color( $item ),
			'borderDash'      => ( 'line' === $chart_type && ! empty( $item['border_dash'] ) && 'yes' === $item['border_dash'] ) ? [5, 5] : [],
			'fill'            => ! empty( $item['fill'] ) && 'yes' === $item['fill'],
			'borderWidth'     => $this->get_border_width(),
		];

		if ( in_array( $chart_type, ['line'], true ) ) {
			$dataset = array_merge( $dataset, $this->get_point_styles( $settings, $chart_type ) );
		}

		if ( 'line' === $chart_type && '' !== $settings['tension'] ) {
			$dataset['tension'] = $settings['tension'];
		}

		if ( 'bar' === $chart_type ) {
			if ( ! empty( $settings['bar_border_radius']['size'] ) ) {
				$dataset['borderRadius']  = $settings['bar_border_radius']['size'];
				$dataset['borderSkipped'] = false;
			}

			if ( ! empty( $settings['barthickness']['size'] ) ) {
				$dataset['barThickness'] = $settings['barthickness']['size'];
			}

			if ( ! empty( $settings['maxbarthickness']['size'] ) ) {
				$dataset['maxBarThickness'] = $settings['maxbarthickness']['size'];
			}
		}

		return $dataset;
	}

	protected function get_border_width() {
		$settings   = $this->get_settings_for_display();
		$chart_type = $this->get_chart_type();

		switch ( $chart_type ) {
			case 'bar':
				$data_border_width = $settings['bar_border_width']['size'];
				break;

			case 'line':
				$data_border_width = $settings['line_border_width']['size'];
				break;
			
			default:
				$data_border_width = 0;
				break;
		}

		$border_width = ( '' !== $data_border_width ) ? $data_border_width : $this->get_default_border_width();

		return $border_width;
	}

	protected function get_default_border_width() {
		$chart_type = $this->get_chart_type();

		switch ( $chart_type ) {
			case 'bar':
				$border_width = 0;
				break;

			case 'line':
				$border_width = 3;
				break;

			default:
				$border_width = 3;
				break;
		}

		return $border_width;
	}

	protected function get_background_color( $item ) {
		return $item['bg_color'];
	}

	protected function get_border_color( $item ) {
		return $item['border_color'];
	}

	protected function get_point_styles( $settings, $chart_type ) {
		$styles = [];

		if ( 'yes' === $settings['custom_point_styles'] ) {
			$styles['pointStyle'] = $settings['point_style'] ?? '';

			if ( $settings['point_bg'] ) {
				$styles['pointBackgroundColor'] = $settings['point_bg'];
			}

			$styles['pointRadius'] = $settings['point_size']['size'] ?? null;
			$styles['pointHoverRadius'] = $settings['point_hover_size']['size'] ?? null;

			if ( $settings['point_border_width']['size'] ) {
				$styles['pointBorderWidth'] = $settings['point_border_width']['size'];
			}

			if ( $settings['point_border_color'] ) {
				$styles['pointBorderColor'] = $settings['point_border_color'];
			}
		}

		return $styles;
	}

	protected function get_chart_options( $settings, $chart_type ) {
		$options = [];

		if ( 'bar' === $chart_type && 'horizontal_bar' === $settings['bar_chart_type'] ) {
			$options['indexAxis'] = 'y';
		}

		$options['scales'] = $this->get_grid_options( $settings );

		$options = $this->get_additional_options( $settings, $options );

		return $options;
	}

	protected function get_grid_options( $settings ) {
		$x_axis_title = $this->get_axis_title( $settings, 'x' );
		$y_axis_title = $this->get_axis_title( $settings, 'y' );

		return [
			'x' => array_merge( $this->get_axis_settings( $settings, 'x' ), [
				'title' => $x_axis_title,
				'grid'  => $this->get_grid_settings( $settings, 'x' ),
			] ),
			'y' => array_merge( $this->get_axis_settings( $settings, 'y' ), [
				'title' => $y_axis_title,
				'grid'  => $this->get_grid_settings( $settings, 'y' ),
			] ),
		];
	}

	private function get_axis_title( $settings, $axis ) {
		$show_title_key  = "{$axis}_axis_show_title";
		$title_key       = "{$axis}_axis_title";
		$color_key       = "{$axis}_axis_title_color";
		$typography_key  = "{$axis}_axis_title_typography";
		$size_key        = "{$axis}_axis_title_size";
		$font_style_key  = "{$axis}_axis_title_font_style";
		$font_weight_key = "{$axis}_axis_title_font_weight";
		$line_height_key = "{$axis}_axis_title_line_height";

		if ( 'yes' === $settings[$show_title_key] ) {
			return [
				'display' => true,
				'text'    => $settings[$title_key] ?? '',
				'color'   => $settings[$color_key],
				'font'    => [
					'size'       => ! empty( $settings[$size_key]['size'] ) ? $settings[$size_key]['size'] : 12,
					'style'      => $settings[$font_style_key] ?? '',
					'weight'     => $settings[$font_weight_key] ?? '',
					'lineHeight' => ! empty( $settings[$line_height_key]['size'] ) ? $settings[$line_height_key]['size'] . $settings[$line_height_key]['unit'] : '',
				],
			];
		}

		return [ 'display' => false ];
	}

	private function get_axis_settings( $settings, $axis ) {
		$begin_at_zero_key = 'begin_at_zero';
		$chart_type_key    = 'chart_type';
		$stacked_key       = 'stacked';

		return [
			'beginAtZero' => ( 'yes' === $settings[$begin_at_zero_key] ) ? true : false,
			'stacked'     => ( 'bar' === $settings[$chart_type_key] && 'yes' === $settings[$stacked_key] ) ? true : false,
			'ticks'       => $this->get_ticks_settings( $settings, $axis ),
		];
	}

	private function get_grid_settings( $settings, $axis ) {
		$show_grid_lines_key = "{$axis}_axis_show_grid_lines";
		$grid_color_key      = "grid_color_{$axis}";
		$grid_width_key      = "grid_width_{$axis}";

		return [
			'display'   => ! empty( $settings[$show_grid_lines_key] ),
			'color'     => $settings[$grid_color_key],
			'lineWidth' => ! empty( $settings[$grid_width_key]['size'] ) ? $settings[$grid_width_key]['size'] : 1,
		];
	}

	private function get_ticks_settings( $settings, $axis ) {
		$show_labels_key  = "{$axis}_axis_show_labels";
		$labels_color_key = "{$axis}_axis_labels_color";
		$typography_key   = "{$axis}_axis_labels_typography";
		$size_key         = "{$axis}_axis_labels_size";
		$font_style_key   = "{$axis}_axis_labels_font_style";
		$font_weight_key  = "{$axis}_axis_labels_font_weight";
		$line_height_key  = "{$axis}_axis_labels_line_height";
		$step_size_key    = 'step_size';

		return [
			'display'  => ! empty( $settings[$show_labels_key] ),
			'color'    => $settings[$labels_color_key],
			'font'     => [
				'size'       => ! empty( $settings[$size_key]['size'] ) ? $settings[$size_key]['size'] : 12,
				'style'      => $settings[$font_style_key] ?? '',
				'weight'     => $settings[$font_weight_key] ?? '',
				'lineHeight' => ! empty( $settings[$line_height_key]['size'] ) ? $settings[$line_height_key]['size'] . $settings[$line_height_key]['unit'] : '',
			],
			'stepSize' => ! empty( $settings[$step_size_key] ) ? $settings[$step_size_key] : 0,
		];
	}

	protected function get_additional_options( $settings, $options ) {
		if ( 'yes' === $settings['show_chart_title'] && ! empty( $settings['chart_title'] ) ) {
			$options['plugins']['title'] = [
				'display' => true,
				'text'    => $settings['chart_title'] ?? '',
				'color'   => $settings['chart_title_color'],
				'font'    => [
					'size'       => ( $settings['chart_title_typography'] && $settings['chart_title_size']['size'] ) ? $settings['chart_title_size']['size'] : 12,
					'style'      => ( $settings['chart_title_typography'] && $settings['chart_title_font_style'] ) ? $settings['chart_title_font_style'] : '',
					'weight'     => ( $settings['chart_title_typography'] && $settings['chart_title_font_weight'] ) ? $settings['chart_title_font_weight'] : '',
					'lineHeight' => ( $settings['chart_title_typography'] && $settings['chart_title_line_height']['size'] ) ? $settings['chart_title_line_height']['size'] . $settings['chart_title_line_height']['unit'] : '',
				],
				'padding' => [
					'bottom' => 20,
				],
			];

			if ( 'top' !== $settings['chart_title_position'] ) {
				$options['plugins']['title']['position'] = $settings['chart_title_position'];
			}

			if ( 'center' !== $settings['chart_title_align'] ) {
				$options['plugins']['title']['align'] = $settings['chart_title_align'];
			}
		}

		if ( ! empty( $settings['show_legend'] ) && 'yes' === $settings['show_legend'] ) {
			$options['plugins']['legend'] = [
				'position' => $settings['legend_position'] ?? '',
				'align'    => $settings['legend_align'] ?? '',
				'reverse'  => ( 'yes' === $settings['legend_reverse'] ),
				'labels'   => [
					'color' => $settings['legend_color'] ?? '',
					'font'  => [
						'size'       => ( $settings['legend_typography'] && $settings['legend_size']['size'] ) ? $settings['legend_size']['size'] : 12,
						'style'      => ( $settings['legend_typography'] && $settings['legend_font_style'] ) ? $settings['legend_font_style'] : '',
						'weight'     => ( $settings['legend_typography'] && $settings['legend_font_weight'] ) ? $settings['legend_font_weight'] : '',
						'lineHeight' => ( $settings['legend_typography'] && $settings['legend_line_height']['size'] ) ? $settings['legend_line_height']['size'] . $settings['legend_line_height']['unit'] : '',
					],
				],
			];
		} else {
			$options['plugins']['legend'] = [ 'display' => false ];
		}

		if ( ! empty( $settings['chart_animation'] ) ) {
			$options['animation'] = [
				'duration' => $settings['chart_animation_duration']['size'] ?? null,
				'easing'   => $settings['chart_animation'],
				'loop'     => ! empty( $settings['chart_animation_loop'] ),
			];
		}

		if ( ! empty( $settings['show_tooltip'] ) && 'yes' === $settings['show_tooltip'] ) {
			$options['interaction']['mode'] = $settings['interaction_mode'];

			$options['plugins']['tooltip'] = [
				'backgroundColor' => $settings['tooltip_background_color'] ?? '',
				'borderColor'     => $settings['tooltip_border_color'] ?? '',
				'borderWidth'     => ( '' !== $settings['tooltip_border_width']['size'] ) ? $settings['tooltip_border_width']['size'] : 0,
				'cornerRadius'    => ( '' !== $settings['tooltip_border_radius']['size'] ) ? $settings['tooltip_border_radius']['size'] : 6,
				'padding'         => ( $settings['tooltip_padding'] ) ? $settings['tooltip_padding'] : 6,
				'titleColor'      => $settings['tooltip_title_color'] ?? '',
				'bodyColor'       => $settings['tooltip_body_color'] ?? '',
				'titleFont'       => [ 'size' => $settings['tooltip_title_font_size']['size'] ?? null ],
				'bodyFont'        => [ 'size' => $settings['tooltip_body_font_size']['size'] ?? null ],
			];

			if ( ! empty( $settings['tooltip_event'] ) && 'click' === $settings['tooltip_event'] ) {
				$options['plugins']['tooltip']['events'] = ['click'];
				$options['events'] = ['click'];
			}
		} else {
			$options['plugins']['tooltip'] = [ 'enabled' => false ];
		}

		if ( 'yes' !== $settings['maintain_aspect_ratio'] ) {
			$options['maintainAspectRatio'] = false;
		}

		return $options;
	}

	protected function bubble_chart_data_array( $bubble_data ) {
		$bubble_data = trim( $bubble_data );
		$matches = [];
		preg_match_all( '#\[([^\]]+)\]#U', $bubble_data, $matches );

		if ( empty( $matches[1] ) ) {
			return [];
		}

		$bubble = [];
		foreach ( $matches[1] as $item ) {
			$values = explode( ',', trim( $item, '[] ' ) );
			if ( 3 !== count( $values ) ) {
				continue;
			}

			$bubble[] = (object) [
				'x' => floatval( $values[0] ),
				'y' => floatval( $values[1] ),
				'r' => floatval( $values[2] ),
			];
		}

		return $bubble;
	}
}
