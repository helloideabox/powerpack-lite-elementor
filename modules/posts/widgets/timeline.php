<?php
namespace PowerpackElements\Modules\Posts\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Timeline Widget
 */
class Timeline extends Powerpack_Widget {
    
    /**
	 * Retrieve timeline widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-timeline';
    }

    /**
	 * Retrieve timeline widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Timeline', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the timeline widget belongs to.
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
	 * Retrieve timeline widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-timeline power-pack-admin-icon';
    }

	/**
	 * Retrieve the list of scripts the timeline widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [
			'jquery-slick',
			'pp-timeline',			
			'powerpack-frontend'
		];
	}

    /**
	 * Register timeline widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	Content Tab
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Settings
         */
        $this->start_controls_section(
            'section_post_settings',
            [
                'label'                 => __( 'Settings', 'power-pack' ),
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'                 => __( 'Layout', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'horizontal' => __( 'Horizontal', 'power-pack' ),
                   'vertical'   => __( 'Vertical', 'power-pack' ),
                ],
                'default'               => 'vertical',
                'frontend_available'    => true,
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
                    '7' => '7',
                    '8' => '8',
                ],
                'frontend_available'    => true,
                'condition'             => [
                    'layout'    => 'horizontal'
                ]
            ]
        );

        $this->add_control(
            'source',
            [
                'label'                 => __( 'Source', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'custom'     => __( 'Custom', 'power-pack' ),
                   'posts'      => __( 'Posts', 'power-pack' ),
                ],
                'default'               => 'custom',
            ]
        );
        
        $this->add_control(
            'posts_per_page',
            [
                'label'                 => __( 'Posts Per Page', 'power-pack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 4,
                'condition'             => [
                    'source'	=> 'posts'
                ]
            ]
        );
        
        $this->add_control(
            'dates',
            [
                'label'                 => __( 'Dates', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'return_value'          => 'yes',
                'frontend_available'    => true,
            ]
        );
        
        $this->add_control(
            'animate_cards',
            [
                'label'                 => __( 'Animate Cards', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'return_value'          => 'yes',
                'frontend_available'    => true,
                'condition'             => [
                    'layout'    => 'vertical'
                ]
            ]
        );
        
        $this->add_control(
            'arrows',
            [
                'label'                 => __( 'Arrows', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'return_value'          => 'yes',
                'frontend_available'    => true,
                'condition'             => [
                    'layout'    => 'horizontal'
                ]
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label'                 => __( 'Autoplay', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'return_value'          => 'yes',
                'frontend_available'    => true,
                'condition'             => [
                    'layout'    => 'horizontal'
                ]
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label'                 => __( 'Autoplay Speed', 'power-pack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 3000,
                'frontend_available'    => true,
                'condition'             => [
                    'layout'    => 'horizontal',
                    'autoplay'  => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Timeline
         */
        $this->start_controls_section(
            'section_timeline_items',
            [
                'label'                 => __( 'Timeline', 'power-pack' ),
                'condition'             => [
                    'source'    => 'custom'
                ]
            ]
        );
        
        $repeater = new Repeater();
        
        $repeater->start_controls_tabs( 'timeline_items_tabs' );

        $repeater->start_controls_tab( 'tab_timeline_items_content', [ 'label' => __( 'Content', 'power-pack' ) ] );
        
            $repeater->add_control(
                'timeline_item_date',
                [
                    'label'             => __( 'Date', 'power-pack' ),
                    'type'              => Controls_Manager::TEXT,
                    'label_block'       => false,
                    'default'           => __( '1 June 2018', 'power-pack' ),
                ]
            );
        
            $repeater->add_control(
                'timeline_item_title',
                [
                    'label'             => __( 'Title', 'power-pack' ),
                    'type'              => Controls_Manager::TEXT,
                    'label_block'       => false,
                    'default'           => '',
                ]
            );
        
            $repeater->add_control(
                'timeline_item_content',
                [
                    'label'             => __( 'Content', 'power-pack' ),
                    'type'              => Controls_Manager::WYSIWYG,
                    'default'           => '',
                ]
            );

            $repeater->add_control(
                'timeline_item_link',
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
                        'url' => '',
                    ],
                ]
            );
        
        $repeater->end_controls_tab();

        $repeater->start_controls_tab( 'tab_timeline_items_image', [ 'label' => __( 'Image', 'power-pack' ) ] );
        
        $repeater->add_control(
            'card_image',
            [
                'label'                 => __( 'Show Image', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $repeater->add_control(
			'image',
			[
				'label'                 => __( 'Choose Image', 'power-pack' ),
				'type'                  => \Elementor\Controls_Manager::MEDIA,
				'default'               => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
                'conditions'            => [
                    'terms' => [
                        [
                            'name'      => 'card_image',
                            'operator'  => '==',
                            'value'     => 'yes',
                        ],
                    ],
                ],
			]
		);
        
        $repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'image',
                'exclude'               => [ 'custom' ],
				'include'               => [],
				'default'               => 'large',
                'conditions'            => [
                    'terms' => [
                        [
                            'name'      => 'card_image',
                            'operator'  => '==',
                            'value'     => 'yes',
                        ],
                    ],
                ],
			]
		);
        
        $repeater->end_controls_tab();

        $repeater->start_controls_tab( 'tab_timeline_items_style', [ 'label' => __( 'Style', 'power-pack' ) ] );
        
        $repeater->add_control(
            'custom_style',
            [
                'label'                 => __( 'Custom Style', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );

        $repeater->add_control(
            'single_marker_color',
            [
                'label'                 => __( 'Marker Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .pp-timeline-marker, {{WRAPPER}} .slick-center .pp-timeline-marker' => 'color: {{VALUE}}',
                ],
                'conditions'            => [
                    'terms' => [
                        [
                            'name'      => 'custom_style',
                            'operator'  => '==',
                            'value'     => 'yes',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'single_marker_bg_color',
            [
                'label'                 => __( 'Marker Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .pp-timeline-marker, {{WRAPPER}} .slick-center .pp-timeline-marker' => 'background-color: {{VALUE}}',
                ],
                'conditions'            => [
                    'terms' => [
                        [
                            'name'      => 'custom_style',
                            'operator'  => '==',
                            'value'     => 'yes',
                        ],
                    ],
                ],
            ]
        );
        
        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'items',
            [
                'label'                 => '',
                'type'                  => Controls_Manager::REPEATER,
                'default'               => [
                    [
                        'timeline_item_date'    => __( '1 May 2018', 'power-pack' ),
                        'timeline_item_title'   => __( 'Timeline Item 1', 'power-pack' ),
                        'timeline_item_content' => __( 'I am timeline item content. Click here to edit this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                    ],
                    [
                        'timeline_item_date'    => __( '1 June 2018', 'power-pack' ),
                        'timeline_item_title'   => __( 'Timeline Item 2', 'power-pack' ),
                        'timeline_item_content' => __( 'I am timeline item content. Click here to edit this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                    ],
                    [
                        'timeline_item_date'    => __( '1 July 2018', 'power-pack' ),
                        'timeline_item_title'   => __( 'Timeline Item 3', 'power-pack' ),
                        'timeline_item_content' => __( 'I am timeline item content. Click here to edit this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                    ],
                    [
                        'timeline_item_date'    => __( '1 August 2018', 'power-pack' ),
                        'timeline_item_title'   => __( 'Timeline Item 4', 'power-pack' ),
                        'timeline_item_content' => __( 'I am timeline item content. Click here to edit this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                    ],
                ],
                'fields'                => array_values( $repeater->get_controls() ),
                'title_field'           => '{{{ timeline_item_date }}}',
                'condition'             => [
                    'source'    => 'custom'
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Query
         */
        $this->start_controls_section(
            'section_post_query',
            [
                'label'                 => __( 'Query', 'power-pack' ),
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );

		$this->add_control(
            'post_type',
            [
                'label'                 => __( 'Post Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => pp_get_post_types(),
                'default'               => 'post',
                'condition'             => [
                    'source'    => 'posts'
                ]

            ]
        );

        $this->add_control(
            'categories',
            [
                'label'                 => __( 'Categories', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT2,
				'label_block'           => true,
				'multiple'              => true,
				'options'               => pp_get_post_categories(),
                'condition'             => [
                    'source'    => 'posts',
                    'post_type' => 'post'
                ]
            ]
        );

        $this->add_control(
            'authors',
            [
                'label'                 => __( 'Authors', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT2,
				'label_block'           => true,
				'multiple'              => true,
				'options'               => pp_get_auhtors(),
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );

        $this->add_control(
            'tags',
            [
                'label'                 => __( 'Tags', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT2,
				'label_block'           => true,
				'multiple'              => true,
				'options'               => pp_get_tags(),
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );

        $this->add_control(
            'exclude_posts',
            [
                'label'                 => __( 'Exclude Posts', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT2,
				'label_block'           => true,
				'multiple'              => true,
				'options'               => pp_get_posts(),
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );

        $this->add_control(
            'order',
            [
                'label'                 => __( 'Order', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'DESC'       => __( 'Descending', 'power-pack' ),
                   'ASC'        => __( 'Ascending', 'power-pack' ),
                ],
                'default'               => 'DESC',
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'                 => __( 'Order By', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'date'           => __( 'Date', 'power-pack' ),
                   'modified'       => __( 'Last Modified Date', 'power-pack' ),
                   'rand'           => __( 'Rand', 'power-pack' ),
                   'comment_count'  => __( 'Comment Count', 'power-pack' ),
                   'title'          => __( 'Title', 'power-pack' ),
                   'ID'             => __( 'Post ID', 'power-pack' ),
                   'author'         => __( 'Post Author', 'power-pack' ),
                ],
                'default'               => 'date',
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );

        $this->add_control(
            'offset',
            [
                'label'                 => __( 'Offset', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => '',
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Posts
         */
        $this->start_controls_section(
            'section_posts',
            [
                'label'                 => __( 'Posts', 'power-pack' ),
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );
        
        $this->add_control(
            'post_title',
            [
                'label'                 => __( 'Post Title', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'show',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'show',
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );
        
        $this->add_control(
            'post_image',
            [
                'label'                 => __( 'Post Image', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'show',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'show',
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );
		
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'image_size',
				'label'                 => __( 'Image Size', 'power-pack' ),
				'default'               => 'medium_large',
                'condition'             => [
                    'source'        => 'posts',
                    'post_image'    => 'show'
                ]
			]
		);
        
        $this->add_control(
            'post_content',
            [
                'label'                 => __( 'Post Content', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'show',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'show',
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );

        $this->add_control(
            'content_type',
            [
                'label'                 => __( 'Content Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'excerpt'            => __( 'Excerpt', 'power-pack' ),
                   'limited-content'    => __( 'Limited Content', 'power-pack' ),
                ],
                'default'               => 'excerpt',
                'condition'             => [
                    'source'        => 'posts',
                    'post_content'  => 'show'
                ]
            ]
        );
        
        $this->add_control(
            'content_length',
            [
                'label'                 => __( 'Content Limit', 'power-pack' ),
                'title'                 => __( 'Words', 'power-pack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 40,
                'min'                   => 0,
                'step'                  => 1,
                'condition'             => [
                    'source'        => 'posts',
                    'post_content'  => 'show',
                    'content_type'  => 'limited-content'
                ]
            ]
        );

        $this->add_control(
            'link_type',
            [
                'label'                 => __( 'Link Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   ''           => __( 'None', 'power-pack' ),
                   'title'      => __( 'Title', 'power-pack' ),
                   'button'     => __( 'Button', 'power-pack' ),
                   'card'       => __( 'Card', 'power-pack' ),
                ],
                'default'               => 'title',
                'condition'             => [
                    'source'        => 'posts',
                ]
            ]
        );
        
        $this->add_control(
            'button_text',
            [
                'label'                 => __( 'Button Text', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'label_block'           => false,
                'default'               => __( 'Read More', 'power-pack' ),
                'condition'             => [
                    'source'        => 'posts',
                    'link_type'     => 'button',
                ]
            ]
        );
        
        $this->add_control(
            'post_meta',
            [
                'label'                 => __( 'Post Meta', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'show',
                'condition'             => [
                    'source'    => 'posts'
                ]
            ]
        );

        $this->add_control(
            'meta_items_divider',
            [
                'label'             => __( 'Meta Items Divider', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => '-',
				'selectors'         => [
					'{{WRAPPER}} .pp-timeline-meta > span:not(:last-child):after' => 'content: "{{UNIT}}";',
				],
                'condition'         => [
                    'source'    => 'posts',
                    'post_meta' => 'show'
                ],
            ]
        );
        
        $this->add_control(
            'post_author',
            [
                'label'                 => __( 'Post Author', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'show',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'show',
                'condition'             => [
                    'source'    => 'posts',
                    'post_meta' => 'show'
                ]
            ]
        );
        
        $this->add_control(
            'post_category',
            [
                'label'                 => __( 'Post Category', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'show',
                'condition'             => [
                    'source'    => 'posts',
                    'post_meta' => 'show'
                ]
            ]
        );

        $this->end_controls_section();
        
        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Content
         */
        $this->start_controls_section(
            'section_layout_style',
            [
                'label'                 => __( 'Layout', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'direction',
            [
                'label'                 => __( 'Direction', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'toggle'                => true,
                'default'               => 'center',
                'tablet_default'        => 'left',
                'mobile_default'        => 'left',
                'options'               => [
                    'left' 		=> [
                        'title' => __( 'Left', 'power-pack' ),
                        'icon' 	=> 'eicon-h-align-left',
                    ],
                    'center' 	=> [
                        'title' => __( 'Center', 'power-pack' ),
                        'icon' 	=> 'eicon-h-align-center',
                    ],
                    'right' 	=> [
                        'title' => __( 'Right', 'power-pack' ),
                        'icon' 	=> 'eicon-h-align-right',
                    ],
                ],
                'condition'             => [
                    'layout'    => 'vertical',
                ]
            ]
        );

		$this->add_control(
			'cards_arrows_alignment',
			[
				'label'                 => __( 'Arrows Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'top'       => [
						'title' => __( 'Top', 'power-pack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle'    => [
						'title' => __( 'Middle', 'power-pack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom'    => [
						'title' => __( 'Bottom', 'power-pack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'               => 'top',
                'condition'             => [
                    'layout'    => 'vertical'
                ]
			]
		);

        $this->add_control(
            'items_spacing',
            [
                'label'                 => __( 'Items Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' 	    => '',
                ],
                'range' 		=> [
                    'px' 		=> [
                        'min' 	=> 0,
                        'max' 	=> 100,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-vertical .pp-timeline-item' => 'margin-bottom: {{SIZE}}px;',
                    '{{WRAPPER}} .pp-timeline-horizontal .pp-timeline-item' => 'padding-left: {{SIZE}}px; padding-right: {{SIZE}}px;',
                    '{{WRAPPER}} .pp-timeline-horizontal .slick-list'       => 'margin-left: -{{SIZE}}px; margin-right: -{{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Cards
         */
        $this->start_controls_section(
            'section_cards_style',
            [
                'label'                 => __( 'Cards', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'cards_padding',
			[
				'label'                 => __( 'Cards Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-timeline .pp-timeline-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_padding',
			[
				'label'                 => __( 'Content Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-timeline .pp-timeline-card-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'cards_text_align',
            [
                'label'                 => __( 'Text Align', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'options'               => [
                    'left' 	=> [
                        'title' 	=> __( 'Left', 'power-pack' ),
                        'icon' 		=> 'fa fa-align-left',
                    ],
                    'center' 		=> [
                        'title' 	=> __( 'Center', 'power-pack' ),
                        'icon' 		=> 'fa fa-align-center',
                    ],
                    'right' 		=> [
                        'title' 	=> __( 'Right', 'power-pack' ),
                        'icon' 		=> 'fa fa-align-right',
                    ],
                ],
                'default'               => 'left',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-card' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->start_controls_tabs( 'card_tabs' );
        
        $this->start_controls_tab( 'tab_card_normal', [ 'label' => __( 'Normal', 'power-pack' ) ] );

        $this->add_control(
            'cards_bg',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-card' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .pp-timeline .pp-timeline-arrow' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'cards_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-timeline .pp-timeline-card',
			]
		);

		$this->add_control(
			'cards_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-timeline .pp-timeline-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
			'cards_box_shadow',
			[
				'label'                 => __( 'Box Shadow', 'power-pack' ),
				'type'                  => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off'             => __( 'Default', 'power-pack' ),
				'label_on'              => __( 'Custom', 'power-pack' ),
				'return_value'          => 'yes',
			]
		);

		$this->start_popover();

			$this->add_control(
				'cards_box_shadow_color',
				[
					'label'                => __( 'Color', 'power-pack' ),
					'type'                 => Controls_Manager::COLOR,
					'default'              => 'rgba(0,0,0,0.5)',
					'selectors'            => [
						'{{WRAPPER}} .pp-timeline-card' => 'filter: drop-shadow({{cards_box_shadow_horizontal.SIZE}}px {{cards_box_shadow_vertical.SIZE}}px {{cards_box_shadow_blur.SIZE}}px {{VALUE}}); -webkit-filter: drop-shadow({{cards_box_shadow_horizontal.SIZE}}px {{cards_box_shadow_vertical.SIZE}}px {{cards_box_shadow_blur.SIZE}}px {{VALUE}});',
					],
					'condition'            => [
						'cards_box_shadow' => 'yes',
					],
				]
			);

			$this->add_control(
				'cards_box_shadow_horizontal',
				[
					'label'                => __( 'Horizontal', 'power-pack' ),
					'type'                 => Controls_Manager::SLIDER,
					'default'              => [
						'size' => 0,
						'unit' => 'px',
					],
					'range'                => [
						'px' => [
							'min'  => -100,
							'max'  => 100,
							'step' => 1,
						],
					],
					'condition'            => [
						'cards_box_shadow' => 'yes',
					],
				]
			);

			$this->add_control(
				'cards_box_shadow_vertical',
				[
					'label'                => __( 'Vertical', 'power-pack' ),
					'type'                 => Controls_Manager::SLIDER,
					'default'              => [
						'size' => 0,
						'unit' => 'px',
					],
					'range'                => [
						'px' => [
							'min'  => -100,
							'max'  => 100,
							'step' => 1,
						],
					],
					'condition'            => [
						'cards_box_shadow' => 'yes',
					],
				]
			);

			$this->add_control(
				'cards_box_shadow_blur',
				[
					'label'                => __( 'Blur', 'power-pack' ),
					'type'                 => Controls_Manager::SLIDER,
					'default'              => [
						'size' => 4,
						'unit' => 'px',
					],
					'range'                => [
						'px' => [
							'min'  => 1,
							'max'  => 10,
							'step' => 1,
						],
					],
					'condition'            => [
						'cards_box_shadow' => 'yes',
					],
				]
			);

		$this->end_popover();

		$this->add_control(
			'heading_image',
			[
				'label'                 => __( 'Image', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);
        
        $this->add_responsive_control(
            'image_margin_bottom',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' 	    => 20,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-card-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'heading_title',
			[
				'label'                 => __( 'Title', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'title_bg',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-card-title-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-card-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-timeline-card-title',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'title_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-timeline .pp-timeline-card-title-wrap',
			]
		);
        
        $this->add_responsive_control(
            'title_margin_bottom',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-card-title-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'title_padding',
			[
				'label'                 => __( 'Title Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-timeline .pp-timeline-card-title-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_content',
			[
				'label'                 => __( 'Content', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'card_text_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-card' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'card_text_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-timeline-card',
            ]
        );

		$this->add_control(
			'meta_content',
			[
				'label'                 => __( 'Post Meta', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
                'condition'             => [
                    'source'    => 'posts',
                    'post_meta' => 'show'
                ]
			]
		);

        $this->add_control(
            'meta_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-meta' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'source'    => 'posts',
                    'post_meta' => 'show'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'meta_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-timeline-meta',
                'condition'             => [
                    'source'    => 'posts',
                    'post_meta' => 'show'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'meta_items_gap',
            [
                'label'                 => __( 'Meta Items Gap', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' 	    => 10,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 60,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-meta > span:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pp-timeline-meta > span:not(:last-child):after' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
                'condition'         => [
                    'source'    => 'posts',
                    'post_meta' => 'show'
                ],
            ]
        );
        
        $this->add_responsive_control(
            'meta_margin_bottom',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' 	    => 20,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 60,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'         => [
                    'source'    => 'posts',
                    'post_meta' => 'show'
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 'tab_card_hover', [ 'label' => __( 'Hover', 'power-pack' ) ] );

        $this->add_control(
            'card_bg_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item:hover .pp-timeline-card' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item:hover .pp-timeline-arrow' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_bg_hover',
            [
                'label'                 => __( 'Title Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item:hover .pp-timeline-card-title-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_title_color_hover',
            [
                'label'                 => __( 'Title Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item:hover .pp-timeline-card-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_title_border_color_hover',
            [
                'label'                 => __( 'Title Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item:hover .pp-timeline-card-title-wrap' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_color_hover',
            [
                'label'                 => __( 'Content Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item:hover .pp-timeline-card' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item:hover .pp-timeline-card' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 'tab_card_focused', [ 'label' => __( 'Focused', 'power-pack' ) ] );

        $this->add_control(
            'card_bg_focused',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item-active .pp-timeline-card, {{WRAPPER}} .pp-timeline .slick-current .pp-timeline-card' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item-active .pp-timeline-arrow, {{WRAPPER}} .pp-timeline .slick-current .pp-timeline-arrow' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_bg_focused',
            [
                'label'                 => __( 'Title Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item-active .pp-timeline-card-title-wrap, {{WRAPPER}} .pp-timeline .slick-current .pp-timeline-card-title-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_title_color_focused',
            [
                'label'                 => __( 'Title Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item-active .pp-timeline-card-title, {{WRAPPER}} .pp-timeline .slick-current .pp-timeline-card-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_title_border_color_focused',
            [
                'label'                 => __( 'Title Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item-active .pp-timeline-card-title-wrap, {{WRAPPER}} .pp-timeline .slick-current .pp-timeline-card-title-wrap' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_color_focused',
            [
                'label'                 => __( 'Content Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item-active .pp-timeline-card, {{WRAPPER}} .pp-timeline .slick-current .pp-timeline-card' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'card_border_color_focused',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline .pp-timeline-item-active .pp-timeline-card, {{WRAPPER}} .pp-timeline .slick-current .pp-timeline-card' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Marker
         */
        $this->start_controls_section(
            'section_marker_style',
            [
                'label'                 => __( 'Marker', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
			'marker_type',
			[
				'label'                 => esc_html__( 'Type', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'toggle'                => false,
				'options'               => [
                    'none' => [
                        'title' => esc_html__( 'None', 'power-pack' ),
                        'icon'  => 'fa fa-ban',
                    ],
					'icon' => [
						'title' => esc_html__( 'Icon', 'power-pack' ),
						'icon' => 'fa fa-gear',
					],
					'number' => [
						'title' => esc_html__( 'Number', 'power-pack' ),
						'icon' => 'fa fa-sort-numeric-asc',
					],
					'letter' => [
						'title' => esc_html__( 'Letter', 'power-pack' ),
						'icon' => 'fa fa-sort-alpha-asc',
					],
				],
				'default'               => 'icon',
			]
		);

        $this->add_control(
            'marker_icon',
            [
                'label'                 => __( 'Choose Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => 'fa fa-calendar',
                'condition'             => [
                    'marker_type'   => 'icon'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'marker_size',
            [
                'label'                 => __( 'Marker Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 5,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-marker' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'marker_type!'  => 'none'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'marker_box_size',
            [
                'label'                 => __( 'Marker Box Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 10,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-marker' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .pp-timeline-connector-wrap' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pp-timeline-navigation:before, {{WRAPPER}} .pp-timeline-navigation .pp-slider-arrow' => 'bottom: calc( {{SIZE}}{{UNIT}}/2 );',
                ],
            ]
        );
        
        $this->start_controls_tabs( 'marker_tabs' );
        
        $this->start_controls_tab( 'tab_marker_normal', [ 'label' => __( 'Normal', 'power-pack' ) ] );

        $this->add_control(
            'marker_color',
            [
                'label'                 => __( 'Marker Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-marker' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'marker_bg',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-marker' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'marker_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-timeline-marker',
			]
		);

		$this->add_control(
			'marker_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-timeline-marker' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'marker_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-timeline-marker',
			]
		);
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 'tab_marker_hover', [ 'label' => __( 'Hover', 'power-pack' ) ] );

        $this->add_control(
            'marker_color_hover',
            [
                'label'                 => __( 'Marker Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-marker-wrapper:hover .pp-timeline-marker' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'marker_bg_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-marker-wrapper:hover .pp-timeline-marker' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'marker_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-marker-wrapper:hover .pp-timeline-marker' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 'tab_marker_focused', [ 'label' => __( 'Focused', 'power-pack' ) ] );

        $this->add_control(
            'marker_color_focused',
            [
                'label'                 => __( 'Marker Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-item-active .pp-timeline-marker, {{WRAPPER}} .slick-current .pp-timeline-marker' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'marker_bg_focused',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-item-active .pp-timeline-marker, {{WRAPPER}} .slick-current .pp-timeline-marker' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'marker_border_color_focused',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-item-active .pp-timeline-marker, {{WRAPPER}} .slick-current .pp-timeline-marker' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Dates
         */
        $this->start_controls_section(
            'section_dates_style',
            [
                'label'                 => __( 'Dates', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->start_controls_tabs( 'dates_tabs' );
        
        $this->start_controls_tab( 'tab_dates_normal', [ 'label' => __( 'Normal', 'power-pack' ) ] );

        $this->add_control(
            'dates_bg',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-card-date' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'dates_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-card-date' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'dates_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-timeline-card-date',
			]
		);

		$this->add_control(
			'dates_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-timeline-card-date' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'dates_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-timeline-card-date' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'dates_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-timeline-card-date',
			]
		);
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 'tab_dates_hover', [ 'label' => __( 'Hover', 'power-pack' ) ] );

        $this->add_control(
            'dates_bg_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-card-date:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'dates_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-card-date:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'dates_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-card-date:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 'tab_dates_focused', [ 'label' => __( 'Focused', 'power-pack' ) ] );

        $this->add_control(
            'dates_bg_focused',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-item-active .pp-timeline-card-date' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'dates_color_focused',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-item-active .pp-timeline-card-date' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'dates_border_color_focused',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-item-active .pp-timeline-card-date' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Connector
         */
        $this->start_controls_section(
            'section_connector_style',
            [
                'label'                 => __( 'Connector', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'connector_spacing',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' 	    => '',
                ],
                'range'                 => [
                    'px' 		=> [
                        'min' 	=> 0,
                        'max' 	=> 100,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-vertical.pp-timeline-left .pp-timeline-marker-wrapper' => 'margin-right: {{SIZE}}px;',
                    '{{WRAPPER}} .pp-timeline-vertical.pp-timeline-right .pp-timeline-marker-wrapper' => 'margin-left: {{SIZE}}px;',
                    '{{WRAPPER}} .pp-timeline-vertical.pp-timeline-center .pp-timeline-marker-wrapper' => 'margin-left: {{SIZE}}px; margin-right: {{SIZE}}px',
            
                    '(tablet){{WRAPPER}} .pp-timeline-vertical.pp-timeline-tablet-left .pp-timeline-marker-wrapper' => 'margin-right: {{SIZE}}px;',
                    '(tablet){{WRAPPER}} .pp-timeline-vertical.pp-timeline-tablet-right .pp-timeline-marker-wrapper' => 'margin-left: {{SIZE}}px;',
                    '(tablet){{WRAPPER}} .pp-timeline-vertical.pp-timeline-tablet-center .pp-timeline-marker-wrapper' => 'margin-left: {{SIZE}}px; margin-right: {{SIZE}}px',
            
                    '(mobile){{WRAPPER}} .pp-timeline-vertical.pp-timeline-mobile-left .pp-timeline-marker-wrapper' => 'margin-right: {{SIZE}}px !important;',
                    '(mobile){{WRAPPER}} .pp-timeline-vertical.pp-timeline-mobile-right .pp-timeline-marker-wrapper' => 'margin-left: {{SIZE}}px !important;',
                    '(mobile){{WRAPPER}} .pp-timeline-vertical.pp-timeline-mobile-center .pp-timeline-marker-wrapper' => 'margin-left: {{SIZE}}px !important; margin-right: {{SIZE}}px !important;',
            
                    '{{WRAPPER}} .pp-timeline-horizontal' 	=> 'margin-top: {{SIZE}}px;',
                    '{{WRAPPER}} .pp-timeline-navigation .pp-timeline-card-date-wrapper' 	=> 'margin-bottom: {{SIZE}}px;',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'connector_thickness',
            [
                'label'                 => __( 'Thickness', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 12,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-vertical .pp-timeline-connector' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pp-timeline-navigation:before' => 'height: {{SIZE}}{{UNIT}}; transform: translateY(calc({{SIZE}}{{UNIT}}/2))',
                ],
            ]
        );
        
        $this->start_controls_tabs( 'tabs_connector' );
        
        $this->start_controls_tab( 'tab_connector_normal', [ 'label' => __( 'Normal', 'power-pack' ) ] );
			
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'connector_bg',
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'classic', 'gradient' ],
                'exclude'               => [ 'image' ],
                'selector'              => '{{WRAPPER}} .pp-timeline-connector',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab( 'tab_connector_progress', [ 'label' => __( 'Progress', 'power-pack' ) ] );
			
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'connector_bg_progress',
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'classic', 'gradient' ],
                'exclude'               => [ 'image' ],
                'selector'              => '{{WRAPPER}} .pp-timeline-connector-inner',
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
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
                ],
            ]
        );
        
        $this->add_control(
			'arrow',
			[
				'label'                 => __( 'Choose Arrow', 'power-pack' ),
				'type'                  => Controls_Manager::ICON,
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
				'default'               => 'fa fa-angle-right',
				'frontend_available'    => true,
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
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
					'{{WRAPPER}} .pp-timeline-navigation .pp-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				],
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'arrows_box_size',
            [
                'label'                 => __( 'Arrows Box Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => '40' ],
                'range'                 => [
                    'px' => [
                        'min'   => 15,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-timeline-navigation .pp-slider-arrow' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; transform: translateY(calc({{SIZE}}{{UNIT}}/2))',
				],
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'align_arrows',
            [
                'label'                 => __( 'Align Arrows', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -40,
                        'max'   => 0,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-timeline-navigation .pp-arrow-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-timeline-navigation .pp-arrow-next' => 'right: {{SIZE}}{{UNIT}};',
				],
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_arrows_style' );

        $this->start_controls_tab(
            'tab_arrows_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-navigation .pp-slider-arrow' => 'background-color: {{VALUE}};',
                ],
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
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
                    '{{WRAPPER}} .pp-timeline-navigation .pp-slider-arrow' => 'color: {{VALUE}};',
                ],
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
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
				'selector'              => '{{WRAPPER}} .pp-timeline-navigation .pp-slider-arrow',
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
                ],
			]
		);

		$this->add_control(
			'arrows_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-timeline-navigation .pp-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
                ],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_arrows_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
                ],
            ]
        );

        $this->add_control(
            'arrows_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-navigation .pp-slider-arrow:hover' => 'background-color: {{VALUE}};',
                ],
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
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
                    '{{WRAPPER}} .pp-timeline-navigation .pp-slider-arrow:hover' => 'color: {{VALUE}};',
                ],
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
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
                    '{{WRAPPER}} .pp-timeline-navigation .pp-slider-arrow:hover' => 'border-color: {{VALUE}};',
                ],
                'condition'             => [
                    'layout'        => 'horizontal',
                    'arrows'        => 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_button_style',
            [
                'label'                 => __( 'Button', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
				],
            ]
        );

        $this->add_control(
            'button_spacing',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' 	    => 20,
                ],
                'range' 		=> [
                    'px' 		=> [
                        'min' 	=> 0,
                        'max' 	=> 60,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-timeline-button' => 'margin-top: {{SIZE}}px;',
                ],
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
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
                    'source'       => 'posts',
					'link_type'    => 'button',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
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
                    '{{WRAPPER}} .pp-timeline-button' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
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
                    '{{WRAPPER}} .pp-timeline-button' => 'color: {{VALUE}}',
                ],
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
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
				'selector'              => '{{WRAPPER}} .pp-timeline-button',
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
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
					'{{WRAPPER}} .pp-timeline-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'button_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-timeline-button',
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
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
					'{{WRAPPER}} .pp-timeline-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-timeline-button',
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
				],
			]
		);
        
        $this->add_control(
            'info_box_button_icon_heading',
            [
                'label'                 => __( 'Button Icon', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
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
                    'source'       => 'posts',
					'link_type'    => 'button',
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
                    'source'       => 'posts',
					'link_type'    => 'button',
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
                    '{{WRAPPER}} .pp-timeline-button:hover' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
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
                    '{{WRAPPER}} .pp-timeline-button:hover' => 'color: {{VALUE}}',
                ],
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
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
                    '{{WRAPPER}} .pp-timeline-button:hover' => 'border-color: {{VALUE}}',
                ],
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
				],
            ]
        );

		$this->add_control(
			'button_animation',
			[
				'label'                 => __( 'Animation', 'power-pack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-timeline-button:hover',
				'condition'             => [
                    'source'       => 'posts',
					'link_type'    => 'button',
				],
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();

    }

    /**
	 * Render timeline widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();
        
        $timeline_classes = array();
        
        $timeline_classes[] = 'pp-timeline';
        
        // Layout
        if ( $settings['layout'] ) {
            $timeline_classes[] = 'pp-timeline-' . $settings['layout'];
        }
        
        // Direction
        if ( $settings['direction'] ) {
            $timeline_classes[] = 'pp-timeline-' . $settings['direction'];
        }
        
        if ( $settings['direction_tablet'] ) {
            $timeline_classes[] = 'pp-timeline-tablet-' . $settings['direction_tablet'];
        }
        
        if ( $settings['direction_mobile'] ) {
            $timeline_classes[] = 'pp-timeline-mobile-' . $settings['direction_mobile'];
        }
        
        if ( $settings['dates'] == 'yes' ) {
            $timeline_classes[] = 'pp-timeline-dates';
        }
        
        if ( $settings['cards_arrows_alignment'] ) {
            $timeline_classes[] = 'pp-timeline-arrows-' . $settings['cards_arrows_alignment'];
        }
        
        $this->add_render_attribute( 'timeline', 'class', $timeline_classes );
        
        $this->add_render_attribute( 'timeline', 'data-timeline-layout', $settings['layout'] );
        
        $this->add_render_attribute( 'post-categories', 'class', 'pp-post-categories' );
        ?>
        <div class="pp-timeline-wrapper">
            <?php $this->render_horizontal_timeline_nav(); ?>

            <div <?php echo $this->get_render_attribute_string( 'timeline' ); ?>>
                <?php if ( $settings['layout'] == 'vertical' ) { ?>
                    <div class="pp-timeline-connector-wrap">
                        <div class="pp-timeline-connector">
                            <div class="pp-timeline-connector-inner">
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="pp-timeline-items">
                <?php
                    if ( $settings['source'] == 'posts' ) {
                        $this->render_source_posts();
                    } elseif ( $settings['source'] == 'custom' ) {
                        $this->render_source_custom();
                    }
                ?>
                </div>
            </div><!--.pp-timeline-->
        </div><!--.pp-timeline-wrapper-->
        <?php
    }

    /**
	 * Render vertical timeline output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_vertical_timeline() {
        $settings = $this->get_settings();
    }

    /**
	 * Render vertical timeline output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_horizontal_timeline_nav() {
        $settings = $this->get_settings();
        if ( $settings['layout'] == 'horizontal' ) {
            ?>
            <div class="pp-timeline-navigation">
                <?php
                    $i = 1;
                    if ( $settings['source'] == 'custom' ) {
                        foreach ( $settings['items'] as $index => $item ) {
                        
                            $date = $item['timeline_item_date'];
                            
                            $this->render_connector_marker( $i, $date );

                            $i++;
                        }
                    } if ( $settings['source'] == 'posts' ) {
                        $args = $this->get_posts_query_arguments();
                        $posts_query = new \WP_Query( $args );

                        if ( $posts_query->have_posts() ) : while ($posts_query->have_posts()) : $posts_query->the_post();
                        
                            $date = get_the_date();
                        
                            $this->render_connector_marker( $i, $date );
                        
                        $i++; endwhile; endif; wp_reset_query();
                    }
                ?>
            </div>
            <?php
        }
    }

    /**
	 * Render custom content output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_connector_marker( $number = '', $date = '' ) {
        $settings = $this->get_settings();
        ?>
        <div class="pp-timeline-marker-wrapper">
            <?php if ( $settings['layout'] == 'horizontal' && $settings['dates'] == 'yes' ) { ?>
                <div class="pp-timeline-card-date-wrapper">
                    <div class="pp-timeline-card-date">
                        <?php echo $date; ?>
                    </div>
                </div>
            <?php } ?>

            <div class="pp-timeline-marker">
                <?php
                    if ( $settings['marker_type'] == 'icon' ) {
                        echo '<i class="'. $settings['marker_icon'] .'"></i>';
                    } elseif ( $settings['marker_type'] == 'number' ) {
                        echo $number;
                    } elseif ( $settings['marker_type'] == 'letter' ) {
                        $alphabets = range('A', 'Z');

                        $alphabets = array_combine( range(1, count( $alphabets ) ), $alphabets );

                        echo $alphabets[ $number ];
                    }
                ?>
            </div>
        </div>
        <?php if ( $settings['layout'] == 'vertical' ) { ?>
            <div class="pp-timeline-card-date-wrapper">
                <?php if ( $settings['dates'] == 'yes' ) { ?>
                    <div class="pp-timeline-card-date">
                        <?php echo $date; ?>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <?php
    }

    /**
	 * Render custom content output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_source_custom() {
        $settings = $this->get_settings();
        
        $i = 1;
        
        foreach ( $settings['items'] as $index => $item ) {
            
            $item_key       = $this->get_repeater_setting_key( 'item', 'items', $index );
            $title_key      = $this->get_repeater_setting_key( 'timeline_item_title', 'items', $index );
            $content_key    = $this->get_repeater_setting_key( 'timeline_item_content', 'items', $index );
            
            $this->add_inline_editing_attributes( $title_key, 'basic' );
            $this->add_inline_editing_attributes( $content_key, 'advanced' );
            
            $this->add_render_attribute( $item_key, 'class', [
                'pp-timeline-item',
                'elementor-repeater-item-' . esc_attr( $item['_id'] )
            ] );
            
            if ( $settings['animate_cards'] === 'yes' ) {
				$this->add_render_attribute( $item_key, 'class', 'pp-timeline-item-hidden' );
			}
            
            $this->add_render_attribute( $title_key, 'class', 'pp-timeline-card-title' );
            
            $this->add_render_attribute( $content_key, 'class', 'pp-timeline-card-content' );
            
            if ( ! empty( $item['timeline_item_link']['url'] ) ) {
                $this->add_render_attribute( 'link', 'href', esc_url( $item['timeline_item_link']['url'] ) );

                if ( $item['timeline_item_link']['is_external'] ) {
                    $this->add_render_attribute( 'link', 'target', '_blank' );
                }

                if ( $item['timeline_item_link']['nofollow'] ) {
                    $this->add_render_attribute( 'link', 'rel', 'nofollow' );
                }
            }
            ?>
            <div <?php echo $this->get_render_attribute_string( $item_key ); ?>>
                <div class="pp-timeline-card-wrapper">
                    <?php if ( $item['timeline_item_link']['url'] != '' ) { ?>
                    <a <?php echo $this->get_render_attribute_string( 'link' ); ?>>
                    <?php } ?>
                    <div class="pp-timeline-arrow"></div>
                    <div class="pp-timeline-card">
                        <?php if ( $item['card_image'] == 'yes' && ! empty( $item['image']['url'] ) ) { ?>
                            <div class="pp-timeline-card-image">
                                <?php echo Group_Control_Image_Size::get_attachment_image_html( $item ); ?>
                            </div>
						<?php } ?>
                        <?php if ( $settings['post_title'] == 'show' || $settings['dates'] == 'yes' ) { ?>
                            <div class="pp-timeline-card-title-wrap">
                                <?php if ( $item['timeline_item_title'] != '' ) { ?>
                                    <?php if ( $settings['layout'] == 'vertical' ) { ?>
                                        <?php if ( $settings['dates'] == 'yes' ) { ?>
                                            <div class="pp-timeline-card-date">
                                                <?php echo $item['timeline_item_date']; ?>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                    <h2 <?php echo $this->get_render_attribute_string( $title_key ); ?>>
                                        <?php
                                            echo $item['timeline_item_title'];
                                        ?>
                                    </h2>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ( $item['timeline_item_content'] != '' ) { ?>
                            <div <?php echo $this->get_render_attribute_string( $content_key ); ?>>
                                <?php
                                    echo $this->parse_text_editor( $item['timeline_item_content'] );
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                    <?php if ( $item['timeline_item_link'] != '' ) { ?>
                    </a>
                    <?php } ?>
                </div>
                
                <?php if ( $settings['layout'] == 'vertical' ) { ?>
                    <?php $this->render_connector_marker( $i, $item['timeline_item_date'] ); ?>
                <?php } ?>
            </div>
            <?php
            $i++;
        }
    }

    /**
	 * Render posts output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_source_posts() {
        $settings = $this->get_settings();

        $i = 1;

        // Query Arguments
        $args = $this->get_posts_query_arguments();
        $posts_query = new \WP_Query( $args );

        if ( $posts_query->have_posts() ) : while ($posts_query->have_posts()) : $posts_query->the_post();
            
            $item_key = 'timeline-item' . $i;

            if ( has_post_thumbnail() ) {
                $image_id = get_post_thumbnail_id( get_the_ID() );
                $pp_thumb_url = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image_size', $settings );
            } else {
                $pp_thumb_url = '';
            }
        
            $this->add_render_attribute( $item_key, 'class', [
                'pp-timeline-item',
                'pp-timeline-item-' . intval( $i )
            ] );
            
            if ( $settings['animate_cards'] === 'yes' ) {
				$this->add_render_attribute( $item_key, 'class', 'pp-timeline-item-hidden' );
			}
            ?>
            <div <?php echo $this->get_render_attribute_string( $item_key ); ?>>
                <div class="pp-timeline-card-wrapper">
                    <?php if ( $settings['link_type'] == 'card' ) { ?>
                    <a href="<?php the_permalink() ?>">
                    <?php } ?>
                    <div class="pp-timeline-arrow"></div>
                    <div class="pp-timeline-card">
                        <?php if ( $settings['post_image'] == 'show' ) { ?>
                            <div class="pp-timeline-card-image">
                                <img src="<?php echo esc_url( $pp_thumb_url ); ?>">
                            </div>
                        <?php } ?>
                        <?php if ( $settings['post_title'] == 'show' || $settings['dates'] == 'yes' ) { ?>
                            <div class="pp-timeline-card-title-wrap">
                                <?php if ( $settings['layout'] == 'vertical' ) { ?>
                                    <?php if ( $settings['dates'] == 'yes' ) { ?>
                                        <div class="pp-timeline-card-date">
                                            <?php echo get_the_date(); ?>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ( $settings['post_title'] == 'show' ) { ?>
                                    <h2 class="pp-timeline-card-title">
                                        <?php
                                            if ( $settings['link_type'] == 'title' ) {
                                                printf( '<a href="%1$s">%2$s</a>', get_permalink(), get_the_title() );
                                            } else {
                                                the_title();
                                            }
                                        ?>
                                    </h2>
                                <?php } ?>
                                <?php if ( $settings['post_meta'] == 'show' ) { ?>
                                    <div class="pp-timeline-meta">
                                        <?php if ( $settings['post_author'] == 'show' ) { ?>
                                            <span class="pp-timeline-author">
                                                <?php the_author(); ?>
                                            </span>
                                        <?php } ?>
                                        <?php if ( $settings['post_category'] == 'show' ) { ?>
                                            <span class="pp-timeline-category">
                                                <?php
                                                    $category = get_the_category();
                                                    if ( $category ) {
                                                        echo esc_attr( $category[0]->name );
                                                    }
                                                ?>
                                            </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <div class="pp-timeline-card-content">
                            <?php if ( $settings['post_content'] == 'show' ) { ?>
                                <div class="pp-timeline-card-excerpt">
                                    <?php
                                        $this->render_post_content();
                                    ?>
                                </div>
                            <?php } ?>
                            <?php if ( $settings['link_type'] == 'button' && $settings['button_text'] ) { ?>
                                <?php
                                    $this->add_render_attribute( 'button', 'class', [
                                            'pp-timeline-button',
                                            'elementor-button',
                                            'elementor-size-' . $settings['button_size'],
                                        ]
                                    );                                           
                                ?>
                                <a <?php echo $this->get_render_attribute_string( 'button' ); ?> href="<?php the_permalink() ?>">
                                    <span class="pp-timeline-button-text">
                                        <?php echo esc_attr( $settings['button_text'] ); ?>
                                    </span>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if ( $settings['link_type'] == 'card' ) { ?>
                    </a>
                    <?php } ?>
                </div>
                
                <?php
                    if ( $settings['layout'] == 'vertical' ) {
                        $this->render_connector_marker( $i, get_the_date() );
                    }
                ?>
            </div>
            <?php
        $i++; endwhile; endif; wp_reset_query();
    }

    /**
	 * Get post query arguments.
	 *
	 * @access protected
	 */
    protected function get_posts_query_arguments() {
        $settings = $this->get_settings();
        $pp_posts_count = absint( $settings['posts_per_page'] );

        // Post Authors
        $pp_tiled_post_author = '';
        $pp_tiled_post_authors = $settings['authors'];
        if ( !empty( $pp_tiled_post_authors) ) {
            $pp_tiled_post_author = implode( ",", $pp_tiled_post_authors );
        }

        // Post Categories
        $pp_tiled_post_cat = '';
        $pp_tiled_post_cats = $settings['categories'];
        if ( !empty( $pp_tiled_post_cats) ) {
            $pp_tiled_post_cat = implode( ",", $pp_tiled_post_cats );
        }

        // Query Arguments
        $args = array(
            'post_status'           => array( 'publish' ),
            'post_type'             => $settings['post_type'],
            'post__in'              => '',
            'cat'                   => $pp_tiled_post_cat,
            'author'                => $pp_tiled_post_author,
            'tag__in'               => $settings['tags'],
            'orderby'               => $settings['orderby'],
            'order'                 => $settings['order'],
            'post__not_in'          => $settings['exclude_posts'],
            'offset'                => $settings['offset'],
            'ignore_sticky_posts'   => 1,
            'showposts'             => $pp_posts_count
        );
        
        return $args;
    }

    /**
	 * Get post content.
	 *
	 * @access protected
	 */
    protected function render_post_content() {
        $settings = $this->get_settings();
        
        $content_length = $settings['content_length'];
        
		if ( $content_length == '' ) {
			$content = get_the_excerpt();
		} else {
			$content = wp_trim_words( get_the_content(), $content_length );
		}
        
		echo $content;
    }

    /**
	 * Render timeline widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {}
}