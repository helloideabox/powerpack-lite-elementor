<?php
namespace PowerpackElementsLite\Modules\Buttons\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Buttons Widget
 */
class Buttons extends Powerpack_Widget {

	/**
	 * Retrieve buttons widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Buttons' );
	}

	/**
	 * Retrieve buttons widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Buttons' );
	}

	/**
	 * Retrieve buttons widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Buttons' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Buttons' );
	}

	/**
	 * Retrieve the list of scripts the advanced menu widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'pp-tooltip',
			'powerpack-frontend',
		);
	}
	/**
	 * Register buttons widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		/*
		-----------------------------------------------------------------------------------*/
		/*
		  CONTENT TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Content Tab: Buttons
		 */
		$this->start_controls_section(
			'section_list',
			array(
				'label' => __( 'Buttons', 'powerpack' ),
			)
		);
		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'buttons_tabs' );

			$repeater->start_controls_tab(
				'button_general',
				array(
					'label' => __( 'Content', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'text',
				array(
					'label'       => __( 'Text', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => __( 'Button #1', 'powerpack' ),
					'placeholder' => __( 'Button #1', 'powerpack' ),
					'dynamic'     => array(
						'active' => true,
					),
				)
			);
			$repeater->add_control(
				'pp_icon_type',
				array(
					'label'       => __( 'Icon Type', 'powerpack' ),
					'type'        => Controls_Manager::CHOOSE,
					'label_block' => false,
					'toggle'      => false,
					'default'     => 'icon',
					'options'     => array(
						'none'  => array(
							'title' => esc_html__( 'None', 'powerpack' ),
							'icon'  => 'fa fa-ban',
						),
						'icon'  => array(
							'title' => esc_html__( 'Icon', 'powerpack' ),
							'icon'  => 'fa fa-star',
						),
						'image' => array(
							'title' => esc_html__( 'Image', 'powerpack' ),
							'icon'  => 'fa fa-picture-o',
						),
						'text'  => array(
							'title' => esc_html__( 'Text', 'powerpack' ),
							'icon'  => 'fa fa-hashtag',
						),
					),
				)
			);
			$repeater->add_control(
				'selected_icon',
				array(
					'label'            => __( 'Icon', 'powerpack' ),
					'type'             => Controls_Manager::ICONS,
					'label_block'      => true,
					'default'          => array(
						'value'   => 'fas fa-check',
						'library' => 'fa-solid',
					),
					'fa4compatibility' => 'button_icon',
					'condition'        => array(
						'pp_icon_type' => 'icon',
					),
				)
			);
			$repeater->add_control(
				'icon_img',
				array(
					'label'       => __( 'Image', 'powerpack' ),
					'label_block' => true,
					'type'        => Controls_Manager::MEDIA,
					'default'     => array(
						'url' => Utils::get_placeholder_image_src(),
					),
					'dynamic'     => array(
						'active' => true,
					),
					'condition'   => array(
						'pp_icon_type' => 'image',
					),
				)
			);
			$repeater->add_control(
				'icon_text',
				array(
					'label'       => __( 'Icon Text', 'powerpack' ),
					'label_block' => false,
					'type'        => Controls_Manager::TEXT,
					'default'     => __( '1', 'powerpack' ),
					'dynamic'     => array(
						'active' => true,
					),
					'condition'   => array(
						'pp_icon_type' => 'text',
					),
				)
			);

			$repeater->add_control(
				'has_tooltip',
				array(
					'label'   => __( 'Enable Tooltip', 'powerpack' ),
					'type'    => Controls_Manager::SWITCHER,
					'default' => 'no',
					'yes'     => __( 'Yes', 'powerpack' ),
					'no'      => __( 'No', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'tooltip_content',
				array(
					'label'       => __( 'Tooltip Content', 'powerpack' ),
					'type'        => Controls_Manager::TEXTAREA,
					'default'     => __( 'I am a tooltip for a button', 'powerpack' ),
					'placeholder' => __( 'I am a tooltip for a button', 'powerpack' ),
					'rows'        => 5,
					'condition'   => array(
						'has_tooltip' => 'yes',
					),
				)
			);

			$repeater->add_control(
				'link',
				array(
					'label'       => __( 'Link', 'powerpack' ),
					'type'        => Controls_Manager::URL,
					'dynamic'     => array(
						'active' => true,
					),
					'label_block' => true,
					'placeholder' => __( 'http://your-link.com', 'powerpack' ),
				)
			);
			$repeater->add_control(
				'css_id',
				array(
					'label'       => __( 'CSS ID', 'powerpack' ),
					'title'       => __( 'Add your custom ID WITHOUT the # key. e.g: my-id', 'powerpack' ),
					'label_block' => false,
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active' => true,
					),
				)
			);
			$repeater->add_control(
				'css_classes',
				array(
					'label'       => __( 'CSS Classes', 'powerpack' ),
					'title'       => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'powerpack' ),
					'label_block' => false,
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active' => true,
					),
				)
			);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab(
				'button_layout_tab',
				array(
					'label' => __( 'Layout', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'single_button_size',
				array(
					'label'   => __( 'Button Size', 'powerpack' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'default',
					'options' => array(
						'default' => __( 'Default', 'powerpack' ),
						'xs'      => __( 'Extra Small', 'powerpack' ),
						'sm'      => __( 'Small', 'powerpack' ),
						'md'      => __( 'Medium', 'powerpack' ),
						'lg'      => __( 'Large', 'powerpack' ),
						'xl'      => __( 'Extra Large', 'powerpack' ),
						'custom'  => __( 'Custom', 'powerpack' ),
					),
				)
			);

			$repeater->add_responsive_control(
				'single_button_width',
				array(
					'label'      => __( 'Button Width', 'powerpack' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', '%' ),
					'range'      => array(
						'px' => array(
							'min'  => 10,
							'max'  => 800,
							'step' => 1,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'single_button_size' => 'custom',
					),
				)
			);

			$repeater->add_responsive_control(
				'single_button_padding',
				array(
					'label'      => __( 'Padding', 'powerpack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$repeater->end_controls_tab();

			$repeater->start_controls_tab(
				'button_style_tabs',
				array(
					'label' => __( 'Style', 'powerpack' ),
				)
			);

			$repeater->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'     => 'single_title_typography',
					'label'    => __( 'Button Typography', 'powerpack' ),
					'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.pp-button .pp-button-title',
				)
			);

			$repeater->add_responsive_control(
				'single_icon_size',
				array(
					'label'     => __( 'Icon Size', 'powerpack' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'px' => array(
							'min'  => 5,
							'max'  => 100,
							'step' => 1,
						),
					),
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}} span.pp-button-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} {{CURRENT_ITEM}} .pp-button-icon-image img' => 'width: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'pp_icon_type!' => 'none',
					),
				)
			);

			$repeater->add_control(
				'single_normal_options',
				array(
					'label'     => __( 'Normal', 'powerpack' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'after',
				)
			);
			$repeater->add_control(
				'single_button_bg_color',
				array(
					'label'     => __( 'Background Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button' => 'background: {{VALUE}};',
					),
				)
			);
			$repeater->add_control(
				'single_text_color',
				array(
					'label'     => __( 'Text Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button' => 'color: {{VALUE}};',
					),
				)
			);
			$repeater->add_control(
				'single_icon_color',
				array(
					'label'     => __( 'Icon Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button .pp-buttons-icon-wrapper span' => 'color: {{VALUE}};',
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button .pp-buttons-icon-wrapper .pp-icon svg' => 'fill: {{VALUE}};',
					),
				)
			);
			$repeater->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'single_button_border',
					'label'       => __( 'Border', 'powerpack' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} {{CURRENT_ITEM}}.pp-button',
				)
			);
			$repeater->add_control(
				'single_button_border_radius',
				array(
					'label'      => __( 'Border Radius', 'powerpack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);

			$repeater->add_control(
				'single_hover_options',
				array(
					'label'     => __( 'Hover', 'powerpack' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'after',
				)
			);

			$repeater->add_control(
				'single_button_bg_color_hover',
				array(
					'label'     => __( 'Background Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button:hover' => 'background: {{VALUE}};',
					),
				)
			);

			$repeater->add_control(
				'single_text_color_hover',
				array(
					'label'     => __( 'Text Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button:hover' => 'color: {{VALUE}};',
					),
				)
			);

			$repeater->add_control(
				'single_icon_color_hover',
				array(
					'label'     => __( 'Icon Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button:hover .pp-buttons-icon-wrapper span' => 'color: {{VALUE}};',
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button:hover .pp-buttons-icon-wrapper .pp-icon svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$repeater->add_control(
				'single_border_color_hover',
				array(
					'label'     => __( 'Border Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-button:hover' => 'border-color: {{VALUE}};',
					),
				)
			);

			$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'buttons',
			array(
				'label'       => __( 'Buttons', 'powerpack' ),
				'type'        => Controls_Manager::REPEATER,
				'show_label'  => true,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array(
						'text' => __( 'Button #1', 'powerpack' ),
					),
					array(
						'text' => __( 'Button #2', 'powerpack' ),
					),
				),
			)
		);
		$this->end_controls_section();

		$this->end_controls_section();

		if ( ! is_pp_elements_active() ) {
			$this->start_controls_section(
				'section_upgrade_powerpack',
				array(
					'label' => apply_filters( 'upgrade_powerpack_title', __( 'Get PowerPack Pro', 'powerpack' ) ),
					'tab'   => Controls_Manager::TAB_CONTENT,
				)
			);

			$this->add_control(
				'upgrade_powerpack_notice',
				array(
					'label'           => '',
					'type'            => Controls_Manager::RAW_HTML,
					'raw'             => apply_filters( 'upgrade_powerpack_message', sprintf( __( 'Upgrade to %1$s Pro Version %2$s for 70+ widgets, exciting extensions and advanced features.', 'powerpack' ), '<a href="#" target="_blank" rel="noopener">', '</a>' ) ),
					'content_classes' => 'upgrade-powerpack-notice elementor-panel-alert elementor-panel-alert-info',
				)
			);

			$this->end_controls_section();
		}

		/**
		 * Style Tab: Layout
		 */
		$this->start_controls_section(
			'button_layout',
			array(
				'label' => __( 'Layout', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_control(
			'button_size',
			array(
				'label'   => __( 'Buttons Size', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => array(
					'xs' => __( 'Extra Small', 'powerpack' ),
					'sm' => __( 'Small', 'powerpack' ),
					'md' => __( 'Medium', 'powerpack' ),
					'lg' => __( 'Large', 'powerpack' ),
					'xl' => __( 'Extra Large', 'powerpack' ),
				),
			)
		);
		$this->add_responsive_control(
			'button_spacing',
			array(
				'label'     => __( 'Buttons Spacing', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 10,
				),
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-buttons-group .pp-button:not(:last-child)'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'(desktop){{WRAPPER}}.pp-buttons-stack-desktop .pp-buttons-group .pp-button:not(:last-child)'  => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}}.pp-buttons-stack-tablet .pp-buttons-group .pp-button:not(:last-child)'  => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}}.pp-buttons-stack-mobile .pp-buttons-group .pp-button:not(:last-child)'  => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'vertical_align',
			array(
				'label'        => __( 'Vertical Alignment', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'middle',
				'options'      => array(
					'top'     => array(
						'title' => __( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle'  => array(
						'title' => __( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom'  => array(
						'title' => __( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
					'stretch' => array(
						'title' => __( 'Stretch', 'powerpack' ),
						'icon'  => 'eicon-v-align-stretch',
					),
				),
				'prefix_class' => 'pp-buttons-valign%s-',
			)
		);

		$this->add_responsive_control(
			'button_align',
			array(
				'label'        => __( 'Horizontal Alignment', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'default'      => 'left',
				'options'      => array(
					'left'    => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
					'stretch' => array(
						'title' => __( 'Stretch', 'powerpack' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'prefix_class' => 'pp-buttons-halign%s-',
			)
		);

		$this->add_responsive_control(
			'content_align',
			array(
				'label'                => __( 'Content Alignment', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'default'              => 'left',
				'options'              => array(
					'left'    => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
					'stretch' => array(
						'title' => __( 'Stretch', 'powerpack' ),
						'icon'  => 'eicon-h-align-stretch',
					),
				),
				'selectors_dictionary' => array(
					'left'    => 'flex-start',
					'center'  => 'center',
					'right'   => 'flex-end',
					'stretch' => 'stretch',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-button .pp-button-content-wrapper'   => 'justify-content: {{VALUE}};',
				),
				'condition'            => array(
					'button_align' => 'stretch',
				),
			)
		);

		$this->add_control(
			'stack_on',
			array(
				'label'        => __( 'Stack on', 'powerpack' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'none',
				'description'  => __( 'Choose a breakpoint where the buttons will stack.', 'powerpack' ),
				'options'      => array(
					'none'    => __( 'None', 'powerpack' ),
					'desktop' => __( 'Desktop', 'powerpack' ),
					'tablet'  => __( 'Tablet', 'powerpack' ),
					'mobile'  => __( 'Mobile', 'powerpack' ),
				),
				'prefix_class' => 'pp-buttons-stack-',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Styling
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_box_button_style',
			array(
				'label' => __( 'Styling', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .pp-button',
			)
		);
		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

			$this->add_control(
				'button_bg_color_normal',
				array(
					'label'     => __( 'Background Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'scheme'    => array(
						'type'  => Scheme_Color::get_type(),
						'value' => Scheme_Color::COLOR_4,
					),
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .pp-button' => 'background: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'button_text_color_normal',
				array(
					'label'     => __( 'Text Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ffffff',
					'selectors' => array(
						'{{WRAPPER}} .pp-button' => 'color: {{VALUE}}',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Border::get_type(),
				array(
					'name'        => 'button_border_normal',
					'label'       => __( 'Border', 'powerpack' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .pp-button',
				)
			);
			$this->add_responsive_control(
				'button_border_radius',
				array(
					'label'      => __( 'Border Radius', 'powerpack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'{{WRAPPER}} .pp-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'button_box_shadow',
					'selector' => '{{WRAPPER}} .pp-button',
				)
			);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => __( 'Hover', 'powerpack' ),
			)
		);

			$this->add_control(
				'button_bg_color_hover',
				array(
					'label'     => __( 'Background Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .pp-button:hover' => 'background: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'button_text_color_hover',
				array(
					'label'     => __( 'Text Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .pp-button:hover' => 'color: {{VALUE}}',
					),
				)
			);
			$this->add_control(
				'button_border_color_hover',
				array(
					'label'     => __( 'Border Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .pp-button:hover' => 'border-color: {{VALUE}}',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'     => 'button_box_shadow_hover',
					'selector' => '{{WRAPPER}} .pp-button:hover',
				)
			);
			$this->add_control(
				'button_animation',
				array(
					'label' => __( 'Animation', 'powerpack' ),
					'type'  => Controls_Manager::HOVER_ANIMATION,
				)
			);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Icon
		 */
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => __( 'Icon', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'icon_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .pp-button-icon-number',
			)
		);
		$this->add_responsive_control(
			'icon_position',
			array(
				'label'   => __( 'Icon Position', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'before',
				'options' => array(
					'after'  => __( 'After', 'powerpack' ),
					'before' => __( 'Before', 'powerpack' ),
					'top'    => __( 'Top', 'powerpack' ),
					'bottom' => __( 'Bottom', 'powerpack' ),
				),
			)
		);
		$this->add_responsive_control(
			'icon_size',
			array(
				'label'     => __( 'Size', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '',
				),
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-button-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-button-icon-image img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);
		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'     => __( 'Spacing', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 8,
				),
				'range'     => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-icon-before .pp-buttons-icon-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-icon-after .pp-buttons-icon-wrapper' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-icon-top .pp-buttons-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-icon-bottom .pp-buttons-icon-wrapper' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

			$this->start_controls_tab(
				'tab_icon_normal',
				array(
					'label' => __( 'Normal', 'powerpack' ),
				)
			);
			$this->add_control(
				'icon_color',
				array(
					'label'     => __( 'Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .pp-buttons-icon-wrapper span' => 'color: {{VALUE}};',
						'{{WRAPPER}} .pp-buttons-icon-wrapper .pp-icon svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_icon_hover',
				array(
					'label' => __( 'Hover', 'powerpack' ),
				)
			);

			$this->add_control(
				'icon_color_hover',
				array(
					'label'     => __( 'Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} .pp-button:hover .pp-buttons-icon-wrapper .pp-button-icon' => 'color: {{VALUE}};',
						'{{WRAPPER}} .pp-button:hover .pp-buttons-icon-wrapper .pp-icon svg' => 'fill: {{VALUE}};',
					),
				)
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Tooltip
		 * -------------------------------------------------
		 */

		$this->start_controls_section(
			'section_tooltip_style',
			array(
				'label' => __( 'Tooltip', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

			$this->add_responsive_control(
				'tooltips_position',
				array(
					'label'   => __( 'Tooltip Position', 'powerpack' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'above',
					'options' => array(
						'above' => __( 'Above', 'powerpack' ),
						'below' => __( 'Below', 'powerpack' ),
						'left'  => __( 'Left', 'powerpack' ),
						'right' => __( 'Right', 'powerpack' ),
					),
				)
			);
			$this->add_control(
				'tooltips_align',
				array(
					'label'     => __( 'Text Align', 'powerpack' ),
					'type'      => Controls_Manager::CHOOSE,
					'default'   => ' center',
					'options'   => array(
						'left'   => array(
							'title' => __( 'Left', 'powerpack' ),
							'icon'  => 'fa fa-align-left',
						),
						'center' => array(
							'title' => __( 'Center', 'powerpack' ),
							'icon'  => 'fa fa-align-center',
						),
						'right'  => array(
							'title' => __( 'Right', 'powerpack' ),
							'icon'  => 'fa fa-align-right',
						),
					),
					'selectors' => array(
						'.pp-tooltip-{{ID}}' => 'text-align: {{VALUE}};',
					),
				)
			);
			$this->add_responsive_control(
				'tooltips_padding',
				array(
					'label'      => __( 'Padding', 'powerpack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'.pp-tooltip-{{ID}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_responsive_control(
				'tooltips_border_radius',
				array(
					'label'      => __( 'Border Radius', 'powerpack' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'selectors'  => array(
						'.pp-tooltip-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'tooltips_typography',
					'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
					'separator' => 'after',
					'selector'  => '.pp-tooltip-{{ID}}',
				)
			);
			$this->add_control(
				'tooltips_background_color',
				array(
					'label'     => __( 'Background Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#000000',
					'selectors' => array(
						'.pp-tooltip-{{ID}}' => 'background-color: {{VALUE}};',
						'.pp-tooltip-{{ID}}.above .tooltip-callout:after' => 'border-top-color: {{VALUE}};',
						'.pp-tooltip-{{ID}}.left .tooltip-callout:after' => 'border-left-color: {{VALUE}};',
						'.pp-tooltip-{{ID}}.right .tooltip-callout:after' => 'border-right-color: {{VALUE}};',
						'.pp-tooltip-{{ID}}.below .tooltip-callout:after' => 'border-bottom-color: {{VALUE}};',
					),
				)
			);
			$this->add_control(
				'tooltips_color',
				array(
					'label'     => __( 'Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#ffffff',
					'selectors' => array(
						'.pp-tooltip-{{ID}}' => 'color: {{VALUE}};',
					),
				)
			);
			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				array(
					'name'      => 'tooltips_box_shadow',
					'selector'  => '.pp-tooltip-{{ID}}',
					'separator' => '',
				)
			);

		$this->end_controls_section();
	}

	/**
	 * Render buttons widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$fallback_defaults = array(
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		);

		// Button Animation
		$button_animation = '';
		if ( $settings['button_animation'] ) {
			$button_animation = 'elementor-animation-' . $settings['button_animation'];
		}

		$i = 1;
		?>
		<div class="pp-buttons-group">
			<?php foreach ( $settings['buttons'] as $index => $item ) : ?>
				<?php
				$button_key        = $this->get_repeater_setting_key( 'button', 'buttons', $index );
				$content_inner_key = $this->get_repeater_setting_key( 'content', 'buttons', $index );

				// Button Size
				$buttonSize = ( $item['single_button_size'] != 'default' ) ? $item['single_button_size'] : $settings['button_size'];

				// Link
				if ( ! empty( $item['link']['url'] ) ) {
					$this->add_link_attributes( $button_key, $item['link'] );
				}

				// Icon Position
				$iconPosition = '';
				if ( $settings['icon_position'] ) {
					$iconPosition = 'pp-icon-' . $settings['icon_position'];
				}
				if ( $settings['icon_position_tablet'] ) {
					$iconPosition .= ' pp-icon-' . $settings['icon_position_tablet'] . '-tablet';
				}
				if ( $settings['icon_position_mobile'] ) {
					$iconPosition .= ' pp-icon-' . $settings['icon_position_mobile'] . '-mobile';
				}

				$this->add_render_attribute(
					$button_key,
					'class',
					array(
						'pp-button',
						'elementor-button',
						'elementor-size-' . $buttonSize,
						'elementor-repeater-item-' . $item['_id'],
						$button_animation,
					)
				);

				// CSS ID
				if ( $item['css_id'] ) {
					$this->add_render_attribute( $button_key, 'id', $item['css_id'] );
				}

				// Custom Class
				if ( $item['css_classes'] ) {
					$this->add_render_attribute( $button_key, 'class', $item['css_classes'] );
				}

				// ToolTip
				if ( $item['has_tooltip'] == 'yes' && ! empty( $item['tooltip_content'] ) ) {
					$ttip_position        = $this->get_tooltip_position( $settings['tooltips_position'] );
					$ttip_position_tablet = $this->get_tooltip_position( $settings['tooltips_position_tablet'] );
					$ttip_position_mobile = $this->get_tooltip_position( $settings['tooltips_position_mobile'] );

					if ( $settings['tooltips_position_tablet'] ) {
						$ttip_tablet = $ttip_position_tablet;
					} else {
						$ttip_tablet = $ttip_position;
					};

					if ( $settings['tooltips_position_mobile'] ) {
						$ttip_mobile = $ttip_position_mobile;
					} else {
						$ttip_mobile = $ttip_position;
					};

					$this->add_render_attribute(
						$button_key,
						array(
							'data-tooltip'                 => htmlspecialchars( $item['tooltip_content'] ),
							'data-tooltip-position'        => $ttip_position,
							'data-tooltip-position-tablet' => $ttip_tablet,
							'data-tooltip-position-mobile' => $ttip_mobile,
						)
					);
				}

				$this->add_render_attribute(
					$content_inner_key,
					'class',
					array(
						'pp-button-content-inner',
						$iconPosition,
					)
				);
				?>
				<a <?php echo $this->get_render_attribute_string( $button_key ); ?>>
					<div class="pp-button-content-wrapper">
						<span <?php echo $this->get_render_attribute_string( $content_inner_key ); ?>>
							<?php
							if ( $item['pp_icon_type'] != 'none' ) {
								$icon_key  = 'icon_' . $i;
								$icon_wrap = 'pp-buttons-icon-wrapper';
								$this->add_render_attribute( $icon_key, 'class', $icon_wrap );
								$migration_allowed = Icons_Manager::is_migration_allowed();
								?>
								<span <?php echo $this->get_render_attribute_string( $icon_key ); ?>>
									<?php
									if ( $item['pp_icon_type'] == 'icon' ) {
										// add old default
										if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
											$item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
										}

										$migrated = isset( $item['__fa4_migrated']['selected_icon'] );
										$is_new   = ! isset( $item['icon'] ) && $migration_allowed;

										if ( ! empty( $item['icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) ) {
											?>
											<span class="pp-button-icon pp-icon">
												<?php
												if ( $is_new || $migrated ) {
													Icons_Manager::render_icon(
														$item['selected_icon'],
														array(
															'class'       => 'pp-button-icon',
															'aria-hidden' => 'true',
														)
													);
												} else {
													?>
														<i class="pp-button-icon <?php echo esc_attr( $item['icon'] ); ?>" aria-hidden="true"></i>
														<?php
												}
												?>
											</span>
											<?php
										}
									} elseif ( $item['pp_icon_type'] == 'image' ) {
										printf( '<span class="pp-button-icon-image"><img src="%1$s"></span>', esc_url( $item['icon_img']['url'] ) );
									} elseif ( $item['pp_icon_type'] == 'text' ) {
										printf( '<span class="pp-button-icon pp-button-icon-number">%1$s</span>', esc_attr( $item['icon_text'] ) );
									}
									?>
								</span>
								<?php
							}
							if ( $item['text'] ) {
								?>
								<?php
								$text_key = $this->get_repeater_setting_key( 'text', 'buttons', $index );
								$this->add_render_attribute( $text_key, 'class', 'pp-button-title' );
								$this->add_inline_editing_attributes( $text_key, 'none' );
								?>

								<span <?php echo $this->get_render_attribute_string( $text_key ); ?>>
								<?php
									echo $item['text'];
								?>
								</span>
							<?php } ?>
						</span>
					</div>
				</a>
				<?php
				$i++;
endforeach;
			?>
		</div>
		<?php
	}

	protected function get_tooltip_position( $pos ) {
		$tt_position = '';

		if ( $pos === 'above' ) {
			$tt_position = 'tt-top';
		} elseif ( $pos === 'below' ) {
			$tt_position = 'tt-bottom';
		} elseif ( $pos === 'left' ) {
			$tt_position = 'tt-left';
		} elseif ( $pos === 'right' ) {
			$tt_position = 'tt-right';
		} else {
			$tt_position = 'tt-top';
		}

		return $tt_position;
	}

	/**
	 * Render buttons widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function _content_template() {
		?>
		<div class="pp-buttons-group">
			<# var i = 1; #>
			<# _.each( settings.buttons, function( item, index ) { #>
				<#
				var button_key = 'button_' + i,
					content_inner_key = 'content-inner_' + i,
					buttonSize = '',
					iconPosition = '',
					iconsHTML = {},
					migrated = {};

				if ( item.single_button_size != 'default' ) {
					buttonSize = item.single_button_size;
				} else {
					buttonSize = settings.button_size;
				}

				if ( settings.icon_position ) {
					iconPosition = 'pp-icon-' + settings.icon_position;
				}

				if ( settings.icon_position_tablet ) {
					iconPosition += ' pp-icon-' + settings.icon_position_tablet + '-tablet';
				}

				if ( settings.icon_position_mobile ) {
					iconPosition += ' pp-icon-' + settings.icon_position_mobile + '-mobile';
				}

				view.addRenderAttribute(
					button_key,
					{
						'id': item.css_id,
						'class': [
							'pp-button',
							'elementor-button',
							'elementor-size-' + buttonSize,
							'elementor-repeater-item-' + item._id,
							'elementor-animation-' + settings.button_animation,
							item.css_classes,
						],
					}
				);

				if ( item.has_tooltip == 'yes' && item.tooltip_content != '' ) {
					var ttip_tablet;
					var ttip_mobile;
				   
					if ( settings.tooltips_position_tablet ) {
						ttip_tablet = settings.tooltips_position_tablet;
					} else { 
						ttip_tablet = settings.tooltips_position;
					};
					if ( settings.tooltips_position_mobile ) {
						ttip_mobile = settings.tooltips_position_mobile;
					} else { 
						ttip_mobile = settings.tooltips_position;
					};
				   
					view.addRenderAttribute(
						button_key,
						{
							'data-tooltip': item.tooltip_content,
							'data-tooltip-position': settings.tooltips_position,
							'data-tooltip-position-tablet': ttip_tablet,
							'data-tooltip-position-mobile': ttip_mobile,
						}
					);
				}

				if ( item.link.url != '' ) {
					view.addRenderAttribute( button_key, 'href', item.link.url );

					if ( item.link.is_external ) {
						view.addRenderAttribute( button_key, 'target', '_blank' );
					}

					if ( item.link.nofollow ) {
						view.addRenderAttribute( button_key, 'rel', 'nofollow' );
					}
				}

				view.addRenderAttribute(
					content_inner_key,
					{
						'class': [
							'pp-button-content-inner',
							iconPosition,
						],
					}
				);
				#>

				<a {{{ view.getRenderAttributeString( button_key ) }}}>
					<div class="pp-button-content-wrapper">
						<span {{{ view.getRenderAttributeString( content_inner_key ) }}}>
							<# if ( item.pp_icon_type != 'none' ) { #>
								<#
									var icon_key = 'icon_' + i;
							   
									view.addRenderAttribute( icon_key, 'class', 'pp-buttons-icon-wrapper' );
								#>
								<span {{{ view.getRenderAttributeString( icon_key ) }}}>
									<# if ( item.pp_icon_type == 'icon' ) { #>
										<# if ( item.button_icon || item.selected_icon.value ) { #>
											<span class="pp-button-icon pp-icon">
											<#
												iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.selected_icon, { 'aria-hidden': true }, 'i', 'object' );
												migrated[ index ] = elementor.helpers.isIconMigrated( item, 'selected_icon' );
												if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.button_icon || migrated[ index ] ) ) { #>
													{{{ iconsHTML[ index ].value }}}
												<# } else { #>
													<i class="{{ item.button_icon }}" aria-hidden="true"></i>
												<# }
											#>
											</span>
										<# } #>
									<# } else if ( item.pp_icon_type == 'image' ) { #>
										<span class="pp-button-icon-image">
											<img src="{{{ item.icon_img.url }}}">
										</span>
									<# } else if ( item.pp_icon_type == 'text' ) { #>
										<span class="pp-button-icon pp-button-icon-number">
											{{{ item.icon_text }}}
										</span>
									<# } #>
								</span>
							<# } #>
								
							<# if ( item.text != '' ) { #>
								<#
									var text_key = 'text_' + i;
							   
									view.addRenderAttribute( text_key, 'class', 'pp-button-title' );
								   
									view.addInlineEditingAttributes( text_key, 'none' );
								#>
								
								<span {{{ view.getRenderAttributeString( text_key ) }}}>
									{{{ item.text }}}
								</span>
							<# } #>
						</span>
					</div>
				</a>
			<# i++ } ); #>
		</div>
		<?php
	}
}
