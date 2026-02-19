<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function powerpack_elements_lite_get_modules() {
	$modules = array(
		'pp-advanced-accordion'     => esc_html__( 'Advanced Accordion', 'powerpack-lite-for-elementor' ),
		'pp-link-effects'           => esc_html__( 'Link Effects', 'powerpack-lite-for-elementor' ),
		'pp-divider'                => esc_html__( 'Divider', 'powerpack-lite-for-elementor' ),
		'pp-flipbox'                => esc_html__( 'Flipbox', 'powerpack-lite-for-elementor' ),
		'pp-image-accordion'        => esc_html__( 'Image Accordion', 'powerpack-lite-for-elementor' ),
		'pp-info-box'               => esc_html__( 'Info Box', 'powerpack-lite-for-elementor' ),
		'pp-info-box-carousel'      => esc_html__( 'Info Grid & Carousel', 'powerpack-lite-for-elementor' ),
		'pp-info-list'              => esc_html__( 'Info List', 'powerpack-lite-for-elementor' ),
		'pp-info-table'             => esc_html__( 'Info Table', 'powerpack-lite-for-elementor' ),
		'pp-pricing-table'          => esc_html__( 'Pricing Table', 'powerpack-lite-for-elementor' ),
		'pp-price-menu'             => esc_html__( 'Price Menu', 'powerpack-lite-for-elementor' ),
		'pp-business-hours'         => esc_html__( 'Business Hours', 'powerpack-lite-for-elementor' ),
		'pp-team-member'            => esc_html__( 'Team Member', 'powerpack-lite-for-elementor' ),
		'pp-team-member-carousel'   => esc_html__( 'Team Member Carousel', 'powerpack-lite-for-elementor' ),
		'pp-counter'                => esc_html__( 'Counter', 'powerpack-lite-for-elementor' ),
		'pp-hotspots'               => esc_html__( 'Image Hotspots', 'powerpack-lite-for-elementor' ),
		'pp-icon-list'              => esc_html__( 'Icon List', 'powerpack-lite-for-elementor' ),
		'pp-dual-heading'           => esc_html__( 'Dual Heading', 'powerpack-lite-for-elementor' ),
		'pp-promo-box'              => esc_html__( 'Promo Box', 'powerpack-lite-for-elementor' ),
		'pp-logo-carousel'          => esc_html__( 'Logo Carousel', 'powerpack-lite-for-elementor' ),
		'pp-logo-grid'              => esc_html__( 'Logo Grid', 'powerpack-lite-for-elementor' ),
		'pp-image-comparison'       => esc_html__( 'Image Comparison', 'powerpack-lite-for-elementor' ),
		'pp-instafeed'              => esc_html__( 'Instagram Feed', 'powerpack-lite-for-elementor' ),
		'pp-interactive-circle'     => esc_html__( 'Interactive Circle', 'powerpack-lite-for-elementor' ),
		'pp-progress-bar'           => esc_html__( 'Progress Bar', 'powerpack-lite-for-elementor' ),
		'pp-content-ticker'         => esc_html__( 'Content Ticker', 'powerpack-lite-for-elementor' ),
		'pp-scroll-image'           => esc_html__( 'Scroll Image', 'powerpack-lite-for-elementor' ),
		'pp-buttons'                => esc_html__( 'Buttons', 'powerpack-lite-for-elementor' ),
		'pp-twitter-buttons'        => esc_html__( 'Twitter Buttons', 'powerpack-lite-for-elementor' ),
		'pp-twitter-grid'           => esc_html__( 'Twitter Grid', 'powerpack-lite-for-elementor' ),
		'pp-twitter-timeline'       => esc_html__( 'Twitter Timeline', 'powerpack-lite-for-elementor' ),
		'pp-twitter-tweet'          => esc_html__( 'Twitter Tweet', 'powerpack-lite-for-elementor' ),
		'pp-fancy-heading'          => esc_html__( 'Fancy Heading', 'powerpack-lite-for-elementor' ),
		'pp-posts'                  => esc_html__( 'Posts', 'powerpack-lite-for-elementor' ),
		'pp-content-reveal'         => esc_html__( 'Content Reveal', 'powerpack-lite-for-elementor' ),
		'pp-random-image'           => esc_html__( 'Random Image', 'powerpack-lite-for-elementor' ),
		'pp-charts'                 => esc_html__( 'Advanced Charts', 'powerpack-lite-for-elementor' ),
	);

	// Contact Form 7
	if ( function_exists( 'wpcf7' ) ) {
		$modules['pp-contact-form-7'] = esc_html__( 'Contact Form 7', 'powerpack-lite-for-elementor' );
	}

	// Gravity Forms
	if ( class_exists( 'GFCommon' ) ) {
		$modules['pp-gravity-forms'] = esc_html__( 'Gravity Forms', 'powerpack-lite-for-elementor' );
	}

	// Ninja Forms
	if ( class_exists( 'Ninja_Forms' ) ) {
		$modules['pp-ninja-forms'] = esc_html__( 'Ninja Forms', 'powerpack-lite-for-elementor' );
	}

	// WPForms
	if ( function_exists( 'wpforms' ) ) {
		$modules['pp-wpforms'] = esc_html__( 'WPForms', 'powerpack-lite-for-elementor' );
	}

	// Formidable Forms
	if ( class_exists( 'FrmForm' ) ) {
		$modules['pp-formidable-forms'] = esc_html__( 'Formidable Forms', 'powerpack-lite-for-elementor' );
	}

	// Fluent Forms
	if ( function_exists( 'wpFluentForm' ) ) {
		$modules['pp-fluent-forms'] = esc_html__( 'Fluent Forms', 'powerpack-lite-for-elementor' );
	}

	ksort( $modules );

	return $modules;
}

