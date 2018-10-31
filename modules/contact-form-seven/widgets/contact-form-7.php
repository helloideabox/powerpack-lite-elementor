<?php
namespace PowerpackElements\Modules\ContactFormSeven\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Contact Form 7 Widget
 */
class Contact_Form_7 extends Powerpack_Widget {
    
    /**
	 * Retrieve contact form 7 widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-contact-form-7';
    }

    /**
	 * Retrieve contact form 7 widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Contact Form 7', 'power-pack' );
    }

    /**
	 * Retrieve the list of categories the contact form 7 widget belongs to.
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
	 * Retrieve contact form 7 widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-contact-form power-pack-admin-icon';
    }

    /**
	 * Register contact form 7 widget controls.
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
         * Content Tab: Contact Form
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_info_box',
            [
                'label'                 => __( 'Contact Form', 'power-pack' ),
            ]
        );
		
		$this->add_control(
			'contact_form_list',
			[
				'label'                 => esc_html__( 'Contact Form', 'power-pack' ),
				'type'                  => Controls_Manager::SELECT,
				'label_block'           => true,
				'options'               => pp_get_contact_form_7_forms(),
                'default'               => '0',
			]
		);
        
        $this->add_control(
            'form_title',
            [
                'label'                 => __( 'Form Title', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
		
		$this->add_control(
			'form_title_text',
			[
				'label'                 => esc_html__( 'Title', 'power-pack' ),
				'type'                  => Controls_Manager::TEXT,
				'label_block'           => true,
                'default'               => '',
                'condition'             => [
                    'form_title'   => 'yes',
                ],
			]
		);
        
        $this->add_control(
            'form_description',
            [
                'label'                 => __( 'Form Description', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'label_on'              => __( 'On', 'power-pack' ),
                'label_off'             => __( 'Off', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
		
		$this->add_control(
			'form_description_text',
			[
				'label'                 => esc_html__( 'Description', 'power-pack' ),
				'type'                  => Controls_Manager::TEXTAREA,
                'default'               => '',
                'condition'             => [
                    'form_description'   => 'yes',
                ],
			]
		);
        
        $this->add_control(
            'labels_switch',
            [
                'label'                 => __( 'Labels', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Show', 'power-pack' ),
                'label_off'             => __( 'Hide', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->end_controls_section();

        /**
         * Content Tab: Errors
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_errors',
            [
                'label'                 => __( 'Errors', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'error_messages',
            [
                'label'                 => __( 'Error Messages', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'show',
                'options'               => [
                    'show'          => __( 'Show', 'power-pack' ),
                    'hide'          => __( 'Hide', 'power-pack' ),
                ],
                'selectors_dictionary'  => [
					'show'          => 'block',
					'hide'          => 'none',
				],
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid-tip' => 'display: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'validation_errors',
            [
                'label'                 => __( 'Validation Errors', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'show',
                'options'               => [
                    'show'          => __( 'Show', 'power-pack' ),
                    'hide'          => __( 'Hide', 'power-pack' ),
                ],
                'selectors_dictionary'  => [
					'show'          => 'block',
					'hide'          => 'none',
				],
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors' => 'display: {{VALUE}} !important;',
                ],
            ]
        );
        
        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/

        /**
         * Style Tab: Title & Description
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_fields_title_description',
            [
                'label'                 => __( 'Title & Description', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
            'heading_alignment',
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
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-contact-form-7 .pp-contact-form-7-heading' => 'text-align: {{VALUE}};',
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
            'title_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .pp-contact-form-7-title' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'title_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-contact-form-7 .pp-contact-form-7-title',
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
            'description_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .pp-contact-form-7-description' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'description_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-contact-form-7 .pp-contact-form-7-description',
            ]
        );
        
        $this->end_controls_section();
        
        /**
         * Style Tab: Input & Textarea
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_fields_style',
            [
                'label'                 => __( 'Input & Textarea', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_fields_style' );

        $this->start_controls_tab(
            'tab_fields_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'field_bg',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'field_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'input_spacing',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'      => '20',
                    'unit'      => 'px'
                ],
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 100,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form p:not(:last-of-type) .wpcf7-form-control-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

		$this->add_responsive_control(
			'field_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'text_indent',
            [
                'label'                 => __( 'Text Indent', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 60,
                        'step'  => 1,
                    ],
                    '%'         => [
                        'min'   => 0,
                        'max'   => 30,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'text-indent: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'input_width',
            [
                'label'                 => __( 'Input Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'textarea_width',
            [
                'label'                 => __( 'Textarea Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea' => 'width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'field_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'field_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'field_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select',
				'separator'             => 'before',
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'field_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-text, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-textarea, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control.wpcf7-select',
				'separator'             => 'before',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_fields_focus',
            [
                'label'                 => __( 'Focus', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'field_bg_focus',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input:focus, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form textarea:focus' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'input_border_focus',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input:focus, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form textarea:focus',
                'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'focus_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input:focus, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form textarea:focus',
				'separator'             => 'before',
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Label Section
         */
        $this->start_controls_section(
            'section_label_style',
            [
                'label'                 => __( 'Labels', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );

        $this->add_control(
            'text_color_label',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form label, {{WRAPPER}} .pp-contact-form-7 .wpcf7-form:not(input)' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'label_spacing',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form label' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'typography_label',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form label',
                'condition'             => [
                    'labels_switch'   => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Placeholder Section
         */
        $this->start_controls_section(
            'section_placeholder_style',
            [
                'label'                 => __( 'Placeholder', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'placeholder_switch',
            [
                'label'                 => __( 'Show Placeholder', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'default'               => 'yes',
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );

        $this->add_control(
            'text_color_placeholder',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control::-webkit-input-placeholder' => 'color: {{VALUE}}',
                ],
                'condition'             => [
                    'placeholder_switch'   => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'typography_placeholder',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form-control::-webkit-input-placeholder',
                'condition'             => [
                    'placeholder_switch'   => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();

        /**
         * Style Tab: Radio & Checkbox
         * -------------------------------------------------
         */
        $this->start_controls_section(
            'section_radio_checkbox_style',
            [
                'label'                 => __( 'Radio & Checkbox', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'custom_radio_checkbox',
            [
                'label'                 => __( 'Custom Styles', 'power-pack' ),
                'type'                  => Controls_Manager::SWITCHER,
                'label_on'              => __( 'Yes', 'power-pack' ),
                'label_off'             => __( 'No', 'power-pack' ),
                'return_value'          => 'yes',
            ]
        );
        
        $this->add_responsive_control(
            'radio_checkbox_size',
            [
                'label'                 => __( 'Size', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'      => '15',
                    'unit'      => 'px'
                ],
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 80,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_radio_checkbox_style' );

        $this->start_controls_tab(
            'radio_checkbox_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_checkbox_color',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]' => 'background: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'radio_checkbox_border_width',
            [
                'label'                 => __( 'Border Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 15,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_checkbox_border_color',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]' => 'border-color: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'checkbox_heading',
            [
                'label'                 => __( 'Checkbox', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
				'condition'             => [
					'custom_radio_checkbox' => 'yes',
				],
            ]
        );

		$this->add_control(
			'checkbox_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
			]
		);
        
        $this->add_control(
            'radio_heading',
            [
                'label'                 => __( 'Radio Buttons', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
				'condition'             => [
					'custom_radio_checkbox' => 'yes',
				],
            ]
        );

		$this->add_control(
			'radio_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"], {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'radio_checkbox_checked',
            [
                'label'                 => __( 'Checked', 'power-pack' ),
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'radio_checkbox_color_checked',
            [
                'label'                 => __( 'Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-custom-radio-checkbox input[type="checkbox"]:checked:before, {{WRAPPER}} .pp-custom-radio-checkbox input[type="radio"]:checked:before' => 'background: {{VALUE}}',
                ],
                'condition'             => [
                    'custom_radio_checkbox' => 'yes',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Submit Button
         */
        $this->start_controls_section(
            'section_submit_button_style',
            [
                'label'                 => __( 'Submit Button', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_responsive_control(
			'button_align',
			[
				'label'                 => __( 'Alignment', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'left',
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
				'selectors'             => [
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form p:nth-last-of-type(1)'   => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'display:inline-block;'
				],
                'condition'             => [
                    'button_width_type' => 'custom',
                ],
			]
		);
        
        $this->add_control(
            'button_width_type',
            [
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'custom',
                'options'               => [
                    'full-width'    => __( 'Full Width', 'power-pack' ),
                    'custom'        => __( 'Custom', 'power-pack' ),
                ],
                'prefix_class'          => 'pp-contact-form-7-button-',
            ]
        );
        
        $this->add_responsive_control(
            'button_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size'      => '100',
                    'unit'      => 'px'
                ],
                'range'                 => [
                    'px'        => [
                        'min'   => 0,
                        'max'   => 1200,
                        'step'  => 1,
                    ],
                ],
                'size_units'            => [ 'px', 'em', '%' ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'width: {{SIZE}}{{UNIT}}',
                ],
                'condition'             => [
                    'button_width_type' => 'custom',
                ],
            ]
        );

        $this->start_controls_tabs( 'tabs_button_style' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'button_bg_color_normal',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_normal',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_normal',
				'label'                 => __( 'Border', 'power-pack' ),
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
            'button_margin',
            [
                'label'                 => __( 'Margin Top', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'button_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]',
				'separator'             => 'before',
            ]
        );

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'button_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]',
				'separator'             => 'before',
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'button_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_border_color_hover',
            [
                'label'                 => __( 'Border Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-form input[type="submit"]:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

        /**
         * Style Tab: Errors
         */
        $this->start_controls_section(
            'section_error_style',
            [
                'label'                 => __( 'Errors', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'error_messages_heading',
            [
                'label'                 => __( 'Error Messages', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

        $this->start_controls_tabs( 'tabs_error_messages_style' );

        $this->start_controls_tab(
            'tab_error_messages_alert',
            [
                'label'                 => __( 'Alert', 'power-pack' ),
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

        $this->add_control(
            'error_alert_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid-tip' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );
        
        $this->add_responsive_control(
            'error_alert_spacing',
            [
                'label'                 => __( 'Spacing', 'power-pack' ),
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
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid-tip' => 'margin-top: {{SIZE}}{{UNIT}}',
                ],
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_error_messages_fields',
            [
                'label'                 => __( 'Fields', 'power-pack' ),
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

        $this->add_control(
            'error_field_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid' => 'background: {{VALUE}}',
                ],
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

        $this->add_control(
            'error_field_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'error_messages' => 'show',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'error_field_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-not-valid',
				'separator'             => 'before',
				'condition'             => [
					'error_messages' => 'show',
				],
			]
		);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        
        $this->add_control(
            'validation_errors_heading',
            [
                'label'                 => __( 'Validation Errors', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
                'separator'             => 'before',
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

        $this->add_control(
            'validation_errors_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors' => 'background: {{VALUE}}',
                ],
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

        $this->add_control(
            'validation_errors_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'validation_errors_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors',
				'separator'             => 'before',
				'condition'             => [
					'validation_errors' => 'show',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'validation_errors_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors',
				'separator'             => 'before',
				'condition'             => [
					'validation_errors' => 'show',
				],
			]
		);

		$this->add_responsive_control(
			'validation_errors_margin',
			[
				'label'                 => __( 'Margin', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', 'em', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-contact-form-7 .wpcf7-validation-errors' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'validation_errors' => 'show',
				],
			]
		);
        
        $this->end_controls_section();

    }

    /**
	 * Render contact form 7 widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings();
        
        $this->add_render_attribute( 'contact-form', 'class', [
				'pp-contact-form',
				'pp-contact-form-7',
			]
		);
        
        if ( $settings['labels_switch'] != 'yes' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'labels-hide' );
        }
        
        if ( $settings['placeholder_switch'] == 'yes' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'placeholder-show' );
        }
        
        if ( $settings['custom_radio_checkbox'] == 'yes' ) {
            $this->add_render_attribute( 'contact-form', 'class', 'pp-custom-radio-checkbox' );
        }
        
        if ( function_exists( 'wpcf7' ) ) {
            if ( ! empty( $settings['contact_form_list'] ) ) { ?>
                <div <?php echo $this->get_render_attribute_string( 'contact-form' ); ?>>
                    <?php if ( $settings['form_title'] == 'yes' || $settings['form_description'] == 'yes' ) { ?>
                        <div class="pp-contact-form-7-heading">
                            <?php if ( $settings['form_title'] == 'yes' && $settings['form_title_text'] != '' ) { ?>
                                <h3 class="pp-contact-form-title pp-contact-form-7-title">
                                    <?php echo esc_attr( $settings['form_title_text'] ); ?>
                                </h3>
                            <?php } ?>
                            <?php if ( $settings['form_description'] == 'yes' && $settings['form_description_text'] != '' ) { ?>
                                <div class="pp-contact-form-description pp-contact-form-7-description">
                                    <?php echo $this->parse_text_editor( $settings['form_description_text'] ); ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php echo do_shortcode( '[contact-form-7 id="' . $settings['contact_form_list'] . '" ]' ); ?>
                </div>
                <?php
            }
        }
    }

    /**
	 * Render contact form 7 widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {}
}