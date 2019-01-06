<?php
namespace PowerpackElementsLite\Modules\Instafeed\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
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
 * Instagram Feed Widget
 */
class Instafeed extends Powerpack_Widget {
    
    /**
	 * Retrieve instagram feed widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-instafeed';
    }

    /**
	 * Retrieve instagram feed widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Instagram Feed', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the instagram feed widget belongs to.
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
	 * Retrieve instagram feed widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-instagram-feed power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the instagram feed widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'instafeed',
            'magnific-popup',
            'powerpack-frontend'
        ];
    }

    /**
	 * Register instagram feed widget controls.
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
         * Content Tab: Instagram Account
         */
        $this->start_controls_section(
            'section_instaaccount',
            [
                'label'                 => __( 'Instagram Account', 'power-pack' ),
            ]
        );

        $this->add_control(
            'user_id',
            [
                'label'                 => __( 'User ID', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
            ]
        );
        
        $this->add_control(
            'access_token',
            [
                'label'                 => __( 'Access Token', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
            ]
        );
        
        $this->add_control(
            'client_id',
            [
                'label'                 => __( 'Client ID', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
            ]
        );

        $this->end_controls_section();
        
        /**
         * Content Tab: Feed Settings
         */
        $this->start_controls_section(
            'section_instafeed',
            [
                'label'                 => __( 'Feed Settings', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'images_count',
            [
                'label'                 => __( 'Images Count', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 5 ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
            ]
        );

        $this->add_control(
            'resolution',
            [
                'label'                 => __( 'Image Resolution', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'thumbnail'              => __( 'Thumbnail', 'power-pack' ),
                   'low_resolution'         => __( 'Low Resolution', 'power-pack' ),
                   'standard_resolution'    => __( 'Standard Resolution', 'power-pack' ),
                ],
                'default'               => 'low_resolution',
            ]
        );

        $this->add_control(
            'sort_by',
            [
                'label'                 => __( 'Sort By', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'none'               => __( 'None', 'power-pack' ),
                   'most-recent'        => __( 'Most Recent', 'power-pack' ),
                   'least-recent'       => __( 'Least Recent', 'power-pack' ),
                   'most-liked'         => __( 'Most Liked', 'power-pack' ),
                   'least-liked'        => __( 'Least Liked', 'power-pack' ),
                   'most-commented'     => __( 'Most Commented', 'power-pack' ),
                   'least-commented'    => __( 'Least Commented', 'power-pack' ),
                   'random'             => __( 'Random', 'power-pack' ),
                ],
                'default'               => 'none',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: General Settings
         */
        $this->start_controls_section(
            'section_general_settings',
            [
                'label'                 => __( 'General Settings', 'power-pack' ),
            ]
        );

        $this->add_control(
            'feed_layout',
            [
                'label'                 => __( 'Layout', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'grid',
                'options'               => [
                   'grid'           => __( 'Grid', 'power-pack' ),
                   'carousel'       => __( 'Carousel', 'power-pack' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'grid_cols',
            [
                'label'                 => __( 'Grid Columns', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'label_block'           => false,
                'default'               => '5',
                'tablet_default'        => '3',
                'mobile_default'        => '2',
                'options'               => [
                   '1'              => __( '1', 'power-pack' ),
                   '2'              => __( '2', 'power-pack' ),
                   '3'              => __( '3', 'power-pack' ),
                   '4'              => __( '4', 'power-pack' ),
                   '5'              => __( '5', 'power-pack' ),
                   '6'              => __( '6', 'power-pack' ),
                   '7'              => __( '7', 'power-pack' ),
                   '8'              => __( '8', 'power-pack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-instagram-feed-grid .pp-feed-item' => 'width: calc( 100% / {{VALUE}} )',
                ],
				'condition'             => [
					'feed_layout'   => 'grid',
				],
            ]
        );
        
        $this->add_control(
            'insta_likes',
            [
                'label'                 => __( 'Likes', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'yes',
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'insta_comments',
            [
                'label'                 => __( 'Comments', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );

        $this->add_control(
            'content_visibility',
            [
                'label'                 => __( 'Content Visibility', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'always',
                'options'               => [
                   'always'         => __( 'Always', 'power-pack' ),
                   'hover'          => __( 'On Hover', 'power-pack' ),
                ],
            ]
        );
        
        $this->add_control(
            'insta_image_popup',
            [
                'label'                 => __( 'Open Image in Popup', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'insta_profile_link',
            [
                'label'                 => __( 'Show Link to Instagram Profile?', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
                'separator'             => 'before',
            ]
        );

        $this->add_control(
            'insta_link_title',
            [
                'label'                 => __( 'Link Title', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => __( 'Follow Us @ Instagram', 'power-pack' ),
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );

        $this->add_control(
            'insta_profile_url',
            [
                'label'                 => __( 'Instagram Profile URL', 'power-pack' ),
                'type'                  => Controls_Manager::URL,
                'placeholder'           => 'https://www.your-link.com',
                'default'               => [
                    'url'           => '#',
                ],
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );
        
        $this->add_control(
            'insta_title_icon',
            [
                'label'                 => __( 'Title Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => 'fa fa-instagram',
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );

        $this->add_control(
            'insta_title_icon_position',
            [
                'label'                 => __( 'Icon Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'before_title'   => __( 'Before Title', 'power-pack' ),
                   'after_title'    => __( 'After Title', 'power-pack' ),
                ],
                'default'               => 'before_title',
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );
        
        $this->add_control(
            'load_more_button',
            [
                'label'                 => __( 'Show Load More Button', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
                'separator'             => 'before',
				'condition'             => [
					'feed_layout'       => 'grid'
				],
            ]
        );

        $this->add_control(
            'load_more_button_text',
            [
                'label'                 => __( 'Button Text', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => __( 'Load More', 'power-pack' ),
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid'
				],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Content Tab: Carousel Settings
         */
        $this->start_controls_section(
            'section_carousel_settings',
            [
                'label'                 => __( 'Carousel Settings', 'power-pack' ),
				'condition'             => [
					'feed_layout'   => 'carousel',
				],
            ]
        );
        
        $this->add_control(
            'arrows',
            [
                'label'                 => __( 'Arrows', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
				'condition'             => [
					'feed_layout' => 'carousel',
				],
            ]
        );
        
        $this->add_control(
            'dots',
            [
                'label'                 => __( 'Pagination', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
				'condition'             => [
					'feed_layout' => 'carousel',
				],
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
        
        $this->add_responsive_control(
            'items',
            [
                'label'                 => __( 'Visible Items', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 3 ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 10,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
				'condition'             => [
					'feed_layout' => 'carousel',
				],
            ]
        );
        
        $this->add_responsive_control(
            'margin',
            [
                'label'                 => __( 'Items Gap', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 10 ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
				'condition'             => [
					'feed_layout'  => 'carousel',
				],
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
				'condition'             => [
					'feed_layout'  => 'carousel',
				],
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label'                 => __( 'Autoplay Speed', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => '2400',
                'title'                 => __( 'Enter carousel speed', 'power-pack' ),
                'condition'             => [
                    'autoplay'     => 'yes',
					'feed_layout'  => 'carousel',
                ],
            ]
        );
        
        $this->add_control(
            'infinite_loop',
            [
                'label'                 => __( 'Infinite Loop', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
				'condition'             => [
					'feed_layout' => 'carousel',
				],
            ]
        );
        
        $this->add_control(
            'grab_cursor',
            [
                'label'                 => __( 'Grab Cursor', 'power-pack' ),
                'description'           => __( 'Shows grab cursor when you hover over the slider', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'          => __( 'Show', 'power-pack' ),
                'label_off'         => __( 'Hide', 'power-pack' ),
                'return_value'      => 'yes',
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
         * Style Tab: Image
         */
        $this->start_controls_section(
            'section_image_style',
            [
                'label'                 => __( 'Image', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_image_style' );

        $this->start_controls_tab(
            'tab_image_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'insta_image_grayscale',
            [
                'label'                 => __( 'Grayscale Image', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'overlay_heading',
            [
                'label'                 => __( 'Overlay', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
            ]
        );
			
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'image_overlay_normal',
                'label'                 => __( 'Overlay', 'power-pack' ),
                'types'                 => [ 'classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-instagram-feed .pp-feed-item:before',
            ]
        );
        
        $this->add_control(
            'image_overlay_opacity_normal',
            [
                'label'                 => __( 'Overlay Opacity', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-instagram-feed .pp-feed-item:before' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_image_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'insta_image_grayscale_hover',
            [
                'label'                 => __( 'Grayscale Image', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_control(
            'overlay_heading_hover',
            [
                'label'                 => __( 'Overlay', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
            ]
        );
			
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'image_overlay_hover',
                'label'                 => __( 'Overlay', 'power-pack' ),
                'types'                 => [ 'none','classic','gradient' ],
                'selector'              => '{{WRAPPER}} .pp-instagram-feed .pp-feed-item:hover:before',
            ]
        );
        
        $this->add_control(
            'image_overlay_opacity_hover',
            [
                'label'                 => __( 'Overlay Opacity', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-instagram-feed .pp-feed-item:hover:before' => 'opacity: {{SIZE}};',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_control(
            'likes_comments_color',
            [
                'label'                 => __( 'Likes and Comments Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-overlay-container' => 'color: {{VALUE}};',
                ],
                'separator'             => 'before'
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Feed Title
         */
        $this->start_controls_section(
            'section_feed_title_style',
            [
                'label'                 => __( 'Feed Title', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );
        
        $this->add_control(
			'feed_title_position',
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
				'prefix_class'          => 'pp-insta-title-',
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'feed_title_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-instagram-feed-title',
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );

        $this->start_controls_tabs( 'tabs_title_style' );

        $this->start_controls_tab(
            'tab_title_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );

        $this->add_control(
            'title_color_normal',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-instagram-feed-title-wrap a' => 'color: {{VALUE}};',
                ],
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );

        $this->add_control(
            'title_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-instagram-feed-title-wrap' => 'background: {{VALUE}};',
                ],
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'title_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-instagram-feed-title-wrap'
			]
		);

		$this->add_control(
			'title_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-instagram-feed-title-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );

        $this->add_control(
            'title_color_hover',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-instagram-feed-title-wrap a:hover' => 'color: {{VALUE}};',
                ],
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );

        $this->add_control(
            'title_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-instagram-feed-title-wrap:hover' => 'background: {{VALUE}};',
                ],
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'title_border_hover',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-instagram-feed-title-wrap:hover'
			]
		);

		$this->add_control(
			'title_border_radius_hover',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-instagram-feed-title-wrap:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

		$this->add_control(
			'title_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-instagram-feed-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'insta_profile_link' => 'yes',
				],
                'separator'             => 'before',
			]
		);
        
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
                    'arrows'       => 'yes',
					'feed_layout'  => 'carousel',
                ],
            ]
        );
        
        $this->add_control(
            'arrow',
            [
                'label'                 => __( 'Choose Arrow', 'your-plugin' ),
                'type'                  => Controls_Manager::SELECT,
                'label_block'           => false,
                'default'               => 'fa fa-angle-right',
                'options'               => [
                    'fa fa-angle-right'             => __( 'Angle', 'power-pack' ),
                    'fa fa-angle-double-right'      => __( 'Double Angle', 'power-pack' ),
                    'fa fa-chevron-right'           => __( 'Chevron', 'power-pack' ),
                    'fa fa-chevron-circle-right'    => __( 'Chevron Circle', 'power-pack' ),
                    'fa fa-arrow-right'             => __( 'Arrow', 'power-pack' ),
                    'fa fa-long-arrow-right'        => __( 'Long Arrow', 'power-pack' ),
                    'fa fa-caret-right'             => __( 'Caret', 'power-pack' ),
                    'fa fa-caret-square-o-right'    => __( 'Caret Square', 'power-pack' ),
                    'fa fa-arrow-circle-right'      => __( 'Arrow Circle', 'power-pack' ),
                    'fa fa-arrow-circle-o-right'    => __( 'Arrow Circle O', 'power-pack' ),
                    'fa fa-toggle-right'            => __( 'Toggle', 'power-pack' ),
                    'fa fa-hand-o-right'            => __( 'Hand', 'power-pack' ),
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
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'color: {{VALUE}};',
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
				'selector'              => '{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev'
			]
		);

		$this->add_control(
			'arrows_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .pp-instagram-feed .swiper-button-next:hover, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev:hover' => 'background-color: {{VALUE}};',
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
                    '{{WRAPPER}} .pp-instagram-feed .swiper-button-next:hover, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev:hover' => 'color: {{VALUE}};',
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
                    '{{WRAPPER}} .pp-instagram-feed .swiper-button-next:hover, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev:hover' => 'border-color: {{VALUE}};',
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
					'{{WRAPPER}} .pp-instagram-feed .swiper-button-next, {{WRAPPER}} .pp-instagram-feed .swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'             => 'before',
			]
		);
        
        $this->end_controls_section();
        
        /**
         * Style Tab: Pagination: Dots
         */
        $this->start_controls_section(
            'section_dots_style',
            [
                'label'                 => __( 'Pagination: Dots', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
					'feed_layout'       => 'carousel',
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
					'feed_layout'       => 'carousel',
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
                    '{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
					'feed_layout'       => 'carousel',
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
                    '{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
					'feed_layout'       => 'carousel',
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
					'feed_layout'       => 'carousel',
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
                    '{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
                'condition'             => [
					'feed_layout'       => 'carousel',
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
                    '{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
                'condition'             => [
					'feed_layout'       => 'carousel',
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
				'selector'              => '{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet',
                'condition'             => [
					'feed_layout'       => 'carousel',
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
					'{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
					'feed_layout'       => 'carousel',
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
					'{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
					'feed_layout'       => 'carousel',
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
					'feed_layout'       => 'carousel',
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
                    '{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
                ],
                'condition'             => [
					'feed_layout'       => 'carousel',
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
                    '{{WRAPPER}} .pp-instagram-feed .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
                ],
                'condition'             => [
					'feed_layout'       => 'carousel',
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
					'feed_layout'       => 'carousel',
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
					'feed_layout'       => 'carousel',
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
					'feed_layout'       => 'carousel',
                    'dots'              => 'yes',
                    'pagination_type'   => 'fraction',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_load_more_button_style',
            [
                'label'                 => __( 'Load More Button', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
            ]
        );
        
        $this->add_responsive_control(
            'button_alignment',
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
				'default'               => 'center',
				'selectors'             => [
					'{{WRAPPER}} .pp-load-more-button-wrap' => 'text-align: {{VALUE}};',
				],
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
			]
		);
        
        $this->add_control(
            'button_top_spacing',
            [
                'label'                 => __( 'Top Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 20 ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => ['px'],
				'selectors'             => [
					'{{WRAPPER}} .pp-load-more-button-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
            ]
        );

		$this->add_control(
			'button_size',
			[
				'label'                 => __( 'Size', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'md',
				'options'               => [
					'xs' => __( 'Extra Small', 'power-pack' ),
					'sm' => __( 'Small', 'power-pack' ),
					'md' => __( 'Medium', 'power-pack' ),
					'lg' => __( 'Large', 'power-pack' ),
					'xl' => __( 'Extra Large', 'power-pack' ),
				],
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
            ]
        );

        $this->add_control(
            'button_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-load-more-button' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
            ]
        );

        $this->add_control(
            'button_text_color_normal',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-load-more-button' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-load-more-button',
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-load-more-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'button_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-load-more-button',
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
            ]
        );

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-load-more-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-load-more-button',
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
			]
		);
        
        $this->add_control(
            'load_more_button_icon_heading',
            [
                'label'                 => __( 'Button Icon', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
                    'button_icon!' => '',
                ],
            ]
        );

		$this->add_responsive_control(
			'button_icon_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'       => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
                'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
                    'button_icon!' => '',
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
                'label'                 => __( 'Hover', 'power-pack' ),
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-load-more-button:hover' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-load-more-button:hover' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-load-more-button:hover' => 'border-color: {{VALUE}}',
                ],
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
            ]
        );

		$this->add_control(
			'button_animation',
			[
				'label'                 => __( 'Animation', 'power-pack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-load-more-button:hover',
				'condition'             => [
					'load_more_button'  => 'yes',
					'feed_layout'       => 'grid',
				],
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();

    }

    /**
	 * Render promo box widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();
        
        $this->add_render_attribute( 'insta-feed-wrap', 'class', [
                'pp-instagram-feed',
                'clearfix',
                'pp-instagram-feed-' . $settings['feed_layout'],
                'pp-instagram-feed-' . $settings['content_visibility']
            ]
        );

        if ( $settings['feed_layout'] == 'grid' && $settings['grid_cols'] ) {
            $this->add_render_attribute( 'insta-feed-wrap', 'class', 'pp-instagram-feed-grid-' . $settings['grid_cols'] );
        }

        if ( $settings['insta_image_grayscale'] == 'yes' ) {
            $this->add_render_attribute( 'insta-feed-wrap', 'class', 'pp-instagram-feed-gray' );
        }

        if ( $settings['insta_image_grayscale_hover'] == 'yes' ) {
            $this->add_render_attribute( 'insta-feed-wrap', 'class', 'pp-instagram-feed-hover-gray' );
        }
        
        $this->add_render_attribute( 'insta-feed-container', 'class', 'pp-instafeed' );
        
        $this->add_render_attribute( 'insta-feed', 'id', 'pp-instafeed-' . esc_attr( $this->get_id() ) );

        $this->add_render_attribute( 'insta-feed-inner', 'class', 'pp-insta-feed-inner' );
        
        if ( $settings['feed_layout'] == 'carousel' ) {

            $this->add_render_attribute( 'insta-feed-inner', 'class', 'swiper-container-wrap pp-insta-feed-carousel-wrap' );
            
            $this->add_render_attribute( 'insta-feed-container', 'class', 'swiper-container swiper-container-' . esc_attr( $this->get_id() ) );
        
            if ( $settings['dots_position'] ) {
                $this->add_render_attribute( 'insta-feed-inner', 'class', 'swiper-container-dots-' . $settings['dots_position'] );
            } elseif ( $settings['pagination_type'] == 'fraction' ) {
                $this->add_render_attribute( 'insta-feed-inner', 'class', 'swiper-container-dots-outside' );
            }
        
            if ( $settings['direction'] == 'right' ) {
                $this->add_render_attribute( 'insta-feed-container', 'dir', 'rtl' );
            }
        
            if ( ! empty( $settings['items']['size'] ) ) {
                $this->add_render_attribute( 'insta-feed-container', 'data-items', $settings['items']['size'] );
            }
            if ( ! empty( $settings['items_tablet']['size'] ) ) {
                $this->add_render_attribute( 'insta-feed-container', 'data-items-tablet', $settings['items_tablet']['size'] );
            }
            if ( ! empty( $settings['items_mobile']['size'] ) ) {
                $this->add_render_attribute( 'insta-feed-container', 'data-items-mobile', $settings['items_mobile']['size'] );
            }
            if ( ! empty( $settings['margin']['size'] ) ) {
                $this->add_render_attribute( 'insta-feed-container', 'data-margin', $settings['margin']['size'] );
            }
            if ( ! empty( $settings['margin_tablet']['size'] ) ) {
                $this->add_render_attribute( 'insta-feed-container', 'data-margin-tablet', $settings['margin_tablet']['size'] );
            }
            if ( ! empty( $settings['margin_mobile']['size'] ) ) {
                $this->add_render_attribute( 'insta-feed-container', 'data-margin-mobile', $settings['margin_mobile']['size'] );
            }
            if ( $settings['autoplay'] == 'yes' && ! empty( $settings['autoplay_speed'] ) ) {
                $this->add_render_attribute( 'insta-feed-container', 'data-autoplay', $settings['autoplay_speed'] );
            } else {
                $this->add_render_attribute( 'insta-feed-container', 'data-autoplay', '999999' );
            }
            if ( $settings['infinite_loop'] == 'yes' ) {
                $this->add_render_attribute( 'insta-feed-container', 'data-loop', '1' );
            }
            if ( $settings['grab_cursor'] == 'yes' ) {
                $this->add_render_attribute( 'insta-feed-container', 'data-grab-cursor', '1' );
            }
            if ( $settings['arrows'] == 'yes' ) {
                $this->add_render_attribute( 'insta-feed-container', 'data-arrows', '1' );
            }
            if ( $settings['dots'] == 'yes' ) {
                $this->add_render_attribute( 'insta-feed-container', 'data-dots', '1' );
                $this->add_render_attribute( 'insta-feed-container', 'data-pagination-type', $settings['pagination_type'] );
            }
            
            $this->add_render_attribute( 'insta-feed', 'class', 'swiper-wrapper' );
        }
        
        if ( ! empty( $settings['insta_profile_url']['url'] ) ) {
            $this->add_render_attribute( 'instagram-profile-link', 'href', esc_url( $settings['insta_profile_url']['url'] ) );

            if ( ! empty( $settings['insta_profile_url']['is_external'] ) ) {
                $this->add_render_attribute( 'instagram-profile-link', 'target', '_blank' );
            }
        }
        
        $pp_widget_options = [
            'user_id'           => ! empty( $settings['user_id'] ) ? $settings['user_id'] : '',
            'access_token'      => ! empty( $settings['access_token'] ) ? $settings['access_token'] : '',
            'sort_by'           => ! empty( $settings['sort_by'] ) ? $settings['sort_by'] : '',
            'images_count'      => ! empty( $settings['images_count']['size'] ) ? $settings['images_count']['size'] : '3',
            'target'            => 'pp-instafeed-'. esc_attr( $this->get_id() ),
            'resolution'        => ! empty( $settings['resolution'] ) ? $settings['resolution'] : '',
            'popup'             => ( $settings['insta_image_popup'] == 'yes' ) ? '1' : '0',
            'likes'             => ( $settings['insta_likes'] == 'yes' ) ? '1' : '0',
            'comments'          => ( $settings['insta_comments'] == 'yes' ) ? '1' : '0',
            'layout'            => ( $settings['feed_layout'] == 'carousel' ) ? 'carousel' : 'grid',
        ];
        ?>
        <div <?php echo $this->get_render_attribute_string( 'insta-feed-wrap' ); ?> data-settings='<?php echo wp_json_encode( $pp_widget_options ); ?>'>
            <?php if ( $settings['insta_profile_link'] == 'yes' ) { ?>
                <?php if ( ! empty( $settings['insta_link_title'] ) ) { ?>
                    <span class="pp-instagram-feed-title-wrap">
                        <a <?php echo $this->get_render_attribute_string( 'instagram-profile-link' ); ?>>
                            <span class="pp-instagram-feed-title">
                                <?php if ( ! empty( $settings['insta_title_icon'] ) ) { ?>
                                    <?php if ( $settings['insta_title_icon_position'] == 'before_title' ) { ?>
                                        <span class="<?php echo esc_attr( $settings['insta_title_icon'] ); ?>" aria-hidden="true"></span>
                                    <?php } ?>
                                <?php } ?>
                                <?php echo esc_attr( $settings[ 'insta_link_title' ] ); ?>
                                <?php if ( ! empty( $settings['insta_title_icon'] ) ) { ?>
                                    <?php if ( $settings['insta_title_icon_position'] == 'after_title' ) { ?>
                                        <span class="<?php echo esc_attr( $settings['insta_title_icon'] ); ?>" aria-hidden="true"></span>
                                    <?php } ?>
                                <?php } ?>
                            </span>
                        </a>
                    </span>
                <?php } ?>
            <?php } ?>
            
            <div <?php echo $this->get_render_attribute_string( 'insta-feed-inner' ); ?>>
                <div <?php echo $this->get_render_attribute_string( 'insta-feed-container' ); ?>>
                    <div <?php echo $this->get_render_attribute_string( 'insta-feed' ); ?>></div>
                </div>
                <?php
                    $this->render_load_more_button();

                    $this->render_dots();

                    $this->render_arrows();
                ?>
            </div>
        </div>
        <?php
    }

    /**
	 * Render load more button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_load_more_button() {
        $settings = $this->get_settings();
        
        $this->add_render_attribute( 'load-more-button', 'class', [
				'pp-load-more-button',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			]
		);

		if ( $settings['button_animation'] ) {
			$this->add_render_attribute( 'load-more-button', 'class', 'elementor-animation-' . $settings['button_animation'] );
		}

        if ( $settings['feed_layout'] == 'grid' && $settings['load_more_button'] == 'yes' ) { ?>
            <div class="pp-load-more-button-wrap">
                <div <?php echo $this->get_render_attribute_string( 'load-more-button' ) ?>>
                    <span class="pp-button-loader"></span>
                    <span class="pp-load-more-button-text">
                        <?php echo $settings['load_more_button_text']; ?>
                    </span>
                </div>
            </div>
        <?php }
    }

    /**
	 * Render insta feed carousel dots output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_dots() {
        $settings = $this->get_settings();

        if ( $settings['feed_layout'] == 'carousel' && $settings['dots'] == 'yes' ) { ?>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        <?php }
    }

    /**
	 * Render insta feed carousel arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_arrows() {
        $settings = $this->get_settings();

        if ( $settings['feed_layout'] == 'carousel' && $settings['arrows'] == 'yes' ) { ?>
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
            <div class="swiper-button-next">
                <i class="<?php echo esc_attr( $pa_next_arrow ); ?>"></i>
            </div>
            <div class="swiper-button-prev">
                <i class="<?php echo esc_attr( $pa_prev_arrow ); ?>"></i>
            </div>
        <?php }
    }

    protected function content_template() {}

}