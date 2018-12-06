<?php
namespace PowerpackElementsLite\Modules\InfoTable\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Info Table Widget
 */
class Info_Table extends Powerpack_Widget {

	/**
	 * Retrieve info table widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pp-info-table';
	}

	/**
	 * Retrieve info table widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Info Table', 'power-pack' );
	}

	/**
	 * Retrieve the list of categories the info table widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'power-pack' ];
	}

	/**
	 * Retrieve info table widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'ppicon-info-table power-pack-admin-icon';
	}

	/**
	 * Register info table widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		/*-----------------------------------------------------------------------------------*/
		/*	CONTENT TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Content Tab: Info Table
		 */
		$this->start_controls_section(
			'section_price_menu',
			[
				'label' => __( 'Info Table', 'power-pack' ),
			]
		);
		$this->add_control(
			'icon_type',
			[
				'label'       => esc_html__( 'Type', 'power-pack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'none' => [
						'title' => esc_html__( 'None', 'power-pack' ),
						'icon'  => 'fa fa-ban',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'power-pack' ),
						'icon'  => 'fa fa-gear',
					],
					'image' => [
						'title' => esc_html__( 'Image', 'power-pack' ),
						'icon'  => 'fa fa-picture-o',
					],
					'text' => [
						'title' => esc_html__( 'Text', 'power-pack' ),
						'icon'  => 'fa fa-font',
					],
				],
				'default'     => 'icon',
			]
		);
		$this->add_control(
			'icon',
			[
				'label'     => __( 'Icon', 'power-pack' ),
				'type'      => Controls_Manager::ICON,
				'default'   => 'fa fa-diamond',
				'condition' => [
					'icon_type' => 'icon',
				],
			]
		);
		$this->add_control(
			'image',
			[
				'label'     => __( 'Image', 'power-pack' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);
		$this->add_control(
			'icon_text',
			[
				'label'     => __( 'Icon Text', 'power-pack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '1',
				'condition' => [
					'icon_type' => 'text',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Content
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'power-pack' ),
			]
		);
		$this->add_control(
			'heading',
			[
				'label'   => __( 'Title', 'power-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Title', 'power-pack' ),
			]
		);
		$this->add_control(
			'title_html_tag',
			[
				'label'   => __( 'Title HTML Tag', 'power-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => [
					'h1'   => __( 'H1', 'power-pack' ),
					'h2'   => __( 'H2', 'power-pack' ),
					'h3'   => __( 'H3', 'power-pack' ),
					'h4'   => __( 'H4', 'power-pack' ),
					'h5'   => __( 'H5', 'power-pack' ),
					'h6'   => __( 'H6', 'power-pack' ),
					'div'  => __( 'div', 'power-pack' ),
					'span' => __( 'span', 'power-pack' ),
					'p'    => __( 'p', 'power-pack' ),
				],
			]
		);
		$this->add_control(
			'sub_heading',
			[
				'label'   => __( 'Subtitle', 'power-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Subtitle', 'power-pack' ),
			]
		);
		$this->add_control(
			'sub_title_html_tag',
			[
				'label'     => __( 'Subtitle HTML Tag', 'power-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h5',
				'options'   => [
					'h1'   => __( 'H1', 'power-pack' ),
					'h2'   => __( 'H2', 'power-pack' ),
					'h3'   => __( 'H3', 'power-pack' ),
					'h4'   => __( 'H4', 'power-pack' ),
					'h5'   => __( 'H5', 'power-pack' ),
					'h6'   => __( 'H6', 'power-pack' ),
					'div'  => __( 'div', 'power-pack' ),
					'span' => __( 'span', 'power-pack' ),
					'p'    => __( 'p', 'power-pack' ),
				],
				'condition' => [
					'sub_heading!' => '',
				],
			]
		);
		$this->add_control(
			'description',
			[
				'label'   => __( 'Description', 'power-pack' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => __( 'Enter description', 'power-pack' ),
			]
		);
		$this->add_responsive_control(
			'sale_badge',
			[
				'label'     => __( 'Sale Badge', 'power-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'show'    => [
						'title' => __( 'Show', 'power-pack' ),
						'icon'  => 'fa fa-eye',
					],
					'hide'  => [
						'title' => __( 'Hide', 'power-pack' ),
						'icon'  => 'fa fa-eye-slash',
					],
				],
				'default'   => 'show',
			]
		);
		$this->add_control(
			'sale_badge_text',
			[
				'label'   => __( 'Sale Badge Text', 'power-pack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'Sale', 'power-pack' ),
				'condition' => [
					'sale_badge' => 'show',
				],
			]
		);
		$this->end_controls_section();
		/**
		 * Content Tab: Link
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_link',
			[
				'label' => __( 'Link', 'power-pack' ),
			]
		);
		$this->add_control(
			'link_type',
			[
				'label'   => __( 'Link Type', 'power-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'  => __( 'None', 'power-pack' ),
					'box'   => __( 'Box', 'power-pack' ),
					'title' => __( 'Title', 'power-pack' ),
					'button'    => __( 'Button', 'power-pack' ),
				],
			]
		);
		$this->add_control(
			'link',
			[
				'label'       => __( 'Link', 'power-pack' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://www.your-link.com',
				'default'     => [
					'url' => '#',
				],
				'condition'   => [
					'link_type!' => 'none',
				],
			]
		);
		$this->add_control(
			'button_text',
			[
				'label'     => __( 'Button Text', 'power-pack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Get Started', 'power-pack' ),
				'condition' => [
					'link_type' => 'button',
				],
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label'    => __( 'Button Icon', 'power-pack' ),
				'type'     => Controls_Manager::ICON,
				'default'  => '',
				'condition' => [
					'link_type' => 'button',
				],
			]
		);
		$this->add_control(
			'button_icon_position',
			[
				'label'     => __( 'Icon Position', 'power-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after',
				'options'   => [
					'after'  => __( 'After', 'power-pack' ),
					'before' => __( 'Before', 'power-pack' ),
				],
				'condition' => [
					'link_type'    => 'button',
					'button_icon!' => '',
				],
			]
		);
		$this->end_controls_section();
		/*-----------------------------------------------------------------------------------*/
		/*	STYLE TAB
		/*-----------------------------------------------------------------------------------*/
		/**
		 * Style Tab: Info Table
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_table_style',
			[
				'label' => __( 'Info Table', 'power-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label'     => __( 'Alignment', 'power-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'  => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'   => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'center',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->start_controls_tabs( 'tabs_info_table_style' );
		/**
		 * Style Control Tab: Normal
		 * -------------------------------------------------
		 */
			$this->start_controls_tab(
				'tab_info_table_normal',
				[
					'label' => __( 'Normal', 'power-pack' ),
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'info_table_bg',
					'label'    => __( 'Background', 'power-pack' ),
					'types'    => [ 'none','classic','gradient' ],
					'selector' => '{{WRAPPER}} .pp-info-table-container',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'info_table_border',
					'label'       => __( 'Border', 'power-pack' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .pp-info-table-container',
				]
			);
			$this->add_control(
				'info_table_border_radius',
				[
					'label'      => __( 'Border Radius', 'power-pack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .pp-info-table-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'info_table_shadow',
					'selector' => '{{WRAPPER}} .pp-info-table-container',
				]
			);
			$this->end_controls_tab();
		/**
		 * Style Control Tab: Hover
		 * -------------------------------------------------
		 */
			$this->start_controls_tab(
				'tab_info_table_hover',
				[
					'label' => __( 'Hover', 'power-pack' ),
				]
			);
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'     => 'info_table_bg_hover',
					'label'    => __( 'Background', 'power-pack' ),
					'types'    => [ 'none','classic','gradient' ],
					'selector' => '{{WRAPPER}} .pp-info-table-container:hover',
				]
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'info_table_border_hover',
					'label'       => __( 'Border', 'power-pack' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .pp-info-table-container:hover',
				]
			);
			$this->add_control(
				'info_table_border_radius_hover',
				[
					'label'      => __( 'Border Radius', 'power-pack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .pp-info-table-container:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'info_table_shadow_hover',
					'selector' => '{{WRAPPER}} .pp-info-table-container:hover',
				]
			);
			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control(
				'info_table_padding',
				[
					'label'      => __( 'Padding', 'power-pack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'separator'  => 'before',
					'selectors'  => [
						'{{WRAPPER}} .pp-info-table-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

		/**
		 * Style Tab: Title
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_table_title_style',
			[
				'label' => __( 'Title', 'power-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'title_box_bg_color',
			[
				'label'     => __( 'Background Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-title-wrap' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'power-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .pp-info-table-title',
			]
		);
		$this->add_responsive_control(
			'title_box_padding',
			[
				'label'      => __( 'Padding', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-title-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => __( 'Margin Bottom', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 20,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%' => [
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_control(
			'subtitle_heading',
			[
				'label'     => __( 'Sub Title', 'power-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'sub_heading!' => '',
				],
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => __( 'Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => [
					'sub_heading!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'subtitle_typography',
				'label'     => __( 'Typography', 'power-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => [
					'sub_heading!' => '',
				],
				'selector'  => '{{WRAPPER}} .pp-info-table-subtitle',
			]
		);

		$this->add_responsive_control(
			'subtitle_margin',
			[
				'label'      => __( 'Margin Bottom', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 20,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%' => [
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%' ],
				'condition'  => [
					'sub_heading!' => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->end_controls_section();

		/**
		 * Style Tab: Icon / Image Style
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_table_icon_style',
			[
				'label'     => __( 'Icon / Image Style', 'power-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'icon_type!' => 'none',
				],
			]
		);
		$this->add_control(
			'icon_box_bg_color',
			[
				'label'     => __( 'Box Background Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-icon-container' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'icon_box_padding',
			[
				'label'      => __( 'Box Padding', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-icon-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => __( 'Icon Size', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'condition'  => [
					'icon_type' => 'icon',
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'icon_box_size',
			[
				'label'      => __( 'Icon Box Size', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 5,
						'max'  => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'condition'  => [
					'icon_type' => 'icon',
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-icon-wrap' => 'height: calc({{SIZE}}{{UNIT}} * 2); width: calc({{SIZE}}{{UNIT}} * 2);',
				],
			]
		);
		$this->add_responsive_control(
			'icon_img_width',
			[
				'label'      => __( 'Image Width', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 25,
						'max'  => 600,
						'step' => 1,
					],
					'%' => [
						'min'  => 25,
						'max'  => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-icon img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'icon_type' => 'image',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'icon_typography',
				'label'     => __( 'Typography', 'power-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'condition' => [
					'icon_type' => 'text',
				],
				'selector'  => '{{WRAPPER}} .pp-info-table-icon',
			]
		);
		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label' => __( 'Normal', 'power-pack' ),
			]
		);
		$this->add_control(
			'icon_bg_color_normal',
			[
				'label'     => __( 'Background Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-icon-wrap' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_color_normal',
			[
				'label'     => __( 'Icon Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'icon_type!' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'icon_border',
				'label'       => __( 'Border', 'power-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'condition'   => [
					'icon_type!' => 'none',
				],
				'selector'    => '{{WRAPPER}} .pp-info-table-icon-container',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'      => __( 'Border Radius', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition'  => [
					'icon_type!' => 'none',
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-icon-wrap, {{WRAPPER}} .pp-info-table-icon-wrap img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_rotation',
			[
				'label'      => __( 'Icon Rotation', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 360,
						'step' => 1,
					],
				],
				'size_units' => '',
				'condition'  => [
					'icon_type!' => 'none',
					'icon_type!' => 'text',
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-icon span' => 'transform: rotate( {{SIZE}}deg );',
					'{{WRAPPER}} .pp-info-table-icon img' => 'transform: rotate( {{SIZE}}deg );',
				],
			]
		);
		$this->add_responsive_control(
			'img_inside_padding',
			[
				'label'      => __( 'Inside Spacing', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'condition'  => [
					'icon_type!' => 'icon',
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab(
			'tab_icon_hover',
			[
				'label' => __( 'Hover', 'power-pack' ),
			]
		);

		$this->add_control(
			'icon_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => [
					'icon_type!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-icon-wrap:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_border_color_hover',
			[
				'label'     => __( 'Border Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'condition' => [
					'icon_type!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-icon-wrap:hover .fa' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label'     => __( 'Icon Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-icon-wrap:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'icon_type!' => 'image',
				],
			]
		);

		$this->add_control(
			'hover_animation_icon',
			[
				'label' => __( 'Icon Animation', 'power-pack' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Description
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_description_style',
			[
				'label'     => __( 'Description', 'power-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'description!' => '',
				],
			]
		);
		$this->add_control(
			'description_bg_color',
			[
				'label'     => __( 'Background Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-description' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'description_color',
			[
				'label'     => __( 'Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-description' => 'color: {{VALUE}}',
				],
				'condition' => [
					'description!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'description_typography',
				'label'     => __( 'Typography', 'power-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-info-table-description',
				'condition' => [
					'description!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'description_padding',
			[
				'label'      => __( 'Padding', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'description_margin',
			[
				'label'      => __( 'Margin Bottom', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 20,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
					'%' => [
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->end_controls_section();

		/**
		 * Style Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_table_button_style',
			[
				'label'     => __( 'Button', 'power-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'     => __( 'Size', 'power-pack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'md',
				'options'   => [
					'xs' => __( 'Extra Small', 'power-pack' ),
					'sm' => __( 'Small', 'power-pack' ),
					'md' => __( 'Medium', 'power-pack' ),
					'lg' => __( 'Large', 'power-pack' ),
					'xl' => __( 'Extra Large', 'power-pack' ),
				],
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label'     => __( 'Normal', 'power-pack' ),
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_bg_color_normal',
			[
				'label'     => __( 'Background Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-button' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_text_color_normal',
			[
				'label'     => __( 'Text Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-button' => 'color: {{VALUE}}',
				],
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_border_normal',
				'label'       => __( 'Border', 'power-pack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-info-table-button',
				'condition'   => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => __( 'Border Radius', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'button_typography',
				'label'     => __( 'Typography', 'power-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-info-table-button',
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => __( 'Padding', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'button_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-info-table-button',
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'info_table_button_icon_heading',
			[
				'label'     => __( 'Button Icon', 'power-pack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'link_type'    => 'button',
					'button_icon!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'button_icon_margin',
			[
				'label'       => __( 'Margin', 'power-pack' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => [ 'px', '%' ],
				'placeholder' => [
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				],
				'condition'   => [
					'link_type'    => 'button',
					'button_icon!' => '',
				],
				'selectors'   => [
					'{{WRAPPER}} .pp-info-table .pp-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label'     => __( 'Hover', 'power-pack' ),
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label'     => __( 'Background Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-button:hover' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label'     => __( 'Text Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-button:hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label'     => __( 'Border Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-button:hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_animation',
			[
				'label'     => __( 'Animation', 'power-pack' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'button_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .pp-info-table-button:hover',
				'condition' => [
					'link_type'    => 'button',
					'button_text!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
		/**
		 * Style Tab: Sale Badge
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_sale_badge_style',
			[
				'label'     => __( 'Sale Badge', 'power-pack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'sale_badge' => 'show',
				],
			]
		);
		$this->add_control(
			'sale_badge_bg_color',
			[
				'label'     => __( 'Background Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-sale-badge' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-info-table-sale-badge.right:after' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .pp-info-table-sale-badge.left:after' => 'border-right-color: {{VALUE}}',
				],
				'condition' => [
					'sale_badge' => 'show',
				],
			]
		);
		$this->add_control(
			'sale_badge_color',
			[
				'label'     => __( 'Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => [
					'{{WRAPPER}} .pp-info-table-sale-badge' => 'color: {{VALUE}}',
				],
				'condition' => [
					'sale_badge' => 'show',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'sale_badge_typography',
				'label'     => __( 'Typography', 'power-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-info-table-sale-badge',
				'condition' => [
					'sale_badge' => 'show',
				],
			]
		);

		$this->add_responsive_control(
			'sale_badge_padding',
			[
				'label'      => __( 'Padding', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator'  => 'before',
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-sale-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'sale_badge' => 'show',
				],
			]
		);
		$this->add_responsive_control(
			'sale_badge_width',
			[
				'label'      => __( 'Width', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min'  => 25,
						'max'  => 600,
						'step' => 1,
					],
					'%' => [
						'min'  => 25,
						'max'  => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-sale-badge' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'sale_badge' => 'show',
				],
			]
		);
		$this->add_responsive_control(
			'sale_badge_align',
			[
				'label'     => __( 'Alignment', 'power-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'right'   => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'right',
			]
		);
		$this->add_responsive_control(
			'sale_badge_position',
			[
				'label'      => __( 'Position From Top', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'%' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'size_units' => [ '%', '' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-sale-badge' => 'top: {{SIZE}}%',
				],
				'condition' => [
					'sale_badge' => 'show',
				],
			]
		);
		$this->add_control(
			'sale_badge_border_radius',
			[
				'label'      => __( 'Border Radius', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-table-sale-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render info table widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		$this->add_render_attribute( 'info-table', 'class', 'pp-info-table' );
		$this->add_render_attribute( 'info-table-container', 'class', 'pp-info-table-container' );
		$this->add_render_attribute( 'info-table-link', 'class', 'pp-info-table-link' );
		$this->add_render_attribute( 'title-container', 'class', 'pp-info-table-title-container' );

		$pp_if_html_tag = 'div';
		$pp_if_html_tag_a = 'div';
		$pp_title_html_tag = 'div';
		$pp_button_html_tag = 'div';

		$this->add_inline_editing_attributes( 'icon_text', 'none' );
		$this->add_render_attribute( 'icon_text', 'class', 'pp-icon-text' );
		$this->add_inline_editing_attributes( 'heading', 'none' );
		$this->add_render_attribute( 'heading', 'class', 'pp-info-table-title' );
		$this->add_inline_editing_attributes( 'sub_heading', 'none' );
		$this->add_render_attribute( 'sub_heading', 'class', 'pp-info-table-subtitle' );
		$this->add_inline_editing_attributes( 'description', 'basic' );
		$this->add_render_attribute( 'description', 'class', 'pp-info-table-description' );
		$this->add_render_attribute( 'icon', 'class', 'pp-info-table-icon' );
		$this->add_inline_editing_attributes( 'button_text', 'none' );
		$this->add_render_attribute( 'button_text', 'class', 'pp-button-text' );

		$this->add_render_attribute( 'info-table-button', 'class', [
				'pp-info-table-button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			]
		);

		if ( $settings['button_animation'] ) {
			$this->add_render_attribute( 'info-table-button', 'class', 'elementor-animation-' . $settings['button_animation'] );
		}

		if ( $settings['hover_animation_icon'] ) {
			$this->add_render_attribute( 'icon', 'class', 'elementor-animation-' . $settings['hover_animation_icon'] );
		}

		if ( 'none' != $settings['link_type'] ) {

			if ( ! empty( $settings['link']['url'] ) ) {

				if ( 'box' == $settings['link_type'] ) {

					$pp_if_html_tag_a = 'a';
					$this->add_render_attribute( 'info-table-container-a', 'href', $settings['link']['url'] );
					if ( $settings['link']['is_external'] ) {
						$this->add_render_attribute( 'info-table-container-a', 'target', '_blank' );
					}
					if ( $settings['link']['nofollow'] ) {
						$this->add_render_attribute( 'info-table-container-a', 'rel', 'nofollow' );
					}
				} elseif ( 'title' == $settings['link_type'] ) {

					$pp_title_html_tag = 'a';
					$this->add_render_attribute( 'title-container', 'href', $settings['link']['url'] );

					if ( $settings['link']['is_external'] ) {
						$this->add_render_attribute( 'title-container', 'target', '_blank' );
					}
					if ( $settings['link']['nofollow'] ) {
						$this->add_render_attribute( 'title-container', 'rel', 'nofollow' );
					}
				} elseif ( 'button' == $settings['link_type'] ) {

					$pp_button_html_tag = 'a';

					$this->add_render_attribute( 'info-table-button', 'href', $settings['link']['url'] );

					if ( $settings['link']['is_external'] ) {
						$this->add_render_attribute( 'info-table-button', 'target', '_blank' );
					}
					if ( $settings['link']['nofollow'] ) {
						$this->add_render_attribute( 'info-table-button', 'rel', 'nofollow' );
					}
				} // End if().
			} // End if().
		} // End if().
		?>
		<<?php echo $pp_if_html_tag . ' ' . $this->get_render_attribute_string( 'info-table-container' ); ?>>
		<?php if ( $pp_if_html_tag_a ) { ?><<?php echo $pp_if_html_tag_a . ' ' . $this->get_render_attribute_string( 'info-table-container-a' ); ?>> <?php } ?>
			<div <?php echo $this->get_render_attribute_string( 'info-table' ); ?>>
				<?php if ( 'show' == $settings['sale_badge'] && ! empty( $settings['sale_badge_text'] ) ) {
					if ( 'right' == $settings['sale_badge_align'] ) { ?>
						<div class='pp-info-table-sale-badge right'>
							<?php } elseif ( 'left' == $settings['sale_badge_align'] ) { ?>
						<div class='pp-info-table-sale-badge left'>
					<?php } ?>
							<p><?php echo $this->parse_text_editor( nl2br( $settings['sale_badge_text'] ) ); ?></p>
						</div>
				<?php } ?>
				<div class="pp-info-table-title-wrap">
				<?php
				if ( ! empty( $settings['heading'] ) ) {
					echo '<' . $pp_title_html_tag . ' ' . $this->get_render_attribute_string( 'title-container' ) . '>';
					printf( '<%1$s %2$s>', $settings['title_html_tag'], $this->get_render_attribute_string( 'heading' ) );
					echo esc_attr( $settings['heading'] );
					printf( '</%1$s>', $settings['title_html_tag'] );
					echo '</' . $pp_title_html_tag . '>';
				}
				if ( ! empty( $settings['sub_heading'] ) ) {
					printf( '<%1$s %2$s>', $settings['sub_title_html_tag'], $this->get_render_attribute_string( 'sub_heading' ) );
					echo esc_attr( $settings['sub_heading'] );
					printf( '</%1$s>', $settings['sub_title_html_tag'] );
				}
				?>
				</div>

				<?php if ( 'none' != $settings['icon_type'] ) { ?>
					<div class="pp-info-table-icon-container">
						<div class="pp-info-table-icon-inner">
							<div class="pp-info-table-icon-wrap">
								<span <?php echo $this->get_render_attribute_string( 'icon' ); ?>>
									<?php if ( 'icon' == $settings['icon_type'] ) { ?>
										<span class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></span>
									<?php } elseif ( 'image' == $settings['icon_type'] ) { ?>
										<img src="<?php echo esc_url( $settings['image']['url'] ); ?>">
									<?php } elseif ( 'text' == $settings['icon_type'] ) { ?>
										<span class="pp-icon-text">
											<?php echo $settings['icon_text']; ?>
										</span>
									<?php } ?>
								</span>
							</div>
						</div>
					</div>
				<?php } ?>

				<?php if ( ! empty( $settings['description'] ) ) { ?>
					<div <?php echo $this->get_render_attribute_string( 'description' ); ?>>
						<?php echo $this->parse_text_editor( nl2br( $settings['description'] ) ); ?>
					</div>
				<?php } ?>
				<?php if ( 'button' == $settings['link_type'] ) { ?>
					<div class="pp-info-table-footer">
						<<?php echo $pp_button_html_tag . ' ' . $this->get_render_attribute_string( 'info-table-button' ); ?>>
							<?php if ( ! empty( $settings['button_icon'] ) && 'before' == $settings['button_icon_position'] ) { ?>
								<span class="pp-button-icon <?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></span>
							<?php } ?>
							<?php if ( ! empty( $settings['button_text'] ) ) { ?>
								<span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
									<?php echo esc_attr( $settings['button_text'] ); ?>
								</span>
							<?php } ?>
							<?php if ( ! empty( $settings['button_icon'] ) && 'after' == $settings['button_icon_position'] ) { ?>
								<span class="pp-button-icon <?php echo esc_attr( $settings['button_icon'] ); ?>" aria-hidden="true"></span>
							<?php } ?>
						</<?php echo $pp_button_html_tag; ?>>
					</div>
				<?php } ?>
			</div>
			<?php if ( $pp_if_html_tag_a ) { ?></<?php echo $pp_if_html_tag_a; ?>> <?php } ?>
		</<?php echo $pp_if_html_tag; ?>>
		<?php
	}
}