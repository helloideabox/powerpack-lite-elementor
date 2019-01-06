<?php
namespace PowerpackElementsLite\Modules\InfoList\Widgets;

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
 * Info List Widget
 */
class Info_List extends Powerpack_Widget {
    
    /**
	 * Retrieve info list widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-info-list';
    }

    /**
	 * Retrieve info list widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Info List', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the info list widget belongs to.
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
	 * Retrieve info list widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-info-list power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the instagram feed widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'powerpack-frontend'
        ];
    }

    /**
	 * Register info list widget controls.
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
         * Content Tab: List Items
         */
        $this->start_controls_section(
            'section_list',
            [
                'label'                 => __( 'List Items', 'power-pack' ),
            ]
        );
        
        $this->add_control(
			'list_items',
			[
				'label'                 => '',
				'type'                  => Controls_Manager::REPEATER,
				'default'               => [
					[
						'text'      => __( 'List Item #1', 'power-pack' ),
                        'list_icon' => __('fa fa-check','power-pack')
					],
                    [
						'text'      => __( 'List Item #2', 'power-pack' ),
                        'list_icon' => __('fa fa-check','power-pack')
					],
				],
				'fields'                => [
					[
						'name'        => 'text',
						'label'       => __( 'Title', 'power-pack' ),
						'label_block' => true,
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'  => true,
                        ],
                        'default'     => __('List Item #1','power-pack')
					],
					[
						'name'        => 'description',
						'label'       => __( 'Description', 'power-pack' ),
						'label_block' => true,
						'type'        => Controls_Manager::TEXTAREA,
                        'dynamic'     => [
                            'active'  => true,
                        ],
                        'default'     => __('List Item Description','power-pack')
					],
                    [
						'name'        => 'pp_icon_type',
						'label'       => esc_html__( 'Icon Type', 'power-pack' ),
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
                                'icon'  => 'fa fa-hashtag',
                            ],
                        ],
                        'default'       => 'icon',
					],
                    [
						'name'              => 'list_icon',
						'label'             => __( 'Icon', 'power-pack' ),
						'label_block'       => true,
						'type'              => Controls_Manager::ICON,
				        'default'           => 'fa fa-check',
				        'condition'         => [
                            'pp_icon_type' => 'icon',
                        ],
					],
                    [
						'name'              => 'list_image',
						'label'             => __( 'Image', 'power-pack' ),
						'label_block'       => true,
						'type'              => Controls_Manager::MEDIA,
				        'default'           => [
                            'url' => Utils::get_placeholder_image_src(),
                         ],
				        'condition'         => [
                            'pp_icon_type' => 'image',
                        ],
					],
					[
						'name'              => 'icon_text',
						'label'             => __( 'Title', 'power-pack' ),
						'label_block'       => true,
						'type'              => Controls_Manager::TEXT,
                        'default'           => __('1','power-pack'),
				        'condition'         => [
                            'pp_icon_type' => 'text',
                        ],
					],
                    [
						'name'              => 'link_type',
                        'label'             => __( 'Link Type', 'power-pack' ),
                        'type'              => Controls_Manager::SELECT,
                        'options'           => [
                            'none'      => __( 'None', 'power-pack' ),
                            'box'       => __( 'Box', 'power-pack' ),
                            'title'     => __( 'Title', 'power-pack' ),
                            'button'    => __( 'Button', 'power-pack' ),
                        ],
                        'default'           => 'none',
                    ],
                    [
                        'name'              => 'button_text',
                        'label'             => __( 'Button Text', 'power-pack' ),
                        'type'              => Controls_Manager::TEXT,
                        'dynamic'           => [
                            'active'    => true,
                        ],
                        'default'           => __( 'Get Started', 'power-pack' ),
                        'condition'         => [
                            'link_type' => 'button',
                        ],
                    ],
                    [
                        'name'              => 'button_icon',
                        'label'             => __( 'Button Icon', 'power-pack' ),
                        'type'              => Controls_Manager::ICON,
                        'default'           => '',
                        'condition'         => [
                            'link_type' => 'button',
                        ],
                    ],
                    [
                        'name'              => 'button_icon_position',
                        'label'             => __( 'Icon Position', 'power-pack' ),
                        'type'              => Controls_Manager::SELECT,
                        'default'           => 'after',
                        'options'           => [
                            'after'     => __( 'After', 'power-pack' ),
                            'before'    => __( 'Before', 'power-pack' ),
                        ],
                        'condition'         => [
                            'link_type'     => 'button',
                            'button_icon!'  => '',
                        ],
                    ],
					[
						'name'              => 'link',
						'label'             => __( 'Link', 'power-pack' ),
						'type'              => Controls_Manager::URL,
                        'dynamic'           => [
                            'active'  => true,
                        ],
						'label_block'       => true,
						'placeholder'       => __( 'http://your-link.com', 'power-pack' ),
                        'default'               => [
                            'url' => '#',
                        ],
                        'conditions'        => [
							'terms' => [
								[
									'name' => 'link_type',
									'operator' => '!=',
									'value' => 'none',
								],
							],
						],
					],
				],
				'title_field'       => '{{{ text }}}',
			]
		);

		$this->add_control(
			'connector',
			[
				'label'                 => __( 'Connector', 'power-pack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: List
         */
        $this->start_controls_section(
            'section_list_style',
            [
                'label'                 => __( 'List', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'items_spacing',
			[
				'label'                 => __( 'Items Spacing', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                ],
				'range'                 => [
					'px' => [
						'max' => 50,
					],
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
                'label'                 => __( 'Position', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => true,
                'default'               => 'left',
                'options'               => [
                    'left'      => [
                        'title' => __( 'Left', 'power-pack' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'top'       => [
                        'title' => __( 'Top', 'power-pack' ),
                        'icon'  => 'eicon-v-align-top',
                    ],
                    'right'     => [
                        'title' => __( 'Right', 'power-pack' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
			]
		);

		$this->add_control(
			'responsive_breakpoint',
			[
				'label'                 => __( 'Responsive Breakpoint', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
                'label_block'           => true,
				'default'               => 'mobile',
				'options'               => [
					''         => __( 'None', 'power-pack' ),
					'tablet'   => __( 'Tablet', 'power-pack' ),
					'mobile'   => __( 'Mobile', 'power-pack' ),
				],
                'condition'             => [
                    'icon_position' => 'top',
                ],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Connector
         */
        $this->start_controls_section(
            'section_connector_style',
            [
                'label'                 => __( 'Connector', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'connector' => 'yes',
                ],
            ]
        );

		$this->add_control(
			'connector_color',
			[
				'label'                 => __( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-list-connector' => 'border-color: {{VALUE}};',
				],
                'condition'             => [
                    'connector' => 'yes',
                ],
			]
		);

		$this->add_control(
			'connector_style',
			[
				'label'                 => __( 'Style', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'solid'    => __( 'Solid', 'power-pack' ),
					'double'   => __( 'Double', 'power-pack' ),
					'dotted'   => __( 'Dotted', 'power-pack' ),
					'dashed'   => __( 'Dashed', 'power-pack' ),
				],
				'default'               => 'solid',
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items.icon-left .pp-info-list-connector' => 'border-right-style: {{VALUE}};',
					'{{WRAPPER}} .pp-list-items.icon-right .pp-info-list-connector' => 'border-left-style: {{VALUE}};',
					'{{WRAPPER}} .pp-list-items.icon-top .pp-info-list-connector' => 'border-top-style: {{VALUE}};',
				],
                'condition'             => [
                    'connector' => 'yes',
                ],
			]
		);

		$this->add_control(
			'connector_width',
			[
				'label'                 => __( 'Width', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size' => 1,
				],
				'range'                 => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
                'condition'             => [
                    'connector' => 'yes',
                ],
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Icon
         */
        $this->start_controls_section(
            'section_icon_style',
            [
                'label'                 => __( 'Icon', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
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
			'icon_color',
			[
				'label'                 => __( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items .pp-info-list-icon' => 'color: {{VALUE}};',
				],
				'scheme'                => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_2,
				],
			]
		);

		$this->add_control(
			'icon_bg_color',
			[
				'label'                 => __( 'Background Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'                 => __( 'Size', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size' => 14,
				],
				'range'                 => [
					'px' => [
						'min' => 6,
						'max' => 100,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items .pp-info-list-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-list-items .pp-info-list-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_box_size',
			[
				'label'                 => __( 'Box Size', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size' => 14,
				],
				'range'                 => [
					'px' => [
						'min' => 6,
						'max' => 200,
					],
				],
			]
		);

		$this->add_control(
			'icon_spacing',
			[
				'label'                 => __( 'Spacing', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size' => 8,
				],
				'range'                 => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items.icon-left .pp-infolist-icon-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-list-items.icon-right .pp-infolist-icon-wrapper' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-list-items.icon-top .pp-infolist-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
				'selector'              => '{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper, {{WRAPPER}} .pp-list-items .pp-info-list-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'icon_color_hover',
			[
				'label'                 => __( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper:hover .pp-info-list-icon' => 'color: {{VALUE}};',
				],
				'scheme'                => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_2,
				],
			]
		);

		$this->add_control(
			'icon_bg_color_hover',
			[
				'label'                 => __( 'Background Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper:hover' => 'background: {{VALUE}};',
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
					'{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper:hover' => 'border-color: {{VALUE}};',
				],
				'scheme'                => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_2,
				],
			]
		);

		$this->add_control(
			'icon_hover_animation',
			[
				'label'                 => __( 'Animation', 'power-pack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
			]
		);

        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Title
         */
        $this->start_controls_section(
            'section_content_style',
            [
                'label'                 => __( 'Content', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'content_align',
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
			]
		);
        
        $this->add_control(
            'title_heading',
            [
                'label'                 => __( 'Title', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );

		$this->add_control(
			'title_color',
			[
				'label'                 => __( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-list-title' => 'color: {{VALUE}};',
				],
				'scheme'                => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_2,
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-list-title',
            ]
        );
        
        $this->add_control(
            'description_heading',
            [
                'label'                 => __( 'Description', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );

		$this->add_control(
			'description_color',
			[
				'label'                 => __( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-list-description' => 'color: {{VALUE}};',
				],
				'scheme'                => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_2,
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'description_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-list-description',
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
				'default'               => 'sm',
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
                    '{{WRAPPER}} .pp-info-list-button' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-info-list-button' => 'color: {{VALUE}}',
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
				'selector'              => '{{WRAPPER}} .pp-info-list-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-list-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'button_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-list-button',
            ]
        );

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-list-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-info-list-button',
			]
		);
        
        $this->add_control(
            'info_box_button_icon_heading',
            [
                'label'                 => __( 'Button Icon', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );

		$this->add_responsive_control(
			'button_icon_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'       => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-list-button .pp-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
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
                    '{{WRAPPER}} .pp-info-list-button:hover' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-info-list-button:hover' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-info-list-button:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
			'button_animation',
			[
				'label'                 => __( 'Animation', 'power-pack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-info-list-button:hover',
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();

    }

    /**
	 * Render info list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'info-list', 'class', [
				'pp-info-list-container',
				'pp-list-container',
			]
		);
        
        if ( $settings['responsive_breakpoint'] != '' ) {
            $this->add_render_attribute( 'info-list', 'class', 'pp-info-list-stack-' . $settings['responsive_breakpoint'] );
        }
        
        $this->add_render_attribute( 'info-list-items', 'class', 'pp-list-items' );
        
        $this->add_render_attribute( 'list-item', 'class', 'pp-info-list-item' );
        
        $this->add_render_attribute( 'list-item', 'style', 'text-align: '.$settings['content_align'].';' );
        
        $this->add_render_attribute( 'icon', 'class', 'pp-info-list-icon' );
        
        $this->add_render_attribute( 'connector', 'class', 'pp-info-list-connector' );

        $pp_box_size = $settings['icon_box_size']['size'];
        $pp_connector_left = $pp_box_size / 2;

        if ( $settings['icon_position'] == 'right' ) {
            $this->add_render_attribute( 'info-list-items', 'class', 'icon-right' );
        
            $this->add_render_attribute( 'list-item', 'style', 'margin-bottom: '.$settings['items_spacing']['size'].'px;' );
            
            $this->add_render_attribute( 'connector', 'style', 'height: calc( 100% - ('.$pp_box_size.'px - '.$settings['items_spacing']['size'].'px) );' );
        
            $this->add_render_attribute( 'connector', 'style', 'top: calc( ( 100% - '.$pp_box_size.'px )/2 + '.$pp_box_size.'px );' );

            $this->add_render_attribute( 'connector', 'style', 'right: '.$pp_connector_left.'px;' );
            
            $this->add_render_attribute( 'connector', 'style', 'border-left-width: '.$settings['connector_width']['size'].'px;' );
        }
        else if ( $settings['icon_position'] == 'top' ) {
            $this->add_render_attribute( 'info-list-items', 'class', 'icon-top' );
        
            $this->add_render_attribute( 'list-item', 'style', 'margin-right: '.$settings['items_spacing']['size'].'px;' );
        
            $this->add_render_attribute( 'connector', 'style', 'width: calc( 100% - ('.$pp_box_size.'px - '.$settings['items_spacing']['size'].'px) );' );
        
            if ( $settings['content_align'] == 'left' ) {
                $this->add_render_attribute( 'connector', 'style', 'left: '.$pp_box_size.'px;');
            } elseif ( $settings['content_align'] == 'right' ) {
                $this->add_render_attribute( 'connector', 'style', 'left: 100%;');
            } else {
                $this->add_render_attribute( 'connector', 'style', 'left: calc( ( 100% - '.$pp_box_size.'px )/2 + '.$pp_box_size.'px );' );
            }

            $this->add_render_attribute( 'connector', 'style', 'top: '.$pp_connector_left.'px;' );
            
            $this->add_render_attribute( 'connector', 'style', 'border-top-width: '.$settings['connector_width']['size'].'px;' );
        
            $pp_list_items_count = count( $settings['list_items'] );
            $pp_list_items_width = ( 100 / $pp_list_items_count );
            $this->add_render_attribute( 'list-item', 'style', 'width: '.$pp_list_items_width.'%;' );
        }
        else {
            $this->add_render_attribute( 'info-list-items', 'class', 'icon-left' );
        
            $this->add_render_attribute( 'list-item', 'style', 'margin-bottom: '.$settings['items_spacing']['size'].'px;' );
            
            $this->add_render_attribute( 'connector', 'style', 'height: calc( 100% - ('.$pp_box_size.'px - '.$settings['items_spacing']['size'].'px) );' );
        
            $this->add_render_attribute( 'connector', 'style', 'top: calc( ( 100% - '.$pp_box_size.'px )/2 + '.$pp_box_size.'px );' );
            
            $this->add_render_attribute( 'connector', 'style', 'left: '.$pp_connector_left.'px;' );
            
            $this->add_render_attribute( 'connector', 'style', 'border-right-width: '.$settings['connector_width']['size'].'px;' );
        }
        
        $this->add_render_attribute( 'info-list-button', 'class', [
				'pp-info-list-button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			]
		);

		if ( $settings['button_animation'] ) {
			$this->add_render_attribute( 'info-list-button', 'class', 'elementor-animation-' . $settings['button_animation'] );
		}
        
        $i = 1;
        ?>
        <div <?php echo $this->get_render_attribute_string( 'info-list' ); ?>>
            <ul <?php echo $this->get_render_attribute_string( 'info-list-items' ); ?>>
                <?php foreach ( $settings['list_items'] as $index => $item ) : ?>
                    <?php if ( $item['text'] ) { ?>
                        <li <?php echo $this->get_render_attribute_string( 'list-item' ); ?>>
                            <?php
                                $text_key = $this->get_repeater_setting_key( 'text', 'list_items', $index );
                                $this->add_render_attribute( $text_key, 'class', 'pp-info-list-title' );
                                //$this->add_inline_editing_attributes( $text_key, 'none' );
            
                                $description_key = $this->get_repeater_setting_key( 'description', 'list_items', $index );
                                $this->add_render_attribute( $description_key, 'class', 'pp-info-list-description' );
                                //$this->add_inline_editing_attributes( $description_key, 'none' );

                                $button_key = $this->get_repeater_setting_key( 'button-wrap', 'list_items', $index );
                                $this->add_render_attribute( $button_key, 'class', 'pp-info-list-button-wrapper pp-info-list-button-icon-'.$item['button_icon_position'] );

                                if ( ! empty( $item['link']['url'] ) ) {
                                    $link_key = 'link_' . $i;

                                    $this->add_render_attribute( $link_key, 'href', esc_url( $item['link']['url'] ) );

                                    if ( $item['link']['is_external'] ) {
                                        $this->add_render_attribute( $link_key, 'target', '_blank' );
                                    }

                                    if ( $item['link']['nofollow'] ) {
                                        $this->add_render_attribute( $link_key, 'rel', 'nofollow' );
                                    }
                                }
                                if ( $item['pp_icon_type'] != 'none' ) {
                                    $icon_key = 'icon_' . $i;
                                    $this->add_render_attribute( $icon_key, 'class', 'pp-infolist-icon-wrapper' );
                                    $this->add_render_attribute( $icon_key, 'style', 'height: '.$pp_box_size.'px; width: '.$pp_box_size.'px;' );
                                    
                                    if ( $settings['icon_hover_animation'] != '' ) {
                                        $icon_animation = 'elementor-animation-' . $settings['icon_hover_animation'];
                                    } else {
                                        $icon_animation = '';
                                    }
                                    ?>
                                    <div <?php echo $this->get_render_attribute_string( $icon_key ); ?>>
                                        <?php
                                            if ( $item['pp_icon_type'] == 'icon' ) {
                                                printf( '<span class="pp-info-list-icon %1$s %2$s"></span>', esc_attr( $item['list_icon'] ), $icon_animation );
                                            } elseif ( $item['pp_icon_type'] == 'image' ) {
                                                printf( '<span class="pp-info-list-image %2$s"><img src="%1$s"></span>', esc_url( $item['list_image']['url'] ), $icon_animation );
                                            } elseif ( $item['pp_icon_type'] == 'text' ) {
                                                printf( '<span class="pp-info-list-icon pp-info-list-number %2$s">%1$s</span>', esc_attr( $item['icon_text'] ), $icon_animation );
                                            }
                                        ?>
                                    </div>
                                    <?php
                                }
                            ?>
                            <div class="pp-infolist-content-wrapper">
                                <?php if ( ! empty( $item['link']['url'] ) && $item['link_type'] == 'box' ) { ?>
                                    <a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                                <?php } ?>
                                <div <?php echo $this->get_render_attribute_string( $text_key ); ?>>
                                    <?php if ( ! empty( $item['link']['url'] ) && $item['link_type'] == 'title' ) { ?>
                                        <a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                                    <?php } ?>
                                        <?php echo $item['text']; ?>
                                    <?php if ( ! empty( $item['link']['url'] ) && $item['link_type'] == 'title' ) { ?>
                                        </a>
                                    <?php } ?>
                                </div>
                                <?php
                                    printf( '<div %1$s>%2$s</div>', $this->get_render_attribute_string( $description_key ), $item['description'] );
                                ?>
                                <?php if ( ! empty( $item['link']['url'] ) && $item['link_type'] == 'button' ) { ?>
                                    <div <?php echo $this->get_render_attribute_string( $button_key ); ?>>
                                        <a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                                            <div <?php echo $this->get_render_attribute_string( 'info-list-button' ); ?>>
                                                <?php if ( ! empty( $item['button_icon'] ) ) { ?>
                                                    <span class="pp-button-icon <?php echo esc_attr( $item['button_icon'] ); ?>" aria-hidden="true"></span>
                                                <?php } ?>
                                                <?php if ( ! empty( $item['button_text'] ) ) { ?>
                                                    <span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
                                                        <?php echo esc_attr( $item['button_text'] ); ?>
                                                    </span>
                                                <?php } ?>
                                            </div>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php
                                if ( ! empty( $item['link']['url'] ) && $item['link_type'] == 'box' ) {
                                    echo '</a>';
                                }
                                ?>
                            </div>
                            <?php if ( $settings['connector'] == 'yes' ) { ?>
                                <div <?php echo $this->get_render_attribute_string( 'connector' ); ?>></div>
                            <?php } ?>
                        </li>
                    <?php } ?>
                <?php $i++; endforeach; ?>
            </ul>
        </div>
        <?php
    }

    /**
	 * Render info list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <#
        view.addRenderAttribute(
            'info-list',
            {
                'class': [ 'pp-info-list-container', 'pp-list-container', 'pp-info-list-stack-' + settings.responsive_breakpoint ],
            }
        );
        #>
        <div {{{ view.getRenderAttributeString( 'info-list' ) }}}>
            <#
                var pp_icon_box_size = settings.icon_box_size.size;
                var pp_connector_left = ( settings.icon_box_size.size ) / 2;

                if ( settings.icon_position == 'right' ) {
                    var icon_class = 'icon-right';
                    var item_spacing_pos = 'margin-bottom';
                    var pp_connector_size = 'height';
                    var pp_connector_width = 'border-left-width';
                    var pp_connector_horizontal_pos = 'right';
                    var pp_connector_vertical_pos = 'top';
                } else if ( settings.icon_position == 'top' ) {
                    var icon_class = 'icon-top';
                    var item_spacing_pos = 'margin-right';
                    var pp_connector_size = 'width';
                    var pp_connector_width = 'border-top-width';
                    var pp_connector_horizontal_pos = 'top';
                    var pp_connector_vertical_pos = 'left';

                    var pp_list_items_count = settings.list_items.length;
                    var pp_list_items_width = ( 100 / pp_list_items_count );
                } else {
                    var icon_class = 'icon-left';
                    var item_spacing_pos = 'margin-bottom';
                    var pp_connector_size = 'height';
                    var pp_connector_width = 'border-right-width';
                    var pp_connector_horizontal_pos = 'left';
                    var pp_connector_vertical_pos = 'top';
                }
            #>
            <ul class="pp-list-items {{ icon_class }}">
                <# var i = 1; #>
                <# _.each( settings.list_items, function( item ) { #>
                    <li class="pp-info-list-item" style="{{ item_spacing_pos }}: {{ settings.items_spacing.size }}px; width: {{ pp_list_items_width }}%; text-align: {{settings.content_align}};">
                        <# if ( item.pp_icon_type != 'none' ) { #>
                            <div class="pp-infolist-icon-wrapper" style="height: {{ settings.icon_box_size.size }}px; width: {{ settings.icon_box_size.size }}px">
                                <# if ( item.pp_icon_type == 'icon' ) { #>
                                    <span class="pp-info-list-icon elementor-animation-{{ settings.icon_hover_animation }} {{ item.list_icon }}" aria-hidden="true"></span>
                                <# } else if ( item.pp_icon_type == 'image' ) { #>
                                    <span class="pp-info-list-image elementor-animation-{{ settings.icon_hover_animation }}">
                                        <img src="{{ item.list_image.url }}">
                                    </span>
                                <# } else if ( item.pp_icon_type == 'text' ) { #>
                                    <span class="pp-info-list-icon elementor-animation-{{ settings.icon_hover_animation }}">
                                        {{ item.icon_text }}
                                    </span>
                                <# } #>
                            </div>
                        <# } #>
                        <div class="pp-infolist-content-wrapper">
                            <# if ( item.link.url != '' && item.link_type == 'box' ) { #>
                                <a href="{{ item.link.url }}">
                            <# } #>
                                <div class="pp-info-list-title">
                                    <# if ( item.link.url != '' && item.link_type == 'title' ) { #>
                                        <a href="{{ item.link.url }}">
                                    <# } #>
                                    {{{ item.text }}}
                                    <# if ( item.link.url != '' && item.link_type == 'title' ) { #>
                                        </a>
                                    <# } #>
                                </div>
                                <div class="pp-info-list-description">
                                    {{{ item.description }}}
                                </div>
                                <# if ( item.link.url != '' && item.link_type == 'button' ) { #>
                                    <div class="pp-info-list-button-wrapper pp-info-list-button-icon-{{ item.button_icon_position }}">
                                        <a href="{{ item.link.url }}">
                                            <div class="pp-info-list-button elementor-button elementor-size-{{ settings.button_size }} elementor-animation-{{ settings.button_animation }}">
                                                <# if ( item.button_icon != '' ) { #>
                                                    <span class="pp-button-icon {{{ item.button_icon }}}" aria-hidden="true"></span>
                                                <# } #>
                                                <# if ( item.button_text != '' ) { #>
                                                    <span class="pp-button-text">
                                                        {{{ item.button_text }}}
                                                    </span>
                                                <# } #>
                                            </div>
                                        </a>
                                    </div>
                                <# } #>
                            <# if ( item.link_type == 'box' ) { #>
                                </a>
                            <# } #>
                        </div>
                        <# if ( settings.connector == 'yes' ) { #>
                            <# if ( settings.icon_position == 'top' && settings.content_align == 'left' ) { #>
                                <div class="pp-info-list-connector" style="{{ pp_connector_size }}: calc( 100% - ( {{ pp_icon_box_size }}px - {{ settings.items_spacing.size }}px ) ); {{ pp_connector_horizontal_pos }}: {{ pp_connector_left }}px; {{ pp_connector_vertical_pos }}: {{ pp_icon_box_size }}px; {{ pp_connector_width }}: {{ settings.connector_width.size }}px"></div>
                            <# } else if ( settings.icon_position == 'top' && settings.content_align == 'right' ) { #>
                                <div class="pp-info-list-connector" style="{{ pp_connector_size }}: calc( 100% - ( {{ pp_icon_box_size }}px - {{ settings.items_spacing.size }}px ) ); {{ pp_connector_horizontal_pos }}: {{ pp_connector_left }}px; {{ pp_connector_vertical_pos }}: 100%; {{ pp_connector_width }}: {{ settings.connector_width.size }}px"></div>
                            <# } else { #>
                                <div class="pp-info-list-connector" style="{{ pp_connector_size }}: calc( 100% - ( {{ pp_icon_box_size }}px - {{ settings.items_spacing.size }}px ) ); {{ pp_connector_horizontal_pos }}: {{ pp_connector_left }}px; {{ pp_connector_vertical_pos }}: calc( ( 100% - {{ pp_icon_box_size }}px )/2 + {{ pp_icon_box_size }}px ); {{ pp_connector_width }}: {{ settings.connector_width.size }}px"></div>
                            <# } #>
                        <# } #>
                    </li>
                <# i++ } ); #>
            </ul>
        </div>
        <?php
    }
}