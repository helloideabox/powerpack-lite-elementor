<?php
namespace PowerpackElementsLite\Modules\ScrollImage\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Scroll Image Widget
 */
class Scroll_Image extends Powerpack_Widget {

	/**
	 * Retrieve Scroll Image widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Scroll_Image' );
	}

	/**
	 * Retrieve Scroll Image widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Scroll_Image' );
	}

	/**
	 * Retrieve Scroll Image widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Scroll_Image' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the Scroll Image widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'imagesloaded',
			'pp-scroll-image',
		);
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
		return [
			'widget-pp-scroll-image'
		];
	}

	protected function register_controls() {
		/* Content Tab */
		$this->register_content_image_controls();
		$this->register_content_settings_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_image_controls();
		$this->register_style_overlay_controls();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	CONTENT TAB
	/*-----------------------------------------------------------------------------------*/

	protected function register_content_image_controls() {
		/**
		 * Content Tab: Image
		 */
		$this->start_controls_section('image_settings',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
			]
		);

		$this->add_control('image',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
				'type'                  => Controls_Manager::MEDIA,
				'dynamic'               => [ 'active' => true ],
				'default'               => [
					'url'   => Utils::get_placeholder_image_src(),
				],
				'label_block'           => true,
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

		$this->add_responsive_control('image_height',
			[
				'label'                 => esc_html__( 'Image Height', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'vh', 'custom' ],
				'default'               => [
					'unit'  => 'px',
					'size'  => 300,
				],
				'range'                 => [
					'px'    => [
						'min'   => 200,
						'max'   => 800,
					],
					'em'    => [
						'min'   => 1,
						'max'   => 50,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-scroll-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label'                 => esc_html__( 'URL', 'powerpack' ),
				'type'                  => Controls_Manager::URL,
				'dynamic'               => [
					'active' => true,
				],
				'placeholder'           => 'https://powerpackelements.com/',
				'label_block'           => true,
			]
		);

		$this->add_control(
			'icon_heading',
			[
				'label'                 => esc_html__( 'Icon', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'                 => esc_html__( 'Cover', 'powerpack' ) . ' ' . esc_html__( 'Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICONS,
				'fa4compatibility'      => 'icon',
			]
		);

		$this->add_control('icon_size',
			[
				'label'                 => esc_html__( 'Icon Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size'  => 30,
				],
				'range'                 => [
					'px'    => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-scroll-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'selected_icon[value]!' => '',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_settings_controls() {
		/**
		 * Content Tab: Settings
		 */
		$this->start_controls_section('settings',
			[
				'label'                 => esc_html__( 'Settings', 'powerpack' ),
			]
		);

		$this->add_control('trigger_type',
			[
				'label'                 => esc_html__( 'Trigger', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'hover'   => esc_html__( 'Hover', 'powerpack' ),
					'scroll'  => esc_html__( 'Mouse Scroll', 'powerpack' ),
				],
				'default'               => 'hover',
				'frontend_available'    => true,
			]
		);

		$this->add_control('duration_speed',
			[
				'label'                 => esc_html__( 'Scroll Speed', 'powerpack' ),
				'title'                 => esc_html__( 'In seconds', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => 3,
				'selectors' => [
					'{{WRAPPER}} .pp-image-scroll-container .pp-image-scroll-image img'   => 'transition: all {{Value}}s; -webkit-transition: all {{Value}}s;',
				],
				'condition'             => [
					'trigger_type' => 'hover',
				],
			]
		);

		$this->add_control('direction_type',
			[
				'label'                 => esc_html__( 'Scroll Direction', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'options'               => [
					'horizontal' => esc_html__( 'Horizontal', 'powerpack' ),
					'vertical'   => esc_html__( 'Vertical', 'powerpack' ),
				],
				'default'               => 'vertical',
				'frontend_available'    => true,
			]
		);

		$this->add_control('reverse',
			[
				'label'                 => esc_html__( 'Reverse Direction', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'frontend_available'    => true,
				'condition'             => [
					'trigger_type' => 'hover',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Scroll_Image' );
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

	protected function register_style_image_controls() {
		/**
		 * Style Tab: Image
		 */
		$this->start_controls_section('image_style',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control('icon_color',
			[
				'label'                 => esc_html__( 'Icon Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'selectors'             => [
					'{{WRAPPER}} .pp-image-scroll-icon'     => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-image-scroll-icon svg' => 'fill: {{VALUE}};',
				],
				'condition'             => [
					'selected_icon[value]!'     => '',
				],
			]
		);

		$this->start_controls_tabs( 'image_style_tabs' );

		$this->start_controls_tab('image_style_tab_normal',
			[
				'label'                 => esc_html__( 'Normal', 'powerpack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'container_border',
				'selector'              => '{{WRAPPER}} .pp-image-scroll-wrap',
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-scroll-wrap, {{WRAPPER}} .pp-container-scroll' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'container_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-image-scroll-wrap',
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'                  => 'css_filters',
				'selector'              => '{{WRAPPER}} .pp-image-scroll-container .pp-image-scroll-image img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab('image_style_tab_hover',
			[
				'label'                 => esc_html__( 'Hover', 'powerpack' ),
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'container_box_shadow_hover',
				'selector'              => '{{WRAPPER}} .pp-image-scroll-wrap:hover',
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name'                  => 'css_filters_hover',
				'selector'              => '{{WRAPPER}} .pp-image-scroll-container .pp-image-scroll-image img:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_overlay_controls() {
		/**
		 * Style Tab: Overlay
		 */
		$this->start_controls_section('overlay_style',
			[
				'label'                 => esc_html__( 'Overlay', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control('overlay',
			[
				'label'                 => esc_html__( 'Overlay', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'label_on'              => esc_html__( 'Show', 'powerpack' ),
				'label_off'             => esc_html__( 'Hide', 'powerpack' ),

			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'overlay_background',
				'types'                 => [ 'classic', 'gradient' ],
				'selector'              => '{{WRAPPER}} .pp-image-scroll-overlay',
				'exclude'               => [
					'image',
				],
				'condition'             => [
					'overlay'  => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['image']['url'] ) ) {
			return;
		}

		$link_url = $settings['link']['url'];

		if ( '' !== $settings['link']['url'] ) {
			$this->add_render_attribute( 'link', 'class', 'pp-image-scroll-link pp-media-content' );

			$this->add_link_attributes( 'link', $settings['link'] );
		}

		$this->add_render_attribute( 'icon', 'class', [
			'pp-image-scroll-icon',
			'pp-icon',
			'pp-mouse-scroll-' . $settings['direction_type'],
		] );

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['icon'] = 'eicon-star';
		}

		$has_icon = ! empty( $settings['icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'i', 'class', $settings['icon'] );
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
		}

		$icon_attributes = $this->get_render_attribute_string( 'icon' );

		if ( ! $has_icon && ! empty( $settings['selected_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = ! isset( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		$this->add_render_attribute( [
			'container' => [
				'class' => 'pp-image-scroll-container',
			],
			'direction_type' => [
				'class' => [ 'pp-image-scroll-image', 'pp-image-scroll-' . $settings['direction_type'] ],
			],
		] );
		?>
		<div class="pp-image-scroll-wrap">
			<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'container' ) ); ?>>
				<?php if ( ! empty( $settings['icon'] ) || ( ! empty( $settings['selected_icon']['value'] ) && $is_new ) ) { ?>
					<div class="pp-image-scroll-content">
						<span <?php echo wp_kses_post( $this->get_render_attribute_string( 'icon' ) ); ?>>
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $settings['selected_icon'], [ 'aria-hidden' => 'true' ] );
							} elseif ( ! empty( $settings['icon'] ) ) {
								?><i <?php echo wp_kses_post( $this->get_render_attribute_string( 'i' ) ); ?>></i><?php
							}
							?>
						</span>
					</div>
				<?php } ?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'direction_type' ) ); ?>>
					<?php if ( 'yes' === $settings['overlay'] ) { ?>
						<div class="pp-image-scroll-overlay pp-media-overlay">
					<?php } ?>
					<?php if ( ! empty( $link_url ) ) { ?>
							<a <?php echo wp_kses_post( $this->get_render_attribute_string( 'link' ) ); ?>></a>
					<?php } ?>
					<?php if ( 'yes' === $settings['overlay'] ) { ?>
						</div> 
					<?php } ?>

					<?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings ) ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render scroll image widgets output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.0.3
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			var direction = settings.direction_type,
				reverse = settings.reverse,
				url,
				iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
				migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );

			if ( settings.icon || settings.selected_icon.value ) {
				view.addRenderAttribute( 'icon', 'class', [
					'pp-image-scroll-icon',
					'pp-icon',
					'pp-mouse-scroll-' + settings.direction_type,
				] );
			}

			if ( settings.link.url ) {
				view.addRenderAttribute( 'link', 'class', 'pp-image-scroll-link pp-media-content' );
				url = settings.link.url;
				view.addRenderAttribute( 'link', 'href',  url );
			}

			view.addRenderAttribute( 'container', 'class', 'pp-image-scroll-container' );

			view.addRenderAttribute( 'direction_type', 'class', 'pp-image-scroll-image pp-image-scroll-' + direction );
		#>
		<div class="pp-image-scroll-wrap">
			<div {{{ view.getRenderAttributeString('container') }}}>
				<# if ( settings.icon || settings.selected_icon ) { #>
					<div class="pp-image-scroll-content">   
						<span {{{ view.getRenderAttributeString('icon') }}}>
							<# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
							{{{ iconHTML.value }}}
							<# } else { #>
								<i class="{{ settings.icon }}" aria-hidden="true"></i>
							<# } #>
						</span>
					</div>
				<# } #>
				<div {{{ view.getRenderAttributeString('direction_type') }}}>
					<# if( 'yes' == settings.overlay ) { #>
						<div class="pp-image-scroll-overlay pp-media-overlay">
					<# }
					if ( settings.link.url ) { #>
						<a {{{ view.getRenderAttributeString('link') }}}></a>
					<# }
					if( 'yes' == settings.overlay ) { #>
						</div> 
					<# }

					var image = {
						id: settings.image.id,
						url: settings.image.url,
						size: settings.image_size,
						dimension: settings.image_custom_dimension,
						model: view.getEditModel()
					};
					var image_url = elementor.imagesManager.getImageUrl( image );
					#>
					<img src="{{ _.escape( image_url ) }}" />
				</div>
			</div>
		</div>
		<?php
	}
}
