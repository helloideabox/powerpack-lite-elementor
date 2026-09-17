<?php
namespace PowerpackElementsLite\Classes;

use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Icons_Manager;
use PowerpackElementsLite\Classes\PP_Config;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class PP_Posts_Helper.
 */
class PP_Helper {

	/**
	 * Script debug
	 *
	 * @var script_debug
	 */
	private static $script_debug = null;

	/**
	 * Widgets List
	 *
	 * @var widgets_list
	 */
	private static $widgets_list = null;

	/**
	 * Widget Options
	 *
	 * @var widget_options
	 */
	private static $widget_options = null;

	/**
	 * A list of safe tage for `validate_html_tag` method.
	 */
	const ALLOWED_HTML_WRAPPER_TAGS = [
		'article',
		'aside',
		'div',
		'footer',
		'h1',
		'h2',
		'h3',
		'h4',
		'h5',
		'h6',
		'header',
		'main',
		'nav',
		'p',
		'section',
		'span',
	];

	/**
	 * Convert Comma Separated List into Array
	 *
	 * @param string $list Comma separated list.
	 * @return array
	 * @since 1.4.13.2
	 */
	public static function comma_list_to_array( $list = '' ) {

		$list_array = explode( ',', $list );

		return $list_array;
	}

	/**
	 * Get widgets list.
	 *
	 * The widgets this edition actually ships — which is not the whole
	 * catalogue. PP_Config::get_widget_info() also carries the paid edition's
	 * widgets so the settings screen and the editor can promote them, and this
	 * is the one place they are dropped. Everything downstream registers
	 * widgets or decides what a save is allowed to enable, and neither may see
	 * a widget whose class this plugin does not contain.
	 *
	 * @since 2.3.0
	 * @return array()
	 */
	public static function get_widgets_list() {

		if ( ! isset( self::$widgets_list ) ) {
			// The catalogue is grouped by category; every caller below wants it
			// keyed by widget, so it is flattened once here.
			$widgets_list = [];

			foreach ( PP_Config::get_widget_info() as $widgets ) {
				foreach ( $widgets as $key => $widget ) {
					if ( ! empty( $widget['is_pro'] ) ) {
						continue;
					}

					$widgets_list[ $key ] = $widget;
				}
			}

			self::$widgets_list = $widgets_list;
		}

		return apply_filters( 'ppe_lite_widgets_list', self::$widgets_list );
	}

