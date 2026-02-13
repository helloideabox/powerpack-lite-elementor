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
			'link' => array( 'url' ),
			'facebook_url',
			'twitter_url',
        	'instagram_url',
        	'linkedin_url',
        	'youtube_url',
        	'pinterest_url',
        	'dribbble_url',
        	'flickr_url',
        	'tumblr_url',
        	'tiktok_url',
        	'github_url',
        	'vimeo_url',
        	'xing_url',
        	'email',
        	'phone',
	 );
	}

	protected function get_title( $field ) {
		switch( $field ) {
			case 'team_member_name':
				return esc_html__( 'Team Member Carousel - Name', 'powerpack-lite-for-elementor' );
			case 'team_member_position':
				return esc_html__( 'Team Member Carousel - Team Member Position', 'powerpack-lite-for-elementor' );
			case 'team_member_description':
				return esc_html__( 'Team Member Carousel - Team Member Description', 'powerpack-lite-for-elementor' );
			case 'url':
				return esc_html__( 'Team Member Carousel - Team Member Link', 'powerpack-lite-for-elementor' );
			case 'facebook_url':
				return esc_html__( 'Team Member Carousel - Facebook URL', 'powerpack-lite-for-elementor' );
			case 'twitter_url':
				return esc_html__( 'Team Member Carousel - Twitter URL', 'powerpack-lite-for-elementor' );
			case 'instagram_url':
				return esc_html__( 'Team Member Carousel - Instagram URL', 'powerpack-lite-for-elementor' );
			case 'linkedin_url':
				return esc_html__( 'Team Member Carousel - Linkedin URL', 'powerpack-lite-for-elementor' );
			case 'youtube_url':
				return esc_html__( 'Team Member Carousel - Youtube URL', 'powerpack-lite-for-elementor' );
			case 'pinterest_url':
				return esc_html__( 'Team Member Carousel - Pinterest URL', 'powerpack-lite-for-elementor' );
			case 'dribbble_url':
				return esc_html__( 'Team Member Carousel - Dribbble URL', 'powerpack-lite-for-elementor' );
			case 'flickr_url':
				return esc_html__( 'Team Member Carousel - Flickr URL', 'powerpack-lite-for-elementor' );
			case 'tumblr_url':
				return esc_html__( 'Team Member Carousel - Tumblr URL', 'powerpack-lite-for-elementor' );
			case 'tiktok_url':
				return esc_html__( 'Team Member Carousel - Tiktok URL', 'powerpack-lite-for-elementor' );
			case 'github_url':
				return esc_html__( 'Team Member Carousel - Github URL', 'powerpack-lite-for-elementor' );
			case 'vimeo_url':
				return esc_html__( 'Team Member Carousel - Vimeo URL', 'powerpack-lite-for-elementor' );
			case 'xing_url':
				return esc_html__( 'Team Member Carousel - Xing URL', 'powerpack-lite-for-elementor' );
			case 'email':
				return esc_html__( 'Team Member Carousel - Email ID', 'powerpack-lite-for-elementor' );
			case 'phone':
				return esc_html__( 'Team Member Carousel - Contact Number', 'powerpack-lite-for-elementor' );
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
			case 'url':
				return 'LINK';
			case 'facebook_url':
				return 'LINE';
			case 'twitter_url':
				return 'LINE';
				return 'LINE';
			case 'instagram_url':
				return 'LINE';
			case 'linkedin_url':
				return 'LINE';
			case 'youtube_url':
				return 'LINE';
			case 'pinterest_url':
				return 'LINE';
			case 'dribbble_url':
				return 'LINE';
			case 'flickr_url':
				return 'LINE';
			case 'tumblr_url':
				return 'LINE';
			case 'tiktok_url':
				return 'LINE';
			case 'github_url':
				return 'LINE';
			case 'vimeo_url':
				return 'LINE';
			case 'xing_url':
				return 'LINE';
			case 'email':
				return 'LINE';
			case 'phone':
				return 'LINE';
			default:
				return '';
		}
	}
}
