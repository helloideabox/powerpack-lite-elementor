<?php
namespace PowerpackElementsLite\Modules\Logos\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

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
        return 'pp-logo-carousel';
    }

    /**
	 * Retrieve logo carousel widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Logo Carousel', 'powerpack' );
    }

    /**
	 * Retrieve the list of categories the logo carousel widget belongs to.
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
	 * Retrieve logo carousel widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-logo-carousel power-pack-admin-icon';
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
        return [
            'jquery-swiper',
            'powerpack-frontend'
        ];
    }

    /**
	 * Register logo carousel widget controls.
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
         * Content Tab: Logo Carousel
         */
        $this->start_controls_section(
            'section_logo_carousel',
            [
                'label'                 => __( 'Logo Carousel', 'powerpack' ),
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
				'fields'                => [
					[
						'name'        => 'logo_carousel_slide',
						'label'       => __( 'Upload Logo Image', 'powerpack' ),
						'type'        => Controls_Manager::MEDIA,
                        'dynamic'     => [
                            'active'   => true,
                        ],
						'default'     => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
					],
					[
						'name'        => 'logo_title',
						'label'       => __( 'Title', 'powerpack' ),
                        'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'   => true,
                        ],
					],
					[
						'name'        => 'link',
						'label'       => __( 'Link', 'powerpack' ),
                        'type'        => Controls_Manager::URL,
                        'dynamic'     => [
                            'active'   => true,
                        ],
                        'placeholder' => 'https://www.your-link.com',
                        'default'     => [
                            'url' => '',
                        ],
					],
				],
				'title_field'           => __( 'Logo Image', 'powerpack' ),
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
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Carousel Settings
         */
        $this->start_controls_section(
            'section_additional_options',
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
            'autoplay_speed',
            [
                'label'                 => __( 'Autoplay Delay', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 2000 ],
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

        $this->end_controls_section();

		/**
		 * Content Tab: Docs Links
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
		
		$this->add_control(
			'help_doc_1',
			[
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/logo-carousel/logo-carousel-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			]
		);

		$this->end_controls_section();
        
        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

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
                'types'                 => [ 'none','classic','gradient' ],
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
				'size_units'            => [ 'px', '%' ],
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

        /**
         * Style Tab: Title
         */
        $this->start_controls_section(
            'section_logo_title_style',
            [
                'label'                 => __( 'Title', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-logo-carousel-title' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-logo-carousel-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-logo-carousel-title',
            ]
        );
        
        $this->end_controls_section();

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
			'arrow',
			[
				'label'                 => __( 'Choose Arrow', 'powerpack' ),
				'type'                  => Controls_Manager::ICON,
				'include'               => [
					'fa fa-angle-right',
                    'fa fa-angle-double-right',
                    'fa fa-chevron-right',
                    'fa fa-chevron-circle-right',
                    'fa fa-arrow-right',
                    'fa fa-long-arrow-right',
                    'fa fa-caret-right',
                    'fa fa-caret-square-o-right',
                    'fa fa-arrow-circle-right',
                    'fa fa-arrow-circle-o-right',
                    'fa fa-toggle-right',
                    'fa fa-hand-o-right',
				],
				'default'               => 'fa fa-angle-right',
				'frontend_available'    => true,
                'condition'             => [
                    'arrows'        => 'yes',
                ],
			]
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
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
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
				'selector'              => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev'
			]
		);

		$this->add_control(
			'arrows_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'             => 'before',
			]
		);
        
        $this->end_controls_section();
        
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'background: {{VALUE}};',
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
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
				'selector'              => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
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
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
                ],
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

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
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
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
        
        $slider_options = [
			'direction'              => 'horizontal',
			'speed'                  => ( $settings['slider_speed']['size'] !== '' ) ? $settings['slider_speed']['size'] : 400,
			'effect'                 => ( $settings['carousel_effect'] ) ? $settings['carousel_effect'] : 'slide',
			'slidesPerView'          => ( $settings['items']['size'] !== '' ) ? absint( $settings['items']['size'] ) : 3,
			'spaceBetween'           => ( $settings['margin']['size'] !== '' ) ? $settings['margin']['size'] : 10,
			'grabCursor'             => ( $settings['grab_cursor'] === 'yes' ),
			'autoHeight'             => true,
			'loop'                   => ( $settings['infinite_loop'] === 'yes' ),
		];
        
        if ( $settings['autoplay'] == 'yes' && ! empty( $settings['autoplay_speed']['size'] ) ) {
            $autoplay_speed = $settings['autoplay_speed']['size'];
        } else {
            $autoplay_speed = 999999;
        }
        
        $slider_options['autoplay'] = [
            'delay'                  => $autoplay_speed
        ];
        
        if ( $settings['dots'] == 'yes' ) {
            $slider_options['pagination'] = [
                'el'                 => '.swiper-pagination-'.esc_attr( $this->get_id() ),
                'type'               => $settings['pagination_type'],
                'clickable'          => true,
            ];
        }
        
        if ( $settings['arrows'] == 'yes' ) {
            $slider_options['navigation'] = [
                'nextEl'             => '.swiper-button-next-'.esc_attr( $this->get_id() ),
                'prevEl'             => '.swiper-button-prev-'.esc_attr( $this->get_id() ),
            ];
        }
		
		$elementor_bp_tablet	= get_option( 'elementor_viewport_lg' );
		$elementor_bp_mobile	= get_option( 'elementor_viewport_md' );
		$bp_tablet				= !empty($elementor_bp_tablet) ? $elementor_bp_tablet : 1025;
		$bp_mobile				= !empty($elementor_bp_mobile) ? $elementor_bp_mobile : 768;
        
        $slider_options['breakpoints'] = [
            $bp_tablet   => [
                'slidesPerView'      => ( $settings['items_tablet']['size'] !== '' ) ? absint( $settings['items_tablet']['size'] ) : 2,
                'spaceBetween'       => ( $settings['margin_tablet']['size'] !== '' ) ? $settings['margin_tablet']['size'] : 10,
            ],
            $bp_mobile   => [
                'slidesPerView'      => ( $settings['items_mobile']['size'] !== '' ) ? absint( $settings['items_mobile']['size'] ) : 1,
                'spaceBetween'       => ( $settings['margin_mobile']['size'] !== '' ) ? $settings['margin_mobile']['size'] : 10,
            ],
        ];
        
        $this->add_render_attribute(
			'logo-carousel-wrap',
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
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'logo-carousel-wrap', 'class', 'swiper-container-wrap pp-logo-carousel-wrap' );
        
        $this->slider_settings();
        
        $this->add_render_attribute(
            'logo-carousel',
            [
                'class'             => ['swiper-container', 'pp-logo-carousel'],
                'data-pagination'   => '.swiper-pagination-'.esc_attr( $this->get_id() ),
                'data-arrow-next'   => '.swiper-button-next-'.esc_attr( $this->get_id() ),
                'data-arrow-prev'   => '.swiper-button-prev-'.esc_attr( $this->get_id() ),
            ]
        );

        if ( $settings['dots_position'] ) {
            $this->add_render_attribute( 'logo-carousel-wrap', 'class', 'swiper-container-wrap-dots-' . $settings['dots_position'] );
        } elseif ( $settings['pagination_type'] == 'fraction' ) {
            $this->add_render_attribute( 'logo-carousel-wrap', 'class', 'swiper-container-wrap-dots-outside' );
        }
        
        if ( is_rtl() ) {
            $this->add_render_attribute( 'logo-carousel', 'dir', 'rtl' );
        }

        if ( $settings['grayscale_normal'] == 'yes' ) {
            $this->add_render_attribute( 'logo-carousel', 'class', 'grayscale-normal' );
        }

        if ( $settings['grayscale_hover'] == 'yes' ) {
            $this->add_render_attribute( 'logo-carousel', 'class', 'grayscale-hover' );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'logo-carousel-wrap' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'logo-carousel' ); ?>>
                <div class="swiper-wrapper">
                <?php
                    $i = 1;
                    foreach ( $settings['carousel_slides'] as $index => $item ) :
                        if ( $item['logo_carousel_slide'] ) : ?>
                            <div class="swiper-slide">
                                <div class="pp-lc-logo-wrap">
                                    <div class="pp-lc-logo">
                                        <?php
                                            if ( ! empty( $item['logo_carousel_slide']['url'] ) ) {

                                                if ( ! empty( $item['link']['url'] ) ) {
													
													$this->add_link_attributes( 'logo-link' . $i, $item['link'] );
													
                                                }

                                                if ( ! empty( $item['link']['url'] ) ) {
                                                    echo '<a ' . $this->get_render_attribute_string( 'logo-link' . $i ) . '>';
                                                }
                                                
                                                $image_url = Group_Control_Image_Size::get_attachment_image_src( $item['logo_carousel_slide']['id'], 'thumbnail', $settings );

                                                if ( $image_url ) {
                                                    echo '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $item['logo_carousel_slide'] ) ) . '">';
                                                } else {
                                                    echo '<img src="' . esc_url( $item['logo_carousel_slide']['url'] ) . '">';
                                                }

                                                if ( ! empty( $item['link']['url'] ) ) {
                                                    echo '</a>';
                                                }
                                            }
                                        ?>
                                    </div>
                                    <?php
                                        if ( ! empty( $item['logo_title'] ) ) {
                                            printf( '<%1$s class="pp-logo-carousel-title">', $settings['title_html_tag'] );
                                                if ( ! empty( $item['link']['url'] ) ) {
                                                    echo '<a ' . $this->get_render_attribute_string( 'logo-link' . $i ) . '>';
                                                }
                                                echo $item['logo_title'];
                                                if ( ! empty( $item['link']['url'] ) ) {
                                                    echo '</a>';
                                                }
                                            printf( '</%1$s>', $settings['title_html_tag'] );
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                        endif;
                        $i++;
                    endforeach;
                ?>
                </div>
            </div>
            <?php
                $this->render_dots();

                $this->render_arrows();
            ?>
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

        if ( $settings['dots'] == 'yes' ) { ?>
            <!-- Add Pagination -->
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
        $settings = $this->get_settings_for_display();

        if ( $settings['arrows'] == 'yes' ) { ?>
            <?php
                $slider_options['arrows'] = true;
				if ( $settings['arrow'] ) {
					$pa_next_arrow = $settings['arrow'];
					$pa_prev_arrow = str_replace("right","left",$settings['arrow']);
				}
				else {
					$pa_next_arrow = 'fa fa-angle-right';
					$pa_prev_arrow = 'fa fa-angle-left';
				}

				$slider_options['prevArrow'] = '<div class="pp-slider-arrow pp-arrow pp-arrow-prev"><i class="' . $pa_prev_arrow . '"></i></div>';
				$slider_options['nextArrow'] = '<div class="pp-slider-arrow pp-arrow pp-arrow-next"><i class="' . $pa_next_arrow . '"></i></div>';
            ?>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
            </div>
            <div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
            </div>
        <?php }
    }

    /**
	 * Render logo carousel widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
		$elementor_bp_tablet	= get_option( 'elementor_viewport_lg' );
		$elementor_bp_mobile	= get_option( 'elementor_viewport_md' );
		$bp_tablet				= !empty($elementor_bp_tablet) ? $elementor_bp_tablet : 1025;
		$bp_mobile				= !empty($elementor_bp_mobile) ? $elementor_bp_mobile : 768;
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
					if ( settings.arrow ) {
						var pp_next_arrow = settings.arrow;
                        var pp_prev_arrow = pp_next_arrow.replace('right', "left");
					}
                    else {
                        var pp_next_arrow = 'fa fa-angle-right';
                        var pp_prev_arrow = 'fa fa-angle-left';
                    }
                    #>
                    <div class="swiper-button-next">
                        <i class="{{ pp_next_arrow }}"></i>
                    </div>
                    <div class="swiper-button-prev">
                        <i class="{{ pp_prev_arrow }}"></i>
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

                return {
                    direction:              "horizontal",
                    speed:                  $speed,
                    effect:                 settings.carousel_effect,
                    slidesPerView:          $items,
                    spaceBetween:           $margin,
                    grabCursor:             ( settings.grab_cursor === 'yes' ) ? true : false,
                    autoHeight:             true,
                    loop:                   ( settings.infinite_loop === 'yes' ),
                    autoplay: {
                        delay: $autoplay,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        type: settings.pagination_type,
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        <?php echo $bp_tablet; ?>: {
                            slidesPerView:  $items_tablet,
                            spaceBetween:   $margin_tablet
                        },
                        <?php echo $bp_mobile; ?>: {
                            slidesPerView:  $items_mobile,
                            spaceBetween:   $margin_mobile
                        }
                    }
                };
           };

           view.addRenderAttribute(
                'container',
                {
                    'class': [ 'swiper-container', 'pp-logo-carousel' ],
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
                    'class': [ "swiper-container-wrap", "pp-logo-carousel-wrap", "swiper-container-wrap-dots-" + settings.dots_position ],
                }
           );

           var slider_options = get_slider_settings( settings );

           view.addRenderAttribute( 'wrapper', 'data-slider-settings', JSON.stringify( slider_options ) );
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
                                                <a href="{{ item.link.url }}">
                                            <# } #>

                                            <#
                                            var image = {
                                                id: item.logo_carousel_slide.id,
                                                url: item.logo_carousel_slide.url,
                                                size: settings.thumbnail_size,
                                                dimension: settings.thumbnail_custom_dimension,
                                                model: view.getEditModel()
                                            };
                                            var image_url = elementor.imagesManager.getImageUrl( image );
                                            #>
                                            <img src="{{{ image_url }}}" />

                                            <# if ( item.link && item.link.url ) { #>
                                                </a>
                                            <# } #>
                                        <# } #>
                                    </div>
                                    <# if ( item.title ) { #>
                                        <{{ settings.title_html_tag }} class="pp-logo-grid-title">
                                            <# if ( item.link && item.link.url ) { #>
                                                <a href="{{ item.link.url }}">
                                            <# } #>
                                                {{ item.title }}
                                            <# if ( item.link && item.link.url ) { #>
                                                </a>
                                            <# } #>
                                        </{{ settings.title_html_tag }}>
                                    <# } #>
                                </div>
                            </div>
                        <# } #>
                    <# i++ } ); #>
                </div>
            </div>
            <# dots_template(); #>
            <# arrows_template(); #>
        </div>
        <?php
    }
}