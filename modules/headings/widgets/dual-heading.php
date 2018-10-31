<?php
namespace PowerpackElements\Modules\Headings\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
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
 * Dual Heading Widget
 */
class Dual_Heading extends Powerpack_Widget {
    
    /**
	 * Retrieve dual heading widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-dual-heading';
    }

    /**
	 * Retrieve dual heading widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Dual Heading', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the dual heading widget belongs to.
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
	 * Retrieve dual heading widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-dual-heading power-pack-admin-icon';
    }

    /**
	 * Register dual heading widget controls.
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
         * Content Tab: Dual Heading
         */
        $this->start_controls_section(
            'section_dual_heading',
            [
                'label'                 => __( 'Dual Heading', 'power-pack' ),
            ]
        );

        $this->add_control(
            'first_text',
            [
                'label'                 => __( 'First Part', 'power-pack' ),
                'type'                  => Controls_Manager::TEXTAREA,
				'dynamic'               => [
					'active'   => true,
				],
                'label_block'           => true,
                'rows'                  => 3,
                'default'               => __( 'Our', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'second_text',
            [
                'label'                 => __( 'Second Part', 'power-pack' ),
                'type'                  => Controls_Manager::TEXTAREA,
				'dynamic'               => [
					'active'   => true,
				],
                'label_block'           => true,
                'rows'                  => 3,
                'default'               => __( 'Services', 'power-pack' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label'                 => __( 'Link', 'power-pack' ),
                'type'                  => Controls_Manager::URL,
				'dynamic'               => [
					'active'        => true,
                    'categories'    => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY
                    ],
				],
                'label_block'           => true,
                'placeholder'           => 'https://www.your-link.com',
            ]
        );
        
        $this->add_control(
            'heading_html_tag',
            [
                'label'                 => __( 'HTML Tag', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'label_block'           => false,
                'default'               => 'h2',
                'options'               => [
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
        
        $this->add_control(
            'second_part_display',
            [
                'label'                 => __( 'Second Part Display', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'label_block'           => false,
                'default'               => 'inline-block',
                'options'               => [
                    'inline-block'  => __( 'Inline', 'power-pack' ),
                    'block'         => __( 'Block', 'power-pack' ),
                ],
                'prefix_class'          => 'pp-dual-heading-',
				'selectors'             => [
					'{{WRAPPER}} .pp-second-text' => 'display: {{VALUE}};',
				],
            ]
        );
        
        $this->add_responsive_control(
			'align',
			[
				'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => true,
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
					'{{WRAPPER}}'   => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Style Tab: First Part
         */
        $this->start_controls_section(
            'first_section_style',
            [
                'label'                 => __( 'First Part', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'first_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-first-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'first_part_bg',
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'none','classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-first-text',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'first_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-first-text',
				'separator'             => 'before',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'first_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-first-text',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'first_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-first-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'first_text_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-first-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'                  => 'first_text_shadow',
				'selector'              => '{{WRAPPER}} .pp-first-text',
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'first_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-first-text',
				'separator'             => 'before',
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Second Part
         */
        $this->start_controls_section(
            'second_section_style',
            [
                'label'                 => __( 'Second Part', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'second_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-second-text' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'second_part_bg',
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'none','classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-second-text',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'second_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-second-text',
				'separator'             => 'before',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'second_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-second-text',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'second_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-second-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'second_text_margin',
			[
				'label'                 => __( 'Spacing', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ '%', 'px' ],
                'default'               => [
                    'size' => 0,
                    'unit' => 'px',
                ],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}}.pp-dual-heading-inline-block .pp-second-text' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.pp-dual-heading-block .pp-second-text' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'second_text_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-second-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'                  => 'second_text_shadow',
				'selector'              => '{{WRAPPER}} .pp-second-text',
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'second_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-second-text',
				'separator'             => 'before',
			]
		);

        $this->end_controls_section();

    }

    /**
	 * Render dual heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'dual-heading', 'class', 'pp-dual-heading' );
        $this->add_inline_editing_attributes( 'first_text', 'basic' );
        $this->add_render_attribute( 'first_text', 'class', 'pp-first-text' );
        $this->add_inline_editing_attributes( 'second_text', 'basic' );
        $this->add_render_attribute( 'second_text', 'class', 'pp-second-text' );
        
        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute( 'dual-heading-link', 'href', $settings['link']['url'] );

            if ( $settings['link']['is_external'] ) {
                $this->add_render_attribute( 'dual-heading-link', 'target', '_blank' );
            }

            if ( $settings['link']['nofollow'] ) {
                $this->add_render_attribute( 'dual-heading-link', 'rel', 'nofollow' );
            }
        }
        
        if ( $settings['first_text'] || $settings['second_text'] ) {
            printf( '<%1$s %2$s>', $settings['heading_html_tag'], $this->get_render_attribute_string( 'dual-heading' ) );
                if ( ! empty( $settings['link']['url'] ) ) { printf( '<a %1$s>', $this->get_render_attribute_string( 'dual-heading-link' ) ); }
            
                if ( $settings['first_text'] ) {
                    printf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( 'first_text' ), $this->parse_text_editor( $settings['first_text'] ) );
                }
                if ( $settings['second_text'] ) {
                    printf( ' <span %1$s>%2$s</span>', $this->get_render_attribute_string( 'second_text' ), $this->parse_text_editor( $settings['second_text'] ) );
                }
            
                if ( ! empty( $settings['link']['url'] ) ) { printf( '</a>' ); }
            printf( '</%1$s>', $settings['heading_html_tag'] );
        }
    }

    /**
	 * Render dual heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <{{{settings.heading_html_tag}}} class="pp-dual-heading">
            <# if ( settings.link.url ) { #><a href="{{settings.link.url}}"><# } #>
                <#
                if ( settings.first_text != '' ) {
                    var first_text = settings.first_text;

                    view.addRenderAttribute( 'first_text', 'class', 'pp-first-text' );

                    view.addInlineEditingAttributes( 'first_text' );

                    var first_text_html = '<span' + ' ' + view.getRenderAttributeString( 'first_text' ) + '>' + first_text + '</span>';

                    print( first_text_html );
                }

                if ( settings.second_text != '' ) {
                    var second_text = settings.second_text;

                    view.addRenderAttribute( 'second_text', 'class', 'pp-second-text' );

                    view.addInlineEditingAttributes( 'second_text' );

                    var second_text_html = '<span' + ' ' + view.getRenderAttributeString( 'second_text' ) + '>' + second_text + '</span>';

                    print( second_text_html );
                }
                #>
            <# if ( settings.link.url ) { #></a><# } #>
        </{{{settings.heading_html_tag}}}>
        <?php
    }
}