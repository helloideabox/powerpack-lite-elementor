<?php
namespace PowerpackElementsLite\Modules\Twitter\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Twitter Grid Widget
 *
 * Deprecated. Twitter retired the embedded grid display type in 2019 and its
 * widgets.js has rendered these embeds as a single column timeline ever since,
 * logging a deprecation notice to the console each time. Nothing on this side
 * can bring the grid layout back, so the widget is hidden from the panel and
 * kept only so pages that already use it keep rendering. Use Twitter Timeline.
 *
 * @see https://twittercommunity.com/t/update-on-the-embedded-grid-display-type/119564
 */
class Twitter_Grid extends Powerpack_Widget {

	public function get_name() {
		return parent::get_widget_name( 'Twitter_Grid' );
	}

	public function get_title() {
		return parent::get_widget_title( 'Twitter_Grid' );
	}

	public function get_icon() {
		return parent::get_widget_icon( 'Twitter_Grid' );
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
		return parent::get_widget_keywords( 'Twitter_Grid' );
	}

	/**
	 * Hide the widget from the Elementor panel.
	 *
	 * Deprecated widgets stay registered so existing elements keep rendering and
	 * stay editable, but cannot be dragged onto a new page.
	 *
	 * @since 3.1.0
	 *
	 * @return bool
	 */
	public function show_in_panel() {
		return false;
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the twitter embedded grid widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return array(
			'pp-jquery-plugin',
			'twitter-widgets',
			'pp-twitter',
		);
	}

	public function has_widget_inner_wrapper(): bool {
		return ! PP_Helper::is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_grid',
			array(
				'label' => esc_html__( 'Grid', 'powerpack-lite-for-elementor' ),
			)
		);

		$this->add_deprecation_message(
			'3.1.0',
			esc_html__( 'Twitter no longer supports grid embeds and renders them as a timeline. This widget is deprecated and will be removed in a future version — use the Twitter Timeline widget instead.', 'powerpack-lite-for-elementor' ),
			PP_Helper::get_widget_name( 'Twitter_Timeline' )
		);

		$this->add_control(
			'url',
			array(
				'label'   => esc_html__( 'Collection URL', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'ai'      => [
					'active' => false,
				],
			)
		);

		$this->add_control(
			'footer',
			array(
				'label'        => esc_html__( 'Show Footer?', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'width',
			array(
				'label'      => esc_html__( 'Width', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => array(
					'unit' => 'px',
					'size' => '',
				),
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 100,
						'max' => 1000,
					),
				),
			)
		);

		$this->add_control(
			'tweet_limit',
			array(
				'label'       => esc_html__( 'Tweet Limit', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 1,
				'step'        => 1,
				'default'     => 3,
			)
		);

		$this->end_controls_section();


	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		/*
		 * Without a collection URL there is nothing for widgets.js to upgrade,
		 * and the anchor left behind is a link to the current page carrying a
		 * Twitter referrer parameter. The console notice that made this widget
		 * worth looking at was reported from an embed in exactly that state.
		 */
		if ( empty( $settings['url'] ) ) {
			return;
		}

		/*
		 * 'twitter-timeline' rather than the 'twitter-grid' class this widget is
		 * named after: widgets.js upgrades both to the same timeline, but only
		 * the grid class logs a deprecation notice to every visitor's console.
		 */
		$this->add_render_attribute(
			'grid',
			[
				'class' => 'twitter-timeline',
				'href'  => esc_url( $settings['url'] ) . '?ref_src=twsrc%5Etfw',
			]
		);

		if ( ! empty( $settings['tweet_limit'] ) ) {
			$this->add_render_attribute( 'grid', 'data-limit', absint( $settings['tweet_limit'] ) );
		}

		if ( 'yes' !== $settings['footer'] ) {
			$this->add_render_attribute( 'grid', 'data-chrome', 'nofooter' );
		}

		if ( ! empty( $settings['width']['size'] ) ) {
			$this->add_render_attribute( 'grid', 'data-width', intval( $settings['width']['size'] ) );
		}
		?>
		<div class="pp-twitter-grid">
			<a <?php $this->print_render_attribute_string( 'grid' ); ?>></a>
		</div>
		<?php
	}

	/**
	 * Render Twitter Grid widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<# if ( settings.url ) {
			view.addRenderAttribute( 'atts', {
				'class': 'twitter-timeline',
				'href': settings.url + '?ref_src=twsrc%5Etfw',
				'data-limit': ( settings.tweet_limit ) ? settings.tweet_limit : '',
				'data-chrome': ( 'yes' != settings.footer ) ? 'nofooter' : '',
				'data-width': settings.width.size,
			});
		#>
		<div class="pp-twitter-grid">
			<a {{{ view.getRenderAttributeString( 'atts' ) }}}></a>
		</div>
		<# } #>
		<?php
	}
}
