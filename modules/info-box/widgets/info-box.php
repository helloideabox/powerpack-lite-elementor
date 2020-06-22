<?php
namespace PowerpackElementsLite\Modules\InfoBox\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Info Box Widget
 */
class Info_Box extends Powerpack_Widget {
    
    /**
	 * Retrieve info box widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-info-box';
    }

    /**
	 * Retrieve info box widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Info Box', 'powerpack' );
    }

    /**
	 * Retrieve the list of categories the info box widget belongs to.
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
	 * Retrieve info box widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-info-box power-pack-admin-icon';
    }

    /**
	 * Register info box widget controls.
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
         * Content Tab: Icon
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_icon',
            [
                'label'                 => __( 'Icon', 'powerpack' ),
            ]
        );
        
        $this->add_control(
			'icon_type',
			[
				'label'                 => esc_html__( 'Type', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'       => false,
				'options'               => [
					'none' => [
						'title' => esc_html__( 'None', 'powerpack' ),
						'icon' => 'fa fa-ban',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'powerpack' ),
						'icon' => 'fa fa-star',
					],
					'image' => [
						'title' => esc_html__( 'Image', 'powerpack' ),
						'icon' => 'fa fa-picture-o',
					],
					'text' => [
						'title' => esc_html__( 'Text', 'powerpack' ),
						'icon' => 'fa fa-font',
					],
				],
				'default'               => 'icon',
			]
		);
		
		$this->add_control(
			'selected_icon',
			[
				'label'					=> __( 'Icon', 'powerpack' ),
				'type'					=> Controls_Manager::ICONS,
				'fa4compatibility'		=> 'icon',
				'default'				=> [
					'value'		=> 'fas fa-star',
					'library'	=> 'fa-solid',
				],
                'condition'             => [
                    'icon_type'     => 'icon',
                ],
			]
		);

        $this->add_control(
            'icon_text',
            [
                'label'                 => __( 'Icon Text', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => '1',
                'condition'             => [
                    'icon_type'     => 'text',
                ],
            ]
        );

		$this->add_control(
			'image',
			[
				'label'                 => __( 'Image', 'powerpack' ),
				'type'                  => Controls_Manager::MEDIA,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => [
					'url' => Utils::get_placeholder_image_src(),
				],
                'condition'             => [
                    'icon_type' => 'image',
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'   => 'full',
				'separator' => 'none',
                'condition'             => [
                    'icon_type' => 'image',
                ],
			]
		);
        
        $this->add_responsive_control(
            'icon_size',
            [
                'label'                 => __( 'Size', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 30,
                    'unit'  => 'px',
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 5,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em' ],
                'condition'             => [
                    'icon_type'     => 'icon',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_rotation',
            [
                'label'                 => __( 'Rotate', 'powerpack' ),
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
                    'icon_type!'   => 'none',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'transform: rotate( {{SIZE}}deg );',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'icon_img_width',
            [
                'label'                 => __( 'Width', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => '',
                    'unit'  => 'px',
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 25,
                        'max'   => 600,
                        'step'  => 1,
                    ],
                    '%' => [
                        'min'   => 25,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}}.pp-info-box-top .pp-info-box-icon img, {{WRAPPER}}.pp-info-box-left .pp-info-box-icon-wrap, {{WRAPPER}}.pp-info-box-right .pp-info-box-icon-wrap' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'icon_type'     => 'image',
                ],
            ]
        );

		$this->add_responsive_control(
			'icon_position',
			[
				'label'                 => __( 'Icon', 'powerpack' ) . ' ' . __( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'top',
				'options'               => [
					'left'        => [
						'title'   => __( 'Icon on Left', 'powerpack' ),
						'icon'    => 'eicon-h-align-left',
					],
					'top'         => [
						'title'   => __( 'Icon on Top', 'powerpack' ),
						'icon'    => 'eicon-v-align-top',
					],
					'right'       => [
						'title'   => __( 'Icon on Right', 'powerpack' ),
						'icon'    => 'eicon-h-align-right',
					],
				],
                'condition'             => [
                    'icon_type!'  => 'none',
                ],
                'prefix_class'          => 'pp-info-box%s-',
                'frontend_available'    => true,
			]
		);

		$this->add_responsive_control(
			'icon_vertical_position',
			[
				'label'                 => __( 'Vertical Align', 'powerpack' ),
				'description'           => __( 'Works in case of left and right icon position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'top',
				'options'               => [
					'top'          => [
						'title'    => __( 'Top', 'powerpack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => __( 'Middle', 'powerpack' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => __( 'Bottom', 'powerpack' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors'             => [
					'(desktop){{WRAPPER}}.pp-info-box-left .pp-info-box'        => 'align-items: {{VALUE}};',
					'(desktop){{WRAPPER}}.pp-info-box-right .pp-info-box'       => 'align-items: {{VALUE}};',
					'(tablet){{WRAPPER}}.pp-info-box-tablet-left .pp-info-box'  => 'align-items: {{VALUE}};',
					'(tablet){{WRAPPER}}.pp-info-box-tablet-right .pp-info-box' => 'align-items: {{VALUE}};',
					'(mobile){{WRAPPER}}.pp-info-box-mobile-left .pp-info-box'  => 'align-items: {{VALUE}};',
					'(mobile){{WRAPPER}}.pp-info-box-mobile-right .pp-info-box' => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
                'condition'             => [
                    'icon_type'         => ['icon', 'image', 'text'],
                ],
			]
		);
        
        $this->end_controls_section();
        
        /**
         * Content Tab: Content
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_content',
            [
                'label'                 => __( 'Content', 'powerpack' ),
            ]
        );

        $this->add_control(
            'heading',
            [
                'label'                 => __( 'Title', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Title', 'powerpack' ),
            ]
        );

        $this->add_control(
            'sub_heading',
            [
                'label'                 => __( 'Subtitle', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Subtitle', 'powerpack' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'label'                 => __( 'Description', 'powerpack' ),
                'type'                  => Controls_Manager::TEXTAREA,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Enter info box description', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'divider_title_switch',
            [
                'label'                 => __( 'Title Separator', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'On', 'powerpack' ),
                'label_off'             => __( 'Off', 'powerpack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'title_html_tag',
            [
                'label'                 => __( 'Title HTML Tag', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'h4',
                'options'               => [
                    'h1'     => __( 'H1', 'powerpack' ),
                    'h2'     => __( 'H2', 'powerpack' ),
                    'h3'     => __( 'H3', 'powerpack' ),
                    'h4'     => __( 'H4', 'powerpack' ),
                    'h5'     => __( 'H5', 'powerpack' ),
                    'h6'     => __( 'H6', 'powerpack' ),
                    'div'    => __( 'div', 'powerpack' ),
                    'span'   => __( 'span', 'powerpack' ),
                    'p'      => __( 'p', 'powerpack' ),
                ],
            ]
        );
        
        $this->add_control(
            'sub_title_html_tag',
            [
                'label'                 => __( 'Subtitle HTML Tag', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'h5',
                'options'               => [
                    'h1'     => __( 'H1', 'powerpack' ),
                    'h2'     => __( 'H2', 'powerpack' ),
                    'h3'     => __( 'H3', 'powerpack' ),
                    'h4'     => __( 'H4', 'powerpack' ),
                    'h5'     => __( 'H5', 'powerpack' ),
                    'h6'     => __( 'H6', 'powerpack' ),
                    'div'    => __( 'div', 'powerpack' ),
                    'span'   => __( 'span', 'powerpack' ),
                    'p'      => __( 'p', 'powerpack' ),
                ],
                'condition'             => [
                    'sub_heading!'  => '',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Content Tab: Link
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_link',
            [
                'label'                 => __( 'Link', 'powerpack' ),
            ]
        );
        
        $this->add_control(
            'link_type',
            [
                'label'                 => __( 'Link Type', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'none',
                'options'               => [
                    'none'      => __( 'None', 'powerpack' ),
                    'box'       => __( 'Box', 'powerpack' ),
                    'icon'      => __( 'Image/Icon', 'powerpack' ),
                    'title'     => __( 'Title', 'powerpack' ),
                    'button'    => __( 'Button', 'powerpack' ),
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'                 => __( 'Link', 'powerpack' ),
                'type'                  => Controls_Manager::URL,
				'dynamic'               => [
					'active'        => true,
                    'categories'    => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY
                    ],
				],
                'placeholder'           => 'https://www.your-link.com',
                'default'               => [
                    'url' => '#',
                ],
                'condition'             => [
                    'link_type!'   => 'none',
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label'                 => __( 'Button', 'powerpack' ) . ' ' . __( 'Text', 'powerpack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Get Started', 'powerpack' ),
                'condition'             => [
                    'link_type'   => 'button',
                ],
            ]
        );
		
		$this->add_control(
			'select_button_icon',
			[
				'label'					=> __( 'Button', 'powerpack' ) . ' ' . __( 'Icon', 'powerpack' ),
				'type'					=> Controls_Manager::ICONS,
				'fa4compatibility'		=> 'button_icon',
                'condition'             => [
                    'link_type'   => 'button',
                ],
			]
		);
        
        $this->add_control(
            'button_icon_position',
            [
                'label'                 => __( 'Icon Position', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'after',
                'options'               => [
                    'after'     => __( 'After', 'powerpack' ),
                    'before'    => __( 'Before', 'powerpack' ),
                ],
                'condition'             => [
                    'link_type'     => 'button',
                    'select_button_icon[value]!'	=> '',
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
				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/info-box/info-box-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			]
		);

		$this->end_controls_section();
		
		if ( !is_pp_elements_active() ) {
			$this->start_controls_section(
				'section_upgrade_powerpack',
				[
					'label'                 => apply_filters( 'upgrade_powerpack_title', __( 'Get PowerPack Pro', 'powerpack' ) ),
					'tab'					=> Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'upgrade_powerpack_notice',
				[
					'label'                 => '',
					'type'					=> Controls_Manager::RAW_HTML,
					'raw'					=> apply_filters( 'upgrade_powerpack_message', sprintf( __( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
					'content_classes'		=> 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				]
			);

			$this->end_controls_section();
		}

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Info Box
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_style',
            [
                'label'                 => __( 'Info Box', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'align',
			[
				'label'                 => __( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify'   => [
						'title' => __( 'Justified', 'powerpack' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box'   => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_info_box_style' );

        $this->start_controls_tab(
            'tab_info_box_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'info_box_bg',
                'label'                 => __( 'Background', 'powerpack' ),
                'types'                 => [ 'none','classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-info-box-container',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'info_box_border',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-info-box-container',
			]
		);

		$this->add_control(
			'info_box_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'info_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-info-box-container',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_info_box_hover',
            [
                'label'                 => __( 'Hover', 'powerpack' ),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'info_box_bg_hover',
                'label'                 => __( 'Background', 'powerpack' ),
                'types'                 => [ 'none','classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-info-box-container:hover',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'info_box_border_hover',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-info-box-container:hover',
			]
		);

		$this->add_control(
			'info_box_border_radius_hover',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-container:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'info_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-info-box-container:hover',
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();

		$this->add_responsive_control(
			'info_box_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
                'separator'             => 'before',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'info_box_min_height',
			array(
				'label'      => __( 'Min Height', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => 50,
						'max'  => 1000,
						'step' => 1,
					),
					'vh' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
				'size_units' => array( 'px', 'vh' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-info-box-container' => 'min-height: {{SIZE}}{{UNIT}}',
				),
			)
		);

        $this->end_controls_section();

        /**
         * Style Tab: Icon
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_icon_style',
            [
                'label'                 => __( 'Icon', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'icon_type!' => 'none',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'icon_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'condition'             => [
                    'icon_type'     => 'text',
                ],
                'selector'              => '{{WRAPPER}} .pp-info-box-icon',
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
            'icon_color_normal',
            [
                'label'                 => __( 'Icon Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .pp-info-box-icon svg' => 'fill: {{VALUE}}',
                ],
                'condition'             => [
                    'icon_type!'    => 'image',
                ],
            ]
        );
        
        $this->add_control(
            'icon_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon' => 'background-color: {{VALUE}}',
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
                'condition'             => [
                    'icon_type!'   => 'none',
                ],
				'selector'              => '{{WRAPPER}} .pp-info-box-icon',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
                'condition'             => [
                    'icon_type!'   => 'none',
                ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon, {{WRAPPER}} .pp-info-box-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .pp-info-box-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label'                 => __( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'       => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon-wrap' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
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
                'label'                 => __( 'Icon Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .pp-info-box-icon:hover svg' => 'fill: {{VALUE}}',
                ],
                'condition'             => [
                    'icon_type!'    => 'image',
                ],
            ]
        );
        
        $this->add_control(
            'icon_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'condition'             => [
                    'icon_type!'   => 'none',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'condition'             => [
                    'icon_type!'   => 'none',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-icon:hover .fa' => 'border-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
			'hover_animation_icon',
			[
				'label'                 => __( 'Icon Animation', 'powerpack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Title
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_title_style',
            [
                'label'                 => __( 'Title', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-box-title',
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label'                 => __( 'Margin Bottom', 'powerpack' ),
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
                    '{{WRAPPER}} .pp-info-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_control(
            'subtitle_heading',
            [
                'label'                 => __( 'Sub Title', 'powerpack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
                    'sub_heading!'  => '',
                ],
            ]
        );

        $this->add_control(
            'subtitle_color',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'condition'             => [
                    'sub_heading!'  => '',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-subtitle' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'subtitle_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'condition'             => [
                    'sub_heading!'  => '',
                ],
                'selector'              => '{{WRAPPER}} .pp-info-box-subtitle',
            ]
        );
        
        $this->add_responsive_control(
            'subtitle_margin',
            [
                'label'                 => __( 'Margin Bottom', 'powerpack' ),
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
                'condition'             => [
                    'sub_heading!'  => '',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Title Separator
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_title_divider_style',
            [
                'label'                 => __( 'Title Separator', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'divider_title_border_type',
            [
                'label'                 => __( 'Border Type', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'solid',
                'options'               => [
                    'none'      => __( 'None', 'powerpack' ),
                    'solid'     => __( 'Solid', 'powerpack' ),
                    'double'    => __( 'Double', 'powerpack' ),
                    'dotted'    => __( 'Dotted', 'powerpack' ),
                    'dashed'    => __( 'Dashed', 'powerpack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-style: {{VALUE}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'divider_title_width',
            [
                'label'                 => __( 'Border Width', 'powerpack' ),
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
                    '{{WRAPPER}} .pp-info-box-divider' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'divider_title_border_height',
            [
                'label'                 => __( 'Border Height', 'powerpack' ),
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
                    '{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'divider_title_border_color',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-color: {{VALUE}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
			'divider_title_align',
			[
				'label'                 => __( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'flex-start'      => [
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					],
					'flex-end'     => [
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-divider-wrap'   => 'display: flex; justify-content: {{VALUE}};',
				],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
			]
		);
        
        $this->add_responsive_control(
            'divider_title_margin',
            [
                'label'                 => __( 'Margin Bottom', 'powerpack' ),
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
                    '{{WRAPPER}} .pp-info-box-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'divider_title_switch' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Description
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_description_style',
            [
                'label'                 => __( 'Description', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'description!' => '',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-description' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'description!' => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'description_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-box-description',
                'condition'             => [
                    'description!' => '',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'description_margin',
            [
                'label'                 => __( 'Margin Bottom', 'powerpack' ),
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
                    '{{WRAPPER}} .pp-info-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'description!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_button_style',
            [
                'label'                 => __( 'Button', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

		$this->add_control(
			'button_size',
			[
				'label'                 => __( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'md',
				'options'               => [
					'xs' => __( 'Extra Small', 'powerpack' ),
					'sm' => __( 'Small', 'powerpack' ),
					'md' => __( 'Medium', 'powerpack' ),
					'lg' => __( 'Large', 'powerpack' ),
					'xl' => __( 'Extra Large', 'powerpack' ),
				],
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label'                 => __( 'Normal', 'powerpack' ),
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

        $this->add_control(
            'button_text_color_normal',
            [
                'label'                 => __( 'Text Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .pp-info-box-button .pp-icon svg' => 'fill: {{VALUE}}',
                ],
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

        $this->add_control(
            'button_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_normal',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-info-box-button',
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'button_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-info-box-button',
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-info-box-button',
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);
        
        $this->add_control(
            'info_box_button_icon_heading',
            [
                'label'                 => __( 'Button Icon', 'powerpack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
					'link_type'    => 'button',
                    'select_button_icon[value]!'	=> '',
                ],
            ]
        );

		$this->add_responsive_control(
			'button_icon_margin',
			[
				'label'                 => __( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'       => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
                'condition'             => [
					'link_type'    => 'button',
                    'select_button_icon[value]!'	=> '',
                ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box .pp-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label'                 => __( 'Hover', 'powerpack' ),
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .pp-info-box-button:hover .pp-icon svg' => 'fill: {{VALUE}}',
                ],
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button:hover' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-info-box-button:hover' => 'border-color: {{VALUE}}',
                ],
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

		$this->add_control(
			'button_animation',
			[
				'label'                 => __( 'Animation', 'powerpack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-info-box-button:hover',
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();

    }

    /**
	 * Render info box icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_infobox_icon() {
        $settings = $this->get_settings_for_display();
		
        $this->add_inline_editing_attributes( 'icon_text', 'none' );
        $this->add_render_attribute( 'icon_text', 'class', 'pp-icon-text' );
        
        $this->add_render_attribute( 'icon', 'class', ['pp-info-box-icon', 'pp-icon'] );

		if ( $settings['hover_animation_icon'] ) {
			$this->add_render_attribute( 'icon', 'class', 'elementor-animation-' . $settings['hover_animation_icon'] );
		}
		
		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['icon'] = 'fa fa-star';
		}

		$has_icon = ! empty( $settings['icon'] );
		
		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}
		
		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = ! isset( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		?>
		<span <?php echo $this->get_render_attribute_string( 'icon' ); ?>>
			<?php if ( $settings['icon_type'] == 'icon' && $has_icon ) { ?>
				<?php
				if ( $is_new || $migrated ) {
					Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
				} elseif ( ! empty( $settings['icon'] ) ) {
					?><i <?php echo $this->get_render_attribute_string( 'i' ); ?>></i><?php
				}
				?>
			<?php } elseif ( $settings['icon_type'] == 'image' ) {

					if ( ! empty( $settings['image']['url'] ) ) {
						echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'image' );
					}

			} elseif ( $settings['icon_type'] == 'text' ) { ?>
				<span class="pp-icon-text">
					<?php echo $settings['icon_text']; ?>
				</span>
			<?php } ?>
		</span>
		<?php
    }

    /**
	 * Render info box button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_infobox_button() {
        $settings = $this->get_settings_for_display();
        
        $this->add_inline_editing_attributes( 'button_text', 'none' );
        $this->add_render_attribute( 'button_text', 'class', 'pp-button-text' );
		
        $this->add_render_attribute( 'info-box-button', 'class', [
				'pp-info-box-button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			]
		);
		
        $pp_button_html_tag = ( $settings['link_type'] == 'button' ) ? 'a' : 'div';

		if ( $settings['button_animation'] ) {
			$this->add_render_attribute( 'info-box-button', 'class', 'elementor-animation-' . $settings['button_animation'] );
		}
		
		if ( ! isset( $settings['button_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['button_icon'] = '';
		}

		$has_icon = ! empty( $settings['button_icon'] );
		
		if ( $has_icon ) {
			$this->add_render_attribute( 'button-icon', 'class', $settings['button_icon'] );
			$this->add_render_attribute( 'button-icon', 'aria-hidden', 'true' );
		}
		
		if ( ! $has_icon && ! empty( $settings['select_button_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['select_button_icon'] );
		$is_new = ! isset( $settings['button_icon'] ) && Icons_Manager::is_migration_allowed();
		
		if ( $settings['link_type'] == 'button' ) { ?>
			<?php if ( $settings['button_text'] != '' || $has_icon ) { ?>
				<div class="pp-info-box-footer">
					<<?php echo $pp_button_html_tag. ' ' . $this->get_render_attribute_string( 'info-box-button' ) .$this->get_render_attribute_string( 'link' ); ?>>
						<?php if ( $settings['button_icon_position'] == 'before' && $has_icon ) { ?>
							<span class='pp-button-icon pp-icon'>
								<?php
								if ( $is_new || $migrated ) {
									Icons_Manager::render_icon( $settings['select_button_icon'], [ 'aria-hidden' => 'true' ] );
								} elseif ( ! empty( $settings['button_icon'] ) ) {
									?><i <?php echo $this->get_render_attribute_string( 'button-icon' ); ?>></i><?php
								}
								?>
							</span>
						<?php } ?>
						<?php if ( ! empty( $settings['button_text'] ) ) { ?>
							<span <?php echo $this->get_render_attribute_string( 'button_text' ); ?>>
								<?php echo esc_attr( $settings['button_text'] ); ?>
							</span>
						<?php } ?>
						<?php if ( $settings['button_icon_position'] == 'after' && $has_icon ) { ?>
							<span class='pp-button-icon pp-icon'>
								<?php
								if ( $is_new || $migrated ) {
									Icons_Manager::render_icon( $settings['select_button_icon'], [ 'aria-hidden' => 'true' ] );
								} elseif ( ! empty( $settings['button_icon'] ) ) {
									?><i <?php echo $this->get_render_attribute_string( 'button-icon' ); ?>></i><?php
								}
								?>
							</span>
						<?php } ?>
					</<?php echo $pp_button_html_tag; ?>>
				</div>
			<?php } ?>
			<?php
		}
    }

    /**
	 * Render info box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( [
			'info-box' => [
				'class' => 'pp-info-box'
			],
			'info-box-container' => [
				'class' => 'pp-info-box-container'
			],
			'title-container' => [
				'class' => 'pp-info-box-title-container'
			],
			'heading' => [
				'class' => 'pp-info-box-title'
			],
			'sub_heading' => [
				'class' => 'pp-info-box-subtitle'
			],
			'description' => [
				'class' => 'pp-info-box-description'
			]
		] );
        
        $pp_if_html_tag = 'div';
        $pp_title_html_tag = 'div';
        
        $this->add_inline_editing_attributes( 'heading', 'none' );
        $this->add_inline_editing_attributes( 'sub_heading', 'none' );
        $this->add_inline_editing_attributes( 'description', 'basic' );

        if ( $settings['link_type'] != 'none' ) {
            if ( ! empty( $settings['link']['url'] ) ) {
				$this->add_link_attributes( 'link', $settings['link'] );

                if ( $settings['link_type'] == 'box' ) {
                    $pp_if_html_tag = 'a';
                }
                elseif ( $settings['link_type'] == 'title' ) {
                    $pp_title_html_tag = 'a';
					$this->add_link_attributes( 'title-container', $settings['link'] );
                }
            }
        }
        ?>
        <<?php echo $pp_if_html_tag . ' ' . $this->get_render_attribute_string( 'info-box-container' ) . $this->get_render_attribute_string( 'link' ); ?>>
        <div <?php echo $this->get_render_attribute_string( 'info-box' ); ?>>
            <?php if ( $settings['icon_type'] != 'none' ) { ?>
                <div class="pp-info-box-icon-wrap">
                    <?php if ( $settings['link_type'] == 'icon' ) { ?>
                        <a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
                    <?php } ?>
                    <?php
						// Icon
						$this->render_infobox_icon();
					?>
                    <?php if ( $settings['link_type'] == 'icon' ) { ?>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
            <div class="pp-info-box-content">
                <div class="pp-info-box-title-wrap">
                    <?php
                        if ( ! empty( $settings['heading'] ) ) {
                            echo '<'.$pp_title_html_tag. ' ' . $this->get_render_attribute_string( 'title-container' ) . '>';
                            printf( '<%1$s %2$s>', $settings['title_html_tag'], $this->get_render_attribute_string( 'heading' ) );
                            echo $settings['heading'];
                            printf( '</%1$s>', $settings['title_html_tag'] );
                            echo '</'.$pp_title_html_tag. '>';
                        }
        
                        if ( ! empty( $settings['sub_heading'] ) ) {
                            printf( '<%1$s %2$s>', $settings['sub_title_html_tag'], $this->get_render_attribute_string( 'sub_heading' ) );
                            echo $settings['sub_heading'];
                            printf( '</%1$s>', $settings['sub_title_html_tag'] );
                        }
                    ?>
                </div>

                <?php if ( $settings['divider_title_switch'] == 'yes' ) { ?>
                    <div class="pp-info-box-divider-wrap">
                        <div class="pp-info-box-divider"></div>
                    </div>
                <?php } ?>

                <?php if ( ! empty( $settings['description'] ) ) { ?>
                    <div <?php echo $this->get_render_attribute_string( 'description' ); ?>>
                        <?php echo $this->parse_text_editor( nl2br( $settings['description'] ) ); ?>
                    </div>
                <?php } ?>
                <?php
					// Button
					$this->render_infobox_button();
				?>
            </div>
        </div>
        </<?php echo $pp_if_html_tag; ?>>
        <?php
    }

    /**
	 * Render info box widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() { ?>
        <#
            var pp_if_html_tag = 'div',
				pp_title_html_tag = 'div',
				pp_button_html_tag = 'div',
				iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
				migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' ),
				buttonIconHTML = elementor.helpers.renderIcon( view, settings.select_button_icon, { 'aria-hidden': true }, 'i' , 'object' ),
				buttonMigrated = elementor.helpers.isIconMigrated( settings, 'select_button_icon' );

			view.addRenderAttribute( 'title-container', 'class', 'pp-info-box-title-container' );

            if ( settings.link.url != '' ) {
                if ( settings.link_type == 'box' ) {
                    var pp_if_html_tag = 'a';
                }
                else if ( settings.link_type == 'title' ) {
                    var pp_title_html_tag = 'a';
					view.addRenderAttribute( 'title-container', 'href', settings.link.url );
                }
                else if ( settings.link_type == 'button' ) {
                    var pp_button_html_tag = 'a';
                }
            }
        #>
        <{{{pp_if_html_tag}}} class="pp-info-box-container" href="{{settings.link.url}}">
            <div class="pp-info-box pp-info-box-{{ settings.icon_position }}">
                <# if ( settings.icon_type != 'none' ) { #>
                    <div class="pp-info-box-icon-wrap">
                        <# if ( settings.link_type == 'icon' ) { #>
                            <a href="{{settings.link.url}}">
                        <# } #>
                        <span class="pp-info-box-icon pp-icon elementor-animation-{{ settings.hover_animation_icon }}">
                            <# if ( settings.icon_type == 'icon' ) { #>
								<# if ( settings.icon || settings.selected_icon ) { #>
								<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
								{{{ iconHTML.value }}}
								<# } else { #>
									<i class="{{ settings.icon }}" aria-hidden="true"></i>
								<# } #>
								<# } #>
                            <# } else if ( settings.icon_type == 'image' ) { #>
                                <#
                                var image = {
                                    id: settings.image.id,
                                    url: settings.image.url,
                                    size: settings.image_size,
                                    dimension: settings.image_custom_dimension,
                                    model: view.getEditModel()
                                };
                                var image_url = elementor.imagesManager.getImageUrl( image );
                                #>
                                <img src="{{{ image_url }}}" />
                            <# } else if ( settings.icon_type == 'text' ) { #>
                                <span class="pp-icon-text elementor-inline-editing" data-elementor-setting-key="icon_text" data-elementor-inline-editing-toolbar="none">
                                    {{{ settings.icon_text }}}
                                </span>
                            <# } #>
                        </span>
                        <# if ( settings.link_type == 'icon' ) { #>
                            </a>
                        <# } #>
                    </div>
                <# } #>
                <div class="pp-info-box-content">
                    <div class="pp-info-box-title-wrap">
                        <# if ( settings.heading ) { #>
                            <{{pp_title_html_tag}} {{{ view.getRenderAttributeString( 'title-container' ) }}}>
                                <{{settings.title_html_tag}} class="pp-info-box-title elementor-inline-editing" data-elementor-setting-key="heading" data-elementor-inline-editing-toolbar="none">
                                    {{{ settings.heading }}}
                                </{{settings.title_html_tag}}>
                            </{{pp_title_html_tag}}>
                        <# } #>
                        <# if ( settings.sub_heading ) { #>
                            <{{settings.sub_title_html_tag}} class="pp-info-box-subtitle elementor-inline-editing" data-elementor-setting-key="sub_heading" data-elementor-inline-editing-toolbar="none">
                                {{{ settings.sub_heading }}}
                            </{{settings.sub_title_html_tag}}>
                        <# } #>
                    </div>

                    <# if ( settings.divider_title_switch == 'yes' ) { #>
                        <div class="pp-info-box-divider-wrap">
                            <div class="pp-info-box-divider"></div>
                        </div>
                    <# } #>

                    <# if ( settings.description ) { #>
                        <div class="pp-info-box-description elementor-inline-editing" data-elementor-setting-key="description" data-elementor-inline-editing-toolbar="basic">
                            {{{ settings.description }}}
                        </div>
                    <# } #>
                    <# if ( settings.link_type == 'button' ) { #>
                        <# if ( settings.button_text != '' || settings.button_icon != '' ) { #>
                            <div class="pp-info-box-footer">
                                <{{pp_button_html_tag}} href="{{ settings.link.url }}" class="pp-info-box-button elementor-button elementor-size-{{ settings.button_size }} elementor-animation-{{ settings.button_animation }}">
                                    <# if ( settings.button_icon_position == 'before' ) { #>
										<# if ( settings.button_icon || settings.select_button_icon ) { #>
											<span class="pp-button-icon pp-icon">
												<# if ( buttonIconHTML && buttonIconHTML.rendered && ( ! settings.button_icon || buttonMigrated ) ) { #>
												{{{ buttonIconHTML.value }}}
												<# } else { #>
													<i class="{{ settings.button_icon }}" aria-hidden="true"></i>
												<# } #>
											</span>
										<# } #>
                                    <# } #>
                                    <# if ( settings.button_text ) { #>
                                        <span class="pp-button-text elementor-inline-editing" data-elementor-setting-key="button_text" data-elementor-inline-editing-toolbar="none">
                                            {{{ settings.button_text }}}
                                        </span>
                                    <# } #>
                                    <# if ( settings.button_icon_position == 'after' ) { #>
										<# if ( settings.button_icon || settings.select_button_icon ) { #>
											<span class="pp-button-icon pp-icon">
												<# if ( buttonIconHTML && buttonIconHTML.rendered && ( ! settings.button_icon || buttonMigrated ) ) { #>
												{{{ buttonIconHTML.value }}}
												<# } else { #>
													<i class="{{ settings.button_icon }}" aria-hidden="true"></i>
												<# } #>
											</span>
										<# } #>
                                    <# } #>
                                </{{pp_button_html_tag}}>
                            </div>
                        <# } #>
                    <# } #>
                </div>
            </div>
        </{{{pp_if_html_tag}}}>
        <?php
    }

}