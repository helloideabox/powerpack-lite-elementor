<?php
namespace PowerpackElementsLite\Classes;

class PP_Elements_WPML {
    public function __construct()
    {
		add_filter( 'wpml_elementor_widgets_to_translate', array( $this, 'translate_fields' ) );
		$this->type = 'widgetType';
	}
	
	public function translate_fields ( $widgets ) {
		$widgets[ 'pp-business-hours' ]       = [
			'conditions' => [ $this->type => 'pp-business-hours' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Business_Hours'
		];
		$widgets[ 'pp-caldera-forms' ]        = [
			'conditions' => [ $this->type => 'pp-caldera-forms' ],
			'fields'     => [
				[
					'field'       => 'form_title_custom',
					'type'        => __( 'Caldera Forms - Title', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'form_description_custom',
					'type'        => __( 'Caldera Forms - Description', 'power-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];
		$widgets[ 'pp-contact-form-7' ]       = [
			'conditions' => [ $this->type => 'pp-contact-form-7' ],
			'fields'     => [
				[
					'field'       => 'form_title_text',
					'type'        => __( 'Contact Form 7 - Title', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'form_description_text',
					'type'        => __( 'Contact Form 7 - Description', 'power-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];
		$widgets[ 'pp-counter' ]              = [
			'conditions' => [ $this->type => 'pp-counter' ],
			'fields'     => [
				[
					'field'       => 'number_prefix',
					'type'        => __( 'Counter - Number Prefix', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'number_suffix',
					'type'        => __( 'Counter - Number Suffix', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'counter_title',
					'type'        => __( 'Counter - Title', 'power-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];
		$widgets[ 'pp-divider' ]              = [
			'conditions' => [ $this->type => 'pp-divider' ],
			'fields'     => [
				[
					'field'       => 'divider_text',
					'type'        => __( 'Divider - Divider Text', 'power-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];
		$widgets[ 'pp-dual-heading' ]         = [
			'conditions' => [ $this->type => 'pp-dual-heading' ],
			'fields'     => [
				[
					'field'       => 'first_text',
					'type'        => __( 'Dual Heading - First Text', 'power-pack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'second_text',
					'type'        => __( 'Dual Heading - Second Text', 'power-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];
		$widgets[ 'pp-gravity-forms' ]        = [
			'conditions' => [ $this->type => 'pp-gravity-forms' ],
			'fields'     => [
				[
					'field'       => 'form_title_custom',
					'type'        => __( 'Gravity Forms - Title', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'form_description_custom',
					'type'        => __( 'Gravity Forms - Description', 'power-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];
		$widgets[ 'pp-image-hotspots' ]       = [
			'conditions' => [ $this->type => 'pp-image-hotspots' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Image_Hotspots'
		];
		$widgets[ 'pp-icon-list' ]            = [
			'conditions' => [ $this->type => 'pp-icon-list' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Icon_List'
		];
		$widgets[ 'pp-image-comparison' ]     = [
			'conditions' => [ $this->type => 'pp-image-comparison' ],
			'fields'     => [
				[
					'field'       => 'before_label',
					'type'        => __( 'Image Comparision - Before Label', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'after_label',
					'type'        => __( 'Image Comparision - After Label', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				
			],
		];
		$widgets[ 'pp-info-box' ]             = [
			'conditions' => [ $this->type => 'pp-info-box' ],
			'fields'     => [
				[
					'field'       => 'icon_text',
					'type'        => __( 'Info Box - Icon Text', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'heading',
					'type'        => __( 'Info Box - Title', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'sub_heading',
					'type'        => __( 'Info Box - Subtitle', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'description',
					'type'        => __( 'Info Box - Description', 'power-pack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'link',
					'type'        => __( 'Info Box - Link', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'button_text',
					'type'        => __( 'Info Box - Button Text', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				
			],
		];
		$widgets[ 'pp-info-box-carousel' ]    = [
			'conditions' => [ $this->type => 'pp-info-box-carousel' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Info_Box_Carousel'
		];
		$widgets[ 'pp-info-list' ]            = [
			'conditions' => [ $this->type => 'pp-info-list' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Info_List'
		];
		$widgets[ 'pp-info-table' ]           = [
			'conditions' => [ $this->type => 'pp-info-table' ],
			'fields'     => [
				[
					'field'       => 'icon_text',
					'type'        => __( 'Info Table - Icon Text', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'heading',
					'type'        => __( 'Info Table - Title', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'sub_heading',
					'type'        => __( 'Info Table - Subtitle', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'description',
					'type'        => __( 'Info Table - Description', 'power-pack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'sale_badge_text',
					'type'        => __( 'Info Table - Sale Badge Text', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'link',
					'type'        => __( 'Info Table - Link', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'button_text',
					'type'        => __( 'Info Table - Button Text', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				
			],
		];
		$widgets[ 'pp-instafeed' ]            = [
			'conditions' => [ $this->type => 'pp-instafeed' ],
			'fields'     => [
				[
					'field'       => 'insta_link_title',
					'type'        => __( 'Instafeed - Link Title', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'insta_profile_url',
					'type'        => __( 'Instafeed - Instagram Profile URL', 'power-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];
		$widgets[ 'pa-link-effects' ]         = [
			'conditions' => [ $this->type => 'pa-link-effects' ],
			'fields'     => [
				[
					'field'       => 'text',
					'type'        => __( 'Link Effects - Text', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'secondary_text',
					'type'        => __( 'Link Effects - Secondary Text', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'link',
					'type'        => __( 'Link Effects - link', 'power-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];
		$widgets[ 'pp-logo-carousel' ]        = [
			'conditions' => [ $this->type => 'pp-logo-carousel' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Logo_Carousel'
		];
		$widgets[ 'pp-logo-grid' ]            = [
			'conditions' => [ $this->type => 'pp-logo-grid' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Logo_Grid'
		];
		$widgets[ 'pp-ninja-forms' ]          = [
			'conditions' => [ $this->type => 'pp-ninja-forms' ],
			'fields'     => [
				[
					'field'       => 'form_title_custom',
					'type'        => __( 'Ninja Forms - Title', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'form_description_custom',
					'type'        => __( 'Ninja Forms - Description', 'power-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];
		$widgets[ 'pp-price-menu' ]           = [
			'conditions' => [ $this->type => 'pp-price-menu' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Price_Menu'
		];
		$widgets[ 'pp-pricing-table' ]        = [
			'conditions' => [ $this->type => 'pp-pricing-table' ],
			'fields'     => [
				[
					'field'       => 'table_title',
					'type'        => __( 'Pricing Table - Title', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'table_subtitle',
					'type'        => __( 'Pricing Table - Subtitle', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'table_price',
					'type'        => __( 'Pricing Table - Price', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'table_original_price',
					'type'        => __( 'Pricing Table - Origibal Price', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'table_duration',
					'type'        => __( 'Pricing Table - Duration', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'table_button_text',
					'type'        => __( 'Pricing Table - Button Text', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'link',
					'type'        => __( 'Pricing Table - Link', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'table_additional_info',
					'type'        => __( 'Pricing Table - Additional Info', 'power-pack' ),
					'editor_type' => 'AREA'
				],
			],
			'integration-class' => 'WPML_PP_Pricing_Table'
		];
		$widgets[ 'pp-promo-box' ]            = [
			'conditions' => [ $this->type => 'pp-promo-box' ],
			'fields'     => [
				[
					'field'       => 'heading',
					'type'        => __( 'Promo Box - Heading', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'sub_heading',
					'type'        => __( 'Promo Box - Sub Heading', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'content',
					'type'        => __( 'Promo Box - Description', 'power-pack' ),
					'editor_type' => 'AREA'
				],
				[
					'field'       => 'button_text',
					'type'        => __( 'Promo Box - Button Text', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'link',
					'type'        => __( 'Promo Box - link', 'power-pack' ),
					'editor_type' => 'LINE'
				],
			],
		];
		$widgets[ 'pp-team-member' ]          = [
			'conditions' => [ $this->type => 'pp-team-member' ],
			'fields'     => [
				[
					'field'       => 'team_member_name',
					'type'        => __( 'Team Member - Name', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'team_member_position',
					'type'        => __( 'Team Member - Position', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'team_member_description',
					'type'        => __( 'Team Member - Description', 'power-pack' ),
					'editor_type' => 'AREA'
				],
			],
			'integration-class' => 'WPML_PP_Team_Member'
		];
		$widgets[ 'pp-team-member-carousel' ] = [
			'conditions' => [ $this->type => 'pp-team-member-carousel' ],
			'fields'     => [],
			'integration-class' => 'WPML_PP_Team_Member_Carousel'
		];
		$widgets[ 'pp-wpforms' ]              = [
			'conditions' => [ $this->type => 'pp-wpforms' ],
			'fields'     => [
				[
					'field'       => 'form_title_custom',
					'type'        => __( 'WPForms - Title', 'power-pack' ),
					'editor_type' => 'LINE'
				],
				[
					'field'       => 'form_description_custom',
					'type'        => __( 'WPForms - Description', 'power-pack' ),
					'editor_type' => 'AREA'
				],
			],
		];

		$this->init_classes();
		
		return $widgets;
	}
	
	private function init_classes() {
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-business-hours.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-image-hotspots.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-icon-list.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-info-box-carousel.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-info-list.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-logo-carousel.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-logo-grid.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-price-menu.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-pricing-table.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-team-member.php';
		require_once POWERPACK_ELEMENTS_LITE_PATH . 'classes/wpml/class-wpml-pp-team-member-carousel.php';
	}
}

$pp_elements_wpml = new PP_Elements_WPML();
