<?php
namespace PowerpackElements\Modules\Buttons\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Buttons Widget
 */
class Buttons extends Powerpack_Widget {
    
    /**
	 * Retrieve buttons widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-buttons';
    }

    /**
	 * Retrieve buttons widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Buttons', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the buttons widget belongs to.
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
	 * Retrieve buttons widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-multi-buttons power-pack-admin-icon';
    }

	/**
	 * Retrieve the list of scripts the advanced menu widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
			'pp-tooltip',
			'powerpack-frontend'
        ];
    }
    /**
	 * Register buttons widget controls.
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
         * Content Tab: Buttons
         */
        $this->start_controls_section(
            'section_list',
            [
                'label'                 => __( 'Buttons', 'power-pack' ),
            ]
        );
		$repeater = new Repeater();
		
		$repeater->start_controls_tabs( 'buttons_tabs' );

			$repeater->start_controls_tab(
				'button_general',
				[
					'label' => __( 'Content', 'power-pack' ),
				]
			);

			$repeater->add_control(
				'text',
				[
					'label'       => __( 'Text', 'power-pack' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Button #1', 'power-pack' ),
					'placeholder' => __( 'Button #1', 'power-pack' ),
					'dynamic'     => [
						'active' => true,
					],
				]
			);
			$repeater->add_control(
				'pp_icon_type',
				[
					'label'       => __( 'Icon Type', 'power-pack' ),
					'type'        => Controls_Manager::CHOOSE,
					'label_block' => false,
					'default'     => 'icon',
					'options'     => [
						'' => [
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
				]
			);
			$repeater->add_control(
				'button_icon',
				[
					'label'             => __( 'Icon', 'power-pack' ),
					'label_block'       => false,
					'type'              => Controls_Manager::ICON,
					'default'           => 'fa fa-check',
					'condition'         => [
						'pp_icon_type' => 'icon',
					],
				]
			);
			$repeater->add_control(
				'icon_img',
				[
					'label'             => __( 'Image', 'power-pack' ),
					'label_block'       => true,
					'type'              => Controls_Manager::MEDIA,
					'default'           => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'dynamic'           => [
						'active'  => true,
					],
					'condition'         => [
						'pp_icon_type' => 'image',
					],
				]
			);
			$repeater->add_control(
				'icon_text',
				[
					'label'             => __( 'Title', 'power-pack' ),
					'label_block'       => false,
					'type'              => Controls_Manager::TEXT,
					'default'           => __('1','power-pack'),
					'dynamic'           => [
						'active'  => true,
					],
					'condition'         => [
						'pp_icon_type' => 'text',
					],
				]
			);
			$repeater->add_control(
				'has_tooltip',
				[
					'label' 		=> __( 'Enable Tooltip', 'power-pack' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'no',
					'yes' 		=> __( 'Yes', 'power-pack' ),
					'no' 		=> __( 'No', 'power-pack' ),
				]
			);

			$repeater->add_control(
				'tooltip_content',
				[
					'label' 		=> __( 'Tooltip Content', 'power-pack' ),
					'type' 			=> Controls_Manager::TEXTAREA,
					'default' 		=> __( 'I am a tooltip for a button', 'power-pack' ),
					'placeholder' 	=> __( 'I am a tooltip for a button', 'power-pack' ),
					'rows' 			=> 5,
					'condition'		=> [
						'has_tooltip'	=> 'yes',
					]
				]
			);

			$repeater->add_control(
				'link',
				[
					'label'             => __( 'Link', 'power-pack' ),
					'type'              => Controls_Manager::URL,
					'dynamic'           => [
						'active'  => true,
					],
					'label_block'       => false,
					'placeholder'       => __( 'http://your-link.com', 'power-pack' ),
				]
			);
			$repeater->add_control(
				'css_id',
				[
					'label'       => __( 'CSS ID', 'power-pack' ),
					'title'       => __( 'Add your custom ID WITHOUT the # key. e.g: my-id', 'power-pack' ),
					'label_block' => false,
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => [
						'active'  => true,
					],
				]
			);
			$repeater->add_control(
				'css_classes',
				[
					'label'       => __( 'CSS Classes', 'power-pack' ),
					'title'       => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'power-pack' ),
					'label_block' => false,
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => [
						'active'  => true,
					],
				]
			);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab(
				'button_design',
				[
					'label' => __( 'Style', 'power-pack' ),
				]
			);
			$repeater->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'                  => 'single_title_typography',
					'label'                 => __( 'Button Typography', 'power-pack' ),
					'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
					'selector'              => '{{WRAPPER}} {{CURRENT_ITEM}}.pp-button .pp-button-title',
				]
			);

			$repeater->add_control(
				'single_icon_size',
				[
					'label'                 => __( 'Icon Size', 'power-pack' ),
					'type'                  => Controls_Manager::SLIDER,
					'range'                 => [
						'px' => [
							'min' 	=> 5,
							'max' 	=> 100,
							'step'	=> 1,
						],
					],
					'selectors'		=> [
						'{{WRAPPER}} {{CURRENT_ITEM}} span.pp-button-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} {{CURRENT_ITEM}} .pp-button-icon-image img' => 'width: {{SIZE}}{{UNIT}};',
					]
				]
			);
			$repeater->add_control(
				'single_button_size',
				[
					'label'                 => __( 'Button Size', 'power-pack' ),
					'type'                  => Controls_Manager::SELECT,
					'default'               => 'default',
					'options'               => [
						'default' => __( 'Default', 'power-pack' ),
						'xs' => __( 'Extra Small', 'power-pack' ),
						'sm' => __( 'Small', 'power-pack' ),
						'md' => __( 'Medium', 'power-pack' ),
						'lg' => __( 'Large', 'power-pack' ),
						'xl' => __( 'Extra Large', 'power-pack' ),
					],
				]
			);
			$repeater->add_responsive_control(
				'single_button_padding',
				[
					'label'                 => __( 'Padding', 'power-pack' ),
					'type'                  => Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', 'em', '%' ],
					'selectors'             => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$repeater->add_control(
				'single_normal_options',
				[
					'label'     => __( 'Normal', 'power-pack' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);
			$repeater->add_control(
				'single_button_bg_color',
				[
					'label'                 => __( 'Background Color', 'power-pack' ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '',
					'selectors'             => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button' => 'background: {{VALUE}};',
					],
				]
			);
			$repeater->add_control(
				'single_text_color',
				[
					'label'                 => __( 'Text Color', 'power-pack' ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '',
					'selectors'             => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button' => 'color: {{VALUE}};',
					],
				]
			);
			$repeater->add_control(
				'single_icon_color',
				[
					'label'                 => __( 'Icon Color', 'power-pack' ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '',
					'selectors'             => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button .pp-buttons-icon-wrapper span' => 'color: {{VALUE}};',
					],
				]
			);
			$repeater->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'                  => 'single_button_border',
					'label'                 => __( 'Border', 'power-pack' ),
					'placeholder'           => '1px',
					'default'               => '1px',
					'selector'              => '{{WRAPPER}} {{CURRENT_ITEM}}.pp-button',
				]
			);
			$repeater->add_control(
				'single_button_border_radius',
				[
					'label'                 => __( 'Border Radius', 'power-pack' ),
					'type'                  => Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%' ],
					'selectors'             => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$repeater->add_control(
				'single_hover_options',
				[
					'label' => __( 'Hover', 'power-pack' ),
					'type'  => Controls_Manager::HEADING,
					'separator' => 'after',
				]
			);

			$repeater->add_control(
				'single_button_bg_color_hover',
				[
					'label'                 => __( 'Background Color', 'power-pack' ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '',
					'selectors'             => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button:hover' => 'background: {{VALUE}};',
					],
				]
			);

			$repeater->add_control(
				'single_text_color_hover',
				[
					'label'                 => __( 'Text Color', 'power-pack' ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '',
					'selectors'             => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button:hover' => 'color: {{VALUE}};',
					],
				]
			);

			$repeater->add_control(
				'single_icon_color_hover',
				[
					'label'                 => __( 'Icon Color', 'power-pack' ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '',
					'selectors'             => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button:hover .pp-buttons-icon-wrapper span' => 'color: {{VALUE}};',
					],
				]
			);
        
			$repeater->add_control(
				'single_border_color_hover',
				[
					'label'                 => __( 'Border Color', 'power-pack' ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '',
					'selectors'             => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button:hover' => 'border-color: {{VALUE}};',
					],
				]
			);

			$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'buttons',
			[
				'label'       => __( 'Buttons', 'power-pack' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      => array_values( $repeater->get_controls() ),
				'title_field' => '{{{ text }}}',
				'default'     => [
					[
						'text' => __( 'Button #1', 'power-pack' ),
					],
					[
						'text' => __( 'Button #2', 'power-pack' ),
					],
				],
			]
		);
        $this->end_controls_section();

        $this->end_controls_section();
        /**
         * Style Tab: Layout
         */
        $this->start_controls_section(
            'button_layout',
            [
                'label' => __( 'Layout', 'power-pack' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_control(
			'button_size',
			[
				'label'                 => __( 'Buttons Size', 'power-pack' ),
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
		$this->add_responsive_control(
			'button_spacing',
			[
				'label'                 => __( 'Buttons Spacing', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                ],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-buttons-group .pp-button:not(:last-child)'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'(desktop){{WRAPPER}}.pp-buttons-stack-desktop .pp-buttons-group .pp-button:not(:last-child)'  => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.pp-buttons-stack-tablet .pp-buttons-group .pp-button:not(:last-child)'  => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.pp-buttons-stack-mobile .pp-buttons-group .pp-button:not(:last-child)'  => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'vertical_align',
            [
                'label'                 => __( 'Vertical Alignment', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'default'               => 'middle',
                'options'               => [
                    'top'    		=> [
                        'title' 	=> __( 'Top', 'power-pack' ),
                        'icon' 		=> 'eicon-v-align-top',
                    ],
                    'middle' 		=> [
                        'title' 	=> __( 'Middle', 'power-pack' ),
                        'icon' 		=> 'eicon-v-align-middle',
                    ],
                    'bottom' 		=> [
                        'title' 	=> __( 'Bottom', 'power-pack' ),
                        'icon' 		=> 'eicon-v-align-bottom',
                    ],
                    'stretch' 		=> [
                        'title' 	=> __( 'Stretch', 'power-pack' ),
                        'icon' 		=> 'eicon-v-align-stretch',
                    ],
                ],
                'prefix_class'          => 'pp-buttons-valign%s-',
            ]
        );

		$this->add_responsive_control(
			'button_align',
			[
				'label'                 => __( 'Horizontal Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'left',
				'options'               => [
					'left'    		=> [
                        'title' 	=> __( 'Left', 'elementor-extras' ),
                        'icon' 		=> 'eicon-h-align-left',
                    ],
                    'center' 		=> [
                        'title' 	=> __( 'Center', 'elementor-extras' ),
                        'icon' 		=> 'eicon-h-align-center',
                    ],
                    'right' 		=> [
                        'title' 	=> __( 'Right', 'elementor-extras' ),
                        'icon' 		=> 'eicon-h-align-right',
                    ],
                    'stretch' 		=> [
                        'title' 	=> __( 'Stretch', 'elementor-extras' ),
                        'icon' 		=> 'eicon-h-align-stretch',
                    ],
				],
				'prefix_class'          => 'pp-buttons-halign%s-',
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label'                 => __( 'Content Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'left',
				'options'               => [
					'left'    		=> [
                        'title' 	=> __( 'Left', 'elementor-extras' ),
                        'icon' 		=> 'eicon-h-align-left',
                    ],
                    'center' 		=> [
                        'title' 	=> __( 'Center', 'elementor-extras' ),
                        'icon' 		=> 'eicon-h-align-center',
                    ],
                    'right' 		=> [
                        'title' 	=> __( 'Right', 'elementor-extras' ),
                        'icon' 		=> 'eicon-h-align-right',
                    ],
                    'stretch' 		=> [
                        'title' 	=> __( 'Stretch', 'elementor-extras' ),
                        'icon' 		=> 'eicon-h-align-stretch',
                    ],
				],
				'selectors_dictionary'  => [
					'left'         => 'flex-start',
					'center'       => 'center',
					'right'        => 'flex-end',
					'stretch'      => 'stretch',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-button .pp-button-content-wrapper'   => 'justify-content: {{VALUE}};',
				],
                'condition'             => [
                    'button_align' => 'stretch',
                ],
			]
		);

		$this->add_control(
			'stack_on',
			[
				'label'                 => __( 'Stack on', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'none',
				'description'           => __( 'Choose a breakpoint where the buttons will stack.', 'power-pack' ),
				'options'               => [
					'none'    => __( 'None', 'power-pack' ),
					'desktop' => __( 'Desktop', 'power-pack' ),
					'tablet'  => __( 'Tablet', 'power-pack' ),
					'mobile'  => __( 'Mobile', 'power-pack' ),
				],
				'prefix_class'          => 'pp-buttons-stack-',
			]
		);
        
		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        /**
         * Style Tab: Styling
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_button_style',
            [
                'label'                 => __( 'Styling', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'button_typography',
				'label'                 => __( 'Typography', 'power-pack' ),
				'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
				'selector'              => '{{WRAPPER}} .pp-button',
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
					'default'               => '#818a91',
					'selectors'             => [
						'{{WRAPPER}} .pp-button' => 'background: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'button_text_color_normal',
				[
					'label'                 => __( 'Text Color', 'power-pack' ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '#ffffff',
					'selectors'             => [
						'{{WRAPPER}} .pp-button' => 'color: {{VALUE}}',
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
					'selector'              => '{{WRAPPER}} .pp-button',
				]
			);
			$this->add_responsive_control(
				'button_border_radius',
				[
					'label'                 => __( 'Border Radius', 'power-pack' ),
					'type'                  => Controls_Manager::DIMENSIONS,
					'size_units'            => [ 'px', '%' ],
					'selectors'             => [
						'{{WRAPPER}} .pp-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'                  => 'button_box_shadow',
					'selector'              => '{{WRAPPER}} .pp-button',
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
						'{{WRAPPER}} .pp-button:hover' => 'background: {{VALUE}};',
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
						'{{WRAPPER}} .pp-button:hover' => 'color: {{VALUE}}',
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
						'{{WRAPPER}} .pp-button:hover' => 'border-color: {{VALUE}}',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'                  => 'button_box_shadow_hover',
					'selector'              => '{{WRAPPER}} .pp-button:hover',
				]
			);
			$this->add_control(
				'button_animation',
				[
					'label'                 => __( 'Animation', 'power-pack' ),
					'type'                  => Controls_Manager::HOVER_ANIMATION,
				]
			);

		$this->end_controls_tab();
		
        $this->end_controls_tabs();
        
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

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'icon_typography',
                'label'     => __( 'Typography', 'power-pack' ),
                'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-button-icon-number',
            ]
		);
		$this->add_responsive_control(
			'icon_position',
			[
				'label'             => __( 'Icon Position', 'power-pack' ),
				'type'              => Controls_Manager::SELECT,
				'default'           => 'before',
				'options'           => [
					'after'     => __( 'After', 'power-pack' ),
					'before'    => __( 'Before', 'power-pack' ),
					'top'       => __( 'Top', 'power-pack' ),
					'bottom'    => __( 'Bottom', 'power-pack' ),
				],
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label'                 => __( 'Size', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size' => '',
				],
				'range'                 => [
					'px' => [
						'min' => 6,
						'max' => 100,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-button-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-button-icon-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
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
					'{{WRAPPER}} .pp-icon-before .pp-buttons-icon-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-icon-after .pp-buttons-icon-wrapper' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-icon-top .pp-buttons-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-icon-bottom .pp-buttons-icon-wrapper' => 'margin-top: {{SIZE}}{{UNIT}};',
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
				'icon_color',
				[
					'label'                 => __( 'Color', 'power-pack' ),
					'type'                  => Controls_Manager::COLOR,
					'default'               => '',
					'selectors'             => [
						'{{WRAPPER}} .pp-buttons-icon-wrapper span' => 'color: {{VALUE}};',
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
						'{{WRAPPER}} .pp-button:hover .pp-buttons-icon-wrapper .pp-button-icon' => 'color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();
        
		$this->end_controls_tabs();

        $this->end_controls_section();

		/**
         * Style Tab: Tooltip
         * -------------------------------------------------
         */
		
		 $this->start_controls_section(
			'section_tooltip_style',
			[
				'label' 	=> __( 'Tooltip', 'power-pack' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);
		
			$this->add_responsive_control(
				'tooltips_position',
				[
					'label'		=> __( 'Tooltip Position', 'power-pack' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'above',
					'options' 	=> [
						'above' 	=> __( 'Above', 'power-pack' ),
						'below' 	=> __( 'Below', 'power-pack' ),
						'left' 		=> __( 'Left', 'power-pack' ),
						'right' 	=> __( 'Right', 'power-pack' ),
					],
				]
			);
			$this->add_control(
				'tooltips_align',
				[
					'label' 	=> __( 'Text Align', 'power-pack' ),
					'type' 		=> Controls_Manager::CHOOSE,
					'default'	=>' center',
					'options' 	=> [
						'left' 	=> [
							'title' 	=> __( 'Left', 'power-pack' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 	=> [
							'title' => __( 'Center', 'power-pack' ),
							'icon' 	=> 'fa fa-align-center',
						],
						'right' 	=> [
							'title' => __( 'Right', 'power-pack' ),
							'icon'	=> 'fa fa-align-right',
						],
					],
					'selectors' => [
						'.pp-tooltip-{{ID}}' => 'text-align: {{VALUE}};',
					],
				]
			);
			$this->add_responsive_control(
				'tooltips_padding',
				[
					'label' 		=> __( 'Padding', 'power-pack' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'.pp-tooltip-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_responsive_control(
				'tooltips_border_radius',
				[
					'label' 		=> __( 'Border Radius', 'power-pack' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'.pp-tooltip-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'tooltips_typography',
					'scheme' 	=> Scheme_Typography::TYPOGRAPHY_3,
					'separator' => 'after',
					'selector' 	=> '.pp-tooltip-{{ID}}',
				]
			);
			$this->add_control(
				'tooltips_background_color',
				[
					'label' 	=> __( 'Background Color', 'power-pack' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '#000000',
					'selectors' => [
						'.pp-tooltip-{{ID}}'       => 'background-color: {{VALUE}};',
						'.pp-tooltip-{{ID}}.above .tooltip-callout:after' => 'border-top-color: {{VALUE}};',
						'.pp-tooltip-{{ID}}.left .tooltip-callout:after' => 'border-left-color: {{VALUE}};',
						'.pp-tooltip-{{ID}}.right .tooltip-callout:after' => 'border-right-color: {{VALUE}};',
						'.pp-tooltip-{{ID}}.below .tooltip-callout:after' => 'border-bottom-color: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'tooltips_color',
				[
					'label' 	=> __( 'Color', 'power-pack' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '#ffffff',
					'selectors' => [
						'.pp-tooltip-{{ID}}' => 'color: {{VALUE}};',
					],
				]
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name' 		=> 'tooltips_box_shadow',
					'selector' => '.pp-tooltip-{{ID}}',
					'separator'	=> '',
				]
			);

		$this->end_controls_section();
    }

    /**
     * Render buttons widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Button Animation
        $button_animation = '';
        if ( $settings['button_animation'] ) {
            $button_animation = 'elementor-animation-' . $settings['button_animation'];
        }

        $i = 1;
        ?>
        <div class="pp-buttons-group">
            <?php foreach ( $settings['buttons'] as $index => $item ) : ?>
                <?php
                $button_key         = $this->get_repeater_setting_key( 'button', 'buttons', $index );
                $content_inner_key  = $this->get_repeater_setting_key( 'content', 'buttons', $index );

                // Button Size
                $buttonSize = ( $item['single_button_size'] != 'default' ) ? $item['single_button_size'] : $settings['button_size'];

                // Link
                if ( ! empty( $item['link']['url'] ) ) {
                    $this->add_render_attribute( $button_key, 'href', $item['link']['url'] );

                    if ( $item['link']['is_external'] ) {
                        $this->add_render_attribute( $button_key, 'target', '_blank' );
                    }

                    if ( $item['link']['nofollow'] ) {
                        $this->add_render_attribute( $button_key, 'rel', 'nofollow' );
                    }
                }

                // Icon Position
                $iconPosition = '';
                if ( $settings['icon_position'] ) {
                    $iconPosition = 'pp-icon-' . $settings['icon_position'];
                }
                if ( $settings['icon_position_tablet'] ) {
                    $iconPosition .= ' pp-icon-' . $settings['icon_position_tablet'] . '-tablet';
                }
                if ( $settings['icon_position_mobile'] ) {
                    $iconPosition .= ' pp-icon-' . $settings['icon_position_mobile'] . '-mobile';
                }

                $this->add_render_attribute( $button_key, 'class', [
                        'pp-button',
                        'elementor-button',
                        'elementor-size-' . $buttonSize,
                        'elementor-repeater-item-' . $item['_id'],
                        $button_animation,
                    ]
                );
        
                // CSS ID
                if ( $item['css_id'] ) {
                    $this->add_render_attribute( $button_key, 'id', $item['css_id'] );
                }

                // Custom Class
                if ( $item['css_classes'] ) {
                    $this->add_render_attribute( $button_key, 'class', $item['css_classes'] );
                }

                // ToolTip
                if ( $item['has_tooltip'] == 'yes' && ! empty( $item['tooltip_content'] ) ) {
                    if ( $settings['tooltips_position_tablet'] ) {
                        $ttip_tablet = $settings['tooltips_position_tablet'];
                    } else { 
                        $ttip_tablet = $settings['tooltips_position'];
                    };

                    if ( $settings['tooltips_position_mobile'] ) {
                        $ttip_mobile = $settings['tooltips_position_mobile'];
                    } else { 
                        $ttip_mobile = $settings['tooltips_position'];
                    };
                    
                    $this->add_render_attribute(
                        $button_key,
                        [
                            'data-tooltip'                  => htmlspecialchars( $item['tooltip_content'] ),
                            'data-tooltip-position'         => $settings['tooltips_position'],
                            'data-tooltip-position-tablet'  => $ttip_tablet,
                            'data-tooltip-position-mobile'  => $ttip_mobile,
                        ]
                    );
                }

                $this->add_render_attribute( $content_inner_key, 'class', [
                        'pp-button-content-inner',
                        $iconPosition,
                    ]
                );
                ?>

                <a <?php echo $this->get_render_attribute_string( $button_key ); ?>>
                    <div class="pp-button-content-wrapper">
                        <span <?php echo $this->get_render_attribute_string( $content_inner_key ); ?>>
                            <?php
                            if ( $item['pp_icon_type'] != 'none' ) {
                                $icon_key = 'icon_' . $i;
                                $icon_wrap = 'pp-buttons-icon-wrapper';
                                $this->add_render_attribute( $icon_key, 'class', $icon_wrap );
                                ?>
                                <span <?php echo $this->get_render_attribute_string( $icon_key ); ?>>
                                    <?php
                                    if ( $item['pp_icon_type'] == 'icon' ) {
                                        printf( '<span class="pp-button-icon %1$s"></span>', esc_attr( $item['button_icon'] ) );
                                    } elseif ( $item['pp_icon_type'] == 'image' ) {
                                        printf( '<span class="pp-button-icon-image"><img src="%1$s"></span>', esc_url( $item['icon_img']['url'] ) );
                                    } elseif ( $item['pp_icon_type'] == 'text' ) {
                                        printf( '<span class="pp-button-icon pp-button-icon-number">%1$s</span>', esc_attr( $item['icon_text'] ) );
                                    }
                                    ?>
                                </span>
                            <?php
                            }
                            if ( $item['text'] ) { ?>
                                <?php
                                $text_key = $this->get_repeater_setting_key( 'text', 'buttons', $index );
                                $this->add_render_attribute( $text_key, 'class', 'pp-button-title' );
                                $this->add_inline_editing_attributes( $text_key, 'none' ); ?>

                                <span <?php echo $this->get_render_attribute_string( $text_key ); ?>>
                                <?php
                                    echo $item['text'];
                                ?>
                                </span>
                            <?php } ?>
                        </span>
                    </div>
                </a>
            <?php $i++; endforeach; ?>
        </div>
    <?php
    }

    /**
	 * Render buttons widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <div class="pp-buttons-group">
            <# var i = 1; #>
            <# _.each( settings.buttons, function( item ) { #>
                <#
                var button_key = 'button_' + i;
                var content_inner_key = 'content-inner_' + i;
                var buttonSize = '';
                var iconPosition = '';

                if ( item.single_button_size != 'default' ) {
                    buttonSize = item.single_button_size;
                } else {
                    buttonSize = settings.button_size;
                }

                if ( settings.icon_position ) {
                    iconPosition = 'pp-icon-' + settings.icon_position;
                }

                if ( settings.icon_position_tablet ) {
                    iconPosition += ' pp-icon-' + settings.icon_position_tablet + '-tablet';
                }

                if ( settings.icon_position_mobile ) {
                    iconPosition += ' pp-icon-' + settings.icon_position_mobile + '-mobile';
                }

                view.addRenderAttribute(
                    button_key,
                    {
                        'id': item.css_id,
                        'class': [
                            'pp-button',
                            'elementor-button',
                            'elementor-size-' + buttonSize,
                            'elementor-repeater-item-' + item._id,
                            'elementor-animation-' + settings.button_animation,
                            item.css_classes,
                        ],
                    }
                );

                if ( item.has_tooltip == 'yes' && item.tooltip_content != '' ) {
                    var ttip_tablet;
                    var ttip_mobile;
                   
                    if ( settings.tooltips_position_tablet ) {
                        ttip_tablet = settings.tooltips_position_tablet;
                    } else { 
                        ttip_tablet = settings.tooltips_position;
                    };
                    if ( settings.tooltips_position_mobile ) {
                        ttip_mobile = settings.tooltips_position_mobile;
                    } else { 
                        ttip_mobile = settings.tooltips_position;
                    };
                   
                    view.addRenderAttribute(
                        button_key,
                        {
                            'data-tooltip': item.tooltip_content,
                            'data-tooltip-position': settings.tooltips_position,
                            'data-tooltip-position-tablet': ttip_tablet,
                            'data-tooltip-position-mobile': ttip_mobile,
                        }
                    );
                }

                if ( item.link.url != '' ) {
                    view.addRenderAttribute( button_key, 'href', item.link.url );

                    if ( item.link.is_external ) {
                        view.addRenderAttribute( button_key, 'target', '_blank' );
                    }

                    if ( item.link.nofollow ) {
                        view.addRenderAttribute( button_key, 'rel', 'nofollow' );
                    }
                }

                view.addRenderAttribute(
                    content_inner_key,
                    {
                        'class': [
                            'pp-button-content-inner',
                            iconPosition,
                        ],
                    }
                );
                #>

                <a {{{ view.getRenderAttributeString( button_key ) }}}>
                    <div class="pp-button-content-wrapper">
                        <span {{{ view.getRenderAttributeString( content_inner_key ) }}}>
                            <# if ( item.pp_icon_type != 'none' ) { #>
                                <#
                                    var icon_key = 'icon_' + i;
                               
                                    view.addRenderAttribute( icon_key, 'class', 'pp-buttons-icon-wrapper' );
                                #>
                                <span {{{ view.getRenderAttributeString( icon_key ) }}}>
                                    <# if ( item.pp_icon_type == 'icon' ) { #>
                                        <span class="pp-button-icon {{{ item.button_icon }}}"></span>
                                    <# } else if ( item.pp_icon_type == 'image' ) { #>
                                        <span class="pp-button-icon-image">
                                            <img src="{{{ item.icon_img.url }}}">
                                        </span>
                                    <# } else if ( item.pp_icon_type == 'text' ) { #>
                                        <span class="pp-button-icon pp-button-icon-number">
                                            {{{ item.icon_text }}}
                                        </span>
                                    <# } #>
                                </span>
                            <# } #>
                                
                            <# if ( item.text != '' ) { #>
                                <#
                                    var text_key = 'text_' + i;
                               
                                    view.addRenderAttribute( text_key, 'class', 'pp-button-title' );
                                   
                                    view.addInlineEditingAttributes( text_key, 'none' );
                                #>
                                
                                <span {{{ view.getRenderAttributeString( text_key ) }}}>
                                    {{{ item.text }}}
                                </span>
                            <# } #>
                        </span>
                    </div>
                </a>
            <# i++ } ); #>
        </div>
        <?php
    }
}
