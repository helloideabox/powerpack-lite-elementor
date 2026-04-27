<?php
namespace PowerpackElementsLite\Modules\Marquee\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Config;
use PowerpackElementsLite\Classes\PP_Posts_Helper;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Marquee Widget
 */
class Marquee extends Powerpack_Widget {

	/**
	 * Retrieve Marquee widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Marquee' );
	}

	/**
	 * Retrieve Marquee widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Marquee' );
	}

	/**
	 * Retrieve Marquee widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Marquee' );
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
		return parent::get_widget_keywords( 'Marquee' );
	}

	/**
	 * Retrieve the list of scripts the Marquee widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [
			'pp-marquee',
		];
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends() {
		return [ 'widget-pp-marquee' ];
	}

	/**
	 * Register Marquee widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {
		// Controls.
		$this->register_content_marquee_controls();
		$this->register_query_section_controls();
		$this->register_content_separator_controls();
		$this->register_content_settings_controls();
		$this->register_content_help_docs_controls();

		// Style.
		$this->register_style_layout_controls();
		$this->register_style_items_controls();
		$this->register_style_text_controls();
		$this->register_style_image_controls();
		$this->register_style_separator_controls();
	}

	protected function register_content_marquee_controls() {
		$this->start_controls_section(
			'section_marquee',
			[
				'label' => esc_html__( 'Marquee', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'source',
			[
				'label'   => esc_html__( 'Source', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'custom' => esc_html__( 'Custom', 'powerpack-lite-for-elementor' ),
					'posts'  => esc_html__( 'Posts', 'powerpack-lite-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'     => esc_html__( 'Posts Count', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5,
				'condition' => [
					'source' => 'posts',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'label'     => esc_html__( 'Image Size', 'powerpack-lite-for-elementor' ),
				'default'   => 'medium_large',
				'condition' => [
					'source' => 'posts',
				],
			]
		);

		$this->add_control(
			'posts_link_to',
			[
				'label'     => esc_html__( 'Link to', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'post_url',
				'options'   => [
					'post_url' => esc_html__( 'Post URL', 'powerpack-lite-for-elementor' ),
					'custom'   => esc_html__( 'Custom URL', 'powerpack-lite-for-elementor' ),
				],
				'condition' => [
					'source' => 'posts',
				],
			]
		);

		$this->add_control(
			'posts_link',
			[
				'label'      => esc_html__( 'Link', 'powerpack-lite-for-elementor' ),
				'show_label' => false,
				'type'       => Controls_Manager::URL,
				'condition'  => [
					'source'        => 'posts',
					'posts_link_to' => 'custom',
				],
			]
		);

		$this->add_control(
			'post_link_target',
			[
				'label'        => esc_html__( 'Open in a New Tab', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => [
					'source'        => 'posts',
					'posts_link_to' => 'post_url',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_item_content' );

		$repeater->start_controls_tab(
			'tab_item_content',
			[
				'label' => esc_html__( 'Content', 'powerpack-lite-for-elementor' ),
			]
		);

		$repeater->add_control(
			'marquee_image',
			[
				'label'       => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::MEDIA,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'    => 'image',
				'label'   => esc_html__( 'Image Size', 'powerpack-lite-for-elementor' ),
				'default' => 'medium',
				'exclude' => [ 'custom' ],
			]
		);

		$repeater->add_control(
			'marquee_text',
			[
				'label'       => esc_html__( 'Text', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'rows'        => 3,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'item_link',
			[
				'type'        => Controls_Manager::URL,
				'label'       => esc_html__( 'Link', 'powerpack-lite-for-elementor' ),
				'placeholder' => 'https://example.com',
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_item_style',
			[
				'label' => esc_html__( 'Style', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->register_field_style_controls( $repeater );

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'items',
			[
				'label'       => esc_html__( 'Items', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[ 'marquee_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
					[ 'marquee_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
					[ 'marquee_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
					[ 'marquee_image' => [ 'url' => Utils::get_placeholder_image_src() ] ],
				],
				'title_field' => '<# print( marquee_text || "Item" ); #>',
				'separator'   => 'before',
				'condition'   => [
					'source' => 'custom',
				],
			]
		);

		$posts_repeater = new Repeater();

		$posts_repeater->start_controls_tabs( 'tabs_post_field_content' );

		$posts_repeater->start_controls_tab(
			'tab_post_field_content',
			[
				'label' => esc_html__( 'Content', 'powerpack-lite-for-elementor' ),
			]
		);

		$posts_repeater->add_control(
			'post_field',
			[
				'label'   => esc_html__( 'Field', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'featured_image',
				'options' => [
					'featured_image' => esc_html__( 'Featured Image', 'powerpack-lite-for-elementor' ),
					'title'          => esc_html__( 'Title', 'powerpack-lite-for-elementor' ),
					'date'           => esc_html__( 'Date', 'powerpack-lite-for-elementor' ),
					'author'         => esc_html__( 'Author', 'powerpack-lite-for-elementor' ),
				],
			]
		);

		$posts_repeater->end_controls_tab();

		$posts_repeater->start_controls_tab(
			'tab_post_field_style',
			[
				'label' => esc_html__( 'Style', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->register_field_style_controls( $posts_repeater, [
			'text_selector'       => '{{WRAPPER}} {{CURRENT_ITEM}}.pp-marquee-text',
			'text_hover_selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.pp-marquee-text:hover',
			'item_selector'       => '{{WRAPPER}} {{CURRENT_ITEM}}',
			'image_condition'     => [ 'post_field' => 'featured_image' ],
			'text_condition'      => [ 'post_field!' => 'featured_image' ],
		] );

		$posts_repeater->end_controls_tab();

		$posts_repeater->end_controls_tabs();

		$this->add_control(
			'posts_items',
			[
				'label'       => esc_html__( 'Fields', 'powerpack-lite-for-elementor' ),
				'description' => esc_html__( 'Each row becomes one marquee item per post, in this order.', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $posts_repeater->get_controls(),
				'default'     => [
					[ 'post_field' => 'featured_image' ],
					[ 'post_field' => 'title' ],
				],
				'title_field' => '{{{ post_field }}}',
				'separator'   => 'before',
				'condition'   => [
					'source' => 'posts',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register per-field style controls (image filters + text colors) on a repeater.
	 *
	 * Shared between the custom items repeater and the posts fields repeater so
	 * both expose the same Style tab without duplicating control declarations.
	 *
	 * Selectors are parameterized because the two repeaters emit different DOM:
	 * custom items carry the repeater-id class on an ancestor wrapper (descendant
	 * selector), while post fields carry it on the same span as `.pp-marquee-text`
	 * (concatenated selector).
	 *
	 * @access protected
	 *
	 * @param Repeater $repeater Repeater instance to receive the controls.
	 * @param array    $args     Optional overrides: image_selector, image_hover_selector,
	 *                           text_selector, text_hover_selector, item_selector,
	 *                           image_condition, text_condition.
	 */
	protected function register_field_style_controls( Repeater $repeater, array $args = [] ) {
		$args = array_merge( [
			'image_selector'       => '{{WRAPPER}} {{CURRENT_ITEM}} .pp-marquee-img',
			'image_hover_selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .pp-marquee-img:hover',
			'text_selector'        => '{{WRAPPER}} {{CURRENT_ITEM}} .pp-marquee-text',
			'text_hover_selector'  => '{{WRAPPER}} {{CURRENT_ITEM}} .pp-marquee-text:hover',
			'item_selector'        => '{{WRAPPER}} {{CURRENT_ITEM}} .pp-marquee-fields',
			'image_condition'      => [],
			'text_condition'       => [],
		], $args );

		$repeater->add_control(
			'field_item_style_heading',
			[
				'label' => esc_html__( 'Item', 'powerpack-lite-for-elementor' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$repeater->add_control(
			'item_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$args['item_selector'] => 'background-color: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'item_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$args['item_selector'] => 'border-color: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'field_image_style_heading',
			[
				'label'     => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => $args['image_condition'],
			]
		);

		$repeater->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'css_filters',
				'selector'  => $args['image_selector'],
				'condition' => $args['image_condition'],
			]
		);

		$repeater->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'      => 'css_hover_filters',
				'label'     => esc_html__( 'CSS Filters', 'powerpack-lite-for-elementor' ) . ' ' . esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
				'selector'  => $args['image_hover_selector'],
				'condition' => $args['image_condition'],
			]
		);

