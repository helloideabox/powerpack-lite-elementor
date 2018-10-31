<?php
namespace PowerpackElements\Modules\AdvancedTabs\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Advanced Tabs Widget
 */
class Advanced_Tabs extends Powerpack_Widget {

	/**
	 * Retrieve advanced tabs widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pp-advanced-tabs';
	}

	/**
	 * Retrieve advanced tabs widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Advanced Tabs', 'power-pack' );
	}

	/**
	 * Retrieve the list of categories the advanced tabs widget belongs to.
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
	 * Retrieve advanced tabs widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'ppicon-tabs power-pack-admin-icon';
	}

    /**
	 * Retrieve the list of scripts the advanced tabs widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
        return [
            'powerpack-frontend',
        ];
    }

	/**
	 * Register advanced tabs widget controls.
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
		 * Content Tab: Advanced Tabs
		 */

		$this->start_controls_section(
			'section_price_menu',
			[
				'label' => __( 'Advanced Tabs', 'power-pack' ),
			]
		);
        
        $repeater = new Repeater();
        
        $repeater->add_control(
			'icon',
			[
				'name'    => 'icon',
                'label'   => __( 'Icon', 'power-pack' ),
                'type'    => Controls_Manager::ICON,
                'default' => 'fa fa-check',
			]
		);
        
