<?php
namespace PowerpackElementsLite\Modules\BusinessHours\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Business Hours Widget
 */
class Business_Hours extends Powerpack_Widget {
    
    /**
	 * Retrieve business hours widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-business-hours';
    }

    /**
	 * Retrieve business hours widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Business Hours', 'powerpack' );
    }

    /**
	 * Retrieve the list of categories the business hours widget belongs to.
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
	 * Retrieve business hours widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-business-hours power-pack-admin-icon';
    }

    /**
	 * Register business hours widget controls.
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
         * Content Tab: Business Hours
         */
        $this->start_controls_section(
            'section_price_menu',
            [
                'label'             => __( 'Business Hours', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'business_timings',
            [
                'label'                => __( 'Business Timings', 'powerpack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'predefined',
                'options'              => [
                    'predefined'    => __( 'Predefined', 'powerpack' ),
                    'custom'        => __( 'Custom', 'powerpack' ),
                ],
            ]
        );
        
        $pp_hours = [
            '00:00'    => '12:00 AM',
            '00:30'    => '12:30 AM',
            '01:00'    => '1:00 AM',
            '01:30'    => '1:30 AM',
            '02:00'    => '2:00 AM',
            '02:30'    => '2:30 AM',
            '03:00'    => '3:00 AM',
            '03:30'    => '3:30 AM',
            '04:00'    => '4:00 AM',
            '04:30'    => '4:30 AM',
            '05:00'    => '5:00 AM',
            '05:30'    => '5:30 AM',
            '06:00'    => '6:00 AM',
            '06:30'    => '6:30 AM',
            '07:00'    => '7:00 AM',
            '07:30'    => '7:30 AM',
            '08:00'    => '8:00 AM',
            '08:30'    => '8:30 AM',
            '09:00'    => '9:00 AM',
            '09:30'    => '9:30 AM',
            '10:00'    => '10:00 AM',
            '10:30'    => '10:30 AM',
            '11:00'    => '11:00 AM',
            '11:30'    => '11:30 AM',
            '12:00'    => '12:00 PM',
            '12:30'    => '12:30 PM',
            '13:00'    => '1:00 PM',
            '13:30'    => '1:30 PM',
            '14:00'    => '2:00 PM',
            '14:30'    => '2:30 PM',
            '15:00'    => '3:00 PM',
            '15:30'    => '3:30 PM',
            '16:00'    => '4:00 PM',
            '16:30'    => '4:30 PM',
            '17:00'    => '5:00 PM',
            '17:30'    => '5:30 PM',
            '18:00'    => '6:00 PM',
            '18:30'    => '6:30 PM',
            '19:00'    => '7:00 PM',
            '19:30'    => '7:30 PM',
            '20:00'    => '8:00 PM',
            '20:30'    => '8:30 PM',
            '21:00'    => '9:00 PM',
            '21:30'    => '9:30 PM',
            '22:00'    => '10:00 PM',
            '22:30'    => '10:30 PM',
            '23:00'    => '11:00 PM',
            '23:30'    => '11:30 PM',
            '24:00'    => '12:00 PM',
            '24:30'    => '12:30 PM',
        ];

		$this->add_control(
			'business_hours',
			[
				'label'             => '',
				'type'              => Controls_Manager::REPEATER,
				'default'           => [
					[
						'day' => 'Monday',
					],
					[
						'day' => 'Tuesday',
					],
					[
						'day' => 'Wednesday',
					],
					[
						'day' => 'Thursday',
					],
					[
						'day' => 'Friday',
					],
					[
						'day'             => 'Saturday',
						'closed'          => 'yes',
						'highlight'       => 'yes',
						'highlight_color' => '#bc1705',
					],
					[
						'day'             => 'Sunday',
						'closed'          => 'yes',
						'highlight'       => 'yes',
						'highlight_color' => '#bc1705',
					],
				],
				'fields'            => [
                    [
                        'name'              => 'day',
                        'label'             => __( 'Day', 'powerpack' ),
                        'type'              => Controls_Manager::SELECT,
                        'default'           => 'Monday',
                        'options'           => [
                            'Monday'    => __( 'Monday', 'powerpack' ),
                            'Tuesday'   => __( 'Tuesday', 'powerpack' ),
                            'Wednesday' => __( 'Wednesday', 'powerpack' ),
                            'Thursday'  => __( 'Thursday', 'powerpack' ),
                            'Friday'    => __( 'Friday', 'powerpack' ),
                            'Saturday'  => __( 'Saturday', 'powerpack' ),
                            'Sunday'    => __( 'Sunday', 'powerpack' ),
                        ],
                    ],
                    [
                        'name'              => 'closed',
                        'label'             => __( 'Closed?', 'powerpack' ),
                        'type'              => Controls_Manager::SWITCHER,
                        'default'           => 'no',
                        'label_on'          => __( 'No', 'powerpack' ),
                        'label_off'         => __( 'Yes', 'powerpack' ),
                        'return_value'      => 'no',
                    ],
                    [
                        'name'              => 'opening_hours',
                        'label'             => __( 'Opening Hours', 'powerpack' ),
                        'type'              => Controls_Manager::SELECT,
                        'default'           => '09:00',
                        'options'           => $pp_hours,
                        'condition'         => [
                            'closed' => 'no',
                        ],
                    ],
                    [
                        'name'              => 'closing_hours',
                        'label'             => __( 'Closing Hours', 'powerpack' ),
                        'type'              => Controls_Manager::SELECT,
                        'default'           => '17:00',
                        'options'           => $pp_hours,
                        'condition'         => [
                            'closed' => 'no',
                        ],
                    ],
                    [
                        'name'              => 'closed_text',
                        'label'             => __( 'Closed Text', 'powerpack' ),
                        'type'              => Controls_Manager::TEXT,
                        'label_block'       => true,
                        'placeholder'       => __( 'Closed', 'powerpack' ),
                        'default'           => __( 'Closed', 'powerpack' ),
                        'conditions'        => [
                            'terms' => [
                                [
                                    'name' => 'closed',
                                    'operator' => '!=',
                                    'value' => 'no',
                                ],
                            ],
                        ],
                    ],
                    [
                        'name'              => 'highlight',
                        'label'             => __( 'Highlight', 'powerpack' ),
                        'type'              => Controls_Manager::SWITCHER,
                        'default'           => 'no',
                        'label_on'          => __( 'Yes', 'powerpack' ),
                        'label_off'         => __( 'No', 'powerpack' ),
                        'return_value'      => 'yes',
                    ],
                    [
                        'name'              => 'highlight_bg',
                        'label'             => __( 'Background Color', 'powerpack' ),
                        'type'              => Controls_Manager::COLOR,
                        'default'           => '',
                        'selectors'         => [
                            '{{WRAPPER}} .pp-business-hours .pp-business-hours-row{{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                        ],
                        'condition'         => [
                            'highlight' => 'yes',
                        ],
                    ],
                    [
                        'name'              => 'highlight_color',
                        'label'             => __( 'Text Color', 'powerpack' ),
                        'type'              => Controls_Manager::COLOR,
                        'default'           => '',
                        'selectors'         => [
                            '{{WRAPPER}} .pp-business-hours .pp-business-hours-row{{CURRENT_ITEM}} .pp-business-day, {{WRAPPER}} .pp-business-hours .pp-business-hours-row{{CURRENT_ITEM}} .pp-business-timing' => 'color: {{VALUE}}',
                        ],
                        'condition'         => [
                            'highlight' => 'yes',
                        ],
                    ]
				],
				'title_field'       => '{{{ day }}}',
                'condition'         => [
                    'business_timings' => 'predefined',
                ],
			]
		);

		$this->add_control(
			'business_hours_custom',
			[
				'label'             => '',
				'type'              => Controls_Manager::REPEATER,
				'default'           => [
					[
						'day' => 'Monday',
					],
					[
						'day' => 'Tuesday',
					],
					[
						'day' => 'Wednesday',
					],
					[
						'day' => 'Thursday',
					],
					[
						'day' => 'Friday',
					],
					[
						'day'             => 'Saturday',
						'closed'          => 'yes',
						'highlight'       => 'yes',
						'highlight_color' => '#bc1705',
					],
					[
						'day'             => 'Sunday',
						'closed'          => 'yes',
						'highlight'       => 'yes',
						'highlight_color' => '#bc1705',
					],
				],
				'fields'            => [
                    [
                        'name'              => 'day',
                        'label'             => __( 'Day', 'powerpack' ),
                        'type'              => Controls_Manager::TEXT,
                        'default'           => 'Monday',
                    ],
                    [
                        'name'              => 'closed',
                        'label'             => __( 'Closed?', 'powerpack' ),
                        'type'              => Controls_Manager::SWITCHER,
                        'default'           => 'no',
                        'label_on'          => __( 'No', 'powerpack' ),
                        'label_off'         => __( 'Yes', 'powerpack' ),
                        'return_value'      => 'no',
                    ],
                    [
                        'name'              => 'time',
                        'label'             => __( 'Time', 'powerpack' ),
                        'type'              => Controls_Manager::TEXT,
                        'default'           => '09:00 AM - 05:00 PM',
                        'condition'         => [
                            'closed' => 'no',
                        ],
                    ],
                    [
                        'name'              => 'closed_text',
                        'label'             => __( 'Closed Text', 'powerpack' ),
                        'type'              => Controls_Manager::TEXT,
                        'label_block'       => true,
                        'placeholder'       => __( 'Closed', 'powerpack' ),
                        'default'           => __( 'Closed', 'powerpack' ),
                        'conditions'        => [
                            'terms' => [
                                [
                                    'name' => 'closed',
                                    'operator' => '!=',
                                    'value' => 'no',
                                ],
                            ],
                        ],
                    ],
                    [
                        'name'              => 'highlight',
                        'label'             => __( 'Highlight', 'powerpack' ),
                        'type'              => Controls_Manager::SWITCHER,
                        'default'           => 'no',
                        'label_on'          => __( 'Yes', 'powerpack' ),
                        'label_off'         => __( 'No', 'powerpack' ),
                        'return_value'      => 'yes',
                    ],
                    [
                        'name'              => 'highlight_bg',
                        'label'             => __( 'Background Color', 'powerpack' ),
                        'type'              => Controls_Manager::COLOR,
                        'default'           => '',
                        'selectors'         => [
                            '{{WRAPPER}} .pp-business-hours .pp-business-hours-row{{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                        ],
                        'condition'         => [
                            'highlight' => 'yes',
                        ],
                    ],
                    [
                        'name'              => 'highlight_color',
                        'label'             => __( 'Text Color', 'powerpack' ),
                        'type'              => Controls_Manager::COLOR,
                        'default'           => '',
                        'selectors'         => [
                            '{{WRAPPER}} .pp-business-hours .pp-business-hours-row{{CURRENT_ITEM}} .pp-business-day, {{WRAPPER}} .pp-business-hours .pp-business-hours-row{{CURRENT_ITEM}} .pp-business-timing' => 'color: {{VALUE}}',
                        ],
                        'condition'         => [
                            'highlight' => 'yes',
                        ],
                    ]
				],
				'title_field'       => '{{{ day }}}',
                'condition'         => [
                    'business_timings' => 'custom',
                ],
			]
		);
        
        $this->add_control(
          'hours_format',
            [
                'label'             => __( '24 Hours Format?', 'powerpack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'Yes', 'powerpack' ),
                'label_off'         => __( 'No', 'powerpack' ),
                'return_value'      => 'yes',
                'condition'         => [
                    'business_timings' => 'predefined',
                ],
            ]
        );
        
        $this->add_control(
            'days_format',
            [
                'label'                => __( 'Days Format', 'powerpack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'long',
                'options'              => [
                    'long'      => __( 'Long', 'powerpack' ),
                    'short'     => __( 'Short', 'powerpack' ),
                ],
                'condition'         => [
                    'business_timings' => 'predefined',
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
				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/business-hours/business-hours-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			]
		);

		$this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Row Style
         */
        $this->start_controls_section(
            'section_rows_style',
            [
                'label'             => __( 'Rows Style', 'powerpack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_rows_style' );

        $this->start_controls_tab(
            'tab_row_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
            ]
        );

        $this->add_control(
            'row_bg_color_normal',
            [
                'label'             => __( 'Background Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_row_hover',
            [
                'label'                 => __( 'Hover', 'powerpack' ),
            ]
        );

        $this->add_control(
            'row_bg_color_hover',
            [
                'label'             => __( 'Background Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_control(
          'stripes',
            [
                'label'             => __( 'Striped Rows', 'powerpack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'Yes', 'powerpack' ),
                'label_off'         => __( 'No', 'powerpack' ),
                'return_value'      => 'yes',
                'separator'         => 'before',
            ]
        );

        $this->start_controls_tabs( 'tabs_alternate_style' );

        $this->start_controls_tab(
            'tab_even',
            [
                'label'                 => __( 'Even Row', 'powerpack' ),
                'condition'             => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'row_even_bg_color',
            [
                'label'             => __( 'Background Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#f5f5f5',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:nth-child(even)' => 'background-color: {{VALUE}}',
                ],
                'condition'         => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'row_even_text_color',
            [
                'label'             => __( 'Text Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:nth-child(even)' => 'color: {{VALUE}}',
                ],
                'condition'         => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_odd',
            [
                'label'                 => __( 'Odd Row', 'powerpack' ),
                'condition'             => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'row_odd_bg_color',
            [
                'label'             => __( 'Background Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#ffffff',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:nth-child(odd)' => 'background-color: {{VALUE}}',
                ],
                'condition'         => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'row_odd_text_color',
            [
                'label'             => __( 'Text Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:nth-child(odd)' => 'color: {{VALUE}}',
                ],
                'condition'         => [
                    'stripes' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

		$this->add_responsive_control(
			'rows_padding',
			[
				'label'             => __( 'Padding', 'powerpack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'default'           => [
                    'top'       => '8',
                    'right'     => '10',
                    'bottom'    => '8',
                    'left'      => '10',
                    'unit'      => 'px',
                    'isLinked'  => false,
                ],
				'selectors'         => [
					'{{WRAPPER}} .pp-business-hours .pp-business-hours-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'         => 'before',
			]
		);
        
        $this->add_responsive_control(
            'rows_margin',
            [
                'label'             => __( 'Margin Bottom', 'powerpack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'closed_row_heading',
            [
                'label'             => __( 'Closed Row', 'powerpack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );

        $this->add_control(
            'closed_row_bg_color',
            [
                'label'             => __( 'Background Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row.row-closed' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'closed_row_day_color',
            [
                'label'             => __( 'Day Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row.row-closed .pp-business-day' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'closed_row_tex_color',
            [
                'label'             => __( 'Text Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row.row-closed .pp-business-timing' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'divider_heading',
            [
                'label'             => __( 'Rows Divider', 'powerpack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );
        
        $this->add_control(
            'rows_divider_style',
            [
                'label'                => __( 'Divider Style', 'powerpack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'none',
                'options'              => [
                    'none'      => __( 'None', 'powerpack' ),
                    'solid'     => __( 'Solid', 'powerpack' ),
                    'dashed'    => __( 'Dashed', 'powerpack' ),
                    'dotted'    => __( 'Dotted', 'powerpack' ),
                    'groove'    => __( 'Groove', 'powerpack' ),
                    'ridge'     => __( 'Ridge', 'powerpack' ),
                ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:not(:last-child)' => 'border-bottom-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'rows_divider_color',
            [
                'label'             => __( 'Divider Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:not(:last-child)' => 'border-bottom-color: {{VALUE}}',
                ],
                'condition'         => [
                    'rows_divider_style!' => 'none',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'rows_divider_weight',
            [
                'label'             => __( 'Divider Weight', 'powerpack' ),
                'type'              => Controls_Manager::SLIDER,
                'default'           => [ 'size' => 1 ],
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ],
                'condition'         => [
                    'rows_divider_style!' => 'none',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Business Hours
         */
        $this->start_controls_section(
            'section_pricing_table_style',
            [
                'label'             => __( 'Business Hours', 'powerpack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_hours_style' );

        $this->start_controls_tab(
            'tab_hours_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'title_heading',
            [
                'label'             => __( 'Day', 'powerpack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'day_alignment',
            [
                'label'                 => __( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => 'left',
				'selectors'             => [
					'{{WRAPPER}} .pp-business-hours .pp-business-day' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->add_control(
            'day_color',
            [
                'label'             => __( 'Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-day' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'title_typography',
                'label'             => __( 'Typography', 'powerpack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-business-hours .pp-business-day',
            ]
        );
        
        $this->add_control(
            'hours_heading',
            [
                'label'             => __( 'Hours', 'powerpack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'hours_alignment',
            [
                'label'                 => __( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => 'right',
				'selectors'             => [
					'{{WRAPPER}} .pp-business-hours .pp-business-timing' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->add_control(
            'hours_color',
            [
                'label'             => __( 'Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-timing' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'hours_typography',
                'label'             => __( 'Typography', 'powerpack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-business-hours .pp-business-timing',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_hours_hover',
            [
                'label'                 => __( 'Hover', 'powerpack' ),
            ]
        );

        $this->add_control(
            'day_color_hover',
            [
                'label'             => __( 'Day Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:hover .pp-business-day' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'hours_color_hover',
            [
                'label'             => __( 'Hours Color', 'powerpack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-business-hours .pp-business-hours-row:hover .pp-business-timing' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

		do_action( 'powerpack_elements/element/after_section_end', $this );
    }

    /**
	 * Render business hours widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();

        $this->add_render_attribute( 'business-hours', 'class', 'pp-business-hours' );
        $i = 1;
        ?>
        <div <?php echo $this->get_render_attribute_string( 'business-hours' ); ?>>
            <?php
                if ( $settings['business_timings'] == 'predefined' ) {
                    $this->render_business_hours_predefined();
                } elseif ( $settings['business_timings'] == 'custom' ) {
                    $this->render_business_hours_custom();
                }
            ?>
        </div>
        <?php
    }

    /**
	 * Render predefined business hours widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_business_hours_predefined() {
        $settings = $this->get_settings();
        $i = 1;
        ?>
            <?php foreach ( $settings['business_hours'] as $index => $item ) : ?>
                <?php
                    $this->add_render_attribute( 'row' . $i, 'class', 'pp-business-hours-row clearfix elementor-repeater-item-' . esc_attr( $item['_id'] ) );
                    if ( $item['closed'] != 'no' ) {
                        $this->add_render_attribute( 'row' . $i, 'class', 'row-closed' );
                    }
                ?>
                <div <?php echo $this->get_render_attribute_string( 'row' . $i ); ?>>
                    <span class="pp-business-day">
                        <?php
                            if ( $settings['days_format'] == 'long' ) {
                                echo ucwords( esc_attr( $item['day'] ) );
                            } else {
                                echo ucwords( esc_attr( substr($item['day'], 0, 3) ) );
                            }
                        ?>
                    </span>
                    <span class="pp-business-timing">
                        <?php if ( $item['closed'] == 'no' ) { ?>
                            <span class="pp-opening-hours">
                                <?php
                                    if ( $settings['hours_format'] == 'yes' ) {
                                        echo esc_attr( $item['opening_hours'] );
                                    } else {
                                        echo esc_attr( date( "g:i A", strtotime( $item['opening_hours'] ) ) );
                                    }
                                ?>
                            </span>
                            -
                            <span class="pp-closing-hours">
                                <?php
                                    if ( $settings['hours_format'] == 'yes' ) {
                                        echo esc_attr( $item['closing_hours'] );
                                    } else {
                                        echo esc_attr( date( "g:i A", strtotime( $item['closing_hours'] ) ) );
                                    }
                                ?>
                            </span>
                        <?php } else {
                            if ( $item['closed_text'] ) {
                                echo esc_attr( $item['closed_text'] );
                            } else {
                                esc_attr_e( 'Closed', 'powerpack' );
                            }
                        } ?>
                    </span>
                </div>
            <?php $i++; endforeach; ?>
        <?php
    }

    /**
	 * Render custom business hours widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_business_hours_custom() {
        $settings = $this->get_settings();
        $i = 1;
        ?>
            <?php foreach ( $settings['business_hours_custom'] as $index => $item ) : ?>
                <?php
                    $this->add_render_attribute( 'row' . $i, 'class', 'pp-business-hours-row clearfix elementor-repeater-item-' . esc_attr( $item['_id'] ) );
                    if ( $item['closed'] != 'no' ) {
                        $this->add_render_attribute( 'row' . $i, 'class', 'row-closed' );
                    }
                ?>
                <div <?php echo $this->get_render_attribute_string( 'row' . $i ); ?>>
                    <?php if ( $item['day'] != '' ) { ?>
                        <span class="pp-business-day">
                            <?php
                                echo esc_attr( $item['day'] );
                            ?>
                        </span>
                    <?php } ?>
                    <span class="pp-business-timing">
                        <?php
                            if ( $item['closed'] == 'no' && $item['time'] != '' ) {
                                echo esc_attr( $item['time'] );
                            }
                            else {
                                if ( $item['closed_text'] ) {
                                    echo esc_attr( $item['closed_text'] );
                                } else {
                                    esc_attr_e( 'Closed', 'powerpack' );
                                }
                            }
                        ?>
                    </span>
                </div>
            <?php $i++; endforeach; ?>
        <?php
    }

    /**
	 * Render business hours widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <div class="pp-business-hours">
            
            <# if ( settings.business_timings == 'predefined' ) { #>
                <?php $this->_business_hours_predefined_template(); ?>
            <# } else { #>
                <?php $this->_business_hours_custom_template(); ?>
            <# } #>
                
        </div>
        <?php
    }

    /**
	 * Render predefined business hours widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _business_hours_predefined_template() {
        ?>
        <#
            function pp_timeTo12HrFormat(time) {
                // Take a time in 24 hour format and format it in 12 hour format
                var time_part_array = time.split(":");
                var ampm = 'AM';
           
                var first_part = time_part_array[0];
                var second_part = time_part_array[1];

                if (first_part >= 12) {
                    ampm = 'PM';
                }
            
                if (first_part == 00) {
                    first_part = 12;
                }

                if (first_part >= 1 && first_part < 10) {
                    var first_part = first_part.substr(1, 2);
                }
            
                if (first_part > 12) {
                    first_part = first_part - 12;
                }

                formatted_time = first_part + ':' + second_part + ' ' + ampm;

                return formatted_time;
            }
        #>
        <# _.each( settings.business_hours, function( item ) { #>
            <#
                var closed = ( item.closed != 'no' ) ? 'row-closed' : '';
            #>
            <div class="pp-business-hours-row clearfix elementor-repeater-item-{{ item._id }} {{ closed }}">
                <span class="pp-business-day">
                    <# if ( settings.days_format == 'long' ) { #>
                        {{ item.day }}
                    <# } else { #>
                        {{ item.day.substring(0,3) }}
                    <# } #>
                </span>
                <span class="pp-business-timing">
                    <# if ( item.closed == 'no' ) { #>
                        <span class="pp-opening-hours">
                            <# if ( settings.hours_format == 'yes' ) { #>
                                {{ item.opening_hours }}
                            <# } else { #>
                                {{ pp_timeTo12HrFormat( item.opening_hours ) }}
                            <# } #>
                        </span>
                        -
                        <span class="pp-closing-hours">
                            <# if ( settings.hours_format == 'yes' ) { #>
                                {{ item.closing_hours }}
                            <# } else { #>
                                {{ pp_timeTo12HrFormat( item.closing_hours ) }}
                            <# } #>
                        </span>
                    <# } else { #>
                        <# if ( item.closed_text != '' ) { #>
                            {{ item.closed_text }}
                        <# } else { #>
                            <?php esc_attr_e( 'Closed', 'powerpack' ); ?>
                        <# } #>
                    <# } #>
                </span>
            </div>
        <# } ); #>
        <?php
    }

    /**
	 * Render custom business hours widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _business_hours_custom_template() {
        ?>
        <# _.each( settings.business_hours_custom, function( item ) { #>
            <#
                var closed = ( item.closed != 'no' ) ? 'row-closed' : '';
            #>
            <div class="pp-business-hours-row clearfix elementor-repeater-item-{{ item._id }} {{ closed }}">
                <# if ( item.day != '' ) { #>
                    <span class="pp-business-day">
                        {{ item.day }}
                    </span>
                <# } #>
                <span class="pp-business-timing">
                    <# if ( item.closed == 'no' && item.time != '' ) { #>
                        {{ item.time }}
                    <# } else { #>
                        <# if ( item.closed_text != '' ) { #>
                            {{ item.closed_text }}
                        <# } else { #>
                            <?php esc_attr_e( 'Closed', 'powerpack' ); ?>
                        <# } #>
                    <# } #>
                </span>
            </div>
        <# } ); #>
        <?php
    }
}

//Plugin::instance()->widgets_manager->register_widget_type( new PP_Business_Hours_Widget() );