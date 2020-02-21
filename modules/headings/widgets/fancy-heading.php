<?php
namespace PowerpackElementsLite\Modules\Headings\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Fancy Heading Widget
 */
class Fancy_Heading extends Powerpack_Widget {
    
    /**
	 * Retrieve fancy heading widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-fancy-heading';
    }

    /**
	 * Retrieve fancy heading widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Fancy Heading', 'powerpack' );
    }

    /**
	 * Retrieve the list of categories the fancy heading widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
    public function get_categories() {
        return [ 'power-pack' ];
    }

    /**
	 * Retrieve fancy heading widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'ppicon-heading power-pack-admin-icon';
    }

    /**
	 * Register fancy heading widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @access protected
	 */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*	CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Fancy Heading
         */
        $this->start_controls_section(
            'section_heading',
            [
                'label'                 => __( 'Fancy Heading', 'powerpack' ),
            ]
        );

        $this->add_control(
            'heading_text',
            [
                'label'                 => __( 'Heading', 'powerpack' ),
                'type'                  => Controls_Manager::TEXTAREA,
				'dynamic'               => [
					'active'   => true,
				],
                'label_block'           => true,
                'rows'                  => 2,
                'default'               => __( 'Add Your Heading Text Here', 'powerpack' ),
            ]
        );

        $this->add_control(
            'link',
            [
                'label'                 => __( 'Link', 'powerpack' ),
                'type'                  => Controls_Manager::URL,
				'dynamic'               => [
					'active'        => true,
                    'categories'    => [
                        TagsModule::POST_META_CATEGORY,
                        TagsModule::URL_CATEGORY
                    ],
				],
                'label_block'           => true,
                'placeholder'           => 'https://www.your-link.com',
            ]
        );
        
        $this->add_control(
            'heading_html_tag',
            [
                'label'                 => __( 'HTML Tag', 'powerpack' ),
                'type'                  => Controls_Manager::SELECT,
                'label_block'           => false,
                'default'               => 'h2',
                'options'               => [
                    'h1'     => __( 'H1', 'powerpack' ),
                    'h2'     => __( 'H2', 'powerpack' ),
                    'h3'     => __( 'H3', 'powerpack' ),
                    'h4'     => __( 'H4', 'powerpack' ),
                    'h5'     => __( 'H5', 'powerpack' ),
                    'h6'     => __( 'H6', 'powerpack' ),
                    'div'    => __( 'div', 'powerpack' ),
                    'span'   => __( 'span', 'powerpack' ),
                    'p'      => __( 'p', 'powerpack' ),
                ],
            ]
        );
        
        $this->add_responsive_control(
			'align',
			[
				'label'                 => __( 'Alignment', 'powerpack' ),
				'type'                  => Controls_Manager::CHOOSE,
                'label_block'           => false,
				'options'               => [
					'left' => [
						'title' => __( 'Left', 'powerpack' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'powerpack' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'powerpack' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'powerpack' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default'               => '',
				'selectors'             => [
					'{{WRAPPER}}'   => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->end_controls_section();

		/**
		 * Content Tab: Help Docs
		 *
		 * @since 1.4.8
		 * @access protected
		 */
		$this->start_controls_section(
			'section_help_docs',
			[
				'label' => __( 'Help Docs', 'powerpack' ),
			]
		);
		
		$this->add_control(
			'help_doc_1',
			[
				'type'            => Controls_Manager::RAW_HTML,
				/* translators: %1$s doc link */
				'raw'             => sprintf( __( '%1$s Watch Video Overview %2$s', 'powerpack' ), '<a href="https://www.youtube.com/watch?v=PxWWUTeW4dc&list=PLpsSO_wNe8Dz4vfe2tWlySBCCFEgh1qZj" target="_blank" rel="noopener">', '</a>' ),
				'content_classes' => 'pp-editor-doc-links',
			]
		);

		$this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Style Tab: First Part
         */
        $this->start_controls_section(
            'first_section_style',
            [
                'label'                 => __( 'Fancy Heading', 'powerpack' ),
                'tab'                   => Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'                  => 'typography',
                'label'                 => __( 'Typography', 'powerpack' ),
				'scheme'				=> Scheme_Typography::TYPOGRAPHY_1,
                'selector'              => '{{WRAPPER}} .pp-heading-text',
            ]
        );

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'                  => 'heading_text_shadow',
				'selector'              => '{{WRAPPER}} .pp-heading-text',
			]
		);

		$this->add_control(
			'heading_fill',
			[
				'label'                 => __( 'Fill', 'powerpack' ),
				'type'					=> Controls_Manager::SELECT,
				'options'				=> [
					'solid' 	=> __( 'Color', 'powerpack' ),
					'gradient' 	=> __( 'Background', 'powerpack' ),
				],
				'default'				=> 'solid',
				'prefix_class'			=> 'pp-heading-fill-',
				'separator'             => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'					=> 'gradient',
				'types'					=> [ 'gradient', 'classic' ],
				'selector'				=> '{{WRAPPER}} .pp-heading-text',
				'default'				=> 'gradient',
				'condition'				=> [
					'heading_fill'	=> 'gradient'
				]
			]
		);

        $this->add_control(
            'heading_text_color',
            [
                'label'                 => __( 'Text Color', 'powerpack' ),
                'type'                  => Controls_Manager::COLOR,
				'scheme'				=> [
					'type' 	=> Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
                'default'               => '',
                'selectors'             => [
                    '{{WRAPPER}} .pp-heading-text' => 'color: {{VALUE}};',
                ],
				'condition'				=> [
					'heading_fill' => 'solid'
				],
            ]
        );

        $this->end_controls_section();
    }

    /**
	 * Render fancy heading widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @access protected
	 */
    protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'fancy-heading', 'class', 'pp-fancy-heading' );
        $this->add_inline_editing_attributes( 'heading_text', 'basic' );
        $this->add_render_attribute( 'heading_text', 'class', 'pp-heading-text' );
        
        if ( ! empty( $settings['link']['url'] ) ) {
            $this->add_link_attributes( 'fancy-heading-link', $settings['link'] );
        }
        
        if ( $settings['heading_text'] ) {
            printf( '<%1$s %2$s>', $settings['heading_html_tag'], $this->get_render_attribute_string( 'fancy-heading' ) );
                if ( ! empty( $settings['link']['url'] ) ) { printf( '<a %1$s>', $this->get_render_attribute_string( 'fancy-heading-link' ) ); }
            
				printf( '<span %1$s>%2$s</span>', $this->get_render_attribute_string( 'heading_text' ), $this->parse_text_editor( $settings['heading_text'] ) );
            
                if ( ! empty( $settings['link']['url'] ) ) { printf( '</a>' ); }
            printf( '</%1$s>', $settings['heading_html_tag'] );
        }
    }

    /**
	 * Render fancy heading widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
    protected function _content_template() {
        ?>
        <{{{settings.heading_html_tag}}} class="pp-fancy-heading">
            <# if ( settings.link.url ) { #><a href="{{settings.link.url}}"><# } #>
                <#
                if ( settings.heading_text != '' ) {
                    var heading_text = settings.heading_text;

                    view.addRenderAttribute( 'heading_text', 'class', 'pp-heading-text' );

                    view.addInlineEditingAttributes( 'heading_text' );

                    var heading_text_html = '<span' + ' ' + view.getRenderAttributeString( 'heading_text' ) + '>' + heading_text + '</span>';

                    print( heading_text_html );
                }
                #>
            <# if ( settings.link.url ) { #></a><# } #>
        </{{{settings.heading_html_tag}}}>
        <?php
    }
}