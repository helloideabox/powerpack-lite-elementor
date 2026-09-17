<?php
namespace PowerpackElementsLite\Classes;

use PowerpackElementsLite\Classes\PP_Helper;

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
	 * Extension documentation links.
	 *
	 * @var extension_docs
	 */
	public static $extension_docs = null;

	/**
	 * Get Widget List.
	 *
	 * A widget that cannot work until credentials are entered carries an
	 * 'integration' key naming the Integration panel section that holds them.
	 * The Elements screen turns it into a link, and reports whether the section
	 * has been filled in — see PP_Settings_REST_Controller::integration_groups().
	 *
	 * @since 2.1.0
	 *
	 * @return array The Widget List.
	 */
	public static function get_widget_info() {
		if ( null === self::$widget_info ) {
			/*
			 * Grouped by category, matching the paid edition. Both editions
			 * feed one settings screen, and it reads the library as
			 * category => widgets; keeping the two shapes identical is what
			 * lets the screen and its REST controller stay the same file in
			 * both. Widget keys and names are unchanged — those are what the
			 * enabled-widgets option stores.
			 *
			 * Widgets only the paid edition ships are listed here too, marked
			 * 'is_pro'. The settings screen shows them beside the free widgets
			 * they belong with, and the editor promotes them, but they are not
			 * shipped: PP_Helper::get_widgets_list() drops them before anything
			 * registers a widget or validates a save. One list rather than two
			 * is what keeps a title or an icon from drifting away from what the
			 * paid edition actually calls it.
			 */
			self::$widget_info = [
				'Content Elements'             => [
					'Buttons'           => [
						'name'     => 'pp-buttons',
						'title'    => esc_html__( 'Buttons', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'buttons' ],
						'icon'     => 'ppicon-multi-buttons power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/button-widget/',
						'docs'     => 'https://powerpackelements.com/doc-category/buttons/',
					],
					'Divider'           => [
						'name'     => 'pp-divider',
						'title'    => esc_html__( 'Divider', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'divider' ],
						'icon'     => 'ppicon-divider power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/divider/',
						'docs'     => 'https://powerpackelements.com/doc-category/divider/',
					],
					'Dual_Heading'      => [
						'name'     => 'pp-dual-heading',
						'title'    => esc_html__( 'Dual Heading', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'dual', 'heading' ],
						'icon'     => 'ppicon-dual-heading power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/dual-heading/',
						'docs'     => 'https://powerpackelements.com/doc-category/dual-heading/',
					],
					'Icon_List'         => [
						'name'     => 'pp-icon-list',
						'title'    => esc_html__( 'Icon List', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'icon', 'list' ],
						'icon'     => 'ppicon-icon-list power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/icon-list/',
						'docs'     => 'https://powerpackelements.com/doc-category/icon-list/',
					],
					'Info_Box'          => [
						'name'     => 'pp-info-box',
						'title'    => esc_html__( 'Info Box', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'info box' ],
						'icon'     => 'ppicon-info-box power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/info-box/',
						'docs'     => 'https://powerpackelements.com/doc-category/info-box/',
					],
					'Info_Box_Carousel' => [
						'name'     => 'pp-info-box-carousel',
						'title'    => esc_html__( 'Info Grid &amp; Carousel', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'info box', 'grid', 'slider' ],
						'icon'     => 'ppicon-info-box-carousel power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/info-box-carousel/',
						'docs'     => 'https://powerpackelements.com/doc-category/info-box-carousel/',
					],
					'Info_List'         => [
						'name'     => 'pp-info-list',
						'title'    => esc_html__( 'Info List', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'info' ],
						'icon'     => 'ppicon-info-list power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/info-list/',
						'docs'     => 'https://powerpackelements.com/doc-category/info-list/',
					],
					'Info_Table'        => [
						'name'     => 'pp-info-table',
						'title'    => esc_html__( 'Info Table', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'info' ],
						'icon'     => 'ppicon-info-table power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/info-table/',
						'docs'     => 'https://powerpackelements.com/doc-category/info-table/',
					],
					'Marquee'           => [
						'name'     => 'pp-marquee',
						'title'    => esc_html__( 'Marquee', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'marquee', 'image', 'text', 'slider' ],
						'icon'     => 'ppicon-marquee power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/marquee/',
						'docs'     => 'https://powerpackelements.com/doc-category/marquee/',
					],
					'Google_Maps'       => [
						'name'     => 'pp-google-maps',
						'title'    => esc_html__( 'Google Maps', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'google', 'maps' ],
						'icon'     => 'ppicon-map power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/google-map/',
						'is_pro'   => true,
					],
					'How_To'            => [
						'name'     => 'pp-how-to',
						'title'    => esc_html__( 'How To', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'how' ],
						'icon'     => 'ppicon-how-to power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/how-to/',
						'is_pro'   => true,
					],
					'Recipe'            => [
						'name'     => 'pp-recipe',
						'title'    => esc_html__( 'Recipe', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'dish' ],
						'icon'     => 'ppicon-recipe power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/recipe/',
						'is_pro'   => true,
					],
					'Table'             => [
						'name'     => 'pp-table',
						'title'    => esc_html__( 'Table', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'table', 'csv' ],
						'icon'     => 'ppicon-table power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/table/',
						'is_pro'   => true,
					],
					'Timeline'          => [
						'name'     => 'pp-timeline',
						'title'    => esc_html__( 'Timeline', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack' ],
						'icon'     => 'ppicon-timeline power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/timeline/',
						'is_pro'   => true,
					],
				],
				'Post Elements'                => [
					'Content_Ticker'  => [
						'name'     => 'pp-content-ticker',
						'title'    => esc_html__( 'Content Ticker', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'posts' ],
						'icon'     => 'ppicon-content-ticker power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/content-ticker/',
						'docs'     => 'https://powerpackelements.com/doc-category/content-ticker/',
					],
					'Event_Calendar'  => [
						'name'        => 'pp-event-calendar',
						'title'       => esc_html__( 'Event Calendar', 'powerpack-lite-for-elementor' ),
						'keywords'    => [ 'powerpack', 'calendar', 'event', 'events', 'schedule', 'agenda', 'fullcalendar' ],
						'icon'        => 'ppicon-calendar power-pack-admin-icon',
						'default_off' => true,
						'demo'        => 'https://powerpackelements.com/elementor-widgets/event-calendar/',
						'docs'        => 'https://powerpackelements.com/doc-category/event-calendar/',
					],
					'Posts'           => [
						'name'     => 'pp-posts',
						'title'    => esc_html__( 'Posts', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack' ],
						'icon'     => 'ppicon-posts-grid power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/posts/',
						'docs'     => 'https://powerpackelements.com/doc-category/posts/',
					],
					'Card_Slider'     => [
						'name'     => 'pp-card-slider',
						'title'    => esc_html__( 'Card Slider', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'posts', 'cpt', 'slider' ],
						'icon'     => 'ppicon-card-slider power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/card-slider/',
						'is_pro'   => true,
					],
					'Categories'      => [
						'name'     => 'pp-categories',
						'title'    => esc_html__( 'Categories', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'categories' ],
						'icon'     => 'ppicon-categories power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/categories/',
						'is_pro'   => true,
					],
					'Magazine_Slider' => [
						'name'     => 'pp-magazine-slider',
						'title'    => esc_html__( 'Magazine Slider', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'posts' ],
						'icon'     => 'ppicon-magazine-slider power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/magazine-slider/',
						'is_pro'   => true,
					],
					'Tiled_Posts'     => [
						'name'     => 'pp-tiled-posts',
						'title'    => esc_html__( 'Tiled Posts', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack' ],
						'icon'     => 'ppicon-tiled-post power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/tiled-post/',
						'is_pro'   => true,
					],
				],
				'Navigation Elements'          => [
					'Slide_Menu'        => [
						'name'        => 'pp-slide-menu',
						'title'       => esc_html__( 'Slide Menu', 'powerpack-lite-for-elementor' ),
						'keywords'    => [ 'powerpack', 'menu', 'navigation' ],
						'icon'        => 'ppicon-slide-menu power-pack-admin-icon',
						'default_off' => true,
						'demo'        => 'https://powerpackelements.com/elementor-widgets/slide-menu/',
						'docs'        => 'https://powerpackelements.com/doc-category/slide-menu/',
					],
					'Advanced_Menu'     => [
						'name'     => 'pp-advanced-menu',
						'title'    => esc_html__( 'Advanced Menu', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'menu', 'navigation' ],
						'icon'     => 'ppicon-advanced-menu power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/advanced-menu/',
						'is_pro'   => true,
					],
					'Breadcrumbs'       => [
						'name'     => 'pp-breadcrumbs',
						'title'    => esc_html__( 'Breadcrumbs', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'breadcrumbs' ],
						'icon'     => 'ppicon-breadcrumbs power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/breadcrumbs/',
						'is_pro'   => true,
					],
					'Offcanvas_Content' => [
						'name'     => 'pp-offcanvas-content',
						'title'    => esc_html__( 'Offcanvas Content', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'offcanvas', 'off canvas' ],
						'icon'     => 'ppicon-offcanvas-content power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/offcanvas-content/',
						'is_pro'   => true,
					],
					'Onepage_Nav'       => [
						'name'     => 'pp-one-page-nav',
						'title'    => esc_html__( 'One Page Navigation', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'one', 'page', 'dot' ],
						'icon'     => 'ppicon-page-navigation power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/one-page-navigation/',
						'is_pro'   => true,
					],
				],
				'Interactive Elements'         => [
					'Advanced_Accordion' => [
						'name'     => 'pp-advanced-accordion',
						'title'    => esc_html__( 'Advanced Accordion', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'accordion', 'advanced' ],
						'icon'     => 'ppicon-advanced-accordion power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/advanced-accordion/',
						'docs'     => 'https://powerpackelements.com/doc-category/advanced-accordion/',
					],
					'Content_Reveal'     => [
						'name'     => 'pp-content-reveal',
						'title'    => esc_html__( 'Content Reveal', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'unfold', 'read more', 'read less', 'more', 'less' ],
						'icon'     => 'ppicon-content-reveal power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/content-reveal/',
						'docs'     => 'https://powerpackelements.com/doc-category/content-reveal/',
					],
					'Advanced_Tabs'      => [
						'name'     => 'pp-advanced-tabs',
						'title'    => esc_html__( 'Advanced Tabs', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'tabs' ],
						'icon'     => 'ppicon-tabs power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/advanced-tab/',
						'is_pro'   => true,
					],
					'Faq'                => [
						'name'     => 'pp-faq',
						'title'    => esc_html__( 'FAQ', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'faq' ],
						'icon'     => 'ppicon-faq power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/faq/',
						'is_pro'   => true,
					],
					'Popup_Box'          => [
						'name'     => 'pp-modal-popup',
						'title'    => esc_html__( 'Popup Box', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'modal', 'popup' ],
						'icon'     => 'ppicon-popup-box power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/popup-box/',
						'is_pro'   => true,
					],
					'Toggle'             => [
						'name'     => 'pp-toggle',
						'title'    => esc_html__( 'Toggle', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'toggle', 'youtube', 'dailymotion' ],
						'icon'     => 'ppicon-content-toggle power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/content-toggle/',
						'is_pro'   => true,
					],
				],
				'Business Elements'            => [
					'Business_Hours'       => [
						'name'     => 'pp-business-hours',
						'title'    => esc_html__( 'Business Hours', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'business', 'hours' ],
						'icon'     => 'ppicon-business-hours power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/business-hours/',
						'docs'     => 'https://powerpackelements.com/doc-category/business-hours/',
					],
					'Price_Menu'           => [
						'name'     => 'pp-price-menu',
						'title'    => esc_html__( 'Price Menu', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'price' ],
						'icon'     => 'ppicon-pricing-menu power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/pricing-menu/',
						'docs'     => 'https://powerpackelements.com/doc-category/price-menu/',
					],
					'Pricing_Table'        => [
						'name'     => 'pp-pricing-table',
						'title'    => esc_html__( 'Pricing Table', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'price' ],
						'icon'     => 'ppicon-pricing-table power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/pricing-table/',
						'docs'     => 'https://powerpackelements.com/doc-category/pricing-table/',
					],
					'Promo_Box'            => [
						'name'     => 'pp-promo-box',
						'title'    => esc_html__( 'Promo Box', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'info' ],
						'icon'     => 'ppicon-promo-box power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/promo-box/',
						'docs'     => 'https://powerpackelements.com/doc-category/promo-box/',
					],
					'Team_Member'          => [
						'name'     => 'pp-team-member',
						'title'    => esc_html__( 'Team Member', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'team', 'member' ],
						'icon'     => 'ppicon-team-member power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/team-member/',
						'docs'     => 'https://powerpackelements.com/doc-category/team-member/',
					],
					'Team_Member_Carousel' => [
						'name'     => 'pp-team-member-carousel',
						'title'    => esc_html__( 'Team Member Carousel', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'team', 'member', 'carousel' ],
						'icon'     => 'ppicon-team-member-carousel power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/team-member-carousel/',
						'docs'     => 'https://powerpackelements.com/doc-category/team-member-carousel/',
					],
					'Coupons'              => [
						'name'     => 'pp-coupons',
						'title'    => esc_html__( 'Coupons', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'coupon' ],
						'icon'     => 'ppicon-coupons power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/coupon/',
						'is_pro'   => true,
					],
					'Review_Box'           => [
						'name'     => 'pp-review-box',
						'title'    => esc_html__( 'Review Box', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'image' ],
						'icon'     => 'ppicon-review-box power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/review-box/',
						'is_pro'   => true,
					],
					'Testimonials'         => [
						'name'     => 'pp-testimonials',
						'title'    => esc_html__( 'Testimonials', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'testimonials', 'reviews' ],
						'icon'     => 'ppicon-testimonial-carousel power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/testimonials/',
						'is_pro'   => true,
					],
				],
				'Media Elements'               => [
					'Image_Accordion'  => [
						'name'     => 'pp-image-accordion',
						'title'    => esc_html__( 'Image Accordion', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack' ],
						'icon'     => 'ppicon-image-accordion power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/image-accordion/',
						'docs'     => 'https://powerpackelements.com/doc-category/image-accordion/',
					],
					'Image_Comparison' => [
						'name'     => 'pp-image-comparison',
						'title'    => esc_html__( 'Image Comparison', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'image', 'comparison', 'before', 'after', 'slider' ],
						'icon'     => 'ppicon-image-comparison power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/image-comparison/',
						'docs'     => 'https://powerpackelements.com/doc-category/image-comparison/',
					],
					'Random_Image'     => [
						'name'     => 'pp-random-image',
						'title'    => esc_html__( 'Random Image', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'image' ],
						'icon'     => 'ppicon-random-image power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/random-image/',
						'docs'     => 'https://powerpackelements.com/doc-category/random-image/',
					],
					'Scroll_Image'     => [
						'name'     => 'pp-scroll-image',
						'title'    => esc_html__( 'Scroll Image', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'image' ],
						'icon'     => 'ppicon-scroll-image power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/scroll-image/',
						'docs'     => 'https://powerpackelements.com/doc-category/scroll-image/',
					],
					'Album'            => [
						'name'     => 'pp-album',
						'title'    => esc_html__( 'Album', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'album', 'gallery', 'lightbox' ],
						'icon'     => 'ppicon-album power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/album/',
						'is_pro'   => true,
					],
					'Devices'          => [
						'name'     => 'pp-devices',
						'title'    => esc_html__( 'Devices', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'devices' ],
						'icon'     => 'ppicon-device power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/devices/',
						'is_pro'   => true,
					],
					'Image_Gallery'    => [
						'name'     => 'pp-image-gallery',
						'title'    => esc_html__( 'Image Gallery', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'image', 'gallery' ],
						'icon'     => 'ppicon-image-gallery power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/image-gallery/',
						'is_pro'   => true,
					],
					'Image_Slider'     => [
						'name'     => 'pp-image-slider',
						'title'    => esc_html__( 'Image Slider', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'image', 'slider', 'slideshow', 'gallery', 'thumbnail', 'carousel' ],
						'icon'     => 'ppicon-gallery-slider power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/image-slider/',
						'is_pro'   => true,
					],
					'Showcase'         => [
						'name'     => 'pp-showcase',
						'title'    => esc_html__( 'Showcase', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'image', 'video', 'embed', 'youtube', 'vimeo', 'dailymotion', 'slider' ],
						'icon'     => 'ppicon-showcase power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/showcase-widget/',
						'is_pro'   => true,
					],
					'Tabbed_Gallery'   => [
						'name'     => 'pp-tabbed-gallery',
						'title'    => esc_html__( 'Tabbed Gallery', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'image', 'gallery', 'carousel', 'tab', 'slider' ],
						'icon'     => 'ppicon-tabbed-gallery power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/tabbed-gallery/',
						'is_pro'   => true,
					],
					'Video'            => [
						'name'     => 'pp-video',
						'title'    => esc_html__( 'Video', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'video', 'youtube', 'dailymotion' ],
						'icon'     => 'ppicon-video power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/video/',
						'is_pro'   => true,
					],
					'Video_Gallery'    => [
						'name'     => 'pp-video-gallery',
						'title'    => esc_html__( 'Video Gallery', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'video', 'youtube', 'dailymotion' ],
						'icon'     => 'ppicon-video-gallery power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/video-gallery/',
						'is_pro'   => true,
					],
				],
				'Creative Elements'            => [
					'Charts'             => [
						'name'     => 'pp-charts',
						'title'    => esc_html__( 'Charts', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'chart', 'graph' ],
						'icon'     => 'ppicon-advanced-charts power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/advanced-charts/',
						'docs'     => 'https://powerpackelements.com/doc-category/advanced-charts/',
					],
					'Counter'            => [
						'name'     => 'pp-counter',
						'title'    => esc_html__( 'Counter', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'counter' ],
						'icon'     => 'ppicon-counter power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/counter/',
						'docs'     => 'https://powerpackelements.com/doc-category/counter/',
					],
					'Fancy_Heading'      => [
						'name'     => 'pp-fancy-heading',
						'title'    => esc_html__( 'Fancy Heading', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'fancy', 'heading' ],
						'icon'     => 'ppicon-heading power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/fancy-heading/',
						'docs'     => 'https://powerpackelements.com/doc-category/fancy-heading/',
					],
					'Flipbox'            => [
						'name'     => 'pp-flipbox',
						'title'    => esc_html__( 'Flip Box', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'flip', 'box', 'flipbox' ],
						'icon'     => 'ppicon-flip-box power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/flip-box/',
						'docs'     => 'https://powerpackelements.com/doc-category/flip-box/',
					],
					'Hotspots'           => [
						'name'     => 'pp-image-hotspots',
						'title'    => esc_html__( 'Image Hotspots', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'image', 'hotspots' ],
						'icon'     => 'ppicon-image-hotspot power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/image-hotspot/',
						'docs'     => 'https://powerpackelements.com/doc-category/image-hotspots/',
					],
					'Interactive_Circle' => [
						'name'     => 'pp-interactive-circle',
						'title'    => esc_html__( 'Interactive Circle', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'interactive circle', 'circle' ],
						'icon'     => 'ppicon-interactive-circle power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/interactive-circle/',
						'docs'     => 'https://powerpackelements.com/doc-category/interactive-circle/',
					],
					'Link_Effects'       => [
						'name'     => 'pa-link-effects',
						'title'    => esc_html__( 'Link Effects', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack' ],
						'icon'     => 'ppicon-link-effects power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/link-effects/',
						'docs'     => 'https://powerpackelements.com/doc-category/link-effects/',
					],
					'Logo_Carousel'      => [
						'name'     => 'pp-logo-carousel',
						'title'    => esc_html__( 'Logo Carousel', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'logo', 'carousel', 'image' ],
						'icon'     => 'ppicon-logo-carousel power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/logo-carousel/',
						'docs'     => 'https://powerpackelements.com/doc-category/logo-carousel/',
					],
					'Logo_Grid'          => [
						'name'     => 'pp-logo-grid',
						'title'    => esc_html__( 'Logo Grid', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'logo', 'image' ],
						'icon'     => 'ppicon-logo-grid power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/logo-grid/',
						'docs'     => 'https://powerpackelements.com/doc-category/logo-grid/',
					],
					'Progress_Bar'       => [
						'name'     => 'pp-progress-bar',
						'title'    => esc_html__( 'Progress Bar', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'chart', 'counter' ],
						'icon'     => 'ppicon-progress-bar power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/progress-bar/',
						'docs'     => 'https://powerpackelements.com/doc-category/progress-bar/',
					],
					'Countdown'          => [
						'name'     => 'pp-countdown',
						'title'    => esc_html__( 'Countdown Timer', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'countdown', 'timer' ],
						'icon'     => 'ppicon-countdown power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/countdown/',
						'is_pro'   => true,
					],
				],
				'Social Media Elements'        => [
					'Instafeed'        => [
						'name'        => 'pp-instafeed',
						'title'       => esc_html__( 'Instagram Feed', 'powerpack-lite-for-elementor' ),
						'keywords'    => [ 'powerpack', 'instagram' ],
						'icon'        => 'ppicon-instagram-feed power-pack-admin-icon',
						'integration' => 'instagram-feed',
						'demo'        => 'https://powerpackelements.com/elementor-widgets/instagram-feed/',
						'docs'        => 'https://powerpackelements.com/doc-category/instagram-feed/',
					],
					'Twitter_Buttons'  => [
						'name'     => 'pp-twitter-buttons',
						'title'    => esc_html__( 'Twitter Buttons', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack' ],
						'icon'     => 'ppicon-twitter-buttons power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/twitter-widget/',
						'docs'     => 'https://powerpackelements.com/doc-category/twitter/',
					],
					'Twitter_Grid'     => [
						'name'       => 'pp-twitter-grid',
						'title'      => esc_html__( 'Twitter Grid', 'powerpack-lite-for-elementor' ),
						'keywords'   => [ 'powerpack' ],
						'icon'       => 'ppicon-twitter-grid power-pack-admin-icon',
						'deprecated' => true,
						'demo'       => 'https://powerpackelements.com/elementor-widgets/twitter-widget/',
						'docs'       => 'https://powerpackelements.com/doc-category/twitter/',
					],
					'Twitter_Timeline' => [
						'name'     => 'pp-twitter-timeline',
						'title'    => esc_html__( 'Twitter Timeline', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack' ],
						'icon'     => 'ppicon-twitter-timeline power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/twitter-widget/',
						'docs'     => 'https://powerpackelements.com/doc-category/twitter/',
					],
					'Twitter_Tweet'    => [
						'name'     => 'pp-twitter-tweet',
						'title'    => esc_html__( 'Twitter Tweet', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack' ],
						'icon'     => 'ppicon-twitter-tweet power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/twitter-widget/',
						'docs'     => 'https://powerpackelements.com/doc-category/twitter/',
					],
				],
				'Form Elements'                => [
					'Contact_Form_7'   => [
						'name'     => 'pp-contact-form-7',
						'title'    => esc_html__( 'Contact Form 7', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'contact', 'form' ],
						'icon'     => 'ppicon-contact-form power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/contact-forms-7/',
						'docs'     => 'https://powerpackelements.com/doc-category/contact-form-7-styler/',
					],
					'Fluent_Forms'     => [
						'name'     => 'pp-fluent-forms',
						'title'    => esc_html__( 'Fluent Forms', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'contact', 'form' ],
						'icon'     => 'ppicon-contact-form power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/wp-fluent-forms/',
						'docs'     => 'https://powerpackelements.com/doc-category/fluent-forms-styler/',
					],
					'Formidable_Forms' => [
						'name'     => 'pp-formidable-forms',
						'title'    => esc_html__( 'Formidable Forms', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'contact', 'form' ],
						'icon'     => 'ppicon-contact-form power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/formidable-forms/',
						'docs'     => 'https://powerpackelements.com/doc-category/formidable-forms-styler/',
					],
					'Gravity_Forms'    => [
						'name'     => 'pp-gravity-forms',
						'title'    => esc_html__( 'Gravity Forms', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'contact', 'form' ],
						'icon'     => 'ppicon-contact-form power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/gravity-forms/',
						'docs'     => 'https://powerpackelements.com/doc-category/gravity-forms-styler/',
					],
					'Ninja_Forms'      => [
						'name'     => 'pp-ninja-forms',
						'title'    => esc_html__( 'Ninja Forms', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'contact', 'form' ],
						'icon'     => 'ppicon-contact-form power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/ninja-forms/',
						'docs'     => 'https://powerpackelements.com/doc-category/ninja-forms-styler/',
					],
					'WP_Forms'         => [
						'name'     => 'pp-wpforms',
						'title'    => esc_html__( 'WP Forms', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'contact', 'form' ],
						'icon'     => 'ppicon-contact-form power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/elementor-widgets/wpforms/',
						'docs'     => 'https://powerpackelements.com/doc-category/wpforms-styler/',
					],
				],
				'WooCommerce Elements'         => [
					'Woo_Add_To_Cart'    => [
						'name'     => 'pp-woo-add-to-cart',
						'title'    => esc_html__( 'Woo - Add To Cart', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-woo-add-to-cart power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-elementor-widgets/add-to-cart-button/',
						'is_pro'   => true,
					],
					'Woo_Cart'           => [
						'name'     => 'pp-woo-cart',
						'title'    => esc_html__( 'Woo - Cart', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-woo-cart power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-elementor-widgets/cart-widget/',
						'is_pro'   => true,
					],
					'Woo_Categories'     => [
						'name'     => 'pp-woo-categories',
						'title'    => esc_html__( 'Woo - Categories', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce', 'category' ],
						'icon'     => 'ppicon-woo-categories power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-elementor-widgets/categories-widget/',
						'is_pro'   => true,
					],
					'Woo_Checkout'       => [
						'name'     => 'pp-woo-checkout',
						'title'    => esc_html__( 'Woo - Checkout', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-woo-checkout power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-elementor-widgets/checkout-widget/',
						'is_pro'   => true,
					],
					'Woo_Mini_Cart'      => [
						'name'     => 'pp-woo-mini-cart',
						'title'    => esc_html__( 'Woo - Mini Cart', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-mini-cart power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-elementor-widgets/mini-cart-widget/',
						'is_pro'   => true,
					],
					'Woo_Offcanvas_Cart' => [
						'name'     => 'pp-woo-offcanvas-cart',
						'title'    => esc_html__( 'Woo - Off Canvas Cart', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce', 'offcanvas' ],
						'icon'     => 'ppicon-offcanvas-cart power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-elementor-widgets/off-canvas-cart-widget/',
						'is_pro'   => true,
					],
					'Woo_Products'       => [
						'name'     => 'pp-woo-products',
						'title'    => esc_html__( 'Woo - Products', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-woo-products power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-elementor-widgets/product-grid/',
						'is_pro'   => true,
					],
					'Woo_My_Account'     => [
						'name'     => 'pp-woo-my-account',
						'title'    => esc_html__( 'Woo - My Account', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-my-account power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-elementor-widgets/my-account-widget/',
						'is_pro'   => true,
					],
					'Woo_Single_Product' => [
						'name'     => 'pp-woo-single-product',
						'title'    => esc_html__( 'Woo - Single Product', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-woo-single-product power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-elementor-widgets/single-product/',
						'is_pro'   => true,
					],
				],
				'WooCommerce Builder Elements' => [
					'Woo_Product_Tabs'              => [
						'name'     => 'pp-woo-product-tabs',
						'title'    => esc_html__( 'Woo - Product Tabs', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-product-tabs power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Product_Title'             => [
						'name'     => 'pp-woo-product-title',
						'title'    => esc_html__( 'Woo - Product Title', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-product-title power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Product_Meta'              => [
						'name'     => 'pp-woo-product-meta',
						'title'    => esc_html__( 'Woo - Product Meta', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-product-meta power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Product_Price'             => [
						'name'     => 'pp-woo-product-price',
						'title'    => esc_html__( 'Woo - Product Price', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-product-price power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Product_Rating'            => [
						'name'     => 'pp-woo-product-rating',
						'title'    => esc_html__( 'Woo - Product Rating', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-product-rating power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Product_Stock'             => [
						'name'     => 'pp-woo-product-stock',
						'title'    => esc_html__( 'Woo - Product Stock', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-product-stock power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Product_Short_Description' => [
						'name'     => 'pp-woo-product-short-description',
						'title'    => esc_html__( 'Woo - Product Short Description', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-product-short-desc power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Product_Content'           => [
						'name'     => 'pp-woo-product-content',
						'title'    => esc_html__( 'Woo - Product Content', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-product-content power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Product_Images'            => [
						'name'     => 'pp-woo-product-images',
						'title'    => esc_html__( 'Woo - Product Images', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-product-images power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Product_Reviews'           => [
						'name'     => 'pp-woo-product-reviews',
						'title'    => esc_html__( 'Woo - Product Reviews', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-product-reviews power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Product_Upsell'            => [
						'name'     => 'pp-woo-product-upsell',
						'title'    => esc_html__( 'Woo - Product Upsell', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-product-upsell power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Add_To_Cart_Notification'  => [
						'name'     => 'pp-woo-add-to-cart-notification',
						'title'    => esc_html__( 'Woo - Add to Cart Notification', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-woo-add-to-cart-notification power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
					'Woo_Archive_Description'       => [
						'name'     => 'pp-woo-archive-description',
						'title'    => esc_html__( 'Woo - Archive Description', 'powerpack-lite-for-elementor' ),
						'keywords' => [ 'powerpack', 'woocommerce' ],
						'icon'     => 'ppicon-woo-archive-description power-pack-admin-icon',
						'demo'     => 'https://powerpackelements.com/woocommerce-builder/',
						'is_pro'   => true,
					],
				],
			];
		}

		return PP_Helper::apply_deprecated_filter(
			'pp_elements_widget_info',
			'powerpack_elements_widget_info',
			self::$widget_info,
			[],
			'2.9.10'
		);
	}

	/**
	 * Get how the widget groups are presented on the Elements screen.
	 *
	 * The keys of get_widget_info() stay English because they are identifiers:
	 * the powerpack_elements_widget_info filter adds widgets under them, and
	 * the Elements screen builds its anchors from them. What the screen shows,
	 * and in what order, comes from here instead.
	 *
	 * 'title' heads the group's section. 'short' goes in the category list,
	 * which sits in a panel already titled Elements, so it leaves that word out.
	 *
	 * 'priority' orders the groups, lowest first, by how often a typical site
	 * reaches for them: everyday building blocks at the top, groups that only
	 * apply to some sites (forms, stores) or are mostly retired (the Twitter
	 * widgets) at the bottom. The order is the same in every language. Leave
	 * gaps so a group can be slotted in without renumbering.
	 *
	 * A group with no entry here, such as one added through the filter, is
	 * shown under its key after all the groups listed here.
	 *
	 * @since x.x.x
	 *
	 * @return array Group key => [ 'title' => string, 'short' => string, 'priority' => int ].
	 */
	public static function get_widget_groups() {
		$groups = [
			'Content Elements'             => [
				'title'    => __( 'Content Elements', 'powerpack-lite-for-elementor' ),
				'short'    => __( 'Content', 'powerpack-lite-for-elementor' ),
				'priority' => 10,
			],
			'Post Elements'                => [
				'title'    => __( 'Post Elements', 'powerpack-lite-for-elementor' ),
				'short'    => __( 'Post', 'powerpack-lite-for-elementor' ),
				'priority' => 20,
			],
			'Business Elements'            => [
				'title'    => __( 'Business Elements', 'powerpack-lite-for-elementor' ),
				'short'    => __( 'Business', 'powerpack-lite-for-elementor' ),
				'priority' => 30,
			],
			'Media Elements'               => [
				'title'    => __( 'Media Elements', 'powerpack-lite-for-elementor' ),
				'short'    => __( 'Media', 'powerpack-lite-for-elementor' ),
				'priority' => 40,
			],
			'Interactive Elements'         => [
				'title'    => __( 'Interactive Elements', 'powerpack-lite-for-elementor' ),
				'short'    => __( 'Interactive', 'powerpack-lite-for-elementor' ),
				'priority' => 50,
			],
			'Creative Elements'            => [
				'title'    => __( 'Creative Elements', 'powerpack-lite-for-elementor' ),
				'short'    => __( 'Creative', 'powerpack-lite-for-elementor' ),
				'priority' => 60,
			],
			'Navigation Elements'          => [
				'title'    => __( 'Navigation Elements', 'powerpack-lite-for-elementor' ),
				'short'    => __( 'Navigation', 'powerpack-lite-for-elementor' ),
				'priority' => 70,
			],
			'Form Elements'                => [
				'title'    => __( 'Form Elements', 'powerpack-lite-for-elementor' ),
				'short'    => __( 'Form', 'powerpack-lite-for-elementor' ),
				'priority' => 80,
			],
			'WooCommerce Elements'         => [
				'title'    => __( 'WooCommerce Elements', 'powerpack-lite-for-elementor' ),
				'short'    => __( 'WooCommerce', 'powerpack-lite-for-elementor' ),
				'priority' => 90,
			],
			'WooCommerce Builder Elements' => [
				'title'    => __( 'WooCommerce Builder Elements', 'powerpack-lite-for-elementor' ),
				'short'    => __( 'WooCommerce Builder', 'powerpack-lite-for-elementor' ),
				'priority' => 100,
			],
			'Social Media Elements'        => [
				'title'    => __( 'Social Media Elements', 'powerpack-lite-for-elementor' ),
				'short'    => __( 'Social Media', 'powerpack-lite-for-elementor' ),
				'priority' => 110,
			],
		];

		/**
		 * Filters how the widget groups are presented on the Elements screen.
		 *
		 * @since x.x.x
		 *
		 * @param array $groups Group key => [ 'title' => string, 'short' => string, 'priority' => int ].
		 */
		return apply_filters( 'powerpack_elements_widget_groups', $groups );
	}

	/**
	 * The widgets only the paid edition ships.
	 *
	 * Derived from get_widget_info() rather than listed separately, so a title
	 * or an icon cannot say one thing here and another there. Returned flat and
	 * keyed by widget, which is the shape Elementor's promotion panel wants.
	 *
	 * @since 1.2.9.4
	 *
	 * @return array The Widget List.
	 */
	public static function get_pro_widgets() {
		if ( null === self::$pro_widgets ) {
			$pro_widgets = [];

			foreach ( self::get_widget_info() as $category => $widgets ) {
				foreach ( $widgets as $key => $widget ) {
					if ( empty( $widget['is_pro'] ) ) {
						continue;
					}

					// The Elementor panel category the widget is promoted under. The
					// promo panel runs JSON.parse() over this one.
					$widget['categories'] = wp_json_encode( [ 'powerpack-elements' ] );

					// This plugin's own grouping, which the catalogue carries as
					// the key each widget sits under.
					$widget['category'] = $category;

					$pro_widgets[ $key ] = $widget;
				}
			}

			self::$pro_widgets = $pro_widgets;
		}

		return PP_Helper::apply_deprecated_filter(
			'pp_elements_lite_pro_widgets',
			'powerpack_elements_pro_widgets',
			self::$pro_widgets,
			[],
			'2.9.10'
		);
	}

	/**
	 * Get the documentation URL for each extension.
	 *
	 * Keyed the way powerpack_elements_lite_get_extensions() keys its list. An
	 * extension registered through a filter has no entry here and simply shows
	 * no link, so the two lists never have to be kept in step.
	 *
	 * @since 3.0.0
	 *
	 * @return array Extension slug => documentation URL.
	 */
	public static function get_extension_docs() {
		if ( null === self::$extension_docs ) {
			$base = 'https://powerpackelements.com/doc-category/';

			// Doc category slugs, which do not all match the extension slug.
			$slugs = [
				'pp-display-conditions'           => 'display-conditions',
				'pp-wrapper-link'                 => 'wrapper-link',
				'pp-animated-gradient-background' => 'animated-gradient-background',
				'pp-custom-cursor'                => 'custom-cursor',

				// Paid edition only. Listed so the preview cards can link to
				// what the extension does before anyone buys it.
				'pp-background-effects'           => 'background-effects',
				'pp-tooltips'                     => 'tooltip',
				'pp-presets-style'                => 'presets',
				'pp-magic-wand'                   => 'magic-wand',
			];

			self::$extension_docs = [];

			foreach ( $slugs as $extension => $slug ) {
				self::$extension_docs[ $extension ] = $base . $slug . '/';
			}
		}

		return apply_filters( 'pp_elements_lite_extension_docs', self::$extension_docs );
	}

	/**
	 * The extensions only the paid edition ships.
	 *
	 * Deliberately kept out of powerpack_elements_lite_get_extensions(), which
	 * is both the list of what this plugin offers and the allowlist a save is
	 * validated against. A name that is not in it can be shown but never
	 * stored, which is what makes listing these safe.
	 *
	 * The widget catalogue solves the same problem with an 'is_pro' flag on one
	 * list, because widgets carry a row of their own. Extensions are a plain
	 * name => title map shared with the registry, so a second list is the
	 * smaller change.
	 *
	 * @since 3.0.0
	 *
	 * @return array Extension slug => title.
	 */
	public static function get_pro_extensions() {
		return [
			'pp-background-effects' => esc_html__( 'Background Effects', 'powerpack-lite-for-elementor' ),
			'pp-tooltips'           => esc_html__( 'Tooltips', 'powerpack-lite-for-elementor' ),
			'pp-presets-style'      => esc_html__( 'Presets', 'powerpack-lite-for-elementor' ),
			'pp-magic-wand'         => esc_html__( 'Magic Wand (Copy/Paste)', 'powerpack-lite-for-elementor' ),
		];
	}

}
