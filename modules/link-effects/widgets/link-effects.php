<?php
namespace PowerpackElements\Modules\LinkEffects\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Link Effects Widget
 */
class Link_Effects extends Powerpack_Widget {
    
    /**
	 * Retrieve link effects widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pa-link-effects';
    }

    /**
	 * Retrieve link effects widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Link Effects', 'power-pack' );
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
        return 'ppicon-link-effects power-pack-admin-icon';
    }

    /**
	 * Register link effects widget controls.
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
         * Content Tab: Link Effects
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_link_effects',
            [
                'label'                 => __( 'Link Effects', 'power-pack' ),
            ]
        );

        $this->add_control(
            'text',
            [
                'label'                 => __( 'Text', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Click Here', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'secondary_text',
            [
                'label'                 => __( 'Secondary Text', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => __( 'Click Here', 'power-pack' ),
                'condition'             => [
                    'effect'    => 'effect-9',
                ],
            ]
        );

        $this->add_control(
            'link',
            [
                'label'                 => __( 'Link', 'power-pack' ),
                'type'                  => Controls_Manager::URL,
				'dynamic'               => [
					'active'   => true,
				],
                'placeholder'           => 'https://www.your-link.com',
                'default'               => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'effect',
            [
                'label'                 => __( 'Effect', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'options'               => [
                   'effect-1'  => __( 'Bottom Border Slides In', 'power-pack' ),
                   'effect-2'  => __( 'Bottom Border Slides Out', 'power-pack' ),
                   'effect-3'  => __( 'Brackets', 'power-pack' ),
                   'effect-4'  => __( '3D Rolling Cube', 'power-pack' ),
                   'effect-5'  => __( 'Same Word Slide In', 'power-pack' ),
                   'effect-6'  => __( 'Right Angle Slides Down over Title', 'power-pack' ),
                   'effect-7'  => __( 'Second Border Slides Up', 'power-pack' ),
                   'effect-8'  => __( 'Border Slight Translate', 'power-pack' ),
                   'effect-9'  => __( 'Second Text and Borders', 'power-pack' ),
                   'effect-10' => __( 'Push Out', 'power-pack' ),
                   'effect-11' => __( 'Text Fill', 'power-pack' ),
                   'effect-12' => __( 'Circle', 'power-pack' ),
                   'effect-13' => __( 'Three Circles', 'power-pack' ),
                   'effect-14' => __( 'Border Switch', 'power-pack' ),
                   'effect-15' => __( 'Scale Down', 'power-pack' ),
                   'effect-16' => __( 'Fall Down', 'power-pack' ),
                   'effect-17' => __( 'Move Up and Push Border', 'power-pack' ),
                   'effect-18' => __( 'Cross', 'power-pack' ),
                   'effect-19' => __( '3D Side', 'power-pack' ),
                   'effect-20' => __( 'Unfold', 'power-pack' ),
                   'effect-21' => __( 'Borders Slight Yranslate', 'power-pack' ),
                ],
                'default'               => 'effect-1',
            ]
        );
        
        $this->add_responsive_control(
			'align',
			[
				'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
					'justify'   => [
						'title' => __( 'Justified', 'power-pack' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}}'   => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Link Effects
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_style',
            [
                'label'                 => __( 'Link Effects', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} a.pa-link',
            ]
        );
        
        $this->add_responsive_control(
            'divider_title_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'  => 200,
                ],
                'range'                 => [
                    'px' => [
                        'min'   => 1,
                        'max'   => 1000,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pa-link-effect-19' => 'width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .pa-link-effect-19 span' => 'transform-origin: 50% 50% calc(-{{SIZE}}{{UNIT}}/2)',
                ],
                'condition'             => [
                    'effect' => 'effect-19',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_link_style' );

        $this->start_controls_tab(
            'tab_link_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'link_color_normal',
            [
                'label'                 => __( 'Link Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} a.pa-link, {{WRAPPER}} .pa-link-effect-10 span, {{WRAPPER}} .pa-link-effect-15:before, {{WRAPPER}} .pa-link-effect-16, {{WRAPPER}} .pa-link-effect-17:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pa-link-effect-4 span, {{WRAPPER}} .pa-link-effect-10 span, {{WRAPPER}} .pa-link-effect-19 span, {{WRAPPER}} .pa-link-effect-20 span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_border_color',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pa-link-effect-8:before' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .pa-link-effect-11' => 'border-top-color: {{VALUE}};',
                    '{{WRAPPER}} .pa-link-effect-1:after, {{WRAPPER}} .pa-link-effect-2:after, {{WRAPPER}} .pa-link-effect-6:before, {{WRAPPER}} .pa-link-effect-6:after, {{WRAPPER}} .pa-link-effect-7:before, {{WRAPPER}} .pa-link-effect-7:after, {{WRAPPER}} .pa-link-effect-14:before, {{WRAPPER}} .pa-link-effect-14:after, {{WRAPPER}} .pa-link-effect-18:before, {{WRAPPER}} .pa-link-effect-18:after' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .pa-link-effect-3:before, {{WRAPPER}} .pa-link-effect-3:after' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .pa-link-effect-20 span' => 'box-shadow: inset 0 3px {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_link_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'link_color_hover',
            [
                'label'                 => __( 'Link Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} a.pa-link:hover, {{WRAPPER}} .pa-link-effect-10:before, {{WRAPPER}} .pa-link-effect-11:before, {{WRAPPER}} .pa-link-effect-15, {{WRAPPER}} .pa-link-effect-16:before, {{WRAPPER}} .pa-link-effect-20 span:before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pa-link-effect-4 span:before, {{WRAPPER}} .pa-link-effect-10:before, {{WRAPPER}} .pa-link-effect-19 span:before, {{WRAPPER}} .pa-link-effect-20 span:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pa-link-effect-8:after' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .pa-link-effect-11:before' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .pa-link-effect-9:before, {{WRAPPER}} .pa-link-effect-9:after, {{WRAPPER}} .pa-link-effect-14:hover:before, {{WRAPPER}} .pa-link-effect-14:focus:before, {{WRAPPER}} .pa-link-effect-14:hover:after, {{WRAPPER}} .pa-link-effect-14:focus:after, {{WRAPPER}} .pa-link-effect-17:after, {{WRAPPER}} .pa-link-effect-18:hover:before, {{WRAPPER}} .pa-link-effect-18:focus:before, {{WRAPPER}} .pa-link-effect-18:hover:after, {{WRAPPER}} .pa-link-effect-18:focus:after, {{WRAPPER}} .pa-link-effect-21:before, {{WRAPPER}} .pa-link-effect-21:after' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .pa-link-effect-17' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .pa-link-effect-13:hover:before, {{WRAPPER}} .pa-link-effect-13:focus:before' => 'color: {{VALUE}}; text-shadow: 10px 0 {{VALUE}}, -10px 0 {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_section();

    }

    /**
	 * Render link effects widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // get our input from the widget settings.

        $pa_link_text = ! empty( $settings['text'] ) ? $settings['text'] : '';
        $pa_link_secondary_text = ! empty( $settings['secondary_text'] ) ? $settings['secondary_text'] : '';

        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_render_attribute( 'pa-link', 'href', $settings['link']['url'] );

            if ( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'pa-link', 'target', '_blank' );
            }
        }

        $this->add_render_attribute( 'pa-link', 'class', 'pa-link' );

        if ( $settings['effect'] ) {
            $this->add_render_attribute( 'pa-link', 'class', 'pa-link-' . $settings['effect'] );
        }
        
        if ( $settings['effect'] == 'effect-4' || $settings['effect'] == 'effect-5' || $settings['effect'] == 'effect-19' || $settings['effect'] == 'effect-20' ) {
            $this->add_render_attribute( 'pa-link-text', 'data-hover', $pa_link_text );
        }
        
        if ( $settings['effect'] == 'effect-10' || $settings['effect'] == 'effect-11' || $settings['effect'] == 'effect-15' || $settings['effect'] == 'effect-16' || $settings['effect'] == 'effect-17' || $settings['effect'] == 'effect-18' ) {
            $this->add_render_attribute( 'pa-link-text-2', 'data-hover', $pa_link_text );
        }
        ?>

        <a <?php echo $this->get_render_attribute_string( 'pa-link' ); ?><?php echo $this->get_render_attribute_string( 'pa-link-text-2' ); ?>>
            <span <?php echo $this->get_render_attribute_string( 'pa-link-text' ); ?>>
                <?php echo $pa_link_text; ?>
            </span>
            <?php if( $settings['effect'] == 'effect-9' ) { ?>
                <span><?php echo esc_attr($pa_link_secondary_text); ?></span>
            <?php } ?>
        </a>

        <?php
    }

    protected function _content_template() {}

}