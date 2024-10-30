<?php
namespace PowerpackElementsLite\Modules\Divider\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

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
	 * @since 2.8.0
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends(): array {
		return [ 'widget-pp-divider' ];
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
		$this->register_content_help_docs_controls();

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
				'label'                 => esc_html__( 'Divider', 'powerpack' ),
			]
		);

		$this->add_control(
			'divider_type',
			[
				'label'                 => esc_html__( 'Add Element', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'default'               => 'plain',
				'options'               => [
					'plain'        => [
						'title'    => esc_html__( 'None', 'powerpack' ),
						'icon'     => 'eicon-ban',
					],
					'text'         => [
						'title'    => esc_html__( 'Text', 'powerpack' ),
						'icon'     => 'eicon-t-letter-bold',
					],
					'icon'         => [
						'title'    => esc_html__( 'Icon', 'powerpack' ),
						'icon'     => 'eicon-star',
					],
					'image'        => [
						'title'    => esc_html__( 'Image', 'powerpack' ),
						'icon'     => 'eicon-image',
					],
				],
				'toggle'                => false,
			]
		);

		$this->add_control(
			'divider_text',
			[
				'label'                 => esc_html__( 'Text', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active' => true,
				],
				'default'               => esc_html__( 'Divider Text', 'powerpack' ),
				'condition'             => [
					'divider_type' => 'text',
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'                 => esc_html__( 'Icon', 'powerpack' ),
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
				'label'                 => esc_html__( 'HTML Tag', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'span',
				'options'               => [
					'h1'            => esc_html__( 'H1', 'powerpack' ),
					'h2'            => esc_html__( 'H2', 'powerpack' ),
					'h3'            => esc_html__( 'H3', 'powerpack' ),
					'h4'            => esc_html__( 'H4', 'powerpack' ),
					'h5'            => esc_html__( 'H5', 'powerpack' ),
					'h6'            => esc_html__( 'H6', 'powerpack' ),
					'div'           => esc_html__( 'div', 'powerpack' ),
					'span'          => esc_html__( 'span', 'powerpack' ),
					'p'             => esc_html__( 'p', 'powerpack' ),
				],
				'condition'             => [
					'divider_type' => 'text',
				],
			]
		);

		$this->add_control(
			'divider_image',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
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
				'label'                 => esc_html__( 'Direction', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'horizontal',
				'options'               => [
					'horizontal' => esc_html__( 'Horizontal', 'powerpack' ),
					'vertical'   => esc_html__( 'Vertical', 'powerpack' ),
				],
				'condition'             => [
					'divider_type' => 'plain',
				],
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label'                 => esc_html__( 'Style', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'dashed',
				'options'               => [
					'solid'          => esc_html__( 'Solid', 'powerpack' ),
					'dashed'         => esc_html__( 'Dashed', 'powerpack' ),
					'dotted'         => esc_html__( 'Dotted', 'powerpack' ),
					'double'         => esc_html__( 'Double', 'powerpack' ),
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-divider, {{WRAPPER}} .divider-border' => 'border-style: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'horizontal_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack' ),
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
				'label'                 => esc_html__( 'Height', 'powerpack' ),
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
					'{{WRAPPER}} .pp-divider.vertical' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-vertical' => 'padding-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-border' => 'border-top-width: {{SIZE}}{{UNIT}};',
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
				'label'                 => esc_html__( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'default'               => 'center',
				'options'               => [
					'left'          => [
						'title'     => esc_html__( 'Left', 'powerpack' ),
						'icon'      => 'eicon-h-align-left',
					],
					'center'        => [
						'title'     => esc_html__( 'Center', 'powerpack' ),
						'icon'      => 'eicon-h-align-center',
					],
					'right'         => [
						'title'     => esc_html__( 'Right', 'powerpack' ),
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
	 * Register Help Docs Controls in Content tab
	 *
	 * @since 2.4.0
	 * @return void
	 */
	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Divider' );

		if ( ! empty( $help_docs ) ) {

			/**
			 * Content Tab: Help Docs
			 *
			 * @since 2.4.0
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
				'label'                 => esc_html__( 'Divider', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'divider_vertical_align',
			[
				'label'                 => esc_html__( 'Vertical Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'default'               => 'middle',
				'options'               => [
					'top'          => [
						'title'    => esc_html__( 'Top', 'powerpack' ),
						'icon'     => 'eicon-v-align-top',
					],
					'middle'       => [
						'title'    => esc_html__( 'Center', 'powerpack' ),
						'icon'     => 'eicon-v-align-middle',
					],
					'bottom'       => [
						'title'    => esc_html__( 'Bottom', 'powerpack' ),
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

		$this->add_responsive_control(
			'horizontal_height',
			[
				'label'                 => esc_html__( 'Weight', 'powerpack' ),
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
				'selectors'             => [
					'{{WRAPPER}} .pp-divider.horizontal' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-divider.pp-divider-horizontal' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .divider-border' => 'border-top-width: {{SIZE}}{{UNIT}};',
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
			'vertical_width',
			[
				'label'                 => esc_html__( 'Weight', 'powerpack' ),
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
				'condition'             => [
					'divider_type'      => 'plain',
					'divider_direction' => 'vertical',
				],
			]
		);

		$this->add_control(
			'divider_border_color',
			[
				'label'                 => esc_html__( 'Divider Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-divider, {{WRAPPER}} .divider-border' => 'border-color: {{VALUE}};',
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
				'label'                 => esc_html__( 'Before', 'powerpack' ),
				'condition'             => [
					'divider_type!' => 'plain',
				],
			]
		);

		$this->add_control(
			'divider_before_color',
			[
				'label'                 => esc_html__( 'Divider Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'divider_type!'   => 'plain',
				],
				'selectors'             => [
					'{{WRAPPER}} .divider-border-left .divider-border' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_after_style',
			[
				'label'                 => esc_html__( 'After', 'powerpack' ),
				'condition'             => [
					'divider_type!' => 'plain',
				],
			]
		);

		$this->add_control(
			'divider_after_color',
			[
				'label'                 => esc_html__( 'Divider Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'condition'             => [
					'divider_type!'   => 'plain',
				],
				'selectors'             => [
					'{{WRAPPER}} .divider-border-right .divider-border' => 'border-color: {{VALUE}};',
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
				'label'                 => esc_html__( 'Text', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_type'    => 'text',
				],
			]
		);

		$this->add_control(
			'text_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'         => [
						'title'    => esc_html__( 'Left', 'powerpack' ),
						'icon'     => 'eicon-h-align-left',
					],
					'center'       => [
						'title'    => esc_html__( 'Center', 'powerpack' ),
						'icon'     => 'eicon-h-align-center',
					],
					'right'        => [
						'title'    => esc_html__( 'Right', 'powerpack' ),
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
				'label'                 => esc_html__( 'Color', 'powerpack' ),
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
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
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
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
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
				'label'                 => esc_html__( 'Icon', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_type' => 'icon',
				],
			]
		);

		$this->add_control(
			'icon_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'         => [
						'title'    => esc_html__( 'Left', 'powerpack' ),
						'icon'     => 'eicon-h-align-left',
					],
					'center'       => [
						'title'    => esc_html__( 'Center', 'powerpack' ),
						'icon'     => 'eicon-h-align-center',
					],
					'right'        => [
						'title'    => esc_html__( 'Right', 'powerpack' ),
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
				'label'                 => esc_html__( 'Color', 'powerpack' ),
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
				'label'                 => esc_html__( 'Size', 'powerpack' ),
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
				'label'                 => esc_html__( 'Icon Rotation', 'powerpack' ),
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
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
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
				'label'                 => esc_html__( 'Image', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'divider_type' => 'image',
				],
			]
		);

		$this->add_control(
			'image_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'    => [
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'     => [
						'title' => esc_html__( 'Right', 'powerpack' ),
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
				'label'                 => esc_html__( 'Width', 'powerpack' ),
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
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
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
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
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
		<div class="pp-divider-wrap">
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
		#>
		<div class="pp-divider-wrap">
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
