<?php

class WPML_PP_One_Page_Nav extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'nav_dots';
	}

	public function get_fields() {
		return array( 
			'section_title',
			'section_id',
	 );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'section_title':
				return esc_html__( 'One Page Navigation - Section Title', 'power-pack' );
			case 'section_id':
				return esc_html__( 'One Page Navigation - Section Id', 'power-pack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'section_title':
				return 'LINE';
			case 'section_id':
				return 'LINE';
			default:
				return '';
		}
	}

}
