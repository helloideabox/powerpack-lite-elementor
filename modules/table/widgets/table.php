<?php
namespace PowerpackElementsLite\Modules\Table\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Config;
use PowerpackElementsLite\Classes\PP_Helper;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Utils;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Table Widget
 */
class Table extends Powerpack_Widget {

	public function get_name() {
		return parent::get_widget_name( 'Table' );
	}

	public function get_title() {
		return parent::get_widget_title( 'Table' );
	}

	public function get_icon() {
		return parent::get_widget_icon( 'Table' );
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
		return parent::get_widget_keywords( 'Table' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	public function get_script_depends() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return array(
				'tablesaw',
				'pp-table',
			);
		}

		$settings = $this->get_settings_for_display();
		$scripts = [
			'tablesaw',
			'pp-table'
		];

		return $scripts;
	}

	public function get_style_depends() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return array(
				'tablesaw',
				'widget-pp-table'
			);
		}

		$settings = $this->get_settings_for_display();
		$scripts = [
			'tablesaw',
			'widget-pp-table'
		];

		return $scripts;
	}

	public function has_widget_inner_wrapper(): bool {
		return ! PP_Helper::is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {

		/* Content Tab */
		$this->register_content_general_controls();
		$this->register_content_header_controls();
		$this->register_content_body_controls();
		$this->register_content_footer_controls();
		$this->register_content_tooltip_controls();
		$this->register_content_help_docs_controls();

		/* Style Tab */
		$this->register_style_table_controls();
		$this->register_style_header_controls();
		$this->register_style_rows_controls();
		$this->register_style_cells_controls();
		$this->register_style_footer_controls();
		$this->register_style_icon_controls();
		$this->register_style_columns_controls();
	}

	protected function register_content_general_controls() {
		$this->start_controls_section(
			'section_general',
			array(
				'label' => esc_html__( 'General', 'powerpack' ),
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => esc_html__( 'Source', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'manual',
				'options' => array(
					'manual' => esc_html__( 'Manual', 'powerpack' ),
					'file'   => esc_html__( 'CSV File', 'powerpack' ),
				),
			)
		);

		$this->add_control(
			'file',
			array(
				'label'     => esc_html__( 'Upload a CSV File', 'powerpack' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => array(
					'active'     => true,
					'categories' => array(
						TagsModule::MEDIA_CATEGORY,
					),
				),
				'condition' => array(
					'source' => 'file',
				),
			)
		);

		$this->add_control(
			'table_type',
			[
				'label'                 => esc_html__( 'Table Type', 'powerpack' ),
				'type'                  => Controls_Manager::SELECT,
				'default'               => 'responsive',
				'options'               => [
					'normal'        => esc_html__( 'Normal', 'powerpack' ),
					'responsive'    => esc_html__( 'Responsive', 'powerpack' ),
				],
				'frontend_available'    => true,
				'condition'             => [
					'hide_table_header!' => 'yes',
				],
			]
		);

		$this->add_control(
			'hide_table_header',
			array(
				'label'        => esc_html__( 'Hide Table Header', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'sortable',
			array(
				'label'        => esc_html__( 'Sortable Table', 'powerpack' ),
				'description'  => esc_html__( 'Enable sorting the table data by clicking on the table headers', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'On', 'powerpack' ),
				'label_off'    => esc_html__( 'Off', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'table_type'         => 'responsive',
					'hide_table_header!' => 'yes',
				),
			)
		);

		$this->add_control(
			'sortable_dropdown',
			array(
				'label'        => esc_html__( 'Sortable Dropdown', 'powerpack' ),
				'description'  => esc_html__( 'This will show dropdown menu to sort the table by columns', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'show',
				'label_on'     => esc_html__( 'Show', 'powerpack' ),
				'label_off'    => esc_html__( 'Hide', 'powerpack' ),
				'return_value' => 'show',
				'condition'    => array(
					'table_type'         => 'responsive',
					'sortable'           => 'yes',
					'hide_table_header!' => 'yes',
				),
			)
		);

		$this->add_control(
			'scrollable',
			array(
				'label'              => esc_html__( 'Scrollable Table', 'powerpack' ),
				'description'        => esc_html__( 'This will disable stacking and enable swipe/scroll when below breakpoint', 'powerpack' ),
				'type'               => Controls_Manager::SWITCHER,
				'default'            => 'no',
				'label_on'           => esc_html__( 'On', 'powerpack' ),
				'label_off'          => esc_html__( 'Off', 'powerpack' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
				'condition'          => array(
					'table_type'         => 'responsive',
					'hide_table_header!' => 'yes',
				),
			)
		);

		$this->add_control(
			'breakpoint',
			array(
				'label'              => esc_html__( 'Breakpoint', 'powerpack' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'ai'                 => [
					'active' => false,
				],
				'condition'          => array(
					'table_type'         => 'responsive',
					'scrollable'         => 'yes',
					'hide_table_header!' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_content_header_controls() {

		$this->start_controls_section(
			'section_headers',
			array(
				'label'     => esc_html__( 'Header', 'powerpack' ),
				'condition' => array(
					'source'             => 'manual',
					'hide_table_header!' => 'yes',
				),
			)
		);

		$repeater_header = new Repeater();

		$repeater_header->start_controls_tabs( 'table_header_tabs' );

		$repeater_header->start_controls_tab( 'table_header_tab_content', array( 'label' => esc_html__( 'Content', 'powerpack' ) ) );

		$repeater_header->add_control(
			'table_header_col',
			array(
				'label'       => esc_html__( 'Text', 'powerpack' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => array(
					'active' => true,
				),
				'placeholder' => esc_html__( 'Table Header', 'powerpack' ),
				'default'     => esc_html__( 'Table Header', 'powerpack' ),
			)
		);

		$repeater_header->add_control(
			'cell_icon_type',
			array(
				'label'       => esc_html__( 'Icon Type', 'powerpack' ),
				'label_block' => false,
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'none'  => array(
						'title' => esc_html__( 'None', 'powerpack' ),
						'icon'  => 'eicon-ban',
					),
					'icon'  => array(
						'title' => esc_html__( 'Icon', 'powerpack' ),
						'icon'  => 'eicon-star',
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'powerpack' ),
						'icon'  => 'eicon-image-bold',
					),
				),
				'default'     => 'none',
			)
		);

		$repeater_header->add_control(
			'select_cell_icon',
			array(
				'label'            => esc_html__( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'fa4compatibility' => 'cell_icon',
				'condition'        => array(
					'cell_icon_type' => 'icon',
				),
			)
		);

		$repeater_header->add_control(
			'cell_icon_image',
			array(
				'label'       => esc_html__( 'Image', 'powerpack' ),
				'label_block' => true,
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition'   => array(
					'cell_icon_type' => 'image',
				),
			)
		);

		$repeater_header->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'cell_icon_image',
				'label'     => esc_html__( 'Image Size', 'powerpack' ),
				'default'   => 'full',
				'exclude'   => array( 'custom' ),
				'condition' => array(
					'cell_icon_type' => 'image',
				),
			)
		);

		$repeater_header->add_control(
			'cell_icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => array(
					'before' => esc_html__( 'Before', 'powerpack' ),
					'after'  => esc_html__( 'After', 'powerpack' ),
				),
				'condition' => array(
					'cell_icon_type!' => 'none',
				),
			)
		);

		$repeater_header->end_controls_tab();

		$repeater_header->start_controls_tab(
			'table_body_tab_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'powerpack' ),
			]
		);

		$repeater_header->end_controls_tab();

		$repeater_header->start_controls_tab( 'table_header_tab_advanced', array( 'label' => esc_html__( 'Advanced', 'powerpack' ) ) );

		$repeater_header->add_control(
			'col_span',
			[
				'label'                 => esc_html__( 'Column Span', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => '',
				'min'                   => 1,
				'max'                   => 30,
				'step'                  => 1,
			]
		);

		$repeater_header->add_control(
			'row_span',
			[
				'label'                 => esc_html__( 'Row Span', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => '',
				'min'                   => 1,
				'max'                   => 30,
				'step'                  => 1,
			]
		);

		$repeater_header->add_control(
			'css_id',
			array(
				'label'   => esc_html__( 'CSS ID', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'ai'      => [
					'active' => false,
				],
			)
		);

		$repeater_header->add_control(
			'css_classes',
			array(
				'label'   => esc_html__( 'CSS Classes', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'ai'      => [
					'active' => false,
				],
			)
		);

		$repeater_header->end_controls_tab();

		$repeater_header->end_controls_tabs();

		$this->add_control(
			'table_headers',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'table_header_col' => esc_html__( 'Header Column #1', 'powerpack' ),
					),
					array(
						'table_header_col' => esc_html__( 'Header Column #2', 'powerpack' ),
					),
					array(
						'table_header_col' => esc_html__( 'Header Column #3', 'powerpack' ),
					),
				),
				'fields'      => $repeater_header->get_controls(),
				'title_field' => '{{{ table_header_col }}}',
				'condition'   => array(
					'hide_table_header!' => 'yes',
					'source'             => 'manual',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_content_body_controls() {

		$this->start_controls_section(
			'section_body',
			array(
				'label'     => esc_html__( 'Body', 'powerpack' ),
				'condition' => array(
					'source' => 'manual',
				),
			)
		);

		$repeater_rows = new Repeater();

		$repeater_rows->add_control(
			'table_body_element',
			array(
				'label'   => esc_html__( 'Element Type', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'cell',
				'options' => array(
					'row'  => esc_html__( 'Row', 'powerpack' ),
					'cell' => esc_html__( 'Cell', 'powerpack' ),
				),
			)
		);

		$repeater_rows->start_controls_tabs( 'table_body_tabs' );

		$repeater_rows->start_controls_tab(
			'table_body_tab_content',
			array(
				'label'     => esc_html__( 'Content', 'powerpack' ),
				'condition' => array(
					'table_body_element' => 'cell',
				),
			)
		);

		$repeater_rows->add_control(
			'cell_text',
			array(
				'label'     => esc_html__( 'Text', 'powerpack' ),
				'type'      => Controls_Manager::TEXTAREA,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'table_body_element' => 'cell',
				),
			)
		);

		$repeater_rows->add_control(
			'cell_icon_type',
			array(
				'label'       => esc_html__( 'Icon Type', 'powerpack' ),
				'label_block' => false,
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'none'  => array(
						'title' => esc_html__( 'None', 'powerpack' ),
						'icon'  => 'eicon-ban',
					),
					'icon'  => array(
						'title' => esc_html__( 'Icon', 'powerpack' ),
						'icon'  => 'eicon-star',
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'powerpack' ),
						'icon'  => 'eicon-image-bold',
					),
				),
				'default'     => 'none',
				'condition'   => array(
					'table_body_element' => 'cell',
				),
			)
		);

		$repeater_rows->add_control(
			'select_cell_icon',
			array(
				'label'            => esc_html__( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'fa4compatibility' => 'cell_icon',
				'condition'        => array(
					'table_body_element' => 'cell',
					'cell_icon_type'     => 'icon',
				),
			)
		);

		$repeater_rows->add_control(
			'cell_icon_image',
			array(
				'label'       => esc_html__( 'Image', 'powerpack' ),
				'label_block' => true,
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition'   => array(
					'table_body_element' => 'cell',
					'cell_icon_type'     => 'image',
				),
			)
		);

		$repeater_rows->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'cell_icon_image',
				'label'     => esc_html__( 'Image Size', 'powerpack' ),
				'default'   => 'full',
				'exclude'   => array( 'custom' ),
				'condition' => array(
					'table_body_element' => 'cell',
					'cell_icon_type'     => 'image',
				),
			)
		);

		$repeater_rows->add_control(
			'cell_icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => array(
					'before' => esc_html__( 'Before', 'powerpack' ),
					'after'  => esc_html__( 'After', 'powerpack' ),
				),
				'condition' => array(
					'table_body_element' => 'cell',
					'cell_icon_type!'    => 'none',
				),
			)
		);

		$repeater_rows->add_control(
			'link',
			array(
				'label'     => esc_html__( 'Link', 'powerpack' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => array(
					'url' => '',
				),
				'condition' => array(
					'table_body_element' => 'cell',
				),
			)
		);

		$repeater_rows->end_controls_tab();

		$repeater_rows->start_controls_tab(
			'table_body_tab_tooltip',
			[
				'label'     => esc_html__( 'Tooltip', 'powerpack' ),
				'condition' => array(
					'table_body_element' => 'cell',
				),
			]
		);

		$repeater_rows->end_controls_tab();

		$repeater_rows->start_controls_tab(
			'table_body_tab_advanced',
			array(
				'label'     => esc_html__( 'Advanced', 'powerpack' ),
				'condition' => array(
					'table_body_element' => 'cell',
				),
			)
		);

		$repeater_rows->add_control(
			'col_span',
			array(
				'label'                 => esc_html__( 'Column Span', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => '',
				'min'                   => 1,
				'max'                   => 30,
				'step'                  => 1,
				'condition'             => array(
					'table_body_element' => 'cell',
				),
			)
		);

		$repeater_rows->add_control(
			'row_span',
			array(
				'label'                 => esc_html__( 'Row Span', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => '',
				'min'                   => 1,
				'max'                   => 30,
				'step'                  => 1,
				'condition'             => array(
					'table_body_element' => 'cell',
				),
			)
		);

		$repeater_rows->add_control(
			'convert_cell_th',
			array(
				'label'       => esc_html__( 'Convert to Table Heading?', 'powerpack' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'th' => esc_html__( 'Yes', 'powerpack' ),
					'td' => esc_html__( 'No', 'powerpack' ),
				),
				'default'     => 'td',
				'label_block' => false,
				'condition'             => array(
					'table_body_element' => 'cell',
				),
			)
		);

		$repeater_rows->add_control(
			'css_id',
			array(
				'label'   => esc_html__( 'CSS ID', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'ai'      => [
					'active' => false,
				],
			)
		);

		$repeater_rows->add_control(
			'css_classes',
			array(
				'label'   => esc_html__( 'CSS Classes', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'ai'      => [
					'active' => false,
				],
			)
		);

		$repeater_rows->end_controls_tab();

		$repeater_rows->end_controls_tabs();

		$this->add_control(
			'table_body_content',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'table_body_element' => 'row',
					),
					array(
						'table_body_element' => 'cell',
						'cell_text'          => esc_html__( 'Column #1', 'powerpack' ),
					),
					array(
						'table_body_element' => 'cell',
						'cell_text'          => esc_html__( 'Column #2', 'powerpack' ),
					),
					array(
						'table_body_element' => 'cell',
						'cell_text'          => esc_html__( 'Column #3', 'powerpack' ),
					),
					array(
						'table_body_element' => 'row',
					),
					array(
						'table_body_element' => 'cell',
						'cell_text'          => esc_html__( 'Column #1', 'powerpack' ),
					),
					array(
						'table_body_element' => 'cell',
						'cell_text'          => esc_html__( 'Column #2', 'powerpack' ),
					),
					array(
						'table_body_element' => 'cell',
						'cell_text'          => esc_html__( 'Column #3', 'powerpack' ),
					),
				),
				'fields'      => $repeater_rows->get_controls(),
				'title_field' => esc_html__( 'Table', 'powerpack' ) . ' {{{ table_body_element }}}: {{{ cell_text }}}',
			)
		);

		$this->end_controls_section();
	}

	protected function register_content_footer_controls() {

		$this->start_controls_section(
			'section_footer',
			array(
				'label'     => esc_html__( 'Footer', 'powerpack' ),
				'condition' => array(
					'source' => 'manual',
				),
			)
		);

		$repeater_footer_rows = new Repeater();

		$repeater_footer_rows->add_control(
			'table_footer_element',
			array(
				'label'   => esc_html__( 'Element Type', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'cell',
				'options' => array(
					'row'  => esc_html__( 'Row', 'powerpack' ),
					'cell' => esc_html__( 'Cell', 'powerpack' ),
				),
			)
		);

		$repeater_footer_rows->start_controls_tabs( 'table_footer_tabs' );

		$repeater_footer_rows->start_controls_tab(
			'table_footer_tab_content',
			array(
				'label'     => esc_html__( 'Content', 'powerpack' ),
				'condition' => array(
					'table_footer_element' => 'cell',
				),
			)
		);

		$repeater_footer_rows->add_control(
			'cell_text',
			array(
				'label'     => esc_html__( 'Text', 'powerpack' ),
				'type'      => Controls_Manager::TEXTAREA,
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => array(
					'table_footer_element' => 'cell',
				),
			)
		);

		$repeater_footer_rows->add_control(
			'link',
			array(
				'label'     => esc_html__( 'Link', 'powerpack' ),
				'type'      => Controls_Manager::URL,
				'dynamic'   => array(
					'active' => true,
				),
				'default'   => array(
					'url' => '',
				),
				'condition' => array(
					'table_footer_element' => 'cell',
				),
			)
		);

		$repeater_footer_rows->add_control(
			'cell_icon_type',
			array(
				'label'       => esc_html__( 'Icon Type', 'powerpack' ),
				'label_block' => false,
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'none'  => array(
						'title' => esc_html__( 'None', 'powerpack' ),
						'icon'  => 'eicon-ban',
					),
					'icon'  => array(
						'title' => esc_html__( 'Icon', 'powerpack' ),
						'icon'  => 'eicon-star',
					),
					'image' => array(
						'title' => esc_html__( 'Image', 'powerpack' ),
						'icon'  => 'eicon-image-bold',
					),
				),
				'default'     => 'none',
				'condition'   => array(
					'table_footer_element' => 'cell',
				),
			)
		);

		$repeater_footer_rows->add_control(
			'select_cell_icon',
			array(
				'label'            => esc_html__( 'Icon', 'powerpack' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'fa4compatibility' => 'cell_icon',
				'condition'        => array(
					'table_footer_element' => 'cell',
					'cell_icon_type'       => 'icon',
				),
			)
		);

		$repeater_footer_rows->add_control(
			'cell_icon_image',
			array(
				'label'       => esc_html__( 'Image', 'powerpack' ),
				'label_block' => true,
				'type'        => Controls_Manager::MEDIA,
				'dynamic'     => array(
					'active' => true,
				),
				'default'     => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition'   => array(
					'table_footer_element' => 'cell',
					'cell_icon_type'       => 'image',
				),
			)
		);

		$repeater_footer_rows->add_group_control(
			Group_Control_Image_Size::get_type(),
			array(
				'name'      => 'cell_icon_image',
				'label'     => esc_html__( 'Image Size', 'powerpack' ),
				'default'   => 'full',
				'exclude'   => array( 'custom' ),
				'condition' => array(
					'table_footer_element' => 'cell',
					'cell_icon_type'       => 'image',
				),
			)
		);

		$repeater_footer_rows->add_control(
			'cell_icon_position',
			array(
				'label'     => esc_html__( 'Icon Position', 'powerpack' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'before',
				'options'   => array(
					'before' => esc_html__( 'Before', 'powerpack' ),
					'after'  => esc_html__( 'After', 'powerpack' ),
				),
				'condition' => array(
					'table_footer_element' => 'cell',
					'cell_icon_type!'      => 'none',
				),
			)
		);

		$repeater_footer_rows->end_controls_tab();

		$repeater_footer_rows->start_controls_tab(
			'table_body_tab_tooltip',
			[
				'label'     => esc_html__( 'Tooltip', 'powerpack' ),
				'condition' => array(
					'table_footer_element' => 'cell',
				),
			]
		);

		$repeater_footer_rows->end_controls_tab();

		$repeater_footer_rows->start_controls_tab(
			'table_footer_tab_advanced',
			array(
				'label'     => esc_html__( 'Advanced', 'powerpack' ),
				'condition' => array(
					'table_footer_element' => 'cell',
				),
			)
		);

		$repeater_footer_rows->add_control(
			'col_span',
			array(
				'label'                 => esc_html__( 'Column Span', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => '',
				'min'                   => 1,
				'max'                   => 30,
				'step'                  => 1,
				'condition'             => array(
					'table_footer_element' => 'cell',
				),
			)
		);

		$repeater_footer_rows->add_control(
			'row_span',
			array(
				'label'                 => esc_html__( 'Row Span', 'powerpack' ),
				'type'                  => Controls_Manager::NUMBER,
				'default'               => '',
				'min'                   => 1,
				'max'                   => 30,
				'step'                  => 1,
				'condition'             => array(
					'table_footer_element' => 'cell',
				),
			)
		);

		$repeater_footer_rows->add_control(
			'css_id',
			array(
				'label'   => esc_html__( 'CSS ID', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'ai'      => [
					'active' => false,
				],
			)
		);

		$repeater_footer_rows->add_control(
			'css_classes',
			array(
				'label'   => esc_html__( 'CSS Classes', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'ai'      => [
					'active' => false,
				],
			)
		);

		$repeater_footer_rows->end_controls_tab();

		$repeater_footer_rows->end_controls_tabs();

		$this->add_control(
			'table_footer_content',
			array(
				'label'       => '',
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'col_span' => '',
					),
				),
				'fields'      => $repeater_footer_rows->get_controls(),
				'title_field' => 'Table {{{ table_footer_element }}}: {{{ cell_text }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Table Tooltip Controls
	 *
	 * @since x.x.x
	 * @return void
	 */
	protected function register_content_tooltip_controls() {
		$this->start_controls_section(
			'section_tooltip',
			[
				'label'     => esc_html__( 'Tooltip', 'powerpack' ),
				'condition' => array(
					'source' => 'manual',
				),
			]
		);

		$this->add_control(
			'show_tooltip',
			[
				'label'        => esc_html__( 'Enable Tooltip', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'condition'    => array(
					'source' => 'manual',
				),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Content Tab: Help Doc Links
	 *
	 * @since x.x.x
	 * @access protected
	 */
	protected function register_content_help_docs_controls() {

		$help_docs = PP_Config::get_widget_help_links( 'Table' );
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
	/* STYLE TAB
	/*-----------------------------------------------------------------------------------*/
	protected function register_style_table_controls() {

		$this->start_controls_section(
			'section_table_style',
			array(
				'label' => esc_html__( 'Table', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'table_width',
			array(
				'label'      => esc_html__( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'default'    => array(
					'size' => 100,
					'unit' => '%',
				),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1200,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table-container' => 'max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'table_align',
			array(
				'label'        => esc_html__( 'Alignment', 'powerpack' ),
				'type'         => Controls_Manager::CHOOSE,
				'label_block'  => false,
				'default'      => 'center',
				'options'      => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'powerpack' ),
						'icon'  => 'eicon-h-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'powerpack' ),
						'icon'  => 'eicon-h-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'powerpack' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'prefix_class' => 'pp-table-',
			)
		);

		$this->add_responsive_control(
			'table_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_header_controls() {

		$this->start_controls_section(
			'section_table_header_style',
			array(
				'label' => esc_html__( 'Header', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_header_typography',
				'label'    => esc_html__( 'Typography', 'powerpack' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .pp-table tr th.pp-table-cell',
			)
		);

		$this->start_controls_tabs( 'tabs_header_style' );

		$this->start_controls_tab(
			'tab_header_normal',
			array(
				'label' => esc_html__( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'table_header_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table th.pp-table-cell .pp-table-cell-content' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-table th.pp-table-cell .pp-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_header_sortable_icon_color',
			array(
				'label'     => esc_html__( 'Sortable Icon Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table th.pp-table-cell .tablesaw-sortable-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_header_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e6e6e6',
				'selectors' => array(
					'{{WRAPPER}} .pp-table th.pp-table-cell' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'section_table_header_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-table th.pp-table-cell',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_header_hover',
			array(
				'label' => esc_html__( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'table_header_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table th.pp-table-cell:hover .pp-table-cell-content' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-table th.pp-table-cell:hover .pp-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_header_sortable_icon_color_hover',
			array(
				'label'     => esc_html__( 'Sortable Icon Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table th.pp-table-cell:hover .tablesaw-sortable-arrow' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_header_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table th.pp-table-cell:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->start_controls_tabs( 'tabs_header_default_style' );

		$this->start_controls_tab(
			'tab_header_default',
			array(
				'label' => esc_html__( 'Default', 'powerpack' ),
			)
		);

		$this->add_responsive_control(
			'table_header_align',
			array(
				'label'                => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
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
				'default'              => '',
				'selectors'            => array(
					'{{WRAPPER}} .pp-table thead th' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_header_text_vertical_align',
			array(
				'label'       => esc_html__( 'Text Vertical Align', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-table th' => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_header_vertical_align',
			array(
				'label'                => esc_html__( 'Icon Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'middle',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-table thead .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_header_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table th.pp-table-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'table_header_box_shadow',
				'selector' => '{{WRAPPER}} .pp-table thead',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_header_col_first',
			array(
				'label' => esc_html__( 'First', 'powerpack' ),
			)
		);

		$this->add_responsive_control(
			'table_header_col_first_align',
			array(
				'label'                => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
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
				'default'              => '',
				'selectors'            => array(
					'{{WRAPPER}} .pp-table thead .pp-table-cell:first-child' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_header_col_first_text_vertical_align',
			array(
				'label'       => esc_html__( 'Text Vertical Align', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-table thead .pp-table-cell:first-child'   => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_header_col_first_vertical_align',
			array(
				'label'                => esc_html__( 'Icon Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'middle',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-table thead .pp-table-cell:first-child .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_header_col_first_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table thead .pp-table-cell:first-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'table_header_col_first_box_shadow',
				'selector' => '{{WRAPPER}} .pp-table thead .pp-table-cell:first-child',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_header_col_last',
			array(
				'label' => esc_html__( 'Last', 'powerpack' ),
			)
		);

		$this->add_responsive_control(
			'table_header_col_last_align',
			array(
				'label'                => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
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
				'default'              => '',
				'selectors'            => array(
					'{{WRAPPER}} .pp-table thead th.pp-table-cell:last-child' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_header_col_last_text_vertical_align',
			array(
				'label'       => esc_html__( 'Text Vertical Align', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-table thead .pp-table-cell:last-child'   => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_header_col_last_vertical_align',
			array(
				'label'                => esc_html__( 'Icon Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'middle',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-table thead .pp-table-cell:last-child .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_header_col_last_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table thead .pp-table-cell:last-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'table_header_col_last_box_shadow',
				'selector' => '{{WRAPPER}} .pp-table thead .pp-table-cell:last-child',
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_rows_controls() {
		$this->start_controls_section(
			'section_table_rows_style',
			array(
				'label' => esc_html__( 'Rows', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'table_striped_rows',
			array(
				'label'        => esc_html__( 'Striped Rows', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'On', 'powerpack' ),
				'label_off'    => esc_html__( 'Off', 'powerpack' ),
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'table_rows_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tbody tr td' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'table_striped_rows!' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_rows_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tbody tr .pp-table-cell-content' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'table_striped_rows!' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'section_table_rowsborder',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-table tr',
				'condition'   => array(
					'table_striped_rows!' => 'yes',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_rows_alternate_style' );

		$this->start_controls_tab(
			'tab_row_even',
			array(
				'label'     => esc_html__( 'Even', 'powerpack' ),
				'condition' => array(
					'table_striped_rows' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_even_rows_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tr:nth-child(even)' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'table_striped_rows' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_even_rows_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tr:nth-child(even) .pp-table-cell-content' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'table_striped_rows' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'section_table_even_rows_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'separator'   => 'before',
				'selector'    => '{{WRAPPER}} .pp-table tr:nth-child(even)',
				'condition'   => array(
					'table_striped_rows' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_row_odd',
			array(
				'label'     => esc_html__( 'Odd', 'powerpack' ),
				'condition' => array(
					'table_striped_rows' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_odd_rows_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tbody tr:nth-child(odd) td' => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'table_striped_rows' => 'yes',
				),
			)
		);

		$this->add_control(
			'table_odd_rows_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tbody tr:nth-child(odd) .pp-table-cell-content' => 'color: {{VALUE}}',
				),
				'condition' => array(
					'table_striped_rows' => 'yes',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'section_table_odd_rows_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'separator'   => 'before',
				'selector'    => '{{WRAPPER}} .pp-table tr:nth-child(odd)',
				'condition'   => array(
					'table_striped_rows' => 'yes',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_cells_controls() {

		$this->start_controls_section(
			'section_table_cells_style',
			array(
				'label' => esc_html__( 'Cells', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_cells_typography',
				'label'    => esc_html__( 'Typography', 'powerpack' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
				],
				'selector' => '{{WRAPPER}} .pp-table tr .pp-table-cell',
			)
		);

		$this->start_controls_tabs( 'tabs_cell_style' );

		$this->start_controls_tab(
			'tab_cell_normal',
			array(
				'label' => esc_html__( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'table_cell_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'global'    => [
					'default' => Global_Colors::COLOR_TEXT,
				],
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table .pp-table-cell .pp-table-cell-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_cell_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table .pp-table-cell' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'section_cell_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-table .pp-table-cell',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_cell_hover',
			array(
				'label' => esc_html__( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'table_cell_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table .pp-table-cell:hover .pp-table-cell-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_cell_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table .pp-table-cell:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->start_controls_tabs( 'tabs_cell_default_style' );

		$this->start_controls_tab(
			'tab_cell_default',
			array(
				'label' => esc_html__( 'Default', 'powerpack' ),
			)
		);

		$this->add_responsive_control(
			'table_cells_horizontal_align',
			array(
				'label'                => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
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
				'default'              => '',
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tbody td' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_cells_text_vertical_align',
			array(
				'label'       => esc_html__( 'Text Vertical Align', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-table tbody td' => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_cells_vertical_align',
			array(
				'label'                => esc_html__( 'Icon Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'middle',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tbody .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_cell_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table tbody td.pp-table-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_cell_first',
			array(
				'label' => esc_html__( 'First', 'powerpack' ),
			)
		);

		$this->add_responsive_control(
			'table_cell_first_horizontal_align',
			array(
				'label'                => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
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
				'default'              => '',
				'selectors_dictionary' => array(
					'left'   => 'flex-start',
					'center' => 'center',
					'right'  => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tbody td.pp-table-cell:first-child' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_cell_first_text_vertical_align',
			array(
				'label'       => esc_html__( 'Text Vertical Align', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-table tbody .pp-table-cell:first-child td'   => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_cell_first_vertical_align',
			array(
				'label'                => esc_html__( 'Icon Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'middle',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tbody .pp-table-cell:first-child .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_cell_first_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table tbody .pp-table-cell:first-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_cell_last',
			array(
				'label' => esc_html__( 'Last', 'powerpack' ),
			)
		);

		$this->add_responsive_control(
			'table_cell_last_horizontal_align',
			array(
				'label'                => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
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
				'default'              => '',
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tbody td.pp-table-cell:last-child' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_cell_last_text_vertical_align',
			array(
				'label'       => esc_html__( 'Text Vertical Align', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-table tbody .pp-table-cell:last-child td'   => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_cell_last_vertical_align',
			array(
				'label'                => esc_html__( 'Icon Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'middle',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tbody .pp-table-cell:last-child .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_cell_last_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table tbody .pp-table-cell:last-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_footer_controls() {

		$this->start_controls_section(
			'section_table_footer_style',
			array(
				'label' => esc_html__( 'Footer', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'table_footer_typography',
				'label'    => esc_html__( 'Typography', 'powerpack' ),
				'global'   => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
				],
				'selector' => '{{WRAPPER}} .pp-table tfoot td.pp-table-cell',
			)
		);

		$this->start_controls_tabs( 'tabs_footer_style' );

		$this->start_controls_tab(
			'tab_footer_normal',
			array(
				'label' => esc_html__( 'Normal', 'powerpack' ),
			)
		);

		$this->add_control(
			'table_footer_text_color',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tfoot td.pp-table-cell .pp-table-cell-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_footer_bg_color',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tfoot td.pp-table-cell' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'section_table_footer_border',
				'label'       => esc_html__( 'Border', 'powerpack' ),
				'placeholder' => '1px',
				'default'     => '1px',
				'selector'    => '{{WRAPPER}} .pp-table tfoot td.pp-table-cell',
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_footer_hover',
			array(
				'label' => esc_html__( 'Hover', 'powerpack' ),
			)
		);

		$this->add_control(
			'table_footer_text_color_hover',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tfoot td.pp-table-cell:hover .pp-table-cell-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_footer_bg_color_hover',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tfoot td.pp-table-cell:hover' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->start_controls_tabs( 'tabs_footer_default_cell_style' );

		$this->start_controls_tab(
			'tab_footer_default_cell',
			array(
				'label' => esc_html__( 'Default', 'powerpack' ),
			)
		);

		$this->add_responsive_control(
			'table_footer_align',
			array(
				'label'                => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'label_block'          => false,
				'options'              => array(
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
				'default'              => '',
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tfoot td' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_footer_text_vertical_align',
			array(
				'label'       => esc_html__( 'Text Vertical Align', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-table tfoot td' => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_footer_vertical_align',
			array(
				'label'                => esc_html__( 'Icon Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'middle',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tfoot .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_footer_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table tfoot td.pp-table-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_footer_first_cell',
			array(
				'label' => esc_html__( 'First', 'powerpack' ),
			)
		);

		$this->add_responsive_control(
			'table_footer_first_cell_align',
			array(
				'label'                => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'label_block'          => false,
				'options'              => array(
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
				'default'              => '',
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tfoot td.pp-table-cell:first-child' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_footer_first_cell_text_vertical_align',
			array(
				'label'       => esc_html__( 'Text Vertical Align', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-table tfoot .pp-table-cell:first-child'   => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_footer_first_cell_vertical_align',
			array(
				'label'                => esc_html__( 'Icon Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'middle',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tfoot .pp-table-cell:first-child .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_footer_first_cell_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table tfoot .pp-table-cell:first-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_footer_last_cell',
			array(
				'label' => esc_html__( 'Last', 'powerpack' ),
			)
		);

		$this->add_responsive_control(
			'table_footer_last_cell_align',
			array(
				'label'                => esc_html__( 'Horizontal Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'label_block'          => false,
				'options'              => array(
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
				'default'              => '',
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tfoot td.pp-table-cell:last-child' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_footer_last_cell_text_vertical_align',
			array(
				'label'       => esc_html__( 'Text Vertical Align', 'powerpack' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'selectors'   => array(
					'{{WRAPPER}} .pp-table tfoot .pp-table-cell:last-child'   => 'vertical-align: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_footer_last_cell_vertical_align',
			array(
				'label'                => esc_html__( 'Icon Vertical Align', 'powerpack' ),
				'type'                 => Controls_Manager::CHOOSE,
				'label_block'          => false,
				'options'              => array(
					'top'    => array(
						'title' => esc_html__( 'Top', 'powerpack' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => esc_html__( 'Middle', 'powerpack' ),
						'icon'  => 'eicon-v-align-middle',
					),
					'bottom' => array(
						'title' => esc_html__( 'Bottom', 'powerpack' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
				'default'              => 'middle',
				'selectors_dictionary' => array(
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				),
				'selectors'            => array(
					'{{WRAPPER}} .pp-table tfoot .pp-table-cell:last-child .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'table_footer_last_cell_padding',
			array(
				'label'      => esc_html__( 'Padding', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table tfoot .pp-table-cell:last-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_style_icon_controls() {

		$this->start_controls_section(
			'section_table_icon_style',
			array(
				'label' => esc_html__( 'Icon', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'icon_spacing',
			array(
				'label'      => esc_html__( 'Icon Spacing', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table-cell-icon-before' => 'margin-right: {{SIZE}}px;',
					'{{WRAPPER}} .pp-table-cell-icon-after' => 'margin-left: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'table_icon_heading',
			array(
				'label'     => esc_html__( 'Icon', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table-cell .pp-table-cell-icon' => 'color: {{VALUE}}',
					'{{WRAPPER}} .pp-table-cell .pp-table-cell-icon svg' => 'fill: {{VALUE}}',
				),
			)
		);

		$this->add_responsive_control(
			'table_icon_size',
			array(
				'label'      => esc_html__( 'Icon Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table-cell-icon' => 'font-size: {{SIZE}}px; line-height: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'table_img_heading',
			array(
				'label'     => esc_html__( 'Image', 'powerpack' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'table_img_size',
			array(
				'label'      => esc_html__( 'Image Size', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'default'    => array(
					'size' => 100,
					'unit' => 'px',
				),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 600,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table-cell-icon img' => 'width: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'table_img_border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'powerpack' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .pp-table-cell-icon img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function register_style_columns_controls() {

		$this->start_controls_section(
			'section_table_columns_style',
			array(
				'label' => esc_html__( 'Columns', 'powerpack' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$repeater_columns = new Repeater();

		$repeater_columns->add_control(
			'table_col_span',
			array(
				'label'   => esc_html__( 'Span', 'powerpack' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1,
				'min'     => 0,
				'max'     => 999,
				'step'    => 1,
			)
		);

		$repeater_columns->add_control(
			'table_col_bg_color',
			array(
				'label'   => esc_html__( 'Background Color', 'powerpack' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
			)
		);

		$repeater_columns->add_control(
			'table_col_width',
			array(
				'label'      => esc_html__( 'Width', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'range'      => array(
					'%'  => array(
						'min' => 1,
						'max' => 100,
					),
					'px' => array(
						'min' => 1,
						'max' => 1200,
					),
				),
			)
		);

		$this->add_control(
			'table_column_styles',
			array(
				'label'       => esc_html__( 'Column Styles', 'powerpack' ),
				'type'        => Controls_Manager::REPEATER,
				'default'     => array(
					array(
						'table_col_span' => '1',
					),
				),
				'fields'      => $repeater_columns->get_controls(),
				'title_field' => 'Column Span {{{ table_col_span }}}',
			)
		);

		$this->start_controls_tabs( 'tabs_columns_style' );

		$this->start_controls_tab(
			'tab_columns_even',
			array(
				'label' => esc_html__( 'Even', 'powerpack' ),
			)
		);

		$this->add_control(
			'table_columns_body',
			array(
				'label' => esc_html__( 'Body', 'powerpack' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'table_columns_text_color_body_even',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tbody tr td:nth-child(even) .pp-table-cell-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_columns_bg_color_body_even',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tbody tr td:nth-child(even)' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_columns_header_even',
			array(
				'label' => esc_html__( 'Header', 'powerpack' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'table_columns_text_color_header_even',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table thead tr th:nth-child(even) .pp-table-cell-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_columns_bg_color_header_even',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table thead tr th:nth-child(even)' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_columns_footer_even',
			array(
				'label' => esc_html__( 'Footer', 'powerpack' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'table_columns_text_color_footer_even',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tfoot tr td:nth-child(even) .pp-table-cell-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_columns_bg_color_footer_even',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tfoot tr td:nth-child(even)' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_columns_odd',
			array(
				'label' => esc_html__( 'Odd', 'powerpack' ),
			)
		);

		$this->add_control(
			'table_columns_body_odd',
			array(
				'label' => esc_html__( 'Body', 'powerpack' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'table_columns_text_color_body_odd',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tbody tr td:nth-child(odd) .pp-table-cell-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_columns_bg_color_body_odd',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tbody tr td:nth-child(odd)' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_columns_header_odd',
			array(
				'label' => esc_html__( 'Header', 'powerpack' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'table_columns_text_color_header_odd',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table thead tr th:nth-child(odd) .pp-table-cell-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_columns_bg_color_header_odd',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table thead tr th:nth-child(odd)' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_columns_footer_odd',
			array(
				'label' => esc_html__( 'Footer', 'powerpack' ),
				'type'  => Controls_Manager::HEADING,
			)
		);

		$this->add_control(
			'table_columns_text_color_footer_odd',
			array(
				'label'     => esc_html__( 'Text Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tfoot tr td:nth-child(odd) .pp-table-cell-content' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'table_columns_bg_color_footer_odd',
			array(
				'label'     => esc_html__( 'Background Color', 'powerpack' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => array(
					'{{WRAPPER}} .pp-table tfoot tr td:nth-child(odd)' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

	}

	protected function render_column_styles() {
		$settings = $this->get_settings_for_display();

		if ( $settings['table_column_styles'] ) { ?>
			<colgroup>
				<?php foreach ( $settings['table_column_styles'] as $col_style ) { ?>
				<col
					span="<?php echo esc_attr( $col_style['table_col_span'] ); ?>"
					class="elementor-repeater-item-<?php echo esc_attr( $col_style['_id'] ); ?>"
					<?php if ( $col_style['table_col_bg_color'] || $col_style['table_col_width'] ) { ?>
						style="
							<?php if ( $col_style['table_col_bg_color'] ) { ?>
						background-color:<?php echo esc_attr( $col_style['table_col_bg_color'] ); ?>;
						<?php } ?>
							<?php if ( $col_style['table_col_width']['size'] ) { ?>
						width:<?php echo esc_attr( $col_style['table_col_width']['size'] ) . esc_attr( $col_style['table_col_width']['unit'] ); ?>
						<?php } ?>
						"
					<?php } ?>
					>
				<?php } ?>
			</colgroup>
			<?php
		}
	}

	protected function clean( $string ) {
		$string = str_replace( ' ', '-', $string ); // Replaces all spaces with hyphens.
		$string = preg_replace( '/[^A-Za-z0-9\-]/', '', $string ); // Removes special chars.

		return preg_replace( '/-+/', '-', $string ); // Replaces multiple hyphens with single one.
	}

	protected function render_header() {
		$settings = $this->get_settings_for_display();
		$i        = 1;
		$this->add_render_attribute( 'row', 'class', 'pp-table-row' );
		if ( 'yes' !== $settings['hide_table_header'] ) {
			?>
			<thead>
				<tr <?php $this->print_render_attribute_string( 'row' ); ?>>
					<?php
					$pp_output = '';
					foreach ( $settings['table_headers'] as $index => $item ) :

						$header_cell_key = $this->get_repeater_setting_key( 'table_header_col', 'table_headers', $index );
						$this->add_render_attribute( $header_cell_key, 'class', 'pp-table-cell-text' );
						$this->add_inline_editing_attributes( $header_cell_key, 'basic' );

						$th_key = 'header-' . $i;
						$th_icon_key = 'header-icon-' . $i;

						$this->add_render_attribute( $th_key, 'class', 'pp-table-cell' );
						$this->add_render_attribute( $th_key, 'class', 'pp-table-cell-' . $item['_id'] );

						if ( $item['css_id'] ) {
							$this->add_render_attribute( $th_key, 'id', $item['css_id'] );
						}

						if ( $item['css_classes'] ) {
							$this->add_render_attribute( $th_key, 'class', $item['css_classes'] );
						}

						if ( 'none' !== $item['cell_icon_type'] ) {
							$this->add_render_attribute( $th_icon_key, 'class', 'pp-table-cell-icon' );
							$this->add_render_attribute( $th_icon_key, 'class', 'pp-table-cell-icon-' . $item['cell_icon_position'] );

							$migration_allowed = Icons_Manager::is_migration_allowed();

							// add old default
							if ( ! isset( $item['cell_icon'] ) && ! $migration_allowed ) {
								$item['cell_icon'] = '';
							}

							$migrated = isset( $item['__fa4_migrated']['select_cell_icon'] );
							$is_new = ! isset( $item['cell_icon'] ) && $migration_allowed;

							if ( ! empty( $item['cell_icon'] ) || ( ! empty( $item['select_cell_icon']['value'] ) && $is_new ) ) {
								$this->add_render_attribute( $th_icon_key, 'class', 'pp-icon' );
							}
						}

						$header_content_key = $this->get_repeater_setting_key( 'header_content_key', 'table_headers', $index );
						$this->add_render_attribute( $header_content_key, 'class', 'pp-table-cell-content' );

						if ( $item['col_span'] > 1 ) {
							$this->add_render_attribute( $th_key, 'colspan', $item['col_span'] );
						}

						if ( $item['row_span'] > 1 ) {
							$this->add_render_attribute( $th_key, 'rowspan', $item['row_span'] );
						}

						if ( 'responsive' === $settings['table_type'] && 'yes' === $settings['sortable'] ) {
							$pp_sortable_header = ' data-tablesaw-sortable-col';
						} else {
							$pp_sortable_header = '';
						}

						if ( 'image' === $item['cell_icon_type'] && $item['cell_icon_image']['url'] ) {
							$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['cell_icon_image']['id'], 'cell_icon_image', $item );

							if ( ! $image_url ) {
								$image_url = $item['cell_icon_image']['url'];
							}

							$this->add_render_attribute( 'header_col_img-' . $i, 'src', $image_url );
							$this->add_render_attribute( 'header_col_img-' . $i, 'title', get_the_title( $item['cell_icon_image']['id'] ) );
							$this->add_render_attribute( 'header_col_img-' . $i, 'alt', Control_Media::get_image_alt( $item['cell_icon_image'] ) );
						}

						echo '<th ' . wp_kses_post( $this->get_render_attribute_string( $th_key ) . $pp_sortable_header ) . '>';
						echo '<span ' . wp_kses_post( $this->get_render_attribute_string( $header_content_key ) ) . '>';
						if ( 'none' !== $item['cell_icon_type'] ) {
							echo '<span ' . wp_kses_post( $this->get_render_attribute_string( $th_icon_key ) ) . '>';
							if ( 'icon' === $item['cell_icon_type'] ) {
								if ( ! empty( $item['cell_icon'] ) || ( ! empty( $item['select_cell_icon']['value'] ) && $is_new ) ) {
									if ( $is_new || $migrated ) {
										Icons_Manager::render_icon( $item['select_cell_icon'], [ 'aria-hidden' => 'true' ] );
									} else { ?>
											<i class="<?php echo esc_attr( $item['cell_icon'] ); ?>" aria-hidden="true"></i>
										<?php }
								}
							} elseif ( 'image' === $item['cell_icon_type'] && $item['cell_icon_image']['url'] ) {
								echo '<img ' . wp_kses_post( $this->get_render_attribute_string( 'header_col_img-' . $i ) ) . '>';
							}
							echo '</span>';
						}
						echo '<span ' . wp_kses_post( $this->get_render_attribute_string( $header_cell_key ) ) . '>';
						echo wp_kses_post( $item['table_header_col'] );
						echo '</span>';
						echo '</span>';
						echo '</th>';
						$i++;
						endforeach;
					?>
				</tr>
			</thead>
			<?php
		}
	}

	protected function render_footer() {
		$settings = $this->get_settings_for_display();
		$i        = 1;
		?>
		<tfoot>
			<?php
			ob_start();
			if ( ! empty( $settings['table_footer_content'] ) ) {
				foreach ( $settings['table_footer_content'] as $index => $item ) {
					if ( 'cell' === $item['table_footer_element'] ) {

						$footer_cell_key = $this->get_repeater_setting_key( 'cell_text', 'table_footer_content', $index );
						$this->add_render_attribute( $footer_cell_key, 'class', 'pp-table-cell-text' );
						$this->add_inline_editing_attributes( $footer_cell_key, 'basic' );

						$this->add_render_attribute( 'footer-' . $i, 'class', 'pp-table-cell' );
						$this->add_render_attribute( 'footer-' . $i, 'class', 'pp-table-cell-' . $item['_id'] );

						if ( $item['css_id'] ) {
							$this->add_render_attribute( 'footer-' . $i, 'id', $item['css_id'] );
						}

						if ( $item['css_classes'] ) {
							$this->add_render_attribute( 'footer-' . $i, 'class', $item['css_classes'] );
						}

						if ( 'none' !== $item['cell_icon_type'] ) {
							$this->add_render_attribute( 'footer-icon-' . $i, 'class', 'pp-table-cell-icon' );
							$this->add_render_attribute( 'footer-icon-' . $i, 'class', 'pp-table-cell-icon-' . $item['cell_icon_position'] );

							$migration_allowed = Icons_Manager::is_migration_allowed();

							// add old default
							if ( ! isset( $item['cell_icon'] ) && ! $migration_allowed ) {
								$item['cell_icon'] = '';
							}

							$migrated = isset( $item['__fa4_migrated']['select_cell_icon'] );
							$is_new   = ! isset( $item['cell_icon'] ) && $migration_allowed;

							if ( ! empty( $item['cell_icon'] ) || ( ! empty( $item['select_cell_icon']['value'] ) && $is_new ) ) {
								$this->add_render_attribute( 'footer-icon-' . $i, 'class', 'pp-icon' );
							}
						}

						$footer_content_key = $this->get_repeater_setting_key( 'footer_content_key', 'table_footer_content', $index );
						$this->add_render_attribute( $footer_content_key, 'class', 'pp-table-cell-content' );

						if ( $item['col_span'] > 1 ) {
							$this->add_render_attribute( 'footer-' . $i, 'colspan', $item['col_span'] );
						}

						if ( $item['row_span'] > 1 ) {
							$this->add_render_attribute( 'footer-' . $i, 'rowspan', $item['row_span'] );
						}

						if ( 'image' === $item['cell_icon_type'] && $item['cell_icon_image']['url'] ) {
							$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['cell_icon_image']['id'], 'cell_icon_image', $item );

							if ( ! $image_url ) {
								$image_url = $item['cell_icon_image']['url'];
							}

							$this->add_render_attribute( 'footer_col_img-' . $i, 'src', $image_url );
							$this->add_render_attribute( 'footer_col_img-' . $i, 'title', get_the_title( $item['cell_icon_image']['id'] ) );
							$this->add_render_attribute( 'footer_col_img-' . $i, 'alt', Control_Media::get_image_alt( $item['cell_icon_image'] ) );
						}

						if ( ! empty( $item['link']['url'] ) ) {
							$this->add_link_attributes( 'col-link-' . $i, $item['link'] );
						}

						if ( $item['cell_text'] || 'none' !== $item['cell_icon_type'] ) {
							echo '<td ' . wp_kses_post( $this->get_render_attribute_string( 'footer-' . $i ) ) . '>';
							if ( ! empty( $item['link']['url'] ) ) { ?>
								<a <?php $this->print_render_attribute_string( 'col-link-' . $i ); ?>>
								<?php
							}
							echo '<span ' . wp_kses_post( $this->get_render_attribute_string( $footer_content_key ) ) . '>';
							if ( 'none' !== $item['cell_icon_type'] ) {
								echo '<span ' . wp_kses_post( $this->get_render_attribute_string( 'footer-icon-' . $i ) ) . '>';
								if ( 'icon' === $item['cell_icon_type'] ) {
									if ( ! empty( $item['cell_icon'] ) || ( ! empty( $item['select_cell_icon']['value'] ) && $is_new ) ) {
										if ( $is_new || $migrated ) {
											Icons_Manager::render_icon( $item['select_cell_icon'], [ 'aria-hidden' => 'true' ] );
										} else { ?>
											<i class="<?php echo esc_attr( $item['cell_icon'] ); ?>" aria-hidden="true"></i>
											<?php
										}
									}
								} elseif ( 'image' === $item['cell_icon_type'] && $item['cell_icon_image']['url'] ) {
									echo '<img ' . wp_kses_post( $this->get_render_attribute_string( 'footer_col_img-' . $i ) ) . '>';
								}
								echo '</span>';
							}
							echo '<span ' . wp_kses_post( $this->get_render_attribute_string( $footer_cell_key ) ) . '>';
							echo wp_kses_post( $item['cell_text'] );
							echo '</span>';
							echo '</span>';
							if ( ! empty( $item['link']['url'] ) ) {
								?>
								</a>
								<?php
							}
							echo '</td>';
						}
					} else {
						if ( 1 === $i && 'row' === $item['table_footer_element'] ) {
							echo '<tr>';
						} elseif ( $i > 1 ) {
							echo '</tr><tr>';
						}
					}
					$i++;
				}
			}
			$html = ob_get_contents();
			ob_end_clean();
			echo wp_kses_post( $html );
			echo '</tr>';
			?>
		</tfoot>
		<?php
	}

	protected function render_body() {
		$settings = $this->get_settings_for_display();
		$i        = 1;
		?>
		<tbody>
			<?php
			foreach ( $settings['table_body_content'] as $index => $item ) {
				if ( 'cell' === $item['table_body_element'] ) {

					$body_cell_key = $this->get_repeater_setting_key( 'cell_text', 'table_body_content', $index );
					$this->add_render_attribute( $body_cell_key, 'class', 'pp-table-cell-text' );
					$this->add_inline_editing_attributes( $body_cell_key, 'basic' );

					$body_icon_key = 'body-icon-' . $i;

					$this->add_render_attribute( 'row-' . $i, 'class', 'pp-table-row' );
					$this->add_render_attribute( 'row-' . $i, 'class', 'pp-table-row-' . $item['_id'] );
					$this->add_render_attribute( 'body-' . $i, 'class', 'pp-table-cell' );
					$this->add_render_attribute( 'body-' . $i, 'class', 'pp-table-cell-' . $item['_id'] );

					if ( $item['css_id'] ) {
						$this->add_render_attribute( 'body-' . $i, 'id', $item['css_id'] );
					}

					if ( $item['css_classes'] ) {
						$this->add_render_attribute( 'body-' . $i, 'class', $item['css_classes'] );
					}

					if ( 'none' !== $item['cell_icon_type'] ) {
						$this->add_render_attribute( $body_icon_key, 'class', 'pp-table-cell-icon' );
						$this->add_render_attribute( $body_icon_key, 'class', 'pp-table-cell-icon-' . $item['cell_icon_position'] );

						$migration_allowed = Icons_Manager::is_migration_allowed();

						// add old default
						if ( ! isset( $item['cell_icon'] ) && ! $migration_allowed ) {
							$item['cell_icon'] = '';
						}

						$migrated = isset( $item['__fa4_migrated']['select_cell_icon'] );
						$is_new   = ! isset( $item['cell_icon'] ) && $migration_allowed;

						if ( ! empty( $item['cell_icon'] ) || ( ! empty( $item['select_cell_icon']['value'] ) && $is_new ) ) {
							$this->add_render_attribute( $body_icon_key, 'class', 'pp-icon' );
						}
					}

					$body_content_key = $this->get_repeater_setting_key( 'body_content_key', 'table_body_content', $index );
					$this->add_render_attribute( $body_content_key, 'class', 'pp-table-cell-content' );

					if ( $item['col_span'] > 1 ) {
						$this->add_render_attribute( 'body-' . $i, 'colspan', $item['col_span'] );
					}

					if ( $item['row_span'] > 1 ) {
						$this->add_render_attribute( 'body-' . $i, 'rowspan', $item['row_span'] );
					}

					if ( 'image' === $item['cell_icon_type'] && $item['cell_icon_image']['url'] ) {
						$image_url = Group_Control_Image_Size::get_attachment_image_src( $item['cell_icon_image']['id'], 'cell_icon_image', $item );

						if ( ! $image_url ) {
							$image_url = $item['cell_icon_image']['url'];
						}

						$this->add_render_attribute( 'col_img-' . $i, 'src', $image_url );
						$this->add_render_attribute( 'col_img-' . $i, 'title', get_the_title( $item['cell_icon_image']['id'] ) );
						$this->add_render_attribute( 'col_img-' . $i, 'alt', Control_Media::get_image_alt( $item['cell_icon_image'] ) );
					}

					if ( ! empty( $item['link']['url'] ) ) {
						$this->add_link_attributes( 'col-link-' . $i, $item['link'] );
					}

					if ( $item['cell_text'] || 'none' !== $item['cell_icon_type'] ) {
						echo '<' . esc_attr( $item['convert_cell_th'] ) . ' ' . wp_kses_post( $this->get_render_attribute_string( 'body-' . $i ) ) . '>';
						if ( ! empty( $item['link']['url'] ) ) { ?>
							<a <?php $this->print_render_attribute_string( 'col-link-' . $i ); ?>>
							<?php
						}
						echo '<span ' . wp_kses_post( $this->get_render_attribute_string( $body_content_key ) ) . '>';
						if ( 'none' !== $item['cell_icon_type'] ) {
							echo '<span ' . wp_kses_post( $this->get_render_attribute_string( $body_icon_key ) ) . '>';
							if ( 'icon' === $item['cell_icon_type'] ) {
								if ( ! empty( $item['cell_icon'] ) || ( ! empty( $item['select_cell_icon']['value'] ) && $is_new ) ) {
									if ( $is_new || $migrated ) {
										Icons_Manager::render_icon( $item['select_cell_icon'], array( 'aria-hidden' => 'true' ) );
									} else {
										?>
											<i class="<?php echo esc_attr( $item['cell_icon'] ); ?>" aria-hidden="true"></i>
										<?php
									}
								}
							} elseif ( 'image' === $item['cell_icon_type'] && $item['cell_icon_image']['url'] ) {
								echo '<img ' . wp_kses_post( $this->get_render_attribute_string( 'col_img-' . $i ) ) . '>';
							}
							echo '</span>';
						}
						echo '<span ' . wp_kses_post( $this->get_render_attribute_string( $body_cell_key ) ) . '>';
						echo wp_kses_post( $item['cell_text'] );
						echo '</span>';
						echo '</span>';
						if ( ! empty( $item['link']['url'] ) ) {
							?>
							</a>
							<?php
						}
						echo '</' . esc_attr( $item['convert_cell_th'] ) . '>';
					}
				} else {
					if ( 1 === $i && 'row' === $item['table_body_element'] ) {
						echo '<tr ' . wp_kses_post( $this->get_render_attribute_string( 'row-' . $i ) ) . '>';
					} elseif ( $i > 1 ) {
						echo '</tr><tr ' . wp_kses_post( $this->get_render_attribute_string( 'row-' . $i ) ) . '>';
					}
				}
				$i++;
			}
			echo '</tr>';
			?>
		</tbody>
		<?php
	}

	/**
	 * Function to get table HTML from csv file.
	 *
	 * Parse CSV to Table
	 *
	 * @access protected
	 */
	protected function parse_csv() {

		$settings = $this->get_settings_for_display();

		$file         = isset( $settings['file'] ) ? $settings['file'] : '';
		$csv_file_url = $this->get_media_url( $file );	

		if ( 'file' !== $settings['source'] ) {
			return array(
				'html' => '',
				'rows' => '',
			);
		}
		$response = wp_remote_get(
			$csv_file_url,
			array(
				'sslverify' => false,
			)
		);

		if ( '' === $csv_file_url || is_wp_error( $response ) || 200 !== $response['response']['code'] || '.csv' !== substr( $csv_file_url, -4 ) ) {
			return array(
				'html' => esc_html__( '<p>Please provide a valid CSV file.</p>', 'powerpack' ),
				'rows' => '',
			);
		}

		$rows       = array();
		$rows_count = array();
		$upload_dir = wp_upload_dir();
		$file_url   = str_replace( $upload_dir['baseurl'], '', $csv_file_url );

		$file = $upload_dir['basedir'] . $file_url;

		// Attempt to change permissions if not readable.
		if ( ! is_readable( $file ) ) {
			chmod( $file, 0744 );
		}

		// Check if file is writable, then open it in 'read only' mode.
		if ( is_readable( $file ) ) {

			$_file = fopen( $file, 'r' );

			if ( ! $_file ) {
				return array(
					'html' => __( "File could not be opened. Check the file's permissions to make sure it's readable by your server.", 'powerpack' ),
					'rows' => '',
				);
			}

			// To sum this part up, all it really does is go row by row.
			// Column by column, saving all the data.
			$file_data = array();

			// Get first row in CSV, which is of course the headers.
			$header = fgetcsv( $_file );

			while ( $row = fgetcsv( $_file ) ) {

				foreach ( $header as $i => $key ) {
					$file_data[ $key ] = $row[ $i ];
				}

				$data[] = $file_data;
			}

			fclose( $_file );

		} else {
			return array(
				'html' => __( "File could not be opened. Check the file's permissions to make sure it's readable by your server.", 'powerpack' ),
				'rows' => '',
			);
		}

		if ( is_array( $data ) ) {
			foreach ( $data as $key => $value ) {
				$rows[ $key ]       = $value;
				$rows_count[ $key ] = count( $value );
			}
		}
		$header_val = array_keys( $rows[0] );

		$return['rows'] = $rows_count;

		$heading_count = 0;

		ob_start();
		if ( 'yes' !== $settings['hide_table_header'] ) {
			?>
			<thead>
				<?php
				$counter_h      = 1;
				$cell_counter_h = 0;
				$inline_count   = 0;
				$header_text    = array();

				if ( $header_val ) {
					$this->add_render_attribute( 'row', 'class', 'pp-table-row' );
					?>
					<tr <?php $this->print_render_attribute_string( 'row' ); ?>>
					<?php
					foreach ( $header_val as $hkey => $head ) {
						$header_cell_key = $this->get_repeater_setting_key( 'table_header_col', 'table_headers', $inline_count );
						$this->add_render_attribute( $header_cell_key, 'class', 'pp-table-cell-text' );
						$this->add_render_attribute( 'header-' . $hkey, 'class', 'pp-table-cell' );
						$this->add_render_attribute( 'header-' . $hkey, 'class', 'pp-table-cell-' . $hkey );

						if ( 'responsive' === $settings['table_type'] && 'yes' === $settings['sortable'] ) {
							$pp_sortable_header = ' data-tablesaw-sortable-col';
						} else {
							$pp_sortable_header = '';
						}
						?>
						<th <?php $this->print_render_attribute_string( 'header-' . $hkey ) ?> <?php echo wp_kses_post( $pp_sortable_header ); ?>>
							<span class="pp-table-cell-content">
								<span <?php $this->print_render_attribute_string( $header_cell_key ); ?>>
									<?php echo wp_kses_post( $head ); ?>
								</span>
							</span>
						</th>
						<?php
						$header_text[ $cell_counter_h ] = $head;
						$cell_counter_h++;
						$counter_h++;
						$inline_count++;
					}
					?>
					</tr>
					<?php
				}
				?>
			</thead>
			<?php
		} ?>
		<tbody>
			<?php
			$counter           = 1;
			$cell_counter      = 0;
			$cell_inline_count = 0;

			foreach ( $rows as $row_key => $row ) {
				$this->add_render_attribute( 'row-' . $row_key, 'class', 'pp-table-row' );
				?>
				<tr <?php $this->print_render_attribute_string( 'row-' . $row_key ); ?>>
					<?php
					foreach ( $row as $bkey => $col ) {
						$body_cell_key = $this->get_repeater_setting_key( 'cell_text', 'table_body_content', $cell_inline_count );
						$this->add_render_attribute( $body_cell_key, 'class', 'pp-table-cell-text' );
						$bkey = $this->clean( $bkey );
						$this->add_render_attribute( 'body-' . $cell_counter, 'class', 'pp-table-cell' );
						$this->add_render_attribute( 'body-' . $cell_counter, 'class', 'pp-table-cell-' . $bkey );
						?>
						<td <?php $this->print_render_attribute_string( 'body-' . $cell_counter ); ?>>
							<span class="pp-table-cell-content">
								<span <?php $this->print_render_attribute_string( $body_cell_key ); ?>>
									<?php echo wp_kses_post( $col ); ?>
								</span>
							</span>
						</td>
						<?php
						// Increment to next cell.
						$cell_counter++;
						$counter++;
						$cell_inline_count++;
					}
					?>
				</tr>
				<?php
			}
			?>
		</tbody>
		<?php
		$html           = ob_get_clean();
		$return['html'] = $html;
		return $return;
	}

	private function get_media_url( $media ) {
		if ( is_array( $media ) ) {
			return isset( $media['url'] ) ? (string) $media['url'] : '';
		}

		return is_string( $media ) ? $media : '';
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$source = isset( $settings['source'] ) ? (string) $settings['source'] : '';
		$file   = isset( $settings['file'] ) ? $settings['file'] : '';

		$file_url = $this->get_media_url( $file );

		if ( 'file' === $source && '' === $file_url ) {
			$placeholder = sprintf( esc_html__( 'Click here to edit the "%1$s" settings and provide a valid CSV file.', 'powerpack' ), esc_attr( $this->get_title() ) );

			echo wp_kses_post( $this->render_editor_placeholder( [
				'title' => esc_html__( 'No CSV file selected!', 'powerpack' ),
				'body' => $placeholder,
			] ) );

			return;
		}

		$this->add_render_attribute( 'table-container', 'class', 'pp-table-container' );

		$this->add_render_attribute( 'table', 'class', [ 'pp-table' ] );

		if ( 'yes' !== $settings['hide_table_header'] ) {
			$this->add_render_attribute( 'table', 'class', [ 'tablesaw' ] );

			if ( 'yes' === $settings['sortable'] && 'show' !== $settings['sortable_dropdown'] && 'yes' !== $settings['hide_table_header'] ) {
				$this->add_render_attribute( 'table-container', 'class', 'pp-table-sortable-dd-hide' );
			}

			if ( 'responsive' === $settings['table_type'] ) {
				if ( 'yes' === $settings['scrollable'] && 'yes' !== $settings['hide_table_header'] ) {
					$this->add_render_attribute( 'table', 'data-tablesaw-mode', 'swipe' );
				} else {
					$this->add_render_attribute( 'table', 'data-tablesaw-mode', 'stack' );
				}
			}
		}
		?>
		<div <?php $this->print_render_attribute_string( 'table-container' ); ?>>
			<table 
			<?php
			$this->print_render_attribute_string( 'table' );
			if ( 'responsive' === $settings['table_type'] && 'yes' === $settings['sortable'] ) {
				echo ' data-tablesaw-sortable data-tablesaw-sortable-switch'; }
			?>
				>
				<?php
				if ( 'file' === $source ) {
					$this->render_column_styles();

					$csv = $this->parse_csv();
					echo wp_kses_post( $csv['html'] );
				} else {
					$this->render_column_styles();

					$this->render_header();

					$this->render_footer();

					$this->render_body();
				}
				?>
			</table>
		</div>
		<?php
	}
}
