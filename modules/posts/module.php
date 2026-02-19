<?php
namespace PowerpackElementsLite\Modules\Posts;

use PowerpackElementsLite\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public static $displayed_ids = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct();

		/**
		 * Pagination Break.
		 *
		 * @see https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
		 */
		add_action( 'pre_get_posts', [ $this, 'fix_query_offset' ], 1 );
		add_filter( 'found_posts', [ $this, 'fix_query_found_posts' ], 1, 2 );

		add_action( 'wp_ajax_pp_get_post', array( $this, 'get_post_data' ) );
		add_action( 'wp_ajax_nopriv_pp_get_post', array( $this, 'get_post_data' ) );

		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
	}

	public function get_name() {
		return 'pp-posts';
	}

	public function get_widgets() {
		return [
			'Posts',
		];
	}

	public static function add_to_avoid_list( $ids ) {
		self::$displayed_ids = array_unique( array_merge( self::$displayed_ids, $ids ) );
	}

	public static function get_avoid_list_ids() {
		return self::$displayed_ids;
	}

	/**
	 * Query Offset Fix.
	 *
	 * @since 1.4.14
	 * @access public
	 * @param object $query query object.
	 */
	public function fix_query_offset( &$query ) {
		if ( ! empty( $query->query_vars['offset_to_fix'] ) ) {
			if ( $query->is_paged ) {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'] + ( ( $query->query_vars['paged'] - 1 ) * $query->query_vars['posts_per_page'] );
			} else {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'];
			}
		}
	}

	/**
	 * Query Found Posts Fix.
	 *
	 * @since 1.4.14
	 * @access public
	 * @param int    $found_posts found posts.
	 * @param object $query query object.
	 * @return int string
	 */
	public function fix_query_found_posts( $found_posts, $query ) {
		$offset_to_fix = $query->get( 'offset_to_fix' );

		if ( $offset_to_fix ) {
			$found_posts -= $offset_to_fix;
		}

		return $found_posts;
	}
	
	public function get_post_data() {
		check_ajax_referer( 'pp-posts-widget-nonce', 'nonce' );

		// Validate + sanitize page_id
		$post_id = 0;
		if ( isset( $_POST['page_id'] ) ) {
			$post_id = absint( wp_unslash( $_POST['page_id'] ) );
		}

		// Validate + sanitize widget_id
		$widget_id = '';
		if ( isset( $_POST['widget_id'] ) ) {
			$widget_id = sanitize_text_field(
				wp_unslash( $_POST['widget_id'] )
			);
		}

		// Category filter
		$filter = '';
		if ( isset( $_POST['category'] ) ) {
			$filter = sanitize_text_field(
				wp_unslash( $_POST['category'] )
			);
			$filter = str_replace( '.', '', $filter );
		}

		// Taxonomy filter
		$taxonomy_filter = '';
		if ( isset( $_POST['taxonomy'] ) ) {
			$taxonomy_filter = sanitize_text_field(
				wp_unslash( $_POST['taxonomy'] )
			);
			$taxonomy_filter = str_replace( '.', '', $taxonomy_filter );
		}

		// Search filter
		$search_filter = '';
		if ( isset( $_POST['search'] ) ) {
			$search_filter = sanitize_text_field(
				wp_unslash( $_POST['search'] )
			);
		}

		// Early return if required data missing
		if ( ! $post_id || empty( $widget_id ) ) {
			wp_send_json_error( __( 'Invalid request.', 'powerpack-lite-for-elementor' ) );
		}

		$elementor = \Elementor\Plugin::$instance;

		$document = $elementor->documents->get( $post_id );

		if ( ! $document ) {
			wp_send_json_error( __( 'Invalid document.', 'powerpack-lite-for-elementor' ) );
		}

		$meta = $document->get_elements_data();

		$widget_data = $this->find_element_recursive( $meta, $widget_id );

		if ( isset( $widget_data['templateID'] ) ) {
			$template_data = $elementor->templates_manager->get_template_data( [
				'source'      => 'local',
				'template_id' => absint( $widget_data['templateID'] ),
			] );

			if ( is_array( $template_data ) && isset( $template_data['content'] ) ) {
				$widget_data = $template_data['content'][0];
			}
		}

		$data = [
			'message'    => __( 'Saved', 'powerpack-lite-for-elementor' ),
			'ID'         => '',
			'skin_id'    => '',
			'html'       => '',
			'pagination' => '',
		];

		if ( null !== $widget_data ) {

			$widget     = $elementor->elements_manager->create_element_instance( $widget_data );
			$skin       = $widget->get_current_skin();
			$skin_body  = $skin->render_ajax_post_body( $filter, $taxonomy_filter, $search_filter );
			$pagination = $skin->render_ajax_pagination();

			$data['ID']         = $widget->get_id();
			$data['skin_id']    = $widget->get_current_skin_id();
			$data['html']       = $skin_body;
			$data['pagination'] = $pagination;
		}

		wp_send_json_success( $data );
	}

	/**
	 * Get Widget Setting data.
	 *
	 * @since 1.7.0
	 * @access public
	 * @param array  $elements Element array.
	 * @param string $form_id Element ID.
	 * @return Boolean True/False.
	 */
	public function find_element_recursive( $elements, $form_id ) {

		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = $this->find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}

	/**
	 * Get Post Parts
	 *
	 * @since  1.4.11.0
	 * @return array
	 */
	public static function get_post_parts() {
		$post_parts = [
			'thumbnail',
			'terms',
			'title',
			'meta',
			'excerpt',
			'button',
		];

		return $post_parts;
	}

	/**
	 * Get Meta Items
	 *
	 * @since  1.4.11.0
	 * @return array
	 */
	public static function get_meta_items() {
		$meta_items = [
			'author',
			'date',
			'comments',
		];

		return $meta_items;
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'widget-pp-posts',
			$this->get_css_assets_url( 'widget-posts', null, true, true ),
			[ 'pp-elementor-grid' ],
			POWERPACK_ELEMENTS_LITE_VER
		);
	}
}
