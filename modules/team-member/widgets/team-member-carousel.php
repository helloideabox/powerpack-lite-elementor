<?php
namespace PowerpackElements\Modules\TeamMember\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

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
 * Team Member Carousel Widget
 */
class Team_Member_Carousel extends Powerpack_Widget {
    
    /**
	 * Retrieve team member carousel widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-team-member-carousel';
    }

    /**
	 * Retrieve team member carousel widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Team Member Carousel', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the team member carousel widget belongs to.
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
	 * Retrieve team member carousel widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-team-member-carousel power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the team member carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'jquery-swiper',
            'powerpack-frontend'
        ];
    }

    /**
	 * Register team member carousel widget controls.
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
         * Content Tab: Team Members
         */
        $this->start_controls_section(
            'section_team_member',
            [
                'label'             => __( 'Team Members', 'power-pack' ),
            ]
        );
        
        $this->add_control(
			'team_member_details',
			[
				'label'             => '',
				'type'              => Controls_Manager::REPEATER,
				'default'           => [
					[
						'team_member_name'        => 'Team Member #1',
						'team_member_position'    => 'WordPress Developer',
						'facebook_url'            => '#',
						'twitter_url'             => '#',
						'google_plus_url'         => '#',
					],
					[
						'team_member_name'        => 'Team Member #2',
						'team_member_position'    => 'Web Designer',
						'facebook_url'            => '#',
						'twitter_url'             => '#',
						'google_plus_url'         => '#',
					],
					[
						'team_member_name'        => 'Team Member #3',
						'team_member_position'    => 'Testing Engineer',
						'facebook_url'            => '#',
						'twitter_url'             => '#',
						'google_plus_url'         => '#',
					],
				],
				'fields'            => [
					[
						'name'        => 'team_member_name',
						'label'       => __( 'Name', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'  => true,
                        ],
                        'default'     => __( 'John Doe', 'power-pack' ),
					],
                    [
						'name'        => 'team_member_position',
						'label'       => __( 'Position', 'power-pack' ),
                        'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'  => true,
                        ],
                        'default'     => __( 'WordPress Developer', 'power-pack' ),
					],
                    [
						'name'        => 'team_member_description',
						'label'       => __( 'Description', 'power-pack' ),
                        'type'        => Controls_Manager::TEXTAREA,
                        'dynamic'     => [
                            'active'  => true,
                        ],
                        'default'     => __( 'Enter member description here which describes the position of member in company', 'power-pack' ),
					],
                    [
						'name'        => 'team_member_image',
						'label'       => __( 'Image', 'power-pack' ),
                        'type'        => Controls_Manager::MEDIA,
                        'dynamic'     => [
                            'active'  => true,
                        ],
                        'default'     => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
					],
                    [
						'name'        => 'link_type',
                        'label'       => __( 'Link Type', 'power-pack' ),
                        'type'        => Controls_Manager::SELECT,
                        'default'     => 'none',
                        'options'     => [
                            'none'      => __( 'None', 'power-pack' ),
                            'image'     => __( 'Image', 'power-pack' ),
                            'title'     => __( 'Title', 'power-pack' ),
                        ],
                    ],
                    [
						'name'        => 'link',
                        'label'       => __( 'Link', 'power-pack' ),
                        'type'        => Controls_Manager::URL,
                        'dynamic'     => [
                            'active'        => true,
                            'categories'    => [
                                TagsModule::POST_META_CATEGORY,
                                TagsModule::URL_CATEGORY
                            ],
                        ],
                        'placeholder' => 'https://www.your-link.com',
                        'default'     => [
                            'url' => '#',
                        ],
                        'condition'   => [
                            'link_type!'   => 'none',
                        ],
                    ],
                    [
                        'name'        => 'social_links_heading',
                        'label'       => __('Social Links', 'power-pack'),
                        'type'        => Controls_Manager::HEADING,
                        'separator'   => 'before',
                    ],
					[
						'name'        => 'facebook_url',
						'label'       => __( 'Facebook', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'        => true,
                            'categories'    => [
                                TagsModule::POST_META_CATEGORY,
                            ],
                        ],
                        'description' => __( 'Enter Facebook page or profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'twitter_url',
						'label'       => __( 'Twitter', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'        => true,
                            'categories'    => [
                                TagsModule::POST_META_CATEGORY,
                            ],
                        ],
                        'description' => __( 'Enter Twitter profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'google_plus_url',
						'label'       => __( 'Google+', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'        => true,
                            'categories'    => [
                                TagsModule::POST_META_CATEGORY,
                            ],
                        ],
                        'description' => __( 'Enter Google+ profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'linkedin_url',
						'label'       => __( 'Linkedin', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'        => true,
                            'categories'    => [
                                TagsModule::POST_META_CATEGORY,
                            ],
                        ],
                        'description' => __( 'Enter Linkedin profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'instagram_url',
						'label'       => __( 'Instagram', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'        => true,
                            'categories'    => [
                                TagsModule::POST_META_CATEGORY,
                            ],
                        ],
                        'description' => __( 'Enter Instagram profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'youtube_url',
						'label'       => __( 'YouTube', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'        => true,
                            'categories'    => [
                                TagsModule::POST_META_CATEGORY,
                            ],
                        ],
                        'description' => __( 'Enter YouTube profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'pinterest_url',
						'label'       => __( 'Pinterest', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'        => true,
                            'categories'    => [
                                TagsModule::POST_META_CATEGORY,
                            ],
                        ],
                        'description' => __( 'Enter Pinterest profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'dribbble_url',
						'label'       => __( 'Dribbble', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'        => true,
                            'categories'    => [
                                TagsModule::POST_META_CATEGORY,
                            ],
                        ],
                        'description' => __( 'Enter Dribbble profile URL of team member', 'power-pack' ),
					],
					[
						'name'        => 'email',
						'label'       => __( 'Email', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'        => true,
                            'categories'    => [
                                TagsModule::POST_META_CATEGORY,
                            ],
                        ],
                        'description' => __( 'Enter email ID of team member', 'power-pack' ),
					],
					[
						'name'        => 'phone',
						'label'       => __( 'Contact Number', 'power-pack' ),
						'type'        => Controls_Manager::TEXT,
                        'dynamic'     => [
                            'active'        => true,
                            'categories'    => [
                                TagsModule::POST_META_CATEGORY,
                            ],
                        ],
                        'description' => __( 'Enter contact number of team member', 'power-pack' ),
					],
				],
				'title_field'       => '{{{ team_member_name }}}',
			]
		);
        
        $this->add_control(
            'member_social_links',
            [
                'label'             => __( 'Show Social Icons', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Team Member Settings
         */
        $this->start_controls_section(
            'section_member_box_settings',
            [
                'label'             => __( 'Team Member Settings', 'power-pack' ),
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
                'label'                => __( 'Social Icons Position', 'power-pack' ),
                'type'                 => Controls_Manager::SELECT,
                'default'              => 'after_desc',
                'options'              => [
                    'before_desc'      => __( 'Before Description', 'power-pack' ),
                    'after_desc'       => __( 'After Description', 'power-pack' ),
                ],
				'condition'             => [
					'member_social_links' => 'yes',
				],
                'separator'             => 'before',
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
                'separator'             => 'before',
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
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'member_position_divider',
            [
                'label'             => __( 'Divider after Position', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'hide',
                'label_on'          => __( 'Show', 'power-pack' ),
                'label_off'         => __( 'Hide', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'member_description_divider',
            [
                'label'             => __( 'Divider after Description', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'hide',
                'label_on'          => __( 'Show', 'power-pack' ),
                'label_off'         => __( 'Hide', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->end_controls_section();

        /**
         * Content Tab: Slider Settings
         */
        $this->start_controls_section(
            'section_slider_settings',
            [
                'label'                 => __( 'Slider Settings', 'power-pack' ),
            ]
        );
        
        $this->add_responsive_control(
            'items',
            [
                'label'                 => __( 'Visible Items', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 3 ],
                'tablet_default'        => [ 'size' => 2 ],
                'mobile_default'        => [ 'size' => 1 ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 10,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
            ]
        );
        
        $this->add_responsive_control(
            'margin',
            [
                'label'                 => __( 'Items Gap', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 10 ],
                'tablet_default'        => [ 'size' => 10 ],
                'mobile_default'        => [ 'size' => 10 ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label'                 => __( 'Autoplay', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'autoplay_speed',
            [
                'label'                 => __( 'Autoplay Speed', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 2000 ],
                'range'                 => [
                    'px' => [
                        'min'   => 500,
                        'max'   => 5000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'condition'         => [
                    'autoplay'      => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'infinite_loop',
            [
                'label'             => __( 'Infinite Loop', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'grab_cursor',
            [
                'label'                 => __( 'Grab Cursor', 'power-pack' ),
                'description'           => __( 'Shows grab cursor when you hover over the slider', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'yes',
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'name_navigation_heading',
            [
                'label'                 => __( 'Navigation', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'arrows',
            [
                'label'             => __( 'Arrows', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'dots',
            [
                'label'             => __( 'Dots', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'pagination_type',
            [
                'label'                 => __( 'Pagination Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'bullets',
                'options'               => [
                    'bullets'       => __( 'Dots', 'power-pack' ),
                    'fraction'      => __( 'Fraction', 'power-pack' ),
                ],
                'condition'             => [
                    'dots'          => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'direction',
            [
                'label'                 => __( 'Direction', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'left',
                'options'               => [
                    'left'       => __( 'Left', 'power-pack' ),
                    'right'      => __( 'Right', 'power-pack' ),
                ],
				'separator'             => 'before',
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
                'label'             => __( 'Box Style', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'member_box_alignment',
            [
                'label'             => __( 'Alignment', 'power-pack' ),
				'type'              => Controls_Manager::CHOOSE,
				'options'           => [
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
				'default'           => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-tm' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Content
         */
        $this->start_controls_section(
            'section_member_content_style',
            [
                'label'             => __( 'Content', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'member_box_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-content-normal' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'member_box_border',
				'label'             => __( 'Border', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'separator'         => 'before',
				'selector'          => '{{WRAPPER}} .pp-tm-content-normal',
			]
		);

		$this->add_control(
			'member_box_border_radius',
			[
				'label'             => __( 'Border Radius', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-content-normal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_box_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', 'em', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-content-normal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'pa_member_box_shadow',
				'selector'          => '{{WRAPPER}} .pp-tm-content-normal',
				'separator'         => 'before',
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Overlay
         */
        $this->start_controls_section(
            'section_member_overlay_style',
            [
                'label'             => __( 'Overlay', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
				'condition'         => [
					'overlay_content!' => 'none',
				],
            ]
        );
        
        $this->add_responsive_control(
            'overlay_alignment',
            [
                'label'             => __( 'Alignment', 'power-pack' ),
				'type'              => Controls_Manager::CHOOSE,
				'options'           => [
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
				'default'           => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-overlay-content-wrap' => 'text-align: {{VALUE}};',
				],
				'condition'         => [
					'overlay_content!' => 'none',
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'overlay_background',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .pp-tm-overlay-content-wrap:before',
				'condition'         => [
					'overlay_content!' => 'none',
				],
			]
		);
        
        $this->add_control(
			'overlay_opacity',
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
					'{{WRAPPER}} .pp-tm-overlay-content-wrap:before' => 'opacity: {{SIZE}};',
				],
				'condition'         => [
					'overlay_content!' => 'none',
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
                'label'             => __( 'Image', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'member_image_width',
			[
				'label'             => __( 'Image Width', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'size_units'        => [ '%', 'px' ],
				'range'             => [
					'px' => [
						'max' => 1200,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'member_image_border',
				'label'             => __( 'Border', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'selector'          => '{{WRAPPER}} .pp-tm-image img',
			]
		);

		$this->add_control(
			'member_image_border_radius',
			[
				'label'             => __( 'Border Radius', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-image img, {{WRAPPER}} .pp-tm-overlay-content-wrap:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'member_image_margin',
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
					'{{WRAPPER}} .pp-tm-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
                'label'             => __( 'Name', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'member_name_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-tm-name',
            ]
        );

        $this->add_control(
            'member_name_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
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
                'label'             => __( 'Divider', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
				'condition'         => [
					'member_title_divider' => 'yes',
				],
            ]
        );

        $this->add_control(
            'name_divider_color',
            [
                'label'             => __( 'Divider Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'scheme'            => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_1,
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-color: {{VALUE}}',
                ],
				'condition'         => [
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
				'condition'         => [
					'member_title_divider' => 'yes',
				],
            ]
        );
        
        $this->add_responsive_control(
			'name_divider_width',
			[
				'label'             => __( 'Divider Width', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 100,
                    'unit' => 'px',
                ],
				'size_units'        => [ 'px', '%' ],
				'range'             => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-title-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_title_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'name_divider_height',
			[
				'label'             => __( 'Divider Height', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 4,
                ],
				'size_units'        => [ 'px' ],
				'range'             => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
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
				'condition'         => [
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
                'label'             => __( 'Position', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'member_position_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-tm-position',
            ]
        );

        $this->add_control(
            'member_position_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
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
                'label'             => __( 'Divider', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
				'condition'         => [
					'member_position_divider' => 'yes',
				],
            ]
        );

        $this->add_control(
            'position_divider_color',
            [
                'label'             => __( 'Divider Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'scheme'            => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_1,
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-color: {{VALUE}}',
                ],
				'condition'         => [
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
				'condition'         => [
					'member_position_divider' => 'yes',
				],
            ]
        );
        
        $this->add_responsive_control(
			'position_divider_width',
			[
				'label'             => __( 'Divider Width', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 100,
                    'unit' => 'px',
                ],
				'size_units'        => [ 'px', '%' ],
				'range'             => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-position-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_position_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'position_divider_height',
			[
				'label'             => __( 'Divider Height', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 4,
                ],
				'size_units'        => [ 'px' ],
				'range'             => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
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
				'condition'         => [
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
                'label'             => __( 'Description', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'member_description_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-tm-description',
            ]
        );

        $this->add_control(
            'member_description_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-description' => 'color: {{VALUE}}',
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
			]
		);
        
        $this->add_control(
            'description_divider_heading',
            [
                'label'             => __( 'Divider', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
				'condition'         => [
					'member_description_divider' => 'yes',
				],
            ]
        );

        $this->add_control(
            'description_divider_color',
            [
                'label'             => __( 'Divider Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'scheme'            => [
					'type'     => Scheme_Color::get_type(),
					'value'    => Scheme_Color::COLOR_1,
				],
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-color: {{VALUE}}',
                ],
				'condition'         => [
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
				'condition'         => [
					'member_description_divider' => 'yes',
				],
            ]
        );
        
        $this->add_responsive_control(
			'description_divider_width',
			[
				'label'             => __( 'Divider Width', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 100,
                    'unit' => 'px',
                ],
				'size_units'        => [ 'px', '%' ],
				'range'             => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-description-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
					'member_description_divider' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'description_divider_height',
			[
				'label'             => __( 'Divider Height', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [
                    'size' => 4,
                ],
				'size_units'        => [ 'px' ],
				'range'             => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'         => [
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
				'condition'         => [
					'member_description_divider' => 'yes',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Social Icons
         */
        $this->start_controls_section(
            'section_member_social_links_style',
            [
                'label'             => __( 'Social Icons', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'member_icons_gap',
			[
				'label'             => __( 'Icons Gap', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'default'           => [ 'size' => 10 ],
				'size_units'        => [ '%', 'px' ],
				'range'             => [
					'px' => [
						'max' => 60,
					],
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'selectors'         => [
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
                'label'             => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'member_links_icons_color',
            [
                'label'             => __( 'Icons Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'member_links_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'              => 'member_links_border',
				'label'             => __( 'Border', 'power-pack' ),
				'placeholder'       => '1px',
				'default'           => '1px',
				'separator'         => 'before',
				'selector'          => '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap',
			]
		);

		$this->add_control(
			'member_links_border_radius',
			[
				'label'             => __( 'Border Radius', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_links_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', 'em', '%' ],
				'separator'         => 'before',
				'selectors'         => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_links_hover',
            [
                'label'             => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'member_links_icons_color_hover',
            [
                'label'             => __( 'Icons Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'member_links_bg_color_hover',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'member_links_border_color_hover',
            [
                'label'             => __( 'Border Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Arrows
         */
        $this->start_controls_section(
            'section_arrows_style',
            [
                'label'                 => __( 'Arrows', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'arrows'        => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'arrow',
            [
                'label'                 => __( 'Choose Arrow', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'label_block'           => true,
                'default'               => 'fa fa-angle-right',
                'include'               => [
                    'fa fa-angle-right',
                    'fa fa-angle-double-right',
                    'fa fa-chevron-right',
                    'fa fa-chevron-circle-right',
                    'fa fa-arrow-right',
                    'fa fa-long-arrow-right',
                    'fa fa-caret-right',
                    'fa fa-caret-square-o-right',
                    'fa fa-arrow-circle-right',
                    'fa fa-arrow-circle-o-right',
                    'fa fa-toggle-right',
                    'fa fa-hand-o-right',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'arrows_size',
            [
                'label'                 => __( 'Arrows Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => '22' ],
                'range'                 => [
                    'px' => [
                        'min'   => 15,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
				],
            ]
        );
        
        $this->add_responsive_control(
            'left_arrow_position',
            [
                'label'                 => __( 'Align Left Arrow', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
            ]
        );
        
        $this->add_responsive_control(
            'right_arrow_position',
            [
                'label'                 => __( 'Align Right Arrow', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_arrows_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_normal',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'arrows_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev',
                'separator'             => 'before',
			]
		);

		$this->add_control(
			'arrows_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrows_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'arrows_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'arrows_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-button-next:hover, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-button-next, {{WRAPPER}} .swiper-container-wrap .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'             => 'before',
			]
		);
        
        $this->end_controls_section();
        
        /**
         * Style Tab: Dots
         */
        $this->start_controls_section(
            'section_dots_style',
            [
                'label'                 => __( 'Pagination: Dots', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );

        $this->add_control(
            'dots_position',
            [
                'label'                 => __( 'Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'inside'     => __( 'Inside', 'power-pack' ),
                   'outside'    => __( 'Outside', 'power-pack' ),
                ],
                'default'               => 'outside',
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dots_size',
            [
                'label'                 => __( 'Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 2,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dots_spacing',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );

        $this->add_control(
            'dots_color_normal',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );

        $this->add_control(
            'active_dot_color_normal',
            [
                'label'                 => __( 'Active Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'dots_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
			]
		);

		$this->add_control(
			'dots_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
			]
		);

		$this->add_responsive_control(
			'dots_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
                'allowed_dimensions'    => 'vertical',
				'placeholder'           => [
					'top'      => '',
					'right'    => 'auto',
					'bottom'   => '',
					'left'     => 'auto',
				],
				'selectors'             => [
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );

        $this->add_control(
            'dots_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
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
                    '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
                ],
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'bullets',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Pagination: Fraction
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_fraction_style',
            [
                'label'                 => __( 'Pagination: Fraction', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'fraction',
                ],
            ]
        );

        $this->add_control(
            'fraction_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}};',
                ],
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'fraction',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'fraction_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .swiper-pagination-fraction',
                'condition'             => [
                    'dots'              => 'yes',
                    'pagination_type'   => 'fraction',
                ],
            ]
        );
        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $image = $this->get_settings( 'member_image' );

        $this->add_render_attribute( 'team-member-carousel-wrap', 'class', 'swiper-container-wrap pp-team-member-carousel-wrap' );

        if ( $settings['dots_position'] ) {
            $this->add_render_attribute( 'team-member-carousel-wrap', 'class', 'swiper-container-wrap-dots-' . $settings['dots_position'] );
        }
        
        $this->add_render_attribute(
            'team-member-carousel',
            [
                'class'             => ['swiper-container', 'pp-tm-wrapper', 'pp-tm-carousel'],
                'id'                => 'swiper-container-'.esc_attr( $this->get_id() ),
                'data-pagination'   => '.swiper-pagination-'.esc_attr( $this->get_id() ),
                'data-arrow-next'   => '.swiper-button-next-'.esc_attr( $this->get_id() ),
                'data-arrow-prev'   => '.swiper-button-prev-'.esc_attr( $this->get_id() ),
                'data-id'           => 'swiper-container-'.esc_attr( $this->get_id() )
            ]
        );

        if ( $settings['dots_position'] ) {
            $this->add_render_attribute( 'team-member-carousel', 'class', 'pp-tm-carousel-dots-' . $settings['dots_position'] );
        }
        
        if ( $settings['direction'] == 'right' ) {
            $this->add_render_attribute( 'team-member-carousel', 'dir', 'rtl' );
        }
        
        if ( ! empty( $settings['items']['size'] ) ) {
            $this->add_render_attribute( 'team-member-carousel', 'data-items', $settings['items']['size'] );
        }
        if ( ! empty( $settings['items_tablet']['size'] ) ) {
            $this->add_render_attribute( 'team-member-carousel', 'data-items-tablet', $settings['items_tablet']['size'] );
        }
        if ( ! empty( $settings['items_mobile']['size'] ) ) {
            $this->add_render_attribute( 'team-member-carousel', 'data-items-mobile', $settings['items_mobile']['size'] );
        }
        if ( ! empty( $settings['margin']['size'] ) ) {
            $this->add_render_attribute( 'team-member-carousel', 'data-margin', $settings['margin']['size'] );
        }
        if ( ! empty( $settings['margin_tablet']['size'] ) ) {
            $this->add_render_attribute( 'team-member-carousel', 'data-margin-tablet', $settings['margin_tablet']['size'] );
        }
        if ( ! empty( $settings['margin_mobile']['size'] ) ) {
            $this->add_render_attribute( 'team-member-carousel', 'data-margin-mobile', $settings['margin_mobile']['size'] );
        }
        if ( $settings['autoplay'] == 'yes' && ! empty( $settings['autoplay_speed']['size'] ) ) {
            $this->add_render_attribute( 'team-member-carousel', 'data-autoplay', $settings['autoplay_speed']['size'] );
        } else {
            $this->add_render_attribute( 'team-member-carousel', 'data-autoplay', '999999' );
        }
        if ( $settings['infinite_loop'] == 'yes' ) {
            $this->add_render_attribute( 'team-member-carousel', 'data-loop', '1' );
        }
        if ( $settings['grab_cursor'] == 'yes' ) {
            $this->add_render_attribute( 'team-member-carousel', 'data-grab-cursor', '1' );
        }
        if ( $settings['arrows'] == 'yes' ) {
            $this->add_render_attribute( 'team-member-carousel', 'data-arrows', '1' );
        }
        if ( $settings['dots'] == 'yes' ) {
            $this->add_render_attribute( 'team-member-carousel', 'data-pagination-type', $settings['pagination_type'] );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'team-member-carousel-wrap' ); ?>>
            <div <?php echo $this->get_render_attribute_string( 'team-member-carousel' ); ?>>
                    <div class="swiper-wrapper">
                    <?php foreach ( $settings['team_member_details'] as $index => $item ) : ?>
                    <div class="swiper-slide">
                        <div class="pp-tm">
                            <div class="pp-tm-image"> 
                                <?php
                                    if ( $item['team_member_image']['url'] != '' ) {
                                        if ( $item['link_type'] == 'image' && $item['link']['url'] != '' ) {
                                            
                                            $link_key = $this->get_repeater_setting_key( 'link', 'team_member_image', $index );
                                            
                                            $this->add_render_attribute( $link_key, 'href', esc_url( $item['link']['url'] ) );

                                            if ( $item['link']['is_external'] ) {
                                                $this->add_render_attribute( $link_key, 'target', '_blank' );
                                            }

                                            if ( $item['link']['nofollow'] ) {
                                                $this->add_render_attribute( $link_key, 'rel', 'nofollow' );
                                            }
                                            
                                            printf( '<a %1$s><img src="%2$s"></a>', $this->get_render_attribute_string( $link_key ), $item['team_member_image']['url'] );
                                            
                                        } else {
                                            
                                            echo '<img src="' . $item['team_member_image']['url'] . '">';
                                            
                                        }
                                    }
                                ?>

                                <?php if ( $settings['overlay_content'] == 'social_icons' ) { ?>
                                    <div class="pp-tm-overlay-content-wrap">
                                        <div class="pp-tm-content">
                                            <?php
                                                if ( $settings['member_social_links'] == 'yes' ) {
                                                    $this->member_social_links( $item );
                                                }
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>

                                <?php if ( $settings['overlay_content'] == 'all_content' ) { ?>
                                    <div class="pp-tm-overlay-content-wrap">
                                        <div class="pp-tm-content">
                                            <?php
                                                if ( $settings['member_social_links'] == 'yes' ) {
                                                    if ( $settings['social_links_position'] == 'before_desc' ) {
                                                        $this->member_social_links( $item );
                                                    }
                                                }
                                            ?>
                                            <?php $this->render_description( $item ); ?>
                                            <?php
                                                if ( $settings['member_social_links'] == 'yes' ) {
                                                    if ( $settings['social_links_position'] == 'after_desc' ) {
                                                        $this->member_social_links( $item );
                                                    }
                                                }
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php if ( $settings['overlay_content'] == 'all_content' ) { ?>
                                <div class="pp-tm-content pp-tm-content-normal">
                                    <?php
                                        // Name
                                        $this->render_name( $item, $index );

                                        // Position
                                        $this->render_position( $item );
                                    ?>
                                </div>
                            <?php } ?>
                            <?php if ( $settings['overlay_content'] != 'all_content' ) { ?>
                                <div class="pp-tm-content pp-tm-content-normal">
                                    <?php
                                        $this->render_name( $item, $index );
                                    ?>
                                    <?php $this->render_position( $item ); ?>
                                    <?php
                                        if ( $settings['member_social_links'] == 'yes' && $settings['overlay_content'] == 'none' ) {
                                            if ( $settings['social_links_position'] == 'before_desc' ) {
                                                $this->member_social_links( $item );
                                            }
                                        }
                                    ?>
                                    <?php $this->render_description( $item ); ?>
                                    <?php
                                        if ( $settings['member_social_links'] == 'yes' && $settings['overlay_content'] == 'none' ) {
                                            if ( $settings['social_links_position'] == 'after_desc' ) {
                                                $this->member_social_links( $item );
                                            }
                                        }
                                    ?>
                                </div><!-- .pp-tm-content -->
                            <?php } ?>
                        </div><!-- .pp-tm -->
                    </div><!-- .swiper-slide -->
                <?php endforeach; ?>
                </div>
            </div>
            <?php
                $this->render_dots();

                $this->render_arrows();
            ?>
        </div>
        <?php
    }
    
    protected function render_name( $item, $index ) {
        $settings = $this->get_settings_for_display();
        
        if ( $item['team_member_name'] == '' ) {
            return;
        }
        
        $member_key = $this->get_repeater_setting_key( 'team_member_name', 'team_member_details', $index );
        $link_key = $this->get_repeater_setting_key( 'link', 'team_member_details', $index );
        
        $this->add_render_attribute( $member_key, 'class', 'pp-tm-name' );
        
        if ( $item['link_type'] == 'title' && $item['link']['url'] != '' ) {
            if ( ! empty( $item['link']['url'] ) ) {
                $this->add_render_attribute( $link_key, 'href', esc_url( $item['link']['url'] ) );

                if ( $item['link']['is_external'] ) {
                    $this->add_render_attribute( $link_key, 'target', '_blank' );
                }

                if ( $item['link']['nofollow'] ) {
                    $this->add_render_attribute( $link_key, 'rel', 'nofollow' );
                }
            }

            printf( '<%1$s class="pp-tm-name"><a %3$s>%4$s</a></%1$s>', $settings['name_html_tag'], $this->get_render_attribute_string( $member_key ), $this->get_render_attribute_string( $link_key ), $item['team_member_name'] );

        } else {

            printf( '<%1$s class="pp-tm-name">%2$s</%1$s>', $settings['name_html_tag'], $item['team_member_name'] );

        }
        ?>
        <?php if ( $settings['member_title_divider'] == 'yes' ) { ?>
            <div class="pp-tm-title-divider-wrap">
                <div class="pp-tm-divider pp-tm-title-divider"></div>
            </div>
        <?php }
    }
    
    protected function render_position( $item ) {
        $settings = $this->get_settings_for_display();
        
        if ( $item['team_member_position'] != '' ) {
                printf( '<%1$s class="pp-tm-position">%2$s</%1$s>', $settings['position_html_tag'], $item['team_member_position'] );
            }
        ?>
        <?php if ( $settings['member_position_divider'] == 'yes' ) { ?>
            <div class="pp-tm-position-divider-wrap">
                <div class="pp-tm-divider pp-tm-position-divider"></div>
            </div>
        <?php }
    }
    
    protected function render_description( $item ) {
        $settings = $this->get_settings_for_display();
        if ( $item['team_member_description'] != '' ) { ?>
            <div class="pp-tm-description">
                <?php echo $this->parse_text_editor( $item['team_member_description'] ); ?>
            </div>
        <?php } ?>
        <?php if ( $settings['member_description_divider'] == 'yes' ) { ?>
            <div class="pp-tm-description-divider-wrap">
                <div class="pp-tm-divider pp-tm-description-divider"></div>
            </div>
        <?php }
    }
    
    private function member_social_links( $item ) {
        $pp_social_links = array();
        
        ( $item['facebook_url'] ) ? $pp_social_links['facebook'] = $item['facebook_url'] : '';
        ( $item['twitter_url'] ) ? $pp_social_links['twitter'] = $item['twitter_url'] : '';
        ( $item['google_plus_url'] ) ? $pp_social_links['google-plus'] = $item['google_plus_url'] : '';
        ( $item['linkedin_url'] ) ? $pp_social_links['linkedin'] = $item['linkedin_url'] : '';
        ( $item['instagram_url'] ) ? $pp_social_links['instagram'] = $item['instagram_url'] : '';
        ( $item['youtube_url'] ) ? $pp_social_links['youtube'] = $item['youtube_url'] : '';
        ( $item['pinterest_url'] ) ? $pp_social_links['pinterest'] = $item['pinterest_url'] : '';
        ( $item['dribbble_url'] ) ? $pp_social_links['dribbble'] = $item['dribbble_url'] : '';
        ( $item['email'] ) ? $pp_social_links['envelope-o'] = $item['email'] : '';
        ( $item['phone'] ) ? $pp_social_links['phone'] = $item['phone'] : '';
        ?>
        <div class="pp-tm-social-links-wrap">
            <ul class="pp-tm-social-links">
                <?php
                    foreach( $pp_social_links as $icon_id => $icon_url ) {
                        if ( $icon_url ) {
                            if ( $icon_id == 'envelope-o' ) {
                                printf( '<li><a href="mailto:%1$s"><span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-'. esc_attr( $icon_id ).'"></span></span></a></li>', sanitize_email( $icon_url )  );
                            } else if ( $icon_id == 'phone' ) {
                                printf( '<li><a href="tel:%1$s"><span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-'. esc_attr( $icon_id ).'"></span></span></a></li>', $icon_url  );
                            } else {
                                printf( '<li><a href="%1$s"><span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-'. esc_attr( $icon_id ).'"></span></span></a></li>', esc_url( $icon_url )  );
                            }
                        }
                    }
                ?>
            </ul>
        </div>
        <?php
    }

    /**
	 * Render team member carousel dots output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_dots() {
        $settings = $this->get_settings_for_display();

        if ( $settings['dots'] == 'yes' ) { ?>
            <!-- Add Pagination -->
            <div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ); ?>"></div>
        <?php }
    }

    /**
	 * Render team member carousel arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_arrows() {
        $settings = $this->get_settings_for_display();

        if ( $settings['arrows'] == 'yes' ) { ?>
            <?php
                if ( $settings['arrow'] ) {
                    $pa_next_arrow = $settings['arrow'];
                    $pa_prev_arrow = str_replace("right","left",$settings['arrow']);
                }
                else {
                    $pa_next_arrow = 'fa fa-angle-right';
                    $pa_prev_arrow = 'fa fa-angle-left';
                }
            ?>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button-next-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
            </div>
            <div class="swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->get_id() ); ?>">
                <i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
            </div>
        <?php }
    }
    
    protected function _name_template() {
        ?>
        <# if ( item.team_member_name != '' ) { #>
        <#
            var name = item.team_member_name;

            view.addRenderAttribute( 'team_member_name', 'class', 'pp-tm-name' );

            var name_html = '<' + settings.name_html_tag  + ' ' + view.getRenderAttributeString( 'team_member_name' ) + '>' + name + '</' + settings.name_html_tag + '>';
           
            if ( item.link_type == 'title' && item.link.url != '' ) {

                var target = item.link.is_external ? ' target="_blank"' : '';
                var nofollow = item.link.nofollow ? ' rel="nofollow"' : '';
                #>
                <a href="{{ item.link.url }}"{{ target }}{{ nofollow }}>
                    <# print( name_html ); #>
                </a>
                <# 
            } else {
                print( name_html );
            }
        } #>
        
        <# if ( settings.member_title_divider == 'yes' ) { #>
            <div class="pp-tm-title-divider-wrap">
                <div class="pp-tm-divider pp-tm-title-divider"></div>
            </div>
        <# } #>
        <?php
    }

    protected function _position_template() {
        ?>
        <#
        if ( item.team_member_position != '' ) {
            var position = item.team_member_position;

            view.addRenderAttribute( 'team_member_position', 'class', 'pp-tm-position' );

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
            <#
                if ( item.team_member_description != '' ) {
                    var description = item.team_member_description;

                    view.addRenderAttribute( 'team_member_description', 'class', 'pp-tm-description' );

                    var description_html = '<div' + ' ' + view.getRenderAttributeString( 'team_member_description' ) + '>' + description + '</div>';

                    print( description_html );
                }
            #>
            <# if ( settings.member_description_divider == 'yes' ) { #>
                <div class="pp-tm-description-divider-wrap">
                    <div class="pp-tm-divider pp-tm-description-divider"></div>
                </div>
            <# } #>
        <?php
    }
    
    protected function _member_social_links_template() {
        ?>
        <#
        var facebook_url       = item.facebook_url,
            twitter_url        = item.twitter_url,
            google_plus_url    = item.google_plus_url,
            linkedin_url       = item.linkedin_url,
            instagram_url      = item.instagram_url,
            youtube_url        = item.youtube_url,
            pinterest_url      = item.pinterest_url,
            dribbble_url       = item.dribbble_url;
            email              = item.email;
            phone              = item.phone;
        #>
        <div class="pp-tm-social-links-wrap">
            <ul class="pp-tm-social-links">
                <# if ( facebook_url ) { #>
                    <li>
                        <a href="{{ facebook_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-facebook"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( twitter_url ) { #>
                    <li>
                        <a href="{{ twitter_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-twitter"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( google_plus_url ) { #>
                    <li>
                        <a href="{{ google_plus_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-google-plus"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( linkedin_url ) { #>
                    <li>
                        <a href="{{ linkedin_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-linkedin"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( instagram_url ) { #>
                    <li>
                        <a href="{{ instagram_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-instagram"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( youtube_url ) { #>
                    <li>
                        <a href="{{ youtube_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-youtube"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( pinterest_url ) { #>
                    <li>
                        <a href="{{ pinterest_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-pinterest"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( dribbble_url ) { #>
                    <li>
                        <a href="{{ dribbble_url }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-dribbble"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( email ) { #>
                    <li>
                        <a href="mailto:{{ email }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-envelope-o"></span></span>
                        </a>
                    </li>
                <# } #>
                <# if ( phone ) { #>
                    <li>
                        <a href="tel:{{ phone }}">
                            <span class="pp-tm-social-icon-wrap"><span class="pp-tm-social-icon fa fa-phone"></span></span>
                        </a>
                    </li>
                <# } #>
            </ul>
        </div>
        <?php
    }

    /**
	 * Render team member carousel dots widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _dots_template() {
        ?>
        <# if ( settings.dots == 'yes' ) { #>
            <div class="swiper-pagination"></div>
        <# } #>
        <?php
    }

    /**
	 * Render team member carousel arrows widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _arrows_template() {
        ?>
        <# if ( settings.arrows == 'yes' ) { #>
            <#
                if ( settings.arrow != '' ) {
                    var pp_next_arrow = settings.arrow;
                    var pp_prev_arrow = pp_next_arrow.replace('right', "left");
                }
                else {
                    var pp_next_arrow = 'fa fa-angle-right';
                    var pp_prev_arrow = 'fa fa-angle-left';
                }
            #>
            <div class="swiper-button-next">
                <i class="{{ pp_next_arrow }}"></i>
            </div>
            <div class="swiper-button-prev">
                <i class="{{ pp_prev_arrow }}"></i>
            </div>
        <# } #>
        <?php
    }

    protected function _content_template() {
        ?>
        <#
           var i               = 1;

           view.addRenderAttribute(
                'container',
                {
                    'class': [ 'swiper-container', 'pp-tm-wrapper', 'pp-tm-carousel' ],
                }
           );
           
           if ( settings.items.size != '' ) {
                view.addRenderAttribute( 'container', 'data-items', settings.items.size );
           }
           
           if ( settings.items_tablet.size != '' ) {
                view.addRenderAttribute( 'container', 'data-items-tablet', settings.items_tablet.size );
           }
           
           if ( settings.items_mobile.size != '' ) {
                view.addRenderAttribute( 'container', 'data-items-mobile', settings.items_mobile.size );
           }
           
           if ( settings.margin.size != '' ) {
                view.addRenderAttribute( 'container', 'data-margin', settings.margin.size );
           }
           
           if ( settings.margin_tablet.size != '' ) {
                view.addRenderAttribute( 'container', 'data-margin-tablet', settings.margin_tablet.size );
           }
           
           if ( settings.margin_mobile.size != '' ) {
                view.addRenderAttribute( 'container', 'data-margin-mobile', settings.margin_mobile.size );
           }
           
           if ( settings.autoplay == 'yes' && settings.autoplay_speed.size != '' ) {
                view.addRenderAttribute( 'container', 'data-autoplay', settings.autoplay_speed.size );
           }
           
           if ( settings.infinite_loop == 'yes' ) {
                view.addRenderAttribute( 'container', 'data-loop', '1' );
           }
           
           if ( settings.grab_cursor == 'yes' ) {
                view.addRenderAttribute( 'container', 'data-grab-cursor', '1' );
           }
           
           if ( settings.arrows == 'yes' ) {
                view.addRenderAttribute( 'container', 'data-arrows', settings.arrows );
           }
           
           if ( settings.dots == 'yes' ) {
                view.addRenderAttribute( 'container', 'data-pagination-type', settings.pagination_type );
           }
           
           if ( settings.direction == 'right' ) {
                view.addRenderAttribute( 'container', 'dir', 'rtl' );
           }
        #>
        <div class="swiper-container-wrap pp-team-member-carousel-wrap swiper-container-wrap-dots-{{ settings.dots_position }}">
            <div {{{ view.getRenderAttributeString( 'container' ) }}}>
                <div class="swiper-wrapper">
                    <# _.each( settings.team_member_details, function( item ) { #>
                        <div class="swiper-slide">
                            <div class="pp-tm">
                                <div class="pp-tm-image">
                                    <# if ( item.team_member_image.url != '' ) { #>
                                        <# if ( item.link_type == 'image' && item.link.url != '' ) { #>
                                            <#
                                            var target = item.link.is_external ? ' target="_blank"' : '';
                                            var nofollow = item.link.nofollow ? ' rel="nofollow"' : '';
                                            #>
                                            <a href="{{ item.link.url }}"{{ target }}{{ nofollow }}>
                                                <img src="{{ item.team_member_image.url }}">
                                            </a>
                                        <# } else { #>
                                            <img src="{{ item.team_member_image.url }}">
                                        <# } #>
                                    <# } #>

                                    <# if ( settings.overlay_content == 'social_icons' ) { #>
                                        <div class="pp-tm-overlay-content-wrap">
                                            <div class="pp-tm-content">
                                                <# if ( settings.member_social_links == 'yes' ) { #>
                                                    <?php $this->_member_social_links_template(); ?>
                                                <# } #>
                                            </div>
                                        </div>
                                    <# } #>

                                    <# if ( settings.overlay_content == 'all_content' ) { #>
                                        <div class="pp-tm-overlay-content-wrap">
                                            <div class="pp-tm-content">
                                                <# if ( settings.member_social_links == 'yes' ) { #>
                                                    <# if ( settings.social_links_position == 'before_desc' ) { #>
                                                        <?php $this->_member_social_links_template(); ?>
                                                    <# } #>
                                                <# } #>

                                                <?php $this->_description_template(); ?>

                                                <# if ( settings.member_social_links == 'yes' ) { #>
                                                    <# if ( settings.social_links_position == 'after_desc' ) { #>
                                                        <?php $this->_member_social_links_template(); ?>
                                                    <# } #>
                                                <# } #>
                                            </div>
                                        </div>
                                    <# } #>
                                </div>
                                <# if ( settings.overlay_content == 'all_content' ) { #>
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
                                    <div class="pp-tm-content pp-tm-content-normal">
                                        <?php $this->_name_template(); ?>
                                        <?php $this->_position_template(); ?>
                                        <# if ( settings.member_social_links == 'yes' && settings.overlay_content == 'none' ) { #>
                                            <# if ( settings.social_links_position == 'before_desc' ) { #>
                                                <?php $this->_member_social_links_template(); ?>
                                            <# } #>
                                        <# } #>

                                        <?php $this->_description_template(); ?>

                                        <# if ( settings.member_social_links == 'yes' && settings.overlay_content == 'none' ) { #>
                                            <# if ( settings.social_links_position == 'after_desc' ) { #>
                                                <?php $this->_member_social_links_template(); ?>
                                            <# } #>
                                        <# } #>
                                    </div><!-- .pp-tm-content -->
                                <# } #>
                            </div><!-- .pp-tm -->
                        </div><!-- .swiper-slide -->
                    <# i++ } ); #>
                </div>
            </div>
            <?php
                $this->_dots_template();

                $this->_arrows_template();
            ?>
        </div>    
        <?php
    }
}