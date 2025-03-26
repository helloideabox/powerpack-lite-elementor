<?php
namespace PowerpackElementsLite\Modules\TeamMember\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Icons_Manager;
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
 * Team Member Carousel Widget
 */
class Team_Member_Carousel extends Powerpack_Widget {

	private static $networks_class_dictionary = [
		'envelope' => [
			'value' => 'fa fa-envelope',
		],
		'phone'    => [
			'value' => 'fa fa-phone-alt',
		],
	];

	private static $networks_icon_mapping = [
		'envelope' => [
			'value'   => 'far fa-envelope',
			'library' => 'fa-regular',
		],
		'phone'    => [
			'value'   => 'fas fa-phone-alt',
			'library' => 'fa-solid',
		],
	];

	/**
	 * Retrieve team member carousel widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Team_Member_Carousel' );
	}

	/**
	 * Retrieve team member carousel widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Team_Member_Carousel' );
	}

	/**
	 * Retrieve team member carousel widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Team_Member_Carousel' );
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
		return parent::get_widget_keywords( 'Team_Member_Carousel' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the team member carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'swiper',
			'pp-carousel',
		);
	}

	/**
	 * Retrieve the list of styles the team member carousel widget depended on.
	 *
	 * Used to set style dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_style_depends() {
		$styles = [ 'e-swiper', 'pp-swiper', 'widget-pp-team-member'];

		if ( Icons_Manager::is_migration_allowed() ) {
			array_push( $styles, 'elementor-icons-fa-regular', 'elementor-icons-fa-solid', 'elementor-icons-fa-brands', );
		}

		return $styles;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! PP_Helper::is_feature_active( 'e_optimized_markup' );
	}

	private static function get_network_icon_data( $network_name ) {
		$prefix = 'fa ';
		$library = '';

		if ( Icons_Manager::is_migration_allowed() ) {
			if ( isset( self::$networks_icon_mapping[ $network_name ] ) ) {
				return self::$networks_icon_mapping[ $network_name ];
			}
			$prefix = 'fab ';
			$library = 'fa-brands';
		}
		if ( isset( self::$networks_class_dictionary[ $network_name ] ) ) {
			return self::$networks_class_dictionary[ $network_name ];
		}

		return [
			'value' => $prefix . 'fa-' . $network_name,
			'library' => $library,
		];
	}

	/**
	 * Register team member carousel widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function register_controls() {

		/*-----------------------------------------------------------------------------------*/
		/* CONTENT TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Content Tab: Team Members
		 */
		$this->start_controls_section(
			'section_team_member',
			array(
				'label' => esc_html__( 'Team Members', 'powerpack' ),
			)
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'team_member_tabs' );

		$repeater->start_controls_tab( 'tab_content', array( 'label' => esc_html__( 'Content', 'powerpack' ) ) );

