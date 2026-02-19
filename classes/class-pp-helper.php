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
	 * @since 2.3.0
	 * @return array()
	 */
	public static function get_widgets_list() {

		if ( ! isset( self::$widgets_list ) ) {
			self::$widgets_list = PP_Config::get_widget_info();
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

		self::$widgets_list = PP_Config::get_widget_info();

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

		self::$widgets_list = PP_Config::get_widget_info();

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
	public static function get_widget_categories( $slug = '' ) {

		self::$widgets_list = PP_Config::get_widget_info();

		$widget_categories = '';

		if ( isset( self::$widgets_list[ $slug ] ) ) {
			$widget_categories = self::$widgets_list[ $slug ]['categories'];
		}

		return self::apply_deprecated_filter(
			'pp_elements_lite_widget_categories',
			'powerpack_elements_widget_categories',
			$widget_categories,
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

		self::$widgets_list = PP_Config::get_widget_info();

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
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.4.13.1
	 */
	public static function get_widget_keywords( $slug = '' ) {

		self::$widgets_list = PP_Config::get_widget_info();

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
	 * Render swiper slider arrows
	 *
	 * @since 2.6.1
	 * @param object $widget
	 */
	public static function render_arrows( $widget ) {
		$settings = $widget->get_settings_for_display();

		$migration_allowed = Icons_Manager::is_migration_allowed();

		if ( ! isset( $settings['arrow'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default.
			$settings['arrow'] = 'fa fa-angle-right';
		}

		$has_icon = ! empty( $settings['arrow'] );

		if ( ! $has_icon && ! empty( $settings['select_arrow']['value'] ) ) {
			$has_icon = true;
		}

		if ( ! empty( $settings['arrow'] ) ) {
			$widget->add_render_attribute( 'arrow-icon', 'class', $settings['arrow'] );
			$widget->add_render_attribute( 'arrow-icon', 'aria-hidden', 'true' );
		}

		$migrated = isset( $settings['__fa4_migrated']['select_arrow'] );
		$is_new = ! isset( $settings['arrow'] ) && $migration_allowed;

		if ( 'yes' === $settings['arrows'] ) {
			if ( $has_icon ) {
				if ( $is_new || $migrated ) {
					$next_arrow = $settings['select_arrow'];
					$prev_arrow = str_replace( 'right', 'left', $settings['select_arrow'] );
				} else {
					$next_arrow = $settings['arrow'];
					$prev_arrow = str_replace( 'right', 'left', $settings['arrow'] );
				}
			} else {
				$next_arrow = 'fa fa-angle-right';
				$prev_arrow = 'fa fa-angle-left';
			}

			if ( ! empty( $settings['arrow'] ) || ( ! empty( $settings['select_arrow']['value'] ) && $is_new ) ) { ?>
				<div class="pp-slider-arrow elementor-swiper-button-prev swiper-button-prev-<?php echo esc_attr( $widget->get_id() ); ?>">
					<?php if ( $is_new || $migrated ) :
						Icons_Manager::render_icon( $prev_arrow, [ 'aria-hidden' => 'true' ] );
					else : ?>
						<i <?php $widget->print_render_attribute_string( 'arrow-icon' ); ?>></i>
					<?php endif; ?>
				</div>
				<div class="pp-slider-arrow elementor-swiper-button-next swiper-button-next-<?php echo esc_attr( $widget->get_id() ); ?>">
					<?php if ( $is_new || $migrated ) :
						Icons_Manager::render_icon( $next_arrow, [ 'aria-hidden' => 'true' ] );
					else : ?>
						<i <?php $widget->print_render_attribute_string( 'arrow-icon' ); ?>></i>
					<?php endif; ?>
				</div>
			<?php }
		}
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
}
