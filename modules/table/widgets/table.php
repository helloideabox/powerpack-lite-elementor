<?php
namespace PowerpackElements\Modules\Table\Widgets;

use PowerpackElements\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Table Widget
 */
class Table extends Powerpack_Widget {
    
    public function get_name() {
        return 'pp-table';
    }

    public function get_title() {
        return __( 'Table', 'power-pack' );
    }

    public function get_categories() {
        return [ 'power-pack' ];
    }

    public function get_icon() {
        return 'ppicon-table power-pack-admin-icon';
    }
    
    public function get_script_depends() {
        return [
            'tablesaw',
            'powerpack-frontend',
        ];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_headers',
            [
                'label'                 => __( 'Header', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'sortable',
            [
                'label'             => __( 'Sortable', 'power-pack' ),
                'description'       => __( 'Enabled sorting the table data by clicking on the table headers', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'On', 'power-pack' ),
                'label_off'         => __( 'Off', 'power-pack' ),
                'return_value'      => 'yes',
            ]
        );
        
        $repeater_header = new Repeater();
        
        $repeater_header->add_control(
            'table_header_col',
            [
                'label'                 => __( 'Text', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'placeholder'           => __( 'Table Header', 'power-pack' ),
				'default'               => __( 'Table Header', 'power-pack' ),
            ]
        );
        
        $repeater_header->add_control(
			'cell_icon_type',
			[
				'label'                 => esc_html__( 'Icon Type', 'power-pack' ),
				'label_block'           => false,
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'none'        => [
						'title'   => esc_html__( 'None', 'power-pack' ),
						'icon'    => 'fa fa-ban',
					],
					'icon'        => [
						'title'   => esc_html__( 'Icon', 'power-pack' ),
						'icon'    => 'fa fa-star',
					],
					'image'       => [
						'title'   => esc_html__( 'Image', 'power-pack' ),
						'icon'    => 'fa fa-picture-o',
					],
				],
				'default'               => 'none',
			]
		);
        
        $repeater_header->add_control(
            'cell_icon',
            [
                'label'                 => __( 'Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
				'default'               => '',
				'condition'             => [
					'cell_icon_type' => 'icon',
				],
            ]
        );
        
        $repeater_header->add_control(
            'cell_icon_image',
            [
                'label'                 => __( 'Image', 'power-pack' ),
				'label_block'           => false,
                'type'                  => Controls_Manager::MEDIA,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
				'condition'             => [
					'cell_icon_type'       => 'image',
				],
            ]
        );
        
        $repeater_header->add_control(
            'cell_icon_position',
            [
                'label'                 => __( 'Icon Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'before',
                'options'               => [
                    'before'    => __( 'Before', 'power-pack' ),
                    'after'     => __( 'After', 'power-pack' ),
                ],
				'condition'             => [
					'cell_icon_type!'   => 'none',
				],
            ]
        );
        
        $repeater_header->add_control(
            'col_span',
            [
                'label'                 => __( 'Column Span', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
            ]
        );
        
        $repeater_header->add_control(
            'row_span',
            [
                'label'                 => __( 'Row Span', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
            ]
        );
        
        $repeater_header->add_control(
            'css_id',
            [
                'label'                 => __( 'CSS ID', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
            ]
        );
        
        $repeater_header->add_control(
            'css_classes',
            [
                'label'                 => __( 'CSS Classes', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
            ]
        );

		$this->add_control(
			'table_headers',
			[
				'label'                 => '',
				'type'                  => Controls_Manager::REPEATER,
				'default'               => [
					[
						'table_header_col' => __( 'Header Column #1', 'power-pack' ),
					],
					[
						'table_header_col' => __( 'Header Column #2', 'power-pack' ),
					],
					[
						'table_header_col' => __( 'Header Column #3', 'power-pack' ),
					],
				],
				'fields'                => array_values( $repeater_header->get_controls() ),
				'title_field'           => '{{{ table_header_col }}}',
			]
		);
        
        $this->end_controls_section();

        $this->start_controls_section(
            'section_body',
            [
                'label'                 => __( 'Body', 'power-pack' ),
            ]
        );
        
        $repeater_rows = new Repeater();
        
        $repeater_rows->add_control(
            'table_body_element',
            [
                'label'                 => __( 'Element', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'cell',
                'options'               => [
                    'row'       => __( 'Row', 'power-pack' ),
                    'cell'      => __( 'Cell', 'power-pack' ),
                ],
            ]
        );
        
        $repeater_rows->add_control(
            'cell_text',
            [
                'label'                 => __( 'Text', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'placeholder'           => '',
				'default'               => '',
				'condition'             => [
					'table_body_element' => 'cell',
				],
            ]
        );
        
        $repeater_rows->add_control(
			'cell_icon_type',
			[
				'label'                 => esc_html__( 'Icon Type', 'power-pack' ),
				'label_block'           => false,
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'none' => [
						'title' => esc_html__( 'None', 'power-pack' ),
						'icon' => 'fa fa-ban',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'power-pack' ),
						'icon' => 'fa fa-star',
					],
					'image' => [
						'title' => esc_html__( 'Image', 'power-pack' ),
						'icon' => 'fa fa-picture-o',
					],
				],
				'default'               => 'none',
				'condition'             => [
					'table_body_element' => 'cell',
				],
			]
		);
        
        $repeater_rows->add_control(
            'cell_icon',
            [
                'label'                 => __( 'Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
				'default'               => '',
				'condition'             => [
					'table_body_element'   => 'cell',
					'cell_icon_type'       => 'icon',
				],
            ]
        );
        
        $repeater_rows->add_control(
            'cell_icon_image',
            [
                'label'                 => __( 'Image', 'power-pack' ),
				'label_block'           => false,
                'type'                  => Controls_Manager::MEDIA,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
				'condition'             => [
					'table_body_element'   => 'cell',
					'cell_icon_type'       => 'image',
				],
            ]
        );
        
        $repeater_rows->add_control(
            'cell_icon_position',
            [
                'label'                 => __( 'Icon Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'before',
                'options'               => [
                    'before'    => __( 'Before', 'power-pack' ),
                    'after'     => __( 'After', 'power-pack' ),
                ],
				'condition'             => [
					'table_body_element'   => 'cell',
					'cell_icon_type!'      => 'none',
				],
            ]
        );
        
        $repeater_rows->add_control(
            'col_span',
            [
                'label'                 => __( 'Column Span', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'table_body_element' => 'cell',
				],
            ]
        );
        
        $repeater_rows->add_control(
            'row_span',
            [
                'label'                 => __( 'Row Span', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'table_body_element' => 'cell',
				],
            ]
        );
        
        $repeater_rows->add_control(
            'css_id',
            [
                'label'                 => __( 'CSS ID', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
            ]
        );
        
        $repeater_rows->add_control(
            'css_classes',
            [
                'label'                 => __( 'CSS Classes', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
            ]
        );

		$this->add_control(
			'table_body_content',
			[
				'label'                 => '',
				'type'                  => Controls_Manager::REPEATER,
				'default'               => [
					[
						'table_body_element'  => 'row',
					],
					[
						'table_body_element'  => 'cell',
						'cell_text'           => __( 'Column #1', 'power-pack' ),
					],
					[
						'table_body_element'  => 'cell',
						'cell_text'           => __( 'Column #2', 'power-pack' ),
					],
					[
						'table_body_element'  => 'cell',
						'cell_text'           => __( 'Column #3', 'power-pack' ),
					],
					[
						'table_body_element'  => 'row',
					],
					[
						'table_body_element'  => 'cell',
						'cell_text'           => __( 'Column #1', 'power-pack' ),
					],
					[
						'table_body_element'  => 'cell',
						'cell_text'           => __( 'Column #2', 'power-pack' ),
					],
					[
						'table_body_element'  => 'cell',
						'cell_text'           => __( 'Column #3', 'power-pack' ),
					],
				],
				'fields'                => array_values( $repeater_rows->get_controls() ),
				'title_field'           => __( 'Table', 'power-pack' ) . ' {{{ table_body_element }}}: {{{ cell_text }}}',
			]
		);
        
        $this->end_controls_section();

        $this->start_controls_section(
            'section_footer',
            [
                'label'                 => __( 'Footer', 'power-pack' ),
            ]
        );
        
        $repeater_footer_rows = new Repeater();
        
        $repeater_footer_rows->add_control(
            'table_footer_element',
            [
                'label'                 => __( 'Element', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'cell',
                'options'               => [
                    'row'       => __( 'Row', 'power-pack' ),
                    'cell'      => __( 'Cell', 'power-pack' ),
                ],
            ]
        );
        
        $repeater_footer_rows->add_control(
            'cell_text',
            [
                'label'                 => __( 'Text', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'dynamic'               => [
					'active'   => true,
				],
                'placeholder'           => '',
				'default'               => '',
				'condition'             => [
					'table_footer_element' => 'cell',
				],
            ]
        );
        
        $repeater_footer_rows->add_control(
			'cell_icon_type',
			[
				'label'                 => esc_html__( 'Icon Type', 'power-pack' ),
				'label_block'       => false,
				'type'                  => Controls_Manager::CHOOSE,
				'options'               => [
					'none' => [
						'title' => esc_html__( 'None', 'power-pack' ),
						'icon' => 'fa fa-ban',
					],
					'icon' => [
						'title' => esc_html__( 'Icon', 'power-pack' ),
						'icon' => 'fa fa-star',
					],
					'image' => [
						'title' => esc_html__( 'Image', 'power-pack' ),
						'icon' => 'fa fa-picture-o',
					],
				],
				'default'               => 'none',
				'condition'             => [
					'table_footer_element' => 'cell',
				],
			]
		);
        
        $repeater_footer_rows->add_control(
            'cell_icon',
            [
                'label'                 => __( 'Icon', 'power-pack' ),
                'type'                  => Controls_Manager::ICON,
				'default'               => '',
				'condition'             => [
					'table_footer_element' => 'cell',
					'cell_icon_type' => 'icon',
				],
            ]
        );
        
        $repeater_footer_rows->add_control(
            'cell_icon_image',
            [
                'label'                 => __( 'Image', 'power-pack' ),
				'label_block'       => false,
                'type'                  => Controls_Manager::MEDIA,
				'dynamic'               => [
					'active'   => true,
				],
                'default'               => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
				'condition'             => [
					'table_footer_element' => 'cell',
					'cell_icon_type' => 'image',
				],
            ]
        );
        
        $repeater_footer_rows->add_control(
            'cell_icon_position',
            [
                'label'                 => __( 'Icon Position', 'power-pack' ),
                'type'                  => Controls_Manager::SELECT,
                'default'               => 'before',
                'options'               => [
                    'before'    => __( 'Before', 'power-pack' ),
                    'after'     => __( 'After', 'power-pack' ),
                ],
				'condition'             => [
					'table_footer_element' => 'cell',
					'cell_icon_type' => 'icon',
					'cell_icon!' => '',
				],
            ]
        );
        
        $repeater_footer_rows->add_control(
            'col_span',
            [
                'label'                 => __( 'Column Span', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'table_footer_element' => 'cell',
				],
            ]
        );
        
        $repeater_footer_rows->add_control(
            'row_span',
            [
                'label'                 => __( 'Row Span', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
				'condition'             => [
					'table_footer_element' => 'cell',
				],
            ]
        );
        
        $repeater_footer_rows->add_control(
            'css_id',
            [
                'label'                 => __( 'CSS ID', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
            ]
        );
        
        $repeater_footer_rows->add_control(
            'css_classes',
            [
                'label'                 => __( 'CSS Classes', 'power-pack' ),
                'type'                  => Controls_Manager::TEXT,
				'default'               => '',
            ]
        );

		$this->add_control(
			'table_footer_content',
			[
				'label'                 => '',
				'type'                  => Controls_Manager::REPEATER,
				'default'               => [
					[
						'col_span' => '',
					],
				],
				'fields'                => array_values( $repeater_footer_rows->get_controls() ),
				'title_field'           => 'Table {{{ table_footer_element }}}: {{{ cell_text }}}',
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_table_style',
            [
                'label'                 => __( 'Table', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'table_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'size' => 100,
                    'unit' => '%',
                ],
                'size_units'            => [ '%', 'px' ],
                'range'                 => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1200,
                    ],
                ],
                'selectors'             => [
                    '{{WRAPPER}} .pp-table' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'table_align',
            [
                'label'                 => __( 'Alignment', 'power-pack' ),
                'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
                'default'               => 'center',
                'options'               => [
                    'left' 		=> [
                        'title' => __( 'Left', 'power-pack' ),
                        'icon' 	=> 'eicon-h-align-left',
                    ],
                    'center' 	=> [
                        'title' => __( 'Center', 'power-pack' ),
                        'icon' 	=> 'eicon-h-align-center',
                    ],
                    'right' 	=> [
                        'title' => __( 'Right', 'power-pack' ),
                        'icon' 	=> 'eicon-h-align-right',
                    ],
                ],
                'prefix_class'           => 'pp-table-',
            ]
        );

		$this->add_control(
			'table_border_radius',
			[
				'label'                 => __( 'Border Radius', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-table-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_table_header_style',
            [
                'label'                 => __( 'Header', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
			'table_header_align',
			[
				'label'                 => __( 'Horizontal Align', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => '',
                'selectors_dictionary'  => [
					'left'     => 'flex-start',
					'center'   => 'center',
					'right'    => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-table thead .pp-table-cell-content'   => 'justify-content: {{VALUE}};',
				],
			]
		);
        
        $this->add_control(
			'table_header_vertical_align',
			[
				'label'                 => __( 'Vertical Align', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'top'      => [
						'title'   => __( 'Top', 'power-pack' ),
						'icon'    => 'eicon-v-align-top',
					],
					'middle'   => [
						'title'   => __( 'Middle', 'power-pack' ),
						'icon'    => 'eicon-v-align-middle',
					],
					'bottom'   => [
						'title'   => __( 'Bottom', 'power-pack' ),
						'icon'    => 'eicon-v-align-bottom',
					],
				],
                'selectors_dictionary'  => [
					'top'      => 'flex-start',
					'middle'   => 'center',
					'bottom'   => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-table thead .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'table_header_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-table thead th.pp-table-cell',
            ]
        );

        $this->start_controls_tabs( 'tabs_header_style' );

        $this->start_controls_tab(
            'tab_header_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'table_header_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table thead th.pp-table-cell' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_header_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '#e6e6e6',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table thead th.pp-table-cell' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'section_table_header_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-table thead th.pp-table-cell',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_header_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'table_header_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table thead th.pp-table-cell:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_header_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table thead th.pp-table-cell:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

		$this->add_responsive_control(
			'table_header_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-table thead th.pp-table-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'             => 'before'
			]
		);
        
        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'                  => 'table_header_box_shadow',
				'selector'              => '{{WRAPPER}} .pp-table thead',
				'separator'             => 'before',
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_table_rows_style',
            [
                'label'                 => __( 'Rows', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'table_striped_rows',
            [
                'label'             => __( 'Striped Rows', 'power-pack' ),
                'type'              => Controls_Manager::SWITCHER,
                'default'           => 'no',
                'label_on'          => __( 'On', 'power-pack' ),
                'label_off'         => __( 'Off', 'power-pack' ),
                'return_value'      => 'yes',
                'separator'             => 'before',
            ]
        );

        $this->add_control(
            'table_rows_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tbody tr' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					'table_striped_rows!' => 'yes',
				],
            ]
        );

        $this->add_control(
            'table_rows_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tbody tr' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'table_striped_rows!' => 'yes',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'section_table_rowsborder',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
                'separator'             => 'before',
				'selector'              => '{{WRAPPER}} .pp-table tr',
				'condition'             => [
					'table_striped_rows!' => 'yes',
				],
			]
		);

        $this->start_controls_tabs( 'tabs_rows_alternate_style' );

        $this->start_controls_tab(
            'tab_row_even',
            [
                'label'                 => __( 'Even', 'power-pack' ),
				'condition'             => [
					'table_striped_rows' => 'yes',
				],
            ]
        );

        $this->add_control(
            'table_even_rows_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tr:nth-child(even)' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					'table_striped_rows' => 'yes',
				],
            ]
        );

        $this->add_control(
            'table_even_rows_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tr:nth-child(even)' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'table_striped_rows' => 'yes',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'section_table_even_rows_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
                'separator'             => 'before',
				'selector'              => '{{WRAPPER}} .pp-table tr:nth-child(even)',
				'condition'             => [
					'table_striped_rows' => 'yes',
				],
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_row_odd',
            [
                'label'                 => __( 'Odd', 'power-pack' ),
				'condition'             => [
					'table_striped_rows' => 'yes',
				],
            ]
        );

        $this->add_control(
            'table_odd_rows_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tr:nth-child(odd)' => 'background-color: {{VALUE}}',
                ],
				'condition'             => [
					'table_striped_rows' => 'yes',
				],
            ]
        );

        $this->add_control(
            'table_odd_rows_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tr:nth-child(odd)' => 'color: {{VALUE}}',
                ],
				'condition'             => [
					'table_striped_rows' => 'yes',
				],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'section_table_odd_rows_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
                'separator'             => 'before',
				'selector'              => '{{WRAPPER}} .pp-table tr:nth-child(odd)',
				'condition'             => [
					'table_striped_rows' => 'yes',
				],
			]
		);

        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_table_cells_style',
            [
                'label'                 => __( 'Cells', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
			'table_cells_horizontal_align',
			[
				'label'                 => __( 'Horizontal Align', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'           => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'            => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => '',
                'selectors_dictionary'  => [
					'left'     => 'flex-start',
					'center'   => 'center',
					'right'    => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-table tbody .pp-table-cell-content'   => 'justify-content: {{VALUE}};',
				],
			]
		);
        
        $this->add_control(
			'table_cells_vertical_align',
			[
				'label'                 => __( 'Vertical Align', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'top'      => [
						'title'   => __( 'Top', 'power-pack' ),
						'icon'    => 'eicon-v-align-top',
					],
					'middle'   => [
						'title'   => __( 'Middle', 'power-pack' ),
						'icon'    => 'eicon-v-align-middle',
					],
					'bottom'   => [
						'title'   => __( 'Bottom', 'power-pack' ),
						'icon'    => 'eicon-v-align-bottom',
					],
				],
                'selectors_dictionary'  => [
					'top'      => 'flex-start',
					'middle'   => 'center',
					'bottom'   => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-table tbody .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'table_cells_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-table tr .pp-table-cell',
            ]
        );

        $this->start_controls_tabs( 'tabs_cell_style' );

        $this->start_controls_tab(
            'tab_cell_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'table_cell_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table .pp-table-cell' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_cell_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table .pp-table-cell' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'section_cell_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-table .pp-table-cell',
			]
		);
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_cell_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'table_cell_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table .pp-table-cell:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_cell_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table .pp-table-cell:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

		$this->add_responsive_control(
			'table_cell_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-table tbody td.pp-table-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'             => 'before'
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_table_footer_style',
            [
                'label'                 => __( 'Footer', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
			'table_footer_align',
			[
				'label'                 => __( 'Horizontal Align', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'label_block'           => false,
				'options'               => [
					'left'      => [
						'title' => __( 'Left', 'power-pack' ),
						'icon'  => 'fa fa-align-left',
					],
					'center'    => [
						'title' => __( 'Center', 'power-pack' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'     => [
						'title' => __( 'Right', 'power-pack' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'default'               => '',
                'selectors_dictionary'  => [
					'left'     => 'flex-start',
					'center'   => 'center',
					'right'    => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-table tfoot .pp-table-cell-content'   => 'justify-content: {{VALUE}};',
				],
			]
		);
        
        $this->add_control(
			'table_footer_vertical_align',
			[
				'label'                 => __( 'Vertical Align', 'power-pack' ),
				'type'                  => Controls_Manager::CHOOSE,
				'label_block'           => false,
				'options'               => [
					'top'      => [
						'title'   => __( 'Top', 'power-pack' ),
						'icon'    => 'eicon-v-align-top',
					],
					'middle'   => [
						'title'   => __( 'Middle', 'power-pack' ),
						'icon'    => 'eicon-v-align-middle',
					],
					'bottom'   => [
						'title'   => __( 'Bottom', 'power-pack' ),
						'icon'    => 'eicon-v-align-bottom',
					],
				],
                'selectors_dictionary'  => [
					'top'      => 'flex-start',
					'middle'   => 'center',
					'bottom'   => 'flex-end',
				],
				'selectors'             => [
					'{{WRAPPER}} .pp-table tfoot .pp-table-cell-content'   => 'align-items: {{VALUE}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'table_footer_typography',
                'label'                 => __( 'Typography', 'power-pack' ),
                'scheme'                => Scheme_Typography::TYPOGRAPHY_4,
                'selector'              => '{{WRAPPER}} .pp-table tfoot td.pp-table-cell',
            ]
        );

        $this->start_controls_tabs( 'tabs_footer_style' );

        $this->start_controls_tab(
            'tab_footer_normal',
            [
                'label'                 => __( 'Normal', 'power-pack' ),
            ]
        );

        $this->add_control(
            'table_footer_text_color',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tfoot td.pp-table-cell' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_footer_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tfoot td.pp-table-cell' => 'background-color: {{VALUE}}',
                ],
            ]
        );

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'                  => 'section_table_footer_border',
				'label'                 => __( 'Border', 'power-pack' ),
				'placeholder'           => '1px',
				'default'               => '1px',
				'selector'              => '{{WRAPPER}} .pp-table tfoot td.pp-table-cell',
			]
		);

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_footer_hover',
            [
                'label'                 => __( 'Hover', 'power-pack' ),
            ]
        );

        $this->add_control(
            'table_footer_text_color_hover',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tfoot td.pp-table-cell:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_footer_bg_color_hover',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tfoot td.pp-table-cell:hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

		$this->add_responsive_control(
			'table_footer_padding',
			[
				'label'                 => __( 'Padding', 'power-pack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-table tfoot td.pp-table-cell' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator'             => 'before'
			]
		);
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'section_table_columns_style',
            [
                'label'                 => __( 'Columns', 'power-pack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $repeater_columns = new Repeater();
        
        $repeater_columns->add_control(
            'table_col_span',
            [
                'label'                 => __( 'Span', 'power-pack' ),
                'type'                  => Controls_Manager::NUMBER,
                'default'               => 1,
                 'min'                  => 0,
                 'max'                  => 999,
                 'step'                 => 1,
            ]
        );
        
        $repeater_columns->add_control(
            'table_col_bg_color',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
            ]
        );

        $repeater_columns->add_control(
            'table_col_width',
            [
                'label'                 => __( 'Width', 'power-pack' ),
                'type'                  => Controls_Manager::SLIDER,
                'size_units'            => [ '%', 'px' ],
                'range'                 => [
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 1200,
                    ],
                ],
            ]
        );
        
        $this->add_control(
			'table_column_styles',
			[
				'label'                 => __( 'Column Styles', 'power-pack' ),
				'type'                  => Controls_Manager::REPEATER,
				'default'               => [
					[
						'table_col_span' => '1',
					],
				],
				'fields'                => array_values( $repeater_columns->get_controls() ),
				'title_field'           => 'Column Span {{{ table_col_span }}}',
			]
		);
        

        $this->start_controls_tabs( 'tabs_columns_style' );

        $this->start_controls_tab(
            'tab_columns_even',
            [
                'label'                 => __( 'Even', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'table_columns_body',
            [
                'label'                 => __( 'Body', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'table_columns_text_color_body_even',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tbody tr td:nth-child(even)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_columns_bg_color_body_even',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tbody tr td:nth-child(even)' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'table_columns_header_even',
            [
                'label'                 => __( 'Header', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'table_columns_text_color_header_even',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table thead tr th:nth-child(even)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_columns_bg_color_header_even',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table thead tr th:nth-child(even)' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'table_columns_footer_even',
            [
                'label'                 => __( 'Footer', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'table_columns_text_color_footer_even',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tfoot tr td:nth-child(even)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_columns_bg_color_footer_even',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tfoot tr td:nth-child(even)' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_columns_odd',
            [
                'label'                 => __( 'Odd', 'power-pack' ),
            ]
        );
        
        $this->add_control(
            'table_columns_body_odd',
            [
                'label'                 => __( 'Body', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'table_columns_text_color_body_odd',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tbody tr td:nth-child(odd)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_columns_bg_color_body_odd',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tbody tr td:nth-child(odd)' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'table_columns_header_odd',
            [
                'label'                 => __( 'Header', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'table_columns_text_color_header_odd',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table thead tr th:nth-child(odd)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_columns_bg_color_header_odd',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table thead tr th:nth-child(odd)' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->add_control(
            'table_columns_footer_odd',
            [
                'label'                 => __( 'Footer', 'power-pack' ),
                'type'                  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'table_columns_text_color_footer_odd',
            [
                'label'                 => __( 'Text Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tfoot tr td:nth-child(odd)' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'table_columns_bg_color_footer_odd',
            [
                'label'                 => __( 'Background Color', 'power-pack' ),
                'type'                  => Controls_Manager::COLOR,
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-table tfoot tr td:nth-child(odd)' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();

    }
    
    protected function render_column_styles() {
        $settings = $this->get_settings_for_display();

        if ( $settings['table_column_styles'] ) { ?>
            <colgroup>
                <?php foreach( $settings['table_column_styles'] as $col_style ) { ?>
                <col
                     span="<?php echo $col_style['table_col_span']; ?>"
                     class="elementor-repeater-item-<?php echo $col_style['_id']; ?>"
                     <?php if ( $col_style['table_col_bg_color'] || $col_style['table_col_width'] ) { ?>
                        style="
                        <?php if ( $col_style['table_col_bg_color'] ) { ?>
                        background-color:<?php echo $col_style['table_col_bg_color']; ?>;
                        <?php } ?>
                        <?php if ( $col_style['table_col_width'] ) { ?>
                        width:<?php echo $col_style['table_col_width']['size'] . $col_style['table_col_width']['unit']; ?>
                        <?php } ?>
                        "
                     <?php } ?>
                     >
                <?php } ?>
            </colgroup>
            <?php
        }
    }
    
    protected function render_header() {
        $settings = $this->get_settings_for_display();
        $i = 1;
        $this->add_render_attribute( 'row', 'class', 'pp-table-row' );
        ?>
        <thead>
            <tr <?php echo $this->get_render_attribute_string( 'row' ); ?>>
                <?php
                    $pp_output = '';
                    foreach ( $settings['table_headers'] as $index => $item ) :

                        $header_cell_key = $this->get_repeater_setting_key( 'table_header_col', 'table_headers', $index );
                        $this->add_render_attribute( $header_cell_key, 'class', 'pp-table-cell-text' );
                        $this->add_inline_editing_attributes( $header_cell_key, 'basic' );

                        $this->add_render_attribute( 'header-' . $i, 'class', 'pp-table-cell' );
                        $this->add_render_attribute( 'header-' . $i, 'class', 'pp-table-cell-' . $item['_id'] );

                        if ( $item['css_id'] != '' ) {
                            $this->add_render_attribute( 'header-' . $i, 'id', $item['css_id'] );
                        }

                        if ( $item['css_classes'] != '' ) {
                            $this->add_render_attribute( 'header-' . $i, 'class', $item['css_classes'] );
                        }

                        if ( $item['cell_icon_type'] != 'none' ) {
                            $this->add_render_attribute( 'icon-' . $i, 'class', 'pp-table-cell-icon' );
                            $this->add_render_attribute( 'icon-' . $i, 'class', 'pp-table-cell-icon-' . $item['cell_icon_position'] );
                        }

                        if ( $item['col_span'] > 1 ) {
                            $this->add_render_attribute( 'header-' . $i, 'colspan', $item['col_span'] );
                        }

                        if ( $item['row_span'] > 1 ) {
                            $this->add_render_attribute( 'header-' . $i, 'rowspan', $item['row_span'] );
                        }

                        if ( $settings['sortable'] == 'yes' ) {
                            $pp_sortable_header = ' data-tablesaw-sortable-col';
                        } else {
                            $pp_sortable_header = '';
                        }

                        $pp_output .= '<th ' . $this->get_render_attribute_string( 'header-' . $i ) . $pp_sortable_header . '>';
                        $pp_output .= '<span class="pp-table-cell-content">';
                        if ( $item['cell_icon_type'] != 'none' ) {
                            $pp_output .= '<span ' . $this->get_render_attribute_string( 'icon-' . $i ) .'>';
                            if ( $item['cell_icon_type'] == 'icon' && $item['cell_icon'] != '' ) {
                                $pp_output .= '<span class="' . esc_attr( $item['cell_icon'] ) . '"></span>';
                            }
                            elseif ( $item['cell_icon_type'] == 'image' && $item['cell_icon_image']['url'] != '' ) {
                                $pp_output .= '<img src="' . esc_url( $item['cell_icon_image']['url'] ) . '">';
                            }
                            $pp_output .= '</span>';
                        }
                        $pp_output .= '<span ' . $this->get_render_attribute_string( $header_cell_key ) . '>';
                        $pp_output .= $item['table_header_col'];
                        $pp_output .= '</span>';
                        $pp_output .= '</span>';
                        $pp_output .= '</th>';
                    $i++;
                    endforeach;
                    echo $pp_output;
                ?>
            </tr>
        </thead>
        <?php
    }
    
    protected function render_footer() {
        $settings = $this->get_settings_for_display();
        $i = 1;
        ?>
        <tfoot>
            <?php
                $pp_output = '';
                if ( !empty( $settings['table_footer_content'] ) ) {
                    foreach ( $settings['table_footer_content'] as $index => $item ) {
                        if ( $item['table_footer_element'] == 'cell' ) {

                            $footer_cell_key = $this->get_repeater_setting_key( 'cell_text', 'table_footer_content', $index );
                            $this->add_render_attribute( $footer_cell_key, 'class', 'pp-table-cell-text' );
                            $this->add_inline_editing_attributes( $footer_cell_key, 'basic' );

                            $this->add_render_attribute( 'footer-' . $i, 'class', 'pp-table-cell' );
                            $this->add_render_attribute( 'footer-' . $i, 'class', 'pp-table-cell-' . $item['_id'] );

                            if ( $item['css_id'] != '' ) {
                                $this->add_render_attribute( 'footer-' . $i, 'id', $item['css_id'] );
                            }

                            if ( $item['css_classes'] != '' ) {
                                $this->add_render_attribute( 'footer-' . $i, 'class', $item['css_classes'] );
                            }

                            if ( $item['cell_icon_type'] != 'none' ) {
                                $this->add_render_attribute( 'footer-' . $i, 'class', 'pp-table-cell-icon-' . $item['cell_icon_position'] );
                            }

                            if ( $item['col_span'] > 1 ) {
                                $this->add_render_attribute( 'footer-' . $i, 'colspan', $item['col_span'] );
                            }

                            if ( $item['row_span'] > 1 ) {
                                $this->add_render_attribute( 'footer-' . $i, 'rowspan', $item['row_span'] );
                            }

                            if ( $item['cell_text'] != '' || $item['cell_icon_type'] != 'none' ) {
                                $pp_output .= '<td ' . $this->get_render_attribute_string( 'footer-' . $i ) . '">';
                                $pp_output .= '<span class="pp-table-cell-content">';
                                if ( $item['cell_icon_type'] != 'none' ) {
                                    $pp_output .= '<span class="pp-table-cell-icon">';
                                    if ( $item['cell_icon_type'] == 'icon' && $item['cell_icon'] != '' ) {
                                        $pp_output .= '<span class="' . esc_attr( $item['cell_icon'] ) . '"></span>';
                                    }
                                    elseif ( $item['cell_icon_type'] == 'image' && $item['cell_icon_image']['url'] != '' ) {
                                        $pp_output .= '<img src="' . esc_url( $item['cell_icon_image']['url'] ) . '">';
                                    }
                                    $pp_output .= '</span>';
                                }
                                $pp_output .= '<span ' . $this->get_render_attribute_string( $footer_cell_key ) . '>';
                                $pp_output .= $item['cell_text'];
                                $pp_output .= '</span>';
                                $pp_output .= '</span>';
                                $pp_output .= '</td>';
                            }
                        }
                        else {
                            if ( $i === 1 && $item['table_footer_element'] == 'row' ) {
                                $pp_output .= '<tr>';
                            }
                            else if ( $i > 1 ) {
                                $pp_output .= '</tr><tr>';
                            }
                        }
                        $i++;
                    }
                }
                echo $pp_output;
                echo '</tr>';
            ?>
        </tfoot>
        <?php
    }
    
    protected function render_body() {
        $settings = $this->get_settings_for_display();
        $i = 1;
        ?>
        <tbody>
            <?php
                $pp_output = '';
                foreach ( $settings['table_body_content'] as $index => $item ) {
                    if ( $item['table_body_element'] == 'cell' ) {

                        $body_cell_key = $this->get_repeater_setting_key( 'cell_text', 'table_body_content', $index );
                        $this->add_render_attribute( $body_cell_key, 'class', 'pp-table-cell-text' );
                        $this->add_inline_editing_attributes( $body_cell_key, 'basic' );
                        
                        $this->add_render_attribute( 'row-' . $i, 'class', 'pp-table-row' );
                        $this->add_render_attribute( 'row-' . $i, 'class', 'pp-table-row-' . $item['_id'] );
                        $this->add_render_attribute( 'body-' . $i, 'class', 'pp-table-cell' );
                        $this->add_render_attribute( 'body-' . $i, 'class', 'pp-table-cell-' . $item['_id'] );

                        if ( $item['css_id'] != '' ) {
                            $this->add_render_attribute( 'body-' . $i, 'id', $item['css_id'] );
                        }

                        if ( $item['css_classes'] != '' ) {
                            $this->add_render_attribute( 'body-' . $i, 'class', $item['css_classes'] );
                        }

                        if ( $item['cell_icon_type'] != 'none' ) {
                            $this->add_render_attribute( 'icon-' . $i, 'class', 'pp-table-cell-icon' );
                            $this->add_render_attribute( 'icon-' . $i, 'class', 'pp-table-cell-icon-' . $item['cell_icon_position'] );
                        }

                        if ( $item['col_span'] > 1 ) {
                            $this->add_render_attribute( 'body-' . $i, 'colspan', $item['col_span'] );
                        }

                        if ( $item['row_span'] > 1 ) {
                            $this->add_render_attribute( 'body-' . $i, 'rowspan', $item['row_span'] );
                        }

                        if ( $item['cell_text'] != '' || $item['cell_icon_type'] != 'none' ) {
                            $pp_output .= '<td ' . $this->get_render_attribute_string( 'body-' . $i ) . '>';
                            $pp_output .= '<span class="pp-table-cell-content">';
                            if ( $item['cell_icon_type'] != 'none' ) {
                                $pp_output .= '<span ' . $this->get_render_attribute_string( 'icon-' . $i ) .'>';
                                if ( $item['cell_icon_type'] == 'icon' && $item['cell_icon'] != '' ) {
                                    $pp_output .= '<span class="' . esc_attr( $item['cell_icon'] ) . '"></span>';
                                }
                                elseif ( $item['cell_icon_type'] == 'image' && $item['cell_icon_image']['url'] != '' ) {
                                    $pp_output .= '<img src="' . esc_url( $item['cell_icon_image']['url'] ) . '">';
                                }
                                $pp_output .= '</span>';
                            }
                            $pp_output .= '<span ' . $this->get_render_attribute_string( $body_cell_key ) . '>';
                            $pp_output .= $item['cell_text'];
                            $pp_output .= '</span>';
                            $pp_output .= '</span>';
                            $pp_output .= '</td>';
                        }
                    }
                    else {
                        if ( $i === 1 && $item['table_body_element'] == 'row' ) {
                            $pp_output .= '<tr ' . $this->get_render_attribute_string( 'row-' . $i ) . '>';
                        }
                        else if ( $i > 1 ) {
                            $pp_output .= '</tr><tr ' . $this->get_render_attribute_string( 'row-' . $i ) . '>';
                        }
                    }
                    $i++;
                }
                echo $pp_output;
                echo '</tr>';
            ?>
        </tbody>
        <?php
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'table', 'class', 'pp-table' );
        $this->add_render_attribute( 'table', 'data-tablesaw-mode', 'stack' );
        ?>
        <div class="pp-table-container">
            <table <?php echo $this->get_render_attribute_string( 'table' ); if ( $settings['sortable'] == 'yes' ) { echo ' data-tablesaw-sortable data-tablesaw-sortable-switch'; } ?>>
                <?php
                    $this->render_column_styles();

                    $this->render_header();

                    $this->render_footer();

                    $this->render_body();
                ?>
            </table>
        </div>
        <?php
    }
    
    protected function _column_styles_template() {
        ?>
        <# if ( settings.table_column_styles != '' ) { #>
            <colgroup>
                <# _.each( settings.table_column_styles, function( item ) { #>
                    <col
                         span="{{ item.table_col_span }}"
                         class="elementor-repeater-item-{{ item._id }}"
                         <# if ( item.table_col_bg_color || item.table_col_width ) { #>
                            style="
                            <# if ( item.table_col_bg_color ) { #>
                                background-color: {{ item.table_col_bg_color }};
                            <# } #>
                            <# if ( item.table_col_width ) { #>
                                width: {{ item.table_col_width.size}}px;
                            <# } #>
                            "
                         <# } #>
                         >
                <# } ); #>
            </colgroup>
        <# } #>
        <?php
    }
    
    protected function _header_template() {
        ?>
        <#
            var $i = 1,
                $sortable_header = '';
           
            if ( settings.sortable == 'yes' ) {
                $sortable_header = 'data-tablesaw-sortable-col';
            }
        #>
        <thead>
            <tr class="pp-table-row">
                <# _.each( settings.table_headers, function( item ) { #>
                    <th id="{{ item.css_id }}" class="pp-table-cell pp-table-cell-{{ item._id }} {{ item.css_classes }}" colspan="{{ item.col_span }}" rowspan="{{ item.row_span }}" {{ $sortable_header }}>
                        <span class="pp-table-cell-content">
                            <# if ( item.cell_icon_type != 'none' ) { #>
                                <span class="pp-table-cell-icon pp-table-cell-icon-{{ item.cell_icon_position }}">
                                    <# if ( item.cell_icon_type == 'icon' && item.cell_icon != '' ) { #>
                                        <span class="{{ item.cell_icon }}"></span>
                                    <# } else if ( item.cell_icon_type == 'image' && item.cell_icon_image.url != '' ) { #>
                                        <img src="{{ item.cell_icon_image.url }}">
                                    <# } #>
                                </span>
                            <# } #>

                            <#
                                var header_text = item.table_header_col;

                                view.addRenderAttribute( 'table_headers.{{ $i - 1 }}.text', 'class', 'pp-table-cell-text' );

                                view.addInlineEditingAttributes( 'table_headers.{{ $i - 1 }}.text', 'basic' );

                                var header_text_html = '<span' + ' ' + view.getRenderAttributeString( 'table_headers.{{ $i - 1 }}.text' ) + '>' + header_text + '</span>';

                                print( header_text_html );
                            #>
                        </span>
                    </th>
                <# $i++; } ); #>
            </tr>
        </thead>
        <?php
    }
    
    protected function _footer_template() {
        ?>
        <#
            var $i = 1;
        #>
        <tfoot>
            <# if ( settings.table_footer_content != '' ) { #>
                <# _.each( settings.table_footer_content, function( item ) { #>
                    <# if ( item.table_footer_element == 'cell' ) { #>
                        <# if ( item.cell_text != '' || item.cell_icon_type != 'none' ) { #>
                            <td id="{{ item.css_id }}" class="pp-table-cell pp-table-cell-{{ item._id }} {{ item.css_classes }} pp-table-cell-icon-{{ item.cell_icon_position }}" colspan="{{ item.col_span }}" rowspan="{{ item.row_span }}">
                                <span class="pp-table-cell-content">
                                    <# if ( item.cell_icon_type != 'none' ) { #>
                                        <span class="pp-table-cell-icon pp-table-cell-icon-{{ item.cell_icon_position }}">
                                            <# if ( item.cell_icon_type == 'icon' && item.cell_icon != '' ) { #>
                                                <span class="{{ item.cell_icon }}"></span>
                                            <# } else if ( item.cell_icon_type == 'image' && item.cell_icon_image.url != '' ) { #>
                                                <img src="{{ item.cell_icon_image.url }}">
                                            <# } #>
                                        </span>
                                    <# } #>

                                    <#
                                        var footer_text = item.cell_text;

                                        view.addRenderAttribute( 'table_footer_content.{{ $i - 1 }}.text', 'class', 'pp-table-cell-text' );

                                        view.addInlineEditingAttributes( 'table_footer_content.{{ $i - 1 }}.text', 'basic' );

                                        var footer_text_html = '<span' + ' ' + view.getRenderAttributeString( 'table_footer_content.{{ $i - 1 }}.text' ) + '>' + footer_text + '</span>';

                                        print( footer_text_html );
                                    #>
                                </span>
                            </td>
                        <# } #>
                    <# } else { #>
                        <# if ( $i === 1 && item.table_footer_element == 'row' ) { #>
                            <tr>
                        <# } else if ( $i > 1 ) { #>
                            </tr><tr>
                        <# } #>
                    <# } #>
                <# $i++; } ); #>
            <# } #>
            </tr>
        </tfoot>
        <?php
    }
    
    protected function _body_template() {
        ?>
        <#
            var $i = 1;
        #>
        <tbody>
            <# _.each( settings.table_body_content, function( item ) { #>
                <# if ( item.table_body_element == 'cell' ) { #>
                    <# if ( item.cell_text != '' || item.cell_icon_type != 'none' ) { #>
                        <td id="{{ item.css_id }}" class="pp-table-cell pp-table-cell-{{ item._id }} {{ item.css_classes }} pp-table-cell-icon-{{ item.cell_icon_position }}" colspan="{{ item.col_span }}" rowspan="{{ item.row_span }}">
                            <span class="pp-table-cell-content">
                                <# if ( item.cell_icon_type != 'none' ) { #>
                                    <span class="pp-table-cell-icon pp-table-cell-icon-{{ item.cell_icon_position }}">
                                        <# if ( item.cell_icon_type == 'icon' && item.cell_icon != '' ) { #>
                                            <span class="{{ item.cell_icon }}"></span>
                                        <# } else if ( item.cell_icon_type == 'image' && item.cell_icon_image.url != '' ) { #>
                                            <img src="{{ item.cell_icon_image.url }}">
                                        <# } #>
                                    </span>
                                <# } #>

                                <#
                                    var body_text = item.cell_text;

                                    view.addRenderAttribute( 'table_body_content.{{ $i - 1 }}.table_header_col', 'class', 'pp-table-cell-text' );

                                    view.addInlineEditingAttributes( 'table_body_content.{{ $i - 1 }}.table_header_col', 'basic' );

                                    var body_text_html = '<span' + ' ' + view.getRenderAttributeString( 'table_body_content.{{ $i - 1 }}.table_header_col' ) + '>' + body_text + '</span>';

                                    print( body_text_html );
                                #>
                            </span>
                        </td>
                    <# } #>
                <# } else { #>
                    <# if ( $i === 1 && item.table_body_element == 'row' ) { #>
                        <tr class="pp-table-row pp-table-row-{{ item._id }}">
                    <# } else if ( $i > 1 ) { #>
                        </tr><tr class="pp-table-row pp-table-row-{{ item._id }}">
                    <# } #>
                <# } #>
            <# $i++; } ); #>
            </tr>
        </tbody>
        <?php
    }

    protected function _content_template() {
        ?>
        <#
            var $sortable  = ( settings.sortable == 'yes' ) ? 'data-tablesaw-sortable data-tablesaw-sortable-switch' : '';   
        #>
        <div class="pp-table-container">
            <table class="pp-table" data-tablesaw-mode="stack" {{ $sortable }}>
                <?php
                    $this->_column_styles_template();

                    $this->_header_template();

                    $this->_footer_template();

                    $this->_body_template();
                ?>
            </table>
        </div>           
        <?php
    }
}