function powerpack_elements_lite_get_extensions() {
	$extensions = array(
		'pp-display-conditions'           => esc_html__( 'Display Conditions', 'powerpack-lite-for-elementor' ),
		'pp-wrapper-link'                 => esc_html__( 'Wrapper Link', 'powerpack-lite-for-elementor' ),
		'pp-animated-gradient-background' => esc_html__( 'Animated Gradient Background', 'powerpack-lite-for-elementor' ),
		'pp-custom-cursor'                => esc_html__( 'Custom Cursor', 'powerpack-lite-for-elementor' ),
	);

	return $extensions;
}

function powerpack_elements_lite_get_enabled_modules() {
	$enabled_modules = \PowerpackElementsLite\Classes\PP_Admin_Settings::get_option( 'pp_elementor_modules', true );

	if ( ! is_array( $enabled_modules ) ) {
		return array_keys( powerpack_elements_lite_get_modules() );
	} else {
		return $enabled_modules;
	}
}

function powerpack_elements_lite_get_filter_modules( $status = '' ) {
	global $wpdb;

	$modules          = [];
	$get_used_widgets = [];
	$all_widget_list  = powerpack_elements_lite_get_modules();

	$cache_key   = 'pp_elementor_post_ids';
	$cache_group = 'powerpack';

	$post_ids = wp_cache_get( $cache_key, $cache_group );

	if ( false === $post_ids ) {

		$query = new WP_Query(
			[
				'post_type'              => 'any',
				'post_status'            => 'any',
				'fields'                 => 'ids',
				'posts_per_page'         => -1,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'meta_query'             => [
					[
						'key'     => '_elementor_version',
						'compare' => 'EXISTS',
					],
				],
			]
		);

		$post_ids = $query->posts;

		wp_cache_set( $cache_key, $post_ids, $cache_group );
	}

	if ( empty( $post_ids ) ) {
		return $modules;
	}

	foreach ( $post_ids as $post_id ) {

		if ( 'revision' === get_post_type( $post_id ) ) {
			continue;
		}

		$used = powerpack_elements_lite_check_widget_used_status(
			$all_widget_list,
			$post_id
		);

		if ( ! empty( $used ) ) {
			$get_used_widgets = array_merge( $get_used_widgets, $used );
		}
	}

	if ( empty( $get_used_widgets ) ) {
		return $modules;
	}

	$get_used_widgets = array_unique( $get_used_widgets );

	foreach ( $get_used_widgets as $widget_key ) {
		if ( isset( $all_widget_list[ $widget_key ] ) ) {
			$modules[ $widget_key ] = $all_widget_list[ $widget_key ];
		}
	}

	asort( $modules );

	update_option( 'pp_elementor_used_modules', $modules );

	$notused_modules = array_diff_key( $all_widget_list, $modules );

	asort( $notused_modules );

	update_option( 'pp_elementor_notused_modules', $notused_modules );

	if ( 'notused' === $status ) {
		return $notused_modules;
	}

	return $modules;
}

function powerpack_elements_lite_check_widget_used_status( $all_widget_list, $post_id = '' ) {
	$widget_data = [];
	if ( ! current_user_can( 'install_plugins' ) ) {
		$widget_data;
	}

	if ( ! empty( $post_id ) ) {
		$meta_data = \Elementor\Plugin::$instance->documents->get( $post_id );

		if ( is_object( $meta_data ) ) {
			$meta_data = $meta_data->get_elements_data();

			if ( empty( $meta_data ) ) {
				$widget_data;
			}

			\Elementor\Plugin::$instance->db->iterate_data( $meta_data, function( $element ) use ( $all_widget_list, &$widget_data ) {
				if ( ! empty( $element['widgetType'] ) ) {
					if ( substr( $element['widgetType'], 0, 3 ) === 'pp-' ) {
						$widget_data[] = $element['widgetType'];
					}
				}
			} );
		}
	}
	return $widget_data;
}

function powerpack_elements_lite_get_enabled_extensions() {
	$enabled_extensions = \PowerpackElementsLite\Classes\PP_Admin_Settings::get_option( 'pp_elementor_extensions', true );

	if ( ! is_array( $enabled_extensions ) ) {
		return array();
	} else {
		return $enabled_extensions;
	}

	//return $enabled_extensions;
}

/**
 * Elementor
 *
 * Retrieves the elementor plugin instance
 *
 * @since  1.2.9
 * @return \Elementor\Plugin|$instace
 */
function powerpack_elements_lite_get_elementor() {
	return \Elementor\Plugin::$instance;
}
