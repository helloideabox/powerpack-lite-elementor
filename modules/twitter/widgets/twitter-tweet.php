<?php
namespace PowerpackElementsLite\Modules\Twitter\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Config;

// Elementor Classes
use Elementor\Controls_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Twitter Tweet Widget
 */
class Twitter_Tweet extends Powerpack_Widget {

	public function get_name() {
		return parent::get_widget_name( 'Twitter_Tweet' );
	}

	public function get_title() {
		return parent::get_widget_title( 'Twitter_Tweet' );
	}

	public function get_icon() {
		return parent::get_widget_icon( 'Twitter_Tweet' );
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
		return parent::get_widget_keywords( 'Twitter_Tweet' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the twitter tweet widget depended on.
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
			'section_tweet',
			array(
				'label' => esc_html__( 'Tweet', 'powerpack-lite-for-elementor' ),
			)
		);

		$this->add_control(
			'tweet_url',
			array(
				'label'       => esc_html__( 'Tweet URL', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => [
					'active'     => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'ai'          => [
					'active' => false,
				],
				'default'     => '',
			)
		);

		$this->add_control(
			'theme',
			array(
				'label'   => esc_html__( 'Theme', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => array(
					'light' => esc_html__( 'Light', 'powerpack-lite-for-elementor' ),
					'dark'  => esc_html__( 'Dark', 'powerpack-lite-for-elementor' ),
				),
			)
		);

		$this->add_control(
			'expanded',
			array(
				'label'        => esc_html__( 'Expanded', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'left'   => esc_html__( 'Left', 'powerpack-lite-for-elementor' ),
					'center' => esc_html__( 'Center', 'powerpack-lite-for-elementor' ),
					'right'  => esc_html__( 'Right', 'powerpack-lite-for-elementor' ),
				),
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
			'link_color',
			[
				'label'       => esc_html__( 'Link Color', 'powerpack-lite-for-elementor' ),
				'description' => esc_html__( 'Applies to links, mentions and hashtags inside the embed.', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Convert the site locale to a language tag the X/Twitter embed accepts.
	 *
	 * get_locale() returns WordPress locales such as `de_DE` or `pt_BR`, while the
	 * embed's data-lang expects BCP-47-style tags (`de`, `pt`, `zh-cn`). An
	 * unrecognised value is dropped and the embed silently falls back to English,
	 * leaving its UI chrome in the wrong language inside a translated page.
	 *
	 * @since x.x.x
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
		 * @since x.x.x
		 *
		 * @param string $locale Language tag derived from the site locale.
		 */
		return apply_filters( 'powerpack_twitter_embed_lang', $locale );
	}

	/**
	 * Normalize a tweet URL to a host the embed script can parse.
	 *
	 * The bundled assets/js/twitter-widgets.js predates the x.com rename and
	 * matches status URLs with a twitter.com-only pattern, so a URL copied from
	 * X's current UI is claimed by the scanner but yields no tweet ID and the
	 * embed silently never renders. twitter.com still redirects to x.com, and
	 * twitter.com is what the official oEmbed markup uses, so rewriting the host
	 * is safe for the fallback link as well.
	 *
	 * @since x.x.x
	 *
	 * @access protected
	 *
	 * @param string $url Tweet URL as entered by the author.
	 * @return string Tweet URL with a parseable host.
	 */
	protected function get_embed_url( $url ) {
		return preg_replace( '#^(https?://)(?:www\.)?x\.com/#i', '$1twitter.com/', $url );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'tweet',
			array(
				'data-theme' => esc_attr( $settings['theme'] ),
				'data-align' => esc_attr( $settings['alignment'] ),
				'data-lang'  => $this->get_embed_lang(),
			)
		);

		if ( ! empty( $settings['width']['size'] ) ) {
			$this->add_render_attribute( 'tweet', 'data-width', intval( $settings['width']['size'] ) );
		}

		if ( '' === $settings['expanded'] ) {
			$this->add_render_attribute( 'tweet', 'data-cards', 'hidden' );
		}

		if ( isset( $settings['link_color'] ) && ! empty( $settings['link_color'] ) ) {
			$this->add_render_attribute( 'tweet', 'data-link-color', esc_attr( $settings['link_color'] ) );
		}

		$url = ( $settings['tweet_url'] ) ? $this->get_embed_url( $settings['tweet_url'] ) : '';

		if ( $url ) {
			// The anchor is the only thing that exists until widgets.js swaps the
			// blockquote for its iframe — and all that exists if that script is
			// blocked. It must never render empty.
			$link_text = esc_html__( 'View post on X', 'powerpack-lite-for-elementor' );

			$this->add_render_attribute( 'tweet_link', 'href', esc_url( $url ) );
			?>
			<div class="pp-twitter-tweet" <?php $this->print_render_attribute_string( 'tweet' ); ?>>
				<blockquote class="twitter-tweet" <?php $this->print_render_attribute_string( 'tweet' ); ?>><a <?php $this->print_render_attribute_string( 'tweet_link' ); ?>><?php echo esc_html( $link_text ); ?></a></blockquote>
			</div>
			<?php
		}
	}
}
