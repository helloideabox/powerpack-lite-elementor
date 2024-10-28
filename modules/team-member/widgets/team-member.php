<?php
namespace PowerpackElementsLite\Modules\TeamMember\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Team Member Widget
 */
class Team_Member extends Powerpack_Widget {

	/**
	 * Retrieve team member widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Team_Member' );
	}

	/**
	 * Retrieve team member widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Team_Member' );
	}

	/**
	 * Retrieve team member widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Team_Member' );
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
		return parent::get_widget_keywords( 'Team_Member' );
	}

	protected function is_dynamic_content(): bool {
		return false;
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
			'widget-pp-team-member'
		];
	}

	/**
	 * Register team member widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access public
	 * @return array Widget scripts dependencies.
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_image_controls();
		$this->register_content_details_controls();
		$this->register_content_social_links_controls();
		$this->register_content_settings_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_content_controls();
		$this->register_style_overlay_controls();
		$this->register_style_image_controls();
		$this->register_style_name_controls();
		$this->register_style_position_controls();
		$this->register_style_description_controls();
		$this->register_style_social_links_controls();
	}

	/**
	 * Register image controls
	 *
	 * @return void
	 */
	protected function register_content_image_controls() {

		$this->start_controls_section(
			'section_image',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
			]
		);

