<?php
// Get all elementor page templates
if ( !function_exists('pp_lite_get_page_templates') ) {
    function pp_lite_get_page_templates( $type = '' ) {
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
}

// Get all forms of Contact Form 7 plugin
if ( !function_exists('pp_lite_get_contact_form_7_forms') ) {
    function pp_lite_get_contact_form_7_forms() {
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
}

// Get all forms of Gravity Forms plugin
if ( !function_exists('pp_lite_get_gravity_forms') ) {
    function pp_lite_get_gravity_forms() {
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
}

// Get all forms of Ninja Forms plugin
if ( !function_exists('pp_lite_get_ninja_forms') ) {
    function pp_lite_get_ninja_forms() {
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
}

// Get all forms of Caldera plugin
if ( !function_exists('pp_lite_get_caldera_forms') ) {
    function pp_lite_get_caldera_forms() {
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
}

// Get all forms of WPForms plugin
if ( !function_exists('pp_lite_get_wpforms_forms') ) {
    function pp_lite_get_wpforms_forms() {
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
}

// Get categories
if ( !function_exists('pp_get_post_categories') ) {
    function pp_get_post_categories() {

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
}

// Get Post Types
if ( !function_exists('pp_get_post_types') ) {
    function pp_get_post_types() {

        $pp_post_types = get_post_types( array(
            'public'            => true,
            'show_in_nav_menus' => true
        ) );

        return $pp_post_types;
    }
}

// Get all Authors
if ( !function_exists('pp_get_auhtors') ) {
    function pp_get_auhtors() {

        $options = array();

        $users = get_users();

        foreach ( $users as $user ) {
            $options[ $user->ID ] = $user->display_name;
        }

        return $options;
    }
}

// Get all Authors
if ( !function_exists('pp_get_tags') ) {
    function pp_get_tags() {

        $options = array();

        $tags = get_tags();

        foreach ( $tags as $tag ) {
            $options[ $tag->term_id ] = $tag->name;
        }

        return $options;
    }
}

// Get all Posts
if ( !function_exists('pp_get_posts') ) {
    function pp_get_posts() {

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
}

// Custom Excerpt
if ( !function_exists('pp_custom_excerpt') ) {
    function pp_custom_excerpt( $limit = '' ) {
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
}
add_filter( 'get_the_excerpt', 'do_shortcode' );

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
}

if ( class_exists( 'WooCommerce' ) || is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

    // Get all Products
    if ( !function_exists('pp_get_products') ) {
        function pp_get_products() {

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
    }
    
    // Woocommerce - Get product categories
    if ( !function_exists('pp_get_product_categories') ) {
        function pp_get_product_categories() {

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
    }

    // WooCommerce - Get product tags
    if ( !function_exists('pp_product_get_tags') ) {
        function pp_product_get_tags() {

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
}

function pp_lite_get_modules() {
    $modules = array(
        'pp-link-effects'           => __('Link Effects', 'powerpack'),
        'pp-divider'                => __('Divider', 'powerpack'),
        'pp-info-box'               => __('Info Box', 'powerpack'),
        'pp-info-box-carousel'      => __('Info Box Carousel', 'powerpack'),
        'pp-info-list'              => __('Info List', 'powerpack'),
		'pp-info-table'             => __('Info Table', 'powerpack'),
        'pp-pricing-table'          => __('Pricing Table', 'powerpack'),
        'pp-price-menu'             => __('Price Menu', 'powerpack'),
        'pp-business-hours'         => __('Businsess Hours', 'powerpack'),
        'pp-team-member'            => __('Team Member', 'powerpack'),
        'pp-team-member-carousel'   => __('Team Member Carousel', 'powerpack'),
        'pp-counter'                => __('Counter', 'powerpack'),
        'pp-hotspots'               => __('Image Hotspots', 'powerpack'),
        'pp-icon-list'              => __('Icon List', 'powerpack'),
        'pp-dual-heading'           => __('Dual Heading', 'powerpack'),
        'pp-promo-box'              => __('Promo Box', 'powerpack'),
        'pp-logo-carousel'          => __('Logo Carousel', 'powerpack'),
        'pp-logo-grid'              => __('Logo Grid', 'powerpack'),
        'pp-image-comparison'       => __('Image Comparison', 'powerpack'),
        'pp-instafeed'              => __('Instagram Feed', 'powerpack'),
    );

    // Contact Form 7
    if ( function_exists( 'wpcf7' ) ) {
        $modules['pp-contact-form-7'] = __('Contact Form 7', 'power-pack');
    }
    
    // Gravity Forms
    if ( class_exists( 'GFCommon' ) ) {
        $modules['pp-gravity-forms'] = __('Gravity Forms', 'power-pack');
    }
    
    // Ninja Forms
    if ( class_exists( 'Ninja_Forms' ) ) {
        $modules['pp-ninja-forms'] = __('Ninja Forms', 'power-pack');
    }
    
    // Caldera Forms
    if ( class_exists( 'Caldera_Forms' ) ) {
        $modules['pp-caldera-forms'] = __('Caldera Forms', 'power-pack');
    }
    
    // WPForms
    if ( function_exists( 'wpforms' ) ) {
        $modules['pp-wpforms'] = __('WPForms', 'power-pack');
    }

    ksort($modules);

    return $modules;
}

function pp_lite_get_enabled_modules()
{
    $enabled_modules = \PowerpackElementsLite\Classes\PP_Admin_Settings::get_option( 'pp_elementor_modules', true );

    if ( ! is_array( $enabled_modules ) ) {
        return array_keys(  pp_lite_get_modules());
    } else {
        return $enabled_modules;
    }
}

// Get templates

function pp_lite_get_saved_templates( $templates = array() ) {

	if ( empty( $templates ) ) {
		return array();
	}

	$options = array();

	foreach ( $templates as $template ) {
		$options[ $template['template_id'] ] = $template['title'];
	}

	return $options;
}