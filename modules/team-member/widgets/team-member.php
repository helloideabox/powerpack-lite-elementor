<?php
namespace PowerpackElementsLite\Modules\TeamMember\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Team Member Widget
 */
class Team_Member extends Powerpack_Widget {
    
    /**
	 * Retrieve team member widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-team-member';
    }

    /**
	 * Retrieve team member widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Team Member', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the team member widget belongs to.
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
	 * Retrieve team member widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-team-member power-pack-admin-icon';
    }

    /**
	 * Retrieve the list of scripts the team member widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Team Member
         */
        $this->start_controls_section(
            'section_team_member',
            [
                'label'                 => __( 'Team Member', 'power-pack' ),
            ]
        );

        $this->add_control(
            'team_member_name',
            [
                'label'                 => __( 'Name', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'John Doe', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'team_member_position',
            [
                'label'                 => __( 'Position', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'WordPress Developer', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'team_member_description_switch',
            [
                'label'                 => __( 'Show Description', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );

        $this->add_control(
            'team_member_description',
            [
                'label'                 => __( 'Description', 'power-pack' ),
                'type'                  => Controls_Manager::TEXTAREA,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Enter member description here which describes the position of member in company', 'power-pack' ),
				'condition'             => [
					'team_member_description_switch' => 'yes',
				],
            ]
        );

		$this->add_control(
			'image',
			[
				'label'                 => __( 'Image', 'power-pack' ),
				'type'                  => Controls_Manager::MEDIA,
				'default'               => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);
		
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'image',
				'label'                 => __( 'Image Size', 'power-pack' ),
				'default'               => 'medium_large',
			]
		);
        
        $this->add_control(
            'link_type',
            [
                'label'                 => __( 'Link Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'none',
                'options'               => [
                    'none'      => __( 'None', 'power-pack' ),
                    'image'     => __( 'Image', 'power-pack' ),
                    'title'     => __( 'Title', 'power-pack' ),
                ],
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
                'placeholder'           => 'https://www.your-link.com',
                'default'               => [
                    'url' => '#',
                ],
                'condition'             => [
                    'link_type!'   => 'none',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Social Links
         */
        $this->start_controls_section(
            'section_member_social_links',
            [
                'label'                 => __( 'Social Links', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'member_social_links',
            [
                'label'                 => __( 'Show Social Links', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
			'team_member_social',
			[
				'label'                 => __( 'Social Links', 'power-pack' ),
				'type'                  => Controls_Manager::REPEATER,
				'default'               => [
					[
						'social_icon' => 'fa fa-facebook',
						'social_link' => [
                            'url' => '#',
                        ],
					],
					[
						'social_icon' => 'fa fa-twitter',
						'social_link' => [
                            'url' => '#',
                        ],
					],
					[
						'social_icon' => 'fa fa-youtube',
						'social_link' => [
                            'url' => '#',
                        ],
					],
				],
				'fields'            => [
					[
						'name'        => 'social_icon',
						'label'       => __( 'Social Icon', 'power-pack' ),
						'type'        => Controls_Manager::ICON,
						'label_block' => false,
						'placeholder' => __( 'Select Icon', 'power-pack' ),
					],
                    [
						'name'        => 'social_link',
						'label'       => __( 'Social Link', 'power-pack' ),
						'type'        => Controls_Manager::URL,
                        'dynamic'     => [
                            'active'  => true,
                        ],
						'label_block' => false,
						'placeholder' => __( 'Enter URL', 'power-pack' ),
					],
				],
				'title_field'       => '{{{ social_icon }}}',
				'condition'             => [
					'member_social_links' => 'yes',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Content Tab: Settings
         */
        $this->start_controls_section(
            'section_member_box_settings',
            [
                'label'                 => __( 'Settings', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'name_html_tag',
            [
                'label'                => __( 'Name HTML Tag', 'power-pack' ),
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
        
        $this->add_control(
            'position_html_tag',
            [
                'label'                => __( 'Position HTML Tag', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'div',
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
        
        $this->add_control(
            'social_links_position',
            [
                'label'                => __( 'Social Links Position', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'after_desc',
                'options'              => [
                    'before_desc'      => __( 'Before Description', 'power-pack' ),
                    'after_desc'       => __( 'After Description', 'power-pack' ),
                ],
				'condition'            => [
					'member_social_links' => 'yes',
					'overlay_content!' => 'social_icons',
				],
            ]
        );
        
        $this->add_control(
            'overlay_content',
            [
                'label'                => __( 'Overlay Content', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'none',
                'options'              => [
                    'none'             => __( 'None', 'power-pack' ),
                    'social_icons'     => __( 'Social Icons', 'power-pack' ),
                    'all_content'      => __( 'Content + Social Icons', 'power-pack' ),
                ],
            ]
        );
        
        $this->add_control(
            'member_title_divider',
            [
                'label'                 => __( 'Divider after Name', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'member_position_divider',
            [
                'label'                 => __( 'Divider after Position', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'hide',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'yes',
				'condition'             => [
					'team_member_position!'  => '',
				],
            ]
        );
        
        $this->add_control(
            'member_description_divider',
            [
                'label'                 => __( 'Divider after Description', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'hide',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'yes',
				'condition'             => [
					'team_member_description_switch'  => 'yes',
				],
            ]
        );
        
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Box Style
         */
        $this->start_controls_section(
            'section_member_box_style',
            [
                'label'                 => __( 'Box Style', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'member_box_alignment',
            [
                'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .pp-tm-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Content
         */
        $this->start_controls_section(
            'section_content_style',
            [
                'label'                 => __( 'Content', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
			
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'content_background',
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-tm-content-normal',
            ]
        );

		$this->add_responsive_control(
			'member_box_content_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-content-normal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_box_content_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'member_content_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-tm-content',
			]
		);
        
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'content_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-tm-content',
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Overlay
         */
        $this->start_controls_section(
            'section_member_overlay_style',
            [
                'label'                 => __( 'Overlay', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'overlay_content!'  => 'none',
				],
            ]
        );
        
        $this->add_responsive_control(
            'overlay_alignment',
            [
                'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .pp-tm-overlay-content-wrap' => 'text-align: {{VALUE}};',
				],
				'condition'             => [
					'overlay_content!'  => 'none',
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'overlay_background',
				'types'                 => [ 'classic', 'gradient' ],
				'selector'              => '{{WRAPPER}} .pp-tm-overlay-content-wrap:before',
				'condition'             => [
					'overlay_content!'  => 'none',
				],
			]
		);
        
        $this->add_control(
			'overlay_opacity',
			[
				'label'                 => __( 'Opacity', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.1,
                    ],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-overlay-content-wrap:before' => 'opacity: {{SIZE}};',
				],
				'condition'             => [
					'overlay_content!'  => 'none',
				],
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Image
         */
        $this->start_controls_section(
            'section_member_image_style',
            [
                'label'                 => __( 'Image', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'member_image_width',
			[
				'label'                 => __( 'Image Width', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ '%', 'px' ],
				'range'                 => [
					'px' => [
						'max' => 1200,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'member_image_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-tm-image img',
			]
		);

		$this->add_control(
			'member_image_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-image img, {{WRAPPER}} .pp-tm-overlay-content-wrap:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Name
         */
        $this->start_controls_section(
            'section_member_name_style',
            [
                'label'                 => __( 'Name', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'member_name_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-tm-name',
            ]
        );

        $this->add_control(
            'member_name_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-name' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
			'member_name_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
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
					'{{WRAPPER}} .pp-tm-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'name_divider_heading',
            [
                'label'                 => __( 'Divider', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
				'condition'             => [
					'member_title_divider' => 'yes',
				],
            ]
        );

        $this->add_control(
            'name_divider_color',
            [
                'label'                 => __( 'Divider Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'scheme'                => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_1,
				],
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-color: {{VALUE}}',
                ],
				'condition'             => [
					'member_title_divider' => 'yes',
				],
            ]
        );
        
        $this->add_control(
            'name_divider_style',
            [
                'label'                => __( 'Divider Style', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'solid',
                'options'              => [
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
                    'double'    => __( 'Double', 'power-pack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-style: {{VALUE}}',
                ],
				'condition'             => [
					'member_title_divider' => 'yes',
				],
            ]
        );
        
        $this->add_responsive_control(
			'name_divider_width',
			[
				'label'                 => __( 'Divider Width', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 100,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-title-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_title_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'name_divider_height',
			[
				'label'                 => __( 'Divider Height', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 4,
                ],
				'size_units'            => [ 'px' ],
				'range'                 => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_title_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'name_divider_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
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
					'{{WRAPPER}} .pp-tm-title-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_title_divider' => 'yes',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Position
         */
        $this->start_controls_section(
            'section_member_position_style',
            [
                'label'                 => __( 'Position', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'member_position_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-tm-position',
            ]
        );

        $this->add_control(
            'member_position_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-position' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
			'member_position_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
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
					'{{WRAPPER}} .pp-tm-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'position_divider_heading',
            [
                'label'                 => __( 'Divider', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
				'condition'             => [
					'member_position_divider' => 'yes',
				],
            ]
        );

        $this->add_control(
            'position_divider_color',
            [
                'label'                 => __( 'Divider Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'scheme'                => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_1,
				],
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-color: {{VALUE}}',
                ],
				'condition'             => [
					'member_position_divider' => 'yes',
				],
            ]
        );
        
        $this->add_control(
            'position_divider_style',
            [
                'label'                => __( 'Divider Style', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'solid',
                'options'              => [
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
                    'double'    => __( 'Double', 'power-pack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-style: {{VALUE}}',
                ],
				'condition'             => [
					'member_position_divider' => 'yes',
				],
            ]
        );
        
        $this->add_responsive_control(
			'position_divider_width',
			[
				'label'                 => __( 'Divider Width', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 100,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-position-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_position_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'position_divider_height',
			[
				'label'                 => __( 'Divider Height', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 4,
                ],
				'size_units'            => [ 'px' ],
				'range'                 => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_position_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'position_divider_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
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
					'{{WRAPPER}} .pp-tm-position-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_position_divider' => 'yes',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Description
         */
        $this->start_controls_section(
            'section_member_description_style',
            [
                'label'                 => __( 'Description', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
				],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'member_description_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-tm-description',
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
				],
            ]
        );

        $this->add_control(
            'member_description_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-description' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
				],
            ]
        );
        
        $this->add_responsive_control(
			'member_description_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
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
					'{{WRAPPER}} .pp-tm-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
				],
			]
		);
        
        $this->add_control(
            'description_divider_heading',
            [
                'label'                 => __( 'Divider', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
            ]
        );

        $this->add_control(
            'description_divider_color',
            [
                'label'                 => __( 'Divider Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'scheme'                => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_1,
				],
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-color: {{VALUE}}',
                ],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
            ]
        );
        
        $this->add_control(
            'description_divider_style',
            [
                'label'                => __( 'Divider Style', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'solid',
                'options'              => [
                    'solid'     => __( 'Solid', 'power-pack' ),
                    'dotted'    => __( 'Dotted', 'power-pack' ),
                    'dashed'    => __( 'Dashed', 'power-pack' ),
                    'double'    => __( 'Double', 'power-pack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-style: {{VALUE}}',
                ],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
            ]
        );
        
        $this->add_responsive_control(
			'description_divider_width',
			[
				'label'                 => __( 'Divider Width', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 100,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
				'range'                 => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-description-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'description_divider_height',
			[
				'label'                 => __( 'Divider Height', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 4,
                ],
				'size_units'            => [ 'px' ],
				'range'                 => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'description_divider_margin',
			[
				'label'                 => __( 'Margin Bottom', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 10,
                    'unit' => 'px',
                ],
				'size_units'            => [ 'px', '%' ],
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
					'{{WRAPPER}} .pp-tm-description-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Social Links
         */
        $this->start_controls_section(
            'section_member_social_links_style',
            [
                'label'                 => __( 'Social Links', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'member_icons_gap',
			[
				'label'                 => __( 'Icons Gap', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ '%', 'px' ],
				'range'                 => [
					'px' => [
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
					'{{WRAPPER}} .pp-tm-social-links li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'member_icon_size',
			[
				'label'                 => __( 'Icon Size', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px' ],
				'range'                 => [
					'px' => [
						'max' => 30,
					],
				],
				'default'    => [
					'size' => '14',
					'unit' => 'px',
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_links_style' );

        $this->start_controls_tab(
            'tab_links_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'member_links_icons_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'member_links_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'member_links_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'separator'             => 'before',
				'selector'              => '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap',
			]
		);

		$this->add_control(
			'member_links_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_links_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'separator'             => 'before',
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_links_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'member_links_icons_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'member_links_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'member_links_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();
    }
    
    protected function render_image() {
        $settings = $this->get_settings();
        $link_key = 'link';
        
        if ( ! empty( $settings['image']['url'] ) ) {
            if ( $settings['link_type'] == 'image' && $settings['link']['url'] != '' ) {
                printf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( $link_key ), Group_Control_Image_Size::get_attachment_image_html( $settings ) );
            } else {
                echo Group_Control_Image_Size::get_attachment_image_html( $settings );
            }
        }
    }
    
    protected function render_name() {
        $settings = $this->get_settings_for_display();
        
        $member_key = 'team_member_name';
        $link_key = 'link';
        
        $this->add_inline_editing_attributes( $member_key, 'none' );
        $this->add_render_attribute( $member_key, 'class', 'pp-tm-name' );

        if ( $settings[$member_key] != '' ) {
            if ( $settings['link_type'] == 'title' && $settings['link']['url'] != '' ) {
                printf( '<%1$s %2$s><a %3$s>%4$s</a></%1$s>', $settings['name_html_tag'], $this->get_render_attribute_string( $member_key ), $this->get_render_attribute_string( $link_key ), $settings['team_member_name'] );
            } else {
                printf( '<%1$s %2$s>%3$s</%1$s>', $settings['name_html_tag'], $this->get_render_attribute_string( $member_key ), $settings['team_member_name'] );
            }
        }
        
        if ( $settings['member_title_divider'] == 'yes' ) { ?>
            <div class="pp-tm-title-divider-wrap">
                <div class="pp-tm-divider pp-tm-title-divider"></div>
            </div>
            <?php
        }
    }
    
    protected function render_position() {
        $settings = $this->get_settings_for_display();
        $this->add_inline_editing_attributes( 'team_member_position', 'none' );
        $this->add_render_attribute( 'team_member_position', 'class', 'pp-tm-position' );

        if ( $settings['team_member_position'] != '' ) {
            printf( '<%1$s %2$s>%3$s</%1$s>', $settings['position_html_tag'], $this->get_render_attribute_string( 'team_member_position' ), $settings['team_member_position'] );
        }
        
        if ( $settings['member_position_divider'] == 'yes' ) { ?>
            <div class="pp-tm-position-divider-wrap">
                <div class="pp-tm-divider pp-tm-position-divider"></div>
            </div>
        <?php }
    }
    
    protected function render_description() {
        $settings = $this->get_settings_for_display();
        $this->add_inline_editing_attributes( 'team_member_description', 'basic' );
        $this->add_render_attribute( 'team_member_description', 'class', 'pp-tm-description' );
        
        if ( $settings['team_member_description_switch'] == 'yes' ) {
            if ( $settings['team_member_description'] != '' ) { ?>
                <div <?php echo $this->get_render_attribute_string( 'team_member_description' ); ?>>
                    <?php echo $this->parse_text_editor( $settings['team_member_description'] ); ?>
                </div>
            <?php } ?>
            <?php if ( $settings['member_description_divider'] == 'yes' ) { ?>
                <div class="pp-tm-description-divider-wrap">
                    <div class="pp-tm-divider pp-tm-description-divider"></div>
                </div>
            <?php }
        }
    }
    
    protected function render_social_links() {
        $settings = $this->get_settings_for_display();
        $i = 1;
        ?>
        <div class="pp-tm-social-links-wrap">
            <ul class="pp-tm-social-links">
                <?php foreach ( $settings['team_member_social'] as $index => $item ) : ?>
                    <?php
                        $this->add_render_attribute( 'social-link', 'class', 'pp-tm-social-link' );
                        $social_link_key = 'social-link' . $i;
                        if ( ! empty( $item['social_link']['url'] ) ) {
                            $this->add_render_attribute( $social_link_key, 'href', esc_url( $item['social_link']['url'] ) );

                            if ( $item['social_link']['is_external'] ) {
                                $this->add_render_attribute( $social_link_key, 'target', '_blank' );
                            }

                            if ( $item['social_link']['nofollow'] ) {
                                $this->add_render_attribute( $social_link_key, 'rel', 'nofollow' );
                            }
                        }
                    ?>
                    <li>
                        <?php
                            if ( $item['social_icon'] ) : ?>
                                <a <?php echo $this->get_render_attribute_string( $social_link_key ); ?>>
                                    <span class="pp-tm-social-icon-wrap">
                                        <span class="pp-tm-social-icon <?php echo esc_attr( $item['social_icon'] ); ?>" aria-hidden="true"></span>
                                    </span>
                                </a>
                            <?php endif;
                        ?>
                    </li>
                <?php $i++; endforeach; ?>
            </ul>
        </div>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $link_key = 'link';
        
        if ( $settings['link_type'] != 'none' ) {
            if ( ! empty( $settings['link']['url'] ) ) {
                $this->add_render_attribute( $link_key, 'href', esc_url( $settings['link']['url'] ) );

                if ( $settings['link']['is_external'] ) {
                    $this->add_render_attribute( $link_key, 'target', '_blank' );
                }

                if ( $settings['link']['nofollow'] ) {
                    $this->add_render_attribute( $link_key, 'rel', 'nofollow' );
                }
            }
        }
        ?>
        <div class="pp-tm-wrapper">
            <div class="pp-tm">
                <?php
                    if ( $settings['overlay_content'] == 'social_icons' ) { ?>
                        <div class="pp-tm-image"> 
                            <?php
                                // Image
                                $this->render_image();
                            ?>
                            <div class="pp-tm-overlay-content-wrap">
                                <div class="pp-tm-content">
                                    <?php
                                        if ( $settings['member_social_links'] == 'yes' ) {
                                            $this->render_social_links();
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
        
                    if ( $settings['overlay_content'] == 'all_content' ) { ?>
                        <div class="pp-tm-image"> 
                            <?php
                                // Image
                                $this->render_image();
                            ?>
                            <div class="pp-tm-overlay-content-wrap">
                                <div class="pp-tm-content">
                                    <?php
                                        if ( $settings['member_social_links'] == 'yes' ) {
                                            if ( $settings['social_links_position'] == 'before_desc' ) {
                                                $this->render_social_links();
                                            }
                                        }

                                        // Description
                                        $this->render_description();
            
                                        if ( $settings['member_social_links'] == 'yes' ) {
                                            if ( $settings['social_links_position'] == 'after_desc' ) {
                                                $this->render_social_links();
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="pp-tm-content pp-tm-content-normal">
                            <?php
                                // Name
                                $this->render_name();

                                // Position
                                $this->render_position();
                            ?>
                        </div>
                        <?php
                    }

                    if ( $settings['overlay_content'] != 'all_content' ) {
                        if ( $settings['overlay_content'] != 'social_icons' ) { ?>
                            <div class="pp-tm-image"> 
                                <?php
                                    // Image
                                    $this->render_image();
                                ?>
                            </div>
                            <?php
                        }
                    ?>
                    <div class="pp-tm-content pp-tm-content-normal">
                        <?php
                            // Name
                            $this->render_name();

                            // Position
                            $this->render_position();

                            if ( $settings['member_social_links'] == 'yes' && $settings['overlay_content'] == 'none' ) {
                                if ( $settings['social_links_position'] == 'before_desc' ) {
                                    $this->render_social_links();
                                }
                            }

                            // Description
                            $this->render_description();

                            if ( $settings['member_social_links'] == 'yes' && $settings['overlay_content'] == 'none' ) {
                                if ( $settings['social_links_position'] == 'after_desc' ) {
                                    $this->render_social_links();
                                }
                            }
                        ?>
                    </div><!-- .pp-tm-content -->
                <?php } ?>
            </div><!-- .pp-tm -->
        </div>
        <?php
    }
    
    protected function _name_template() {
        ?>
        <#
        if ( settings.team_member_name != '' ) {
            var name = settings.team_member_name;

            view.addRenderAttribute( 'team_member_name', 'class', 'pp-tm-name' );

            view.addInlineEditingAttributes( 'team_member_name' );

            var name_html = '<' + settings.name_html_tag  + ' ' + view.getRenderAttributeString( 'team_member_name' ) + '>' + name + '</' + settings.name_html_tag + '>';
        }
		#>
        <# if ( settings.link_type == 'title' && settings.link.url != '' ) { #>
            <#
            var target = settings.link.is_external ? ' target="_blank"' : '';
            var nofollow = settings.link.nofollow ? ' rel="nofollow"' : '';
            #>
            <a href="{{ settings.link.url }}"{{ target }}{{ nofollow }}>
                <# print( name_html ); #>
            </a>
        <# } else { #>
            <# print( name_html ); #>
        <# } #>
        
        <# if ( settings.member_title_divider == 'yes' ) { #>
            <div class="pp-tm-title-divider-wrap">
                <div class="pp-tm-divider pp-tm-title-divider"></div>
            </div>
        <# } #>
        <?php
    }

    protected function _image_template() {
        ?>
        <#
            if ( '' !== settings.image.url ) {
                var image = {
                    id: settings.image.id,
                    url: settings.image.url,
                    size: settings.image_size,
                    dimension: settings.image_custom_dimension,
                    model: view.getEditModel()
                };

                var image_url = elementor.imagesManager.getImageUrl( image );
            }
        #>
        <# if ( settings.image.url != '' ) { #>
            <# if ( settings.link_type == 'image' && settings.link.url != '' ) { #>
                <#
                var target = settings.link.is_external ? ' target="_blank"' : '';
                var nofollow = settings.link.nofollow ? ' rel="nofollow"' : '';
                #>
                <a href="{{ settings.link.url }}"{{ target }}{{ nofollow }}>
                    <img src="{{ image_url }}" alt="">
                </a>
            <# } else { #>
                <img src="{{ image_url }}" alt="">
            <# } #>
        <# } #>
        <?php
    }

    protected function _position_template() {
        ?>
        <#
        if ( settings.team_member_position != '' ) {
            var position = settings.team_member_position;

            view.addRenderAttribute( 'team_member_position', 'class', 'pp-tm-position' );

            view.addInlineEditingAttributes( 'team_member_position' );

            var position_html = '<' + settings.position_html_tag  + ' ' + view.getRenderAttributeString( 'team_member_position' ) + '>' + position + '</' + settings.position_html_tag + '>';

            print( position_html );
        }
		#>
        <# if ( settings.member_position_divider == 'yes' ) { #>
            <div class="pp-tm-position-divider-wrap">
                <div class="pp-tm-divider pp-tm-position-divider"></div>
            </div>
        <# } #>
        <?php
    }
    
    protected function _description_template() {
        ?>
        <# if ( settings.team_member_description_switch == 'yes' ) { #>
            <#
                if ( settings.team_member_description != '' ) {
                    var description = settings.team_member_description;

                    view.addRenderAttribute( 'team_member_description', 'class', 'pp-tm-description' );

                    view.addInlineEditingAttributes( 'team_member_description' );

                    var description_html = '<' + settings.position_html_tag  + ' ' + view.getRenderAttributeString( 'team_member_description' ) + '>' + description + '</' + settings.position_html_tag + '>';

                    print( description_html );
                }
            #>
            <# if ( settings.member_description_divider == 'yes' ) { #>
                <div class="pp-tm-description-divider-wrap">
                    <div class="pp-tm-divider pp-tm-description-divider"></div>
                </div>
            <# } #>
        <# } #>
        <?php
    }
    
    protected function _social_links_template() {
        ?>
        <div class="pp-tm-social-links-wrap">
            <ul class="pp-tm-social-links">
                <# _.each( settings.team_member_social, function( item ) { #>
                    <li>
                        <# if ( item.social_icon != '' ) { #>
                            <# if ( item.social_link.url != '' ) { #>
                                <a class="pp-tm-social-link" href="{{ item.social_link.url }}">
                            <# } #>
                                <span class="pp-tm-social-icon-wrap">
                                    <span class="pp-tm-social-icon {{ item.social_icon }}" aria-hidden="true"></span>
                                </span>
                            <# if ( item.social_link.url != '' ) { #>
                                </a>
                            <# } #>
                        <# } #>
                    </li>
                <# } ); #>
            </ul>
        </div>
        <?php
    }

    protected function _content_template() {
        ?>
        <div class="pp-tm-wrapper">
            <div class="pp-tm">
                <# if ( settings.overlay_content == 'social_icons' ) { #>
                    <div class="pp-tm-image"> 
                        <?php
                            // Image
                            $this->_image_template();
                        ?>
                        <div class="pp-tm-overlay-content-wrap">
                            <div class="pp-tm-content">
                                <# if ( settings.member_social_links == 'yes' ) { #>
                                    <?php $this->_social_links_template(); ?>
                                <# } #>
                            </div>
                        </div>
                    </div>
                <# } #>
                <# if ( settings.overlay_content == 'all_content' ) { #>
                    <div class="pp-tm-image"> 
                        <?php
                            // Image
                            $this->_image_template();
                        ?>
                        <div class="pp-tm-overlay-content-wrap">
                            <div class="pp-tm-content">
                                <# if ( settings.member_social_links == 'yes' ) { #>
                                    <# if ( settings.social_links_position == 'before_desc' ) { #>
                                        <?php $this->_social_links_template(); ?>
                                    <# } #>
                                <# } #>
                                <?php
                                    // Description
                                    $this->_description_template();
                                ?>
                                <# if ( settings.member_social_links == 'yes' ) { #>
                                    <# if ( settings.social_links_position == 'after_desc' ) { #>
                                        <?php $this->_social_links_template(); ?>
                                    <# } #>
                                <# } #>
                            </div>
                        </div>
                    </div>
                    <div class="pp-tm-content pp-tm-content-normal">
                        <?php
                            // Name
                            $this->_name_template();

                            // Position
                            $this->_position_template();
                        ?>
                    </div>
                <# } #>
                <# if ( settings.overlay_content != 'all_content' ) { #>
                    <# if ( settings.overlay_content != 'social_icons' ) { #>
                        <div class="pp-tm-image"> 
                            <?php
                                // Image
                                $this->_image_template();
                            ?>
                        </div>
                    <# } #>
                    <div class="pp-tm-content pp-tm-content-normal">
                        <?php
                            // Name
                            $this->_name_template();

                            // Position
                            $this->_position_template();
                        ?>
                        <# if ( settings.member_social_links == 'yes' && settings.overlay_content == 'none' ) { #>
                            <# if ( settings.social_links_position == 'before_desc' ) { #>
                                <?php $this->_social_links_template(); ?>
                            <# } #>
                        <# } #>
                        <?php
                            // Description
                            $this->_description_template();
                        ?>
                        <# if ( settings.member_social_links == 'yes' && settings.overlay_content == 'none' ) { #>
                            <# if ( settings.social_links_position == 'after_desc' ) { #>
                                <?php $this->_social_links_template(); ?>
                            <# } #>
                        <# } #>
                    </div><!-- .pp-tm-content -->
                <# } #>
            </div><!-- .pp-tm -->
        </div>
        <?php }
}