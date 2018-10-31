<?php
namespace PowerpackElements\Modules\GoogleMaps\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Google Maps Widget
 */
class Google_Maps extends Powerpack_Widget {
    
    /**
	 * Retrieve link effects widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-google-maps';
    }

    /**
	 * Retrieve link effects widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Google Maps', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the link effects widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
    public function get_categories() {
        return [ 'power-pack' ];
    }

    /**
	 * Retrieve link effects widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-map power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the instagram feed widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'google-maps',
            'pp-google-maps',
            'powerpack-frontend',
        ];
    }

    /**
	 * Register link effects widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        $this->start_controls_section(
			'section_map_addresses',
			[
				'label'                 => esc_html__( 'Addresses', 'power-pack' ),
			]
		);
		 
		$this->add_control(
			'pp_map_addresses',
			[
				'label'                 => '',
				'type'                  => Controls_Manager::REPEATER,
				'default'               => [
					[
						'map_latitude'    => 24.553311,
						'map_longitude'   => 73.694076,
						'map_title'       => esc_html__( 'IdeaBox Creations', 'power-pack' ),
						'map_description' => esc_html__( 'Add description to your map pins', 'power-pack' ),
					],
				],
				'fields' => [
					[
						'name'            => 'map_latitude',
						'label'           => esc_html__( 'Latitude', 'power-pack' ),
                        'description'     => sprintf( '<a href="https://www.latlong.net/" target="_blank">%1$s</a> %2$s',__( 'Click here', 'power-pack' ), __( 'to find Latitude and Longitude of your location', 'power-pack' ) ),
						'type'            => Controls_Manager::TEXT,
						'label_block'     => true,
					],
					[
						'name'            => 'map_longitude',
						'label'           => esc_html__( 'Longitude', 'power-pack' ),
                        'description'     => sprintf( '<a href="https://www.latlong.net/" target="_blank">%1$s</a> %2$s',__( 'Click here', 'power-pack' ), __( 'to find Latitude and Longitude of your location', 'power-pack' ) ),
						'type'            => Controls_Manager::TEXT,
						'label_block'     => true,
					],
					[
						'name'            => 'map_title',
						'label'           => esc_html__( 'Address Title', 'power-pack' ),
						'type'            => Controls_Manager::TEXT,
						'label_block'     => true,
					],
                    [
						'name'            => 'map_marker_infowindow',
                        'label'           => esc_html__( 'Info Window', 'power-pack' ),
                        'type'            => Controls_Manager::SWITCHER,
                        'default'         => 'no',
                        'label_on'        => __( 'On', 'power-pack' ),
                        'label_off'       => __( 'Off', 'power-pack' ),
                        'return_value'    => 'yes',
                    ],
					[
						'name'            => 'map_info_window_open',
						'label'           => esc_html__( 'Open Info Window on Load', 'power-pack' ),
						'type'            => Controls_Manager::SWITCHER,
						'default'         => 'yes',
                        'conditions'      => [
							'terms' => [
								[
									'name' => 'map_marker_infowindow',
									'operator' => '==',
									'value' => 'yes',
								],
							],
						],
					],
					[
						'name'            => 'map_description',
						'label'           => esc_html__( 'Address Description', 'power-pack' ),
						'type'            => Controls_Manager::TEXTAREA,
						'label_block'     => true,
                        'conditions'      => [
							'terms' => [
								[
									'name' => 'map_marker_infowindow',
									'operator' => '==',
									'value' => 'yes',
								],
							],
						],
					],
                    [
						'name'            => 'map_marker_icon_type',
                        'label'           => esc_html__( 'Marker Icon', 'power-pack' ),
                        'type'            => Controls_Manager::SELECT,
                        'default'         => 'default',
                        'options'         => [
                            'default'     => esc_html__( 'Default', 'power-pack' ),
                            'custom'      => esc_html__( 'Custom', 'power-pack' ),
                        ],
                    ],
					[
						'name'            => 'map_marker_icon',
						'label'           => esc_html__( 'Custom Marker Icon', 'power-pack' ),
						'type'            => Controls_Manager::MEDIA,
                        'conditions' => [
							'terms' => [
								[
									'name' => 'map_marker_icon_type',
									'operator' => '==',
									'value' => 'custom',
								],
							],
						],
					],
                    [
						'name'            => 'map_custom_marker_size',
                        'label'           => esc_html__( 'Size', 'power-pack' ),
                        'type'            => Controls_Manager::NUMBER,
                        'default'         => 30,
                        'min'             => 5,
                        'max'             => 100,
                        'step'            => 1,
                        'conditions'      => [
							'terms' => [
								[
									'name' => 'map_marker_icon_type',
									'operator' => '==',
									'value' => 'custom',
								],
							],
						]
                    ]
				],
				'title_field' => '<i class="fa fa-map-marker"></i> {{{ map_title }}}',
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
  			'section_map_settings',
  			[
  				'label'                 => esc_html__( 'Settings', 'power-pack' )
  			]
  		);

  		$this->add_control(
  			'map_zoom',
  			[
  				'label'                 => esc_html__( 'Map Zoom', 'power-pack' ),
  				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size' => 12,
				],
				'range'                 => [
					'px' => [
						'min' => 0,
						'max' => 22,
					],
				],
  			]
  		);
		
		$this->add_control(
			'map_type',
			[
				'label'                 => esc_html__( 'Map Type', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'roadmap',
				'options'               => [
					'roadmap'      => esc_html__( 'Road Map', 'power-pack' ),
					'satellite'    => esc_html__( 'Satellite', 'power-pack' ),
					'hybrid'       => esc_html__( 'Hybrid', 'power-pack' ),
					'terrain'      => esc_html__( 'Terrain', 'power-pack' ),
				],
                'frontend_available'    => true,
			]
		);
		
		$this->add_control(
			'marker_animation',
			[
				'label'                 => esc_html__( 'Marker Animation', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => '',
				'options'               => [
					''         => esc_html__( 'None', 'power-pack' ),
					'drop'     => esc_html__( 'Drop', 'power-pack' ),
					'bounce'   => esc_html__( 'Bounce', 'power-pack' ),
				],
                'frontend_available'    => true,
			]
		);
        
		$this->end_controls_section();

        $this->start_controls_section(
  			'section_map_controls',
  			[
  				'label'                 => esc_html__( 'Controls', 'power-pack' )
  			]
  		);
		
		$this->add_control(
			'map_option_streeview',
			[
				'label'                 => esc_html__( 'Street View Controls', 'power-pack' ),
				'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
			]
		);

		$this->add_control(
			'map_type_control',
			[
				'label'                 => esc_html__( 'Map Type Control', 'power-pack' ),
				'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
			]
		);

		$this->add_control(
			'zoom_control',
			[
				'label'                 => esc_html__( 'Zoom Control', 'power-pack' ),
				'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
			]
		);

		$this->add_control(
			'fullscreen_control',
			[
				'label'                 => esc_html__( 'Fullscreen Control', 'power-pack' ),
				'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
			]
		);
		
		$this->add_control(
			'map_scroll_zoom',
			[
				'label'                 => esc_html__( 'Scroll Wheel Zoom', 'power-pack' ),
				'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
			]
		);
		
  		$this->end_controls_section();
        
        $this->start_controls_section(
			'section_map_theme',
			[
				'label'                 => esc_html__( 'Map Style', 'power-pack' ),
			]
		);
		
		$this->add_control(
			'map_theme',
			[
				'label'                 => esc_html__( 'Map Theme', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'standard',
				'options'               => [
					'standard'     => __( 'Standard', 'power-pack' ),
					'silver'       => __( 'Silver', 'power-pack' ),
					'retro'        => __( 'Retro', 'power-pack' ),
					'dark'         => __( 'Dark', 'power-pack' ),
					'night'        => __( 'Night', 'power-pack' ),
					'aubergine'    => __( 'Aubergine', 'power-pack' ),
					'custom'       => __( 'Custom', 'power-pack' ),
				],
			]
		);
        
		$this->add_control(
			'map_custom_style',
			[
				'label'                 => __( 'Custom Style', 'power-pack' ),
				'description'           => sprintf( '<a href="https://mapstyle.withgoogle.com/" target="_blank">%1$s</a> %2$s',__( 'Click here', 'power-pack' ), __( 'to get JSON style code to style your map', 'power-pack' ) ),
				'type'                  => Controls_Manager::TEXTAREA,
                'condition'             => [
                    'map_theme'     => 'custom',
                ],
			]
		);
        
  		$this->end_controls_section();
        
        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Style Tab: Map
         * -------------------------------------------------
         */
        $this->start_controls_section(
			'section_map_style',
			[
				'label'                 => esc_html__( 'Map', 'power-pack' ),
				'tab'                   => Controls_Manager::TAB_STYLE
			]
		);
        
