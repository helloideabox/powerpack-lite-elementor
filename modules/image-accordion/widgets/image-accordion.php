<?php
namespace PowerpackElementsLite\Modules\ImageAccordion\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Image Accordion Widget
 */
class Image_Accordion extends Powerpack_Widget {

	/**
	 * Retrieve image accordion widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Image_Accordion' );
	}

	/**
	 * Retrieve image accordion widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Image_Accordion' );
	}

	/**
	 * Retrieve image accordion widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Image_Accordion' );
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
		return parent::get_widget_keywords( 'Image_Accordion' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the image accordion widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'pp-image-accordion',
		);
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return [ 'widget-pp-image-accordion' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! PP_Helper::is_feature_active( 'e_optimized_markup' );
	}

	/**
	 * Register image accordion widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.3.2
	 * @access protected
	 */
	protected function register_controls() {

		/*-----------------------------------------------------------------------------------*/
		/*	Content Tab
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Content Tab: Items
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_items',
			[
				'label'                 => esc_html__( 'Items', 'powerpack' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'image_accordion_tabs' );

		$repeater->start_controls_tab( 'tab_content', [ 'label' => esc_html__( 'Content', 'powerpack' ) ] );

		$repeater->add_control(
			'title',
			[
				'label'                 => esc_html__( 'Title', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'label_block'           => true,
				'default'               => esc_html__( 'Accordion Title', 'powerpack' ),
				'dynamic'               => [
					'active'   => true,
				],
			]
		);

		$repeater->add_control(
			'description',
			[
				'label'                 => esc_html__( 'Description', 'powerpack' ),
				'type'                  => Controls_Manager::WYSIWYG,
				'label_block'           => true,
				'default'               => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'powerpack' ),
				'dynamic'               => [
					'active'   => true,
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'tab_image', [ 'label' => esc_html__( 'Image', 'powerpack' ) ] );

		$repeater->add_control(
			'image',
			[
				'label'                 => esc_html__( 'Choose Image', 'powerpack' ),
				'type'                  => Controls_Manager::MEDIA,
				'label_block'           => true,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'tab_link', [ 'label' => esc_html__( 'Link', 'powerpack' ) ] );

		$repeater->add_control(
			'show_button',
			[
				'label'                 => esc_html__( 'Add Link', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$repeater->add_control(
			'apply_link_on',
			[
				'label'                 => esc_html__( 'Apply Link On', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'button',
				'options'               => [
					'button'    => esc_html__( 'Button', 'powerpack' ),
					'container' => esc_html__( 'Container', 'powerpack' ),
				],
				'condition'             => [
					'show_button' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'                 => esc_html__( 'Link', 'powerpack' ),
				'type'                  => Controls_Manager::URL,
				'label_block'           => true,
				'default'               => [
					'url'           => '#',
					'is_external'   => '',
				],
				'show_external'         => true,
				'condition'             => [
					'show_button'   => 'yes',
				],
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label'                 => esc_html__( 'Button Text', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Get Started', 'powerpack' ),
				'condition'             => [
					'show_button'    => 'yes',
					'apply_link_on!' => 'container',
				],
			]
		);

		$repeater->add_control(
			'select_button_icon',
			[
				'label'                 => esc_html__( 'Button Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICONS,
				'fa4compatibility'      => 'button_icon',
				'condition'             => [
					'show_button'    => 'yes',
					'apply_link_on!' => 'container',
				],
			]
		);

		$repeater->add_control(
			'button_icon_position',
			[
				'label'                 => esc_html__( 'Icon Position', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'after',
				'options'               => [
					'before'    => esc_html__( 'Before', 'powerpack' ),
					'after'     => esc_html__( 'After', 'powerpack' ),
				],
				'condition'             => [
					'show_button'    => 'yes',
					'apply_link_on!' => 'container',
					'select_button_icon[value]!'  => '',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'accordion_items',
			[
				'type'                  => Controls_Manager::REPEATER,
				'seperator'             => 'before',
				'default'               => [
					[
						'title'         => esc_html__( 'Accordion #1', 'powerpack' ),
						'description'   => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'powerpack' ),
						'image'         => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'title'         => esc_html__( 'Accordion #2', 'powerpack' ),
						'description'   => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'powerpack' ),
						'image'         => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'title'         => esc_html__( 'Accordion #3', 'powerpack' ),
						'description'   => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'powerpack' ),
						'image'         => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'title'         => esc_html__( 'Accordion #4', 'powerpack' ),
						'description'   => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'powerpack' ),
						'image'         => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'fields'        => $repeater->get_controls(),
				'title_field' => '{{title}}',
			]
		);

		$this->add_control(
			'active_tab',
			[
				'label'                 => esc_html__( 'Default Active Item', 'powerpack' ),
				'description'           => esc_html__( 'Add item number to make that item active by default. For example: Add 1 to make first item active by default.', 'powerpack' ),
				'type'                  => \Elementor\Controls_Manager::NUMBER,
				'min'                   => 1,
				'max'                   => 100,
				'step'                  => 1,
				'default'               => '',
				'separator'             => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_image_accordion_settings',
			[
				'label'                 => esc_html__( 'Settings', 'powerpack' ),
			]
		);

		$this->add_responsive_control(
			'accordion_height',
			[
				'label'                 => esc_html__( 'Height', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'vh', 'custom' ],
				'range'                 => [
					'px'        => [
						'min'   => 50,
						'max'   => 1000,
						'step'  => 1,
					],
				],
				'default'               => [
					'size' => 400,
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion' => 'height: {{SIZE}}px',
				],
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label'                => esc_html__( 'Title HTML Tag', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'h2',
				'separator'            => 'before',
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

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'image',
				'label'                 => esc_html__( 'Image Size', 'powerpack' ),
				'default'               => 'full',
			]
		);

		$this->add_control(
			'accordion_action',
			[
				'label'                 => esc_html__( 'Accordion Action', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'on-hover',
				'label_block'           => false,
				'options'               => [
					'on-hover'  => esc_html__( 'On Hover', 'powerpack' ),
					'on-click'  => esc_html__( 'On Click', 'powerpack' ),
				],
				'frontend_available'    => true,
			]
		);

		$this->add_control(
			'orientation',
			[
				'label'                 => esc_html__( 'Orientation', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'vertical',
				'label_block'           => false,
				'options'               => [
					'vertical'      => esc_html__( 'Vertical', 'powerpack' ),
					'horizontal'    => esc_html__( 'Horizontal', 'powerpack' ),
				],
				'prefix_class'          => 'pp-image-accordion-orientation-',
			]
		);

		$this->add_control(
			'stack_on',
			[
				'label'                 => esc_html__( 'Stack On', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'tablet',
				'label_block'           => false,
				'options'               => [
					'tablet'    => esc_html__( 'Tablet', 'powerpack' ),
					'mobile'    => esc_html__( 'Mobile', 'powerpack' ),
					'none'      => esc_html__( 'None', 'powerpack' ),
				],
				'prefix_class'          => 'pp-image-accordion-stack-on-',
				'condition'             => [
					'orientation'   => 'vertical',
				],
			]
		);

		$this->add_control(
			'disable_body_click',
			[
				'label'                 => esc_html__( 'Disable Body Click', 'powerpack' ),
				'description'           => esc_html__( 'Don\'t collapse accordion on body click', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'no',
				'label_block'           => false,
				'options'               => [
					'yes' => esc_html__( 'Yes', 'powerpack' ),
					'no'  => esc_html__( 'No', 'powerpack' ),
				],
				'frontend_available'    => true,
				'condition'             => [
					'accordion_action' => 'on-click',
				],
			]
		);

		$this->end_controls_section();

		$help_docs = PP_Config::get_widget_help_links( 'Image_Accordion' );
		if ( ! empty( $help_docs ) ) {
			/**
			 * Content Tab: Docs Links
			 *
			 * @since 2.4.1
			 * @access protected
			 */
			$this->start_controls_section(
				'section_help_docs',
				[
					'label' => __( 'Help Docs', 'powerpack' ),
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

		/*-----------------------------------------------------------------------------------*/
		/*	Style Tab
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Style Tab: Items
		 */
		$this->start_controls_section(
			'section_items_style',
			[
				'label'                 => esc_html__( 'Items', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'items_spacing',
			[
				'label'                 => esc_html__( 'Items Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px'        => [
						'min'   => 0,
						'max'   => 50,
						'step'  => 1,
					],
				],
				'default'               => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'             => [
					'(desktop){{WRAPPER}}.pp-image-accordion-orientation-vertical .pp-image-accordion-item:not(:last-child)' => 'margin-right: {{SIZE}}px',
					'(desktop){{WRAPPER}}.pp-image-accordion-orientation-horizontal .pp-image-accordion-item:not(:last-child)' => 'margin-bottom: {{SIZE}}px',
					'(tablet){{WRAPPER}}.pp-image-accordion-orientation-vertical.pp-image-accordion-stack-on-tablet .pp-image-accordion-item:not(:last-child)' => 'margin-bottom: {{SIZE}}px;',
					'(mobile){{WRAPPER}}.pp-image-accordion-orientation-vertical.pp-image-accordion-stack-on-mobile .pp-image-accordion-item:not(:last-child)' => 'margin-bottom: {{SIZE}}px;',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_items_style' );

		$this->start_controls_tab(
			'tab_items_normal',
			[
				'label'                 => esc_html__( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'accordion_img_overlay_color',
			[
				'label'                 => esc_html__( 'Overlay Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => 'rgba(0,0,0,0.3)',
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion-item .pp-image-accordion-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'items_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-image-accordion-item',
			]
		);

		$this->add_control(
			'items_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'items_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-image-accordion-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_items_hover',
			[
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'accordion_img_hover_color',
			[
				'label'                 => esc_html__( 'Overlay Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => 'rgba(0,0,0,0.5)',
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion-item:hover .pp-image-accordion-overlay' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .pp-image-accordion-item.pp-image-accordion-active .pp-image-accordion-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'items_border_color_hover',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion-item:hover, {{WRAPPER}} .pp-image-accordion-item.pp-image-accordion-active' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'items_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-image-accordion-item:hover, {{WRAPPER}} .pp-image-accordion-item.pp-image-accordion-active',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Content
		 */
		$this->start_controls_section(
			'section_content_style',
			[
				'label'                 => esc_html__( 'Content', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion .pp-image-accordion-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'content_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-image-accordion .pp-image-accordion-content',
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
					'{{WRAPPER}} .pp-image-accordion .pp-image-accordion-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_vertical_align',
			[
				'label'                 => esc_html__( 'Vertical Align', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'middle',
				'options'               => [
					'top'       => [
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle'    => [
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom'    => [
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary'  => [
					'top'       => 'flex-start',
					'middle'    => 'center',
					'bottom'    => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion .pp-image-accordion-overlay' => '-webkit-align-items: {{VALUE}}; -ms-flex-align: {{VALUE}}; align-items: {{VALUE}};',
				],
				'separator'             => 'before',
			]
		);

		$this->add_responsive_control(
			'content_horizontal_align',
			[
				'label'                 => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => true,
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
				'selectors_dictionary'  => [
					'left'     => 'flex-start',
					'center'   => 'center',
					'right'    => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion .pp-image-accordion-overlay' => '-webkit-justify-content: {{VALUE}}; justify-content: {{VALUE}};',
					'{{WRAPPER}} .pp-image-accordion .pp-image-accordion-content-wrap' => '-webkit-align-items: {{VALUE}}; -ms-flex-align: {{VALUE}}; align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'                 => esc_html__( 'Text Align', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => ' center',
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
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion .pp-image-accordion-content' => 'text-align: {{VALUE}};',
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
					'px'        => [
						'min'   => 0,
						'max'   => 400,
						'step'  => 1,
					],
				],
				'default'               => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion .pp-image-accordion-content' => 'width: {{SIZE}}{{UNIT}}',
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
					'{{WRAPPER}} .pp-image-accordion .pp-image-accordion-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_style_heading',
			[
				'label'                 => esc_html__( 'Title', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#fff',
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion .pp-image-accordion-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'title_typography',
				'selector'              => '{{WRAPPER}} .pp-image-accordion .pp-image-accordion-title',
			]
		);

		$this->add_responsive_control(
			'title_spacing',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px'        => [
						'min'   => 0,
						'max'   => 50,
						'step'  => 1,
					],
				],
				'default'               => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion .pp-image-accordion-title' => 'margin-bottom: {{SIZE}}px',
				],
			]
		);

		$this->add_control(
			'description_style_heading',
			[
				'label'                 => esc_html__( 'Description', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#fff',
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion .pp-image-accordion-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'description_typography',
				'selector'              => '{{WRAPPER}} .pp-image-accordion .pp-image-accordion-description',
			]
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_button_style',
			[
				'label'                 => esc_html__( 'Button', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
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
			]
		);

		$this->add_responsive_control(
			'button_spacing',
			[
				'label'                 => esc_html__( 'Button Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px'        => [
						'min'   => 0,
						'max'   => 50,
						'step'  => 1,
					],
				],
				'default'               => [
					'size' => 15,
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion-button' => 'margin-top: {{SIZE}}px',
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
					'{{WRAPPER}} .pp-image-accordion-button' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .pp-image-accordion-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-image-accordion-button .pp-icon svg' => 'fill: {{VALUE}}',
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
				'selector'              => '{{WRAPPER}} .pp-image-accordion-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'button_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'              => '{{WRAPPER}} .pp-image-accordion-button',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-image-accordion-button',
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
					'{{WRAPPER}} .pp-image-accordion-button:hover' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .pp-image-accordion-button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-image-accordion-button:hover .pp-icon svg' => 'fill: {{VALUE}}',
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
					'{{WRAPPER}} .pp-image-accordion-button:hover' => 'border-color: {{VALUE}}',
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
				'selector'              => '{{WRAPPER}} .pp-image-accordion-button:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'button_icon_heading',
			[
				'label'                 => esc_html__( 'Icon', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_responsive_control(
			'button_icon_spacing',
			[
				'label'                 => esc_html__( 'Icon Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px'        => [
						'min'   => 0,
						'max'   => 50,
						'step'  => 1,
					],
				],
				'default'               => [
					'size' => 2,
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-button-icon-before .pp-button-icon' => 'margin-right: {{SIZE}}px',
					'{{WRAPPER}} .pp-button-icon-after .pp-button-icon' => 'margin-left: {{SIZE}}px',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render_button_icon( $item ) {
		$settings = $this->get_settings_for_display();

		$migration_allowed = Icons_Manager::is_migration_allowed();

		// add old default
		if ( ! isset( $item['button_icon'] ) && ! $migration_allowed ) {
			$item['hotspot_icon'] = '';
		}

		$migrated = isset( $item['__fa4_migrated']['select_button_icon'] );
		$is_new = ! isset( $item['button_icon'] ) && $migration_allowed;

		if ( ! empty( $item['button_icon'] ) || ( ! empty( $item['select_button_icon']['value'] ) && $is_new ) ) {
			?>
			<span class="pp-button-icon pp-icon pp-no-trans">
				<?php if ( $is_new || $migrated ) {
					Icons_Manager::render_icon( $item['select_button_icon'], [ 'aria-hidden' => 'true' ] );
				} else { ?>
					<i class="<?php echo esc_attr( $item['button_icon'] ); ?>" aria-hidden="true"></i>
				<?php } ?>
			</span>
			<?php
		}
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'image-accordion', [
			'class' => [ 'pp-image-accordion', 'pp-image-accordion-' . $settings['accordion_action'] ],
			'id'    => 'pp-image-accordion-' . $this->get_id(),
		] );

		if ( ! empty( $settings['accordion_items'] ) ) { ?>
			<div <?php $this->print_render_attribute_string( 'image-accordion' ); ?>>
				<?php foreach ( $settings['accordion_items'] as $index => $item ) { ?>
					<?php
					$item_key    = $this->get_repeater_setting_key( 'item', 'accordion_items', $index );
					$overlay_key = $this->get_repeater_setting_key( 'container_link', 'accordion_items', $index );
					$overlay_tag = 'div';
					$add_link    = false;

					$this->add_render_attribute( $item_key, 'class', [ 'pp-image-accordion-item', 'elementor-repeater-item-' . esc_attr( $item['_id'] ) ] );
					$this->add_render_attribute( $overlay_key, 'class', [ 'pp-image-accordion-overlay', 'pp-media-overlay' ] );

					if ( $item['image']['url'] ) {

						$image_id  = apply_filters( 'wpml_object_id', $item['image']['id'], 'attachment', true );
						$image_url = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image', $settings );

						if ( ! $image_url ) {
							$image_url = $item['image']['url'];
						}

						$this->add_render_attribute( $item_key, [
							'style' => 'background-image: url(' . esc_url( $image_url ) . ');',
						] );
					}

					$content_key = $this->get_repeater_setting_key( 'content', 'accordion_items', $index );

					$this->add_render_attribute( $content_key, 'class', 'pp-image-accordion-content-wrap' );

					if ( 'yes' === $item['show_button'] && ! empty( $item['link']['url'] ) ) {
						$add_link      = true;
						$apply_link_on = ( $item['apply_link_on'] ) ? $item['apply_link_on'] : 'button';

						if ( $add_link && 'container' === $apply_link_on ) {
							$overlay_tag = 'a';

							$this->add_link_attributes( $overlay_key, $item['link'] );
						} else {
							$button_key = $this->get_repeater_setting_key( 'button', 'accordion_items', $index );

							$this->add_render_attribute( $button_key, 'class', [
								'pp-image-accordion-button',
								'pp-button-icon-' . $item['button_icon_position'],
								'elementor-button',
								'elementor-size-' . $settings['button_size'],
							] );

							if ( $settings['button_hover_animation'] ) {
								$this->add_render_attribute( $button_key, 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
							}

							$this->add_link_attributes( $button_key, $item['link'] );
						}
					}

					if ( $settings['active_tab'] ) {
						$tab_count = $settings['active_tab'] - 1;

						if ( $index === $tab_count ) {
							$this->add_render_attribute( $item_key, [
								'class' => 'pp-image-accordion-active',
								'style' => 'flex: 3 1 0;',
							] );
							$this->add_render_attribute( $content_key, [
								'class' => 'pp-image-accordion-content-active',
							] );
						}
					}
					?>
					<div <?php $this->print_render_attribute_string( $item_key ); ?>>
						<<?php echo esc_html( $overlay_tag ) ?> <?php $this->print_render_attribute_string( $overlay_key ); ?>>
							<div <?php $this->print_render_attribute_string( $content_key ); ?>>
								<div class="pp-image-accordion-content">
									<?php $title_tag = PP_Helper::validate_html_tag( $settings['title_html_tag'] ); ?>
									<<?php echo esc_html( $title_tag ); ?> class="pp-image-accordion-title">
										<?php echo wp_kses_post( $item['title'] ); ?>
									</<?php echo esc_html( $title_tag ); ?>>
									<div class="pp-image-accordion-description">
										<?php echo $this->parse_text_editor( $item['description'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								</div>
								<?php if ( $add_link && 'button' === $apply_link_on ) { ?>
									<div class="pp-image-accordion-button-wrap">
										<a <?php $this->print_render_attribute_string( $button_key ); ?>>
											<?php
											if ( 'before' === $item['button_icon_position'] ) {
												$this->render_button_icon( $item );
											}
											?>
											<?php if ( ! empty( $item['button_text'] ) ) { ?>
												<span class="pp-button-text">
													<?php echo wp_kses_post( $item['button_text'] ); ?>
												</span>
											<?php } ?>
											<?php
											if ( 'after' === $item['button_icon_position'] ) {
												$this->render_button_icon( $item );
											}
											?>
										</a>
									</div>
								<?php } ?>
							</div>
						</<?php echo esc_html( $overlay_tag ) ?>>
					</div>
				<?php } ?>
			</div>
		<?php }
	}
}
