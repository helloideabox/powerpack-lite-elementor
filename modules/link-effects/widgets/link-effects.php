<?php
namespace PowerpackElementsLite\Modules\LinkEffects\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

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
		return parent::get_widget_name( 'Link_Effects' );
	}

	/**
	 * Retrieve link effects widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Link_Effects' );
	}

	/**
	 * Retrieve link effects widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Link_Effects' );
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
		return parent::get_widget_keywords( 'Link_Effects' );
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
		return [ 'widget-pp-link-effects' ];
	}

	/**
	 * Register link effects widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 2.3.2
	 * @access protected
	 */
	protected function register_controls() {

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
				'label'                 => esc_html__( 'Link Effects', 'powerpack' ),
			]
		);

		$this->add_control(
			'text',
			[
				'label'                 => esc_html__( 'Text', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'label_block'           => true,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Click Here', 'powerpack' ),
			]
		);

		$this->add_control(
			'secondary_text',
			[
				'label'                 => esc_html__( 'Secondary Text', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Click Here', 'powerpack' ),
				'condition'             => [
					'effect'    => 'effect-9',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label'                 => esc_html__( 'Link', 'powerpack' ),
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
				'label'                 => esc_html__( 'Effect', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'effect-1'  => esc_html__( 'Bottom Border Slides In', 'powerpack' ),
					'effect-2'  => esc_html__( 'Bottom Border Slides Out', 'powerpack' ),
					'effect-3'  => esc_html__( 'Brackets', 'powerpack' ),
					'effect-4'  => esc_html__( '3D Rolling Cube', 'powerpack' ),
					'effect-5'  => esc_html__( 'Same Word Slide In', 'powerpack' ),
					'effect-6'  => esc_html__( 'Right Angle Slides Down over Title', 'powerpack' ),
					'effect-7'  => esc_html__( 'Second Border Slides Up', 'powerpack' ),
					'effect-8'  => esc_html__( 'Border Slight Translate', 'powerpack' ),
					'effect-9'  => esc_html__( 'Second Text and Borders', 'powerpack' ),
					'effect-10' => esc_html__( 'Push Out', 'powerpack' ),
					'effect-11' => esc_html__( 'Text Fill', 'powerpack' ),
					'effect-12' => esc_html__( 'Circle', 'powerpack' ),
					'effect-13' => esc_html__( 'Three Circles', 'powerpack' ),
					'effect-14' => esc_html__( 'Border Switch', 'powerpack' ),
					'effect-15' => esc_html__( 'Scale Down', 'powerpack' ),
					'effect-16' => esc_html__( 'Fall Down', 'powerpack' ),
					'effect-17' => esc_html__( 'Move Up and Push Border', 'powerpack' ),
					'effect-18' => esc_html__( 'Cross', 'powerpack' ),
					'effect-19' => esc_html__( '3D Side', 'powerpack' ),
					'effect-20' => esc_html__( 'Unfold', 'powerpack' ),
					'effect-21' => esc_html__( 'Borders Slight Translate', 'powerpack' ),
				],
				'default'               => 'effect-1',
			]
		);

		$this->add_control(
			'html_tag',
			array(
				'label'   => esc_html__( 'HTML Tag', 'powerpack' ),
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

		$this->add_responsive_control(
			'align',
			[
				'label'                 => esc_html__( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
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
					'justify'   => [
						'title' => esc_html__( 'Justified', 'powerpack' ),
						'icon'  => 'eicon-text-align-justify',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}}'   => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$help_docs = PP_Config::get_widget_help_links( 'Link_Effects' );

		if ( ! empty( $help_docs ) ) {

			/**
			 * Content Tab: Help Docs
			 *
			 * @since 2.4.0
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
				'label'                 => esc_html__( 'Link Effects', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
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
				'selector'              => '{{WRAPPER}} a.pp-link',
			]
		);

		$this->add_responsive_control(
			'divider_title_width',
			[
				'label'                 => esc_html__( 'Width', 'powerpack' ),
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
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-link-effect-19' => 'width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .pp-link-effect-19 span' => 'transform-origin: 50% 50% calc(-{{SIZE}}{{UNIT}}/2)',
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
				'label'                 => esc_html__( 'Normal', 'powerpack' ),
			]
		);

		$this->add_control(
			'link_color_normal',
			[
				'label'                 => esc_html__( 'Link Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} a.pp-link, {{WRAPPER}} .pp-link-effect-10 span, {{WRAPPER}} .pp-link-effect-15:before, {{WRAPPER}} .pp-link-effect-16, {{WRAPPER}} .pp-link-effect-17:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color_normal',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-link-effect-4 span, {{WRAPPER}} .pp-link-effect-10 span, {{WRAPPER}} .pp-link-effect-19 span, {{WRAPPER}} .pp-link-effect-20 span' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link_border_color',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-link-effect-8:before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-11' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-1:after, {{WRAPPER}} .pp-link-effect-2:after, {{WRAPPER}} .pp-link-effect-6:before, {{WRAPPER}} .pp-link-effect-6:after, {{WRAPPER}} .pp-link-effect-7:before, {{WRAPPER}} .pp-link-effect-7:after, {{WRAPPER}} .pp-link-effect-14:before, {{WRAPPER}} .pp-link-effect-14:after, {{WRAPPER}} .pp-link-effect-18:before, {{WRAPPER}} .pp-link-effect-18:after' => 'background: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-3:before, {{WRAPPER}} .pp-link-effect-3:after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-20 span' => 'box-shadow: inset 0 3px {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_link_hover',
			[
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_control(
			'link_color_hover',
			[
				'label'                 => esc_html__( 'Link Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} a.pp-link:hover, {{WRAPPER}} .pp-link-effect-10:before, {{WRAPPER}} .pp-link-effect-11:before, {{WRAPPER}} .pp-link-effect-15, {{WRAPPER}} .pp-link-effect-16:before, {{WRAPPER}} .pp-link-effect-20 span:before' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_color_hover',
			[
				'label'                 => esc_html__( 'Background Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-link-effect-4 span:before, {{WRAPPER}} .pp-link-effect-10:before, {{WRAPPER}} .pp-link-effect-19 span:before, {{WRAPPER}} .pp-link-effect-20 span:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'link_border_color_hover',
			[
				'label'                 => esc_html__( 'Border Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-link-effect-8:after' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-11:before' => 'border-bottom-color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-9:before, {{WRAPPER}} .pp-link-effect-9:after, {{WRAPPER}} .pp-link-effect-14:hover:before, {{WRAPPER}} .pp-link-effect-14:focus:before, {{WRAPPER}} .pp-link-effect-14:hover:after, {{WRAPPER}} .pp-link-effect-14:focus:after, {{WRAPPER}} .pp-link-effect-17:after, {{WRAPPER}} .pp-link-effect-18:hover:before, {{WRAPPER}} .pp-link-effect-18:focus:before, {{WRAPPER}} .pp-link-effect-18:hover:after, {{WRAPPER}} .pp-link-effect-18:focus:after, {{WRAPPER}} .pp-link-effect-21:before, {{WRAPPER}} .pp-link-effect-21:after' => 'background: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-17' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-link-effect-13:hover:before, {{WRAPPER}} .pp-link-effect-13:focus:before' => 'color: {{VALUE}}; text-shadow: 10px 0 {{VALUE}}, -10px 0 {{VALUE}}',
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

		$link_text = '' !== $settings['text'] ? $settings['text'] : '';
		$link_secondary_text = ! empty( $settings['secondary_text'] ) ? $settings['secondary_text'] : '';

		if ( ! empty( $settings['link']['url'] ) ) {
			$this->add_link_attributes( 'link', $settings['link'] );
		}

		$this->add_render_attribute( 'link', 'class', 'pp-link' );

		if ( $settings['effect'] ) {
			$this->add_render_attribute( 'link', 'class', 'pp-link-' . $settings['effect'] );
		}

		switch ( $settings['effect'] ) {
			case 'effect-4':
			case 'effect-5':
			case 'effect-19':
			case 'effect-20':
				$this->add_render_attribute( 'pp-link-text', 'data-hover', wp_strip_all_tags( $link_text ) );
				break;

			case 'effect-10':
			case 'effect-11':
			case 'effect-15':
			case 'effect-16':
			case 'effect-17':
			case 'effect-18':
				$this->add_render_attribute( 'pp-link-text-2', 'data-hover', wp_strip_all_tags( $link_text ) );
				break;
		}

		$title_tag = PP_Helper::validate_html_tag( $settings['html_tag'] );
		?>
		<<?php echo esc_html( $title_tag ); ?> class="pp-link-container">
			<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'link' ) ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'pp-link-text-2' ) ); ?>>
				<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'pp-link-text' ) ); ?>>
					<?php echo wp_kses_post( $link_text ); ?>
				</span>
				<?php if ( 'effect-9' === $settings['effect'] ) { ?>
					<span><?php echo wp_kses_post( $link_secondary_text ); ?></span>
				<?php } ?>
			</a>
		</<?php echo esc_html( $title_tag ); ?>>
		<?php
	}

	/**
	 * Render link effects widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.4.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		view.addRenderAttribute( 'link', 'class', ['pp-link', 'pp-link-' + settings.effect] );

		var hasLink = settings.link.url;

		if ( hasLink ) {
			view.addRenderAttribute( 'link', 'href', _.escape( settings.link.url ) );
		}

		switch ( settings.effect ) {
			case 'effect-4':
			case 'effect-5':
			case 'effect-19':
			case 'effect-20':
				view.addRenderAttribute( 'pp-link-text', 'data-hover', elementor.helpers.sanitize( settings.text ) );
				break;

			case 'effect-10':
			case 'effect-11':
			case 'effect-15':
			case 'effect-16':
			case 'effect-17':
			case 'effect-18':
				view.addRenderAttribute( 'link', 'data-hover', elementor.helpers.sanitize( settings.text ) );
				break;
		}
		#>
		<# var htmlTag = elementor.helpers.validateHTMLTag( settings.html_tag ); #>
		<{{{ htmlTag }}} class="pp-link-container">
			<a {{{ view.getRenderAttributeString( 'link' ) }}}>
				<span {{{ view.getRenderAttributeString( 'pp-link-text' ) }}}>
					{{{ elementor.helpers.sanitize( settings.text ) }}}
				</span>
				<# if ( 'effect-9' === settings.effect ) { #>
					<span>{{{ elementor.helpers.sanitize( settings.secondary_text ) }}}</span>
				<# } #>
			</a>
		</{{{ htmlTag }}}>
		<?php
	}
}
