<?php
namespace PowerpackElements\Modules\PromoBox\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

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
        return 'pp-promo-box';
    }

    /**
	 * Retrieve promo box widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Promo Box', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the promo box widget belongs to.
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
	 * Retrieve promo box widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-promo-box power-pack-admin-icon';
    }

    /**
	 * Register promo box widget controls.
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
         * Content Tab: Promo Box
         */
        $this->start_controls_section(
            'section_promo_box',
            [
                'label'                 => __( 'Promo Box', 'power-pack' ),
            ]
        );

        $this->add_control(
            'icon_switch',
            [
                'label'                 => __( 'Icon', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'yes',
                'separator'             => 'before',
            ]
        );

        $this->add_control(
            'icon_type',
            [
                'label'                 => __( 'Icon Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'icon',
                'options'               => [
                    'icon'      => __( 'Icon', 'power-pack' ),
                    'image'     => __( 'Image', 'power-pack' ),
                ],
                'condition'             => [
                    'icon_switch'   => 'yes',
                ],
            ]
        );

        $this->add_control(
            'icon',
            [
                'label'                 => __( 'Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => 'fa fa-diamond',
                'condition'             => [
                    'icon_switch'   => 'yes',
                    'icon_type'     => 'icon',
                ],
            ]
        );

		$this->add_control(
			'icon_image',
			[
				'label'                 => __( 'Image', 'power-pack' ),
				'type'                  => Controls_Manager::MEDIA,
                'condition'             => [
                    'icon_switch'   => 'yes',
                    'icon_type'     => 'image',
                ],
			]
		);

        $this->add_control(
            'icon_position',
            [
                'label'                 => __( 'Icon Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'above-title',
                'options'               => [
                    'above-title'      => __( 'Above Title', 'power-pack' ),
                    'below-title'      => __( 'Below Title', 'power-pack' ),
                ],
                'condition'             => [
                    'icon_switch'   => 'yes',
                ],
            ]
        );

        $this->add_control(
            'heading',
            [
                'label'                 => __( 'Heading', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Heading', 'power-pack' ),
                'separator'             => 'before',
            ]
        );

        $this->add_control(
            'divider_heading_switch',
            [
                'label'                 => __( 'Heading Divider', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
                'condition'             => [
                    'heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'sub_heading',
            [
                'label'                 => __( 'Sub Heading', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Sub heading', 'power-pack' ),
            ]
        );

        $this->add_control(
            'divider_subheading_switch',
            [
                'label'                 => __( 'Sub Heading Divider', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
                'condition'             => [
                    'sub_heading!' => '',
                ],
            ]
        );

        $this->add_control(
            'content',
            [
                'label'                 => __( 'Description', 'power-pack' ),
                'type'                  => Controls_Manager::TEXTAREA,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Enter promo box description', 'power-pack' ),
            ]
        );

        $this->add_control(
            'button_switch',
            [
                'label'                 => __( 'Button', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
                'separator'             => 'before',
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'                 => __( 'Button Text', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Get Started', 'power-pack' ),
                'condition'             => [
                    'button_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'                 => __( 'Link', 'power-pack' ),
                'type'                  => Controls_Manager::URL,
                'label_block'           => false,
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

        $this->add_control(
            'overlay_switch',
            [
                'label'                 => __( 'Overlay', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
                'separator'             => 'before',
            ]
        );
        
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Promo Box
         */
        $this->start_controls_section(
            'section_promo_box_style',
            [
                'label'                 => __( 'Promo Box', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
			
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'box_bg',
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-promo-box-bg',
            ]
        );
        
        $this->add_responsive_control(
            'box_height',
            [
                'label'                 => __( 'Height', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 50,
                        'max'   => 1000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-promo-box' => 'height: {{SIZE}}{{UNIT}}',
                ],
                'separator'             => 'before'
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'promo_box_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-promo-box-wrap',
			]
		);

		$this->add_control(
			'promo_box_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box, {{WRAPPER}} .pp-promo-box-wrap, {{WRAPPER}} .pp-promo-box .pp-promo-box-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'box_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Overlay
         */
        $this->start_controls_section(
            'section_promo_overlay_style',
            [
                'label'                 => __( 'Overlay', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'overlay_switch'    => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'overlay_color',
                'label'                 => __( 'Color', 'power-pack' ),
                'types'                 => [ 'classic', 'gradient' ],
                'selector'              => '{{WRAPPER}} .pp-promo-box-overlay',
                'condition'             => [
                    'overlay_switch'    => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Content
         */
        $this->start_controls_section(
            'section_promo_content_style',
            [
                'label'                 => __( 'Content', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
			'align',
			[
				'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
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
					'{{WRAPPER}} .pp-promo-box'   => 'text-align: {{VALUE}};',
				],
			]
		);
        
        $this->add_control(
			'vertical_align',
			[
				'label'                 => __( 'Vertical Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
				'default'               => 'middle',
				'options'               => [
					'top'          => [
						'title'    => __( 'Top', 'power-pack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => __( 'Center', 'power-pack' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => __( 'Bottom', 'power-pack' ),
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
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'classic', 'gradient' ],
                'selector'              => '{{WRAPPER}} .pp-promo-box-inner',
                'separator'             => 'before'
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'content_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-promo-box-inner',
                'separator'             => 'before'
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'content_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-promo-box-wrap' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'separator'             => 'before'
            ]
        );

		$this->add_responsive_control(
			'content_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Icon Section
         */
        $this->start_controls_section(
            'section_promo_box_icon_style',
            [
                'label'                 => __( 'Icon', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'icon_switch'   => 'yes',
                ],
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
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                'size_units'            => [ 'px', '%' ],
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
                    '{{WRAPPER}} .pp-promo-box-icon' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-promo-box-icon' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'icon_switch' => 'yes',
                    'icon_type' => 'icon',
                ],
            ]
        );

		$this->add_responsive_control(
			'icon_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector'              => '{{WRAPPER}} .pp-promo-box-icon',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-icon, {{WRAPPER}} .pp-promo-box-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .pp-promo-box-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
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
                    '{{WRAPPER}} .pp-promo-box-icon:hover' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-promo-box-icon:hover' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'icon_switch' => 'yes',
                    'icon_type' => 'icon',
                ],
            ]
        );

		$this->add_control(
			'hover_animation_icon',
			[
				'label'                 => __( 'Icon Animation', 'power-pack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Heading Section
         */
        $this->start_controls_section(
            'section_promo_box_heading_style',
            [
                'label'                 => __( 'Heading', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-promo-box-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-promo-box-title',
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-promo-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Heading Divider Section
         */
        $this->start_controls_section(
            'section_heading_divider_style',
            [
                'label'                 => __( 'Heading Divider', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'divider_heading_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'divider_heading_type',
            [
                'label'                 => __( 'Divider Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'border',
                'options'               => [
                    'border'    => __( 'Border', 'power-pack' ),
                    'image'     => __( 'Image', 'power-pack' ),
                ],
                'condition'             => [
                    'divider_heading_switch' => 'yes',
                ],
            ]
        );

		$this->add_control(
			'divider_title_image',
			[
				'label'                 => __( 'Image', 'power-pack' ),
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
                'label'                 => __( 'Border Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'solid',
                'options'               => [
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'double'    => __( 'Double', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
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
                'label'                 => __( 'Border Weight', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                'size_units'            => [ 'px' ],
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
                'label'                 => __( 'Border Color', 'power-pack' ),
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
                'label'                 => __( 'Spacing', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-promo-box-heading-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'divider_heading_switch'    => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Sub Heading Section
         */
        $this->start_controls_section(
            'section_subheading_style',
            [
                'label'                 => __( 'Sub Heading', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
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
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-promo-box-subtitle',
            ]
        );
        
        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-promo-box-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Heading Divider Section
         */
        $this->start_controls_section(
            'section_subheading_divider_style',
            [
                'label'                 => __( 'Sub Heading Divider', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'divider_subheading_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'divider_subheading_type',
            [
                'label'                 => __( 'Divider Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'border',
                'options'               => [
                    'border'    => __( 'Border', 'power-pack' ),
                    'image'     => __( 'Image', 'power-pack' ),
                ],
                'condition'             => [
                    'divider_subheading_switch' => 'yes',
                ],
            ]
        );

		$this->add_control(
			'divider_subheading_image',
			[
				'label'                 => __( 'Image', 'power-pack' ),
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
                'label'                 => __( 'Border Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'solid',
                'options'               => [
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'double'    => __( 'Double', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
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
                'label'                 => __( 'Border Weight', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                'size_units'            => [ 'px' ],
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
                'label'                 => __( 'Border Color', 'power-pack' ),
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
                'label'                 => __( 'Spacing', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-promo-box-subheading-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Description Section
         */
        $this->start_controls_section(
            'section_promo_description_style',
            [
                'label'                 => __( 'Description', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
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
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-promo-box-content',
            ]
        );
        
        $this->add_responsive_control(
            'content_margin',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
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
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-promo-box-content' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Footer Section
         */
        
        $this->start_controls_section(
            'section_promo_box_button_style',
            [
                'label'                 => __( 'Button', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'button_switch'   => 'yes',
                ],
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
				'condition'             => [
					'button_text!' => '',
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
                    '{{WRAPPER}} .pp-promo-box-button' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-promo-box-button' => 'color: {{VALUE}}',
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
				'selector'              => '{{WRAPPER}} .pp-promo-box-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-promo-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'button_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-promo-box-button',
            ]
        );

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
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
                    '{{WRAPPER}} .pp-promo-box-button:hover' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-promo-box-button:hover' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-promo-box-button:hover' => 'border-color: {{VALUE}}',
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
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'promo-box', 'class', 'pp-promo-box' );
        
        $this->add_render_attribute( 'icon', 'class', 'pp-promo-box-icon' );
        
        $this->add_inline_editing_attributes( 'heading', 'none' );
        $this->add_render_attribute( 'heading', 'class', 'pp-promo-box-title' );
        
        $this->add_inline_editing_attributes( 'sub_heading', 'none' );
        $this->add_render_attribute( 'sub_heading', 'class', 'pp-promo-box-subtitle' );
        
        $this->add_inline_editing_attributes( 'content', 'none' );
        $this->add_render_attribute( 'content', 'class', 'pp-promo-box-content' );

		if ( $settings['hover_animation_icon'] ) {
			$this->add_render_attribute( 'icon', 'class', 'elementor-animation-' . $settings['hover_animation_icon'] );
		}
        
        $this->add_inline_editing_attributes( 'button_text', 'none' );
        
        $this->add_render_attribute( 'button_text', 'class', [
				'pp-promo-box-button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			]
		);
        
        if ( ! empty( $settings['link']['url'] ) ) {

            $this->add_render_attribute( 'button_text', 'href', $settings['link']['url'] );

            if ( $settings['link']['is_external'] ) {
                $this->add_render_attribute( 'button_text', 'target', '_blank' );
            }

            if ( $settings['link']['nofollow'] ) {
                $this->add_render_attribute( 'button_text', 'rel', 'nofollow' );
            }
        }

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}
        ?>
        <div <?php echo $this->get_render_attribute_string( 'promo-box' ); ?>>
            <div class="pp-promo-box-bg"></div>
            <?php if ( $settings['overlay_switch'] == 'yes' ) { ?>
                <div class="pp-promo-box-overlay"></div>
            <?php } ?>
            <div class="pp-promo-box-wrap">
                <div class="pp-promo-box-inner">
                    <div class="pp-promo-box-inner-content">
                        <?php if ( $settings['icon_switch'] == 'yes' ) { ?>
                            <?php if ( $settings['icon_position'] == 'above-title' ) { ?>
                                <div class="pp-promo-box-icon-wrap">
                                    <span <?php echo $this->get_render_attribute_string( 'icon' ); ?>>
                                        <?php if ( $settings['icon_type'] == 'icon' ) { ?>
                                            <span class="pp-promo-box-icon-inner <?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></span>
                                        <?php } elseif ( $settings['icon_type'] == 'image' ) { ?>
                                            <span class="pp-promo-box-icon-inner">
                                                <img src="<?php echo esc_url( $settings['icon_image']['url'] ); ?>">
                                            </span>
                                        <?php } ?>
                                    </span>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        
                        <?php if ( ! empty( $settings['heading'] ) ) { ?>
                            <h4 <?php echo $this->get_render_attribute_string( 'heading' ); ?>>
                                <?php echo $this->parse_text_editor( $settings['heading'] ); ?>
                            </h4>
                        <?php } ?>

                        <?php if ( $settings['divider_heading_switch'] == 'yes' ) { ?>
                            <div class="pp-promo-box-heading-divider-wrap">
                                <div class="pp-promo-box-heading-divider">
                                    <?php if ( $settings['divider_heading_type'] == 'image' && $settings['divider_title_image']['url'] != '' ) { ?>
                                        <img src="<?php echo esc_url( $settings['divider_title_image']['url'] ); ?>">
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ( $settings['icon_switch'] == 'yes' ) { ?>
                            <?php if ( $settings['icon_position'] == 'below-title' ) { ?>
                                <div class="pp-promo-box-icon-wrap">
                                    <span <?php echo $this->get_render_attribute_string( 'icon' ); ?>>
                                        <?php if ( $settings['icon_type'] == 'icon' ) { ?>
                                            <span class="pp-promo-box-icon-inner <?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></span>
                                        <?php } elseif ( $settings['icon_type'] == 'image' ) { ?>
                                            <span class="pp-promo-box-icon-inner">
                                                <img src="<?php echo esc_url( $settings['icon_image']['url'] ); ?>">
                                            </span>
                                        <?php } ?>
                                    </span>
                                </div>
                            <?php } ?>
                        <?php } ?>
                        
                        <?php if ( ! empty( $settings['sub_heading'] ) ) { ?>
                            <h5 <?php echo $this->get_render_attribute_string( 'sub_heading' ); ?>>
                                <?php echo $settings['sub_heading']; ?>
                            </h5>
                        <?php } ?>

                        <?php if ( $settings['divider_subheading_switch'] == 'yes' ) { ?>
                            <div class="pp-promo-box-subheading-divider-wrap">
                                <div class="pp-promo-box-subheading-divider">
                                    <?php if ( $settings['divider_subheading_type'] == 'image' && $settings['divider_subheading_image']['url'] != '' ) { ?>
                                        <img src="<?php echo esc_url( $settings['divider_subheading_image']['url'] ); ?>">
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if ( ! empty( $settings['content'] ) ) { ?>
                            <div <?php echo $this->get_render_attribute_string( 'content' ); ?>>
                                <?php echo $this->parse_text_editor( $settings['content'] ); ?>
                            </div>
                        <?php } ?>
                        <?php if ( $settings['button_switch'] == 'yes' ) { ?>
                            <?php if ( ! empty( $settings['button_text'] ) ) { ?>
                                <div class="pp-promo-box-footer">
                                    <a <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
                                        <?php echo esc_attr( $settings['button_text'] ); ?>
                                    </a>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div><!-- .pp-promo-box-inner-content -->
                </div><!-- .pp-promo-box-inner -->
            </div><!-- .pp-promo-box-wrap -->
        </div>
        <?php
    }

    /**
	 * Render promo box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
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
                                <div class="pp-promo-box-icon-wrap">
                                    <span class="pp-promo-box-icon elementor-animation-{{ settings.hover_animation_icon }}">
                                        <# if ( settings.icon_type == 'icon' ) { #>
                                            <span class="pp-promo-box-icon-inner {{ settings.icon }}" aria-hidden="true"></span>
                                        <# } else if ( settings.icon_type == 'image' ) { #>
                                            <span class="pp-promo-box-icon-inner">
                                                <img src="{{ settings.icon_image.url }}">
                                            </span>
                                        <# } #>
                                    </span>
                                </div>
                            <# } #>
                        <# } #>
                            
                        <#
                            if ( settings.heading != '' ) {
                                var heading = settings.heading;

                                view.addRenderAttribute( 'heading', 'class', 'pp-promo-box-title' );

                                view.addInlineEditingAttributes( 'heading' );

                                var heading_html = '<h4' + ' ' + view.getRenderAttributeString( 'heading' ) + '>' + heading + '</h4>';

                                print( heading_html );
                            }
                        #>

                        <# if ( settings.divider_heading_switch == 'yes' ) { #>
                            <div class="pp-promo-box-heading-divider-wrap">
                                <div class="pp-promo-box-heading-divider">
                                    <# if ( settings.divider_heading_type == 'image' ) { #>
                                        <# if ( settings.divider_title_image.url != '' ) { #>
                                            <img src="{{ settings.divider_title_image.url }}">
                                        <# } #>
                                    <# } #>
                                </div>
                            </div>
                        <# } #>

                        <# if ( settings.icon_switch == 'yes' ) { #>
                            <# if ( settings.icon_position == 'below-title' ) { #>
                                <div class="pp-promo-box-icon-wrap">
                                    <span class="pp-promo-box-icon elementor-animation-{{ settings.hover_animation_icon }}">
                                        <# if ( settings.icon_type == 'icon' ) { #>
                                            <span class="pp-promo-box-icon-inner {{ settings.icon }}" aria-hidden="true"></span>
                                        <# } else if ( settings.icon_type == 'image' ) { #>
                                            <span class="pp-promo-box-icon-inner">
                                                <img src="{{ settings.icon_image.url }}">
                                            </span>
                                        <# } #>
                                    </span>
                                </div>
                            <# } #>
                        <# } #>
                            
                        <#
                            if ( settings.sub_heading != '' ) {
                                var sub_heading = settings.sub_heading;

                                view.addRenderAttribute( 'sub_heading', 'class', 'pp-promo-box-subtitle' );

                                view.addInlineEditingAttributes( 'sub_heading' );

                                var sub_heading_html = '<h5' + ' ' + view.getRenderAttributeString( 'sub_heading' ) + '>' + sub_heading + '</h5>';

                                print( sub_heading_html );
                            }
                        #>

                        <# if ( settings.divider_subheading_switch == 'yes' ) { #>
                            <div class="pp-promo-box-subheading-divider-wrap">
                                <div class="pp-promo-box-subheading-divider">
                                    <# if ( settings.divider_subheading_type == 'image' ) { #>
                                        <# if ( settings.divider_subheading_image.url != '' ) { #>
                                            <img src="{{ settings.divider_subheading_image.url }}">
                                        <# } #>
                                    <# } #>
                                </div>
                            </div>
                        <# } #>
                            
                        <#
                            if ( settings.content != '' ) {
                                var content = settings.content;

                                view.addRenderAttribute( 'content', 'class', 'pp-promo-box-content' );

                                view.addInlineEditingAttributes( 'content' );

                                var content_html = '<div' + ' ' + view.getRenderAttributeString( 'content' ) + '>' + content + '</div>';

                                print( content_html );
                            }
                        #>
                        
                        <# if ( settings.button_switch == 'yes' ) { #>
                            <# if ( settings.button_text != '' ) { #>
                                <div class="pp-promo-box-footer">
                                    <#
                                        var button_text = settings.button_text;

                                        view.addRenderAttribute( 'button_text', 'class', [ 'pp-promo-box-button', 'elementor-button', 'elementor-size-' + settings.button_size, 'elementor-animation-' + settings.button_hover_animation ] );

                                        view.addInlineEditingAttributes( 'button_text' );

                                        var button_html = '<a href="' + settings.link.url + '"' + ' ' + view.getRenderAttributeString( 'button_text' ) + '>' + button_text + '</a>';

                                        print( button_html );
                                    #>
                                </div>
                            <# } #>
                        <# } #>
                    </div><!-- .pp-promo-box-inner-content -->
                </div><!-- .pp-promo-box-inner -->
            </div><!-- .pp-promo-box-wrap -->
        </div>
        <?php
    }
}