			$repeater->add_control(
				'team_member_name',
				array(
					'label'   => esc_html__( 'Name', 'powerpack' ),
					'type'    => Controls_Manager::TEXT,
					'dynamic' => array(
						'active' => true,
					),
					'default' => esc_html__( 'John Doe', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'team_member_position',
				array(
					'label'   => esc_html__( 'Position', 'powerpack' ),
					'type'    => Controls_Manager::TEXT,
					'dynamic' => array(
						'active' => true,
					),
					'default' => esc_html__( 'WordPress Developer', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'team_member_description',
				array(
					'label'   => esc_html__( 'Description', 'powerpack' ),
					'type'    => Controls_Manager::TEXTAREA,
					'dynamic' => array(
						'active' => true,
					),
					'default' => esc_html__( 'Enter member description here which describes the position of member in company', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'team_member_image',
				array(
					'label'   => esc_html__( 'Image', 'powerpack' ),
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
				'link_type',
				array(
					'label'   => esc_html__( 'Link Type', 'powerpack' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'none',
					'options' => array(
						'none'  => esc_html__( 'None', 'powerpack' ),
						'image' => esc_html__( 'Image', 'powerpack' ),
						'title' => esc_html__( 'Title', 'powerpack' ),
					),
				)
			);

			$repeater->add_control(
				'link',
				array(
					'label'       => esc_html__( 'Link', 'powerpack' ),
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

		$repeater->end_controls_tab();

		$repeater->start_controls_tab( 'tab_social_links', array( 'label' => esc_html__( 'Social Links', 'powerpack' ) ) );

			$repeater->add_control(
				'facebook_url',
				array(
					'name'        => 'facebook_url',
					'label'       => esc_html__( 'Facebook', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Facebook page or profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'twitter_url',
				array(
					'name'        => 'twitter_url',
					'label'       => esc_html__( 'Twitter', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Twitter profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'instagram_url',
				array(
					'label'       => esc_html__( 'Instagram', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Instagram profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'linkedin_url',
				array(
					'label'       => esc_html__( 'Linkedin', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Linkedin profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'youtube_url',
				array(
					'label'       => esc_html__( 'YouTube', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter YouTube profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'pinterest_url',
				array(
					'label'       => esc_html__( 'Pinterest', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Pinterest profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'dribbble_url',
				array(
					'label'       => esc_html__( 'Dribbble', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Dribbble profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'flickr_url',
				array(
					'label'       => esc_html__( 'Flickr', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Flickr profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'tumblr_url',
				array(
					'label'       => esc_html__( 'Tumblr', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Tumblr profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'tiktok_url',
				array(
					'label'       => esc_html__( 'Tiktok', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Tiktok profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'github_url',
				array(
					'label'       => esc_html__( 'Github', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Github profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'vimeo_url',
				array(
					'label'       => esc_html__( 'Vimeo', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Vimeo profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'xing_url',
				array(
					'label'       => esc_html__( 'Xing', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter Xing profile URL of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'email',
				array(
					'label'       => esc_html__( 'Email', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter email ID of team member', 'powerpack' ),
				)
			);

			$repeater->add_control(
				'phone',
				array(
					'label'       => esc_html__( 'Contact Number', 'powerpack' ),
					'type'        => Controls_Manager::TEXT,
					'dynamic'     => array(
						'active'     => true,
						'categories' => array(
							TagsModule::POST_META_CATEGORY,
						),
					),
					'ai'          => [
						'active' => false,
					],
					'description' => esc_html__( 'Enter contact number of team member', 'powerpack' ),
				)
			);

		$this->add_control(
			'team_member_details',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'team_member_name'     => 'Team Member #1',
						'team_member_position' => 'WordPress Developer',
						'facebook_url'         => '#',
						'twitter_url'          => '#',
						'instagram_url'        => '#',
					),
					array(
						'team_member_name'     => 'Team Member #2',
						'team_member_position' => 'Web Designer',
						'facebook_url'         => '#',
						'twitter_url'          => '#',
						'instagram_url'        => '#',
					),
					array(
						'team_member_name'     => 'Team Member #3',
						'team_member_position' => 'Testing Engineer',
						'facebook_url'         => '#',
						'twitter_url'          => '#',
						'instagram_url'        => '#',
					),
				),
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ team_member_name }}}',
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: General Settings
		 */
		$this->start_controls_section(
			'section_member_box_settings',
			array(
				'label' => esc_html__( 'General Settings', 'powerpack' ),
			)
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'    => 'thumbnail', // Usage: '{name}_size' and '{name}_custom_dimension', in this case 'thumbnail_size' and 'thumbnail_custom_dimension'.,
				'label'   => esc_html__( 'Image Size', 'powerpack' ),
				'default' => 'full',
			)
		);

		$this->add_control(
			'name_html_tag',
			array(
				'label'     => esc_html__( 'Name HTML Tag', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h4',
				'options'   => array(
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
				'separator' => 'before',
			)
		);

		$this->add_control(
			'position_html_tag',
			array(
				'label'   => esc_html__( 'Position HTML Tag', 'powerpack' ),
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
			'member_social_links',
			array(
				'label'        => esc_html__( 'Show Social Icons', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'social_links_position',
			array(
				'label'     => esc_html__( 'Social Icons Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'after_desc',
				'options'   => array(
					'before_desc' => esc_html__( 'Before Description', 'powerpack' ),
					'after_desc'  => esc_html__( 'After Description', 'powerpack' ),
				),
				'condition' => array(
					'member_social_links' => 'yes',
					'overlay_content'     => [ 'none', 'all_content' ],
				),
			)
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
				'condition' => array(
					'member_social_links' => 'yes',
				),
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
					'member_social_links' => 'yes',
					'social_links_style'  => 'button',
				],
			]
		);

		$this->add_control(
			'links_target',
			[
				'label'     => esc_html__( 'Links Target', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '_self',
				'options'   => [
					'_self'  => esc_html__( 'Same Window', 'powerpack' ),
					'_blank' => esc_html__( 'New Window', 'powerpack' ),
				],
				'condition' => array(
					'member_social_links' => 'yes',
				),
			]
		);

		$this->add_control(
			'overlay_content',
			array(
				'label'     => esc_html__( 'Overlay Content', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'none',
				'options'   => array(
					'none'         => esc_html__( 'None', 'powerpack' ),
					'social_icons' => esc_html__( 'Social Icons', 'powerpack' ),
					'content'      => esc_html__( 'Description', 'powerpack' ),
					'all_content'  => esc_html__( 'Description', 'powerpack' ) . ' + ' . esc_html__( 'Social Icons', 'powerpack' ),
				),
				'separator' => 'before',
			)
		);

		$this->add_control(
			'member_title_divider',
			array(
				'label'        => esc_html__( 'Divider after Name', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Show', 'powerpack' ),
				'label_off'    => esc_html__( 'Hide', 'powerpack' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'member_position_divider',
			array(
				'label'        => esc_html__( 'Divider after Position', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'hide',
				'label_on'     => esc_html__( 'Show', 'powerpack' ),
				'label_off'    => esc_html__( 'Hide', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'member_description_divider',
			array(
				'label'        => esc_html__( 'Divider after Description', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'hide',
				'label_on'     => esc_html__( 'Show', 'powerpack' ),
				'label_off'    => esc_html__( 'Hide', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		/**
		 * Content Tab: Carousel Settings
		 */
		$this->start_controls_section(
			'section_slider_settings',
			array(
				'label' => esc_html__( 'Carousel Settings', 'powerpack' ),
			)
		);

		$this->add_responsive_control(
			'items',
			array(
				'label'          => esc_html__( 'Visible Items', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array( 'size' => 3 ),
				'tablet_default' => array( 'size' => 2 ),
				'mobile_default' => array( 'size' => 1 ),
				'range'          => array(
					'px' => array(
						'min'  => 1,
						'max'  => 10,
						'step' => 1,
					),
				),
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'          => esc_html__( 'Items Gap', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => array( 'size' => 10 ),
				'tablet_default' => array( 'size' => 10 ),
				'mobile_default' => array( 'size' => 10 ),
				'range'          => array(
					'px' => array(
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					),
				),
			)
		);

		$this->add_control(
			'slider_speed',
			array(
				'label'       => esc_html__( 'Slider Speed', 'powerpack' ),
				'description' => esc_html__( 'Duration of transition between slides (in ms)', 'powerpack' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => array( 'size' => 600 ),
				'range'       => array(
					'px' => array(
						'min'  => 100,
						'max'  => 3000,
						'step' => 1,
					),
				),
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => esc_html__( 'Autoplay', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'                 => esc_html__( 'Pause on Hover', 'powerpack' ),
				'description'           => '',
				'type'                  => Controls_Manager::SWITCHER,
				'default'               => '',
				'label_on'              => esc_html__( 'Yes', 'powerpack' ),
				'label_off'             => esc_html__( 'No', 'powerpack' ),
				'return_value'          => 'yes',
				'frontend_available'    => true,
				'condition'             => array(
					'autoplay'      => 'yes',
				),
			)
		);

		$this->add_control(
			'pause_on_interaction',
			array(
				'label'              => esc_html__( 'Pause on Interaction', 'powerpack' ),
				'description'        => esc_html__( 'Disables autoplay completely on first interaction with the carousel.', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'label_on'           => esc_html__( 'Yes', 'powerpack' ),
				'label_off'          => esc_html__( 'No', 'powerpack' ),
				'return_value'       => 'yes',
				'condition'          => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'      => esc_html__( 'Autoplay Speed', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array( 'size' => 3000 ),
				'range'      => array(
					'px' => array(
						'min'  => 500,
						'max'  => 5000,
						'step' => 1,
					),
				),
				'condition'  => array(
					'autoplay' => 'yes',
				),
			)
		);

		$this->add_control(
			'infinite_loop',
			array(
				'label'        => esc_html__( 'Infinite Loop', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'grab_cursor',
			array(
				'label'        => esc_html__( 'Grab Cursor', 'powerpack' ),
				'description'  => esc_html__( 'Shows grab cursor when you hover over the slider', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Show', 'powerpack' ),
				'label_off'    => esc_html__( 'Hide', 'powerpack' ),
				'return_value' => 'yes',
				'separator'    => 'before',
			)
		);

		$this->add_control(
			'name_navigation_heading',
			array(
				'label'     => esc_html__( 'Navigation', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'arrows',
			array(
				'label'        => esc_html__( 'Arrows', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'dots',
			array(
				'label'        => esc_html__( 'Pagination', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'pagination_type',
			array(
				'label'     => esc_html__( 'Pagination Type', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'bullets',
				'options'   => array(
					'bullets'  => esc_html__( 'Dots', 'powerpack' ),
					'fraction' => esc_html__( 'Fraction', 'powerpack' ),
				),
				'condition' => array(
					'dots' => 'yes',
				),
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'                 => esc_html__( 'Direction', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'left',
				'options'               => [
					'auto'  => esc_html__( 'Auto', 'powerpack' ),
					'left'  => esc_html__( 'Left', 'powerpack' ),
					'right' => esc_html__( 'Right', 'powerpack' ),
				],
				'separator'             => 'before',
			)
		);

		$this->end_controls_section();

		$help_docs = PP_Config::get_widget_help_links( 'Team_Member_Carousel' );
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

		/*-----------------------------------------------------------------------------------*/
		/* STYLE TAB
		/*-----------------------------------------------------------------------------------*/

		/**
		 * Style Tab: Box Style
		 */
		$this->start_controls_section(
			'section_member_box_style',
			array(
				'label' => esc_html__( 'Box Style', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'member_box_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .pp-tm' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_box_style' );

		$this->start_controls_tab(
			'tab_box_normal',
			array(
				'label' => esc_html__( 'Normal', 'powerpack' ),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'member_container_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-tm',
			)
		);

		$this->add_control(
			'member_container_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-tm' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'member_container_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-tm' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_box_active',
			array(
				'label' => esc_html__( 'Active', 'powerpack' ),
			)
		);

		$this->add_control(
			'member_container_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-slide-active .pp-tm' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Content
		 */
		$this->start_controls_section(
			'section_member_content_style',
			array(
				'label' => esc_html__( 'Content', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'member_box_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-content-normal' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'member_box_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'separator'   => 'before',
				'selector'    => '{{WRAPPER}} .pp-tm-content-normal',
			)
		);

		$this->add_control(
			'member_box_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-tm-content-normal' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'member_box_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-tm-content-normal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'pa_member_box_shadow',
				'selector'  => '{{WRAPPER}} .pp-tm-content-normal',
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Overlay
		 */
		$this->start_controls_section(
			'section_member_overlay_style',
			array(
				'label'     => esc_html__( 'Overlay', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'overlay_content!' => 'none',
				),
			)
		);

		$this->add_responsive_control(
			'overlay_alignment',
			array(
				'label'     => esc_html__( 'Alignment', 'powerpack' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-overlay-content-wrap' => 'text-align: {{VALUE}};',
				),
				'condition' => array(
					'overlay_content!' => 'none',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'      => 'overlay_background',
				'types'     => array( 'classic', 'gradient' ),
				'selector'  => '{{WRAPPER}} .pp-tm-overlay-content-wrap:before',
				'condition' => array(
					'overlay_content!' => 'none',
				),
			)
		);

		$this->add_control(
			'overlay_opacity',
			array(
				'label'     => esc_html__( 'Opacity', 'powerpack' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-overlay-content-wrap:before' => 'opacity: {{SIZE}};',
				),
				'condition' => array(
					'overlay_content!' => 'none',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Image
		 */
		$this->start_controls_section(
			'section_member_image_style',
			array(
				'label' => esc_html__( 'Image', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'member_image_width',
			array(
				'label'          => esc_html__( 'Image Width', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'          => array(
					'px' => array(
						'max' => 1200,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-image img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'member_image_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-tm-image img',
			)
		);

		$this->add_control(
			'member_image_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-tm-image img, {{WRAPPER}} .pp-tm-overlay-content-wrap:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'member_image_margin',
			array(
				'label'          => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 10,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Name
		 */
		$this->start_controls_section(
			'section_member_name_style',
			array(
				'label' => esc_html__( 'Name', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'member_name_typography',
				'label'    => esc_html__( 'Typography', 'powerpack' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				],
				'selector' => '{{WRAPPER}} .pp-tm-name',
			)
		);

		$this->add_control(
			'member_name_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-name' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'member_name_margin',
			array(
				'label'          => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 10,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'name_divider_heading',
			array(
				'label'     => esc_html__( 'Divider', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'name_divider_color',
			array(
				'label'     => esc_html__( 'Divider Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'name_divider_style',
			array(
				'label'     => esc_html__( 'Divider Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'powerpack' ),
					'dotted' => esc_html__( 'Dotted', 'powerpack' ),
					'dashed' => esc_html__( 'Dashed', 'powerpack' ),
					'double' => esc_html__( 'Double', 'powerpack' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-style: {{VALUE}}',
				),
				'condition' => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'name_divider_width',
			array(
				'label'          => esc_html__( 'Divider Width', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 100,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 800,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-title-divider' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'name_divider_height',
			array(
				'label'          => esc_html__( 'Divider Height', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 4,
				),
				'range'          => array(
					'px' => array(
						'max' => 20,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-title-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'name_divider_margin',
			array(
				'label'          => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 10,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-title-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_title_divider' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Position
		 */
		$this->start_controls_section(
			'section_member_position_style',
			array(
				'label' => esc_html__( 'Position', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'member_position_typography',
				'label'    => esc_html__( 'Typography', 'powerpack' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_SECONDARY,
				],
				'selector' => '{{WRAPPER}} .pp-tm-position',
			)
		);

		$this->add_control(
			'member_position_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'   => [
					'default' => Global_Colors::COLOR_SECONDARY,
				],
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-position' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'member_position_margin',
			array(
				'label'          => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 10,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-position' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'position_divider_heading',
			array(
				'label'     => esc_html__( 'Divider', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'position_divider_color',
			array(
				'label'     => esc_html__( 'Divider Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'position_divider_style',
			array(
				'label'     => esc_html__( 'Divider Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'powerpack' ),
					'dotted' => esc_html__( 'Dotted', 'powerpack' ),
					'dashed' => esc_html__( 'Dashed', 'powerpack' ),
					'double' => esc_html__( 'Double', 'powerpack' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-style: {{VALUE}}',
				),
				'condition' => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'position_divider_width',
			array(
				'label'          => esc_html__( 'Divider Width', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 100,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 800,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-position-divider' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'position_divider_height',
			array(
				'label'          => esc_html__( 'Divider Height', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 4,
				),
				'range'          => array(
					'px' => array(
						'max' => 20,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-position-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'position_divider_margin',
			array(
				'label'          => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 10,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-position-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_position_divider' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Description
		 */
		$this->start_controls_section(
			'section_member_description_style',
			array(
				'label' => esc_html__( 'Description', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'member_description_typography',
				'label'    => esc_html__( 'Typography', 'powerpack' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .pp-tm-description',
			)
		);

		$this->add_control(
			'member_description_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-description' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'member_description_margin',
			array(
				'label'          => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 10,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-description' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'description_divider_heading',
			array(
				'label'     => esc_html__( 'Divider', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'member_description_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'description_divider_color',
			array(
				'label'     => esc_html__( 'Divider Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'global'    => [
					'default' => Global_Colors::COLOR_PRIMARY,
				],
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-color: {{VALUE}}',
				),
				'condition' => array(
					'member_description_divider' => 'yes',
				),
			)
		);

		$this->add_control(
			'description_divider_style',
			array(
				'label'     => esc_html__( 'Divider Style', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'solid',
				'options'   => array(
					'solid'  => esc_html__( 'Solid', 'powerpack' ),
					'dotted' => esc_html__( 'Dotted', 'powerpack' ),
					'dashed' => esc_html__( 'Dashed', 'powerpack' ),
					'double' => esc_html__( 'Double', 'powerpack' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-style: {{VALUE}}',
				),
				'condition' => array(
					'member_description_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'description_divider_width',
			array(
				'label'          => esc_html__( 'Divider Width', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 100,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 800,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-description-divider' => 'width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_description_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'description_divider_height',
			array(
				'label'          => esc_html__( 'Divider Height', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 4,
				),
				'range'          => array(
					'px' => array(
						'max' => 20,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-description-divider' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_description_divider' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'description_divider_margin',
			array(
				'label'          => esc_html__( 'Margin Bottom', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'        => array(
					'size' => 10,
					'unit' => 'px',
				),
				'range'          => array(
					'px' => array(
						'max' => 100,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-description-divider-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
				'condition'      => array(
					'member_description_divider' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Social Icons
		 */
		$this->start_controls_section(
			'section_member_social_links_style',
			array(
				'label' => esc_html__( 'Social Icons', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'member_icons_gap',
			array(
				'label'          => esc_html__( 'Icons Gap', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'        => array( 'size' => 10 ),
				'range'          => array(
					'px' => array(
						'max' => 60,
					),
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-social-links li:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'member_icon_size',
			array(
				'label'          => esc_html__( 'Icon Size', 'powerpack' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => array( 'px', 'em', 'rem', 'custom' ),
				'range'          => array(
					'px' => array(
						'max' => 30,
					),
				),
				'default'        => array(
					'size' => '14',
					'unit' => 'px',
				),
				'tablet_default' => array(
					'unit' => 'px',
				),
				'mobile_default' => array(
					'unit' => 'px',
				),
				'selectors'      => array(
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
				),
			)
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
			array(
				'label'     => esc_html__( 'Icons Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
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
			)
		);

		$this->add_control(
			'member_links_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'background-color: {{VALUE}};',
				),
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
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'member_links_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'separator'   => 'before',
				'selector'    => '{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap',
			)
		);

		$this->add_control(
			'member_links_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'member_links_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_links_hover',
			array(
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
			)
		);

		$this->add_control(
			'member_links_icons_color_hover',
			array(
				'label'     => esc_html__( 'Icons Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover .pp-tm-social-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				),
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
			)
		);

		$this->add_control(
			'member_links_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'background-color: {{VALUE}};',
				),
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
			)
		);

		$this->add_control(
			'member_links_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-tm-social-links .pp-tm-social-icon-wrap:hover' => 'border-color: {{VALUE}};',
				),
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
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Arrows
		 */
		$this->start_controls_section(
			'section_arrows_style',
			array(
				'label'     => esc_html__( 'Arrows', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'arrows' => 'yes',
				),
			)
		);

		$this->add_control(
			'select_arrow',
			array(
				'label'                  => esc_html__( 'Choose Arrow', 'powerpack' ),
				'type'                   => Controls_Manager::ICONS,
				'fa4compatibility'       => 'arrow',
				'label_block'            => false,
				'default'                => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'skin'                   => 'inline',
				'exclude_inline_options' => 'svg',
				'recommended'            => array(
					'fa-regular' => array(
						'arrow-alt-circle-right',
						'caret-square-right',
						'hand-point-right',
					),
					'fa-solid'   => array(
						'angle-right',
						'angle-double-right',
						'chevron-right',
						'chevron-circle-right',
						'arrow-right',
						'long-arrow-alt-right',
						'caret-right',
						'caret-square-right',
						'arrow-circle-right',
						'arrow-alt-circle-right',
						'toggle-right',
						'hand-point-right',
					),
				),
			)
		);

		$this->add_responsive_control(
			'arrows_size',
			array(
				'label'      => esc_html__( 'Arrows Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'default'    => array( 'size' => '22' ),
				'range'      => array(
					'px' => array(
						'min'  => 15,
						'max'  => 100,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'left_arrow_position',
			array(
				'label'      => esc_html__( 'Align Left Arrow', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'right_arrow_position',
			array(
				'label'      => esc_html__( 'Align Right Arrow', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => -100,
						'max'  => 40,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_arrows_style' );

		$this->start_controls_tab(
			'tab_arrows_normal',
			array(
				'label' => esc_html__( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'arrows_bg_color_normal',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_color_normal',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'arrows_border_normal',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev',
				'separator'   => 'before',
			)
		);

		$this->add_control(
			'arrows_border_radius_normal',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrows_hover',
			array(
				'label' => esc_html__( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'arrows_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-swiper-button-next:hover, {{WRAPPER}} .elementor-swiper-button-prev:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-swiper-button-next:hover, {{WRAPPER}} .elementor-swiper-button-prev:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'arrows_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .elementor-swiper-button-next:hover, {{WRAPPER}} .elementor-swiper-button-prev:hover' => 'border-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'arrows_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .elementor-swiper-button-next, {{WRAPPER}} .elementor-swiper-button-prev' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'separator'  => 'before',
			)
		);

		$this->end_controls_section();

		/**
		 * Style Tab: Dots
		 */
		$this->start_controls_section(
			'section_dots_style',
			array(
				'label'     => esc_html__( 'Pagination: Dots', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_position',
			array(
				'label'     => esc_html__( 'Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'inside'  => esc_html__( 'Inside', 'powerpack' ),
					'outside' => esc_html__( 'Outside', 'powerpack' ),
				),
				'default'   => 'outside',
				'condition' => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_size',
			array(
				'label'      => esc_html__( 'Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 2,
						'max'  => 40,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_spacing',
			array(
				'label'      => esc_html__( 'Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min'  => 1,
						'max'  => 30,
						'step' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}}',
				),
				'condition'  => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_dots_style' );

		$this->start_controls_tab(
			'tab_dots_normal',
			array(
				'label'     => esc_html__( 'Normal', 'powerpack' ),
				'condition' => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_color_normal',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'active_dot_color_normal',
			array(
				'label'     => esc_html__( 'Active Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'dots_border_normal',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet',
				'condition'   => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_border_radius_normal',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'  => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_responsive_control(
			'dots_margin',
			array(
				'label'              => esc_html__( 'Margin', 'powerpack' ),
				'type'               => Controls_Manager::DIMENSIONS,
				'size_units'         => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'allowed_dimensions' => 'vertical',
				'placeholder'        => array(
					'top'    => '',
					'right'  => 'auto',
					'bottom' => '',
					'left'   => 'auto',
				),
				'selectors'          => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullets' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'condition'          => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_dots_hover',
			array(
				'label'     => esc_html__( 'Hover', 'powerpack' ),
				'condition' => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_color_hover',
			array(
				'label'     => esc_html__( 'Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->add_control(
			'dots_border_color_hover',
			array(
				'label'     => esc_html__( 'Border Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-container-wrap .swiper-pagination-bullet:hover' => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'dots'            => 'yes',
					'pagination_type' => 'bullets',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		/**
		 * Style Tab: Pagination: Fraction
		 * -------------------------------------------------
		 */
		$this->start_controls_section(
			'section_fraction_style',
			array(
				'label'     => esc_html__( 'Pagination: Fraction', 'powerpack' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->add_control(
			'fraction_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'fraction_typography',
				'label'     => esc_html__( 'Typography', 'powerpack' ),
				'global'    => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector'  => '{{WRAPPER}} .swiper-pagination-fraction',
				'condition' => array(
					'dots'            => 'yes',
					'pagination_type' => 'fraction',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$image    = $this->get_settings( 'member_image' );

		$this->add_render_attribute( 'team-member-carousel-wrap', 'class', 'swiper-container-wrap' );

		if ( $settings['dots_position'] ) {
			$this->add_render_attribute( 'team-member-carousel-wrap', 'class', 'swiper-container-wrap-dots-' . $settings['dots_position'] );
		}

		$this->add_render_attribute(
			'team-member-carousel',
			array(
				'class' => array( 'pp-tm-wrapper', 'pp-tm-carousel', 'pp-swiper-slider', 'swiper' ),
				'id'    => 'swiper-container-' . esc_attr( $this->get_id() ),
			)
		);

		if ( 'auto' === $settings['direction'] ) {
			if ( is_rtl() ) {
				$this->add_render_attribute( 'team-member-carousel', 'dir', 'rtl' );
			}
		} else {
			if ( 'right' === $settings['direction'] ) {
				$this->add_render_attribute( 'team-member-carousel', 'dir', 'rtl' );
			}
		}

		$slider_options = $this->get_swiper_slider_settings( $settings, false );

		$this->add_render_attribute(
			'team-member-carousel',
			array(
				'data-slider-settings' => wp_json_encode( $slider_options ),
			)
		);
		?>
		<div <?php $this->print_render_attribute_string( 'team-member-carousel-wrap' ); ?>>
			<div <?php $this->print_render_attribute_string( 'team-member-carousel' ); ?>>
				<div class="swiper-wrapper">
					<?php foreach ( $settings['team_member_details'] as $index => $item ) : ?>
						<div class="swiper-slide">
							<div class="pp-tm">
								<div class="pp-tm-image"> 
									<?php
									if ( $item['team_member_image']['url'] ) {
										$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['team_member_image']['id'], 'thumbnail', $settings );

										if ( $image_url ) {
											$image_html = '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $item['team_member_image'] ) ) . '">';
										} else {
											$image_html = '<img src="' . esc_url( $item['team_member_image']['url'] ) . '">';
										}

										if ( 'image' === $item['link_type'] && $item['link']['url'] ) {

											$link_key = $this->get_repeater_setting_key( 'link', 'team_member_image', $index );

											$this->add_link_attributes( $link_key, $item['link'] );
											?>
											<a <?php $this->print_render_attribute_string( $link_key ); ?>>
												<?php echo wp_kses_post( $image_html ); ?>
											</a>
											<?php
										} else {
											echo wp_kses_post( $image_html );
										}
									}
									?>

									<?php if ( 'none' !== $settings['overlay_content'] ) { ?>
										<div class="pp-tm-overlay-content-wrap">
											<div class="pp-tm-content">
												<?php
												if ( 'yes' === $settings['member_social_links'] ) {
													if ( 'social_icons' === $settings['overlay_content'] ) {
														$this->member_social_links( $item );
													} elseif ( 'all_content' === $settings['overlay_content'] ) {
														if ( 'before_desc' === $settings['social_links_position'] ) {
															$this->member_social_links( $item );
														}
													}
												}

												if ( 'content' === $settings['overlay_content'] || 'all_content' === $settings['overlay_content'] ) {
													$this->render_description( $item );
												}

												if ( 'yes' === $settings['member_social_links'] && 'all_content' === $settings['overlay_content'] ) {
													if ( 'after_desc' === $settings['social_links_position'] ) {
														$this->member_social_links( $item );
													}
												}
												?>
											</div>
										</div>
									<?php } ?>
								</div>
								<div class="pp-tm-content pp-tm-content-normal">
									<?php
									$this->render_name( $item, $index );

									$this->render_position( $item );

									if ( 'yes' === $settings['member_social_links'] && ( 'none' === $settings['overlay_content'] || 'content' === $settings['overlay_content'] ) ) {
										if ( 'none' === $settings['overlay_content'] ) {
											if ( 'before_desc' === $settings['social_links_position'] ) {
												$this->member_social_links( $item );
											}
										} else {
											$this->member_social_links( $item );
										}
									}

									if ( 'none' === $settings['overlay_content'] || 'social_icons' === $settings['overlay_content'] ) {
										$this->render_description( $item );
									}

									if ( 'yes' === $settings['member_social_links'] && ( 'none' === $settings['overlay_content'] || 'content' === $settings['overlay_content'] ) ) {
										if ( 'after_desc' === $settings['social_links_position'] && 'none' === $settings['overlay_content'] ) {
											$this->member_social_links( $item );
										}
									}
									?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
				$this->render_dots();

				$this->render_arrows();
			?>
		</div>
		<?php
	}

	protected function render_name( $item, $index ) {
		$settings = $this->get_settings_for_display();

		if ( ! $item['team_member_name'] ) {
			return;
		}

		$member_key = $this->get_repeater_setting_key( 'team_member_name', 'team_member_details', $index );
		$link_key   = $this->get_repeater_setting_key( 'link', 'team_member_details', $index );

		$this->add_render_attribute( $member_key, 'class', 'pp-tm-name' );

		$member_name = $item['team_member_name'];

		if ( 'title' === $item['link_type'] && ! empty( $item['link']['url'] ) ) {
			$this->add_link_attributes( $link_key, $item['link'] );

			$member_name = '<a ' . $this->get_render_attribute_string( $link_key ) . '>' . $member_name . '</a>';
		}

		$name_html_tag = PP_Helper::validate_html_tag( $settings['name_html_tag'] );
		?>
		<<?php echo esc_html( $name_html_tag ); ?> <?php $this->print_render_attribute_string( $member_key ); ?>>
			<?php echo wp_kses_post( $member_name ); ?>
		</<?php echo esc_html( $name_html_tag ); ?>>

		<?php if ( 'yes' === $settings['member_title_divider'] ) { ?>
			<div class="pp-tm-title-divider-wrap">
				<div class="pp-tm-divider pp-tm-title-divider"></div>
			</div>
			<?php
		}
	}

	protected function render_position( $item ) {
		$settings = $this->get_settings_for_display();

		if ( $item['team_member_position'] ) {
			$position_html_tag = PP_Helper::validate_html_tag( $settings['position_html_tag'] );
			?>
			<<?php echo esc_html( $position_html_tag ); ?> class="pp-tm-position">
				<?php echo wp_kses_post( $item['team_member_position'] ); ?>
			</<?php echo esc_html( $position_html_tag ); ?>>
			<?php
		}
		?>
		<?php if ( 'yes' === $settings['member_position_divider'] ) { ?>
			<div class="pp-tm-position-divider-wrap">
				<div class="pp-tm-divider pp-tm-position-divider"></div>
			</div>
			<?php
		}
	}

	protected function render_description( $item ) {
		$settings = $this->get_settings_for_display();
		if ( $item['team_member_description'] ) {
			?>
			<div class="pp-tm-description">
				<?php echo $this->parse_text_editor( $item['team_member_description'] ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php } ?>
		<?php if ( 'yes' === $settings['member_description_divider'] ) { ?>
			<div class="pp-tm-description-divider-wrap">
				<div class="pp-tm-divider pp-tm-description-divider"></div>
			</div>
			<?php
		}
	}

	private static function render_share_icon( $network_name ) {
		$network_icon_data = self::get_network_icon_data( $network_name );

		if ( PP_Helper::is_feature_active( 'e_font_icon_svg' ) ) {
			$icon = Icons_Manager::render_font_icon( $network_icon_data );
		} else {
			$icon = sprintf( '<i class="%s" aria-hidden="true"></i>', $network_icon_data['value'] );
		}

		$icon = '<span class="pp-tm-social-icon pp-icon">' . $icon . '</span>';

		\Elementor\Utils::print_unescaped_internal_string( $icon );
	}

	private function member_social_links( $item ) {
		$settings = $this->get_settings_for_display();
		$social_links = array();

		( $item['facebook_url'] ) ? $social_links['facebook']   = $item['facebook_url'] : '';
		( $item['twitter_url'] ) ? $social_links['x-twitter']   = $item['twitter_url'] : '';
		( $item['instagram_url'] ) ? $social_links['instagram'] = $item['instagram_url'] : '';
		( $item['linkedin_url'] ) ? $social_links['linkedin']   = $item['linkedin_url'] : '';
		( $item['youtube_url'] ) ? $social_links['youtube']     = $item['youtube_url'] : '';
		( $item['pinterest_url'] ) ? $social_links['pinterest'] = $item['pinterest_url'] : '';
		( $item['dribbble_url'] ) ? $social_links['dribbble']   = $item['dribbble_url'] : '';
		( $item['flickr_url'] ) ? $social_links['flickr']       = $item['flickr_url'] : '';
		( $item['tumblr_url'] ) ? $social_links['tumblr']       = $item['tumblr_url'] : '';
		( $item['tiktok_url'] ) ? $social_links['tiktok']       = $item['tiktok_url'] : '';
		( $item['github_url'] ) ? $social_links['github']       = $item['github_url'] : '';
		( $item['vimeo_url'] ) ? $social_links['vimeo']         = $item['vimeo_url'] : '';
		( $item['xing_url'] ) ? $social_links['xing']           = $item['xing_url'] : '';
		( $item['email'] ) ? $social_links['envelope']          = $item['email'] : '';
		( $item['phone'] ) ? $social_links['phone']             = $item['phone'] : '';
		?>
		<div class="pp-tm-social-links-wrap">
			<ul class="pp-tm-social-links">
				<?php
				$i = 0;
				foreach ( $social_links as $icon_id => $icon_url ) {
					$network_name = $icon_id;

					$icon_wrap_key = 'icon_wrap' . $i;
					$this->add_render_attribute( $icon_wrap_key, 'class', 'pp-tm-social-icon-wrap' );

					if ( 'button' === $settings['social_links_style'] ) {
						$this->add_render_attribute( $icon_wrap_key, 'class', [
							'elementor-icon',
							'elementor-social-icon',
							'elementor-social-icon-' . $network_name,
						] );
					}

					if ( $icon_url ) {
						$social_link_url = esc_url( $icon_url );

						if ( 'envelope' === $icon_id ) {
							$social_link_url = "mailto:" . sanitize_email( $icon_url );
						} elseif ( 'phone' === $icon_id ) {
							$social_link_url = "tel:" . esc_attr( $icon_url );
						}

						$link_target = $settings['links_target'];
						?>
						<li>
							<a href="<?php echo $social_link_url; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" target="<?php echo esc_attr( $link_target ); ?>">
								<span <?php $this->print_render_attribute_string( $icon_wrap_key ); ?>>
									<?php self::render_share_icon( $network_name ); ?>
								</span>
							</a>
						</li>
						<?php	
					}
					$i++;
				}
				?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Render team member carousel dots output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_dots() {
		$settings = $this->get_settings_for_display();

		if ( 'yes' === $settings['dots'] ) {
			?>
			<!-- Add Pagination -->
			<div class="swiper-pagination swiper-pagination-<?php echo esc_attr( $this->get_id() ); ?>"></div>
			<?php
		}
	}

	/**
	 * Render team member carousel arrows output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
	protected function render_arrows() {
		PP_Helper::render_arrows( $this );
	}

	/**
	 * Render team member carousel widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.4.1
	 * @access protected
	 */
	protected function content_template() {
		$elementor_bp_tablet = get_option( 'elementor_viewport_lg' );
		$elementor_bp_mobile = get_option( 'elementor_viewport_md' );
		$elementor_bp_lg     = get_option( 'elementor_viewport_lg' );
		$elementor_bp_md     = get_option( 'elementor_viewport_md' );
		$bp_desktop          = ! empty( $elementor_bp_lg ) ? $elementor_bp_lg : 1025;
		$bp_tablet           = ! empty( $elementor_bp_md ) ? $elementor_bp_md : 768;
		$bp_mobile           = 320;
		$this->get_swiper_slider_settings_js();
		?>
		<#
			var i = 1;

			function get_social_icon_obj( icon ) {
				if ( icon == 'email' ) {
					var iconObj = { value:"far fa-envelope", library:"fa-regular" };
				} else if ( icon == 'phone' ) {
					var iconObj = { value:"fas fa-phone-alt", library:"fa-solid" };
				} else {
					var iconObj = { value:"fab fa-" + icon, library:"fa-brands" };
				}

				return iconObj;
			}

			function render_social_icon_html( item, index, icon ) {
				var icon_url_var = `${icon}_url`,
					icon_url = item[icon_url_var],
					social = icon;

				if ( icon == 'email' || icon == 'phone' ) {
					icon_url = item[icon];
				}

				if ( icon_url ) { #>
					<li>
						<#
						var url = icon_url,
							social_icon = "fab fa-{{ icon }}";

						if ( icon == 'email' ) {
							url = "mailto:" + icon_url;
							social_icon = "far fa-envelope";
						} else if ( icon == 'phone' ) {
							url = "tel:" + icon_url;
							social_icon = "fas fa-phone-alt";
						}

						var iconWrapKey = view.getRepeaterSettingKey( 'text', 'icon_wrap', index );
						view.addRenderAttribute( iconWrapKey, 'class', 'pp-tm-social-icon-wrap' );

						if ( 'email' == icon ) {
							social = 'envelope';
						}

						if ( 'button' == settings.social_links_style ) {
							view.addRenderAttribute( iconWrapKey, 'class', [
								'elementor-icon',
								'elementor-social-icon',
								'elementor-social-icon-' + social,
							] );
						}
						#>
						<a href="{{ url }}">
							<span {{{ view.getRenderAttributeString( iconWrapKey ) }}}>
								<#
								var iconObj = get_social_icon_obj( icon );
								var iconHTML = elementor.helpers.renderIcon( view, iconObj, { 'aria-hidden': true, 'class': 'pp-tm-social-icon' }, 'i' , 'object' );
								#>
								<# if ( iconHTML && iconHTML.rendered ) { #>
									{{{ iconHTML.value }}}
								<# } else { #>
									<span class="pp-tm-social-icon {{ social_icon }}"></span>
								<# } #>
							</span>
						</a>
					</li>
				<# }
			}

			function member_social_links_template( item ) { #>
				<div class="pp-tm-social-links-wrap">
					<ul class="pp-tm-social-links">
						<#
						var social_links = [
							'facebook',
							'twitter',
							'instagram',
							'linkedin',
							'youtube',
							'pinterest',
							'dribbble',
							'flickr',
							'tumblr',
							'tiktok',
							'github',
							'vimeo',
							'xing',
							'email',
							'phone',
						];

						for ( var i = 0; i < social_links.length; i++ ) {
							render_social_icon_html( item, i, social_links[i] );
						}
						#>
					</ul>
				</div>
				<#
			}

			function name_template( item ) {
				if ( item.team_member_name != '' ) {
					var name = item.team_member_name;

					view.addRenderAttribute( 'team_member_name', 'class', 'pp-tm-name' );

					if ( item.link_type == 'title' && item.link.url != '' ) {
						var target = item.link.is_external ? ' target="_blank"' : '',
							nofollow = item.link.nofollow ? ' rel="nofollow"' : '';
				   
						var name = '<a href="' + _.escape( item.link.url ) + '" ' + target + '>' + name + '</a>';
					}

					var nameHTMLTag = elementor.helpers.validateHTMLTag( settings.name_html_tag ),
						name_html = '<' + nameHTMLTag  + ' ' + view.getRenderAttributeString( 'team_member_name' ) + '>' + name + '</' + nameHTMLTag + '>';
				   
					print(name_html);
				}

				if ( settings.member_title_divider == 'yes' ) {
					#>
					<div class="pp-tm-title-divider-wrap">
						<div class="pp-tm-divider pp-tm-title-divider"></div>
					</div>
					<#
				}
			}

			function position_template( item ) {
				if ( item.team_member_position != '' ) {
					var position = item.team_member_position;

					view.addRenderAttribute( 'team_member_position', 'class', 'pp-tm-position' );

					var positionHTMLTag = elementor.helpers.validateHTMLTag( settings.position_html_tag ),
						position_html = '<' + positionHTMLTag  + ' ' + view.getRenderAttributeString( 'team_member_position' ) + '>' + position + '</' + positionHTMLTag + '>';

					print( position_html );
				}
				if ( settings.member_position_divider == 'yes' ) {
					#>
					<div class="pp-tm-position-divider-wrap">
						<div class="pp-tm-divider pp-tm-position-divider"></div>
					</div>
					<#
				}
			}

			function description_template( item ) {
				if ( item.team_member_description != '' ) {
					var description = item.team_member_description;

					view.addRenderAttribute( 'team_member_description', 'class', 'pp-tm-description' );

					var description_html = '<div' + ' ' + view.getRenderAttributeString( 'team_member_description' ) + '>' + description + '</div>';

					print( description_html );
				}

				if ( settings.member_description_divider == 'yes' ) {
					#>
					<div class="pp-tm-description-divider-wrap">
						<div class="pp-tm-divider pp-tm-description-divider"></div>
					</div>
					<#
				}
			}

			function dots_template() {
				if ( settings.dots == 'yes' ) {
					#>
					<div class="swiper-pagination"></div>
					<#
				}
			}

			function arrows_template() {
				var arrowIconHTML = elementor.helpers.renderIcon( view, settings.select_arrow, { 'aria-hidden': true }, 'i' , 'object' ),
					arrowMigrated = elementor.helpers.isIconMigrated( settings, 'select_arrow' );

				if ( settings.arrows == 'yes' ) {
					if ( settings.arrow || settings.select_arrow.value ) {
						if ( arrowIconHTML && arrowIconHTML.rendered && ( ! settings.arrow || arrowMigrated ) ) {
							var pp_next_arrow = settings.select_arrow.value;
							var pp_prev_arrow = pp_next_arrow.replace('right', "left");
						} else if ( settings.arrow != '' ) {
							var pp_next_arrow = settings.arrow;
							var pp_prev_arrow = pp_next_arrow.replace('right', "left");
						}
						else {
							var pp_next_arrow = 'fa fa-angle-right';
							var pp_prev_arrow = 'fa fa-angle-left';
						}
						#>
						<div class="pp-slider-arrow elementor-swiper-button-next">
							<i class="{{ pp_next_arrow }}"></i>
						</div>
						<div class="pp-slider-arrow elementor-swiper-button-prev">
							<i class="{{ pp_prev_arrow }}"></i>
						</div>
						<#
					}
				}
			}

			view.addRenderAttribute(
				'container',
				{
					'class': [
						'pp-tm-wrapper',
						'pp-tm-carousel',
						'pp-swiper-slider',
						'swiper'
					],
				}
			);

			if ( settings.direction == 'auto' ) {
				var direction = elementorFrontend.config.is_rtl ? 'rtl' : 'ltr';

				view.addRenderAttribute( 'container', 'dir', direction );
			} else {
				if ( settings.direction == 'right' ) {
					view.addRenderAttribute( 'container', 'dir', 'rtl' );
				}
			}

			var slider_options = get_slider_settings( settings );

			view.addRenderAttribute( 'container', 'data-slider-settings', JSON.stringify( slider_options ) );
		#>
		<div class="swiper-container-wrap swiper-container-wrap-dots-{{ settings.dots_position }}">
			<div {{{ view.getRenderAttributeString( 'container' ) }}}>
				<div class="swiper-wrapper">
					<# _.each( settings.team_member_details, function( item ) { #>
						<div class="swiper-slide">
							<div class="pp-tm">
								<div class="pp-tm-image">
									<#
										if ( item.team_member_image.url != '' ) {
											var image = {
												id: item.team_member_image.id,
												url: item.team_member_image.url,
												size: settings.thumbnail_size,
												dimension: settings.thumbnail_custom_dimension,
												model: view.getEditModel()
											};

											var image_url = elementor.imagesManager.getImageUrl( image );

											var imageHtml = '<img src="' + _.escape( image_url ) + '" />';

											if ( item.link_type == 'image' && item.link.url != '' ) {
												imageHtml = '<a href="' + _.escape( item.link.url ) + '">' + imageHtml + '</a>';
											}

											print( imageHtml );
										}
									#>

									<# if ( settings.overlay_content != 'none' ) { #>
										<div class="pp-tm-overlay-content-wrap">
											<div class="pp-tm-content">
												<#
													if ( settings.member_social_links == 'yes' ) {
														if ( settings.overlay_content == 'social_icons' ) {
															member_social_links_template( item );
														} else if ( settings.overlay_content == 'all_content' ) {
															if ( settings.social_links_position == 'before_desc' ) {
																member_social_links_template( item );
															}
														}
													}

													if ( settings.overlay_content == 'content' || settings.overlay_content == 'all_content' ) {
														description_template( item );
													}
												   
													if ( settings.member_social_links == 'yes' && settings.overlay_content == 'all_content' ) {
														if ( settings.social_links_position == 'after_desc' ) {
															member_social_links_template( item );
														}
													}
												#>
											</div>
										</div>
									<# } #>
								</div>
								<div class="pp-tm-content pp-tm-content-normal">
									<#
										name_template( item );
										position_template( item );

										if ( settings.member_social_links == 'yes' && ( settings.overlay_content == 'none' || settings.overlay_content == 'content' ) ) {
											if ( settings.overlay_content == 'none' ) {
												if ( settings.social_links_position == 'before_desc' ) {
													member_social_links_template( item );
												}
											} else {
												member_social_links_template( item );
											}
										}

										if ( settings.overlay_content == 'none' || settings.overlay_content == 'social_icons' ) {
											description_template( item );
										}

										if ( settings.member_social_links == 'yes' && ( settings.overlay_content == 'none' || settings.overlay_content == 'content' ) ) {
											if ( settings.social_links_position == 'after_desc' && settings.overlay_content == 'none' ) {
												member_social_links_template( item );
											}
										}
									#>
								</div>
							</div>
						</div>
					<# i++ } ); #>
				</div>
			</div>
			<# dots_template(); #>
			<# arrows_template(); #>
		</div>    
		<?php
	}
}
