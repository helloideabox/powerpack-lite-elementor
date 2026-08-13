<?php
namespace PowerpackElementsLite\Modules\SlideMenu\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Slide Menu Widget
 */
class Slide_Menu extends Powerpack_Widget {

	/**
	 * Menu Index
	 *
	 * @var $nav_menu_index
	 */
	protected $nav_menu_index = 1;

	/**
	 * Rendered submenu toggle markup, reused for every parent menu item.
	 *
	 * @since 3.0.0
	 * @var string
	 */
	protected $submenu_icon_html = '';

	/**
	 * Deepest menu level to render, or 0 for no limit.
	 *
	 * @since 3.0.0
	 * @var int
	 */
	protected $max_depth = 0;

	/**
	 * Whether menu item descriptions are output.
	 *
	 * @since 3.0.0
	 * @var bool
	 */
	protected $show_description = false;

	/**
	 * Retrieve slide menu widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Slide_Menu' );
	}

	/**
	 * Retrieve slide menu widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Slide_Menu' );
	}

	/**
	 * Retrieve slide menu widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Slide_Menu' );
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
		return parent::get_widget_keywords( 'Slide_Menu' );
	}

	/**
	 * Retrieve the list of scripts the slide menu widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [
			'pp-slide-menu',
		];
	}

	/**
	 * Retrieve the list of styles the slide menu widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget styles dependencies.
	 */
	public function get_style_depends() {
		return [
			'widget-pp-slide-menu',
		];
	}

	/**
	 * Strip the selected menu when the widget is exported.
	 *
	 * Menu slugs are local to a site, so carrying one over to another install
	 * would point at a menu that does not exist there.
	 *
	 * @since 3.0.0
	 * @access public
	 *
	 * @param array $element Exported element data.
	 * @return array
	 */
	public function on_export( $element ) {
		unset( $element['settings']['menu'] );

		return $element;
	}

	/**
	 * Get menu index
	 *
	 * @access protected
	 *
	 * @return int Current index, before it is incremented.
	 */
	protected function get_nav_menu_index() {
		return $this->nav_menu_index++;
	}

