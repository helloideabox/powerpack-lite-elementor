<?php
namespace PowerpackElementsLite\Modules\Posts\Skins;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;
use PowerpackElementsLite\Modules\Posts\Module;
use PowerpackElementsLite\Classes\PP_Posts_Helper;
use PowerpackElementsLite\Group_Control_Transition;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Skin Base
 */
abstract class Skin_Base extends Elementor_Skin_Base {

	protected function _register_controls_actions() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore 
		add_action( 'elementor/element/pp-posts/section_skin_field/before_section_end', array( $this, 'register_layout_controls' ) );
		add_action( 'elementor/element/pp-posts/section_query/after_section_end', array( $this, 'register_controls' ) );
		add_action( 'elementor/element/pp-posts/section_query/after_section_end', array( $this, 'register_style_sections' ) );
	}

	public function register_style_sections( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_style_controls();
	}

	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_slider_controls();
		$this->register_filter_section_controls();
		$this->register_search_controls();
		$this->register_terms_controls();
		$this->register_image_controls();
		$this->register_title_controls();
		$this->register_excerpt_controls();
		$this->register_meta_controls();
		$this->register_button_controls();
		$this->register_pagination_controls();
		$this->register_content_order();
		$this->register_content_help_docs();
		$this->register_content_upgrade_pro_controls();
	}

	public function register_style_controls() {
		$this->register_style_layout_controls();
		$this->register_style_box_controls();
		$this->register_style_content_controls();
		$this->register_style_image_controls();
		$this->register_style_terms_controls();
		$this->register_style_title_controls();
		$this->register_style_excerpt_controls();
		$this->register_style_meta_controls();
		$this->register_style_button_controls();
		$this->register_style_pagination_controls();
		$this->register_style_arrows_controls();
		$this->register_style_dots_controls();
	}

	public function register_layout_controls( Widget_Base $widget ) {
		$this->parent = $widget;

		$this->register_layout_content_controls();
	}

	public function register_layout_content_controls() {

		$this->add_control(
			'layout',
			array(
				'label'   => esc_html__( 'Layout', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'grid'     => esc_html__( 'Grid', 'powerpack' ),
					'masonry'  => esc_html__( 'Masonry', 'powerpack' ),
					'carousel' => esc_html__( 'Carousel', 'powerpack' ),
				),
				'default' => 'grid',
			)
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
				'render_type'        => 'template',
			)
		);

		$this->add_control(
			'equal_height',
			array(
				'label'        => esc_html__( 'Equal Height', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'prefix_class' => 'pp-equal-height-',
				'render_type'  => 'template',
				'condition'    => array(
					$this->get_control_id( 'layout!' ) => 'masonry',
				),
			)
		);
	}

	public function register_slider_controls() {

		$this->start_controls_section(
			'section_slider_options',
			array(
				'label'     => esc_html__( 'Carousel Options', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'carousel_effect',
			[
				'label'              => esc_html__( 'Effect', 'powerpack' ),
				'description'        => esc_html__( 'Sets transition effect', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'slide',
				'options'            => [
					'slide' => esc_html__( 'Slide', 'powerpack' ),
					'fade'  => esc_html__( 'Fade', 'powerpack' ),
				],
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			]
		);

		$slides_per_view = range( 1, 10 );
		$slides_per_view = array_combine( $slides_per_view, $slides_per_view );

		$this->add_responsive_control(
			'slides_to_scroll',
			array(
				'type'               => Controls_Manager::SELECT,
				'label'              => esc_html__( 'Slides to Scroll', 'powerpack' ),
				'description'        => esc_html__( 'Set how many slides are scrolled per swipe.', 'powerpack' ),
				'options'            => $slides_per_view,
				'default'            => '1',
				'tablet_default'     => '1',
				'mobile_default'     => '1',
				'condition'          => array(
					$this->get_control_id( 'layout' )          => 'carousel',
					$this->get_control_id( 'carousel_effect' ) => 'slide',
					$this->get_control_id( 'center_mode!' )    => 'yes',
				),
			)
		);

		$this->add_control(
			'animation_speed',
			array(
				'label'              => esc_html__( 'Animation Speed', 'powerpack' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 600,
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'arrows',
			array(
				'label'              => esc_html__( 'Arrows', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => esc_html__( 'Yes', 'powerpack' ),
				'label_off'          => esc_html__( 'No', 'powerpack' ),
				'return_value'       => 'yes',
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'              => esc_html__( 'Dots', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => esc_html__( 'Yes', 'powerpack' ),
				'label_off'          => esc_html__( 'No', 'powerpack' ),
				'return_value'       => 'yes',
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'              => esc_html__( 'Autoplay', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => esc_html__( 'Yes', 'powerpack' ),
				'label_off'          => esc_html__( 'No', 'powerpack' ),
				'return_value'       => 'yes',
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'              => esc_html__( 'Autoplay Speed', 'powerpack' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 3000,
				'condition'          => array(
					$this->get_control_id( 'layout' )   => 'carousel',
					$this->get_control_id( 'autoplay' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'              => esc_html__( 'Pause on Hover', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => esc_html__( 'Yes', 'powerpack' ),
				'label_off'          => esc_html__( 'No', 'powerpack' ),
				'return_value'       => 'yes',
				'condition'          => array(
					$this->get_control_id( 'layout' )   => 'carousel',
					$this->get_control_id( 'autoplay' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'infinite_loop',
			array(
				'label'              => esc_html__( 'Infinite Loop', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => esc_html__( 'Yes', 'powerpack' ),
				'label_off'          => esc_html__( 'No', 'powerpack' ),
				'return_value'       => 'yes',
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'adaptive_height',
			array(
				'label'              => esc_html__( 'Adaptive Height', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => esc_html__( 'Yes', 'powerpack' ),
				'label_off'          => esc_html__( 'No', 'powerpack' ),
				'return_value'       => 'yes',
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'center_mode',
			[
				'label'                 => esc_html__( 'Center Mode', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'direction',
			array(
				'label'              => esc_html__( 'Direction', 'powerpack' ),
				'type'               => Controls_Manager::CHOOSE,
				'label_block'        => false,
				'toggle'             => false,
				'options'            => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'            => 'left',
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
				),
			)
		);

		$this->end_controls_section();
	}

	public function register_filter_section_controls() {

		$this->start_controls_section(
			'section_filters',
			array(
				'label'     => esc_html__( 'Filters', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'post_type!'                       => 'related',
					$this->get_control_id( 'layout!' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'show_filters',
			array(
				'label'        => esc_html__( 'Show Filters', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'post_type!'                       => 'related',
					$this->get_control_id( 'layout!' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'show_filters_notice',
			array(
				'label'           => '',
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This feature is available in PowerPack Pro.', 'powerpack' ) . ' ' . apply_filters( 'upgrade_powerpack_message', sprintf( esc_html__( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
				'content_classes' => 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				'condition'    => array(
					'post_type!'                            => 'related',
					$this->get_control_id( 'layout!' )      => 'carousel',
					$this->get_control_id( 'show_filters' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Search Form
	 *
	 * @since 1.4.11.0
	 * @access protected
	 */
	protected function register_search_controls() {

		$this->start_controls_section(
			'section_search_form',
			array(
				'label'     => esc_html__( 'Search Form', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->add_control(
			'show_ajax_search_form',
			array(
				'label'              => esc_html__( 'Show Search Form', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'label_on'           => esc_html__( 'Yes', 'powerpack' ),
				'label_off'          => esc_html__( 'No', 'powerpack' ),
				'return_value'       => 'yes',
				'condition'          => array(
					$this->get_control_id( 'layout' ) => array( 'grid', 'masonry' ),
				),
			)
		);

		$this->add_control(
			'show_ajax_search_form_notice',
			array(
				'label'           => '',
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This feature is available in PowerPack Pro.', 'powerpack' ) . ' ' . apply_filters( 'upgrade_powerpack_message', sprintf( esc_html__( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
				'content_classes' => 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				'condition'    => array(
					$this->get_control_id( 'layout' )                => array( 'grid', 'masonry' ),
					$this->get_control_id( 'show_ajax_search_form' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_terms_controls() {
		/**
		 * Content Tab: Post Terms
		 */
		$this->start_controls_section(
			'section_terms',
			array(
				'label'     => esc_html__( 'Post Terms', 'powerpack' ),
			)
		);

		$this->add_control(
			'post_terms',
			array(
				'label'        => esc_html__( 'Show Post Terms', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$post_types = PP_Posts_Helper::get_post_types();

		foreach ( $post_types as $post_type_slug => $post_type_label ) {

			$taxonomy = PP_Posts_Helper::get_post_taxonomies( $post_type_slug );

			if ( ! empty( $taxonomy ) ) {

				$related_tax = array();

				// Get all taxonomy values under the taxonomy.
				foreach ( $taxonomy as $index => $tax ) {

					$terms = get_terms( $index );

					$related_tax[ $index ] = $tax->label;
				}

				// Add control for all taxonomies.
				$this->add_control(
					'tax_badge_' . $post_type_slug,
					array(
						'label'     => esc_html__( 'Select Taxonomy', 'powerpack' ),
						'type'      => Controls_Manager::SELECT2,
						'options'   => $related_tax,
						'multiple'  => true,
						'default'   => array_keys( $related_tax )[0],
						'condition' => array(
							'post_type' => $post_type_slug,
							$this->get_control_id( 'post_terms' ) => 'yes',
						),
					)
				);
			}
		}

		$this->add_control(
			'max_terms',
			array(
				'label'       => esc_html__( 'Max Terms to Show', 'powerpack' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 1,
				'condition'   => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
				'label_block' => false,
			)
		);

		$this->add_control(
			'post_taxonomy_link',
			array(
				'label'        => esc_html__( 'Link to Taxonomy', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'post_terms_separator',
			array(
				'label'     => esc_html__( 'Terms Separator', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => ',',
				'selectors' => array(
					'{{WRAPPER}} .pp-post-terms > .pp-post-term:not(:last-child):after' => 'content: "{{UNIT}}";',
				),
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Image
	 */
	protected function register_image_controls() {
		$this->start_controls_section(
			'section_image',
			array(
				'label'     => esc_html__( 'Image', 'powerpack' ),
			)
		);

		$this->add_control(
			'show_thumbnail',
			array(
				'label'        => esc_html__( 'Show Image', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'thumbnail_link',
			array(
				'label'        => esc_html__( 'Link to Post', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_link_target',
			array(
				'label' => esc_html__( 'Open in a New Tab', 'powerpack' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
					$this->get_control_id( 'thumbnail_link' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_custom_height',
			array(
				'label'        => esc_html__( 'Custom Height', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'ratio',
				'prefix_class' => 'pp-posts-thumbnail-',
				'condition'    => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'thumbnail_ratio',
			array(
				'label'          => esc_html__( 'Image Ratio', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array(
					'size' => 1,
				),
				'tablet_default' => array(
					'size' => '',
				),
				'mobile_default' => array(
					'size' => 1,
				),
				'range'          => array(
					'px' => array(
						'min'  => 0.1,
						'max'  => 2,
						'step' => 0.01,
					),
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-posts-container .pp-post-thumbnail-wrap' => 'padding-bottom: calc( {{SIZE}} * 100% );',
				),
				'condition'      => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
					$this->get_control_id( 'thumbnail_custom_height!' ) => '',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'label'     => esc_html__( 'Image Size', 'powerpack' ),
				'default'   => 'large',
				'exclude'   => array( 'custom' ),
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_location',
			array(
				'label'     => esc_html__( 'Image Location', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'inside'  => esc_html__( 'Inside Content Container', 'powerpack' ),
					'outside' => esc_html__( 'Outside Content Container', 'powerpack' ),
				),
				'default'   => 'outside',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'fallback_image',
			array(
				'label'       => esc_html__( 'Fallback Image', 'powerpack' ),
				'description' => esc_html__( 'If a featured image is not available in post, it will display the first image from the post or default image placeholder or a custom image. You can choose None to do not display the fallback image.', 'powerpack' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'none'    => esc_html__( 'None', 'powerpack' ),
					'default' => esc_html__( 'Default', 'powerpack' ),
					'custom'  => esc_html__( 'Custom', 'powerpack' ),
				),
				'default'     => 'default',
				'condition'   => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'fallback_image_custom',
			array(
				'label'     => esc_html__( 'Fallback Image Custom', 'powerpack' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
					$this->get_control_id( 'fallback_image' ) => 'custom',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Title
	 */
	protected function register_title_controls() {
		$this->start_controls_section(
			'section_post_title',
			array(
				'label'     => esc_html__( 'Title', 'powerpack' ),
			)
		);

		$this->add_control(
			'post_title',
			array(
				'label'        => esc_html__( 'Post Title', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'post_title_link',
			array(
				'label'        => esc_html__( 'Link to Post', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'post_title_link_target',
			array(
				'label' => esc_html__( 'Open in a New Tab', 'powerpack' ),
				'type'  => Controls_Manager::SWITCHER,
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_link' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'     => esc_html__( 'HTML Tag', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => array(
					'h1'   => esc_html__( 'H1', 'powerpack' ),
					'h2'   => esc_html__( 'H2', 'powerpack' ),
					'h3'   => esc_html__( 'H3', 'powerpack' ),
					'h4'   => esc_html__( 'H4', 'powerpack' ),
					'h5'   => esc_html__( 'H5', 'powerpack' ),
					'h6'   => esc_html__( 'H6', 'powerpack' ),
					'div'  => esc_html__( 'div', 'powerpack' ),
					'span' => esc_html__( 'span', 'powerpack' ),
					'p'    => esc_html__( 'p', 'powerpack' ),
				),
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'post_title_separator',
			array(
				'label'        => esc_html__( 'Title Separator', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Excerpt
	 */
	protected function register_excerpt_controls() {
		$this->start_controls_section(
			'section_post_excerpt',
			array(
				'label'     => esc_html__( 'Content', 'powerpack' ),
			)
		);

		$this->add_control(
			'show_excerpt',
			array(
				'label'        => esc_html__( 'Show Content', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'content_type',
			array(
				'label'     => esc_html__( 'Content Type', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'excerpt',
				'options'   => array(
					'excerpt' => esc_html__( 'Excerpt', 'powerpack' ),
					'content' => esc_html__( 'Limited Content', 'powerpack' ),
					'full'    => esc_html__( 'Full Content', 'powerpack' ),
				),
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_length',
			array(
				'label'     => esc_html__( 'Excerpt Length', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 20,
				'min'       => 0,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
					$this->get_control_id( 'content_type' ) => 'excerpt',
				),
			)
		);

		$this->add_control(
			'content_length',
			array(
				'label'       => esc_html__( 'Content Length', 'powerpack' ),
				'title'       => esc_html__( 'Words', 'powerpack' ),
				'description' => esc_html__( 'Number of words to be displayed from the post content', 'powerpack' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 30,
				'min'         => 0,
				'step'        => 1,
				'condition'   => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
					$this->get_control_id( 'content_type' ) => 'content',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Meta
	 */
	protected function register_meta_controls() {
		$this->start_controls_section(
			'section_post_meta',
			array(
				'label'     => esc_html__( 'Meta', 'powerpack' ),
			)
		);

		$this->add_control(
			'post_meta',
			array(
				'label'        => esc_html__( 'Post Meta', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'post_meta_separator',
			array(
				'label'     => esc_html__( 'Post Meta Separator', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '-',
				'selectors' => array(
					'{{WRAPPER}} .pp-post-meta .pp-meta-separator:not(:last-child):after' => 'content: "{{UNIT}}";',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_post_author',
			array(
				'label'     => esc_html__( 'Post Author', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'show_author',
			array(
				'label'        => esc_html__( 'Show Post Author', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'author_link',
			array(
				'label'        => esc_html__( 'Link to Author', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_author' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'select_author_icon',
			array(
				'label'            => esc_html__( 'Author Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => $this->get_control_id( 'author_icon' ),
				'skin'             => 'inline',
				'recommended'      => [
					'fa-solid' => [
						'user',
						'user-circle',
						'user-tie',
					],
					'fa-regular' => [
						'user',
						'user-circle',
					],
				],
				'condition'        => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_author' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'author_prefix',
			array(
				'label'     => esc_html__( 'Prefix', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_author' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_post_date',
			array(
				'label'     => esc_html__( 'Post Date', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'        => esc_html__( 'Show Post Date', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'date_link',
			array(
				'label'        => esc_html__( 'Link to Post', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_date' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'     => esc_html__( 'Date Type', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''         => esc_html__( 'Published Date', 'powerpack' ),
					'ago'      => esc_html__( 'Time Ago', 'powerpack' ),
					'modified' => esc_html__( 'Last Modified Date', 'powerpack' ),
					'key'      => esc_html__( 'Custom Meta Key', 'powerpack' ),
				),
				'default'   => '',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_date' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'date_meta_key',
			array(
				'label'       => esc_html__( 'Custom Meta Key', 'powerpack' ),
				'description' => esc_html__( 'Display the post date stored in custom meta key.', 'powerpack' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => '',
				'ai'          => [
					'active' => false,
				],
				'condition'   => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_date' )   => 'yes',
					$this->get_control_id( 'date_format' ) => 'key',
				),
			)
		);

		$this->add_control(
			'date_format_select',
			array(
				'label'     => esc_html__( 'Date Format', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					''       => esc_html__( 'Default', 'powerpack' ),
					'F j, Y' => gmdate( 'F j, Y' ),
					'Y-m-d'  => gmdate( 'Y-m-d' ),
					'm/d/Y'  => gmdate( 'm/d/Y' ),
					'd/m/Y'  => gmdate( 'd/m/Y' ),
					'custom' => esc_html__( 'Custom', 'powerpack' ),
				),
				'default'   => '',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_date' ) => 'yes',
					$this->get_control_id( 'date_format' ) => [ '', 'modified', 'key' ],
				),
			)
		);

		$this->add_control(
			'date_custom_format',
			array(
				'label'       => esc_html__( 'Custom Format', 'powerpack' ),
				'description' => sprintf(
					/* translators: 1: Link opening tag, 2: 2: Link closing tag. */
					esc_html__( 'Refer to PHP date formats %1$shere%2$s', 'powerpack' ),
					sprintf( '<a href="%s" target="_blank">', 'https://wordpress.org/support/article/formatting-date-and-time/' ),
					'</a>'
				),
				'type'        => Controls_Manager::TEXT,
				'label_block' => false,
				'default'     => get_option( 'date_format' ),
				'ai'          => [
					'active' => false,
				],
				'condition'   => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_date' )   => 'yes',
					$this->get_control_id( 'date_format' ) => [ '', 'modified', 'key' ],
					$this->get_control_id( 'date_format_select' ) => 'custom',
				),
			)
		);

		$this->add_control(
			'select_date_icon',
			array(
				'label'            => esc_html__( 'Date Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => $this->get_control_id( 'date_icon' ),
				'skin'             => 'inline',
				'recommended'      => [
					'fa-solid' => [
						'calendar',
						'calendar-alt',
						'calendar-check',
					],
					'fa-regular' => [
						'calendar',
						'calendar-alt',
						'calendar-check',
					],
				],
				'condition'        => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_date' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'date_prefix',
			array(
				'label'     => esc_html__( 'Prefix', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_date' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'heading_post_comments',
			array(
				'label'     => esc_html__( 'Post Comments', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'show_comments',
			array(
				'label'        => esc_html__( 'Show Post Comments', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'hide_empty_comments',
			array(
				'label'        => esc_html__( 'Hide if Empty', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_comments' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'select_comments_icon',
			array(
				'label'            => esc_html__( 'Comments Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => false,
				'fa4compatibility' => $this->get_control_id( 'comments_icon' ),
				'skin'             => 'inline',
				'recommended'      => [
					'fa-solid' => [
						'comment',
						'comment-alt',
						'comments',
						'comment-dots',
					],
					'fa-regular' => [
						'comment',
						'comment-alt',
						'comments',
						'comment-dots',
					],
				],
				'condition'        => array(
					$this->get_control_id( 'post_meta' )     => 'yes',
					$this->get_control_id( 'show_comments' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_button_controls() {

		$this->start_controls_section(
			'section_button',
			array(
				'label' => esc_html__( 'Read More Button', 'powerpack' ),
			)
		);

		$this->add_control(
			'show_button',
			array(
				'label'        => esc_html__( 'Show Button', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'     => esc_html__( 'Button Text', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => esc_html__( 'Read More', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'select_button_icon',
			array(
				'label'            => esc_html__( 'Button Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => $this->get_control_id( 'button_icon' ),
				'condition'        => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after',
				'options'   => array(
					'after'  => esc_html__( 'After', 'powerpack' ),
					'before' => esc_html__( 'Before', 'powerpack' ),
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
					$this->get_control_id( 'select_button_icon[value]!' ) => '',
				),
			)
		);

		$this->add_control(
			'button_alignment',
			[
				'label'       => esc_html__( 'Automatically Align Buttons', 'powerpack' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Yes', 'powerpack' ),
				'label_off'   => esc_html__( 'No', 'powerpack' ),
				'default'     => '',
				'render_type' => 'template',
				'condition'   => [
					$this->get_control_id( 'layout!' ) => 'masonry',
					$this->get_control_id( 'show_button' ) => 'yes',
				],
			]
		);

		$this->add_control(
			'button_link_target',
			array(
				'label'     => esc_html__( 'Open in a New Tab', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	public function register_pagination_controls() {
		$this->start_controls_section(
			'section_pagination',
			array(
				'label'     => esc_html__( 'Pagination', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'pagination_type',
			array(
				'label'     => esc_html__( 'Pagination', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'                  => esc_html__( 'None', 'powerpack' ),
					'numbers'               => esc_html__( 'Numbers', 'powerpack' ),
					'numbers_and_prev_next' => esc_html__( 'Numbers', 'powerpack' ) . ' + ' . esc_html__( 'Previous/Next', 'powerpack' ),
					'load_more'             => esc_html__( 'Load More Button', 'powerpack' ),
					'infinite'              => esc_html__( 'Infinite', 'powerpack' ),
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
				),
			)
		);

		$this->add_control(
			'pagination_notice',
			array(
				'label'           => '',
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'This pagination option is available in PowerPack Pro.', 'powerpack' ) . ' ' . apply_filters( 'upgrade_powerpack_message', sprintf( esc_html__( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
				'content_classes' => 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				'condition'       => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array(
						'load_more',
						'infinite',
					),
				),
			)
		);

		$this->add_control(
			'pagination_position',
			array(
				'label'     => esc_html__( 'Pagination Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bottom',
				'options'   => array(
					'top'        => esc_html__( 'Top', 'powerpack' ),
					'bottom'     => esc_html__( 'Bottom', 'powerpack' ),
					'top-bottom' => esc_html__( 'Top', 'powerpack' ) . ' + ' . esc_html__( 'Bottom', 'powerpack' ),
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array(
						'numbers',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_ajax',
			array(
				'label'     => esc_html__( 'Ajax Pagination', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array(
						'numbers',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_page_limit',
			array(
				'label'     => esc_html__( 'Page Limit', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array(
						'numbers',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_numbers_shorten',
			array(
				'label'     => esc_html__( 'Shorten', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => '',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array(
						'numbers',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->add_control(
			'pagination_prev_label',
			array(
				'label'     => esc_html__( 'Previous Label', 'powerpack' ),
				'default'   => esc_html__( '&laquo; Previous', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'numbers_and_prev_next',
				),
			)
		);

		$this->add_control(
			'pagination_next_label',
			array(
				'label'     => esc_html__( 'Next Label', 'powerpack' ),
				'default'   => esc_html__( 'Next &raquo;', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => 'numbers_and_prev_next',
				),
			)
		);

		$this->add_control(
			'pagination_align',
			array(
				'label'     => esc_html__( 'Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination-wrap' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array(
						'numbers',
						'numbers_and_prev_next',
					),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Order
	 *
	 * @since 1.4.11.0
	 * @access protected
	 */
	protected function register_content_order() {

		$this->start_controls_section(
			'section_order',
			array(
				'label'     => esc_html__( 'Order', 'powerpack' ),
			)
		);

		$this->add_control(
			'content_parts_order_heading',
			array(
				'label' => esc_html__( 'Content Parts', 'powerpack' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'thumbnail_order',
			array(
				'label'     => esc_html__( 'Thumbnail', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
					$this->get_control_id( 'thumbnail_location' ) => 'inside',
				),
			)
		);

		$this->add_control(
			'terms_order',
			array(
				'label'     => esc_html__( 'Terms', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_order',
			array(
				'label'     => esc_html__( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_order',
			array(
				'label'     => esc_html__( 'Meta', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_order',
			array(
				'label'     => esc_html__( 'Excerpt', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_order',
			array(
				'label'     => esc_html__( 'Read More Button', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_order_heading',
			array(
				'label'     => esc_html__( 'Post Meta', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'author_order',
			array(
				'label'     => esc_html__( 'Author', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_meta' )   => 'yes',
					$this->get_control_id( 'show_author' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'date_order',
			array(
				'label'     => esc_html__( 'Date', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_date' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'comments_order',
			array(
				'label'     => esc_html__( 'Comments', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 10,
				'step'      => 1,
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
					$this->get_control_id( 'show_comments' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Help Docs
	 *
	 * @since 2.1.0
	 * @access protected
	 */
	protected function register_content_help_docs() {

		$help_docs = PP_Config::get_widget_help_links( 'Posts' );

		if ( ! empty( $help_docs ) ) {

			/**
			 * Content Tab: Help Docs
			 *
			 * @since 2.1.0
			 * @access protected
			 */
			$this->start_controls_section(
				'section_help_docs',
				array(
					'label' => esc_html__( 'Help Docs', 'powerpack' ),
				)
			);

			$hd_counter = 1;
			foreach ( $help_docs as $hd_title => $hd_link ) {
				$this->add_control(
					'help_doc_' . $hd_counter,
					array(
						'type'            => Controls_Manager::RAW_HTML,
						'raw'             => sprintf( '%1$s ' . $hd_title . ' %2$s', '<a href="' . $hd_link . '" target="_blank" rel="noopener">', '</a>' ),
						'content_classes' => 'pp-editor-doc-links',
					)
				);

				$hd_counter++;
			}

			$this->end_controls_section();
		}
	}

	/**
	 * Content Tab: Upgrade pro section
	 *
	 * @since 2.1.0
	 * @access protected
	 */
	protected function register_content_upgrade_pro_controls() {
		if ( ! is_pp_elements_active() ) {
			$this->start_controls_section(
				'section_upgrade_powerpack',
				array(
					'label' => apply_filters( 'upgrade_powerpack_title', esc_html__( 'Get PowerPack Pro', 'powerpack' ) ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'upgrade_powerpack_notice',
				array(
					'label'           => '',
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => apply_filters( 'upgrade_powerpack_message', sprintf( esc_html__( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
					'content_classes' => 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->end_controls_section();
		}
	}

	/*-----------------------------------------------------------------------------------*/
	/*	STYLE TAB
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Style Tab: Layout
	 */
	protected function register_style_layout_controls() {

		$this->start_controls_section(
			'section_layout_style',
			array(
				'label' => esc_html__( 'Layout', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'posts_horizontal_spacing',
			array(
				'label'      => esc_html__( 'Column Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 25,
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--grid-column-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .pp-posts:not(.elementor-grid)' => 'margin-left: -{{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .pp-posts:not(.elementor-grid) .pp-post-wrap' => 'padding-left: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_responsive_control(
			'posts_vertical_spacing',
			array(
				'label'      => esc_html__( 'Row Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 25,
				),
				'selectors'  => array(
					'{{WRAPPER}}' => '--grid-row-gap: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .pp-elementor-grid .pp-grid-item-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Box
	 */
	protected function register_style_box_controls() {
		$this->start_controls_section(
			'section_post_box_style',
			array(
				'label' => esc_html__( 'Box', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_post_box_style' );

		$this->start_controls_tab(
			'tab_post_box_normal',
			array(
				'label' => esc_html__( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'post_box_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'post_box_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-post',
			)
		);

		$this->add_responsive_control(
			'post_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'post_box_shadow',
				'selector' => '{{WRAPPER}} .pp-post',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_post_box_hover',
			array(
				'label' => esc_html__( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'post_box_bg_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'post_box_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'post_box_shadow_hover',
				'selector' => '{{WRAPPER}} .pp-post:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Content Container
	 */
	protected function register_style_content_controls() {
		$this->start_controls_section(
			'section_post_content_style',
			array(
				'label' => esc_html__( 'Content Container', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'post_content_align',
			array(
				'label'       => esc_html__( 'Alignment', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} .pp-post-content-wrap' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'post_content_bg',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post-content-wrap' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_content_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-content-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'post_content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Image
	 */
	protected function register_style_image_controls() {
		$this->start_controls_section(
			'section_image_style',
			array(
				'label'     => esc_html__( 'Image', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'img_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-thumbnail, {{WRAPPER}} .pp-post-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'    => array(
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab(
			'normal',
			array(
				'label'     => esc_html__( 'Normal', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'image_box_shadow',
				'selector' => '{{WRAPPER}} .pp-post-thumbnail img',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'      => 'thumbnail_filters',
				'selector'  => '{{WRAPPER}} .pp-post-thumbnail img',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Transition::get_type(),
			array(
				'name'      => 'image_transition',
				'selector'  => '{{WRAPPER}} .pp-post-thumbnail img',
				'separator' => '',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'hover',
			array(
				'label'     => esc_html__( 'Hover', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'image_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .pp-post:hover .pp-post-thumbnail img',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			array(
				'name'      => 'thumbnail_hover_filters',
				'selector'  => '{{WRAPPER}} .pp-post:hover .pp-post-thumbnail img',
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'thumbnail_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'powerpack' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					$this->get_control_id( 'show_thumbnail' ) => 'yes',
				),
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Title
	 */
	protected function register_style_title_controls() {
		$this->start_controls_section(
			'section_title_style',
			array(
				'label'     => esc_html__( 'Title', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .pp-post-title, {{WRAPPER}} .pp-post-title a' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_color_hover',
			array(
				'label'     => esc_html__( 'Hover Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .pp-post-title a:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_link' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'title_typography',
				'label'     => esc_html__( 'Typography', 'powerpack' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'  => '{{WRAPPER}} .pp-post-title',
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'      => 'title_text_shadow',
				'selector'  => '{{WRAPPER}} .pp-post-title',
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			]
		);

		$this->add_responsive_control(
			'title_margin_bottom',
			array(
				'label'      => esc_html__( 'Bottom Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 10,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_title' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_separator_heading',
			array(
				'label'     => esc_html__( 'Separator', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'title_separator_align',
			array(
				'label'       => esc_html__( 'Alignment', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'flex-start'   => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'  => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'     => '',
				'selectors'   => array(
					'{{WRAPPER}} .pp-post-separator-wrap' => 'justify-content: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'title_separator_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pp-post-separator',
				'exclude'   => array(
					'image',
				),
				'condition' => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'title_separator_height',
			array(
				'label'      => esc_html__( 'Separator Height', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 1,
				),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 20,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-separator' => 'height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'title_separator_width',
			array(
				'label'      => esc_html__( 'Separator Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'range'      => array(
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
					'px' => array(
						'min'  => 10,
						'max'  => 200,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-separator' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'title_separator_margin_bottom',
			array(
				'label'      => esc_html__( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 15,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-separator-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_title' ) => 'yes',
					$this->get_control_id( 'post_title_separator' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Post Terms
	 */
	protected function register_style_terms_controls() {
		$this->start_controls_section(
			'section_terms_style',
			array(
				'label'     => esc_html__( 'Post Terms', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'terms_typography',
				'label'     => esc_html__( 'Typography', 'powerpack' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector'  => '{{WRAPPER}} .pp-post-terms',
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'terms_margin_bottom',
			array(
				'label'      => esc_html__( 'Bottom Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 10,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-terms-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'terms_gap',
			array(
				'label'      => esc_html__( 'Terms Gap', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 5,
				),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-terms .pp-post-term:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'terms_style_tabs' );

		$this->start_controls_tab(
			'terms_style_normal',
			array(
				'label' => esc_html__( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'terms_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-post-terms' => 'background: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'terms_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post-terms' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'terms_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-terms' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'terms_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-terms' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'terms_style_hover',
			array(
				'label' => esc_html__( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'terms_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-post-terms:hover' => 'background: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'terms_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post-terms a:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_terms' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Content
	 */
	protected function register_style_excerpt_controls() {
		$this->start_controls_section(
			'section_excerpt_style',
			array(
				'label'     => esc_html__( 'Content', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .pp-post-excerpt' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'excerpt_typography',
				'label'     => esc_html__( 'Typography', 'powerpack' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector'  => '{{WRAPPER}} .pp-post-excerpt',
				'condition' => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'excerpt_margin_bottom',
			array(
				'label'      => esc_html__( 'Bottom Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_excerpt' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Meta
	 */
	protected function register_style_meta_controls() {

		$this->start_controls_section(
			'section_meta_style',
			array(
				'label'     => esc_html__( 'Meta', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post-meta' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_links_color',
			array(
				'label'     => esc_html__( 'Links Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post-meta a' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_links_color_hover',
			array(
				'label'     => esc_html__( 'Links Hover Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post-meta a:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'meta_typography',
				'label'     => esc_html__( 'Typography', 'powerpack' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector'  => '{{WRAPPER}} .pp-post-meta, {{WRAPPER}} .pp-post-meta a',
				'condition' => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_items_spacing',
			array(
				'label'      => esc_html__( 'Meta Items Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 5,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-meta .pp-meta-separator:not(:last-child)' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2); margin-right: calc({{SIZE}}{{UNIT}} / 2);',
				),
				'condition'  => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_margin_bottom',
			array(
				'label'      => esc_html__( 'Bottom Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'post_meta' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Style Tab: Button
	 */
	protected function register_style_button_controls() {

		$this->start_controls_section(
			'section_button_style',
			array(
				'label'     => esc_html__( 'Read More Button', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'     => esc_html__( 'Size', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'sm',
				'options'   => array(
					'xs' => esc_html__( 'Extra Small', 'powerpack' ),
					'sm' => esc_html__( 'Small', 'powerpack' ),
					'md' => esc_html__( 'Medium', 'powerpack' ),
					'lg' => esc_html__( 'Large', 'powerpack' ),
					'xl' => esc_html__( 'Extra Large', 'powerpack' ),
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'label'     => esc_html__( 'Typography', 'powerpack' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'  => '{{WRAPPER}} .pp-posts-button',
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => esc_html__( 'Normal', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-button' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_text_color_normal',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-button' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_normal',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-posts-button',
				'condition'   => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => esc_html__( 'Margin', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-posts-button',
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'read_more_button_icon_style_heading',
			array(
				'label'     => esc_html__( 'Button Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
					$this->get_control_id( 'select_button_icon[value]!' ) => '',
				),
			)
		);

		$this->add_control(
			'button_icon_spacing',
			array(
				'label'      => esc_html__( 'Icon Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'show_button' ) => 'yes',
					$this->get_control_id( 'select_button_icon[value]!' ) => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => esc_html__( 'Hover', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-button:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-button:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-button:hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'button_animation',
			array(
				'label'     => esc_html__( 'Animation', 'powerpack' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .pp-posts-button:hover',
				'condition' => array(
					$this->get_control_id( 'show_button' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function register_style_arrows_controls() {
		$this->start_controls_section(
			'section_arrows_style',
			array(
				'label'     => esc_html__( 'Arrows', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
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
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'arrows_size',
			array(
				'label'      => esc_html__( 'Arrows Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array( 'size' => '22' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-slider-arrow' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'arrows_position',
			array(
				'label'      => esc_html__( 'Align Arrows', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 50,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			array(
				'label'     => esc_html__( 'Normal', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_bg_color_normal',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-slider-arrow' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_color_normal',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-slider-arrow' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'arrows_border_normal',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-slider-arrow',
				'condition'   => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'arrows_border_radius_normal',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-slider-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			array(
				'label'     => esc_html__( 'Hover', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-slider-arrow:hover' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-slider-arrow:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->add_control(
			'arrows_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-slider-arrow:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-slider-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'arrows' ) => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	public function register_style_dots_controls() {
		$this->start_controls_section(
			'section_dots_style',
			array(
				'label'     => esc_html__( 'Dots', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
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
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			]
		);

		$this->add_responsive_control(
			'dots_size',
			array(
				'label'      => esc_html__( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 40,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'dots_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 30,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

		$this->start_controls_tab(
			'tab_dots_normal',
			array(
				'label'     => esc_html__( 'Normal', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_color_normal',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'background: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'dots_border_normal',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
				'condition'   => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'dots_border_radius_normal',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'dots_margin',
			array(
				'label'              => esc_html__( 'Margin', 'powerpack' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'allowed_dimensions' => 'vertical',
				'placeholder'        => array(
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				),
				'selectors'          => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'          => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_active',
			array(
				'label'     => esc_html__( 'Active', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_color_active',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			array(
				'label'     => esc_html__( 'Hover', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->add_control(
			'dots_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout' ) => 'carousel',
					$this->get_control_id( 'dots' )   => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	public function register_style_pagination_controls() {
		$this->start_controls_section(
			'section_pagination_style',
			array(
				'label'     => esc_html__( 'Pagination', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type!' ) => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_margin_top',
			array(
				'label'      => esc_html__( 'Gap between Posts & Pagination', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => '',
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-pagination-top .pp-posts-pagination' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-posts-pagination-bottom .pp-posts-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'pagination_typography',
				'selector'  => '{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a',
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->start_controls_tabs( 'tabs_pagination' );

		$this->start_controls_tab(
			'tab_pagination_normal',
			array(
				'label'     => esc_html__( 'Normal', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_normal',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'pagination_link_border_normal',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a',
				'condition'   => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_responsive_control(
			'pagination_link_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_responsive_control(
			'pagination_link_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pagination_link_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_hover',
			array(
				'label'     => esc_html__( 'Hover', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination a:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination a:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination a:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pagination_link_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .pp-posts-pagination a:hover',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_active',
			array(
				'label'     => esc_html__( 'Active', 'powerpack' ),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_active',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers.current' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_color_active',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers.current' => 'color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers.current' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pagination_link_box_shadow_active',
				'selector'  => '{{WRAPPER}} .pp-posts-pagination .page-numbers.current',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pagination_spacing',
			array(
				'label'      => esc_html__( 'Space Between', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'separator'  => 'before',
				'default'    => array(
					'size' => 10,
				),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'body:not(.rtl) {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
				'condition'  => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'heading_loader',
			array(
				'label'     => esc_html__( 'Loader', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'load_more', 'infinite' ),
				),
			)
		);

		$this->add_control(
			'loader_color',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-loader:after, {{WRAPPER}} .pp-posts-loader:after' => 'border-bottom-color: {{VALUE}}; border-top-color: {{VALUE}};',
				),
				'condition' => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'load_more', 'infinite' ),
				),
			)
		);

		$this->add_responsive_control(
			'loader_size',
			array(
				'label'      => esc_html__( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 10,
						'max'  => 80,
						'step' => 1,
					),
				),
				'default'    => array(
					'size' => 46,
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-loader' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					$this->get_control_id( 'layout!' ) => 'carousel',
					$this->get_control_id( 'pagination_type' ) => array( 'load_more', 'infinite' ),
				),
			)
		);

		$this->end_controls_section();
	}

	public function get_avatar_size( $size = 'sm' ) {

		if ( 'xs' === $size ) {
			$value = 30;
		} elseif ( 'sm' === $size ) {
			$value = 60;
		} elseif ( 'md' === $size ) {
			$value = 120;
		} elseif ( 'lg' === $size ) {
			$value = 180;
		} elseif ( 'xl' === $size ) {
			$value = 240;
		} else {
			$value = 60;
		}

		return $value;
	}

	/**
	 * Get Masonry classes array.
	 *
	 * Returns the Masonry classes array.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function get_masonry_classes() {
		$settings = $this->parent->get_settings_for_display();

		$post_type = $settings['post_type'];

		$filter_by = $this->get_instance_value( 'tax_' . $post_type . '_filter' );

		$taxonomies = wp_get_post_terms( get_the_ID(), $filter_by );
		$class      = array();

		if ( count( $taxonomies ) > 0 ) {

			foreach ( $taxonomies as $taxonomy ) {

				if ( is_object( $taxonomy ) ) {

					$class[] = $taxonomy->slug;
				}
			}
		}

		return implode( ' ', $class );
	}

	/**
	 * Render post terms output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_terms() {
		$settings   = $this->parent->get_settings_for_display();
		$post_terms = $this->get_instance_value( 'post_terms' );
		$query_type = $settings['query_type'];

		if ( 'yes' !== $post_terms ) {
			return;
		}

		$post_type = $settings['post_type'];

		if ( 'related' === $settings['post_type'] || 'main' === $query_type ) {
			$post_type = get_post_type();
		}

		$taxonomies = $this->get_instance_value( 'tax_badge_' . $post_type );

		$terms = array();

		if ( is_array( $taxonomies ) ) {
			foreach ( $taxonomies as $taxonomy ) {
				$terms_tax = wp_get_post_terms( get_the_ID(), $taxonomy );
				$terms     = array_merge( $terms, $terms_tax );
			}
		} else {
			$terms = wp_get_post_terms( get_the_ID(), $taxonomies );
		}

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return;
		}

		$max_terms = $this->get_instance_value( 'max_terms' );

		if ( $max_terms ) {
			$terms = array_slice( $terms, 0, $max_terms );
		}

		$terms = apply_filters( 'ppe_posts_terms', $terms );

		$link_terms = $this->get_instance_value( 'post_taxonomy_link' );

		if ( 'yes' === $link_terms ) {
			$format = '<span class="pp-post-term"><a href="%2$s">%1$s</a></span>';
		} else {
			$format = '<span class="pp-post-term">%1$s</span>';
		}
		?>
		<?php do_action( 'ppe_before_single_post_terms', get_the_ID(), $settings ); ?>
		<div class="pp-post-terms-wrap">
			<span class="pp-post-terms">
				<?php
				foreach ( $terms as $term ) {
					printf( wp_kses_post( $format ), esc_attr( $term->name ), esc_url( get_term_link( (int) $term->term_id ) ) );
				}

				do_action( 'ppe_single_post_terms', get_the_ID(), $settings );
				?>
			</span>
		</div>
		<?php do_action( 'ppe_after_single_post_terms', get_the_ID(), $settings ); ?>
		<?php
	}

	/**
	 * Render post meta output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_meta_item( $item_type = '' ) {
		$skin     = $this->get_id();
		$settings = $this->parent->get_settings_for_display();

		if ( '' === $item_type ) {
			return;
		}

		$show_item           = $this->get_instance_value( 'show_' . $item_type );
		$item_link           = $this->get_instance_value( $item_type . '_link' );
		$item_prefix         = $this->get_instance_value( $item_type . '_prefix' );
		$hide_empty_comments = $this->get_instance_value( 'hide_empty_comments' );

		if ( 'yes' !== $show_item ) {
			return;
		}

		if ( 'comments' === $item_type && 'yes' === $hide_empty_comments && '0' === get_comments_number() ) {
			return;
		}

		$item_icon        = $this->get_instance_value( $item_type . '_icon' );
		$select_item_icon = $this->get_instance_value( 'select_' . $item_type . '_icon' );

		$migrated = isset( $settings['__fa4_migrated'][ $skin . '_select_' . $item_type . '_icon' ] );
		$is_new   = empty( $settings[ $skin . '_' . $item_type . '_icon' ] ) && Icons_Manager::is_migration_allowed();
		?>
		<?php do_action( 'ppe_before_single_post_' . $item_type, get_the_ID(), $settings ); ?>
		<span class="pp-post-<?php echo esc_attr( $item_type ); ?>">
			<?php
			if ( $item_icon || ! empty( $select_item_icon['value'] ) ) { ?>
				<span class="pp-icon">
				<?php
				if ( $is_new || $migrated ) {
					Icons_Manager::render_icon( $select_item_icon, array( 'class' => 'pp-meta-icon', 'aria-hidden' => 'true' ) );
				} else { ?>
					<span class="pp-meta-icon <?php echo esc_attr( $item_icon ); ?>" aria-hidden="true"></span>
					<?php
				} ?>
				</span>
				<?php
			}

			if ( $item_prefix ) {
				?>
				<span class="pp-meta-prefix">
				<?php
					echo esc_attr( $item_prefix );
				?>
				</span>
				<?php
			}
			?>
			<span class="pp-meta-text">
				<?php
				if ( 'author' === $item_type ) {
					echo wp_kses_post( $this->get_post_author( $item_link ) );
				} elseif ( 'date' === $item_type ) {
					if ( PP_Helper::is_tribe_events_post( get_the_ID() ) && function_exists( 'tribe_get_start_date' ) ) {
						$date_format = $this->get_instance_value( 'date_format_select' );
						$date_custom_format = $this->get_instance_value( 'date_custom_format' );

						if ( 'custom' === $date_format && $date_custom_format ) {
							$date_format = $date_custom_format;
						}

						$post_date = tribe_get_start_time( get_the_ID(), $date_format );
					} else {
						$post_date = $this->get_post_date();
					}

					if ( 'yes' === $item_link ) {
						echo '<a href="' . esc_url( get_permalink() ) . '">' . wp_kses_post( $post_date ) . '</a>';
					} else {
						echo wp_kses_post( $post_date );
					}
				} elseif ( 'comments' === $item_type ) {
					echo wp_kses_post( $this->get_post_comments() );
				}
				?>
			</span>
		</span>
		<span class="pp-meta-separator"></span>
		<?php do_action( 'ppe_after_single_post_' . $item_type, get_the_ID(), $settings ); ?>
		<?php
	}

	/**
	 * Get post author
	 *
	 * @access protected
	 */
	protected function get_post_author( $author_link = '' ) {
		if ( 'yes' === $author_link ) {
			return get_the_author_posts_link();
		} else {
			return get_the_author();
		}
	}

	/**
	 * Get post author
	 *
	 * @access protected
	 */
	protected function get_post_comments() {
		/**
		 * Comments Filter
		 *
		 * Filters the output for comments
		 *
		 * @since 1.4.11.0
		 * @param string    $comments       The original text
		 * @param int       get_the_id()    The post ID
		 */
		$comments = get_comments_number_text();
		$comments = apply_filters( 'ppe_posts_comments', $comments, get_the_ID() );
		return $comments;
	}

	/**
	 * Get post date
	 *
	 * @access protected
	 */
	protected function get_post_date( $date_link = '' ) {
		$date_type = $this->get_instance_value( 'date_format' );
		$date_format = $this->get_instance_value( 'date_format_select' );
		$date_custom_format = $this->get_instance_value( 'date_custom_format' );
		$date = '';

		if ( 'custom' === $date_format && $date_custom_format ) {
			$date_format = $date_custom_format;
		}

		if ( 'ago' === $date_type ) {
			$date = sprintf( _x( '%s ago', '%s = human-readable time difference', 'powerpack' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
		} elseif ( 'modified' === $date_type ) {
			$date = get_the_modified_date( $date_format, get_the_ID() );
		} elseif ( 'key' === $date_type ) {
			$date_meta_key = $this->get_instance_value( 'date_meta_key' );

			if ( $date_meta_key ) {
				$date = get_post_meta( get_the_ID(), $date_meta_key, 'true' );
			}

			if ( $date ) {
				$date = date( $date_format, strtotime( $date ) );
			}
		} else {
			$date = get_the_date( $date_format );
		}

		if ( '' === $date ) {
			$date = get_the_date( $date_format );
		}

		return apply_filters( 'ppe_posts_date', $date, get_the_ID() );
	}

	/**
	 * Render post thumbnail output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function get_post_thumbnail() {
		$settings              = $this->parent->get_settings_for_display();
		$image                 = $this->get_instance_value( 'show_thumbnail' );
		$hover_animation       = $this->get_instance_value( 'thumbnail_hover_animation' );
		$fallback_image        = $this->get_instance_value( 'fallback_image' );
		$fallback_image_custom = $this->get_instance_value( 'fallback_image_custom' );
		$post_type_name        = $settings['post_type'];

		if ( 'yes' !== $image ) {
			return;
		}

		if ( has_post_thumbnail() || 'attachment' === $post_type_name ) {

			if ( 'attachment' === $post_type_name ) {
				$image_id = get_the_ID();
			} else {
				$image_id = get_post_thumbnail_id( get_the_ID() );
			}

			$setting_key              = $this->get_control_id( 'thumbnail' );
			$settings[ $setting_key ] = array(
				'id' => $image_id,
			);

			if ( $hover_animation ) {
				$settings[ 'hover_animation' ] = $hover_animation;
			}

			$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		} elseif ( 'default' === $fallback_image ) {

			$image_class    = ! empty( $hover_animation ) ? 'elementor-animation-' . $hover_animation : '';
			$thumbnail_url  = Utils::get_placeholder_image_src();
			$thumbnail_html = '<img class="' . esc_attr( $image_class ) . '" src="' . $thumbnail_url . '"/>';

		} elseif ( 'custom' === $fallback_image ) {

			$custom_image_id          = $fallback_image_custom['id'];
			$setting_key              = $this->get_control_id( 'thumbnail' );
			$settings[ $setting_key ] = array(
				'id' => $custom_image_id,
			);

			if ( $hover_animation ) {
				$settings[ 'hover_animation' ] = $hover_animation;
			}
			$thumbnail_html = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		}

		if ( empty( $thumbnail_html ) ) {
			return;
		}

		return $thumbnail_html;
	}

	/**
	 * Render post title output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_post_title() {
		$settings = $this->parent->get_settings_for_display();

		$show_post_title      = $this->get_instance_value( 'post_title' );
		$title_tag            = PP_Helper::validate_html_tag( $this->get_instance_value( 'title_html_tag' ) );
		$title_link           = $this->get_instance_value( 'post_title_link' );
		$title_link_key       = 'title-link-' . get_the_ID();
		$title_link_target    = $this->get_instance_value( 'post_title_link_target' );
		$post_title_separator = $this->get_instance_value( 'post_title_separator' );

		if ( 'yes' !== $show_post_title ) {
			return;
		}

		$post_title = get_the_title();
		/**
		 * Post Title Filter
		 *
		 * Filters post title
		 *
		 * @since 1.4.11.0
		 * @param string    $post_title     The original text
		 * @param int       get_the_id()    The post ID
		 */
		$post_title = apply_filters( 'ppe_posts_title', $post_title, get_the_ID() );
		if ( $post_title ) {
			?>
			<?php do_action( 'ppe_before_single_post_title', get_the_ID(), $settings ); ?>
			<<?php PP_Helper::print_validated_html_tag( $title_tag ); ?> class="pp-post-title">
				<?php
				if ( 'yes' === $title_link ) {
					$title_link_atts = array();

					$title_link_atts['href'] = apply_filters( 'ppe_posts_title_link', get_the_permalink(), get_the_ID() );

					if ( 'yes' === $title_link_target ) {
						$title_link_atts['target'] = '_blank';
					}

					$title_link_atts = apply_filters( 'ppe_posts_title_link_atts', $title_link_atts, $title_link_atts );

					$this->parent->add_render_attribute( $title_link_key, $title_link_atts );

					$post_title = '<a ' . $this->parent->get_render_attribute_string( $title_link_key ) . '>' . $post_title . '</a>';
				}

				echo wp_kses_post( $post_title );
				?>
			</<?php PP_Helper::print_validated_html_tag( $title_tag ); ?>>
			<?php
			if ( 'yes' === $post_title_separator ) {
				?>
				<div class="pp-post-separator-wrap">
					<div class="pp-post-separator"></div>
				</div>
				<?php
			}
		}

		do_action( 'ppe_after_single_post_title', get_the_ID(), $settings );
	}

	/**
	 * Render post thumbnail output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_post_thumbnail() {
		$settings = $this->parent->get_settings_for_display();

		$image_wrap_tag = 'div';
		$image_wrap_key = 'image-wrap-' . get_the_ID();
		$image_link     = $this->get_instance_value( 'thumbnail_link' );
		$thumbnail_html = $this->get_post_thumbnail();

		if ( empty( $thumbnail_html ) ) {
			return;
		}

		$this->parent->add_render_attribute( $image_wrap_key, 'class', 'pp-post-thumbnail-wrap' );

		if ( 'yes' === $image_link ) {
			$image_link_atts   = array();
			$image_wrap_tag    = 'a';
			$image_link_target = $this->get_instance_value( 'thumbnail_link_target' );

			$image_link_atts['href'] = apply_filters( 'ppe_posts_image_link', get_the_permalink(), get_the_ID() );

			if ( 'yes' === $image_link_target ) {
				$image_link_atts['target'] = '_blank';
			}

			$image_link_atts['title'] = the_title_attribute( 'echo=0' );

			$image_link_atts = apply_filters( 'ppe_posts_image_link_atts', $image_link_atts, $image_link_atts );

			$this->parent->add_render_attribute( $image_wrap_key, $image_link_atts );
		}

		do_action( 'ppe_before_single_post_thumbnail', get_the_ID(), $settings );
		?>
		<div class="pp-post-thumbnail">
			<<?php PP_Helper::print_validated_html_tag( $image_wrap_tag ); ?> <?php $this->parent->print_render_attribute_string( $image_wrap_key ) ?>>
				<?php echo wp_kses_post( $thumbnail_html ); ?>
			</<?php PP_Helper::print_validated_html_tag( $image_wrap_tag ); ?>>
		</div>
		<?php
		do_action( 'ppe_after_single_post_thumbnail', get_the_ID(), $settings );
	}

	/**
	 * Get post excerpt length.
	 *
	 * Returns the length of post excerpt.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function pp_excerpt_length_filter() {
		return $this->get_instance_value( 'excerpt_length' );
	}

	/**
	 * Get post excerpt with limited words.
	 *
	 * Returns the excerpt with limit.
	 *
	 * @since 2.7.11
	 * @access public
	 */
	public function pp_post_excerpt() {
		if ( ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
			$limit = $this->pp_excerpt_length_filter();
		} else {
			$limit = apply_filters( 'excerpt_length', 20 );
		}
		$excerpt = explode( ' ', get_the_excerpt(), $limit + 1 );
		$excerpt_more = apply_filters( 'excerpt_more', 20 );

		if ( count( $excerpt ) >= $limit ) {
			array_pop( $excerpt );
			$excerpt = implode( ' ', $excerpt ) . $excerpt_more;
		} else {
			$excerpt = implode( ' ', $excerpt );
		}

		$excerpt = preg_replace( '`\[[^\]]*\]`', '', $excerpt );

		return wpautop( $excerpt );
	}

	/**
	 * Get post excerpt end text.
	 *
	 * Returns the string to append to post excerpt.
	 *
	 * @param string $more returns string.
	 * @since 1.7.0
	 * @access public
	 */
	public function pp_excerpt_more_filter( $more ) {
		return ' ...';
	}

	/**
	 * Render post content.
	 *
	 * @param boolean     $with_wrapper - Whether to wrap the content with a div.
	 * @param boolean     $with_css - Decides whether to print inline CSS before the post content.
	 *
	 * @return void
	 */
	public function render_post_content( $with_wrapper = false, $with_css = true ) {
		static $did_posts = [];
		static $level = 0;

		$post = get_post();

		if ( post_password_required( $post->ID ) ) {
			// PHPCS - `get_the_password_form`. is safe.
			echo get_the_password_form( $post->ID ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			return;
		}

		// Avoid recursion
		if ( isset( $did_posts[ $post->ID ] ) ) {
			return;
		}

		$level++;
		$did_posts[ $post->ID ] = true;
		// End avoid recursion

		$editor = PP_Helper::elementor()->editor;
		$is_edit_mode = $editor->is_edit_mode();

		if ( PP_Helper::elementor()->preview->is_preview_mode( $post->ID ) ) {
			$content = PP_Helper::elementor()->preview->builder_wrapper( '' ); // XSS ok
		} else {
			// Set edit mode as false, so don't render settings and etc. use the $is_edit_mode to indicate if we need the CSS inline
			$editor->set_edit_mode( false );

			// Print manually (and don't use `the_content()`) because it's within another `the_content` filter, and the Elementor filter has been removed to avoid recursion.
			$content = PP_Helper::elementor()->frontend->get_builder_content( $post->ID, $with_css );

			PP_Helper::elementor()->frontend->remove_content_filter();

			if ( empty( $content ) ) {
				// Split to pages.
				setup_postdata( $post );

				/** This filter is documented in wp-includes/post-template.php */
				// PHPCS - `get_the_content` is safe.
				echo apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

				wp_link_pages( [
					'before' => '<div class="page-links elementor-page-links"><span class="page-links-title elementor-page-links-title">' . esc_html__( 'Pages:', 'powerpack' ) . '</span>',
					'after' => '</div>',
					'link_before' => '<span>',
					'link_after' => '</span>',
					'pagelink' => '<span class="screen-reader-text">' . esc_html__( 'Page', 'powerpack' ) . ' </span>%',
					'separator' => '<span class="screen-reader-text">, </span>',
				] );

				PP_Helper::elementor()->frontend->add_content_filter();

				$level--;

				// Restore edit mode state
				PP_Helper::elementor()->editor->set_edit_mode( $is_edit_mode );

				return;
			} else {
				PP_Helper::elementor()->frontend->remove_content_filters();
				$content = apply_filters( 'the_content', $content );
				PP_Helper::elementor()->frontend->restore_content_filters();
			}
		} // End if().

		// Restore edit mode state
		PP_Helper::elementor()->editor->set_edit_mode( $is_edit_mode );

		if ( $with_wrapper ) {
			echo '<div class="elementor-post__content">' . balanceTags( $content, true ) . '</div>';  // XSS ok.
		} else {
			echo $content; // XSS ok.
		}

		$level--;

		if ( 0 === $level ) {
			$did_posts = [];
		}
	}

	/**
	 * Get post excerpt.
	 *
	 * Returns the post excerpt HTML wrap.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_excerpt() {
		$settings       = $this->parent->get_settings_for_display();
		$show_excerpt   = $this->get_instance_value( 'show_excerpt' );
		$excerpt_length = $this->get_instance_value( 'excerpt_length' );
		$content_type   = $this->get_instance_value( 'content_type' );
		$content_length = $this->get_instance_value( 'content_length' );

		if ( 'yes' !== $show_excerpt ) {
			return;
		}

		if ( 'excerpt' === $content_type && 0 === $excerpt_length ) {
			return;
		}
		?>
		<?php do_action( 'ppe_before_single_post_excerpt', get_the_ID(), $settings ); ?>
		<div class="pp-post-excerpt">
			<?php
			if ( 'full' === $content_type ) {
				$this->render_post_content( false, false );
			} elseif ( 'content' === $content_type ) {
				$more = '...';
				$post_content = wp_trim_words( get_the_content(), $content_length, apply_filters( 'pp_posts_content_limit_more', $more ) );
				echo wp_kses_post( $post_content );
			} else {
				add_filter( 'excerpt_length', array( $this, 'pp_excerpt_length_filter' ), 20 );
				add_filter( 'excerpt_more', array( $this, 'pp_excerpt_more_filter' ), 20 );

				echo wp_kses_post( $this->pp_post_excerpt() );

				remove_filter( 'excerpt_length', array( $this, 'pp_excerpt_length_filter' ), 20 );
				remove_filter( 'excerpt_more', array( $this, 'pp_excerpt_more_filter' ), 20 );
			}
			?>
		</div>
		<?php do_action( 'ppe_after_single_post_excerpt', get_the_ID(), $settings ); ?>
		<?php
	}

	/**
	 * Render button icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_button_icon() {
		$skin             = $this->get_id();
		$settings         = $this->parent->get_settings_for_display();
		$show_button      = $this->get_instance_value( 'show_button' );

		if ( 'yes' !== $show_button ) {
			return;
		}

		$button_icon          = $this->get_instance_value( 'button_icon' );
		$select_button_icon   = $this->get_instance_value( 'select_button_icon' );
		$button_icon_position = $this->get_instance_value( 'button_icon_position' );
		$button_icon_align    = ( 'before' === $button_icon_position ) ? 'left' : 'right';

		$migrated = isset( $settings['__fa4_migrated'][ $skin . '_select_button_icon' ] );
		$is_new   = empty( $settings[ $skin . '_button_icon' ] ) && Icons_Manager::is_migration_allowed();

		if ( $is_new || $migrated ) { ?>
			<span class="pp-button-icon elementor-button-icon elementor-align-icon-<?php echo esc_attr( $button_icon_align ); ?>">
				<?php Icons_Manager::render_icon( $select_button_icon, array( 'aria-hidden' => 'true' ) ); ?>
			</span>
			<?php
		} else { ?>
			<span class="pp-button-icon elementor-button-icon elementor-align-icon-<?php echo esc_attr( $button_icon_align ); ?>">
				<i class="pp-button-icon <?php echo esc_attr( $button_icon ); ?>" aria-hidden="true"></i>
			</span>
			<?php
		}
	}

	/**
	 * Render button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_button() {
		$skin             = $this->get_id();
		$settings         = $this->parent->get_settings_for_display();
		$show_button      = $this->get_instance_value( 'show_button' );
		$button_animation = $this->get_instance_value( 'button_animation' );

		if ( 'yes' !== $show_button ) {
			return;
		}

		$button_text          = isset( $settings[ $skin . '_button_text' ] ) ? $settings[ $skin . '_button_text' ] : $this->get_instance_value( 'button_text' );
		$button_icon_position = $this->get_instance_value( 'button_icon_position' );
		$button_size          = $this->get_instance_value( 'button_size' );
		$button_link_target   = $this->get_instance_value( 'button_link_target' );

		$classes = array(
			'pp-posts-button',
			'elementor-button',
			'elementor-size-' . $button_size,
		);

		if ( $button_animation ) {
			$classes[] = 'elementor-animation-' . $button_animation;
		}

		$title_attribute = the_title_attribute( 'echo=0' );

		$this->parent->add_render_attribute(
			'button-' . get_the_ID(),
			array(
				'class'      => implode( ' ', $classes ),
				'href'       => apply_filters( 'ppe_posts_button_link', get_the_permalink(), get_the_ID() ),
				'title'      => $title_attribute,
				'aria-label' => apply_filters( 'ppe_posts_button_aria_label', sprintf(
					/* translators: Aria-label describing the read more button */
					esc_attr__( 'Read more about %1$s', 'powerpack' ),
					$title_attribute
				), get_the_ID() ),
			)
		);

		if ( 'yes' === $button_link_target ) {
			$this->parent->add_render_attribute( 'button-' . get_the_ID(), 'target', '_blank' );
		}

		/**
		 * Button Text Filter
		 *
		 * Filters the text for the button
		 *
		 * @since 1.4.11.0
		 * @param string    $button_text    The original text
		 * @param int       get_the_id()    The post ID
		 */
		$button_text = apply_filters( 'ppe_posts_button_text', $button_text, get_the_ID() );
		?>
		<?php do_action( 'ppe_before_single_post_button', get_the_ID(), $settings ); ?>
		<a <?php $this->parent->print_render_attribute_string( 'button-' . get_the_ID() ); ?>>
			<?php if ( 'before' === $button_icon_position ) {
				$this->render_button_icon();
			} ?>
			<?php if ( $button_text ) { ?>
				<span class="pp-button-text">
					<?php echo wp_kses_post( $button_text ); ?>
				</span>
			<?php } ?>
			<?php if ( 'after' === $button_icon_position ) {
				$this->render_button_icon();
			} ?>
		</a>
		<?php do_action( 'ppe_after_single_post_button', get_the_ID(), $settings ); ?>
		<?php
	}

	/**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render_ajax_post_body( $filter = '', $taxonomy = '', $search = '' ) {
		ob_start();
		$this->parent->query_posts( $filter, $taxonomy, $search );

		$query       = $this->parent->get_query();
		$total_pages = $query->max_num_pages;

		while ( $query->have_posts() ) {
			$query->the_post();

			$this->render_post_body();
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render_ajax_pagination() {
		ob_start();
		$this->render_pagination();
		return ob_get_clean();
	}

	/**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render_ajax_not_found( $filter = '', $taxonomy = '', $search = '' ) {
		ob_start();
		$this->parent->query_posts( $filter, $taxonomy, $search );

		$query = $this->parent->get_query();

		if ( ! $query->found_posts ) {
			$this->render_search();
		}
		return ob_get_clean();
	}

	/**
	 * Get Pagination.
	 *
	 * Returns the Pagination HTML.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_pagination() {
		$settings  = $this->parent->get_settings_for_display();

		$pagination_type    = $this->get_instance_value( 'pagination_type' );
		$page_limit         = $this->get_instance_value( 'pagination_page_limit' );
		$pagination_shorten = $this->get_instance_value( 'pagination_numbers_shorten' );

		if ( 'none' === $pagination_type ) {
			return;
		}

		// Get current page number.
		$paged = $this->parent->get_paged();

		$query       = $this->parent->get_query();
		$total_pages = $query->max_num_pages;
		$total_pages_pagination = $query->max_num_pages;

		if ( 2 > $total_pages ) {
			return;
		}

		$has_numbers   = in_array( $pagination_type, array( 'numbers', 'numbers_and_prev_next' ) );
		$has_prev_next = ( 'numbers_and_prev_next' === $pagination_type );
		$is_load_more  = ( 'load_more' === $pagination_type );

		$links = array();

		if ( $has_numbers ) {

			$current_page = $paged;
			if ( ! $current_page ) {
				$current_page = 1;
			}

			$paginate_args = array(
				'type'      => 'array',
				'current'   => $current_page,
				'total'     => $total_pages,
				'prev_next' => false,
				'show_all'  => 'yes' !== $pagination_shorten,
			);
		}

		if ( $has_prev_next ) {
			$prev_label = $this->get_instance_value( 'pagination_prev_label' );
			$next_label = $this->get_instance_value( 'pagination_next_label' );

			$paginate_args['prev_next'] = true;

			if ( $prev_label ) {
				$paginate_args['prev_text'] = $prev_label;
			}
			if ( $next_label ) {
				$paginate_args['next_text'] = $next_label;
			}
		}

		if ( $has_numbers || $has_prev_next ) {

			if ( is_singular() && ! is_front_page() && ! is_singular( 'page' ) ) {
				global $wp_rewrite;
				if ( $wp_rewrite->using_permalinks() ) {
					$paginate_args['base']   = trailingslashit( get_permalink() ) . '%_%';
					$paginate_args['format'] = user_trailingslashit( '%#%', 'single_paged' );
				} else {
					$paginate_args['format'] = '?page=%#%';
				}
			}

			$links = paginate_links( $paginate_args );

		}

		if ( ! $is_load_more ) {
			$pagination_ajax = $this->get_instance_value( 'pagination_ajax' );
			$query_type      = $settings['query_type'];
			$pagination_type = 'standard';

			if ( 'yes' === $pagination_ajax && 'main' !== $query_type ) {
				$pagination_type = 'ajax';
			}
			?>
			<nav class="pp-posts-pagination pp-posts-pagination-<?php echo esc_attr( $pagination_type ); ?> elementor-pagination" role="navigation" aria-label="<?php esc_attr_e( 'Pagination', 'powerpack' ); ?>" data-total="<?php echo esc_html( $total_pages_pagination ); ?>">
				<?php echo implode( PHP_EOL, $links ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</nav>
			<?php
		}
	}

	public function get_posts_outer_wrap_classes() {
		$layout          = $this->get_instance_value( 'layout' );
		$pagination_type = $this->get_instance_value( 'pagination_type' );
		$dots_position   = $this->get_instance_value( 'dots_position' );

		$classes = array(
			'pp-posts-container',
		);

		if ( 'carousel' === $layout ) {
			$classes[] = 'swiper-container-wrap';

			if ( $dots_position ) {
				$classes[] = 'swiper-container-wrap-dots-' . $dots_position;
			}
		}

		if ( 'infinite' === $pagination_type ) {
			$classes[] = 'pp-posts-infinite-scroll';
		}

		return apply_filters( 'ppe_posts_outer_wrap_classes', $classes );
	}

	public function get_posts_wrap_classes() {
		$layout = $this->get_instance_value( 'layout' );

		$classes = array(
			'pp-posts',
			'pp-posts-skin-' . $this->get_id(),
		);

		if ( 'yes' === $this->get_instance_value( 'button_alignment' ) ) {
			$classes[] = 'pp-posts-align-buttons';
		}

		if ( 'carousel' === $layout ) {
			$swiper_class = PP_Helper::is_feature_active( 'e_swiper_latest' ) ? 'swiper' : 'swiper-container';
			$classes[] = 'pp-posts-carousel';
			$classes[] = 'pp-swiper-slider';
			$classes[] = $swiper_class;
		} elseif ( 'masonry' === $layout ) {
			$classes[] = 'pp-elementor-grid';
			$classes[] = 'pp-posts-grid';
		} else {
			$classes[] = 'elementor-grid';
			$classes[] = 'pp-posts-grid';
		}

		return apply_filters( 'ppe_posts_wrap_classes', $classes );
	}

	public function get_item_wrap_classes() {
		$layout = $this->get_instance_value( 'layout' );

		$classes = array( 'pp-post-wrap' );

		if ( 'carousel' === $layout ) {
			$classes[] = 'pp-carousel-item-wrap swiper-slide';
		} elseif ( 'masonry' === $layout ) {
			$classes[] = 'pp-grid-item-wrap';
		} else {
			$classes[] = 'pp-grid-item-wrap elementor-grid-item';
		}

		return implode( ' ', $classes );
	}

	public function get_item_classes() {
		$layout = $this->get_instance_value( 'layout' );

		$classes = array();

		$classes[] = 'pp-post';

		if ( 'carousel' === $layout ) {
			$classes[] = 'pp-carousel-item';
		} else {
			$classes[] = 'pp-grid-item';
		}

		return implode( ' ', $classes );
	}

	public function get_ordered_items( $items ) {

		if ( ! $items ) {
			return;
		}

		$ordered_items = array();

		foreach ( $items as $item ) {
			$order = $this->get_instance_value( $item . '_order' );

			$order = ( $order ) ? $order : 1;

			$ordered_items[ $item ] = $order;
		}

		asort( $ordered_items );

		return $ordered_items;
	}

	/**
	 * Render post meta output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_post_meta() {
		$settings  = $this->parent->get_settings_for_display();
		$post_meta = $this->get_instance_value( 'post_meta' );

		if ( 'yes' === $post_meta ) {
			?>
			<?php do_action( 'ppe_before_single_post_meta', get_the_ID(), $settings ); ?>
			<div class="pp-post-meta">
				<?php
					$meta_items = $this->get_ordered_items( Module::get_meta_items() );

				foreach ( $meta_items as $meta_item => $index ) {
					if ( 'author' === $meta_item ) {
						// Post Author
						$this->render_meta_item( 'author' );
					}

					if ( 'date' === $meta_item ) {
						// Post Date
						$this->render_meta_item( 'date' );
					}

					if ( 'comments' === $meta_item ) {
						// Post Comments
						$this->render_meta_item( 'comments' );
					}
				}
				?>
			</div>
			<?php
			do_action( 'ppe_after_single_post_meta', get_the_ID(), $settings );
		}
	}

	/**
	 * Render post body output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_post_body() {
		$settings = $this->parent->get_settings_for_display();

		$post_terms         = $this->get_instance_value( 'post_terms' );
		$post_meta          = $this->get_instance_value( 'post_meta' );
		$thumbnail_location = $this->get_instance_value( 'thumbnail_location' );

		do_action( 'ppe_before_single_post_wrap', get_the_ID(), $settings );
		?>
		<div <?php post_class( $this->get_item_wrap_classes() ); ?>>
			<?php do_action( 'ppe_before_single_post', get_the_ID(), $settings ); ?>
			<div class="<?php echo esc_attr( $this->get_item_classes() ); ?>">
				<?php
				if ( 'outside' === $thumbnail_location ) {
					$this->render_post_thumbnail();
				}
				?>

				<?php do_action( 'ppe_before_single_post_content', get_the_ID(), $settings ); ?>

				<div class="pp-post-content-wrap">
					<div class="pp-post-content">
						<?php
							$content_parts = $this->get_ordered_items( Module::get_post_parts() );

							foreach ( $content_parts as $part => $index ) {
								if ( 'thumbnail' === $part ) {
									if ( 'inside' === $thumbnail_location ) {
										$this->render_post_thumbnail();
									}
								}

								if ( 'terms' === $part ) {
									$this->render_terms();
								}

								if ( 'title' === $part ) {
									$this->render_post_title();
								}

								if ( 'meta' === $part ) {
									$this->render_post_meta();
								}

								if ( 'excerpt' === $part ) {
									$this->render_excerpt();
								}
							}
						?>
					</div>
					<?php
						if ( 'button' === $part ) {
							$this->render_button();
						}
					?>
				</div>

				<?php do_action( 'ppe_after_single_post_content', get_the_ID(), $settings ); ?>
			</div>
			<?php do_action( 'ppe_after_single_post', get_the_ID(), $settings ); ?>
		</div>
		<?php
		do_action( 'ppe_after_single_post_wrap', get_the_ID(), $settings );
	}

	/**
	 * Render Search Form HTML.
	 *
	 * Returns the Search Form HTML.
	 *
	 * @since 1.4.11.0
	 * @access public
	 */
	public function render_search() {
		$settings = $this->parent->get_settings_for_display();
		?>
		<div class="pp-posts-empty">
			<?php if ( $settings['nothing_found_message'] ) { ?>
				<p><?php echo wp_kses_post( $settings['nothing_found_message'] ); ?></p>
			<?php } ?>

			<?php if ( 'yes' === $settings['show_search_form'] ) { ?>
				<?php get_search_form(); ?>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Carousel Settings.
	 *
	 * @access public
	 */
	public function slider_settings() {
		$skin            = $this->get_id();
		$center_mode     = $this->get_instance_value( 'center_mode' );
		$autoplay        = $this->get_instance_value( 'autoplay' );
		$autoplay_speed  = $this->get_instance_value( 'autoplay_speed' );
		$arrows          = $this->get_instance_value( 'arrows' );
		$arrow           = $this->get_instance_value( 'arrow' );
		$select_arrow    = $this->get_instance_value( 'select_arrow' );
		$dots            = $this->get_instance_value( 'dots' );
		$animation_speed = $this->get_instance_value( 'animation_speed' );
		$infinite_loop   = $this->get_instance_value( 'infinite_loop' );
		$pause_on_hover  = $this->get_instance_value( 'pause_on_hover' );
		$adaptive_height = $this->get_instance_value( 'adaptive_height' );
		$direction       = $this->get_instance_value( 'direction' );

		$effect = ( $this->get_instance_value('carousel_effect') ) ? $this->get_instance_value('carousel_effect') : 'slide';

		if ( 'slide' === $effect ) {
			$slides_to_show          = ( ! empty( $this->get_instance_value( 'columns' ) ) ) ? absint( $this->get_instance_value( 'columns' ) ) : 3;
			$slides_to_show_tablet   = ( ! empty( $this->get_instance_value( 'columns_tablet' ) ) ) ? absint( $this->get_instance_value( 'columns_tablet' ) ) : 2;
			$slides_to_show_mobile   = ( ! empty( $this->get_instance_value( 'columns_mobile' ) ) ) ? absint( $this->get_instance_value( 'columns_mobile' ) ) : 2;
			$slides_to_scroll        = ( ! empty( $this->get_instance_value( 'slides_to_scroll' ) ) ) ? absint( $this->get_instance_value( 'slides_to_scroll' ) ) : 1;
			$slides_to_scroll_tablet = ( ! empty( $this->get_instance_value( 'slides_to_scroll_tablet' ) ) ) ? absint( $this->get_instance_value( 'slides_to_scroll_tablet' ) ) : 1;
			$slides_to_scroll_mobile = ( ! empty( $this->get_instance_value( 'slides_to_scroll_mobile' ) ) ) ? absint( $this->get_instance_value( 'slides_to_scroll_mobile' ) ) : 1;
		} else {
			$slides_to_show          = 1;
			$slides_to_show_tablet   = 1;
			$slides_to_show_mobile   = 1;
			$slides_to_scroll        = 1;
			$slides_to_scroll_tablet = 1;
			$slides_to_scroll_mobile = 1;
		}

		/* if ( 'right' === $direction ) {
			$slider_options['rtl'] = true;
		} */

		$slider_options = [
			'direction'        => 'horizontal',
			'effect'           => $effect,
			'speed'            => ( $animation_speed ) ? absint( $animation_speed ) : 600,
			'slides_per_view'  => $slides_to_show,
			'slides_to_scroll' => $slides_to_scroll,
			'centered_slides'  => ( 'yes' === $center_mode ),
			'loop'             => ( 'yes' === $infinite_loop ),
		];

		if ( 'yes' === $autoplay ) {
			$slider_options['autoplay'] = 'yes';

			$autoplay_speed = ( $autoplay_speed ) ? $autoplay_speed : 999999;

			$slider_options['autoplay_speed'] = $autoplay_speed;
			$slider_options['pause_on_hover'] = ( 'yes' === $pause_on_hover ) ? 'yes' : '';
		}

		if ( 'yes' === $dots ) {
			$slider_options['pagination'] = 'bullets';
		}

		if ( 'yes' === $arrows ) {
			$slider_options['show_arrows'] = true;
		}

		$breakpoints = PP_Helper::elementor()->breakpoints->get_active_breakpoints();

		foreach ( $breakpoints as $device => $breakpoint ) {
			if ( in_array( $device, [ 'mobile', 'tablet', 'desktop' ] ) ) {
				switch ( $device ) {
					case 'desktop':
						$slider_options['slides_per_view'] = $slides_to_show;
						$slider_options['slides_to_scroll'] = $slides_to_scroll;
						break;
					case 'tablet':
						$slider_options['slides_per_view_tablet'] = $slides_to_show_tablet;
						$slider_options['slides_to_scroll_tablet'] = $slides_to_scroll_tablet;;
						break;
					case 'mobile':
						$slider_options['slides_per_view_mobile'] = $slides_to_show_mobile;
						$slider_options['slides_to_scroll_mobile'] = $slides_to_scroll_mobile;
						break;
				}
			} else {
				if ( ( ! empty( $this->get_instance_value( 'columns_'  . $device ) ) ) ) {
					$slider_options['slides_per_view_' . $device] = absint( $this->get_instance_value( 'columns_'  . $device ) );
				}

				if ( ( ! empty( $this->get_instance_value( 'slides_to_scroll_'  . $device ) ) ) ) {
					$slider_options['slides_to_scroll_' . $device] = absint( $this->get_instance_value( 'slides_to_scroll_'  . $device ) );
				}
			}
		}

		$this->parent->add_render_attribute(
			'posts-wrap',
			array(
				'data-slider-settings' => wp_json_encode( $slider_options ),
			)
		);
	}

	/**
	 * Render team member carousel dots output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_dots() {
		$dots   = $this->get_instance_value( 'dots' );
		$layout = $this->get_instance_value( 'layout' );

		if ( 'carousel' !== $layout ) {
			return;
		}

		if ( 'yes' === $dots ) {
			?>
			<!-- Add Pagination -->
			<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->parent->get_id() ); ?>"></div>
			<?php
		}
	}

	/**
	 * Render team member carousel arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_arrows() {
		$settings        = $this->parent->get_settings_for_display();
		$skin            = $this->get_id();
		$layout          = $this->get_instance_value( 'layout' );
		$arrows          = $this->get_instance_value( 'arrows' );
		$arrow           = $this->get_instance_value( 'arrow' );
		$select_arrow    = $this->get_instance_value( 'select_arrow' );

		if ( 'carousel' !== $layout ) {
			return;
		}

		$migration_allowed = Icons_Manager::is_migration_allowed();

		if ( ! isset( $settings[ $skin . '_arrow' ] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default.
			$settings[ $skin . '_arrow' ] = 'fa fa-angle-right';
		}

		$has_icon = ! empty( $settings[ $skin . '_arrow' ] );

		if ( ! $has_icon && ! empty( $select_arrow['value'] ) ) {
			$has_icon = true;
		}

		if ( ! empty( $settings['arrow'] ) ) {
			$this->parent->add_render_attribute( 'arrow-icon', 'class', $settings[ $skin . '_arrow' ] );
			$this->parent->add_render_attribute( 'arrow-icon', 'aria-hidden', 'true' );
		}

		$migrated = isset( $settings['__fa4_migrated'][ $skin . '_select_arrow' ] );
		$is_new   = ! isset( $settings[ $skin . '_arrow' ] ) && Icons_Manager::is_migration_allowed();

		if ( 'yes' === $arrows ) {
			if ( $has_icon ) {
				if ( $is_new || $migrated ) {
					$next_arrow = $select_arrow;
					$prev_arrow = str_replace( 'right', 'left', $select_arrow );
				} else {
					$next_arrow = $settings['arrow'];
					$prev_arrow = str_replace( 'right', 'left', $arrow );
				}
			} else {
				$next_arrow = 'fa fa-angle-right';
				$prev_arrow = 'fa fa-angle-left';
			}

			if ( ! empty( $arrow ) || ( ! empty( $select_arrow['value'] ) && $is_new ) ) { ?>
				<div class="pp-slider-arrow elementor-swiper-button-prev swiper-button-prev-<?php echo esc_attr( $this->parent->get_id() ); ?>">
					<?php if ( $is_new || $migrated ) :
						Icons_Manager::render_icon( $prev_arrow, [ 'aria-hidden' => 'true' ] );
					else : ?>
						<i <?php $this->parent->print_render_attribute_string( 'arrow-icon' ); ?>></i>
					<?php endif; ?>
				</div>
				<div class="pp-slider-arrow elementor-swiper-button-next swiper-button-next-<?php echo esc_attr( $this->parent->get_id() ); ?>">
					<?php if ( $is_new || $migrated ) :
						Icons_Manager::render_icon( $next_arrow, [ 'aria-hidden' => 'true' ] );
					else : ?>
						<i <?php $this->parent->print_render_attribute_string( 'arrow-icon' ); ?>></i>
					<?php endif; ?>
				</div>
			<?php }
		}
	}

	/**
	 * Render posts grid widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	public function render() {
		$settings = $this->parent->get_settings_for_display();

		$query_type          = $settings['query_type'];
		$layout              = $this->get_instance_value( 'layout' );
		$pagination_type     = $this->get_instance_value( 'pagination_type' );
		$pagination_position = $this->get_instance_value( 'pagination_position' );
		$equal_height        = $this->get_instance_value( 'equal_height' );
		$direction           = $this->get_instance_value( 'direction' );
		$skin                = $this->get_id();
		$posts_outer_wrap    = $this->get_posts_outer_wrap_classes();
		$posts_wrap          = $this->get_posts_wrap_classes();
		$page_id             = '';
		if ( null !== \Elementor\Plugin::$instance->documents->get_current() ) {
			$page_id = \Elementor\Plugin::$instance->documents->get_current()->get_main_id();
		}

		$this->parent->add_render_attribute( 'posts-container', 'class', $posts_outer_wrap );

		$this->parent->add_render_attribute( 'posts-wrap', 'class', $posts_wrap );

		if ( 'carousel' === $layout ) {
			if ( 'yes' === $equal_height ) {
				$this->parent->add_render_attribute( 'posts-wrap', 'data-equal-height', 'yes' );
			}
			if ( 'right' === $direction ) {
				$this->parent->add_render_attribute( 'posts-wrap', 'dir', 'rtl' );
			}
		}

		$this->parent->add_render_attribute(
			'posts-wrap',
			array(
				'data-query-type' => $query_type,
				'data-layout'     => $layout,
				'data-page'       => $page_id,
				'data-skin'       => $skin,
			)
		);

		$this->parent->add_render_attribute( 'post-categories', 'class', 'pp-post-categories' );

		$filter   = '';
		$taxonomy = '';

		$this->parent->query_posts( $filter, $taxonomy );
		$query = $this->parent->get_query();

		if ( 'carousel' === $layout ) {
			$this->slider_settings();
		}

		if ( ! $query->found_posts ) {
			?>
			<div <?php echo wp_kses_post( $this->parent->get_render_attribute_string( 'posts-container' ) ); ?>>
			<?php
			$this->render_search();
			?>
			</div>
			<?php
			return;
		}

		do_action( 'ppe_before_posts_outer_wrap', $settings );
		?>
		<div <?php echo wp_kses_post( $this->parent->get_render_attribute_string( 'posts-container' ) ); ?>>
			<?php
			do_action( 'ppe_before_posts_wrap', $settings );

			$i = 1;

			$total_pages = $query->max_num_pages;
			?>

			<?php if ( 'carousel' !== $layout ) { ?>
				<?php if ( ( 'numbers' === $pagination_type || 'numbers_and_prev_next' === $pagination_type ) && ( 'top' === $pagination_position || 'top-bottom' === $pagination_position ) ) { ?>
				<div class="pp-posts-pagination-wrap pp-posts-pagination-top">
					<?php
						$this->render_pagination();
					?>
				</div>
				<?php } ?>
			<?php } ?>

			<div <?php echo wp_kses_post( $this->parent->get_render_attribute_string( 'posts-wrap' ) ); ?>>
				<?php if ( 'carousel' === $layout ) { ?><div class="swiper-wrapper"><?php } ?>
					<?php
					$i = 1;

					if ( $query->have_posts() ) :
						while ( $query->have_posts() ) :
							$query->the_post();

							$this->render_post_body();

							$i++;

						endwhile;
					endif;
					wp_reset_postdata();
					?>
				<?php if ( 'carousel' === $layout ) { ?></div><?php } ?>
			</div>
			<?php
				$this->render_dots();

				$this->render_arrows();
			?>

			<?php do_action( 'ppe_after_posts_wrap', $settings ); ?>

			<?php if ( 'load_more' === $pagination_type || 'infinite' === $pagination_type ) { ?>
			<div class="pp-posts-loader"></div>
			<?php } ?>

			<?php
			if ( 'load_more' === $pagination_type || 'infinite' === $pagination_type ) {
				$pagination_bottom = true;
			} elseif ( ( 'numbers' === $pagination_type || 'numbers_and_prev_next' === $pagination_type ) && ( '' === $pagination_position || 'bottom' === $pagination_position || 'top-bottom' === $pagination_position ) ) {
				$pagination_bottom = true;
			} else {
				$pagination_bottom = false;
			}
			?>

			<?php if ( 'carousel' !== $layout ) { ?>
				<?php if ( $pagination_bottom ) { ?>
				<div class="pp-posts-pagination-wrap pp-posts-pagination-bottom">
					<?php
						$this->render_pagination();
					?>
				</div>
				<?php } ?>
			<?php } ?>
		</div>

		<?php do_action( 'ppe_after_posts_outer_wrap', $settings ); ?>

		<?php
		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {

			if ( 'masonry' === $layout ) {
				$this->render_editor_script();
			}
		}
	}

	/**
	 * Get masonry script.
	 *
	 * Returns the post masonry script.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_editor_script() {
		$settings = $this->parent->get_settings_for_display();
		$layout   = $this->get_instance_value( 'layout' );

		if ( 'masonry' !== $layout ) {
			return;
		}

		$layout = 'masonry';

		?>
		<script type="text/javascript">

			jQuery( document ).ready( function( $ ) {
				$( '.pp-posts-grid' ).each( function() {

					var $node_id 	= '<?php echo esc_attr( $this->parent->get_id() ); ?>',
						$scope 		= $( '[data-id="' + $node_id + '"]' ),
						$selector 	= $(this);

					if ( $selector.closest( $scope ).length < 1 ) {
						return;
					}

					$selector.imagesLoaded( function() {

						$isotopeObj = $selector.isotope({
							layoutMode: '<?php echo esc_attr( $layout ); ?>',
							itemSelector: '.pp-grid-item-wrap',
						});

						$selector.find( '.pp-grid-item-wrap' ).resize( function() {
							$isotopeObj.isotope( 'layout' );
						});
					});
				});
			});

		</script>
		<?php
	}
}
