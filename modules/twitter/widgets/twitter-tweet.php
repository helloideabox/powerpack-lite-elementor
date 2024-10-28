<?php
namespace PowerpackElementsLite\Modules\Twitter\Widgets;

use PowerpackElementsLite\Base\Powerpack_Widget;
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
			'jquery-cookie',
			'twitter-widgets',
			'pp-twiiter',
		);
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_tweet',
			array(
				'label' => esc_html__( 'Tweet', 'powerpack' ),
			)
		);

		$this->add_control(
			'tweet_url',
			array(
				'label'       => esc_html__( 'Tweet URL', 'powerpack' ),
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
			'expanded',
			array(
				'label'        => esc_html__( 'Expanded', 'powerpack' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'powerpack' ),
				'label_off'    => esc_html__( 'No', 'powerpack' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'alignment',
			array(
				'label'   => esc_html__( 'Alignment', 'powerpack' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'center',
				'options' => array(
					'left'   => esc_html__( 'Left', 'powerpack' ),
					'center' => esc_html__( 'Center', 'powerpack' ),
					'right'  => esc_html__( 'Right', 'powerpack' ),
				),
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
			'link_color',
			array(
				'label'   => esc_html__( 'Link Color', 'powerpack' ),
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
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute(
			'tweet',
			array(
				'data-theme' => esc_attr( $settings['theme'] ),
				'data-align' => esc_attr( $settings['alignment'] ),
				'data-lang'  => get_locale(),
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

		$url = ( $settings['tweet_url'] ) ? $settings['tweet_url'] : '';

		if ( $url ) {
			?>
			<div class="pp-twitter-tweet" <?php $this->print_render_attribute_string( 'tweet' ); ?>>
				<blockquote class="twitter-tweet" <?php $this->print_render_attribute_string( 'tweet' ); ?>><a href="<?php echo esc_url( $url ); ?>"></a></blockquote>
			</div>
			<?php
		}
	}
}
