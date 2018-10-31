<?php
namespace PowerpackElements\Modules\Posts\Widgets;

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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tiled Posts Widget
 */
class Tiled_Posts extends Powerpack_Widget {
    
    /**
	 * Retrieve tiled posts widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-tiled-posts';
    }

    /**
	 * Retrieve tiled posts widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Tiled Posts', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the tiled posts widget belongs to.
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
	 * Retrieve tiled posts widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-tiled-post power-pack-admin-icon';
    }

    /**
	 * Register tiled posts widget controls.
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
         * Content Tab: Layout
         */
        $this->start_controls_section(
            'section_post_settings',
            [
                'label'             => __( 'Layout', 'power-pack' ),
            ]
        );

		$this->add_control(
			'layout',
			[
				'label'             => __( 'Layout', 'power-pack' ),
				'type'              => Controls_Manager::CHOOSE,
				'label_block'       => true,
				'toggle'            => false,
				'options'           => [
					'layout-1'  => [
						'title' => __( 'Layout 1', 'power-pack' ),
						'icon'  => 'ppicon-layout-1',
					],
					'layout-2'  => [
						'title' => __( 'Layout 2', 'power-pack' ),
						'icon'  => 'ppicon-layout-2',
					],
					'layout-3'  => [
						'title' => __( 'Layout 3', 'power-pack' ),
						'icon'  => 'ppicon-layout-3',
					],
					'layout-4'  => [
						'title' => __( 'Layout 4', 'power-pack' ),
						'icon'  => 'ppicon-layout-4',
					],
					'layout-5'  => [
						'title' => __( 'Layout 5', 'power-pack' ),
						'icon'  => 'ppicon-layout-5',
					],
				],
				'separator'         => 'none',
				'default'           => 'layout-1',
			]
		);

		$this->add_control(
			'content_vertical_position',
			[
				'label'             => __( 'Content Position', 'power-pack' ),
				'type'              => Controls_Manager::CHOOSE,
				'label_block'       => false,
				'options'           => [
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
				'separator'         => 'before',
				'default'           => 'bottom',
			]
		);
        
        $this->add_control(
            'post_title',
            [
                'label'             => __( 'Post Title', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
			'post_title_length',
			[
				'label'             => __( 'Post Title Length', 'power-pack' ),
				'title'             => __( 'In characters', 'power-pack' ),
                'description'       => __( 'Leave blank to show full title', 'power-pack' ),
				'type'              => Controls_Manager::NUMBER,
				'step'              => 1,
                'condition'         => [
                    'post_title'    => 'yes'
                ]
			]
		);

        $this->add_control(
            'fallback_image',
            [
                'label'             => __( 'Fallback Image', 'power-pack' ),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                   ''               => __( 'None', 'power-pack' ),
                   'placeholder'    => __( 'Placeholder', 'power-pack' ),
                   'custom'         => __( 'Custom', 'power-pack' ),
                ],
                'default'           => '',
                'separator'         => 'before',
            ]
        );

		$this->add_control(
			'fallback_image_custom',
			[
				'label'             => __( 'Fallback Image Custom', 'power-pack' ),
				'type'              => Controls_Manager::MEDIA,
                'condition'         => [
                    'fallback_image'    => 'custom'
                ]
			]
		);
        
        $this->add_control(
            'large_tile_heading',
            [
                'label'             => __( 'Large Tile', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
                'condition'         => [
                    'layout!'   => 'layout-5'
                ]
            ]
        );
		
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'              => 'image_size',
				'label'             => __( 'Image Size', 'power-pack' ),
				'default'           => 'medium_large',
                'condition'         => [
                    'layout!'   => 'layout-5'
                ]
			]
		);
        
        $this->add_control(
            'post_excerpt',
            [
                'label'             => __( 'Post Excerpt', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
                'condition'         => [
                    'layout!'   => 'layout-5'
                ]
            ]
        );
        
        $this->add_control(
            'excerpt_length',
            [
                'label'             => __( 'Excerpt Length', 'power-pack' ),
                'type'              => Controls_Manager::NUMBER,
                'default'           => 20,
                'min'               => 0,
                'max'               => 58,
                'step'              => 1,
                'condition'         => [
                    'layout!'       => 'layout-5',
                    'post_excerpt'  => 'yes'
                ]
            ]
        );
        
        $this->add_control(
            'small_tiles_heading',
            [
                'label'             => __( 'Small Tiles', 'power-pack' ),
                'type'              => Controls_Manager::HEADING,
                'separator'         => 'before',
            ]
        );
		
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'              => 'image_size_small',
				'label'             => __( 'Image Size', 'power-pack' ),
				'default'           => 'medium_large',
			]
		);
        