        $repeater->add_control(
			'tab_title',
			[
                'label'       => __( 'Title', 'power-pack' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __( 'Title', 'power-pack' ),
                'default'     => __( 'Title', 'power-pack' ),
			]
		);
        
        $repeater->add_control(
			'content_type',
			[
                'label'   => __( 'Content Type', 'power-pack' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'tab_content',
                'options' => [
                    'tab_content'   => __( 'Content', 'power-pack' ),
                    'tab_photo'     => __( 'Image', 'power-pack' ),
                    'tab_video'     => __( 'Link (Video/Map/Page)', 'power-pack' ),
                    'section'       => __( 'Saved Section', 'power-pack' ),
                    'widget'        => __( 'Saved Widget', 'power-pack' ),
                    'template'      => __( 'Saved Page Template', 'power-pack' ),
                ],
			]
		);
        
        $repeater->add_control(
			'content',
            [
                'label'   => __( 'Content', 'power-pack' ),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => __( 'I am tab content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                'condition' => [
                    'content_type' => 'tab_content',
                ],
            ]
		);
        
        $repeater->add_control(
			'image',
            [
                'label'     => __( 'Image', 'power-pack' ),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'content_type' => 'tab_photo',
                ],
            ]
		);
        
        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'                  => 'image',
                'label'                 => __( 'Image Size', 'power-pack' ),
                'default'               => 'large',
                'exclude'               => [ 'custom' ],
                'condition' => [
                    'content_type' => 'tab_photo',
                ],
            ]
        );
        
        $repeater->add_control(
			'link_video',
            [
                'label'       => __( 'Link', 'power-pack' ),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __( 'Enter your Video link', 'power-pack' ),
                'label_block' => true,
                'condition'   => [
                    'content_type' => 'tab_video',
                ],
            ]
		);
        
        $repeater->add_control(
			'saved_widget',
            [
                'label'                 => __( 'Choose Widget', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => $this->get_page_template_options( 'widget' ),
                'default'               => '-1',
                'condition'             => [
                    'content_type'    => 'widget',
                ],
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'widget',
                        ],
                    ],
                ]
            ]
		);
        
        $repeater->add_control(
			'saved_section',
            [
                'label'                 => __( 'Choose Section', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => $this->get_page_template_options( 'section' ),
                'default'               => '-1',
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'section',
                        ],
                    ],
                ]
            ]
		);
        
        $repeater->add_control(
			'templates',
            [
                'label'                 => __( 'Choose Template', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => $this->get_page_template_options( 'page' ),
                'default'               => '-1',
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'template',
                        ],
                    ],
                ]
            ]
		);

		$this->add_control(
			'tab_features',
			[
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => [
					[
						'tab_title'     => __( 'Tab #1', 'power-pack' ),
						'content' => __( 'I am tab 1 content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
					],
					[
						'tab_title'     => __( 'Tab #2', 'power-pack' ),
						'content' => __( 'I am tab 2 content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
					],
					[
						'tab_title'     => __( 'Tab #3', 'power-pack' ),
						'content' => __( 'I am tab 3 content. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
					],
				],
				'fields'                => array_values( $repeater->get_controls() ),
				'title_field' => '{{{ tab_title }}}',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_general_layout',
			[
				'label' => __( 'Layout', 'power-pack' ),
			]
		);
		$this->add_control(
			'type',
			[
				'label'   => __( 'Layout', 'power-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'at-horizontal',
				'options' => [
					'at-horizontal' => __( 'Horizontal', 'power-pack' ),
					'at-vertical'   => __( 'Vertical', 'power-pack' ),
				],
			]
		);
		$this->add_control(
			'custom_style',
			[
				'label'   => __( 'Select Style', 'power-pack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'style-0',
				'options' => [
					'style-0' => __( 'Basic', 'power-pack' ),
					'style-1' => __( 'Style-1', 'power-pack' ),
					'style-2' => __( 'Style-2', 'power-pack' ),
					'style-3' => __( 'Style-3', 'power-pack' ),
					'style-4' => __( 'Style-4', 'power-pack' ),
					'style-5' => __( 'Style-5', 'power-pack' ),
					'style-6' => __( 'Style-6', 'power-pack' ),
					'style-7' => __( 'Style-7', 'power-pack' ),
					'style-8' => __( 'Style-8', 'power-pack' ),
				],
			]
		);
		$this->add_control(
			'default_tab',
			[
				'label'       => __( 'Default Active Tab Index', 'power-pack' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 1 ),
				'placeholder' => __( 'Default Active Tab Index', 'power-pack' ),
			]
		);

		$this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
		/*	STYLE TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Style Tab: Color
		 */
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __( 'Title', 'power-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'title_align_horizontal',
			[
				'label'     => __( 'Alignment', 'power-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'   => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'center',
				'condition' => [
					'type' => 'at-horizontal',
				],
				'selectors' => [
					'{{WRAPPER}} .pp-advanced-tabs-wrapper.at-horizontal' => 'justify-content: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_align_vertical',
			[
				'label'     => __( 'Alignment', 'power-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => __( 'Top', 'power-pack' ),
						'icon'  => 'fa fa-angle-double-up',
					],
					'center'     => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-justify',
					],
					'flex-end'   => [
						'title' => __( 'Bottom', 'power-pack' ),
						'icon'  => 'fa fa-angle-double-down',
					],
				],
				'default'   => 'flex-start',
				'condition' => [
					'type' => 'at-vertical',
				],
				'selectors' => [
					'{{WRAPPER}} .pp-advanced-tabs-wrapper.at-vertical' => 'justify-content: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'icon_position',
			[
				'label'   => __( 'Icon Position', 'power-pack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'top'    => __( 'Top', 'power-pack' ),
					'bottom' => __( 'Bottom', 'power-pack' ),
					'left'   => __( 'Left', 'power-pack' ),
					'right'  => __( 'Right', 'power-pack' ),
				],
				'default' => 'left',
				'separator'  => 'before',
			]
		);
		$this->add_responsive_control(
			'icon_size',
			[
				'label'      => __( 'Icon Size', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 15,
				],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-advanced-tabs-title i' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'separator'  => 'after',
			]
		);
		$this->add_control(
			'title_border_radius',
			[
				'label'      => __( 'Border Radius', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'default'    => [
					'top' => 0,
					'bottom' => 0,
					'left' => 0,
					'right' => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-advanced-tabs-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_padding',
			[
				'label'      => __( 'Padding', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'separator'  => 'after',
				'default'    => [
					'top' => 10,
					'bottom' => 10,
					'left' => 10,
					'right' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-advanced-tabs-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'title_margin_right',
			[
				'label'      => __( 'Margin Right', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 0,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'condition' => [
					'type' => 'at-horizontal',
					'title_align_horizontal' => 'flex-end',
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .at-horizontal .pp-advanced-tabs-title:last-child' => 'margin-right: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_responsive_control(
			'title_margin_left',
			[
				'label'      => __( 'Margin Left', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 0,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'condition' => [
					'type' => 'at-horizontal',
					'title_align_horizontal' => 'flex-start',
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .at-horizontal .pp-advanced-tabs-title:first-child' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_responsive_control(
			'title_margin_top',
			[
				'label'      => __( 'Margin Top', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 0,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'condition' => [
					'type' => 'at-vertical',
					'title_align_vertical' => 'flex-start',
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .at-vertical .pp-advanced-tabs-title:first-child' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_responsive_control(
			'title_margin_bottom',
			[
				'label'      => __( 'Margin Bottom', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 0,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'condition' => [
					'type' => 'at-vertical',
					'title_align_vertical' => 'flex-end',
				],
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .at-vertical .pp-advanced-tabs-title:last-child' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_responsive_control(
			'title_space',
			[
				'label'      => __( 'Space Between Tabs', 'power-pack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 0,
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
				],
				'size_units' => [ 'px' ],
				'separator'  => 'after',
				'selectors'  => [
					'{{WRAPPER}} .at-horizontal .pp-advanced-tabs-title:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .at-vertical .pp-advanced-tabs-title:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_typography',
				'label'     => __( 'Title Typography', 'power-pack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-advanced-tabs-title span',
			]
		);
		$this->start_controls_tabs( 'tabs_title_style' );

		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => __( 'Normal', 'power-pack' ),
			]
		);
		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#808080',
				'selectors' => [
					'{{WRAPPER}} .pp-advanced-tabs-title i' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'title_text_color',
			[
				'label'     => __( 'Title Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#808080',
				'selectors' => [
					'{{WRAPPER}} .pp-advanced-tabs-title span' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'title_bg_color',
			[
				'label'     => __( 'Title Background Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'condition' => [
					'custom_style!' => 'style-6',
				],
				'selectors' => [
					'{{WRAPPER}} .pp-advanced-tabs-title' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'title_border_color',
			[
				'label'     => __( 'Title Border Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#808080',
				'condition' => [
					'custom_style' => [ 'style-6' ],
				],
				'selectors' => [
					'{{WRAPPER}} .pp-advanced-tabs-title' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-6 .pp-advanced-tabs-title:after' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab(); // End Normal Tab

		$this->start_controls_tab(
			'tab_title_active',
			[
				'label' => __( 'Active', 'power-pack' ),
			]
		);
		$this->add_control(
			'icon_color_active',
			[
				'label'     => __( 'Active Icon Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .at-active i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .at-hover .pp-advanced-tabs-title:hover i' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'title_text_color_active',
			[
				'label'     => __( 'Active Title Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .at-active span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .at-hover .pp-advanced-tabs-title:hover span' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'title_bg_color_active',
			[
				'label'     => __( 'Active Background Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .at-active' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .at-hover .pp-advanced-tabs-title:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-1 .at-horizontal .at-active:after' => 'border-top-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-1 .at-vertical .at-active:after' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-6 .pp-advanced-tabs-title.at-active:after' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'title_border_color_active',
			[
				'label'     => __( 'Active Border Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'condition' => [
					'custom_style!' => [ 'style-1', 'style-6', 'style-7', 'style-8' ],
				],
				'selectors' => [
					'{{WRAPPER}} .at-active' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .at-hover .pp-advanced-tabs-title:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-2 .pp-advanced-tabs-title.at-active:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-2 .at-hover .pp-advanced-tabs-title.at-active:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-3 .pp-advanced-tabs-title.at-active:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-3 .at-hover .pp-advanced-tabs-title.at-active:before' => 'background-color: {{VALUE}}',
				],
			]
			);
			$this->add_control(
				'title_animation_color',
				[
				'label'     => __( 'Animation Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'condition' => [
					'custom_style' => [ 'style-4', 'style-5', 'style-7', 'style-8' ],
				],
				'selectors' => [
					'{{WRAPPER}} .pp-style-4 .pp-advanced-tabs-title.at-active:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-4 .pp-advanced-tabs-title.at-active:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-5 .pp-advanced-tabs-title.at-active:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-5 .pp-advanced-tabs-title.at-active:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-7 .pp-advanced-tabs-title .active-slider-span' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-style-8 .pp-advanced-tabs-title .active-slider-span' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab(); // End Hover Tab

		$this->end_controls_tabs(); // End Controls Tab

		$this->add_responsive_control(
			'tab_hover_effect',
			[
				'label'     => __( 'Hover Effect', 'power-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'yes' => [
						'title' => __( 'Yes', 'power-pack' ),
						'icon'  => 'fa fa-check',
					],
					'no'     => [
						'title' => __( 'No', 'power-pack' ),
						'icon'  => 'fa fa-ban',
					],
				],
				'condition' => [
					'custom_style!' => [ 'style-6' ],
				],
				'separator' => 'before',
				'default'   => 'no',
			]
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Color
		 */
		$this->start_controls_section(
			'section_content_style',
			[
				'label' => __( 'Content', 'power-pack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'tab_align',
			[
				'label'     => __( 'Alignment', 'power-pack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'start' => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'     => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'end'   => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'   => 'center',
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .pp-advanced-tabs-content'   => 'justify-content: flex-{{VALUE}}; text-align: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'tab_bg_style',
				'label'    => __( 'Background', 'power-pack' ),
				'types'    => [ 'none','classic','gradient' ],
				'selector' => '{{WRAPPER}} .pp-advanced-tabs-content',
			]
		);
		$this->add_control(
			'text_color',
			[
				'label'     => __( 'Text Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#808080',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .pp-advanced-tabs-content' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'tab_text_typography',
				'label'    => __( 'Text Typography', 'power-pack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .pp-advanced-tabs-content',
			]
		);
		$this->add_control(
			'tab_border_color',
			[
				'label'     => __( 'Border Color', 'power-pack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#808080',
				'selectors' => [
					'{{WRAPPER}} .pp-advanced-tabs-content' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'tab_border_radius',
			[
				'label'      => __( 'Border Radius', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator' => 'before',
				'default'    => [
					'top' => 0,
					'bottom' => 0,
					'left' => 0,
					'right' => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-advanced-tabs-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'tab_padding',
			[
				'label'      => __( 'Padding', 'power-pack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'top' => 10,
					'bottom' => 10,
					'left' => 10,
					'right' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-advanced-tabs-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_section();
	}

		/**
	 * Render tabs widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		$tabs = $this->get_settings_for_display( 'tab_features' );
		$id_int = substr( $this->get_id_int(), 0, 3 );
		$hover_class = $defaultTabNo = $defaultTitle = $defaultContent = '';
		 
		if ( 0 < $settings['default_tab'] && sizeof($tabs) >= $settings['default_tab'] ) {
			$defaultTabNo = $settings['default_tab'];
		} else {
			$defaultTabNo = 1;
		}
        
		$hover_state = $settings['tab_hover_effect'];
        
		if ( 'yes' == $hover_state ) {
			$hover_class = ' at-hover';
		} else {
			$hover_class = ' at-no-hover';
		}
		?>
		<div class="pp-advanced-tabs pp-<?php echo $settings['custom_style']; ?>" role="tablist">
			<div class="pp-advanced-tabs-wrapper <?php echo $settings['type'] . $hover_class;?>">
				<?php
                    foreach ( $tabs as $index => $item ) {

                        $tab_count = $index + 1;

                        if ( $tab_count == $defaultTabNo ) {
                            $defaultTitle = 'at-active';
                        } else {
                            $defaultTitle = '';
                        }

                        $title_text_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tab_features', $index );

                        $this->add_render_attribute( $title_text_setting_key, [
                            'id' => 'pp-advanced-tabs-title-' . $id_int . $tab_count,
                            'class' => [ 'pp-advanced-tabs-title', 'pp-advanced-tabs-desktop-title', $defaultTitle ],
                            'data-tab' => $tab_count,
                            'data-index' => $id_int . $tab_count,
                            'tabindex' => $id_int . $tab_count,
                            'role' => 'tab',
                            'aria-controls' => 'pp-advanced-tabs-content-' . $id_int . $tab_count,
                        ] );

                        if ( 'top' == $settings['icon_position'] || 'left' == $settings['icon_position'] ) { ?>
                            <div <?php echo $this->get_render_attribute_string( $title_text_setting_key ); ?>>
                                <?php if ( $item['icon'] ) { ?>
                                    <i class="<?php echo $item['icon'] . ' pp-advanced-tabs-icon-' . $settings['icon_position']; ?>"></i>
                                <?php } ?>
                                <span><?php echo $item['tab_title']; ?></span>
                                <?php if ( 'style-7' == $settings['custom_style'] || 'style-8' == $settings['custom_style'] ) { ?>
                                    <span class="active-slider-span"></span>
                                <?php }?>
                            </div>
                        <?php } elseif ( 'bottom' == $settings['icon_position'] || 'right' == $settings['icon_position'] ) { ?>
                            <div <?php echo $this->get_render_attribute_string( $title_text_setting_key ); ?>>
                                <span><?php echo $item['tab_title']; ?></span>
                                <?php if ( $item['icon'] ) { ?>
                                    <i class="<?php echo $item['icon'] . ' pp-advanced-tabs-icon-' . $settings['icon_position']; ?>"></i>
                                <?php } ?>
                                <?php if ( 'style-7' == $settings['custom_style'] || 'style-8' == $settings['custom_style'] ) { ?>
                                    <span class="active-slider-span"></span>
                                <?php }?>
                            </div>
                        <?php }
                    } ?>
			</div>
			<div class="pp-advanced-tabs-content-wrapper <?php echo $settings['type']; ?>-content">
				<?php foreach ( $tabs as $index => $item ) :
					$tab_count = $index + 1;
					if ( $tab_count == $defaultTabNo ) {
						$defaultContent = 'at-active-content';
					} else {
						$defaultContent = '';
					}
					$tab_content_setting_key = $this->get_repeater_setting_key( 'content', 'tab_features', $index );

					$this->add_render_attribute( $tab_content_setting_key, [
						'id'              => 'pp-advanced-tabs-content-' . $id_int . $tab_count,
						'class'           => [ 'pp-advanced-tabs-content', 'elementor-clearfix', 'pp-advanced-tabs-' . $item['content_type'], $defaultContent ],
						'data-tab'        => $tab_count,
						'role'            => 'tabpanel',
						'aria-labelledby' => 'pp-advanced-tabs-title-' . $id_int . $tab_count,
					] );

					//$this->add_inline_editing_attributes( $tab_content_setting_key, 'advanced' );
					?>

					<div class="pp-advanced-tabs-title pp-tab-responsive<?php echo $hover_class;?>" data-index ="<?php echo $id_int . $tab_count;?>">
						<div class="pp-advanced-tabs-title-inner">
							<i class="<?php echo $item['icon'] . ' pp-advanced-tabs-icon-' . $settings['icon_position']; ?>"></i>
							<span><?php echo $item['tab_title']; ?></span>
							<i class="pp-toggle-icon pp-tab-open fa"></i>
						</div>
					</div>
					<div <?php echo $this->get_render_attribute_string( $tab_content_setting_key ); ?>>
						<?php
                            if ( 'tab_content' == $item['content_type'] ) {
                                
                                echo $this->parse_text_editor( $item['content'] );
                                
                            } elseif ( 'tab_photo' == $item['content_type'] && $item['image']['url'] != '' ) {
                                
                                echo Group_Control_Image_Size::get_attachment_image_html( $item, 'image', 'image' );
                                
                            } elseif ( 'tab_video' == $item['content_type'] ) {
                                
                                echo $this->parse_text_editor( $item['link_video'] );
                                
                            } elseif ( $item['content_type'] == 'section' && !empty( $item['saved_section'] ) ) {

                                echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $item['saved_section'] );

                            } elseif ( $item['content_type'] == 'template' && !empty( $item['templates'] ) ) {

                                echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $item['templates'] );

                            } elseif ( $item['content_type'] == 'widget' && !empty( $item['saved_widget'] ) ) {

                                echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $item['saved_widget'] );

                            }
                        ?>
					</div>
					<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 *  Get Saved Widgets
	 *
	 *  @param string $type Type.
	 *  
	 *  @return string
	 */
	public function get_page_template_options( $type = '' ) {

		$page_templates = pp_get_page_templates( $type );

		$options[-1]   = __( 'Select', 'power-pack' );

		if ( count( $page_templates ) ) {
			foreach ( $page_templates as $id => $name ) {
				$options[ $id ] = $name;
			}
		} else {
			$options['no_template'] = __( 'No saved templates found!', 'power-pack' );
		}

		return $options;
	}
    
	/**
	 * Render Advanced Tabs widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
	}
}