<?php

class WPML_PP_Team_Member_Carousel extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'team_member_details';
	}

	public function get_fields() {
		return array(
			'team_member_name',
			'team_member_position',
			'team_member_description',
			'facebook_url',
			'twitter_url',
        	'google_plus_url',
        	'linkedin_url',
        	'instagram_url',
        	'youtube_url',
        	'pinterest_url',
        	'dribbble_url',
	 );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'team_member_name':
				return esc_html__( 'Team Member Carousel - Name', 'power-pack' );
			case 'team_member_position':
				return esc_html__( 'Team Member Carousel - Team Member Position', 'power-pack' );
			case 'team_member_description':
				return esc_html__( 'Team Member Carousel - Team Member Description', 'power-pack' );
			case 'facebook_url':
				return esc_html__( 'Team Member Carousel - Facebook URL', 'power-pack' );
			case 'twitter_url':
				return esc_html__( 'Team Member Carousel - Twitter URL', 'power-pack' );
			case 'google_plus_url':
				return esc_html__( 'Team Member Carousel - Google_plus URL', 'power-pack' );
			case 'linkedin_url':
				return esc_html__( 'Team Member Carousel - Linkedin URL', 'power-pack' );
			case 'instagram_url':
				return esc_html__( 'Team Member Carousel - Instagram URL', 'power-pack' );
			case 'youtube_url':
				return esc_html__( 'Team Member Carousel - Youtube URL', 'power-pack' );
			case 'pinterest_url':
				return esc_html__( 'Team Member Carousel - Pinterest URL', 'power-pack' );
			case 'dribbble_url':
				return esc_html__( 'Team Member Carousel - Dribbble URL', 'power-pack' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'team_member_name':
				return 'LINE';
			case 'team_member_position':
				return 'LINE';
			case 'team_member_description':
				return 'AREA';
			case 'facebook_url':
				return 'LINK';
			case 'twitter_url':
				return 'LINK';
			case 'google_plus_url':
				return 'LINK';
			case 'linkedin_url':
				return 'LINK';
			case 'instagram_url':
				return 'LINK';
			case 'youtube_url':
				return 'LINK';
			case 'pinterest_url':
				return 'LINK';
			case 'dribbble_url':
				return 'LINK';
			default:
				return '';
		}
	}
}
