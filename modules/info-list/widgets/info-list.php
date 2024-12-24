<?php
namespace PowerpackElementsLite\Modules\InfoList\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Info List Widget
 */
class Info_List extends Powerpack_Widget {

	/**
	 * Retrieve info list widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Info_List' );
	}

	/**
	 * Retrieve info list widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Info_List' );
	}

	/**
	 * Retrieve info list widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Info_List' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 1.4.13.1
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Info_List' );
	}

	protected function is_dynamic_content(): bool {
		return false;
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
		return [ 'widget-pp-info-list' ];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! PP_Helper::is_feature_active( 'e_optimized_markup' );
	}

	/**
	 * Register info list widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.3.2
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_list_items_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_list_controls();
		$this->register_style_connector_controls();
		$this->register_style_icon_controls();
		$this->register_style_title_controls();
		$this->register_style_button_controls();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	CONTENT TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_content_list_items_controls() {
		/**
		 * Content Tab: List Items
		 */
		$this->start_controls_section(
			'section_list',
			array(
				'label' => esc_html__( 'List Items', 'powerpack' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'       => esc_html__( 'Title', 'powerpack' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'List Item #1', 'powerpack' ),
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label'       => esc_html__( 'Description', 'powerpack' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => esc_html__( 'List Item Description', 'powerpack' ),
			)
		);

		$repeater->add_control(
			'pp_icon_type',
			array(
				'label'       => esc_html__( 'Icon Type', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'none'  => array(
						'title' => esc_html__( 'None', 'powerpack' ),
						'icon'  => 'eicon-ban',
					),
					'icon'  => array(
						'title' => esc_html__( 'Icon', 'powerpack' ),
						'icon'  => 'eicon-star',
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'powerpack' ),
						'icon'  => 'eicon-image-bold',
					),
					'text'  => array(
						'title' => esc_html__( 'Text', 'powerpack' ),
						'icon'  => 'eicon-font',
					),
				),
				'default'     => 'icon',
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'            => esc_html__( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'default'          => array(
					'value'   => 'fas fa-check',
					'library' => 'fa-solid',
				),
				'fa4compatibility' => 'list_icon',
				'condition'        => array(
					'pp_icon_type' => 'icon',
				),
			)
		);

		$repeater->add_control(
			'list_image',
			array(
				'label'       => esc_html__( 'Image', 'powerpack' ),
				'label_block' => true,
				'type'        => Controls_Manager::MEDIA,
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition'   => array(
					'pp_icon_type' => 'image',
				),
			)
		);

		$repeater->add_control(
			'icon_text',
			array(
				'label'       => esc_html__( 'Icon Text', 'powerpack' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '1', 'powerpack' ),
				'condition'   => array(
					'pp_icon_type' => 'text',
				),
			)
		);

		$repeater->add_control(
			'link_type',
			array(
				'label'   => esc_html__( 'Link Type', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'none'   => esc_html__( 'None', 'powerpack' ),
					'box'    => esc_html__( 'Box', 'powerpack' ),
					'title'  => esc_html__( 'Title', 'powerpack' ),
					'button' => esc_html__( 'Button', 'powerpack' ),
				),
				'default' => 'none',
			)
		);

		$repeater->add_control(
			'button_text',
			array(
				'label'     => esc_html__( 'Button Text', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => esc_html__( 'Get Started', 'powerpack' ),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$repeater->add_control(
			'selected_icon',
			array(
				'label'            => esc_html__( 'Button Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'fa4compatibility' => 'button_icon',
				'condition'        => array(
					'link_type' => 'button',
				),
			)
		);

		$repeater->add_control(
			'button_icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after',
				'options'   => array(
					'after'  => esc_html__( 'After', 'powerpack' ),
					'before' => esc_html__( 'Before', 'powerpack' ),
				),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => esc_html__( 'Link', 'powerpack' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
				'placeholder' => esc_html__( 'http://your-link.com', 'powerpack' ),
				'default'     => array(
					'url' => '#',
				),
				'conditions'  => array(
					'terms' => array(
						array(
							'name'     => 'link_type',
							'operator' => '!=',
							'value'    => 'none',
						),
					),
				),
			)
		);

		$this->add_control(
			'list_items',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'text' => esc_html__( 'List Item #1', 'powerpack' ),
						'icon' => esc_html__( 'fa fa-check', 'powerpack' ),
					),
					array(
						'text' => esc_html__( 'List Item #2', 'powerpack' ),
						'icon' => esc_html__( 'fa fa-check', 'powerpack' ),
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.,
				'label'     => esc_html__( 'Image Size', 'powerpack' ),
				'default'   => 'full',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => esc_html__( 'Title HTML Tag', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'div',
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
			'connector',
			array(
				'label'        => esc_html__( 'Connector', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'corner_lines',
			array(
				'label'        => esc_html__( 'Hide Corner Lines', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'connector' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Info_List' );

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

	/*-----------------------------------------------------------------------------------*/
	/*	STYLE TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_style_list_controls() {
		/**
		 * Style Tab: List
		 */
		$this->start_controls_section(
			'section_list_style',
			array(
				'label' => esc_html__( 'List', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'items_spacing',
			array(
				'label'      => esc_html__( 'Items Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 10,
				),
				'range'      => array(
					'px' => array(
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.pp-info-list-icon-left .pp-info-list-item:not(:last-child) .pp-info-list-item-inner, {{WRAPPER}}.pp-info-list-icon-right .pp-info-list-item:not(:last-child) .pp-info-list-item-inner' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-info-list-icon-top .pp-info-list-item .pp-info-list-item-inner' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2);',
					'{{WRAPPER}}.pp-info-list-icon-top .pp-list-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2);',

					'(tablet){{WRAPPER}}.pp-info-list-stack-tablet.pp-info-list-icon-top .pp-info-list-item .pp-info-list-item-inner' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-left: 0; margin-right: 0;',
					'(tablet){{WRAPPER}}.pp-info-list-stack-tablet.pp-info-list-icon-top .pp-list-items' => 'margin-right: 0; margin-left: 0;',

					'(mobile){{WRAPPER}}.pp-info-list-stack-mobile.pp-info-list-icon-top .pp-info-list-item .pp-info-list-item-inner' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-left: 0; margin-right: 0;',
					'(mobile){{WRAPPER}}.pp-info-list-stack-mobile.pp-info-list-icon-top .pp-list-items' => 'margin-right: 0; margin-left: 0;',
				),
			)
		);

		$this->add_control(
			'icon_position',
			array(
				'label'        => esc_html__( 'Position', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'toggle'       => false,
				'default'      => 'left',
				'options'      => array(
					'left'  => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'top'   => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'right' => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'pp-info-list-icon-',
			)
		);

		$this->add_control(
			'responsive_breakpoint',
			array(
				'label'        => esc_html__( 'Responsive Breakpoint', 'powerpack' ),
				'type'         => Controls_Manager::SELECT,
				'label_block'  => false,
				'default'      => 'mobile',
				'options'      => array(
					''       => esc_html__( 'None', 'powerpack' ),
					'tablet' => esc_html__( 'Tablet', 'powerpack' ),
					'mobile' => esc_html__( 'Mobile', 'powerpack' ),
				),
				'prefix_class' => 'pp-info-list-stack-',
				'condition'    => array(
					'icon_position' => 'top',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_connector_controls() {
		/**
		 * Style Tab: Connector
		 */
		$this->start_controls_section(
			'section_connector_style',
			array(
				'label'     => esc_html__( 'Connector', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'connector' => 'yes',
				),
			)
		);

		$this->add_control(
			'connector_color',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-info-list-connector .pp-infolist-icon-wrapper:before, {{WRAPPER}} .pp-info-list-connector .pp-infolist-icon-wrapper:after' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'connector' => 'yes',
				),
			)
		);

		$this->add_control(
			'connector_style',
			array(
				'label'     => esc_html__( 'Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'powerpack' ),
					'double' => esc_html__( 'Double', 'powerpack' ),
					'dotted' => esc_html__( 'Dotted', 'powerpack' ),
					'dashed' => esc_html__( 'Dashed', 'powerpack' ),
				),
				'default'   => 'solid',
				'selectors' => array(
					'{{WRAPPER}}.pp-info-list-icon-left .pp-info-list-connector .pp-infolist-icon-wrapper:before, {{WRAPPER}}.pp-info-list-icon-left .pp-info-list-connector .pp-infolist-icon-wrapper:after' => 'border-right-style: {{VALUE}};',
					'{{WRAPPER}}.pp-info-list-icon-right .pp-info-list-connector .pp-infolist-icon-wrapper:before, {{WRAPPER}}.pp-info-list-icon-right .pp-info-list-connector .pp-infolist-icon-wrapper:after' => 'border-left-style: {{VALUE}};',
					'{{WRAPPER}}.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:before, {{WRAPPER}}.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:after' => 'border-top-style: {{VALUE}};',

					'(tablet){{WRAPPER}}.pp-info-list-stack-tablet.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:before, {{WRAPPER}}.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:after' => 'border-right-style: {{VALUE}};',

					'(mobile){{WRAPPER}}.pp-info-list-stack-mobile.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:before, {{WRAPPER}}.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:after' => 'border-right-style: {{VALUE}};',
				),
				'condition' => array(
					'connector' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'connector_width',
			array(
				'label'      => esc_html__( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 1,
				),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 20,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.pp-info-list-icon-left .pp-info-list-connector .pp-infolist-icon-wrapper:before, {{WRAPPER}}.pp-info-list-icon-left .pp-info-list-connector .pp-infolist-icon-wrapper:after' => 'border-right-width: {{SIZE}}px;',
					'{{WRAPPER}}.pp-info-list-icon-right .pp-info-list-connector .pp-infolist-icon-wrapper:before, {{WRAPPER}}.pp-info-list-icon-right .pp-infolist-icon-wrapper:after' => 'border-left-width: {{SIZE}}px;',
					'{{WRAPPER}}.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:before, {{WRAPPER}}.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:after' => 'border-top-width: {{SIZE}}px;',

					'(tablet){{WRAPPER}}.pp-info-list-stack-tablet.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:before, {{WRAPPER}}.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:after' => 'border-right-width: {{SIZE}}px;',

					'(mobile){{WRAPPER}}.pp-info-list-stack-mobile.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:before, {{WRAPPER}}.pp-info-list-icon-top .pp-info-list-connector .pp-infolist-icon-wrapper:after' => 'border-right-width: {{SIZE}}px;',
				),
				'condition'  => array(
					'connector' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_icon_controls() {
		/**
		 * Style Tab: Icon
		 */
		$this->start_controls_section(
			'section_icon_style',
			array(
				'label' => esc_html__( 'Icon', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'icon_vertical_align',
			array(
				'label'                => esc_html__( 'Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'toggle'               => false,
				'default'              => 'middle',
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'prefix_class'         => 'pp-info-list-icon-vertical-',
				'condition'            => array(
					'icon_position' => array( 'left', 'right' ),
				),
			)
		);

		$this->add_control(
			'icon_horizontal_align',
			array(
				'label'                => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'toggle'               => false,
				'options'              => array(
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
				'default'              => 'center',
				'selectors_dictionary' => array(
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				),
				'prefix_class'         => 'pp-info-list-icon-horizontal-',
				'condition'            => array(
					'icon_position' => 'top',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_icon_normal',
			array(
				'label' => esc_html__( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items .pp-info-list-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-list-items .pp-info-list-icon svg' => 'fill: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
			)
		);

		$this->add_control(
			'icon_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 14,
				),
				'range'      => array(
					'px' => array(
						'min' => 6,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-list-items .pp-info-list-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-list-items .pp-info-list-image img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_box_size',
			array(
				'label'      => esc_html__( 'Box Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 14,
				),
				'range'      => array(
					'px' => array(
						'min' => 6,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-infolist-icon-wrapper' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',

					'{{WRAPPER}}.pp-info-list-icon-left .pp-info-list-container .pp-infolist-icon-wrapper:before' => 'left: calc(({{SIZE}}px/2) - ({{connector_width.SIZE}}px/2)); bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-info-list-icon-left .pp-info-list-container .pp-infolist-icon-wrapper:after' => 'left: calc(({{SIZE}}px/2) - ({{connector_width.SIZE}}px/2)); top: {{SIZE}}{{UNIT}};',

					'{{WRAPPER}}.pp-info-list-icon-right .pp-info-list-container .pp-infolist-icon-wrapper:before' => 'right: calc(({{SIZE}}px/2) - ({{connector_width.SIZE}}px/2)); bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-info-list-icon-right .pp-info-list-container .pp-infolist-icon-wrapper:after' => 'right: calc(({{SIZE}}px/2) - ({{connector_width.SIZE}}px/2)); top: {{SIZE}}{{UNIT}};',

					'{{WRAPPER}}.pp-info-list-icon-top .pp-info-list-container .pp-infolist-icon-wrapper:before' => 'top: calc(({{SIZE}}px/2) - ({{connector_width.SIZE}}px/2)); right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-info-list-icon-top .pp-info-list-container .pp-infolist-icon-wrapper:after' => 'top: calc(({{SIZE}}px/2) - ({{connector_width.SIZE}}px/2)); left: {{SIZE}}{{UNIT}};',

					'(tablet){{WRAPPER}}.pp-info-list-stack-tablet.pp-info-list-icon-top .pp-info-list-container .pp-infolist-icon-wrapper:before' => 'left: calc(({{SIZE}}px/2) - ({{connector_width.SIZE}}px/2)); bottom: {{SIZE}}{{UNIT}}; right: auto; top: auto;',
					'(tablet){{WRAPPER}}.pp-info-list-stack-tablet.pp-info-list-icon-top .pp-info-list-container .pp-infolist-icon-wrapper:after' => 'left: calc(({{SIZE}}px/2) - ({{connector_width.SIZE}}px/2)); top: {{SIZE}}{{UNIT}};',

					'(mobile){{WRAPPER}}.pp-info-list-stack-mobile.pp-info-list-icon-top .pp-info-list-container .pp-infolist-icon-wrapper:before' => 'left: calc(({{SIZE}}px/2) - ({{connector_width.SIZE}}px/2)); bottom: {{SIZE}}{{UNIT}}; right: auto; top: auto;',
					'(mobile){{WRAPPER}}.pp-info-list-stack-mobile.pp-info-list-icon-top .pp-info-list-container .pp-infolist-icon-wrapper:after' => 'left: calc(({{SIZE}}px/2) - ({{connector_width.SIZE}}px/2)); top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 8,
				),
				'range'      => array(
					'px' => array(
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}}.pp-info-list-icon-left .pp-infolist-icon-wrapper' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-info-list-icon-right .pp-infolist-icon-wrapper' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-info-list-icon-top .pp-infolist-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',

					'(tablet){{WRAPPER}}.pp-info-list-stack-tablet.pp-info-list-icon-top .pp-infolist-icon-wrapper' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: 0;',

					'(mobile){{WRAPPER}}.pp-info-list-stack-mobile.pp-info-list-icon-top .pp-infolist-icon-wrapper' => 'margin-right: {{SIZE}}{{UNIT}}; margin-bottom: 0;',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'icon_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper',
			)
		);

		$this->add_responsive_control(
			'icon_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper, {{WRAPPER}} .pp-list-items .pp-info-list-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_icon_hover',
			array(
				'label' => esc_html__( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'icon_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper:hover .pp-info-list-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper:hover .pp-info-list-icon svg' => 'fill: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
			)
		);

		$this->add_control(
			'icon_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper:hover' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-list-items .pp-infolist-icon-wrapper:hover' => 'border-color: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
			)
		);

		$this->add_control(
			'icon_hover_animation',
			array(
				'label' => esc_html__( 'Animation', 'powerpack' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'icon_number_heading',
			array(
				'label'     => esc_html__( 'Icon Type: Number', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'icon_number_typography',
				'label'    => esc_html__( 'Typography', 'powerpack' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .pp-list-items .pp-info-list-number',
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_title_controls() {
		/**
		 * Style Tab: Title
		 */
		$this->start_controls_section(
			'section_content_style',
			array(
				'label' => esc_html__( 'Content', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'content_align',
			array(
				'label'     => esc_html__( 'Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'    => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-text-align-right',
					),
					'justify' => array(
						'title' => esc_html__( 'Justified', 'powerpack' ),
						'icon'  => 'eicon-text-align-justify',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-infolist-content-wrapper' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-infolist-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'show_separator',
			[
				'label'     => esc_html__( 'Separator', 'powerpack' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'powerpack' ),
				'label_on'  => esc_html__( 'Show', 'powerpack' ),
				'default'   => '',
				'separator' => 'before',
				'condition' => [
					'icon_position!' => 'top',
				],
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e1e8ed',
				'selectors' => [
					'{{WRAPPER}} .pp-info-list-item:not(:last-child) .pp-infolist-content-wrapper' => 'border-bottom-color: {{VALUE}}',
				],
				'condition' => [
					'icon_position!'  => 'top',
					'show_separator!' => '',
				],
			]
		);

		$this->add_control(
			'separator_style',
			array(
				'label'     => esc_html__( 'Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'powerpack' ),
					'double' => esc_html__( 'Double', 'powerpack' ),
					'dotted' => esc_html__( 'Dotted', 'powerpack' ),
					'dashed' => esc_html__( 'Dashed', 'powerpack' ),
				),
				'default'   => 'solid',
				'condition' => array(
					'icon_position!'  => 'top',
					'show_separator!' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-info-list-item:not(:last-child) .pp-infolist-content-wrapper' => 'border-bottom-style: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'separator_size',
			[
				'label'      => esc_html__( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 1,
				),
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition'  => [
					'icon_position!'  => 'top',
					'show_separator!' => '',
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-info-list-item:not(:last-child) .pp-infolist-content-wrapper' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'title_heading',
			array(
				'label'     => esc_html__( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-info-list-title' => 'color: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Typography', 'powerpack' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .pp-info-list-title',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'      => 'title_text_shadow',
				'label'     => esc_html__( 'Text Shadow', 'powerpack' ),
				'selector'  => '{{WRAPPER}} .pp-info-list-title',
			)
		);

		$this->add_responsive_control(
			'title_margin',
			array(
				'label'      => esc_html__( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-info-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'description_heading',
			array(
				'label'     => esc_html__( 'Description', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'description_color',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-info-list-description' => 'color: {{VALUE}};',
				),
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography',
				'label'    => esc_html__( 'Typography', 'powerpack' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .pp-info-list-description',
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_button_controls() {
		/**
		 * Style Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_box_button_style',
			array(
				'label' => esc_html__( 'Button', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'   => esc_html__( 'Size', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sm',
				'options' => array(
					'xs' => esc_html__( 'Extra Small', 'powerpack' ),
					'sm' => esc_html__( 'Small', 'powerpack' ),
					'md' => esc_html__( 'Medium', 'powerpack' ),
					'lg' => esc_html__( 'Large', 'powerpack' ),
					'xl' => esc_html__( 'Extra Large', 'powerpack' ),
				),
			)
		);

		$this->add_responsive_control(
			'button_margin',
			array(
				'label'      => esc_html__( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					),
					'%'  => array(
						'min'  => 0,
						'max'  => 30,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-info-list-button' => 'margin-top: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label' => esc_html__( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-info-list-button' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_text_color_normal',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-info-list-button'     => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-info-list-button svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_normal',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-info-list-button',
			)
		);

		$this->add_responsive_control(
			'button_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-info-list-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'powerpack' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .pp-info-list-button',
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-info-list-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .pp-info-list-button',
			)
		);

		$this->add_control(
			'info_box_button_icon_heading',
			array(
				'label'     => esc_html__( 'Button Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'button_icon_margin',
			array(
				'label'       => esc_html__( 'Margin', 'powerpack' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', 'em', 'rem', 'custom' ),
				'placeholder' => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-info-list-button .pp-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label' => esc_html__( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-info-list-button:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-info-list-button:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-info-list-button:hover' => 'border-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'button_animation',
			array(
				'label' => esc_html__( 'Animation', 'powerpack' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .pp-info-list-button:hover',
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Render info list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			array(
				'info-list'        => array(
					'class' => array(
						'pp-info-list-container',
						'pp-list-container',
					),
				),
				'info-list-items'  => array(
					'class' => 'pp-list-items',
				),
				'list-item'        => array(
					'class' => 'pp-info-list-item',
				),
				'icon'             => array(
					'class' => array( 'pp-info-list-icon', 'pp-icon' ),
				),
				'info-list-button' => array(
					'class' => array(
						'pp-info-list-button',
						'elementor-button',
						'elementor-size-' . $settings['button_size'],
					),
				),
			)
		);

		if ( 'yes' === $settings['connector'] ) {
			$this->add_render_attribute( 'info-list', 'class', 'pp-info-list-connector' );
			if ( 'yes' === $settings['corner_lines'] ) {
				$this->add_render_attribute( 'info-list', 'class', 'pp-info-list-corners-hide' );
			}
		}

		if ( $settings['button_animation'] ) {
			$this->add_render_attribute( 'info-list-button', 'class', 'elementor-animation-' . $settings['button_animation'] );
		}

		$i = 1;
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'info-list' ) ); ?>>
			<ul <?php echo wp_kses_post( $this->get_render_attribute_string( 'info-list-items' ) ); ?>>
				<?php foreach ( $settings['list_items'] as $index => $item ) : ?>
					<?php if ( $item['text'] || $item['description'] ) { ?>
						<li <?php echo wp_kses_post( $this->get_render_attribute_string( 'list-item' ) ); ?>>
							<?php
								$text_key = $this->get_repeater_setting_key( 'text', 'list_items', $index );
								$this->add_render_attribute( $text_key, 'class', 'pp-info-list-title' );
								$this->add_inline_editing_attributes( $text_key, 'none' );

								$description_key = $this->get_repeater_setting_key( 'description', 'list_items', $index );
								$this->add_render_attribute( $description_key, 'class', 'pp-info-list-description' );
								$this->add_inline_editing_attributes( $description_key, 'basic' );

								$button_key = $this->get_repeater_setting_key( 'button-wrap', 'list_items', $index );
								$this->add_render_attribute( $button_key, 'class', 'pp-info-list-button-wrapper pp-info-list-button-icon-' . $item['button_icon_position'] );

								if ( ! empty( $item['link']['url'] ) ) {
									$link_key = 'link_' . $i;

									$this->add_link_attributes( $link_key, $item['link'] );
								}
							?>
							<?php if ( ! empty( $item['link']['url'] ) && 'box' === $item['link_type'] ) { ?>
								<a <?php echo wp_kses_post( $this->get_render_attribute_string( $link_key ) ); ?>>
							<?php }  ?>
								<div class="pp-info-list-item-inner">
									<?php $this->render_infolist_icon( $item, $i ); ?>
									<div class="pp-infolist-content-wrapper">
										<?php
										if ( $item['text'] ) {
											$title_tag = PP_Helper::validate_html_tag( $settings['title_html_tag'] );
											?>
											<<?php echo esc_html( $title_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( $text_key ) ); ?>>
											<?php if ( ! empty( $item['link']['url'] ) && 'title' === $item['link_type'] ) { ?>
												<a <?php echo wp_kses_post( $this->get_render_attribute_string( $link_key ) ); ?>>
											<?php } ?>
												<?php echo wp_kses_post( $item['text'] ); ?>
											<?php if ( ! empty( $item['link']['url'] ) && 'title' === $item['link_type'] ) { ?>
												</a>
											<?php } ?>
											</<?php echo esc_html( $title_tag ); ?>>
										<?php } ?>
										<?php
										if ( $item['description'] ) {
											?>
											<div <?php echo wp_kses_post( $this->get_render_attribute_string( $description_key ) ); ?>>
												<?php echo $this->parse_text_editor( $item['description'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
											</div>
											<?php
										}
										?>
										<?php if ( 'button' === $item['link_type'] && ! empty( $item['link']['url'] ) ) { ?>
											<div <?php echo wp_kses_post( $this->get_render_attribute_string( $button_key ) ); ?>>
												<a <?php echo wp_kses_post( $this->get_render_attribute_string( $link_key ) ); ?>>
													<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'info-list-button' ) ); ?>>
														<?php $this->render_infolist_button_icon( $item ); ?>

														<?php if ( ! empty( $item['button_text'] ) ) { ?>
															<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'button_text' ) ); ?>>
																<?php echo wp_kses_post( $item['button_text'] ); ?>
															</span>
														<?php } ?>
													</div>
												</a>
											</div>
										<?php } ?>
									</div>
								</div>
							<?php if ( ! empty( $item['link']['url'] ) && 'box' === $item['link_type'] ) { ?>
								</a>
							<?php } ?>
						</li>
					<?php } ?>
					<?php
					$i++;
				endforeach;
				?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Render info-box carousel icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_infolist_button_icon( $item ) {
		$settings = $this->get_settings_for_display();

		$migration_allowed = Icons_Manager::is_migration_allowed();

		// add old default
		if ( ! isset( $item['button_icon'] ) && ! $migration_allowed ) {
			$item['button_icon'] = '';
		}

		$migrated = isset( $item['__fa4_migrated']['icon'] );
		$is_new   = empty( $item['button_icon'] ) && $migration_allowed;

		if ( ! empty( $item['button_icon'] ) || ( ! empty( $item['selected_icon']['value'] ) && $is_new ) ) {
			?>
			<span class="pp-button-icon pp-icon">
				<?php
				if ( $is_new || $migrated ) {
					Icons_Manager::render_icon( $item['selected_icon'], array( 'aria-hidden' => 'true' ) );
				} else {
					?>
					<i class="<?php echo esc_attr( $item['button_icon'] ); ?>" aria-hidden="true"></i>
					<?php
				}
				?>
			</span>
			<?php
		}
	}

	/**
	 * Render info-box carousel icon output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_infolist_icon( $item, $i ) {
		$settings = $this->get_settings_for_display();

		$fallback_defaults = array(
			'fa fa-check',
			'fa fa-times',
			'fa fa-dot-circle-o',
		);

		$migration_allowed = Icons_Manager::is_migration_allowed();

		// add old default
		if ( ! isset( $item['list_icon'] ) && ! $migration_allowed ) {
			$item['list_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
		}

		$migrated = isset( $item['__fa4_migrated']['icon'] );
		$is_new   = empty( $item['list_icon'] ) && $migration_allowed;

		if ( 'none' !== $item['pp_icon_type'] ) {
			$icon_wrap_key = $this->get_repeater_setting_key( 'icon_wrap', 'list_items', $i );
			$icon_key      = $this->get_repeater_setting_key( 'icon', 'list_items', $i );

			if ( '' !== $settings['icon_hover_animation'] ) {
				$icon_animation = 'elementor-animation-' . $settings['icon_hover_animation'];
			} else {
				$icon_animation = '';
			}

			$this->add_render_attribute( $icon_wrap_key, 'class', 'pp-infolist-icon-wrapper' );
			$this->add_render_attribute(
				$icon_key,
				'class',
				array(
					'pp-info-list-icon',
					'pp-icon',
					esc_attr( $icon_animation ),
				)
			);
			?>
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( $icon_wrap_key ) ); ?>>
				<?php
				if ( 'icon' === $item['pp_icon_type'] ) {
					if ( ! empty( $item['list_icon'] ) || ( ! empty( $item['icon']['value'] ) && $is_new ) ) {
						?>
						<span <?php echo wp_kses_post( $this->get_render_attribute_string( $icon_key ) ); ?>>
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $item['icon'], array( 'aria-hidden' => 'true' ) );
							} else {
								?>
								<i class="<?php echo esc_attr( $item['list_icon'] ); ?>" aria-hidden="true"></i>
								<?php
							}
							?>
						</span>
						<?php
					}
				} elseif ( 'image' === $item['pp_icon_type'] ) {
					$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['list_image']['id'], 'thumbnail', $settings );

					if ( $image_url ) {
						?>
						<span class="pp-info-list-image <?php echo esc_attr( $icon_animation ); ?>"><img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( Control_Media::get_image_alt( $item['list_image'] ) ); ?>"></span>
						<?php
					} else {
						?>
						<img src="<?php echo esc_url( $item['list_image']['url'] ); ?>">
						<?php
					}
				} elseif ( 'text' === $item['pp_icon_type'] ) {
					?>
					<span class="pp-info-list-icon pp-info-list-number <?php echo esc_attr( $icon_animation ); ?>">
						<?php echo wp_kses_post( $item['icon_text'] ); ?>
					</span>
					<?php
				}
				?>
			</div>
			<?php
		}
	}

	/**
	 * Render info list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		view.addRenderAttribute(
			'info-list',
			{
				'class': [ 'pp-info-list-container', 'pp-list-container' ],
			}
		);
		   
		if ( settings.connector == 'yes' ) {
			view.addRenderAttribute( 'info-list', 'class', 'pp-info-list-connector' );  
			if ( settings.corner_lines == 'yes' ) {
			view.addRenderAttribute( 'info-list', 'class', 'pp-info-list-corners-hide' );
			}
		}
			   
		var iconsHTML = {},
			migrated = {},
			buttonIconHTML = {},
			buttonMigrated = {};
		#>
		<div {{{ view.getRenderAttributeString( 'info-list' ) }}}>
			<ul class="pp-list-items">
				<# var i = 1; #>
				<# _.each( settings.list_items, function( item, index ) { #>
					<#
						var text_key = 'list_items.' + (i - 1) + '.text';
						var description_key = 'list_items.' + (i - 1) + '.description';

						view.addInlineEditingAttributes( text_key );

						view.addRenderAttribute( description_key, 'class', 'pp-info-list-description' );
						view.addInlineEditingAttributes( description_key );
					#>
					<# if ( item.text || item.description ) { #>
						<li class="pp-info-list-item">
							<# if ( item.link.url != '' && item.link_type == 'box' ) { #>
								<a href="{{ _.escape( item.link.url ) }}">
							<# } #>
								<div class="pp-info-list-item-inner">
									<# if ( item.pp_icon_type != 'none' ) { #>
										<div class="pp-infolist-icon-wrapper">
											<# if ( item.pp_icon_type == 'icon' ) { #>
												<# if ( item.list_icon || item.icon.value ) { #>
													<span class="pp-info-list-icon pp-icon elementor-animation-{{ settings.icon_hover_animation }}" aria-hidden="true">
													<#
														iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.icon, { 'aria-hidden': true }, 'i', 'object' );
														migrated[ index ] = elementor.helpers.isIconMigrated( item, 'icon' );
														if ( iconsHTML[ index ] && iconsHTML[ index ].rendered && ( ! item.list_icon || migrated[ index ] ) ) { #>
															{{{ iconsHTML[ index ].value }}}
														<# } else { #>
															<i class="{{ item.list_icon }}" aria-hidden="true"></i>
														<# }
													#>
													</span>
												<# } #>
											<# } else if ( item.pp_icon_type == 'image' ) { #>
												<span class="pp-info-list-image elementor-animation-{{ settings.icon_hover_animation }}">
													<#
													var image = {
														id: item.list_image.id,
														url: item.list_image.url,
														size: settings.thumbnail_size,
														dimension: settings.thumbnail_custom_dimension,
														model: view.getEditModel()
													};
													var image_url = elementor.imagesManager.getImageUrl( image );
													#>
													<img src="{{ _.escape( image_url ) }}" />
												</span>
											<# } else if ( item.pp_icon_type == 'text' ) { #>
												<span class="pp-info-list-icon pp-info-list-number elementor-animation-{{ settings.icon_hover_animation }}">
													{{ item.icon_text }}
												</span>
											<# } #>
										</div>
									<# } #>
									<div class="pp-infolist-content-wrapper">
										<# if ( item.text ) { #>
											<# var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag ); #>
											<{{{ titleHTMLTag }}} class="pp-info-list-title">
												<# if ( item.link.url != '' && item.link_type == 'title' ) { #>
													<a href="{{ _.escape( item.link.url ) }}">
												<# } #>
												<span {{{ view.getRenderAttributeString( 'list_items.' + (i - 1) + '.text' ) }}}>
												{{{ item.text }}}
												</span>
												<# if ( item.link.url != '' && item.link_type == 'title' ) { #>
													</a>
												<# } #>
											</{{{ titleHTMLTag }}}>
										<# } #>
										<# if ( item.description ) { #>
										<div {{{ view.getRenderAttributeString( description_key ) }}}>
											{{{ item.description }}}
										</div>
										<# } #>
										<# if ( item.link.url != '' && item.link_type == 'button' ) { #>
											<div class="pp-info-list-button-wrapper pp-info-list-button-icon-{{ item.button_icon_position }}">
												<a href="{{ _.escape( item.link.url ) }}">
													<div class="pp-info-list-button elementor-button elementor-size-{{ settings.button_size }} elementor-animation-{{ settings.button_animation }}">
														<#
															buttonIconHTML[ index ] = elementor.helpers.renderIcon( view, item.selected_icon, { 'aria-hidden': true }, 'i', 'object' );
															buttonMigrated[ index ] = elementor.helpers.isIconMigrated( item, 'selected_icon' );
														#>
														<# if ( buttonIconHTML[ index ] && buttonIconHTML[ index ].rendered && ( ! item.button_icon || buttonMigrated[ index ] ) ) { #>
															<span class="pp-button-icon pp-icon">
																{{{ buttonIconHTML[ index ].value }}}
															</span>
														<# } else if ( item.button_icon ) { #>
															<span class="pp-button-icon pp-icon">
																<i class="{{ item.button_icon }}" aria-hidden="true"></i>
															</span>
														<# } #>

														<# if ( item.button_text != '' ) { #>
															<span class="pp-button-text">
																{{{ item.button_text }}}
															</span>
														<# } #>
													</div>
												</a>
											</div>
										<# } #>
									</div>
								</div>
							<# if ( item.link_type == 'box' ) { #>
								</a>
							<# } #>
						</li>
					<# } #>
				<# i++ } ); #>
			</ul>
		</div>
		<?php
	}
}
