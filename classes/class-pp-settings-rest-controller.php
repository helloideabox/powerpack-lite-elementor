<?php
namespace PowerpackElementsLite\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * REST endpoints backing the admin settings screen.
 *
 * Every read and write goes through PP_Settings_Registry, so the stored option
 * keys and value shapes are defined in exactly one place.
 *
 * Two deliberate departures from the legacy form handlers:
 *
 * - Writes are partial. Only keys present in the payload are touched, so one
 *   panel can never blank another's options.
 * - Nothing in the save path makes an outbound HTTP request. Credential
 *   verification and license checks are separate, explicit endpoints, so a
 *   Facebook or Google outage can never block an unrelated setting from saving.
 *
 * @since x.x.x
 */
final class PP_Settings_REST_Controller {

	/**
	 * REST namespace. Not named NAMESPACE, which is a reserved word.
	 */
	const REST_NAMESPACE = 'powerpack/v1';

	/**
	 * How long a credential verification verdict is cached.
	 */
	const VERIFY_CACHE_TTL = DAY_IN_SECONDS;

	/**
	 * Where the used / not used classification is cached, and for how long.
	 *
	 * Working it out means reading every Elementor document on the site, so it
	 * is never computed as part of a normal page load.
	 *
	 * The key names this edition. The classification is a list of widget names
	 * this build ships, and the paid edition ships a longer one; sharing a key
	 * would let one edition read a tally the other wrote and count more widgets
	 * in use than it has.
	 */
	const USAGE_CACHE_KEY = 'pp_lite_modules_usage';
	const USAGE_CACHE_TTL = HOUR_IN_SECONDS;

	/**
	 * Register the hook.
	 *
	 * @since x.x.x
	 * @return void
	 */
	public static function init() {
		/*
		 * The paid edition registers the same routes under the same namespace,
		 * so only one of the two may claim them. It wins: if both are installed
		 * it is the one whose settings screen is shown.
		 */
		if ( function_exists( 'is_pp_elements_active' ) && is_pp_elements_active() ) {
			return;
		}

		add_action( 'rest_api_init', [ __CLASS__, 'register_routes' ] );
	}

