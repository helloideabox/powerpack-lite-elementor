<?php
namespace PowerpackElementsLite\Modules\Pricing\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Config;
use PowerpackElementsLite\Classes\PP_Helper;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pricing Table Widget
 */
class Pricing_Table extends Powerpack_Widget {

	/**
	 * Retrieve pricing table widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Pricing_Table' );
	}

	/**
	 * Retrieve pricing table widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Pricing_Table' );
	}

	/**
	 * Retrieve pricing table widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Pricing_Table' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.3.7
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Pricing_Table' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the pricing table widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 2.2.5
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {

		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return array(
				'pp-tooltipster',
				'pp-pricing-table',
			);
		}

		$settings = $this->get_settings_for_display();
		$scripts = [];

		if ( 'yes' === $settings['show_tooltip'] ) {
			array_push( $scripts, 'pp-tooltipster', 'pp-pricing-table' );
		}

		return $scripts;
	}

	/**
	 * Retrieve the list of styles the offcanvas content widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return array(
				'pp-tooltip',
				'widget-pp-pricing-table'
			);
		}

		$settings = $this->get_settings_for_display();
		$scripts = [ 'widget-pp-pricing-table' ];

		if ( 'yes' === $settings['show_tooltip'] ) {
			array_push( $scripts, 'pp-tooltip' );
		}

		return $scripts;
	}

	/**
	 * Register pricing table widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.0.3
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_header_controls();
		$this->register_content_pricing_controls();
		$this->register_content_features_controls();
		$this->register_content_ribbon_controls();
		$this->register_content_tooltip_controls();
		$this->register_content_button_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_table_controls();
		$this->register_style_header_controls();
		$this->register_style_pricing_controls();
		$this->register_style_features_controls();
		$this->register_style_tooltip_controls();
		$this->register_style_ribbon_controls();
		$this->register_style_button_controls();
		$this->register_style_footer_controls();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	CONTENT TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_content_header_controls() {
		/**
		 * Content Tab: Header
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_header',
			[
				'label'                 => esc_html__( 'Header', 'powerpack' ),
			]
		);

		$this->add_control(
			'icon_type',
			[
				'label'                 => esc_html__( 'Icon Type', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'none'        => [
						'title'   => esc_html__( 'None', 'powerpack' ),
						'icon'    => 'eicon-ban',
					],
					'icon'        => [
						'title'   => esc_html__( 'Icon', 'powerpack' ),
						'icon'    => 'eicon-star',
					],
					'image'       => [
						'title'   => esc_html__( 'Image', 'powerpack' ),
						'icon'    => 'eicon-image-bold',
					],
				],
				'default'               => 'none',
			]
		);

		$this->add_control(
			'select_table_icon',
			[
				'label'                 => esc_html__( 'Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICONS,
				'fa4compatibility'      => 'table_icon',
				'default'               => [
					'value'     => 'fas fa-star',
					'library'   => 'fa-solid',
				],
				'condition'             => [
					'icon_type'     => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_image',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
				'type'                  => Controls_Manager::MEDIA,
				'default'               => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'             => [
					'icon_type'  => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'image', // Usage: '{name}_size' and '{name}_custom_dimension', in this case 'image_size' and 'image_custom_dimension'.
				'default'               => 'full',
				'separator'             => 'none',
				'condition'             => [
					'icon_type'  => 'image',
				],
			]
		);

		$this->add_control(
			'table_title',
			[
				'label'                 => esc_html__( 'Title', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Title', 'powerpack' ),
				'title'                 => esc_html__( 'Enter table title', 'powerpack' ),
			]
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h1'   => esc_html__( 'H1', 'powerpack' ),
					'h2'   => esc_html__( 'H2', 'powerpack' ),
					'h3'   => esc_html__( 'H3', 'powerpack' ),
					'h4'   => esc_html__( 'H4', 'powerpack' ),
					'h5'   => esc_html__( 'H5', 'powerpack' ),
					'h6'   => esc_html__( 'H6', 'powerpack' ),
					'div'  => esc_html__( 'div', 'powerpack' ),
					'span' => esc_html__( 'span', 'powerpack' ),
					'p'    => esc_html__( 'p', 'powerpack' ),
				),
			)
		);

		$this->add_control(
			'table_subtitle',
			[
				'label'                 => esc_html__( 'Subtitle', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Subtitle', 'powerpack' ),
				'title'                 => esc_html__( 'Enter table subtitle', 'powerpack' ),
			]
		);

		$this->add_control(
			'subtitle_html_tag',
			array(
				'label'   => esc_html__( 'Subtitle HTML Tag', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h4',
				'options' => array(
					'h1'   => esc_html__( 'H1', 'powerpack' ),
					'h2'   => esc_html__( 'H2', 'powerpack' ),
					'h3'   => esc_html__( 'H3', 'powerpack' ),
					'h4'   => esc_html__( 'H4', 'powerpack' ),
					'h5'   => esc_html__( 'H5', 'powerpack' ),
					'h6'   => esc_html__( 'H6', 'powerpack' ),
					'div'  => esc_html__( 'div', 'powerpack' ),
					'span' => esc_html__( 'span', 'powerpack' ),
					'p'    => esc_html__( 'p', 'powerpack' ),
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_content_pricing_controls() {
		/**
		 * Content Tab: Pricing
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_pricing',
			[
				'label'                 => esc_html__( 'Pricing', 'powerpack' ),
			]
		);

		$this->add_control(
			'currency_symbol',
			[
				'label'                 => esc_html__( 'Currency Symbol', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					''             => esc_html__( 'None', 'powerpack' ),
					'dollar'       => '&#36; ' . esc_html__( 'Dollar', 'powerpack' ),
					'euro'         => '&#128; ' . esc_html__( 'Euro', 'powerpack' ),
					'baht'         => '&#3647; ' . esc_html__( 'Baht', 'powerpack' ),
					'franc'        => '&#8355; ' . esc_html__( 'Franc', 'powerpack' ),
					'guilder'      => '&fnof; ' . esc_html__( 'Guilder', 'powerpack' ),
					'krona'        => 'kr ' . esc_html__( 'Krona', 'powerpack' ),
					'lira'         => '&#8356; ' . esc_html__( 'Lira', 'powerpack' ),
					'peseta'       => '&#8359 ' . esc_html__( 'Peseta', 'powerpack' ),
					'peso'         => '&#8369; ' . esc_html__( 'Peso', 'powerpack' ),
					'pound'        => '&#163; ' . esc_html__( 'Pound Sterling', 'powerpack' ),
					'real'         => 'R$ ' . esc_html__( 'Real', 'powerpack' ),
					'ruble'        => '&#8381; ' . esc_html__( 'Ruble', 'powerpack' ),
					'rupee'        => '&#8360; ' . esc_html__( 'Rupee', 'powerpack' ),
					'indian_rupee' => '&#8377; ' . esc_html__( 'Rupee (Indian)', 'powerpack' ),
					'shekel'       => '&#8362; ' . esc_html__( 'Shekel', 'powerpack' ),
					'yen'          => '&#165; ' . esc_html__( 'Yen/Yuan', 'powerpack' ),
					'won'          => '&#8361; ' . esc_html__( 'Won', 'powerpack' ),
					'custom'       => esc_html__( 'Custom', 'powerpack' ),
				],
				'default'               => 'dollar',
			]
		);

		$this->add_control(
			'currency_symbol_custom',
			[
				'label'                 => esc_html__( 'Custom Symbol', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => '',
				'condition'             => [
					'currency_symbol'   => 'custom',
				],
			]
		);

		$this->add_control(
			'table_price',
			[
				'label'                 => esc_html__( 'Price', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => '49.99',
			]
		);

		$this->add_control(
			'currency_format',
			[
				'label'                 => esc_html__( 'Currency Format', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'raised',
				'options'               => [
					'raised' => esc_html__( 'Raised', 'powerpack' ),
					''       => esc_html__( 'Normal', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'discount',
			[
				'label'                 => esc_html__( 'Discount', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'On', 'powerpack' ),
				'label_off'             => esc_html__( 'Off', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'table_original_price',
			[
				'label'                 => esc_html__( 'Original Price', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => '69',
				'condition'             => [
					'discount' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_duration',
			[
				'label'                 => esc_html__( 'Duration', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'default'               => esc_html__( 'per month', 'powerpack' ),
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_features_controls() {
		/**
		 * Content Tab: Features
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_features',
			[
				'label'                 => esc_html__( 'Features', 'powerpack' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_features_content' );

		$repeater->start_controls_tab(
			'tab_features_content',
			[
				'label' => esc_html__( 'Content', 'powerpack' ),
			]
		);

		$repeater->add_control(
			'feature_text',
			array(
				'label'       => esc_html__( 'Text', 'powerpack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => '3',
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'Feature', 'powerpack' ),
				'default'     => esc_html__( 'Feature', 'powerpack' ),
			)
		);

		$repeater->add_control(
			'exclude',
			array(
				'label'        => esc_html__( 'Exclude', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'select_feature_icon',
			array(
				'label'            => esc_html__( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'default'          => array(
					'value'   => 'far fa-arrow-alt-circle-right',
					'library' => 'fa-regular',
				),
				'fa4compatibility' => 'feature_icon',
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_features_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'powerpack' ),
			]
		);

		$repeater->add_control(
			'tooltip_content',
			array(
				'label'       => esc_html__( 'Tooltip Content', 'powerpack' ),
				'type'        => Controls_Manager::WYSIWYG,
				'default'     => esc_html__( 'This is a tooltip', 'powerpack' ),
				'dynamic'     => array(
					'active' => true,
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_features_style',
			[
				'label' => esc_html__( 'Style', 'powerpack' ),
			]
		);

		$repeater->add_control(
			'feature_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .pp-icon i' => 'color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .pp-icon svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'select_feature_icon[value]!' => '',
				),
			)
		);

		$repeater->add_control(
			'feature_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}}',
				),
			)
		);

		$repeater->add_control(
			'feature_bg_color',
			array(
				'name'      => 'feature_bg_color',
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
				),
			)
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'table_features',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'feature_text'        => esc_html__( 'Feature #1', 'powerpack' ),
						'select_feature_icon' => 'fa fa-check',
					),
					array(
						'feature_text'        => esc_html__( 'Feature #2', 'powerpack' ),
						'select_feature_icon' => 'fa fa-check',
					),
					array(
						'feature_text'        => esc_html__( 'Feature #3', 'powerpack' ),
						'select_feature_icon' => 'fa fa-check',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ feature_text }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Pricing Table Tooltip Controls
	 *
	 * @since 2.2.5
	 * @return void
	 */
	protected function register_content_tooltip_controls() {
		$this->start_controls_section(
			'section_tooltip',
			[
				'label'                 => esc_html__( 'Tooltip', 'powerpack' ),
			]
		);

		$this->add_control(
			'show_tooltip',
			[
				'label'                 => esc_html__( 'Enable Tooltip', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'tooltip_trigger',
			[
				'label'              => esc_html__( 'Trigger', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'hover',
				'options'            => array(
					'hover' => esc_html__( 'Hover', 'powerpack' ),
					'click' => esc_html__( 'Click', 'powerpack' ),
				),
				'frontend_available' => true,
				'condition'          => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_size',
			array(
				'label'   => esc_html__( 'Size', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'powerpack' ),
					'tiny'    => esc_html__( 'Tiny', 'powerpack' ),
					'small'   => esc_html__( 'Small', 'powerpack' ),
					'large'   => esc_html__( 'Large', 'powerpack' ),
				),
				'frontend_available' => true,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			)
		);

		$this->add_control(
			'tooltip_position',
			array(
				'label'   => esc_html__( 'Position', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'          => esc_html__( 'Top', 'powerpack' ),
					'bottom'       => esc_html__( 'Bottom', 'powerpack' ),
					'left'         => esc_html__( 'Left', 'powerpack' ),
					'right'        => esc_html__( 'Right', 'powerpack' ),
				),
				'condition' => [
					'show_tooltip' => 'yes',
				],
			)
		);

		$this->add_control(
			'tooltip_arrow',
			array(
				'label'   => esc_html__( 'Show Arrow', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => array(
					'yes' => esc_html__( 'Yes', 'powerpack' ),
					'no'  => esc_html__( 'No', 'powerpack' ),
				),
				'frontend_available' => true,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			)
		);

		$this->add_control(
			'tooltip_animation',
			array(
				'label'   => esc_html__( 'Animation', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => array(
					'fade'  => esc_html__( 'Fade', 'powerpack' ),
					'fall'  => esc_html__( 'Fall', 'powerpack' ),
					'grow'  => esc_html__( 'Grow', 'powerpack' ),
					'slide' => esc_html__( 'Slide', 'powerpack' ),
					'swing' => esc_html__( 'Swing', 'powerpack' ),
				),
				'frontend_available' => true,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			)
		);

		$this->add_control(
			'tooltip_display_on',
			array(
				'label'   => esc_html__( 'Display On', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'text',
				'options' => array(
					'text' => esc_html__( 'Text', 'powerpack' ),
					'icon' => esc_html__( 'Icon', 'powerpack' ),
				),
				'frontend_available' => true,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			)
		);

		$this->add_control(
			'tooltip_icon',
			[
				'label'     => esc_html__( 'Icon', 'powerpack' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-info-circle',
					'library' => 'fa-solid',
				],
				'condition' => [
					'show_tooltip'       => 'yes',
					'tooltip_display_on' => 'icon',
				],
			]
		);

		$this->add_control(
			'tooltip_distance',
			array(
				'label'       => esc_html__( 'Distance', 'powerpack' ),
				'description' => esc_html__( 'The distance between the text/icon and the tooltip.', 'powerpack' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array(
					'size' => '',
				),
				'range'       => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'frontend_available' => true,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			)
		);

		$tooltip_animations = array(
			''                  => esc_html__( 'Default', 'powerpack' ),
			'bounce'            => esc_html__( 'Bounce', 'powerpack' ),
			'flash'             => esc_html__( 'Flash', 'powerpack' ),
			'pulse'             => esc_html__( 'Pulse', 'powerpack' ),
			'rubberBand'        => esc_html__( 'rubberBand', 'powerpack' ),
			'shake'             => esc_html__( 'Shake', 'powerpack' ),
			'swing'             => esc_html__( 'Swing', 'powerpack' ),
			'tada'              => esc_html__( 'Tada', 'powerpack' ),
			'wobble'            => esc_html__( 'Wobble', 'powerpack' ),
			'bounceIn'          => esc_html__( 'bounceIn', 'powerpack' ),
			'bounceInDown'      => esc_html__( 'bounceInDown', 'powerpack' ),
			'bounceInLeft'      => esc_html__( 'bounceInLeft', 'powerpack' ),
			'bounceInRight'     => esc_html__( 'bounceInRight', 'powerpack' ),
			'bounceInUp'        => esc_html__( 'bounceInUp', 'powerpack' ),
			'bounceOut'         => esc_html__( 'bounceOut', 'powerpack' ),
			'bounceOutDown'     => esc_html__( 'bounceOutDown', 'powerpack' ),
			'bounceOutLeft'     => esc_html__( 'bounceOutLeft', 'powerpack' ),
			'bounceOutRight'    => esc_html__( 'bounceOutRight', 'powerpack' ),
			'bounceOutUp'       => esc_html__( 'bounceOutUp', 'powerpack' ),
			'fadeIn'            => esc_html__( 'fadeIn', 'powerpack' ),
			'fadeInDown'        => esc_html__( 'fadeInDown', 'powerpack' ),
			'fadeInDownBig'     => esc_html__( 'fadeInDownBig', 'powerpack' ),
			'fadeInLeft'        => esc_html__( 'fadeInLeft', 'powerpack' ),
			'fadeInLeftBig'     => esc_html__( 'fadeInLeftBig', 'powerpack' ),
			'fadeInRight'       => esc_html__( 'fadeInRight', 'powerpack' ),
			'fadeInRightBig'    => esc_html__( 'fadeInRightBig', 'powerpack' ),
			'fadeInUp'          => esc_html__( 'fadeInUp', 'powerpack' ),
			'fadeInUpBig'       => esc_html__( 'fadeInUpBig', 'powerpack' ),
			'fadeOut'           => esc_html__( 'fadeOut', 'powerpack' ),
			'fadeOutDown'       => esc_html__( 'fadeOutDown', 'powerpack' ),
			'fadeOutDownBig'    => esc_html__( 'fadeOutDownBig', 'powerpack' ),
			'fadeOutLeft'       => esc_html__( 'fadeOutLeft', 'powerpack' ),
			'fadeOutLeftBig'    => esc_html__( 'fadeOutLeftBig', 'powerpack' ),
			'fadeOutRight'      => esc_html__( 'fadeOutRight', 'powerpack' ),
			'fadeOutRightBig'   => esc_html__( 'fadeOutRightBig', 'powerpack' ),
			'fadeOutUp'         => esc_html__( 'fadeOutUp', 'powerpack' ),
			'fadeOutUpBig'      => esc_html__( 'fadeOutUpBig', 'powerpack' ),
			'flip'              => esc_html__( 'flip', 'powerpack' ),
			'flipInX'           => esc_html__( 'flipInX', 'powerpack' ),
			'flipInY'           => esc_html__( 'flipInY', 'powerpack' ),
			'flipOutX'          => esc_html__( 'flipOutX', 'powerpack' ),
			'flipOutY'          => esc_html__( 'flipOutY', 'powerpack' ),
			'lightSpeedIn'      => esc_html__( 'lightSpeedIn', 'powerpack' ),
			'lightSpeedOut'     => esc_html__( 'lightSpeedOut', 'powerpack' ),
			'rotateIn'          => esc_html__( 'rotateIn', 'powerpack' ),
			'rotateInDownLeft'  => esc_html__( 'rotateInDownLeft', 'powerpack' ),
			'rotateInDownLeft'  => esc_html__( 'rotateInDownRight', 'powerpack' ),
			'rotateInUpLeft'    => esc_html__( 'rotateInUpLeft', 'powerpack' ),
			'rotateInUpRight'   => esc_html__( 'rotateInUpRight', 'powerpack' ),
			'rotateOut'         => esc_html__( 'rotateOut', 'powerpack' ),
			'rotateOutDownLeft' => esc_html__( 'rotateOutDownLeft', 'powerpack' ),
			'rotateOutDownLeft' => esc_html__( 'rotateOutDownRight', 'powerpack' ),
			'rotateOutUpLeft'   => esc_html__( 'rotateOutUpLeft', 'powerpack' ),
			'rotateOutUpRight'  => esc_html__( 'rotateOutUpRight', 'powerpack' ),
			'hinge'             => esc_html__( 'Hinge', 'powerpack' ),
			'rollIn'            => esc_html__( 'rollIn', 'powerpack' ),
			'rollOut'           => esc_html__( 'rollOut', 'powerpack' ),
			'zoomIn'            => esc_html__( 'zoomIn', 'powerpack' ),
			'zoomInDown'        => esc_html__( 'zoomInDown', 'powerpack' ),
			'zoomInLeft'        => esc_html__( 'zoomInLeft', 'powerpack' ),
			'zoomInRight'       => esc_html__( 'zoomInRight', 'powerpack' ),
			'zoomInUp'          => esc_html__( 'zoomInUp', 'powerpack' ),
			'zoomOut'           => esc_html__( 'zoomOut', 'powerpack' ),
			'zoomOutDown'       => esc_html__( 'zoomOutDown', 'powerpack' ),
			'zoomOutLeft'       => esc_html__( 'zoomOutLeft', 'powerpack' ),
			'zoomOutRight'      => esc_html__( 'zoomOutRight', 'powerpack' ),
			'zoomOutUp'         => esc_html__( 'zoomOutUp', 'powerpack' ),
		);

		/* $this->add_control(
			'tooltip_animation_in',
			array(
				'label'   => esc_html__( 'Animation In', 'powerpack' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => '',
				'options' => $tooltip_animations,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			)
		);

		$this->add_control(
			'tooltip_animation_out',
			array(
				'label'   => esc_html__( 'Animation Out', 'powerpack' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => '',
				'options' => $tooltip_animations,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			)
		); */

		$this->add_control(
			'tooltip_zindex',
			array(
				'label'              => esc_html__( 'Z-Index', 'powerpack' ),
				'description'        => esc_html__( 'Increase the z-index value if you are unable to see the tooltip. For example: 99, 999, 9999 ', 'powerpack' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 99,
				'min'                => -9999999,
				'step'               => 1,
				'frontend_available' => true,
				'condition'          => [
					'show_tooltip' => 'yes',
				],
			)
		);

		$this->end_controls_section();
	}

	protected function register_content_ribbon_controls() {
		/**
		 * Content Tab: Ribbon
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_ribbon',
			[
				'label'                 => esc_html__( 'Ribbon', 'powerpack' ),
			]
		);

		$this->add_control(
			'show_ribbon',
			[
				'label'                 => esc_html__( 'Show Ribbon', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'ribbon_style',
			[
				'label'                => esc_html__( 'Style', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => '1',
				'options'              => [
					'1'         => esc_html__( 'Default', 'powerpack' ),
					'2'         => esc_html__( 'Circle', 'powerpack' ),
					'3'         => esc_html__( 'Flag', 'powerpack' ),
				],
				'condition'             => [
					'show_ribbon'  => 'yes',
				],
			]
		);

		$this->add_control(
			'ribbon_title',
			[
				'label'                 => esc_html__( 'Title', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'New', 'powerpack' ),
				'condition'             => [
					'show_ribbon'  => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'ribbon_size',
			[
				'label'                 => esc_html__( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 200,
					],
					'em' => [
						'min'   => 1,
						'max'   => 15,
					],
				],
				'default'               => [
					'size'      => 4,
					'unit'      => 'em',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-ribbon-2' => 'min-width: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'show_ribbon'  => 'yes',
					'ribbon_style' => [ '2' ],
				],
			]
		);

		$this->add_responsive_control(
			'top_distance',
			[
				'label'                 => esc_html__( 'Distance from Top', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 200,
					],
				],
				'default'               => [
					'size'      => 20,
					'unit'      => '%',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-ribbon' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'show_ribbon'  => 'yes',
					'ribbon_style' => [ '2', '3' ],
				],
			]
		);

		$ribbon_distance_transform = is_rtl() ? 'translateY(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)' : 'translateY(-50%) translateX(-50%) translateX({{SIZE}}{{UNIT}}) rotate(-45deg)';

		$this->add_responsive_control(
			'ribbon_distance',
			[
				'label'                 => esc_html__( 'Distance', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-ribbon-inner' => 'margin-top: {{SIZE}}{{UNIT}}; transform: ' . $ribbon_distance_transform,
				],
				'condition'             => [
					'show_ribbon'  => 'yes',
					'ribbon_style' => [ '1' ],
				],
			]
		);

		$this->add_control(
			'ribbon_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'toggle'                => false,
				'label_block'           => false,
				'options'               => [
					'left'  => [
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'               => 'right',
				'condition'             => [
					'show_ribbon'  => 'yes',
					'ribbon_style' => [ '1', '2', '3' ],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_button_controls() {
		/**
		 * Content Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_button',
			[
				'label'                 => esc_html__( 'Button', 'powerpack' ),
			]
		);

		$this->add_control(
			'table_button_position',
			[
				'label'                => esc_html__( 'Button Position', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'below',
				'options'              => [
					'above'    => esc_html__( 'Above Features', 'powerpack' ),
					'below'    => esc_html__( 'Below Features', 'powerpack' ),
					'none'    => esc_html__( 'None', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'table_button_text',
			[
				'label'                 => esc_html__( 'Button Text', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Get Started', 'powerpack' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'                 => esc_html__( 'Link', 'powerpack' ),
				'label_block'           => true,
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
			'table_additional_info',
			[
				'label'                 => esc_html__( 'Additional Info', 'powerpack' ),
				'type'                  => Controls_Manager::TEXTAREA,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Enter additional info here', 'powerpack' ),
				'title'                 => esc_html__( 'Additional Info', 'powerpack' ),
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Pricing_Table' );
		if ( ! empty( $help_docs ) ) {
			/**
			 * Content Tab: Docs Links
			 *
			 * @since 1.4.8
			 * @access protected
			 */
			$this->start_controls_section(
				'section_help_docs',
				[
					'label' => esc_html__( 'Help Docs', 'powerpack' ),
				]
			);

			$hd_counter = 1;
			foreach ( $help_docs as $hd_title => $hd_link ) {
				$this->add_control(
					'help_doc_' . $hd_counter,
					[
						'type'            => Controls_Manager::RAW_HTML,
						'raw'             => sprintf( '%1$s ' . $hd_title . ' %2$s', '<a href="' . $hd_link . '" target="_blank" rel="noopener">', '</a>' ),
						'content_classes' => 'pp-editor-doc-links',
					]
				);

				$hd_counter++;
			}

			$this->end_controls_section();
		}
	}

	/*-----------------------------------------------------------------------------------*/
	/*	STYLE TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_style_table_controls() {
		/**
		 * Content Tab: Table
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_style',
			[
				'label'                 => esc_html__( 'Table', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'table_align',
			[
				'label'                 => esc_html__( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'    => [
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'     => [
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'               => '',
				'prefix_class'      => 'pp-pricing-table-align-',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_header_controls() {
		/**
		 * Style Tab: Header
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_header_style',
			[
				'label'                 => esc_html__( 'Header', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'table_title_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'global'                => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-head' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'table_header_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'condition'             => [
					'table_button_text!' => '',
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-head',
			]
		);

		$this->add_responsive_control(
			'table_title_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_title_icon',
			[
				'label'                 => esc_html__( 'Icon', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'icon_type!' => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'table_icon_size',
			[
				'label'                 => esc_html__( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'unit' => 'px',
					'size' => 26,
				],
				'range'                 => [
					'px' => [
						'min'   => 5,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'condition'             => [
					'icon_type'   => 'icon',
					'select_table_icon[value]!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'table_icon_image_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 120,
					'unit' => 'px',
				],
				'range'                 => [
					'px' => [
						'min'   => 1,
						'max'   => 1200,
						'step'  => 1,
					],
				],
				'condition'             => [
					'icon_type'        => 'image',
					'icon_image[url]!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-icon' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'table_icon_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'icon_type!' => 'none',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-icon' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'table_icon_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#ffffff',
				'condition'             => [
					'icon_type'   => 'icon',
					'select_table_icon[value]!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-pricing-table-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'table_icon_margin',
			[
				'label'                 => esc_html__( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'condition'             => [
					'icon_type!' => 'none',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'table_icon_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'condition'             => [
					'icon_type!' => 'none',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'table_icon_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'condition'             => [
					'icon_type!' => 'none',
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-icon',
			]
		);

		$this->add_control(
			'icon_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'condition'             => [
					'icon_type!' => 'none',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-icon, {{WRAPPER}} .pp-pricing-table-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_title_heading',
			[
				'label'                 => esc_html__( 'Title', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'table_title_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#fff',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'table_title_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-title',
			]
		);

		$this->add_control(
			'table_subtitle_heading',
			[
				'label'                 => esc_html__( 'Sub Title', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'table_subtitle!' => '',
				],
			]
		);

		$this->add_control(
			'table_subtitle_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#fff',
				'condition'             => [
					'table_subtitle!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'table_subtitle_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'condition'             => [
					'table_subtitle!' => '',
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-subtitle',
			]
		);

		$this->add_responsive_control(
			'table_subtitle_spacing',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'condition'             => [
					'table_subtitle!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-subtitle' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_pricing_controls() {
		/**
		 * Style Tab: Pricing
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_pricing_style',
			[
				'label'                 => esc_html__( 'Pricing', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'table_pricing_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-price',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'table_price_color_normal',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-price' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'table_price_bg_color_normal',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-price' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'price_border_normal',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-pricing-table-price',
			]
		);

		$this->add_control(
			'pricing_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-price' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'table_pricing_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'                 => [
					'%' => [
						'min'   => 1,
						'max'   => 100,
						'step'  => 1,
					],
					'px' => [
						'min'   => 25,
						'max'   => 1200,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-price' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'table_price_margin',
			[
				'label'                 => esc_html__( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'table_price_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'pa_logo_wrapper_shadow',
				'selector'              => '{{WRAPPER}} .pp-pricing-table-price',
			]
		);

		$this->add_control(
			'table_curreny_heading',
			[
				'label'                 => esc_html__( 'Currency Symbol', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition' => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->add_control(
			'currency_size',
			[
				'label'                 => esc_html__( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-price-prefix' => 'font-size: calc({{SIZE}}em/100)',
				],
				'condition'             => [
					'currency_symbol!' => '',
				],
			]
		);

		$this->add_control(
			'currency_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'default'               => 'before',
				'options'               => [
					'before' => [
						'title' => esc_html__( 'Before', 'powerpack' ),
						'icon' => 'eicon-h-align-left',
					],
					'after' => [
						'title' => esc_html__( 'After', 'powerpack' ),
						'icon' => 'eicon-h-align-right',
					],
				],
			]
		);

		$this->add_control(
			'currency_vertical_position',
			[
				'label'                 => esc_html__( 'Vertical Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'top'       => [
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle'    => [
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom'    => [
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'               => 'top',
				'selectors_dictionary'  => [
					'top'      => 'flex-start',
					'middle'   => 'center',
					'bottom'   => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-price-prefix' => 'align-self: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'table_duration_heading',
			[
				'label'                 => esc_html__( 'Duration', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'duration_position',
			[
				'label'                => esc_html__( 'Duration Position', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'wrap',
				'options'              => [
					'nowrap'    => esc_html__( 'Same Line', 'powerpack' ),
					'wrap'      => esc_html__( 'Next Line', 'powerpack' ),
				],
				'prefix_class' => 'pp-pricing-table-price-duration-',
			]
		);

		$this->add_control(
			'duration_text_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-price-duration' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'duration_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-price-duration',
			]
		);

		$this->add_responsive_control(
			'duration_spacing',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}}.pp-pricing-table-price-duration-wrap .pp-pricing-table-price-duration' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'duration_position' => 'wrap',
				],
			]
		);

		$this->add_control(
			'table_original_price_style_heading',
			[
				'label'                 => esc_html__( 'Original Price', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'discount' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_original_price_text_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'discount' => 'yes',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-price-original' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'table_original_price_text_size',
			[
				'label'                 => esc_html__( 'Font Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 5,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'condition'             => [
					'discount' => 'yes',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-price-original' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_features_controls() {
		/**
		 * Style Tab: Features
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_features_style',
			[
				'label'                 => esc_html__( 'Features', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'table_features_align',
			[
				'label'                 => esc_html__( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'    => [
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'     => [
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-features'   => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'table_features_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-features' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'table_features_text_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-features' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'table_features_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'default'               => [
					'top'       => '20',
					'right'     => '',
					'bottom'    => '20',
					'left'      => '',
					'unit'      => 'px',
					'isLinked'  => false,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'table_features_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 60,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-features' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'table_features_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-features',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'table_features_icon_heading',
			[
				'label'                 => esc_html__( 'Icon', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'table_features_icon_color',
			[
				'label'                 => esc_html__( 'Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-fature-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-pricing-table-fature-icon svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'table_features_icon_size',
			[
				'label'                 => esc_html__( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 5,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-fature-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'table_features_icon_spacing',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'default'               => [
					'size' => 5,
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-fature-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_features_rows_heading',
			[
				'label'                 => esc_html__( 'Rows', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_responsive_control(
			'table_features_spacing',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'unit' => 'px',
					'size' => 10,
				],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-features li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_features_alternate',
			[
				'label'                 => esc_html__( 'Striped Rows', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_responsive_control(
			'table_features_rows_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-features li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'             => [
					'table_features_alternate' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_features_style' );

		$this->start_controls_tab(
			'tab_features_even',
			[
				'label'                 => esc_html__( 'Even', 'powerpack' ),
				'condition'             => [
					'table_features_alternate' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_features_bg_color_even',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-features li:nth-child(even)' => 'background-color: {{VALUE}}',
				],
				'condition'             => [
					'table_features_alternate' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_features_text_color_even',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-features li:nth-child(even)' => 'color: {{VALUE}}',
				],
				'condition'             => [
					'table_features_alternate' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_features_odd',
			[
				'label'                 => esc_html__( 'Odd', 'powerpack' ),
				'condition'             => [
					'table_features_alternate' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_features_bg_color_odd',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-features li:nth-child(odd)' => 'background-color: {{VALUE}}',
				],
				'condition'             => [
					'table_features_alternate' => 'yes',
				],
			]
		);

		$this->add_control(
			'table_features_text_color_odd',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-features li:nth-child(odd)' => 'color: {{VALUE}}',
				],
				'condition'             => [
					'table_features_alternate' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'table_divider_heading',
			[
				'label'                 => esc_html__( 'Divider', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'table_feature_divider',
				'label'                 => esc_html__( 'Divider', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-pricing-table-features li',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Tooltip Style Controls
	 *
	 * @since 2.2.5
	 * @return void
	 */
	protected function register_style_tooltip_controls() {

		$this->start_controls_section(
			'section_tooltips_style',
			[
				'label'     => esc_html__( 'Tooltip', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'.pp-tooltip.pp-tooltip-{{ID}} .tooltipster-box' => 'background-color: {{VALUE}};',
					'.pp-tooltip.pp-tooltip-{{ID}}.tooltipster-top .tooltipster-arrow-background' => 'border-top-color: {{VALUE}};',
					'.pp-tooltip.pp-tooltip-{{ID}}.tooltipster-bottom .tooltipster-arrow-background' => 'border-bottom-color: {{VALUE}};',
					'.pp-tooltip.pp-tooltip-{{ID}}.tooltipster-left .tooltipster-arrow-background' => 'border-left-color: {{VALUE}};',
					'.pp-tooltip.pp-tooltip-{{ID}}.tooltipster-right .tooltipster-arrow-background' => 'border-right-color: {{VALUE}};',
				],
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'.pp-tooltip.pp-tooltip-{{ID}} .pp-tooltip-content' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_width',
			[
				'label'     => esc_html__( 'Width', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 50,
						'max'  => 400,
						'step' => 1,
					],
				],
				'frontend_available' => true,
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'tooltip_typography',
				'label'     => esc_html__( 'Typography', 'powerpack' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'  => '.pp-tooltip.pp-tooltip-{{ID}} .pp-tooltip-content',
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'tooltip_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.pp-tooltip.pp-tooltip-{{ID}} .tooltipster-box',
				'condition'   => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'.pp-tooltip.pp-tooltip-{{ID}} .tooltipster-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'tooltip_padding',
			[
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'.pp-tooltip.pp-tooltip-{{ID}} .tooltipster-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'tooltip_box_shadow',
				'selector'  => '.pp-tooltip.pp-tooltip-{{ID}} .tooltipster-box',
				'condition' => [
					'show_tooltip' => 'yes',
				],
			]
		);

		$this->add_control(
			'tooltip_icon_style_heading',
			[
				'label'     => esc_html__( 'Tooltip Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_tooltip'       => 'yes',
					'tooltip_display_on' => 'icon',
				],
			]
		);

		$this->add_control(
			'tooltip_icon_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-pricing-table-features .pp-pricing-table-tooltip-icon' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_tooltip'       => 'yes',
					'tooltip_display_on' => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'tooltip_icon_size',
			[
				'label'      => esc_html__( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'   => 5,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-pricing-table-features .pp-pricing-table-tooltip-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'show_tooltip'       => 'yes',
					'tooltip_display_on' => 'icon',
				],
			]
		);

		$this->add_responsive_control(
			'tooltip_icon_spacing',
			array(
				'label'      => esc_html__( 'Icon Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-pricing-table-tooltip-icon' => 'margin-left: {{SIZE}}px;',
				),
				'condition'  => [
					'show_tooltip'       => 'yes',
					'tooltip_display_on' => 'icon',
				],
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_ribbon_controls() {
		/**
		 * Style Tab: Ribbon
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_ribbon_style',
			[
				'label'                 => esc_html__( 'Ribbon', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ribbon_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-ribbon .pp-pricing-table-ribbon-inner' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .pp-pricing-table-ribbon-3.pp-pricing-table-ribbon-right:before' => 'border-left-color: {{VALUE}}',
					'{{WRAPPER}} .pp-pricing-table-ribbon-3.pp-pricing-table-ribbon-left:before' => 'border-right-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'ribbon_text_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '#ffffff',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-ribbon .pp-pricing-table-ribbon-inner' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'ribbon_typography',
				'selector'              => '{{WRAPPER}} .pp-pricing-table-ribbon .pp-pricing-table-ribbon-inner',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'box_shadow',
				'selector'              => '{{WRAPPER}} .pp-pricing-table-ribbon .pp-pricing-table-ribbon-inner',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_button_controls() {
		/**
		 * Style Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_button_style',
			[
				'label'                 => esc_html__( 'Button', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'table_button_text!' => '',
				],
			]
		);

		$this->add_control(
			'table_button_size',
			[
				'label'                 => esc_html__( 'Size', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'md',
				'options'               => [
					'xs' => esc_html__( 'Extra Small', 'powerpack' ),
					'sm' => esc_html__( 'Small', 'powerpack' ),
					'md' => esc_html__( 'Medium', 'powerpack' ),
					'lg' => esc_html__( 'Large', 'powerpack' ),
					'xl' => esc_html__( 'Extra Large', 'powerpack' ),
				],
				'condition'             => [
					'table_button_text!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'button_spacing',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'      => 20,
					'unit'      => 'px',
				],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-button-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'table_button_text!' => '',
					'table_button_position' => 'above',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label'                 => esc_html__( 'Normal', 'powerpack' ),
				'condition'             => [
					'table_button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_bg_color_normal',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-button' => 'background-color: {{VALUE}}',
				],
				'condition'             => [
					'table_button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_text_color_normal',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'table_button_text!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_normal',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'condition'             => [
					'table_button_text!' => '',
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-button',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'button_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'condition'             => [
					'table_button_text!' => '',
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-button',
			]
		);

		$this->add_responsive_control(
			'table_button_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'condition'             => [
					'table_button_text!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'condition'             => [
					'table_button_text!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'pa_pricing_table_button_shadow',
				'condition'             => [
					'table_button_text!' => '',
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
				'condition'             => [
					'table_button_text!' => '',
				],
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'table_button_text!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'table_button_text!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'button_border_hover',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'condition'             => [
					'table_button_text!' => '',
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-button:hover',
			]
		);

		$this->add_control(
			'button_hover_animation',
			[
				'label'                 => esc_html__( 'Animation', 'powerpack' ),
				'type'                  => Controls_Manager::HOVER_ANIMATION,
				'condition'             => [
					'table_button_text!' => '',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_footer_controls() {
		/**
		 * Style Tab: Footer
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_table_footer_style',
			[
				'label'                 => esc_html__( 'Footer', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'table_footer_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-footer' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'table_footer_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'default'               => [
					'top'       => '30',
					'right'     => '30',
					'bottom'    => '30',
					'left'      => '30',
					'unit'      => 'px',
					'isLinked'  => true,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'table_additional_info_heading',
			[
				'label'                 => esc_html__( 'Additional Info', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'table_additional_info!' => '',
				],
			]
		);

		$this->add_control(
			'additional_info_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'               => '',
				'condition'             => [
					'table_additional_info!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-additional-info' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'additional_info_bg_color',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'table_additional_info!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-additional-info' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'additional_info_margin',
			[
				'label'                 => esc_html__( 'Margin Top', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'      => 20,
					'unit'      => 'px',
				],
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 100,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-additional-info' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'table_additional_info!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'additional_info_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'condition'             => [
					'table_additional_info!' => '',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-pricing-table-additional-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'additional_info_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'condition'             => [
					'table_additional_info!' => '',
				],
				'selector'              => '{{WRAPPER}} .pp-pricing-table-additional-info',
			]
		);

		$this->end_controls_section();

	}

	private function render_currency_symbol( $symbol, $location ) {
		$currency_position = $this->get_settings( 'currency_position' );
		$location_setting = ! empty( $currency_position ) ? $currency_position : 'before';
		if ( ! empty( $symbol ) && $location === $location_setting ) {
			$symbol = apply_filters( 'ppe_pricing_table_currency', $symbol, $this->get_id() );

			echo '<span class="pp-pricing-table-price-prefix">' . $symbol . '</span>';
		}
	}

	private function get_currency_symbol( $symbol_name ) {
		$symbols = [
			'dollar'         => '&#36;',
			'euro'           => '&#128;',
			'franc'          => '&#8355;',
			'pound'          => '&#163;',
			'ruble'          => '&#8381;',
			'shekel'         => '&#8362;',
			'baht'           => '&#3647;',
			'yen'            => '&#165;',
			'won'            => '&#8361;',
			'guilder'        => '&fnof;',
			'peso'           => '&#8369;',
			'peseta'         => '&#8359',
			'lira'           => '&#8356;',
			'rupee'          => '&#8360;',
			'indian_rupee'   => '&#8377;',
			'real'           => 'R$',
			'krona'          => 'kr',
		];
		return isset( $symbols[ $symbol_name ] ) ? $symbols[ $symbol_name ] : '';
	}

	/**
	 * Render pricing table widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function get_tooltip_attributes( $item, $tooltip_key, $tooltip_content_key ) {
		$settings = $this->get_settings_for_display();
		$tooltip_position = $settings['tooltip_position'];
		$tooltip_content_id  = $this->get_id() . '-' . $item['_id'];

		$this->add_render_attribute(
			$tooltip_key,
			array(
				'class'                 => 'pp-pricing-table-tooptip',
				'data-tooltip'          => 'yes',
				'data-tooltip-position' => $tooltip_position,
				'data-tooltip-content'  => '#pp-tooltip-content-' . $tooltip_content_id,
			)
		);

		if ( $settings['tooltip_distance']['size'] ) {
			$this->add_render_attribute( $tooltip_key, 'data-tooltip-distance', $settings['tooltip_distance']['size'] );
		}

		if ( $settings['tooltip_width']['size'] ) {
			$this->add_render_attribute( $tooltip_key, 'data-tooltip-width', $settings['tooltip_width']['size'] );
		}

		$this->add_render_attribute(
			$tooltip_content_key,
			array(
				'class' => [ 'pp-tooltip-content', 'pp-tooltip-content-' . $this->get_id() ],
				'id'    => 'pp-tooltip-content-' . $tooltip_content_id,
			)
		);

		/* if ( $settings['tooltip_animation_in'] ) {
			$this->add_render_attribute( $tooltip_key, 'data-tooltip-animation-in', $settings['tooltip_animation_in'] );
		}

		if ( $settings['tooltip_animation_out'] ) {
			$this->add_render_attribute( $tooltip_key, 'data-tooltip-animation-out', $settings['tooltip_animation_out'] );
		} */
	}

	/**
	 * Render pricing table widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$symbol = '';

		if ( ! empty( $settings['currency_symbol'] ) ) {
			if ( 'custom' !== $settings['currency_symbol'] ) {
				$symbol = $this->get_currency_symbol( $settings['currency_symbol'] );
			} else {
				$symbol = $settings['currency_symbol_custom'];
			}
		}

		if ( ! isset( $settings['table_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['table_icon'] = 'fa fa-star';
		}

		$has_icon = ! empty( $settings['table_icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['table_icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		if ( ! $has_icon && ! empty( $settings['select_table_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['select_table_icon'] );
		$is_new = ! isset( $settings['table_icon'] ) && Icons_Manager::is_migration_allowed();

		$this->add_inline_editing_attributes( 'table_title', 'none' );
		$this->add_render_attribute( 'table_title', 'class', 'pp-pricing-table-title' );

		$this->add_inline_editing_attributes( 'table_subtitle', 'none' );
		$this->add_render_attribute( 'table_subtitle', 'class', 'pp-pricing-table-subtitle' );

		$this->add_render_attribute( 'table_price', 'class', 'pp-pricing-table-price-value' );

		$this->add_inline_editing_attributes( 'table_duration', 'none' );
		$this->add_render_attribute( 'table_duration', 'class', 'pp-pricing-table-price-duration' );

		$this->add_inline_editing_attributes( 'table_additional_info', 'none' );
		$this->add_render_attribute( 'table_additional_info', 'class', 'pp-pricing-table-additional-info' );

		$this->add_render_attribute( 'pricing-table', 'class', 'pp-pricing-table' );

		$this->add_render_attribute( 'feature-list-item', 'class', '' );

		$this->add_inline_editing_attributes( 'table_button_text', 'none' );

		$this->add_render_attribute( 'table_button_text', 'class', [
			'pp-pricing-table-button',
			'elementor-button',
			'elementor-size-' . $settings['table_button_size'],
		] );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'table_button_text', $settings['link'] );
		}

		$this->add_render_attribute( 'pricing-table-duration', 'class', 'pp-pricing-table-price-duration' );
		if ( 'wrap' === $settings['duration_position'] ) {
			$this->add_render_attribute( 'pricing-table-duration', 'class', 'next-line' );
		}

		if ( $settings['button_hover_animation'] ) {
			$this->add_render_attribute( 'table_button_text', 'class', 'elementor-animation-' . $settings['button_hover_animation'] );
		}

		if ( 'raised' === $settings['currency_format'] ) {
			$price = explode( '.', $settings['table_price'] );
			$intvalue = $price[0];
			$fraction = '';
			if ( 2 === count( $price ) ) {
				$fraction = $price[1];
			}
		} else {
			$intvalue = $settings['table_price'];
			$fraction = '';
		}
		?>
		<div class="pp-pricing-table-container">
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'pricing-table' ) ); ?>>
				<div class="pp-pricing-table-head">
					<?php if ( 'none' !== $settings['icon_type'] ) { ?>
						<div class="pp-pricing-table-icon-wrap">
							<?php if ( 'icon' === $settings['icon_type'] && $has_icon ) { ?>
								<span class="pp-pricing-table-icon pp-icon">
									<?php
									if ( $is_new || $migrated ) {
										Icons_Manager::render_icon( $settings['select_table_icon'], [ 'aria-hidden' => 'true' ] );
									} elseif ( ! empty( $settings['table_icon'] ) ) {
										?><i <?php echo wp_kses_post( $this->get_render_attribute_string( 'i' ) ); ?>></i><?php
									}
									?>
								</span>
							<?php } elseif ( 'image' === $settings['icon_type'] ) { ?>
								<?php $image = $settings['icon_image'];
								if ( $image['url'] ) { ?>
									<span class="pp-pricing-table-icon pp-pricing-table-icon-image">
										<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'image', 'icon_image' ) ); ?>
									</span>
								<?php } ?>
							<?php } ?>
						</div>
					<?php } ?>
					<div class="pp-pricing-table-title-wrap">
						<?php
						if ( $settings['table_title'] ) {
							$title_tag = PP_Helper::validate_html_tag( $settings['title_html_tag'] );
							?>
							<<?php echo esc_html( $title_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'table_title' ) ); ?>>
								<?php echo wp_kses_post( $settings['table_title'] ); ?>
							</<?php echo esc_html( $title_tag ); ?>>
							<?php
						}

						if ( $settings['table_subtitle'] ) {
							$subtitle_tag = PP_Helper::validate_html_tag( $settings['subtitle_html_tag'] );
							?>
							<<?php echo esc_html( $subtitle_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'table_subtitle' ) ); ?>>
								<?php echo wp_kses_post( $settings['table_subtitle'] ); ?>
							</<?php echo esc_html( $subtitle_tag ); ?>>
							<?php
						}
						?>
					</div>
				</div>
				<div class="pp-pricing-table-price-wrap">
					<div class="pp-pricing-table-price">
						<?php if ( 'yes' === $settings['discount'] && $settings['table_original_price'] ) { ?>
							<span class="pp-pricing-table-price-original">
								<?php
									$this->render_currency_symbol( $symbol, 'before' );
									$this->print_unescaped_setting( 'table_original_price' );
									$this->render_currency_symbol( $symbol, 'after' );
								?>
							</span>
						<?php } ?>
						<?php $this->render_currency_symbol( $symbol, 'before' ); ?>
						<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'table_price' ) ); ?>>
							<span class="pp-pricing-table-integer-part">
								<?php
									// PHPCS - the main text of a widget should not be escaped.
									echo $intvalue; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</span>
							<?php if ( $fraction ) { ?>
								<span class="pp-pricing-table-after-part">
									<?php echo esc_attr( $fraction ); ?>
								</span>
							<?php } ?>
						</span>
						<?php $this->render_currency_symbol( $symbol, 'after' ); ?>
						<?php if ( $settings['table_duration'] ) { ?>
							<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'table_duration' ) ); ?>>
								<?php echo wp_kses_post( $settings['table_duration'] ); ?>
							</span>
						<?php } ?>
					</div>
				</div>
				<?php if ( 'above' === $settings['table_button_position'] ) { ?>
					<div class="pp-pricing-table-button-wrap">
						<?php if ( $settings['table_button_text'] ) { ?>
							<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'table_button_text' ) ); ?>>
								<?php echo wp_kses_post( $settings['table_button_text'] ); ?>
							</a>
						<?php } ?>
					</div>
				<?php } ?>
				<ul class="pp-pricing-table-features">
					<?php foreach ( $settings['table_features'] as $index => $item ) : ?>
						<?php
						$fallback_defaults = [
							'fa fa-check',
							'fa fa-times',
							'fa fa-dot-circle-o',
						];

						$migration_allowed = Icons_Manager::is_migration_allowed();

						// add old default
						if ( ! isset( $item['feature_icon'] ) && ! $migration_allowed ) {
							$item['feature_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
						}

						$migrated = isset( $item['__fa4_migrated']['select_feature_icon'] );
						$is_new = ! isset( $item['feature_icon'] ) && $migration_allowed;

						$feature_list_key = $this->get_repeater_setting_key( 'feature_list_key', 'table_features', $index );
						$this->add_render_attribute( $feature_list_key, 'class', 'elementor-repeater-item-' . $item['_id'] );

						$feature_content_key = $this->get_repeater_setting_key( 'feature_content_key', 'table_features', $index );
						$this->add_render_attribute( $feature_content_key, 'class', 'pp-pricing-table-feature-content' );

						$tooltip_icon_key = $this->get_repeater_setting_key( 'tooltip_icon_key', 'table_features', $index );
						$this->add_render_attribute( $tooltip_icon_key, 'class', ['pp-pricing-table-tooltip-icon', 'pp-icon'] );

						$tooltip_content_key = $this->get_repeater_setting_key( 'tooltip_content_key', 'table_features', $index );

						if ( 'yes' === $settings['show_tooltip'] && $item['tooltip_content'] ) {
							if ( 'text' === $settings['tooltip_display_on'] ) {
								$this->get_tooltip_attributes( $item, $feature_content_key, $tooltip_content_key );
								if ( 'click' === $settings['tooltip_trigger'] ) {
									$this->add_render_attribute( $feature_content_key, 'class', 'pp-tooltip-click' );
								}
							} else {
								$this->get_tooltip_attributes( $item, $tooltip_icon_key, $tooltip_content_key );
								if ( 'click' === $settings['tooltip_trigger'] ) {
									$this->add_render_attribute( $tooltip_icon_key, 'class', 'pp-tooltip-click' );
								}
							}
						}

						$feature_key = $this->get_repeater_setting_key( 'feature_text', 'table_features', $index );
						$this->add_render_attribute( $feature_key, 'class', 'pp-pricing-table-feature-text' );
						$this->add_inline_editing_attributes( $feature_key, 'none' );

						if ( 'yes' === $item['exclude'] ) {
							$this->add_render_attribute( $feature_list_key, 'class', 'excluded' );
						}
						?>
						<li <?php echo wp_kses_post( $this->get_render_attribute_string( $feature_list_key ) ); ?>>
							<div <?php echo wp_kses_post( $this->get_render_attribute_string( $feature_content_key ) ); ?>>
								<?php
								if ( ! empty( $item['feature_icon'] ) || ( ! empty( $item['select_feature_icon']['value'] ) && $is_new ) ) : ?>
									<span class="pp-pricing-table-fature-icon pp-icon">
										<?php
										if ( $is_new || $migrated ) {
											Icons_Manager::render_icon( $item['select_feature_icon'], [ 'aria-hidden' => 'true' ] );
										} else { ?>
											<i class="<?php echo esc_attr( $item['feature_icon'] ); ?>" aria-hidden="true"></i>
											<?php
										}
										?>
									</span>
									<?php
									endif;
								?>
								<?php if ( $item['feature_text'] ) { ?>
									<span <?php echo wp_kses_post( $this->get_render_attribute_string( $feature_key ) ); ?>>
										<?php echo wp_kses_post( $item['feature_text'] ); ?>
									</span>
								<?php } ?>
								<?php if ( 'yes' === $settings['show_tooltip'] && $item['tooltip_content'] ) { ?>
									<?php if ( 'icon' === $settings['tooltip_display_on'] ) { ?>
										<span <?php echo wp_kses_post( $this->get_render_attribute_string( $tooltip_icon_key ) ); ?>>
											<?php \Elementor\Icons_Manager::render_icon( $settings['tooltip_icon'], array( 'aria-hidden' => 'true' ) ); ?>
										</span>
									<?php } ?>
									<div class="pp-tooltip-container">
										<div <?php echo wp_kses_post( $this->get_render_attribute_string( $tooltip_content_key ) ); ?>>
											<?php echo wp_kses_post( $item['tooltip_content'] ); ?>
										</div>
									</div>
								<?php } ?>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
				<div class="pp-pricing-table-footer">
					<?php if ( 'below' === $settings['table_button_position'] ) { ?>
						<?php if ( $settings['table_button_text'] ) { ?>
							<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'table_button_text' ) ); ?>>
								<?php echo wp_kses_post( $settings['table_button_text'] ); ?>
							</a>
						<?php } ?>
					<?php } ?>
					<?php if ( $settings['table_additional_info'] ) { ?>
						<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'table_additional_info' ) ); ?>>
							<?php echo wp_kses_post( $this->parse_text_editor( $settings['table_additional_info'] ) ); ?>
						</div>
					<?php } ?>
				</div>
			</div>
			<?php if ( 'yes' === $settings['show_ribbon'] && $settings['ribbon_title'] ) { ?>
				<?php
					$classes = [
						'pp-pricing-table-ribbon',
						'pp-pricing-table-ribbon-' . $settings['ribbon_style'],
						'pp-pricing-table-ribbon-' . $settings['ribbon_position'],
					];
					$this->add_render_attribute( 'ribbon', 'class', $classes );
					?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'ribbon' ) ); ?>>
					<div class="pp-pricing-table-ribbon-inner">
						<div class="pp-pricing-table-ribbon-title">
							<?php echo wp_kses_post( $settings['ribbon_title'] ); ?>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
		<?php
	}

	/**
	 * Render pricing table widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			var buttonClasses = 'pp-pricing-table-button elementor-button elementor-size-' + settings.table_button_size + ' elementor-animation-' + settings.button_hover_animation;
		   
			var $i = 1,
				symbols = {
					dollar: '&#36;',
					euro: '&#128;',
					franc: '&#8355;',
					pound: '&#163;',
					ruble: '&#8381;',
					shekel: '&#8362;',
					baht: '&#3647;',
					yen: '&#165;',
					won: '&#8361;',
					guilder: '&fnof;',
					peso: '&#8369;',
					peseta: '&#8359;',
					lira: '&#8356;',
					rupee: '&#8360;',
					indian_rupee: '&#8377;',
					real: 'R$',
					krona: 'kr'
				},
				symbol = '',
				iconHTML = {},
				iconsHTML = {},
				migrated = {},
				iconsMigrated = {},
				tooltipIconHTML = {};

			if ( settings.currency_symbol ) {
				if ( 'custom' !== settings.currency_symbol ) {
					symbol = symbols[ settings.currency_symbol ] || '';
				} else {
					symbol = settings.currency_symbol_custom;
				}
			}
		   
			if ( settings.currency_format == 'raised' ) {
				var table_price = settings.table_price.toString(),
					price = table_price.split( '.' ),
					intvalue = price[0],
					fraction = price[1];
			} else {
				var intvalue = settings.table_price,
					fraction = '';
			}

			function get_tooltip_attributes( item, toolTipKey ) {
				var tooltipContentId = view.$el.data('id') + '-' + item._id;

				view.addRenderAttribute(
					toolTipKey,
					{
						'class': 'pp-pricing-table-tooptip',
						'data-tooltip': 'yes',
						'data-tooltip-position': settings.tooltip_position,
						'data-tooltip-content': '#pp-tooltip-content-' + tooltipContentId,
					}
				);

				if ( settings.tooltip_distance.size ) {
					view.addRenderAttribute( toolTipKey, 'data-tooltip-distance', settings.tooltip_distance.size );
				}

				if ( settings.tooltip_width.size ) {
					view.addRenderAttribute( toolTipKey, 'data-tooltip-width', settings.tooltip_width.size );
				}
			}
		#>
		<div class="pp-pricing-table-container">
			<div class="pp-pricing-table">
				<div class="pp-pricing-table-head">
					<# if ( settings.icon_type != 'none' ) { #>
						<div class="pp-pricing-table-icon-wrap">
							<# if ( settings.icon_type == 'icon' ) { #>
								<# if ( settings.table_icon || settings.select_table_icon ) { #>
									<span class="pp-pricing-table-icon pp-icon">
										<# if ( iconHTML && iconHTML.rendered && ( ! settings.table_icon || migrated ) ) { #>
										{{{ iconHTML.value }}}
										<# } else { #>
											<i class="{{ settings.table_icon }}" aria-hidden="true"></i>
										<# } #>
									</span>
								<# } #>
							<# } else if ( settings.icon_type == 'image' ) { #>
								<span class="pp-pricing-table-icon pp-pricing-table-icon-image">
									<# if ( settings.icon_image.url != '' ) { #>
										<#
										var image = {
											id: settings.icon_image.id,
											url: settings.icon_image.url,
											size: settings.image_size,
											dimension: settings.image_custom_dimension,
											model: view.getEditModel()
										};
										var image_url = elementor.imagesManager.getImageUrl( image );
										#>
										<img src="{{ _.escape( image_url ) }}" />
									<# } #>
								</span>
							<# } #>
						</div>
					<# } #>
					<div class="pp-pricing-table-title-wrap">
						<# if ( settings.table_title ) { #>
							<# var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag ); #>
							<{{{ titleHTMLTag }}} class="pp-pricing-table-title elementor-inline-editing" data-elementor-setting-key="table_title" data-elementor-inline-editing-toolbar="none">
								{{{ settings.table_title }}}
							</{{{ titleHTMLTag }}}>
						<# } #>
						<# if ( settings.table_subtitle ) { #>
							<# var subtitleHTMLTag = elementor.helpers.validateHTMLTag( settings.subtitle_html_tag ); #>
							<{{{ subtitleHTMLTag }}} class="pp-pricing-table-subtitle elementor-inline-editing" data-elementor-setting-key="table_subtitle" data-elementor-inline-editing-toolbar="none">
								{{{ settings.table_subtitle }}}
							</{{{ subtitleHTMLTag }}}>
						<# } #>
					</div>
				</div>
				<div class="pp-pricing-table-price-wrap">
					<div class="pp-pricing-table-price">
						<# if ( settings.discount === 'yes' && settings.table_original_price > 0 ) { #>
							<span class="pp-pricing-table-price-original">
								<# if ( ! _.isEmpty( symbol ) && 'after' == settings.currency_position ) { #>
									{{{ settings.table_original_price + symbol }}}
								<# } else { #>
									{{{ symbol + settings.table_original_price }}}
								<# } #>
							</span>
						<# } #>
						<# if ( ! _.isEmpty( symbol ) && ( 'before' == settings.currency_position || _.isEmpty( settings.currency_position ) ) ) { #>
							<span class="pp-pricing-table-price-prefix">{{{ symbol }}}</span>
						<# } #>
						<span class="pp-pricing-table-price-value">
							<span class="pp-pricing-table-integer-part">
								{{{ intvalue }}}
							</span>
							<# if ( fraction ) { #>
								<span class="pp-pricing-table-after-part">
									{{{ fraction }}}
								</span>
							<# } #>
						</span>
						<# if ( ! _.isEmpty( symbol ) && 'after' == settings.currency_position ) { #>
							<span class="pp-pricing-table-price-prefix">{{{ symbol }}}</span>
						<# } #>
						<# if ( settings.table_duration ) { #>
							<span class="pp-pricing-table-price-duration elementor-inline-editing" data-elementor-setting-key="table_duration" data-elementor-inline-editing-toolbar="none">
								{{{ settings.table_duration }}}
							</span>
						<# } #>
					</div>
				</div>
				<# if ( settings.table_button_position == 'above' ) { #>
					<div class="pp-pricing-table-button-wrap">
						<#
						if ( settings.table_button_text ) {
						var button_text = settings.table_button_text;

						view.addRenderAttribute( 'table_button_text', 'class', buttonClasses );

						view.addInlineEditingAttributes( 'table_button_text' );

						var button_text_html = '<a ' + 'href="' + _.escape( settings.link.url ) + '"' + view.getRenderAttributeString( 'table_button_text' ) + '>' + button_text + '</a>';

						print( button_text_html );
						}
						#>
					</div>
				<# } #>
				<ul class="pp-pricing-table-features">
					<# var i = 1; #>
					<# _.each( settings.table_features, function( item, index ) {
						var  tooltipContentId = view.$el.data('id') + '-' + item._id;

						var featureContentKey = view.getRepeaterSettingKey( 'feature_content_key', 'table_features', index );
						view.addRenderAttribute( featureContentKey, 'class', 'pp-pricing-table-feature-content' );

						var tooltipIconKey = view.getRepeaterSettingKey( 'tooltip_icon_key', 'table_features', index ),
							tooltipContentKey = view.getRepeaterSettingKey( 'tooltip_content', 'hot_spots', index );

						view.addRenderAttribute( tooltipIconKey, 'class', 'pp-pricing-table-tooltip-icon' );

						view.addRenderAttribute(
							tooltipContentKey,
							{
								'class': [ 'pp-tooltip-content', 'pp-tooltip-content-' + tooltipContentId ],
								'id': 'pp-tooltip-content-' + tooltipContentId,
							}
						);

						if ( 'yes' === settings.show_tooltip && item.tooltip_content ) {
							if ( 'text' === settings.tooltip_display_on ) {
								get_tooltip_attributes( item, featureContentKey );
								if ( 'click' === settings.tooltip_trigger ) {
									view.addRenderAttribute( featureContentKey, 'class', 'pp-tooltip-click' );
								}
							} else {
								get_tooltip_attributes( item, tooltipIconKey );
								if ( 'click' === settings.tooltip_trigger ) {
									view.addRenderAttribute( tooltipIconKey, 'class', 'pp-tooltip-click' );
								}
							}
						} #>
						<li class="elementor-repeater-item-{{ item._id }} <# if ( item.exclude == 'yes' ) { #> excluded <# } #>">
							<div {{{ view.getRenderAttributeString( featureContentKey ) }}}>
								<# if ( item.feature_icon || item.select_feature_icon.value ) { #>
									<span class="pp-pricing-table-fature-icon pp-icon">
									<#
										iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.select_feature_icon, { 'aria-hidden': true }, 'i', 'object' );
										iconsMigrated[ index ] = elementor.helpers.isIconMigrated( item, 'select_feature_icon' );
										if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.feature_icon || iconsMigrated[ index ] ) ) { #>
											{{{ iconsHTML[ index ].value }}}
										<# } else { #>
											<i class="{{ item.feature_icon }}" aria-hidden="true"></i>
										<# }
									#>
									</span>
								<# } #>

								<#
									var feature_text = item.feature_text;

									view.addRenderAttribute( 'table_features.' + (i - 1) + '.feature_text', 'class', 'pp-pricing-table-feature-text' );

									view.addInlineEditingAttributes( 'table_features.' + (i - 1) + '.feature_text' );

									var feature_text_html = '<span' + ' ' + view.getRenderAttributeString( 'table_features.' + (i - 1) + '.feature_text' ) + '>' + feature_text + '</span>';

									print( feature_text_html );
								#>

								<# if ( 'yes' === settings.show_tooltip && item.tooltip_content ) { #>
									<#
									if ( 'icon' === settings.tooltip_display_on) {
										tooltipIconHTML = elementor.helpers.renderIcon( view, settings.tooltip_icon, { 'aria-hidden': true }, 'i', 'object' );
										var tooltip_icon_html = '<span' + ' ' + view.getRenderAttributeString( tooltipIconKey ) + '>' + tooltipIconHTML.value + '</span>';

										print( tooltip_icon_html );
									}
									#>
									<div class="pp-tooltip-container">
										<div {{{ view.getRenderAttributeString( tooltipContentKey ) }}}>
											{{ item.tooltip_content }}
										</div>
									</div>
								<# } #>
							</div>
						</li>
					<# i++ } ); #>
				</ul>
				<div class="pp-pricing-table-footer">
					<#
					if ( settings.table_button_position == 'below' ) {
						if ( settings.table_button_text ) {
						var button_text = settings.table_button_text;

						view.addRenderAttribute( 'table_button_text', 'class', buttonClasses );

						view.addInlineEditingAttributes( 'table_button_text' );

						var button_text_html = '<a ' + 'href="' + _.escape( settings.link.url ) + '"' + view.getRenderAttributeString( 'table_button_text' ) + '>' + button_text + '</a>';

						print( button_text_html );
						}
					}

					if ( settings.table_additional_info ) {
					var additional_info_text = settings.table_additional_info;

					view.addRenderAttribute( 'table_additional_info', 'class', 'pp-pricing-table-additional-info' );

					view.addInlineEditingAttributes( 'table_additional_info' );

					var additional_info_text_html = '<div ' + view.getRenderAttributeString( 'table_additional_info' ) + '>' + additional_info_text + '</div>';

					print( additional_info_text_html );
					}
					#>
				</div>
			</div>
			<# if ( settings.show_ribbon == 'yes' && settings.ribbon_title != '' ) { #>
				<div class="pp-pricing-table-ribbon pp-pricing-table-ribbon-{{ settings.ribbon_style }} pp-pricing-table-ribbon-{{ settings.ribbon_position }}">
					<div class="pp-pricing-table-ribbon-inner">
						<div class="pp-pricing-table-ribbon-title">
							<# print( settings.ribbon_title ); #>
						</div>
					</div>
				</div>
			<# } #>
		</div>
		<?php
	}
}
