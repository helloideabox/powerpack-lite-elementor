<?php
namespace PowerpackElementsLite\Modules\RandomImage\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Modules\RandomImage\Module;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Random Image Widget
 */
class Random_Image extends Powerpack_Widget {

	/**
	 * Retrieve Random Image widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Random_Image' );
	}

	/**
	 * Retrieve Random Image widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Random_Image' );
	}

	/**
	 * Retrieve the list of categories the Random Image widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return parent::get_widget_categories( 'Random_Image' );
	}

	/**
	 * Retrieve Random Image widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Random_Image' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Random_Image' );
	}

	protected function is_dynamic_content(): bool {
		return true;
	}

	/**
	 * Retrieve the list of styles the offcanvas content widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		return [
			'widget-pp-random-image'
		];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! PP_Helper::is_feature_active( 'e_optimized_markup' );
	}

	/**
	 * Image filters.
	 *
	 * @access public
	 * @param boolean $inherit if inherit option required.
	 * @return array Filters.
	 */
	protected function image_filters( $inherit = false ) {

		$inherit_opt = array();

		if ( $inherit ) {
			$inherit_opt = array(
				'' => esc_html__( 'Inherit', 'powerpack-lite-for-elementor' ),
			);
		}

		$pp_image_filters = array(
			'normal'            => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
			'filter-1977'       => esc_html__( '1977', 'powerpack-lite-for-elementor' ),
			'filter-aden'       => esc_html__( 'Aden', 'powerpack-lite-for-elementor' ),
			'filter-amaro'      => esc_html__( 'Amaro', 'powerpack-lite-for-elementor' ),
			'filter-ashby'      => esc_html__( 'Ashby', 'powerpack-lite-for-elementor' ),
			'filter-brannan'    => esc_html__( 'Brannan', 'powerpack-lite-for-elementor' ),
			'filter-brooklyn'   => esc_html__( 'Brooklyn', 'powerpack-lite-for-elementor' ),
			'filter-charmes'    => esc_html__( 'Charmes', 'powerpack-lite-for-elementor' ),
			'filter-clarendon'  => esc_html__( 'Clarendon', 'powerpack-lite-for-elementor' ),
			'filter-crema'      => esc_html__( 'Crema', 'powerpack-lite-for-elementor' ),
			'filter-dogpatch'   => esc_html__( 'Dogpatch', 'powerpack-lite-for-elementor' ),
			'filter-earlybird'  => esc_html__( 'Earlybird', 'powerpack-lite-for-elementor' ),
			'filter-gingham'    => esc_html__( 'Gingham', 'powerpack-lite-for-elementor' ),
			'filter-ginza'      => esc_html__( 'Ginza', 'powerpack-lite-for-elementor' ),
			'filter-hefe'       => esc_html__( 'Hefe', 'powerpack-lite-for-elementor' ),
			'filter-helena'     => esc_html__( 'Helena', 'powerpack-lite-for-elementor' ),
			'filter-hudson'     => esc_html__( 'Hudson', 'powerpack-lite-for-elementor' ),
			'filter-inkwell'    => esc_html__( 'Inkwell', 'powerpack-lite-for-elementor' ),
			'filter-juno'       => esc_html__( 'Juno', 'powerpack-lite-for-elementor' ),
			'filter-kelvin'     => esc_html__( 'Kelvin', 'powerpack-lite-for-elementor' ),
			'filter-lark'       => esc_html__( 'Lark', 'powerpack-lite-for-elementor' ),
			'filter-lofi'       => esc_html__( 'Lofi', 'powerpack-lite-for-elementor' ),
			'filter-ludwig'     => esc_html__( 'Ludwig', 'powerpack-lite-for-elementor' ),
			'filter-maven'      => esc_html__( 'Maven', 'powerpack-lite-for-elementor' ),
			'filter-mayfair'    => esc_html__( 'Mayfair', 'powerpack-lite-for-elementor' ),
			'filter-moon'       => esc_html__( 'Moon', 'powerpack-lite-for-elementor' ),
		);

		return array_merge( $inherit_opt, $pp_image_filters );
	}

