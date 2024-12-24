<?php
namespace PowerpackElementsLite\Modules\PromoBox\Widgets;

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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Promo Box Widget
 */
class Promo_Box extends Powerpack_Widget {

	/**
	 * Retrieve promo box widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Promo_Box' );
	}

	/**
	 * Retrieve promo box widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Promo_Box' );
	}

	/**
	 * Retrieve promo box widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Promo_Box' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.4.13.1
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Promo_Box' );
	}

	protected function is_dynamic_content(): bool {
		return false;
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
			'widget-pp-promo-box'
		];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! PP_Helper::is_feature_active( 'e_optimized_markup' );
	}

	/**
	 * Register promo box widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.3.2
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_content_controls();
		$this->register_content_icon_controls();
		$this->register_content_button_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_promo_box_controls();
		$this->register_style_overlay_controls();
		$this->register_style_content_controls();
		$this->register_style_icon_controls();
		$this->register_style_heading_controls();
		$this->register_style_heading_divider_controls();
		$this->register_style_subheading_controls();
		$this->register_style_subheading_divider_controls();
		$this->register_style_description_controls();
		$this->register_style_button_controls();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	CONTENT TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_content_content_controls() {
		/**
		 * Content Tab: Content
		 */
		$this->start_controls_section(
			'section_content',
			[
				'label'                 => esc_html__( 'Content', 'powerpack' ),
			]
		);

		$this->add_control(
			'heading',
			[
				'label'                 => esc_html__( 'Heading', 'powerpack' ),
				'label_block'           => true,
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Heading', 'powerpack' ),
			]
		);

