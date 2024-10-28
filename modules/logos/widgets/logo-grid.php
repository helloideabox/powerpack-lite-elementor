<?php
namespace PowerpackElementsLite\Modules\Logos\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Logo Grid Widget
 */
class Logo_Grid extends Powerpack_Widget {

	/**
	 * Retrieve logo grid widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Logo_Grid' );
	}

	/**
	 * Retrieve logo grid widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Logo_Grid' );
	}

	/**
	 * Retrieve logo grid widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Logo_Grid' );
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
		return parent::get_widget_keywords( 'Logo_Grid' );
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
		return [ 'widget-pp-logo-grid' ];
	}

	/**
	 * Register logo grid widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.1.4
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_logo_grid_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_logos_controls();
		$this->register_style_title_controls();
	}

	protected function register_content_logo_grid_controls() {
		$this->start_controls_section(
			'section_logo_grid',
			[
				'label' => esc_html__( 'Logo Grid', 'powerpack' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'items_repeater' );

		$repeater->start_controls_tab( 'tab_content', [ 'label' => esc_html__( 'Content', 'powerpack' ) ] );

			$repeater->add_control(
				'logo_image',
				[
					'label'             => esc_html__( 'Image', 'powerpack' ),
					'type'              => Controls_Manager::MEDIA,
					'dynamic'           => [
						'active'   => true,
					],
					'default'           => [
						'url' => Utils::get_placeholder_image_src(),
					],
				]
			);

			$repeater->add_control(
				'title',
				[
					'label'             => esc_html__( 'Title', 'powerpack' ),
					'type'              => Controls_Manager::TEXT,
					'dynamic'           => [
						'active'   => true,
					],
				]
			);

			$repeater->add_control(
				'link',
				[
					'label'             => esc_html__( 'Link', 'powerpack' ),
					'type'              => Controls_Manager::URL,
					'dynamic'           => [
						'active'   => true,
					],
					'placeholder'       => 'https://www.your-link.com',
					'default'           => [
						'url' => '',
					],
				]
			);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'tab_style', [ 'label' => esc_html__( 'Style', 'powerpack' ) ] );

			$repeater->add_control(
				'custom_style',
				[
					'label'             => esc_html__( 'Custom Style', 'powerpack' ),
					'type'              => Controls_Manager::SWITCHER,
					'description'       => esc_html__( 'Add custom styles which will affect only this item', 'powerpack' ),
					'default'           => '',
					'label_on'          => esc_html__( 'On', 'powerpack' ),
					'label_off'         => esc_html__( 'Off', 'powerpack' ),
					'return_value'      => 'yes',
				]
			);

			$repeater->add_control(
				'custom_style_target',
				[
					'label'                => esc_html__( 'Apply Styles To', 'powerpack' ),
					'type'                 => Controls_Manager::SELECT,
					'default'              => 'container',
					'options'              => [
						'logo'      => esc_html__( 'Logo Image', 'powerpack' ),
						'container' => esc_html__( 'Logo Container', 'powerpack' ),
					],
					'condition'          => [
						'custom_style' => 'yes',
					],
				]
			);

			$repeater->add_control(
				'custom_logo_wrapper_bg',
				[
					'label'              => esc_html__( 'Background Color', 'powerpack' ),
					'type'               => Controls_Manager::COLOR,
					'default'            => '',
					'selectors'          => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-logo-grid-item-custom, {{WRAPPER}} {{CURRENT_ITEM}} .pp-logo-grid-item-custom' => 'background-color: {{VALUE}}',
					],
					'condition'          => [
						'custom_style'   => 'yes',
					],
				]
			);

			$repeater->add_control(
				'custom_logo_wrapper_border_type',
				[
					'label'                => esc_html__( 'Border Type', 'powerpack' ),
					'type'                 => Controls_Manager::SELECT,
					'default'              => '',
					'options'              => [
						''       => esc_html__( 'None', 'powerpack' ),
						'solid'  => esc_html__( 'Solid', 'powerpack' ),
						'double' => esc_html__( 'Double', 'powerpack' ),
						'dotted' => esc_html__( 'Dotted', 'powerpack' ),
						'dashed' => esc_html__( 'Dashed', 'powerpack' ),
						'groove' => esc_html__( 'Groove', 'powerpack' ),
					],
					'selectors'             => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-logo-grid-item-custom, {{WRAPPER}} {{CURRENT_ITEM}} .pp-logo-grid-item-custom' => 'border-style: {{VALUE}};',
					],
					'condition'          => [
						'custom_style'   => 'yes',
					],
				]
			);

			$repeater->add_control(
				'custom_logo_border_width',
				[
					'label'                 => esc_html__( 'Border Width', 'powerpack' ),
					'type'                  => Controls_Manager::SLIDER,
					'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
					'range'                 => [
						'px' => [
							'min' => 0,
							'max' => 20,
						],
					],
					'selectors'             => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-logo-grid-item-custom, {{WRAPPER}} {{CURRENT_ITEM}} .pp-logo-grid-item-custom' => 'border-width: {{SIZE}}{{UNIT}};',
					],
					'condition'          => [
						'custom_style'   => 'yes',
						'custom_logo_wrapper_border_type!' => '',
					],
				]
			);

			$repeater->add_control(
				'custom_logo_wrapper_border_color',
				[
					'label'              => esc_html__( 'Border Color', 'powerpack' ),
					'type'               => Controls_Manager::COLOR,
					'default'            => '',
					'selectors'          => [
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-logo-grid-item-custom, {{WRAPPER}} {{CURRENT_ITEM}} .pp-logo-grid-item-custom' => 'border-color: {{VALUE}}',
					],
					'condition'          => [
						'custom_style' => 'yes',
						'custom_logo_wrapper_border_type!' => '',
					],
				]
			);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'pp_logos',
			[
				'label'     => esc_html__( 'Add Logos', 'powerpack' ),
				'type'      => Controls_Manager::REPEATER,
				'default'   => [
					[
						'logo_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'logo_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'logo_image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				],
				'fields'        => $repeater->get_controls(),
				'title_field'   => '{{title}}',
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'                 => esc_html__( 'Show Title', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'title_html_tag',
			[
				'label'                => esc_html__( 'Title HTML Tag', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'h4',
				'options'              => [
					'h1'     => esc_html__( 'H1', 'powerpack' ),
					'h2'     => esc_html__( 'H2', 'powerpack' ),
					'h3'     => esc_html__( 'H3', 'powerpack' ),
					'h4'     => esc_html__( 'H4', 'powerpack' ),
					'h5'     => esc_html__( 'H5', 'powerpack' ),
					'h6'     => esc_html__( 'H6', 'powerpack' ),
					'div'    => esc_html__( 'div', 'powerpack' ),
					'span'   => esc_html__( 'span', 'powerpack' ),
					'p'      => esc_html__( 'p', 'powerpack' ),
				],
				'condition'             => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->add_control(
			'randomize',
			[
				'label'                 => esc_html__( 'Randomize Logos', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$logo_columns = range( 1, 12 );
		$logo_columns = array_combine( $logo_columns, $logo_columns );

		$this->add_responsive_control(
			'columns',
			[
				'label'                 => esc_html__( 'Columns', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => '3',
				'tablet_default'        => '2',
				'mobile_default'        => '1',
				'options'               => $logo_columns,
				'prefix_class'          => 'elementor-grid%s-',
			]
		);

		$this->add_responsive_control(
			'logos_spacing',
			[
				'label'                 => esc_html__( 'Logos Gap', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [ 'size' => 10 ],
				'range'                 => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'             => [
					'{{WRAPPER}}' => '--grid-column-gap: {{SIZE}}{{UNIT}}; --grid-row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'logos_vertical_align',
			[
				'label'                 => esc_html__( 'Vertical Align', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'default'               => 'top',
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
					'{{WRAPPER}} .pp-logo-grid .elementor-grid-item' => 'align-items: {{VALUE}};',
				],
				'selectors_dictionary'  => [
					'top'          => 'flex-start',
					'middle'       => 'center',
					'bottom'       => 'flex-end',
				],
			]
		);

		$this->add_control(
			'logos_horizontal_align',
			[
				'label'                 => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					],
					'center'           => [
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					],
					'right'            => [
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'               => 'center',
				'selectors_dictionary'  => [
					'left'     => 'flex-start',
					'center'   => 'center',
					'right'    => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-logo-grid .elementor-grid-item, {{WRAPPER}} .pp-logo-grid .pp-logo-wrap' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'image',
				'label'                 => esc_html__( 'Image Size', 'powerpack' ),
				'default'               => 'full',
			]
		);

		$this->add_responsive_control(
			'logos_width',
			[
				'label'             => esc_html__( 'Image Width', 'powerpack' ),
				'type'              => Controls_Manager::SLIDER,
				'size_units'        => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'             => [
					'px' => [
						'min'   => 10,
						'max'   => 800,
						'step'  => 1,
					],
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-logo-wrap img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Logo_Grid' );

		if ( ! empty( $help_docs ) ) {

			/**
			 * Content Tab: Help Docs
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

	protected function register_style_logos_controls() {
		$this->start_controls_section(
			'section_logos_style',
			[
				'label'                 => esc_html__( 'Logos', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'tabs_logos_style' );

		$this->start_controls_tab(
			'tab_logos_normal',
			[
				'label'             => esc_html__( 'Normal', 'powerpack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'logo_bg',
				'label'                 => esc_html__( 'Background', 'powerpack' ),
				'types'                 => [ 'classic', 'gradient' ],
				'exclude'               => array( 'image' ),
				'selector'              => '{{WRAPPER}} .pp-logo-wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'logo_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-logo-wrap',
			]
		);

		$this->add_control(
			'logo_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-logo-wrap, {{WRAPPER}} .pp-logo-wrap img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-logo-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'grayscale_normal',
			[
				'label'             => esc_html__( 'Grayscale', 'powerpack' ),
				'type'              => Controls_Manager::SWITCHER,
				'default'           => 'no',
				'label_on'          => esc_html__( 'Yes', 'powerpack' ),
				'label_off'         => esc_html__( 'No', 'powerpack' ),
				'return_value'      => 'yes',
			]
		);

		$this->add_control(
			'opacity_normal',
			[
				'label'             => esc_html__( 'Opacity', 'powerpack' ),
				'type'              => Controls_Manager::SLIDER,
				'range'             => [
					'px' => [
						'min'   => 0,
						'max'   => 1,
						'step'  => 0.1,
					],
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-logo-wrap img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'pp_logo_box_shadow_normal',
				'selector'              => '{{WRAPPER}} .pp-logo-wrap',
				'separator'             => 'before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_logos_hover',
			[
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'logos_bg_hover',
				'label'                 => esc_html__( 'Background', 'powerpack' ),
				'types'                 => [ 'classic', 'gradient' ],
				'exclude'               => array( 'image' ),
				'selector'              => '{{WRAPPER}} .pp-logo-wrap:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'logo_border_hover',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-logo-wrap:hover',
			]
		);

		$this->add_responsive_control(
			'translate',
			[
				'label'                 => esc_html__( 'Slide', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => -40,
						'max'   => 40,
						'step'  => 1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .elementor-grid-item:hover' => 'transform:translateY({{SIZE}}{{UNIT}})',
				],
			]
		);

		$this->add_control(
			'grayscale_hover',
			[
				'label'             => esc_html__( 'Grayscale', 'powerpack' ),
				'type'              => Controls_Manager::SWITCHER,
				'default'           => 'no',
				'label_on'          => esc_html__( 'Yes', 'powerpack' ),
				'label_off'         => esc_html__( 'No', 'powerpack' ),
				'return_value'      => 'yes',
			]
		);

		$this->add_control(
			'opacity_hover',
			[
				'label'             => esc_html__( 'Opacity', 'powerpack' ),
				'type'              => Controls_Manager::SLIDER,
				'range'             => [
					'px' => [
						'min'   => 0,
						'max'   => 1,
						'step'  => 0.1,
					],
				],
				'selectors'         => [
					'{{WRAPPER}} .pp-logo-wrap:hover img' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'pp_logo_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-logo-wrap:hover',
				'separator'             => 'before',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_title_controls() {
		$this->start_controls_section(
			'section_logo_title_style',
			[
				'label'                 => esc_html__( 'Title', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'              => esc_html__( 'Color', 'powerpack' ),
				'type'               => Controls_Manager::COLOR,
				'default'            => '',
				'selectors'          => [
					'{{WRAPPER}} .pp-logo-title' => 'color: {{VALUE}}',
				],
				'condition'          => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'title_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'              => '{{WRAPPER}} .pp-logo-title',
				'condition'             => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->add_control(
			'title_spacing',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-logo-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'show_title'   => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render logo grid widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'logo-grid', 'class', 'pp-logo-grid elementor-grid' );

		if ( 'yes' === $settings['grayscale_normal'] ) {
			$this->add_render_attribute( 'logo-grid', 'class', 'grayscale-normal' );
		}

		if ( 'yes' === $settings['grayscale_hover'] ) {
			$this->add_render_attribute( 'logo-grid', 'class', 'grayscale-hover' );
		}
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'logo-grid' ) ); ?>>
			<?php
			$logos = $settings['pp_logos'];

			if ( 'yes' === $settings['randomize'] ) {
				shuffle( $logos );
			}

			foreach ( $logos as $index => $item ) :
				if ( ! empty( $item['logo_image']['url'] ) ) {
					$item_wrap_setting_key = $this->get_repeater_setting_key( 'item_wrap', 'logos', $index );
					$item_setting_key = $this->get_repeater_setting_key( 'item', 'logos', $index );
					$link_setting_key = $this->get_repeater_setting_key( 'link', 'logos', $index );

					$this->add_render_attribute( $item_wrap_setting_key, 'class', [
						'elementor-grid-item',
						'elementor-repeater-item-' . esc_attr( $item['_id'] ),
					] );

					$this->add_render_attribute( $item_setting_key, 'class', 'pp-logo-wrap' );

					if ( 'yes' === $item['custom_style'] ) {
						if ( 'logo' === $item['custom_style_target'] ) {
							$this->add_render_attribute( $item_setting_key, 'class', 'pp-logo-grid-item-custom' );
						} else {
							$this->add_render_attribute( $item_wrap_setting_key, 'class', 'pp-logo-grid-item-custom' );
						}
					}
					?>
					<div <?php echo wp_kses_post( $this->get_render_attribute_string( $item_wrap_setting_key ) ); ?>>
						<div <?php echo wp_kses_post( $this->get_render_attribute_string( $item_setting_key ) ); ?>>
						<?php
						if ( '' !== $item['link']['url'] ) {
							$this->add_link_attributes( $link_setting_key, $item['link'] );
						}

						if ( ! empty( $item['link']['url'] ) ) {
							?>
							<a <?php echo wp_kses_post( $this->get_render_attribute_string( $link_setting_key ) ); ?>>
							<?php
						}

						echo wp_kses_post( $this->render_image( $item, $settings ) );

						if ( '' !== $item['link']['url'] ) { ?>
							</a>
							<?php
						}
						?>
						</div>
						<?php
						if ( 'yes' == $settings['show_title'] ) {
							if ( '' !== $item['title'] ) {
								$title_tag = PP_Helper::validate_html_tag( $settings['title_html_tag'] );
								?>
								<<?php echo esc_html( $title_tag ); ?> class="pp-logo-title">
								<?php
								if ( '' !== $item['link']['url'] ) { ?>
									<a <?php echo wp_kses_post( $this->get_render_attribute_string( $link_setting_key ) ); ?>>
									<?php
								}

								echo wp_kses_post( $item['title'] );

								if ( '' !== $item['link']['url'] ) { ?>
									</a>
									<?php
								}
								?>
								</<?php echo esc_html( $title_tag ); ?>>
								<?php
							}
						}
						?>
					</div>
					<?php
				}
			endforeach;
			?>
		</div>
		<?php
	}

	/**
	 *  Render Image HTML.
	 *
	 *  @param string $item image attributes.
	 *  @param string $instance settings object instance.
	 *
	 * @access protected
	 */
	protected function render_image( $item, $instance ) {

		$image_id   = apply_filters( 'wpml_object_id', $item['logo_image']['id'], 'attachment', true );
		$image_size = $instance['image_size'];
		$image_alt  = esc_attr( Control_Media::get_image_alt( $item['logo_image'] ) );
		$image_url  = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image', $instance );

		if ( ! $image_url ) {
			$image_url = $item['logo_image']['url'];
		}

		return sprintf( '<img src="%s" alt="%s" />', $image_url, esc_attr( $image_alt ) );
	}

