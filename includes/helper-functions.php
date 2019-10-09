<?php
// Get all elementor page templates
function pp_elements_lite_get_page_templates( $type = '' ) {
	$args = [
		'post_type'         => 'elementor_library',
		'posts_per_page'    => -1,
	];
	
	if ( $type ) {
		$args['tax_query'] = [
			[
				'taxonomy' => 'elementor_library_type',
				'field'    => 'slug',
				'terms' => $type,
			]
		];
	}
	
	$page_templates = get_posts( $args );

	$options = array();

	if ( ! empty( $page_templates ) && ! is_wp_error( $page_templates ) ){
		foreach ( $page_templates as $post ) {
			$options[ $post->ID ] = $post->post_title;
		}
	}
	return $options;
}

// Get all forms of Contact Form 7 plugin
function pp_elements_lite_get_contact_form_7_forms() {
	if ( function_exists( 'wpcf7' ) ) {
		$options = array();

		$args = array(
			'post_type'         => 'wpcf7_contact_form',
			'posts_per_page'    => -1
		);

		$contact_forms = get_posts( $args );

		if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

		$i = 0;

		foreach ( $contact_forms as $post ) {	
			if ( $i == 0 ) {
				$options[0] = esc_html__( 'Select a Contact form', 'power-pack' );
			}
			$options[ $post->ID ] = $post->post_title;
			$i++;
		}
		}
	} else {
		$options = array();
	}

	return $options;
}

// Get all forms of Gravity Forms plugin
function pp_elements_lite_get_gravity_forms() {
	if ( class_exists( 'GFCommon' ) ) {
		$options = array();

		$contact_forms = RGFormsModel::get_forms( null, 'title' );

		if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

			$i = 0;

			foreach ( $contact_forms as $form ) {	
				if ( $i == 0 ) {
					$options[0] = esc_html__( 'Select a Contact form', 'power-pack' );
				}
				$options[ $form->id ] = $form->title;
				$i++;
			}
		}
	} else {
		$options = array();
	}

	return $options;
}

// Get all forms of Ninja Forms plugin
function pp_elements_lite_get_ninja_forms() {
	if ( class_exists( 'Ninja_Forms' ) ) {
		$options = array();

		$contact_forms = Ninja_Forms()->form()->get_forms();

		if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

			$i = 0;

			foreach ( $contact_forms as $form ) {	
				if ( $i == 0 ) {
					$options[0] = esc_html__( 'Select a Contact form', 'power-pack' );
				}
				$options[ $form->get_id() ] = $form->get_setting( 'title' );
				$i++;
			}
		}
	} else {
		$options = array();
	}

	return $options;
}

// Get all forms of Caldera plugin
function pp_elements_lite_get_caldera_forms() {
	if ( class_exists( 'Caldera_Forms' ) ) {
		$options = array();

		$contact_forms = Caldera_Forms_Forms::get_forms( true, true );

		if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

		$i = 0;

		foreach ( $contact_forms as $form ) {	
			if ( $i == 0 ) {
				$options[0] = esc_html__( 'Select a Contact form', 'power-pack' );
			}
			$options[ $form['ID'] ] = $form['name'];
			$i++;
		}
		}
	} else {
		$options = array();
	}

	return $options;
}

// Get all forms of WPForms plugin
function pp_elements_lite_get_wpforms_forms() {
	if ( class_exists( 'WPForms' ) ) {
		$options = array();

		$args = array(
			'post_type'         => 'wpforms',
			'posts_per_page'    => -1
		);

		$contact_forms = get_posts( $args );

		if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {

		$i = 0;

		foreach ( $contact_forms as $post ) {	
			if ( $i == 0 ) {
				$options[0] = esc_html__( 'Select a Contact form', 'power-pack' );
			}
			$options[ $post->ID ] = $post->post_title;
			$i++;
		}
		}
	} else {
		$options = array();
	}

	return $options;
}

// Get categories
function pp_elements_lite_get_post_categories() {

	$options = array();
	
	$terms = get_terms( array( 
		'taxonomy'      => 'category',
		'hide_empty'    => true,
	));

	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = $term->name;
		}
	}

	return $options;
}