		$this->add_control(
			'image',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
				'type'                  => Controls_Manager::MEDIA,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'image',
				'label'                 => esc_html__( 'Image Size', 'powerpack' ),
				'default'               => 'medium_large',
			]
		);

		$this->add_responsive_control(
			'member_image_width',
			[
				'label'                 => esc_html__( 'Image Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 200,
					'unit' => 'px',
				],
				'range'                 => [
					'px' => [
						'max' => 1200,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register details controls
	 *
	 * @return void
	 */
	protected function register_content_details_controls() {

		$this->start_controls_section(
			'section_details',
			[
				'label'                 => esc_html__( 'Details', 'powerpack' ),
			]
		);

		$this->add_control(
			'team_member_name',
			[
				'label'                 => esc_html__( 'Name', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'John Doe', 'powerpack' ),
			]
		);

		$this->add_control(
			'team_member_position',
			[
				'label'                 => esc_html__( 'Position', 'powerpack' ),
				'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'WordPress Developer', 'powerpack' ),
			]
		);

		$this->add_control(
			'team_member_description_switch',
			[
				'label'                 => esc_html__( 'Show Description', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'team_member_description',
			[
				'label'                 => esc_html__( 'Description', 'powerpack' ),
				'type'                  => Controls_Manager::WYSIWYG,
				'dynamic'               => [
					'active'   => true,
				],
				'default'               => esc_html__( 'Enter member description here which describes the position of member in company', 'powerpack' ),
				'condition'             => [
					'team_member_description_switch' => 'yes',
				],
			]
		);

		$this->add_control(
			'link_type',
			[
				'label'                 => esc_html__( 'Link Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'none',
				'options'               => [
					'none'      => esc_html__( 'None', 'powerpack' ),
					'image'     => esc_html__( 'Image', 'powerpack' ),
					'title'     => esc_html__( 'Title', 'powerpack' ),
				],
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
				'placeholder'           => 'https://www.your-link.com',
				'default'               => [
					'url' => '#',
				],
				'condition'             => [
					'link_type!'   => 'none',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register social links controls
	 *
	 * @return void
	 */
	protected function register_content_social_links_controls() {

		$this->start_controls_section(
			'section_member_social_links',
			[
				'label'                 => esc_html__( 'Social Links', 'powerpack' ),
			]
		);

		$this->add_control(
			'member_social_links',
			[
				'label'                 => esc_html__( 'Show Social Links', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'select_social_icon',
			[
				'label'                 => esc_html__( 'Social Icon', 'powerpack' ),
				'type'                  => Controls_Manager::ICONS,
				'fa4compatibility'      => 'social_icon',
				'recommended' => [
					'fa-brands' => [
						'android',
						'apple',
						'behance',
						'bitbucket',
						'codepen',
						'delicious',
						'deviantart',
						'digg',
						'dribbble',
						'elementor',
						'facebook',
						'flickr',
						'foursquare',
						'free-code-camp',
						'github',
						'gitlab',
						'globe',
						'houzz',
						'instagram',
						'jsfiddle',
						'linkedin',
						'medium',
						'meetup',
						'mixcloud',
						'odnoklassniki',
						'pinterest',
						'product-hunt',
						'reddit',
						'shopping-cart',
						'skype',
						'slideshare',
						'snapchat',
						'soundcloud',
						'spotify',
						'stack-overflow',
						'steam',
						'stumbleupon',
						'telegram',
						'thumb-tack',
						'tripadvisor',
						'tumblr',
						'twitch',
						'twitter',
						'twitter-square',
						'x-twitter',
						'x-twitter-square',
						'viber',
						'vimeo',
						'vk',
						'weibo',
						'weixin',
						'whatsapp',
						'wordpress',
						'xing',
						'yelp',
						'youtube',
						'500px',
					],
					'fa-solid' => [
						'envelope',
						'link',
						'rss',
					],
				],
			]
		);

		$repeater->add_control(
			'social_link',
			[
				'label'                 => esc_html__( 'Social Link', 'powerpack' ),
				'type'                  => Controls_Manager::URL,
				'dynamic'               => [
					'active'  => true,
				],
				'label_block'           => true,
				'placeholder'           => esc_html__( 'Enter URL', 'powerpack' ),
			]
		);

		$this->add_control(
			'team_member_social',
			[
				'label'                 => esc_html__( 'Add Social Links', 'powerpack' ),
				'type'                  => Controls_Manager::REPEATER,
				'default'               => [
					[
						'select_social_icon' => [
							'value'   => 'fab fa-facebook',
							'library' => 'fa-brands',
						],
						'social_link' => [
							'url' => '#',
						],
					],
					[
						'select_social_icon' => [
							'value'   => 'fab fa-x-twitter',
							'library' => 'fa-brands',
						],
						'social_link' => [
							'url' => '#',
						],
					],
					[
						'select_social_icon' => [
							'value'   => 'fab fa-instagram',
							'library' => 'fa-brands',
						],
						'social_link' => [
							'url' => '#',
						],
					],
				],
				'fields'                => $repeater->get_controls(),
				'title_field' => '<# var migrated = "undefined" !== typeof __fa4_migrated, social = ( "undefined" === typeof social ) ? false : social; #>{{{ elementor.helpers.getSocialNetworkNameFromIcon( select_social_icon, social, true, migrated, true ) }}}',
				'condition'             => [
					'member_social_links' => 'yes',
				],
			]
		);

		$this->add_control(
			'social_links_style',
			[
				'label'   => esc_html__( 'Links Style', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon'   => esc_html__( 'Icon', 'powerpack' ),
					'button' => esc_html__( 'Button', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'shape',
			[
				'label'        => esc_html__( 'Shape', 'powerpack' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'rounded',
				'options'      => [
					'rounded' => esc_html__( 'Rounded', 'powerpack' ),
					'square'  => esc_html__( 'Square', 'powerpack' ),
					'circle'  => esc_html__( 'Circle', 'powerpack' ),
				],
				'prefix_class' => 'elementor-shape-',
				'condition'    => [
					'social_links_style' => 'button',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register settings controls
	 *
	 * @return void
	 */
	protected function register_content_settings_controls() {

		$this->start_controls_section(
			'section_member_box_settings',
			[
				'label'                 => esc_html__( 'Settings', 'powerpack' ),
			]
		);

		$this->add_control(
			'name_html_tag',
			[
				'label'                => esc_html__( 'Name HTML Tag', 'powerpack' ),
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
			]
		);

		$this->add_control(
			'position_html_tag',
			[
				'label'                => esc_html__( 'Position HTML Tag', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'div',
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
			]
		);

		$this->add_control(
			'social_links_position',
			[
				'label'                => esc_html__( 'Social Links Position', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'after_desc',
				'options'              => [
					'before_desc'      => esc_html__( 'Before Description', 'powerpack' ),
					'after_desc'       => esc_html__( 'After Description', 'powerpack' ),
				],
				'condition'            => [
					'member_social_links' => 'yes',
					'overlay_content'     => [ 'none', 'all_content' ],
				],
			]
		);

		$this->add_control(
			'overlay_content',
			[
				'label'                => esc_html__( 'Overlay Content', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'none',
				'options'              => [
					'none'         => esc_html__( 'None', 'powerpack' ),
					'social_icons' => esc_html__( 'Social Icons', 'powerpack' ),
					'content'      => esc_html__( 'Description', 'powerpack' ),
					'all_content'  => esc_html__( 'Description + Social Icons', 'powerpack' ),
				],
			]
		);

		$this->add_control(
			'member_title_divider',
			[
				'label'                 => esc_html__( 'Divider after Name', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'no',
				'label_on'              => esc_html__( 'Show', 'powerpack' ),
				'label_off'             => esc_html__( 'Hide', 'powerpack' ),
				'return_value'          => 'yes',
			]
		);

		$this->add_control(
			'member_position_divider',
			[
				'label'                 => esc_html__( 'Divider after Position', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'yes',
				'label_on'              => esc_html__( 'Show', 'powerpack' ),
				'label_off'             => esc_html__( 'Hide', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'team_member_position!'  => '',
				],
			]
		);

		$this->add_control(
			'member_description_divider',
			[
				'label'                 => esc_html__( 'Divider after Description', 'powerpack' ),
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => 'hide',
				'label_on'              => esc_html__( 'Show', 'powerpack' ),
				'label_off'             => esc_html__( 'Hide', 'powerpack' ),
				'return_value'          => 'yes',
				'condition'             => [
					'team_member_description_switch'  => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Help Doc Links
	 *
	 * @since 2.4.0
	 * @access protected
	 */
	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Team_Member' );
		if ( ! empty( $help_docs ) ) {

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

	/**
	 * Register content style controls
	 *
	 * @return void
	 */
	protected function register_style_content_controls() {

		$this->start_controls_section(
			'section_content_style',
			[
				'label'                 => esc_html__( 'Content', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'member_box_alignment',
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
				],
				'default'               => 'center',
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-wrapper' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'content_background',
				'label'                 => esc_html__( 'Background', 'powerpack' ),
				'types'                 => [ 'classic', 'gradient' ],
				'separator'             => 'before',
				'selector'              => '{{WRAPPER}} .pp-tm-content-normal',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'member_content_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'separator'             => 'before',
				'selector'              => '{{WRAPPER}} .pp-tm-content',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'content_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-tm-content',
			]
		);

		$this->add_responsive_control(
			'member_box_content_margin',
			[
				'label'                 => esc_html__( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'separator'             => 'before',
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-content-normal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_box_content_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register overlay style controls
	 *
	 * @return void
	 */
	protected function register_style_overlay_controls() {

		$this->start_controls_section(
			'section_member_overlay_style',
			[
				'label'                 => esc_html__( 'Overlay', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'overlay_content!'  => 'none',
				],
			]
		);

		$this->add_responsive_control(
			'overlay_alignment',
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
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-overlay-content-wrap' => 'text-align: {{VALUE}};',
				],
				'condition'             => [
					'overlay_content!'  => 'none',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'overlay_background',
				'types'                 => [ 'classic', 'gradient' ],
				'selector'              => '{{WRAPPER}} .pp-tm-overlay-content-wrap:before',
				'condition'             => [
					'overlay_content!'  => 'none',
				],
			]
		);

		$this->add_control(
			'overlay_opacity',
			[
				'label'                 => esc_html__( 'Opacity', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'range'                 => [
					'px' => [
						'min'   => 0,
						'max'   => 1,
						'step'  => 0.1,
					],
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-overlay-content-wrap:before' => 'opacity: {{SIZE}};',
				],
				'condition'             => [
					'overlay_content!'  => 'none',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register image style controls
	 *
	 * @return void
	 */
	protected function register_style_image_controls() {

		$this->start_controls_section(
			'section_member_image_style',
			[
				'label'                 => esc_html__( 'Image', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'member_image_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-tm-image img',
			]
		);

		$this->add_control(
			'member_image_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-image img, {{WRAPPER}} .pp-tm-overlay-content-wrap:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_image_margin',
			[
				'label'                 => esc_html__( 'Margin', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register name style controls
	 *
	 * @return void
	 */
	protected function register_style_name_controls() {

		$this->start_controls_section(
			'section_member_name_style',
			[
				'label'                 => esc_html__( 'Name', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'member_name_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector'              => '{{WRAPPER}} .pp-tm-name',
			]
		);

		$this->add_control(
			'member_name_text_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'member_name_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 10,
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
					'{{WRAPPER}} .pp-tm-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'name_divider_heading',
			[
				'label'                 => esc_html__( 'Divider', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'member_title_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'name_divider_color',
			[
				'label'                 => esc_html__( 'Divider Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'global'                => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-color: {{VALUE}}',
				],
				'condition'             => [
					'member_title_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'name_divider_style',
			[
				'label'                => esc_html__( 'Divider Style', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'solid',
				'options'              => [
					'solid'     => esc_html__( 'Solid', 'powerpack' ),
					'dotted'    => esc_html__( 'Dotted', 'powerpack' ),
					'dashed'    => esc_html__( 'Dashed', 'powerpack' ),
					'double'    => esc_html__( 'Double', 'powerpack' ),
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-style: {{VALUE}}',
				],
				'condition'             => [
					'member_title_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'name_divider_width',
			[
				'label'                 => esc_html__( 'Divider Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 100,
					'unit' => 'px',
				],
				'range'                 => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-title-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_title_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'name_divider_height',
			[
				'label'                 => esc_html__( 'Divider Height', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 4,
				],
				'range'                 => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_title_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'name_divider_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 10,
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
					'{{WRAPPER}} .pp-tm-title-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_title_divider' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register position style controls
	 *
	 * @return void
	 */
	protected function register_style_position_controls() {

		$this->start_controls_section(
			'section_member_position_style',
			[
				'label'                 => esc_html__( 'Position', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'member_position_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector'              => '{{WRAPPER}} .pp-tm-position',
			]
		);

		$this->add_control(
			'member_position_text_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-position' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'member_position_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 10,
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
					'{{WRAPPER}} .pp-tm-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'position_divider_heading',
			[
				'label'                 => esc_html__( 'Divider', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'member_position_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'position_divider_color',
			[
				'label'                 => esc_html__( 'Divider Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'global'                => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-color: {{VALUE}}',
				],
				'condition'             => [
					'member_position_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'position_divider_style',
			[
				'label'                => esc_html__( 'Divider Style', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'solid',
				'options'              => [
					'solid'     => esc_html__( 'Solid', 'powerpack' ),
					'dotted'    => esc_html__( 'Dotted', 'powerpack' ),
					'dashed'    => esc_html__( 'Dashed', 'powerpack' ),
					'double'    => esc_html__( 'Double', 'powerpack' ),
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-style: {{VALUE}}',
				],
				'condition'             => [
					'member_position_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'position_divider_width',
			[
				'label'                 => esc_html__( 'Divider Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 100,
					'unit' => 'px',
				],
				'range'                 => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-position-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_position_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'position_divider_height',
			[
				'label'                 => esc_html__( 'Divider Height', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 3,
				],
				'range'                 => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_position_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'position_divider_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 10,
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
					'{{WRAPPER}} .pp-tm-position-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'member_position_divider' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register description style controls
	 *
	 * @return void
	 */
	protected function register_style_description_controls() {

		$this->start_controls_section(
			'section_member_description_style',
			[
				'label'                 => esc_html__( 'Description', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'                  => 'member_description_typography',
				'label'                 => esc_html__( 'Typography', 'powerpack' ),
				'global'                => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector'              => '{{WRAPPER}} .pp-tm-description',
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
				],
			]
		);

		$this->add_control(
			'member_description_text_color',
			[
				'label'                 => esc_html__( 'Text Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'global'                => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-description' => 'color: {{VALUE}}',
				],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'member_description_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 10,
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
					'{{WRAPPER}} .pp-tm-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
				],
			]
		);

		$this->add_control(
			'description_divider_heading',
			[
				'label'                 => esc_html__( 'Divider', 'powerpack' ),
				'type'                  => Controls_Manager::HEADING,
				'separator'             => 'before',
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'description_divider_color',
			[
				'label'                 => esc_html__( 'Divider Color', 'powerpack' ),
				'type'                  => Controls_Manager::COLOR,
				'default'               => '',
				'global'                => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-color: {{VALUE}}',
				],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
			]
		);

		$this->add_control(
			'description_divider_style',
			[
				'label'                => esc_html__( 'Divider Style', 'powerpack' ),
				'type'                 => Controls_Manager::SELECT,
				'default'              => 'solid',
				'options'              => [
					'solid'     => esc_html__( 'Solid', 'powerpack' ),
					'dotted'    => esc_html__( 'Dotted', 'powerpack' ),
					'dashed'    => esc_html__( 'Dashed', 'powerpack' ),
					'double'    => esc_html__( 'Double', 'powerpack' ),
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-style: {{VALUE}}',
				],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'description_divider_width',
			[
				'label'                 => esc_html__( 'Divider Width', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 100,
					'unit' => 'px',
				],
				'range'                 => [
					'px' => [
						'max' => 800,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-description-divider' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'description_divider_height',
			[
				'label'                 => esc_html__( 'Divider Height', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 4,
				],
				'range'                 => [
					'px' => [
						'max' => 20,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'description_divider_margin',
			[
				'label'                 => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'               => [
					'size' => 10,
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
					'{{WRAPPER}} .pp-tm-description-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'condition'             => [
					'team_member_description_switch' => 'yes',
					'team_member_description!' => '',
					'member_description_divider' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register social links style controls
	 *
	 * @return void
	 */
	protected function register_style_social_links_controls() {

		$this->start_controls_section(
			'section_member_social_links_style',
			[
				'label'                 => esc_html__( 'Social Links', 'powerpack' ),
				'tab'                   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'member_icons_gap',
			[
				'label'                 => esc_html__( 'Icons Gap', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'max' => 60,
					],
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-social-links li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_icon_size',
			[
				'label'                 => esc_html__( 'Icon Size', 'powerpack' ),
				'type'                  => Controls_Manager::SLIDER,
				'size_units'            => [ 'px', 'em', 'rem', 'custom' ],
				'range'                 => [
					'px' => [
						'max' => 30,
					],
				],
				'default'    => [
					'size' => '14',
					'unit' => 'px',
				],
				'tablet_default'        => [
					'unit' => 'px',
				],
				'mobile_default'        => [
					'unit' => 'px',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'member_icon_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => [
					'default' => esc_html__( 'Official Color', 'powerpack' ),
					'custom'  => esc_html__( 'Custom', 'powerpack' ),
				],
				'condition' => [
					'social_links_style' => 'button',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_links_style' );

		$this->start_controls_tab(
			'tab_links_normal',
			[
				'label'      => esc_html__( 'Normal', 'powerpack' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'social_links_style',
							'operator' => '===',
							'value'    => 'icon',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'social_links_style',
									'operator' => '===',
									'value'    => 'button',
								),
								array(
									'name'     => 'member_icon_color',
									'operator' => '==',
									'value'    => 'custom',
								),
							),
						),
					),
				),
			]
		);

		$this->add_control(
			'member_links_icons_color',
			[
				'label'      => esc_html__( 'Color', 'powerpack' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap svg' => 'fill: {{VALUE}};',
				],
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'social_links_style',
							'operator' => '===',
							'value'    => 'icon',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'social_links_style',
									'operator' => '===',
									'value'    => 'button',
								),
								array(
									'name'     => 'member_icon_color',
									'operator' => '==',
									'value'    => 'custom',
								),
							),
						),
					),
				),
			]
		);

		$this->add_control(
			'member_links_bg_color',
			[
				'label'      => esc_html__( 'Background Color', 'powerpack' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'background-color: {{VALUE}};',
				],
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'social_links_style',
							'operator' => '===',
							'value'    => 'icon',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'social_links_style',
									'operator' => '===',
									'value'    => 'button',
								),
								array(
									'name'     => 'member_icon_color',
									'operator' => '==',
									'value'    => 'custom',
								),
							),
						),
					),
				),
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'member_links_border',
				'label'                 => esc_html__( 'Border', 'powerpack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'separator'             => 'before',
				'selector'              => '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap',
			]
		);

		$this->add_control(
			'member_links_border_radius',
			[
				'label'                 => esc_html__( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'member_links_padding',
			[
				'label'                 => esc_html__( 'Padding', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'separator'             => 'before',
				'selectors'             => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_links_hover',
			[
				'label'      => esc_html__( 'Hover', 'powerpack' ),
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'social_links_style',
							'operator' => '===',
							'value'    => 'icon',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'social_links_style',
									'operator' => '===',
									'value'    => 'button',
								),
								array(
									'name'     => 'member_icon_color',
									'operator' => '==',
									'value'    => 'custom',
								),
							),
						),
					),
				),
			]
		);

		$this->add_control(
			'member_links_icons_color_hover',
			[
				'label'      => esc_html__( 'Color', 'powerpack' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover svg' => 'fill: {{VALUE}};',
				],
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'social_links_style',
							'operator' => '===',
							'value'    => 'icon',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'social_links_style',
									'operator' => '===',
									'value'    => 'button',
								),
								array(
									'name'     => 'member_icon_color',
									'operator' => '==',
									'value'    => 'custom',
								),
							),
						),
					),
				),
			]
		);

		$this->add_control(
			'member_links_bg_color_hover',
			[
				'label'      => esc_html__( 'Background Color', 'powerpack' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'background-color: {{VALUE}};',
				],
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'social_links_style',
							'operator' => '===',
							'value'    => 'icon',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'social_links_style',
									'operator' => '===',
									'value'    => 'button',
								),
								array(
									'name'     => 'member_icon_color',
									'operator' => '==',
									'value'    => 'custom',
								),
							),
						),
					),
				),
			]
		);

		$this->add_control(
			'member_links_border_color_hover',
			[
				'label'      => esc_html__( 'Border Color', 'powerpack' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => [
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'border-color: {{VALUE}};',
				],
				'conditions' => array(
					'relation' => 'or',
					'terms'    => array(
						array(
							'name'     => 'social_links_style',
							'operator' => '===',
							'value'    => 'icon',
						),
						array(
							'relation' => 'and',
							'terms'    => array(
								array(
									'name'     => 'social_links_style',
									'operator' => '===',
									'value'    => 'button',
								),
								array(
									'name'     => 'member_icon_color',
									'operator' => '==',
									'value'    => 'custom',
								),
							),
						),
					),
				),
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render_image() {
		$settings = $this->get_settings_for_display();
		$link_key = 'link';

		if ( ! empty( $settings['image']['url'] ) ) {
			if ( 'image' === $settings['link_type'] && $settings['link']['url'] ) {
				?>
				<a <?php echo wp_kses_post( $this->get_render_attribute_string( $link_key ) ); ?>><?php echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings ) ); ?></a>
				<?php
			} else {
				echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings ) );
			}
		}
	}

	protected function render_name() {
		$settings = $this->get_settings_for_display();

		$member_key = 'team_member_name';
		$link_key   = 'link';

		$this->add_inline_editing_attributes( $member_key, 'none' );
		$this->add_render_attribute( $member_key, 'class', 'pp-tm-name' );

		if ( $settings[ $member_key ] ) {
			if ( 'title' === $settings['link_type'] && $settings['link']['url'] ) {
				$name_html_tag = PP_Helper::validate_html_tag( $settings['name_html_tag'] );
				?>
				<<?php echo esc_html( $name_html_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( $member_key ) ); ?>>
					<a <?php echo wp_kses_post( $this->get_render_attribute_string( $link_key ) ); ?>>
						<?php echo esc_html( $settings['team_member_name'] ); ?>
					</a>
				</<?php echo esc_html( $name_html_tag ); ?>>
				<?php
			} else {
				$name_html_tag = PP_Helper::validate_html_tag( $settings['name_html_tag'] );
				?>
				<<?php echo esc_html( $name_html_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( $member_key ) ); ?>>
						<?php echo esc_html( $settings['team_member_name'] ); ?>
				</<?php echo esc_html( $name_html_tag ); ?>>
				<?php
			}
		}

		if ( 'yes' === $settings['member_title_divider'] ) {
			?>
			<div class="pp-tm-title-divider-wrap">
				<div class="pp-tm-divider pp-tm-title-divider"></div>
			</div>
			<?php
		}
	}

	protected function render_position() {
		$settings = $this->get_settings_for_display();
		$this->add_inline_editing_attributes( 'team_member_position', 'none' );
		$this->add_render_attribute( 'team_member_position', 'class', 'pp-tm-position' );

		if ( $settings['team_member_position'] ) {
			$position_html_tag = PP_Helper::validate_html_tag( $settings['position_html_tag'] );
			?>
			<<?php echo esc_html( $position_html_tag ); ?> <?php echo wp_kses_post( $this->get_render_attribute_string( 'team_member_position' ) ); ?>>
				<?php echo wp_kses_post( $settings['team_member_position'] ); ?>
			</<?php echo esc_html( $position_html_tag ); ?>>
			<?php
		}

		if ( 'yes' === $settings['member_position_divider'] ) {
			?>
			<div class="pp-tm-position-divider-wrap">
				<div class="pp-tm-divider pp-tm-position-divider"></div>
			</div>
			<?php
		}
	}

	protected function render_description() {
		$settings = $this->get_settings_for_display();
		$this->add_inline_editing_attributes( 'team_member_description', 'basic' );
		$this->add_render_attribute( 'team_member_description', 'class', 'pp-tm-description' );

		if ( 'yes' === $settings['team_member_description_switch'] ) {
			if ( $settings['team_member_description'] ) {
				?>
				<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'team_member_description' ) ); ?>>
					<?php echo wp_kses_post( $this->parse_text_editor( $settings['team_member_description'] ) ); ?>
				</div>
			<?php } ?>
			<?php if ( 'yes' === $settings['member_description_divider'] ) { ?>
				<div class="pp-tm-description-divider-wrap">
					<div class="pp-tm-divider pp-tm-description-divider"></div>
				</div>
				<?php
			}
		}
	}

	protected function render_social_links() {
		$settings = $this->get_settings_for_display();
		$i = 1;

		$fallback_defaults = [
			'fa fa-facebook',
			'fa fa-x-twitter',
			'fa fa-instagram',
		];

		$migration_allowed = Icons_Manager::is_migration_allowed();

		// add old default
		if ( ! isset( $item['icon'] ) && ! $migration_allowed ) {
			$item['icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-check';
		}

		$migrated = isset( $item['__fa4_migrated']['select_social_icon'] );
		$is_new = ! isset( $item['icon'] ) && $migration_allowed;
		?>
		<div class="pp-tm-social-links-wrap">
			<ul class="pp-tm-social-links">
				<?php foreach ( $settings['team_member_social'] as $index => $item ) : ?>
					<?php
					$migrated = isset( $item['__fa4_migrated']['select_social_icon'] );
					$is_new = empty( $item['social_icon'] ) && $migration_allowed;
					$social = '';

					// add old default
					if ( empty( $item['social_icon'] ) && ! $migration_allowed ) {
						$item['social_icon'] = isset( $fallback_defaults[ $index ] ) ? $fallback_defaults[ $index ] : 'fa fa-wordpress';
					}

					if ( ! empty( $item['social_icon'] ) ) {
						$social = str_replace( 'fa fa-', '', $item['social_icon'] );
					}

					if ( ( $is_new || $migrated ) && 'svg' !== $item['select_social_icon']['library'] ) {
						$social = explode( ' ', $item['select_social_icon']['value'], 2 );
						if ( empty( $social[1] ) ) {
							$social = '';
						} else {
							$social = str_replace( 'fa-', '', $social[1] );
						}
					}
					if ( 'svg' === $item['select_social_icon']['library'] ) {
						$social = '';
					}

					$social_link_key = 'social_link' . $i;
					$icon_wrap_key   = 'icon_wrap' . $i;

					$this->add_render_attribute( $social_link_key, 'class', 'pp-tm-social-link' );
					$this->add_render_attribute( $icon_wrap_key, 'class', 'pp-tm-social-icon-wrap' );

					if ( 'button' === $settings['social_links_style'] ) {
						$this->add_render_attribute( $icon_wrap_key, 'class', [
							'elementor-icon',
							'elementor-social-icon',
							'elementor-social-icon-' . $social,
						] );
					}

					if ( ! empty( $item['social_link']['url'] ) ) {
						$this->add_link_attributes( $social_link_key, $item['social_link'] );
					}
					?>
					<li>
						<a <?php echo wp_kses_post( $this->get_render_attribute_string( $social_link_key ) ); ?>>
							<span <?php echo wp_kses_post( $this->get_render_attribute_string( $icon_wrap_key ) ); ?>>
								<span class="elementor-screen-only"><?php echo esc_html( ucwords( $social ) ); ?></span>
								<span class="pp-tm-social-icon pp-icon">
								<?php
								if ( $is_new || $migrated ) {
									Icons_Manager::render_icon( $item['select_social_icon'], array( 'aria-hidden' => 'true' ) );
								} else {
									?>
									<i class="<?php echo esc_attr( $item['social_icon'] ); ?>"></i>
								<?php } ?>
								</span>
							</span>
						</a>
					</li>
					<?php $i++;
				endforeach; ?>
			</ul>
		</div>
		<?php
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$link_key = 'link';

		if ( 'none' !== $settings['link_type'] ) {
			if ( ! empty( $settings['link']['url'] ) ) {
				$this->add_link_attributes( $link_key, $settings['link'] );
			}
		}
		?>
		<div class="pp-tm-wrapper">
			<div class="pp-tm">
				<div class="pp-tm-image"> 
					<?php $this->render_image(); ?>

					<?php if ( 'none' !== $settings['overlay_content'] ) { ?>
						<div class="pp-tm-overlay-content-wrap">
							<div class="pp-tm-content">
								<?php
								if ( 'yes' === $settings['member_social_links'] ) {
									if ( 'social_icons' === $settings['overlay_content'] ) {
										$this->render_social_links();
									} elseif ( 'all_content' === $settings['overlay_content'] ) {
										if ( 'before_desc' === $settings['social_links_position'] ) {
											$this->render_social_links();
										}
									}
								}

								if ( 'content' === $settings['overlay_content'] || 'all_content' === $settings['overlay_content'] ) {
									$this->render_description();
								}

								if ( 'yes' === $settings['member_social_links'] && 'all_content' === $settings['overlay_content'] ) {
									if ( 'after_desc' === $settings['social_links_position'] ) {
										$this->render_social_links();
									}
								}
								?>
							</div>
						</div>
					<?php } ?>
				</div>
				<div class="pp-tm-content pp-tm-content-normal">
					<?php
					// Name
					$this->render_name();

					// Position
					$this->render_position();

					if ( 'yes' === $settings['member_social_links'] && ( 'none' === $settings['overlay_content'] || 'content' === $settings['overlay_content'] ) ) {
						if ( 'none' === $settings['overlay_content'] ) {
							if ( 'before_desc' === $settings['social_links_position'] ) {
								$this->render_social_links();
							}
						} else {
							$this->render_social_links();
						}
					}

					if ( 'none' === $settings['overlay_content'] || 'social_icons' === $settings['overlay_content'] ) {
						$this->render_description();
					}

					if ( 'yes' === $settings['member_social_links'] && ( 'none' === $settings['overlay_content'] || 'content' === $settings['overlay_content'] ) ) {
						if ( 'after_desc' === $settings['social_links_position'] && 'none' === $settings['overlay_content'] ) {
							$this->render_social_links();
						}
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render team member widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.4.2
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			function member_image() {
				if ( '' !== settings.image.url ) {
					var image = {
						id: settings.image.id,
						url: settings.image.url,
						size: settings.image_size,
						dimension: settings.image_custom_dimension,
						model: view.getEditModel()
					};

					var image_url = elementor.imagesManager.getImageUrl( image );

					var imageHtml = '<img src="' + _.escape( image_url ) + '" />';

					if ( settings.link_type == 'image' && settings.link.url != '' ) {
						imageHtml = '<a href="' + _.escape( settings.link.url ) + '">' + imageHtml + '</a>';
					}

					print( imageHtml );
				}
			}

			function member_name() {
				if ( settings.team_member_name != '' ) {
					var name = settings.team_member_name;

					view.addRenderAttribute( 'team_member_name', 'class', 'pp-tm-name' );

					view.addInlineEditingAttributes( 'team_member_name' );

					var nameHTMLTag = elementor.helpers.validateHTMLTag( settings.name_html_tag ),
						name_html = '<' + nameHTMLTag  + ' ' + view.getRenderAttributeString( 'team_member_name' ) + '>' + name + '</' + nameHTMLTag + '>';
				}

				if ( settings.link_type == 'title' && settings.link.url != '' ) { #>
					<#
					var target = settings.link.is_external ? ' target="_blank"' : '';
					var nofollow = settings.link.nofollow ? ' rel="nofollow"' : '';
					#>
					<a href="{{ _.escape( settings.link.url ) }}"{{ target }}{{ nofollow }}>
						<# print( name_html ); #>
					</a>
				<# } else {
					print( name_html );
				}

				if ( settings.member_title_divider == 'yes' ) { #>
					<div class="pp-tm-title-divider-wrap">
						<div class="pp-tm-divider pp-tm-title-divider"></div>
					</div>
				<# }
			}

			function member_position() {
				if ( settings.team_member_position != '' ) {
					var position = settings.team_member_position;

					view.addRenderAttribute( 'team_member_position', 'class', 'pp-tm-position' );

					view.addInlineEditingAttributes( 'team_member_position' );

					var positionHTMLTag = elementor.helpers.validateHTMLTag( settings.position_html_tag ),
						position_html = '<' + positionHTMLTag  + ' ' + view.getRenderAttributeString( 'team_member_position' ) + '>' + position + '</' + positionHTMLTag + '>';

					print( position_html );
				}

				if ( settings.member_position_divider == 'yes' ) { #>
					<div class="pp-tm-position-divider-wrap">
						<div class="pp-tm-divider pp-tm-position-divider"></div>
					</div>
				<# }
			}

			function member_description() {
				if ( settings.team_member_description_switch == 'yes' ) {
					if ( settings.team_member_description != '' ) {
						var description = settings.team_member_description;

						view.addRenderAttribute( 'team_member_description', 'class', 'pp-tm-description' );

						view.addInlineEditingAttributes( 'team_member_description', 'advanced' );

						var positionHTMLTag = elementor.helpers.validateHTMLTag( settings.position_html_tag ),
							description_html = '<' + positionHTMLTag  + ' ' + view.getRenderAttributeString( 'team_member_description' ) + '>' + description + '</' + positionHTMLTag + '>';

						print( description_html );
					}

					if ( settings.member_description_divider == 'yes' ) { #>
						<div class="pp-tm-description-divider-wrap">
							<div class="pp-tm-divider pp-tm-description-divider"></div>
						</div>
					<# }
				}
			}

			function member_social_links() { #>
				<# var iconsHTML = {}; #>
				<div class="pp-tm-social-links-wrap">
					<ul class="pp-tm-social-links">
						<# _.each( settings.team_member_social, function( item, index ) {
							var link = item.social_link ? item.social_link.url : '',
								migrated = elementor.helpers.isIconMigrated( item, 'select_social_icon' );
								social = elementor.helpers.getSocialNetworkNameFromIcon( item.select_social_icon, item.social_icon, false, migrated );

								var socialLinkKey = view.getRepeaterSettingKey( 'text', 'social_link', index );
								var iconWrapKey = view.getRepeaterSettingKey( 'text', 'icon_wrap', index );

								view.addRenderAttribute( socialLinkKey, 'class', 'pp-tm-social-link' );
								view.addRenderAttribute( iconWrapKey, 'class', 'pp-tm-social-icon-wrap' );

								if ( 'button' == settings.social_links_style ) {
									view.addRenderAttribute( iconWrapKey, 'class', [
										'elementor-icon',
										'elementor-social-icon',
										'elementor-social-icon-' + social,
									] );
								}

								view.addRenderAttribute( socialLinkKey, 'href', link );
							#>
							<li>
								<# if ( item.social_icon || item.select_social_icon ) { #>
									<a {{{ view.getRenderAttributeString( socialLinkKey ) }}}>
										<span {{{ view.getRenderAttributeString( iconWrapKey ) }}}>
											<span class="pp-tm-social-icon pp-icon">
												<span class="elementor-screen-only">{{{ social }}}</span>
												<#
													iconsHTML[ index ] = elementor.helpers.renderIcon( view, item.select_social_icon, {}, 'i', 'object' );
													if ( ( ! item.social_icon || migrated ) && iconsHTML[ index ] && iconsHTML[ index ].rendered ) { #>
														{{{ iconsHTML[ index ].value }}}
													<# } else { #>
														<i class="{{ item.social_icon }}"></i>
													<# }
												#>
											</span>
										</span>
									</a>
								<# } #>
							</li>
						<# } ); #>
					</ul>
				</div>
		<# } #>

		<div class="pp-tm-wrapper">
			<div class="pp-tm">
				<div class="pp-tm-image"> 
					<# member_image(); #>

					<# if ( settings.overlay_content != 'none' ) { #>
						<div class="pp-tm-overlay-content-wrap">
							<div class="pp-tm-content">
								<#
									if ( settings.member_social_links == 'yes' ) {
										if ( settings.overlay_content == 'social_icons' ) {
											member_social_links();
										} else if ( settings.overlay_content == 'all_content' ) {
											if ( settings.social_links_position == 'before_desc' ) {
												member_social_links();
											}
										}
									}

									if ( settings.overlay_content == 'content' || settings.overlay_content == 'all_content' ) {
										member_description();
									}

									if ( settings.member_social_links == 'yes' && settings.overlay_content == 'all_content' ) {
										if ( settings.social_links_position == 'after_desc' ) {
											member_social_links();
										}
									}
								#>
							</div>
						</div>
					<# } #>
				</div>
				<div class="pp-tm-content pp-tm-content-normal">
					<#
						member_name();
						member_position();

						if ( settings.member_social_links == 'yes' && ( settings.overlay_content == 'none' || settings.overlay_content == 'content' ) ) {
							if ( settings.overlay_content == 'none' ) {
								if ( settings.social_links_position == 'before_desc' ) {
									member_social_links();
								}
							} else {
								member_social_links();
							}
						}

						if ( settings.overlay_content == 'none' || settings.overlay_content == 'social_icons' ) {
							member_description();
						}

						if ( settings.member_social_links == 'yes' && ( settings.overlay_content == 'none' || settings.overlay_content == 'content' ) ) {
							if ( settings.social_links_position == 'after_desc' && settings.overlay_content == 'none' ) {
								member_social_links();
							}
						}
					#>
				</div>
			</div>
		</div>
		<?php
	}
}
