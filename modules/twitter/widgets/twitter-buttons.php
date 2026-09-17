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
 * Twitter Buttons Widget
 */
class Twitter_Buttons extends Powerpack_Widget {

	public function get_name() {
		return parent::get_widget_name( 'Twitter_Buttons' );
	}

	public function get_title() {
		return parent::get_widget_title( 'Twitter_Buttons' );
	}

	public function get_icon() {
		return parent::get_widget_icon( 'Twitter_Buttons' );
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
		return parent::get_widget_keywords( 'Twitter_Buttons' );
	}

	protected function is_dynamic_content(): bool {
		return false;
	}

	/**
	 * Retrieve the list of scripts the Twitter Buttons widget depended on.
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
			'section_buttons',
			[
				'label' => esc_html__( 'Buttons', 'powerpack-lite-for-elementor' ),
			]
		);

		$this->add_control(
			'button_type',
			[
				'label'   => esc_html__( 'Type', 'powerpack-lite-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'share',
				'options' => [
					'share'   => esc_html__( 'Share', 'powerpack-lite-for-elementor' ),
					'follow'  => esc_html__( 'Follow', 'powerpack-lite-for-elementor' ),
					'mention' => esc_html__( 'Mention', 'powerpack-lite-for-elementor' ),
					'hashtag' => esc_html__( 'Hashtag', 'powerpack-lite-for-elementor' ),
					'message' => esc_html__( 'Message', 'powerpack-lite-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'profile',
			[
				'label'     => esc_html__( 'Profile URL or Username', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'ai'        => [
					'active' => false,
				],
				'condition' => [
					'button_type' => [ 'follow', 'mention', 'message' ],
				],
			]
		);

		$this->add_control(
			'recipient_id',
			[
				'label'     => esc_html__( 'Recipient ID', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'ai'        => [
					'active' => false,
				],
				'condition' => [
					'button_type' => 'message',
				],
			]
		);

		$this->add_control(
			'default_text',
			[
				'label'     => esc_html__( 'Default Text', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => [
					'button_type' => 'message',
				],
			]
		);

		$this->add_control(
			'hashtag_url',
			[
				'label'     => esc_html__( 'Hashtag URL or #hashtag', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'ai'        => [
					'active' => false,
				],
				'condition' => [
					'button_type' => 'hashtag',
				],
			]
		);

		$this->add_control(
			'via',
			[
				'label'     => esc_html__( 'Via (twitter handler)', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'ai'        => [
					'active' => false,
				],
				'condition' => [
					'button_type' => [ 'share', 'mention', 'hashtag' ],
				],
			]
		);

		$this->add_control(
			'share_text',
			[
				'label'     => esc_html__( 'Custom Share Text', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => [
					'button_type' => [ 'share', 'mention', 'hashtag' ],
				],
			]
		);

		$this->add_control(
			'share_url',
			[
				'label'     => esc_html__( 'Custom Share URL', 'powerpack-lite-for-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'ai'        => [
					'active' => false,
				],
				'condition' => [
					'button_type' => [ 'share', 'mention', 'hashtag' ],
				],
			]
		);

		/**
		 * Visible fallback text, which doubles as the button's accessible name.
		 *
		 * @since 3.1.0
		 */
		$this->add_control(
			'button_label',
			[
				'label'       => esc_html__( 'Button Text', 'powerpack-lite-for-elementor' ),
				'description' => esc_html__( 'Shown when Twitter\'s script is blocked, and used to name the button for screen readers. Leave empty to generate a descriptive label from the settings above.', 'powerpack-lite-for-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => '',
				'label_block' => true,
			]
		);

		$this->add_control(
			'show_count',
			[
				'label'        => esc_html__( 'Show Count', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'button_type' => 'follow',
				],
			]
		);

		$this->add_control(
			'large_button',
			[
				'label'        => esc_html__( 'Large Button', 'powerpack-lite-for-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'powerpack-lite-for-elementor' ),
				'label_off'    => esc_html__( 'No', 'powerpack-lite-for-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Normalize a handle or hashtag that may have been entered as a full URL.
	 *
	 * The controls accept "acme", "@acme" or "https://twitter.com/acme", so the raw
	 * value cannot be concatenated into a URL as-is. A "#hashtag" value is especially
	 * damaging: the "#" terminates the query string, leaving button_hashtag empty.
	 *
	 * @since 3.1.0
	 * @access private
	 *
	 * @param string $value  Raw control value.
	 * @param string $prefix Leading character to strip, '@' or '#'.
	 * @return string Bare handle or hashtag, without its prefix.
	 */
	private function get_bare_value( $value, $prefix ) {
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

		return ltrim( $value, $prefix );
	}

	/**
	 * Get the descriptive accessible name for the button.
	 *
	 * Twitter's fallback markup is a bare verb ("Follow", "Hashtag"), which conveys no
	 * link purpose when several buttons share a page.
	 *
	 * @since 3.1.0
	 * @access private
	 *
	 * @param string $type    Selected button type.
	 * @param string $handle  Normalized screen name.
	 * @param string $hashtag Normalized hashtag.
	 * @return string Translated label.
	 */
	private function get_default_button_label( $type, $handle, $hashtag ) {
		switch ( $type ) {
			case 'follow':
				return $handle
					/* translators: %s: Twitter account handle, including the @ sign. */
					? sprintf( esc_html__( 'Follow %s on Twitter', 'powerpack-lite-for-elementor' ), '@' . $handle )
					: esc_html__( 'Follow on Twitter', 'powerpack-lite-for-elementor' );

			case 'mention':
				return $handle
					/* translators: %s: Twitter account handle, including the @ sign. */
					? sprintf( esc_html__( 'Mention %s on Twitter', 'powerpack-lite-for-elementor' ), '@' . $handle )
					: esc_html__( 'Mention on Twitter', 'powerpack-lite-for-elementor' );

			case 'hashtag':
				return $hashtag
					/* translators: %s: hashtag, including the # sign. */
					? sprintf( esc_html__( 'Tweet %s on Twitter', 'powerpack-lite-for-elementor' ), '#' . $hashtag )
					: esc_html__( 'Tweet a hashtag on Twitter', 'powerpack-lite-for-elementor' );

			case 'message':
				return $handle
					/* translators: %s: Twitter account handle, including the @ sign. */
					? sprintf( esc_html__( 'Send a direct message to %s on Twitter', 'powerpack-lite-for-elementor' ), '@' . $handle )
					: esc_html__( 'Send a direct message on Twitter', 'powerpack-lite-for-elementor' );

			default:
				return esc_html__( 'Share on Twitter', 'powerpack-lite-for-elementor' );
		}
	}

	/**
	 * Convert the site locale to a language code Twitter's widget API accepts.
	 *
	 * get_locale() returns "fr_FR"; the widget API expects "fr", or "zh-cn"/"zh-tw" for
	 * the Chinese variants. Unrecognized values silently render the button in English.
	 *
	 * @since 3.1.0
	 * @access private
	 *
	 * @return string Language code.
	 */
	private function get_widget_lang() {
		$locale = strtolower( str_replace( '_', '-', get_locale() ) );

		if ( 0 === strpos( $locale, 'zh-' ) ) {
			return ( false !== strpos( $locale, 'tw' ) || false !== strpos( $locale, 'hk' ) ) ? 'zh-tw' : 'zh-cn';
		}

		return strtok( $locale, '-' );
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$type         = $settings['button_type'];
		$handle       = $this->get_bare_value( $settings['profile'], '@' );
		$hashtag      = $this->get_bare_value( $settings['hashtag_url'], '#' );
		$recipient_id = trim( (string) $settings['recipient_id'] );

		// A button whose target is missing still announces as "Follow" or "Hashtag"
		// while going nowhere useful, so it is not rendered at all on the front end.
		$requires = [
			'follow'  => $handle,
			'mention' => $handle,
			'hashtag' => $hashtag,
			'message' => $recipient_id,
		];

		if ( isset( $requires[ $type ] ) && '' === $requires[ $type ] ) {
			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
				echo '<div class="pp-twitter-buttons-notice elementor-alert elementor-alert-info">' .
					esc_html__( 'Add the profile, hashtag or recipient ID to display this button.', 'powerpack-lite-for-elementor' ) .
					'</div>';
			}

			return;
		}

		$label = trim( (string) $settings['button_label'] );

		if ( '' === $label ) {
			$label = $this->get_default_button_label( $type, $handle, $hashtag );
		}

		switch ( $type ) {
			case 'follow':
				$url   = 'https://twitter.com/' . rawurlencode( $handle );
				$class = 'twitter-follow-button';
				break;

			case 'mention':
				$url   = add_query_arg( 'screen_name', rawurlencode( $handle ), 'https://twitter.com/intent/tweet' );
				$class = 'twitter-mention-button';
				break;

			case 'hashtag':
				$url   = add_query_arg( 'button_hashtag', rawurlencode( $hashtag ), 'https://twitter.com/intent/tweet' );
				$class = 'twitter-hashtag-button';
				break;

			case 'message':
				$args = [ 'recipient_id' => rawurlencode( $recipient_id ) ];

				if ( ! empty( $settings['default_text'] ) ) {
					$args['text'] = rawurlencode( $settings['default_text'] );
				}

				$url   = add_query_arg( $args, 'https://twitter.com/messages/compose' );
				$class = 'twitter-dm-button';
				break;

			default:
				$url   = 'https://twitter.com/share';
				$class = 'twitter-share-button';
				break;
		}

		$this->add_render_attribute( 'buttons', [
			'class'             => 'pp-twitter-buttons',
			// Twitter replaces the anchor with a cross-origin iframe titled only
			// "Twitter Tweet Button" or "Twitter Follow Button", never naming the
			// account or hashtag, so the accessible name is reapplied from JS.
			'data-iframe-title' => $label,
		] );

		$this->add_render_attribute( 'tweet', [
			'href'      => $url,
			'class'     => $class,
			'data-lang' => $this->get_widget_lang(),
		] );

		if ( 'yes' === $settings['large_button'] ) {
			$this->add_render_attribute( 'tweet', 'data-size', 'large' );
		}

		if ( 'share' === $type || 'mention' === $type || 'hashtag' === $type ) {
			$share_attrs = [
				'data-via'  => 'via',
				'data-text' => 'share_text',
				'data-url'  => 'share_url',
			];

			foreach ( $share_attrs as $attr => $key ) {
				if ( ! empty( $settings[ $key ] ) ) {
					$this->add_render_attribute( 'tweet', $attr, $settings[ $key ] );
				}
			}
		}

		if ( 'follow' === $type ) {
			$this->add_render_attribute( 'tweet', 'data-show-count', ( 'yes' === $settings['show_count'] ) ? 'true' : 'false' );
		}

		if ( 'message' === $type ) {
			$this->add_render_attribute( 'tweet', 'data-screen-name', $handle );
		}
		?>
		<div <?php $this->print_render_attribute_string( 'buttons' ); ?>>
			<a <?php $this->print_render_attribute_string( 'tweet' ); ?>><?php echo esc_html( $label ); ?></a>
		</div>
		<?php
	}

	/**
	 * Render Twitter Buttons widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.4.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
			var bare = function ( value, prefix ) {
					value = ( value || '' ).trim();

					if ( value.indexOf( '/' ) > -1 ) {
						value = value.replace( /\/+$/, '' ).split( '/' ).pop();
					}

					return value.replace( new RegExp( '^\\' + prefix + '+' ), '' );
				},
				type     = settings.button_type,
				handle   = bare( settings.profile, '@' ),
				hashtag  = bare( settings.hashtag_url, '#' ),
				recipient = ( settings.recipient_id || '' ).trim(),
				requires = {
					follow: handle,
					mention: handle,
					hashtag: hashtag,
					message: recipient
				},
				labels = {
					share:   '<?php echo esc_js( __( 'Share on Twitter', 'powerpack-lite-for-elementor' ) ); ?>',
					follow:  handle ? '<?php echo esc_js( __( 'Follow', 'powerpack-lite-for-elementor' ) ); ?> @' + handle + ' <?php echo esc_js( __( 'on Twitter', 'powerpack-lite-for-elementor' ) ); ?>' : '<?php echo esc_js( __( 'Follow on Twitter', 'powerpack-lite-for-elementor' ) ); ?>',
					mention: handle ? '<?php echo esc_js( __( 'Mention', 'powerpack-lite-for-elementor' ) ); ?> @' + handle + ' <?php echo esc_js( __( 'on Twitter', 'powerpack-lite-for-elementor' ) ); ?>' : '<?php echo esc_js( __( 'Mention on Twitter', 'powerpack-lite-for-elementor' ) ); ?>',
					hashtag: hashtag ? '<?php echo esc_js( __( 'Tweet', 'powerpack-lite-for-elementor' ) ); ?> #' + hashtag + ' <?php echo esc_js( __( 'on Twitter', 'powerpack-lite-for-elementor' ) ); ?>' : '<?php echo esc_js( __( 'Tweet a hashtag on Twitter', 'powerpack-lite-for-elementor' ) ); ?>',
					message: handle ? '<?php echo esc_js( __( 'Send a direct message to', 'powerpack-lite-for-elementor' ) ); ?> @' + handle + ' <?php echo esc_js( __( 'on Twitter', 'powerpack-lite-for-elementor' ) ); ?>' : '<?php echo esc_js( __( 'Send a direct message on Twitter', 'powerpack-lite-for-elementor' ) ); ?>'
				},
				classes = {
					share: 'twitter-share-button',
					follow: 'twitter-follow-button',
					mention: 'twitter-mention-button',
					hashtag: 'twitter-hashtag-button',
					message: 'twitter-dm-button'
				},
				urls = {
					share:   'https://twitter.com/share',
					follow:  'https://twitter.com/' + encodeURIComponent( handle ),
					mention: 'https://twitter.com/intent/tweet?screen_name=' + encodeURIComponent( handle ),
					hashtag: 'https://twitter.com/intent/tweet?button_hashtag=' + encodeURIComponent( hashtag ),
					message: 'https://twitter.com/messages/compose?recipient_id=' + encodeURIComponent( recipient ) + ( settings.default_text ? '&text=' + encodeURIComponent( settings.default_text ) : '' )
				},
				label = ( settings.button_label || '' ).trim() || labels[ type ] || labels.share;
		#>
		<# if ( 'undefined' !== typeof requires[ type ] && '' === requires[ type ] ) { #>
			<div class="pp-twitter-buttons-notice elementor-alert elementor-alert-info">
				<?php echo esc_html__( 'Add the profile, hashtag or recipient ID to display this button.', 'powerpack-lite-for-elementor' ); ?>
			</div>
		<# } else {
			view.addRenderAttribute( 'buttons', {
				'class': 'pp-twitter-buttons',
				'data-iframe-title': label
			} );

			view.addRenderAttribute( 'atts', {
				'href': urls[ type ] || urls.share,
				'class': classes[ type ] || classes.share,
				'data-lang': '<?php echo esc_js( $this->get_widget_lang() ); ?>'
			} );

			if ( 'yes' === settings.large_button ) {
				view.addRenderAttribute( 'atts', 'data-size', 'large' );
			}

			if ( 'share' === type || 'mention' === type || 'hashtag' === type ) {
				if ( settings.via ) {
					view.addRenderAttribute( 'atts', 'data-via', settings.via );
				}
				if ( settings.share_text ) {
					view.addRenderAttribute( 'atts', 'data-text', settings.share_text );
				}
				if ( settings.share_url ) {
					view.addRenderAttribute( 'atts', 'data-url', settings.share_url );
				}
			}

			if ( 'follow' === type ) {
				view.addRenderAttribute( 'atts', 'data-show-count', ( 'yes' === settings.show_count ) ? 'true' : 'false' );
			}

			if ( 'message' === type ) {
				view.addRenderAttribute( 'atts', 'data-screen-name', handle );
			}
		#>
			<div {{{ view.getRenderAttributeString( 'buttons' ) }}}>
				<a {{{ view.getRenderAttributeString( 'atts' ) }}}>{{{ label }}}</a>
			</div>
		<# } #>
		<?php
	}
}
