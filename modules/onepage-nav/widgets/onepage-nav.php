<?php
namespace PowerpackElements\Modules\OnepageNav\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * One Page Navigation Widget
 */
class Onepage_Nav extends Powerpack_Widget {

    /**
	 * Retrieve one page navigation widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-one-page-nav';
    }

    /**
	 * Retrieve one page navigation widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'One Page Navigation', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the one page navigation widget belongs to.
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
	 * Retrieve one page navigation widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-page-navigation power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the one page navigation widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'jquery-powerpack-dot-nav',
            'powerpack-frontend'
        ];
    }

    /**
	 * Register one page navigation widget controls.
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
         * Content Tab: Navigation Dots
         */
        $this->start_controls_section(
            'section_nav_dots',
            [
                'label'                 => __( 'Navigation Dots', 'power-pack' ),
            ]
        );
        
        $repeater = new Repeater();

        $repeater->add_control(
            'section_title',
            [
                'label'                 => __( 'Section Title', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => __( 'Section Title', 'power-pack' ),
            ]
        );

        $repeater->add_control(
            'section_id',
            [
                'label'                 => __( 'Section ID', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => '',
            ]
        );
        
        $repeater->add_control(
            'dot_icon',
            [
                'label'                 => __( 'Navigation Dot', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
				'default'               => 'fa fa-circle',
            ]
        );

        $this->add_control(
            'nav_dots',
            [
                'label'                 => '',
                'type'                  => Controls_Manager::REPEATER,
                'default'               => [
                    [
                        'section_title'   => __( 'Section #1', 'power-pack' ),
						'section_id'      => 'section-1',
						'dot_icon'        => 'fa fa-circle',
                    ],
                    [
                        'section_title'   => __( 'Section #2', 'power-pack' ),
						'section_id'      => 'section-2',
						'dot_icon'        => 'fa fa-circle',
                    ],
                    [
                        'section_title'   => __( 'Section #3', 'power-pack' ),
						'section_id'      => 'section-3',
						'dot_icon'        => 'fa fa-circle',
                    ],
                ],
                'fields'                => array_values( $repeater->get_controls() ),
                'title_field'           => '{{{ section_title }}}',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Settings
         */
        $this->start_controls_section(
            'section_onepage_nav_settings',
            [
                'label'                 => __( 'Settings', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'nav_tooltip',
            [
                'label'                 => __( 'Tooltip', 'power-pack' ),
                'description'           => __( 'Show tooltip on hover', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'tooltip_arrow',
            [
                'label'                 => __( 'Tooltip Arrow', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'yes',
                'condition'             => [
                    'nav_tooltip'   => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'scroll_wheel',
            [
                'label'                 => __( 'Scroll Wheel', 'power-pack' ),
                'description'           => __( 'Use mouse wheel to navigate from one row to another', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'off',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'on',
            ]
        );
        
        $this->add_control(
            'scroll_touch',
            [
                'label'                 => __( 'Touch Swipe', 'power-pack' ),
                'description'           => __( 'Use touch swipe to navigate from one row to another in mobile devices', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'off',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'on',
                'condition'             => [
                    'scroll_wheel'   => 'on',
                ],
            ]
        );
        
        $this->add_control(
            'scroll_keys',
            [
                'label'                 => __( 'Scroll Keys', 'power-pack' ),
                'description'           => __( 'Use UP and DOWN arrow keys to navigate from one row to another', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'off',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'on',
            ]
        );
        
        $this->add_control(
            'top_offset',
            [
                'label'                 => __( 'Row Top Offset', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => '0' ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 300,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
            ]
        );

        $this->add_control(
            'scrolling_speed',
            [
                'label'                 => __( 'Scrolling Speed', 'power-pack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => '700',
            ]
        );
        
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Navigation Box
         */
        $this->start_controls_section(
            'section_nav_box_style',
            [
                'label'                 => __( 'Navigation Box', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'heading_alignment',
            [
                'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
                    'top'          => [
						'title'    => __( 'Top', 'power-pack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'bottom'       => [
						'title'    => __( 'Bottom', 'power-pack' ),
						'icon'     => 'eicon-v-align-bottom',
					],
					'left'         => [
                        'title'    => __( 'Left', 'power-pack' ),
                        'icon' 	   => 'eicon-h-align-left',
                    ],
                    'right' 	   => [
                        'title'    => __( 'Right', 'power-pack' ),
                        'icon' 	   => 'eicon-h-align-right',
                    ],
				],
				'default'               => 'right',
                'prefix_class'          => 'nav-align-',
                'frontend_available'    => true,
				'selectors'             => [
					'{{WRAPPER}} .pp-caldera-form-heading' => 'text-align: {{VALUE}};',
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'nav_container_background',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .pp-one-page-nav',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'nav_container_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-one-page-nav'
			]
		);

		$this->add_control(
			'nav_container_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-one-page-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_container_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-one-page-nav-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'nav_container_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-one-page-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'nav_container_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-one-page-nav',
				'separator'             => 'before',
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Navigation Dots
         */
        $this->start_controls_section(
            'section_dots_style',
            [
                'label'                 => __( 'Navigation Dots', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'dots_size',
            [
                'label'                 => __( 'Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => '10' ],
                'range'                 => [
                    'px' => [
                        'min'   => 5,
                        'max'   => 60,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-nav-dot' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dots_spacing',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => '10' ],
                'range'                 => [
                    'px' => [
                        'min'   => 2,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}}.nav-align-right .pp-one-page-nav-item, {{WRAPPER}}.nav-align-left .pp-one-page-nav-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.nav-align-top .pp-one-page-nav-item, {{WRAPPER}}.nav-align-bottom .pp-one-page-nav-item' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'dots_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-nav-dot-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'dots_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-nav-dot-wrap',
				'separator'             => 'before',
			]
		);

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'dots_color_normal',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-nav-dot' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'dots_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-nav-dot-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'dots_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-nav-dot-wrap'
			]
		);

		$this->add_control(
			'dots_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-nav-dot-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'dots_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-one-page-nav-item .pp-nav-dot-wrap:hover .pp-nav-dot' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'dots_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-one-page-nav-item .pp-nav-dot-wrap:hover' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-one-page-nav-item .pp-nav-dot-wrap:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_active',
            [
                'label'                 => __( 'Active', 'power-pack' ),
            ]
        );

        $this->add_control(
            'dots_color_active',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-one-page-nav-item.active .pp-nav-dot' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'dots_bg_color_active',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-one-page-nav-item.active .pp-nav-dot-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'dots_border_color_active',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-one-page-nav-item.active .pp-nav-dot-wrap' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Tooltip
         */
        $this->start_controls_section(
            'section_tooltips_style',
            [
                'label'                 => __( 'Tooltip', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'nav_tooltip'  => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tooltip_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-nav-dot-tooltip-content' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .pp-nav-dot-tooltip' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'nav_tooltip'  => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tooltip_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-nav-dot-tooltip-content' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'nav_tooltip'  => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'tooltip_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-nav-dot-tooltip',
                'condition'             => [
                    'nav_tooltip'  => 'yes',
                ],
            ]
        );

		$this->add_responsive_control(
			'tooltip_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-nav-dot-tooltip-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();
        
        $this->add_render_attribute( 'onepage-nav-container', 'class', 'pp-one-page-nav-container' );
        
        $this->add_render_attribute(
            'onepage-nav',
            [
                'class'             => 'pp-one-page-nav',
                'id'                => 'pp-one-page-nav-' . $this->get_id(),
                'data-section-id'   => 'pp-one-page-nav-' . $this->get_id(),
                'data-top-offset'   => $settings['top_offset']['size'],
                'data-scroll-speed' => $settings['scrolling_speed'],
                'data-scroll-wheel' => $settings['scroll_wheel'],
                'data-scroll-touch' => $settings['scroll_touch'],
                'data-scroll-keys'  => $settings['scroll_keys'],
            ]
        );
        
        $this->add_render_attribute( 'tooltip', 'class', 'pp-nav-dot-tooltip' );
        
        if ( $settings['tooltip_arrow'] == 'yes' ) {
            $this->add_render_attribute( 'tooltip', 'class', 'pp-tooltip-arrow' );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'onepage-nav-container' ); ?>>
            <ul <?php echo $this->get_render_attribute_string( 'onepage-nav' ); ?>>
                <?php
                $i = 1;
                foreach ( $settings['nav_dots'] as $index => $dot ) {
                    $pp_section_title = $dot['section_title'];
                    $pp_section_id = $dot['section_id'];
                    $pp_dot_icon = $dot['dot_icon'];

                    if ( $settings['nav_tooltip'] == 'yes' ) {
                        $pp_dot_tooltip = sprintf( '<span %1$s><span class="pp-nav-dot-tooltip-content">%2$s</span></span>', $this->get_render_attribute_string( 'tooltip' ), $pp_section_title );
                    } else {
                        $pp_dot_tooltip = '';
                    }

                    printf( '<li class="pp-one-page-nav-item">%1$s<a href="#" data-row-id="%2$s"><span class="pp-nav-dot-wrap"><span class="pp-nav-dot %3$s"></span></span></a></li>', $pp_dot_tooltip, $pp_section_id, $pp_dot_icon );

                    $i++;
                }
                ?>
            </ul>
        </div>
        <?php
    }

    /**
	 * Render one page navigation widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
    }
}