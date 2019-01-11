<?php
namespace PowerpackElementsLite\Modules\ImageAccordion\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Image Accordion Widget
 */
class Image_Accordion extends Powerpack_Widget {
    
    /**
	 * Retrieve image accordion widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-image-accordion';
    }

    /**
	 * Retrieve image accordion widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Image Accordion', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the image accordion widget belongs to.
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
	 * Retrieve image accordion widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-contact-form power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the image accordion widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'powerpack-frontend'
        ];
    }

    /**
	 * Register image accordion widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	Content Tab
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Items
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_items',
            [
                'label'                 => esc_html__( 'Items', 'power-pack' )
            ]
        );
        
        $repeater = new Repeater();
        
        $repeater->start_controls_tabs( 'image_accordion_tabs' );

        $repeater->start_controls_tab( 'tab_content', [ 'label' => __( 'Content', 'power-pack' ) ] );
        
        $repeater->add_control(
			'title',
			[
                'label'                 => esc_html__( 'Title', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
                'label_block'           => true,
                'default'               => esc_html__( 'Accordion Title', 'power-pack' ),
                'dynamic'               => [
                    'active'   => true,
                ],
			]
		);
        
        $repeater->add_control(
			'description',
			[
                'label'                 => esc_html__( 'Description', 'power-pack' ),
                'type'                  => Controls_Manager::WYSIWYG,
                'label_block'           => true,
                'default'               => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                'dynamic'               => [
                    'active'   => true,
                ],
			]
		);
        
        $repeater->add_control(
			'link',
			[
                'label'                 => esc_html__( 'Title Link', 'power-pack' ),
                'type'                  => Controls_Manager::URL,
                'label_block'           => true,
                'default'               => [
                    'url'           => '#',
                    'is_external'   => '',
                ],
                'show_external'         => true,
			]
		);
        
        $repeater->end_controls_tab();
        
        $repeater->start_controls_tab( 'tab_image', [ 'label' => __( 'Image', 'power-pack' ) ] );
        
        $repeater->add_control(
			'image',
			[
                'label'                 => esc_html__( 'Choose Image', 'power-pack' ),
                'type'                  => Controls_Manager::MEDIA,
                'label_block'           => true,
                'dynamic'               => [
                    'active'   => true,
                ],
                'default'               => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
			]
		);
        
        $repeater->end_controls_tab();
        
        $repeater->end_controls_tabs();

        $this->add_control(
            'accordion_items',
            [
                'type'                  => Controls_Manager::REPEATER,
                'seperator'             => 'before',
                'default'               => [
                    [
                        'title'         => esc_html__( 'Accordion #1', 'power-pack' ),
                        'description'   => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                        'image'         => [
                            'url' => Utils::get_placeholder_image_src(),
                        ]
                    ],
                    [
                        'title'         => esc_html__( 'Accordion #2', 'power-pack' ),
                        'description'   => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                        'image'         => [
                            'url' => Utils::get_placeholder_image_src(),
                        ]
                    ],
                    [
                        'title'         => esc_html__( 'Accordion #3', 'power-pack' ),
                        'description'   => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                        'image'         => [
                            'url' => Utils::get_placeholder_image_src(),
                        ]
                    ],
                    [
                        'title'         => esc_html__( 'Accordion #4', 'power-pack' ),
                        'description'   => esc_html__( 'Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'power-pack' ),
                        'image'         => [
                            'url' => Utils::get_placeholder_image_src(),
                        ]
                    ],
                  ],
                'fields' 		=> array_values( $repeater->get_controls() ),
                'title_field' => '{{title}}',
            ]
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_image_accordion_settings',
            [
                'label'                 => esc_html__( 'Settings', 'power-pack' )
            ]
        );
        
        $this->add_responsive_control(
            'accordion_height',
            [
                'label'                 => esc_html__( 'Height', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 50,
                        'max'   => 1000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
				'default'               => [
					'size' => 400,
					'unit' => 'px',
				],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-accordion' => 'height: {{SIZE}}px',
                ],
            ]
        );

        $this->add_control(
            'accordion_action',
            [
                'label'                 => esc_html__( 'Accordion Action', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'on-hover',
                'label_block'           => false,
                'options'               => [
                    'on-hover'  => esc_html__( 'On Hover', 'power-pack' ),
                    'on-click'  => esc_html__( 'On Click', 'power-pack' ),
                ],
                'frontend_available'    => true,
            ]
        );
        
        $this->end_controls_section();

      /**
       * -------------------------------------------
       * Tab Style (Image accordion)
       * -------------------------------------------
       */
        $this->start_controls_section(
            'section_image_accordion_style',
            [
                'label'                 => esc_html__( 'Image Accordion', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'accordion_bg_color',
            [
                'label'                 => esc_html__( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-accordion' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'                  => 'accordion_border',
                'label'                 => esc_html__( 'Border', 'power-pack' ),
                'selector'              => '{{WRAPPER}} .pp-image-accordion',
            ]
        );

		$this->add_control(
			'accordion_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-accordion' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'accordion_container_margin',
            [
                'label'                 => esc_html__( 'Margin', 'power-pack' ),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-accordion' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'accordion_container_padding',
            [
                'label'                 => esc_html__( 'Padding', 'power-pack' ),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-accordion' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'                  => 'accordion_box_shadow',
                'selector'              => '{{WRAPPER}} .pp-image-accordion',
            ]
        );
        
        $this->add_control(
            'overlay_heading',
            [
                'label'                 => __( 'Overlay', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
            ]
        );

        $this->add_control(
            'accordion_img_overlay_color',
            [
                'label'                 => esc_html__( 'Overlay Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => 'rgba(0, 0, 0, .3)',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-accordion a:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'accordion_img_hover_color',
            [
                'label'                 => esc_html__( 'Overlay Hover Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => 'rgba(0, 0, 0, .5)',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-accordion a:hover::after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .pp-image-accordion a.pp-image-accordion-overlay-active:after' => 'background-color: {{VALUE}};',
                ],
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
                'tab'                   => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'                 => esc_html__( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#fff',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-accordion .pp-image-accordion-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'selector'              => '{{WRAPPER}} .pp-image-accordion .pp-image-accordion-title',
            ]
        );

        $this->end_controls_section();

        /**
         * Style Tab: Description
         */
        $this->start_controls_section(
            'section_description_style',
            [
                'label'                 => esc_html__( 'Description', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label'                 => esc_html__( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#fff',
                'selectors'             => [
                    '{{WRAPPER}} .pp-image-accordion .pp-image-accordion-description' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'description_typography',
                'selector'              => '{{WRAPPER}} .pp-image-accordion .pp-image-accordion-description',
            ]
        );

      $this->end_controls_section();

  }


    protected function render( ) {

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'image-accordion', [
            'class'     => [ 'pp-image-accordion', 'pp-image-accordion-' . $settings['accordion_action'] ],
            'id'        => 'pp-image-accordion-' . $this->get_id(),
        ] );

        if ( !empty( $settings['accordion_items'] ) ) {
            ?>
            <div <?php echo $this->get_render_attribute_string( 'image-accordion' ); ?>>
                <?php foreach( $settings['accordion_items'] as $index => $item ) { ?>
                    <?php
                        $link_key = $this->get_repeater_setting_key( 'link', 'accordion_items', $index );

                        $this->add_render_attribute( $link_key, [
                            'class' => 'elementor-repeater-item-' . esc_attr( $item['_id'] ),
                            'style' => 'background-image: url(' . esc_url( $item['image']['url'] ) . ');',
                        ] );
                
                        if ( ! empty( $item['link']['url'] ) ) {
                            $this->add_render_attribute( $link_key, 'href', esc_url( $item['link']['url'] ) );

                            if ( $item['link']['is_external'] ) {
                                $this->add_render_attribute( $link_key, 'target', '_blank' );
                            }

                            if ( $item['link']['nofollow'] ) {
                                $this->add_render_attribute( $link_key, 'rel', 'nofollow' );
                            }
                        }
                    ?>
                    <a <?php echo $this->get_render_attribute_string( $link_key ); ?>>
                        <div class="pp-image-accordion-overlay">
                            <div class="pp-image-accordion-content">
                                <h2 class="pp-image-accordion-title">
                                    <?php echo $item['title']; ?>
                                </h2>
                                <div class="pp-image-accordion-description">
                                    <?php echo $item['description']; ?>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        <?php }
    }

    protected function content_template() {
    }
}