<?php
namespace PowerpackElementsLite\Modules\RandomImage;

use PowerpackElementsLite\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_action( 'elementor/frontend/after_register_styles', [ $this, 'register_styles' ] );
	}

	/**
	 * Module is active or not.
	 *
	 * @since 2.3.0
	 *
	 * @access public
	 *
	 * @return bool true|false.
	 */
	public static function is_active() {
		return true;
	}

	/**
	 * Get Module Name.
	 *
	 * @since 2.3.0
	 *
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'pp-random-image';
	}

	/**
	 * Get Widgets.
	 *
	 * @since 2.3.0
	 *
	 * @access public
	 *
	 * @return array Widgets.
	 */
	public function get_widgets() {
		return [
			'Random_Image',
		];
	}

	/**
	 * Get Image Caption.
	 *
	 * @since 2.3.0
	 *
	 * @access public
	 *
	 * @return string image caption.
	 */
	public static function get_image_caption( $id, $caption_type = 'caption' ) {

		$attachment = get_post( $id );

		$attachment_caption = '';

		if ( 'title' === $caption_type ) {
			$attachment_caption = $attachment->post_title;
		} elseif ( 'caption' === $caption_type ) {
			$attachment_caption = wp_get_attachment_caption( $id );
		} elseif ( 'description' === $caption_type ) {
			$attachment_caption = $attachment->post_content;
		}

		return $attachment_caption;

	}

	/**
	 * Get Image Filters.
	 *
	 * @since 2.3.0
	 *
	 * @access public
	 *
	 * @return array image filters.
	 */
	public static function get_image_filters() {

		$pp_image_filters = [
			'normal'            => esc_html__( 'Normal', 'powerpack' ),
			'filter-1977'       => esc_html__( '1977', 'powerpack' ),
			'filter-aden'       => esc_html__( 'Aden', 'powerpack' ),
			'filter-amaro'      => esc_html__( 'Amaro', 'powerpack' ),
			'filter-ashby'      => esc_html__( 'Ashby', 'powerpack' ),
			'filter-brannan'    => esc_html__( 'Brannan', 'powerpack' ),
			'filter-brooklyn'   => esc_html__( 'Brooklyn', 'powerpack' ),
			'filter-charmes'    => esc_html__( 'Charmes', 'powerpack' ),
			'filter-clarendon'  => esc_html__( 'Clarendon', 'powerpack' ),
			'filter-crema'      => esc_html__( 'Crema', 'powerpack' ),
			'filter-dogpatch'   => esc_html__( 'Dogpatch', 'powerpack' ),
			'filter-earlybird'  => esc_html__( 'Earlybird', 'powerpack' ),
			'filter-gingham'    => esc_html__( 'Gingham', 'powerpack' ),
			'filter-ginza'      => esc_html__( 'Ginza', 'powerpack' ),
			'filter-hefe'       => esc_html__( 'Hefe', 'powerpack' ),
			'filter-helena'     => esc_html__( 'Helena', 'powerpack' ),
			'filter-hudson'     => esc_html__( 'Hudson', 'powerpack' ),
			'filter-inkwell'    => esc_html__( 'Inkwell', 'powerpack' ),
			'filter-juno'       => esc_html__( 'Juno', 'powerpack' ),
			'filter-kelvin'     => esc_html__( 'Kelvin', 'powerpack' ),
			'filter-lark'       => esc_html__( 'Lark', 'powerpack' ),
			'filter-lofi'       => esc_html__( 'Lofi', 'powerpack' ),
			'filter-ludwig'     => esc_html__( 'Ludwig', 'powerpack' ),
			'filter-maven'      => esc_html__( 'Maven', 'powerpack' ),
			'filter-mayfair'    => esc_html__( 'Mayfair', 'powerpack' ),
			'filter-moon'       => esc_html__( 'Moon', 'powerpack' ),
		];

		return $pp_image_filters;
	}

	/**
	 * Register styles.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'widget-pp-random-image',
			$this->get_css_assets_url( 'widget-random-image', null, true, true ),
			[],
			POWERPACK_ELEMENTS_LITE_VER
		);
	}
}