	/**
	 * Render logo grid widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			var i = 1;

			view.addRenderAttribute( 'logo-grid', {
				'class': 'pp-logo-grid elementor-grid',
			});

			if ( settings.grayscale_normal == 'yes' ) {
				view.addRenderAttribute( 'logo-grid', {
					'class': 'grayscale-normal',
				});
			}

			if ( settings.grayscale_hover == 'yes' ) {
				view.addRenderAttribute( 'logo-grid', {
					'class': 'grayscale-hover',
				});
			}
		#>
		<div {{{ view.getRenderAttributeString( 'logo-grid' ) }}}>
			<# _.each( settings.pp_logos, function( item ) { #>
				<# if ( item.logo_image.url != '' ) { #>
					<#
						var item_wrap_custom_style_class = '',
							item_custom_style_class = '';

						if ( item.custom_style == 'yes' ) {
							if ( item.custom_style_target == 'logo' ) {
								var item_custom_style_class = 'pp-logo-grid-item-custom';
							} else {
								var item_wrap_custom_style_class = 'pp-logo-grid-item-custom';
							}
						}
					#>
					<div class="elementor-grid-item elementor-repeater-item-{{ item._id }} {{ item_wrap_custom_style_class }}">
						<div class="pp-logo-wrap {{ item_custom_style_class }}">
							<# if ( item.link && item.link.url ) { #>
								<a href="{{ _.escape( item.link.url ) }}">
							<# } #>
							<#
							if ( item.logo_image && item.logo_image.id ) {

								var image = {
									id: item.logo_image.id,
									url: item.logo_image.url,
									size: settings.image_size,
									dimension: settings.image_custom_dimension,
									model: view.getEditModel()
								};

								var image_url = elementor.imagesManager.getImageUrl( image );

								if ( ! image_url ) {
									return;
								}
							} else {

								var image_url = item.logo_image.url;
							}
							#>
							<img src="{{ _.escape( image_url ) }}" alt="{{ item.title }}" />

							<# if ( item.link && item.link.url ) { #>
								</a>
							<# } #>
						</div>
						<#
							if ( 'yes' == settings.show_title ) {
								if ( item.title != '' ) {
									var title = item.title;

									view.addRenderAttribute( 'title' + i, 'class', 'pp-logo-grid-title' );

									if ( item.link && item.link.url ) {
										title = '<a href="' + _.escape( item.link.url ) + '">' + item.title + '</a>';
									}

									var titleHTMLTag = elementor.helpers.validateHTMLTag( settings.title_html_tag ),
										title_html = '<' + titleHTMLTag  + ' ' + view.getRenderAttributeString( 'title' + i ) + '>' + title + '</' + titleHTMLTag + '>';

									print( title_html );
								}
							}
						#>
					</div>
				<# } #>
			<# i++ } ); #>
		</div>
		<?php
	}
}
