<?php

class WPML_PP_Info_Box_Carousel extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'pp_info_boxes';
	}

	public function get_fields() {
		return array( 
			'title',
			'subtitle',
			'description',
			'icon_text',
			'link' => array( 'url' ),
			'button_text',
	 );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'title':
				return esc_html__( 'Info Grid & Carousel - Title', 'powerpack-lite-for-elementor' );
			case 'subtitle':
				return esc_html__( 'Info Grid & Carousel - Subtitle', 'powerpack-lite-for-elementor' );
			case 'description':
				return esc_html__( 'Info Grid & Carousel - Description', 'powerpack-lite-for-elementor' );
			case 'icon_text':
				return esc_html__( 'Info Grid & Carousel - Icon Text', 'powerpack-lite-for-elementor' );
			case 'url':
				return esc_html__( 'Info Grid & Carousel - Link', 'powerpack-lite-for-elementor' );
			case 'button_text':
				return esc_html__( 'Info Grid & Carousel - Button Text', 'powerpack-lite-for-elementor' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'title':
				return 'LINE';
			case 'subtitle':
				return 'LINE';
			case 'description':
				return 'AREA';
			case 'icon_text':
				return 'LINE';
			case 'url':
				return 'LINK';
			case 'button_text':
				return 'LINE';
			default:
				return '';
		}
	}

}
