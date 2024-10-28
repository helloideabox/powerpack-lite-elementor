<?php
namespace PowerpackElementsLite\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class PP_Config.
 */
class PP_Config {

	/**
	 * Widget List
	 *
	 * @var widget_list
	 */
	public static $widget_info = null;

	/**
	 * Pro Widgets List
	 *
	 * @var widget_list
	 */
	public static $pro_widgets = null;

	/**
	 * Help Docs Links
	 *
	 * @var help_docs
	 */
	public static $help_docs = null;

	/**
	 * Get Widget List.
	 *
	 * @since 2.1.0
	 *
	 * @return array The Widget List.
	 */
	public static function get_widget_info() {
		if ( null === self::$widget_info ) {
			self::$widget_info = array(
				'Advanced_Accordion'   => array(
					'name'       => 'pp-advanced-accordion',
					'title'      => esc_html__( 'Advanced Accordion', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'icon'       => 'ppicon-advanced-accordion power-pack-admin-icon',
					'keywords'   => array( 'powerpack', 'accordion', 'advanced' ),
				),
				'Business_Hours'       => array(
					'name'       => 'pp-business-hours',
					'title'      => esc_html__( 'Business Hours', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'business', 'hours' ),
					'icon'       => 'ppicon-business-hours power-pack-admin-icon',
				),
				'Buttons'              => array(
					'name'       => 'pp-buttons',
					'title'      => esc_html__( 'Buttons', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'buttons' ),
					'icon'       => 'ppicon-multi-buttons power-pack-admin-icon',
				),
				'Contact_Form_7'       => array(
					'name'       => 'pp-contact-form-7',
					'title'      => esc_html__( 'Contact Form 7', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'contact', 'form' ),
					'icon'       => 'ppicon-contact-form power-pack-admin-icon',
				),
				'Content_Reveal'       => array(
					'name'       => 'pp-content-reveal',
					'title'      => esc_html__( 'Content Reveal', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack' ),
					'icon'       => 'ppicon-content-reveal power-pack-admin-icon',
				),
				'Content_Ticker'       => array(
					'name'       => 'pp-content-ticker',
					'title'      => esc_html__( 'Content Ticker', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'posts' ),
					'icon'       => 'ppicon-content-ticker power-pack-admin-icon',
				),
				'Counter'              => array(
					'name'       => 'pp-counter',
					'title'      => esc_html__( 'Counter', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'counter' ),
					'icon'       => 'ppicon-counter power-pack-admin-icon',
				),
				'Divider'              => array(
					'name'       => 'pp-divider',
					'title'      => esc_html__( 'Divider', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'divider' ),
					'icon'       => 'ppicon-divider power-pack-admin-icon',
				),
				'Flipbox'              => array(
					'name'       => 'pp-flipbox',
					'title'      => esc_html__( 'Flip Box', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'flip', 'box', 'flipbox' ),
					'icon'       => 'ppicon-flip-box power-pack-admin-icon',
				),
				'Fluent_Forms'         => array(
					'name'       => 'pp-fluent-forms',
					'title'      => esc_html__( 'Fluent Forms', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'contact', 'form' ),
					'icon'       => 'ppicon-contact-form power-pack-admin-icon',
				),
				'Formidable_Forms'     => array(
					'name'       => 'pp-formidable-forms',
					'title'      => esc_html__( 'Formidable Forms', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'contact', 'form' ),
					'icon'       => 'ppicon-contact-form power-pack-admin-icon',
				),
				'Gravity_Forms'        => array(
					'name'       => 'pp-gravity-forms',
					'title'      => esc_html__( 'Gravity Forms', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'contact', 'form' ),
					'icon'       => 'ppicon-contact-form power-pack-admin-icon',
				),
				'Dual_Heading'         => array(
					'name'       => 'pp-dual-heading',
					'title'      => esc_html__( 'Dual Heading', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'dual', 'heading' ),
					'icon'       => 'ppicon-dual-heading power-pack-admin-icon',
				),
				'Fancy_Heading'        => array(
					'name'       => 'pp-fancy-heading',
					'title'      => esc_html__( 'Fancy Heading', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'fancy', 'heading' ),
					'icon'       => 'ppicon-heading power-pack-admin-icon',
				),
				'Hotspots'             => array(
					'name'       => 'pp-image-hotspots',
					'title'      => esc_html__( 'Image Hotspots', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'image', 'hotspots' ),
					'icon'       => 'ppicon-image-hotspot power-pack-admin-icon',
				),
				'Icon_List'            => array(
					'name'       => 'pp-icon-list',
					'title'      => esc_html__( 'Icon List', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'icon', 'list' ),
					'icon'       => 'ppicon-icon-list power-pack-admin-icon',
				),
				'Image_Accordion'      => array(
					'name'       => 'pp-image-accordion',
					'title'      => esc_html__( 'Image Accordion', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack' ),
					'icon'       => 'ppicon-image-accordion power-pack-admin-icon',
				),
				'Image_Comparison'     => array(
					'name'       => 'pp-image-comparison',
					'title'      => esc_html__( 'Image Comparison', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'image', 'comparison', 'before', 'after', 'slider' ),
					'icon'       => 'ppicon-image-comparison power-pack-admin-icon',
				),
				'Info_Box'             => array(
					'name'       => 'pp-info-box',
					'title'      => esc_html__( 'Info Box', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'info box' ),
					'icon'       => 'ppicon-info-box power-pack-admin-icon',
				),
				'Info_Box_Carousel'    => array(
					'name'       => 'pp-info-box-carousel',
					'title'      => esc_html__( 'Info Grid & Carousel', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'info box', 'grid', 'slider' ),
					'icon'       => 'ppicon-info-box-carousel power-pack-admin-icon',
				),
				'Info_List'            => array(
					'name'       => 'pp-info-list',
					'title'      => esc_html__( 'Info List', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'info' ),
					'icon'       => 'ppicon-info-list power-pack-admin-icon',
				),
				'Info_Table'           => array(
					'name'       => 'pp-info-table',
					'title'      => esc_html__( 'Info Table', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'info' ),
					'icon'       => 'ppicon-info-table power-pack-admin-icon',
				),
				'Instafeed'            => array(
					'name'       => 'pp-instafeed',
					'title'      => esc_html__( 'Instagram Feed', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'instagram' ),
					'icon'       => 'ppicon-instagram-feed power-pack-admin-icon',
				),
				'Interactive_Circle'   => array(
					'name'       => 'pp-interactive-circle',
					'title'      => esc_html__( 'Interactive Circle', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'interactive circle', 'circle' ),
					'icon'       => 'ppicon-interactive-circle power-pack-admin-icon',
				),
				'Link_Effects'         => array(
					'name'       => 'pa-link-effects',
					'title'      => esc_html__( 'Link Effects', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack' ),
					'icon'       => 'ppicon-link-effects power-pack-admin-icon',
				),
				'Logo_Carousel'        => array(
					'name'       => 'pp-logo-carousel',
					'title'      => esc_html__( 'Logo Carousel', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'logo', 'carousel', 'image' ),
					'icon'       => 'ppicon-logo-carousel power-pack-admin-icon',
				),
				'Logo_Grid'            => array(
					'name'       => 'pp-logo-grid',
					'title'      => esc_html__( 'Logo Grid', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'logo', 'image' ),
					'icon'       => 'ppicon-logo-grid power-pack-admin-icon',
				),
				'Ninja_Forms'          => array(
					'name'       => 'pp-ninja-forms',
					'title'      => esc_html__( 'Ninja Forms', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'contact', 'form' ),
					'icon'       => 'ppicon-contact-form power-pack-admin-icon',
				),
				'Posts'              => array(
					'name'       => 'pp-posts',
					'title'      => esc_html__( 'Posts', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack' ),
					'icon'       => 'ppicon-posts-grid power-pack-admin-icon',
				),
				'Price_Menu'           => array(
					'name'       => 'pp-price-menu',
					'title'      => esc_html__( 'Price Menu', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'price' ),
					'icon'       => 'ppicon-pricing-menu power-pack-admin-icon',
				),
				'Pricing_Table'        => array(
					'name'       => 'pp-pricing-table',
					'title'      => esc_html__( 'Pricing Table', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'price' ),
					'icon'       => 'ppicon-pricing-table power-pack-admin-icon',
				),
				'Progress_Bar'         => array(
					'name'       => 'pp-progress-bar',
					'title'      => esc_html__( 'Progress Bar', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'chart', 'counter' ),
					'icon'       => 'ppicon-progress-bar-2 power-pack-admin-icon',
				),
				'Promo_Box'            => array(
					'name'       => 'pp-promo-box',
					'title'      => esc_html__( 'Promo Box', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'info' ),
					'icon'       => 'ppicon-promo-box power-pack-admin-icon',
				),
				'Random_Image' => array(
					'name'       => 'pp-random-image',
					'title'      => esc_html__( 'Random Image', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'image' ),
					'icon'       => 'eicon-image power-pack-admin-icon',
				),
				'Scroll_Image'         => array(
					'name'       => 'pp-scroll-image',
					'title'      => esc_html__( 'Scroll Image', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'image' ),
					'icon'       => 'ppicon-scroll-image power-pack-admin-icon',
				),
				'Team_Member'          => array(
					'name'       => 'pp-team-member',
					'title'      => esc_html__( 'Team Member', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'team', 'member' ),
					'icon'       => 'ppicon-team-member power-pack-admin-icon',
				),
				'Team_Member_Carousel' => array(
					'name'       => 'pp-team-member-carousel',
					'title'      => esc_html__( 'Team Member Carousel', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'team', 'member', 'carousel' ),
					'icon'       => 'ppicon-team-member-carousel power-pack-admin-icon',
				),
				'Twitter_Buttons'      => array(
					'name'       => 'pp-twitter-buttons',
					'title'      => esc_html__( 'Twitter Buttons', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack' ),
					'icon'       => 'ppicon-twitter-buttons power-pack-admin-icon',
				),
				'Twitter_Grid'         => array(
					'name'       => 'pp-twitter-grid',
					'title'      => esc_html__( 'Twitter Grid', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack' ),
					'icon'       => 'ppicon-twitter-grid power-pack-admin-icon',
				),
				'Twitter_Timeline'     => array(
					'name'       => 'pp-twitter-timeline',
					'title'      => esc_html__( 'Twitter Timeline', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack' ),
					'icon'       => 'ppicon-twitter-timeline power-pack-admin-icon',
				),
				'Twitter_Tweet'        => array(
					'name'       => 'pp-twitter-tweet',
					'title'      => esc_html__( 'Twitter Tweet', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack' ),
					'icon'       => 'ppicon-twitter-tweet power-pack-admin-icon',
				),
				'WP_Forms'             => array(
					'name'       => 'pp-wpforms',
					'title'      => esc_html__( 'WP Forms', 'powerpack' ),
					'categories' => array( 'powerpack-elements' ),
					'keywords'   => array( 'powerpack', 'contact', 'form' ),
					'icon'       => 'ppicon-contact-form power-pack-admin-icon',
				),
			);
		}

		return apply_filters( 'pp_elements_widget_info', self::$widget_info );
	}

	/**
	 * Get Widget List.
	 *
	 * @since 1.2.9.4
	 *
	 * @return array The Widget List.
	 */
	public static function get_pro_widgets() {
		if ( null === self::$pro_widgets ) {
			self::$pro_widgets = array(
				array(
					'name'       => 'pp-advanced-menu',
					'title'      => esc_html__( 'Advanced Menu', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'menu', 'navigation' ),
					'icon'       => 'ppicon-advanced-menu power-pack-admin-icon',
				),
				array(
					'name'       => 'pp-advanced-tabs',
					'title'      => esc_html__( 'Advanced Tabs', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'tabs' ),
					'icon'       => 'ppicon-tabs power-pack-admin-icon',
				),
				'Album'              => array(
					'name'       => 'pp-album',
					'title'      => esc_html__( 'Album', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'album', 'gallery', 'lightbox' ),
					'icon'       => 'ppicon-tabs power-pack-admin-icon',
				),
				'Breadcrumbs'        => array(
					'name'       => 'pp-breadcrumbs',
					'title'      => esc_html__( 'Breadcrumbs', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'breadcrumbs' ),
					'icon'       => 'ppicon-breadcrumbs power-pack-admin-icon',
				),
				'Card_Slider'        => array(
					'name'       => 'pp-card-slider',
					'title'      => esc_html__( 'Card Slider', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'posts', 'cpt', 'slider' ),
					'icon'       => 'ppicon-card-slider power-pack-admin-icon',
				),
				'Categories'         => array(
					'name'       => 'pp-categories',
					'title'      => esc_html__( 'Categories', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'categories' ),
					'icon'       => 'ppicon-categories power-pack-admin-icon',
				),
				'Countdown'          => array(
					'name'       => 'pp-countdown',
					'title'      => esc_html__( 'Countdown Timer', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'countdown', 'timer' ),
					'icon'       => 'ppicon-countdown power-pack-admin-icon',
				),
				'Coupons'            => array(
					'name'       => 'pp-coupons',
					'title'      => esc_html__( 'Coupons', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'coupon' ),
					'icon'       => 'ppicon-coupon power-pack-admin-icon',
				),
				'Devices'            => array(
					'name'       => 'pp-devices',
					'title'      => esc_html__( 'Devices', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'devices' ),
					'icon'       => 'ppicon-device power-pack-admin-icon',
				),
				'Faq'                => array(
					'name'       => 'pp-faq',
					'title'      => esc_html__( 'FAQ', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'faq' ),
					'icon'       => 'ppicon-advanced-accordion power-pack-admin-icon',
				),
				'Image_Gallery'      => array(
					'name'       => 'pp-image-gallery',
					'title'      => esc_html__( 'Image Gallery', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'image', 'gallery' ),
					'icon'       => 'ppicon-image-gallery power-pack-admin-icon',
				),
				'Image_Slider'       => array(
					'name'       => 'pp-image-slider',
					'title'      => esc_html__( 'Image Slider', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'image', 'slider', 'slideshow', 'gallery', 'thumbnail', 'carousel' ),
					'icon'       => 'ppicon-gallery-slider power-pack-admin-icon',
				),
				'Google_Maps'        => array(
					'name'       => 'pp-google-maps',
					'title'      => esc_html__( 'Google Maps', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'google', 'maps' ),
					'icon'       => 'ppicon-map power-pack-admin-icon',
				),
				'How_To'             => array(
					'name'       => 'pp-how-to',
					'title'      => esc_html__( 'How To', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'how' ),
					'icon'       => 'ppicon-how-to power-pack-admin-icon',
				),
				'Magazine_Slider'    => array(
					'name'       => 'pp-magazine-slider',
					'title'      => esc_html__( 'Magazine Slider', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'posts' ),
					'icon'       => 'ppicon-magazine-slider power-pack-admin-icon',
				),
				'Offcanvas_Content'  => array(
					'name'       => 'pp-offcanvas-content',
					'title'      => esc_html__( 'Offcanvas Content', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'offcanvas', 'off canvas' ),
					'icon'       => 'ppicon-offcanvas-content power-pack-admin-icon',
				),
				'Onepage_Nav'        => array(
					'name'       => 'pp-one-page-nav',
					'title'      => esc_html__( 'One Page Navigation', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'one', 'page', 'dot' ),
					'icon'       => 'ppicon-page-navigation power-pack-admin-icon',
				),
				'Popup_Box'          => array(
					'name'       => 'pp-modal-popup',
					'title'      => esc_html__( 'Popup Box', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'modal', 'popup' ),
					'icon'       => 'ppicon-popup-box power-pack-admin-icon',
				),
				'Recipe'             => array(
					'name'       => 'pp-recipe',
					'title'      => esc_html__( 'Recipe', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'dish' ),
					'icon'       => 'ppicon-recipe power-pack-admin-icon',
				),
				'Review_Box'         => array(
					'name'       => 'pp-review-box',
					'title'      => esc_html__( 'Review Box', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'image' ),
					'icon'       => 'ppicon-review-box power-pack-admin-icon',
				),
				'Showcase'           => array(
					'name'       => 'pp-showcase',
					'title'      => esc_html__( 'Showcase', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'image', 'video', 'embed', 'youtube', 'vimeo', 'dailymotion', 'slider' ),
					'icon'       => 'ppicon-showcase power-pack-admin-icon',
				),
				'Table'              => array(
					'name'       => 'pp-table',
					'title'      => esc_html__( 'Table', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'table', 'csv' ),
					'icon'       => 'ppicon-table power-pack-admin-icon',
				),
				'Tabbed_Gallery'     => array(
					'name'       => 'pp-tabbed-gallery',
					'title'      => esc_html__( 'Tabbed Gallery', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'image', 'gallery', 'carousel', 'tab', 'slider' ),
					'icon'       => 'ppicon-tabbed-gallery power-pack-admin-icon',
				),
				'Testimonials'       => array(
					'name'       => 'pp-testimonials',
					'title'      => esc_html__( 'Testimonials', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'testimonials', 'reviews' ),
					'icon'       => 'ppicon-testimonial-carousel power-pack-admin-icon',
				),
				'Tiled_Posts'        => array(
					'name'       => 'pp-tiled-posts',
					'title'      => esc_html__( 'Tiled Posts', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack' ),
					'icon'       => 'ppicon-tiled-post power-pack-admin-icon',
				),
				'Timeline'           => array(
					'name'       => 'pp-timeline',
					'title'      => esc_html__( 'Timeline', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack' ),
					'icon'       => 'ppicon-timeline power-pack-admin-icon',
				),
				'Toggle'             => array(
					'name'       => 'pp-toggle',
					'title'      => esc_html__( 'Toggle', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'toggle', 'youtube', 'dailymotion' ),
					'icon'       => 'ppicon-content-toggle power-pack-admin-icon',
				),
				'Video'              => array(
					'name'       => 'pp-video',
					'title'      => esc_html__( 'Video', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'video', 'youtube', 'dailymotion' ),
					'icon'       => 'ppicon-video power-pack-admin-icon',
				),
				'Video_Gallery'      => array(
					'name'       => 'pp-video-gallery',
					'title'      => esc_html__( 'Video Gallery', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'video', 'youtube', 'dailymotion' ),
					'icon'       => 'ppicon-video-gallery power-pack-admin-icon',
				),
				'Woo_Add_To_Cart'    => array(
					'name'       => 'pp-woo-add-to-cart',
					'title'      => esc_html__( 'Woo - Add To Cart', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'woocommerce' ),
					'icon'       => 'ppicon-woo-add-to-cart power-pack-admin-icon',
				),
				'Woo_Cart'           => array(
					'name'       => 'pp-woo-cart',
					'title'      => esc_html__( 'Woo - Cart', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'woocommerce' ),
					'icon'       => 'ppicon-woo-cart power-pack-admin-icon',
				),
				'Woo_Categories'     => array(
					'name'       => 'pp-woo-categories',
					'title'      => esc_html__( 'Woo - Categories', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'woocommerce', 'category' ),
					'icon'       => 'ppicon-woo-categories power-pack-admin-icon',
				),
				'Woo_Checkout'       => array(
					'name'       => 'pp-woo-checkout',
					'title'      => esc_html__( 'Woo - Checkout', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'woocommerce' ),
					'icon'       => 'ppicon-woo-checkout power-pack-admin-icon',
				),
				'Woo_Mini_Cart'      => array(
					'name'       => 'pp-woo-mini-cart',
					'title'      => esc_html__( 'Woo - Mini Cart', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'woocommerce' ),
					'icon'       => 'ppicon-mini-cart power-pack-admin-icon',
				),
				'Woo_Offcanvas_Cart' => array(
					'name'       => 'pp-woo-offcanvas-cart',
					'title'      => esc_html__( 'Woo - Off Canvas Cart', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'woocommerce', 'offcanvas' ),
					'icon'       => 'ppicon-offcanvas-cart power-pack-admin-icon',
				),
				'Woo_Products'       => array(
					'name'       => 'pp-woo-products',
					'title'      => esc_html__( 'Woo - Products', 'powerpack' ),
					'categories' => '["powerpack-elements"]',
					'keywords'   => array( 'powerpack', 'woocommerce' ),
					'icon'       => 'ppicon-woo-products power-pack-admin-icon',
				),
			);
		}

		return apply_filters( 'pp_elements_lite_pro_widgets', self::$pro_widgets );
	}

	/**
	 * Add helper links for widgets
	 *
	 * @since 1.4.13.1
	 * @access public
	 */
	public static function widgets_help_links() {
		$utm_suffix = '?utm_source=widget&utm_medium=panel&utm_campaign=userkb';

		self::$help_docs = array(
			// Business Hours.
			'Advanced_Accordion'    => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/advanced-accordion-widget-overview/' . $utm_suffix,
			),
			// Business Hours.
			'Business_Hours'    => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/business-hours/business-hours-widget-overview/' . $utm_suffix,
			),
			// Counter.
			'Counter'           => array(
				esc_html__( 'Watch Video Overview', 'powerpack' ) => 'https://www.youtube.com/watch?v=g70UKxK_1dU&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj',
				esc_html__( 'Widget Overview', 'powerpack' )      => 'https://powerpackelements.com/docs/powerpack/widgets/counter/counter-widget-overview/' . $utm_suffix,
			),
			// Content Ticker.
			'Content_Ticker'     => array(
				esc_html__( 'Widget Overview', 'powerpack' )      => 'https://powerpackelements.com/docs/content-ticker-widget-overview/' . $utm_suffix,
			),
			// Counter.
			'Counter'            => array(
				esc_html__( 'Watch Video Overview', 'powerpack' ) => 'https://www.youtube.com/watch?v=g70UKxK_1dU&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj',
				esc_html__( 'Widget Overview', 'powerpack' )      => 'https://powerpackelements.com/docs/powerpack/widgets/counter/counter-widget-overview/' . $utm_suffix,
			),
			// Divider.
			'Divider'       => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/divider-widget-overview/' . $utm_suffix,
			),
			// Dual Heading.
			'Dual_Heading'      => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/dual-heading/dual-heading-widget-overview/' . $utm_suffix,
			),
			// Fancy Heading.
			'Fancy_Heading'     => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/fancy-heading-widget-overview/' . $utm_suffix,
				esc_html__( 'Watch Video Overview', 'powerpack' ) => 'https://www.youtube.com/watch?v=PxWWUTeW4dc&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj',
			),
			// Flip Box.
			'Flipbox'           => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/flip-box/flip-box-widget-overview/' . $utm_suffix,
			),
			// Fluent Forms.
			'Fluent_Forms'       => array(
				esc_html__( 'Watch Video Overview', 'powerpack' ) => 'https://www.youtube.com/watch?v=fvPnKNpsNyc&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj',
			),
			// Gravity Forms.
			'Gravity_Forms'     => array(
				esc_html__( 'Watch Video Overview', 'powerpack' ) => 'https://www.youtube.com/watch?v=fw47JcVDIpI&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj',
			),
			// Image Hotspots.
			'Hotspots'          => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/image-hotspots/image-hotspots-widget-overview/' . $utm_suffix,
			),
			// Icon List.
			'Icon_List'          => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/icon-list/icon-list-widget-overview/' . $utm_suffix,
			),
			// Image Accordion.
			'Image_Accordion'   => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/image-accordion-widget-overview/' . $utm_suffix,
			),
			// Image Comparison.
			'Image_Comparison'   => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/image-comparison/image-comparison-widget-overview/' . $utm_suffix,
			),
			// Info Box.
			'Info_Box'          => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/info-box/info-box-widget-overview/' . $utm_suffix,
			),
			// Info Box Carousel.
			'Info_Box_Carousel' => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/info-box-carousel/info-box-carousel-widget-overview/' . $utm_suffix,
			),
			// Info List.
			'Info_List'         => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/info-list/info-list-widget-overview/' . $utm_suffix,
			),
			// Info Table.
			'Info_Table'        => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/info-table-widget-overview/' . $utm_suffix,
			),
			// Instafeed.
			'Instafeed'         => array(
				esc_html__( 'Watch Video Overview', 'powerpack' ) => 'https://www.youtube.com/watch?v=33A9XL1twFE&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj',
				esc_html__( 'Widget Overview', 'powerpack' )      => 'https://powerpackelements.com/docs/powerpack/widgets/instagram-feed/instagram-feed-widget-overview/' . $utm_suffix,
				esc_html__( 'How to get Instagram Access Token?', 'powerpack' ) => 'https://powerpackelements.com/docs/create-instagram-access-token-for-instagram-feed-widget/' . $utm_suffix,
				esc_html__( 'How to set up Instagram Feed widget?', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/instagram-feed/elementor-instagram-widget-setup/' . $utm_suffix,
			),
			// Link Effects.
			'Link_Effects'          => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/link-effects-widget-overview/' . $utm_suffix,
			),
			// Logo Carousel.
			'Logo_Carousel'     => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/logo-carousel/logo-carousel-widget-overview/' . $utm_suffix,
			),
			// Logo Grid.
			'Logo_Grid'         => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/logo-grid/logo-grid-widget-overview/' . $utm_suffix,
			),
			// One Page Navigation.
			'Onepage_Nav'       => array(
				esc_html__( 'Watch Video Overview', 'powerpack' ) => 'https://www.youtube.com/watch?v=onZ0mnkRJiY&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj',
			),
			// Posts.
			'Posts'              => array(
				esc_html__( 'Watch Video Overview', 'powerpack' ) => 'https://www.youtube.com/watch?v=9-SF5w93Yr8&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj&index=14',
				esc_html__( 'Action Hooks for Post Widget', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/posts/actions-hooks-for-post-widget/' . $utm_suffix,
				esc_html__( 'How to Customize Query in Post Widget?', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/posts/how-to-customize-query-in-post-widget/' . $utm_suffix,
			),
			// Price Menu.
			'Price_Menu'        => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/price-menu/price-menu-widget-overview/' . $utm_suffix,
			),
			// Pricing Table.
			'Pricing_Table'     => array(
				esc_html__( 'Watch Video Overview', 'powerpack' ) => 'https://www.youtube.com/watch?v=cO-WFCHtwiM&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj',
				esc_html__( 'Widget Overview', 'powerpack' )      => 'https://powerpackelements.com/docs/powerpack/widgets/pricing-table/pricing-table-widget-overview/' . $utm_suffix,
			),
			// Promo Box.
			'Promo_Box'         => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/promo-box/promo-box-widget-overview/' . $utm_suffix,
			),
			// Random Image.
			'Random_Image'          => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/use-the-random-image-widget/' . $utm_suffix,
			),
			// Scroll Image.
			'Scroll_Image'      => array(
				esc_html__( 'Watch Video Overview', 'powerpack' ) => 'https://www.youtube.com/watch?v=eduATa8FPpU&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj',
			),
			// Team Member.
			'Team_Member'        => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/team-member-widget-overview/' . $utm_suffix,
			),
			// Team Member.
			'Team_Member_Carousel' => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/team-member-carousel-widget-overview/' . $utm_suffix,
			),
			// Twitter Grid.
			'Twitter_Grid'        => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/twitter-widget-overview/' . $utm_suffix,
				esc_html__( 'How to Create a Collection URL for Twitter Grid', 'powerpack' ) => 'https://powerpackelements.com/docs/how-to-create-a-collection-url-for-twitter-grid/' . $utm_suffix,
			),
			// Twitter Widget.
			'Twitter_Widget'        => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/twitter-widget-overview/' . $utm_suffix,
			),
			'WP_Forms'          => array(
				esc_html__( 'Widget Overview', 'powerpack' ) => 'https://powerpackelements.com/docs/powerpack/widgets/wpforms-styler/wpforms-styler-widget-overview/',
			),
		);

		return apply_filters( 'pp_elements_lite_help_links', self::$help_docs );
	}

	/**
	 * Get widget help links
	 *
	 * @param  object $widget widget object.
	 */
	public static function get_widget_help_links( $widget ) {
		$settings = \PowerpackElementsLite\Classes\PP_Admin_Settings::get_settings();

		if ( 'on' !== $settings['hide_support'] ) {
			$links = self::widgets_help_links();
		} else {
			$links = array();
		}

		if ( isset( $links[ $widget ] ) ) {
			return $links[ $widget ];
		}

		return '';
	}
}