// Get Post Types
function pp_elements_lite_get_post_types() {

	$pp_post_types = get_post_types( array(
		'public'            => true,
		'show_in_nav_menus' => true
	) );

	return $pp_post_types;
}

// Get all Authors
function pp_elements_lite_get_auhtors() {

	$options = array();

	$users = get_users();

	foreach ( $users as $user ) {
		$options[ $user->ID ] = $user->display_name;
	}

	return $options;
}

// Get all Authors
function pp_elements_lite_get_tags() {

	$options = array();

	$tags = get_tags();

	foreach ( $tags as $tag ) {
		$options[ $tag->term_id ] = $tag->name;
	}

	return $options;
}

// Get all Posts
function pp_elements_lite_get_posts() {

	$post_list = get_posts( array(
		'post_type'         => 'post',
		'orderby'           => 'date',
		'order'             => 'DESC',
		'posts_per_page'    => -1,
	) );

	$posts = array();

	if ( ! empty( $post_list ) && ! is_wp_error( $post_list ) ) {
		foreach ( $post_list as $post ) {
		   $posts[ $post->ID ] = $post->post_title;
		}
	}

	return $posts;
}

// Custom Excerpt
function pp_elements_lite_custom_excerpt( $limit = '' ) {
	$excerpt = explode(' ', get_the_excerpt(), $limit);
	if ( count( $excerpt ) >= $limit ) {
		array_pop( $excerpt );
		$excerpt = implode( " ", $excerpt ).'...';
	} else {
		$excerpt = implode( " ", $excerpt );
	}
	$excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );
	return $excerpt;
}
add_filter( 'get_the_excerpt', 'do_shortcode' );

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( class_exists( 'WooCommerce' ) || is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

    // Get all Products
    function pp_elements_lite_get_products() {

		$post_list = get_posts( array(
			'post_type'         => 'product',
			'orderby'           => 'date',
			'order'             => 'DESC',
			'posts_per_page'    => -1,
		) );

		$posts = array();

		if ( ! empty( $post_list ) && ! is_wp_error( $post_list ) ) {
			foreach ( $post_list as $post ) {
			   $posts[ $post->ID ] = $post->post_title;
			}
		}

		return $posts;
	}
    
    // Woocommerce - Get product categories
    function pp_elements_lite_get_product_categories() {

		$options = array();

		$terms = get_terms( array( 
			'taxonomy'      => 'product_cat',
			'hide_empty'    => true,
		));

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$options[ $term->term_id ] = $term->name;
			}
		}

		return $options;
	}

    // WooCommerce - Get product tags
    function pp_elements_lite_product_get_tags() {

		$options = array();

		$tags = get_terms( 'product_tag' );

		if ( ! empty( $tags ) && ! is_wp_error( $tags ) ){
			foreach ( $tags as $tag ) {
				$options[ $tag->term_id ] = $tag->name;
			}
		}

		return $options;
	}
}
	
	function pp_elements_lite_get_modules() {
		$modules = [
			'pp-advanced-accordion'     => [
				'title'	=>	esc_html__('Advanced Accordion', 'powerpack'),
				'demo'	=>	'advanced-accordion',
				'icon'	=>	'ppicon-advanced-accordion',
				'package'	=>	'free',
				'category'	=>	'creative'
			],
			'pp-link-effects'           => [
				'title'	=>	esc_html__('Link Effects', 'power-pack'),
				'demo'	=>	'link-effects',
				'icon'	=>	'ppicon-link-effects',
				'package'	=>	'free',
				'category'	=>	'creative'
			],
			'pp-divider'                => [
				'title'	=>	esc_html__('Divider', 'power-pack'),
				'demo'	=>	'divider',
				'icon'	=>	'ppicon-divider',
				'package'	=>	'free',
				'category'	=>	'creative'
			],
			'pp-flipbox'                => [
				'title'	=>	esc_html__('Flipbox', 'power-pack'),
				'demo'	=>	'flip-box',
				'icon'	=>	'ppicon-flip-box',
				'package'	=>	'free',
				'category'	=>	'creative'
			],
			'pp-image-accordion'        => [
				'title'	=>	esc_html__('Image Accordion', 'powerpack'),
				'demo'	=>	'image-accordion',
				'package'	=>	'free',
				'category'	=>	'creative'
			],
			'pp-info-box'               => [
				'title'	=>	esc_html__('Info Box', 'power-pack'),
				'demo'	=>	'info-box',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-info-box-carousel'      => [
				'title'	=>	esc_html__('Info Box Carousel', 'power-pack'),
				'demo'	=>	'info-box-carousel',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-info-list'              => [
				'title'	=>	esc_html__('Info List', 'power-pack'),
				'demo'	=>	'info-list',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-info-table'             => [
				'title'	=>	esc_html__('Info Table', 'power-pack'),
				'demo'	=>	'info-table',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-pricing-table'          => [
				'title'	=>	esc_html__('Pricing Table', 'power-pack'),
				'demo'	=>	'pricing-table',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-price-menu'             => [
				'title'	=>	esc_html__('Price Menu', 'power-pack'),
				'demo'	=>	'pricing-menu',
				'icon'	=>	'ppicon-pricing-menu',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-business-hours'         => [
				'title'	=>	esc_html__('Businsess Hours', 'power-pack'),
				'demo'	=>	'business-hours',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-team-member'            => [
				'title'	=>	esc_html__('Team Member', 'power-pack'),
				'demo'	=>	'team-member',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-team-member-carousel'   => [
				'title'	=>	esc_html__('Team Member Carousel', 'power-pack'),
				'demo'	=>	'team-member-carousel',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-counter'                => [
				'title'	=>	esc_html__('Counter', 'power-pack'),
				'demo'	=>	'counter',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-hotspots'               => [
				'title'	=>	esc_html__('Image Hotspots', 'power-pack'),
				'demo'	=>	'image-hotspot',
				'icon'	=>	'ppicon-image-hotspot',
				'package'	=>	'free',

'category'	=>	''			],
			'pp-icon-list'              => [
				'title'	=>	esc_html__('Icon List', 'power-pack'),
				'demo'	=>	'icon-list',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-dual-heading'           => [
				'title'	=>	esc_html__('Dual Heading', 'power-pack'),
				'demo'	=>	'dual-heading',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-promo-box'              => [
				'title'	=>	esc_html__('Promo Box', 'power-pack'),
				'demo'	=>	'promo-box',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-logo-carousel'          => [
				'title'	=>	esc_html__('Logo Carousel', 'power-pack'),
				'demo'	=>	'logo-carousel',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-logo-grid'              => [
				'title'	=>	esc_html__('Logo Grid', 'power-pack'),
				'demo'	=>	'logo-grid',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-image-comparison'       => [
				'title'	=>	esc_html__('Image Comparison', 'power-pack'),
				'demo'	=>	'image-comparison',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-instafeed'              => [
				'title'	=>	esc_html__('Instagram Feed', 'power-pack'),
				'demo'	=>	'instagram-feed',
				'icon'	=>	'ppicon-instagram-feed',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-content-ticker'         => [
				'title'	=>	esc_html__('Content Ticker', 'power-pack'),
				'demo'	=>	'content-ticker',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-scroll-image'           => [
				'title'	=>	esc_html__('Scroll Image', 'powerpack'),
				'demo'	=>	'scroll-image',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-buttons'				=> [
				'title'	=>	esc_html__('Buttons', 'powerpack'),
				'demo'	=>	'button-widget',
				'icon'	=>	'ppicon-multi-buttons',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-twitter-buttons'        => [
				'title'	=>	esc_html__('Twitter Buttons', 'powerpack'),
				'demo'	=>	'twitter-widget',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-twitter-grid'           => [
				'title'	=>	esc_html__('Twitter Grid', 'powerpack'),
				'demo'	=>	'twitter-widget',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-twitter-timeline'       => [
				'title'	=>	esc_html__('Twitter Timeline', 'powerpack'),
				'demo'	=>	'twitter-widget',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-twitter-tweet'          => [
				'title'	=>	esc_html__('Twitter Tweet', 'powerpack'),
				'demo'	=>	'twitter-widget/',
				'package'	=>	'free',
				'category'	=>	''
			],
			'pp-fancy-heading'			=> [
				'title'	=>	esc_html__('Fancy Heading', 'powerpack'),
				'demo'	=>	'fancy-heading',
				'icon'	=>	'ppicon-heading',
				'package'	=>	'free',
				'category'	=>	''
			]
		];

    // Contact Form 7
    if ( function_exists( 'wpcf7' ) ) {
        $modules['pp-contact-form-7']['title'] = esc_html__('Contact Form 7', 'power-pack');
		$modules['pp-contact-form-7']['demo'] = 'https://powerpackelements.com/demo/contact-form/';
		$modules['pp-contact-form-7']['package'] = 'free';
    }
    
    // Gravity Forms
    if ( class_exists( 'GFCommon' ) ) {
		$modules['pp-gravity-forms']['title']	=	esc_html__('Gravity Forms', 'power-pack');
		$modules['pp-gravity-forms']['demo']	=	'https://powerpackelements.com/demo/contact-form/';
		$modules['pp-gravity-forms']['icon']	=	'ppicon-contact-form';
		$modules['pp-gravity-forms']['package'] = 'free';
    }
    
    // Ninja Forms
    if ( class_exists( 'Ninja_Forms' ) ) {
        $modules['pp-ninja-forms']['title'] = esc_html__('Ninja Forms', 'power-pack');
		$modules['pp-ninja-forms']['demo'] = 'https://powerpackelements.com/demo/contact-form/';
		$modules['pp-ninja-forms']['package'] = 'free';
    }
    
    // Caldera Forms
    if ( class_exists( 'Caldera_Forms' ) ) {
        $modules['pp-caldera-forms']['title'] = esc_html__('Caldera Forms', 'power-pack');
		$modules['pp-caldera-forms']['demo'] = 'https://powerpackelements.com/demo/contact-form/';
		$modules['pp-caldera-forms']['package'] = 'free';
    }
    
    // WPForms
    if ( function_exists( 'wpforms' ) ) {
        $modules['pp-wpforms']['title'] = esc_html__('WPForms', 'power-pack');
		$modules['pp-wpforms']['demo'] = 'https://powerpackelements.com/demo/contact-form/';
		$modules['pp-wpforms']['package'] = 'free';
    }

    ksort($modules);

    return $modules;
}

