<?php
namespace PowerpackElementsLite;

use PowerpackElementsLite\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Modules_Manager {
	/**
	 * @var Module_Base[]
	 */
	private $modules = [];

	public function register_modules() {
		$modules = [
			'advanced-accordion',
			'business-hours',
			'buttons',
			'charts',
			'contact-form-seven',
			'content-reveal',
			'content-ticker',
			'flipbox',
			'formidable-forms',
			'fluent-forms',
			'gravity-forms',
			'ninja-forms',
			'wpforms',
			'counter',
			'divider',
			'headings',
			'hotspots',
			'icon-list',
			'image-accordion',
			'image-comparison',
			'info-box',
			'info-list',
			'info-table',
			'instafeed',
			'interactive-circle',
			'link-effects',
			'logos',
			'marquee',
			'posts',
			'pricing',
			'progress-bar',
			'promo-box',
			'random-image',
			'scroll-image',
			'team-member',
			'twitter',
			'query-control',
			'display-conditions',
		];

		ksort($modules);

		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );

			$class_name = str_replace( ' ', '', ucwords( $class_name ) );

			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			/** @var Module_Base $class_name */
			if ( ! $class_name::is_active() ) {
				continue;
			}

			if ( ! $this->is_module_needed( $module_name, $class_name ) ) {
				continue;
			}

			$this->modules[ $module_name ] = $class_name::instance();
		}
	}

	/**
	 * Whether a module has anything left to do on this request.
	 *
	 * A module that exists only to register widgets has nothing to do once every
	 * one of its widgets has been switched off in the admin — booting it would
	 * just add hooks that can never fire, on every page load. Skipping those
	 * keeps the number of live modules proportional to what the site uses.
	 *
	 * Modules that declare no widgets at all (query controls, display
	 * conditions) always load, since their work is not widget bound.
	 *
	 * @since 3.0.0
	 * @param string $module_name Module directory name.
	 * @param string $class_name  Fully qualified module class name.
	 * @return bool
	 */
	private function is_module_needed( $module_name, $class_name ) {
		$widgets = $this->get_module_widgets( $class_name );

		if ( empty( $widgets ) ) {
			return true;
		}

		foreach ( $widgets as $widget ) {
			$widget_name = 'pp-' . str_replace( '_', '-', strtolower( $widget ) );

			if ( Module_Base::is_widget_active( $widget_name ) ) {
				return true;
			}
		}

		/**
		 * Filters whether a module is booted even though all of its widgets are
		 * switched off. Useful for modules whose hooks are relied on elsewhere.
		 *
		 * @since 3.0.0
		 * @param bool   $load        Default false.
		 * @param string $module_name Module directory name.
		 */
		return (bool) apply_filters( 'pp_load_module_without_active_widgets', false, $module_name );
	}

	/**
	 * Read a module's widget list without booting the module.
	 *
	 * get_widgets() is an instance method, but no module implementation touches
	 * instance state, so the list can be read from an object built without its
	 * constructor. That keeps this check from registering the very hooks it is
	 * trying to avoid.
	 *
	 * @since 3.0.0
	 * @param string $class_name Fully qualified module class name.
	 * @return array Widget class names.
	 */
	private function get_module_widgets( $class_name ) {
		try {
			$module = ( new \ReflectionClass( $class_name ) )->newInstanceWithoutConstructor();

			return (array) $module->get_widgets();
		} catch ( \Exception $e ) {
			// Fall back to loading the module rather than silently dropping it.
			return [];
		}
	}

	/**
	 * @param string $module_name
	 *
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}

	private function require_files() {
		require( POWERPACK_ELEMENTS_LITE_PATH . 'base/module-base.php' );
	}

	public function __construct() {
		$this->require_files();
		$this->register_modules();
	}
}