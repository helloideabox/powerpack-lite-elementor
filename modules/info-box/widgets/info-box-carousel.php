<?php
namespace PowerpackElementsLite\Modules\InfoBox\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Info Box Carousel Widget
 */
class Info_Box_Carousel extends Powerpack_Widget {

	/**
	 * Retrieve info box carousel widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Info_Box_Carousel' );
	}

	/**
	 * Retrieve info box carousel widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Info_Box_Carousel' );
	}

	/**
	 * Retrieve info box carousel widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Info_Box_Carousel' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.4.13.1
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Info_Box_Carousel' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the info box carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return [
				'swiper',
				'pp-carousel',
			];
		}

		$settings = $this->get_settings_for_display();
		$scripts = [];

		if ( 'carousel' === $settings['layout'] ) {
			array_push( $scripts, 'swiper', 'pp-carousel' );
		}

		return $scripts;
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 2.11.0
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return [ 'pp-swiper', 'widget-pp-info-box' ];
	}

	/**
	 * Register info box carousel widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.0.3
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_info_boxes_controls();
		$this->register_content_carousel_settings_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_layout_controls();
		$this->register_style_box_controls();
		$this->register_style_icon_controls();
		$this->register_style_title_controls();
		$this->register_style_title_divider_controls();
		$this->register_style_description_controls();
		$this->register_style_button_controls();
		$this->register_style_arrows_controls();
		$this->register_style_dots_controls();
		$this->register_style_fraction_controls();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	CONTENT TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_content_info_boxes_controls() {
		/**
		 * Content Tab: Info Boxes
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_boxes',
			[
				'label'                     => esc_html__( 'Info Boxes', 'powerpack' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'items_repeater' );

		$repeater->start_controls_tab( 'tab_content', [ 'label' => esc_html__( 'Content', 'powerpack' ) ] );

			$repeater->add_control(
				'title',
				[
					'label'                 => esc_html__( 'Title', 'powerpack' ),
					'type'                  => Controls_Manager::TEXT,
					'dynamic'               => [
						'active'   => true,
					],
					'default'               => esc_html__( 'Title', 'powerpack' ),
				]
			);

			$repeater->add_control(
				'subtitle',
				[
					'label'                 => esc_html__( 'Subtitle', 'powerpack' ),
					'type'                  => Controls_Manager::TEXT,
					'dynamic'               => [
						'active'   => true,
					],
					'default'               => esc_html__( 'Subtitle', 'powerpack' ),
				]
			);

			$repeater->add_control(
				'description',
				[
					'label'                 => esc_html__( 'Description', 'powerpack' ),
					'type'                  => Controls_Manager::WYSIWYG,
					'dynamic'               => [
						'active'   => true,
					],
					'default'               => esc_html__( 'Enter info box description', 'powerpack' ),
				]
			);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'tab_icon', [ 'label' => esc_html__( 'Icon', 'powerpack' ) ] );

			$repeater->add_control(
				'icon_type',
				[
					'label'                 => esc_html__( 'Type', 'powerpack' ),
					'type'                  => Controls_Manager::CHOOSE,
					'label_block'           => false,
					'options'               => [
						'none'  => [
							'title' => esc_html__( 'None', 'powerpack' ),
							'icon'  => 'eicon-ban',
						],
						'icon'  => [
							'title' => esc_html__( 'Icon', 'powerpack' ),
							'icon'  => 'eicon-star',
						],
						'image' => [
							'title' => esc_html__( 'Image', 'powerpack' ),
							'icon'  => 'eicon-image-bold',
						],
						'text'  => [
							'title' => esc_html__( 'Text', 'powerpack' ),
							'icon'  => 'eicon-font',
						],
					],
					'default'               => 'icon',
				]
			);

			$repeater->add_control(
				'selected_icon',
				[
					'label'                 => esc_html__( 'Icon', 'powerpack' ),
					'type'                  => Controls_Manager::ICONS,
					'label_block'           => true,
					'default'               => [
						'value'     => 'fas fa-check',
						'library'   => 'fa-solid',
					],
					'fa4compatibility'      => 'icon',
					'condition'             => [
						'icon_type'     => 'icon',
					],
				]
			);

			$repeater->add_control(
				'icon_text',
				[
					'label'                 => esc_html__( 'Text', 'powerpack' ),
					'type'                  => Controls_Manager::TEXT,
					'dynamic'               => [
						'active'   => true,
					],
					'default'               => '1',
					'condition'             => [
						'icon_type'     => 'text',
					],
				]
			);

			$repeater->add_control(
				'image',
				[
					'label'                 => esc_html__( 'Image', 'powerpack' ),
					'type'                  => Controls_Manager::MEDIA,
					'dynamic'               => [
						'active'   => true,
					],
					'default'               => [
						'url' => Utils::get_placeholder_image_src(),
					],
					'condition'             => [
						'icon_type' => 'image',
					],
				]
			);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'tab_link', [ 'label' => esc_html__( 'Link', 'powerpack' ) ] );

		$repeater->add_control(
			'link_type',
			[
				'label'                 => esc_html__( 'Link Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'none',
				'options'               => [
					'none'      => esc_html__( 'None', 'powerpack' ),
					'box'       => esc_html__( 'Box', 'powerpack' ),
					'icon'      => esc_html__( 'Image/Icon', 'powerpack' ),
					'title'     => esc_html__( 'Title', 'powerpack' ),
					'button'    => esc_html__( 'Button', 'powerpack' ),
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'                 => esc_html__( 'Link', 'powerpack' ),
				'type'                  => Controls_Manager::URL,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => [
					'url' => '#',
				],
				'condition'             => [
					'link_type!'   => 'none',
				],
			]
		);

		$repeater->add_control(
			'button_visible',
			[
				'label'        => esc_html__( 'Show Button', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => [
					'link_type' => 'box',
				],
			]
		);

		$repeater->add_control(
			'button_text',
			[
				'label'                 => esc_html__( 'Button Text', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Get Started', 'powerpack' ),
				'conditions'            => [
					'relation' => 'or',
					'terms' => [
						[
							'name'     => 'link_type',
							'operator' => '==',
							'value'    => 'button',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name'     => 'link_type',
									'operator' => '==',
									'value'    => 'box',
								],
								[
									'name'     => 'button_visible',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'select_button_icon',
			[
				'label'                 => esc_html__( 'Button Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICONS,
				'label_block'           => true,
				'fa4compatibility'      => 'button_icon',
				'conditions'            => [
					'relation' => 'or',
					'terms' => [
						[
							'name'     => 'link_type',
							'operator' => '==',
							'value'    => 'button',
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name'     => 'link_type',
									'operator' => '==',
									'value'    => 'box',
								],
								[
									'name'     => 'button_visible',
									'operator' => '==',
									'value'    => 'yes',
								],
							],
						],
					],
				],
			]
		);

		$repeater->add_control(
			'button_icon_position',
			[
				'label'                 => esc_html__( 'Icon Position', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'after',
				'options'               => [
					'before'    => esc_html__( 'Before', 'powerpack' ),
					'after'     => esc_html__( 'After', 'powerpack' ),
				],
				'conditions'            => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name'     => 'link_type',
									'operator' => '==',
									'value'    => 'button',
								],
								[
									'name'     => 'select_button_icon[value]',
									'operator' => '!=',
									'value'    => '',
								],
							],
						],
						[
							'relation' => 'and',
							'terms' => [
								[
									'name'     => 'link_type',
									'operator' => '==',
									'value'    => 'box',
								],
								[
									'name'     => 'button_visible',
									'operator' => '==',
									'value'    => 'yes',
								],
								[
									'name'     => 'select_button_icon[value]',
									'operator' => '!=',
									'value'    => '',
								],
							],
						],
					],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'pp_info_boxes',
			[
				'label'     => '',
				'type'      => Controls_Manager::REPEATER,
				'default'   => [
					[
						'title' => esc_html__( 'Info Box 1', 'powerpack' ),
					],
					[
						'title' => esc_html__( 'Info Box 2', 'powerpack' ),
					],
					[
						'title' => esc_html__( 'Info Box 3', 'powerpack' ),
					],
				],
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{{ title }}}',
			]
		);

		$this->add_control(
			'layout',
			[
				'label'                 => esc_html__( 'Layout', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'carousel',
				'options'               => [
					'grid'     => esc_html__( 'Grid', 'powerpack' ),
					'carousel' => esc_html__( 'Carousel', 'powerpack' ),
				],
				'separator'             => 'before',
			]
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'              => esc_html__( 'Columns', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
					'7' => '7',
					'8' => '8',
				),
				'prefix_class'       => 'elementor-grid%s-',
				'condition'          => array(
					'layout' => 'grid',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'thumbnail',
				'label'                 => esc_html__( 'Image Size', 'powerpack' ),
				'default'               => 'full',
			]
		);

		$this->add_control(
			'divider_title_switch',
			[
				'label'                 => esc_html__( 'Title Separator', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'On', 'powerpack' ),
				'label_off'             => esc_html__( 'Off', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label'                 => esc_html__( 'Title HTML Tag', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'h4',
				'options'               => [
					'h1'     => esc_html__( 'H1', 'powerpack' ),
					'h2'     => esc_html__( 'H2', 'powerpack' ),
					'h3'     => esc_html__( 'H3', 'powerpack' ),
					'h4'     => esc_html__( 'H4', 'powerpack' ),
					'h5'     => esc_html__( 'H5', 'powerpack' ),
					'h6'     => esc_html__( 'H6', 'powerpack' ),
					'div'    => esc_html__( 'div', 'powerpack' ),
					'span'   => esc_html__( 'span', 'powerpack' ),
					'p'      => esc_html__( 'p', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'sub_title_html_tag',
			[
				'label'                 => esc_html__( 'Subtitle HTML Tag', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'h5',
				'options'               => [
					'h1'     => esc_html__( 'H1', 'powerpack' ),
					'h2'     => esc_html__( 'H2', 'powerpack' ),
					'h3'     => esc_html__( 'H3', 'powerpack' ),
					'h4'     => esc_html__( 'H4', 'powerpack' ),
					'h5'     => esc_html__( 'H5', 'powerpack' ),
					'h6'     => esc_html__( 'H6', 'powerpack' ),
					'div'    => esc_html__( 'div', 'powerpack' ),
					'span'   => esc_html__( 'span', 'powerpack' ),
					'p'      => esc_html__( 'p', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'equal_height_boxes',
			[
				'label'                 => esc_html__( 'Equal Height Boxes', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'On', 'powerpack' ),
				'label_off'             => esc_html__( 'Off', 'powerpack' ),
				'return_value'          => 'yes',
				'frontend_available'    => true,
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_carousel_settings_controls() {
		/**
		 * Content Tab: Carousel Settings
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_carousel_settings',
			[
				'label'                 => esc_html__( 'Carousel Settings', 'powerpack' ),
				'condition'             => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_control(
			'carousel_effect',
			[
				'label'                 => esc_html__( 'Effect', 'powerpack' ),
				'description'           => esc_html__( 'Sets transition effect', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'slide',
				'options'               => [
					'slide'     => esc_html__( 'Slide', 'powerpack' ),
					'fade'      => esc_html__( 'Fade', 'powerpack' ),
					'cube'      => esc_html__( 'Cube', 'powerpack' ),
					'coverflow' => esc_html__( 'Coverflow', 'powerpack' ),
					'flip'      => esc_html__( 'Flip', 'powerpack' ),
				],
				'condition'             => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_responsive_control(
			'items',
			[
				'label'                 => esc_html__( 'Visible Items', 'powerpack' ),
				'description'           => esc_html__( 'Number of slides visible at the same time on slider\'s container).', 'powerpack' ),
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
				'separator'             => 'before',
				'condition'             => [
					'layout'          => 'carousel',
					'carousel_effect' => 'slide',
				],
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label'                 => esc_html__( 'Items Gap', 'powerpack' ),
				'description'           => esc_html__( 'Distance between slides (in px)', 'powerpack' ),
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
				'condition'             => [
					'layout'          => 'carousel',
					'carousel_effect' => 'slide',
				],
			]
		);

		$this->add_control(
			'slider_speed',
			[
				'label'                 => esc_html__( 'Transition Duration', 'powerpack' ),
				'description'           => esc_html__( 'Duration of transition between slides (in ms)', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [ 'size' => 500 ],
				'range'                 => [
					'px' => [
						'min'   => 100,
						'max'   => 3000,
						'step'  => 1,
					],
				],
				'size_units'            => '',
				'separator'             => 'before',
				'condition'             => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'                 => esc_html__( 'Autoplay', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'separator'             => 'before',
				'condition'             => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label'                 => esc_html__( 'Pause on Hover', 'powerpack' ),
				'description'           => '',
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'frontend_available'    => true,
				'condition'             => [
					'layout'   => 'carousel',
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label'                 => esc_html__( 'Pause on Interaction', 'powerpack' ),
				'description'           => esc_html__( 'Disables autoplay completely on first interaction with the carousel.', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'frontend_available'    => true,
				'condition'             => [
					'layout'   => 'carousel',
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label'                 => esc_html__( 'Autoplay Speed', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [ 'size' => 3000 ],
				'range'                 => [
					'px' => [
						'min'   => 500,
						'max'   => 5000,
						'step'  => 1,
					],
				],
				'size_units'            => '',
				'condition'             => [
					'layout'   => 'carousel',
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'infinite_loop',
			[
				'label'                 => esc_html__( 'Infinite Loop', 'powerpack' ),
				'description'           => esc_html__( 'Enables continuous loop mode', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'          => esc_html__( 'Yes', 'powerpack' ),
				'label_off'         => esc_html__( 'No', 'powerpack' ),
				'return_value'      => 'yes',
				'condition'             => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_control(
			'centered_slides',
			[
				'label'                 => esc_html__( 'Centered Slides', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'separator'             => 'before',
				'condition'             => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_control(
			'grab_cursor',
			[
				'label'                 => esc_html__( 'Grab Cursor', 'powerpack' ),
				'description'           => esc_html__( 'Shows grab cursor when you hover over the slider', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'Show', 'powerpack' ),
				'label_off'             => esc_html__( 'Hide', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_control(
			'navigation_heading',
			[
				'label'                 => esc_html__( 'Navigation', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_control(
			'arrows',
			[
				'label'                 => esc_html__( 'Arrows', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_control(
			'dots',
			[
				'label'                 => esc_html__( 'Pagination', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'layout' => 'carousel',
				],
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label'                 => esc_html__( 'Pagination Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'bullets',
				'options'               => [
					'bullets'  => esc_html__( 'Dots', 'powerpack' ),
					'fraction' => esc_html__( 'Fraction', 'powerpack' ),
				],
				'condition'             => [
					'layout' => 'carousel',
					'dots'   => 'yes',
				],
			]
		);

		$this->add_control(
			'direction',
			[
				'label'                 => esc_html__( 'Direction', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'left',
				'options'               => [
					'auto'  => esc_html__( 'Auto', 'powerpack' ),
					'left'  => esc_html__( 'Left', 'powerpack' ),
					'right' => esc_html__( 'Right', 'powerpack' ),
				],
				'separator'             => 'before',
				'condition'             => [
					'layout' => 'carousel',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Info_Box_Carousel' );

		if ( ! empty( $help_docs ) ) {

			/**
			 * Content Tab: Help Docs
			 *
			 * @since 1.4.8
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

	/**
	 * Register layout controls for style tab
	 */
	protected function register_style_layout_controls() {
		/**
		 * Style Tab: Layout
		 */
		$this->start_controls_section(
			'section_layout_style',
			array(
				'label' => esc_html__( 'Layout', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'align',
			[
				'label'                 => esc_html__( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
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
					'justify'   => [
						'title' => esc_html__( 'Justified', 'powerpack' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'               => 'center',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box'   => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'columns_gap',
			array(
				'label'          => esc_html__( 'Columns Gap', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'default'        => array(
					'size' => 20,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}}' => '--grid-column-gap: {{SIZE}}{{UNIT}}',
				),
				'condition'      => array(
					'layout' => 'grid',
				),
			)
		);

		$this->add_responsive_control(
			'rows_gap',
			array(
				'label'          => esc_html__( 'Rows Gap', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 20,
					'unit' => 'px',
				),
				'size_units'     => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}',
				),
				'condition'      => array(
					'layout' => 'grid',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_box_controls() {
		/**
		 * Style Tab: Box
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_box_style',
			[
				'label'                 => esc_html__( 'Box', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'              => 'info_box_background',
				'types'             => [ 'classic', 'gradient' ],
				'selector'          => '{{WRAPPER}} .pp-info-box',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'info_box_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'separator'             => 'before',
				'selector'              => '{{WRAPPER}} .pp-info-box',
			]
		);

		$this->add_control(
			'info_box_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'info_box_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'separator'             => 'before',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_icon_controls() {
		/**
		 * Style Tab: Icon Style
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_box_icon_style',
			[
				'label'                 => esc_html__( 'Icon', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'                 => esc_html__( 'Icon Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 5,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			[
				'label'                 => esc_html__( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'icon_color_normal',
			[
				'label'                 => esc_html__( 'Icon Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-info-box-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_bg_color_normal',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'icon_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-info-box-icon',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon, {{WRAPPER}} .pp-info-box-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_rotation',
			[
				'label'                 => esc_html__( 'Icon Rotation', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 360,
						'step'  => 1,
					],
				],
				'size_units'            => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon' => 'transform: rotate( {{SIZE}}deg );',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 120,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label'                 => esc_html__( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			[
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label'                 => esc_html__( 'Icon Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-info-box-icon:hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_bg_color_hover',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'icon_border_color_hover',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_rotation_hover',
			[
				'label'                 => esc_html__( 'Icon Rotation', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 360,
						'step'  => 1,
					],
				],
				'size_units'            => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box .pp-info-box-icon-wrap:hover' => 'transform: rotate( {{SIZE}}deg );',
				],
			]
		);

		$this->add_control(
			'icon_animation',
			[
				'label'                 => esc_html__( 'Icon Animation', 'powerpack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_image_heading',
			[
				'label'                 => esc_html__( 'Icon Image', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_responsive_control(
			'icon_img_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 25,
						'max'   => 200,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'default'               => [
					'size' => 100,
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-icon img' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'icon_text_heading',
			[
				'label'                 => esc_html__( 'Icon Text', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'icon_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'              => '{{WRAPPER}} .pp-info-box-icon',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_title_controls() {
		/**
		 * Style Tab: Title
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_box_title_style',
			[
				'label'                 => esc_html__( 'Title', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'title_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'              => '{{WRAPPER}} .pp-info-box-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'                  => 'title_text_stroke',
				'selector'              => '{{WRAPPER}} .pp-info-box-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'                  => 'title_text_shadow',
				'label'                 => esc_html__( 'Text Shadow', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-info-box-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size'  => 20,
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
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'subtitle_heading',
			[
				'label'                 => esc_html__( 'Sub Title', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'subtitle_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector'              => '{{WRAPPER}} .pp-info-box-subtitle',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'                  => 'subtitle_text_stroke',
				'selector'              => '{{WRAPPER}} .pp-info-box-subtitle',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'                  => 'subtitle_text_shadow',
				'label'                 => esc_html__( 'Text Shadow', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-info-box-subtitle',
			)
		);

		$this->add_responsive_control(
			'subtitle_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size'  => 20,
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
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_title_divider_controls() {
		/**
		 * Style Tab: Title Separator
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_title_divider_style',
			[
				'label'                 => esc_html__( 'Title Separator', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_title_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_title_border_type',
			[
				'label'                 => esc_html__( 'Border Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'solid',
				'options'               => [
					'none'      => esc_html__( 'None', 'powerpack' ),
					'solid'     => esc_html__( 'Solid', 'powerpack' ),
					'double'    => esc_html__( 'Double', 'powerpack' ),
					'dotted'    => esc_html__( 'Dotted', 'powerpack' ),
					'dashed'    => esc_html__( 'Dashed', 'powerpack' ),
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-style: {{VALUE}}',
				],
				'condition'             => [
					'divider_title_switch' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_title_width',
			[
				'label'                 => esc_html__( 'Border Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size'  => 30,
				],
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 1000,
						'step'  => 1,
					],
					'%' => [
						'min'   => 1,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-divider' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'divider_title_switch' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_title_border_height',
			[
				'label'                 => esc_html__( 'Border Height', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size'  => 2,
				],
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 20,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'divider_title_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'divider_title_border_color',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-divider' => 'border-bottom-color: {{VALUE}}',
				],
				'condition'             => [
					'divider_title_switch' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_title_align',
			[
				'label'                 => esc_html__( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
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
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-divider-wrap'   => 'text-align: {{VALUE}};',
				],
				'condition'             => [
					'divider_title_switch' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'divider_title_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size'  => 20,
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
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'divider_title_switch' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_description_controls() {
		/**
		 * Style Tab: Description
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_description_style',
			[
				'label'                 => esc_html__( 'Description', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'description_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-description' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'description_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector'              => '{{WRAPPER}} .pp-info-box-description',
			]
		);

		$this->add_responsive_control(
			'description_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-description' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'description_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size'  => 0,
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
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-description' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_button_controls() {
		/**
		 * Style Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_box_button_style',
			[
				'label'                 => esc_html__( 'Button', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'button_size',
			[
				'label'                 => esc_html__( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'md',
				'options'               => [
					'xs' => esc_html__( 'Extra Small', 'powerpack' ),
					'sm' => esc_html__( 'Small', 'powerpack' ),
					'md' => esc_html__( 'Medium', 'powerpack' ),
					'lg' => esc_html__( 'Large', 'powerpack' ),
					'xl' => esc_html__( 'Extra Large', 'powerpack' ),
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label'                 => esc_html__( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'button_text_color_normal',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-info-box-button .pp-icon' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_color_normal',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_normal',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-info-box-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'button_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'              => '{{WRAPPER}} .pp-info-box-button',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-info-box-button',
			]
		);

		$this->add_control(
			'info_box_button_icon_heading',
			[
				'label'                 => esc_html__( 'Button Icon', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'button_icon!'  => '',
				],
			]
		);

		$this->add_responsive_control(
			'button_icon_margin',
			[
				'label'                 => esc_html__( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'placeholder'           => [
					'top'      => '',
					'right'    => '',
					'bottom'   => '',
					'left'     => '',
				],
				'condition'             => [
					'button_icon!'  => '',
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
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-info-box-button:hover .pp-icon' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-info-box-button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label'                 => esc_html__( 'Animation', 'powerpack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-info-box-button:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_arrows_controls() {
		/**
		 * Style Tab: Arrows
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_arrows_style',
			[
				'label'                 => esc_html__( 'Arrows', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'arrows'        => 'yes',
				],
			]
		);

		$this->add_control(
			'select_arrow',
			array(
				'label'                  => esc_html__( 'Choose Arrow', 'powerpack' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'arrow',
				'label_block'            => false,
				'default'                => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'skin'                   => 'inline',
				'exclude_inline_options' => 'svg',
				'recommended'            => array(
					'fa-regular' => array(
						'arrow-alt-circle-right',
						'caret-square-right',
						'hand-point-right',
					),
					'fa-solid'   => array(
						'angle-right',
						'angle-double-right',
						'chevron-right',
						'chevron-circle-right',
						'arrow-right',
						'long-arrow-alt-right',
						'caret-right',
						'caret-square-right',
						'arrow-circle-right',
						'arrow-alt-circle-right',
						'toggle-right',
						'hand-point-right',
					),
				),
			)
		);

		$this->add_responsive_control(
			'arrows_size',
			[
				'label'                 => esc_html__( 'Arrows Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [ 'size' => '22' ],
				'range'                 => [
					'px' => [
						'min'   => 15,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'left_arrow_position',
			[
				'label'                 => esc_html__( 'Align Left Arrow', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => -100,
						'max'   => 40,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .elementor-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'right_arrow_position',
			[
				'label'                 => esc_html__( 'Align Right Arrow', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => -100,
						'max'   => 40,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .elementor-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			[
				'label'                 => esc_html__( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'arrows_bg_color_normal',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-slider-arrow' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_color_normal',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-slider-arrow' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'arrows_border_normal',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-slider-arrow',
			]
		);

		$this->add_control(
			'arrows_border_radius_normal',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			[
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'arrows_bg_color_hover',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-slider-arrow:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_color_hover',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-slider-arrow:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'arrows_border_color_hover',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-slider-arrow:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-slider-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'             => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_dots_controls() {
		/**
		 * Style Tab: Pagination: Dots
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_dots_style',
			[
				'label'                 => esc_html__( 'Pagination: Dots', 'powerpack' ),
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
				'label'                 => esc_html__( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'inside'     => esc_html__( 'Inside', 'powerpack' ),
					'outside'    => esc_html__( 'Outside', 'powerpack' ),
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
				'label'                 => esc_html__( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 2,
						'max'   => 40,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
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
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 30,
						'step'  => 1,
					],
				],
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
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
				'label'                 => esc_html__( 'Normal', 'powerpack' ),
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_color_normal',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
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
				'label'                 => esc_html__( 'Active Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
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
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .swiper-pagination-bullet',
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_border_radius_normal',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label'                 => esc_html__( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'allowed_dimensions'    => 'vertical',
				'placeholder'           => [
					'top'      => '',
					'right'    => 'auto',
					'bottom'   => '',
					'left'     => 'auto',
				],
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'bullets',
				],
			]
		);

		$this->add_control(
			'dots_color_hover',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
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
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
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
	}

	protected function register_style_fraction_controls() {
		/**
		 * Style Tab: Pagination: Fraction
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_fraction_style',
			[
				'label'                 => esc_html__( 'Pagination: Fraction', 'powerpack' ),
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
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
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
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'              => '{{WRAPPER}} .swiper-pagination-fraction',
				'condition'             => [
					'dots'              => 'yes',
					'pagination_type'   => 'fraction',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Get swiper slider settings
	 *
	 * @access public
	 * @since 2.1.0
	 */
	public function get_slider_settings() {
		$settings = $this->get_settings_for_display();
		$effect   = $settings['carousel_effect'] ?? 'slide';

		if ( 'slide' === $effect ) {
			$items         = ( isset( $settings['items']['size'] ) && $settings['items']['size'] ) ? absint( $settings['items']['size'] ) : 3;
			$items_tablet  = ( isset( $settings['items_tablet']['size'] ) && $settings['items_tablet']['size'] ) ? absint( $settings['items_tablet']['size'] ) : 2;
			$items_mobile  = ( isset( $settings['items_mobile']['size'] ) && $settings['items_mobile']['size'] ) ? absint( $settings['items_mobile']['size'] ) : 1;
			$margin        = ( isset( $settings['margin']['size'] ) && $settings['margin']['size'] ) ? absint( $settings['margin']['size'] ) : 10;
			$margin_tablet = ( isset( $settings['margin_tablet']['size'] ) && $settings['margin_tablet']['size'] ) ? absint( $settings['margin_tablet']['size'] ) : 10;
			$margin_mobile = ( isset( $settings['margin_mobile']['size'] ) && $settings['margin_mobile']['size'] ) ? absint( $settings['margin_mobile']['size'] ) : 10;
		} elseif ( 'coverflow' === $effect ) {
			$items         = 3;
			$items_tablet  = 2;
			$items_mobile  = 1;
			$margin        = 10;
			$margin_tablet = 10;
			$margin_mobile = 10;
		} else {
			$items         = 1;
			$items_tablet  = 1;
			$items_mobile  = 1;
			$margin        = 10;
			$margin_tablet = 10;
			$margin_mobile = 10;
		}

		$slider_options = [
			'effect'          => $effect,
			'speed'           => ( $settings['slider_speed']['size'] ) ? $settings['slider_speed']['size'] : 500,
			'slides_per_view' => $items,
			'space_between'   => $margin,
			'auto_height'     => true,
			'loop'            => ( 'yes' === $settings['infinite_loop'] ) ? 'yes' : '',
		];

		if ( 'yes' === $settings['grab_cursor'] ) {
			$slider_options['grab_cursor'] = true;
		}

		if ( 'yes' === $settings['centered_slides'] ) {
			$slider_options['centered_slides'] = 'yes';
		}

		if ( 'yes' === $settings['autoplay'] ) {
			$autoplay_speed = 999999;
			$slider_options['autoplay'] = 'yes';

			if ( isset( $settings['autoplay_speed']['size'] ) ) {
				$autoplay_speed = $settings['autoplay_speed']['size'];
			} elseif ( $settings['autoplay_speed'] ) {
				$autoplay_speed = $settings['autoplay_speed'];
			}

			$slider_options['autoplay_speed'] = $autoplay_speed;
			$slider_options['pause_on_interaction'] = ( 'yes' === $settings['pause_on_interaction'] ) ? 'yes' : '';
		}

		if ( 'yes' === $settings['dots'] && $settings['pagination_type'] ) {
			$slider_options['pagination'] = $settings['pagination_type'];
		}

		if ( 'yes' === $settings['arrows'] ) {
			$slider_options['show_arrows'] = true;
		}

		$breakpoints = PP_Helper::elementor()->breakpoints->get_active_breakpoints();

		foreach ( $breakpoints as $device => $breakpoint ) {
			if ( in_array( $device, [ 'mobile', 'tablet', 'desktop' ] ) ) {
				switch ( $device ) {
					case 'desktop':
						$slider_options['slides_per_view'] = absint( $items );
						$slider_options['space_between'] = absint( $margin );
						break;
					case 'tablet':
						$slider_options['slides_per_view_tablet'] = absint( $items_tablet );
						$slider_options['space_between_tablet'] = absint( $margin_tablet );
						break;
					case 'mobile':
						$slider_options['slides_per_view_mobile'] = absint( $items_mobile );
						$slider_options['space_between_mobile'] = absint( $margin_mobile );
						break;
				}
			} else {
				if ( isset( $settings['items_' . $device]['size'] ) && $settings['items_' . $device]['size'] ) {
					$slider_options['slides_per_view_' . $device] = absint( $settings['items_' . $device]['size'] );
				}

				if ( isset( $settings['margin_' . $device]['size'] ) && $settings['margin_' . $device]['size'] ) {
					$slider_options['space_between_' . $device] = absint( $settings['margin_' . $device]['size'] );
				}
			}
		}

		return $slider_options;
	}

	/**
	 * Render info box carousel widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			[
				'container' => [
					'class' => 'pp-info-box-container',
				],
				'info-box'  => [
					'class' => 'pp-info-box',
				]
			]
		);

		if ( 'grid' === $settings['layout'] ) {

			$this->add_render_attribute( 'container', 'class', 'elementor-grid' );
			$this->add_render_attribute( 'info-box', 'class', 'elementor-grid-item' );

			if ( 'yes' === $settings['equal_height_boxes'] ) {
	
				$this->add_render_attribute( 'container', 'class', 'pp-info-box-equal-height' );
			}

		} else {

			if ( 'right' === $settings['direction'] || is_rtl() ) {
				$this->add_render_attribute( 'container', 'dir', 'rtl' );
			}

			$slider_options = $this->get_slider_settings();

			$swiper_class = PP_Helper::is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';

			$this->add_render_attribute(
				'container',
				[
					'class'                => [ 'pp-info-box-carousel', 'pp-swiper-slider', $swiper_class ],
					'data-slider-settings' => wp_json_encode( $slider_options ),
				]
			);

			if ( $settings['dots_position'] ) {
				$this->add_render_attribute( 'container', 'class', 'swiper-container-wrap-dots-' . $settings['dots_position'] );
			} elseif ( 'fraction' === $settings['pagination_type'] ) {
				$this->add_render_attribute( 'container', 'class', 'swiper-container-wrap-dots-outside' );
			}

			$this->add_render_attribute( 'info-box', 'class', 'swiper-slide' );

		}

		$title_container_tag = 'div';
		$button_html_tag     = 'div';

		$this->add_render_attribute( 'info-box-button', 'class', [
			'pp-info-box-button',
			'elementor-button',
			'elementor-size-' . $settings['button_size'],
		] );

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'info-box-button', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		$this->add_render_attribute( 'icon', 'class', [ 'pp-info-box-icon', 'pp-icon' ] );

		if ( $settings['icon_animation'] ) {
			$this->add_render_attribute( 'icon', 'class', 'elementor-animation-' . $settings['icon_animation'] );
		}
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'container' ) ); ?>>
			<?php if ( 'carousel' === $settings['layout'] ) { ?><div class="swiper-wrapper"><?php } ?>
				<?php
				$i = 1;

				foreach ( $settings['pp_info_boxes'] as $index => $item ) :
					$title_container_setting_key = $this->get_repeater_setting_key( 'title_container', 'info_boxes', $index );
					$link_setting_key = $this->get_repeater_setting_key( 'link', 'info_boxes', $index );

					$this->add_render_attribute( $title_container_setting_key, 'class', 'pp-info-box-title-container' );

					if ( 'none' !== $item['link_type'] ) {
						if ( ! empty( $item['link']['url'] ) ) {

							$this->add_link_attributes( $link_setting_key, $item['link'] );

							if ( 'title' === $item['link_type'] ) {
								$title_container_tag = 'a';
								$this->add_link_attributes( $title_container_setting_key, $item['link'] );
							} elseif ( 'button' === $item['link_type'] ) {
								$button_html_tag = 'a';
							} elseif ( 'box' === $item['link_type'] ) {
								$button_html_tag = 'div';
							}
						}
					}
					?>
					<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'info-box' ) ); ?>>
						<?php if ( 'box' === $item['link_type'] ) { ?>
							<a <?php echo wp_kses_post( $this->get_render_attribute_string( $link_setting_key ) ); ?>>
						<?php } ?>
							<?php if ( 'none' !== $item['icon_type'] ) { ?>
								<div class="pp-info-box-icon-wrap">
									<?php if ( 'icon' === $item['link_type'] ) { ?>
										<a <?php echo wp_kses_post( $this->get_render_attribute_string( $link_setting_key ) ); ?>>
									<?php } ?>
									<?php $this->render_infobox_icon( $item ); ?>
									<?php if ( 'icon' === $item['link_type'] ) { ?>
										</a>
									<?php } ?>
								</div>
							<?php } ?>
							<div class="pp-info-box-content">
								<div class="pp-info-box-title-wrap">
									<?php
									if ( '' !== $item['title'] ) {
										$title_tag = PP_Helper::validate_html_tag( $settings['title_html_tag'] );
										?>
										<<?php echo esc_html( $title_container_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( $title_container_setting_key ) ); ?>>
											<<?php echo esc_html( $title_tag ); ?> class="pp-info-box-title">
												<?php echo wp_kses_post( $item['title'] ); ?>
											</<?php echo esc_html( $title_tag ); ?>>
										</<?php echo esc_html( $title_container_tag ); ?>>
										<?php
									}

									if ( '' !== $item['subtitle'] ) {
										$subtitle_tag = PP_Helper::validate_html_tag( $settings['sub_title_html_tag'] );
										?>
										<<?php echo esc_html( $subtitle_tag ); ?> class="pp-info-box-subtitle">
											<?php echo wp_kses_post( $item['subtitle'] ); ?>
										</<?php echo esc_html( $subtitle_tag ); ?>>
										<?php
									}
									?>
								</div>

								<?php if ( 'yes' === $settings['divider_title_switch'] ) { ?>
									<div class="pp-info-box-divider-wrap">
										<div class="pp-info-box-divider"></div>
									</div>
								<?php } ?>

								<?php if ( ! empty( $item['description'] ) ) { ?>
									<div class="pp-info-box-description">
										<?php echo $this->parse_text_editor( $item['description'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php } ?>
								<?php if ( 'button' === $item['link_type'] || ( 'box' === $item['link_type'] && 'yes' === $item['button_visible'] ) ) { ?>
									<div class="pp-info-box-footer">
										<<?php echo esc_html( $button_html_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'info-box-button' ) ); ?> <?php if ( 'button' === $item['link_type'] ) { echo wp_kses_post( $this->get_render_attribute_string( $link_setting_key ) ); } ?>>
											<?php
											if ( 'before' === $item['button_icon_position'] ) {
												$this->render_infobox_button_icon( $item );
											}
											?>
											<?php if ( ! empty( $item['button_text'] ) ) { ?>
												<span class="pp-button-text">
													<?php echo wp_kses_post( $item['button_text'] ); ?>
												</span>
											<?php } ?>
											<?php
											if ( 'after' === $item['button_icon_position'] ) {
												$this->render_infobox_button_icon( $item );
											}
											?>
										</<?php echo esc_html( $button_html_tag ); ?>>
									</div>
								<?php } ?>
							</div>
						<?php if ( 'box' === $item['link_type'] ) { ?>
							</a>
						<?php } ?>
					</div>
					<?php $i++;
				endforeach; ?>
			<?php if ( 'carousel' === $settings['layout'] ) { ?></div><?php } ?>
			<?php
			if ( 'carousel' === $settings['layout'] ) {
				$this->render_dots();

				$this->render_arrows();
			}
			?>
		</div>
		<?php
	}

	/**
	 * Render info-box carousel icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_infobox_icon( $item ) {
		$settings = $this->get_settings_for_display();

		$fallback_defaults = [
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		];

		$migration_allowed = Icons_Manager::is_migration_allowed();

		// add old default
		if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
			$item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
		}

		$migrated = isset( $item['__fa4_migrated']['selected_icon'] );
		$is_new = ! isset( $item['icon'] ) && $migration_allowed;

		if ( ! empty( $item['icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) || ! empty( $item['image']['url'] ) || '' !== $item['icon_text'] ) {
			?>
			<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon' ) ); ?>>
				<?php if ( 'icon' === $item['icon_type'] ) { ?>
					<?php
					if ( $is_new || $migrated ) {
						Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] );
					} else { ?>
						<i class="<?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
					<?php } ?>
				<?php } elseif ( 'image' === $item['icon_type'] ) { ?>
					<?php
					if ( ! empty( $item['image']['url'] ) ) {
						$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['image']['id'], 'thumbnail', $settings );

						if ( $image_url ) {
							?>
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( Control_Media::get_image_alt( $item['image'] ) ); ?>">
							<?php
						} else {
							?>
							<img src="<?php echo esc_url( $item['image']['url'] ); ?>">
							<?php
						}
					}
					?>
				<?php } elseif ( 'text' === $item['icon_type'] ) {
					echo wp_kses_post( $item['icon_text'] );
				} ?>
			</span>
			<?php
		}
	}

	/**
	 * Render info-box carousel icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_infobox_button_icon( $item ) {
		$settings = $this->get_settings_for_display();

		$migration_allowed = Icons_Manager::is_migration_allowed();

		// add old default
		if ( ! isset( $item['button_icon'] ) && ! $migration_allowed ) {
			$item['button_icon'] = '';
		}

		$migrated = isset( $item['__fa4_migrated']['select_button_icon'] );
		$is_new = ! isset( $item['button_icon'] ) && $migration_allowed;

		if ( ! empty( $item['button_icon'] ) || ( ! empty( $item['select_button_icon']['value'] ) && $is_new ) ) {
			?>
			<span class="pp-button-icon pp-icon">
				<?php
				if ( $is_new || $migrated ) {
					Icons_Manager::render_icon( $item['select_button_icon'], [ 'aria-hidden' => 'true' ] );
				} else { ?>
					<i class="<?php echo esc_attr( $item['button_icon'] ); ?>" aria-hidden="true"></i>
				<?php } ?>
			</span>
			<?php
		}
	}

	/**
	 * Render info-box carousel dots output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_dots() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' === $settings['dots'] ) { ?>
			<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ); ?>"></div>
		<?php }
	}

	/**
	 * Render info-box carousel arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_arrows() {
		PP_Helper::render_arrows( $this );
	}

	protected function content_template() {
		$elementor_bp_tablet = get_option( 'elementor_viewport_lg' );
		$elementor_bp_mobile = get_option( 'elementor_viewport_md' );
		$elementor_bp_lg     = get_option( 'elementor_viewport_lg' );
		$elementor_bp_md     = get_option( 'elementor_viewport_md' );
		$bp_desktop          = ! empty( $elementor_bp_lg ) ? $elementor_bp_lg : 1025;
		$bp_tablet           = ! empty( $elementor_bp_md ) ? $elementor_bp_md : 768;
		$bp_mobile           = 320;
		?>
		<#
			function get_slider_settings( settings ) {

				if ( settings.carousel_effect !== 'undefined' ) {
					var $effect = settings.carousel_effect;
				} else {
					var $effect = 'slide';
				}

				var $items         = ( settings.items.size !== '' || settings.items.size !== undefined ) ? settings.items.size : 3,
					$items_tablet  = ( settings.items_tablet.size !== '' || settings.items_tablet.size !== undefined ) ? settings.items_tablet.size : 2,
					$items_mobile  = ( settings.items_mobile.size !== '' || settings.items_mobile.size !== undefined ) ? settings.items_mobile.size : 1,
					$margin        = ( settings.margin.size !== '' || settings.margin.size !== undefined ) ? settings.margin.size : 10,
					$margin_tablet = ( settings.margin_tablet.size !== '' || settings.margin_tablet.size !== undefined ) ? settings.margin_tablet.size : 10,
					$margin_mobile = ( settings.margin_mobile.size !== '' || settings.margin_mobile.size !== undefined ) ? settings.margin_mobile.size : 10;

				if ( $effect == 'coverflow' ) {
					$items         = 3,
					$items_tablet  = 2,
					$items_mobile  = 1;
				} else if ( $effect == 'fade' || $effect == 'cube' || $effect == 'flip' ) {
					$items         = 1,
					$items_tablet  = 1,
					$items_mobile  = 1,
					$margin        = 10,
					$margin_tablet = 10,
					$margin_mobile = 10;
				}

				var sliderOptions = {
					effect:          $effect,
					speed:           ( settings.slider_speed.size !== '' || settings.slider_speed.size !== undefined ) ? settings.slider_speed.size : 500,
					slides_per_view: $items,
					space_between:   $margin,
					auto_height:     true,
					loop:            ( 'yes' === settings.infinite_loop ) ? 'yes' : false,
					centered_slides: ( 'yes' === settings.centered_slides ) ? 'yes' : false,
					grab_cursor:     ( 'yes' === settings.grab_cursor ) ? 'yes' : false,
				}

				if ( 'yes' === settings.autoplay ) {
					var $autoplay = ( '' !== settings.autoplay_speed.size ) ? settings.autoplay_speed.size : 999999;

					sliderOptions.autoplay = $autoplay;
					sliderOptions.pause_on_interaction = ( 'yes' === settings.pause_on_interaction ) ? 'yes' : '';;
				}

				if ( 'yes' === settings.dots && settings.pagination_type ) {
					sliderOptions.pagination = settings.pagination_type;
				}

				if ( 'yes' === settings.arrows ) {
					sliderOptions.show_arrows = true;
				}

				breakpoints = elementorFrontend.config.responsive.activeBreakpoints;
				Object.keys(breakpoints).forEach(breakpointName => {
					if ( 'tablet' === breakpointName || 'mobile' === breakpointName ) {
						switch(breakpointName) {
							case 'tablet':
								sliderOptions['slides_per_view_tablet'] = $items_tablet;
								sliderOptions['space_between_tablet'] = $margin_tablet;
								break;
							case 'mobile':
								sliderOptions['slides_per_view_mobile'] = $items_mobile;
								sliderOptions['space_between_mobile'] = $margin_mobile;
								break;
						}
					} else {
						if ( settings['items_' + breakpointName].size !== '' || settings['items_' + breakpointName].size !== undefined ) {
							sliderOptions['slides_per_view_' + breakpointName] = settings['items_' + breakpointName].size;
						}

						if ( settings['margin_' + breakpointName].size !== '' || settings['margin_' + breakpointName].size !== undefined ) {
							sliderOptions['space_between_' + breakpointName] = settings['margin_' + breakpointName].size;
						}
					}
				});

				return sliderOptions;
			};

			function dots_template() {
				if ( settings.dots == 'yes' ) {
					#>
					<div class="swiper-pagination"></div>
					<#
				}
			}

			function arrows_template() {
				var arrowIconHTML = elementor.helpers.renderIcon( view, settings.select_arrow, { 'aria-hidden': true }, 'i' , 'object' ),
					arrowMigrated = elementor.helpers.isIconMigrated( settings, 'select_arrow' );

				if ( settings.arrows == 'yes' ) {
					if ( settings.arrow || settings.select_arrow.value ) {
						if ( arrowIconHTML && arrowIconHTML.rendered && ( ! settings.arrow || arrowMigrated ) ) {
							var next_arrow = settings.select_arrow.value;
							var prev_arrow = next_arrow.replace('right', "left");
						} else if ( settings.arrow != '' ) {
							var next_arrow = settings.arrow;
							var prev_arrow = next_arrow.replace('right', "left");
						}
						else {
							var next_arrow = 'fa fa-angle-right';
							var prev_arrow = 'fa fa-angle-left';
						}
						#>
						<div class="pp-slider-arrow elementor-swiper-button-next">
							<i class="{{ next_arrow }}"></i>
						</div>
						<div class="pp-slider-arrow elementor-swiper-button-prev">
							<i class="{{ prev_arrow }}"></i>
						</div>
						<#
					}
				}
			}
					   
			function button_icon_template( item, index ) {
				var buttonIconHTML = {},
					buttonMigrated = {};

				if ( item.button_icon || item.select_button_icon.value ) { #>
					<span class="pp-button-icon pp-icon">
						<#
						buttonIconHTML[ index ] = elementor.helpers.renderIcon( view, item.select_button_icon, { 'aria-hidden': true }, 'i', 'object' );
						buttonMigrated[ index ] = elementor.helpers.isIconMigrated( item, 'select_button_icon' );
						if ( buttonIconHTML[ index ] && buttonIconHTML[ index ].rendered && ( ! item.button_icon || buttonMigrated[ index ] ) ) { #>
							{{{ buttonIconHTML[ index ].value }}}
						<# } else { #>
							<i class="{{ item.button_icon }}" aria-hidden="true"></i>
						<# } #>
					</span>
					<#
				}
			}

			view.addRenderAttribute( 'container', 'class', 'pp-info-box-container' );

			view.addRenderAttribute( 'info-box', 'class', 'pp-info-box' );

            if ( settings.layout == 'grid' ) {

				view.addRenderAttribute( 'container', 'class', 'elementor-grid' );
				view.addRenderAttribute( 'info-box', 'class', 'elementor-grid-item' );

			} else {

                if ( settings.direction == 'auto' ) {
					var direction = elementorFrontend.config.is_rtl ? 'rtl' : 'ltr';

					view.addRenderAttribute( 'container', 'dir', direction );
                } else {
                    if ( settings.direction == 'right' ) {
                        view.addRenderAttribute( 'container', 'dir', 'rtl' );
                    }
                }

                var slider_options = get_slider_settings( settings );

                view.addRenderAttribute(
                    'container',
                    {
                        'class': [ 'pp-info-box-carousel', 'pp-swiper-slider', elementorFrontend.config.swiperClass, 'swiper-container-wrap-dots-' + settings.dots_position ],
                        'data-slider-settings': JSON.stringify( slider_options )
                    }
                );

				view.addRenderAttribute( 'info-box', 'class', 'swiper-slide' );
            }

			var $title_container_tag = 'div',
				$button_html_tag = 'div';

			view.addRenderAttribute( 'info-box-button', 'class', [
					'pp-info-box-button',
					'elementor-button',
					'elementor-size-' + settings.button_size,
				],
			);

			if ( settings.button_hover_animation ) {
				view.addRenderAttribute( 'info-box-button', 'class', 'elementor-animation-' + settings.button_hover_animation );
			}

			view.addRenderAttribute( 'icon', 'class', ['pp-info-box-icon', 'pp-icon'] );

			if ( settings.icon_animation ) {
				view.addRenderAttribute( 'icon', 'class', 'elementor-animation-' + settings.icon_animation );
			}

			var iconsHTML = {},
				migrated = {};
		#>
		<div {{{ view.getRenderAttributeString( 'container' ) }}}>
			<# if ( settings.layout == 'carousel' ) { #><div class="swiper-wrapper"><# } #>
			<#
				var i = 1;

				_.each( settings.pp_info_boxes, function( item, index ) {
					
					view.addRenderAttribute( 'title-container' + i, 'class', 'pp-info-box-title-container' );

					if ( item.link_type != 'none' ) {
						if ( item.link.url ) {
				
							view.addRenderAttribute( 'link' + i, 'href', item.link.url );

							if ( item.link.is_external ) {
								view.addRenderAttribute( 'link' + i, 'target', '_blank' );
							}

							if ( item.link.nofollow ) {
								view.addRenderAttribute( 'link' + i, 'rel', 'nofollow' );
							}

							if ( item.link_type == 'title' ) {
								$title_container_tag = 'a';
								view.addRenderAttribute( 'title-container' + i, 'href', item.link.url );
							}
							else if ( item.link_type == 'button' ) {
								$button_html_tag = 'a';
							}
						}
					}
				#>
				<div {{{ view.getRenderAttributeString( 'info-box' ) }}}>
					<# if ( item.link_type == 'box' ) { #>
						<a {{{ view.getRenderAttributeString( 'link' + i ) }}}>
					<# } #>
						<# if ( item.icon_type != 'none' ) { #>
							<div class="pp-info-box-icon-wrap">
								<# if ( item.link_type == 'icon' ) { #>
									<a {{{ view.getRenderAttributeString( 'link' + i ) }}}>
								<# } #>
								<span {{{ view.getRenderAttributeString( 'icon' ) }}}>
									<# if ( item.icon_type == 'icon' ) { #>
										<# if ( item.icon || item.selected_icon.value ) { #>
											<#
												iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.selected_icon, { 'aria-hidden': true }, 'i', 'object' );
												migrated[ index ] = elementor.helpers.isIconMigrated( item, 'selected_icon' );
												if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.icon || migrated[ index ] ) ) { #>
													{{{ iconsHTML[ index ].value }}}
												<# } else { #>
													<i class="{{ item.icon }}" aria-hidden="true"></i>
												<# }
											#>
										<# } #>
									<# } else if ( item.icon_type == 'image' ) { #>
										<#
										var image = {
											id: item.image.id,
											url: item.image.url,
											size: settings.thumbnail_size,
											dimension: settings.thumbnail_custom_dimension,
											model: view.getEditModel()
										};
										var image_url = elementor.imagesManager.getImageUrl( image );
										#>
										<img src="{{{ image_url }}}" />
									<# } else if ( item.icon_type == 'text' ) { #>
										{{{ item.icon_text }}}
									<# } #>
								</span>
								<# if ( item.link_type == 'icon' ) { #>
									</a>
								<# } #>
							</div>
						<# } #>
						<div class="pp-info-box-content">
							<div class="pp-info-box-title-wrap">
								<#
									var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag ),
										subtitleHTMLTag = elementor.helpers.validateHTMLTag( settings.sub_title_html_tag );

									if ( item.title ) {
										#>
										<{{{ $title_container_tag }}} {{{ view.getRenderAttributeString( 'title-container' + i ) }}}>

										<{{{ titleHTMLTag }}} class="pp-info-box-title">
										{{ item.title }}
										</{{{ titleHTMLTag }}}>
										</{{{ $title_container_tag }}}>
										<#
									}

									if ( item.subtitle ) {
										#>
										<{{{ subtitleHTMLTag }}} class="pp-info-box-subtitle">
										{{ item.subtitle }}
										</{{{ subtitleHTMLTag }}}>
										<#
									}
								#>
							</div>

							<# if ( settings.divider_title_switch == 'yes' ) { #>
								<div class="pp-info-box-divider-wrap">
									<div class="pp-info-box-divider"></div>
								</div>
							<# } #>

							<# if ( item.description ) { #>
								<div class="pp-info-box-description">
									{{{ item.description }}}
								</div>
							<# } #>
							<# if ( item.link_type == 'button' || ( item.link_type == 'box' && item.button_visible == 'yes' ) ) { #>
								<div class="pp-info-box-footer">
									<{{{ $button_html_tag }}} {{{ view.getRenderAttributeString( 'info-box-button' ) }}}>
										<# if ( item.button_icon_position == 'before' ) { #>
											<# button_icon_template( item, index ); #>
										<# } #>
										<# if ( item.button_text ) { #>
											<span class="pp-button-text">
												{{ item.button_text }}
											</span>
										<# } #>
										<# if ( item.button_icon_position == 'after' ) { #>
											<# button_icon_template( item, index ); #>
										<# } #>
									</{{{ $button_html_tag }}}>
								</div>
							<# } #>
						</div>
					<# if ( item.link_type == 'box' ) { #>
						</a>
					<# } #>
				</div>
			<# i++ } ); #>
			<# if ( settings.layout == 'carousel' ) { #></div><# } #>
			<#
			if ( settings.layout == 'carousel' ) {
				dots_template();
				arrows_template();
			}
			#>
		</div>
		<?php
	}
}
