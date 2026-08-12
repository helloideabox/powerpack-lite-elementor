<?php
namespace PowerpackElementsLite\Modules\Divider\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Divider Widget
 */
class Divider extends Powerpack_Widget {

	/**
	 * Retrieve divider widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Divider' );
	}

	/**
	 * Retrieve divider widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Divider' );
	}

	/**
	 * Retrieve divider widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Divider' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the divider widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Divider' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 2.11.0
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return [ 'widget-pp-divider' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! PP_Helper::is_feature_active( 'e_optimized_markup' );
	}

	/**
	 * Register divider widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.4.0
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_divider_controls();

		/* Style Tab */
		$this->register_style_divider_controls();
	}

	/**
	 * Register Icon Controls in Content tab
	 *
	 * @return void
	 */
	protected function register_content_divider_controls() {

		/*-----------------------------------------------------------------------------------*/
		/*	CONTENT TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Content Tab: Divider
		 */
		$this->start_controls_section(
			'section_buton',
			[
				'label'                 => esc_html__( 'Divider', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'divider_type',
			[
				'label'                 => esc_html__( 'Add Element', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'default'               => 'plain',
				'options'               => [
					'plain'        => [
						'title'    => esc_html__( 'None', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-ban',
					],
					'text'         => [
						'title'    => esc_html__( 'Text', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-t-letter-bold',
					],
					'icon'         => [
						'title'    => esc_html__( 'Icon', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-star',
					],
					'image'        => [
						'title'    => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-image',
					],
				],
				'toggle'                => false,
			]
		);

		$this->add_control(
			'divider_text',
			[
				'label'                 => esc_html__( 'Text', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active' => true,
				],
				'default'               => esc_html__( 'Divider Text', 'powerpack-lite-for-elementor' ),
				'condition'             => [
					'divider_type' => 'text',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'                 => esc_html__( 'Icon', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::ICONS,
				'fa4compatibility'      => 'divider_icon',
				'default'               => [
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'condition'             => [
					'divider_type'  => 'icon',
				],
			]
		);

		$this->add_control(
			'text_html_tag',
			[
				'label'                 => esc_html__( 'HTML Tag', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'span',
				'options'               => [
					'h1'            => esc_html__( 'H1', 'powerpack-lite-for-elementor' ),
					'h2'            => esc_html__( 'H2', 'powerpack-lite-for-elementor' ),
					'h3'            => esc_html__( 'H3', 'powerpack-lite-for-elementor' ),
					'h4'            => esc_html__( 'H4', 'powerpack-lite-for-elementor' ),
					'h5'            => esc_html__( 'H5', 'powerpack-lite-for-elementor' ),
					'h6'            => esc_html__( 'H6', 'powerpack-lite-for-elementor' ),
					'div'           => esc_html__( 'div', 'powerpack-lite-for-elementor' ),
					'span'          => esc_html__( 'span', 'powerpack-lite-for-elementor' ),
					'p'             => esc_html__( 'p', 'powerpack-lite-for-elementor' ),
				],
				'condition'             => [
					'divider_type' => 'text',
				],
			]
		);

		$this->add_control(
			'divider_image',
			[
				'label'                 => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::MEDIA,
				'dynamic'               => [
					'active' => true,
				],
				'default'               => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'             => [
					'divider_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default'               => 'full',
				'separator'             => 'none',
				'condition'             => [
					'divider_type' => 'image',
				],
			]
		);

		$this->add_control(
			'divider_direction',
			[
				'label'                 => esc_html__( 'Direction', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'horizontal',
				'options'               => [
					'horizontal' => esc_html__( 'Horizontal', 'powerpack-lite-for-elementor' ),
					'vertical'   => esc_html__( 'Vertical', 'powerpack-lite-for-elementor' ),
				],
				'condition'             => [
					'divider_type' => 'plain',
				],
			]
		);

		$styles = $this->get_separator_styles();
		$this->add_control(
			'divider_style',
			[
				'label'                 => esc_html__( 'Style', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SELECT,
				'groups'                => array_values( $this->get_options_by_groups( $styles ) ),
				'default'               => 'dashed',
				'render_type'           => 'template',
				'selectors'             => [
					'{{WRAPPER}} .pp-divider, {{WRAPPER}} .pp-divider-vertical, {{WRAPPER}} .divider-border' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'separator_type',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => 'pattern',
				'prefix_class' => 'pp-widget-divider--separator-type-',
				'condition' => [
					'divider_style!' => [
						'',
						'solid',
						'double',
						'dotted',
						'dashed',
					],
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'pattern_spacing_flag',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => 'no-spacing',
				'prefix_class' => 'pp-widget-divider--',
				'condition' => [
					'divider_style' => array_keys( $this->filter_styles_by( $styles, 'supports_amount', false ) ),
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'pattern_round_flag',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => 'bg-round',
				'prefix_class' => 'pp-widget-divider--',
				'condition' => [
					'divider_style' => array_keys( $this->filter_styles_by( $styles, 'round', true ) ),
				],
				'render_type' => 'template',
			]
		);

		$this->add_responsive_control(
			'horizontal_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'                 => [
					'px'           => [
						'min'      => 1,
						'max'      => 1200,
					],
				],
				'default'               => [
					'size'         => 100,
					'unit'         => '%',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider.horizontal' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-horizontal' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-text-container' => 'width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'divider_type',
									'operator' => '==',
									'value' => 'plain',
								],
								[
									'name' => 'divider_direction',
									'operator' => '==',
									'value' => 'horizontal',
								],
							],
						],
						[
							'name' => 'divider_type',
							'operator' => '!=',
							'value' => 'plain',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'vertical_height',
			[
				'label'                 => esc_html__( 'Height', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px'           => [
						'min'      => 1,
						'max'      => 500,
					],
					'%'           => [
						'min'      => 1,
						'max'      => 100,
					],
				],
				'default'               => [
					'size'         => 80,
					'unit'         => 'px',
				],
				'tablet_default'   => [
					'unit'         => 'px',
				],
				'mobile_default'   => [
					'unit'         => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider.vertical'                                                   => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-vertical'                                        => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-border'                                                         => 'border-top-width: {{SIZE}}{{UNIT}};',
					// Pattern mode removes padding-bottom and uses explicit height instead.
					'{{WRAPPER}}.pp-widget-divider--separator-type-pattern .pp-divider.pp-divider-vertical' => 'height: {{SIZE}}{{UNIT}}; padding-bottom: 0;',
				],
				'condition'             => [
					'divider_type'      => 'plain',
					'divider_direction' => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'                 => esc_html__( 'Alignment', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'center',
				'options'               => [
					'left'          => [
						'title'     => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'      => 'eicon-h-align-left',
					],
					'center'        => [
						'title'     => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'      => 'eicon-h-align-center',
					],
					'right'         => [
						'title'     => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'      => 'eicon-h-align-right',
					],
				],
				'selectors'             => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Divider Controls in Style tab
	 *
	 * @return void
	 */
	protected function register_style_divider_controls() {

		/*-----------------------------------------------------------------------------------*/
		/*	STYLE TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Style Tab: Divider
		 */
		$this->start_controls_section(
			'section_divider_style',
			[
				'label'                 => esc_html__( 'Divider', 'powerpack-lite-for-elementor' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'divider_vertical_align',
			[
				'label'                 => esc_html__( 'Vertical Alignment', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'default'               => 'middle',
				'options'               => [
					'top'          => [
						'title'    => esc_html__( 'Top', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => esc_html__( 'Bottom', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-v-align-bottom',
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .divider-text-wrap'   => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
				'condition'             => [
					'divider_type!' => 'plain',
				],
			]
		);

		$styles = $this->get_separator_styles();
		$this->add_responsive_control(
			'horizontal_height',
			[
				'label'                 => esc_html__( 'Weight', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px'       => [
						'min'  => 1,
						'max'  => 60,
					],
				],
				'default'               => [
					'size'     => 3,
					'unit'     => 'px',
				],
				'tablet_default'    => [
					'unit'     => 'px',
				],
				'mobile_default'    => [
					'unit'     => 'px',
				],
				'render_type'           => 'template',
				'selectors'             => [
					'{{WRAPPER}} .pp-divider.horizontal' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-horizontal' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-border' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
				'conditions' => [
					'relation' => 'and',
					'terms' => [
						[
							'relation' => 'or',
							'terms' => [
								[
									'relation' => 'and',
									'terms' => [
										[
											'name'     => 'divider_type',
											'operator' => '==',
											'value'    => 'plain',
										],
										[
											'name'     => 'divider_direction',
											'operator' => '==',
											'value'    => 'horizontal',
										],
									],
								],
								[
									'name'     => 'divider_type',
									'operator' => '!=',
									'value'    => 'plain',
								],
							],
						],
						[
							'name'     => 'divider_style',
							'operator' => 'in',
							'value'    => array_keys( $this->get_options_by_groups( $styles, 'line' )['options'] ),
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'vertical_width',
			[
				'label'                 => esc_html__( 'Weight', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px'           => [
						'min'      => 1,
						'max'      => 100,
					],
				],
				'default'               => [
					'size'         => 3,
					'unit'         => 'px',
				],
				'tablet_default'   => [
					'unit'         => 'px',
				],
				'mobile_default'   => [
					'unit'         => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider.vertical' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-vertical' => 'border-left-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-text-container' => 'width: {{SIZE}}{{UNIT}};',
				],
				'render_type'           => 'template',
				'condition'             => [
					'divider_type'      => 'plain',
					'divider_direction' => 'vertical',
					'divider_style'     => array_keys( $this->get_options_by_groups( $styles, 'line' )['options'] ),
				],
			]
		);

		$this->add_control(
			'pattern_height',
			[
				'label' => esc_html__( 'Size', 'powerpack-lite-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}}' => '--pp-divider-pattern-height: {{SIZE}}{{UNIT}}',
				],
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'step' => 0.1,
					],
				],
				'condition' => [
					'divider_style!' => [
						'',
						'solid',
						'double',
						'dotted',
						'dashed',
					],
				],
			]
		);

		$this->add_control(
			'pattern_size',
			[
				'label' => esc_html__( 'Amount', 'powerpack-lite-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}' => '--pp-divider-pattern-size: {{SIZE}}{{UNIT}}',
				],
				'default' => [
					'size' => 20,
				],
				'range' => [
					'px' => [
						'step' => 0.1,
					],
					'%' => [
						'step' => 0.01,
					],
				],
				'condition' => [
					'divider_style!' => array_merge( array_keys( $this->filter_styles_by( $styles, 'supports_amount', false ) ), [
						'',
						'solid',
						'double',
						'dotted',
						'dashed',
					] ),
				],
			]
		);

		$this->add_control(
			'divider_border_color',
			[
				'label'                 => esc_html__( 'Divider Color', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-divider, {{WRAPPER}} .divider-border'                 => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.pp-widget-divider--separator-type-pattern .pp-divider' => 'color: {{VALUE}};',
				],
				'condition'             => [
					'divider_type' => 'plain',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_before_after_style' );

		$this->start_controls_tab(
			'tab_before_style',
			[
				'label'                 => esc_html__( 'Before', 'powerpack-lite-for-elementor' ),
				'condition'             => [
					'divider_type!' => 'plain',
				],
			]
		);

		$this->add_control(
			'divider_before_color',
			[
				'label'                 => esc_html__( 'Divider Color', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'divider_type!'   => 'plain',
				],
				'selectors'             => [
					'{{WRAPPER}} .divider-border-left .divider-border' => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.pp-widget-divider--separator-type-pattern .divider-border-left .divider-border' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_after_style',
			[
				'label'                 => esc_html__( 'After', 'powerpack-lite-for-elementor' ),
				'condition'             => [
					'divider_type!' => 'plain',
				],
			]
		);

		$this->add_control(
			'divider_after_color',
			[
				'label'                 => esc_html__( 'Divider Color', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'divider_type!'   => 'plain',
				],
				'selectors'             => [
					'{{WRAPPER}} .divider-border-right .divider-border' => 'border-color: {{VALUE}};',
					'{{WRAPPER}}.pp-widget-divider--separator-type-pattern .divider-border-right .divider-border' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Text
		 */
		$this->start_controls_section(
			'section_text_style',
			[
				'label'                 => esc_html__( 'Text', 'powerpack-lite-for-elementor' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_type'    => 'text',
				],
			]
		);

		$this->add_control(
			'text_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'         => [
						'title'    => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-h-align-left',
					],
					'center'       => [
						'title'    => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-h-align-center',
					],
					'right'        => [
						'title'    => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-h-align-right',
					],
				],
				'default'               => 'center',
				'prefix_class'          => 'pp-divider-',
			]
		);

		$this->add_control(
			'divider_text_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'divider_type'    => 'text',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'typography',
				'label'                 => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'              => '{{WRAPPER}} .pp-divider-text',
				'condition'             => [
					'divider_type' => 'text',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'                 => 'divider_text_stroke',
				'selector'             => '{{WRAPPER}} .pp-divider-text',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'                  => 'divider_text_shadow',
				'selector'              => '{{WRAPPER}} .pp-divider-text',
			]
		);

		$this->add_responsive_control(
			'text_spacing',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'max' => 200,
					],
				],
				'condition'             => [
					'divider_type' => 'text',
				],
				'selectors'             => [
					'{{WRAPPER}}.pp-divider-center .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-left .pp-divider-content' => 'margin-left: 0; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-right .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Icon
		 */
		$this->start_controls_section(
			'section_icon_style',
			[
				'label'                 => esc_html__( 'Icon', 'powerpack-lite-for-elementor' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'         => [
						'title'    => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-h-align-left',
					],
					'center'       => [
						'title'    => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-h-align-center',
					],
					'right'        => [
						'title'    => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'     => 'eicon-h-align-right',
					],
				],
				'default'               => 'center',
				'prefix_class'          => 'pp-divider-',
			]
		);

		$this->add_control(
			'divider_icon_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'divider_type' => 'icon',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-divider-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'                 => esc_html__( 'Size', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'default'               => [
					'size' => 16,
					'unit' => 'px',
				],
				'condition'             => [
					'divider_type' => 'icon',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_rotation',
			[
				'label'                 => esc_html__( 'Icon Rotation', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'deg' ],
				'range'                 => [
					'deg' => [
						'max' => 360,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider-icon' => 'transform: rotate( {{SIZE}}deg );',
				],
				'condition'             => [
					'divider_type' => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'icon_spacing',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'max' => 200,
					],
				],
				'condition'             => [
					'divider_type' => 'icon',
				],
				'selectors'             => [
					'{{WRAPPER}}.pp-divider-center .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-left .pp-divider-content' => 'margin-left: 0; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-right .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Image
		 */
		$this->start_controls_section(
			'section_image_style',
			[
				'label'                 => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_type' => 'image',
				],
			]
		);

		$this->add_control(
			'image_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'    => [
						'title' => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'     => [
						'title' => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'               => 'center',
				'prefix_class'          => 'pp-divider-',
			]
		);

		$this->add_responsive_control(
			'image_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'                 => [
					'px' => [
						'max' => 1200,
					],
				],
				'default'               => [
					'size' => 80,
					'unit' => 'px',
				],
				'tablet_default'    => [
					'unit' => 'px',
				],
				'mobile_default'    => [
					'unit' => 'px',
				],
				'condition'             => [
					'divider_type' => 'image',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'condition'             => [
					'divider_type'    => 'image',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack-lite-for-elementor' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'max' => 200,
					],
				],
				'condition'             => [
					'divider_type' => 'image',
				],
				'selectors'             => [
					'{{WRAPPER}}.pp-divider-center .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-left .pp-divider-content' => 'margin-left: 0; margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-divider-right .pp-divider-content' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: 0;',
				],
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render divider widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		$svg_code = $this->build_svg();

		$this->add_render_attribute( 'wrapper', 'class', 'pp-divider-wrap' );

		if ( ! empty( $svg_code ) ) {
			$this->add_render_attribute( 'wrapper', 'style', '--divider-pattern-url: url("data:image/svg+xml,' . $this->svg_to_data_uri( $svg_code ) . '");' );
		}

		$classes = [ 'pp-divider' ];

		if ( $settings['divider_direction'] ) {
			$classes[] = 'pp-divider-' . $settings['divider_direction'];
			$classes[] = $settings['divider_direction'];
		}

		$this->add_render_attribute( 'divider', 'class', $classes );

		$this->add_render_attribute( 'divider-content', 'class', [ 'pp-divider-' . $settings['divider_type'], 'pp-icon' ] );

		$this->add_inline_editing_attributes( 'divider_text', 'none' );
		$this->add_render_attribute( 'divider_text', 'class', 'pp-divider-' . $settings['divider_type'] );

		if ( 'icon' === $settings['divider_type'] ) {
			if ( ! isset( $settings['divider_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
				// add old default
				$settings['divider_icon'] = 'fa fa-circle';
			}

			$has_icon = ! empty( $settings['divider_icon'] );

			if ( $has_icon ) {
				$this->add_render_attribute( 'i', 'class', $settings['divider_icon'] );
				$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
			}

			$icon_attributes = $this->get_render_attribute_string( 'divider_icon' );

			if ( ! $has_icon && ! empty( $settings['icon']['value'] ) ) {
				$has_icon = true;
			}
			$migrated = isset( $settings['__fa4_migrated']['icon'] );
			$is_new = ! isset( $settings['divider_icon'] ) && Icons_Manager::is_migration_allowed();
		}
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( 'plain' === $settings['divider_type'] ) { ?>
				<div <?php $this->print_render_attribute_string( 'divider' ); ?>></div>
				<?php
			} else { ?>
				<div class="divider-text-container">
					<div class="divider-text-wrap">
						<span class="pp-divider-border-wrap divider-border-left">
							<span class="divider-border"></span>
						</span>
						<span class="pp-divider-content">
							<?php
							if ( 'text' === $settings['divider_type'] && $settings['divider_text'] ) {
								$text_html_tag = PP_Helper::validate_html_tag( $settings['text_html_tag'] );
								?>
								<<?php PP_Helper::print_validated_html_tag( $text_html_tag ); ?> <?php $this->print_render_attribute_string( 'divider_text' ); ?>>
									<?php echo wp_kses_post( $settings['divider_text'] ); ?>
								</<?php PP_Helper::print_validated_html_tag( $text_html_tag ); ?>>
								<?php
							} elseif ( 'icon' === $settings['divider_type'] ) {
								if ( ! empty( $settings['divider_icon'] ) || ( ! empty( $settings['icon']['value'] ) && $is_new ) ) { ?>
									<span <?php $this->print_render_attribute_string( 'divider-content' ); ?>>
										<?php
										if ( $is_new || $migrated ) {
											Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
										} elseif ( ! empty( $settings['divider_icon'] ) ) {
											?><i <?php $this->print_render_attribute_string( 'i' ); ?>></i><?php
										}
										?>
									</span>
									<?php
								}
							} elseif ( 'image' === $settings['divider_type'] ) { ?>
								<span <?php $this->print_render_attribute_string( 'divider-content' ); ?>>
									<?php
									$image = $settings['divider_image'];
									if ( $image['url'] ) {
										echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'divider_image' ) );
									}
									?>
								</span>
							<?php } ?>
						</span>
						<span class="pp-divider-border-wrap divider-border-right">
							<span class="divider-border"></span>
						</span>
					</div>
				</div>
				<?php
			}
			?>
		</div>    
		<?php
	}

	private function build_svg() {
		$settings = $this->get_settings_for_display();

		if ( 'pattern' !== $settings['separator_type'] || empty( $settings['divider_style'] ) ) {
			return '';
		}

		$svg_shapes       = $this->get_separator_styles();
		$selected_pattern = $svg_shapes[ $settings['divider_style'] ];
		$preserve_aspect_ratio = $selected_pattern['preserve_aspect_ratio'] ? 'xMidYMid meet' : 'none';
		$view_box         = isset( $selected_pattern['view_box'] ) ? $selected_pattern['view_box'] : '0 0 24 24';
		$direction = ( 'plain' === $settings['divider_type'] && isset( $settings['divider_direction'] ) )
			? $settings['divider_direction']
			: 'horizontal';
		$shape            = $selected_pattern['shape'];

		if ( 'vertical' === $direction ) {
			$vb_parts = explode( ' ', $view_box );
			$vb_w     = isset( $vb_parts[2] ) ? (float) $vb_parts[2] : 24;
			$vb_h     = isset( $vb_parts[3] ) ? (float) $vb_parts[3] : 24;
			$view_box = "0 0 {$vb_h} {$vb_w}";
			$shape    = '<g transform="translate(' . $vb_h . ',0) rotate(90)">' . $shape . '</g>';
		}

		$attr = [
			'preserveAspectRatio' => $preserve_aspect_ratio,
			'overflow'            => 'visible',
			'height'              => '100%',
			'viewBox'             => $view_box,
		];

		if ( 'line' !== $selected_pattern['group'] ) {
			$attr['fill']   = 'black';
			$attr['stroke'] = 'none';
		} else {
			$attr['fill']              = 'none';
			$attr['stroke']            = 'black';
			$attr['stroke-width']      = $settings['horizontal_height']['size'];
			$attr['stroke-linecap']    = 'square';
			$attr['stroke-miterlimit'] = '10';
		}

		$this->add_render_attribute( 'svg', $attr );

		$pattern_attribute_string = $this->get_render_attribute_string( 'svg' );

		return '<svg xmlns="http://www.w3.org/2000/svg" ' . $pattern_attribute_string . '>' . $shape . '</svg>';
	}

	public function svg_to_data_uri( $svg ) {
		return str_replace(
			[ '<', '>', '"', '#' ],
			[ '%3C', '%3E', "'", '%23' ],
			$svg
		);
	}

	private static function get_additional_styles() {
		static $additional_styles = null;

		if ( null !== $additional_styles ) {
			return $additional_styles;
		}
		$additional_styles = [];

		$additional_styles = apply_filters( 'powerpack/divider/styles/additional_styles', $additional_styles );
		return $additional_styles;
	}

	private function get_separator_styles() {
		return array_merge(
			self::get_additional_styles(),
			[
				'curly'   => [
					'label' => esc_html_x( 'Curly', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M0,21c3.3,0,8.3-0.9,15.7-7.1c6.6-5.4,4.4-9.3,2.4-10.3c-3.4-1.8-7.7,1.3-7.3,8.8C11.2,20,17.1,21,24,21"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'line',
				],
				'curved'   => [
					'label' => esc_html_x( 'Curved', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M0,6c6,0,6,13,12,13S18,6,24,6"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'line',
				],
				'multiple'   => [
					'label' => esc_html_x( 'Multiple', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M24,8v12H0V8H24z M24,4v1H0V4H24z"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => false,
					'round' => false,
					'group' => 'pattern',
				],
				'slashes' => [
					'label' => esc_html_x( 'Slashes', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<g transform="translate(-12.000000, 0)"><path d="M28,0L10,18"/><path d="M18,0L0,18"/><path d="M48,0L30,18"/><path d="M38,0L20,18"/></g>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'view_box' => '0 0 20 16',
					'group' => 'line',
				],
				'squared' => [
					'label' => esc_html_x( 'Squared', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<polyline points="0,6 6,6 6,18 18,18 18,6 24,6 	"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'line',
				],
				'wavy'   => [
					'label' => esc_html_x( 'Wavy', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M0,6c6,0,0.9,11.1,6.9,11.1S18,6,24,6"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'line',
				],
				'zigzag'  => [
					'label' => esc_html_x( 'Zigzag', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<polyline points="0,18 12,6 24,18 "/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'line',
				],
				'arrows'   => [
					'label' => esc_html_x( 'Arrows', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M14.2,4c0.3,0,0.5,0.1,0.7,0.3l7.9,7.2c0.2,0.2,0.3,0.4,0.3,0.7s-0.1,0.5-0.3,0.7l-7.9,7.2c-0.2,0.2-0.4,0.3-0.7,0.3s-0.5-0.1-0.7-0.3s-0.3-0.4-0.3-0.7l0-2.9l-11.5,0c-0.4,0-0.7-0.3-0.7-0.7V9.4C1,9,1.3,8.7,1.7,8.7l11.5,0l0-3.6c0-0.3,0.1-0.5,0.3-0.7S13.9,4,14.2,4z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => true,
					'round' => true,
					'group' => 'pattern',
				],
				'pluses'   => [
					'label' => esc_html_x( 'Pluses', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M21.4,9.6h-7.1V2.6c0-0.9-0.7-1.6-1.6-1.6h-1.6c-0.9,0-1.6,0.7-1.6,1.6v7.1H2.6C1.7,9.6,1,10.3,1,11.2v1.6c0,0.9,0.7,1.6,1.6,1.6h7.1v7.1c0,0.9,0.7,1.6,1.6,1.6h1.6c0.9,0,1.6-0.7,1.6-1.6v-7.1h7.1c0.9,0,1.6-0.7,1.6-1.6v-1.6C23,10.3,22.3,9.6,21.4,9.6z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => true,
					'round' => false,
					'group' => 'pattern',
				],
				'rhombus'   => [
					'label' => esc_html_x( 'Rhombus', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M12.7,2.3c-0.4-0.4-1.1-0.4-1.5,0l-8,9.1c-0.3,0.4-0.3,0.9,0,1.2l8,9.1c0.4,0.4,1.1,0.4,1.5,0l8-9.1c0.3-0.4,0.3-0.9,0-1.2L12.7,2.3z"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'pattern',
				],
				'parallelogram'   => [
					'label' => esc_html_x( 'Parallelogram', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<polygon points="9.4,2 24,2 14.6,21.6 0,21.6"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => false,
					'group' => 'pattern',
				],
				'rectangles'   => [
					'label' => esc_html_x( 'Rectangles', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<rect x="15" y="0" width="30" height="30"/>',
					'preserve_aspect_ratio' => false,
					'supports_amount' => true,
					'round' => true,
					'group' => 'pattern',
					'view_box' => '0 0 60 30',
				],
				'dots_tribal'   => [
					'label' => esc_html_x( 'Dots', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M3,10.2c2.6,0,2.6,2,2.6,3.2S4.4,16.5,3,16.5s-3-1.4-3-3.2S0.4,10.2,3,10.2z M18.8,10.2c1.7,0,3.2,1.4,3.2,3.2s-1.4,3.2-3.2,3.2c-1.7,0-3.2-1.4-3.2-3.2S17,10.2,18.8,10.2z M34.6,10.2c1.5,0,2.6,1.4,2.6,3.2s-0.5,3.2-1.9,3.2c-1.5,0-3.4-1.4-3.4-3.2S33.1,10.2,34.6,10.2z M50.5,10.2c1.7,0,3.2,1.4,3.2,3.2s-1.4,3.2-3.2,3.2c-1.7,0-3.3-0.9-3.3-2.6S48.7,10.2,50.5,10.2z M66.2,10.2c1.5,0,3.4,1.4,3.4,3.2s-1.9,3.2-3.4,3.2c-1.5,0-2.6-0.4-2.6-2.1S64.8,10.2,66.2,10.2z M82.2,10.2c1.7,0.8,2.6,1.4,2.6,3.2s-0.1,3.2-1.6,3.2c-1.5,0-3.7-1.4-3.7-3.2S80.5,9.4,82.2,10.2zM98.6,10.2c1.5,0,2.6,0.4,2.6,2.1s-1.2,4.2-2.6,4.2c-1.5,0-3.7-0.4-3.7-2.1S97.1,10.2,98.6,10.2z M113.4,10.2c1.2,0,2.2,0.9,2.2,3.2s-0.1,3.2-1.3,3.2s-3.1-1.4-3.1-3.2S112.2,10.2,113.4,10.2z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 126 26',
				],
				'trees_2_tribal'   => [
					'label' => esc_html_x( 'Fir Tree', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M111.9,18.3v3.4H109v-3.4H111.9z M90.8,18.3v3.4H88v-3.4H90.8z M69.8,18.3v3.4h-2.9v-3.4H69.8z M48.8,18.3v3.4h-2.9v-3.4H48.8z M27.7,18.3v3.4h-2.9v-3.4H27.7z M6.7,18.3v3.4H3.8v-3.4H6.7z M46.4,4l4.3,4.8l-1.8,0l3.5,4.4l-2.2-0.1l3,3.3l-11,0.4l3.6-3.8l-2.9-0.1l3.1-4.2l-1.9,0L46.4,4z M111.4,4l2.4,4.8l-1.8,0l3.5,4.4l-2.5-0.1l3.3,3.3h-11l3.1-3.4l-2.5-0.1l3.1-4.2l-1.9,0L111.4,4z M89.9,4l2.9,4.8l-1.9,0l3.2,4.2l-2.5,0l3.5,3.5l-11-0.4l3-3.1l-2.4,0L88,8.8l-1.9,0L89.9,4z M68.6,4l3,4.4l-1.9,0.1l3.4,4.1l-2.7,0.1l3.8,3.7H63.8l2.9-3.6l-2.9,0.1L67,8.7l-2,0.1L68.6,4z M26.5,4l3,4.4l-1.9,0.1l3.7,4.7l-2.5-0.1l3.3,3.3H21l3.1-3.4l-2.5-0.1l3.2-4.3l-2,0.1L26.5,4z M4.9,4l3.7,4.8l-1.5,0l3.1,4.2L7.6,13l3.4,3.4H0l3-3.3l-2.3,0.1l3.5-4.4l-2.3,0L4.9,4z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 126 26',
				],
				'rounds_tribal'   => [
					'label' => esc_html_x( 'Half Rounds', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M11.9,15.9L11.9,15.9L0,16c-0.2-3.7,1.5-5.7,4.9-6C10,9.6,12.4,14.2,11.9,15.9zM26.9,15.9L26.9,15.9L15,16c0.5-3.7,2.5-5.7,5.9-6C26,9.6,27.4,14.2,26.9,15.9z M37.1,10c3.4,0.3,5.1,2.3,4.9,6H30.1C29.5,14.4,31.9,9.6,37.1,10z M57,15.9L57,15.9L45,16c0-3.4,1.6-5.4,4.9-5.9C54.8,9.3,57.4,14.2,57,15.9z M71.9,15.9L71.9,15.9L60,16c-0.2-3.7,1.5-5.7,4.9-6C70,9.6,72.4,14.2,71.9,15.9z M82.2,10c3.4,0.3,5,2.3,4.8,6H75.3C74,13,77.1,9.6,82.2,10zM101.9,15.9L101.9,15.9L90,16c-0.2-3.7,1.5-5.7,4.9-6C100,9.6,102.4,14.2,101.9,15.9z M112.1,10.1c2.7,0.5,4.3,2.5,4.9,5.9h-11.9l0,0C104.5,14.4,108,9.3,112.1,10.1z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 120 26',
				],
				'leaves_tribal'   => [
					'label' => esc_html_x( 'Leaves', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M3,1.5C5,4.9,6,8.8,6,13s-1.7,8.1-5,11.5C0.3,21.1,0,17.2,0,13S1,4.9,3,1.5z M16,1.5c2,3.4,3,7.3,3,11.5s-1,8.1-3,11.5c-2-4.1-3-8.3-3-12.5S14,4.3,16,1.5z M29,1.5c2,4.8,3,9.3,3,13.5s-1,7.4-3,9.5c-2-3.4-3-7.3-3-11.5S27,4.9,29,1.5z M41.1,1.5C43.7,4.9,45,8.8,45,13s-1,8.1-3,11.5c-2-3.4-3-7.3-3-11.5S39.7,4.9,41.1,1.5zM55,1.5c2,2.8,3,6.3,3,10.5s-1.3,8.4-4,12.5c-1.3-3.4-2-7.3-2-11.5S53,4.9,55,1.5z M68,1.5c2,3.4,3,7.3,3,11.5s-0.7,8.1-2,11.5c-2.7-4.8-4-9.3-4-13.5S66,3.6,68,1.5z M82,1.5c1.3,4.8,2,9.3,2,13.5s-1,7.4-3,9.5c-2-3.4-3-7.3-3-11.5S79.3,4.9,82,1.5z M94,1.5c2,3.4,3,7.3,3,11.5s-1.3,8.1-4,11.5c-1.3-1.4-2-4.3-2-8.5S92,6.9,94,1.5z M107,1.5c2,2.1,3,5.3,3,9.5s-0.7,8.7-2,13.5c-2.7-3.4-4-7.3-4-11.5S105,4.9,107,1.5z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 117 26',
				],
				'stripes_tribal'   => [
					'label' => esc_html_x( 'Stripes', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M54,1.6V26h-9V2.5L54,1.6z M69,1.6v23.3L60,26V1.6H69z M24,1.6v23.5l-9-0.6V1.6H24z M30,0l9,0.7v24.5h-9V0z M9,2.5v22H0V3.7L9,2.5z M75,1.6l9,0.9v22h-9V1.6z M99,2.7v21.7h-9V3.8L99,2.7z M114,3.8v20.7l-9-0.5V3.8L114,3.8z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 120 26',
				],
				'squares_tribal'   => [
					'label' => esc_html_x( 'Squares', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M46.8,7.8v11.5L36,18.6V7.8H46.8z M82.4,7.8L84,18.6l-12,0.7L70.4,7.8H82.4z M0,7.8l12,0.9v9.9H1.3L0,7.8z M30,7.8v10.8H19L18,7.8H30z M63.7,7.8L66,18.6H54V9.5L63.7,7.8z M89.8,7L102,7.8v10.8H91.2L89.8,7zM108,7.8l12,0.9v8.9l-12,1V7.8z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 126 26',
				],
				'trees_tribal'   => [
					'label' => esc_html_x( 'Trees', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M6.4,2l4.2,5.7H7.7v2.7l3.8,5.2l-3.8,0v7.8H4.8v-7.8H0l4.8-5.2V7.7H1.1L6.4,2z M25.6,2L31,7.7h-3.7v2.7l4.8,5.2h-4.8v7.8h-2.8v-7.8l-3.8,0l3.8-5.2V7.7h-2.9L25.6,2z M47.5,2l4.2,5.7h-3.3v2.7l3.8,5.2l-3.8,0l0.4,7.8h-2.8v-7.8H41l4.8-5.2V7.7h-3.7L47.5,2z M66.2,2l5.4,5.7h-3.7v2.7l4.8,5.2h-4.8v7.8H65v-7.8l-3.8,0l3.8-5.2V7.7h-2.9L66.2,2zM87.4,2l4.8,5.7h-2.9v3.1l3.8,4.8l-3.8,0v7.8h-2.8v-7.8h-4.8l4.8-4.8V7.7h-3.7L87.4,2z M107.3,2l5.4,5.7h-3.7v2.7l4.8,5.2h-4.8v7.8H106v-7.8l-3.8,0l3.8-5.2V7.7h-2.9L107.3,2z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 123 26',
				],
				'planes_tribal'   => [
					'label' => esc_html_x( 'Tribal', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M29.6,10.3l2.1,2.2l-3.6,3.3h7v2.9h-7l3.6,3.5l-2.1,1.7l-5.2-5.2h-5.8v-2.9h5.8L29.6,10.3z M70.9,9.6l2.1,1.7l-3.6,3.5h7v2.9h-7l3.6,3.3l-2.1,2.2l-5.2-5.5h-5.8v-2.9h5.8L70.9,9.6z M111.5,9.6l2.1,1.7l-3.6,3.5h7v2.9h-7l3.6,3.3l-2.1,2.2l-5.2-5.5h-5.8v-2.9h5.8L111.5,9.6z M50.2,2.7l2.1,1.7l-3.6,3.5h7v2.9h-7l3.6,3.3l-2.1,2.2L45,10.7h-5.8V7.9H45L50.2,2.7z M11,2l2.1,1.7L9.6,7.2h7V10h-7l3.6,3.3L11,15.5L5.8,10H0V7.2h5.8L11,2z M91.5,2l2.1,2.2l-3.6,3.3h7v2.9h-7l3.6,3.5l-2.1,1.7l-5.2-5.2h-5.8V7.5h5.8L91.5,2z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 121 26',
				],
				'x_tribal'   => [
					'label' => esc_html_x( 'X', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<path d="M10.7,6l2.5,2.6l-4,4.3l4,5.4l-2.5,1.9l-4.5-5.2l-3.9,4.2L0.7,17L4,13.1L0,8.6l2.3-1.3l3.9,3.9L10.7,6z M23.9,6.6l4.2,4.5L32,7.2l2.3,1.3l-4,4.5l3.2,3.9L32,19.1l-3.9-3.3l-4.5,4.3l-2.5-1.9l4.4-5.1l-4.2-3.9L23.9,6.6zM73.5,6L76,8.6l-4,4.3l4,5.4l-2.5,1.9l-4.5-5.2l-3.9,4.2L63.5,17l4.1-4.7L63.5,8l2.3-1.3l4.1,3.6L73.5,6z M94,6l2.5,2.6l-4,4.3l4,5.4L94,20.1l-3.9-5l-3.9,4.2L84,17l3.2-3.9L84,8.6l2.3-1.3l3.2,3.9L94,6z M106.9,6l4.5,5.1l3.9-3.9l2.3,1.3l-4,4.5l3.2,3.9l-1.6,2.1l-3.9-4.2l-4.5,5.2l-2.5-1.9l4-5.4l-4-4.3L106.9,6z M53.1,6l2.5,2.6l-4,4.3l4,4.6l-2.5,1.9l-4.5-4.5l-3.5,4.5L43.1,17l3.2-3.9l-4-4.5l2.3-1.3l3.9,3.9L53.1,6z"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 126 26',
				],
				'zigzag_tribal'   => [
					'label' => esc_html_x( 'Zigzag', 'Shapes', 'powerpack-lite-for-elementor' ),
					'shape' => '<polygon points="0,14.4 0,21 11.5,12.4 21.3,20 30.4,11.1 40.3,20 51,12.4 60.6,20 69.6,11.1 79.3,20 90.1,12.4 99.6,20 109.7,11.1 120,21 120,14.4 109.7,5 99.6,13 90.1,5 79.3,14.5 71,5.7 60.6,12.4 51,5 40.3,14.5 31.1,5 21.3,13 11.5,5 	"/>',
					'preserve_aspect_ratio' => true,
					'supports_amount' => false,
					'round' => false,
					'group' => 'tribal',
					'view_box' => '0 0 120 26',
				],
			]
		);
	}

	private function filter_styles_by( $styles_array, $key, $value ) {
		return array_filter( $styles_array, function( $style ) use ( $key, $value ) {
			return $value === $style[ $key ];
		} );
	}

	private function get_options_by_groups( $styles, $group = false ) {
		$groups = [
			'line' => [
				'label' => esc_html__( 'Line', 'powerpack-lite-for-elementor' ),
				'options' => [
					'solid'  => esc_html__( 'Solid', 'powerpack-lite-for-elementor' ),
					'dashed' => esc_html__( 'Dashed', 'powerpack-lite-for-elementor' ),
					'dotted' => esc_html__( 'Dotted', 'powerpack-lite-for-elementor' ),
					'double' => esc_html__( 'Double', 'powerpack-lite-for-elementor' ),
				],
			],
		];
		foreach ( $styles as $key => $style ) {
			if ( ! isset( $groups[ $style['group'] ] ) ) {
				$groups[ $style['group'] ] = [
					'label' => ucwords( str_replace( '_', '', $style['group'] ) ),
					'options' => [],
				];
			}
			$groups[ $style['group'] ]['options'][ $key ] = $style['label'];
		}

		if ( $group && isset( $groups[ $group ] ) ) {
			return $groups[ $group ];
		}
		return $groups;
	}

	/**
	 * Render divider widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.0.3
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		var iconHTML = elementor.helpers.renderIcon( view, settings.icon, { 'aria-hidden': true }, 'i' , 'object' ),
			migrated = elementor.helpers.isIconMigrated( settings, 'icon' );   

		var imageUrl = false;

		if ( '' !== settings.divider_image.url ) {
			var image = {
				id: settings.divider_image.id,
				url: settings.divider_image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var imageUrl = elementor.imagesManager.getImageUrl( image );

			var imageHtml = '<img src="' + _.escape( imageUrl ) + '" alt="divider" />';
		}

		var ppSvgPatterns = {
			curly:          { shape: '<path d="M0,21c3.3,0,8.3-0.9,15.7-7.1c6.6-5.4,4.4-9.3,2.4-10.3c-3.4-1.8-7.7,1.3-7.3,8.8C11.2,20,17.1,21,24,21"/>', group: 'line',    viewBox: '0 0 24 24',  preserveAspectRatio: false },
			curved:         { shape: '<path d="M0,6c6,0,6,13,12,13S18,6,24,6"/>',                                                                                   group: 'line',    viewBox: '0 0 24 24',  preserveAspectRatio: false },
			multiple:       { shape: '<path d="M24,8v12H0V8H24z M24,4v1H0V4H24z"/>',                                                                                group: 'pattern', viewBox: '0 0 24 24',  preserveAspectRatio: false },
			slashes:        { shape: '<g transform="translate(-12.000000, 0)"><path d="M28,0L10,18"/><path d="M18,0L0,18"/><path d="M48,0L30,18"/><path d="M38,0L20,18"/></g>', group: 'line', viewBox: '0 0 20 16', preserveAspectRatio: false },
			squared:        { shape: '<polyline points="0,6 6,6 6,18 18,18 18,6 24,6"/>',                                                                           group: 'line',    viewBox: '0 0 24 24',  preserveAspectRatio: false },
			wavy:           { shape: '<path d="M0,6c6,0,0.9,11.1,6.9,11.1S18,6,24,6"/>',                                                                           group: 'line',    viewBox: '0 0 24 24',  preserveAspectRatio: false },
			zigzag:         { shape: '<polyline points="0,18 12,6 24,18"/>',                                                                                        group: 'line',    viewBox: '0 0 24 24',  preserveAspectRatio: false },
			arrows:         { shape: '<path d="M14.2,4c0.3,0,0.5,0.1,0.7,0.3l7.9,7.2c0.2,0.2,0.3,0.4,0.3,0.7s-0.1,0.5-0.3,0.7l-7.9,7.2c-0.2,0.2-0.4,0.3-0.7,0.3s-0.5-0.1-0.7-0.3s-0.3-0.4-0.3-0.7l0-2.9l-11.5,0c-0.4,0-0.7-0.3-0.7-0.7V9.4C1,9,1.3,8.7,1.7,8.7l11.5,0l0-3.6c0-0.3,0.1-0.5,0.3-0.7S13.9,4,14.2,4z"/>', group: 'pattern', viewBox: '0 0 24 24', preserveAspectRatio: true },
			pluses:         { shape: '<path d="M21.4,9.6h-7.1V2.6c0-0.9-0.7-1.6-1.6-1.6h-1.6c-0.9,0-1.6,0.7-1.6,1.6v7.1H2.6C1.7,9.6,1,10.3,1,11.2v1.6c0,0.9,0.7,1.6,1.6,1.6h7.1v7.1c0,0.9,0.7,1.6,1.6,1.6h1.6c0.9,0,1.6-0.7,1.6-1.6v-7.1h7.1c0.9,0,1.6-0.7,1.6-1.6v-1.6C23,10.3,22.3,9.6,21.4,9.6z"/>', group: 'pattern', viewBox: '0 0 24 24', preserveAspectRatio: true },
			rhombus:        { shape: '<path d="M12.7,2.3c-0.4-0.4-1.1-0.4-1.5,0l-8,9.1c-0.3,0.4-0.3,0.9,0,1.2l8,9.1c0.4,0.4,1.1,0.4,1.5,0l8-9.1c0.3-0.4,0.3-0.9,0-1.2L12.7,2.3z"/>', group: 'pattern', viewBox: '0 0 24 24', preserveAspectRatio: false },
			parallelogram:  { shape: '<polygon points="9.4,2 24,2 14.6,21.6 0,21.6"/>',                                                                             group: 'pattern', viewBox: '0 0 24 24',  preserveAspectRatio: false },
			rectangles:     { shape: '<rect x="15" y="0" width="30" height="30"/>',                                                                                 group: 'pattern', viewBox: '0 0 60 30',  preserveAspectRatio: false },
			dots_tribal:    { shape: '<path d="M3,10.2c2.6,0,2.6,2,2.6,3.2S4.4,16.5,3,16.5s-3-1.4-3-3.2S0.4,10.2,3,10.2z M18.8,10.2c1.7,0,3.2,1.4,3.2,3.2s-1.4,3.2-3.2,3.2c-1.7,0-3.2-1.4-3.2-3.2S17,10.2,18.8,10.2z M34.6,10.2c1.5,0,2.6,1.4,2.6,3.2s-0.5,3.2-1.9,3.2c-1.5,0-3.4-1.4-3.4-3.2S33.1,10.2,34.6,10.2z M50.5,10.2c1.7,0,3.2,1.4,3.2,3.2s-1.4,3.2-3.2,3.2c-1.7,0-3.3-0.9-3.3-2.6S48.7,10.2,50.5,10.2z M66.2,10.2c1.5,0,3.4,1.4,3.4,3.2s-1.9,3.2-3.4,3.2c-1.5,0-2.6-0.4-2.6-2.1S64.8,10.2,66.2,10.2z M82.2,10.2c1.7,0.8,2.6,1.4,2.6,3.2s-0.1,3.2-1.6,3.2c-1.5,0-3.7-1.4-3.7-3.2S80.5,9.4,82.2,10.2zM98.6,10.2c1.5,0,2.6,0.4,2.6,2.1s-1.2,4.2-2.6,4.2c-1.5,0-3.7-0.4-3.7-2.1S97.1,10.2,98.6,10.2z M113.4,10.2c1.2,0,2.2,0.9,2.2,3.2s-0.1,3.2-1.3,3.2s-3.1-1.4-3.1-3.2S112.2,10.2,113.4,10.2z"/>',   group: 'tribal', viewBox: '0 0 126 26', preserveAspectRatio: true },
			trees_2_tribal: { shape: '<path d="M111.9,18.3v3.4H109v-3.4H111.9z M90.8,18.3v3.4H88v-3.4H90.8z M69.8,18.3v3.4h-2.9v-3.4H69.8z M48.8,18.3v3.4h-2.9v-3.4H48.8z M27.7,18.3v3.4h-2.9v-3.4H27.7z M6.7,18.3v3.4H3.8v-3.4H6.7z M46.4,4l4.3,4.8l-1.8,0l3.5,4.4l-2.2-0.1l3,3.3l-11,0.4l3.6-3.8l-2.9-0.1l3.1-4.2l-1.9,0L46.4,4z M111.4,4l2.4,4.8l-1.8,0l3.5,4.4l-2.5-0.1l3.3,3.3h-11l3.1-3.4l-2.5-0.1l3.1-4.2l-1.9,0L111.4,4z M89.9,4l2.9,4.8l-1.9,0l3.2,4.2l-2.5,0l3.5,3.5l-11-0.4l3-3.1l-2.4,0L88,8.8l-1.9,0L89.9,4z M68.6,4l3,4.4l-1.9,0.1l3.4,4.1l-2.7,0.1l3.8,3.7H63.8l2.9-3.6l-2.9,0.1L67,8.7l-2,0.1L68.6,4z M26.5,4l3,4.4l-1.9,0.1l3.7,4.7l-2.5-0.1l3.3,3.3H21l3.1-3.4l-2.5-0.1l3.2-4.3l-2,0.1L26.5,4z M4.9,4l3.7,4.8l-1.5,0l3.1,4.2L7.6,13l3.4,3.4H0l3-3.3l-2.3,0.1l3.5-4.4l-2.3,0L4.9,4z"/>', group: 'tribal', viewBox: '0 0 126 26', preserveAspectRatio: true },
			rounds_tribal:  { shape: '<path d="M11.9,15.9L11.9,15.9L0,16c-0.2-3.7,1.5-5.7,4.9-6C10,9.6,12.4,14.2,11.9,15.9zM26.9,15.9L26.9,15.9L15,16c0.5-3.7,2.5-5.7,5.9-6C26,9.6,27.4,14.2,26.9,15.9z M37.1,10c3.4,0.3,5.1,2.3,4.9,6H30.1C29.5,14.4,31.9,9.6,37.1,10z M57,15.9L57,15.9L45,16c0-3.4,1.6-5.4,4.9-5.9C54.8,9.3,57.4,14.2,57,15.9z M71.9,15.9L71.9,15.9L60,16c-0.2-3.7,1.5-5.7,4.9-6C70,9.6,72.4,14.2,71.9,15.9z M82.2,10c3.4,0.3,5,2.3,4.8,6H75.3C74,13,77.1,9.6,82.2,10zM101.9,15.9L101.9,15.9L90,16c-0.2-3.7,1.5-5.7,4.9-6C100,9.6,102.4,14.2,101.9,15.9z M112.1,10.1c2.7,0.5,4.3,2.5,4.9,5.9h-11.9l0,0C104.5,14.4,108,9.3,112.1,10.1z"/>',    group: 'tribal', viewBox: '0 0 120 26', preserveAspectRatio: true },
			leaves_tribal:  { shape: '<path d="M3,1.5C5,4.9,6,8.8,6,13s-1.7,8.1-5,11.5C0.3,21.1,0,17.2,0,13S1,4.9,3,1.5z M16,1.5c2,3.4,3,7.3,3,11.5s-1,8.1-3,11.5c-2-4.1-3-8.3-3-12.5S14,4.3,16,1.5z M29,1.5c2,4.8,3,9.3,3,13.5s-1,7.4-3,9.5c-2-3.4-3-7.3-3-11.5S27,4.9,29,1.5z M41.1,1.5C43.7,4.9,45,8.8,45,13s-1,8.1-3,11.5c-2-3.4-3-7.3-3-11.5S39.7,4.9,41.1,1.5zM55,1.5c2,2.8,3,6.3,3,10.5s-1.3,8.4-4,12.5c-1.3-3.4-2-7.3-2-11.5S53,4.9,55,1.5z M68,1.5c2,3.4,3,7.3,3,11.5s-0.7,8.1-2,11.5c-2.7-4.8-4-9.3-4-13.5S66,3.6,68,1.5z M82,1.5c1.3,4.8,2,9.3,2,13.5s-1,7.4-3,9.5c-2-3.4-3-7.3-3-11.5S79.3,4.9,82,1.5z M94,1.5c2,3.4,3,7.3,3,11.5s-1.3,8.1-4,11.5c-1.3-1.4-2-4.3-2-8.5S92,6.9,94,1.5z M107,1.5c2,2.1,3,5.3,3,9.5s-0.7,8.7-2,13.5c-2.7-3.4-4-7.3-4-11.5S105,4.9,107,1.5z"/>',  group: 'tribal', viewBox: '0 0 117 26', preserveAspectRatio: true },
			stripes_tribal: { shape: '<path d="M54,1.6V26h-9V2.5L54,1.6z M69,1.6v23.3L60,26V1.6H69z M24,1.6v23.5l-9-0.6V1.6H24z M30,0l9,0.7v24.5h-9V0z M9,2.5v22H0V3.7L9,2.5z M75,1.6l9,0.9v22h-9V1.6z M99,2.7v21.7h-9V3.8L99,2.7z M114,3.8v20.7l-9-0.5V3.8L114,3.8z"/>',  group: 'tribal', viewBox: '0 0 120 26', preserveAspectRatio: true },
			squares_tribal: { shape: '<path d="M46.8,7.8v11.5L36,18.6V7.8H46.8z M82.4,7.8L84,18.6l-12,0.7L70.4,7.8H82.4z M0,7.8l12,0.9v9.9H1.3L0,7.8z M30,7.8v10.8H19L18,7.8H30z M63.7,7.8L66,18.6H54V9.5L63.7,7.8z M89.8,7L102,7.8v10.8H91.2L89.8,7zM108,7.8l12,0.9v8.9l-12,1V7.8z"/>',  group: 'tribal', viewBox: '0 0 126 26', preserveAspectRatio: true },
			trees_tribal:   { shape: '<path d="M6.4,2l4.2,5.7H7.7v2.7l3.8,5.2l-3.8,0v7.8H4.8v-7.8H0l4.8-5.2V7.7H1.1L6.4,2z M25.6,2L31,7.7h-3.7v2.7l4.8,5.2h-4.8v7.8h-2.8v-7.8l-3.8,0l3.8-5.2V7.7h-2.9L25.6,2z M47.5,2l4.2,5.7h-3.3v2.7l3.8,5.2l-3.8,0l0.4,7.8h-2.8v-7.8H41l4.8-5.2V7.7h-3.7L47.5,2z M66.2,2l5.4,5.7h-3.7v2.7l4.8,5.2h-4.8v7.8H65v-7.8l-3.8,0l3.8-5.2V7.7h-2.9L66.2,2zM87.4,2l4.8,5.7h-2.9v3.1l3.8,4.8l-3.8,0v7.8h-2.8v-7.8h-4.8l4.8-4.8V7.7h-3.7L87.4,2z M107.3,2l5.4,5.7h-3.7v2.7l4.8,5.2h-4.8v7.8H106v-7.8l-3.8,0l3.8-5.2V7.7h-2.9L107.3,2z"/>',  group: 'tribal', viewBox: '0 0 123 26', preserveAspectRatio: true },
			planes_tribal:  { shape: '<path d="M29.6,10.3l2.1,2.2l-3.6,3.3h7v2.9h-7l3.6,3.5l-2.1,1.7l-5.2-5.2h-5.8v-2.9h5.8L29.6,10.3z M70.9,9.6l2.1,1.7l-3.6,3.5h7v2.9h-7l3.6,3.3l-2.1,2.2l-5.2-5.5h-5.8v-2.9h5.8L70.9,9.6z M111.5,9.6l2.1,1.7l-3.6,3.5h7v2.9h-7l3.6,3.3l-2.1,2.2l-5.2-5.5h-5.8v-2.9h5.8L111.5,9.6z M50.2,2.7l2.1,1.7l-3.6,3.5h7v2.9h-7l3.6,3.3l-2.1,2.2L45,10.7h-5.8V7.9H45L50.2,2.7z M11,2l2.1,1.7L9.6,7.2h7V10h-7l3.6,3.3L11,15.5L5.8,10H0V7.2h5.8L11,2z M91.5,2l2.1,2.2l-3.6,3.3h7v2.9h-7l3.6,3.5l-2.1,1.7l-5.2-5.2h-5.8V7.5h5.8L91.5,2z"/>',  group: 'tribal', viewBox: '0 0 121 26', preserveAspectRatio: true },
			x_tribal:       { shape: '<path d="M10.7,6l2.5,2.6l-4,4.3l4,5.4l-2.5,1.9l-4.5-5.2l-3.9,4.2L0.7,17L4,13.1L0,8.6l2.3-1.3l3.9,3.9L10.7,6z M23.9,6.6l4.2,4.5L32,7.2l2.3,1.3l-4,4.5l3.2,3.9L32,19.1l-3.9-3.3l-4.5,4.3l-2.5-1.9l4.4-5.1l-4.2-3.9L23.9,6.6zM73.5,6L76,8.6l-4,4.3l4,5.4l-2.5,1.9l-4.5-5.2l-3.9,4.2L63.5,17l4.1-4.7L63.5,8l2.3-1.3l4.1,3.6L73.5,6z M94,6l2.5,2.6l-4,4.3l4,5.4L94,20.1l-3.9-5l-3.9,4.2L84,17l3.2-3.9L84,8.6l2.3-1.3l3.2,3.9L94,6z M106.9,6l4.5,5.1l3.9-3.9l2.3,1.3l-4,4.5l3.2,3.9l-1.6,2.1l-3.9-4.2l-4.5,5.2l-2.5-1.9l4-5.4l-4-4.3L106.9,6z M53.1,6l2.5,2.6l-4,4.3l4,4.6l-2.5,1.9l-4.5-4.5l-3.5,4.5L43.1,17l3.2-3.9l-4-4.5l2.3-1.3l3.9,3.9L53.1,6z"/>',  group: 'tribal', viewBox: '0 0 126 26', preserveAspectRatio: true },
			zigzag_tribal:  { shape: '<polygon points="0,14.4 0,21 11.5,12.4 21.3,20 30.4,11.1 40.3,20 51,12.4 60.6,20 69.6,11.1 79.3,20 90.1,12.4 99.6,20 109.7,11.1 120,21 120,14.4 109.7,5 99.6,13 90.1,5 79.3,14.5 71,5.7 60.6,12.4 51,5 40.3,14.5 31.1,5 21.3,13 11.5,5"/>',  group: 'tribal', viewBox: '0 0 120 26', preserveAspectRatio: true },
		};

		var ppWrapperStyle = '';
		if ( 'pattern' === settings.separator_type && settings.divider_style && ppSvgPatterns[ settings.divider_style ] ) {
			var ppPattern       = ppSvgPatterns[ settings.divider_style ];
			var ppPreserveAR    = ppPattern.preserveAspectRatio ? 'xMidYMid meet' : 'none';
			var ppViewBox       = ppPattern.viewBox || '0 0 24 24';
			var ppStrokeWidth   = ( settings.horizontal_height && settings.horizontal_height.size ) ? settings.horizontal_height.size : 3;
			var ppDirection = ( 'plain' === settings.divider_type && settings.divider_direction )
				? settings.divider_direction
				: 'horizontal';
			var ppShape = ppPattern.shape;
			if ( 'vertical' === ppDirection ) {
				var ppVbParts = ppViewBox.split( ' ' );
				var ppVbW     = parseFloat( ppVbParts[2] ) || 24;
				var ppVbH     = parseFloat( ppVbParts[3] ) || 24;
				ppViewBox = '0 0 ' + ppVbH + ' ' + ppVbW;
				ppShape   = '<g transform=\'translate(' + ppVbH + ',0) rotate(90)\'>' + ppShape + '</g>';
			}

			var ppFillStroke;
			if ( 'line' !== ppPattern.group ) {
				ppFillStroke = 'fill=\'black\' stroke=\'none\'';
			} else {
				ppFillStroke = 'fill=\'none\' stroke=\'black\' stroke-width=\'' + ppStrokeWidth + '\' stroke-linecap=\'square\' stroke-miterlimit=\'10\'';
			}

			var ppSvg     = '<svg xmlns=\'http://www.w3.org/2000/svg\' preserveAspectRatio=\'' + ppPreserveAR + '\' overflow=\'visible\' height=\'100%\' viewBox=\'' + ppViewBox + '\' ' + ppFillStroke + '>' + ppShape + '</svg>';
			var ppEncoded = ppSvg
				.replace( /%/g,  '%25' )  // must be first to avoid double-encoding
				.replace( /<(?!!)/g, '%3C' )
				.replace( />/g,  '%3E' )
				.replace( /#/g,  '%23' )
				.replace( /"/g,  "'"  );
			ppWrapperStyle = '--divider-pattern-url: url("data:image/svg+xml,' + ppEncoded + '");';
		}
		#>
		<div class="pp-divider-wrap"<# if ( ppWrapperStyle ) { #> style="{{ ppWrapperStyle }}"<# } #>>
			<# if ( settings.divider_type == 'plain' ) { #>
				<div class="pp-divider pp-divider-{{ settings.divider_direction }} {{ settings.divider_direction }} pp-divider-{{ settings.divider_style }} {{ settings.divider_style }} "></div>
			<# } else { #>
				<div class="divider-text-container">
					<div class="divider-text-wrap">
						<span class="pp-divider-border-wrap divider-border-left">
							<span class="divider-border"></span>
						</span>
						<span class="pp-divider-content">
							<# if ( settings.divider_type == 'text' && settings.divider_text != '' ) { #>
								<# var textHTMLTag = elementor.helpers.validateHTMLTag( settings.text_html_tag ); #>
								<{{ textHTMLTag }} class="pp-divider-{{ settings.divider_type }} elementor-inline-editing" data-elementor-setting-key="divider_text" data-elementor-inline-editing-toolbar="none">
									{{ settings.divider_text }}
								</{{ textHTMLTag }}>
							<# } else if ( settings.divider_type == 'icon' && settings.divider_icon != '' ) { #>
								<span class="pp-divider-{{ settings.divider_type }} pp-icon">
									<# if ( settings.divider_icon || settings.icon ) { #>
										<# if ( iconHTML && iconHTML.rendered && ( ! settings.divider_icon || migrated ) ) { #>
											{{{ iconHTML.value }}}
										<# } else { #>
											<i class="{{ settings.divider_icon }}" aria-hidden="true"></i>
										<# } #>
									<# } #>
								</span>
							<# } else if ( settings.divider_type == 'image' ) { #>
								<# if ( imageUrl ) { #>
									<span class="pp-divider-{{ settings.divider_type }}">{{{ imageHtml }}}</span>
								<# } #>
							<# } #>
						</span>
						<span class="pp-divider-border-wrap divider-border-right">
							<span class="divider-border"></span>
						</span>
					</div>
				</div>
			<# } #>
		</div>
		<?php
	}
}
