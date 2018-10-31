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
 * Card Slider Widget
 */
class Card_Slider extends Powerpack_Widget {
    
    /**
	 * Retrieve card slider widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-card-slider';
    }

    /**
	 * Retrieve card slider widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Card Slider', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the card slider widget belongs to.
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
	 * Retrieve card slider widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-card-slider power-pack-admin-icon';
    }

	/**
	 * Retrieve the list of scripts the card slider widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
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
	 * Register card slider widget controls.
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
            'section_card',
            [
                'label'                 => __( 'Card', 'power-pack' ),
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
            'posts_count',
            [
                'label'                 => __( 'Posts Count', 'power-pack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 4,
                'condition'             => [
                    'source'	=> 'posts'
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
                   'image'      => __( 'Image', 'power-pack' ),
                   'button'     => __( 'Button', 'power-pack' ),
                   'box'        => __( 'Box', 'power-pack' ),
                ],
                'default'               => '',
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
                    'link_type'     => 'button',
                ]
            ]
        );
        
        $this->add_control(
            'date',
            [
                'label'                 => __( 'Date', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'return_value'          => 'yes',
                'frontend_available'    => true,
            ]
        );

        $this->add_control(
            'date_icon',
            [
                'label'                 => __( 'Date Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => 'fa fa-calendar',
                'condition'             => [
                    'date'      => 'yes'
                ]
            ]
        );
        
        $repeater = new Repeater();
        
        $repeater->start_controls_tabs( 'card_items_tabs' );

        $repeater->start_controls_tab( 'tab_card_items_content', [ 'label' => __( 'Content', 'power-pack' ) ] );
        
            $repeater->add_control(
                'card_date',
                [
                    'label'             => __( 'Date', 'power-pack' ),
                    'type'              => Controls_Manager::TEXT,
                    'label_block'       => false,
                    'default'           => __( '1 June 2018', 'power-pack' ),
                ]
            );
        
            $repeater->add_control(
                'card_title',
                [
                    'label'             => __( 'Title', 'power-pack' ),
                    'type'              => Controls_Manager::TEXT,
                    'label_block'       => false,
                    'default'           => '',
                ]
            );
        
            $repeater->add_control(
                'card_content',
                [
                    'label'             => __( 'Content', 'power-pack' ),
                    'type'              => Controls_Manager::WYSIWYG,
                    'default'           => '',
                ]
            );

            $repeater->add_control(
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
                        'url' => '',
                    ],
                ]
            );
        
        $repeater->end_controls_tab();

        $repeater->start_controls_tab( 'tab_card_items_image', [ 'label' => __( 'Image', 'power-pack' ) ] );
        
        $repeater->add_control(
            'card_image',
            [
                'label'                 => __( 'Show Image', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
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

        $repeater->end_controls_tabs();

        $this->add_control(
            'items',
            [
                'label'                 => '',
                'type'                  => Controls_Manager::REPEATER,
                'default'               => [
                    [
                        'card_date'    => __( '1 May 2018', 'power-pack' ),
                        'card_title'   => __( 'Card Slider Item 1', 'power-pack' ),
                        'card_content' => __( 'I am card slider Item content. Click here to edit this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                    ],
                    [
                        'card_date'    => __( '1 June 2018', 'power-pack' ),
                        'card_title'   => __( 'Card Slider Item 2', 'power-pack' ),
                        'card_content' => __( 'I am card slider Item content. Click here to edit this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                    ],
                    [
                        'card_date'    => __( '1 July 2018', 'power-pack' ),
                        'card_title'   => __( 'Card Slider Item 3', 'power-pack' ),
                        'card_content' => __( 'I am card slider Item content. Click here to edit this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                    ],
                    [
                        'card_date'    => __( '1 August 2018', 'power-pack' ),
                        'card_title'   => __( 'Card Slider Item 4', 'power-pack' ),
                        'card_content' => __( 'I am card slider Item content. Click here to edit this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                    ],
                ],
                'fields'                => array_values( $repeater->get_controls() ),
                'title_field'           => '{{{ card_date }}}',
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
            'post_excerpt',
            [
                'label'                 => __( 'Post Excerpt', 'power-pack' ),
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
            'excerpt_length',
            [
                'label'                 => __( 'Excerpt Length', 'power-pack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 20,
                'min'                   => 0,
                'max'                   => 58,
                'step'                  => 1,
                'condition'             => [
                    'source'        => 'posts',
                    'post_excerpt'  => 'show'
                ]
            ]
        );
        
        $this->add_control(
            'meta_heading',
            [
                'label'                 => __( 'Post Meta', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
                'condition'             => [
                    'source'	=> 'posts'
                ]
            ]
        );
        
        $this->add_control(
            'post_meta',
            [
                'label'                 => __( 'Post Meta', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'return_value'          => 'yes',
                'frontend_available'    => true,
                'condition'             => [
                    'source'	=> 'posts'
                ]
            ]
        );
        
        $this->add_control(
            'post_author',
            [
                'label'                 => __( 'Author', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'return_value'          => 'yes',
                'frontend_available'    => true,
                'condition'             => [
                    'source'	=> 'posts',
                    'post_meta'	=> 'yes'
                ]
            ]
        );

        $this->add_control(
            'author_icon',
            [
                'label'                 => __( 'Author Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => 'fa fa-user',
                'condition'             => [
                    'source'        => 'posts',
                    'post_author'   => 'yes',
                    'post_meta'     => 'yes'
                ]
            ]
        );
        
        $this->add_control(
            'post_category',
            [
                'label'                 => __( 'Category', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'return_value'          => 'yes',
                'frontend_available'    => true,
                'condition'             => [
                    'source'	=> 'posts',
                    'post_meta'	=> 'yes'
                ]
            ]
        );

        $this->add_control(
            'category_icon',
            [
                'label'                 => __( 'Category Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => 'fa fa-folder-open',
                'condition'             => [
                    'source'        => 'posts',
                    'post_category' => 'yes',
                    'post_meta'     => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Additional Options
         */
        $this->start_controls_section(
            'section_additional_options',
            [
                'label'                 => __( 'Additional Options', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'slider_speed',
            [
                'label'                 => __( 'Slider Speed', 'power-pack' ),
                'description'           => __( 'Duration of transition between slides (in ms)', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => 400 ],
                'range'                 => [
                    'px' => [
                        'min'   => 100,
                        'max'   => 3000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'separator'             => 'before',
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
                'frontend_available'    => true,
            ]
        );
        
        $this->add_control(
            'autoplay_speed',
            [
                'label'                 => __( 'Autoplay Speed', 'power-pack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 2000,
                'min'                   => 500,
                'max'                   => 5000,
                'step'                  => 1,
                'frontend_available'    => true,
                'condition'             => [
                    'autoplay'      => 'yes',
                ]
            ]
        );
        
        $this->add_control(
            'loop',
            [
                'label'                 => __( 'Infinite Loop', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
            ]
        );
        
        $this->add_control(
            'grab_cursor',
            [
                'label'                 => __( 'Grab Cursor', 'power-pack' ),
                'description'           => __( 'Shows grab cursor when you hover over the slider', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
                'separator'             => 'before',
                'frontend_available'    => true,
            ]
        );
        
        $this->add_control(
            'navigation_heading',
            [
                'label'                 => __( 'Navigation', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );
        
        $this->add_control(
            'pagination',
            [
                'label'                 => __( 'Pagination', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
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
                'frontend_available'    => true,
                'condition'             => [
                    'pagination'        => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
        
        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Card
         */
        $this->start_controls_section(
            'section_card_style',
            [
                'label'                 => __( 'Card', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'card_max_width',
            [
                'label'                 => __( 'Max Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 200,
                        'max'   => 1600,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-card-slider' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'card_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-card-slider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-card-slider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'card_text_align',
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
                    '{{WRAPPER}} .pp-card-slider' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->start_controls_tabs( 'card_tabs' );
        
        $this->start_controls_tab( 'tab_card_normal', [ 'label' => __( 'Normal', 'power-pack' ) ] );

        $this->add_control(
            'card_bg',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-card-slider' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'card_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-card-slider',
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-card-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'card_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-card-slider',
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
            'title_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-card-slider-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-card-slider-title',
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
                    '{{WRAPPER}} .pp-card-slider-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_control(
			'heading_date',
			[
				'label'                 => __( 'Date', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'date'     => 'yes',
				],
			]
		);

        $this->add_control(
            'date_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-card-slider-date' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'date'     => 'yes',
				],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'date_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-card-slider-date',
				'condition'             => [
					'date'     => 'yes',
				],
            ]
        );
        
        $this->add_responsive_control(
            'date_margin_bottom',
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
                    '{{WRAPPER}} .pp-card-slider-date' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
				'condition'             => [
					'date'     => 'yes',
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
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-card-slider-content' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'card_text_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-card-slider-content',
            ]
        );

		$this->add_control(
			'heading_meta',
			[
				'label'                 => __( 'Post Meta', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'source'       => 'posts',
					'post_meta'    => 'yes',
				],
			]
		);

        $this->add_control(
            'meta_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-card-slider-meta' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'source'       => 'posts',
					'post_meta'    => 'yes',
				],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'meta_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-card-slider-meta',
				'condition'             => [
					'source'       => 'posts',
					'post_meta'    => 'yes',
				],
            ]
        );
        
        $this->add_control(
            'meta_spacing',
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
                    '{{WRAPPER}} .pp-card-slider-meta' 	=> 'margin-bottom: {{SIZE}}px;'
                ],
				'condition'             => [
					'source'       => 'posts',
					'post_meta'    => 'yes',
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
                    '{{WRAPPER}} .pp-card-slider:hover' => 'background-color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-card-slider:hover' => 'border-color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-card-slider:hover .pp-card-slider-title' => 'color: {{VALUE}}',
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
                    '{{WRAPPER}} .pp-card-slider:hover .pp-card-slider-content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'meta_color_hover',
            [
                'label'                 => __( 'Post Meta Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-card-slider:hover .pp-card-slider-meta' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'source'       => 'posts',
					'post_meta'    => 'yes',
				],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

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

        $this->add_control(
            'image_direction',
            [
                'label'                 => __( 'Direction', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
                'toggle'                => false,
                'default'               => 'left',
                'options'               => [
                    'left' 		=> [
                        'title' => __( 'Left', 'power-pack' ),
                        'icon' 	=> 'eicon-h-align-left',
                    ],
                    'right' 	=> [
                        'title' => __( 'Right', 'power-pack' ),
                        'icon' 	=> 'eicon-h-align-right',
                    ],
                ],
                'prefix_class'          => 'pp-card-slider-image-',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'image_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-card-slider-image',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-card-slider-image, {{WRAPPER}} .pp-card-slider-image:after, {{WRAPPER}} .pp-card-slider-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'image_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 500,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-card-slider-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'image_height',
            [
                'label'                 => __( 'Height', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 500,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-card-slider-image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'image_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-card-slider-image' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'image_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-card-slider-image',
			]
		);

		$this->add_control(
			'image_overlay_heading',
			[
				'label'                 => __( 'Overlay', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'image_overlay',
                'label'                 => __( 'Background', 'power-pack' ),
                'types'                 => [ 'classic', 'gradient' ],
                'selector'              => '{{WRAPPER}} .pp-card-slider-image:after',
                'exclude'               => [ 'image' ],
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
                'label'                 => __( 'Button', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
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
                    '{{WRAPPER}} .pp-card-slider-button-wrap' => 'margin-top: {{SIZE}}px;',
                ],
				'condition'             => [
					'link_type'    => 'button',
				],
            ]
        );

		$this->add_control(
			'button_size',
			[
				'label'                 => __( 'Size', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'sm',
				'options'               => [
					'xs' => __( 'Extra Small', 'power-pack' ),
					'sm' => __( 'Small', 'power-pack' ),
					'md' => __( 'Medium', 'power-pack' ),
					'lg' => __( 'Large', 'power-pack' ),
					'xl' => __( 'Extra Large', 'power-pack' ),
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
                'label'                 => __( 'Normal', 'power-pack' ),
				'condition'             => [
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
                    '{{WRAPPER}} .pp-card-slider-button' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
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
                    '{{WRAPPER}} .pp-card-slider-button' => 'color: {{VALUE}}',
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
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-card-slider-button',
				'condition'             => [
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
					'{{WRAPPER}} .pp-card-slider-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-card-slider-button',
				'condition'             => [
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
					'{{WRAPPER}} .pp-card-slider-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector'              => '{{WRAPPER}} .pp-card-slider-button',
				'condition'             => [
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
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
                'condition'             => [
					'link_type'    => 'button',
                    'button_icon!' => '',
                ],
				'selectors'             => [
					'{{WRAPPER}} .pp-card-slider .pp-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
				'condition'             => [
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
                    '{{WRAPPER}} .pp-card-slider-button:hover' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
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
                    '{{WRAPPER}} .pp-card-slider-button:hover' => 'color: {{VALUE}}',
                ],
				'condition'             => [
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
                    '{{WRAPPER}} .pp-card-slider-button:hover' => 'border-color: {{VALUE}}',
                ],
				'condition'             => [
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
					'link_type'    => 'button',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-card-slider-button:hover',
				'condition'             => [
					'link_type'    => 'button',
				],
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Dots
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_dots_style',
            [
                'label'                 => __( 'Dots', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
                ],
            ]
        );

        $this->add_control(
            'dots_position',
            [
                'label'                 => __( 'Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'aside'      => __( 'Aside', 'power-pack' ),
                   'bottom'     => __( 'Bottom', 'power-pack' ),
                ],
                'default'               => 'aside',
                'prefix_class'          => 'pp-card-slider-dots-',
            ]
        );

		$this->add_responsive_control(
			'dots_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-card-slider .swiper-pagination' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
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
                    '(desktop){{WRAPPER}}.pp-card-slider-dots-aside .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
                    '(desktop){{WRAPPER}}.pp-card-slider-dots-bottom .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                    '(tablet){{WRAPPER}}.pp-card-slider-dots-aside .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin-top: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}}',
                    '(tablet){{WRAPPER}}.pp-card-slider-dots-bottom .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                    '(mobile){{WRAPPER}}.pp-card-slider-dots-aside .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin-top: 0; margin-bottom: 0; margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                    '(mobile){{WRAPPER}}.pp-card-slider-dots-bottom .swiper-pagination-bullets .swiper-pagination-bullet' => 'margin-top: 0; margin-bottom: 0; margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
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
                    '{{WRAPPER}} .pp-card-slider .swiper-pagination-bullet' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dots_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-card-slider .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dots_height',
            [
                'label'                 => __( 'Height', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-card-slider .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
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
				'selector'              => '{{WRAPPER}} .pp-card-slider .swiper-pagination-bullet',
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
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
					'{{WRAPPER}} .pp-card-slider .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
                ],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
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
                    '{{WRAPPER}} .pp-card-slider .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
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
                    '{{WRAPPER}} .pp-card-slider .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
                ],
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_active',
            [
                'label'                 => __( 'Active', 'power-pack' ),
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
                ],
            ]
        );

        $this->add_control(
            'dot_color_active',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-card-slider .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dots_width_active',
            [
                'label'                 => __( 'Width', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-card-slider .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
                ],
            ]
        );
        
        $this->add_responsive_control(
            'dots_height_active',
            [
                'label'                 => __( 'Height', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-card-slider .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    'pagination'        => 'yes',
                    'pagination_type'   => 'bullets'
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

    }

    /**
	 * Render card slider widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();
        
        $this->add_render_attribute( 'card-slider', 'class', 'pp-card-slider' );
        
        if ( $settings['image_direction'] ) {
            $this->add_render_attribute( 'card-slider', 'class', 'pp-card-slider-image-' . $settings['image_direction'] );
        }
        ?>

        <div class="pp-card-slider-wrapper">
            <div class="pp-card-slider">
                <div class="swiper-wrapper">
                    <?php
                        if ( $settings['source'] == 'posts' ) {
                            $this->render_source_posts();
                        } elseif ( $settings['source'] == 'custom' ) {
                            $this->render_source_custom();
                        }
                    ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
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
            $title_key      = $this->get_repeater_setting_key( 'card_title', 'items', $index );
            $content_key    = $this->get_repeater_setting_key( 'card_content', 'items', $index );
            
            /*$this->add_inline_editing_attributes( $title_key, 'basic' );
            $this->add_inline_editing_attributes( $content_key, 'advanced' );*/
            
            $this->add_render_attribute( $item_key, 'class', [
                'pp-card-slider-item',
                'swiper-slide',
                'elementor-repeater-item-' . esc_attr( $item['_id'] )
            ] );
            
            $this->add_render_attribute( $title_key, 'class', 'pp-card-slider-title' );
            
            $this->add_render_attribute( $content_key, 'class', 'pp-card-slider-content' );
            ?>
            <div <?php echo $this->get_render_attribute_string( $item_key ); ?>>
                <?php if ( $settings['link_type'] == 'box' && $item['link']['url'] ) { ?>
                    <?php
                        $box_link_key = 'card-slider-box-link-' . $i;

                        $this->add_render_attribute(
                            $box_link_key,
                            [
                                'class' => 'pp-card-slider-box-link',
                                'href'  => $item['link']['url'],
                            ]
                        );

                        if ( $item['link']['is_external'] ) {
                            $this->add_render_attribute( $box_link_key, 'target', '_blank' );
                        }

                        if ( $item['link']['nofollow'] ) {
                            $this->add_render_attribute( $box_link_key, 'rel', 'nofollow' );
                        }
                    ?>
                    <a <?php echo $this->get_render_attribute_string( $box_link_key ); ?>></a>
                <?php } ?>
                <?php if ( $item['card_image'] == 'yes' && ! empty( $item['image']['url'] ) ) { ?>
                    <div class="pp-card-slider-image">
                        <?php
                            if ( $settings['link_type'] == 'image' && $item['link']['url'] ) {
                                printf( '<a href="%1$s"></a>%2$s', $item['link']['url'], Group_Control_Image_Size::get_attachment_image_html( $item ) );
                            } else {
                                echo Group_Control_Image_Size::get_attachment_image_html( $item );
                            }
                        ?>
                    </div>
                <?php } ?>
                <div class="pp-card-slider-content-wrap">
                    <?php if ( $settings['date'] == 'yes' ) { ?>
                        <div class="pp-card-slider-date">
                            <?php if ( $settings['date_icon'] ) { ?>
                                <span class="pp-card-slider-meta-icon <?php echo $settings['date_icon']; ?>"></span>
                            <?php } ?>
                            <span class="pp-card-slider-meta-text">
                                <?php
                                    echo $item['card_date'];
                                ?>
                            </span>
                        </div>
                    <?php } ?>
                    <?php if ( $item['card_title'] != '' ) { ?>
                        <h2 <?php echo $this->get_render_attribute_string( $title_key ); ?>>
                            <?php
                                if ( $settings['link_type'] == 'title' && $item['link']['url'] ) {
                                    printf( '<a href="%1$s">%2$s</a>', $item['link']['url'], $item['card_title'] );
                                } else {
                                    echo $item['card_title'];
                                }
                            ?>
                        </h2>
                    <?php } ?>
                    <?php if ( $item['card_content'] != '' ) { ?>
                        <div <?php echo $this->get_render_attribute_string( $content_key ); ?>>
                            <?php
                                echo $this->parse_text_editor( $item['card_content'] );
                            ?>
                        </div>
                    <?php } ?>
                    <?php if ( $settings['link_type'] == 'button' && $settings['button_text'] ) { ?>
                        <?php
                            if ( ! empty( $item['link']['url'] ) ) {
                                
                                $button_key = $this->get_repeater_setting_key( 'button', 'items', $index );
                
                                $this->add_render_attribute( $button_key, 'href', esc_url( $item['link']['url'] ) );

                                if ( $item['link']['is_external'] ) {
                                    $this->add_render_attribute( $button_key, 'target', '_blank' );
                                }

                                if ( $item['link']['nofollow'] ) {
                                    $this->add_render_attribute( $button_key, 'rel', 'nofollow' );
                                }
                
                                $this->add_render_attribute( $button_key, 'class', [
                                        'pp-card-slider-button',
                                        'elementor-button',
                                        'elementor-size-' . $settings['button_size'],
                                    ]
                                );
                                ?>
                                <div class="pp-card-slider-button-wrap">
                                    <a <?php echo $this->get_render_attribute_string( $button_key ); ?> href="<?php the_permalink() ?>">
                                        <span class="pp-card-slider-button-text">
                                            <?php echo esc_attr( $settings['button_text'] ); ?>
                                        </span>
                                    </a>
                                </div>
                        <?php } ?>
                    <?php } ?>
                </div>
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
            
            $item_key = 'card-slider-item' . $i;

            if ( has_post_thumbnail() ) {
                $image_id = get_post_thumbnail_id( get_the_ID() );
                $pp_thumb_url = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image_size', $settings );
            } else {
                $pp_thumb_url = '';
            }
        
            $this->add_render_attribute( $item_key, 'class', [
                'pp-card-slider-item',
                'swiper-slide',
                'pp-card-slider-item-' . intval( $i )
            ] );
            ?>
            <div <?php echo $this->get_render_attribute_string( $item_key ); ?>>
                <?php if ( $settings['link_type'] == 'box' ) { ?>
                    <?php
                        $box_link_key = 'card-slider-box-link-' . $i;

                        $this->add_render_attribute(
                            $box_link_key,
                            [
                                'class' => 'pp-card-slider-box-link',
                                'href'  => get_permalink(),
                            ]
                        );
                    ?>
                    <a <?php echo $this->get_render_attribute_string( $box_link_key ); ?>></a>
                <?php } ?>
                <?php if ( $settings['post_image'] == 'show' && $pp_thumb_url ) { ?>
                    <div class="pp-card-slider-image">
                        <?php
                            if ( $settings['link_type'] == 'image' ) {
                                printf( '<a href="%1$s"></a>%2$s', get_permalink(), '<img src="' . esc_url( $pp_thumb_url ) . '">' );
                            } else {
                                echo '<img src="' . esc_url( $pp_thumb_url ) . '">';
                            }
                        ?>
                    </div>
                <?php } ?>
                <div class="pp-card-slider-content-wrap">
                    <?php if ( $settings['date'] == 'yes' ) { ?>
                        <div class="pp-card-slider-date">
                            <?php if ( $settings['date_icon'] ) { ?>
                                <span class="pp-card-slider-meta-icon <?php echo $settings['date_icon']; ?>"></span>
                            <?php } ?>
                            <span class="pp-card-slider-meta-text">
                                <?php
                                    echo get_the_date();
                                ?>
                            </span>
                        </div>
                    <?php } ?>
                    <?php if ( $settings['post_title'] == 'show' ) { ?>
                        <h2 class="pp-card-slider-title">
                            <?php
                                if ( $settings['link_type'] == 'title' ) {
                                    printf( '<a href="%1$s">%2$s</a>', get_permalink(), get_the_title() );
                                } else {
                                    the_title();
                                }
                            ?>
                        </h2>
                    <?php } ?>
                    <?php if ( $settings['post_meta'] == 'yes' ) { ?>
                        <div class="pp-card-slider-meta">
                            <?php if ( $settings['post_author'] == 'yes' ) { ?>
                                <span class="pp-content-author">
                                    <?php if ( $settings['author_icon'] ) { ?>
                                        <span class="pp-card-slider-meta-icon <?php echo $settings['author_icon']; ?>"></span>
                                    <?php } ?>
                                    <span class="pp-card-slider-meta-text">
                                        <?php echo get_the_author(); ?>
                                    </span>
                                </span>
                            <?php } ?>  
                            <?php if ( $settings['post_category'] == 'yes' ) { ?>
                                <span class="pp-post-category">
                                    <?php if ( $settings['category_icon'] ) { ?>
                                        <span class="pp-card-slider-meta-icon <?php echo $settings['category_icon']; ?>"></span>
                                    <?php } ?>
                                    <span class="pp-card-slider-meta-text">
                                        <?php
                                            $category = get_the_category();
                                            if ( $category ) {
                                                echo esc_attr( $category[0]->name );
                                            }
                                        ?>
                                    </span>
                                </span>
                            <?php } ?>  
                        </div>
                    <?php } ?>
                    <?php if ( $settings['post_excerpt'] == 'show' ) { ?>
                        <div class="pp-card-slider-content">
                            <?php
                                echo pp_custom_excerpt( $settings['excerpt_length'] );
                            ?>
                        </div>
                    <?php } ?>
                    <?php if ( $settings['link_type'] == 'button' && $settings['button_text'] ) { ?>
                        <?php
                            $button_key = 'card-slider-button' . $i;
                
                            $this->add_render_attribute( $button_key, 'class', [
                                    'pp-card-slider-button',
                                    'elementor-button',
                                    'elementor-size-' . $settings['button_size'],
                                ]
                            );                                           
                        ?>
                        <div class="pp-card-slider-button-wrap">
                            <a <?php echo $this->get_render_attribute_string( $button_key ); ?> href="<?php the_permalink() ?>">
                                <span class="pp-card-slider-button-text">
                                    <?php echo esc_attr( $settings['button_text'] ); ?>
                                </span>
                            </a>
                        </div>
                    <?php } ?>
                </div>
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
        $pp_posts_count = absint( $settings['posts_count'] );

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
	 * Render card slider widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {}
}