        $this->add_control(
            'post_excerpt_small',
            [
                'label'             => __( 'Post Excerpt', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
            'excerpt_length_small',
            [
                'label'             => __( 'Excerpt Length', 'power-pack' ),
                'type'              => Controls_Manager::NUMBER,
                'default'           => 20,
                'min'               => 0,
                'max'               => 58,
                'step'              => 1,
                'condition'         => [
                    'post_excerpt_small' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Other Posts
         */
        $this->start_controls_section(
            'section_other_posts',
            [
                'label'             => __( 'Other Posts', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'other_posts',
            [
                'label'             => __( 'Show Other Posts', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'return_value'      => 'yes',
            ]
        );
        
        $this->add_control(
			'other_posts_count',
			[
				'label'             => __( 'Posts Count', 'power-pack' ),
                'description'       => __( 'Leave blank to show all posts', 'power-pack' ),
				'type'              => Controls_Manager::NUMBER,
				'step'              => 1,
				'default'           => 4,
			]
		);

        $this->add_control(
            'other_posts_columns',
            [
                'label'             => __( 'Columns', 'power-pack' ),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                   '1'     => __( '1', 'power-pack' ),
                   '2'     => __( '2', 'power-pack' ),
                   '3'     => __( '3', 'power-pack' ),
                   '4'     => __( '4', 'power-pack' ),
                ],
                'default'           => '2',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Query
         */
        $this->start_controls_section(
            'section_post_query',
            [
                'label'             => __( 'Query', 'power-pack' ),
            ]
        );

		$this->add_control(
            'post_type',
            [
                'label'             => __( 'Post Type', 'power-pack' ),
                'type'              => Controls_Manager::SELECT,
                'options'           => pp_get_post_types(),
                'default'           => 'post',

            ]
        );

        $this->add_control(
            'categories',
            [
                'label'             => __( 'Categories', 'power-pack' ),
                'type'              => Controls_Manager::SELECT2,
				'label_block'       => true,
				'multiple'          => true,
				'options'           => pp_get_post_categories(),
                'condition'         => [
                    'post_type' => 'post'
                ]
            ]
        );

        $this->add_control(
            'authors',
            [
                'label'             => __( 'Authors', 'power-pack' ),
                'type'              => Controls_Manager::SELECT2,
				'label_block'       => true,
				'multiple'          => true,
				'options'           => pp_get_auhtors(),
            ]
        );

        $this->add_control(
            'tags',
            [
                'label'             => __( 'Tags', 'power-pack' ),
                'type'              => Controls_Manager::SELECT2,
				'label_block'       => true,
				'multiple'          => true,
				'options'           => pp_get_tags(),
            ]
        );

        $this->add_control(
            'exclude_posts',
            [
                'label'             => __( 'Exclude Posts', 'power-pack' ),
                'type'              => Controls_Manager::SELECT2,
				'label_block'       => true,
				'multiple'          => true,
				'options'           => pp_get_posts(),
            ]
        );

        $this->add_control(
            'order',
            [
                'label'             => __( 'Order', 'power-pack' ),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                   'DESC'           => __( 'Descending', 'power-pack' ),
                   'ASC'       => __( 'Ascending', 'power-pack' ),
                ],
                'default'           => 'DESC',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'             => __( 'Order By', 'power-pack' ),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                   'date'           => __( 'Date', 'power-pack' ),
                   'modified'       => __( 'Last Modified Date', 'power-pack' ),
                   'rand'           => __( 'Rand', 'power-pack' ),
                   'comment_count'  => __( 'Comment Count', 'power-pack' ),
                   'title'          => __( 'Title', 'power-pack' ),
                   'ID'             => __( 'Post ID', 'power-pack' ),
                   'author'         => __( 'Post Author', 'power-pack' ),
                ],
                'default'           => 'date',
            ]
        );

        $this->add_control(
            'offset',
            [
                'label'             => __( 'Offset', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => '',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Post Meta
         */
        $this->start_controls_section(
            'section_post_meta',
            [
                'label'             => __( 'Post Meta', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'post_meta',
            [
                'label'             => __( 'Post Meta', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );

        $this->add_control(
            'post_meta_divider',
            [
                'label'             => __( 'Post Meta Divider', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => '-',
				'selectors'         => [
					'{{WRAPPER}} .pp-tiled-posts-meta > span:not(:last-child):after' => 'content: "{{UNIT}}";',
				],
                'condition'         => [
                    'post_meta'     => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'post_author',
            [
                'label'             => __( 'Post Author', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
                'condition'         => [
                    'post_meta'     => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'post_category',
            [
                'label'             => __( 'Post Category', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
                'condition'         => [
                    'post_meta'     => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'post_date',
            [
                'label'             => __( 'Post Date', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'yes',
                'label_on'          => __( 'Yes', 'power-pack' ),
                'label_off'         => __( 'No', 'power-pack' ),
                'return_value'      => 'yes',
                'condition'         => [
                    'post_meta'     => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
        
        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Layout
         */
        $this->start_controls_section(
            'section_layout_style',
            [
                'label'             => __( 'Layout', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'fallback_img_bg_color',
            [
                'label'             => __( 'Tiles Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tiled-post-bg' => 'background-color: {{VALUE}}',
                ],
                'condition'         => [
                    'fallback_image'    => ''
                ]
            ]
        );
        
        $this->add_control(
			'height',
			[
				'label'             => __( 'Height', 'power-pack' ),
				'type'              => Controls_Manager::SLIDER,
				'size_units'        => [ 'px' ],
				'range'             => [
					'px' => [
						'min' => 200,
						'max' => 1000,
						'step' => 1,
					],
				],
				'default'           => [
					'unit' => 'px',
					'size' => 535,
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-tiled-post' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-tiled-post-medium, {{WRAPPER}} .pp-tiled-post-small, {{WRAPPER}} .pp-tiled-post-large' => 'height: calc( ({{SIZE}}{{UNIT}} - 5px)/2 );',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Content
         */
        $this->start_controls_section(
            'section_post_content_style',
            [
                'label'             => __( 'Content', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'post_content_bg',
                'label'             => __( 'Post Content Background', 'power-pack' ),
                'types'             => [ 'classic', 'gradient' ],
                'exclude'           => [ 'image' ],
                'selector'          => '{{WRAPPER}} .pp-tiled-post-content',
            ]
        );

		$this->add_control(
			'post_content_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', 'em', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-tiled-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Title
         */
        $this->start_controls_section(
            'section_title_style',
            [
                'label'             => __( 'Title', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
                'condition'         => [
                    'post_title'  => 'yes'
                ]
            ]
        );

        $this->add_control(
            'title_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tiled-post-title' => 'color: {{VALUE}}',
                ],
                'condition'         => [
                    'post_title'  => 'yes'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'title_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-tiled-post-title',
                'condition'         => [
                    'post_title'  => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'title_margin_bottom',
            [
                'label'             => __( 'Margin Bottom', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-tiled-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'         => [
                    'post_title'  => 'yes'
                ]
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Post Category
         */
        $this->start_controls_section(
            'section_cat_style',
            [
                'label'             => __( 'Post Category', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
                'condition'         => [
                    'post_category'  => 'yes'
                ]
            ]
        );

        $this->add_control(
            'category_style',
            [
                'label'             => __( 'Category Style', 'power-pack' ),
                'type'              => Controls_Manager::SELECT,
                'options'           => [
                   'style-1'       => __( 'Style 1', 'power-pack' ),
                   'style-2'       => __( 'Style 2', 'power-pack' ),
                ],
                'default'           => 'style-1',
                'condition'         => [
                    'post_category'  => 'yes'
                ]
            ]
        );

        $this->add_control(
            'cat_bg_color',
            [
                'label'             => __( 'Background Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'scheme'            => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_1,
                ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-post-categories-style-2 span' => 'background: {{VALUE}}',
                ],
                'condition'         => [
                    'post_category'     => 'yes',
                    'category_style'    => 'style-2'
                ]
            ]
        );

        $this->add_control(
            'cat_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#fff',
                'selectors'         => [
                    '{{WRAPPER}} .pp-post-categories' => 'color: {{VALUE}}',
                ],
                'condition'         => [
                    'post_category'  => 'yes'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'cat_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-post-categories',
                'condition'         => [
                    'post_category'  => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'cat_margin_bottom',
            [
                'label'             => __( 'Margin Bottom', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-post-categories' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'         => [
                    'post_category'  => 'yes'
                ]
            ]
        );

		$this->add_control(
			'cat_padding',
			[
				'label'             => __( 'Padding', 'power-pack' ),
				'type'              => Controls_Manager::DIMENSIONS,
				'size_units'        => [ 'px', 'em', '%' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-post-categories-style-2 span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'         => [
                    'post_category'     => 'yes',
                    'category_style'    => 'style-2'
                ]
			]
		);
        
        $this->end_controls_section();

        /**
         * Style Tab: Post Meta
         */
        $this->start_controls_section(
            'section_meta_style',
            [
                'label'             => __( 'Post Meta', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
                'condition'         => [
                    'post_meta' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'meta_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#fff',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tiled-posts-meta' => 'color: {{VALUE}}',
                ],
                'condition'         => [
                    'post_meta' => 'yes'
                ]
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'meta_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-tiled-posts-meta',
                'condition'         => [
                    'post_meta' => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'meta_items_spacing',
            [
                'label'             => __( 'Items Spacing', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-tiled-posts-meta > span:not(:last-child):after' => 'margin-left: calc({{SIZE}}{{UNIT}}/2); margin-right: calc({{SIZE}}{{UNIT}}/2);',
                ],
                'condition'         => [
                    'post_meta' => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'meta_margin_bottom',
            [
                'label'             => __( 'Margin Bottom', 'power-pack' ),
                'type'              => Controls_Manager::SLIDER,
                'range'             => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'        => [ 'px' ],
                'selectors'         => [
                    '{{WRAPPER}} .pp-tiled-posts-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'         => [
                    'post_meta' => 'yes'
                ]
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Post Excerpt
         */
        $this->start_controls_section(
            'section_excerpt_style',
            [
                'label'             => __( 'Post Excerpt', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'excerpt_text_color',
            [
                'label'             => __( 'Text Color', 'power-pack' ),
                'type'              => Controls_Manager::COLOR,
                'default'           => '#fff',
                'selectors'         => [
                    '{{WRAPPER}} .pp-tiled-post-excerpt' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'              => 'excerpt_typography',
                'label'             => __( 'Typography', 'power-pack' ),
                'scheme'            => Scheme_Typography::TYPOGRAPHY_4,
                'selector'          => '{{WRAPPER}} .pp-tiled-post-excerpt',
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Overlay
         */
        $this->start_controls_section(
            'section_overlay_style',
            [
                'label'             => __( 'Overlay', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_overlay_style' );

        $this->start_controls_tab(
            'tab_overlay_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'post_overlay_bg',
                'label'                 => __( 'Overlay Background', 'power-pack' ),
                'types'                 => [ 'classic', 'gradient' ],
                'exclude'               => [ 'image' ],
                'selector'              => '{{WRAPPER}} .pp-tiled-post-overlay',
            ]
        );
        
        $this->add_control(
            'post_overlay_opacity',
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
					'{{WRAPPER}} .pp-tiled-post-overlay' => 'opacity: {{SIZE}};',
				],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_overlay_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'                  => 'post_overlay_bg_hover',
                'label'                 => __( 'Overlay Background', 'power-pack' ),
                'types'                 => [ 'classic', 'gradient' ],
                'exclude'               => [ 'image' ],
                'selector'              => '{{WRAPPER}} .pp-tiled-post:hover .pp-tiled-post-overlay',
            ]
        );
        
        $this->add_control(
            'post_overlay_opacity_hover',
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
					'{{WRAPPER}} .pp-tiled-post:hover .pp-tiled-post-overlay' => 'opacity: {{SIZE}};',
				],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

    }

    /**
	 * Render tiled posts widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();
        
        $this->add_render_attribute( 'tiled-posts', 'class', 'pp-tiled-posts clearfix' );
        
        if ( $settings['layout'] ) {
            $this->add_render_attribute( 'tiled-posts', 'class', 'pp-tiled-posts-' . $settings['layout'] );
        }
        
        $this->add_render_attribute( 'post-content', 'class', 'pp-tiled-post-content' );
        
        if ( $settings['content_vertical_position'] ) {
            $this->add_render_attribute( 'post-content', 'class', 'pp-tiled-post-content-' . $settings['content_vertical_position'] );
        }
        
        $this->add_render_attribute( 'post-categories', 'class', 'pp-post-categories' );
        
        if ( $settings['category_style'] ) {
            $this->add_render_attribute( 'post-categories', 'class', 'pp-post-categories-' . $settings['category_style'] );
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'tiled-posts' ); ?>>
            <?php
                $count = 1;
        
                $layout = $settings['layout'];
        
                if ( $layout == 'layout-1' ) {
                    $pp_posts_count = 4;
                }
                elseif ( $layout == 'layout-2' || $layout == 'layout-3' ) {
                    $pp_posts_count = 3;
                }
                elseif ( $layout == 'layout-4' || $layout == 'layout-5' ) {
                    $pp_posts_count = 5;
                }
                else {
                    $pp_posts_count = 3;
                }
        
                if ( $settings['other_posts'] == 'yes' ) {
                    if ( ! empty( $settings['other_posts_count'] ) && is_numeric( $settings['other_posts_count'] ) ) {
                        $number_of_posts = absint( $settings['other_posts_count'] );
                        $pp_posts_count += $number_of_posts;
                    } else {
                        $pp_posts_count = '-1';
                    }
                }

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
                $featured_posts = new \WP_Query( $args );


                if ( $featured_posts->have_posts() ) : while ($featured_posts->have_posts()) : $featured_posts->the_post();
                    if ( $count == 1 && $layout != 'layout-5' ) {
                        ?><div class="pp-tiles-posts-left"><?php
                    }
        
                    if ( $layout == 'layout-1' || $layout == 'layout-2' || $layout == 'layout-3' || $layout == 'layout-4' ) {
                        if ( $count == 2 ) { ?><div class="pp-tiles-posts-right"><?php }
                    }
        
                    if ( $settings['other_posts'] == 'yes' && ( ( $count == 5 && $layout == 'layout-1' ) || ( $count == 4 && ( $layout == 'layout-2' || $layout == 'layout-3' ) ) || ( $count == 6 && ( $layout == 'layout-4' || $layout == 'layout-5' ) ) ) ) {
                        echo '<div class="pp-tiled-post-group pp-tiled-post-col-' . $settings['other_posts_columns'] . '">';
                    }

                    $this->render_post_body( $count, $layout );
        
                    if ( $count == 1 && $layout != 'layout-5' ) {
                        ?></div><?php
                    }
        
                    if ( $settings['other_posts'] == 'yes' && $count == $pp_posts_count ) {
                        echo '</div>';
                    }
                        
                    if ( $layout == 'layout-1' ) {
                        if ( $count == 4 ) { ?></div><?php }
                    }
                    elseif ( $layout == 'layout-2' || $layout == 'layout-3' ) {
                        if ( $count == 3 ) { ?></div><?php }
                    }
                    if ( $layout == 'layout-4' ) {
                        if ( $count == 5 ) { ?></div><?php }
                    }
                $count++; endwhile; endif; wp_reset_postdata();
        ?>
        </div><!--.pp-tiled-posts-->
        <?php
    }

    /**
	 * Render posts body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_post_body( $count, $layout ) {
        $settings = $this->get_settings();
        
        $this->add_render_attribute( 'post-' . $count, 'class', [
            'pp-tiled-post',
            'pp-tiled-post-' . intval( $count ),
            $this->pp_get_post_class( $count, $layout )
        ] );
        
        if ( has_post_thumbnail() ) {
            $image_id = get_post_thumbnail_id( get_the_ID() );
            if ( $count == 1 && $layout != 'layout-5' ) {
                $pp_thumb_url = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image_size', $settings );
            } else {
                $pp_thumb_url = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image_size_small', $settings );
            }
        } else {
            if ( $settings['fallback_image'] == 'placeholder' ) {
                $pp_thumb_url = Utils::get_placeholder_image_src();
            } elseif ( $settings['fallback_image'] == 'custom' && !empty( $settings['fallback_image_custom']['url'] ) ) {
                $custom_image_id = $settings['fallback_image_custom']['id'];
                if ( $count == 1 && $layout != 'layout-5' ) {
                    $pp_thumb_url = Group_Control_Image_Size::get_attachment_image_src( $custom_image_id, 'image_size', $settings );
                } else {
                    $pp_thumb_url = Group_Control_Image_Size::get_attachment_image_src( $custom_image_id, 'image_size_small', $settings );
                }
            } else {
                $pp_thumb_url = '';
            }
        }
        ?>
        <div <?php echo $this->get_render_attribute_string( 'post-' . $count ); ?>>
            <div class="pp-tiled-post-bg" <?php if ( $pp_thumb_url ) { echo "style='background-image:url(".esc_url( $pp_thumb_url ).")'"; } ?>>
                <a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"></a>
            </div>
            <div class="pp-tiled-post-overlay"></div>
            <div <?php echo $this->get_render_attribute_string( 'post-content' ); ?>>
                <?php if ( $settings['post_meta'] == 'yes' ) { ?>
                    <?php if ( $settings['post_category'] == 'yes' ) { ?>
                        <div <?php echo $this->get_render_attribute_string( 'post-categories' ); ?>>
                            <span>
                                <?php
                                    $category = get_the_category();
                                    if ( $category ) {
                                        echo esc_attr( $category[0]->name );
                                    }
                                ?>
                            </span>
                        </div><!--.pp-post-categories-->
                    <?php } ?>
                <?php } ?>
                <?php if ( $settings['post_title'] == 'yes' ) { ?>
                    <header>
                        <h2 class="pp-tiled-post-title">
                            <?php echo $this->get_post_title_length( get_the_title() ); ?>
                        </h2>
                    </header>
                <?php } ?>
                <?php if ( $settings['post_meta'] == 'yes' ) { ?>
                    <div class="pp-tiled-posts-meta">
                        <?php if ( $settings['post_author'] == 'yes' ) { ?>
                            <span class="pp-post-author">
                                <?php echo get_the_author(); ?>
                            </span>
                        <?php } ?>
                        <?php if ( $settings['post_date'] == 'yes' ) { ?>
                                <?php
                                    $pp_time_string = sprintf( '<time class="entry-date" datetime="%1$s">%2$s</time>',
                                        esc_attr( get_the_date( 'c' ) ),
                                        get_the_date()
                                    );

                                    printf( '<span class="pp-post-date"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                                        __( 'Posted on', 'power-pack' ),
                                        $pp_time_string
                                    );
                                ?>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php $this->render_post_excerpt( $count, $layout ) ?>
            </div><!--.post-inner-->
        </div>
        <?php
    }

    /**
	 * Render posts body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_post_excerpt( $count, $layout ) {
        $settings = $this->get_settings();
        
        if ( $count == 1 && $layout != 'layout-5' ) {
            $pp_post_excerpt = $settings['post_excerpt'];
            $limit = $settings['excerpt_length'];
        } else {
            $pp_post_excerpt = $settings['post_excerpt_small'];
            $limit = $settings['excerpt_length_small'];
        }
        
        if ( $pp_post_excerpt == 'yes' ) { ?>
        <div class="pp-tiled-post-excerpt">
            <?php echo $this->get_custom_post_excerpt( $limit ); ?>
        </div>
        <?php }
    }

    /**
	 * Get post class.
	 *
	 * @access protected
	 */
    protected function pp_get_post_class( $count, $layout ) {
        $settings = $this->get_settings();

        $class = '';
        
        if ( ( $count == 2 && $layout == 'layout-1' ) || ( ( $count == 2 || $count == 3 ) && ( $layout == 'layout-2' || $layout == 'layout-3' ) ) ) {
			$class = 'pp-tiled-post-large';
		}
        if ( ( ( $count == 3 || $count == 4 ) && $layout == 'layout-1' ) || ( ( $count == 1 || $count == 2 ) && $layout == 'layout-5' ) ) {
			$class = 'pp-tiled-post-medium';
		}
        if ( $count > 1 && $count < 6 && $layout == 'layout-4' ) {
			$class = 'pp-tiled-post-medium';
		}
        if ( ( $count == 3 || $count == 4 || $count == 5 ) && $layout == 'layout-5' ) {
			$class = 'pp-tiled-post-small';
        }
        
        if ( $this->pp_check_other_posts( $count, $layout ) ) {
            if ( $settings['other_posts_columns'] == '4' ) {
                $class = 'pp-tiled-post-xs';
            }
            elseif ( $settings['other_posts_columns'] == '3' ) {
                $class = 'pp-tiled-post-small';
            }
            elseif ( $settings['other_posts_columns'] == '2' ) {
                $class = 'pp-tiled-post-medium';
            }
            elseif ( $settings['other_posts_columns'] == '1' ) {
                $class = 'pp-tiled-post-large';
            }
        }
        
        return $class;
    }

    /**
	 * Check other posts.
	 *
	 * @access protected
	 */
    protected function pp_check_other_posts( $count, $layout ) {
        $settings = $this->get_settings();

        if ( $settings['other_posts'] == 'yes' && ( ( $count >= 5 && $layout == 'layout-1' ) || ( $count >= 4 && ( $layout == 'layout-2' || $layout == 'layout-3' ) ) || ( $count >= 6 && ( $layout == 'layout-4' || $layout == 'layout-5' ) ) ) ) {
            return true;
        }
    }

    /**
	 * Get post title length.
	 *
	 * @access protected
	 */
    protected function get_post_title_length( $title ) {
        $settings = $this->get_settings();
        
        $length = absint( $settings['post_title_length'] );

        if ( $length != '' ) {
            if ( strlen( $title ) > $length ) {
                $title = substr( $title, 0, $length ). "&hellip;";
            }
        }

        return $title;
    }

    /**
	 * Get custom post excerpt.
	 *
	 * @access protected
	 */
    protected function get_custom_post_excerpt( $limit ) {
        $pp_excerpt = explode(' ', get_the_excerpt(), $limit);
    
        if ( count( $pp_excerpt ) >= $limit ) {
            array_pop($pp_excerpt);
            $pp_excerpt = implode(" ",$pp_excerpt).'...';
        } else {
            $pp_excerpt = implode(" ",$pp_excerpt);
        }

        $pp_excerpt = preg_replace('`[[^]]*]`','',$pp_excerpt);

        return $pp_excerpt;
    }

    /**
	 * Render tiled posts widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {}
}