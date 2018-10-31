<?php

class WPML_PP_Showcase extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'items';
	}

	public function get_fields() {
		return array( 
			'title',
			'description',
		);
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'title':
				return esc_html__( 'Showcase - Title', 'power-pack' );
			case 'description':
				return esc_html__( 'Showcase - Description', 'power-pack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'title':
				return 'LINE';
			case 'description':
				return 'LINE';
			default:
				return '';
		}
	}

}
