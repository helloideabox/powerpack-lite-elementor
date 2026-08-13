<?php
namespace PowerpackElementsLite\Modules\EventCalendar\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Event_Calendar_Helper;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Event Calendar Widget
 */
class Event_Calendar extends Powerpack_Widget {

	/**
	 * Retrieve Event Calendar widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return parent::get_widget_name( 'Event_Calendar' );
	}

	/**
	 * Retrieve Event Calendar widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return parent::get_widget_title( 'Event_Calendar' );
	}

	/**
	 * Retrieve Event Calendar widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return parent::get_widget_icon( 'Event_Calendar' );
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
		return parent::get_widget_keywords( 'Event_Calendar' );
	}

	/**
	 * Retrieve the list of scripts the Event Calendar widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [
			'fullcalendar',
			'pp-event-calendar',
		];
	}

	/**
	 * Retrieve the list of styles the Event Calendar widget depended on.
	 *
	 * Used to set styles dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_style_depends() {
		// FullCalendar 6 carries its own CSS inside the script bundle and injects
		// it on init, so there is no vendor stylesheet to depend on here.
		return [
			'widget-pp-event-calendar',
		];
	}

	/**
	 * Register Event Calendar widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
	protected function _register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore
		$this->register_controls();
	}

	/**
	 * Register Event Calendar widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 3.0.0
	 * @access protected
	 */
	protected function register_controls() {
		/* Content Tab */
		$this->register_content_event_controls();
		$this->register_content_header_toolbar_controls();
		$this->register_content_footer_toolbar_controls();
		$this->register_content_event_click_controls();
		$this->register_content_navigation_controls();
		$this->register_content_settings_controls();

		/* Style Tab */
		$this->register_style_calendar_controls();
		$this->register_style_toolbar_controls();
		$this->register_style_header_controls();
		$this->register_style_rows_controls();
		$this->register_style_days_controls();
		$this->register_style_event_controls();
		$this->register_popup_style_controls();
	}

	/*-----------------------------------------------------------------------------------*/
	/*	CONTENT TAB
	/*-----------------------------------------------------------------------------------*/

