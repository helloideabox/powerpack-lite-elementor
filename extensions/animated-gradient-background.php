<?php
namespace PowerpackElementsLite\Extensions;

// Powerpack Elements classes
use PowerpackElementsLite\Base\Extension_Base;

// Elementor classes
use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Animated Gradient Background Extension
 *
 * Adds Animated Gradient Background to sections
 *
 * @since 2.6.0
 */
class Extension_Animated_Gradient_Background extends Extension_Base {

	/**
	 * Is Common Extension
	 *
	 * Defines if the current extension is common for all element types or not
	 *
	 * @since 2.6.0
	 * @access protected
	 *
	 * @var bool
	 */
	protected $is_common = true;

	/**
	 * A list of scripts that the widgets is depended in
	 *
	 * @since 2.6.0
	 **/
	public function get_script_depends() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			return array(
				'pp-animated-gradient-bg',
			);
		}

		return [];
	}

	/**
	 * A list of styles that the extension is depended in
	 *
	 * @since 2.8.0
	 **/
	public function get_style_depends() {
		return array(
			'pp-extensions',
		);
	}

	/**
	 * The description of the current extension
	 *
	 * @since 2.6.0
	 **/
	public static function get_description() {
		return esc_html__( 'Add Animated Gradient Background to sections allowing you to show gradient Animated background for sections.', 'powerpack' );
	}

	/**
	 * Is disabled by default
	 *
	 * Return wether or not the extension should be disabled by default,
	 * prior to user actually saving a value in the admin page
	 *
	 * @access public
	 * @since 2.6.0
	 * @return bool
	 */
	public static function is_default_disabled() {
		return true;
	}

	/**
	 * Add Actions
	 *
	 * @since 0.1.0
	 *
	 * @access private
	 */
	protected function add_gradient_background_animation_sections( $element, $args ) {

		// The name of the section
		$section_name = 'section_powerpack_elements_background_effects';

		// Check if this section exists
		$section_exists = \Elementor\Plugin::instance()->controls_manager->get_control_from_stack( $element->get_unique_name(), $section_name );

		if ( ! is_wp_error( $section_exists ) ) {
			// We can't and should try to add this section to the stack
			return false;
		}

		$element->start_controls_section(
			$section_name,
			array(
				'tab'   => Controls_Manager::TAB_STYLE,
				'label' => esc_html__( 'PowerPack Background', 'powerpack' ),
			)
		);

		$element->end_controls_section();

	}

	/**
	 * Add common sections
	 *
	 * @since 2.6.0
	 *
	 * @access protected
	 */
	protected function add_common_sections_actions() {
		// Activate animated gradient background for sections
		add_action(
			'elementor/element/section/section_background/after_section_end',
			function( $element, $args ) {
				$this->add_gradient_background_animation_sections( $element, $args );
			},
			10,
			2
		);

		// Activate animated gradient background for containers
		add_action(
			'elementor/element/container/section_background/after_section_end',
			function( $element, $args ) {
				$this->add_gradient_background_animation_sections( $element, $args );
			},
			10,
			2
		);
	}

	/**
	 * Add Controls
	 *
	 * @since 2.6.0
	 *
	 * @access private
	 */
	private function add_controls( $element, $args ) {

		$element->add_control(
			'pp_animated_gradient_bg_heading',
			array(
				'label'              => esc_html__( 'Animated Gradient Background', 'powerpack' ),
				'type'               => Controls_Manager::HEADING,
				'default'            => '',
				'separator'          => 'before',
			)
		);

		$element->add_control(
			'pp_animated_gradient_bg_enable',
			[
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Enable Animated Gradient Background', 'powerpack' ),
				'default'      => '',
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'prefix_class' => 'pp-animated-gradient-bg-',
				'render_type'  => 'template',
			]
		);

		$element->add_control(
			'pp_animated_gradient_bg_angle',
			[
				'label'      => esc_html__( 'Angle', 'powerpack' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'deg' ],
				'range'      => [
					'deg' => [
						'min'  => -45,
						'max'  => 180,
						'step' => 2,
					],
				],
				'default'    => [
					'unit' => 'deg',
					'size' => -45,
				],
				'condition'  => [
					'pp_animated_gradient_bg_enable' => 'yes',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'pp_animated_gradient_bg_color',
			[
				'label' => esc_html__( 'Add Color', 'powerpack' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$element->add_control(
			'pp_animated_gradient_bg_color_list',
			[
				'label'       => esc_html__( 'Color', 'powerpack' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => 'Color {{{pp_animated_gradient_bg_color}}}',
				'show_label'  => true,

				'default'     => [
					[
						'pp_animated_gradient_bg_color' => '#F6AD1F',
					],
					[
						'pp_animated_gradient_bg_color' => '#F7496A',
					],
					[
						'pp_animated_gradient_bg_color' => '#565AD8',
					],
				],

				'condition'   => [
					'pp_animated_gradient_bg_enable' => 'yes',
				],
			]
		);
	}

	protected function render() {
		$settings = $element->get_settings();
	}

	/**
	 * Add Actions
	 *
	 * @since 2.6.0
	 *
	 * @access protected
	 */
	protected function add_actions() {

		// Activate controls for rows
		add_action(
			'elementor/element/section/section_powerpack_elements_background_effects/before_section_end',
			function( $element, $args ) {
				$this->add_controls( $element, $args );
			},
			10,
			2
		);

		// Activate controls for containers
		add_action(
			'elementor/element/container/section_powerpack_elements_background_effects/before_section_end',
			function( $element, $args ) {
				$this->add_controls( $element, $args );
			},
			10,
			2
		);

		add_action( 'elementor/frontend/section/before_render', array( $this, 'before_render' ), 10, 1 );
		add_action( 'elementor/frontend/container/before_render', array( $this, 'before_render' ), 10, 1 );

		add_action( 'elementor/section/print_template', array( $this, 'print_template' ), 10, 2 );
		add_action( 'elementor/container/print_template', array( $this, 'print_template' ), 10, 2 );
	}

	/**
	 * Render Animated Gradient Background output on the frontend.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.6.14
	 * @access public
	 * @param object $element for current element.
	 */
	public function before_render( $element ) {
		$settings  = $element->get_settings();

		if ( 'yes' === $settings['pp_animated_gradient_bg_enable'] ) {
			if ( ! \Elementor\Plugin::$instance->editor->is_edit_mode() || ! \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				wp_enqueue_script( 'pp-animated-gradient-bg' );
			}
		}

		if ( 'yes' === $settings['pp_animated_gradient_bg_enable'] ) {
			$angle = isset( $settings['pp_animated_gradient_bg_angle'] ) ? $settings['pp_animated_gradient_bg_angle']['size'] : '';
			$element->add_render_attribute( '_wrapper', 'data-angle', $angle . 'deg' );
			$gradient_color_list = $settings['pp_animated_gradient_bg_color_list'];
			foreach ( $gradient_color_list as $gradient_color ) {
				$color[] = $gradient_color['pp_animated_gradient_bg_color'];
			};
			$colors = implode( ',', $color );
			$element->add_render_attribute( '_wrapper', 'data-color', $colors );
		}
	}

	/**
	 * Render Animated Gradient Background output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.6.14
	 * @access public
	 * @param object $template for current template.
	 * @param object $widget for current widget.
	 */
	public function print_template( $template, $widget ) {
		if ( ! $template ) {
			return;
		}
		ob_start();
		$old_template = $template;
		?>
		<# if ( 'yes' === settings.pp_animated_gradient_bg_enable ) {

			color_list = settings.pp_animated_gradient_bg_color_list;
			angle = settings.pp_animated_gradient_bg_angle.size + 'deg';
			var color = [];
			var i = 0;
			_.each(color_list , function(color_list){
					color[i] = color_list.pp_animated_gradient_bg_color;
					i = i+1;
			});
			view.addRenderAttribute('_wrapper', 'data-color', color);
			var gradientColorEditor = 'linear-gradient( ' + angle + ',' + color + ' )';
			#>
			<div class="pp-animated-gradient-bg" data-angle="{{{ angle }}}deg" data-color="{{{ color }}}" style="background-image : {{{ gradientColorEditor }}}"></div>
		<# } #>
		<?php
		$animated_gradient_content = ob_get_contents();
		ob_end_clean();
		$template = $animated_gradient_content . $old_template;
		return $template;
	}
}