function pp_elements_pro_get_modules() {

	$pro_modules = [
		
			'pp-recipe'                 =>	[
				'title'		=>	 __('Recipe', 'powerpack'),
				'demo'		=>	'recipe',
				'package'	=>	'pro'
			],
			'pp-tiled-posts'            =>	[
				'title'		=>	 __('Tiled Posts', 'powerpack'),
				'demo'		=>	'tiled-post',
				'package'	=>	'pro'
			],
			'pp-posts'					=>	[
				'title'		=>	__('Posts', 'powerpack'),
				'demo'		=>	'posts',
				'package'	=>	'pro'
			],
			'pp-modal-popup'            =>	[
				'title'		=>	__('Modal Popup', 'powerpack'),
				'demo'		=>	'popup-box',
				'package'	=>	'pro'
			],
			'pp-onepage-nav'            =>	[
				'title'		=>	__('One Page Navigation', 'powerpack'),
				'demo'		=>	'one-page-navigation',
				'package'	=>	'pro'
			],
			'pp-table'                  =>	[
				'title'		=>	__('Table', 'powerpack'),
				'demo'		=>	'table',
				'package'	=>	'pro'
			],
			'pp-toggle'                 =>	[
				'title'		=>	__('Toggle', 'powerpack'),
				'demo'		=>	'content-toggle',
				'package'	=>	'pro'
			],
			'pp-google-maps'            =>	[
				'title'		=>	__('Google Maps', 'powerpack'),
				'demo'		=>	'google-map',
				'package'	=>	'pro'
			],
			'pp-review-box'             =>	[
				'title'		=>	__('Review Box', 'powerpack'),
				'demo'		=>	'review-box',
				'package'	=>	'pro'
			],
			'pp-countdown'            	=>	[
				'title'		=>	__('Countdown', 'powerpack'),
				'demo'		=>	'countdown',
				'package'	=> 'pro'
			],
			'pp-advanced-tabs'          =>	[
				'title'		=>	__('Advanced Tabs', 'powerpack'),
				'demo'		=>	'advanced-tab',
				'package'	=>	'pro'
			],
			'pp-image-gallery'          =>	[
				'title'		=>	__('Image Gallery', 'powerpack'),
				'demo'		=>	'image-gallery',
				'package'	=>	'pro'
			],
			'pp-image-slider'           =>	[
				'title'		=>	__('Image Slider', 'powerpack'),
				'demo'		=>	'image-slider',
				'package'	=>	'pro'
			],
			'pp-advanced-menu'          =>	[
				'title'		=>	__('Advanced Menu', 'powerpack'),
				'demo'		=>	'advanced-menu',
				'package'	=>	'pro'
			],
			'pp-offcanvas-content'      =>	[
				'title'		=>	__('Offcanvas Content', 'powerpack'),
				'demo'		=>	'offcanvas-content',
				'package'	=>	'pro'
			],
			'pp-timeline'               =>	[
				'title'		=>	__('Timeline', 'powerpack'),
				'demo'		=>	'timeline',
				'package'	=>	'pro'
			],
			'pp-showcase'               =>	[
				'title'		=>	__('Showcase', 'powerpack'),
				'demo'		=>	'showcase-widget',
				'package'	=>	'pro'
			],
			'pp-card-slider'            =>	[
				'title'		=>	__('Card Slider', 'powerpack'),
				'demo'		=>	'card-slider',
				'package'	=>	'pro'
			],
			'pp-breadcrumbs'            =>	[
				'title'		=>	__('Breadcrumbs', 'powerpack'),
				'demo'		=>	'breadcrumbs',
				'package'	=>	'pro'
			],
			'pp-magazine-slider'        =>	[
				'title'		=>	__('Magazine Slider', 'powerpack'),
				'demo'		=>	'magazine-slider',
				'package'	=>	'pro'
			],
			'pp-video'                  =>	[
				'title'		=>	__('Video', 'powerpack'),
				'demo'		=>	'video',
				'package'	=>	'pro'
			],
			'pp-video-gallery'          =>	[
				'title'		=>	__('Video Gallery', 'powerpack'),
				'demo'		=>	'video-gallery',
				'package'	=>	'pro'
			],
			'pp-testimonials'           =>	[
				'title'		=>	__('Testimonials', 'powerpack'),
				'demo'		=>	'testimonials',
				'package'	=>	'pro'
			],
			'pp-album'                  =>	[
				'title'		=>	__('Album', 'powerpack'),
				'demo'		=>	'album',
				'package'	=>	'pro'
			],
			'pp-tabbed-gallery'			=>	[
				'title'		=>	__('Tabbed Gallery', 'powerpack'),
				'demo'		=>	'tabbed-gallery',
				'package'	=>	'pro'
			],
			'pp-devices'				=>	[
				'title'		=>	__('Devices', 'powerpack'),
				'demo'		=>	'devices',
				'package'	=>	'pro'
			],
			'pp-faq'					=>	[
				'title'		=>	__('FAQ', 'powerpack'),
				'demo'		=>	'faq',
				'package'	=>	'pro'
			]
	];

	return $pro_modules;
}

function pp_elements_lite_get_enabled_modules() {
    $enabled_modules = \PowerpackElementsLite\Classes\PP_Admin_Settings::get_option( 'pp_elementor_modules', true );

    if ( ! is_array( $enabled_modules ) ) {
        return array_keys( pp_elements_lite_get_modules() );
    } else {
        return $enabled_modules;
    }
}

// Get templates
function pp_elements_lite_get_saved_templates( $templates = array() ) {
	if ( empty( $templates ) ) {
		return array();
	}

	$options = array();

	foreach ( $templates as $template ) {
		$options[ $template['template_id'] ] = $template['title'];
	}

	return $options;
}