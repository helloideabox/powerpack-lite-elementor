<?php
namespace PowerpackElementsLite\Classes;

use Elementor\Plugin;
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
	 * Get Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.4.13.1
	 */
	public static function get_widget_name( $slug = '' ) {

		$widget_list = PP_Config::get_widget_info();

		$widget_name = '';

		if ( isset( $widget_list[ $slug ] ) ) {
			$widget_name = $widget_list[ $slug ]['name'];
		}

		return apply_filters( 'pp_elements_lite_widget_name', $widget_name );
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.4.13.1
	 */
	public static function get_widget_title( $slug = '' ) {

		$widget_list = PP_Config::get_widget_info();

		$widget_name = '';

		if ( isset( $widget_list[ $slug ] ) ) {
			$widget_name = $widget_list[ $slug ]['title'];
		}

		return apply_filters( 'pp_elements_lite_widget_title', $widget_name );
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.4.13.1
	 */
	public static function get_widget_categories( $slug = '' ) {

		$widget_list = PP_Config::get_widget_info();

		$widget_categories = '';

		if ( isset( $widget_list[ $slug ] ) ) {
			$widget_categories = $widget_list[ $slug ]['categories'];
		}

		return apply_filters( 'pp_elements_lite_widget_categories', $widget_categories );
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.4.13.1
	 */
	public static function get_widget_icon( $slug = '' ) {

		$widget_list = PP_Config::get_widget_info();

		$widget_icon = '';

		if ( isset( $widget_list[ $slug ] ) ) {
			$widget_icon = $widget_list[ $slug ]['icon'];
		}

		return apply_filters( 'pp_elements_lite_widget_icon', $widget_icon );
	}

	/**
	 * Provide Widget Name
	 *
	 * @param string $slug Module slug.
	 * @return string
	 * @since 1.4.13.1
	 */
	public static function get_widget_keywords( $slug = '' ) {

		$widget_list = PP_Config::get_widget_info();

		$widget_keywords = '';

		if ( isset( $widget_list[ $slug ] ) ) {
			$widget_keywords = $widget_list[ $slug ]['keywords'];
		}

		return apply_filters( 'pp_elements_lite_widget_keywords', $widget_keywords );
	}

	/**
	 * Get widget styles.
	 *
	 * @since x.x.x
	 * @return array
	 */
	public static function get_widget_style() {

		return PP_Config::get_widget_style();
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
	 * Check if script debug is enabled.
	 *
	 * @since x.x.x
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
	 * Get Google Map Languages
	 *
	 * @since 1.4.11.2
	 * @access public
	 */
	public static function get_google_map_languages() {

		$languages = array(
			'af'     => __( 'AFRIKAAN', 'powerpack' ),
			'sq'     => __( 'ALBANIAN', 'powerpack' ),
			'am'     => __( 'AMHARIC', 'powerpack' ),
			'ar'     => __( 'ARABIC', 'powerpack' ),
			'hy'     => __( 'ARMENIAN', 'powerpack' ),
			'az'     => __( 'AZERBAIJANI', 'powerpack' ),
			'eu'     => __( 'BASQUE', 'powerpack' ),
			'be'     => __( 'BELARUSIAN', 'powerpack' ),
			'bn'     => __( 'BENGALI', 'powerpack' ),
			'bs'     => __( 'BOSNIAN', 'powerpack' ),
			'bg'     => __( 'BULGARIAN', 'powerpack' ),
			'my'     => __( 'BURMESE', 'powerpack' ),
			'ca'     => __( 'CATALAN', 'powerpack' ),
			'zh'     => __( 'CHINESE', 'powerpack' ),
			'zh-CN'  => __( 'CHINESE (SIMPLIFIED)', 'powerpack' ),
			'zh-HK'  => __( 'CHINESE (HONG KONG)', 'powerpack' ),
			'zh-TW'  => __( 'CHINESE (TRADITIONAL)', 'powerpack' ),
			'hr'     => __( 'CROATIAN', 'powerpack' ),
			'cs'     => __( 'CZECH', 'powerpack' ),
			'da'     => __( 'DANISH', 'powerpack' ),
			'nl'     => __( 'DUTCH', 'powerpack' ),
			'en'     => __( 'ENGLISH', 'powerpack' ),
			'en-AU'  => __( 'ENGLISH (AUSTRALIAN)', 'powerpack' ),
			'en-GB'  => __( 'ENGLISH (GREAT BRITAIN)', 'powerpack' ),
			'et'     => __( 'ESTONIAN', 'powerpack' ),
			'fa'     => __( 'FARSI', 'powerpack' ),
			'fi'     => __( 'FINNISH', 'powerpack' ),
			'fil'    => __( 'FILIPINO', 'powerpack' ),
			'fr'     => __( 'FRENCH', 'powerpack' ),
			'fr-CA'  => __( 'FRENCH (CANADA)', 'powerpack' ),
			'gl'     => __( 'GALICIAN', 'powerpack' ),
			'ka'     => __( 'GEORGIAN', 'powerpack' ),
			'de'     => __( 'GERMAN', 'powerpack' ),
			'el'     => __( 'GREEK', 'powerpack' ),
			'gu'     => __( 'GUJARATI', 'powerpack' ),
			'iw'     => __( 'HEBREW', 'powerpack' ),
			'hi'     => __( 'HINDI', 'powerpack' ),
			'hu'     => __( 'HUNGARIAN', 'powerpack' ),
			'is'     => __( 'ICELANDIC', 'powerpack' ),
			'id'     => __( 'INDONESIAN', 'powerpack' ),
			'it'     => __( 'ITALIAN', 'powerpack' ),
			'ja'     => __( 'JAPANESE', 'powerpack' ),
			'kn'     => __( 'KANNADA', 'powerpack' ),
			'kk'     => __( 'KAZAKH', 'powerpack' ),
			'km'     => __( 'KHMER', 'powerpack' ),
			'ko'     => __( 'KOREAN', 'powerpack' ),
			'ky'     => __( 'KYRGYZ', 'powerpack' ),
			'lo'     => __( 'LAO', 'powerpack' ),
			'lv'     => __( 'LATVIAN', 'powerpack' ),
			'lt'     => __( 'LITHUANIAN', 'powerpack' ),
			'mk'     => __( 'MACEDONIAN', 'powerpack' ),
			'ms'     => __( 'MALAY', 'powerpack' ),
			'ml'     => __( 'MALAYALAM', 'powerpack' ),
			'mr'     => __( 'MARATHI', 'powerpack' ),
			'mn'     => __( 'MONGOLIAN', 'powerpack' ),
			'ne'     => __( 'NEPALI', 'powerpack' ),
			'no'     => __( 'NORWEGIAN', 'powerpack' ),
			'pl'     => __( 'POLISH', 'powerpack' ),
			'pt'     => __( 'PORTUGUESE', 'powerpack' ),
			'pt-BR'  => __( 'PORTUGUESE (BRAZIL)', 'powerpack' ),
			'pt-PT'  => __( 'PORTUGUESE (PORTUGAL)', 'powerpack' ),
			'pa'     => __( 'PUNJABI', 'powerpack' ),
			'ro'     => __( 'ROMANIAN', 'powerpack' ),
			'ru'     => __( 'RUSSIAN', 'powerpack' ),
			'sr'     => __( 'SERBIAN', 'powerpack' ),
			'si'     => __( 'SINHALESE', 'powerpack' ),
			'sk'     => __( 'SLOVAK', 'powerpack' ),
			'sl'     => __( 'SLOVENIAN', 'powerpack' ),
			'es'     => __( 'SPANISH', 'powerpack' ),
			'es-419' => __( 'SPANISH (LATIN AMERICA)', 'powerpack' ),
			'sw'     => __( 'SWAHILI', 'powerpack' ),
			'sv'     => __( 'SWEDISH', 'powerpack' ),
			'ta'     => __( 'TAMIL', 'powerpack' ),
			'te'     => __( 'TELUGU', 'powerpack' ),
			'th'     => __( 'THAI', 'powerpack' ),
			'tr'     => __( 'TURKISH', 'powerpack' ),
			'uk'     => __( 'UKRAINIAN', 'powerpack' ),
			'ur'     => __( 'URDU', 'powerpack' ),
			'uz'     => __( 'UZBEK', 'powerpack' ),
			'vi'     => __( 'VIETNAMESE', 'powerpack' ),
			'zu'     => __( 'ZULU', 'powerpack' ),
		);

		return $languages;
	}

	public static function get_recaptcha_desc() {
		// translators: %s: Integration Setting Page link
		return sprintf( __( 'To use reCAPTCHA, you need to add the API keys in the <a href="%s" target="_blank">Integrations Settings</a> and complete the setup process.', 'powerpack' ), \PowerpackElements\Classes\PP_Admin_Settings::get_form_action( '&tab=integration' ) );
	}

	/**
	 * Get contact forms of supported forms plugins
	 *
	 * @since 1.4.14.1
	 * @access public
	 */
	public static function get_contact_forms( $plugin = '' ) {
		$options       = array();
		$contact_forms = array();

		// Caldera Forms
		if ( 'Caldera_Forms' == $plugin && class_exists( 'Caldera_Forms' ) ) {
			$caldera_forms = \Caldera_Forms_Forms::get_forms( true, true );

			if ( ! empty( $caldera_forms ) && ! is_wp_error( $caldera_forms ) ) {
				foreach ( $caldera_forms as $form ) {
					$contact_forms[ $form['ID'] ] = $form['name'];
				}
			}
		}

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
			global $wpdb;

			$fluent_forms = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}fluentform_forms" );

			if ( $fluent_forms ) {
				foreach ( $fluent_forms as $form ) {
					$contact_forms[ $form->id ] = $form->title;
				}
			}
		}

		// Formidable Forms
		if ( 'Formidable_Forms' == $plugin && class_exists( 'FrmForm' ) ) {
			$formidable_forms = \FrmForm::get_published_forms( array(), 999, 'exclude' );
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
			$options[0] = esc_html__( 'Select a Contact Form', 'powerpack' );
			foreach ( $contact_forms as $form_id => $form_title ) {
				$options[ $form_id ] = $form_title;
			}
		}

		if ( empty( $options ) ) {
			$options[0] = esc_html__( 'No contact forms found!', 'powerpack' );
		}

		return $options;
	}

	/**
	 * Build the URL of Facebook SDK.
	 *
	 * @since x.x.x
	 * @return string
	 */
	public static function get_fb_sdk_url( $app_id = '' ) {
		$fb_app_id = \PowerpackElements\Classes\PP_Admin_Settings::get_option( 'pp_fb_app_id' );

		$app_id = empty( $app_id ) ? $fb_app_id : $app_id;

		if ( $app_id && ! empty( $app_id ) ) {
			return sprintf( 'https://connect.facebook.net/%s/sdk.js#xfbml=1&version=v2.12&appId=%s', get_locale(), $app_id );
		}

		return sprintf( 'https://connect.facebook.net/%s/sdk.js#xfbml=1&version=v2.12', get_locale() );
	}

	/**
	 * Get Facebook app settings url
	 *
	 * @since x.x.x
	 * @return string
	 */
	public static function get_fb_app_settings_url() {
		$app_id = \PowerpackElements\Classes\PP_Admin_Settings::get_option( 'pp_fb_app_id' );

		if ( $app_id ) {
			return sprintf( 'https://developers.facebook.com/apps/%d/settings/', $app_id );
		} else {
			return 'https://developers.facebook.com/apps/';
		}
	}

	/**
	 * Returns user agent.
	 *
	 * @since x.x.x
	 * @return string
	 */
	public static function get_user_agent() {
		$user_agent = $_SERVER['HTTP_USER_AGENT'];

		if ( false !== stripos( $user_agent, 'Chrome' ) ) {
			return 'chrome';
		} elseif ( false !== stripos( $user_agent, 'Safari' ) ) {
			return 'safari';
		} elseif ( false !== stripos( $user_agent, 'Firefox' ) ) {
			return 'firefox';
		} elseif ( false !== stripos( $user_agent, 'MSIE' ) ) {
			return 'ie';
		} elseif ( false !== stripos( $user_agent, 'Trident/7.0; rv:11.0' ) ) {
			return 'ie';
		}

		return;
	}

	/**
	 * Get Client IP address
	 *
	 * @since x.x.x
	 * @return string
	 */
	public static function get_client_ip() {
		$keys = array(
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR',
		);

		foreach ( $keys as $key ) {
			if ( isset( $_SERVER[ $key ] ) && filter_var( $_SERVER[ $key ], FILTER_VALIDATE_IP ) ) {
				return $_SERVER[ $key ];
			}
		}

		// fallback IP address.
		return '127.0.0.1';
	}
}
