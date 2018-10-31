<?php

class WPML_PP_Google_Maps extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'pp_map_addresses';
	}

	public function get_fields() {
		return array( 
			'map_title',
			'map_description',
	 );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'map_title':
				return esc_html__( 'Google Maps - Title', 'power-pack' );
			case 'map_description':
				return esc_html__( 'Google Maps - Description', 'power-pack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'map_title':
				return 'LINE';
			case 'map_description':
				return 'AREA';
			default:
				return '';
		}
	}

}