        $this->add_responsive_control(
			'map_align',
			[
				'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'        => [
						'title'   => __( 'Left', 'power-pack' ),
						'icon'    => 'eicon-h-align-left',
					],
					'center'      => [
						'title'   => __( 'Center', 'power-pack' ),
						'icon'    => 'eicon-h-align-center',
					],
					'right'       => [
						'title'   => __( 'Right', 'power-pack' ),
						'icon'    => 'eicon-h-align-right',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-google-map-container' => 'text-align: {{VALUE}};',
				],
			]
		);

  		$this->add_responsive_control(
  			'map_width',
  			[
  				'label'                 => esc_html__( 'Width', 'power-pack' ),
  				'type'                  => Controls_Manager::SLIDER,
                'size_units'            => [ 'px', '%' ],
				'default'               => [
					'size' => 100,
					'unit' => '%',
				],
				'range'                 => [
					'px' => [
						'min' => 100,
						'max' => 1920,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-map-height' => 'width: {{SIZE}}{{UNIT}};',
				],
  			]
  		);

  		$this->add_responsive_control(
  			'map_height',
  			[
  				'label'                 => esc_html__( 'Height', 'power-pack' ),
  				'type'                  => Controls_Manager::SLIDER,
                'size_units'            => [ 'px' ],
				'default'               => [
					'size' => 500,
					'unit' => 'px',
				],
				'range'                 => [
					'px' => [
						'min' => 80,
						'max' => 1200,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-map-height' => 'height: {{SIZE}}{{UNIT}};',
				],
  			]
  		);
        
  		$this->end_controls_section();
        
        /**
         * Style Tab: Info Window
         * -------------------------------------------------
         */
        $this->start_controls_section(
			'section_info_window_style',
			[
				'label'                 => esc_html__( 'Info Window', 'power-pack' ),
				'tab'                   => Controls_Manager::TAB_STYLE
			]
		);
        
        $this->add_control(
            'iw_max_width',
            [
                'label'                 => __( 'Info Window Max Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
					'size'      => 240,
				],
                'range'                 => [
                    'px'        => [
                        'min'   => 40,
                        'max'   => 500,
                        'step'  => 1,
                    ],
                ],
            ]
        );
        
        $this->add_responsive_control(
			'info_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .gm-style .pp-infowindow-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_control(
            'title_heading',
            [
                'label'                 => __( 'Title', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );

		$this->add_control(
			'title_color',
			[
				'label'                 => esc_html__( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .gm-style .pp-infowindow-title' => 'color: {{VALUE}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'title_spacing',
            [
                'label'                 => __( 'Bottom Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .gm-style .pp-infowindow-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
                'name'                  => 'title_typography',
				'scheme'                => Scheme_Typography::TYPOGRAPHY_1,
				'selector'              => '{{WRAPPER}} .gm-style .pp-infowindow-title',
			]
		);
        
        $this->add_control(
            'description_heading',
            [
                'label'                 => __( 'Description', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );

		$this->add_control(
			'description_color',
			[
				'label'                 => esc_html__( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .gm-style .pp-infowindow-description' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
                'name'                  => 'description_typography',
				'selector'              => '{{WRAPPER}} .gm-style .pp-infowindow-description',
			]
		);

        $this->end_controls_section();
	}

	/**
	 * Get map styles.
	 *
	 * @access protected
	 */
	protected function get_map_styles() {
        $pp_map_themes = array(
            'silver' => '[{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]',
            'retro' => '[{"elementType":"geometry","stylers":[{"color":"#ebe3cd"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#523735"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f1e6"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#c9b2a6"}]},{"featureType":"administrative.land_parcel","elementType":"geometry.stroke","stylers":[{"color":"#dcd2be"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#ae9e90"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#93817c"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#a5b076"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#447530"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#f5f1e6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#fdfcf8"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#f8c967"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#e9bc62"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#e98d58"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#db8555"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#806b63"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#8f7d77"}]},{"featureType":"transit.line","elementType":"labels.text.stroke","stylers":[{"color":"#ebe3cd"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#b9d3c2"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#92998d"}]}]',
            'dark' => '[{"elementType":"geometry","stylers":[{"color":"#212121"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#212121"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#757575"}]},{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"administrative.land_parcel","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#181818"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"color":"#1b1b1b"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#2c2c2c"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#8a8a8a"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#373737"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#3c3c3c"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#3d3d3d"}]}]',
            'night' => '[{"elementType":"geometry","stylers":[{"color":"#242f3e"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#746855"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#242f3e"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#263c3f"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#6b9a76"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#38414e"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#212a37"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#9ca5b3"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#746855"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#1f2835"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#f3d19c"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#2f3948"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#17263c"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#515c6d"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#17263c"}]}]',
            'aubergine' => '[{"elementType":"geometry","stylers":[{"color":"#1d2c4d"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#8ec3b9"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#1a3646"}]},{"featureType":"administrative.country","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#64779e"}]},{"featureType":"administrative.province","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#334e87"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#023e58"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#283d6a"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#6f9ba5"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#023e58"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#3C7680"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#304a7d"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#2c6675"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#255763"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#b0d5ce"}]},{"featureType":"road.highway","elementType":"labels.text.stroke","stylers":[{"color":"#023e58"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"transit.line","elementType":"geometry.fill","stylers":[{"color":"#283d6a"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#3a4762"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#0e1626"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#4e6d70"}]}]',
        );
        
        return $pp_map_themes;
    }

    /**
	 * Render google maps widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();
        
        $map_styles = $this->get_map_styles();

        $i = 1;
        
        $this->add_render_attribute( 'google-map',
            [
                'id'            => 'pp-google-map-' . esc_attr( $this->get_id() ),
                'class'         => [ 'pp-google-map', 'pp-map-height' ],
            ]
        );

        if ( ! empty( $settings['map_zoom']['size'] ) ) {
            $this->add_render_attribute( 'google-map', 'data-zoom', $settings['map_zoom']['size'] );
        }

        if ( $settings['iw_max_width']['size'] ) {
            $this->add_render_attribute( 'google-map', 'data-iw-max-width', $settings['iw_max_width']['size'] );
        }

        if ( $settings['map_theme'] != 'standard' ) {
            if ( $settings['map_theme'] != 'custom' ) {
                $this->add_render_attribute( 'google-map', 'data-custom-style', $map_styles[$settings['map_theme']] );
            } else if ( ! empty( $settings['map_custom_style'] ) ) {
                $this->add_render_attribute( 'google-map', 'data-custom-style', $settings['map_custom_style'] );
            }
        }
        
        $pp_map_locations = array();
        
        foreach ( $settings['pp_map_addresses'] as $index => $item ) {
            
            $pp_map_location = array(
                $item['map_latitude'],
                $item['map_longitude']
            );
            
            if ( $item['map_marker_infowindow'] == 'yes' ) {
                $pp_map_location[] = 'yes';
            } else {
                $pp_map_location[] = '';
            }
            
            $pp_map_location[] = $item['map_title'];
            $pp_map_location[] = $item['map_description'];
            
            if ( $item['map_marker_icon_type'] == 'custom' && $item['map_marker_icon']['url'] != '' ) {
                $pp_map_location[] = 'custom';
                $pp_map_location[] = $item['map_marker_icon']['url'];
                $pp_map_location[] = $item['map_custom_marker_size'];
            } else {
                $pp_map_location[] = '';
                $pp_map_location[] = '';
                $pp_map_location[] = '';
            }
            
            if ( $item['map_info_window_open'] == 'yes' ) {
                $pp_map_location[] = 'iw_open';
            } else {
                $pp_map_location[] = '';
            }
            
            $pp_map_locations[] = $pp_map_location;
        }
        
        $this->add_render_attribute( 'google-map', 'data-locations', wp_json_encode( $pp_map_locations ) );
	?>
    <div class="pp-google-map-container">
        <div <?php echo $this->get_render_attribute_string( 'google-map' ); ?>></div>
    </div>
	<?php
	}

    protected function _content_template() {
        ?>
        <#
            function get_map_styles() {
                return {
                    'silver': '[{"elementType":"geometry","stylers":[{"color":"#f5f5f5"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f5f5"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#ffffff"}]},{"featureType":"road.arterial","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#dadada"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#e5e5e5"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#eeeeee"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#c9c9c9"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]}]',
                    'retro': '[{"elementType":"geometry","stylers":[{"color":"#ebe3cd"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#523735"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#f5f1e6"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#c9b2a6"}]},{"featureType":"administrative.land_parcel","elementType":"geometry.stroke","stylers":[{"color":"#dcd2be"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#ae9e90"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#93817c"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#a5b076"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#447530"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#f5f1e6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#fdfcf8"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#f8c967"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#e9bc62"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#e98d58"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry.stroke","stylers":[{"color":"#db8555"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#806b63"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"transit.line","elementType":"labels.text.fill","stylers":[{"color":"#8f7d77"}]},{"featureType":"transit.line","elementType":"labels.text.stroke","stylers":[{"color":"#ebe3cd"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#dfd2ae"}]},{"featureType":"water","elementType":"geometry.fill","stylers":[{"color":"#b9d3c2"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#92998d"}]}]',
                    'dark': '[{"elementType":"geometry","stylers":[{"color":"#212121"}]},{"elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#212121"}]},{"featureType":"administrative","elementType":"geometry","stylers":[{"color":"#757575"}]},{"featureType":"administrative.country","elementType":"labels.text.fill","stylers":[{"color":"#9e9e9e"}]},{"featureType":"administrative.land_parcel","stylers":[{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#bdbdbd"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#181818"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"poi.park","elementType":"labels.text.stroke","stylers":[{"color":"#1b1b1b"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"color":"#2c2c2c"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#8a8a8a"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#373737"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#3c3c3c"}]},{"featureType":"road.highway.controlled_access","elementType":"geometry","stylers":[{"color":"#4e4e4e"}]},{"featureType":"road.local","elementType":"labels.text.fill","stylers":[{"color":"#616161"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#757575"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#3d3d3d"}]}]',
                    'night': '[{"elementType":"geometry","stylers":[{"color":"#242f3e"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#746855"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#242f3e"}]},{"featureType":"administrative.locality","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#263c3f"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#6b9a76"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#38414e"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"color":"#212a37"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#9ca5b3"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#746855"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#1f2835"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#f3d19c"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#2f3948"}]},{"featureType":"transit.station","elementType":"labels.text.fill","stylers":[{"color":"#d59563"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#17263c"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#515c6d"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"color":"#17263c"}]}]',
                    'aubergine': '[{"elementType":"geometry","stylers":[{"color":"#1d2c4d"}]},{"elementType":"labels.text.fill","stylers":[{"color":"#8ec3b9"}]},{"elementType":"labels.text.stroke","stylers":[{"color":"#1a3646"}]},{"featureType":"administrative.country","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"administrative.land_parcel","elementType":"labels.text.fill","stylers":[{"color":"#64779e"}]},{"featureType":"administrative.province","elementType":"geometry.stroke","stylers":[{"color":"#4b6878"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"color":"#334e87"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#023e58"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#283d6a"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#6f9ba5"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"poi.park","elementType":"geometry.fill","stylers":[{"color":"#023e58"}]},{"featureType":"poi.park","elementType":"labels.text.fill","stylers":[{"color":"#3C7680"}]},{"featureType":"road","elementType":"geometry","stylers":[{"color":"#304a7d"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#2c6675"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#255763"}]},{"featureType":"road.highway","elementType":"labels.text.fill","stylers":[{"color":"#b0d5ce"}]},{"featureType":"road.highway","elementType":"labels.text.stroke","stylers":[{"color":"#023e58"}]},{"featureType":"transit","elementType":"labels.text.fill","stylers":[{"color":"#98a5be"}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"color":"#1d2c4d"}]},{"featureType":"transit.line","elementType":"geometry.fill","stylers":[{"color":"#283d6a"}]},{"featureType":"transit.station","elementType":"geometry","stylers":[{"color":"#3a4762"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#0e1626"}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"color":"#4e6d70"}]}]',
                };
            }
           
            var map_styles = get_map_styles();

            view.addRenderAttribute(
                'google-map',
                {
                    'class' : [ 'pp-google-map', 'pp-map-height' ],
                }
            );
           
            if ( settings.map_zoom.size != '' ) {
                view.addRenderAttribute( 'google-map', { 'data-zoom' : settings.map_zoom.size } );
            }
           
            if ( settings.iw_max_width.size != '' ) {
                view.addRenderAttribute( 'google-map', { 'data-iw-max-width' : settings.iw_max_width.size } );
            }

            if ( settings.map_theme != 'standard' ) {
                if ( settings.map_theme != 'custom' ) {
                    view.addRenderAttribute( 'google-map', { 'data-custom-style' : map_styles[settings.map_theme] } );
                } else if ( settings.map_custom_style ) {
                    view.addRenderAttribute( 'google-map', { 'data-custom-style' : settings.map_custom_style } );
                }
            }
           
            var pp_map_locations = [];

			_.each( settings.pp_map_addresses, function( item ) {

				var pp_map_location = [ item.map_latitude, item.map_longitude ];
           
                if ( item.map_marker_infowindow == 'yes' ) {
                    pp_map_location.push( 'yes' );
                } else {
                    pp_map_location.push( "" );
                }

                pp_map_location.push( item.map_title );
                pp_map_location.push( item.map_description );

                if ( item.map_marker_icon_type == 'custom' && item.map_marker_icon.url != '' ) {
                    pp_map_location.push( 'custom' );
                    pp_map_location.push( item.map_marker_icon.url );
                    pp_map_location.push( item.map_custom_marker_size );
                } else {
                    pp_map_location.push( "" );
                    pp_map_location.push( "" );
                    pp_map_location.push( "" );
                }

                if ( item.map_info_window_open == 'yes' ) {
                    pp_map_location.push( 'iw_open' );
                } else {
                    pp_map_location.push( "" );
                }

				pp_map_locations.push( pp_map_location );

			});
           
            view.addRenderAttribute( 'google-map', { 'data-locations' : JSON.stringify( pp_map_locations ) } );
        #>
        <div class="pp-google-map-container">
            <div {{{ view.getRenderAttributeString( 'google-map' ) }}}></div>
        </div>
        <?php
    }

}