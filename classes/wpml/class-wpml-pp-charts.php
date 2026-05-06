<?php

class WPML_PP_Charts_Dataset_Colors extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'dataset_colors';
	}

	public function get_fields() {
		return [
			'dataset_label',
		];
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'dataset_label':
				return esc_html__( 'Charts - Dataset Label', 'powerpack-lite-for-elementor' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'dataset_label':
				return 'LINE';
			default:
				return '';
		}
	}

}

class WPML_PP_Charts_Dataset extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'chart_dataset';
	}

	public function get_fields() {
		return [
			'dataset_label',
		];
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'dataset_label':
				return esc_html__( 'Charts - Dataset Label', 'powerpack-lite-for-elementor' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'dataset_label':
				return 'LINE';
			default:
				return '';
		}
	}

}
