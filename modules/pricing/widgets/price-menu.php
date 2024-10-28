<?php
namespace PowerpackElementsLite\Modules\Pricing\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Config;
use PowerpackElementsLite\Classes\PP_Helper;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Price Menu Widget
 */
class Price_Menu extends Powerpack_Widget {

	public function get_name() {
		return parent::get_widget_name( 'Price_Menu' );
	}

	public function get_title() {
		return parent::get_widget_title( 'Price_Menu' );
	}

	public function get_icon() {
		return parent::get_widget_icon( 'Price_Menu' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Price_Menu' );
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
			'widget-pp-price-menu'
		];
	}

	/**
	 * Register price menu widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.3.2
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_price_menu_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_items_controls();
		$this->register_style_content_controls();
		$this->register_style_title_controls();
		$this->register_style_title_separator_controls();
		$this->register_style_price_controls();
		$this->register_style_description_controls();
		$this->register_style_image_controls();
		$this->register_style_title_connector_controls();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	Content Tab
	/*-----------------------------------------------------------------------------------*/

	protected function register_content_price_menu_controls() {

		$this->start_controls_section(
			'section_price_menu',
			array(
				'label' => esc_html__( 'Price Menu', 'powerpack' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'menu_title',
			array(
				'label'       => esc_html__( 'Title', 'powerpack' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
				'placeholder' => esc_html__( 'Title', 'powerpack' ),
				'default'     => esc_html__( 'Title', 'powerpack' ),
			)
		);

		$repeater->add_control(
			'menu_description',
			array(
				'label'       => esc_html__( 'Description', 'powerpack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
				'default'     => esc_html__( 'I am item content. Double click here to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'powerpack' ),
			)
		);

		$repeater->add_control(
			'menu_price',
			array(
				'label'   => esc_html__( 'Price', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array(
					'active' => true,
				),
				'default' => '$49',
			)
		);

		$repeater->add_control(
			'discount',
			array(
				'label'        => esc_html__( 'Discount', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'On', 'powerpack' ),
				'label_off'    => esc_html__( 'Off', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'original_price',
			array(
				'label'      => esc_html__( 'Original Price', 'powerpack' ),
				'type'       => Controls_Manager::TEXT,
				'dynamic'    => array(
					'active' => true,
				),
				'default'    => '$69',
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'discount',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'image_switch',
			array(
				'label'        => esc_html__( 'Show Image', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'On', 'powerpack' ),
				'label_off'    => esc_html__( 'Off', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'image',
			array(
				'name'       => 'image',
				'label'      => esc_html__( 'Image', 'powerpack' ),
				'type'       => Controls_Manager::MEDIA,
				'default'    => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'dynamic'    => array(
					'active' => true,
				),
				'conditions' => array(
					'terms' => array(
						array(
							'name'     => 'image_switch',
							'operator' => '==',
							'value'    => 'yes',
						),
					),
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'name'        => 'link',
				'label'       => esc_html__( 'Link', 'powerpack' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => 'https://www.your-link.com',
			)
		);

		$this->add_control(
			'menu_items',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'menu_title' => esc_html__( 'Menu Item #1', 'powerpack' ),
						'menu_price' => '$49',
					),
					array(
						'menu_title' => esc_html__( 'Menu Item #2', 'powerpack' ),
						'menu_price' => '$49',
					),
					array(
						'menu_title' => esc_html__( 'Menu Item #3', 'powerpack' ),
						'menu_price' => '$49',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ menu_title }}}',
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'image_size',
				'label'     => esc_html__( 'Image Size', 'powerpack' ),
				'default'   => 'thumbnail',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'menu_style',
			array(
				'label'   => esc_html__( 'Menu Style', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style-1',
				'options' => array(
					'style-powerpack' => esc_html__( 'PowerPack Style', 'powerpack' ),
					'style-1'         => esc_html__( 'Style 1', 'powerpack' ),
					'style-2'         => esc_html__( 'Style 2', 'powerpack' ),
					'style-3'         => esc_html__( 'Style 3', 'powerpack' ),
					'style-4'         => esc_html__( 'Style 4', 'powerpack' ),
				),
			)
		);

		$this->add_responsive_control(
			'menu_align',
			array(
				'label'     => esc_html__( 'Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'powerpack' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-restaurant-menu-style-4'   => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'menu_style' => 'style-4',
				),
			)
		);

		$this->add_control(
			'title_price_connector',
			array(
				'label'        => esc_html__( 'Title-Price Connector', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'menu_style' => 'style-1',
				),
			)
		);

		$this->add_control(
			'title_separator',
			array(
				'label'        => esc_html__( 'Title Separator', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Price_Menu' );
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
	/*	Style Tab
	/*-----------------------------------------------------------------------------------*/

	protected function register_style_items_controls() {
		/**
		 * Style Tab: Menu Items
		 */
		$this->start_controls_section(
			'section_items_style',
			[
				'label'                 => esc_html__( 'Menu Items', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'items_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-item' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'items_spacing',
			[
				'label'                 => esc_html__( 'Items Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'%' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-item-wrap' => 'margin-bottom: calc(({{SIZE}}{{UNIT}})/2); padding-bottom: calc(({{SIZE}}{{UNIT}})/2)',
				],
			]
		);

		$this->add_responsive_control(
			'items_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'items_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-item',
			]
		);

		$this->add_control(
			'items_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'pricing_table_shadow',
				'selector'              => '{{WRAPPER}} .pp-restaurant-menu-item',
				'separator'             => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_content_controls() {
		/**
		 * Style Tab: Content
		 */
		$this->start_controls_section(
			'section_content_style',
			[
				'label'                 => esc_html__( 'Content', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'menu_style' => 'style-powerpack',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'menu_style' => 'style-powerpack',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_title_controls() {
		/**
		 * Style Tab: Title Section
		 */
		$this->start_controls_section(
			'section_title_style',
			[
				'label'                 => esc_html__( 'Title', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => esc_html__( 'HTML Tag', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => array(
					'h1'   => esc_html__( 'H1', 'powerpack' ),
					'h2'   => esc_html__( 'H2', 'powerpack' ),
					'h3'   => esc_html__( 'H3', 'powerpack' ),
					'h4'   => esc_html__( 'H4', 'powerpack' ),
					'h5'   => esc_html__( 'H5', 'powerpack' ),
					'h6'   => esc_html__( 'H6', 'powerpack' ),
					'div'  => esc_html__( 'div', 'powerpack' ),
					'span' => esc_html__( 'span', 'powerpack' ),
					'p'    => esc_html__( 'p', 'powerpack' ),
				),
			)
		);

		$this->add_control(
			'title_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'title_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'              => '{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'%' => [
						'min'   => 0,
						'max'   => 40,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-header' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_title_separator_controls() {
		/**
		 * Style Tab: Title Separator
		 */
		$this->start_controls_section(
			'section_title_separator_style',
			[
				'label'                 => esc_html__( 'Title Separator', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'title_separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_title_border_type',
			[
				'label'                 => esc_html__( 'Border Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'dotted',
				'options'               => [
					'none'      => esc_html__( 'None', 'powerpack' ),
					'solid'     => esc_html__( 'Solid', 'powerpack' ),
					'double'    => esc_html__( 'Double', 'powerpack' ),
					'dotted'    => esc_html__( 'Dotted', 'powerpack' ),
					'dashed'    => esc_html__( 'Dashed', 'powerpack' ),
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-price-menu-divider' => 'border-bottom-style: {{VALUE}}',
				],
				'condition'             => [
					'title_separator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_title_border_weight',
			[
				'label'                 => esc_html__( 'Border Height', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'      => 1,
				],
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 20,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-price-menu-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'title_separator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_title_border_width',
			[
				'label'                 => esc_html__( 'Border Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'      => 100,
					'unit'      => '%',
				],
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 20,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-price-menu-divider' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'title_separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_title_border_color',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-price-menu-divider' => 'border-bottom-color: {{VALUE}}',
				],
				'condition'             => [
					'title_separator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_title_spacing',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'%' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-price-menu-divider' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_price_controls() {

		$this->start_controls_section(
			'section_price_style',
			[
				'label'                 => esc_html__( 'Price', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_badge_heading',
			[
				'label'                 => esc_html__( 'Price Badge', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'menu_style' => 'style-powerpack',
				],
			]
		);

		$this->add_control(
			'badge_text_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-style-powerpack .pp-restaurant-menu-price' => 'color: {{VALUE}}',
				],
				'condition'             => [
					'menu_style' => 'style-powerpack',
				],
			]
		);

		$this->add_control(
			'badge_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-style-powerpack .pp-restaurant-menu-price:after' => 'border-right-color: {{VALUE}}',
				],
				'condition'             => [
					'menu_style' => 'style-powerpack',
				],
			]
		);

		$this->add_control(
			'price_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-price-discount' => 'color: {{VALUE}}',
				],
				'condition'             => [
					'menu_style!' => 'style-powerpack',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'price_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'              => '{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-price-discount',
			]
		);

		$this->add_control(
			'original_price_heading',
			[
				'label'                 => esc_html__( 'Original Price', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'original_price_strike',
			[
				'label'                 => esc_html__( 'Strikethrough', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => esc_html__( 'On', 'powerpack' ),
				'label_off'             => esc_html__( 'Off', 'powerpack' ),
				'return_value'          => 'yes',
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-price-original' => 'text-decoration: line-through;',
				],
			]
		);

		$this->add_control(
			'original_price_color',
			[
				'label'                 => esc_html__( 'Original Price Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-price-original' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'original_price_typography',
				'label'                 => esc_html__( 'Original Price Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'              => '{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-price-original',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_description_controls() {

		$this->start_controls_section(
			'section_description_style',
			[
				'label'                 => esc_html__( 'Description', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'description_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector'              => '{{WRAPPER}} .pp-restaurant-menu-description',
			]
		);

		$this->add_responsive_control(
			'description_spacing',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'%' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_image_controls() {
		/**
		 * Style Tab: Image Section
		 */
		$this->start_controls_section(
			'section_image_style',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'image_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-image img' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 20,
						'max'   => 300,
						'step'  => 1,
					],
					'%' => [
						'min'   => 5,
						'max'   => 50,
						'step'  => 1,
					],
				],
				'default'               => [
					'unit' => 'px',
					'size' => 150,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-image img' => 'min-width: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_margin',
			[
				'label'                 => esc_html__( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'image_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-restaurant-menu-image img',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_vertical_position',
			[
				'label'                 => esc_html__( 'Vertical Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
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
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu .pp-restaurant-menu-image' => 'align-self: {{VALUE}}',
				],
				'selectors_dictionary'  => [
					'top'      => 'flex-start',
					'middle'   => 'center',
					'bottom'   => 'flex-end',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_title_connector_controls() {
		/**
		 * Style Tab: Items Divider Section
		 */
		$this->start_controls_section(
			'section_table_title_connector_style',
			[
				'label'                 => esc_html__( 'Title-Price Connector', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'title_price_connector' => 'yes',
					'menu_style' => 'style-1',
				],
			]
		);

		$this->add_control(
			'title_connector_vertical_align',
			[
				'label'                 => esc_html__( 'Vertical Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .pp-restaurant-menu-style-1 .pp-price-title-connector'   => 'align-self: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
				'condition'             => [
					'title_price_connector' => 'yes',
					'menu_style' => 'style-1',
				],
			]
		);

		$this->add_control(
			'items_divider_style',
			[
				'label'                 => esc_html__( 'Style', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'dashed',
				'options'              => [
					'solid'     => esc_html__( 'Solid', 'powerpack' ),
					'dashed'    => esc_html__( 'Dashed', 'powerpack' ),
					'dotted'    => esc_html__( 'Dotted', 'powerpack' ),
					'double'    => esc_html__( 'Double', 'powerpack' ),
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-style-1 .pp-price-title-connector' => 'border-bottom-style: {{VALUE}}',
				],
				'condition'             => [
					'title_price_connector' => 'yes',
					'menu_style' => 'style-1',
				],
			]
		);

		$this->add_control(
			'items_divider_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-style-1 .pp-price-title-connector' => 'border-bottom-color: {{VALUE}}',
				],
				'condition'             => [
					'title_price_connector' => 'yes',
					'menu_style' => 'style-1',
				],
			]
		);

		$this->add_responsive_control(
			'items_divider_weight',
			[
				'label'                 => esc_html__( 'Divider Weight', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [ 'size' => '1' ],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 30,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-restaurant-menu-style-1 .pp-price-title-connector' => 'border-bottom-width: {{SIZE}}{{UNIT}}; bottom: calc((-{{SIZE}}{{UNIT}})/2)',
				],
				'condition'             => [
					'title_price_connector' => 'yes',
					'menu_style' => 'style-1',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$i = 1;
		$this->add_render_attribute( 'price-menu', 'class', 'pp-restaurant-menu' );

		if ( $settings['menu_style'] ) {
			$this->add_render_attribute( 'price-menu', 'class', 'pp-restaurant-menu-' . $settings['menu_style'] );
		}
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'price-menu' ) ); ?>>
			<div class="pp-restaurant-menu-items">
				<?php foreach ( $settings['menu_items'] as $index => $item ) : ?>
					<?php
						$title_key = $this->get_repeater_setting_key( 'menu_title', 'menu_items', $index );
						$this->add_render_attribute( $title_key, 'class', 'pp-restaurant-menu-title-text' );
						$this->add_inline_editing_attributes( $title_key, 'none' );

						$description_key = $this->get_repeater_setting_key( 'menu_description', 'menu_items', $index );
						$this->add_render_attribute( $description_key, 'class', 'pp-restaurant-menu-description' );
						$this->add_inline_editing_attributes( $description_key, 'basic' );

						$discount_price_key = $this->get_repeater_setting_key( 'menu_price', 'menu_items', $index );
						$this->add_render_attribute( $discount_price_key, 'class', 'pp-restaurant-menu-price-discount' );
						$this->add_inline_editing_attributes( $discount_price_key, 'none' );

						$original_price_key = $this->get_repeater_setting_key( 'original_price', 'menu_items', $index );
						$this->add_render_attribute( $original_price_key, 'class', 'pp-restaurant-menu-price-original' );
						$this->add_inline_editing_attributes( $original_price_key, 'none' );
					?>
					<div class="pp-restaurant-menu-item-wrap">
						<div class="pp-restaurant-menu-item">
							<?php if ( 'yes' === $item['image_switch'] ) { ?>
								<div class="pp-restaurant-menu-image">
									<?php
									if ( ! empty( $item['image']['url'] ) ) :
										$image = $item['image'];
										$image_url = Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'image_size', $settings );

										if ( $image_url ) {
											echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $item['image'] ) ) . '">';
										} else {
											echo '<img src="' . esc_url( $item['image']['url'] ) . '">';
										}
										?>
									<?php endif; ?>
								</div>
							<?php } ?>

							<div class="pp-restaurant-menu-content">
								<div class="pp-restaurant-menu-header">
									<?php
									if ( ! empty( $item['menu_title'] ) ) {
										$title_tag = PP_Helper::validate_html_tag( $settings['title_html_tag'] );
										?>
										<<?php echo esc_html( $title_tag ); ?> class="pp-restaurant-menu-title">
											<?php
											if ( ! empty( $item['link']['url'] ) ) {
												$title_link_key = $this->get_repeater_setting_key( 'menu_title_link', 'menu_items', $index );
												$this->add_link_attributes( $title_link_key, $item['link'] );
												?>
												<a <?php echo wp_kses_post( $this->get_render_attribute_string( $title_link_key ) ); ?>>
													<span <?php echo wp_kses_post( $this->get_render_attribute_string( $title_key ) ); ?>>
														<?php $this->print_unescaped_setting( 'menu_title', 'menu_items', $index ); ?>
													</span>
												</a>
												<?php
											} else {
												?>
												<span <?php echo wp_kses_post( $this->get_render_attribute_string( $title_key ) ); ?>>
													<?php $this->print_unescaped_setting( 'menu_title', 'menu_items', $index ); ?>
												</span>
												<?php
											}
											?>
										</<?php echo esc_html( $title_tag ); ?>>
										<?php
									}

									if ( 'yes' === $settings['title_price_connector'] ) { ?>
										<span class="pp-price-title-connector"></span>
										<?php
									}

									if ( 'style-1' === $settings['menu_style'] ) { ?>
										<?php if ( ! empty( $item['menu_price'] ) ) { ?>
											<span class="pp-restaurant-menu-price">
												<?php if ( 'yes' === $item['discount'] ) { ?>
													<span <?php echo wp_kses_post( $this->get_render_attribute_string( $original_price_key ) ); ?>>
														<?php $this->print_unescaped_setting( 'original_price', 'menu_items', $index ); ?>
													</span>
												<?php } ?>
												<span <?php echo wp_kses_post( $this->get_render_attribute_string( $discount_price_key ) ); ?>>
													<?php $this->print_unescaped_setting( 'menu_price', 'menu_items', $index ); ?>
												</span>
											</span>
										<?php } ?>
									<?php } ?>
								</div>

								<?php if ( 'yes' === $settings['title_separator'] ) { ?>
									<div class="pp-price-menu-divider-wrap">
										<div class="pp-price-menu-divider"></div>
									</div>
								<?php } ?>

								<?php
								if ( '' !== $item['menu_description'] ) {
									?>
									<div <?php echo wp_kses_post( $this->get_render_attribute_string( $description_key ) ); ?>>
										<?php $this->print_unescaped_setting( 'menu_description', 'menu_items', $index ); ?>
									</div>
									<?php
								}
								?>

								<?php if ( 'style-1' !== $settings['menu_style'] ) { ?>
									<?php if ( '' !== $item['menu_price'] ) { ?>
										<span class="pp-restaurant-menu-price">
											<?php if ( 'yes' === $item['discount'] ) { ?>
												<span <?php echo wp_kses_post( $this->get_render_attribute_string( $original_price_key ) ); ?>>
													<?php $this->print_unescaped_setting( 'original_price', 'menu_items', $index ); ?>
												</span>
											<?php } ?>
											<span <?php echo wp_kses_post( $this->get_render_attribute_string( $discount_price_key ) ); ?>>
												<?php $this->print_unescaped_setting( 'menu_price', 'menu_items', $index ); ?>
											</span>
										</span>
									<?php } ?>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php $i++;
				endforeach; ?>
			</div>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<#
			var $i = 1;

			function price_template( item ) {
				if ( item.menu_price != '' ) { #>
					<span class="pp-restaurant-menu-price">
						<#
							if ( item.discount == 'yes' ) {
								var original_price = item.original_price;

								view.addRenderAttribute( 'menu_items.' + ($i - 1) + '.original_price', 'class', 'pp-restaurant-menu-price-original' );

								view.addInlineEditingAttributes( 'menu_items.' + ($i - 1) + '.original_price' );

								var original_price_html = '<span' + ' ' + view.getRenderAttributeString( 'menu_items.' + ($i - 1) + '.original_price' ) + '>' + original_price + '</span>';

								print( original_price_html );
							}

							var menu_price = item.menu_price;

							view.addRenderAttribute( 'menu_items.' + ($i - 1) + '.menu_price', 'class', 'pp-restaurant-menu-price-discount' );

							view.addInlineEditingAttributes( 'menu_items.' + ($i - 1) + '.menu_price' );

							var menu_price_html = '<span' + ' ' + view.getRenderAttributeString( 'menu_items.' + ($i - 1) + '.menu_price' ) + '>' + menu_price + '</span>';

							print( menu_price_html );
						#>
					</span>
				<# }
			}

			function title_template( item ) {
				var title = item.menu_title;

				view.addRenderAttribute( 'menu_items.' + ($i - 1) + '.menu_title', 'class', 'pp-restaurant-menu-title-text' );

				view.addInlineEditingAttributes( 'menu_items.' + ($i - 1) + '.menu_title' );

				var title_html = '<div' + ' ' + view.getRenderAttributeString( 'menu_items.' + ($i - 1) + '.menu_title' ) + '>' + title + '</div>';

				print( title_html );
			}
		#>
		<div class="pp-restaurant-menu pp-restaurant-menu-{{ settings.menu_style }}">
			<div class="pp-restaurant-menu-items">
				<# _.each( settings.menu_items, function( item ) { #>
					<div class="pp-restaurant-menu-item-wrap">
						<div class="pp-restaurant-menu-item">
							<# if ( item.image_switch == 'yes' ) { #>
								<div class="pp-restaurant-menu-image">
									<# if ( item.image.url != '' ) { #>
										<#
										var image = {
											id: item.image.id,
											url: item.image.url,
											size: settings.image_size_size,
											dimension: settings.image_size_custom_dimension,
											model: view.getEditModel()
										};
										var image_url = elementor.imagesManager.getImageUrl( image );
										#>
										<img src="{{ _.escape( image_url ) }}" />
									<# } #>
								</div>
							<# } #>

							<div class="pp-restaurant-menu-content">
								<div class="pp-restaurant-menu-header">
									<# if ( item.menu_title != '' ) { #>
										<# var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag ); #>
										<{{{ titleHTMLTag }}} class="pp-restaurant-menu-title">
											<# if ( item.link && item.link.url ) { #>
												<a href="{{ _.escape( item.link.url ) }}">
													<# title_template( item ) #>
												</a>
											<# } else { #>
												<# title_template( item ) #>
											<# } #>
										</{{{ titleHTMLTag }}}>
									<# }

									if ( settings.title_price_connector == 'yes' ) { #>
										<span class="pp-price-title-connector"></span>
									<# }

									if ( settings.menu_style == 'style-1' ) {
										price_template( item );
									} #>
								</div>

								<# if ( settings.title_separator == 'yes' ) { #>
									<div class="pp-price-menu-divider-wrap">
										<div class="pp-price-menu-divider"></div>
									</div>
								<# }

								if ( item.menu_description != '' ) {
									var description = item.menu_description;

									view.addRenderAttribute( 'menu_items.' + ($i - 1) + '.menu_description', 'class', 'pp-restaurant-menu-description' );

									view.addInlineEditingAttributes( 'menu_items.' + ($i - 1) + '.menu_description' );

									var description_html = '<div' + ' ' + view.getRenderAttributeString( 'menu_items.' + ($i - 1) + '.menu_description' ) + '>' + description + '</div>';

									print( description_html );
								}

								if ( settings.menu_style != 'style-1' ) {
									price_template( item );
								} #>
							</div>
						</div>
					</div>
				<# $i++; } ); #>
			</div>
		</div>
		<?php
	}
}
