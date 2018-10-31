<?php
namespace PowerpackElements\Modules\Gallery\Widgets;

use PowerpackElements\Base\Powerpack_Widget;
use PowerpackElements\Modules\Gallery\Module;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Gallery Slider Widget
 */
class Image_Gallery extends Powerpack_Widget {
    
    /**
	 * Retrieve gallery slider widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-image-gallery';
    }

    /**
	 * Retrieve gallery slider widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Image Gallery', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the gallery slider widget belongs to.
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
	 * Retrieve gallery slider widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-image-gallery power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the gallery slider widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'tilt',
            'isotope',
            'jquery-resize',
            'imagesloaded',
            'powerpack-frontend'
        ];
    }

    /**
	 * Register gallery slider widget controls.
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
         * Content Tab: Gallery
         */
        $this->start_controls_section(
            'section_gallery',
            [
                'label'                 => __( 'Gallery', 'power-pack' ),
            ]
        );
        
        $repeater = new Repeater();
        
        $repeater->add_control(
			'filter_label',
			[
				'label'                 => __( 'Filter Label', 'power-pack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'placeholder'           => '',
                'dynamic'               => [
                    'active' => true
                ],
			]
		);
        
        $repeater->add_control(
            'image_group',
            [
                'label'                 => __( 'Add Images', 'power-pack' ),
                'type'                  => Controls_Manager::GALLERY,
                'dynamic'               => [
                    'active' => true
                ],
            ]
        );
        
        $this->add_control(
            'gallery_images',
            [
                'label'                 => '',
                'type'                  => Controls_Manager::REPEATER,
                'fields'                => array_values( $repeater->get_controls() ),
                'title_field'           => '',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Filter
         */
        $this->start_controls_section(
            'section_filter',
            [
                'label'                 => __( 'Filter', 'power-pack' ),
            ]
        );

		$this->add_control(
			'filter_enable',
			[
				'label'                 => __( 'Enable Filter', 'power-pack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
			]
		);

        $this->add_control(
            'filter_all_label',
            [
                'label'                 => __( '"All" Filter Label', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => __( 'All', 'power-pack' ),
                'condition'             => [
					'filter_enable'    => 'yes',
                ]
            ]
        );
        
        $this->add_responsive_control(
			'filter_alignment',
			[
				'label'                 => __( 'Alignment', 'power-pack' ),
                'label_block'           => true,
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'left',
				'options'               => [
					'left'          => [
						'title'     => __( 'Left', 'power-pack' ),
						'icon'      => 'eicon-h-align-left',
					],
					'center'        => [
						'title'     => __( 'Center', 'power-pack' ),
						'icon'      => 'eicon-h-align-center',
					],
					'right'         => [
						'title'     => __( 'Right', 'power-pack' ),
						'icon'      => 'eicon-h-align-right',
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-filters'   => 'text-align: {{VALUE}};',
				],
				'condition'             => [
					'filter_enable'    => 'yes',
				],
			]
		);
        
        $this->end_controls_section();

        /**
         * Content Tab: Settings
         */
        $this->start_controls_section(
            'section_settings',
            [
                'label'                 => __( 'Settings', 'power-pack' ),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'                  => 'image',
                'label'                 => __( 'Image Size', 'power-pack' ),
                'default'               => 'full',
                'exclude' 	=> [ 'custom' ],
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'                 => __( 'Layout', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'grid',
                'options'               => [
                    'grid' 		=> __( 'Grid', 'power-pack' ),
                    'masonry' 	=> __( 'Masonry', 'power-pack' ),
                ],
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
                'prefix_class'          => 'elementor-grid%s-',
                'frontend_available'    => true,
            ]
        );

        $this->add_control(
            'caption',
            [
                'label'                 => __( 'Caption', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'show',
                'options'               => [
                    'show'  => __( 'Show', 'power-pack' ),
                    'hide' 	=> __( 'Hide', 'power-pack' ),
                ],
            ]
        );

        $this->add_control(
            'caption_type',
            [
                'label'                 => __( 'Caption Type', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'caption',
                'options'               => [
                    'title' 		=> __( 'Title', 'power-pack' ),
                    'caption' 		=> __( 'Caption', 'power-pack' ),
                ],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
            ]
        );

        $this->add_control(
            'caption_position',
            [
                'label'                 => __( 'Caption Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'over_image',
                'options'               => [
                    'over_image'    => __( 'Over Image', 'power-pack' ),
                    'below_image' 	=> __( 'Below Image', 'power-pack' ),
                ],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
            ]
        );

        $this->add_control(
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
            ]
        );

        $this->add_control(
            'link',
            [
                'label'                 => __( 'Link', 'power-pack' ),
                'show_label'            => false,
                'type'                  => Controls_Manager::URL,
                'placeholder'           => __( 'http://your-link.com', 'power-pack' ),
                'condition'             => [
                    'link_to'   => 'custom',
                ],
            ]
        );

        $this->add_control(
            'open_lightbox',
            [
                'label'                 => __( 'Lightbox', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'default',
                'options'               => [
                    'default'   => __( 'Default', 'power-pack' ),
                    'yes' 		=> __( 'Yes', 'power-pack' ),
                    'no' 		=> __( 'No', 'power-pack' ),
                ],
                'condition'             => [
                    'link_to' => 'file',
                ],
            ]
        );
        
        $this->add_control(
			'link_icon',
			[
				'label'                 => __( 'Link Icon', 'power-pack' ),
				'type'                  => Controls_Manager::ICON,
				'default'               => '',
                'condition'             => [
                    'link_to!' => 'none',
                ],
			]
		);

        $this->add_control(
            'ordering',
            [
                'label'                 => __( 'Ordering', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => '',
                'options'               => [
                    ''          => __( 'Default', 'power-pack' ),
                    'random'    => __( 'Random', 'power-pack' ),
                ],
            ]
        );

        $this->end_controls_section();
		
		/**
         * Content Tab: Load More Button
         */
        $this->start_controls_section(
            'section_pagination',
            [
                'label'                 => __( 'Load More Button', 'power-pack' ),
            ]
		);

		$this->add_control(
			'pagination',
			[
				'label'                 => __( 'Load More Button', 'power-pack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
                'frontend_available'    => true,
			]
		);
		
		$this->add_control(
			'images_per_page',
			[
				'label'                 => __('Images Per Page', 'power-pack'),
				'type'                  => Controls_Manager::TEXT,
				'default'			    => 6,
				'condition'			    => [
					'pagination'		=> 'yes'
				]
			]
		);

		$this->add_control(
			'load_more_text',
			[
				'label'				    => __('Button Text', 'power-pack'),
				'type'				    => Controls_Manager::TEXT,
				'default'			    => __('Load More', 'power-pack'),
				'condition'			    => [
					'pagination'		=> 'yes'
				]
			]
		);

        $this->add_control(
            'load_more_icon',
            [
                'label'                 => __( 'Button Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => '',
                'condition'             => [
                    'pagination'		=> 'yes'
                ],
            ]
        );
        
        $this->add_control(
            'button_icon_position',
            [
                'label'                 => __( 'Icon Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'after',
                'options'               => [
                    'after'     => __( 'After', 'power-pack' ),
                    'before'    => __( 'Before', 'power-pack' ),
                ],
                'condition'             => [
                    'pagination'		=> 'yes'
                ],
            ]
        );
        
        $this->add_responsive_control(
			'load_more_align',
			[
				'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'          => [
						'title'     => __( 'Left', 'power-pack' ),
						'icon'      => 'eicon-h-align-left',
					],
					'center'        => [
						'title'     => __( 'Center', 'power-pack' ),
						'icon'      => 'eicon-h-align-center',
					],
					'right'         => [
						'title'     => __( 'Right', 'power-pack' ),
						'icon'      => 'eicon-h-align-right',
					],
				],
				'default'               => 'center',
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-pagination'   => 'text-align: {{VALUE}};',
				],
                'condition'             => [
                    'pagination'		=> 'yes'
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
                'label'                 => __( 'Layout', 'power-pack' ),
                'tab'               => Controls_Manager::TAB_STYLE,
            ]
        );$this->add_responsive_control(
			'columns_gap',
			[
				'label'                 => __( 'Columns Gap', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 20,
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
					'{{WRAPPER}} .pp-image-gallery .pp-grid-item-wrap' => 'padding-left: calc({{SIZE}}{{UNIT}}/2); padding-right: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}} .pp-image-gallery' => 'margin-left: calc(-{{SIZE}}{{UNIT}}/2); margin-right: calc(-{{SIZE}}{{UNIT}}/2);',
				],
			]
		);
        
        $this->add_responsive_control(
			'rows_gap',
			[
				'label'                 => __( 'Rows Gap', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
                    'size' => 20,
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
					'{{WRAPPER}} .pp-image-gallery .pp-grid-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Thumbnails
         */
        $this->start_controls_section(
            'section_thumbnails_style',
            [
                'label'                 => __( 'Thumbnails', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $pp_image_filters = [
            'normal'            => __( 'Normal', 'power-pack' ),
            'filter-1977'       => __( '1977', 'power-pack' ),
            'filter-aden'       => __( 'Aden', 'power-pack' ),
            'filter-amaro'      => __( 'Amaro', 'power-pack' ),
            'filter-ashby'      => __( 'Ashby', 'power-pack' ),
            'filter-brannan'    => __( 'Brannan', 'power-pack' ),
            'filter-brooklyn'   => __( 'Brooklyn', 'power-pack' ),
            'filter-charmes'    => __( 'Charmes', 'power-pack' ),
            'filter-clarendon'  => __( 'Clarendon', 'power-pack' ),
            'filter-crema'      => __( 'Crema', 'power-pack' ),
            'filter-dogpatch'   => __( 'Dogpatch', 'power-pack' ),
            'filter-earlybird'  => __( 'Earlybird', 'power-pack' ),
            'filter-gingham'    => __( 'Gingham', 'power-pack' ),
            'filter-ginza'      => __( 'Ginza', 'power-pack' ),
            'filter-hefe'       => __( 'Hefe', 'power-pack' ),
            'filter-helena'     => __( 'Helena', 'power-pack' ),
            'filter-hudson'     => __( 'Hudson', 'power-pack' ),
            'filter-inkwell'    => __( 'Inkwell', 'power-pack' ),
            'filter-juno'       => __( 'Juno', 'power-pack' ),
            'filter-kelvin'     => __( 'Kelvin', 'power-pack' ),
            'filter-lark'       => __( 'Lark', 'power-pack' ),
            'filter-lofi'       => __( 'Lofi', 'power-pack' ),
            'filter-ludwig'     => __( 'Ludwig', 'power-pack' ),
            'filter-maven'      => __( 'Maven', 'power-pack' ),
            'filter-mayfair'    => __( 'Mayfair', 'power-pack' ),
            'filter-moon'       => __( 'Moon', 'power-pack' ),
        ];

        $this->start_controls_tabs( 'tabs_image_filter_style' );

        $this->start_controls_tab(
            'tab_image_filter_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'image_scale',
            [
                'label'                 => __( 'Scale', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 2,
                        'step' => 0.01,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-gallery-thumbnail img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->add_control(
            'image_opacity',
            [
                'label'                 => __( 'Opacity', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-gallery-thumbnail img' => 'opacity: {{SIZE}}',
                ],
            ]
        );

        $this->add_control(
            'thumbnail_filter',
            [
                'label'                 => __( 'Image Filter', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'normal',
                'options'               => $pp_image_filters,
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_image_filter_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'image_hover_scale',
            [
                'label'                 => __( 'Scale', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 2,
                        'step' => 0.01,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-gallery-thumbnail-wrap:hover .pp-image-gallery-thumbnail img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->add_control(
            'image_hover_opacity',
            [
                'label'                 => __( 'Opacity', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0,
                        'step' => 0.01,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-gallery-thumbnail-wrap:hover .pp-image-gallery-thumbnail img' => 'opacity: {{SIZE}}',
                ],
            ]
        );

        $this->add_control(
            'thumbnail_hover_filter',
            [
                'label'                 => __( 'Image Filter', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'normal',
                'options'               => $pp_image_filters,
            ]
        );

		$this->add_control(
			'tilt',
			[
				'label'                 => __( 'Tilt', 'power-pack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
			]
		);
        
        $this->add_control(
            'tilt_axis',
            [
                'label'                 => __( 'Axis', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => '',
                'options'               => [
                    '' 		=> __( 'Both', 'power-pack' ),
                    'x' 	=> __( 'X Axis', 'power-pack' ),
                    'y' 	=> __( 'Y Axis', 'power-pack' ),
                ],
                'condition'             => [
                    'tilt'    => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tilt_amount',
            [
                'label'                 => __( 'Amount', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' 	=> [
                        'min' => 10,
                        'max' => 50,
                    ],
                ],
                'default'               => [
                    'size' 	=> 20,
                ],
                'condition'             => [
                    'tilt'    => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tilt_scale',
            [
                'label'                 => __( 'Scale', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' 	=> [
                        'min' 	=> 1,
                        'max' 	=> 1.4,
                        'step'	=> 0.01,
                    ],
                ],
                'default' 		=> [
                    'size' 		=> 1.06,
                ],
                'condition'             => [
                    'tilt'    => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tilt_caption_depth',
            [
                'label'                 => __( 'Depth', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' 	=> [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'               => [
                    'size' 	=> 20,
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-grid-item .pp-gallery-image-content' => 'transform: translateZ({{SIZE}}px);',
                ],
                'condition'             => [
                    'tilt'    => 'yes',
                ],
            ]
        );

        $this->add_control(
            'tilt_speed',
            [
                'label'                 => __( 'Speed', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' 	=> [
                        'min' 	=> 100,
                        'max' 	=> 1000,
                        'step'	=> 20,
                    ],
                ],
                'default' 		=> [
                    'size' 		=> 800,
                ],
                'condition'             => [
                    'tilt'    => 'yes',
                ],
            ]
        );
        
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Caption
         */
        $this->start_controls_section(
            'section_caption_style',
            [
                'label'                 => __( 'Caption', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'caption' 	=> 'show',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'caption_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-gallery-image-caption',
                'condition'             => [
                    'caption' 	=> 'show',
                ],
            ]
        );
        
        $this->add_control(
			'caption_vertical_align',
			[
				'label'                 => __( 'Vertical Align', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'toggle'                => false,
				'default'               => 'bottom',
				'options'               => [
					'top'          => [
						'title'    => __( 'Top', 'power-pack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => __( 'Center', 'power-pack' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => __( 'Bottom', 'power-pack' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-image-content'   => 'justify-content: {{VALUE}};',
				],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
			]
		);
        
        $this->add_control(
			'caption_horizontal_align',
			[
				'label'                 => __( 'Horizontal Align', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'toggle'                => false,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'           => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'            => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'eicon-h-align-right',
					],
					'justify'          => [
						'title' => __( 'Justify', 'power-pack' ),
						'icon'  => 'eicon-h-align-stretch',
					],
				],
				'default'               => 'left',
                'selectors_dictionary'  => [
					'left'     => 'flex-start',
					'center'   => 'center',
					'right'    => 'flex-end',
					'justify'  => 'stretch',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-image-content' => 'align-items: {{VALUE}};',
				],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
			]
		);
        
        $this->add_control(
            'caption_text_align',
            [
                'label'                 => __( 'Text Align', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
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
					'{{WRAPPER}} .pp-gallery-image-caption' => 'text-align: {{VALUE}};',
				],
                'condition'             => [
                    'caption' 	               => 'show',
					'caption_horizontal_align' => 'justify',
                ]
			]
		);

		$this->add_responsive_control(
			'caption_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-image-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
			]
		);

		$this->add_responsive_control(
			'caption_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-image-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
			]
		);

        $this->start_controls_tabs( 'tabs_caption_style' );

        $this->start_controls_tab(
            'tab_caption_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
                    'caption' 	=> 'show',
                ],
            ]
        );

        $this->add_control(
            'caption_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-gallery-image-caption' => 'background-color: {{VALUE}};',
                ],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
            ]
        );

        $this->add_control(
            'caption_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-gallery-image-caption' => 'color: {{VALUE}};',
                ],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'caption_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-gallery-image-caption',
                'condition'             => [
                    'caption' 	=> 'show',
                ],
			]
		);

		$this->add_control(
			'caption_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-image-caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
			]
		);
        
        $this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'                  => 'caption_text_shadow',
				'label'                 => __( 'Text Shadow', 'power-pack' ),
				'selector'              => '{{WRAPPER}} .pp-gallery-image-caption',
                'condition'             => [
                    'caption' 	=> 'show',
                ],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_caption_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
                'condition'             => [
                    'caption' 	=> 'show',
                ],
            ]
        );

        $this->add_control(
            'caption_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-grid-item:hover .pp-gallery-image-caption' => 'background-color: {{VALUE}};',
                ],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
            ]
        );

        $this->add_control(
            'caption_color_hover',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-grid-item:hover .pp-gallery-image-caption' => 'color: {{VALUE}};',
                ],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
            ]
        );

        $this->add_control(
            'caption_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-grid-item:hover .pp-gallery-image-caption' => 'border-color: {{VALUE}};',
                ],
                'condition'             => [
                    'caption' 	=> 'show',
                ],
            ]
        );
        
        $this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'                  => 'caption_text_shadow_hover',
				'label'                 => __( 'Text Shadow', 'power-pack' ),
				'selector'              => '{{WRAPPER}} .pp-grid-item:hover .pp-gallery-image-caption',
                'condition'             => [
                    'caption' 	=> 'show',
                ],
			]
		);
        
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        /**
         * Style Tab: Link Icon
         */
        $this->start_controls_section(
            'section_link_icon_style',
            [
                'label'                 => __( 'Link Icon', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
					'link_icon!'   => '',
                ]
            ]
        );

        $this->start_controls_tabs( 'tabs_link_icon_style' );

        $this->start_controls_tab(
            'tab_link_icon_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
					'link_icon!'   => '',
                ]
            ]
        );

        $this->add_control(
            'link_icon_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-grid-item .pp-gallery-image-icon' => 'color: {{VALUE}};',
                ],
                'condition'             => [
					'link_icon!'   => '',
                ]
            ]
        );

        $this->add_control(
            'link_icon_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-grid-item .pp-gallery-image-icon' => 'background-color: {{VALUE}};',
                ],
                'condition'             => [
					'link_icon!'   => '',
                ]
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'link_icon_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-grid-item .pp-gallery-image-icon',
                'condition'             => [
					'link_icon!'   => '',
                ]
			]
		);

		$this->add_control(
			'link_icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-grid-item .pp-gallery-image-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
					'link_icon!'   => '',
                ]
			]
		);
        
        $this->add_responsive_control(
            'link_icon_size',
            [
                'label'                 => __( 'Icon Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 5,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'condition'             => [
                    'icon_type'     => 'icon',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-grid-item .pp-gallery-image-icon' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
					'link_icon!'   => '',
                ]
            ]
        );
        
        $this->add_control(
            'link_icon_opacity_normal',
            [
                'label'                 => __( 'Opacity', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.01,
                    ],
                ],
				'selectors'             => [
					'{{WRAPPER}} .pp-grid-item .pp-gallery-image-icon' => 'opacity: {{SIZE}};',
				],
                'condition'             => [
					'link_icon!'   => '',
                ]
            ]
        );

		$this->add_responsive_control(
			'link_icon_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-grid-item .pp-gallery-image-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
					'link_icon!'   => '',
                ]
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_link_icon_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
                'condition'             => [
					'link_icon!'   => '',
                ]
            ]
        );

        $this->add_control(
            'link_icon_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-grid-item:hover .pp-gallery-image-icon' => 'color: {{VALUE}};',
                ],
                'condition'             => [
					'link_icon!'   => '',
                ]
            ]
        );

        $this->add_control(
            'link_icon_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-grid-item:hover .pp-gallery-image-icon' => 'background-color: {{VALUE}};',
                ],
                'condition'             => [
					'link_icon!'   => '',
                ]
            ]
        );

        $this->add_control(
            'link_icon_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-grid-item:hover .pp-gallery-image-icon' => 'border-color: {{VALUE}};',
                ],
                'condition'             => [
					'link_icon!'   => '',
                ]
            ]
        );
        
