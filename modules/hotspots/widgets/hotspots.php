<?php
/**
 * PowerPack Image Hotspots Widget
 *
 * @package PPE
 */

namespace PowerpackElementsLite\Modules\Hotspots\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

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
		return parent::get_widget_name( 'Hotspots' );
	}

	/**
	 * Retrieve image hotspots widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Hotspots' );
	}

	/**
	 * Retrieve image hotspots widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Hotspots' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the image hotspots widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Hotspots' );
	}

	protected function is_dynamic_content(): bool {
		return false;
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
		return array(
			'pp-tooltipster',
			'pp-hotspots',
		);
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return [ 'pp-tooltip', 'widget-pp-hotspots' ];
	}

	/**
	 * Register image hotspots widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.1.4
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_image_controls();
		$this->register_content_hotspots_controls();
		$this->register_content_tooltip_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_image_controls();
		$this->register_style_hotspot_controls();
		$this->register_style_tooltip_controls();
	}

	protected function register_content_image_controls() {
		/**
		 * Content Tab: Image
		 */
		$this->start_controls_section(
			'section_image',
			array(
				'label' => esc_html__( 'Image', 'powerpack' ),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => esc_html__( 'Choose Image', 'powerpack' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'label'   => esc_html__( 'Image Size', 'powerpack' ),
				'default' => 'full',
			)
		);

		$this->end_controls_section();
	}

	protected function register_content_hotspots_controls() {
		/**
		 * Content Tab: Hotspots
		 */
		$this->start_controls_section(
			'section_hotspots',
			array(
				'label' => esc_html__( 'Hotspots', 'powerpack' ),
			)
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'hot_spots_tabs' );

		$repeater->start_controls_tab( 'tab_content', array( 'label' => esc_html__( 'General', 'powerpack' ) ) );

			$repeater->add_control(
				'hotspot_admin_label',
				array(
					'label'       => esc_html__( 'Admin Label', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'default'     => '',
				)
			);

			$repeater->add_control(
				'hotspot_type',
				array(
					'label'   => esc_html__( 'Type', 'powerpack' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'icon',
					'options' => array(
						'icon'  => esc_html__( 'Icon', 'powerpack' ),
						'text'  => esc_html__( 'Text', 'powerpack' ),
						'blank' => esc_html__( 'Blank', 'powerpack' ),
					),
				)
			);

			$repeater->add_control(
				'selected_icon',
				array(
					'label'            => esc_html__( 'Icon', 'powerpack' ),
					'type'             => Controls_Manager::ICONS,
					'label_block'      => false,
					'default'          => array(
						'value'   => 'fas fa-plus',
						'library' => 'fa-solid',
					),
					'fa4compatibility' => 'hotspot_icon',
					'skin'             => 'inline',
					'conditions'       => array(
						'terms' => array(
							array(
								'name'     => 'hotspot_type',
								'operator' => '==',
								'value'    => 'icon',
							),
						),
					),
				)
			);

			$repeater->add_control(
				'hotspot_text',
				array(
					'label'       => esc_html__( 'Text', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => false,
					'default'     => '#',
					'conditions'  => array(
						'terms' => array(
							array(
								'name'     => 'hotspot_type',
								'operator' => '==',
								'value'    => 'text',
							),
						),
					),
				)
			);

			$repeater->add_responsive_control(
				'left_position',
				array(
					'label'     => esc_html__( 'Horizontal Position (%)', 'powerpack' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'%' => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 0.1,
						),
					),
					'default'   => [
						'unit' => '%',
						'size' => 20,
					],
					'separator' => 'before',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}%;',
					),
				)
			);

			$repeater->add_responsive_control(
				'top_position',
				array(
					'label'     => esc_html__( 'Vertical Position (%)', 'powerpack' ),
					'type'      => Controls_Manager::SLIDER,
					'range'     => array(
						'%' => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 0.1,
						),
					),
					'default'   => [
						'unit' => '%',
						'size' => 20,
					],
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}%;',
					),
				)
			);

			$repeater->add_control(
				'hotspot_link',
				array(
					'label'       => esc_html__( 'Link', 'powerpack' ),
					'description' => esc_html__( 'Works only when tolltips\' Trigger is set to Hover or if tooltip is disabled.', 'powerpack' ),
					'type'        => Controls_Manager::URL,
					'dynamic'     => array(
						'active' => true,
					),
					'placeholder' => 'https://www.your-link.com',
					'default'     => array(
						'url' => '#',
					),
					'separator'   => 'before',
				)
			);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'tab_position', array( 'label' => esc_html__( 'Tooltip', 'powerpack' ) ) );

			$repeater->add_control(
				'tooltip',
				array(
					'label'        => esc_html__( 'Tooltip', 'powerpack' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => '',
					'label_on'     => esc_html__( 'Show', 'powerpack' ),
					'label_off'    => esc_html__( 'Hide', 'powerpack' ),
					'return_value' => 'yes',
				)
			);

			$repeater->add_control(
				'tooltip_position_local',
				array(
					'label'      => esc_html__( 'Tooltip Position', 'powerpack' ),
					'type'       => Controls_Manager::SELECT,
					'default'    => 'global',
					'options'    => array(
						'global'       => esc_html__( 'Global', 'powerpack' ),
						'top'          => esc_html__( 'Top', 'powerpack' ),
						'bottom'       => esc_html__( 'Bottom', 'powerpack' ),
						'left'         => esc_html__( 'Left', 'powerpack' ),
						'right'        => esc_html__( 'Right', 'powerpack' ),
					),
					'conditions' => array(
						'terms' => array(
							array(
								'name'     => 'tooltip',
								'operator' => '==',
								'value'    => 'yes',
							),
						),
					),
				)
			);

			$repeater->add_control(
				'tooltip_content',
				array(
					'label'      => esc_html__( 'Tooltip Content', 'powerpack' ),
					'type'       => Controls_Manager::WYSIWYG,
					'default'    => esc_html__( 'Tooltip Content', 'powerpack' ),
					'conditions' => array(
						'terms' => array(
							array(
								'name'     => 'tooltip',
								'operator' => '==',
								'value'    => 'yes',
							),
						),
					),
				)
			);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'tab_style', array( 'label' => esc_html__( 'Style', 'powerpack' ) ) );

			$repeater->add_group_control(
				Group_Control_Typography::get_type(),
				array(
					'name'      => 'hotspot_typography',
					'label'     => esc_html__( 'Typography', 'powerpack' ),
					'selector'  => '{{WRAPPER}} {{CURRENT_ITEM}} .pp-hot-spot-inner',
					'condition' => array(
						'hotspot_type' => 'text',
					),
				)
			);

			$repeater->add_control(
				'hotspot_color_single',
				array(
					'label'     => esc_html__( 'Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-hot-spot-wrap, {{WRAPPER}} {{CURRENT_ITEM}} .pp-hot-spot-inner, {{WRAPPER}} {{CURRENT_ITEM}} .pp-hot-spot-inner:before' => 'color: {{VALUE}}',
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-hot-spot-wrap .pp-icon svg' => 'fill: {{VALUE}}',
					),
					'condition' => array(
						'hotspot_type!' => 'blank',
					),
				)
			);

			$repeater->add_control(
				'hotspot_bg_color_single',
				array(
					'label'     => esc_html__( 'Background Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-hot-spot-wrap, {{WRAPPER}} {{CURRENT_ITEM}} .pp-hot-spot-inner, {{WRAPPER}} {{CURRENT_ITEM}} .pp-hot-spot-inner:before' => 'background-color: {{VALUE}}',
					),
				)
			);

			$repeater->add_control(
				'hotspot_border_color_single',
				array(
					'label'     => esc_html__( 'Border Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-hot-spot-wrap' => 'border-color: {{VALUE}}',
					),
				)
			);

			$repeater->add_responsive_control(
				'hotspot_icon_size_single',
				array(
					'label'      => esc_html__( 'Size', 'powerpack' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px', 'em', 'rem', 'custom' ),
					'range'      => array(
						'px' => array(
							'min'  => 6,
							'max'  => 100,
							'step' => 1,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} {{CURRENT_ITEM}} .pp-hot-spot-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
					),
				)
			);

			$repeater->add_control(
				'hotspot_css_id',
				array(
					'label'   => esc_html__( 'CSS ID', 'powerpack' ),
					'type'    => Controls_Manager::TEXT,
					'default' => '',
					'ai'      => [
						'active' => false,
					],
				)
			);
	
			$repeater->add_control(
				'hotspot_css_classes',
				array(
					'label'   => esc_html__( 'CSS Classes', 'powerpack' ),
					'type'    => Controls_Manager::TEXT,
					'default' => '',
					'ai'      => [
						'active' => false,
					],
				)
			);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'hot_spots',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'hotspot_admin_label' => esc_html__( 'Hotspot #1', 'powerpack' ),
						'hotspot_text'        => esc_html__( '1', 'powerpack' ),
						'selected_icon'       => 'fa fa-plus',
						'left_position'       => 20,
						'top_position'        => 30,
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ hotspot_admin_label }}}',
			)
		);

		$this->add_control(
			'hotspot_pulse',
			array(
				'label'        => esc_html__( 'Glow Effect', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->end_controls_section();
	}

	protected function register_content_tooltip_controls() {
		/**
		 * Content Tab: Tooltip Settings
		 */
		$this->start_controls_section(
			'section_tooltip',
			array(
				'label' => esc_html__( 'Tooltip Settings', 'powerpack' ),
			)
		);

		$this->add_control(
			'tooltip_always_open',
			array(
				'label'              => esc_html__( 'Always Open?', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => esc_html__( 'Yes', 'powerpack' ),
				'label_off'          => esc_html__( 'No', 'powerpack' ),
				'return_value'       => 'yes',
			)
		);

		$this->add_control(
			'tooltip_trigger',
			array(
				'label'              => esc_html__( 'Trigger', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'hover',
				'options'            => array(
					'hover' => esc_html__( 'Hover', 'powerpack' ),
					'click' => esc_html__( 'Click', 'powerpack' ),
				),
				'condition' => array(
					'tooltip_always_open!' => 'yes',
				),
			)
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
			)
		);

		$this->add_control(
			'tooltip_position',
			array(
				'label'   => esc_html__( 'Global Position', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => array(
					'top'          => esc_html__( 'Top', 'powerpack' ),
					'bottom'       => esc_html__( 'Bottom', 'powerpack' ),
					'left'         => esc_html__( 'Left', 'powerpack' ),
					'right'        => esc_html__( 'Right', 'powerpack' ),
				),
			)
		);

		$this->add_control(
			'distance',
			array(
				'label'       => esc_html__( 'Distance', 'powerpack' ),
				'description' => esc_html__( 'The distance between the hotspot and the tooltip.', 'powerpack' ),
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
			)
		);

		$this->add_control(
			'tooltip_arrow',
			array(
				'label'              => esc_html__( 'Show Arrow', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => esc_html__( 'Yes', 'powerpack' ),
				'label_off'          => esc_html__( 'No', 'powerpack' ),
				'return_value'       => 'yes',
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
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'tooltip_animation_out',
			array(
				'label'   => esc_html__( 'Animation Out', 'powerpack' ),
				'type'    => Controls_Manager::SELECT2,
				'default' => '',
				'options' => $tooltip_animations,
				'frontend_available' => true,
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
			)
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Hotspots' );

		if ( ! empty( $help_docs ) ) {

			/**
			 * Content Tab: Help Docs
			 *
			 * @since 1.4.8
			 * @access protected
			 */
			$this->start_controls_section(
				'section_help_docs',
				array(
					'label' => esc_html__( 'Help Docs', 'powerpack' ),
				)
			);

			$hd_counter = 1;
			foreach ( $help_docs as $hd_title => $hd_link ) {
				$this->add_control(
					'help_doc_' . $hd_counter,
					array(
						'type'            => Controls_Manager::RAW_HTML,
						'raw'             => sprintf( '%1$s ' . $hd_title . ' %2$s', '<a href="' . $hd_link . '" target="_blank" rel="noopener">', '</a>' ),
						'content_classes' => 'pp-editor-doc-links',
					)
				);

				$hd_counter++;
			}

			$this->end_controls_section();
		}
	}

	protected function register_style_image_controls() {
		/**
		 * Style Tab: Image
		 */
		$this->start_controls_section(
			'section_image_style',
			array(
				'label' => esc_html__( 'Image', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label'      => esc_html__( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 1200,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-hot-spot-image' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'image_align',
			array(
				'label'        => esc_html__( 'Alignment', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'pp-hotspot-img-align%s-',
				'selectors'    => array(
					'{{WRAPPER}} .pp-hot-spot-image' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'image_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-hot-spot-image img',
			)
		);

		$this->add_control(
			'image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-hot-spot-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'image_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-hot-spot-image img',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_hotspot_controls() {
		/**
		 * Style Tab: Hotspot
		 */
		$this->start_controls_section(
			'section_hotspots_style',
			array(
				'label' => esc_html__( 'Hotspot', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'hotspot_icon_size',
			array(
				'label'      => esc_html__( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array( 'size' => '14' ),
				'range'      => array(
					'px' => array(
						'min'  => 6,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-hot-spot-inner' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'hotspots_typography',
				'label'     => esc_html__( 'Typography', 'powerpack' ),
				'selector'  => '{{WRAPPER}} .pp-hot-spot-inner',
			)
		);

		$this->add_control(
			'icon_color_normal',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .pp-hot-spot-inner, {{WRAPPER}} .pp-hot-spot-inner:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-hot-spot-wrap .pp-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'icon_bg_color_normal',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-hot-spot-inner, {{WRAPPER}} .pp-hot-spot-inner:before, {{WRAPPER}} .pp-hotspot-icon-wrap' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icon_border_normal',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-hot-spot-inner',
			)
		);

		$this->add_control(
			'icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-hot-spot-inner, {{WRAPPER}} .pp-hot-spot-inner:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-hot-spot-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'icon_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-hot-spot-inner',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_tooltip_controls() {
		/**
		 * Style Tab: Tooltip
		 */
		$this->start_controls_section(
			'section_tooltips_style',
			array(
				'label' => esc_html__( 'Tooltip', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tooltip_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.pp-tooltip.pp-tooltip-{{ID}} .tooltipster-box' => 'background-color: {{VALUE}};',
					'.pp-tooltip.pp-tooltip-{{ID}}.tooltipster-top .tooltipster-arrow-background' => 'border-top-color: {{VALUE}};',
					'.pp-tooltip.pp-tooltip-{{ID}}.tooltipster-bottom .tooltipster-arrow-background' => 'border-bottom-color: {{VALUE}};',
					'.pp-tooltip.pp-tooltip-{{ID}}.tooltipster-left .tooltipster-arrow-background' => 'border-left-color: {{VALUE}};',
					'.pp-tooltip.pp-tooltip-{{ID}}.tooltipster-right .tooltipster-arrow-background' => 'border-right-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tooltip_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'.pp-tooltip.pp-tooltip-{{ID}} .pp-tooltip-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tooltip_width',
			array(
				'label'     => esc_html__( 'Width', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 100,
						'max'  => 400,
						'step' => 1,
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'tooltip_typography',
				'label'    => esc_html__( 'Typography', 'powerpack' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '.pp-tooltip.pp-tooltip-{{ID}} .pp-tooltip-content',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'tooltip_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '.pp-tooltip.pp-tooltip-{{ID}} .tooltipster-box',
			)
		);

		$this->add_control(
			'tooltip_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'.pp-tooltip.pp-tooltip-{{ID}} .tooltipster-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tooltip_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'.pp-tooltip.pp-tooltip-{{ID}} .tooltipster-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'tooltip_box_shadow',
				'selector' => '.pp-tooltip.pp-tooltip-{{ID}} .tooltipster-box',
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings          = $this->get_settings_for_display();
		$fallback_defaults = array(
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		);

		if ( empty( $settings['image']['url'] ) ) {
			return;
		}

		$tooltip_settings = array(
			'always_open' => $settings['tooltip_always_open'],
			'trigger'     => ( $settings['tooltip_trigger'] ) ? $settings['tooltip_trigger'] : 'hover',
			'size'        => $settings['tooltip_size'],
			'distance'    => ( $settings['distance']['size'] ) ? $settings['distance']['size'] : '',
			'arrow'       => $settings['tooltip_arrow'],
			'animation'   => $settings['tooltip_animation'],
			'zindex'      => $settings['tooltip_zindex'],
			'width'       => ( $settings['tooltip_width']['size'] ) ? $settings['tooltip_width']['size'] : '180',
		);

		$this->add_render_attribute(
			'container',
			[
				'class'                => 'pp-image-hotspots',
				'data-tooltip-options' => wp_json_encode( $tooltip_settings ),
			]
		);
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'container' ) ); ?>>
			<div class="pp-hot-spot-image">
				<?php
				echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings ) );

				foreach ( $settings['hot_spots'] as $index => $item ) :
					$hotspot_tag         = 'span';
					$hotspot_key         = $this->get_repeater_setting_key( 'hotspot', 'hot_spots', $index );
					$tooltip_content_key = $this->get_repeater_setting_key( 'tooltip_content', 'hot_spots', $index );
					$tooltip_content_id  = $this->get_id() . '-' . $item['_id'];
					$hotspot_inner_key   = $this->get_repeater_setting_key( 'hotspot-inner', 'hot_spots', $index );
					$link_key            = $this->get_repeater_setting_key( 'link', 'hot_spots', $index );

					$this->add_render_attribute(
						$hotspot_key,
						'class',
						array(
							'pp-hot-spot-wrap',
							'elementor-repeater-item-' . esc_attr( $item['_id'] ),
						)
					);

					if ( 'yes' === $item['tooltip'] && $item['tooltip_content'] ) {
						if ( 'global' !== $item['tooltip_position_local'] ) {
							$tooltip_position = $item['tooltip_position_local'];
						} else {
							$tooltip_position = $settings['tooltip_position'];
						}

						$this->add_render_attribute(
							$tooltip_content_key,
							array(
								'class' => [ 'pp-tooltip-content', 'pp-tooltip-content-' . $this->get_id() ],
								'id'    => 'pp-tooltip-content-' . $tooltip_content_id,
							)
						);

						$this->add_render_attribute(
							$hotspot_key,
							array(
								'class'                 => 'pp-hot-spot-tooptip',
								'data-tooltip'          => 'yes',
								'data-tooltip-position' => $tooltip_position,
								'data-tooltip-content'  => '#pp-tooltip-content-' . $tooltip_content_id,
							)
						);
					}

					if ( $item['hotspot_css_id'] ) {
						$this->add_render_attribute( $hotspot_key, 'id', $item['hotspot_css_id'] );
					}

					if ( $item['hotspot_css_classes'] ) {
						$this->add_render_attribute( $hotspot_key, 'class', $item['hotspot_css_classes'] );
					}

					$this->add_render_attribute( $hotspot_inner_key, 'class', 'pp-hot-spot-inner' );

					if ( 'yes' === $settings['hotspot_pulse'] ) {
						$this->add_render_attribute( $hotspot_inner_key, 'class', 'hotspot-animation' );
					}

					$migration_allowed = Icons_Manager::is_migration_allowed();

					// add old default
					if ( ! isset( $item['hotspot_icon'] ) && ! $migration_allowed ) {
						$item['hotspot_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-plus';
					}

					$migrated = isset( $item['__fa4_migrated']['selected_icon'] );
					$is_new   = ! isset( $item['hotspot_icon'] ) && $migration_allowed;

					if ( $item['hotspot_link']['url'] ) {
						if ( 'yes' !== $item['tooltip'] || ( 'yes' === $item['tooltip'] && 'hover' === $settings['tooltip_trigger'] ) ) {

							$hotspot_tag = 'a';

							$this->add_link_attributes( $hotspot_key, $item['hotspot_link'] );

						}
					}
					?>
					<<?php echo esc_html( $hotspot_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( $hotspot_key ) ); ?>>
						<span <?php echo wp_kses_post( $this->get_render_attribute_string( $hotspot_inner_key ) ); ?>>
							<span class="pp-hotspot-icon-wrap">
							<?php
							if ( 'icon' === $item['hotspot_type'] ) {
								if ( ! empty( $item['hotspot_icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) ) {
									?>
									<span class="pp-hotspot-icon pp-icon">
										<?php
										if ( $is_new || $migrated ) {
											Icons_Manager::render_icon( $item['selected_icon'], array( 'aria-hidden' => 'true' ) );
										} else {
											?>
											<i class="<?php echo esc_attr( $item['hotspot_icon'] ); ?>" aria-hidden="true"></i>
											<?php
										}
										?>
									</span>
									<?php
								}
							} elseif ( 'text' === $item['hotspot_type'] ) { ?>
								<span class="pp-hotspot-icon-wrap">
									<span class="pp-hotspot-text">
										<?php echo esc_attr( $item['hotspot_text'] ); ?>
									</span>
								</span>
								<?php
							}
							?>
							</span>
						</span>
					</<?php echo esc_html( $hotspot_tag ); ?>>
					<?php if ( 'yes' === $item['tooltip'] && $item['tooltip_content'] ) { ?>
						<div class="pp-tooltip-container">
							<div <?php echo wp_kses_post( $this->get_render_attribute_string( $tooltip_content_key ) ); ?>>
								<?php echo wp_kses_post( $item['tooltip_content'] ); ?>
							</div>
						</div>
					<?php }
				endforeach;
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render image hotspots widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.0.3
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			var i = 1;

			var tooltipSettings = {
				'always_open': settings.tooltip_always_open,
				'trigger':     ( settings.tooltip_trigger !== '' ) ? settings.tooltip_trigger : 'hover',
				'size':        settings.tooltip_size,
				'distance':    ( settings.distance.size !== '' ) ? settings.distance.size : '',
				'arrow':       settings.tooltip_arrow,
				'animation':   settings.tooltip_animation,
				'zindex':      settings.tooltip_zindex,
				'width':       ( settings.tooltip_width.size !== '' ) ? settings.tooltip_width.size : '',
			};

			view.addRenderAttribute(
				'container',
				{
					'class': 'pp-image-hotspots',
					'data-tooltip-options': JSON.stringify( tooltipSettings )
				}
			);
		#>
		<div {{{ view.getRenderAttributeString( 'container' ) }}}>
			<div class="pp-hot-spot-image">
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
					<img src="{{ _.escape( image_url ) }}" />
				<# } #>
				<# _.each( settings.hot_spots, function( item, index ) {
				   
					var hotspotTag 			= 'span',
						tooltipContentId    = view.$el.data('id') + '-' + item._id;
						hotspotAnimation	= ( settings.hotspot_pulse == 'yes' ) ? 'hotspot-animation' : '',
						ttPosition			= '',
						iconsHTML			= {},
						migrated			= {};

					var hotspotKey 			= view.getRepeaterSettingKey( 'hotspot', 'hot_spots', index ),
						tooltipContentKey   = view.getRepeaterSettingKey( 'tooltip_content', 'hot_spots', index );

					view.addRenderAttribute(
						hotspotKey,
						{
							'class': [
								'pp-hot-spot-wrap',
								'elementor-repeater-item-' + item._id
							],
						}
					);

					view.addRenderAttribute(
						tooltipContentKey,
						{
							'class': [ 'pp-tooltip-content', 'pp-tooltip-content-' + tooltipContentId ],
							'id': 'pp-tooltip-content-' + tooltipContentId,
						}
					);

					if ( item.tooltip_position_local != 'global' ) {
						ttPosition = item.tooltip_position_local;
					} else {
						ttPosition = settings.tooltip_position;
					}

					if ( item.tooltip == 'yes' ) {
						view.addRenderAttribute(
							hotspotKey,
							{
								'class': 'pp-hot-spot-tooptip',
								'data-tooltip': 'yes',
								'data-tooltip-position': ttPosition,
								'data-tooltip-content': '#pp-tooltip-content-' + tooltipContentId,
							}
						);
					}
					#>
					<#
						if ( item.hotspot_link.url ) {
							if ( item.tooltip != 'yes' || ( item.tooltip == 'yes' && settings.tooltip_trigger == 'hover' ) ) {
								hotspotTag = 'a';

								if ( item.hotspot_link.is_external ) {
									view.addRenderAttribute( hotspotKey, 'target', '_blank' );
								}

								if ( item.hotspot_link.nofollow ) {
									view.addRenderAttribute( hotspotKey, 'rel', 'nofollow' );
								}
							}
						}
					#>
					<{{ hotspotTag }} {{{ view.getRenderAttributeString( hotspotKey ) }}}>
						<span class="pp-hot-spot-inner {{ hotspotAnimation }}">
							<# if ( item.hotspot_type == 'icon' ) { #>
								<span class="pp-hotspot-icon-wrap">
									<span class="pp-hotspot-icon pp-icon">
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
									<span class="pp-hotspot-icon">{{ item.hotspot_text }}</span>
								</span>
							<# } #>
						</span>
					</{{ hotspotTag }}>
					<# if ( 'yes' === item.tooltip && item.tooltip_content ) { #>
						<div class="pp-tooltip-container">
							<div {{{ view.getRenderAttributeString( tooltipContentKey ) }}}>{{{ item.tooltip_content }}}</div>
						</div>
					<# } #>
				<# i++ } ); #>
			</div>
		</div>
		<?php
	}
}
