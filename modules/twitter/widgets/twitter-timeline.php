<?php
namespace PowerpackElementsLite\Modules\Twitter\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Twitter Timeline Widget
 */
class Twitter_Timeline extends Powerpack_Widget {

	public function get_name() {
		return parent::get_widget_name( 'Twitter_Timeline' );
	}

	public function get_title() {
		return parent::get_widget_title( 'Twitter_Timeline' );
	}

	public function get_icon() {
		return parent::get_widget_icon( 'Twitter_Timeline' );
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
		return parent::get_widget_keywords( 'Twitter_Timeline' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the twitter timeline widget depended on.
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
			'jquery-cookie',
			'twitter-widgets',
			'pp-twiiter',
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_timeline',
			array(
				'label' => esc_html__( 'Timeline', 'powerpack' ),
			)
		);

		$this->add_control(
			'username',
			array(
				'label'   => esc_html__( 'User Name', 'powerpack' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'ai'      => [
					'active' => false,
				],
			)
		);

		$this->add_control(
			'theme',
			array(
				'label'   => esc_html__( 'Theme', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => array(
					'light' => esc_html__( 'Light', 'powerpack' ),
					'dark'  => esc_html__( 'Dark', 'powerpack' ),
				),
			)
		);

		$this->add_control(
			'show_replies',
			array(
				'label'        => esc_html__( 'Show Replies', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'    => esc_html__( 'Layout', 'powerpack' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => '',
				'options'  => array(
					'noheader'    => esc_html__( 'No Header', 'powerpack' ),
					'nofooter'    => esc_html__( 'No Footer', 'powerpack' ),
					'noborders'   => esc_html__( 'No Borders', 'powerpack' ),
					'transparent' => esc_html__( 'Transparent', 'powerpack' ),
					'noscrollbar' => esc_html__( 'No Scroll Bar', 'powerpack' ),
				),
				'multiple' => true,
			)
		);

		$this->add_control(
			'width',
			array(
				'label'      => esc_html__( 'Width', 'powerpack' ),
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
			'height',
			array(
				'label'      => esc_html__( 'Height', 'powerpack' ),
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
				'label'       => esc_html__( 'Tweet Limit', 'powerpack' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 1,
				'step'        => 1,
				'default'     => 3,
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'   => esc_html__( 'Link Color', 'powerpack' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'   => esc_html__( 'Border Color', 'powerpack' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
			)
		);

		$this->end_controls_section();

		$help_docs = PP_Config::get_widget_help_links( 'Twitter_Widget' );
		if ( ! empty( $help_docs ) ) {
			/**
			 * Content Tab: Docs Links
			 *
			 * @since 2.4.1
			 * @access protected
			 */
			$this->start_controls_section(
				'section_help_docs',
				[
					'label' => esc_html__( 'Help Docs', 'powerpack' ),
				]
			);

			$hd_counter = 1;
			foreach ( $help_docs as $hd_title => $hd_link ) {
				$this->add_control(
					'help_doc_' . $hd_counter,
					[
						'type'            => Controls_Manager::RAW_HTML,
						'raw'             => sprintf( '%1$s ' . $hd_title . ' %2$s', '<a href="' . $hd_link . '" target="_blank" rel="noopener">', '</a>' ),
						'content_classes' => 'pp-editor-doc-links',
					]
				);

				$hd_counter++;
			}

			$this->end_controls_section();
		}

	}

	protected function render() {
		$settings = $this->get_settings();

		$this->add_render_attribute(
			'timeline',
			array(
				'data-theme'        => esc_attr( $settings['theme'] ),
				'data-show-replies' => ( 'yes' === $settings['show_replies'] ) ? true : false,
			)
		);

		if ( ! empty( $settings['width']['size'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-width', intval( $settings['width']['size'] ) );
		}

		if ( ! empty( $settings['height']['size'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-height', intval( $settings['height']['size'] ) );
		}

		if ( isset( $settings['layout'] ) && ! empty( $settings['layout'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-chrome', implode( ' ', $settings['layout'] ) );
		}

		if ( ! empty( $settings['tweet_limit'] ) && absint( $settings['tweet_limit'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-tweet-limit', absint( $settings['tweet_limit'] ) );
		}

		if ( ! empty( $settings['link_color'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-link-color', esc_attr( $settings['link_color'] ) );
		}

		if ( ! empty( $settings['border_color'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-border-color', esc_attr( $settings['border_color'] ) );
		}

		$user = $settings['username'];
		?>
		<div class="pp-twitter-timeline" <?php $this->print_render_attribute_string( 'timeline' ); ?>>
			<a class="twitter-timeline" href="https://twitter.com/<?php echo esc_attr( $user ); ?>" <?php $this->print_render_attribute_string( 'timeline' ); ?>><?php esc_html_e( 'Tweets by', 'powerpack' ); ?> <?php echo esc_html( $user ); ?></a>
		</div>
		<?php
	}

	/**
	 * Render Twitter Timeline widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			view.addRenderAttribute( 'atts', {
				'data-theme': settings.theme,
				'data-show-replies': ( 'yes' == settings.show_replies ) ? 'true' : 'false',
				'link_color': settings.link_color,
				'data-width': settings.width.size,
				'data-height': settings.height.size,
				'data-chrome': settings.layout,
				'data-tweet-limit': settings.tweet_limit,
				'data-link-color': settings.link_color,
				'data-border-color': settings.border_color,
			});
		#>
		<div class="pp-twitter-timeline" {{{ view.getRenderAttributeString( 'atts' ) }}}>
			<a class="twitter-timeline" href="https://twitter.com/{{ settings.username }}" {{{ view.getRenderAttributeString( 'atts' ) }}}>Tweets by <# {{ settings.username }} #></a>
		</div>
		<?php
	}
}