		$this->add_control(
			'heading_html_tag',
			[
				'label'                => esc_html__( 'Heading HTML Tag', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'h4',
				'options'              => [
					'h1'     => esc_html__( 'H1', 'powerpack' ),
					'h2'     => esc_html__( 'H2', 'powerpack' ),
					'h3'     => esc_html__( 'H3', 'powerpack' ),
					'h4'     => esc_html__( 'H4', 'powerpack' ),
					'h5'     => esc_html__( 'H5', 'powerpack' ),
					'h6'     => esc_html__( 'H6', 'powerpack' ),
					'div'    => esc_html__( 'div', 'powerpack' ),
					'span'   => esc_html__( 'span', 'powerpack' ),
					'p'      => esc_html__( 'p', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'divider_heading_switch',
			[
				'label'                 => esc_html__( 'Heading Divider', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'On', 'powerpack' ),
				'label_off'             => esc_html__( 'Off', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'heading!' => '',
				],
			]
		);

		$this->add_control(
			'sub_heading',
			[
				'label'                 => esc_html__( 'Sub Heading', 'powerpack' ),
				'label_block'           => true,
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Sub heading', 'powerpack' ),
			]
		);

		$this->add_control(
			'sub_heading_html_tag',
			[
				'label'                => esc_html__( 'Sub Heading HTML Tag', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'h5',
				'options'              => [
					'h1'     => esc_html__( 'H1', 'powerpack' ),
					'h2'     => esc_html__( 'H2', 'powerpack' ),
					'h3'     => esc_html__( 'H3', 'powerpack' ),
					'h4'     => esc_html__( 'H4', 'powerpack' ),
					'h5'     => esc_html__( 'H5', 'powerpack' ),
					'h6'     => esc_html__( 'H6', 'powerpack' ),
					'div'    => esc_html__( 'div', 'powerpack' ),
					'span'   => esc_html__( 'span', 'powerpack' ),
					'p'      => esc_html__( 'p', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'divider_subheading_switch',
			[
				'label'                 => esc_html__( 'Sub Heading Divider', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'On', 'powerpack' ),
				'label_off'             => esc_html__( 'Off', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'sub_heading!' => '',
				],
			]
		);

		$this->add_control(
			'content',
			[
				'label'                 => esc_html__( 'Description', 'powerpack' ),
				'type'                  => Controls_Manager::TEXTAREA,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Enter promo box description', 'powerpack' ),
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_icon_controls() {
		/**
		 * Content Tab: Icon
		 */
		$this->start_controls_section(
			'section_promo_box_icon',
			[
				'label'                 => esc_html__( 'Icon', 'powerpack' ),
			]
		);

		$this->add_control(
			'icon_switch',
			[
				'label'                 => esc_html__( 'Show Icon', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'icon_type',
			[
				'label'                 => esc_html__( 'Icon Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'icon',
				'options'               => [
					'icon'      => esc_html__( 'Icon', 'powerpack' ),
					'image'     => esc_html__( 'Image', 'powerpack' ),
				],
				'condition'             => [
					'icon_switch'   => 'yes',
				],
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'                 => esc_html__( 'Choose', 'powerpack' ) . ' ' . esc_html__( 'Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICONS,
				'fa4compatibility'      => 'icon',
				'default'               => [
					'value'     => 'fas fa-gem',
					'library'   => 'fa-solid',
				],
				'condition'             => [
					'icon_switch'   => 'yes',
					'icon_type'     => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_image',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
				'type'                  => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'             => [
					'icon_switch'   => 'yes',
					'icon_type'     => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'image', // Usage: '{name}_size' and '{name}_custom_dimension', in this case 'image_size' and 'image_custom_dimension'.
				'default'               => 'full',
				'separator'             => 'none',
				'condition'             => [
					'icon_switch'   => 'yes',
					'icon_type'     => 'image',
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label'                 => esc_html__( 'Icon Position', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'above-title',
				'options'               => [
					'above-title'      => esc_html__( 'Above Title', 'powerpack' ),
					'below-title'      => esc_html__( 'Below Title', 'powerpack' ),
				],
				'condition'             => [
					'icon_switch'   => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_button_controls() {
		/**
		 * Content Tab: Button
		 */
		$this->start_controls_section(
			'section_promo_box_button',
			[
				'label'                 => esc_html__( 'Button', 'powerpack' ),
			]
		);

		$this->add_control(
			'button_switch',
			[
				'label'                 => esc_html__( 'Button', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'On', 'powerpack' ),
				'label_off'             => esc_html__( 'Off', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'                 => esc_html__( 'Button Text', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Get Started', 'powerpack' ),
				'condition'             => [
					'button_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label'                 => esc_html__( 'Link', 'powerpack' ),
				'type'                  => Controls_Manager::URL,
				'label_block'           => true,
				'dynamic'               => [
					'active'   => true,
				],
				'placeholder'           => 'https://www.your-link.com',
				'default'               => [
					'url' => '#',
				],
				'condition'             => [
					'button_switch' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Promo_Box' );
		if ( ! empty( $help_docs ) ) {
			/**
			 * Content Tab: Docs Links
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

	protected function register_style_promo_box_controls() {
		/**
		 * Style Tab: Promo Box
		 */
		$this->start_controls_section(
			'section_promo_box_style',
			[
				'label'                 => esc_html__( 'Promo Box', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'box_bg',
				'label'                 => esc_html__( 'Background', 'powerpack' ),
				'types'                 => [ 'classic', 'gradient' ],
				'selector'              => '{{WRAPPER}} .pp-promo-box-bg',
			]
		);

		$this->add_responsive_control(
			'box_height',
			[
				'label'                 => esc_html__( 'Height', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'vh', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 50,
						'max'   => 1000,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box' => 'height: {{SIZE}}{{UNIT}}',
				],
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'promo_box_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-promo-box-wrap',
			]
		);

		$this->add_control(
			'promo_box_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box, {{WRAPPER}} .pp-promo-box-wrap, {{WRAPPER}} .pp-promo-box .pp-promo-box-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_overlay_controls() {
		/**
		 * Style Tab: Overlay
		 */
		$this->start_controls_section(
			'section_promo_overlay_style',
			[
				'label'                 => esc_html__( 'Overlay', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'overlay_switch',
			[
				'label'                 => esc_html__( 'Overlay', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'On', 'powerpack' ),
				'label_off'             => esc_html__( 'Off', 'powerpack' ),
				'return_value'          => 'yes',
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'overlay_color',
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'types'                 => [ 'classic', 'gradient' ],
				'selector'              => '{{WRAPPER}} .pp-promo-box-overlay',
				'condition'             => [
					'overlay_switch'    => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_content_controls() {
		/**
		 * Style Tab: Content
		 */
		$this->start_controls_section(
			'section_promo_content_style',
			[
				'label'                 => esc_html__( 'Content', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'align',
			[
				'label'                 => esc_html__( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'    => [
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'     => [
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box'   => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'vertical_align',
			[
				'label'                 => esc_html__( 'Vertical Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'default'               => 'middle',
				'options'               => [
					'top'          => [
						'title'    => esc_html__( 'Top', 'powerpack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => esc_html__( 'Center', 'powerpack' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => esc_html__( 'Bottom', 'powerpack' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-inner-content'   => 'vertical-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'content_bg',
				'label'                 => esc_html__( 'Background', 'powerpack' ),
				'types'                 => [ 'classic', 'gradient' ],
				'selector'              => '{{WRAPPER}} .pp-promo-box-inner',
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'content_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-promo-box-inner',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 1200,
						'step'  => 1,
					],
					'%' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-wrap' => 'width: {{SIZE}}{{UNIT}}',
				],
				'separator'             => 'before',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_icon_controls() {
		/**
		 * Style Tab: Icon
		 */
		$this->start_controls_section(
			'section_promo_box_icon_style',
			[
				'label'                 => esc_html__( 'Icon', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'icon_switch'   => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'                 => esc_html__( 'Icon Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'unit' => 'px',
					'size' => 30,
				],
				'range'                 => [
					'px' => [
						'min'   => 5,
						'max'   => 200,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'icon_switch'   => 'yes',
					'icon_type'     => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'icon_img_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 500,
						'step'  => 1,
					],
					'%' => [
						'min'   => 1,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-icon img' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'icon_switch'   => 'yes',
					'icon_type'     => 'image',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label'                 => esc_html__( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'icon_color_normal',
			[
				'label'                 => esc_html__( 'Icon Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-promo-box-icon svg' => 'fill: {{VALUE}}',
				],
				'condition'             => [
					'icon_switch' => 'yes',
					'icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_bg_color_normal',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'icon_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-promo-box-icon',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-icon, {{WRAPPER}} .pp-promo-box-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label'                 => esc_html__( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			[
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label'                 => esc_html__( 'Icon Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-icon:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-promo-box-icon:hover svg' => 'fill: {{VALUE}}',
				],
				'condition'             => [
					'icon_switch' => 'yes',
					'icon_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_bg_color_hover',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-icon:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'hover_animation_icon',
			[
				'label'                 => esc_html__( 'Icon Animation', 'powerpack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_heading_controls() {
		/**
		 * Style Tab: Heading
		 */
		$this->start_controls_section(
			'section_promo_box_heading_style',
			[
				'label'                 => esc_html__( 'Heading', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'title_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-promo-box-title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'  => 20,
				],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
					'%' => [
						'min'   => 0,
						'max'   => 30,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_heading_divider_controls() {
		/**
		 * Style Tab: Heading Divider Section
		 */
		$this->start_controls_section(
			'section_heading_divider_style',
			[
				'label'                 => esc_html__( 'Heading Divider', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_heading_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_heading_type',
			[
				'label'                 => esc_html__( 'Divider Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'border',
				'options'               => [
					'border'    => esc_html__( 'Border', 'powerpack' ),
					'image'     => esc_html__( 'Image', 'powerpack' ),
				],
				'condition'             => [
					'divider_heading_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_title_image',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
				'type'                  => Controls_Manager::MEDIA,
				'condition'             => [
					'divider_heading_switch' => 'yes',
					'divider_heading_type'   => 'image',
				],
			]
		);

		$this->add_control(
			'divider_heading_border_type',
			[
				'label'                 => esc_html__( 'Border Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'solid',
				'options'               => [
					'solid'     => esc_html__( 'Solid', 'powerpack' ),
					'double'    => esc_html__( 'Double', 'powerpack' ),
					'dotted'    => esc_html__( 'Dotted', 'powerpack' ),
					'dashed'    => esc_html__( 'Dashed', 'powerpack' ),
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-heading-divider' => 'border-bottom-style: {{VALUE}}',
				],
				'condition'             => [
					'divider_heading_switch'    => 'yes',
					'divider_heading_type'      => 'border',
				],
			]
		);

		$this->add_responsive_control(
			'divider_title_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'  => 30,
				],
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 1000,
						'step'  => 1,
					],
					'%' => [
						'min'   => 1,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-heading-divider' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'divider_heading_switch'    => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_heading_border_weight',
			[
				'label'                 => esc_html__( 'Border Weight', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'  => 4,
				],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 50,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-heading-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'divider_heading_switch'    => 'yes',
					'divider_heading_type'      => 'border',
				],
			]
		);

		$this->add_control(
			'divider_heading_border_color',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#000000',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-heading-divider' => 'border-bottom-color: {{VALUE}}',
				],
				'condition'             => [
					'divider_heading_switch'    => 'yes',
					'divider_heading_type'      => 'border',
				],
			]
		);

		$this->add_responsive_control(
			'divider_title_margin',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'  => 20,
				],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
					'%' => [
						'min'   => 0,
						'max'   => 30,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-heading-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'divider_heading_switch'    => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_subheading_controls() {
		/**
		 * Style Tab: Sub Heading Section
		 */
		$this->start_controls_section(
			'section_subheading_style',
			[
				'label'                 => esc_html__( 'Sub Heading', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'subtitle_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-promo-box-subtitle',
			]
		);

		$this->add_responsive_control(
			'subtitle_margin',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'  => 20,
				],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
					'%' => [
						'min'   => 0,
						'max'   => 30,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_subheading_divider_controls() {
		/**
		 * Style Tab: Sub Heading Divider Section
		 */
		$this->start_controls_section(
			'section_subheading_divider_style',
			[
				'label'                 => esc_html__( 'Sub Heading Divider', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_subheading_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_subheading_type',
			[
				'label'                 => esc_html__( 'Divider Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'border',
				'options'               => [
					'border'    => esc_html__( 'Border', 'powerpack' ),
					'image'     => esc_html__( 'Image', 'powerpack' ),
				],
				'condition'             => [
					'divider_subheading_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_subheading_image',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
				'type'                  => Controls_Manager::MEDIA,
				'condition'             => [
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'image',
				],
			]
		);

		$this->add_control(
			'divider_subheading_border_type',
			[
				'label'                 => esc_html__( 'Border Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'solid',
				'options'               => [
					'solid'     => esc_html__( 'Solid', 'powerpack' ),
					'double'    => esc_html__( 'Double', 'powerpack' ),
					'dotted'    => esc_html__( 'Dotted', 'powerpack' ),
					'dashed'    => esc_html__( 'Dashed', 'powerpack' ),
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-subheading-divider' => 'border-bottom-style: {{VALUE}}',
				],
				'condition'             => [
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'border',
				],
			]
		);

		$this->add_responsive_control(
			'divider_subheading_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'  => 30,
				],
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 1000,
						'step'  => 1,
					],
					'%' => [
						'min'   => 1,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-subheading-divider' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'divider_subheading_switch' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_subheading_border_weight',
			[
				'label'                 => esc_html__( 'Border Weight', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'  => 4,
				],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 50,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-subheading-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'border',
				],
			]
		);

		$this->add_control(
			'divider_subheading_border_color',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#000000',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-subheading-divider' => 'border-bottom-color: {{VALUE}}',
				],
				'condition'             => [
					'divider_subheading_switch' => 'yes',
					'divider_subheading_type'   => 'border',
				],
			]
		);

		$this->add_responsive_control(
			'divider_subheading_margin',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'  => 20,
				],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
					'%' => [
						'min'   => 0,
						'max'   => 30,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-subheading-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_description_controls() {
		/**
		 * Style Tab: Description Section
		 */
		$this->start_controls_section(
			'section_promo_description_style',
			[
				'label'                 => esc_html__( 'Description', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'content_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-promo-box-content',
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'  => 0,
				],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
					'%' => [
						'min'   => 0,
						'max'   => 30,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_button_controls() {
		/**
		 * Style Tab: Button
		 */
		$this->start_controls_section(
			'section_promo_box_button_style',
			[
				'label'                 => esc_html__( 'Button', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'button_switch'   => 'yes',
				],
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'                 => esc_html__( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'md',
				'options'               => [
					'xs' => esc_html__( 'Extra Small', 'powerpack' ),
					'sm' => esc_html__( 'Small', 'powerpack' ),
					'md' => esc_html__( 'Medium', 'powerpack' ),
					'lg' => esc_html__( 'Large', 'powerpack' ),
					'xl' => esc_html__( 'Extra Large', 'powerpack' ),
				],
				'condition'             => [
					'button_text!' => '',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label'                 => esc_html__( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'button_bg_color_normal',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_text_color_normal',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_normal',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-promo-box-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'button_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-promo-box-button',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-promo-box-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label'                 => esc_html__( 'Animation', 'powerpack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-promo-box-button:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render promo box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_promobox_icon() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'icon', 'class', [ 'pp-promo-box-icon', 'pp-icon' ] );

		if ( $settings['hover_animation_icon'] ) {
			$this->add_render_attribute( 'icon', 'class', 'elementor-animation-' . $settings['hover_animation_icon'] );
		}

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['icon'] = 'fa fa-star';
		}

		$has_icon = ! empty( $settings['icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		$icon_attributes = $this->get_render_attribute_string( 'icon' );

		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = ! isset( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		?>
		<div class="pp-promo-box-icon-wrap">
			<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon' ) ); ?>>
				<?php if ( 'icon' === $settings['icon_type'] ) { ?>
					<span class="pp-promo-box-icon-inner">
						<?php
						if ( $is_new || $migrated ) {
							Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
						} elseif ( ! empty( $settings['icon'] ) ) {
							?>
							<i <?php echo wp_kses_post( $this->get_render_attribute_string( 'i' ) ); ?>></i>
							<?php
						}
						?>
					</span>
				<?php } elseif ( 'image' === $settings['icon_type'] ) { ?>
					<?php if ( ! empty( $settings['icon_image']['url'] ) ) { ?>
					<span class="pp-promo-box-icon-inner">
						<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'icon_image' ) ); ?>
					</span>
					<?php } ?>
				<?php } ?>
			</span>
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

		$this->add_render_attribute( 'promo-box', 'class', 'pp-promo-box' );

		$this->add_inline_editing_attributes( 'heading', 'none' );
		$this->add_render_attribute( 'heading', 'class', 'pp-promo-box-title' );

		$this->add_inline_editing_attributes( 'sub_heading', 'none' );
		$this->add_render_attribute( 'sub_heading', 'class', 'pp-promo-box-subtitle' );

		$this->add_inline_editing_attributes( 'content', 'none' );
		$this->add_render_attribute( 'content', 'class', 'pp-promo-box-content' );

		$this->add_inline_editing_attributes( 'button_text', 'none' );

		$this->add_render_attribute( 'button_text', 'class', [
			'pp-promo-box-button',
			'elementor-button',
			'elementor-size-' . $settings['button_size'],
		] );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'button_text', $settings['link'] );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'promo-box' ) ); ?>>
			<div class="pp-promo-box-bg"></div>
			<?php if ( 'yes' === $settings['overlay_switch'] ) { ?>
				<div class="pp-promo-box-overlay"></div>
			<?php } ?>
			<div class="pp-promo-box-wrap">
				<div class="pp-promo-box-inner">
					<div class="pp-promo-box-inner-content">
						<?php
						if ( 'yes' === $settings['icon_switch'] ) {
							if ( 'above-title' === $settings['icon_position'] ) {
								$this->render_promobox_icon();
							}
						}

						if ( '' !== $settings['heading'] ) {
							$heading_html_tag = PP_Helper::validate_html_tag( $settings['heading_html_tag'] );
							?>
							<<?php echo esc_html( $heading_html_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'heading' ) ); ?>>
								<?php echo esc_attr( $settings['heading'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</<?php echo esc_html( $heading_html_tag ); ?>>
							<?php
						}

						if ( 'yes' === $settings['divider_heading_switch'] ) { ?>
							<div class="pp-promo-box-heading-divider-wrap">
								<div class="pp-promo-box-heading-divider">
									<?php if ( 'image' === $settings['divider_heading_type'] && $settings['divider_title_image']['url'] ) { ?>
										<img src="<?php echo esc_url( $settings['divider_title_image']['url'] ); ?>">
									<?php } ?>
								</div>
							</div>
							<?php
						}

						if ( 'yes' === $settings['icon_switch'] ) {
							if ( 'below-title' === $settings['icon_position'] ) {
								$this->render_promobox_icon();
							}
						}

						if ( '' !== $settings['sub_heading'] ) {
							$subheading_html_tag = PP_Helper::validate_html_tag( $settings['sub_heading_html_tag'] );
							?>
							<<?php echo esc_html( $subheading_html_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'sub_heading' ) ); ?>>
								<?php echo esc_attr( $settings['sub_heading'] ); ?>
							</<?php echo esc_html( $subheading_html_tag ); ?>>
							<?php
						}

						if ( 'yes' === $settings['divider_subheading_switch'] ) { ?>
							<div class="pp-promo-box-subheading-divider-wrap">
								<div class="pp-promo-box-subheading-divider">
									<?php if ( 'image' === $settings['divider_subheading_type'] && $settings['divider_subheading_image']['url'] ) { ?>
										<img src="<?php echo esc_url( $settings['divider_subheading_image']['url'] ); ?>">
									<?php } ?>
								</div>
							</div>
							<?php
						}

						if ( '' !== $settings['content'] ) { ?>
							<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'content' ) ); ?>>
								<?php echo $this->parse_text_editor( $settings['content'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
							<?php
						}

						if ( 'yes' === $settings['button_switch'] ) {
							if ( '' !== $settings['button_text'] ) {
								?>
								<div class="pp-promo-box-footer">
									<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'button_text' ) ); ?>>
										<?php echo esc_attr( $settings['button_text'] ); ?>
									</a>
								</div>
								<?php
							}
						} ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render promo box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.3.2
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			function icon_template() {
				var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
				migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
				#>
				<div class="pp-promo-box-icon-wrap">
					<span class="pp-promo-box-icon pp-icon elementor-animation-{{ settings.hover_animation_icon }}">
						<# if ( settings.icon_type == 'icon' ) { #>
							<span class="pp-promo-box-icon-inner">
								<# if ( settings.icon || settings.selected_icon ) { #>
								<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
								{{{ iconHTML.value }}}
								<# } else { #>
									<i class="{{ settings.icon }}" aria-hidden="true"></i>
								<# } #>
								<# } #>
							</span>
						<# } else if ( settings.icon_type == 'image' ) { #>
							<span class="pp-promo-box-icon-inner">
								<#
								var image = {
									id: settings.icon_image.id,
									url: settings.icon_image.url,
									size: settings.image_size,
									dimension: settings.image_custom_dimension,
									model: view.getEditModel()
								};
								var image_url = elementor.imagesManager.getImageUrl( image );
								#>
								<img src="{{ _.escape( image_url ) }}" />
							</span>
						<# } #>
					</span>
				</div>
				<#
			}
		#>
		<div class="pp-promo-box">
			<div class="pp-promo-box-bg"></div>
			<# if ( settings.overlay_switch == 'yes' ) { #>
				<div class="pp-promo-box-overlay"></div>
			<# } #>
			<div class="pp-promo-box-wrap">
				<div class="pp-promo-box-inner">
					<div class="pp-promo-box-inner-content">
						<# if ( settings.icon_switch == 'yes' ) { #>
							<# if ( settings.icon_position == 'above-title' ) { #>
								<# icon_template(); #>
							<# } #>
						<# }
						   
						if ( settings.heading != '' ) {
							var heading = settings.heading;

							view.addRenderAttribute( 'heading', 'class', 'pp-promo-box-title' );

							view.addInlineEditingAttributes( 'heading' );

							var headingHTMLTag = elementor.helpers.validateHTMLTag( settings.heading_html_tag ),
								heading_html = '<' + headingHTMLTag + ' ' + view.getRenderAttributeString( 'heading' ) + '>' + heading + '</' + headingHTMLTag + '>';

							print( heading_html );
						}
						   
						if ( settings.divider_heading_switch == 'yes' ) { #>
							<div class="pp-promo-box-heading-divider-wrap">
								<div class="pp-promo-box-heading-divider">
									<# if ( settings.divider_heading_type == 'image' ) { #>
										<# if ( settings.divider_title_image.url != '' ) { #>
											<img src="{{ _.escape( settings.divider_title_image.url ) }}">
										<# } #>
									<# } #>
								</div>
							</div>
						<# }
						   
						if ( settings.icon_switch == 'yes' ) {
							if ( settings.icon_position == 'below-title' ) {
								icon_template();
							}
						}
						   
						if ( settings.sub_heading != '' ) {
							var sub_heading = settings.sub_heading;

							view.addRenderAttribute( 'sub_heading', 'class', 'pp-promo-box-subtitle' );

							view.addInlineEditingAttributes( 'sub_heading' );

							var subHeadingHTMLTag = elementor.helpers.validateHTMLTag( settings.sub_heading_html_tag ),
								sub_heading_html = '<' + subHeadingHTMLTag + ' ' + view.getRenderAttributeString( 'sub_heading' ) + '>' + sub_heading + '</' + subHeadingHTMLTag + '>';

							print( sub_heading_html );
						} #>

						<# if ( settings.divider_subheading_switch == 'yes' ) { #>
							<div class="pp-promo-box-subheading-divider-wrap">
								<div class="pp-promo-box-subheading-divider">
									<# if ( settings.divider_subheading_type == 'image' ) { #>
										<# if ( settings.divider_subheading_image.url != '' ) { #>
											<img src="{{ _.escape( settings.divider_subheading_image.url ) }}">
										<# } #>
									<# } #>
								</div>
							</div>
						<# }
						   
						if ( settings.content != '' ) {
							var content = settings.content;

							view.addRenderAttribute( 'content', 'class', 'pp-promo-box-content' );

							view.addInlineEditingAttributes( 'content' );

							var content_html = '<div' + ' ' + view.getRenderAttributeString( 'content' ) + '>' + content + '</div>';

							print( content_html );
						}
						   
						if ( settings.button_switch == 'yes' ) { #>
							<# if ( settings.button_text != '' ) { #>
								<div class="pp-promo-box-footer">
									<#
										var button_text = settings.button_text;

										view.addRenderAttribute( 'button_text', 'class', [ 'pp-promo-box-button', 'elementor-button', 'elementor-size-' + settings.button_size, 'elementor-animation-' + settings.button_hover_animation ] );

										view.addInlineEditingAttributes( 'button_text' );

										var button_html = '<a href="' + _.escape( settings.link.url ) + '"' + ' ' + view.getRenderAttributeString( 'button_text' ) + '>' + button_text + '</a>';

										print( button_html );
									#>
								</div>
							<# } #>
						<# } #>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
