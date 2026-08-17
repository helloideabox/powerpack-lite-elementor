<?php

/**
 * Translation support for the Event Calendar widget's manually added events.
 *
 * @since 3.0.1
 */
class WPML_PP_Event_Calendar_Events extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'events';
	}

	public function get_fields() {
		return [
			'event_title',
			'guest',
			'location',
			'description',
			'event_url' => [ 'url' ],
		];
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'event_title':
				return esc_html__( 'Event Calendar - Event Title', 'powerpack-lite-for-elementor' );
			case 'guest':
				return esc_html__( 'Event Calendar - Event Guest', 'powerpack-lite-for-elementor' );
			case 'location':
				return esc_html__( 'Event Calendar - Event Location', 'powerpack-lite-for-elementor' );
			case 'description':
				return esc_html__( 'Event Calendar - Event Description', 'powerpack-lite-for-elementor' );
			case 'url':
				return esc_html__( 'Event Calendar - Event URL', 'powerpack-lite-for-elementor' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'event_title':
				return 'LINE';
			case 'guest':
				return 'LINE';
			case 'location':
				return 'LINE';
			case 'description':
				return 'VISUAL';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}

}

/**
 * Translation support for the Event Calendar popup's header field labels.
 *
 * @since 3.0.1
 */
class WPML_PP_Event_Calendar_Popup_Fields extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'popup_header_fields';
	}

	public function get_fields() {
		return [
			'field_title',
			'field_allday_text',
		];
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'field_title':
				return esc_html__( 'Event Calendar - Popup Field Title', 'powerpack-lite-for-elementor' );
			case 'field_allday_text':
				return esc_html__( 'Event Calendar - Popup Field All Day Text', 'powerpack-lite-for-elementor' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'field_title':
				return 'LINE';
			case 'field_allday_text':
				return 'LINE';
			default:
				return '';
		}
	}

}
