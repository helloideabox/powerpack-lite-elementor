<?php

class WPML_PP_Image_Hotspots extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'hot_spots';
	}

	public function get_fields() {
		return array( 
			'hotspot_admin_label',
			'hotspot_text',
			'hotspot_link' => array( 'url' ),
			'tooltip_content',
	 );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'hotspot_admin_label':
				return esc_html__( 'Image Hotspot - Admin Label', 'power-pack' );
			case 'hotspot_text':
				return esc_html__( 'Image Hotspot - Text', 'power-pack' );
			case 'url':
				return esc_html__( 'Image Hotspot - Link', 'power-pack' );
			case 'tooltip_content':
				return esc_html__( 'Image Hotspot - Tooltip Content', 'power-pack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'hotspot_admin_label':
				return 'LINE';
			case 'hotspot_text':
				return 'LINE';
			case 'url':
				return 'LINE';
			case 'tooltip_content':
				return 'VISUAL';
			default:
				return '';
		}
	}

}
