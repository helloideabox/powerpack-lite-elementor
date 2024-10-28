<?php
namespace PowerpackElementsLite\Modules\Headings\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Dual Heading Widget
 */
class Dual_Heading extends Powerpack_Widget {

	/**
	 * Retrieve dual heading widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Dual_Heading' );
	}

	/**
	 * Retrieve dual heading widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Dual_Heading' );
	}

	/**
	 * Retrieve dual heading widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Dual_Heading' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the dual heading widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Dual_Heading' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Register dual heading widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.3.2
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_heading_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_first_section_controls();
		$this->register_style_second_section_controls();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	CONTENT TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_content_heading_controls() {
		/**
		 * Content Tab: Dual Heading
		 */
		$this->start_controls_section(
			'section_dual_heading',
			[
				'label'                 => esc_html__( 'Dual Heading', 'powerpack' ),
			]
		);

		$this->add_control(
			'first_text',
			[
				'label'                 => esc_html__( 'First Part', 'powerpack' ),
				'type'                  => Controls_Manager::TEXTAREA,
				'dynamic'               => [
					'active'   => true,
				],
				'label_block'           => true,
				'rows'                  => 3,
				'default'               => esc_html__( 'Our', 'powerpack' ),
			]
		);

		$this->add_control(
			'second_text',
			[
				'label'                 => esc_html__( 'Second Part', 'powerpack' ),
				'type'                  => Controls_Manager::TEXTAREA,
				'dynamic'               => [
					'active'   => true,
				],
				'label_block'           => true,
				'rows'                  => 3,
				'default'               => esc_html__( 'Services', 'powerpack' ),
			]
		);

		$this->add_control(
			'link',
			[
				'label'                 => esc_html__( 'Link', 'powerpack' ),
				'type'                  => Controls_Manager::URL,
				'dynamic'               => [
					'active'        => true,
					'categories'    => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'label_block'           => true,
			]
		);

		$this->add_control(
			'heading_html_tag',
			[
				'label'                 => esc_html__( 'HTML Tag', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'label_block'           => false,
				'default'               => 'h2',
				'options'               => [
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
			]
		);

		$this->add_control(
			'second_part_display',
			[
				'label'                 => esc_html__( 'Second Part Display', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'label_block'           => false,
				'default'               => 'inline',
				'options'               => [
					'inline' => esc_html__( 'Inline', 'powerpack' ),
					'block'  => esc_html__( 'Block', 'powerpack' ),
				],
				'prefix_class'          => 'pp-dual-heading-',
				'selectors'             => [
					'{{WRAPPER}} .pp-first-text' => 'display: inline-block;',
					'{{WRAPPER}} .pp-second-text' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'align',
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
					'{{WRAPPER}}'   => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Dual_Heading' );

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

	protected function register_style_first_section_controls() {
		/**
		 * Style Tab: First Part
		 */
		$this->start_controls_section(
			'first_section_style',
			[
				'label'                 => esc_html__( 'First Part', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'first_text_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-first-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'first_part_bg',
				'label'                 => esc_html__( 'Background', 'powerpack' ),
				'types'                 => [ 'none', 'classic', 'gradient' ],
				'selector'              => '{{WRAPPER}} .pp-first-text',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'first_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'              => '{{WRAPPER}} .pp-first-text',
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'first_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-first-text',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'first_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-first-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'first_text_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-first-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'                  => 'first_text_stroke',
				'selector'              => '{{WRAPPER}} .pp-first-text',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'                  => 'first_text_shadow',
				'selector'              => '{{WRAPPER}} .pp-first-text',
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'first_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-first-text',
				'separator'             => 'before',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_second_section_controls() {
		/**
		 * Style Tab: Second Part
		 */
		$this->start_controls_section(
			'second_section_style',
			[
				'label'                 => esc_html__( 'Second Part', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'second_text_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-second-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'second_part_bg',
				'label'                 => esc_html__( 'Background', 'powerpack' ),
				'types'                 => [ 'none', 'classic', 'gradient' ],
				'selector'              => '{{WRAPPER}} .pp-second-text',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'second_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'              => '{{WRAPPER}} .pp-second-text',
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'second_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-second-text',
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'second_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-second-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'second_text_margin',
			[
				'label'                 => esc_html__( 'Spacing', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 0,
					'unit' => 'px',
				],
				'range'                 => [
					'px' => [
						'max' => 100,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}}.pp-dual-heading-inline .pp-second-text' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.pp-dual-heading-block .pp-second-text' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'second_text_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-second-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name'                  => 'second_text_stroke',
				'selector'              => '{{WRAPPER}} .pp-second-text',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'                  => 'second_text_shadow',
				'selector'              => '{{WRAPPER}} .pp-second-text',
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'second_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-second-text',
				'separator'             => 'before',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render dual heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'dual-heading', 'class', 'pp-dual-heading' );
		$this->add_inline_editing_attributes( 'first_text', 'basic' );
		$this->add_render_attribute( 'first_text', 'class', 'pp-first-text' );
		$this->add_inline_editing_attributes( 'second_text', 'basic' );
		$this->add_render_attribute( 'second_text', 'class', 'pp-second-text' );

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'dual-heading-link', $settings['link'] );
		}

		if ( $settings['first_text'] || $settings['second_text'] ) {
			$html_tag = PP_Helper::validate_html_tag( $settings['heading_html_tag'] );

			$html = '<' . esc_html( $html_tag ) . ' ' . wp_kses_post( $this->get_render_attribute_string( 'dual-heading' ) ) . '>';
				if ( ! empty( $settings['link']['url'] ) ) {
					$html .= '<a ' . wp_kses_post( $this->get_render_attribute_string( 'dual-heading-link' ) ) . '>';
				}

				if ( $settings['first_text'] ) {
					$html .= '<span ' . wp_kses_post( $this->get_render_attribute_string( 'first_text' ) ) . '>' . $this->parse_text_editor( $settings['first_text'] ) . '</span>';
				}
				if ( $settings['second_text'] ) {
					$html .= '<span ' . wp_kses_post( $this->get_render_attribute_string( 'second_text' ) ) . '>' . $this->parse_text_editor( $settings['second_text'] ) . '</span>';
				}

				if ( ! empty( $settings['link']['url'] ) ) {
					$html .= '</a>';
				}

			$html .= '</' . esc_html( $html_tag ) . '>';

			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	/**
	 * Render dual heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<# var headingHTMLTag = elementor.helpers.validateHTMLTag( settings.heading_html_tag ); #>
		<{{{ headingHTMLTag }}} class="pp-dual-heading">
			<# if ( settings.link.url ) { #><a href="{{ _.escape( settings.link.url ) }}"><# } #>
				<#
				if ( settings.first_text != '' ) {
					var first_text = settings.first_text;

					view.addRenderAttribute( 'first_text', 'class', 'pp-first-text' );

					view.addInlineEditingAttributes( 'first_text' );

					var first_text_html = '<span' + ' ' + view.getRenderAttributeString( 'first_text' ) + '>' + first_text + '</span>';

					print( first_text_html );
				}

				if ( settings.second_text != '' ) {
					var second_text = settings.second_text;

					view.addRenderAttribute( 'second_text', 'class', 'pp-second-text' );

					view.addInlineEditingAttributes( 'second_text' );

					var second_text_html = '<span' + ' ' + view.getRenderAttributeString( 'second_text' ) + '>' + second_text + '</span>';

					print( second_text_html );
				}
				#>
			<# if ( settings.link.url ) { #></a><# } #>
		</{{{ headingHTMLTag }}}>
		<?php
	}
}
