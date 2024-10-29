<?php
namespace PowerpackElementsLite\Classes;

class PP_Elements_WPML {
    public function __construct() {
		add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'translate_fields' ) );
	}

	public function translate_fields ( $widgets ) {
		$widgets['pp-advanced-accordion']   = [
			'conditions'        => [ 'widgetType' => 'pp-advanced-accordion' ],
			'fields'            => [],
			'integration-class' => 'WPML_PP_Advanced_Accordion',
		];
		$widgets[ 'pp-business-hours' ]       = [
			'conditions'        => [ 'widgetType' => 'pp-business-hours' ],
			'fields'            => [],
			'integration-class' => [
				'WPML_PP_Business_Hours',
				'WPML_PP_Business_Hours_Custom',
			],
		];
		$widgets['pp-buttons']              = [
			'conditions'        => [ 'widgetType' => 'pp-buttons' ],
			'fields'            => [],
			'integration-class' => 'WPML_PP_Buttons',
		];
		$widgets[ 'pp-contact-form-7' ]       = [
			'conditions' => [ 'widgetType' => 'pp-contact-form-7' ],
			'fields'     => [
				[
					'field'       => 'form_title_text',
					'type'        => esc_html__( 'Contact Form 7 - Title', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'form_description_text',
					'type'        => esc_html__( 'Contact Form 7 - Description', 'powerpack' ),
					'editor_type' => 'AREA'
				],
			],
		];
		$widgets['pp-content-ticker']       = [
			'conditions'        => [ 'widgetType' => 'pp-content-ticker' ],
			'fields'            => [
				[
					'field'       => 'heading',
					'type'        => esc_html__( 'Content Ticker - Heading Text', 'powerpack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => 'WPML_PP_Content_Ticker',
		];
		$widgets['pp-content-reveal']       = [
			'conditions' => [ 'widgetType' => 'pp-content-reveal' ],
			'fields'     => [
				[
					'field'       => 'content',
					'type'        => esc_html__( 'Content Reveal - Content Type = Content', 'powerpack' ),
					'editor_type' => 'VISUAL',
				],
				[
					'field'       => 'button_text_closed',
					'type'        => esc_html__( 'Content Reveal - Content Unreveal Label', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'button_text_open',
					'type'        => esc_html__( 'Content Reveal - Content Reveal Label', 'powerpack' ),
					'editor_type' => 'LINE',
				],
			],
		];
		$widgets[ 'pp-counter' ]              = [
			'conditions' => [ 'widgetType' => 'pp-counter' ],
			'fields'     => [
				[
					'field'       => 'starting_number',
					'type'        => esc_html__( 'Counter - Starting Number', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'ending_number',
					'type'        => esc_html__( 'Counter - Ending Number', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'number_prefix',
					'type'        => esc_html__( 'Counter - Number Prefix', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'number_suffix',
					'type'        => esc_html__( 'Counter - Number Suffix', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'counter_title',
					'type'        => esc_html__( 'Counter - Title', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'counter_subtitle',
					'type'        => esc_html__( 'Counter - Subtitle', 'powerpack' ),
					'editor_type' => 'LINE'
				],
			],
		];
		$widgets[ 'pp-divider' ]              = [
			'conditions' => [ 'widgetType' => 'pp-divider' ],
			'fields'     => [
				[
					'field'       => 'divider_text',
					'type'        => esc_html__( 'Divider - Divider Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
			],
		];
		$widgets[ 'pp-dual-heading' ]         = [
			'conditions' => [ 'widgetType' => 'pp-dual-heading' ],
			'fields'     => [
				[
					'field'       => 'first_text',
					'type'        => esc_html__( 'Dual Heading - First Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'second_text',
					'type'        => esc_html__( 'Dual Heading - Second Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Dual Heading - Link', 'powerpack' ),
					'editor_type' => 'LINK',
				],
			],
		];
		$widgets['pp-fancy-heading']        = [
			'conditions' => [ 'widgetType' => 'pp-fancy-heading' ],
			'fields'     => [
				[
					'field'       => 'heading_text',
					'type'        => esc_html__( 'Fancy Heading - Heading Text', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Fancy Heading - Link', 'powerpack' ),
					'editor_type' => 'LINK',
				],
			],
		];
		$widgets['pp-flipbox']              = [
			'conditions' => [ 'widgetType' => 'pp-flipbox' ],
			'fields'     => [
				[
					'field'       => 'icon_text',
					'type'        => esc_html__( 'Flip Box - Front Icon Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'title_front',
					'type'        => esc_html__( 'Flip Box - Front Title', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_front',
					'type'        => esc_html__( 'Flip Box - Front Description', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'icon_text_back',
					'type'        => esc_html__( 'Flip Box - Back Icon Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'title_back',
					'type'        => esc_html__( 'Flip Box - Back Title', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'description_back',
					'type'        => esc_html__( 'Flip Box - Back Description', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Flip Box - Link', 'powerpack' ),
					'editor_type' => 'LINK',
				],
				[
					'field'       => 'flipbox_button_text',
					'type'        => esc_html__( 'Flip Box - Button Text', 'powerpack' ),
					'editor_type' => 'LINE',
				],
			],
		];
		$widgets['pp-fluent-forms']         = [
			'conditions' => [ 'widgetType' => 'pp-fluent-forms' ],
			'fields'     => [
				[
					'field'       => 'form_title_custom',
					'type'        => esc_html__( 'Fluent Forms - Title', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'form_description_custom',
					'type'        => esc_html__( 'Fluent Forms - Description', 'powerpack' ),
					'editor_type' => 'AREA',
				],
			],
		];
		$widgets['pp-formidable-forms']     = [
			'conditions' => [ 'widgetType' => 'pp-formidable-forms' ],
			'fields'     => [
				[
					'field'       => 'form_title_custom',
					'type'        => esc_html__( 'Formidable Forms - Title', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'form_description_custom',
					'type'        => esc_html__( 'Formidable Forms - Description', 'powerpack' ),
					'editor_type' => 'AREA',
				],
			],
		];
		$widgets[ 'pp-gravity-forms' ]        = [
			'conditions' => [ 'widgetType' => 'pp-gravity-forms' ],
			'fields'     => [
				[
					'field'       => 'form_title_custom',
					'type'        => esc_html__( 'Gravity Forms - Title', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'form_description_custom',
					'type'        => esc_html__( 'Gravity Forms - Description', 'powerpack' ),
					'editor_type' => 'AREA'
				],
			],
		];
		$widgets[ 'pp-image-hotspots' ]       = [
			'conditions' => [ 'widgetType' => 'pp-image-hotspots' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Image_Hotspots'
		];
		$widgets[ 'pp-icon-list' ]            = [
			'conditions' => [ 'widgetType' => 'pp-icon-list' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Icon_List'
		];
		$widgets['pp-image-accordion']      = [
			'conditions'        => [ 'widgetType' => 'pp-image-accordion' ],
			'fields'            => [],
			'integration-class' => 'WPML_PP_Image_Accordion',
		];
		$widgets[ 'pp-image-comparison' ]     = [
			'conditions' => [ 'widgetType' => 'pp-image-comparison' ],
			'fields'     => [
				[
					'field'       => 'before_label',
					'type'        => esc_html__( 'Image Comparision - Before Label', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'after_label',
					'type'        => esc_html__( 'Image Comparision - After Label', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				
			],
		];
		$widgets[ 'pp-info-box' ]             = [
			'conditions' => [ 'widgetType' => 'pp-info-box' ],
			'fields'     => [
				[
					'field'       => 'icon_text',
					'type'        => esc_html__( 'Info Box - Icon Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'heading',
					'type'        => esc_html__( 'Info Box - Title', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'sub_heading',
					'type'        => esc_html__( 'Info Box - Subtitle', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'description',
					'type'        => esc_html__( 'Info Box - Description', 'powerpack' ),
					'editor_type' => 'AREA'
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Info Box - Link', 'powerpack' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Info Box - Button Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				
			],
		];
		$widgets[ 'pp-info-box-carousel' ]    = [
			'conditions' => [ 'widgetType' => 'pp-info-box-carousel' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Info_Box_Carousel'
		];
		$widgets[ 'pp-info-list' ]            = [
			'conditions' => [ 'widgetType' => 'pp-info-list' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Info_List'
		];
		$widgets[ 'pp-info-table' ]           = [
			'conditions' => [ 'widgetType' => 'pp-info-table' ],
			'fields'     => [
				[
					'field'       => 'icon_text',
					'type'        => esc_html__( 'Info Table - Icon Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'heading',
					'type'        => esc_html__( 'Info Table - Title', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'sub_heading',
					'type'        => esc_html__( 'Info Table - Subtitle', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'description',
					'type'        => esc_html__( 'Info Table - Description', 'powerpack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'sale_badge_text',
					'type'        => esc_html__( 'Info Table - Sale Badge Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Info Table - Link', 'powerpack' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Info Table - Button Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				
			],
		];
		$widgets[ 'pp-instafeed' ]            = [
			'conditions' => [ 'widgetType' => 'pp-instafeed' ],
			'fields'     => [
				[
					'field'       => 'insta_link_title',
					'type'        => esc_html__( 'Instafeed - Link Title', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				'insta_profile_url' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Instafeed - Instagram Profile URL', 'powerpack' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'load_more_button_text',
					'type'        => esc_html__( 'Instafeed - Load More Button Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
			],
		];
		$widgets[ 'pa-link-effects' ]         = [
			'conditions' => [ 'widgetType' => 'pa-link-effects' ],
			'fields'     => [
				[
					'field'       => 'text',
					'type'        => esc_html__( 'Link Effects - Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'secondary_text',
					'type'        => esc_html__( 'Link Effects - Secondary Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Link Effects - Link', 'powerpack' ),
					'editor_type' => 'LINK'
				],
			],
		];
		$widgets[ 'pp-logo-carousel' ]        = [
			'conditions' => [ 'widgetType' => 'pp-logo-carousel' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Logo_Carousel'
		];
		$widgets[ 'pp-logo-grid' ]            = [
			'conditions' => [ 'widgetType' => 'pp-logo-grid' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Logo_Grid'
		];
		$widgets[ 'pp-ninja-forms' ]          = [
			'conditions' => [ 'widgetType' => 'pp-ninja-forms' ],
			'fields'     => [
				[
					'field'       => 'form_title_custom',
					'type'        => esc_html__( 'Ninja Forms - Title', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'form_description_custom',
					'type'        => esc_html__( 'Ninja Forms - Description', 'powerpack' ),
					'editor_type' => 'AREA'
				],
			],
		];
		$widgets['pp-posts']                = [
			'conditions' => [ 'widgetType' => 'pp-posts' ],
			'fields'     => [
				[
					'field'       => 'query_id',
					'type'        => esc_html__( 'Posts - Query Id', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'nothing_found_message',
					'type'        => esc_html__( 'Posts - Nothing Found Message', 'powerpack' ),
					'editor_type' => 'AREA',
				],
				[
					'field'       => 'classic_post_terms_separator',
					'type'        => esc_html__( 'Posts: Classic - Terms Separator', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'classic_post_meta_separator',
					'type'        => esc_html__( 'Posts: Classic - Post Meta Separator', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'classic_author_prefix',
					'type'        => esc_html__( 'Posts: Classic - Author Prefix', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'classic_date_prefix',
					'type'        => esc_html__( 'Posts: Classic - Date Prefix', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'classic_button_text',
					'type'        => esc_html__( 'Posts: Classic - Read More Button Text', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'classic_pagination_prev_label',
					'type'        => esc_html__( 'Posts: Classic - Pagination Prev Label', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'classic_pagination_next_label',
					'type'        => esc_html__( 'Posts: Classic - Pagination Next Label', 'powerpack' ),
					'editor_type' => 'LINE',
				],
			],
		];
		$widgets[ 'pp-price-menu' ]           = [
			'conditions' => [ 'widgetType' => 'pp-price-menu' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Price_Menu'
		];
		$widgets[ 'pp-pricing-table' ]        = [
			'conditions' => [ 'widgetType' => 'pp-pricing-table' ],
			'fields'     => [
				[
					'field'       => 'table_title',
					'type'        => esc_html__( 'Pricing Table - Title', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'table_subtitle',
					'type'        => esc_html__( 'Pricing Table - Subtitle', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'table_price',
					'type'        => esc_html__( 'Pricing Table - Price', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'table_original_price',
					'type'        => esc_html__( 'Pricing Table - Origibal Price', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'table_duration',
					'type'        => esc_html__( 'Pricing Table - Duration', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'ribbon_title',
					'type'        => esc_html__( 'Pricing Table - Ribbon Title', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'table_button_text',
					'type'        => esc_html__( 'Pricing Table - Button Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Pricing Table - Link', 'powerpack' ),
					'editor_type' => 'LINK'
				],
				[
					'field'       => 'table_additional_info',
					'type'        => esc_html__( 'Pricing Table - Additional Info', 'powerpack' ),
					'editor_type' => 'AREA'
				],
			],
			'integration-class' => 'WPML_PP_Pricing_Table'
		];
		$widgets[ 'pp-promo-box' ]            = [
			'conditions' => [ 'widgetType' => 'pp-promo-box' ],
			'fields'     => [
				[
					'field'       => 'heading',
					'type'        => esc_html__( 'Promo Box - Heading', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'sub_heading',
					'type'        => esc_html__( 'Promo Box - Sub Heading', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'content',
					'type'        => esc_html__( 'Promo Box - Description', 'powerpack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'button_text',
					'type'        => esc_html__( 'Promo Box - Button Text', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Promo Box - link', 'powerpack' ),
					'editor_type' => 'LINK'
				],
			],
		];
		$widgets['pp-scroll-image']         = [
			'conditions' => [ 'widgetType' => 'pp-scroll-image' ],
			'fields'     => [
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Scroll Image - URL', 'powerpack' ),
					'editor_type' => 'LINK',
				],
			],
		];
		$widgets['pp-random-image']         = [
			'conditions' => [ 'widgetType' => 'pp-random-image' ],
			'fields'     => [
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Random Image - URL', 'powerpack' ),
					'editor_type' => 'LINK',
				],
			],
		];
		$widgets[ 'pp-team-member' ]          = [
			'conditions' => [ 'widgetType' => 'pp-team-member' ],
			'fields'     => [
				[
					'field'       => 'team_member_name',
					'type'        => esc_html__( 'Team Member - Name', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'team_member_position',
					'type'        => esc_html__( 'Team Member - Position', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'team_member_description',
					'type'        => esc_html__( 'Team Member - Description', 'powerpack' ),
					'editor_type' => 'VISUAL'
				],
				'link' => [
					'field'       => 'url',
					'type'        => esc_html__( 'Team Member - URL', 'powerpack' ),
					'editor_type' => 'LINK',
				],
			],
			'integration-class' => 'WPML_PP_Team_Member'
		];
		$widgets[ 'pp-team-member-carousel' ] = [
			'conditions' => [ 'widgetType' => 'pp-team-member-carousel' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Team_Member_Carousel'
		];
		$widgets['pp-twitter-buttons']      = [
			'conditions' => [ 'widgetType' => 'pp-twitter-buttons' ],
			'fields'     => [
				[
					'field'       => 'profile',
					'type'        => esc_html__( 'Twitter Button - Profile URL or Username', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'recipient_id',
					'type'        => esc_html__( 'Twitter Button - Recipient Id', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'default_text',
					'type'        => esc_html__( 'Twitter Button - Default Text', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'hashtag_url',
					'type'        => esc_html__( 'Twitter Button - Hashtag URL or #hashtag', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'via',
					'type'        => esc_html__( 'Twitter Button - Via (twitter handler)', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'share_text',
					'type'        => esc_html__( 'Twitter Button - Custom Share Text', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'share_url',
					'type'        => esc_html__( 'Twitter Button - Custom Share URL', 'powerpack' ),
					'editor_type' => 'LINK',
				],
			],
		];
		$widgets['pp-twitter-grid']         = [
			'conditions' => [ 'widgetType' => 'pp-twitter-grid' ],
			'fields'     => [
				[
					'field'       => 'url',
					'type'        => esc_html__( 'Twitter Grid - Collection URL', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'tweet_limit',
					'type'        => esc_html__( 'Twitter Grid - Tweet Limit', 'powerpack' ),
					'editor_type' => 'LINE',
				],
			],
		];
		$widgets['pp-twitter-timeline']     = [
			'conditions' => [ 'widgetType' => 'pp-twitter-timeline' ],
			'fields'     => [
				[
					'field'       => 'username',
					'type'        => esc_html__( 'Twitter Timeline - Username', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'tweet_limit',
					'type'        => esc_html__( 'Twitter Timeline - Tweet Limit', 'powerpack' ),
					'editor_type' => 'LINE',
				],
			],
		];
		$widgets['pp-twitter-tweet']        = [
			'conditions' => [ 'widgetType' => 'pp-twitter-tweet' ],
			'fields'     => [
				[
					'field'       => 'tweet_url',
					'type'        => esc_html__( 'Twitter Tweet - Tweet URL', 'powerpack' ),
					'editor_type' => 'LINK',
				],
			],
		];
		$widgets[ 'pp-wpforms' ]              = [
			'conditions' => [ 'widgetType' => 'pp-wpforms' ],
			'fields'     => [
				[
					'field'       => 'form_title_custom',
					'type'        => esc_html__( 'WPForms - Title', 'powerpack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'form_description_custom',
					'type'        => esc_html__( 'WPForms - Description', 'powerpack' ),
					'editor_type' => 'AREA'
				],
			],
		];
		$widgets['pp-progress-bar'] = [
			'conditions' => [ 'widgetType' => 'pp-progress-bar' ],
			'fields'     => [
				[
					'field'       => 'bar_label',
					'type'        => esc_html__( 'Progress Bar - Label (Single)', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'half_circle_prefix',
					'type'        => esc_html__( 'Progress Bar - Half Circle Prefix Label', 'powerpack' ),
					'editor_type' => 'LINE',
				],
				[
					'field'       => 'half_circle_suffix',
					'type'        => esc_html__( 'Progress Bar - Half Circle Suffix Label', 'powerpack' ),
					'editor_type' => 'LINE',
				],
			],
			'integration-class' => 'WPML_PP_Progress_Bar',
		];
		$widgets['pp-interactive-circle'] = [
			'conditions' => [ 'widgetType' => 'pp-interactive-circle' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Interactive_Circle',
		];

		$this->init_classes();
		
		return $widgets;
	}
	
	private function init_classes() {
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-advanced-accordion.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-business-hours.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-buttons.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-content-ticker.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-image-hotspots.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-icon-list.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-image-accordion.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-info-box-carousel.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-info-list.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-logo-carousel.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-logo-grid.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-price-menu.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-pricing-table.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-team-member.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-team-member-carousel.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-progress-bar.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-interactive-circle.php';
	}
}

$pp_elements_wpml = new PP_Elements_WPML();
