<?php
namespace PowerpackElementsLite\Modules\Flipbox\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Flipbox Widget
 */
class Flipbox extends Powerpack_Widget {

	/**
	 * Retrieve flipbox widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Flipbox' );
	}

	/**
	 * Retrieve flipbox widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Flipbox' );
	}

	/**
	 * Retrieve flipbox widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Flipbox' );
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return parent::get_widget_keywords( 'Flipbox' );
	}

	/**
	 * Retrieve the list of scripts the logo carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'powerpack-frontend',
		);
	}

	/**
	 * Register counter widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() {

		/*
		-----------------------------------------------------------------------------------*/
		/*
		  CONTENT TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Content Tab: Front
		 */
		$this->start_controls_section(
			'section_front',
			array(
				'label' => esc_html__( 'Front', 'powerpack' ),
			)
		);

		$this->add_control(
			'icon_type',
			array(
				'label'       => esc_html__( 'Icon Type', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'none'  => array(
						'title' => __( 'None', 'powerpack' ),
						'icon'  => 'fa fa-ban',
					),
					'image' => array(
						'title' => __( 'Image', 'powerpack' ),
						'icon'  => 'fa fa-picture-o',
					),
					'icon'  => array(
						'title' => __( 'Icon', 'powerpack' ),
						'icon'  => 'fa fa-star',
					),
				),
				'default'     => 'icon',
			)
		);

		$this->add_control(
			'icon_image',
			array(
				'label'     => esc_html__( 'Choose Image', 'powerpack' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'icon_type' => 'image',
				),
			)
		);

