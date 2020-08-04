<?php
namespace PowerpackElementsLite\Modules\Posts\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Posts_Helper;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Skin_Base as Elementor_Skin_Base;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Css_Filter;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Posts Grid Widget
 */
class Posts extends Posts_Base {

	/**
	 * Retrieve posts grid widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pp-posts';
	}

	/**
	 * Retrieve posts grid widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Posts', 'powerpack' );
	}

	/**
	 * Retrieve the list of categories the posts grid widget belongs to.
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
	 * Retrieve posts grid widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'ppicon-posts-grid power-pack-admin-icon';
	}

	protected function _register_controls() {
		/* Content Tab */
		$this->register_content_layout_controls();
		$this->register_query_section_controls( array(), 'posts', '', 'yes' );
		$this->register_content_filters_controls();
		$this->register_content_terms_controls();
		$this->register_content_image_controls();
		$this->register_content_title_controls();
		$this->register_content_excerpt_controls();
		$this->register_content_meta_controls();
		$this->register_content_button_controls();
		$this->register_content_pagination_controls();
		$this->register_content_order_controls();
		$this->register_content_help_docs_controls();
		$this->register_content_upgrade_pro_controls();

		/* Style Tab */
		$this->register_style_layout_controls();
		$this->register_style_box_controls();
		$this->register_style_content_controls();
		$this->register_style_image_controls();
		$this->register_style_title_controls();
		$this->register_style_excerpt_controls();
		$this->register_style_meta_controls();
		$this->register_style_button_controls();
		$this->register_style_pagination_controls();
	}

	protected function register_content_layout_controls() {
		/**
		 * Content Tab: Layout
		 */
		$this->start_controls_section(
			'section_layout',
			[
				'label'                 => __( 'Layout', 'powerpack' ),
			]
		);

		$this->add_control(
			'_skin',
			[
				'label'                 => __( 'Skin', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'classic',
				'options'               => [
					'classic'       => __( 'Classic', 'powerpack' ),
					'portfolio'     => __( 'Portfolio', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'     => __( 'Posts Per Page', 'powerpack' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 6,
				'condition' => array(
					'query_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'layout',
			[
				'label'                 => __( 'Layout', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'grid'       => __( 'Grid', 'powerpack' ),
					'masonry'    => __( 'Masonry', 'powerpack' ),
					'carousel'   => __( 'Carousel', 'powerpack' ),
				],
				'default'               => 'grid',
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'                 => __( 'Columns', 'powerpack' ),
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
				'render_type'           => 'template',
				'frontend_available'    => true,
			]
		);

		$this->add_control(
			'equal_height',
			array(
				'label'        => __( 'Equal Height', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'prefix_class' => 'pp-equal-height-',
				'render_type'  => 'template',
				'condition'    => array(
					'layout!' => 'masonry',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Filters
	 */
	protected function register_content_filters_controls() {
		$this->start_controls_section(
			'section_filters',
			array(
				'label'     => __( 'Filters', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'post_type!' => 'related',
					'layout!'    => 'carousel',
				),
			)
		);

		$this->add_control(
			'show_filters',
			array(
				'label'        => __( 'Show Filters', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'post_type!' => 'related',
					'layout!'    => 'carousel',
				),
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
					'tax_' . $post_type_slug . '_filter',
					array(
						'label'       => __( 'Filter By', 'powerpack' ),
						'type'        => Controls_Manager::SELECT2,
						'options'     => $related_tax,
						'multiple'    => true,
						'label_block' => true,
						'default'     => array_keys( $related_tax )[0],
						'condition'   => array(
							'post_type'    => $post_type_slug,
							'show_filters' => 'yes',
							'layout'       => array( 'grid', 'masonry' ),
						),
					)
				);
			}
		}

		$this->add_control(
			'filter_all_label',
			array(
				'label'     => __( '"All" Filter Label', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'All', 'powerpack' ),
				'condition' => array(
					'layout!'      => 'carousel',
					'show_filters' => 'yes',
				),
			)
		);

		$this->add_control(
			'enable_active_filter',
			array(
				'label'        => __( 'Default Active Filter', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'layout!'      => 'carousel',
					'show_filters' => 'yes',
				),
			)
		);

		// Active filter
		$this->add_control(
			'filter_active',
			array(
				'label'        => __( 'Active Filter', 'powerpack' ),
				'type'         => 'pp-query',
				'post_type'    => '',
				'options'      => array(),
				'label_block'  => true,
				'multiple'     => false,
				'query_type'   => 'terms',
				'object_type'  => '',
				'include_type' => true,
				'condition'    => array(
					'layout!'              => 'carousel',
					'show_filters'         => 'yes',
					'enable_active_filter' => 'yes',
				),
			)
		);

		$this->add_control(
			'show_filters_count',
			array(
				'label'        => __( 'Show Post Count', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => array(
					'layout!'      => 'carousel',
					'show_filters' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'filter_alignment',
			array(
				'label'       => __( 'Alignment', 'powerpack' ),
				'label_block' => false,
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'left',
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-post-filters' => 'text-align: {{VALUE}};',
				),
				'condition'   => array(
					'layout!'      => 'carousel',
					'show_filters' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Post Terms
	 */
	protected function register_content_terms_controls() {
		$this->start_controls_section(
			'section_terms',
			[
				'label'                 => __( 'Post Terms', 'powerpack' ),
			]
		);

		$this->add_control(
			'post_terms',
			[
				'label'                 => __( 'Show Post Terms', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$post_types = PP_Posts_Helper::get_post_types();

		foreach ( $post_types as $post_type_slug => $post_type_label ) {

			$taxonomy = PP_Posts_Helper::get_post_taxonomies( $post_type_slug );

			if ( ! empty( $taxonomy ) ) {

				$related_tax = [];

				// Get all taxonomy values under the taxonomy.
				foreach ( $taxonomy as $index => $tax ) {

					$terms = get_terms( $index );

					$related_tax[ $index ] = $tax->label;
				}

				// Add control for all taxonomies.
				$this->add_control(
					'tax_badge_' . $post_type_slug,
					[
						'label'     => __( 'Select Taxonomy', 'powerpack' ),
						'type'      => Controls_Manager::SELECT2,
						'options'   => $related_tax,
						'multiple'  => true,
						'default'   => array_keys( $related_tax )[0],
						'condition' => [
							'post_type' => $post_type_slug,
							'post_terms' => 'yes',
						],
					]
				);
			}
		}

		$this->add_control(
			'max_terms',
			[
				'label'                 => __( 'Max Terms to Show', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => 1,
				'condition'             => [
					'post_terms' => 'yes',
				],
				'label_block'           => false,
			]
		);

		$this->add_control(
			'post_taxonomy_link',
			[
				'label'                 => __( 'Link to Taxonomy', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'post_terms' => 'yes',
				],
			]
		);

		$this->add_control(
			'post_terms_separator',
			[
				'label'                 => __( 'Terms Separator', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => ',',
				'selectors'             => [
					'{{WRAPPER}} .pp-post-terms > .pp-post-term:not(:last-child):after' => 'content: "{{UNIT}}";',
				],
				'condition'             => [
					'post_terms' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Image
	 */
	protected function register_content_image_controls() {
		$this->start_controls_section(
			'section_image',
			[
				'label'                 => __( 'Image', 'powerpack' ),
			]
		);

		$this->add_control(
			'show_thumbnail',
			[
				'label'                 => __( 'Show Image', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'thumbnail_link',
			[
				'label'                 => __( 'Link to Post', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'thumbnail',
				'label'                 => __( 'Image Size', 'powerpack' ),
				'default'               => 'large',
				'exclude'           => [ 'custom' ],
				'condition'             => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_control(
			'thumbnail_location',
			[
				'label'                 => __( 'Image Location', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'inside'     => __( 'Inside Content Container', 'powerpack' ),
					'outside'    => __( 'Outside Content Container', 'powerpack' ),
				],
				'default'               => 'outside',
				'condition'             => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_control(
			'fallback_image',
			[
				'label'                 => __( 'Fallback Image', 'powerpack' ),
				'description'           => __( 'If a featured image is not available in post, it will display the first image from the post or default image placeholder or a custom image. You can choose None to do not display the fallback image.', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'none'           => __( 'None', 'powerpack' ),
					'default'        => __( 'Default', 'powerpack' ),
					'custom'         => __( 'Custom', 'powerpack' ),
				],
				'default'               => 'default',
				'condition'             => [
					'show_thumbnail' => 'yes',
				],
			]
		);

		$this->add_control(
			'fallback_image_custom',
			[
				'label'             => __( 'Fallback Image Custom', 'powerpack' ),
				'type'              => Controls_Manager::MEDIA,
				'condition'         => [
					'show_thumbnail' => 'yes',
					'fallback_image' => 'custom',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Title
	 */
	protected function register_content_title_controls() {
		$this->start_controls_section(
			'section_post_title',
			[
				'label'                 => __( 'Title', 'powerpack' ),
			]
		);

		$this->add_control(
			'post_title',
			[
				'label'                 => __( 'Post Title', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'post_title_link',
			[
				'label'                 => __( 'Link to Post', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'post_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label'                 => __( 'HTML Tag', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'h2',
				'options'               => [
					'h1'     => __( 'H1', 'powerpack' ),
					'h2'     => __( 'H2', 'powerpack' ),
					'h3'     => __( 'H3', 'powerpack' ),
					'h4'     => __( 'H4', 'powerpack' ),
					'h5'     => __( 'H5', 'powerpack' ),
					'h6'     => __( 'H6', 'powerpack' ),
					'div'    => __( 'div', 'powerpack' ),
					'span'   => __( 'span', 'powerpack' ),
					'p'      => __( 'p', 'powerpack' ),
				],
				'condition'             => [
					'post_title' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Excerpt
	 */
	protected function register_content_excerpt_controls() {
		$this->start_controls_section(
			'section_post_excerpt',
			[
				'label'                 => __( 'Content', 'powerpack' ),
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'                 => __( 'Show Content', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'content_type',
			[
				'label'                 => __( 'Content Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'excerpt',
				'options'               => [
					'excerpt'   => __( 'Excerpt', 'powerpack' ),
					'content'   => __( 'Limited Content', 'powerpack' ),
					'full'      => __( 'Full Content', 'powerpack' ),
				],
				'condition'             => [
					'show_excerpt' => 'yes',
				],
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'                 => __( 'Excerpt Length', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => 20,
				'min'                   => 0,
				'step'                  => 1,
				'condition'             => [
					'show_excerpt' => 'yes',
					'content_type' => 'excerpt',
				],
			]
		);

		$this->add_control(
			'content_length',
			[
				'label'                 => __( 'Content Length', 'powerpack' ),
				'title'                 => __( 'Words', 'powerpack' ),
				'description'           => __( 'Number of words to be displayed from the post content', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => 300,
				'min'                   => 0,
				'step'                  => 1,
				'condition'             => [
					'show_excerpt' => 'yes',
					'content_type' => 'excerpt',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Meta
	 */
	protected function register_content_meta_controls() {
		$this->start_controls_section(
			'section_post_meta',
			[
				'label'                 => __( 'Meta', 'powerpack' ),
			]
		);

		$this->add_control(
			'post_meta',
			[
				'label'                 => __( 'Post Meta', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'post_meta_separator',
			[
				'label'                 => __( 'Post Meta Separator', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '-',
				'selectors'             => [
					'{{WRAPPER}} .pp-post-meta > span:not(:last-child):after' => 'content: "{{UNIT}}";',
				],
				'condition'             => [
					'post_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_post_author',
			[
				'label'                 => __( 'Post Author', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'post_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_author',
			[
				'label'                 => __( 'Show Post Author', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'post_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'author_link',
			[
				'label'                 => __( 'Link to Author', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'post_meta' => 'yes',
					'show_author' => 'yes',
				],
			]
		);

		$this->add_control(
			'author_icon',
			[
				'label'                 => __( 'Author Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICON,
				'default'               => '',
				'condition'             => [
					'post_meta' => 'yes',
					'show_author' => 'yes',
				],
			]
		);

		$this->add_control(
			'author_prefix',
			[
				'label'                 => __( 'Prefix', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'post_meta' => 'yes',
					'show_author' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_post_date',
			[
				'label'                 => __( 'Post Date', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'post_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_date',
			[
				'label'                 => __( 'Show Post Date', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'post_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'date_link',
			[
				'label'                 => __( 'Link to Post', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'post_meta' => 'yes',
					'show_date' => 'yes',
				],
			]
		);

		$this->add_control(
			'date_format',
			[
				'label'                 => __( 'Date Format', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					''           => __( 'Published Date', 'powerpack' ),
					'ago'        => __( 'Time Ago', 'powerpack' ),
					'modified'   => __( 'Last Modified Date', 'powerpack' ),
					'custom'     => __( 'Custom Format', 'powerpack' ),
					'key'        => __( 'Custom Meta Key', 'powerpack' ),
				],
				'default'               => '',
				'condition'             => [
					'post_meta' => 'yes',
					'show_date' => 'yes',
				],
			]
		);

		$this->add_control(
			'date_custom_format',
			[
				'label'             => __( 'Custom Format', 'powerpack' ),
				'description'       => sprintf( __( 'Refer to PHP date formats <a href="%s">here</a>', 'powerpack' ), 'https://wordpress.org/support/article/formatting-date-and-time/' ),
				'type'              => Controls_Manager::TEXT,
				'label_block'       => false,
				'default'           => '',
				'dynamic'           => [
					'active' => true,
				],
				'condition'             => [
					'post_meta' => 'yes',
					'show_date' => 'yes',
					'date_format' => 'custom',
				],
			]
		);

		$this->add_control(
			'date_meta_key',
			[
				'label'             => __( 'Custom Meta Key', 'powerpack' ),
				'description'       => __( 'Display the post date stored in custom meta key.', 'powerpack' ),
				'type'              => Controls_Manager::TEXT,
				'label_block'       => false,
				'default'           => '',
				'dynamic'           => [
					'active' => true,
				],
				'condition'             => [
					'post_meta' => 'yes',
					'show_date' => 'yes',
					'date_format' => 'key',
				],
			]
		);

		$this->add_control(
			'date_icon',
			[
				'label'                 => __( 'Date Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICON,
				'default'               => '',
				'condition'             => [
					'post_meta' => 'yes',
					'show_date' => 'yes',
				],
			]
		);

		$this->add_control(
			'date_prefix',
			[
				'label'                 => __( 'Prefix', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'post_meta' => 'yes',
					'show_date' => 'yes',
				],
			]
		);

		$this->add_control(
			'heading_post_comments',
			[
				'label'                 => __( 'Post Comments', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'post_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_comments',
			[
				'label'                 => __( 'Show Post Comments', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'post_meta' => 'yes',
				],
			]
		);

		$this->add_control(
			'comments_icon',
			[
				'label'                 => __( 'Comments Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICON,
				'default'               => '',
				'condition'             => [
					'post_meta' => 'yes',
					'show_comments' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Read More Button
	 */
	protected function register_content_button_controls() {
		$this->start_controls_section(
			'section_button',
			[
				'label'                 => __( 'Read More Button', 'powerpack' ),
			]
		);

		$this->add_control(
			'show_button',
			[
				'label'                 => __( 'Show Button', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'button_text',
			[
				'label'                 => __( 'Button Text', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => __( 'Read More', 'powerpack' ),
				'condition'             => [
					'show_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_icon',
			[
				'label'                 => __( 'Button Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICON,
				'default'               => '',
				'condition'             => [
					'show_button' => 'yes',
				],
			]
		);

		$this->add_control(
			'button_icon_position',
			[
				'label'                 => __( 'Icon Position', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'after',
				'options'               => [
					'after'     => __( 'After', 'powerpack' ),
					'before'    => __( 'Before', 'powerpack' ),
				],
				'condition'             => [
					'show_button' => 'yes',
					'button_icon!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Pagination
	 */
	protected function register_content_pagination_controls() {
		$this->start_controls_section(
			'section_pagination',
			[
				'label'                 => __( 'Pagination', 'powerpack' ),
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label'                 => __( 'Pagination', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'none',
				'options'               => [
					'none'                  => __( 'None', 'powerpack' ),
					'numbers'               => __( 'Numbers', 'powerpack' ),
					'numbers_and_prev_next' => __( 'Numbers', 'powerpack' ) . ' + ' . __( 'Previous/Next', 'powerpack' ),
					'load_more'             => __( 'Load More Button', 'powerpack' ),
					'infinite'              => __( 'Infinite', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'pagination_position',
			[
				'label'                 => __( 'Pagination Position', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'bottom',
				'options'               => [
					'top'           => __( 'Top', 'powerpack' ),
					'bottom'        => __( 'Bottom', 'powerpack' ),
					'top-bottom'    => __( 'Top', 'powerpack' ) . ' + ' . __( 'Bottom', 'powerpack' ),
				],
				'condition'             => [
					'pagination_type'   => [
						'numbers',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_ajax',
			[
				'label'                 => __( 'Ajax Pagination', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'condition'             => [
					'pagination_type'   => [
						'numbers',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_page_limit',
			[
				'label'                 => __( 'Page Limit', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => 5,
				'condition'             => [
					'pagination_type'   => [
						'numbers',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_numbers_shorten',
			[
				'label'                 => __( 'Shorten', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'condition'             => [
					'pagination_type'   => [
						'numbers',
						'numbers_and_prev_next',
					],
				],
			]
		);

		$this->add_control(
			'pagination_load_more_label',
			[
				'label'                 => __( 'Button Label', 'powerpack' ),
				'default'               => __( 'Load More', 'powerpack' ),
				'condition'             => [
					'pagination_type'   => 'load_more',
				],
			]
		);

		$this->add_control(
			'pagination_load_more_button_icon',
			[
				'label'                 => __( 'Button Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICON,
				'default'               => '',
				'condition'             => [
					'pagination_type'   => 'load_more',
				],
			]
		);

		$this->add_control(
			'pagination_load_more_button_icon_position',
			[
				'label'                 => __( 'Icon Position', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'after',
				'options'               => [
					'after'     => __( 'After', 'powerpack' ),
					'before'    => __( 'Before', 'powerpack' ),
				],
				'condition'             => [
					'pagination_type'   => 'load_more',
					'pagination_load_more_button_icon!' => '',
				],
			]
		);

		$this->add_control(
			'pagination_prev_label',
			[
				'label'                 => __( 'Previous Label', 'powerpack' ),
				'default'               => __( '&laquo; Previous', 'powerpack' ),
				'condition'             => [
					'pagination_type'   => 'numbers_and_prev_next',
				],
			]
		);

		$this->add_control(
			'pagination_next_label',
			[
				'label'                 => __( 'Next Label', 'powerpack' ),
				'default'               => __( 'Next &raquo;', 'powerpack' ),
				'condition'             => [
					'pagination_type'   => 'numbers_and_prev_next',
				],
			]
		);

		$this->add_control(
			'pagination_align',
			[
				'label'                 => __( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'           => [
					'left'      => [
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => 'center',
				'selectors'         => [
					'{{WRAPPER}} .pp-posts-pagination-wrap' => 'text-align: {{VALUE}};',
				],
				'condition'             => [
					'pagination_type!'  => 'none',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Order
	 */
	protected function register_content_order_controls() {
		$this->start_controls_section(
			'section_order',
			[
				'label'                 => __( 'Order', 'powerpack' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Help Docs
	 *
	 * @since 1.4.8
	 * @access protected
	 */
	protected function register_content_help_docs_controls() {
		$this->start_controls_section(
			'section_help_docs',
			[
				'label'                 => __( 'Help Docs', 'powerpack' ),
			]
		);

		$this->add_control(
			'help_doc_1',
			[
				'type'                  => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'                   => sprintf( __( '%1$s Watch Video Overview %2$s', 'powerpack' ), '<a href="https://www.youtube.com/watch?v=9-SF5w93Yr8&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj" target="_blank" rel="noopener">', '</a>' ),
				'content_classes'       => 'pp-editor-doc-links',
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_upgrade_pro_controls() {
		if ( ! is_pp_elements_active() ) {
			$this->start_controls_section(
				'section_upgrade_powerpack',
				array(
					'label' => apply_filters( 'upgrade_powerpack_title', __( 'Get PowerPack Pro', 'powerpack' ) ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'upgrade_powerpack_notice',
				array(
					'label'           => '',
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => apply_filters( 'upgrade_powerpack_message', sprintf( __( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
					'content_classes' => 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->end_controls_section();
		}
	}

	/**
	 * Style Tab: Layout
	 */
	protected function register_style_layout_controls() {
		$this->start_controls_section(
			'section_layout_style',
			[
				'label'                 => __( 'Layout', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'posts_horizontal_spacing',
			[
				'label'                 => __( 'Column Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px'    => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'               => [
					'size'  => 25,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-wrap' => 'padding-left: calc( {{SIZE}}{{UNIT}}/2 ); padding-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .pp-posts'  => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);

		$this->add_responsive_control(
			'posts_vertical_spacing',
			[
				'label'                 => __( 'Row Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px'    => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'               => [
					'size'  => 25,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-elementor-grid .pp-grid-item-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Box
	 */
	protected function register_style_box_controls() {
		$this->start_controls_section(
			'section_post_box_style',
			[
				'label'                 => __( 'Box', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_post_box_style' );

		$this->start_controls_tab(
			'tab_post_box_normal',
			[
				'label'                 => __( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'post_box_bg',
			[
				'label'                 => __( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-post' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'post_box_border',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-post',
			]
		);

		$this->add_control(
			'post_box_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-post' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'post_box_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-post' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'post_box_shadow',
				'selector'          => '{{WRAPPER}} .pp-post',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_post_box_hover',
			[
				'label'                 => __( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'post_box_bg_hover',
			[
				'label'                 => __( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-post:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_box_border_color_hover',
			[
				'label'                 => __( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-post:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'              => 'post_box_shadow_hover',
				'selector'          => '{{WRAPPER}} .pp-post:hover',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Content
	 */
	protected function register_style_content_controls() {
		$this->start_controls_section(
			'section_post_content_style',
			[
				'label'                 => __( 'Content', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'post_content_align',
			[
				'label'                 => __( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'           => [
					'left'      => [
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => '',
				'selectors'         => [
					'{{WRAPPER}} .pp-post-content' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_content_bg',
			[
				'label'                 => __( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-post-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_content_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_content_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Image
	 */
	protected function register_style_image_controls() {
		$this->start_controls_section(
			'section_image_style',
			[
				'label'                 => __( 'Image', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'show_thumbnail'    => 'yes',
				],
			]
		);

		$this->add_control(
			'img_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-thumbnail, {{WRAPPER}} .pp-post-thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'show_thumbnail'    => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label'                 => __( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'default'               => [
					'size' => 20,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-thumbnail' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'             => [
					'show_thumbnail'    => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab( 'normal',
			[
				'label'                 => __( 'Normal', 'powerpack' ),
				'condition'             => [
					'show_thumbnail'    => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'                  => 'thumbnail_filters',
				'selector'              => '{{WRAPPER}} .pp-post-thumbnail img',
				'condition'             => [
					'show_thumbnail'    => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label'                 => __( 'Hover', 'powerpack' ),
				'condition'             => [
					'show_thumbnail'    => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'                  => 'thumbnail_hover_filters',
				'selector'              => '{{WRAPPER}} .pp-post:hover .pp-post-thumbnail img',
				'condition'             => [
					'show_thumbnail'    => 'yes',
				],
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
			[
				'label'                 => __( 'Title', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'post_title'    => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'                 => __( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-title, {{WRAPPER}} .pp-post-title a' => 'color: {{VALUE}}',
				],
				'condition'             => [
					'post_title'    => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label'                 => __( 'Hover Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-title a:hover' => 'color: {{VALUE}}',
				],
				'condition'             => [
					'post_title'    => 'yes',
					'post_title_link' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'title_typography',
				'label'                 => __( 'Typography', 'powerpack' ),
				'selector'              => '{{WRAPPER}} .pp-post-title',
				'condition'             => [
					'post_title'    => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'title_margin_bottom',
			[
				'label'                 => __( 'Bottom Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 50,
						'step'  => 1,
					],
				],
				'default'               => [
					'size'  => 10,
				],
				'size_units'            => [ 'px' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-post-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'post_title'    => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Style Tab: Content
	 */
	protected function register_style_excerpt_controls() {
		$this->start_controls_section(
			'section_excerpt_style',
			array(
				'label'     => __( 'Content', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'excerpt_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'scheme'    => array(
					'type'  => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-post-excerpt' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'excerpt_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'selector'  => '{{WRAPPER}} .pp-post-excerpt',
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'excerpt_margin_bottom',
			array(
				'label'      => __( 'Bottom Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
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
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'show_excerpt' => 'yes',
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
				'label'     => __( 'Meta', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'post_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_text_color',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post-meta' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'post_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_links_color',
			array(
				'label'     => __( 'Links Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post-meta a' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'post_meta' => 'yes',
				),
			)
		);

		$this->add_control(
			'meta_links_color_hover',
			array(
				'label'     => __( 'Links Hover Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-post-meta a:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'post_meta' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'meta_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'selector'  => '{{WRAPPER}} .pp-post-meta',
				'condition' => array(
					'post_meta' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_items_spacing',
			array(
				'label'      => __( 'Meta Items Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
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
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-meta .pp-meta-separator:not(:last-child)' => 'margin-left: calc({{SIZE}}{{UNIT}} / 2); margin-right: calc({{SIZE}}{{UNIT}} / 2);',
				),
				'condition'  => array(
					'post_meta' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'meta_margin_bottom',
			array(
				'label'      => __( 'Bottom Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
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
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-post-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'post_meta' => 'yes',
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
				'label'     => __( 'Read More Button', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'     => __( 'Size', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'sm',
				'options'   => array(
					'xs' => __( 'Extra Small', 'powerpack' ),
					'sm' => __( 'Small', 'powerpack' ),
					'md' => __( 'Medium', 'powerpack' ),
					'lg' => __( 'Large', 'powerpack' ),
					'xl' => __( 'Extra Large', 'powerpack' ),
				),
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-posts-button',
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => __( 'Normal', 'powerpack' ),
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-button' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-button' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_normal',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-posts-button',
				'condition'   => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => __( 'Margin', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-posts-button',
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'info_box_button_icon_heading',
			array(
				'label'     => __( 'Button Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_button' => 'yes',
					'button_icon!'                         => '',
				),
			)
		);

		$this->add_responsive_control(
			'button_icon_margin',
			array(
				'label'       => __( 'Margin', 'powerpack' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'placeholder' => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-info-box .pp-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				),
				'condition'   => array(
					'show_button' => 'yes',
					'button_icon!'                         => '',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => __( 'Hover', 'powerpack' ),
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-button:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-button:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-button:hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_control(
			'button_animation',
			array(
				'label'     => __( 'Animation', 'powerpack' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					'show_button' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .pp-posts-button:hover',
				'condition' => array(
					'show_button' => 'yes',
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
				'label'     => __( 'Pagination', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'pagination_margin_top',
			array(
				'label'     => __( 'Gap between Posts & Pagination', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '',
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination-top .pp-posts-pagination' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-posts-pagination-bottom .pp-posts-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'load_more_button_size',
			array(
				'label'     => __( 'Size', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'sm',
				'options'   => array(
					'xs' => __( 'Extra Small', 'powerpack' ),
					'sm' => __( 'Small', 'powerpack' ),
					'md' => __( 'Medium', 'powerpack' ),
					'lg' => __( 'Large', 'powerpack' ),
					'xl' => __( 'Extra Large', 'powerpack' ),
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => 'load_more',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'pagination_typography',
				'selector'  => '{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a',
				'scheme'    => Scheme_Typography::TYPOGRAPHY_2,
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->start_controls_tabs( 'tabs_pagination' );

		$this->start_controls_tab(
			'tab_pagination_normal',
			array(
				'label'     => __( 'Normal', 'powerpack' ),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'pagination_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'pagination_link_border_normal',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a',
				'condition'   => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'pagination_link_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_responsive_control(
			'pagination_link_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pagination_link_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-posts-pagination .page-numbers, {{WRAPPER}} .pp-posts-pagination a',
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_hover',
			array(
				'label'     => __( 'Hover', 'powerpack' ),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination a:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'pagination_color_hover',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination a:hover' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_control(
			'pagination_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination a:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pagination_link_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .pp-posts-pagination a:hover',
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next', 'load_more' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_pagination_active',
			array(
				'label'     => __( 'Active', 'powerpack' ),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_link_bg_color_active',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers.current' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_color_active',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers.current' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'pagination_border_color_active',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-posts-pagination .page-numbers.current' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pagination_link_box_shadow_active',
				'selector'  => '{{WRAPPER}} .pp-posts-pagination .page-numbers.current',
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'pagination_spacing',
			array(
				'label'     => __( 'Space Between', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'body:not(.rtl) {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:first-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'body:not(.rtl) {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:last-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:first-child)' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 );',
					'body.rtl {{WRAPPER}} .pp-posts-pagination .page-numbers:not(:last-child)' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 );',
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'numbers', 'numbers_and_prev_next' ),
				),
			)
		);

		$this->add_control(
			'heading_loader',
			array(
				'label'     => __( 'Loader', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'load_more', 'infinite' ),
				),
			)
		);

		$this->add_control(
			'loader_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .pp-loader:after, {{WRAPPER}} .pp-posts-loader:after' => 'border-bottom-color: {{VALUE}}; border-top-color: {{VALUE}};',
				),
				'condition' => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'load_more', 'infinite' ),
				),
			)
		);

		$this->add_responsive_control(
			'loader_size',
			array(
				'label'      => __( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
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
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-posts-loader' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'layout!'          => 'carousel',
					'pagination_type!' => array( 'load_more', 'infinite' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Filters.
	 *
	 * Returns the Filter HTML.
	 *
	 * @since 1.7.0
	 * @access public
	 */
	public function render_filters() {
		$settings = $this->get_settings_for_display();

		$layout                = $settings['layout'];
		$show_filters          = $settings['show_filters'];
		$show_filters_count    = $settings['show_filters_count'];
		$show_ajax_search_form = $settings['show_ajax_search_form'];
		$search_form_action    = $settings['search_form_action'];

		if ( 'carousel' == $layout ) {
			return;
		}

		if ( 'yes' != $show_filters && 'yes' != $show_ajax_search_form ) {
			return;
		}

		$filters   = $this->get_filter_values();
		$all_label = $settings['filter_all_label'];

		$this->add_render_attribute( 'filters-container', 'class', 'pp-post-filters-container' );

		if ( 'yes' === $show_ajax_search_form ) {
			$this->add_render_attribute(
				'filters-container',
				array(
					'data-search-form'   => 'show',
					'data-search-action' => $search_form_action,
				)
			);
		}

		$enable_active_filter = $settings['enable_active_filter'];
		if ( $enable_active_filter == 'yes' ) {
			$filter_active = $settings['filter_active'];
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'filters-container' ); ?>>
			<?php if ( 'yes' == $show_filters ) { ?>
			<div class="pp-post-filters-wrap">
				<ul class="pp-post-filters">
					<li class="pp-post-filter <?php echo ( $enable_active_filter == 'yes' ) ? '' : 'pp-filter-current'; ?>" data-filter="*" data-taxonomy=""><?php echo ( 'All' == $all_label || '' == $all_label ) ? __( 'All', 'powerpack' ) : $all_label; ?></li>
					<?php foreach ( $filters as $key => $value ) { ?>
						<?php
						if ( 'yes' == $show_filters_count ) {
							$filter_value = $value->name . '<span class="pp-post-filter-count">' . $value->count . '</span>';
						} else {
							$filter_value = $value->name;
						}
						?>
						<?php if ( $enable_active_filter == 'yes' && ( $key == $filter_active ) ) { ?>
					<li class="pp-post-filter pp-filter-current" data-filter="<?php echo '.' . $value->slug; ?>" data-taxonomy="<?php echo '.' . $value->taxonomy; ?>"><?php echo $filter_value; ?></li>
					<?php } else { ?>
					<li class="pp-post-filter" data-filter="<?php echo '.' . $value->slug; ?>" data-taxonomy="<?php echo '.' . $value->taxonomy; ?>"><?php echo $filter_value; ?></li>
							<?php
					}
					}
					?>
				</ul>
			</div>
			<?php } ?>
			<?php $this->render_search_form(); ?>
		</div>
		<?php
	}

	/**
	 * Get Masonry classes array.
	 *
	 * Returns the Masonry classes array.
	 *
	 * @since x.x.x
	 * @access public
	 */
	public function get_masonry_classes() {
		$settings = $this->get_settings_for_display();

		$post_type = $settings['post_type'];
		$filter_by = $settings[ 'tax_' . $post_type . '_filter' ];

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
		$settings   = $this->get_settings_for_display();
		$post_terms = $settings['post_terms'];

		if ( 'yes' !== $post_terms ) {
			return;
		}

		$post_type = $settings['post_type'];

		if ( 'related' === $settings['post_type'] ) {
			$post_type = get_post_type();
		}

		$taxonomies = $settings[ 'tax_badge_' . $post_type ];

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

		$max_terms = $settings['max_terms'];

		if ( $max_terms ) {
			$terms = array_slice( $terms, 0, $max_terms );
		}

		$terms = apply_filters( 'ppe_posts_terms', $terms );

		$link_terms = $settings['post_taxonomy_link'];

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
					printf( $format, $term->name, get_term_link( (int) $term->term_id ) );
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
		$settings = $this->get_settings_for_display();

		if ( ! $item_type ) {
			return;
		}

		$show_item   = $settings[ 'show_' . $item_type ];
		$item_link   = $settings[ $item_type . '_link' ];
		$item_icon   = $settings[ $item_type . '_icon' ];
		$item_prefix = $settings[ $item_type . '_prefix' ];

		if ( 'yes' !== $show_item ) {
			return;
		}
		?>
		<?php do_action( 'ppe_before_single_post_' . $item_type, get_the_ID(), $settings ); ?>
		<span class="pp-post-<?php echo $item_type; ?>">
			<?php
			if ( $item_icon ) {
				?>
				<span class="pp-meta-icon <?php echo $item_icon; ?>">
				</span>
				<?php
			}

			if ( $item_prefix ) {
				?>
				<span class="pp-meta-prefix">
				<?php
					echo $item_prefix;
				?>
				</span>
				<?php
			}
			?>
			<span class="pp-meta-text">
				<?php
				if ( 'author' === $item_type ) {
					echo $this->get_post_author( $item_link );
				} elseif ( 'date' === $item_type ) {
					if ( $item_link == 'yes' ) {
						echo '<a href="' . get_permalink() . '">' . $this->get_post_date() . '</a>';
					} else {
						echo $this->get_post_date();
					}
				} elseif ( 'comments' === $item_type ) {
					echo $this->get_post_comments();
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
		 * @since x.x.x
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
		$settings    = $this->get_settings_for_display();
		$date_format = $settings['date_format'];
		$date        = '';

		switch ( $date_format ) {
			case 'ago':
				$date = sprintf( _x( '%s ago', '%s = human-readable time difference', 'powerpack' ), human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) );
				break;

			case 'modified':
				$date = get_the_modified_date( '', get_the_ID() );
				break;

			case 'custom':
				$date_custom_format = $settings['date_custom_format'];
				$date               = ( $date_custom_format ) ? get_the_date( $date_custom_format ) : get_the_date();
				break;

			case 'key':
				$date_meta_key = $settings['date_meta_key'];
				if ( $date_meta_key ) {
					$date = get_post_meta( get_the_ID(), $date_meta_key, 'true' );
				}
				break;

			default:
				$date = get_the_date();
				break;
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
		$settings              = $this->get_settings_for_display();
		$image                 = $settings['show_thumbnail'];
		$fallback_image        = $settings['fallback_image'];
		$fallback_image_custom = $settings['fallback_image_custom'];

		if ( 'yes' !== $image ) {
			return;
		}

		if ( has_post_thumbnail() ) {

			$image_id = get_post_thumbnail_id( get_the_ID() );

			$setting_key              = 'thumbnail';
			$settings[ $setting_key ] = array(
				'id' => $image_id,
			);
			$thumbnail_html           = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

		} elseif ( 'default' === $fallback_image ) {

			$thumbnail_url  = Utils::get_placeholder_image_src();
			$thumbnail_html = '<img src="' . $thumbnail_url . '"/>';

		} elseif ( 'custom' === $fallback_image ) {

			$custom_image_id          = $fallback_image_custom['id'];
			$setting_key              = 'thumbnail';
			$settings[ $setting_key ] = array(
				'id' => $custom_image_id,
			);
			$thumbnail_html           = Group_Control_Image_Size::get_attachment_image_html( $settings, $setting_key );

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
		$settings = $this->get_settings_for_display();

		$show_post_title      = $settings['post_title'];
		$title_tag            = $settings['title_html_tag'];
		$title_link           = $settings['post_title_link'];
		$post_title_separator = $settings['post_title_separator'];

		if ( 'yes' !== $show_post_title ) {
			return;
		}

		$post_title = get_the_title();
		/**
		 * Post Title Filter
		 *
		 * Filters post title
		 *
		 * @since x.x.x
		 * @param string    $post_title     The original text
		 * @param int       get_the_id()    The post ID
		 */
		$post_title = apply_filters( 'ppe_posts_title', $post_title, get_the_ID() );
		if ( $post_title ) {
			?>
			<?php do_action( 'ppe_before_single_post_title', get_the_ID(), $settings ); ?>
			<<?php echo $title_tag; ?> class="pp-post-title">
				<?php if ( $title_link == 'yes' ) { ?>
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php echo $post_title; ?>
					</a>
					<?php
				} else {
					echo $post_title; }
				?>
			</<?php echo $title_tag; ?>>
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
		$settings = $this->get_settings_for_display();

		$image_link = $settings['thumbnail_link'];

		$thumbnail_html = $this->get_post_thumbnail();

		if ( empty( $thumbnail_html ) ) {
			return;
		}

		if ( 'yes' === $image_link ) {

			$thumbnail_html = '<a href="' . get_the_permalink() . '">' . $thumbnail_html . '</a>';

		}
		do_action( 'ppe_before_single_post_thumbnail', get_the_ID(), $settings );
		?>
		<div class="pp-post-thumbnail">
			<div class="pp-post-thumbnail-wrap">
				<?php echo $thumbnail_html; ?>
			</div>
		</div>
		<?php
		do_action( 'ppe_after_single_post_thumbnail', get_the_ID(), $settings );
	}

	/**
	 * Get post excerpt length.
	 *
	 * Returns the length of post excerpt.
	 *
	 * @since x.x.x
	 * @access public
	 */
	public function pp_excerpt_length_filter() {
        $settings = $this->get_settings_for_display();

		return $settings['excerpt_length'];
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
	 * Get post excerpt.
	 *
	 * Returns the post excerpt HTML wrap.
	 *
	 * @since x.x.x
	 * @access public
	 */
	public function render_excerpt() {
		$settings       = $this->get_settings_for_display();
		$show_excerpt   = $settings['show_excerpt'];
		$excerpt_length = $settings['excerpt_length'];
		$content_type   = $settings['content_type'];
		$content_length = $settings['content_length'];

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
				the_content();
			} elseif ( 'content' === $content_type ) {
				$more = '...';
				echo wp_trim_words( get_the_content(), $content_length, apply_filters( 'pp_posts_content_limit_more', $more ) );
			} else {
				add_filter( 'excerpt_length', array( $this, 'pp_excerpt_length_filter' ), 20 );
				add_filter( 'excerpt_more', array( $this, 'pp_excerpt_more_filter' ), 20 );
				the_excerpt();
				remove_filter( 'excerpt_length', array( $this, 'pp_excerpt_length_filter' ), 20 );
				remove_filter( 'excerpt_more', array( $this, 'pp_excerpt_more_filter' ), 20 );
			}
			?>
		</div>
		<?php do_action( 'ppe_after_single_post_excerpt', get_the_ID(), $settings ); ?>
		<?php
	}

	/**
	 * Render button output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_button() {
		$settings         = $this->get_settings_for_display();
		$show_button      = $settings['show_button'];
		$button_animation = $settings['button_animation'];

		if ( 'yes' !== $show_button ) {
			return;
		}

		$button_text          = $settings['button_text'];
		$button_icon          = $settings['button_icon'];
		$button_icon_position = $settings['button_icon_position'];
		$button_size          = $settings['button_size'];

		$classes = array(
			'pp-posts-button',
			'elementor-button',
			'elementor-size-' . $button_size,
		);

		if ( $button_animation ) {
			$classes[] = 'elementor-animation-' . $button_animation;
		}

		/**
		 * Button Text Filter
		 *
		 * Filters the text for the button
		 *
		 * @since x.x.x
		 * @param string    $button_text    The original text
		 * @param int       get_the_id()    The post ID
		 */
		$button_text = apply_filters( 'ppe_posts_button_text', $button_text, get_the_ID() );
		?>
		<?php do_action( 'ppe_before_single_post_button', get_the_ID(), $settings ); ?>
		<a class="<?php echo implode( ' ', $classes ); ?>" href="<?php echo get_the_permalink(); ?>">
			<?php if ( $button_icon && 'before' === $button_icon_position ) { ?>
				<span class="pp-button-icon <?php echo esc_attr( $button_icon ); ?>" aria-hidden="true"></span>
			<?php } ?>
			<?php if ( $button_text ) { ?>
				<span class="pp-button-text">
					<?php echo esc_html( $button_text ); ?>
				</span>
			<?php } ?>
			<?php if ( $button_icon && 'after' === $button_icon_position ) { ?>
				<span class="pp-button-icon <?php echo esc_attr( $button_icon ); ?>" aria-hidden="true"></span>
			<?php } ?>
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
		$this->query_posts( $filter, $taxonomy, $search );

		$query       = $this->get_query();
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
	 * Get Pagination.
	 *
	 * Returns the Pagination HTML.
	 *
	 * @since x.x.x
	 * @access public
	 */
	public function render_pagination() {
		$settings = $this->get_settings_for_display();

		$pagination_type    = $settings['pagination_type'];
		$page_limit         = $settings['pagination_page_limit'];
		$pagination_shorten = $settings['pagination_numbers_shorten'];

		if ( 'none' === $pagination_type ) {
			return;
		}

		// Get current page number.
		$paged = $this->get_paged();

		$query       = $this->get_query();
		$total_pages = $query->max_num_pages;

		if ( 'load_more' !== $pagination_type || 'infinite' !== $pagination_type ) {

			if ( '' !== $page_limit && null !== $page_limit ) {
				$total_pages = min( $page_limit, $total_pages );
			}
		}

		if ( 2 > $total_pages ) {
			return;
		}

		$has_numbers   = in_array( $pagination_type, array( 'numbers', 'numbers_and_prev_next' ) );
		$has_prev_next = ( 'numbers_and_prev_next' === $pagination_type );
		$is_load_more  = ( 'load_more' === $pagination_type );
		$is_infinite   = ( 'infinite' === $pagination_type );

		$links = array();

		if ( $has_numbers || $is_infinite ) {

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
			$prev_label = $settings['pagination_prev_label'];
			$next_label = $settings['pagination_next_label'];

			$paginate_args['prev_next'] = true;

			if ( $prev_label ) {
				$paginate_args['prev_text'] = $prev_label;
			}
			if ( $next_label ) {
				$paginate_args['next_text'] = $next_label;
			}
		}

		if ( $has_numbers || $has_prev_next || $is_infinite ) {

			if ( is_singular() && ! is_front_page() ) {
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
			$pagination_ajax = $settings['pagination_ajax'];

			if ( 'yes' === $pagination_ajax ) {
				$pagination_type = 'ajax';
			} else {
				$pagination_type = 'standard';
			}
			?>
			<nav class="pp-posts-pagination pp-posts-pagination-<?php echo $pagination_type; ?> elementor-pagination" role="navigation" aria-label="<?php _e( 'Pagination', 'powerpack' ); ?>">
				<?php echo implode( PHP_EOL, $links ); ?>
			</nav>
			<?php
		}

		if ( $is_load_more ) {
			$load_more_label                = $settings['pagination_load_more_label'];
			$load_more_button_icon          = $settings['pagination_load_more_button_icon'];
			$load_more_button_icon_position = $settings['pagination_load_more_button_icon_position'];
			$load_more_button_size          = $settings['load_more_button_size'];
			?>
			<div class="pp-post-load-more-wrap pp-posts-pagination">
				<a class="pp-post-load-more elementor-button elementor-size-<?php echo $load_more_button_size; ?>" href="javascript:void(0);">
					<?php if ( $load_more_button_icon && 'before' === $load_more_button_icon_position ) { ?>
						<span class="pp-button-icon <?php echo esc_attr( $load_more_button_icon ); ?>" aria-hidden="true"></span>
					<?php } ?>
					<?php if ( $load_more_label ) { ?>
						<span class="pp-button-text">
							<?php echo esc_html( $load_more_label ); ?>
						</span>
					<?php } ?>
					<?php if ( $load_more_button_icon && 'after' === $load_more_button_icon_position ) { ?>
						<span class="pp-button-icon <?php echo esc_attr( $load_more_button_icon ); ?>" aria-hidden="true"></span>
					<?php } ?>
				</a>
			</div>
			<?php
		}
	}

	public function get_posts_outer_wrap_classes() {
		$settings = $this->get_settings_for_display();

		$classes = array(
			'pp-posts-container',
		);

		if ( 'infinite' === $settings['pagination_type'] ) {
			$classes[] = 'pp-posts-infinite-scroll';
		}

		return apply_filters( 'ppe_posts_outer_wrap_classes', $classes );
	}

	public function get_posts_wrap_classes() {
		$settings = $this->get_settings_for_display();

		$classes = array(
			'pp-posts',
			'pp-posts-skin-' . $this->get_id(),
		);

		if ( 'carousel' === $settings['layout'] ) {
			$classes[] = 'pp-posts-carousel';
			$classes[] = 'pp-slick-slider';
		} else {
			$classes[] = 'pp-elementor-grid';
			$classes[] = 'pp-posts-grid';
		}

		return apply_filters( 'ppe_posts_wrap_classes', $classes );
	}

	public function get_item_wrap_classes() {
		$settings = $this->get_settings_for_display();

		$classes = array( 'pp-post-wrap' );

		if ( 'carousel' === $settings['layout'] ) {
			$classes[] = 'pp-carousel-item-wrap';
		} else {
			$classes[] = 'pp-grid-item-wrap';
		}

		return implode( ' ', $classes );
	}

	public function get_item_classes() {
		$settings = $this->get_settings_for_display();

		$classes = array();

		$classes[] = 'pp-post';

		if ( 'carousel' === $settings['layout'] ) {
			$classes[] = 'pp-carousel-item';
		} else {
			$classes[] = 'pp-grid-item';
		}

		return implode( ' ', $classes );
	}

	/**
	 * Render post meta output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_post_meta() {
		$settings  = $this->get_settings_for_display();

		if ( 'yes' === $settings['post_meta'] ) {
			?>
			<?php do_action( 'ppe_before_single_post_meta', get_the_ID(), $settings ); ?>
			<div class="pp-post-meta">
				<?php
					// Post Author
					$this->render_meta_item( 'author' );

					// Post Date
					$this->render_meta_item( 'date' );

					// Post Comments
					$this->render_meta_item( 'comments' );
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
		$settings = $this->get_settings_for_display();

		$post_terms         = $settings['post_terms'];
		$post_meta          = $settings['post_meta'];
		$thumbnail_location = $settings['thumbnail_location'];

		do_action( 'ppe_before_single_post_wrap', get_the_ID(), $settings );
		?>
		<div class="<?php echo $this->get_item_wrap_classes(); ?>">
			<?php do_action( 'ppe_before_single_post', get_the_ID(), $settings ); ?>
			<div class="<?php echo $this->get_item_classes(); ?>">
				<?php
				if ( 'outside' === $thumbnail_location ) {
					$this->render_post_thumbnail();
				}
				?>

				<?php do_action( 'ppe_before_single_post_content', get_the_ID(), $settings ); ?>

				<div class="pp-post-content">
					<?php
					if ( 'inside' === $thumbnail_location ) {
						$this->render_post_thumbnail();
					}

					$this->render_terms();

					$this->render_post_title();

					$this->render_post_meta();

					$this->render_excerpt();

					$this->render_button();
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
	 * @since x.x.x
	 * @access public
	 */
	public function render_search() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="pp-posts-empty">
			<?php if ( $settings['nothing_found_message'] ) { ?>
				<p><?php echo $settings['nothing_found_message']; ?></p>
			<?php } ?>

			<?php if ( $settings['show_search_form'] === 'yes' ) { ?>
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
		$autoplay        = $settings['autoplay'];
		$autoplay_speed  = $settings['autoplay_speed'];
		$arrows          = $settings['arrows'];
		$arrow           = $settings['arrow'];
		$dots            = $settings['dots'];
		$animation_speed = $settings['animation_speed'];
		$infinite_loop   = $settings['infinite_loop'];
		$pause_on_hover  = $settings['pause_on_hover'];
		$adaptive_height = $settings['adaptive_height'];
		$direction       = $settings['direction'];

		$slides_to_show          = ( $settings['columns'] !== '' ) ? absint( $settings['columns'] ) : 3;
		$slides_to_show_tablet   = ( $settings['columns_tablet'] !== '' ) ? absint( $settings['columns_tablet'] ) : 2;
		$slides_to_show_mobile   = ( $settings['columns_mobile'] !== '' ) ? absint( $settings['columns_mobile'] ) : 2;
		$slides_to_scroll        = ( $settings['slides_to_scroll'] !== '' ) ? absint( $settings['slides_to_scroll'] ) : 1;
		$slides_to_scroll_tablet = ( $settings['slides_to_scroll_tablet'] !== '' ) ? absint( $settings['slides_to_scroll_tablet'] ) : 1;
		$slides_to_scroll_mobile = ( $settings['slides_to_scroll_mobile'] !== '' ) ? absint( $settings['slides_to_scroll_mobile'] ) : 1;

		$slider_options = array(
			'slidesToShow'   => $slides_to_show,
			'slidesToScroll' => $slides_to_scroll,
			'autoplay'       => ( 'yes' === $autoplay ),
			'autoplaySpeed'  => ( '' !== $autoplay_speed ) ? $autoplay_speed : 3000,
			'arrows'         => ( 'yes' === $arrows ),
			'dots'           => ( 'yes' === $dots ),
			'speed'          => ( '' !== $animation_speed ) ? $animation_speed : 600,
			'infinite'       => ( 'yes' === $infinite_loop ),
			'pauseOnHover'   => ( 'yes' === $pause_on_hover ),
			'adaptiveHeight' => ( 'yes' === $adaptive_height ),
		);

		if ( 'right' === $direction ) {
			$slider_options['rtl'] = true;
		}

		if ( 'yes' === $arrows ) {
			if ( $arrow ) {
				$pa_next_arrow = $arrow;
				$pa_prev_arrow = str_replace( 'right', 'left', $arrow );
			} else {
				$pa_next_arrow = 'fa fa-angle-right';
				$pa_prev_arrow = 'fa fa-angle-left';
			}

			$slider_options['prevArrow'] = '<div class="pp-slider-arrow pp-arrow pp-arrow-prev"><i class="' . $pa_prev_arrow . '"></i></div>';
			$slider_options['nextArrow'] = '<div class="pp-slider-arrow pp-arrow pp-arrow-next"><i class="' . $pa_next_arrow . '"></i></div>';
		}

		$slider_options['responsive'] = array(
			array(
				'breakpoint' => 1024,
				'settings'   => array(
					'slidesToShow'   => $slides_to_show_tablet,
					'slidesToScroll' => $slides_to_scroll_tablet,
				),
			),
			array(
				'breakpoint' => 768,
				'settings'   => array(
					'slidesToShow'   => $slides_to_show_mobile,
					'slidesToScroll' => $slides_to_scroll_mobile,
				),
			),
		);

		$this->add_render_attribute(
			'posts-wrap',
			array(
				'data-slider-settings' => wp_json_encode( $slider_options ),
			)
		);
	}

	protected function render() {
        $settings = $this->get_settings_for_display();

		$query_type          = $settings['query_type'];
		$layout              = $settings['layout'];
		$pagination_type     = $settings['pagination_type'];
		$pagination_position = $settings['pagination_position'];
		$equal_height        = $settings['equal_height'];
		$direction           = $settings['direction'];
		$skin                = $this->get_id();
		$posts_outer_wrap    = $this->get_posts_outer_wrap_classes();
		$posts_wrap          = $this->get_posts_wrap_classes();
		$page_id             = '';
		if ( null != \Elementor\Plugin::$instance->documents->get_current() ) {
			$page_id = \Elementor\Plugin::$instance->documents->get_current()->get_main_id();
		}

		$this->add_render_attribute( 'posts-container', 'class', $posts_outer_wrap );

		$this->add_render_attribute( 'posts-wrap', 'class', $posts_wrap );

		if ( $layout == 'carousel' ) {
			if ( $equal_height == 'yes' ) {
				$this->add_render_attribute( 'posts-wrap', 'data-equal-height', 'yes' );
			}
			if ( $direction == 'right' ) {
				$this->add_render_attribute( 'posts-wrap', 'dir', 'rtl' );
			}
		}

		$this->add_render_attribute(
			'posts-wrap',
			array(
				'data-query-type' => $query_type,
				'data-layout'     => $layout,
				'data-page'       => $page_id,
				'data-skin'       => $skin,
			)
		);

		$this->add_render_attribute( 'post-categories', 'class', 'pp-post-categories' );

		// Filters
		if ( $settings['post_type'] != 'related' ) {
			$this->render_filters();
		}

		if ( $layout == 'carousel' ) {
			$this->slider_settings();
		}
		?>

		<?php do_action( 'ppe_before_posts_outer_wrap', $settings ); ?>

		<div <?php echo $this->get_render_attribute_string( 'posts-container' ); ?>>
			<?php
				do_action( 'ppe_before_posts_wrap', $settings );

				$i = 1;

				$enable_active_filter = $settings['enable_active_filter'];
			if ( $enable_active_filter == 'yes' ) {
				$filter_active = $settings['filter_active'];
				$filters       = $this->get_filter_values();
				$taxonomy      = $filters[ $filter_active ]->taxonomy;
				$filter        = $filters[ $filter_active ]->slug;
			} else {
				$filter   = '';
				$taxonomy = '';
			}
				$this->query_posts( $filter, $taxonomy );
				$query = $this->get_query();

			if ( ! $query->found_posts ) {

				$this->render_search();

				return;
			}

				$total_pages = $query->max_num_pages;
			?>
			
			<?php if ( 'carousel' != $layout ) { ?>
				<?php if ( ( 'numbers' == $pagination_type || 'numbers_and_prev_next' == $pagination_type ) && ( 'top' == $pagination_position || 'top-bottom' == $pagination_position ) ) { ?>
				<div class="pp-posts-pagination-wrap pp-posts-pagination-top" data-total="<?php echo $total_pages; ?>">
					<?php
						$this->render_pagination();
					?>
				</div>
			<?php } ?>
			<?php } ?>
			
			<div <?php echo $this->get_render_attribute_string( 'posts-wrap' ); ?>>
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
			</div>
			
			<?php do_action( 'ppe_after_posts_wrap', $settings ); ?>
			
			<?php if ( 'load_more' == $pagination_type || 'infinite' == $pagination_type ) { ?>
			<div class="pp-posts-loader"></div>
			<?php } ?>
			
			<?php
			if ( 'load_more' == $pagination_type || 'infinite' == $pagination_type ) {
				$pagination_bottom = true;
			} elseif ( ( 'numbers' == $pagination_type || 'numbers_and_prev_next' == $pagination_type ) && ( '' == $pagination_position || 'bottom' == $pagination_position || 'top-bottom' == $pagination_position ) ) {
				$pagination_bottom = true;
			} else {
				$pagination_bottom = false;
			}
			?>
			
			<?php if ( 'carousel' != $layout ) { ?>
				<?php if ( $pagination_bottom ) { ?>
				<div class="pp-posts-pagination-wrap pp-posts-pagination-bottom" data-total="<?php echo $total_pages; ?>">
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
}
