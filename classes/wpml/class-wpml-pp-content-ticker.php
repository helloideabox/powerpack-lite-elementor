<?php

class WPML_PP_Content_Ticker extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'items';
	}

	public function get_fields() {
		return array( 
			'ticker_title'
		);
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'ticker_title':
				return esc_html__( 'Content Ticker - Item Title', 'powerpack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'ticker_title':
				return 'LINE';
			default:
				return '';
		}
	}

}
