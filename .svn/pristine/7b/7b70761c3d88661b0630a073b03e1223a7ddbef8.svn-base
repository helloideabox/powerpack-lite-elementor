<?php
namespace PowerpackElementsLite\Modules\Logos\Widgets;

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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Logo Grid Widget
 */
class Logo_Grid extends Powerpack_Widget {
    
    /**
	 * Retrieve logo grid widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-logo-grid';
    }

    /**
	 * Retrieve logo grid widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Logo Grid', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the logo grid widget belongs to.
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
	 * Retrieve logo grid widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-logo-grid power-pack-admin-icon';
    }

    /**
	 * Register logo grid widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section(
            'section_logo_grid',
            [
                'label'             => __( 'Logo Grid', 'power-pack' ),
            ]
        );
        
        $repeater = new Repeater();
        
        $repeater->start_controls_tabs( 'items_repeater' );

        $repeater->start_controls_tab( 'tab_content', [ 'label' => __( 'Content', 'power-pack' ) ] );
        
            $repeater->add_control(
                'logo_image',
                [
                    'label'             => __( 'Upload Logo Image', 'power-pack' ),
                    'type'              => Controls_Manager::MEDIA,
                    'dynamic'           => [
                        'active'   => true,
                    ],
                    'default'           => [
                        'url' => Utils::get_placeholder_image_src(),
                    ],
                ]
            );

            $repeater->add_control(
                'title',
                [
                    'label'             => __( 'Title', 'power-pack' ),
                    'type'              => Controls_Manager::TEXT,
                    'dynamic'           => [
                        'active'   => true,
                    ],
                ]
            );

            $repeater->add_control(
                'link',
                [
                    'label'             => __( 'Link', 'power-pack' ),
                    'type'              => Controls_Manager::URL,
                    'dynamic'           => [
                        'active'   => true,
                    ],
                    'placeholder'       => 'https://www.your-link.com',
                    'default'           => [
                        'url' => '',
                    ],
                ]
            );
        
        $repeater->end_controls_tab();
        
        $repeater->start_controls_tab( 'tab_style', [ 'label' => __( 'Style', 'power-pack' ) ] );
        
            $repeater->add_control(
                'custom_style',
                [
                    'label'             => __( 'Custom Style', 'power-pack' ),
                    'type'              => Controls_Manager::SWITCHER,
                    'description'       => __( 'Add custom styles which will affect only this item', 'power-pack' ),
                    'default'           => '',
                    'label_on'          => __( 'On', 'power-pack' ),
                    'label_off'         => __( 'Off', 'power-pack' ),
                    'return_value'      => 'yes',
                ]
            );

            $repeater->add_control(
                'custom_logo_wrapper_bg',
                [
                    'label'              => __( 'Background Color', 'power-pack' ),
                    'type'               => Controls_Manager::COLOR,
                    'default'            => '',
                    'selectors'          => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.pp-logo-grid-item-custom' => 'background-color: {{VALUE}}',
                    ],
                    'condition'          => [
                        'custom_style'   => 'yes',
                    ],
                ]
            );

            $repeater->add_control(
                'custom_logo_wrapper_border_color',
                [
                    'label'              => __( 'Border Color', 'power-pack' ),
                    'type'               => Controls_Manager::COLOR,
                    'default'            => '',
                    'selectors'          => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.pp-logo-grid-item-custom' => 'border-color: {{VALUE}}',
                    ],
                    'condition'          => [
                        'custom_style'   => 'yes',
                    ],
                ]
            );

            $repeater->add_control(
                'custom_logo_border_width',
                [
                    'label'                 => __( 'Border Width', 'power-pack' ),
                    'type'                  => Controls_Manager::SLIDER,
                    'size_units'            => [ 'px' ],
                    'range'                 => [
                        'px' => [
                            'min' => 0,
                            'max' => 20,
                        ],
                    ],
                    'selectors'             => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.pp-logo-grid-item-custom' => 'border-width: {{SIZE}}{{UNIT}};',
                    ],
                    'condition'          => [
                        'custom_style'   => 'yes',
                    ],
                ]
            );
        
        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'pp_logos',
            [
                'label' 	=> __( 'Add Logos', 'power-pack' ),
                'type' 		=> Controls_Manager::REPEATER,
                'default' 	=> [
                    [
                        'logo_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'logo_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                    [
                        'logo_image' => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                    ],
                ],
                'fields' 		=> array_values( $repeater->get_controls() ),
                'title_field' 	=> __( 'Logo Image', 'power-pack' )
            ]
        );
        
        $this->add_control(
            'title_html_tag',
            [
                'label'                => __( 'Title HTML Tag', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'h4',
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
        
        $this->add_responsive_control(
            'columns',
            [    
                'label'                 => __( 'Columns', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => '3',
                'tablet_default'        => '2',
                'mobile_default'        => '1',
                'options'               => [
                 '1' => '1',
                 '2' => '2',
                 '3' => '3',
                 '4' => '4',
                 '5' => '5',
                 '6' => '6',
                ],
                'prefix_class'          => 'elementor-grid%s-',
                'frontend_available'    => true,
            ]
        );

        $this->add_responsive_control(
            'logos_spacing',
            [
                'label'                 => __( 'Logos Gap', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'size_units'            => [ 'px' ],
                'range'                 => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'             => [
                    '(desktop){{WRAPPER}} .pp-grid-item-wrap' 		=> 'width: calc( ( 100% - (({{columns.SIZE}} - 1) * {{SIZE}}{{UNIT}}) ) / {{columns.SIZE}} ); margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}} .pp-grid-item-wrap' 		=> 'width: calc( ( 100% - (({{columns_tablet.SIZE}} - 1) * {{SIZE}}{{UNIT}}) ) / {{columns_tablet.SIZE}} ); margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}} .pp-grid-item-wrap' 		=> 'width: calc( ( 100% - (({{columns_mobile.SIZE}} - 1) * {{SIZE}}{{UNIT}}) ) / {{columns_mobile.SIZE}} ); margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
			'logos_vertical_align',
			[
				'label'                 => __( 'Vertical Align', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
				'default'               => 'top',
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
					'{{WRAPPER}} .pp-logo-grid .pp-grid-item-wrap' => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
			]
		);
        
        $this->add_control(
			'logos_horizontal_align',
			[
				'label'                 => __( 'Horizontal Align', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'           => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'            => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'               => 'center',
                'selectors_dictionary'  => [
					'left'     => 'flex-start',
					'center'   => 'center',
					'right'    => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-logo-grid .pp-grid-item-wrap' => 'justify-content: {{VALUE}};',
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
				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/logo-grid/logo-grid-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			]
		);

		$this->end_controls_section();
		
		/*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/


        $this->start_controls_section(
            'section_logos_style',
            [
                'label'                 => __( 'Logos', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_logos_style' );

        $this->start_controls_tab(
            'tab_logos_normal',
            [
                'label'             => __( 'Normal', 'power-pack' ),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'logo_bg',
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'none','classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-grid-item-wrap',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'logo_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-grid-item-wrap',
			]
		);

		$this->add_control(
			'logo_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-grid-item-wrap, {{WRAPPER}} .pp-grid-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-grid-item-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'grayscale_normal',
            [
                'label'             => __( 'Grayscale', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'opacity_normal',
            [
                'label'             => __( 'Opacity', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.1,
                    ],
                ],
				'selectors'         => [
					'{{WRAPPER}} .pp-grid-item img' => 'opacity: {{SIZE}};',
				],
            ]
        );
        
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'pp_logo_box_shadow_normal',
				'selector'              => '{{WRAPPER}} .pp-grid-item-wrap',
				'separator'             => 'before',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_logos_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'logos_bg_hover',
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'none','classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-grid-item-wrap:hover',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'logo_border_hover',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-grid-item-wrap:hover',
			]
		);
        
        $this->add_responsive_control(
            'translate',
            [
                'label'                 => __( 'Slide', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -40,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-grid-item-wrap:hover' => 'transform:translateY({{SIZE}}{{UNIT}})',
                ],
            ]
        );
        
        $this->add_control(
            'grayscale_hover',
            [
                'label'             => __( 'Grayscale', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'opacity_hover',
            [
                'label'             => __( 'Opacity', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.1,
                    ],
                ],
				'selectors'         => [
					'{{WRAPPER}} .pp-grid-item:hover img' => 'opacity: {{SIZE}};',
				],
            ]
        );
        
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'pp_logo_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-grid-item-wrap:hover',
				'separator'             => 'before',
			]
		);

        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'section_logo_title_style',
            [
                'label'                 => __( 'Title', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'              => __( 'Color', 'power-pack' ),
                'type'               => Controls_Manager::COLOR,
                'default'            => '',
                'selectors'          => [
                    '{{WRAPPER}} .pp-logo-grid-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_spacing',
            [
                'label'                 => __( 'Margin Top', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'size_units'            => [ 'px' ],
                'range'                 => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-logo-grid-title' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-logo-grid-title',
            ]
        );
        
        $this->end_controls_section();
    }

    /**
	 * Render logo grid widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $i = 1;
        
        $this->add_render_attribute( 'logo-grid', 'class', 'pp-logo-grid pp-elementor-grid clearfix' );

        if ( $settings['grayscale_normal'] == 'yes' ) {
            $this->add_render_attribute( 'logo-grid', 'class', 'grayscale-normal' );
        }

        if ( $settings['grayscale_hover'] == 'yes' ) {
            $this->add_render_attribute( 'logo-grid', 'class', 'grayscale-hover' );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'logo-grid' ); ?>>
            <?php
                foreach ( $settings['pp_logos'] as $item ) :
                    if ( ! empty( $item['logo_image']['url'] ) ) {
                        
                        $this->add_render_attribute( 'logo-grid-item-wrap-' . $i, 'class', 'pp-grid-item-wrap' );
                        $this->add_render_attribute( 'logo-grid-item-wrap-' . $i, 'class', 'elementor-repeater-item-' . esc_attr( $item['_id'] ) );
                        
                        $this->add_render_attribute( 'logo-grid-item-' . $i, 'class', 'pp-grid-item' );

                        if ( $item['custom_style'] == 'yes' ) {
                            $this->add_render_attribute( 'logo-grid-item-wrap-' . $i, 'class', 'pp-logo-grid-item-custom' );
                        }
        
                        $this->add_render_attribute( 'title' . $i, 'class', 'pp-logo-grid-title' );
                        ?>
                        <div <?php echo $this->get_render_attribute_string( 'logo-grid-item-wrap-' . $i ); ?>>
                            <div <?php echo $this->get_render_attribute_string( 'logo-grid-item-' . $i ); ?>>
                                <?php
                                    if ( ! empty( $item['link']['url'] ) ) {
                                        
                                        $this->add_render_attribute( 'logo-link' . $i, 'href', esc_url( $item['link']['url'] ) );

                                        if ( $item['link']['is_external'] ) {
                                            $this->add_render_attribute( 'logo-link' . $i, 'target', '_blank' );
                                        }

                                        if ( $item['link']['nofollow'] ) {
                                            $this->add_render_attribute( 'logo-link' . $i, 'rel', 'nofollow' );
                                        }
                                    }
                                                                  
                                    if ( ! empty( $item['link']['url'] ) ) {
                                        echo '<a ' . $this->get_render_attribute_string( 'logo-link' . $i ) . '>';
                                    }

                                    echo '<img src="' . esc_url( $item['logo_image']['url'] ) . '">';
                                    if ( ! empty( $item['link']['url'] ) ) {
                                        echo '</a>';
                                    }
                                ?>
                            </div>
                            <?php
                                if ( ! empty( $item['title'] ) ) {
                                    printf( '<%1$s %2$s>', $settings['title_html_tag'], $this->get_render_attribute_string( 'title' . $i ) );
                                        if ( ! empty( $item['link']['url'] ) ) {
                                            echo '<a ' . $this->get_render_attribute_string( 'logo-link' . $i ) . '>';
                                        }
                                        echo $item['title'];
                                        if ( ! empty( $item['link']['url'] ) ) {
                                            echo '</a>';
                                        }
                                    printf( '</%1$s>', $settings['title_html_tag'] );
                                }
                            ?>
                        </div>
                        <?php
                    }
                    $i++;
                endforeach;
            ?>
        </div>
    <?php
    }

    /**
	 * Render logo grid widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <#
            var i = 1;
        #>
        <div class="pp-logo-grid clearfix">
            <# _.each( settings.pp_logos, function( item ) { #>
                <# if ( item.logo_image.url != '' ) { #>
                    <#
                        if ( item.custom_style == 'yes' ) {
                            var custom_style_class = 'pp-logo-grid-item-custom';
                        }
                        else {
                            var custom_style_class = '';
                        }
                    #>
                    <div class="pp-grid-item-wrap elementor-repeater-item-{{ item._id }} {{ custom_style_class }}">
                        <div class="pp-grid-item">
                            <# if ( item.link && item.link.url ) { #>
                                <a href="{{ item.link.url }}">
                            <# } #>
                                
                            <img src="{{ item.logo_image.url }}">
                                
                            <# if ( item.link && item.link.url ) { #>
                                </a>
                            <# } #>
                        </div>
                        <#
                            if ( item.title != '' ) {
                                var title = item.title;

                                view.addRenderAttribute( 'title' + i, 'class', 'pp-logo-grid-title' );

                                if ( item.link && item.link.url ) {
                                    title = '<a href="' + item.link.url + '">' + item.title + '</a>';
                                }

                                var title_html = '<' + settings.title_html_tag  + ' ' + view.getRenderAttributeString( 'title' + i ) + '>' + title + '</' + settings.title_html_tag + '>';

                                print( title_html );
                            }
                        #>
                    </div>
                <# } #>
            <# i++ } ); #>
        </div>
        <?php
    }
}