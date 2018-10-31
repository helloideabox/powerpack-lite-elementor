<?php
namespace PowerpackElements\Modules\Showcase;

use PowerpackElements\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	/**
	 * Module is active or not.
	 *
	 * @since 1.3.3
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
	 * @since 1.3.3
     *
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'pp-showcase';
	}

    /**
	 * Get Widgets.
	 *
	 * @since 1.3.3
     *
	 * @access public
	 *
	 * @return array Widgets.
	 */
	public function get_widgets() {
		return [
			'Showcase',
		];
	}
    
    /**
	 * Get Image Caption.
	 *
	 * @since 1.3.3
     *
	 * @access public
	 *
	 * @return string image caption.
	 */
    public static function get_image_caption( $id, $caption_type = 'caption' ) {

        $attachment = get_post( $id );

        if ( $caption_type == 'title' ) {
            $attachment_caption = $attachment->post_title;
        }
        elseif ( $caption_type == 'caption' ) {
            $attachment_caption = $attachment->post_excerpt;
        }

        return $attachment_caption;
        
    }
    
    /**
	 * Get Image Filters.
	 *
	 * @since 1.3.3
     *
	 * @access public
	 *
	 * @return array image filters.
	 */
    public static function get_image_filters() {
        
        $pp_image_filters = [
            'normal'            => __( 'Normal', 'power-pack' ),
            'filter-1977'       => __( '1977', 'power-pack' ),
            'filter-aden'       => __( 'Aden', 'power-pack' ),
            'filter-amaro'      => __( 'Amaro', 'power-pack' ),
            'filter-ashby'      => __( 'Ashby', 'power-pack' ),
            'filter-brannan'    => __( 'Brannan', 'power-pack' ),
            'filter-brooklyn'   => __( 'Brooklyn', 'power-pack' ),
            'filter-charmes'    => __( 'Charmes', 'power-pack' ),
            'filter-clarendon'  => __( 'Clarendon', 'power-pack' ),
            'filter-crema'      => __( 'Crema', 'power-pack' ),
            'filter-dogpatch'   => __( 'Dogpatch', 'power-pack' ),
            'filter-earlybird'  => __( 'Earlybird', 'power-pack' ),
            'filter-gingham'    => __( 'Gingham', 'power-pack' ),
            'filter-ginza'      => __( 'Ginza', 'power-pack' ),
            'filter-hefe'       => __( 'Hefe', 'power-pack' ),
            'filter-helena'     => __( 'Helena', 'power-pack' ),
            'filter-hudson'     => __( 'Hudson', 'power-pack' ),
            'filter-inkwell'    => __( 'Inkwell', 'power-pack' ),
            'filter-juno'       => __( 'Juno', 'power-pack' ),
            'filter-kelvin'     => __( 'Kelvin', 'power-pack' ),
            'filter-lark'       => __( 'Lark', 'power-pack' ),
            'filter-lofi'       => __( 'Lofi', 'power-pack' ),
            'filter-ludwig'     => __( 'Ludwig', 'power-pack' ),
            'filter-maven'      => __( 'Maven', 'power-pack' ),
            'filter-mayfair'    => __( 'Mayfair', 'power-pack' ),
            'filter-moon'       => __( 'Moon', 'power-pack' ),
        ];
        
        return $pp_image_filters;
    }
}
