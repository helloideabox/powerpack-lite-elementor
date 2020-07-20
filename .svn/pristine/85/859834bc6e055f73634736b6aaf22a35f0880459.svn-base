<?php
namespace PowerpackElementsLite\Modules\Hotspots\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Image Hotspots Widget
 */
class Hotspots extends Powerpack_Widget {

    /**
	 * Retrieve image hotspots widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-image-hotspots';
    }

    /**
	 * Retrieve image hotspots widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Image Hotspots', 'powerpack' );
    }

    /**
	 * Retrieve the list of categories the image hotspots widget belongs to.
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
	 * Retrieve image hotspots widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-image-hotspot power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the image hotspots widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
			'pp-tooltip',
            'powerpack-frontend'
        ];
    }

    /**
	 * Register image hotspots widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Image
         */
        $this->start_controls_section(
            'section_image',
            [
                'label'                 => __( 'Image', 'powerpack' ),
            ]
        );

		$this->add_control(
			'image',
			[
				'label'                 => __( 'Image', 'powerpack' ),
				'type'                  => Controls_Manager::MEDIA,
				'default'               => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'                  => 'image',
                'label'                 => __( 'Image Size', 'powerpack' ),
                'default'               => 'full',
            ]
        );
        
        $this->add_responsive_control(
            'image_align',
            [
                'label'                 => __( 'Alignment', 'powerpack' ),
                'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => true,
                'options'               => [
                    'left' 		=> [
                        'title' => __( 'Left', 'powerpack' ),
                        'icon' 	=> 'eicon-h-align-left',
                    ],
                    'center' 	=> [
                        'title' => __( 'Center', 'powerpack' ),
                        'icon' 	=> 'eicon-h-align-center',
                    ],
                    'right' 	=> [
                        'title' => __( 'Right', 'powerpack' ),
                        'icon' 	=> 'eicon-h-align-right',
                    ],
                ],
                'prefix_class'          => 'pp-hotspot-img-align%s-',
                'selectors'     => [
                    '{{WRAPPER}} .pp-hot-spot-image' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Content Tab: Hotspots
         */
        $this->start_controls_section(
            'section_hotspots',
            [
                'label'                 => __( 'Hotspots', 'powerpack' ),
            ]
        );
        
        $repeater = new Repeater();
        
        $repeater->start_controls_tabs( 'hot_spots_tabs' );

        $repeater->start_controls_tab( 'tab_content', [ 'label' => __( 'Content', 'powerpack' ) ] );
        
            $repeater->add_control(
                'hotspot_admin_label',
                [
                    'label'           => __( 'Admin Label', 'powerpack' ),
                    'type'            => Controls_Manager::TEXT,
                    'label_block'     => false,
                    'default'         => '',
                ]
            );
        
            $repeater->add_control(
                'hotspot_type',
                [
                    'label'           => __( 'Type', 'powerpack' ),
                    'type'            => Controls_Manager::SELECT,
                    'default'         => 'icon',
                    'options'         => [
                        'icon'  => __( 'Icon', 'powerpack' ),
                        'text'  => __( 'Text', 'powerpack' ),
                        'blank' => __( 'Blank', 'powerpack' ),
                     ],
                ]
            );

			$repeater->add_control(
				'selected_icon',
				[
					'label'					=> __( 'Icon', 'powerpack' ),
					'type'					=> Controls_Manager::ICONS,
					'label_block'			=> true,
					'default'				=> [
						'value'		=> 'fas fa-plus',
						'library'	=> 'fa-solid',
					],
					'fa4compatibility'		=> 'hotspot_icon',
                    'conditions'        => [
                        'terms' => [
                            [
                                'name' => 'hotspot_type',
                                'operator' => '==',
                                'value' => 'icon',
                            ],
                        ],
                    ],
				]
			);
        
            $repeater->add_control(
                'hotspot_text',
                [
                    'label'           => __( 'Text', 'powerpack' ),
                    'type'            => Controls_Manager::TEXT,
                    'label_block'     => false,
                    'default'         => '#',
                    'conditions'        => [
                        'terms' => [
                            [
                                'name' => 'hotspot_type',
                                'operator' => '==',
                                'value' => 'text',
                            ],
                        ],
                    ],
                ]
            );
        
            $repeater->add_control(
                'tooltip',
                [
                    'label'           => __( 'Tooltip', 'powerpack' ),
                    'type'            => Controls_Manager::SWITCHER,
                    'default'         => '',
                    'label_on'        => __( 'Show', 'powerpack' ),
                    'label_off'       => __( 'Hide', 'powerpack' ),
                    'return_value'    => 'yes',
                ]
            );

            $repeater->add_control(
                'tooltip_position_local',
                [
                    'label'                 => __( 'Tooltip Position', 'powerpack' ),
                    'type'                  => Controls_Manager::SELECT,
                    'default'               => 'global',
                    'options'               => [
                        'global'        => __( 'Global', 'powerpack' ),
                        'top'           => __( 'Top', 'powerpack' ),
                        'bottom'        => __( 'Bottom', 'powerpack' ),
                        'left'          => __( 'Left', 'powerpack' ),
                        'right'         => __( 'Right', 'powerpack' ),
                        'top-left'      => __( 'Top Left', 'powerpack' ),
                        'top-right'     => __( 'Top Right', 'powerpack' ),
                        'bottom-left'   => __( 'Bottom Left', 'powerpack' ),
                        'bottom-right'  => __( 'Bottom Right', 'powerpack' ),
                    ],
                    'conditions'        => [
                        'terms' => [
                            [
                                'name' => 'tooltip',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                        ],
                    ],
                ]
            );
        
            $repeater->add_control(
                'tooltip_content',
                [
                    'label'           => __( 'Tooltip Content', 'powerpack' ),
                    'type'            => Controls_Manager::WYSIWYG,
                    'default'         => __( 'Tooltip Content', 'powerpack' ),
                    'conditions'        => [
                        'terms' => [
                            [
                                'name' => 'tooltip',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                        ],
                    ],
                ]
            );
        
        $repeater->end_controls_tab();
        
        $repeater->start_controls_tab( 'tab_position', [ 'label' => __( 'Position', 'powerpack' ) ] );

            $repeater->add_control(
                'left_position',
                [
                    'label'         => __( 'Left Position', 'powerpack' ),
                    'type'          => Controls_Manager::SLIDER,
                    'range'         => [
                        'px' 	=> [
                            'min' 	=> 0,
                            'max' 	=> 100,
                            'step'	=> 0.1,
                        ],
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}%;',
                    ],
                ]
            );

            $repeater->add_control(
                'top_position',
                [
                    'label'         => __( 'Top Position', 'powerpack' ),
                    'type'          => Controls_Manager::SLIDER,
                    'range'         => [
                        'px' 	=> [
                            'min' 	=> 0,
                            'max' 	=> 100,
                            'step'	=> 0.1,
                        ],
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}%;',
                    ],
                ]
            );
        
        $repeater->end_controls_tab();
        
        $repeater->start_controls_tab( 'tab_style', [ 'label' => __( 'Style', 'powerpack' ) ] );

            $repeater->add_control(
                'hotspot_color_single',
                [
                    'label'                 => __( 'Color', 'powerpack' ),
                    'type'                  => Controls_Manager::COLOR,
                    'default'               => '',
                    'selectors'             => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.pp-hot-spot-wrap, {{WRAPPER}} {{CURRENT_ITEM}} .pp-hot-spot-inner, {{WRAPPER}} {{CURRENT_ITEM}} .pp-hot-spot-inner:before' => 'color: {{VALUE}}',
                        '{{WRAPPER}} {{CURRENT_ITEM}}.pp-hot-spot-wrap .pp-icon svg' => 'fill: {{VALUE}}',
                    ],
                ]
            );

            $repeater->add_control(
                'hotspot_bg_color_single',
                [
                    'label'                 => __( 'Background Color', 'powerpack' ),
                    'type'                  => Controls_Manager::COLOR,
                    'default'               => '',
                    'selectors'             => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.pp-hot-spot-wrap, {{WRAPPER}} {{CURRENT_ITEM}} .pp-hot-spot-inner, {{WRAPPER}} {{CURRENT_ITEM}} .pp-hot-spot-inner:before' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $repeater->add_control(
                'hotspot_border_color_single',
                [
                    'label'                 => __( 'Border Color', 'powerpack' ),
                    'type'                  => Controls_Manager::COLOR,
                    'default'               => '',
                    'selectors'             => [
                        '{{WRAPPER}} {{CURRENT_ITEM}}.pp-hot-spot-wrap' => 'border-color: {{VALUE}}',
                    ],
                ]
            );
        
        $repeater->end_controls_tab();

        $repeater->end_controls_tabs();

        $this->add_control(
            'hot_spots',
            [
                'label'                 => '',
                'type'                  => Controls_Manager::REPEATER,
                'default'               => [
                    [
                        'hotspot_admin_label'   => __( 'Hotspot #1', 'powerpack' ),
                        'hotspot_text'          => __( '1', 'powerpack' ),
						'selected_icon'         => 'fa fa-plus',
                        'left_position'         => 20,
                        'top_position'          => 30,
                    ],
                ],
                'fields'                => array_values( $repeater->get_controls() ),
                'title_field'           => '{{{ hotspot_admin_label }}}',
            ]
        );
        
        $this->add_control(
            'hotspot_pulse',
            [
                'label'                 => __( 'Glow Effect', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
            ]
        );

        $this->end_controls_section();

        /**
         * Content Tab: Tooltip Settings
         */
        $this->start_controls_section(
            'section_tooltip',
            [
                'label'                 => __( 'Tooltip Settings', 'powerpack' ),
            ]
        );

        $this->add_control(
            'tooltip_trigger',
            [
                'label'                 => __( 'Trigger', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'hover',
                'options'               => [
                    'hover' 	=> __( 'Hover', 'powerpack' ),
                    'click' 	=> __( 'Click', 'powerpack' ),
                ],
                'frontend_available'    => true,
            ]
        );
        
        $this->add_control(
            'tooltip_size',
            [
                'label'                 => __( 'Size', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'default',
                'options'               => [
                    'default'       => __( 'Default', 'powerpack' ),
                    'tiny'          => __( 'Tiny', 'powerpack' ),
                    'small'         => __( 'Small', 'powerpack' ),
                    'large'         => __( 'Large', 'powerpack' )
                ],
            ]
        );
        
        $this->add_control(
            'tooltip_position',
            [
                'label'                 => __( 'Global Position', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'top',
                'options'               => [
                    'top'           => __( 'Top', 'powerpack' ),
                    'bottom'        => __( 'Bottom', 'powerpack' ),
                    'left'          => __( 'Left', 'powerpack' ),
                    'right'         => __( 'Right', 'powerpack' ),
                    'top-left'      => __( 'Top Left', 'powerpack' ),
                    'top-right'     => __( 'Top Right', 'powerpack' ),
                    'bottom-left'   => __( 'Bottom Left', 'powerpack' ),
                    'bottom-right'  => __( 'Bottom Right', 'powerpack' ),
                ],
            ]
        );

        $this->add_control(
            'distance',
            [
                'label'                 => __( 'Distance', 'powerpack' ),
                'description'           => __( 'The distance between the hotspot and the tooltip.', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' 	=> '',
                ],
                'range'                 => [
                    'px' 	=> [
                        'min' 	=> 0,
                        'max' 	=> 100,
                    ],
                ],
                'selectors'             => [
                    '.pp-tooltip.pp-tooltip-{{ID}}.tt-top' 			=> 'transform: translateY(-{{SIZE}}{{UNIT}});',
                    '.pp-tooltip.pp-tooltip-{{ID}}.tt-bottom' 		=> 'transform: translateY({{SIZE}}{{UNIT}});',
                    '.pp-tooltip.pp-tooltip-{{ID}}.tt-left' 			=> 'transform: translateX(-{{SIZE}}{{UNIT}});',
                    '.pp-tooltip.pp-tooltip-{{ID}}.tt-right' 		=> 'transform: translateX({{SIZE}}{{UNIT}});',
                ]
            ]
        );
        
        $this->add_control(
            'tooltip_arrow',
            [
                'label'                 => __( 'Show Arrow', 'powerpack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'powerpack' ),
                'label_off'             => __( 'No', 'powerpack' ),
                'return_value'          => 'yes',
                'frontend_available'    => true,
            ]
        );
        
        $tooltip_animations = [
            ''                  => __( 'Default', 'powerpack' ),
            'bounce'            => __( 'Bounce', 'powerpack' ),
            'flash'             => __( 'Flash', 'powerpack' ),
            'pulse'             => __( 'Pulse', 'powerpack' ),
            'rubberBand'        => __( 'rubberBand', 'powerpack' ),
            'shake'             => __( 'Shake', 'powerpack' ),
            'swing'             => __( 'Swing', 'powerpack' ),
            'tada'              => __( 'Tada', 'powerpack' ),
            'wobble'            => __( 'Wobble', 'powerpack' ),
            'bounceIn'          => __( 'bounceIn', 'powerpack' ),
            'bounceInDown'      => __( 'bounceInDown', 'powerpack' ),
            'bounceInLeft'      => __( 'bounceInLeft', 'powerpack' ),
            'bounceInRight'     => __( 'bounceInRight', 'powerpack' ),
            'bounceInUp'        => __( 'bounceInUp', 'powerpack' ),
            'bounceOut'         => __( 'bounceOut', 'powerpack' ),
            'bounceOutDown'     => __( 'bounceOutDown', 'powerpack' ),
            'bounceOutLeft'     => __( 'bounceOutLeft', 'powerpack' ),
            'bounceOutRight'    => __( 'bounceOutRight', 'powerpack' ),
            'bounceOutUp'       => __( 'bounceOutUp', 'powerpack' ),
            'fadeIn'            => __( 'fadeIn', 'powerpack' ),
            'fadeInDown'        => __( 'fadeInDown', 'powerpack' ),
            'fadeInDownBig'     => __( 'fadeInDownBig', 'powerpack' ),
            'fadeInLeft'        => __( 'fadeInLeft', 'powerpack' ),
            'fadeInLeftBig'     => __( 'fadeInLeftBig', 'powerpack' ),
            'fadeInRight'       => __( 'fadeInRight', 'powerpack' ),
            'fadeInRightBig'    => __( 'fadeInRightBig', 'powerpack' ),
            'fadeInUp'          => __( 'fadeInUp', 'powerpack' ),
            'fadeInUpBig'       => __( 'fadeInUpBig', 'powerpack' ),
            'fadeOut'           => __( 'fadeOut', 'powerpack' ),
            'fadeOutDown'       => __( 'fadeOutDown', 'powerpack' ),
            'fadeOutDownBig'    => __( 'fadeOutDownBig', 'powerpack' ),
            'fadeOutLeft'       => __( 'fadeOutLeft', 'powerpack' ),
            'fadeOutLeftBig'    => __( 'fadeOutLeftBig', 'powerpack' ),
            'fadeOutRight'      => __( 'fadeOutRight', 'powerpack' ),
            'fadeOutRightBig'   => __( 'fadeOutRightBig', 'powerpack' ),
            'fadeOutUp'         => __( 'fadeOutUp', 'powerpack' ),
            'fadeOutUpBig'      => __( 'fadeOutUpBig', 'powerpack' ),
            'flip'              => __( 'flip', 'powerpack' ),
            'flipInX'           => __( 'flipInX', 'powerpack' ),
            'flipInY'           => __( 'flipInY', 'powerpack' ),
            'flipOutX'          => __( 'flipOutX', 'powerpack' ),
            'flipOutY'          => __( 'flipOutY', 'powerpack' ),
            'lightSpeedIn'      => __( 'lightSpeedIn', 'powerpack' ),
            'lightSpeedOut'     => __( 'lightSpeedOut', 'powerpack' ),
            'rotateIn'          => __( 'rotateIn', 'powerpack' ),
            'rotateInDownLeft'  => __( 'rotateInDownLeft', 'powerpack' ),
            'rotateInDownLeft'  => __( 'rotateInDownRight', 'powerpack' ),
            'rotateInUpLeft'    => __( 'rotateInUpLeft', 'powerpack' ),
            'rotateInUpRight'   => __( 'rotateInUpRight', 'powerpack' ),
            'rotateOut'         => __( 'rotateOut', 'powerpack' ),
            'rotateOutDownLeft' => __( 'rotateOutDownLeft', 'powerpack' ),
            'rotateOutDownLeft' => __( 'rotateOutDownRight', 'powerpack' ),
            'rotateOutUpLeft'   => __( 'rotateOutUpLeft', 'powerpack' ),
            'rotateOutUpRight'  => __( 'rotateOutUpRight', 'powerpack' ),
            'hinge'             => __( 'Hinge', 'powerpack' ),
            'rollIn'            => __( 'rollIn', 'powerpack' ),
            'rollOut'           => __( 'rollOut', 'powerpack' ),
            'zoomIn'            => __( 'zoomIn', 'powerpack' ),
            'zoomInDown'        => __( 'zoomInDown', 'powerpack' ),
            'zoomInLeft'        => __( 'zoomInLeft', 'powerpack' ),
            'zoomInRight'       => __( 'zoomInRight', 'powerpack' ),
            'zoomInUp'          => __( 'zoomInUp', 'powerpack' ),
            'zoomOut'           => __( 'zoomOut', 'powerpack' ),
            'zoomOutDown'       => __( 'zoomOutDown', 'powerpack' ),
            'zoomOutLeft'       => __( 'zoomOutLeft', 'powerpack' ),
            'zoomOutRight'      => __( 'zoomOutRight', 'powerpack' ),
            'zoomOutUp'         => __( 'zoomOutUp', 'powerpack' ),
        ];
        
        $this->add_control(
            'tooltip_animation_in',
            [
                'label'                 => __( 'Animation In', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT2,
                'default'               => '',
                'options'               => $tooltip_animations,
            ]
        );
        
        $this->add_control(
            'tooltip_animation_out',
            [
                'label'                 => __( 'Animation Out', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT2,
                'default'               => '',
                'options'               => $tooltip_animations,
            ]
        );
        
        $this->end_controls_section();

        /**
		 * Content Tab: Docs Links
		 *
		 * @since 1.4.8
		 * @access protected
		 */
		$this->start_controls_section(
    			'section_help_docs',
    			[
    				'label' => __( 'Help Docs', 'powerpack' ),
    			]
    		);
    		
    		$this->add_control(
    			'help_doc_1',
    			[
    				'type'            => Controls_Manager::RAW_HTML,
    				/* translators: %1$s doc link */
    				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/image-hotspots/image-hotspots-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
    				'content_classes' => 'pp-editor-doc-links',
    			]
    		);
    
    		$this->end_controls_section();
    

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Image
         */
        $this->start_controls_section(
            'section_image_style',
            [
                'label'                 => __( 'Image', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'image_width',
            [
                'label'                 => __( 'Width', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                    '%' => [
                        'min'   => 1,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-hot-spot-image' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Hotspot
         */
        $this->start_controls_section(
            'section_hotspots_style',
            [
                'label'                 => __( 'Hotspot', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'hotspot_icon_size',
            [
                'label'                 => __( 'Size', 'powerpack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [ 'size' => '14' ],
                'range'                 => [
                    'px' => [
                        'min'   => 6,
                        'max'   => 40,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-hot-spot-wrap' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_normal',
            [
                'label'                 => __( 'Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#fff',
                'selectors'             => [
                    '{{WRAPPER}} .pp-hot-spot-wrap, {{WRAPPER}} .pp-hot-spot-inner, {{WRAPPER}} .pp-hot-spot-inner:before' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .pp-hot-spot-wrap .pp-icon svg' => 'fill: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-hot-spot-wrap, {{WRAPPER}} .pp-hot-spot-inner, {{WRAPPER}} .pp-hot-spot-inner:before, {{WRAPPER}} .pp-hotspot-icon-wrap' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'icon_border_normal',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-hot-spot-wrap'
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-hot-spot-wrap, {{WRAPPER}} .pp-hot-spot-inner, {{WRAPPER}} .pp-hot-spot-inner:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-hot-spot-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'icon_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-hot-spot-wrap',
				'separator'             => 'before',
			]
		);

        $this->end_controls_section();

        /**
         * Style Tab: Tooltip
         */
        $this->start_controls_section(
            'section_tooltips_style',
            [
                'label'                 => __( 'Tooltip', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'tooltip_bg_color',
            [
                'label'                 => __( 'Background Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '.pp-tooltip.pp-tooltip-{{ID}} .pp-tooltip-content' => 'background-color: {{VALUE}};',
                    '.pp-tooltip.pp-tooltip-{{ID}}.tt-top .pp-tooltip-callout:after'    => 'border-top-color: {{VALUE}};',
                    '.pp-tooltip.pp-tooltip-{{ID}}.tt-bottom .pp-tooltip-callout:after' => 'border-bottom-color: {{VALUE}};',
                    '.pp-tooltip.pp-tooltip-{{ID}}.tt-left .pp-tooltip-callout:after' 	=> 'border-left-color: {{VALUE}};',
                    '.pp-tooltip.pp-tooltip-{{ID}}.tt-right .pp-tooltip-callout:after' 	=> 'border-right-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_color',
            [
                'label'                 => __( 'Text Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '.pp-tooltip.pp-tooltip-{{ID}} .pp-tooltip-content' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'tooltip_width',
            [
                'label'         => __( 'Width', 'powerpack' ),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px' 	=> [
                        'min' 	=> 50,
                        'max' 	=> 400,
                        'step'	=> 1,
                    ],
                ],
                'selectors'             => [
                    '.pp-tooltip.pp-tooltip-{{ID}}' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'tooltip_typography',
                'label'                 => __( 'Typography', 'powerpack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '.pp-tooltip.pp-tooltip-{{ID}}',
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'tooltip_border',
				'label'                 => __( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '.pp-tooltip.pp-tooltip-{{ID}} .pp-tooltip-content'
			]
		);

		$this->add_control(
			'tooltip_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'.pp-tooltip.pp-tooltip-{{ID}} .pp-tooltip-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'tooltip_padding',
			[
				'label'                 => __( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'.pp-tooltip.pp-tooltip-{{ID}} .pp-tooltip-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'tooltip_box_shadow',
				'selector'              => '.pp-tooltip.pp-tooltip-{{ID}} .pp-tooltip-content',
			]
		);

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings();
		$fallback_defaults = [
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		];

		if ( empty( $settings['image']['url'] ) ) {
			return;
		}
        ?>
        <div class="pp-image-hotspots">
            <div class="pp-hot-spot-image">
                <?php
                    $i = 1;
                    foreach ( $settings['hot_spots'] as $index => $item ) :

                    $this->add_render_attribute( 'hotspot' . $i, 'class', 'pp-hot-spot-wrap elementor-repeater-item-' . esc_attr( $item['_id'] ) );
        
                    if ( $item['tooltip'] == 'yes' && $item['tooltip_content'] != '' ) {
                        $this->add_render_attribute( 'hotspot' . $i, 'class', 'pp-hot-spot-tooptip' );
                        $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip', $item['tooltip_content'] );
                        
                        if ( $item['tooltip_position_local'] != 'global' ) {
                            $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-position', 'tt-' . $item['tooltip_position_local'] );
                        } else {
                            $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-position', 'tt-' . $settings['tooltip_position'] );
                        }

                        if ( $settings['tooltip_size'] ) {
                            $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-size', $settings['tooltip_size'] );
                        }

                        if ( $settings['tooltip_width'] ) {
                            $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-width', $settings['tooltip_width']['size'] );
                        }

                        if ( $settings['tooltip_animation_in'] ) {
                            $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-animation-in', $settings['tooltip_animation_in'] );
                        }

                        if ( $settings['tooltip_animation_out'] ) {
                            $this->add_render_attribute( 'hotspot' . $i, 'data-tooltip-animation-out', $settings['tooltip_animation_out'] );
                        }
                    }
        
                    $this->add_render_attribute( 'hotspot_inner_' . $i, 'class', 'pp-hot-spot-inner' );
        
                    if ( $settings['hotspot_pulse'] == 'yes' ) {
                        $this->add_render_attribute( 'hotspot_inner_' . $i, 'class', 'hotspot-animation' );
                    }
		
					$migration_allowed = Icons_Manager::is_migration_allowed();
		
					// add old default
					if ( ! isset( $item['hotspot_icon'] ) && ! $migration_allowed ) {
						$item['hotspot_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-plus';
					}

					$migrated = isset( $item['__fa4_migrated']['selected_icon'] );
					$is_new = ! isset( $item['hotspot_icon'] ) && $migration_allowed;
                    ?>
                    <span <?php echo $this->get_render_attribute_string( 'hotspot' . $i ); ?>>
                        <span <?php echo $this->get_render_attribute_string( 'hotspot_inner_' . $i ); ?>>
							<span class="pp-hotspot-icon-wrap">
								<?php
									if ( $item['hotspot_type'] == 'icon' ) {
										if ( ! empty( $item['hotspot_icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) ) {
											echo '<span class="pp-hotspot-icon pp-icon">';
											if ( $is_new || $migrated ) {
												Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] );
											} else { ?>
													<i class="<?php echo esc_attr( $item['hotspot_icon'] ); ?>" aria-hidden="true"></i>
											<?php }
											echo '</span>';
										}
									}
									elseif ( $item['hotspot_type'] == 'text' ) {
										printf( '<span class="pp-hotspot-icon-wrap"><span class="pp-hotspot-text">%1$s</span></span>', esc_attr( $item['hotspot_text'] ) );
									}
								?>
                        	</span>
                        </span>
                    </span>
                <?php $i++; endforeach; ?>
                
                <?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
            </div>
        </div>
        <?php
    }

    /**
	 * Render image hotspots widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <#
            var i = 1;
        #>
        <div class="pp-image-hotspots">
            <div class="pp-hot-spot-image">
                <# _.each( settings.hot_spots, function( item, index ) { #>
                    <#
                        var $tt_size            = ( settings.tooltip_size ) ? settings.tooltip_size : '';
                        var $tt_animation_in    = ( settings.tooltip_animation_in ) ? settings.tooltip_animation_in : '';
                        var $tt_animation_out   = ( settings.tooltip_animation_out ) ? settings.tooltip_animation_out : '';
                        var $hotspot_animation  = ( settings.hotspot_pulse == 'yes' ) ? 'hotspot-animation' : '';
                        var $tt_position = '';
                       
                        var $hotspot_key = 'hot_spots.' + (i - 1) + '.hotspot',
					   		iconsHTML = {},
							migrated = {};
                       
                        view.addRenderAttribute(
                            $hotspot_key,
                            {
                                'class': [ 'pp-hot-spot-wrap', 'elementor-repeater-item-' + item._id ],
                            }
                        );
                       
                        if ( item.tooltip_position_local != 'global' ) {
                            $tt_position = 'tt-' + item.tooltip_position_local;
                        } else {
                            $tt_position = 'tt-' + settings.tooltip_position;
                        }
                       
                        if ( item.tooltip == 'yes' ) {
                            view.addRenderAttribute(
                                $hotspot_key,
                                {
                                    'class': [ 'pp-hot-spot-tooptip' ],
                                    'data-tooltip': item.tooltip_content,
                                    'data-tooltip-position': $tt_position,
                                    'data-tooltip-size': $tt_size,
                                    'data-tooltip-animation-in': $tt_animation_in,
                                    'data-tooltip-animation-out': $tt_animation_out,
                                }
                            );
                        }
                    #>
                    <span {{{ view.getRenderAttributeString( $hotspot_key ) }}}>
                        <span class="pp-hot-spot-inner {{ $hotspot_animation }}">
                            <# if ( item.hotspot_type == 'icon' ) { #>
                                <span class="pp-hotspot-icon-wrap">
                                    <span class="pp-hotspot-icon tooltip pp-icon">
										<#
											iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.selected_icon, { 'aria-hidden': true }, 'i', 'object' );
											migrated[ index ] = elementor.helpers.isIconMigrated( item, 'selected_icon' );
											if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.hotspot_icon || migrated[ index ] ) ) { #>
												{{{ iconsHTML[ index ].value }}}
											<# } else { #>
												<i class="{{ item.hotspot_icon }}" aria-hidden="true"></i>
											<# }
										#>
									</span>
                                </span>
                            <# } else if ( item.hotspot_type == 'text' ) { #>
                                <span class="pp-hotspot-icon-wrap">
                                    <span class="pp-hotspot-icon tooltip">{{ item.hotspot_text }}</span>
                                </span>
                            <# } #>
                        </span>
                    </span>
                <# i++ } ); #>
                
                <# if ( settings.image.url != '' ) { #>
                    <#
                    var image = {
                        id: settings.image.id,
                        url: settings.image.url,
                        size: settings.thumbnail_size,
                        dimension: settings.thumbnail_custom_dimension,
                        model: view.getEditModel()
                    };
                    var image_url = elementor.imagesManager.getImageUrl( image );
                    #>
                    <img src="{{{ image_url }}}" />
                <# } #>
            </div>
        </div>
        <?php
    }
}