<?php
namespace PowerpackElementsLite\Modules\Logos\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

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
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

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

	/**
	 * Register logo grid widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_logo_grid',
			array(
				'label' => __( 'Logo Grid', 'powerpack' ),
			)
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'items_repeater' );

		$repeater->start_controls_tab( 'tab_content', array( 'label' => __( 'Content', 'powerpack' ) ) );

			$repeater->add_control(
				'logo_image',
				array(
					'label'   => __( 'Upload Logo Image', 'powerpack' ),
					'type'    => Controls_Manager::MEDIA,
					'dynamic' => array(
						'active' => true,
					),
					'default' => array(
						'url' => Utils::get_placeholder_image_src(),
					),
				)
			);

			$repeater->add_control(
				'title',
				array(
					'label'   => __( 'Title', 'powerpack' ),
					'type'    => Controls_Manager::TEXT,
					'dynamic' => array(
						'active' => true,
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
					'placeholder' => 'https://www.your-link.com',
					'default'     => array(
						'url' => '',
					),
				)
			);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'tab_style', array( 'label' => __( 'Style', 'powerpack' ) ) );

			$repeater->add_control(
				'custom_style',
				array(
					'label'        => __( 'Custom Style', 'powerpack' ),
					'type'         => Controls_Manager::SWITCHER,
					'description'  => __( 'Add custom styles which will affect only this item', 'powerpack' ),
					'default'      => '',
					'label_on'     => __( 'On', 'powerpack' ),
					'label_off'    => __( 'Off', 'powerpack' ),
					'return_value' => 'yes',
				)
			);

			$repeater->add_control(
				'custom_logo_wrapper_bg',
				array(
					'label'     => __( 'Background Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-logo-grid-item-custom' => 'background-color: {{VALUE}}',
					),
					'condition' => array(
						'custom_style' => 'yes',
					),
				)
			);

			$repeater->add_control(
				'custom_logo_wrapper_border_color',
				array(
					'label'     => __( 'Border Color', 'powerpack' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-logo-grid-item-custom' => 'border-color: {{VALUE}}',
					),
					'condition' => array(
						'custom_style' => 'yes',
					),
				)
			);

			$repeater->add_control(
				'custom_logo_border_width',
				array(
					'label'      => __( 'Border Width', 'powerpack' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => array( 'px' ),
					'range'      => array(
						'px' => array(
							'min' => 0,
							'max' => 20,
						),
					),
					'selectors'  => array(
						'{{WRAPPER}} {{CURRENT_ITEM}}.pp-logo-grid-item-custom' => 'border-width: {{SIZE}}{{UNIT}};',
					),
					'condition'  => array(
						'custom_style' => 'yes',
					),
				)
			);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'pp_logos',
			array(
				'label'       => __( 'Add Logos', 'powerpack' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'logo_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
					),
					array(
						'logo_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
					),
					array(
						'logo_image' => array(
							'url' => Utils::get_placeholder_image_src(),
						),
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => __( 'Logo Image', 'powerpack' ),
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'     => __( 'Title HTML Tag', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h4',
				'options'   => array(
					'h1'   => __( 'H1', 'powerpack' ),
					'h2'   => __( 'H2', 'powerpack' ),
					'h3'   => __( 'H3', 'powerpack' ),
					'h4'   => __( 'H4', 'powerpack' ),
					'h5'   => __( 'H5', 'powerpack' ),
					'h6'   => __( 'H6', 'powerpack' ),
					'div'  => __( 'div', 'powerpack' ),
					'span' => __( 'span', 'powerpack' ),
					'p'    => __( 'p', 'powerpack' ),
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'              => __( 'Columns', 'powerpack' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '3',
				'tablet_default'     => '2',
				'mobile_default'     => '1',
				'options'            => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
					'6' => '6',
				),
				'prefix_class'       => 'elementor-grid%s-',
				'frontend_available' => true,
			)
		);

		$this->add_responsive_control(
			'logos_spacing',
			array(
				'label'      => __( 'Logos Gap', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'(desktop){{WRAPPER}} .pp-grid-item-wrap'       => 'width: calc( ( 100% - (({{columns.SIZE}} - 1) * {{SIZE}}{{UNIT}}) ) / {{columns.SIZE}} ); margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
					'(tablet){{WRAPPER}} .pp-grid-item-wrap'        => 'width: calc( ( 100% - (({{columns_tablet.SIZE}} - 1) * {{SIZE}}{{UNIT}}) ) / {{columns_tablet.SIZE}} ); margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
					'(mobile){{WRAPPER}} .pp-grid-item-wrap'        => 'width: calc( ( 100% - (({{columns_mobile.SIZE}} - 1) * {{SIZE}}{{UNIT}}) ) / {{columns_mobile.SIZE}} ); margin-right: {{SIZE}}{{UNIT}}; margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'logos_vertical_align',
			array(
				'label'                => __( 'Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'default'              => 'top',
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-logo-grid .pp-grid-item-wrap' => 'align-items: {{VALUE}};',
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
			)
		);

		$this->add_control(
			'logos_horizontal_align',
			array(
				'label'                => __( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'left'   => array(
						'title' => __( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'              => 'center',
				'selectors_dictionary' => array(
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-logo-grid .pp-grid-item-wrap, {{WRAPPER}} .pp-logo-grid .pp-grid-item' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'image',
				'label'   => __( 'Image Size', 'powerpack' ),
				'default' => 'full',
			)
		);

		$this->add_responsive_control(
			'logos_width',
			array(
				'label'      => __( 'Image Width', 'powerpack' ),
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
					'{{WRAPPER}} .pp-grid-item img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Docs Links
		 *
		 * @since 1.4.8
		 * @access protected
		 */
		$this->start_controls_section(
			'section_help_docs',
			array(
				'label' => __( 'Help Docs', 'powerpack' ),
			)
		);

		$this->add_control(
			'help_doc_1',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/logo-grid/logo-grid-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			)
		);

		$this->end_controls_section();

		if ( ! is_pp_elements_active() ) {
			/**
			 * Content Tab: Upgrade PowerPack
			 *
			 * @since 1.2.9.4
			 */
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

		/*
		-----------------------------------------------------------------------------------*/
		/*
		  STYLE TAB
		/*-----------------------------------------------------------------------------------*/

		$this->start_controls_section(
			'section_logos_style',
			array(
				'label' => __( 'Logos', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs( 'tabs_logos_style' );

		$this->start_controls_tab(
			'tab_logos_normal',
			array(
				'label' => __( 'Normal', 'powerpack' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'logo_bg',
				'label'    => __( 'Background', 'powerpack' ),
				'types'    => array( 'none', 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pp-grid-item-wrap',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'logo_border',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-grid-item-wrap',
			)
		);

		$this->add_control(
			'logo_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-grid-item-wrap, {{WRAPPER}} .pp-grid-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'logo_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-grid-item-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'grayscale_normal',
			array(
				'label'        => __( 'Grayscale', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'opacity_normal',
			array(
				'label'     => __( 'Opacity', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-grid-item img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pp_logo_box_shadow_normal',
				'selector'  => '{{WRAPPER}} .pp-grid-item-wrap',
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_logos_hover',
			array(
				'label' => __( 'Hover', 'powerpack' ),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'logos_bg_hover',
				'label'    => __( 'Background', 'powerpack' ),
				'types'    => array( 'none', 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .pp-grid-item-wrap:hover',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'logo_border_hover',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-grid-item-wrap:hover',
			)
		);

		$this->add_responsive_control(
			'translate',
			array(
				'label'      => __( 'Slide', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => array(
					'px' => array(
						'min'  => -40,
						'max'  => 40,
						'step' => 1,
					),
				),
				'size_units' => '',
				'selectors'  => array(
					'{{WRAPPER}} .pp-grid-item-wrap:hover' => 'transform:translateY({{SIZE}}{{UNIT}})',
				),
			)
		);

		$this->add_control(
			'grayscale_hover',
			array(
				'label'        => __( 'Grayscale', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => __( 'Yes', 'powerpack' ),
				'label_off'    => __( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'opacity_hover',
			array(
				'label'     => __( 'Opacity', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-grid-item:hover img' => 'opacity: {{SIZE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pp_logo_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .pp-grid-item-wrap:hover',
				'separator' => 'before',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_logo_title_style',
			array(
				'label' => __( 'Title', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-logo-grid-title' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'title_spacing',
			array(
				'label'      => __( 'Margin Top', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-logo-grid-title' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'label'    => __( 'Typography', 'powerpack' ),
				'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .pp-logo-grid-title',
			)
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
		$i        = 1;

		$this->add_render_attribute( 'logo-grid', 'class', 'pp-logo-grid pp-elementor-grid clearfix' );

		if ( $settings['grayscale_normal'] == 'yes' ) {
			$this->add_render_attribute( 'logo-grid', 'class', 'grayscale-normal' );
		}

		if ( $settings['grayscale_hover'] == 'yes' ) {
			$this->add_render_attribute( 'logo-grid', 'class', 'grayscale-hover' );
		}
		?>
		<div <?php echo $this->get_render_attribute_string( 'logo-grid' ); ?>>
			<?php
			foreach ( $settings['pp_logos'] as $item ) :
				if ( ! empty( $item['logo_image']['url'] ) ) {

					$this->add_render_attribute( 'logo-grid-item-wrap-' . $i, 'class', 'pp-grid-item-wrap' );
					$this->add_render_attribute( 'logo-grid-item-wrap-' . $i, 'class', 'elementor-repeater-item-' . esc_attr( $item['_id'] ) );

					$this->add_render_attribute( 'logo-grid-item-' . $i, 'class', 'pp-grid-item' );

					if ( $item['custom_style'] == 'yes' ) {
						$this->add_render_attribute( 'logo-grid-item-wrap-' . $i, 'class', 'pp-logo-grid-item-custom' );
					}

					$this->add_render_attribute( 'title' . $i, 'class', 'pp-logo-grid-title' );
					?>
						<div <?php echo $this->get_render_attribute_string( 'logo-grid-item-wrap-' . $i ); ?>>
							<div <?php echo $this->get_render_attribute_string( 'logo-grid-item-' . $i ); ?>>
							<?php
							if ( ! empty( $item['link']['url'] ) ) {

								$this->add_link_attributes( 'logo-link' . $i, $item['link'] );

							}

							if ( ! empty( $item['link']['url'] ) ) {
								echo '<a ' . $this->get_render_attribute_string( 'logo-link' . $i ) . '>';
							}

								echo $this->render_image( $item, $settings );

							if ( ! empty( $item['link']['url'] ) ) {
								echo '</a>';
							}
							?>
							</div>
							<?php
							if ( ! empty( $item['title'] ) ) {
								printf( '<%1$s %2$s>', $settings['title_html_tag'], $this->get_render_attribute_string( 'title' . $i ) );
								if ( ! empty( $item['link']['url'] ) ) {
									echo '<a ' . $this->get_render_attribute_string( 'logo-link' . $i ) . '>';
								}
									echo $item['title'];
								if ( ! empty( $item['link']['url'] ) ) {
									echo '</a>';
								}
									printf( '</%1$s>', $settings['title_html_tag'] );
							}
							?>
						</div>
						<?php
				}
				$i++;
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

		$image_id   = $item['logo_image']['id'];
		$image_size = $instance['image_size'];
		$image_alt  = esc_attr( Control_Media::get_image_alt( $item['logo_image'] ) );

		$image_url = Group_Control_Image_Size::get_attachment_image_src( $image_id, 'image', $instance );

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
	protected function _content_template() {
		?>
		<#
			var i = 1;
		#>
		<div class="pp-logo-grid clearfix">
			<# _.each( settings.pp_logos, function( item ) { #>
				<# if ( item.logo_image.url != '' ) { #>
					<#
						if ( item.custom_style == 'yes' ) {
							var custom_style_class = 'pp-logo-grid-item-custom';
						}
						else {
							var custom_style_class = '';
						}
					#>
					<div class="pp-grid-item-wrap elementor-repeater-item-{{ item._id }} {{ custom_style_class }}">
						<div class="pp-grid-item">
							<# if ( item.link && item.link.url ) { #>
								<a href="{{ item.link.url }}">
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
							<img src="{{{ image_url }}}" alt="{{ item.title }}"/>
								
							<# if ( item.link && item.link.url ) { #>
								</a>
							<# } #>
						</div>
						<#
							if ( item.title != '' ) {
								var title = item.title;

								view.addRenderAttribute( 'title' + i, 'class', 'pp-logo-grid-title' );

								if ( item.link && item.link.url ) {
									title = '<a href="' + item.link.url + '">' + item.title + '</a>';
								}

								var title_html = '<' + settings.title_html_tag  + ' ' + view.getRenderAttributeString( 'title' + i ) + '>' + title + '</' + settings.title_html_tag + '>';

								print( title_html );
							}
						#>
					</div>
				<# } #>
			<# i++ } ); #>
		</div>
		<?php
	}
}