	/**
	 * Register all routes.
	 *
	 * @since x.x.x
	 * @return void
	 */
	public static function register_routes() {
		$network_arg = [
			'network' => [
				'type'              => 'boolean',
				'default'           => false,
				'description'       => 'Operate on network wide options rather than the current site.',
				'sanitize_callback' => 'rest_sanitize_boolean',
			],
		];

		register_rest_route( self::REST_NAMESPACE, '/settings', [
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ __CLASS__, 'get_settings' ],
				'permission_callback' => [ __CLASS__, 'can_read' ],
				'args'                => $network_arg,
			],
			[
				'methods'             => \WP_REST_Server::EDITABLE,
				'callback'            => [ __CLASS__, 'update_settings' ],
				'permission_callback' => [ __CLASS__, 'can_read' ],
				'args'                => array_merge( $network_arg, [
					'settings' => [
						'type'        => 'object',
						'required'    => true,
						'description' => 'Map of setting key to value. Absent keys are left untouched.',
					],
				] ),
			],
		] );

		register_rest_route( self::REST_NAMESPACE, '/modules', [
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => [ __CLASS__, 'get_modules' ],
			'permission_callback' => [ __CLASS__, 'can_edit_posts' ],
		] );

		register_rest_route( self::REST_NAMESPACE, '/modules/usage', [
			'methods'             => \WP_REST_Server::READABLE,
			'callback'            => [ __CLASS__, 'get_module_usage' ],
			'permission_callback' => [ __CLASS__, 'can_edit_posts' ],
			'args'                => [
				'refresh' => [
					'type'              => 'boolean',
					'default'           => false,
					'description'       => 'Recompute instead of using the cached classification.',
					'sanitize_callback' => 'rest_sanitize_boolean',
				],
			],
		] );

	}

	/* ---------------------------------------------------------------------
	 * Permissions
	 * ------------------------------------------------------------------ */

	/**
	 * Whether the user may see the settings screen at all.
	 *
	 * This is the coarse gate. Per field capabilities are enforced inside the
	 * registry on both read and write, so a user who passes here still only
	 * sees and writes the groups they are entitled to.
	 *
	 * @since x.x.x
	 * @param \WP_REST_Request $request Request.
	 * @return bool|\WP_Error
	 */
	public static function can_read( $request ) {
		if ( $request->get_param( 'network' ) ) {
			return current_user_can( 'manage_network_plugins' ) ? true : self::forbidden();
		}

		if ( current_user_can( 'edit_posts' ) || current_user_can( 'manage_options' ) ) {
			return true;
		}

		return self::forbidden();
	}

	/**
	 * Capability gate for the widget library.
	 *
	 * @since x.x.x
	 * @return bool|\WP_Error
	 */
	public static function can_edit_posts() {
		return current_user_can( 'edit_posts' ) ? true : self::forbidden();
	}

	/**
	 * Capability gate for administrator only endpoints.
	 *
	 * @since x.x.x
	 * @return bool|\WP_Error
	 */
	public static function can_manage() {
		return current_user_can( 'manage_options' ) ? true : self::forbidden();
	}

	/**
	 * Standard forbidden response.
	 *
	 * @since x.x.x
	 * @return \WP_Error
	 */
	private static function forbidden() {
		return new \WP_Error(
			'pp_rest_forbidden',
			esc_html__( 'You are not allowed to manage these settings.', 'powerpack-lite-for-elementor' ),
			[ 'status' => rest_authorization_required_code() ]
		);
	}

	/* ---------------------------------------------------------------------
	 * Settings
	 * ------------------------------------------------------------------ */

	/**
	 * Read every setting the current user is entitled to.
	 *
	 * @since x.x.x
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response
	 */
	public static function get_settings( $request ) {
		$network = (bool) $request->get_param( 'network' );

		return rest_ensure_response( self::settings_payload( $network ) );
	}

	/**
	 * Apply a partial settings update.
	 *
	 * @since x.x.x
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public static function update_settings( $request ) {
		$network = (bool) $request->get_param( 'network' );
		$payload = $request->get_param( 'settings' );

		if ( ! is_array( $payload ) ) {
			return new \WP_Error(
				'pp_rest_invalid_payload',
				esc_html__( 'Settings must be an object of key and value pairs.', 'powerpack-lite-for-elementor' ),
				[ 'status' => 400 ]
			);
		}

		$unknown = array_diff( array_keys( $payload ), array_keys( PP_Settings_Registry::get_fields() ) );
		$result  = PP_Settings_Registry::apply( $payload, [ 'network' => $network ] );

		$response = self::settings_payload( $network );

		$response['written'] = $result['written'];
		$response['skipped'] = $result['skipped'];
		$response['unknown'] = array_values( $unknown );

		return rest_ensure_response( $response );
	}

	/**
	 * Build the settings payload: values, field descriptors and group list.
	 *
	 * Credentials come back masked. The stored value never leaves the server,
	 * which is why the client must omit an unedited credential from its payload
	 * rather than echoing the mask back.
	 *
	 * @since x.x.x
	 * @param bool $network Whether to operate on network wide options.
	 * @return array
	 */
	private static function settings_payload( $network ) {
		$settings  = [];
		$fields    = [];
		$groups    = [];
		$show_docs = null;

		foreach ( PP_Settings_Registry::get_fields() as $key => $field ) {
			if ( ! PP_Settings_Registry::current_user_can( $key, $network ) ) {
				continue;
			}

			$settings[ $key ] = PP_Settings_Registry::read_for_output( $key, $network );

			$descriptor = [
				'group'  => $field['group'],
				'type'   => $field['type'],
				'secret' => ! empty( $field['secret'] ),
			];

			$choices = self::resolve_choices( $field );

			if ( null !== $choices ) {
				$descriptor['choices'] = $choices;
			}

			/*
			 * Documentation links for a list's choices. Read once per request
			 * rather than per field, and dropped entirely when white label
			 * hides docs links.
			 */
			if ( ! empty( $field['docs'] ) && is_callable( $field['docs'] ) ) {
				if ( null === $show_docs ) {
					$wl        = PP_Admin_Settings::get_settings();
					$show_docs = 'on' !== ( $wl['hide_docs_links'] ?? 'off' );
				}

				if ( $show_docs ) {
					$descriptor['docs'] = (array) call_user_func( $field['docs'] );
				}
			}

			$fields[ $key ]           = $descriptor;
			$groups[ $field['group'] ] = true;
		}

		return [
			'settings' => (object) $settings,
			'fields'   => (object) $fields,
			'groups'   => array_keys( $groups ),
			'network'  => (bool) $network,
		];
	}

	/**
	 * Resolve a field's allowed values into a label map the client can render.
	 *
	 * @since x.x.x
	 * @param array $field Field descriptor.
	 * @return array|null
	 */
	private static function resolve_choices( $field ) {
		if ( empty( $field['choices'] ) ) {
			return null;
		}

		if ( is_callable( $field['choices'] ) ) {
			return (array) call_user_func( $field['choices'] );
		}

		$choices = [];

		foreach ( (array) $field['choices'] as $choice ) {
			$choices[ $choice ] = $choice;
		}

		return $choices;
	}

	/* ---------------------------------------------------------------------
	 * Widget library
	 * ------------------------------------------------------------------ */

	/**
	 * The widget catalogue, grouped by category, with enabled state.
	 *
	 * Writes do not come back here: the client submits the complete enabled
	 * list as 'pp_elementor_modules' through the settings endpoint. The legacy
	 * screen instead posted only the widgets visible under the active filter
	 * and reconstructed the rest with set arithmetic against options written as
	 * a side effect of the previous page render, which is what made the filter
	 * views hazardous.
	 *
	 * The used / not used filter is not carried over for now. Adding it later
	 * is a display concern only: classification would ride along on each widget
	 * here, and the submitted list stays complete either way.
	 *
	 * @since x.x.x
	 * @return \WP_REST_Response
	 */
	public static function get_modules() {
		$widget_info = PP_Config::get_widget_info();
		$all_modules = pp_get_modules();
		$enabled     = pp_get_enabled_modules_lookup();
		$wl          = PP_Admin_Settings::get_settings();
		$show_demo   = 'on' !== ( $wl['hide_demo_links'] ?? 'off' );
		$show_docs   = 'on' !== ( $wl['hide_docs_links'] ?? 'off' );

		ksort( $widget_info );

		$categories = [];

		foreach ( $widget_info as $category_name => $widgets ) {
			if ( empty( $widgets ) ) {
				continue;
			}

			$items = [];

			foreach ( $widgets as $widget_key => $widget_data ) {
				$name   = isset( $widget_data['name'] ) ? $widget_data['name'] : '';
				$is_pro = ! empty( $widget_data['is_pro'] );

				/*
				 * A widget this build ships has to be in the master list, which
				 * is what a save is validated against. A widget it does not
				 * ship is listed but never switchable, so the master list has
				 * no say over it — the free edition carries the paid widgets
				 * here so the library reads as one library rather than two.
				 */
				if ( '' === $name || ( ! $is_pro && ! isset( $all_modules[ $name ] ) ) ) {
					continue;
				}

				$items[] = [
					'name'    => $name,
					'title'   => self::plain_text( isset( $widget_data['title'] ) ? $widget_data['title'] : ucfirst( $widget_key ) ),
					'icon'    => isset( $widget_data['icon'] ) ? $widget_data['icon'] : '',
					'demo'    => $show_demo && ! empty( $widget_data['demo'] ) ? $widget_data['demo'] : '',
					'docs'    => $show_docs && ! empty( $widget_data['docs'] ) ? $widget_data['docs'] : '',
					'enabled' => ! $is_pro && isset( $enabled[ $name ] ),
					'isPro'   => $is_pro,
				];
			}

			if ( empty( $items ) ) {
				continue;
			}

			$categories[] = [
				'name'    => self::plain_text( $category_name ),
				'slug'    => sanitize_title( $category_name ),
				'widgets' => $items,
			];
		}

		return rest_ensure_response( [
			'categories' => $categories,
			'stats'      => pp_get_modules_stats(),
		] );
	}

	/**
	 * Which widgets actually appear on this site.
	 *
	 * Separate from GET /modules on purpose. Answering it means loading every
	 * Elementor document and walking its element tree, which is far too slow to
	 * sit on the path that paints the screen. The client asks for it in the
	 * background once the catalogue is up, and the answer is cached so the next
	 * visit is instant.
	 *
	 * @since x.x.x
	 * @param \WP_REST_Request $request Request.
	 * @return \WP_REST_Response
	 */
	public static function get_module_usage( $request ) {
		if ( ! $request->get_param( 'refresh' ) ) {
			$cached = get_transient( self::USAGE_CACHE_KEY );

			if ( is_array( $cached ) ) {
				$cached['cached'] = true;

				return rest_ensure_response( $cached );
			}
		}

		$all = array_keys( pp_get_modules() );

		// Reading documents needs Elementor. Without it, report the whole
		// library as unclassified rather than as unused, which would be a lie.
		if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
			return rest_ensure_response( [
				'available' => false,
				'used'      => [],
				'notUsed'   => [],
				'cached'    => false,
			] );
		}

		$used = array_values( array_intersect( $all, array_keys( (array) pp_get_filter_modules( 'used' ) ) ) );

		$payload = [
			'available' => true,
			'used'      => $used,
			'notUsed'   => array_values( array_diff( $all, $used ) ),
			'cached'    => false,
		];

		set_transient( self::USAGE_CACHE_KEY, $payload, self::USAGE_CACHE_TTL );

		return rest_ensure_response( $payload );
	}

	/**
	 * Undo HTML escaping for a label headed into a JSON response.
	 *
	 * PP_Config escapes widget and category titles at definition time, so
	 * "Info Grid & Carousel" is stored as "Info Grid &amp; Carousel". The old
	 * template escaped that a second time and rendered the entity verbatim; a
	 * JSON client has the same problem for the opposite reason. Escaping is the
	 * consumer's job, so the API hands over plain text.
	 *
	 * @since x.x.x
	 * @param string $text Possibly escaped label.
	 * @return string
	 */
	private static function plain_text( $text ) {
		return html_entity_decode( (string) $text, ENT_QUOTES, get_bloginfo( 'charset' ) );
	}
}

PP_Settings_REST_Controller::init();
