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
 * @since 3.0.0
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

/**
 * The widgets that are switched on, as a list of names.
 *
 * Three shapes reach here. An unsaved option is false, and means every widget
 * is on. The string 'disabled' is what the old settings screen wrote when every
 * widget was switched off — it is not an array either, so it used to fall
 * through to "everything on" and turn the whole library back on. Anything else
 * is the stored list.
 *
 * @since 3.0.0
 * @return array
 */
function powerpack_elements_lite_get_enabled_modules() {
	$enabled_modules = \PowerpackElementsLite\Classes\PP_Admin_Settings::get_option( 'pp_elementor_modules', true );

	if ( 'disabled' === $enabled_modules ) {
		$enabled_modules = [];
	} elseif ( ! is_array( $enabled_modules ) ) {
		$enabled_modules = array_keys( powerpack_elements_lite_get_modules() );
	}

	return apply_filters( 'pp_elementor_enabled_modules', $enabled_modules );
}

/**
 * The widgets that are switched on, as a lookup table.
 *
 * The stored option is a plain list of widget names, but the "nothing saved
 * yet" fallback above builds its list from the catalogue, which is keyed by
 * name. Both shapes are flattened here to 'name => true'.
 *
 * Module_Base::is_widget_active() runs once per widget on every registration
 * pass, and now once more per module before the module is booted at all, so
 * this is one of the hottest paths in the plugin. The option is read and
 * filtered once per request, and membership becomes a single isset() instead
 * of an in_array() scan over the whole library.
 *
 * @since 3.0.0
 * @param bool $reset Internal. Flush the cached lookup, see
 *                    powerpack_elements_lite_flush_enabled_modules_cache().
 * @return array Map of enabled widget name => true.
 */
function powerpack_elements_lite_get_enabled_modules_lookup( $reset = false ) {
	static $lookup = null;

	if ( $reset ) {
		$lookup = null;

		return [];
	}

	if ( null !== $lookup ) {
		return $lookup;
	}

	$enabled_modules = powerpack_elements_lite_get_enabled_modules();
	$lookup          = [];

	if ( is_array( $enabled_modules ) ) {
		foreach ( $enabled_modules as $key => $value ) {
			$module_name = is_int( $key ) ? $value : $key;

			if ( is_string( $module_name ) ) {
				$lookup[ $module_name ] = true;
			}
		}
	}

	return $lookup;
}

/**
 * Flush the cached enabled widgets lookup.
 *
 * Hooked to the option writes so a settings save is picked up within the same
 * request that performed it. The site option hooks matter on multisite, where
 * PP_Admin_Settings::get_option() may read the network copy.
 *
 * @since 3.0.0
 * @return void
 */
function powerpack_elements_lite_flush_enabled_modules_cache() {
	powerpack_elements_lite_get_enabled_modules_lookup( true );
}
add_action( 'add_option_pp_elementor_modules', 'powerpack_elements_lite_flush_enabled_modules_cache' );
add_action( 'update_option_pp_elementor_modules', 'powerpack_elements_lite_flush_enabled_modules_cache' );
add_action( 'delete_option_pp_elementor_modules', 'powerpack_elements_lite_flush_enabled_modules_cache' );
add_action( 'add_site_option_pp_elementor_modules', 'powerpack_elements_lite_flush_enabled_modules_cache' );
add_action( 'update_site_option_pp_elementor_modules', 'powerpack_elements_lite_flush_enabled_modules_cache' );
add_action( 'delete_site_option_pp_elementor_modules', 'powerpack_elements_lite_flush_enabled_modules_cache' );

/**
 * The enabled widget names, limited to widgets this plugin still ships.
 *
 * Names left behind by widgets that have since been removed or that this site
 * cannot use are dropped, so counts always line up with the current library.
 *
 * @since 3.0.0
 * @return array
 */
function powerpack_elements_lite_get_enabled_module_names() {
	$all_modules = array_keys( powerpack_elements_lite_get_modules() );
	$enabled     = array_keys( powerpack_elements_lite_get_enabled_modules_lookup() );

	return array_values( array_intersect( $all_modules, $enabled ) );
}

/**
 * Counts for the widget library, used to decide whether it is worth nudging
 * someone to switch off widgets their site is not using.
 *
 * @since 3.0.0
 * @return array {
 *     @type int $total    Widgets this plugin ships that this site can use.
 *     @type int $enabled  Widgets switched on.
 *     @type int $disabled Widgets switched off.
 *     @type int $percent  Percentage of the library switched on, rounded.
 * }
 */
function powerpack_elements_lite_get_modules_stats() {
	$total   = count( powerpack_elements_lite_get_modules() );
	$enabled = count( powerpack_elements_lite_get_enabled_module_names() );

	return [
		'total'    => $total,
		'enabled'  => $enabled,
		'disabled' => max( 0, $total - $enabled ),
		'percent'  => $total > 0 ? (int) round( ( $enabled / $total ) * 100 ) : 0,
	];
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

/**
 * Send "upgrade" on the settings screen to the page that sells the paid edition.
 *
 * The settings app, its REST controller and the settings registry are the same
 * code as the paid edition's, which is why the destination arrives through a
 * filter rather than being written into the screen itself.
 *
 * Everything this file declares is prefixed for this plugin alone, and this
 * plugin's copy of the shared code calls these names rather than the paid
 * edition's. That is deliberate, and it is the whole reason both plugins can be
 * active at once: two declarations of one function name is a fatal, and which of
 * the two declares first depends on the order WordPress happens to load them in.
 * With no name in common there is nothing to collide, and the paid edition needs
 * to know nothing about this one.
 *
 * @since 3.0.0
 * @return string
 */
function powerpack_elements_lite_settings_upgrade_url() {
	return 'https://powerpackelements.com/upgrade/';
}
add_filter( 'pp_settings_upgrade_url', 'powerpack_elements_lite_settings_upgrade_url' );
