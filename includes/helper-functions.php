<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Every widget this plugin ships and this site can actually use.
 *
 * Derived from the catalogue rather than listed again here. The two were
 * maintained separately and had drifted: this list carried 'pp-link-effects'
 * and 'pp-hotspots', which no widget answers to — the catalogue registers them
 * as 'pa-link-effects' and 'pp-image-hotspots' — so neither name could ever
 * enable anything.
 *
 * PP_Helper::get_widgets_list() is what drops the paid edition's widgets, which
 * the catalogue also carries so the settings screen can promote them.
 *
 * @since x.x.x
 * @return array
 */
function powerpack_elements_lite_get_modules() {
	/*
	 * Form stylers are only offered when the form plugin they style is here.
	 * Nothing is gained by putting a Gravity Forms switch in front of someone
	 * who does not have Gravity Forms.
	 */
	$requires = [
		'pp-contact-form-7'   => 'wpcf7',
		'pp-gravity-forms'    => 'GFCommon',
		'pp-ninja-forms'      => 'Ninja_Forms',
		'pp-wpforms'          => 'wpforms',
		'pp-formidable-forms' => 'FrmForm',
		'pp-fluent-forms'     => 'wpFluentForm',
	];

	$modules = [];

	foreach ( \PowerpackElementsLite\Classes\PP_Helper::get_widgets_list() as $widget ) {
		if ( empty( $widget['name'] ) ) {
			continue;
		}

		$name = $widget['name'];

		if ( isset( $requires[ $name ] ) && ! function_exists( $requires[ $name ] ) && ! class_exists( $requires[ $name ] ) ) {
			continue;
		}

		$modules[ $name ] = $widget['title'];
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
