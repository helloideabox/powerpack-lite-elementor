<?php
class WPML_PP_Progress_Bar extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'labels';
	}

	public function get_fields() {
		return array( 
			'text',
		);
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'text':
				return esc_html__( 'Progress Bar - Label (Multiple)', 'powerpack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'text':
				return 'LINE';
			default:
				return '';
		}
	}

}
