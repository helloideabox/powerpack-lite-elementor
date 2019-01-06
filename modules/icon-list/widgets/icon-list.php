<?php
namespace PowerpackElementsLite\Modules\IconList\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

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
 * Icon List Widget
 */
class Icon_List extends Powerpack_Widget {
    
    /**
	 * Retrieve icon list widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-icon-list';
    }

    /**
	 * Retrieve icon list widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Icon List', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the icon list widget belongs to.
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
	 * Retrieve icon list widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-icon-list power-pack-admin-icon';
    }

    /**
	 * Register icon list widget controls.
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
         * Content Tab: List
         */
        $this->start_controls_section(
            'section_list',
            [
                'label'                 => __( 'List', 'power-pack' ),
            ]
        );

		$this->add_control(
			'view',
			[
				'label'                 => __( 'Layout', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'traditional',
				'options'               => [
					'traditional'  => [
						'title'    => __( 'Default', 'power-pack' ),
						'icon'     => 'eicon-editor-list-ul',
					],
					'inline'       => [
						'title'    => __( 'Inline', 'power-pack' ),
						'icon'     => 'eicon-ellipsis-h',
					],
				],
				'render_type'           => 'template',
				'prefix_class'          => 'pp-icon-list-',
				'label_block'           => false,
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
						'label'       => __( 'Text', 'power-pack' ),
						'label_block' => true,
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'  => true,
                        ],
                        'default'     => __('List Item #1','power-pack')
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
                            'number' => [
                                'title' => esc_html__( 'Number', 'power-pack' ),
                                'icon'  => 'fa fa-hashtag',
                            ],
                        ],
                        'default'       => 'icon',
					],
                    [
						'name'        => 'list_icon',
						'label'       => __( 'Icon', 'power-pack' ),
						'label_block' => true,
						'type'        => Controls_Manager::ICON,
				        'default'     => 'fa fa-check',
				        'condition'     => [
                            'pp_icon_type' => 'icon',
                        ],
					],
                    [
						'name'        => 'list_image',
						'label'       => __( 'Image', 'power-pack' ),
						'label_block' => true,
						'type'        => Controls_Manager::MEDIA,
				        'default'     => [
                            'url' => Utils::get_placeholder_image_src(),
                         ],
				        'condition'   => [
                            'pp_icon_type' => 'image',
                        ],
					],
					[
						'name'        => 'link',
						'label'       => __( 'Link', 'power-pack' ),
						'type'        => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'http://your-link.com', 'power-pack' ),
					],
				],
				'title_field'       => '<i class="{{ list_icon }}" aria-hidden="true"></i> {{{ text }}}',
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
			
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'items_background',
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-list-items li',
            ]
        );

		$this->add_control(
			'items_spacing',
			[
				'label'                 => __( 'List Items Gap', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'max' => 50,
					],
				],
				'separator'             => 'before',
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items:not(.pp-inline-items) li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-list-items.pp-inline-items li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'list_items_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'list_items_alignment',
			[
				'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}}.pp-icon-list-traditional .pp-list-items li, {{WRAPPER}}.pp-icon-list-inline .pp-list-items' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'right' => 'flex-end',
				],
			]
		);

		$this->add_control(
			'divider',
			[
				'label'                 => __( 'Divider', 'power-pack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'label_off'             => __( 'Off', 'power-pack' ),
				'label_on'              => __( 'On', 'power-pack' ),
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label'                 => __( 'Style', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'solid'    => __( 'Solid', 'power-pack' ),
					'double'   => __( 'Double', 'power-pack' ),
					'dotted'   => __( 'Dotted', 'power-pack' ),
					'dashed'   => __( 'Dashed', 'power-pack' ),
					'groove'   => __( 'Groove', 'power-pack' ),
					'ridge'    => __( 'Ridge', 'power-pack' ),
				],
				'default'               => 'solid',
				'condition'             => [
					'divider' => 'yes',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items:not(.pp-inline-items) li:not(:last-child)' => 'border-bottom-style: {{VALUE}};',
					'{{WRAPPER}} .pp-list-items.pp-inline-items li:not(:last-child)' => 'border-right-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'divider_weight',
			[
				'label'                 => __( 'Weight', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size' => 1,
				],
				'range'                 => [
					'px'   => [
						'min' => 1,
						'max' => 10,
					],
				],
				'condition'             => [
					'divider' => 'yes',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items:not(.pp-inline-items) li:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-list-items.pp-inline-items li:not(:last-child)' => 'border-right-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'                 => __( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#ddd',
				'scheme'                => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_3,
				],
				'condition'             => [
					'divider'  => 'yes',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items:not(.pp-inline-items) li:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .pp-list-items.pp-inline-items li:not(:last-child)' => 'border-right-color: {{VALUE}};',
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

		$this->add_control(
			'icon_position',
			[
                'label'                 => __( 'Position', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
                'toggle'                => false,
                'default'               => 'left',
                'options'               => [
                    'left'      => [
                        'title' => __( 'Left', 'power-pack' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right'     => [
                        'title' => __( 'Right', 'power-pack' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
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
					'{{WRAPPER}} .pp-list-items .pp-icon-list-icon' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper' => 'background: {{VALUE}};',
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
					'{{WRAPPER}} .pp-list-items .pp-icon-list-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-list-items .pp-icon-list-image img' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .pp-list-items .icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-list-items .icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
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
				'selector'              => '{{WRAPPER}} .pp-list-items .pp-icon-wrapper',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper, {{WRAPPER}} .pp-list-items .pp-icon-list-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper:hover .pp-icon-list-icon' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper:hover' => 'background: {{VALUE}};',
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
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper:hover' => 'border-color: {{VALUE}};',
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
         * Style Tab: Text
         */
        $this->start_controls_section(
            'section_text_style',
            [
                'label'                 => __( 'Text', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'text_color',
			[
				'label'                 => __( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-icon-list-text' => 'color: {{VALUE}};',
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
                'name'                  => 'text_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-icon-list-text',
            ]
        );
        
        $this->end_controls_section();

    }

    /**
	 * Render icon list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'icon-list', 'class', 'pp-list-items' );
        
        $this->add_render_attribute( 'icon', 'class', 'pp-icon-list-icon' );
        
        $this->add_render_attribute( 'icon-wrap', 'class', 'pp-icon-wrapper' );
        
        if ( 'inline' === $settings['view'] ) {
			$this->add_render_attribute( 'icon-list', 'class', 'pp-inline-items' );
		}
        
        $i = 1;
        ?>
        <div class="pp-list-container">
            <ul <?php echo $this->get_render_attribute_string( 'icon-list' ); ?>>
                <?php foreach ( $settings['list_items'] as $index => $item ) : ?>
                    <?php if ( $item['text'] ) { ?>
                        <li>
                            <?php
                                $text_key = $this->get_repeater_setting_key( 'text', 'list_items', $index );
                                $this->add_render_attribute( $text_key, 'class', 'pp-icon-list-text' );
                                $this->add_inline_editing_attributes( $text_key, 'none' );

                                if ( ! empty( $item['link']['url'] ) ) {
                                    $link_key = 'link_' . $i;

                                    $this->add_render_attribute( $link_key, 'href', esc_url( $item['link']['url'] ) );

                                    if ( $item['link']['is_external'] ) {
                                        $this->add_render_attribute( $link_key, 'target', '_blank' );
                                    }

                                    if ( $item['link']['nofollow'] ) {
                                        $this->add_render_attribute( $link_key, 'rel', 'nofollow' );
                                    }

                                    echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
                                }
                                if ( $item['pp_icon_type'] != 'none' ) {
                                    $icon_key = 'icon_' . $i;
                                    $this->add_render_attribute( $icon_key, 'class', 'pp-icon-wrapper' );
                                    if ( $settings['icon_position'] == 'right' ) {
                                        $this->add_render_attribute( $icon_key, 'class', 'icon-right' );
                                    }
                                    else {
                                        $this->add_render_attribute( $icon_key, 'class', 'icon-left' );
                                    }
                                    
                                    if ( $settings['icon_hover_animation'] != '' ) {
                                        $icon_animation = 'elementor-animation-' . $settings['icon_hover_animation'];
                                    } else {
                                        $icon_animation = '';
                                    }
                                    ?>
                                    <span <?php echo $this->get_render_attribute_string( $icon_key ); ?>>
                                        <?php
                                            if ( $item['pp_icon_type'] == 'icon' ) {
                                                printf( '<span class="pp-icon-list-icon %1$s %2$s"></span>', esc_attr( $item['list_icon'] ), $icon_animation );
                                            } elseif ( $item['pp_icon_type'] == 'image' ) {
                                                printf( '<span class="pp-icon-list-image %2$s"><img src="%1$s"></span>', esc_url( $item['list_image']['url'] ), $icon_animation );
                                            } elseif ( $item['pp_icon_type'] == 'number' ) {
                                                printf( '<span class="pp-icon-list-icon %2$s">%1$s</span>', $i, $icon_animation );
                                            }
                                        ?>
                                    </span>
                                    <?php
                                }

                                printf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( $text_key ), $item['text'] );

                                if ( ! empty( $item['link']['url'] ) ) {
                                    echo '</a>';
                                }
                            ?>
                        </li>
                    <?php } ?>
                <?php $i++; endforeach; ?>
            </ul>
        </div>
        <?php
    }

    /**
	 * Render icon list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <div class="pp-list-container">
            <#
                if ( settings.view == 'inline' ) {
                    var list_class = 'pp-inline-items';
                } else {
                    var list_class = '';
                }
            #>
            <ul class="pp-list-items {{ list_class }}">
                <# var i = 1; #>
                <# _.each( settings.list_items, function( item ) { #>
                    <# if ( item.text != '' ) { #>
                        <li>
                            <# if ( item.link && item.link.url ) { #>
                                <a href="{{ item.link.url }}">
                            <# } #>
                            <# if ( item.pp_icon_type != 'none' ) { #>
                                <#
                                    if ( settings.icon_position == 'right' ) {
                                        var icon_class = 'icon-right';
                                    } else {
                                        var icon_class = 'icon-left';
                                    }
                                #>
                                <span class="pp-icon-wrapper {{ icon_class }}">
                                    <# if ( item.pp_icon_type == 'icon' ) { #>
                                        <span class="pp-icon-list-icon elementor-animation-{{ settings.icon_hover_animation }} {{ item.list_icon }}" aria-hidden="true"></span>
                                    <# } else if ( item.pp_icon_type == 'image' ) { #>
                                        <span class="pp-icon-list-image elementor-animation-{{ settings.icon_hover_animation }}">
                                            <img src="{{ item.list_image.url }}">
                                        </span>
                                    <# } else if ( item.pp_icon_type == 'number' ) { #>
                                        <span class="pp-icon-list-icon elementor-animation-{{ settings.icon_hover_animation }}">
                                            {{ i }}
                                        </span>
                                    <# } #>
                                </span>
                            <# } #>

                            <#
                                var text = item.text;

                                view.addRenderAttribute( 'list_items.' + (i - 1) + '.text', 'class', 'pp-icon-list-text' );

                                view.addInlineEditingAttributes( 'list_items.' + (i - 1) + '.text' );

                                var text_html = '<span' + ' ' + view.getRenderAttributeString( 'list_items.' + (i - 1) + '.text' ) + '>' + text + '</span>';

                                print( text_html );
                            #>

                            <# if ( item.link && item.link.url ) { #>
                                </a>
                            <# } #>
                        </li>
                    <# } #>
                <# i++ } ); #>
            </ul>
        </div>
        <?php
    }
}

//Plugin::instance()->widgets_manager->register_widget_type( new PP_List_Widget() );