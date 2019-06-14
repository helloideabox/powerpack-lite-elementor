<?php
namespace PowerpackElementsLite\Modules\Counter\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Counter Widget
 */
class Counter extends Powerpack_Widget {
    
    /**
	 * Retrieve counter widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-counter';
    }

    /**
	 * Retrieve counter widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Counter', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the counter widget belongs to.
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
	 * Retrieve counter widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-counter power-pack-admin-icon';
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
            'waypoints',
            'odometer',
            'powerpack-frontend',
        ];
    }

    /**
	 * Register counter widget controls.
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
         * Content Tab: Counter
         */
        $this->start_controls_section(
            'section_counter',
            [
                'label'                 => __( 'Counter', 'power-pack' ),
            ]
        );
        
        $this->add_control(
			'pp_icon_type',
			[
				'label'                 => esc_html__( 'Icon Type', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'none'        => [
						'title'   => esc_html__( 'None', 'power-pack' ),
						'icon'    => 'fa fa-ban',
					],
					'icon'        => [
						'title'   => esc_html__( 'Icon', 'power-pack' ),
						'icon'    => 'fa fa-info-circle',
					],
					'image'       => [
						'title'   => esc_html__( 'Image', 'power-pack' ),
						'icon'    => 'fa fa-picture-o',
					],
				],
				'default'               => 'none',
			]
		);
        
        $this->add_control(
            'counter_icon',
            [
                'label'                 => __( 'Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'condition'             => [
                    'pp_icon_type'  => 'icon',
                ],
            ]
        );
        
        $this->add_control(
            'icon_image',
            [
                'label'                 => __( 'Image', 'power-pack' ),
                'type'                  => Controls_Manager::MEDIA,
                'default'               => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
				'condition'             => [
					'pp_icon_type'  => 'image',
				],
            ]
        );
        
