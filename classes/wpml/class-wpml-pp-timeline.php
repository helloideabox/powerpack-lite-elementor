<?php

class WPML_PP_Timeline extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'items';
	}

	public function get_fields() {
		return array( 
			'timeline_item_date',
			'timeline_item_title',
			'timeline_item_content',
			'timeline_item_link',
		);
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'timeline_item_date':
				return esc_html__( 'Timeline - Item Date', 'power-pack' );
			case 'timeline_item_title':
				return esc_html__( 'Timeline - Item Title', 'power-pack' );
			case 'timeline_item_content':
				return esc_html__( 'Timeline - Item Content', 'power-pack' );
			case 'timeline_item_link':
				return esc_html__( 'Timeline - Item Link', 'power-pack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'timeline_item_date':
				return 'LINE';
			case 'timeline_item_title':
				return 'LINE';
			case 'timeline_item_content':
				return 'LINE';
			case 'timeline_item_link':
				return 'LINE';
			default:
				return '';
		}
	}

}
