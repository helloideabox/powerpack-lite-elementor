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
		return [
			'pp-jquery-plugin',
			'twitter-widgets',
			'pp-twitter',
		];
	}

	public function has_widget_inner_wrapper(): bool {
		return ! PP_Helper::is_feature_active( 'e_optimized_markup' );
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_timeline',
			[
				'label' => esc_html__( 'Timeline', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'username',
			[
				'label'   => esc_html__( 'User Name', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'ai'      => [
					'active' => false,
				],
			]
		);

		/**
		 * Optional override for the timeline's link text and accessible name.
		 *
		 * @since 3.1.0
		 */
		$this->add_control(
			'timeline_label',
			[
				'label'       => esc_html__( 'Link Text', 'powerpack-lite-for-elementor' ),
				'description' => esc_html__( 'Shown when X/Twitter\'s script is blocked, and used to name the embedded timeline for screen readers. Leave empty to generate it from the user name.', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'theme',
			[
				'label'   => esc_html__( 'Theme', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => esc_html__( 'Light', 'powerpack-lite-for-elementor' ),
					'dark'  => esc_html__( 'Dark', 'powerpack-lite-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'show_replies',
			[
				'label'        => esc_html__( 'Show Replies', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'layout',
			[
				'label'    => esc_html__( 'Layout', 'powerpack-lite-for-elementor' ),
				'type'     => Controls_Manager::SELECT2,
				'default'  => '',
				'options'  => [
					'noheader'    => esc_html__( 'No Header', 'powerpack-lite-for-elementor' ),
					'nofooter'    => esc_html__( 'No Footer', 'powerpack-lite-for-elementor' ),
					'noborders'   => esc_html__( 'No Borders', 'powerpack-lite-for-elementor' ),
					'transparent' => esc_html__( 'Transparent', 'powerpack-lite-for-elementor' ),
					'noscrollbar' => esc_html__( 'No Scroll Bar', 'powerpack-lite-for-elementor' ),
				],
				'multiple' => true,
			]
		);

		$this->add_control(
			'width',
			[
				'label'      => esc_html__( 'Width', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => '',
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
			]
		);
		$this->add_control(
			'height',
			[
				'label'      => esc_html__( 'Height', 'powerpack-lite-for-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'unit' => 'px',
					'size' => '',
				],
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
				],
			]
		);

		$this->add_control(
			'tweet_limit',
			[
				'label'       => esc_html__( 'Tweet Limit', 'powerpack-lite-for-elementor' ),
				'description' => esc_html__( 'Renders the timeline statically. Leave this empty and the timeline keeps updating itself, with no way for a visitor to pause it.', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'label_block' => false,
				'min'         => 1,
				'step'        => 1,
				'default'     => 3,
			]
		);

		$this->add_control(
			'link_color',
			[
				'label'       => esc_html__( 'Link Color', 'powerpack-lite-for-elementor' ),
				'description' => esc_html__( 'Needs a 4.5:1 contrast ratio against the timeline background.', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
			]
		);

		$this->add_control(
			'border_color',
			[
				'label'   => esc_html__( 'Border Color', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Reduce an author-entered user name to a bare screen name.
	 *
	 * Authors type "@handle" or paste a full profile URL; both produce a 404 link and
	 * an embed the script cannot parse, leaving the broken fallback link on screen.
	 *
	 * @since 3.1.0
	 *
	 * @access protected
	 *
	 * @param string $value User name as entered by the author.
	 * @return string Bare screen name.
	 */
	protected function get_bare_username( $value ) {
		$value = trim( (string) $value );

		if ( '' === $value ) {
			return '';
		}

		if ( false !== strpos( $value, '/' ) ) {
			$path = wp_parse_url( $value, PHP_URL_PATH );

			if ( $path ) {
				$value = basename( untrailingslashit( $path ) );
			}
		}

		return ltrim( $value, '@' );
	}

	/**
	 * Convert the site locale to a language tag the X/Twitter embed accepts.
	 *
	 * get_locale() returns WordPress locales such as `de_DE`; the embed's data-lang
	 * expects `de`, or `zh-cn`/`zh-tw` for the Chinese variants. Without it the embed
	 * falls back to English chrome inside a translated page.
	 *
	 * @since 3.1.0
	 *
	 * @access protected
	 *
	 * @return string Language tag for the embed's data-lang attribute.
	 */
	protected function get_embed_lang() {
		$locale = strtolower( str_replace( '_', '-', get_locale() ) );

		// Regional variants the embed distinguishes; everything else uses the
		// primary subtag only.
		$regional = [ 'zh-cn', 'zh-tw' ];

		if ( ! in_array( $locale, $regional, true ) ) {
			$locale = strtok( $locale, '-' );
		}

		/**
		 * Filters the language tag passed to the X/Twitter embed.
		 *
		 * @since 3.1.0
		 *
		 * @param string $locale Language tag derived from the site locale.
		 */
		return apply_filters( 'powerpack_twitter_embed_lang', $locale );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$user = $this->get_bare_username( $settings['username'] );

		// A timeline with no account renders an anchor named only "Tweets by" that points
		// at Twitter's home page, and the embed can never replace it: the script requires
		// a screen name. So it is not rendered at all on the front end.
		if ( '' === $user ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="pp-twitter-timeline-notice elementor-alert elementor-alert-info">' .
					esc_html__( 'Add a user name to display this timeline.', 'powerpack-lite-for-elementor' ) .
					'</div>';
			}

			return;
		}

		$label = trim( (string) $settings['timeline_label'] );

		if ( '' === $label ) {
			/* translators: %s: X/Twitter user name. */
			$label = sprintf( __( 'Tweets by @%s', 'powerpack-lite-for-elementor' ), $user );
		}

		$this->add_render_attribute(
			'timeline-wrap',
			[
				'class'             => 'pp-twitter-timeline',
				// Twitter replaces the anchor with a cross-origin iframe titled only
				// "Twitter Timeline" — naming no account, and repeating verbatim when a
				// page carries more than one timeline — so the title is reapplied from JS.
				'data-iframe-title' => $label,
			]
		);

		$this->add_render_attribute(
			'timeline',
			[
				'class'             => 'twitter-timeline',
				'href'              => 'https://twitter.com/' . rawurlencode( $user ),
				'data-theme'        => $settings['theme'],
				'data-lang'         => $this->get_embed_lang(),
				'data-show-replies' => ( 'yes' === $settings['show_replies'] ) ? 'true' : 'false',
			]
		);

		if ( ! empty( $settings['width']['size'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-width', intval( $settings['width']['size'] ) );
		}

		if ( ! empty( $settings['height']['size'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-height', intval( $settings['height']['size'] ) );
		}

		if ( ! empty( $settings['layout'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-chrome', implode( ' ', $settings['layout'] ) );
		}

		if ( ! empty( $settings['tweet_limit'] ) && absint( $settings['tweet_limit'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-tweet-limit', absint( $settings['tweet_limit'] ) );
		}

		if ( ! empty( $settings['link_color'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-link-color', $settings['link_color'] );
		}

		if ( ! empty( $settings['border_color'] ) ) {
			$this->add_render_attribute( 'timeline', 'data-border-color', $settings['border_color'] );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'timeline-wrap' ); ?>>
			<a <?php $this->print_render_attribute_string( 'timeline' ); ?>><?php echo esc_html( $label ); ?></a>
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
			var user = ( settings.username || '' ).trim().replace( /^@+/, '' ),
				label = ( settings.timeline_label || '' ).trim();

			if ( '' === user ) { #>
				<div class="pp-twitter-timeline-notice elementor-alert elementor-alert-info">
					<?php echo esc_html__( 'Add a user name to display this timeline.', 'powerpack-lite-for-elementor' ); ?>
				</div>
			<# } else {
				if ( '' === label ) {
					label = '<?php echo esc_js( __( 'Tweets by', 'powerpack-lite-for-elementor' ) ); ?> @' + user;
				}

				view.addRenderAttribute( 'wrap', {
					'class': 'pp-twitter-timeline',
					'data-iframe-title': label
				} );

				view.addRenderAttribute( 'atts', {
					'class': 'twitter-timeline',
					'href': 'https://twitter.com/' + encodeURIComponent( user ),
					'data-theme': settings.theme,
					'data-lang': '<?php echo esc_js( $this->get_embed_lang() ); ?>',
					'data-show-replies': ( 'yes' === settings.show_replies ) ? 'true' : 'false',
					'data-width': settings.width.size,
					'data-height': settings.height.size,
					'data-chrome': ( settings.layout || [] ).join( ' ' ),
					'data-tweet-limit': settings.tweet_limit,
					'data-link-color': settings.link_color,
					'data-border-color': settings.border_color
				} );
			#>
			<div {{{ view.getRenderAttributeString( 'wrap' ) }}}>
				<a {{{ view.getRenderAttributeString( 'atts' ) }}}>{{ label }}</a>
			</div>
		<# } #>
		<?php
	}
}
