<?php
namespace PowerpackElementsLite\Modules\InfoBox\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
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
 * Info Box Carousel Widget
 */
class Info_Box_Carousel extends Powerpack_Widget {
    
    /**
	 * Retrieve info box carousel widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-info-box-carousel';
    }

    /**
	 * Retrieve info box carousel widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Info Box Carousel', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the info box carousel widget belongs to.
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
	 * Retrieve info box carousel widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-info-box-carousel power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the info box carousel widget depended on.
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
	 * Register info box carousel widget controls.
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
         * Content Tab: Info Boxes
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_boxes',
            [
                'label'                     => __( 'Info Boxes', 'power-pack' ),
            ]
        );
        
        $repeater = new Repeater();
        
        $repeater->start_controls_tabs( 'items_repeater' );

        $repeater->start_controls_tab( 'tab_content', [ 'label' => __( 'Content', 'power-pack' ) ] );

            $repeater->add_control(
                'title',
                [
                    'label'                 => __( 'Title', 'power-pack' ),
                    'type'                  => Controls_Manager::TEXT,
                    'dynamic'               => [
                        'active'   => true,
                    ],
                    'default'               => __( 'Title', 'power-pack' ),
                ]
            );

            $repeater->add_control(
                'subtitle',
                [
                    'label'                 => __( 'Subtitle', 'power-pack' ),
                    'type'                  => Controls_Manager::TEXT,
                    'dynamic'               => [
                        'active'   => true,
                    ],
                    'default'               => __( 'Subtitle', 'power-pack' ),
                ]
            );

            $repeater->add_control(
                'description',
                [
                    'label'                 => __( 'Description', 'power-pack' ),
                    'type'                  => Controls_Manager::TEXTAREA,
                    'dynamic'               => [
                        'active'   => true,
                    ],
                    'default'               => __( 'Enter info box description', 'power-pack' ),
                ]
            );
        
        $repeater->end_controls_tab();
        
        $repeater->start_controls_tab( 'tab_icon', [ 'label' => __( 'Icon', 'power-pack' ) ] );
        
            $repeater->add_control(
                'icon_type',
                [
                    'label'                 => esc_html__( 'Type', 'power-pack' ),
                    'type'                  => Controls_Manager::CHOOSE,
                    'label_block'           => false,
                    'options'               => [
                        'none' => [
                            'title' => esc_html__( 'None', 'power-pack' ),
                            'icon' => 'fa fa-ban',
                        ],
                        'icon' => [
                            'title' => esc_html__( 'Icon', 'power-pack' ),
                            'icon' => 'fa fa-gear',
                        ],
                        'image' => [
                            'title' => esc_html__( 'Image', 'power-pack' ),
                            'icon' => 'fa fa-picture-o',
                        ],
                        'text' => [
                            'title' => esc_html__( 'Text', 'power-pack' ),
                            'icon' => 'fa fa-font',
                        ],
                    ],
                    'default'               => 'icon',
                ]
            );

            $repeater->add_control(
                'icon',
                [
                    'label'                 => __( 'Icon', 'power-pack' ),
                    'type'                  => Controls_Manager::ICON,
                    'default'               => 'fa fa-diamond',
                    'condition'             => [
                        'icon_type'     => 'icon',
                    ],
                ]
            );

            $repeater->add_control(
                'icon_text',
                [
                    'label'                 => __( 'Text', 'power-pack' ),
                    'type'                  => Controls_Manager::TEXT,
                    'dynamic'               => [
                        'active'   => true,
                    ],
                    'default'               => '1',
                    'condition'             => [
                        'icon_type'     => 'text',
                    ],
                ]
            );

            $repeater->add_control(
                'image',
                [
                    'label'                 => __( 'Image', 'power-pack' ),
                    'type'                  => Controls_Manager::MEDIA,
                    'dynamic'               => [
                        'active'   => true,
                    ],
                    'default'               => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                    'condition'             => [
                        'icon_type' => 'image',
                    ],
                ]
            );
        
        $repeater->end_controls_tab();
        
        $repeater->start_controls_tab( 'tab_link', [ 'label' => __( 'Link', 'power-pack' ) ] );
        
        $repeater->add_control(
            'link_type',
            [
                'label'                 => __( 'Link Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'none',
                'options'               => [
                    'none'      => __( 'None', 'power-pack' ),
                    'box'       => __( 'Box', 'power-pack' ),
                    'icon'      => __( 'Image/Icon', 'power-pack' ),
                    'title'     => __( 'Title', 'power-pack' ),
                    'button'    => __( 'Button', 'power-pack' ),
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'                 => __( 'Link', 'power-pack' ),
                'type'                  => Controls_Manager::URL,
                'dynamic'               => [
                    'active'   => true,
                ],
                'placeholder'           => 'https://www.your-link.com',
                'default'               => [
                    'url' => '#',
                ],
                'condition'             => [
                    'link_type!'   => 'none',
                ],
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label'                 => __( 'Button Text', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'dynamic'               => [
                    'active'   => true,
                ],
                'default'               => __( 'Get Started', 'power-pack' ),
                'condition'             => [
                    'link_type'   => 'button',
                ],
            ]
        );

        $repeater->add_control(
            'button_icon',
            [
                'label'                 => __( 'Button Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => '',
                'condition'             => [
                    'link_type'   => 'button',
                ],
            ]
        );
        
        $repeater->add_control(
            'button_icon_position',
            [
                'label'                 => __( 'Icon Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'after',
                'options'               => [
                    'before'    => __( 'Before', 'power-pack' ),
                    'after'     => __( 'After', 'power-pack' ),
                ],
                'condition'             => [
                    'link_type'     => 'button',
                    'button_icon!'  => '',
                ],
            ]
        );
        
        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'pp_info_boxes',
            [
                'label' 	=> '',
                'type' 		=> Controls_Manager::REPEATER,
                'default' 	=> [
                    [
                        'title' => __( 'Info Box 1', 'power-pack' ),
                    ],
                    [
                        'title' => __( 'Info Box 2', 'power-pack' ),
                    ],
                    [
                        'title' => __( 'Info Box 3', 'power-pack' ),
                    ],
                ],
                'fields' 		=> array_values( $repeater->get_controls() ),
                'title_field' 	=> '{{{ title }}}'
            ]
        );
        
        $this->add_control(
            'divider_title_switch',
            [
                'label'                 => __( 'Title Separator', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'title_html_tag',
            [
                'label'                 => __( 'Title HTML Tag', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'h4',
                'options'               => [
                    'h1'     => __( 'H1', 'power-pack' ),
                    'h2'     => __( 'H2', 'power-pack' ),
                    'h3'     => __( 'H3', 'power-pack' ),
                    'h4'     => __( 'H4', 'power-pack' ),
                    'h5'     => __( 'H5', 'power-pack' ),
                    'h6'     => __( 'H6', 'power-pack' ),
                    'div'    => __( 'div', 'power-pack' ),
                    'span'   => __( 'span', 'power-pack' ),
                    'p'      => __( 'p', 'power-pack' ),
                ],
            ]
        );
        
        $this->add_control(
            'sub_title_html_tag',
            [
                'label'                 => __( 'Subtitle HTML Tag', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'h5',
                'options'               => [
                    'h1'     => __( 'H1', 'power-pack' ),
                    'h2'     => __( 'H2', 'power-pack' ),
                    'h3'     => __( 'H3', 'power-pack' ),
                    'h4'     => __( 'H4', 'power-pack' ),
                    'h5'     => __( 'H5', 'power-pack' ),
                    'h6'     => __( 'H6', 'power-pack' ),
                    'div'    => __( 'div', 'power-pack' ),
                    'span'   => __( 'span', 'power-pack' ),
                    'p'      => __( 'p', 'power-pack' ),
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Content Tab: Carousel Settings
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_carousel_settings',
            [
                'label'                 => __( 'Carousel Settings', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'carousel_effect',
            [
                'label'                 => __( 'Effect', 'power-pack' ),
                'description'           => __( 'Sets transition effect', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'slide',
                'options'               => [
                    'slide'     => __( 'Slide', 'power-pack' ),
                    'fade'      => __( 'Fade', 'power-pack' ),
                    'cube'      => __( 'Cube', 'power-pack' ),
                    'coverflow' => __( 'Coverflow', 'power-pack' ),
                    'flip'      => __( 'Flip', 'power-pack' ),
                ],
            ]
        );
        
        $this->add_responsive_control(
            'items',
            [
                'label'                 => __( 'Visible Items', 'power-pack' ),
                'description'           => __( 'Number of slides visible at the same time on slider\'s container).', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 3 ],
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
                'label'                 => __( 'Items Gap', 'power-pack' ),
                'description'           => __( 'Distance between slides (in px)', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 10 ],
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
                'label'                 => __( 'Slider Speed', 'power-pack' ),
                'description'           => __( 'Duration of transition between slides (in ms)', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 600 ],
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
                'label'                 => __( 'Autoplay', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'autoplay_speed',
            [
                'label'                 => __( 'Autoplay Speed', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 2400 ],
                'range'                 => [
                    'px' => [
                        'min'   => 500,
                        'max'   => 5000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'condition'             => [
                    'autoplay'      => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'infinite_loop',
            [
                'label'                 => __( 'Infinite Loop', 'power-pack' ),
                'description'           => __( 'Enables continuous loop mode', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'grab_cursor',
            [
                'label'                 => __( 'Grab Cursor', 'power-pack' ),
                'description'           => __( 'Shows grab cursor when you hover over the slider', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'yes',
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'navigation_heading',
            [
                'label'                 => __( 'Navigation', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'arrows',
            [
                'label'                 => __( 'Arrows', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'dots',
            [
                'label'                 => __( 'Pagination', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'pagination_type',
            [
                'label'                 => __( 'Pagination Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'bullets',
                'options'               => [
                    'bullets'       => __( 'Dots', 'power-pack' ),
                    'fraction'      => __( 'Fraction', 'power-pack' ),
                ],
                'condition'             => [
                    'dots'          => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'direction',
            [
                'label'                 => __( 'Direction', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'left',
                'options'               => [
                    'left'       => __( 'Left', 'power-pack' ),
                    'right'      => __( 'Right', 'power-pack' ),
                ],
				'separator'             => 'before',
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Info Box
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_style',
            [
                'label'                 => __( 'Info Box', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'align',
			[
				'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify'   => [
						'title' => __( 'Justified', 'power-pack' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box .swiper-slide'   => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Icon Style
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_icon_style',
            [
                'label'                 => __( 'Icon Style', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'icon_size',
            [
                'label'                 => __( 'Icon Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 5,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_icon_style' );

        $this->start_controls_tab(
            'tab_icon_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'icon_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_color_normal',
            [
                'label'                 => __( 'Icon Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'icon_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-info-box-icon',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon, {{WRAPPER}} .pp-info-box-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'icon_rotation',
            [
                'label'                 => __( 'Icon Rotation', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 360,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'transform: rotate( {{SIZE}}deg );',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_padding',
            [
                'label'                 => __( 'Padding', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 120,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'icon_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_icon_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'icon_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'                 => __( 'Icon Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_rotation_hover',
            [
                'label'                 => __( 'Icon Rotation', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 360,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box .pp-info-box-icon-wrap:hover' => 'transform: rotate( {{SIZE}}deg );',
                ],
            ]
        );

		$this->add_control(
			'icon_animation',
			[
				'label'                 => __( 'Icon Animation', 'power-pack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->add_control(
            'icon_image_heading',
            [
                'label'                 => __( 'Icon Image', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );

        $this->add_responsive_control(
            'icon_img_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    '%' => [
                        'min'   => 25,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon img' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_control(
            'icon_text_heading',
            [
                'label'                 => __( 'Icon Text', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'icon_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-box-icon',
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Title
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_title_style',
            [
                'label'                 => __( 'Title', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-box-title',
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label'                 => __( 'Margin Bottom', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_control(
            'subtitle_heading',
            [
                'label'                 => __( 'Sub Title', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'subtitle_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-box-subtitle',
            ]
        );
        
        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label'                 => __( 'Margin Bottom', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Title Separator
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_title_divider_style',
            [
                'label'                 => __( 'Title Separator', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'divider_title_border_type',
            [
                'label'                 => __( 'Border Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'solid',
                'options'               => [
                    'none'      => __( 'None', 'power-pack' ),
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'double'    => __( 'Double', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-style: {{VALUE}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'divider_title_width',
            [
                'label'                 => __( 'Border Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'divider_title_border_height',
            [
                'label'                 => __( 'Border Height', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 2,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 20,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'divider_title_border_color',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-color: {{VALUE}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
			'divider_title_align',
			[
				'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-divider-wrap'   => 'text-align: {{VALUE}};',
				],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
			]
		);
        
        $this->add_responsive_control(
            'divider_title_margin',
            [
                'label'                 => __( 'Margin Bottom', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Description
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_description_style',
            [
                'label'                 => __( 'Description', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-description' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'description_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-box-description',
            ]
        );

		$this->add_responsive_control(
			'description_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'description_margin',
            [
                'label'                 => __( 'Margin Bottom', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_button_style',
            [
                'label'                 => __( 'Button', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'button_size',
			[
				'label'                 => __( 'Size', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'md',
				'options'               => [
					'xs' => __( 'Extra Small', 'power-pack' ),
					'sm' => __( 'Small', 'power-pack' ),
					'md' => __( 'Medium', 'power-pack' ),
					'lg' => __( 'Large', 'power-pack' ),
					'xl' => __( 'Extra Large', 'power-pack' ),
				],
			]
		);

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'button_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_normal',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-info-box-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'button_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-box-button',
            ]
        );

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-info-box-button',
			]
		);
        
        $this->add_control(
            'info_box_button_icon_heading',
            [
                'label'                 => __( 'Button Icon', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
                    'button_icon!'  => '',
                ],
            ]
        );

		$this->add_responsive_control(
			'button_icon_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
                'condition'             => [
                    'button_icon!'  => '',
                ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box .pp-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
			'button_hover_animation',
			[
				'label'                 => __( 'Animation', 'power-pack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-info-box-button:hover',
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Arrows
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_arrows_style',
            [
                'label'                 => __( 'Arrows', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'arrows'        => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'arrow',
            [
                'label'                 => __( 'Choose Arrow', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'label_block'           => true,
                'default'               => 'fa fa-angle-right',
                'options'               => [
                    'fa fa-angle-right'             => __( 'Angle', 'power-pack' ),
                    'fa fa-angle-double-right'      => __( 'Double Angle', 'power-pack' ),
                    'fa fa-chevron-right'           => __( 'Chevron', 'power-pack' ),
                    'fa fa-chevron-circle-right'    => __( 'Chevron Circle', 'power-pack' ),
                    'fa fa-arrow-right'             => __( 'Arrow', 'power-pack' ),
                    'fa fa-long-arrow-right'        => __( 'Long Arrow', 'power-pack' ),
                    'fa fa-caret-right'             => __( 'Caret', 'power-pack' ),
                    'fa fa-caret-square-o-right'    => __( 'Caret Square', 'power-pack' ),
                    'fa fa-arrow-circle-right'      => __( 'Arrow Circle', 'power-pack' ),
                    'fa fa-arrow-circle-o-right'    => __( 'Arrow Circle O', 'power-pack' ),
                    'fa fa-toggle-right'            => __( 'Toggle', 'power-pack' ),
                    'fa fa-hand-o-right'            => __( 'Hand', 'power-pack' ),
                ],
            ]
        );
        
        $this->add_responsive_control(
            'arrows_size',
            [
                'label'                 => __( 'Arrows Size', 'power-pack' ),
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
                'label'                 => __( 'Align Left Arrow', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
            ]
        );
        
        $this->add_responsive_control(
            'right_arrow_position',
            [
                'label'                 => __( 'Align Right Arrow', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_arrows_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
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
                'label'                 => __( 'Color', 'power-pack' ),
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
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev'
			]
		);

		$this->add_control(
			'arrows_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
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
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
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
                'label'                 => __( 'Color', 'power-pack' ),
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
                'label'                 => __( 'Border Color', 'power-pack' ),
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
				'label'                 => __( 'Padding', 'power-pack' ),
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
         * Style Tab: Pagination: Dots
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_dots_style',
            [
                'label'                 => __( 'Pagination: Dots', 'power-pack' ),
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
                'label'                 => __( 'Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'inside'     => __( 'Inside', 'power-pack' ),
                   'outside'    => __( 'Outside', 'power-pack' ),
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
                'label'                 => __( 'Size', 'power-pack' ),
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
                'label'                 => __( 'Spacing', 'power-pack' ),
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
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );

        $this->add_control(
            'dots_color_normal',
            [
                'label'                 => __( 'Color', 'power-pack' ),
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
                'label'                 => __( 'Active Color', 'power-pack' ),
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
				'label'                 => __( 'Border', 'power-pack' ),
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
				'label'                 => __( 'Border Radius', 'power-pack' ),
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
			'dots_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
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
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'label'                 => __( 'Hover', 'power-pack' ),
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );

        $this->add_control(
            'dots_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
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
                'label'                 => __( 'Border Color', 'power-pack' ),
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
         * Style Tab: Pagination: Dots
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_fraction_style',
            [
                'label'                 => __( 'Pagination: Fraction', 'power-pack' ),
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
                'label'                 => __( 'Text Color', 'power-pack' ),
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
                'label'                 => __( 'Typography', 'power-pack' ),
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
	 * Render info box carousel widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'info-box-carousel-wrap', 'class', 'swiper-container-wrap pp-info-box-carousel-wrap' );

        if ( $settings['dots_position'] ) {
            $this->add_render_attribute( 'info-box-carousel-wrap', 'class', 'swiper-container-wrap-dots-' . $settings['dots_position'] );
        } elseif ( $settings['pagination_type'] == 'fraction' ) {
            $this->add_render_attribute( 'info-box-carousel-wrap', 'class', 'swiper-container-wrap-dots-outside' );
        }
        
        if ( $settings['direction'] == 'right' ) {
            $this->add_render_attribute( 'info-box-carousel', 'dir', 'rtl' );
        }
        
        $this->add_render_attribute( 'info-box-carousel', 'class', 'swiper-container pp-info-box pp-info-box-carousel' );
        
        $this->add_render_attribute( 'info-box-carousel', 'class', 'swiper-container-'.esc_attr($this->get_id()) );
        $this->add_render_attribute( 'info-box-carousel', 'data-pagination', '.swiper-pagination-'.esc_attr( $this->get_id() ) );
        $this->add_render_attribute( 'info-box-carousel', 'data-arrow-next', '.swiper-button-next-'.esc_attr( $this->get_id() ) );
        $this->add_render_attribute( 'info-box-carousel', 'data-arrow-prev', '.swiper-button-prev-'.esc_attr( $this->get_id() ) );
        
        $this->add_render_attribute( 'info-box-container', 'class', 'pp-info-box-container' );
            
        $this->add_render_attribute( 'title-container', 'class', 'pp-info-box-title-container' );
        
        $pp_if_html_tag = 'div';
        $pp_title_html_tag = 'div';
        $pp_button_html_tag = 'div';
        
        $this->add_render_attribute( 'info-box-button', 'class', [
				'pp-info-box-button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			]
		);

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'info-box-button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}
        
        $this->add_render_attribute( 'icon', 'class', 'pp-info-box-icon' );

		if ( $settings['icon_animation'] ) {
			$this->add_render_attribute( 'icon', 'class', 'elementor-animation-' . $settings['icon_animation'] );
		}
        
        if ( ! empty( $settings['items']['size'] ) ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-items', $settings['items']['size'] );
        }
        if ( ! empty( $settings['items_tablet']['size'] ) ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-items-tablet', $settings['items_tablet']['size'] );
        }
        if ( ! empty( $settings['items_mobile']['size'] ) ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-items-mobile', $settings['items_mobile']['size'] );
        }
        if ( ! empty( $settings['margin']['size'] ) ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-margin', $settings['margin']['size'] );
        }
        if ( ! empty( $settings['margin_tablet']['size'] ) ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-margin-tablet', $settings['margin_tablet']['size'] );
        }
        if ( ! empty( $settings['margin_mobile']['size'] ) ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-margin-mobile', $settings['margin_mobile']['size'] );
        }
        if ( $settings['carousel_effect'] ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-effect', $settings['carousel_effect'] );
        }
        if ( ! empty( $settings['slider_speed']['size'] ) ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-speed', $settings['slider_speed']['size'] );
        }
        if ( $settings['autoplay'] == 'yes' && ! empty( $settings['autoplay_speed']['size'] ) ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-autoplay', $settings['autoplay_speed']['size'] );
        } else {
            $this->add_render_attribute( 'info-box-carousel', 'data-autoplay', '999999' );
        }
        if ( $settings['infinite_loop'] == 'yes' ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-loop', '1' );
        }
        if ( $settings['grab_cursor'] == 'yes' ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-grab-cursor', '1' );
        }
        if ( $settings['arrows'] == 'yes' ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-arrows', '1' );
        }
        if ( $settings['dots'] == 'yes' ) {
            $this->add_render_attribute( 'info-box-carousel', 'data-dots', '1' );
            $this->add_render_attribute( 'info-box-carousel', 'data-pagination-type', $settings['pagination_type'] );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'info-box-carousel-wrap' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'info-box-carousel' ); ?>>
                <div class="swiper-wrapper">
                <?php
                $i = 1;

                foreach ( $settings['pp_info_boxes'] as $item ) :

                    if ( $item['link_type'] != 'none' ) {
                        if ( ! empty( $item['link']['url'] ) ) {
                            
                            $this->add_render_attribute( 'link' . $i, 'href', $item['link']['url'] );

                            if ( $item['link']['is_external'] ) {
                                $this->add_render_attribute( 'link' . $i, 'target', '_blank' );
                            }

                            if ( $item['link']['nofollow'] ) {
                                $this->add_render_attribute( 'link' . $i, 'rel', 'nofollow' );
                            }
                            
                            if ( $item['link_type'] == 'title' ) {
                                $pp_title_html_tag = 'a';
                            }
                            elseif ( $item['link_type'] == 'button' ) {
                                $pp_button_html_tag = 'a';
                            }
                        }
                    }
                    ?>
                    <div class="swiper-slide">
                        <?php if ( $item['link_type'] == 'box' ) { ?>
                            <a <?php echo $this->get_render_attribute_string( 'link' . $i ); ?>>
                        <?php } ?>
                        <?php if ( $item['icon_type'] != 'none' ) { ?>
                            <div class="pp-info-box-icon-wrap">
                                <?php if ( $item['link_type'] == 'icon' ) { ?>
                                    <a <?php echo $this->get_render_attribute_string( 'link' . $i ); ?>>
                                <?php } ?>
                                <span <?php echo $this->get_render_attribute_string( 'icon' ); ?>>
                                    <?php if ( $item['icon_type'] == 'icon' ) { ?>
                                        <span class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></span>
                                    <?php } elseif ( $item['icon_type'] == 'image' ) { ?>
                                        <img src="<?php echo esc_url( $item['image']['url'] ); ?>">
                                    <?php } elseif ( $item['icon_type'] == 'text' ) {
                                        echo $item['icon_text'];
                                    } ?>
                                </span>
                                <?php if ( $item['link_type'] == 'icon' ) { ?>
                                    </a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class="pp-info-box-content">
                            <div class="pp-info-box-title-wrap">
                                <?php
                                    if ( ! empty( $item['title'] ) ) {
                                        printf( '<%1$s %2$s %3$s>', $pp_title_html_tag, $this->get_render_attribute_string( 'title-container' . $i ), $this->get_render_attribute_string( 'link' . $i ) );
                                        printf( '<%1$s class="pp-info-box-title">', $settings['title_html_tag'] );
                                        echo $item['title'];
                                        printf( '</%1$s>', $settings['title_html_tag'] );
                                        printf( '</%1$s>', $pp_title_html_tag );
                                    }

                                    if ( ! empty( $item['subtitle'] ) ) {
                                        printf( '<%1$s class="pp-info-box-subtitle">', $settings['sub_title_html_tag'] );
                                        echo $item['subtitle'];
                                        printf( '</%1$s>', $settings['sub_title_html_tag'] );
                                    }
                                ?>
                            </div>

                            <?php if ( $settings['divider_title_switch'] == 'yes' ) { ?>
                                <div class="pp-info-box-divider-wrap">
                                    <div class="pp-info-box-divider"></div>
                                </div>
                            <?php } ?>

                            <?php if ( ! empty( $item['description'] ) ) { ?>
                                <div class="pp-info-box-description">
                                    <?php echo $this->parse_text_editor( nl2br( $item['description'] ) ); ?>
                                </div>
                            <?php } ?>
                            <?php if ( $item['link_type'] == 'button' ) { ?>
                                <div class="pp-info-box-footer">
                                    <<?php echo $pp_button_html_tag. ' ' . $this->get_render_attribute_string( 'info-box-button' . $i ) . $this->get_render_attribute_string( 'link' . $i ); ?>>
                                        <div <?php echo $this->get_render_attribute_string( 'info-box-button' ); ?>>
                                            <?php if ( ! empty( $item['button_icon'] ) && $item['button_icon_position'] == 'before' ) { ?>
                                                <span class="pp-button-icon <?php echo esc_attr( $item['button_icon'] ); ?>" aria-hidden="true"></span>
                                            <?php } ?>
                                            <?php if ( ! empty( $item['button_text'] ) ) { ?>
                                                <span class="pp-button-text">
                                                    <?php echo esc_attr( $item['button_text'] ); ?>
                                                </span>
                                            <?php } ?>
                                            <?php if ( ! empty( $item['button_icon'] ) && $item['button_icon_position'] == 'after' ) { ?>
                                                <span class="pp-button-icon <?php echo esc_attr( $item['button_icon'] ); ?>" aria-hidden="true"></span>
                                            <?php } ?>
                                        </div>
                                    </<?php echo $pp_button_html_tag; ?>>
                                </div>
                            <?php } ?>
                            <?php if ( $item['link_type'] == 'box' ) { ?>
                                </a>
                            <?php } ?>
                        </div><!-- .pp-info-box-content -->
                    </div>
                <?php $i++; endforeach; ?>
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
	 * Render info-box carousel dots output on the frontend.
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
	 * Render info-box carousel arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_arrows() {
        $settings = $this->get_settings_for_display();

        if ( $settings['arrows'] == 'yes' ) { ?>
            <?php
                if ( $settings['arrow'] ) {
                    $pa_next_arrow = $settings['arrow'];
                    $pa_prev_arrow = str_replace("right","left",$settings['arrow']);
                }
                else {
                    $pa_next_arrow = 'fa fa-angle-right';
                    $pa_prev_arrow = 'fa fa-angle-left';
                }
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

    protected function _content_template() {}

}