		$this->add_control(
			'select_icon',
			array(
				'label'            => __( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default'          => array(
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				),
				'condition'        => array(
					'icon_type' => 'icon',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail',
				'default'   => 'full',
				'condition' => array(
					'icon_type'        => 'image',
					'icon_image[url]!' => '',
				),
			)
		);

		$this->add_control(
			'title_front',
			array(
				'label'       => esc_html__( 'Title', 'powerpack' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'This is the heading', 'powerpack' ),
				'separator'   => 'before',
			)
		);
		$this->add_control(
			'description_front',
			array(
				'label'       => esc_html__( 'Description', 'powerpack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default'     => __( 'This is the front content. Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'powerpack' ),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Back
		 */
		$this->start_controls_section(
			'section_back',
			array(
				'label' => esc_html__( 'Back', 'powerpack' ),
			)
		);

		$this->add_control(
			'icon_type_back',
			array(
				'label'       => esc_html__( 'Icon Type', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'none'  => array(
						'title' => __( 'None', 'powerpack' ),
						'icon'  => 'fa fa-ban',
					),
					'image' => array(
						'title' => __( 'Image', 'powerpack' ),
						'icon'  => 'fa fa-picture-o',
					),
					'icon'  => array(
						'title' => __( 'Icon', 'powerpack' ),
						'icon'  => 'fa fa-star',
					),
				),
				'default'     => 'icon',
			)
		);

		$this->add_control(
			'icon_image_back',
			array(
				'label'     => esc_html__( 'Flipbox Image', 'powerpack' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'icon_type_back' => 'image',
				),
			)
		);

		$this->add_control(
			'select_icon_back',
			array(
				'label'            => __( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon_back',
				'default'          => array(
					'value'   => 'far fa-snowflake',
					'library' => 'fa-regular',
				),
				'condition'        => array(
					'icon_type_back' => 'icon',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'thumbnail_back',
				'default'   => 'full',
				'condition' => array(
					'icon_type_back'        => 'image',
					'icon_image_back[url]!' => '',
				),
			)
		);

		$this->add_control(
			'title_back',
			array(
				'label'       => esc_html__( 'Title', 'powerpack' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'This is the heading', 'powerpack' ),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'description_back',
			array(
				'label'       => esc_html__( 'Description', 'powerpack' ),
				'type'        => Controls_Manager::TEXTAREA,
				'label_block' => true,
				'default'     => __( 'This is the front content. Click edit button to change this text. Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'powerpack' ),
			)
		);

		$this->add_control(
			'link_type',
			array(
				'label'   => __( 'Link Type', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => array(
					'none'   => __( 'None', 'powerpack' ),
					'title'  => __( 'Title', 'powerpack' ),
					'button' => __( 'Button', 'powerpack' ),
					'box'    => __( 'Box', 'powerpack' ),
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'Link', 'powerpack' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => array(
					'active'     => true,
					'categories' => array(
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					),
				),
				'placeholder' => 'https://www.your-link.com',
				'default'     => array(
					'url' => '#',
				),
				'condition'   => array(
					'link_type!' => 'none',
				),
			)
		);

		$this->add_control(
			'flipbox_button_text',
			array(
				'label'     => __( 'Button Text', 'powerpack' ),
				'type'      => Controls_Manager::TEXT,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => __( 'Get Started', 'powerpack' ),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'select_button_icon',
			array(
				'label'            => __( 'Button Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'button_icon',
				'condition'        => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_icon_position',
			array(
				'label'     => __( 'Icon Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after',
				'options'   => array(
					'after'  => __( 'After', 'powerpack' ),
					'before' => __( 'Before', 'powerpack' ),
				),
				'condition' => array(
					'link_type'                  => 'button',
					'select_button_icon[value]!' => '',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Settings
		 */
		$this->start_controls_section(
			'section_settings',
			array(
				'label' => esc_html__( 'Settings', 'powerpack' ),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => __( 'Height', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-flipbox-container' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-flipbox-back, {{WRAPPER}} .pp-flipbox-front' => 'border-radius: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->add_control(
			'flip_effect',
			array(
				'label'       => esc_html__( 'Flip Effect', 'powerpack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'flip',
				'label_block' => false,
				'options'     => array(
					'flip'     => esc_html__( 'Flip', 'powerpack' ),
					'slide'    => esc_html__( 'Slide', 'powerpack' ),
					'push'     => esc_html__( 'Push', 'powerpack' ),
					'zoom-in'  => esc_html__( 'Zoom In', 'powerpack' ),
					'zoom-out' => esc_html__( 'Zoom Out', 'powerpack' ),
					'fade'     => esc_html__( 'Fade', 'powerpack' ),
				),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'flip_direction',
			array(
				'label'       => esc_html__( 'Flip Direction', 'powerpack' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'left',
				'label_block' => false,
				'options'     => array(
					'left'  => esc_html__( 'Left', 'powerpack' ),
					'right' => esc_html__( 'Right', 'powerpack' ),
					'up'    => esc_html__( 'Top', 'powerpack' ),
					'down'  => esc_html__( 'Bottom', 'powerpack' ),
				),
				'condition'   => array(
					'flip_effect!' => array(
						'fade',
						'zoom-in',
						'zoom-out',
					),
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
				'raw'             => sprintf( __( '%1$s Widget Overview %2$s', 'powerpack' ), '<a href="https://powerpackelements.com/docs/powerpack/widgets/flip-box/flip-box-widget-overview/?utm_source=widget&utm_medium=panel&utm_campaign=userkb" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			)
		);

		$this->end_controls_section();

		if ( ! is_pp_elements_active() ) {
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

		/**
		 * Style Tab: Front
		 */
		$this->start_controls_section(
			'section_front_style',
			array(
				'label' => esc_html__( 'Front', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'padding_front',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-flipbox-front .pp-flipbox-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_alignment_front',
			array(
				'label'       => esc_html__( 'Alignment', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'     => 'center',
				'selectors'   => array(
					'{{WRAPPER}} .pp-flipbox-front .pp-flipbox-overlay' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'vertical_position_front',
			array(
				'label'                => __( 'Vertical Position', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-flipbox-front .pp-flipbox-overlay' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'background_front',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pp-flipbox-front',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'border_front',
				'label'     => esc_html__( 'Border Style', 'powerpack' ),
				'selector'  => '{{WRAPPER}} .pp-flipbox-front',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'overlay_front',
			array(
				'label'     => esc_html__( 'Overlay', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'overlay_front',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .pp-flipbox-front .pp-flipbox-overlay',
			)
		);

		$this->add_control(
			'image_style_heading_front',
			array(
				'label'     => esc_html__( 'Image', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'icon_type' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing_front',
			array(
				'label'     => __( 'Spacing', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-icon-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'icon_type' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'image_size_front',
			array(
				'label'     => esc_html__( 'Size (%)', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-icon-image > img' => 'width: {{SIZE}}%;',
				),
				'condition' => array(
					'icon_type' => 'image',
				),
			)
		);

		$this->add_control(
			'icon_style_heading_front',
			array(
				'label'     => esc_html__( 'Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'icon_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_color_front',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-icon-image i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-flipbox-icon-image svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'icon_type' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size_front',
			array(
				'label'     => __( 'Icon Size', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 40,
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-icon-image, {{WRAPPER}} .pp-flipbox-icon-image i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'icon_type' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing_front',
			array(
				'label'     => __( 'Spacing', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-icon-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'icon_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'title_heading_front',
			array(
				'label'     => esc_html__( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color_front',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-front .pp-flipbox-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography_front',
				'selector' => '{{WRAPPER}} .pp-flipbox-front .pp-flipbox-heading',
			)
		);

		$this->add_control(
			'description_heading_front',
			array(
				'label'     => esc_html__( 'Description', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'description_color_front',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-front .pp-flipbox-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography_front',
				'selector' => '{{WRAPPER}} .pp-flipbox-front .pp-flipbox-content',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Back
		 */
		$this->start_controls_section(
			'section_back_style',
			array(
				'label' => esc_html__( 'Back', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'padding_back',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-flipbox-back .pp-flipbox-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'content_alignment_back',
			array(
				'label'       => esc_html__( 'Alignment', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'     => 'center',
				'selectors'   => array(
					'{{WRAPPER}} .pp-flipbox-back .pp-flipbox-overlay' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'vertical_position_back',
			array(
				'label'                => __( 'Vertical Position', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => __( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => __( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-flipbox-back .pp-flipbox-overlay' => 'justify-content: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'background_back',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pp-flipbox-back',
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'      => 'border_back',
				'label'     => esc_html__( 'Border Style', 'powerpack' ),
				'selector'  => '{{WRAPPER}} .pp-flipbox-back',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'overlay_back',
			array(
				'label'     => esc_html__( 'Overlay', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'overlay_back',
				'types'    => array( 'classic', 'gradient' ),
				'exclude'  => array( 'image' ),
				'selector' => '{{WRAPPER}} .pp-flipbox-back .pp-flipbox-overlay',
			)
		);

		$this->add_control(
			'image_style_heading_back',
			array(
				'label'     => esc_html__( 'Image', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'icon_type_back' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'image_spacing_back',
			array(
				'label'     => __( 'Spacing', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-icon-image-back' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'icon_type_back' => 'image',
				),
			)
		);

		$this->add_responsive_control(
			'image_size_back',
			array(
				'label'     => esc_html__( 'Size (%)', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => '',
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-icon-image-back > img' => 'width: {{SIZE}}%;',
				),
				'condition' => array(
					'icon_type_back' => 'image',
				),
			)
		);

		$this->add_control(
			'icon_style_heading_back',
			array(
				'label'     => esc_html__( 'Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'icon_type_back' => 'icon',
				),
			)
		);

		$this->add_control(
			'icon_color_back',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-icon-image-back i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-flipbox-icon-image-back svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'icon_type_back' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size_back',
			array(
				'label'     => __( 'Icon Size', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 40,
					'unit' => 'px',
				),
				'range'     => array(
					'px' => array(
						'min' => 6,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-icon-image-back, {{WRAPPER}} .pp-flipbox-icon-image-back i' => 'font-size: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'icon_type_back' => 'icon',
				),
			)
		);

		$this->add_responsive_control(
			'icon_spacing_back',
			array(
				'label'     => __( 'Spacing', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-icon-image-back' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'icon_type_back' => 'icon',
				),
			)
		);

		$this->add_control(
			'title_heading_back',
			array(
				'label'     => esc_html__( 'Title', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'title_color_back',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#fff',
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-back .pp-flipbox-heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography_back',
				'selector' => '{{WRAPPER}} .pp-flipbox-back .pp-flipbox-heading',
			)
		);

		$this->add_control(
			'description_heading_back',
			array(
				'label'     => esc_html__( 'Description', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'description_color_back',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-back .pp-flipbox-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'description_typography_back',
				'selector' => '{{WRAPPER}} .pp-flipbox-back .pp-flipbox-content',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Button
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_info_box_button_style',
			array(
				'label'     => __( 'Button', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_size',
			array(
				'label'     => __( 'Size', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'md',
				'options'   => array(
					'xs' => __( 'Extra Small', 'powerpack' ),
					'sm' => __( 'Small', 'powerpack' ),
					'md' => __( 'Medium', 'powerpack' ),
					'lg' => __( 'Large', 'powerpack' ),
					'xl' => __( 'Extra Large', 'powerpack' ),
				),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_responsive_control(
			'button_spacing',
			array(
				'label'     => __( 'Spacing', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 15,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-button' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			array(
				'label'     => __( 'Normal', 'powerpack' ),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_bg_color_normal',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-button' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_text_color_normal',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-flipbox-button .pp-button-icon svg' => 'fill: {{VALUE}}',
				),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'button_border_normal',
				'label'       => __( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-flipbox-button',
				'condition'   => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_border_radius',
			array(
				'label'      => __( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-flipbox-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'button_typography',
				'label'     => __( 'Typography', 'powerpack' ),
				'scheme'    => Scheme_Typography::TYPOGRAPHY_4,
				'selector'  => '{{WRAPPER}} .pp-flipbox-button',
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_responsive_control(
			'button_padding',
			array(
				'label'      => __( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-flipbox-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-flipbox-button',
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'info_box_button_icon_heading',
			array(
				'label'     => __( 'Button Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'link_type'                  => 'button',
					'select_button_icon[value]!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'button_icon_margin',
			array(
				'label'       => __( 'Margin', 'powerpack' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array( 'px', '%' ),
				'placeholder' => array(
					'top'    => '',
					'right'  => '',
					'bottom' => '',
					'left'   => '',
				),
				'condition'   => array(
					'link_type'                  => 'button',
					'select_button_icon[value]!' => '',
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-info-box .pp-button-icon' => 'margin-top: {{TOP}}{{UNIT}}; margin-left: {{LEFT}}{{UNIT}}; margin-right: {{RIGHT}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			array(
				'label'     => __( 'Hover', 'powerpack' ),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-button:hover' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_text_color_hover',
			array(
				'label'     => __( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-button:hover' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_border_color_hover',
			array(
				'label'     => __( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-flipbox-button:hover' => 'border-color: {{VALUE}}',
				),
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_control(
			'button_animation',
			array(
				'label'     => __( 'Animation', 'powerpack' ),
				'type'      => Controls_Manager::HOVER_ANIMATION,
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'button_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .pp-flipbox-button:hover',
				'condition' => array(
					'link_type' => 'button',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$flipbox_if_html_tag = 'div';
		$this->add_render_attribute( 'flipbox-card', 'class', 'pp-flipbox-flip-card' );

		$this->add_render_attribute(
			'flipbox-container',
			array(
				'class' => array(
					'pp-flipbox-container',
					'pp-animate-' . esc_attr( $settings['flip_effect'] ),
					'pp-direction-' . esc_attr( $settings['flip_direction'] ),
				),
			)
		);

		?>

	<div <?php echo $this->get_render_attribute_string( 'flipbox-container' ); ?>>

		<div <?php echo $this->get_render_attribute_string( 'flipbox-card' ); ?>>

			<?php
				// Front
				$this->render_front();

				// Back
				$this->render_back();
			?>

		</div>
	</div>
		<?php
	}

	protected function render_front() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'icon-front', 'class', 'pp-flipbox-icon-image' );

		if ( 'icon' === $settings['icon_type'] ) {
			$this->add_render_attribute( 'icon-front', 'class', 'pp-icon' );
		}

		if ( ! isset( $settings['icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['icon'] = 'fa fa-star';
		}

		$has_icon = ! empty( $settings['icon'] );

		if ( $has_icon ) {
			$this->add_render_attribute( 'front-i', 'class', $settings['icon'] );
			$this->add_render_attribute( 'front-i', 'aria-hidden', 'true' );
		}

		if ( ! $has_icon && ! empty( $settings['select_icon']['value'] ) ) {
			$has_icon = true;
		}
		$migrated = isset( $settings['__fa4_migrated']['select_icon'] );
		$is_new   = ! isset( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		?>
		<div class="pp-flipbox-front">
			<div class="pp-flipbox-overlay">
				<div class="pp-flipbox-inner">
					<div <?php echo $this->get_render_attribute_string( 'icon-front' ); ?>>
						<?php if ( 'icon' === $settings['icon_type'] && $has_icon ) { ?>
							<?php
							if ( $is_new || $migrated ) {
								Icons_Manager::render_icon( $settings['select_icon'], array( 'aria-hidden' => 'true' ) );
							} elseif ( ! empty( $settings['icon'] ) ) {
								?>
								<i <?php echo $this->get_render_attribute_string( 'front-i' ); ?>></i>
								<?php
							}
							?>
						<?php } elseif ( 'image' === $settings['icon_type'] ) { ?>
							<?php
								$flipbox_image     = $settings['icon_image'];
								$flipbox_image_url = Group_Control_Image_Size::get_attachment_image_src( $flipbox_image['id'], 'thumbnail', $settings );
								$flipbox_image_url = ( empty( $flipbox_image_url ) ) ? $flipbox_image['url'] : $flipbox_image_url;
							?>
							<?php if ( $flipbox_image_url ) { ?>
								<img src="<?php echo esc_url( $flipbox_image_url ); ?>" alt="">
							<?php } ?>
						<?php } ?>
					</div>

					<h3 class="pp-flipbox-heading">
						<?php echo esc_html__( $settings['title_front'], 'powerpack' ); ?>
					</h3>

					<div class="pp-flipbox-content">
					   <?php echo __( $settings['description_front'], 'powerpack' ); ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_back() {
		$settings = $this->get_settings_for_display();

		$pp_title_html_tag = 'h3';

		$this->add_render_attribute( 'title-container', 'class', 'pp-flipbox-heading' );

		$flipbox_image_back     = $settings['icon_image_back'];
		$flipbox_back_image_url = Group_Control_Image_Size::get_attachment_image_src( $flipbox_image_back['id'], 'thumbnail_back', $settings );
		$flipbox_back_image_url = ( empty( $flipbox_back_image_url ) ) ? $flipbox_image_back['url'] : $flipbox_back_image_url;

		if ( $settings['icon_type_back'] != 'none' ) {

			$this->add_render_attribute( 'icon-back', 'class', 'pp-flipbox-icon-image-back' );

			if ( ! isset( $settings['icon_back'] ) && ! Icons_Manager::is_migration_allowed() ) {
				// add old default
				$settings['icon'] = 'fa fa-snowflake-o';
			}

			$has_icon_back = ! empty( $settings['icon_back'] );

			if ( $has_icon_back ) {
				$this->add_render_attribute( 'back-i', 'class', $settings['icon_back'] );
				$this->add_render_attribute( 'back-i', 'aria-hidden', 'true' );
			}

			if ( ! $has_icon_back && ! empty( $settings['select_icon_back']['value'] ) ) {
				$has_icon_back = true;
			}
			$migrated_icon_back = isset( $settings['__fa4_migrated']['select_icon_back'] );
			$is_new_icon_back   = ! isset( $settings['icon_back'] ) && Icons_Manager::is_migration_allowed();

			if ( 'image' == $settings['icon_type_back'] ) {
				$this->add_render_attribute(
					'icon-image-back',
					array(
						'src' => $flipbox_back_image_url,
						'alt' => 'flipbox-image',
					)
				);
			} elseif ( 'icon' == $settings['icon_type_back'] ) {
				$this->add_render_attribute( 'icon-back', 'class', 'pp-icon' );
			}
		}

		if ( $settings['link_type'] != 'none' ) {
			if ( ! empty( $settings['link']['url'] ) ) {
				if ( $settings['link_type'] == 'title' ) {

					$pp_title_html_tag = 'a';

					$this->add_render_attribute( 'title-container', 'class', 'pp-flipbox-linked-title' );

					$this->add_link_attributes( 'title-container', $settings['link'] );

				} elseif ( $settings['link_type'] == 'button' ) {

					$this->add_render_attribute( 'button', 'class', array( 'elementor-button', 'pp-flipbox-button', 'elementor-size-' . $settings['button_size'] ) );

					$this->add_link_attributes( 'button', $settings['link'] );

				}
			}
		}
		?>
		<div class="pp-flipbox-back">
			<?php
			if ( $settings['link_type'] == 'box' && $settings['link']['url'] != '' ) {
				$this->add_render_attribute( 'box-link', 'class', 'pp-flipbox-box-link' );

				$this->add_link_attributes( 'box-link', $settings['link'] );
				?>
				<a <?php echo $this->get_render_attribute_string( 'box-link' ); ?>></a>
			<?php } ?>
			<div class="pp-flipbox-overlay">
				<div class="pp-flipbox-inner">
					<?php if ( 'none' != $settings['icon_type_back'] ) { ?>
						<div <?php echo $this->get_render_attribute_string( 'icon-back' ); ?>>
							<?php if ( 'image' == $settings['icon_type_back'] ) { ?>
								<img <?php echo $this->get_render_attribute_string( 'icon-image-back' ); ?>>
							<?php } elseif ( 'icon' == $settings['icon_type_back'] && $has_icon_back ) { ?>
								<?php
								if ( $is_new_icon_back || $migrated_icon_back ) {
									Icons_Manager::render_icon( $settings['select_icon_back'], array( 'aria-hidden' => 'true' ) );
								} elseif ( ! empty( $settings['icon_back'] ) ) {
									?>
									<i <?php echo $this->get_render_attribute_string( 'back-i' ); ?>></i>
									<?php
								}
								?>
							<?php } ?>
						</div>
					<?php } ?>

					<?php if ( $settings['title_back'] ) { ?>
						<<?php echo $pp_title_html_tag,' ', $this->get_render_attribute_string( 'title-container' ); ?>>
							<?php echo esc_html__( $settings['title_back'], 'powerpack' ); ?>
						</<?php echo $pp_title_html_tag; ?>>
					<?php } ?>

					<div class="pp-flipbox-content">
					   <?php echo __( $settings['description_back'], 'powerpack' ); ?>
					</div>

					<?php if ( $settings['link_type'] == 'button' && ! empty( $settings['flipbox_button_text'] ) ) : ?>
						<a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
							<?php if ( 'before' == $settings['button_icon_position'] ) : ?>
								<?php $this->render_button_icon(); ?>
							<?php endif; ?>
							<?php echo esc_attr( $settings['flipbox_button_text'] ); ?>
							<?php if ( 'after' == $settings['button_icon_position'] ) : ?>
								<?php $this->render_button_icon(); ?>
							<?php endif; ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}

	protected function render_button_icon() {
		$settings = $this->get_settings_for_display();

		if ( ! isset( $settings['button_icon'] ) && ! Icons_Manager::is_migration_allowed() ) {
			// add old default
			$settings['icon'] = '';
		}

		$has_button_icon = ! empty( $settings['button_icon'] );

		if ( $has_button_icon ) {
			$this->add_render_attribute( 'back-i', 'class', $settings['button_icon'] );
			$this->add_render_attribute( 'back-i', 'aria-hidden', 'true' );
		}

		if ( ! $has_button_icon && ! empty( $settings['select_button_icon']['value'] ) ) {
			$has_button_icon = true;
		}
		$migrated_button_icon = isset( $settings['__fa4_migrated']['select_button_icon'] );
		$is_new_button_icon   = ! isset( $settings['button_icon'] ) && Icons_Manager::is_migration_allowed();

		if ( 'image' == $settings['icon_type_back'] ) {
			$this->add_render_attribute(
				'icon-image-back',
				array(
					'src' => $flipbox_back_image_url,
					'alt' => 'flipbox-image',
				)
			);
		} elseif ( 'icon' == $settings['icon_type_back'] ) {
			$this->add_render_attribute( 'icon-back', 'class', 'pp-icon' );
		}

		if ( $has_button_icon ) {
			echo '<span class="pp-button-icon">';
			if ( $is_new_button_icon || $migrated_button_icon ) {
				Icons_Manager::render_icon( $settings['select_button_icon'], array( 'aria-hidden' => 'true' ) );
			} elseif ( ! empty( $settings['button_icon'] ) ) {
				?>
				<i <?php echo $this->get_render_attribute_string( 'back-i' ); ?>></i>
				<?php
			}
			echo '</span>';
		}
	}

	protected function content_template() { }
}