        $this->add_control(
            'ending_number',
            [
                'label'                 => __( 'Number', 'power-pack' ),
                'type'                  => Controls_Manager::NUMBER,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( '250', 'power-pack' ),
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'number_prefix',
            [
                'label'                 => __( 'Number Prefix', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
            ]
        );
        
        $this->add_control(
            'number_suffix',
            [
                'label'                 => __( 'Number Suffix', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
            ]
        );

        $this->add_control(
            'counter_title',
            [
                'label'                 => __( 'Title', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Counter Title', 'power-pack' ),
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'title_html_tag',
            [
                'label'                => __( 'Title HTML Tag', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'div',
                'options'              => [
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
        
        $layouts = array();
		for ($x = 1; $x <= 10; $x++) {
			$layouts['layout-' . $x] = __( 'Layout', 'powerpack' ) . ' ' . $x;
		}
        
        $this->add_control(
            'counter_layout',
            [
                'label'                => __( 'Layout', 'powerpack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'layout-1',
                'options'              => $layouts,
                'separator'             => 'before',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Separators
         */
        $this->start_controls_section(
            'section_counter_separators',
            [
                'label'                 => __( 'Dividers', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'icon_divider',
            [
                'label'                 => __( 'Icon Divider', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
                'condition'             => [
                    'pp_icon_type!' => 'none',
                ],
            ]
        );
        
        $this->add_control(
            'num_divider',
            [
                'label'                 => __( 'Number Divider', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->end_controls_section();

        /**
         * Content Tab: Settings
         */
        $this->start_controls_section(
            'section_counter_settings',
            [
                'label'                 => __( 'Settings', 'power-pack' ),
            ]
        );
        
        $this->add_responsive_control(
            'counter_speed',
            [
                'label'                 => __( 'Counting Speed', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 1500 ],
                'range'                 => [
                    'px' => [
                        'min'   => 100,
                        'max'   => 2000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
            ]
        );
        
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Style Tab: Counter
         */
        $this->start_controls_section(
            'section_style',
            [
                'label'                 => __( 'Counter', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'counter_align',
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
				'default'               => 'center',
				'selectors'             => [
					'{{WRAPPER}} .pp-counter-container'   => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Icon
         */
        $this->start_controls_section(
            'section_counter_icon_style',
            [
                'label'                 => __( 'Icon', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'pp_icon_type!' => 'none',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'counter_icon_bg',
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'none','classic','gradient' ],
                'condition'             => [
                    'pp_icon_type!' => 'none',
                ],
                'selector'              => '{{WRAPPER}} .pp-counter-icon',
            ]
        );

        $this->add_control(
            'counter_icon_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-icon' => 'color: {{VALUE}};',
                ],
                'condition'             => [
                    'pp_icon_type'  => 'icon',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'counter_icon_size',
            [
                'label'                 => __( 'Size', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-counter-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'pp_icon_type'  => 'icon',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'counter_icon_img_width',
            [
                'label'                 => __( 'Image Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 10,
                        'max'   => 500,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => ['px', '%'],
                'condition'             => [
                    'pp_icon_type'  => 'image',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-icon img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'counter_icon_rotation',
            [
                'label'                 => __( 'Rotation', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 360,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'condition'             => [
                    'pp_icon_type!' => 'none',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-icon .fa, {{WRAPPER}} .pp-counter-icon img' => 'transform: rotate( {{SIZE}}deg );',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'counter_icon_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-counter-icon',
                'condition'             => [
                    'pp_icon_type!' => 'none',
                ],
			]
		);

		$this->add_control(
			'counter_icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-counter-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'pp_icon_type!' => 'none',
                ],
			]
		);

		$this->add_responsive_control(
			'counter_icon_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-counter-icon' => 'padding-top: {{TOP}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
				],
                'condition'             => [
                    'pp_icon_type!' => 'none',
                ],
			]
		);

		$this->add_responsive_control(
			'counter_icon_margin',
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
					'{{WRAPPER}} .pp-counter-icon-wrap' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
                'condition'             => [
                    'pp_icon_type!' => 'none',
                ],
			]
		);
        
        $this->add_control(
            'icon_divider_heading',
            [
                'label'                 => __( 'Icon Divider', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
                    'pp_icon_type!' => 'none',
                    'icon_divider'  => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'icon_divider_type',
            [
            'label'                     => __( 'Divider Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'solid',
                'options'               => [
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'double'    => __( 'Double', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-icon-divider' => 'border-bottom-style: {{VALUE}}',
                ],
                'condition'             => [
                    'pp_icon_type!' => 'none',
                    'icon_divider'  => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_divider_height',
            [
                'label'                 => __( 'Height', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-counter-icon-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'pp_icon_type!' => 'none',
                    'icon_divider'  => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_divider_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-counter-icon-divider' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'pp_icon_type!' => 'none',
                    'icon_divider'  => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon_divider_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-icon-divider' => 'border-bottom-color: {{VALUE}}',
                ],
                'condition'             => [
                    'pp_icon_type!' => 'none',
                    'icon_divider'  => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_divider_margin',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .pp-counter-icon-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'pp_icon_type!' => 'none',
                    'icon_divider'  => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Number
         */
        $this->start_controls_section(
            'section_counter_num_style',
            [
                'label'                 => __( 'Number', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'counter_num_color',
            [
                'label'                 => __( 'Number Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-number' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'counter_num_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-counter-number-wrap',
            ]
        );

		$this->add_responsive_control(
			'counter_num_margin',
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
					'{{WRAPPER}} .pp-counter-number-wrap' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'num_divider_heading',
            [
                'label'                 => __( 'Number Divider', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
                    'num_divider'  => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'num_divider_type',
            [
                'label'                 => __( 'Divider Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'solid',
                'options'               => [
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'double'    => __( 'Double', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-num-divider' => 'border-bottom-style: {{VALUE}}',
                ],
                'condition'             => [
                    'num_divider'  => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'num_divider_height',
            [
                'label'                 => __( 'Height', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-counter-num-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'num_divider'  => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'num_divider_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-counter-num-divider' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'num_divider'  => 'yes',
                ],
            ]
        );

        $this->add_control(
            'num_divider_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-num-divider' => 'border-bottom-color: {{VALUE}}',
                ],
                'condition'             => [
                    'num_divider'  => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'num_divider_margin',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .pp-counter-num-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'num_divider'  => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Prefix
         */
        $this->start_controls_section(
            'section_number_prefix_style',
            [
                'label'                 => __( 'Prefix', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'number_prefix!' => '',
                ],
            ]
        );

        $this->add_control(
            'number_prefix_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-number-prefix' => 'color: {{VALUE}};',
                ],
                'condition'             => [
                    'number_prefix!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'number_prefix_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-counter-number-prefix',
                'condition'             => [
                    'number_prefix!' => '',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Suffix
         */
        $this->start_controls_section(
            'section_number_suffix_style',
            [
                'label'                 => __( 'Suffix', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'number_suffix!' => '',
                ],
            ]
        );

        $this->add_control(
            'section_number_suffix_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-number-suffix' => 'color: {{VALUE}};',
                ],
                'condition'             => [
                    'number_suffix!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'section_number_suffix_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-counter-number-suffix',
                'condition'             => [
                    'number_suffix!' => '',
                ],
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section(
            'section_counter_title_style',
            [
                'label'                 => __( 'Title', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'counter_title!' => '',
                ],
            ]
        );

        $this->add_control(
            'counter_title_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-title' => 'color: {{VALUE}};',
                ],
                'condition'             => [
                    'counter_title!' => '',
                ],
            ]
        );

        $this->add_control(
            'counter_title_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-counter-title' => 'background-color: {{VALUE}};',
                ],
                'condition'             => [
                    'counter_title!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'counter_title_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-counter-title',
                'condition'             => [
                    'counter_title!' => '',
                ],
            ]
        );

		$this->add_responsive_control(
			'counter_title_margin',
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
					'{{WRAPPER}} .pp-counter-title' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
                'condition'             => [
                    'counter_title!' => '',
                ],
			]
		);

		$this->add_responsive_control(
			'counter_title_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-counter-title' => 'padding-top: {{TOP}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}}; padding-right: {{RIGHT}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
				],
                'condition'             => [
                    'counter_title!' => '',
                ],
			]
		);
        
        $this->end_controls_section();

    }

    /**
	 * Render counter widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'counter', 'class', 'pp-counter pp-counter-'.esc_attr( $this->get_id() ) );
        
        if ( $settings['counter_layout'] ) {
            $this->add_render_attribute( 'counter', 'class', 'pp-counter-' . $settings['counter_layout'] );
        }
        
        $this->add_render_attribute( 'counter', 'data-target', '.pp-counter-number-'.esc_attr( $this->get_id() ) );
        
        $this->add_render_attribute( 'counter-number', 'class', 'pp-counter-number pp-counter-number-'.esc_attr( $this->get_id() ) );
        
        if ( $settings['ending_number'] != '' ) {
            $this->add_render_attribute( 'counter-number', 'data-to', $settings['ending_number'] );
        }
        
        if ( $settings['counter_speed']['size'] != '' ) {
            $this->add_render_attribute( 'counter-number', 'data-speed', $settings['counter_speed']['size'] );
        }
        
        $this->add_inline_editing_attributes( 'counter_title', 'none' );
        $this->add_render_attribute( 'counter_title', 'class', 'pp-counter-title' );
        ?>
        <div class="pp-counter-container">
            <div <?php echo $this->get_render_attribute_string( 'counter' ); ?>>
                <?php if ( $settings['counter_layout'] == 'layout-1' || $settings['counter_layout'] == 'layout-5' || $settings['counter_layout'] == 'layout-6' ) { ?>
                    <?php
                        // Counter Icon
                        $this->render_icon();
                    ?>
                
                    <div class="pp-counter-number-title-wrap">
                        <?php
                            // Counter Number
                            $this->render_counter_number();
                        ?>

                        <?php if ( $settings['num_divider'] == 'yes' ) { ?>
                            <div class="pp-counter-num-divider-wrap">
                                <span class="pp-counter-num-divider"></span>
                            </div>
                        <?php } ?>

                        <?php
                            // Counter Title
                            $this->render_counter_title();
                        ?>
                    </div>
                <?php } elseif ( $settings['counter_layout'] == 'layout-2' ) { ?>
                    <?php
                        // Counter Icon
                        $this->render_icon();

                        // Counter Title
                        $this->render_counter_title();
            
                        // Counter Number
                        $this->render_counter_number();
            
            
                        if ( $settings['num_divider'] == 'yes' ) { ?>
                            <div class="pp-counter-num-divider-wrap">
                                <span class="pp-counter-num-divider"></span>
                            </div>
                            <?php
                        }
                    } elseif ( $settings['counter_layout'] == 'layout-3' ) {
            
                        // Counter Number
                        $this->render_counter_number();
                        ?>

                    <?php if ( $settings['num_divider'] == 'yes' ) { ?>
                        <div class="pp-counter-num-divider-wrap">
                            <span class="pp-counter-num-divider"></span>
                        </div>
                    <?php } ?>
                
                    <div class="pp-icon-title-wrap">
                        <?php
                            // Counter Icon
                            $this->render_icon();
            
                            // Counter Title
                            $this->render_counter_title();
                        ?>
                    </div>
                <?php } elseif ( $settings['counter_layout'] == 'layout-4' ) { ?>
                    <div class="pp-icon-title-wrap">
                        <?php
                            // Counter Icon
                            $this->render_icon();
            
                            // Counter Title
                            $this->render_counter_title();
                        ?>
                    </div>
                
                    <?php
                        // Counter Number
                        $this->render_counter_number();
                    ?>

                    <?php if ( $settings['num_divider'] == 'yes' ) { ?>
                        <div class="pp-counter-num-divider-wrap">
                            <span class="pp-counter-num-divider"></span>
                        </div>
                    <?php }
                    } elseif ( $settings['counter_layout'] == 'layout-7' || $settings['counter_layout'] == 'layout-8' ) {

                        // Counter Number
                        $this->render_counter_number();
                        ?>
                
                        <div class="pp-icon-title-wrap">
                            <?php
                                // Counter Icon
                                $this->render_icon();

                                // Counter Title
                                $this->render_counter_title();
                            ?>
                        </div>
                <?php } elseif ( $settings['counter_layout'] == 'layout-9' ) {
                        ?>
                        <div class="pp-icon-number-wrap">
                            <?php
                                // Counter Icon
                                $this->render_icon();

								// Counter Number
								$this->render_counter_number();
							?>
						</div>
						<?php
							// Counter Title
							$this->render_counter_title();
						?>
                <?php } elseif ( $settings['counter_layout'] == 'layout-10' ) {
                        ?>
                        <div class="pp-icon-number-wrap">
                            <?php
								// Counter Number
								$this->render_counter_number();

                                // Counter Icon
                                $this->render_icon();
							?>
						</div>
						<?php
							// Counter Title
							$this->render_counter_title();
						?>
                <?php } ?>
            </div>
        </div><!-- .pp-counter-container -->
        <?php
    }
    
    /**
	 * Render counter icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    private function render_icon() {
        $settings = $this->get_settings_for_display();
        
        if ( $settings['pp_icon_type'] == 'icon' ) {
            if ( !empty( $settings['counter_icon'] ) ) { ?>
                <span class="pp-counter-icon-wrap">
                    <span class="pp-counter-icon">
                        <span class="<?php echo $settings['counter_icon'] ?>" aria-hidden="true"></span>
                    </span>
                </span>
            <?php }
        } elseif ( $settings['pp_icon_type'] == 'image' ) {
            $image = $settings['icon_image'];
            if ( $image['url'] ) { ?>
                <span class="pp-counter-icon-wrap">
                    <span class="pp-counter-icon pp-counter-icon-img">
                        <img src="<?php echo esc_url( $image['url'] ); ?>">
                    </span>
                </span>
            <?php }
        }

        if ( $settings['icon_divider'] == 'yes' ) {
            if ( $settings['counter_layout'] == 'layout-1' || $settings['counter_layout'] == 'layout-2' ) { ?>
                <div class="pp-counter-icon-divider-wrap">
                    <span class="pp-counter-icon-divider"></span>
                </div>
                <?php
            }
        }
    }

    /**
	 * Render counter icon output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _icon_template() {
        ?>
        <# if ( settings.pp_icon_type == 'icon' ) { #>
            <# if ( settings.counter_icon != '' ) { #>
                <span class="pp-counter-icon-wrap">
                    <span class="pp-counter-icon">
                        <span class="{{ settings.counter_icon }}" aria-hidden="true"></span>
                    </span>
                </span>
            <# } #>
        <# } else if ( settings.pp_icon_type == 'image' ) { #>
            <# if ( settings.icon_image.url != '' ) { #>
                <span class="pp-counter-icon-wrap">
                    <span class="pp-counter-icon pp-counter-icon-img">
                        <img src="{{ settings.icon_image.url }}">
                    </span>
                </span>
            <# } #>
        <# } #>

        <# if ( settings.icon_divider == 'yes' ) { #>
            <# if ( settings.counter_layout == 'layout-1' || settings.counter_layout == 'layout-2' ) { #>
                <div class="pp-counter-icon-divider-wrap">
                    <span class="pp-counter-icon-divider"></span>
                </div>
            <# } #>
        <# } #>
        <?php
    }

    /**
	 * Render counter number output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _number_template() {
        ?>
        <div class="pp-counter-number-wrap">
            <#
                if ( settings.number_prefix != '' ) {
                    var prefix = settings.number_prefix;

                    view.addRenderAttribute( 'prefix', 'class', 'pp-counter-number-prefix' );

                    var prefix_html = '<span' + ' ' + view.getRenderAttributeString( 'prefix' ) + '>' + prefix + '</span>';

                    print( prefix_html );
                }
            #>
            <div class="pp-counter-number" data-to="{{ settings.ending_number }}" data-speed="{{ settings.counter_speed.size }}">
                0
            </div>
            <#
                if ( settings.number_suffix != '' ) {
                    var suffix = settings.number_suffix;

                    view.addRenderAttribute( 'suffix', 'class', 'pp-counter-number-suffix' );

                    var suffix_html = '<span' + ' ' + view.getRenderAttributeString( 'suffix' ) + '>' + suffix + '</span>';

                    print( suffix_html );
                }
            #>
        </div>
        <?php
    }

    /**
	 * Render counter title output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _title_template() {
        ?>
        <#
            if ( settings.counter_title != '' ) {
                var title = settings.counter_title;

                view.addRenderAttribute( 'counter_title', 'class', 'pp-counter-title' );

                view.addInlineEditingAttributes( 'counter_title' );

                var title_html = '<' + settings.title_html_tag  + ' ' + view.getRenderAttributeString( 'counter_title' ) + '>' + title + '</' + settings.title_html_tag + '>';

                print( title_html );
            }
        #>
        <?php
    }

    /**
	 * Render counter widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <div class="pp-counter-container">
            <div class="pp-counter pp-counter-{{ settings.counter_layout }}" data-target=".pp-counter-number">
                <# if ( settings.counter_layout == 'layout-1' || settings.counter_layout == 'layout-5' || settings.counter_layout == 'layout-6' ) { #>
                    <?php
                        // Counter Icon
                        $this->_icon_template();
                    ?>
                
                    <div class="pp-counter-number-title-wrap">
                        <?php
                            // Counter Number
                            $this->_number_template();
                        ?>

                        <# if ( settings.num_divider == 'yes' ) { #>
                            <div class="pp-counter-num-divider-wrap">
                                <span class="pp-counter-num-divider"></span>
                            </div>
                        <# } #>

                        <?php
                            // Title Number
                            $this->_title_template();
                        ?>
                    </div>
                <# } else if ( settings.counter_layout == 'layout-2' ) { #>
                    <?php
                        // Counter Icon
                        $this->_icon_template();
        
                        // Title Number
                        $this->_title_template();
        
                        // Counter Number
                        $this->_number_template();
                    ?>

                    <# if ( settings.num_divider == 'yes' ) { #>
                        <div class="pp-counter-num-divider-wrap">
                            <span class="pp-counter-num-divider"></span>
                        </div>
                    <# } #>
                <# } else if ( settings.counter_layout == 'layout-3' ) { #>
                    <?php
                        // Counter Number
                        $this->_number_template();
                    ?>

                    <# if ( settings.num_divider == 'yes' ) { #>
                        <div class="pp-counter-num-divider-wrap">
                            <span class="pp-counter-num-divider"></span>
                        </div>
                    <# } #>
                
                    <div class="pp-icon-title-wrap">
                        <?php
                            // Counter Icon
                            $this->_icon_template();
        
                            // Title Number
                            $this->_title_template();
                        ?>
                    </div>
                <# } else if ( settings.counter_layout == 'layout-4' ) { #>
                    <div class="pp-icon-title-wrap">
                        <?php
                            // Counter Icon
                            $this->_icon_template();

                            // Title Number
                            $this->_title_template();
                        ?>
                    </div>
                
                    <?php
                        // Counter Number
                        $this->_number_template();
                    ?>

                    <# if ( settings.num_divider == 'yes' ) { #>
                        <div class="pp-counter-num-divider-wrap">
                            <span class="pp-counter-num-divider"></span>
                        </div>
                    <# } #>
                <# } else if ( settings.counter_layout == 'layout-7' || settings.counter_layout == 'layout-8' ) { #>
                    <?php
                        // Counter Number
                        $this->_number_template();
                    ?>
                    
                    <div class="pp-icon-title-wrap">
                        <?php
                            // Counter Icon
                            $this->_icon_template();

                            // Title Number
                            $this->_title_template();
                        ?>
                    </div>
                <# } else if ( settings.counter_layout == 'layout-9' ) { #>
					<div class="pp-icon-number-wrap">
						<?php
							// Counter Icon
							$this->_icon_template();

							// Counter Number
							$this->_number_template();
						?>
					</div>
					<?php
						// Counter Title
						$this->_title_template();
					?>
                <# } else if ( settings.counter_layout == 'layout-10' ) { #>
					<div class="pp-icon-number-wrap">
						<?php
							// Counter Number
							$this->_number_template();

							// Counter Icon
							$this->_icon_template();
						?>
					</div>
					<?php
						// Counter Title
						$this->_title_template();
					?>
                <# } #>
            </div>
        </div><!-- .pp-counter-container -->
        <?php
    }
}