<?php
namespace PowerpackElements\Modules\Toggle\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Toggle Widget
 */
class Toggle extends Powerpack_Widget {
    
    /**
	 * Retrieve toggle widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-toggle';
    }

    /**
	 * Retrieve toggle widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Toggle', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the toggle widget belongs to.
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
	 * Retrieve toggle widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-content-toggle power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the toggle widget depended on.
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
	 * Register toggle widget controls.
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
         * Content Tab: Primary
         */
        $this->start_controls_section(
            'section_primary',
            [
                'label'                 => __( 'Primary', 'power-pack' ),
            ]
        );

        $this->add_control(
            'primary_label',
            [
                'label'                 => __( 'Label', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => __( 'Annual', 'power-pack' ),
            ]
        );

        $this->add_control(
            'primary_content_type',
            [
                'label'                 => __( 'Content Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                    'image'         => __( 'Image', 'power-pack' ),
                    'content'       => __( 'Content', 'power-pack' ),
                    'template'      => __( 'Saved Templates', 'power-pack' ),
                ],
                'default'               => 'content',
            ]
        );

        $this->add_control(
            'primary_templates',
            [
                'label'                 => __( 'Choose Template', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => pp_get_page_templates(),
				'condition'             => [
					'primary_content_type'      => 'template',
				],
            ]
        );

        $this->add_control(
            'primary_content',
            [
                'label'                 => __( 'Content', 'power-pack' ),
                'type'                  => Controls_Manager::WYSIWYG,
                'default'               => __( 'Primary Content', 'power-pack' ),
				'condition'             => [
					'primary_content_type'      => 'content',
				],
            ]
        );

		$this->add_control(
			'primary_image',
			[
				'label'                 => __( 'Image', 'power-pack' ),
				'type'                  => Controls_Manager::MEDIA,
				'default'               => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'             => [
					'primary_content_type'      => 'image',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Content Tab: Secondary
         */
        $this->start_controls_section(
            'section_secondary',
            [
                'label'                 => __( 'Secondary', 'power-pack' ),
            ]
        );

        $this->add_control(
            'secondary_label',
            [
                'label'                 => __( 'Label', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => __( 'Lifetime', 'power-pack' ),
            ]
        );

        $this->add_control(
            'secondary_content_type',
            [
                'label'                 => __( 'Content Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                    'image'         => __( 'Image', 'power-pack' ),
                    'content'       => __( 'Content', 'power-pack' ),
                    'template'      => __( 'Saved Templates', 'power-pack' ),
                ],
                'default'               => 'content',
            ]
        );

        $this->add_control(
            'secondary_templates',
            [
                'label'                 => __( 'Choose Template', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => pp_get_page_templates(),
				'condition'             => [
					'secondary_content_type'      => 'template',
				],
            ]
        );

        $this->add_control(
            'secondary_content',
            [
                'label'                 => __( 'Content', 'power-pack' ),
                'type'                  => Controls_Manager::WYSIWYG,
                'default'               => __( 'Secondary Content', 'power-pack' ),
				'condition'             => [
					'secondary_content_type'      => 'content',
				],
            ]
        );

		$this->add_control(
			'secondary_image',
			[
				'label'                 => __( 'Image', 'power-pack' ),
				'type'                  => Controls_Manager::MEDIA,
				'default'               => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'             => [
					'secondary_content_type'      => 'image',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Overlay
         */
        $this->start_controls_section(
            'section_toggle_switch_style',
            [
                'label'             => __( 'Switch', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'toggle_switch_alignment',
			[
                'label'                 => __( 'Alignment', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'default'               => 'center',
                'options'               => [
                    'left'          => [
                        'title'     => __( 'Left', 'power-pack' ),
                        'icon'      => 'eicon-h-align-left',
                    ],
                    'center'        => [
                        'title'     => __( 'Center', 'power-pack' ),
                        'icon'      => 'eicon-h-align-center',
                    ],
                    'right'         => [
                        'title'     => __( 'Right', 'power-pack' ),
                        'icon'      => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class'          => 'pp-toggle-',
                'frontend_available'    => true,
			]
		);

        $this->add_control(
            'switch_style',
            [
                'label'                 => __( 'Switch Style', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                    'round'         => __( 'Round', 'power-pack' ),
                    'rectangle'     => __( 'Rectangle', 'power-pack' ),
                ],
                'default'               => 'round',
            ]
        );
        
        $this->add_responsive_control(
			'toggle_switch_size',
			[
				'label'                 => __( 'Switch Size', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 26,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px' ],
				'range'                 => [
					'px'   => [
						'min' => 15,
						'max' => 60,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-toggle-switch-container' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'toggle_switch_spacing',
			[
				'label'                 => __( 'Headings Spacing', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 15,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px'   => [
						'max' => 80,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-toggle-switch-container' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'toggle_switch_gap',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 20,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px'   => [
						'max' => 80,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-toggle-switch-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_switch' );

        $this->start_controls_tab(
            'tab_switch_primary',
            [
                'label'             => __( 'Primary', 'power-pack' ),
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'toggle_switch_primary_background',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .pp-toggle-slider',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'toggle_switch_primary_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-toggle-switch-container',
			]
		);

		$this->add_control(
			'toggle_switch_primary_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-toggle-switch-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_switch_secondary',
            [
                'label'             => __( 'Secondary', 'power-pack' ),
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'toggle_switch_secondary_background',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .pp-toggle-switch-on .pp-toggle-slider',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'toggle_switch_secondary_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-toggle-switch-container.pp-toggle-switch-on',
			]
		);

		$this->add_control(
			'toggle_switch_secondary_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-toggle-switch-container.pp-toggle-switch-on' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->add_control(
            'switch_controller_heading',
            [
                'label'                 => __( 'Controller', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'toggle_controller_background',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .pp-toggle-slider::before',
			]
		);

		$this->add_control(
			'toggle_controller_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-toggle-slider::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Label
         */
        $this->start_controls_section(
            'section_label_style',
            [
                'label'             => __( 'Label', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
			'label_horizontal_position',
			[
				'label'                 => __( 'Position', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'default'               => 'middle',
				'options'               => [
					'top'          => [
						'title'    => __( 'Top', 'power-pack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => __( 'Middle', 'power-pack' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => __( 'Bottom', 'power-pack' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary'  => [
					'top'      => 'flex-start',
                    'middle'   => 'center',
					'bottom'   => 'flex-end',
				],
				'selectors'             => [
                    '{{WRAPPER}} .pp-toggle-switch-inner' => 'align-items: {{VALUE}}',
                ],
			]
		);

        $this->start_controls_tabs( 'tabs_label_style' );

        $this->start_controls_tab(
            'tab_label_primary',
            [
                'label'             => __( 'Primary', 'power-pack' ),
            ]
        );

        $this->add_control(
            'label_text_color_primary',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-primary-toggle-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'label_active_text_color_primary',
            [
                'label'             => __( 'Active Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-primary-toggle-label.active' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'label_typography_primary',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-primary-toggle-label',
				'separator'         => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_label_secondary',
            [
                'label'             => __( 'Secondary', 'power-pack' ),
            ]
        );

        $this->add_control(
            'label_text_color_secondary',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-secondary-toggle-label' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'label_active_text_color_secondary',
            [
                'label'             => __( 'Active Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-secondary-toggle-label.active' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'label_typography_secondary',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-secondary-toggle-label',
				'separator'         => 'before',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Content
         */
        $this->start_controls_section(
            'section_content_style',
            [
                'label'             => __( 'Content', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'content_alignment',
			[
                'label'                 => __( 'Alignment', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'default'               => 'center',
                'options'               => [
                    'left'          => [
                        'title'     => __( 'Left', 'power-pack' ),
                        'icon'      => 'eicon-h-align-left',
                    ],
                    'center'        => [
                        'title'     => __( 'Center', 'power-pack' ),
                        'icon'      => 'eicon-h-align-center',
                    ],
                    'right'         => [
                        'title'     => __( 'Right', 'power-pack' ),
                        'icon'      => 'eicon-h-align-right',
                    ],
                ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-toggle-content-wrap' => 'text-align: {{VALUE}}',
                ],
			]
		);

        $this->add_control(
            'content_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-toggle-content-wrap' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'content_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-toggle-content-wrap',
            ]
        );
        
        $this->end_controls_section();

    }

    /**
	 * Render toggle widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();

        $this->add_render_attribute( 'toggle-container', 'class', 'pp-toggle-container' );

        $this->add_render_attribute( 'toggle-container', 'id', 'pp-toggle-container-' . esc_attr( $this->get_id() ) );

        $this->add_render_attribute( 'toggle-container', 'data-toggle-target', '#pp-toggle-container-' . esc_attr( $this->get_id() ) );
        
        $this->add_render_attribute( 'toggle-switch-wrap', 'class', 'pp-toggle-switch-wrap' );
        
        $this->add_render_attribute( 'toggle-switch-container', 'class', 'pp-toggle-switch-container' );
        
        $this->add_render_attribute( 'toggle-switch-container', 'class', 'pp-toggle-switch-' . $settings['switch_style'] );
        
        $this->add_render_attribute( 'toggle-content-wrap', 'class', 'pp-toggle-content-wrap primary' );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'toggle-container' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'toggle-switch-wrap' ); ?>>
                <div class="pp-toggle-switch-inner">
                    <?php if ( $settings['primary_label'] != '' ) { ?>
                        <div class="pp-primary-toggle-label">
                            <?php echo esc_attr( $settings['primary_label'] ); ?>
                        </div>
                    <?php } ?>
                    <div <?php echo $this->get_render_attribute_string( 'toggle-switch-container' ); ?>>
                        <label class="pp-toggle-switch">
                            <input type="checkbox">
                            <span class="pp-toggle-slider"></span>
                        </label>
                    </div>
                    <?php if ( $settings['secondary_label'] != '' ) { ?>
                        <div class="pp-secondary-toggle-label">
                            <?php echo esc_attr( $settings['secondary_label'] ); ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div <?php echo $this->get_render_attribute_string( 'toggle-content-wrap' ); ?>>
                <div class="pp-toggle-primary-wrap">
                    <?php
                        if ( $settings['primary_content_type'] == 'content' ) {
                            echo $this->parse_text_editor( $settings['primary_content'] );
                        } elseif ( $settings['primary_content_type'] == 'image' ) {
                            $this->add_render_attribute( 'primary-image', 'src', $settings['primary_image']['url'] );
                            $this->add_render_attribute( 'primary-image', 'alt', Control_Media::get_image_alt( $settings['primary_image'] ) );
                            $this->add_render_attribute( 'primary-image', 'title', Control_Media::get_image_title( $settings['primary_image'] ) );

                            printf( '<img %s />', $this->get_render_attribute_string( 'primary-image' ) );
                        } elseif ( $settings['primary_content_type'] == 'template' ) {
                            if ( !empty( $settings['primary_templates'] ) ) {
                                $pp_template_id = $settings['primary_templates'];

                                echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $pp_template_id );
                            }
                        }
                    ?>
                </div>
                <div class="pp-toggle-secondary-wrap">
                    <?php
                        if ( $settings['secondary_content_type'] == 'content' ) {
                            echo $this->parse_text_editor( $settings['secondary_content'] );
                        } elseif ( $settings['secondary_content_type'] == 'image' ) {
                            $this->add_render_attribute( 'secondary-image', 'src', $settings['secondary_image']['url'] );
                            $this->add_render_attribute( 'secondary-image', 'alt', Control_Media::get_image_alt( $settings['secondary_image'] ) );
                            $this->add_render_attribute( 'secondary-image', 'title', Control_Media::get_image_title( $settings['secondary_image'] ) );

                            printf( '<img %s />', $this->get_render_attribute_string( 'secondary-image' ) );
                        } elseif ( $settings['secondary_content_type'] == 'template' ) {
                            if ( !empty( $settings['secondary_templates'] ) ) {
                                $pp_template_id = $settings['secondary_templates'];

                                echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $pp_template_id );
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
	 * Render toggle widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
    }
}