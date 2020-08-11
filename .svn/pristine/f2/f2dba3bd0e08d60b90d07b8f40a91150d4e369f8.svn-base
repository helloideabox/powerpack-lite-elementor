<?php
namespace PowerpackElementsLite\Modules\IconList\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
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
        return __( 'Icon List', 'powerpack' );
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
                'label'                 => __( 'List', 'powerpack' ),
            ]
        );

		$this->add_control(
			'view',
			[
				'label'                 => __( 'Layout', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'traditional',
				'options'               => [
					'traditional'  => [
						'title'    => __( 'Default', 'powerpack' ),
						'icon'     => 'eicon-editor-list-ul',
					],
					'inline'       => [
						'title'    => __( 'Inline', 'powerpack' ),
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
						'text'      => __( 'List Item #1', 'powerpack' ),
                        'icon'		=> __('fa fa-check','powerpack')
					],
                    [
						'text'      => __( 'List Item #2', 'powerpack' ),
                        'icon'		=> __('fa fa-check','powerpack')
					],
				],
				'fields'                => [
					[
						'name'        => 'text',
						'label'       => __( 'Text', 'powerpack' ),
						'label_block' => true,
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'  => true,
                        ],
                        'default'     => __('List Item #1','powerpack')
					],
                    [
						'name'        => 'pp_icon_type',
						'label'       => esc_html__( 'Icon Type', 'powerpack' ),
                        'type'        => Controls_Manager::CHOOSE,
                        'label_block' => false,
                        'options'     => [
                            'none' => [
                                'title' => esc_html__( 'None', 'powerpack' ),
                                'icon'  => 'fa fa-ban',
                            ],
                            'icon' => [
                                'title' => esc_html__( 'Icon', 'powerpack' ),
                                'icon'  => 'fa fa-star',
                            ],
                            'image' => [
                                'title' => esc_html__( 'Image', 'powerpack' ),
                                'icon'  => 'fa fa-picture-o',
                            ],
                            'number' => [
                                'title' => esc_html__( 'Number', 'powerpack' ),
                                'icon'  => 'fa fa-hashtag',
                            ],
                        ],
                        'default'       => 'icon',
					],
					[
						'name'        => 'icon',
						'label'					=> __( 'Icon', 'powerpack' ),
						'type'					=> Controls_Manager::ICONS,
						'label_block'			=> true,
						'default'				=> [
							'value'		=> 'fas fa-check',
							'library'	=> 'fa-solid',
						],
						'fa4compatibility'		=> 'list_icon',
						'condition'             => [
							'pp_icon_type'     => 'icon',
						],
					],
                    [
						'name'        => 'list_image',
						'label'       => __( 'Image', 'powerpack' ),
						'label_block' => true,
						'type'        => Controls_Manager::MEDIA,
                        'dynamic'     => [
                            'active'  => true,
                        ],
				        'default'     => [
                            'url' => Utils::get_placeholder_image_src(),
                         ],
				        'condition'   => [
                            'pp_icon_type' => 'image',
                        ],
					],
					[
						'name'              => 'icon_text',
						'label'             => __( 'Number/Text', 'powerpack' ),
						'label_block'       => false,
						'type'              => Controls_Manager::TEXT,
                        'default'           => '',
				        'condition'         => [
                            'pp_icon_type' => 'number',
                        ],
					],
					[
						'name'        => 'link',
						'label'       => __( 'Link', 'powerpack' ),
						'type'        => Controls_Manager::URL,
						'label_block' => true,
                        'dynamic'     => [
                            'active'  => true,
                        ],
						'placeholder' => __( 'http://your-link.com', 'powerpack' ),
					],
				],
				'title_field'       => '<i class="{{ icon }}" aria-hidden="true"></i> {{{ text }}}',
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'image', // Usage: '{name}_size' and '{name}_custom_dimension', in this case 'thumbnail_size' and 'thumbnail_custom_dimension'.,
                'label'                 => __( 'Image Size', 'powerpack' ),
                'default'               => 'full',
				'separator'             => 'before',
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
				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/icon-list/icon-list-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			]
		);

		$this->end_controls_section();

        /**
         * Style Tab: List
         */
        $this->start_controls_section(
            'section_list_style',
            [
                'label'                 => __( 'List', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
			
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'items_background',
                'label'                 => __( 'Background', 'powerpack' ),
                'types'                 => [ 'classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-list-items li',
            ]
        );

		$this->add_responsive_control(
			'items_spacing',
			[
				'label'                 => __( 'List Items Gap', 'powerpack' ),
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
				'label'                 => __( 'Padding', 'powerpack' ),
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
				'label'                 => __( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'powerpack' ),
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
				'label'                 => __( 'Divider', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'label_off'             => __( 'Off', 'powerpack' ),
				'label_on'              => __( 'On', 'powerpack' ),
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label'                 => __( 'Style', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'solid'    => __( 'Solid', 'powerpack' ),
					'double'   => __( 'Double', 'powerpack' ),
					'dotted'   => __( 'Dotted', 'powerpack' ),
					'dashed'   => __( 'Dashed', 'powerpack' ),
					'groove'   => __( 'Groove', 'powerpack' ),
					'ridge'    => __( 'Ridge', 'powerpack' ),
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
				'label'                 => __( 'Weight', 'powerpack' ),
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
				'label'                 => __( 'Color', 'powerpack' ),
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
                'label'                 => __( 'Icon', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'icon_position',
			[
                'label'                 => __( 'Position', 'powerpack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
                'toggle'                => false,
                'default'               => 'left',
                'options'               => [
                    'left'      => [
                        'title' => __( 'Left', 'powerpack' ),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'right'     => [
                        'title' => __( 'Right', 'powerpack' ),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
				'prefix_class'          => 'pp-icon-',
			]
		);
		
        $this->add_control(
			'icon_vertical_align',
			[
				'label'                 => __( 'Vertical Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
				'default'               => 'middle',
				'options'               => [
					'top'          => [
						'title'    => __( 'Top', 'powerpack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => __( 'Center', 'powerpack' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => __( 'Bottom', 'powerpack' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-list-container .pp-list-items li'   => 'align-items: {{VALUE}};',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_icon_style' );

        $this->start_controls_tab(
            'tab_icon_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
            ]
        );

		$this->add_control(
			'icon_color',
			[
				'label'                 => __( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items .pp-icon-list-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-list-items .pp-icon-list-icon svg' => 'fill: {{VALUE}};',
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
				'label'                 => __( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'                 => __( 'Size', 'powerpack' ),
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

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'                 => __( 'Spacing', 'powerpack' ),
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
					'{{WRAPPER}}.pp-icon-left .pp-list-items .pp-icon-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-icon-right .pp-list-items .pp-icon-wrapper' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'icon_border',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-list-items .pp-icon-wrapper',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
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
				'label'                 => __( 'Padding', 'powerpack' ),
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
                'label'                 => __( 'Hover', 'powerpack' ),
            ]
        );

		$this->add_control(
			'icon_color_hover',
			[
				'label'                 => __( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper:hover .pp-icon-list-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-list-items .pp-icon-wrapper:hover .pp-icon-list-icon svg' => 'fill: {{VALUE}};',
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
				'label'                 => __( 'Background Color', 'powerpack' ),
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
				'label'                 => __( 'Border Color', 'powerpack' ),
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
				'label'                 => __( 'Animation', 'powerpack' ),
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
                'label'                 => __( 'Text', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'text_color',
			[
				'label'                 => __( 'Color', 'powerpack' ),
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
                'label'                 => __( 'Typography', 'powerpack' ),
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
        
        $this->add_render_attribute( [
			'icon-list'	=> [
				'class' => 'pp-list-items'
			],
			'icon'		=> [
				'class' => 'pp-icon-list-icon'
			],
			'icon-wrap' => [
				'class' => 'pp-icon-wrapper'
			]
		] );
        
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
									
									$this->add_link_attributes( $link_key, $item['link'] );

                                    echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
                                }
                                
								$this->render_iconlist_icon( $item, $i );

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
	 * Render info-box carousel icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_iconlist_icon( $item, $i ) {
        $settings = $this->get_settings_for_display();

		$fallback_defaults = [
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		];
		
		$migration_allowed = Icons_Manager::is_migration_allowed();
		
		// add old default
		if ( ! isset( $item['list_icon'] ) && ! $migration_allowed ) {
			$item['list_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
		}

		$migrated = isset( $item['__fa4_migrated']['icon'] );
		$is_new = ! isset( $item['list_icon'] ) && $migration_allowed;
		
		if ( $item['pp_icon_type'] != 'none' ) {
			$icon_key = 'icon_' . $i;
			$this->add_render_attribute( $icon_key, 'class', 'pp-icon-wrapper' );

			if ( $settings['icon_hover_animation'] != '' ) {
				$icon_animation = 'elementor-animation-' . $settings['icon_hover_animation'];
			} else {
				$icon_animation = '';
			}
			?>
			<span <?php echo $this->get_render_attribute_string( $icon_key ); ?>>
				<?php
					if ( $item['pp_icon_type'] == 'icon' ) {
						if ( ! empty( $item['list_icon'] ) || ( ! empty( $item['icon']['value'] ) && $is_new ) ) {
							echo '<span class="pp-icon-list-icon pp-icon ' . $icon_animation . '">';
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] );
							} else { ?>
									<i class="<?php echo esc_attr( $item['list_icon'] ); ?>" aria-hidden="true"></i>
							<?php }
							echo '</span>';
						}
					} elseif ( $item['pp_icon_type'] == 'image' ) {
						$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['list_image']['id'], 'image', $settings );

						if ( $image_url ) {
							$image_html = '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $item['list_image'] ) ) . '">';
						} else {
							$image_html = '<img src="' . esc_url( $item['list_image']['url'] ) . '">';
						}

						printf( '<span class="pp-icon-list-image %2$s">%1$s</span>', $image_html, $icon_animation );
					} elseif ( $item['pp_icon_type'] == 'number' ) {
						if ( $item['icon_text'] ) {
							$number = $item['icon_text'];
						} else {
							$number = $i;
						}
						printf( '<span class="pp-icon-list-icon %2$s">%1$s</span>', $number, $icon_animation );
					}
				?>
			</span>
			<?php
		}
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
			   
				var iconsHTML = {},
					migrated = {};
            #>
            <ul class="pp-list-items {{ list_class }}">
                <# var i = 1; #>
                <# _.each( settings.list_items, function( item, index ) { #>
                    <# if ( item.text != '' ) { #>
                        <li>
                            <# if ( item.link && item.link.url ) { #>
                                <a href="{{ item.link.url }}">
                            <# } #>
                            <# if ( item.pp_icon_type != 'none' ) { #>
                                <#
                                    if ( settings.icon_position == 'right' ) {
                                        var icon_class = 'pp-icon-right';
                                    } else {
                                        var icon_class = 'pp-icon-left';
                                    }
                                #>
                                <span class="pp-icon-wrapper {{ icon_class }}">
                                    <# if ( item.pp_icon_type == 'icon' ) { #>
										<# if ( item.list_icon || item.icon.value ) { #>
                                        	<span class="pp-icon-list-icon pp-icon elementor-animation-{{ settings.icon_hover_animation }}" aria-hidden="true">
											<#
												iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.icon, { 'aria-hidden': true }, 'i', 'object' );
												migrated[ index ] = elementor.helpers.isIconMigrated( item, 'icon' );
												if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.list_icon || migrated[ index ] ) ) { #>
													{{{ iconsHTML[ index ].value }}}
												<# } else { #>
													<i class="{{ item.list_icon }}" aria-hidden="true"></i>
												<# }
											#>
											</span>
										<# } #>
                                    <# } else if ( item.pp_icon_type == 'image' ) { #>
                                        <span class="pp-icon-list-image elementor-animation-{{ settings.icon_hover_animation }}">
                                            <#
                                            var image = {
                                                id: item.list_image.id,
                                                url: item.list_image.url,
                                                size: settings.image_size,
                                                dimension: settings.image_custom_dimension,
                                                model: view.getEditModel()
                                            };
                                            var image_url = elementor.imagesManager.getImageUrl( image );
                                            #>
                                            <img src="{{{ image_url }}}" />
                                        </span>
                                    <# } else if ( item.pp_icon_type == 'number' ) { #>
										<#
										   if ( item.icon_text ) {
										   		var number = item.icon_text;
										   } else {
										   		var number = i;
										   }
										#>
                                        <span class="pp-icon-list-icon elementor-animation-{{ settings.icon_hover_animation }}">
                                            {{ number }}
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