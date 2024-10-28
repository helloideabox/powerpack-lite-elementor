<?php
namespace PowerpackElementsLite\Modules\RandomImage\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Modules\RandomImage\Module;
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
				'' => esc_html__( 'Inherit', 'powerpack' ),
			);
		}

		$pp_image_filters = array(
			'normal'            => esc_html__( 'Normal', 'powerpack' ),
			'filter-1977'       => esc_html__( '1977', 'powerpack' ),
			'filter-aden'       => esc_html__( 'Aden', 'powerpack' ),
			'filter-amaro'      => esc_html__( 'Amaro', 'powerpack' ),
			'filter-ashby'      => esc_html__( 'Ashby', 'powerpack' ),
			'filter-brannan'    => esc_html__( 'Brannan', 'powerpack' ),
			'filter-brooklyn'   => esc_html__( 'Brooklyn', 'powerpack' ),
			'filter-charmes'    => esc_html__( 'Charmes', 'powerpack' ),
			'filter-clarendon'  => esc_html__( 'Clarendon', 'powerpack' ),
			'filter-crema'      => esc_html__( 'Crema', 'powerpack' ),
			'filter-dogpatch'   => esc_html__( 'Dogpatch', 'powerpack' ),
			'filter-earlybird'  => esc_html__( 'Earlybird', 'powerpack' ),
			'filter-gingham'    => esc_html__( 'Gingham', 'powerpack' ),
			'filter-ginza'      => esc_html__( 'Ginza', 'powerpack' ),
			'filter-hefe'       => esc_html__( 'Hefe', 'powerpack' ),
			'filter-helena'     => esc_html__( 'Helena', 'powerpack' ),
			'filter-hudson'     => esc_html__( 'Hudson', 'powerpack' ),
			'filter-inkwell'    => esc_html__( 'Inkwell', 'powerpack' ),
			'filter-juno'       => esc_html__( 'Juno', 'powerpack' ),
			'filter-kelvin'     => esc_html__( 'Kelvin', 'powerpack' ),
			'filter-lark'       => esc_html__( 'Lark', 'powerpack' ),
			'filter-lofi'       => esc_html__( 'Lofi', 'powerpack' ),
			'filter-ludwig'     => esc_html__( 'Ludwig', 'powerpack' ),
			'filter-maven'      => esc_html__( 'Maven', 'powerpack' ),
			'filter-mayfair'    => esc_html__( 'Mayfair', 'powerpack' ),
			'filter-moon'       => esc_html__( 'Moon', 'powerpack' ),
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
				'label' => esc_html__( 'Images', 'powerpack' ),
			]
		);

		$this->add_control(
			'wp_gallery',
			[
				'label'     => esc_html__( 'Add Images', 'powerpack' ),
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
				'label'     => esc_html__( 'Image Size', 'powerpack' ),
				'default'   => 'full',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'  => [
						'title'     => esc_html__( 'Left', 'powerpack' ),
						'icon'      => 'eicon-text-align-left',
					],
					'center'    => [
						'title'     => esc_html__( 'Center', 'powerpack' ),
						'icon'      => 'eicon-text-align-center',
					],
					'right'     => [
						'title'     => esc_html__( 'Right', 'powerpack' ),
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
				'label'     => esc_html__( 'Caption', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''            => esc_html__( 'None', 'powerpack' ),
					'title'       => esc_html__( 'Title', 'powerpack' ),
					'caption'     => esc_html__( 'Caption', 'powerpack' ),
					'description' => esc_html__( 'Description', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'caption_position',
			array(
				'label'     => esc_html__( 'Caption Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'below_image',
				'options'   => array(
					'over_image'  => esc_html__( 'Over Image', 'powerpack' ),
					'below_image' => esc_html__( 'Below Image', 'powerpack' ),
				),
				'condition' => array(
					'caption!' => '',
				),
			)
		);

		$this->add_control(
			'link_to',
			[
				'label'   => esc_html__( 'Link to', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'   => esc_html__( 'None', 'powerpack' ),
					'file'   => esc_html__( 'Media File', 'powerpack' ),
					'custom' => esc_html__( 'Custom URL', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link', 'powerpack' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'powerpack' ),
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
				'raw'             => esc_html__( 'To add a different link to each image, add custom link in the media uploader.', 'powerpack' ),
				'content_classes' => 'pp-editor-info',
				'condition'       => array(
					'link_to' => 'custom',
				),
			)
		);

		$this->add_control(
			'open_lightbox',
			[
				'label'     => esc_html__( 'Lightbox', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default' => esc_html__( 'Default', 'powerpack' ),
					'yes'     => esc_html__( 'Yes', 'powerpack' ),
					'no'      => esc_html__( 'No', 'powerpack' ),
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
					'label' => esc_html__( 'Help Docs', 'powerpack' ),
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
				'label' => esc_html__( 'Image', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label'      => esc_html__( 'Width', 'powerpack' ),
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
				'label'      => esc_html__( 'Max Width', 'powerpack' ),
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
				'label'      => esc_html__( 'Height', 'powerpack' ),
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
				'label'     => esc_html__( 'Object Fit', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => [
					'height[size]!' => '',
				],
				'options'   => [
					''        => esc_html__( 'Default', 'powerpack' ),
					'fill'    => esc_html__( 'Fill', 'powerpack' ),
					'cover'   => esc_html__( 'Cover', 'powerpack' ),
					'contain' => esc_html__( 'Contain', 'powerpack' ),
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
				'label' => esc_html__( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'opacity',
			[
				'label' => esc_html__( 'Opacity', 'powerpack' ),
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
				'label' => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label' => esc_html__( 'Opacity', 'powerpack' ),
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
				'label' => esc_html__( 'Transition Duration', 'powerpack' ),
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
				'label' => esc_html__( 'Hover Animation', 'powerpack' ),
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
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
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
				'label'                 => esc_html__( 'Caption', 'powerpack' ),
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
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-random-image-caption',
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_vertical_align',
			[
				'label'                 => esc_html__( 'Vertical Align', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'toggle'                => false,
				'default'               => 'bottom',
				'options'               => [
					'top'          => [
						'title'    => esc_html__( 'Top', 'powerpack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => esc_html__( 'Center', 'powerpack' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => esc_html__( 'Bottom', 'powerpack' ),
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
				'label'                 => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'toggle'                => false,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'           => [
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'            => [
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					],
					'justify'          => [
						'title' => esc_html__( 'Justify', 'powerpack' ),
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
				'label'                 => esc_html__( 'Text Align', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'    => [
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'     => [
						'title' => esc_html__( 'Right', 'powerpack' ),
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
				'label'                 => esc_html__( 'Margin', 'powerpack' ),
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
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
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
				'label'                 => esc_html__( 'Hover Effect', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => '',
				'options'               => [
					''                  => esc_html__( 'None', 'powerpack' ),
					'fade-in'           => esc_html__( 'Fade In', 'powerpack' ),
					'fade-out'          => esc_html__( 'Fade Out', 'powerpack' ),
					'fade-from-top'     => esc_html__( 'Fade From Top', 'powerpack' ),
					'fade-from-bottom'  => esc_html__( 'Fade From Bottom', 'powerpack' ),
					'fade-from-left'    => esc_html__( 'Fade From Left', 'powerpack' ),
					'fade-from-right'   => esc_html__( 'Fade From Right', 'powerpack' ),
					'slide-from-top'    => esc_html__( 'Slide From Top', 'powerpack' ),
					'slide-from-bottom' => esc_html__( 'Slide From Bottom', 'powerpack' ),
					'slide-from-left'   => esc_html__( 'Slide From Left', 'powerpack' ),
					'slide-from-right'  => esc_html__( 'Slide From Right', 'powerpack' ),
					'fade-to-top'       => esc_html__( 'Fade To Top', 'powerpack' ),
					'fade-to-bottom'    => esc_html__( 'Fade To Bottom', 'powerpack' ),
					'fade-to-left'      => esc_html__( 'Fade To Left', 'powerpack' ),
					'fade-to-right'     => esc_html__( 'Fade To Right', 'powerpack' ),
					'slide-to-top'      => esc_html__( 'Slide To Top', 'powerpack' ),
					'slide-to-bottom'   => esc_html__( 'Slide To Bottom', 'powerpack' ),
					'slide-to-left'     => esc_html__( 'Slide To Left', 'powerpack' ),
					'slide-to-right'    => esc_html__( 'Slide To Right', 'powerpack' ),
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
				'label'                 => esc_html__( 'Normal', 'powerpack' ),
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
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
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
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
				'label'                 => esc_html__( 'Border', 'powerpack' ),
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
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
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
				'label'                 => esc_html__( 'Text Shadow', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-random-image-caption',
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_opacity_normal',
			[
				'label'                 => esc_html__( 'Opacity', 'powerpack' ),
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
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_color_hover',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
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
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
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
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
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
				'label'                 => esc_html__( 'Text Shadow', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-random-image-wrap:hover .pp-random-image-caption',
				'condition'             => [
					'caption!'   => '',
				],
			]
		);

		$this->add_control(
			'caption_opacity_hover',
			[
				'label'                 => esc_html__( 'Opacity', 'powerpack' ),
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
			$placeholder = sprintf( esc_html__( 'Click here to edit the "%1$s" settings and choose some images.', 'powerpack' ), esc_attr( $this->get_title() ) );

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