	/**
	 * Register Random Image widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {

		/* Content Tab */
		$this->register_content_gallery_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_image_controls();
		$this->register_style_caption_controls();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	CONTENT TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_content_gallery_controls() {
		/**
		 * Content Tab: Gallery
		 */
		$this->start_controls_section(
			'section_images',
			[
				'label' => esc_html__( 'Images', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'wp_gallery',
			[
				'label'     => esc_html__( 'Add Images', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::GALLERY,
				'dynamic'   => [
					'active' => true,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image', // Actually its `image_size`.
				'label'     => esc_html__( 'Image Size', 'powerpack-lite-for-elementor' ),
				'default'   => 'full',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'  => [
						'title'     => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'      => 'eicon-text-align-left',
					],
					'center'    => [
						'title'     => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'      => 'eicon-text-align-center',
					],
					'right'     => [
						'title'     => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'      => 'eicon-text-align-right',
					],
				],
				'selectors'     => [
					'{{WRAPPER}} .pp-random-image-wrap' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'caption',
			[
				'label'     => esc_html__( 'Caption', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''            => esc_html__( 'None', 'powerpack-lite-for-elementor' ),
					'title'       => esc_html__( 'Title', 'powerpack-lite-for-elementor' ),
					'caption'     => esc_html__( 'Caption', 'powerpack-lite-for-elementor' ),
					'description' => esc_html__( 'Description', 'powerpack-lite-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'caption_position',
			array(
				'label'     => esc_html__( 'Caption Position', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'below_image',
				'options'   => array(
					'over_image'  => esc_html__( 'Over Image', 'powerpack-lite-for-elementor' ),
					'below_image' => esc_html__( 'Below Image', 'powerpack-lite-for-elementor' ),
				),
				'condition' => array(
					'caption!' => '',
				),
			)
		);

		$this->add_control(
			'link_to',
			[
				'label'   => esc_html__( 'Link to', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'   => esc_html__( 'None', 'powerpack-lite-for-elementor' ),
					'file'   => esc_html__( 'Media File', 'powerpack-lite-for-elementor' ),
					'custom' => esc_html__( 'Custom URL', 'powerpack-lite-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'powerpack-lite-for-elementor' ),
				'condition'   => [
					'link_to' => 'custom',
				],
				'show_label'  => false,
			]
		);

		$this->add_control(
			'important_note',
			array(
				'label'           => '',
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'To add a different link to each image, add custom link in the media uploader.', 'powerpack-lite-for-elementor' ),
				'content_classes' => 'pp-editor-info',
				'condition'       => array(
					'link_to' => 'custom',
				),
			)
		);

		$this->add_control(
			'open_lightbox',
			[
				'label'     => esc_html__( 'Lightbox', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default' => esc_html__( 'Default', 'powerpack-lite-for-elementor' ),
					'yes'     => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
					'no'      => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				],
				'condition' => [
					'link_to' => 'file',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Random_Image' );

		if ( ! empty( $help_docs ) ) {

			/**
			 * Content Tab: Help Docs
			 *
			 * @since 2.3.0
			 * @access protected
			 */
			$this->start_controls_section(
				'section_help_docs',
				[
					'label' => esc_html__( 'Help Docs', 'powerpack-lite-for-elementor' ),
				]
			);

			$hd_counter = 1;
			foreach ( $help_docs as $hd_title => $hd_link ) {
				$this->add_control(
					'help_doc_' . $hd_counter,
					[
						'type'            => Controls_Manager::RAW_HTML,
						'raw'             => sprintf( '%1$s ' . $hd_title . ' %2$s', '<a href="' . $hd_link . '" target="_blank" rel="noopener">', '</a>' ),
						'content_classes' => 'pp-editor-doc-links',
					]
				);

				$hd_counter++;
			}

			$this->end_controls_section();
		}
	}

	/*-----------------------------------------------------------------------------------*/
	/*	STYLE TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_style_image_controls() {
		/**
		 * Style Tab: Image
		 */
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'      => esc_html__( 'Width', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'default'    => [
					'unit' => '%',
				],
				'range'      => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-random-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'space',
			[
				'label'      => esc_html__( 'Max Width', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'default'    => [
					'unit' => '%',
				],
				'range'      => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-random-image' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'      => esc_html__( 'Height', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'vh', 'custom' ],
				'default'    => [
					'unit' => 'px',
				],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-random-image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'object-fit',
			[
				'label'     => esc_html__( 'Object Fit', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => [
					'height[size]!' => '',
				],
				'options'   => [
					''        => esc_html__( 'Default', 'powerpack-lite-for-elementor' ),
					'fill'    => esc_html__( 'Fill', 'powerpack-lite-for-elementor' ),
					'cover'   => esc_html__( 'Cover', 'powerpack-lite-for-elementor' ),
					'contain' => esc_html__( 'Contain', 'powerpack-lite-for-elementor' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-random-image' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'separator_panel_style',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => esc_html__( 'Opacity', 'powerpack-lite-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .pp-random-image' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .pp-random-image',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label' => esc_html__( 'Opacity', 'powerpack-lite-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .pp-random-image-wrap:hover .pp-random-image' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters_hover',
				'selector' => '{{WRAPPER}} .pp-random-image-wrap:hover .pp-random-image',
			]
		);

		$this->add_control(
			'background_hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'powerpack-lite-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 3,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .pp-random-image' => 'transition-duration: {{SIZE}}s',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'powerpack-lite-for-elementor' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'image_border',
				'selector'  => '{{WRAPPER}} .pp-random-image',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-random-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'image_box_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .pp-random-image',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_caption_controls() {
		/**
		 * Style Tab: Caption
		 */
		$this->start_controls_section(
			'section_caption_style',
			[
				'label'                 => esc_html__( 'Caption', 'powerpack-lite-for-elementor' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'caption_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'selector'              => '{{WRAPPER}} .pp-random-image-caption',
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_vertical_align',
			[
				'label'                 => esc_html__( 'Vertical Align', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'toggle'                => false,
				'default'               => 'bottom',
				'options'               => [
					'top'          => [
						'title'    => esc_html__( 'Top', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => esc_html__( 'Bottom', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-media-content'   => 'justify-content: {{VALUE}};',
				],
				'condition'             => [
					'caption!'         => '',
					'caption_position' => 'over_image',
				],
			]
		);

		$this->add_control(
			'caption_horizontal_align',
			[
				'label'                 => esc_html__( 'Horizontal Align', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'toggle'                => false,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'           => [
						'title' => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'            => [
						'title' => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
					'justify'          => [
						'title' => esc_html__( 'Justify', 'powerpack-lite-for-elementor' ),
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
					'{{WRAPPER}} .pp-media-content' => 'align-items: {{VALUE}};',
				],
				'condition'             => [
					'caption!'         => '',
					'caption_position' => 'over_image',
				],
			]
		);

		$this->add_control(
			'caption_text_align',
			[
				'label'                 => esc_html__( 'Text Align', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'    => [
						'title' => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'     => [
						'title' => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'               => 'center',
				'selectors'             => [
					'{{WRAPPER}} .pp-media-content' => 'text-align: {{VALUE}};',
				],
				'condition'             => [
					'caption!'                 => '',
					'caption_horizontal_align' => 'justify',
				],
				'conditions'        => [
					'relation' => 'and',
					'terms' => [
						[
							'name' => 'caption',
							'operator' => '!=',
							'value' => '',
						],
						[
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name' => 'caption_position',
											'operator' => '==',
											'value' => 'over_image',
										],
										[
											'name' => 'caption_horizontal_align',
											'operator' => '==',
											'value' => 'justify',
										],
									],
								],
								[
									'name' => 'caption_position',
									'operator' => '==',
									'value' => 'below_image',
								],
							],
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'caption_margin',
			[
				'label'                 => esc_html__( 'Margin', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-random-image-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_responsive_control(
			'caption_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-random-image-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_hover_effect',
			[
				'label'                 => esc_html__( 'Hover Effect', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => '',
				'options'               => [
					''                  => esc_html__( 'None', 'powerpack-lite-for-elementor' ),
					'fade-in'           => esc_html__( 'Fade In', 'powerpack-lite-for-elementor' ),
					'fade-out'          => esc_html__( 'Fade Out', 'powerpack-lite-for-elementor' ),
					'fade-from-top'     => esc_html__( 'Fade From Top', 'powerpack-lite-for-elementor' ),
					'fade-from-bottom'  => esc_html__( 'Fade From Bottom', 'powerpack-lite-for-elementor' ),
					'fade-from-left'    => esc_html__( 'Fade From Left', 'powerpack-lite-for-elementor' ),
					'fade-from-right'   => esc_html__( 'Fade From Right', 'powerpack-lite-for-elementor' ),
					'slide-from-top'    => esc_html__( 'Slide From Top', 'powerpack-lite-for-elementor' ),
					'slide-from-bottom' => esc_html__( 'Slide From Bottom', 'powerpack-lite-for-elementor' ),
					'slide-from-left'   => esc_html__( 'Slide From Left', 'powerpack-lite-for-elementor' ),
					'slide-from-right'  => esc_html__( 'Slide From Right', 'powerpack-lite-for-elementor' ),
					'fade-to-top'       => esc_html__( 'Fade To Top', 'powerpack-lite-for-elementor' ),
					'fade-to-bottom'    => esc_html__( 'Fade To Bottom', 'powerpack-lite-for-elementor' ),
					'fade-to-left'      => esc_html__( 'Fade To Left', 'powerpack-lite-for-elementor' ),
					'fade-to-right'     => esc_html__( 'Fade To Right', 'powerpack-lite-for-elementor' ),
					'slide-to-top'      => esc_html__( 'Slide To Top', 'powerpack-lite-for-elementor' ),
					'slide-to-bottom'   => esc_html__( 'Slide To Bottom', 'powerpack-lite-for-elementor' ),
					'slide-to-left'     => esc_html__( 'Slide To Left', 'powerpack-lite-for-elementor' ),
					'slide-to-right'    => esc_html__( 'Slide To Right', 'powerpack-lite-for-elementor' ),
				],
				'prefix_class'          => 'pp-caption-hover-effect-',
				'condition'             => [
					'caption!'         => '',
					'caption_position' => 'over_image',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_caption_style' );

		$this->start_controls_tab(
			'tab_caption_normal',
			[
				'label'                 => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-random-image-caption' => 'color: {{VALUE}};',
				],
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-random-image-caption' => 'background-color: {{VALUE}};',
				],
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'caption_border',
				'label'                 => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-random-image-caption',
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-random-image-caption' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'                  => 'caption_text_shadow',
				'label'                 => esc_html__( 'Text Shadow', 'powerpack-lite-for-elementor' ),
				'selector'              => '{{WRAPPER}} .pp-random-image-caption',
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_opacity_normal',
			[
				'label'                 => esc_html__( 'Opacity', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 1,
						'step'  => 0.01,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-random-image-caption' => 'opacity: {{SIZE}};',
				],
				'condition'             => [
					'caption!'         => '',
					'caption_position' => 'over_image',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_caption_hover',
			[
				'label'                 => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_color_hover',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-random-image-wrap:hover .pp-random-image-caption' => 'color: {{VALUE}};',
				],
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_bg_color_hover',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-random-image-wrap:hover .pp-random-image-caption' => 'background-color: {{VALUE}};',
				],
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_border_color_hover',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-random-image-wrap:hover .pp-random-image-caption' => 'border-color: {{VALUE}};',
				],
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'                  => 'caption_text_shadow_hover',
				'label'                 => esc_html__( 'Text Shadow', 'powerpack-lite-for-elementor' ),
				'selector'              => '{{WRAPPER}} .pp-random-image-wrap:hover .pp-random-image-caption',
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_opacity_hover',
			[
				'label'                 => esc_html__( 'Opacity', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 1,
						'step'  => 0.01,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-random-image-wrap:hover .pp-random-image-caption' => 'opacity: {{SIZE}};',
				],
				'condition'             => [
					'caption!'         => '',
					'caption_position' => 'over_image',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! $settings['wp_gallery'] ) {
			$placeholder = sprintf(
				/* translators: %s: Widget title. */
				esc_html__(
					'Click here to edit the "%1$s" settings and choose some images.',
					'powerpack-lite-for-elementor'
				),
				esc_html( $this->get_title() )
			);

			echo esc_attr( $this->render_editor_placeholder(
				array(
					'body'  => $placeholder,
				)
			) );
			return;
		}

		$count       = count( $settings['wp_gallery'] );
		$index       = ( $count > 1 ) ? wp_rand( 0, $count - 1 ) : 0;
		$image_id    = apply_filters( 'wpml_object_id', $settings['wp_gallery'][ $index ]['id'], 'attachment', true );
		$has_caption = '' !== $settings['caption'];
		$link        = '';
		$attachment  = get_post( $image_id );

		$image = array(
			'id'  => $image_id,
			'url' => Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image', $settings ),
		);

		$this->add_render_attribute( [
			'wrapper' => [
				'class' => 'pp-random-image-wrap',
			],
			'figure' => [
				'class' => [
					'pp-image',
					'wp-caption',
					'pp-random-image-figure',
				],
			],
			'image' => [
				'class' => 'elementor-image pp-random-image',
				'src' => Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image', $settings ),
				'alt' => esc_attr( Control_Media::get_image_alt( $image ) ),
			],
			'caption' => [
				'class' => [
					'widget-image-caption',
					'wp-caption-text',
					'pp-random-image-caption',
					'pp-gallery-image-caption',
				],
			],
		] );

		if ( '' !== $settings['hover_animation'] ) {
			$this->add_render_attribute( 'image', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		if ( 'none' !== $settings['link_to'] ) {
			if ( 'file' === $settings['link_to'] ) {
				$link = $settings['wp_gallery'][ $index ];
				$this->add_render_attribute( 'link', [
					'class' => 'pp-random-image-link',
					'data-elementor-open-lightbox' => $settings['open_lightbox'],
				] );

				if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
					$this->add_render_attribute( 'link', [
						'class' => 'elementor-clickable',
					] );
				}

				$this->add_render_attribute( 'link', 'href', $link['url'] );
			} elseif ( 'custom' === $settings['link_to'] ) {
				$link        = $settings['link'];
				$link_custom = get_post_meta( $image_id, 'pp-custom-link', true );

				if ( '' !== $link_custom ) {
					$link['url'] = $link_custom;
				}

				$this->add_link_attributes( 'link', $link );
			}
		}
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'wrapper' ) ); ?>>
			<?php if ( $has_caption ) { ?>
			<figure <?php echo wp_kses_post( $this->get_render_attribute_string( 'figure' ) ); ?>>
			<?php } ?>

			<?php
			$image_html = '<img ' . $this->get_render_attribute_string( 'image' ) . '/>';

			if ( $link ) {
				if ( 'over_image' === $settings['caption_position'] ) {
					$image_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '></a>' . $image_html;
				} else {
					$image_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $image_html . '</a>';
				}
			}

			echo wp_kses_post( $image_html );
			?>
			<?php if ( $has_caption ) { ?>
				<?php if ( 'over_image' === $settings['caption_position'] ) { ?>
				<div class="pp-gallery-image-content pp-media-content">
				<?php } ?>
				<figcaption <?php echo wp_kses_post( $this->get_render_attribute_string( 'caption' ) ); ?>>
					<?php echo wp_kses_post( $this->render_image_caption( $attachment ) ); ?>
				</figcaption>
				<?php if ( 'over_image' === $settings['caption_position'] ) { ?>
				</div>
				<?php } ?>
			</figure>
			<?php } ?>
		</div>
		<?php
	}

	protected function render_image_caption( $image_id ) {
		$settings = $this->get_settings_for_display();

		if ( '' === $settings['caption'] ) {
			return '';
		}

		$caption_type = $settings['caption'];

		$caption = Module::get_image_caption( $image_id, $caption_type );

		if ( '' === $caption ) {
			return '';
		}

		ob_start();

		echo wp_kses_post( $caption );

		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
}
