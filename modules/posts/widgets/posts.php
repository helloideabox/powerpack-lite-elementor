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
class Posts extends Powerpack_Widget {

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

		$this->end_controls_section();

		/**
		 * Content Tab: Query
		 */
		$this->start_controls_section(
			'section_query',
			[
				'label'                 => __( 'Query', 'powerpack' ),
			]
		);

		$this->add_control(
			'query_type',
			[
				'label'                 => __( 'Query Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'custom',
				'label_block'           => true,
				'options'               => [
					'main'      => __( 'Main Query', 'powerpack' ),
					'custom'    => __( 'Custom Query', 'powerpack' ),
				],
			]
		);

		$post_types = PP_Posts_Helper::get_post_types();
		$post_types['related'] = __( 'Related', 'powerpack' );

		$this->add_control(
			'post_type',
			[
				'label'                 => __( 'Post Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => $post_types,
				'default'               => 'post',

			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'                 => __( 'Posts Per Page', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => 6,
			]
		);

		$post_types = PP_Posts_Helper::get_post_types();

		foreach ( $post_types as $post_type_slug => $post_type_label ) {

			$taxonomy = PP_Posts_Helper::get_post_taxonomies( $post_type_slug );

			if ( ! empty( $taxonomy ) ) {

				foreach ( $taxonomy as $index => $tax ) {

					$terms = get_terms( $index );

					$tax_terms = array();

					if ( ! empty( $terms ) ) {

						foreach ( $terms as $term_index => $term_obj ) {

							$tax_terms[ $term_obj->term_id ] = $term_obj->name;
						}

						$tax_control_key = $index . '_' . $post_type_slug;

						// Taxonomy filter type
						$this->add_control(
							$index . '_' . $post_type_slug . '_filter_type',
							[
								/* translators: %s Label */
								'label'       => sprintf( __( '%s Filter Type', 'powerpack' ), $tax->label ),
								'type'        => Controls_Manager::SELECT,
								'default'     => 'IN',
								'label_block' => true,
								'options'     => [
									/* translators: %s label */
									'IN'     => sprintf( __( 'Include %s', 'powerpack' ), $tax->label ),
									/* translators: %s label */
									'NOT IN' => sprintf( __( 'Exclude %s', 'powerpack' ), $tax->label ),
								],
								'separator'         => 'before',
								'condition'   => [
									'post_type' => $post_type_slug,
								],
							]
						);

						// Add control for all taxonomies.
						$this->add_control(
							$tax_control_key,
							[
								'label'       => $tax->label,
								'type'        => Controls_Manager::SELECT2,
								'multiple'    => true,
								'default'     => '',
								'label_block' => true,
								'options'     => $tax_terms,
								'condition'   => [
									'post_type' => $post_type_slug,
								],
							]
						);

					}
				}
			}
		}

		$this->add_control(
			'author_filter_type',
			[
				'label'                 => __( 'Authors Filter Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'author__in',
				'label_block'           => true,
				'separator'             => 'before',
				'options'               => [
					'author__in'     => __( 'Include Authors', 'powerpack' ),
					'author__not_in' => __( 'Exclude Authors', 'powerpack' ),
				],
				'condition'         => [
					'post_type!' => 'related',
				],
			]
		);

		$this->add_control(
			'authors',
			[
				'label'                 => __( 'Authors', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT2,
				'label_block'           => true,
				'multiple'              => true,
				'options'               => PP_Posts_Helper::get_users(),
				'condition'         => [
					'post_type!' => 'related',
				],
			]
		);

		$post_types = PP_Posts_Helper::get_post_types();

		foreach ( $post_types as $post_type_slug => $post_type_label ) {

			$posts_all = PP_Posts_Helper::get_all_posts_by_type( $post_type_slug );

			$this->add_control(
				$post_type_slug . '_filter_type',
				[
					'label'             => sprintf( __( '%s Filter Type', 'powerpack' ), $post_type_label ),
					'type'              => Controls_Manager::SELECT,
					'default'           => 'post__not_in',
					'label_block'       => true,
					'separator'         => 'before',
					'options'           => [
						'post__in'     => sprintf( __( 'Include %s', 'powerpack' ), $post_type_label ),
						'post__not_in' => sprintf( __( 'Exclude %s', 'powerpack' ), $post_type_label ),
					],
					'condition'         => [
						'post_type' => $post_type_slug,
					],
				]
			);

			$this->add_control(
				$post_type_slug . '_filter',
				[
					/* translators: %s Label */
					'label'             => $post_type_label,
					'type'              => Controls_Manager::SELECT2,
					'default'           => '',
					'multiple'          => true,
					'label_block'       => true,
					'options'           => $posts_all,
					'condition'         => [
						'post_type' => $post_type_slug,
					],
				]
			);
		}

		$taxonomy = PP_Posts_Helper::get_post_taxonomies( $post_type_slug );
		$taxonomies = array();
		foreach ( $taxonomy as $index => $tax ) {
			$taxonomies[ $tax->name ] = $tax->label;
		}

		$this->start_controls_tabs(
			'tabs_related',
			[
				'condition'             => [
					'post_type' => 'related',
				],
			]
		);

		$this->start_controls_tab(
			'tab_related_include',
			[
				'label'                 => __( 'Include', 'powerpack' ),
				'condition'             => [
					'post_type' => 'related',
				],
			]
		);

		$this->add_control(
			'related_include_by',
			[
				'label'                 => __( 'Include By', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT2,
				'default'               => '',
				'label_block'           => true,
				'multiple'              => true,
				'options'               => [
					'terms'     => __( 'Term', 'powerpack' ),
					'authors'   => __( 'Author', 'powerpack' ),
				],
				'condition'             => [
					'post_type' => 'related',
				],
			]
		);

		$this->add_control(
			'related_filter_include',
			[
				'label'                 => __( 'Term', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT2,
				'default'               => '',
				'label_block'           => true,
				'multiple'              => true,
				'options'               => PP_Posts_Helper::get_taxonomies_options(),
				'condition'             => [
					'post_type' => 'related',
					'related_include_by' => 'terms',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_related_exclude',
			[
				'label'                 => __( 'Exclude', 'powerpack' ),
				'condition'             => [
					'post_type' => 'related',
				],
			]
		);

		$this->add_control(
			'related_exclude_by',
			[
				'label'                 => __( 'Exclude By', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT2,
				'default'               => '',
				'label_block'           => true,
				'multiple'              => true,
				'options'               => [
					'current_post'  => __( 'Current Post', 'powerpack' ),
					'authors'       => __( 'Author', 'powerpack' ),
				],
				'condition'             => [
					'post_type' => 'related',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'related_fallback',
			[
				'label'                 => __( 'Fallback', 'powerpack' ),
				'description'           => __( 'Displayed if no relevant results are found.', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'none'      => __( 'None', 'powerpack' ),
					'recent'    => __( 'Recent Posts', 'powerpack' ),
				],
				'default'               => 'none',
				'label_block'           => false,
				'separator'             => 'before',
				'condition'             => [
					'post_type' => 'related',
				],
			]
		);

		$this->add_control(
			'select_date',
			[
				'label'                 => __( 'Date', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'anytime'   => __( 'All', 'powerpack' ),
					'today'     => __( 'Past Day', 'powerpack' ),
					'week'      => __( 'Past Week', 'powerpack' ),
					'month'     => __( 'Past Month', 'powerpack' ),
					'quarter'   => __( 'Past Quarter', 'powerpack' ),
					'year'      => __( 'Past Year', 'powerpack' ),
					'exact'     => __( 'Custom', 'powerpack' ),
				],
				'default'               => 'anytime',
				'label_block'           => false,
				'multiple'              => false,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'date_before',
			[
				'label'                 => __( 'Before', 'powerpack' ),
				'description'           => __( 'Setting a ‘Before’ date will show all the posts published until the chosen date (inclusive).', 'powerpack' ),
				'type'                  => Controls_Manager::DATE_TIME,
				'label_block'           => false,
				'multiple'              => false,
				'placeholder'           => __( 'Choose', 'powerpack' ),
				'condition'             => [
					'select_date' => 'exact',
				],
			]
		);

		$this->add_control(
			'date_after',
			[
				'label'                 => __( 'After', 'powerpack' ),
				'description'           => __( 'Setting an ‘After’ date will show all the posts published since the chosen date (inclusive).', 'powerpack' ),
				'type'                  => Controls_Manager::DATE_TIME,
				'label_block'           => false,
				'multiple'              => false,
				'placeholder'           => __( 'Choose', 'powerpack' ),
				'condition'             => [
					'select_date' => 'exact',
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'                 => __( 'Order', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'DESC'       => __( 'Descending', 'powerpack' ),
					'ASC'        => __( 'Ascending', 'powerpack' ),
				],
				'default'               => 'DESC',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'                 => __( 'Order By', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'date'           => __( 'Date', 'powerpack' ),
					'modified'       => __( 'Last Modified Date', 'powerpack' ),
					'rand'           => __( 'Random', 'powerpack' ),
					'comment_count'  => __( 'Comment Count', 'powerpack' ),
					'title'          => __( 'Title', 'powerpack' ),
					'ID'             => __( 'Post ID', 'powerpack' ),
					'author'         => __( 'Post Author', 'powerpack' ),
				],
				'default'               => 'date',
			]
		);

		$this->add_control(
			'sticky_posts',
			[
				'label'                 => __( 'Sticky Posts', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'all_sticky_posts',
			[
				'label'                 => __( 'Show Only Sticky Posts', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'sticky_posts' => 'yes',
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label'                 => __( 'Offset', 'powerpack' ),
				'description'           => __( 'Use this setting to skip this number of initial posts', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => '',
				'separator'             => 'before',
				'condition'             => [
					'post_type!' => 'related',
				],
			]
		);

		$this->add_control(
			'query_id',
			[
				'label'                 => __( 'Query ID', 'powerpack' ),
				'description'           => __( 'Give your Query a custom unique id to allow server side filtering', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'heading_nothing_found',
			[
				'label'                 => __( 'If Nothing Found!', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'nothing_found_message',
			[
				'label'                 => __( 'Nothing Found Message', 'powerpack' ),
				'type'                  => Controls_Manager::TEXTAREA,
				'rows'                  => 3,
				'default'               => __( 'It seems we can\'t find what you\'re looking for.', 'powerpack' ),
			]
		);

		$this->add_control(
			'show_search_form',
			[
				'label'                 => __( 'Show Search Form', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'label_on'              => __( 'Yes', 'powerpack' ),
				'label_off'             => __( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'default'               => '',
			]
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Filters
		 */
		$this->start_controls_section(
			'section_filters',
			[
				'label'                 => __( 'Filters', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Post Terms
		 */
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

		/**
		 * Content Tab: Image
		 */
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

		/**
		 * Content Tab: Title
		 */
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

		/**
		 * Content Tab: Excerpt
		 */
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

		/**
		 * Content Tab: Meta
		 */
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

		/**
		 * Content Tab: Read More Button
		 */
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

		/**
		 * Content Tab: Pagination
		 */
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

		/**
		 * Content Tab: Order
		 */
		$this->start_controls_section(
			'section_order',
			[
				'label'                 => __( 'Order', 'powerpack' ),
			]
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Help Docs
		 *
		 * @since 1.4.8
		 * @access protected
		 */
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

		/**
		 * Style Tab: Layout
		 */
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

		/**
		 * Style Tab: Box
		 */
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

		/**
		 * Style Tab: Content
		 */
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

		/**
		 * Style Tab: Image
		 */
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

		/**
		 * Style Tab: Title
		 */
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

	protected function render() {
		echo 'Posts Widget';
	}
}
