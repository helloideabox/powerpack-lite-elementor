<?php
namespace PowerpackElementsLite\Modules\ImageComparison\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Control_Media;
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
		$this->register_content_help_docs_controls();

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
				'label'             => esc_html__( 'Before Image', 'powerpack' ),
			]
		);

		$this->add_control(
			'before_label',
			[
				'label'             => esc_html__( 'Label', 'powerpack' ),
				'type'              => Controls_Manager::TEXT,
				'default'           => esc_html__( 'Before', 'powerpack' ),
				'dynamic'           => array(
					'active' => true,
				),
			]
		);

		$this->add_control(
			'before_image',
			[
				'label'             => esc_html__( 'Image', 'powerpack' ),
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
				'label'             => esc_html__( 'After Image', 'powerpack' ),
			]
		);

		$this->add_control(
			'after_label',
			[
				'label'             => esc_html__( 'Label', 'powerpack' ),
				'type'              => Controls_Manager::TEXT,
				'default'           => esc_html__( 'After', 'powerpack' ),
				'dynamic'           => array(
					'active' => true,
				),
			]
		);

		$this->add_control(
			'after_image',
			[
				'label'             => esc_html__( 'Image', 'powerpack' ),
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
				'label'             => esc_html__( 'Settings', 'powerpack' ),
			]
		);

		$this->add_control(
			'visible_ratio',
			[
				'label'                 => esc_html__( 'Visible Ratio', 'powerpack' ),
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
				'label'                 => esc_html__( 'Orientation', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'horizontal',
				'options'               => [
					'vertical'      => esc_html__( 'Vertical', 'powerpack' ),
					'horizontal'    => esc_html__( 'Horizontal', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'move_slider',
			[
				'label'                 => esc_html__( 'Move Slider', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'drag',
				'options'               => [
					'drag'          => esc_html__( 'Drag', 'powerpack' ),
					'mouse_move'    => esc_html__( 'Mouse Move', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'overlay',
			[
				'label'             => esc_html__( 'Overlay', 'powerpack' ),
				'type'              => Controls_Manager::SWITCHER,
				'default'           => 'yes',
				'label_on'          => esc_html__( 'Show', 'powerpack' ),
				'label_off'         => esc_html__( 'Hide', 'powerpack' ),
				'return_value'      => 'yes',
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Image_Comparison' );

		if ( ! empty( $help_docs ) ) {

			/**
			 * Content Tab: Help Docs
			 *
			 * @since 1.4.8
			 * @access protected
			 */
			$this->start_controls_section(
				'section_help_docs',
				[
					'label' => esc_html__( 'Help Docs', 'powerpack' ),
				]
			);

			$hd_counter = 1;
			foreach ( $help_docs as $hd_title => $hd_link ) {
				$this->add_control(
					'help_doc_' . $hd_counter,
					[
						'type'            => Controls_Manager::RAW_HTML,
						'raw'             => sprintf( '%1$s ' . $hd_title . ' %2$s', '<a href="' . $hd_link . '" target="_blank" rel="noopener">', '</a>' ),
						'content_classes' => 'pp-editor-doc-links',
					]
				);

				$hd_counter++;
			}

			$this->end_controls_section();
		}
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
				'label'             => esc_html__( 'Overlay', 'powerpack' ),
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
				'label'             => esc_html__( 'Normal', 'powerpack' ),
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
				'label'             => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'overlay_background_hover',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .pp-image-comparison-overlay:hover',
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
				'label'             => esc_html__( 'Handle', 'powerpack' ),
				'tab'               => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'handle_icon',
			array(
				'label'                  => esc_html__( 'Choose Icon', 'powerpack' ),
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
				'label'             => esc_html__( 'Icon Size', 'powerpack' ),
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
				'label'             => esc_html__( 'Width', 'powerpack' ),
				'type'              => Controls_Manager::SLIDER,
				'size_units'        => [ 'px', 'em', 'rem', 'custom' ],
				'default'           => [
					'size' => 42,
					'unit' => 'px',
				],
				'range'             => [
					'px' => [
						'min' => 20,
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
				'label'             => esc_html__( 'Height', 'powerpack' ),
				'type'              => Controls_Manager::SLIDER,
				'size_units'        => [ 'px', 'em', 'rem', 'custom' ],
				'default'           => [
					'size' => 42,
					'unit' => 'px',
				],
				'range'             => [
					'px' => [
						'min' => 20,
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
				'label'             => esc_html__( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'handle_icon_color',
			[
				'label'             => esc_html__( 'Icon Color', 'powerpack' ),
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
				'label'             => esc_html__( 'Border', 'powerpack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'selector'          => '{{WRAPPER}} .pp-comparison-handle',
				'separator'         => 'before',
			]
		);

		$this->add_control(
			'handle_border_radius',
			[
				'label'             => esc_html__( 'Border Radius', 'powerpack' ),
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
				'label'             => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'handle_icon_color_hover',
			[
				'label'             => esc_html__( 'Icon Color', 'powerpack' ),
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
				'label'             => esc_html__( 'Border Color', 'powerpack' ),
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
				'label'             => esc_html__( 'Divider', 'powerpack' ),
				'tab'               => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'             => esc_html__( 'Color', 'powerpack' ),
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
				'label'             => esc_html__( 'Width', 'powerpack' ),
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
				'label'             => esc_html__( 'Label', 'powerpack' ),
				'tab'               => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'label_horizontal_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'default'               => 'top',
				'options'               => [
					'top'          => [
						'title'    => esc_html__( 'Top', 'powerpack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => esc_html__( 'Middle', 'powerpack' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => esc_html__( 'Bottom', 'powerpack' ),
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
				'label'                 => esc_html__( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'           => [
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'            => [
						'title' => esc_html__( 'Right', 'powerpack' ),
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
				'label'             => esc_html__( 'Align', 'powerpack' ),
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
				'label'             => esc_html__( 'Typography', 'powerpack' ),
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
				'label'             => esc_html__( 'Before', 'powerpack' ),
			]
		);

		$this->add_control(
			'label_text_color_before',
			[
				'label'             => esc_html__( 'Text Color', 'powerpack' ),
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
				'label'             => esc_html__( 'Background Color', 'powerpack' ),
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
				'label'             => esc_html__( 'Border', 'powerpack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'selector'          => '{{WRAPPER}} .pp-comparison-label-before > span',
			]
		);

		$this->add_control(
			'label_border_radius',
			[
				'label'             => esc_html__( 'Border Radius', 'powerpack' ),
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
				'label'             => esc_html__( 'After', 'powerpack' ),
			]
		);

		$this->add_control(
			'label_text_color_after',
			[
				'label'             => esc_html__( 'Text Color', 'powerpack' ),
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
				'label'             => esc_html__( 'Background Color', 'powerpack' ),
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
				'label'             => esc_html__( 'Border', 'powerpack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'selector'          => '{{WRAPPER}} .pp-comparison-label-after > span',
			]
		);

		$this->add_control(
			'label_border_radius_after',
			[
				'label'             => esc_html__( 'Border Radius', 'powerpack' ),
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
				'label'             => esc_html__( 'Padding', 'powerpack' ),
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
	protected function get_image_src( $position ) {
		$settings = $this->get_settings_for_display();

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
	 * Render image comparison widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$widget_options = [
			'visible_ratio'      => ! empty( $settings['visible_ratio']['size'] ) ? $settings['visible_ratio']['size'] : '0.5',
			'orientation'        => ( $settings['orientation'] ) ? $settings['orientation'] : 'vertical',
			'slider_on_hover'    => 'mouse_move' === $settings['move_slider'] ? true : false,
			'slider_with_handle' => 'drag' === $settings['move_slider'] ? true : false,
			'slider_with_click'  => 'mouse_click' === $settings['move_slider'] ? true : false,
		];

		$this->add_render_attribute( 'image-comparison', [
			'class'         => [ 'pp-image-comparison', 'pp-image-comparison-' . $settings['orientation'] ],
			'id'            => 'pp-image-comparison-' . esc_attr( $this->get_id() ),
			'data-settings' => wp_json_encode( $widget_options ),
		] );
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'image-comparison' ) ); ?>>
			<?php
			if ( ! empty( $settings['before_image'] ) ) :
				echo '<div class="pp-before-image">';
				$img_url = $this->get_image_src('before');

				$this->add_render_attribute( 'before-image', 'src', esc_url( $img_url ) );
				$this->add_render_attribute( 'before-image', 'alt', Control_Media::get_image_alt( $settings['before_image'] ) );
				$this->add_render_attribute( 'before-image', 'title', Control_Media::get_image_title( $settings['before_image'] ) );
				$this->add_render_attribute( 'before-image', 'class', 'pp-before-img' );

				printf( '<img %s />', $this->get_render_attribute_string( 'before-image' ) );
				echo '</div>';
			endif;

			if ( ! empty( $settings['after_image'] ) ) :
				echo '<div class="pp-after-image">';
				$img_url = $this->get_image_src('after');

				$this->add_render_attribute( 'after-image', 'src', esc_url( $img_url ) );
				$this->add_render_attribute( 'after-image', 'alt', Control_Media::get_image_alt( $settings['after_image'] ) );
				$this->add_render_attribute( 'after-image', 'title', Control_Media::get_image_title( $settings['after_image'] ) );
				$this->add_render_attribute( 'after-image', 'class', 'pp-after-img' );

				printf( '<img %s />', $this->get_render_attribute_string( 'after-image' ) );
				echo '</div>';
			endif;

			echo '<div class="pp-comparison-handle">';
			if ( ! empty( $settings['handle_icon']['value'] ) ) {
				if ( 'horizontal' === $settings['orientation'] ) {
					$after_icon = $settings['handle_icon'];
					$before_icon = str_replace( 'right', 'left', $settings['handle_icon'] );
				} else {
					$after_icon = $settings['handle_icon'];
					$after_icon = str_replace( 'right', 'down', $settings['handle_icon'] );
					$before_icon = str_replace( 'right', 'up', $settings['handle_icon'] );
				}

				Icons_Manager::render_icon( $before_icon, [ 'aria-hidden' => 'true' ] );
				Icons_Manager::render_icon( $after_icon, [ 'aria-hidden' => 'true' ] );
			}
			echo '</div>';

			if ( 'yes' === $settings['overlay'] ) {
				echo '<div class="pp-image-comparison-overlay">';
			}
				if ( '' !== $settings['before_label'] ) {
					echo '<div class="pp-comparison-label pp-comparison-label-before">';
						echo '<span>'. esc_html( $settings['before_label'] ) .'</span>';
					echo '</div>';
				}

				if ( '' !== $settings['after_label'] ) {
					echo '<div class="pp-comparison-label pp-comparison-label-after">';
						echo '<span>'. esc_html( $settings['after_label'] ) .'</span>';
					echo '</div>';
				}
			if ( 'yes' === $settings['overlay'] ) {
				echo '</div>';
			}
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
		#>
		<div class="pp-image-comparison pp-image-comparison-{{ settings.orientation }}" data-settings='{ "visible_ratio":{{ visible_ratio }},"orientation":"{{ settings.orientation }}","before_label":"{{ settings.before_label }}","after_label":"{{ settings.after_label }}","slider_on_hover":{{ slider_on_hover }},"slider_with_handle":{{ slider_with_handle }},"slider_with_click":{{ slider_with_click }} }'>
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
					<img src="{{ _.escape( before_image_url ) }}" class="pp-before-img">
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
					<img src="{{ _.escape( after_image_url ) }}" class="pp-after-img">
				</div>
			<# } #>

			<div class="pp-comparison-handle">
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
					<div class="pp-comparison-label pp-comparison-label-before">
						<span>{{{ settings.before_label }}}</span>
					</div>
				<# } #>

				<# if ( settings.after_label != '' ) { #>
					<div class="pp-comparison-label pp-comparison-label-after">
						<span>{{{ settings.after_label }}}</span>
					</div>
				<# } #>
			<# if ( 'yes' === settings.overlay ) { #>
				</div>
			<# } #>
		</div>
		<?php
	}
}