        $this->add_control(
            'link_icon_opacity_hover',
            [
                'label'                 => __( 'Opacity', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.01,
                    ],
                ],
				'selectors'             => [
					'{{WRAPPER}} .pp-grid-item:hover .pp-gallery-image-icon' => 'opacity: {{SIZE}};',
				],
                'condition'             => [
					'link_icon!'   => '',
                ]
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Overlay
         */
        $this->start_controls_section(
            'section_overlay_style',
            [
                'label'                 => __( 'Overlay', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'overlay_blend_mode',
            [
                'label'                 => __( 'Blend Mode', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'normal',
                'options'               => [
                    'normal'		=> __( 'Normal', 'power-pack' ),
                    'multiply'		=> __( 'Multiply', 'power-pack' ),
                    'screen'		=> __( 'Screen', 'power-pack' ),
                    'overlay'		=> __( 'Overlay', 'power-pack' ),
                    'darken'		=> __( 'Darken', 'power-pack' ),
                    'lighten'		=> __( 'Lighten', 'power-pack' ),
                    'color-dodge'   => __( 'Color Dodge', 'power-pack' ),
                    'color'			=> __( 'Color', 'power-pack' ),
                    'hue'			=> __( 'Hue', 'power-pack' ),
                    'hard-light'	=> __( 'Hard Light', 'power-pack' ),
                    'soft-light'	=> __( 'Soft Light', 'power-pack' ),
                    'difference'	=> __( 'Difference', 'power-pack' ),
                    'exclusion'		=> __( 'Exclusion', 'power-pack' ),
                    'saturation'	=> __( 'Saturation', 'power-pack' ),
                    'luminosity'	=> __( 'Luminosity', 'power-pack' ),
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-overlay' => 'mix-blend-mode: {{VALUE}};',
                ],
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
				'name'                  => 'overlay_background_color_normal',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-image-overlay',
                'exclude'               => [
                    'image',
                ],
			]
		);
        
        $this->add_control(
			'overlay_margin_normal',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
                        'min'   => 0,
                        'max'   => 50,
                        'step'  => 1,
                    ],
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-image-overlay' => 'top: {{SIZE}}px; bottom: {{SIZE}}px; left: {{SIZE}}px; right: {{SIZE}}px;',
				],
			]
		);
        
        $this->add_control(
			'overlay_opacity_normal',
			[
				'label'                 => __( 'Opacity', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.01,
                    ],
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-image-overlay' => 'opacity: {{SIZE}};',
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
				'name'                  => 'overlay_background_color_hover',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-grid-item:hover .pp-image-overlay',
                'exclude'               => [
                    'image',
                ],
			]
		);
        
        $this->add_control(
			'overlay_margin_hover',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
                        'min'   => 0,
                        'max'   => 50,
                        'step'  => 1,
                    ],
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-grid-item:hover .pp-image-overlay' => 'top: {{SIZE}}px; bottom: {{SIZE}}px; left: {{SIZE}}px; right: {{SIZE}}px;',
				],
			]
		);
        
        $this->add_control(
			'overlay_opacity_hover',
			[
				'label'                 => __( 'Opacity', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
                        'min'   => 0,
                        'max'   => 1,
                        'step'  => 0.01,
                    ],
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-grid-item:hover .pp-image-overlay' => 'opacity: {{SIZE}};',
				],
			]
		);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        /**
         * Style Tab: Filter
         */
        $this->start_controls_section(
            'section_filter_style',
            [
                'label'                 => __( 'Filter', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'filter_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-gallery-filters',
            ]
        );
        
        $this->add_responsive_control(
            'filters_margin_bottom',
            [
                'label'                 => __( 'Filters Bottom Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-gallery-filters .pp-gallery-filter' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_filter_style' );

        $this->start_controls_tab(
            'tab_filter_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'filter_color_normal',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-gallery-filters .pp-gallery-filter' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_background_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-gallery-filters .pp-gallery-filter' => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'filter_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-gallery-filters .pp-gallery-filter',
			]
		);

		$this->add_control(
			'filter_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-filters .pp-gallery-filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filter_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-filters .pp-gallery-filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'filter_box_shadow',
				'selector'          => '{{WRAPPER}} .pp-gallery-filters .pp-gallery-filter',
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dots_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'filter_color_hover',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-gallery-filters .pp-gallery-filter:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_background_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-gallery-filters .pp-gallery-filter:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'filter_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-gallery-filters .pp-gallery-filter:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'filter_box_shadow_hover',
				'selector'          => '{{WRAPPER}} .pp-gallery-filters .pp-gallery-filter:hover',
			]
		);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Load More Button
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box_button_style',
            [
                'label'                 => __( 'Load More Button', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
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
					'pagination'        => 'yes',
					'load_more_text!'   => '',
				],
			]
		);
        
        $this->add_responsive_control(
            'button_margin_top',
            [
                'label'                 => __( 'Top Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 0,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-gallery-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_load_more_button_style' );

        $this->start_controls_tab(
            'tab_load_more_button_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
				],
            ]
        );

        $this->add_control(
            'load_more_button_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-gallery-load-more' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
				],
            ]
        );

        $this->add_control(
            'load_more_button_text_color_normal',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-gallery-load-more' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'load_more_button_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-gallery-load-more',
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
				],
			]
		);

		$this->add_control(
			'load_more_button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'load_more_button_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-gallery-load-more',
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
				],
            ]
        );

		$this->add_responsive_control(
			'load_more_button_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'load_more_button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-gallery-load-more',
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
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
					'pagination'        => 'yes',
					'load_more_icon!'   => '',
                ],
            ]
        );

		$this->add_responsive_control(
			'load_more_button_icon_margin',
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
				'selectors'             => [
					'{{WRAPPER}} .pp-gallery-pagination .pp-gallery-load-more-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
                'condition'             => [
					'pagination'        => 'yes',
					'load_more_icon!'   => '',
                ],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
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
                    '{{WRAPPER}} .pp-gallery-load-more:hover' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
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
                    '{{WRAPPER}} .pp-gallery-load-more:hover' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
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
                    '{{WRAPPER}} .pp-gallery-load-more:hover' => 'border-color: {{VALUE}}',
                ],
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-gallery-load-more:hover',
				'condition'             => [
					'pagination'        => 'yes',
					'load_more_text!'   => '',
				],
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $classes = [
            'pp-image-gallery',
            'pp-elementor-grid'
        ];
        
        if ( $settings['layout'] == 'masonry' ) {
            $classes[] = 'pp-image-gallery-masonry';
        }
        
        if ( $settings['filter_enable'] == 'yes' ) {
            $classes[] = 'pp-image-gallery-filter-enabled';
        }
            
        if ( $settings['caption_position'] == 'over_image' ) {
            $classes[] = 'pp-gallery-caption-over-image';
        }
            
        if ( $settings['thumbnail_filter'] != '' ) {
            $classes[] = 'pp-ins-' . $settings['thumbnail_filter'];
        }
            
        if ( $settings['thumbnail_hover_filter'] != '' ) {
            $classes[] = 'pp-ins-hover-' . $settings['thumbnail_hover_filter'];
        }
        
		$this->add_render_attribute( 'gallery', 'class', $classes );
		
		$pp_gallery_settings = [];
        
        if ( $settings['tilt'] == 'yes' ) {
            $pp_tilt_options = [
                'tilt_enable'       => 'yes',
                'tilt_axis'         => ! empty( $settings['tilt_axis'] ) ? $settings['tilt_axis'] : '',
                'tilt_amount'       => ! empty( $settings['tilt_amount']['size'] ) ? $settings['tilt_amount']['size'] : '20',
                'tilt_scale'        => ! empty( $settings['tilt_scale']['size'] ) ? $settings['tilt_scale']['size'] : '1.06',
                'tilt_speed'        => ! empty( $settings['tilt_speed']['size'] ) ? $settings['tilt_speed']['size'] : '800',
            ];
        } else {
            $pp_tilt_options = [
                'tilt_enable'       => 'no',
            ];
		}
		
		$pp_gallery_settings = array_merge( $pp_gallery_settings, $pp_tilt_options );

		$pp_gallery_settings['layout'] = $settings['layout'];

		if ( $settings['pagination'] == 'yes' ) {
			$pp_gallery_settings['pagination'] = $settings['pagination'];
			$pp_gallery_settings['per_page'] = $settings['images_per_page'];
		}

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$pp_gallery_settings['post_id'] = \Elementor\Plugin::$instance->editor->get_post_id();
		} else {
			$pp_gallery_settings['post_id'] = get_the_ID();
		}

		$pp_gallery_settings['widget_id'] = $this->get_id();
        
        $this->add_render_attribute( 'gallery', 'data-settings', wp_json_encode( $pp_gallery_settings ) );
        
        ?>
        <div class="pp-image-gallery-container">
            <div class="pp-image-gallery-wrapper">
                <?php $this->render_filters(); ?>
                <div <?php echo $this->get_render_attribute_string( 'gallery' ); ?>>
                    <?php $this->render_gallery_items(); ?>
                </div>
				<?php $this->render_pagination(); ?>				
            </div>
        </div>
        <?php
        
        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {

			if ( ( 'grid' === $settings['layout'] && 'yes' === $settings['filter_enable'] ) || 'masonry' === $settings['layout'] ) {
				$this->render_editor_script();
			}
		}
    }
    
	protected function render_filters() {
		$settings = $this->get_settings_for_display();

		if ( $settings['filter_enable'] == 'yes' ) {
			$all_text 	= ( $settings['filter_all_label'] != '' ) ? $settings['filter_all_label'] : esc_html__('All', 'power-pack');
			$gallery	= $settings['gallery_images'];
			?>
			<div class="pp-gallery-filters">
                <div class="pp-gallery-filter pp-active" data-filter="*">
                    <?php echo $all_text; ?>
                </div>
				<?php foreach ( $gallery as $index => $item ) {
					$filter_label = $item['filter_label'];
					if ( empty( $filter_label ) ) {
						$filter_label = __('Group ', 'power-pack');
						$filter_label .= ( $index + 1 );
					}
					?>
					<div class="pp-gallery-filter" data-filter=".pp-group-<?php echo ( $index + 1 ); ?>"><?php echo $filter_label; ?></div>
				<?php } ?>
			</div>
			<?php
		}
	}
	
	protected function render_gallery_items() {
		$settings 		= $this->get_settings_for_display();
		$filter_labels 	= $this->get_filter_ids( $settings['gallery_images'], true );
		$photos			= $this->get_photos();
		$count 			= 0;

		foreach ( $photos as $photo ) {
            $pp_grid_item_key = $this->get_repeater_setting_key( 'gallery_item', 'gallery_images', $count );

            $this->add_render_attribute( $pp_grid_item_key, 'class', 'pp-grid-item' );

            if ( $settings['tilt'] == 'yes' ) {
                $this->add_render_attribute( $pp_grid_item_key, 'class', 'pp-gallery-tilt' );
            }

			$filter_label 		= $filter_labels[ $photo->id ];
			$final_filter_label = preg_replace('/[^\sA-Za-z0-9]/', '-', $filter_label); ?>
			
			<div class="pp-grid-item-wrap <?php echo $final_filter_label; ?>" data-item-id="<?php echo $photo->id; ?>">
				<div <?php echo $this->get_render_attribute_string( $pp_grid_item_key ); ?>>
					<div class="pp-image-gallery-thumbnail-wrap pp-ins-filter-hover">
						<?php

						$image_html = '<div class="pp-ins-filter-target pp-image-gallery-thumbnail"><img class="pp-gallery-slide-image" src="' . esc_attr( $photo->src ) . '" alt="' . $photo->alt . '" data-no-lazy="1" /></div>';

						$image_html .= $this->render_image_overlay();

                        $image_html .= '<div class="pp-gallery-image-content pp-gallery-image-over-content">';

                        // Link Icon
                        $image_html .= $this->render_link_icon();

						if ( $settings['caption_position'] == 'over_image' ) {
                            // Image Caption
                            $image_html .= $this->render_image_caption( $photo->id );
                        }

                        $image_html .= '</div>';

						if ( $settings['link_to'] != 'none' ) {

							$link_key = $this->get_repeater_setting_key( 'link', 'gallery_images', $count );

							if ( $settings['link_to'] == 'file' ) {
								$link = wp_get_attachment_url( $photo->id );

								$this->add_render_attribute( $link_key, [
									'data-elementor-open-lightbox' 		=> $settings['open_lightbox'],
									'data-elementor-lightbox-slideshow' => $this->get_id(),
								] );
							} elseif ( $settings['link_to'] == 'custom' && $settings['link']['url'] != '' ) {
								$link = $settings['link']['url'];

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

						if ( $settings['caption_position'] == 'below_image' ) {
                            ?>
                            <div class="pp-gallery-image-content">
                                <?php
                                    // Image Caption
                                    echo $this->render_image_caption( $photo->id );
                                ?>
                            </div>
                            <?php
                        }
						?>
					</div>
				</div>
			</div><?php
			$count++;
		}
	}

	protected function render_pagination() {
		$settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'load-more-button', 'class', [
				'pp-gallery-load-more',
				'elementor-button',
				'elementor-size-' . $settings['button_size'],
			]
		);

		if ( $settings['pagination'] == 'yes' ) { ?>
		<div class="pp-gallery-pagination">
			<a href="#" <?php echo $this->get_render_attribute_string( 'load-more-button' ); ?>>
                <span class="pp-button-loader"></span>
                <?php if ( ! empty( $settings['load_more_icon'] ) ) { ?>
                    <span class="pp-gallery-load-more-icon <?php echo esc_attr( $settings['load_more_icon'] ); ?>" aria-hidden="true"></span>
                <?php } ?>
                <span class="pp-gallery-load-more-text">
                    <?php echo $settings['load_more_text']; ?>
                </span>
            </a>
		</div>
		<?php }
	}
    
    protected function render_image_caption( $id ) {
        $settings = $this->get_settings_for_display();
        
        if ( $settings['caption'] == 'hide' ) {
			return '';
		}
        
        $caption_type = $this->get_settings( 'caption_type' );
        
        $caption = Module::get_image_caption( $id, $caption_type );
        
        if ( $caption == '' ) {
			return '';
		}
        
        ob_start();
        ?>
        <div class="pp-gallery-image-caption">
            <?php echo $caption; ?>
        </div>
        <?php
        $html = ob_get_contents();
		ob_end_clean();
        return $html;
    }
    
    protected function render_link_icon() {
        $settings = $this->get_settings_for_display();
        
        if ( $settings['link_icon'] == '' ) {
			return '';
		}
        
        ob_start();
        ?>
        <div class="pp-gallery-image-icon-wrap">
            <span class="pp-gallery-image-icon <?php echo $settings['link_icon']; ?>">
            </span>
        </div>
        <?php
        $html = ob_get_contents();
		ob_end_clean();
        return $html;
    }
    
    protected function render_image_overlay() {
        $this->add_render_attribute( 'overlay', 'class', [
            'pp-image-overlay',
            'pp-gallery-image-overlay',
		] );
		
        return '<div ' . $this->get_render_attribute_string( 'overlay' ) . '></div>';
	}
	
	protected function get_filter_ids( $items = array(), $get_labels = false ) {
		$ids = array();
		$labels = array();

		if ( ! count( $items ) ) {
			return $ids;
		}

		foreach ( $items as $index => $item ) {
			$image_group = $item['image_group'];
			$filter_ids = array();
			$filter_label = '';

			foreach ( $image_group as $group ) {
				$ids[] = $group['id'];
				$filter_ids[] = $group['id'];
				$filter_label = 'pp-group-' . ( $index + 1 );
			}

			$labels[ $filter_label ] = $filter_ids;
		}

		if ( ! count( $ids ) ) {
			return $ids;
		}

		$unique_ids = array_unique( $ids );

		if ( $get_labels ) {
			$filter_labels = array();

			foreach ( $unique_ids as $unique_id ) {
				if ( empty( $unique_id ) ) {
					continue;
				}

				foreach ( $labels as $key => $filter_ids ) {
					if ( in_array( $unique_id, $filter_ids ) ) {
						if ( isset( $filter_labels[ $unique_id ] ) ) {
							$filter_labels[ $unique_id ] = $filter_labels[ $unique_id ]  . ' ' . str_replace( " ", "-", strtolower( $key ) );
						}
						else {
							$filter_labels[ $unique_id ] = str_replace( " ", "-", strtolower( $key ) );
						}
					}
				}
			}

			return $filter_labels;
		}

		return $unique_ids;
	}

	protected function get_wordpress_photos() {
		$settings 	= $this->get_settings_for_display();
		$image_size	= $settings['image_size'];
		$photos     = array();
		$ids		= array();

		if ( ! count( $settings['gallery_images'] ) ) {
			return $photos;
		}

		$filter_ids = $this->get_filter_ids( $settings['gallery_images'] );

		foreach ( $filter_ids as $id ) {
			if ( empty( $id ) ) {
				continue;
			}

			$photo = $this->get_attachment_data( $id );

			if ( ! $photo ) {
				continue;
			}

			// Only use photos who have the sizes object.
			if ( isset( $photo->sizes ) ) {
				$data = new \stdClass();

				// Photo data object
				$data->id 			= $id;
				$data->alt		 	= $photo->alt;
				$data->caption 		= $photo->caption;
				$data->description 	= $photo->description;
				$data->title 		= $photo->title;

				// Collage photo src
				if ( $settings['layout'] == 'masonry' ) {
					if ( $image_size == 'thumbnail' && isset( $photo->sizes->thumbnail ) ) {
						$data->src = $photo->sizes->thumbnail->url;
					}
					elseif ( $image_size == 'medium' && isset( $photo->sizes->medium ) ) {
						$data->src = $photo->sizes->medium->url;
					}
					else {
						$data->src = $photo->sizes->full->url;
					}
				}
				// Grid photo src
				else {
					if ( $image_size == 'thumbnail' && isset( $photo->sizes->thumbnail ) ) {
						$data->src = $photo->sizes->thumbnail->url;
					}
					elseif ( $image_size == 'medium' && isset( $photo->sizes->medium ) ) {
						$data->src = $photo->sizes->medium->url;
					}
					else {
						$data->src = $photo->sizes->full->url;
					}
				}

				// Photo Link
				if ( isset( $photo->sizes->large ) ) {
					$data->link = $photo->sizes->large->url;
				}
				else {
					$data->link = $photo->sizes->full->url;
				}

				$photos[$id] = $data;
			}
		}

		return $photos;
	}

	protected function get_photos() {
		$photos 	= $this->get_wordpress_photos();
		$settings 	= $this->get_settings_for_display();
		$order		= $settings['ordering'];
		$ordered	= array();

		if ( is_array( $photos ) && 'random' == $order ) {
			$keys = array_keys( $photos );
			shuffle( $keys );

			foreach ( $keys as $key ) {
				$ordered[ $key ] = $photos[ $key ];
			}
		} else {
			$ordered = $photos;
		}

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return $ordered;
		}

		if ( $settings['pagination'] == 'yes' ) {
			$per_page = $settings['images_per_page'];
			
			if ( empty( $per_page ) ) {
				return $ordered;
			}

			if ( $per_page > count( $ordered ) ) {
				return $ordered;
			}

			$count = 0;
			$current_photos = array();

			foreach ( $ordered as $photo_id => $photo ) {
				if ( $count == $per_page ) {
					break;
				} else {
					$current_photos[ $photo_id ] = $photo;
					$count++;
				}
			}

			return $current_photos;
		}

		return $ordered;
	}

	public function ajax_get_images() {
		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
			return;
		}

		ob_start();
		$this->render_gallery_items();
		$html = ob_get_contents();
		ob_end_clean();
		return trim( $html );
	}

	protected function get_attachment_data( $id ) {
		$data = wp_prepare_attachment_for_js( $id );

		if ( gettype( $data ) == 'array' ) {
			return json_decode( json_encode( $data ) );
		}

		return $data;
	}

	public function ajax_get_gallery_images() {
		if ( ! isset( $_POST['pp_action'] ) || 'pp_gallery_get_images' != $_POST['pp_action'] ) {
			return;
		}

		// Tell WordPress this is an AJAX request.
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		$response = array(
			'error'	=> false,
			'data'	=> ''
		);

		$photos = $this->get_photos();

		echo json_encode( $photos ); die;
	}

	/**
	 * Render masonry script
	 *
	 * @access protected
	 */
	protected function render_editor_script() {

		?><script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
                $( '.pp-image-gallery' ).each( function() {
                    
                    var $node_id 	= '<?php echo $this->get_id(); ?>',
                        $scope 		= $( '[data-id="' + $node_id + '"]' ),
                        $gallery 	= $(this);
                    
                    if ( $gallery.closest( $scope ).length < 1 ) {
        				return;
        			}
                    
                    var $layout_mode = 'fitRows';

                    if ( $gallery.hasClass('pp-image-gallery-masonry') ) {
                        $layout_mode = 'masonry';
                    }

                    var $isotope_args = {
                        itemSelector:   '.pp-grid-item-wrap',
                        layoutMode		: $layout_mode,
                        percentPosition : true,
                    },
                        $isotope_gallery = {};

                    $gallery.imagesLoaded( function(e) {
                        $isotope_gallery = $gallery.isotope( $isotope_args );
                        
                        $gallery.find('.pp-grid-item-wrap').resize( function() {
							$gallery.isotope( 'layout' );
						});
                    });

                    $('.pp-gallery-filters').on( 'click', '.pp-gallery-filter', function() {
                        var filterValue = $(this).attr('data-filter');
                        $isotope_gallery.isotope({ filter: filterValue });
                    });
                });
			});
		</script>
		<?php
	}

    protected function _content_template() {
    }
}