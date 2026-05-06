<?php

class WPML_PP_Marquee extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'items';
	}

	public function get_fields() {
		return [
			'marquee_text',
			'item_link' => [ 'url' ],
		];
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'marquee_text':
				return esc_html__( 'Marquee - Text', 'powerpack-lite-for-elementor' );
			case 'url':
				return esc_html__( 'Marquee - Item Link', 'powerpack-lite-for-elementor' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'marquee_text':
				return 'AREA';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}

}