	/**
	 * Content Tab: Calendar
	 */
	protected function register_content_event_controls() {
		$this->start_controls_section(
			'section_calendar_events',
			[
				'label' => esc_html__( 'Events', 'powerpack-lite-for-elementor' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'tabs_events' );

		$repeater->start_controls_tab(
			'tab_event_details',
			[
				'label' => esc_html__( 'Details', 'powerpack-lite-for-elementor' ),
			]
		);

		$repeater->add_control(
			'event_title',
			[
				'label'       => esc_html__( 'Event Title', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Event Title', 'powerpack-lite-for-elementor' ),
				'label_block' => true,
				'dynamic'     => [
					'active'   => true,
				],
			]
		);

		$repeater->add_control(
			'guest',
			[
				'label'       => esc_html__( 'Guest/Speaker', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( 'John Doe', 'powerpack-lite-for-elementor' ),
				'ai'          => [
					'active' => false,
				],
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'location',
			[
				'label'       => esc_html__( 'Location', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => esc_html__( '4382 Roosevelt Road, KS, Kansas', 'powerpack-lite-for-elementor' ),
				'ai'          => [
					'active' => false,
				],
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'all_day',
			[
				'label'        => esc_html__( 'All Day Event', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_block'  => false,
				'return_value' => 'yes',
			]
		);

		$repeater->add_control(
			'start_event',
			[
				'label'       => esc_html__( 'Start Time', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::DATE_TIME,
				'label_block' => true,
				'multiple'    => false,
				'placeholder' => esc_html__( 'Choose', 'powerpack-lite-for-elementor' ),
				'default'     => gmdate( 'Y-m-d H:i', current_time( 'timestamp', 0 ) ),
				'condition'   => [
					'all_day' => '',
				],
			]
		);

		$repeater->add_control(
			'end_event',
			[
				'label'       => esc_html__( 'End Time', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::DATE_TIME,
				'label_block' => true,
				'multiple'    => false,
				'placeholder' => esc_html__( 'Choose', 'powerpack-lite-for-elementor' ),
				'default'     => gmdate( 'Y-m-d H:i', strtotime( '+59 minute', current_time( 'timestamp', 0 ) ) ),
				'condition'   => [
					'all_day' => '',
				],
			]
		);

		$repeater->add_control(
			'start_event_allday',
			[
				'label'          => esc_html__( 'Start Date', 'powerpack-lite-for-elementor' ),
				'type'           => Controls_Manager::DATE_TIME,
				'picker_options' => ['enableTime' => false],
				'default'        => gmdate( 'Y-m-d', current_time( 'timestamp', 0 ) ),
				'condition'      => [
					'all_day' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'end_event_allday',
			[
				'label'          => esc_html__( 'End Date', 'powerpack-lite-for-elementor' ),
				'type'           => Controls_Manager::DATE_TIME,
				'picker_options' => ['enableTime' => false],
				'default'        => gmdate( 'Y-m-d', current_time( 'timestamp', 0 ) ),
				'condition'      => [
					'all_day' => 'yes',
				],
			]
		);

		$repeater->add_control(
			'event_url',
			[
				'label'       => esc_html__( 'Event Link', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::URL,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => [
					'url' => 'https://powerpackelements.com/',
				],
				'placeholder' => esc_html__( 'https://powerpackelements.com/', 'powerpack-lite-for-elementor' ),
				'description' => esc_html__( 'Opened when "On Event Click" is set to "Open Link". Also used as the "Read More" link inside the event popup.', 'powerpack-lite-for-elementor' ),
			]
		);

		$repeater->add_control(
			'image',
			[
				'label'   => esc_html__( 'Choose Image', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'label'     => 'Thumbnail Size',
				'default'   => 'thumbnail',
				'separator' => 'before',
				'exclude'   => [
					'custom',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_event_description',
			[
				'label' => esc_html__( 'Description', 'powerpack-lite-for-elementor' ),
			]
		);

		$repeater->add_control(
			'description',
			[
				'label'      => esc_html__( 'Description', 'powerpack-lite-for-elementor' ),
				'show_label' => true,
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => esc_html__( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries', 'powerpack-lite-for-elementor' )
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'tab_event_style',
			[
				'label' => esc_html__( 'Style', 'powerpack-lite-for-elementor' ),
			]
		);

		$repeater->add_control(
			'custom_style',
			[
				'label'          => esc_html__( 'Custom Style', 'powerpack-lite-for-elementor' ),
				'type'           => Controls_Manager::SWITCHER,
				'label_on'       => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'      => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'return_value'   => 'yes',
				'default'        => 'no',
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'text_color',
			[
				'label'          => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					'{{WRAPPER}} .pp-event-calendar-container {{CURRENT_ITEM}}' => 'color: {{VALUE}}!important;',
					'{{WRAPPER}} .pp-event-calendar-container {{CURRENT_ITEM}} .fc-event-main' => 'color: {{VALUE}}!important;',
				],
				'condition'      => [
					'custom_style' => 'yes',
				],
				'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'event_color',
			[
				'label'          => esc_html__( 'Event Color', 'powerpack-lite-for-elementor' ),
				'type'           => Controls_Manager::COLOR,
				'selectors'      => [
					// Block events (all-day, time grid, Block display): fill and border.
					'{{WRAPPER}} .pp-event-calendar-container {{CURRENT_ITEM}}.fc-daygrid-block-event' => 'background-color: {{VALUE}}!important; border-color: {{VALUE}}!important;',
					'{{WRAPPER}} .pp-event-calendar-container {{CURRENT_ITEM}}.fc-timegrid-event' => 'background-color: {{VALUE}}!important; border-color: {{VALUE}}!important;',
					// Dot events (auto timed, list, Dot display): color the dot.
					'{{WRAPPER}} .pp-event-calendar-container {{CURRENT_ITEM}} .fc-daygrid-event-dot' => 'border-color: {{VALUE}}!important;',
					'{{WRAPPER}} .pp-event-calendar-container {{CURRENT_ITEM}} .fc-list-event-dot' => 'border-color: {{VALUE}}!important;',
				],
				'condition'      => [
					'custom_style' => 'yes',
				],
				'style_transfer' => true,
			]
		);

		$repeater->end_controls_tab();

		$month = gmdate( 'Y-m', current_time( 'timestamp', 0 ) );

		$this->add_control(
			'events',
			[
				'label'              => esc_html__( 'Events', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::REPEATER,
				'default'            => [
					[
						'event_title'  => esc_html__( 'Product Strategy Workshop', 'powerpack-lite-for-elementor' ),
						'guest'        => esc_html__( 'Sarah Mitchell', 'powerpack-lite-for-elementor' ),
						'location'     => esc_html__( 'Convention Center, Hall A, Chicago', 'powerpack-lite-for-elementor' ),
						'start_event'  => $month . '-08 10:00',
						'end_event'    => $month . '-08 11:00',
						'custom_style' => 'yes',
						'event_color'  => '#5b8aa6',
					],
					[
						'event_title'  => esc_html__( 'Annual Design Conference', 'powerpack-lite-for-elementor' ),
						'guest'        => esc_html__( 'James Carter', 'powerpack-lite-for-elementor' ),
						'location'     => esc_html__( 'Grand Plaza Hotel, New York', 'powerpack-lite-for-elementor' ),
						'start_event'  => $month . '-08 14:00',
						'end_event'    => $month . '-08 15:00',
						'custom_style' => 'yes',
						'event_color'  => '#7a9b76',
					],
					[
						'event_title'  => esc_html__( 'Marketing Masterclass', 'powerpack-lite-for-elementor' ),
						'guest'        => esc_html__( 'Emily Roberts', 'powerpack-lite-for-elementor' ),
						'location'     => esc_html__( 'Riverside Auditorium, Austin', 'powerpack-lite-for-elementor' ),
						'start_event'  => $month . '-13 09:30',
						'end_event'    => $month . '-13 10:30',
						'custom_style' => 'yes',
						'event_color'  => '#bd8158',
					],
					[
						'event_title'  => esc_html__( 'Tech Leadership Summit', 'powerpack-lite-for-elementor' ),
						'guest'        => esc_html__( 'Michael Chen', 'powerpack-lite-for-elementor' ),
						'location'     => esc_html__( 'Innovation Hub, San Francisco', 'powerpack-lite-for-elementor' ),
						'start_event'  => $month . '-28 13:00',
						'end_event'    => $month . '-28 14:00',
						'custom_style' => 'yes',
						'event_color'  => '#8d7399',
					],
				],
				'fields'             => $repeater->get_controls(),
				'title_field'        => '{{{ event_title }}}',
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Button options shared across every toolbar slot.
	 *
	 * The paid edition also offers buttons that switch the calendar between its
	 * week, day and list views. This edition renders the month grid only, so a
	 * switcher would have nothing to switch to and they are left out.
	 */
	protected function get_toolbar_button_options() {
		return [
			'title'    => esc_html__( 'Calendar Title', 'powerpack-lite-for-elementor' ),
			'prevYear' => esc_html__( 'Previous Year', 'powerpack-lite-for-elementor' ),
			'prev'     => esc_html__( 'Previous Month', 'powerpack-lite-for-elementor' ),
			'next'     => esc_html__( 'Next Month', 'powerpack-lite-for-elementor' ),
			'nextYear' => esc_html__( 'Next Year', 'powerpack-lite-for-elementor' ),
			'today'    => esc_html__( 'Today', 'powerpack-lite-for-elementor' ),
		];
	}

	/**
	 * Register a single toolbar slot (header/footer × left/center/right) as a repeater of button groups.
	 * Each row is one group: buttons within a row render touching; rows render space-separated.
	 *
	 * @param string $key       Control key (e.g. 'header_left_groups').
	 * @param string $label     Visible label (e.g. 'Header Left').
	 * @param array  $defaults  Default groups, each a [ 'buttons' => [..] ] entry.
	 * @param array  $condition Optional Elementor condition.
	 */
	protected function add_toolbar_slot_control( $key, $label, $defaults = [], $condition = [] ) {
		$group_repeater = new Repeater();
		$group_repeater->add_control(
			'buttons',
			[
				'label'       => esc_html__( 'Buttons in Group', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_toolbar_button_options(),
				'description' => esc_html__( 'Buttons in a single group are placed together with no space between them. Add another group to insert a space.', 'powerpack-lite-for-elementor' ),
			]
		);

		$args = [
			'label'              => $label,
			'type'               => Controls_Manager::REPEATER,
			'fields'             => $group_repeater->get_controls(),
			'default'            => $defaults,
			'title_field'        => '<# if ( buttons && buttons.length ) { print( "[ " + buttons.join(" · ") + " ]" ); } else { print("Empty group"); } #>',
			'frontend_available' => true,
			'prevent_empty'      => false,
		];

		if ( ! empty( $condition ) ) {
			$args['condition'] = $condition;
		}

		$this->add_control( $key, $args );
	}

	protected function register_content_header_toolbar_controls() {
		$this->start_controls_section(
			'section_header_toolbar_settings',
			[
				'label' => esc_html__( 'Header Toolbar', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_toolbar_slot_control(
			'header_left_groups',
			esc_html__( 'Header Left', 'powerpack-lite-for-elementor' ),
			[ [ 'buttons' => [ 'prev', 'next' ] ] ]
		);

		$this->add_toolbar_slot_control(
			'header_center_groups',
			esc_html__( 'Header Center', 'powerpack-lite-for-elementor' ),
			[ [ 'buttons' => [ 'title' ] ] ]
		);

		$this->add_toolbar_slot_control(
			'header_right_groups',
			esc_html__( 'Header Right', 'powerpack-lite-for-elementor' ),
			[ [ 'buttons' => [ 'today' ] ] ]
		);

		$this->end_controls_section();
	}

	protected function register_content_footer_toolbar_controls() {
		$this->start_controls_section(
			'section_footer_toolbar_settings',
			[
				'label' => esc_html__( 'Footer Toolbar', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'show_footer_toolbar',
			[
				'label'              => esc_html__( 'Show Footer Toolbar', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => '',
				'label_on'           => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'          => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
			]
		);

		$footer_condition = [ 'show_footer_toolbar' => 'yes' ];

		$this->add_toolbar_slot_control(
			'footer_left_groups',
			esc_html__( 'Footer Left', 'powerpack-lite-for-elementor' ),
			[],
			$footer_condition
		);

		$this->add_toolbar_slot_control(
			'footer_center_groups',
			esc_html__( 'Footer Center', 'powerpack-lite-for-elementor' ),
			[],
			$footer_condition
		);

		$this->add_toolbar_slot_control(
			'footer_right_groups',
			esc_html__( 'Footer Right', 'powerpack-lite-for-elementor' ),
			[],
			$footer_condition
		);

		$this->end_controls_section();
	}

	protected function register_content_event_click_controls() {
		$this->start_controls_section(
			'section_event_click',
			[
				'label' => esc_html__( 'Event Click', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'event_click_action',
			[
				'label'              => esc_html__( 'On Event Click', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'popup',
				'options'            => [
					'popup' => esc_html__( 'Open Popup', 'powerpack-lite-for-elementor' ),
					'link'  => esc_html__( 'Open Link', 'powerpack-lite-for-elementor' ),
					'none'  => esc_html__( 'Do Nothing', 'powerpack-lite-for-elementor' ),
				],
				'description'        => esc_html__( 'Choose what happens when a visitor clicks an event. "Open Link" uses each event\'s "Event Link" field.', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'event_popup_layout',
			[
				'label'     => esc_html__( 'Popup Layout', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'layout-1',
				'options'   => [
					'layout-1' => esc_html__( 'Layout 1', 'powerpack-lite-for-elementor' ),
					'layout-2' => esc_html__( 'Layout 2', 'powerpack-lite-for-elementor' ),
					'layout-3' => esc_html__( 'Layout 3', 'powerpack-lite-for-elementor' ),
				],
				'condition' => [
					'event_click_action' => 'popup',
				],
			]
		);

		$this->add_control(
			'event_popup_header_fields_heading',
			[
				'label'     => esc_html__( 'Popup Header Fields', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'event_click_action' => 'popup',
				],
			]
		);

		$repeater_popup = new Repeater();

		$repeater_popup->add_control(
			'field_type',
			[
				'label'       => esc_html__( 'Field Type', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'event_time',
				'label_block' => true,
				'options'     => [
					'event_time'     => esc_html__( 'Event Time', 'powerpack-lite-for-elementor' ),
					'event_speaker'  => esc_html__( 'Event Speaker', 'powerpack-lite-for-elementor' ),
					'event_location' => esc_html__( 'Event Location', 'powerpack-lite-for-elementor' ),
				],
			]
		);

		$repeater_popup->add_control(
			'field_title',
			[
				'label'       => esc_html__( 'Field Title', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$repeater_popup->add_control(
			'field_allday_text',
			[
				'label'       => esc_html__( 'All Day Text', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active' => true,
				],
				'condition'   => [
					'field_type' => 'event_time',
				],
			]
		);

		$repeater_popup->add_control(
			'field_icon',
			[
				'label'       => esc_html__( 'Field Icon', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'skin'        => 'inline',
			]
		);

		$this->add_control(
			'popup_header_fields',
			[
				'label'              => '',
				'type'               => Controls_Manager::REPEATER,
				'default'               => [
					[
						'field_type'  => 'event_time',
						'field_title' => esc_html__( 'Event Time', 'powerpack-lite-for-elementor' ),
						'field_icon'  => [
							'value'   => 'fas fa-clock',
							'library' => 'fa-solid',
						]
					],
					[
						'field_type'  => 'event_speaker',
						'field_title' => esc_html__( 'Speaker', 'powerpack-lite-for-elementor' ),
						'field_icon'  => [
							'value'   => 'fas fa-user',
							'library' => 'fa-solid',
						]
					],
					[
						'field_type'  => 'event_location',
						'field_title' => esc_html__( 'Location', 'powerpack-lite-for-elementor' ),
						'field_icon'  => [
							'value'   => 'fas fa-map-marker-alt',
							'library' => 'fa-solid',
						]
					],
				],
				'fields'             => $repeater_popup->get_controls(),
				'title_field'        => sprintf(
					'{{{ "event_time" === field_type ? "%1$s" : ( "event_speaker" === field_type ? "%2$s" : ( "event_location" === field_type ? "%3$s" : field_type ) ) }}}',
					esc_js( esc_html__( 'Event Time', 'powerpack-lite-for-elementor' ) ),
					esc_js( esc_html__( 'Event Speaker', 'powerpack-lite-for-elementor' ) ),
					esc_js( esc_html__( 'Event Location', 'powerpack-lite-for-elementor' ) )
				),
				'frontend_available' => true,
				'condition'          => [
					'event_click_action' => 'popup',
				],
			]
		);

		$this->add_control(
			'allday_text',
			[
				'label'              => esc_html__( 'All Day Text', 'powerpack-lite-for-elementor' ),
				'label_block'        => false,
				'type'               => Controls_Manager::TEXT,
				'default'            => esc_html__( 'All Day', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
				'condition'          => [
					'event_click_action' => 'popup',
				],
			]
		);

		$this->add_control(
			'popup_read_more_heading',
			[
				'label'     => esc_html__( 'Read More', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'event_click_action' => 'popup',
				],
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label'        => esc_html__( 'Show Read More', 'powerpack-lite-for-elementor' ),
				'label_block'  => false,
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'event_click_action' => 'popup',
				],
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label'       => esc_html__( 'Read More Text', 'powerpack-lite-for-elementor' ),
				'label_block' => false,
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Read More', 'powerpack-lite-for-elementor' ),
				'condition'   => [
					'event_click_action' => 'popup',
					'show_read_more'     => 'yes',
				],
			]
		);

		$this->add_control(
			'event_popup_close_popup_heading',
			[
				'label'     => esc_html__( 'Popup Close Icon', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'event_click_action' => 'popup',
				],
			]
		);

		$this->add_control(
			'popup_close_icon',
			[
				'label'       => esc_html__( 'Close Icon', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::ICONS,
				'label_block' => false,
				'default'     => [
					'value'   => 'fa fa-times',
					'library' => 'fa-solid',
				],
				'skin'        => 'inline',
				'condition'   => [
					'event_click_action' => 'popup',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_navigation_controls() {
		$this->start_controls_section(
			'section_calendar_navigation',
			[
				'label' => esc_html__( 'Navigation', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'nav_links',
			[
				'label'              => esc_html__( 'Navigation Links', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'          => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'return_value'       => 'yes',
				'default'            => 'yes',
				'description'        => esc_html__( 'Determines if day names and week names are clickable.', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'nav_button_style',
			[
				'label'              => esc_html__( 'Prev/Next Button Style', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'arrows',
				'options'            => [
					'arrows' => esc_html__( 'Arrows', 'powerpack-lite-for-elementor' ),
					'text'   => esc_html__( 'Text', 'powerpack-lite-for-elementor' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'prev_button_text',
			[
				'label'              => esc_html__( 'Previous Button Text', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => esc_html__( 'Prev', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
				'condition'          => [
					'nav_button_style' => 'text',
				],
			]
		);

		$this->add_control(
			'next_button_text',
			[
				'label'              => esc_html__( 'Next Button Text', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => esc_html__( 'Next', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
				'condition'          => [
					'nav_button_style' => 'text',
				],
			]
		);

		$this->add_control(
			'prev_year_button_text',
			[
				'label'              => esc_html__( 'Previous Year Button Text', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => esc_html__( 'Prev Year', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
				'condition'          => [
					'nav_button_style' => 'text',
				],
			]
		);

		$this->add_control(
			'next_year_button_text',
			[
				'label'              => esc_html__( 'Next Year Button Text', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::TEXT,
				'default'            => esc_html__( 'Next Year', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
				'condition'          => [
					'nav_button_style' => 'text',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_settings_controls() {
		$this->start_controls_section(
			'section_calendar_settings',
			[
				'label' => esc_html__( 'Settings', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'first_day',
			[
				'label'              => esc_html__( 'First Day of Week', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '1',
				'options'            => [
					'0' => esc_html__( 'Sunday', 'powerpack-lite-for-elementor' ),
					'1' => esc_html__( 'Monday', 'powerpack-lite-for-elementor' ),
					'2' => esc_html__( 'Tuesday', 'powerpack-lite-for-elementor' ),
					'3' => esc_html__( 'Wednesday', 'powerpack-lite-for-elementor' ),
					'4' => esc_html__( 'Thursday', 'powerpack-lite-for-elementor' ),
					'5' => esc_html__( 'Friday', 'powerpack-lite-for-elementor' ),
					'6' => esc_html__( 'Saturday', 'powerpack-lite-for-elementor' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'default_current_month',
			[
				'label'              => esc_html__( 'Default to Current Month', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'yes',
				'label_on'           => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'          => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'default_month',
			[
				'label'              => esc_html__( 'Default Month', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::DATE_TIME,
				'label_block'        => false,
				'picker_options'     => [
					'enableTime' => false,
					'dateFormat' => 'Y-m',
				],
				'default'            => gmdate( 'Y-m-d' ),
				'frontend_available' => true,
				'condition'          => [
					'default_current_month' => '',
				],
			]
		);

		$this->add_control(
			'localization_heading',
			[
				'label'     => esc_html__( 'Localization', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'timezone',
			[
				'label'              => esc_html__( 'Timezone', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SELECT2,
				'options'            => PP_Event_Calendar_Helper::get_timezones(),
				'default'            => ( '' !== get_option( 'timezone_string' ) ) ? get_option( 'timezone_string' ) : 'UTC',
				'label_block'        => true,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'display_heading',
			[
				'label'     => esc_html__( 'Display', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'calendar_height_type',
			[
				'label'              => esc_html__( 'Height', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'auto',
				'options'            => [
					'auto'        => esc_html__( 'Auto', 'powerpack-lite-for-elementor' ),
					'fixed'       => esc_html__( 'Fixed', 'powerpack-lite-for-elementor' ),
					'aspectRatio' => esc_html__( 'Aspect Ratio', 'powerpack-lite-for-elementor' ),
				],
				'description'        => esc_html__( 'Auto expands to fit all content. Fixed sets a pixel height. Aspect Ratio sizes the height relative to the calendar width.', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'calendar_height',
			[
				'label'              => esc_html__( 'Custom Height', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', 'vh' ],
				'range'              => [
					'px' => [
						'min'  => 300,
						'max'  => 2000,
						'step' => 10,
					],
					'vh' => [
						'min'  => 20,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'            => [
					'size' => 600,
					'unit' => 'px',
				],
				'frontend_available' => true,
				'condition'          => [
					'calendar_height_type' => 'fixed',
				],
			]
		);

		$this->add_control(
			'calendar_aspect_ratio',
			[
				'label'              => esc_html__( 'Aspect Ratio', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '4:3',
				'options'            => [
					'1:1'  => '1:1',
					'3:2'  => '3:2',
					'4:3'  => '4:3',
					'9:16' => '9:16',
					'16:9' => '16:9',
					'21:9' => '21:9',
				],
				'description'        => esc_html__( 'The calendar height scales with its width to keep this ratio.', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
				'condition'          => [
					'calendar_height_type' => 'aspectRatio',
				],
			]
		);

		$this->add_control(
			'show_weekends',
			[
				'label'              => esc_html__( 'Weekends', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Show', 'powerpack-lite-for-elementor' ),
				'label_off'          => esc_html__( 'Hide', 'powerpack-lite-for-elementor' ),
				'return_value'       => 'yes',
				'default'            => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'hidden_days',
			[
				'label'              => esc_html__( 'Hide Days', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SELECT2,
				'multiple'           => true,
				'label_block'        => true,
				'options'            => [
					'0' => esc_html__( 'Sunday', 'powerpack-lite-for-elementor' ),
					'1' => esc_html__( 'Monday', 'powerpack-lite-for-elementor' ),
					'2' => esc_html__( 'Tuesday', 'powerpack-lite-for-elementor' ),
					'3' => esc_html__( 'Wednesday', 'powerpack-lite-for-elementor' ),
					'4' => esc_html__( 'Thursday', 'powerpack-lite-for-elementor' ),
					'5' => esc_html__( 'Friday', 'powerpack-lite-for-elementor' ),
					'6' => esc_html__( 'Saturday', 'powerpack-lite-for-elementor' ),
				],
				'description'        => esc_html__( 'Days selected here are hidden from every view.', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_week_numbers',
			[
				'label'              => esc_html__( 'Week Numbers', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'          => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'return_value'       => 'yes',
				'default'            => '',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'events_heading',
			[
				'label'     => esc_html__( 'Events', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'event_display',
			[
				'label'              => esc_html__( 'Event Display', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'auto',
				'options'            => [
					'auto'      => esc_html__( 'Auto', 'powerpack-lite-for-elementor' ),
					'block'     => esc_html__( 'Block', 'powerpack-lite-for-elementor' ),
					'list-item' => esc_html__( 'Dot', 'powerpack-lite-for-elementor' ),
				],
				'description'        => esc_html__( 'How events appear in month and day-grid views. Auto shows blocks for all-day events and dots for timed events.', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'display_event_time',
			[
				'label'              => esc_html__( 'Event Time', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Show', 'powerpack-lite-for-elementor' ),
				'label_off'          => esc_html__( 'Hide', 'powerpack-lite-for-elementor' ),
				'return_value'       => 'yes',
				'default'            => 'yes',
				'description'        => esc_html__( 'Show or hide the time text on events.', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'display_event_end',
			[
				'label'              => esc_html__( 'Event End Time', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => esc_html__( 'Show', 'powerpack-lite-for-elementor' ),
				'label_off'          => esc_html__( 'Hide', 'powerpack-lite-for-elementor' ),
				'return_value'       => 'yes',
				'default'            => 'yes',
				'description'        => esc_html__( 'Show or hide an event\'s end time alongside its start time.', 'powerpack-lite-for-elementor' ),
				'frontend_available' => true,
				'condition'          => [
					'display_event_time' => 'yes',
				],
			]
		);


		$this->end_controls_section();
	}

	protected function register_style_calendar_controls() {
		$this->start_controls_section(
			'section_calendar_style',
			[
				'label' => esc_html__( 'Calendar', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'calendar_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-view-harness' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'calendar_border',
				'label'       => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-event-calendar-container .fc-view-harness th, {{WRAPPER}} .pp-event-calendar-container .fc-view-harness td',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'calendar_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'powerpack-lite-for-elementor' ),
				'selector' => '{{WRAPPER}} .pp-event-calendar-container .fc-view-harness',
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_toolbar_controls() {
		$this->start_controls_section(
			'section_toolbar_style',
			[
				'label' => esc_html__( 'Toolbar', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'toolbar_spacing',
			[
				'label'       => esc_html__( 'Toolbar Spacing', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'description' => esc_html__( 'Gap between the calendar and the header/footer toolbars.', 'powerpack-lite-for-elementor' ),
				'selectors'   => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-header-toolbar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .pp-event-calendar-container .fc-footer-toolbar' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toolbar_title_heading',
			[
				'label'     => esc_html__( 'Title', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'calendar_title_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-toolbar-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'calendar_title_typography',
				'label'    => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .pp-event-calendar-container .fc-toolbar-title',
			]
		);

		$this->add_control(
			'buttons_heading',
			[
				'label'     => esc_html__( 'Buttons', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'label'    => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'selector' => '{{WRAPPER}} .pp-event-calendar-container .fc-button',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color_normal',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-button' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-event-calendar-container .fc-button svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_color_normal',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'button_border_normal',
				'label'       => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-event-calendar-container .fc-button',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .pp-event-calendar-container .fc-button',
			]
		);

		$this->add_responsive_control(
			'button_padding',
			[
				'label'      => esc_html__( 'Padding', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label'     => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-button:hover, {{WRAPPER}} .pp-event-calendar-container .fc-button:focus' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-button:hover, {{WRAPPER}} .pp-event-calendar-container .fc-button:focus' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_hover',
			[
				'label'     => esc_html__( 'Border Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-button:hover, {{WRAPPER}} .pp-event-calendar-container .fc-button:focus' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'button_box_shadow_hover',
				'selector'  => '{{WRAPPER}} .pp-event-calendar-container .fc-button:hover, {{WRAPPER}} .pp-event-calendar-container .fc-button:focus',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_active',
			[
				'label'     => esc_html__( 'Active', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'button_text_color_active',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-button.fc-button-active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_bg_color_active',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-button.fc-button-active' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'button_border_color_active',
			[
				'label'     => esc_html__( 'Border Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-button.fc-button-active' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'button_box_shadow_active',
				'selector'  => '{{WRAPPER}} .pp-event-calendar-container .fc-button.fc-button-active',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_header_controls() {

		$this->start_controls_section(
			'section_calendar_header_style',
			[
				'label' => esc_html__( 'Header', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'header_text_align',
			[
				'label'       => esc_html__( 'Text Align', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'left'   => [
						'title' => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'     => 'center',
				'selectors'   => [
					'{{WRAPPER}} .fc-scrollgrid thead .fc-scrollgrid-sync-inner, {{WRAPPER}} tr.fc-list-day th'   => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'calendar_header_typography',
				'label'    => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .fc-scrollgrid .fc-col-header th.fc-col-header-cell,{{WRAPPER}} tr.fc-list-day th',
			]
		);

		$this->start_controls_tabs( 'tabs_header_style' );

		$this->start_controls_tab(
			'tab_header_normal',
			[
				'label' => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'calendar_header_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .fc-scrollgrid .fc-col-header th.fc-col-header-cell .fc-scrollgrid-sync-inner a,{{WRAPPER}} tr.fc-list-day th a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'calendar_header_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F3F3F4',
				'selectors' => [
					'{{WRAPPER}} .fc-scrollgrid .fc-col-header th.fc-col-header-cell,{{WRAPPER}} tr.fc-list-day th' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'calendar_header_border',
				'label'       => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .fc-scrollgrid .fc-col-header th.fc-col-header-cell,{{WRAPPER}} tr.fc-list-day th',
			]
		);

		$this->add_responsive_control(
			'calendar_header_padding',
			[
				'label'      => esc_html__( 'Padding', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .fc-scrollgrid .fc-col-header th.fc-col-header-cell,{{WRAPPER}} tr.fc-list-day th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_header_hover',
			[
				'label' => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'calendar_header_text_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .fc-scrollgrid .fc-col-header th.fc-col-header-cell:hover .fc-scrollgrid-sync-inner a,{{WRAPPER}} tr.fc-list-day th:hover a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'calendar_header_background_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .fc-scrollgrid .fc-col-header th.fc-col-header-cell:hover,{{WRAPPER}} tr.fc-list-day th:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_rows_controls() {
		$this->start_controls_section(
			'section_calendar_rows_style',
			[
				'label' => esc_html__( 'Rows', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'calendar_striped_rows',
			[
				'label'        => esc_html__( 'Striped Rows', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'none',
				'options'      => [
					'none' => esc_html__( 'None', 'powerpack-lite-for-elementor' ),
					'even' => esc_html__( 'Even', 'powerpack-lite-for-elementor' ),
					'odd'  => esc_html__( 'Odd', 'powerpack-lite-for-elementor' ),
				],
				'prefix_class' => 'pp-event-calendar-rows--',
			]
		);

		$this->add_control(
			'calendar_rows_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .fc-scrollgrid-sync-table tbody tr td:not(.fc-day-today),{{WRAPPER}} tr.fc-list-event td' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'calendar_rows_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .fc-scrollgrid-sync-table tbody tr .fc-scrollgrid-sync-inner a,{{WRAPPER}} tr.fc-list-event td' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'calendar_rows_border',
				'label'       => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .fc-scrollgrid-sync-table tr,{{WRAPPER}} tr.fc-list-event',
			]
		);

		$this->add_control(
			'alternate_rows_heading',
			[
				'label'     => esc_html__( 'Alternate Rows', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'calendar_striped_rows' => [ 'even', 'odd' ],
				],
			]
		);

		$this->add_control(
			'calendar_alt_rows_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}.pp-event-calendar-rows--even .fc-scrollgrid-sync-table tbody tr:nth-child(even) td:not(.fc-day-today)' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}.pp-event-calendar-rows--odd .fc-scrollgrid-sync-table tbody tr:nth-child(odd) td:not(.fc-day-today)' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'calendar_striped_rows' => [ 'even', 'odd' ],
				],
			]
		);

		$this->add_control(
			'calendar_alt_rows_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}}.pp-event-calendar-rows--even .fc-scrollgrid-sync-table tbody tr:nth-child(even) .fc-scrollgrid-sync-inner a' => 'color: {{VALUE}}',
					'{{WRAPPER}}.pp-event-calendar-rows--odd .fc-scrollgrid-sync-table tbody tr:nth-child(odd) .fc-scrollgrid-sync-inner a' => 'color: {{VALUE}}',
				],
				'condition' => [
					'calendar_striped_rows' => [ 'even', 'odd' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'        => 'calendar_alt_rows_border',
				'label'       => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}}.pp-event-calendar-rows--even .fc-scrollgrid-sync-table tr:nth-child(even), {{WRAPPER}}.pp-event-calendar-rows--odd .fc-scrollgrid-sync-table tr:nth-child(odd)',
				'condition'   => [
					'calendar_striped_rows' => [ 'even', 'odd' ],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_days_controls() {
		$this->start_controls_section(
			'section_days_style',
			[
				'label' => esc_html__( 'Days', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'days_typography',
				'label'    => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .fc-scrollgrid-sync-table td .fc-scrollgrid-sync-inner',
			]
		);

		$this->add_control(
			'all_days_heading',
			[
				'label'     => esc_html__( 'All Days', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'tabs_days_style' );

		$this->start_controls_tab(
			'tab_days_normal',
			[
				'label' => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'days_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .fc-scrollgrid-sync-table td .fc-scrollgrid-sync-inner a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'days_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .fc-scrollgrid-sync-table td:not(.fc-day-today)' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_cell_hover',
			[
				'label' => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'days_text_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .fc-scrollgrid-sync-table td:hover .fc-scrollgrid-sync-inner a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'days_background_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .fc-scrollgrid-sync-table td:hover:not(.fc-day-today)' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'calendar_today_heading',
			[
				'label'     => esc_html__( 'Today', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'today_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-scrollgrid td.fc-day-today a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'today_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F3F3F4',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-scrollgrid td.fc-day-today' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'past_day_heading',
			[
				'label'     => esc_html__( 'Past Day', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'past_day_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-scrollgrid td.fc-day-past a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'past_day_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-scrollgrid td.fc-day-past' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'adjacent_days_heading',
			[
				'label'     => esc_html__( 'Adjacent Days', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'adjacent_day_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-scrollgrid td.fc-day-other a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'adjacent_day_background_color',
			[
				'label'     => esc_html__( 'Background Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-scrollgrid td.fc-day-other' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_event_controls() {
		$this->start_controls_section(
			'section_event_style',
			[
				'label' => esc_html__( 'Event', 'powerpack-lite-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'event_color',
			[
				'label'              => esc_html__( 'Event Color', 'powerpack-lite-for-elementor' ),
				'type'               => Controls_Manager::COLOR,
				'default'            => '#3788d8',
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'event_typography',
				'label'    => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .fc-daygrid-event',
			]
		);

		$this->start_controls_tabs( 'tabs_event_style' );

		$this->start_controls_tab(
			'tab_event_normal',
			[
				'label' => esc_html__( 'Normal', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'event_text_color_normal',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container' => '--fc-event-text-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'event_padding',
			[
				'label'      => esc_html__( 'Padding', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .fc-daygrid-event' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_event_hover',
			[
				'label' => esc_html__( 'Hover', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'event_text_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-container .fc-event:hover' => '--fc-event-text-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_popup_style_controls() {

		$this->start_controls_section(
			'section_style_event_popup',
			[
				'label'     => esc_html__( 'Event Popup', 'powerpack-lite-for-elementor' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'event_click_action' => 'popup',
				],
			]
		);

		$this->add_responsive_control(
			'event_popup_width',
			[
				'label'      => esc_html__( 'Popup Width', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1200,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'event_popup_padding',
			[
				'label'      => esc_html__( 'Content Padding', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'event_popup_overlay_background',
			[
				'label'     => esc_html__( 'Overlay Background', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper.pp-event-calendar-popup-ready:before' => 'background: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'event_popup_background',
			[
				'label'     => esc_html__( 'Popup Background', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'event_popup_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'event_popup_border',
				'label'    => esc_html__( 'Border', 'powerpack-lite-for-elementor' ),
				'selector' => '{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup',
			]
		);

		$this->add_control(
			'event_popup_image_heading',
			[
				'label'     => esc_html__( 'Image', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'event_popup_image_height',
			[
				'label'      => esc_html__( 'Height', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper' => '--pp-event-calendar-popup-image-height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'event_popup_layout!' => 'layout-3',
				],
			]
		);

		$this->add_control(
			'event_popup_image_width',
			[
				'label'      => esc_html__( 'Width', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper' => '--pp-event-calendar-popup-image-width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'event_popup_layout' => 'layout-3',
				],
			]
		);

		$this->add_control(
			'event_popup_title_heading',
			[
				'label'     => esc_html__( 'Title', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'event_popup_title_typography',
				'label'    => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'exclude'  => [
					'font_family',
				],
				'selector' => '{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-content h3',
			]
		);

		$this->add_control(
			'event_popup_title_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-content h3' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'event_popup_title_margin_bottom',
			[
				'label'      => esc_html__( 'Margin Bottom', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-content h3' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'event_popup_desc_heading',
			[
				'label'     => esc_html__( 'Description', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'event_popup_desc_typography',
				'label'    => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'exclude'  => [
					'font_family',
				],
				'selector' => '{{WRAPPER}} .pp-event-calendar-popup-wrapper p.pp-event-calendar-popup-desc',
			]
		);

		$this->add_control(
			'event_popup_desc_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper p.pp-event-calendar-popup-desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'event_popup_details_heading',
			[
				'label'     => esc_html__( 'Event Details', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'event_popup_details_text_typography',
				'label'    => esc_html__( 'Text Typography', 'powerpack-lite-for-elementor' ),
				'exclude'  => [
					'font_family',
				],
				'selector' => '{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-content .pp-event-calendar-event-detail',
			]
		);

		$this->add_control(
			'event_popup_details_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-content .pp-event-calendar-event-detail' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'event_popup_details_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-content .pp-event-calendar-popup-field-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'event_popup_details_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-content .pp-event-calendar-popup-field-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'event_popup_details_items_gap',
			[
				'label'      => esc_html__( 'Items Gap', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default'    => [
					'size' => 15,
					'unit' => 'px',
				],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-content ul' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'event_popup_readmore_heading',
			[
				'label'     => esc_html__( 'Read More', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'event_click_action' => 'popup',
					'show_read_more'     => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'event_popup_readmore_typography',
				'label'     => esc_html__( 'Typography', 'powerpack-lite-for-elementor' ),
				'selector'  => '{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-readmore-link',
				'condition' => [
					'event_click_action' => 'popup',
					'show_read_more'     => 'yes',
				],
			]
		);

		$this->add_control(
			'event_popup_readmore_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper a.pp-event-calendar-popup-readmore-link' => 'color: {{VALUE}}',
				],
				'condition' => [
					'event_click_action' => 'popup',
					'show_read_more'     => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'event_popup_readmore_spacing',
			[
				'label'      => esc_html__( 'Margin Top', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-readmore-link' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'event_popup_close_button_heading',
			[
				'label'     => esc_html__( 'Close Button', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'event_popup_close_button_font_size',
			[
				'label'      => esc_html__( 'Icon Size', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 200,
						'step' => 1,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-close' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'event_popup_close_button_color',
			[
				'label'     => esc_html__( 'Color', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-close' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'event_popup_close_button_background',
			[
				'label'     => esc_html__( 'Background', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-close' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'event_popup_close_button_box_shadow',
				'label'    => esc_html__( 'Box Shadow', 'powerpack-lite-for-elementor' ),
				'selector' => '{{WRAPPER}} .pp-event-calendar-popup-wrapper .pp-event-calendar-popup-close',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Build calendar event data from the custom (repeater) source.
	 *
	 * @since 3.0.0
	 * @access protected
	 * @return array {
	 *     @type bool   $status Whether any events were found.
	 *     @type string $error  Error message, empty on success.
	 *     @type array  $data   List of event arrays for FullCalendar.
	 * }
	 */
	protected function get_calendar_custom() {
		$settings = $this->get_settings_for_display();

		$cale_data     = [];
		$calendar_data = [];
		$events        = ! empty( $settings['events'] ) ? $settings['events'] : [];

		if ( empty( $events) ) {
			$calendar_data['status'] = false;
			$calendar_data['error']  = '';
			$calendar_data['data']   = [];

			return $calendar_data;
		}

		foreach ( $settings['events'] as $index => $item ) {

			if ( isset( $item['all_day'] ) && 'yes' === $item['all_day'] ) {
				$start = PP_Event_Calendar_Helper::get_timezones_converted_date( $item['start_event_allday'], 'Y-m-d', $settings['timezone'] );
				$end   = PP_Event_Calendar_Helper::get_timezones_converted_date( $item['end_event_allday'], 'Y-m-d', $settings['timezone'] );
			} else {
				$start = PP_Event_Calendar_Helper::get_timezones_converted_date( $item['start_event'], 'Y-m-d H:i:s', $settings['timezone'] );
				$end   = PP_Event_Calendar_Helper::get_timezones_converted_date( $item['end_event'], 'Y-m-d H:i:s', $settings['timezone'] );
			}

			$details_link = ! empty( $item['event_url']['url'] ) ? esc_url( $item['event_url']['url'] ) : '';
			if ( 'none' === $settings['event_click_action'] ) {
				// Non-clickable: drop the URL so FullCalendar renders a plain event.
				$details_link = '';
			} elseif ( 'popup' === $settings['event_click_action'] && empty( $details_link ) ) {
				// Popup with no link still needs an href so the event is clickable.
				$details_link = '#';
			}

			$image = ! empty( $item['image']['url'] ) ? esc_url( $item['image']['url'] ) : '';
			if ( ! empty( $item['image']['id'] ) ) {
				$image = esc_url( wp_get_attachment_image_url( $item['image']['id'], $item['thumbnail_size'] ) );
			}

			$cale_data[ $index ]['id']          = $index;
			$cale_data[ $index ]['classNames']  = 'elementor-repeater-item-' . esc_attr( $item['_id'] );
			$cale_data[ $index ]['title']       = esc_html( $item['event_title'] );
			$cale_data[ $index ]['description'] = wp_kses_post( $item['description'] );
			$cale_data[ $index ]['start']       = $start;
			$cale_data[ $index ]['end']         = $end;
			$cale_data[ $index ]['url']         = $details_link;
			$cale_data[ $index ]['allDay']      = esc_html( $item['all_day'] );
			$cale_data[ $index ]['external']    = esc_attr( $item['event_url']['is_external'] );
			$cale_data[ $index ]['nofollow']    = esc_attr( $item['event_url']['nofollow'] );
			$cale_data[ $index ]['guest']       = esc_html( $item['guest'] );
			$cale_data[ $index ]['location']    = esc_html( $item['location'] );
			$cale_data[ $index ]['image']       = $image;
		}
		$calendar_data['status'] = true;
		$calendar_data['error']  = '';
		$calendar_data['data']   = $cale_data;

		return $calendar_data;
	}

	/**
	 * Print the event popup markup.
	 *
	 * @since 3.0.0
	 * @access public
	 * @param array $settings Widget settings.
	 * @return void
	 */
	public function get_popup_markup( $settings ) {
		ob_start();

		$event_popup_layout = $settings['event_popup_layout'];
		?>
		<div class="pp-event-calendar-popup-wrapper pp-event-calendar-popup-<?php echo esc_attr( $event_popup_layout ); ?>">
			<div class="pp-event-calendar-popup">
				<?php if ( ! empty( $settings['popup_close_icon']['value'] ) ) { ?>
					<span class="pp-event-calendar-popup-close pp-icon">
						<?php Icons_Manager::render_icon( $settings['popup_close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					</span>
				<?php } ?>
				<div class="pp-event-calendar-popup-body-wrap">
					<div class="pp-event-calendar-popup-body">
						<div class="pp-event-calendar-popup-image">
							<img src="" alt="">
							<h3 class="pp-event-calendar-popup-image-title"></h3>
						</div>
						<div class="pp-event-calendar-popup-content">
							<h3 class="pp-event-calendar-event-title"></h3>
							<?php $popup_header_fields = $settings['popup_header_fields']; ?>
							<?php if ( ! empty( $popup_header_fields ) ) { ?>
								<ul>
									<?php
										$popup_header_fields = $settings['popup_header_fields'];

										foreach ( $popup_header_fields as $index => $field ) {
											$field_type  = $field['field_type'];

											switch ( $field_type ) {
												case 'event_time':
													$field_wrap_class = 'pp-event-calendar-event-time-wrap';
													$field_class = 'pp-event-calendar-event-time';
													break;

												case 'event_speaker':
													$field_wrap_class = 'pp-event-calendar-event-guest-wrap';
													$field_class = 'pp-event-calendar-event-guest';
													break;

												case 'event_location':
													$field_wrap_class = 'pp-event-calendar-event-location-wrap';
													$field_class = 'pp-event-calendar-event-location';
													break;

												default:
													$field_wrap_class = '';
													$field_class = '';
											}

											$this->add_render_attribute( 'popup-field-wrap-' . $index, 'class', $field_wrap_class );
											$this->add_render_attribute( 'popup-field-' . $index, 'class', [ 'pp-event-calendar-event-detail', $field_class ] );
											?>
											<li <?php $this->print_render_attribute_string( 'popup-field-wrap-' . $index ); ?>>
												<div class="pp-event-calendar-popup-field-icon">
													<?php Icons_Manager::render_icon( $field['field_icon'], [ 'aria-hidden' => 'true' ] ); ?>
												</div>
												<div class="pp-event-calendar-popup-field-content">
													<span <?php $this->print_render_attribute_string( 'popup-field-' . $index ); ?>></span>
												</div>
											</li>
											<?php
										}
									?>
								</ul>
							<?php } ?>
							<div class="pp-event-calendar-popup-desc"></div>
							<?php if ( 'yes' === $settings['show_read_more'] ) { ?>
								<?php $read_more_text = ! empty( $settings['read_more_text'] ) ? $settings['read_more_text'] : ''; ?>
								<div class="pp-event-calendar-popup-readmore">
									<a class="pp-event-calendar-popup-readmore-link" href=""><?php echo esc_html( $read_more_text ); ?></a>
								</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		echo ob_get_clean();
	}

	/**
	 * Render the calendar widget output on the frontend.
	 *
	 * @since 3.0.0
	 * @access protected
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$page_id  = '';

		if ( null !== \Elementor\Plugin::$instance->documents->get_current() ) {
			$page_id = \Elementor\Plugin::$instance->documents->get_current()->get_main_id();
		}

		$time_format = get_option( 'time_format' );

		$calendar_data = $this->get_calendar_custom();

		$this->add_render_attribute(
			'wrapper',
			[
				'class'            => 'pp-event-calendar-container',
				'data-page'        => $page_id,
				'data-source'      => 'custom',
				'data-time-format' => ! empty( $time_format ) ? $time_format : 'g:i a',
				'data-cal-status'  => ! empty( $calendar_data['status'] ) ? 'true' : 'false',
				'data-cal-error'   => ! empty( $calendar_data['error'] ) ? $calendar_data['error'] : '',
				'id'               => 'pp-event-calendar-' . $this->get_id(),
			]
		);
		?>

		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'wrapper' ) ); ?>></div>
		<script type="application/json" class="pp-event-calendar-events" id="pp-event-calendar-events-<?php echo esc_attr( $this->get_id() ); ?>"><?php
			// JSON_HEX_TAG escapes "<"/">" so the payload can never break out of
			// the script element; JSON.parse reverses the escaping client-side.
			echo wp_json_encode( ! empty( $calendar_data['data'] ) ? $calendar_data['data'] : [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?></script>

		<?php
		if ( 'popup' === $settings['event_click_action'] ) {
			$this->get_popup_markup( $settings );
		}
	}
}
