<?php

class WPML_PP_Recipe extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'recipe_ingredients';
	}

	public function get_fields() {
		return array( 
			'recipe_ingredient',
			'recipe_instruction',
		);
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'recipe_ingredient':
				return esc_html__( 'Recipe - Ingredient Text', 'power-pack' );
			case 'recipe_instruction':
				return esc_html__( 'Recipe - Instruction Text', 'power-pack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'recipe_ingredient':
				return 'LINE';
			case 'recipe_instruction':
				return 'LINE';
			default:
				return '';
		}
	}

}