	/**
	 * Get Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.4.13.1
	 */
	public static function get_widget_name( $slug = '' ) {

		self::$widgets_list = self::get_widgets_list();

		$widget_name = '';

		if ( isset( self::$widgets_list[ $slug ] ) ) {
			$widget_name = self::$widgets_list[ $slug ]['name'];
		}

		return self::apply_deprecated_filter(
			'pp_elements_lite_widget_name',
			'powerpack_elements_widget_name',
			$widget_name,
			[],
			'2.9.10'
		);
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.4.13.1
	 */
	public static function get_widget_title( $slug = '' ) {

		self::$widgets_list = self::get_widgets_list();

		$widget_name = '';

		if ( isset( self::$widgets_list[ $slug ] ) ) {
			$widget_name = self::$widgets_list[ $slug ]['title'];
		}

		return self::apply_deprecated_filter(
			'pp_elements_lite_widget_title',
			'powerpack_elements_widget_title',
			$widget_name,
			[],
			'2.9.10'
		);
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.4.13.1
	 */
	public static function get_widget_icon( $slug = '' ) {

		self::$widgets_list = self::get_widgets_list();

		$widget_icon = '';

		if ( isset( self::$widgets_list[ $slug ] ) ) {
			$widget_icon = self::$widgets_list[ $slug ]['icon'];
		}

		return self::apply_deprecated_filter(
			'pp_elements_lite_widget_icon',
			'powerpack_elements_widget_icon',
			$widget_icon,
			[],
			'2.9.10'
		);
	}

	/**
	 * Provide Widget Docs URL
	 *
	 * For the editor's "Need Help?" link, so it carries the editor panel's
	 * campaign parameters. The filter sees the tracked URL, and whatever it
	 * returns is used as is.
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 3.0.0
	 */
	public static function get_widget_docs( $slug = '' ) {

		self::$widgets_list = self::get_widgets_list();

		$widget_docs = '';

		if ( isset( self::$widgets_list[ $slug ]['docs'] ) ) {
			$widget_docs = self::get_tracked_url( self::$widgets_list[ $slug ]['docs'], 'panel' );
		}

		return apply_filters( 'pp_elements_lite_widget_docs', $widget_docs );
	}

	/**
	 * Add PowerPack's campaign parameters to a powerpackelements.com link.
	 *
	 * Links are stored plain, in PP_Config and elsewhere, and tagged here as
	 * they are output, so the parameters live in one place and each placement
	 * reports under its own medium: 'panel' for the Elementor editor, 'settings'
	 * for the plugin's settings screen. add_query_arg() keeps a URL that
	 * already has a query string or an anchor intact.
	 *
	 * A link to any other host is returned untouched, so a URL swapped in
	 * through a filter never picks up PowerPack's tracking.
	 *
	 * @since x.x.x
	 *
	 * @param string $url    Link to tag.
	 * @param string $medium Where the link is shown.
	 * @return string The tagged link, or '' for an empty one.
	 */
	public static function get_tracked_url( $url, $medium ) {
		if ( empty( $url ) ) {
			return '';
		}

		$host = wp_parse_url( $url, PHP_URL_HOST );

		if ( ! is_string( $host ) || ! preg_match( '/(^|\.)powerpackelements\.com$/i', $host ) ) {
			return $url;
		}

		$params = [
			'utm_source'   => 'widget',
			'utm_medium'   => $medium,
			'utm_campaign' => 'userkb',
		];

		/**
		 * Filters the campaign parameters added to powerpackelements.com links.
		 *
		 * Return an empty array to leave links untagged.
		 *
		 * @since x.x.x
		 *
		 * @param array  $params Query parameter => value.
		 * @param string $url    Link being tagged.
		 * @param string $medium Where the link is shown: 'panel' or 'settings'.
		 */
		$params = apply_filters( 'powerpack_elements_tracked_url_params', $params, $url, $medium );

		if ( empty( $params ) || ! is_array( $params ) ) {
			return $url;
		}

		return add_query_arg( urlencode_deep( $params ), $url );
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.4.13.1
	 */
	public static function get_widget_keywords( $slug = '' ) {

		self::$widgets_list = self::get_widgets_list();

		$widget_keywords = '';

		if ( isset( self::$widgets_list[ $slug ] ) ) {
			$widget_keywords = self::$widgets_list[ $slug ]['keywords'];
		}

		return self::apply_deprecated_filter(
			'pp_elements_lite_widget_keywords',
			'powerpack_elements_widget_keywords',
			$widget_keywords,
			[],
			'2.9.10'
		);
	}

	/**
	 * Get widget styles.
	 *
	 * @since 2.1.0
	 * @return array
	 */
	public static function get_widget_style() {

		return PP_Config::get_widget_style();
	}

	/**
	 * Get Widget Options.
	 *
	 * @since 2.3.0
	 * @return array()
	 */
	public static function get_widget_options() {
		if ( null === self::$widget_options ) {
			if ( ! isset( self::$widgets_list ) ) {
				$widgets = self::get_widgets_list();
			} else {
				$widgets = self::$widgets_list;
			}

			$saved_widgets = powerpack_elements_lite_get_enabled_modules();

			if ( is_array( $widgets ) ) {

				foreach ( $widgets as $slug => $data ) {

					if ( in_array( $data['name'], $saved_widgets, true ) ) {
						$widgets[ $slug ]['is_activate'] = true;
					} else {
						$widgets[ $slug ]['is_activate'] = false;
					}
				}
			}

			self::$widget_options = $widgets;
		}

		return apply_filters( 'ppe_lite_enabled_widgets', self::$widget_options );
	}

	/**
	 * Check if widget is active.
	 *
	 * @param string $slug Module slug.
	 * @return boolean
	 * @since 2.3.0
	 */
	public static function is_widget_active( $slug = '' ) {
		$widgets     = self::get_widget_options();
		$is_activate = false;

		if ( isset( $widgets[ $slug ] ) ) {
			$is_activate = $widgets[ $slug ]['is_activate'];
		}

		return $is_activate;
	}

	/**
	 * Elementor
	 *
	 * Retrieves the elementor plugin instance
	 *
	 * @since  1.4.13.2
	 * @return \Elementor\Plugin|$instace
	 */
	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * Check if Elementor experimental feature is active.
	 *
	 * @param string $feature Feature slug.
	 * @return boolean
	 * @since 2.7.23
	 */
	public static function is_feature_active( $feature = '' ) {
		$is_active = false;

		if ( '' !== $feature && \Elementor\Plugin::$instance->experiments->is_feature_active( $feature ) ) {
			$is_active = true;
		}

		return $is_active;
	}

	/**
	 * Get upgrade notice HTML.
	 *
	 * @since 2.9.10
	 *
	 * @return string
	 */
	public static function get_upgrade_notice() {

		$upgrade_url = 'https://powerpackelements.com/upgrade/?utm_medium=pp-elements-lite&utm_source=pp-widget-upgrade-section&utm_campaign=pp-pro-upgrade';

		$upgrade_message = sprintf(
			/* translators: 1: Opening anchor tag, 2: Closing anchor tag. */
			__(
				'Upgrade to %1$sPro Version%2$s for 90+ widgets, exciting extensions and advanced features.',
				'powerpack-lite-for-elementor'
			),
			'<a href="' . $upgrade_url . '" target="_blank" rel="noopener">',
			'</a>'
		);

		return wp_kses_post(
			apply_filters( 'upgrade_powerpack_message', $upgrade_message )
		);
	}

	/**
	 * Get full Pro feature notice.
	 *
	 * @param string $message Optional prefix message.
	 * @since 2.9.10
	 *
	 * @return string
	 */
	public static function get_pro_feature_notice( $message = '' ) {

		$prefix = '';

		if ( ! empty( $message ) ) {
			$prefix = esc_html( $message ) . ' ';
		}

		return $prefix . self::get_upgrade_notice();
	}

	/**
	 * Check if script debug is enabled.
	 *
	 * @since 2.1.0
	 *
	 * @return string The CSS suffix.
	 */
	public static function is_script_debug() {

		if ( null === self::$script_debug ) {

			self::$script_debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
		}

		return self::$script_debug;
	}

	/**
	 * Get contact forms of supported forms plugins
	 *
	 * @since 1.4.14.1
	 * @access public
	 */
	public static function get_contact_forms( $plugin = '' ) {
		$options       = [];
		$contact_forms = [];

		// Contact Form 7
		if ( 'Contact_Form_7' == $plugin && function_exists( 'wpcf7' ) ) {
			$args = array(
				'post_type'      => 'wpcf7_contact_form',
				'posts_per_page' => -1,
			);

			$cf7_forms = get_posts( $args );

			if ( ! empty( $cf7_forms ) && ! is_wp_error( $cf7_forms ) ) {
				foreach ( $cf7_forms as $form ) {
					$contact_forms[ $form->ID ] = $form->post_title;
				}
			}
		}

		// Fluent Forms
		if ( 'Fluent_Forms' == $plugin && function_exists( 'wpFluentForm' ) ) {
			$fluent_forms = \FluentForm\App\Models\Form::select( array( 'id', 'title' ) )
				->orderBy( 'id', 'DESC' )
				->get();

			if ( ! empty( $fluent_forms ) ) {
				foreach ( $fluent_forms as $form ) {
					$contact_forms[ $form->id ] = $form->title;
				}
			}
		}

		// Formidable Forms
		if ( 'Formidable_Forms' == $plugin && class_exists( 'FrmForm' ) ) {
			$formidable_forms = \FrmForm::get_published_forms( [], 999, 'exclude' );
			if ( count( $formidable_forms ) ) {
				foreach ( $formidable_forms as $form ) {
					$contact_forms[ $form->id ] = $form->name;
				}
			}
		}

		// Gravity Forms
		if ( 'Gravity_Forms' == $plugin && class_exists( 'GFCommon' ) ) {
			$gravity_forms = \RGFormsModel::get_forms( null, 'title' );

			if ( ! empty( $gravity_forms ) && ! is_wp_error( $gravity_forms ) ) {
				foreach ( $gravity_forms as $form ) {
					$contact_forms[ $form->id ] = $form->title;
				}
			}
		}

		// Ninja Forms
		if ( 'Ninja_Forms' == $plugin && class_exists( 'Ninja_Forms' ) ) {
			$ninja_forms = Ninja_Forms()->form()->get_forms();

			if ( ! empty( $ninja_forms ) && ! is_wp_error( $ninja_forms ) ) {
				foreach ( $ninja_forms as $form ) {
					$contact_forms[ $form->get_id() ] = $form->get_setting( 'title' );
				}
			}
		}

		// WPforms
		if ( 'WP_Forms' == $plugin && function_exists( 'wpforms' ) ) {
			$args = array(
				'post_type'      => 'wpforms',
				'posts_per_page' => -1,
			);

			$wpf_forms = get_posts( $args );

			if ( ! empty( $wpf_forms ) && ! is_wp_error( $wpf_forms ) ) {
				foreach ( $wpf_forms as $form ) {
					$contact_forms[ $form->ID ] = $form->post_title;
				}
			}
		}

		// Contact Forms List
		if ( ! empty( $contact_forms ) ) {
			$options[0] = esc_html__( 'Select a Contact Form', 'powerpack-lite-for-elementor' );
			foreach ( $contact_forms as $form_id => $form_title ) {
				$options[ $form_id ] = $form_title;
			}
		}

		if ( empty( $options ) ) {
			$options[0] = esc_html__( 'No contact forms found!', 'powerpack-lite-for-elementor' );
		}

		return $options;
	}

	/**
	 * Returns user agent.
	 *
	 * @since 2.1.0
	 * @return string
	 */
	private function get_user_agent() {

		if ( empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			return '';
		}

		return sanitize_text_field(
			wp_unslash( $_SERVER['HTTP_USER_AGENT'] )
		);
	}

	/**
	 * Get Client IP address
	 *
	 * @since 2.1.0
	 * @return string
	 */
	public static function get_client_ip() {

		if ( class_exists( '\Elementor\Utils' ) ) {
			return \Elementor\Utils::get_client_ip();
		}

		return '127.0.0.1';
	}

	/**
	 * Validate an HTML tag against a safe allowed list.
	 *
	 * @since 2.3.2
	 * @param string $tag specifies the HTML Tag.
	 * @access public
	 * @return string
	 */
	public static function validate_html_tag( $tag ) {
		// Check if Elementor method exists, else we will run custom validation code.
		if ( method_exists( 'Elementor\Utils', 'validate_html_tag' ) ) {
			return Utils::validate_html_tag( $tag );
		} else {
			return in_array( strtolower( $tag ), self::ALLOWED_HTML_WRAPPER_TAGS, true ) ? $tag : 'div';
		}
	}

	/**
	 * Safe print a validated HTML tag.
	 *
	 * @since 2.7.7
	 * @param string $tag
	 */
	public static function print_validated_html_tag( $tag ) {
		// PHPCS - the method validate_html_tag is safe.
		echo self::validate_html_tag( $tag ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public static function is_tribe_events_post( $post_id ) {
		return ( class_exists( 'Tribe__Events__Main' ) && 'tribe_events' === get_post_type( $post_id ) );
	}

	/**
	 * Render the previous and next arrows of a carousel.
	 *
	 * The arrows are div[role=button] rather than native buttons: themes style bare
	 * buttons on :hover and :focus more specifically than a single class, which would
	 * override the widget's own arrow colour controls. Swiper's a11y module supplies
	 * the Enter and Space handling.
	 *
	 * @param \Elementor\Widget_Base $widget Widget the arrows belong to. Skins pass their parent.
	 * @param array                  $args {
	 *     Optional. Arguments.
	 *
	 *     @type array        $settings      Settings to read. Default the widget's settings for display.
	 *     @type string       $prefix        Control ID prefix, e.g. 'classic_' for a skin. Default empty.
	 *     @type string|false $toggle        Switcher control that turns the arrows on, before the prefix,
	 *                                       or false when the caller has already checked. Default 'arrows'.
	 *     @type array|null   $icon          Icon for the next arrow, bypassing the 'select_arrow' control.
	 *     @type string[]     $classes       Classes after 'pp-slider-arrow'. '{dir}' becomes prev or next
	 *                                       and '{id}' the widget ID. These are the hooks the carousel
	 *                                       script passes to Swiper, so they must match it.
	 *     @type string[]     $labels        Accessible names keyed 'prev' and 'next'. Empty values fall
	 *                                       back to "Previous slide" and "Next slide".
	 *     @type string       $aria_controls ID of the element the arrows control. Default empty.
	 * }
	 */
	public static function render_arrows( $widget, $args = [] ) {
		$args = wp_parse_args(
			$args,
			[
				'settings'      => null,
				'prefix'        => '',
				'toggle'        => 'arrows',
				'icon'          => null,
				'classes'       => [ 'elementor-swiper-button-{dir}', 'swiper-button-{dir}-{id}' ],
				'labels'        => [],
				'aria_controls' => '',
			]
		);

		$settings = null === $args['settings'] ? $widget->get_settings_for_display() : $args['settings'];

		if ( $args['toggle'] && 'yes' !== $settings[ $args['prefix'] . $args['toggle'] ] ) {
			return;
		}

		if ( null === $args['icon'] ) {
			$icons = self::get_arrow_icons( $settings, $args['prefix'] );
		} elseif ( ! empty( $args['icon']['value'] ) ) {
			$icons = [
				'prev' => self::get_reversed_arrow_icon( $args['icon'] ),
				'next' => $args['icon'],
			];
		} else {
			$icons = null;
		}

		if ( ! $icons ) {
			return;
		}

		$labels = array_merge(
			[
				'prev' => __( 'Previous slide', 'powerpack-lite-for-elementor' ),
				'next' => __( 'Next slide', 'powerpack-lite-for-elementor' ),
			],
			array_filter( $args['labels'] )
		);

		$id = $widget->get_id();

		foreach ( $icons as $direction => $icon ) {
			$attributes = [
				'class'      => array_merge( [ 'pp-slider-arrow' ], str_replace( [ '{dir}', '{id}' ], [ $direction, $id ], $args['classes'] ) ),
				'role'       => 'button',
				'tabindex'   => '0',
				'aria-label' => $labels[ $direction ],
			];

			if ( $args['aria_controls'] ) {
				$attributes['aria-controls'] = $args['aria_controls'];
			}
			?>
			<div <?php Utils::print_html_attributes( $attributes ); ?>>
				<?php
				if ( empty( $icon['library'] ) ) {
					// A Font Awesome 4 class saved before the icon library migration.
					printf( '<i class="%s" aria-hidden="true"></i>', esc_attr( $icon['value'] ) );
				} else {
					Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
				}
				?>
			</div>
			<?php
		}
	}

	/**
	 * Resolve the previous and next icons from a carousel's 'select_arrow' control.
	 *
	 * Settings saved before the Font Awesome 5 migration keep the icon as a class string
	 * in 'arrow', which is not a registered control, so it only exists on that old data.
	 * Those come back with an empty 'library'.
	 *
	 * @since x.x.x
	 *
	 * @param array  $settings Widget settings.
	 * @param string $prefix   Control ID prefix. Default empty.
	 * @return array|null Icons keyed 'prev' and 'next', or null when no icon is set.
	 */
	public static function get_arrow_icons( $settings, $prefix = '' ) {
		$old_key = $prefix . 'arrow';
		$new_key = $prefix . 'select_arrow';

		$migration_allowed = Icons_Manager::is_migration_allowed();

		if ( ! isset( $settings[ $old_key ] ) && ! $migration_allowed ) {
			$settings[ $old_key ] = 'fa fa-angle-right';
		}

		$is_new   = ! isset( $settings[ $old_key ] ) && $migration_allowed;
		$migrated = isset( $settings['__fa4_migrated'][ $new_key ] );

		if ( $is_new || $migrated ) {
			if ( empty( $settings[ $new_key ]['value'] ) ) {
				return null;
			}

			$next = $settings[ $new_key ];
		} elseif ( ! empty( $settings[ $old_key ] ) ) {
			$next = [
				'value'   => $settings[ $old_key ],
				'library' => '',
			];
		} else {
			return null;
		}

		return [
			'prev' => self::get_reversed_arrow_icon( $next ),
			'next' => $next,
		];
	}

	/**
	 * Mirror an arrow icon for use as the previous arrow.
	 *
	 * Flips 'down' to 'up' for vertical carousels and 'right' to 'left' for horizontal ones.
	 * The word boundary keeps icons such as 'download' and 'copyright' intact. Uploaded SVG
	 * icons have no class to flip and are returned unchanged.
	 *
	 * @since x.x.x
	 *
	 * @param array $icon Icon control value.
	 * @return array
	 */
	public static function get_reversed_arrow_icon( $icon ) {
		if ( ! is_string( $icon['value'] ) ) {
			return $icon;
		}

		$replacements = [
			'/-down\b/'  => '-up',
			'/-right\b/' => '-left',
		];

		foreach ( $replacements as $pattern => $replacement ) {
			$reversed = preg_replace( $pattern, $replacement, $icon['value'] );

			if ( $reversed !== $icon['value'] ) {
				$icon['value'] = $reversed;
				break;
			}
		}

		return $icon;
	}

	public static function apply_deprecated_filter( $old_hook, $new_hook, $value, $args = [], $version = '2.9.10' ) {

		$value = apply_filters_ref_array( $new_hook, array_merge( array( $value ), $args ) );

		if ( has_filter( $old_hook ) ) {
			_deprecated_hook( esc_html( $old_hook ), esc_html( $version ), esc_html( $new_hook ) );
			$value = apply_filters_ref_array( $old_hook, array_merge( array( $value ), $args ) );
		}

		return $value;
	}

	public static function do_deprecated_action( $old_hook, $new_hook, $args = [], $version = '2.9.0' ) {

		do_action_ref_array( $new_hook, $args );

		if ( has_action( $old_hook ) ) {
			_deprecated_hook( esc_html( $old_hook ), esc_html( $version ), esc_html( $new_hook ) );
			do_action_ref_array( $old_hook, $args );
		}
	}

	/**
	 * Get the list of PHP timezone identifiers, keyed by identifier.
	 *
	 * @since 3.0.0
	 * @access public
	 *
	 * @return array Timezone identifier => identifier.
	 */
	public static function get_timezones() {
		$timezone_list = [];
		foreach ( timezone_identifiers_list() as $timezone ) {
			$timezone_list[ $timezone ] = $timezone;
		}
		return $timezone_list;
	}

	/**
	 * Convert a date string from the site timezone to another timezone.
	 *
	 * @since 3.0.0
	 * @access public
	 * @param string $date         The source date string.
	 * @param string $formate      The output date format.
	 * @param string $new_timezone Target timezone identifier.
	 * @return string The formatted date, or the original value on failure.
	 */
	public static function get_timezones_converted_date( $date, $formate, $new_timezone ) {
		if ( empty( $date ) ) {
			return '';
		}

		$timezone_string = ( '' !== get_option( 'timezone_string' ) ) ? get_option( 'timezone_string' ) : 'UTC';

		try {
			$datetime = new \DateTime( $date, new \DateTimeZone( $timezone_string ) );
			$datetime->setTimezone( new \DateTimeZone( $new_timezone ) );
			return $datetime->format( $formate );
		} catch ( \Exception $e ) {
			return $date;
		}
	}

	/**
	 * Strings a carousel widget announces to assistive technology.
	 *
	 * Every widget that enables Swiper's a11y module passes it the same set of
	 * messages, so they are defined once here and localised per script. The
	 * {{index}} and {{slidesLength}} placeholders are substituted by Swiper
	 * itself, not by us.
	 *
	 * @since x.x.x
	 * @access public
	 *
	 * @param array $extra Widget-specific strings, merged over the shared set.
	 * @return array
	 */
	public static function get_carousel_a11y_strings( $extra = [] ) {
		return array_merge(
			[
				'prevSlide'            => esc_html__( 'Previous slide', 'powerpack-lite-for-elementor' ),
				'nextSlide'            => esc_html__( 'Next slide', 'powerpack-lite-for-elementor' ),
				'firstSlide'           => esc_html__( 'This is the first slide', 'powerpack-lite-for-elementor' ),
				'lastSlide'            => esc_html__( 'This is the last slide', 'powerpack-lite-for-elementor' ),
				/* translators: the {{index}} placeholder is replaced by Swiper */
				'paginationBullet'     => esc_html__( 'Go to slide {{index}}', 'powerpack-lite-for-elementor' ),
				/* translators: the {{index}} and {{slidesLength}} placeholders are replaced by Swiper */
				'slideLabel'           => esc_html__( 'Slide {{index}} of {{slidesLength}}', 'powerpack-lite-for-elementor' ),
				'slideRoleDescription' => esc_html__( 'slide', 'powerpack-lite-for-elementor' ),
				/* translators: %s: name of the item now showing */
				'status'               => esc_html__( 'Showing %s', 'powerpack-lite-for-elementor' ),
				'slideStatus'          => esc_html( self::get_slide_status_format() ),
			],
			$extra
		);
	}

	/**
	 * The text a carousel status region starts out with, before any slide change.
	 *
	 * Every carousel prints one of these so a pointer user gets the feedback Swiper
	 * only gives keyboard users, and the script rewrites it from the same msgid on
	 * every move, so the announcement reads identically before and after the first
	 * slide change and translators only see the string once. Widgets that can name
	 * their slides pass a $label, appended the way the script appends it.
	 *
	 * The return value is not escaped, so escape it at the point of output.
	 *
	 * @since x.x.x
	 * @access public
	 *
	 * @param int|string $current Slide number now showing, one based.
	 * @param int|string $total   Number of slides.
	 * @param string     $label   Optional name of the slide now showing.
	 * @return string
	 */
	public static function get_slide_status_text( $current, $total, $label = '' ) {
		$status = sprintf( self::get_slide_status_format(), $current, $total );

		if ( '' !== $label ) {
			$status .= ': ' . $label;
		}

		return $status;
	}

	/**
	 * The one definition of the slide status format.
	 *
	 * Both the element the widget prints and the script that rewrites it on every
	 * slide change read from here, so the announcement says the same thing before
	 * and after the first move and translators only see the string once.
	 *
	 * @since x.x.x
	 * @access private
	 *
	 * @return string
	 */
	private static function get_slide_status_format() {
		/* translators: 1: current slide number, 2: total slides */
		return __( 'Showing Slide %1$s of %2$s', 'powerpack-lite-for-elementor' );
	}
}