	/**
	 * Get available menus
	 *
	 * @access private
	 *
	 * @return array Menu names keyed by slug.
	 */
	private function get_available_menus() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Register widget controls
	 *
	 * @access protected
	 */
	protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		$this->register_controls();
	}

	/**
	 * Register slide menu widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.0.0
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_slide_menu_controls();
		$this->register_content_settings_controls();

		/* Style Tab */
		$this->register_style_menu_controls();
		$this->register_style_links_controls();
		$this->register_style_submenu_controls();
		$this->register_style_description_controls();
		$this->register_style_arrows_controls();
	}

	/**
	 * Register widget layout controls
	 *
	 * @since  3.0.0
	 * @access protected
	 *
	 * @return void
	 */
	protected function register_content_slide_menu_controls() {
		$this->start_controls_section(
			'section_general',
			[
				'label' => esc_html__( 'Slide Menu', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$menus = $this->get_available_menus();

			if ( ! empty( $menus ) ) {
				$this->add_control(
					'menu',
					[
						'label'        => esc_html__( 'Menu', 'powerpack-lite-for-elementor' ),
						'type'         => Controls_Manager::SELECT,
						'options'      => $menus,
						'default'      => array_keys( $menus )[0],
						'save_default' => true,
						'label_block'  => true,
						'description'  => sprintf(
							/* translators: 1: Link opening tag, 2: Link closing tag. */
							esc_html__( 'Go to the %1$sMenus screen%2$s to manage your menus.', 'powerpack-lite-for-elementor' ),
							sprintf( '<a href="%s" target="_blank">', esc_url( admin_url( 'nav-menus.php' ) ) ),
							'</a>'
						),
					]
				);
			} else {
				$this->add_control(
					'menu',
					[
						'type'            => Controls_Manager::RAW_HTML,
						'raw'             => sprintf(
							/* translators: 1: Strong opening tag, 2: Strong closing tag, 3: Line break, 4: Link opening tag, 5: Link closing tag. */
							esc_html__( '%1$sThere are no menus in your site.%2$s%3$sGo to the %4$sMenus screen%5$s to create one.', 'powerpack-lite-for-elementor' ),
							'<strong>',
							'</strong>',
							'<br>',
							sprintf( '<a href="%s" target="_blank">', esc_url( admin_url( 'nav-menus.php?action=edit&menu=0' ) ) ),
							'</a>'
						),
						'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
					]
				);
			}

			$this->add_control(
				'max_depth',
				[
					'label'       => esc_html__( 'Max Depth', 'powerpack-lite-for-elementor' ),
					'type'        => Controls_Manager::SELECT,
					'options'     => [
						'0' => esc_html__( 'All Levels', 'powerpack-lite-for-elementor' ),
						'1' => esc_html__( 'Top Level Only', 'powerpack-lite-for-elementor' ),
						'2' => esc_html__( '2 Levels', 'powerpack-lite-for-elementor' ),
						'3' => esc_html__( '3 Levels', 'powerpack-lite-for-elementor' ),
						'4' => esc_html__( '4 Levels', 'powerpack-lite-for-elementor' ),
					],
					'default'     => '0',
					'description' => esc_html__( 'Levels deeper than this are left out of the menu entirely.', 'powerpack-lite-for-elementor' ),
				]
			);

			$this->add_control(
				'show_description',
				[
					'label'        => esc_html__( 'Item Description', 'powerpack-lite-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'description'  => sprintf(
						/* translators: 1: Link opening tag, 2: Link closing tag. */
						esc_html__( 'Shows the description saved on each menu item. Enable Descriptions under Screen Options on the %1$sMenus screen%2$s to fill them in.', 'powerpack-lite-for-elementor' ),
						sprintf( '<a href="%s" target="_blank">', esc_url( admin_url( 'nav-menus.php' ) ) ),
						'</a>'
					),
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'submenu_behavior',
				[
					'label'              => esc_html__( 'Submenu Behavior', 'powerpack-lite-for-elementor' ),
					'type'               => Controls_Manager::SELECT,
					'options'            => [
						'slide'     => esc_html__( 'Slide', 'powerpack-lite-for-elementor' ),
						'accordion' => esc_html__( 'Accordion', 'powerpack-lite-for-elementor' ),
					],
					'default'            => 'slide',
					'description'        => esc_html__( 'Slide moves each sub-menu in as a panel. Accordion expands it in place, below its parent item.', 'powerpack-lite-for-elementor' ),
					'prefix_class'       => 'pp-slide-menu-submenu--',
					'render_type'        => 'template',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'effect',
				[
					'label'     => esc_html__( 'Effect', 'powerpack-lite-for-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
						'overlay'   => esc_html__( 'Overlay', 'powerpack-lite-for-elementor' ),
						'push'      => esc_html__( 'Push', 'powerpack-lite-for-elementor' ),
					],
					'default'       => 'overlay',
					'prefix_class'  => 'pp-slide-menu-effect--',
					'render_type'  => 'template',
					'condition'     => [
						'submenu_behavior' => 'slide',
					],
				]
			);

			$this->add_control(
				'direction',
				[
					'label'     => esc_html__( 'Direction', 'powerpack-lite-for-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => [
						'left'      => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'right'     => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'bottom'    => esc_html__( 'Bottom', 'powerpack-lite-for-elementor' ),
						'top'       => esc_html__( 'Top', 'powerpack-lite-for-elementor' ),
					],
					'default'       => 'left',
					'prefix_class'  => 'pp-slide-menu-direction--',
					'condition'     => [
						'submenu_behavior' => 'slide',
					],
				]
			);

			$this->add_responsive_control(
				'duration',
				[
					'label'         => esc_html__( 'Transition Duration', 'powerpack-lite-for-elementor' ),
					'type'          => Controls_Manager::SLIDER,
					'size_units'    => [ 's', 'ms', 'custom' ],
					'default'       => [
						'unit' => 's',
						'size' => 0.3,
					],
					'range'         => [
						's'         => [
							'min'  => 0,
							'max'  => 3,
							'step' => 0.1,
						],
						'ms'        => [
							'min'  => 0,
							'max'  => 3000,
							'step' => 50,
						],
					],
					'selectors'     => [
						// The item fade under the push effect runs alongside the slide, so
						// it has to share the same duration.
						'{{WRAPPER}} .pp-menu-sub-menu,
						 {{WRAPPER}} .pp-menu-menu,
						 {{WRAPPER}} .pp-menu-item' => 'transition-duration: {{SIZE}}{{UNIT}};',
					],
					// Accordion mode shows and hides panels outright, so there is no
					// slide for a duration to apply to.
					'condition'     => [
						'submenu_behavior' => 'slide',
					],
				]
			);

			$this->add_control(
				'back_text_source',
				[
					'label'              => esc_html__( 'Back Label Source', 'powerpack-lite-for-elementor' ),
					'type'               => Controls_Manager::SELECT,
					'options'            => [
						'custom'       => esc_html__( 'Custom Label', 'powerpack-lite-for-elementor' ),
						'parent_label' => esc_html__( 'Parent Item Label', 'powerpack-lite-for-elementor' ),
					],
					'default'            => 'custom',
					'frontend_available' => true,
					'condition'          => [
						'submenu_behavior' => 'slide',
					],
				]
			);

			$this->add_control(
				'back_text',
				[
					'label'              => esc_html__( 'Back Label', 'powerpack-lite-for-elementor' ),
					'type'               => Controls_Manager::TEXT,
					'dynamic'            => [ 'active' => true ],
					'default'            => esc_html__( 'Back', 'powerpack-lite-for-elementor' ),
					'label_block'        => false,
					'frontend_available' => true,
					'condition'          => [
						'submenu_behavior' => 'slide',
						'back_text_source' => 'custom',
					],
				]
			);

			$this->add_control(
				'submenu_icon',
				[
					'label'       => esc_html__( 'Submenu Arrow Icon', 'powerpack-lite-for-elementor' ),
					'type'        => Controls_Manager::ICONS,
					'label_block' => false,
					'skin'        => 'inline',
					'default'     => [
						'value'   => 'fas fa-angle-right',
						'library' => 'fa-solid',
					],
					'recommended' => [
						'fa-regular' => [
							'plus-square',
						],
						'fa-solid' => [
							'angle-right',
							'caret-right',
							'chevron-right',
							'plus',
							'plus-circle',
							'plus-square',
							'folder-plus',
						],
					],
				]
			);

			$this->add_control(
				'back_icon',
				[
					'label'       => esc_html__( 'Back Arrow Icon', 'powerpack-lite-for-elementor' ),
					'type'        => Controls_Manager::ICONS,
					'label_block' => false,
					'skin'        => 'inline',
					'default'     => [
						'value'   => 'fas fa-angle-left',
						'library' => 'fa-solid',
					],
					'recommended' => [
						'fa-regular' => [
							'minus-square',
						],
						'fa-solid' => [
							'angle-left',
							'caret-left',
							'chevron-left',
							'minus',
							'minus-circle',
							'minus-square',
							'folder-minus',
						],
					],
					'condition'   => [
						'submenu_behavior' => 'slide',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register widget layout controls
	 *
	 * @since  3.0.0
	 * @access protected
	 *
	 * @return void
	 */
	protected function register_content_settings_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'link_navigation',
				[
					'label'              => esc_html__( 'Link Navigation', 'powerpack-lite-for-elementor' ),
					'type'               => Controls_Manager::SWITCHER,
					'description'        => esc_html__( 'Allow navigating to sub-menus by clicking the links instead of just the arrows.', 'powerpack-lite-for-elementor' ),
					'return_value'       => 'yes',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'close_on_escape',
				[
					'label'              => esc_html__( 'Close on Escape', 'powerpack-lite-for-elementor' ),
					'type'               => Controls_Manager::SWITCHER,
					'description'        => esc_html__( 'Pressing Escape steps back one level.', 'powerpack-lite-for-elementor' ),
					'default'            => 'yes',
					'return_value'       => 'yes',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'close_on_click_outside',
				[
					'label'              => esc_html__( 'Close on Click Outside', 'powerpack-lite-for-elementor' ),
					'type'               => Controls_Manager::SWITCHER,
					'description'        => esc_html__( 'Clicking away from the menu returns it to the top level.', 'powerpack-lite-for-elementor' ),
					'return_value'       => 'yes',
					'frontend_available' => true,
				]
			);

			$this->add_control(
				'close_on_link_click',
				[
					'label'              => esc_html__( 'Close on Link Click', 'powerpack-lite-for-elementor' ),
					'type'               => Controls_Manager::SWITCHER,
					'description'        => esc_html__( 'Returns the menu to the top level when a link is followed. Useful for anchor links, where the page does not reload.', 'powerpack-lite-for-elementor' ),
					'return_value'       => 'yes',
					'frontend_available' => true,
				]
			);

		$this->end_controls_section();

	}

	/*-----------------------------------------------------------------------------------*/
	/*	STYLE TAB
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Register menu style controls
	 *
	 * @since  3.0.0
	 * @access protected
	 */
	protected function register_style_menu_controls() {
		$this->start_controls_section(
			'section_menu_style',
			[
				'label' => esc_html__( 'Menu', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'menu_width',
				[
					'label'         => esc_html__( 'Width', 'powerpack-lite-for-elementor' ),
					'type'          => Controls_Manager::SLIDER,
					'size_units'    => [ 'px', '%' ],
					'range'         => [
						'%'         => [
							'min' => 0,
							'max' => 100,
						],
						'px'        => [
							'min' => 100,
							'max' => 1000,
						],
					],
					'selectors'     => [
						'{{WRAPPER}} .pp-slide-menu' => 'max-width: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'menu_fixed_height',
				[
					'label'              => esc_html__( 'Fixed Height', 'powerpack-lite-for-elementor' ),
					'type'               => Controls_Manager::SWITCHER,
					'description'        => esc_html__( 'If turned off, the menu will adjust its height based on the currently active sub-menu.', 'powerpack-lite-for-elementor' ),
					'default'            => '',
					'frontend_available' => true,
					// Accordion mode grows the menu in place, so there is no panel
					// height for the menu to track.
					'condition'          => [
						'submenu_behavior' => 'slide',
					],
				]
			);

			$this->add_responsive_control(
				'menu_height',
				[
					'label'         => esc_html__( 'Min. Height', 'powerpack-lite-for-elementor' ),
					'type'          => Controls_Manager::SLIDER,
					'size_units'    => [ 'px', 'vh', 'custom' ],
					'range'         => [
						'px'        => [
							'min' => 100,
							'max' => 1000,
						],
						'vh'        => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'     => [
						'{{WRAPPER}} .pp-slide-menu,
						 {{WRAPPER}} .pp-slide-menu-sub-menu' => 'min-height: {{SIZE}}{{UNIT}};',
					],
					'condition'     => [
						'submenu_behavior'   => 'slide',
						'menu_fixed_height!' => '',
					],
				]
			);

			$this->add_responsive_control(
				'submenu_indent',
				[
					'label'      => esc_html__( 'Submenu Indent', 'powerpack-lite-for-elementor' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem', 'custom' ],
					'default'    => [
						'size' => 20,
						'unit' => 'px',
					],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .pp-slide-menu-sub-menu' => 'padding-inline-start: {{SIZE}}{{UNIT}};',
					],
					// Expanded panels sit in the same column as their parent, so without
					// an indent there is nothing to show the nesting.
					'condition'  => [
						'submenu_behavior' => 'accordion',
					],
				]
			);

			$this->add_responsive_control(
				'menu_align',
				[
					'label'         => esc_html__( 'Align', 'powerpack-lite-for-elementor' ),
					'type'          => Controls_Manager::CHOOSE,
					'default'       => 'left',
					'options'       => [
						'left'          => [
							'title'     => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
							'icon'      => 'eicon-h-align-left',
						],
						'center'        => [
							'title'     => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
							'icon'      => 'eicon-h-align-center',
						],
						'right'         => [
							'title'     => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
							'icon'      => 'eicon-h-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}}' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
					'name'      => 'menu_background',
					'label'     => esc_html__( 'Background', 'powerpack-lite-for-elementor' ),
					'types'     => [ 'classic', 'gradient' ],
					'exclude'   => [ 'image' ],
					'selector'  => '{{WRAPPER}} .pp-slide-menu, {{WRAPPER}} .pp-slide-menu .pp-slide-menu-sub-menu',
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'      => 'menu_border',
					'label'     => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
					'selector'  => '{{WRAPPER}} .pp-slide-menu',
				]
			);

			$this->add_control(
				'menu_border_radius',
				[
					'label'         => esc_html__( 'Border Radius', 'powerpack-lite-for-elementor' ),
					'type'          => Controls_Manager::DIMENSIONS,
					'selectors'     => [
						'{{WRAPPER}} .pp-slide-menu,
						 {{WRAPPER}} .pp-slide-menu-sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'     => 'menu_box_shadow',
					'selector' => '{{WRAPPER}} .pp-slide-menu',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'menu_links_typography',
					'label'     => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
					'global'    => [
						'default' => Global_Typography::TYPOGRAPHY_ACCENT,
					],
					// Targets the shared link class so the Back row - a button, not an
					// anchor - is covered too.
					'selector'  => '{{WRAPPER}} .pp-slide-menu .pp-slide-menu-item-link',
				]
			);

		$this->end_controls_section();

	}

	/**
	 * Register links style Controls
	 *
	 * @since  3.0.0
	 * @access protected
	 */
	protected function register_style_links_controls() {
		$this->start_controls_section(
			'section_links_style',
			[
				'label' => esc_html__( 'Links', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'links_spacing',
				[
					'label'         => esc_html__( 'Spacing', 'powerpack-lite-for-elementor' ),
					'type'          => Controls_Manager::SLIDER,
					'size_units'    => [ 'px', 'em', 'rem', 'custom' ],
					'default'       => [
						'size'      => 0,
						'unit'      => 'px',
					],
					'range'         => [
						'px'        => [
							'min' => 0,
							'max' => 50,
						],
					],
					'selectors'     => [
						'{{WRAPPER}} .pp-menu-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'links_separator_thickness',
				[
					'label'      => esc_html__( 'Separator Thickness', 'powerpack-lite-for-elementor' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem', 'custom' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 50,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .pp-menu-item' => 'border-bottom-width: {{SIZE}}{{UNIT}}; border-bottom-style: solid;',
					],
				]
			);

			$this->start_controls_tabs( 'links_type' );

			$this->start_controls_tab( 'links_regular', [ 'label' => esc_html__( 'Default', 'powerpack-lite-for-elementor' ) ] );

				$this->add_responsive_control(
					'links_text_align',
					[
						'label'         => esc_html__( 'Align Text', 'powerpack-lite-for-elementor' ),
						'type'          => Controls_Manager::CHOOSE,
						'default'       => 'left',
						'options'       => [
							'left'          => [
								'title'     => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
								'icon'      => 'eicon-text-align-left',
							],
							'center'        => [
								'title'     => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
								'icon'      => 'eicon-text-align-center',
							],
							'right'         => [
								'title'     => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
								'icon'      => 'eicon-text-align-right',
							],
						],
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-item-link' => 'text-align: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'links_padding',
					[
						'label'         => esc_html__( 'Padding', 'powerpack-lite-for-elementor' ),
						'type'          => Controls_Manager::DIMENSIONS,
						'size_units'    => [ 'px', '%', 'em', 'rem', 'custom' ],
						'selectors'     => [
							'{{WRAPPER}} .pp-slide-menu-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .pp-slide-menu-arrow' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'links_back', [ 'label' => esc_html__( 'Back', 'powerpack-lite-for-elementor' ) ] );

				$this->add_responsive_control(
					'links_back_text_align',
					[
						'label'         => esc_html__( 'Align Text', 'powerpack-lite-for-elementor' ),
						'type'          => Controls_Manager::CHOOSE,
						'default'       => 'left',
						'options'       => [
							'left'          => [
								'title'     => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
								'icon'      => 'eicon-text-align-left',
							],
							'center'        => [
								'title'     => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
								'icon'      => 'eicon-text-align-center',
							],
							'right'         => [
								'title'     => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
								'icon'      => 'eicon-text-align-right',
							],
						],
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-back .pp-slide-menu-item-link' => 'text-align: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'links_back_padding',
					[
						'label'         => esc_html__( 'Padding', 'powerpack-lite-for-elementor' ),
						'type'          => Controls_Manager::DIMENSIONS,
						'size_units'    => [ 'px', '%', 'em', 'rem', 'custom' ],
						'selectors'     => [
							'{{WRAPPER}} .pp-slide-menu-back .pp-slide-menu-item-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .pp-slide-menu-back .pp-slide-menu-arrow' => 'padding-top: {{TOP}}{{UNIT}}; padding-bottom: {{BOTTOM}}{{UNIT}};',
						],
					]
				);

					// The rest of the Back styling is slide-only, since accordion mode
					// never builds a back row.
					$this->add_group_control(
						Group_Control_Typography::get_type(),
						[
							'name'      => 'links_back_typography',
							'label'     => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
							'selector'  => '{{WRAPPER}} .pp-slide-menu-back .pp-slide-menu-item-link',
							'condition' => [
								'submenu_behavior' => 'slide',
							],
						]
					);

					$this->add_control(
						'links_back_color',
						[
							'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pp-slide-menu-back .pp-slide-menu-item-link' => 'color: {{VALUE}};',
							],
							'condition' => [
								'submenu_behavior' => 'slide',
							],
						]
					);

					$this->add_control(
						'links_back_color_hover',
						[
							'label'     => esc_html__( 'Text Color on Hover', 'powerpack-lite-for-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pp-slide-menu-back .pp-slide-menu-item-link:hover' => 'color: {{VALUE}};',
							],
							'condition' => [
								'submenu_behavior' => 'slide',
							],
						]
					);

					$this->add_control(
						'links_back_background',
						[
							'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pp-slide-menu-back .pp-slide-menu-item-link' => 'background-color: {{VALUE}};',
							],
							'condition' => [
								'submenu_behavior' => 'slide',
							],
						]
					);

					$this->add_control(
						'links_back_background_hover',
						[
							'label'     => esc_html__( 'Background Color on Hover', 'powerpack-lite-for-elementor' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pp-slide-menu-back .pp-slide-menu-item-link:hover' => 'background-color: {{VALUE}};',
							],
							'condition' => [
								'submenu_behavior' => 'slide',
							],
						]
					);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->start_controls_tabs( 'links' );

			$this->start_controls_tab( 'links_normal', [ 'label' => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ) ] );

				$this->add_control(
					'links_color',
					[
						'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-item-link' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'links_background',
					[
						'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-item-link' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'links_separator_color',
					[
						'label'     => esc_html__( 'Separator Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-menu-item' => 'border-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'links_hover', [ 'label' => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ) ] );

				$this->add_control(
					'links_color_hover',
					[
						'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-item-link:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'links_background_hover',
					[
						'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-item-link:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'links_separator_color_hover',
					[
						'label'     => esc_html__( 'Separator Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-menu-item:hover' => 'border-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'links_current', [ 'label' => esc_html__( 'Current', 'powerpack-lite-for-elementor' ) ] );

				$this->add_control(
					'links_color_current',
					[
						'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-item-link.pp-slide-menu-item-link-current,
							 {{WRAPPER}} .pp-slide-menu-item-link.pp-slide-menu-item-link-current:hover,
							 {{WRAPPER}} .pp-slide-menu-item-link.pp-slide-menu-item-link-ancestor,
							 {{WRAPPER}} .pp-slide-menu-item-link.pp-slide-menu-item-link-ancestor:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'links_background_current',
					[
						'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-item-link.pp-slide-menu-item-link-current,
							 {{WRAPPER}} .pp-slide-menu-item-link.pp-slide-menu-item-link-current:hover,
							 {{WRAPPER}} .pp-slide-menu-item-link.pp-slide-menu-item-link-ancestor,
							 {{WRAPPER}} .pp-slide-menu-item-link.pp-slide-menu-item-link-ancestor:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'links_separator_color_current',
					[
						'label'     => esc_html__( 'Separator Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-menu-item.pp-menu-item-current,
							 {{WRAPPER}} .pp-menu-item.pp-menu-item-ancestor' => 'border-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Register sub-menu link style controls
	 *
	 * Sub-menu links inherit the Links styling unless this is switched on, so the
	 * common case stays a single set of controls.
	 *
	 * @since  3.0.0
	 * @access protected
	 */
	protected function register_style_submenu_controls() {
		$this->start_controls_section(
			'section_submenu_style',
			[
				'label' => esc_html__( 'Sub-menu Links', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'submenu_custom_style',
				[
					'label'        => esc_html__( 'Custom Sub-menu Styles', 'powerpack-lite-for-elementor' ),
					'type'         => Controls_Manager::SWITCHER,
					'description'  => esc_html__( 'Style links below the top level separately. When off, they follow the Links section.', 'powerpack-lite-for-elementor' ),
					'return_value' => 'yes',
				]
			);

			// Two classes plus the wrapper, so these outrank the equivalent Links
			// rules whatever order Elementor emits them in.
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'submenu_typography',
					'label'     => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
					'selector'  => '{{WRAPPER}} .pp-slide-menu-item-link.pp-menu-sub-item-link',
					'condition' => [
						'submenu_custom_style' => 'yes',
					],
				]
			);

			$this->start_controls_tabs(
				'submenu_links',
				[
					'condition' => [
						'submenu_custom_style' => 'yes',
					],
				]
			);

			$this->start_controls_tab( 'submenu_links_normal', [ 'label' => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ) ] );

				$this->add_control(
					'submenu_links_color',
					[
						'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-item-link.pp-menu-sub-item-link' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'submenu_links_background',
					[
						'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-item-link.pp-menu-sub-item-link' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'submenu_links_hover', [ 'label' => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ) ] );

				$this->add_control(
					'submenu_links_color_hover',
					[
						'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-item-link.pp-menu-sub-item-link:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'submenu_links_background_hover',
					[
						'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-item-link.pp-menu-sub-item-link:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'submenu_links_current', [ 'label' => esc_html__( 'Current', 'powerpack-lite-for-elementor' ) ] );

				$this->add_control(
					'submenu_links_color_current',
					[
						'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .pp-menu-sub-item-link.pp-slide-menu-item-link-current,
							 {{WRAPPER}} .pp-menu-sub-item-link.pp-slide-menu-item-link-current:hover,
							 {{WRAPPER}} .pp-menu-sub-item-link.pp-slide-menu-item-link-ancestor,
							 {{WRAPPER}} .pp-menu-sub-item-link.pp-slide-menu-item-link-ancestor:hover' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'submenu_links_background_current',
					[
						'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .pp-menu-sub-item-link.pp-slide-menu-item-link-current,
							 {{WRAPPER}} .pp-menu-sub-item-link.pp-slide-menu-item-link-current:hover,
							 {{WRAPPER}} .pp-menu-sub-item-link.pp-slide-menu-item-link-ancestor,
							 {{WRAPPER}} .pp-menu-sub-item-link.pp-slide-menu-item-link-ancestor:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Register item description style controls
	 *
	 * @since  3.0.0
	 * @access protected
	 */
	protected function register_style_description_controls() {
		$this->start_controls_section(
			'section_description_style',
			[
				'label'     => esc_html__( 'Descriptions', 'powerpack-lite-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_description' => 'yes',
				],
			]
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'description_typography',
					'label'    => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
					'selector' => '{{WRAPPER}} .pp-slide-menu-item-description',
				]
			);

			$this->add_control(
				'description_color',
				[
					'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .pp-slide-menu-item-description' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'description_spacing',
				[
					'label'      => esc_html__( 'Spacing', 'powerpack-lite-for-elementor' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem', 'custom' ],
					'default'    => [
						'size' => 3,
						'unit' => 'px',
					],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 50,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .pp-slide-menu-item-description' => 'margin-top: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();

	}

	/**
	 * Register links style Controls
	 *
	 * @since  3.0.0
	 * @access protected
	 */
	protected function register_style_arrows_controls() {
		$this->start_controls_section(
			'section_arrows_style',
			[
				'label' => esc_html__( 'Arrows', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'arrows_separator_thickness',
				[
					'label'      => esc_html__( 'Separator Thickness', 'powerpack-lite-for-elementor' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem', 'custom' ],
					'range'      => [
						'px' => [
							'min' => 0,
							'max' => 50,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .pp-slide-menu-item-has-children > .pp-slide-menu-arrow' => 'border-left-width: {{SIZE}}{{UNIT}}; border-left-style: solid;',
						'{{WRAPPER}} .pp-slide-menu-back > .pp-slide-menu-arrow' => 'border-right-width: {{SIZE}}{{UNIT}}; border-right-style: solid;',
					],
				]
			);

			$this->start_controls_tabs( 'arrows_type' );

			$this->start_controls_tab( 'arrows_regular', [ 'label' => esc_html__( 'Default', 'powerpack-lite-for-elementor' ) ] );

				$this->add_responsive_control(
					'arrows_size',
					[
						'label'      => esc_html__( 'Size', 'powerpack-lite-for-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'default'    => [ 'size' => '20' ],
						'range'      => [
							'px' => [
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							],
						],
						'size_units' => [ 'px', 'em', 'rem', 'custom' ],
						'selectors'  => [
							'{{WRAPPER}} .pp-slide-menu-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'arrows_padding',
					[
						'label'              => esc_html__( 'Padding', 'powerpack-lite-for-elementor' ),
						'type'               => Controls_Manager::DIMENSIONS,
						'size_units'         => [ 'px', '%', 'em', 'rem', 'custom' ],
						'allowed_dimensions' => [ 'right', 'left' ],
						'selectors'          => [
							'{{WRAPPER}} .pp-slide-menu-arrow' => 'padding-right: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'arrows_back', [ 'label' => esc_html__( 'Back', 'powerpack-lite-for-elementor' ) ] );

				$this->add_responsive_control(
					'arrows_back_size',
					[
						'label'      => esc_html__( 'Size', 'powerpack-lite-for-elementor' ),
						'type'       => Controls_Manager::SLIDER,
						'default'    => [ 'size' => '20' ],
						'range'      => [
							'px' => [
								'min'  => 0,
								'max'  => 100,
								'step' => 1,
							],
						],
						'size_units' => [ 'px', 'em', 'rem', 'custom' ],
						'selectors'  => [
							'{{WRAPPER}} .pp-slide-menu-back .pp-slide-menu-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'arrows_back_padding',
					[
						'label'              => esc_html__( 'Padding', 'powerpack-lite-for-elementor' ),
						'type'               => Controls_Manager::DIMENSIONS,
						'size_units'         => [ 'px', '%', 'em', 'rem', 'custom' ],
						'allowed_dimensions' => [ 'right', 'left' ],
						'selectors'          => [
							'{{WRAPPER}} .pp-slide-menu-back .pp-slide-menu-arrow' => 'padding-right: {{RIGHT}}{{UNIT}}; padding-left: {{LEFT}}{{UNIT}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->start_controls_tabs( 'arrows' );

			$this->start_controls_tab( 'arrows_default', [ 'label' => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ) ] );

				$this->add_control(
					'arrows_color',
					[
						'label'     => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-arrow' => 'color: {{VALUE}};',
							'{{WRAPPER}} .pp-slide-menu-arrow svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'arrows_background',
					[
						'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-arrow' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'arrows_separator_color',
					[
						'label'     => esc_html__( 'Separator Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-arrow' => 'border-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab( 'arrows_hover', [ 'label' => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ) ] );

				$this->add_control(
					'arrows_color_hover',
					[
						'label'     => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-arrow:hover' => 'color: {{VALUE}};',
							'{{WRAPPER}} .pp-slide-menu-arrow:hover svg' => 'fill: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'arrows_background_hover',
					[
						'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-arrow:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'arrows_separator_color_hover',
					[
						'label'     => esc_html__( 'Separator Color', 'powerpack-lite-for-elementor' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .pp-slide-menu-arrow:hover' => 'border-color: {{VALUE}};',
						],
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

	}

	/**
	 * Render widget output
	 *
	 * @since 3.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function render() {

		$available_menus = $this->get_available_menus();

		if ( ! $available_menus ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		// No menu is stored when the site had none at the time the widget was saved.
		if ( empty( $settings['menu'] ) ) {
			return;
		}

		$menu_name = ! empty( $available_menus[ $settings['menu'] ] ) ? $available_menus[ $settings['menu'] ] : esc_html__( 'Menu', 'powerpack-lite-for-elementor' );

		$this->submenu_icon_html = $this->get_icon_html( $settings['submenu_icon'] );
		$this->max_depth         = ! empty( $settings['max_depth'] ) ? absint( $settings['max_depth'] ) : 0;
		$this->show_description  = ! empty( $settings['show_description'] );
		$back_icon               = $this->get_icon_html( $settings['back_icon'] );

		$args = [
			'echo'          => false,
			'menu'          => $settings['menu'],
			'menu_class'    => 'pp-menu-menu pp-slide-menu__menu',
			'menu_id'       => 'menu-' . $this->get_nav_menu_index() . '-' . $this->get_id(),
			'fallback_cb'   => '__return_empty_string',
			'container'     => '',
		];

		if ( $this->max_depth ) {
			$args['depth'] = $this->max_depth;
		}

		add_filter( 'walker_nav_menu_start_el', [ $this, 'filter_nav_menu_start_el' ], 10, 4 );
		add_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ], 10, 4 );
		add_filter( 'nav_menu_submenu_css_class', [ $this, 'handle_sub_menu_classes' ] );
		add_filter( 'nav_menu_css_class', [ $this, 'handle_menu_item_classes' ] );
		// The same menu can be output more than once on a page, so the per-item IDs
		// have to be dropped to keep them unique.
		add_filter( 'nav_menu_item_id', '__return_empty_string' );

		// General Menu.
		$menu_html = wp_nav_menu( $args );

		remove_filter( 'nav_menu_item_id', '__return_empty_string' );
		remove_filter( 'nav_menu_link_attributes', [ $this, 'handle_link_classes' ] );
		remove_filter( 'nav_menu_submenu_css_class', [ $this, 'handle_sub_menu_classes' ] );
		remove_filter( 'nav_menu_css_class', [ $this, 'handle_menu_item_classes' ] );
		remove_filter( 'walker_nav_menu_start_el', [ $this, 'filter_nav_menu_start_el' ] );

		$this->add_render_attribute( [
			'wrapper' => [
				'class'      => [
					'pp-menu',
					'pp-slide-menu',
				],
				'aria-label' => $menu_name,
			],
		] );

		?>
		<nav <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<span class="pp-slide-menu-back-icon" hidden><?php echo $back_icon; ?></span>
			<?php echo $menu_html; ?>
		</nav><?php
	}

	/**
	 * Render an icon control's markup.
	 *
	 * The icon can be cleared in the panel, in which case there is no library to
	 * render from and the toggle falls back to its padding for a hit area.
	 *
	 * @since  3.0.0
	 * @access protected
	 *
	 * @param array $icon Icon control value.
	 * @return string
	 */
	protected function get_icon_html( $icon ) {
		if ( empty( $icon['value'] ) ) {
			return '';
		}

		ob_start();
		Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );

		return ob_get_clean();
	}

	/**
	 * Filter for walker_nav_menu_start_el
	 *
	 * Prepends the submenu toggle to items that actually have children, so leaf
	 * items are not burdened with an arrow that only gets hidden again by CSS.
	 *
	 * @since  3.0.0
	 *
	 * @param string   $item_output Rendered menu item HTML.
	 * @param WP_Post  $item        Menu item object.
	 * @param int      $depth       Depth of the menu item.
	 * @param stdClass $args        wp_nav_menu arguments.
	 * @return string
	 */
	public function filter_nav_menu_start_el( $item_output, $item, $depth, $args ) {
		if ( $this->show_description && ! empty( $item->description ) ) {
			$item_output = $this->append_description( $item_output, $item->description );
		}

		if ( $this->item_has_rendered_children( $item, $depth ) ) {
			$item_output = $this->get_submenu_toggle_html( $item ) . $item_output;
		}

		/**
		 * Filters a slide menu item's rendered markup.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output Rendered menu item HTML.
		 * @param WP_Post  $item        Menu item object.
		 * @param int      $depth       Depth of the menu item.
		 * @param stdClass $args        wp_nav_menu arguments.
		 */
		return apply_filters( 'pp_slide_menu_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Whether an item's children are actually rendered.
	 *
	 * WordPress strips menu-item-has-children from the class filter at the depth
	 * limit, but not from the item object this filter receives, so an item on the
	 * last rendered level would otherwise get a toggle for a panel that is not
	 * there.
	 *
	 * @since  3.0.0
	 * @access protected
	 *
	 * @param WP_Post $item  Menu item object.
	 * @param int     $depth Zero-based depth of the menu item.
	 * @return bool
	 */
	protected function item_has_rendered_children( $item, $depth ) {
		if ( ! in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
			return false;
		}

		return ! ( $this->max_depth && ( $depth + 1 ) >= $this->max_depth );
	}

	/**
	 * Append a menu item's description inside its link.
	 *
	 * Injected at the last closing anchor so the description stays part of the
	 * link's accessible name and shares its hit area.
	 *
	 * @since  3.0.0
	 * @access protected
	 *
	 * @param string $item_output Rendered menu item HTML.
	 * @param string $description Menu item description.
	 * @return string
	 */
	protected function append_description( $item_output, $description ) {
		$position = strrpos( $item_output, '</a>' );

		if ( false === $position ) {
			return $item_output;
		}

		$markup = sprintf(
			'<span class="pp-slide-menu-item-description">%s</span>',
			wp_kses_post( $description )
		);

		return substr_replace( $item_output, $markup . '</a>', $position, strlen( '</a>' ) );
	}

	/**
	 * Build the submenu toggle markup for a parent menu item.
	 *
	 * The toggle is a span rather than a button so it can sit inside the menu
	 * item row without inheriting button semantics from the theme, so it has to
	 * carry the button role, keyboard affordance and state explicitly.
	 *
	 * @since  3.0.0
	 *
	 * @param WP_Post $item Menu item object.
	 * @return string
	 */
	protected function get_submenu_toggle_html( $item ) {
		$label = sprintf(
			/* translators: %s: Menu item label. */
			__( 'Open %s submenu', 'powerpack-lite-for-elementor' ),
			wp_strip_all_tags( $item->title )
		);

		return sprintf(
			'<span class="pp-slide-menu-arrow pp-icon" role="button" tabindex="0" aria-expanded="false" aria-label="%1$s">%2$s</span>',
			esc_attr( $label ),
			$this->submenu_icon_html
		);
	}

	/**
	 * Handle Link Classes
	 *
	 * Filter for nav_menu_link_attributes.
	 *
	 * @since  3.0.0
	 *
	 * @param array    $atts  Menu item link attributes.
	 * @param WP_Post  $item  Menu item object.
	 * @param stdClass $args  wp_nav_menu arguments.
	 * @param int      $depth Depth of the menu item.
	 * @return array
	 */
	public function handle_link_classes( $atts, $item, $args, $depth ) {
		$classes = $depth ? 'pp-slide-menu-item-link pp-menu-sub-item-link' : 'pp-slide-menu-item-link';

		if ( in_array( 'current-menu-item', $item->classes ) ) {
			$classes .= ' pp-slide-menu-item-link-current';
		}

		if ( $this->is_ancestor_item( $item->classes ) ) {
			$classes .= ' pp-slide-menu-item-link-ancestor';
		}

		if ( empty( $atts['class'] ) ) {
			$atts['class'] = $classes;
		} else {
			$atts['class'] .= ' ' . $classes;
		}

		return $atts;
	}

	/**
	 * Handle Menu Items Classes
	 *
	 * Filter for nav_menu_css_class.
	 *
	 * @since  3.0.0
	 *
	 * @param array $classes Menu item classes.
	 * @return array
	 */
	public function handle_menu_item_classes( $classes ) {
		$classes[] = 'pp-menu-item pp-slide-menu-item';

		if ( in_array( 'menu-item-has-children', $classes ) ) {
			$classes[] = 'pp-slide-menu-item-has-children';
		}

		if ( in_array( 'current-menu-item', $classes ) ) {
			$classes[] = 'pp-menu-item-current';
		}

		if ( $this->is_ancestor_item( $classes ) ) {
			$classes[] = 'pp-menu-item-ancestor';
		}

		return $classes;
	}

	/**
	 * Check whether a menu item is on the path to the current page.
	 *
	 * The current item usually sits inside a closed sub-menu, so without marking
	 * its ancestors there is nothing to highlight on the level being viewed.
	 *
	 * @since  3.0.0
	 *
	 * @param array $classes Menu item classes.
	 * @return bool
	 */
	protected function is_ancestor_item( $classes ) {
		$ancestor_classes = [
			'current-menu-ancestor',
			'current-menu-parent',
			'current_page_ancestor',
			'current_page_parent',
		];

		return (bool) array_intersect( $ancestor_classes, (array) $classes );
	}

	/**
	 * Handle Sub-Menu Items Classes
	 *
	 * Filter for nav_menu_submenu_css_class.
	 *
	 * @since  3.0.0
	 *
	 * @param array $classes Sub-menu classes.
	 * @return array
	 */
	public function handle_sub_menu_classes( $classes ) {
		$classes[] = 'pp-slide-menu-sub-menu';
		$classes[] = 'pp-menu-sub-menu';

		return $classes;
	}

}
