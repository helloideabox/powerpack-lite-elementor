<?php
namespace PowerpackElements\Modules\Showcase\Widgets;

use PowerpackElements\Base\Powerpack_Widget;
use PowerpackElements\Modules\Showcase\Module;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Scheme_Typography;
use Elementor\Embed;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Showcase Widget
 */
class Showcase extends Powerpack_Widget {
    
    /**
	 * Retrieve showcase widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-showcase';
    }

    /**
	 * Retrieve showcase widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Showcase', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the showcase widget belongs to.
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
	 * Retrieve showcase widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-showcase power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the showcase widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'jquery-resize',
			'jquery-slick',
            'powerpack-frontend'
        ];
    }

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.3.6
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'image', 'video', 'embed', 'youtube', 'vimeo', 'dailymotion', 'slider' ];
	}

    /**
	 * Register showcase widget controls.
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
         * Content Tab: Items
         */
        $this->start_controls_section(
            'section_gallery',
            [
                'label'                 => __( 'Items', 'power-pack' ),
            ]
        );
        
        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label'             => __( 'Title', 'power-pack' ),
                'type'              => Controls_Manager::TEXT,
                'default'           => '',
                'dynamic'           => [
                    'active'   => true,
                ],
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label'             => __( 'Description', 'power-pack' ),
                'type'              => Controls_Manager::TEXTAREA,
                'default'           => '',
                'dynamic'           => [
                    'active'   => true,
                ],
            ]
        );
        
        $repeater->add_control(
			'nav_icon_type',
            [
                'label'             => esc_html__( 'Icon Type', 'power-pack' ),
                'type'              => Controls_Manager::CHOOSE,
                'label_block'       => false,
                'options'           => [
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
                ],
                'default'           => 'none',
            ]
        );
        
        $repeater->add_control(
			'nav_icon',
            [
                'label'             => esc_html__( 'Icon', 'power-pack' ),
                'type'              => Controls_Manager::ICON,
                'label_block'       => false,
                'default'           => 'fa fa-picture-o',
                'condition'         => [
                    'nav_icon_type' => 'icon',
                ],
            ]
        );
        
        $repeater->add_control(
			'nav_icon_image',
            [
                'label'             => esc_html__( 'Icon Image', 'power-pack' ),
                'type'              => Controls_Manager::MEDIA,
				'label_block'       => false,
                'default'           => [
                    'url' => Utils::get_placeholder_image_src(),
                 ],
                'condition'         => [
                    'nav_icon_type' => 'image',
                ],
            ]
        );
        
        $repeater->add_control(
			'content_type',
			[
				'label'                 => esc_html__( 'Content Type', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'label_block'           => false,
                'options'               => [
                    'image'     => __( 'Image', 'power-pack' ),
                    'video' 	=> __( 'Video', 'power-pack' ),
                    'section'   => __( 'Saved Section', 'power-pack' ),
                    'widget'    => __( 'Saved Widget', 'power-pack' ),
                    'template'  => __( 'Saved Page Template', 'power-pack' ),
                ],
				'default'               => 'image',
				'separator'             => 'before',
			]
		);
        
        $repeater->add_control(
            'image',
            [
                'label'                 => __( 'Image', 'power-pack' ),
                'type'                  => Controls_Manager::MEDIA,
                'dynamic'               => [
                    'active'   => true,
                ],
                'default'               => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'conditions'            => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'image',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'link_to',
            [
                'label'                 => __( 'Link to', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'none',
                'options'               => [
                    'none' 		=> __( 'None', 'power-pack' ),
                    'file' 		=> __( 'Media File', 'power-pack' ),
                    'custom' 	=> __( 'Custom URL', 'power-pack' ),
                ],
                'conditions'            => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'image',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label'                 => __( 'Link', 'power-pack' ),
                'show_label'            => false,
                'type'                  => Controls_Manager::URL,
                'placeholder'           => __( 'http://your-link.com', 'power-pack' ),
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'image',
                        ],
                        [
                            'name'      => 'link_to',
                            'operator'  => '==',
                            'value'     => 'custom',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'open_lightbox',
            [
                'label'                 => __( 'Lightbox', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'default',
                'options'               => [
                    'default' 	=> __( 'Default', 'power-pack' ),
                    'yes' 		=> __( 'Yes', 'power-pack' ),
                    'no' 		=> __( 'No', 'power-pack' ),
                ],
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'image',
                        ],
                        [
                            'name'      => 'link_to',
                            'operator'  => '==',
                            'value'     => 'file',
                        ],
                    ],
                ],
            ]
        );

		$repeater->add_control(
			'video_source',
			[
				'label'                 => __( 'Video Source', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'youtube',
				'options'               => [
					'youtube'      => __( 'YouTube', 'power-pack' ),
					'vimeo'        => __( 'Vimeo', 'power-pack' ),
					'dailymotion'  => __( 'Dailymotion', 'power-pack' ),
				],
                'conditions'            => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'video',
                        ],
                    ],
                ],
			]
		);

        $repeater->add_control(
            'video_url',
            [
                'label'                 => __( 'Video URL', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'label_block'           => true,
                'default'               => 'https://www.youtube.com/watch?v=9uOETcuFjbE',
                'dynamic'               => [
                    'active'   => true,
                ],
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'video',
                        ],
                    ],
                ],
            ]
        );

		$repeater->add_control(
			'thumbnail_size',
			[
				'label'                 => __( 'Thumbnail Size', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'maxresdefault',
				'options'               => [
					'maxresdefault' => __( 'Maximum Resolution', 'power-pack' ),
                    'hqdefault'     => __( 'High Quality', 'power-pack' ),
                    'mqdefault'     => __( 'Medium Quality', 'power-pack' ),
                    'sddefault'     => __( 'Standard Quality', 'power-pack' ),
				],
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'video',
                        ],
                        [
                            'name'      => 'video_source',
                            'operator'  => '==',
                            'value'     => 'youtube',
                        ],
                    ],
                ],
			]
		);

        $repeater->add_control(
            'saved_widget',
            [
                'label'                 => __( 'Choose Widget', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => $this->get_page_template_options( 'widget' ),
				'default'               => '-1',
				'condition'             => [
					'content_type'    => 'widget',
				],
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'widget',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'saved_section',
            [
                'label'                 => __( 'Choose Section', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => $this->get_page_template_options( 'section' ),
				'default'               => '-1',
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'section',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'templates',
            [
                'label'                 => __( 'Choose Template', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => $this->get_page_template_options( 'page' ),
				'default'               => '-1',
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'template',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'items',
            [
                'label' 	=> '',
                'type' 		=> Controls_Manager::REPEATER,
                'default' 	=> [
                    [
                        'content_type'   => 'image',
                        'image'         => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'title'         => __( 'Item 1', 'power-pack' ),
                        'description'   => __( 'I am the description for item 1', 'power-pack' ),
                    ],
                    [
                        'content_type'   => 'image',
                        'image'         => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'title'         => __( 'Item 2', 'power-pack' ),
                        'description'   => __( 'I am the description for item 2', 'power-pack' ),
                    ],
                    [
                        'content_type'   => 'image',
                        'image'         => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'title'         => __( 'Item 3', 'power-pack' ),
                        'description'   => __( 'I am the description for item 3', 'power-pack' ),
                    ],
                ],
                'fields' 		=> array_values( $repeater->get_controls() ),
                'title_field' 	=> '{{ title }}'
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Preview
         */
        $this->start_controls_section(
            'section_preview',
            [
                'label'                 => __( 'Preview', 'power-pack' ),
            ]
        );

		$this->add_control(
			'images_heading',
			[
				'label'                 => __( 'Images', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
			]
		);
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'                  => 'image',
                'label'                 => __( 'Image Size', 'power-pack' ),
                'default'               => 'full',
                'exclude'               => [ 'custom' ],
				'separator'             => 'before',
            ]
        );

		$this->add_control(
			'preview_caption',
			[
				'type'                  => Controls_Manager::SELECT,
				'label'                 => __( 'Caption', 'power-pack' ),
				'default'               => '',
				'options'               => [
					''         => __( 'None', 'power-pack' ),
					'caption'  => __( 'Caption', 'power-pack' ),
					'title'    => __( 'Title', 'power-pack' ),
				],
			]
		);

		$this->add_control(
			'videos_heading',
			[
				'label'                 => __( 'Videos', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'aspect_ratio',
			[
				'label'                 => __( 'Aspect Ratio', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'169'  => '16:9',
					'219'  => '21:9',
					'43'   => '4:3',
					'32'   => '3:2',
				],
				'default'               => '169',
				'prefix_class'          => 'elementor-aspect-ratio-',
				'frontend_available'    => true,
			]
		);
        
        $this->add_control(
            'mute',
            [
                'label'                 => __( 'Mute', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
				'frontend_available'    => true,
            ]
        );

		$this->add_control(
			'play_icon_heading',
			[
				'label'                 => __( 'Play Icon', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'play_icon_type',
            [
                'label'                 => __( 'Icon Type', 'power-pack' ),
				'label_block'           => false,
				'toggle'                => false,
                'type'                  => Controls_Manager::CHOOSE,
                'options'               => [
					'none'        => [
						'title'   => esc_html__( 'None', 'power-pack' ),
						'icon'    => 'fa fa-ban',
					],
                    'icon'  => [
                        'title' => __( 'Icon', 'power-pack' ),
                        'icon'  => 'fa fa-info-circle',
                    ],
                    'image' => [
                        'title' => __( 'Image', 'power-pack' ),
                        'icon'  => 'fa fa-picture-o',
                    ],
                ],
                'default'               => 'icon',
            ]
        );

        $this->add_control(
            'play_icon',
            [
                'label'                 => __( 'Select Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => 'fa fa-play-circle',
                'condition'             => [
                    'play_icon_type' => 'icon',
                ],
            ]
        );

        $this->add_control(
            'play_icon_image',
            [
                'label'                 => __( 'Select Image', 'power-pack' ),
                'type'                  => Controls_Manager::MEDIA,
                'default'               => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition'             => [
                    'play_icon_type' => 'image',
                ],
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
			'navigation_heading',
			[
				'label'                 => __( 'Navigation', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
			]
		);
        
        $this->add_control(
            'scrollable_nav',
            [
                'label'                 => __( 'Scrollable Navigation', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
            ]
        );

		$slides_to_show = range( 1, 10 );
		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

		$this->add_responsive_control(
			'nav_items',
			[
				'label'                 => __( 'Items to Show', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'' => __( 'Default', 'power-pack' ),
				] + $slides_to_show,
				'frontend_available'    => true,
                'condition'             => [
					'scrollable_nav'    => 'yes',
				],
			]
		);

        $this->add_responsive_control(
            'navigation_columns',
            [
                'label'                 => __( 'Columns', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => '1',
                'tablet_default'        => '1',
                'mobile_default'        => '1',
                'options'               => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                ],
                'prefix_class'          => 'elementor-grid%s-',
                'frontend_available'    => true,
                'condition'             => [
					'scrollable_nav!'   => 'yes',
				],
            ]
        );

		$this->add_control(
			'preview_heading',
			[
				'label'                 => __( 'Preview', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'effect',
			[
				'type'                  => Controls_Manager::SELECT,
				'label'                 => __( 'Effect', 'power-pack' ),
				'default'               => 'slide',
				'options'               => [
					'slide'    => __( 'Slide', 'power-pack' ),
					'fade'     => __( 'Fade', 'power-pack' ),
				],
				'frontend_available'    => true,
			]
		);

        $this->add_control(
            'animation_speed',
            [
                'label'                 => __( 'Animation Speed', 'power-pack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 600,
                'frontend_available'    => true,
            ]
        );
        
        $this->add_control(
            'arrows',
            [
                'label'                 => __( 'Arrows', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
				'frontend_available'    => true,
            ]
        );
        
        $this->add_control(
            'dots',
            [
                'label'                 => __( 'Dots', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
				'frontend_available'    => true,
            ]
        );
        
        $this->add_control(
            'autoplay',
            [
                'label'                 => __( 'Autoplay', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => '',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
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
                    'autoplay'  => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'pause_on_hover',
            [
                'label'                 => __( 'Pause on Hover', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
                'condition'             => [
                    'autoplay'  => 'yes',
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
                'frontend_available'    => true,
            ]
        );
        
        $this->add_control(
            'adaptive_height',
            [
                'label'                 => __( 'Adaptive Height', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Preview
         */
        $this->start_controls_section(
            'section_preview_style',
            [
                'label'                 => __( 'Preview', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
			'preview_position',
			[
                'label'                 => __( 'Position', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
                'toggle'                => false,
                'default'               => 'right',
                'options'               => [
                    'left'          => [
                        'title'     => __( 'Left', 'power-pack' ),
                        'icon'      => 'eicon-h-align-left',
                    ],
                    'top'           => [
                        'title'     => __( 'Top', 'power-pack' ),
                        'icon'      => 'eicon-v-align-top',
                    ],
                    'right'         => [
                        'title'     => __( 'Right', 'power-pack' ),
                        'icon'      => 'eicon-h-align-right',
                    ],
                ],
                'prefix_class'          => 'pp-showcase-preview-align-',
                'frontend_available'    => true,
			]
		);

        $this->add_responsive_control(
            'preview_image_align',
            [
                'label'                 => __( 'Image Align', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-image' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'preview_stack',
            [
                'label'                 => __( 'Stack On', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'tablet',
                'options'               => [
                    'tablet' 	=> __( 'Tablet', 'power-pack' ),
                    'mobile' 	=> __( 'Mobile', 'power-pack' ),
                ],
                'prefix_class'          => 'pp-showcase-preview-stack-',
                'frontend_available'    => true,
                'condition'             => [
					'preview_position!' => 'top',
				],
            ]
        );

        $this->add_responsive_control(
            'preview_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'size_units'            => [ '%' ],
                'devices'               => [ 'desktop', 'tablet' ],
                'range'                 => [
                    '%' 	=> [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'               => [
                    'size' 	=> 70,
                ],
                'selectors'             => [
                    '{{WRAPPER}}.pp-showcase-preview-align-left .pp-showcase-preview-wrap' => 'width: {{SIZE}}%',
                    '{{WRAPPER}}.pp-showcase-preview-align-right .pp-showcase-preview-wrap' => 'width: {{SIZE}}%',
                    '{{WRAPPER}}.pp-showcase-preview-align-right .pp-showcase-navigation' => 'width: calc(100% - {{SIZE}}%)',
                    '{{WRAPPER}}.pp-showcase-preview-align-left .pp-showcase-navigation' => 'width: calc(100% - {{SIZE}}%)',
                ],
                'condition'             => [
					'preview_position!' => 'top',
				],
            ]
        );
        
        $this->add_responsive_control(
            'preview_spacing',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' 	=> [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'               => [
                    'size' 	=> 20,
                ],
                'selectors'             => [
                    '{{WRAPPER}}.pp-showcase-preview-align-left .pp-showcase,
                    {{WRAPPER}}.pp-showcase-preview-align-right .pp-showcase' => 'margin-left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.pp-showcase-preview-align-left .pp-showcase > *,
                    {{WRAPPER}}.pp-showcase-preview-align-right .pp-showcase > *' => 'padding-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.pp-showcase-preview-align-top .pp-showcase-preview-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.pp-showcase-preview-stack-tablet .pp-showcase-preview-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.pp-showcase-preview-stack-mobile .pp-showcase-preview-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'preview_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-showcase-preview' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'preview_background',
				'types'            	    => [ 'classic', 'gradient' ],
				'selector'              => '{{WRAPPER}} .pp-showcase-preview',
                'exclude'               => [
                    'image',
                ],
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'preview_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-showcase-preview',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'preview_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-showcase-preview',
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'                  => 'preview_css_filters',
				'selector'              => '{{WRAPPER}} .pp-showcase-preview img',
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Preview Captions
         */
        $this->start_controls_section(
            'section_preview_captions_style',
            [
                'label'                 => __( 'Preview Captions', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'preview_caption!'  => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'preview_captions_vertical_align',
            [
                'label'                 => __( 'Vertical Align', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'options'               => [
                    'top' 	=> [
                        'title' 	=> __( 'Top', 'power-pack' ),
                        'icon' 		=> 'eicon-v-align-top',
                    ],
                    'middle' 		=> [
                        'title' 	=> __( 'Middle', 'power-pack' ),
                        'icon' 		=> 'eicon-v-align-middle',
                    ],
                    'bottom' 		=> [
                        'title' 	=> __( 'Bottom', 'power-pack' ),
                        'icon' 		=> 'eicon-v-align-bottom',
                    ],
                ],
                'default'               => 'bottom',
				'selectors' => [
					'{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-content' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'top'      => 'flex-start',
					'bottom'   => 'flex-end',
					'middle'   => 'center',
				],
                'condition'             => [
                    'preview_caption!'  => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'preview_captions_horizontal_align',
            [
                'label'                 => __( 'Horizontal Align', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'options'               => [
                    'left' 	=> [
                        'title' 	=> __( 'Left', 'power-pack' ),
                        'icon' 		=> 'eicon-h-align-left',
                    ],
                    'center' 		=> [
                        'title' 	=> __( 'Center', 'power-pack' ),
                        'icon' 		=> 'eicon-h-align-center',
                    ],
                    'right' 		=> [
                        'title' 	=> __( 'Right', 'power-pack' ),
                        'icon' 		=> 'eicon-h-align-right',
                    ],
                    'justify' 		=> [
                        'title' 	=> __( 'Justify', 'power-pack' ),
                        'icon' 		=> 'eicon-h-align-stretch',
                    ],
                ],
                'default'               => 'left',
				'selectors' => [
					'{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-content' => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'left'     => 'flex-start',
					'right'    => 'flex-end',
					'center'   => 'center',
					'justify'  => 'stretch',
				],
                'condition'             => [
                    'preview_caption!'  => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'preview_captions_align',
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
                'default'               => 'center',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-caption' => 'text-align: {{VALUE}};',
                ],
                'condition'             => [
                    'preview_captions_horizontal_align'   => 'justify',
                    'preview_caption!'                    => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'preview_captions_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-caption',
                'condition'             => [
                    'preview_caption!'  => '',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_preview_captions_style' );

        $this->start_controls_tab(
            'tab_preview_captions_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
                    'preview_caption!'  => '',
                ],
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'preview_captions_background',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-caption',
                'exclude'               => [
                    'image',
                ],
                'condition'             => [
                    'preview_caption!'  => '',
                ],
			]
		);

        $this->add_control(
            'preview_captions_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-caption' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'preview_caption!'  => '',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'preview_captions_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-caption',
                'condition'             => [
                    'preview_caption!'  => '',
                ],
			]
		);

		$this->add_control(
			'preview_captions_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'preview_caption!'  => '',
                ],
			]
		);

		$this->add_responsive_control(
			'preview_captions_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'preview_caption!'  => '',
                ],
			]
		);

		$this->add_responsive_control(
			'preview_captions_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'preview_caption!'  => '',
                ],
			]
		);

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'                  => 'preview_text_shadow',
                'selector' 	            => '{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-caption',
                'condition'             => [
                    'preview_caption!'  => '',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_preview_captions_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
                'condition'             => [
                    'preview_caption!'  => '',
                ],
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'preview_captions_background_hover',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-showcase-preview:hover .pp-showcase-preview-caption',
                'exclude'               => [
                    'image',
                ],
                'condition'             => [
                    'preview_caption!'  => '',
                ],
			]
		);

        $this->add_control(
            'preview_captions_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-preview:hover .pp-showcase-preview-caption' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'preview_caption!'  => '',
                ],
            ]
        );

        $this->add_control(
            'preview_captions_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-preview:hover .pp-showcase-preview-caption' => 'border-color: {{VALUE}}',
                ],
                'condition'             => [
                    'preview_caption!'  => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'                  => 'preview_text_shadow_hover',
                'selector' 	            => '{{WRAPPER}} .pp-showcase-preview:hover .pp-showcase-preview-caption',
                'condition'             => [
                    'preview_caption!'  => '',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
			'preview_captions_blend_mode',
			[
				'label'                 => __( 'Blend Mode', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					''             => __( 'Normal', 'power-pack' ),
					'multiply'     => 'Multiply',
					'screen'       => 'Screen',
					'overlay'      => 'Overlay',
					'darken'       => 'Darken',
					'lighten'      => 'Lighten',
					'color-dodge'  => 'Color Dodge',
					'saturation'   => 'Saturation',
					'color'        => 'Color',
					'difference'   => 'Difference',
					'exclusion'    => 'Exclusion',
					'hue'          => 'Hue',
					'luminosity'   => 'Luminosity',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-showcase-preview .pp-showcase-preview-caption' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator'             => 'before',
                'condition'             => [
                    'preview_caption!'  => '',
                ],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Navigation
         */
        $this->start_controls_section(
            'section_navigation_style',
            [
                'label'                 => __( 'Navigation', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'navigation_items_horizontal_spacing',
            [
                'label'                 => __( 'Column Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' 	=> [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'               => [
                    'size' 	=> '',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-items .pp-showcase-navigation-item-wrap' => 'padding-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pp-showcase-navigation-items'  => 'margin-left: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_items_vertical_spacing',
            [
                'label'                 => __( 'Row Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' 	=> [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'               => [
                    'size'  => 15,
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-item-wrap:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'navigation_text_align',
            [
                'label'                 => __( 'Text Align', 'power-pack' ),
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
				'default'               => 'left',
				'selectors'             => [
					'{{WRAPPER}} .pp-showcase-navigation-items .pp-showcase-navigation-item' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->add_control(
            'navigation_background',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-items' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_responsive_control(
			'navigation_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-showcase-navigation-items' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_title_style' );

        $this->start_controls_tab(
            'tab_title_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

		$this->add_control(
			'title_heading',
			[
				'label'                 => __( 'Title', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'title_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-showcase-navigation-title',
            ]
        );
        
        $this->add_responsive_control(
            'title_margin',
            [
                'label'                 => __( 'Margin Bottom', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 5,
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
                    '{{WRAPPER}} .pp-showcase-navigation-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

		$this->add_control(
			'description_heading',
			[
				'label'                 => __( 'Description', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'description_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-description' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'description_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-showcase-navigation-description',
            ]
        );

		$this->add_control(
			'navigation_icon_heading',
			[
				'label'                 => __( 'Navigation Icon/Image', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'navigation_icon_color',
            [
                'label'                 => __( 'Icon Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_icon_size',
            [
                'label'                 => __( 'Icon Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min' => 10,
                        'max' => 400,
                    ],
                ],
                'default'               => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'navigation_icon_img_size',
            [
                'label'                 => __( 'Icon Image Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min' => 10,
                        'max' => 400,
                    ],
                ],
                'default'               => [
                    'size' => 80,
                    'unit' => 'px',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-icon img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'navigation_icon_margin',
            [
                'label'                 => __( 'Margin Bottom', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 5,
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
                    '{{WRAPPER}} .pp-showcase-navigation-icon-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

		$this->add_control(
			'navigation_item_heading',
			[
				'label'                 => __( 'Navigation Item', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'navigation_item_background_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'navigation_item_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-showcase-navigation-item',
			]
		);

		$this->add_control(
			'navigation_item_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-showcase-navigation-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'navigation_item_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-showcase-navigation-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'navigation_item_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-showcase-navigation-item',
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_title_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

		$this->add_control(
			'title_heading_hover',
			[
				'label'                 => __( 'Title', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'title_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-item:hover .pp-showcase-navigation-title' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
			'description_heading_hover',
			[
				'label'                 => __( 'Description', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'description_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-item:hover .pp-showcase-navigation-description' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
			'navigation_icon_hover_heading',
			[
				'label'                 => __( 'Navigation Icon', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'navigation_icon_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-item:hover .pp-showcase-navigation-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
			'navigation_item_hover_heading',
			[
				'label'                 => __( 'Navigation Item', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'navigation_item_background_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-item:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'navigation_item_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-showcase-navigation-item:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'navigation_item_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-showcase-navigation-item:hover',
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_color_active',
            [
                'label'                 => __( 'Active', 'power-pack' ),
            ]
        );

		$this->add_control(
			'title_heading_active',
			[
				'label'                 => __( 'Title', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'title_color_active',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-active-slide .pp-showcase-navigation-item .pp-showcase-navigation-title, {{WRAPPER}} .slick-current .pp-showcase-navigation-item .pp-showcase-navigation-title' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
			'description_heading_active',
			[
				'label'                 => __( 'Description', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'description_color_active',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-active-slide .pp-showcase-navigation-item .pp-showcase-navigation-description, {{WRAPPER}} .slick-current .pp-showcase-navigation-item .pp-showcase-navigation-description' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
			'navigation_icon_active_heading',
			[
				'label'                 => __( 'Navigation Icon', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'navigation_icon_active_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-active-slide .pp-showcase-navigation-item .pp-showcase-navigation-icon, {{WRAPPER}} .slick-current .pp-showcase-navigation-item .pp-showcase-navigation-icon' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_control(
			'navigation_item_active_heading',
			[
				'label'                 => __( 'Navigation Item', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

        $this->add_control(
            'navigation_item_background_color_active',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-active-slide .pp-showcase-navigation-item, {{WRAPPER}} .slick-current .pp-showcase-navigation-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'navigation_item_border_color_active',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-active-slide .pp-showcase-navigation-item, {{WRAPPER}} .slick-current .pp-showcase-navigation-item' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'navigation_item_box_shadow_active',
				'selector'              => '{{WRAPPER}} .pp-active-slide .pp-showcase-navigation-item, {{WRAPPER}} .slick-current .pp-showcase-navigation-item',
			]
		);
        
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        
        /**
         * Style Tab: Play Icon
         */
        $this->start_controls_section(
			'section_play_icon_style',
			[
				'label'                 => __( 'Play Icon', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'play_icon_type!'   => 'none',
                ],
			]
		);

        $this->add_responsive_control(
            'play_icon_size',
            [
                'label'                 => __( 'Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min' => 10,
                        'max' => 400,
                    ],
                ],
                'default'               => [
                    'size' => 80,
                    'unit' => 'px',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-video-play-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    'play_icon_type!'   => 'none',
                ],
            ]
        );

		$this->add_control(
			'play_icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-video-play-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'play_icon_type'    => 'image',
                ],
			]
		);

        $this->start_controls_tabs( 'tabs_play_icon_style' );

        $this->start_controls_tab(
            'tab_play_icon_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
                    'play_icon_type'    => 'icon',
                    'play_icon!'        => '',
                ],
            ]
        );

        $this->add_control(
            'play_icon_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .pp-video-play-icon' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'play_icon_type'    => 'icon',
                    'play_icon!'        => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'                  => 'play_icon_text_shadow',
                'selector'              => '{{WRAPPER}} .pp-video-play-icon',
                'condition'             => [
                    'play_icon_type'    => 'icon',
                    'play_icon!'        => '',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_play_icon_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
                'condition'             => [
                    'play_icon_type'    => 'icon',
                    'play_icon!'        => '',
                ],
            ]
        );

        $this->add_control(
            'play_icon_hover_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .pp-video-container:hover .pp-video-play-icon' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'play_icon_type'    => 'icon',
                    'play_icon!'        => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'                  => 'play_icon_hover_text_shadow',
                'selector'              => '{{WRAPPER}} .pp-video-container:hover .pp-video-play-icon',
                'condition'             => [
                    'play_icon_type'    => 'icon',
                    'play_icon!'        => '',
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
					'{{WRAPPER}} .pp-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				],
                'condition'             => [
                    'arrows'        => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'arrows_position',
            [
                'label'                 => __( 'Align Arrows', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => -100,
                        'max'   => 50,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'selectors'         => [
					'{{WRAPPER}} .pp-arrow-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-arrow-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
                'condition'             => [
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
                    '{{WRAPPER}} .pp-slider-arrow' => 'background-color: {{VALUE}};',
                ],
                'condition'             => [
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
                    '{{WRAPPER}} .pp-slider-arrow' => 'color: {{VALUE}};',
                ],
                'condition'             => [
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
				'selector'              => '{{WRAPPER}} .pp-slider-arrow',
                'condition'             => [
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
					'{{WRAPPER}} .pp-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
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
                    '{{WRAPPER}} .pp-slider-arrow:hover' => 'background-color: {{VALUE}};',
                ],
                'condition'             => [
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
                    '{{WRAPPER}} .pp-slider-arrow:hover' => 'color: {{VALUE}};',
                ],
                'condition'             => [
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
                    '{{WRAPPER}} .pp-slider-arrow:hover',
                ],
                'condition'             => [
                    'arrows'        => 'yes',
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
					'{{WRAPPER}} .pp-slider-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'             => 'before',
                'condition'             => [
                    'arrows'        => 'yes',
                ],
			]
		);
        
        $this->end_controls_section();
        
        /**
         * Style Tab: Dots
         */
        $this->start_controls_section(
            'section_dots_style',
            [
                'label'                 => __( 'Dots', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'dots'      => 'yes',
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
				'prefix_class'          => 'pp-showcase-dots-',
                'condition'             => [
                    'dots'      => 'yes',
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
                    '{{WRAPPER}} .pp-showcase-preview .slick-dots li button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    'dots'      => 'yes',
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
                    '{{WRAPPER}} .pp-showcase-preview .slick-dots li' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'dots'      => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_dots_style' );

        $this->start_controls_tab(
            'tab_dots_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
                    'dots'      => 'yes',
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
                    '{{WRAPPER}} .pp-showcase-preview .slick-dots li' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    'dots'      => 'yes',
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
                    '{{WRAPPER}} .pp-showcase-preview .slick-dots li.slick-active' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    'dots'      => 'yes',
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
				'selector'              => '{{WRAPPER}} .pp-showcase-preview .slick-dots li',
                'condition'             => [
                    'dots'      => 'yes',
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
					'{{WRAPPER}} .pp-showcase-preview .slick-dots li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'dots'      => 'yes',
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
					'{{WRAPPER}} .pp-showcase-preview .slick-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'dots'      => 'yes',
                ],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
                'condition'             => [
                    'dots'      => 'yes',
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
                    '{{WRAPPER}} .pp-showcase-preview .slick-dots li:hover' => 'background: {{VALUE}};',
                ],
                'condition'             => [
                    'dots'      => 'yes',
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
                    '{{WRAPPER}} .pp-showcase-preview .slick-dots li:hover' => 'border-color: {{VALUE}};',
                ],
                'condition'             => [
                    'dots'      => 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'preview-wrap', 'class', 'pp-showcase-preview-wrap' );
        
        $this->add_render_attribute( 'preview', 'class', 'pp-showcase-preview' );
        $this->add_render_attribute( 'preview', 'id', 'pp-showcase-preview-'.esc_attr($this->get_id()) );
        ?>
        <div class="pp-showcase">
            <div <?php echo $this->get_render_attribute_string( 'preview-wrap' ); ?>>
                <div <?php echo $this->get_render_attribute_string( 'preview' ); ?>>
                    <?php
                        $this->render_preview();
                    ?>
                </div>
            </div>
            <?php
                // Items Navigation
                $this->render_navigation();
            ?>
        </div>
        <?php
    }
    
    protected function render_preview() {
        $settings = $this->get_settings_for_display();
        
        foreach ( $settings['items'] as $index => $item ) {
            ?>
            <div class="pp-showcase-preview-item">
                <?php
                if ( $item['content_type'] == 'image' && $item['image']['url'] ) {

                    $image_url = Group_Control_Image_Size::get_attachment_image_src( $item['image']['id'], 'image', $settings );

                    if ( ! $image_url ) {
                        $image_url = $item['image']['url'];
                    }

                    $image_html = '<div class="pp-showcase-preview-image">';

                    $image_html .= '<img src="' . esc_url( $image_url ) . '">';

                    $image_html .= '</div>';

                    if ( $settings['preview_caption'] != '' ) {
                        $image_html .= '<div class="pp-showcase-preview-content">';
                            $image_html .= $this->render_image_caption( $item['image']['id'], $settings['preview_caption'] );
                        $image_html .= '</div>';
                    }

                    if ( $item['link_to'] != 'none' ) {

                        $link_key = $this->get_repeater_setting_key( 'link', 'items', $index );

                        if ( $item['link_to'] == 'file' ) {
                            $link = wp_get_attachment_url( $item['image']['id'] );

                            $this->add_render_attribute( $link_key, [
                                'data-elementor-open-lightbox' 		=> $item['open_lightbox'],
                                'data-elementor-lightbox-slideshow' => $this->get_id(),
                                'data-elementor-lightbox-index' 	=> $index,
                            ] );
                        } elseif ( $item['link_to'] == 'custom' && $item['link']['url'] != '' ) {
                            $link = $item['link']['url'];

                            if ( ! empty( $link['is_external'] ) ) {
                                $this->add_render_attribute( $link_key, 'target', '_blank' );
                            }

                            if ( ! empty( $link['nofollow'] ) ) {
                                $this->add_render_attribute( $link_key, 'rel', 'nofollow' );
                            }
                        }

                        $this->add_render_attribute( $link_key, [
                            'href' 								=> $link,
                            'class' 							=> 'elementor-clickable',
                        ] );

                        $image_html = '<a ' . $this->get_render_attribute_string( $link_key ) . '>' . $image_html . '</a>';
                    }

                    echo $image_html;
                    
                } elseif ( $item['content_type'] == 'video' ) {
                    
                    $embed_params   = $this->get_embed_params( $item );
                    $video_url      = Embed::get_embed_url( $item['video_url'], $embed_params, [] );
                    $thumb_size     = $item['thumbnail_size'];
                    ?>
                    <div class="pp-video-container elementor-fit-aspect-ratio" data-src="<?php echo $video_url; ?>">
                        <div class="pp-video-player">
                            <img class="pp-video-thumb" src="<?php echo esc_url( $this->get_video_thumbnail( $item, $thumb_size ) ); ?>">
                            <?php $this->render_play_icon(); ?>
                        </div>
                    </div>
                    <?php
                    
                } elseif ( $item['content_type'] == 'section' && !empty( $item['saved_section'] ) ) {

                    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $item['saved_section'] );

                } elseif ( $item['content_type'] == 'template' && !empty( $item['templates'] ) ) {

                    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $item['templates'] );

                } elseif ( $item['content_type'] == 'widget' && !empty( $item['saved_widget'] ) ) {

                    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $item['saved_widget'] );

                }
                ?>
            </div>
            <?php
        }
    }
    
    protected function render_navigation() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="pp-showcase-navigation">
            <div class="pp-showcase-navigation-items pp-elementor-grid">
                <?php
                    foreach ( $settings['items'] as $item ) {
                        ?>
                        <div class="pp-showcase-navigation-item-wrap pp-grid-item-wrap">
                            <div class="pp-showcase-navigation-item pp-grid-item">
                                <?php if ( $item['nav_icon_type'] != 'nonw' ) { ?>
                                    <div class="pp-showcase-navigation-icon-wrap">
                                        <?php
                                            if ( $item['nav_icon_type'] == 'icon' ) {
                                                printf( '<span class="pp-showcase-navigation-icon %1$s"></span>', esc_attr( $item['nav_icon'] ) );
                                            } elseif ( $item['nav_icon_type'] == 'image' ) {
                                                printf( '<span class="pp-showcase-navigation-icon"><img src="%1$s"></span>', esc_url( $item['nav_icon_image']['url'] ) );
                                            }
                                        ?>
                                    </div>
                                <?php } ?>
                                
                                <?php if ( ! empty( $item['title'] ) ) { ?>
                                    <h4 class="pp-showcase-navigation-title">
                                        <?php echo $item['title']; ?>
                                    </h4>
                                <?php } ?>

                                <?php if ( ! empty( $item['description'] ) ) { ?>
                                    <div class="pp-showcase-navigation-description">
                                        <?php echo $item['description']; ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
        <?php
    }
    
     /**
	 * Render image overlay output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_image_overlay() {
        return '<div class="pp-showcase-preview-overlay"></div>';
    }
    
    /**
	 * Render play icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render_play_icon() {
        $settings = $this->get_settings_for_display();
        
        if ( $settings['play_icon_type'] == 'none' ) {
            return;
        }

        $this->add_render_attribute( 'play-icon', 'class', 'pp-video-play-icon' );
        
        if ( $settings['play_icon_type'] == 'icon' ) {
            
            if ( $settings['play_icon'] != '' ) {
                $this->add_render_attribute( 'play-icon', 'class', $settings['play_icon'] );
            } else {
                $this->add_render_attribute( 'play-icon', 'class', 'fa fa-play-circle' );
            }
            ?>
            <span <?php echo $this->get_render_attribute_string( 'play-icon' ); ?>></span>
            <?php

        } elseif ( $settings['play_icon_type'] == 'image' ) {
            
            if ( $settings['play_icon_image']['url'] != '' ) {
                ?>
                <span <?php echo $this->get_render_attribute_string( 'play-icon' ); ?>>
                    <img src="<?php echo esc_url( $settings['play_icon_image']['url'] ); ?>">
                </span>
                <?php
            }

        }
    }
    
    protected function render_image_caption( $id, $caption_type = 'caption' ) {
        $settings = $this->get_settings_for_display();
        
        $caption = Module::get_image_caption( $id, $caption_type );
        
        if ( $caption == '' ) {
			return '';
		}
        
        ob_start();
        ?>
        <div class="pp-showcase-preview-caption">
            <?php echo $caption; ?>
        </div>
        <?php
        $html = ob_get_contents();
		ob_end_clean();
        return $html;
    }

	/**
	 * Returns Video Thumbnail.
	 *
	 * @access protected
	 */
	protected function get_video_thumbnail( $item, $thumb_size ) {
        
        $thumb_url  = '';
        $video_id   = $this->get_video_id( $item );
        
        if ( $item['video_source'] == 'youtube' ) {

            if ( $video_id != '' ) {
                $thumb_url = 'https://i.ytimg.com/vi/' . $video_id . '/' . $thumb_size . '.jpg';
            }

        } elseif ( $item['video_source'] == 'vimeo' ) {

            if ( $video_id != '' ) {
                $vimeo = unserialize( file_get_contents( "https://vimeo.com/api/v2/video/$video_id.php" ) );
                $thumb_url = $vimeo[0]['thumbnail_large'];
            }
            
        } elseif ( $item['video_source'] == 'dailymotion' ) {

            if ( $video_id != '' ) {
                $dailymotion = 'https://api.dailymotion.com/video/'.$video_id.'?fields=thumbnail_url';
                $get_thumbnail = json_decode( file_get_contents( $dailymotion ), TRUE );
                $thumb_url = $get_thumbnail['thumbnail_url'];
            }
        }
        
        return $thumb_url;

    }

	/**
	 * Returns Video ID.
	 *
	 * @access protected
	 */
	protected function get_video_id( $item ) {

		$video_id = '';
		$url      = $item['video_url'];

		if ( $item['video_source'] == 'youtube' ) {

			if ( preg_match( "#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $url, $matches ) ) {
				$video_id = $matches[0];
			}

		} elseif ( $item['video_source'] == 'vimeo' ) {

			$video_id = preg_replace( '/[^\/]+[^0-9]|(\/)/', '', rtrim( $url, '/' ) );

		} elseif ( $item['video_source'] == 'dailymotion' ) {
            
            if ( preg_match('/^.+dailymotion.com\/(?:video|swf\/video|embed\/video|hub|swf)\/([^&?]+)/', $url, $matches) ) {
				$video_id = $matches[1];
			}

		}

		return $video_id;

	}

	/**
	 * Get embed params.
	 *
	 * Retrieve video widget embed parameters.
	 *
	 * @since 1.5.0
	 * @access public
	 *
	 * @return array Video embed parameters.
	 */
	public function get_embed_params( $item ) {
		$settings = $this->get_settings_for_display();

		$params = [];

		$params_dictionary = [];

		if ( 'youtube' === $item['video_source'] ) {
            
            $params_dictionary = [
				'mute',
			];

			$params['autoplay'] = 1;

			$params['wmode'] = 'opaque';
		} elseif ( 'vimeo' === $item['video_source'] ) {
            
            $params_dictionary = [
				'mute' => 'muted',
			];

            $params['autopause'] = '0';
			$params['autoplay'] = '1';
		} elseif ( 'dailymotion' === $item['video_source'] ) {
            
            $params_dictionary = [
				'mute',
			];
            
			$params['endscreen-enable'] = '0';
			$params['autoplay'] = 1;

		}

		foreach ( $params_dictionary as $key => $param_name ) {
			$setting_name = $param_name;

			if ( is_string( $key ) ) {
				$setting_name = $key;
			}

			$setting_value = $settings[ $setting_name ] ? '1' : '0';

			$params[ $param_name ] = $setting_value;
		}

		return $params;
	}

	/**
	 *  Get Saved Widgets
	 *
	 *  @param string $type Type.
	 *  
	 *  @return string
	 */
	public function get_page_template_options( $type = '' ) {

		$page_templates = pp_get_page_templates( $type );

		$options[-1]   = __( 'Select', 'power-pack' );

		if ( count( $page_templates ) ) {
			foreach ( $page_templates as $id => $name ) {
				$options[ $id ] = $name;
			}
		} else {
			$options['no_template'] = __( 'No saved templates found!', 'power-pack' );
		}

		return $options;
	}

    protected function _content_template() {
    }
}