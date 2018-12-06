<?php
namespace PowerpackElementsLite\Classes;

use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handle AJAX requests.
 */
class PP_Ajax_Handler {
	public function __construct() {
		// Gallery module - load more componenet
		add_action( 'wp', [ $this, 'gallery_get_images' ] );
	}

	public function gallery_get_images() {
		if ( ! isset( $_POST['pp_action'] ) || 'pp_gallery_get_images' != $_POST['pp_action'] ) {
			return;
		}

		if ( ! isset( $_POST['settings'] ) || empty( $_POST['settings'] ) ) {
			return;
		}

		// Tell WordPress this is an AJAX request.
		if ( ! defined( 'DOING_AJAX' ) ) {
			define( 'DOING_AJAX', true );
		}

		$settings 	= $_POST['settings'];
		$gallery_id = $settings['widget_id'];
		$post_id 	= $settings['post_id'];

		$meta = Plugin::$instance->db->get_plain_editor( $post_id );

		$gallery = $this->find_element_recursive( $meta, $gallery_id );

		if ( ! $gallery ) {
			wp_send_json_error();
		}

		// restore default values
		$widget = Plugin::$instance->elements_manager->create_element_instance( $gallery );
		$photos = $widget->ajax_get_images();
		//$gallery['settings'] = $widget->get_active_settings();
		//$gallery['settings']['id'] = $gallery_id;

		wp_send_json_success( array( 'items' => $photos ) );
	}

	public function find_element_recursive( $elements, $widget_id ) {
		foreach ( $elements as $element ) {
			if ( $widget_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = $this->find_element_recursive( $element['elements'], $widget_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}
}

$pp_ajax_handler = new PP_Ajax_Handler();