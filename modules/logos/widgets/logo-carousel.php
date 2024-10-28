<?php
namespace PowerpackElementsLite\Modules\Logos\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Logo Carousel Widget
 */
class Logo_Carousel extends Powerpack_Widget {

	/**
	 * Retrieve logo carousel widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Logo_Carousel' );
	}

	/**
	 * Retrieve logo carousel widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Logo_Carousel' );
	}

	/**
	 * Retrieve logo carousel widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Logo_Carousel' );
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
		return parent::get_widget_keywords( 'Logo_Carousel' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the logo carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'swiper',
			'pp-carousel',
		);
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since x.x.x
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return [ 'pp-swiper', 'widget-pp-logo-carousel' ];
	}

	/**
	 * Register logo carousel widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.3.2
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_logo_carousel_controls();
		$this->register_content_carousel_settings_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_logos_controls();
		$this->register_style_title_controls();
		$this->register_style_arrows_controls();
		$this->register_style_dots_controls();
		$this->register_style_fraction_controls();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	CONTENT TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_content_logo_carousel_controls() {
		/**
		 * Content Tab: Logo Carousel
		 */
		$this->start_controls_section(
			'section_logo_carousel',
			[
				'label'                 => __( 'Logo Carousel', 'powerpack' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'logo_carousel_slide',
			[
				'label'             => __( 'Image', 'powerpack' ),
				'type'              => Controls_Manager::MEDIA,
				'dynamic'           => [
					'active'   => true,
				],
				'default'           => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'logo_title',
			[
				'label'             => __( 'Title', 'powerpack' ),
				'type'              => Controls_Manager::TEXT,
				'dynamic'           => [
					'active'   => true,
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'             => __( 'Link', 'powerpack' ),
				'type'              => Controls_Manager::URL,
				'dynamic'           => [
					'active'   => true,
				],
				'placeholder'       => 'https://www.your-link.com',
				'default'           => [
					'url' => '',
				],
			]
		);

		$this->add_control(
			'carousel_slides',
			[
				'label'                 => '',
				'type'                  => Controls_Manager::REPEATER,
				'default'               => [
					[
						'logo_carousel_slide' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'logo_carousel_slide' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'logo_carousel_slide' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'logo_carousel_slide' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'logo_carousel_slide' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'fields'                => $repeater->get_controls(),
				'title_field'           => '{{logo_title}}',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.,
				'label'                 => __( 'Image Size', 'powerpack' ),
				'default'               => 'full',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'                 => __( 'Show Title', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label'                => __( 'Title HTML Tag', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'h4',
				'options'              => [
					'h1'     => __( 'H1', 'powerpack' ),
					'h2'     => __( 'H2', 'powerpack' ),
					'h3'     => __( 'H3', 'powerpack' ),
					'h4'     => __( 'H4', 'powerpack' ),
					'h5'     => __( 'H5', 'powerpack' ),
					'h6'     => __( 'H6', 'powerpack' ),
					'div'    => __( 'div', 'powerpack' ),
					'span'   => __( 'span', 'powerpack' ),
					'p'      => __( 'p', 'powerpack' ),
				],
				'condition'             => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->add_control(
			'randomize',
			[
				'label'                 => __( 'Randomize Logos', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_carousel_settings_controls() {
		/**
		 * Content Tab: Carousel Settings
		 */
		$this->start_controls_section(
			'section_carousel_settings',
			[
				'label'                 => __( 'Carousel Settings', 'powerpack' ),
			]
		);

		$this->add_control(
			'carousel_effect',
			[
				'label'                 => __( 'Effect', 'powerpack' ),
				'description'           => __( 'Sets transition effect', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'slide',
				'options'               => [
					'slide'     => __( 'Slide', 'powerpack' ),
					'fade'      => __( 'Fade', 'powerpack' ),
					'cube'      => __( 'Cube', 'powerpack' ),
					'coverflow' => __( 'Coverflow', 'powerpack' ),
					'flip'      => __( 'Flip', 'powerpack' ),
				],
			]
		);

		$this->add_responsive_control(
			'items',
			[
				'label'                 => __( 'Visible Items', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [ 'size' => 3 ],
				'tablet_default'        => [ 'size' => 2 ],
				'mobile_default'        => [ 'size' => 1 ],
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 10,
						'step'  => 1,
					],
				],
				'size_units'            => '',
				'condition'             => [
					'carousel_effect'   => 'slide',
				],
				'separator'             => 'before',
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label'                 => __( 'Items Gap', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [ 'size' => 10 ],
				'tablet_default'        => [ 'size' => 10 ],
				'mobile_default'        => [ 'size' => 10 ],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'size_units'            => '',
				'condition'             => [
					'carousel_effect'   => 'slide',
				],
			]
		);

		$this->add_control(
			'slider_speed',
			[
				'label'                 => __( 'Slider Speed', 'powerpack' ),
				'description'           => __( 'Duration of transition between slides (in ms)', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [ 'size' => 400 ],
				'range'                 => [
					'px' => [
						'min'   => 100,
						'max'   => 3000,
						'step'  => 1,
					],
				],
				'size_units'            => '',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'                 => __( 'Autoplay', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label'                 => __( 'Pause on Hover', 'powerpack' ),
				'description'           => '',
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'frontend_available'    => true,
				'condition'             => [
					'autoplay'      => 'yes',
				],
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label'                 => __( 'Pause on Interaction', 'powerpack' ),
				'description'           => __( 'Disables autoplay completely on first interaction with the carousel.', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'frontend_available'    => true,
				'condition'             => [
					'autoplay'      => 'yes',
				],
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'                 => __( 'Autoplay Delay', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [ 'size' => 3000 ],
				'range'                 => [
					'px' => [
						'min'   => 500,
						'max'   => 5000,
						'step'  => 1,
					],
				],
				'size_units'            => '',
				'condition'         => [
					'autoplay'      => 'yes',
				],
			]
		);

		$this->add_control(
			'infinite_loop',
			[
				'label'                 => __( 'Infinite Loop', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'grab_cursor',
			[
				'label'                 => __( 'Grab Cursor', 'powerpack' ),
				'description'           => __( 'Shows grab cursor when you hover over the slider', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'          => __( 'Show', 'powerpack' ),
				'label_off'         => __( 'Hide', 'powerpack' ),
				'return_value'      => 'yes',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'navigation_heading',
			[
				'label'                 => __( 'Navigation', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'arrows',
			[
				'label'                 => __( 'Arrows', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'dots',
			[
				'label'                 => __( 'Dots', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label'                 => __( 'Pagination Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'bullets',
				'options'               => [
					'bullets'       => __( 'Dots', 'powerpack' ),
					'fraction'      => __( 'Fraction', 'powerpack' ),
				],
				'condition'             => [
					'dots'          => 'yes',
				],
			]
		);

		$this->add_control(
			'direction',
			[
				'label'                 => __( 'Direction', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'left',
				'options'               => array(
					'auto'  => __( 'Auto', 'powerpack' ),
					'left'  => __( 'Left', 'powerpack' ),
					'right' => __( 'Right', 'powerpack' ),
				),
				'separator'             => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Logo_Carousel' );

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
	}

	/*-----------------------------------------------------------------------------------*/
	/*	STYLE TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_style_logos_controls() {
		/**
		 * Style Tab: Logos
		 */
		$this->start_controls_section(
			'section_logos_style',
			[
				'label'                 => __( 'Logos', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'logo_bg',
				'label'                 => __( 'Button Background', 'powerpack' ),
				'types'                 => [ 'none', 'classic', 'gradient' ],
				'selector'              => '{{WRAPPER}} .pp-lc-logo',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'logo_border',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-lc-logo',
			]
		);

		$this->add_control(
			'logo_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-lc-logo, {{WRAPPER}} .pp-lc-logo img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-lc-logo' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logos_vertical_alignment',
			array(
				'label'     => __( 'Vertical Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'center'     => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'flex-end'   => array(
						'title' => __( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-logo-carousel .swiper-wrapper' => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'logos_horizontal_alignment',
			array(
				'label'     => __( 'Horizontal Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left' => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'     => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .pp-logo-carousel .swiper-slide' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_logos_style' );

		$this->start_controls_tab(
			'tab_logos_normal',
			[
				'label'                 => __( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'grayscale_normal',
			[
				'label'                 => __( 'Grayscale', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'no',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'opacity_normal',
			[
				'label'                 => __( 'Opacity', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 1,
						'step'  => 0.1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-logo-carousel img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_responsive_control(
			'logos_height',
			[
				'label'                 => esc_html__( 'Height', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'vh', 'custom' ],
				'range'                 => [
					'px' => [
						'min' => 50,
						'max' => 1000,
					],
					'em' => [
						'min' => 10,
						'max' => 100,
					],
					'rem' => [
						'min' => 10,
						'max' => 100,
					],
					'vh' => [
						'min' => 20,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .swiper-slide img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logos_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'                 => [
					'px' => [
						'min' => 30,
						'max' => 400,
					],
					'%' => [
						'min' => 50,
					],
				],
				'default' => [
					'unit' => '%',
				],
				'selectors'             => [
					'{{WRAPPER}} .swiper-slide img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logos_object_fit',
			[
				'label'                 => __( 'Object Fit', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					''        => __( 'Default', 'powerpack' ),
					'fill'    => __( 'Fill', 'powerpack' ),
					'cover'   => __( 'Cover', 'powerpack' ),
					'contain' => __( 'Contain', 'powerpack' ),
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-slide img' => 'object-fit: {{VALUE}};',
				],
				'condition'             => [
					'logos_height[size]!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_logos_hover',
			[
				'label'                 => __( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'grayscale_hover',
			[
				'label'                 => __( 'Grayscale', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'no',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label'                 => __( 'Opacity', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 1,
						'step'  => 0.1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-logo-carousel .swiper-slide:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_title_controls() {
		/**
		 * Style Tab: Title
		 */
		$this->start_controls_section(
			'section_logo_title_style',
			[
				'label'                 => __( 'Title', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'title_typography',
				'label'                 => __( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'              => '{{WRAPPER}} .pp-logo-title',
				'condition'             => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'                 => __( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-logo-title' => 'color: {{VALUE}}',
				],
				'condition'             => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'logo_title_bg',
				'label'                 => __( 'Background', 'powerpack' ),
				'types'                 => [ 'classic', 'gradient' ],
				'exclude'               => [ 'image' ],
				'selector'              => '{{WRAPPER}} .pp-logo-title',
				'condition'             => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->add_control(
			'title_spacing',
			[
				'label'                 => __( 'Margin Top', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px' ],
				'range'                 => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-logo-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-logo-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_arrows_controls() {
		/**
		 * Style Tab: Arrows
		 */
		$this->start_controls_section(
			'section_arrows_style',
			[
				'label'                 => __( 'Arrows', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'arrows'        => 'yes',
				],
			]
		);

		$this->add_control(
			'select_arrow',
			array(
				'label'                  => __( 'Choose Arrow', 'powerpack' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'arrow',
				'label_block'            => false,
				'default'                => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'skin'                   => 'inline',
				'exclude_inline_options' => 'svg',
				'recommended'            => array(
					'fa-regular' => array(
						'arrow-alt-circle-right',
						'caret-square-right',
						'hand-point-right',
					),
					'fa-solid'   => array(
						'angle-right',
						'angle-double-right',
						'chevron-right',
						'chevron-circle-right',
						'arrow-right',
						'long-arrow-alt-right',
						'caret-right',
						'caret-square-right',
						'arrow-circle-right',
						'arrow-alt-circle-right',
						'toggle-right',
						'hand-point-right',
					),
				),
			)
		);

		$this->add_responsive_control(
			'arrows_size',
			[
				'label'                 => __( 'Arrows Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [ 'size' => '22' ],
				'range'                 => [
					'px' => [
						'min'   => 15,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px' ],
				'selectors'             => [
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'left_arrow_position',
			[
				'label'                 => __( 'Align Left Arrow', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => -100,
						'max'   => 40,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .elementor-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'right_arrow_position',
			[
				'label'                 => __( 'Align Right Arrow', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => -100,
						'max'   => 40,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .elementor-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			[
				'label'                 => __( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'arrows_bg_color_normal',
			[
				'label'                 => __( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_color_normal',
			[
				'label'                 => __( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'arrows_border_normal',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev',
			]
		);

		$this->add_control(
			'arrows_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em' ],
				'selectors'             => [
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			[
				'label'                 => __( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'arrows_bg_color_hover',
			[
				'label'                 => __( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .elementor-swiper-button-next:hover, {{WRAPPER}} .elementor-swiper-button-prev:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_color_hover',
			[
				'label'                 => __( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .elementor-swiper-button-next:hover, {{WRAPPER}} .elementor-swiper-button-prev:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_border_color_hover',
			[
				'label'                 => __( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .elementor-swiper-button-next:hover, {{WRAPPER}} .elementor-swiper-button-prev:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'             => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_dots_controls() {
		/**
		 * Style Tab: Dots
		 */
		$this->start_controls_section(
			'section_dots_style',
			[
				'label'                 => __( 'Pagination: Dots', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_position',
			[
				'label'                 => __( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'inside'     => __( 'Inside', 'powerpack' ),
					'outside'    => __( 'Outside', 'powerpack' ),
				],
				'default'               => 'outside',
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_responsive_control(
			'dots_size',
			[
				'label'                 => __( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 2,
						'max'   => 40,
						'step'  => 1,
					],
				],
				'size_units'            => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_responsive_control(
			'dots_spacing',
			[
				'label'                 => __( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 30,
						'step'  => 1,
					],
				],
				'size_units'            => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

		$this->start_controls_tab(
			'tab_dots_normal',
			[
				'label'                 => __( 'Normal', 'powerpack' ),
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_color_normal',
			[
				'label'                 => __( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
				],
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_control(
			'active_dot_color_normal',
			[
				'label'                 => __( 'Active Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
				],
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'dots_border_normal',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .swiper-pagination-bullet',
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			[
				'label'                 => __( 'Hover', 'powerpack' ),
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_color_hover',
			[
				'label'                 => __( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
				],
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_border_color_hover',
			[
				'label'                 => __( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				],
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'pagination_container_heading',
			[
				'label'                 => __( 'Pagination Container', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_responsive_control(
			'dots_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'allowed_dimensions'    => 'vertical',
				'placeholder'           => [
					'top'      => '',
					'right'    => 'auto',
					'bottom'   => '',
					'left'     => 'auto',
				],
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_fraction_controls() {
		/**
		 * Style Tab: Pagination: Fraction
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_fraction_style',
			[
				'label'                 => __( 'Pagination: Fraction', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'fraction',
				],
			]
		);

		$this->add_control(
			'fraction_text_color',
			[
				'label'                 => __( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}};',
				],
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'fraction',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'fraction_typography',
				'label'                 => __( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'              => '{{WRAPPER}} .swiper-pagination-fraction',
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'fraction',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Slider Settings.
	 *
	 * @access public
	 */
	public function slider_settings() {
		$settings = $this->get_settings();

		$effect        = ( $settings['carousel_effect'] ) ? $settings['carousel_effect'] : 'slide';
		$items         = ( isset( $settings['items']['size'] ) && $settings['items']['size'] ) ? absint( $settings['items']['size'] ) : 3;
		$items_tablet  = ( isset( $settings['items_tablet']['size'] ) && $settings['items_tablet']['size'] ) ? absint( $settings['items_tablet']['size'] ) : 2;
		$items_mobile  = ( isset( $settings['items_mobile']['size'] ) && $settings['items_mobile']['size'] ) ? absint( $settings['items_mobile']['size'] ) : 1;
		$margin        = ( isset( $settings['margin']['size'] ) && $settings['margin']['size'] ) ? absint( $settings['margin']['size'] ) : 10;
		$margin_tablet = isset( $settings['margin_tablet']['size'] ) ? absint( $settings['margin_tablet']['size'] ) : 10;
		$margin_mobile = isset( $settings['margin_mobile']['size'] ) ? absint( $settings['margin_mobile']['size'] ) : 10;

		if ( 'fade' === $effect || 'cube' === $effect || 'flip' === $effect ) {
			$items         = 1;
			$items_tablet  = 1;
			$items_mobile  = 1;
			$margin        = 10;
			$margin_tablet = 10;
			$margin_mobile = 10;
		} elseif ( 'coverflow' === $effect ) {
			$items         = 3;
			$items_tablet  = 2;
			$items_mobile  = 1;
			$margin        = 10;
			$margin_tablet = 10;
			$margin_mobile = 10;
		}

		$slider_options = [
			'effect'          => $effect,
			'speed'           => ( $settings['slider_speed']['size'] ) ? $settings['slider_speed']['size'] : 500,
			'slides_per_view' => $items,
			'space_between'   => $margin,
			'auto_height'     => false,
			'loop'            => ( 'yes' === $settings['infinite_loop'] ) ? 'yes' : '',
		];

		if ( 'yes' === $settings['grab_cursor'] ) {
			$slider_options['grab_cursor'] = true;
		}

		if ( 'yes' === $settings['autoplay'] ) {
			$autoplay_speed = 999999;
			$slider_options['autoplay'] = 'yes';

			if ( isset( $settings['autoplay_speed']['size'] ) ) {
				$autoplay_speed = $settings['autoplay_speed']['size'];
			} elseif ( $settings['autoplay_speed'] ) {
				$autoplay_speed = $settings['autoplay_speed'];
			}

			$slider_options['autoplay_speed'] = $autoplay_speed;
			$slider_options['pause_on_interaction'] = ( 'yes' === $settings['pause_on_interaction'] ) ? 'yes' : '';
		}

		if ( 'yes' === $settings['dots'] && $settings['pagination_type'] ) {
			$slider_options['pagination'] = $settings['pagination_type'];
		}

		if ( 'yes' === $settings['arrows'] ) {
			$slider_options['show_arrows'] = true;
		}

		$breakpoints = PP_Helper::elementor()->breakpoints->get_active_breakpoints();

		foreach ( $breakpoints as $device => $breakpoint ) {
			if ( in_array( $device, [ 'mobile', 'tablet', 'desktop' ] ) ) {
				switch ( $device ) {
					case 'desktop':
						$slider_options['slides_per_view'] = absint( $items );
						$slider_options['space_between'] = absint( $margin );
						break;
					case 'tablet':
						$slider_options['slides_per_view_tablet'] = absint( $items_tablet );
						$slider_options['space_between_tablet'] = absint( $margin_tablet );
						break;
					case 'mobile':
						$slider_options['slides_per_view_mobile'] = absint( $items_mobile );
						$slider_options['space_between_mobile'] = absint( $margin_mobile );
						break;
				}
			} else {
				if ( isset( $settings['items_' . $device]['size'] ) && $settings['items_' . $device]['size'] ) {
					$slider_options['slides_per_view_' . $device] = absint( $settings['items_' . $device]['size'] );
				}

				if ( isset( $settings['margin_' . $device]['size'] ) && $settings['margin_' . $device]['size'] ) {
					$slider_options['space_between_' . $device] = absint( $settings['margin_' . $device]['size'] );
				}
			}
		}

		$this->add_render_attribute(
			'logo-carousel',
			[
				'data-slider-settings' => wp_json_encode( $slider_options ),
			]
		);
	}

	/**
	 * Render logo carousel widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings     = $this->get_settings_for_display();
		$swiper_class = PP_Helper::is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

		$this->add_render_attribute(
			[
				'logo-carousel-wrap' => [
					'class' => [
						'swiper-container-wrap',
					]
				],
				'logo-carousel' => [
					'class'           => [
						'pp-logo-carousel',
						'pp-swiper-slider',
						$swiper_class
					],
				]
			]
		);

		$this->slider_settings();

		if ( 'right' === $settings['direction'] || ( 'auto' === $settings['direction'] && is_rtl() ) ) {
			$this->add_render_attribute( 'logo-carousel', 'dir', 'rtl' );
		}

		if ( $settings['dots_position'] ) {
			$this->add_render_attribute( 'logo-carousel-wrap', 'class', 'swiper-container-wrap-dots-' . $settings['dots_position'] );
		} elseif ( 'fraction' === $settings['pagination_type'] ) {
			$this->add_render_attribute( 'logo-carousel-wrap', 'class', 'swiper-container-wrap-dots-outside' );
		}

		if ( is_rtl() ) {
			$this->add_render_attribute( 'logo-carousel', 'dir', 'rtl' );
		}

		if ( 'yes' === $settings['grayscale_normal'] ) {
			$this->add_render_attribute( 'logo-carousel', 'class', 'grayscale-normal' );
		}

		if ( 'yes' === $settings['grayscale_hover'] ) {
			$this->add_render_attribute( 'logo-carousel', 'class', 'grayscale-hover' );
		}
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'logo-carousel-wrap' ) ); ?>>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'logo-carousel' ) ); ?>>
				<div class="swiper-wrapper">
				<?php
				$logos = $settings['carousel_slides'];

				if ( 'yes' === $settings['randomize'] ) {
					shuffle( $logos );
				}

				foreach ( $logos as $index => $item ) :
					$logo_link_setting_key = $this->get_repeater_setting_key( 'logo_link', 'logos', $index );

					if ( $item['logo_carousel_slide'] ) : ?>
							<div class="swiper-slide">
								<div class="pp-lc-logo-wrap">
									<div class="pp-lc-logo">
										<?php
										if ( '' !== $item['logo_carousel_slide']['url'] ) {
											if ( '' !== $item['link']['url'] ) {
												$this->add_link_attributes( $logo_link_setting_key, $item['link'] );
											}

											if ( ! empty( $item['link']['url'] ) ) { ?>
												<a <?php echo wp_kses_post( $this->get_render_attribute_string( $logo_link_setting_key ) ); ?>>
												<?php
											}

											$image_id = apply_filters( 'wpml_object_id', $item['logo_carousel_slide']['id'], 'attachment', true );
											$image_url = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'thumbnail', $settings );

											if ( $image_url ) {
												?>
												<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( Control_Media::get_image_alt( $item['logo_carousel_slide'] ) ); ?>">
												<?php
											} else {
												?>
												<img src="<?php echo esc_url( $item['logo_carousel_slide']['url'] ); ?>">
												<?php
											}

											if ( ! empty( $item['link']['url'] ) ) { ?>
												</a>
												<?php
											}
										}
										?>
									</div>
									<?php
									if ( 'yes' == $settings['show_title'] ) {
										if ( '' !== $item['logo_title'] ) {
											$title_tag = PP_Helper::validate_html_tag( $settings['title_html_tag'] );
											?>
											<<?php echo esc_html( $title_tag ); ?> class="pp-logo-title">
											<?php
											if ( ! empty( $item['link']['url'] ) ) {
												?>
												<a <?php echo wp_kses_post( $this->get_render_attribute_string( $logo_link_setting_key ) ); ?>>
												<?php
											}
											echo wp_kses_post( $item['logo_title'] );
											if ( '' !== $item['link']['url'] ) { ?>
												</a>
												<?php
											}
											?>
											</<?php echo esc_html( $title_tag ); ?>>
											<?php
										}
									}
									?>
								</div>
							</div>
							<?php
						endif;
					endforeach;
				?>
				</div>
				<?php
					$this->render_dots();

					$this->render_arrows();
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render logo carousel dots output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_dots() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' === $settings['dots'] ) { ?>
			<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ); ?>"></div>
		<?php }
	}

	/**
	 * Render logo carousel arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_arrows() {
		PP_Helper::render_arrows( $this );
	}

	/**
	 * Render logo carousel widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() {
		$elementor_bp_tablet    = get_option( 'elementor_viewport_lg' );
		$elementor_bp_mobile    = get_option( 'elementor_viewport_md' );
		$elementor_bp_lg        = get_option( 'elementor_viewport_lg' );
		$elementor_bp_md        = get_option( 'elementor_viewport_md' );
		$bp_desktop             = ! empty( $elementor_bp_lg ) ? $elementor_bp_lg : 1025;
		$bp_tablet              = ! empty( $elementor_bp_md ) ? $elementor_bp_md : 768;
		$bp_mobile              = 320;
		?>
		<#
		var i = 1;

		function dots_template() {
			if ( settings.dots == 'yes' ) {
				#>
				<div class="swiper-pagination"></div>
				<#
			}
		}

		function arrows_template() {
			if ( settings.arrows == 'yes' ) {
				if ( settings.arrow || settings.select_arrow.value ) {
					if ( settings.select_arrow.value ) {
						var next_arrow = settings.select_arrow.value;
						var prev_arrow = next_arrow.replace('right', "left");
					} else {
						var next_arrow = settings.arrow;
						var prev_arrow = next_arrow.replace('right', "left");
					}
				}
				else {
					var next_arrow = 'fa fa-angle-right';
					var prev_arrow = 'fa fa-angle-left';
				}
				#>
				<div class="pp-slider-arrow elementor-swiper-button-next">
					<i class="{{ next_arrow }}"></i>
				</div>
				<div class="pp-slider-arrow elementor-swiper-button-prev">
					<i class="{{ prev_arrow }}"></i>
				</div>
				<#
			}
		}

		function get_slider_settings( settings ) {
			var $items          = ( settings.items.size !== '' || settings.items.size !== undefined ) ? settings.items.size : 3,
				$items_tablet   = ( settings.items_tablet.size !== '' || settings.items_tablet.size !== undefined ) ? settings.items_tablet.size : 2,
				$items_mobile   = ( settings.items_mobile.size !== '' || settings.items_mobile.size !== undefined ) ? settings.items_mobile.size : 1,
				$speed          = ( settings.slider_speed.size !== '' || settings.slider_speed.size !== undefined ) ? settings.slider_speed.size : 400,
				$margin         = ( settings.margin.size !== '' || settings.margin.size !== undefined ) ? settings.margin.size : 10,
				$margin_tablet  = ( settings.margin_tablet.size !== '' || settings.margin_tablet.size !== undefined ) ? settings.margin_tablet.size : 10,
				$margin_mobile  = ( settings.margin_mobile.size !== '' || settings.margin_mobile.size !== undefined ) ? settings.margin_mobile.size : 10,
				$autoplay       = ( settings.autoplay == 'yes' && settings.autoplay_speed.size != '' ) ? settings.autoplay_speed.size : 999999;

			if ( 'fade' == settings.carousel_effect || 'cube' == settings.carousel_effect || 'flip' == settings.carousel_effect ) {
				$items = 1;
				$items_tablet = 1;
				$items_mobile = 1;
			} else if ( 'coverflow' == settings.carousel_effect ) {
				$items = 3;
				$items_tablet = 2;
				$items_mobile = 1;
			}

			var sliderOptions = {
				effect:          settings.carousel_effect,
				speed:           $speed,
				slides_per_view: $items,
				space_between:   $margin,
				auto_height:     false,
				loop:            ( 'yes' === settings.infinite_loop ) ? 'yes' : false,
				grab_cursor:     ( 'yes' === settings.grab_cursor ) ? 'yes' : false,
			};

			if ( 'yes' === settings.autoplay ) {
				var $autoplay = ( '' !== settings.autoplay_speed.size ) ? settings.autoplay_speed.size : 999999;

				sliderOptions.autoplay = $autoplay;
				sliderOptions.pause_on_interaction = ( 'yes' === settings.pause_on_interaction ) ? 'yes' : '';;
			}

			if ( 'yes' === settings.dots && settings.pagination_type ) {
				sliderOptions.pagination = settings.pagination_type;
			}

			if ( 'yes' === settings.arrows ) {
				sliderOptions.show_arrows = true;
			}

			breakpoints = elementorFrontend.config.responsive.activeBreakpoints;
			Object.keys(breakpoints).forEach(breakpointName => {
				if ( 'tablet' === breakpointName || 'mobile' === breakpointName ) {
					switch(breakpointName) {
						case 'tablet':
							sliderOptions['slides_per_view_tablet'] = $items_tablet;
							sliderOptions['space_between_tablet'] = $margin_tablet;
							break;
						case 'mobile':
							sliderOptions['slides_per_view_mobile'] = $items_mobile;
							sliderOptions['space_between_mobile'] = $margin_mobile;
							break;
					}
				} else {
						if ( settings['items_' + breakpointName].size !== '' || settings['items_' + breakpointName].size !== undefined ) {
							sliderOptions['slides_per_view_' + breakpointName] = settings['items_' + breakpointName].size;
						}

						if ( settings['margin_' + breakpointName].size !== '' || settings['margin_' + breakpointName].size !== undefined ) {
							sliderOptions['space_between_' + breakpointName] = settings['margin_' + breakpointName].size;
						}
				}
			});

			return sliderOptions;
		};

		view.addRenderAttribute(
			'container',
			{
				'class': [ 'pp-logo-carousel', 'pp-swiper-slider', elementorFrontend.config.swiperClass ],
			}
		);

		if ( settings.grayscale_normal == 'yes' ) {
			view.addRenderAttribute( 'container', 'class', 'grayscale-normal' );
		}

		if ( settings.grayscale_hover == 'yes' ) {
			view.addRenderAttribute( 'container', 'class', 'grayscale-hover' );
		}

		if ( settings.direction == 'right' ) {
			view.addRenderAttribute( 'container', 'dir', 'rtl' );
		}

		view.addRenderAttribute(
			'wrapper',
			{
				'class': [ "swiper-container-wrap", "swiper-container-wrap-dots-" + settings.dots_position ],
			}
		);

		var slider_options = get_slider_settings( settings );

		view.addRenderAttribute( 'container', 'data-slider-settings', JSON.stringify( slider_options ) );
		#>
		<div {{{ view.getRenderAttributeString( 'wrapper' ) }}}>
			<div {{{ view.getRenderAttributeString( 'container' ) }}}>
				<div class="swiper-wrapper">
					<# _.each( settings.carousel_slides, function( item ) { #>
						<# if ( item.logo_carousel_slide ) { #>
							<div class="swiper-slide">
								<div class="pp-lc-logo-wrap">
									<div class="pp-lc-logo">
										<# if ( item.logo_carousel_slide.url != '' ) { #>
											<# if ( item.link && item.link.url ) { #>
												<a href="{{ _.escape( item.link.url ) }}">
											<# } #>
											<#
											if ( item.logo_carousel_slide && item.logo_carousel_slide.id ) {

												var image = {
													id: item.logo_carousel_slide.id,
													url: item.logo_carousel_slide.url,
													size: settings.image_size,
													dimension: settings.image_custom_dimension,
													model: view.getEditModel()
												};

												var image_url = elementor.imagesManager.getImageUrl( image );

												if ( ! image_url ) {
													return;
												}
											} else {

												var image_url = item.logo_carousel_slide.url;
											}
											#>
											<img src="{{ _.escape( image_url ) }}" />

											<# if ( item.link && item.link.url ) { #>
												</a>
											<# } #>
										<# } #>
									</div>
									<# if ( 'yes' == settings.show_title ) { #>
										<# if ( item.logo_title ) { #>
											<# var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag ); #>
											<{{{ titleHTMLTag }}} class="pp-logo-grid-title">
												<# if ( item.link && item.link.url ) { #>
													<a href="{{ _.escape( item.link.url ) }}">
												<# } #>
													{{ item.logo_title }}
												<# if ( item.link && item.link.url ) { #>
													</a>
												<# } #>
											</{{{ titleHTMLTag }}}>
										<# } #>
									<# } #>
								</div>
							</div>
						<# } #>
					<# i++ } ); #>
				</div>
				<# dots_template(); #>
				<# arrows_template(); #>
			</div>
		</div>
		<?php
	}
}
