<?php

class WPML_PP_Business_Hours extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'business_hours';
	}

	public function get_fields() {
		return array( 'day', 'closed_text' );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'day':
				return esc_html__( 'Business Hours - Day', 'power-pack' );

			case 'closed_text':
				return esc_html__( 'Business Hours - Closed Text', 'power-pack' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'day':
				return 'LINE';

			case 'closed_text':
				return 'LINE';

			default:
				return '';
		}
	}

}
