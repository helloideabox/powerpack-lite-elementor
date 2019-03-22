<?php
namespace PowerpackElementsLite\Modules\ScrollImage\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Modules\ScrollImage\Module;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Scheme_Typography;
use Elementor\Embed;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Scroll Image Widget
 */
class Scroll_Image extends Powerpack_Widget {
    
    /**
	 * Retrieve showcase widget name.
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
    public function get_name() {
        return 'pp-scroll-image';
    }

    /**
	 * Retrieve showcase widget title.
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
    public function get_title() {
        return __( 'Scroll Image', 'powerpack' );
    }

    /**
	 * Retrieve the list of categories the showcase widget belongs to.
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
	 * Retrieve showcase widget icon.
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
    public function get_icon() {
        return 'eicon-import-export power-pack-admin-icon';
    }
    
    /**
	 * Retrieve the list of scripts the showcase widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
        return [
            'imagesloaded',
            'powerpack-frontend'
        ];
    }

	protected function _register_controls() {

		/*-----------------------------------------------------------------------------------*/
        /*	CONTENT TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Content Tab: Image
         */
		$this->start_controls_section('image_settings',
            [
				'label'					=> __('Image', 'powerpack')
            ]
        );

		$this->add_control('image',
			[
				'label'					=> __('Image', 'powerpack'),
				'type'					=> Controls_Manager::MEDIA,
				'dynamic'				=> [ 'active' => true ],
				'default'				=> [
					'url'	=> Utils::get_placeholder_image_src(),
					],
				'label_block'			=> true
			]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'                  => 'image',
                'label'                 => __( 'Image Size', 'powerpack' ),
                'default'               => 'full',
            ]
        );
        
		$this->add_responsive_control('image_height',
            [
				'label'					=> __('Image Height', 'powerpack'),
				'type'					=> Controls_Manager::SLIDER,
				'size_units'			=> ['px', 'em', 'vh'],
				'default'				=> [
                    'unit'  => 'px',
                    'size'  => 300,
                ],
				'range'					=> [
                    'px'    => [
                        'min'   => 200,
                        'max'   => 800,
                    ],
                    'em'    => [
                        'min'   => 1,
                        'max'   => 50,
                    ],
                ],
				'selectors'				=> [
                    '{{WRAPPER}} .pp-image-scroll-container' => 'height: {{SIZE}}{{UNIT}};',
                ]
            ]
        );

        $this->add_control('link',
            [
				'label'					=> __('URL', 'powerpack'),
				'type'					=> Controls_Manager::URL,
				'placeholder'			=> 'https://powerpackelements.com/',
				'label_block'			=> true
            ]
        );
        
        $this->add_control('icon_heading',
            [
				'label'					=> __('Icon', 'powerpack'),
				'type'					=> Controls_Manager::HEADING,
				'separator'				=> 'before',
            ]
        );

        $this->add_control(
            'icon',
            [
                'label'                 => __( 'Cover Icon', 'powerpack' ),
                'type'                  => Controls_Manager::ICON,
                'default'               => '',
            ]
        );

        $this->add_control('icon_size',
            [
				'label'					=> __('Icon Size', 'powerpack'),
				'type'					=> Controls_Manager::SLIDER,
				'size_units'			=> ['px','em'],
				'default'				=> [
                    'size'  => 30,
                ],
				'range'					=> [
                    'px'    => [
                        'min' => 5,
                        'max' => 100
                    ],
                ],
				'selectors'				=> [
                    '{{WRAPPER}} .pp-image-scroll-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
				'condition'				=> [
                    'icon!'		=> ''
                ]
            ]
        );

        $this->end_controls_section();

		/**
         * Content Tab: Settings
         */
        $this->start_controls_section('settings',
			[
				'label'					=> __( 'Settings' , 'powerpack' )
			]
        );

        $this->add_control('trigger_type', 
            [
				'label'					=> __('Trigger', 'powerpack'),
				'type'					=> Controls_Manager::SELECT,
				'options'				=> [
                    'hover'   => __('Hover', 'powerpack'),
                    'scroll'  => __('Mouse Scroll', 'powerpack'),
                ],
				'default'				=> 'hover',
            ]
        );

        $this->add_control('duration_speed',
            [
				'label'					=> __( 'Scroll Speed', 'powerpack' ),
				'title'					=> __( 'In seconds', 'powerpack' ),
				'type'					=> Controls_Manager::NUMBER,
				'default'				=> 3,
                'selectors' => [
                    '{{WRAPPER}} .pp-image-scroll-container .pp-image-scroll-image img'   => 'transition: all {{Value}}s; -webkit-transition: all {{Value}}s;',
                ],
				'condition'				=> [
                    'trigger_type' => 'hover',
                ],
            ]
        );

        $this->add_control('direction_type',
            [
				'label'					=> __( 'Scroll Direction', 'powerpack' ),
				'type'					=> Controls_Manager::SELECT,
				'options'				=> [
                    'horizontal' => __( 'Horizontal', 'powerpack' ),
                    'vertical'   => __( 'Vertical', 'powerpack' )
                ],
				'default'				=> 'vertical',
				'frontend_available'	=> true,
            ]
        );
        
        $this->add_control('reverse',
            [
				'label'					=> __( 'Reverse Direction', 'powerpack' ),
				'type'					=> Controls_Manager::SWITCHER,
				'frontend_available'	=> true,
				'condition'				=> [
                    'trigger_type' => 'hover',
                ]
            ]
        );

        $this->end_controls_section();

		/*-----------------------------------------------------------------------------------*/
        /*	STYLE TAB
        /*-----------------------------------------------------------------------------------*/
        
        /**
         * Style Tab: Image
         */
        $this->start_controls_section('image_style',
            [
				'label'					=> __('Image', 'powerpack'),
                'tab'					=> Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control('icon_color',
            [
				'label'					=> __('Icon Color', 'powerpack'),
				'type'					=> Controls_Manager::COLOR,
				'selectors'				=> [
                    '{{WRAPPER}} .pp-image-scroll-icon'  => 'color: {{VALUE}};'
                ],
				'condition'				=> [
                    'icon!'		=> ''
                ]
            ]
        );
        
        $this->start_controls_tabs('image_style_tabs');
        
        $this->start_controls_tab('image_style_tab_normal',
            [
				'label'					=> __('Normal', 'powerpack'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
				'name'					=> 'container_border',
				'selector'				=> '{{WRAPPER}} .pp-image-scroll-wrap',
            ]
        );

		$this->add_control(
			'image_border_radius',
			[
				'label'                 => __( 'Border Radius', 'powerpack' ),
				'type'                  => Controls_Manager::DIMENSIONS,
				'size_units'            => [ 'px', '%', 'em' ],
				'selectors'             => [
					'{{WRAPPER}} .pp-image-scroll-wrap, {{WRAPPER}} .pp-container-scroll' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
				'name'					=> 'container_box_shadow',
				'selector'				=> '{{WRAPPER}} .pp-image-scroll-wrap',
            ]
        );

        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
				'name'					=> 'css_filters',
				'selector'				=> '{{WRAPPER}} .pp-image-scroll-container .pp-image-scroll-image img',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->start_controls_tab('image_style_tab_hover',
            [
				'label'					=> __('Hover', 'powerpack'),
            ]
        );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
				'name'					=> 'container_box_shadow_hover',
				'selector'				=> '{{WRAPPER}} .pp-image-scroll-wrap:hover',
            ]
        );
        
        $this->add_group_control(
            Group_Control_Css_Filter::get_type(),
            [
				'name'					=> 'css_filters_hover',
				'selector'				=> '{{WRAPPER}} .pp-image-scroll-container .pp-image-scroll-image img:hover',
            ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        /**
         * Style Tab: Overlay
         */
        $this->start_controls_section('overlay_style',
            [
				'label'					=> __('Overlay', 'powerpack'),
                'tab'					=> Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control('overlay',
            [
				'label'					=> __('Overlay','powerpack'),
				'type'					=> Controls_Manager::SWITCHER,
				'label_on'				=> __('Show','powerpack'),
				'label_off'				=> __('Hide','powerpack'),
                
            ]
        );
        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'                  => 'overlay_background',
				'types'            	    => [ 'classic','gradient' ],
				'selector'              => '{{WRAPPER}} .pp-image-scroll-overlay',
                'exclude'               => [
                    'image',
                ],
				'condition'				=> [
                    'overlay'  => 'yes'
                ]
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
       
        if ( $settings['link']['url'] != '' ) {
            $this->add_render_attribute( 'link', 'class', 'pp-image-scroll-link' );

            if( ! empty( $settings['link']['is_external'] ) ) {
                $this->add_render_attribute( 'link', 'target', "_blank" );
            }

            if( ! empty( $settings['link']['nofollow'] ) ) {
                $this->add_render_attribute( 'link', 'rel',  "nofollow" );
            }

            if( ! empty( $settings['link']['url'] ) ) {
                $this->add_render_attribute( 'link', 'href',  $link_url );
            }
        }
       
        if ( $settings['icon'] ) {
			$this->add_render_attribute( 'icon', 'class', [
				'pp-image-scroll-icon',
				'pp-mouse-scroll-' . $settings['direction_type'],
				$settings['icon'],
			] );
        }

        $this->add_render_attribute( [
			'container' => [
				'class' => 'pp-image-scroll-container'
			],
			'direction_type' => [
				'class' => ['pp-image-scroll-image', 'pp-image-scroll-'.$settings['direction_type']]
			]
		] );
        ?>
		<div class="pp-image-scroll-wrap">
			<div <?php echo $this->get_render_attribute_string('container'); ?>>
				<?php if ( $settings['icon'] ) { ?>
					<div class="pp-image-scroll-content">
						<span <?php echo $this->get_render_attribute_string('icon'); ?>></span>
					</div>
				<?php } ?>
				<div <?php echo $this->get_render_attribute_string('direction_type'); ?>>
					<?php if ( $settings['overlay'] == 'yes' ) { ?>
						<div class="pp-image-scroll-overlay">
					<?php } ?>
					<?php if ( ! empty( $link_url ) ) { ?>
							<a <?php echo $this->get_render_attribute_string('link'); ?>></a>
					<?php } ?>
					<?php if ( $settings['overlay'] == 'yes' ) { ?>
						</div> 
					<?php } ?>

					<?php echo Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
				</div>
			</div>
		</div>
        <?php
    }
    
    protected function _content_template() {
    ?>
        <#
            var direction = settings.direction_type,
                reverse = settings.reverse,
                url;
            
            if ( settings.icon ) {
            
                view.addRenderAttribute( 'icon', 'class', [
		   			'pp-image-scroll-icon',
					'pp-mouse-scroll-' + settings.direction_type,
		   			settings.icon
		   		] );
            
            }
            
            if ( settings.link.url ) {
                view.addRenderAttribute( 'link', 'class', 'pp-image-scroll-link' );
                url = settings.link.url;
                view.addRenderAttribute( 'link', 'href',  url );
            }
            
            view.addRenderAttribute( 'container', 'class', 'pp-image-scroll-container' );
            
            view.addRenderAttribute( 'direction_type', 'class', 'pp-image-scroll-image pp-image-scroll-' + direction );
        #>
        
        <div class="pp-image-scroll-wrap">
            <div {{{ view.getRenderAttributeString('container') }}}>
                <# if ( settings.icon ) { #>
                    <div class="pp-image-scroll-content">   
                        <span {{{ view.getRenderAttributeString('icon') }}}></span>
                    </div>
                <# } #>
                <div {{{ view.getRenderAttributeString('direction_type') }}}>
                    <# if( 'yes' == settings.overlay ) { #>
                        <div class="pp-image-scroll-overlay">
                    <# }
                    if ( settings.link.url ) { #>
                        <a {{{ view.getRenderAttributeString('link') }}}></a>
                    <# }
                    if( 'yes' == settings.overlay ) { #>
                        </div> 
                    <# } #>
						
					<#
					var image = {
						id: settings.image.id,
						url: settings.image.url,
						size: settings.image_size,
						dimension: settings.image_custom_dimension,
						model: view.getEditModel()
					};
					var image_url = elementor.imagesManager.getImageUrl( image );
					#>
					<img src="{{{ image_url }}}" />
                </div>
            </div>
        </div>
    <?php 
    }
    
}