		$repeater->add_control(
			'field_text_style_heading',
			[
				'label'     => esc_html__( 'Text', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => $args['text_condition'],
			]
		);

		$repeater->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$args['text_selector'] => 'color: {{VALUE}};',
				],
				'condition' => $args['text_condition'],
			]
		);

		$repeater->add_control(
			'text_color_hover',
			[
				'label'     => esc_html__( 'Text Hover Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					$args['text_hover_selector'] => 'color: {{VALUE}};',
				],
				'condition' => $args['text_condition'],
			]
		);
	}

	/**
	 * Register simplified Posts query controls.
	 *
	 * Lite's Posts_Base query helpers are tied to the Posts widget's settings shape,
	 * so the Marquee widget builds its own minimal query controls (post type +
	 * taxonomy include/exclude + author + posts filter + orderby/order + offset)
	 * inline, matching the pattern used by the Content Ticker widget.
	 *
	 * @access protected
	 */
	protected function register_query_section_controls() {
		$this->start_controls_section(
			'section_post_query',
			[
				'label'     => esc_html__( 'Query', 'powerpack-lite-for-elementor' ),
				'condition' => [
					'source' => 'posts',
				],
			]
		);

		$this->add_control(
			'post_type',
			[
				'label'     => esc_html__( 'Post Type', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => PP_Posts_Helper::get_post_types(),
				'default'   => 'post',
				'condition' => [
					'source' => 'posts',
				],
			]
		);

		$post_types = PP_Posts_Helper::get_post_types();

		foreach ( $post_types as $post_type_slug => $post_type_label ) {

			$taxonomy = PP_Posts_Helper::get_post_taxonomies( $post_type_slug );

			if ( ! empty( $taxonomy ) && ! is_wp_error( $taxonomy ) ) {

				foreach ( $taxonomy as $index => $tax ) {

					$terms = get_terms( $index );

					if ( empty( $terms ) || is_wp_error( $terms ) ) {
						continue;
					}

					if ( 'post_tag' === $index ) {
						$tax_control_key = 'tags';
					} elseif ( 'category' === $index ) {
						$tax_control_key = 'categories';
					} else {
						$tax_control_key = $index . '_' . $post_type_slug;
					}

					$this->add_control(
						$index . '_' . $post_type_slug . '_filter_type',
						[
							/* translators: %s: Taxonomy label. */
							'label'       => sprintf( esc_html__( '%s Filter Type', 'powerpack-lite-for-elementor' ), $tax->label ),
							'type'        => Controls_Manager::SELECT,
							'default'     => 'IN',
							'label_block' => true,
							'options'     => [
								/* translators: %s: Taxonomy label. */
								'IN'     => sprintf( esc_html__( 'Include %s', 'powerpack-lite-for-elementor' ), $tax->label ),
								/* translators: %s: Taxonomy label. */
								'NOT IN' => sprintf( esc_html__( 'Exclude %s', 'powerpack-lite-for-elementor' ), $tax->label ),
							],
							'separator'   => 'before',
							'condition'   => [
								'source'    => 'posts',
								'post_type' => $post_type_slug,
							],
						]
					);

					$this->add_control(
						$tax_control_key,
						[
							'label'        => $tax->label,
							'type'         => 'pp-query',
							'post_type'    => $post_type_slug,
							'options'      => [],
							'label_block'  => true,
							'multiple'     => true,
							'query_type'   => 'terms',
							'object_type'  => $index,
							'include_type' => true,
							'condition'    => [
								'source'    => 'posts',
								'post_type' => $post_type_slug,
							],
						]
					);
				}
			}
		}

		$this->add_control(
			'author_filter_type',
			[
				'label'       => esc_html__( 'Authors Filter Type', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'author__in',
				'label_block' => true,
				'separator'   => 'before',
				'options'     => [
					'author__in'     => esc_html__( 'Include Authors', 'powerpack-lite-for-elementor' ),
					'author__not_in' => esc_html__( 'Exclude Authors', 'powerpack-lite-for-elementor' ),
				],
				'condition'   => [
					'source' => 'posts',
				],
			]
		);

		$this->add_control(
			'authors',
			[
				'label'       => esc_html__( 'Authors', 'powerpack-lite-for-elementor' ),
				'type'        => 'pp-query',
				'label_block' => true,
				'multiple'    => true,
				'query_type'  => 'authors',
				'condition'   => [
					'source' => 'posts',
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'     => esc_html__( 'Order By', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'date',
				'options'   => [
					'date'          => esc_html__( 'Date', 'powerpack-lite-for-elementor' ),
					'modified'      => esc_html__( 'Last Modified', 'powerpack-lite-for-elementor' ),
					'rand'          => esc_html__( 'Random', 'powerpack-lite-for-elementor' ),
					'comment_count' => esc_html__( 'Comment Count', 'powerpack-lite-for-elementor' ),
					'title'         => esc_html__( 'Title', 'powerpack-lite-for-elementor' ),
					'ID'            => esc_html__( 'Post ID', 'powerpack-lite-for-elementor' ),
					'author'        => esc_html__( 'Post Author', 'powerpack-lite-for-elementor' ),
				],
				'separator' => 'before',
				'condition' => [
					'source' => 'posts',
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label'     => esc_html__( 'Order', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'DESC',
				'options'   => [
					'DESC' => esc_html__( 'Descending', 'powerpack-lite-for-elementor' ),
					'ASC'  => esc_html__( 'Ascending', 'powerpack-lite-for-elementor' ),
				],
				'condition' => [
					'source' => 'posts',
				],
			]
		);

		$this->add_control(
			'offset',
			[
				'label'     => esc_html__( 'Offset', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'condition' => [
					'source' => 'posts',
				],
			]
		);

		$this->add_control(
			'sticky_posts',
			[
				'label'        => esc_html__( 'Sticky Posts', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'return_value' => 'yes',
				'condition'    => [
					'source' => 'posts',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_separator_controls() {
		$this->start_controls_section(
			'section_separator',
			[
				'label' => esc_html__( 'Separator', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'separator',
			[
				'label'   => esc_html__( 'Separator', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			]
		);

		$this->add_control(
			'separator_type',
			[
				'label'     => esc_html__( 'Type', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'icon',
				'options'   => [
					'icon'  => esc_html__( 'Icon', 'powerpack-lite-for-elementor' ),
					'text'  => esc_html__( 'Text', 'powerpack-lite-for-elementor' ),
					'image' => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
				],
				'condition' => [
					'separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'separator_icon',
			[
				'label'     => esc_html__( 'Icon', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-star-of-life',
					'library' => 'fa-solid',
				],
				'condition' => [
					'separator'       => 'yes',
					'separator_type!' => [ 'text', 'image' ],
				],
			]
		);

		$this->add_control(
			'separator_text',
			[
				'label'       => esc_html__( 'Text', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '/',
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
				'condition'   => [
					'separator'      => 'yes',
					'separator_type' => 'text',
				],
			]
		);

		$this->add_control(
			'separator_image',
			[
				'label'     => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'separator'      => 'yes',
					'separator_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'separator_image',
				'label'     => esc_html__( 'Image Size', 'powerpack-lite-for-elementor' ),
				'default'   => 'thumbnail',
				'condition' => [
					'separator'      => 'yes',
					'separator_type' => 'image',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_settings_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'direction',
			[
				'label'   => esc_html__( 'Direction', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'   => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
					'right'  => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
					'top'    => esc_html__( 'Top', 'powerpack-lite-for-elementor' ),
					'bottom' => esc_html__( 'Bottom', 'powerpack-lite-for-elementor' ),
				],
			]
		);

		$this->add_responsive_control(
			'container_height',
			[
				'label'      => esc_html__( 'Container Height', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'vh' ],
				'default'    => [
					'unit' => 'vh',
					'size' => 100,
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-marquee' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'direction' => [ 'top', 'bottom' ],
				],
			]
		);

		$this->add_control(
			'on_hover',
			[
				'label'   => esc_html__( 'On Hover', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''      => esc_html__( 'None', 'powerpack-lite-for-elementor' ),
					'pause' => esc_html__( 'Pause', 'powerpack-lite-for-elementor' ),
					'slow'  => esc_html__( 'Slow Down', 'powerpack-lite-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'slow_down_factor',
			[
				'label'       => esc_html__( 'Slow Down By', 'powerpack-lite-for-elementor' ),
				'description' => esc_html__( 'Multiplier applied to the animation speed on hover. Higher means slower.', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min'  => 1.5,
						'max'  => 10,
						'step' => 0.5,
					],
				],
				'default'     => [
					'size' => 3,
				],
				'condition'   => [
					'on_hover' => 'slow',
				],
			]
		);

		$this->add_control(
			'speed',
			[
				'label'     => esc_html__( 'Speed', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'default'   => [
					'size' => 50,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .pp-marquee' => '--speed: {{SIZE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Marquee' );

		if ( ! empty( $help_docs ) ) {
			/**
			 * Content Tab: Help Docs
			 *
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

	protected function register_style_layout_controls() {
		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => esc_html__( 'Layout', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'fields_layout',
			[
				'label'     => esc_html__( 'Fields Layout', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'row',
				'options'   => [
					'row'    => esc_html__( 'Inline', 'powerpack-lite-for-elementor' ),
					'column' => esc_html__( 'Stack', 'powerpack-lite-for-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .pp-marquee' => '--fields-layout: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'fields_gap',
			[
				'label'      => esc_html__( 'Fields Gap', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-marquee' => '--fields-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'items_gap',
			[
				'label'      => esc_html__( 'Items gap', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-marquee-animation' => '--items-gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-marquee-separator' => 'margin-inline-start: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'marquee_rotation',
			[
				'label'      => esc_html__( 'Rotation', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range'      => [
					'deg' => [
						'min'  => -360,
						'max'  => 360,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-marquee' => 'transform: rotate({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_items_controls() {
		$this->start_controls_section(
			'section_items_style',
			[
				'label' => esc_html__( 'Items', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'items_padding',
			[
				'label'      => esc_html__( 'Padding', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-marquee-fields' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_items' );

		$this->start_controls_tab(
			'tab_items_normal',
			[
				'label' => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'marquee_bg',
				'label'    => esc_html__( 'Background', 'powerpack-lite-for-elementor' ),
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .pp-marquee-fields',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'items_border',
				'selector' => '{{WRAPPER}} .pp-marquee-fields',
			]
		);

		$this->add_responsive_control(
			'items_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-marquee-fields' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'items_box_shadow',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .pp-marquee-fields',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_items_hover',
			[
				'label' => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'items_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-marquee-fields:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'items_box_shadow_hover',
				'exclude'  => [
					'box_shadow_position',
				],
				'selector' => '{{WRAPPER}} .pp-marquee-fields:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_text_controls() {
		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Text', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} .pp-marquee-text',
			]
		);

		$this->add_responsive_control(
			'text_align',
			[
				'label'                => esc_html__( 'Alignment', 'powerpack-lite-for-elementor' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'left'   => [
						'title' => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors_dictionary' => [
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				],
				'selectors'            => [
					'{{WRAPPER}} .pp-marquee-fields' => 'justify-content: {{VALUE}}; align-items: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_text' );

		$this->start_controls_tab(
			'tab_text_normal',
			[
				'label' => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-marquee-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'label'    => esc_html__( 'Text Shadow', 'powerpack-lite-for-elementor' ),
				'selector' => '{{WRAPPER}} .pp-marquee-text',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'     => 'text_stroke',
				'label'    => esc_html__( 'Text Stroke', 'powerpack-lite-for-elementor' ),
				'selector' => '{{WRAPPER}} .pp-marquee-text',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_text_hover',
			[
				'label' => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'text_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-marquee-text:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow_hover',
				'label'    => esc_html__( 'Text Shadow', 'powerpack-lite-for-elementor' ),
				'selector' => '{{WRAPPER}} .pp-marquee-text:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_image_controls() {
		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'      => esc_html__( 'Image Width', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 120,
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-marquee-img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => esc_html__( 'Image Height', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 120,
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-marquee-img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_image' );

		$this->start_controls_tab(
			'tab_image_normal',
			[
				'label' => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'image_css_filters',
				'selector' => '{{WRAPPER}} .pp-marquee-img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_image_hover',
			[
				'label' => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'     => 'image_css_hover_filters',
				'selector' => '{{WRAPPER}} .pp-marquee-img:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_separator_controls() {
		$this->start_controls_section(
			'section_separator_style',
			[
				'label'     => esc_html__( 'Separator', 'powerpack-lite-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'separator' => 'yes',
				],
			]
		);

		$this->add_control(
			'separator_icon_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => [
					'{{WRAPPER}} .pp-marquee-separator' => '--separator-color: {{VALUE}};',
				],
				'condition' => [
					'separator' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'separator_icon_size',
			[
				'label'      => esc_html__( 'Size', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-marquee-separator' => '--separator-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'separator' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'separator_text_typography',
				'label'     => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'selector'  => '{{WRAPPER}} .pp-marquee-separator-text',
				'condition' => [
					'separator'      => 'yes',
					'separator_type' => 'text',
				],
			]
		);

		$this->add_responsive_control(
			'separator_image_width',
			[
				'label'      => esc_html__( 'Image Width', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 400,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-marquee-separator-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'separator'      => 'yes',
					'separator_type' => 'image',
				],
			]
		);

		$this->add_control(
			'separator_icon_fit_to_size',
			[
				'label'       => esc_html__( 'Fit to Size', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'Avoid gaps around icons when width and height aren\'t equal', 'powerpack-lite-for-elementor' ),
				'label_off'   => esc_html__( 'Off', 'powerpack-lite-for-elementor' ),
				'label_on'    => esc_html__( 'On', 'powerpack-lite-for-elementor' ),
				'selectors'   => [
					'{{WRAPPER}} .pp-marquee-separator svg' => 'width: 100%;',
				],
				'condition'   => [
					'separator'               => 'yes',
					'separator_type!'         => [ 'text', 'image' ],
					'separator_icon[library]' => 'svg',
				],
			]
		);

		$this->add_responsive_control(
			'separator_icon_rotate',
			[
				'label'          => esc_html__( 'Rotate', 'powerpack-lite-for-elementor' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'deg', 'grad', 'rad', 'turn', 'custom' ],
				'default'        => [
					'unit' => 'deg',
				],
				'tablet_default' => [
					'unit' => 'deg',
				],
				'mobile_default' => [
					'unit' => 'deg',
				],
				'selectors'      => [
					'{{WRAPPER}} .pp-marquee-separator' => '--separator-rotate: {{SIZE}}{{UNIT}};',
				],
				'condition'      => [
					'separator' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render Marquee widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings  = $this->get_settings_for_display();
		$direction = $settings['direction'];
		$vertical  = '';

		$classes = [ 'pp-marquee', 'pp-marquee-' . esc_attr( $this->get_id() ) ];

		if ( 'top' === $direction || 'bottom' === $direction ) {
			$classes[] = 'pp-marquee-vertical';
			$vertical  = 'yes';
			$direction = ( 'top' === $settings['direction'] ) ? '1' : '-1';
		} else {
			$direction = ( 'left' === $settings['direction'] ) ? '1' : '-1';
		}

		$pause_on_hover = ( 'pause' === $settings['on_hover'] ) ? 'true' : 'false';
		$slow_factor    = ( 'slow' === $settings['on_hover'] && ! empty( $settings['slow_down_factor']['size'] ) )
			? (float) $settings['slow_down_factor']['size']
			: 1;

		$this->add_render_attribute( [
			'marquee' => [
				'class'                => $classes,
				'data-v-direction'     => esc_attr( $vertical ),
				'data-viewport-offset' => '0.01',
				'data-slow-factor'     => esc_attr( $slow_factor ),
				'style'                => "--direction: {$direction}; --pause-on-hover: {$pause_on_hover};",
			],
		] );
		?>

		<div <?php $this->print_render_attribute_string( 'marquee' ); ?>>
			<div class="pp-marquee-animation">
			<?php
			if ( 'posts' === $settings['source'] ) {
				$marquee_data = $this->get_marquee_posts();
			} else {
				$marquee_data = $this->get_marquee_data();
			}

			if ( ! empty( $marquee_data ) ) {
				foreach ( $marquee_data as $index => $item ) {
					$item_key = 'marquee-item-' . $index;

					$this->add_render_attribute( $item_key, 'class', [
						'pp-marquee-item',
						'elementor-repeater-item-' . $item['_id'],
					] );
					?>
					<div <?php $this->print_render_attribute_string( $item_key ); ?>>
					<?php
					$this->render_item( $item, $index );
					if ( 'yes' === $settings['separator'] ) {
						$this->render_separator();
					}
					?>
					</div>
					<?php
				}
			}
			?>
			</div>
		</div>

		<?php
	}

	protected function get_marquee_data() {
		$settings = $this->get_settings_for_display();
		$items    = ! empty( $settings['items'] ) ? $settings['items'] : [];

		foreach ( $items as &$item ) {
			$fields = [];

			if ( ! empty( $item['marquee_image']['url'] ) || ! empty( $item['marquee_image']['id'] ) ) {
				$fields[] = [
					'type'       => 'image',
					'value'      => $item['marquee_image'],
					'image_size' => ! empty( $item['image_size'] ) ? $item['image_size'] : 'medium',
				];
			}

			if ( '' !== trim( (string) ( $item['marquee_text'] ?? '' ) ) ) {
				$fields[] = [
					'type'  => 'text',
					'value' => $item['marquee_text'],
				];
			}

			$item['fields'] = $fields;
		}

		return $items;
	}

	/**
	 * Render a single marquee item: wraps all fields in one link (if set)
	 * and delegates to field renderers.
	 *
	 * @access protected
	 */
	protected function render_item( $item, $index ) {
		if ( empty( $item['fields'] ) ) {
			return;
		}

		$has_link = ! empty( $item['item_link']['url'] );
		$key      = 'marquee-item-fields-' . $index;
		$tag      = $has_link ? 'a' : 'span';

		$this->add_render_attribute( $key, 'class', 'pp-marquee-fields' );

		if ( $has_link ) {
			$this->add_link_attributes( $key, $item['item_link'] );
			$this->add_render_attribute( $key, 'class', 'pp-marquee-url' );
		}

		echo '<' . $tag . ' ';
		$this->print_render_attribute_string( $key );
		echo '>';

		foreach ( $item['fields'] as $field ) {
			if ( 'image' === $field['type'] ) {
				$this->render_image_field( $field );
			} elseif ( 'text' === $field['type'] ) {
				$this->render_text_field( $field );
			}
		}

		echo '</' . $tag . '>';
	}

	protected function render_image_field( $field ) {
		$image = $field['value'];

		if ( empty( $image['url'] ) && empty( $image['id'] ) ) {
			return;
		}

		$size     = ! empty( $field['image_size'] ) ? $field['image_size'] : 'medium';
		$image_id = ! empty( $image['id'] ) ? apply_filters( 'wpml_object_id', $image['id'], 'attachment', true ) : 0;
		$classes  = [ 'pp-marquee-content', 'pp-marquee-image' ];
		if ( ! empty( $field['field_key'] ) ) {
			$classes[] = 'elementor-repeater-item-' . $field['field_key'];
		}
		?>
		<span class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<?php
			if ( $image_id ) {
				echo wp_get_attachment_image( $image_id, $size, '', [ 'class' => 'pp-marquee-img' ] );
			} else {
				printf(
					'<img class="pp-marquee-img" src="%s" alt="%s" loading="lazy" decoding="async" />',
					esc_url( $image['url'] ),
					esc_attr( Control_Media::get_image_alt( $image ) )
				);
			}
			?>
		</span>
		<?php
	}

	protected function render_text_field( $field ) {
		if ( '' === trim( (string) $field['value'] ) ) {
			return;
		}

		$classes = [ 'pp-marquee-content', 'pp-marquee-text' ];
		if ( ! empty( $field['field_key'] ) ) {
			$classes[] = 'elementor-repeater-item-' . $field['field_key'];
		}
		?>
		<span class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"><?php echo esc_html( $field['value'] ); ?></span>
		<?php
	}

	protected function render_separator() {
		$settings = $this->get_settings_for_display();
		$type     = ! empty( $settings['separator_type'] ) ? $settings['separator_type'] : 'icon';

		if ( 'text' === $type ) {
			$text = isset( $settings['separator_text'] ) ? $settings['separator_text'] : '';

			if ( '' === trim( (string) $text ) ) {
				return;
			}
			?>
			<span class="pp-marquee-separator pp-marquee-separator-text" aria-hidden="true"><?php echo esc_html( $text ); ?></span>
			<?php
			return;
		}

		if ( 'image' === $type ) {
			if ( empty( $settings['separator_image']['url'] ) && empty( $settings['separator_image']['id'] ) ) {
				return;
			}
			?>
			<span class="pp-marquee-separator pp-marquee-separator-image" aria-hidden="true">
				<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings, 'separator_image', 'separator_image' ); ?>
			</span>
			<?php
			return;
		}

		if ( empty( $settings['separator_icon']['value'] ) ) {
			return;
		}
		?>
		<span class="pp-marquee-separator pp-icon">
			<?php Icons_Manager::render_icon( $settings['separator_icon'], [ 'aria-hidden' => 'true' ] ); ?>
		</span>
		<?php
	}

	/**
	 * Build WP_Query args for the posts source.
	 *
	 * @access protected
	 */
	protected function get_posts_query_arguments() {
		$settings    = $this->get_settings_for_display();
		$posts_count = ! empty( $settings['posts_per_page'] ) ? absint( $settings['posts_per_page'] ) : 5;
		$post_type   = ! empty( $settings['post_type'] ) ? $settings['post_type'] : 'post';

		$args = [
			'post_status'         => [ 'publish' ],
			'post_type'           => $post_type,
			'orderby'             => ! empty( $settings['orderby'] ) ? $settings['orderby'] : 'date',
			'order'               => ! empty( $settings['order'] ) ? $settings['order'] : 'DESC',
			'offset'              => ! empty( $settings['offset'] ) ? absint( $settings['offset'] ) : 0,
			'ignore_sticky_posts' => ( 'yes' === $settings['sticky_posts'] ) ? 0 : 1,
			'posts_per_page'      => $posts_count,
		];

		// Author filter.
		if ( ! empty( $settings['authors'] ) ) {
			$author_filter_type = ! empty( $settings['author_filter_type'] ) ? $settings['author_filter_type'] : 'author__in';
			$args[ $author_filter_type ] = $settings['authors'];
		}

		// Taxonomy filter.
		$taxonomy = PP_Posts_Helper::get_post_taxonomies( $post_type );

		if ( ! empty( $taxonomy ) && ! is_wp_error( $taxonomy ) ) {
			foreach ( $taxonomy as $index => $tax ) {
				if ( 'post_tag' === $index ) {
					$tax_control_key = 'tags';
				} elseif ( 'category' === $index ) {
					$tax_control_key = 'categories';
				} else {
					$tax_control_key = $index . '_' . $post_type;
				}

				if ( ! empty( $settings[ $tax_control_key ] ) ) {
					$operator = ! empty( $settings[ $index . '_' . $post_type . '_filter_type' ] )
						? $settings[ $index . '_' . $post_type . '_filter_type' ]
						: 'IN';

					$args['tax_query'][] = [
						'taxonomy' => $index,
						'field'    => 'term_id',
						'terms'    => $settings[ $tax_control_key ],
						'operator' => $operator,
					];
				}
			}
		}

		return $args;
	}

	/**
	 * Build one field entry for a post item.
	 *
	 * @access protected
	 */
	protected function build_post_field( $post_id, $field, $image_size ) {
		switch ( $field ) {
			case 'featured_image':
				$attachment_id = get_post_thumbnail_id( $post_id );
				$url           = wp_get_attachment_image_src( $attachment_id, $image_size );
				return [
					'type'       => 'image',
					'value'      => [
						'id'  => $attachment_id,
						'url' => ! empty( $url ) ? $url[0] : '',
					],
					'image_size' => $image_size,
				];
			case 'date':
				return [ 'type' => 'text', 'value' => get_the_date( '', $post_id ) ];
			case 'author':
				return [ 'type' => 'text', 'value' => get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) ) ];
			case 'title':
			default:
				return [ 'type' => 'text', 'value' => get_the_title( $post_id ) ];
		}
	}

	protected function get_marquee_posts() {
		$settings    = $this->get_settings();
		$post_fields = ! empty( $settings['posts_items'] ) ? $settings['posts_items'] : [ [ 'post_field' => 'featured_image' ] ];
		$image_size  = ! empty( $settings['image_size'] ) ? $settings['image_size'] : 'medium_large';

		$i     = 0;
		$items = [];

		$args        = $this->get_posts_query_arguments();
		$posts_query = new \WP_Query( $args );

		if ( $posts_query->have_posts() ) {
			while ( $posts_query->have_posts() ) {
				$posts_query->the_post();
				$post_id = $posts_query->post->ID;

				if ( 'post_url' === $settings['posts_link_to'] ) {
					$link = [
						'url'         => esc_url( get_permalink( $post_id ) ),
						'is_external' => ( 'yes' === $settings['post_link_target'] ) ? 'on' : '',
						'nofollow'    => '',
					];
				} else {
					$link = $settings['posts_link'];
				}

				$fields = [];
				foreach ( $post_fields as $field_config ) {
					$name  = ! empty( $field_config['post_field'] ) ? $field_config['post_field'] : 'title';
					$field = $this->build_post_field( $post_id, $name, $image_size );

					if ( ! empty( $field_config['_id'] ) ) {
						$field['field_key'] = $field_config['_id'];
					}

					$fields[] = $field;
				}

				$items[] = [
					'_id'       => 'ppm' . $i,
					'item_link' => $link,
					'fields'    => $fields,
				];

				$i++;
			}
		}

		wp_reset_postdata();

		return $items;
	}
}
