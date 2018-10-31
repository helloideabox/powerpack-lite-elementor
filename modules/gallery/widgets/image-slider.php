<?php
namespace PowerpackElements\Modules\Gallery\Widgets;

use PowerpackElements\Base\Powerpack_Widget;
use PowerpackElements\Modules\Gallery\Module;

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
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Image Slider Widget
 */
class Image_Slider extends Powerpack_Widget {
    
    /**
	 * Retrieve image slider widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-image-slider';
    }

    /**
	 * Retrieve image slider widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Image Slider', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the image slider widget belongs to.
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
	 * Retrieve image slider widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-gallery-slider power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the image slider widget depended on.
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
	 * Register image slider widget controls.
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
        
        $this->add_control(
            'gallery_images',
            [
                'label'                 => __( 'Add Images', 'power-pack' ),
                'type'                  => Controls_Manager::GALLERY,
                'dynamic'               => [
                    'active' => true
                ],
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
				'separator'             => 'before',
				'frontend_available'    => true,
			]
		);

		$this->add_control(
			'skin',
			[
				'label'                 => __( 'Layout', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'slideshow',
				'options'               => [
					'slideshow'    => __( 'Slideshow', 'power-pack' ),
					'carousel'     => __( 'Carousel', 'power-pack' ),
				],
				'prefix_class'          => 'pp-image-slider-',
				'render_type'           => 'template',
				'frontend_available'    => true,
			]
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slides_per_view',
			[
				'type'                  => Controls_Manager::SELECT,
				'label'                 => __( 'Slides Per View', 'power-pack' ),
				'options'               => $slides_per_view,
				'default'               => '3',
				'tablet_default'        => '2',
				'mobile_default'        => '2',
				'condition'             => [
					'effect'   => 'slide',
					'skin!'    => 'slideshow',
				],
				'frontend_available'    => true,
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'type'                  => Controls_Manager::SELECT,
				'label'                 => __( 'Slides to Scroll', 'power-pack' ),
				'description'           => __( 'Set how many slides are scrolled per swipe.', 'power-pack' ),
				'options'               => $slides_per_view,
				'default'               => '1',
				'tablet_default'        => '1',
				'mobile_default'        => '1',
				'condition'             => [
					'effect'   => 'slide',
					'skin!'    => 'slideshow',
				],
				'frontend_available'    => true,
			]
		);

        $this->end_controls_section();

        /**
         * Content Tab: Thumbnails
         */
        $this->start_controls_section(
            'section_thumbnails_settings',
            [
                'label'                 => __( 'Thumbnails', 'power-pack' ),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'                  => 'thumbnail',
                'label'                 => __( 'Image Size', 'power-pack' ),
                'default'               => 'thumbnail',
                'exclude'               => [ 'custom' ],
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label'                 => __( 'Columns', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => '3',
                'tablet_default'        => '6',
                'mobile_default'        => '4',
                'options'               => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                ],
                'prefix_class'          => 'elementor-grid%s-',
                'frontend_available'    => true,
                'condition'             => [
					'skin'     => 'slideshow',
				],
            ]
        );

		$this->add_control(
			'thumbnails_caption',
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
            'carousel_link_to',
            [
                'label'                 => __( 'Link to', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'none',
                'options'               => [
                    'none' 		=> __( 'None', 'power-pack' ),
                    'file' 		=> __( 'Media File', 'power-pack' ),
                    'custom' 	=> __( 'Custom URL', 'power-pack' ),
                ],
                'condition'             => [
					'skin'      => 'carousel',
                ],
            ]
        );

        $this->add_control(
            'carousel_link',
            [
                'label'                 => __( 'Link', 'power-pack' ),
                'show_label'            => false,
                'type'                  => Controls_Manager::URL,
                'placeholder'           => __( 'http://your-link.com', 'power-pack' ),
                'condition'             => [
					'skin'              => 'carousel',
                    'carousel_link_to'  => 'custom',
                ],
            ]
        );

        $this->add_control(
            'carousel_open_lightbox',
            [
                'label'                 => __( 'Lightbox', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'default',
                'options'               => [
                    'default' 	=> __( 'Default', 'power-pack' ),
                    'yes' 		=> __( 'Yes', 'power-pack' ),
                    'no' 		=> __( 'No', 'power-pack' ),
                ],
                'condition'             => [
					'skin'              => 'carousel',
                    'carousel_link_to'  => 'file',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Content Tab: Feature Image
         */
        $this->start_controls_section(
            'section_feature_image',
            [
                'label'                 => __( 'Feature Image', 'power-pack' ),
                'condition'             => [
					'skin'     => 'slideshow',
				],
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
                'condition'             => [
					'skin'     => 'slideshow',
				],
            ]
        );

		$this->add_control(
			'feature_image_caption',
			[
				'type'                  => Controls_Manager::SELECT,
				'label'                 => __( 'Caption', 'power-pack' ),
				'default'               => '',
				'options'               => [
					''         => __( 'None', 'power-pack' ),
					'caption'  => __( 'Caption', 'power-pack' ),
					'title'    => __( 'Title', 'power-pack' ),
				],
                'condition'             => [
					'skin'     => 'slideshow',
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
                'condition'             => [
					'skin'      => 'slideshow',
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
					'skin'      => 'slideshow',
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
                    'default' 	=> __( 'Default', 'power-pack' ),
                    'yes' 		=> __( 'Yes', 'power-pack' ),
                    'no' 		=> __( 'No', 'power-pack' ),
                ],
                'condition'             => [
					'skin'      => 'slideshow',
                    'link_to'   => 'file',
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
                'default'               => 'yes',
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
                'default'               => 'no',
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
                'default'               => 'yes',
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

        $this->add_control(
            'direction',
            [
                'label'                 => __( 'Direction', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
                'toggle'                => false,
                'options'               => [
                    'left' 	=> [
                        'title' 	=> __( 'Left', 'power-pack' ),
                        'icon' 		=> 'eicon-h-align-left',
                    ],
                    'right' 		=> [
                        'title' 	=> __( 'Right', 'power-pack' ),
                        'icon' 		=> 'eicon-h-align-right',
                    ],
                ],
                'default'               => 'left',
                'frontend_available'    => true,
                'condition'             => [
                    'effect!'       => 'fade',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Feature Image
         */
        $this->start_controls_section(
            'section_feature_image_style',
            [
                'label'                 => __( 'Feature Image', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'skin'     => 'slideshow',
                ],
            ]
        );
        
        $this->add_control(
			'feature_image_align',
			[
                'label'                 => __( 'Align', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
                'toggle'                => false,
                'default'               => 'left',
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
                'prefix_class'          => 'pp-image-slider-align-',
                'frontend_available'    => true,
                'condition'             => [
					'skin'     => 'slideshow',
				],
			]
		);
        
        $this->add_control(
            'feature_image_stack',
            [
                'label'                 => __( 'Stack On', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'tablet',
                'options'               => [
                    'tablet' 	=> __( 'Tablet', 'power-pack' ),
                    'mobile' 	=> __( 'Mobile', 'power-pack' ),
                ],
                'prefix_class'          => 'pp-image-slider-stack-',
                'condition'             => [
					'skin'                 => 'slideshow',
					'feature_image_align!' => 'top',
				],
            ]
        );

        $this->add_responsive_control(
            'feature_image_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'size_units'            => [ '%' ],
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
                    '{{WRAPPER}}.pp-image-slider-align-left .pp-image-slider-wrap' => 'width: {{SIZE}}%',
                    '{{WRAPPER}}.pp-image-slider-align-right .pp-image-slider-wrap' => 'width: {{SIZE}}%',
                    '{{WRAPPER}}.pp-image-slider-align-right .pp-image-slider-thumb-pagination' => 'width: calc(100% - {{SIZE}}%)',
                    '{{WRAPPER}}.pp-image-slider-align-left .pp-image-slider-thumb-pagination' => 'width: calc(100% - {{SIZE}}%)',
                ],
                'condition'             => [
                    'skin'     => 'slideshow',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'feature_image_spacing',
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
                    '{{WRAPPER}}.pp-image-slider-align-left .pp-image-slider-container,
                    {{WRAPPER}}.pp-image-slider-align-right .pp-image-slider-container' => 'margin-left: -{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.pp-image-slider-align-left .pp-image-slider-container > *,
                    {{WRAPPER}}.pp-image-slider-align-right .pp-image-slider-container > *' => 'padding-left: {{SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}}.pp-image-slider-stack-tablet .pp-image-slider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}}.pp-image-slider-stack-mobile .pp-image-slider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
                    'skin'     => 'slideshow',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'feature_image_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-image-slider',
				'separator'             => 'before',
                'condition'             => [
                    'skin'     => 'slideshow',
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'feature_image_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-image-slider',
				'separator'             => 'before',
                'condition'             => [
                    'skin'     => 'slideshow',
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'                  => 'feature_image_css_filters',
				'selector'              => '{{WRAPPER}} .pp-image-slider img',
                'condition'             => [
                    'skin'     => 'slideshow',
                ],
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Feature Image Captions
         */
        $this->start_controls_section(
            'section_feature_image_captions_style',
            [
                'label'                 => __( 'Feature Image Captions', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_image_captions_vertical_align',
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
					'{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-content' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'top'      => 'flex-start',
					'bottom'   => 'flex-end',
					'middle'   => 'center',
				],
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_image_captions_horizontal_align',
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
					'{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-content' => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'left'     => 'flex-start',
					'right'    => 'flex-end',
					'center'   => 'center',
					'justify'  => 'stretch',
				],
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'feature_image_captions_align',
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
                    '{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-caption' => 'text-align: {{VALUE}};',
                ],
                'condition'             => [
                    'skin'                                      => 'slideshow',
                    'feature_image_captions_horizontal_align'   => 'justify',
                    'feature_image_caption!'                    => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'feature_image_captions_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-caption',
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_feature_image_captions_style' );

        $this->start_controls_tab(
            'tab_feature_image_captions_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'feature_image_captions_background',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-caption',
                'exclude'               => [
                    'image',
                ],
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
			]
		);

        $this->add_control(
            'feature_image_captions_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-caption' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'feature_image_captions_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-caption',
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
			]
		);

		$this->add_control(
			'feature_image_captions_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
			]
		);

		$this->add_responsive_control(
			'feature_image_captions_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
			]
		);

		$this->add_responsive_control(
			'feature_image_captions_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
			]
		);

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'                  => 'feature_image_text_shadow',
                'selector' 	            => '{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-caption',
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_feature_image_captions_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'feature_image_captions_background_hover',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-image-slider-slide:hover .pp-image-slider-caption',
                'exclude'               => [
                    'image',
                ],
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
			]
		);

        $this->add_control(
            'feature_image_captions_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-slider-slide:hover .pp-image-slider-caption' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
            ]
        );

        $this->add_control(
            'feature_image_captions_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-slider-slide:hover .pp-image-slider-caption' => 'border-color: {{VALUE}}',
                ],
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'                  => 'feature_image_text_shadow_hover',
                'selector' 	            => '{{WRAPPER}} .pp-image-slider-slide:hover .pp-image-slider-caption',
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
			'feature_image_captions_blend_mode',
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
					'{{WRAPPER}} .pp-image-slider-slide .pp-image-slider-caption' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator'             => 'before',
                'condition'             => [
                    'skin'                      => 'slideshow',
                    'feature_image_caption!'    => '',
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

        $this->add_control(
            'thumbnails_alignment',
            [
                'label'                 => __( 'Alignment', 'power-pack' ),
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
                ],
                'default'               => 'left',
				'selectors' => [
					'{{WRAPPER}} .pp-image-slider-thumb-pagination' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'left'     => 'flex-start',
					'right'    => 'flex-end',
					'center'   => 'center',
				],
                'condition'             => [
                    'skin'     => 'slideshow',
                ],
            ]
        );
        
        $this->add_control(
            'thumbnail_images_heading',
            [
                'label'                 => __( 'Images', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );
        
        $this->add_responsive_control(
            'thumbnails_horizontal_spacing',
            [
                'label'                 => __( 'Column Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' 	=> [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'               => [
                    'size' 	=> '',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-slider-thumb-item-wrap,
                    {{WRAPPER}}.pp-image-slider-carousel .pp-image-slider-slide' => 'padding-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .pp-image-slider-thumb-pagination,
                    {{WRAPPER}}.pp-image-slider-carousel .slick-list'  => 'margin-left: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnails_vertical_spacing',
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
                    'size' 	=> '',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-slider-thumb-item-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition'             => [
					'skin'     => 'slideshow',
				],
            ]
        );

        $this->start_controls_tabs( 'tabs_thumbnails_style' );

        $this->start_controls_tab(
            'tab_thumbnails_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'thumbnails_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-image-slider-thumb-item',
			]
		);

		$this->add_control(
			'thumbnails_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-slider-thumb-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'thumbnails_scale',
            [
                'label'                 => __( 'Scale', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 1,
                        'max'   => 2,
                        'step'  => 0.01,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-slider-thumb-image img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'thumbnails_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-image-slider-thumb-item',
				'condition'             => [
					'skin'     => 'slideshow',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'                  => 'thumbnails_css_filters',
				'selector'              => '{{WRAPPER}} .pp-image-slider-thumb-image img',
			]
		);

        $this->add_control(
            'thumbnails_image_filter',
            [
                'label'                 => __( 'Image Filter', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'normal',
                'options'               => Module::get_image_filters(),
				'prefix_class'          => 'pp-ins-',
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_thumbnails_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'thumbnails_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-slider-thumb-item:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnails_scale_hover',
            [
                'label'                 => __( 'Scale', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 1,
                        'max'   => 2,
                        'step'  => 0.01,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-slider-thumb-item:hover .pp-image-slider-thumb-image img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'thumbnails_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-image-slider-thumb-item:hover',
				'condition'             => [
					'skin'     => 'slideshow',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'                  => 'thumbnails_css_filters_hover',
				'selector'              => '{{WRAPPER}} .pp-image-slider-thumb-item:hover .pp-image-slider-thumb-image img',
			]
		);

        $this->add_control(
            'thumbnails_image_filter_hover',
            [
                'label'                 => __( 'Image Filter', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'normal',
                'options'               => Module::get_image_filters(),
				'prefix_class'          => 'pp-ins-hover-',
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_thumbnails_active',
            [
                'label'                 => __( 'Active', 'power-pack' ),
            ]
        );

        $this->add_control(
            'thumbnails_border_color_active',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-active-slide .pp-image-slider-thumb-item' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnails_scale_active',
            [
                'label'                 => __( 'Scale', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 1,
                        'max'   => 2,
                        'step'  => 0.01,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-active-slide .pp-image-slider-thumb-image img' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'thumbnails_box_shadow_active',
				'selector'              => '{{WRAPPER}} .pp-active-slide .pp-image-slider-thumb-item',
				'condition'             => [
					'skin'     => 'slideshow',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'                  => 'thumbnails_css_filters_active',
				'selector'              => '{{WRAPPER}} .pp-active-slide .pp-image-slider-thumb-image img',
			]
		);
        
        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        $this->add_control(
            'thumbnail_overlay_heading',
            [
                'label'                 => __( 'Overlay', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );

        $this->start_controls_tabs( 'tabs_thumbnails_overlay_style' );

        $this->start_controls_tab(
            'tab_thumbnails_overlay_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'thumbnails_overlay_background',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-image-slider-thumb-overlay',
                'exclude'               => [
                    'image',
                ],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_thumbnails_overlay_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'thumbnails_overlay_background_hover',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-image-slider-thumb-item:hover .pp-image-slider-thumb-overlay',
                'exclude'               => [
                    'image',
                ],
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_thumbnails_overlay_active',
            [
                'label'                 => __( 'Active', 'power-pack' ),
                'condition'             => [
                    'skin'  => 'slideshow',
                ],
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'thumbnails_overlay_background_active',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-active-slide .pp-image-slider-thumb-overlay',
                'exclude'               => [
                    'image',
                ],
                'condition'             => [
                    'skin'  => 'slideshow',
                ],
			]
		);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
			'feature_image_overlay_blend_mode',
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
					'{{WRAPPER}} .pp-image-slider-thumb-overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator'             => 'before',
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Thumbnails Captions
         */
        $this->start_controls_section(
            'section_thumbnails_captions_style',
            [
                'label'                 => __( 'Thumbnails Captions', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnails_captions_vertical_align',
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
					'{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-content' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'top'      => 'flex-start',
					'bottom'   => 'flex-end',
					'middle'   => 'center',
				],
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnails_captions_horizontal_align',
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
					'{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-content' => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'left'     => 'flex-start',
					'right'    => 'flex-end',
					'center'   => 'center',
					'justify'  => 'stretch',
				],
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'thumbnails_captions_align',
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
                    '{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-caption' => 'text-align: {{VALUE}};',
                ],
                'condition'             => [
                    'thumbnails_captions_horizontal_align'  => 'justify',
                    'thumbnails_caption!'                   => '',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'thumbnails_captions_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-caption',
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_thumbnails_captions_style' );

        $this->start_controls_tab(
            'tab_thumbnails_captions_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'thumbnails_captions_background',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-caption',
                'exclude'               => [
                    'image',
                ],
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
			]
		);

        $this->add_control(
            'thumbnails_captions_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-caption' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'thumbnails_captions_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-caption',
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
			]
		);

		$this->add_control(
			'thumbnails_captions_border_radius_normal',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
			]
		);

		$this->add_responsive_control(
			'thumbnails_captions_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
			]
		);

		$this->add_responsive_control(
			'thumbnails_captions_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
			]
		);

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'                  => 'thumbnails_text_shadow',
                'selector' 	            => '{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-caption',
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_thumbnails_captions_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'thumbnails_captions_background_hover',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-image-slider-thumb-item-wrap:hover .pp-image-slider-caption',
                'exclude'               => [
                    'image',
                ],
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
			]
		);

        $this->add_control(
            'thumbnails_captions_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-slider-thumb-item-wrap:hover .pp-image-slider-caption' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
            ]
        );

        $this->add_control(
            'thumbnails_captions_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-slider-thumb-item-wrap:hover .pp-image-slider-caption' => 'border-color: {{VALUE}}',
                ],
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'                  => 'thumbnails_text_shadow_hover',
                'selector' 	            => '{{WRAPPER}} .pp-image-slider-thumb-item-wrap:hover .pp-image-slider-caption',
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->add_control(
			'thumbnails_captions_blend_mode',
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
					'{{WRAPPER}} .pp-image-slider-thumb-item-wrap .pp-image-slider-caption' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator'             => 'before',
                'condition'             => [
                    'thumbnails_caption!'   => '',
                ],
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
				'prefix_class'          => 'pp-image-slider-dots-',
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
                    '{{WRAPPER}} .pp-image-slider .slick-dots li button' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .pp-image-slider .slick-dots li' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
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
                    '{{WRAPPER}} .pp-image-slider .slick-dots li' => 'background: {{VALUE}};',
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
                    '{{WRAPPER}} .pp-image-slider .slick-dots li.slick-active' => 'background: {{VALUE}};',
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
				'selector'              => '{{WRAPPER}} .pp-image-slider .slick-dots li',
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
					'{{WRAPPER}} .pp-image-slider .slick-dots li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .pp-image-slider .slick-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .pp-image-slider .slick-dots li:hover' => 'background: {{VALUE}};',
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
                    '{{WRAPPER}} .pp-image-slider .slick-dots li:hover' => 'border-color: {{VALUE}};',
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

        $this->add_render_attribute( 'slider-wrap', 'class', 'pp-image-slider-wrap' );
        
        $this->add_render_attribute( 'slider', 'class', 'pp-image-slider' );
        $this->add_render_attribute( 'slider', 'id', 'pp-image-slider-'.esc_attr($this->get_id()) );
        
        if ( $settings['direction'] == 'right' ) {
            $this->add_render_attribute( 'slider', 'dir', 'rtl' );
        }
        ?>
        <div class="pp-image-slider-container">
            <div <?php echo $this->get_render_attribute_string( 'slider-wrap' ); ?>>
                <div <?php echo $this->get_render_attribute_string( 'slider' ); ?>>
                    <?php
                        if ( $settings['skin'] == 'slideshow' ) {
                            $this->render_slideshow();
                        } else {
                            $this->render_carousel();
                        }
                    ?>
                </div>
            </div>
            <?php
                if ( $settings['skin'] == 'slideshow' ) {
                    // Slideshow Thumbnails
                    $this->render_thumbnails();
                }
            ?>
        </div>
        <?php
    }
    
    protected function render_slideshow() {
        $settings = $this->get_settings_for_display();
		$gallery = $settings['gallery_images'];
        
        foreach ( $gallery as $index => $item ) {
            ?>
            <div class="pp-image-slider-slide">
                <?php
                    $image_url = Group_Control_Image_Size::get_attachment_image_src( $item['id'], 'image', $settings );
                    $image_html = '<div class="pp-image-slider-image-wrap">';
                    $image_html .= '<img class="pp-image-slider-image" src="' . esc_url( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $item ) ) . '" />';
                    $image_html .= '</div>';
            
                    if ( $settings['feature_image_caption'] != '' ) {
                        $image_html .= '<div class="pp-image-slider-content">';
                            $image_html .= $this->render_image_caption( $item['id'], $settings['feature_image_caption'] );
                        $image_html .= '</div>';
                    }
            
                    if ( $settings['link_to'] != 'none' ) {
				
                        $link_key = $this->get_repeater_setting_key( 'link', 'gallery_images', $index );
                        
                        if ( $settings['link_to'] == 'file' ) {
                            $link = wp_get_attachment_url( $item['id'] );
                            
                            $this->add_render_attribute( $link_key, [
                                'data-elementor-open-lightbox' 		=> $settings['open_lightbox'],
                                'data-elementor-lightbox-slideshow' => $this->get_id(),
                                'data-elementor-lightbox-index' 	=> $index,
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
                ?>
            </div>
            <?php
        }
    }
    
    protected function render_thumbnails() {
        $settings = $this->get_settings_for_display();
		$gallery = $settings['gallery_images'];
        ?>
        <div class="pp-image-slider-thumb-pagination pp-elementor-grid <?php echo 'pp-' . $settings['thumbnails_image_filter']; ?>">
            <?php
                foreach ( $gallery as $index => $item ) {
                    $image_url = Group_Control_Image_Size::get_attachment_image_src( $item['id'], 'thumbnail', $settings );
                    ?>
                    <div class="pp-image-slider-thumb-item-wrap pp-grid-item-wrap">
                        <div class="pp-grid-item pp-image-slider-thumb-item pp-ins-filter-hover">
                            <div class="pp-image-slider-thumb-image pp-ins-filter-target">
                                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( Control_Media::get_image_alt( $item ) ); ?>" />
                            </div>
                            <?php echo $this->render_image_overlay(); ?>
                            <?php if ( $settings['thumbnails_caption'] != '' ) { ?>
                                <div class="pp-image-slider-content">
                                    <?php
                                        echo $this->render_image_caption( $item['id'], $settings['thumbnails_caption'] );
                                    ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
        <?php
    }
    
    protected function render_carousel() {
        $settings = $this->get_settings_for_display();
		$gallery = $settings['gallery_images'];
        
        foreach ( $gallery as $index => $item ) {
            $image_url = Group_Control_Image_Size::get_attachment_image_src( $item['id'], 'thumbnail', $settings );
            ?>
            <div class="pp-image-slider-thumb-item-wrap">
                <div class="pp-image-slider-thumb-item pp-ins-filter-hover">
                    <?php
                        $image_html = '<div class="pp-image-slider-thumb-image pp-ins-filter-target">';
                        $image_html .= '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $item ) ) . '" />';
                        $image_html .= '</div>';
            
                        $image_html .= $this->render_image_overlay();

                        if ( $settings['thumbnails_caption'] != '' ) {
                            $image_html .= '<div class="pp-image-slider-content">';
                            $image_html .= $this->render_image_caption( $item['id'], $settings['thumbnails_caption'] );
                            $image_html .= '</div>';
                        }

                        if ( $settings['carousel_link_to'] != 'none' ) {

                            $link_key = $this->get_repeater_setting_key( 'carousel_link', 'gallery_images', $index );

                            if ( $settings['carousel_link_to'] == 'file' ) {
                                $link = wp_get_attachment_url( $item['id'] );

                                $this->add_render_attribute( $link_key, [
                                    'data-elementor-open-lightbox' 		=> $settings['carousel_open_lightbox'],
                                    'data-elementor-lightbox-slideshow' => $this->get_id(),
                                    'data-elementor-lightbox-index' 	=> $index,
                                ] );
                            } elseif ( $settings['carousel_link_to'] == 'custom' && $settings['carousel_link']['url'] != '' ) {
                                $link = $settings['carousel_link']['url'];

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
                    ?>
                </div>
            </div>
            <?php
        }
    }
    
    protected function render_image_overlay() {
        return '<div class="pp-image-slider-thumb-overlay"></div>';
    }
    
    protected function render_image_caption( $id, $caption_type = 'caption' ) {
        $settings = $this->get_settings_for_display();
        
        $caption = Module::get_image_caption( $id, $caption_type );
        
        if ( $caption == '' ) {
			return '';
		}
        
        ob_start();
        ?>
        <div class="pp-image-slider-caption">
            <?php echo $caption; ?>
        </div>
        <?php
        $html = ob_get_contents();
		ob_end_clean();
        return $html;
    }

    protected function _content_template() {
    }
}