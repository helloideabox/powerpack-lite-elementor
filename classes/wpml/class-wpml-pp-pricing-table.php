<?php

class WPML_PP_Pricing_Table extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'table_features';
	}

	public function get_fields() {
		return array( 
			'feature_text',
			'tooltip_content',
	 );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'feature_text':
				return esc_html__( 'Price Table - Feature Text', 'powerpack' );
			case 'tooltip_content':
				return esc_html__( 'Price Table - Tooltip Content', 'powerpack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'feature_text':
				return 'LINE';
			case 'tooltip_content':
				return 'VISUAL';
			default:
				return '';
		}
	}

}
