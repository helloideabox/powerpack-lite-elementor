<?php
namespace PowerpackElements;

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {	exit; } // Exit if accessed directly

/**
 * Main class plugin
 */
class Powerpackplugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	private $_modules_manager;

	/**
	 * @var array
	 */
	private $_localize_settings = [];

	/**
	 * @return string
	 */
	public function get_version() {
		return POWERPACK_ELEMENTS_VER;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'power-pack' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'power-pack' ), '1.0.0' );
	}

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function _includes() {
		require POWERPACK_ELEMENTS_PATH . 'includes/modules-manager.php';
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$filename = strtolower(
			preg_replace(
				[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
				[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
				$class
			)
		);
		$filename = POWERPACK_ELEMENTS_PATH . $filename . '.php';

		if ( is_readable( $filename ) ) {
			include( $filename );
		}
	}

	public function get_localize_settings() {
		return $this->_localize_settings;
	}

	public function add_localize_settings( $setting_key, $setting_value = null ) {
		if ( is_array( $setting_key ) ) {
			$this->_localize_settings = array_replace_recursive( $this->_localize_settings, $setting_key );

			return;
		}

		if ( ! is_array( $setting_value ) || ! isset( $this->_localize_settings[ $setting_key ] ) || ! is_array( $this->_localize_settings[ $setting_key ] ) ) {
			$this->_localize_settings[ $setting_key ] = $setting_value;

			return;
		}

		$this->_localize_settings[ $setting_key ] = array_replace_recursive( $this->_localize_settings[ $setting_key ], $setting_value );
	}

    /**
	 * Enqueue frontend styles
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 */
	public function enqueue_frontend_styles() {
		wp_enqueue_style(
			'powerpack-frontend',
			POWERPACK_ELEMENTS_URL . 'assets/css/frontend.css',
			[],
			POWERPACK_ELEMENTS_VER
		);
        
		wp_enqueue_style(
			'tablesaw',
			POWERPACK_ELEMENTS_URL . 'assets/lib/tablesaw/tablesaw.css',
			[],
			POWERPACK_ELEMENTS_VER
		);
        
		wp_enqueue_style(
			'tipso',
			POWERPACK_ELEMENTS_URL . 'assets/lib/tipso/tipso.css',
			[],
			POWERPACK_ELEMENTS_VER
		);
        
		wp_enqueue_style(
			'odometer',
			POWERPACK_ELEMENTS_URL . 'assets/lib/odometer/odometer-theme-default.css',
			[],
			POWERPACK_ELEMENTS_VER
		);
        
		wp_enqueue_style(
			'twentytwenty',
			POWERPACK_ELEMENTS_URL . 'assets/lib/twentytwenty/twentytwenty.css',
			[],
			POWERPACK_ELEMENTS_VER
		);
        
        if ( class_exists( 'GFCommon' ) ) {
            foreach( pp_get_gravity_forms() as $form_id => $form_name ){
                if ( $form_id != '0' ) {
                    gravity_form_enqueue_scripts( $form_id );
                }
            };
        }

        if ( function_exists( 'wpforms' ) ) {
            wpforms()->frontend->assets_css();
        }
	}

    /**
	 * Enqueue frontend scripts
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 */
	public function enqueue_frontend_scripts() {
        $settings = \PowerpackElements\Classes\PP_Admin_Settings::get_settings();
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script(
			'instafeed',
			POWERPACK_ELEMENTS_URL . 'assets/lib/instafeed/instafeed' . $suffix . '.js',
			[
				'jquery',
			],
			'1.4.1',
			true
		);

		wp_register_script(
			'tipso',
			POWERPACK_ELEMENTS_URL . 'assets/lib/tipso/tipso.js',
			[
				'jquery',
			],
			'1.0.8',
			true
		);

		wp_register_script(
			'twentytwenty',
			POWERPACK_ELEMENTS_URL . 'assets/lib/twentytwenty/jquery.twentytwenty.js',
			[
				'jquery',
			],
			'2.0.0',
			true
		);

		wp_register_script(
			'jquery-event-move',
			POWERPACK_ELEMENTS_URL . 'assets/js/jquery.event.move.js',
			[
				'jquery',
			],
			'2.0.0',
			true
		);

		wp_register_script(
			'magnific-popup',
			POWERPACK_ELEMENTS_URL . 'assets/lib/magnific-popup/jquery.magnific-popup.min.js',
			[
				'jquery',
			],
			'2.2.1',
			true
		);

		wp_register_script(
			'jquery-cookie',
			POWERPACK_ELEMENTS_URL . 'assets/js/jquery.cookie.js',
			[
				'jquery',
			],
			'1.4.1',
			true
		);

		wp_register_script(
			'waypoints',
			POWERPACK_ELEMENTS_URL . 'assets/lib/waypoints/waypoints.min.js',
			[
				'jquery',
			],
			'4.0.1',
			true
		);

		wp_register_script(
			'odometer',
			POWERPACK_ELEMENTS_URL . 'assets/lib/odometer/odometer.min.js',
			[
				'jquery',
			],
			'0.4.8',
			true
		);

		wp_register_script(
			'jquery-powerpack-dot-nav',
			POWERPACK_ELEMENTS_URL . 'assets/js/one-page-nav.js',
			[
				'jquery',
			],
			'1.0.0',
			true
		);
        
        if ( isset( $settings['google_map_api'] ) && ! empty( $settings['google_map_api'] ) ) {
            wp_register_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . $settings['google_map_api'], '', rand() );
        } else {
            wp_register_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js', '', rand() );
        }

		wp_register_script(
			'pp-google-maps',
			POWERPACK_ELEMENTS_URL . 'assets/js/pp-google-maps.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_VER,
			true
		);

		wp_register_script(
			'pp-jquery-plugin',
			POWERPACK_ELEMENTS_URL . 'assets/js/jquery.plugin.js',
			[
				'jquery',
			],
			'1.0.0',
			true
		);

		wp_register_script(
			'jquery-countdown',
			POWERPACK_ELEMENTS_URL . 'assets/lib/countdown/jquery.countdown.js',
			[
				'jquery',
			],
			'2.0.2',
			true
		);

		wp_register_script(
			'pp-frontend-countdown',
			POWERPACK_ELEMENTS_URL . 'assets/js/frontend-countdown.js',
			[
				'jquery',
			],
			'1.0.0',
			true
		);

		wp_register_script(
			'jquery-smartmenu',
			POWERPACK_ELEMENTS_URL . 'assets/lib/smartmenu/jquery-smartmenu.js',
			[
				'jquery',
			],
			'1.0.1',
			true
		);

		wp_register_script(
			'pp-advanced-menu',
			POWERPACK_ELEMENTS_URL . 'assets/js/frontend-advanced-menu.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_VER,
			true
		);

		wp_register_script(
			'pp-timeline',
			POWERPACK_ELEMENTS_URL . 'assets/js/frontend-timeline.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_VER,
			true
		);

		wp_register_script(
			'scotch-panels',
			POWERPACK_ELEMENTS_URL . 'assets/lib/scotchPanels.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_VER,
			true
		);

		wp_register_script(
			'tablesaw',
			POWERPACK_ELEMENTS_URL . 'assets/lib/tablesaw/tablesaw.jquery.js',
			[
				'jquery',
			],
			'3.0.3',
			true
		);

		wp_register_script(
			'tablesaw-init',
			POWERPACK_ELEMENTS_URL . 'assets/lib/tablesaw/tablesaw-init.js',
			[
				'jquery',
			],
			'3.0.3',
			true
		);

		wp_register_script(
			'isotope',
			POWERPACK_ELEMENTS_URL . 'assets/lib/isotope/isotope.pkgd' . $suffix . '.js',
			[
				'jquery',
			],
			'0.5.3',
			true
		);

		wp_register_script(
			'tilt',
			POWERPACK_ELEMENTS_URL . 'assets/lib/tilt/tilt.jquery' . $suffix . '.js',
			[
				'jquery',
			],
			'1.1.19',
			true
		);

		wp_register_script(
			'jquery-resize',
			POWERPACK_ELEMENTS_URL . 'assets/lib/jquery-resize/jquery.resize' . $suffix . '.js',
			[
				'jquery',
			],
			'0.5.3',
			true
		);

		wp_register_script(
			'pp-offcanvas-content',
			POWERPACK_ELEMENTS_URL . 'assets/js/frontend-offcanvas-content.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_VER,
			true
		);

		wp_register_script(
			'pp-tooltip',
			POWERPACK_ELEMENTS_URL . 'assets/js/tooltip.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_VER,
			true
		);

		wp_register_script(
			'powerpack-frontend',
			POWERPACK_ELEMENTS_URL . 'assets/js/frontend.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_VER,
			true
		);
	}

    /**
	 * Enqueue editor styles
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 */
	public function enqueue_editor_styles() {
		wp_enqueue_style(
			'powerpack-editor',
			POWERPACK_ELEMENTS_URL . 'assets/css/editor.css',
			[],
			POWERPACK_ELEMENTS_VER
		);
        
		wp_enqueue_style(
			'powerpack-icons',
			POWERPACK_ELEMENTS_URL . 'assets/lib/ppicons/css/powerpack-icons.css',
			[],
			POWERPACK_ELEMENTS_VER
		);
	}

    /**
	 * Enqueue editor scripts
	 *
	 * @since 1.3.3
	 *
	 * @access public
	 */
	public function enqueue_editor_scripts() {
		wp_enqueue_script(
			'powerpack-editor',
			POWERPACK_ELEMENTS_URL . 'assets/js/editor.js',
			[
				'jquery',
			],
			POWERPACK_ELEMENTS_VER,
			true
		);
        
		wp_enqueue_script(
			'magnific-popup',
			POWERPACK_ELEMENTS_URL . 'assets/lib/magnific-popup/jquery.magnific-popup.min.js',
			[
				'jquery',
			],
			'2.2.1',
			true
		);
	}

	public function enqueue_panel_scripts() {}

	public function enqueue_panel_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	public function elementor_init() {
		$this->_modules_manager = new Modules_Manager();

		// Add element category in panel
		\Elementor\Plugin::instance()->elements_manager->add_category(
			'power-pack', // This is the name of your addon's category and will be used to group your widgets/elements in the Edit sidebar pane!
			[
				'title' => __( 'Powerpack Elements', 'power-pack' ), // The title of your modules category - keep it simple and short!
				'icon' => 'font',
			],
			1
		);
	}

	protected function add_actions() {
		add_action( 'elementor/init', [ $this, 'elementor_init' ] );

		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );

		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_frontend_styles' ] );
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->_includes();
		$this->add_actions();
	}
	
}

if ( ! defined( 'POWERPACK_ELEMENTS_TESTS' ) ) {
	// In tests we run the instance manually.
	Powerpackplugin::instance();
}
