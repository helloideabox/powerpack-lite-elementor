<?php
namespace PowerpackElementsLite\Modules\AdvancedAccordion\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
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
 * Advanced Accordion Widget
 */
class Advanced_Accordion extends Powerpack_Widget {

	/**
	 * Retrieve advanced accordion widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'pp-advanced-accordion';
	}

	/**
	 * Retrieve advanced accordion widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Advanced Accordion', 'power-pack' );
	}

	/**
	 * Retrieve the list of categories the advanced accordion widget belongs to.
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
	 * Retrieve advanced accordion widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'ppicon-tabs power-pack-admin-icon';
	}

    /**
	 * Retrieve the list of scripts the advanced accordion widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
        return [
            'powerpack-frontend',
        ];
    }

	/**
	 * Register advanced tabs widget controls.
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
  		 * Content Tab: Tabs
  		 */
  		$this->start_controls_section(
  			'section_accordion_tabs',
  			[
  				'label'                 => esc_html__( 'Tabs', 'power-pack' )
  			]
  		);
        
        $repeater = new Repeater();

        $repeater->add_control(
            'accordion_tab_default_active',
            [
                'label'                 => esc_html__( 'Active as Default', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'no',
                'return_value'          => 'yes',
            ]
        );

        $repeater->add_control(
            'accordion_tab_icon_show',
            [
                'label'                 => esc_html__( 'Enable Tab Icon', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'return_value'          => 'yes',
            ]
        );

        $repeater->add_control(
            'accordion_tab_title_icon',
            [
                'label'                 => esc_html__( 'Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => 'fa fa-plus',
                'condition'             => [
                    'accordion_tab_icon_show' => 'yes'
                ]
            ]
        );

        $repeater->add_control(
            'tab_title',
            [
                'label'                 => __( 'Title', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'default'               => __( 'Accordion Title', 'power-pack' ),
                'dynamic'               => [
                    'active'   => true,
                ],
            ]
        );
        
        $repeater->add_control(
			'content_type',
			[
				'label'                 => esc_html__( 'Content Type', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'label_block'           => false,
                'options'               => [
                    'content'   => __( 'Content', 'power-pack' ),
                    'image'     => __( 'Image', 'power-pack' ),
                    'video' 	=> __( 'Video', 'power-pack' ),
                    'section'   => __( 'Saved Section', 'power-pack' ),
                    'widget'    => __( 'Saved Widget', 'power-pack' ),
                    'template'  => __( 'Saved Page Template', 'power-pack' ),
                ],
				'default'               => 'content',
			]
		);

        $repeater->add_control(
            'accordion_content',
            [
                'label'                 => esc_html__( 'Content', 'power-pack' ),
                'type'                  => Controls_Manager::WYSIWYG,
                'default'               => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                'dynamic'               => [ 'active' => true ],
                'condition'             => [
                    'content_type'	=> 'content',
                ],
            ]
        );
        
        $repeater->add_control(
            'image',
            [
                'label'                 => __( 'Image', 'power-pack' ),
                'type'                  => Controls_Manager::MEDIA,
                'dynamic'               => [
                    'active'   => true,
                ],
                'default'               => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'conditions'            => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'image',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'saved_widget',
            [
                'label'                 => __( 'Choose Widget', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => $this->get_page_template_options( 'widget' ),
				'default'               => '-1',
				'condition'             => [
					'content_type'    => 'widget',
				],
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'widget',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'saved_section',
            [
                'label'                 => __( 'Choose Section', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => $this->get_page_template_options( 'section' ),
				'default'               => '-1',
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'section',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'templates',
            [
                'label'                 => __( 'Choose Template', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => $this->get_page_template_options( 'page' ),
				'default'               => '-1',
                'conditions'        => [
                    'terms' => [
                        [
                            'name'      => 'content_type',
                            'operator'  => '==',
                            'value'     => 'template',
                        ],
                    ],
                ],
            ]
        );
        
  		$this->add_control(
			'tabs',
			[
				'type'                  => Controls_Manager::REPEATER,
				'default'               => [
					[ 'tab_title' => esc_html__( 'Accordion Tab Title 1', 'power-pack' ) ],
					[ 'tab_title' => esc_html__( 'Accordion Tab Title 2', 'power-pack' ) ],
					[ 'tab_title' => esc_html__( 'Accordion Tab Title 3', 'power-pack' ) ],
				],
				'fields'                => array_values( $repeater->get_controls() ),
				'title_field'           => '{{tab_title}}',
			]
		);

  		$this->end_controls_section();

  		$this->start_controls_section(
  			'section_accordion_toggle_icon',
  			[
  				'label'                 => esc_html__( 'Toggle Icon', 'power-pack' )
  			]
  		);
        
		$this->add_control(
			'toggle_icon_show',
			[
				'label'                 => esc_html__( 'Toggle Icon', 'power-pack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
				'return_value'          => 'yes',
			]
		);
        
		$this->add_control(
			'toggle_icon',
			[
				'label'                 => esc_html__( 'Icon', 'power-pack' ),
				'type'                  => Controls_Manager::ICON,
				'default'               => 'fa fa-angle-right',
				'include'               => [
					'fa fa-angle-right',
					'fa fa-angle-double-right',
					'fa fa-chevron-right',
					'fa fa-chevron-circle-right',
					'fa fa-arrow-right',
					'fa fa-long-arrow-right',
				],
				'condition'             => [
					'toggle_icon_show' => 'yes'
				]
			]
		);

  		$this->end_controls_section();

  		$this->start_controls_section(
  			'section_accordion_settings',
  			[
  				'label'                 => esc_html__( 'Settings', 'power-pack' )
  			]
  		);
        
  		$this->add_control(
		  'accordion_type',
		  	[
                'label'                 => esc_html__( 'Accordion Type', 'power-pack' ),
		     	'type'                  => Controls_Manager::SELECT,
		     	'default'               => 'accordion',
		     	'label_block'           => false,
		     	'options'               => [
		     		'accordion' 	=> esc_html__( 'Accordion', 'power-pack' ),
		     		'toggle' 		=> esc_html__( 'Toggle', 'power-pack' ),
		     	],
				'frontend_available'    => true,
		  	]
		);
        
		$this->add_control(
			'toggle_speed',
			[
				'label'                 => esc_html__( 'Toggle Speed (ms)', 'power-pack' ),
				'type'                  => Controls_Manager::NUMBER,
				'label_block'           => false,
				'default'               => 300,
				'frontend_available'    => true,
			]
		);
		$this->end_controls_section();
  		
        /**
  		 * Style Tab: Items
  		 */
		$this->start_controls_section(
			'section_accordion_items_style',
			[
				'label'                 => esc_html__( 'Items', 'power-pack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_responsive_control(
            'accordion_items_spacing',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px' 	=> [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default'               => [
                    'size' 	=> '',
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-accordion-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'accordion_items_border',
				'label'                 => esc_html__( 'Border', 'power-pack' ),
				'selector'              => '{{WRAPPER}} .pp-accordion-item',
			]
		);
        
		$this->add_responsive_control(
			'accordion_items_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
	 					'{{WRAPPER}} .pp-accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);
        
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'accordion_items_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-accordion-item',
			]
		);

  		$this->end_controls_section();
		
  		/**
  		 * Style Tab: Title
  		 */
		$this->start_controls_section(
			'section_title_style',
			[
				'label'                 => esc_html__( 'Title', 'power-pack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'accordion_tabs_style' );

		$this->start_controls_tab(
			'accordion_tab_normal',
			[
				'label'                 => __( 'Normal', 'power-pack' ),
			]
		);

		$this->add_control(
			'tab_title_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#f1f1f1',
				'selectors'	=> [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_title_text_color',
			[
				'label'                 => esc_html__( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#333333',
				'selectors'             => [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name'                  => 'tab_title_typography',
				'selector'              => '{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title',
			]
		);
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'                  => 'tab_title_border',
                'label'                 => esc_html__( 'Border', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title',
            ]
        );

        $this->end_controls_tab();

		$this->start_controls_tab(
			'accordion_tab_hover',
			[
				'label'                 => __( 'Hover', 'power-pack' ),
			]
		);

		$this->add_control(
			'tab_title_bg_color_hover',
			[
				'label'                 => esc_html__( 'Background Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#414141',
				'selectors'	=> [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_title_text_color_hover',
			[
				'label'                 => esc_html__( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#ffffff',
				'selectors'             => [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_title_border_color_hover',
			[
				'label'                 => esc_html__( 'Border Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#ffffff',
				'selectors'             => [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title:hover' => 'border-color: {{VALUE}};',
				],
			]
		);
        
        $this->end_controls_tab();

		$this->start_controls_tab(
			'accordion_tab_active',
			[
				'label'                 => __( 'Active', 'power-pack' ),
			]
		);

		$this->add_control(
			'tab_title_bg_color_active',
			[
				'label'                 => esc_html__( 'Background Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#414141',
				'selectors'	=> [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title.pp-accordion-tab-active' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_title_text_color_active',
			[
				'label'                 => esc_html__( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#ffffff',
				'selectors'             => [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title.pp-accordion-tab-active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_title_border_color_active',
			[
				'label'                 => esc_html__( 'Border Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#ffffff',
				'selectors'             => [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title.pp-accordion-tab-active' => 'border-color: {{VALUE}};',
				],
			]
		);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

		$this->add_control(
			'tab_icon_heading',
			[
				'label'                 => __( 'Icon', 'power-pack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_responsive_control(
			'tab_icon_size',
			[
				'label'                 => __( 'Icon Size', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size'	=> 16,
					'unit'	=> 'px',
				],
				'size_units'            => [ 'px' ],
				'range'                 => [
					'px'		=> [
						'min'	=> 0,
						'max'	=> 100,
						'step'	=> 1,
					]
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title .fa' => 'font-size: {{SIZE}}{{UNIT}};',
				]
			]
		);
		$this->add_responsive_control(
			'tab_icon_spacing',
			[
				'label'                 => __( 'Icon Spacing', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size'	=> 10,
					'unit'	=> 'px',
				],
				'size_units'            => [ 'px' ],
				'range'                 => [
					'px'	=> [
						'min'	=> 0,
						'max'	=> 100,
						'step'	=> 1,
					]
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title .fa' => 'margin-right: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();
		
  		/**
  		 * Style Tab: Content
  		 */
		$this->start_controls_section(
			'section_content_style',
			[
				'label'                 => esc_html__( 'Content', 'power-pack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tab_content_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'	=> [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-item .pp-accordion-tab-content' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'tab_content_text_color',
			[
				'label'                 => esc_html__( 'Text Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#333',
				'selectors'             => [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-item .pp-accordion-tab-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
            	'name'                  => 'tab_content_typography',
				'selector'              => '{{WRAPPER}} .pp-advanced-accordion .pp-accordion-item .pp-accordion-tab-content',
			]
		);

		$this->add_responsive_control(
			'tab_content_padding',
			[
				'label'                 => esc_html__( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
	 					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-item .pp-accordion-tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
	 			],
			]
		);

  		$this->end_controls_section();

  		/**
  		 * Style tab: Toggle Icon
  		 */
  		$this->start_controls_section(
  			'section_toggle_icon_style',
  			[
  				'label'                 => esc_html__( 'Toggle icon', 'power-pack' ),
  				'tab'                   => Controls_Manager::TAB_STYLE,
  			]
  		);

		$this->add_control(
			'toggle_icon_color',
			[
				'label'                 => esc_html__( 'Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#444',
				'selectors'	=> [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title .pp-accordion-toggle-icon' => 'color: {{VALUE}};',
				],
				'condition'	=> [
					'toggle_icon_show' => 'yes'
				]
			]
		);
        
		$this->add_control(
			'toggle_icon_active_color',
			[
				'label'                 => esc_html__( 'Active Color', 'power-pack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#fff',
				'selectors'	=> [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title.pp-accordion-tab-active .pp-accordion-toggle-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-item:hover .pp-accordion-tab-title .pp-accordion-toggle-icon' => 'color: {{VALUE}};',
				],
				'condition'             => [
					'toggle_icon_show' => 'yes'
				]
			]
		);
        
		$this->add_responsive_control(
			'toggle_icon_size',
			[
				'label'                 => __( 'Size', 'power-pack' ),
				'type'                  => Controls_Manager::SLIDER,
				'default'               => [
					'size'	=> 16,
					'unit'	=> 'px',
				],
				'size_units'            => [ 'px' ],
				'range'	=> [
					'px'	=> [
						'min'	=> 0,
						'max'	=> 100,
						'step'	=> 1,
					]
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-advanced-accordion .pp-accordion-tab-title .pp-accordion-toggle-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'toggle_icon_show' => 'yes'
				]
			]
		);
        
  		$this->end_controls_section();
	}

	protected function render() {

		$settings	= $this->get_settings_for_display();
		$id_int		= substr( $this->get_id_int(), 0, 3 );
		
		$this->add_render_attribute( 'accordion', [
            'class'                 => 'pp-advanced-accordion',
            'id'                    => 'pp-advanced-accordion-'.esc_attr( $this->get_id() ),
            'data-accordion-id'     => esc_attr( $this->get_id() )
        ] );
        ?>
        <div <?php echo $this->get_render_attribute_string('accordion'); ?>>
            <?php
                foreach( $settings['tabs'] as $index => $tab ) {

                    $tab_count = $index+1;
                    $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);
                    $tab_content_setting_key = $this->get_repeater_setting_key('accordion_content', 'tabs', $index);	

                    $tab_title_class 	= 'pp-accordion-tab-title';
                    $tab_content_class 	= 'pp-accordion-tab-content';

                    if ( $tab['accordion_tab_default_active'] == 'yes' ) {
                        $tab_title_class[] 		= 'active-default';
                        $tab_content_class[] 	= 'active-default';
                    }

                    $this->add_render_attribute( $tab_title_setting_key, [
                        'id'                => 'pp-accordion-tab-title-' . $id_int . $tab_count,
                        'class'             => $tab_title_class,
                        'tabindex'          => $id_int . $tab_count,
                        'data-tab'          => $tab_count,
                        'role'              => 'tab',
                        'aria-controls'     => 'pp-accordion-tab-content-' . $id_int . $tab_count,
                    ]);

                    $this->add_render_attribute( $tab_content_setting_key, [
                        'id'                => 'elementor-tab-content-' . $id_int . $tab_count,
                        'class'             => $tab_content_class,
                        'data-tab'          => $tab_count,
                        'role'              => 'tabpanel',
                        'aria-labelledby'   => 'pp-accordion-tab-title-' . $id_int . $tab_count,
                    ] );
            ?>
            <div class="pp-accordion-item">

                <div <?php echo $this->get_render_attribute_string($tab_title_setting_key); ?>>
                    <span class="pp-accordion-title-icon">
                        <span class="pp-accordion-tab-icon">
                            <?php if ( $tab['accordion_tab_icon_show'] === 'yes' ) { ?>
                                <i class="<?php echo esc_attr( $tab['accordion_tab_title_icon'] ); ?> fa-accordion-icon"></i>
                            <?php } ?>
                        </span>
                        <span class="pp-accordion-title-text">
                            <?php echo $tab['tab_title']; ?>
                        </span>
                    </span>
                    <?php if ( $settings['toggle_icon_show'] === 'yes' ) { ?>
                        <span class="pp-accordion-toggle-icon <?php echo esc_attr( $settings['toggle_icon'] ); ?>"></span>
                    <?php } ?>
                </div>

                <div <?php echo $this->get_render_attribute_string($tab_content_setting_key); ?>>
                    <?php
                        if( $tab['content_type'] == 'content' ) {

                            echo do_shortcode( $tab['accordion_content'] );

                        } elseif ( $tab['content_type'] == 'section' && !empty( $tab['saved_section'] ) ) {

                            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $tab['saved_section'] );

                        } elseif ( $tab['content_type'] == 'template' && !empty( $tab['templates'] ) ) {

                            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $tab['templates'] );

                        } elseif ( $tab['content_type'] == 'widget' && !empty( $tab['saved_widget'] ) ) {

                            echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $tab['saved_widget'] );

                        }
                    ?>
                </div>

            </div>
            <?php } ?>
        </div>
	<?php
	}

	/**
	 *  Get Saved Widgets
	 *
	 *  @param string $type Type.
	 *  
	 *  @return string
	 */
	public function get_page_template_options( $type = '' ) {

		$page_templates = pp_get_page_templates( $type );

		$options[-1]   = __( 'Select', 'power-pack' );

		if ( count( $page_templates ) ) {
			foreach ( $page_templates as $id => $name ) {
				$options[ $id ] = $name;
			}
		} else {
			$options['no_template'] = __( 'No saved templates found!', 'power-pack' );
		}

		return $options;
	}

	protected function _content_template() {  }
}