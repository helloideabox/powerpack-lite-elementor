<?php

class WPML_PP_Icon_List extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'list_items';
	}

	public function get_fields() {
		return array( 
			'text',
			'icon_text',
			'link' => array( 'url' ),
	 );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'text':
				return esc_html__( 'Icon List - Text', 'powerpack-lite-for-elementor' );
			case 'icon_text':
				return esc_html__( 'Icon List - Icon Text', 'powerpack-lite-for-elementor' );
			case 'url':
				return esc_html__( 'Icon List - Link', 'powerpack-lite-for-elementor' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'text':
				return 'LINE';
			case 'icon_text':
				return 'LINE';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}

}
