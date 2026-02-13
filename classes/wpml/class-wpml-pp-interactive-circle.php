<?php
class WPML_PP_Interactive_Circle extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'tabs';
	}

	public function get_fields() {
		return array( 
			'tab_label',
			'item_title',
			'item_content',
		);
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'tab_label':
				return esc_html__( 'Interactive Circle - Tab Label', 'powerpack-lite-for-elementor' );
			case 'item_title':
				return esc_html__( 'Interactive Circle - Item Title', 'powerpack-lite-for-elementor' );
			case 'item_content':
				return esc_html__( 'Interactive Circle - Item Content', 'powerpack-lite-for-elementor' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'tab_label':
				return 'LINE';
			case 'item_title':
				return 'LINE';
			case 'item_content':
				return 'VISUAL';
			default:
				return '';
		}
	}

}
